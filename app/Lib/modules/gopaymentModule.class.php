<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class gopaymentModule extends BaseModule
{	
	//京东支付
	public function jdpay()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
	
		$payment_notice_id=intval($_REQUEST['payment_notice_id']);
		$notice_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id." and is_paid = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".intval($notice_info['payment_id']));

		if($payment_info['class_name']=='Jdpay')
		{
			$class_name = $payment_info['class_name']."_payment";
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			
			$pay_data=$o->get_payment_code($payment_notice_id);

			header(("location:".$pay_data['url']));
		}else
		{
			showErr("支付出错",0,url("account#view_order",array("id"=>$notice_info['order_id'])));
		}
	}
	
}
?>