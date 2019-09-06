<?php


class Settings
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('settings',$where, $fields)) {
            throw new Exception("There was a problem updating the website settings");
        }
    }

    public static function get($name)
    {
        $data = Database::getInstance()->get('settings', array(
            array('setting_name', '=', $name)
        ));
        if($data->count()){
            $data = $data->first();
            return $data->setting_value;
        }
        return false;
    }
}