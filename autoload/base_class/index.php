<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-5
 * Time: 下午5:31
 */
class IndexController{

    function __construct(){
    }

    public static function _redirect($url){
        header("Location: /".$url);
    }

    public static function _redirect_code($code){
        echo "Error {$code}\n";
    }

    public static function input(){
        return [
            "GET"=> array_map(function($i){return trim(htmlspecialchars($i));}, $_GET),
            "POST"=> array_map(function($i){return trim(htmlspecialchars($i));}, $_POST)];
    }

    public static function load($page, $data=[]){

        if(preg_match("/\.\w+/", $page) === 0){
            $page.=".php";
        }

        $content = file_get_contents(MVC. DIRECTORY_SEPARATOR. "views". DIRECTORY_SEPARATOR. $page);
        foreach($data as $k=>$v){
            $content = str_replace("{{".$k."}}", $v, $content);
        }

        if(preg_match_all("/{{\w+}}/", $content, $matches) >0){
            throw new Exception(sprintf("%s not mapped", implode("|", $matches[0])), 401);
        }else{
            echo $content;
        }

    }

    public function index(){
        $this->load("default");
    }

}