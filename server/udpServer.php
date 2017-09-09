<?php

namespace server;

use lib\Log;

include "../ini.php";
/**
 * udp log server
 * User: 赖杰
 * Date: 2017/9/8
 * Time: 16:29
 */

//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
$serv = new \swoole_server("127.0.0.1", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据接收事件
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server : hello client,nice to meet u");
    var_dump($clientInfo);
    //这里把收到的客户端发来的数据写日志记录
    $str = json_decode($data, true);
    Log::$str["methodName"]($str["data"], $str["fileName"]);
});

//启动服务器
$serv->start();
