<?php
//底部文章
class message_cate_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$message_cate = $GLOBALS['cache']->get($key);
		if($message_cate === false)
		{
			
			$message_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."message_cate order by id asc");
 			
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$message_cate);
		}
		return $message_cate;
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