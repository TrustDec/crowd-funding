<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 注册时候发送验证码
 */
class send_register_code
{
	public function index()
	{	
		if( intval($_REQUEST['type']) == 1)//type:1 邮箱验证,0 手机验证
		{
			$email = strim( $_REQUEST ['email'] );
			$data = send_email_verify_code ($email,1);
		}
		else
		{
			
			$mobile = strim( $_REQUEST ['mobile'] );
			$data = send_code_function ( $mobile, 1,'','' );
		}
		
		
		if ($data ['status'] == 1)
		{
			$data ['response_code'] = 1;
		} else
		{
			$data ['response_code'] = 0;
		}
		output ( $data );
	}
}
?>