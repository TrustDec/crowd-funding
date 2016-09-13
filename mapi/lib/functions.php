<?php
// 输出接口数据
function output($data)
{
	header ( "Content-Type:text/html; charset=utf-8" );
	$r_type = intval ( $_REQUEST ['r_type'] ); // 返回数据格式类型;
	                                           // 0:base64;1;json_encode;2:array
	$data ['act'] = ACT;
	$data ['act_2'] = ACT_2;
	$data ['gq_name'] = app_conf ( "GQ_NAME" );
	sql_check ( "wap" );
	if ($r_type == 0)
	{
		require_once APP_ROOT_PATH . 'system/libs/json.php';
		$JSON = new JSON ();
		print_r ( base64_encode ( $JSON->encode ( $data ) ) );
		// echo base64_encode(json_encode($data));
	} else if ($r_type == 1)
	{
		
		// echo APP_ROOT_PATH; exit;
		require_once APP_ROOT_PATH . 'system/libs/json.php';
		// echo 'ss';exit;
		$JSON = new JSON ();
		print_r ($JSON->encode ( $data ) );
		
		// print_r(json_encode($data));
	} else if ($r_type == 2)
	{
		print_r ( $data );
	}else if($r_type == 4){
		require_once APP_ROOT_PATH.'/system/libs/crypt_aes.php';
		$aes = new CryptAES();
		$aes->set_key('FANWE5LMUQC889ZC');
		$aes->require_pkcs5();
		$encText = $aes->encrypt(json_encode($data));
		echo $encText;
	};
	;
	exit ();
}
 

/**
 * 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
 *
 * @access public
 * @param string $sql
 *        	SQL查询串
 * @return string 返回已过滤掉注释的SQL查询串。
 *        
 */
