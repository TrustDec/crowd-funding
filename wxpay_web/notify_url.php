<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	require '../system/system_init.php';
	include_once("./log_.php");
	include_once("../system/payment/Wxjspay/WxPayPubHelper.php");
	$log_ = new Log_();
	$log_name="./notify_url.log";//log文件路径
 	
	$order_id = intval($_REQUEST['order_id']);
	$payment_notice_sn = trim($_REQUEST['out_trade_no']);
	//获取配置信息
	$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wwxjspay'");
 	$payment_info['config'] = unserialize($payment_info['config']);
 	$wx_config=$payment_info['config'];
	$notify = new Notify_pub();
	$notify->update_config($wx_config['appid'],$wx_config['appsecret'],$wx_config['mchid'],$wx_config['partnerid'],$wx_config['partnerkey'],$wx_config['key'],$wx_config['sslcert'],$wx_config['sslkey']);
 	if(empty($order_id)&&empty($payment_notice_sn)){
 		 
		//进入V3
		//存储微信的回调
		
 		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
 		$notify->saveData($xml);
  		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
			//$log_->log_result($log_name,"【签名失败】:\n".$xml."\n");
  		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
			//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
			$info=$notify->xmlToArray($xml);
 			$order_id = intval($info['order_id']);
			$payment_notice_sn = trim($info['out_trade_no']);
			if ($order_id == 0){
			 	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
				$order_id = intval($payment_notice['order_id']);
			}else{
				$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where order_id = ".$order_id);
			}
			$trade_no=$info['transaction_id'];
			$out_trade_no=$payment_notice['notice_sn'];
			require_once APP_ROOT_PATH."system/libs/cart.php";
		   $rs = payment_paid($out_trade_no,$trade_no);		
		    if ($rs)
		   {
			   	$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);				
		   } 
		}
		$returnXml = $notify->returnXml();
		
		echo $returnXml;
	}else{
		//进入V2
		$log_->log_result($log_name,"【接收到的top_xml通知】:\n".$xml."\n");
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
		$trade_no=$_REQUEST['transaction_id'];
		$out_trade_no=$payment_notice['notice_sn'];
 	 	if($wx_config['type']=='V2'){
	  		$request=$_REQUEST;
	 		$sign=$request['sign'];
	 		unset($request['order_id'],$request['sign']);
	 		ksort($request);
	 		if($notify->md5_verifySignature($notify->formatBizQueryParaMap($request,false) ,$sign,$notify->trimString($wx_config['partnerkey']))){
	 			$pay_result = $request['trade_state'];
	 			if($pay_result==0){
	 				require_once APP_ROOT_PATH."system/libs/cart.php";
				   $rs = payment_paid($out_trade_no,$trade_no);		
				    if ($rs)
				   {
					   	$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);				
				   } 
					echo "success";	
					//此处应该更新一下订单状态，商户自行增删操作
					$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
	 			}else{
	 				echo 'fail';
					exit ();
	 			}
	 		}else{
	 			echo 'fail';
				exit ();
	 		}
	 	}
	}
?>