<?php

namespace HTools\BaseClass;

use HTools\BaseClass\IndexRedisModel;


class ListRedisModel extends IndexRedisModel{
//    public $model_name = "bullets";
    public $model_name;
    public $data_type = "list";

    public function __construct(){
        parent::__construct();
    }

   public function __call($name, $arguments)
    {
        $this->redis = isset($this->redis_cluster['master']) ?: $this->redis ;
        if(in_array($name, ["rPush", "lPush"])){

            array_unshift($arguments, $this->model_name);
            $res= call_user_func_array([$this->redis, $name], $arguments);//update the way of pushing data into queue
            if($res && !is_object($res)){
                return ($res);
            }elseif($res && is_object($res)){
                return "Queued";// in case of transaction
            }else{
                throw new \HTools\Exception\RedisException(['model_name'=>$this->model_name, 'type'=>$this->type()]);
            }
        } elseif (in_array($name, ['blPop', 'brPop'])){
            if(empty($arguments)){
                throw new \HTools\Exception\RedisException("block pop requires timeout");
            }else{
                array_unshift($arguments, $this->model_name);
                $res = call_user_func_array([$this->redis, $name], $arguments);
                return $res;
            }
        } elseif(in_array($name, ['rPop', 'lPop'])){
            $res = $this->redis->$name($this->model_name);
            if($res){
                return $res;
            }elseif($res && is_object($res)){
                return "Queued";
            }else{
                throw new \Htools\Exception\RedisException(['model_name'=>$this->model_name, 'type'=>$this->type()]);
            }
        } else{
            $this->redis = $this->redis_cluster['slave'];
            return call_user_func_array([$this->redis, $name], $arguments);
        }
    }

    public function len(){
        $res= $this->redis->lLen($this->model_name);
        if(is_object($res)){
            return "Queued";
        }else{
            return $res;
        }
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