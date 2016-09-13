<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// session 管理控制器
// +----------------------------------------------------------------------

class SessionHandler
{
	private $savePath;
	private $mem;  //Memcache使用
	private $db;	//数据库使用
	private $table; //数据库使用

	function open($savePath, $sessionName)
	{		
		$this->savePath = APP_ROOT_PATH."public/session";
		if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
		{
			$this->mem = require_once APP_ROOT_PATH."system/cache/MemcacheSASL/MemcacheSASL.php";
			$this->mem = new MemcacheSASL;
			$this->mem->addServer($GLOBALS['distribution_cfg']['SESSION_CLIENT'], $GLOBALS['distribution_cfg']['SESSION_PORT']);
			$this->mem->setSaslAuthData($GLOBALS['distribution_cfg']['SESSION_USERNAME'],$GLOBALS['distribution_cfg']['SESSION_PASSWORD']);	    
		}
		elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
		{
 			$pconnect = false;
			$session_client = $GLOBALS['distribution_cfg']['SESSION_CLIENT']==""?app_conf('DB_HOST'):$GLOBALS['distribution_cfg']['SESSION_CLIENT'];
			$session_port = $GLOBALS['distribution_cfg']['SESSION_PORT']==""?app_conf('DB_PORT'):$GLOBALS['distribution_cfg']['SESSION_PORT'];
			$session_username = $GLOBALS['distribution_cfg']['SESSION_USERNAME']==""?app_conf('DB_USER'):$GLOBALS['distribution_cfg']['SESSION_USERNAME'];
			$session_password = $GLOBALS['distribution_cfg']['SESSION_PASSWORD']==""?app_conf('DB_PWD'):$GLOBALS['distribution_cfg']['SESSION_PASSWORD'];
			$session_db = $GLOBALS['distribution_cfg']['SESSION_DB']==""?app_conf('DB_NAME'):$GLOBALS['distribution_cfg']['SESSION_DB'];
 			$this->db = new mysql_db($session_client.":".$session_port, $session_username,$session_password,$session_db,'utf8',$pconnect);
 			$this->table = $GLOBALS['distribution_cfg']['SESSION_TABLE']==""?DB_PREFIX."session":$GLOBALS['distribution_cfg']['SESSION_TABLE'];
		}
		else
		{
			if (!is_dir($this->savePath)) {
				@mkdir($this->savePath, 0777);
			}		
		}
		return true;
	}

	function close()
	{
		return true;
	}

	function read($id)
	{
		$sess_id = "sess_".$id;
		 
		if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
		{
   			return $this->mem->get("$this->savePath/$sess_id");
		}
		elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
		{			
			$session_data = $this->db->getRow("select session_data,session_time from ".$this->table." where session_id = '".$sess_id."'",true);
 			if($session_data['session_time']<NOW_TIME)
			{
 				return false;
			}
			else
			{
 				return $session_data['session_data'];
			}
		}
		else
		{
			$file = "$this->savePath/$sess_id";
			if (filemtime($file) + $GLOBALS['distribution_cfg']['SESSION_TIME'] < time() && file_exists($file)) {
				@unlink($file);
			}
			$data = (string)@file_get_contents($file);
			return $data;
		}
		

	}

	function write($id, $data)
	{
		
		$sess_id = "sess_".$id;
		if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
		{
			return $this->mem->set("$this->savePath/$sess_id",$data,$GLOBALS['distribution_cfg']['SESSION_TIME']);
		}
		elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
		{			
			$session_data = $this->db->getRow("select session_data,session_time from ".$this->table." where session_id = '".$sess_id."'",true);
			if($session_data)
			{
				$session_data['session_data'] = $data;
				$session_data['session_time'] = NOW_TIME+$GLOBALS['distribution_cfg']['SESSION_TIME'];
				$this->db->autoExecute($this->table, $session_data,"UPDATE","session_id = '".$sess_id."'");
			}
			else
			{
				$session_data['session_id'] = $sess_id;
				$session_data['session_data'] = $data;
				$session_data['session_time'] = NOW_TIME+$GLOBALS['distribution_cfg']['SESSION_TIME'];
				$this->db->autoExecute($this->table, $session_data);
			}
			return true;
		}
		else
		{			
			
			return file_put_contents("$this->savePath/$sess_id", $data) === false ? false : true;
		}
		

	}

	function destroy($id)
	{
		
		$sess_id = "sess_".$id;
		if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
		{
			 $this->mem->delete($sess_id);
		}
		elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
		{
			$this->db->query("delete from ".$this->table." where session_id = '".$sess_id."'");
		}
		else
		{
			$file = "$this->savePath/$sess_id";
			if (file_exists($file)) {
				@unlink($file);
			}	
		}
		return true;
	}

	function gc($maxlifetime)
	{
		if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
		{
			 
		}
		elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
		{
			$this->db->query("delete from ".$this->table." where session_time < ".NOW_TIME);
		}
		else
		{
			foreach (glob("$this->savePath/sess_*") as $file) {
				if (filemtime($file) + $GLOBALS['distribution_cfg']['SESSION_TIME'] < time() && file_exists($file)) {
					unlink($file);
				}
			}	
		}
		return true;
	}
}
?>