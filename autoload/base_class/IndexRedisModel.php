<?php

namespace HTools\BaseClass;

class IndexRedisModel{
    protected static $redis;

    public $data_type;

    public $model_name;
    public static function get_instance(){

        static $redis;

        if(empty($redis)){
            $redis = new \Redis();
            $redis->pconnect(REDIS_HOST, REDIS_PORT);
            return $redis;
        }else{
            return $redis;
        }
    }

    public function del(){
        return self::get_instance()->del($this->model_name);
    }

    public function type(){
        return self::get_instance()->type($this->model_name);
    }
    public function exists(){
        return self::get_instance()->exists($this->model_name);
    }
}