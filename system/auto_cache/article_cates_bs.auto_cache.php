<?php
//底部文章
class article_cates_bs_auto_cache extends auto_cache{
	public function load($param,$is_real)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$article_cates = $GLOBALS['cache']->get($key);
 		if($article_cates === false||!$is_real)
		{
			$article_cates = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."article_cate  where is_effect=1 and  is_delete=0  order by  sort asc");
			$article_cates_array=array();
			foreach($article_cates as $k=>$v){
 				$article_cates_array[$v['seo_title']]=$v['id'];
			}
			$article_cates=$article_cates_array;
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$article_cates);
		}
		return $article_cates;
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