<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class sms_sender
{
	var $sms;
	
	public function __construct()
    { 	
		$sms_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms where is_effect = 1");
		if($sms_info)
		{
			$sms_info['config'] = unserialize($sms_info['config']);
			
			require_once APP_ROOT_PATH."system/sms/".$sms_info['class_name']."_sms.php";
			
			$sms_class = $sms_info['class_name']."_sms";
			$this->sms = new $sms_class($sms_info);
		}
    }
    
	
	public function sendSms($mobiles,$content,$sendTime='',$is_adv=0)
	{
		if(app_conf("SMS_ON")==0)
		{
			return array(
					"status"	=>	0,
					"msg"	=>	'网站未开启短信功能',
					);
		}
		if(!is_array($mobiles))
			$mobiles = preg_split("/[ ,]/i",$mobiles);
		
		if(count($mobiles) > 0 )
		{
			if(!$this->sms)
			{
				$result['status'] = 0;
			}
			else
			{
				$result = $this->sms->sendSms($mobiles,$content,$sendTime,$is_adv);
			}
		}
		else
		{
			$result['status'] = 0;
			$result['msg'] = "没有发送的手机号";
		}
		
		return $result;
	}
}
?>