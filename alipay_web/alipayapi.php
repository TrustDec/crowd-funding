<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付宝即时到账交易接口接口</title>
</head>
<body>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");

$order_id = intval($_REQUEST['order_id']);
$payment_notice_sn = trim($_REQUEST['out_trade_no']);
if ($order_id == 0){
 	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
	$order_id = intval($payment_notice['order_id']);
}else{
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where order_id = ".$order_id);
}

 if (empty($payment_notice)){
	echo "订单不存在";
	exit();
}

if ($payment_notice['is_paid'] == 1){
	echo "订单已经收款";
	exit();
}

$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
$pay_code = strtolower($payment_info['class_name']);

if($pay_code !="walipay"){
	echo "不支持的支付方式";
	exit();
}

/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
	
//返回格式
$format = "xml";
//必填，不需要修改

//返回格式
$v = "2.0";
//必填，不需要修改

//请求号
$req_id = date('Ymdhis');
//必填，须保证每次请求都是唯一

//**req_data详细信息**

//服务器异步通知页面路径
$notify_url = get_domain().APP_ROOT."/notify_url.php";
//需http://格式的完整路径，不允许加?id=123这类自定义参数

//页面跳转同步通知页面路径
$call_back_url = get_domain().APP_ROOT."/call_back_url.php";

//需http://格式的完整路径，不允许加?id=123这类自定义参数

//卖家支付宝帐户

$seller_email = $alipay_config['account'];
//必填



//商户网站订单系统中唯一订单号，必填


//if($payment_info&&$pay_price>0)
//{
//	require_once APP_ROOT_PATH."system/libs/cart.php";
//	$payment_notice_id = make_payment_notice($pay_price,$order_id,$payment_info['id']);
//	//创建支付接口的付款单
//}

//创建了支付单号，通过支付接口创建支付数据
require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
$payment_class = $payment_info['class_name']."_payment";
$payment_object = new $payment_class();
$pay = $payment_object->get_payment_code($payment_notice['id']);

 //订单名称
$subject = $pay['subject']?$pay['subject']:'在线支付';

//必填

//付款金额
$pay_price =$pay['total_fee'];
//商户订单号
$out_trade_no = $pay['out_trade_no'];

$total_fee = $pay['total_fee'];

//必填
//请求业务参数详细
$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee></direct_trade_create_req>';
//必填

/************************************************************/

//构造要请求的参数数组，无需改动
$para_token = array(
		"service" => "alipay.wap.trade.create.direct",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

 
//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestHttp($para_token);


//URLDECODE返回的信息
$html_text = urldecode($html_text);

//解析远程模拟提交后返回的信息
$para_html_text = $alipaySubmit->parseResponse($html_text);


//获取request_token
$request_token = $para_html_text['request_token'];


/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

//业务详细
$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
//必填

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "alipay.wap.auth.authAndExecute",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//print_r($parameter); exit;

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '页面跳转中，如果未跳转点此');
echo $html_text;
?>
</body>
</html>