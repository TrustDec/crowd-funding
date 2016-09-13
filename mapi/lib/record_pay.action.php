<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 未充值成功继续付款页面
 */
class record_pay
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['notice_id'] );
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		
		if($user_id >0)
		{
			$root['user_login_status'] = 1;
			
			$payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment_notice where is_paid = 0 and id=".$id." and user_id=".$user_id);
			if (! $payment_info)
			{
				$result = responseErrorInfo ( "notice_id参数错误" );
				output ( $result );
			}
			
			//判断第三主托管
			$collotion=$GLOBALS['db']->getAll("select id,name,class_name from  ".DB_PREFIX."collocation where is_effect=1 limit 1");
			if( $collotion[0]['id'] >0 && $user['ips_acct_no'] )
			{
				$result ['is_tg'] =1;//表显示第三方托管
				$result ['collotion']=$collotion;
			}
			else
			{
				$result ['is_tg'] =0;//不显示
				$result ['collotion']=array();
			}
			
					
			$result ['payment_info'] = $payment_info;
			$result ['payment_list'] = getPayMentList ();
			$result ['response_code'] = 1;
			$result ['info'] = "未充值成功继续付款页面";
			}
		else
		{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		
		output ( $result );
	}
}

?>