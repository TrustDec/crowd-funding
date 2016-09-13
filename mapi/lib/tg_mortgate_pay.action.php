<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class tg_mortgate_pay
{
	public function index()
	{
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] );//项目金额
		$paypassword=strim($GLOBALS ['request']['paypassword']);
		$pTrdAmt=floatval($GLOBALS ['request']['money']);
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
		
		if($paypassword==''){
				$root['response_code'] = 0;
			$root['info'] = "请输入支付密码";
			output($root);	
		}
		if(md5($paypassword)!=$user['paypassword']){
			$root['response_code'] = 0;
			$root['info'] = "支付密码错误";
			output($root);	
		}
		
	
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_id);
		if(!$deal_info)
		{
			$root['response_code'] = 0;
			$root['info'] = "项目不存在！";
			output($root);	
		}
		
		$is_tg=is_tg();
		if(!$is_tg)
		{
			$root['response_code'] = 0;
			$root['info'] = "网站第三方托管没有开启！";
			output($root);
		}
		if($user['ips_acct_no'] =='')
		{
			$root['response_code'] = 0;
			$root['info'] ="你第三方托管没有开启！";
			output($root);
		}
		
		if($deal_info['ips_bill_no'] =='')
		{
			$root['response_code'] = 0;
			$root['info'] ="项目不支持第三方托管！";
			output($root);
		}
		
		$tg_url=HTML_APP_ROOT."/index.php?ctl=collocation&act=SincerityGoldFreeze&user_type=0&user_id=".$user_id."&pTrdAmt=".$pTrdAmt."&deal_id=".$deal_id."&from=app";
		$root['dp_url'] = SITE_DOMAIN.$tg_url;
		$root['response_code'] =1;
		$root['info'] ="缴纳诚意金";		
			
		output ( $root );
	}
}
?>
