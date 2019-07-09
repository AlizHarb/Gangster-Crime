<?php


class Home extends Controller
{

    public function __construct()
    {
        parent::__construct(true, false, false);
    }

    public function index()
    {
        $this->view('home');
    }

}