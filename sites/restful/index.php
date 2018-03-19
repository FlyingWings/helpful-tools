<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-27
 * Time: 上午10:13
 */


define("SITE_ROOT", dirname(__FILE__));


global $routers;

$routers['resource/get'] = "RestfulController/get";


require_once(SITE_ROOT."/../../framework/index.php");

