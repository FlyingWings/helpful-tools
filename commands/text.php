<?php
/**
 * 统计给定文本中关键词次数
 * 文件名|关键词
 */
function cli_statics_appearance(){
    dd(func_get_args());
    $param = func_get_args()[0];
    if(file_exists($param[0])){
        $content = file_get_contents($param[0]);
        $keyword = $param[1];
        $count = preg_match_all("/".$param[1].".+?\n/", $content, $matches);

        dd($count);
    }else{
        throw new Exception("File not exists");
    }
}

function cli_text_get_lines(){
    $params = func_get_args()[0];
    foreach($params as $name){
        printf("%s:%d\n", $name, \library\TextRelated\FileStat::getFileLines($name));
    }
}