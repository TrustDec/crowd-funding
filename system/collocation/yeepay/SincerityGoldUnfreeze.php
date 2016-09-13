<?php
	
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function SincerityGoldUnfreezeXml($data,$pWebUrl,$pS2SUrl){	

		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
				."<freezeRequestNo>" .$data['freezeRequestNo'] ."</freezeRequestNo>"	  	
				."</request>";	
				
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	


	function SincerityGoldUnfreeze($user_id,$platformNo,$freezeRequestNo,$deal_id,$post_url,$sys='pc'){
	
		
		
		//$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=SincerityGoldUnfreeze&from=".$_REQUEST['from'];//web方式返回
		//$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=SincerityGoldUnfreeze&from=".$_REQUEST['from'];//s2s方式返回		
		
		
		$yeepay_log = array();
		$yeepay_log['code'] = 'UNFREEZE';
		$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		$requestNo = $GLOBALS['db']->insert_id();
		
		
		$data = array();
		$data['platformNo'] = $platformNo;// 商户编号
		$data['freezeRequestNo'] = $freezeRequestNo ;
		$data['expired'] =NOW_TIME; //到期自劢解冻时间			
	//	$strxml = SincerityGoldUnfreezeXml($data,$pWebUrl,$pS2SUrl);

		/* 请求参数 */
		$req = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
				."<freezeRequestNo>" .$data['freezeRequestNo'] ."</freezeRequestNo>"
				."</request>";
		/* 签名数据 */
		$sign = cfca($req);
		/* 调用账户查询服务 */
		$service = "UNFREEZE";
		$ch = curl_init($post_url."/bhaexter/bhaController");
		curl_setopt_array($ch, array(
		CURLOPT_POST => TRUE,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
		CURLOPT_POSTFIELDS => 'service=' . $service . '&req=' . rawurlencode($req) . "&sign=" . rawurlencode($sign)
		));
		$resultStr = curl_exec($ch);
		if (empty($resultStr)){
				//$err_count ++ ;
			}else{
					require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
					$str3ParaInfo = @XML_unserialize($resultStr);
 					$str3Req = $str3ParaInfo['response'];
 					$result['pErrCode'] = $str3Req["code"];
					$result['pErrMsg'] = $str3Req["description"];
 					if($str3Req["code"] == 1)
					{
						//操作成功					
						$where=" platformUserNo=".$user_id." and deal_id =".$deal_id." and requestNo=".$freezeRequestNo;
						$data['status'] = 2;
						$data['create_time']=NOW_TIME;
						if (isset($str3Req['description']))
							$data['description'] = $str3Req["description"];
						$GLOBALS['db']->autoExecute(DB_PREFIX."money_freeze",$data,'UPDATE',$where);
						showIpsInfo('诚意金解冻成功',get_domain().APP_ROOT.'/'.URL_NAME.'?m=UserFreeze&a=index&');
					}
					
			}
	
	}
	
	
?>