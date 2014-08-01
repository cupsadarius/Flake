<?php

use application\assets\Registry;
use application\assets\Routes\RouteManager;
use application\assets\Routes\Route;
use application\controllers\ControllerFactory;
use application\assets\Routes\Conditions;
use application\assets\Routes\Binds;


spl_autoload_extensions(".php");
spl_autoload_register();

require_once __DIR__.'/application/lib/Twig/Autoloader.php';

$app = Registry::getInstance();

$app->register('dev_mode',true);
$app->register('base_url','http://localhost');
if($app->dev_mode){
    function exception_handler(Exception $e){
        file_put_contents(__DIR__.'/logs/exceptions.log',"Exception caught: ".$e->getMessage()."\n",FILE_APPEND);
    }
    set_exception_handler('exception_handler');
}

//Twig register
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__.'/application/views');
$twig = new Twig_Environment($loader);

//register services
$app->register('twig',$twig);
$app->register('controllerFactory',new ControllerFactory());

//controllers
$home = $app->controllerFactory->buildHomeController();

//define conditions
$conditions = new Conditions();
$conditions->register('popup',function($app){
   	echo "<script type='text/javascript'>alert('im in a binding');</script>";
    return true;
});
$app->register('conditions',$conditions);

//define routes
$routeManager = new RouteManager();

$routeManager->addRoute(new Route('get','/',$home,'indexAction'),'home');

$routeManager->addRoute(new Route('get','/hello/{name}',$home,'helloAction'),'hello');

$app->register('routes',$routeManager);
//add bindings
$bindings = new Binds();
$bindings->before('home','popup');
$app->register('bindings',$bindings);

//run 
$app->run();