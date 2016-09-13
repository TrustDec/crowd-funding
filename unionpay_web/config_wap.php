<?php
require '../system/system_init.php';
if(!defined('APP_ROOT_PATH')) 
		define('APP_ROOT_PATH', str_replace('unionpay_web/config_wap.php', '', str_replace('\\', '/', __FILE__)));

$payment_config = $GLOBALS['db']->getOne("select config from ".DB_PREFIX."payment where class_name='Wunionpay'");
$config=unserialize($payment_config);
$sign_cert_pwd=$config['sign_cert_pwd'];
define("SDK_SIGN_CERT_PWD_2",$sign_cert_pwd);// 签名证书密码
// 前台通知地址 (商户自行配置通知地址)
define("SDK_FRONT_NOTIFY_URL",get_domain().APP_ROOT.'/call_back_url.php?from='.$_REQUEST['from']);
// 后台通知地址 (商户自行配置通知地址)
define("SDK_BACK_NOTIFY_URL",get_domain().APP_ROOT.'/notify_url.php');
//日志 目录
if($_REQUEST['from'] =='wap')
	define("SDK_LOG_FILE_PATH",APP_ROOT_PATH.'/public/logger/yinl_logs/logs_wap');
else
	define("SDK_LOG_FILE_PATH",APP_ROOT_PATH.'/public/logger/yinl_logs/logs_app');

define("UNIONPAY_PATH",APP_ROOT_PATH.'unionpay_web/func/');//存放 函数，类文件，配置文件 文件夹路径
define("UNIONPAY_CERTS_PATH",APP_ROOT_PATH.'unionpay_web/certs_wap/');//存放 证书 文件夹路径
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(app_conf('DEFAULT_TIMEZONE'));
require_once(UNIONPAY_PATH.'SDKConfig.php');
require_once(UNIONPAY_PATH.'common.php');
require_once(UNIONPAY_PATH.'secureUtil.php');
require_once(UNIONPAY_PATH.'log.class.php');

?>