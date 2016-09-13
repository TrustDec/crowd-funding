<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>京东在线交易</title>
<style type="text/css">
body table tr td {
	font-size: 14px;
	word-wrap: break-word;
	word-break: break-all;
	empty-cells: show;
}
</style>
</head>
<body>
<?php
require_once '../system/system_init.php';

$param=$_REQUEST;

if(isset($param['token'])){
	$payment_notice_sn = $param['tradeNum'];
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");	
	$token= $param['token'];
	if($token !=''&&strlen($token)==32){			
		require_once APP_ROOT_PATH."system/libs/cart.php";
		$rs = payment_paid($payment_notice_sn,$outer_notice_sn);

		if ($rs)
		{
			$user_info = $GLOBALS['db']->getRow("select wjdpay_token from ".DB_PREFIX."user where id = ".$payment_notice['user_id']."'");
			if($user_info['wjdpay_token'] ==''){
				$GLOBALS['db']->query("update ".DB_PREFIX."user set wjdpay_token = '".$token."'  where id = ".$payment_notice['user_id']);	
			}	
			$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
			app_redirect($url);
		}
		else
		{
			echo "支付失败<br />请点左上角<b>返回</b>按钮";
		}
	}else{
			echo "支付失败<br />请点左上角<b>返回</b>按钮";
	}   
	
}
?>	
</body>
</html>















