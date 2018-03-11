<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-6
 * Time: 下午5:24
 */

global $pre_hooks;
$pre_hooks = [
    "init"=> [
        'class'=>null,
        'name'=>'hello_world',
        'params'=>[]],
];



function hello_world(){
    global $argv;
    if(php_sapi_name() == 'cli'){
        $_SERVER["REQUEST_URI"] = "/". implode("/", $argv);
    }
}
