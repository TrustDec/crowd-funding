<?php
// +----------------------------------------------------------------------
// | EaseTHINK 易想团购系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.easethink.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'支付宝手机支付(WAP版本)',
	'alipay_partner'	=>	'合作者身份ID',
	'alipay_account'	=>	'支付宝帐号',
	'alipay_key'	=>	'安全校验码（Key）',
);
$config = array(
	'alipay_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //合作者身份ID
	'alipay_account'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝帐号: 
	//支付宝公钥
	'alipay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Walipay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付;2:手机wap;3:手机sdk */
    $module['online_pay'] = '2';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 支付宝手机支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Walipay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		define('REAL_APP_ROOT',str_replace('/mapi',"",APP_ROOT));
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $payment_notice['notice_sn'];
 		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
 		$sql = "select name ".
						  "from ".DB_PREFIX."deal ".					
						  "where id =". intval($payment_notice['deal_id']);
 		$title_name =$GLOBALS['db']->getOne($sql);
		
		$subject = $order_sn;
		//$data_return_url = get_domain().APP_ROOT.'/../shop.php?ctl=payment&act=response&class_name=Walipay';
		//$notify_url = get_domain().APP_ROOT.'/../shop.php?ctl=payment&act=notify&class_name=Walipay';
		
		$notify_url = get_domain().REAL_APP_ROOT."/alipay_web/alipayapi.php?order_id=".intval($payment_notice['order_id'])."&out_trade_no=".$order_sn;//."&out_trade_no={$data.walipay.out_trade_no}";
		
		$pay = array();
		$pay['subject'] = $title_name;
		$pay['body'] = $subject;
		$pay['total_fee'] = $money;
		$pay['total_fee_format'] = format_price($money);
		$pay['out_trade_no'] = $payment_notice['notice_sn'];
		$pay['notify_url'] = $notify_url;
		
		$pay['partner'] = $payment_info['config']['alipay_partner'];//合作商户ID
		$pay['seller'] = $payment_info['config']['alipay_account'];//账户ID
				
		$pay['key'] = $payment_info['config']['alipay_key'];//支付宝(RSA)公钥
		
		$pay['is_wap'] = 1;
		$pay['pay_code'] = 'walipay';//,支付宝;mtenpay,财付通;mcod,货到付款
 		return $pay;
	}
	
	public function response($request)
	{	
							
	}
	
	public function notify($request){
		
	}
	
	public function get_display_code(){
		return "";
	}
}
?>