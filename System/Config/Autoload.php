<?php
session_start();

require_once('Config.php');
require_once('Functions.php');


spl_autoload_register(function($class) {
    if(file_exists('System/Core/'.$class.'.php')){
        require_once ('System/Core/'.$class.'.php');
    }else{
        require_once('System/Plugins/Twig/Autoloader.php');
        Twig_Autoloader::register();
    }
});

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);