function remove_comment($sql)
{
	/* 删除SQL行注释，行注释不匹配换行符 */
	$sql = preg_replace ( '/^\s*(?:--|#).*/m', '', $sql );
	
	/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
	// $sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
	$sql = preg_replace ( '/^\s*\/\*.*?\*\//ms', '', $sql );
	
	return $sql;
}
function emptyTag($string)
{
	if (empty ( $string ))
		return "";
	
	$string = strip_tags ( trim ( $string ) );
	$string = preg_replace ( "|&.+?;|", '', $string );
	
	return $string;
}
function get_abs_img_root($content)
{
	return str_replace ( "./public/", get_domain () . APP_ROOT . "/../public/", $content );
	// return str_replace('/mapi/','/',$str);
}
//
function get_abs_img_root_wap($content)
{
	return str_replace ( "./public/", get_domain () . APP_ROOT . "/public/", $content );
	// return str_replace('/mapi/','/',$str);
}
function get_abs_url_root($content)
{
	$content = str_replace ( "./", get_domain () . APP_ROOT . "/../", $content );
	return $content;
}
function user_check($username_email, $pwd)
{
	// $username_email = addslashes($username_email);
	// $pwd = addslashes($pwd);
	if ($username_email && $pwd)
	{
		// $sql = "select *,id as uid from ".DB_PREFIX."user where
		// (user_name='".$username_email."' or email = '".$username_email."')
		// and is_delete = 0";
		$sql = "select *,id as uid from " . DB_PREFIX . "user where (user_name='" . $username_email . "' or email = '" . $username_email . "' or mobile = '" . $username_email . "') ";
		$user_info = $GLOBALS ['db']->getRow ( $sql );
		
		$is_use_pass = false;
		if (strlen ( $pwd ) != 32)
		{
			if ($user_info ['user_pwd'] == md5 ( $pwd . $user_info ['code'] ) || $user_info ['user_pwd'] == md5 ( $pwd ))
			{
				$is_use_pass = true;
			}
		} else
		{
			if ($user_info ['user_pwd'] == $pwd)
			{
				$is_use_pass = true;
			}
		}
		if ($is_use_pass)
		{
			es_session::set ( "user_info", $user_info );
			$GLOBALS ['user_info'] = $user_info;
			return $user_info;
		} else
			return null;
	} else
	{
		return null;
	}
}
function uc_center($username_email, $pwd)
{
	
	// 检查用户,用户密码
	$user = user_check ( $username_email, $pwd );
	$user_id = intval ( $user ['id'] );
	if ($user_id > 0)
	{
		$province_str = $GLOBALS ['db']->getOne ( "select province from " . DB_PREFIX . "user where id = " . $user_id );
		$city_str = $GLOBALS ['db']->getOne ( "select city from " . DB_PREFIX . "user where id = " . $user_id );
		if ($province_str . $city_str == '')
			$user_location = '未知';
		else
			$user_location = $province_str . " " . $city_str;
		$user ['money_format'] = format_price ( $user ['money'] ); // 可用资金
		$user ['create_time_format'] = to_date ( $user ['create_time'], 'Y-m-d' ); // 注册时间
		$result ['user_login_status'] = 1;
		$result ['response_code'] = 1;
		$result ['user_name'] = $user ['user_name'];
		$result ['id'] = $user ['id'];
		$result ['email'] = $user ['email'];
		$result ['money'] = $user ['money'];
		$result ['money_format'] = $user ['money_format'];
		$result ['score'] = $user ['score'];
		$result ['point'] = $user ['point'];
		$result ['province'] = $user ['province'];
		$result ['city'] = $user ['city'];
		$result ['sex'] = $user ['sex'];
		$result ['intro'] = $user ['intro'];
		$result ['info'] = "";
		$result ['mobile'] = $user ['mobile'];
		$result ['image'] = get_user_avatar_root ( $user ['id'], "middle" );
		$result ['user_avatar'] = get_abs_img_root ( get_muser_avatar ( $user ['id'], "big" ) );
		$weibo_list = $GLOBALS ['db']->getOne ( "select weibo_url from " . DB_PREFIX . "user_weibo where user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
		$result ['weibo_list'] = $weibo_list;
		$result ['create_time_format'] = $user ['create_time_format'];
		$result ['job']=$user['job'];
		$result ['investor_status'] = intval ( $user ['investor_status'] );
		$result ['ex_real_name'] = $user ['ex_real_name'];
		$result ['identify_name'] = $user ['identify_name'];
		$result ['identify_number'] = $user ['identify_number'];
		$result ['identify_business_name'] = $user ['identify_business_name'];
		$result ['identify_business_name'] = $user ['identify_business_name'];
		$result ['identify_business_code'] = get_abs_img_root($user ['identify_business_code']);
		$result ['identify_business_tax'] = get_abs_img_root($user ['identify_business_tax']);
		$result ['identify_business_licence'] = get_abs_img_root($user ['identify_business_licence']);
		$result ['identify_positive_image'] = get_abs_img_root($user ['identify_positive_image']);
		$result ['identify_nagative_image'] = get_abs_img_root($user ['identify_nagative_image']);
		$result ['is_investor']=$user['is_investor'];
		$data=detectStateAudit($user);
		$result['status_express']=$data['status_express'];
		$result['status']=$data['status'];
	
		//领域
		$cate_name=unserialize(stripslashes($user['cate_name']));
		if($cate_name)
		{
			$cate_ids=array_keys($cate_name);
			$cate_list = $GLOBALS ['db']->getAll( "select * from " . DB_PREFIX . "deal_cate where id in (".implode(",",$cate_ids).") " );
			$result ['cate_name']=$cate_list;
		}
		else
		{
			$result ['cate_name']=array();
		}
		
		$is_user_investor=is_user_investor_mapi($user['is_investor'],$user['investor_status']);
		if($is_user_investor ==1)
		{
			$result['user_investor_status']=1;
			$result['user_investor_status_info']="身份认证已通过";
		}
		elseif($is_user_investor ==2){
			$result['user_investor_status']=2;
			$result['user_investor_status_info']="您的实名认证正在审核中";
		}else 
		{
			$result['user_investor_status']=0;
			$result['user_investor_status_info']="您未进行身份认证";
		}
		
		$result['ips_acct_no']=$user['ips_acct_no'];//托管平台账户号
		$result['ips_mer_code']=$user['ips_mer_code'];//商户号
		
		$result['acct_url']='';
		$is_tg=is_tg();
		if($is_tg && $user['ips_acct_no'] !='')
		{	
			$result['view_tg']=1;//会员绑定了第三方托管，显示第三方托管可用余额,给额一些第三方托管操作显示判断
			$result['view_tg_info']="网站开启第三方托管，会员已绑定第三方托管";
		}elseif($is_tg && $user['ips_acct_no'] =='')
		{	
			$result['view_tg']=2;//网站安装第三方，但会员没有绑定第三方托管
			$result['view_tg_info']="网站开启第三方托管，但会员没有绑定第三方托管";
			$app_url = HTML_APP_ROOT."index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$user_id."&from=app";
			$result['acct_url'] = SITE_DOMAIN.$app_url;//第三方托管 绑定url
		}
		else{
			$result['view_tg']=0;//不显示第三方托管可用余额
			$result['view_tg_info']="网站没有开启第三方托管";
		}
		
	} else
	{
		$result ['user_login_status'] = 0;
		$result ['response_code'] = 0;
		$result ['info'] = "未登录";
	}
	
	return $result;
}
function user_login($username_email, $pwd)
{
	require_once APP_ROOT_PATH . "system/libs/user.php";
	if (check_ipop_limit ( get_client_ip (), "user_dologin", intval ( app_conf ( "SUBMIT_DELAY" ) ) ))
	{
		$result = do_login_user ( $username_email, $pwd );
	} else
	{
		// showErr($GLOBALS['lang']['SUBMIT_TOO_FAST'],$ajax,url("shop","user#login"));
		$result ['status'] = 0;
		$result ['msg'] = $GLOBALS ['lang'] ['SUBMIT_TOO_FAST'];
		return $result;
	}
	
	if ($result ['status'])
	{
		// $GLOBALS['user_info'] = $result["user"];
		return $result;
	} else
	{
		$GLOBALS ['user_info'] = null;
		unset ( $GLOBALS ['user_info'] );
		
		if ($result ['data'] == ACCOUNT_NO_EXIST_ERROR)
		{
			$err = $GLOBALS ['lang'] ['USER_NOT_EXIST'];
		}
		if ($result ['data'] == ACCOUNT_PASSWORD_ERROR)
		{
			$err = $GLOBALS ['lang'] ['PASSWORD_ERROR'];
		}
		if ($result ['data'] == ACCOUNT_NO_VERIFY_ERROR)
		{
			$err = $GLOBALS ['lang'] ['USER_NOT_VERIFY'];
		}
		
		$result ['msg'] = $err;
		return $result;
	}
}
function init_deal_page($deal_info)
{
	$root ['page_title'] = $deal_info ['name'];
	if ($deal_info ['seo_title'] != "")
		$root ['seo_title'] = $deal_info ['seo_title'];
	if ($deal_info ['seo_keyword'] != "")
		$root ['seo_keyword'] = $deal_info ['seo_keyword'];
	if ($deal_info ['seo_description'] != "")
		$root ['seo_description'] = $deal_info ['seo_description'];
	$deal_info ['tags_arr'] = preg_split ( "/[ ,]/", $deal_info ['tags'] );
	
	$deal_info ['support_amount_format'] = number_price_format ( $deal_info ['support_amount'] );
	$deal_info ['limit_price_format'] = number_price_format ( $deal_info ['limit_price'] );
	
	$deal_info ['remain_days'] = ceil ( ($deal_info ['end_time'] - NOW_TIME) / (24 * 3600) );
	$deal_info ['percent'] = round ( $deal_info ['support_amount'] / $deal_info ['limit_price'] * 100 );
	$root ['deal_info'] = $deal_info;
	$deal_item_list = $deal_info ['deal_item_list'];
	$root ['deal_item_list'] = $deal_item_list;
	if ($deal_info ['user_id'] > 0)
	{
		$deal_user_info = $GLOBALS ['db']->getRow ( "select id,user_name,province,city,intro,login_time from " . DB_PREFIX . "user where id = " . $deal_info ['user_id'] . " and is_effect = 1" );
		if ($deal_user_info)
		{
			$deal_user_info ['weibo_list'] = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user_weibo where user_id = " . $deal_user_info ['id'] );
			$root ['deal_user_info'] = $deal_user_info;
		}
	}
	
	if ($GLOBALS ['user_info'])
	{
		$is_focus = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $deal_info ['id'] . " and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
		$root ['is_focus'] = $is_focus;
	}
}
function init_deal_page_wap($deal_info)
{
	$GLOBALS ['tmpl']->assign ( "page_title", $deal_info ['name'] );
	if ($deal_info ['seo_title'] != "")
		$GLOBALS ['tmpl']->assign ( "seo_title", $deal_info ['seo_title'] );
	if ($deal_info ['seo_keyword'] != "")
		$GLOBALS ['tmpl']->assign ( "seo_keyword", $deal_info ['seo_keyword'] );
	if ($deal_info ['seo_description'] != "")
		$GLOBALS ['tmpl']->assign ( "seo_description", $deal_info ['seo_description'] );
		
		// 开启限购后剩余几位
	$deal_info ['deal_item_count'] = 0;
	foreach ( $deal_info ['deal_item_list'] as $k => $v )
	{
		// 统计所有真实+虚拟（钱）
		$deal_info ['total_virtual_person'] += $v ['virtual_person'];
		$deal_info ['total_virtual_price'] += $v ['price'] * $v ['virtual_person'] + $v ['support_amount'];
		// 统计每个子项目真实+虚拟（钱）
		$deal_info ['deal_item_list'] [$k] ['person'] = $v ['virtual_person'] + $v ['support_count'];
		$deal_info ['deal_item_list'] [$k] ['money'] = $v ['price'] * $v ['virtual_person'] + $v ['support_amount'];
		$deal_info ['deal_item_list'] [$k] ['cart_url'] = url_wap ( "cart#index", array (
				"id" => $v ['id'] 
		) );
		if ($v ['limit_user'])
		{
			$deal_info ['deal_item_list'] [$k] ['remain_person'] = $v ['limit_user'] - $v ['virtual_person'] - $v ['support_count'];
		}
		$deal_info ['deal_item_count'] ++;
	}
	// $deal_info['deal_type']=$GLOBALS['db']->getOne("select name from
	// ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
	$deal_info ['tags_arr'] = preg_split ( "/[ ,]/", $deal_info ['tags'] );
	
	$deal_info ['support_amount_format'] = number_price_format ( $deal_info ['support_amount'] );
	$deal_info ['limit_price_format'] = number_price_format ( $deal_info ['limit_price'] );
	$deal_info ['total_virtual_price_format'] = number_price_format ( intval ( $deal_info ['total_virtual_price'] ) );
	$deal_info ['remain_days'] = ceil ( ($deal_info ['end_time'] - NOW_TIME) / (24 * 3600) );
	$deal_info ['percent'] = round ( $deal_info ['support_amount'] / $deal_info ['limit_price'] * 100 );
	
	// $deal_info['deal_level']=$GLOBALS['db']->getOne("select level from
	// ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
	$deal_info ['person'] = $deal_info ['total_virtual_person'] + $deal_info ['support_count'];
	$deal_info ['percent'] = round ( ($deal_info ['total_virtual_price'] / $deal_info ['limit_price']) * 100 );
	
	$deal_info ['update_url'] = url_wap ( "deal#update", array (
			"id" => $deal_info ['id'] 
	) );
	$deal_info ['comment_url'] = url_wap ( "deal#comment", array (
			"id" => $deal_info ['id'] 
	) );
	$deal_info ['info_url'] = url_wap ( "deal#info", array (
			"id" => $deal_info ['id'] 
	) );
	
	if ($deal_info ['begin_time'] > NOW_TIME)
	{
		$deal_info ['status'] = '0';
		$deal_info ['left_days'] = ceil ( ($deal_info ['begin_time'] - NOW_TIME) / (24 * 3600) );
	} elseif ($deal_info ['end_time'] < NOW_TIME && $deal_info ['end_time'] > 0)
	{
		if ($deal_info ['percent'] >= 100)
		{
			$deal_info ['status'] = '1';
		} else
		{
			$deal_info ['status'] = '2';
		}
	} else
	{
		if ($deal_info ['end_time'] > 0)
		{
			$deal_info ['status'] = '3';
		} else
			$deal_info ['status'] = '4';
	}
	if ($GLOBALS ['user_info'])
	{
		$is_focus = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $deal_info ['id'] . " and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
		$GLOBALS ['tmpl']->assign ( "is_focus", $is_focus );
	}
	if ($deal_info ['user_id'] > 0)
	{
		$deal_user_info = $GLOBALS ['db']->getRow ( "select id,user_name,province,city,intro,login_time from " . DB_PREFIX . "user where id = " . $deal_info ['user_id'] . " and is_effect = 1" );
		if ($deal_user_info)
		{
			$deal_user_info ['weibo_list'] = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user_weibo where user_id = " . $deal_user_info ['id'] );
			$deal_user_info ['image'] = get_user_avatar ( $deal_user_info ['id'], 'middle' );
			$deal_info ['user_info'] = $deal_user_info;
		}
	}
	
	$GLOBALS ['tmpl']->assign ( "deal_info", $deal_info );
}
function get_pre_wap()
{
	if ((ACT == "index") || (ACT == "project" && ACT_2 == "add") || (ACT == "project" && ACT_2 == "edit") || (ACT == "project" && ACT_2 == "add_item") || (ACT == "project" && ACT_2 == "edit_item") || (ACT == "deals" && ACT_2 == "index") || (ACT == "deal" && ACT_2 == "index") || (ACT == "deal" && ACT_2 == "show") || (ACT == "deal" && ACT_2 == "update") || (ACT == "deal" && ACT_2 == "updatedetail") || (ACT == "deal" && ACT_2 == "comment") || (ACT == "cart" && ACT_2 == "index") || (ACT == "cart" && ACT_2 == "pay") || (ACT == "faq") || (ACT == "help") || (ACT == "account" && ACT_2 == "index") || (ACT == "account" && ACT_2 == "incharge") || (ACT == "account" && ACT_2 == "pay") || (ACT == "account" && ACT_2 == "project") || (ACT == "account" && ACT_2 == "credit") || (ACT == "account" && ACT_2 == "view_order") || (ACT == "account" && ACT_2 == "focus") || (ACT == "account" && ACT_2 == "support") || (ACT == "account" && ACT_2 == "paid") || (ACT == "account" && ACT_2 == "refund") || (ACT == "news" && ACT_2 == "index") || (ACT == "news" && ACT_2 == "fav") || (ACT == "comment" && ACT_2 == "index") || (ACT == "comment" && ACT_2 == "send") || (ACT == "message" && ACT_2 == "index") || (ACT == "message" && ACT_2 == "history") || (ACT == "notify" && ACT_2 == "index") || (ACT == "settings" && ACT_2 == "index") || (ACT == "settings" && ACT_2 == "password") || (ACT == "settings" && ACT_2 == "bind") || (ACT == "settings" && ACT_2 == "consignee"))
	{
		set_gopreview ();
	}
}

// 发短信验证码
// $immediately: true，立即即行发送操作;
function send_verify_sms_app($mobile, $code, $user_info, $immediately)
{
	$re = array (
			'msg_id' => 0,
			'status' => 0,
			'msg' => '' 
	);
	
	if (app_conf ( "SMS_ON" ) == 1)
	{
		
		$tmpl = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "msg_template where name = 'TPL_SMS_VERIFY_CODE'" );
		$tmpl_content = $tmpl ['content'];
		$verify ['mobile'] = $mobile;
		$verify ['code'] = $code;
		$GLOBALS ['tmpl']->assign ( "verify", $verify );
		$msg = $GLOBALS ['tmpl']->fetch ( "str:" . $tmpl_content );
		$msg_data ['dest'] = $mobile;
		$msg_data ['send_type'] = 0;
		$msg_data ['title'] = addslashes ( $msg );
		$msg_data ['content'] = $msg_data ['title'];
		$msg_data ['send_time'] = 0;
		
		if ($immediately)
		{
			$msg_data ['is_send'] = 1;
		} else
		{
			$msg_data ['is_send'] = 0;
		}
		$msg_data ['create_time'] = TIME_UTC;
		$msg_data ['user_id'] = $user_info ['id'];
		$msg_data ['is_html'] = $tmpl ['is_html'];
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_msg_list", $msg_data ); // 插入
		
		$msg_id = $GLOBALS ['db']->insert_id ();
		
		$re ['msg_id'] = $msg_id;
		
		if ($immediately && $msg_id > 0)
		{
			$result = send_sms_email ( $msg_data );
			
			$re ['status'] = intval ( $result ['status'] );
			$re ['msg'] = trim ( $result ['msg'] );
			
			// 发送结束，更新当前消息状态
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_msg_list set is_success = " . intval ( $result ['status'] ) . ",result='" . $result ['msg'] . "',send_time='" . TIME_UTC . "' where id =" . $msg_id );
		} else
		{
			if ($msg_id == 0)
			{
				$re ['status'] = 0;
			} else
			{
				$re ['status'] = 1;
			}
		}
		
		return $re;
	} else
	{
		return $re;
	}
}
function send_sms_email($msg_item)
{
	$re = array (
			'status' => 0,
			'msg' => '' 
	);
	
	if ($msg_item ['send_type'] == 0)
	{
		// 短信
		require_once APP_ROOT_PATH . "system/utils/es_sms.php";
		$sms = new sms_sender ();
		$result = $sms->sendSms ( $msg_item ['dest'], $msg_item ['content'] );
		// 发送结束，更新当前消息状态
		// $GLOBALS['db']->query("update ".DB_PREFIX."deal_msg_list set
		// is_success =
		// ".intval($result['status']).",result='".$result['msg']."',send_time='".TIME_UTC."'
		// where id =".intval($msg_item['id']));
		
		$re ['status'] = intval ( $result ['status'] );
		$re ['msg'] = $result ['msg'];
	}
	
	if ($msg_item ['send_type'] == 1)
	{
		// 邮件
		require_once APP_ROOT_PATH . "system/utils/es_mail.php";
		$mail = new mail_sender ();
		
		$mail->AddAddress ( $msg_item ['dest'] );
		$mail->IsHTML ( $msg_item ['is_html'] ); // 设置邮件格式为 HTML
		$mail->Subject = $msg_item ['title']; // 标题
		$mail->Body = $msg_item ['content']; // 内容
		
		$is_success = $mail->Send ();
		$result = $mail->ErrorInfo;
		
		// 发送结束，更新当前消息状态
		// $GLOBALS['db']->query("update ".DB_PREFIX."deal_msg_list set
		// is_success =
		// ".intval($is_success).",result='".$result."',send_time='".TIME_UTC."'
		// where id =".intval($msg_item['id']));
		
		$re ['status'] = intval ( $is_success );
		$re ['msg'] = $result;
	}
	
	return $re;
}
function getInchargeDone($payment_id, $money, $memo)
{
	$status = array (
			'status' => 0,
			'show_err' => '' 
	);
	
	if ($money <= 0)
	{
		$status ['status'] = 0;
		$status ['show_err'] = '请输入正确的充值金额';
		return $status;
	}
	
	$payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where id = " . $payment_id );
	if (! $payment_info)
	{
		$status ['status'] = 0;
		$status ['show_err'] = '请选择支付方式';
		return $status;
	}
	
	$order ['memo'] = $memo;
	
	// 开始生成订单
	$now = TIME_UTC;
	$order ['type'] = 1; // 充值单
	$order ['user_id'] = $GLOBALS ['user_info'] ['id'];
	$order ['create_time'] = $now;
	if ($payment_info ['fee_type'] == 0)
		$order ['total_price'] = $money + $payment_info ['fee_amount'];
	else
		$order ['total_price'] = $money + $payment_info ['fee_amount'] * $money;
	
	$order ['deal_total_price'] = $money;
	$order ['pay_amount'] = 0;
	$order ['pay_status'] = 0;
	$order ['delivery_status'] = 5;
	$order ['order_status'] = 0;
	$order ['payment_id'] = $payment_id;
	if ($payment_info ['fee_type'] == 0)
		$order ['payment_fee'] = $payment_info ['fee_amount'];
	else
		$order ['payment_fee'] = $payment_info ['fee_amount'] * $money;
	if ($order ['memo'] != "")
	{
		$order ['memo'] = $order ['memo'];
	}
	do
	{
		$order ['order_sn'] = to_date ( TIME_UTC, "Ymdhis" ) . rand ( 100, 999 );
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT' );
		$order_id = intval ( $GLOBALS ['db']->insert_id () );
	} while ( $order_id == 0 );
	
	require_once APP_ROOT_PATH . "system/libs/cart.php";
	$payment_notice_id = make_payment_notice ( $order ['total_price'], $order_id, $payment_info ['id'], $order ['memo'] );
	// 创建支付接口的付款单
	
	$status ['payment_info'] = $payment_info;
	$status ['status'] = 1;
	$status ['payment_notice_id'] = $payment_notice_id;
	$status ['order_id'] = $order_id;
	$rs = order_paid ( $order_id );
	if ($rs)
	{
		$status ['pay_status'] = 1;
	} else
	{
		$status ['pay_status'] = 0;
	}
	
	return $status;
}
/**
 * 创建付款单号
 *
 * @param $money 付款金额        	
 * @param $order_id 订单ID        	
 * @param $payment_id 付款方式ID        	
 * @param $memo 付款单备注
 *        	return payment_notice_id 付款单ID
 *        	
 */
