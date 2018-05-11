<?php

namespace library\redis_model;

use \autoload\base_class\IndexRedisModel;
class Bullets extends IndexRedisModel{
    public $model_name = "bullets";
    public $data_type = "list";

    public function __construct(){
        $this->redis = self::get_instance();
    }

    public function len(){
        return $this->redis->lLen($this->model_name);
    }

    public function rPush($val){
        $res = $this->redis->rPush($this->model_name, $val);
        return $res;
    }
}