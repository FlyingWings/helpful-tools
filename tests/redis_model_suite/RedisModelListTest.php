<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-13
 * Time: 下午6:23
 */

use PHPUnit\Framework\TestCase;
require_once(dirname(__DIR__)."/../base.php");


class RedisModelListTest extends TestCase{

    public function testConnect(){
        $list = new HTools\Library\RedisModel\Bullets();
        return $list;
    }

    /**
     * @depends testConnect
     */
    public function testExists($list){
        $this->assertEquals(1, $list->exists());
    }
}