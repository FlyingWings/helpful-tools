<?php

/**
 * 搭建本地隧道到目标地址
 */
function cli_build_ssh_direct(){
    $arguments = [
        "alexfu.cc",
        "root"
    ];
    $name = ["addr", "user"];

    //获取命令行参数
    $argu_new = func_get_args()[0];
    foreach($arguments as $key=>$value){
        if($key == count($argu_new)){
            break;
        }
        $arguments[$key] = $argu_new[$key];
    }

    foreach($arguments as $key=>$value){
        $arguments[$name[$key]] = $value;
        unset($arguments[$key]);
    }

    $user = posix_getpwuid(posix_geteuid())['name'];

    if($user == "root"){
        throw new Exception("Please DO NOT run in root mode");
    }



    if(!is_dir(ROOT. DIRECTORY_SEPARATOR. "data/ssh")){
        mkdir(ROOT. DIRECTORY_SEPARATOR. "data/ssh");
    }

    //找到本地.ssh目录 && 远程.ssh目录(默认远程为Linux)

    if(PHP_OS == "Linux"){
        //MacOS
        $local_ssh_dir = "/home/{$user}/.ssh";
    }else if(PHP_OS == "Darwin"){
        $local_ssh_dir = "/Users/{$user}/.ssh";
    }else{
        throw new Exception("OS not supported");
    }

    //默认远端为Linux
    if($arguments["user"] == "root"){
        $remote_ssh_dir = "/{$arguments["user"]}/.ssh";
    }else{
        $remote_ssh_dir = "/home/{$arguments["user"]}/.ssh";
    }


    $key_file = ROOT. "/data/ssh/id_rsa_".$arguments["addr"];
    
    //本地未保存该密钥时，生成密钥对，并继续
    if(!file_exists($key_file)){
        exec(sprintf("ssh-keygen -f %s -N ''", $key_file));


        $temppath =ROOT."/data/ssh/".$arguments["addr"]."_TEMP";
        mkdir($temppath);

        //SCP from remote
        exec(sprintf("scp %s@%s:%s/authorized_keys %s", $arguments["user"], $arguments["addr"], $remote_ssh_dir, $temppath));
        $content = file_get_contents($temppath. "/authorized_keys");
        $public_key = file_get_contents(ROOT. "/data/ssh/id_rsa_".$arguments["addr"].".pub");


        //SCP back to remote
        if(strpos($content, $public_key) ===0 || strpos($content, $public_key) > 0){
            //Do nothing
        }else{
            file_put_contents($temppath. "/authorized_keys", $public_key, FILE_APPEND);
            exec(sprintf("scp %s/authorized_keys %s@%s:%s/authorized_keys ", $temppath, $arguments["user"], $arguments["addr"], $remote_ssh_dir));
        }

        $key_file = ROOT. "/data/ssh/id_rsa_".$arguments["addr"];
        printf("Key File Generated\n");

    }else{
        $key_file = ROOT. "/data/ssh/id_rsa_".$arguments["addr"];

        printf("Key File Name Existed\n");

    }


    $auth = sprintf("Host %s\n\tHostName %s\n\tUser %s\n\tIdentityFile %s\n\n", $arguments["addr"], $arguments["addr"], $arguments["user"], $key_file);
    $configs = @file_get_contents($local_ssh_dir."/config");
    if(gettype(strpos($configs, $auth)) == "boolean"){
        file_put_contents($local_ssh_dir."/config", $auth, FILE_APPEND);
    }

}

