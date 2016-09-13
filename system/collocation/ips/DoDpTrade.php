<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function DoDpTradeXml($IpsAcct,$pWebUrl,$pS2SUrl){		
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsAcct['pMerBillNo'] ."</pMerBillNo>"
				."<pAcctType>".$IpsAcct['pAcctType'] ."</pAcctType>"
				."<pIdentNo>".$IpsAcct['pIdentNo'] ."</pIdentNo>"
				."<pRealName>".$IpsAcct['pRealName'] ."</pRealName>"
				."<pIpsAcctNo>".$IpsAcct['pIpsAcctNo'] ."</pIpsAcctNo>"
				."<pTrdDate>".$IpsAcct['pTrdDate'] ."</pTrdDate>"
				."<pTrdAmt>".$IpsAcct['pTrdAmt'] ."</pTrdAmt>"
				."<pChannelType>".$IpsAcct['pChannelType'] ."</pChannelType>"
				."<pTrdBnkCode>".$IpsAcct['pTrdBnkCode'] ."</pTrdBnkCode>"
				."<pMerFee>".$IpsAcct['pMerFee'] ."</pMerFee>"
				."<pIpsFeeType>".$IpsAcct['pIpsFeeType'] ."</pIpsFeeType>"
				."<pWebUrl><![CDATA[" .$pWebUrl ."]]></pWebUrl>"
				."<pS2SUrl><![CDATA[" .$pS2SUrl ."]]></pS2SUrl>"
				."<pMemo1><![CDATA[" .$IpsAcct['pMemo1'] ."]]></pMemo1>"
				."<pMemo2><![CDATA[" .$IpsAcct['pMemo2'] ."]]></pMemo2>"
				."<pMemo3><![CDATA[" .$IpsAcct['pMemo3'] ."]]></pMemo3>"
				."</pReq>";		
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	

	/**
	 * 充值
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 充值金额
	 * @param string $pTrdBnkCode 银行编号
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function DoDpTrade($user_id,$user_type,$pTrdAmt,$pTrdBnkCode,$MerCode,$cert_md5,$post_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=DoDpTrade&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=DoDpTrade&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = array();
		if ($user_type == 0){
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}else{
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}
		
		//print_r($data); exit;
		
		$data = array();
		$data['user_type'] = $user_type;
		$data['user_id'] = $user_id;
		$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $user_id.'DP'.get_gmtime();//商户充值订单号 否 商户系统唯一不重复,
		$data['pAcctType'] = 1;//账户类型 否 固定值为 1，表示为类型为IPS个人账户,
		
		$data['pIdentNo'] = $user['idno'];//'证件号码 否 真实身份证（个人）/IPS颁发的商户号（商户） 本期考虑个人，商户充值预留，下期增加
		$data['pRealName'] = $user['real_name'];//'姓名 否 真实姓名（中文） pIpsAcctNo 30 IPS托管账户号 否 账户类型为1时，IPS托管账户号（个人）		
		$data['pIpsAcctNo'] = $user['ips_acct_no']; //IPS托管账户号 账户类型为1时，IPS托管账户号（个人）
		
		$data['pTrdDate'] = to_date(get_gmtime(),'Ymd');//充值日期 否 格式：YYYYMMDD
	
		$data['pTrdAmt'] = str_replace(',', '',number_format($pTrdAmt,2));//充值金额 否 金额单位：元，丌能为负，丌允许为0，保留2位小数； 格式：12.00 
		$data['pChannelType'] = 1;//充值渠道种类 否 1#网银充值；2#代扣充值
		$data['pTrdBnkCode'] = $pTrdBnkCode;//充值银行 是/否 网银充值的银行列表由IPS提供，对应充值银行的CODE， 具体使用见接口 <<商户端获取银行列表查询(WS)>>， 获取pBankList内容项中“银行卡编号”字段； 代扣充值这里传空； ',
		$data['pMerFee'] = '0.00';//`pMerFee` decimal(11,2) default '0.00' COMMENT '平台手续费 否 这里是平台向用户收取的费用 金额单位：元，丌能为负，允许为0，保留2位小数； 格式：12.00 ',
		
		$collocation_item = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."collocation where class_name='Ips'");
		$collocation_cfg = unserialize($collocation_item['config']);
		$data['pIpsFeeType'] = $collocation_cfg['fee_type'];
		
		//$data['pIpsFeeType'] = app_conf("IPS_FEE_TYPE");//'谁付IPS手续费 否 这里是IPS向平台收取的费用 1：平台支付 2：用户支付 ',
		
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_do_dp_trade",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = DoDpTradeXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		
		//print_r($cert_md5); exit;
		
		$pSign=md5($str);
	
		$html = '
		<form name="form1" id="form1" method="post" action="'.$post_url.'doDpTrade.aspx" target="_self">
		<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
		<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
		<input type="hidden" name="pSign" value="'.$pSign.'" />
		</form>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		
		$ips_log = array();
		$ips_log['code'] = 'DoDpTrade';
		$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$ips_log['strxml'] =$strxml;
		$ips_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
				
		return $html;
	
	}
	
	//充值回调
	function DoDpTradeCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
			$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_do_dp_trade set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
		
			//操作成功
			$data = array();
			$data['pIpsBillNo'] = $str3Req["pIpsBillNo"];

			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_do_dp_trade",$data,'UPDATE',$where);
			
			if ($pErrCode == 'MG00000F'){	

				$dp_trade = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_do_dp_trade where ".$where);
				
				/*
				$order['bank_id'] = $dp_trade['pTrdBnkCode'];
				$order['memo'] = '第三方托管充值pIpsBillNo:'.$dp_trade['pIpsBillNo'];
				
				//开始生成订单
				$now = NOW_TIME;
				$order['type'] = 1; //充值单
				$order['user_id'] = $dp_trade['user_id'];
				$order['create_time'] = $now;				
				
				//谁付IPS手续费 否 这里是IPS向平台收取的费用 1：平台支付 2：用户支付 				
				$order['payment_fee'] = $dp_trade['pMerFee'];
				$order['deal_total_price'] = $dp_trade['pTrdAmt'];
				$order['total_price'] = $dp_trade['pTrdAmt'] + $dp_trade['pMerFee'];
				
				$order['pay_amount'] = $order['total_price'];
				$order['pay_status'] = 2;
				$order['delivery_status'] = 5;
				$order['order_status'] = 0;
				$order['payment_id'] = 0;		
				
				do
				{
					$order['order_sn'] = to_date(NOW_TIME,"Ymdhis").rand(100,999);
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order,'INSERT','','SILENT');
					$order_id = intval($GLOBALS['db']->insert_id());
				}while($order_id==0);
				*/
				
				$user_id = intval($dp_trade['user_id']);				
				$log_info['log_info'] = '第三方托管充值:'.$dp_trade['pIpsBillNo'];
				$log_info['log_time'] = NOW_TIME;					
				$log_info['log_user_id'] = $user_id;				
				$log_info['money'] = floatval($dp_trade['pTrdAmt']);
				$log_info['score'] = 0;
				$log_info['point'] = 0;
				$log_info['quota'] = 0;
				$log_info['lock_money'] = 0;
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				
				//$deal_id = intval($GLOBALS['db']->getOne("select deal_id from ".DB_PREFIX."ips_register_subject where ".$where));
				//$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_bill_no = '".$data['pIpsBillNo']."',real_freezen_amt = ".floatval($data['pRealFreezenAmt'])." where id = ".$deal_id);
			}
		}
		
	}	
	
?>