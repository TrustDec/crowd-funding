<?php
require_once("yeepay.config.php");
require_once("lib/yeepayMPay.php");
$yeepay = new yeepayMPay($yeepay_config['merchantaccount'],$yeepay_config['merchantPublicKey'],$yeepay_config['merchantPrivateKey'],$yeepay_config['yeepayPublicKey']);
try {

    		//echo $request['data']."<br>";
    		//echo $request['encryptkey']."<br>";
    		$return = $yeepay->callback($_REQUEST['data'], $_REQUEST['encryptkey']);
    		//print_r($return);

    		//file_put_contents("./log/yjwap2_response_".strftime("%Y%m%d%H%M%S",time()).".txt",print_r($return,true));

    		// TODO:添加订单处理逻辑代码
    		/*
    		名称 	中文说明 	数据类型 	描述
    		merchantaccount 	商户账户 	string
    		yborderid 	易宝交易流水号 	string
    		orderid 	交易订单 	String
    		amount 	支付金额 	int 	以“分”为单位的整型
    		bankcode 	银行编码 	string 	支付卡所属银行的编码，如ICBC
    		bank 	银行信息 	string 	支付卡所属银行的名称
    		cardtype 	卡类型 	int 	支付卡的类型，1为借记卡，2为信用卡
    		lastno 	卡号后4位 	string 	支付卡卡号后4位
    		status 	订单状态 	int 	1：成功
    		*/
            //'fw-'.$payment_log['rec_id'].'-'.$payment_log_id;
            $out_trade_no = $return['orderid'];
            
    		//$payment_log_id = $return['orderid'];
    		$money = floatval($return['amount']/100);
    		$outer_notice_sn = $return['yborderid'];
    		$payment_id = $payment['id'];
    		$currency_id = $payment['currency'];

    		if ($return['status'] == 1){ 
    			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");
    			require_once APP_ROOT_PATH."system/libs/cart.php";
				   $rs = payment_paid($out_trade_no,$trade_no);		
				    if ($rs)
				   {
 					   	$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);				
 				   } 
					echo "success";	
    		}else{
    			 echo "fail";
    		}
    	}catch (yeepayMPayException $e) {
    		 echo "fail";
    	}