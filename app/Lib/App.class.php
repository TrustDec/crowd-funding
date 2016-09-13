<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/app_init.php';


class App{		
	private $module_obj;
	//网站项目构造
	public function __construct(){		
		$module_name = $GLOBALS['module']."Module";
		$this->module_obj = new $module_name;
		$this->module_obj->$GLOBALS['action']();
		
	}
	
	public function __destruct()
	{
		unset($this);
	}
}
?>