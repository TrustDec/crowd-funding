<?php
//底部文章
class deal_nav_cate_auto_cache extends auto_cache{
	public function load($param)
	{
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$deal_cate_array = $GLOBALS['cache']->get($key);
		if($deal_cate_array === false)
		{
			$deal_nav_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX.$param['name']." where is_effect=1");
			$deal_cate_array=array();
			foreach($deal_nav_cate as $k=>$v){
				$deal_cate_all[$v['id']]=$v;
				if($v['pid'] ==0)
				{
					$deal_cate_big[$v['id']]=$v;
				}else{
					$deal_cate_all[$v['pid']]['sub_list'][]=$v;
				}
			}
			
			$deal_cate_array['deal_cate_big']=$deal_cate_big;
			$deal_cate_array['deal_cate_all']=$deal_cate_all;
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$deal_cate_array);
			$deal_cate=$deal_cate_array;
		}
		return $deal_cate_array;
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