<?php

use HTools\Library\RedisModel\Bullets;
use HTools\BaseClass\HashRedisModel;

function cli_redis_insert_bullets(){
    $params = func_get_args()[0];
    $max_length = 100;

    $flag = sprintf("bullets-%d", date("i", time()));


    $model = new Bullets($flag);

    $model->lPush($params);

    //@todo :values out of range will be stored instead of discarded

    $model->lTrim(0, $max_length-1);

    dd($model->len());
}

function cli_redis_get_bullets(){
    $flag = sprintf("bullets-%d", date("i", time()));
    $model = new Bullets($flag);

    dd($model->lRange());
}


function cli_redis_hash(){
    $test = new Bullets();
//    $test->exec();
//    var_dump($test->len());
//    dd();
//    $test->watch();
    $test->multi();
    sleep(3);
    var_dump($test->lPush(["123", "456"]));
    var_dump($test->len());
    $test->exec();
    var_dump($test->len());
//    $test->
}
