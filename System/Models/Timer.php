<?php


class Timer
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('gangstersTimers', $fields)) {
            throw new Exception("There was a problem inserting the timer");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('gangstersTimers',$where, $fields)) {
            throw new Exception("There was a problem updating the timer");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('gangstersTimers', $fields)) {
            throw new Exception("There was a problem deleting your timer");
        }
    }

    public function find($id, $name)
    {
        $data = $this->_db->get("gangstersTimers", array(
            array('id', '=', $id),
            array('GT_name', '=', $name)
        ));
        if($data->count()){
             return $data->first();
        }
        return false;
    }

    public function prisoners()
    {
        $prisoners = array();
        $prison = $this->_db->get("gangstersTimers", array(
            array('GT_name', '=', "prison"),
            array('GT_time', '>=', time())
        ));
        if($prison->count()){
            foreach($prison as $row){
                $prisoners[] = array(
                    "user"  => $row->id,
                    "time"  => $row->GT_time
                );
            }
            return $prisoners;
        }
        return false;
    }
}