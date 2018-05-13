<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-13
 * Time: 下午6:04
 */

use PHPUnit\Framework\TestCase;
require_once(dirname(__DIR__)."/../base.php");



class RedisModelBaseTest extends TestCase{
    public function testConnect(){
        $redis = \HTools\BaseClass\IndexRedisModel::get_instance();
        $this->assertInstanceOf("Redis", $redis);
    }
}