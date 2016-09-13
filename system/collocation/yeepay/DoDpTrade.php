<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function DoDpTradeXml($data,$pWebUrl,$pS2SUrl){	

		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$data['platformNo']."'>"
				."<requestNo>" .$data['requestNo'] ."</requestNo>"
				."<platformUserNo>" .$data['platformUserNo'] ."</platformUserNo>"
				."<amount>" .$data['amount'] ."</amount>"
				."<feeMode>" .$data['feeMode'] ."</feeMode>"			  		
				."<callbackUrl><![CDATA[" .$pWebUrl ."]]></callbackUrl>"
				."<notifyUrl><![CDATA[" .$pS2SUrl ."]]></notifyUrl>"
				."</request>";	
				
		
		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	


	function DoDpTrade($user_id,$platformNo,$pTrdAmt,$post_url,$sys='pc'){
	
		
		if($sys=='pc'){
			$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=DoDpTrade&from=".$_REQUEST['from'];//web方式返回
		
		}elseif($sys=='mobile'){
			if($_REQUEST['from']=='app')
				$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=DoDpTrade&from=app";//web方式返回
			else
				$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=DoDpTrade&from=wap";//web方式返回
			
		
		}
		//$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=DoDpTrade&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=DoDpTrade&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		//手续费
		//$fee = getCarryFee($pTrdAmt,$user);
		$fee = 0;
		$yeepay_log = array();
		$yeepay_log['code'] = 'toRecharge';
		$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		$requestNo = $GLOBALS['db']->insert_id();
		
		
		$data = array();
		$data['requestNo'] = $requestNo;//请求流水号
		$data['platformUserNo'] = $user_id;//
		$data['platformNo'] = $platformNo;// 商户编号
		$data['amount'] = $pTrdAmt - $fee;
		//$data['feeMode'] = 'PLATFORM';//费率模式PLATFORM
		$collocation_item = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."collocation where class_name='Yeepay'");
		$collocation_cfg = unserialize($collocation_item['config']);
		$data['feeMode'] = $collocation_cfg['feeMode']?$collocation_cfg['feeMode']:'USER';//费率模式PLATFORM PLATFORM 收取商户手续费 USER 收取用户手续费
		$data['fee'] = $fee;
		$data['create_time'] = NOW_TIME;
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_recharge",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = DoDpTradeXml($data,$pWebUrl,$pS2SUrl);


		
		$pSign=cfca($strxml);
		
		if($sys=='pc'){
			$act = 'bha';
		}elseif($sys=='mobile'){
			$act = 'bhawireless';
		}
		$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>
		<form name="form1" id="form1" method="post" action="'.$post_url.'/'.$act.'/toRecharge" target="_self">		
		<input type="hidden" name="sign" value="'.$pSign.'" />		
		<textarea name="req" cols="100" rows="5" style="display:none">'.$strxml.'</textarea>
	 	<input type="hidden" value="提交"></input>
	 	<div style="width:100%;text-align:center;padding:50px 0;"><img src="'.APP_ROOT.'/app/Tpl/'.app_conf("TEMPLATE").'/images/loading.gif" />页面正在跳转，请稍后...</div>
		</form>
		</body></html>
		<script language="javascript">document.form1.submit();</script>';
		//echo $html; exit;
		
		$yeepay_log = array();
		$yeepay_log['strxml'] =$strxml;
		$yeepay_log['html'] = $html;
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log,'UPDATE','id='.$requestNo);
		
		return $html;
	
	}
	
	
	function DoDpTradeCallBack($str3Req){
		//print_r($str3XmlParaInfo);
		$requestNo = $str3Req["requestNo"];
		$where = " requestNo = '".$requestNo."'";
		$sql = "update ".DB_PREFIX."yeepay_recharge set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
			//操作成功
			$data = array();
			
			$data['code'] = $str3Req["code"];//1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败
			
			if (isset($str3Req['bizType']))
				$data['bizType'] = $str3Req["bizType"];
			
			if (isset($str3Req['message']))
				$data['message'] = $str3Req["message"];
			
			if (isset($str3Req['description']))
				$data['description'] = $str3Req["description"];
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_recharge",$data,'UPDATE',$where);
			
				
			return 1;		
			
		}else{
			return 1;
		}
	}	
	
?>