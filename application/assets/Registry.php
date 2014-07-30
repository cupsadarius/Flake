<?php
/**
 * Created by PhpStorm.
 * User: Darius
 * Date: 7/29/14
 * Time: 4:06 PM
 */

namespace application\assets;


class Registry {

    private static $_instance;

    public static function getInstance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){
        return $this;
    }

    public function register($key, $value){
        $this->$key = $value;
    }

    public function remove($key){
        foreach($this as $k=>$value){
            if($k == $key){
                unset($this->$k);
                return true;
            }
        }
        return false;
    }


    public function __clone(){}


} 