function make_payment_notice($money, $order_id, $payment_id, $memo = '')
{
	$notice ['create_time'] = TIME_UTC;
	$notice ['order_id'] = $order_id;
	$notice ['user_id'] = $GLOBALS ['db']->getOne ( "select user_id from " . DB_PREFIX . "deal_order where id = " . $order_id );
	$notice ['payment_id'] = $payment_id;
	$notice ['memo'] = $memo;
	$notice ['money'] = $money;
	do
	{
		$notice ['notice_sn'] = to_date ( TIME_UTC, "Ymdhis" ) . rand ( 10, 99 );
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "payment_notice", $notice, 'INSERT', '', 'SILENT' );
		$notice_id = intval ( $GLOBALS ['db']->insert_id () );
	} while ( $notice_id == 0 );
	return $notice_id;
}
// 同步订单支付状态
function order_paid($order_id)
{
	$order_id = intval ( $order_id );
	$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );
	if ($order ['pay_amount'] >= $order ['total_price'])
	{
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 2 where id =" . $order_id . " and pay_status <> 2" );
		$rs = $GLOBALS ['db']->affected_rows ();
		if ($rs)
		{
			// 支付完成
			order_paid_done ( $order_id );
			$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );
			if ($order ['pay_status'] == 2 && $order ['after_sale'] == 0)
				$result = true;
			else
				$result = false;
		}
	} elseif ($order ['pay_amount'] < $order ['total_price'] && $order ['pay_amount'] != 0)
	{
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 1 where id =" . $order_id );
		$result = false; // 订单未支付成功
	} elseif ($order ['pay_amount'] == 0)
	{
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 0 where id =" . $order_id );
		$result = false; // 订单未支付成功
	}
	return $result;
}

