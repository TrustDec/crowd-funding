<?php
// +----------------------------------------------------------------------
// |美食街
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.meishijie.cn All rights reserved.
// +----------------------------------------------------------------------

$sms_lang = array(
);
$config = array(
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'smsbao';
    /* 名称 */
    $module['name']    = "短信宝 (<a href='http://www.smsbao.com/reg?r=10021' target='_blank'><font color='red'>还没账号？点击这免费注册</font></a>)";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://www.smsbao.com/sms';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/smsbao/transport.php"; 

class smsbao_sms implements sms
{
	public $sms;
	public $message = "";
	
	private $statusStr = array(
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
	
  public function __construct($smsInfo = '')
    { 	    	
		if(!empty($smsInfo))
		{			
			$this->sms = $smsInfo;
		}
    }
	
	public function sendSMS($mobile_number,$content)
	{
		if(is_array($mobile_number))
		{
			$mobile_number = implode(",",$mobile_number);
		}
		$sms = new transport();
				
				$params = array(
					"u"=>$this->sms['user_name'],
					"p"=>md5($this->sms['password']),
					"m"=>$mobile_number,
					"c"=>urlencode($content)
				);
				
				$result = $sms->request($this->sms['server_url'],$params);
				$code = $result['body'];
				
				if($code=='0')
				{
							$result['status'] = 1;
				}
				else
				{
							$result['status'] = 0;
							$result['msg'] = $this->statusStr[$code];
				}
		return $result;
	}
	
	public function getSmsInfo()
	{		
			return "短信宝";
	}
	
	public function check_fee()
	{
		es_session::start();
		$last_visit = intval(es_session::get("last_visit_smsbao"));
		if(get_gmtime() - $last_visit > 10)
		{
			$sms = new transport();
				
			$params = array(
						"u"=>$this->sms['user_name'],
						"p"=>md5($this->sms['password'])
					);
					
			$url = "http://www.smsbao.com/query";
			$result = $sms->request($url,$params);
	
			$match = explode(',',$result['body']);
    	if ($match[0] != '')
    	{
    			$remain = (int)$match[1];
    			$str = sprintf('短信宝 剩余：%d 条', $remain);
    	}
    	else
    	{
    			$str = "短信宝 (<a href='http://www.smsbao.com/reg?r=10021' target='_blank'><font color='red'>还没账号？点击这免费注册</font></a>)";
    	}
		
			es_session::set("smsbao_info",$str);
			es_session::set("last_visit_smsbao",get_gmtime());
			return $str;
		}
		else
		{
			$qxt_info = es_session::get("smsbao_info");
			if($smsbao_info)
			return $smsbao_info;
			else
			return "短信宝 (<a href='http://www.smsbao.com/reg?r=10021' target='_blank'><font color='red'>还没账号？点击这免费注册</font></a>)";
		}

	}
}
?>