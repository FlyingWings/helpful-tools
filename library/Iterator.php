<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-4-4
 * Time: 下午5:54
 */
class Users implements Iterator{
    public function current()
    {
        // TODO: Implement current() method.
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }

    public function key()
    {
        // TODO: Implement key() method.
    }
}


function test_iter(){
//    return range(0, 1000000000);
    for($i=0; $i< 1000000000; $i++){
        yield $i;
    }
}

