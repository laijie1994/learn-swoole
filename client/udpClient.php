<?php

namespace client;

/**
 * udp client
 * User: Alex
 * Date: 2017/9/9
 * Time: 11:48
 */
$client = new \swoole_client(SWOOLE_SOCK_UDP);

if (!$client->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}

$client->send("hello world\n");

echo $client->recv();

$client->close();

