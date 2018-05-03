<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-26
 * Time: 下午4:46
 */

namespace library;
class HTTP_client{
    public $client;
    public $cookies;
    public function __construct($base_url="", $cookie = false){
        if($cookie){
            $cookie_file = DATA.'/cookie/'.sha1($base_url);
            $content = file_get_contents($cookie_file);
//            dd($cookie_file);
            if(empty($content)){
                file_put_contents($cookie_file, "");
                throw new Exception("Empty Cookie File\n");
            }

            if($json_content =json_decode($content)){
                $result = [];
                foreach($json_content as $obj){
                    $item = (array)$obj;
                    array_map(function($i)use (&$item){$item[ucfirst($i)] = $item[$i]; unset($item[$i]);}, array_keys($item));
                    $result[] = $item;
                }
                $cookie = $result;
            }else{
                $cookie = unserialize($content);
            }

            $this->cookies = new \GuzzleHttp\Cookie\CookieJar([], $cookie);

        }

        $this->client = new \GuzzleHttp\Client([
            'base_uri'=>$base_url,
        ]);
    }



    public function get($request_uri= "", $request_params= []){
        $response = $this->client->request("GET", $request_uri,['cookies'=>$this->cookies]);
        return $response->getBody()->getContents();
    }

    public function post($request_uri="", $request_params = []){
        $response = $this->client->request("POST", [
            'form_params'=>$request_params,
        ]);
        return $response;
    }
}