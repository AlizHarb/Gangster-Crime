<?php


class Location
{
    private $_db,
            $_data;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('locations', $fields)) {
            throw new Exception("There was a problem inserting the location");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('locations',$where, $fields)) {
            throw new Exception("There was a problem updating the location");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('locations', $fields)) {
            throw new Exception("There was a problem deleting the location");
        }
    }

    public function find($location)
    {
        if ($location) {
            $fields = (is_numeric($location)) ? 'id' : 'L_name';
            $data 	= $this->_db->get('locations', array(
                array($fields, '=', $location)
            ));
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function all($where)
    {
        $all = array();
        $data = $this->_db->get("locations", array($where));
        if($data->count()){
            foreach($data->results() as $row){
                $all[] = array(
                    "id"             => $row->id,
                    "name"           => $row->L_name,
                    "cost"           => $row->L_cost,
                    "time"           => $row->L_time,
                    "bullets"        => $row->L_bullets,
                    "bullets_cost"   => $row->L_bulletsCost
                );
            }
            return $all;
        }
        return false;
    }

    public function data()
    {
        return $this->_data;
    }

}