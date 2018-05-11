<?php


function cli_redis_test(){
    $a = new \library\redis_model\Bullets();
    var_dump($a->len());
    var_dump($a->rPush("abc"));
    var_dump($a->len());
}