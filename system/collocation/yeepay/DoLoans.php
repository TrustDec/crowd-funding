<?php
	
	/**
	 * 转帐
	 * @param int $pTransferType;//转账类型 1确认转账 2 取消转账
	 * @param int $deal_id  标的id	 
	 * @param string $ref_data 逗号分割的, 1：投资,填还款日期(int)  ; 2代偿，3代偿还款列表; 4债权转让: id; 5结算担保收益:金额，如果为0,则取fanwe_deal.guarantor_pro_fit_amt ;
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function DoLoans($pTransferType, $deal_id, $repay_start_time=0, $platformNo,$post_url){
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Yeepay&class_act=DoLoans&from=".$_REQUEST['from']."&repay_start_time=".$repay_start_time;//web方式返回
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=notify&class_name=Yeepay&class_act=DoLoans&from=".$_REQUEST['from']."&repay_start_time=".$repay_start_time;//s2s方式返回
		
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		if($deal['type']==3){
			$t_arr = $GLOBALS['db']->getAll("select ycp.*,dorder.type as order_type,dorder.progress as order_progress,dorder.deal_id,dorder.user_id from ".DB_PREFIX."yeepay_cp_transaction as ycp left join ".DB_PREFIX."deal_order as dorder on dorder.requestNo=ycp.requestNo where ycp.code = 1 and ycp.tenderId = ".$deal_id." and ycp.is_complete_transaction = 0 and (dorder.progress=3 or dorder.progress=5)");
		}else{
			 
			$t_arr = $GLOBALS['db']->getAll("select ycp.*,dorder.type as order_type,dorder.deal_id,dorder.user_id from ".DB_PREFIX."yeepay_cp_transaction as ycp left join ".DB_PREFIX."deal_order as dorder on dorder.requestNo=ycp.requestNo where ycp.code = 1 and ycp.tenderId = ".$deal_id." and ycp.is_complete_transaction = 0");
		}
		
  		if(!$repay_start_time){
 			$repay_start_time=get_gmtime();
 		}
  		foreach($t_arr as $key => $t_r)
		{

			$GLOBALS['db']->query("update ".DB_PREFIX."yeepay_cp_transaction set repay_start_time = '".$repay_start_time."' where is_callback = 0 and requestNo = ".$t_r["requestNo"]);
			
			$data = array();
			$requestNo = $t_r["requestNo"]; 
			$data['requestNo'] = $requestNo;//请求流水号
			$data['platformNo'] = $platformNo;// 商户编号
			if($pTransferType==1){
				$data['mode'] = "CONFIRM";	
			}else{
				$data['mode'] = "CANCEL";	
			}
			//print_r($data['mode']);exit;
			
			/* 请求参数 */  
			$req = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
			."<request platformNo=\"".$platformNo."\">"
			."<requestNo>".$requestNo."</requestNo>"
			."<mode>".$data['mode']."</mode>"
			."<notifyUrl><![CDATA[" .$pS2SUrl ."]]></notifyUrl>"
			."</request>";
			
			$yeepay_log = array();
			$yeepay_log['code'] = 'bhaController';
			$yeepay_log['create_date'] = to_date(NOW_TIME,'Y-m-d H:i:s');
			$yeepay_log['strxml'] = $req;
			$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$yeepay_log);
			$log_id = $GLOBALS['db']->insert_id();
			
			/* 签名数据 */
			$sign = cfca($req);
			/* 调用账户查询服务 */
			$service = "COMPLETE_TRANSACTION";
			$ch = curl_init($post_url."/bhaexter/bhaController");
			curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER=>0,
			CURLOPT_SSL_VERIFYHOST=>0,
			CURLOPT_POSTFIELDS => 'service=' . $service . '&req=' . rawurlencode($req) . "&sign=" . rawurlencode($sign)
			));
			$resultStr = curl_exec($ch);
			
			$result = array();
 			if (empty($ch)){

			}else{
				
				$GLOBALS['db']->query("update ".DB_PREFIX."yeepay_cp_transaction set is_callback = 1 where is_callback = 0 and requestNo = ".$requestNo);
				
				require_once(APP_ROOT_PATH.'system/collocation/ips/xml.php');
				$str3ParaInfo = @XML_unserialize($resultStr);
				//print_r($str3ParaInfo);exit;
				$str3Req = $str3ParaInfo['response'];
				
				$result['pErrCode'] = $str3Req["code"];
				$result['pErrMsg'] = $str3Req["description"];
				if($str3Req["code"] == 1)
				{
					if($data['mode']=='CONFIRM'){
						$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_complete_transaction = 1 where is_callback = 1 and requestNo = ".$requestNo;
					}elseif($data['mode']=='CANCEL'){
						//表示取消转账
						$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_complete_transaction = 2 where is_callback = 1 and requestNo = ".$requestNo;
					}
					$GLOBALS['db']->query($sql);
 					$deal_load = array();
 					if($data['mode']=='CONFIRM'){
						$deal_load['is_complete_transaction'] = 1;//1#转账成功
					}elseif($data['mode']=='CANCEL'){	
						$deal_load['is_complete_transaction'] = 2;//表示取消转账	
						
					}
 					//$deal_load['progress']=$t_r['order_type'];
					if($t_r['share_fee']>0){
						$deal_load['share_status']=1;
					}
					$where = " requestNo = ".$requestNo;
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$deal_load,'UPDATE',$where);
					if($GLOBALS['db']->affected_rows()){
						//deal_order_progress($t_r['deal_id'],$t_r['user_id'],5);
					}
					if($pTransferType ==2)
					{
							require_once APP_ROOT_PATH."system/libs/user.php";
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$t_r["tenderOrderNo"]);
							/*
							$refund_order_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where deal_id=".$deal_id." and is_refund = 0 and order_status = 3");
							foreach($refund_order_list as $k=>$v)
							{
								$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$v['id']);
								if($GLOBALS['db']->affected_rows()>0)
								{	
									
									//抽奖订单 把抽奖号标为已退款
									if($v['type'] ==3)
									{
										$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=3 where order_id=".intval($v['id'])."");
									}
									
								}
							}
							*/
							//同步商品记录
							syn_deal($deal_id);
							$deal_item_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id=".intval($deal_id));
							foreach($deal_item_list as $k=>$v)
							{									
								$deal_item['support_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
								$deal_item['support_amount'] = floatval($GLOBALS['db']->getOne("select sum(deal_price) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
								$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item", $deal_item, $mode = 'UPDATE', "id=".intval($v['id']), $querymode = 'SILENT');
							}
				
							$remain = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."DealOrder where deal_id=".$deal_id." and is_refund = 0 and order_status = 3");
							if($remain==0)
							{
								$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache='' where id=".intval($deal_id)."");
								$GLOBALS['db']->query("update ".DB_PREFIX."deal_log set deal_info_cache='' where deal_id=".intval($deal_id)."");
 							}
							else
							{							
								showIpsInfo("批量退款中，请勿刷新页面，剩余".$remain."条订单未退款",get_domain().APP_ROOT.'/'.URL_NAME.'?m=Deal&a=online_index');
							}
					}
					
					if($data['mode']=='CONFIRM'){
						$log['deal_id'] = $deal_id;
						$log['money'] = $t_r['targetAmount'];
						$log['create_time'] = get_gmtime();
						$log['log_info'] = '';
						$log['comissions'] = $t_r['fee'];
						$log['share_fee'] = $t_r['share_fee'];
						$log['delivery_fee'] = $t_r['delivery_fee'];
						$log['requestNo'] = $t_r['requestNo'];
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_pay_log",$log,'INSERT');
					}
				}else{
					$str =  "pErrCode:".$result['pErrCode'].",pErrMsg:".$result['pErrMsg'];
					$error_data = array('html'=>$str);
					$GLOBALS['db']->autoExecute(DB_PREFIX."yeepay_log",$error_data,'UPDATE',"id = ".$log_id);
				}
			
			}
		}
		if(!$t_arr){
			if($pTransferType ==2){
				showIpsInfo("已经批量退款过了",get_domain().APP_ROOT.'/'.URL_NAME.'?m=Deal&a=online_index');
			}
		}
		
		 
	}
		//回调
		function DoLoansCallBack($str3Req){
			
			$requestNo = $str3Req["requestNo"];
			$platformNo = $str3Req["platformNo"];
			
			$GLOBALS['db']->query("update ".DB_PREFIX."yeepay_cp_transaction set is_callback = 1 where is_callback = 0 and requestNo = ".$requestNo);
			
			$result['pErrCode'] = $str3Req["code"];
			$result['pErrMsg'] = $str3Req["message"];
			$result['pIpsAcctNo'] = $str3Req["requestNo"];	
			 
			if($str3Req["code"] == 1)
			{
				if($str3Req["status"]=='CONFIRM'){
					$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_complete_transaction = 1 where is_callback = 1 and requestNo = ".$requestNo;
				}elseif($str3Req["status"]=='CANCEL'){
					$sql = "update ".DB_PREFIX."yeepay_cp_transaction set is_complete_transaction = 2 where is_callback = 1 and requestNo = ".$requestNo;
				}
				
				$GLOBALS['db']->query($sql);
				if($str3Req["status"]=='CONFIRM'){
					$t_r = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where code = 1  and is_complete_transaction = 1 and requestNo=$requestNo");
				}elseif($str3Req["status"]=='CANCEL'){
					$t_r = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."yeepay_cp_transaction where code = 2  and is_complete_transaction = 1 and requestNo=$requestNo");
				}
				
				
				$result['tenderId'] = $t_r["tenderId"];	
				$deal_load = array();
				if($str3Req["status"]=='CONFIRM'){
					$deal_load['is_complete_transaction'] = 1;//1#转账成功
				}elseif($str3Req["status"]=='CANCEL'){
					$deal_load['is_complete_transaction'] = 2;//1#取消成功
				}
				
				if($t_r['share_fee']>0){
					$deal_load['share_status']=1;
				}
				
				$where = " requestNo = ".$requestNo;
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$deal_load,'UPDATE',$where);
				
				if($str3Req["status"]=='CONFIRM'){
					$log['deal_id'] = $t_r['tenderId'];
					$log['money'] = $t_r['targetAmount'];
					$log['create_time'] = get_gmtime();
					$log['log_info'] = '';
					$log['comissions'] = $t_r['fee'];
					$log['share_fee'] = $t_r['share_fee'];
					$log['delivery_fee'] = $t_r['delivery_fee'];
					$log['requestNo'] = $t_r['requestNo'];
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_pay_log",$log,'INSERT');
				}
				
			}
			
			return $result;
 	}
	
?>