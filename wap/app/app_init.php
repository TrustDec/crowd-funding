<?php
require_once 'common.php';
filter_injection($_REQUEST);

if(!file_exists(APP_ROOT_PATH.'public/runtime/app/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/app/',0777);
}

//输出根路径
$GLOBALS['tmpl']->assign("APP_ROOT",APP_ROOT);


$IMG_APP_ROOT = APP_ROOT;
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_caches/',0777);
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/',0777);
$GLOBALS['tmpl']->cache_dir      = APP_ROOT_PATH . 'public/runtime/app/tpl_caches';
$GLOBALS['tmpl']->compile_dir    = APP_ROOT_PATH . 'public/runtime/app/tpl_compiled';
$GLOBALS['tmpl']->template_dir   = APP_ROOT_PATH . 'app/Tpl/' . app_conf("TEMPLATE");
//定义当前语言包
//定义模板路径
$tmpl_path = get_domain().APP_ROOT."/app/Tpl/";
$GLOBALS['tmpl']->assign("TMPL",$tmpl_path.app_conf("TEMPLATE"));
$GLOBALS['tmpl']->assign("TMPL_REAL",APP_ROOT_PATH."app/Tpl/".app_conf("TEMPLATE")); 

define("DEAL_PAGE_SIZE",60);
define("DEAL_STEP_SIZE",12);

define("DEALUPDATE_PAGE_SIZE",15);
define("DEALUPDATE_STEP_SIZE",5);

define("DEAL_COMMENT_PAGE_SIZE",40);

define("DEAL_SUPPORT_PAGE_SIZE",20);

define("ACCOUNT_PAGE_SIZE",10);


require APP_ROOT_PATH.'app/Lib/BaseModule.class.php';
define("CTL",'ctl');
define("ACT",'act');

if($GLOBALS['pay_req'][CTL])
	$_REQUEST[CTL] = $GLOBALS['pay_req'][CTL];
if($GLOBALS['pay_req'][ACT])
	$_REQUEST[ACT] = $GLOBALS['pay_req'][ACT];
	
$module = filter_ma_request(strtolower(!empty($_REQUEST[CTL])?$_REQUEST[CTL]:"index"));
$action = filter_ma_request(strtolower(!empty($_REQUEST[ACT])?$_REQUEST[ACT]:"index"));


if(!file_exists(APP_ROOT_PATH."app/Lib/modules/".$module."Module.class.php"))
$module = "index";

require_once APP_ROOT_PATH."app/Lib/modules/".$module."Module.class.php";				
if(!class_exists($module."Module"))
{
	$module = "index";
	require_once APP_ROOT_PATH."app/Lib/modules/".$module."Module.class.php";	
}
if(!method_exists($module."Module",$action))
$action = "index";

define("MODULE_NAME",$module);
define("ACTION_NAME",$action);

//载入会员登录信息
//会员自动登录及输出
$cookie_uname = es_cookie::get("email")?es_cookie::get("email"):'';
$cookie_upwd = es_cookie::get("user_pwd")?es_cookie::get("user_pwd"):'';
if($cookie_uname!=''&&$cookie_upwd!=''&&!es_session::get("user_info"))
{
	$cookie_uname = strim($cookie_uname);
	$cookie_upwd =  strim($cookie_upwd);
	require_once APP_ROOT_PATH."system/libs/user.php";
	auto_do_login_user($cookie_uname,$cookie_upwd);
}
$user_info = es_session::get('user_info');


if($module!="ajax")
{
	if($user_info)
	{
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id'])." and is_effect = 1");
		es_session::set('user_info',$user_info);
		//查询登入用户所对应的user_level
		$user_level=$GLOBALS['db']->getAll("select level from ".DB_PREFIX."user_level where id=".intval($GLOBALS['user_info']['user_level']));
		//给前台会员的level值
		$GLOBALS['tmpl']->assign("user_level",$user_level);
		$GLOBALS['tmpl']->assign("user_info",$user_info);
	}
	
	//输出导航菜单
	$nav_list = get_nav_list();
 	$nav_list= init_nav_list($nav_list);
	$GLOBALS['tmpl']->assign("nav_list",$nav_list);
	
	
	//输出SEO元素
	$GLOBALS['tmpl']->assign("site_name",app_conf("SITE_NAME"));
	$GLOBALS['tmpl']->assign("seo_title",app_conf("SEO_TITLE"));
	$GLOBALS['tmpl']->assign("seo_keyword",app_conf("SEO_KEYWORD"));
	$GLOBALS['tmpl']->assign("seo_description",app_conf("SEO_DESCRIPTION"));
	
	$helps = load_auto_cache("helps");
	$GLOBALS['tmpl']->assign("helps",$helps);
	
	//删除超过三天的订单
	//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where order_status = 0 and credit_pay = 0 and  ".NOW_TIME." - create_time > ".(24*3600*3));
	
	
	$has_deal_notify = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_notify"));
	define("HAS_DEAL_NOTIFY",$has_deal_notify); //存在待发的项目通知
	
	if($user_info)
	{
	$user_notify_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_notify where user_id = ".intval($user_info['id'])." and is_read = 0"));
	$user_message_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_message where user_id = ".intval($user_info['id'])." and is_read = 0"));
	$GLOBALS['tmpl']->assign("USER_NOTIFY_COUNT",$user_notify_count);
	$GLOBALS['tmpl']->assign("USER_MESSAGE_COUNT",$user_message_count);
	$GLOBALS['tmpl']->assign("HIDE_USER_NOTIFY",intval(es_cookie::get("hide_user_notify")));
	}

}


//页脚的文章分类信息 star

$help_cates = load_auto_cache("new_hepls");
$GLOBALS['tmpl']->assign("help_cates",$help_cates);
//页脚的文章分类信息 end

$GLOBALS['tmpl']->assign("now",NOW_TIME);

$is_tg=intval(is_tg());

//$is_user_tg=is_user_tg();
//$is_user_investor=is_user_investor();
//if($is_tg){
//	$GLOBALS['tmpl']->assign("tg_register_url",get_domain()."/wap/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id']);
//}
//$GLOBALS['tmpl']->assign("is_tg",$is_tg);
//$GLOBALS['tmpl']->assign("is_user_tg",$is_user_tg);
//$GLOBALS['tmpl']->assign("is_user_investor",$is_user_investor);

?>