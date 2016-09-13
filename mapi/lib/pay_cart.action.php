<?php

// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class pay_cart
{
	public function index()
	{
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$ips_bill_no_pay = intval ( $GLOBALS ['request'] ['ips_bill_no_pay'] ); //是否托管支付
		                                              
		 // 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
			
			//检查ios app是否在审核中
			$ios_pack_version=strim($GLOBALS['request']['ios_pack_version']);
			$ios_is_check=check_ios_is_audit($GLOBALS ['m_config']['ios_check_version'],$ios_pack_version);
			if($ios_is_check !=1)
			{
				if($ips_bill_no_pay ==1)
				{
					$collotion=$GLOBALS['db']->getAll("select id,name,class_name from  ".DB_PREFIX."collocation where is_effect=1 limit 1");
					$root ['collotion']=$collotion;
				}
				else{
					$user ['money_format'] = format_price ( $user ['money'] ); // 可用资金
					$root ['money_format'] = $user ['money_format'];
					$root ['money'] = $user ['money'];
					$root ['score'] = intval($user['score']);//积分
					$root ['payment_list'] = getPayMentList ();
				}
			}
			
			$consignee_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user_consignee where user_id = " . intval ( $user ['id'] ) );
			$root ['consignee_list'] = $consignee_list;
			
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