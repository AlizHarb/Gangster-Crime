<?php


class User
{

    private $_db,
            $_data,
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
                    $this->bank();
                } else {
                    self::logout();
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('gangsters', $fields)) {
            throw new Exception("There was a problem creating your account");
        }
    }

    public function set($fields = array())
    {
        if (!$this->_db->update('gangstersStats', 'id='.$this->data()->id, $fields)) {
            throw new Exception("There was a problem updating the user stats");
        }
    }

    public function find($user = null)
    {
        $settings = Model::get('Settings');

        if ($user) {
            $fields = (is_numeric($user)) ? 'id' : 'G_name';
            $data 	= $this->_db->get('gangsters', array(
                array($fields, '=', $user)
            ));
            if ($data->count()) {
                $this->_data = $data->first();

                $expIntoNextRank =  $this->stats()->GS_exp - $this->rank()->R_fromExp;
                $nextRank = Model::get('Rank');
                $nextRank->find($this->stats()->GS_rank+1);
                $expNeededForNextRank = $nextRank->data()->R_fromExp - $this->rank()->R_fromExp;

                $maxHealth = $this->rank()->R_health;

                $this->data()->name = $this->data()->G_name;
                $this->data()->user = '<a href="'.$settings::get('website_url').'profile/'.$this->data()->id.'">'.$this->data()->G_name.'</a>';
                $this->data()->rank = $this->rank()->R_name;
                $this->data()->exp = round($expIntoNextRank / $expNeededForNextRank * 100, 2);
                $this->data()->health = ($maxHealth - $this->stats()->GS_health) / $maxHealth * 100;
                $this->data()->cash = $this->stats()->GS_cash;
                $this->data()->bullets = $this->stats()->GS_bullets;
                $this->data()->credits = $this->stats()->GS_credits;
                $this->data()->location = $this->location()->L_name;
                if($this->stats()->GS_crew == 0){
                    $this->data()->crew = "Crewless";
                }else{
                    $this->data()->crew = $this->crew()->C_name;
                    $this->data()->crewProfile = '<a href="'.$settings::get('website_url').'crews/profile/'.$this->crew()->id.'">'.$this->crew()->C_name.'</a>';
                }

                if ($this->data()->health < 0) $this->data()->health = 0;
                return true;
            }
        }
        return false;
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

    public function logout()
    {
        Session::delete($this->_sessionName);
    }

    public function location()
    {
        $location = Model::get('Location');
        if($location->find($this->stats()->GS_location)){
            $location->find($this->stats()->GS_location);
            return $location->data();
        }else{
            $this->set(array(
                "GS_location"   => 1
            ));
            return self::location();
        }
    }

    public function crew()
    {
        $crew = Model::get('Crew');
        if($crew->find($this->stats()->GS_crew)){
            $crew->find($this->stats()->GS_crew);
            return $crew->data();
        }
        return false;
    }

    public function rank()
    {
        $rank = Model::get('Rank');
        $next = Model::get('Rank');
        $mail = Model::get('Mail');

        if($rank->find($this->stats()->GS_rank)){
            $rank->find($this->stats()->GS_rank);
            if($rank->check($this->stats()->GS_rank, $this->stats()->GS_exp)){
                $this->set(array(
                    "GS_rank"   => $this->stats()->GS_rank + 1
                ));
                $next->find($this->stats()->GS_rank+1);
                $mail->create(array(
                    "M_toUser"      => $this->data()->id,
                    "M_fromUser"    => 0,
                    "M_text"        => "You have been promoted to <b>".$next->data()->R_name."</b>. Keep up the good work!",
                    "M_date"        => date('Y-m-d H:i:s')
                ));
            }
            return $rank->data();
        }else{
            $this->set(array(
                "GS_rank"   => 1,
                "GS_exp"    => 0
            ));
            return self::rank();
        }
    }

    public function timer($name, $update = null)
    {
        $timer = Model::get('Timer');
        if($timer->find($this->data()->id, $name)){
            if(isset($update)){
                $timer->update('id='.$this->data()->id.' and GT_name="'.$name.'"', array(
                    "GT_time"   => time()+$update
                ));
            }
            return $timer->find($this->data()->id, $name)->GT_time;
        }else{
            $timer->create(array(
                "id"        => $this->data()->id,
                "GT_name"   => $name,
                "GT_time"   => 0
            ));
            return self::timer($name);
        }
    }

    public function bank()
    {
        $mail = Model::get('Mail');
        if($this->timer('bank') <= time() && $this->stats()->GS_bank > 0){
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
            $this->timer('bank', 0);
        }
    }

    public function stats()
    {
        $stats = $this->_db->get("gangstersStats", array(
            array('id', '=', $this->data()->id)
        ));
        if($stats->count()){
            return $stats->first();
        }else{
            $this->_db->insert('gangstersStats', array(
                "id"    => $this->data()->id
            ));
            return self::stats();
        }
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}