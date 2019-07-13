<?php


class Settings
{
    public static function get($name)
    {
        $data = Database::getInstance()->get('settings', array(
            array('setting_name', '=', $name)
        ));
        if($data->count()){
            $data = $data->first();
            return $data->setting_value;
        }else{
            return "The {$name} is not exist in settings.";
        }
    }
}