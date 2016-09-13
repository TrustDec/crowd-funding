<?php
class apns
{
	public function index()
	{		
		$root = array();
		$root['response_code'] = 1;
		
		$email = addslashes($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = addslashes($GLOBALS['request']['pwd']);//密码		
		
		$apns_code = addslashes($GLOBALS['request']['apns_code']);
		
		//检查用户,用户密码
		$user_info = user_check($email,$pwd);
		$user_id  = intval($user_info['id']);
		
		if ($user_id > 0){
			$sql = 'update '.DB_PREFIX."user set apns_code ='".$apns_code."' where id =".$user_id;
			$GLOBALS['db']->query($sql);
			$root['response_code'] = 1;
		}else{
			$root['response_code'] = 0;
		}
		
		output($root);
	}
}
?>