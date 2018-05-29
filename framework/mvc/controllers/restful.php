<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-3-19
 * Time: 上午10:22
 */
namespace HTools\MVC\Controllers;

use HTools\BaseClass\IndexController;
class RestfulController extends IndexController{
    public function __construct(){
        parent::__construct();
    }

    public function get(){
//        http_response_code()
//        dd($this->get_method());
//        dd(http_response_code(401));
    }
}