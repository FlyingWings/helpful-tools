<?php


/**
 * 安装composer指定的libraries
 */
function cli_install_composer(){
    exec("php composer.phar install");
}