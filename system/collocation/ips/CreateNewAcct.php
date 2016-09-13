<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function CreateNewAcctXml($IpsAcct,$pWebUrl,$pS2SUrl){		
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>" .$IpsAcct['pMerBillNo'] ."</pMerBillNo>"
				."<pIdentType>" .$IpsAcct['pIdentType'] ."</pIdentType>"
				."<pIdentNo>" .$IpsAcct['pIdentNo'] ."</pIdentNo>"
				."<pRealName>" .$IpsAcct['pRealName'] ."</pRealName>"
				."<pMobileNo>" .$IpsAcct['pMobileNo'] ."</pMobileNo>"
				."<pEmail>" .$IpsAcct['pEmail'] ."</pEmail>"
				."<pSmDate>" .$IpsAcct['pSmDate'] ."</pSmDate>"
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
	 * 创建新帐户
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function CreateNewAcct($user_id,$user_type,$MerCode,$cert_md5,$post_url){
	
		
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=CreateNewAcct&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=CreateNewAcct&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$data = array();
		$data['user_type'] = $user_type;
		$data['user_id'] = $user_id;
		$data['argMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $user_id.'U'.get_gmtime();//$user_id;//'pMerBillNo商户开户流水号 否 商户系统唯一丌重复 针对用户在开户中途中断（开户未完成，但关闭了IPS开 户界面）时，必须重新以相同的商户订单号发起再次开户 ',
		$data['pIdentType'] = 1;//'证件类型 否 1#身份证，默认：1',
		$data['pIdentNo'] = $user['idno'];//'证件号码 否 真实身份证 ',
		$data['pRealName'] = $user['real_name'];//'姓名 否 真实姓名（中文） '
		$data['pMobileNo'] = $user['mobile'];;//'手机号 否 用户发送短信 '
		$data['pEmail'] = $user['email'];//'注册邮箱 否 用于登录账号，IPS系统内唯一丌能重复',
		$data['pSmDate'] = to_date(get_gmtime(),'Ymd');//'提交日期 否 时间格式“yyyyMMdd”,商户提交日期,。如：20140323 ',
	
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_create_new_acct",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = CreateNewAcctXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		
		//print_r($cert_md5); exit;
		
		$pSign=md5($str);
	
		$html = '
		<form name="form1" id="form1" method="post" action="'.$post_url.'CreateNewIpsAcct.aspx" target="_self">
		<input type="hidden" name="argMerCode" value="'.$MerCode.'" />
		<input type="hidden" name="arg3DesXmlPara" value="'.$p3DesXmlPara.'" />
		<input type="hidden" name="argSign" value="'.$pSign.'" />
		</form>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		return $html;
	
	}
	
	//创建新帐户回调
	function CreateNewAcctCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3XmlParaInfo);
		$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_create_new_acct set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
			//操作成功
			$data = array();
			$data['pStatus'] = $str3Req["pStatus"]; //2 开户状态 否 状态：10#开户成功，5#注册超时，9#开户失败。
			$data['pBankName'] = $str3Req["pMerCode"];//64 银行名称 是/否
			$data['pBkAccName']  = $str3Req["pMerCode"];//50 户名 是/否
			$data['pBkAccNo'] = $str3Req["pBkAccNo"];// 4 银行卡账号 是/否
			$data['pCardStatus'] = $str3Req["pCardStatus"];// 1 身份证状态 是/否				
			$data['pIpsAcctNo'] = $str3Req["pIpsAcctNo"];//pIpsAcctNo 30 IPS托管平台账 户号是/否 pErrCode 返回状态为 MG00000F 时返回，由 IPS生成颁发的资金账号。 
			$data['pIpsAcctDate'] = $str3Req["pIpsAcctDate"];// 8 IPS开户日期 否 pErrCode 返回状态为 MG00000F 时返回，格 式：yyyymmdd
			
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_create_new_acct",$data,'UPDATE',$where);
			
			if ($pErrCode == 'MG00000F'){
				$user_id = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."ips_create_new_acct where ".$where));
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user set ips_acct_no = '".$data['pIpsAcctNo']."' where id = ".$user_id);
					
				return 	$user_type;		
			}
		}
	}	
	
?>