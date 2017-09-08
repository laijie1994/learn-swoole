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
}