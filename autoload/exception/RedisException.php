<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-14
 * Time: 下午3:07
 */
namespace HTools\Exception;

use Throwable;

class RedisException extends \Exception{

    protected static $exception_message = [
        'empty'=>"Key '%s' not existed in the Redis\n",
        'type_error'=>"Key '%s' is of type: '%s'\n",
        'list_len'=>"Key '%s' index out of range %d\n",// List Length
    ];


    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if(is_array($message)){

            if($message['type'] == "none"){
                $dealt_message = sprintf("Key '%s' not existed in the Redis\n", $message['model_name']);
            }else{
                $dealt_message = sprintf("Key '%s' is of type: '%s'\n", $message['model_name'], $message['type']);
            }
        }else{
            $dealt_message = $message;
        }
        parent::__construct($dealt_message, $code, $previous);
    }


}