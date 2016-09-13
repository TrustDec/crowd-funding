<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 充值记录未支付则继续支付使用
 */
class record_go_pay
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['notice_id'] );
		$payment_id = intval ( $GLOBALS ['request'] ['payment'] );
		$ips_bill_no_pay = intval ( $GLOBALS ['request'] ['ips_bill_no_pay'] ); // 1:托管支付，0表不是
		
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
		
		// 参数验证区
		
		if ($id <= 0)
		{
			$root = responseErrorInfo ( "notice_id参数错误" );
			output ( $root );
		}
		$payment_notice = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment_notice where id=" . $id . " and user_id = " . $user_id );
		if ($payment_notice ['is_paid'] == 1)
		{
			$root = responseErrorInfo ( "已支付成功无需再支付" );
			output ( $root );
		}
		
		if ($ips_bill_no_pay == 1)
		{
			// 判断托管是否开启
			if (! is_tg ())
			{
				$root ['response_code'] = 0;
				$root ['info'] = "网站第三方托管没有开启";
				output ( $root );
			}
			if ($user ['ips_acct_no'] == '')
			{
				$root ['response_code'] = 0;
				$root ['info'] = "您第三方托管没有开启";
				output ( $root );
			}
			$url = APP_ROOT . "/index.php?ctl=collocation&act=DoDpTrade&pTrdAmt=" . $payment_notice ['money'] . "&user_type=0&user_id=" . $user_id . "&from=" . $GLOBALS ['request'] ['from'];
			$root ['pay_wap'] = str_replace ( "/mapi", "", SITE_DOMAIN . $url );
			$root ['pay_code'] = '';
			$root ['pay_type'] = 1;
			$root ['response_code'] = 1;
			$root ['info'] = '去充值';
		} else
		{
			$payment_info = $this->getPayMentInfo ( $payment_id );
			if (! $payment_info)
			{
				$root = responseErrorInfo ( "payment无效支付方式" );
				output ( $root );
			}
			
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "payment_notice set payment_id=$payment_id where id=$id " );
			
			require_once APP_ROOT_PATH . "system/payment/" . $payment_info ['class_name'] . "_payment.php";
			$payment_class = $payment_info ['class_name'] . "_payment";
			$payment_object = new $payment_class ();
			$pay = $payment_object->get_payment_code ( $id );
			
			$root = responseSuccessInfo ( "", 0, "充值未完成继续充值使用" );
			$root ['pay_code'] = $pay ['pay_code'];
			$root ['pay_type'] = 1;
			$root ['pay_wap'] = $pay ['notify_url'];
		}
		
		output ( $root );
	}
	
	/**
	 * 获取支付方式实体类
	 */
	private function getPayMentInfo($payment_id)
	{
		$payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where id = " . $payment_id );
		return $payment_info;
	}
}

?>