// 订单付款完毕后执行的操作,充值单也在这处理，未实现
function order_paid_done($order_id)
{
	// 处理支付成功后的操作
	/**
	 * 1.
	 * 发货
	 * 2. 超量发货的存到会员中心
	 * 3. 发券
	 * 4. 发放抽奖
	 */
	require_once APP_ROOT_PATH . "system/libs/deal.php";
	$order_id = intval ( $order_id );
	$stock_status = true; // 团购状态
	$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );
	if ($order_info ['type'] == 0)
	{
		// 首先验证所有的规格库存
		$order_goods_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_order_item where order_id = " . $order_id );
		foreach ( $order_goods_list as $k => $v )
		{
			if ($GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "attr_stock where deal_id = " . $v ['deal_id'] . " and locate(attr_str,'" . $v ['attr_str'] . "') > 0" ))
			{
				$sql = "update " . DB_PREFIX . "attr_stock set buy_count = buy_count + " . $v ['number'] . " where deal_id = " . $v ['deal_id'] . " and ((buy_count + " . $v ['number'] . " <= stock_cfg) or stock_cfg = 0 )" . " and locate(attr_str,'" . $v ['attr_str'] . "') > 0 ";
				$GLOBALS ['db']->query ( $sql ); // 增加商品的发货量
				$rs = $GLOBALS ['db']->affected_rows ();
				
				if ($rs)
				{
					$affect_attr_list [] = $v;
				} else
				{
					
					$stock_status = false;
					break;
				}
			}
		}
		
		if ($stock_status)
		{
			$goods_list = $GLOBALS ['db']->getAll ( "select deal_id,sum(number) as num from " . DB_PREFIX . "deal_order_item where order_id = " . $order_id . " group by deal_id" );
			foreach ( $goods_list as $k => $v )
			{
				$sql = "update " . DB_PREFIX . "deal set buy_count = buy_count + " . $v ['num'] . ",user_count = user_count + 1 where id=" . $v ['deal_id'] . " and ((buy_count + " . $v ['num'] . "<= max_bought) or max_bought = 0) " . " and time_status = 1 and buy_status <> 2";
				
				$GLOBALS ['db']->query ( $sql ); // 增加商品的发货量
				$rs = $GLOBALS ['db']->affected_rows ();
				
				if ($rs)
				{
					$affect_list [] = $v; // 记录下更新成功的团购商品，用于回滚
				} else
				{
					// 失败成功，即过期支付，超量支付
					$stock_status = false;
					break;
				}
			}
		}
		
		if ($stock_status)
		{
			// 发货成功，发券
			foreach ( $goods_list as $k => $v )
			{
				// 为相应团购发券
				$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where id = " . intval ( $v ['deal_id'] ) );
				if ($deal_info ['is_coupon'] == 1)
				{
					if ($deal_info ['deal_type'] == 1) // 按单发券
					{
						$deal_order_item_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_order_item where order_id = " . $order_info ['id'] . " and deal_id = " . $v ['deal_id'] );
						foreach ( $deal_order_item_list as $item )
						{
							// for($i=0;$i<$item['number'];$i++) //按单
							// {
							// 需要发券
							/**
							 * 1.
							 * 先从已有团购券中发送
							 * 2. 无有未发送的券，自动发送
							 * 3. 发送状态的is_valid 都是 0, 该状态的激活在syn_deal_status中处理
							 */
							$sql = "update " . DB_PREFIX . "deal_coupon set user_id=" . $order_info ['user_id'] . ",order_id = " . $order_info ['id'] . ",order_deal_id = " . $item ['id'] . " where deal_id = " . $v ['deal_id'] . " and user_id = 0 " . " and is_delete = 0";
							$GLOBALS ['db']->query ( $sql );
							$exist_coupon = $GLOBALS ['db']->affected_rows ();
							if (! $exist_coupon)
							{
								// 未发送成功，即无可发放的预设团购券
								add_coupon ( $v ['deal_id'], $order_info ['user_id'], 0, '', '', 0, 0, $item ['id'], $order_info ['id'] );
							}
							// }
						}
					} else
					{
						$deal_order_item_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_order_item where order_id = " . $order_info ['id'] . " and deal_id = " . $v ['deal_id'] );
						foreach ( $deal_order_item_list as $item )
						{
							for($i = 0; $i < $item ['number']; $i ++) // 按件
							{
								// 需要发券
								/**
								 * 1.
								 * 先从已有团购券中发送
								 * 2. 无有未发送的券，自动发送
								 * 3. 发送状态的is_valid 都是 0,
								 * 该状态的激活在syn_deal_status中处理
								 */
								$sql = "update " . DB_PREFIX . "deal_coupon set user_id=" . $order_info ['user_id'] . ",order_id = " . $order_info ['id'] . ",order_deal_id = " . $item ['id'] . " where deal_id = " . $v ['deal_id'] . " and user_id = 0 " . " and is_delete = 0 limit 1";
								$GLOBALS ['db']->query ( $sql );
								$exist_coupon = $GLOBALS ['db']->affected_rows ();
								if (! $exist_coupon)
								{
									// 未发送成功，即无可发放的预设团购券
									add_coupon ( $v ['deal_id'], $order_info ['user_id'], 0, '', '', 0, 0, $item ['id'], $order_info ['id'] );
								}
							}
						}
					}
				}
				// 发券结束
			}
			// 开始处理返还的积分或现金
			require_once APP_ROOT_PATH . "system/libs/user.php";
			if ($order_info ['return_total_money'] != 0)
			{
				$msg = sprintf ( $GLOBALS ['lang'] ['ORDER_RETURN_MONEY'], $order_info ['order_sn'] );
				modify_account ( array (
						'money' => $order_info ['return_total_money'],
						'score' => 0 
				), $order_info ['user_id'], $msg );
			}
			
			if ($order_info ['return_total_score'] != 0)
			{
				$msg = sprintf ( $GLOBALS ['lang'] ['ORDER_RETURN_SCORE'], $order_info ['order_sn'] );
				modify_account ( array (
						'money' => 0,
						'score' => $order_info ['return_total_score'] 
				), $order_info ['user_id'], $msg );
			}
			
			// 开始处理返利，只创建返利， 发放将与msg_list的自动运行一起执行
			$user_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . $order_info ['user_id'] );
			// 开始查询所购买的列表中支不支持促销
			$is_referrals = 1; // 默认为返利
			foreach ( $goods_list as $k => $v )
			{
				$is_referrals = $GLOBALS ['db']->getOne ( "select is_referral from " . DB_PREFIX . "deal where id = " . $v ['deal_id'] );
				if ($is_referrals == 0)
				{
					break;
				}
			}
			if ($user_info ['referral_count'] < app_conf ( "REFERRAL_LIMIT" ) && $is_referrals == 1)
			{
				// 开始返利给推荐人
				$parent_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . $user_info ['pid'] );
				if ($parent_info)
				{
					if ((app_conf ( "REFERRAL_IP_LIMIT" ) == 1 && $parent_info ['login_ip'] != get_client_ip ()) || app_conf ( "REFERRAL_IP_LIMIT" ) == 0) // IP限制
					{
						if (app_conf ( "INVITE_REFERRALS_TYPE" ) == 0) // 现金返利
						{
							$referral_data ['user_id'] = $parent_info ['id']; // 初返利的会员ID
							$referral_data ['rel_user_id'] = $user_info ['id']; // 被推荐且发生购买的会员ID
							$referral_data ['create_time'] = TIME_UTC;
							$referral_data ['money'] = app_conf ( "INVITE_REFERRALS" );
							$referral_data ['order_id'] = $order_info ['id'];
							$GLOBALS ['db']->autoExecute ( DB_PREFIX . "referrals", $referral_data ); // 插入
						} else
						{
							$referral_data ['user_id'] = $parent_info ['id']; // 初返利的会员ID
							$referral_data ['rel_user_id'] = $user_info ['id']; // 被推荐且发生购买的会员ID
							$referral_data ['create_time'] = TIME_UTC;
							$referral_data ['score'] = app_conf ( "INVITE_REFERRALS" );
							$referral_data ['order_id'] = $order_info ['id'];
							$GLOBALS ['db']->autoExecute ( DB_PREFIX . "referrals", $referral_data ); // 插入
						}
						$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set referral_count = referral_count + 1 where id = " . $user_info ['id'] );
					}
				}
			}
			
			// 超出充值
			if ($order_info ['pay_amount'] > $order_info ['total_price'])
			{
				require_once APP_ROOT_PATH . "system/libs/user.php";
				if ($order_info ['total_price'] < 0)
					$msg = sprintf ( $GLOBALS ['lang'] ['MONEYORDER_INCHARGE'], $order_info ['order_sn'] );
				else
					$msg = sprintf ( $GLOBALS ['lang'] ['OUTOFMONEY_INCHARGE'], $order_info ['order_sn'] );
				$refund_money = $order_info ['pay_amount'] - $order_info ['total_price'];
				
				if ($order_info ['account_money'] > $refund_money)
					$account_money_now = $order_info ['account_money'] - $refund_money;
				else
					$account_money_now = 0;
				$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set account_money = " . $account_money_now . " where id = " . $order_info ['id'] );
				
				if ($order_info ['ecv_money'] > $refund_money)
					$ecv_money_now = $order_info ['ecv_money'] - $refund_money;
				else
					$ecv_money_now = 0;
				$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set ecv_money = " . $ecv_money_now . " where id = " . $order_info ['id'] );
				
				modify_account ( array (
						'money' => ($order_info ['pay_amount'] - $order_info ['total_price']),
						'score' => 0 
				), $order_info ['user_id'], $msg );
			}
			
			// 生成抽奖
			$lottery_list = $GLOBALS ['db']->getAll ( "select d.id as did,doi.number from " . DB_PREFIX . "deal_order_item as doi left join " . DB_PREFIX . "deal_order as do on doi.order_id = do.id left join " . DB_PREFIX . "deal as d on doi.deal_id = d.id where d.is_lottery = 1 and do.id = " . $order_info ['id'] );
			$lottery_user = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . intval ( $order_info ['user_id'] ) );
			
			// 如为首次抽奖，先为推荐人生成抽奖号
			$lottery_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "lottery where user_id = " . intval ( $order_info ['user_id'] ) );
			if ($lottery_count == 0 && $lottery_user ['pid'] != 0)
			{
				$lottery_puser = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . intval ( $lottery_user ['pid'] ) );
				foreach ( $lottery_list as $lottery )
				{
					$k = 0;
					do
					{
						if ($k > 10)
							break;
						$buy_count = $GLOBALS ['db']->getOne ( "select buy_count from " . DB_PREFIX . "deal where id = " . $lottery ['did'] );
						$max_sn = $buy_count - $lottery ['number'] + intval ( $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "lottery where deal_id = " . intval ( $lottery ['did'] ) . " and buyer_id <> 0 " ) );
						// $max_sn = intval($GLOBALS['db']->getOne("select
						// lottery_sn from ".DB_PREFIX."lottery where deal_id =
						// '".$lottery['did']."' order by lottery_sn desc limit
						// 1"));
						$sn = $max_sn + 1;
						$sn = str_pad ( $sn, "6", "0", STR_PAD_LEFT );
						$sql = "insert into " . DB_PREFIX . "lottery (`lottery_sn`,`deal_id`,`user_id`,`mobile`,`create_time`,`buyer_id`) select '" . $sn . "','" . $lottery ['did'] . "'," . $lottery_puser ['id'] . ",'" . $lottery_puser ['lottery_mobile'] . "'," . TIME_UTC . "," . $order_info ['user_id'] . " from dual where not exists( select * from " . DB_PREFIX . "lottery where deal_id = " . $lottery ['did'] . " and lottery_sn = '" . $sn . "')";
						$GLOBALS ['db']->query ( $sql );
						send_lottery_sms ( intval ( $GLOBALS ['db']->insert_id () ) );
						$k ++;
					} while ( intval ( $GLOBALS ['db']->insert_id () ) == 0 );
				}
			}
			
			foreach ( $lottery_list as $lottery )
			{
				for($i = 0; $i < $lottery ['number']; $i ++) // 按购买数量生成抽奖号
				{
					$k = 0;
					do
					{
						if ($k > 10)
							break;
						$buy_count = $GLOBALS ['db']->getOne ( "select buy_count from " . DB_PREFIX . "deal where id = " . $lottery ['did'] );
						$max_sn = $buy_count - $lottery ['number'] + intval ( $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "lottery where deal_id = " . intval ( $lottery ['did'] ) . " and buyer_id <> 0 " ) );
						// $max_sn = intval($GLOBALS['db']->getOne("select
						// lottery_sn from ".DB_PREFIX."lottery where deal_id =
						// '".$lottery['did']."' order by lottery_sn desc limit
						// 1"));
						$sn = $max_sn + $i + 1;
						$sn = str_pad ( $sn, "6", "0", STR_PAD_LEFT );
						$sql = "insert into " . DB_PREFIX . "lottery (`lottery_sn`,`deal_id`,`user_id`,`mobile`,`create_time`,`buyer_id`) select '" . $sn . "','" . $lottery ['did'] . "'," . $order_info ['user_id'] . "," . $lottery_user ['lottery_mobile'] . "," . TIME_UTC . ",0 from dual where not exists( select * from " . DB_PREFIX . "lottery where deal_id = " . $lottery ['did'] . " and lottery_sn = '" . $sn . "')";
						$GLOBALS ['db']->query ( $sql );
						send_lottery_sms ( intval ( $GLOBALS ['db']->insert_id () ) );
						$k ++;
					} while ( intval ( $GLOBALS ['db']->insert_id () ) == 0 );
				}
			}
		} else
		{
			// 开始模拟事务回滚
			foreach ( $affect_attr_list as $k => $v )
			{
				$sql = "update " . DB_PREFIX . "attr_stock set buy_count = buy_count - " . $v ['number'] . " where deal_id = " . $v ['deal_id'] . " and locate(attr_str,'" . $v ['attr_str'] . "') > 0 ";
				
				$GLOBALS ['db']->query ( $sql ); // 回滚已发的货量
			}
			foreach ( $affect_list as $k => $v )
			{
				$sql = "update " . DB_PREFIX . "deal set buy_count = buy_count - " . $v ['num'] . ",user_count = user_count - 1 where id=" . $v ['deal_id'];
				$GLOBALS ['db']->query ( $sql ); // 回滚已发的货量
			}
			
			// 超出充值
			require_once APP_ROOT_PATH . "system/libs/user.php";
			$msg = sprintf ( $GLOBALS ['lang'] ['OUTOFSTOCK_INCHARGE'], $order_info ['order_sn'] );
			modify_account ( array (
					'money' => $order_info ['total_price'],
					'score' => 0 
			), $order_info ['user_id'], $msg );
			
			// 将订单的extra_status 状态更新为2，并自动退款，结单
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set extra_status = 2, after_sale = 1, refund_money = pay_amount, order_status = 1 where id = " . intval ( $order_info ['id'] ) );
			
			// 记录退款的订单日志
			$log ['log_info'] = $msg;
			$log ['log_time'] = TIME_UTC;
			$log ['order_id'] = intval ( $order_info ['id'] );
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_log", $log );
		}
		
		// 同步所有未过期的团购状态
		syn_dealing ();
	} 	// end 普通团购
	else
	{
		// 订单充值
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set order_status = 1 where id = " . $order_info ['id'] ); // 充值单自动结单
		require_once APP_ROOT_PATH . "system/libs/user.php";
		$msg = sprintf ( $GLOBALS ['lang'] ['USER_INCHARGE_DONE'], $order_info ['order_sn'] );
		modify_account ( array (
				'money' => $order_info ['total_price'] - $order_info ['payment_fee'],
				'score' => 0 
		), $order_info ['user_id'], $msg );
	}
}

