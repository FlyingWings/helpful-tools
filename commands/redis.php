<?php

use HTools\Library\RedisModel\Bullets;
use HTools\BaseClass\HashRedisModel;

function cli_redis_push_task(){
    $tasks = new HashRedisModel();

    $unique_id = sha1(microtime(1).":".rand(0, 65535));
    $things = ['name'=>"Test", "start"=>time(), 'end'=>time()+3600, "priority"=>rand(0, 1024), 'id'=>$unique_id];
    $tasks->redis->hSet("todo_list", $unique_id, json_encode($things));
}


function cli_redis_test(){
    $task = new HashRedisModel();


    $b = $task->redis->hGetAll("tests");

    asort($b);
    dd($b);
}