<?php

namespace robot;

use lib\httpManager;
use lib\Log;
use lib\Functions;

class Login
{
    private $ip;
    private $port;
    private $room = [];
    private $num = 0;

    /**
     * Login constructor.
     * @param $ip
     * @param $port
     */
    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * 获取用户id和token
     */
    public function getUserInfo()
    {
        $arr = ["method" => "GameMember.otherLogin", "param" => ["rtype" => 9, "openid" => "PHPWS" . Functions::generate_password(6) . mt_rand(1, 10000000)]];
        $json = json_encode($arr);
        httpManager::req($this->ip, 'gamecoin_3/api/flashapi.php', array('win_param' => $json), false, function ($str) {
            if ($str) {
                $info = json_decode($str, true);
                Log::debug($info, "getUserInfo");
                print_r($info);
                PHP_EOL;
                if ($info["ret"] === 0) {
                    $this->coinCreateConnect(["uid" => $info["user"]["uid"], "session_key" => $info["session_key"]]);
                }
            }
        });
    }

    /**
     * 金币场和server建立连接，进入房间
     * @param $userInfo
     */
    public function coinCreateConnect($userInfo)
    {
        $arr = ["method" => "GameSAR.roomFastEnter", "session_key" => $userInfo["session_key"], "param" => ["gid" => 12]];
        $json = json_encode($arr);
        httpManager::req($this->ip, 'gamecoin_3/api/flashapi.php', array('win_param' => $json), false, function ($str) use ($userInfo) {
//        httpManager::req($this->ip, 'dstars_5/api/flashapi.php', array('win_param' => $json), false, function ($str) use ($userInfo) {
            if ($str) {
                $info = json_decode($str, true);
                if ($info["ret"] == 0) {
                    $client = new WebSocket($this->ip, $this->port, $userInfo["uid"], $userInfo["session_key"]);
                    $aCmd = [];
                    $info["cser"]['_from'] = 'Quick';
                    $aCmd[] = array(
                        '_cmd' => 'enter',
                        '_st' => 'req',
                        '_para' => $info["cser"]
                    );
                    $client->pushMsgToServer("/chess/1", $aCmd);
                }
            }
        });
    }

    /**
     * 金币场快速进房，不经过token验证
     */
    public function coinFastCreateConnect()
    {
        httpManager::req($this->ip, 'gamecoin_3/ltest.php', array('win_param' => ''), false, function ($str) {
            if ($str) {
                $info = json_decode($str, true);
//                print_r($info);PHP_EOL;
//                Log::debug($info,"info");
                if ($info["ret"] == 0) {
                    $client = new WebSocket($this->ip, $this->port, $info["uid"], '');
                    $aCmd = [];
                    $info["cser"]['_from'] = 'Quick';
                    $aCmd[] = array(
                        '_cmd' => 'enter',
                        '_st' => 'req',
                        '_para' => $info["cser"]
                    );
                    $client->pushMsgToServer("/chess/1", $aCmd);
                }
            }
        });
    }

    /**
     * 房卡场和server建立连接
     * @param $userInfo
     */
    public function cardCreateConnect($userInfo)
    {
        if ($this->num <= 0) {
            $this->createRoom($userInfo);
            return;
        }
        $this->num--;
        $client = new WebSocket($this->ip, $this->port, $userInfo["uid"], $userInfo["session_key"]);
        $client->pushMsgToServer("/chess/1", '');
    }

    /**
     * 房卡场开房
     * @param $userInfo
     */
    public function createRoom($userInfo)
    {
        $arr = ["method" => "GameSAR.createRoom", "param" => ["gid" => 4, "rounds" => 8, "pnum" => 4, "hun" => 1, "hutype" => 1, "wind" => 1, "lowrun" => 1, "gangrun" => 1, "dealeradd" => 1, "gfadd" => 1, "spadd" => 1]];
        $json = json_encode($arr);
        httpManager::req($this->ip, 'dstars_4/api/flashapi.php', array('win_param' => $json), false, function ($str) use ($userInfo) {
            if ($str) {
                $info = json_decode($str, true);
                if ($info["ret"] == 0) {
                    //返回的房间信息
                    $this->room = array();
                    $this->num = 4;
                    $this->cardCreateConnect($userInfo);
                }
            }
        });
    }
}
