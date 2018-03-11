<?php

/**
 * 根据参数计算收益
 * 年化|本金|期限
 */
function cli_balance_cal_year(){
    $param = func_get_args()[0];
    if(empty($param)){
        $param = [];
    }
    else if(!is_numeric($param[0])){
        printf("参数错误，%s不是合法的输入\n默认", implode("|", $param));
        $param= [];
    }
    $param = $param + [3.9810, 10000, 365];
    printf("收益：%.3f，年化利率：%.5f，本金：%d，天数：%d\n", $param[0]* $param[1]* $param[2]/36500, $param[0], $param[1], $param[2]);
}