<?php
/**
 * Created by PhpStorm.
 * User: fpl
 * Date: 2018/5/4
 * Time: 下午9:19
 */

use PHPUnit\Framework\TestCase;


class ExceptionAndErrorTest extends TestCase{
    public function testUU(){

        $this->setExpectedException("Exception", 'ok', 200);
        throw new Exception("1234ok", 200);
        $this->setExpectedException("Exception", "ok", 100);
        throw new Exception("ok", 400);
    }


    /**
     *
     */
    public function testEE(){
        $this->setExpectedException("PHPUnit_Framework_Error");
        echo 1/0;
//        include "no_existed.php";

    }

    public function testOutput(){
        $this->expectOutputString("123");
        echo 123;

    }

    public function test1(){
        $this->expectOutputRegex("/[0-9]+/");
        echo "123";
    }
}