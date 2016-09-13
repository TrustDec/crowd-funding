<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://sz.ipyy.com/';
	
    $module['class_name']    = 'CSHX';
    /* 名称 */
    $module['name']    = "创世华信短信平台";
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
class CSHX_sms implements sms
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

		$userid='';
		$account=$this->sms['user_name'];
		$password=$this->sms['password'];
		$content.='【酒店众筹网】';
	    $gateway = "http://sz.ipyy.com/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile_number}&content={$content}&sendTime=";

	 	$r= file_get_contents($gateway);
		$xml=simplexml_load_string($r);
		if($xml->returnstatus =='Success'){
			$result['status']=true;
		}else{
			$result['status']=false;
		}

		$result['msg'] = $xml->message;
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "创世华信短信平台";
		
	}
	
	public function check_fee()
	{


		$userid='';
		$account=$this->sms['user_name'];
		$password=$this->sms['password'];

		$gateway = "http://sz.ipyy.com/sms.aspx?action=overage&userid={$userid}&account={$account}&password={$password}";

		$r= file_get_contents($gateway);

		$xml=simplexml_load_string($r);


		if(trim($xml->returnstatus)=='Sucess'){
			$str=' 当前余额:'.$xml->overage.'元,已发送短信条数'.$xml->sendTotal.'条';
		}else{
			$str=$xml->message;
		}


		return $str;
	}
}
?>