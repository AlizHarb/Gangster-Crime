<?php


class Crew
{
    private $_db,
            $_data;

    public function __construct()
    {
        $this->_db 	= Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('crews', $fields)) {
            throw new Exception("There was a problem creating the crew");
        }
    }

    public function update($fields = array())
    {
        $user = Model::get('User');
        if (!$this->_db->update('crews', "id = ".$user->stats()->GS_crew, $fields)) {
            throw new Exception("There was a problem updating the crew");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('crews', $fields)) {
            throw new Exception("There was a problem deleting the crew");
        }
    }

    public function find($crew = null)
    {
        if($crew){
            $fields = (is_numeric($crew)) ? 'id' : 'C_name';
            $data = $this->_db->get('crews', array(
                array($fields, '=', $crew)
            ));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
            return false;
        }
    }

    public function isBoss()
    {
        $user = Model::get('User');
        $crew = $this->_db->get('crews', array(
            array("C_boss", '=', $user->data()->id)
        ));
        if($crew->count()){
            return $crew->first();
        }
        return false;
    }

    public function data()
    {
        return $this->_data;
    }
}