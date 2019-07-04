<?php


class Controller
{

    public function model($model)
    {
        require_once("System/Models/".$model.'.php');
        return new $model();
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
        if($user->isLoggedIn()){
            $twig->addGlobal('user', $user->data());
        }

        $twig->addGlobal('base_url', Config::get('website/base_url'));
        $twig->addGlobal('token', Token::generate());

        if(Session::exists('error')){
            $twig->addGlobal('error', Session::flash('error'));
        }
        if(Session::exists('success')){
            $twig->addGlobal('success', Session::flash('success'));
        }

        echo $twig->render($theme.'/'.$view.'.html', $data);
    }

}