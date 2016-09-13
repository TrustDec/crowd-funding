<?php
class userModule{
	 
	public function login()
	{
		
		if($GLOBALS['user_info']){
			app_redirect(url_wap("settings#index"));
 		}
		
 		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('user_login.html', $cache_id))
		{		
			$GLOBALS['tmpl']->assign("page_title","会员登录");
		}
		if($_REQUEST['target']){
			$GLOBALS['tmpl']->assign("target",$_REQUEST['target']);
		}else{
			if($_REQUEST['deal_id']){
				$target = "URL-dealID-".intval($_REQUEST['deal_id']);
			}
			$GLOBALS['tmpl']->assign("target",$target);
		}
		$GLOBALS['tmpl']->display("user_login.html",$cache_id);
	}
	//
	public function app_login(){
		
		$GLOBALS['tmpl']->display("user_app_login.html",$cache_id);
	}
	
	public function do_login()
	{		
		if(!$_POST)
		{
 			app_redirect(APP_ROOT."/");
		}
		foreach($_POST as $k=>$v)
		{
			$_POST[$k] = strim($v);
		}
		$ajax = intval($_REQUEST['ajax']);
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		// if(check_ipop_limit(get_client_ip(),"user_dologin",5))
		$result = do_login_user($_POST['email'],$_POST['user_pwd']);
		// else
		// showErr("提交太快",$ajax,url_wap("user#login"));		
		if($result['status'])
		{
			
 			$s_user_info = es_session::get("user_info");
			if(intval($_POST['auto_login'])==1)
			{
				//自动登录，保存cookie
				$user_data = $s_user_info;
				es_cookie::set("email",$user_data['email'],3600*24*30);			
				es_cookie::set("user_pwd",md5($user_data['user_pwd']."_EASE_COOKIE"),3600*24*30);
				
			}
			if($ajax==0&&trim(app_conf("INTEGRATE_CODE"))=='')
			{
 				$redirect = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:url_wap("index");
				if($_POST['target']){
					$redirect = create_target_url( $_POST['target'],1);
				}
//				if(strpos('/?',$redirect)!==false){
//					$redirect = url_wap("index",array('is_login'=>1));
//				}else{
//					$redirect = $redirect.'?is_login=1&first_post=1';
//				}
				
						
				app_redirect($redirect);
			}
			else
			{		
			 
				$jump_url = get_gopreview_wap();	
				if($_POST['target']){
					$jump_url =  create_target_url( $_POST['target'],1);
				}			
				if($ajax==1)
				{
					if($GLOBALS['is_tg']&&!$GLOBALS['user_info']['ips_acct_no']){
						$return['status'] = 2;
						$jump_url=get_domain().APP_ROOT."/wap/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id'];
					}else{
						$return['status'] = 1;
					}
					
					$return['info'] = "登录成功";
					$return['data'] = $result['msg'];
					$return['user_info'] = $result['user'];
//					$jump_url = $jump_url.'&is_login=1&first_post=1';
					$return['jump'] = $jump_url;
					if(es_session::get('is_app_type')){
						$return['type'] = 'app';
					}else{
						$return['type'] = 'wap';
					}
					// showSuccess("登录成功",$ajax,$jump_url);					
					ajax_return($return);
				}
				else
				{
 					$GLOBALS['tmpl']->assign('integrate_result',$result['msg']);	
// 					$jump_url = $jump_url.'&is_login=1&first_post=1';		
					showSuccess("登录成功",$ajax,$jump_url);
				}
			}
		}
		else
		{
 			if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
			{
				$err = "会员不存在";
			}
			if($result['data'] == ACCOUNT_PASSWORD_ERROR)
			{
				$err = "密码错误";
			}
                        if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
			{
				$err = "用户未通过验证";
				if(app_conf("MAIL_ON")==1&&$ajax==0)
				{				
					$GLOBALS['tmpl']->assign("page_title",$err);
					$GLOBALS['tmpl']->assign("user_info",$result['user']);
					$GLOBALS['tmpl']->display("verify_user.html");
					exit;
				}
				
			}
			showErr($err,$ajax);
		}
	}
	public function register()
	{
                 //links
        if($GLOBALS['user_info']){
			app_redirect(url_wap("settings#index"));
		}
 		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('user_register.html', $cache_id))
		{		
			$GLOBALS['tmpl']->assign("page_title","会员注册");
		}
		if($_REQUEST['target']){
			$GLOBALS['tmpl']->assign("target",$_REQUEST['target']);
		}
		$GLOBALS['tmpl']->display("user_register.html",$cache_id);
	}
	public function do_register()
		{
			$email = strim($_REQUEST['email']);
			require_once APP_ROOT_PATH."system/libs/user.php";
 			$return = $this->register_check_all();
			 
 			if($return['status']==0)
			{
				ajax_return($return);
			}		
			$user_data = $_POST;
			foreach($_POST as $k=>$v)
			{
				$user_data[$k] = strim($v);
			}	
             //开启邮箱验证
             if(app_conf("USER_VERIFY")!=3){
                 $user_data['is_effect'] = 1;
            }else{
            	$user_data['is_effect'] = 0;
            }
             if(intval($GLOBALS['ref_uid']) >0)
            {
            	$user_data['pid'] = intval($GLOBALS['ref_uid']);//推荐人id
            	$user_data['is_send_referrals'] = 1;//未发放返利给推荐人
            }else
            {
            	$user_data['pid'] = 0;//没有推荐人
            	$user_data['is_send_referrals'] = 0;//不用发放返利
            }
 		
			$res = save_user($user_data);
		
	
			if($res['status'] == 1)
			{
				if(!check_ipop_limit(get_client_ip(),"user_do_register",5))
				showErr("提交太快",1);	
				
				$user_id = intval($res['data']);
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
				if($user_info['is_effect']==1)
				{
					//发放返利给推荐人
					if($user_info['pid'] >0)
					{
						send_referrals($user_info);
					}
					//在此自动登录
					//send_register_success(0,$user_data);
					$result = do_login_user($user_data['user_name'],$user_data['user_pwd']);
				//	ajax_return(array("status"=>1,"jump"=>get_gopreview()));
					if($GLOBALS['is_tg']){
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"info"=>"注册成功","jump"=>url_wap("user#register_two")));
					}else{
						if($_POST['target']){
							$jump_url =  create_target_url( $_POST['target'],1);
						}else{
							$jump_url = get_gopreview();
						}
						$jump_url = $jump_url.'&is_login=1';
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"jump"=>$jump_url));
					}
						
				}
				else
				{
                    if(app_conf("USER_VERIFY")==1){
                        ajax_return(array("status"=>1,"jump"=>url_wap("user#mail_check",array('uid'=>$user_id))));
                    }else if(app_conf("USER_VERIFY")==3){
                    	ajax_return(array("status"=>0,"info"=>"请等待管理员审核"));
                    }
						
				}                     
			}
			else
			{
				$error = $res['data'];	
				if($error['field_name']=="user_name")
				{
					$data[] = array("type"=>"form_success","field"=>"email","info"=>"");	
					$field_name = "会员帐号";
				}
				if($error['field_name']=="email")
				{
					$data[] = array("type"=>"form_success","field"=>"user_name","info"=>"");
					$field_name = "电子邮箱";
				}
				if($error['field_name']=="mobile")
				{
					$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");
					$field_name = "手机号码";
				}
				if($error['field_name']=="verify_code")
				{
					$data[] = array("type"=>"form_success","field"=>"verify_code","info"=>"");
					$field_name = "验证码";
				}
			
				if($error['error']==EMPTY_ERROR)
				{
					$error_info = "不能为空";
					$type = "form_tip";
				}
				if($error['error']==FORMAT_ERROR)
				{
					$error_info = "错误";
					$type="form_error";
				}
				if($error['error']==EXIST_ERROR)
				{
					$error_info = "已存在";
					$type="form_error";
				}
				
				//$data[] = array("type"=>$type,"field"=>$error['field_name'],"info"=>$field_name.$error_info);	
				ajax_return(array("status"=>0,"data"=>$field_name.$error_info,"info"=>""));			
				
			}
	}
	public function register_two(){
		$GLOBALS['tmpl']->assign("page_title","实名认证");
 		if($GLOBALS['user_info']['is_investor']==0 ||($GLOBALS['user_info']['is_investor']>0&&$GLOBALS['user_info']['investor_status']==2)){
 		}else{
 			showSuccess("您已经申请了实名认证",0,url_wap("settings#security"));
		}
		$GLOBALS['tmpl']->display("user_register_two.html");
	}
	public function do_register_two(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		$return=array("status"=>1,'info'=>'','jump'=>'');
		$is_investor=intval($_REQUEST['is_investor']);
		$identify_name=strim($_REQUEST['identify_name']);
		$identify_number=strim($_REQUEST['identify_number']);
		$card=strim($_REQUEST['card']);
		$identity_conditions=intval($_REQUEST['identity_conditions']);
		$credit_report=strim($_REQUEST['credit_report']);
		$housing_certificate=strim($_REQUEST['housing_certificate']);
		
		if($identify_name==''){
			showErr("身份证姓名不能为空!",$ajax,"");
		}
		if($identify_number==''){
			showErr("身份证号码不能为空!",$ajax,"");
		}
		if(!isCreditNo($identify_number)){
			showErr("请输入正确的身份证号码!",$ajax,"");
		}
  		//判断该实名是否存在
 		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user where (identify_name = '$identify_name' or identify_number = '$identify_number') and id<>".$GLOBALS['user_info']['id']) > 0 ){
			showErr("该实名已被其他用户认证，非本人请联系客服",$ajax,"");
		}
		if($is_investor==2){
			$identify_business_name=strim($_REQUEST['identify_business_name']);
			if($identify_business_name==''){
				showErr("企业不能为空!",$ajax,"");
			}
			$bankLicense=strim($_REQUEST['bankLicense']);
			if($bankLicense==''){
				showErr("开户银行许可证不能为空!",$ajax,"");
			}
			$orgNo=strim($_REQUEST['orgNo']);
			if($orgNo==''){
				showErr("组织机构代码不能为空!",$ajax,"");
			}
			$taxNo=strim($_REQUEST['taxNo']);
			if($taxNo==''){
				showErr("税务登记号不能为空!",$ajax,"");
			}
			$businessLicense=strim($_REQUEST['businessLicense']);
			if($businessLicense==''){
				showErr("营业执照编号不能为空!",$ajax,"");
			}
			$contact=strim($_REQUEST['contact']);
			if($contact==''){
				showErr("企业联系人不能为空!",$ajax,"");
			}
			$memberClassType=strim($_REQUEST['memberClassType']);
		}
		$user_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']);
		if($user_info){
			require_once APP_ROOT_PATH."system/libs/user.php";
			$user_info['is_investor']=$is_investor;
 			$user_info['identify_name']=$identify_name;
			$user_info['identify_number']=$identify_number;
			if($is_investor==1){
 				$user_info['card']=$card;
				$user_info['identity_conditions']=$identity_conditions;
				$user_info['credit_report']=$credit_report;
				$user_info['housing_certificate']=$housing_certificate;
 			}
 			if($is_investor==2){
				$user_info['identify_business_name'] = $identify_business_name;
 				$user_info['bankLicense'] = $bankLicense;
				$user_info['orgNo'] = $orgNo;
				$user_info['businessLicense'] = $businessLicense;
				$user_info['contact'] = $contact;
				$user_info['taxNo'] = $taxNo;
				$user_info['memberClassType'] = $memberClassType;
			}
			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_info,"UPDATE","id=".intval($GLOBALS['user_info']['id']));
		//	$res=save_user($user_info,"UPDATE");
			//showSuccess("保存成功",$ajax,url("settings#security"));	
			showSuccess("验证成功",$ajax,get_domain().APP_ROOT."/wap/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id']);
		}else{
			showErr("会员信息不存在",$ajax);
		}
	}
	public function loginout(){
		$ajax = intval($_REQUEST['ajax']);
		require_once APP_ROOT_PATH."system/libs/user.php";
		$result = loginout_user();
		if($result['status'])
		{
			es_cookie::delete("email");
			es_cookie::delete("user_pwd");
			es_cookie::delete("hide_user_notify");
			es_session::delete("user_info");
			if($ajax==1)
			{
				$return['status'] = 1;
				$return['info'] = "登出成功";
				$return['data'] = $result['msg'];
				$return['jump'] =url_wap("index");		
				ajax_return($return);
			}
			else
			{
				$GLOBALS['tmpl']->assign('integrate_result',$result['msg']);
				if(trim(app_conf("INTEGRATE_CODE"))=='')
				{
					app_redirect(url_wap("index",array('is_loginout'=>1)));
				}
				else
				showSuccess("登出成功",0,url_wap("index",array('is_loginout'=>1)));
			}
		}
		else
		{
			if($ajax==1)
			{
				$return['status'] = 1;
				$return['info'] = "登出成功";
				$return['jump'] = url_wap("index");					
				ajax_return($return);
			}
			else
			app_redirect(url_wap("index"));		
		}
	}
	
	
	
	
	private function register_check_all()
	{
		$user_name = strim($_REQUEST['user_name']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		
		$email = strim($_REQUEST['email']);
		$verify_coder_email=strim($_REQUEST['verify_coder_email']);
		$mobile = strim($_REQUEST['mobile']);
		$verify_coder=strim($_REQUEST['verify_coder']);
		
		$data = array();
		require_once APP_ROOT_PATH."system/libs/user.php";
		
		//用户名
		$user_name_result = check_user("user_name",$user_name);
		if($user_name_result['status']==0)
		{
			if($user_name_result['data']['error']==EMPTY_ERROR)
			{
				$error = "不能为空";
				$type = "form_tip";
			}
			if($user_name_result['data']['error']==FORMAT_ERROR)
			{
				$error = "格式有误";
				$type="form_error";
			}
			if($user_name_result['data']['error']==EXIST_ERROR)
			{
				$error = "已存在";
				$type="form_error";
			}
			return array("status"=>0,"field"=>"user_name","info"=>"会员帐号".$error);
		}
		else
		{
			//$data[] = array("type"=>"form_success","field"=>"user_name","info"=>"");
		}
		
		//密码
		if($user_pwd=="")
		{
			$user_pwd_result['status'] = 0;
			return array("status"=>0,"field"=>"user_pwd","info"=>"请输入会员密码");
		}
		elseif(strlen($user_pwd)<4)
		{
			$user_pwd_result['status'] = 0;
			return array("status"=>0,"field"=>"user_pwd","info"=>"密码不得小于四位");
		}
		else
		{
			$user_pwd_result['status'] = 1;
			//$data[] = array("type"=>"form_success","field"=>"user_pwd","info"=>"");
		}
		
		if($user_pwd==$confirm_user_pwd)
		{
			$confirm_user_pwd_result['status'] = 1;
			//return array("type"=>"form_success","field"=>"confirm_user_pwd","info"=>"");
		}
		else
		{
			$confirm_user_pwd_result['status'] = 0;
			return array("status"=>0,"field"=>"confirm_user_pwd","info"=>"确认密码失败");
		}
		
		//邮箱
		if(app_conf("USER_VERIFY")!=2)
		{
			$email_result = check_user("email",$email);
			if($email_result['status']==0)
			{
				if($email_result['data']['error']==EMPTY_ERROR)
				{
					$error = "不能为空";
					$type = "form_tip";
				}
				if($email_result['data']['error']==FORMAT_ERROR)
				{
					$error = "格式有误";
					$type="form_error";
				}
				if($email_result['data']['error']==EXIST_ERROR)
				{
					$error = "已存在";
					$type="form_error";
				}
				return array("status"=>0,"field"=>"email","info"=>"电子邮箱".$error);
			}
			else
			{
				//$data[] = array("type"=>"form_success","field"=>"email","info"=>"");
			}
			
			//邮箱验证码
			if(app_conf("USER_VERIFY")==1 || app_conf("USER_VERIFY")==4 || app_conf("USER_VERIFY")==5)
			{
				$verify_coder_email_result = check_user("verify_coder_email",$verify_coder_email);
				if($verify_coder_email_result['status']==0)
				{
	 				if($verify_coder_email_result['data']['error']==EMPTY_ERROR)
					{
						$error = "不能为空";
						$type = "form_tip";
					}
					if($verify_coder_email_result['data']['error']==EXIST_ERROR)
					{
						$error = "错误";
						$type="form_error";
					}
					if($verify_coder_email_result['data']['error']==FORMAT_ERROR)
					{
						$error = "格式有误";
						$type="form_error";
					}
					 
					return array("status"=>0,"field"=>"verify_coder_email","info"=>"邮件验证码".$error);
				}
				else
				{
					//$data[] = array("type"=>"form_success","field"=>"verify_coder_email","info"=>"");
				}
			}
		}
		
		//手机
		if(app_conf("USER_VERIFY")==2 || app_conf("USER_VERIFY")==4  || app_conf("USER_VERIFY")==5 || app_conf("USER_VERIFY")==6)
		{
			$mobile_result = check_user("mobile",$mobile);
			if($mobile_result['status']==0)
			{
				if($mobile_result['data']['error']==EMPTY_ERROR)
				{
					$error = "不能为空";
					$type = "form_tip";
				}
				if($mobile_result['data']['error']==FORMAT_ERROR)
				{
					$error = "格式有误";
					$type="form_error";
				}
				if($mobile_result['data']['error']==EXIST_ERROR)
				{
					$error = "已存在";
					$type="form_error";
				}
				return array("status"=>0,"field"=>"mobile","info"=>"手机号码".$error);
			}
			else
			{
				//$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");
			}
			if(app_conf("USER_VERIFY")!=5){
				//手机验证码
				$verify_coder_result = check_user("verify_coder",$verify_coder);
				//var_dump($verify_coder_result);exit;
				if($verify_coder_result['status']==0)
				{
	 				if($verify_coder_result['data']['error']==EMPTY_ERROR)
					{
						$error = "不能为空";
						$type = "form_tip";
					}
					if($verify_coder_result['data']['error']==EXIST_ERROR)
					{
						$error = "错误";
						$type="form_error";
					}
					if($verify_coder_result['data']['error']==FORMAT_ERROR)
					{
						$error = "格式有误";
						$type="form_error";
					}
					 
					return  array("status"=>0,"field"=>"verify_coder","info"=>"手机验证码".$error);
				}
				else
				{
					//$data[] = array("type"=>"form_success","field"=>"verify_coder","info"=>"");
				}
			}
			
		}
		return array("status"=>1);
		//输出结束
//		if(app_conf("USER_VERIFY")==2){
//			if($mobile_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_result['status']==1)
//			{
//				$return = array("status"=>1);
//			}
//			else
//			{
//				$return = array("status"=>0,"data"=>$data,"info"=>"");
//			}
//		}elseif(app_conf("USER_VERIFY")==1)
//		{
//			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_email_result['status']==1)
//			{
//				$return = array("status"=>1);
//			}
//			else
//			{
//				$return = array("status"=>0,"data"=>$data,"info"=>"");
//			}
//		}
//		elseif(app_conf("USER_VERIFY")==4)
//		{
//			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_email_result['status']==1 &&$verify_coder_result['status']==1)
//			{
//				$return = array("status"=>1);
//			}
//			else
//			{
//				$return = array("status"=>0,"data"=>$data,"info"=>"");
//			}
//		}
//		else{
//			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1)
//			{
//				$return = array("status"=>1);
//			}
//			else
//			{
//				$return = array("status"=>0,"data"=>$data,"info"=>"");
//			}
//		}
//		return $return;
		
	}
	
	//检查验证码是否正确
	function check_verify_code()
	{
 		$settings_mobile_code=strim($_REQUEST['code']);
  		$mobile=strim($_REQUEST['mobile']);
		//判断验证码是否正确=============================
 		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$settings_mobile_code."'")==0){
			$data['status'] = 0;
			$data['info'] = "手机验证码出错";
			ajax_return($data);
		}else{
			$data['status'] = 1;
			$data['info'] = "验证码正确";
			ajax_return($data);
		}
	}
	
	public function getpassword()
	{
		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);
		if (!$GLOBALS['tmpl']->is_cached('user_getpassword.html', $cache_id))
		{
			$GLOBALS['tmpl']->assign("page_title","找回密码");
		}
		$GLOBALS['tmpl']->display("user_getpassword.html",$cache_id);
	}
	
	public function wx_register(){
		if($GLOBALS['user_info']){
			app_redirect(url_wap("index#index"));
		}
		 
		$GLOBALS['tmpl']->assign('wx_info',$GLOBALS['wx_info']);
		$GLOBALS['tmpl']->display("user_wx_register.html");
	}
	
	public function wx_do_register()
	{
 		$user_info=array();
		$user_info['mobile'] = strim($_REQUEST['mobile']);
		
		$user_info['verify_coder_email']=strim($_REQUEST['verify_coder_email']);
 		$user_info['verify_coder']=strim($_REQUEST['verify_coder']);
 		$user_info['wx_openid']=strim($_REQUEST['wx_openid']);
		$user_info['user_name']=strim($_REQUEST['user_name']);
		$user_info['user_name']=trim_utf8mb4($user_info['user_name']);
		$user_info['province']=strim($_REQUEST['province']);
		$user_info['email']=strim($_REQUEST['email']);
		$user_info['city']=strim($_REQUEST['city']);
		$user_info['sex']=strim($_REQUEST['sex']);
		$user_info['head_image']=strim($_REQUEST['head_image']);
		
		if(!$user_info['user_name']||!$user_info['wx_openid']){
			 ajax_return(array("status"=>0,"info"=>"未获取微信信息，请重新获取权限!"));
		}

		if(app_conf('USER_VERIFY')==2||app_conf('USER_VERIFY')==4){
			if(!$user_info['mobile'])
			{
				$data['status'] = 0;
				$data['info'] = "手机号码为空";
				ajax_return($data);
			}
	 		if($user_info['verify_coder']==""){
				$data['status'] = 0;
				$data['info'] = "手机验证码为空";
				ajax_return($data);
			}
			//判断验证码是否正确=============================
			if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$user_info['mobile']." AND verify_code='".$user_info['verify_coder']."'")==0){
	 			$data['status'] = 0;
				$data['info'] = "手机验证码错误";
				ajax_return($data);
			}
			$user=get_user_has('mobile',$user_info['mobile']);
		}
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		if($user){
			if($user_info['wx_openid']){
				$GLOBALS['db']->query("update ".DB_PREFIX."user set wx_openid='".$user_info['wx_openid']."' where id=".$user['id']);
 			}
			if($user_info['head_image']){
				$GLOBALS['db']->query("update ".DB_PREFIX."user set head_image='".$user_info['head_image']."' where id=".$user['id']);
 			}
 			$user_id = $user['id'];	
 		}else{
	 			if(app_conf("USER_VERIFY")!=2){
	 				if(!$user_info['email'])
					{
						$data['status'] = 0;
						$data['info'] = "邮箱为空";
						ajax_return($data);
					}
					if(!check_email($user_info['email'])){
						$data['status'] = 0;
						$data['info'] = "邮箱格式错误";
						ajax_return($data);
					}
					if(app_conf('USER_VERIFY')==1||app_conf('USER_VERIFY')==4){
						if($user_info['verify_coder_email']==""){
							$data['status'] = 0;
							$data['info'] = "邮件验证码为空";
							ajax_return($data);
						}
					}
					$user=get_user_has('email',$user_info['email']);
	  			}
		 		
		 		if($user){
					$GLOBALS['db']->query("update ".DB_PREFIX."user set wx_openid='".$user_info['wx_openid']."' where id=".$user['id']);
					$user_id = $user['id'];	
				}else{
					
					$has_user_name=get_user_has('user_name',$user_info['user_name']);
					if($has_user_name){
						$user_info['user_name']=$user_info['user_name'].rand(10000,99999);
					}
					
		 			
		 			if($user_info['sex']==0){
		 				$user_info['sex']=-1;
		 			}elseif($user_info['sex']==1){
		 				$user_info['sex']=1;
		 			}else{
		 				$user_info['sex']=0;
		 			}
		 			//开启邮箱验证
		            if(app_conf("USER_VERIFY")!=3){
		                 $user_info['is_effect'] = 1;
		            }else{
		            	$user_info['is_effect'] = 0;
		            }
		 			
		 			$user_info['create_time'] = get_gmtime();
					$user_info['update_time'] = get_gmtime();
					//新建用户 使用验证码作为密码
					$user_info['user_pwd']=$user_info['verify_coder'];
					//$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_info,"INSERT");
		 			
		 			$res = save_user($user_info);
		 			 
		 			if($res['status']==0){
						$data['status'] = 0;
						$data['info'] = $res['data']['field_name'].'错误';
						ajax_return($data);
					}
	 				$user_id = intval($res['data']);	
					if($has_user_name){
 						$GLOBALS['db']->query("update ".DB_PREFIX."user set user_name='".strim($_REQUEST['user_name'])."_".$user_id."' where id=".$user_id);
 					}
	 			}
				
	 		}
   			$user_info_new = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
 			if(!$user_info_new){
 				ajax_return(array("status"=>0,"info"=>"注册会员失败，请重新获取授权!"));
 			}
 			if($user_info_new['is_effect']==1)
			{
				if($user_info_new['mobile']){
					$name=$user_info_new['mobile'];
				}elseif($user_info_new['email']){
					$name=$user_info_new['email'];
				}else{
					$name=$user_info_new['user_name'];
				}
 				$result = do_login_user($name,$user_info_new['user_pwd']);
 				if($GLOBALS['is_tg']){
 					ajax_return(array("status"=>1,"data"=>$result['msg'],"jump"=>url_wap("user#register_two")));
				}else{
  					ajax_return(array("status"=>1,"info"=>$result['msg'],"jump"=>url_wap("index")));
 				}
			}
			else
			{
                ajax_return(array("status"=>0,"info"=>"请等待管理员审核"));
					
			}                     
 	}
	
	//手机验证修改密码=====================================================================================
	public function phone_update_password()
	{
		$mobile = strim($_REQUEST['mobile']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd=strim($_POST['confirm_user_pwd']);
		$settings_mobile_code1=strim($_POST['code']);
	
		if(!$mobile)
		{
			$data['status'] = 0;
			$data['info'] = "手机号码为空";
			ajax_return($data);
		}
	
		if($settings_mobile_code1==""){
			$data['status'] = 0;
			$data['info'] = "手机验证码为空";
			ajax_return($data);
		}
	
		if($user_pwd==""){
			$data['status'] = 0;
			$data['info'] = "密码为空";
			ajax_return($data);
		}
	
		if($user_pwd!==$confirm_user_pwd){
			$data['status'] = 0;
			$data['info'] = "两次密码不一致";
			ajax_return($data);
		}
	
		//判断验证码是否正确=============================
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$settings_mobile_code1."'")==0){
	
			$data['status'] = 0;
			$data['info'] = "手机验证码错误";
			ajax_return($data);
		}
	
	
		if($user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile =".$mobile))
		{
				
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET user_pwd='".md5($user_pwd.$user_info['code'])."' where mobile=".$mobile);
			$result = 1;  //初始为1
			$data['status'] = 1;
			$data['info'] = "密码修改成功";
			ajax_return($data);//密码修改成功
		}
		else{
			$data['status'] = 0;
			$data['info'] = "没有该手机账户";
			ajax_return($data);//密码修改成功
		}
	}
	
	 
	
	public function verify()
	{
		$id = intval($_REQUEST['id']);
		$user_info  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
		if(!$user_info)
		{
			showErr("没有该会员");
		}
		$verify = addslashes(trim($_REQUEST['code']));
		if($user_info['verify']!=''&&$user_info['verify'] == $verify)
		{
			//成功
//			send_register_success(0,$user_info);
			es_session::set("user_info",$user_info);
			$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= ".get_gmtime().",verify = '',is_effect = 1 where id =".$user_info['id']);
			$GLOBALS['db']->query("update ".DB_PREFIX."mail_list set is_effect = 1 where mail_address ='".$user_info['email']."'");
			showSuccess("验证成功",0,get_gopreview_wap());
		}
	
		elseif($user_info['verify']=='')
		{
			showErr("已验证过",0,get_gopreview_wap());
	
		}
		else
		{
			showErr("验证失败",0,get_gopreview_wap());
		}
	}
	
	public function investor_result($from='wap'){
		if(!$GLOBALS['user_info']){
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif ($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		if($GLOBALS['user_info']['investor_status']==1){
			$GLOBALS['tmpl']->assign("investor_status",$GLOBALS['user_info']['investor_status']);
			$GLOBALS['tmpl']->assign("is_investor",$GLOBALS['user_info']['is_investor']);
		}
		$GLOBALS['tmpl']->display("investor_success.html");
	}

	//投资认证申请信息入库(个人)
	public function investor_save_data($from='wap'){
		if(!$GLOBALS['user_info']){
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		if(!check_ipop_limit(get_client_ip(),"user_investor_result",5))
			showErr("提交太快",1);
		$id=intval($_REQUEST ['id']);
		$ajax = intval($_POST['ajax']);
		$identify_name=strim($_POST['identify_name']);
		$identify_number=strim($_POST['identify_number']);
		$image1['url']=replace_public(strim($_POST['idcard_zheng_u']));
		$image2['url']=replace_public(strim($_POST['idcard_fang_u']));
		$data=investor_save($id,$ajax='',$identify_name,$identify_number,$image1['url'],$image2['url']);
		ajax_return($data);
		return false;
	}
	
	//投资认证申请信息入库(机构)
	public function investor_agency_save_data($from='wap'){
		if(!$GLOBALS['user_info']){
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif ($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		if(!check_ipop_limit(get_client_ip(),"user_investor_result",5))
			showErr("提交太快",1);
		$id=intval($_REQUEST ['id']);
		$ajax = intval($_POST['ajax']);
 		$identify_name=strim($_POST['identify_name']);
		$identify_number=strim($_POST['identify_number']);
 		$identify_business_name=strim($_POST['identify_business_name']);
 		$identify_business_licence=replace_public(strim($_POST['identify_business_licence_u']));
		$identify_business_code=replace_public(strim($_POST['identify_business_code_u']));
		$identify_business_tax=replace_public(strim($_POST['identify_business_tax_u']));
		$data=investor_agency_save($id,$ajax='',$identify_business_name,$identify_business_licence,$identify_business_code,$identify_business_tax,$identify_name,$identify_number);
		ajax_return($data);
		return false;
	}
	
	//(投资者认证)更新用户手机号码
	public function investor_save_mobile(){
		$id=$GLOBALS['user_info']['id'];
		$mobile=strim($_POST['mobile']);
		if((es_cookie::get(md5("mobile_is_bind".$id)))!=1)
		{
			$verify_coder=strim($_POST['verify_coder']);
			if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$verify_coder."'")==0){
				$data['status'] = 0;
				$data['info'] = "手机验证码出错!";
				ajax_return($data);
				return false;
			}
		}
		$is_investor=strim($_POST['is_investor']);
		if($mobile==null){
			$data['status'] = 0;
			$data['info'] = "手机号码不能为空！";
			ajax_return($data);
			return false;
		}
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user WHERE id!=".$id." AND mobile=".$mobile)>0){
			$data['status'] = 0;
			$data['info'] = "手机号码已经被使用！";
			ajax_return($data);
			return false;
		}
		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET mobile=".$mobile." WHERE id = ".$id)&&$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET is_investor=".$is_investor." WHERE id = ".$id)){
			//绑定过回退不用再次发送短信
			es_cookie::set(md5("mobile_is_bind".$id),1);
			$data['status'] = 1;
			ajax_return($data);
		}
	
		return false;
	}
	
	//（普通众筹）支持前用户是否绑定了手机号码
	public function user_bind_mobile(){
		$cid=strim($_REQUEST['cid']);
		$GLOBALS['tmpl']->assign("cid",$cid);
		$GLOBALS['tmpl']->display("inc/user_bind_mobile.html");
	}
	
	//更新用户手机号码
	public function save_mobile(){
		$mobile=strim($_POST['mobile']);
		$cid=strim($_POST['cid']);
		$verify_coder=strim($_POST['verify_coder']);
		if($mobile==null){
			$data['status'] = 0;
			$data['info'] = "手机号码不能为空！";
			ajax_return($data);
			return false;
		}
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$verify_coder."'")==0){
			$data['status'] = 0;
			$data['info'] = "手机验证码出错";
			ajax_return($data);
			return false;
		}
		$id=$GLOBALS['user_info']['id'];
		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET mobile=".$mobile." WHERE id = ".$id)){
			//绑定过回退不用再次发送短信
			es_cookie::set(md5("mobile_is_bind".$GLOBALS['user_info']['id']),1);
			$data['status'] = 1;
			ajax_return($data);
		}
		return false;
	}
}
?>