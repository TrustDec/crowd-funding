<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function SincerityGoldFreezeXml($data,$pWebUrl,$pS2SUrl){	

		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
				."<requestNo>" .$data['requestNo'] ."</requestNo>"
				."<platformUserNo>" .$data['platformUserNo'] ."</platformUserNo>"
				."<amount>" .$data['amount'] ."</amount>"
				."<expired>" .$data['expired'] ."</expired>"			  	
				."</request>";	
				
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	


	function SincerityGoldFreeze($user_id,$platformNo,$pTrdAmt,$deal_id,$from,$post_url,$sys='pc'){
	
	
		
		//$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=SincerityGoldFreeze&from=".$_REQUEST['from'];//web方式返回
		//$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=SincerityGoldFreeze&from=".$_REQUEST['from'];//s2s方式返回		
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		

		$yeepay_log = array();
		$yeepay_log['code'] = 'FREEZE';
		$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		$requestNo = $GLOBALS['db']->insert_id();
		$data = array();
		$data['requestNo'] = $requestNo;//请求流水号
		$data['platformUserNo'] = $user_id;//
		$data['platformNo'] = $platformNo;// 商户编号
		$data['amount'] = $pTrdAmt ;
		//$data['feeMode'] = 'PLATFORM';//费率模式PLATFORM
		$data['deal_id']=$deal_id;
		$unfree_month = app_conf("MORTGAGE_MONEY_UNFREEZE")?app_conf("MORTGAGE_MONEY_UNFREEZE"):12;
		$data['expired'] =to_date(NOW_TIME+3600*12+3600*24*30*$unfree_month,"Y-m-d H:i:s"); //到期自动解冻时间			
	//	$strxml = SincerityGoldFreezeXml($data,$pWebUrl,$pS2SUrl);

		/* 请求参数 */
		$req = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
				."<requestNo>" .$data['requestNo'] ."</requestNo>"
				."<platformUserNo>" .$data['platformUserNo'] ."</platformUserNo>"
				."<amount>" .$data['amount'] ."</amount>"
				."<expired>" .$data['expired'] ."</expired>"
				."</request>";
		/* 签名数据 */
		$sign = cfca($req);
		/* 调用账户查询服务 */
		$service = "FREEZE";
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
 					if($str3Req["code"] == 1 && $str3Req["description"]=="操作成功")
					{	
						//操作成功					
						$data['code'] = $str3Req["code"];//1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败						
						$data['is_callback'] = 1;
						$data['status'] = 1;
						$data['pay_type']=0;
						$data['create_time']=NOW_TIME;
						if (isset($str3Req['description']))
							$data['description'] = $str3Req["description"];
						$GLOBALS['db']->autoExecute(DB_PREFIX."money_freeze",$data,'INSERT');
						$id = $GLOBALS['db']->insert_id();
						
						if($from=='web'){
				 			showIpsInfo('诚意金支付成功',url("deal#show",array("id"=>$deal_id)));	
				 		}elseif($from=='wap'){
				 			showIpsInfo('诚意金支付成功',url_wap("deal#index",array("id"=>$deal_id)));
				 		}elseif($from=='app'){
				 			echo "诚意金支付成功<br />请点左上角<b>返回</b>按钮";
				 		}
					}else{
						if($from=='web'){
				 			showIpsInfo($str3Req["description"],url("deal#show",array("id"=>$deal_id)));	
				 		}elseif($from=='wap'){
				 			showIpsInfo($str3Req["description"],url_wap("deal#index",array("id"=>$deal_id)));	
				 		}elseif($from=='app'){
				 			echo $str3Req["description"]."<br />请点左上角<b>返回</b>按钮";
				 		}
						
					}
					
			}
	
	}
	
?>