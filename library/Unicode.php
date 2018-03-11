<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-1-24
 * Time: 上午10:44
 */


function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', "UNICODE");
}
function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}


/**
 * TEST UNICODE
 * @param $str
 * @return string
 */
function unicode_encode($str){
    return json_encode($str);
}


//See tests