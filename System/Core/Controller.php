<?php


class Controller
{

    public function model($model)
    {
        require_once("System/Models/".$model.'.php');
        return new $model();
    }

    public function __construct($login = true, $jail = false, $hospital = false)
    {
        $user = $this->model('User');
        if($login && !$user->isLoggedIn()){
            Redirect::to('/');
        }
        if(!$login && $user->isLoggedIn()){
            Redirect::to('/home');
        }
        if($jail && $user->getTimer('prison') >= time()){
            Redirect::to('/prison');
        }
        if($hospital && $user->getTimer('hospital') >= time()){
            Redirect::to('/healthcare');
        }
    }

    public function view($view, $data = array())
    {
        $theme = "default";
        $loader = new Twig_Loader_Filesystem(Config::get('template/template_dir'));
        $twig = new Twig_Environment($loader, array(
            'cache' => false,
            //'cache' => Config::get('template/cache_dir'),
        ));

        $user = $this->model('User');
        $mail = $this->model('Mail');
        if($user->isLoggedIn()){
            $twig->addGlobal('user', $user);
            $twig->addGlobal('mail', $mail);
        }
        $twig->addGlobal('base_url', Config::get('website/base_url'));
        $twig->addGlobal('token', Token::generate());
        $twig->addGlobal('time', time());

        if(Session::exists('error')){
            $twig->addGlobal('error', Session::flash('error'));
        }
        if(Session::exists('success')){
            $twig->addGlobal('success', Session::flash('success'));
        }
        if(Session::exists('info')){
            $twig->addGlobal('info', Session::flash('info'));
        }

        echo $twig->render($theme.'/'.$view.'.html', $data);
    }

}