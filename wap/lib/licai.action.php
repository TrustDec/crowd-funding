<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'wap/app/shop_lip.php';
require APP_ROOT_PATH.'system/libs/licai.php';
require APP_ROOT_PATH.'wap/app/page.php';
class licaiModule  
{
	public function __construct(){
 		$has_pro = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai where user_id= ".$GLOBALS['user_info']['id']);
		$GLOBALS['tmpl']->assign('has_pro',$has_pro);
		define("TIME_UTC",get_gmtime());   //当前UTC时间戳
	}
	public function index()
	{
  		
  		$data = array();
		$data['today'] = date("Y-m-d");
		$GLOBALS['tmpl']->assign("data",$data);
		
  		$page_size = PAGE_SIZE;
		$step_size = PAGE_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		$result = get_licai_list("status=1 ","sort DESC,id DESC",$limit);
		$GLOBALS['tmpl']->assign("list",$result['list']);
		
		$GLOBALS['tmpl']->assign("deal_count",$result['rs_count']);
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 	
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
  		
  		$GLOBALS['tmpl']->display("licai/licai_deals.html");
	}	
	
	
	public function deals(){
		$filter_parms =array();
		
		$filter_parms['type'] = $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		//起购金额
		$filter_parms['money'] = $money = isset($_REQUEST['money']) ? intval($_REQUEST['money']) : 0;
		//年化收益
		$filter_parms['rate'] = $rate = isset($_REQUEST['rate']) ? intval($_REQUEST['rate']) : 0;
		
		$filter_parms['sortby'] = $sortby = isset($_REQUEST['sortby']) ? strim($_REQUEST['sortby']) : "";
		$filter_parms['descby'] = $descby = isset($_REQUEST['descby']) ?strtoupper(strim($_REQUEST['descby'])) : "DESC";
		
		$page_size = ACCOUNT_PAGE_SIZE;
		
		$page = intval($_REQUEST['p']);
		
		
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$condition = " status = 1 ";
		if($type!=""){
			$condition .= " AND `type` = $type  ";
		}
		
		if($money != 0){
			switch($money){
				case 1:
					$condition.=" AND min_money <= 1000 ";
					break;
				case 2:
					$condition.=" AND min_money >= 1000 AND min_money <=10000  ";
					break;
				case 3:
					$condition.=" AND min_money >= 10000 AND min_money <=30000  ";
					break;
				case 4:
					$condition.=" AND min_money >= 30000 AND min_money <=50000  ";
					break;
				case 5:
					$condition.=" AND min_money >= 50000 AND min_money <=100000  ";
					break;
				case 6:
					$condition.=" AND min_money >= 100000 AND min_money <=150000  ";
					break;
				case 7:
					$condition.=" AND min_money >= 150000 AND min_money <=200000  ";
					break;
				case 8:
					$condition.=" AND min_money >= 200000 ";
					break;
			}
		}
		
		if($rate != 0){
			switch($rate){
				case 1:
					$condition.=" AND average_income_rate <= 4.5 ";
					break;
				case 2:
					$condition.=" AND average_income_rate between 4.5 AND  5.6  ";
					break;
				case 3:
					$condition.=" AND average_income_rate between 5.6 AND 6  ";
					break;
				case 4:
					$condition.=" AND average_income_rate between 6 AND  7  ";
					break;
				case 5:
					$condition.=" AND average_income_rate between 7 AND  8  ";
					break;
				case 6:
					$condition.=" AND average_income_rate between 8 AND 9  ";
					break;
				case 7:
					$condition.=" AND average_income_rate >= 9  ";
					break;
			}
		}
		
		$orderBy = "`sort` DESC,id DESC";
		if($sortby!=""){
			$orderBy = $sortby." ".$descby.", `sort` DESC,id DESC ";
		}


		$result = get_licai_list($condition,$orderBy,$limit);
		$GLOBALS['tmpl']->assign("list",$result['list']);
		
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("licai#deals",$filter_parms);
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		
		$money_arr= array(
			"0"=>"全部",
			"1"=>"1000≤",
			"2"=>"1000-1万元",
			"3"=>"1-3万元",
			"4"=>"3-5万元",
			"5"=>"5-10万元",
			"6"=>"10-15万元",
			"7"=>"15-20万元",
			"8"=>"≥20万元",
		);
		
		$money_url = array();
		foreach($money_arr as $k=>$v){
			$tmp_filter = $filter_parms;
			$tmp_filter['money'] = $k;
			$money_url[$k]['name'] = $v;
			$money_url[$k]['selected'] = ($k==$money) ? 1 : 0;
			$money_url[$k]['url'] = url("licai#deals",$tmp_filter);
		}
		$GLOBALS['tmpl']->assign('money_url',$money_url);	
		
		
		$rate_arr= array(
			"0"=>"全部",
			"1"=>"4.50%以下",
			"2"=>"4.50%-5.60%",
			"3"=>"5.60%-6.00%",
			"4"=>"6.00%-7.00%",
			"5"=>"7.00%-8.00%",
			"6"=>"8.00%-9.00%",
			"7"=>"9.00%以上",
		);
		
		$rate_url = array();
		foreach($rate_arr as $k=>$v){
			$tmp_filter = $filter_parms;
			$tmp_filter['rate'] = $k;
			$rate_url[$k]['name'] = $v;
			$rate_url[$k]['selected'] = ($k==$rate) ? 1 : 0;
			$rate_url[$k]['url'] = url("licai#deals",$tmp_filter);
		}
		$GLOBALS['tmpl']->assign('rate_url',$rate_url);	
		
		$orderby_arr = array(
			"0"=>array("name"=>"全部产品","key"=>""),
			"1"=>array("name"=>"起购金额","key"=>"min_money"),
			"2"=>array("name"=>"年化利率","key"=>"average_income_rate"),
			"3"=>array("name"=>"成交总额","key"=>"subscribing_amount"),
		);
		
		$orderby_url = array();
		foreach($orderby_arr as $k=>$v){
			$tmp_filter = $filter_parms;
			$tmp_filter['sortby'] = $v['key'];
			
			$orderby_url[$k]['key'] = $v['key'];
			$orderby_url[$k]['name'] = $v['name'];
			$orderby_url[$k]['selected'] = ($v['key']==$sortby) ? 1 : 0;
			if($orderby_url[$k]['selected']==1){
				
				$tmp_filter['descby'] = ($descby=="DESC") ? "ASC" : "DESC"; 
				$orderby_url[$k]['descby'] = $descby;
			}
			else{
				$tmp_filter['descby'] = "DESC";
				$orderby_url[$k]['descby'] = "DESC";
			}
			
			$orderby_url[$k]['url'] = url("licai#deals",$tmp_filter);
		}
		$GLOBALS['tmpl']->assign('orderby_url',$orderby_url);	
		$GLOBALS['tmpl']->assign('descby',$descby);	
		
		
		$image_list = load_dynamic_cache("INDEX_IMAGE_LIST");
		if($image_list===false)
		{
			$image_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."index_image order by sort asc");
			set_dynamic_cache("INDEX_IMAGE_LIST",$image_list);
		}
		$GLOBALS['tmpl']->assign("image_list",$image_list);
		// 猜你喜欢
		$hot_list = get_licai_list($condition,"sort DESC,subscribing_amount DESC",$limit);
		$GLOBALS['tmpl']->assign("hot_list",$hot_list['list']);
		$rectype_list = get_licai_list("re_type > 0 and status=1 and is_recommend = 1","sort DESC,id DESC",3);
		$GLOBALS['tmpl']->assign("rectype_list",$rectype_list['list']);
		
		//为客户创造收益
		//$user_income = floatval($GLOBALS['db']->getOneCached("select sum(earn_money) from ".DB_PREFIX."user_log WHERE `type`=9 "));
		$user_income = floatval($GLOBALS['db']->getOneCached("select sum(earn_money) from ".DB_PREFIX."licai_redempte"));
		$GLOBALS['tmpl']->assign("user_income",$user_income);
		
