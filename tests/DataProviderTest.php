<?php

use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase{
    /**
     * @dataProvider fetch_data
     */
    public function testData($a, $b, $expected){
        $this->assertEquals($expected, $a+$b);
    }

    public function fetch_data(){
        $d = [
            [1,2,3],
            [4,5,9]
        ];
        foreach($d as $item){
            yield $item;
        }
    }


    /**
     * @dataProvider use_data
     */
    public function testLength($length){
        $this->assertGreaterThan(0, $length);
    }

    public function use_data(){
        for($i=1; $i<10; $i++){
            yield [$i];
        }
    }


    /**
     * @expectedException Exception
     */
    public function testException(){
        throw new Exception("123");
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testError(){
        include "ffds.php";
    }

    public function testOutput(){
        $this->expectOutputString("fff");
        echo "fff";
    }
}