<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'app/Lib/page.php';
class deals_cateModule extends BaseModule
{
	//产品众筹 首页
	public function index()
	{	
	 
 		$info=array();
		$type=0;
		$info=get_deal_cate_list($type);
		
 		$GLOBALS['tmpl']->assign("image_list",$info['image_list']);
 		$GLOBALS['tmpl']->assign("hot_list",$info['hot_list']);
 		$GLOBALS['tmpl']->assign("recommend_list",$info['recommend_list']);
 		$GLOBALS['tmpl']->assign("classic_list",$info['classic_list']);
 		$GLOBALS['tmpl']->assign("preheat_list",$info['preheat_list']);
 		$GLOBALS['tmpl']->assign("log_list",$info['log_list']);
 		
 		$GLOBALS['tmpl']->assign("p_type",$type);
		$GLOBALS['tmpl']->display("deals_cate_index.html");
	}
	//股权众筹 首页
	public function equity(){
		$info=array();
		$type=1;
		$info=get_deal_cate_list($type);
		
 		$GLOBALS['tmpl']->assign("image_list",$info['image_list']);
 		$GLOBALS['tmpl']->assign("hot_list",$info['hot_list']);
 		$GLOBALS['tmpl']->assign("recommend_list",$info['recommend_list']);
 		$GLOBALS['tmpl']->assign("classic_list",$info['classic_list']);
 		$GLOBALS['tmpl']->assign("preheat_list",$info['preheat_list']);
 		$GLOBALS['tmpl']->assign("log_list",$info['log_list']);
 		
 		$GLOBALS['tmpl']->assign("p_type",$type);
		$GLOBALS['tmpl']->display("deals_cate_equity.html");
	}
	 
}
?>