		$GLOBALS['tmpl']->display("licai/licai_deals.html");
	}	
	
	public function deal(){
		$data = array();
		$data['today'] = date("Y-m-d");
		$GLOBALS['tmpl']->assign("data",$data);

		$id = intval($_REQUEST['id']);
		$licai = get_licai($id);
		
		if($GLOBALS['user_info'])
		$GLOBALS['tmpl']->assign('is_login',1);
		else
		$GLOBALS['tmpl']->assign('is_login',0);
		
		$licai["fund_brand_name"] = $GLOBALS["db"]->getOne("select name from ".DB_PREFIX."licai_fund_brand where id =".$licai["fund_brand_id"]);
		
		if(!$licai || $licai['status'] == 0)
			showErr("理财产品不存在");
		$GLOBALS['tmpl']->assign("licai",$licai);
		$min_interest_rate=0;
		$min_interest_rate=0;
		if($licai['type'] > 0){
			$licai_interest_json = json_encode($licai['licai_interest']);
			$min_interest_rate = $licai['licai_interest'][0]['interest_rate'];
			$max_interest_rate = $licai['licai_interest'][count($licai['licai_interest'])-1]['interest_rate'];
		}
		else{
			$licai_interest_json =json_encode($licai['licai_interest']);
		}

		//为客户创造收益
		//$user_income = floatval($GLOBALS['db']->getOneCached("select sum(money) from ".DB_PREFIX."user_log WHERE `type`=9 "));
		$user_income = floatval($GLOBALS['db']->getOneCached("select sum(earn_money) from ".DB_PREFIX."licai_redempte"));
		$GLOBALS['tmpl']->assign("user_income",$user_income);
		
		if($licai['type'] == 0){
			$licai_page_title='余额宝';
		}
		if($licai['type'] == 1){
			$licai_page_title='定存宝';
		}
		if($licai['type'] == 2){
			$licai_page_title='浮动定存';
		}
		$GLOBALS['tmpl']->assign('page_title',$licai_page_title."理财详情");
		
		$condition = " where lc.id = ".$id;
		//图表
		//七天
		//$condition .= " and lh.history_date >= '".to_date(TIME_UTC-7*3600*24,"Y-m-d")."' and lh.history_date <= '".to_date(TIME_UTC,"Y-m-d")."'";
		if($licai["type"] == 0)
		{
			$data_table_count = $GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."licai_history lh left join ".DB_PREFIX."licai lc on lc.id=lh.licai_id ".$condition);
			
			if($data_table_count >= 7)
			{
				$limit = " limit ".($data_table_count-7).",7 ";
			}
			else
			{
				$limit = "";
			}
			
			$data_table = $GLOBALS['db']->getAll("select lh.history_date,lh.rate from ".DB_PREFIX."licai_history lh left join ".DB_PREFIX."licai lc on lc.id = lh.licai_id ".$condition." order by lh.history_date asc ".$limit);
			
			if($data_table_count == 1)
			{
				array_unshift($data_table,array("history_date"=>$data_table[0]["history_date"],"rate"=>$data_table[0]["rate"]));
			}

			$GLOBALS['tmpl']->assign("data_table",$data_table);
		}
		$GLOBALS['tmpl']->assign("licai_interest_json",$licai_interest_json);
		$GLOBALS['tmpl']->assign("min_interest_rate",$min_interest_rate);
		$GLOBALS['tmpl']->assign("max_interest_rate",$max_interest_rate);
		$GLOBALS['tmpl']->display("licai/licai_deal.html");
	}
	
	/**
	 * 下定
	 */
	public function bid(){
		$ajax = intval($_REQUEST['ajax']);
		$id = intval($_REQUEST['id']);
		$money =  floatval($_REQUEST['money']);
		$paypassword = trim($_REQUEST['paypassword']);
		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		
		$result = licai_bid($id,$money,$paypassword);
		if($result['status']==0){
			ajax_return($result,$ajax);
		}
		else{
			ajax_return($result,$ajax);
		}
	}
	// 发起的理财
	public function uc_published_lc_status(){
		$result["status"] = 1;
		$result["info"] = "";
		//购买开始时间
		
		$buy_begin_time = strim($_REQUEST['b_b_time']);
		$buy_end_time = strim($_REQUEST['b_e_time']);
		
		$d = explode('-',$buy_begin_time);

		if (isset($_REQUEST['buy_begin_time']) && $buy_begin_time !="" && checkdate($d[1], $d[2], $d[0]) == false){		
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
			
		}
		
		$d = explode('-',$buy_end_time);
		if ( isset($_REQUEST['buy_end_time']) && strim($buy_end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($buy_begin_time!="" && strim($buy_end_time) !="" && to_timespan($buy_begin_time) > to_timespan($buy_end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		//项目结束时间
		$start_time = strim($_REQUEST['b_time']);
		$end_time = strim($_REQUEST['e_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['begin_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		ajax_return($result);
	}
	public function uc_published_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		// (不考虑是否发放给发起人)
		$total_order = $GLOBALS["db"]->getRow("select 
		sum(money) as total_money,
		sum(site_buy_fee) as total_service_fee 
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id =".$GLOBALS["user_info"]["id"]." and lco.status > 0");
		
		$vo["licai_total_money"] = $total_order["total_money"] - $total_order["total_service_fee"];
		//成交总额 
		$vo["licai_total_money_format"] = format_money_wan($vo["licai_total_money"]);
		
		$licai_count = $GLOBALS["db"]->getRow("select sum(total_people) as total_people_count,count(*) as total_count from ".DB_PREFIX."licai where user_id=".$GLOBALS["user_info"]["id"]);
		
		$vo["total_people_count"] = $licai_count["total_people_count"]?$licai_count["total_people_count"]:0;
		
		$vo["licai_total_count"] = $licai_count["total_count"];
		
		//正在进行中的
		$total_ing_order = $GLOBALS["db"]->getRow("select 
		sum(money) as total_money,
		sum(site_buy_fee) as total_service_fee,
		sum(redempte_money) as total_redempte_money 
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id =".$GLOBALS["user_info"]["id"]." and lco.status in (1,2)");
		
		$vo["licai_total_ing_money"] = $total_ing_order["total_money"] - $total_ing_order["total_service_fee"] - $total_ing_order["total_redempte_money"];
		
		$vo["licai_total_ing_money"] = format_money_wan($vo["licai_total_ing_money"]);
		
		
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$condition =" and status = 1";
		
		$page_size = ACCOUNT_PAGE_SIZE;
		
		$page = intval($_REQUEST['p']);
		
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$search = array();
		
		
		if(strim($_REQUEST["deal_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["deal_name"])."%'";
			$search["deal_name"] = strim($_REQUEST["deal_name"]);
		}
		
		//购买开始时间
		
		$buy_begin_time = strim($_REQUEST['buy_begin_time']);
		$buy_end_time = strim($_REQUEST['buy_end_time']);

		if(strim($buy_begin_time)!="")
		{
			$condition .= " and lc.begin_buy_date >= '".strim($buy_begin_time)."'";
			$search["buy_begin_time"] = strim($_REQUEST["buy_begin_time"]);
			
		}
		if(strim($buy_end_time) !="")
		{
			$condition .= " and lc.begin_buy_date <= '".  strim($buy_end_time)."'";
			$search["buy_end_time"] = strim($_REQUEST["buy_end_time"]);
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['begin_time']);
		$end_time = strim($_REQUEST['end_time']);

		if(strim($start_time)!="")
		{
			$condition .= " and lc.end_date >= '".$start_time."'";
			$search["begin_time"] = $start_time;
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lc.end_date <= '".  $end_time."'";
			$search["end_time"] = $end_time;
		}
			
		$count = $GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."licai lc where user_id ='".$GLOBALS["user_info"]["id"]."'  ".$condition );
				
		$list = $GLOBALS["db"]->getAll("select * from ".DB_PREFIX."licai lc where user_id ='".$GLOBALS["user_info"]["id"]."'  ".$condition." order by id desc limit ".$limit );
		foreach($list as $k=>$v)
		{
			$list[$k] = licai_item_format($v);
			$list[$k]["total_money"] = $GLOBALS["db"]->getOne("select sum(money)-sum(site_buy_fee) from ".DB_PREFIX."licai_order where licai_id = ".$v["id"]); 
			$list[$k]["total_money_format"] = format_money_wan($list[$k]["total_money"]); 
			$list[$k]["net_value_format"] = format_price($list[$k]["net_value"]); 
		}
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS["tmpl"]->assign("search",$search);
		$GLOBALS['tmpl']->assign("page_title","我的理财");
		$GLOBALS['tmpl']->display("licai/licai_uc_published_lc.html");
	}
	// 购买记录
	public function uc_record_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			showErr("操作失败，请重试");
		}
		
		$link_date = to_date(TIME_UTC,"Y-m-d");
		
		$GLOBALS["tmpl"] -> assign("link_date",$link_date);
		
		$vo = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where id=".$id); 
		
		// (不考虑是否发放给发起人)
		$total_order = $GLOBALS["db"]->getRow("select 
		sum(money) as total_money,
		sum(site_buy_fee) as total_service_fee ,
		count(*) as total_count 
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id =".$GLOBALS["user_info"]["id"]." and lco.status > 0 and lc.id=".$id);
		
		$vo["name"] = $GLOBALS["db"]->getOne("select name from ".DB_PREFIX."licai where id = ".$id);
		
		$vo["licai_total_money"] = $total_order["total_money"] - $total_order["total_service_fee"];

		//成交总额 
		$vo["licai_total_money_format"] = format_money_wan($vo["licai_total_money"]);
		
		$vo["licai_order_total_count"] = $total_order["total_count"];
		
		$vo["total_people_count"] = $GLOBALS["db"]->getOne("select count(distinct user_id) from ".DB_PREFIX."licai_order where licai_id=".$id);
		
		//print_r("select count(*) from ".DB_PREFIX."licai_order where licai_id=".$id." group by user_id");die;
		
		if(!$vo["total_people_count"])
		{
			$vo["total_people_count"] = 0;
		}
		
		$vo["average_income_rate_format"] = $vo["average_income_rate"]."%";
		
		
		//正在进行中的
		$total_ing_order = $GLOBALS["db"]->getRow("select 
		sum(money) as total_money,
		sum(site_buy_fee) as total_service_fee,
		sum(redempte_money) as total_redempte_money  
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id =".$GLOBALS["user_info"]["id"]." and lco.status in (1,2) and lc.id=".$id);
		
		$vo["licai_total_ing_money"] = $total_ing_order["total_money"] - $total_ing_order["total_service_fee"] - $total_ing_order['total_redempte_money'];
		
		$vo["licai_total_ing_money_format"] = format_money_wan($vo["licai_total_ing_money"]);
		
		
		//开始
		$conditon = " and lco.status>0 ";
		
		
		
		$page_size = ACCOUNT_PAGE_SIZE;
		
		$page = intval($_REQUEST['p']);
		
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$search = array();
		
		//购买开始时间
		
		$buy_begin_time = strim($_REQUEST['buy_begin_time']);
		$buy_end_time = strim($_REQUEST['buy_end_time']);

		if(strim($buy_begin_time)!="")
		{
			$condition .= " and lco.create_date >= '".strim($buy_begin_time)."'";
			$search["buy_begin_time"] = strim($_REQUEST["buy_begin_time"]);
			
		}
		if(strim($buy_end_time) !="")
		{
			$condition .= " and lco.create_date <= '".  strim($buy_end_time)."'";
			$search["buy_end_time"] = strim($_REQUEST["buy_end_time"]);
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['begin_time']);
		$end_time = strim($_REQUEST['end_time']);

		if(strim($start_time)!="")
		{
			$condition .= " and lco.end_interest_date >= '".$start_time."'";
			$search["begin_time"] = $start_time;
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.end_interest_date <= '".  $end_time."'";
			$search["end_time"] = $end_time;
		}

		$GLOBALS["tmpl"]->assign("search",$search);
		
		$count = $GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.id = ".$id." and lc.user_id = ".$GLOBALS["user_info"]["id"].$condition );
		
		//结束

		$list = $GLOBALS["db"]->getAll("select lco.* from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.id = ".$id." and lc.user_id = ".$GLOBALS["user_info"]["id"].$condition." order by id desc limit ".$limit);
		
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		foreach($list as $k=>$v)
		{
			$list[$k]["have_money"] = $v["have_money"] = $v["money"] - $v["site_buy_fee"] - $v["redempte_money"];
			
			$list[$k]["have_money_format"] = format_money_wan($v["have_money"]);
			
			$list[$k]["interest_rate_format"] = $v["interest_rate"]."%";

			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "未支付";
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已支付";
			}
			elseif($v["status"] == 2)
			{
				$list[$k]["status_format"] = "部分赎回";
			}
			elseif($v["status"] == 3)
			{
				$list[$k]["status_format"] = "已完结";
			}
		}
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS['tmpl']->assign("page_title","购买记录");
		$GLOBALS['tmpl']->display("licai/licai_uc_record_lc.html");
	}
	//
	public function uc_record_lc_status(){
		//购买开始时间
		$result["status"] = 1;
		$result['info']="";
		$buy_begin_time = strim($_REQUEST['b_b_time']);
		$buy_end_time = strim($_REQUEST['b_e_time']);

		$d = explode('-',$buy_begin_time);
		if (isset($_REQUEST['buy_begin_time']) && $buy_begin_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		$d = explode('-',$buy_end_time);
		if ( isset($_REQUEST['buy_end_time']) && strim($buy_end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($buy_begin_time!="" && strim($buy_end_time) !="" && to_timespan($buy_begin_time) > to_timespan($buy_end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		//项目结束时间
		$start_time = strim($_REQUEST['b_time']);
		$end_time = strim($_REQUEST['e_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['begin_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		ajax_return($result);
	}
	// 发起的理财---理财管理
	public function uc_u_buyed_deal_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			showErr("操作失败，请重试");
		}
		
		$vo = $GLOBALS["db"]->getRow("select lc.*,lco.id as order_id from ".DB_PREFIX."licai lc left join ".DB_PREFIX."licai_order lco on lc.id = lco.licai_id where lco.id=".$id." and lc.user_id = ".$GLOBALS["user_info"]["id"]);
		
		$vo["average_income_rate_format"] = $vo["average_income_rate"]."%";
		
		$vo_info =  $GLOBALS["db"]->getRow("select sum(case when lcr.status = 0 then 1 else 0 end) as licai_total_count,sum(case when lcr.status = 1 then lcr.money else 0 end) as licai_total_money,sum(case when lcr.status = 1 then lcr.earn_money-lcr.fee else 0 end) as total_earn_money ,sum(case when lcr.status = 0 then lcr.money+lcr.earn_money-lcr.fee else 0 end) as wait_money
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id = ".$GLOBALS["user_info"]["id"]." and lco.id=".$id);
		
		//请求总数
		$vo["licai_total_count"] = $vo_info["licai_total_count"];
		//本金总和
		$vo["licai_total_money_format"] = format_price($vo_info["licai_total_money"]);
		//收益
		$vo["total_earn_money_format"] = format_price($vo_info["total_earn_money"]);
		//总额
		$vo["licai_all_money_format"] = format_price($vo_info["wait_money"]);
		

		$list = $GLOBALS["db"]->getAll("select lcr.* from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		where lcr.order_id = ".$id);
		
		foreach($list as $k=>$v)
		{
			$list[$k]["money_format"] = format_money_wan($v["money"]);
			$list[$k]["earn_money_format"] = format_price($v["earn_money"]);
			$list[$k]["fee_format"] = format_price($v["fee"]);
			$list[$k]["real_money"] = $v["real_money"] = $v["money"]+ $v["earn_money"] - $v["fee"];
			$list[$k]["real_money_format"] = format_money_wan($v["real_money"]);
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "已结清";
			}
			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$list[$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$list[$k]["status_format"] = "已取消";
			}
		}
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$GLOBALS["tmpl"]->assign("list",$list);
		
		$GLOBALS['tmpl']->display("licai/licai_uc_u_buyed_deal_lc.html");
	}
	// 将过期的理财发放
	public function uc_expire_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$vo = $GLOBALS["db"]->getRow("select 
		sum(lco.money) as licai_total_money,
		count(*) as licai_total_count ,
		sum(lco.interest_rate*lco.money) as interest_rate_money 
		from ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lc.user_id = '".$GLOBALS["user_info"]["id"]."' and lco.end_interest_date = date(now())");
		
		//成交总额
		$vo["licai_total_money_format"] = format_money_wan($vo["licai_total_money"]);
		//利息和
		$vo["interest_rate_money_format"] = format_price($vo["interest_rate_money"]);
		//发起总额
		$vo["licai_all_money"] = $vo["licai_total_money"] + $vo["interest_rate_money"];
		
		$vo["licai_all_money_format"] = format_money_wan($vo["licai_all_money"]);
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$condition =" and lco.status in (1,2,3) ";
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$search = array();
		
		
		if(strim($_REQUEST["deal_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["deal_name"])."%'";
			$search["deal_name"] = strim($_REQUEST["deal_name"]);
		}
		
		if(strim($_REQUEST["user_name"])!="")
		{
			$condition .= " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
			$search["user_name"] = strim($_REQUEST["user_name"]);
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['begin_time']);
		$end_time = strim($_REQUEST['end_time']);	

		/*if(!$start_time)
		{
			$start_time = to_date(TIME_UTC-15*24*3600,"Y-m-d");
		}*/
		if(!$end_time)
		{
			$end_time = to_date(TIME_UTC,"Y-m-d");
		}
		
		if(strim($start_time)!="")
		{
			$condition .= " and lco.end_interest_date >= '".$start_time."'";
			$search["begin_time"] = $start_time;
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.end_interest_date <= '".  $end_time."'";
			$search["end_time"] = $end_time;
		}
		
		$count = $GLOBALS["db"]->getOne("select count(*) 
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lc.user_id ='".$GLOBALS["user_info"]["id"]."' ".$condition );
						
		$list = $GLOBALS["db"]->getAll("select lco.*,lc.name as licai_name,lc.type as licai_type
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lc.user_id ='".$GLOBALS["user_info"]["id"]."'  ".$condition." order by lco.id desc limit ".$limit );

		foreach($list as $k=>$v)
		{
			$list[$k]["money_format"] = format_price($v["money"]-$v['redempte_money']-$v["site_buy_fee"]);
			if($v["licai_type"] > 0)
			{
				$list[$k]["before_rate_format"] = number_format($v["before_rate"],2)."%";
				$list[$k]["interest_rate_format"] = number_format($v["interest_rate"],2)."%";
			}
			else
			{
				$licai_interest = get_licai_interest_yeb($v['licai_id'],$v["begin_interest_date"],$v["end_interest_date"]);
				$list[$k]["rate_format"] = number_format($licai_interest["avg_interest_rate"],2)."%";
			}
			if($v["licai_type"] == 0)
			{
				$list[$k]["type_format"] = "余额宝";
			}
			elseif($v["licai_type"] == 1)
			{
				$list[$k]["type_format"] = "固定定存";
			}
			$list[$k]["create_time"] = to_date(to_timespan($v["create_time"]),"Y-m-d");
			//$list[$k] = licai_item_format($v);
		}
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS["tmpl"]->assign("search",$search);
		$GLOBALS['tmpl']->assign("page_title","快到期理财发放");
		$GLOBALS['tmpl']->display("licai/licai_uc_expire_lc.html");
	}
	
	public function uc_expire_lc_status(){
		$result = array('status'=>1,'info'=>"");
		
		$deal_name = strim($_REQUEST['deal_name']);
		$user_name = strim($_REQUEST['user_name']);
		
		//项目结束时间
		$start_time = strim($_REQUEST['b_time']);
		$end_time = strim($_REQUEST['e_time']);
		if($deal_name==''&&$user_name==''&&$start_time==''&&$end_time==''){
			$result["status"] = 0;
			$result['info']="搜索条件不能为空";
			ajax_return($result);
		}
		
		
		$d = explode('-',$start_time);
		if (isset($_REQUEST['begin_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		$d = explode('-',$end_time);
		if (isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		ajax_return($result);
	}
	// 赎回管理
	public function uc_redeem_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$id = intval($_REQUEST["id"]);
		
		$where = " ";
		
		if($id)
		{
			$where = " and lc.id = ".$id." ";
		}
		
		//待赎回本金
		$wait_order = $GLOBALS["db"]->getRow("select sum(lcr.money) as licai_wait_money, count(*) as wait_count 
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id = '".$GLOBALS["user_info"]["id"]."' and lcr.status = 0
		".$where);
		
		$vo["licai_wait_money_format"] = format_money_wan($wait_order["licai_wait_money"]);
		
		$vo["licai_wait_count"] = $wait_order["wait_count"];
		
		//已赎回本金
		$vo["licai_pass_money"] = $GLOBALS["db"]->getOne("select sum(lcr.money) 
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lc.user_id = '".$GLOBALS["user_info"]["id"]."' and lcr.status = 1
		".$where);
		
		
		$vo["licai_pass_money_format"] = format_money_wan($vo["licai_pass_money"]);
		
		
		//收益总额
		//$vo["licai_total_earn_money_format"] = format_price($vo["licai_total_earn_money"]-$vo["licai_total_fee"]);
		//
		//总额
		$vo["licai_all_money"] = $vo["licai_total_money"] + $vo["licai_total_earn_money"] - $vo["licai_total_fee"];
		
		$vo["licai_all_money_format"] = format_money_wan($vo["licai_all_money"]);
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$condition ="  ".$where;
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$search = array();
		
		
		if(strim($_REQUEST["deal_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["deal_name"])."%'";
			$search["deal_name"] = strim($_REQUEST["deal_name"]);
		}
		
		if(strim($_REQUEST["user_name"])!="")
		{
			$condition .= " and lcr.user_name like '%".strim($_REQUEST["user_name"])."%'";
			$search["user_name"] = strim($_REQUEST["user_name"]);
		}
		
		if(strim($_REQUEST["licai_type"])!=="" && intval($_REQUEST["licai_type"])!=-1)
		{
			$condition .= " and lc.type = ".intval($_REQUEST["licai_type"]);
			$search["licai_type"] = intval($_REQUEST["licai_type"]);
		}
		else
		{
			$search["licai_type"] = -1;
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['begin_time']);
		$end_time = strim($_REQUEST['end_time']);	

		
		if(strim($start_time)!="")
		{
			$condition .= " and lcr.create_date >= '".$start_time."'";
			$search["begin_time"] = $start_time;
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lcr.create_date <= '".  $end_time."'";
			$search["end_time"] = $end_time;
		}
		
		$count = $GLOBALS["db"]->getOne("select count(*) 
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lc.user_id ='".$GLOBALS["user_info"]["id"]."' ".$condition );
				
		$list = $GLOBALS["db"]->getAll("select lcr.*,lc.name as licai_name,lco.begin_interest_date,
		lco.money as all_money,lco.breach_rate,lco.before_breach_rate,lco.interest_rate,lco.licai_id,lco.site_buy_fee as o_site_buy_fee ,lco.redempte_money as o_redempte_money 
		,lc.type from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lc.user_id ='".$GLOBALS["user_info"]["id"]."'  ".$condition." order by lcr.id desc limit ".$limit );
		
		
		foreach($list as $k=>$v)
		{
			//$list[$k]["all_money_format"] = format_price($v["all_money"]);
			$list[$k]["money_format"] = format_price($v["money"]);
			$v["licai_type"] = $v["type"];
			if($v["licai_type"] > 0)
			{
				$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
				
				if($v["type"] == 0 )
				{
					$list[$k]["rate"] = $v["rate"] =  $licai_interest["before_breach_rate"];
					$list[$k]["rata_format"] = number_format($v["rate"],2)."%";
				}
				elseif($v["type"] == 1)
				{
					$list[$k]["rate"] = $v["rate"] =  $licai_interest["breach_rate"];
					$list[$k]["rata_format"] = number_format($v["rate"],2)."%";
				}
				else
				{
					$list[$k]["rate"] = $v["rate"] =  $licai_interest["interest_rate"];
					$list[$k]["rata_format"] = number_format($v["rate"],2)."%";
				}
			}
			else
			{
				$licai_interest = get_licai_interest_yeb($v["licai_id"],$v["begin_interest_date"],$v["create_date"]);
				$list[$k]["rate"] = $v["rate"] =  floatval($licai_interest["avg_interest_rate"]);
				$list[$k]["rata_format"] = number_format($v["rate"],2)."%";
			}
			
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期赎回";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期赎回";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "正常到期赎回";
			}
			
			
			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已赎回"; 
			}
			elseif($v["status"] == 2)
			{
				$list[$k]["status_format"] = "已拒绝"; 
			}
			elseif($v["status"] == 3)
			{
				$list[$k]["status_format"] = "已赎回";
			}
			
			$list[$k]["have_money"] = $v["have_money"] = $v["all_money"] - $v["o_site_buy_fee"] - $v["o_redempte_money"];
			$list[$k]["have_money_format"] = format_price($v["have_money"]);
			
			if($v["licai_type"] == 0)
			{
				$list[$k]["licai_type_format"] = "余额宝";
			}
			elseif($v["licai_type"] == 1)
			{
				$list[$k]["licai_type_format"] = "固定定存";
			}
		}
		
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS["tmpl"]->assign("search",$search);
		$GLOBALS['tmpl']->assign("page_title","赎回管理");
		$GLOBALS['tmpl']->display("licai/licai_uc_redeem_lc.html");
	}
	
	public function uc_redeem_lc_statu(){
		$result = array('status'=>1,'info'=>"");
		
		$deal_name = strim($_REQUEST['deal_name']);
		$user_name = strim($_REQUEST['user_name']);
		
		//项目结束时间
		$start_time = strim($_REQUEST['b_time']);
		$end_time = strim($_REQUEST['e_time']);
		if($deal_name==''&&$user_name==''&&$start_time==''&&$end_time==''){
			$result["status"] = 0;
			$result['info']="搜索条件不能为空";
			ajax_return($result);
		}
		
		
		$d = explode('-',$start_time);
		if (isset($_REQUEST['begin_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		$d = explode('-',$end_time);
		if (isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		ajax_return($result);
		
	}
	
	// 我购买的理财
	public function uc_buyed_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		//$vo["interest_rate_money"] = $GLOBALS["db"]->getOne("select sum(earn_money-fee) from ".DB_PREFIX."licai_redempte where user_id = '".$GLOBALS["user_info"]["id"]."' and status = 1");
		$vo["interest_rate_money"] = $GLOBALS["db"]->getOne("select sum(gross) from ".DB_PREFIX."licai_order where user_id = '".$GLOBALS["user_info"]["id"]."' ");
		
		$vo["interest_rate_money_format"] = format_money_wan($vo["interest_rate_money"]);
		
		$order_info = $GLOBALS["db"]->getRow("select 
		sum(money-site_buy_fee-redempte_money) as have_money,
		count(*) as licai_order_count 
		from ".DB_PREFIX."licai_order lco where user_id = '".$GLOBALS["user_info"]["id"]."' and status in (1,2)");
		
		$vo["licai_order_count"] = intval($order_info["licai_order_count"]);
		
		$vo["have_money_format"] = format_money_wan($order_info["have_money"]);
		
		$vo["total_money_format"] = format_money_wan($vo["interest_rate_money"]+$order_info["have_money"]);
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$condition =" ";
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
		
		$search = array();
		
		
		if(strim($_REQUEST["deal_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["deal_name"])."%'";
			$search["deal_name"] = strim($_REQUEST["deal_name"]);
		}
		
		if(strim($_REQUEST["licai_type"])!=="" && intval($_REQUEST["licai_type"])!=-1)
		{
			$condition .= " and lc.type = ".intval($_REQUEST["licai_type"]);
			$search["licai_type"] = intval($_REQUEST["licai_type"]);
		}
		else
		{
			$search["licai_type"] = -1;
		}
		
		//购买开始时间
		$buy_begin_time = strim($_REQUEST['buy_begin_time']);
		$buy_end_time = strim($_REQUEST['buy_end_time']);

		
		if(strim($buy_begin_time)!="")
		{
			$condition .= " and lco.create_date >= '".strim($buy_begin_time)."'";
			$search["buy_begin_time"] = strim($_REQUEST["buy_begin_time"]);
			
		}
		if(strim($buy_end_time) !="")
		{
			$condition .= " and lco.create_date <= '".  strim($buy_end_time)."'";
			$search["buy_end_time"] = strim($_REQUEST["buy_end_time"]);
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['begin_time']);
		$end_time = strim($_REQUEST['end_time']);

		if(strim($start_time)!="")
		{
			$condition .= " and lco.end_interest_date >= '".$start_time."'";
			$search["begin_time"] = $start_time;
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.end_interest_date <= '".  $end_time."'";
			$search["end_time"] = $end_time;
		}
		
		$count = $GLOBALS["db"]->getOne("select count(*) 
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lco.user_id ='".$GLOBALS["user_info"]["id"]."' ".$condition );
				
		$list = $GLOBALS["db"]->getAll("select lco.*,lc.name as licai_name,lc.time_limit,lc.investment_adviser,lc.begin_buy_date,lc.net_value,lc.type    
		from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		where lco.user_id ='".$GLOBALS["user_info"]["id"]."'  ".$condition." order by lco.id desc limit ".$limit );

		foreach($list as $k=>$v)
		{
			$list[$k]["money_format"] = format_price($v["money"]);
			
			$list[$k]["have_money"] = $v["have_money"] = floatval($v["money"]) - floatval($v["site_buy_fee"]) -floatval($v["redempte_money"]);
			
			$list[$k]["have_money_format"] = format_price($v["have_money"]);
			
			if($v["type"] > 0)
			{
				$list[$k]["before_rate_format"] = number_format($v["before_rate"],2)."%";
				$list[$k]["interest_rate_format"] = number_format($v["interest_rate"],2)."%";
			}
			else
			{
				$list[$k]["before_rate_format"] = "无";
				$licai_intereset = get_licai_interest_yeb($v["licai_id"],$v["begin_interest_date"],$v["end_interest_date"]);
				$list[$k]["interest_rate_format"] = number_format($licai_intereset["avg_interest_rate"],2)."%";
			}
			switch($v["status"])
			{
				case 0 :
					$list[$k]["status_format"] = "未支付";
					break;
				case 1 :
					$list[$k]["status_format"] = "已支付";
					break;
				case 2 :
					$list[$k]["status_format"] = "部分赎回";
					break;
				case 3 :
					$list[$k]["status_format"] = "已完结";
					break;
			}
			if(to_timespan($v["end_interest_date"]) > TIME_UTC)
			{
				$list[$k]["end_status"] = 0;
			}
			else
			{
				$list[$k]["end_status"] = 1;
			}
			
			if($v["type"]==0)
			{
				$list[$k]["type_format"] = "余额宝";
			}
			elseif($v["type"]==1)
			{
				$list[$k]["type_format"] = "固定定存";
			}
			
			$list[$k]["create_time"] = to_date(to_timespan($v["create_time"]),"Y-m-d");
			
			$redempte_money = $GLOBALS["db"]->getRow("select sum(money) from ".DB_PREFIX." where licai_id = ".$v["licai_id"]." and status = 0 and user_id =".$GLOBALS["user_info"]["id"]);
			if($v["have_money"] <= $redempte_money)
			{
				$list[$k]["money_over"] = 1 ;
			}
			else
			{
				$list[$k]["money_over"] = 0 ;
			}
			//$list[$k] = licai_item_format($v);
		}
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS["tmpl"]->assign("search",$search);
		$GLOBALS['tmpl']->assign("page_title","购买的理财");
		$GLOBALS['tmpl']->display("licai/licai_uc_buyed_lc.html");
	}
	
	public function uc_buyed_lc_status(){

		$result = array('status'=>1,'info'=>"");
		
		//购买开始时间
		$buy_begin_time = strim($_REQUEST['b_b_time']);
		$buy_end_time = strim($_REQUEST['b_e_time']);
		$d = explode('-',$buy_begin_time);
		if (isset($_REQUEST['buy_begin_time']) && $buy_begin_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$buy_begin_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		$d = explode('-',$buy_end_time);
		if ( isset($_REQUEST['buy_end_time']) && strim($buy_end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$buy_end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($buy_begin_time!="" && strim($buy_end_time) !="" && to_timespan($buy_begin_time) > to_timespan($buy_end_time)){
			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		
		//项目结束时间
		$start_time = strim($_REQUEST['b_time']);
		$end_time = strim($_REQUEST['e_time']);
		
		$d = explode('-',$start_time);
		if (isset($_REQUEST['begin_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$result["status"] = 0;
			$result['info']="结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)";
			ajax_return($result);
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){

			$result["status"] = 0;
			$result['info']="开始时间不能大于结束时间";
			ajax_return($result);
		}
		ajax_return($result);
	}
	
	// 我购买的理财详情
	public function uc_buyed_deal_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			showErr("操作失败，请重试");
		}
		
		$vo = $GLOBALS["db"]->getRow("select lc.*,lco.id as order_id from ".DB_PREFIX."licai lc left join ".DB_PREFIX."licai_order lco on lc.id = lco.licai_id where lco.id=".$id." and lco.user_id = ".$GLOBALS["user_info"]["id"]);
		
		$vo["average_income_rate_format"] = $vo["average_income_rate"]."%";
		
		$vo_info =  $GLOBALS["db"]->getRow("select count(*) as licai_total_count,
		sum(case when lcr.status = 1 then (lcr.money+lcr.earn_money-lcr.fee) else 0 end) as licai_all_redempte,
		sum(case when lcr.status = 0 then lcr.money else 0 end) as licai_ing_money,
		sum(case when lcr.status = 1 then lcr.earn_money-lcr.fee else 0 end) as total_earn_money  
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lcr.user_id = ".$GLOBALS["user_info"]["id"]." and lcr.status in (0,1) and lco.id=".$id); 
		
		//请求总数
		$vo["licai_total_count"] = $vo_info["licai_total_count"];
		//已赎回总额
		$vo["licai_all_redempte_format"] = format_money_wan($vo_info["licai_all_redempte"]);
		//收益
		$vo["total_earn_money_format"] = format_price($vo_info["total_earn_money"]);
		//进行中的
		$vo["licai_ing_money_format"] = format_money_wan($vo_info["licai_ing_money"]);
		

		$list = $GLOBALS["db"]->getAll("select lcr.* from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		where lcr.order_id = ".$id." order by id desc");
		
		foreach($list as $k=>$v)
		{
			$list[$k]["money_format"] = format_money_wan($v["money"]);
			$list[$k]["earn_money_format"] = format_price($v["earn_money"]);
			$list[$k]["fee_format"] = format_price($v["fee"]);
			$list[$k]["real_money"] = $v["real_money"] = $v["money"]+ $v["earn_money"] - $v["fee"];
			$list[$k]["real_money_format"] = format_money_wan($v["real_money"]);
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "已结清";
			}
			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$list[$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$list[$k]["status_format"] = "已取消";
			}
		}
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS['tmpl']->assign("page_title","购买的理财详情");
		$GLOBALS['tmpl']->display("licai/licai_uc_buyed_deal_lc.html");
	}
	
	public function uc_buyed_lc_list(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
//		$id = intval($_REQUEST["id"]);
//		if(!$id)
//		{
//			showErr("操作失败，请重试");
//		}
		
		$vo = $GLOBALS["db"]->getRow("select lc.*,lco.id as order_id from ".DB_PREFIX."licai lc left join ".DB_PREFIX."licai_order lco on lc.id = lco.licai_id where   lco.user_id = ".$GLOBALS["user_info"]["id"]);
		
		$vo["average_income_rate_format"] = $vo["average_income_rate"]."%";
		
		$vo_info =  $GLOBALS["db"]->getRow("select count(*) as licai_total_count,
		sum(case when lcr.status = 1 then (lcr.money+lcr.earn_money-lcr.fee) else 0 end) as licai_all_redempte,
		sum(case when lcr.status = 0 then lcr.money else 0 end) as licai_ing_money,
		sum(case when lcr.status = 1 then lcr.earn_money-lcr.fee else 0 end) as total_earn_money  
		from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lcr.user_id = ".$GLOBALS["user_info"]["id"]." and lcr.status in (0,1)  "); 
		
		//请求总数
		$vo["licai_total_count"] = $vo_info["licai_total_count"];
		//已赎回总额
		$vo["licai_all_redempte_format"] = format_money_wan($vo_info["licai_all_redempte"]);
		//收益
		$vo["total_earn_money_format"] = format_price($vo_info["total_earn_money"]);
		//进行中的
		$vo["licai_ing_money_format"] = format_money_wan($vo_info["licai_ing_money"]);
		

		$list = $GLOBALS["db"]->getAll("select lcr.*,lci.name as licai_name from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id left join  ".DB_PREFIX."licai lci on lci.id = lco.licai_id
		where lcr.user_id = ".$GLOBALS["user_info"]["id"]." order by id desc");
		
		foreach($list as $k=>$v)
		{
			$list[$k]["money_format"] = format_money_wan($v["money"]);
			$list[$k]["earn_money_format"] = format_price($v["earn_money"]);
			$list[$k]["fee_format"] = format_price($v["fee"]);
			$list[$k]["real_money"] = $v["real_money"] = $v["money"]+ $v["earn_money"] - $v["fee"];
			$list[$k]["real_money_format"] = format_money_wan($v["real_money"]);
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "已结清";
			}
			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$list[$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$list[$k]["status_format"] = "取消赎回";
			}
		}
		
		$GLOBALS["tmpl"]->assign("vo",$vo);
		
		$GLOBALS["tmpl"]->assign("list",$list);
		
		$GLOBALS['tmpl']->display("licai/licai_uc_buyed_deal_lc_list.html");
	}
	
	// 申请赎回
	public function uc_redeem(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			showErr("操作失败，请重试");
		}
		
		$vo = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai_order where user_id =".$GLOBALS["user_info"]["id"]." and id=".$id);
		
		if(!$vo)
		{
			showErr("操作失败，请重试");
		}
		
		if(to_timespan($vo["end_interest_date"]) <= TIME_UTC)
		{
			showErr("等待发起人发放理财");
		}
		
		//申请了 还未赎回
		$vo["redempte_wait_pay"] = $GLOBALS["db"]->getOne("select sum(money) from ".DB_PREFIX."licai_redempte where status = 0 and user_id =".$GLOBALS["user_info"]["id"]." and order_id = ".$id);
		
		$vo["redempte_wait_pay_format"] = format_money_wan($vo["redempte_wait_pay"]);
		
		if(floatval($vo["redempte_money"])>=floatval($vo["money"]-$vo["site_buy_fee"])) 
		{
			showErr("已无可赎回金额");
		}
		
		
		
		$vo["purchasing_time"] = $GLOBALS["db"]->getOne("select lc.purchasing_time from ".DB_PREFIX."licai lc 
		left join ".DB_PREFIX."licai_order lco on lco.licai_id = lc.id 
		left join ".DB_PREFIX."licai_redempte lcr on lcr.order_id = lco.id where lcr.id=".$id);
		
		$vo["back_rate_format"] = $vo["back_rate"]."%";
		
		//$vo["back_interest_money"] = format_price($vo["money"]*$vo["back_rate"]);
		//持有金额 包括未赎回
		$vo["money_format"] = format_money_wan($vo["money"] - $vo["site_buy_fee"]-$vo["redempte_money"]);
		//可赎回金额
		$vo["have_money"] = $vo["money"] - $vo["redempte_money"] - $vo["site_buy_fee"] - $vo["redempte_wait_pay"];
		
		$vo["have_money_format"] = format_money_wan($vo["have_money"]);
		
		$vo["have_money"] = $vo["have_money"]/10000;
		
		//$vo["money_format"] = format_price($vo["money"]);
		
		//json
		$licai = get_licai($vo["licai_id"]);
		
		if(!$licai || $licai['status'] == 0)
			showErr("理财产品不存在");
		$GLOBALS['tmpl']->assign("licai",$licai);
		
		if($licai['type'] > 0){
			$licai_interest_json = json_encode($licai['licai_interest']);
		}
		else{
			$licai_interest_json = $licai['average_income_rate'];
		}
		
		//理财状态
		$now=get_gmtime();
		
		$vo['before_interest_date']=to_timespan($vo['before_interest_date']);
		$vo['before_interest_enddate']=to_timespan($vo['before_interest_enddate']);
		
		$vo['begin_interest_date']=to_timespan($vo['begin_interest_date']);
		$vo['end_interest_date']=to_timespan($vo['end_interest_date']);
		
		$vo["create_time"] = to_timespan($vo["create_time"]);
		
		//未开始
		if($vo['before_interest_date']>$now)
		{
			$vo["licai_status"] = 0;
			$vo["before_days"] = 0;
			$vo["days"] = 0;
		}
		
		if($vo['before_interest_date']<$now&&$vo['before_interest_enddate']>$now){
			//小于起息时间，就是预热期就赎回
			$vo["licai_status"] = 0;
			$day=intval(($now-$vo['before_interest_date'])/24/3600);
			if($day<=0){
				$day=0;
			} 
			$vo["before_days"] = $day;
			$vo["days"] = 0;
			
		}elseif($vo['before_interest_enddate']<=$now&&$vo['begin_interest_date']>$now){
			//完成预期期间，未进入正式起息时间
			$vo["licai_status"] = 1;
			$day=intval(($now-$vo['before_interest_date'])/24/3600);
			if($day<=0){
				$day=0;
			}
			$vo["before_days"] = $day;
			$vo["days"] = 0;
			 
		}elseif($vo['begin_interest_date']<=$now&&$vo['end_interest_date']>$now){
			//进入正式起息时间,违约
			$vo["licai_status"] = 1;
			$vo["before_days"] = intval(($vo['before_interest_enddate']-$vo['before_interest_date'])/24/3600);
			if($vo["before_days"]<=0){
				$vo["before_days"]=0;
			}
			
			
			$day=intval(($now-$vo['begin_interest_date'])/24/3600);
			if($day<=0){
				$day=0;
			}
			
			$vo["days"] = $day;	
					
		}elseif($vo['end_interest_date']<=$now){
			//正常结束
			$vo["licai_status"] = 2;

			$vo["before_days"] = intval(($vo['before_interest_enddate']-$vo['before_interest_date'])/24/3600);
			
			if($vo["before_days"]<0)
			{
				$vo["before_days"] = 0;
			}
			$vo["days"] = intval(($vo['end_interest_date']-$vo['begin_interest_date'])/24/3600);
			if($vo["days"]<0)
			{
				$vo["days"] = 0;
			}
		}
		
		//
		if($vo["licai_status"] == 0)
		{
			$vo["back_status_format"] = "预热期提前";
			//$vo["back_rate_format"] = ;
			
		}
		elseif($vo["licai_status"] == 1)
		{
			$vo["back_status_format"] = "理财期提前";
			//$vo["back_rate_format"] = ;
		}
		else
		{
			$vo["back_status_format"] = "理财结束";
			//$vo["back_rate_format"] = ;
		}
		
		$GLOBALS['tmpl']->assign("licai_interest_json",$licai_interest_json);
		//json end
		
		$GLOBALS['tmpl']->assign("vo",$vo);

		$GLOBALS['tmpl']->display("licai/licai_uc_redeem.html");
	}
	//理财申请
	function uc_redeem_add()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		$redeem_money = floatval($_REQUEST["redeem_money"])*10000;
		$paypassword = strim($_REQUEST["paypassword"]);
		
		$result["jump"] = "";
		
		if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
			$result["status"] = 0;
			$result["info"] = "付款密码错误";
			ajax_return($result);	
		}
		
		$result = create_redempte($GLOBALS["user_info"]["id"],$id,$redeem_money);
		
		$result["jump"] = url("licai#uc_buyed_lc");

		ajax_return($result);	
		
	}
	// 我发起的理财详情
	public function uc_deal_lc(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		$vo = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where id=".$id);
		
		$vo = licai_item_format($vo);
		
		if($vo.type > 0){
			$list = $GLOBALS["db"]->getAll("select * from ".DB_PREFIX."licai_interest where licai_id=".$id." order by id asc ");

			foreach($list as $k => $v)
			{
				$list[$k] = interest_item_format($v);
				$list[$k]["min_money_format"] = format_money_wan($list[$k]["min_money"]);
				$list[$k]["max_money_format"] = format_money_wan($list[$k]["max_money"]);
			}
		}else{
		
			$list = $GLOBALS["db"]->getAll("select * from ".DB_PREFIX."licai_history where licai_id=".$id." order by history_date asc ");
			foreach($list as $k => $v)
			{
				$list[$k] = interest_item_format($v);
				$list[$k]["net_value_format"] = format_money_wan($list[$k]["net_value"]);
				$list[$k]["rate_format"] = number_format($v["rate"],4)."%";
				
			}
		}
		

		$GLOBALS["tmpl"] -> assign("vo",$vo);
		$GLOBALS["tmpl"] -> assign("list",$list);
		$GLOBALS['tmpl']->assign("page_title","发起的理财详情");
		$GLOBALS['tmpl']->display("licai/licai_uc_deal_lc.html");
	}
	//赎回管理
	public function uc_redeem_lc_status()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		
		$vo =  $GLOBALS["db"]->getRow("select lcr.* from ".DB_PREFIX."licai_redempte lcr  
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lc.id= lco.licai_id  
		where lcr.id =".$id." and lc.user_id =".$GLOBALS["user_info"]["id"]);
		
		$vo["money_format"] = format_money_wan($vo["money"]);
		
		$vo["organiser_fee_format"] = format_price($vo["organiser_fee"]);
		
		$GLOBALS['tmpl']->assign("vo",$vo);
		
		$GLOBALS['tmpl']->display("licai/licai_uc_redeem_lc_status.html");
	}
	//赎回更新
	public function set_redeem_lc_status()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$result["jump"] = "";
		
		$redempte_id = intval($_REQUEST["redempte_id"]);
		$status = 1;
		$earn_money = strim($_REQUEST["earn_money"]);
		$fee = strim($_REQUEST["fee"]);
		$pay_type = 0; //0不允许垫付
		$web_type = 2; //0前台
		
		$redempte_info = $GLOBALS['db']->getRow("select lcr.* from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lco.id = lcr.order_id 
		left join ".DB_PREFIX."licai lc on lc.id = lco.licai_id 
		where lcr.id =".$redempte_id." and lc.user_id = ".$GLOBALS["user_info"]["id"]);

		if(!$redempte_info)
		{
			$result["status"] = 0;
			$reuslt["info"] = "操作失败，请重试";
			ajax_return($result);
		}
		
		$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$redempte_info["organiser_fee"],$pay_type,$web_type);
		
		//修改状态
		if($result["status"] != 1)
		{
			$result["jump"] = "";
			ajax_return($result);
		}
		
		$result["jump"] = url("licai#uc_redeem_lc");
		
		$result["info"] = "操作成功!";
		
		ajax_return($result);
	}
	//到期赎回管理
	public function uc_expire_status()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST["id"]);
		
		$vo =  $GLOBALS["db"]->getRow("select lco.*,lc.type from ".DB_PREFIX."licai_order lco
		left join ".DB_PREFIX."licai lc on lc.id= lco.licai_id  
		where lco.id =".$id." and lc.user_id = ".$GLOBALS["user_info"]["id"]);
		
		$vo['before_interest_enddate'] = to_timespan($vo['before_interest_enddate']);
		$vo['before_interest_date'] = to_timespan($vo['before_interest_date']);
		$vo['end_interest_date'] = to_timespan($vo['end_interest_date']);
		$vo['begin_interest_date'] = to_timespan($vo['begin_interest_date']);
		
		$vo["money"] = $vo["money"]-$vo['redempte_money']-$vo["site_buy_fee"];
		
		$money = $vo["money"];
		
		$vo["money_format"] = format_money_wan($vo["money"]);
		
		if($vo["type"] > 0)
		{
			$licai_interest=get_licai_interest($vo['licai_id'],$money);
			
			$day_before=intval(($vo['before_interest_enddate']-$vo['before_interest_date'])/24/3600);
		
			$before_earn_money=$money*$day_before*$licai_interest['before_rate']*0.01/365;
			
			$day_begin=intval(($vo['end_interest_date']-$vo['begin_interest_date'])/24/3600);
			
			$begin_earn_money=$money*$day_begin*$licai_interest['interest_rate']*0.01/365;
		}
		else
		{
			$vo["begin_interest_date"] = date('Y-m-d',$vo["begin_interest_date"]);
			$vo["end_interest_date"] = date('Y-m-d',$vo["end_interest_date"]);
			$licai_interest = get_licai_interest_yeb($vo['licai_id'],$vo["begin_interest_date"],$vo["end_interest_date"]);
				 
		}

		if($vo["type"] > 0){
			$vo['earn_money']= round($before_earn_money+$begin_earn_money,2);
			$vo['fee']= round($money*($day_before+$day_begin)*$licai_interest['redemption_fee_rate']*0.01/365,2);
			$vo['organiser_fee']= round($money*($day_before+$day_begin)*$licai_interest['platform_rate']*0.01/365,2);
		}else{
			$vo['earn_money']=round($money*$licai_interest['interest_rate']*0.01/365,2);
			$vo['fee']=round($money*$licai_interest['days']*$licai_interest['redemption_fee_rate']*0.01/365,2);;
			$vo['organiser_fee']= round($money*$licai_interest['days']*$licai_interest['platform_rate']*0.01/365,2);
		}
	
		$GLOBALS['tmpl']->assign("vo",$vo);
		
		$GLOBALS['tmpl']->display("licai/licai_uc_expire_status.html");
	}
	//到期赎回更新
	public function set_status()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$result["jump"] = url("licai#uc_expire_lc");
		
		$id = intval($_REQUEST["id"]);
		$status = 1;
		$earn_money = strim($_REQUEST["earn_money"]);
		$fee = strim($_REQUEST["fee"]);
		$pay_type = 0; //0不允许垫付
		$web_type = 2; //0前台
		
		$licai_order = $GLOBALS["db"]->getRow("select lco.*,u.user_name from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		left join ".DB_PREFIX."user u on u.id = lco.user_id 
		where lco.id=".$id." and lc.user_id = ".$GLOBALS["user_info"]["id"]);
		
		if(!$licai_order)
		{
			$result["status"] = 0;
			$result["info"] = "操作失败，请重试";
			ajax_return($result);
		}
		
		$redempte = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."licai_redempte 
		where status = 0 and order_id =".$id." and type = 2");
		
		$redempte_id = $redempte["id"];
		
		if(!$redempte)
		{
			$licai_redempte_data = array();
			
			$licai_order['before_interest_enddate'] = to_timespan($licai_order['before_interest_enddate']);
			$licai_order['before_interest_date'] = to_timespan($licai_order['before_interest_date']);
			$licai_order['begin_interest_date'] = to_timespan($licai_order['begin_interest_date']);
			$licai_order['end_interest_date'] = to_timespan($licai_order['end_interest_date']);

			$money = $licai_redempte_data["money"] = $licai_order["money"] - $licai_order["redempte_money"] - $licai_order["site_buy_fee"];
			$licai_redempte_data["create_date"] = to_date(TIME_UTC);
			$licai_redempte_data["order_id"] = $licai_order["id"];
			$licai_redempte_data["user_id"] = $licai_order["user_id"];
			$licai_redempte_data["user_name"] = $licai_order["user_name"];
			$licai_redempte_data["status"] = 0;
			$licai_redempte_data["type"] = 2;	
			$licai_interest = get_licai_interest($licai_order["licai_id"],$money);
			
			$day_before=intval(($licai_order['before_interest_enddate']-$licai_order['before_interest_date'])/24/3600);
			
			if($day_before < 0)
			{
				$day_before = 0;
			}
			
			$before_earn_money=$licai_order["money"]*$day_before*$licai_interest['before_rate']*0.01/365;
			
			$day_begin=intval(($licai_order['end_interest_date']-$licai_order['begin_interest_date'])/24/3600);
			
			if($day_begin < 0)
			{
				$day_begin = 0;
			}
			
			$begin_earn_money=$licai_order["money"]*$day_begin*$licai_interest['interest_rate']*0.01/365;

			$licai_redempte_data["organiser_fee"] = $licai_interest["platform_rate"] * $money * ($day_before+$day_begin)/100/365;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."licai_redempte",$licai_redempte_data,"INSERT");
			
			$redempte_id = $GLOBALS['db']->insert_id();
			
			$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$licai_redempte_data["organiser_fee"],$pay_type,$web_type);
			
		}
		else
		{
			
			$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$redempte["organiser_fee"],$pay_type,$web_type);
		}
		
		$result["jump"] = url("licai#uc_expire_lc");
		//修改状态
		if($result["status"] != 1)
		{
			ajax_return($result);
		}
		
		$result["info"] = "操作成功!";		
		ajax_return($result);
	}
	
	public function uc_buyed_deal_cancel()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$redempte_id = intval($_REQUEST["redempte_id"]);
		$redempte_info = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai_redempte where id =".$redempte_id." and status=0 and user_id =".$GLOBALS["user_info"]["id"]);
		
		if(!$redempte_info)
		{
			$result["status"] = 0;
			$reuslt["info"] = "操作失败，请重试";
			ajax_return($result);
		}
		
		$status = 3;
		
		$earn_money = 0;
		$fee = 0;
		$organiser_fee = 0;
		$pay_type = 0;
		$web_type = 0;
		
		
		$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$organiser_fee,$pay_type,$web_type);
		
		$result["jump"] = url("licai#uc_expire_lc");
		
		ajax_return($result);
	}
	//余额宝
	function uc_yeb_lc()
	{

		//require_once APP_ROOT_PATH.'app/Lib/uc.php';
		
		$id = intval($_REQUEST["id"]);
		
		if(!$id)
		{
			$result["status"] = 0;
			$reuslt["info"] = "操作失败，请重试";
			ajax_return($result);
		}
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0) $page = 1;		
		$limit = ($page-1)*$page_size.",".$page_size;
				
		
		$vo = $GLOBALS["db"]->getRow("select lco.*,lc.name from ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lc.id=lco.licai_id where lco.id=".$id);
		

		
		$vo["have_money"] = $vo["money"]-$vo["redempte_money"]-$vo["site_buy_fee"];
		
		$list = $GLOBALS["db"]->getAll("select history_date,rate,net_value,rate/100/365*".$vo["have_money"]." as money from ".DB_PREFIX."licai_history where licai_id=".$vo["licai_id"]." and history_date >='".$vo["begin_interest_date"]."' and history_date <='".$vo["end_interest_date"]."' order by history_date desc ");

		$vo["interest_money"] = $GLOBALS["db"]->getOne("select sum(rate/100/365*".$vo["have_money"].") from ".DB_PREFIX."licai_history where licai_id=".$vo["licai_id"]." and history_date >='".$vo["begin_interest_date"]."' and history_date <='".$vo["end_interest_date"]."'");
		
		$count = $GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."licai_history where licai_id=".$vo["licai_id"]." and history_date >='".$vo["begin_interest_date"]."' and history_date <='".$vo["end_interest_date"]."'");;
		
		$vo["have_money_format"] = format_price($vo["have_money"]);
		
		$vo["site_buy_fee_format"] = format_price($vo["site_buy_fee"]);
		
		/*foreach($list as $k=>$v)
		{
			$list[$k]["money"] = $v["rate"]*($vo["have_money"]);
		}*/
		
		$vo["interest_money_format"] = format_price($vo["interest_money"]);

		foreach($list as $k => $v)
		{
			$list[$k]["net_value_format"] = format_price($v["net_value"]);
			$list[$k]["money_format"] = format_price($v["money"]);
		}
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();

		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS["tmpl"]->assign("list",$list);
		$GLOBALS["tmpl"]->assign("vo",$vo);
		$GLOBALS['tmpl']->display("licai/licai_uc_yeb_lc.html");
	}
	//发起理财
	function licai_create()
	{
		require_once APP_ROOT_PATH.'app/Lib/uc.php';
		
		$fund_brand = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_brand where status = 1 ");
		$GLOBALS["tmpl"]->assign("fund_brand",$fund_brand);
		
		$fund_type = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_type where status = 1 ");
		$GLOBALS["tmpl"]->assign("fund_type",$fund_type);
		
		$GLOBALS['tmpl']->display("licai/licai_create.html");
	}
	//保存理财
	function save_create()
	{
		require_once APP_ROOT_PATH.'app/Lib/uc.php';
		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$order = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where user_id =".$GLOBALS["user_info"]["id"]." and status =0 and verify = 0");
		
		if($order)
		{
			showErr("您已经有申请的理财在审核，请耐心等待");
		}
		
		$data = array();
		$data["name"] = $_REQUEST["name"];
		$lc_sn = $GLOBALS["db"]->getOne("select max(id) from ".DB_PREFIX."licai");
		$data['sort'] = $lc_sn+1;
		$data["licai_sn"] = "LC".to_date(TIME_UTC,"Y")."".str_pad($lc_sn+1,7,0,STR_PAD_LEFT);
		$data["user_id"] = intval($GLOBALS['user_info']["id"]);
		$data['img'] = strim($_REQUEST['img']);
		
		$data['begin_buy_date'] = strim($_REQUEST['begin_buy_date']);
		$data['end_buy_date'] = strim($_REQUEST['end_buy_date']);
		$data['begin_interest_date'] = strim($_REQUEST['begin_interest_date']);
		$data['end_date'] = strim($_REQUEST['end_date']);
		$data['min_money'] = floatval($_REQUEST['min_money']);
		$data['max_money'] = floatval($_REQUEST['max_money']);
		
		$data['scope'] = strim($_REQUEST['scope']);
		
		$data['profit_way'] = strim($_REQUEST['profit_way']);
		
		$data['time_limit'] = intval($_REQUEST['time_limit']);
		
		$data['begin_interest_type'] = intval($_REQUEST['begin_interest_type']);
		
		$data['product_size'] = strim($_REQUEST['product_size']);
		
		$data['type'] = intval($_REQUEST['type']);
		
		$data['status'] = 0;
		
		$data['purchasing_time'] = strim($_REQUEST['purchasing_time']);

		$data['description'] = replace_public(btrim($_REQUEST['description']));
    	$data['description'] = valid_tag($data['description']);
		
		$data['brief'] = replace_public(btrim($_REQUEST['brief']));
    	$data['brief'] = valid_tag($data['brief']);

		$data['rule_info'] = replace_public(btrim($_REQUEST['rule_info']));
    	$data['rule_info'] = valid_tag($data['rule_info']);
		
		$data['net_value'] = strim($_REQUEST['net_value']);
		$data['fund_key'] = strim($_REQUEST['fund_key']);
		$data['fund_type_id'] = intval($_REQUEST['fund_type_id']);
		$data['fund_brand_id'] = intval($_REQUEST['fund_brand_id']);
		
		
		//$data['risk_rank'] = intval($_REQUEST['risk_rank']); //风险等级
		
		$data['verify'] = 0;
		
		if($data['name']=="")
		{
			showErr("请输入名称");
		}
		if($data['begin_buy_date'] == "" || $data['begin_buy_date'] == '00000000')
		{
			showErr("请选择理财开始购买时间");
		}
		if($data['max_money'] == 0)
		{
			showErr("单笔最大购买限额");
		}
		//余额宝
		if($data['type'] == 0)
		{
			
			if($data['end_date'] == "" || $data['end_date'] == '00000000')
			{
				showErr("请选择理财结束时间");
			}
		}
		//定存宝
		else
		{
			if($data['begin_interest_date'] == ""|| $data['begin_interest_date'] == '00000000')
			{
				showErr("请选择起息时间");
			}
			if($data['time_limit'] && ($data['end_date'] == ""|| $data['end_date'] == '00000000'))
			{
				showErr("项目结束时间和理财期限至少填写一个");
			}
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX."licai",$data,"INSERT");
		
		
		showSuccess("提交成功，等待管理员审核",0,url("index","licai#uc_published_lc"));

	}
	
	//详细信息
	function deal_detail(){
		$id = intval($_REQUEST['id']);
		$licai = get_licai($id);

		$licai["fund_brand_name"] = $GLOBALS["db"]->getOne("select name from ".DB_PREFIX."licai_fund_brand where id =".$licai["fund_brand_id"]);
		
		if(!$licai || $licai['status'] == 0)
			showErr("理财产品不存在");
		
		$GLOBALS['tmpl']->assign("licai",$licai);
		$GLOBALS['tmpl']->assign("page_title","图文介绍");
		$GLOBALS['tmpl']->display("licai/licai_deal_detail.html");
	}
	//冻结金明细
	function mortgage_money(){
		$GLOBALS['tmpl']->assign("page_title","冻结金明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and money <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and money>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and money>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and money<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and money<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		$condition.=" and type=44  ";
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])."   $condition order by log_time desc,id desc limit ".$limit);
 		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])."  $condition ");
 		 
 		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($log_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('list',$log_list);

		$GLOBALS['tmpl']->assign('mortgage_money',$GLOBALS['user_info']['mortgage_money']);
		$GLOBALS['tmpl']->display("licai/licai_uc_mortgage_money.html");
	}
}
?>