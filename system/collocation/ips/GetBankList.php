<?php

/**
 * 商户端获取银行列表查询(WS) 
 * @param int $MerCode
 * @param unknown_type $cert_md5
 * @param unknown_type $ws_url
 * @return  
 * 		  pMerCode 6 “平台”账号 否 由IPS颁发的商户号 pErrCode 4 返回状态 否 0000成功； 9999失败；
 * 		  pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因 
 * 		  pBankList 银行名称|银行卡别名|银行卡编号#银行名称|银行卡别名|银行卡编号
 * 		  BankList[] = array('name'=>银行名称,'sub_name'=>银行卡别名,'id'=>银行卡编号);
 */

function GetBankList($MerCode, $cert_md5, $ws_url) {
	$str = $MerCode . $cert_md5;
	$pSign = md5 ( $str );
	
	try {
		$url = $ws_url;
		$client = new SoapClient ( $url );
		// 接口方法：public string GetBankList(string argMerCode, string argSign)
		$param = array (
				'argMerCode' => $MerCode,
				'argSign' => $pSign 
		);
		$arrResult = $client->GetBankList ( $param );
		$resultStr = $arrResult->GetBankListResult;
		
		/*
		 * pMerCode 6 “平台”账号 否 由IPS颁发的商户号 pErrCode 4 返回状态 否 0000成功； 9999失败；
		 * pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因 
		 * pBankList 银行名称|银行卡别名|银行卡编号#银行名称|银行卡别名|银行卡编号
		 */
		require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
		$str3ParaInfo = @XML_unserialize($resultStr);
		//print_r($str3ParaInfo);exit;
		$str3Req = $str3ParaInfo['pReq'];
		
		$result = array ();
		$result ['pErrCode'] = $str3Req["pErrCode"];
		$result ['pErrMsg'] = $str3Req["pErrMsg"];
		$result ['pBankList'] = $str3Req["pBankList"];
		$list = explode('#',$result ['pBankList']);
		$BankList = array();
		foreach ($list as $bank){
			$b = explode('|',$bank);
			if (count($b) >= 3){
				$BankList[] = array('name'=>$b[0],'sub_name'=>$b[1],'id'=>$b[2]);
			}
		}
		$result ['BankList'] = $BankList;
		
	} catch ( SOAPFault $e ) {
		
		$result = array ();
		$result ['pErrCode'] = 9999;
		$result ['pErrMsg'] = print_r ( $e, 1 );
		$result ['pBankList'] = '';
	}
	
	return $result;
}

?>