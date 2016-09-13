<?php
if(!defined('CK_ROOT_PATH')) 
	define('CK_ROOT_PATH', str_replace('/admin/public/kindeditor/check.php', '', str_replace('\\', '/', __FILE__)));
require CK_ROOT_PATH.'/system/system_init.php';
$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
$adm_name = $adm_session['adm_name'];
$adm_id = intval($adm_session['adm_id']);
if($adm_id==0 && !es_session::get("user_info")){
	app_redirect("404.html");
	exit();
}
?>
