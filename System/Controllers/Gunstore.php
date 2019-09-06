<?php


class Gunstore extends Controller
{
    protected $_db;

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $this->view('store/main');
    }

}