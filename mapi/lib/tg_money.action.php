<?php
class tg_money{
	public function index()
	{		
	    $root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$root ['response_code'] = 1;
			$root ['user_login_status'] = 1;
		} else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output ( $root );
		}
		
		if(!is_tg())
		{
			$root ['response_code'] = 0;
			output($root);
		}
		
		if($user['ips_acct_no'] =='')
		{
			$root ['response_code'] = 0;
			output($root);
		}
		$tg_info = GetIpsUserMoney($user_id,0);	
		$root['pLock'] = $tg_info['pLock'];//冻结的资金
		$root['pBalance'] = $tg_info['pBalance'];
		$ips_money=floor(($tg_info['pBalance']-$tg_info['pLock'])*100)/100;
		$root['ips_money'] = $ips_money;//托管可用余额
		$root['response_code'] = 1;
		
		output($root);
	}
}
?>