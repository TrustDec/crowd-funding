<?php
// +----------------------------------------------------------------------
// | Fanwe 方维 用户保存配送地址
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class save_consignee
{
	public function index(){
		$root = array();
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			
			
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_consignee where user_id = ".$user_id)>10)
			{
				$root['info']='每个会员只能预设10个配送地址';
			}
			
			$id = intval($_REQUEST['id']);
			$consignee = strim($_REQUEST['consignee']);
			$province = strim($_REQUEST['province']);
			$city = strim($_REQUEST['city']);
			$address = strim($_REQUEST['address']);
			$zip = strim($_REQUEST['zip']);
			$mobile = strim($_REQUEST['mobile']);
			if($consignee=="")
			{
				$root['info']='请填写收货人姓名';	
			}
			if($province=="")
			{
				$root['info']='请选择省份';
			}
			if($city=="")
			{
				$root['info']='请选择城市';
			}
			if($address=="")
			{
				$root['info']='请填写详细地址';
			}
			if(!check_postcode($zip))
			{
				$root['info']='请填写正确的邮编';
			}
			if($mobile=="")
			{
				$root['info']='请填写收货人手机号码';
			}
			if(!check_mobile($mobile))
			{
				$root['info']='请填写正确的手机号码';
			}
			
			$data = array();
			$data['consignee'] = $consignee;
			$data['province'] = $province;
			$data['city'] = $city;
			$data['address'] = $address;
			$data['zip'] = $zip;
			$data['mobile'] = $mobile;
			$data['user_id'] = $user_id;
			
			
			
			if(!check_ipop_limit(get_client_ip(),"setting_save_consignee",5))
			$root['info']='提交太频繁';
			
			if($id>0)
			{
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data,"UPDATE","id=".$id);
			}
			
			else
			{
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data);
				$root['response_code'] = 1;
				$root['info']='保存成功';
			}
			
			//$root['info']='保存成功';
			//showSuccess("保存成功",$ajax,get_gopreview());
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>