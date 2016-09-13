<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户提现表单提交
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_submitrefund
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$money = floatval ( $GLOBALS ['request'] ['money'] );
		$memo = strim ( $GLOBALS ['request'] ['memo'] );
		
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if ($user_id > 0)
		{
			if ($user ['is_bank'] <= 0)
			{
				$result = responseErrorInfo ( "亲!您的银行账户还未设置!" );
				$result ['is_bank'] = $user ['is_bank'];
				output ( $result );
			}
			
			$root = array ();
			$root ['user_login_status'] = 1;
			$root ['is_bank'] = $user ['is_bank'];
			$root ['response_code'] = 1;
			
			if ($money <= 0)
			{
				$root ['info'] = "提现金额出错";
				output ( $root );
			}
			
			$ready_refund_money = floatval ( $GLOBALS ['db']->getOne ( "select sum(money) from " . DB_PREFIX . "user_refund where user_id = " . $user_id . " and is_pay = 0" ) );
			
			if ($ready_refund_money + $money > $user ['money'])
			{
				$root ['info'] = "提现超出限制";
				output ( $root );
			}
			
			$refund_data ['money'] = $money;
			$refund_data ['user_id'] = $user_id;
			$refund_data ['create_time'] = NOW_TIME;
			$refund_data ['memo'] = $memo;
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_refund", $refund_data );
			$root ['info'] = "申请成功";
		} else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
}
?>