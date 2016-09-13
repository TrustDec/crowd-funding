<?php
class settingsModule{
	public function index()
	{	
		$GLOBALS['tmpl']->assign("page_title","最新动态");
		if(!$GLOBALS['user_info']){
			if($_REQUEST['first_post']){
				app_redirect(url_wap("user#login",array('first_post'=>1)));
			}else{
				app_redirect(url_wap("user#login"));
			}
			
		}
		
		$level_name="";
		 if($GLOBALS['user_info']['user_level']){
		 	$level_name=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."user_level where id=".$GLOBALS['user_info']['user_level']);
		 }
		$GLOBALS['tmpl']->assign("level_name",$level_name);
		
		$GLOBALS['tmpl']->assign("page_title",'用户中心');
		
		$GLOBALS['tmpl']->display("settings_index.html");
	}
	//修改资料（展示）
	public function modify()
	{	
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$gz_region=unserialize($GLOBALS['user_info']['gz_region']);
		$GLOBALS['tmpl']->assign("gz_region",$gz_region);
		$GLOBALS['tmpl']->assign("gz_region_count",intval(count($gz_region)));
		$company_create_time =to_date ($GLOBALS['user_info']['company_create_time'], 'Y-m-d' ); 
		$GLOBALS['tmpl']->assign("company_create_time",$company_create_time);
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $GLOBALS['user_info']['province'])
			{
				$region_lv2[$k]['selected'] = 1;
				$region_pid = $region_lv2[$k]['id'];
				break;
			}
		}
		$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		
		
		if($region_pid>0)
		{
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['name'] == $GLOBALS['user_info']['city'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
		}
		$weibo_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".intval($GLOBALS['user_info']['id']));
		//var_dump($weibo_list);
		$GLOBALS['tmpl']->assign("weibo_list",$weibo_list);
		$gz_city_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$GLOBALS['tmpl']->assign("gz_city_lv2",$gz_city_lv2);
		//领域
		$deal_cate= load_auto_cache("deal_cate");
		$GLOBALS['tmpl']->assign("deal_cate",$deal_cate);
		$GLOBALS['tmpl']->assign("page_title","个人资料");
		$deal_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where is_delete=0 and pid = 0 ");
		$GLOBALS['tmpl']->assign("deal_cate",$deal_cate);
		$GLOBALS['tmpl']->display("settings_modify.html");
	}
	//修改资料（保存）
	public function save_modify()
	{
		
		$ajax = intval($_REQUEST['ajax']);		
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_index",5))
		showErr("提交太频繁",$ajax,"");	
		
		require_once APP_ROOT_PATH."system/libs/user.php";


		$user_data = array();
		$user_data['province'] = strim($_REQUEST['province']);
		$user_data['city'] = strim($_REQUEST['city']);
		$user_data['sex'] = intval($_REQUEST['sex']);
		$user_data['intro'] = strim($_REQUEST['intro']);
		$user_data['company'] = strim($_REQUEST['company']);
		$user_data['job'] = strim($_REQUEST['job']);
		if(strim($_REQUEST['mobile'])){
			$user_data['mobile'] = strim($_REQUEST['mobile']);
			$num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where mobile='".$user_data['mobile']."' and id!=".$GLOBALS['user_info']['id']);
			if($num>0){
				showErr("手机已经绑定其他账号,请输入新的手机号",$ajax,"");
			}
			$has_code=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile='".$user_data['mobile']."' and verify_code='".strim($_REQUEST['verify_coder'])."' ");
			if(!$has_code){
				showErr("验证码错误",$ajax,"");
			}
		}
	 	
	 	$user_data['cate_name'] =addslashes(serialize($_POST['cates']));
	 	$user_data['gz_region'] =addslashes(serialize($_POST['gz_region']));
	 	$user_data['concept'] = strim($_REQUEST['concept']);
	 	if($GLOBALS['user_info']['is_investor'] ==2)
	 	{
	 		$user_data['company_english_name'] =strim($_REQUEST['company_english_name']);
	 		$user_data['company_url'] =strim($_REQUEST['company_url']);
	 		$user_data['company_create_time'] =to_timespan(strim($_REQUEST['company_create_time']),'Y-m-d');
		
			if($user_data['company_create_time']==0)
			{
				showErr("请选择机构成立时间",$ajax,"");
			}
	 	}			
	 	if($user_data['concept']=="")
		{
			if($GLOBALS['user_info']['is_investor'] == 2){
				showErr("请填写机构简介",$ajax,"");
			}else{
				showErr("请填写投资理念",$ajax,"");
			}
			
		}
		$user_data['investment_num'] = intval($_REQUEST['investment_num']);
	 	if($user_data['investment_num']<=0)
		{
			showErr("投资项目必须大于0",$ajax,"");
		}
	 	$user_data['investment_begin'] = floatval($_REQUEST['investment_begin']);
	 	if($user_data['investment_begin']<=0)
		{
			showErr("请输入正确的投资金额",$ajax,"");
		}
	 	$user_data['investment_end'] = floatval($_REQUEST['investment_end']);
	 	if($user_data['investment_begin']<=0)
		{
			showErr("请输入正确的投资金额",$ajax,"");
		}
		if($user_data['investment_begin']>=$user_data['investment_end'])
		{
			showErr("请输入正确的投资金额范围",$ajax,"");
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"UPDATE","id=".intval($GLOBALS['user_info']['id']));
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".intval($GLOBALS['user_info']['id']));
		foreach($_REQUEST['weibo_url'] as $k=>$v)
		{
			if($v!="")
			{
				$weibo_data = array();
				$weibo_data['user_id'] = intval($GLOBALS['user_info']['id']);
				$weibo_data['weibo_url'] = strim($v);
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_weibo",$weibo_data);
			}
		}
		
		showSuccess("资料保存成功",$ajax,url_wap('settings#index'));
	}
	
	public function add_consignee()
	{

		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		else
		{
			$GLOBALS['tmpl']->assign("page_title","添加新地址");
			$id = intval($_REQUEST['id']);
 			if($id>0){
				$consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
				if(!$consignee_info){
					app_redirect(url_wap("settings#consignee"));
				}
				$region_pid = 0;
				$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
				foreach($region_lv2 as $k=>$v)
				{
					if($v['name'] == $consignee_info['province'])
					{
						$region_lv2[$k]['selected'] = 1;
						$region_pid = $region_lv2[$k]['id'];
						break;
					}
				}
				$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
				
				
				if($region_pid>0)
				{
					$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
					foreach($region_lv3 as $k=>$v)
					{
						if($v['name'] == $consignee_info['city'])
						{
							$region_lv3[$k]['selected'] = 1;
							break;
						}
					}
					$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
				}
							
				$GLOBALS['tmpl']->assign("consignee_info",$consignee_info);
			}else{
				$GLOBALS['tmpl']->caching = true;
				$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
				if (!$GLOBALS['tmpl']->is_cached('inc/add_consignee.html', $cache_id))
				{		
					$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
					$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
				}
			}
			$deal_item_id = intval($_REQUEST['deal_item_id']);
			$GLOBALS['tmpl']->assign("deal_item_id",$deal_item_id);
			$GLOBALS['tmpl']->assign("pre_page",url_wap("settings#consignee"));		
			$GLOBALS['tmpl']->display("add_consignee.html");			
			//$data['status'] = 1;
		}
		//ajax_return($data);
	}
	
	public function save_consignee()
	{		
		$ajax = intval($_REQUEST['ajax']);
 		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']))>10)
		{
			showErr("每个会员只能预设10个配送地址",$ajax,"");
		}
		$deal_item_id = intval($_REQUEST['deal_item_id']);
		$id = intval($_REQUEST['id']);
		$consignee = strim($_REQUEST['consignee']);
		$province = strim($_REQUEST['province']);
		$city = strim($_REQUEST['city']);
		$address = strim($_REQUEST['address']);
		$zip = strim($_REQUEST['zip']);
		$mobile = strim($_REQUEST['mobile']);
		if($consignee=="")
		{
			showErr("请填写收货人姓名",$ajax,"");	
		}
		if($province=="")
		{
			showErr("请选择省份",$ajax,"");	
		}
		if($city=="")
		{
			showErr("请选择城市",$ajax,"");	
		}
		if($address=="")
		{
			showErr("请填写详细地址",$ajax,"");	
		}
		if(!check_postcode($zip))
		{
			showErr("请填写正确的邮编",$ajax,"");	
		}
		if($mobile=="")
		{
			showErr("请填写收货人手机号码",$ajax,"");	
		}
		if(!check_mobile($mobile))
		{
			showErr("请填写正确的手机号码",$ajax,"");	
		}
		
		$data = array();
		$data['consignee'] = $consignee;
		$data['province'] = $province;
		$data['city'] = $city;
		$data['address'] = $address;
		$data['zip'] = $zip;
		$data['mobile'] = $mobile;
		$data['user_id'] = intval($GLOBALS['user_info']['id']);
		
		
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_consignee",5)){
			showErr("提交太频繁",$ajax,"");exit;
		}
		
		
		if($id>0){
			 $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data,"UPDATE","id=".$id);
 		}
		else{
			 $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data);
			$id = $GLOBALS['db']->insert_id();
		}
		if($deal_item_id>0){
			// echo url_wap("cart#index",array('id'=>$deal_item_id));exit;
			showSuccess("保存成功",$ajax,url_wap("cart#index",array('id'=>$deal_item_id)));
		}else{
			showSuccess("保存成功",$ajax,url_wap("settings#consignee",array('id'=>$id)));
		}
		//$res = save_user($user_data);
	}
	public function password()
	{
		if(intval($_REQUEST['code'])!=0)
		{
			$uid = intval($_REQUEST['id']);
			$code = intval($_REQUEST['code']); 
			$GLOBALS['user_info'] = $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$uid." and password_verify = '".$code."' and is_effect = 1");
			if($user_info)
			{
				es_session::set("user_info",$user_info);
				$GLOBALS['tmpl']->assign("user_info",$user_info);
				
			}
			else
			{
				app_redirect(url_wap("index"));
			}
		}
		else if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		if(app_conf("USER_VERIFY")==2){//问问看是否要做2个
			$GLOBALS['tmpl']->display("settings_mobile_password.html");
		}else{
			$GLOBALS['tmpl']->display("settings_password.html");
		}
		
	}
	public function bind()
	{
        $GLOBALS['tmpl']->assign("page_title","帐号绑定");
		$GLOBALS['tmpl']->display("settings_bind.html");
	}
	public function consignee()
	{
 		$GLOBALS['tmpl']->assign("page_title","收货地址管理");
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));

		$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("user_id",intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("consignee_list",$consignee_list);
		$GLOBALS['tmpl']->assign("page_title","收货地址");
		$GLOBALS['tmpl']->display("settings_consignee.html");
	}
	public function edit_consignee()
	{

		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		else
		{
			$id = intval($_REQUEST['id']);
			$consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
			
			$region_pid = 0;
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			foreach($region_lv2 as $k=>$v)
			{
				if($v['name'] == $consignee_info['province'])
				{
					$region_lv2[$k]['selected'] = 1;
					$region_pid = $region_lv2[$k]['id'];
					break;
				}
			}
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			
			
			if($region_pid>0)
			{
				$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
				foreach($region_lv3 as $k=>$v)
				{
					if($v['name'] == $consignee_info['city'])
					{
						$region_lv3[$k]['selected'] = 1;
						break;
					}
				}
				$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
			}
						
			$GLOBALS['tmpl']->assign("consignee_info",$consignee_info);
			$data['html'] = $GLOBALS['tmpl']->display("inc/add_consignee.html","",true);			
			$data['status'] = 1;
		}
		ajax_return($data);
	}
	public function del_consignee()
	{
		if(!$GLOBALS['user_info'])
		{
			$data['html'] = $GLOBALS['tmpl']->display("inc/user_login_box.html","",true);			
			$data['status'] = 1;
			ajax_return($data);
		}
		else
		{
			$id = intval($_REQUEST['id']);
			$data=array('status'=>1,'info'=>'删除成功','jump'=>url_wap("settings#consignee"));
			$re=$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if(!$re){
				$data['status']=0;
				$data['info']="删除失败";
				$data['jump']="";
			}
			ajax_return($data);
		}
	}
	
	public function bank()
	{
		$GLOBALS['tmpl']->assign("page_title","银行账户");
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$GLOBALS['tmpl']->assign("save_bank_url",url_wap("settings#save_bank"));
		$GLOBALS['tmpl']->display("settings_bank.html");
	}
	
	public function save_bank()
	{
		$ajax = intval($_REQUEST['ajax']);
	
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
	
		$ex_real_name = strim($_REQUEST['ex_real_name']);
		$ex_account_info = strim($_REQUEST['ex_account_info']);
		$ex_account_bank = strim($_REQUEST['ex_account_bank']);
		$ex_contact = strim($_REQUEST['ex_contact']);
		$ex_qq = strim($_REQUEST['ex_qq']);
		
		$data =array();
		if($ex_real_name=="")
		{
		    $data['info']="请填写姓名";
		    ajax_return($data);
		    return false;
		}
		if($ex_account_bank=="")
		{
		    $data['info']="请填写开户银行";
		    ajax_return($data);
		    return false;
		}
		if($ex_account_info=="")
		{
		    $data['info']="请填写银行帐号";
		    ajax_return($data);
		    return false;
		}
		if($ex_contact=="")
		{
		    $data['info']="请填写联系电话";
		    ajax_return($data);
		    return false;
		}
		
		if($ex_qq=="")
		{
		    $data['info']="请填写联系qq";
		    ajax_return($data);
		    return false;
		}
		
		if($GLOBALS['db']->query("update ".DB_PREFIX."user set ex_qq = '".$ex_qq."',ex_account_bank = '".$ex_account_bank."',ex_real_name = '".$ex_real_name."',ex_account_info = '".$ex_account_info."',ex_contact = '".$ex_contact."',is_bank = '".'1'."' where id = ".intval($GLOBALS['user_info']['id'])))
		{
			$data['status'] =1;
		}else{
			$data['status'] =0;
		}
		ajax_return($data);
	}
	
	public function save_password()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
	
		if(!check_ipop_limit(get_client_ip(),"setting_save_password",5))
			showErr("提交太频繁",$ajax,"");
		$user_old_pwd=strim($_REQUEST['user_old_pwd']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$data=array();		
		if(strlen($user_pwd)<0){
			$data['info']="请输入旧密码";
			ajax_return($data);
			return false;
		}
		if( md5($user_old_pwd.$user_info['code'])!= $user_info['user_pwd']){
			$data['info']="旧密码输入错误";
			ajax_return($data);
			return false;
		}
		if(strlen($user_pwd)<4)
		{
			$data['info']="密码不能低于四位";
			ajax_return($data);
			return false;
		}
		if($user_pwd!=$confirm_user_pwd)
		{
			$data['info']="密码确认失败";
			ajax_return($data);
			return false;
		}
	
		require_once APP_ROOT_PATH."system/libs/user.php";
		$user_info['user_pwd'] = $user_pwd;
		save_user($user_info,"UPDATE");
		if(	$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '' where id = ".intval($GLOBALS['user_info']['id']))){
			$data['status']=1;
		}else{
			$data['status']=0;
		}
		ajax_return($data);
	}
	
	public function save_mobile_password()
	{
		//$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		$data=array();
		if(!check_ipop_limit(get_client_ip(),"setting_save_mobile_password",5)){
			$data['info']="提交太频繁";
			ajax_return($data);
			return false;
		}
			//showErr("提交太频繁",$ajax,"");
	
	
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$mobile=strim($user_info['mobile']);
		$user_info['verify_coder']=strim($_REQUEST['verify_coder']);
		if($mobile){
				
			$has_code=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile='".$mobile."' and verify_code='".strim($_REQUEST['verify_coder'])."' ");
			if(!$has_code){
				//showErr("验证码错误",$ajax,"");
				$data['info']="验证码错误";
				ajax_return($data);
				return false;
			}
		}else{
			//showErr("请绑定手机号",$ajax,"");
			$data['info']="请绑定手机号";
			ajax_return($data);
			return false;
		}
	
		if(strlen($user_pwd)<4)
		{
			//showErr("密码不能低于四位",$ajax,"");
			$data['info']="密码不能低于四位";
			ajax_return($data);
			return false;
		}
		if($user_pwd!=$confirm_user_pwd)
		{
			//showErr("密码确认失败",$ajax,"");
			$data['info']="密码确认失败";
			ajax_return($data);
			return false;
		}
	
		require_once APP_ROOT_PATH."system/libs/user.php";
		$user_info['user_pwd'] = $user_pwd;
		
		save_user($user_info,"UPDATE");
		
		if(	$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '' where id = ".intval($GLOBALS['user_info']['id']))){
			$data['status']=1;
		}else{
			$data['status']=0;
		}
		ajax_return($data);
		//showSuccess("保存成功",$ajax,url_wap("settings#index"));
	}
	public function invest_info()
	{	
		
		 settings_invest_info('wap',$GLOBALS['user_info']);
 	}
 	public function security(){
 		
 		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$method=strim($_REQUEST['method']);
		$method_array=array("setting-username-box","setting-pwd-box","setting-email-box","setting-mobile-box","setting-pass-box","setting-id-box",);
		if(!in_array($method,$method_array)){
			$method='';
		}
		$GLOBALS['tmpl']->assign("method",$method);
		$GLOBALS['tmpl']->assign("page_title","安全信息");
	 
		$GLOBALS['tmpl']->display("settings_security.html");
	}
	public function save_username(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("请先登录",$ajax,url_wap("user#login"));
		}
		$user_name=strim($_REQUEST['user_name']);
		if(empty($user_name)){
			showErr("请填写昵称",$ajax);
		}
		$re=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name='$user_name' and id!=".$GLOBALS['user_info']['id']);
		if($re>0){
			showErr("昵称已经存在，请重新填写",$ajax);
		}else{
			$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
			$user_info['user_name'] = $user_name;
			$res=save_user($user_info,"UPDATE");
			showSuccess("昵称设置成功",$ajax,url_wap("settings#security"));
		}
	}
	public function save_pass(){
 		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		//if(!check_ipop_limit(get_client_ip(),"setting_save_password",5))
		//showErr("提交太频繁",$ajax,"");	
		$user_old_pwd=strim($_REQUEST['user_old_pwd']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$change_pwd=intval($_REQUEST['change_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
 		if( md5($user_old_pwd)!= $user_info['user_pwd']&&$change_pwd==1){
			showErr("旧密码输入错误",$ajax,"");
		}
 		if(strlen($user_pwd)<=0){
			showErr("请输入新密码",$ajax,"");
		}
		
		
		if(strlen($user_pwd)<4)
		{
			showErr("新密码不能低于四位",$ajax,"");
		}
		if($user_pwd!=$confirm_user_pwd)
		{
			showErr("新密码确认失败",$ajax,"");
		}
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		
 		$user_info['user_pwd'] = $user_pwd;
  		$user_info['money'] = 100;
		$res=save_user($user_info,"UPDATE");
 		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '' where id = ".intval($GLOBALS['user_info']['id']));
		showSuccess("保存成功",$ajax,url_wap("settings#security"));
		
	}
	public function email_binding(){
		$ajax = intval($_REQUEST['ajax']);
		$email=strim($_REQUEST["email"]);
		$verify=strim($_REQUEST["verify_coder"]);
		$step=intval($_REQUEST["step"]);
		if(strlen($verify)< 0 || strlen($verify)== 0){
			showErr("请输入邮件验证号码",$ajax,"");
		}
		if($step==2){
 			if($email==$GLOBALS['user_info']['email']){
 				showErr("新邮箱和旧邮箱一样，请重新输入",$ajax,"");
 			}
		} 
		check_registor_email($email);
		if($step==2){
			$condition="email = '".$GLOBALS['user_info']['email']."'  and verify_code='".$verify."' ";
		}else{
			$condition="email = '".$email."'  and verify_code='".$verify."' ";
		}
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set email='".$email."' where id=".$GLOBALS['user_info']['id']);
			showSuccess("保存成功",$ajax,url_wap("settings#security"));	
		}
		
	}
	public function mobile_binding(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		$return=array("status"=>1,'info'=>'','jump'=>'');
		$mobile=strim($_REQUEST["mobile"]);
		$verify=strim($_REQUEST["verify_coder"]);
		$bind_mobile=intval($_REQUEST["bind_mobile"]);
 		if(strlen($verify)< 0 || strlen($verify)== 0){
			showErr("请输入手机验证号码",$ajax,"");
		}
		if(!$bind_mobile){
 			if($mobile==$GLOBALS['user_info']['mobile']){
 				showErr("新号码和旧号码一样，请重新输入",$ajax,"");
 			}
		} 
		check_registor_mobile($mobile);
		if(!$bind_mobile){
			$condition="mobile = '".$GLOBALS['user_info']['mobile']."'  and verify_code='".$verify."' ";
		}else{
			$condition="mobile = '".$mobile."'  and verify_code='".$verify."' ";
		}
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set mobile='".$mobile."' where id=".$GLOBALS['user_info']['id']);
			showSuccess("保存成功",$ajax,url_wap("settings#security"));	
		}
	}
	public function paypassword_binding(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		$return=array("status"=>1,'info'=>'','jump'=>'');
 		$paypassword=strim($_REQUEST["paypassword"]);
		$confirm_paypassword=strim($_REQUEST["confirm_pypassword"]);
		$verify=strim($_REQUEST['verify']);
		if($paypassword==''||$confirm_paypassword==''){
			showErr("请输入密码",$ajax,"");
		}
		if($paypassword!=$confirm_paypassword){
			showErr("密码不一致",$ajax,"");
		}
 		$condition="mobile = '".$GLOBALS['user_info']['mobile']."'  and verify_code='".$verify."' ";
		 
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set paypassword='".md5($paypassword)."' where id=".$GLOBALS['user_info']['id']);
			showSuccess("保存成功",$ajax,url_wap("settings#security"));	
		}
	}
	public function binding_investor(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		$return=array("status"=>1,'info'=>'','jump'=>'');
		$is_investor=intval($_REQUEST['is_investor']);
		$identify_name=strim($_REQUEST['identify_name']);
		$identify_number=strim($_REQUEST['identify_number']);
		$identify_positive_image=strim($_REQUEST['identify_positive_image']);
		$identify_nagative_image=strim($_REQUEST['identify_nagative_image']);
		$card=strim($_REQUEST['card']);
		$identity_conditions=intval($_REQUEST['identity_conditions']);
		$credit_report=strim($_REQUEST['credit_report']);
		$housing_certificate=strim($_REQUEST['housing_certificate']);
		//=============================
		
		
		$verify=strim($_REQUEST['verify']);
		if($identify_name==''){
			showErr("身份证姓名不能为空!",$ajax,"");
		}
		if($identify_number==''){
			showErr("身份证号码不能为空!",$ajax,"");
		}
		if(!isCreditNo($identify_number)){
			showErr("请输入正确的身份证号码!",$ajax,"");
		}
		if($identify_positive_image==''&&app_conf('IDENTIFY_POSITIVE')){
			showErr("请上传身份证正面照片！",$ajax,"");
		}
		if($identify_nagative_image==''&&app_conf('IDENTIFY_NAGATIVE')){
			showErr("请上传身份证背面照片！",$ajax,"");
		}
		//判断该实名是否存在
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user where (identify_name = '$identify_name' or identify_number = '$identify_number') and id<>".$GLOBALS['user_info']['id']) > 0 ){
			showErr("该实名已被其他用户认证，非本人请联系客服",$ajax,"");
		}
		if($is_investor==2){
			$identify_business_name=strim($_REQUEST['identify_business_name']);
			$identify_business_licence=strim($_REQUEST['identify_business_licence']);
			$identify_business_code=strim($_REQUEST['identify_business_code']);
			$identify_business_tax=strim($_REQUEST['identify_business_tax']);
			if($identify_business_name==''){
				showErr("企业名称不能为空!",$ajax,"");
			}
			if($identify_business_licence==''&&app_conf('BUSINESS_LICENCE')){
				showErr("营业执照不能为空!",$ajax,"");
			}
			if($identify_business_code==''&&app_conf('BUSINESS_CODE')){
				showErr("组织机构代码证!",$ajax,"");
			}
			if($identify_business_tax==''&&app_conf('BUSINESS_TAX')){
				showErr("税务登记证!",$ajax,"");
			}
		
		}
		
		$condition="mobile = '".$GLOBALS['user_info']['mobile']."'  and verify_code='".$verify."' ";
 		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
				$user_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']);
 				unset($user_info['user_pwd']);
 				if($user_info){
 					require_once APP_ROOT_PATH."system/libs/user.php";
 					$user_info['is_investor']=$is_investor;
 					if($is_investor==1){
 						$user_info['identify_business_name']='';
 						$user_info['identify_business_licence']='';
 						$user_info['identify_business_code']='';
 						$user_info['identify_business_tax']='';
 						$user_info['card']=$card;
 						$user_info['identity_conditions']=$identity_conditions;
 						$user_info['credit_report']=$credit_report;
 						$user_info['housing_certificate']=$housing_certificate;
 					}else{
 						$user_info['identify_business_name']=$identify_business_name;
 						$user_info['identify_business_licence']=$identify_business_licence;
 						$user_info['identify_business_code']=$identify_business_code;
 						$user_info['identify_business_tax']=$identify_business_tax;
 					}
 					$user_info['identify_name']=$identify_name;
 					$user_info['identify_number']=$identify_number;
 					$user_info['identify_positive_image']=$identify_positive_image;
 					$user_info['identify_nagative_image']=$identify_nagative_image;
 					$user_info['investor_status']=0;
 					$user_info['investor_send_info']='';
 					
 					$res=save_user($user_info,"UPDATE");
 					showSuccess("保存成功",$ajax,url_wap("settings#security"));	
 				}else{
 					showErr("会员信息不存在",$ajax);
 				}
 				
		}
	}
	public function setting_id(){
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$method=strim($_REQUEST['method']);
		$method_array=array("setting-username-box","setting-pwd-box","setting-email-box","setting-mobile-box","setting-pass-box","setting-id-box",);
		if(!in_array($method,$method_array)){
			$method='';
		}
		$GLOBALS['tmpl']->assign("method",$method);
		$GLOBALS['tmpl']->assign("page_title","实名认证");
		$GLOBALS['tmpl']->display("inc/setting_id.html");
	}
	//机构成员邀请
	public function invite(){
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$GLOBALS['tmpl']->assign("page_title","机构成员邀请列表");
		$condition='';
		$parameter=array();
		$status=$_REQUEST['status'];
		if($status!=''){
			$condition .= " and fi.status=".$status;
			$parameter[]="status=".$status;
			$GLOBALS['tmpl']->assign('status',$status);
		}
		$page_size = DEAL_COMMENT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;
		$invite_list = $GLOBALS['db']->getAll("select fi.*,u.user_name as user_name from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.user_id = u.id where fi.type = 4 and fi.company_id=".$GLOBALS['user_info']['id'].$condition." limit ".$limit);
		$invite_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.user_id = u.id where fi.type = 4 and fi.company_id=".$GLOBALS['user_info']['id'].$condition);
		$invite_number=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.user_id = u.id where fi.type = 4 and fi.company_id=".$GLOBALS['user_info']['id']);
		$GLOBALS['tmpl']->assign("invite_number",$invite_number);
		$GLOBALS['tmpl']->assign("invite_list",$invite_list);
		$GLOBALS['tmpl']->assign("invite_count",$invite_count);
		require APP_ROOT_PATH.'app/Lib/page.php';
		$parameter_str="&".implode("&",$parameter);
		$page = new Page($invite_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();

		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->display("settings_invite.html");
	}
	public function add_invite()
	{
		if(!$GLOBALS['user_info'])
		{
			$data['html'] = $GLOBALS['tmpl']->display("inc/user_login_box.html","",true);			
			$data['status'] = 0;
		}
		else
		{
			$GLOBALS['tmpl']->caching = true;
			$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
			if (!$GLOBALS['tmpl']->is_cached('inc/add_invite.html', $cache_id))
			{		
			}			
			$data['html'] = $GLOBALS['tmpl']->display("inc/add_invite.html",$cache_id,true);			
			$data['status'] = 1;
		}
		ajax_return($data);
	}
	public function save_invite()
	{		
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		$user_id = intval($_REQUEST['user_id']);
		$user_name = strim($_REQUEST['founder_name']);
		$position = strim($_REQUEST['job']);
		$email = strim($_REQUEST['founder_email']);
		$job_start_time = strtotime($_REQUEST['job_start_time']);
		$job_end_time = strtotime($_REQUEST['job_end_time']);
		$invite_name = strim($_REQUEST['invite_name']);
		if($user_name=="")
		{
			showErr("请填写姓名",$ajax,"");	
		}
		if($job_start_time==""){
			showErr("请填写任职开始时间",$ajax,"");	
		}
		if($job_end_time===""){
			showErr("请填写任职结束时间",$ajax,"");	
		}
		if($job_end_time){
			if($job_end_time<$job_start_time){
				showErr("请输入正确的任职时间段时间范围",$ajax,"");	
			}
		}
		$user_list = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where investor_status = 1 and is_effect=1 and is_investor = 1 and user_name="."'$user_name'");
		if($user_list)
		{
			$data = array();
			$data['user_id'] = $user_id;
			$data['company_id'] = $GLOBALS['user_info']['id'];
			$data['position'] = $position;
			$data['email'] = $email;
			$data['status'] = 0;
			$data['job_start_time'] = $job_start_time;
			$data['job_end_time'] = $job_end_time;
			$data['invite_name'] = $invite_name;
			//查询用户是否接受邀请
			$user_invite = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_team where status !=2 and user_id = ".$user_id." and type = 4 and company_id =".$GLOBALS['user_info']['id']);
			if($user_invite){
				if($user_invite['status'] == 1){
					showErr("该用户已经接受邀请，无需在邀请！",$ajax,"");	
				}
				elseif($user_invite['status'] ==0){
					$data['update_time'] = NOW_TIME;
					$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$data,"UPDATE","id=".$user_invite['id']);
				}
				else
				{
					$data['type'] = 4;
					$data['create_time'] = NOW_TIME;
					$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$data,"INSERT","","SILENT");
				}		
			}
			else{
				$data['type'] = 4;
				$data['create_time'] = NOW_TIME;
				$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$data,"INSERT","","SILENT");
			}
			showSuccess("邀请发送成功，等待用户回复！",$ajax,url_wap("settings#invite"));	
		}
		else{
			showErr("该用户不存在",$ajax,"");	
		}
		
 	}
 	/*
	 * 检索机构成员名字
	 * name 团员的名字，审核通过的投资人
	*/
	function chack_team_name(){
		if($_REQUEST['name']){
			$name=strim($_REQUEST['name']);	
		}
		if($name){
			$team_name = $GLOBALS['db']->getAll("select id,user_name,email,job,head_image from ".DB_PREFIX."user where investor_status = 1 and is_effect=1 and is_investor = 1 and user_name  LIKE '%".$name."%' group by id");
		if($team_name){
			$return['status'] = 1;
			foreach($team_name as $k =>$v){
				$team_name[$k]['image']=get_user_avatar($v['id'], "middle");
			}
			$return['name'] = $team_name;
		}else{
			$return['status'] = 0;
		}
		ajax_return($return);
		}
	}
	function del_invite(){
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."finance_company_team where id = ".$id." and status != 0 and company_id = ".intval($GLOBALS['user_info']['id']));
		if($GLOBALS['db']->affected_rows()>0)
		{
			$data['status'] = 1;
			$data['info']='删除成功！';
			ajax_return($data);
			//showSuccess("删除成功",$ajax,url_wap("settings#invite"));
		}
		else
		{
			$data['status'] = 0;
			$data['info']='删除失败！';
			ajax_return($data);
			//showErr("删除失败",$ajax);
		}
	}
}
?>