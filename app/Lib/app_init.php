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

define("DEAL_PAGE_SIZE",32);
define("DEAL_STEP_SIZE",8);

define("DEALUPDATE_PAGE_SIZE",15);
define("DEALUPDATE_STEP_SIZE",5);

define("DEAL_COMMENT_PAGE_SIZE",40);

define("DEAL_SUPPORT_PAGE_SIZE",20);

define("ACCOUNT_PAGE_SIZE",10);

define("SCORE_GOODS_PAGE_SIZE",60);//dz_chh
define("SCORE_GOODS_STEP_SIZE",8);

require APP_ROOT_PATH.'app/Lib/BaseModule.class.php';
define("CTL",'ctl');
define("ACT",'act');

if($GLOBALS['pay_req'][CTL])
	$_REQUEST[CTL] = $GLOBALS['pay_req'][CTL];
if($GLOBALS['pay_req'][ACT])
	$_REQUEST[ACT] = $GLOBALS['pay_req'][ACT];
	
$module = ((!empty($_REQUEST[CTL])?$_REQUEST[CTL]:"index"));

$action = ((!empty($_REQUEST[ACT])?$_REQUEST[ACT]:"index"));

 
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

$GLOBALS['tmpl']->assign("module",$module);
$GLOBALS['tmpl']->assign("action",$action);
//载入会员登录信息
//会员自动登录及输出
$cookie_uname = es_cookie::get("email")?es_cookie::get("email"):'';
$cookie_upwd = es_cookie::get("user_pwd")?es_cookie::get("user_pwd"):'';
if($cookie_uname!=''&&$cookie_upwd!=''&&!es_session::get("user_info")&&!es_session::get('user_info'))
{
	$cookie_uname = strim($cookie_uname);
	$cookie_upwd =  strim($cookie_upwd);
	require_once APP_ROOT_PATH."system/libs/user.php";
	//auto_do_login_user($cookie_uname,$cookie_upwd);
}
$user_info = es_session::get('user_info');
if($user_info)
{
	if(MAX_LOGIN_TIME>0){
 		$user_logined_time = intval($user_info['login_time']);
    	if((NOW_TIME-$user_logined_time)>=intval(MAX_LOGIN_TIME))
		{
 			es_session::delete('user_info');
			$user_info = '';
		}else{
			$GLOBALS['tmpl']->assign("user_info",$user_info);
		}
	}else{
		$GLOBALS['tmpl']->assign("user_info",$user_info);
	}
	
}
 
$helps = load_auto_cache("helps");
$GLOBALS['tmpl']->assign("helps",$helps);

$message_cate = load_auto_cache("message_cate");
$GLOBALS['tmpl']->assign("message_cate",$message_cate);

$user_level= load_auto_cache("user_level");
$GLOBALS['tmpl']->assign("user_level",$user_level);

$deal_cate= load_auto_cache("deal_cate");
$GLOBALS['tmpl']->assign("deal_cate",$deal_cate);

$article_cates_bs= load_auto_cache("article_cates_bs");
$GLOBALS['tmpl']->assign("article_cates_bs",$article_cates_bs);

$article_cates= load_auto_cache("article_cates");
$GLOBALS['tmpl']->assign("article_cates",$article_cates);

$articles= load_auto_cache("article");
$GLOBALS['tmpl']->assign("articles",$articles);


