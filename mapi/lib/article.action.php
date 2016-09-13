<?php
// +----------------------------------------------------------------------
// | Fanwe 文章详细页
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class article
{
	public function index(){
		
		$root = array();
		$root['response_code'] = 1;
		$id = intval( $GLOBALS ['request']['id']);
		$article = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where id=$id");
		//文章详细页面最新更新(控制最新的10条)
		$temp_article_list=$GLOBALS['db']->getAllCached("SELECT a.*,c.type_id from ".DB_PREFIX."article a LEFT JOIN ".DB_PREFIX."article_cate c on a.cate_id=c.id order by update_time desc limit 10");
		$article_list=array();
		foreach ($temp_article_list as $k=>$v){
			//最新更新
			if($v['type_id']==0&&$v['is_delete']==0&&$v['is_effect']==1){
				$article_list[$k]['cate_title']=$v['title'];
				if($v['rel_url']=="")
					$article_list[$k]['url']=url('article',array('id'=>$v['id']));
				else
					$article_list[$k]['url']=$v['rel_url'];
			}
		}
		unset($temp_article_list);
		$root['article'] = $article;
		$root['article_list'] = $article_list;
		output($root);		
	}
}
?>
