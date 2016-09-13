<?php
// +----------------------------------------------------------------------
// | Fanwe 文章列表页
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class article_cate
{
	public function index(){
		
		$root = array();
		$root['response_code'] = 1;
		$artilce_cate = load_auto_cache("article_cates"); 
		foreach($artilce_cate as $k=>$v)
		{  			
 			$artilce_cate[$k]['cate_id']=$v['id'];
 			$artilce_cate[$k]['titles']=$v['title'];
		}
		$GLOBALS['tmpl']->assign("artilce_cate",$artilce_cate);    
		$temp_artilce_list = $GLOBALS['db']->getAllCached("SELECT a.*,c.type_id from ".DB_PREFIX."article a LEFT JOIN ".DB_PREFIX."article_cate c on a.cate_id=c.id order by update_time asc"); 
		$hot_article=array();
		$week_article=array();
		$artilce_item=array();
		foreach($temp_artilce_list as $k=>$v)
		{  
			//最新智能头条 type_id==0帮助文章，is_hot==1热门，is_week==1本周必读
			if($v['type_id']==0&&$v['is_delete']==0&&$v['is_effect']==1){
				$artilce_item[$k]['cate_title']=$v['title'];
				$artilce_item[$k]['seo_keyword']=$v['seo_keyword'];
				$artilce_item[$k]['title']=$v['title'];
				$artilce_item[$k]['content']=$v['content'];
				$artilce_item[$k]['update_time']=$v['update_time'];
				if($v['rel_url']=="")
 					$artilce_item[$k]['url']=url('article',array('id'=>$v['id']));
				else
					$artilce_item[$k]['url']=$v['rel_url'];
					
			}
			//热门话题
			if($v['is_hot']==1&&$v['is_effect']=1&&$v['is_delete']==0&&$v['type_id']==0){
				$hot_article[$k]['title']=$v['title'];
				if($v['rel_url']=="")
					$hot_article[$k]['url']=url('article',array('id'=>$v['id']));
				else
					$hot_article[$k]['url']=$v['rel_url'];
			}
			//每周必读
			if($v['is_week']==1&&$v['is_effect']==1&&$v['is_delete']==0&&$v['type_id']==0){
				$week_article[$k]['title']=$v['title'];
				if($v['rel_url']=="")
					$week_article[$k]['url']=url('article',array('id'=>$v['id']));
				else
					$week_article[$k]['url']=$v['rel_url'];
			}
 		}
 		unset($temp_artilce_list);
		$root['artilce_list'] = $artilce_item;
		$root['hot_1'] = $hot_article;
		$root['hot_2'] = $week_article;
		output($root);		
	}
}
?>
