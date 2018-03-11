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


function cli_deal_state(){

    $states = State::all();
//    dd($states[0]);
    $list = array_map(function($i){return $i->state_abbr;}, $states);

    $file = ROOT."/docs/state.txt";
    $content = file_get_contents($file);
    $content = (explode("\n",$content));
    $result = [];
    $key = "";
    foreach($content as $item){

        if(preg_match("/^\w+$/", $item) == true){
            $key = strtoupper(trim($item, "."));
            if(isset($result[$key])){
                continue;
            }else{
                $result[$key] = [];
            }
        }else if(preg_match("/^\w+?\t\d*$/", $item) == true){
            $value = explode("\t",trim($item));
            if(isset($result[$key][$value[0]])){
                $result[$key][$value[0]] += $value[1];
            }else{
                $result[$key][$value[0]] = $value[1];
            }

        }

    }
    $key_mapping = new SellState();
    $key_mapping = $key_mapping->attributes();
    array_shift($key_mapping);
    $key_mapping = array_keys($key_mapping);
    $i = 0;

    foreach($result as $key=>$value){
        if(!in_array($key, $list)){
            unset($result[$key]);
        }else{
            $t = $value;
            printf("%s\n", $key);
            arsort($t);
            $t = array_splice($t, 0, 10);
            foreach($t as $state=>$count){
                $attributes = [$state, $count, $key];//dd($key_mapping, $attributes);
                $attributes = array_combine($key_mapping, $attributes);
                $obj = new SellState($attributes);
                $obj->save();
                printf("%s/%s\n", $i, $i++);
            }

//            $filtered = array_map(function($i) use($t){return sprintf("%s\t%s\n", $i, $t[$i]);}, array_keys($t));//dd($list);
//            printf("%s\n\n", implode("", $filtered));
            unset($t);
            continue;
        }
    }

}

function cli_count_state_sell(){

    if(file_exists("/home/alex/PycharmProjects/alex/docs/state_count.txt")){
        $file = "/home/alex/PycharmProjects/alex/docs/state_count.txt";
    }else{
        $file = ROOT."/docs/state_count.txt";
    }

    $states = State::all();
//    dd($states[0]);
    $list = array_map(function($i){return $i->state_abbr;}, $states);

    $content = file_get_contents($file);

    $content = explode("\n", $content);

    $result = array_map(function($i){return explode("\t", $i);}, $content);

    $new = [];

    foreach($result as $key=>$value){
        if(!in_array(strtoupper(trim($value[0], ".")), $list)){
            unset($result[$key]);
        }else{
            $flag = strtoupper(trim($value[0], "."));
            if(isset($new[$flag])){
                $new[$flag]+= $value[1];
            }else{
                $new[$flag] = $value[1];
            }
        }
    }

    dd($new);
}