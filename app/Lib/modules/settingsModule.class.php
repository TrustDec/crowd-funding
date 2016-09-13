<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class settingsModule extends BaseModule
{
	public function index()
	{	
		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$gz_region=unserialize($GLOBALS['user_info']['gz_region']);
		$GLOBALS['tmpl']->assign("gz_region",$gz_region);
		$GLOBALS['tmpl']->assign("gz_region_count",intval(count($gz_region)));
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$company_create_time =to_date ($GLOBALS['user_info']['company_create_time'], 'Y-m-d' ); 
		$GLOBALS['tmpl']->assign("company_create_time",$company_create_time);
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
		$gz_city_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$GLOBALS['tmpl']->assign("gz_city_lv2",$gz_city_lv2);
		
		$weibo_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("weibo_list",$weibo_list);
		$GLOBALS['tmpl']->assign("page_title",'用户中心');
		$deal_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where is_delete=0 and pid = 0 ");
		$GLOBALS['tmpl']->assign("deal_cate",$deal_cate);
		$GLOBALS['tmpl']->display("settings_index.html");
	}
	
	public function save_index()
	{		
		$ajax = intval($_REQUEST['ajax']);	
		delete_mobile_verify_code();	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
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
		
		showSuccess("资料保存成功",$ajax,url('settings#index'));
 	}
	
	public function password()
	{
               
		$GLOBALS['tmpl']->assign("page_title","最新动态");
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
				app_redirect(url("index"));
			}
		}
		else if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		if(app_conf("USER_VERIFY")==2){
			$GLOBALS['tmpl']->display("settings_mobile_password.html");
		}else{
			$GLOBALS['tmpl']->display("settings_password.html");
		}
		
	}
	public function mobile_password()
	{
                    
		$GLOBALS['tmpl']->assign("page_title","最新动态");
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
				app_redirect(url("index"));
			}
		}
		else if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));		
		$GLOBALS['tmpl']->display("settings_mobile_password.html");
	}
	
	public function save_password()
	{		
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_password",5))
		showErr("提交太频繁",$ajax,"");	
		//$user_old_pwd=strim($_REQUEST['user_old_pwd']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		unset($user_info['user_pwd']);
		if(strlen($user_pwd)<0){
			showErr("请输入新密码",$ajax,"");
		}
//		if( md5($user_old_pwd.$user_info['code'])!= $user_info['user_pwd']){
//			showErr("旧密码输入错误",$ajax,"");
//		}
		if(strlen($user_pwd)<4)
		{
			showErr("密码不能低于四位",$ajax,"");
		}
		if($user_pwd!=$confirm_user_pwd)
		{
			showErr("密码确认失败",$ajax,"");
		}
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		//$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$user_info['user_pwd'] = $user_pwd;
		save_user($user_info,"UPDATE");
		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '' where id = ".intval($GLOBALS['user_info']['id']));
		showSuccess("保存成功",$ajax,url("settings#password"));
 	}
	public function save_mobile_password()
	{		
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_mobile_password",5))
		showErr("提交太频繁",$ajax,"");	
		
		delete_mobile_verify_code();
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$mobile=strim($user_info['mobile']);
		$user_info['verify_coder']=strim($_REQUEST['verify_coder']);
		if($mobile){
			
			$has_code=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile='".$mobile."' and verify_code='".strim($_REQUEST['verify_coder'])."' ");
			if(!$has_code){
				showErr("验证码错误",$ajax,"");
			}
		}else{
			showErr("请绑定手机号",$ajax,"");
		}
		
		if(strlen($user_pwd)<4)
		{
			showErr("密码不能低于四位",$ajax,"");
		}
		if($user_pwd!=$confirm_user_pwd)
		{
			showErr("密码确认失败",$ajax,"");
		}
		
		require_once APP_ROOT_PATH."system/libs/user.php";
		//$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$user_info['user_pwd'] = $user_pwd;
		save_user($user_info,"UPDATE");
		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '' where id = ".intval($GLOBALS['user_info']['id']));
		showSuccess("保存成功",$ajax,url("settings#password"));
 	}

	public function bind()
	{
        
		$GLOBALS['tmpl']->assign("page_title","帐号绑定");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		
		$api_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."api_login where class_name <> 'Weixin' ");
		foreach($api_list as $k=>$v)
		{
			if($GLOBALS['user_info'][strtolower($v['class_name'])."_id"]!='')
			{
				$api_list[$k]['is_bind'] = true;
				$api_list[$k]['weibo_url'] = $GLOBALS['user_info'][strtolower($v['class_name'])."_url"];
			}
			else
			{
				$api_list[$k]['is_bind'] = false;
				require_once APP_ROOT_PATH."system/api_login/".$v['class_name']."_api.php";
				$class_name = $v['class_name']."_api";
				$o = new $class_name($v);
				$api_list[$k]['url'] = $o->get_bind_api_url();
			}
			
		}
		
		
		$GLOBALS['tmpl']->assign("api_list",$api_list);
		$GLOBALS['tmpl']->display("settings_bind.html");
	}
	
	public function unbind()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$class_name = strim($_REQUEST['c']);
		$api_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."api_login where class_name='".$class_name."'");
		if($api_info['is_weibo'] ==1)
		{
			$class_name_update = strtolower($class_name);
			update_user_weibo($GLOBALS['user_info']['id'],$GLOBALS['user_info'][$class_name.'_url'],2); //删除微博		
			$GLOBALS['db']->query("update ".DB_PREFIX."user set ".$class_name_update."_id = '',".$class_name_update."_url = '' where id = ".intval($GLOBALS['user_info']['id']),"SILENT");
		}
		else
		{
			require_once APP_ROOT_PATH."system/api_login/".$class_name."_api.php";
			$class_name = $class_name."_api";
			$o = new $class_name($api_info);
			$o->unset_api();
		}

		app_redirect(url("settings#bind"));
	}
	
	public function consignee()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));

		$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("user_id",intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("consignee_list",$consignee_list);
		$GLOBALS['tmpl']->assign("page_title","收货地址");
		$GLOBALS['tmpl']->display("settings_consignee.html");
	}
	public function set_default_consignee(){
		$data=array('status'=>0,'info'=>'');
		$id=intval($_POST['id']);
		$user_id=intval($_POST['user_id']);
		if(!$id){
			$data['info']="信息错误";
		}else{
			if($GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user_consignee where id=$id")>0){
				$consignee_all['is_default']=0;
				$consignee['is_default']=1;
				$GLOBALS['db']->autoExecute(DB_PREFIX.'user_consignee',$consignee_all,"UPDATE","user_id=".$user_id);//全部设置为0
				$GLOBALS['db']->autoExecute(DB_PREFIX.'user_consignee',$consignee,"UPDATE","id=".$id);//设置对应的为默认
				if($GLOBALS['db']->affected_rows()){
					$data['status']=1;
				}else{
					$data['status']=2;//表示更新数据失败，让用户重新提交
					$data['info']="设置失败,请重新设置";
				}
			}else{
				$data['info']="没有该地址";
			}
		}
		ajax_return($data);
	}
	
	public function add_consignee()
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
			if (!$GLOBALS['tmpl']->is_cached('inc/add_consignee.html', $cache_id))
			{		
				$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
				$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			}			
			$data['html'] = $GLOBALS['tmpl']->display("inc/add_consignee.html",$cache_id,true);			
			$data['status'] = 1;
		}
		ajax_return($data);
	}
	
	public function save_consignee()
	{		
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']))>10)
		{
			showErr("每个会员只能预设10个配送地址",$ajax,"");
		}
		
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
		
		
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_consignee",5))
		showErr("提交太频繁",$ajax,"");	
		
	
		if($id>0)
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data,"UPDATE","id=".$id);
		else
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data);
		
		showSuccess("保存成功",$ajax,get_gopreview());
 	}
	
	public function edit_consignee()
	{

		if(!$GLOBALS['user_info'])
		{
			$data['html'] = $GLOBALS['tmpl']->display("inc/user_login_box.html","",true);			
			$data['status'] = 0;
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
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
			
			showSuccess("",1,get_gopreview());
		}
	}
	
	
	public function bank()
	{
                    
		$GLOBALS['tmpl']->assign("page_title","最新动态");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
	/*	if($GLOBALS['user_info']['ex_real_name']!=""||$GLOBALS['user_info']['ex_account_info']!=""||$GLOBALS['user_info']['ex_contact']!="")
		{
			app_redirect_preview();
		}
	*/	
		$GLOBALS['tmpl']->display("settings_bank.html");
	}
	
	
	public function save_bank()
	{	
		$ajax = intval($_REQUEST['ajax']);		
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		if($GLOBALS['user_info']['ex_qq']!=""&&$GLOBALS['user_info']['ex_account_bank']!=""&&$GLOBALS['user_info']['ex_real_name']!=""&&$GLOBALS['user_info']['ex_account_info']!=""&&$GLOBALS['user_info']['ex_contact']!="")
		{
			showErr("银行帐户信息已经设置过",$ajax,"");	
		}
		
		if(!check_ipop_limit(get_client_ip(),"setting_save_bank",5))
		showErr("提交太频繁",$ajax,"");	
		
		$ex_real_name = strim($_REQUEST['ex_real_name']);
		$ex_account_info = strim($_REQUEST['ex_account_info']);
		$ex_account_bank = strim($_REQUEST['ex_account_bank']);
		$ex_contact = strim($_REQUEST['ex_contact']);
		$ex_qq = strim($_REQUEST['ex_qq']);
		
		if($ex_real_name=="")
		{
			showErr("请填写姓名",$ajax,"");	
		}
		if($ex_account_bank=="")
		{
			showErr("请填写开户银行",$ajax,"");	
		}
		if($ex_account_info=="")
		{
			showErr("请填写银行帐号",$ajax,"");	
		}
		if($ex_contact=="")
		{
			showErr("请填写联系电话",$ajax,"");	
		}
		if(!check_mobile($ex_contact))
		{
			showErr("请填写正确的手机号码",$ajax,"");	
		}
		if($ex_qq=="")
		{
			showErr("请填写联系qq",$ajax,"");	
		}	
		$GLOBALS['db']->query("update ".DB_PREFIX."user set ex_qq = '".$ex_qq."',ex_account_bank = '".$ex_account_bank."',ex_real_name = '".$ex_real_name."',ex_account_info = '".$ex_account_info."',ex_contact = '".$ex_contact."',is_bank = '".'1'."' where id = ".intval($GLOBALS['user_info']['id']));
		
		
		showSuccess("资料保存成功",$ajax,url("settings#bank"));
 	}
	//设置手机号
	public function mobile_change(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
 		if(!$GLOBALS['user_info']['mobile']){
			showErr("您未设置手机,请先设置手机");
 		}
 		$GLOBALS['tmpl']->assign("user",$GLOBALS['user_info']);
 		$step=intval($_REQUEST['step']);
 		if($step==0){
 			es_session::set("mobile_status",0);
 		}elseif($step==1){
  			if(es_session::get("mobile_status")!=1){
 				showErr("请进行第一步先验证手机",0,url("settings#mobile_change"));
  			}
 		}elseif($step==2){
 			if(es_session::get("mobile_status")==1){
 				showErr("请进行第二步先验证手机",0,url("settings#mobile_change",array("step"=>1)));
 			}elseif(es_session::get("mobile_status")==0){
 				showErr("请进行第一步先验证手机",0,url("settings#mobile_change"));
  			}
 			es_session::set("mobile_status",0);
 		}
 		
 		$GLOBALS['tmpl']->assign("step",$step);
		$GLOBALS['tmpl']->display("settings_mobile_change.html");
	}
	public function mobile_change_step(){
		$return=array("status"=>1,'info'=>'','jump'=>'');
		$mobile=strim($_REQUEST["mobile"]);
		$verify=strim($_REQUEST["verify"]);
		$step=intval($_REQUEST['step']);
		delete_mobile_verify_code();
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile = '".$mobile."'  and verify_code='".$verify."'  ORDER BY id DESC");
		if($num<=0){
 			$return['status']=0;
			$return['info']='验证码错误';
		}else{
			if($step==0){
				es_session::set("mobile_status",1);
				$return['jump']=url("settings#mobile_change",array("step"=>1));
			}elseif($step=1){
				$re=$GLOBALS['db']->query("update ".DB_PREFIX."user set mobile='".$mobile."' where id=".$GLOBALS['user_info']['id']);
 				es_session::set("mobile_status",2);
				$return['jump']=url("settings#mobile_change",array("step"=>2));
			}
			
		}
		ajax_return($return);
	}
	
	 
	public function mail_change_verify(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);
		
		$verify_code=strim($_REQUEST['code']);
		if($GLOBALS['user_info']['id']!=$id){
			showErr("账号信息不一致，请重新发送验证申请",0,url("settings#mail_change"));
		}
		$user_info  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
		if(!$user_info)
		{
			showErr("没有该会员");
		}
		$send_time=$user_info['verify_time'];
		if((get_gmtime()-$send_time)>48*3600){
			showErr("该申请已过期，请重新发送验证申请",0,url("settings#mail_change"));
		}
		
		$step=intval($_REQUEST['step']);
		if($step==1){
			if($verify_code!=$user_info['verify']){
			showErr("验证错误，请重新发送验证申请",0,url("settings#mail_change"));
		}
			$step=2;
		}elseif($step==2){
			if($verify_code!=$user_info['verify_setting']){
				showErr("验证错误，请重新发送验证申请",0,url("settings#mail_change"));
			}
			$email=strim(base64_decode($_REQUEST['e']));
 			if(!check_email($email)){
				showErr("邮箱格式错误，请重新发送验证申请",0,url("settings#mail_change"));
			}else{
				$count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='".$email."' ");
				if($count>0){
					showErr("邮箱已存在，请重新发送验证申请",0,url("settings#mail_change"));
				}
				
			}
			$GLOBALS['db']->query("update  ".DB_PREFIX."user set email='".$email."' where id=".$id);
			if($GLOBALS['db']->affected_rows()){
				showSuccess("邮箱修改成功",0,url("settings"));	
			}else{
				showErr("邮箱修改失败,请重新点击邮箱中的链接");
			}
			
		}
		$GLOBALS['tmpl']->assign("step",$step);
		$GLOBALS['tmpl']->display("settings_mail_change.html");
	}
	public function invest_info()
	{	
		 //
		 $GLOBALS['tmpl']->assign("page_title",'安全信息');
		 settings_invest_info('web',$GLOBALS['user_info']);
 	}
 	
 	public function security(){
 		
 		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$method=strim($_REQUEST['method']);
		$method_array=array("setting-username-box","setting-pwd-box","setting-email-box","setting-mobile-box","setting-pass-box","setting-id-box",);
		if(!in_array($method,$method_array)){
			$method='';
		}
		$GLOBALS['tmpl']->assign("page_title","安全信息");
		$GLOBALS['tmpl']->assign("method",$method);
		$payment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,1) and class_name = 'YeepayInvestmentPass' ");
		if($payment_count){
			$requestid = date('YmdHis') . rand(1000000, 9000000);
			$identityid = md5($requestid);
			$userip=get_client_ip();
		}
		$GLOBALS['tmpl']->assign("requestid",$requestid);
		$GLOBALS['tmpl']->assign("identityid",$identityid);
		$GLOBALS['tmpl']->assign("userip",$userip);
		$GLOBALS['tmpl']->assign("payment_count",$payment_count);
		
		$bank_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."bank where is_support_tzt =1");
 		//$bank_list=$bank_list['recommend'];
 		$GLOBALS['tmpl']->assign('bank_list',$bank_list);
 		
 		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		$GLOBALS['tmpl']->display("settings_security.html");
	}
	public function save_pass(){
 		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		//if(!check_ipop_limit(get_client_ip(),"setting_save_password",5))
		//showErr("提交太频繁",$ajax,"");	
		$user_old_pwd=strim($_REQUEST['user_old_pwd']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$confirm_user_pwd = strim($_REQUEST['confirm_user_pwd']);
		$change_pwd=intval($_REQUEST['change_pwd']);
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
 		if(strlen($user_pwd)<=0){
			showErr("请输入新密码",$ajax,"");
		}
		if( md5($user_old_pwd)!= $user_info['user_pwd']&&$change_pwd==1){
			showErr("旧密码输入错误",$ajax,"");
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
		showSuccess("保存成功",$ajax,url("settings#security"));
		
	}
	public function save_username(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("请先登录",$ajax,url("user#login"));
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
			showSuccess("昵称设置成功",$ajax,url("settings#security"));
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
		delete_mobile_verify_code();
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set mobile='".$mobile."' where id=".$GLOBALS['user_info']['id']);
				showSuccess("保存成功",$ajax,url("settings#security"));	
		}
		
		
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
		delete_mobile_verify_code();
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set email='".$email."' where id=".$GLOBALS['user_info']['id']);
				showSuccess("保存成功",$ajax,url("settings#security"));	
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
		delete_mobile_verify_code();
		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set paypassword='".md5($paypassword)."' where id=".$GLOBALS['user_info']['id']);
				showSuccess("保存成功",$ajax,url("settings#security"));	
		}
		
		
	}
	public function bind_investor(){
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}

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
		$payment_count=intval($_REQUEST['payment_count']);
		if($payment_count){
			$requestid=strim($_REQUEST['requestid']);
			$identitytype=2;
			$identityid=strim($_REQUEST['identityid']);
			$cardno=strim($_REQUEST['cardno']);
			$idcardtype=strim($_REQUEST['idcardtype']);
			$phone=strim($_REQUEST['phone']);
			$userip=strim($_REQUEST['userip']);	
			$bank_id = strim($_REQUEST['bank_id']);
 			$otherbank=intval($_REQUEST['otherbank']);
 			$province=strim($_REQUEST['province']);
 			$city=strim($_REQUEST['city']);
 			$bankzone=strim($_REQUEST['bankzone']);
		}
				
		$verify=strim($_REQUEST['verify']);
		if($identify_name==''){
			$result['status']=0;
			$result['info']="身份证姓名不能为空!";
			ajax_return($result);
		}
		if($identify_number==''){
			$result['status']=0;
			$result['info']="身份证号码不能为空!";
			ajax_return($result);
		}
		if(!isCreditNo($identify_number)){
			$result['status']=0;
			$result['info']="请输入正确的身份证号码!";
			ajax_return($result);
		}
		if($identify_positive_image==''&&app_conf('IDENTIFY_POSITIVE')){
			$result['status']=0;
			$result['info']="请上传身份证正面照片！";
			ajax_return($result);
		}
		if($identify_nagative_image==''&&app_conf('IDENTIFY_NAGATIVE')){
			$result['status']=0;
			$result['info']="请上传身份证背面照片！";
			ajax_return($result);
		}
		//判断该实名是否存在
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user where identify_number = '$identify_number' and id<>".$GLOBALS['user_info']['id']) > 0 ){
			$result['status']=0;
			$result['info']="该实名已被其他用户认证，非本人请联系客服";
			ajax_return($result);
		}
		if($is_investor==2){
			$identify_business_name=strim($_REQUEST['identify_business_name']);
			$identify_business_licence=strim($_REQUEST['identify_business_licence']);
			$identify_business_code=strim($_REQUEST['identify_business_code']);
			$identify_business_tax=strim($_REQUEST['identify_business_tax']);
			if($identify_business_name==''){
				$result['status']=0;
				$result['info']="企业名称不能为空!";
				ajax_return($result);
			}
			if($identify_business_licence==''&&app_conf('BUSINESS_LICENCE')){
				$result['status']=0;
				$result['info']="营业执照不能为空!";
				ajax_return($result);
			}
			if($identify_business_code==''&&app_conf('BUSINESS_CODE')){
				$result['status']=0;
				$result['info']="组织机构代码证!";
				ajax_return($result);
			}
			if($identify_business_tax==''&&app_conf('BUSINESS_TAX')){
				$result['status']=0;
				$result['info']="税务登记证!";
				ajax_return($result);
			}
		
		}
		if($payment_count){
			if($cardno==''){
				$result['status']=0;
				$result['info']="银行卡号不能为空!";
				ajax_return($result);
			}
			if($phone==''){
				$result['status']=0;
				$result['info']="银行预留手机号不能为空!";
				ajax_return($result);
			}
			$cart_info=array();
	 		if(empty($bank_id)){
	 			$result['status']=0;
				$result['info']="请选择银行!";
				ajax_return($result);
			}else{
				if($bank_id=='other'){
					if($otherbank==0){
						$result['status']=0;
						$result['info']="请选择银行!";
						ajax_return($result);
					}else{
						$cart_info['bank_id']=$otherbank;
					}
				}else{
					$cart_info['bank_id']=$bank_id;
				}
			}
			if(empty($province)){
				$result['status']=0;
				$result['info']="请选择省份!";
				ajax_return($result);
			}
			$cart_info['region_lv2']=$province;
			
			if(empty($city)){
				$result['status']=0;
				$result['info']="请选择城市!";
				ajax_return($result);
			}
			$cart_info['region_lv3']=$city;
			
			if($bankzone==''){
				$result['status']=0;
				$result['info']="请填写开户行网点";
				ajax_return($result);
			}
			$cart_info['bankzone']=$bankzone;
		}
		
			
		$identityid = strim($_REQUEST['identityid']);
		$identitytype = 2;
		$requestid = strim($_REQUEST['requestid']);
		$cardno = strim($_REQUEST['cardno']);
		$idcardno = $identify_number;
		$username = $identify_name;
		$phone = strim($_REQUEST['phone']);
		$userip = strim($_REQUEST['userip']);
		$user_info['is_investor']=intval($_REQUEST['is_investor']);
		require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass/BindBankCard.php";
		$bindbankcard = BindBankCard($identityid,$identitytype,$requestid,$cardno,$idcardno,$username,$phone,$userip);
		
		if($bindbankcard['codesender']){
			if($bindbankcard['codesender']=='MERCHANT'){
				send_tzt_verify_sms($phone,$bindbankcard['smscode']);
			}
			if($is_investor==1){
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
			$cart_info['bankcard']=$cardno;
			$cart_info['real_name']=$identify_name;
			$cart_info['mobile']=$phone;
			$cart_info['identityid']=$identityid;
			$cart_info['identitytype']=$identitytype;
			$GLOBALS['tmpl']->assign("user_infos",$user_info);
			$GLOBALS['tmpl']->assign("cart_info",$cart_info);
			$GLOBALS['tmpl']->assign("requestid",$requestid);
			
			$result['status']=1;
			$result['html'] = $GLOBALS['tmpl']->fetch("inc/bindbankcard_confirm.html");
			ajax_return($result);
			return false;
		}else{
			
			$result['status']=2;
			$result['info'] = $bindbankcard['error_msg'];
			ajax_return($result);
			return false;
		}	
		
	}
	
	public function binding_investor(){
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
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
		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."user where identify_number = '$identify_number' and id<>".$GLOBALS['user_info']['id']) > 0 ){
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
		delete_mobile_verify_code();
		
		$condition="mobile = '".$GLOBALS['user_info']['mobile']."'  and verify_code='".$verify."' ";
 		$num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where $condition  ORDER BY id DESC");
		if($num<=0){
			showErr("验证码错误",$ajax,"");
		}else{
				$user_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']);
 				unset($user_info['user_pwd']);
 				if($user_info){
 					require_once APP_ROOT_PATH."system/libs/user.php";
 					$user_info['is_investor']=intval($_REQUEST['is_investor']);
 					if($user_info['is_investor']==1){
 						$user_info['identify_business_name']='';
 						$user_info['identify_business_licence']='';
 						$user_info['identify_business_code']='';
 						$user_info['identify_business_tax']='';
 						$user_info['card']=strim($_REQUEST['card']);
 						$user_info['identity_conditions']=intval($_REQUEST['identity_conditions']);
 						$user_info['credit_report']=strim($_REQUEST['credit_report']);
 						$user_info['housing_certificate']=strim($_REQUEST['housing_certificate']);
 					}else{
 						$user_info['identify_business_name']=strim($_REQUEST['identify_business_name']);
 						$user_info['identify_business_licence']=strim($_REQUEST['identify_business_licence']);
 						$user_info['identify_business_code']=strim($_REQUEST['identify_business_code']);
 						$user_info['identify_business_tax']=strim($_REQUEST['identify_business_tax']);
 					}
 					$user_info['identify_name']=strim($_REQUEST['identify_name']);
 					$user_info['identify_number']=strim($_REQUEST['identify_number']);
 					$user_info['identify_positive_image']=strim($_REQUEST['identify_positive_image']);
 					$user_info['identify_nagative_image']=strim($_REQUEST['identify_nagative_image']);
 					$user_info['investor_status']=0;
 					$user_info['investor_send_info']='';
 					$user_info['is_binding']=intval($_REQUEST['is_binding']);
 					$res=save_user($user_info,"UPDATE");
 					showSuccess("保存成功",$ajax,url("settings#security"));	
 				}else{
 					showErr("会员信息不存在",$ajax);
 				}
 				
		}
	}
	//机构成员邀请
	public function invite(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
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
	
	
	//转账操作
	
		public function docheck(){
			
			
			
			
			$user_data = $_POST;
		//转账记录表
		$tr=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']."");
		print_r($tr);exit();
		$GLOBALS['tmpl']->assign("tr",$tr);
		$shop_id=$user_data['times'];//交易单号
		$tr_user=$tr['user_name'];//转账人昵称
		$tr_mobile=$tr['mobile'];//转账人手机号
		$tr_sohu_id=$user_data['coin'];//转账人转游乐币
		$tr_money=$user_data['money'];//转账人收取金额
		//$tr_time=date("Y-m-d H:i:s",time());//提交时间
		$tr_time=time();
		$co_user=$user_data['user_name'];//被转人昵称
		$co_mobile=$user_data['mobile'];//被转人手机号
		$cool_coin=$user_data['coin'];//冻结游乐币  
		$coin_hand=ceil($tr_sohu_id * 0.03);//手续费币
		$co_ssohu_id=$tr_sohu_id - $coin_hand;
		$sql="INSERT INTO ".DB_PREFIX."transferaccounts 						(shop_id,tr_user,tr_mobile,tr_sohu_id,tr_money,tr_time,co_user,co_mobile,cool_coin,coin_hand,co_ssohu_id) VALUES ('$shop_id','$tr_user','$tr_mobile','$tr_sohu_id','$tr_money','$tr_time','$co_user','$co_mobile','$cool_coin','$coin_hand','$co_ssohu_id')";
		
		$data=mysql_query($sql);
		
		$reduce_coin="UPDATE ".DB_PREFIX."user SET sohu_id= sohu_id- ".$tr_sohu_id." WHERE id=".$GLOBALS['user_info']['id']."";
		$sqlreduce_coin=mysql_query($reduce_coin);
		
		
		
		if($data && $sqlreduce_coin){
			
			$this->alerts("转账已提交，等待对方确认","member.php?ctl=uc_center");
			//app_redirect("member.php?ctl=uc_center");
			}else{
			$this->alert("转账失败");
			}
			
		if(!$user_data){
			 app_redirect("404.html"); 
		}
		
		
		
			
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
			showErr("",$ajax,url("user#login"));
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
		if($_REQUEST['job_end_time']===""){
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
			showSuccess("邀请发送成功，等待用户回复！",$ajax,url("settings#invite"));	
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
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."finance_company_team where id = ".$id." and status != 0 and company_id = ".intval($GLOBALS['user_info']['id']));
		if($GLOBALS['db']->affected_rows()>0)
		{
			$data['status'] = 1;
			$data['info']='删除成功！';
			ajax_return($data);
			//showSuccess("删除成功",$ajax,url("settings#invite"));
		}
		else
		{
			$data['status'] = 0;
			$data['info']='删除失败！';
			ajax_return($data);
			//showErr("删除失败",$ajax);
		}
	}
	//安装易宝投资通，实名认证第二步短信验证
	function bindbankcard_confirm(){
		$requestid=strim($_POST['requestid']);
		$validatecode=strim($_POST['validatecode']);
		if($validatecode==''){
			$result['status']=0;
			$result['info']="短信验证码不能为空!";
			ajax_return($result);
		}
		$addbindbankcard=intval($_POST['addbindbankcard']);
		$user_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']);
		unset($user_info['user_pwd']);
		$user_info['is_investor']=intval($_POST['is_investor']);
		$user_info['identify_name']=strim($_POST['identify_name']);
		$user_info['identify_number']=strim($_POST['identify_number']);
		$user_info['identify_positive_image']=strim($_POST['identify_positive_image']);
		$user_info['identify_nagative_image']=strim($_POST['identify_nagative_image']);
		if($user_info['is_investor']==1){
			$user_info['card']=strim($_POST['card']);
			$user_info['identity_conditions']=intval($_POST['identity_conditions']);
			$user_info['credit_report']=strim($_POST['credit_report']);
			$user_info['housing_certificate']=strim($_POST['housing_certificate']);
		}
		if($user_info['is_investor']==2){
			$user_info['identify_business_name']=strim($_POST['identify_business_name']);
			$user_info['identify_business_licence']=strim($_POST['identify_business_licence']);
			$user_info['identify_business_code']=strim($_POST['identify_business_code']);
			$user_info['identify_business_tax']=strim($_POST['identify_business_tax']);
		}
		$data['bankcard']=strim($_POST['bankcard']);
		$data['real_name']=strim($_POST['real_name']);
		$data['mobile']=strim($_POST['mobile']);
		$data['bank_id']=strim($_POST['bank_id']);
		$data['region_lv2']=strim($_POST['region_lv2']);
		$data['region_lv3']=strim($_POST['region_lv3']);
		$data['bankzone']=strim($_POST['bankzone']);
		$data['user_id']=$GLOBALS['user_info']['id'];
		$data['identitytype']=intval($_POST['identitytype']);
		$data['identityid']=strim($_POST['identityid']);

		require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass/BindBankCardConfirm.php";
		$bindbankcard = BindBankCardConfirm($requestid,$validatecode);
		if($bindbankcard['error_msg']){
			$result['status']=0;
			$result['info'] = $bindbankcard['error_msg'];
			ajax_return($result);
			return false;
		}else{
			//			
			if(!$addbindbankcard){
  				$user_info['investor_status']=1;
				$user_info['investor_send_info']='';
				require_once APP_ROOT_PATH."system/libs/user.php";
				$res=save_user($user_info,"UPDATE");
			}
			//银行卡信息

			$data['bank_name']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."bank where id=".$data['bank_id']);
			$data['type']=1;

			$data['bankcode']=$bindbankcard['bankcode'];
			$data['card_last']=$bindbankcard['card_last'];
			$data['card_top']=$bindbankcard['card_top'];
			$re=$GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$data,"INSERT","","SILENT");
			$result['status']=1;
			$result['info'] = "绑卡成功";
			ajax_return($result);
			return false;
		}	
	}
}
?>