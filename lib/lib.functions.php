<?php
/**
 * 
 * @author KevinXie
 * @time 2016/08/23
 */
class Lib_Functions {
    private $isPhpVersion;
    protected static $_instance = array();
    public $config;
    private $runStart;
    
	public function __construct()
	{
		global $config;
		$this->config = $config;
		
		$this->runStart = $this->time( true);
	}
	
	/**
	 * 设置COOKIE
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @param unknown_type $time 过期时间,�?则关闭浏览器失效getFlashVars
	 */
	public function setCookie($name, $value, $time=0){
	    $expires = $time ? $this->time()+(int)$time : 0;
	    setcookie( $name, $value, $expires, '/');
    }

    public function header(){
		header( "Content-Type:text/html; charset=utf-8" );
    }

    public function nocache(){
        header("Pragma:no-cache");
        header("Cache-Type:no-cache, must-revalidate");
        header("Expires: -1");
    }

    public function dp3p(){
	   header("P3P:CP='ALL DSP CURa ADMa DEVa CONi OUT DELa IND PHY ONL PUR COM NAV DEM CNT STA PRE'");
    }

    /**
     * 字符串转二进制
     * @date 2016年8月29日
     * @param string $txt_str
     * @return bin
     */
    public function str2bin($txt_str)
    {
        $len = strlen($txt_str);
        $bin = '';
        for($i = 0; $i < $len; $i++  )
        {
            $bin .= strlen(decbin(ord($txt_str[$i]))) < 8 ? str_pad(decbin(ord($txt_str[$i])), 8, 0, STR_PAD_LEFT) : decbin(ord($txt_str[$i]));
        }
        return $bin;
    }
    
    /**
     * 获取IP
     * @return IP
     */
	function getip() {
		if ($_SERVER ["HTTP_X_FORWARDED_FOR"]){
			$ipArr = explode(",",$_SERVER ["HTTP_X_FORWARDED_FOR"]);
			$ip = $ipArr[0];
		}else if ($_SERVER ["HTTP_CLIENT_IP"])
			$ip = $_SERVER ["HTTP_CLIENT_IP"];
		else if ($_SERVER ["REMOTE_ADDR"])
			$ip = $_SERVER ["REMOTE_ADDR"];
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "HTTP_CLIENT_IP" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "REMOTE_ADDR" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else
			$ip = "Unknown";
		return $ip;
	}

	/**
	 * 验证ip地址
	 * @param        string    $ip, ip地址
	 * @return        bool    正确返回true, 否则返回false
	 */
	public static function checkIP( $ip ){
		$ip = trim( $ip );
		$pt = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
		if( preg_match( $pt, $ip ) === 1 ){
			return true;
		}
		return false;
	}

	/**
	 * 是否为国内的IP
	 * @param string $ip
	 * @return bool
	 */
	public function isCnIP($ip = 0) {
		$ip = $ip ? $ip : $this->getip();
		if(!self::checkIP($ip)) return false;

		$cidrs = include WWWROOT . 'config/config.cniplist.php';
		if (!$cidrs) return false;

		foreach( $cidrs as $cidr ){
			list( $net, $mask ) = explode ( '/', $cidr );
			if( ( ip2long ( $ip ) & ~ ( ( 1 << ( 32 - $mask ) ) - 1) ) === ip2long ( $net ) ){
				return true;
			}
		}
		return false;
	}

    /**
     * 返回浏览器信�?ver为版本号,nav为浏览器�?     */
    function getbrowser(){
    	$browsers = "mozilla msie gecko firefox ";
		$browsers.= "konqueror safari netscape navigator ";
		$browsers.= "opera mosaic lynx amaya omniweb";
		$browsers = split(" ", $browsers);
		$nua = strToLower( $_SERVER['HTTP_USER_AGENT']);
		$l = strlen($nua);
		for ($i=0; $i<count($browsers); $i++){
		  $browser = $browsers[$i];
		  $n = stristr($nua, $browser);
		  if(strlen($n)>0){
		   $arrInfo["ver"] = "";
		   $arrInfo["nav"] = $browser;
		   $j=strpos($nua, $arrInfo["nav"])+$n+strlen($arrInfo["nav"])+1;
		   for (; $j<=$l; $j++){
		     $s = substr ($nua, $j, 1);
		     if(is_numeric($arrInfo["ver"].$s) )
		     $arrInfo["ver"] .= $s;
		     else
		     break;
		   }
		  }
		}
		return $arrInfo;
    }


