<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-26
 * Time: 下午4:46
 */

class HTTP_client{
    protected $headers;
    protected $cookie;
    protected $url;

    public function set_cookie($cookie=""){
        if(isset($this->cookie)){
            return true;
        }else if(file_exists($cookie)){
            $this->cookie = file_get_contents($cookie);
            return true;
        }else{
            throw new Exception("Cookie file not exists");
        }

    }


    public function __construct($cookie = null){
        if(is_null($cookie)){
            $this->cookie = null;
        }else{
            $this->set_cookie($cookie);
        }
    }


    public function post($url, $params=[], $headers = []){
        $this->url = $url;
        $this->params = $params;
        $this->headers = $headers;
        $this->run("POST");
    }

    public function get($url, $params=[], $headers=[]){
        $this->url = $url;
        $this->params = empty($params) ?: explode("&", $params);
        $this->headers = $headers;
        $this->run("GET");
    }


    public function run($method){
        $ch = curl_init();
        switch ($method){
            case "GET":
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
                break;
            default:
                break;
        }
//        dd($this->headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        curl_setopt($ch, CURLOPT_URL, $this->url);

        //返回string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $m = curl_getinfo($ch);
        dd($m);
    }
}