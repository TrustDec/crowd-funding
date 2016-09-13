<?php
require APP_ROOT_PATH.'wap/app/shop_lip.php';
class investorModule{
	//申请投资者页面
	public function index(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		$id=$GLOBALS['user_info']['id'];
		$mobile_is_bind=es_cookie::get(md5("mobile_is_bind".$id));
		$GLOBALS['tmpl']->assign("mobile_is_bind",$mobile_is_bind);
		$data=$GLOBALS['db']->getRow("select is_investor,identify_name,identify_number,identify_positive_image,identify_nagative_image,investor_status,identify_business_licence,identify_business_code,identify_business_tax,identify_business_name from ".DB_PREFIX."user where id=".$id);
 		 
 		if(($data['is_investor']==1&&$data['investor_status']==0&&$data['identify_name']!=null&&$data['identify_number']!=null&&$data['identify_positive_image']!=null&&$data['identify_nagative_image']!=null)
			||($data['is_investor']==2&&$data['investor_status']==0&&$data['identify_business_licence']!=null&&$data['identify_business_code']!=null&&$data['identify_business_tax']!=null&&$data['identify_business_name']!=null)||($data['investor_status']==1)
 			||($data['investor_status']==1)){
			app_redirect(url_wap("user#investor_result"));
		}
		//服务条款
		$help_item=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."help where type ='term'");
		$GLOBALS['tmpl']->assign("help_item",$help_item);
		$GLOBALS['tmpl']->assign("help_title","服务条款");
		 
