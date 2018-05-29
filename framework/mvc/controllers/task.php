<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-29
 * Time: 下午3:08
 */
namespace HTools\MVC\Controllers;

use HTools\BaseClass\IndexController;
use HTools\Library\RedisModel\Bullets;


class TaskController extends IndexController {
    public function __construct()
    {
        $this->todo_list = new Bullets("todo_list");
        $this->doing_list = new Bullets("doing_list");
        $this->done_list = new Bullets("done_list");
    }

    public function view(){

//        $m = new Bullets();
//        dd($m->redis->hGetAll("todo_list"));
//        dd($this->todo_list->)
        $todo_list = "";
        $doing_list = "";
        $done_list = "";
        $to = [];
        $to_key = [];
        $template = "<li><span>%s</span><br/><span>Start: %s</span><br><span>End: %s</span><br><span>%d</span></li>";
        foreach($this->todo_list->redis->hGetALl("todo_list") as $key=> $item){
            $item = json_decode($item);
            $to[$key] = $item;
            $to_key[$key] = $item->priority;

        }

        asort($to_key);
//        dd($to_key);
        foreach (array_keys($to_key) as $key){
            $todo_list .= sprintf($template, $to[$key]->name, date("Y-m-d H:i:s", $to[$key]->start), date("Y-m-d H:i:s", $to[$key]->end), $to[$key]->priority);
        }
//        dd($to_key);

        foreach($this->doing_list->redis->hGetALl("doing_list") as $item){
            $item = json_decode($item);
            $doing_list .= sprintf($template, $item->name, date("Y-m-d H:i:s", $item->start), date("Y-m-d H:i:s", $item->end));
        }

        foreach($this->done_list->redis->hGetALl("done_list") as $item){
            $item = json_decode($item);
            $done_list .= sprintf($template, $item->name, date("Y-m-d H:i:s", $item->start), date("Y-m-d H:i:s", $item->end));
        }
        $this->load("task/index", ['site'=>"task", 'todo_list'=>$todo_list, "doing_list"=>$doing_list, "done_list"=>$done_list]);

    }

    public function edit(){

    }
}