    public function magic_quote( $mixVar){
        if( get_magic_quotes_gpc()){
            if(is_array( $mixVar)){
                foreach ( $mixVar as $key => $value){
                    $temp[$key] = $this->magic_quote( $value);
                }
            }else{
                $temp = stripslashes( $mixVar);
            }
            return $temp;
        }else{
        	return $mixVar;
        }
    }

    public function stripslashes_deep($value)
	{
	    $value = is_array($value) ?
	                array_map('stripslashes_deep', $value) :
	                stripslashes($value);
	    return $value;
	}

    /**
     * 数组分页
     * @param unknown_type $array
     * @param unknown_type $num 每页显示个数
     * @param unknown_type $now 当前页码,0开�?     * @param unknown_type $url 除去p=后的url
     * @return unknown
     */
    function apart_page( $array, $num, $now, $url){
		$count = count( $array);
		$now = min($now, floor($count/$num));
		if($count < $num){
			return array($array, '');
		}else{
			if($now!=0){
				$str .= '<a href="'.$url.'">|&lt;</a> <a href="'.$url.'?p='.($now-1).'">&lt;</a> ';
			}
			$str .= $num*$now+1;
			$str .= "~";
			$str .= ($num*$now)+$num;
			if($now!=floor($count/$num)){
				$str .= '<a href="'.$url.'?p='.($now+1).'">&gt;</a> <a href="'.$url.'?p='.floor($count/$num).'">&gt;|</a> ';
			}
			return array(array_slice($array, $num*$now, $num), $str);
		}
	}

	/**
     * �?arr的长和宽等比例缩小至$arrTo resize(array($array['width'],$array['height']), array(160,120))
     * @return unknown
     */
    function resize($arr, $arrTo ){
        $arr[0] = $arr[0]>10 ? $arr[0] : $arrTo[0];
        $arr[1] = $arr[1]>10 ? $arr[1] : $arrTo[1];
        $arrTo[0] = $arrTo[0]<=0 ? 160 : $arrTo[0];
        $arrTo[1] = $arrTo[1]<=0 ? 120 : $arrTo[1];
        $temp = $arr;
        //如果宽度超出
        if( $arr[0] > $arrTo[0]){
            $temp[0] = $arrTo[0];
            $temp[1] = (int)($temp[0]*$arr[1]/$arr[0]);
            if( $temp[1] > $arrTo[1]){
                $temp[1] = $arrTo[1];
                $temp[0] = (int)($arr[0]*$temp[1]/$arr[1]);
            }
        }
        //如果高度超出
        if( $arr[1] > $arrTo[1] ){
            $temp[1] = $arrTo[1];
            $temp[0] = (int)($arr[0]*$temp[1]/$arr[1]);
            if( $temp[0] > $arrTo[0]){
                $temp[0] = $arrTo[0];
                $temp[1] = (int)($temp[0]*$arr[1]/$arr[0]);
            }
        }
        return $temp;
    }

    /**
     * 返回UNIX时间
     * 
     * @param boolen $float 是否精确到微秒
     * @return int/float
     */
	public function time( $float=false){
		return $float ? microtime( true) : time();
	}

	public function runTime(){
	    return $this->time( true) - $this->runStart;
	}

