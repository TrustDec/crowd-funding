<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RegisterCreditorXml($IpsData,$pWebUrl,$pS2SUrl){		
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsData['pMerBillNo']."</pMerBillNo>"
				."<pMerDate>".$IpsData['pMerDate']."</pMerDate>"
				."<pBidNo>".$IpsData['pBidNo']."</pBidNo>"
				."<pContractNo>".$IpsData['pContractNo']."</pContractNo>"
				."<pRegType>".$IpsData['pRegType']."</pRegType>"
				."<pAuthNo>".$IpsData['pAuthNo']."</pAuthNo>"
				."<pAuthAmt>".$IpsData['pAuthAmt']."</pAuthAmt>"
				."<pTrdAmt>".$IpsData['pTrdAmt']."</pTrdAmt>"
				."<pFee>".$IpsData['pFee']."</pFee>"
				."<pAcctType>".$IpsData['pAcctType']."</pAcctType>"
				."<pIdentNo>".$IpsData['pIdentNo']."</pIdentNo>"
				."<pRealName>".$IpsData['pRealName']."</pRealName>"
				."<pAccount>".$IpsData['pAccount']."</pAccount>"
				."<pUse>".$IpsData['pUse']."</pUse>"
				."<pWebUrl><![CDATA[" .$pWebUrl."]]></pWebUrl>"
				."<pS2SUrl><![CDATA[" .$pS2SUrl."]]></pS2SUrl>"
				."<pMemo1><![CDATA[" .$IpsData['pMemo1']."]]></pMemo1>"
				."<pMemo2><![CDATA[" .$IpsData['pMemo2']."]]></pMemo2>"
				."<pMemo3><![CDATA[" .$IpsData['pMemo3']."]]></pMemo3>"
				."</pReq>";		
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	

	
	/**
	 * 登记债权人
	 * @param int $user_id  用户ID
	 * @param int $deal_id  标的ID
	 * @param float $pAuthAmt 投资金额
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RegisterCreditor($user_id,$deal_id,$pAuthAmt,$MerCode,$cert_md5,$post_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=RegisterCreditor&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=RegisterCreditor&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		
		$data = array();
		$data['user_id'] = $user_id;
		$data['deal_id'] = $deal_id;
		$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $user_id.'L'.$deal_id.'No'.to_date(get_gmtime(),'YmdHis');//商户订单号 否 商户系统唯一不重复,
		$data['pMerDate'] = to_date(get_gmtime(),'Ymd');//'商户日期 否 格式：YYYYMMDD ',
		$data['pBidNo'] = $deal_id;// varchar(30) default NULL COMMENT '标的号 否 字母和数字，如a~z,A~Z,0~9',
		$data['pContractNo'] = $data['pMerBillNo'];//$user_id.'L'.$deal_id.'No'.to_date(get_gmtime(),'YmdH:i:s');//`` varchar(30) default NULL COMMENT '合同号 否 字母和数字，如a~z,A~Z,0~9',
		$data['pRegType'] = 1; //'登记方式 否 1：手劢投标  2：自劢投标',
		$data['pAuthNo'] = '';// '授权号 是/否  字母和数字，如a~z,A~Z,0~9 登记方式为1时，为空 登记方式为2时，填写该投资人自劢投标签约时IPS向平 台接口返回的“pIpsAuthNo 授权号” （详见自劢投标签 约） ',
		$data['pAuthAmt'] = str_replace(',', '', number_format($pAuthAmt,2));;// '债权面额 否 金额单位元，不能为负，不允许为0 ',
		$data['pTrdAmt'] = str_replace(',', '', number_format($pAuthAmt,2));;//'交易金额 否 金额单位元，不能为负，不允许为0 债权面额等于交易金额 ',
		$data['pFee'] = 0;// '0.00' COMMENT '投资人手续费 否 金额单位元，不能为负，允许为0 ',
		$data['pAcctType'] = 1;// '账户类型 否 0#机构（暂未开放） ；1#个人 ',
		$data['pIdentNo'] = $user['idno'];// '证件号码 否 真实身份证（个人）/由IPS颁发的商户号',
		$data['pRealName'] = $user['real_name'];// '姓名 否 真实姓名（中文）',
		$data['pAccount'] = $user['ips_acct_no'];// '投资人账户 否 账户类型为1时，IPS托管账户号（个人） 账户类型为0时，由IPS颁发的商户号',
		$pUse = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_loan_type where id = ".intval($deal['type_id']));
		$data['pUse'] = $pUse;// '借款用途',
		
				

		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_creditor",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = RegisterCreditorXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		
		//print_r($cert_md5); exit;
		
		$pSign=md5($str);
	
		$html = '
		<form name="form1" id="form1" method="post" action="'.$post_url.'registerCreditor.aspx" target="_self">
		<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
		<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
		<input type="hidden" name="pSign" value="'.$pSign.'" />
		</form>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		$ips_log = array();
		$ips_log['code'] = 'RegisterCreditor';
		$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$ips_log['strxml'] =$strxml;
		$ips_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
		
		return $html;
	
	}
	
	//登记债权人回调
	function RegisterCreditorCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3XmlParaInfo);

		$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_register_creditor set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
		
			//操作成功
			$data = array();
			$data['pAccountDealNo'] = $str3Req["pAccountDealNo"]; //投资人编号 否 IPS返回的投资人编号
			$data['pBidDealNo'] = $str3Req["pBidDealNo"];//标的编号 否 IPS返回的标的编号
			$data['pBusiType']  = $str3Req["pBusiType"];//业务类型 否 返回1，代表投标
			$data['pTransferAmt'] = $str3Req["pTransferAmt"];//实际冻结金额 否 实际冻结金额
			$data['pStatus'] = $str3Req["pStatus"];//债权人状态 否 0：新增 1：迚行中 10：结束			
			$data['pP2PBillNo'] = $str3Req["pP2PBillNo"];//IPS P2P订单号 否 由IPS系统生成的唯一流水号
			$data['pIpsTime'] = $str3Req["pIpsTime"];//IPS处理时间 否 格式为：yyyyMMddHHmmss
			
			
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_creditor",$data,'UPDATE',$where);
			
			if ($pErrCode == 'MG00000F'){
				
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_register_creditor where ".$where);
				$user_id = intval($ipsdata['user_id']);
				$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
				
				$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".(int)$ipsdata['deal_id']);
				
				$data['pMerBillNo'] = $pMerBillNo;
				$data['pContractNo'] = $str3Req["pContractNo"];
				$data['pP2PBillNo'] = $data['pP2PBillNo'];
				$data['user_id'] = $user_id;
				$data['user_name'] = $user['user_name'];
				$data['deal_id'] = $ipsdata['deal_id'];
				$data['money'] = $data['pTransferAmt'];
				
				$insertdata = return_deal_load_data($data,$user,$deal);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load",$insertdata,"INSERT");
				$load_id = $GLOBALS['db']->insert_id();
				if($load_id > 0){				
					require APP_ROOT_PATH.'app/Lib/deal_func.php';
					dobid2_ok($ipsdata['deal_id'], $user_id);	
					return $ipsdata;			
				}
				
				//$user_id = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."ips_register_creditor where ".$where));
				//$GLOBALS['db']->query("update ".DB_PREFIX."user set ips_acct_no = '".$data['pIpsAcctNo']."' where id = ".$user_id);
			}
		}
		else{
			
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_register_creditor where ".$where);
			return $ipsdata;
		}
	}	
	
?>