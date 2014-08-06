<?php


namespace application\controllers;


class ControllerFactory {

    public function buildHomeController(){
        return new HomeController();
    }
} 