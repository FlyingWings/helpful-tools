<?php


namespace library\TextRelated;

class FileStat {
    public static function getFileLines($filename){
        if(!file_exists($filename)){
            return false;
        }else{
            if(is_readable($filename)){
                return trim(shell_exec("cat $filename | wc -l"));
            }else{
                return false;
            }
        }
    }
}