<?php


class Home extends Controller
{

    public function index()
    {
        $user = $this->model('User');
        if(!$user->isLoggedIn()){
            Redirect::to('/');
        }
        
        $this->view('home');
    }

}