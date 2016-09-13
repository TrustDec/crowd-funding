<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function GuaranteeUnfreezeXml($IpsAcct,$pWebUrl,$pS2SUrl){	
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsAcct['pMerBillNo']."</pMerBillNo>"
				."<pBidNo>".$IpsAcct['pBidNo']."</pBidNo>"
				."<pUnfreezeDate>".$IpsAcct['pUnfreezeDate']."</pUnfreezeDate>"
				."<pUnfreezeAmt>".$IpsAcct['pUnfreezeAmt']."</pUnfreezeAmt>"
				."<pUnfreezenType>".$IpsAcct['pUnfreezenType']."</pUnfreezenType>"
				."<pAcctType>".$IpsAcct['pAcctType']."</pAcctType>"
				."<pIdentNo>".$IpsAcct['pIdentNo']."</pIdentNo>"
				."<pRealName>".$IpsAcct['pRealName']."</pRealName>"
				."<pIpsAcctNo>".$IpsAcct['pIpsAcctNo']."</pIpsAcctNo>"
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
	 * 解冻保证金
	 * @param int $deal_id 标的号
	 * @param int $pUnfreezenType 解冻类型 否 1#解冻借款方；2#解冻担保方
	 * @param float $money 解冻金额;默认为0时，则解冻所有未解冻的金额
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function GuaranteeUnfreeze($deal_id,$pUnfreezenType, $money, $MerCode,$cert_md5,$ws_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=GuaranteeUnfreeze";//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=GuaranteeUnfreeze";//s2s方式返回		
	
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	

		if ($pUnfreezenType == 1){
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($deal['user_id']));
		}else{
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($deal['agency_id']));
		}		
		
		
		$data = array();
		$data['deal_id'] = $deal_id;
		$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
		$data['pMerBillNo'] = $deal_id.'U'.get_gmtime();//商户系统唯一丌重复 ',
		$data['pBidNo'] = $deal_id; //'标的号，商户系统唯一丌重复',
		$data['pUnfreezeDate'] = to_date(get_gmtime(),'Ymd');//'解冻日期格 式：yyyymmdd',
		
		$data['pUnfreezenType'] = $pUnfreezenType;//'解冻类型 否 1#解冻借款方；2#解冻担保方',
		
		
		$money = floatval($money);
		if ($money == 0){
			if ($pUnfreezenType == 1){				
				$money = $deal['real_freezen_amt'] - $deal['un_real_freezen_amt'];//'解冻金额 金额单位，丌能为负，丌允许为0 累计解冻金额  <= 当时冻结时的保证金',
			}else{
				$money = $deal['guarantor_real_freezen_amt'] - $deal['un_guarantor_real_freezen_amt'];
			}			
		}
		
		$data['pUnfreezeAmt'] = str_replace(',', '', number_format($money,2));
		
		$data['pAcctType'] = 1;//'解冻者账户类型 否 0#机构；1#个人',
		
		$data['pIdentNo'] = $user['idno'];//'解冻者证件号码 是/否 解冻者账户类型1时：真实身份证（个人），必填 解冻账户类型0时：为空处理 ',
		$data['pRealName'] = $user['real_name'];//'解冻者姓名 否 账户类型为1时，真实姓名（中文） 账户类型为0时，开户时在IPS登记的商户名称 '		
		$data['pIpsAcctNo'] = $user['ips_acct_no'];//'解冻者IPS账号 否 账户类型为1时，IPS个人托管账户号 账户类型为0时，由IPS颁发的商户号',
		
		
		
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_guarantee_unfreeze",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = GuaranteeUnfreezeXml($data,$pWebUrl,$pS2SUrl);

		//echo $strxml;exit;
		
		$Crypt3Des=new Crypt3Des();//new 3des class
		$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
	
		
		
		$str=$MerCode.$p3DesXmlPara.$cert_md5;
		$pSign=md5($str);
	
		try {
			$url=$ws_url;
			$client = new SoapClient($url);
			$param = array('argMerCode'=>$MerCode,'arg3DesXmlPara'=>$p3DesXmlPara,'argSign'=>$pSign);
			$arrResult = $client->GuaranteeUnfreeze($param);
			$resultStr = $arrResult->GuaranteeUnfreezeResult;
						
			
			require_once(APP_ROOT_PATH.'system/collocation/ips/ips.php');
			require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
			$result = @XML_unserialize($resultStr);
			$result = $result['pReq'];
			wsnotify($result,'GuaranteeUnfreeze',$cert_md5);
							
			$result['resultStr'] = $resultStr;				
			return $result;
			
		} catch (SOAPFault $e) {
			print $e;
			//file_put_contents(PATH_LOG_FILE,PATH.$e."\r\n",FILE_APPEND);
		}
	}
	

	//解冻保证金回调
	function GuaranteeUnfreezeCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3XmlParaInfo);
		

		$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_guarantee_unfreeze set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
			//操作成功
			$data = array();
			$data['pIpsBillNo'] = $str3Req["pIpsBillNo"]; //由IPS系统生成的唯一流水号
			$data['pIpsTime'] = $str3Req["pIpsTime"];//IPS处理时间 否 格式为：yyyyMMddHHmmss
	
			$pUnfreezenType = intval($str3Req["pUnfreezenType"]);//'解冻类型 否 1#解冻借款方；2#解冻担保方',
			$pUnfreezeAmt = floatval($str3Req["pUnfreezeAmt"]);
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_guarantee_unfreeze",$data,'UPDATE',$where);
			
			if ($pErrCode == 'MG00000F'){
				$deal_id = intval($GLOBALS['db']->getOne("select deal_id from ".DB_PREFIX."ips_guarantee_unfreeze where ".$where));			
				if ($pUnfreezenType == 1){
					$GLOBALS['db']->query("update ".DB_PREFIX."deal set un_real_freezen_amt = un_real_freezen_amt +".$pUnfreezeAmt." where id = ".$deal_id);
				}else if($pUnfreezenType == 2){
					$GLOBALS['db']->query("update ".DB_PREFIX."deal set un_guarantor_real_freezen_amt = un_guarantor_real_freezen_amt +".$pUnfreezeAmt." where id = ".$deal_id);
				}
			}
		}
	}	
	
?>