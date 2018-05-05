<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-3
 * Time: 下午3:27
 */

require_once(dirname(__DIR__)."/base.php");

use PHPUnit\Framework\TestCase;


class FileStatTest extends TestCase{
    public function testFileLines(){
        $file = DATA."/test1.log";

        if(!file_exists($file)){
            touch($file);
        }
        $line = \library\TextRelated\FileStat::getFileLines($file);
        $this->assertNotFalse($line);
    }

    public function testFileEmpty(){
        $file = "no.log";
        $line = \library\TextRelated\FileStat::getFileLines($file);
        $this->assertEquals(0, $line);
        $this->assertFalse($line);
    }

    public function testFileNotReadble(){
        $file = DATA."/test.log";
        if(!file_exists($file)){
            touch($file);
        }
        chmod(DATA."/test.log", 000);
        $line = \library\TextRelated\FileStat::getFileLines($file);
        $this->assertFalse($line);
    }
}