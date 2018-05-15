<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-5
 * Time: 下午5:31
 */
namespace HTools\BaseClass;

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

    protected function get_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    protected function get_request_headers($key=null){
        $headers = getallheaders();
        if(!empty($key)){
            return $headers[$key];
        }
        return $headers;
    }

    protected function get_response_headers($key=null){
        $headers = headers_list();
        $new_headers = [];
        array_map(function($i)use(&$new_headers){$temp=explode(":", $i);$new_headers[$temp[0]] = $temp[1];}, $headers);
        if(!empty($key)){
            return $new_headers[$key];
        }
        return $new_headers;
    }

    protected function set_response_header($key=null, $value=null){
        if(!empty($key)){
            header(implode(": ", [$key, $value]));
            return 1;
        }
        return 0;
    }

    protected function remove_response_header($key=null){
        if(!empty($key)){
            header_remove($key);
            return 1;
        }
        return 0;
    }


}