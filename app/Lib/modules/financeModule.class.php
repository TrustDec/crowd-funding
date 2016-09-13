<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------
//融资模块

class financeModule extends BaseModule
{
	public function __construct(){
		parent::__construct();
		if(app_conf("IS_FINANCE")==0)
		{
			showErr("融资众筹已经关闭");
		}
		$GLOBALS['tmpl']->assign("deal_type",'finance_type');
 	}
 	//首页
	public function index()
	{ 
		$now = get_gmtime();
		$param = array();//参数集合
		
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$GLOBALS['tmpl']->assign("pid",$id);
		
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$GLOBALS['tmpl']->assign("p_loc",$loc);

		$pha = intval($_REQUEST['pha']);  //阶段
		$param['pha'] = $pha;
		$GLOBALS['tmpl']->assign("p_pha",$pha);

		$kw = strim($_REQUEST['k']);    //关键词
		$param['k'] = $kw;
		$GLOBALS['tmpl']->assign("p_k",$kw);
		
		$state = intval($_REQUEST['state']);  //融资状态
		$param['state'] = $state;
		$GLOBALS['tmpl']->assign("state",$state);

		//所属行业
		$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
		$nav_cate_all=$nav_cate_array['deal_cate_all'];
		$cate_result=$nav_cate_array['deal_cate_big'];
		foreach($cate_result as $k=>$v)
		{
			$temp_param = $param;
			$temp_param['id'] = $v['id'];
			$cate_result[$k]['url'] = url("finance",$temp_param);
		}
		
		if($id >0)
		{
			if($nav_cate_all[$id]['pid'] >0)
			{//当前小分类
				$pid=$nav_cate_all[$id]['pid'];
				$cate_ids['id']=$id;
			}
			else
			{//当前分类是大分类
				$pid=$id;
				if($nav_cate_all[$id]['sub_list'])
				{
					$cate_ids=array_map('array_shift',$nav_cate_all[$id]['sub_list']);
				}
				$cate_ids[]=$id;
			}

			$child_cate_result=$nav_cate_all[$pid]['sub_list'];
			foreach($child_cate_result as $k=>$v)
			{
				$temp_param = $param;
				$temp_param['id'] = $v['id'];
				$child_cate_result[$k]['url'] = url("finance",$temp_param);
			}
		
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		//城市
       	$city_list = load_dynamic_cache("INDEX_CITY_LIST"); 
       	if(!$city_list)
		{
			$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."finance_company where status =1 group by province  order by id asc");
		}
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = url("finance",$temp_param);
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
			$phase_list[$k]['url'] = url("finance",$temp_param);
		}
		$GLOBALS['tmpl']->assign("phase_list",$phase_list);
		$page_size = DEAL_SUPPORT_PAGE_SIZE;
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
            $condition.=" and (d.invest_phase = '".$pha."') ";
            $JOIN = "left join ".DB_PREFIX."deal  AS d on d.company_id = c.id ";
			$GLOBALS['tmpl']->assign("page_title",$pha);            
		}
		
		if($kw!=="")
        {
             $condition .=" and (c.company_name like '%".$kw."%')";
			 $GLOBALS['tmpl']->assign("page_title",$kw);           
		}
		
		$GLOBALS['tmpl']->assign('deal_type','finance_type');
		
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
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		$page_title=$page_title?$page_title:'投资项目';
		$GLOBALS['tmpl']->assign("page_title",$page_title);
				
		require APP_ROOT_PATH.'app/Lib/page.php';
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 		
		
		$p  =  $page->show();
		$p = $page->new_para_show("finance", $param);
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->display("finance/company_deals_list.html");
		
	}	
	//公司总览
	public function company_show(){
		require_once APP_ROOT_PATH.'app/Lib/page.php';
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
			$GLOBALS['tmpl']->assign("company_invests",$company_invests);
			//融资经历
			$company_experience= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id=".$company_id." and type = 1 and status = 1");
			foreach($company_experience as $k =>$v){
				$invest_subject_info = unserialize($v['invest_subject']);
				$company_info['company_experience'][$k]['invest_time'] =to_date($v['invest_time'],"Y-m-d");
				$company_info['company_experience'][$k]['valuation_unit'] = $v['valuation_unit'];						
				$company_info['company_experience'][$k]['valuation'] = transform_wan($v['valuation']);
				$company_info['company_experience'][$k]['finance_amount_unit'] = $v['finance_amount_unit'];
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
			$GLOBALS['tmpl']->assign("company_info",$company_info);
		}
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id = ".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ((end_time>".$now." and is_success=0) or (pay_end_time>".$now." and is_success=1))");
			if(!$deal_info){
				$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id =".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ( (end_time<".$now." and is_success=0) or (pay_end_time<=".$now." and is_success=1)) ORDER BY id DESC ");
				if(!$deal_info){
					$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id =".$company_id." and is_delete = 0 and type = 4 and (is_effect = 1 or (is_effect = 0 and user_id =".intval($GLOBALS['user_info']['id'])."))");
				}
			}
			
			$deal_focus = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
			$deal_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
			$GLOBALS['tmpl']->assign("deal_focus",$deal_focus);
			$GLOBALS['tmpl']->assign("deal_focus_count",intval($deal_focus_count));
		
			if($deal_info['id']){
				$_REQUEST['id'] = $deal_info['id'];
				require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
				$deal_obj = new dealModule;
				$deal_obj->show(2);
			}	
				
			if($GLOBALS['user_info'])
			{
				$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
				$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
			}
			
		if($deal_info['status']){
			require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
			$deal_obj = new dealModule;
			$deal_obj->show($show_type=1);
		}else{
			$company_info['type']=4;
			//暂时 将项目等级与公司等级同步
			if($deal_info['user_level']){
				$company_info['user_level'] = $deal_info['user_level'];
			}
			if($GLOBALS['user_info']){
				$access=get_level_access($GLOBALS['user_info'],$company_info,1);
				$access = $access;
			}else{
				$access = 0;
			}
  			$GLOBALS['tmpl']->assign("access",$access);
 			
 			$company_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$company_info['user_id']);
 			$company_user_info['user_icon']=$GLOBALS['user_level'][$company_user_info['user_level']]['icon'];
 			$GLOBALS['tmpl']->assign("company_user_info",$company_user_info);
			
			$GLOBALS['tmpl']->display("finance/company_deal_show.html");
		}
		
		
	}
	//融资详情
	public function company_finance(){
		$now = get_gmtime();
 		$GLOBALS['tmpl']->assign("now",$now);
		$id = intval($_REQUEST['id']);
		$this->get_company_info($id);
		if($GLOBALS['user_info'])
		{
			$company_id = $GLOBALS['db']->getOne("select company_id from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
		}
		require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
		$deal_obj = new dealModule;
		$deal_obj->show();
	}
	//动态
	public function company_update(){
		$now = get_gmtime();
 		$GLOBALS['tmpl']->assign("now",$now);
		$id = intval($_REQUEST['id']);
		$this->get_company_info($id);
		if($GLOBALS['user_info'])
		{
			$company_id = $GLOBALS['db']->getOne("select company_id from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
		}
		require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
		$deal_obj = new dealModule;
		$deal_obj->update();
	}
	//粉丝
	public function company_fans(){
		$now = get_gmtime();
 		$GLOBALS['tmpl']->assign("now",$now);
		 
		 $company_id = intval($_REQUEST['cid']);
		 $company_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id= ".$company_id);
		 //获取项目的ID
		  $deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id = ".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ((end_time>".$now." and is_success=0) or (pay_end_time>".$now." and is_success=1))");
			if(!$deal_info){
				$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id =".$company_id." and is_delete = 0 and type = 4 and is_effect = 1 and ( (end_time<".$now." and is_success=0) or (pay_end_time<=".$now." and is_success=1)) ORDER BY id DESC ");
				if(!$deal_info){
					$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where company_id = ".$company_id." and is_delete = 0 and type = 4 and (is_effect = 1 or (is_effect = 0 and user_id =".intval($GLOBALS['user_info']['id']));
				}
			}
		 if($deal_info){
		 	$id = $_REQUEST['id'] = intval($deal_info['id']);
		 	$this->get_company_info($id);
		 }else{
		 	if(!$company_info){
				showErr("该公司不存在");
			}
			$GLOBALS['tmpl']->assign("company_info",$company_info);
		 }
		if($GLOBALS['user_info'])
		{
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."finance_company_focus where company_id = ".$company_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focuss",$is_focus);
		}
		 
		$company_focus = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_focus where company_id= ".$company_id);
		$company_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_focus where company_id= ".$company_id);
		foreach($company_focus as $k =>$v){
			 $user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id= ".$v['user_id']);
			 $company_focus[$k]['user_name'] = $user_name;
		}
		
		$GLOBALS['tmpl']->assign("company_focus",$company_focus);
		$GLOBALS['tmpl']->assign("company_focus_count",$company_focus_count);

		$deal_focus = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
		$deal_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where deal_id= ".$deal_info['id']);
		$GLOBALS['tmpl']->assign("deal_focus",$deal_focus);
		$GLOBALS['tmpl']->assign("deal_focus_count",intval($deal_focus_count));
			

		if($deal_info['id']){
			require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
			$deal_obj = new dealModule;
			$deal_obj->show(2);
		}

		if(!$deal_info['status']){
			$company_info['type']=4;
			//暂时 将项目等级与公司等级同步
			if($deal_info['user_level']){
				$company_info['user_level'] = $deal_info['user_level'];
			}
			if($GLOBALS['user_info']){
				$access=get_level_access($GLOBALS['user_info'],$company_info,1);
				$access = $access;
			}else{
				$access = 0;
			}
  			$GLOBALS['tmpl']->assign("access",$access);
 			$company_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$company_info['user_id']);
 			$company_user_info['user_icon']=$GLOBALS['user_level'][$company_user_info['user_level']]['icon'];
 			$GLOBALS['tmpl']->assign("company_user_info",$company_user_info);
		}
		$GLOBALS['tmpl']->display("finance/company_deal_fans.html");
	}
	//创建公司
	public function company_create()
	{
		 if(!$GLOBALS['user_info'])
		 app_redirect(url("user#login"));	
		 
		 check_tg();
		 	
		 $GLOBALS['tmpl']->assign("page_title","创建公司");
		
		 $GLOBALS['tmpl']->display("finance/company_create.html");
	}
	// 检测公司简称
	public function do_company_name(){
		$return =array("status"=>0,"company_name"=>"","company_p_status"=>"","company_brief"=>"","company_logo"=>"","info"=>"","jump"=>"");
		$data=array();
		$data['company_name'] = strim($_REQUEST['company_name']);
		if($data['company_name'] == "aa"){
			$return['status'] = 1;
			$return['company_name'] = "aa";
			$return['company_p_status'] = 2;
			$return['company_logo'] = "http://krplus-pic.b0.upaiyun.com/201507/21190110/0c4d39be627bb39d.jpg";
			$return['company_brief'] = "摄影社交场景应用";
			ajax_return($return);
		}
		ajax_return($return);
	}
	//do 创建公司
	public function do_company_create($is_debug=false,$debug_info=array()){
		 
		$return =array("status"=>0,"info"=>"","jump"=>"");
		if(!$GLOBALS['user_info']&&!$is_debug){
			$return['info'] = "请重新登录";
			ajax_return($return);
		}
		$data=array();
		if($is_debug){
			$_REQUEST = $debug_info;
		}
		$data['user_id'] = intval($_REQUEST['user_id']);
		if(!$data['user_id']){
			$return['info'] = "未获取会员号";
			ajax_return($return,$is_debug);
		}
		$data['company_name'] = strim($_REQUEST['company_name']);
		if(!$data['company_name']){
			$return['info'] = "请输入公司名称";
			ajax_return($return,$is_debug);
 		}
 		$data['company_p_status'] = intval($_REQUEST['company_p_status']);
		$data['company_brief'] = strim($_REQUEST['company_brief']);
		if(!$data['company_brief']){
			$return['info'] = "请输入一句话简介";
			ajax_return($return,$is_debug);
 		}
		$data['company_logo'] = strim($_REQUEST['company_logo']);
		if(!$data['company_logo']){
			$return['info'] = "请输入公司LOGO";
			ajax_return($return,$is_debug);
 		}
		$data['company_website'] = strtolower(strim($_REQUEST['company_website']));
		if($data['company_website']!=''&&!check_url($data['company_website'])){
 			$return['info'] = "公司网站不正确";
			ajax_return($return,$is_debug);
 		}
		$data['company_begin_time'] = strim($_REQUEST['company_begin_time']);
		if(!$data['company_begin_time']){
			$return['info'] = "请输入 创建时间";
			ajax_return($return,$is_debug);
 		}
		$data['company_level'] = intval($_REQUEST['company_level']);
		$data['company_job'] = strim($_REQUEST['company_job']);
		if(!$data['company_job']){
			$return['info'] = "请输入公司职位";
			ajax_return($return,$is_debug);
 		}
		//company_business_card
		$data['company_business_card'] = strim($_REQUEST['company_business_card']);
		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company",$data);
		
		if(!$re){
			$return['info'] = "公司名称重复,请重新输入";
			ajax_return($return,$is_debug);
		}else{
			$company_id= $GLOBALS['db']->insert_id();
			//生成公司管理者
			$datas['company_id'] = $company_id;
			$datas['create_time'] = get_gmtime();
			$datas['type'] = 0;
			$datas['name'] = $GLOBALS['user_info']['user_name'];
			$datas['level'] = $data['company_level'];
			$datas['position'] = $data['company_job'];
			$datas['user_id'] = $GLOBALS['user_info']['id'];
			$datas['status'] =1;
			$datas['email'] = $GLOBALS['user_info']['email'];
			$datas['intro'] = $GLOBALS['user_info']['intro'];
			$datas['is_manager'] = 1;
			$team_re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$datas);
			
			$return['status'] = 1;
			$return['jump'] = url("finance#company_overview",array('id'=>$company_id));
			ajax_return($return,$is_debug);
		}
		
	}
	
	
	
	//管理公司
	public function company_manage(){
		$now = get_gmtime();
		 if(!$GLOBALS['user_info'])
		 app_redirect(url("user#login"));	
		$GLOBALS['tmpl']->assign("page_title","我管理的公司");
		
		$page_size = 9;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
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
	
		$GLOBALS['tmpl']->assign('company_list',$company_list);
		
		require_once APP_ROOT_PATH.'app/Lib/page.php';
 		$page = new Page(intval($company_count),$page_size,$parameter_str);   //初始化分页对象 
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->display("finance/company_manage.html");
	}
	
	//编辑公司
	public function company_overview(){
	 	if(!$GLOBALS['user_info'])
	 	app_redirect(url("user#login"));
	 	$company_id = intval($_REQUEST['id']);
	 	$compay = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id=".$company_id." and user_id = ".$GLOBALS['user_info']['id']);
	 	if($compay['company_create_time']){
	 		$compay['company_create_time'] = to_date($compay['company_create_time'],'Y-m-d');
	 	}
		if(!$compay){
			showErr("该公司不存在",0,url("finance#company_manage"));
		}
		$GLOBALS['tmpl']->assign("page_title",$compay['company_name']);
	 	// 地区筛选
	 	$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $compay['province'])
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
					if($v['name'] == $compay['city'])
					{
						$region_lv3[$k]['selected'] = 1;
						break;
					}
				}
			$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
		}
		// 分类
		$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
		require APP_ROOT_PATH.'system/utils/tree.php';
		$tree=new tree();
		$cate_list=$tree->toFormatTree($cate_list_str);
		$GLOBALS['tmpl']->assign("cate_list",$cate_list);
		//by slf
			//子项目
			$company_sub_product = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_sub_product where company_id=".$company_id);
			foreach($company_sub_product as $k=> $v){
				$company_product[$k]['id'] = $v['id'];
				$company_product[$k]['product_name'] = $v['product_name'];
				$company_product[$k]['product_website'] = $v['product_website'];
			}
			$GLOBALS['tmpl']->assign('productall',$company_product);	
			//创始团队
			$company_team = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_team where company_id=".$company_id." and type = 0" );

			if($company_team){
				foreach($company_team as $k =>$v){
					if($v['is_manager'] ==1){
						$company_team[$k]['company_level'] =$compay['company_level'];
						$company_team[$k]['company_job'] =$compay['company_job'];
						if($v['intro'] == ''){
							$company_team[$k]['intro'] = $GLOBALS['db']->getOne("select intro from ".DB_PREFIX."user where id=".$v['user_id']);
						}
					}
					$company_team[$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
					$company_team[$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
				}
			}
			$GLOBALS['tmpl']->assign('company_team',$company_team);
			//图片简介
			$compay['introduce_image'] = unserialize($compay['company_introduce_image']);
			//公司所有者姓名
			$compay['user_name'] =  $GLOBALS['user_info']['user_name'];
			//投资案例
			$company_investment_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id=".$company_id." and type = 0 and status <>2 order by invest_phase ");
			foreach($company_investment_info as $k =>$v){
				$company_info = $GLOBALS['db']->getRow("select id,company_name,company_brief,company_logo from ".DB_PREFIX."finance_company where id=".$v['invest_company_id']);
				$invest_id = $v['invest_company_id'];
				$company_investment_case[$invest_id]['invest_company_id'] = $company_info['id'];
				$company_investment_case[$invest_id]['company_name'] = $company_info['company_name'];
				$company_investment_case[$invest_id]['image'] = $company_info['company_logo'];
				$company_investment_case[$invest_id]['company_brief'] =$company_info['company_brief'];
				$company_investment_case[$invest_id]['company_id'] =$company_info['id'];
				$company_investment_case[$invest_id]['exp'][$k]['id'] = $v['id'];
				$company_investment_case[$invest_id]['exp'][$k]['status'] = $v['status'];
				$company_investment_case[$invest_id]['exp'][$k]['invest_time'] = to_date($v['invest_time'],"Y-m-d");
				$company_investment_case[$invest_id]['exp'][$k]['invest_phase'] =$v['invest_phase'];
				$company_investment_case[$invest_id]['exp'][$k]['finance_amount_unit'] =$v['finance_amount_unit'];
				$company_investment_case[$invest_id]['exp'][$k]['finance_amount'] =transform_wan($v['finance_amount']);	
			}
			
			$GLOBALS['tmpl']->assign('company_investment_case',$company_investment_case);
			//团队成员
			$company_now_employee = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_team where company_id=".$company_id." and type = 1" );;
			if($company_now_employee){
				foreach($company_now_employee as $k =>$v){				
					$company_now_employee[$k]['level'] = $this->employee_level($company_now_employee[$k]['employee_level']);
					$company_now_employee[$k]['position'] = $v['position'];
					$company_now_employee[$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
					$company_now_employee[$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
					}
			}
			$GLOBALS['tmpl']->assign('company_now_team',$company_now_employee);
			//过往团队
			$company_past_employee = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_team where company_id=".$company_id." and type = 2" );
			if($company_past_employee){
				foreach($company_past_employee as $k =>$v){	
					$company_past_employee[$k]['level'] = $this->employee_level($company_past_employee[$k]['employee_level']);
					$company_past_employee[$k]['job_start_time'] = to_date($v['job_start_time'],"Y-m");
					$company_past_employee[$k]['job_end_time'] = to_date($v['job_end_time'],"Y-m");
					}
				$GLOBALS['tmpl']->assign('company_past_team',$company_past_employee);
			}
			//融资经历
			$company_experience = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id=".$company_id." and type = 1");
			foreach($company_experience as $k =>$v){
				$invest_subject_info = unserialize($v['invest_subject']);
				$company_experience[$k]['invest_time'] =to_date($v['invest_time'],"Y-m-d");						
				$company_experience[$k]['valuation'] = transform_wan($v['valuation']);
				$company_experience[$k]['finance_amount'] = transform_wan($v['finance_amount']);
				foreach($invest_subject_info as $kk=> $vv){
					if($vv){
						$experience_info =$this->chack_investor_info($vv['invest_id'],$vv['invest_type']);
						if($experience_info){
						  $company_experience[$k]['invest_subject_info'][$kk]['image'] = $experience_info['image'];
						}	
						$company_experience[$k]['invest_subject_info'][$kk]['user_id'] = $vv['invest_id'];
						$company_experience[$k]['invest_subject_info'][$kk]['name'] =$vv['invest_subject'];
						$company_experience[$k]['invest_subject_info'][$kk]['invest_type'] =$vv['invest_type'];
					
					}
				}
			}
	
			$GLOBALS['tmpl']->assign('company_experience',$company_experience);
			//过往投资方
			$company_investor_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_team where company_id=".$company_id." and type = 3");
			foreach($company_investor_info as $k =>$v){
				if($v['invest_type'] ==1){//个人	
					$company_investor[$k] = $this->get_company_investor($v['invest_type'],1,$v['user_id'],$company_id);
				}elseif($v['invest_type'] ==2){//2 投资机构
					$company_investor[$k] = $this->get_company_investor($v['invest_type'],2,$v['user_id'],$company_id);
				}
				$company_investor[$k]['status'] =$v['status']; 
				$company_investor[$k]['id'] = $v['id'];	
			}
			$GLOBALS['tmpl']->assign('company_investor',$company_investor);	

	 		if($compay){
	 			if($compay['is_edit']==1){
					$compay['company_is_edit'] = 1;
	 			}else{
	 				$compay['company_is_edit'] = 0;
	 			}
	 		}
	 		if($compay['company_create_time']==0){
	 			$compay['company_create_time'] = '';
	 		}
		 	$GLOBALS['tmpl']->assign('company',$compay);
 			$GLOBALS['tmpl']->display("finance/company_overview.html");
	}
	//do 编辑公司
	public function do_company_overview(){
		$method = strim($_REQUEST['method']);
		$return = array('status'=>1,'info'=>'','jump'=>'','method'=>'');
		$company_id = intval($_REQUEST['company_id']);
		if(!$company_id){
			$return['status'] = 0;
			$return['info'] = "公司ID不存在";
			ajax_return($return);
		}
		if($method=='company_default'){
			$return['method'] = 'company_default';
			$data = array();
			$data['company_p_status'] = intval($_REQUEST['company_p_status']);
			$data['company_logo'] = strim($_REQUEST['company_logo']);
			if(!$data['company_logo']){
				$return['status'] = 0;
				$return['info'] = "请上传图片";
				ajax_return($return);
			}
			require_once APP_ROOT_PATH."system/libs/words.php";	
			$data['company_tag'] = implode(" ",words::segment($_REQUEST['company_tag']));
			
			$data['company_all_name'] = strim($_REQUEST['company_all_name']);
			$data['company_brief'] = strim($_REQUEST['company_brief']);
			if(!$data['company_brief']){
				$return['status'] = 0;
				$return['info'] = "请填写一句话简介";
				ajax_return($return);
			}
			
			$data['company_website'] = strtolower(strim($_REQUEST['company_website']));
			if($data['company_website'] !=''&&!check_url($data['company_website'])){
				$return['status'] = 0;
				$return['info'] = "公司网址填写错误";
				ajax_return($return);
			}
			$data['province'] = strim($_REQUEST['province']);
			if(!$data['province']){
				$return['status'] = 0;
				$return['info'] = "请选择省份";
				ajax_return($return);
			}
			$data['city'] = strim($_REQUEST['city']);
			if(!$data['city']){
				$return['status'] = 0;
				$return['info'] = "请选择城市";
				ajax_return($return);
			}
			
			$data['company_create_time'] =to_timespan(strim($_REQUEST['company_create_time']),'Y-m-d');
			$data['cate_id'] = intval($_REQUEST['cate_id']);
			if(!$data['cate_id']){
				$return['status'] = 0;
				$return['info'] = "请选择公司领域";
				ajax_return($return);
			}
			
			$data['company_sina_weibo'] = strim($_REQUEST['company_sina_weibo']);
			$data['company_weixin'] = strim($_REQUEST['company_weixin']);
			$re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company",$data,'UPDATE'," id = ".$company_id);
 			if($re){
 				$return['company_name'] = $data['company_name'];
 				$return['company_all_name'] = $data['company_all_name'];
 				$return['company_brief'] = $data['company_brief'];
 				$return['province'] = $data['province'];
 				$return['city'] = $data['city'];
 				$return['company_website'] = $data['company_website'];
 				$return['company_tag'] = $data['company_tag'];
 				$return['company_sina_weibo'] = $data['company_sina_weibo'];
 				$return['company_weixin'] = $data['company_weixin'];
				ajax_return($return);
			}else{
				$return['status'] = 0;
				$return['info'] = "保存失败";
				ajax_return($return);
			}
			
		}elseif($method=='company_intro'){  //公司介绍			
			$return['method'] = 'company_intro';
			$data = array();
			$data['company_introduce_word'] = strim($_REQUEST['intro']);
			if(!$data['company_introduce_word']){
				$return['status'] = 0;
				$return['info'] = "请填写公司介绍";
				ajax_return($return);
			}
			//------------------
			$introduce_image = $_REQUEST['introduce_image'];

			if(count($introduce_image)>5)
			{
				$return['status'] = 0;
				$return['info'] = "图片不能超过五张";
				ajax_return($return);	
			}else{				
				if(count($introduce_image)>0)
				{
					foreach($introduce_image as $k=>$v)
					{
						$introduce_image[$k] = format_image_path($v);
						$introduce_images[] = replace_public($v);
						$data['company_introduce_image'] =serialize($introduce_images);
					}					
				}else{
					$data['company_introduce_image'] = '';
				}
			}

		    $re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company",$data,'UPDATE'," id = ".$company_id);
 			if($re){
 				$return['company_introduce_word'] = $data['company_introduce_word'];
 				$return['company_introduce_image'] = $introduce_image;
				ajax_return($return);
			}else{
				$return['status'] = 0;
				$return['info'] = "保存失败";
				ajax_return($return);
			}
			
		}elseif($method=='company_link'){  //相关链接
			
			$return['method'] = 'company_link';
			$data = array();
			$data['company_website'] = strtolower(strim($_REQUEST['webLink']));				
				
			if($data['company_website']){
				if(!check_url($data['company_website'])){
					$return['status'] = 0;
					$return['info'] = "Web端链接填写错误";
					ajax_return($return);
				}
			}
			$data['iphone_url'] = strtolower(strim($_REQUEST['iphoneAppstoreLink']));
			if($data['iphone_url']){
				if(!check_url($data['iphone_url'])){
					$return['status'] = 0;
					$return['info'] = "iPhone下载链接填写错误";
					ajax_return($return);
				}
			}
			$data['pc_url'] =strtolower(strim($_REQUEST['pcLink'])) ;
			if($data['pc_url']){
				if(!check_url($data['pc_url'])){
					$return['status'] = 0;
					$return['info'] = "PC端下载链接填写错误";
					ajax_return($return);
				}
			}
			
			$data['android_url'] =strtolower(strim($_REQUEST['androidLink'])) ;
			if($data['android_url']){
				if(!check_url($data['android_url'])){
					$return['status'] = 0;
					$return['info'] = "Android下载链接填写错误";
					ajax_return($return);
				}
			}
			
			$data['ipd_url'] =strtolower(strim($_REQUEST['ipadAppstoreLink']));
			if($data['ipd_url']){
				if(!check_url($data['ipd_url'])){
					$return['status'] = 0;
					$return['info'] = "iPad下载链接填写错误";
					ajax_return($return);
				}
			}
			
		    $re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company",$data,'UPDATE'," id = ".$company_id);
 			if($re){
 				$return['company_website'] = $data['company_website'];
 				$return['iphone_url'] = $data['iphone_url'];
 				$return['pc_url'] = $data['pc_url'];
 				$return['android_url'] = $data['android_url'];
 				$return['ipd_url'] = $data['ipd_url'];
				ajax_return($return);
			}else{
				$return['status'] = 0;
				$return['info'] = "保存失败";
				ajax_return($return);
			}
		}elseif($method=='company_sub_project'){//子产品介绍 
			$return['method'] = 'company_sub_project';
			$data = array();
			
			$ajax_act=strim($_REQUEST['ajax_act']);
			$product_id= intval($_REQUEST['ajax_item_id']);			
			
			if($ajax_act == 'edit'){
					$compay_product = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_sub_product where id= ".$product_id);
					$return['status'] = 1;
					$return['compay_product'] = $compay_product;
					ajax_return($return);
			}elseif($ajax_act == 'del'){	
				if($product_id){
					$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_sub_product where id= ".$product_id);
					$return['status'] = 1;
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "删除失败";
					ajax_return($return);
				}	
			}elseif($ajax_act == 'save'){
				$data['product_name'] = strim($_REQUEST['subPro_name']);
				if(!$data['product_name']){
					$return['status'] = 0;
					$return['info'] = "请填写子产品名称";
					ajax_return($return);
				}
				$data['product_website'] =strtolower(strim($_REQUEST['subPro_website']));
				if(!$data['product_website']){
					$return['status'] = 0;
					$return['info'] = "请填写子产品链接";
					ajax_return($return);
				}elseif(!check_url($data['product_website'])){
					$return['status'] = 0;
					$return['info'] = "产品链接填写错误";
					ajax_return($return);
				}
				
				$compay_product = $GLOBALS['db']->getOne("select * from ".DB_PREFIX."finance_company_sub_product where id= ".$product_id);
		 		if(!$compay_product){
		 			$data['company_id'] = $company_id;
		 			$data['create_time'] = get_gmtime();
					$data['status'] = 0;
					$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_sub_product",$data);
				}else{
					$re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_sub_product",$data,'UPDATE'," id = ".$product_id);
				}
	
	 			if($re){
	 				$return['status'] = 1;
	 				$compay_productall = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_sub_product where company_id= ".$company_id);
	 				$return['productall'] = $compay_productall;
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "保存失败";
					ajax_return($return);
				}
			}
			
		}elseif($method=='company_team'){//创始团队 //团队成员 //过往成员 
			$return['method'] = 'company_team';
			$data = array();			
						
			$ajax_act= strim($_REQUEST['ajax_act']);
			$team_id = intval($_REQUEST['ajax_item_id']);
			$type= intval($_REQUEST['type']);
			
			if($ajax_act == 'edit'){
					$compay_team = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_team where id= ".$team_id);
					$return['status'] = 1;
					$return['compay_team'] = $compay_team;
					ajax_return($return);
			}elseif($ajax_act == 'del'){
				if($team_id){
					$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_team where id= ".$team_id);
					$return['status'] = 1;
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "删除失败";
					ajax_return($return);
				}	
			}elseif($ajax_act == 'save'){
				//团队成员姓名
				$data['name'] = strim($_REQUEST['founder_name']);
				if(!$data['name']){
					$return['status'] = 0;
					$return['info'] = "请填写团队姓名";
					ajax_return($return);
				}
				//团队职位类型 0 表示创始团队 1 团队成员 2 过往成员
				if($type ==0){
					$data['level'] = intval($_REQUEST['company_level']);
				}elseif($type ==1||$type ==2){
					$data['employee_level'] = intval($_REQUEST['company_level']);
				}
				//团员职位
				$data['position'] = strim($_REQUEST['company_job']);
				if(!$data['position']){
					$return['status'] = 0;
					$return['info'] = "请填写职位";
					ajax_return($return);
				}
				//成员简介
				$data['intro'] = strim($_REQUEST['founder_intro']);
					
				$compay_team = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_team where id= ".$team_id);
		 		if(!$compay_team){
		 			$data['company_id'] = $company_id;
		 			$data['invite_name'] = $GLOBALS['db']->getOne("select company_name from ".DB_PREFIX."finance_company where id= ".$company_id);;
		 			$data['create_time'] = get_gmtime();
					$data['status'] = 0;
					$data['type'] = $type;
					$data['user_id'] =intval($_REQUEST['user_id']);
					//20151125新增
					$data['job_start_time'] =to_timespan($_REQUEST['job_start_time']);
					if(!$data['job_start_time']){
						$return['status'] = 0;
						$return['info'] = "请填写任职起始时间";
						ajax_return($return);
					}
					
					if($_REQUEST['job_end_time'] === NULL||$_REQUEST['job_end_time'] ==''){
						$return['status'] = 0;
						$return['info'] = "请填写任职结束时间";
						ajax_return($return);
					}else{
						$data['job_end_time'] = to_timespan($_REQUEST['job_end_time']);
					}

					$data['update_time'] =to_timespan($_REQUEST['update_time']);
					//20151125end
					
					$employee = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_team where user_id= '".$data['user_id']."' and company_id='".$company_id."' and type = ".$data['type']);
					if(!$employee){
						$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id= '".$data['user_id']."'");
						if($user){
							$data['email'] = $user['email'];
							$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$data);
							$return['id'] = $GLOBALS['db']->insert_id();
						}else{
							$return['status'] = 0;
							$return['info'] = "会员不存在或还未审核通过，请稍后添加";
							ajax_return($return);
						}
					}else{
						$return['status'] = 0;
						$return['info'] = "成员已添加，不能重复添加";
						ajax_return($return);
					}
				}
	 			if($re){
	 				$return['status'] = 1;
	 				$return['name'] = $data['name'];
	 				$return['employee_level'] = $this->employee_level($data['employee_level']);
	 				$return['type'] = $data['type'];
	 				$return['position'] = $data['position'];
	 				$return['level'] = $data['level'];
	 				$return['intro'] = $data['intro'];
	 				$return['user_id'] = $data['user_id'];
	 				$return['home_url'] = url("home#index",array("id"=>$data['user_id']));
	 				$return['image'] =get_user_avatar($data['user_id'], "middle");
					$return['job_start_time'] =to_date($data['job_start_time'],"Y-m");
					$return['job_end_time'] =to_date($data['job_end_time'],"Y-m");
					$return['update_time'] = $data['update_time'];
					$return['invite_name'] = $data['invite_name'];
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "保存失败";
					ajax_return($return);
				}
			}
		}elseif($method=='company_case'){	//团队优势 
			$return['method'] = 'company_case';
			$data = array();
			$data['team_advantage'] = strim($_REQUEST['story']);

		    $re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company",$data,'UPDATE'," id = ".$company_id);
 			if($re){
 				$return['team_advantage'] = $data['team_advantage'];
				ajax_return($return);
			}else{
				$return['status'] = 0;
				$return['info'] = "保存失败";
				ajax_return($return);
			}
		}elseif($method=='company_invest'){	//投资案例
			$return['method'] = 'company_invest';
			$data = array();
			
			$ajax_act= trim($_REQUEST['ajax_act']);
			$investment_id = intval($_REQUEST['ajax_item_id']);
			$invest_id = intval($_REQUEST['ajax_invest_id']);
			
			if($ajax_act == 'edit'){
					$compay_investment = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_investment_case where id= ".$investment_id);
					$return['status'] = 1;
					$compay_investment['invest_time'] = to_date($compay_investment['invest_time'],"Y-m-d");
					$compay_investment['finance_amount'] = transform_wan($compay_investment['finance_amount']);
					$compay_investment['invest_amount'] = transform_wan($compay_investment['invest_amount']);
					$compay_investment['valuation'] = transform_wan($compay_investment['valuation']);
					$return['compay_team'] = $compay_investment;
					ajax_return($return);
			}elseif($ajax_act == 'del'){
				if($investment_id||$invest_id){
					if($investment_id){
						$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_investment_case where id= ".$investment_id);	
						$return['status'] = 1;
						ajax_return($return);
					}else{
						$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_investment_case where invest_company_id= ".$invest_id." and company_id =".$company_id);
						$return['status'] = 1;
						ajax_return($return);
					}
				}else{
					$return['status'] = 0;
					$return['info'] = "删除失败";
					ajax_return($return);
				}	
			}elseif($ajax_act == 'save'){
				$data['company_name'] = strim($_REQUEST['company_abbreviat']);
				if(!$data['company_name']){
					$return['status'] = 0;
					$return['info'] = "请填写公司简称";
					ajax_return($return);
				}
				$data['invest_phase'] = intval($_REQUEST['invest_phase']);
				$data['invest_amount_unit'] = intval($_REQUEST['invest_amount_unit']);
				$data['invest_amount'] =transform_yuan(intval($_REQUEST['invest_amount']));
				$data['finance_amount_unit'] = intval($_REQUEST['finance_amount_unit']);				
				$data['finance_amount'] = transform_yuan(intval($_REQUEST['finance_amount']));				
				$data['valuation_unit'] = intval($_REQUEST['valuation_unit']);
				$data['valuation'] = transform_yuan(intval($_REQUEST['valuation']));

				$data['invest_time'] =to_timespan(strim($_REQUEST['invest_time']),'Y-m-d');

				if(!$data['invest_time']){
					$return['status'] = 0;
					if(intval($_REQUEST['phase_type'])== 0){
						$return['info'] = "投资时间";
					}else{
						$return['info'] = "并购时间";
					}
					ajax_return($return);	
				}

				$company_investment = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_investment_case where id= ".$investment_id."");
		 		if(!$company_investment){
			 			$data['invest_company_id'] = intval($_REQUEST['invest_id']);
						if($data['invest_company_id']){
							$invest_company_info = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_investment_case where invest_company_id= '".$data['invest_company_id']."' and company_id='".$company_id."' and type = 0 and invest_phase =".$data['invest_phase']);
							if(!$invest_company_info){
								$data['type'] = 0;//表示投资案例
					 			$data['company_id'] = $company_id;
					 			$data['create_time'] = get_gmtime();
					 			$data['invest_company_id'] =intval($_REQUEST['invest_id']);
								$data['status'] = 0;//0 未审核 1审核通过 2 审核不通过
								$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_investment_case",$data);
								$return['id'] = $GLOBALS['db']->insert_id();
							}else{
								$return['status'] = 0;
								$return['info'] = "公司案例已添加，不能重复添加";
								ajax_return($return);
							}
						}else{
							$return['status'] = 0;
							$return['info'] = "公司不存在";
							ajax_return($return);
						}
						
				}else{
					$re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_investment_case",$data,'UPDATE'," id = ".$company_investment['id']);
					$return['id'] =$company_investment['id'];
				}
				
	 			if($re){
	 				$return['status'] = 1;
	 				$return['company_name'] = $data['company_name'];
	 				$company_info = $GLOBALS['db']->getRow("select company_brief,company_logo from ".DB_PREFIX."finance_company where id=".$data['invest_company_id']);
	 				$return['image'] =$company_info['company_logo'];
	 				$return['company_brief']=$company_info['company_brief'];
	 				$return['invest_company_id'] =$data['invest_company_id'];
	 				$return['invest_time'] = to_date($data['invest_time'],"Y-m-d");
	 				$return['invest_phase'] = $data['invest_phase'];
	 				$return['company_url'] = url("finance#company_show",array("cid"=>$data['invest_company_id']));
	 				$return['finance_amount_unit'] =$data['finance_amount_unit'];
	 				$return['finance_amount'] = empty($data['finance_amount'])?'0.00':transform_wan($data['finance_amount']);
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "保存失败";
					ajax_return($return);
				}
			}
			
		}elseif($method=='company_experience'){//融资经历
			$return['method'] = 'company_experience';
			$data = array();
				
			$ajax_act= strim($_REQUEST['ajax_act']);
			$finance_id = intval($_REQUEST['ajax_item_id']);			
			if($ajax_act == 'edit'){
					$compay_finance = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_investment_case where id= ".$finance_id);
					$return['status'] = 1;
					$compay_finance['invest_time'] =to_date($compay_finance['invest_time'],"Y-m-d");
					$compay_finance['finance_amount'] = transform_wan($compay_finance['finance_amount']);
	 				$compay_finance['valuation'] = transform_wan($compay_finance['valuation']);
					$invest_subject_info = unserialize($compay_finance['invest_subject']);
					foreach($invest_subject_info as $kk=> $vv){
						if($vv){
							$experience_info =$this->chack_investor_info($vv['invest_id'],$vv['invest_type']);
							if($experience_info){
							  $compay_finance['invest_subject_info'][$kk]['image'] = $experience_info['image'];
							}
							if($vv['invest_id'] != $compay_finance['invest_subject_info']){
								$compay_finance['invest_subject_info'][$kk]['id'] = $vv['invest_id'];
								$compay_finance['invest_subject_info'][$kk]['name'] =$vv['invest_subject'];
								$compay_finance['invest_subject_info'][$kk]['invest_type'] =$vv['invest_type'];
							}
							
						}
					}
					$return['compay_team'] = $compay_finance;
					ajax_return($return);
			}elseif($ajax_act == 'del'){
				if($finance_id){
					$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_investment_case where id= ".$finance_id);	
					$return['status'] = 1;
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "删除失败";
					ajax_return($return);
				}	
			}elseif($ajax_act == 'save'){
				$data['invest_phase'] = strim($_REQUEST['finance_phase']);	
				if($data['invest_phase'] == ''){
					$return['status'] = 0;
					$return['info'] = "请选择融资阶段";
					ajax_return($return);
				}
				//我方投资金额
				$data['invest_amount_unit'] = intval($_REQUEST['invest_amount_unit']);
				$data['invest_amount'] =transform_yuan(intval($_REQUEST['invest_amount'])) ;
				//此轮总投资金额
				$data['finance_amount_unit'] = intval($_REQUEST['finance_amount_unit']);
				$data['finance_amount'] =transform_yuan(intval($_REQUEST['finance_amount']));
				//此轮估值
				$data['valuation_unit'] = intval($_REQUEST['finance_valuation_unit']);
				$data['valuation'] =transform_yuan(intval($_REQUEST['finance_valuation']));
				//投资主体			
				foreach($_REQUEST['invests_id'] as $k => $v){
					$data['invest_subject'][$v]['invest_id'] = $v;
					$data['invest_subject'][$v]['invest_subject'] = $_REQUEST['invests_subject'][$k];		
					$data['invest_subject'][$v]['invest_type'] = $_REQUEST['invests_type'][$k];
					$invest_info[$k] = $this->chack_investor_info($data['invest_subject'][$v]['invest_id'],$data['invest_subject'][$v]['invest_type']);
				}
			
				sort($data['invest_subject']);
				sort($invest_info);
				$data['invest_subject'] =serialize($data['invest_subject']);
				//相关报道
				$data['finance_pressurl'] =strtolower(strim($_REQUEST['finance_pressurl']));
				if($data['finance_pressurl'] !=''){
					if(!check_url($data['finance_pressurl'])){
						$return['status'] = 0;
						$return['info'] = "相关报道链接出错";
						ajax_return($return);
					}
				}
				
				$data['invest_time'] =to_timespan(strim($_REQUEST['finance_time']),'Y-m-d');
				
				if(!$data['invest_time']){
					$return['status'] = 0;
					if(intval($_REQUEST['phase_type']) == 0){
						$return['info'] = "融资时间";
					}elseif($_REQUEST['phase_type'] == 1){
						$return['info'] = "并购时间";
					}else{
						$return['info'] = "上市时间";
					}
					ajax_return($return);	
				}

				$company_finance = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_investment_case where id= ".$finance_id);
		 		if(!$company_finance){
		 			$company_exists = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."finance_company_investment_case where company_id= ".$company_id." and invest_phase =  ".$data['invest_phase']." and type = 1");
		 			if(!$company_exists){
		 				$data['type'] = 1;//表示融资经历
			 			$data['company_id'] = $company_id;
			 			$data['create_time'] = get_gmtime();
						$data['status'] = 0;
						$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_investment_case",$data);
						$return['id'] = $GLOBALS['db']->insert_id();
		 			}else{
		 				$return['status'] = 0;
						$return['info'] = "相同经历已存在，无需重复添加";
						ajax_return($return);
		 			}
				}else{
					$re = $GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_investment_case",$data,'UPDATE'," id = ".$company_finance['id']);
				}
	 			if($re){
	 				$return['status'] = 1;
	 				$return['company_name'] = $data['company_name'];
	 				$company_info = $GLOBALS['db']->getRow("select company_brief,company_logo from ".DB_PREFIX."finance_company where id=".$data['invest_company_id']);
	 				$return['image'] =$company_info['company_logo'];
	 				$return['company_brief']=$company_info['company_brief'];
	 				$return['invest_company_id'] =$data['invest_company_id'];
	 				$return['invest_time'] = to_date($data['invest_time'],"Y-m-d");
	 				$return['invest_phase'] = $data['invest_phase'];
	 				$return['finance_amount_unit'] =$data['finance_amount_unit'] ;
	 				$return['finance_amount'] = $data['finance_amount']?transform_wan($data['finance_amount']):'0.00';
	 				$return['valuation_unit'] =$data['valuation_unit'] ;
	 				$return['valuation'] = $data['valuation']?transform_wan($data['valuation']):'0.00';
	 				$return['invest_subject_info'] = $invest_info;
	 				$return['finance_pressurl'] = $data['finance_pressurl'];
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "保存失败";
					ajax_return($return);
				}
			}
		}elseif($method=='company_investor'){	//过往投资方
			$return['method'] = 'company_investor';
			$data = array();			
			
			$ajax_act= $_REQUEST['ajax_act'];
			$investor_id = intval($_REQUEST['ajax_item_id']);
			
			if($ajax_act == 'del'){
				if($investor_id){
					//删除邀请信息
					$u_id =$GLOBALS['db']->getRow("select company_id as user_id from ".DB_PREFIX."finance_company_investment_case where id=".$investor_id);	
					$invite_re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."invite  where user_id=".$u_id['user_id']." and invite_id ='".$company_id."' and type =6");

					$re = $GLOBALS['db']->getRow("delete from ".DB_PREFIX."finance_company_investment_case where id= ".$investor_id);	
					$return['status'] = 1;
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "删除失败";
					ajax_return($return);
				}	
			}elseif($ajax_act == 'save'){
				//记录类型	
				$data['type'] = 3;//type 3 过往投资方
				//投资代表
				$data['invest_type'] = strim($_REQUEST['invest_type']);

		 		$compay_team = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company_team where id= ".$investor_id);

		 		if(!$compay_team){//不存在，添加新的投资方
					$data['status'] = 0;
					$data['company_id'] = $company_id;
					
					if($_REQUEST['invest_id']){
						$data['user_id'] = intval($_REQUEST['invest_id']);
					}
					
					$investor = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."finance_company_team where user_id= '".$data['user_id']."' and company_id='".$company_id."' and type = ".$data['type']);

					if(!$investor){//判断是否已经存在
						if($data['invest_type'] !=''){
							$user = $GLOBALS['db']->getRow("select id,user_name,email,intro from ".DB_PREFIX."user where id= '".$data['user_id']."'");
							$data['invite_name'] = $GLOBALS['db']->getOne("select company_name from ".DB_PREFIX."finance_company where id= ".$company_id);;
							$data['create_time'] = get_gmtime();
							if($user){//获取个人的邮箱
								$data['email'] = $user['email'];
								$data['user_id'] = $user['id'];
				 				$data['intro']= $user['intro'];
				 				$data['image']= get_user_avatar($user['id'], "middle");
								$data['name']= $user['user_name'];

								$re=$GLOBALS['db']->autoExecute(DB_PREFIX."finance_company_team",$data);
								$return['id'] = $GLOBALS['db']->insert_id();
							}else{
								$return['status'] = 0;
								$return['info'] = "会员不存在或还未审核通过，请稍后添加";
								ajax_return($return);
							}
						}
					}else{
						$return['status'] = 0;
						$return['info'] = "投资方已添加，不能重复添加";
						ajax_return($return);
					}
				}else{
					//当记录已经存在数据库中，此部分作为更新记录使用。
				}
	 			if($re){
	 				$return['status'] = 1;
	 				$return['name'] = $data['name'];
	 				$return['type'] = $data['invest_type'];
	 				$return['brief'] = $data['intro'];
	 				$return['user_id'] = $data['user_id'];
	 				$return['company_id'] = $data['company_id'];
	 				$return['image'] =$data['image'];
	 				$return['home_url'] = url("home#index",array("id"=>$data['user_id']));
					ajax_return($return);
				}else{
					$return['status'] = 0;
					$return['info'] = "保存失败";
					ajax_return($return);
				}
			}
		}
	}
	//我关注的公司
	public function company_focus(){
		require_once APP_ROOT_PATH.'app/Lib/page.php';
		if(!$GLOBALS['user_info'])
		 app_redirect(url("user#login"));	
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$parameter=array();
			
		$GLOBALS['tmpl']->assign("page_title","我关注的公司");
		
		$focus_list = $GLOBALS['db']->getAll("select fc_focus.*,fc.company_name,fc.company_logo,fc.company_brief,u.user_name,u.id as user_name_id,d.id as deal_id from ".DB_PREFIX."finance_company_focus as fc_focus left join ".DB_PREFIX."finance_company  AS fc on fc_focus.company_id = fc.id left join ".DB_PREFIX."user as u on u.id = fc.user_id LEFT JOIN ".DB_PREFIX."deal as d on d.company_id = fc.id where fc_focus.user_id = ".$GLOBALS['user_info']['id']." and d.is_effect = 1 GROUP BY fc_focus.company_id ORDER BY d.invest_phase limit ".$limit);
		$GLOBALS['tmpl']->assign('focus_list',$focus_list);
		$focus_count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."finance_company_focus where user_id= ".$GLOBALS['user_info']['id']);
		
		$page = new Page(intval($focus_count),$page_size,$parameter_str);   //初始化分页对象 
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->display("finance/company_focus.html");
	}
	
	
	//检索团员名称
	function chack_team_name(){
		if($_REQUEST['name']){
			$name=strim($_REQUEST['name']);	
		}
		if($name){
			$team_name = $GLOBALS['db']->getAll("select u.id,u.user_name,u.email,u.head_image from
".DB_PREFIX."user as u  left join ".DB_PREFIX."finance_company_team  AS fc_team on u.id <>fc_team.user_id where u.investor_status = 1 and u.is_effect=1 and u.id <> ".$GLOBALS['user_info']['id']."  and u.user_name  LIKE '%".$name."%' group by u.id");
		;
		if($team_name){
			$return['status'] = 1;
			foreach($team_name as $k =>$v){
				$team_name[$k]['invest_id']= $v['id'];
				$team_name[$k]['name']= $v['user_name'];
				$team_name[$k]['image']=get_user_avatar($v['id'], "middle");
				$team_name[$k]['brief'] = $v['intro'];
				$team_name[$k]['invest_type'] = 1;
			}
			$return['name'] = $team_name;
		}else{
			$return['status'] = 0;
		}
		ajax_return($return);
		}
	}
	
	// 检索公司名称
	function chack_company_name(){
		if($_REQUEST['name']){
			$name = strim($_REQUEST['name']);
			$where =" and  `company_name` LIKE '%".strim($_REQUEST['name'])."%' and status = 1";
		}	
		if($name !=''){
		$company_info = $GLOBALS['db']->getAll("SELECT id,company_name,company_brief,company_logo FROM `".DB_PREFIX."finance_company` WHERE 1=1 ".$where."  group by id");		
		if($company_info){
			$return['status'] = 1;
			foreach($company_info as $k =>$v){
				$company_name[$k]['invest_id']= $v['id'];
				$company_name[$k]['name'] = $v['company_name'];
				$company_name[$k]['image'] = $v['company_logo'];
				$company_name[$k]['brief'] = $v['company_brief'];
				$company_name[$k]['invest_type'] = 3;
			}
			$return['name'] = $company_name;
		}else{
			$return['status'] =0;
		}
		ajax_return($return);
		}
	}
	//检索过往投资
	function chack_investor_name(){
		if($_REQUEST['name']){
			$name = strim($_REQUEST['name']);
		}		
		if($name){
		//$company_info = $GLOBALS['db']->getAll("SELECT id,company_name,company_brief,company_logo FROM `".DB_PREFIX."finance_company` WHERE status = 1 and  `company_name` LIKE '%".$name."%'" ."  group by id");
		
		$user_info = $GLOBALS['db']->getAll("SELECT id,user_name,email,head_image,intro FROM `".DB_PREFIX."user` WHERE is_effect = 1 and investor_status = 1 and is_investor = 1 and  `user_name` LIKE '%".$name."%'  group by id");
		
		$investor = $GLOBALS['db']->getAll("SELECT id,user_name,email,head_image,intro FROM `".DB_PREFIX."user` WHERE is_effect = 1 and investor_status = 1 and is_investor = 2 and  `user_name` LIKE '%".$name."%'  group by id");

			/*if($company_info){//公司
				$return['status'] = 1;
				foreach($company_info as $k =>$v){
					$company_name[$k]['invest_id']= $v['id'];
					$company_name[$k]['name'] = $v['company_name'];
					$company_name[$k]['image'] = $v['company_logo'];
					$company_name[$k]['brief'] = $v['company_brief'];
					$company_name[$k]['invest_type'] = 3;
					$company_name[$k]['invest_type_name'] ="公司";
				}
				$return['name'] =$company_name;
			}*/
			
			if($user_info){//个人
				$return['status'] = 1;
				foreach($user_info as $k =>$v){
					$user_name[$k]['invest_id']= $v['id'];
					$user_name[$k]['name']= $v['user_name'];
					$user_name[$k]['image']=get_user_avatar($v['id'], "middle");
					$user_name[$k]['brief'] = $v['intro'];
					$user_name[$k]['invest_type'] = 1;
					$user_name[$k]['invest_type_name'] ="个人";
				}
				$return['name'] =$user_name;
					
			}

			if($investor){//投资机构
				$return['status'] = 1;
				foreach($investor as $k =>$v){
					$investor_name[$k]['invest_id']= $v['id'];
					$investor_name[$k]['name']= $v['user_name'];
					$investor_name[$k]['image']=get_user_avatar($v['id'], "middle");
					$investor_name[$k]['brief'] = $v['intro'];
					$investor_name[$k]['invest_type'] = 2;
					$investor_name[$k]['invest_type_name'] ="机构";
				}
				if($return['name']){
					$return['name'] =array_merge($return['name'], $investor_name);
				}else{
					$return['name'] =$investor_name;
				}
				
			}
			if(empty($return['name'])){
				$return['status'] =0;
			}
			
			ajax_return($return);
		}
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
						$user_info[0]['home_url'] = url("home#index",array("id"=>$user_info[0]['id']));
					}
					$investor_info =$user_info[0];		
				}
			}elseif($invest_type ==2){
				$investor = $GLOBALS['db']->getAll("SELECT id,user_name,email,head_image as image,intro FROM `".DB_PREFIX."user` WHERE is_effect = 1 and is_investor = 2 and  id = '".$id."'  group by id");
				if($investor){//投资机构
					if($investor[0]['image']==''){
						$investor[0]['image'] =get_user_avatar($investor[0]['id'], "middle");
						$investor[0]['home_url'] = url("home#index",array("id"=>$investor[0]['id']));
					}
					$investor_info =$investor[0];	
				}
			}else{

			}		
			return $investor_info;
		}
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
	
	//提交公司审核
	public function submit_finance()
	{
		$id = intval($_REQUEST['id']);
		$ajax = 1;
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		$GLOBALS['db']->query("update ".DB_PREFIX."finance_company set is_edit = 0,status=0 where id = ".$id);
		
		$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,'admin',array('company_id'=>$id,'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE));
		showSuccess("提交成功，等待管理员审核！",$ajax);
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
		$GLOBALS['tmpl']->assign("deal_focus_count",intval($deal_focus_count));

	}
	//关注
	public function focus()
	{
				
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
	 * 融资公司列表数据查询
	 * @param 统计公司数量条件  $conditionall
	 * @param 查询条数  $limit
	 * @param 查询公司条件 $conditions
	 * @param 排序 $orderby
	 * @param 表类型 deal_type
	 */
	function get_c_deal_list($type='',$limit="",$conditions="",$orderby=" d.is_top DESC,d.sort asc,d.id desc ",$deal_type='deal'){

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
//		echo "<!--";
//		print_r("SELECT count(DISTINCT(c.id)) FROM `".DB_PREFIX."finance_company` as c LEFT JOIN `".DB_PREFIX."deal`  as d on  c.id = d.company_id WHERE ".$condition);
//		echo "-->";
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
//			echo "<!--";
//			print_r("select c.*,c.cate_id as cate_ids,d.* from ".DB_PREFIX."deal  as d left join ".DB_PREFIX."finance_company as c on d.company_id = c.id   where ".$condition." GROUP BY d.company_id ".$orderby.$limit  );
//			echo "-->";
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
	/*
	 * 查看全部动态
	 * 
	 */
	function updatedetail(){
		require_once APP_ROOT_PATH."app/Lib/modules/dealModule.class.php";	
		$deal_obj = new dealModule;
		$deal_obj->updatedetail();
	 }

}
?>