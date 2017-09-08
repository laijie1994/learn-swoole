<?php
/**
 * $db = new Lib_Mysqli( array ( array('192.168.1.239:3306', 'root', 'password', '')));
 * @author KevinXie
 * @time 2017/01/12
 */
class Lib_Mysqli{
	private $host;//主机
	private $port;//端口
	private $user;//用户
	private $password;//密码
	private $dbname;//db名
	/**
	 * mysqli对象
	 * @var mysqli
	 */
	static private $mysqli;//mysqli对象
	/**
	 * @var mysqli_stmt
	 */
	static private $stmt;//预处理对象
	/**
	 * 为了兼容老版 采用二维数组
	 * 参数 array ( array('192.168.1.239:3306', 'socialgame', 'socialgame', ''))
	 * @param unknown_type $servers
	 */
	public function __construct( $servers){
		$servers = $servers[0];//为了兼容老版
		$aHost = explode(':', $servers[0]);
		$this->host = $aHost[0];
		$this->port = isset( $aHost[1]) ? $aHost[1] : '3306';//默认端口3306
		$this->user = $servers[1];
		$this->password = $servers[2];
		$this->dbname = $servers[3];
	}
	/**
	 * 检查并连接数据库
	 * @return mysqli
	 */
	public function connect(){
		if (isset(self::$mysqli[$this->host]) && !empty(self::$mysqli[$this->host]) ) {//如果已经连接
			return self::$mysqli[$this->host];
		}
		if ( ! class_exists( mysqli)) {
// 			die('This Lib Requires The Mysqli Extention!');
			return false;
		}
		self::$mysqli[$this->host] = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port);
		if ( $error = mysqli_connect_error()) {
			$this->errorlog( $error);
			return false;
		}
		self::$mysqli[$this->host]->query("SET SQL_MODE='',CHARACTER_SET_CONNECTION='utf8',CHARACTER_SET_RESULTS='utf8',CHARACTER_SET_CLIENT='binary',NAMES 'utf8'");
		return self::$mysqli[$this->host];
	}
    /**
     * 检查数据库连接,是否有效，无效则重新建立
     */
    protected function checkConnection()
    {
        if (!@self::$mysqli[$this->host]->ping())
        {
            $this->close();
            return $this->connect();
        }
        return true;
    }
	/**
	 * 重连
	 * @date 2016-8-19
	 * @param unknown_type $call
	 * @param unknown_type $params
	 * @return
	 */
    protected function tryReconnect($params)
    {
    	$this->connect();
        $result = false;
        for ($i = 0; $i < 2; $i++)
        {
            $result = self::$mysqli[$this->host]->query($params);
            if ($result == false)
            {
//                if (self::$mysqli[$this->host]->errno == 2013 or self::$mysqli[$this->host]->errno == 2006)
//                {
					$this->errorlog(mysqli_error(self::$mysqli[$this->host]),$params);
                	usleep(100);
                    $r = $this->checkConnection();
                    if ($r !== false)
                    {
                        continue;
                    }
//                }
//                else
//                {
//                    $this->errorlog(mysqli_error(self::$mysqli[$this->host]));
//                    return false;
//                }
            }
            break;
        }
        return $result;
    }
	/**
	 * 执行sql
	 * @param unknown_type $query
	 */
	public function query( $query){
		$result = $this->tryReconnect($query);
        if (!$result)
        {
            return false;
        }
        if (is_bool($result))
        {
            return $result;
        }
        return $result;
	}
	public function numRows($result=null){ //Return number of rows in selected table
		if( is_object( $result)){
			return $result->num_rows;
		}
		return 0;
	}
	//查询一条记录
	public function getOne($query, $mode = MYSQL_ASSOC){
		$result = $this->query($query);
		if ( ! is_object( $result)) {
			return array();
		}
		$re = $result->fetch_array( $mode);
		if ( ! is_array($re)) {
			$re = array();
		}
		return $re;
	}
	//查询多条记录
	public function getAll($query, $mode = MYSQL_ASSOC){
		$result = $this->query($query);
		if ( ! is_object( $result)) {
			return array();
		}
		$dataList = array();
		while ($row = $result->fetch_array( $mode)) {
			$dataList[] = $row;
		}
		return $dataList;
	}
	/**
	 * 缓存多行数据
	 */
	public function getCacheAll($sql, $expire, $mode=MYSQL_BOTH, $key=false){
		$key = $key===false ? md5($sql) : $key;
		if( ($temp = ocache::cache()->get($key)) === false){
			$temp = $this->getAll($sql, $mode);
			ocache::cache()->set($key, $temp, $expire);
		}
		return $temp;
	}

	/**
	 * 缓存一行数据
	 */
	public function getCacheOne($sql, $expire, $mode=MYSQL_BOTH, $key=false){
		$key = $key===false ? md5($sql) : $key;
		if( ($temp = ocache::cache()->get($key)) === false){
			$temp = $this->getOne($sql, $mode);
			ocache::cache()->set($key, $temp, $expire);
		}
		return $temp;
	}
	public function fetchArray($result=null,$mode=MYSQL_BOTH){
		if ( ! is_object($result)) {
			return array();
		}
		$row = $result->fetch_array( $mode);
		return is_array($row) ? $row : array();
	}
	public function fetchAssoc($result=null){
		if ( ! is_object($result)) {
			return array();
		}
		$row = $result->fetch_assoc();
		return is_array($row) ? $row : array();
	}
	//获取最新插入的记录ID
	public function insertID(){
	    return self::$mysqli[$this->host]->insert_id;
	}
	/**
	 * 
	 * sql执行的影响行数
	 */
	public function affectedRows(){
		return self::$mysqli[$this->host]->affected_rows;
	}
	/**
	 * 
	 * 关闭数据库
	 */
	public function close(){
		self::$mysqli[$this->host]->close();
		unset(self::$mysqli[$this->host]);
	}
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 */
	public function escape( $string){
		if ( Lib_Functions::factory()->isPhpVersion()) {
			return addslashes( trim($string));
		}
		return mysql_escape_string( trim($string));
	}

	public function unescape( $string){
		return stripslashes( $string);
	}
	/**
	 * 事务处理章节
	 */
	public function Start(){
		$this->connect();
		self::$mysqli[$this->host]->autocommit( false);
	}
	/**
	 * 
	 *	提交事务
	 */
	public function Commit(){
		self::$mysqli[$this->host]->commit();
		self::$mysqli[$this->host]->autocommit( true);//恢复自动提交
	}
	public function CommitId(){
		$aId = $this->getOne('SELECT LAST_INSERT_ID()', MYSQL_NUM);
		return (int)$aId[0];
	}
	public function Rollback(){
		self::$mysqli[$this->host]->rollback();
	}
	/**
	 * mysqli 预处理章节
	 */
	/**
	 * 该方法准备要执行的预处理语句
	 * @return mysqli_stmt
	 */
	public function stmtPrepare( $query){
		if ( ! $query) {
			$this->errorlog('no query');
			return false;
		}
		$this->connect();
		self::$stmt = self::$mysqli[$this->host]->prepare( $query);
		return self::$stmt;
	}

	/**
	 * 记录错误日志
	 * @param string $msg
	 * @param string $query
	 * @return bool
	 */
	private function errorlog( $msg='', $query = ''){
		$date = date( 'Ymd');
		$file = PATH_DAT . 'mysql/' . $date . '.php';
		$error = '';
		if ( ! file_exists( $file)) {
			$error = "<?php\nexit();\n";
		}
		if ( ! is_dir( PATH_DAT . 'mysql')) {
			mkdir( PATH_DAT . 'mysql',  0777);
		}
		$query = oo::functions()->escape( $query);
		$error .= date('Y-m-d H:i:s') . "-- ". mysqli_errno( self::$mysqli[$this->host]) . "-- msg:". $msg . ";" . " query:". $query;
		@file_put_contents( $file, $error . " \n ", FILE_APPEND | LOCK_EX);
		return false;
	}

}
