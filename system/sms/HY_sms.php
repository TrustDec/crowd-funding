<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://www.duanxin10086.com/';
	
    $module['class_name']    = 'HY';
    /* 名称 */
    $module['name']    = "海岩短信平台";
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
class HY_sms implements sms
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
		$post_data = array();
		$post_data['userid'] = 10665;
		$post_data['account'] = $this->sms['user_name'];
		$post_data['password'] =$this->sms['password'];
		$post_data['content'] = $content;
		$post_data['mobile'] = $mobile_number;
		$post_data['sendtime'] = ''; //

		$url='http://www.duanxin10086.com/sms.aspx?action=send';
		$o='';
		foreach ($post_data as $k=>$v)
		{
 			$o.="$k=".$v.'&';
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$str = curl_exec($ch);

		$xml=simplexml_load_string($str);

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
		return "海岩短信平台";
	}
	
	public function check_fee()
	{
 	  		$post_data = array();
		$post_data['userid'] = 10665;
		$post_data['account'] = $this->sms['user_name'];
		$post_data['password'] =$this->sms['password'];
		$url='http://www.duanxin10086.com/sms.aspx?action=overage';
		$o='';


		foreach ($post_data as $k=>$v)
		{
			$o.="$k=".$v.'&';
		}
		$post_data=substr($o,0,-1);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$r= curl_exec($ch);

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