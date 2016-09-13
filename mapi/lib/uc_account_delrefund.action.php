<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户提现列表删除
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_delrefund
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
			$root['response_code'] = 1;
			$id = intval($_REQUEST['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund where id = ".$id." and user_id = ".$user_id);
			if($GLOBALS['db']->affected_rows()>0)
			{
				$root['info'] = "删除成功";
				output($root);
			}
			else
			{
				$root['info'] = "删除失败";
				output($root);
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