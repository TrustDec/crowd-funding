<?php
//底部文章
class article_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$article = $GLOBALS['cache']->get($key);
		if($article === false)
		{
			$article = $GLOBALS['db']->getAll("select id,cate_id,title from ".DB_PREFIX."article  where is_effect=1 and  is_delete=0  order by  sort asc");
			$article_array=array();
			foreach($article as $k=>$v){
 				$article_array[$v['id']]=$v;
			}
			$article=$article_array;
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$article);
		}
		return $article;
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