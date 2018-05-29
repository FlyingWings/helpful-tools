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

    $model = new Bullets("bull");
    dd($model->redis->blPop("bull",0 ));
    dd($model->len());
}

function cli_redis_generate_task(){
    $client = new Bullets("task_queue");
    $id = 1;
    while(1){
        $amount = 100000;
        $insert_batch = [];

        while($amount){
            $insert_batch[] = json_encode(['task_id'=>$id++,'class'=>null, 'func'=>'calculate_multi', 'params'=>[rand(1, 65535)]]);
            $amount--;
        }
        $client->rPush($insert_batch);
        sleep(10);
    }
}

function cli_redis_deal_task(){

    $client = new Bullets("task_queue");
    $res = new Bullets("result_queue");
    while(1){
        $task = $client->lPop();
        if($task){
            $task = json_decode($task);
        }
        if(@$task->class){
            $res->rPush(json_encode(['task_id'=>$task->task_id, 'result'=>call_user_func_array([$task->class, $task->func], $task->params)]));
        }else{
            $res->rPush(json_encode(['task_id'=>$task->task_id, 'result'=>call_user_func_array($task->func, $task->params)]));
        }
//        sleep(0.5);
    }

}

function cli_redis_test(){
    $res = new Bullets("result_queue");
    $num = $res->len();

    while(1){
        $new_num = $res->len();
        var_dump($new_num- $num);
        $num = $new_num;
        sleep(1);

    }

}


class TT{
    public function  __get($key){
        return $this->$key;
    }
    public function __set($name, $value)
    {
        $this->$name= $value;
    }

    public function uset(){
        dd($this);
    }



}

function cli_redis_usss(){
    $m = new TT();
    dd($m->uset());
    $m->a = 123;
    dd($m, $m->a);
}