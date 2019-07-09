<?php


class Properties
{
    private $_db,
            $_data;

    public function __construct($property = null)
    {
        $this->_db 			= Database::getInstance();
        $this->production();

        if($property){
            $this->find($property);
        }
    }

    public function find($property)
    {
        if($property){
            $data 	= $this->_db->get('properties', array(
                array('id', '=', $property)
            ));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function production()
    {
        $bullets = 1000;
        $properties 	= $this->_db->get('properties', array(
            array('P_type', '=', 'bulletfactory'),
            array('P_time', '<=', time())
        ));
        foreach($properties->results() as $property){
            $location = $this->_db->get('locations', array(
                array('id', '=', $property->P_location)
            ));
            $location = $location->first();
            if($this->_db->update("locations", 'id = '.$property->P_location, array('L_bullets' => $location->L_bullets + $bullets))){
                $this->_db->update("properties", 'id = '.$property->id, array('P_time' => (time()+1*60*60)));
            }
        }
    }

    public function data()
    {
        return $this->_data;
    }
}