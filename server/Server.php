<?php

namespace server;

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set([
            "worker_num" => 8,
            "daemonize" => false,
        ]);
        $this->serv->on("Start", [$this, "onStart"]);
        $this->serv->on("Connect", [$this, "onConnect"]);
        $this->serv->on("Receive", [$this, "onReceive"]);
        $this->serv->on("Close", [$this, "onClose"]);
        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "Server Start\n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        $serv->send($fd, "Hello Client {$fd} !", $from_id);
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd, $data, $from_id);
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

}

//启动服务器
$server = new Server();