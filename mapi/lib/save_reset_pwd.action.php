<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 忘记密码提交新修改后接口
 */
class save_reset_pwd
{
	public function index()
	{
		$mobile = addslashes ( htmlspecialchars ( trim ( $_REQUEST ['mobile'] ) ) );
		$verify = addslashes ( htmlspecialchars ( trim ( $_REQUEST ['mobile_code'] ) ) );
		$user_pwd = addslashes ( htmlspecialchars ( trim ( $_REQUEST ['user_pwd'] ) ) );
		$user_pwd_confirm = addslashes ( htmlspecialchars ( $_REQUEST ['user_pwd_confirm'] ) );
		
		if (! $this->verifyParams ( $mobile, $verify, $user_pwd, $user_pwd_confirm ))
		{
			return;
		}
		
		$sql = "select id from " . DB_PREFIX . "user where mobile = " . $mobile;
		$user_info = $GLOBALS ['db']->getRow ( $sql );
		$user_id = intval ( $user_info ['id'] );
		
		if ($user_id <= 0)
		{
			$root ['response_code'] = 0;
			$root ['info'] = '用户不存在';
			output ( $root );
		} else
		{
			
			$new_pwd = md5 ( $user_pwd );
			
			$sql = "update " . DB_PREFIX . "user set user_pwd='" . $new_pwd . "' where id = " . $user_id;
			$GLOBALS ['db']->query ( $sql );
			
			$root ['response_code'] = 1;
			$root ['info'] = "密码更新成功!";
			output ( $root );
		}
	}
	private function verifyParams($mobile, $verify, $user_pwd, $user_pwd_confirm)
	{
		if ($user_pwd == '')
		{
			$root ['response_code'] = 0;
			$root ['info'] = '请输入密码';
			output ( $root );
		}
		
		if ($verify == "")
		{
			$root ['response_code'] = 0;
			$root ['info'] = '验证码错误';
			output ( $root );
		}
		
		if ($mobile == '')
		{
			$root ['response_code'] = 0;
			$root ['info'] = '请输入你的手机号';
			output ( $root );
		}
		
		if ($user_pwd != $user_pwd_confirm)
		{
			$root ['response_code'] = 0;
			$root ['info'] = '密码确认失败';
			output ( $root );
		}
		
		if (! check_mobile ( $mobile ))
		{
			$root ['response_code'] = 0;
			$root ['info'] = '请填写正确的手机号码';
			output ( $root );
		}
		
		if ($verify != $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and create_time>=" . (TIME_UTC - 180) . " ORDER BY id DESC" ))
		{
			$root ['response_code'] = 0;
			$root ['info'] = "短信验证码错误或已失效";
			output ( $root );
		}
		return true;
	}
}
?>