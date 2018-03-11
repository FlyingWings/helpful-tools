<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-1-24
 * Time: 上午11:07
 */

use PHPUnit\Framework\TestCase;
include_once "../Unicode.php";


class UnicodeTest extends TestCase
{

    public function testEquals()
    {
        $words = ["给对方嘎撒发送的奋斗", "突然2了", "编码奖品200元他21fds可发现fds实话"];

        foreach($words as $word){
            $new[$word] = trim(unicode_encode($word), "\"");
        }
        foreach($new as $key=>$value){
            $this->assertEquals($key, unicode_decode($value));
            $this->assertEquals($value, unicode_encode($key));
        }

    }


}