function cli_build_ssh_reverse(){
    $arguments = [
        "alexfu.cc",
        "2222",
        "root"
    ];

    //获取命令行参数
    $argu_new = func_get_args()[0];
    foreach($arguments as $key=>$value){
        if($key == count($argu_new)){
            break;
        }
        $arguments[$key] = $argu_new[$key];
    }

    $name = ["addr", "port", "user"];

    foreach($arguments as $key=>$value){
        $arguments[$name[$key]] = $value;
        unset($arguments[$key]);
    }

    $user =  posix_getpwuid(posix_geteuid())['name'];

    if($user == "root"){
        throw new Exception("Please DO NOT run in root mode");
    }

    if(!is_dir(ROOT. DIRECTORY_SEPARATOR. "data/ssh")){
        mkdir(ROOT. DIRECTORY_SEPARATOR. "data/ssh");
    }


    $temppath =ROOT."/data/ssh/".$arguments["addr"]."_TEMP";
    if(!is_dir($temppath)){
        mkdir($temppath);
    }

    //找到本地.ssh目录 && 远程.ssh目录(默认Linux和MacOS)

    if(PHP_OS == "Linux"){
        //Linuxvi
        $local_ssh_dir = "/home/{$user}/.ssh";
    }else if(PHP_OS == "Darwin"){
        $local_ssh_dir = "/Users/{$user}/.ssh";
    }else{
        throw new Exception("OS not supported");
    }

    if($arguments["user"] == "root"){
        $remote_ssh_dir = "/{$arguments["user"]}/.ssh";
    }else{
        $remote_ssh_dir = "/home/{$arguments["user"]}/.ssh";
    }

    $key_file = ROOT. "/data/ssh/id_rsa_".$arguments["addr"];
    if(!file_exists($key_file)){
        throw new Exception(sprintf("Key File not exists, please run command 'php cli.php build ssh direct %s", implode(" ",$arguments)));
    }

    //从服务器上复制ssh_hosts.json的信息
    exec(sprintf("scp -i %s %s@%s:%s %s",$key_file, $arguments['user'], $arguments['addr'], SSH_HOSTS_FILE, $temppath));


    if(file_exists($temppath. "/ssh_hosts.json")){
        $temp = array_filter(explode("\n", file_get_contents($temppath. "/ssh_hosts.json")));
    }else{
        $temp = [];
    }

    $map = [];
    if(!empty($temp)){
        foreach($temp as $item){
            $t = explode("\t", $item);
            $map[$t[0]] = ["addr"=>$t[1], "user"=>$t[2]];
        }
    }

    $judge_flag = sprintf("%d:127.0.0.1:22", $arguments['port']);
//    dd($map);
    $shell_status = array_filter(explode("\n", shell_exec(sprintf("ps -ef | grep ssh | grep '%s'", $judge_flag))));

//    dd($map, $shell_status);


//    dd($shell_status);
    if((isset($map[$arguments['port']]) && $map[$arguments['port']]['addr'] != gethostname()) || count($shell_status) >2 ){
        throw new Exception(sprintf("PORT %d IN USED FOR %s\n", $arguments['port'], implode(":",$map[$arguments['port']])));
    }else{
        $map[$arguments['port']] = ['addr'=>gethostname(), 'user'=>$user];
    }
//    dd($map, $shell_status);

    foreach($map as $k=>$v){
        $content[] = implode("\t", [$k, implode("\t", $v)]);
    }
//    dd($content);
    file_put_contents($temppath. "/ssh_hosts.json", implode("\n", $content));

    exec(sprintf("scp -i %s %s/ssh_hosts.json %s@%s:%s ",$key_file, $temppath, $arguments['user'], $arguments['addr'], SSH_HOSTS_FILE));

    try{
        printf("PORT LISTEN FOR %s:%s\n", $arguments['addr'], $arguments['port']);
        exec(sprintf("ssh -i %s -N -f -R %d:127.0.0.1:22 %s -l %s", $key_file, $arguments['port'], $arguments['addr'], $arguments['user']));
    }catch (Exception $e){
        printf("%s\n", $e->getMessage());
    }
}

function cli_manage_hosts_view(){
    $content = file_get_contents("/etc/hosts");
    $content = explode("\n", $content);
    $content = array_filter($content);
    $content = array_filter($content, function($i){return gettype(strpos($i, "#")) == "boolean";});
//    $content = array_map(function($i){return ( trim($i, "\n"));}, $content);
    dd($content);

}