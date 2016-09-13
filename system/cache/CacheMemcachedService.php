<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class CacheMemcachedService extends CacheService
{

	private $mem;
	private $dir; //模拟的目录，即前缀
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct()
    {

    	if(!class_exists("Memcache"))
    	return false;
		$this->mem = new Memcache;
		$host = $GLOBALS['distribution_cfg']['CACHE_CLIENT'];
		$port = $GLOBALS['distribution_cfg']['CACHE_PORT']; //默认端口为11211
		$this->mem->connect($host, $port);   //此处为memcache的连接主机与端口 
		$this->dir = "";
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
    	if(!$this->mem)return false;
    	if(IS_DEBUG)return false;
    	$var_name = md5($this->dir.$name);    	
    	global $$var_name;
    	if($$var_name)
    	{
    		return $$var_name;
    	}    	
    	$data = $this->mem->get($var_name);
   		if($data)
    	{
    		$$var_name = $data;
    	}
    	else
    	{
    		$data = false;
    	}
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
    	if(!$this->mem)return false;
    	if($expire=='-1') $expire = 3600*24;
		$key = md5($this->dir.$name);
		$this->log_names($key);
		return $this->mem->set($key,$value,0,$expire);

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
    	if(!$this->mem)return false;
    	$key = md5($this->dir.$name);
		return $this->mem->delete($key);
    }
    
    
    public function clear()
    {
		$names = $this->get_names();
		foreach($names as $name)
		{
			$this->mem->delete($name);
		}
		$this->del_name_logs();
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