<?php


class Rank
{
    private $_db,
            $_data;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('ranks', $fields)) {
            throw new Exception("There was a problem inserting the rank");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('ranks',$where, $fields)) {
            throw new Exception("There was a problem updating the rank");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('ranks', $fields)) {
            throw new Exception("There was a problem deleting your rank");
        }
    }

    public function find($rank)
    {
        if($rank){
            $fields = (is_numeric($rank)) ? 'id' : 'R_name';
            $data = $this->_db->get("ranks", array(
                array($fields, '=', $rank)
            ));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function check($rank, $now)
    {
        $next = Model::get('Rank');
        $next->find($rank+1);
        if($now >= $next->data()->R_fromExp){
            return true;
        }
        return false;
    }

    public function data()
    {
        return $this->_data;
    }
}