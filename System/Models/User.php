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

    public function set($field)
    {
        if(!$this->_db->update("gangstersStats", "id = ".$this->data()->id, $field)){
            throw new Exception("There was a problem updating the data.");
        }
    }

    public function find($user = null)
    {
        if ($user) {
            $fields = (is_numeric($user)) ? 'id' : 'G_name';
            $data 	= $this->_db->get('gangsters', array($fields, '=', $user));
            if ($data->count()) {
                $this->_data = $data->first();
                $stats 	= $this->_db->get('gangstersStats', array("id", '=', $this->data()->id));
                $this->_stats = $stats->first();

                if($this->stats()->GS_crew == 0){
                    $this->data()->crew = "Crewless";
                }else{
                    $this->data()->crew = "";
                }
                $this->data()->name = $this->data()->G_name;
                return true;
            }
        }
        return false;
    }

    public function getTimer($name)
    {
        $timer = $this->_db->get("gangstersTimer", array('id' ,'=', $this->data()->id));
        $timer = $timer->results();
        foreach($timer as $time){
            if($time->GT_name == $name){
                return $time->GT_time;
            }
        }
    }

    public function convertTimer($name)
    {
        $timer = $this->_db->get("gangstersTimer", array('id' ,'=', $this->data()->id));
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

    public function getLocation()
    {
        $location = $this->_db->get("locations", array("id", '=', $this->stats()->GS_location));
        return $location->first()->L_name;
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