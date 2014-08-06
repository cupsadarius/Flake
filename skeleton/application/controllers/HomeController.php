<?php
/**
 * Created by PhpStorm.
 * User: Darius
 * Date: 7/30/14
 * Time: 9:41 AM
 */

namespace application\controllers;
use application\assets\Registry;
use application\assets\Request;

class HomeController {

    public function indexAction(Registry $app, Request $request){
        echo $app->twig->render('layout.html.twig');
    }

    public function helloAction(Registry $app, Request $request){
        echo $app->twig->render('layout.html.twig',array('name' =>$request->name));
    }
} 