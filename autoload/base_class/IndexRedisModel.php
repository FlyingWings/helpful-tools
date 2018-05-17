<?php

namespace HTools\BaseClass;

class IndexRedisModel{
    public static $redis;

    protected static $redis_cluster;
    protected static $type =[
        '1'=>"string",
        '2'=>'set',
        '3'=>'list',
        '4'=>'zset',
        '5'=>'hash'
    ];

    public $data_type;

    public $model_name;


    public function __construct(){
        if(REDIS_RUN_MODE == "SINGLE"){
            $this->redis = self::get_instance();
        }else{
            $this->redis_cluster = self::get_cluster_instance();
            $this->redis = $this->redis_cluster['master'];
        }

    }

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

    public static function get_cluster_instance(){
        static $redis_cluster;
        if(empty($redis_cluster)){
            global $cluster;
            $master = new \Redis();
            $master->pconnect($cluster['master'], REDIS_PORT);
            $slave = new \Redis();
            $slave->pconnect($cluster['slaves'][rand(0, count($cluster['slaves'])-1)], REDIS_PORT);
            $redis_cluster['master'] = $master;
            $redis_cluster['slave'] = $slave;
        }

        return $redis_cluster;
    }


    public function del(){
        return self::get_instance()->del($this->model_name);
    }

    public function type(){
        return self::$type[self::get_instance()->type($this->model_name)];
    }
    public function exists(){
        return self::get_instance()->exists($this->model_name);
    }

    public function multi(){
        return self::get_instance()->multi();
    }

    public function exec(){
        self::get_instance()->exec();
    }

    public function discard(){
        self::get_instance()->discard();
    }

    public function watch($keys=[]){
        if($keys){
            self::get_instance()->watch($keys);
        }else{
            self::get_instance()->watch($this->model_name);
        }
    }

    public function unwatch(){
        self::get_instance()->unwatch();
    }
}