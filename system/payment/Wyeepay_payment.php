<?php
// +----------------------------------------------------------------------
// | EaseTHINK 易想团购系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.easethink.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'易宝支付(WAP版本)',
	'merchantaccount'	=>	'商户编号',
	'productcatalog'=>'商品类别码',
	'merchantPrivateKey'	=>	'商户私钥',
	'merchantPublicKey'	=>	'商户公钥',
	'yeepayPublicKey'	=>	'易宝公钥',
);
$config = array(
	'productcatalog'=>array(
		'INPUT_TYPE'=>'0',
	),
	'merchantaccount'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户编号
	'merchantPrivateKey'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户私钥 
	'merchantPublicKey'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户公钥
	'yeepayPublicKey'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //易宝公钥 
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Wyeepay';

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
class Wyeepay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		include(APP_ROOT_PATH."system/payment/yeepay/yeepayMPay.php");
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
 		
 		$yeepay = new yeepayMPay($payment_info['config']['merchantaccount'],$payment_info['config']['merchantPublicKey'],$payment_info['config']['merchantPrivateKey'],$payment_info['config']['yeepayPublicKey']);
 		//$notify_url = get_domain().APP_ROOT."/yeepay_web/alipayapi.php?order_id=".intval($payment_notice['order_id'])."&out_trade_no=".$order_sn;//."&out_trade_no={$data.walipay.out_trade_no}";
		$data_return_url = get_domain().APP_ROOT.'/yeepay_web/yjwap_response.php';
		$data_notify_url = get_domain().APP_ROOT.'/yeepay_web/yjwap_notify.php';
		
		$data_notify_url = str_replace("/sjmapi", "", $data_notify_url);
		$data_notify_url = str_replace("/mapi", "", $data_notify_url);
		$data_notify_url = str_replace("/wap", "", $data_notify_url);
		
		
		$data_return_url = str_replace("/sjmapi", "", $data_return_url);
		$data_return_url = str_replace("/mapi", "", $data_return_url);
		$data_return_url = str_replace("/wap", "", $data_return_url);
		
				
		$order_id = $order_sn;//网页支付的订单在订单有效期内可以进行多次支付请求，但是需要注意的是每次请求的业务参数都要一致，交易时间也要保持一致。否则会报错“订单与已存在的订单信息不符”
		$transtime = NOW_TIME;// time();//交易时间，是每次支付请求的时间，注意此参数在进行多次支付的时候要保持一致。
		$product_catalog =$payment_info['config']['productcatalog'];//商品类编码是我们业管根据商户业务本身的特性进行配置的业务参数。
		$identity_id = $payment_notice['user_id'];//用户身份标识，是生成绑卡关系的因素之一，在正式环境此值不能固定为一个，要一个用户有唯一对应一个用户标识，以防出现盗刷的风险且一个支付身份标识只能绑定5张银行卡
		$identity_type = 2;     //支付身份标识类型码
		require_once APP_ROOT_PATH.'system/extend/ip.php';
		$iplocation = new iplocate();
		$user_ip = $iplocation->getIP(); //此参数不是固定的商户服务器ＩＰ，而是用户每次支付时使用的网络终端IP，否则的话会有不友好提示：“检测到您的IP地址发生变化，请注意支付安全”。
		$user_ua =  $_SERVER['HTTP_USER_AGENT'];//'NokiaN70/3.0544.5.1 Series60/2.8 Profile/MIDP-2.0 Configuration/CLDC-1.1';//用户ua
		$callbackurl = $data_notify_url;//商户后台系统回调地址，前后台的回调结果一样
		$fcallbackurl = $data_return_url;//商户前台系统回调地址，前后台的回调结果一样
		$product_name = '订单号-'.$title_name;//出于风控考虑，请按下面的格式传递值：应用-商品名称，如“诛仙-3 阶成品天琊”
		$product_desc = '';//商品描述
		$terminaltype = 3;
		$terminalid = '';//其他支付身份信息
		$amount = $money * 100;//订单金额单位为分，支付时最低金额为2分，因为测试和生产环境的商户都有手续费（如2%），易宝支付收取手续费如果不满1分钱将按照1分钱收取。


		$url = $yeepay->webPay($order_id,$transtime,$amount,$product_catalog,$identity_id,$identity_type,$user_ip,$user_ua,$callbackurl,$fcallbackurl,$currency=156,$product_name,$product_desc,$terminaltype,$terminalid,$orderexp_date=60);
		 
 		
		$pay = array();
		$pay['subject'] = $subject;
		$pay['body'] = $title_name;
		$pay['total_fee'] = $money;
		$pay['total_fee_format'] = format_price($money);
		$pay['out_trade_no'] = $payment_notice['notice_sn'];
		$pay['notify_url'] = $url;
 		$pay['is_wap'] = 1;
		$pay['pay_code'] = 'wyeepay';//,支付宝;mtenpay,财付通;mcod,货到付款
				
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