// 发短信验证码
function send_verify_code_app($mobile, $code, $type = "nomal")
{
	if (app_conf ( "SMS_ON" ) == 1)
	{
		$tmpl = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "msg_template where name = 'TPL_SMS_VERIFY_CODE'" );
		$tmpl_content = $tmpl ['content'];
		$verify ['mobile'] = $mobile;
		$verify ['code'] = $code;
		$GLOBALS ['tmpl']->assign ( "verify", $verify );
		$msg = $GLOBALS ['tmpl']->fetch ( "str:" . $tmpl_content );
		$msg_data ['dest'] = $mobile;
		$msg_data ['send_type'] = 0;
		$msg_data ['title'] = "短信验证码";
		$msg_data ['content'] = addslashes ( $msg );
		$msg_data ['send_time'] = 0;
		$msg_data ['is_send'] = 0;
		$msg_data ['create_time'] = get_gmtime ();
		$msg_data ['user_id'] = $GLOBALS ['user_info'] ['id'];
		$msg_data ['is_html'] = $tmpl ['is_html'];
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_msg_list", $msg_data ); // 插入
		
		$result ['status'] = 1;
		return $result;
	}
}

/**
 * @author 作者 E-mail:309581534@qq.com
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 封装处start
 */

/* 实体处理区域 start--------------------------------------*/
/**
 * 格式处理手机端才可解析
 */
function formateCates($cates = array ())
{
	$cate = array ();
	$i = 0;
	foreach ( $cates as $k => $v )
	{
		$cate [$i] ['sort'] = $k;
		$cate [$i] ['name'] = $v;
		$i ++;
	}
	return $cate;
}
/**
 * 股权deal实体处理
 */
function formateType2DealInfo($deal_info)
{
	if ($deal_info ['type'] != 1)
	{
		return false;
	}
	$deal_info_type2 = array ();
	$deal_info_type2 ['id'] = $deal_info ['id'];
	$deal_info_type2 ['type'] = $deal_info ['type'];
	$deal_info_type2 ['name'] = $deal_info ['name'];
	$deal_info_type2 ['image'] = get_abs_img_root ( $deal_info ['image'] );
	$deal_info_type2 ['invote_money'] = $deal_info ['invote_money'];
	$deal_info_type2 ['invote_money_format'] = format_price ( $deal_info ['invote_money'] );
	$deal_info_type2 ['percent'] = round ( ($deal_info ['invote_money'] / $deal_info ['limit_price']) * 100 );
	$deal_info_type2 ['gen_num'] = $deal_info ['gen_num'];
	$deal_info_type2 ['xun_num'] = $deal_info ['xun_num'];
	$deal_info_type2 ['person'] = $deal_info ['invote_num'];
	$deal_info_type2 ['limit_price'] = $deal_info ['limit_price'];
	$deal_info_type2 ['limit_price_format'] = ($deal_info ['limit_price'] / 10000) . '万';
	$deal_info_type2 ['invote_mini_money'] = $deal_info ['invote_mini_money'];
	$deal_info_type2 ['invote_mini_money_format'] = ($deal_info ['invote_mini_money']/10000) . '万';
	$deal_info_type2 ['percent'] = round ( ($deal_info ['invote_money'] / $deal_info ['limit_price']) * 100 );
	$deal_info_type2 ['is_success'] = $deal_info ['is_success'];
	$deal_info_type2 ['is_effect'] = $deal_info ['is_effect'];
	$deal_info_type2 ['end_time'] = to_date ( $deal_info ['end_time'], 'Y-m-d' );
	$deal_info_type2 ['begin_time'] = to_date ( $deal_info ['begin_time'], 'Y-m-d' );
	$deal_info_type2 ['create_time'] = to_date ( $deal_info ['create_time'], 'Y-m-d' );
	$deal_info_type2 = formateDealInfoType2Status ( $deal_info_type2, $deal_info );
	return $deal_info_type2;
}

/**
 * 股权deal Status判断 0预热中 1已成功 2筹资失败 3长期项目 4融资中
 *          deal_status 1待审核 2预热中3 进行中 4已成功 5已失败 6准备中 7未通过
 */
function formateDealInfoType2Status($deal_info_type2, $deal_info)
{
	if ($deal_info ['type'] != 1)
	{
		return false;
	}
	$now_time = get_gmtime ();
	if ($deal_info ['end_time'] > 0 && $deal_info ['end_time'] > $now_time)
	{
		$deal_info_type2 ['remain_days'] = ceil ( ($deal_info ['end_time'] - $now_time) / (24 * 3600) );
	} elseif ($deal_info ['end_time'] > 0 && $deal_info ['end_time'] <= $now_time)
	{
		$deal_info_type2 ['remain_days'] = 0;
	}
	
	if ($deal_info ['begin_time'] > $now_time)
	{
		$deal_info_type2 ['left_days'] = ceil ( ($deal_info ['begin_time'] - $now_time) / 24 / 3600 );
		$deal_info_type2['left_begin_time'] = ceil($deal_info['begin_time'] - $now_time);
	} else
	{
		$deal_info_type2 ['left_days'] = 0;
		$deal_info_type2['left_begin_time']=0;
	}
	
	if ($deal_info ['begin_time'] > NOW_TIME)
	{
		$equity_status = 0; // 预热中
		$equity_status_expression = '预热中';
		
	} elseif ($deal_info ['end_time'] < NOW_TIME && $deal_info ['end_time'] > 0)
	{
		if ($deal_info_type2 ['percent'] >= 100)
		{
			$equity_status = 1; // 已成功
			$equity_status_expression = '已成功';
		} else
		{
			$equity_status = 2; // 筹资失败
			$equity_status_expression = '筹资失败';
		}
	} else
	{
		if ($deal_info_type2 ['percent'] >= 100)
		{
			$equity_status = 1; // 已成功
			$equity_status_expression = '已成功';
		} elseif ($deal_info ['end_time'] == 0)
		{
			$equity_status = 3; // 长期项目
			$equity_status_expression = '长期项目';
		} else
		{
			$equity_status_expression = '融资中';
			$equity_status = 4; // 融资中
		}
	}
	
	$deal_info_type2 ['equity_status_expression'] = $equity_status_expression;
	$deal_info_type2 ['equity_status'] = $equity_status;
	
	
	if($deal_info['is_effect']==0)
	{
		
		if($deal_info['is_edit'] ==1)
		{
			$deal_info_type2['deal_status_expression']='准备中';
			$deal_info_type2['deal_status']=6;
		}
		else
		{
			$deal_info_type2['deal_status_expression']='待审核';
			$deal_info_type2['deal_status']=1;
		}
	}elseif($deal_info['is_effect']==2)
	{
		$deal_info_type2['deal_status_expression']='未通过';
		$deal_info_type2['deal_status']=7;
	}else if($deal_info['begin_time']>NOW_TIME&&$deal_info['end_time']>NOW_TIME&&$deal_info['is_effect']==1)
	{
		$deal_info_type2['deal_status']=2;
		$deal_info_type2['deal_status_expression']='预热中';
	}else if($deal_info['begin_time']<=NOW_TIME&&$deal_info['end_time']>NOW_TIME&&$deal_info['is_success']==0)
	{
		$deal_info_type2['deal_status']=3;
		$deal_info_type2['deal_status_expression']='进行中';
	}else if($deal_info['end_time']<NOW_TIME&&$deal_info['is_success']==1&&$deal_info['pay_end_time'] >NOW_TIME)
	{
		$deal_info_type2['deal_status']=8;
		$deal_info_type2['deal_status_expression']='支付阶段';
	}
	else if($deal_info['begin_time']<NOW_TIME&&$deal_info['is_success']==1)
	{
		$deal_info_type2['deal_status']=4;
		$deal_info_type2['deal_status_expression']='已成功';
	}
	else if($deal_info['end_time']<NOW_TIME&&$deal_info['is_success']==0)
	{
		$deal_info_type2['deal_status']=5;
		$deal_info_type2['deal_status_expression']='已失败';
	}

	return $deal_info_type2;
}

