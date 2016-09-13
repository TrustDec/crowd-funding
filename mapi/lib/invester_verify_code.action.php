<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 投资人申请时使用发送验证码接口
 */
class invester_verify_code
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$mobile = trim ( $GLOBALS ['request'] ['mobile'] );
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$data = send_code_function ( $mobile, 1, $email, $pwd,1 );
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
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$mobile = trim ( $GLOBALS ['request'] ['mobile'] );
		
		$root = array ();
		$root ['response_code'] = 0;
		
		if (! check_ipop_limit ( get_client_ip (), "mobile_verify", 60, 0 ))
		{
			$root ['info'] = '短信发送太快,请稍后再试';
			output ( $root );
		}
		
		if (app_conf ( "SMS_ON" ) == 0)
		{
			$root ['info'] = '短信未开启';
			output ( $root );
		}
		
		if (! check_mobile ( $mobile ))
		{
			$root ['info'] = '请填写正确的手机号码';
			output ( $root );
		}
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if ($user_id > 0)
		{
			$verify_code = $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and create_time>=" . (TIME_UTC - 180) . " ORDER BY id DESC" );
			if (intval ( $verify_code ) == 0)
			{
				
				// 如果数据库中存在验证码，则取数据库中的（上次的 ）；确保连接发送时，前后2条的验证码是一至的.==为了防止延时
				// 开始生成手机验证
				$verify_code = rand ( 1111, 9999 );
				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mobile_verify_code", array (
						"verify_code" => $verify_code,
						"mobile" => $mobile,
						"create_time" => get_gmtime (),
						"client_ip" => get_client_ip () 
				), "INSERT" );
			}
			
			// 使用立即发送方式
			$result = send_verify_code_app ( $mobile, $verify_code ); //
			
			if ($result ['status'] == 1)
			{
				$root ['response_code'] = 1;
				$root ['info'] = '验证短信已经发送，请注意查收';
			} else
			{
				$root ['info'] = "验证码发送失败";
			}
		} else
		{
			$root ['user_login_status'] = 0;
			$root ['info'] = '未登陆';
		}
		output ( $root );
	}
}
?>