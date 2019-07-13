<?php


class Model
{

    public static function get($model)
    {
        if(file_exists("System/Models/".$model.'.php')){
            require_once("System/Models/".$model.'.php');
            return new $model();
        }else{
            die("<h1>There was a problem finding the model {$model}</h1>");
        }
    }
}