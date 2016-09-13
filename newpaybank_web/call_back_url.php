<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>牛付银行支付交易</title>
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

		require '../system/system_init.php';
	   	$request=$_REQUEST;

		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Wnewpay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
        /* 检查数字签名是否正确 */
        //ksort($request);
       	//reset($request);

        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' &&  $key != 'code' && $key!='class_name' && $key!='act' && $key!='ctl' && $key!='md5' && $key!='bankJournal' && $key!='bankOrderId')
            {
              $sign .= "$key=$val&";
            }
        }
        $sign  = substr($sign, 0, -1)."&key=". $payment['config']['partner_key'];

		if (md5($sign) != $request['md5'])
        {
            echo "md5验证失败";
        }
        $payment_notice_sn = $request['orderId'];
        
    	$money = $request['amount'];
		
    	$outer_notice_sn = $request['traceId'];
    	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
		
		if ($request['result'] == 'S'){
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
									
			
			$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
			app_redirect($url);
			
		}else{
		   
			 $url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
	   		 app_redirect($url);
			
		}   
       
	?>
</body>
</html>