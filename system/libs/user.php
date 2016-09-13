<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

define("EMPTY_ERROR",1);  //未填写的错误
define("FORMAT_ERROR",2); //格式错误
define("EXIST_ERROR",3); //已存在的错误

define("ACCOUNT_NO_EXIST_ERROR",1); //帐户不存在
define("ACCOUNT_PASSWORD_ERROR",2); //帐户密码错误
define("ACCOUNT_NO_VERIFY_ERROR",3); //帐户未激活


	/**
	 * 生成会员数据
	 * @param $user_data  提交[post或get]的会员数据
	 * @param $mode  处理的方式，注册或保存
	 * 返回：data中返回出错的字段信息，包括field_name, 可能存在的field_show_name 以及 error 错误常量
	 * 不会更新保存的字段为：score,money,verify,pid
	 * $update_status后台更新标示字段
	 */
	function save_user($user_data,$mode='INSERT',$update_status)
	{		
		
		//开始数据验证
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['user_name'])=='')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($user_data['user_name'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($user_data['email']!=''){
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))>0)
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
			if(trim($user_data['email'])=='')
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		
			if(!check_email(trim($user_data['email'])))
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}
		
		if(!check_mobile(trim($user_data['mobile'])))
		{
			
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
//		if($update_status!=1){
//			if(app_conf("USER_VERIFY")==2){
//				if(trim($user_data['verify_coder'])=='')
//				{
//					$field_item['field_name'] = 'verify_coder';
//					$field_item['error']	=	EMPTY_ERROR;
//					$res['status'] = 0;
//					$res['data'] = $field_item;
//					return $res;
//				}
//				
//				if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".trim($user_data['mobile'])." AND verify_code='".trim($user_data['verify_coder'])."'")==0)
//				{
//					$field_item['field_name'] = 'verify_coder';
//					$field_item['error']	=	FORMAT_ERROR;
//					$res['status'] = 0;
//					$res['data'] = $field_item;
//					return $res;
//				}
//			}
//		}
		$source_url=es_session::get("source_url");
		if($source_url){
			$user['source_url']=$source_url;
		}
 		//验证结束开始插入数据
		$user['user_name'] = $user_data['user_name'];
		if($user_data['create_time']){
			$user['update_time'] = get_gmtime();
		}else{
			$user['create_time'] = get_gmtime();
		}
		$user['pid'] = $user_data['pid'];
		$user['is_send_referrals'] = $user_data['is_send_referrals'];
		$user['province'] = $user_data['province'];
		$user['city'] = $user_data['city'];
		if(isset($user_data['sex']))
		$user['sex'] = intval($user_data['sex']);
		$user['intro'] = strim($user_data['intro']);
		if(strim($user_data['wx_openid'])){
			$user['wx_openid'] = strim($user_data['wx_openid']);
		}
		if(strim($user_data['head_image'])){
			$user['head_image'] = strim($user_data['head_image']);
		}
 		$user['ex_real_name'] = strim($user_data['ex_real_name']);
		$user['ex_account_info'] = strim($user_data['ex_account_info']);
		$user['ex_contact'] = strim($user_data['ex_contact']);
		
		$user['get_user_msg'] = intval($user_data['get_user_msg']);		
		$user['get_deal_msg'] = intval($user_data['get_deal_msg']);
		
		$user['is_investor']=intval($user_data['is_investor']);
		$user['investor_status']=intval($user_data['investor_status']);
		$user['identify_name']=strim($user_data['identify_name']);
		$user['identify_number']=strim($user_data['identify_number']);
		$user['identify_positive_image']=strim($user_data['identify_positive_image']);
		$user['identify_nagative_image']=strim($user_data['identify_nagative_image']);
		
		$user['card']=strim($user_data['card']);
		$user['identity_conditions']=intval($user_data['identity_conditions']);
		$user['credit_report']=strim($user_data['credit_report']);
		$user['housing_certificate']=strim($user_data['housing_certificate']);
		
		$user['identify_business_name']=strim($user_data['identify_business_name']);
		$user['identify_business_licence']=strim($user_data['identify_business_licence']);
 		$user['identify_business_code']=strim($user_data['identify_business_code']);
		$user['identify_business_tax']=strim($user_data['identify_business_tax']);
		
		$user['is_binding']=strim($user_data['is_binding']);
		//验证结束开始插入数据（这里没写user模块写不进去）
		
		if($user_data['user_level']!=null){
			$user['user_level'] = intval($user_data['user_level']);
		}else{
			//$user['user_level'] =$GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_level where level in (select min(level) from ".DB_PREFIX."user_level)");
			$user_level=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_level order by point asc ");
			$user['user_level'] =$user_level['id'];
			$user['point'] = $user_level['point'];
		}
		$user['user_type'] = intval($user_data['user_type']);
		//开户信息
		$user['ex_real_name'] = strim($user_data['ex_real_name']);
		$user['ex_account_bank'] = strim($user_data['ex_account_bank']);
		$user['ex_account_info'] = strim($user_data['ex_account_info']);
		$user['ex_qq'] = strim($user_data['ex_qq']);
		$user['mobile'] = strim($user_data['mobile']);
		//会员状态
		if(intval($user_data['is_effect'])!=0)
		{
			$user['is_effect'] = $user_data['is_effect'];
		}
		if(strim($user_data['email'])){
			$user['email'] = $user_data['email'];
		}
		if(strim($user_data['mobile'])){
			$user['mobile'] = $user_data['mobile'];
		}
		if($mode == 'INSERT')
		{
			$user['code'] = ''; //默认不使用code, 该值用于其他系统导入时的初次认证
		}
		else
		{
			$user['code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."user where id =".$user_data['id']);
		}
		if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='')
		$user['user_pwd'] = md5($user_data['user_pwd'].$user['code']);
		
		if($user_data['user_name'] !=='' && $user_data['user_pwd'] !='' && $user_data['email'] !='')
		{
			//载入会员整合
			$integrate_code = trim(app_conf("INTEGRATE_CODE"));
			if($integrate_code!='')
			{
				$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
				if(file_exists($integrate_file))
				{
					require_once $integrate_file;
					$integrate_class = $integrate_code."_integrate";
					$integrate_obj = new $integrate_class;
				}	
			}
			//同步整合
			if($integrate_obj)
			{
				if($mode == 'INSERT')
				{
					$res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
					$user['integrate_id'] = intval($res['data']);
				}
				else
				{
					$add_res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
					if(intval($add_res['status']))
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."user set integrate_id = ".intval($add_res['data'])." where id = ".intval($user_data['id']));
					}
					else
					{
						if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='') //有新密码
						{
							$status = $integrate_obj->edit_user($user,$user_data['user_pwd']);
							if($status<=0)
							{
								//修改密码失败
								$res['status'] = 0;						
							}
						}
					}
				}			
				if(intval($res['status'])==0) //整合注册失败
				{
					return $res;
				}
			}
		}
		
		
		if($mode == 'INSERT')
		{
			$s_api_user_info = es_session::get("api_user_info");
			$user[$s_api_user_info['field']] = $s_api_user_info['id'];
			es_session::delete("api_user_info");
			$where = '';
		}
		else
		{			
			unset($user['pid']);
			unset($user['is_send_referrals']);
			$where = "id=".intval($user_data['id']);
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{
			if($mode == 'INSERT')
			{
				$user_id = $GLOBALS['db']->insert_id();	
				$register_money = floatval(0);
				if($register_money>0)
				{
					$user_get['money'] = $register_money;
					modify_account($user_get,intval($user_id),"在".to_date(get_gmtime())."注册成功");
				}
				$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$user_id,array('type'=>'会员注册','content'=>'您于 '.get_client_ip() ."注册成功!"));				
				
			}
			else
			{
				$user_id = $user_data['id'];
				
			}
		}
		$res['data'] = $user_id;
		
		return $res;
	}
	function save_mobile_user($user_data,$mode='INSERT')
	{		
		//开始数据验证
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['user_name'])=='')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($user_data['user_name'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
	/*	if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(trim($user_data['email'])=='')
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(!check_email(trim($user_data['email'])))
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
	*/	
		if(!check_mobile(trim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		
		
		//验证结束开始插入数据
		$user['user_name'] = $user_data['user_name'];
		$user['create_time'] = get_gmtime();
		$user['update_time'] = get_gmtime();
		$user['pid'] = $user_data['pid'];
		$user['province'] = $user_data['province'];
		$user['city'] = $user_data['city'];
		if(isset($user_data['sex']))
		$user['sex'] = intval($user_data['sex']);
		$user['intro'] = strim($user_data['intro']);
		$user['ex_real_name'] = strim($user_data['ex_real_name']);
		$user['ex_account_info'] = strim($user_data['ex_account_info']);
		$user['ex_contact'] = strim($user_data['ex_contact']);
		
		$user['get_user_msg'] = intval($user_data['get_user_msg']);		
		$user['get_deal_msg'] = intval($user_data['get_deal_msg']);
		//验证结束开始插入数据（这里没写user模块写不进去）
		
		if($user_data['user_level']!=null){
			$user['user_level'] = intval($user_data['user_level']);
		}else{
			$user['user_level'] =$GLOBALS['db']->getOne("select min(id) from ".DB_PREFIX."user_level");
		}
		$user['user_type'] = intval($user_data['user_type']);
		//开户信息
		$user['ex_real_name'] = strim($user_data['ex_real_name']);
		$user['ex_account_bank'] = strim($user_data['ex_account_bank']);
		$user['ex_account_info'] = strim($user_data['ex_account_info']);
		$user['ex_qq'] = strim($user_data['ex_qq']);
		$user['mobile'] = strim($user_data['mobile']);
		
		//会员状态
		if(intval($user_data['is_effect'])!=0)
		{
			$user['is_effect'] = $user_data['is_effect'];
		}
		
		$user['email'] = $user_data['email'];
		$user['mobile'] = $user_data['mobile'];
		if($mode == 'INSERT')
		{
			$user['code'] = ''; //默认不使用code, 该值用于其他系统导入时的初次认证
		}
		else
		{
			$user['code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."user where id =".$user_data['id']);
		}
		if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='')
		$user['user_pwd'] = md5($user_data['user_pwd'].$user['code']);
		
		//载入会员整合
		$integrate_code = trim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='')
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}	
		}
		//同步整合
		if($integrate_obj)
		{
			if($mode == 'INSERT')
			{
				$res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
				$user['integrate_id'] = intval($res['data']);
			}
			else
			{
				$add_res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
				if(intval($add_res['status']))
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."user set integrate_id = ".intval($add_res['data'])." where id = ".intval($user_data['id']));
				}
				else
				{
					if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='') //有新密码
					{
						$status = $integrate_obj->edit_user($user,$user_data['user_pwd']);
						if($status<=0)
						{
							//修改密码失败
							$res['status'] = 0;						
						}
					}
				}
			}			
			if(intval($res['status'])==0) //整合注册失败
			{
				return $res;
			}
		}
		
		
		
		if($mode == 'INSERT')
		{
			$s_api_user_info = es_session::get("api_user_info");
			$user[$s_api_user_info['field']] = $s_api_user_info['id'];
			es_session::delete("api_user_info");
			$where = '';
		}
		else
		{			
			unset($user['pid']);
			$where = "id=".intval($user_data['id']);
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{
			if($mode == 'INSERT')
			{
				$user_id = $GLOBALS['db']->insert_id();	
				$register_money = floatval(0);
				if($register_money>0)
				{
					$user_get['money'] = $register_money;
					modify_account($user_get,intval($user_id),"在".to_date(get_gmtime())."注册成功");
				}
			}
			else
			{
				$user_id = $user_data['id'];
			}
		}
		$res['data'] = $user_id;
		
		return $res;
	}
	
	function update_mobile_user($user_data,$mode='INSERT')
	{
		//开始数据验证
		if(!check_mobile(trim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		//$user=$GLOBALS['db']->getOne("select * from ".DB_PREFIX."user where mobile='".trim($user_data['mobile'])."');
		delete_mobile_verify_code();
		$verify_code=$GLOBALS['db']->getOne("select * from ".DB_PREFIX."mobile_verify_code where mobile='".trim($user_data['mobile'])."' and verify_code='".trim($user_data['verify_coder'])."'");
		if($verify_code){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET user_pwd='".md5($user_data['user_pwd'])."'");
		}
	}

	/**
	 * 删除会员以及相关数据
	 * @param integer $id
	 */
	function delete_user($id)
	{
		
		$result = 1;
		//载入会员整合
		$integrate_code = trim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='')
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}	
		}
		if($integrate_obj)
		{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);			
			$result = $integrate_obj->delete_user($user_info);				
		}
		
		if($result>0)
		{

			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."referrals where user_id = ".$id);
			
			//$GLOBLAS['db']->query("delete from ".DB_PREFIX."deal where user_id = ".$id); //不删除相关的项目记录
			
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_msg_list where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_support_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."payment_notice where user_id = ".$id);
			
			
			
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user where id =".$id); //删除会员			
		}
	}

	/**
	 * 会员资金积分变化操作函数
	 * @param array $data 包括 money
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 * @param array $param 要插入的数组
	 */
	function modify_account($data,$user_id,$log_msg='',$param=array())
	{
		$user_info=$GLOBALS['db']->getRow("select id,money,score,point,user_level from  ".DB_PREFIX."user where id=".$user_id);
		
		$money=$data['money'];
		$mortgage_money=$data['mortgage_money'];
		
		$score=intval($data['score']);
		$point=intval($data['point']);
		
		if(floatval($money)!=0 && ($user_info['money']+$money)<0)
			return false;
		
		if($score !=0 && ($user_info['score']+$score)<0)
			return false;
			
		if($point !=0 && ($user_info['point']+$point)<0)
			return false;
			
	 	if(floatval($data['money'])!=0)
		{
			$sql = "update ".DB_PREFIX."user set money = money + ".floatval($data['money'])." where id =".$user_id;
			$GLOBALS['db']->query($sql);
		}
		if($score !=0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set score = score + ".intval($data['score'])." where id =".$user_id);
		}
		if($point !=0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set point = point + ".intval($data['point'])." where id =".$user_id);
		}
		if($mortgage_money !=0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set mortgage_money = mortgage_money + ".floatval($data['mortgage_money'])." where id =".$user_id);
		}
		
		if(floatval($data['money'])!=0 || $score !=0 || $point !=0||floatval($data['mortgage_money'])!=0 )
		{
			//获取更新后 的会员信息进行等级变更
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
			//信用变更计算会员等级
			if($point !=0)
			{
				user_leverl_syn($user_info);//$user_date 要包括会员id,会员等级,会员信用值
			}
				
			
			$log_info['log_info'] = $log_msg;
			$log_info['log_time'] = get_gmtime();
			$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
			$adm_id = intval($adm_session['adm_id']);
			if($adm_id!=0)
			{
				$log_info['log_admin_id'] = $adm_id;
			}
			if(is_array($param)&&count($param)>0){
				foreach($param as $k=>$v){
					$log_info[$k] = $v;
				}
			}
			if(intval($data['mortgage_money'])){
				$log_info['money'] = floatval($data['mortgage_money']);
			}else{
				$log_info['money'] = floatval($data['money']);
			}

			$log_info['score'] = $score;
			$log_info['user_id'] = intval($user_id);
			$log_info['point'] = $point;
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
			if($GLOBALS['db']->insert_id()){
				if($param[money_type]==17){
					//充值
					$GLOBALS['msg']->manage_msg("MSG_INCHARGE",$log_info['user_id'],array('money'=>$log_info['money']));
				}elseif($param[money_type]==19){
					//退款通知
					$GLOBALS['msg']->manage_msg("MSG_REFUND",$log_info['user_id'],array('money'=>$log_info['money'],'content'=>$log_msg));
				}elseif($param[money_type]==22){
					//接收筹款
					$GLOBALS['msg']->manage_msg("notify",$user_id,array('content'=>$content,'url_route'=>$url_route,'url_param'=>$url_param));
				}
			}
			
		}
		return true;
	}

	/**
	 * 处理cookie的自动登录
	 * @param $user_name_or_email  用户名或邮箱
	 * @param $user_md5_pwd  md5加密过的密码
	 */
	function auto_do_login_user($user_name_or_email,$user_md5_pwd)
	{
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name='".$user_name_or_email."' or email = '".$user_name_or_email."'  or mobile = '".$user_name_or_email."' ) and is_effect = 1");
	
		if($user_data)
		{
			if(md5($user_data['user_pwd']."_EASE_COOKIE")==$user_md5_pwd)
			{
				//登录成功自动检测关于会员等级	
				user_leverl_syn($user_data);//$user_data 要包括会员id,会员等级,会员信用值
				
				//成功				
				$build_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and user_id = ".$user_data['id']);
				$focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where user_id = ".$user_data['id']);
				$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_support_log where user_id = ".$user_data['id']);
				es_session::set("user_info",$user_data);
				$GLOBALS['user_info'] = $user_data;
				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= ".get_gmtime().",build_count = $build_count,support_count = $support_count,focus_count = $focus_count where id =".$user_data['id']);				
			}
		}
	}
	/**
	 * 处理会员登录
	 * @param $user_name_or_email 用户名或邮箱地址
	 * @param $user_pwd 密码
	 * 
	 */
	function do_login_user($user_name_or_email,$user_pwd)
	{
		
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name='".$user_name_or_email."' or email = '".$user_name_or_email."' or mobile = '".$user_name_or_email."' )");
 		//载入会员整合
		$integrate_code = trim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='')
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}	
		}
		if($integrate_obj)
		{			
			$result = $integrate_obj->login($user_name_or_email,$user_pwd);	
							
		}
		
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name='".$user_name_or_email."' or email = '".$user_name_or_email."' or mobile = '".$user_name_or_email."' )");	
		if(!$user_data)
		{			
			$result['status'] = 0;
			$result['data'] = ACCOUNT_NO_EXIST_ERROR;
			return $result;
		}
		else
		{
			$result['user'] = $user_data;
			if($user_data['user_pwd'] != md5($user_pwd.$user_data['code'])&&$user_data['user_pwd']!=$user_pwd)
			{
				$result['status'] = 0;
				$result['data'] = ACCOUNT_PASSWORD_ERROR;
				return $result;
			}
			elseif($user_data['is_effect'] != 1)
			{
				$result['status'] = 0;
				$result['data'] = ACCOUNT_NO_VERIFY_ERROR;
				return $result;
			}
			else
			{

				if(intval($result['status'])==0) //未整合，则直接成功
				{
					$result['status'] = 1;
				}
				
				//登录成功自动检测关于会员等级	
				user_leverl_syn($user_data);//$user_data 要包括会员id,会员等级,会员信用值
				$login_time = get_gmtime();
				
				$build_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and user_id = ".$user_data['id']);
				$focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where user_id = ".$user_data['id']);
				$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_support_log where user_id = ".$user_data['id']);
				$user_data['login_time'] = $login_time;
 				es_session::set("user_info",$user_data);
				$GLOBALS['user_info'] = $user_data;
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= ".$login_time.",build_count = $build_count,support_count = $support_count,focus_count = $focus_count where id =".$user_data['id']);
				$s_api_user_info = es_session::get("api_user_info");
				
				if($s_api_user_info)
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."user set ".$s_api_user_info['field']." = '".$s_api_user_info['id']."' where id = ".$user_data['id']." and (".$s_api_user_info['field']." = 0 or ".$s_api_user_info['field']."='')");
					es_session::delete("api_user_info");
				}		
 				$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$GLOBALS['user_info']['id'],array('type'=>'会员登录','content'=>'您的帐号  '.$GLOBALS['user_info']['user_name'].'  于   '.get_client_ip() ."  登录!"));				
				return $result;
			}
		}
	}
	
	/**
	 * 登出,返回 array('status'=>'',data=>'',msg=>'') msg存放整合接口返回的字符串
	 */
	function loginout_user()
	{
		$user_info = es_session::get("user_info");
		
		if(!$user_info)
		{
			return false;
		}
		else
		{
			//载入会员整合
			$integrate_code = trim(app_conf("INTEGRATE_CODE"));
			if($integrate_code!='')
			{
				$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
				if(file_exists($integrate_file))
				{
					require_once $integrate_file;
					$integrate_class = $integrate_code."_integrate";
					$integrate_obj = new $integrate_class;
				}	
			}
			if($integrate_obj)
			{
				$result = $integrate_obj->logout();					
			}
			if(intval($result['status'])==0)	
			{
				$result['status'] = 1;
			}	
 			$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$user_info['id'],array('type'=>'会员登出','content'=>'您的帐号  '.$user_info['user_name'].'  于  '.get_client_ip() ." 登出！"));				
  			es_session::delete(md5("mobile_is_bind".$user_info['id']));
			es_cookie::delete(md5("mobile_is_bind".$user_info['id']));
			es_session::delete("user_info");
			
			return $result;
		}
	}
	
	
	
	
	
	/**
	 * 验证会员数据
	 */
	function check_user($field_name,$field_data)
	{		
		delete_mobile_verify_code();
		//开始数据验证
		$user_data[$field_name] = $field_data;
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['user_name'])==''&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(mb_strlen(trim($user_data['user_name']))<4&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='user_name'&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($user_data['user_name'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(app_conf("USER_VERIFY")!=2||$user_data['email']!=''){
			if($field_name=='email'&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))>0)
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
			if($field_name=='email'&&trim($user_data['email'])=='')
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
			if($field_name=='email'&&!check_email(trim($user_data['email'])))
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}
		if($field_name=='mobile'&&!check_mobile(trim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='mobile'&&$user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}		
		if($field_name=='verify_coder'&&(app_conf("USER_VERIFY")==2||app_conf("USER_VERIFY")==4)){
			if(strim($_REQUEST['verify_coder'])==''){
				$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			if(!check_verify_coder(trim($_REQUEST['verify_coder']))){
				$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			
			$check_code_sql="SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".strim($_REQUEST['mobile'])." AND verify_code='".trim($_REQUEST['verify_coder'])."'";
			 
			if($GLOBALS['db']->getOne($check_code_sql)==0)
			{
				 
	 			$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}
		
		if($field_name=='verify_coder_email'&&(app_conf("USER_VERIFY")==1||app_conf("USER_VERIFY")==4)){
			if(strim($_REQUEST['verify_coder_email'])==''){
				$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			if(!check_verify_coder(trim($_REQUEST['verify_coder_email']))){
				$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			
			$check_code_sql="SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE email='".strim($_REQUEST['email'])."' AND verify_code='".trim($_REQUEST['verify_coder_email'])."'";
		
			if($GLOBALS['db']->getOne($check_code_sql)==0)
			{
				 
	 			$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}
		
		
		return $res;
	}
	
	function modify_advance($data,$user_id,$log_msg='',$param=array())
	{
		$user=$GLOBALS['db']->getRow("select mortgage_money,money from  ".DB_PREFIX."user where id=".$user_id);
		//$user_money=$user['mortgage_money']+$user['money'];
		$money=floatval($data['money']);
 		$mortgage_money=floatval($data['mortgage_money']);
 		 if(($user['mortgage_money']+$mortgage_money)>=0&&($user['money']+$money)>=0){
		 	if(floatval($data['mortgage_money'])!=0)
			{
				$sql = "update ".DB_PREFIX."user set mortgage_money = mortgage_money + ".floatval($data['mortgage_money'])." where id =".$user_id;
				$GLOBALS['db']->query($sql);
			}
			if(floatval($data['money'])!=0){
				$sql = "update ".DB_PREFIX."user set money = money + ".floatval($data['money'])." where id =".$user_id;
				$GLOBALS['db']->query($sql);
			}
			if(floatval($data['ben_money'])!=0
			||floatval($data['earn_money'])!=0
			||floatval($data['buy_money'])!=0
			||floatval($data['fee'])!=0 
			||floatval($data['buy_frozen'])!=0
			||floatval($data['service_fee'])!=0
			||floatval($data['pay_money'])!=0
			||floatval($data['organiser_fee'])!=0
			){
				
				licai_log($data,$user_id,$log_msg);
 			}
			elseif(floatval($data['money'])!=0)
			{
				$log_info['log_info'] = $log_msg;
				$log_info['log_time'] = get_gmtime();
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
				$adm_id = intval($adm_session['adm_id']);
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				if(is_array($param)&&count($param)>0){
					foreach($param as $k=>$v){
 						$log_info[$k] = $v;
					}
				}
				$log_info['money'] = floatval($data['money']);
				if($data['mortgage_money']){
					$log_info['money'] = floatval($log_info['money']+$data['mortgage_money']);
				}
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				
			}
			return true;
		 }else{
		 	return false;
		 }
	}
	/**
	 * 会员资金积分变化操作函数
	 * @param array $data 包括 money
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 * @param array $param 要插入的数组
	 */
	function modify_account_ben($data,$user_id,$log_msg='',$param=array())
	{
		$user_money=$GLOBALS['db']->getOne("select money from  ".DB_PREFIX."user where id=".$user_id);
		$money=$data['money'];
		 if(($user_money+$money)>=0){
		 	if(floatval($data['money'])!=0)
			{
				$sql = "update ".DB_PREFIX."user set money = money + ".floatval($data['money'])." where id =".$user_id;
				$GLOBALS['db']->query($sql);
			}
			
			if(floatval($data['ben_money'])!=0){ 
				licai_log($data,$user_id);
			}
			elseif(floatval($data['money'])!=0)
			{
				
				$log_info['log_info'] = $log_msg;
				$log_info['log_time'] = get_gmtime();
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
				$adm_id = intval($adm_session['adm_id']);
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				if(is_array($param)&&count($param)>0){
					foreach($param as $k=>$v){
 						$log_info[$k] = $v;
					}
				}
				$log_info['money'] = floatval($data['money']);
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				
			}
			return true;
		 }else{
		 	return false;
		 }
			
 		
	}
	 /**
	 * 理财操作的用户日志
	 * @param array $data 包括 money  //ben_money 表示本金、earn_money 表示收益、fee 表示费用
	 * @param integer $user_id  会员ID
	 * @param string $log_msg  日志信息
	 */
	function licai_log($data,$user_id,$log_msg){
				$log_info['log_time'] = get_gmtime();
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
				$adm_id = intval($adm_session['adm_id']);
				$log_info['log_info'] = $log_msg;
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				$log_info['user_id'] = $user_id;
 				if( floatval($data['ben_money'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "赎回本金";
					$log_info['type'] = 8;
					$log_info['money'] = floatval($data['ben_money']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
  				if(floatval($data['earn_money'])!=0){
					//赎回收益
	 				$log_info['log_info'] = "赎回收益";
					$log_info['type'] = 9;
					$log_info['money'] = floatval($data['earn_money']);
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				if(floatval($data['fee'])!=0){
					//赎回手续费
	 				$log_info['log_info'] = "赎回手续费";
					$log_info['type'] = 10;
					$log_info['money'] = floatval($data['fee']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				
				if(floatval($data['organiser_fee'])!=0){
					//赎回手续费
	 				$log_info['log_info'] = "平台收益"; 
					$log_info['type'] = 10;
					$log_info['money'] = floatval($data['organiser_fee']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				
				if( floatval($data['buy_money'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "理财购买本金";
					$log_info['type'] = 11;
					$log_info['money'] = floatval($data['buy_money']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				if( floatval($data['buy_fee'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "理财购买手续费";
					$log_info['type'] = 12;
					$log_info['money'] = floatval($data['buy_fee']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				if(floatval($data['buy_frozen'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "理财冻结资金";
					$log_info['type'] = 13;
					$log_info['money'] = floatval($data['buy_frozen']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				
				if( floatval($data['service_fee'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "理财服务费";
					$log_info['type'] = 14;
					$log_info['money'] = floatval($data['service_fee']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				
				if( floatval($data['pay_money'])!=0){
					//赎回本金
	 				$log_info['log_info'] = "理财发放资金";
					$log_info['type'] = 15;
					$log_info['money'] = floatval($data['pay_money']);
	 				
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				}
				return true;
	}
	 /*
	 * 获取用户信息
	 * @param $field_data  会员ID
	 * @param $field_name  查询的字段名称
	 */
	function get_user_info($field_name,$field_data){
		
		//$get_user_sql= "select * from  ".DB_PREFIX."user where id=".$field_data;
		$get_user_info = $GLOBALS['db']->getOne("select $field_name from  ".DB_PREFIX."user where id=".$field_data);
		return $get_user_info;
		
	}
	

?>