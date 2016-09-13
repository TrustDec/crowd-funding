<?php
/**
前台通知
*/
require_once('./config_wap.php');//银联wap配置文件
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>银联在线交易</title>
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
	   $param=$_REQUEST;
	   $from=$param['from'];
	   unset($param['from']);
       if(isset ($param['signature']) && verify ($param))
       {
	       	$outer_notice_sn = $param['queryId'];
	        $payment_notice_sn = $param['orderId'];
	    	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			
			if ($param['respCode'] == '00'){
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
       }else
       {
       		
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