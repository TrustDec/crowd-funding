<?php
if(!defined('APP_ROOT_PATH')) 
		define('APP_ROOT_PATH', str_replace('system/payment/jdpay/config/config.php', '', str_replace('\\', '/', __FILE__)));

$payment_config = $GLOBALS['db']->getOne("select config from ".DB_PREFIX."payment where class_name='UnionpayPc'");
$config=unserialize($payment_config);
$sign_cert_pwd=$config['sign_cert_pwd'];
define("SDK_SIGN_CERT_PWD_2",$sign_cert_pwd);// 签名证书密码
// 前台通知地址 (商户自行配置通知地址)
define("SDK_FRONT_NOTIFY_URL",get_domain().APP_ROOT.'/index.php?ctl=payment&act=response&class_name=UnionpayPc');
// 后台通知地址 (商户自行配置通知地址)
define("SDK_BACK_NOTIFY_URL",get_domain().APP_ROOT.'/yinlpc_notify.php');
//日志 目录 
define("SDK_LOG_FILE_PATH",APP_ROOT_PATH.'/public/logger/yinl_logs/logs_pc');

define("UNIONPAY_PATH",APP_ROOT_PATH.'system/payment/Unionpay/');//存放 函数，类文件，配置文件 文件夹路径
define("UNIONPAY_CERTS_PATH",APP_ROOT_PATH.'unionpay_web/certs_pc/');//存放 证书 文件夹路径
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(TIME_ZONE);

require_once(UNIONPAY_PATH.'SDKConfig.php');
require_once(UNIONPAY_PATH.'common.php');
require_once(UNIONPAY_PATH.'secureUtil.php');
require_once(UNIONPAY_PATH.'log.class.php');
?>