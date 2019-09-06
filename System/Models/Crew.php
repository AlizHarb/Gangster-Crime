<?php


class Crew
{
    private $_db,
            $_data;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('crews', $fields)) {
            throw new Exception("There was a problem inserting the crew");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('crews',$where, $fields)) {
            throw new Exception("There was a problem updating the crew");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('crews', $fields)) {
            throw new Exception("There was a problem deleting the crew");
        }
    }

    public function find($crew)
    {
        if ($crew) {
            $fields = (is_numeric($crew)) ? 'id' : 'C_name';
            $data 	= $this->_db->get('crews', array(
                array($fields, '=', $crew)
            ));
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function allCrews()
    {
        $user = Model::get('User');

        $crews = array();
        $data = $this->_db->get('crews', array(
            array('id', '>', '0')
        ));
        foreach($data->results() as $crew){
            if($crew->C_boss > 0){
                $user->find($crew->C_boss);
                $boss = $user->data()->G_name;
            }

            if($crew->C_underboss > 0){
                $user->find($crew->C_underboss);
                $underboss = $user->data()->G_name;
            }else{
                $underboss = '-';
            }

            $members = $this->_db->get('gangstersStats', array(
                array('GS_crew', '=', $crew->id)
            ));
            $members = $members->count();

            $crews[] = array(
                "id"            => $crew->id,
                "name"          => $crew->C_name,
                "boss"          => $boss,
                "underboss"     => $underboss,
                "members"       => $members,
                "recruiting"    => $crew->C_recruiting,
                "size"          => $crew->C_size
            );
        }
        return $crews;
    }

    public function myOwnCrew()
    {
        $user = Model::get('User');

        $data = $this->_db->get('crews', array(
            array('C_boss', '=', $user->data()->id)
        ));
        if($data->count()){
            return $data->first();
        }
        return false;
    }

    public function crewNum()
    {
        $data = $this->_db->get('crews', array(
            array('id', '>', 1)
        ));
        return $data->count();
    }

    public function data()
    {
        return $this->_data;
    }

}