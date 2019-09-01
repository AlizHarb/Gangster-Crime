<?php


class Item
{
    private $_db,
            $_data;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('items', $fields)) {
            throw new Exception("There was a problem inserting the item");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('items',$where, $fields)) {
            throw new Exception("There was a problem updating the item");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('items', $fields)) {
            throw new Exception("There was a problem deleting the item");
        }
    }

    public function find($item)
    {
        if ($item) {
            $fields = (is_numeric($item)) ? 'id' : 'I_name';
            $data 	= $this->_db->get('items', array(
                array($fields, '=', $item)
            ));
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function all()
    {
        $all = array();
        $data = $this->_db->get("items", array(
            array("id", '>', 0)
        ));
        if($data->count()){
            foreach($data->results() as $row){
                $all[] = array(
                    "id"    => $row->id,
                    "name"  => $row->I_name,
                    "icon"  => $row->I_icon
                );
            }
            return $all;
        }
        return false;
    }

    public function userItems($userItems)
    {
        $items = array();
        foreach(explode('-', $userItems) as $key => $item){
            if($item > 0){
                $itemsQuery = $this->_db->get("items", array(
                    array('id', '=', $key + 1)
                ));
                if($itemsQuery->count()){
                    $itemsQuery = $itemsQuery->first();

                    $items[] = array(
                        "item"  => $itemsQuery->I_name,
                        "icon"  => $itemsQuery->I_icon,
                        "count" => $item
                    );
                }
            }
        }
        return $items;
    }

    public function data()
    {
        return $this->_data;
    }
}