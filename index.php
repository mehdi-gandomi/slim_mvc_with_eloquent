<?php
/******************************* LOADING & INITIALIZING BASE APPLICATION ****************************************/
// Configuration for error reporting, useful to show every little problem during development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Load Composer's PSR-4 autoloader (necessary to load Slim)
require 'vendor/autoload.php';
//auto loading classes
spl_autoload_register(function ($class){
    preg_match('/(\w)+(Controller)/', $class, $matches, PREG_OFFSET_CAPTURE);
    $file="";
    if($matches){
        $file="App/Controllers/".$class.".php";
    }else{
        $file=str_replace('\\','/',$class).'.php';
    }
    $file=__DIR__."/$file";
        if (is_readable($file)){
            require_once $file;
        }
});
//end autoloading code
    
//loading jdf library to convert date to persian format
// require_once "App/Dependencies/jdf.php";

//loading Settings and instantiate app
$settings=require_once(__DIR__."/slim_settings.php");
$app = new \Slim\App($settings);

//loading app container configurations

require_once(__DIR__."/slim_extentions.php");

/*******************************End Of LOADING & INITIALIZING BASE APPLICATION ****************************************/

// loading routes

foreach(glob(__DIR__."/routes/*.php") as $route){
    require_once $route;
}


//running project
$app->run();

