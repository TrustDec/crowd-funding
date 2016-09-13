<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function RepaymentNewTradeXml($IpsData,$pDetails,$pWebUrl,$pS2SUrl){
		
		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pBidNo>".$IpsData['pBidNo']."</pBidNo>"
				."<pRepaymentDate>".$IpsData['pRepaymentDate']."</pRepaymentDate>"
				."<pMerBillNo>".$IpsData['pMerBillNo']."</pMerBillNo>"
				."<pRepayType>".$IpsData['pRepayType']."</pRepayType>"
				."<pIpsAuthNo>".$IpsData['pIpsAuthNo']."</pIpsAuthNo>"
				."<pOutAcctNo>".$IpsData['pOutAcctNo']."</pOutAcctNo>"
				."<pOutAmt>".$IpsData['pOutAmt']."</pOutAmt>"
				."<pOutFee>".$IpsData['pOutFee']."</pOutFee>"
				."<pDetails>".$pDetails."</pDetails>"
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
	
	function RepaymentNewTradeRowXml($IpsData){
		$strxml = "<pRow>"
				."<pCreMerBillNo>".$IpsData['pCreMerBillNo']."</pCreMerBillNo>"
				."<pInAcctNo>".$IpsData['pInAcctNo']."</pInAcctNo>"
				."<pInFee>".$IpsData['pInFee']."</pInFee>"
				."<pOutInfoFee>".$IpsData['pOutInfoFee']."</pOutInfoFee>"
				."<pInAmt>".$IpsData['pInAmt']."</pInAmt>"
				."</pRow>";
	
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\
		return $strxml;
	}
	
	/**
	 * 还款
	 * @param deal $deal  标的数据
	 * @param array $repaylist  还款列表
	 * @param int $deal_repay_id  还款计划ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RepaymentNewTrade($deal, $repaylist, $deal_repay_id, $MerCode,$cert_md5,$post_url){

		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=RepaymentNewTrade&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=RepaymentNewTrade&from=".$_REQUEST['from'];//s2s方式返回		
	
		
		
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$deal['user_id']);

		$deal_id = $deal['id'];
		
			$pOutAmt = 0;
			$pOutFee = 0;
			
			
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['deal_repay_id'] = $deal_repay_id;
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] = $deal_id.'RT'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
			$data['pRepaymentDate'] = to_date(get_gmtime(),'Ymd');//还款日期   格式：YYYYMMDD,
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$data['pRepayType'] = 1;//'还款类型，1#手动还款，2#自动还款',
			$data['pIpsAuthNo'] = '';//授权号   是/否   当还款类型为自动还款时不为空，为手动还款时为空
			$data['pOutAcctNo'] = $user['ips_acct_no'];//转出方IPS账号   否   借款人在IPS注册的资金托管账号
			$data['pOutAmt'] = 0;//'转出金额   否   表示此次还款总金额。   转出金额=Sum(pInAmt)   Sum(pInAmt)代表转入金额的合计，一个或多个 投资人时的还款金额的累加。   金额单位：元，不能为负，不允许为 0，保留 2 位小 数；   格式：12.00 ',
			$data['pOutFee'] = 0;//'转出方总手续费   否   表示此次借款人或担保人所承担的还款手续费，此手 续费由商户平台向用户收取。   金额单位：元，不能为负，允许为0，保留 2位小数；   格式：12.00  pOutFee  =  Sum(pOutInfoFee)   Sum(pOutInfoFee)代表转出方手续费的合计   ',
			//print_r($repaylist);die();
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_repayment_new_trade",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
						
			
			if ($id > 0){
				$result['data'] = $data;
				$details = array();
			
				$result['id'] = $id;
				
				foreach($repaylist as $k=>$v){
			
					$detail = array();					
					$detail['pid'] = $id;					
					$detail['deal_load_repay_id'] = $v['id'];
					$detail['impose_money'] = round($v['impose_money'],2);					
					$detail['repay_manage_impose_money'] = $v['repay_manage_impose_money'];
					$detail['repay_status'] = intval($v['status']) - 1;		
					$detail['true_repay_time'] = NOW_TIME;		
					
					//$detail['self_money'] = round($v['self_money'],2);
					//$detail['manage_interest_money'] = 	$v['manage_interest_money'];
					
					
					//转出方手续费  ===》收取：借款者 的管理费 + 管理逾期罚息   $item['repay_manage_money']  + $item['repay_manage_impose_money']
					//转入方手续费  ===》收取：投资者 的管理费  $item['manage_money']
					//转入金额 ===》还款金额 + 逾期罚息 $item['month_repay_money'] + $item['impose_money']
					
					
					$detail['pInFee'] =  str_replace(',', '',number_format(round($v['manage_money']+$v['manage_interest_money'],2),2));;//转入方手续费   否   表示此次还款债权人所承担的还款手续费，此手续费由商 户平台向用户收取。金额单位：元，不能为负，允许为0，保留2位小数；   格式：12.00
					
					
					$detail['pOutInfoFee'] = str_replace(',', '',number_format(round($v['repay_manage_money'] + $v['repay_manage_impose_money'],2),2));//转出方手续费   否   表示此次借款人或担保人所承担的还款明细手续费，此手 续费由商户平台收取。；
					
					$detail['pInAmt'] = str_replace(',', '',number_format(round($v['month_repay_money'] + $v['impose_money'],2),2));//转入金额   否   格式：0.00    必须大于0  且大于转入方手续费
					
					if ($v['t_user_id']){
						//债权转让后,还款时，转给：承接者, 在债权转让后需要更新 fanwe_deal_load_repay.t_user_id 数据值
						$pInAcctNo = $v['t_ips_acct_no'];
						
						$detail['pCreMerBillNo'] = $v['t_pMerBillNo'];//登记债权人时提 交的订单号   否   登记债权人时提交的订单号，见<登记债权人接口>请求 参数中的“pMerBillNo” '
					
					}else{
						$pInAcctNo = $v['ips_acct_no'];
						$detail['pCreMerBillNo'] = $v['pMerBillNo'];//登记债权人时提 交的订单号   否   登记债权人时提交的订单号，见<登记债权人接口>请求 参数中的“pMerBillNo” '
					
					}
					
					$detail['pInAcctNo'] = $pInAcctNo;//转出方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转出方（投资人）；  转账类型，2：代偿，此为转出方（担保方）；  转账类型，3：代偿还款，此为转出方（借款人）；  转账类型，4：债权转让，此为转出方（受让方）；  转账类型，5：结算担保收益，此为转出方（借款人）；  '
					
					$pOutAmt = $pOutAmt + $detail['pInAmt'];
					$pOutFee = $pOutFee + $detail['pOutInfoFee'];
					
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_repayment_new_trade_detail",$detail,'INSERT');
			
					$details[] = $detail;
				}
							
				
				$data2['pOutAmt'] = str_replace(',', '',number_format($pOutAmt,2));//'转出金额   否   表示此次还款总金额。   转出金额=Sum(pInAmt)   Sum(pInAmt)代表转入金额的合计，一个或多个 投资人时的还款金额的累加。   金额单位：元，不能为负，不允许为 0，保留 2 位小 数；   格式：12.00 ',
				$data2['pOutFee'] = str_replace(',', '',number_format($pOutFee,2));//'转出方总手续费   否   表示此次借款人或担保人所承担的还款手续费，此手 续费由商户平台向用户收取。   金额单位：元，不能为负，允许为0，保留 2位小数；   格式：12.00  pOutFee  =  Sum(pOutInfoFee)   Sum(pOutInfoFee)代表转出方手续费的合计   ',
				
				$data['pOutAmt'] = $data2['pOutAmt'];
				$data['pOutFee'] = $data2['pOutFee'];
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."ips_repayment_new_trade",$data2,'UPDATE',' id = '.$id);
				
			}
			
			//print_r($repaylist);die();
			$pDetails = '';
			foreach($details as $k=>$v){
				$pDetails .= RepaymentNewTradeRowXml($v);
			}
			
			$strxml = RepaymentNewTradeXml($data,$pDetails,$pWebUrl,$pS2SUrl);
			
			//echo $strxml;exit;
			
			$Crypt3Des=new Crypt3Des();//new 3des class
			$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密
			
			
			
			$str=$MerCode.$p3DesXmlPara.$cert_md5;
			
			//print_r($cert_md5); exit;
			
			$pSign=md5($str);
			
			$html = '
				<form name="form1" id="form1" method="post" action="'.$post_url.'RepaymentNewTrade.aspx" target="_self">
				<input type="hidden" name="pMerCode" value="'.$MerCode.'" />
				<input type="hidden" name="p3DesXmlPara" value="'.$p3DesXmlPara.'" />
				<input type="hidden" name="pSign" value="'.$pSign.'" />
				</form>
				<script language="javascript">document.form1.submit();</script>';
			//echo $html; exit;
			
			$ips_log = array();
			$ips_log['code'] = 'RepaymentNewTrade';
			$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$ips_log['strxml'] =$strxml;
			$ips_log['html'] = $html;
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
			
			return $html;
			
	}
	
	//还款回调
	function RepaymentNewTradeCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3Req);
		
		
		$pMerBillNo = $str3Req["pMerBillNo"];
		$where = " pMerBillNo = '".$pMerBillNo."'";

		$data = array();

		$data['pErrCode'] = $pErrCode;//MG00008F IPS受理中
		$data['pErrMsg'] = $pErrMsg;
		
		$data['pIpsBillNo']  = $str3Req["pIpsBillNo"];//IPS还款订单号  否  由 IPS 系统生成的唯一流水号， 此次还款的批次号					
		$data['pIpsDate'] = $str3Req["pIpsDate"];//IPS受理日期  否  yyyyMMdd
		
		if ($data['pErrCode'] == 'MG00008F')
			$data['pOutIpsFee'] = $str3Req["pOutIpsFee"];//收取转出方手 续费  此手续费由平台商户垫付给 IPS 的手续费			
					
		//echo $where;exit;
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_repayment_new_trade",$data,'UPDATE',$where);
			
		
		if ($data['pErrCode'] == 'MG00008F'){
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_repayment_new_trade where ".$where);
			
			//has_repay 0未还,1已还 2部分还款3IPS受理中
			//,lock_time = ".get_gmtime()."
			$sql = "update ".DB_PREFIX."deal_repay set has_repay = 3 where has_repay in (0,2) and id = ".$ipsdata['deal_repay_id'];			
			$GLOBALS['db']->query($sql);
			
		}
		
			//MG00000F操作成功
		if ($data['pErrCode'] == 'MG00000F'){
			$sql = "update ".DB_PREFIX."ips_repayment_new_trade set is_callback = 1 where is_callback = 0 and ".$where;
			$GLOBALS['db']->query($sql);
			if ($GLOBALS['db']->affected_rows()){	
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_repayment_new_trade where ".$where);
				//require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				//$pDetails = @XML_unserialize($pDetails);
				
				//print_r($pDetails);
				
				//$pDetails = getXmlNodeValue($str3XmlParaInfo, "pDetails");
				$guarantor_real_fit_amt = 0;
				
				$attr = array();	
				if(isset($str3Req["pDetails"]["pRow"][0])){
					$attr = $str3Req["pDetails"]["pRow"];
				}
				else{
					$attr[] = $str3Req["pDetails"]["pRow"];
				}
				
				
				foreach($attr as $k=>$v){
					$pCreMerBillNo = $v["pCreMerBillNo"];
					$where = " pid = ".$ipsdata['id']. " and pCreMerBillNo = '".$pCreMerBillNo."'";

					$ips_detail = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_repayment_new_trade_detail where ".$where);
					
					
					$detail = array();
					$detail['pStatus'] = $v["pStatus"];//Y#转账成功；N#转账失败
					$detail['pMessage'] = $v["pMessage"];//转账备注  否  转账失败的原因
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_repayment_new_trade_detail",$detail,'UPDATE'," id = ".intval($ips_detail['id']));
					
					
					if ($v["pStatus"] == 'Y'){		

						$load_repay = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_load_repay where id = ".$ips_detail['deal_load_repay_id']);
						
						$detail = array();
						$detail['repay_manage_impose_money'] = $ips_detail["repay_manage_impose_money"];//平台收取 借款者 的管理费逾期罚息
						$detail['impose_money'] = $ips_detail["impose_money"];//投资者收取 借款者 的逾期罚息
						$detail['status'] = $ips_detail["repay_status"];//还款状态
						$detail['true_repay_time'] = $ips_detail["true_repay_time"];//还款时间
						$detail['true_repay_date'] = to_date($ips_detail["true_repay_time"]);
							
							
						$detail['has_repay'] = 1;//0未收到还款，1已收到还款
						$detail['true_manage_money'] = $load_repay['manage_money'];
						$detail['true_manage_interest_money'] = $load_repay["manage_interest_money"];
						$detail['true_repay_manage_money'] = $load_repay["repay_manage_money"];
						$detail['true_repay_money'] =$load_repay["repay_money"];
						$detail['true_self_money'] = $load_repay['self_money'];
						$detail['true_interest_money'] =  $load_repay['interest_money'];
						
						/*
						$detail = array();
						$detail['repay_manage_impose_money'] = $ips_detail["repay_manage_impose_money"];
						$detail['true_manage_money'] = floatval($ips_detail["pInFee"]) - floatval($ips_detail["manage_interest_money"]);
						$detail['true_manage_interest_money'] = $ips_detail["manage_interest_money"];
						$detail['true_repay_manage_money'] = floatval($ips_detail["pOutInfoFee"]) - floatval($detail['repay_manage_impose_money']);
						$detail['true_repay_money'] = $ips_detail['pInAmt'] - $ips_detail["impose_money"];
						$detail['true_self_money'] = $ips_detail['self_money'];
						$detail['true_interest_money'] = floatval($detail['true_repay_money']) - floatval($ips_detail['self_money']);
						$detail['impose_money'] = $ips_detail["impose_money"];
						$detail['has_repay'] = 1;//0未收到还款，1已收到还款
						$detail['status'] = $ips_detail["repay_status"];
						$detail['true_repay_time'] = $ips_detail["true_repay_time"];
						$detail['true_repay_date'] = to_date($ips_detail["true_repay_time"]);
						*/
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load_repay",$detail,'UPDATE'," has_repay = 0 and id = ".intval($ips_detail['deal_load_repay_id']));		
						
						//普通会员邀请返利
						get_referrals($ips_detail['deal_load_repay_id']);									
					}
				}
				
				//更新用户回款计划
				require_once APP_ROOT_PATH."app/Lib/deal.php";
				syn_deal_repay_status($ipsdata['deal_id'],$ipsdata['deal_repay_id']);							
				
			}
		}
		return $ipsdata;	
	}	
	
?>