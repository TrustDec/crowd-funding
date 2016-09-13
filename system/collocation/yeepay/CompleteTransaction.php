<?php
	
/**
 * 
 * @param unknown_type $platformNo
 * @param unknown_type $requestNo
 * @param unknown_type $mode
 * @param unknown_type $post_url
 * @return multitype:number string unknown
 */
	function CompleteTransaction($platformNo,$requestNo,$mode,$post_url){
		
		/* 请求参数 */  
		$req = "<request platformNo=\"$platformNo\"><requestNo>$requestNo</requestNo><mode>$mode</mode><notifyUrl><![CDATA[".$IpsSubject['pMemo1']."]]></notifyUrl></request>";
		/* 签名数据 */
		$sign = cfca($req);
		/* 调用账户查询服务 */
		$service = "ACCOUNT_INFO";
		$ch = curl_init($post_url."/bhaexter/bhaController");
		curl_setopt_array($ch, array(
		CURLOPT_POST => TRUE,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
		CURLOPT_POSTFIELDS => 'service=' . $service . '&req=' . rawurlencode($req) . "&sign=" . rawurlencode($sign)
		));
		$resultStr = curl_exec($ch);
		//print($result);
		if (empty($resultStr)){
			$result = array();
			$result['pErrCode'] = 9999;
			$result['pErrMsg'] = '返回出错';
			$result['pIpsAcctNo'] = '';
			$result['pBalance'] = 0;
			$result['pLock'] = 0;
			$result['pNeedstl'] = 0;
		}else{
	
				
		
				require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				$str3ParaInfo = @XML_unserialize($resultStr);
				//print_r($str3ParaInfo);exit;
				$str3Req = $str3ParaInfo['response'];
		
				$result = array();
				$result['pErrCode'] = $str3Req["code"];
				$result['pErrMsg'] = $str3Req["description"];
				$result['pIpsAcctNo'] = $requestNo;
				$result['pBalance'] = $str3Req["balance"];
				$result['pLock'] = $str3Req["freezeAmount"];
				$result['pNeedstl'] = 0;// $str3Req["availableAmount"];			
		}
		
		return $result;
		
		/*
		 * <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<response platformNo="10040011137">
    <code>1</code>
    <description>操作成功</description>
    <memberType>PERSONAL</memberType>
    <activeStatus>ACTIVATED</activeStatus>
    <balance>9980.98</balance>
    <availableAmount>9980.98</availableAmount>
    <freezeAmount>0.00</freezeAmount>
    <cardNo>********5512</cardNo>
    <cardStatus>VERIFIED</cardStatus>
    <bank>CCB</bank>
    <autoTender>false</autoTender>
</response>
		 */
		
	
	}	
	
?>