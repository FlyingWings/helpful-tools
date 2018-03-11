<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-1-24
 * Time: 上午10:51
 */
function dd(){
    $args = func_get_args();
    array_map("var_dump", $args);
    exit;
}


