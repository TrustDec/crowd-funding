<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_incharge
{
	public function index()
	{
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
			$root ['payment_list'] = getPayMentList ();
			
			$is_tg=intval(is_tg());//是否有安装第三方托管
			$is_view_tg=0;//0：不显示第三方托管，1显示
			if($is_tg && $user['ips_acct_no']){
				$is_view_tg=1;//显示第三方托管充值
				//$list = GetIpsBankList();
				//$ips_bank_list = $list['BankList'];
				//if(!$ips_bank_list)
					//$ips_bank_list=array();
				
				$app_url = HTML_APP_ROOT."index.php?ctl=collocation&act=DoDpTrade&user_type=0&pTrdBnkCode=parm_bnk&pTrdAmt=parm_amt&user_id=".$user_id."&from=app";
				$root['dp_url'] = SITE_DOMAIN.$app_url;//充值链接
			}
			$root['is_view_tg']=$is_view_tg;
			
		}else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
}
?>
