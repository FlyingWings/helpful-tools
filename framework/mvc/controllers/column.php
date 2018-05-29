<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-3-15
 * Time: 下午5:27
 */

namespace HTools\MVC\Controllers;
use HTools\BaseClass\IndexController;

class ColumnController extends IndexController{
    public function __construct(){
        parent::__construct();
    }

    public function view(){
        $data = $this->input();
        $result = ['left'=>"", 'right'=>"", "single_left"=>"", "single_right"=>"", "hidden"=>"none", "dup"=>""];


        if(!empty($data["POST"])){
            $left= $data["POST"]['left'];
            $right = $data["POST"]['right'];

            if(empty($left) || empty($right)){
                $this->load("/column/index", $result);
                exit;
            }
            $left = array_map(function($i){return trim($i,"\"'\t\n\r\0");},explode("\n", $left));
            $right = array_map(function($i){return trim($i,"\"'\t\n\r\0");},explode("\n", $right));
            $left_ids = [];
            $right_ids = [];
            $duplicated_right = $duplicated_left = $island_right = $island_left = [];
            foreach($left as $left_key=>$left_item){
                foreach($right as $right_key=>$right_item){
                    if(strpos($left_item, $right_item) >0 || strpos($left_item, $right_item) ===0 || strpos( $right_item, $left_item) >0 || strpos($right_item, $left_item) ===0 ){
                        $left_ids[$left_key] = 1;
                        $right_ids[$right_key] = 1;
                    }
                }
            }

//            dd($left_ids, $right_ids);
            foreach($left as $key=>$item){
                if(in_array($key, array_keys($left_ids))){
                    $duplicated_left[]= $item;
                }else{
                    $island_left[]=$item;
                }
            }

            foreach($right as $key=>$item){
                if(in_array($key, array_keys($right_ids))){
                    $duplicated_right[]= $item;
                }else{
                    $island_right[]=$item;
                }
            }
//            dd($left_ids, $right_ids, $island_left, $island_right);
            $duplicated_left = implode("\n", $duplicated_left);$duplicated_right = implode("\n", $duplicated_right);
            $island_left = implode("\n", $island_left);$island_right = implode("\n", $island_right);
            $this->load("/column/index", ['left'=>$duplicated_left, 'right'=>$duplicated_right, "single_left"=>$island_left, "single_right"=>$island_right, "hidden"=>"", "dup"=>"重合的"]);

        }else{
            $this->load("/column/index", $result);
        }
    }
}