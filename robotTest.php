<?php

include_once "./ini.php";

//$ret['Socket接收缓冲区'] = (socket_get_option(socket_create(AF_INET, SOCK_DGRAM, SOL_UDP), SOL_SOCKET, SO_RCVBUF)/1024/1024).'M';
//$ret['Socket发送缓冲区'] = (socket_get_option(socket_create(AF_INET, SOCK_DGRAM, SOL_UDP), SOL_SOCKET, SO_SNDBUF)/1024/1024).'M';
//print_r($ret);die;


//$ip = "211.159.188.91";
//$port = 8001;

$ip = "192.168.2.6";
$port = 8071;

$login = new robot\Login($ip, $port);

/*$login->coinFastCreateConnect();

swoole_timer_tick(100000, function () {
    Log::dayLog(["num" => Main::$num], "OnlineNum");
    Log::dayLog(["num" => Main::$play], "PlayNum");
});*/

/*$login->getUserInfo();

swoole_timer_tick(100000,function(){
    Log::dayLog(["num"=>Main::$num],"OnlineNum");
    Log::dayLog(["num"=>Main::$play],"PlayNum");
});*/

start($login);

function start($login)
{
    swoole_timer_after(5000, function () use ($login) {
        if (robot\Main::$num < 10000) {
            $login->coinFastCreateConnect();
        }
        start($login);
    });
}

swoole_timer_tick(100000, function () {
    lib\Log::dayLog(["num" => robot\Main::$num], "OnlineNum");
    lib\Log::dayLog(["num" => robot\Main::$play], "PlayNum");
});

