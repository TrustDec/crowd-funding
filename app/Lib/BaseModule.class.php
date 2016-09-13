<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class BaseModule{
	public function __construct()
	{
		$GLOBALS['tmpl']->assign("MODULE_NAME",MODULE_NAME);
		$GLOBALS['tmpl']->assign("ACTION_NAME",ACTION_NAME);
		
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/page_static_cache/");
		$GLOBALS['dynamic_cache'] = $GLOBALS['cache']->get("APP_DYNAMIC_CACHE_".MODULE_NAME."_".ACTION_NAME);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
		$GLOBALS['dynamic_avatar_cache'] = $GLOBALS['cache']->get("AVATAR_DYNAMIC_CACHE"); //头像的动态缓存
		
		
		
		//设置返回前面的页面
		if((MODULE_NAME=="index")||
		(MODULE_NAME == "project"&&ACTION_NAME=="add")||
		(MODULE_NAME == "project"&&ACTION_NAME=="edit")||
		(MODULE_NAME == "project"&&ACTION_NAME=="add_item")||
		(MODULE_NAME == "project"&&ACTION_NAME=="edit_item")||
		(MODULE_NAME == "deals"&&ACTION_NAME=="index")||
		(MODULE_NAME == "deal"&&ACTION_NAME=="index")||
		(MODULE_NAME == "deal"&&ACTION_NAME=="show")||
		(MODULE_NAME == "finance"&&ACTION_NAME=="company_show")||
		(MODULE_NAME == "finance"&&ACTION_NAME=="company_finance")||
		(MODULE_NAME == "finance"&&ACTION_NAME=="company_update")||
		(MODULE_NAME == "finance"&&ACTION_NAME=="company_fans")||
		(MODULE_NAME == "deal"&&ACTION_NAME=="update")||
		(MODULE_NAME == "deal"&&ACTION_NAME=="updatedetail")||
		(MODULE_NAME == "deal"&&ACTION_NAME=="comment")||
		(MODULE_NAME == "cart"&&ACTION_NAME=="index")||
		(MODULE_NAME == "cart"&&ACTION_NAME=="pay")||
		(MODULE_NAME == "faq")||(MODULE_NAME == "help")||
		(MODULE_NAME == "account"&&ACTION_NAME=="index")||
		(MODULE_NAME == "account"&&ACTION_NAME=="incharge")||
		(MODULE_NAME == "account"&&ACTION_NAME=="pay")||
		(MODULE_NAME == "account"&&ACTION_NAME=="project")||
		(MODULE_NAME == "account"&&ACTION_NAME=="credit")||
		(MODULE_NAME == "account"&&ACTION_NAME=="view_order")||
		(MODULE_NAME == "account"&&ACTION_NAME=="focus")||
		(MODULE_NAME == "account"&&ACTION_NAME=="support")||
		(MODULE_NAME == "account"&&ACTION_NAME=="paid")||
		(MODULE_NAME == "account"&&ACTION_NAME=="refund")||
		(MODULE_NAME == "news"&&ACTION_NAME=="index")||
		(MODULE_NAME == "news"&&ACTION_NAME=="fav")||
		(MODULE_NAME == "comment"&&ACTION_NAME=="index")||
		(MODULE_NAME == "comment"&&ACTION_NAME=="send")||
		(MODULE_NAME == "message"&&ACTION_NAME=="index")||
		(MODULE_NAME == "message"&&ACTION_NAME=="history")||
		(MODULE_NAME == "notify"&&ACTION_NAME=="index")||
		(MODULE_NAME=="settings"&&ACTION_NAME=="index")||
		(MODULE_NAME == "settings"&&ACTION_NAME=="password")||
		(MODULE_NAME == "settings"&&ACTION_NAME=="bind")||
		(MODULE_NAME == "settings"&&ACTION_NAME=="consignee"))
		{	
			set_gopreview();
		}		
	}

	public function index()
	{
		showErr("invalid access");
	}
	public function __destruct()
	{
		if(isset($GLOBALS['cache']))
		{
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/page_static_cache/");
			$GLOBALS['cache']->set("APP_DYNAMIC_CACHE_".MODULE_NAME."_".ACTION_NAME,$GLOBALS['dynamic_cache']);
			if(count($GLOBALS['dynamic_avatar_cache'])<=500)
			{
				$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
				$GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
			}
		}
		unset($this);
	}
}
?>