<?php

namespace HTools\BaseClass;

//use HTools\BaseClass\IndexRedisModel;


class ListRedisModel extends IndexRedisModel{
//    public $model_name = "bullets";
    public $model_name;
    public $data_type = "list";

    public function __construct(){
        $this->redis = self::get_instance();
    }

    public function __call($name, $arguments)
    {
        if(in_array($name, ["rPush", "lPush"])){
            $val = $arguments[0];
            if(is_array($val)){
                array_unshift($val, $this->model_name);
            }else{
                $val = [$this->model_name, $val];
            }
            $res= call_user_func_array([$this->redis, $name], $val);
            if($res){
                return $res;
            }else{
                throw new \Htools\Exception\RedisException(['model_name'=>$this->model_name, 'type'=>$this->type()]);
            }
        }elseif(in_array($name, ['rPop', 'lPop'])){
            $res = $this->redis->$name($this->model_name);
            if($res){
                return $res;
            }else{
                throw new \Htools\Exception\RedisException(['model_name'=>$this->model_name, 'type'=>$this->type()]);
            }
        }
    }

    public function len(){
        return $this->redis->lLen($this->model_name);
    }

    public function lTrim($start=0, $end=1){
        return $this->redis->lTrim($this->model_name, $start, $end);
    }


    public function lSet($index=0, $val=""){
        $res= $this->redis->lSet($this->model_name, $index, $val);
        if($res){
            return true;
        }else{
            $len = $this->len();
            throw new \Htools\Exception\RedisException(['model_name'=>$this->model_name, 'type'=>$this->type(), 'exception'=>'']);
        }
    }

    public function lRange($start=0, $end=-1){
        return $this->redis->lRange($this->model_name, $start, $end);
    }
}