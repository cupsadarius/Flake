<?php

namespace application\assets;
use application\assets\Request;
class Routes {

    public function __construct(){

    }

    private function getData($pattern){
        if($pattern != '/'){
            preg_match_all('/(?<={).*?(?=})/',$pattern,$keys);
            $root = substr($pattern,0,strpos($pattern,'{'));
            if(!empty($root)){
                $uri = $_SERVER['REQUEST_URI'];
                $data = explode('/',substr($uri,strlen($root),strlen($uri)-strlen($root)));
                $req = new Request();
                for($i = 0;$i <count($keys[0]);$i++){
                    $req->push($keys[0][$i],(isset($data[$i])&& !empty($data[$i]))?htmlentities($data[$i]):0);
                }
                return array('root_pattern'=>$root,'request_data'=>$req);
            }else{
                $root = $pattern;
            }
            return array('root_pattern'=>$root);
        }
        return array('root_pattern'=>'/','request_data'=>new Request());
    }

    public function get($pattern, $callable){
        $data = $this->getData($pattern);
        if($pattern == '/'){
            if($_SERVER['REQUEST_URI'] == $data['root_pattern']){
                call_user_func_array(array($callable[0],$callable[1]),array($callable[2],$data['request_data']));
                return true;
            }
        }else {
            if(strpos($_SERVER['REQUEST_URI'],$data['root_pattern']) !== false){
                call_user_func_array(array($callable[0],$callable[1]),array($callable[2],$data['request_data']));
                return true;
            }
        }
        return false;
    }
    public function post($pattern, $callable){
        if($_SERVER['REQUEST_URI'] == $pattern){
            $req = new Request();
            foreach($_POST as $key=>$val){
                $req->push($key,htmlentities($val));
            }
            call_user_func_array(array($callable[0],$callable[1]),array($callable[2],$req));
            return true;
        }
    }
}
