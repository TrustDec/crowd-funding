<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */

	function RegisterCretansferXml($data,$details,$extend,$pWebUrl,$pS2SUrl){
		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
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
	 * 登记债权转让
	 * @param int $order_id  订单id
	 * @param int $t_user_id  受让用户ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RegisterCretansfer($order_id,$t_user_id, $platformNo,$post_url,$sys='pc'){
	
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
		
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=RegisterCretansfer&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=RegisterCretansfer&from=".$_REQUEST['from'];//s2s方式返回		
	
//		$sql = "update ".DB_PREFIX."deal_order set lock_user_id = ".$t_user_id.", lock_time =".NOW_TIME;
//		$sql .= " where is_tg = 1  and order_status = 0 ";
//		$sql .= " and id = ".$order_id;
//		
//		//echo $sql; exit;
//		$GLOBALS['db']->query($sql);
		if (true){
			
			$yeepay_log = array();
			$yeepay_log['code'] = 'toCpTransaction';
			$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
			$requestNo = $GLOBALS['db']->insert_id();
			
			$data = array();
			$data['requestNo'] = $requestNo;//请求流水号
			$data['platformUserNo'] = $user_id;//
			$data['platformNo'] = $platformNo;// 商户编号
			$data['transfer_id'] = $order_id;
			
			//用户类型 0普通用户 1 企业用户；现在只支持 普通用户
			if (true){
				$data['userType'] = 'MEMBER';//出款人用户类型
			}else{
				$data['userType'] = 'MERCHANT';//出款人用户类型MEMBER 个人会员  商户
			}
			
			//TENDER 投标 REPAYMENT 还款 CREDIT_ASSIGNMENT 债权转让 TRANSFER 转账 COMMISSION 分润，仅在资金转账明细中使用
			$data['bizType'] = 'CREDIT_ASSIGNMENT';//根据业务的不同，需要传入不同的值，见【业务类型】。并参考下面的详细信息
			
			//投标 扩展字段
			$data['tenderOrderNo'] = $order_id;//项目编号
			$data['creditorPlatformUserNo'] = $user_id;//债权转让人 
 			$data['originalRequestNo'] = $order_id;//需要转让的投资记录流水号 
			
			//成交服务费
			$deal_fee=$deal['pay_radio']?$deal['pay_radio']:0.1;
			$fee = round($order['deal_price'] * $deal_fee,2);
			
			//分红
			$share_fee=$order['share_fee'];
			 
			//实际可到账金额
			$targetAmount = $order['total_price'] - $fee-$share_fee;
			
			$data["tenderAmount"] = $fee + $targetAmount+$share_fee;
			
 			$data["tenderName"] = $deal["name"];
			
			$data["borrowerPlatformUserNo"] = $user_id;
			
			$data["tenderDescription"] = $deal["name"];
			
			
			
			//$data["money"] = $deal["name"];
			
			$details = "<details><detail><targetUserType>".$data['userType']."</targetUserType><targetPlatformUserNo>".$t_user_id."</targetPlatformUserNo><amount>".$targetAmount."</amount><bizType>CREDIT_ASSIGNMENT</bizType></detail>"
					."<detail><targetUserType>MERCHANT</targetUserType><targetPlatformUserNo>$platformNo</targetPlatformUserNo><amount>$fee</amount><bizType>COMMISSION</bizType></detail>";
			if($share_fee>0){
				$details.= "<detail><targetUserType>".$data['userType']."</targetUserType><targetPlatformUserNo>".$user_id."</targetPlatformUserNo><amount>$share_fee</amount><bizType>COMMISSION</bizType></detail>";
			}
			$details .= "</details>";
			$extend = '<extend>'
				.'<property name="tenderOrderNo" value="'.$data['tenderOrderNo'].'" />'
				.'<property name="creditorPlatformUserNo" value="'.$data['creditorPlatformUserNo'].'" />'
				.'<property name="originalRequestNo" value="'.$data['originalRequestNo'].'" />'
				.'</extend>';
			
			
			$data['details'] = $details;//资金明细记录
			$data['extend'] = $extend;//业务扩展属性，根据业务类型的不同，需要传入不同的参数
			$data['create_time'] = NOW_TIME;	
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$data,'INSERT');
				
			$id = $GLOBALS['db']->insert_id();
			
			
			$strxml = RegisterCretansferXml($data,$details,$extend,$pWebUrl,$pS2SUrl);
 			$pSign=cfca($strxml);
 			
 			if($sys=='pc'){
				$act = 'bha';
			}elseif($sys=='mobile'){
				$act = 'bhawireless';
			}
			
			$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>
			<form name="form1" id="form1" method="post" action="'.$post_url.'/'.$act.'/toCpTransaction" target="_self">
			<input type="hidden" name="sign" value="'.$pSign.'" />
			<textarea name="req" cols="100" rows="5" style="display:none">'.$strxml.'</textarea>
			<input type="hidden" value="提交"></input>
			<div style="width:100%;text-align:center;padding:50px 0;"><img src="'.APP_ROOT.'/app/Tpl/'.app_conf("TEMPLATE").'/images/loading.gif" />页面正在跳转，请稍后...</div>
			</form>
			</body></html>
			<script language="javascript">document.form1.submit();</script>';
			//echo $html; exit;
			
			$yeepay_log = array();
			$yeepay_log['strxml'] =$strxml;
			$yeepay_log['html'] = $html;
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log,'UPDATE','id='.$requestNo);
			
			
			return $html;
		}else{
			return '该债权转让已经被其它用户锁定';
		}		
	}
	
	//登记债权转让回调
	function RegisterCretansferCallBack($str3Req,$platformNo,$post_url){
		
		$requestNo = $str3Req["requestNo"];
		$where = " requestNo = '".$requestNo."'";
		$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$str3Req,'UPDATE',$where);
			
			$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=RegisterCretansferBack&from=".$_REQUEST['from'];//s2s方式返回		
		
			$where = " requestNo = '".$requestNo."' and is_callback = 1 and bizType ='CREDIT_ASSIGNMENT' ";
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where ".$where);
			$user_id = intval($ipsdata['platformUserNo']);
			$expired = to_date(NOW_TIME+3600*12,"Y-m-d H:i:s");
			
			/* 请求参数 */  
			$req = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
			."<request platformNo=\"".$platformNo."\">"
			."<requestNo>".$requestNo."</requestNo>"
			."<mode>CONFIRM</mode>"
			."<notifyUrl><![CDATA[" .$pS2SUrl ."]]></notifyUrl>"
			."</request>";
	
			/* 签名数据 */
			$sign = "xxxx";
			/* 调用账户查询服务 */
			$service = "COMPLETE_TRANSACTION";
			
			$yeepay_log = array();
			$yeepay_log['code'] = 'bhaController';
			$yeepay_log['strxml'] = $req;
			$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
			
			$ch = curl_init($post_url."/bhaexter/bhaController");
			curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER=>0,
			CURLOPT_SSL_VERIFYHOST=>0,
			CURLOPT_POSTFIELDS => 'service=' . $service . '&req=' . rawurlencode($req) . "&sign=" . rawurlencode($sign)
			));
			$resultStr = curl_exec($ch);
			
			if (empty($resultStr)){
				$result = array();
				$result['pErrCode'] = 9999;
				$result['pErrMsg'] = '返回出错';
				$result['pIpsAcctNo'] = '';
				$result['pBalance'] = 0;
				$result['pLock'] = 0;
				$result['pNeedstl'] = 0;
			}else{
					require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
					$str3ParaInfo = @XML_unserialize($resultStr);
					//print_r($str3ParaInfo);exit;
					$str3Req = $str3ParaInfo['response'];
					
					$result = array();
					$result['pErrCode'] = $str3Req["code"];
					$result['pErrMsg'] = $str3Req["description"];
					$result['pIpsAcctNo'] = $user_id;	
					if($str3Req["code"] == 1)
					{
						$r_data = array();
						$r_data["is_complete_transaction"] = 1;
						
						$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$r_data,'UPDATE','id='.$ipsdata["id"]);
						
						$sql = "update ".DB_PREFIX."deal_load_transfer set t_user_id = lock_user_id, transfer_time = '".get_gmtime()."', ips_status = 2, ips_bill_no = '".$requestNo."' where ips_status = 1 and id =".intval($ipsdata['transfer_id']);
						//echo $sql;
						$GLOBALS['db']->query($sql);
						
						$sql = "select * from ".DB_PREFIX."deal_load_transfer where ips_status = 2 and id =".intval($ipsdata['transfer_id']);
							
						$transfer = $GLOBALS['db']->getRow($sql);
							
						//将用户投资回款计划,收款人更改为：承接者
						$sql = "update ".DB_PREFIX."deal_load_repay set t_user_id = '".$transfer['t_user_id']."' where has_repay = 0 and load_id =".intval($transfer['load_id']) + " and user_id =".intval($transfer['user_id']) + " and deal_id = ".intval($transfer['deal_id']);
						//echo $sql;
						$GLOBALS['db']->query($sql);
						
						return 1;
					}
			}
			return 1;
		}
		else
		{
			return 1;
		}
		
	}	
	function RegisterCretansferBack($str3Req)
	{
		$result = array();
		$result['pErrCode'] = $str3Req["code"];
		$result['pErrMsg'] = $str3Req["description"];
		//$result['pIpsAcctNo'] = $user_id;	
		
		$requestNo = $str3Req["requestNo"];
		
		$where = " requestNo = '".$requestNo."' and is_callback = 1 and bizType ='CREDIT_ASSIGNMENT' ";
		$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where ".$where);
		$user_id = intval($ipsdata['platformUserNo']);
		
		if($str3Req["code"] == 1)
		{
			$r_data = array();
			$r_data["is_complete_transaction"] = 1;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$r_data,'UPDATE','id='.$ipsdata["id"]);
			
			$sql = "update ".DB_PREFIX."deal_load_transfer set t_user_id = lock_user_id, transfer_time = '".get_gmtime()."', ips_status = 2, ips_bill_no = '".$requestNo."' where ips_status = 1 and id =".intval($ipsdata['transfer_id']);
			//echo $sql;
			$GLOBALS['db']->query($sql);
			
			$sql = "select * from ".DB_PREFIX."deal_load_transfer where ips_status = 2 and id =".intval($ipsdata['transfer_id']);
				
			$transfer = $GLOBALS['db']->getRow($sql);
				
			//将用户投资回款计划,收款人更改为：承接者
			$sql = "update ".DB_PREFIX."deal_load_repay set t_user_id = '".$transfer['t_user_id']."' where has_repay = 0 and load_id =".intval($transfer['load_id']) + " and user_id =".intval($transfer['user_id']) + " and deal_id = ".intval($transfer['deal_id']);
			//echo $sql;
			$GLOBALS['db']->query($sql);
			
			//return 1;
		}
		return 1;
	}
?>