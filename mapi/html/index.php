<?php 
// +----------------------------------------------------------------------
// | EaseTHINK 易想团购系统 mapi 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
require '../../system/system_init.php';;
require './common.php';
define('REAL_APP_ROOT',str_replace('/mapi/html',"",APP_ROOT));
define('REAL_APP_ROOT_PATH',str_replace('/mapi/html',"",APP_ROOT_PATH));

$GLOBALS['tmpl']->assign("REAL_APP_ROOT",REAL_APP_ROOT);
//定义缓存路经
filter_injection($_REQUEST);

if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/',0777);
}
if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/tpl_caches/',0777);
if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/tpl_compiled/',0777);
$GLOBALS['tmpl']->cache_dir      = APP_ROOT_PATH . 'public/runtime/mapi/tpl_caches';
$GLOBALS['tmpl']->compile_dir    = APP_ROOT_PATH . 'public/runtime/mapi/tpl_compiled';

//定义模板路径
$GLOBALS['tmpl']->template_dir   = APP_ROOT_PATH . 'mapi/html/tpl/default';
$tmpl_path = REAL_APP_ROOT."/mapi/html/tpl/";
$GLOBALS['tmpl']->assign("TMPL",$tmpl_path."default");
$GLOBALS['tmpl']->assign("APP_ROOT_URL",get_domain().APP_ROOT);
 
$GLOBALS['tmpl']->assign("TMPL_REAL",APP_ROOT_PATH."mapi/html/tpl/default");

//
define('MAPI_DATA_CACHE_DIR',APP_ROOT_PATH.'public/runtime/mapi/data_caches');
$m_config = getMConfig();//初始化手机端配置
$GLOBALS['tmpl']->assign('m_config',$m_config);
$GLOBALS['tmpl']->assign('now_time',NOW_TIME);
define("CACHE_TIME",60); //动态数据缓存时间，300秒
if (intval($m_config['page_size']) == 0){
	define('PAGE_SIZE',20); //分页的常量
}else{
	define('PAGE_SIZE',intval($m_config['page_size'])); //分页的常量
}
$GLOBALS['tmpl']->assign("site_name",$m_config['program_title']?$m_config['program_title']:app_conf("SITE_NAME"));
$GLOBALS['tmpl']->assign("site_logo",$m_config['logo']?$m_config['logo']:app_conf("SITE_LOGO"));

//用户信息
$user_id=intval($_REQUEST['user_id']);
if($user_id >0)
{
	$user_info=$GLOBALS['db']->getOne("select * from ".DB_PREFIX."user where id=".$user_id);
}else
{
	$user_info=array();
}

$class = strtolower(strim($_REQUEST['ctl']))?strtolower(strim($_REQUEST['ctl'])):"index";
$act2 = strtolower(strim($_REQUEST['act']))?strtolower(strim($_REQUEST['act'])):"index";
$city_id = intval($request['city_id']);
define('ACT',$class); //act常量
define('ACT_2',$act2);

$GLOBALS['tmpl']->assign("class",$class);
$GLOBALS['tmpl']->assign("act",$act2);

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