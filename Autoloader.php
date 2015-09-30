<?php
namespace Framework;

class Autoloader
{
    public static function init()
    {
        spl_autoload_register(function($class){
            $pathParams = explode("\\", $class);
            $path = implode(DIRECTORY_SEPARATOR, $pathParams);
            $path = str_replace($pathParams[0], "", $path);
            $fullPathArr = explode('/', $_SERVER['SCRIPT_FILENAME']);
            array_pop($fullPathArr);
            $fullPath = implode(DIRECTORY_SEPARATOR, $fullPathArr);
            if(file_exists($fullPath . $path . '.php')) {
                require_once $path . '.php';
            } else {
                throw new \Exception('Class does not exists.');
            }
        });
    }
}