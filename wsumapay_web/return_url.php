<?php
/**
失败失败跳转通知页面
*/
require_once('./func/common.php');//
require '../system/system_init.php';
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>丰付快捷支付</title>
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
	   	$request=$_REQUEST;
	   	$from=$request['from'];
	   	$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Wsumapay'");  
    	$payment['config'] = unserialize($payment['config']);
		$requestId=$request['requestId'];
		$result=$request['result'];
		$payId=$request['payId'];
		$fiscalDate=$request['fiscalDate'];
		$status=$request['status'];
		$passThrough=$request['passThrough'];
		$signature='';
		$signature.=$requestId;
		$signature.=$result;
		$signature.=$payId;
		$signature.=$fiscalDate;
		$signature.=$status;
		$signature.=$passThrough;
        $signature=HmacMd5($signature,trim($payment['config']['merchant_key']));
        if ($signature == $request['signature']){
        	$payment_notice_sn=$requestId;
		   	$outer_notice_sn=$payId;
		   	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
		   	if($request['status']==2){
		   		require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
				if($from=='wap')
				{
					$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
					app_redirect($url);
				}
				else
				{
					echo "支付成功<br />请点左上角<b>返回</b>按钮";
				}
		   	}else{
		   		if($from=='wap')
				{
					 $url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
			   		 app_redirect($url);
				}
				else
				{
					echo "支付失败<br />请点左上角<b>返回</b>按钮";
				}
		   	}
        }else{
        	if($from=='wap')
			{
				$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
				app_redirect($url);
			}
			else
			{
				echo "签名失败<br />请点左上角<b>返回</b>按钮";
			}
        }
	?>
</body>
</html>