<?php
class verify_code
{
	public function index()
	{
		$user_name = strim ( $GLOBALS ['request'] ['email'] );
		$user_pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$mobile = strim ( $GLOBALS ['request'] ['mobile'] );
		$mobile_code = strim ( $GLOBALS ['request'] ['mobile_code'] );
		
		$user = user_check ( $user_name, $user_pwd );
		$user_id = intval ( $user ['id'] );
		
		if ($user_id > 0)
		{
			// 删除超过5分钟的验证码
			$GLOBALS ['db']->query ( "DELETE FROM " . DB_PREFIX . "mobile_verify_code WHERE create_time <=" . (get_gmtime () - 300) );
			
			if ($user ['mobile'] != '')
			{
				if ($user ['mobile'] == $mobile)
				{
					if ($mobile_code != $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and create_time>=" . (TIME_UTC - 180) . " ORDER BY id DESC" ))
					{
						$root ['user_login_status'] = 1;
						$root ['response_code'] = 0;
						$root ['info'] = "短信验证码错误或已失效";
						output ( $root );
					} else
					{
						$root ['user_login_status'] = 1;
						$root ['response_code'] = 1;
						$root ['info'] = "亲!验证成功!";
						output ( $root );
					}
				} else
				{
					$root ['user_login_status'] = 1;
					$root ['response_code'] = 0;
					$root ['info'] = "亲!该手机号不是原来绑定的手机号!";
					output ( $root );
				}
			}
			
			// 短信验证码
			if ($mobile_code != $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and create_time>=" . (TIME_UTC - 180) . " ORDER BY id DESC" ))
			{
				$root ['user_login_status'] = 1;
				$root ['response_code'] = 0;
				$root ['info'] = "短信验证码错误或已失效";
				output ( $root );
			}
			if ($GLOBALS ['db']->getOne ( "select count(*) from  " . DB_PREFIX . "user where 1=1  and mobile='" . $mobile . "'" ) > 0)
			{
				$root ['user_login_status'] = 1;
				$root ['response_code'] = 0;
				$root ['info'] = "该手机号已经存在";
				output ( $root );
			} else
			{
				
				$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set mobile='" . $mobile . "' where id = " . $user_id );
				$root ['response_code'] = 1;
				$root ['user_login_status'] = 1;
				$root ['info'] = "手机号码绑定成功";
				output ( $root );
			}
		} else
		{
			$root ['response_code'] = 0;
			$root ['user_login_status'] = 0;
			output ( $root );
		}
	}
}
?>