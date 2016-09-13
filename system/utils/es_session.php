<?php 
define("WRITE_SESSION",0);
define("DELETE_SESSION",1);
class es_session
{		
	
	public $write_list; //写入清单
	public function __construct()
	{
		es_session_start(self::$sess_id);
		self::$sess_id = session_id();
		self::close();
		$this->write_list = array();
	}
	
	public function __destruct()
	{
		//析构中写入
		$this->direct_write();
	}
	
	public function direct_write()
	{
		//写入
		es_session_start(self::$sess_id);
		foreach($this->write_list as $row)
		{
			if($row['p']==WRITE_SESSION)
			{
				$_SESSION[$row['k']] = $row['v'];
			}
			if($row['p']==DELETE_SESSION)
			{
				unset($_SESSION[$row['k']]);
			}
		}
		$this->write_list = array();
		self::close();
	}
	
	static $es_session_instance;	
	static $sess_id = "";
	static $sess_started;
	static function id()
	{
		self::start();
		return self::$sess_id;;
	}
	static function set_sessid($sess_id)
	{
		self::$sess_id = $sess_id;
	}
	static function start()
	{
		if(!self::$es_session_instance)
		{
			self::$es_session_instance = new es_session();					
		}		
	}
	
	static function restart()
	{
		self::$es_session_instance = new es_session();
	}

	// 判断session是否存在
	static function is_set($name) {
		self::start();
		$tag = isset($_SESSION[app_conf("AUTH_KEY").$name]);
		return $tag;
	}

	// 获取某个session值
	static function get($name) {
		self::start();
		$value   = $_SESSION[app_conf("AUTH_KEY").$name];
		return $value;
	}

	// 设置某个session值
	static function set($name,$value) {
		self::start();
		$_SESSION[app_conf("AUTH_KEY").$name]  =   $value;
		self::$es_session_instance->write_list[] = array("p"=>WRITE_SESSION,"v"=>$value,"k"=>app_conf("AUTH_KEY").$name);
	}

	// 删除某个session值
	static function delete($name) {
		self::start();
		unset($_SESSION[app_conf("AUTH_KEY").$name]);
		self::$es_session_instance->write_list[] = array("p"=>DELETE_SESSION,"v"=>null,"k"=>app_conf("AUTH_KEY").$name);
	}

	// 清空session
	static function clear() {
		@session_destroy();
	}

	//关闭session的读写
	static function close()
	{
		@session_write_close();
	}
	
	static function write()
	{
		self::$es_session_instance->direct_write();
	}

}
//end session
?>