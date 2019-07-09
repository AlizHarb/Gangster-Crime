<?php


class User
{

    private $_db,
            $_data,
            $_stats,
            $_sessionName,
            $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db 			= Database::getInstance();
        $this->_sessionName = Config::get('session/sessionName');
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                    $this->checkRank();
                    $this->checkBank();
                    $this->checkHospital();
                } else {
                    self::logout();
                }
            }
        } else {
            if($user !== 0){
                $this->find($user);
            }else{
                $this->find(0);
            }
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('gangsters', $fields)) {
            throw new Exception("There was a problem creating your account");
        }
    }

    public function set($field)
    {
        if(!$this->_db->update("gangstersStats", "id = ".$this->data()->id, $field)){
            throw new Exception("There was a problem updating the data.");
        }
    }

    public function find($user = null)
    {
        if($user == 0){
            $this->data()->profile = "System";
            $this->data()->name = "System";
            $this->data()->avatar = "public/assets/img/avatar_small.jpg";
        }else{
            if ($user) {
                $fields = (is_numeric($user)) ? 'id' : 'G_name';
                $data 	= $this->_db->get('gangsters', array(
                    array($fields, '=', $user)
                ));
                if ($data->count()) {
                    $this->_data = $data->first();

                    $stats 	= $this->_db->get('gangstersStats', array(
                        array("id", '=', $this->data()->id)
                    ));
                    if($stats->count()){
                        $this->_stats = $stats->first();
                    }else{
                        $this->_db->insert('gangstersStats', array(
                            "id" => $this->data()->id
                        ));
                    }

                    $nextRank = $this->_db->get("ranks", array(
                        array("id", '=', $this->stats()->GS_rank+1)
                    ));
                    $nextRank = $nextRank->first();
                    $expIntoNextRank =  $this->stats()->GS_exp - $this->getRank()->R_fromExp;
                    $expNeededForNextRank = $nextRank->R_fromExp - $this->getRank()->R_fromExp;
                    $this->data()->expPerc = round($expIntoNextRank / $expNeededForNextRank * 100, 2);

                    $maxHealth = $this->getRank()->R_health;
                    $this->data()->health = ($maxHealth - $this->stats()->GS_health) / $maxHealth * 100;
                    if ($this->data()->health < 0) $this->data()->health = 0;

                    $this->data()->hospitalTime = $this->getHospital()->H_planTime * 1000;

                    if($this->stats()->GS_crew == 0){
                        $this->data()->crew = "Crewless";
                    }else{
                        $this->data()->crew = "";
                    }
                    $this->data()->profile = "<a class='' href='profile/".$this->data()->G_name."'>".$this->data()->G_name."</a>";
                    $this->data()->name = $this->data()->G_name;
                    $this->data()->avatar = $this->data()->G_avatar;

                    return true;
                }
            }
        }
        return false;
    }

    public function getTimer($name)
    {
        $timer = $this->_db->get("gangstersTimer", array(
            array('id' ,'=', $this->data()->id)
        ));
        $timer = $timer->results();
        foreach($timer as $time){
            if($time->GT_name == $name){
                return $time->GT_time;
            }
        }
    }

    public function convertTimer($name)
    {
        $timer = $this->_db->get("gangstersTimer", array(
            array('id' ,'=', $this->data()->id)
        ));
        $timer = $timer->results();
        foreach($timer as $time){
            if($time->GT_name == $name){
                return $time->GT_time * 1000;
            }
        }
    }

    public function setTimer($name, $time)
    {
        if($this->getTimer($name) == 0){
            $this->_db->insert('gangstersTimer', array(
                "id"        => $this->data()->id,
                "GT_name"   => $name,
                "GT_time"   => 0
            ));
        }
        if(!$this->_db->update("gangstersTimer", "id = ".$this->data()->id." AND GT_name = '{$name}'", array("GT_time" => (time() + $time)))){
            throw new Exception("There was a problem updating the data.");
        }
    }

    public function login($username = null, $password = null)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
            if ($user) {
                if ($this->data()->G_password === Hash::make($password, $this->data()->G_salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    return true;
                }
            }
        }
        return false;
    }

    public function checkBank()
    {
        require_once("Mail.php");
        $mail = new Mail();
        if($this->getTimer('bank') <= time() && $this->stats()->GS_bank > 0){
            $interest = ($this->stats()->GS_bank < 1000000000 ? $interest = 0.30 : $interest = 0.01);
            if($interest == 0.30){
                $interestMsg = 3;
            }else{
                $interestMsg = 1;
            }
            $amount = ($this->stats()->GS_bank + ($this->stats()->GS_bank * $interest));
            $this->set(array(
                "GS_cash" => $this->stats()->GS_cash + ($this->stats()->GS_bank + ($this->stats()->GS_bank * $interest)),
                "GS_bank" => 0
            ));
            $mail->create(array(
                "M_toUser"      => $this->data()->id,
                "M_fromUser"    => 0,
                "M_text"        => "Your savings plan with Centro Bank has recently expired. Under the terms of your account, Centro Bank has paid you ".$interestMsg."% interest on the amount in your account at the time of your account expiry. <br><br>
                                    In total, you have been credited with $".number_format($amount)." by Centro Bank. <br><br>
                                    Thank you for using Centro Bank.",
                "M_date"        => date('Y-m-d H:i:s')
            ));
            $this->setTimer('bank', 0);
        }
    }

    public function checkRank()
    {
        require_once("Mail.php");
        $mail = new Mail();
        $nextRank = $this->_db->get("ranks", array(
            array("id", '=', $this->stats()->GS_rank+1)
        ));
        $nextRank = $nextRank->first();
        if($this->stats()->GS_exp >= $nextRank->R_fromExp){
            $this->set(array(
                'GS_rank' => $this->stats()->GS_rank + 1
            ));
            $mail->create(array(
                "M_toUser"      => $this->data()->id,
                "M_fromUser"    => 0,
                "M_text"        => "You have been promoted to ".$nextRank->R_name.". Keep up the good work!",
                "M_date"        => date('Y-m-d H:i:s')
            ));
        }
    }

    public function getRank()
    {
        $rank = $this->_db->get("ranks", array(
            array("id", '=', $this->stats()->GS_rank)
        ));
        return $rank->first();
    }

    public function getLocation()
    {
        $location = $this->_db->get("locations", array(
            array("id", '=', $this->stats()->GS_location)
        ));
        return $location->first();
    }

    public function setHospital($fields = array())
    {
        if(!$this->_db->update("healthcare", "H_user = ".$this->data()->id, $fields)){
            throw new Exception("There was a problem updating the data");
        }
    }

    public function getHospital()
    {
        $hospital = $this->_db->get("healthcare", array(
            array('H_user', '=', $this->data()->id)
        ));
        if($hospital->count()){
            return $hospital->first();
        }else{
            $this->_db->insert("healthcare", array(
                "H_user"     => $this->data()->id,
                "H_plan"     => 3,
                "H_planTime" => (time()+(7*24*60*60))
            ));
        }
        return false;
    }

    public function checkHospital()
    {
        if($this->getHospital()->H_planTime <= time()){
            $this->_db->update("healthcare", "H_user = ".$this->data()->id, array(
               "H_plan" => 0
            ));
            return true;
        }
        if(($this->getTimer('hospital') <= time()) && $this->getHospital()->H_plan <= 3){
            if($this->getHospital()->H_plan == 1){
                $parentage = 5;
            }elseif($this->getHospital()->H_plan == 3){
                $parentage = 10;
            }else{
                $parentage = 8;
            }
            $increaseHealth = ($this->getRank()->R_health * ($this->stats()->GS_hospitalHours * ($parentage / 100)));
            $increaseHealth = $this->stats()->GS_health - $increaseHealth;
            if($increaseHealth < 0)
                $increaseHealth = 0;

            $this->set(array(
                "GS_health"         => $increaseHealth,
                "GS_hospitalHours"    => 0
            ));
        }/*elseif($this->getHospital()->H_plan == 4 && $this->stats()->GS_health < 100){
            $increaseHealth = ($this->getRank()->R_health * ($this->stats()->GS_hospitalHours * 0.10));
            $increaseHealth = $this->stats()->GS_health - $increaseHealth;
            if($increaseHealth < 0)
                $increaseHealth = 0;

            $this->set(array(
                "GS_health" => $increaseHealth
            ));
        }*/
    }

    public function logout()
    {
        Session::delete($this->_sessionName);
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function data()
    {
        return $this->_data;
    }

    public function stats()
    {
        return $this->_stats;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}