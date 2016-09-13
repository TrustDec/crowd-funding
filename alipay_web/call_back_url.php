<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */


//file_put_contents("./alipaylog/call_back_url_1_".strftime("%Y%m%d%H%M%S",time()).".txt",print_r($_GET,true));

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
        <title>支付宝即时到账交易接口</title>
	</head>
<body>
<?php

//echo APP_ROOT_PATH."/alipaylog/call_back_url".time().".txt";
//file_put_contents("./alipaylog/call_back_url_2_".strftime("%Y%m%d%H%M%S",time()).".txt",print_r($_GET,true).print_r($alipay_config,true));

//计算得出通知验证结果

$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$result = $_GET['result'];


	//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
	

   $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");
   //file_put_contents(APP_ROOT_PATH."/alipaylog/payment_notice_sn_3.txt",$payment_notice_sn);
   
   require_once APP_ROOT_PATH."system/libs/cart.php";
   $rs = payment_paid($out_trade_no,$trade_no);		
    if ($rs)
   {
   		//file_put_contents(APP_ROOT_PATH."/alipaylog/1.txt","");
	   	$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);				
   	 	//echo "支付成功<br />请点左上角<b>返回</b>按钮";
		//app_redirect(APP_ROOT."/wap/index.php?ctl=pay_order&act=index&order_id=".$payment_notice['order_id']);
   }else{
   		//file_put_contents(APP_ROOT_PATH."/alipaylog/2.txt","");
   	 	//echo "支付成功<br />请点左上角<b>返回</b>按钮";
		//app_redirect(APP_ROOT."/wap/index.php?ctl=pay_order&act=index&order_id=".$payment_notice['order_id']);
   }
	$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
	app_redirect($url);
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
     //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    //echo "验证失败<br />请点左上角<b>返回</b>按钮";
	$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
	app_redirect($url);
}
?>
    </body>
</html>