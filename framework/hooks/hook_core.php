<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-6
 * Time: 下午5:21
 */

function load_hook_pre(){
    global $pre_hooks;
    foreach($pre_hooks as $func){
        if(empty($func['class'])){
            call_user_func($func['name'], $func['params']);
        }else{
            if(class_exists($func['class'])){
                call_user_func([$func['class'], $func['name']], $func['params']);
            }else{
                throw new Exception("Class Not Exists", 502);
            }
        }
    }

}

function load_hook_done(){

}

function load_hook_before_controller(){

}