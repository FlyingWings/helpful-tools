<?php
///**
// * Created by PhpStorm.
// * User: alex
// * Date: 18-9-30
// * Time: 下午2:13
// */
//
//class Clickhouse {
//    private $client;
//
//    public function __construct(array $param){
//        $default_config = [
//            'host'=>'127.0.0.1',
//            'port'=>'8123',
//            'username'=>'default',
//            'password'=>'',
//            'db'=>'default',
//        ];
//        $config = array_merge($default_config, $param);
//        $db = $config['db'];
//        unset($config['db']);
//
//
//    }
//}
//
//
//$addr = "tcp://172.28.128.10:3306";
//$sock = stream_socket_client($addr, $code, $message);
//if(!$sock){
//    var_dump($code, $message);
//}else{
//    if(stream_set_blocking($sock, false) === false){
//        var_dump("ERR");
//    }
//}
//
//
//$string = "show databases;";
//$length = strlen($string);
//$bytes = @fwrite($sock, $string);
//
//while(!feof($sock)){
//    var_dump(fgets($sock, 128));
//}
//
//stream_socket_shutdown($sock, STREAM_SHUT_RDWR);