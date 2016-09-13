<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------



if (PHP_VERSION >= '5.0.0')
{
	$begin_run_time = @microtime(true);
}
else
{
	$begin_run_time = @microtime();
}
@set_magic_quotes_runtime (0);
define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
if(!defined('IS_CGI'))
define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
 if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',  rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }

if(!defined('APP_ROOT')) {
        // 网站URL根目录
        $_root = dirname(_PHP_FILE_);
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        $_root = str_replace("/system","",$_root);
        $_root = str_replace("/wap","",$_root);
        define('APP_ROOT', $_root  );
}



 if(!defined('APP_ROOT_PATH')) 
	define('APP_ROOT_PATH', str_replace('system/system_init.php', '', str_replace('\\', '/', __FILE__)));
    error_reporting(0);


require APP_ROOT_PATH."/system/phpqrcode/license";

//关于安装的检测
if(FANWE)die();
if(!file_exists(APP_ROOT_PATH."public/install.lock"))
{
	app_redirect(APP_ROOT."/install/index.php");
}	
if(IS_DEBUG){
	ini_set("display_errors", 1);
 	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
 	$GLOBALS['msg']->set_debug(true);
}
else
	error_reporting(0);
	
	//输出后台URL文件名称
define('URL_NAME',app_conf("URL_NAME"));
$GLOBALS['tmpl']->assign("URL_NAME",URL_NAME);
?>