<?php

class uc_save_pwd{
	
	public function index()
	{

		
		$root = array();

		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		$user_pwd = addslashes(htmlspecialchars(trim($GLOBALS['request']['user_pwd'])));
		$user_pwd_confirm = addslashes(htmlspecialchars(trim($GLOBALS['request']['confirm_user_pwd'])));
				
		if($user_pwd != $user_pwd_confirm)
		{
			$root['response_code'] = 0;
			$root['show_err'] = '密码确认失败';
			output($root);
		}
			
		if($user_pwd == null || $user_pwd =='')
		{
			$root['response_code'] = 0;
			$root['show_err'] = '请输入密码';
			output($root);
		}
				
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){		
		
			$code = $user['code'];
			
			if($user_id == 0)
			{
				$root['response_code'] = 0;
				$root['show_err'] = '验证码错误';
				output($root);
			}else{
							
				$new_pwd = md5($user_pwd.$code);
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user set user_pwd='".$new_pwd."' where id = ".$user_id);
				
				$root['response_code'] = 1;
				$root['show_err'] = "密码更新成功!";//$GLOBALS['lang']['MOBILE_BIND_SUCCESS'];
				output($root);
			}
		}
		output($root);
	}
}
?>