	/**
	 * 获取用户头像地址
	 * @param unknown_type $mid 用户ID
	 * @param unknown_type $sid 站点ID
	 * @param unknown_type $typ 头像类型0小头像1中头像2大头像
	 * @param unknown_type $sitemid 用户在平台的ID
	 * @param unknown_type $ver 头像缓存版本,用于squid
	 * @param unknown_type $flag 标志是否直接取地址
	 */
    function getIcon($mid, $sid, $typ, $sitemid, $mstatus=0, $mimg=''){
    	switch ( $sid){
    		case 1: //校内
    		case 19: //雅虎
    			$icon = strlen($mimg)>=10 ? $mimg : $this->config['baseUrl'].'images/avatar.jpg';
    		break;
    		case 32: //百度
    			$icon = "http://himg.baidu.com/sys/portrait/item/" . ($mimg ? $mimg : '000000000000000000000000') . ".jpg";
    		break;
    		default:
    			$icon = "http://uchome.manyou.com/avatar/" . $sitemid . ($typ==0 ? "?thumb" : ($typ==1?"?small":"?normal"));
    		break;
    	}

    	return $icon;
    }

    /**
	 * @param int $uid
	 * @return string
	 */
	function get_avatar($mid, $size=0) {
		$size = in_array($size, array(0, 1, 2)) ? $size : 1;
		$mid = abs(intval($mid));
		$mid = sprintf("%09d", $mid);
		$dir1 = substr($mid, 0, 3);
		$dir2 = substr($mid, 3, 2);
		$dir3 = substr($mid, 5, 2);
		return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($mid, -2)."$size.jpg";
	}

	//获取个人主页
	function get_site($sid, $sitemid, $url=''){
		if( $sid > 99){ //漫游个人主页地址
			return 'http://uchome.manyou.com/profile/' . $sitemid;
		}elseif ( $sid==19){ //雅虎
            return 'http://guanxi.koubei.com/showprofile.php?uid=0' . $sitemid;
        }elseif ( $sid==1){ //校内
            return 'http://xiaonei.com/profile.do?id=' . $sitemid;
        }
		return 'http://uchome.manyou.com/profile/' . $sitemid;
	}
	/**
	 * 	制作flash参数
	 */
	function getFlashVars( $aLoad) {
		$aVars['mid'] 		= $aLoad['mid'];
		$aVars['sid'] 		= $aLoad['sid'];
		$aVars['sesskey'] 	= $aLoad['sesskey'];
		$aVars['name'] 		= $aLoad['name'];
		$aVars['gateway'] 	= $aLoad['gateway'];
		$aVars['flashUrl'] 	= $aLoad['flashUrl'];
		$aVars['dataUrl'] 	= $aLoad['dataUrl'];
		$aVars['imageUrl'] 	= $aLoad['imageUrl'];
		$aVars['webroot'] 	= $aLoad['webroot'];
		$aVars['isFirst'] 	= $aLoad['isFirst'];
		$aVars['flashver'] 	= json_encode($aLoad['flashver']);
		$aVars['hallip'] 	= json_encode($aLoad['hallip']);
		$aVars['other'] 	= json_encode($aLoad['other']);
		$aVars['time'] 		= NOW;
		$aVars['isFans'] 	= $aLoad['isFans'];
		$aVars['isCreate'] 	= $aLoad['isCreate'];
		$aVars['isPayUrl']	= $aLoad['isPayUrl'];
		$aVars['ispay']		= $aLoad['ispay'];
		
		foreach ( $aVars as $key => $value){
			$flashvars .= $key . '=' . urlencode($value) . '&';
		}
		return substr( $flashvars, 0, -1);
	}

	public function __destruct(){

	}
	
	public function writeErrorLog($msg){
		echo PATH_LOG;die;
	}
	
