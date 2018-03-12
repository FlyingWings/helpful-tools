<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-6
 * Time: 上午11:09
 */
class LoginController extends IndexController{
    function __construct(){
        parent::__construct();
    }
    public function index(){
        $data = ['title'=>'123', 'content'=>'123', 'id'=>1];
        $this->load("user/index", $data);
    }

    public function test(){
        $data = array_merge(['title'=>'alex test', 'content'=> 'hello world', 'id'=> rand(1,65535)], $this->input()["GET"]);
        $this->load("user/index.php", $data);
    }

    public function cli(){
        print("GG\n");
    }
}