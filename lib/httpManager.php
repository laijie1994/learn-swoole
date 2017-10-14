<?php

namespace lib;

/**
 * Class httpManager
 * $ssl需要依赖openssl，必须在编译swoole时启用--enable-openssl
 * error_reporting(E_ALL^E_NOTICE);
 * swoole_timer_tick(1000, function(){
 * httpManager::req('127.0.0.1', 'texas/ajax/inc.php', array('a'=>'b'), function($str){
 * var_dump($str,count(httpManager::$aHcli), count(httpManager::$aHostIp));
 * });
 * httpManager::req('127.0.0.1', 'texas/ajax/inc.php', array('a'=>'b'), function($str){
 * var_dump($str,count(httpManager::$aHcli), count(httpManager::$aHostIp));
 * });
 * });
 */
class httpManager
{
    public static $aHcli = array();
    public static $aHostIp = array();
    public static $aDnsData = array();

    /**
     * 处理PHP请求
     */
    public static function req($host, $path, $aData = array(), $ssl = true, $callBack = null, $method = 'get')
    {
        if (self::$aHostIp[$host]) {
            $host = self::$aHostIp[$host];
        } elseif (!filter_var($host, FILTER_VALIDATE_IP)) {//如果是URL
            self::$aDnsData[$host][] = array($path, $aData, $ssl, $callBack, $method);
            if (count(self::$aDnsData[$host]) > 1) {//解析中
                return;
            }
            swoole_async_dns_lookup($host, function ($host, $ip) {//异步解析dns
                if ($ip) {
                    httpManager::$aHostIp[$host] = $ip;
                    if (httpManager::$aDnsData[$host]) {
                        foreach (httpManager::$aDnsData[$host] as $aData) {
                            array_unshift($aData, $host);
                            call_user_func_array(array('httpManager', 'req'), $aData);
                        }
                    }
                }
                unset(httpManager::$aDnsData[$host]);
            });
            return;
        }
        $useHcli = false;
        $now = microtime(true);
        foreach (self::$aHcli as $key => $hcli) {
            $time_out = intval($now - $hcli->request_time);
            if ($hcli->busy) {
                if ($time_out > 10) {//超时了
                    unset(self::$aHcli[$key]);
                }
            } else {
                if ($host == $hcli->host) {
                    $useHcli = $hcli;
                    break;
                }
                if ($time_out > 3600) {//超时了
                    unset(self::$aHcli[$key]);
                }
            }
        }
        if (!$useHcli) {
            self::$aHcli[] = $useHcli = new httpClient($host, $ssl);
        }
        $useHcli->send($path, $aData, $callBack, $method);
    }
}