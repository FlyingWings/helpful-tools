<?php

use HTools\Library\RedisModel\Bullets;
function cli_redis_test(){

    $a = new Bullets();
    var_dump($a->len());
    var_dump($a->rPush("abc"));
    var_dump($a->len());
}