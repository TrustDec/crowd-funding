<?php
//底部文章
class article_cates_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$article_cates = $GLOBALS['cache']->get($key);
		$article_cates_array=array();
		if($article_cates === false)
		{
			$article_cates = $GLOBALS['db']->getAll("select ac.*,fa.num from ".DB_PREFIX."article_cate as ac left join (SELECT count(*) as num,cate_id from ".DB_PREFIX."article where is_effect=1 and is_delete=0 GROUP BY cate_id) as fa on fa.cate_id=ac.id  where ac.is_effect=1 and  ac.is_delete=0  order by ac.sort asc");
			foreach($article_cates as $k=>$v){
				$article_cates_array[$v['id']]=$v;
				$article_cates_array[$v['id']]['num']=intval($v['num']);
				$article_cates_array[$v['id']]['url']=url('article',array('id'=>$v['id']));
				
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