/**
 * 普通众筹获取只需要数据
 */
function formateType1DealInfo($deal_info)
{
	if ($deal_info ['type'] != 0)
	{
		return false;
	}
	$deal_info_type1 = array ();
	$deal_info_type1 ['id'] = $deal_info ['id'];
	$deal_info_type1 ['type'] = $deal_info ['type'];
	$deal_info_type1 ['name'] = $deal_info ['name'];
	$deal_info_type1 ['image'] = get_abs_img_root ( $deal_info ['image'] );
	$deal_info_type1 ['num_days'] = ceil ( ($deal_info ['end_time'] - $deal_info ['begin_time']) / (24 * 3600) ); // 目标天数
	$deal_info_type1 ['limit_price'] = $deal_info ['limit_price']; // 目标金额
	$deal_info_type1 ['percent'] = round ( (($deal_info ['support_amount'] + $deal_info ['virtual_price']) / $deal_info ['limit_price']) * 100 ); // 已达百分比
	$deal_info_type1 ['total_virtual_price'] = $deal_info ['support_amount'] + $deal_info ['virtual_price']; // 已筹资
	$deal_info_type1 ['total_virtual_price_format'] = ($deal_info ['support_amount'] + $deal_info ['virtual_price']).'元'; // 已筹资
	$deal_info_type1 ['support_amount'] = $deal_info ['support_amount'];
	$deal_info_type1 ['end_time'] = to_date ( $deal_info ['end_time'], 'Y-m-d' );
	$deal_info_type1 ['begin_time'] = to_date ( $deal_info ['begin_time'], 'Y-m-d' );
	$deal_info_type1 ['create_time'] = to_date ( $deal_info ['create_time'], 'Y-m-d' );
	$deal_info_type1 ['person'] = $deal_info ['support_count'] + $deal_info ['virtual_num'];
	$deal_info_type1 ['is_success'] = $deal_info ['is_success'];
	$deal_info_type1 ['is_effect'] = $deal_info ['is_effect'];
	if($deal_info['is_effect'] ==2)
		$deal_info['is_edit']=1;
		
	if($deal_info['is_effect'] ==1)
		$deal_info['is_edit']=0;
		
	$deal_info_type1 ['is_edit'] = $deal_info['is_edit'];
	
	return handleDealType0Status($deal_info,$deal_info_type1);
}
/**
 *处理普通众筹的状态等
 * status值0表示预热中；1表示已成功；2表示筹资失败；3表示筹资中；4表示长期项目 剩余时间remain_days
 * deal_status 1待审核 2进行中 3 已成功 4 已失败 5 未通过 6准备中 7预热中
 */
function handleDealType0Status($deal_info,$deal_info_type1)
{
	if ($deal_info ['type'] != 0)
	{
		return false;
	}
	
	$now_time = get_gmtime ();
	
	if ($deal_info ['end_time'] > 0 && $deal_info ['end_time'] > $now_time)
	{
		$deal_info_type1 ['remain_days'] = ceil ( ($deal_info ['end_time'] - $now_time) / (24 * 3600) );
	} elseif ($deal_info ['end_time'] > 0 && $deal_info ['end_time'] <= $now_time)
	{
		$deal_info_type1 ['remain_days'] = 0;
	}
	
	if ($deal_info ['begin_time'] > $now_time)
	{
		$deal_info_type1 ['left_days'] = ceil ( ($deal_info ['begin_time'] - $now_time) / 24 / 3600 );
		$deal_info_type1['left_begin_time'] = ceil($deal_info['begin_time'] - $now_time);
	} else
	{
		$deal_info_type1 ['left_days'] = 0;
		$deal_info_type1['left_begin_time']=0;
	}
	
	if ($deal_info ['begin_time'] > $now_time)
	{
		$deal_info_type1 ['status'] = '0';
		$deal_info_type1 ['status_expression'] = '预热中';
		
	} elseif ($deal_info ['end_time'] < $now_time && $deal_info ['end_time'] > 0)
	{
		if ($deal_info_type1 ['percent'] >= 100)
		{
			$deal_info_type1 ['status'] = '1';
			$deal_info_type1 ['status_expression'] = '已成功';
		} else
		{
			if ($deal_info_type1 ['percent'] >= 0)
			{
				$deal_info_type1 ['status'] = '2';
				$deal_info_type1 ['status_expression'] = '筹资失败';
			}
		}
	} else
	{
		if ($deal_info ['end_time'] > 0)
		{
			if ($deal_info_type1 ['percent'] >= 100)
			{
				$deal_info_type1 ['status'] = '1';
				$deal_info_type1 ['status_expression'] = '已成功';
			} else
			{
				$deal_info_type1 ['status'] = '3';
				$deal_info_type1 ['status_expression'] = '筹资中';
			}
		} else
		{
			$deal_info_type1 ['status'] = '4';
			$deal_info_type1 ['status_expression'] = '长期项目';
		}
	}
	//========================================
	if($deal_info['is_effect']==0)
	{
		if($deal_info['is_edit'] ==1)
		{
			$deal_info_type1['deal_status']=6;
			$deal_info_type1['deal_status_expression']='准备中';
		}
		else
		{
			$deal_info_type1['deal_status']=1;
			$deal_info_type1['deal_status_expression']='待审核';
		}
	}else if($deal_info['is_effect']==2)
	{
		$deal_info_type1['deal_status']=5;
		$deal_info_type1['deal_status_expression']='未通过';
	}
	else if($deal_info['begin_time'] > NOW_TIME )
	{
		$deal_info_type1['deal_status']=7;
		$deal_info_type1['deal_status_expression']='预热中';
	}
	else if($deal_info['begin_time']<=NOW_TIME&&$deal_info['end_time']>=NOW_TIME && $deal_info['is_success']==0)
	{
		$deal_info_type1['deal_status']=2;
		$deal_info_type1['deal_status_expression']='进行中';
	}else if($deal_info['begin_time']<=NOW_TIME && $deal_info['is_success']==1)
	{
		$deal_info_type1['deal_status']=3;
		$deal_info_type1['deal_status_expression']='已成功';
	}else if($deal_info['is_success']==0&&$deal_info['end_time']<NOW_TIME)
	{
		$deal_info_type1['deal_status']=4;
		$deal_info_type1['deal_status_expression']='已失败';
	}

	return $deal_info_type1;
}

/**
 *处理产品众筹状态
 * deal_status 1待审核 2进行中 3 已成功 4 已失败 5 未通过 6准备中 7预热中
 */
function get_deal_status($deal_info)
{
	$type=intval($type);
	if($deal_info['is_effect']==1)
	{
		if($deal_info['begin_time']>NOW_TIME&&$deal_info['end_time']>NOW_TIME){
			$deal_status['deal_status']=7;
			$deal_status['deal_status_expression']='预热中';
		}
		else if($deal_info['begin_time']<=NOW_TIME&&$deal_info['end_time']>=NOW_TIME && $deal_info['is_success']==0)
		{
			$deal_status['deal_status']=2;
			$deal_status['deal_status_expression']='进行中';
		}else if($deal_info['begin_time']<=NOW_TIME && $deal_info['is_success']==1)
		{
			$deal_status['deal_status']=3;
			$deal_status['deal_status_expression']='已成功';
		}else if($deal_info['is_success']==0&&$deal_info['end_time']<NOW_TIME)
		{
			$deal_status['deal_status']=4;
			$deal_status['deal_status_expression']='已失败';
		}
	}
	elseif($deal_info['is_effect']==0)
	{
		if($deal_info['is_edit'] ==1)
		{
			$deal_status['deal_status']=6;
			$deal_status['deal_status_expression']='准备中';
		}
		else
		{
			$deal_status['deal_status']=1;
			$deal_status['deal_status_expression']='待审核';
		}
	}
	elseif($deal_info['is_effect']==2)
	{
		$deal_status['deal_status']=5;
		$deal_status['deal_status_expression']='未通过';
	}

	return $deal_status;
}

/**
 *处理股权众筹状态
 * deal_status 1待审核 2进行中 3 已成功 4 已失败 5 未通过 6准备中 7预热中
 */
