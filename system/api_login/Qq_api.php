<?php
// +----------------------------------------------------------------------
// | Fanwe 方维订餐小秘书商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$api_lang = array(
	'name'	=>	'QQv2登录插件',
	'app_key'	=>	'QQAPI应用appid',
	'app_secret'	=>	'QQAPI应用appkey',
);

$config = array(
	'app_key'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //腾讯API应用的KEY值
	'app_secret'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //腾讯API应用的密码值
);

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
	if(ACTION_NAME=='install')
	{
		//更新字段
		$GLOBALS['db']->query("ALTER TABLE `".DB_PREFIX."user`  ADD COLUMN `qq_id`  varchar(255) NOT NULL",'SILENT');
		$GLOBALS['db']->query("ALTER TABLE `".DB_PREFIX."user`  ADD COLUMN `qq_token`  varchar(255) NOT NULL",'SILENT');
	}
    $module['class_name']    = 'Qq';

    /* 名称 */
    $module['name']    = $api_lang['name'];
    
    $module['dispname']    = "QQ登录";

	$module['config'] = $config;
	
	$module['lang'] = $api_lang;
    
    return $module;
}

// QQ的api登录接口
require_once(APP_ROOT_PATH.'system/libs/api_login.php');
class Qq_api implements api_login {
	
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
		es_session::start();
		$inc=array();
		$callback = SITE_DOMAIN.APP_ROOT."/qq_callback.php";
		$scope="get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr";
		$inc['appid']=$this->api['config']['app_key'];
		$inc['appkey']=$this->api['config']['app_secret'];
		$inc['callback']=$callback;
		$inc['scope']=$scope;
		$inc['errorReport']=1;
		$inc['storageType']="file";
		$inc['host']=SITE_DOMAIN;
		$setting = json_encode($inc);
		if (file_exists(APP_ROOT_PATH."/public/qqv2_inc.php")) {
			$result=unlink (APP_ROOT_PATH."/public/qqv2_inc.php");/*防止多个域名时,只有一个域名可以登录140217*/
		  }
		@file_put_contents(APP_ROOT_PATH."/public/qqv2_inc.php",$setting);
		@chmod(APP_ROOT_PATH."/public/qqv2_inc.php",0777);
		$url = SITE_DOMAIN.APP_ROOT."/system/api_login/qqv2/qq_login.php";	
		$str = "<a href='".$url."' title='".$this->api['name']."'><img src='".$this->api['icon']."' alt='".$this->api['name']."' /></a>&nbsp;";
		return $str;
	}

    public function get_big_api_url()
	{
		es_session::start();
		$inc=array();
		$callback = SITE_DOMAIN.APP_ROOT."/qq_callback.php";
		$scope="get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr";
		$inc['appid']=$this->api['config']['app_key'];
		$inc['appkey']=$this->api['config']['app_secret'];
		$inc['callback']=$callback;
		$inc['scope']=$scope;
		$inc['errorReport']=1;
		$inc['storageType']="file";
		$inc['host']=SITE_DOMAIN;
		$setting = json_encode($inc);
		if (file_exists(APP_ROOT_PATH."/public/qqv2_inc.php")) {
			$result=unlink (APP_ROOT_PATH."/public/qqv2_inc.php");/*防止多个域名时,只有一个域名可以登录140217*/
		  }
		@file_put_contents(APP_ROOT_PATH."/public/qqv2_inc.php",$setting);
		@chmod(APP_ROOT_PATH."/public/qqv2_inc.php",0777);
		$url = SITE_DOMAIN.APP_ROOT."/system/api_login/qqv2/qq_login.php";	
		$str = "<a href='".$url."' title='".$this->api['name']."'><img src='".$this->api['bicon']."' alt='".$this->api['name']."' /></a>&nbsp;";
		return $str;
	}
	/**
	 * 返回腾讯绑定数组信息
	 * @return array("class","name","bicon",url);
	 */
	public function get_bind_api_url()
	{
	    es_session::start();
		$inc=array();
		
		$callback = SITE_DOMAIN.APP_ROOT."/qq_callback.php";
		$scope="get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr";
		$inc['appid']=$this->api['config']['app_key'];
		$inc['appkey']=$this->api['config']['app_secret'];
		$inc['callback']=$callback;
		$inc['scope']=$scope;
		$inc['errorReport']=1;
		$inc['storageType']="file";
		$inc['host']=SITE_DOMAIN;
		$setting = json_encode($inc);
		@file_put_contents(APP_ROOT_PATH."/public/qqv2_inc.php",$setting);
		@chmod(APP_ROOT_PATH."/public/qqv2_inc.php",0777);
		$aurl = SITE_DOMAIN.APP_ROOT."/system/api_login/qqv2/qq_login.php";	
	    es_session::set("is_bind",1);
	
	    return $aurl;
	}
		
	public function callback()
	{
	    
	    es_session::start();
	    require_once(APP_ROOT_PATH."system/api_login/qqv2/qqConnectAPI.php");
		$qc = new QC();
		$access_token =$qc->qq_callback();
		$openid = $qc->get_openid();
		$use_info_keysArr = array(
            "access_token" => $access_token,
			"openid" => $openid,
		 	"oauth_consumer_key" => $this->api['config']['app_key']
        );
		$use_info_url="https://graph.qq.com/user/get_user_info";
        $graph_use_info_url = $qc->urlUtils->combineURL($use_info_url, $use_info_keysArr);
        $response = $qc->urlUtils->get_contents($graph_use_info_url);
		
        if($response['ret']!=0){
            showErr("授权失败,错误信息：".$response['msg']);
            die();
        }
        $msg = json_decode($response,1);
        //file_put_contents(APP_ROOT_PATH."/public/qqv2_user_info.php",print_r($msg,1));
		//name,province,city,avatar,token,field,token_field(授权的字段),sex
		$api_data['id'] = $openid;
		$api_data['field'] = 'qq_id';
		$api_data['token'] = $access_token;
		$api_data['token_field'] = "qq_token";
		$api_data['name'] = $msg['nickname'];
		$api_data['province'] = $msg['province'];
		$api_data['city'] = $msg['city'];
		$api_data['avatar'] = $msg['figureurl_2'];//100*100
		if($msg['gender']=='女')
		$api_data['sex'] = 0;
		else if($msg['gender']=='男')
		$api_data['sex'] = 1;
		else 
		$api_data['sex'] = -1;

		if($api_data['id']!="")
			es_session::set("api_user_info",$api_data);

		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where qq_id = '".$openid."' and qq_id <> '' and is_effect=1");
		if($user_data)
		{
			   es_session::delete("api_user_info");		
				$GLOBALS['db']->query("update ".DB_PREFIX."user set qq_token = '".$api_data['token']."',login_ip = '".get_client_ip()."',login_time= ".get_gmtime()." where id =".$user_data['id']);								
				es_session::set("user_info",$user_data);
				app_redirect_preview();
		}
		else{
			if($GLOBALS['user_info'])
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set qq_id = '".$api_data['id']."',qq_token = '".$api_data['token']."' where id =".intval($GLOBALS['user_info']['id']));								
				app_redirect(url("settings#bind"));
			}
			else
			app_redirect(url("user#api_register"));
		}
		
	}
	
	public function get_title()
	{
		return 'QQv2登录接口，需要php_curl扩展的支持';
	}
		
	//解除API 绑定
	public function unset_api(){
	    if($GLOBALS['user_info']){
	       $GLOBALS['db']->query("update ".DB_PREFIX."user set qq_id= '', qq_token ='' where id =".$GLOBALS['user_info']['id']);
	    }
	}    
	
	//同步微博信息
	public function send_message($data){
	    
	}
}
?>