<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class helpModule 
{
	public function index()
	{	
		$act = 1;
		$GLOBALS['tmpl']->caching = false;
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
	public function	about()
	{
		$act = 'about';
		$GLOBALS['tmpl']->caching = false;
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
	public function	intro()
	{
		$act = 'intro';
		$GLOBALS['tmpl']->caching = false;
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
	public function	privacy()
	{
		$act = 'privacy';
		$GLOBALS['tmpl']->caching = false;
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
	public function	write_guide()
	{
		$act = 'write_guide';
		$GLOBALS['tmpl']->caching = false;
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
}
?>