function get_deal_status1($deal_info)
{
	$type=intval($type);
	if($deal_info['is_effect']==1)
	{
		if($deal_info['begin_time']>NOW_TIME&&$deal_info['end_time']>NOW_TIME&&$deal_info['is_effect']==1)
		{
			$deal_status['deal_status']=2;
			$deal_status['deal_status_expression']='预热中';
		}else if($deal_info['begin_time']<=NOW_TIME&&$deal_info['end_time']>NOW_TIME&&$deal_info['is_success']==0)
		{
			$deal_status['deal_status']=3;
			$deal_status['deal_status_expression']='进行中';
		}else if($deal_info['begin_time']<NOW_TIME&&$deal_info['is_success']==1)
		{
			$deal_status['deal_status']=4;
			$deal_status['deal_status_expression']='已成功';
		}else if($deal_info['begin_time']<NOW_TIME&&$deal_info['end_time']<NOW_TIME&&$deal_info['is_effect']==0)
		{
			$deal_status['deal_status']=6;
			$deal_status['deal_status_expression']='未成功';
		}
	}
	elseif($deal_info['is_effect']==0)
	{
		$deal_status['deal_status']=1;
		if($deal_info['is_edit'] ==1)
			$deal_status['deal_status_expression']='准备中';
		else
			$deal_status['deal_status_expression']='待审核';
		
	}elseif($deal_info['is_effect']==2)
	{
		$deal_status['deal_status']=7;
		$deal_status['deal_status_expression']='未通过';
	}
	
	return $deal_status;
}

function mapi_get_deal_list($limit="",$conditions="",$orderby=" sort asc "){
	
	if($limit!=""){
		$limit = " LIMIT ".$limit;
	}
	
	if($orderby!=""){
		$orderby = " ORDER BY ".$orderby;
	}
	
	if(app_conf("INVEST_STATUS")==0)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 ";
	}
	elseif(app_conf("INVEST_STATUS")==1)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 and d.type=0 ";
	}
	elseif(app_conf("INVEST_STATUS")==2)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 and d.type=1 ";
	}
	
	if($conditions!=""){
		$condition.=" ".$conditions;
	}
	
 	$deal_count = $GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."deal as d  where ".$condition);
 
 	if($deal_count > 0){
		$deal_list = $GLOBALS['db']->getAll("select d.* from ".DB_PREFIX."deal  as d   where ".$condition.$orderby.$limit);
	}
	
	return array("rs_count"=>$deal_count,"list"=>$deal_list);
}

/** 获取支付方式并且处理实体 */
function getPayMentList(){
	$payment_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "payment where is_effect = 1 and online_pay=2 order by sort asc " );
	$key=-1;
	foreach ( $payment_list as $k => $v )
	{
		
		$payment_list [$k] ['logo'] = get_abs_img_root ( $v ['logo'] );
		unset ( $payment_list [$k] ['config'] );
		if($v ['class_name']=='Wwxjspay')
		{
			//array_splice($payment_list,$k,1);
			unset($payment_list[$k]);
		
		}
	}
	
     $arr = array();
     foreach($payment_list AS $v)
     {
      $arr[] = $v;
     }

	return $arr;
}

/* 实体处理区域 end--------------------------------------*/

/* 返回output时候使用区域 start-------------------------- */
/**
 * 用户密码提交错误直接输出给客户端
 */
function responseNoLoginParams()
{
	$data ['response_code'] = 0;
	$data ['info'] = "未登录";
	$data ['user_login_status'] = 0;
	return $data;
}
/**
 * 错误时候返回信息
 */
function responseErrorInfo($error)
{
	$result ['response_code'] = 0;
	$result ['info'] = $error;
	return $result;
}
/**
 * 成功时候返回信息 $isReturnLoginStatus 0 不是，1 是
 */
function responseSuccessInfo($successInfo,$isReturnLoginStatus,$page_title)
{
	$result ['response_code'] = 1;

	
	if(!empty($page_title))
	{
	$result ['page_title']=$page_title;
	}
	
	if($isReturnLoginStatus==1)
	{
		$result ['user_login_status'] = 1;
	}
	
	if(!empty($successInfo))
	{
	$result ['info'] = $successInfo;
	}
	return $result;
}
/* 返回output时候使用区域 end-------------------------- */

/* 数据库查询区域 start---------------------------------*/
/**
 * 判断deal表中是否有该id type 0 普通众筹 1股权众筹
 */
function dealIdIsExist($deal_id, $type)
{
	$deal_id_is_exist = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal where id = " . $deal_id . " and type=" . $type );
	return $deal_id_is_exist;
}
/**
 * 判断该用户investment_list中是否有领投或者跟投记录 type 1领投 2跟投
 */
function investorOrFollowedIsExist($deal_id, $user_id, $type)
{
	$investor_is_exist = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "investment_list where deal_id = " . $deal_id . " and user_id= " . $user_id . " and type=" . $type );
	return $investor_is_exist;
}

/** 领投信息查询 */
function getLeaderInfo($deal_id)
{
	$leader_info = $GLOBALS ['db']->getRow ( "select i.*,u.user_name,u.identify_name,u.is_investor,u.identify_business_name,u.user_level from " . DB_PREFIX . "investment_list i LEFT JOIN " . DB_PREFIX . "user as u on u.id=i.user_id where i.deal_id=" . $deal_id . " and i.type=1 and status=1 GROUP BY i.user_id,i.user_id ORDER BY i.user_id DESC" );
	if ($leader_info > 0)
	{
	    $leader_info ['image'] = get_user_avatar_root ( $leader_info ["user_id"], "middle" );
	    $leader_info ['create_time'] = to_date ( $leader_info ['create_time'] );
	    $user_level= load_auto_cache("user_level");
	    $leader_info['user_level_icon']=get_mapi_user_level_icon($user_level,$leader_info ["user_level"]);//等级图片
	}
	return $leader_info;
}

/**
 * 获得用户ID
 */
function getUserID($email, $password)
{
	$user = user_check ( $email, $password );
	$user_id = intval ( $user ['id'] );
	return $user_id;
}
/* 数据库查询区域 end---------------------------------*/

/* 常用验证区域 start---------------------------------*/

/**
 * $is_only 是否必须验证手机唯一性$is_belong_user该手机号是否是用户的绑定手机号
 */
function send_code_function($mobile, $is_only, $email, $pwd,$is_belong_user)
{
	// $is_only = intval ( $_REQUEST ['is_only'] );
	
	// is_only 为1的话，表示不允许手机号重复
	if (app_conf ( "SMS_ON" ) == 0)
	{
		$data ['status'] = 0;
		$data ['info'] = "短信未开启";
		return $data;
	}
	
	if ($mobile == '')
	{
		$data ['status'] = 0;
		$data ['info'] = "请输入你的手机号";
		return $data;
	}
	
	if (! check_mobile ( $mobile ))
	{
		$data ['status'] = 0;
		$data ['info'] = "请填写正确的手机号码";
		return $data;
	}
	
	if ($is_only == 1)
	{
		$condition_1 = " and mobile='" . $mobile . "' ";
		$user = user_check ( $email, $pwd );
	    $user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$condition_1 .= " and id!=" . $user_id;
		}
		if ($GLOBALS ['db']->getOne ( "select count(*) from  " . DB_PREFIX . "user where 1=1 $condition_1 " ) > 0)
		{
			$data ['status'] = 0;
			$data ['info'] = "该手机号已经存在";
			return $data;
		}
		if($is_belong_user==1&&$user_id>0){
			if($user['mobile']!='')
			{
			   if($user['mobile']!=$mobile)
			   {
				$data ['status'] = 0;
				$data ['info'] = "亲!该手机号不是您绑定的手机号码!";
				return $data;
			   }else
			   {
			   	//继续执行
			   }
			}else{
				//继续执行
			}
		}
		
	}
	
	if (! check_ipop_limit ( get_client_ip (), "mobile_verify", 60, 0 ))
	{
		$data ['status'] = 0;
		$data ['info'] = "发送速度太快了";
		return $data;
	}
	
	if ($GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "mobile_verify_code where mobile = '" . $mobile . "' and client_ip='" . get_client_ip () . "' and create_time>=" . (get_gmtime () - 60) . " ORDER BY id DESC" ) > 0)
	{
		$data ['status'] = 0;
		$data ['info'] = "发送速度太快了";
		return $data;
	}
	$n_time = get_gmtime () - 300;
	// 删除超过5分钟的验证码
	$GLOBALS ['db']->query ( "DELETE FROM " . DB_PREFIX . "mobile_verify_code WHERE create_time <=" . $n_time );
	// 开始生成手机验证
	$code = rand ( 100000, 999999 );
	$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mobile_verify_code", array (
			"verify_code" => $code,
			"mobile" => $mobile,
			"create_time" => get_gmtime (),
			"client_ip" => get_client_ip () 
	), "INSERT" );
	
	send_verify_sms ( $mobile, $code );
	$data ['status'] = 1;
	$data ['info'] = "验证码发送成功";
	return $data;
}

//发送邮件验证码 $type 1:表发送注册或初设置邮箱，2表单纯发送验证邮件
function send_email_verify_code($email,$type)
{
	if(app_conf("MAIL_ON")==0)
	{
		$data['status'] = 0;
		$data['info'] = "邮件未开启";
		return($data);		
	}
	if($email == '')
	{
		$data['status'] = 0;
		$data['info'] = "请输入你的邮件";
		return($data);
	}
	
	if($type==1)
	{
		$m_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='".$email."' ");
		if($m_count>0){
			$data['status'] = 0;
			$data['info'] = "你输入的邮件已存在";
			return($data);
		}
		
		if(!check_email($email))
		{
			$data['status'] = 0;
			$data['info'] = "请填写正确的邮件";
			return($data);
		}
	}
	
	if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where email = '".$email."' and client_ip='".get_client_ip()."' and create_time>=".(get_gmtime()-60)." ORDER BY id DESC") > 0)
	{
		$data['status'] = 0;
		$data['info'] = "发送速度太快了";
		return($data);
	}
	$n_time=get_gmtime()-300;
	//删除超过5分钟的验证码
	$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."mobile_verify_code WHERE create_time <=".$n_time);
	//开始生成手机验证
	$code = rand(100000,999999);
	$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_verify_code",array("verify_code"=>$code,"email"=>$email,"create_time"=>get_gmtime(),"client_ip"=>get_client_ip()),"INSERT");

	send_verify_email($email,$code);
	$data['status'] = 1;
	$data['info'] = "验证码发送成功";
	return($data);
}
	
