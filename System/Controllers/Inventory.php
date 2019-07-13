<?php


class Inventory extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = Model::get('User');

        $items = array();
        foreach(explode('-', $user->stats()->GS_items) as $key => $item){
            if($item > 0){
                $itemsQuery = $this->_db->get("items", array(
                    array('id', '=', $key + 1)
                ));
                if($itemsQuery->count()){
                    $itemsQuery = $itemsQuery->first();

                    $items[] = array(
                        "item"  => $itemsQuery->I_name,
                        "count" => $item
                    );
                }
            }
        }

        $this->view('inventory', array(
            "items" => $items
        ));
    }
}