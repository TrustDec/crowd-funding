<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class faqModule extends BaseModule
{
	public function index()
	{	
                
		$GLOBALS['tmpl']->caching = true;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('faq_index.html', $cache_id))
		{
			$faq_list = array();
			$faq_group_list = $GLOBALS['db']->getAll("select distinct(`group`) from ".DB_PREFIX."faq order by sort asc");
			foreach ($faq_group_list as $k=>$v)
			{
				$faq_list[$v['group']]=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."faq where `group`='".$v['group']."' order by sort asc");
			}
			$GLOBALS['tmpl']->assign("faq_list",$faq_list);
			$GLOBALS['tmpl']->assign("page_title","常见问题");
		}
		//获得文章列表
		$artilce_cate = load_auto_cache("article_cates");
		foreach($artilce_cate as $k=>$v)
		{
			$artilce_cate[$k]['cate_id']=$v['id'];
			$artilce_cate[$k]['titles']=$v['title'];
			if($id>0&&$v['id']==$id){
				$type_id=intval($v['type_id']);
				$cate_name=$v['title'];
			}
			if($id==$artilce_cate[$k]['cate_id'])
			{
				$artilce_cate[$k]['current']=1;
			}
		
		}
		$GLOBALS['tmpl']->assign("artilce_cate",$artilce_cate);
		//文章头部导航
		$nav_top=set_nav_top($GLOBALS['module'],$GLOBALS['action']);
		$GLOBALS['tmpl']->assign('nav_top',$nav_top);
		$GLOBALS['tmpl']->assign('deal_type','article_type');
		$GLOBALS['tmpl']->display("faq_index.html",$cache_id);
	}	
	
}
?>