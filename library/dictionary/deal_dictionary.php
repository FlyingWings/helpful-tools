<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-8
 * Time: 上午9:56
 */
//class DealDictionary implements IndexModel {
//    protected $file_name;
//
//    public function setDataPostion($position, $name){
//        if(!is_dir(ROOT. DIRECTORY_SEPARATOR. "data")){
//            mkdir(ROOT. DIRECTORY_SEPARATOR. "data");
//        }
//        $file = ROOT. DIRECTORY_SEPARATOR. "data". DIRECTORY_SEPARATOR. $name;
//        file_put_contents($file, "");
//        $this->file_name = $file;
//    }
//
//    public function buildData($data=[], $callback=""){
//        if(empty($callback)){
//            foreach($data as $datum){
//                file_put_contents($this->file_name, $datum, FILE_APPEND);
//            }
//        }else{
//            if(function_exists(($callback))){
//                foreach($data as $datum){
//                    file_put_contents($this->file_name, $callback($datum), FILE_APPEND);
//                }
//            }
//
//        }
//    }
//}