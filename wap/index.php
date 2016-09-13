<?php 
// +----------------------------------------------------------------------
// | EaseTHINK 易想团购系统 mapi 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

//define('APP_ROOT','zhongc');

require '../system/system_init.php';
require '../system/utils/transport.php';
require './common.php';
require './functions.php';
require '../system/utils/weixin.php';
define("DEAL_PAGE_SIZE",60);
define("DEAL_STEP_SIZE",12);

define("DEALUPDATE_PAGE_SIZE",15);
define("DEALUPDATE_STEP_SIZE",5);

define("DEAL_COMMENT_PAGE_SIZE",40);

define("DEAL_SUPPORT_PAGE_SIZE",20);

define("ACCOUNT_PAGE_SIZE",10);
$transport = new transport;
$transport->use_curl = true;
$GLOBALS['tmpl']->assign("APP_ROOT",APP_ROOT);
$GLOBALS['tmpl']->assign("APP_URL",get_domain().APP_ROOT."/wap");
$GLOBALS['tmpl']->assign("PC_URL",get_domain().APP_ROOT);


define('REAL_APP_ROOT',str_replace('/wap',"",APP_ROOT));
define('REAL_APP_ROOT_PATH',str_replace('/wap',"",APP_ROOT));

//get_mortgate();
//======
filter_injection($_REQUEST);
if($_REQUEST['clear']=='app'){
	es_session::delete("is_app_type");
}
if($_REQUEST['from_type']=='IOS'||$_REQUEST['from_type']=='ANDROID'|| es_session::get('is_app_type')){
	$postfix = '_app';
}else{
	$postfix = '';
}

