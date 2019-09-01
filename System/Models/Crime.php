<?php


class Crime
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('crimes', $fields)) {
            throw new Exception("There was a problem inserting the crime");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('crimes',$where, $fields)) {
            throw new Exception("There was a problem updating the crime");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('crimes', $fields)) {
            throw new Exception("There was a problem deleting the crime");
        }
    }

    public function find($crime)
    {
        if ($crime) {
            $fields = (is_numeric($crime)) ? 'id' : 'C_name';
            $data 	= $this->_db->get('crimes', array(
                array($fields, '=', $crime)
            ));
            if ($data->count()) {
                return $data->first();
            }
        }
        return false;
    }

    public function all()
    {
        $all = array();
        $data = $this->_db->get("crimes", array(
            array("id", '>', 0)
        ));
        if($data->count()){
            foreach($data->results() as $row){
                $all[] = array(
                    "id"    => $row->id,
                    "name"  => $row->C_name,
                    "time"  => $row->C_time,
                    "min"   => $row->C_minMoney,
                    "max"   => $row->C_maxMoney,
                    "exp"   => $row->C_exp,
                    "items" => $row->C_items
                );
            }
            return $all;
        }
        return false;
    }
}