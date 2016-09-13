<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RegisterGuarantorXml($IpsAcct,$pWebUrl,$pS2SUrl){		

		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsAcct['pMerBillNo']."</pMerBillNo>"
				."<pMerDate>".$IpsAcct['pMerDate']."</pMerDate>"
				."<pBidNo>".$IpsAcct['pBidNo']."</pBidNo>"
				."<pAmount>".$IpsAcct['pAmount']."</pAmount>"
				."<pMarginAmt>".$IpsAcct['pMarginAmt']."</pMarginAmt>"
				."<pProFitAmt>".$IpsAcct['pProFitAmt']."</pProFitAmt>"
				."<pAcctType>".$IpsAcct['pAcctType']."</pAcctType>"
				."<pFromIdentNo>".$IpsAcct['pFromIdentNo']."</pFromIdentNo>"
				."<pAccountName>".$IpsAcct['pAccountName']."</pAccountName>"
				."<pAccount>".$IpsAcct['pAccount']."</pAccount>"
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
	 * 登记担保方
	 * @param int $deal_id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function RegisterGuarantor($deal_id,$MerCode,$cert_md5,$post_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=RegisterGuarantor";//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=RegisterGuarantor";//s2s方式返回		
	
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		$agency_id = intval($deal['agency_id']);
		$agency = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$agency_id);
		
		$data = array();
		$data['deal_id'] = $deal_id;
		$data['agency_id'] = $agency_id;
		$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $deal_id.'G'.get_gmtime();//$user_id;//'pMerBillNo商户开户流水号 否 商户系统唯一丌重复 针对用户在开户中途中断（开户未完成，但关闭了IPS开 户界面）时，必须重新以相同的商户订单号发起再次开户 ',
		$data['pMerDate'] = to_date(get_gmtime(),'Ymd');//商户日期 否 格式：yyyyMMdd,
		$data['pBidNo'] = $deal_id;//'标的号 否 字母和数字，如a~z,A~Z,0~9',
		
		$pAcctType = intval($agency['acct_type']);
		$data['pAcctType'] = $pAcctType;//担保方类型 否 0#机构；1#个人,
		
		if ($pAcctType == 0){			
			$data['pFromIdentNo'] = $agency['ips_mer_code'];//担保方证件号码 否 针对担保方类型为1时：真实身份证（个人） 针对担保方类型为0时：由IPS颁发的商户号
			$data['pAccountName'] = $agency['real_name'];//担保方账户姓名 否 针对担保方类型为1时：担保方账户真实姓名 针对担保方类型为0时：在IPS开户时登记的商户名称
			$data['pAccount'] = $agency['ips_mer_code']; //担保方账户 否 担保方类型为1时，IPS托管账户号（个人） 担保方类型为0时，由IPS颁发的商户号
		}else{
			$data['pFromIdentNo'] = $agency['idno'];//担保方证件号码 否 针对担保方类型为1时：真实身份证（个人） 针对担保方类型为0时：由IPS颁发的商户号
			$data['pAccountName'] = $agency['real_name'];//担保方账户姓名 否 针对担保方类型为1时：担保方账户真实姓名 针对担保方类型为0时：在IPS开户时登记的商户名称
			$data['pAccount'] = $agency['ips_acct_no']; //担保方账户 否 担保方类型为1时，IPS托管账户号（个人） 担保方类型为0时，由IPS颁发的商户号			
		}
		
		//`pAmount` decimal(11,2) default '0.00' COMMENT '担保金额 否 金额单位元，不能为负，不允许为0 担保人针对该合同标的承诺的最高赔付金额 ',
		//`pProFitAmt` decimal(11,2) default NULL COMMENT '担保收益 否 金额单位元，不能为负，允许为0 ',

		$data['pAmount'] = str_replace(',', '',number_format($deal['guarantor_amt'],2));//'担保金额 否 金额单位元，不能为负，不允许为0 担保人针对该合同标的承诺的最高赔付金额 '
		$data['pMarginAmt'] = str_replace(',', '',number_format($deal['guarantor_margin_amt'],2));//'担保保证金 否 金额单位元，不能为负，允许为0 担保人针对该合同标的被冻结的金额',
		$data['pProFitAmt'] = str_replace(',', '',number_format($deal['guarantor_pro_fit_amt'],2));//'担保收益 否 金额单位元，不能为负，允许为0 ',
	
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_guarantor",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = RegisterGuarantorXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		
		//print_r($cert_md5); exit;
		
		$pSign=md5($str);
	
		$html = '
		<form name="form1" id="form1" method="post" action="'.$post_url.'registerGuarantor.aspx" target="_self">
		<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
		<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
		<input type="hidden" name="pSign" value="'.$pSign.'" />
		</form>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		
		$ips_log = array();
		$ips_log['code'] = 'RegisterGuarantor';
		$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$ips_log['strxml'] =$strxml;
		$ips_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
				
		return $html;
	
	}
	
	//登记担保方回调
	function RegisterGuarantorCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
			$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		
		$sql = "update ".DB_PREFIX."ips_register_guarantor set is_callback = 1 where is_callback = 0 and ".$where;
		//echo $sql; exit;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
		
			//操作成功
			$data = array();
			$data['pP2PBillNo'] = $str3Req["pP2PBillNo"];//担保方编号 否 IPS返回的担保人编号
			$data['pIpsTime'] = $str3Req["pIpsTime"]; //'IPS处理时间  格式为：yyyyMMddHHmmss',
			$data['pStatus'] = $str3Req["pStatus"]; // '担保状态 否 0：新增  1：迚行中  10：结束  9：失败；',
			$data['pRealFreezeAmt'] = $str3Req["pRealFreezeAmt"]; // '实际冻结金额  IPS返回的担保保证金',
			$data['pCompenAmt'] = $str3Req["pRealFreezeAmt"]; // '已代偿金额  IPS返回的担保金额',

			
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_guarantor",$data,'UPDATE',$where);

			if ($pErrCode == 'MG00000F'){				
				$deal_id = intval($GLOBALS['db']->getOne("select deal_id from ".DB_PREFIX."ips_register_guarantor where ".$where));
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set mer_guarantor_bill_no =  '".$pMerBillNo."', ips_guarantor_bill_no = '".$data['pP2PBillNo']."',guarantor_real_freezen_amt = ".floatval($data['pRealFreezeAmt'])." where id = ".$deal_id);
			}
		}
		
	}	
	
?>