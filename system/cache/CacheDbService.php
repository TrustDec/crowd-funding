<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class CacheDbService extends CacheService
{

	private $db;
	private $dir; //模拟的目录，即前缀
	private $table; //缓存表名
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct()
    {
		$pconnect = false;
		$cache_client = $GLOBALS['distribution_cfg']['CACHE_CLIENT']==""?app_conf('DB_HOST'):$GLOBALS['distribution_cfg']['CACHE_CLIENT'];
		$cache_port = $GLOBALS['distribution_cfg']['CACHE_PORT']==""?app_conf('DB_PORT'):$GLOBALS['distribution_cfg']['CACHE_PORT'];
		$cache_username = $GLOBALS['distribution_cfg']['CACHE_USERNAME']==""?app_conf('DB_USER'):$GLOBALS['distribution_cfg']['CACHE_USERNAME'];
		$cache_password = $GLOBALS['distribution_cfg']['CACHE_PASSWORD']==""?app_conf('DB_PWD'):$GLOBALS['distribution_cfg']['CACHE_PASSWORD'];
		$cache_db = $GLOBALS['distribution_cfg']['CACHE_DB']==""?app_conf('DB_NAME'):$GLOBALS['distribution_cfg']['CACHE_DB'];
		$this->db = new mysql_db($cache_client.":".$cache_port, $cache_username,$cache_password,$cache_db,'utf8',$pconnect);
		$this->table = $GLOBALS['distribution_cfg']['CACHE_TABLE']==""?DB_PREFIX."auto_cache":$GLOBALS['distribution_cfg']['CACHE_TABLE'];
		
		$this->db->query("delete from ".$this->table." where cache_time < ".NOW_TIME);
    }

    /**
     +----------------------------------------------------------
     * 读取缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function get($name)
    {
    	if(!$this->db)return false;
    	if(IS_DEBUG)return false;
    	$var_name = md5($this->dir.$name);    	
    	global $$var_name;
    	if($$var_name)
    	{
    		return $$var_name;
    	}    	
    	$key = $var_name;
    	$tmp_data =  $this->db->getRow("select cache_data,cache_time from ".$this->table." where cache_key = '".$key."'",true);
    	if($tmp_data['cache_time']>NOW_TIME)
    	{
    		$data = unserialize($tmp_data['cache_data']);
    	}
    	else 
    	{
    		$this->db->query("delete from ".$this->table." where cache_key = '".$key."'");
    		$data = false;
    	}
    	$$var_name = $data;
        return $data;
    }


    /**
     +----------------------------------------------------------
     * 写入缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
	public function set($name, $value,$expire ="-1")
    {
    	if(IS_DEBUG)return false;
    	if(!$this->db)return false;
    	if($expire=='-1') $expire = 3600*24;
    	$cache_data['cache_data'] = serialize($value);
    	$cache_data['cache_key'] = md5($this->dir.$name);
    	$cache_data['cache_type'] = $this->dir;
    	$cache_data['cache_time'] = NOW_TIME+$expire;
    	
    	$this->db->query("delete from ".$this->table." where cache_key = '".$cache_data['cache_key']."'");
    	$this->db->autoExecute($this->table, $cache_data);

    }

    /**
     +----------------------------------------------------------
     * 删除缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function rm($name)
    {
    	if(!$this->db)return false;
    	$key = md5($this->dir.$name);
    	$this->db->query("delete from ".$this->table." where cache_key = '".$key."'");
    }
    
    
    public function clear()
    {
    	if($this->dir)
    	$this->db->query("delete from ".$this->table." where cache_type = '".$this->dir."'");
    	else
		$this->db->query("truncate table ".$this->table);
    }
    public function set_dir($dir='')
    {
    	if($dir!='')
    	{
    		$this->dir = md5($dir);
    	}
    }

}//类定义结束

?>