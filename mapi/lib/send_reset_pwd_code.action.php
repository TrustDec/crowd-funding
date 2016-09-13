<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 忘记密码发送验证码
 */
class send_reset_pwd_code
{
	public function index()
	{
		$mobile = addslashes ( htmlspecialchars ( trim ( $_REQUEST ['mobile'] ) ) );
		
		$user_info=$GLOBALS ['db']->getRow( "select * from  " . DB_PREFIX . "user where mobile= '".$mobile."' ");
		if(!$user_info)
		{
			$data ['status'] = 0;
			$data ['info'] = "用户不存在!";
			$data ['response_code'] = 0;
			output ( $data );
		}
		
		if($user_info['is_effect'] ==0)
		{
			$data ['status'] = 0;
			$data ['info'] = "用户已无效!";
			$data ['response_code'] = 0;
			output ( $data );
		}
		
		$data = send_code_function ( $mobile, 0, '', '' );
		
		if ($data ['status'] == 1)
		{
			$data ['response_code'] = 1;
		} else
		{
			$data ['response_code'] = 0;
		}
		output ( $data );
	}
	private function indexError()
	{
		$mobile = addslashes ( htmlspecialchars ( trim ( $GLOBALS ['request'] ['mobile'] ) ) );
		
		$root = array ();
		
		if (app_conf ( "SMS_ON" ) == 0)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = '短信未开启';
			output ( $root );
		}
		
		if ($mobile == '')
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = '请输入你的手机号';
			output ( $root );
		}
		
		if (! check_mobile ( $mobile ))
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = '请填写正确的手机号码';
			output ( $root );
		}
		
		if (! check_ipop_limit ( get_client_ip (), "mobile_verify", 60, 0 ))
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = '短信发送太快,请稍后再试';
			output ( $root );
		}
		
		$sql = "select id,verify from " . DB_PREFIX . "user where mobile = '" . $mobile . "'";
		$user_info = $GLOBALS ['db']->getRow ( $sql );
		$user_id = intval ( $user_info ['id'] );
		$code = intval ( $user_info ['verify'] );
		
		if ($user_id == 0)
		{
			// $field_show_name = $GLOBALS['lang']['USER_TITLE_mobile'];
			$root ['response_code'] = 0;
			$root ['show_err'] = '手机号码不存在或被禁用';
			output ( $root );
		}
		
		// 开始生成手机验证
		if ($code == 0)
		{
			// 已经生成过了，则使用旧的验证码；反之生成一个新的
			$code = rand ( 1111, 9999 );
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set verify = '" . $code . "',verify_setting_time = '" . TIME_UTC . "' where id = " . $user_id );
		}
		
		// 使用立即发送方式
		$result = send_verify_code_app ( $mobile, $code ); //
		
		if ($result ['status'] == 1)
		{
			$root ['response_code'] = 1;
			$root ['info'] = '验证短信已经发送，请注意查收';
		} else
		{
			$root ['info'] = "验证码发送失败";
		}
		
		output ( $root );
	}
}
?>