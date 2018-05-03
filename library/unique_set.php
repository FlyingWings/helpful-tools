<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-4-11
 * Time: 上午10:58
 */


namespace library;
class UniqueSet
{
    protected $set;
    public function __construct(){
        $this->set = [];
    }

    public function add($obj){
        if(in_array($this->set, $obj)){
            return 1;
        }else{
            array_push($this->set, $obj);
            return 1;
        }
    }

    public function delete($obj){
        $pos =array_search($this->set, $obj);
        if($pos === false) return 1;
        unset($this->set[$pos]);
        return 1;
    }

    public function getArray(){
        return $this->set;
    }

}