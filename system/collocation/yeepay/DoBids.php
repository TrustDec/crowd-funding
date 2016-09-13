<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function DoBidsXml($IpsSubject,$pWebUrl,$pS2SUrl){		

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
	 * @param int $status; 0:新增; 2:流标结束
	 * @param string $status_msg 主要是status_msg=2时记录的，流标原因
	 * @param unknown_type $platformNo
	 * @param unknown_type $post_url
	 * @return string
	 */
	function DoBids($deal_id,$pOperationType,$status, $status_msg, $platformNo,$post_url){
			
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=yeepay&class_act=DoBids";//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=yeepay&class_act=DoBids";//s2s方式返回		
		
		//$requestNo = $GLOBALS['db']->getOne("select * from ".DB_PREFIX."yeepay_cp_transaction where is_callback = 1 and code = 1 and tenderOrderNo = '".$deal_id."'");
		
		$t_arr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."yeepay_cp_transaction where is_callback = 1 and tenderOrderNo = ".$deal_id." and bizType = 'TENDER'");
		
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);

		$err_count = 0;
		foreach($t_arr as $k => $v)
		{
			$data = array();
			$data['requestNo'] = $v["requestNo"];//请求流水号
			$data['platformNo'] = $platformNo;// 商户编号
			$data['mode'] = "CANCEL";
			
			/* 请求参数 */  
			$req = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
			."<request platformNo=\"".$platformNo."\">"
			."<requestNo>".$data['requestNo']."</requestNo>"
			."<mode>".$data['mode']."</mode>"
			."<notifyUrl><![CDATA[" .$pS2SUrl ."]]></notifyUrl>"
			."</request>";
			/* 签名数据 */
			$pSign= cfca($req);
			
			$yeepay_log = array();
			$yeepay_log['code'] = 'COMPLETE_TRANSACTION';
			$yeepay_log['create_date'] = to_date(TIME_UTC,'Y-m-d H:i:s');
			$yeepay_log['strxml'] = $req;
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
			//$id = $GLOBALS['db']->insert_id();
			
			/* 调用账户查询服务 */
			$service = "COMPLETE_TRANSACTION";
			$ch = curl_init($post_url."/bhaexter/bhaController");
			curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER=>0,
			CURLOPT_SSL_VERIFYHOST=>0,
			CURLOPT_POSTFIELDS => 'service=' . $service . '&req=' . rawurlencode($req) . "&sign=" . rawurlencode($pSign)
			));
			$resultStr = curl_exec($ch);
			if (empty($resultStr)){
				$err_count ++ ;
			}else{
					require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
					$str3ParaInfo = @XML_unserialize($resultStr);
					//print_r($str3ParaInfo);exit;
					$str3Req = $data['requestNo'];
					
					$result['pErrCode'] = $str3Req["code"];
					$result['pErrMsg'] = $str3Req["description"];
					//$result['pIpsAcctNo'] = $user_id;	
					
					if($str3Req["code"] == 1)
					{
						$requestNo = $data['requestNo'];
						$t_data = array();
						$t_data["is_complete_transaction"] = 1;
						$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_cp_transaction",$t_data,'UPDATE'," requestNo = '".$requestNo."'");
						
						return array("info"=>'操作成功');
					}
			}
		}	
		//showIpsInfo('同步成功',"");
	}
	function DoBidsCallBack($str3Req)
	{

		$yeepay_log = array();
		$yeepay_log['code'] = 'test';
		$yeepay_log['create_date'] = to_date(TIME_UTC,'Y-m-d H:i:s');
		$yeepay_log['strxml'] = print_r($str3Req,true);
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		
		if($str3Req["code"] == 1)
		{
			$requestNo = $str3Req['requestNo'];

			$where = " requestNo = '".$requestNo."'";
			
			$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where ".$where);
				
			$deal_id = (int)$ipsdata['tenderOrderNo'];

			require_once APP_ROOT_PATH.'app/Lib/common.php';
			$result = do_received($deal_id,1,$ipsdata['message']);
			
			//本地解冻:借款保证金,担保保证金0
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_over = 1 ,un_real_freezen_amt = real_freezen_amt,un_guarantor_real_freezen_amt = guarantor_real_freezen_amt where id = ".$deal_id);
			
		}
		return 1;
	}
	
	
?>