<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-29
 * Time: 下午3:08
 */
namespace HTools\MVC\Controllers;

use HTools\BaseClass\IndexController;

class TaskController extends IndexController {
    public function view(){
        $this->load("task/index", ['site'=>"task"]);
    }
}