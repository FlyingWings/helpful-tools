<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-8
 * Time: 上午9:57
 */
namespace HTools\BaseClass;

interface IndexModel{
    public function buildData($data, $callback);

    public function getData();

    public function setDataPostion($position, $name);


}