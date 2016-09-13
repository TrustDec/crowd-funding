<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'丰付支付(WAP版本)',
	'merchant_code'	=>	'商户编号',
	'merchant_key'		=>	'商户秘钥',
	'biz_type'		=>	'业务类型',
	'biz_type_code'		=>	'业务类型代码',
	'GO_TO_PAY'	=>	'前往丰付快捷支付'
);
$config = array(
	'merchant_code'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户编号
	'merchant_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户秘钥 
	'biz_type'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //业务类型
	'biz_type_code'	=>	array(
		'INPUT_TYPE'	=>	'0'
	) //业务类型代码
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Wsumapay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '2';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    //$module['reg_url'] = 'https://b.alipay.com/order/slaverIndex.htm?customer_external_id=C4393319516691172112&market_type=from_agent_contract&pro_codes=61F99645EC0DC4380ADE569DD132AD7A';
    $module['reg_url'] = '';
    return $module;
}

// 丰付快捷支付(WAP版本)模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Wsumapay_payment implements payment {
	//发送数据，创建订单
	public function get_payment_code($payment_notice_id)
	{
		define('REAL_APP_ROOT',str_replace('/mapi',"",APP_ROOT));
		$pay=array();
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$money = round($payment_notice['money'],2);
		
		
		$pay['body'] = $payment_notice['notice_sn'];
		$pay['total_fee'] = $money;
		$pay['total_fee_format'] = format_price($money);
		$pay['pay_code'] = 'wsumapay';
		$pay['is_wap']=1;
		$pay['notify_url']=get_domain().REAL_APP_ROOT."/wsumapay_web/sumapayapi.php?payment_notice_id=".intval($payment_notice_id)."&from=".strim($_REQUEST['from']);
		return $pay;
	}
	public function response($request)
	{
				
	}
	
	public function notify($request)
	{
		
	}
	function get_display_code(){
		
	}
}
?>