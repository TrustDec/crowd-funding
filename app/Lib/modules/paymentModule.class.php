<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class paymentModule extends BaseModule
{	
	
	public function response()
	{
                 
		//支付跳转返回页
		if($GLOBALS['pay_req']['class_name'])
			$_REQUEST['class_name'] = $GLOBALS['pay_req']['class_name'];
		$class_name = quotes(trim($_REQUEST['class_name']));
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			$_REQUEST = quotes($_REQUEST);
			$payment_code = $payment_object->response($_REQUEST);
			
		}
		else
		{
			showErr("支付接口不存在");
		}
	}
	
	public function notify()
	{
		
		//支付跳转返回页
		if($GLOBALS['pay_req']['class_name'])
			$_REQUEST['class_name'] = $GLOBALS['pay_req']['class_name'];
			
		$class_name = quotes(trim($_REQUEST['class_name']));
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			$_REQUEST = quotes($_REQUEST);
			$payment_code = $payment_object->notify($_REQUEST);
			//get_mortgate();
		}
	}
	// 微信支付
	public function wx_payment()
	{
		$GLOBALS['tmpl']->display("wx_payment.html");
	}
}
?>