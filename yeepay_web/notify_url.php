<?php

require_once("alipay.config.php");
require_once("core.php");

/*
method	方法名	此为固定值mobilepay	不可空
apporderid	订单号		不可空
amount	交易金额	以"分"为单位的整型，必须大于等于2分	不可空
identityid	用户标识	用户ID	不可空
productname	商品名称	最长50位，出于风控考虑，请按下面的格式传递值：应用-商品名称，如“诛仙-3阶成品天琊”，此商品名在发送短信校验的时候会发给用户，所以描述内容不要加在此参数中，以提高用户的体验度	不可空
productdesc	商品描述	最长200位	可空
appcallbackurl	第3方平台异步通知地址		可空
appkey	应用id	Sdk后台申请	不可空
sign	本系统签名		不可空
*/


$key = "jz";//干扰码：jz

$notice_sn = $_REQUEST['apporderid'];
$trade_no = $_REQUEST['order_id'];

$sign = $_REQUEST['sign'];

//除去待签名参数数组中的空值和签名参数
$para_filter = paraFilter($_REQUEST);		
//对待签名参数数组排序
$para_sort = argSort($para_filter);

//生成签名结果
$sign_str = createLinkstring($para_sort);
$mysign = md5($sign_str.$key);

/*
$sign_str = $amount.$apporderid.$order_id;
$mysign = md5($sing_str.$key);
*/



if ($sign == $mysign){
	  $apporderid = $_REQUEST['apporderid'];
	  $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$notice_sn."'");
	  require_once APP_ROOT_PATH."system/libs/cart.php";
	  $rs = payment_paid($notice_sn,$trade_no);	
	   if ($rs)
	   {
 		   	$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);				
 	   } 
		$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
		app_redirect($url);
}else{
	//验证失败
	$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result";
	app_redirect($url);
}

?>