<?php 

if(!defined('APP_ROOT_PATH')) 
	define('APP_ROOT_PATH', str_replace('system/payment/wbfpay/config.php', '', str_replace('\\', '/', __FILE__)));
	
	
return  $config_ini = array(
	'InterfaceVersion' =>"4.0",//接口版本号Int ,现接口版本为 4.0
	'PageUrl' =>get_domain().APP_ROOT.'/wbfpay_web/wmerchant_url.php',//页面通知地址String,需 URL 编码，长度不超过 255 位
	'ReturnUrl' =>get_domain().APP_ROOT.'/wbfpay_web/return_url.php',//服务器通知地址String,需 URL 编码，长度不超过 255 位
	'NoticeType' =>"1",//通知方式 Int,1：服务器通知和页面通知。支付成功后，自动重定向到“页面通知地址”0：只发服务器端通知，不跳转
	'KeyType' =>"1",//加密类型 是 Int,1：md5;KeyType=1
);
?>
