<?php

namespace lib;
/**
 * 一些常用帮助方法
 * User: 赖杰
 * Date: 2017/9/5
 * Time: 14:35
 */
class Functions
{

    /**
     * 通过传入的目录获取该目录及子目录下的所有文件名
     * @param $dir
     * @return array
     */
    public function getFileNamesByDir($dir)
    {
        $files = [];
        $this->getAllFiles($dir, $files);
        return $files;
    }

    /**
     * 获取指定路径下的文件名
     * @param $path
     * @param $files
     */
    public function getAllFiles($path, &$files)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file != "." && $file != "..") {
                    $this->getAllFiles($path . "/" . $file, $files);
                }
            }
            $dp->close();
        }
        if (is_file($path)) {
            $files[] = $path;
        }
    }

    /**
     * 上传图片方法（比较原始，可以根据具体需要做相应修改）
     * @return bool
     */
    public function uploadImages()
    {
        $file = $_FILES['file'];//得到传输的数据
        //得到文件名称
        $name = $file['name'];
        $type = strtolower(substr($name, strrpos($name, '.') + 1)); //得到文件类型，并且都转化成小写
        $allow_type = array('jpg', 'jpeg', 'gif', 'png'); //定义允许上传的类型
        //判断文件类型是否被允许上传
        if (!in_array($type, $allow_type)) {
            //如果不被允许，则直接停止程序运行
            return false;
        }
        //判断是否是通过HTTP POST上传的
        if (!is_uploaded_file($file['tmp_name'])) {
            //如果不是通过HTTP POST上传的
            return false;
        }
        $upload_path = WWWROOT . "images/laijietest/"; //上传文件的存放路径
        //开始移动文件到相应的文件夹
        if (move_uploaded_file($file['tmp_name'], $upload_path . $file['name'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 写php配置文件
     * @param $file  string 文件的路径 如 "/data/wwwroot/dstars_4/config/config.alex.php"
     * @param $data  array  需要生成文件的数组
     */
    public function writeFile($file, $data)
    {
        $dir = dirname($file);
        is_dir($dir) or @mkdir($dir, 0777, true);
        clearstatcache(true, $dir);
//        $fileSize = @filesize($file);
        $var = var_export($data, true);
        file_put_contents($file, "<?php \n return {$var};");
    }

    /**
     * 生成随意长度字符串
     * @param int $length
     * @return string
     */
    public static function generate_password($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

    /**
     * @return float
     */
    function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * @return bool|string
     */
    function shell_read()
    {
        $fp = fopen('php://stdin', 'r');
        $input = fgets($fp, 255);
        fclose($fp);
        $input = chop($input);
        return $input;
    }

    /**
     * 获取后缀名
     * @param $file
     * @return mixed
     */
    function get_extension($file)
    {
        $info = pathinfo($file);
        return strtolower($info['extension']??'');
    }

    /**
     * php在指定目录中查找指定扩展名的文件
     * @param $path
     * @param $ext
     * @return array
     */
    function get_files_by_ext($path, $ext)
    {
        $files = array();
        if (is_dir($path)) {
            $handle = opendir($path);
            while ($file = readdir($handle)) {
                if ($file[0] == '.') {
                    continue;
                }
                if (is_file($path . $file) and preg_match('/\.' . $ext . '$/', $file)) {
                    $files[] = $file;
                }
            }
            closedir($handle);
        }
        return $files;
    }

    /**
     * 检查扩展
     * @return bool
     */
    function checkExtension()
    {
        $check = true;
        if (!extension_loaded('swoole')) {
            print_r("[扩展依赖]缺少swoole扩展\n");
            $check = false;
        }
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            print_r("[版本错误]PHP版本必须大于7.0.0\n");
            $check = false;
        }
        if (version_compare(SWOOLE_VERSION, '1.9.18', '<')) {
            print_r("[版本建议]Swoole推荐使用1.9.18版本,之前版本存在bug\n");
            $check = false;
        }
        if (SWOOLE_VERSION[0] == 2) {
            print_r("[版本错误]不支持2.0版本swoole，请安装1.9版本\n");
            $check = false;
        }
        if(!class_exists('swoole_redis')){
            print_r("[编译错误]swoole编译缺少--enable-async-redis,具体参见文档http://docs.sder.xin/%E7%8E%AF%E5%A2%83%E8%A6%81%E6%B1%82.html");
            $check = false;
        }
        if (!extension_loaded('redis')) {
            print_r("[扩展依赖]缺少redis扩展\n");
            $check = false;
        }
        if (!extension_loaded('pdo')) {
            print_r("[扩展依赖]缺少pdo扩展\n");
            $check = false;
        }
        return $check;
    }

    /**
     * 断点调试
     */
    function breakpoint()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        print_r($backtrace);
        print_r("断点中任意键继续:");
        shell_read();
    }

    /**
     * 是否是mac系统
     * @return bool
     */
    function isDarwin()
    {
        if(PHP_OS=="Darwin"){
            return true;
        }else{
            return false;
        }
    }
}