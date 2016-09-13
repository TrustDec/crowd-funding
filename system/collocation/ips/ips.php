<?php
	//(ws回调)
	function wsnotify($resultStr,$class_act,$cert_md5){
	
		$pMerCode = $resultStr["pMerCode"];
		$pErrCode = $resultStr["pErrCode"];
		$pErrMsg = $resultStr["pErrMsg"];
		$p3DesXmlPara = $resultStr["p3DesXmlPara"];
		$pSign = $resultStr["pSign"];
	
	
		$signPlainText = $pMerCode.$pErrCode.$pErrMsg.$p3DesXmlPara.$cert_md5;
		$localSign = md5($signPlainText);
		if($localSign==$pSign){
			//file_put_contents(PATH_LOG_FILE,PATH."--".date('YmdHis')."	验签通过"."\r\n",FILE_APPEND);
			$Crypt3Des=new Crypt3Des();//new 3des class
			$str3XmlParaInfo=$Crypt3Des->DESDecrypt($p3DesXmlPara);//3des解密
			if(empty($str3XmlParaInfo)){
				//file_put_contents(PATH_LOG_FILE,PATH."--".date('YmdHis')."	3DES解密失败"."\r\n",FILE_APPEND);
				return;
			}else{
				
				require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				$str3ParaInfo = @XML_unserialize($str3XmlParaInfo);
				$str3Req = $str3ParaInfo['pReq'];
				
				if ($class_act == 'GuaranteeUnfreeze'){
					require_once(APP_ROOT_PATH.'system/collocation/ips/GuaranteeUnfreeze.php');
					GuaranteeUnfreezeCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req);
					showSuccess($pErrMsg,0,SITE_DOMAIN.APP_ROOT);
				}	
				if ($class_act == 'Transfer'){
					require_once(APP_ROOT_PATH.'system/collocation/ips/Transfer.php');					
					
					TransferCallBack($pMerCode,$pErrCode,$pErrMsg,$str3Req);
					showSuccess($pErrMsg,0,SITE_DOMAIN.APP_ROOT);
				}				
			}
		}
	}
	
?>