if(!file_exists(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/',0777);
}
if(!file_exists(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/tpl_caches/',0777);
if(!file_exists(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/wap'.$postfix.'/tpl_compiled/',0777);
$GLOBALS['tmpl']->cache_dir      = APP_ROOT_PATH . 'public/runtime/wap'.$postfix.'/tpl_caches';
$GLOBALS['tmpl']->compile_dir    = APP_ROOT_PATH . 'public/runtime/wap'.$postfix.'/tpl_compiled';
$GLOBALS['tmpl']->template_dir   = APP_ROOT_PATH . 'wap/tpl/default';

//定义模板路径
$tmpl_path = get_domain().APP_ROOT."/wap/tpl/";
$GLOBALS['tmpl']->assign("TMPL",$tmpl_path."default");
$GLOBALS['tmpl']->assign("APP_ROOT_URL",get_domain().APP_ROOT);
 
$GLOBALS['tmpl']->assign("TMPL_REAL",APP_ROOT_PATH."wap/tpl/default"); 

$GLOBALS['tmpl']->assign("font_url",get_domain().APP_ROOT."/public/script/Font-Awesome-4.2.0/css/font-awesome.min.css");

//初始化session
global $sess_id;
global $define_sess_id;
$sess_id = strim($_REQUEST['session_id']);
if($sess_id)
{
	$sess_verify = strim($_REQUEST['sess_verify']);
	//开始为session获取一个新分配的id
	$alloc_sess_id = es_session::id();
	
	//再用指定sess_id打开
	$define_sess_id = true;
	es_session::set_sessid($sess_id);
	es_session::restart();
	unset($_REQUEST['session_id']);
	
	if(es_session::get("sess_verify")==$sess_verify&&es_session::get("sess_verify")!="")
	{
		$define_sess_id = true;
		es_session::delete("sess_verify");
	}
	else
	{
		es_session::set_sessid($alloc_sess_id);
		es_session::restart();		
		$define_sess_id = false;
		$sess_id= $alloc_sess_id;
	}
}
else
{
	$define_sess_id = false;
	$sess_id= es_session::id();
}
$GLOBALS['tmpl']->assign("hash_key",HASH_KEY()); 
//用户信息
$user_info = es_session::get('user_info');
if($module!="ajax")
{
	if($user_info)
	{
		if(MAX_LOGIN_TIME>0){
			$user_logined_time = intval($user_info['login_time']);
			if((NOW_TIME-$user_logined_time)>=intval(MAX_LOGIN_TIME))
			{
				es_session::delete('user_info');
				$user_info = '';
			}else{
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id'])." and is_effect = 1");
				es_session::set('user_info',$user_info);
				//查询登入用户所对应的user_level
				$user_level=$GLOBALS['db']->getAll("select level from ".DB_PREFIX."user_level where id=".intval($GLOBALS['user_info']['user_level']));
				//给前台会员的level值
				$GLOBALS['tmpl']->assign("user_level",$user_level);
				$user_info['user_icon']=$user_level[$user_info['user_level']]['icon'];
				$user_info['cate_name']=unserialize($user_info['cate_name']);
				$GLOBALS['tmpl']->assign("user_info",$user_info);
			}
		}else{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id'])." and is_effect = 1");
			es_session::set('user_info',$user_info);
			//查询登入用户所对应的user_level
			$user_level=$GLOBALS['db']->getAll("select level from ".DB_PREFIX."user_level where id=".intval($GLOBALS['user_info']['user_level']));
			//给前台会员的level值
			$GLOBALS['tmpl']->assign("user_level",$user_level);
			$user_info['user_icon']=$user_level[$user_info['user_level']]['icon'];
			$user_info['cate_name']=unserialize($user_info['cate_name']);
			$GLOBALS['tmpl']->assign("user_info",$user_info);
		}
		
	}
	
	 
	//输出SEO元素
	//$GLOBALS['tmpl']->assign("site_name",app_conf("SITE_NAME"));
	$GLOBALS['tmpl']->assign("seo_title",app_conf("SEO_TITLE"));
	$GLOBALS['tmpl']->assign("seo_keyword",app_conf("SEO_KEYWORD"));
	$GLOBALS['tmpl']->assign("seo_description",app_conf("SEO_DESCRIPTION"));
	$GLOBALS['tmpl']->assign("gq_name",app_conf("GQ_NAME"));
	
	$helps = load_auto_cache("helps");
	$GLOBALS['tmpl']->assign("helps",$helps);
	
	//删除超过三天的订单
	//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where order_status = 0 and credit_pay = 0 and  ".NOW_TIME." - create_time > ".(24*3600*3));
	
	
	$has_deal_notify = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_notify"));
	define("HAS_DEAL_NOTIFY",$has_deal_notify); //存在待发的项目通知
	 
}
//获取最大的上传图片和文件大小
$max_size = get_max_file_size();
$GLOBALS['tmpl']->assign("max_size",$max_size);
$max_size_byte = get_max_file_size_byte();
$GLOBALS['tmpl']->assign("max_size_byte",$max_size_byte);

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

$user_level= load_auto_cache("user_level");
$GLOBALS['tmpl']->assign("user_level",$user_level);
//
define('MAPI_DATA_CACHE_DIR',APP_ROOT_PATH.'public/runtime/mapi/data_caches');
$m_config = getMConfig();//初始化手机端配置
$GLOBALS['tmpl']->assign('m_config',$m_config);
$GLOBALS['tmpl']->assign('now_time',NOW_TIME);
define('VERSION',1); //接口版本号,float 类型
define("CACHE_TIME",60); //动态数据缓存时间，300秒
if (intval($m_config['page_size']) == 0){
	define('PAGE_SIZE',20); //分页的常量
}else{
	define('PAGE_SIZE',intval($m_config['page_size'])); //分页的常量
}


$GLOBALS['tmpl']->assign("site_name",$m_config['program_title']?$m_config['program_title']:app_conf("SITE_NAME"));
$GLOBALS['tmpl']->assign("site_logo",$m_config['logo']?$m_config['logo']:app_conf("SITE_LOGO"));

$class = strtolower(strim($_REQUEST['ctl']))?strtolower(strim($_REQUEST['ctl'])):"index";

$act2 = strtolower(strim($_REQUEST['act']))?strtolower(strim($_REQUEST['act'])):"index";
$city_id = intval($request['city_id']);
define('ACT',$class); //act常量
define('ACT_2',$act2);

$se_url=es_session::get("gopreview"); 

$GLOBALS['tmpl']->assign("class",$class);
$GLOBALS['tmpl']->assign("act",$act2);
get_pre_wap();
$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
		
if(!$cate_list)
{
	$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate  order by sort asc");
	set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
}
$is_weixin=isWeixin();
$GLOBALS['tmpl']->assign("is_weixin",$is_weixin);
if($se_url){
	$current_url =$se_url;
}else{ 
	if($_REQUEST['id']){
		$current_url =  url_wap($class."#".$act2,array('id'=>$_REQUEST['id']));
	}else{
		$current_url =  url_wap($class."#".$act2);
	}
	
}

$is_tg=intval(is_tg());
$is_user_tg=is_user_tg();
$is_user_investor=is_user_investor();
if($is_tg){
	$GLOBALS['tmpl']->assign("tg_register_url",REAL_APP_ROOT_PATH."/wap/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id']);
}
$GLOBALS['tmpl']->assign("is_tg",$is_tg);
$GLOBALS['tmpl']->assign("is_user_tg",$is_user_tg);
$GLOBALS['tmpl']->assign("is_user_investor",$is_user_investor);

$GLOBALS['tmpl']->assign("cate_list",$cate_list);
$weixin_conf = load_auto_cache("weixin_conf");
if(!$user_info&&$is_weixin){
	$wx_status = (($m_config['wx_appid']&&$m_config['wx_secrit'])||($weixin_conf['platform_status']&&$weixin_conf['platform_appid']&&$weixin_conf['platform_component_access_token']))?1:0;
	 
	if($_REQUEST['code']&&$_REQUEST['state']==1&&$wx_status){
		//file_put_contents('./t.txt',var_export($_REQUEST,TRUE)."==1\n",FILE_APPEND);
		//require '../system/utils/weixin.php';
		$weixin=new weixin($m_config['wx_appid'],$m_config['wx_secrit'],get_domain().$current_url);
 		if($weixin_conf['platform_status']){
 			$wx_info=$weixin->scope_get_userinfo($_REQUEST['code'],$_REQUEST['appid']);
		}else{
			$wx_info=$weixin->scope_get_userinfo($_REQUEST['code']);
		}
 		
	  	if($wx_info['errcode']>0){
			var_dump($wx_info);exit;
		}
		
	  	if($wx_info['openid']){
			$wx_user_info=get_user_has('wx_openid',$wx_info['openid']);
			
			if($wx_user_info){
	  			if($wx_user_info['mobile']){
					$name=$wx_user_info['mobile'];
				}elseif($wx_user_info['email']){
					$name=$wx_user_info['email'];
				}else{
					$name=$wx_user_info['user_name'];
				}
	 			require_once APP_ROOT_PATH."system/libs/user.php";
				//如果会员存在，直接登录
	 			do_login_user($name,$wx_user_info['user_pwd']);
	 		}else{
				//会员不存在进入登录流程
				$class='user';
				$act2='wx_register';
	 		}
		}
	}else{
  			if($is_weixin&&!$user_info&&$wx_status&&$m_config['wx_controll']==1&&$class!='ajax'&&$class=='user'&&$act2=='login'){
 				 
				if($_REQUEST['target']){
					$current_url = create_target_url($_REQUEST['target'],1);
				}else{
					if($_REQUEST['deal_id']){
						$current_url = create_target_url("URL-dealID-".intval($_REQUEST['deal_id']),1);
					}
				}
				$weixin_2=new weixin($m_config['wx_appid'],$m_config['wx_secrit'],get_domain().$current_url);
				if($weixin_conf['platform_status']){
					$appid=$_REQUEST['appid'];
					if(!$appid){
						$appid = $GLOBALS['db']->getOne("select authorizer_appid from ".DB_PREFIX."weixin_account where type=1 and user_id=0 ");
						if(!$appid){
							var_dump("管理员未绑定微信账号");exit;
						}
 					}
 					$wx_url=$weixin_2->scope_get_code($appid);
				}else{
 					$wx_url=$weixin_2->scope_get_code();
				}
				//echo $wx_url;exit;
 				app_redirect($wx_url);
			}else{
				if($is_weixin&&!$user_info&&$wx_status&&$class!='ajax'&&$class!='user'&&$m_config['wx_controll']==0){
					$weixin_2=new weixin($m_config['wx_appid'],$m_config['wx_secrit'],get_domain().$current_url);
					if($weixin_conf['platform_status']){
						$appid=$_REQUEST['appid'];
						if(!$appid){
							$appid = $GLOBALS['db']->getOne("select authorizer_appid from ".DB_PREFIX."weixin_account where type=1 and user_id=0 ");
							if(!$appid){
								var_dump("管理员未绑定微信账号");exit;
							}
						}
						$wx_url=$weixin_2->scope_get_code($appid);
					}else{
						$wx_url=$weixin_2->scope_get_code();
					}
					//echo $wx_url;exit;
					//file_put_contents('./t.txt',var_export($_REQUEST,TRUE)."==2\n",FILE_APPEND);
 					app_redirect($wx_url);
				}
			}
 	}
 	
 	if($wx_status&&$class!='cart'){
		require_once APP_ROOT_PATH."system/utils/jssdk.php";
		if($weixin_conf['platform_status']){
			$appid=$_REQUEST['appid'];
			if(!$appid){
				$appid = $GLOBALS['db']->getOne("select authorizer_appid from ".DB_PREFIX."weixin_account where type=1 and user_id=0 ");
				 
			}
			if($appid){
			 	$jssdk = new JSSDK($appid);
			}
		}else{
			$jssdk = new JSSDK($m_config['wx_appid'],$m_config['wx_secrit']);
		}
 		$signPackage = $jssdk->getSignPackage();	
		$GLOBALS['tmpl']->assign("signPackage",$signPackage);
	  	$wx_url=get_domain().$_SERVER["REQUEST_URI"];
	  	$GLOBALS['tmpl']->assign("wx_url",$wx_url);
	}
}

$input_array = $_GET;
$input_str = '';
unset($input_array['ctl']);
unset($input_array['act']);
if($input_array){
	foreach($input_array as $k=>$v){
		if($v){
			if($class=='index'){
				break;
			}else{
				if($k!='id'){
					$input_str .='-'.$k.'-'.strim($v);
				}
			}
		}
	}
 }
$mobile_id = $class.'-'.$act2;
/*if($input_str){
	$mobile_id .=$input_str;
}*/
$GLOBALS['tmpl']->assign('mobile_id',$mobile_id);


$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
$is_back = intval($_REQUEST['is_back'])?intval($_REQUEST['is_back']):1;
 if(($HTTP_REFERER == ""||$class=="deals"||($class=='cart'&&$act2=='go_pay'))&&$is_back==1){
 	$HTTP_REFERER = url_wap("index");
	$is_back = 0;
}elseif($is_back==2){
	
	if($class=="deal"&&($act2=='update'||$act2=='comment')){
	
		$back_url = url_wap("deal#show",array('id'=>intval($_REQUEST['id']),'first_post'=>1));
	}
}
$tmpl->assign("back_url", $back_url?$back_url:url_wap("index"));
$tmpl->assign("is_back", $is_back);
$tmpl->assign("HTTP_REFERER", $HTTP_REFERER);
if($user_info['id']==17){
	log_result_notify(var_export($_SERVER,true));
	log_result_notify(var_export($is_back,true));
}
$is_app =false;
if($_REQUEST['from_type']=='IOS'||$_REQUEST['from_type']=='ANDROID'|| es_session::get('is_app_type')){
	if(!$_REQUEST['from_type']){
		$_REQUEST['from_type'] = es_session::get('is_app_type');
	}
	$is_app = $_REQUEST['from_type'];
	if(!es_session::get('is_app_type')){
		es_session::set('is_app_type',$is_app);
	}
	
	$left_app_msg = 'back';
	$first_post = intval($_REQUEST['first_post']);
	if($class=='index' || $first_post==1||($class=='cart'&&$act2=='go_pay')){
		$left_app_msg = 'list';
	}
	$right_app_msg = 0;
	$tmpl->assign("left_app_msg", $left_app_msg);
	$tmpl->assign("right_app_msg", $right_app_msg);
	
	$is_back_first = 0;
	$tmpl->assign("is_back_first", $is_back_first);
	
	$is_first_start = intval($_REQUEST['is_first_start']);
	$tmpl->assign("is_first_start", $is_first_start);
	//是否登录
	if($user_info){
		$is_login = intval($_REQUEST['is_login']);
		$tmpl->assign("is_login", $is_login);
	}else{
		$is_loginout = intval($_REQUEST['is_loginout']);
		$tmpl->assign("is_loginout", $is_loginout);
	}
	
	
	
}
//是否登录
	if($user_info){
		$is_login = intval($_REQUEST['is_login']);
		$tmpl->assign("is_login", $is_login);
	}else{
		$is_loginout = intval($_REQUEST['is_loginout']);
		$tmpl->assign("is_loginout", $is_loginout);
	}
$login_info = json_encode(array('user_name'=>$user_info['user_name'],'id'=>intval($user_info['id']),'user_pwd'=>$user_info['user_pwd']));
$tmpl->assign("login_info", $login_info);
$tmpl->assign("is_app",$is_app);

$is_sdk_browser= 0;
if(strpos(strtolower($_SERVER['ALL_HTTP']), 'fanwe_app_sdk') !== false)   
	$is_sdk_browser++;   
if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'fanwe_app_sdk') !== false)   
	$is_sdk_browser++;
$tmpl->assign("is_sdk", $is_sdk_browser);	
//获取最大的上传图片和文件大小
$max_size = get_max_file_size();
$GLOBALS['tmpl']->assign("max_size",$max_size);
$max_size_byte = get_max_file_size_byte();
$GLOBALS['tmpl']->assign("max_size_byte",$max_size_byte);

  //公共初始化
if(file_exists("./lib/".$class.".action.php"))
{	
	
	require_once "./lib/".$class.".action.php";	
	//if($class=='index'){
		$class=$class.'Module';
	//}
 	if(class_exists($class))
	{
 		$obj = new $class;		
		if(method_exists($obj,$act2))
		{
			
			$obj->$act2();
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			exit("Hack attemp!");
		}
	}
	else
	{
		header("Content-Type:text/html; charset=utf-8");
		exit("Hack attemp!");
	}
}
else
{
	header("Content-Type:text/html; charset=utf-8");
	exit("Hack attemp!".ACT);
}

?>