<?php
   /**
	后台通知
   */	
   require_once('./config_wap.php');//银联wap配置文件

	  $log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
	  $log->LogInfo ( "进入后台通知");
	
	   $param=$_REQUEST;
	   if (!isset ($param['signature']))
	    	return 0;
	    	
	   if(!verify ($param))
	    	return 0;
	  
	   $outer_notice_sn = $param['queryId'];
	   $payment_notice_sn = $param['orderId'];
		
		if ($param['respCode'] == '00'){
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice_sn,$outer_notice_sn);						
			return 1;
		}else{
			$log->LogInfo ( "后台通知处理失败");
		    return 0;
		}
?>
