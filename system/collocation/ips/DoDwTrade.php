<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function DoDwTradeXml($IpsAcct,$pWebUrl,$pS2SUrl){		
	
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsAcct['pMerBillNo']."</pMerBillNo>"
				."<pAcctType>".$IpsAcct['pAcctType']."</pAcctType>"
				."<pOutType>".$IpsAcct['pOutType']."</pOutType>"
				."<pBidNo>".$IpsAcct['pBidNo']."</pBidNo>"
				."<pContractNo>".$IpsAcct['pContractNo']."</pContractNo>"
				."<pDwTo>".$IpsAcct['pDwTo']."</pDwTo>"
				."<pIdentNo>".$IpsAcct['pIdentNo']."</pIdentNo>"
				."<pRealName>".$IpsAcct['pRealName']."</pRealName>"
				."<pIpsAcctNo>".$IpsAcct['pIpsAcctNo']."</pIpsAcctNo>"
				."<pDwDate>".$IpsAcct['pDwDate']."</pDwDate>"
				."<pTrdAmt>".$IpsAcct['pTrdAmt']."</pTrdAmt>"
				."<pMerFee>".$IpsAcct['pMerFee']."</pMerFee>"
				."<pIpsFeeType>".$IpsAcct['pIpsFeeType']."</pIpsFeeType>"
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
	 * 用户提现
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 提现金额
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function DoDwTrade($user_id,$user_type,$pTrdAmt,$MerCode,$cert_md5,$post_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=DoDwTrade&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=DoDwTrade&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = array();
		if ($user_type == 0){
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}else{
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}
		
		
		$data = array();
		$data['user_type'] = $user_type;
		$data['user_id'] = $user_id;
		$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $user_id.'DW'.NOW_TIME;// '商户提现订单号商户系统唯一不重复',
		$data['pAcctType'] = 1;// '账户类型 否 0#机构（暂未开放） ；1#个人',
		$data['pOutType'] = 1;// '提现模式 否 1#普通提现；2#定向提现<暂不开放> ',
		$data['pBidNo'] = '';// '标号 是/否 提现模式为2时，此字段生效 内容是投标时的标号',
		$data['pContractNo'] = '';// '合同号 是/否 提现模式为2时，此字段生效 内容是投标时的合同号',
		$data['pDwTo'] = '';// '提现去向 是/否 提现模式为2时，此字段生效 上送IPS托管账户号（个人/商户号）',
		$data['pIdentNo'] = $user['idno'];// '证件号码 否 真实身份证（个人）/由IPS颁发的商户号（商户）',
		$data['pRealName'] = $user['real_name'];// '姓名 否 真实姓名（中文） ',
		$data['pIpsAcctNo'] = $user['ips_acct_no'];// 'IPS账户号 否 账户类型为1时，IPS个人托管账户号 账户类型为0时，由IPS颁发的商户号',
		$data['pDwDate'] = to_date(NOW_TIME,'Ymd');// '提现日期 否 格式：YYYYMMDD ',
		$data['pTrdAmt'] = str_replace(',', '',number_format($pTrdAmt,2));//'提现金额 否 金额单位，不能为负，不允许为0 ',
		$fee = 0;
		//获取手续费配置表
		$fee_config = load_auto_cache("user_carry_config");
		//如果手续费大于最大的配置那么取这个手续费
		if($data['pTrdAmt'] >=$fee_config[count($fee_config)-1]['max_price']){
			$fee = $fee_config[count($fee_config)-1]['fee'];
		}
		else{
			foreach($fee_config as $k=>$v){
				if($data['pTrdAmt'] >= $v['min_price'] &&$data['pTrdAmt'] <= $v['max_price']){
					$fee =  floatval($v['fee']);
				}
			}
		}
		$data['pMerFee'] = str_replace(',', '',number_format($fee,2));// '平台手续费 否 金额单位，不能为负，允许为0 这里是平台向用户收取的费用 ',
		$data['pIpsFeeType'] = 2;// 'IPS手续费收取方  这里是IPS收取的费用 1：平台支付 2：提现方支付',
	
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_do_dw_trade",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = DoDwTradeXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		
		//print_r($cert_md5); exit;
		
		$pSign=md5($str);
	
		$html = '
		<form name="form1" id="form1" method="post" action="'.$post_url.'doDwTrade.aspx" target="_self">
		<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
		<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
		<input type="hidden" name="pSign" value="'.$pSign.'" />
		</form>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		
		$ips_log = array();
		$ips_log['code'] = 'DoDwTrade';
		$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$ips_log['strxml'] =$strxml;
		$ips_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
				
		return $html;
	
	}
	
	//用户提现回调
	function DoDwTradeCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
			$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_do_dw_trade set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
		
			//操作成功
			$data = array();
			$data['pIpsBillNo'] = $str3Req["pIpsBillNo"];		
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_do_dw_trade",$data,'UPDATE',$where);	

			//print_r($str3XmlParaInfo); exit;
		}
		
	}	
	
?>