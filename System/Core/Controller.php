<?php


class Controller
{
    private $_db;

    public function __construct($login = true, $jail = false, $hospital = false)
    {
        $this->_db = Database::getInstance();

        $user = Model::get('User');
        if($login && !$user->isLoggedIn()){
            Redirect::to('auth');
        }
        if(!$login && $user->isLoggedIn()){
            Redirect::to('home');
        }
        if($jail && $user->timer('prison') >= time()){
            Redirect::to('prison');
        }
        if($hospital && $user->timer('hospital') >= time()){
            Redirect::to('healthcare');
        }
    }

    public function view($view, $data = array())
    {
        $settings = Model::get('Settings');
        $theme = $settings::get('website_theme');
        if(file_exists("Public/Views/".$theme)){
            $loader = new Twig_Loader_Filesystem(Config::get('template/template_dir'));
            $twig = new Twig_Environment($loader, array(
                'cache' => Config::get('template/cache'),
            ));
            require_once('System/Config/Menus.php');
            require_once('System/Config/Globals.php');

            echo $twig->render($theme.'/pages/'.$view.'.tpl', $data);
        }else{
            echo "<h1>The {$theme} theme is not exist in Views directory.</h1>";
        }
    }

}