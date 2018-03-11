<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-8
 * Time: 上午9:57
 */

interface IndexModel{
    public function buildData($data, $callback);

    public function getData();

    public function setDataPostion($position, $name);


}