/** 检测当前用户申请的状态 status 1审核通过 2 审核未通过 0未申请认证 3 审核中*/
function detectStateAudit($user)
{
    if(!$user)
    {
	    return false;
    }
	$investor_status = intval ( $user ['investor_status'] );
	$identify_name = $user ['identify_name']; // 法定身份证姓名
	$identify_number = $user ['identify_number']; // 法定身份证号码
	$identify_positive_image = $user ['identify_positive_image']; // 身份证正面
	$identify_nagative_image = $user ['identify_nagative_image']; // 身份证反面

	$identify_business_name = $user ['identify_business_name']; // 机构名称
	$identify_business_licence = $user ['identify_business_licence']; // 营业执照
	$identify_business_code = $user ['identify_business_code']; // 组织机构代码证
	$identify_business_tax = $user ['identify_business_tax']; // 税务登记证

	// $investor_status 0的情况下，那8个字段东都存在说明在在审核机构投资者
	if ($investor_status == 0)
	{
		if ($identify_name != '' && $identify_number != '' && $identify_positive_image != '' && $identify_nagative_image != '' && $identify_business_name != '' && $identify_business_licence != '' && $identify_business_code != '' && $identify_business_tax != '')
		{
			$data ['info'] = '机构投资者资料正在审核';
			$data ['status']=3;
			$data ['status_express'] = '机构投资者资料正在审核';
			$data ['response_code'] = 0;
		} else if ($identify_name != '' && $identify_number != '' && $identify_positive_image != '' && $identify_nagative_image != '' && $identify_business_name == null && $identify_business_licence == null && $identify_business_code == null && $identify_business_tax == null)
		{
			$data ['info'] = '个人投资者资料正在审核';
			$data ['status']=3;
			$data ['status_express'] = '个人投资者资料正在审核';
			$data ['response_code'] = 0;
		} else
		{
			$data ['status']=0;
			$data ['status_express'] = '未审核';
			$data ['response_code'] = 1;
		}
		$data ['investor_status'] = $investor_status;
		return $data;
	} else
	{
		$data ['status']=$investor_status;
		if($data ['status']==1)
		{
		$data ['status_express']="审核通过";
		}
		if($data ['status']==2)
		{
			$data ['status_express']="审核未通过";
		}
		$data ['response_code'] = 1;
		$data ['investor_status'] = $investor_status;
		return $data;
	}
}

/* 常用验证区域 end--------------------------------*/

// 常用工具类区域 start----------------------------/
function replace_mapi($item)
{
	return get_domain () . str_replace ( "/mapi", "", $item );
}

/**
 * 创建attachment目录
 */
function createImageDirectory()
{
	if (! is_dir ( APP_ROOT_PATH . "public/attachment" ))
	{
		@mkdir ( APP_ROOT_PATH . "public/attachment" );
		@chmod ( APP_ROOT_PATH . "public/attachment", 0777 );
	}

	$dir = to_date ( get_gmtime (), "Ym" );
	if (! is_dir ( APP_ROOT_PATH . "public/attachment/" . $dir ))
	{
		@mkdir ( APP_ROOT_PATH . "public/attachment/" . $dir );
		@chmod ( APP_ROOT_PATH . "public/attachment/" . $dir, 0777 );
	}

	$dir = $dir . "/" . to_date ( get_gmtime (), "d" );
	if (! is_dir ( APP_ROOT_PATH . "public/attachment/" . $dir ))
	{
		@mkdir ( APP_ROOT_PATH . "public/attachment/" . $dir );
		@chmod ( APP_ROOT_PATH . "public/attachment/" . $dir, 0777 );
	}

	$dir = $dir . "/" . to_date ( get_gmtime (), "H" );

	if (! is_dir ( APP_ROOT_PATH . "public/attachment/" . $dir ))
	{
		@mkdir ( APP_ROOT_PATH . "public/attachment/" . $dir );
		@chmod ( APP_ROOT_PATH . "public/attachment/" . $dir, 0777 );
	}
	return $dir;
}
// 常用工具类区域 end------------------------------/
/**
 * @author 作者 E-mail:309581534@qq.com
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 封装处end
 */

/**
 * 链接到mapi/html
 */
function url_mapi_html($route="index",$param=array())
{
	$key = md5("URL_MAPI_HTML_KEY_".$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	
	$route_array = explode("#",$route);
	
	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";
	
	//原始模式
	$html_app_root=str_replace('/mapi/html',"",APP_ROOT);
	$html_app_root=str_replace('/mapi',"",APP_ROOT);
	$url = $html_app_root."/mapi/html/index.php";
	if($module!=''||$action!=''||count($param)>0) //有后缀参数
	{
		$url.="?";
	}

	if($module&&$module!='')
	$url .= "ctl=".$module."&";
	if($action&&$action!='')
	$url .= "act=".$action."&";
	if(count($param)>0)
	{
		foreach($param as $k=>$v)
		{
			if($k&&$v)
			$url =$url.$k."=".urlencode($v)."&";
		}
	}
	if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
	$GLOBALS[$key] = $url;
	set_dynamic_cache($key,$url);
	
	return $url;
}

//是否绑定资金托管账户
function is_user_tg_mapi($is_tg,$ips_acct_no,$ips_mer_code){
	if($is_tg&&($ips_acct_no||$ips_mer_code)){
		return 1;
	}else{
		return 0;
	}
}

//是否进行身份认证
function is_user_investor_mapi($is_investor,$investor_status){
	if($is_investor==0){
		return 0; //未进行身份认证
	}else{
		if($is_investor >0 && $investor_status==1)
			return 1; //通过审核
		else
			return 2; //审核中
	}
}
//判断用户是否有权限
//0 表示未登陆 1表示正常 2表示等级不够 3表示没有认证手机 4表示没有身份认证 5表示身份认证审核中 6表示身份认证审核失败
function get_level_access2($user_info,$deal_info){
	if(!$user_info){
		//0 表示未登陆
		if($deal_info['user_level']>0){
			return 0;
		}else{
			return 1;
		}
		
	}
	if($user_info['id']!=$deal_info['user_id']){
	$user_level_array= load_auto_cache("user_level");
	
 	$user_level=intval($user_level_array[$user_info['user_level']]['point']);
	$deal_level=intval($user_level_array[$deal_info['user_level']]['point']);
	
	if($deal_level!=0&&($deal_level>$user_level)){
		// 2表示等级不够
		return 2;
	}
	if($deal_info['type']==0){
		if(!$user_info['mobile']){
			return 3;
		}
 	}elseif($deal_info['type']==1){
		if($user_info['is_investor']==0){
			return 4;
		}elseif($user_info['investor_status']==0){
			return 5;
		}elseif($user_info['investor_status']==2){
			return 6;
		}
	}
	}
	return 1;
}
//判断用户是否有权限
//0 表示未 登陆 1表示正常 2表示等级不够 3表示没有认证手机 4表示没有身份认证 5表示身份认证审核中 6表示身份认证审核失败
function mapi_get_level_access($user_info,$deal_info){
	$return=array();
	$access=get_level_access2($user_info,$deal_info);
	$return['access']=$access;
	if($access ==1)
	{
		$return['access_info']="正常";
	}elseif($access ==2)
	{
		$return['access_info']="等级不够";
	}
	elseif($access ==3)
	{
		$return['access_info']="没有认证手机";
	}
	elseif($access ==4)
	{
		$return['access_info']="没有身份认证";
	}
	elseif($access ==5)
	{
		$return['access_info']="身份认证审核中";
	}
	elseif($access ==6)
	{
		$return['access_info']="身份认证审核失败";
	}else
	{
		$return['access_info'] = '未登陆';//0
	}
	
	return $return;
}
function get_mapi_user_level_icon($user_level,$user_level_id)
{
	$icon_image=$user_level[$user_level_id]['icon'];
	$icon_image=str_replace ( "./public/", get_domain () . APP_ROOT . "/../public/", $icon_image );
	$icon_image=str_replace ( "./app/", get_domain () . APP_ROOT . "/../app/", $icon_image );
	return $icon_image;
}

function get_location_list($type)
{	//$type 0全部 1普通 2股权
	$type=intval($type);
	
	if($type == 1)
		$where = ' and d.type=0';
	elseif($type == 2)
		$where = ' and d.type=1';
	else
		$where ='';
		
	$province_list=$GLOBALS['db']->getAll("select r.id,d.province as name from ".DB_PREFIX."deal as d left join ".DB_PREFIX."region_conf as r on r.name = d.province where d.is_delete=0 and d.is_effect=1 and d.province <> '' ".$where." GROUP BY d.province order by d.sort asc");
	$all_array=array(0 => array('id'=>0,'name'=>'全部地区'));
	$location_list=array_merge($all_array,$province_list);
	
	return $location_list;
}
/**
 * 检测 ios 是否要审核
 * $ios_check_version ios 审核版本 后台填写
 * $ios_pack_version ios 打包的版
 */
function check_ios_is_audit($ios_check_version,$ios_pack_version){
	$status=0;
	if($ios_check_version !='' && $ios_pack_version !='' && $ios_check_version == $ios_pack_version)
	{
		$status=1;
	}
	return $status;
}

/**
 * 按宽度格式化html内容中的图片
 * @param unknown_type $content
 * @param unknown_type $width
 * @param unknown_type $height
 */
function format_html_content_image($content,$width,$height=0)
{
    $res = preg_match_all("/<img.*?src=[\"|\']([^\"|\']*)[\"|\'][^>]*>/i", $content, $matches);
    if($res)
    {
        foreach($matches[0] as $k=>$match)
        {
            $old_path = $matches[1][$k];
            if(preg_match("/\.\/public\//i", $old_path))
            {
            	$origin_path = $matches[1][$k];
                $new_path = get_spec_image($matches[1][$k],$width,$height,0);
                $content = str_replace($match, "<a href='".$origin_path."'><img src='".$new_path."' lazy='true' /></a>", $content);
            }
        }
    }

    return $content;
}
?>