<?php


class Home extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, false, false, "Game");
    }

    public function index()
    {
        $this->view('home/main');
    }

    public function logout()
    {
        $user = Model::get('User');
        $user->logout();
        Redirect::to('/auth');
    }

}