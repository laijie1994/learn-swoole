<?php

namespace lib;

/**
 * swoole写的一个http客户端类
 * Class httpClient
 */
class httpClient
{
    public $host;
    public $request_time;
    public $busy = false;
    private $cli; //请求时间
    private $try;
    private $ssl = false;

    public function __construct($host, $ssl = true)
    {
        $this->request_time = microtime(true);
        $this->host = $host;
        $this->ssl = $ssl;
    }

    public function conn()
    {
        if ($this->cli) {
            return;
        }
        $this->cli = new \swoole_http_client($this->host, $this->ssl ? 443 : 80, $this->ssl);
        $this->cli->setHeaders([
            'Connection' => 'Keep-Alive',
        ]);
        $this->cli->on('close', function ($cli) {
            $this->cli = false;
            if ($this->try && $this->busy) {
                $this->tryOne();
            }
        });
        $this->cli->on('error', function ($cli) {
            $this->cli = false;
        });
    }

    public function tryOne()
    {
        $this->try = false;
        $this->httpSend();
    }

    /**
     * 发送请求了
     */
    public function httpSend()
    {
        $this->conn();
        if ($this->method == 'get') {
            $this->cli->get($this->path . '?' . $this->data, function ($cli) {
                if ($cli->statusCode == -2) {
                    $this->cli = false;
                    $this->tryOne();
                } else {
                    $this->callBack($cli);
                }
            });
        } else {
            $this->cli->post($this->path, $this->data, function ($cli) {
                $this->callBack($cli);
            });
        }
    }

    /**
     * 收到数据后回调
     */
    public function callBack($cli)
    {
        $this->busy = false;
        $data = $cli->body;
        if (is_callable($this->cb)) {
            call_user_func($this->cb, $data);
        }
    }

    /**
     * 发送数据 外部调用方法
     */
    public function send($path, $aData = array(), $callBack = null, $method = 'get')
    {
        $this->try = true;
        $this->busy = true;
        $this->request_time = microtime(true);
        $this->path = '/' . $path;
        $this->data = ($method == 'get') ? http_build_query($aData) : $aData;
        $this->method = $method;
        $this->cb = $callBack;
        $this->httpSend();
    }
}