<?php


    class Inventory extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = $this->model('User');

        $items = array();
        foreach(explode('-', $user->stats()->GS_items) as $key => $item){
            if($item > 0){
                $itemsQuery = Database::getInstance()->get("items", array(
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