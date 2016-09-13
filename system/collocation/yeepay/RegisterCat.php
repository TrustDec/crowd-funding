<?php
	/**
	 * 
	 * @param unknown_type $pMerBillNo
	 * @return string
	 */
	function CreateNewAcctXml($IpsAcct,$pWebUrl,$pS2SUrl){	

		$strxml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
				."<request platformNo='".$IpsAcct['platformNo']."'>"
				."<platformUserNo>" .$IpsAcct['platformUserNo'] ."</platformUserNo>"
				."<requestNo>" .$IpsAcct['requestNo'] ."</requestNo>"
 				."<callbackUrl><![CDATA[" .$pWebUrl ."]]></callbackUrl>"
 				."</request>";	
				

		$strxml=preg_replace("/[\s]{2,}/","",$strxml);//去除空格、回车、换行等空白符
		$strxml=str_replace('\\','',$strxml);//去除转义反斜杠\		
		return $strxml;		
	}
	

	/**
	 * 创建新帐户
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function RegisterCat($user_id,$platformNo,$post_url,$sys='pc'){
	
		
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=RegisterCat&from=".$_REQUEST['from'];//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=RegisterCat&from=".$_REQUEST['from'];//s2s方式返回		
	
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$yeepay_log = array();
		$yeepay_log['code'] = 'toRegister';
		$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
		$requestNo = $GLOBALS['db']->insert_id();
		
		
		$data = array();
		$data['requestNo'] = $requestNo;//请求流水号
		$data['platformUserNo'] = $user_id;//
		$data['platformNo'] = $platformNo;// 商户编号
		$data['nickName'] = $user['user_name'];
		$data['realName'] = $user['identify_name'];
		$data['idCardNo'] = $user['identify_number'];//
		$data['idCardType'] = 'G2_IDCARD';
		$data['mobile'] = $user['mobile'];//
		$data['email'] = $user['email'];//
	
		$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_register",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
	
		$strxml = CreateNewAcctXml($data,$pWebUrl,$pS2SUrl);


		
		$pSign=cfca($strxml);
		
		if($sys=='pc'){
			$act = 'bha';
		}elseif($sys=='mobile'){
			$act = 'bhawireless';
		}
		$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>
		<form name="form1" id="form1" method="post" action="'.$post_url.'/'.$act.'/identityVerification" target="_self">		
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
	
	//创建新帐户回调
	function RegisterCatAcctCallBack($str3Req){
		//print_r($str3XmlParaInfo);
		$requestNo = $str3Req["requestNo"];
		$where = " requestNo = '".$requestNo."'";
		$sql = "update ".DB_PREFIX."yeepay_register set is_callback = 1 where is_callback = 0 and ".$where;
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
			//操作成功
			$data = array();
 			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_register",$str3Req,'UPDATE',$where);
 			if ($str3Req['code'] == '1'){				
				$user_id = intval($GLOBALS['db']->getOne("select platformUserNo from ".DB_PREFIX."yeepay_register where ".$where));
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user set ips_acct_no = '".$user_id."' where id = ".$user_id);	
				return 1;		
			}else{
				return 0;
			}
		}else{
			return 1;
		}
	}	
	
?>