<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-2-5
 * Time: 下午4:45
 */

//加载核心
require_once("../base.php");

function load_mvc_router(){
    global $routers;

    //加载指定控制器
    $controllers = str_replace("\\","",json_encode(load_files(ROOT. DIRECTORY_SEPARATOR. "mvc". DIRECTORY_SEPARATOR. "controllers")));//dd($controllers);
    preg_match_all("/controllers.+?php/", $controllers, $matches);
    foreach($matches[0] as $m){
        $name = explode("/", substr($m, 0, strpos($m, ".php")));
        array_shift($name);
        $router_key = implode("/", $name);
        $name = implode("", array_map("ucfirst", $name)). "Controller";
        $reflect = new ReflectionClass($name);
        $methods = array_diff($reflect->getMethods(ReflectionMethod::IS_PUBLIC), $reflect->getMethods(ReflectionMethod::IS_PUBLIC|| ReflectionMethod::IS_STATIC));

        foreach($methods as $method){
            if($method->name != "__construct"){
                $class = lcfirst(substr($method->class,0, strpos($method->class, "Controller")));
                $routers[$router_key. DIRECTORY_SEPARATOR. $method->name] = $method->class. DIRECTORY_SEPARATOR. $method->name;
            }
        }
    }

}

function call_controller_method($uri, &$routers){
    switch (ROUTER_METHOD){
        case "default":
            /**
             * 过滤并调用Controller/Method
             */

            $uri = implode("/", $uri);
            //过滤URI参数
            $uri = substr($uri, 0,strpos($uri, "?") <=0 ? strlen($uri): strpos($uri, "?"));
            if(in_array($uri, array_keys($routers))){
                $class_func = $routers[$uri];
                $obj = class_exists(ucfirst($class_func[0])) ? ucfirst($class_func[0]) : null;
                //判断调用的控制器类是否存在
                if(!empty($obj)){
                    $controller = new $obj();
                    $controller->$class_func[1]();//调用指定方法
                }else{
                    throw new Exception("Class Not Defined", 401);
                }
            }else{
                throw new Exception("Not Found", 404);
            }
            break;
        default:
            break;
    }
}




load_mvc_router();
//加载控制器
load_files(MVC. DIRECTORY_SEPARATOR. "controllers");
load_files(FRAME. DIRECTORY_SEPARATOR. "hooks");


//路由转意，寻找符合条件的控制器类
$tranlated_routers = array_map(function($i){
    return explode("/", $i);
}, $routers);

try{
    //检查default方法是否存在
    if(isset($tranlated_routers["default"]) && class_exists(ucfirst($tranlated_routers['default'][0]))){
        $ref = new ReflectionClass(ucfirst($tranlated_routers['default'][0]));
        $flag= 0;
        foreach($ref->getMethods() as $method){
            if($method->name == $tranlated_routers['default'][1]){
                $flag = 1;
            }
        }
        if(!$flag){
            throw new Exception("Default uri not set");
        }
    }else{
        throw new Exception("Default uri not set");
    }

    //Pre Hook 加载（用于URI等过滤）
    load_hook_pre();
    $uri = explode("/", substr($_SERVER['REQUEST_URI'], strlen("/index.php/")));//dd($uri); // /index.php/ ->Length: 11
    if(empty($uri[0])){
        $uri = ['default'];
    }

    //Before Controller Hook加载（用于修改参数，设置SESSION等）
    load_hook_before_controller();

    //dd($uri);
    call_controller_method($uri, $tranlated_routers);
    //Done Hook, 扫尾工作
    load_hook_done();
}catch (Exception $e){
    if(RUN_MODE != "ONLINE"){
        print($e->getMessage()."<br>\n");
        $trace = ($e->getTraceAsString());
        $trace = str_replace("\n", "<br>\n", $trace);//dd($trace);
        echo $trace;
    }else{
        print($e->getCode());
        IndexController::_redirect_code(404);
    }

}
