<?php
// +----------------------------------------------------------------------
// | Fanwe 方维订餐小秘书商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$api_lang = array(
	'name'	=>	'微信登录',
);

$config = array(
);

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
	if(ACTION_NAME=='install')
	{
		
	}
    $module['class_name']    = 'Weixin';

    /* 名称 */
    $module['name']    = $api_lang['name'];
	$module['dispname']    = "微信登录";
	$module['config'] = $config;
	
	$module['lang'] = $api_lang;
    
    return $module;
}

// Weixin的api登录接口
require_once(APP_ROOT_PATH.'system/libs/api_login.php');
class Weixin_api implements api_login {
	
	private $api;
	
	public function __construct($api)
	{
		$api['config'] = unserialize($api['config']);
		if (file_exists(APP_ROOT_PATH."/public/images/api_login/".__CLASS__.".png")) {
			$default_img= SITE_DOMAIN.APP_ROOT."/public/images/api_login/".__CLASS__.".png";
			if(!$api['icon']){
				$api['icon']=$default_img;
			}
			if(!$api['bicon']){
				$api['bicon']=$default_img;
			}
		}
		$this->api = $api;
	}
	
	public function get_api_url()
	{
		$str = "<a href='javascript:void(0);' id='weixin_login' title='".$this->api['name']."'><img src='".$this->api['icon']."' alt='".$this->api['name']."' /></a>&nbsp;";
		return $str;
	}
	
	public function get_big_api_url()
	{
		$str = "<a href='javascript:void(0);' id='weixin_login'  title='".$this->api['name']."'><img src='".$this->api['bicon']."' alt='".$this->api['name']."' /></a>&nbsp;";
		return $str;
	}	
	
	public function get_bind_api_url()
	{
		return'';
	}	
		
	public function callback()
	{
	   return'';
	}
	
	public function get_title()
	{
		return '微信登录，需要php_curl扩展的支持';
	}
	public function create_user()
	{
	   return'';
	}	
	
	//解除API 绑定
	public function unset_api(){
	   return'';
	}    
	
	//同步微博信息
	public function send_message($data){
	   return''; 
	}
}
?>