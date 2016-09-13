<?php
class check{
	public function index(){
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);		
		
			
		$root = array();
		$root['response_code'] = 1;
		if($user_id>0)
		{
			$root['user_login_status'] = 1;
			$id = intval( $GLOBALS ['request']['id']);
			$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
			if(!$deal_item)
			{
				$root['addr'] = url("index");
				//showErr("",$ajax,url("index"));
			}
			elseif($deal_item['support_count']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
			{
				$root['addr'] = url("deal#show",array("id"=>$deal_item['deal_id']));
				//showErr("",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
			if(!$deal_info)
			{
				$root['addr'] = url("index");
				//showErr("",$ajax,url("index"));
			}
			elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
			{
				$root['addr'] = url("deal#show",array("id"=>$deal_item['deal_id']));
				//showErr("",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			
			if($deal_item['is_delivery']==1)
			{
				$consignee_id = intval( $GLOBALS ['request']['consignee_id']);
				if($consignee_id==0)
				{
					$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
					if($consignee_list)
					{
						$root['info'] = "请选择配送方式";
						//showErr("请选择配送方式",$ajax);
					}
					else
					{
						$consignee = strim( $GLOBALS ['request']['consignee']);
						$province = strim( $GLOBALS ['request']['province']);
						$city = strim( $GLOBALS ['request']['city']);
						$address = strim( $GLOBALS ['request']['address']);
						$zip = strim( $GLOBALS ['request']['zip']);
						$mobile = strim( $GLOBALS ['request']['mobile']);
						if($consignee=="")
						{
							$root['info'] = "请填写收货人姓名";
							//showErr("请填写收货人姓名",$ajax,"");	
						}
						if($province=="")
						{
							$root['info'] = "请选择省份";
							//showErr("请选择省份",$ajax,"");	
						}
						if($city=="")
						{
							$root['info'] = "请选择城市";
							//showErr("请选择城市",$ajax,"");	
						}
						if($address=="")
						{
							$root['info'] = "请填写详细地址";
							//showErr("请填写详细地址",$ajax,"");	
						}
						if(!check_postcode($zip))
						{
							$root['info'] = "请填写正确的邮编";
							//showErr("请填写正确的邮编",$ajax,"");	
						}
						if($mobile=="")
						{
							$root['info'] = "请填写收货人手机号码";
							//showErr("请填写收货人手机号码",$ajax,"");	
						}
						if(!check_mobile($mobile))
						{
							$root['info'] = "请填写正确的手机号码";
							//showErr("请填写正确的手机号码",$ajax,"");	
						}
						
						$data = array();
						$data['consignee'] = $consignee;
						$data['province'] = $province;
						$data['city'] = $city;
						$data['address'] = $address;
						$data['zip'] = $zip;
						$data['mobile'] = $mobile;
						$data['user_id'] = $user_id;
						$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data);
						$consignee_id = $GLOBALS['db']->insert_id();
						
					}
				}			
			}
			
			if(intval($consignee_id)==0&&$deal_item['is_delivery']==1)
			{
				$root['info'] = "请选择配送方式";
				//showErr("请选择配送方式",$ajax,"");	
			}
			else
			{
				$memo = strim($_REQUEST['memo']);
				if($memo!=""&&$memo!="在此填写关于回报内容的具体选择或者任何你想告诉项目发起人的话")
				es_session::set("cart_memo_".intval($id),$memo);
	
				if($deal_item['is_delivery']==0)
				{
					//	$root['addr'] =url("cart#pay",array("id"=>$id));
					//	showSuccess("",$ajax,url("cart#pay",array("id"=>$id)));
				}
				else
				{
						$root['did'] =$consignee_id;
					//  showSuccess("",$ajax,url("cart#pay",array("id"=>$id,"did"=>$consignee_id)));
				}
				
			}
		}
		else {
			$root['response_code'] = 0;
			$root['show_err'] = "未登录";
			$root['user_login_status'] = 0;
		}
		output($root);	
	}
}
?>