<?php
class project{
	public function check_add()
	{	
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
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
		
		$is_tg=intval(is_tg());//是否有安装第三方托管
		$is_user_tg=is_user_tg_mapi($is_tg,$user['ips_acct_no'],$user['ips_mer_code']);
		$is_user_investor=is_user_investor_mapi($user['is_investor'],$user['investor_status']);
		
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
		
		if($is_tg)
		{
			if($is_user_tg)
			{
				$root['status']=1;
				$root['info']="可以增加";
			}
			else
			{
				$root['status']=2;//去绑定
				$root['info']="您未绑定资金托管账户，无法发起项目，请在电脑上登录网站进行绑定。";
				$app_url = HTML_APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$user_id."&from=app";
				$root['acct_url'] = SITE_DOMAIN.$app_url;//绑定url
			}
			
		}
		else{
			if($is_user_investor ==1)
			{
				$root['status']=1;
				$root['info']="可以增加";
			}
			elseif($is_user_investor ==2){
				$root['status']=0;
				$root['info']="您的实名认证正在审核中，还不能发起项目，请联系网站管理员";
			}else 
			{
				$root['status']=0;
				$root['info']="您未进行身份认证，无法发起项目，请先进行身份认证页面";
			}
		}
		output($root);
	}
	
	public function save_project()
	{
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
		
		$id=intval( $GLOBALS ['request'] ['deal_id'] );//项目id
		if($id >0)
		{
			$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$id." and user_id=".$user_id." and is_edit=1 and is_effect in(0,2)");
			if(!$deal_info)
			{
				$root['status']=0;
				$root['info']="项目不能编辑";
				output($root);
			}
		}
		
		$data=array();
		$data['name'] = strim ( $GLOBALS ['request'] ['name'] ); // 项目标题
		$data['limit_price'] = floatval ( $GLOBALS ['request'] ['limit_price'] ); //筹资金额
		$data['deal_days'] = intval ( $GLOBALS ['request'] ['deal_days'] ); //筹集天数
		$data['cate_id'] = intval ( $GLOBALS ['request'] ['cate_id'] ); //项目分类id
		$data['province']=strim($GLOBALS ['request'] ['province']);//省份
		$data['city']=strim($GLOBALS ['request'] ['city']);//城市
		$data['vedio'] = strim ( $GLOBALS ['request'] ['vedio'] ); //项目视频
		$data['brief'] = btrim ( $GLOBALS ['request'] ['brief'] ); //简要说明
		$data['description'] = btrim ( $GLOBALS ['request'] ['descript'] ); //项目详情
	
		if($data['name'] =='')
		{
			$root['status']=0;
			$root['info']="请填写项目名称";
			output($root);
		}
		if(msubstr($data['name'],0,25)!=$data['name'])
		{			
			$root['status']=0;
			$root['info']="项目名称不超过25个字";
			output($root);
		}
		if($data['limit_price'] <=0)
		{
			$root['status']=0;
			$root['info']="请填写筹资金额";
			output($root);
		}
		if($data['deal_days'] <=0)
		{
			$root['status']=0;
			$root['info']="请填写筹集天数";
			output($root);
		}
		if($data['cate_id'] <=0)
		{
			$root['status']=0;
			$root['info']="请选择分类";
			output($root);
		}
		if($data['province'] =='')
		{
			$root['status']=0;
			$root['info']="请选择省份";
			output($root);
		}
		if($data['city'] =='')
		{
			$root['status']=0;
			$root['info']="请选择城市";
			output($root);
		}
		
		
		if($data['vedio'] !="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
		
		if (isset ( $_FILES ['images'] ))
		{
			$dir = createImageDirectory ();
			
			$images_result = save_image_upload ( $_FILES, "images", "attachment/" . $dir, $whs = array (
					'thumb' => array (
							205,
							160,
							1,
							0 
					) 
			), 0, 1 );
			
			if (intval ( $images_result ['error'] ) != 0)
			{
				$data = responseErrorInfo ( $images_result ['message'] );
				$data['status']=0;
				output ( $data );
			} else
			{
				$images_url = $images_result ['images'] ['url'];
				$data['image']=$images_url;//上传封面
			}
		}
		else{
			
			if(!$id)
			{
				$root['status']=0;
				$root['info']="请上传图片";
				output($root);
			}
			
		}

		$data['user_id'] = $user_id;
		$data['user_name'] = $user['user_name'];
		$data['is_edit']=1;
		$data['is_effect']=0;
		
		if($id>0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","user_id=".$user_id." and id=".$id,"SILENT");
			$root['deal_id']=$id;
			$root['status']=1;
			$root['info']="操作成功";
		}
		else
		{
			$data['create_time'] = get_gmtime();
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$deal_id = intval($GLOBALS['db']->insert_id());
			if($deal_id >0)
			{
				$root['deal_id']=$deal_id;
				$root['status']=1;
				$root['info']="增加成功";
			}else{
				$root['status']=0;
				$root['info']="增加失败";
			}
		}

		
		output($root);
	}
	
	public function submit_project()
	{
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
		$id = intval($_REQUEST['deal_id']);//项目id
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where user_id = ".$user_id." and is_delete = 0 and is_effect = 0 and id= ".$id);
		$deal_item_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item where deal_id = ".$id);
		if(!$deal_info){
			$root['status']=0;
			$root['info']="项目出错了";
			output($root);
		}
		if($deal_item_count==0)
		{
			$root['status']=0;
			$root['info']="请先添加至少一项回报设置";
			output($root);
		}
		
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_edit = 0 where id = ".$id);
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_effect = 0 where id = ".$id);
	
		$root['status']=1;
		$root['info']="提交成功，等待管理员审核！";
	
		output($root);
	}
	
	public function edit()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
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
		$id=intval($GLOBALS ['request'] ['deal_id']);//项目id
		$deal= $GLOBALS['db']->getRow("select id,name,limit_price,deal_days,cate_id,province,city,image,vedio,brief,description from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($user['id'])." and is_edit=1 and is_effect in(0,2)");
		if($deal)
		{
			$deal['image']=get_abs_img_root($deal['image']);
			$deal['content']=$deal['description'];
			$root['status']=1;
		}
		else
		{
			$deal=array();
			$root['status']=0;
			$root['info']="项目不能编辑";
		}
		$root['deal']=$deal;
		output($root);
	}
	
	public function delete()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
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
		$id = intval($GLOBALS ['request']['deal_id']);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."deal where id = ".$id." and is_edit = 1 and user_id = ".intval($user['id']." and is_effect in (0,2) and is_delete = 0"));
		if($GLOBALS['db']->affected_rows()>0)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item_image where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_faq where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_pay_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_support_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_visit_log where deal_id = ".$id);
			
			$root['status']=1;
			$root['info']="删除成功";
		}
		else
		{
			$root['status']=0;
			$root['info']="删除失败";
		}
		
		output($root);
	}
}
?>