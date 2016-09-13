<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 发送私信
 */
class send_message
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$receiver_id = intval ( $GLOBALS ['request'] ['receiver_id'] );
		$message = strim ( $GLOBALS ['request'] ['message'] );
		
		$sender_info = user_check ( $email, $password );
		$sender_id = intval ( $sender_info ['id'] );
		
		if (! $this->verifyParams ( $sender_id, $receiver_id, $message ))
		{
			return false;
		}
		
		$receiver_info = $GLOBALS ['db']->getRow ( "select user_name from " . DB_PREFIX . "user where is_effect = 1 and id = " . $receiver_id );
		if (! $receiver_info)
		{
			$data = responseErrorInfo ( "收信人不存在" );
			output ( $data );
		}
		
		// 发私信：生成发件与收件
		// 1.生成发件
		$data = array ();
		$data ['create_time'] = NOW_TIME;
		$data ['message'] = $message;
		$data ['user_id'] = $sender_id;
		$data ['dest_user_id'] = $receiver_id;
		$data ['send_user_id'] = $sender_id;
		$data ['receive_user_id'] = $receiver_id;
		$data ['user_name'] = $sender_info ['user_name'];
		$data ['dest_user_name'] = $receiver_info ['user_name'];
		$data ['send_user_name'] = $sender_info ['user_name'];
		$data ['receive_user_name'] = $receiver_info ['user_name'];
		$data ['message_type'] = "outbox";
		$data ['is_read'] = 1;
		
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_message", $data );
		
		// 2.生成收件
		$data = array ();
		$data ['create_time'] = NOW_TIME;
		$data ['message'] = $message;
		$data ['user_id'] = $receiver_id;
		$data ['dest_user_id'] = $sender_id;
		$data ['send_user_id'] = $sender_id;
		$data ['receive_user_id'] = $receiver_id;
		$data ['user_name'] = $receiver_info ['user_name'];
		$data ['dest_user_name'] = $sender_info ['user_name'];
		$data ['send_user_name'] = $sender_info ['user_name'];
		$data ['receive_user_name'] = $receiver_info ['user_name'];
		$data ['message_type'] = "inbox";
		
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_message", $data );
		
		$data = responseSuccessInfo ( "发送成功" );
		output ( $data );
	}
	private function verifyParams($user_id, $receiver_id, $message)
	{
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		if ($user_id == $receiver_id)
		{
			$data = responseErrorInfo ( "不能给自己发私信" );
			output ( $data );
		}
		if ($message == '')
		{
			$data = responseErrorInfo ( "亲！请填写私信内容!" );
			output ( $data );
		}
		
		return true;
	}
}

?>