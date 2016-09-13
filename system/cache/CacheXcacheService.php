<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class CacheXcacheService extends CacheService
{
	private $dir;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct()
    {
        if ( !function_exists('xcache_info') ) {
           return false;
        }
        $this->type = strtoupper(substr(__CLASS__,6));
		$this->expire = 36000;
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
    	if(IS_DEBUG)return false;
    	$var_name = md5($this->dir.$name);    	
    	global $$var_name;
    	if($$var_name)
    	{
    		return $$var_name;
    	}
    	if(function_exists("xcache_isset"))
    	{
	   		if (xcache_isset($var_name)) {
	   			if(function_exists("xcache_get"))
	   			$data = xcache_get($var_name);
	    		$$var_name = $data;    	
				return $data;
			}
    	}
        return false;
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
		if($expire=='-1') $expire = 3600*24;
		
		$key = md5($this->dir.$name);
		$this->log_names($key);
		if(function_exists("xcache_set"))
		return xcache_set($key, $value, $expire);
		else
		return false;			
		
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
    	if(function_exists("xcache_unset"))
    	{
    		$key = md5($this->dir.$name);
			return xcache_unset($key);
    	}
    }
    
    
    public function clear()
    {
		$names = $this->get_names();
		foreach($names as $name)
		{
			xcache_unset($name);
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