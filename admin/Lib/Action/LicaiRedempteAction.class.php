<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'system/libs/licai.php';
require APP_ROOT_PATH.'system/libs/user.php';
define("TIME_UTC",get_gmtime());   //当前UTC时间戳
class LicaiRedempteAction extends CommonAction{
	public function index()
	{	
		$condition = " and lcr.type = 1 and lcr.status in (0,1,2,3) ";
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$r_id = intval($_REQUEST["id"]);
		if($r_id)
		{
			$condition = " and lcr.id =".$r_id;
		}
		
		
		if(strim($_REQUEST["p_name"]) != "")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition .= " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
		}
		
		$start_time = strim($_REQUEST['start_time']);
		$end_time = strim($_REQUEST['end_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['start_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)");
			exit;
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)");
			exit;
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$this->error('开始时间不能大于结束时间:'.$start_time.'至'.$end_time);
			exit;
		}
		if(strim($start_time)!="")
		{
			$condition .= " and lcr.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lcr.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id" && strim($_REQUEST['_order']) != "user_name")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "show_rate_format" ||$order == "breach_money_format" ||  $order == "fee_money_format")
				{
					$order = "";
				}
			}
			else
			{
				$order = "lcr.".strim($_REQUEST ['_order']);
			}			
		} else {
			$order = " lcr.id ";
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])){
			$sort = strim($_REQUEST ['_sort']) ? 'asc' : 'desc';
		} else {
			$sort = 'desc';
		}
		
		$sortImg = $sort; //排序图标
		$sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
		
		if($order == "")
		{
			$order_str = "";
		}
		else
		{
			$order_str = " order by ". str_replace("_format","",$order)." ".$sort;
		}
				
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 20;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();

		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);

		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lcr.*,lco.user_name,lco.licai_id,lco.money as order_money, 
			lc.type as licai_type, lc.name ,
			lco.before_interest_date,lco.begin_interest_date,lco.before_interest_enddate,lco.site_buy_fee,
			lco.redempte_money ,lco.money-lco.site_buy_fee-lco.redempte_money as have_money 
			FROM ".DB_PREFIX."licai_redempte lcr 
			left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
			left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace("lcr.","",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			switch($v["licai_type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				case 2: $result["list"][$k]["type_format"] = "浮动定存";
					break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			//0表示未赎回 1表示已赎回 2表示拒绝
			if($v["status"] == 0)
			{
				$result["list"][$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$result["list"][$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$result["list"][$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已取消";
			}
			//持有金额
			//$v["have_money"] = $v["order_money"] - $v["site_buy_fee"] - $v["redempte_money"];
			
			$result["list"][$k]["have_money_format"] = format_money_wan($v["have_money"]);
			
			$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
			$v["type"] = $v["licai_type"];
			if($v["type"] == 0)
			{	//余额宝收益率
				//$v["show_rate"] = $licai_interest["before_breach_rate"];
				$licai_interest = get_licai_interest_yeb($v['licai_id'],$v["begin_interest_date"],$v["create_date"]);
				$v["show_rate"] = $licai_interest["avg_interest_rate"];				
			}
			elseif($v["type"] == 1)
			{
				$v["show_rate"] = $licai_interest["breach_rate"];
			}
			elseif($v["type"] == 2)
			{
				$v["show_rate"] = $licai_interest["interest_rate"];
			}
			
			$result["list"][$k]["show_rate_format"] = $v["show_rate"]."%";
			//预热期时间
			$before_days = (to_timespan($v["before_interest_enddate"]) - to_timespan($v["before_interest_date"]))/24/3600;
			if($before_days < 0)
			{
				$before_days = 0;
			}
			//理财期时间
			$days = (to_timespan($v["create_date"]) - to_timespan($v["begin_interest_date"]))/24/3600;
			if($days < 0)
			{
				$days = 0;
			}
			
			$v['before_reach_money'] = $licai_interest["before_rate"] / 100 / 365 *$v["money"] * $before_days;
			
			$v['breach_money'] = $v["show_rate"] / 100 / 365 *$v["money"] * $days;
			
			$v["breach_money"] = $v['before_reach_money'] + $v['breach_money'];
			
			$result["list"][$k]["breach_money_format"] = format_price($v["breach_money"]);
			//手续费
			$result["list"][$k]["fee_money"] = $v["fee_money"] = $licai_interest["redemption_fee_rate"] /100 / 365 *$v["money"] * ($before_days + $days);
			
			$result["list"][$k]["fee_money_format"] = format_price($v["fee_money"]); 
			//赎回金额
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);

		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"理财赎回管理");
		$this->display ();
	}
	public function before_index()
	{	
		$condition = " and lcr.type = 0 or lcr.type = 3 and lcr.status in (0,1,2,3) ";
		
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$r_id = intval($_REQUEST["id"]);
		if($r_id)
		{
			$condition = " and lcr.id =".$r_id;
		}
		
		if(strim($_REQUEST["p_name"]) != "")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition .= " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
		}
		
		$start_time = strim($_REQUEST['start_time']);
		$end_time = strim($_REQUEST['end_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['start_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)");
			exit;
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)");
			exit;
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$this->error('开始时间不能大于结束时间:'.$start_time.'至'.$end_time);
			exit;
		}
		if(strim($start_time)!="")
		{
			$condition .= " and lcr.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lcr.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id" && strim($_REQUEST['_order']) != "user_name")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "show_rate_format" ||$order == "breach_money_format" ||  $order == "fee_money_format")
				{
					$order = "";
				}
			}
			else
			{
				$order = "lcr.".strim($_REQUEST ['_order']);
			}			
		} else {
			$order = " lcr.id ";
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])){
			$sort = strim($_REQUEST ['_sort']) ? 'asc' : 'desc';
		} else {
			$sort = 'desc';
		}
		
		$sortImg = $sort; //排序图标
		$sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
		
		if($order == "")
		{
			$order_str = "";
		}
		else
		{
			$order_str = " order by ". str_replace("_format","",$order)." ".$sort;
		}
				
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 20;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();

		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lcr.*,lco.user_name,lco.licai_id,lco.money as order_money, 
			lc.type as licai_type, lc.name ,lco.site_buy_fee,lco.before_interest_date,lco.redempte_money
			 ,lco.money-lco.site_buy_fee-lco.redempte_money as have_money   
			FROM ".DB_PREFIX."licai_redempte lcr 
			left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
			left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace("lcr.","",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			switch($v["licai_type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				case 2: $result["list"][$k]["type_format"] = "浮动定存";
					break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			//0表示未赎回 1表示已赎回 2表示拒绝
			if($v["status"] == 0)
			{
				$result["list"][$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$result["list"][$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$result["list"][$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已取消";
			}
			
			//持有金额
			$v["have_money"] = $v["order_money"] - $v["site_buy_fee"] - $v["redempte_money"];
			
			$result["list"][$k]["have_money_format"] = format_money_wan($v["have_money"]);
			
			$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
			if($v["type"] == 3){
				$v["show_rate"] = $licai_interest["before_rate"];		
			}else{
				$v["show_rate"] = $licai_interest["before_breach_rate"];
			}
			
			$result["list"][$k]["show_rate_format"] = $v["show_rate"]."%";
			
			$days = (to_timespan($v["create_date"]) - to_timespan($v["before_interest_date"]))/24/3600;
			if($days < 0)
			{
				$days = 0;
			}
			
			$v['before_reach_money'] = $v["show_rate"] / 100 / 365 *$v["money"] * $days;

			$v["breach_money"] = $v['before_reach_money'];
			
			$result["list"][$k]["breach_money_format"] = format_price($v["breach_money"]);
			//手续费
			$result["list"][$k]["fee_money"] = $v["fee_money"] = $licai_interest["redemption_fee_rate"] /100 / 365 *$v["money"] * $days;
			
			$result["list"][$k]["fee_money_format"] = format_price($v["fee_money"]); 
			//赎回金额
			$result["list"][$k]["money_format"] = format_price($v["money"]);
			
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);

		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"预热期赎回管理");
		$this->display ();
	}
	public function export_csv($page = 1)
	{	
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		$id = intval($_REQUEST["id"]);
		
		$condition = " and lcr.type = 1 and lcr.status in (0,1,2,3) ";
		
		if(strim($_REQUEST["p_name"]) != "")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition .= " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
		}
		
		$start_time = strim($_REQUEST['start_time']);
		$end_time = strim($_REQUEST['end_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['start_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)");
			exit;
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)");
			exit;
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$this->error('开始时间不能大于结束时间:'.$start_time.'至'.$end_time);
			exit;
		}
		if(strim($start_time)!="")
		{
			$condition .= " and lcr.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lcr.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
				
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lcr.*,lco.user_name,lco.licai_id,lco.money as order_money, 
			lc.type as licai_type, lc.name ,
			lco.before_interest_date,lco.begin_interest_date,lco.before_interest_enddate,lco.site_buy_fee,
			lco.redempte_money 
			FROM ".DB_PREFIX."licai_redempte lcr 
			left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
			left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition." order by lcr.id desc limit ".$limit);
			
		}
		foreach($result['list']  as $k => $v)
		{
			switch($v["licai_type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				case 2: $result["list"][$k]["type_format"] = "浮动定存";
					break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			//0表示未赎回 1表示已赎回 2表示拒绝
			if($v["status"] == 0)
			{
				$result["list"][$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$result["list"][$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$result["list"][$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已取消";
			}
			//持有金额
			$result["list"][$k]["have_money"] = $v["have_money"] = $v["order_money"] - $v["site_buy_fee"] - $v["redempte_money"];
			
			$result["list"][$k]["have_money_format"] = format_money_wan($v["have_money"]);
			
			$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
			if($v["type"] == 0)
			{
				$v["show_rate"] = $licai_interest["before_breach_rate"];
			}
			elseif($v["type"] == 1)
			{
				$v["show_rate"] = $licai_interest["breach_rate"];
			}
			elseif($v["type"] == 2)
			{
				$v["show_rate"] = $licai_interest["interest_rate"];
			}
			
			$result["list"][$k]["show_rate_format"] = $v["show_rate"]."%";
			//预热期时间
			$before_days = (to_timespan($v["before_interest_enddate"]) - to_timespan($v["before_interest_date"]))/24/3600;
			if($before_days < 0)
			{
				$before_days = 0;
			}
			//理财期时间
			$days = (to_timespan($v["create_date"]) - to_timespan($v["begin_interest_date"]))/24/3600;
			if($days < 0)
			{
				$days = 0;
			}
			
			$v['before_reach_money'] = $licai_interest["before_rate"] / 100 / 365 *$v["money"] * $before_days;
			
			$v['breach_money'] = $v["show_rate"] / 100 / 365 *$v["money"] * $days;
			
			$result["list"][$k]["breach_money"] = $v["breach_money"] = $v['before_reach_money'] + $v['breach_money'];
			
			$result["list"][$k]["breach_money_format"] = format_price($v["breach_money"]);
			//手续费
			$result["list"][$k]["fee_money"] = $v["fee_money"] = $licai_interest["redemption_fee_rate"] /100 / 365 *$v["money"] * ($before_days + $days);
			
			$result["list"][$k]["fee_money_format"] = format_price($v["fee_money"]); 
			//赎回金额
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);
		}
		
		$list = $result["list"];
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'name'=>'""', 'type_format'=>'""','user_name'=>'""','have_money_format'=>'""','show_rate_format'=>'""','breach_money_format'=>'""','money_format'=>'""','fee_money_format'=>'""','create_date'=>'""','status_format'=>'""');
	    	if($page == 1)
	    	{	
		    	$content = iconv("utf-8","gbk","编号,产品名称,理财类型,购买用户,持有本金,收益率,收益金额,赎回资金,手续费,申请时间,状态");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['have_money_format'] = '"' . iconv('utf-8','gbk',$v['have_money_format']) . '"';
				$order_value['money_format'] = '"' . iconv('utf-8','gbk',$v['money_format']) . '"';
				$order_value['show_rate_format'] = '"' . iconv('utf-8','gbk',$v['show_rate_format']) . '"';
				$order_value['fee_money_format'] = '"' . iconv('utf-8','gbk',$v['fee_money']) . '"';
				$order_value['breach_money_format'] = '"' . iconv('utf-8','gbk',$v['breach_money']) . '"';
				$order_value['create_date'] = '"' . iconv('utf-8','gbk',$v['create_date']). '"' ;
				$order_value['status_format'] = '"' . iconv('utf-8','gbk',$v['status_format']). '"' ;
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=order_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
	}
	public function export_q_csv($page = 1)
	{	
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		$id = intval($_REQUEST["id"]);
		
		$condition = " and lcr.type = 0 or lcr.type = 3 and lcr.status in (0,1,2,3) ";
		
		if(strim($_REQUEST["p_name"]) != "")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition .= " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
		}
		
		$start_time = strim($_REQUEST['start_time']);
		$end_time = strim($_REQUEST['end_time']);

		$d = explode('-',$start_time);
		if (isset($_REQUEST['start_time']) && $start_time !="" && checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("开始时间不是有效的时间格式:{$start_time}(yyyy-mm-dd)");
			exit;
		}
		
		$d = explode('-',$end_time);
		if ( isset($_REQUEST['end_time']) && strim($end_time) !="" &&  checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("结束时间不是有效的时间格式:{$end_time}(yyyy-mm-dd)");
			exit;
		}
		
		if ($start_time!="" && strim($end_time) !="" && to_timespan($start_time) > to_timespan($end_time)){
			$this->error('开始时间不能大于结束时间:'.$start_time.'至'.$end_time);
			exit;
		}
		if(strim($start_time)!="")
		{
			$condition .= " and lcr.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lcr.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
				
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_redempte lcr 
		left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lcr.*,lco.user_name,lco.licai_id,lco.money as order_money, lc.type as licai_type, lc.name ,lco.site_buy_fee,lco.before_interest_date,lco.redempte_money 
			FROM ".DB_PREFIX."licai_redempte lcr 
			left join ".DB_PREFIX."licai_order lco on lcr.order_id = lco.id 
			left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition." order by lcr.id desc limit ".$limit);
			
		}
		foreach($result['list']  as $k => $v)
		{
			switch($v["licai_type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				case 2: $result["list"][$k]["type_format"] = "浮动定存";
					break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			//0表示未赎回 1表示已赎回 2表示拒绝
			if($v["status"] == 0)
			{
				$result["list"][$k]["status_format"] = "未赎回";
			}
			elseif($v["status"] == 1)
			{
				$result["list"][$k]["status_format"] = "已赎回";
			}
			elseif($v["status"] == 2)
			{
				$result["list"][$k]["status_format"] = "已拒绝";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已取消";
			}
			
			//持有金额
			$result["list"][$k]["have_money"] = $v["have_money"] = $v["order_money"] - $v["site_buy_fee"] - $v["redempte_money"];
			
			$result["list"][$k]["have_money_format"] = format_money_wan($v["have_money"]);
			
			$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
			$v["show_rate"] = $licai_interest["before_breach_rate"];
			
			$result["list"][$k]["show_rate_format"] = $v["show_rate"]."%"; 
			
			$days = (to_timespan($v["create_date"]) - to_timespan($v["before_interest_date"]))/24/3600;
			if($days < 0)
			{
				$days = 0;
			}
			
			$v['before_reach_money'] = $v["show_rate"] / 100 / 365 *$v["money"] * $days;

			$result["list"][$k]["breach_money"] = $v["breach_money"] = $v['before_reach_money'];
			
			$result["list"][$k]["breach_money_format"] = format_price($v["breach_money"]);
			//手续费
			$result["list"][$k]["fee_money"] = $v["fee_money"] = $licai_interest["redemption_fee_rate"] /100 / 365 *$v["money"] * $days;
			
			$result["list"][$k]["fee_money_format"] = format_price($v["fee_money"]); 
			//赎回金额
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);
			

		}
		
		$list = $result["list"];
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'name'=>'""', 'type_format'=>'""','user_name'=>'""','have_money_format'=>'""','show_rate_format'=>'""','breach_money_format'=>'""','money_format'=>'""','fee_money_format'=>'""','create_date'=>'""','status_format'=>'""');
	    	if($page == 1)
	    	{	
		    	$content = iconv("utf-8","gbk","编号,产品名称,理财类型,购买用户,持有本金,收益率,收益金额,赎回资金,手续费,申请时间,状态");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['have_money_format'] = '"' . iconv('utf-8','gbk',$v['have_money_format']) . '"';
				$order_value['money_format'] = '"' . iconv('utf-8','gbk',$v['money_format']) . '"';
				$order_value['show_rate_format'] = '"' . iconv('utf-8','gbk',$v['show_rate_format']) . '"';
				$order_value['fee_money_format'] = '"' . iconv('utf-8','gbk',$v['fee_money']) . '"';
				$order_value['breach_money_format'] = '"' . iconv('utf-8','gbk',$v['breach_money']) . '"';
				$order_value['create_date'] = '"' . iconv('utf-8','gbk',$v['create_date']). '"' ;
				$order_value['status_format'] = '"' . iconv('utf-8','gbk',$v['status_format']). '"' ;
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=order_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
	}
	public function status()
	{
		$id = intval($_REQUEST["id"]);
		$vo =  $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai_redempte where id =".$id);
		
		$vo["money_format"] = format_money_wan($vo["money"]);
		
		$this->assign("vo",$vo);
		$this->display();
	}
	public function set_status()
	{
		$redempte_id = intval($_REQUEST["redempte_id"]);
		$status = intval($_REQUEST["status"]);
		$earn_money = strim($_REQUEST["earn_money"]);
		$fee = strim($_REQUEST["fee"]);
		$pay_type = intval($_REQUEST["pay_type"]);
		$organiser_fee = strim($_REQUEST["organiser_fee"]);
		$web_type = 1;
		
		$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$organiser_fee,$pay_type,$web_type);
		
		//修改状态
		if($result["status"] != 1)
		{
			ajax_return($result);
		}
		
		B('FilterString');
		$data = array();
		$data["id"] = intval($_REQUEST["id"]);
		$data["status"] = intval($_REQUEST["status"]);
		$data["update_date"] = to_date(TIME_UTC,"Y-m-d");
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name")."更新状态,";
		
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			//$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			//$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
		
		ajax_return($result);
	}
}
?>