<?php

namespace autoload\base_class;

class IndexRedisModel{
    protected static $redis;

    public $data_type;

    public $model_name;
    public static function get_instance(){
        if(empty(self::$redis)){
            self::$redis = new \Redis();
            self::$redis->pconnect(REDIS_HOST, REDIS_PORT);
            return self::$redis;
        }else{
            return self::$redis;
        }
    }

    public function getOne($model_name){

    }


}