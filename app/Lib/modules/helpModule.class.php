<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class helpModule extends BaseModule
{
	public function index()
	{	
               
		$act = strim($_REQUEST['act']);
		$GLOBALS['tmpl']->caching = true;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME.$act);		
		if (!$GLOBALS['tmpl']->is_cached('help_index.html', $cache_id))
		{
			$help_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."help where type = '".$act."' or id = ".intval($act));
			if($help_item)
			{			
				$GLOBALS['tmpl']->assign("help_item",$help_item);
				$GLOBALS['tmpl']->assign("page_title",$help_item['title']);
			}
			else
			{
				app_redirect(url("index"));
			}
		}
		$GLOBALS['tmpl']->display("help_index.html",$cache_id);
	}
	public function show()
	{	
               
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
        
		$act = strim($_REQUEST['act']);

		$help_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."help where type = '".$act."' or id = ".intval($act));		
		$GLOBALS['tmpl']->assign("help_item",$help_item);
		$GLOBALS['tmpl']->assign("page_title",$help_item['title']);
		$GLOBALS['tmpl']->display("help_show.html");
	}
	
	
}
?>