<?php
class uc_change_pwd{
	public function index()
	{

		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		$new_pwd = strim($GLOBALS['request']['newpassword']);//新密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);			
			
		$root = array();
				
		if($user_id>0)
		{
			$root['user_login_status'] = 1;	
				
			if (strlen($new_pwd) == 0){
				$root['response_code'] = 0;
				$root['show_err'] = "登陆密码不能为空";
			}else{
			
				$new_pwd = md5($new_pwd.$user['code']);			
				$sql = "update ".DB_PREFIX."user set user_pwd = '".$new_pwd."' where id = {$user_id}";
				$GLOBALS['db']->query($sql);
				$rs = $GLOBALS['db']->affected_rows();
				if ($rs > 0){
					$root['response_code'] = 1;									
					$root['show_err'] = "密码更新成功!";
				}else{
					$root['response_code'] = 0;
					$root['show_err'] = "密码更新失败!";
				}
			}
		}
		else
		{
			$root['response_code'] = 0;
			$root['user_login_status'] = 0;		
			$root['show_err'] = "原始密码不正确";
		}		
	
		output($root);
	}
}
?>