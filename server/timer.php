<?php

include __DIR__."../ini.php";

/**
 * 定时器
 * User: 赖杰
 * Date: 2017/9/8
 * Time: 16:48
 */

swoole_timer_tick(2000,function (){
    echo "石老师，为什么不直接说呢？";PHP_EOL;
});

swoole_timer_after(5000,function (){
    echo "因为你太蠢了！";PHP_EOL;
});