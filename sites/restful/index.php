<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-27
 * Time: 上午10:13
 */


define("SITE_ROOT", __DIR__);
require_once(SITE_ROOT."/../../base.php");


switch($_SERVER['REQUEST_METHOD']){
    case "GET":
        var_dump($_GET);break;
    case "POST":
        var_dump($_POST,getallheaders());break;
    default:
        throw new Exception("Request Method not allowed");
}