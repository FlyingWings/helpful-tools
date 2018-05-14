<?php

use HTools\Library\RedisModel\Bullets;


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

