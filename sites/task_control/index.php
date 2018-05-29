<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-19
 * Time: 下午3:28
 */

define("SITE_ROOT", dirname(__FILE__));

global $routers;

$routers['login'] = 'TaskController/view';


require_once(SITE_ROOT."/../../framework/index.php");