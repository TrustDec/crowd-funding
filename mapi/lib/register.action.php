<?php
/**
	 * 生成会员数据
	 * @param $user_data  提交[post或get]的会员数据
	 * @param $mode  处理的方式，注册或保存
	 * 返回：data中返回出错的字段信息，包括field_name, 可能存在的field_show_name 以及 error 错误常量
	 * 不会更新保存的字段为：score,money,verify,pid

function add_user($user_data)
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
		
		
		//验证结束开始插入数据
		$user['user_name'] = $user_data['user_name'];
		$user['create_time'] = get_gmtime();
		$user['update_time'] = get_gmtime();
		//自动获取会员分组
		$user['group_id'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_group order by score asc limit 1");
		$user['is_effect'] = 1; //手机注册自动生效
		
		$user['email'] = $user_data['email'];

		$user['user_pwd'] = md5($user_data['user_pwd']);
		
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
			$res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
			$user['integrate_id'] = intval($res['data']);		
			if(intval($res['status'])==0) //整合注册失败
			{
				//return $res;  //不处理
			}
		}
		if($res['status']>0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"INSERT","");
			$user_id = $GLOBALS['db']->insert_id();	
			if($user_id > 0)
			{
					$register_money = floatval(app_conf("USER_REGISTER_MONEY"));
					$register_score = intval(app_conf("USER_REGISTER_SCORE"));
					if($register_money>0||$register_score>0)
					{
						$user_get['score'] = $register_score;
						$user_get['money'] = $register_money;
						modify_account($user_get,intval($user_id),"在".to_date(get_gmtime())."注册成功");
					}
			}
			$res['data'] = $user_id;
		}

			if(strim($GLOBALS['request']['sina_id'])!='')
			{
				if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where sina_id = '".strim($GLOBALS['request']['sina_id'])."'")==0)
				{
					$access_token =  trim($GLOBALS['request']['access_token']);
					$GLOBALS['db']->query("update ".DB_PREFIX."user set sina_id = '".strim($GLOBALS['request']['sina_id'])."',sina_token = '".$access_token."' where id = ".$user_id);				
				}
				
				
			}
			if(strim($GLOBALS['request']['tencent_id'])!='')
			{
				if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where tencent_id = '".strim($GLOBALS['request']['tencent_id'])."'")==0)
				{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set tencent_id = '".strim($GLOBALS['request']['tencent_id'])."' where id = ".$user_id);
			
				$openid = trim($GLOBALS['request']['openid']);
				$openkey = trim($GLOBALS['request']['openkey']);
		 		$access_token =  trim($GLOBALS['request']['access_token']);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set t_access_token ='".$access_token."',t_openkey = '".$openkey."',t_openid = '".$openid."', login_ip = '".get_client_ip()."',login_time= ".get_gmtime()." where id =".$user_id);				
				
				}
				
			}
		
		return $res;
}
*/
class register
{
	public function index()
	{    
		$user_verify=app_conf("USER_VERIFY");//注册验证方式，0：无需验证，1：邮箱验证，2：手机验证,3:管理验证，4：手机与邮箱验证
		$user_name = strim ( $GLOBALS ['request'] ['user_name'] ); // 用户名
		$user_pwd = strim ( $GLOBALS ['request'] ['user_pwd'] ); // 密码
		$user_pwd_confirm = strim ( $GLOBALS ['request'] ['user_pwd_confirm'] );
		$mobile = strim ( $GLOBALS ['request'] ['mobile'] );
		$mobile_code = strim ( $GLOBALS ['request'] ['mobile_code'] );
		
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$email_code = strim ( $GLOBALS ['request'] ['email_code'] );
		$user_data = array (
				'mobile' => $mobile,
				'email' => $email,
				'user_name' => $user_name,
				'user_pwd' => $user_pwd,
				'user_pwd_confirm' => $user_pwd_confirm 
		);
		if($user_verify !=2)
		{
			$user_data['email'] = $email;
		}
		if($user_verify ==2 || $user_verify ==4)
		{
			$user_data['mobile'] = $mobile;
		}
		require_once APP_ROOT_PATH . "system/libs/user.php";
		$check_status = $this->check_user ( $user_data );
		/////////
		if ($check_status ['status'] == 1)
		{
			$now_time=get_gmtime ();
			// 删除超过5分钟的验证码
			if($mobile_code !='' || $email_code !='' )
				$GLOBALS ['db']->query ( "DELETE FROM " . DB_PREFIX . "mobile_verify_code WHERE create_time <=" . ($now_time - 300) );
			
			if($user_verify ==2 || $user_verify ==4 )//2：手机验证,4：手机与邮箱验证
			{
				// 短信验证码
				if(!$mobile_code)
				{
					$root ['response_code'] = 0;
					$root ['info'] = "请输入手机验证码";
					output($root);
				}
				if ($mobile_code != $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and create_time>=" . ($now_time - 300) . " ORDER BY id DESC" ))
				{
					$root ['response_code'] = 0;
					$root ['info'] = "短信验证码错误或已失效";
					// output($root);
				} 
			}
			
			if($user_verify ==1 || $user_verify ==4)//1：邮箱验证,4：手机与邮箱验证
			{
				// 邮件验证码
				if(!$email_code)
				{
					$root ['response_code'] = 0;
					$root ['info'] = "请输入邮件验证码";
					output($root);
				}
				
				if ($email_code != $GLOBALS ['db']->getOne ( "select verify_code from " . DB_PREFIX . "mobile_verify_code where email = '" . $email . "' and create_time >=" . ($now_time - 300) . " ORDER BY id DESC" ))
				{
					$root ['response_code'] = 0;
					$root ['info'] = "邮件验证码错误或已失效";
					output($root);
				} 
			}
			 
			
			if($user_verify ==3)
				$user_data['is_effect']=0;
			else
				$user_data['is_effect']=1;
				
			$user_id = $this->add_user ( $user_data );
			if( $user_id>0)
			{	$user_id=intval($user_id);
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
				if($user_info['is_effect'] ==1)
				{
					$result = user_login ( $user_name, $user_pwd );
					if ($result ['status'])
					{
						$user_data = $GLOBALS ['user_info']; // $result['user'];
						$root ['user_login_status'] = 1; // 用户登陆状态：1:成功登陆;0：未成功登陆
						$root ['response_code'] = 1;
						$root ['info'] = "用户登陆成功";
						// $root ['id'] = $user_data ['id'];
						$root ['user_name'] = $user_data ['user_name'];
						$root ['money'] = $user_data ['money'];
						$root ['money_format'] = format_price ( $user_data ['money'] ); // 用户金额
						$root ['mobile'] = $user_data ['mobile'];
						$root ['user_id']=$user_data['id'];
						$root ['intro'] = $user_data ['intro'];
						$root ['email'] = $user_data ['email'];
						$root ['province'] = $user_data ['province'];
						$root ['city'] = $user_data ['city'];
						$root ['sex'] = $user_data ['sex'];
						$root ['image'] = get_user_avatar_root ( $user_data ['id'], "middle" );
						$root ['user_avatar'] = get_abs_img_root ( get_muser_avatar ( $user_data ['id'], "big" ) );
						$weibo_list = $GLOBALS ['db']->getOne ( "select weibo_url from " . DB_PREFIX . "user_weibo where user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
						$root ['weibo_list'] = $weibo_list;
						$user ['create_time_format'] = to_date ( $user_data ['create_time'], 'Y-m-d' ); // 注册时间
						$root ['create_time_format'] = $user ['create_time_format'];
						
						$root ['investor_status'] = intval ( $user ['investor_status'] );
						$root ['identify_positive_image'] = $user ['identify_positive_image'];
						$root ['identify_business_licence'] = $user ['identify_business_licence'];
					}
					else
					{
						$root ['response_code'] = 1;
						$root ['user_login_status'] = 0; // 用户登陆状态：1:成功登陆;0：未成功登陆
						$root ['info'] = "会员注册成功,登录失败,请联系管理员.";
						// output($root);
					}
				}
				else
				{
					$root ['response_code'] = 1;
					$root ['user_login_status'] = 0; // 用户登陆状态：1:成功登陆;0：未成功登陆
					$root ['info'] = "会员注册成功,请等待管理员审核。";
				}
			}
			else
			{   //数据插入失败
				$root ['response_code'] = 0;
				$root ['user_login_status'] = 0; // 用户登陆状态：1:成功登陆;0：未成功登陆
				$root ['info'] = "会员注册失败.";
			}
			
		}else
		{	//提交注册信息验证未通过
			$root ['response_code'] = 0;
			$root ['user_login_status'] = 0; // 用户登陆状态：1:成功登陆;0：未成功登陆
			$root ['show_err'] = $check_status ['error_msg'];
			$root ['info'] = $check_status ['error_msg'];
			// output($root);
		}
		

		output ( $root );
	}
	function check_user($user_data)
	{
		$user_verify=app_conf("USER_VERIFY");//注册验证方式，0：无需验证，1：邮箱验证，2：手机验证,3:管理验证，4：手机与邮箱验证
		
		// 开始数据验证
		$res = array (
				'status' => 1,
				'info' => '',
				'data' => '',
				'error_msg' => '' 
		); // 用于返回的数据
		
		if ($user_data ['user_pwd'] != $user_data ['user_pwd_confirm'])
		{
			$res ['status'] = 0;
			$res ['error_msg'] = '密码确认失败';
			return $res;
		}
		
		if (trim ( $user_data ['user_pwd'] ) == '')
		{
			$res ['status'] = 0;
			$res ['error_msg'] = '请输入密码';
			return $res;
		}
		
		if (strlen ( $user_data ['user_pwd'] ) <4)
		{
			$res ['status'] = 0;
			$res ['error_msg'] = '密码不能小于4位';
			return $res;
		}
		
		if ($res ['status'] == 1 && trim ( $user_data ['user_name'] ) == '')
		{
			$field_item ['field_name'] = '用户名';
			$field_item ['error'] = EMPTY_ERROR;
			$res ['status'] = 0;
			$res ['data'] = $field_item;
		}
		
		if ($res ['status'] == 1 && $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where user_name = '" . trim ( $user_data ['user_name'] ) . "' and id <> " . intval ( $user_data ['id'] ) ) > 0)
		{
			$field_item ['field_name'] = '用户名';
			$field_item ['error'] = EXIST_ERROR;
			$res ['status'] = 0;
			$res ['data'] = $field_item;
		}
		
		//邮箱验证 除了手机验证都要验证
		if($res ['status'] == 1 && $user_verify !=2)
		{
			if($user_data ['email'] =='')
			{
				$field_item ['field_name'] = '邮箱';
				$field_item ['error'] = EMPTY_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
			if($res ['status'] == 1 && !check_email(trim($user_data['email'])))
			{
				$field_item ['field_name'] = '邮箱';
				$field_item ['error'] = FORMAT_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
			if($res ['status'] == 1 && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))>0)
			{
				$field_item ['field_name'] = '邮箱';
				$field_item ['error'] = EXIST_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
		}
		
		//手机验证
		if($res ['status'] == 1 && ($user_verify ==2 || $user_verify ==4) )
		{
			if (trim ( $user_data ['mobile'] ) == '')
			{
				$field_item ['field_name'] = '手机号';
				$field_item ['error'] = EMPTY_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
			
			if ($res ['status'] == 1 && ! check_mobile ( trim ( $user_data ['mobile'] ) ))
			{
				$field_item ['field_name'] = '手机号';
				$field_item ['error'] = FORMAT_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
			
			if ($res ['status'] == 1 && $user_data ['mobile'] != '' && $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where mobile = '" . trim ( $user_data ['mobile'] ) . "' and id <> " . intval ( $user_data ['id'] ) ) > 0)
			{
				$field_item ['field_name'] = '手机号';
				$field_item ['error'] = EXIST_ERROR;
				$res ['status'] = 0;
				$res ['data'] = $field_item;
			}
		}
		
		
		if ($res ['status'] == 0)
		{
			$error = $res ['data'];
			$error_msg = "";
			if (! $error ['field_show_name'])
			{
				$error ['field_show_name'] = sprintf ( '%s', $error ['field_name'] );
			}
			if ($error ['error'] == EMPTY_ERROR)
			{
				$error_msg = sprintf ( '%s不能为空', $error ['field_show_name'] );
			}
			if ($error ['error'] == FORMAT_ERROR)
			{
				$error_msg = sprintf ( '%s格式错误，请重新输入', $error ['field_show_name'] );
			}
			if ($error ['error'] == EXIST_ERROR)
			{
				$error_msg = sprintf ( '%s已存在，请重新输入', $error ['field_show_name'] );
			}
			// showErr($error_msg);
			
			$res ['error_msg'] = $error_msg;
		}
		
		return $res;
	}
	
	/**
	 * 生成会员数据
	 *
	 * @param $user_data 提交[post或get]的会员数据        	
	 * @param $mode 处理的方式，注册或保存
	 *        	返回：data中返回出错的字段信息，包括field_name, 可能存在的field_show_name 以及 error
	 *        	错误常量
	 *        	不会更新保存的字段为：score,money,verify,pid
	 */
	function add_user($user_data)
	{
		
		// $res = array('status'=>1,'id'=>0); //用于返回的数据
		
		// 验证结束开始插入数据
		$user_id = 0;
		
		$user ['user_name'] = $user_data ['user_name'];
		$user ['create_time'] = TIME_UTC;
		$user ['update_time'] = TIME_UTC;
		// $user['pid'] = $user_data['pid'];
		
		// 获取默认会员组, 即升级积分最小的会员组
		$user ['group_id'] = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "user_group order by score asc limit 1" );
		
		$user ['is_effect'] = $user_data['is_effect'];
		$user ['mobile'] = $user_data ['mobile'];
		$user ['mobilepassed'] = 1; // 是否已经绑定手机；1：是；0：否; 手机注册的，直接就绑定手机了;
		$user ['code'] = ''; // 默认不使用code, 该值用于其他系统导入时的初次认证
		$user ['user_pwd'] = md5 ( $user_data ['user_pwd'] . $user ['code'] );
		$user ['email'] = $user_data ['email'];
		
		/*
		 * //载入会员整合，手机端没填：email，暂时不做会员整合; $integrate_code =
		 * trim(app_conf("INTEGRATE_CODE")); if($integrate_code!='') {
		 * $integrate_file =
		 * APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
		 * if(file_exists($integrate_file)) { require_once $integrate_file;
		 * $integrate_class = $integrate_code."_integrate"; $integrate_obj = new
		 * $integrate_class; } } //同步整合 if($integrate_obj) { $res =
		 * $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
		 * $user['integrate_id'] = intval($res['data']);
		 * if(intval($res['status'])==0) //整合注册失败 { return $res; } }
		 * $s_api_user_info = es_session::get("api_user_info");
		 * $user[$s_api_user_info['field']] = $s_api_user_info['id'];
		 * es_session::delete("api_user_info");
		 */
		if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", $user, 'INSERT' ))
		{
			$user_id = $GLOBALS ['db']->insert_id ();
			$register_money = floatval ( app_conf ( "USER_REGISTER_MONEY" ) );
			$register_score = intval ( app_conf ( "USER_REGISTER_SCORE" ) );
			$register_point = intval ( app_conf ( "USER_REGISTER_POINT" ) );
			$register_lock_money = intval ( app_conf ( "USER_LOCK_MONEY" ) );
			if ($register_money > 0 || $register_score > 0 || $register_point > 0 || $register_lock_money > 0)
			{
				$user_get ['score'] = $register_score;
				$user_get ['money'] = $register_money;
				$user_get ['point'] = $register_point;
				$user_get ['reg_lock_money'] = $register_lock_money;
				modify_account ( $user_get, intval ( $user_id ), "在" . to_date ( TIME_UTC ) . "注册成功" );
			}
		}
		
		return $user_id;
	}
}
?>