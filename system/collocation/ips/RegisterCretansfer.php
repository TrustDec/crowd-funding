<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RegisterCretansferXml($IpsData,$pWebUrl,$pS2SUrl){		
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsData['pMerBillNo']."</pMerBillNo>"
				."<pMerDate>".$IpsData['pMerDate']."</pMerDate>"
				."<pBidNo>".$IpsData['pBidNo']."</pBidNo>"
				."<pContractNo>".$IpsData['pContractNo']."</pContractNo>"
				."<pFromAccountType>".$IpsData['pFromAccountType']."</pFromAccountType>"
				."<pFromName>".$IpsData['pFromName']."</pFromName>"
				."<pFromAccount>".$IpsData['pFromAccount']."</pFromAccount>"
				."<pFromIdentType>".$IpsData['pFromIdentType']."</pFromIdentType>"
				."<pFromIdentNo>".$IpsData['pFromIdentNo']."</pFromIdentNo>"
				."<pToAccountType>".$IpsData['pToAccountType']."</pToAccountType>"
				."<pToAccountName>".$IpsData['pToAccountName']."</pToAccountName>"
				."<pToAccount>".$IpsData['pToAccount']."</pToAccount>"
				."<pToIdentType>".$IpsData['pToIdentType']."</pToIdentType>"
				."<pToIdentNo>".$IpsData['pToIdentNo']."</pToIdentNo>"
				."<pCreMerBillNo>".$IpsData['pCreMerBillNo']."</pCreMerBillNo>"
				."<pCretAmt>".$IpsData['pCretAmt']."</pCretAmt>"
				."<pPayAmt>".$IpsData['pPayAmt']."</pPayAmt>"
				."<pFromFee>".$IpsData['pFromFee']."</pFromFee>"
				."<pToFee>".$IpsData['pToFee']."</pToFee>"
				."<pCretType>".$IpsData['pCretType']."</pCretType>"	
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
	 * 登记债权转让
	 * @param int $transfer_id  转让id
	 * @param int $t_user_id  受让用户ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RegisterCretansfer($transfer_id,$t_user_id, $MerCode,$cert_md5,$post_url){
	
		$transfer = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_load_transfer where id = ".$transfer_id);
		$deal_id = intval($transfer['deal_id']);
		$user_id = intval($transfer['user_id']);	
		
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		$tuser = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$t_user_id);
		$user_load_transfer_fee = $GLOBALS['db']->getOne("SELECT user_load_transfer_fee FROM ".DB_PREFIX."deal WHERE id=".$deal_id);

		if (empty($user['ips_acct_no']) || empty($tuser['ips_acct_no'])){
			return '有一方未申请 ips 帐户';
		}
		
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=RegisterCretansfer&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=RegisterCretansfer&from=".$_REQUEST['from'];//s2s方式返回		
	
		$sql = "update ".DB_PREFIX."deal_load_transfer set lock_user_id = ".$t_user_id.", lock_time =".NOW_TIME;
		$sql .= " where ips_status = 0 and t_user_id = 0 and status = 1 and (lock_user_id = 0 || lock_user_id =".$t_user_id." || (lock_user_id > 0 && lock_time < ".(NOW_TIME - 600)."))";
		$sql .= " and id = ".$transfer_id;
		//echo $sql; exit;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
			
			$data = array();
			$data['transfer_id'] = $transfer_id;
			$data['t_user_id'] = $t_user_id;
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] = $transfer_id.'T'.NOW_TIME;//'商户订单号 否 商户系统唯一不重复',
			$data['pMerDate'] = to_date(NOW_TIME,'Ymd');//'商户日期 否 格式：YYYYMMDD ',
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$pContractNo = $GLOBALS['db']->getOne("select pContractNo from ".DB_PREFIX."deal_load where id = ".intval($transfer['load_id']));
			$data['pContractNo'] = $pContractNo;// '合同号 否 原投资交易的合同号， 字母和数字，如a~z,A~Z,0~9 ',
			
			$data['pFromAccountType'] = 1;// '出让方账户类型 否 0：机构（暂不支持） 1：个人 ',
			$data['pFromName'] = $user['real_name'];// '出让方账户姓名 否 出让方账户真实姓名',
			$data['pFromAccount'] = $user['ips_acct_no'];// '出让方账户 否 出让方账户类型为1时，IPS托管账户号（个人） 出让方账户类型为0时，由IPS颁发的商户号 ',
			$data['pFromIdentType'] = 1;//'出让方证件类型 否 1#身份证，默认：1 ',
			$data['pFromIdentNo'] = $user['idno'];// '出让方证件号码 否 真实身份证（个人）/由IPS颁发的商户号（机构）',
			$data['pToAccountType'] = 1;// '受让方账户类型 否 1：个人  0：机构（暂不支持）',
			$data['pToAccountName'] = $tuser['real_name'];// '受让方账户姓名 否 受让方账户真实姓名 ',
			$data['pToAccount'] = $tuser['ips_acct_no'];// '受让方账户 否 受让方账户类型为1时，IPS托管账户号（个人）',
			$data['pToIdentType'] = 1;// '受让方证件类型 否 1#身份证，默讣：1 ',
			$data['pToIdentNo'] = $tuser['idno'];// '受让方证件号码 否 真实身份证（个人）/由IPS颁发的商户号（机构）',
			
			$pCreMerBillNo = $GLOBALS['db']->getOne("select pMerBillNo from ".DB_PREFIX."deal_load where id = ".intval($transfer['load_id']));
			$data['pCreMerBillNo'] = $pCreMerBillNo;//  '登记债权人时提 交的订单号 否 字母和数字，如a~z,A~Z,0~9 登记债权人时提交的订单号，见<登记债权人接口>请求 参数中的“pMerBillNo” ',
			$data['pCretAmt'] = str_replace(',', '', number_format($transfer['load_money'],2));//  '债权面额 否 金额单位元，不能为负，不允许为0 ',
			$data['pPayAmt'] = str_replace(',', '', number_format($transfer['transfer_amount'],2));// '支付金额 否 金额单位元，不能为负，不允许为0 债权面额（1-30%）<=支付金额<= 债权面额（1+30%） ',
			$data['pFromFee'] = str_replace(',', '', number_format($transfer['transfer_amount']*$user_load_transfer_fee*0.01,2));// '出让方手续费 否 金额单位元，不能为负，允许为0 ',
			$data['pToFee'] = '0.00';// '受让方手续费 否 金额单位元，不能为负，允许为0 ',
			$data['pCretType'] = 1;// '转让类型 否 1：全部转让 2：部分转让',
			
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_cretansfer",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
			
			$strxml = RegisterCretansferXml($data,$pWebUrl,$pS2SUrl);
			
			//echo $strxml;exit;
			
			$Crypt3Des=new Crypt3Des();//new 3des class
			$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
			
			
			
			$str=$MerCode.$p3DesXmlPara.$cert_md5;
			
			//print_r($cert_md5); exit;
			
			$pSign=md5($str);
			
			$html = '
				<form name="form1" id="form1" method="post" action="'.$post_url.'registerCretansfer.aspx" target="_self">
				<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
				<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
				<input type="hidden" name="pSign" value="'.$pSign.'" />
				</form>
				<script language="javascript">document.form1.submit();</script>';
			//echo $html; exit;
			
			$ips_log = array();
			$ips_log['code'] = 'RegisterCretansfer';
			$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$ips_log['strxml'] =$strxml;
			$ips_log['html'] = $html;
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
			
			return $html;
		}else{
			return '该债权转让已经被其它用户锁定';
		}		
	}
	
	//登记债权转让回调
	function RegisterCretansferCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3XmlParaInfo); die;
		
		
		$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";
		$sql = "update ".DB_PREFIX."ips_register_cretansfer set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){

			//操作成功
			$data = array();

			$data['pBussType']  = $str3Req["pBussType"];//业务类型 否 1：债权转让			
			$data['pStatus'] = $str3Req["pStatus"];//转让状态 否 0：新建 1：迚行中 10：成功  9： 失败			
			$data['pP2PBillNo'] = $str3Req["pP2PBillNo"];//IPS P2P订单号 否 由IPS系统生成的唯一流水号
			$data['pIpsTime'] = $str3Req["pIpsTime"];//IPS处理时间 否 格式为：yyyyMMddHHmmss
			
			$data['pErrCode'] = $pErrCode;
			$data['pErrMsg'] = $pErrMsg;
			
			
			//echo $where;exit;
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_cretansfer",$data,'UPDATE',$where);
			
			if ($pErrCode == 'MG00000F'){
					
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_register_cretansfer where ".$where);
				
				$transfer_id = intval($ipsdata['transfer_id']);
				$deal_id = intval($ipsdata['pBidNo']);
				
				//ips_status ips处理状态;0:未处理;1:已登记债权转让;2:已转让
				$sql = "update ".DB_PREFIX."deal_load_transfer set ips_status = 1, pMerBillNo = '".$pMerBillNo."' where ips_status = 0 and id =".$transfer_id;
				$GLOBALS['db']->query($sql);
				if ($GLOBALS['db']->affected_rows()){
					
					//$transfer = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."deal_load_transfer WHERE id=".$transfer_id);
					//更新相应的回款计划 del by chenfq 2015-01-09===》放在Transfer_4 的回调中处理
					//$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal_load_repay SET t_user_id='".$GLOBALS['user_info']['id']."' WHERE  user_id=".$transfer['user_id']." and load_id=".$transfer['load_id']." and repay_time >= ".$transfer['near_repay_time'] );
			
					
					//调用 转让 接口;
					$class_name = 'Ips';
					require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
					$collocation_class = $class_name."_collocation";
					$collocation_object = new $collocation_class();
					
					$collocation_code = $collocation_object->Transfer(4, $deal_id, $transfer_id);
					
					//return $collocation_code;
					print_r($collocation_code);
				}				
			}
		}
		
		
	}	
	
?>