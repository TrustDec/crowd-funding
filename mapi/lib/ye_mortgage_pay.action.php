<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户提现列表
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class ye_mortgage_pay
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		$paypassword = strim($GLOBALS['request']['paypassword']);//密码
		$deal_id=intval($GLOBALS['request']['deal_id']);
		$money = floatval($GLOBALS['request']['money']);
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			$root['response_code'] = 1;
			
			if($paypassword==''){
				$root['response_code'] = 0;
				$root['info'] = "请输入支付密码";
				output($root);	
			}
			if(md5($paypassword)!=$user['paypassword']){
				$root['response_code'] = 0;
				$root['info'] = "支付密码错误";
				output($root);	
			}
			
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_id);
			if(!$deal_info)
			{
				$root['response_code'] = 0;
				$root['info'] = "项目不存在！";
				output($root);	
			}
			
			if($user['money']>=$money){
				$re = set_mortgate($user_id,$deal_id,$money);
				if($re){
					syn_mortgate($user_id);
					$root['response_code'] = 1;
					$root['info'] = "冻结成功";
				}else{
					$root['response_code'] = 0;
					$root['info'] = "冻结失败";	
				}
			}else{
				$root['response_code'] = 0;
				$root['info'] = "您的余额不够";
			}
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>