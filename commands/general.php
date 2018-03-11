<?php

/**
 * 计算给定的时间
 * 时区|时间|(时间戳/格式化)
 */
function cli_general_calculate_time(){
    $params = func_get_args()[0];
    ini_set("date.timezone", "Asia/Shanghai");
    if(empty($params)){
        printf("%s", date("Y-m-d H:i:s\n"));
    }
}

/**
 * 用于工作收入
 * 工作天数|应工作|基准
 */
function cli_general_calculate_salary(){
    $params = func_get_args()[0];
    $params = $params+ [21, 22, 12000];
    printf("本月薪资：%.3f， 工作%d/%d天\n", $params[0]*1.0/$params[1]*$params[2], $params[0], $params[1]);
}

/**
 * 计算分月利息
 * 月数|月入|基准
 */
function cli_interest_month()
{
    $params = func_get_args()[0];
    $params = $params + [12, 12000, 4.0];
    $count = 0;
    for ($i = 0; $i < $params[0]; $i++) {
        $count += $params[1] * pow(1 + $params[2] / 1200 * pow(1.1, floor($i/12)), $i)-6000;
    }

    dd($count);
}



