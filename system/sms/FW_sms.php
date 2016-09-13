<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://sms.fanwe.com/';
	
    $module['class_name']    = 'FW';
    /* 名称 */
    $module['name']    = "方维短信平台";
  
    if(ACTION_NAME == "install" || ACTION_NAME == "edit"){  
	    require_once APP_ROOT_PATH."system/sms/FW/transport.php";
		$tran = new transport();
		$install_info = $tran->request($module['server_url']."data/install.php");
		$install_info = json_decode($install_info['body'],1);
		
	    $module['lang']  = $install_info['lang'];
	    $module['config'] = $install_info['config'];	
    }
    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
 require_once APP_ROOT_PATH."system/sms/FW/transport.php";
class FW_sms implements sms
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
		$sms = new transport();
		if(is_array($mobile_number)){
			$mobile_number = implode(",",$mobile_number);
		}
		
		$this->sms['mobile'] = $mobile_number;
		$this->sms['content'] = urlencode($content);
		
		$params = json_encode($this->sms);
		
		$result_info = $sms->request($this->sms['server_url']."post",$params);
		
		$return = json_decode($result_info['body'],1);
		
		$result['status'] = $return['status'];
		$result['msg'] = $return['msg'];
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "方维短信平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
					
		$url = $this->sms['server_url']."get";
		
		$params = json_encode($this->sms);
		
		$result = $sms->request($url,$params);
		$result = json_decode($result['body'],1);
		
		$str = $result['info'];	

		return $str;

	}
}
?>