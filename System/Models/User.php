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

    public function find($user = null)
    {
        if ($user) {
            $fields = (is_numeric($user)) ? 'id' : 'G_name';
            $data 	= $this->_db->get('gangsters', array($fields, '=', $user));
            if ($data->count()) {
                $this->_data = $data->first();
                $this->data()->name = $this->data()->G_name;
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

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
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