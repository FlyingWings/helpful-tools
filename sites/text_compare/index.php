<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-3-16
 * Time: 下午3:28
 */


define("SITE_ROOT", dirname(__FILE__));

global $routers;

$routers['column'] = 'ColumnController/view';


require_once(SITE_ROOT."/../../framework/index.php");