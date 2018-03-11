<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-5
 * Time: 上午10:18
 */

require_once("base.php");


function load_command($func){
    if(function_exists($func)){
        $ref = new ReflectionFunction($func);
        return str_replace("\n", ";", preg_replace("[\*\n|\/|\*|\ ]","",$ref->getDocComment()));
    }else{
        return "No Function Existed";
    }
}

function get_cli_functions($files){
    $res = json_encode(array_map(function($i){
        if(is_array($i)){
             return get_cli_functions($i);

         }else{
            if(strpos($i, ".php") <=0 ){
                return [];
            }else if(preg_match_all("/cli_\w+/", file_get_contents($i), $matches)){
                @$file_name = array_pop(explode("/",$i));
                @$identifier = array_shift(explode(".",$file_name));
                $funcs = array_map(
                    function($func) use ($identifier){return str_replace("cli", $identifier,$func);}, $matches[0]);

                return $funcs;
             }
         }
    }, $files));

    $reg = preg_match_all("/[a-z]+?_\w+/", $res, $matches);

    return $matches[0];
}


$cmd = load_files(COMMANDS);


function load_functions($argv = [], $cmd=[]){
    $func_list = array_map(function($i){return explode("_", $i);},get_cli_functions($cmd));
    $matches = [];
    $arguments = $argv;
    $arguments[0] = "cli";
    $find_flag = $i = 0;
    $total = [];
    $preg="";
    for($i=0;$i< count($arguments); $i++){
        $col = array_column($func_list, $i);
        $new_func = $total = [];
        foreach($col as $k=>$v){
            if($i == 0){
                $new_func[]= $k;
            }
            else if(preg_match("/^".$arguments[$i]."[a-zA-Z0-9]*$/", $v)){
                $new_func[]= $k;
            }
        }

        $preg = "/".$arguments[$i]."\w*/";
        foreach($new_func as $k){
            $total[] = $func_list[$k];
        }
        if(count($total) > 1){
            $find_flag = 1;
            $func_list = $total;
            continue;
        }
        if(count($total) == 1){
            $find_flag = 1;
            $func_list = $total;
            break;
        }else{
            $i-=1;
            break;
        }
    }



    if($find_flag == 1 && count($func_list)==1) {

        $ar = array_splice($arguments, $i+1);
        $func_list[0][0] = "cli";
        call_user_func(implode("_", $func_list[0]), $ar);
    }else if($find_flag == 1){
        global $COMMAND_PROMPT;
        $func_type_list = [];
        foreach ($func_list as $f){
            $func_type_list[$f[0]][]= $f;
        }

        $pad_string = str_pad("-", 80, "-");
        exec("clear");
        printf("%s\n", $pad_string,$pad_string,$pad_string);

        foreach($func_type_list as $key=>$value){

            printf("%s\t%s\n", str_pad("", 30, "-", STR_PAD_LEFT), $COMMAND_PROMPT[$key]."|".$key);
            foreach($value as $f){
                $f[0] = "cli";
                $func_info = implode("_", $f);
                printf("%s\t%s\n", str_pad($func_info, 30), str_pad(load_command($func_info), 30));
            }
        }

        printf("%s\n", $pad_string,$pad_string,$pad_string);


    }else{
        echo "Not found\n";
    }
}
try{
    load_functions($argv, $cmd);
}catch (Exception $e){
    print($e->getMessage());

}
