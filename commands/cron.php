<?php


/**
 * 列出Cron内容
 */

function cli_cron_list(){
    $user = posix_getpwuid(posix_geteuid())['name'];
//    dd($user);
    $content = shell_exec("sudo cat /var/spool/cron/crontabs/{$user}");

    $res = preg_match_all("/^([^#]{1})(.+)$/m", $content, $ma);

    foreach($ma[0] as $content){
        printf("%s\n", trim($content));
    }
}

/**
 * 将Cronjob文件夹里的内容合并到当前用户下
 */
function cli_cron_init(){
    $cron_dir = ROOT."/cronjob";
    $cronjobs = [];
    $files = list_files($cron_dir);
    foreach($files as $file){
        $str = file_get_contents($file);
        preg_match_all("/^([^#]{1})(.+)$/m", $str, $ma);

        $cronjobs = array_merge($cronjobs, $ma[0]);
    }
    $cronjobs = array_map(function($i){return preg_replace("/ROOT/", ROOT, $i);}, $cronjobs);
    dd($cronjobs);
}