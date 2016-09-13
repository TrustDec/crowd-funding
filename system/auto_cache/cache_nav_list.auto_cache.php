<?php
//导航
class cache_nav_list_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$nav_list = $GLOBALS['cache']->get($key);
		 
		if($nav_list === false)
		{
			$nav_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."nav where is_effect = 1 order by sort asc");
			$nav_list_item=array();
			foreach ($nav_list as $k=>$v)
			{
				if(app_conf("INVEST_STATUS")==0)
				{
					//开启“产品”和“股权”众筹
					$nav_list_item[$k]=$v;
				}
				elseif (app_conf("INVEST_STATUS")==1)
				{
					//只开启“产品”众筹
					if($v['u_param']!="type=1"){
						$nav_list_item[$k]=$v;
					}
				}
				elseif (app_conf("INVEST_STATUS")==2)
				{
					//只开启“股权”众筹
					if($v['u_module']!="deals" || $v['u_param']=="type=1"){
						$nav_list_item[$k]=$v;
					}	
				}
			}
			$nav_list = format_nav_list($nav_list_item);
			unset($nav_list_item);
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$nav_list);
		}
		return $nav_list;
	}
	public function rm($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->clear();
	}
}
?>