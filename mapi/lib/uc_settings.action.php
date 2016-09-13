<?php
class uc_settings{
	public function index()
	{		
		/**
		 * is_nickname：昵称   1表设置，0没有设置
			is_user_pwd：密码   1表设置，0没有设置
			is_email：邮箱   1表设置，0没有设置
			is_mobile：手机 1表设置，0没有设置
			paypassword 支付密码   1表设置，0没有设置
			is_identity：身份认证  0表没有设置，1表设置，2表认证未通过 3，认证审核中
		 */
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$root['user_anme'] = $user['user_name'];
		$root['nickname'] = $user['user_name'];
		$root['email'] = $user['email'];
		$root['mobile'] = $user['mobile'];
		$root['is_investor'] = $user['is_investor'];//0表没有认证，1表个人，2机构
		
		if($user['user_name'] !='' )
			$root['is_nickname'] = 1;//昵称
		else
			$root['is_nickname'] = 0;
			
		if($user['user_pwd'] != '')	
			$root['is_user_pwd']=1;
		else
			$root['is_user_pwd']=0;
		
		if($user['email'] != '')	
			$root['is_email']=1;
		else
			$root['is_email']=0;
	
		if($user['mobile'] != '')	
			$root['is_mobile']=1;
		else
			$root['is_mobile']=0;
		
		if($user['paypassword'] != '')	
			$root['is_paypassword']=1;
		else
			$root['is_paypassword']=0;
		
		if($user['is_investor '] >0 || $user['identify_name'] !='')
		{
			if($user['investor_status']==1)
				$root['is_identity']=1;//认证通过
			elseif($user['investor_status']==2)
				$root['is_identity']=2;//认证未通过
			else
				$root['is_identity']=3;//认证审核中
		}
		else
			$root['is_identity']=0;//未认证
			
		output($root);
	}
	
	//邮箱验证码
	public function email_code()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); //用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$code_email = strim ( $GLOBALS ['request'] ['code_email'] ); //发送验证的邮箱
		                  
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		if($user['email'])
		{
			$type=2;
			$code_email=$user['email'];
		}
		else
			$type=1;
			
		$data = send_email_verify_code($code_email,$type);
		if($data['status']==1)
			$root['status']=1;//status 1表发送成功，0表失败
		else
			$root['status']=0;
			
		$root['info']=$data['info'];
		
		output ($root);
	}
	
	//邮箱绑定
	public function email_binding(){
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); //用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$verify_coder=strim ($GLOBALS ['request'] ['verify_coder']); //邮箱验证码
		$new_email = strim ($GLOBALS ['request'] ['new_email']); //发送验证的邮箱
		                  
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		if($user['email'] != '' && $user['email']==$new_email)
		{
			$root['status']=0;
			$root['info']="新邮箱和旧邮箱一样，请重新输入";
			output($root);
		}
		if(strlen($new_email)<=0 ){
			$root['status']=0;
			$root['info']="请输入邮箱";
			output($root);
		}
		
		if(!check_email($new_email))
		{
			$root['status']=0;
			$root['info']="请填写正确的邮箱";
			output($root);	
		}
		 
		$num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where  email='".$email."' ");
		if($num>0){
			$root['status']=0;
			$root['info']="邮箱已存在,请重新输入";
			output($root);
		}
		
		if( $user['email'] != ''){
			$condition="email = '".$user['email']."'  and verify_code='".$verify_coder."' ";
		}else{
			$condition="email = '".$new_email."'  and verify_code='".$verify_coder."' ";
		}
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			$root['status']=0;
			$root['info']="验证码错误";
			
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set email='".$new_email."' where id=".intval($user['id']));
				$root['status']=1;
				$root['info']="设置成功";	
		}

		output($root);
	}
	
	public function mobile_code()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); //用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$code_mobile = strim ( $GLOBALS ['request'] ['code_mobile'] ); //发送验证的邮箱
		                  
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		if($user['mobile'] !='')
		{
			$data = send_code_function ( $user['mobile'],0);
		}
		else
		{
			$data = send_code_function ( $code_mobile,1);
		}
		
		if($data['status']==1)
			$root['status']=1;//status 1表发送成功，0表失败
		else
			$root['status']=0;
			
		$root['info']=$data['info'];
		
		output ($root);
	}
	
	public function mobile_binding(){
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); //用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$verify_coder=strim ($GLOBALS ['request'] ['verify_coder']); //验证码
		$new_mobile = strim ($GLOBALS ['request'] ['new_mobile']); //发送验证的的手机号
		                  
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		if(strlen($verify_coder)< 0 || strlen($verify_coder)== 0){
			$root['status']=0;
			$root['info']="请输入手机验证号码";
			output($root);
		}
		if($user['mobile'] !='' && $new_mobile==$user['mobile']){
 			$root['status']=0;
			$root['info']="新号码和旧号码一样，请重新输入";
			output($root);
		}
		
		if(strlen($new_mobile)< 0 || strlen($new_mobile)== 0){
			$root['status']=0;
			$root['info']="请输入手机号码";
			output($root);
		}
		if(!check_mobile($new_mobile))
		{
			$root['status']=0;
			$root['info']="请填写正确的手机号码";
			output($root);	
		}

		$num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where mobile='".$new_mobile."'");
		if($num>0){
			$root['status']=0;
			$root['info']="手机已存在,请重新输入";
			output($root);
		}
		
		if($user['mobile'] !=''){
			$condition="mobile = '".$user['mobile']."'  and verify_code='".$verify_coder."' ";
		}else{
			$condition="mobile = '".$new_mobile."' and verify_code='".$verify_coder."' ";
		}
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			$root['status']=0;
			$root['info']="验证码错误";
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set mobile='".$new_mobile."' where id=".intval($user['id']));
				$root['status']=1;
				$root['info']="设置成功";	
		}
		output($root);
	}
	
	
	public function paypassword_binding(){
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); //用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		
		$verify_coder=strim ($GLOBALS ['request']['verify_coder']); //验证码
		$paypassword=strim ($GLOBALS ['request']['paypassword']);
		$confirm_paypassword=strim($GLOBALS ['request']['confirm_pypassword']);
		                  
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		if($paypassword==''||$confirm_paypassword==''){
			$root['status']=0;
			$root['info']="请输入密码";
			output($root);
		}
		if($paypassword!=$confirm_paypassword){
			$root['status']=0;
			$root['info']="密码不一致";
			output($root);
		}
 		$condition="mobile = '".$user['mobile']."'  and verify_code='".$verify_coder."' ";
		 
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			$root['status']=0;
			$root['info']="验证码错误";
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set paypassword='".md5($paypassword)."' where id=".$GLOBALS['user_info']['id']);
				$root['status']=1;
				$root['info']="设置成功";
		}
		output($root);
	}
}
?>