		$GLOBALS['tmpl']->display("investor_index.html");
	}
	public function investor_two(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		$id=$GLOBALS['user_info']['id'];
		$GLOBALS['tmpl']->assign("id",$id);
		if($GLOBALS['user_info']['is_investor']==1){
			$GLOBALS['tmpl']->display("investor_personal.html");
		}
		if($GLOBALS['user_info']['is_investor']==2){
			$GLOBALS['tmpl']->display("investor_agency.html");
		}
	}
	public function investor_success(){
		$GLOBALS['tmpl']->display("investor_success.html");
	}
	
	public function invester_list($from='wap'){
		$GLOBALS['tmpl']->assign('is_pull_to_refresh',1);
		$GLOBALS['tmpl']->assign("page_title","投资人列表");
	
	
		$param = array();//参数集合
		 
		//数据来源参数
		$r = strim($_REQUEST['r']);   //投资人类型
		$param['r'] = $r?$r:'';
		$GLOBALS['tmpl']->assign("p_r",$r);
	
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$GLOBALS['tmpl']->assign("p_loc",$loc);
	
		$city = strim($_REQUEST['city']);  //地区
		$param['city'] = $city;
		$GLOBALS['tmpl']->assign("p_city",$city);
	
		if(intval($_REQUEST['redirect'])==1)
		{
			$param = array();
			if($r!="")
			{
				$param = array_merge($param,array("r"=>$r));
			}
	
			if($loc!="")
			{
				$param = array_merge($param,array("loc"=>$loc));
			}
			if($city!="")
			{
				$param = array_merge($param,array("city"=>$city));
			}
			 
		}
		$city_list = load_dynamic_cache("INDEX_CITY_LIST");
		if(!$city_list)
		{
			$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."user group by province order by create_time desc");
			set_dynamic_cache("INDEX_CITY_LIST",$city_list);
		}
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			unset( $temp_param['city']);
			$temp_param['loc'] = $v['province'];
			if($from=='web'){
				$city_list[$k]['url'] = url("investor#invester_list",$temp_param);
			}elseif($from=='wap'){
				$city_list[$k]['url'] = url_wap("investor#invester_list",$temp_param);
			}
		}
		$GLOBALS['tmpl']->assign("city_list",$city_list);
	
		$next_pid = 0 ;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v){
			$temp_param = $param;
			unset( $temp_param['city']);
			$temp_param['loc'] = $v['name'];
			if($from=='web'){
				$region_lv2[$k]['url'] = url("investor#invester_list",$temp_param);
			}elseif($from=='wap'){
				$region_lv2[$k]['url'] = url_wap("investor#invester_list",$temp_param);
			}
			if($loc == $v['name']){
				$next_pid = $v['id'];
			}
		}
		$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		if($next_pid > 0){
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 3 and `pid`='".$next_pid."' order by py asc");  //二级地址
			foreach($region_lv3 as $k=>$v){
				$temp_param = $param;
				$temp_param['city'] = $v['name'];
				if($from=='web'){
					$region_lv3[$k]['url'] = url("investor#invester_list",$temp_param);
				}elseif($from=='wap'){
					$region_lv3[$k]['url'] = url_wap("investor#invester_list",$temp_param);
				}
			}
			$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
		}
	
		//	print_r($region_lv2);exit;
		$page_size = 20;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
	
	
		$GLOBALS['tmpl']->assign("current_page",$page);
	
		$condition = "is_effect = 1 ";
		if($r!="")
		{
			if($r=="all")
			{
				if(app_conf("AVERAGE_USER_STATUS")==0&&INVEST_TYPE!=1){
					if($r=="all")
					{
						$condition.=" and (is_investor = 1 or is_investor = 2) and investor_status = 1 ";
					}
				}	
				$GLOBALS['tmpl']->assign("page_title","全部");
			}
			if($r=="ordinary_user")
			{
				$condition.=" and is_investor = 0 ";
				$GLOBALS['tmpl']->assign("page_title","普通用户");
			}
			if($r=="invester")
			{
				$condition.=" and is_investor = 1 and investor_status = 1 ";
				$GLOBALS['tmpl']->assign("page_title","投资人");
			}
			if($r=="institutions_invester")
			{
				$condition.=" and is_investor = 2 and investor_status = 1 ";
				$GLOBALS['tmpl']->assign("page_title","机构投资人");
			}
		}
		if($loc!="")
		{
			$condition.=" and (province = '".$loc."') ";
			$GLOBALS['tmpl']->assign("page_title",$loc);
		}
		if($city!="")
		{
			$condition.=" and (province = '".$loc."' and city = '".$city."') ";
			$GLOBALS['tmpl']->assign("page_title",$city);
		}
		/*投资人列表*/
		$invester_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where ".$condition." order by create_time desc limit ".$limit);
		foreach($invester_list as $k=>$v)
		{
			$invester_list[$k]['image'] =get_user_avatar($v["id"],"middle");//用户头像
			
			$invester_list[$k]['user_icon'] =$GLOBALS['user_level'][$v['user_level']]['icon'];//用户等级图标
			
			$invester_list[$k]['cate_name'] =unserialize($v["cate_name"]);//所在行业领域
		}
		$invester_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where ".$condition);
	
		$GLOBALS['tmpl']->assign("invester_count",$invester_count);
	
		require APP_ROOT_PATH.'wap/app/page.php';
		$page = new Page($invester_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign("invester_list",$invester_list);
	
		/*投资人推荐*/
		$new_invester = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where investor_status = 1 and is_effect = 1 and (is_investor = 1 or is_investor = 2) order by create_time desc ");
		foreach($new_invester as $kk=>$vv)
		{
			$new_invester[$kk]['image'] =get_user_avatar($vv["id"],"middle");//用户头像
			$new_invester[$k]['user_icon'] =$GLOBALS['user_level'][$v['user_level']]['icon'];//用户等级图标
			$new_invester[$k]['cate_name'] =unserialize($v["cate_name"]);//所在行业领域
		}
		$GLOBALS['tmpl']->assign("new_invester",$new_invester);
		$GLOBALS['tmpl']->display("invester_list.html");
	}
	
	//领投ajax判断
	public function leader_ajax(){
		$ajax=intval($_REQUEST['leader_ajax']);
		$user_id=intval($GLOBALS['user_info']['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		$result=investor_leader_ajax($user_id,$deal_id,$ajax,$from='wap',$info=$GLOBALS['user_info']);
		ajax_return($result);
		return false;
	}
	
	//跟投ajax判断
	public function ajax_continue_investor($from='wap'){
		if(!$GLOBALS['user_info'])
		{
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=$_REQUEST['deal_id'];//不这样取不到
		$result=investor_continue($user_id,$deal_id,$from='wap',$info=$GLOBALS['user_info']);
		ajax_return($result);
		return false;
	}
	
	//ajax删除“领投”，是未审核的数据
	public function delete_leader_investor(){
		//status 0删除失败  1"领投申请"取消成功
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=intval($_REQUEST['deal_id']);
		if($GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=1 AND status=0")>0){
			$result['status']=1;
			$result['info']="领投申请取消成功,请进行跟投!";
			ajax_return($result);
			return false;
		}else{
			$result['status']=0;
			$result['info']="领投申请取消失败!";
			ajax_return($result);
			return false;
		}
	}

	//申请领投
	public function applicate_leader($from='wap'){
		//查询分类列表
		if(!$GLOBALS['user_info'])
		{
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		$deal_id=intval($_REQUEST['deal_id']);
		$user_id=intval($GLOBALS['user_info']['id']);
		$GLOBALS['tmpl']->assign("page_title","申请领投资格");
		investor_applicate_leader($deal_id,$user_id);
	}
	
	//"跟投"询价表单信息保存
	public function enquiry_save(){
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=intval($_POST['deal_id']);
		$stock_value=floatval($_POST['stock_value']);
		$stock_value=$stock_value*10000;
		$money=floatval($_POST['money']);
		$money=$money*10000;
		$investment_reason=strim($_POST['investment_reason']);
		$funding_to_help=strim($_POST['funding_to_help']);
		$is_partner=intval($_POST['is_partner']);
		//num份数
		$num=intval($_POST['num']);
		$result=investor_enquiry_save($user_id,$deal_id,$stock_value,$money,$investment_reason,$funding_to_help,$is_partner,$num);
		ajax_return($result);
		return false;
	}

	//跟投出资保存
	public function enquiry_money_save(){
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=intval($_POST['deal_id']);
		$is_partner=intval($_POST['is_partner']);
		$money=floatval($_POST['money']);
		$money=$money*10000;
		//num份数
		$num=intval($_POST['num']);
		$result=investor_enquiry_money_save($user_id,$deal_id,$is_partner,$money,$num);
		ajax_return($result);
		return false;
	}
	
	public function enquiry_page(){
		//status:1询价(次数大于0) 3询价(次数小于0) 2不参与询价无条件接受项目最终估值(第一次跟投)
		// 4不参与询价无条件接受项目最终估值(后续跟投追加) 5不可以在追加资金
	
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=intval($_POST['deal_id']);
		$enquiry=intval($_POST['enquiry']);
		$result=investor_enquiry_page($user_id,$deal_id,$enquiry);
		ajax_return($result);
		return false;
	}
	
	//领投(首次、追加)投资金额
	public function save_investment_money($from='wap'){
		if(!$GLOBALS['user_info'])
		{
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		$user_id=intval($_POST['user_id']);
		$deal_id=intval($_POST['deal_id']);
		$money=floatval($_POST['money']);
		$money=$money*10000;
		//num份数
		$num=intval($_POST['num']);
		$is_partner=$_POST['is_partner'];
		$ajax=intval($_POST['ajax']);
		$result=investor_save_money($user_id,$deal_id,$money,$num,$is_partner,$ajax);
		ajax_return($result);
		return false;
	}
	
	//申请领投信息入库
	public function save_applicate_leader($from='wap'){
		if(!$GLOBALS['user_info'])
		{
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		$deal_id=intval($_POST['deal_id']);
		$user_id=$GLOBALS['user_info']['id'];
		$cates=addslashes(serialize($_POST['cates']));
		$investor_id=intval($_POST['investor_id']);
		$invest=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id=".$investor_id);
		$introduce=addslashes($_POST['describe']);
		$data=investor_save_leader($deal_id,$user_id,$cates,$investor_id,$invest,$introduce,$from='wap');
		ajax_return($data);
		return false;
	}
	
	//查看申请领投信息
	public function edit_applicate_leader($from='wap'){
		if(!$GLOBALS['user_info'])
		{
			if($from=='web'){
				app_redirect(url("user#login"));
			}elseif($from=='wap'){
				app_redirect(url_wap("user#login"));
			}
		}
		$data=$GLOBALS['db']->getRow("select i.* from ".DB_PREFIX."investment_list i WHERE i.user_id=".$GLOBALS['user_info']['id']." and i.status=0");
		$data['cate_name_lists']['name']=explode(",",$data['cates_name']);
		$data['cate_name_lists']['id']=explode(",",$data['cates_id']);
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("user_edit_applicate_leader.html");
	}
	
	public function pay_mortgage_money(){
		$user_id=$GLOBALS['user_info']['id'];
		$deal_id=intval($_POST['deal_id']);
		$money=floatval($_POST['money']);
		$GLOBALS['tmpl']->assign('deal_id',$deal_id);
		$GLOBALS['tmpl']->assign('user_id',$user_id);
		$GLOBALS['tmpl']->assign('money',$money);
		$GLOBALS['tmpl']->display("pay_mortgage_money.html");
	}
	public function explain(){
		$GLOBALS['tmpl']->display("investor_explain.html");
	}
 }
?>