<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RegisterSubjectXml($IpsSubject,$pWebUrl,$pS2SUrl){		

		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsSubject['pMerBillNo'] ."</pMerBillNo>"
				."<pBidNo>".$IpsSubject['pBidNo']."</pBidNo>"
				."<pRegDate>".$IpsSubject['pRegDate']."</pRegDate>"
				."<pLendAmt>".$IpsSubject['pLendAmt'] ."</pLendAmt>"
				."<pGuaranteesAmt>".$IpsSubject['pGuaranteesAmt']."</pGuaranteesAmt>"
				."<pTrdLendRate>".$IpsSubject['pTrdLendRate']."</pTrdLendRate>"
				."<pTrdCycleType>".$IpsSubject['pTrdCycleType']."</pTrdCycleType>"
				."<pTrdCycleValue>".$IpsSubject['pTrdCycleValue']."</pTrdCycleValue>"
				."<pLendPurpose>".$IpsSubject['pLendPurpose']."</pLendPurpose>"
				."<pRepayMode>".$IpsSubject['pRepayMode']."</pRepayMode>"
				."<pOperationType>".$IpsSubject['pOperationType']."</pOperationType>"
				."<pLendFee>".$IpsSubject['pLendFee']."</pLendFee>"
				."<pAcctType>".$IpsSubject['pAcctType']."</pAcctType>"
				."<pIdentNo>".$IpsSubject['pIdentNo']."</pIdentNo>"
				."<pRealName>".$IpsSubject['pRealName']."</pRealName>"
				."<pIpsAcctNo>".$IpsSubject['pIpsAcctNo']."</pIpsAcctNo>"
				."<pWebUrl><![CDATA[".$pWebUrl."]]></pWebUrl>"
				."<pS2SUrl><![CDATA[".$pS2SUrl."]]></pS2SUrl>"
				."<pMemo1><![CDATA[".$IpsSubject['pMemo1']."]]></pMemo1>"
				."<pMemo2><![CDATA[".$IpsSubject['pMemo2']."]]></pMemo2>"
				."<pMemo3><![CDATA[".$IpsSubject['pMemo3']."]]></pMemo3>"
				."</pReq>";
				
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	

	/**
	 * 标的登记 及 流标
	 * @param int $deal_id
	 * @param int $pOperationType 标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻
	 * @param int $status; 0:新增; 1:标的正常结束; 2:流标结束
	 * @param string $status_msg 主要是status_msg=2时记录的，流标原因
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function RegisterSubject($deal_id,$pOperationType,$status, $status_msg, $MerCode,$cert_md5,$post_url){
	
		if ($pOperationType == 0)  $pOperationType = 1;
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=RegisterSubject";//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=RegisterSubject";//s2s方式返回		
	
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($deal['user_id']));
		
		if ($pOperationType == 1){
		
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['status'] = $status;//$status; 0:新增; 1:标的正常结束; 2:流标结束
			$data['status_msg'] = $status_msg;//主要是status_msg=2时记录的，流标原因
			$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
			
			if ($pOperationType == 1){
				$data['pMerBillNo'] = $deal_id.'D'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
				$data['pRegDate'] = to_date($deal['start_time'],'Ymd');//'商户日期 否 格式：YYYYMMDD ',
			}else if ($pOperationType == 2){
				$data['pMerBillNo'] = $deal['mer_bill_no'];//'标的登记时提交的订单单号'
				$data['pRegDate'] = to_date($deal['start_time'],'Ymd');//'商户日期 否 格式：YYYYMMDD ',
			}
			
			$data['pBidNo'] = $deal_id;//'标的号，商户系统唯一不重复 ',
			
			$data['pLendAmt'] =  str_replace(',', '', number_format($deal['borrow_amount'],2));// '借款金额 否 金额单位，丌能为负，丌允许为0； 借款金额  <= 10000.00万 关于N(9,2)见4.1补充说明 ',
			$data['pGuaranteesAmt'] = str_replace(',', '', number_format($deal['guarantees_amt'],2));// '借款保证金，允许冻结的金额，金额单位，丌能为负，允 许为0； 借款保证金  <= 10000.00万 ',
			$data['pTrdLendRate'] = str_replace(',', '',number_format($deal['rate']+10,2));//'借款利率 否 金额单位，丌能为负，允许为0； 借款利率  < 48%，例如：45.12%传入 45.12 ',

			// '借款周期类型 否 借款周期类型，1：天；3：月； 借款周期 <= 5年',
			if ($deal['repay_time_type'] == 0)
				$data['pTrdCycleType'] = 1;
			else
				$data['pTrdCycleType'] = 3;
			
			// '借款周期值 否 借款周期值 借款周期 <= 5年。 如果借款周期类型为天，则借款周期值<= 1800(360 * 5)；如果借款周期类型为月，则借款周期值<= 60(12 * 5) ',
			$data['pTrdCycleValue'] = $deal['repay_time'];
			
			$pLendPurpose = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_loan_type where id = ".intval($deal['type_id']));
			$data['pLendPurpose'] = $pLendPurpose;// '借款用途',
			
			// '还款方式，1：等额本息，2：按月还息到期还本；3：等 额本金；99：其他； ',
			if ($deal['loantype'] == 0){
				$data['pRepayMode'] = 1;//等额本息
			}else if ($deal['loantype'] == 1){
				$data['pRepayMode'] = 2;//付息还本
			}else if($deal['loantype'] == 2){
				$data['pRepayMode'] = 99;//到期本息
			}else{
				$data['pRepayMode'] = 99;
			}
					
			$data['pOperationType'] = $pOperationType;// '标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻。 ',
			$data['pLendFee'] = str_replace(',', '', number_format(floatval($deal['services_fee']) / 100 * $deal['borrow_amount'],2));// '借款人手续费 否 金额单位，丌能为负，允许为0 这里是平台向借款人收取的费用 ',
			$data['pAcctType'] = 1;// '账户类型 否 0#机构（暂未开放） ；1#个人 ',
			$data['pIdentNo'] = $user['idno'];// '证件号码 否 真实身份证（个人）/由IPS颁发的商户号 ',
			$data['pRealName'] = $user['real_name'];// '姓名 否 真实姓名（中文）',
			$data['pIpsAcctNo'] = $user['ips_acct_no'];// 'IPS账户号 否 账户类型为1时，IPS托管账户号（个人） 账户类型为0时，由IPS颁发的商户号 ',
			
			//print_r($data);exit;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_subject",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
			
		}else{
			$where = " pMerBillNo = '".$deal['mer_bill_no']."'";
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_register_subject where ".$where);
			
			$id = intval($ipsdata['id']);
			
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['status'] = $status;//$status; 0:新增; 1:标的正常结束; 2:流标结束
			$data['status_msg'] = $status_msg;//主要是status_msg=2时记录的，流标原因
			$data['pMerCode'] = $MerCode;// '“平台”账号 否 由IPS颁发的商户号 ',
				
			$data['pMerBillNo'] = $ipsdata['pMerBillNo'];//'标的登记时提交的订单单号'
				
			$data['pBidNo'] = $deal_id;//'标的号，商户系统唯一不重复 ',
			$data['pRegDate'] = to_date(NOW_TIME,'Ymd'); //'商户日期 否 格式：YYYYMMDD ',
			$data['pLendAmt'] =  str_replace(',', '', number_format($ipsdata['pLendAmt'],2));// '借款金额 否 金额单位，丌能为负，丌允许为0； 借款金额  <= 10000.00万 关于N(9,2)见4.1补充说明 ',
			$data['pGuaranteesAmt'] = str_replace(',', '', number_format($ipsdata['pGuaranteesAmt'],2));// '借款保证金，允许冻结的金额，金额单位，丌能为负，允 许为0； 借款保证金  <= 10000.00万 ',
			$data['pTrdLendRate'] = str_replace(',', '',number_format($ipsdata['pTrdLendRate'],2));//'借款利率 否 金额单位，丌能为负，允许为0； 借款利率  < 48%，例如：45.12%传入 45.12 ',
			$data['pTrdCycleType'] = $ipsdata['pTrdCycleType'];	
			$data['pTrdCycleValue'] = $ipsdata['pTrdCycleValue'];// '借款周期值 否 借款周期值 借款周期 <= 5年。 如果借款周期类型为天，则借款周期值<= 1800(360 * 5)；如果借款周期类型为月，则借款周期值<= 60(12 * 5) ',
				
			$data['pLendPurpose'] = $ipsdata['pLendPurpose'];// '借款用途',
			$data['pRepayMode'] = $ipsdata['pRepayMode'];
				
			$data['pOperationType'] = $pOperationType;// '标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻。 ',
			$data['pLendFee'] =  $ipsdata['pLendFee'];// '借款人手续费 否 金额单位，丌能为负，允许为0 这里是平台向借款人收取的费用 ',
			$data['pAcctType'] =  $ipsdata['pAcctType'];// '账户类型 否 0#机构（暂未开放） ；1#个人 ',
			$data['pIdentNo'] =  $ipsdata['pIdentNo'];// '证件号码 否 真实身份证（个人）/由IPS颁发的商户号 ',
			$data['pRealName'] =  $ipsdata['pRealName'];// '姓名 否 真实姓名（中文）',
			$data['pIpsAcctNo'] =  $ipsdata['pIpsAcctNo'];// 'IPS账户号 否 账户类型为1时，IPS托管账户号（个人） 账户类型为0时，由IPS颁发的商户号 ',
				
			//print_r($data);exit;
				
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_subject",$data,'UPDATE',"id =".$id);
			
		}
		
		
		
		
		if ($id > 0){
			$data['pMemo1'] = $id;
			
			$subject = array();
			$subject['pMemo1'] = $id;
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_subject",$subject,'UPDATE',"id =".$id);
			//print_r($data);exit();
			$strxml = RegisterSubjectXml($data,$pWebUrl,$pS2SUrl);
			//echo $strxml;exit;
			
			$Crypt3Des=new Crypt3Des();//new 3des class
			$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
		
			$str=$MerCode.$p3DesXmlPara.$cert_md5;
			
			//print_r($cert_md5); exit;
			
			$pSign=md5($str);
		
			$html = '
			<form name="form1" id="form1" method="post" action="'.$post_url.'registerSubject.aspx" target="_self">
			<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
			<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
			<input type="hidden" name="pSign" value="'.$pSign.'" />
			</form>
			<script language="javascript">document.form1.submit();</script>';
			//echo $html; exit;
			
			$ips_log = array();
			$ips_log['code'] = 'RegisterSubject';
			$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$ips_log['strxml'] =$strxml;
			$ips_log['html'] = $html;
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
			
			return $html;
		}else{
			return '数据插入错误';
		}
	
	}
	
	//标的登记回调
	function RegisterSubjectCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3Req);
		
		$id = intval($str3Req["pMemo1"]);
		$where = " id = '".$id."'";
		
		
		$pMerBillNo = $str3Req["pMerBillNo"];
		//$where = " pMerBillNo = '".$pMerBillNo."'";
		
		
		$sql = "update ".DB_PREFIX."ips_register_subject set is_callback = is_callback + 1 where ".$where;
		//echo $sql; exit;
		
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
		
			//标的操作类型，1：新增，2：结束
			$pOperationType = $str3Req["pOperationType"];
			
			
			//操作成功
			$data = array();
			$data['pIpsBillNo'] = $str3Req["pIpsBillNo"];
			$data['pIpsTime'] = $str3Req["pIpsTime"]; //'IPS处理时间 否 格式为：yyyyMMddHHmmss ',
			$data['pBidStatus'] = $str3Req["pBidStatus"]; // '标的状态，1：新增；2：募集中；3：迚 行中；8：结束处理中；9：失败；10：结 束；',
			$data['pRealFreezenAmt'] = $str3Req["pRealFreezenAmt"]; // '实际冻结金额，金额单位，不能为负，不允许为0； 实际冻结金额 = 保证金',
	
			$data['pErrCode'] = $pErrCode; //MG02500F标的新增；（登记标的时同步返回）   MG02501F标的募集中；（登记标的成功后异步返回）   MG02503F 标的结束处理中；（登记结束标的时同步返 回）   MG02504F标的失败；   MG02505F标的结束(登记结束标的成功后异步返回)
			$data['pErrMsg'] = $pErrMsg;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_register_subject",$data,'UPDATE',$where);
			/*
			 MG02500F标的新增；（登记标的时同步返回）
			MG02501F标的募集中；（登记标的成功后异步返回） 
			MG02503F 标的结束处理中；（登记结束标的时同步返 回）
			MG02504F标的失败；
			MG02505F标的结束(登记结束标的成功后异步返回)
			*/
			
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_register_subject where ".$where);
			$deal_id = intval($ipsdata['deal_id']);
			
			if ($pErrCode == 'MG02501F'){				
				
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set mer_bill_no = '".$pMerBillNo."',ips_bill_no = '".$data['pIpsBillNo']."',real_freezen_amt = ".floatval($data['pRealFreezenAmt'])." where id = ".$deal_id);
			}else if ($pErrCode == 'MG02505F'){	
				//0:新增; 1:标的正常结束; 2:流标结束
				//print_r($ipsdata);exit;
				
				if ($ipsdata['status'] == 2){
					require_once APP_ROOT_PATH.'app/Lib/common.php';
					$result = do_received($deal_id,1,$ipsdata['status_msg']);
				}

				//本地解冻:借款保证金,担保保证金0
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_over = 1 ,un_real_freezen_amt = real_freezen_amt,un_guarantor_real_freezen_amt = guarantor_real_freezen_amt where id = ".$deal_id);				
			}
		}
	}	
	
?>