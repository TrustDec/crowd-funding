<?php
   /**
	后台通知
   */	
   	  	require '../system/system_init.php';

	  	$request=$_REQUEST;
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
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
            echo "fail";
        }
		
        $payment_notice_sn = $request['orderId'];
        
    	$money = $request['amount'];
		$outer_notice_sn = $request['traceId'];
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			
		if ($request['result'] == 'S'){

			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice_sn,$outer_notice_sn);						
			echo  "success";							
		}else{
		   echo "fail";
		}   
?>
