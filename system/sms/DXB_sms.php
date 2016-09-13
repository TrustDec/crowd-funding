<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://api.smsbao.com/';
	
    $module['class_name']    = 'DXB';
    /* 名称 */
    $module['name']    = "短信宝平台";
  /*
    if(ACTION_NAME == "install" || ACTION_NAME == "edit"){
	    require_once APP_ROOT_PATH."system/sms/FW/transport.php";
		$tran = new transport();
		$install_info = $tran->request($module['server_url']."data/install.php");
		$install_info = json_decode($install_info['body'],1);
		
	    $module['lang']  = $install_info['lang'];
	    $module['config'] = $install_info['config'];	
    }
  */
    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
  require_once APP_ROOT_PATH."system/sms/FW/transport.php";
class DXB_sms implements sms
{
	public $sms;
	public $message = "";
	
    public function __construct($smsInfo = '')
    { 	    	
		if(!empty($smsInfo))
		{


			$this->sms = $smsInfo;
		}
    }
	
	public function sendSMS($mobile_number,$content)
	{







		if(is_array($mobile_number)){
			$mobile_number=$mobile_number[0];
		}



		$content.='【酒店众筹网】';
 		$account = $this->sms['user_name'];
		$password =$this->sms['password'];



		$statusStr = array(
			"0" => "短信发送成功",
			"-1" => "参数不全",
			"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
			"30" => "密码错误",
			"40" => "账号不存在",
			"41" => "余额不足",
			"42" => "帐户已过期",
			"43" => "IP地址限制",
			"50" => "内容含有敏感词"
		);
		$smsapi = "http://api.smsbao.com/"; //短信网关
		$user = $account ; //短信平台帐号
		$pass = md5($password); //短信平台密码
 		$phone = $mobile_number;//要发送短信的手机号码
		$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
		$r =file_get_contents($sendurl) ;

		$result=array();
		if($r=='0'){
			$result['status']=true;
		}else{
			$result['status']=false;
		}
		$result['msg'] = $statusStr[$r];
 		return $result;
	}
	
	public function getSmsInfo()
	{	
		return "短信宝平台";
	}
	
	public function check_fee()
	{

		$account = $this->sms['user_name'];
		$password =$this->sms['password'];
		$statusStr = array(
			"0" => "短信发送成功",
			"-1" => "参数不全",
			"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
			"30" => "密码错误",
			"40" => "账号不存在",
			"41" => "余额不足",
			"42" => "帐户已过期",
			"43" => "IP地址限制",
			"50" => "内容含有敏感词"
		);
		$smsapi = "http://www.smsbao.com/"; //短信网关
		$user = $account ; //短信平台帐号
		$pass = md5($password); //短信平台密码
 		$sendurl = $smsapi."query?u=".$user."&p=".$pass;
		$result =file_get_contents($sendurl) ;
		$remain=explode(',',$result);
		 return  '剩下'.$remain[1].'条短信';

	}
}
?>