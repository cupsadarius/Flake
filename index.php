<?php

use application\assets\Registry;
use application\assets\Routes;
use application\controllers\ControllerFactory;

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
$app->register('routes',new Routes());
$app->register('controllerFactory',new ControllerFactory());

//controllers
$home = $app->controllerFactory->buildHomeController();


//define routes
$app->routes->get('/',array($home,'indexAction',$app));

$app->routes->get('/hello/{name}',array($home,'helloAction',$app));
