<?php


function cli_test_iter(){
    foreach(test_iter() as $r){
        var_dump($r);
    }
}


function cli_test_regex(){
    $regex = "/(?<=: )([\w^*].+)/";
    $content = '1) 完全匹配: alexfu.cc
2) 通配符: *.alexfu.cc
3) 通配符在后: alexfu.*
4) 正则表达式: ^alex.+$';

    $ma= preg_replace($regex, "`$1`", $content);
    dd($ma);
}