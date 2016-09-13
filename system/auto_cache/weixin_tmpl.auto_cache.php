<?php
//底部文章
class weixin_tmpl_auto_cache extends auto_cache{
	public function load($param)
	{
		//$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$data = $GLOBALS['cache']->get($key);
		if($data === false)
		{
			$weixin = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_tmpl where account_id = ".$param['account_id']);
			 
			foreach($weixin as $k=>$v){
 				$data[$v['template_id_short']]=$v;
			}
		 	 
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$data);
		}
		return $data;
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