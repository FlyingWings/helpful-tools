<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-5
 * Time: 上午11:17
 */
class TimeConvert {
    public static function calculate_multi() {
        $name = func_get_args();
        return $name[0] << 2;
    }

}


function calculate_multi(){
    $name = func_get_args();
    return $name[0] << 2;
}