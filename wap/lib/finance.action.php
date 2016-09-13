<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雲飞水月(172231343@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'wap/app/shop_lip.php';
require APP_ROOT_PATH.'wap/app/page.php';
class financeModule  
{
	public function __construct(){
		 define("TIME_UTC",get_gmtime());   //当前UTC时间戳
	}
	
	// 创业公司列表
	public function index()
	{	
		//
		$GLOBALS['tmpl']->assign('is_pull_to_refresh',1);
		$now = get_gmtime();
		$param = array();//参数集合
		
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$GLOBALS['tmpl']->assign("p_id",$id);
		
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$GLOBALS['tmpl']->assign("p_loc",$loc);

		$pha = intval($_REQUEST['pha']);  //阶段
		$param['pha'] = $pha;
		$GLOBALS['tmpl']->assign("p_pha",$pha);

		$state = intval($_REQUEST['state']);  //融资状态
		$param['state'] = $state;
		$GLOBALS['tmpl']->assign("p_status",$state);

		//所属行业
		$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
		$cate_list=$nav_cate_array['deal_cate_big'];
		
		$cate_result = array();
		foreach($cate_list as $k=>$v){
			if($v['pid'] == 0){
				$temp_param = $param;
				$cate_result[$k+1]['id'] = $v['id'];
				$cate_result[$k+1]['name'] = $v['name'];
				$temp_param['id'] = $v['id'];
				$cate_result[$k+1]['url'] = url_wap("finance",$temp_param);
			}
		}
		if($param['id']){
			$GLOBALS['tmpl']->assign("p_id_name",$cate_result[$id]['name']);
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		
		$pid = $id;
		//获取父类id
		if($cate_list){
			$pid = $this->get_child($cate_list,$pid);
		}
		//子分类 start
		$cate_ids = array();
		$is_child = false;
		$temp_cate_ids = array();
		if($cate_list){
			$child_cate_result= array();
			foreach($cate_list as $k=>$v)
			{
				if($v['pid'] == $pid){
					if($v['id'] > 0){
						$temp_param = $param;
						$child_cate_result[$v['id']]['id'] = $v['id'];
						$child_cate_result[$v['id']]['name'] = $v['name'];
						$temp_param['id'] = $v['id'];
						$child_cate_result[$v['id']]['url'] = url_wap("finance",$temp_param);
						 if($id==$v['id']){
						 	$is_child = true;
						 }
						
					}
				}
				if($v['pid'] == $pid || $pid==0){
					$temp_cate_ids[] = $v['id'];
				}
			}		
		}
		//假如选择了子类 那么使用子类ID  否则使用 父类和其子类
		if($is_child){
			$cate_ids[] = $id;
		}
		else{
			$cate_ids[] = $pid;
			$cate_ids = array_merge($cate_ids,$temp_cate_ids);
		}
 		$cate_ids=array_filter($cate_ids);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
		//城市
        $city_list = load_dynamic_cache("INDEX_CITY_LIST");
       	if(!$city_list)
		{
			$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."finance_company where status =1 group by province  order by id asc");
		}
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = url_wap("finance",$temp_param);
		}	
		$GLOBALS['tmpl']->assign("city_list",$city_list);
		
		//阶段
        $phase_list = load_dynamic_cache("INDEX_PHASE_LIST"); 
       	if(!$phase_list)
		{
			$phase_list =$this->invest_phase_arr();
		}
		foreach($phase_list as $k=>$v){
			$temp_param = $param;
			$temp_param['pha'] =$v['invest_phase'];
			$phase_list[$k]['url'] = url_wap("finance",$temp_param);
		}
		$GLOBALS['tmpl']->assign("phase_list",$phase_list);
		
		if($param['pha']){
			$GLOBALS['tmpl']->assign("p_pha_name",$phase_list[$pha-1]['phase']);
		}
		//融资状态
		$state_list = array(
				0=>array("name"=>"全部公司"),
				1=>array("name"=>"融资中"),
				2=>array("name"=>"融资完成"),
		);
		foreach($state_list as $k=>$v){
			$temp_param = $param;
			$temp_param['state'] = $k;
			$state_list[$k]['url'] = url_wap("finance",$temp_param);
		}
		$GLOBALS['tmpl']->assign("state_list",$state_list);
		
		if($param['state'] == ''||$param['state'] ==0){
			$GLOBALS['tmpl']->assign("state_name",'全部公司');
		}else{
			$GLOBALS['tmpl']->assign("state_name",$state_list[$state]['name']);
		}
		//分页未添加
		$page_size = $GLOBALS['m_config']['page_size'];
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size	;
		
		$condition = ' 1=1 ';
		if(count($cate_ids)>0)
		{
			if($state ==0||$state ==''){
				$condition.= " and c.cate_id in (".implode(",",$cate_ids).")";
			}else{
				$condition.= " and d.cate_id in (".implode(",",$cate_ids).")";
			}
			$GLOBALS['tmpl']->assign("page_title",$cate_result[$id]['name']);
                        
		}
		if($loc!="")
        {
            $condition.=" and (c.province = '".$loc."' or c.city = '".$loc."') ";
			$GLOBALS['tmpl']->assign("page_title",$loc);            
		}
		if($pha!==""&&$pha!=0)
        {
        	$pha = intval($pha-1);
            $condition.=" and (d.invest_phase = '".$pha."')";
            $JOIN = "left join ".DB_PREFIX."deal  AS d on d.company_id = c.id ";
			$GLOBALS['tmpl']->assign("page_title",$pha);            
		}
		
		$condition0 = " and d.is_delete = 0 and d.is_effect = 1 and d.type=4 ";
		//融资中
		$condition1 = " and ( (d.end_time>".$now." and d.is_success=0) or (d.pay_end_time>".$now." and d.is_success=1)) ";
		$company_id_array1 = array();
		$company_id_array1 = $GLOBALS['db']->getAll("SELECT c.id FROM `".DB_PREFIX."finance_company` as c LEFT JOIN ".DB_PREFIX."deal  as d on  c.id = d.company_id  WHERE 1=1 ".$condition0.$condition1);
		if(count($company_id_array1)>1){
			$company_array1 = array();
			foreach($company_id_array1 as $k =>$v){
				if(!in_array($v['id'],$company_array1)){
					$company_array1[] = $v['id'];
				}
				$company_str1 = implode(',',$company_array1);
			}
		}
		
		$result1['rs_count'] = count($company_id_array1);
		$GLOBALS['tmpl']->assign("finance_count1",intval($result1['rs_count']));
		//融资完成
		$condition2 = " and ( (d.end_time<".$now." and d.is_success=0) or (d.pay_end_time<=".$now." and d.is_success=1)) and c.company_id NOT IN (".$company_str1.") ";
		$result2['rs_count'] = $GLOBALS['db']->getOne("SELECT count(DISTINCT(c.id)) FROM `".DB_PREFIX."finance_company` as c LEFT JOIN ".DB_PREFIX."deal  as d on  c.id = d.company_id  WHERE ".$condition0.$condition2);
		$GLOBALS['tmpl']->assign("finance_count2",intval($result2['rs_count']));
		//全部公司
		$result3['rs_count'] = $GLOBALS['db']->getOne("SELECT count(DISTINCT(c.id)) FROM `".DB_PREFIX."finance_company` as c WHERE c.status =1 ");
		$GLOBALS['tmpl']->assign("finance_count3",intval($result3['rs_count']));
		if($state ==1){//融资中
			$condition .=$condition0.$condition1;
			$result =$this->get_c_deal_list(1,$limit,$condition);
		}elseif($state ==2){//融资完成
			$condition.=$condition0.$condition2;
			$result =$this->get_c_deal_list(2,$limit,$condition);
		}elseif($state ==0||$state ==''){//全部公司
			$condition =$condition." and c.status = 1 ";
			$result =$this->get_c_deal_list(3,$limit,$condition);
			foreach($result['list'] as $k =>$v){
				$result['list'][$k]['company_id'] = $v['id'];
				//融资阶段			
				$phase_id = $GLOBALS['db']->getOne("SELECT invest_phase FROM `".DB_PREFIX."deal` WHERE company_id = ".$v['id'] ." and is_delete = 0 and is_effect = 1 and type=4  order by id desc");
				if($phase_id != ''){
					$result['list'][$k]['phase'] = get_invest_phase($phase_id);
				}else{
					$result['list'][$k]['phase'] = "--";
				}
			}
		}
		$GLOBALS['tmpl']->assign("finance_list",$result['list']);
		$GLOBALS['tmpl']->assign("finance_count",$result['rs_count']);	
		//
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		echo "<!--";
		print_r($p);
		echo "-->";
			
		$GLOBALS['tmpl']->assign("page_title","创业公司");
		$GLOBALS['tmpl']->display("finance/company_deals_list.html");
	}

	// 公司详细
	public function company_show(){
		//
 		$now = get_gmtime();
 		$GLOBALS['tmpl']->assign("now",$now);
 		//获取项目的ID
		$company_id = intval($_REQUEST['cid']);
	    $company_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id= ".$company_id);
 		if(!$company_info){
			showErr("该公司不存在");
		}else{
			$company_info['introduce_image'] = unserialize($company_info['company_introduce_image']);
			//子产品介绍
			$company_sub_product = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_sub_product where company_id=".$company_id);
			foreach($company_sub_product as $k=> $v){
				$company_info['sub_product'][$k]['id'] = $v['id'];
				$company_info['sub_product'][$k]['product_name'] = $v['product_name'];
				$company_info['sub_product'][$k]['product_website'] = $v['product_website'];
			}
			//创始团队
			$company_team= $GLOBALS['db']->getAll("select ct.* from ".DB_PREFIX."finance_company_team as ct  where  ct.company_id =".$company_id." and ct.type = 0 and ct.`status` = 1 group by ct.id");
			foreach($company_team as $k=> $v){
				$company_info['company_team'][$k]['is_manager'] = $v['is_manager'];
				$company_info['company_team'][$k]['id'] = $v['user_id'];
				$company_info['company_team'][$k]['user_id'] = $v['user_id'];
				$company_info['company_team'][$k]['name'] = $v['name'];
				if($v['is_manager'] ==1){
					if($v['intro'] == ''){
						$company_info['company_team'][$k]['intro'] = $GLOBALS['db']->getOne("select intro from ".DB_PREFIX."user where id=".$v['user_id']);
					}else{
						$company_info['company_team'][$k]['intro'] = $v['intro'];
					}
				}else{
					$company_info['company_team'][$k]['intro'] = $v['intro'];
				}
				$company_info['company_team'][$k]['image'] = get_user_avatar($v['user_id'], "middle");
				$company_info['company_team'][$k]['level'] = $v['level'];
				$company_info['company_team'][$k]['employee_level'] = $v['employee_level'];
				$company_info['company_team'][$k]['position'] = $v['position'];
				$company_info['company_team'][$k]['status'] = $v['status'];
				$company_info['company_team'][$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
				$company_info['company_team'][$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
			}
			$company_team_sum = count($company_info['company_team']);
			$GLOBALS['tmpl']->assign("company_team_sum",$company_team_sum);
			
			//投资案例
			$company_invest= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id=".$company_id." and type = 0 and status =1 order by invest_phase");
			foreach($company_invest as $k=> $v){
				$company_invest_info = $GLOBALS['db']->getRow("select id,company_name,company_brief,company_logo from ".DB_PREFIX."finance_company where id=".$v['invest_company_id']);
				$invest_id = $v['invest_company_id'];
				$company_invests[$invest_id]['invest_company_id'] = $company_invest_info['id'];
				$company_invests[$invest_id]['company_name'] = $company_invest_info['company_name'];
				$company_invests[$invest_id]['image'] = $company_invest_info['company_logo'];
				$company_invests[$invest_id]['company_brief'] =$company_invest_info['company_brief'];
				$company_invests[$invest_id]['company_id'] =$company_invest_info['id'];
				$company_invests[$invest_id]['exp'][$k]['invest_time'] = to_date($v['invest_time'],"Y-m-d");
				$company_invests[$invest_id]['exp'][$k]['invest_phase'] =$v['invest_phase'];
				$company_invests[$invest_id]['exp'][$k]['finance_amount_unit'] =$v['finance_amount_unit'];
				$company_invests[$invest_id]['exp'][$k]['finance_amount'] =transform_wan($v['finance_amount']);
			}
			$company_invests_sum = count($company_invests);
			$GLOBALS['tmpl']->assign("company_invests_sum",$company_invests_sum);
			$GLOBALS['tmpl']->assign("company_invests",$company_invests);
			//融资经历
			$company_experience= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id=".$company_id." and type = 1 and status = 1");
			foreach($company_experience as $k =>$v){
				$invest_subject_info = unserialize($v['invest_subject']);
				$company_info['company_experience'][$k]['invest_time'] =to_date($v['invest_time'],"Y-m-d");						
				$company_info['company_experience'][$k]['valuation'] = transform_wan($v['valuation']);
				$company_info['company_experience'][$k]['finance_amount'] = transform_wan($v['finance_amount']);
				$company_info['company_experience'][$k]['invest_phase'] = $v['invest_phase'];
				foreach($invest_subject_info as $kk=> $vv){
					if($vv){
						$experience_info =$this->chack_investor_info($vv['invest_id'],$vv['invest_type']);
						if($experience_info){
						  $company_info['company_experience'][$k]['invest_subject_info'][$kk]['image'] = $experience_info['image'];
						}	
						$company_info['company_experience'][$k]['invest_subject_info'][$kk]['id'] = $vv['invest_id'];
						$company_info['company_experience'][$k]['invest_subject_info'][$kk]['name'] =$vv['invest_subject'];
						$company_info['company_experience'][$k]['invest_subject_info'][$kk]['invest_type'] =$vv['invest_type'];
					
					}
				}
			}
			$company_experience_sum = count($company_info['company_experience']);
			$GLOBALS['tmpl']->assign("company_experience_sum",$company_experience_sum);
			
			//过往投资方	
			$company_investor= $GLOBALS['db']->getAll("select cic.* from ".DB_PREFIX."finance_company_team as cic where company_id=".$company_id." and cic.type = 3  and cic.status =1 group by cic.id");
			foreach($company_investor as $k =>$v){
				if($v['invest_type'] ==1){//个人
					$company_info['company_investor'][$k]['id'] = $v['id'];
					$company_info['company_investor'][$k] = $this->get_company_investor($v['invest_type'],1,$v['user_id'],$company_id);
				}elseif($v['invest_type'] ==2){//2 投资机构
					$company_info['company_investor'][$k]['id'] = $v['id'];
					$company_info['company_investor'][$k] = $this->get_company_investor($v['invest_type'],2,$v['user_id'],$company_id);
				}	
			}
			$company_investor_sum = count($company_info['company_investor']);
			$GLOBALS['tmpl']->assign("company_investor_sum",$company_investor_sum);
			//团队成员
			$employee_team= $GLOBALS['db']->getAll("select ct.* from ".DB_PREFIX."finance_company_team as ct where ct.company_id=".$company_id." and ct.type = 1  and ct.`status`=1 group by ct.id");		
			foreach($employee_team as $k=> $v){
				$company_info['employee_team'][$k]['id'] = $v['user_id'];
				$company_info['employee_team'][$k]['user_id'] = $v['user_id'];
				$company_info['employee_team'][$k]['name'] = $v['name'];
				$company_info['employee_team'][$k]['intro'] = $v['intro'];
				$company_info['employee_team'][$k]['image'] = get_user_avatar($v['user_id'], "middle");
				$company_info['employee_team'][$k]['employee_level'] = $this->employee_level($v['employee_level']);
				$company_info['employee_team'][$k]['position'] = $v['position'];
				$company_info['employee_team'][$k]['status'] = $v['status'];
				$company_info['employee_team'][$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
				$company_info['employee_team'][$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
			}
			$employee_team_sum = count($company_info['employee_team']);
			$GLOBALS['tmpl']->assign("employee_team_sum",$employee_team_sum);
			//过往成员
			$past_team= $GLOBALS['db']->getAll("select ct.* from ".DB_PREFIX."finance_company_team as ct where  ct.company_id=".$company_id." and ct.type = 2  and ct.`status`=1 group by ct.id");
			foreach($past_team as $k=> $v){
				$company_info['past_team'][$k]['id'] = $v['user_id'];
				$company_info['past_team'][$k]['user_id'] = $v['user_id'];
				$company_info['past_team'][$k]['name'] = $v['name'];
				$company_info['past_team'][$k]['intro'] = $v['intro'];
				$company_info['past_team'][$k]['image'] = get_user_avatar($v['user_id'], "middle");
				$company_info['past_team'][$k]['employee_level'] = $this->employee_level($v['employee_level']);
				$company_info['past_team'][$k]['position'] = $v['position'];
				$company_info['past_team'][$k]['status'] = $v['status'];
				$company_info['past_team'][$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
				$company_info['past_team'][$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
			
			}
			$past_team_sum = count($company_info['past_team']);
			$GLOBALS['tmpl']->assign("past_team_sum",$past_team_sum);

			$GLOBALS['tmpl']->assign("company_info",$company_info);
		}
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id = ".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ((end_time>".$now." and is_success=0) or (pay_end_time>".$now." and is_success=1))");
			if(!$deal_info){
				$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id =".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ( (end_time<".$now." and is_success=0) or (pay_end_time<=".$now." and is_success=1)) ORDER BY id DESC ");
				if(!$deal_info){
					$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id =".$company_id." and is_delete = 0 and type = 4 and (is_effect = 1 or (is_effect = 0 and user_id =".intval($GLOBALS['user_info']['id'])."))");
				}
			}
			if($deal_info['id']){
				
				$deal_focus = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
				$deal_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
				if($deal_focus_count == ''){
					$deal_focus_count = 0;
				}
				$GLOBALS['tmpl']->assign("deal_focus",$deal_focus);
				$GLOBALS['tmpl']->assign("deal_focus_count",$deal_focus_count);

				$this->show(2);
			}	
				
			if($GLOBALS['user_info'])
			{
				$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
				$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
			}
			
		if($deal_info['status']){
			$this->show($show_type=1);
		}else{
			$company_info['type']=4;
			if($GLOBALS['user_info']){
				$access=get_level_access($GLOBALS['user_info'],$company_info,1);
				$access = $access;
			}else{
				$access = 0;
			}
  			$GLOBALS['tmpl']->assign("access",$access);
 			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
 			$company_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$company_info['user_id']);
 			$company_user_info['user_icon']=$GLOBALS['user_level'][$company_user_info['user_level']]['icon'];
 			$GLOBALS['tmpl']->assign("company_user_info",$company_user_info);
		}
		//
		$GLOBALS['tmpl']->assign("page_title","公司详细");
		$GLOBALS['tmpl']->display("finance/company_deal_show.html");
	}

	//融资详情
	public function company_finance($is_debug=false,$debug_info=array()){
		$now = time();
 		$GLOBALS['tmpl']->assign("now",$now);
 		//
 		$is_debug = 1;
 		$debug_info = array(
 			'id' =>69
 		);
 		
 		if($is_debug){
			$_REQUEST = $debug_info;
		}
		$id = intval($_REQUEST['id']);
		$this->get_company_info($id);
		if($GLOBALS['user_info'])
		{
			$company_id = $GLOBALS['db']->getOne("select company_id from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
		}
		$this->show();
	}
	//管理公司
	public function company_manage(){
		//
		$now = get_gmtime();
		 if(!$GLOBALS['user_info'])
		 app_redirect(url("user#login"));	
		$GLOBALS['tmpl']->assign("page_title","我管理的公司");
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;		
		$parameter=array();
		$company_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company where user_id = ".$GLOBALS['user_info']['id']." order by id desc"." limit ".$limit);
		$company_count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."finance_company where user_id = ".$GLOBALS['user_info']['id']);
		foreach($company_list as $k => $v){
				if($v['status'] ==1 && $v['is_edit'] == 0){
					$company_counts = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."deal where company_id = ".$v['id']." and is_effect =1 and ( (end_time>".$now.") or (pay_end_time>".$now." and is_success=1)) ");
					if($company_counts>0){
						$company_list[$k]['examine_status'] = 2;
					}else{
						$company_list[$k]['examine_status'] = 1;
					}
				}
				//成功融资的的列表
				$company_i_success_counts = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where company_id = ".$v['id']." and is_effect =1 and ((end_time<".$now." and is_success=0) or (pay_end_time<=".$now." and is_success=1))");
				
				if($company_i_success_counts > 0){
						$company_list[$k]['deal_success_company']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where company_id = ".$v['id']." and  is_effect =1 and" .
							" ((end_time<".$now." and is_success=0) or (pay_end_time<=".$now." and is_success=1))");
							
				}
		}
	
		$page = new Page($company_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('company_count',$company_count);
		$GLOBALS['tmpl']->assign('company_list',$company_list);
		//
		$GLOBALS['tmpl']->assign("page_title","我管理的公司");
		$GLOBALS['tmpl']->display("finance/company_manage.html");
	}
	//我关注的公司
	public function company_focus(){
		//
		if(!$GLOBALS['user_info'])
		 app_redirect(url("user#login"));	
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$parameter=array();

		$focus_list = $GLOBALS['db']->getAll("select fc_focus.*,fc.company_name,fc.company_logo,fc_focus.company_id as cid,fc.company_brief,u.user_name,u.id as user_name_id from ".DB_PREFIX."finance_company_focus as fc_focus left join ".DB_PREFIX."finance_company  AS fc on fc_focus.company_id = fc.id left join ".DB_PREFIX."user as u on u.id = fc.user_id where fc_focus.user_id = ".$GLOBALS['user_info']['id']." GROUP BY fc_focus.company_id  limit ".$limit);
		
		$focus_count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."finance_company_focus where user_id= ".$GLOBALS['user_info']['id']);
		
		$GLOBALS['tmpl']->assign('focus_list',$focus_list);
		$GLOBALS['tmpl']->assign('focus_count',$focus_count);
		
		$page = new Page($focus_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		//
		$GLOBALS['tmpl']->assign("page_title","我关注的公司");
		$GLOBALS['tmpl']->display("finance/company_focus.html");
	}
	//关注
	public function focus()
	{
		//
		if(!$GLOBALS['user_info'])
		{
			$data['status'] = 0;
		}
		else
		{
			if($_REQUEST['cid']){
				$id = intval($_REQUEST['cid']);
			}else{
				$deal_id = intval($_REQUEST['id']);
				$id = $GLOBALS['db']->getOne("select company_id as id from ".DB_PREFIX."deal where id = ".$deal_id);
			}
			$company_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id = ".$id);
			if($company_info['user_id']==$GLOBALS['user_info']['id']){
					$data['status'] = 3;	
					$data['info'] = "不能关注自己的项目！";
					ajax_return($data);
			}else{	
				if(!$company_info)
				{
					$data['status'] = 3;	
					$data['info'] = "公司不存";
					ajax_return($data);
				}
				
				$focus_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_focus where company_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
				if($focus_data)
				{
					
					$GLOBALS['db']->query("update ".DB_PREFIX."finance_company set focus_company_count = focus_company_count - 1 where id = ".$id);
					if($GLOBALS['db']->affected_rows()>0)
					{
						$GLOBALS['db']->query("delete from ".DB_PREFIX."finance_company_focus where id = ".$focus_data['id']);
						$GLOBALS['db']->query("update ".DB_PREFIX."user set focus_company_count = focus_company_count - 1 where id = ".intval($GLOBALS['user_info']['id']));
						$data['status'] = 2;	
					}	
					else
					{
						$data['status'] = 3;	
						$data['info'] = "公司未审核";
					}		
				}
				else
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."finance_company set focus_company_count = focus_company_count + 1 where id = ".$id);
					if($GLOBALS['db']->affected_rows()>0)
					{
						$focus_data['user_id'] = intval($GLOBALS['user_info']['id']);
						$focus_data['company_id'] = $id;
						$focus_data['create_time'] = NOW_TIME;
						$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_focus",$focus_data);
						$GLOBALS['db']->query("update ".DB_PREFIX."user set focus_company_count = focus_company_count + 1 where id = ".intval($GLOBALS['user_info']['id']));
						$data['status'] = 1;
					}
					else
					{
						$data['status'] = 3;
						$data['info'] = "公司未审核";
					}
				}
			}
		}
		ajax_return($data);
		//	
	}
	/*
	 * 获取子行业
	 * @param 父级行业数组  $cate_list
	 * @param 子行业ID  $pid
	 */
	public function get_child($cate_list,$pid){
 			foreach($cate_list as $k=>$v)
			{
				if($v['id'] ==  $pid){
					if($v['pid'] > 0){
						$pid =$this->get_child($cate_list,$v['pid']) ;
						if($pid==$v['pid']){
							return $pid;
						}
					}
					else{
						return $pid;
					}
				}
			}
	}
	/*
	 * 融资公司列表数据查询
	 * @param 统计公司数量条件  $conditionall
	 * @param 查询条数  $limit
	 * @param 查询公司条件 $conditions
	 * @param 排序 $orderby
	 * @param 表类型 deal_type
	 */
	function get_c_deal_list($type='',$limit="",$conditions="",$orderby=" is_top DESC,d.sort asc ",$deal_type='deal'){

		if($limit!=""){
			$limit = " LIMIT ".$limit;
		}
		
		if($orderby!=""){
			$orderby = " ORDER BY ".$orderby;
		}

		if($conditions!=""){
			$condition.=" ".$conditions;
		}
	 
	 	$company_count = $GLOBALS['db']->getOne("SELECT count(DISTINCT(c.id)) FROM `".DB_PREFIX."finance_company` as c LEFT JOIN `".DB_PREFIX."deal`  as d on  c.id = d.company_id WHERE ".$condition);
		echo "<!--";
		print_r("SELECT count(DISTINCT(c.id)) FROM `".DB_PREFIX."finance_company` as c LEFT JOIN `".DB_PREFIX."deal`  as d on  c.id = d.company_id WHERE ".$condition);
		echo "-->";
	 	/*（所需项目）准备虚拟数据 start*/
	 	//权限浏览控制
		$company_list = array();
		$level_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_level ");
		$level_list_array=array();
		foreach($level_list_array as $k=>$v){
			if($v['id']){
				$level_list_array[$v['id']]=$v['level'];
			}
		}
		//($company_count);exit;
	 	if($company_count > 0){
			$now_time = NOW_TIME;
			if($type ==3){
				$company_list = $GLOBALS['db']->getAll("select c.*,c.cate_id as cate_ids from `".DB_PREFIX."finance_company` as c LEFT JOIN `".DB_PREFIX."deal`  as d on  c.id = d.company_id where ".$condition.$limit  );
			}else{
				$company_list = $GLOBALS['db']->getAll("select c.*,c.cate_id as cate_ids,d.* from ".DB_PREFIX."deal  as d left join ".DB_PREFIX."finance_company as c on d.company_id = c.id   where ".$condition." GROUP BY d.company_id ".$orderby.$limit  );
			}
			echo "<!--";
			print_r("select c.*,c.cate_id as cate_ids,d.* from ".DB_PREFIX."deal  as d left join ".DB_PREFIX."finance_company as c on d.company_id = c.id   where ".$condition." GROUP BY d.company_id ".$orderby.$limit  );
			echo "-->";
	 		//file_put_contents("condition.txt", print_r("select d.* from ".DB_PREFIX."deal  as d   where ".$condition.$orderby.$limit,1));
			$deal_ids = array();
			foreach($company_list as $k=>$v)
			{ 
				$deal_ids[] = $v['id'];
				//查询出对应项目id的user_level
				$company_list[$k]['deal_level']=$level_list_array[intval($company_list[$k]['user_level'])];
	  			//创始人
				$company_list[$k]['user_name'] = $GLOBALS['db']->getOne("SELECT user_name FROM `".DB_PREFIX."user` WHERE id = ".$v['user_id']);
				//行业
				$company_list[$k]['cate_name'] = $GLOBALS['db']->getOne("SELECT name FROM `".DB_PREFIX."deal_finance_cate` WHERE id = ".$v['cate_id']);
				//融资阶段
				$phase_list =$this->invest_phase_arr();
				$company_list[$k]['phase'] =$phase_list[$v['invest_phase']]['phase'];
			}
		}
		return array("rs_count"=>$company_count,"list"=>$company_list);
	}
	// 供融资列表页轮次筛选使用 数组
	function invest_phase_arr(){
		return array(
						array('invest_phase'=>1,'phase'=>'天使轮'),
						array('invest_phase'=>2,'phase'=>'Pre-A轮'),
						array('invest_phase'=>3,'phase'=>'A轮'),
						array('invest_phase'=>4,'phase'=>'A+轮'),
						array('invest_phase'=>5,'phase'=>'B轮'),
						array('invest_phase'=>6,'phase'=>'B+轮'),
						array('invest_phase'=>7,'phase'=>'C轮'),
						array('invest_phase'=>8,'phase'=>'D轮'),
						array('invest_phase'=>9,'phase'=>'E轮及以后'),
						array('invest_phase'=>10,'phase'=>'并购'),
						array('invest_phase'=>11,'phase'=>'上市')
						);
	}
	//融资阶段
	function get_invest_phase($invest_phase){
		$invest_phase_str='';
		if($invest_phase==0)$invest_phase_str="天使轮";
		elseif($invest_phase==1)$invest_phase_str="Pre-A轮";
		elseif($invest_phase==2)$invest_phase_str="A轮";
		elseif($invest_phase==3)$invest_phase_str="A+轮";
		elseif($invest_phase==4)$invest_phase_str="B轮";
		elseif($invest_phase==5)$invest_phase_str="B+轮";
		elseif($invest_phase==6)$invest_phase_str="C轮";
		elseif($invest_phase==7)$invest_phase_str="D轮";
		elseif($invest_phase==8)$invest_phase_str="E轮及以后";
		elseif($invest_phase==9)$invest_phase_str="并购";
		elseif($invest_phase==10)$invest_phase_str="上市";
		return $invest_phase_str;
	}
	//检索过往投资
	function chack_investor_info($id,$invest_type){
		if($id){
			if($invest_type ==3){
				$company_info = $GLOBALS['db']->getAll("SELECT id,company_name,company_brief,company_logo as image FROM `".DB_PREFIX."finance_company` WHERE status = 1 and  id = '".$id."'group by id");
				if($company_info){//公司
					$investor_info =$company_info[0];	
				}
				
			}elseif($invest_type ==1){
				$user_info = $GLOBALS['db']->getAll("SELECT id,user_name,email,head_image as image,intro FROM `".DB_PREFIX."user` WHERE is_effect = 1 and is_investor = 1 and  id = '".$id."' group by id");
				if($user_info){//个人
					if($user_info[0]['image']==''){
						$user_info[0]['image'] =get_user_avatar($user_info[0]['id'], "middle");
						$user_info[0]['home_url'] = url_wap("home#index",array("id"=>$user_info[0]['id']));
					}
					$investor_info =$user_info[0];		
				}
			}elseif($invest_type ==2){
				$investor = $GLOBALS['db']->getAll("SELECT id,user_name,email,head_image as image,intro FROM `".DB_PREFIX."user` WHERE is_effect = 1 and is_investor = 2 and  id = '".$id."'  group by id");
				if($investor){//投资机构
					if($investor[0]['image']==''){
						$investor[0]['image'] =get_user_avatar($investor[0]['id'], "middle");
						$investor[0]['home_url'] = url_wap("home#index",array("id"=>$investor[0]['id']));
					}
					$investor_info =$investor[0];	
				}
			}else{

			}		
			return $investor_info;
		}
	}
	/*
	 * 获取 过往投资方 基本信息 
	 * @param 过往投资方类型  $invest_type  1表示个人  2表示 机构  3 表示公司  
	 * @param 是否为投资者  $is_investor 默认0表示非投资者，1表示投资者,2 表示机构投资者
	 * @param 数据ID $id
	 * @param 被投资的公司ID $company_id
	 */
	public function get_company_investor($invest_type,$is_investor=1,$id,$company_id)
	{
		if($invest_type ==3){
			$company_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id=".$id);
			
			$company_investor_info['name'] = $company_info['company_name'];
			$company_investor_info['image'] = $company_info['company_logo'];
			$company_investor_info['brief'] =$company_info['company_brief'];
		}else{
			if($is_investor ==1){
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$id." and investor_status = 1 and is_investor = 1");
			}else{
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$id." and investor_status = 1 and is_investor = 2");
			}
			$company_investor_info['user_id'] = $user_info['id'];
			$company_investor_info['name'] = $user_info['user_name'];
			$company_investor_info['image'] = get_user_avatar($user_info['id'], "middle");;
			$company_investor_info['brief'] =$user_info['intro'];
		}
		return $company_investor_info;
	}
	/*
	 * 获取 公司基本信息 和 项目关注数量 
	 * @param 项目ID  $id
	 */
	public function get_company_info($id){
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$company_id = intval($deal_info['company_id']);
		$company_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id= ".$company_id);
 		if(!$company_info){
			showErr("该公司不存在");
		}else{
			$company_info['introduce_image'] = unserialize($company_info['company_introduce_image']);
			$GLOBALS['tmpl']->assign("company_info",$company_info);
		}
		//关注
		$deal_focus = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_focus_log where deal_id= ".$id);
		$deal_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where deal_id= ".$id);
		$GLOBALS['tmpl']->assign("deal_focus",$deal_focus);
		$GLOBALS['tmpl']->assign("deal_focus_count",$deal_focus_count);

	}
	
	/*
	 * 获取 职位名称 
	 * @param 职位类型  $employee_level ; 0 技术 1 设计 2 产品 3 运营 4 市场与销售 5 行政、人事及财务 6 投资和并购 7 其他
	 */
	public function employee_level($employee_level)
	{
		switch($employee_level){
			case 0;
			$level = '技术';
			break;
			case 1;
			$level = '设计';
			break;
			case 2;
			$level = '产品';
			break;
			case 3;
			$level = '运营';
			break;
			case 4;
			$level = '市场与销售';
			break;
			case 5;
			$level = '行政、人事及财务';
			break;
			case 6;
			$level = '投资和并购';
			break;
			default;
			$level = '其他';
		}
		return $level;
	}
	//show_type 0 表示默认（股权和 融资） 1表示公司页面
	public function show($show_type=0)
	{
		//get_mortgate();
 		//获取项目的ID
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		
 		$access=get_level_access($GLOBALS['user_info'],$deal_info);
 		 
		$GLOBALS['tmpl']->assign("access",$access);
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}		
		
		if($deal_info['is_effect']==1)
		{
			log_deal_visit($deal_info['id']);
		}		
		if($deal_info['type']==4){
			//跟投人数
			$gen_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=2 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('gen_num',intval($gen_num));
			//询价人数
			$xun_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=0 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('xun_num',intval($xun_num));
		}
		$deal_info = cache_deal_extra($deal_info);
		
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign('comment_count',$comment_count);
		$this->init_deal_page(@$deal_info);	


		if($deal_info['type']==4){
			$GLOBALS['tmpl']->assign('deal_type','gq_type');
		}
		if($deal_info['type']==4){
			
			if(app_conf("IS_FINANCE")==0)
			{
				$data['status'] = 0;	
				$data['info'] = "融资众筹已经关闭";
				ajax_return($data);
			}
			set_deal_status($deal_info);
	
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
  			$GLOBALS['tmpl']->assign("user_name",$user_name);
  			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' ); 	
 			$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			$GLOBALS['tmpl']->assign("cate",$cate);
			//编辑及管理团队
			$stock_list = unserialize($deal_info['stock']);
			$GLOBALS['tmpl']->assign("stock_list",$stock_list);
			$unstock_list = unserialize($deal_info['unstock']);
			$GLOBALS['tmpl']->assign("unstock_list",$unstock_list);
			//项目历史执行资料
			$history_list = unserialize($deal_info['history']);
			$GLOBALS['tmpl']->assign("history_list",$history_list);	
			$total_history_income =0;
			$total_history_out=0;
			$total_history=0;
			foreach($history_list as $key => $v)
			{
				$total_history_income += floatval($v["info"]["item_income"]);
				$total_history_out+= floatval($v["info"]["item_out"]);
				$total_history=$total_history_income-$total_history_out;
			}
			$GLOBALS['tmpl']->assign("total_history_income",$total_history_income);
			$GLOBALS['tmpl']->assign("total_history_out",$total_history_out);
			$GLOBALS['tmpl']->assign("total_history",$total_history);
			//未来三年内计划
			$plan_list = unserialize($deal_info['plan']);
			$GLOBALS['tmpl']->assign("plan_list",$plan_list);
			$total_plan_income =0;
			$total_plan_out=0;
			$total_plan=0;
			foreach($plan_list as $key => $v)
			{
				$total_plan_income += floatval($v["info"]["item_income"]);
				$total_plan_out+= floatval($v["info"]["item_out"]);
				$total_plan=$total_plan_income-$total_plan_out;
			}
			$GLOBALS['tmpl']->assign("total_plan_income",$total_plan_income);
			$GLOBALS['tmpl']->assign("total_plan_out",$total_plan_out);
			$GLOBALS['tmpl']->assign("total_plan",$total_plan);
			//项目附件
			$attach_list = unserialize($deal_info['attach']);
			$GLOBALS['tmpl']->assign("attach_list",$attach_list);
			//资质证明
			$audit_data_list = unserialize($deal_info['audit_data']);
			$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);

			//跟投、领投信息列表
			get_investor_info($id);
			//var_dump($deal_info);
 			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			/* by slf
			//热门的项目
			$deal_hot_result = get_deal_list($limit,'is_hot=1','support_count desc');
			$GLOBALS['tmpl']->assign("deal_hot_list",$deal_hot_result['list']);
			*/
			//固定回报总共期数
			$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
			$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
			//分红总共期数
			$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
			$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
			if($show_type==0){			
				$GLOBALS['tmpl']->display("finance/company_finance.html");
			}elseif($show_type==1){
				$GLOBALS['tmpl']->display("finance/finance_show.html");
			}elseif($show_type==2){
				
			}
		}
	}
	public function init_deal_page($deal_info)
	{
 		
		$GLOBALS['tmpl']->assign("page_title",$deal_info['name']);
		
		if($deal_info['seo_title']!="")
			$GLOBALS['tmpl']->assign("seo_title",$deal_info['seo_title']);
		if($deal_info['seo_keyword']!="")
			$GLOBALS['tmpl']->assign("seo_keyword",$deal_info['seo_keyword']);
		if($deal_info['seo_description']!="")
			$GLOBALS['tmpl']->assign("seo_description",$deal_info['seo_description']);
		
		$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);
	
	
		$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
		if($deal_info['type']==1||$deal_info['type']==4){
			$deal_info['limit_price_format'] = number_price_format(($deal_info['limit_price']/10000));
		}else{
			$deal_info['limit_price_format'] = $deal_info['limit_price'];
		}
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
 		$deal_info['person']=0;
		$type_2=0;
		$type_array=array();
		//初始化与虚拟金额有所关联的几个比较特殊的数据 start
		foreach ($deal_info['deal_item_list'] as $k=>$v){
 			//统计每个子项目真实+虚拟（人）
			$deal_info['deal_item_list'][$k]['virtual_person_list']=intval($v['virtual_person']+$v['support_count']);
 			if($v['type']==1){
  				$type_array[]=$v;
 				unset($deal_info['deal_item_list'][$k]);
 			}
 		}
  		if($type_array){
 			$deal_info['deal_item_list']=array_merge($deal_info['deal_item_list'],$type_array);
 		}
 		if($deal_info['type']==1||$deal_info['type']==4){
	 		$deal_info['person']=$deal_info['invote_num'];
			$deal_info['total_virtual_price']=number_price_format($deal_info['invote_money']/10000);
 			$deal_info['percent']=round(($deal_info['invote_money']/$deal_info['limit_price'])*100,2);
 		}else{
	 		$deal_info['person']=$deal_info['support_count']+$deal_info['virtual_num'];
			$deal_info['total_virtual_price']=$deal_info['support_amount']+$deal_info['virtual_price'];
 			$deal_info['percent']=round((($deal_info['support_amount']+$deal_info['virtual_price'])/$deal_info['limit_price'])*100,2);
 		}
 		//项目等级放到项目详细页面模块（对详细页面进行控制）
		$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
		$deal_info['virtual_person']=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$deal_info['id']);
		//初始化与虚拟金额有所关联的几个比较特殊的数据 end
		if(!empty($deal_info['vedio'])&&!preg_match("/http://player.youku.com/embed/i",$deal_info['source_video'])){
			$deal_info['source_vedio']= preg_replace("/id_(.*)\.html(.*)/i","http://player.youku.com/embed/\${1}",baseName($deal_info['vedio']));
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set source_vedio='".$deal_info['source_vedio']."'  where id=".$deal_info['id']);
		}
  		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
	
		$deal_item_list = $deal_info['deal_item_list'];
		//开启限购后剩余几位
		foreach ($deal_item_list as $k=>$v){
			if($v['limit_user']>0){
				$deal_item_list[$k]['virtual_add_support_person']=$v['virtual_person']+$v['support_count'];
				$deal_item_list[$k]['remain_person']=$v['limit_user']-$deal_item_list[$k]['virtual_add_support_person'];
				if($deal_item_list[$k]['remain_person'] < 0)
					$deal_item_list[$k]['remain_person']=0;
			}
		}
		$GLOBALS['tmpl']->assign("deal_item_list",$deal_item_list);
 		if($GLOBALS['user_info'])
		{
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focus",$is_focus);
		}
	
	}
}
?>
