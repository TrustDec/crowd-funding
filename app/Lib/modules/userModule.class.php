<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class userModule extends BaseModule
{
	public function login()
	{
        if($GLOBALS['user_info']){
			app_redirect(url("settings#index"));
		}
       
		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('user_login.html', $cache_id))
		{		
			$GLOBALS['tmpl']->assign("page_title","会员登录");
		}
		 
		$GLOBALS['tmpl']->display("user_login.html",$cache_id);
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
		
		if(app_conf("USER_VERIFY_STATUS")==1){
				$verify=es_session::get("login_verify");
				$image_code = strim($_REQUEST['image_code']);
 				if($image_code){
					if(md5($image_code)!=$verify){
 						$err = "验证码错误!";
						showErr($err,$ajax);
					}
				}else{
 					$err = "验证码不能为空!";
					showErr($err,$ajax);
				}
 			}
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		if(check_ipop_limit(get_client_ip(),"user_dologin",5))
		$result = do_login_user($_POST['email'],$_POST['user_pwd']);
		else
		showErr("提交太快",$ajax,url("user#login"));		
		if($result['status'])
		{	
			$s_user_info = es_session::get("user_info");
			if(intval($_POST['auto_login'])==1)
			{
				//自动登录，保存cookie
				$user_data = $s_user_info;
				
				if($user_data['email']){
					$user_name_or_email = $user_data['email'];
				}elseif($user_data['mobile']){
					$user_name_or_email = $user_data['mobile'];
				}elseif($user_data['user_name']){
					$user_name_or_email = $user_data['user_name'];
				}
				
				es_cookie::set("email",$user_name_or_email,3600*24*30);			
				es_cookie::set("user_pwd",md5($user_data['user_pwd']."_EASE_COOKIE"),3600*24*30);
				
			}
			if($ajax==0&&trim(app_conf("INTEGRATE_CODE"))=='')
			{
				$redirect = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:url("index");
				app_redirect($redirect);
			}
			else
			{			
				$jump_url = get_gopreview();				
				if($ajax==1)
				{
					if($GLOBALS['is_tg']&&!$GLOBALS['user_info']['ips_acct_no']){
						$return['status'] = 2;
						$jump_url=APP_ROOT."/index.php?ctl=?ctl=indexs&act=indexs&user_type=0&user_id=".$GLOBALS['user_info']['id'];
						
					}else{
						$return['status'] = 1;
					}
					
					$return['info'] = "登录成功";
					$return['data'] = $result['msg'];
					$return['jump'] = $jump_url;	
					ajax_return($return);
				}
				else
				{
 					
					$GLOBALS['tmpl']->assign('integrate_result',$result['msg']);					
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
			//send_register_success(0,$user_info);
			es_session::set("user_info",$user_info);
			$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= ".get_gmtime().",verify = '',is_effect = 1 where id =".$user_info['id']);
			$GLOBALS['db']->query("update ".DB_PREFIX."mail_list set is_effect = 1 where mail_address ='".$user_info['email']."'");									
			showSuccess("验证成功",0,get_gopreview());
		}
                
		elseif($user_info['verify']=='')
		{
			showErr("已验证过",0,get_gopreview());
                        
		}
		else
		{
			showErr("验证失败",0,get_gopreview());
		}
	}
	
	public function loginout()
	{		
		$ajax = intval($_REQUEST['ajax']);
		require_once APP_ROOT_PATH."system/libs/user.php";
		
		$result = loginout_user();
 		if($result['status'])
		{
			es_cookie::delete("email");
			es_cookie::delete("user_pwd");
			es_cookie::delete("hide_user_notify");
			es_cookie::delete("mobile_status");
			
 			if($ajax==1)
			{
				$return['status'] = 1;
				$return['info'] = "登出成功";
				$return['data'] = $result['msg'];
				$return['jump'] = get_gopreview();					
				ajax_return($return);
			}
			else
			{
				$GLOBALS['tmpl']->assign('integrate_result',$result['msg']);
				if(trim(app_conf("INTEGRATE_CODE"))=='')
				{
					app_redirect_preview();
				}
				else
				showSuccess("登出成功",0,get_gopreview());
			}
		}
		else
		{
			if($ajax==1)
			{
				$return['status'] = 1;
				$return['info'] = "登出成功";
				$return['jump'] = get_gopreview();					
				ajax_return($return);
			}
			else
			app_redirect(get_gopreview());		
		}
	}
	
	public function getpassword()
	{
		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('user_getpassword.html', $cache_id))	
		{			 
			$GLOBALS['tmpl']->assign("page_title","邮件取回密码");
		}
		$GLOBALS['tmpl']->display("user_getpassword.html",$cache_id);
	}
	
	public function do_getpassword()
	{
		
		$email = strim($_REQUEST['email']);
		$ajax = intval($_REQUEST['ajax']);
		if(!check_ipop_limit(get_client_ip(),"user_do_getpassword",5))
		showErr("提交太快",$ajax);	
		if(!check_email($email))
		{
			showErr("邮箱格式有误",$ajax);
		}
		elseif($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email ='".$email."'") == 0)
		{
			showErr("邮箱不存在",$ajax);
		}
		else 
		{
			$user_info = $GLOBALS['db']->getRow('select * from '.DB_PREFIX."user where email='".$email."'");
			send_user_password_mail($user_info['id']);
			showSuccess("邮件已经寄出，请查看您的邮箱!",$ajax);
		}
	}
	
	
	public function register()
	{
		if($GLOBALS['user_info']){
			app_redirect(url("settings#index"));
		}
                
       
		$GLOBALS['tmpl']->caching = false;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('user_register.html', $cache_id))
		{		
			$GLOBALS['tmpl']->assign("page_title","会员注册");
		}
		$GLOBALS['tmpl']->display("user_register.html",$cache_id);
	}
	
	public function register_check()
	{
		$field = strim($_REQUEST['field']);
		$value = strim($_REQUEST['value']);
		require_once APP_ROOT_PATH."system/libs/user.php";
		$result = check_user($field,$value);
		if($result['status']==0)
		{
			if($result['data']['field_name']=='user_name')
			{
				$field_name = "会员帐号";
			}
		
			if($result['data']['field_name']=='email')
			{
				$field_name = "电子邮箱";
			}
			if($result['data']['error']==EMPTY_ERROR)
			{
				$error = "不能为空";
			}
			if($result['data']['error']==FORMAT_ERROR)
			{
				$error = "格式有误";
			}
			if($result['data']['error']==EXIST_ERROR)
			{
				$error = "已存在";
			}
			$return = array('status'=>0,"info"=>$field_name.$error);
			ajax_return($return);
		}
		else
		{
			$return = array('status'=>1);
			ajax_return($return);
		}
		
		
	}
	public function register_check1()
	{
		$field = strim($_REQUEST['field']);
		$value = strim($_REQUEST['value']);
		require_once APP_ROOT_PATH."system/libs/user.php";
		$result = check_user($field,$value);
		if($result['status']==0)
		{
			if($result['data']['field_name']=='user_name')
			{
				$field_name = "会员帐号";
			}
			if($result['data']['field_name']=='mobile')
			{
				$field_name = "会员手机";
			}
			
			if($result['data']['error']==EMPTY_ERROR)
			{
				$error = "不能为空";
			}
			if($result['data']['error']==FORMAT_ERROR)
			{
				$error = "格式有误";
			}
			if($result['data']['error']==EXIST_ERROR)
			{
				$error = "已存在";
			}
			$return = array('status'=>0,"info"=>$field_name.$error);
			ajax_return($return);
		}
		else
		{
			$return = array('status'=>1);
			ajax_return($return);
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
			$data[] = array("type"=>$type,"field"=>"user_name","info"=>"会员帐号".$error);
		}
		else
		{
			$data[] = array("type"=>"form_success","field"=>"user_name","info"=>"");
		}
		
		//密码
		if($user_pwd=="")
		{
			$user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_tip","field"=>"user_pwd","info"=>"请输入会员密码");
		}
		elseif(strlen($user_pwd)<4)
		{
			$user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_error","field"=>"user_pwd","info"=>"密码不得小于四位");
		}
		else
		{
			$user_pwd_result['status'] = 1;
			$data[] = array("type"=>"form_success","field"=>"user_pwd","info"=>"");
		}
		
		if($user_pwd==$confirm_user_pwd)
		{
			$confirm_user_pwd_result['status'] = 1;
			$data[] = array("type"=>"form_success","field"=>"confirm_user_pwd","info"=>"");
		}
		else
		{
			$confirm_user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_error","field"=>"confirm_user_pwd","info"=>"确认密码失败");
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
				$data[] = array("type"=>$type,"field"=>"email","info"=>"电子邮箱".$error);
			}
			else
			{
				$data[] = array("type"=>"form_success","field"=>"email","info"=>"");
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
					 
					$data[] = array("type"=>$type,"field"=>"verify_coder_email","info"=>"邮件验证码".$error);
				}
				else
				{
					$data[] = array("type"=>"form_success","field"=>"verify_coder_email","info"=>"");
				}
			}
		}
		
		//手机
		if(app_conf("USER_VERIFY")==2 || app_conf("USER_VERIFY")==4 || app_conf("USER_VERIFY")==5 || app_conf("USER_VERIFY")==6)
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
				$data[] = array("type"=>$type,"field"=>"mobile","info"=>"手机号码".$error);
			}
			else
			{
				$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");
			}
			
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
				 
				$data[] = array("type"=>$type,"field"=>"verify_coder","info"=>"手机验证码".$error);
			}
			else
			{
				$data[] = array("type"=>"form_success","field"=>"verify_coder","info"=>"");
			}
			
		}

		//输出结束
		if(app_conf("USER_VERIFY")==2){
			if($mobile_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}elseif(app_conf("USER_VERIFY")==1)
		{
			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_email_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}
		elseif(app_conf("USER_VERIFY")==4)
		{
			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_email_result['status']==1 &&$verify_coder_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}elseif(app_conf("USER_VERIFY")==5){
			if($mobile_result['status']==1&&$email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_email_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}elseif(app_conf("USER_VERIFY")==6){
			if($email_result['status']==1&&$mobile_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1&&$verify_coder_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}
		else{
			if($email_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1)
			{
				$return = array("status"=>1);
			}
			else
			{
				$return = array("status"=>0,"data"=>$data,"info"=>"");
			}
		}
		return $return;
		
	}
	private function mobile_register_check_all()
	{
		
		$user_name = strim($_REQUEST['user_name']);
	
		$mobile = strim($_REQUEST['mobile']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		
		$data = array();
		require_once APP_ROOT_PATH."system/libs/user.php";
		
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
			$data[] = array("type"=>$type,"field"=>"user_name","info"=>"会员帐号".$error);	
		}
		else
		{
			$data[] = array("type"=>"form_success","field"=>"user_name","info"=>"");	
		}
		
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
			$data[] = array("type"=>$type,"field"=>"mobile","info"=>"手机号码".$error);	
		}
		
		else
		{
			$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");	
		}
		
		if($user_pwd=="")
		{
			$user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_tip","field"=>"user_pwd","info"=>"请输入会员密码");	
		}
		elseif(strlen($user_pwd)<4)
		{
			$user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_error","field"=>"user_pwd","info"=>"密码不得小于四位");	
		}
		else
		{
			$user_pwd_result['status'] = 1;
			$data[] = array("type"=>"form_success","field"=>"user_pwd","info"=>"");	
		}
		
		if($user_pwd==$confirm_user_pwd)
		{
			$confirm_user_pwd_result['status'] = 1;
			$data[] = array("type"=>"form_success","field"=>"confirm_user_pwd","info"=>"");	
		}
		else
		{
			$confirm_user_pwd_result['status'] = 0;
			$data[] = array("type"=>"form_error","field"=>"confirm_user_pwd","info"=>"确认密码失败");	
		}
		
		if($mobile_result['status']==1&&$user_name_result['status']==1&&$user_pwd_result['status']==1&&$confirm_user_pwd_result['status']==1)
		{
			$return = array("status"=>1);
		}
		else
		{
			$return = array("status"=>0,"data"=>$data,"info"=>"");
		}
	
		return $return;
		
	}
        
       
		public function index_register()
		{
			//首页注册
			require_once APP_ROOT_PATH."system/libs/user.php";
			$ajax = intval($_REQUEST['ajax']);
			$user_name = strim($_REQUEST['user_name']);
			$user_pwd = strim($_REQUEST['user_pwd']);
			$mobile = strim($_REQUEST['mobile']);
			$verify_coder = strim($_REQUEST['verify_coder']);
			$is_agree = strim($_REQUEST['is_agree']);
			$data = array();
			$data['is_agree']=$is_agree;
			if(strlen($user_name)<=0)
			{
				showErr("用户名不能为空",$ajax,"");
			}
			if(strlen($user_name)<4)
			{
				showErr("用户名不能低于四位",$ajax,"");
			}
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = ".$user_name))
			{
				showErr("用户名已存在",$ajax,"");
			}
			$data['user_name']=$user_name;
			if(strlen($user_pwd)<=0){
				showErr("请输入密码",$ajax,"");
			}
			if(strlen($user_pwd)<4)
			{
				showErr("密码不能低于四位",$ajax,"");
			}
			$data['user_pwd']=$user_pwd;
			if($mobile){
				$condition="mobile = '".$mobile."'  and verify_code='".$verify_coder."' ";
			}
			delete_mobile_verify_code();
			$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
			if($num<=0){
				showErr("验证码错误",$ajax,"");
			}else{
					$data['mobile']=$mobile;
					$data['is_effect']=1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data,"INSERT","","SILENT");
					//自动登录
					$result = do_login_user($user_name ,$user_pwd);
					
 					if($GLOBALS['is_tg']){
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"info"=>"注册成功","jump"=>url("user#register_two")));
					}else{
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"info"=>"注册成功","jump"=>get_gopreview()));
					}
			}
		}
		
        public function do_register()
		{
			$email = strim($_REQUEST['email']);
			if(app_conf("USER_VERIFY_STATUS")==1){
				$verify=es_session::get("register_verify");
				$image_code = strim($_REQUEST['image_code']);
 				if($image_code){
					if(md5($image_code)!=$verify){
						$data[] = array("type"=>"form_error","field"=>"image_code","info"=>"验证码错误!");
						ajax_return(array("status"=>0,"data"=>$data,"info"=>"" ));	
					}
				}else{
					$data[] = array("type"=>"form_error","field"=>"image_code","info"=>"验证码不能为空!");
 					ajax_return(array("status"=>0,"data"=>$data,"info"=>"" ));	
				}
 			}
			require_once APP_ROOT_PATH."system/libs/user.php";
			$return = $this->register_check_all();
//			var_dump($return);
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
					$u_name=$user_data['email']?$user_data['email']:$user_data['mobile'];
					$result = do_login_user($u_name,$user_data['user_pwd']);
					//ajax_return(array("status"=>1,"jump"=>get_gopreview()));
				 
					if($GLOBALS['is_tg']){
					
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"jump"=>url("user#register_two")));
					}else{
 						ajax_return(array("status"=>1,"data"=>$result['msg'],"jump"=>get_gopreview()));
					}
				}
				else
				{
                    if(app_conf("USER_VERIFY")==1){
                        ajax_return(array("status"=>1,"jump"=>url("user#mail_check",array('uid'=>$user_id))));
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
				
				$data[] = array("type"=>$type,"field"=>$error['field_name'],"info"=>$field_name.$error_info);	
				ajax_return(array("status"=>0,"data"=>$data,"info"=>""));			
				
			}
	}
	
	public function register_two(){
 		 if(!$GLOBALS['user_info']){
			app_redirect(url("settings#index"));
		}
 		if($GLOBALS['user_info']['is_investor']==0 ||($GLOBALS['user_info']['is_investor']>0&&$GLOBALS['user_info']['investor_status']==2)){
 		}else{
 			//showSuccess("您已经申请了实名认证",0,url("settings#security"));
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
  		//判断该实名是否存在
 		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user where (identify_name = '$identify_name' or identify_number = '$identify_number') and id<>".$GLOBALS['user_info']['id']) > 0 ){
			showErr("该实名已被其他用户认证，非本人请联系客服",$ajax,"");
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
			showSuccess("验证成功",$ajax,APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id']);
		}else{
			showErr("会员信息不存在",$ajax);
		}
	}
	
	public function api_register()
	{			
		$api_info = es_session::get("api_user_info");		
		if(!$api_info)
		{
			app_redirect_preview();
		}
		
		$GLOBALS['tmpl']->assign("api_info",$api_info);
		$GLOBALS['tmpl']->assign("page_title","帐号绑定");
		$GLOBALS['tmpl']->display("user_api_register.html");
	}
	
	public function do_api_register()
	{
		require_once APP_ROOT_PATH."system/libs/user.php";
		$api_info = es_session::get("api_user_info");		
		if(!$api_info)
		{
			app_redirect_preview();
		}
		$user_name = strim($_REQUEST['user_name']);
		$email = strim($_REQUEST['email']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		if(app_conf("USER_VERIFY")==2)
		{
		$user_data['mobile'] = strim($_REQUEST['mobile']);
		$user_data['verify_coder'] = strim($_REQUEST['verify_coder']);
		}
		$user_data['user_name'] = $user_name;
		$user_data['email'] = $email;
		//$user_data['user_pwd'] = rand(100000,999999);
		$user_data['user_pwd'] = $user_pwd;
		$user_data['province'] = $api_info['province'];
		$user_data['city'] = $api_info['city'];
		$user_data['is_effect'] = 1;
		$user_data['sex'] = $api_info['sex'];
		
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
			if(!check_ipop_limit(get_client_ip(),"user_do_api_register",5))
			showErr("提交太快",1);	
			$user_id = intval($res['data']);
			$GLOBALS['db']->query("update ".DB_PREFIX."user set ".$api_info['field']." = '".$api_info['id']."',".$api_info['token_field']." = '".$api_info['token']."',".$api_info['secret_field']." = '".$api_info['secret']."',".$api_info['url_field']." = '".$api_info['url']."' where id = ".$user_id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".$user_id." and weibo_url = '".$api_info['url']."'");
			
			update_user_weibo($user_id,$api_info['url']); 
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
				do_login_user($user_data['email'],$user_data['user_pwd']);
				ajax_return(array("status"=>1,"jump"=>get_gopreview()));
			}
			else
			{
				ajax_return(array("status"=>0,"info"=>"请等待管理员审核","jump"=>get_gopreview()));
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
			if($error['field_name']=="verify_coder"){
				$data[] = array("type"=>"form_success","field"=>"verify_coder","info"=>"");
				$field_name = "验证码";
			}
			if($error['field_name']=="mobile"){
				$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");
				$field_name = "手机号";
			}
		
			if($error['error']==EMPTY_ERROR)
			{
				$error_info = "不能为空";
				$type = "form_tip";
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_info = "格式有误";
				$type="form_error";
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_info = "已存在";
				$type="form_error";
			}
			ajax_return(array("status"=>0,"info"=>$field_name.$error_info,"field"=>$error['field_name'],"jump"=>get_gopreview()));			
			
		}
		
	}
	
	public function api_login()
	{			
		$api_info = es_session::get("api_user_info");		
		if(!$api_info)
		{
			app_redirect_preview();
		}
		$GLOBALS['tmpl']->assign("api_info",$api_info);
		$GLOBALS['tmpl']->assign("page_title","帐号绑定");
		$GLOBALS['tmpl']->display("user_api_login.html");
	}
	
	
	public function do_api_login()
	{		
		
		
		$api_info = es_session::get("api_user_info");		
		if(!$api_info)
		{
			app_redirect_preview();
		}
		
		if(!$_POST)
		{
			app_redirect(APP_ROOT."/");
		}
		foreach($_POST as $k=>$v)
		{
			$_POST[$k] = strim($v);
		}
		$ajax = intval($_REQUEST['ajax']);
		if(!check_ipop_limit(get_client_ip(),"user_do_api_login",5))
		showErr("提交太快",$ajax);	
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		$result = do_login_user($_POST['email'],$_POST['user_pwd']);				
		if($result['status'])
		{	
			$s_user_info = es_session::get("user_info");
			$GLOBALS['db']->query("update ".DB_PREFIX."user set ".$api_info['field']." = '".$api_info['id']."',".$api_info['token_field']." = '".$api_info['token']."',".$api_info['secret_field']." = '".$api_info['secret']."',".$api_info['url_field']." = '".$api_info['url']."' where id = ".$s_user_info['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".intval($s_user_info['id'])." and weibo_url = '".$api_info['url']."'");
			update_user_weibo(intval($s_user_info['id']),$api_info['url']);
			if($ajax==0&&trim(app_conf("INTEGRATE_CODE"))=='')
			{
				$redirect = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:url("index");
				app_redirect($redirect);
			}
			else
			{			
				$jump_url = get_gopreview();				
				if($ajax==1)
				{
					$return['status'] = 1;
					$return['info'] = "登录成功";
					$return['data'] = $result['msg'];
					$return['jump'] = $jump_url;					
					ajax_return($return);
				}
				else
				{
					$GLOBALS['tmpl']->assign('integrate_result',$result['msg']);					
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
			showErr($err,$ajax);
		}
	}
	public function add_weibo()
	{
		$GLOBALS['tmpl']->display("inc/weibo_row.html");
	}
	//手机注册
	public function user_register()
	{
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		$return = $this->mobile_register_check_all();
		
		if($return['status']==0)
		{
			ajax_return($return);
		}		
		$user_data = $_POST;
		foreach($_POST as $k=>$v)
		{
			$user_data[$k] = strim($v);
		}	
        		
		$user_data['is_effect'] = 1;
		if(app_conf("USER_VERIFY")==2){
			
			if($user_data["mobile"] == ""){
            	$data[] = array("type"=>"form_error","field"=>"mobile","info"=>"请输入手机号码");	
            	ajax_return(array("status"=>0,"data"=>$data));			
            }
			
            if($user_data["verify_coder"] == ""){
            	$data[] = array("type"=>"form_error","field"=>"verify_coder","info"=>"请输入验证码");	
				
            	ajax_return(array("status"=>0,"data"=>$data));			
            }
            delete_mobile_verify_code();
            if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile ='".$user_data['mobile']."' and verify_code='".$user_data["verify_coder"]."' order by create_time desc") == 0)
            {
            	$data[] = array("type"=>"form_error","field"=>"verify_coder","info"=>"验证码错误");	
            	ajax_return(array("status"=>0,"data"=>$data));
            }
            
            if(app_conf("SMS_ON")==1)
	        	$user_data['is_effect'] = 1;
	        else
	        	$user_data['is_effect'] = 0;
        }
        
        
        
		$res = save_mobile_user($user_data);
		
	
		if($res['status'] == 1)
		{
			if(!check_ipop_limit(get_client_ip(),"user_do_register",5))
			showErr("提交太快",1);	
			
			$user_id = intval($res['data']);
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
			if($user_info['is_effect']==1)
			{
				//send_register_success(0,$user_data);
				do_login_user($user_data['user_name'],$user_data['user_pwd']);
				ajax_return(array("status"=>1,"jump"=>get_gopreview()));
			}
			else
			{
				 ajax_return(array("status"=>0,"info"=>"请等待管理员审核"));
			}                     
		}
		else
		{
			$error = $res['data'];	
			if($error['field_name']=="user_name")
			{
				$data[] = array("type"=>"form_success","field"=>"user_name","info"=>"");	
				$field_name = "会员帐号";
			}
			if($error['field_name']=="mobile")
			{
				$data[] = array("type"=>"form_success","field"=>"mobile","info"=>"");
				$field_name = "手机号码";
			}
		
			if($error['error']==EMPTY_ERROR)
			{
				$error_info = "不能为空";
				$type = "form_tip";
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_info = "格式有误";
				$type="form_error";
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_info = "已存在";
				$type="form_error";
			}
			
			$data[] = array("type"=>$type,"field"=>$error['field_name'],"info"=>$field_name.$error_info);	
			ajax_return(array("status"=>0,"data"=>$data,"info"=>""));			
			
		}
	}
	//手机验证修改密码=====================================================================================
	public function phone_update_password()
	{
		$mobile = strim($_REQUEST['mobile']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd=strim($_POST['confirm_user_pwd']);
		$settings_mobile_code1=strim($_POST['code']);
		$ajax=intval($_REQUEST['ajax']);
		if(!$mobile)
		{
 			showErr( "手机号码为空",$ajax);
		}
	
		if($settings_mobile_code1==""){
 			showErr( "手机验证码为空",$ajax);
		}
	
		if($user_pwd==""){
 			showErr( "密码为空",$ajax);
		}
	
		if($user_pwd!==$confirm_user_pwd){
 			showErr( "两次密码不一致",$ajax);
		}
		delete_mobile_verify_code();
		//判断验证码是否正确=============================
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$settings_mobile_code1."'")==0){
			 
 			showErr( "手机验证码错误",$ajax);
		}
		
	
		if($user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile =".$mobile))
		{
			$user_info['user_pwd']=$user_pwd;
 			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET user_pwd='".md5($user_info['user_pwd'])."' WHERE mobile =".$mobile);
  			showSuccess("密码修改成功",$ajax,url("user#login"));
			
		}
		else{
 			showErr( "没有该手机账户",$ajax);
		}
	}
	//手机验证修改密码=====================================================================================
	public function email_update_password()
	{
		$ajax=intval($_REQUEST['ajax']);
		$email = strim($_REQUEST['email']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd=strim($_POST['confirm_user_pwd']);
		$settings_mobile_code1=strim($_POST['verify_coder']);
 		if(!$email)
		{
 			showErr( "邮件为空",$ajax);
		}
 		if($user_pwd==""){
 			showErr( "密码为空",$ajax);
		}
	
		if($user_pwd!==$confirm_user_pwd){
 			showErr( "两次密码不一致",$ajax);
		}
		
		if($settings_mobile_code1==""){
 			showErr( "邮件验证码为空",$ajax);
		}
		delete_mobile_verify_code();
		//判断验证码是否正确=============================
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE email='".$email."' AND verify_code='".$settings_mobile_code1."'")==0){
 			showErr( "邮件验证码错误",$ajax);
		}
		
	
		if($user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where email ='$email'"))
		{
			$user_info['user_pwd']=$user_pwd;
 			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET user_pwd='".md5($user_info['user_pwd'])."' WHERE email = '$email'");			
 			showSuccess("密码修改成功",$ajax,url("user#login"));
		}
		else{
 			showErr( "没有该邮箱账户",$ajax);
		}
	}
	//检查验证码是否正确
	function check_verify_code()
	{
		$settings_mobile_code=strim($_POST['code']);
		$mobile=strim($_POST['mobile']);
		delete_mobile_verify_code();
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
		delete_mobile_verify_code();
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$mobile." AND verify_code='".$verify_coder."'")==0){
			$data['status'] = 0;
			$data['info'] = "手机验证码出错";
			ajax_return($data);
			return false;
		}
		$id=$GLOBALS['user_info']['id'];
		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET mobile=".$mobile." WHERE id = ".$id)){
			//绑定过回退不用再次发送短信
			es_session::set(md5("mobile_is_bind".$GLOBALS['user_info']['id']),1);
			$data['status'] = 1;
			ajax_return($data);
		}
		return false;
	}
	
	//(投资者认证)更新用户手机号码
	public function investor_save_mobile(){
		$id=$GLOBALS['user_info']['id'];
		$mobile=strim($_POST['mobile']);
		if((es_session::get(md5("mobile_is_bind".$id)))!=1)
		{
			$verify_coder=strim($_POST['verify_coder']);
			delete_mobile_verify_code();
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
			es_session::set(md5("mobile_is_bind".$id),1);
			$data['status'] = 1;
			ajax_return($data);
		}

		return false;
	}
	//投资认证申请信息入库(个人)
	public function investor_save_data($from='web'){
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
	
	public function investor_result($from='web'){
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
	
	//投资认证申请信息入库(机构)
	public function investor_agency_save_data($from='web'){
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
		$identify_business_name=strim($_POST['identify_business_name']);
		$identify_business_licence=es_session::get("identify_business_licence");
		$identify_business_code=es_session::get("identify_business_code");
		$identify_business_tax=es_session::get("identify_business_tax");
 		$data=investor_agency_save($id,$ajax='',$identify_business_name,$identify_business_licence,$identify_business_code,$identify_business_tax);
		ajax_return($data);
		return false;
	}

}
?>