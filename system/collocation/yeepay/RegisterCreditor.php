<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RegisterCreditorXml($data,$details,$extend,$pWebUrl,$pS2SUrl){		
		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo=\"".$data['platformNo']."\">"
				."<requestNo>" .$data['requestNo'] ."</requestNo>"
				."<platformUserNo>" .$data['platformUserNo'] ."</platformUserNo>"
				."<userType>" .$data['userType'] ."</userType>"						
				."<bizType>" .$data['bizType'] ."</bizType>"
				.$details.$extend		  		
				."<callbackUrl><![CDATA[" .$pWebUrl ."]]></callbackUrl>"
				."<notifyUrl><![CDATA[" .$pS2SUrl ."]]></notifyUrl>"
				."</request>";	
				
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	

	
	/**
	 * 投标
	 * @param int $user_id  用户ID
	 * @param int $deal_id  标的ID
	 * @param float $pAuthAmt 投资金额
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RegisterCreditor($order_id,$t_user_id,$platformNo,$post_url,$sys='pc'){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=RegisterCreditor&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=RegisterCreditor&from=".$_REQUEST['from'];//s2s方式返回		
		
		$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
		$deal_id = intval($order['deal_id']);
		$user_id = intval($order['user_id']);	
		
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		$tuser = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$t_user_id);
		$user_load_transfer_fee = $GLOBALS['db']->getOne("SELECT user_load_transfer_fee FROM ".DB_PREFIX."deal WHERE id=".$deal_id);
		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id);
		if (empty($user['ips_acct_no']) || empty($tuser['ips_acct_no'])){
			return '有一方未申请 ips 帐户';
		}
		
 		
		$yeepay_log = array();
		$yeepay_log['code'] = 'toCpTransaction';
		$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		$requestNo = $GLOBALS['db']->insert_id();
				
		$data = array();
		$data['requestNo'] = $requestNo;//请求流水号
		$data['platformUserNo'] = $user_id;//
		$data['platformNo'] = $platformNo;// 商户编号
		$data['paymentAmount'] = $order['total_price'];// 记录投标金额
		
		//用户类型 0普通用户 1 企业用户；现在只支持 普通用户
		if (true){
			$data['userType'] = 'MEMBER';//出款人用户类型
		}else{
			$data['userType'] = 'MERCHANT';//出款人用户类型MEMBER 个人会员 MERCHANT 商户 
		}
		
		//TENDER 投标 REPAYMENT 还款 CREDIT_ASSIGNMENT 债权转让 TRANSFER 转账 COMMISSION 分润，仅在资金转账明细中使用
		$data['bizType'] = 'TENDER';//根据业务的不同，需要传入不同的值，见【业务类型】。并参考下面的详细信息
		
		//投标 扩展字段
		$data['tenderOrderNo'] = $order_id;//订单编号
		$data['tenderName'] = $deal['name'];//项目名称 
		$data['tenderId'] = $deal['id'];//项目编号
		$data['tenderAmount'] = $deal['limit_price'];//标的金额
 		$data['paymentAmount'] = $order['total_price'];//实际支付金额
		$data['tenderDescription'] = $deal['name'];//项目描述信息
		$data['borrowerPlatformUserNo'] = $deal['user_id'];//项目的借款人平台用户编号		  
		
		
 		if (true){
			$targetUserType = 'MEMBER';//出款人用户类型
		}else{
			$targetUserType = 'MERCHANT';//出款人用户类型MEMBER 个人会员  商户
		}
		if($deal['pay_radio']==0){
			$deal['pay_radio']=0.1;
		}
		//成交服务费
		$fee = round($order['deal_price'] * $deal['pay_radio'],2);
		$data['fee'] =$fee; 
		//分红
		$share_fee=$order['share_fee'];
		$data['share_fee'] =$share_fee;
		
		$data['delivery_fee'] =$order['delivery_fee'];
		//实际可到账金额
		$targetAmount = $order['total_price'] - $fee-$share_fee;
		
		$data['targetAmount'] =$targetAmount;
		$details = "<details><detail><targetUserType>".$targetUserType."</targetUserType><targetPlatformUserNo>".intval($deal['user_id'])."</targetPlatformUserNo><amount>".$targetAmount."</amount><bizType>TENDER</bizType></detail>"  
				  ."<detail><targetUserType>MERCHANT</targetUserType><targetPlatformUserNo>$platformNo</targetPlatformUserNo><amount>$fee</amount><bizType>COMMISSION</bizType></detail></details>";
		if($share_fee>0){
				$details.= "<detail><targetUserType>".$data['userType']."</targetUserType><targetPlatformUserNo>".$user_id."</targetPlatformUserNo><amount>$share_fee</amount><bizType>COMMISSION</bizType></detail>";
			}		
		$extend = '<extend>'
				.'<property name="tenderOrderNo" value="'.$data['tenderOrderNo'].'" />'
				.'<property name="tenderName" value="'.$data['tenderName'].'" />'
				.'<property name="tenderAmount" value="'.$data['tenderAmount'].'" />'
				.'<property name="tenderDescription" value="'.$data['tenderDescription'].'" />'
				.'<property name="borrowerPlatformUserNo" value="'.$deal["user_id"].'" />'
				.'</extend>';		
		
		$data['details'] = $details;//资金明细记录
		$data['extend'] = $extend;//业务扩展属性，根据业务类型的不同，需要传入不同的参数
		$data['create_time'] = NOW_TIME;
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$data,'INSERT');
		
		$id = $GLOBALS['db']->insert_id();
		
		$deal_order = array();
		$deal_order['requestNo'] = $requestNo;//1#转账成功
		$deal_order['fee'] = $fee; 
		
		$deal_order['targetAmount'] = $targetAmount; 
		$where = " id = ".$order_id;
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$deal_order,'UPDATE',$where);
		
		$strxml = RegisterCreditorXml($data,$details,$extend,$pWebUrl,$pS2SUrl);			
		
		$pSign=cfca($strxml);
		
//		$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>
//		<form name="form1" id="form1" method="post" action="'.$post_url.'/bha/toCpTransaction" target="_self">
//		<input type="text" name="sign" value="'.$pSign.'" />
//				<textarea name="req" cols="100" rows="5">'.$strxml.'</textarea>
//						 <input type="submit" value="提交"></input>
//		</form>
//		</body></html>
//		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		if($sys=='pc'){
			$act = 'bha';
		}elseif($sys=='mobile'){
			$act = 'bhawireless';
		}
		$html='<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
	<body>
		<form name="form1" id="form1" method="post" action="'.$post_url.'/'.$act.'/toCpTransaction" target="_self">
			<input type="hidden" name="sign" value="'.$pSign.'" />
			<textarea name="req" cols="100" rows="5" style="display:none">'.$strxml.'</textarea>
		 	<input type="hidden" value="提交"></input>
		</form>
		<div style="width:100%;text-align:center;padding:50px 0;"><img src="'.APP_ROOT.'/app/Tpl/'.app_conf("TEMPLATE").'/images/loading.gif" />页面正在跳转，请稍后...</div>
	</body>
</html>
<script language="javascript">document.form1.submit();</script>';
		$yeepay_log = array();
		$yeepay_log['strxml'] =$strxml;
		$yeepay_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log,'UPDATE','id='.$requestNo);
		
		return $html;
	}
	
	//投资回调
	function RegisterCreditorCallBack($str3Req){
		
		$requestNo = $str3Req["requestNo"];
		$where = " requestNo = '".$requestNo."'";
		$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		
		//操作成功
		if ($GLOBALS['db']->affected_rows()){	
 			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$str3Req,'UPDATE',$where);
			 
			if ($str3Req['code'] == '1'){
				
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where ".$where);
 				 
 				$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".(int)$ipsdata['tenderOrderNo']);
				$GLOBALS['db']->query("update  ".DB_PREFIX."deal_order set online_pay=".$ipsdata['paymentAmount'].",is_tg = 1 where order_status=0 and id=".$ipsdata['tenderOrderNo']);
  				 
  				deal_order_progress($order['deal_id'],$order['user_id'],$order['type']);
  				$log_info = "通过第三方接口易宝支付，单号".$ipsdata['tenderOrderNo']."，支付金额".$ipsdata['paymentAmount'];
 				$deal_type = $GLOBALS['db']->getOne("select type from ".DB_PREFIX."deal where id = ".$order['deal_id']);
 				if($deal_type==0){
 					save_log_common('-'.$ipsdata['paymentAmount'],$GLOBALS['user_info']['id'],$log_info,array('type'=>5,'deal_id'=>$order['deal_id']));
 				
 				}
 				if($deal_type==1){
 					save_log_common('-'.$ipsdata['paymentAmount'],$GLOBALS['user_info']['id'],$log_info,array('type'=>6,'deal_id'=>$order['deal_id']));
 				
 				}
 				
 				pay_order($ipsdata['tenderOrderNo']);
  			}
 		}else{
 			return 1;
		}
	}	
	
?>