	public function uint( $num){
		return max(0, (int)$num);
	}
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 */
	public function escape( $string){
		if ( oo::functions()->isPhpVersion()) {
			return addslashes( trim($string));
		}
		return mysql_escape_string( trim($string));
	}
	/**
	 * 判断当前环境php版本是否大于大于等于指定的一个版本
	 * @param sreing $version default=5.3.0
	 * @return boolean 大于true,小于false
	 */
	public function isPhpVersion( $version = '5.3.0' ) {
		if ( $this->isPhpVersion) {
			$php_version = $this->isPhpVersion;
		} else {
			$php_version = explode( '-', phpversion() );
		}
		$is_pass = strnatcasecmp( $php_version[0], $version ) >= 0 ? true : false;
		return $is_pass;
	}
	/** 
	 * 将字符串ID转换为对应的数字ID 不可逆  转换后不唯一
	 */
	public function midToNumber( $mid){
		return is_numeric($mid) ? $mid : abs( crc32($mid));
	}
	/**
	 * 把数组序列成Server识别的.有缺陷,不能是null类型的
	 * @param Array $array
	 */
	public static function serialize( $array ){
		return str_replace( '=', ':', http_build_query( $array, null, ',' ) );
	}

	/**
	 * 把字符串反序列成索引数组
	 * @param String $string
	 */
	public static function unserialize( $string ){
		parse_str( str_replace( array( ':', ',' ), array( '=', '&' ), $string ), $array );
		return (array) $array;
	}
	/**
     * 获取网页数据
     * @param string $url
     * @param array $post_data post的数据,为空时表示get请求
     * @param string $json 返回数据格式，0表示json 1原数据返回
     * @return array/int
     */
	public static function curl( $url, $post_data=array(), $json=1, $timeout=20) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		if(!empty($post_data))
		{
			curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		$result = curl_exec ( $ch );
		curl_close($ch);
		return $data = empty($json) ? $result : json_decode($result, true);
	}
	/**
     * 获取HTTPS数据,这个方法请求https没内存泄露的情况
     * @param string $url
     * @param array $post_data post的数据,为空时表示get请求
     * @param string $json 返回数据格式，0表示json 1原数据返回
     * @return array/int
     */
	public static function httpscurl( $url, $post_data=array(), $json=1, $timeout=20) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		
		if(!empty($post_data))
		{
			curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		$result = curl_exec ( $ch );
		curl_close($ch);
		return $data = empty($json) ? $result : json_decode($result, true);
	}
	/**
	 * 返回
	 * @param unknown $array
	 */
	public function sendData( $array, $stime){
		$etime = oo::functions()->time( true);
		$aRet = array();
		$aRet['code'] = $array['code'];
		$aRet['codemsg'] = $array['codemsg'];
		$aRet['sid'] = GHSID;
		$aRet['data'] = $array['data'];
		$aRet['time'] = NOW;
		$aRet['exetime'] = $etime - $stime; //脚本执行时间
// 		if (oo::setConfiginc('hall','production')) {
// 			echo gzcompress( json_encode( $aRet), 9);
// 		} else {
			echo json_encode( $aRet);
// 		}
// 		echo json_encode( $aRet);
// 		exit();
	}
	/**
	 * 解析JS传中文参数
	 * 
	 * @author dulu
	 * 
	 * @param String $str 
	 */
    public static function js_unescape($str) {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i+1] == 'u') {
                $val = hexdec(substr($str, $i+2, 4));
                if ($val < 0x7f) {
                	 $ret .= chr($val);
                }
                else if($val < 0x800) { 
                	$ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
                }
             	else {
             		$ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
             	} 
                $i += 5;
            }
            else if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            }
            else {
				$ret .= $str[$i];
			}
        }
        return $ret;
	}

	/**
	 * 计算两个时间戳间隔天数，例如 2017-05-08 与 2017-05-10 中间间隔1天，这个一天的概念不是等于24小时
	 * @param $time1
	 * @param $time2
	 * @return int
	 */
	public static function getDays($time1,$time2){
		if($time1>$time2) list($time1,$time2)=array($time2,$time1);//保证前小后大
		$dateTime1 = date("Ymd",$time1);
		$dateTime2 = date("Ymd",$time2);
		if($dateTime1 == $dateTime2){
			return 0;
		}
		for($i=0;;$i++){
			if(date("Ymd",mktime(0,0,0,date("m"),date("d")-$i-1,date("Y"))) == $dateTime1){
				return $i;
				break;
			}
		}
	}
}