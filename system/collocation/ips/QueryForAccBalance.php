<?php

	/**
	 * 账户余额查询(WS) 
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $ws_url
	 * @return
	 * 			pMerCode 6 “平台”账号 否 由IPS颁发的商户号
				pErrCode 4 返回状态 否 0000成功； 9999失败；
				pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
				pIpsAcctNo 30 IPS账户号 否 查询时提交
				pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数
				pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数
				pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数
	 */
	function QueryForAccBalance($user_id,$user_type,$MerCode,$cert_md5,$ws_url){
	
		$user = array();
		if ($user_type == 0){
			$user = $GLOBALS['db']->getRow("select id,ips_acct_no from ".DB_PREFIX."user where id = ".$user_id);
			$argIpsAccount = $user['ips_acct_no'];
		}else{
			
			//$user = $GLOBALS['db']->getRow("select id,ips_acct_no,acct_type,ips_mer_code from ".DB_PREFIX."user where id = ".$user_id);
			$user = $GLOBALS['db']->getRow("select id,ips_acct_no,acct_type,ips_mer_code from ".DB_PREFIX."user where id = ".$user_id);
			//acct_type 担保方类型 否 0#机构；1#个人
			if ($user['acct_type'] == 0){
				$argIpsAccount = $user['ips_mer_code'];
			}else{
				$argIpsAccount = $user['ips_acct_no'];
			}
		}		
		
		if (empty($argIpsAccount)){
			$result = array();				
			$result['pErrCode'] = 9999;
			$result['pErrMsg'] = '未开通ips帐户';
			$result['pIpsAcctNo'] = '';
			$result['pBalance'] = 0;
			$result['pLock'] = 0;
			$result['pNeedstl'] = 0;
		}else{
			
			$str=$MerCode.$argIpsAccount.$cert_md5;
			$pSign=md5($str);
			
			try {
				$url=$ws_url;
				//1.开启soap支持，在php.ini中去除extension=php_soap.dll之前的‘
				$client = new SoapClient($url);
				//接口方法：public string QueryForAccBalance (string argMerCode,string argIpsAccount,string argSign)
				$param = array('argMerCode'=>$MerCode,'argIpsAccount'=>$argIpsAccount,'argSign'=>$pSign);
				$arrResult = $client->QueryForAccBalance($param);
				$resultStr = $arrResult->QueryForAccBalanceResult;
					
				/*
				pMerCode 6 “平台”账号 否 由IPS颁发的商户号
				pErrCode 4 返回状态 否 0000成功； 9999失败；
				pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
				pIpsAcctNo 30 IPS账户号 否 查询时提交
				pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数
				pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数
				pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数
				*/
				
				require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				$str3ParaInfo = @XML_unserialize($resultStr);
				//print_r($str3ParaInfo);exit;
				$str3Req = $str3ParaInfo['pReq'];

				$result = array();				
				$result['pErrCode'] = $str3Req["pErrCode"];
				$result['pErrMsg'] = $str3Req["pErrMsg"];
				$result['pIpsAcctNo'] = $str3Req["pIpsAcctNo"];
				$result['pBalance'] = $str3Req["pBalance"];
				$result['pLock'] = $str3Req["pLock"];
				$result['pNeedstl'] = $str3Req["pNeedstl"];

				//print_r($result);
			} catch (SOAPFault $e) {

				$result = array();
				$result['pErrCode'] = 9999;
				$result['pErrMsg'] = print_r($e,1);
				$result['pIpsAcctNo'] = '';
				$result['pBalance'] = 0;
				$result['pLock'] = 0;
				$result['pNeedstl'] = 0;				
			}
		}

		return $result;
	}
	
	
?>