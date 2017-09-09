<?php

namespace lib;

/**
 * 写日志的类
 * Class Log
 * @package lib
 */
class Log
{
    /**
     * 写日志,带时间
     * @param mixed $params 信息
     * @param string $file 文件名
     */
    public static function debug($params, $file = 'debug.txt')
    {
        clearstatcache();
        $file = PATH_DAT . 'log/' . $file . '.php';
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $size = file_exists($file) ? @filesize($file) : 0;
        $time = date('Y-m-d H:i:s');
        $contents = ($size ? '' : "<?php die();?>\n") . $time . "\n" . var_export($params, TRUE) . "\n\n";
        @file_put_contents($file, $contents, $size < 64 * 1024 ? FILE_APPEND : NULL);
    }

    /**
     * 每日日志，每天记录一个文件
     * @param mixed $params 要记录的内容
     * @param string $file 文件名
     * @param string $folder 文件夹的名字
     * @param bool $daylog 是否每天一个文件，如果为true，则每天会新建一个文件
     */
    public static function dayLog($params, $file = 'daylog.txt', $folder = 'log', $daylog = TRUE)
    {
        clearstatcache();
        $day = date('Ymd');
        $file = PATH_DAT . $folder . '/' . $file;
        if ($daylog) {
            $file .= $day;
        }
        $file .= '.php';
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $size = file_exists($file) ? @filesize($file) : 0;
        $time = date('Y-m-d H:i:s');
        $contents = ($size ? '' : "<?php die();?>\n") . $time . "\n" . var_export($params, TRUE) . "\n\n";
        @file_put_contents($file, $contents, FILE_APPEND);
    }

    /**
     * tcp方法写日志
     * @param $data
     * @param $methodName
     */
    public static function tcpLog($data, $methodName)
    {
        //创建一个socket套接流
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        /****************设置socket连接选项，这两个步骤你可以省略*************/
        //接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
        //发送套接流的最大超时时间为6秒
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
        /****************设置socket连接选项，这两个步骤你可以省略*************/

        //连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
        if (socket_connect($socket, '127.0.0.1', 9501) == false) {
            echo 'connect fail massege:' . socket_strerror(socket_last_error());
        } else {
            $message = 'l love you 我爱你 socket';
            //转为GBK编码，处理乱码问题，这要看你的编码情况而定，每个人的编码都不同
            $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
            //向服务端写入字符串信息

            if (socket_write($socket, $message, strlen($message)) == false) {
                echo 'fail to write' . socket_strerror(socket_last_error());
            } else {
                echo 'client write success' . PHP_EOL;
                //读取服务端返回来的套接流信息
                while ($callback = socket_read($socket, 1024)) {
                    echo 'server return message is:' . PHP_EOL . $callback;
                }
            }
        }
        socket_close($socket);//工作完毕，关闭套接流
    }

    /**
     * udp方法写日志
     * @param $data
     * @param $methodName
     */
    public static function udpLog($data, $fileName, $methodName)
    {
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $msg = json_encode(["data" => $data, "fileName"=>$fileName, "methodName" => $methodName]);
        $len = strlen($msg);
        socket_sendto($sock, $msg, $len, 0, '127.0.0.1', 9501);
        socket_close($sock);
    }

}