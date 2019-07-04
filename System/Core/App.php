<?php


class App
{
    protected $controller = '';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        $url[0] = ucfirst($url[0]);

        if(empty($this->controller) && empty($url[0]) || isset($url[0]) && file_exists('System/Controllers/'.$url[0].'.php')){
            $this->controller = 'Auth';
            if(file_exists('System/Controllers/'.$url[0].'.php')){
                $this->controller = $url[0];
                unset($url[0]);
            }

            require_once('System/Controllers/'.$this->controller.'.php');
            $this->controller = new $this->controller;

            if(isset($url[1])){
                if(method_exists($this->controller, $url[1])){
                    $this->method = $url[1];
                    unset($url[1]);
                }
            }

            $this->params = $url ? array_values($url) : [];

            call_user_func_array([$this->controller, $this->method], $this->params);
        }else{
            die("<h1>The controller ".$url[0].' is not exist.</h1>');
        }
    }

    public function parseUrl()
    {
        if(isset($_GET['url'])){
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}