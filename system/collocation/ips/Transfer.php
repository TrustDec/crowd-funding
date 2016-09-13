<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function TransferXml($IpsData,$pDetails,$pS2SUrl){

		$strxml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
				."<pReq>"
				."<pMerBillNo>".$IpsData['pMerBillNo']."</pMerBillNo>"
				."<pBidNo>".$IpsData['pBidNo']."</pBidNo>"
				."<pDate>".$IpsData['pDate']."</pDate>"
				."<pTransferType>".$IpsData['pTransferType']."</pTransferType>"
				."<pTransferMode>".$IpsData['pTransferMode']."</pTransferMode>"
				."<pS2SUrl><![CDATA[" .$pS2SUrl."]]></pS2SUrl>"
				."<pDetails>".$pDetails."</pDetails>"
				."<pMemo1><![CDATA[" .$IpsData['pMemo1']."]]></pMemo1>"
				."<pMemo2><![CDATA[" .$IpsData['pMemo2']."]]></pMemo2>"
				."<pMemo3><![CDATA[" .$IpsData['pMemo3']."]]></pMemo3>"
				."</pReq>";
				
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	
	function TransferRowXml($IpsData){
		$strxml = "<pRow>"
				."<pOriMerBillNo>".$IpsData['pOriMerBillNo']."</pOriMerBillNo>"
				."<pTrdAmt>".$IpsData['pTrdAmt']."</pTrdAmt>"
				."<pFAcctType>".$IpsData['pFAcctType']."</pFAcctType>"
				."<pFIpsAcctNo>".$IpsData['pFIpsAcctNo']."</pFIpsAcctNo>"
				."<pFTrdFee>".$IpsData['pFTrdFee']."</pFTrdFee>"
				."<pTAcctType>".$IpsData['pTAcctType']."</pTAcctType>"
				."<pTIpsAcctNo>".$IpsData['pTIpsAcctNo']."</pTIpsAcctNo>"
				."<pTTrdFee>".$IpsData['pTTrdFee']."</pTTrdFee>"				
				."</pRow>";
	
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\
		return $strxml;
	}	

	
	/**
	 * 转帐
	 * @param int $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	 * @param int $deal_id  标的id	 
	 * @param string $ref_data 逗号分割的, 1：投资,填还款日期(int)  ; 2代偿，3代偿还款列表; 4债权转让: id; 5结算担保收益:金额，如果为0,则取fanwe_deal.guarantor_pro_fit_amt ;
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function Transfer($pTransferType, $deal_id, $ref_data, $MerCode,$cert_md5,$ws_url){
	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Ips&class_act=Transfer&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Ips&class_act=Transfer&from=".$_REQUEST['from'];//s2s方式返回		
	
		
		//deal_status 0待等材料，1进行中，2满标，3流标，4还款中，5已还清
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id);
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$deal['user_id']);

		if ($deal){
			$result = array('id' => 0, 'msg' => '');
			
			//echo $pTransferType; exit;
			
			if ($pTransferType == 1){
				//投资
				$result = Transfer_1($pTransferType, $deal, $ref_data, $user, $MerCode);
			}else if ($pTransferType == 4){
				//债权转让
				$result = Transfer_4($pTransferType, $deal, $ref_data, $MerCode);
			}else if ($pTransferType == 5){
				//担保收益
				$result = Transfer_5($pTransferType, $deal, $ref_data, $MerCode);
			}
			
			$id = intval($result['id']);
			if ($id > 0){	
				$result['data']['pMemo1'] = $id;
				
				$subject = array();
				$subject['pMemo1'] = $id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$subject,'UPDATE',"id =".$id);
				
				
				$pDetails = '';
				foreach($result['details'] as $k=>$v){
					$pDetails .= TransferRowXml($v);
				}
				
				$strxml = TransferXml($result['data'],$pDetails,$pS2SUrl);
				//echo $strxml;exit;
				
				$Crypt3Des=new Crypt3Des();//new 3des class
				$p3DesXmlPara=$Crypt3Des->DESEncrypt($strxml);//3des 加密

				$str=$MerCode.$p3DesXmlPara.$cert_md5;
				$pSign=md5($str);
				
				$ips_log = array();
				$ips_log['code'] = 'Transfer_'.$pTransferType;
				$ips_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
				$ips_log['strxml'] =$strxml;
				$ips_log['html'] = 'p3DesXmlPara:'.$p3DesXmlPara.';pSign:'.$pSign;
				$GLOBALS['db']->autoExecute(DB_PREFIX."ips_log",$ips_log);
				
				try {
					$url=$ws_url;
					$client = new SoapClient($url);
				    $param = array('pMerCode'=>$MerCode,'p3DesXmlPara'=>$p3DesXmlPara,'pSign'=>$pSign);
					$arrResult = $client->Transfer($param);
					$resultStr = $arrResult->TransferResult;
					
					require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
					$result = @XML_unserialize($resultStr);
					$result = $result['pReq'];
					require_once(APP_ROOT_PATH.'system/collocation/ips/ips.php');
						
					wsnotify($result,'Transfer',$cert_md5);
					
					$result['resultStr'] = $resultStr;
					$result['strxml'] = $strxml;
					
					return $result;
				} catch (SOAPFault $e) {
					return print_r($e,1);
				    //file_put_contents(PATH_LOG_FILE,PATH.$e."\r\n",FILE_APPEND);
				}
			}else{
				return $result['msg'];
			}
		}else{
			return '借款不存在';
		}		
	}
	
	//投资
	function Transfer_1($pTransferType, $deal, $ref_data, $user, $MerCode){
		$deal_id = intval($deal['id']);
		
		$result = array('id' => 0, 'msg' => '', 'data' => array(), 'details' => array());
		
		if ($pTransferType == 1 && $deal['is_has_loans'] == 0){
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['ref_data'] = $ref_data;//还款日期
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] =  $deal_id.'T'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
			$data['pDate'] = to_date(get_gmtime(),'Ymd');//商户日期  否  格式：YYYYMMDD,
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$data['pTransferMode'] = 1;//转账方式  是  转账方式，1：逐笔入账；2：批量入账  逐笔入账：不将转账款项汇总，而是按明细交易一笔一 笔计入账户  批量入帐：针对投资，将明细交易按 1 笔汇总本金和 1 笔汇总手续费记入借款人帐户  当转账类型为“1：投资”时，可选择 1 或 2。其余交 易只能选1
				
			$data['pTransferType'] = $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
		
				
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
			
			if ($id > 0){
				$result['data'] = $data;
				$details = array();
				
				$result['id'] = $id;				
				//满标 `is_has_loans` tinyint(11) NOT NULL COMMENT '是否已经放款给招标人',
				
				
				//
				
				$deal = $GLOBALS['db']->getRow("select services_fee,borrow_amount from ".DB_PREFIX."deal where id = ".$deal_id);
				$pLendFee = floatval($deal['services_fee']) / 100 * $deal['borrow_amount'];//借款人手续费				
				//标的登记时： 借款人手续费  金额单位，丌能为负，允许为0 这里是平台向借款人收取的费用，此环节只做手续费登 记，真正扣款要通过： 接口类型：4.10转账接口 转账类型：1：投资 通过输入“转入方明细手续费”来按笔收取 “借款人手续费”≧“转入方明细手续费”乊和
				$load_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_load where deal_id = ".$deal_id);				
				$avg_lend_fee = $pLendFee / $load_count;
				 				
				$load = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_load where  deal_id = ".$deal_id);
				foreach($load as $k=>$v){
					if ($v['is_has_loans'] == 0){
						$detail = array();
						$detail['pid'] = $id;
						$detail['pOriMerBillNo'] = $v['pMerBillNo'];//原商户订单号   商户系统唯一不重复  当转账类型为投资时，为登记债权人时提交的商户订单号  当转账类型为代偿时，为登记债权人时提交的商户订单号  当转账类型为代偿还款时，为代偿时提交的商户订单号  当转账类型为债权转让时，为登记债权转让时提交的商户 订单号  当转账类型为结算担保收益时，为登记担保人时提交的商 户订单号,
						$detail['pTrdAmt'] = str_replace(',', '',number_format($v['money'],2));//转账金额  否  金额单位：元，不能为负，不允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，转账金额=债权面额；  转账类型，2：代偿，转账金额=代偿金额；  转账类型，3：代偿还款，转账金额=代偿还款金额；  转账类型，4：债权转让，转账金额=登记债权转让时的 支付金额； 转账类型，5：结算担保收益，累计转账金额<=登记担保 方时的担保收益；
						
						$detail['pFAcctType'] = 1;//转出方账户类型  否  0#机构；1#个人,				
						$pFIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$v['user_id']);
						$detail['pFIpsAcctNo'] = $pFIpsAcctNo;//转出方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转出方（投资人）；  转账类型，2：代偿，此为转出方（担保方）；  转账类型，3：代偿还款，此为转出方（借款人）；  转账类型，4：债权转让，此为转出方（受让方）；  转账类型，5：结算担保收益，此为转出方（借款人）；  '
						$detail['pFTrdFee'] = 0;//转出方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转出方（投资人）手续费；  转账类型，2：代偿，此为转出方（担保方）手续费；  转账类型，3：代偿还款，此为转出方（借款人）手续费；  转账类型，4：债权转让，此为转出方（受让方）手续费；  转账类型，5：结算担保收益，此为转出方（借款人）手 续费；  '
										
						$detail['pTAcctType'] = 1;//转入方账户类型  否  0#机构；1#个人,
						$detail['pTIpsAcctNo'] = $user['ips_acct_no'];//转入方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转入方（借款人）；  转账类型，2：代偿，此为转入方（投资人）；  转账类型，3：代偿还款，此为转入方（担保方）；  转账类型，4：债权转让，此为转入方（出让方）；  转账类型，5：结算担保收益，此为转入方（担保方）；
						
						if ($k + 1 == $load_count){
							//最后一批还款
							$avg_lend_fee = $pLendFee - $avg_lend_fee * ($load_count - 1);													
						}
						
						$detail['pTTrdFee'] = str_replace(',', '', number_format($avg_lend_fee,2));// '转入方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转入方（借款人）手续费；  转账类型，2：代偿，此为转入方（投资人）手续费；  转账类型，3：代偿还款，此为转入方（担保方）手续费；  转账类型，4：债权转让，此为转入方（出让方）手续费；  转账类型，5：结算担保收益，此为转入方（担保方）手 续费； ',
						
						/*
						`pIpsDetailBillNo` varchar(255) default NULL COMMENT 'IPS明细订单号  否  IPS明细订单号',
						`pIpsDetailTime` datetime default NULL COMMENT 'IPS明细处理时间  否  格式为：yyyyMMddHHmmss ',
						`pIpsFee` decimal(11,2) default '0.00' COMMENT 'IPS手续费  否  IPS手续费',
						`pStatus` varchar(1) default NULL,
						`pMessage` varchar(100) default NULL COMMENT '转账备注  否  转账失败的原因 ',
						*/
						$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer_detail",$detail,'INSERT');
						
						$details[] = $detail;	
					}				
				}
				
				$result['details'] = $details;
			}
		}

		return $result;
			
	}
	
	
	//代偿
	function Transfer_2($pTransferType, $deal, $user, $r_ids, $MerCode){
		$deal_id = intval($deal['id']);
	
		$result = array('id' => 0, 'msg' => '', 'data' => array(), 'details' => array());
	
		if ($pTransferType == 1 && $deal['is_has_loans'] == 0){
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] = $deal_id.'T'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
			$data['pDate'] = to_date(get_gmtime(),'Ymd');//商户日期  否  格式：YYYYMMDD,
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$data['pTransferMode'] = 1;//转账方式  是  转账方式，1：逐笔入账；2：批量入账  逐笔入账：不将转账款项汇总，而是按明细交易一笔一 笔计入账户  批量入帐：针对投资，将明细交易按 1 笔汇总本金和 1 笔汇总手续费记入借款人帐户  当转账类型为“1：投资”时，可选择 1 或 2。其余交 易只能选1
	
			$data['pTransferType'] = $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
				
			if ($id > 0){
				$result['data'] = $data;
				$details = array();
	
				$result['id'] = $id;
				//满标 `is_has_loans` tinyint(11) NOT NULL COMMENT '是否已经放款给招标人',
				$load = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_load where is_has_loans = 0 and deal_id = ".$deal_id);
				foreach($load as $k=>$v){
						
					$detail = array();
					$detail['pid'] = $id;
					$detail['pOriMerBillNo'] = $v['pMerBillNo'];//原商户订单号   商户系统唯一不重复  当转账类型为投资时，为登记债权人时提交的商户订单号  当转账类型为代偿时，为登记债权人时提交的商户订单号  当转账类型为代偿还款时，为代偿时提交的商户订单号  当转账类型为债权转让时，为登记债权转让时提交的商户 订单号  当转账类型为结算担保收益时，为登记担保人时提交的商 户订单号,
					$detail['pTrdAmt'] = str_replace(',', '',number_format($v['money'],2));//转账金额  否  金额单位：元，不能为负，不允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，转账金额=债权面额；  转账类型，2：代偿，转账金额=代偿金额；  转账类型，3：代偿还款，转账金额=代偿还款金额；  转账类型，4：债权转让，转账金额=登记债权转让时的 支付金额； 转账类型，5：结算担保收益，累计转账金额<=登记担保 方时的担保收益；
						
					$detail['pFAcctType'] = 1;//转出方账户类型  否  0#机构；1#个人,
					$pFIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$v['user_id']);
					$detail['pFIpsAcctNo'] = $pFIpsAcctNo;//转出方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转出方（投资人）；  转账类型，2：代偿，此为转出方（担保方）；  转账类型，3：代偿还款，此为转出方（借款人）；  转账类型，4：债权转让，此为转出方（受让方）；  转账类型，5：结算担保收益，此为转出方（借款人）；  '
					$detail['pFTrdFee'] = 0;//转出方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转出方（投资人）手续费；  转账类型，2：代偿，此为转出方（担保方）手续费；  转账类型，3：代偿还款，此为转出方（借款人）手续费；  转账类型，4：债权转让，此为转出方（受让方）手续费；  转账类型，5：结算担保收益，此为转出方（借款人）手 续费；  '
						
					$detail['pTAcctType'] = 1;//转入方账户类型  否  0#机构；1#个人,
					$detail['pTIpsAcctNo'] = $user['ips_acct_no'];//转入方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转入方（借款人）；  转账类型，2：代偿，此为转入方（投资人）；  转账类型，3：代偿还款，此为转入方（担保方）；  转账类型，4：债权转让，此为转入方（出让方）；  转账类型，5：结算担保收益，此为转入方（担保方）；
					$detail['pTTrdFee'] = 0;// '转入方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转入方（借款人）手续费；  转账类型，2：代偿，此为转入方（投资人）手续费；  转账类型，3：代偿还款，此为转入方（担保方）手续费；  转账类型，4：债权转让，此为转入方（出让方）手续费；  转账类型，5：结算担保收益，此为转入方（担保方）手 续费； ',
						
					/*
						`pIpsDetailBillNo` varchar(255) default NULL COMMENT 'IPS明细订单号  否  IPS明细订单号',
					`pIpsDetailTime` datetime default NULL COMMENT 'IPS明细处理时间  否  格式为：yyyyMMddHHmmss ',
					`pIpsFee` decimal(11,2) default '0.00' COMMENT 'IPS手续费  否  IPS手续费',
					`pStatus` varchar(1) default NULL,
					`pMessage` varchar(100) default NULL COMMENT '转账备注  否  转账失败的原因 ',
					*/
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer_detail",$detail,'INSERT');
						
					$details[] = $detail;
				}
	
				$result['details'] = $details;
			}
		}
	
		return $result;
			
	}	
	
	//债权转让
	function Transfer_4($pTransferType, $deal, $transfer_id, $MerCode){
		$deal_id = intval($deal['id']);
	
		$result = array('id' => 0, 'msg' => '', 'data' => array(), 'details' => array());
	
		if ($pTransferType == 4 && $deal['is_has_loans'] == 1){
			$data = array();
			$data['deal_id'] = $deal_id;
			$data['ref_data'] = $transfer_id;
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] = $deal_id.'T'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
			$data['pDate'] = to_date(get_gmtime(),'Ymd');//商户日期  否  格式：YYYYMMDD,
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$data['pTransferMode'] = 1;//转账方式  是  转账方式，1：逐笔入账；2：批量入账  逐笔入账：不将转账款项汇总，而是按明细交易一笔一 笔计入账户  批量入帐：针对投资，将明细交易按 1 笔汇总本金和 1 笔汇总手续费记入借款人帐户  当转账类型为“1：投资”时，可选择 1 或 2。其余交 易只能选1
	
			$data['pTransferType'] = $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
				
			if ($id > 0){
				
	
				$result['data'] = $data;
				$details = array();
	
				$result['id'] = $id;
				//ips_status ips处理状态;0:未处理;1:已登记债权转让;2:已转让
				$load = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_load_transfer where ips_status = 1 and id = ".$transfer_id);
				
				$user_load_transfer_fee = $GLOBALS['db']->getOne("SELECT user_load_transfer_fee FROM ".DB_PREFIX."deal WHERE id=".$deal_id);
				
				foreach($load as $k=>$v){
						
					$detail = array();
					$detail['pid'] = $id;
					$detail['pOriMerBillNo'] = $v['pMerBillNo'];//原商户订单号   商户系统唯一不重复  当转账类型为投资时，为登记债权人时提交的商户订单号  当转账类型为代偿时，为登记债权人时提交的商户订单号  当转账类型为代偿还款时，为代偿时提交的商户订单号  当转账类型为债权转让时，为登记债权转让时提交的商户 订单号  当转账类型为结算担保收益时，为登记担保人时提交的商 户订单号,
					$detail['pTrdAmt'] = str_replace(',', '',number_format($v['transfer_amount'],2)) ;//转账金额  否  金额单位：元，不能为负，不允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，转账金额=债权面额；  转账类型，2：代偿，转账金额=代偿金额；  转账类型，3：代偿还款，转账金额=代偿还款金额；  转账类型，4：债权转让，转账金额=登记债权转让时的 支付金额； 转账类型，5：结算担保收益，累计转账金额<=登记担保 方时的担保收益；
						
					$detail['pFAcctType'] = 1;//转出方账户类型  否  0#机构；1#个人,
					$pFIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$v['lock_user_id']);
					$detail['pFIpsAcctNo'] = $pFIpsAcctNo;//转出方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转出方（投资人）；  转账类型，2：代偿，此为转出方（担保方）；  转账类型，3：代偿还款，此为转出方（借款人）；  转账类型，4：债权转让，此为转出方（受让方）；  转账类型，5：结算担保收益，此为转出方（借款人）；  '
					$detail['pFTrdFee'] = '0.00';//转出方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转出方（投资人）手续费；  转账类型，2：代偿，此为转出方（担保方）手续费；  转账类型，3：代偿还款，此为转出方（借款人）手续费；  转账类型，4：债权转让，此为转出方（受让方）手续费；  转账类型，5：结算担保收益，此为转出方（借款人）手 续费；  '
						
					$detail['pTAcctType'] = 1;//转入方账户类型  否  0#机构；1#个人,
					$pTIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$v['user_id']);
					$detail['pTIpsAcctNo'] = $pTIpsAcctNo;//转入方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转入方（借款人）；  转账类型，2：代偿，此为转入方（投资人）；  转账类型，3：代偿还款，此为转入方（担保方）；  转账类型，4：债权转让，此为转入方（出让方）；  转账类型，5：结算担保收益，此为转入方（担保方）；
					$detail['pTTrdFee'] = str_replace(',', '', number_format($v['transfer_amount']*$user_load_transfer_fee*0.01,2));;// '转入方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转入方（借款人）手续费；  转账类型，2：代偿，此为转入方（投资人）手续费；  转账类型，3：代偿还款，此为转入方（担保方）手续费；  转账类型，4：债权转让，此为转入方（出让方）手续费；  转账类型，5：结算担保收益，此为转入方（担保方）手 续费； ',
						
					/*
						`pIpsDetailBillNo` varchar(255) default NULL COMMENT 'IPS明细订单号  否  IPS明细订单号',
					`pIpsDetailTime` datetime default NULL COMMENT 'IPS明细处理时间  否  格式为：yyyyMMddHHmmss ',
					`pIpsFee` decimal(11,2) default '0.00' COMMENT 'IPS手续费  否  IPS手续费',
					`pStatus` varchar(1) default NULL,
					`pMessage` varchar(100) default NULL COMMENT '转账备注  否  转账失败的原因 ',
					*/
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer_detail",$detail,'INSERT');
						
					$details[] = $detail;
				}
	
				$result['details'] = $details;
			}
		}
	
		return $result;
			
	}	
	
	//担保收益
	function Transfer_5($pTransferType, $deal, $money, $MerCode){
		$deal_id = intval($deal['id']);
	
		$result = array('id' => 0, 'msg' => '', 'data' => array(), 'details' => array());
	
		if ($pTransferType == 5){
			$data = array();
			
			if ($money == 0){
				$money = $deal['guarantor_pro_fit_amt'] - $deal['guarantor_real_fit_amt'];
			}
			
			
			$data['deal_id'] = $deal_id;
			$data['ref_data'] = $money;
			$data['pMerCode'] = $MerCode;//“平台”账 号 由IPS颁发的商户号',
			$data['pMerBillNo'] = $deal_id.'T'.get_gmtime();//'商户订单号 否 商户系统唯一不重复',
			$data['pDate'] = to_date(get_gmtime(),'Ymd');//商户日期  否  格式：YYYYMMDD,
			$data['pBidNo'] = $deal_id;// '标的号 否 原投资交易的标的号，字母和数字，如a~z,A~Z,0~9 ',
			$data['pTransferMode'] = 1;//转账方式  是  转账方式，1：逐笔入账；2：批量入账  逐笔入账：不将转账款项汇总，而是按明细交易一笔一 笔计入账户  批量入帐：针对投资，将明细交易按 1 笔汇总本金和 1 笔汇总手续费记入借款人帐户  当转账类型为“1：投资”时，可选择 1 或 2。其余交 易只能选1
	
			$data['pTransferType'] = $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
	
			if ($id > 0){
				$result['data'] = $data;
				$details = array();
	
				$result['id'] = $id;
		
	
					$detail = array();
					$detail['pid'] = $id;
					$detail['pOriMerBillNo'] = $deal['mer_guarantor_bill_no'];//原商户订单号   商户系统唯一不重复  当转账类型为投资时，为登记债权人时提交的商户订单号  当转账类型为代偿时，为登记债权人时提交的商户订单号  当转账类型为代偿还款时，为代偿时提交的商户订单号  当转账类型为债权转让时，为登记债权转让时提交的商户 订单号  当转账类型为结算担保收益时，为登记担保人时提交的商 户订单号,
					
					
					
					$detail['pTrdAmt'] = str_replace(',', '',number_format($money,2));//转账金额  否  金额单位：元，不能为负，不允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，转账金额=债权面额；  转账类型，2：代偿，转账金额=代偿金额；  转账类型，3：代偿还款，转账金额=代偿还款金额；  转账类型，4：债权转让，转账金额=登记债权转让时的 支付金额； 转账类型，5：结算担保收益，累计转账金额<=登记担保 方时的担保收益；
	
					
					$detail['pFAcctType'] = 1;//转出方账户类型  否  0#机构；1#个人,
					$pFIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$deal['user_id']);
					$detail['pFIpsAcctNo'] = $pFIpsAcctNo;//转出方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转出方（投资人）；  转账类型，2：代偿，此为转出方（担保方）；  转账类型，3：代偿还款，此为转出方（借款人）；  转账类型，4：债权转让，此为转出方（受让方）；  转账类型，5：结算担保收益，此为转出方（借款人）；  '
					$detail['pFTrdFee'] = 0;//转出方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转出方（投资人）手续费；  转账类型，2：代偿，此为转出方（担保方）手续费；  转账类型，3：代偿还款，此为转出方（借款人）手续费；  转账类型，4：债权转让，此为转出方（受让方）手续费；  转账类型，5：结算担保收益，此为转出方（借款人）手 续费；  '
	
					$detail['pTAcctType'] = 1;//转入方账户类型  否  0#机构；1#个人,
					$pTIpsAcctNo = $GLOBALS['db']->getOne("select ips_acct_no from ".DB_PREFIX."user where id = ".$deal['agency_id']);
					$detail['pTIpsAcctNo'] = $pTIpsAcctNo;//转入方 IPS 托管 账户号  否  账户类型为1时，IPS个人托管账户号  账户类型为0时，由 IPS颁发的商户号  转账类型，1：投资，此为转入方（借款人）；  转账类型，2：代偿，此为转入方（投资人）；  转账类型，3：代偿还款，此为转入方（担保方）；  转账类型，4：债权转让，此为转入方（出让方）；  转账类型，5：结算担保收益，此为转入方（担保方）；
					$detail['pTTrdFee'] = 0;// '转入方明细手续 费  否  金额单位：元，不能为负，允许为0，保留2位小数；  格式：12.00  转账类型，1：投资，此为转入方（借款人）手续费；  转账类型，2：代偿，此为转入方（投资人）手续费；  转账类型，3：代偿还款，此为转入方（担保方）手续费；  转账类型，4：债权转让，此为转入方（出让方）手续费；  转账类型，5：结算担保收益，此为转入方（担保方）手 续费； ',
	
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer_detail",$detail,'INSERT');
	
					$details[] = $detail;
				
	
				$result['details'] = $details;
			}
		}
	
		return $result;
			
	}

	
	//转帐回调
	function TransferCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req){
		//print_r($str3Req);
		//require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
		//print_r(@XML_unserialize($str3XmlParaInfo)); exit;
		
		$pTransferType = $str3Req["pTransferType"];
		$pMerBillNo = $str3Req["pMerBillNo"];		
		$id = intval($str3Req["pMemo1"]);
		$where = " id = '".$id."'";
		
		
		$data = array();

		$data['pErrCode'] = $pErrCode;//一、转账类型为“代偿”，“投 资”时同步返回 MG00008F IPS 受理中；异步再返回 MG00000F 操作成功；  二、其他转账类型 MG00000F 操作成功
		$data['pErrMsg'] = $pErrMsg;
		
		$data['pIpsBillNo']  = $str3Req["pIpsBillNo"];//IPS还款订单号  否  由 IPS 系统生成的唯一流水号， 此次还款的批次号					
		$data['pIpsTime'] = $str3Req["pIpsTime"];//IPS处理时间  否  格式为：yyyyMMddHHmmss
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer",$data,'UPDATE',$where);
		/*if ($data['pErrCode'] == 'MG00008F'){
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",array("ips_do_transfer"=>1),'UPDATE',"id in (SELECT pBidNo FROM ".DB_PREFIX."ips_transfer WHERE id = '".$id."')");
		}*/	
			//MG00000F操作成功
		if ($data['pErrCode'] == 'MG00000F'){
			$sql = "update ".DB_PREFIX."ips_transfer set is_callback = 1 where is_callback = 0 and ".$where;
			$GLOBALS['db']->query($sql);
			if ($GLOBALS['db']->affected_rows()){	
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ips_transfer where ".$where);
				$deal_id = intval($ipsdata['deal_id']);
				
				//require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				//$pDetails = getXmlNodeValue($str3XmlParaInfo, "pDetails");
				
				//print_r($pDetails);
				
				//$pDetails = @XML_unserialize($pDetails);
				
				//print_r($pDetails);
				
				//$pDetails = getXmlNodeValue($str3XmlParaInfo, "pDetails");
				$attr = array();	
				if(isset($str3Req["pDetails"]["pRow"][0])){
					$attr = $str3Req["pDetails"]["pRow"];
				}
				else{
					$attr[] = $str3Req["pDetails"]["pRow"];
				}
				foreach($attr as $k=>$v){
					//$pDetail = '';
					
					$pOriMerBillNo = $v["pOriMerBillNo"];
					$where = " pid = ".$ipsdata['id']. " and pOriMerBillNo = '".$pOriMerBillNo."'";
					
					$detail = array();
					$detail['pIpsDetailBillNo']  = $v["pIpsDetailBillNo"];//IPS明细订单号  否  IPS明细订单号
					$detail['pIpsDetailTime'] = $v["pIpsDetailTime"];//IPS明细处理时间  否  格式为：yyyyMMddHHmmss
					$detail['pIpsFee'] = $v["pIpsFee"];//IPS手续费  否  IPS手续费
					$detail['pStatus'] = $v["pStatus"];//Y#转账成功；N#转账失败 
					$detail['pMessage'] = $v["pMessage"];//转账备注  否  转账失败的原因 
					$GLOBALS['db']->autoExecute(DB_PREFIX."ips_transfer_detail",$detail,'UPDATE',$where);

					
					if ($pTransferType == 1){
						$deal_load = array();
						if ($detail['pStatus'] == 'Y')
							$deal_load['is_has_loans'] = 1;//1#转账成功；0#转账失败
						else 
							$deal_load['is_has_loans'] = 0;//1#转账成功；0#转账失败
						
						$deal_load['msg'] = $detail['pMessage'];//转账备注  否  转账失败的原因
						
						$where = " deal_id = ".$deal_id. " and pMerBillNo = '".$pOriMerBillNo."'";
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load",$deal_load,'UPDATE',$where);
					}	
												
				}
				
				//转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
				if ($pTransferType == 1){					
					$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_load where is_has_loans = 0 and deal_id = ".$deal_id);
					if ($count == 0){
						//已经全部放款完成;
						
						//$sql = "update ".DB_PREFIX."deal set is_has_loans = 1 where is_has_loans = 0 and id = ".$deal_id;
						//$GLOBALS['db']->query($sql);
						$repay_start_time = intval($ipsdata['ref_data']);
						 require_once(APP_ROOT_PATH."app/Lib/common.php");						 
						$result = do_loans($deal_id,$repay_start_time,1);						
					}else{
						//还有未放款完成的
						//$sql = "update ".DB_PREFIX."deal set is_has_loans = 0 where id = ".$deal_id;
						//$GLOBALS['db']->query($sql);
					}		
					
					
					
				}else if ($pTransferType == 4){		
					
					$sql = "update ".DB_PREFIX."deal_load_transfer set t_user_id = lock_user_id, transfer_time = '".get_gmtime()."', ips_status = 2, ips_bill_no = '".$data['pIpsBillNo']."' where ips_status = 1 and id =".intval($ipsdata['ref_data']);
					//echo $sql;
					$GLOBALS['db']->query($sql);	

					$sql = "select * from ".DB_PREFIX."deal_load_transfer where ips_status = 2 and id =".intval($ipsdata['ref_data']);
					
					$transfer = $GLOBALS['db']->getRow($sql);
					
					//将用户投资回款计划,收款人更改为：承接者
					$sql = "update ".DB_PREFIX."deal_load_repay set t_pMerBillNo = '".$ipsdata["pMerBillNo"]."', t_user_id = '".$transfer['t_user_id']."' where has_repay = 0 and load_id =".intval($transfer['load_id'])." and user_id =".intval($transfer['user_id'])." and deal_id = ".$deal_id;
					//echo $sql;					
					
					$GLOBALS['db']->query($sql); 
					
					return $transfer;
				}else if ($pTransferType == 5){					
					$sql = "update ".DB_PREFIX."deal set guarantor_real_fit_amt = guarantor_real_fit_amt +".intval($ipsdata['ref_data'])." where id =".$deal_id;
					//echo $sql;
					$GLOBALS['db']->query($sql);				
				}
			}
		}

		//print_r($str3Req);exit;
	}	
	
?>