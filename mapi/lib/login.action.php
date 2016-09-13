<?php
class login
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$result = user_login ( $email, $pwd );
		if ($result ['status'])
		{
			$user_data = $GLOBALS ['user_info']; // $result['user'];
			$root ['response_code'] = 1;
			$root ['user_login_status'] = 1; // 用户登陆状态：1:成功登陆;0：未成功登陆
			$root ['info'] = "用户登陆成功";
			$root ['id'] = $user_data ['id'];
			$root ['user_name'] = $user_data ['user_name'];
			$root ['money'] = $user_data ['money'];
			$root ['money_format'] = format_price ( $user_data ['money'] ); // 用户金额
			$root ['mobile'] = $user_data ['mobile'];
			
			$result ['intro'] = $user_data ['intro'];
			$root ['email'] = $user_data ['email'];
			$root ['province'] = $user_data ['province'];
			$root ['city'] = $user_data ['city'];
			$root ['sex'] = $user_data ['sex'];
			$root ['image'] = get_user_avatar_root ( $user_data ['id'], "middle" );
			$root ['user_avatar'] = get_abs_img_root ( get_muser_avatar ( $user_data ['id'], "big" ) );
			$weibo_list = $GLOBALS ['db']->getOne ( "select weibo_url from " . DB_PREFIX . "user_weibo where user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
			$root ['weibo_list'] = $weibo_list;
			$user ['create_time_format'] = to_date ( $user_data ['create_time'], 'Y-m-d' ); // 注册时间
			$root ['create_time_format'] = $user_data ['create_time_format'];
			
			$root ['investor_status'] = intval ( $user_data ['investor_status'] );
			$root ['identify_positive_image'] = get_abs_img_root($user_data ['identify_positive_image']);
			$root ['identify_business_licence'] = get_abs_img_root($user_data ['identify_business_licence']);
			
			$is_user_investor=is_user_investor_mapi($user_data['is_investor'],$user_data['investor_status']);
			if($is_user_investor ==1)
			{
				$root['user_investor_status']=1;
				$root['user_investor_status_info']="身份认证已通过";
			}
			elseif($is_user_investor ==2){
				$root['user_investor_status']=2;
				$root['user_investor_status_info']="您的实名认证正在审核中";
			}else 
			{
				$root['user_investor_status']=0;
				$root['user_investor_status_info']="您未进行身份认证";
			}
			
			$is_tg=is_tg();//网站是否安装了第三方托管
			if( is_tg() && $user_data['ips_acct_no'] =='')
			{
				$app_url = HTML_APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$user_data['id']."&from=app";
				$root['acct_url'] = SITE_DOMAIN.$app_url;//绑定url
				$root ['is_tg']=1;//去绑定第三方
			}
			else
			{
				$root['acct_url'] = '';
				$root ['is_tg']=0;
			}
			
		} else
		{
			if ($result ['data'] == ACCOUNT_NO_EXIST_ERROR)
			{
				$err = "会员不存在";
			}
			if ($result ['data'] == ACCOUNT_PASSWORD_ERROR)
			{
				$err = "密码错误";
			}
			if ($result ['data'] == ACCOUNT_NO_VERIFY_ERROR)
			{
				$err = "会员未通过验证";
			}
			$root ['response_code'] = 0;
			$root ['info'] = $err;
			$root ['id'] = 0;
			$root ['user_name'] = $email;
			$root ['user_email'] = $email;
		}
		
		$root ['act'] = "login";
		output ( $root );
	}
}
?>