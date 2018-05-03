<?php

/**
 * 处理订单数据
 */
function cli_deal_data(){
    $file = DATA."/combine-shipping-2.csv";
    $file = file_get_contents($file);

    $regex = "/[^0-9]\n/";
    $file = preg_replace($regex, "", $file);
    $file = mb_convert_encoding($file, "UTF-8");
    file_put_contents(DATA."/dealt_2.txt", $file);
}



