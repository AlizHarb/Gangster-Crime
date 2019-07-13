<?php


class Location
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('locations', $fields)) {
            throw new Exception("There was a problem sending the location");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('locations', $where, $fields)) {
            throw new Exception("There was a problem updating the location");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('locations', $fields)) {
            throw new Exception("There was a problem deleting the location");
        }
    }

    public function getLocation($location)
    {
        (is_numeric($location) ? $field = "id" : $field = "L_name");
        $location = $this->_db->get('locations', array(
            array($field, '=', $location)
        ));
        if($location->count()){
            return $location->first();
        }else{
            return false;
        }
    }

    public function getUserLocation()
    {
        $user = Model::get('User');

        $location = $this->_db->get('locations', array(
            array('id', '=', $user->stats()->GS_location),
        ));
        if($location->count()){
            return $location->first();
        }else{
            $user->set(array(
                "GS_location" => 1
            ));
            return false;
        }
    }
}