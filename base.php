<?php


function load_files($directory){
    if(is_dir($directory)) {
        $dir = opendir($directory);
        $sub_file = [];
        while (($file = readdir($dir)) !== false) {
            if ($file == "." || $file == "..") continue;
            if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                $sub_file[] = load_files($directory . DIRECTORY_SEPARATOR . $file);
            } else {
                require_once($directory . DIRECTORY_SEPARATOR . $file);
                $sub_file[] = $directory . DIRECTORY_SEPARATOR . $file;
            }
        }
        closedir($dir);
        return $sub_file;
    }
    return [];
}

function list_files($directory){
    if(is_dir($directory)) {
        $dir = opendir($directory);
        $sub_file = [];
        while (($file = readdir($dir)) !== false) {
            if ($file == "." || $file == "..") continue;
            if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                $sub_file[] = list_files($directory . DIRECTORY_SEPARATOR . $file);
            } else {
                $sub_file[] = $directory . DIRECTORY_SEPARATOR . $file;
            }
        }
        closedir($dir);
        return $sub_file;
    }
    return [];
}



define("ROOT", __DIR__);

define("AUTOLOAD", ROOT. DIRECTORY_SEPARATOR. "autoload");//核心函数，如dd等

define("LIBRARY", ROOT. DIRECTORY_SEPARATOR. "library");//工具类函数，如Unicode_encode等

define("COMMANDS", ROOT. DIRECTORY_SEPARATOR. "commands");//实际使用的指令

define("CONFIG", ROOT. DIRECTORY_SEPARATOR. "config");//核心配置

define("DATA", ROOT. DIRECTORY_SEPARATOR. "data");

if(!is_dir(DATA)){
    mkdir(DATA);
}

load_files(CONFIG);//首先包含Config目录下的配置项目

require_once(ROOT."/secret_config.php");//根据
require_once(ROOT."/secret_config.php");//根据

require_once(ROOT. DIRECTORY_SEPARATOR. "vendor". DIRECTORY_SEPARATOR. "autoload.php");//Composer Autoloader

load_files(AUTOLOAD);
load_files(LIBRARY."/model");
load_files(LIBRARY);


//dd(sprintf('mysql://%s:%s@%s/%s', DB_USER, DB_PASS, DB_HOST, DB_DEFAULT));

//DB initialize
ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(LIBRARY. DIRECTORY_SEPARATOR. "model");
    $cfg->set_connections(array('development' => sprintf('mysql://%s:%s@%s/%s?charset=utf8', $GLOBALS["db_config"]['DB_USER'], $GLOBALS["db_config"]['DB_PASS'],
        $GLOBALS["db_config"]['DB_HOST'], $GLOBALS["db_config"]['DB_DEFAULT'])));

});
ActiveRecord\Connection::$datetime_format = "Y-m-d H:i:s";






switch (RUN_MODE){
    case "DEV":
        error_reporting(E_ALL & ~E_STRICT);
        ini_set("display_errors", 1);break;
    case "TEST":
        error_reporting((E_ALL | E_WARNING) & ~E_NOTICE & ~E_STRICT);
        ini_set("display_errors", 1);break;
    case "ONLINE":
        error_reporting(0);
        ini_set("display_errors", 0);break;
    default:
        error_reporting(0);
        ini_set("display_errors", 0);break;
}