if($module!="ajax")
{
	if($user_info)
	{
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id'])." and is_effect = 1");
		es_session::set('user_info',$user_info);
		//查询登入用户所对应的user_level
		//$user_level=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level where id=".intval($GLOBALS['user_info']['user_level']));
 		//给前台会员的level值
		$user_info['user_icon']=$user_level[$user_info['user_level']]['icon'];
		$user_info['cate_name']=unserialize($user_info['cate_name']);
		 
		//var_dump($user_info['cate_name']);
		//$GLOBALS['tmpl']->assign("user_level",$user_level);
		$GLOBALS['tmpl']->assign("user_info",$user_info);
	}
	
	global $ref_uid;
	//保存返利的cookie
	if($_REQUEST['ref'])
	{
		$rid = intval(base64_decode($_REQUEST['ref']));
		$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval($rid)));
		es_cookie::set("REFERRAL_USER",intval($ref_uid));
	}
	else
	{
		//获取存在的推荐人ID
		if(intval(es_cookie::get("REFERRAL_USER"))>0)
			$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval(es_cookie::get("REFERRAL_USER"))));
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
	
	$GLOBALS['tmpl']->assign("gq_name",app_conf("GQ_NAME"));
	
  	//删除超过三天的订单
	//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where order_status = 0 and credit_pay = 0 and  ".NOW_TIME." - create_time > ".(24*3600*3));
	
	
	$has_deal_notify = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_notify"));
	define("HAS_DEAL_NOTIFY",$has_deal_notify); //存在待发的项目通知
	
	if($user_info)
	{
	$user_notify_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_notify where user_id = ".intval($user_info['id'])." and is_read = 0"));
	$user_message_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_message where user_id = ".intval($user_info['id'])." and is_read = 0"));
	//邀请数量总数（公司邀请和机构邀请）
	$user_invite_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."invite where user_id = ".intval($user_info['id'])));
	$GLOBALS['tmpl']->assign("USER_NOTIFY_COUNT",$user_notify_count);
	$GLOBALS['tmpl']->assign("USER_MESSAGE_COUNT",$user_message_count);
	$GLOBALS['tmpl']->assign("USER_INVITE_COUNT",$user_invite_count);
	$GLOBALS['tmpl']->assign("HIDE_USER_NOTIFY",intval(es_cookie::get("hide_user_notify")));
	}

}
$g_links =get_link_by_id();     
$GLOBALS['tmpl']->assign("g_links",$g_links);

//页脚的文章分类信息 star

$help_cates = load_auto_cache("new_hepls");
$GLOBALS['tmpl']->assign("help_cates",$help_cates);
//页脚的文章分类信息 end
//get_mortgate();
$GLOBALS['tmpl']->assign("now",NOW_TIME);

$is_tg=intval(is_tg(true));
 
$is_user_tg=is_user_tg();
$is_user_investor=is_user_investor();
if($is_tg){
	$GLOBALS['tmpl']->assign("tg_register_url",APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id']);
}
$GLOBALS['tmpl']->assign("is_tg",$is_tg);
$GLOBALS['tmpl']->assign("is_user_tg",$is_user_tg);
$GLOBALS['tmpl']->assign("is_user_investor",$is_user_investor);

$app=array();
$app['web_url']=SITE_DOMAIN.APP_ROOT;
$web_dir=APP_ROOT_PATH."public/images/qrcode/zc.png";
$web_dir_logo=APP_ROOT_PATH."public/images/qrcode/zc_logo.png";

if(!is_file($web_dir)||!is_file($web_dir_logo)){
	get_qrcode_png($app['web_url'],$web_dir,$web_dir_logo);
}
$m_config = getMConfig();//初始化手机端配置
if($m_config['android_filename']||$m_config['ios_down_url']){
		$app['android_filename'] =$m_config['android_filename'];
		$app['ios_down_url'] =$m_config['ios_down_url'];
 		$app['is_app']=1;
		$app['app_file_url']=SITE_DOMAIN.url_wap("app_download");;
		$app['app_url_logo']=SITE_DOMAIN.APP_ROOT.'/public/images/qrcode/zc_app_logo.png';
		$qrcode_dir=APP_ROOT_PATH."public/images/qrcode/zc_app.png";
		$qrcode_dir_logo=APP_ROOT_PATH."public/images/qrcode/zc_app_logo.png";
		if(!is_file($qrcode_dir)||!is_file($qrcode_dir_logo)){
			get_qrcode_png($app['app_file_url'],$qrcode_dir,$qrcode_dir_logo);
		}
		$GLOBALS['tmpl']->assign("app",$app);
 }
//查看是否有调查问卷
if(intval(app_conf("VOTE_ID"))){
	$vote_url =url("vote#index",array("id"=>intval(app_conf("VOTE_ID"))));
	$GLOBALS['tmpl']->assign("vote_url",$vote_url);
}

//获取最大的上传图片和文件大小
$max_size = get_max_file_size();
$GLOBALS['tmpl']->assign("max_size",$max_size);
$max_size_byte = get_max_file_size_byte();
$GLOBALS['tmpl']->assign("max_size_byte",$max_size_byte);
 
 
?>