<?php
//底部文章
class deal_cate_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$deal_cate = $GLOBALS['cache']->get($key);
		$deal_cate_array=array();
		if($deal_cate === false)
		{
			$deal_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where is_delete=0");
			foreach($deal_cate as $k=>$v){
 				$deal_cate_array[$v['id']]=$v;
			}
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$deal_cate_array);
			$deal_cate=$deal_cate_array;
		}
		return $deal_cate;
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