<?php
/**
 * mongo基本操作类
 * include PATH_LIB . 'class.mumongo.php';

	$mongoobj = new mumongo('192.168.0.6:27017');
	$mongoobj->selectDataBase('testmongo', 'user');

	var_dump($mongoobj->insert(array('firstname8888' => 'Bob', 'address' => '888888')));

	$mongoobj->update(array('firstname8888' => 'Bob'), array('address'=>'9999999999'));

	var_dump($mongoobj->getOne(array('firstname8888' => 'Bob')));

	var_dump($mongoobj->getAll(array('uid' => 99999),0,0,true, true, array('level' => 1)));
	var_dump($mongoobj->getOne(array('uid' => 99999)));
 */
class mumongo {
	private $services; //mongo服务器地址 格式为 ip:端口
	private $options; //数组选项 connect If the constructor should connect before returning. Default is TRUE. 
	//		  persist
	//        timeout For how long the driver should try to connect to the database (in milliseconds). 
	//	      replicaSet
	/**
	 * 
	 * @var Mongo
	 */
	private $mongo; //mongo对象
	private $connected = false; //是否连接过
	private $database; //数据库名
	private $table; //数据库表名
	public function __construct($services, $options = array()){
		if (!class_exists( 'Mongo')) {
			die( 'This Lib Requires The Mongo Extention!');
		}
		$this->services = $services;
		$this->options = $options;
	}
	/**
	 * 
	 * 连接mongo服务器
	 * @return Mongo
	 */
	public function connect(){
		if (!$this->connected) {
			try {
				$this->mongo = new Mongo( $this->services);
				$this->connected = true;
			} catch ( Exception $e ) {
				$this->errorlog( 'Unable to connect mongodb - ' . $this->services);
			}
		}
		return $this->mongo;
	}
	/**
	 * 
	 * 选择数据库和数据表
	 * @param unknown_type $database 数据库名
	 * @param unknown_type $table	 数据表
	 */
	public function selectDataBase($database, $table){
		if ((!is_string( $database) || (!is_string( $table)))) {
			return false;
		}
		$mongo = $this->connect();
		if (!$this->connected) {
			return false;
		}
		$this->database = $mongo->$database; //选择一个已有数据库  如果没有则新建
		$this->table = $this->database->$table; //选择一个已有数据表  如果没有则新建 
	}
	/**
	 * 检查是否选择了数据库 - 数据表
	 * Enter description here ...
	 */
	public function checkDatabase(){
		if (!$this->connected) {
			return false;
		}
		if ((!is_object( $this->database) || (!is_object( $this->table)))) {
			die( 'mongo:no database');
		}
		return true;
	}
	/**
	 * 写入数据 
	 * @param unknown_type $data  数组
	 */
	public function insert($data){
		if (!is_array( $data)) {
			return false;
		}
		if (!$this->checkDatabase()) {
			return false;
		}
		return $this->table->insert( $data);
	}
	/**
	 * 更新数据
	 * @param unknown_type $where	更新条件 array("firstname" => "Bob")
	 * @param unknown_type $update  更新内容 array("address" => "1 Smith Lane")
	 */
	public function update($where, $update){
		if ((!is_array( $where)) || (!is_array( $update))) {
			return false;
		}
		if (!$this->checkDatabase()) {
			return false;
		}
		$newdata = array('$set' => $update );
		return $this->table->update( $where, $newdata);
	}
	/**
	 * 删除记录
	 * Enter description here ...
	 * @param unknown_type $where  array("firstname" => "Bob")
	 * @param unknown_type $justOne boolen
	 */
	public function delete($where, $justOne = true){
		if (!is_array( $where)) {
			return false;
		}
		if (!$this->checkDatabase()) {
			return false;
		}
		return $this->table->remove( $where, $justOne);
	}
	/**
	 * 设置索引
	 * @param unknown_type $aIdex    索引字段 array('uid' => 1) 1升序  -1降序
	 * @param unknown_type $options  索引类型  array('unique' => 1) 唯一索引
	 */
	public function setIndex($aIdex, $options = array()){
		if (!is_array( $aIdex)) {
			return false;
		}
		if (!$this->checkDatabase()) {
			return false;
		}
		return $this->table->ensureIndex( $aIdex, $options);
	}
	/**
	 * 
	 * 删除索引
	 * $index  如果是字符串则删除单个索引   如果是数组则删除多个索引
	 * array('uid' => 1)
	 * 'uid'
	 */
	public function delIndex($index){
		if (!$this->checkDatabase()) {
			return false;
		}
		return $this->table->deleteIndex( $index);
	}
	/**
	 * 批量获取符合条件的记录
	 * @param unknown_type $where	条件数组格式  如 array('uid' => 55)
	 * @param unknown_type $field	需要返回的字段  如 array('uid' => 55)
	 * @param unknown_type $offset	起始位置
	 * @param unknown_type $lengths	返回记录数
	 * @param unknown_type $isCount	是否返回总记录数
	 */
	public function getAll($where, $field, $offset = 0, $length = 0, $isCount = false,$isRand = false){
		if (!is_array( $where)) { //非数组
			return array();
		}
		if (!$this->checkDatabase()) {
			return array();
		}
		$oCursor = $this->table->find( $where, $field);
		$aRet = array('data' => array(), 'count' => -1 );
		$getedCount = false;
		$offset = ( int )$offset;
		$length = ( int ) $length;
		if ( $isRand === true) {
			$aRet['count'] = $count = $oCursor->count();
			$offset = $count > $length ? rand( 0, $count - $length) : 0; //起始位置
		}
		if ( ( $isCount === true ) && ( ! $getedCount)) {
			$aRet['count'] = $oCursor->count();
		}
		if ($length > 0) {
			$oCursor->skip( $offset)->limit( $length); //取部分记录
		}
		$data = array();
		while ( $oCursor->getNext() ) { //遍历数据
			$data[] = $oCursor->current();
		}
		$aRet['data'] = $data;
		return $aRet;
	}
	/**
	 * 
	 * 返回单行记录
	 * @param unknown_type $where	条件数组
	 * @param unknown_type $fields	字段数组
	 */
	public function getOne($where, $fields = array()){
		if (!is_array( $where)) {
			return array();
		}
		if (!$this->checkDatabase()) {
			return array();
		}
		return $this->table->findOne( $where, $fields);
	}
	/**
	 * 
	 * 获取所有记录行
	 * @param unknown_type $where
	 */
	public function getCount($where){
		if (!is_array( $where)) {
			return 0;
		}
		if (!$this->checkDatabase()) {
			return 0;
		}
		$oCursor = $this->table->find( $where);
		return $oCursor->count();
	}
	/**
	 * 错误日志
	 * @param unknown_type $msg
	 */
	private function errorlog($msg){
		$error = date( 'Y-m-d H:i:s') . ":\error:" . $msg . "\n\n";
		oo::logs()->logsUdp( 'debug', 'mongondb.txt', $error);
	}
}