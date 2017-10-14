<?php

namespace client;

class Client
{
    private $client;

    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
        if (!$this->client->connect("127.0.0.1", 9501, 1)) {
            echo "Error {$this->client->errMsg}[{$this->client->errCode}]\n";
        }

        //发消息给服务器
        fwrite(STDOUT, "请输入消息：");
        $msg = trim(fgets(STDIN));
        $this->client->send($msg);

        //从服务器接受消息
        $getMessage = $this->client->recv();
        echo "Get Message From Server:{$getMessage}\n";
    }
}

$client = new Client();
$client->connect();