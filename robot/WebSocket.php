<?php

namespace robot;

use lib\Log;

/**
 * Class WebSocket
 */
class WebSocket
{
    /**
     * 用来保存建立连接发送的数据
     * @var array
     */
    public $aQueue = [];
    /**
     * @var string 服务端IP地址
     */
    public $ip;
    /**
     * @var string 服务端端口
     */
    public $port;
    /**
     * @var
     */
    public $uid;
    /**
     * @var void  swoole 定时器返回的tickid
     */
    public $tickId;
    /**
     * @var array  存用户session信息
     */
    public $dst = [];
    /**
     * @var array 玩家手上的牌
     */
    public $handCards = [];

    /**
     * @var \swoole_http_client
     */
    private $client;

    /**
     * 建立一个webSocket连接
     * WebSocket constructor.
     * @param $ip
     * @param $port
     * @param $uid
     * @param $token
     */
    public function __construct($ip, $port, $uid, $token)
    {
        $this->client = new \swoole_http_client($ip, $port);

        $this->client->on('message', function ($_cli, $frame) {
//            print_r(json_decode($frame->data, true));PHP_EOL;
            $getMessage = json_decode($frame->data, true);
            //日志记录server返回的操作日志
//            Log::dayLog($getMessage,"playLog");
//            Log::dayLog(["num"=>Main::$num],"PlayNum");
            foreach ($getMessage as $key => $value) {
                if ($key == "_events") {
                    foreach ($value as $k => $v) {
                        $functionName = "on_" . $v["_cmd"];
//                        Log::dayLog($v["_cmd"], "playLog");
                        if (method_exists($this, $functionName)) {
                            $this->$functionName($v);
                        }
                    }
                } else {
                    continue;
                }
            }
        });

        $this->client->on('close', function ($_cli) {
            $this->tickId && swoole_timer_clear($this->tickId);
            Main::$num--;
        });

        $this->client->on('error', function ($_cli) {
            $this->tickId && swoole_timer_clear($this->tickId);
            Main::$num--;
        });

        $this->client->upgrade("/?uid={$uid}&token={$token}", function ($cli) {
            if ($this->aQueue) {
                $this->pushMsgToServer($this->aQueue["uri"], $this->aQueue["events"]);
            }
            Main::$num++;
            //在这里设置一个定时器，每三秒向服务端发送一个心跳包
            $this->tickId = swoole_timer_tick(3000, function () {
                $sendData = [];
                $sendData[] = [
                    '_cmd' => 'heart_beat',
                    '_st' => 'req',
                    '_para' => []
                ];
                $this->pushMsgToServer($this->aQueue["uri"], $sendData);
            });
        });
    }

    /**
     * 发送消息给server
     * @param $uri
     * @param $events
     * @return bool
     */
    public function pushMsgToServer($uri, $events)
    {
        if ($this->client && $this->client->isConnected()) {
//            echo 'host=dstars&uri=' . $uri . '&msgid=http_req@@@@{"_check": 123456789,"_ver": 1,"_dst":[],"_events": ' . $events . '}'.PHP_EOL;
//            $this->client->push('host=dstars&uri=' . $uri . '&msgid=http_req@@@@{"_sn": 223343,"_ver": 1,"_events": ' . $events . '}');
            $json = json_encode(array(
                "_check" => 123456789,
                "_ver" => 1,
                "_dst" => $this->dst,
                "_events" => $events
            ));
            $this->client->push('host=dstars&uri=' . $uri . '&msgid=http_req@@@@' . $json);
            return true;
        }
        //可能还在建立连接中，所以先存到数组中
        $this->aQueue = array("uri" => $uri, "events" => $events);
    }

    /**
     * 回复server准备
     */
    public function on_session($data)
    {
        $this->dst = $data['_para'];
    }

    /**
     * 回复server准备
     */
    public function on_ask_ready($data)
    {
        Log::dayLog($data, "on_ask_ready");
        $events = [];
        $events[] = [
            '_cmd' => 'ready',
            '_st' => 'req',
            '_para' => [],
        ];
        $this->pushMsgToServer($this->aQueue["uri"], $events);
    }

    //server给chair1发消息，通知他自己的牌 以及庄家、骰子 圈风
    /*public function on_deal($data)
    {
        Log::dayLog($data, "on_deal");
        $this->handCards = $data["_para"]["cards"];
    }*/

    public function on_leave($data)
    {
        Log::dayLog($data, "on_leave");
        $this->client->close();
    }

    /**
     * 游戏开始
     * @param $data
     */
    public function on_game_start($data)
    {
        Log::dayLog($data, "on_game_start");
        Main::$play++;
    }

    /**
     * 游戏结束
     * @param $data
     */
    public function on_gameend($data)
    {
        Log::dayLog($data, "on_gameend");
        Main::$play--;
    }

}