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
        $item = Model::get('Item');

        $this->view('inventory/main', array(
            "items" => $item->userItems($user->stats()->GS_items)
        ));
    }
}