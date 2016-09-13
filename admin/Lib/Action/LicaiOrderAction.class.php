<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------


class LicaiOrderAction extends CommonAction{
	public function index()
	{	
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		
		$this->assign('licai_id',$id);
		
		$condition = " and licai_id = ".$id;
		
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
			$condition .= " and create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = strim($_REQUEST ['_order']);
		}
		else
		{
			$order = "id";
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
				
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 20;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();
		
		$licai = $GLOBALS["db"]->getRow("select name,id from ".DB_PREFIX."licai where id=".$id);

		$this->assign("licai",$licai);
		
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_order  where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_order where 1=1 ".$condition." order by ".str_replace("_format","",$order)." ".$sort." limit ".$limit);
			
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			if($v["status"] == 0)
			{
				$result['list'][$k]["status_format"] = "未支付";
			}
			elseif($v["status"] == 1)
			{
				$result['list'][$k]["status_format"] = "已支付";
			}
			elseif($v["status"] == 2)
			{
				$result['list'][$k]["status_format"] = "部分赎回";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已完结";
			}
			
			$result['list'][$k]["site_buy_fee_rate_format"] = $v['site_buy_fee_rate']."%";
			
			if($v["begin_interest_type"] == 0)
			{
				$result['list'][$k]["begin_interest_type_format"] = "当日生效";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$result['list'][$k]["begin_interest_type_format"] = "次日生效";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下个工作日生效";
			}
			elseif($v["begin_interest_type"] == 3)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下二个工作日生效";
			}
			
			$result['list'][$k]["money_format"] = format_money_wan($v["money"]);
		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"购买记录");
		$this->display ();
	}
	public function export_csv($page = 1)
	{	
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		$condition = " and licai_id = ".$id;

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
			$condition .= " and create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}

		$result = array();
		
		$licai = $GLOBALS["db"]->getRow("select name,id from ".DB_PREFIX."licai where id=".$id);

		$this->assign("licai",$licai);
		
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_order  where 1=1 ".$condition." order by id desc ");
		
		if($count > 0){
			
			$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_order where 1=1 ".$condition." order by id desc limit ".$limit);
			
		}
		
		foreach($list  as $k => $v)
		{
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
			
			$list[$k]["site_buy_fee_rate_format"] = $v['site_buy_fee_rate']."%";
			
			if($v["begin_interest_type"] == 0)
			{
				$list[$k]["begin_interest_type_format"] = "当日生效";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$list[$k]["begin_interest_type_format"] = "次日生效";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$list[$k]["begin_interest_type_format"] = "下个工作日生效";
			}
			elseif($v["begin_interest_type"] == 3)
			{
				$list[$k]["begin_interest_type_format"] = "下二个工作日生效";
			}
			
			$list[$k]["money_format"] = format_money_wan($v["money"]);
			
		}
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'user_name'=>'""', 'money_format'=>'""','status_format'=>'""','create_date'=>'""','site_buy_fee_rate_format'=>'""','begin_interest_type_format'=>'""');
	    	if($page == 1)
	    	{	
		    	$content = iconv("utf-8","gbk","编号,购买用户,购买金额,状态,购买时间,网站手续费,利息类型");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['money_format'] = '"' . iconv('utf-8','gbk',$v['money_format']) . '"';
				$order_value['status_format'] = '"' . iconv('utf-8','gbk',$v['status_format']) . '"';
				$order_value['create_date'] = '"' . iconv('utf-8','gbk',$v['create_date']) . '"';
				$order_value['site_buy_fee_rate_format'] = '"' . iconv('utf-8','gbk',$v['site_buy_fee_rate_format']).'"';
				$order_value['begin_interest_type_format'] = '"' . iconv('utf-8','gbk',$v['begin_interest_type_format']). '"' ;
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
	public function edit(){
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		$vo = $GLOBALS["db"]->getRow("select lco.*,lc.type,lc.name,lc.licai_sn from ".DB_PREFIX."licai_order lco
		 left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where lco.id =".$id);

		
		if($vo["status"] == 0)
		{
			$vo["status_format"] = "未支付";
		}
		elseif($vo["status"] == 1)
		{
			$vo["status_format"] = "已支付";
		}
		elseif($vo["status"] == 2)
		{
			$vo["status_format"] = "部分赎回";
		}
		elseif($vo["status"] == 3)
		{
			$vo["status_format"] = "已完结";
		}
		
		$vo["site_buy_fee_rate_format"] = $vo['site_buy_fee_rate']."%";
		
		if($vo["begin_interest_type"] == 0)
		{
			$vo["begin_interest_type_format"] = "当日生效";
		}
		elseif($vo["begin_interest_type"] == 1)
		{
			$vo["begin_interest_type_format"] = "次日生效";
		}
		elseif($vo["begin_interest_type"] == 2)
		{
			$vo["begin_interest_type_format"] = "下个工作日生效";
		}
		elseif($vo["begin_interest_type"] == 3)
		{
			$vo["begin_interest_type_format"] = "下二个工作日生效";
		}
		
		switch($vo["type"])
		{
			//case 0: $vo["type_format"] = "余额宝";
			//	break;
			case 1: $vo["type_format"] = "固定定存";
				break;
			case 2: $vo["type_format"] = "浮动定存";
				break;
			//case 3: $vo["type_format"] = "票据";
			//	break;
			//case 4: $vo["type_format"] = "基金";
			//	break;
		}
		$vo["fee_format"] = format_price($vo["site_buy_fee_rate"]*$vo["money"]);		
		$vo["freeze_bond_format"] = format_price($vo["freeze_bond"]);
		$vo["pay_money_format"] = format_money_wan($vo["pay_money"]);
		$vo["before_breach_rate_format"] = $vo["before_breach_rate"]."%";
		$vo["site_buy_fee_rate_format"] = $vo["site_buy_fee_rate"]."%";
		$vo["breach_rate_format"] = $vo["breach_rate"]."%";
		$vo["money_format"] = format_money_wan($vo["money"]);
		$vo["before_rate"] = $vo["before_rate"]."%";
		
		if($vo["status_time"] == "" || $vo["status_time"] == "0000-00-00 00:00:00")
		{
			$vo["status_time"] = $vo["create_time"];
		}
		
		$this->assign('vo',$vo);
		$this->display ();
	}
	public function update(){
		$id = $_REQUEST['id'];
		$data=array();
		$gross_margins = floatval($_REQUEST['gross_margins']);
		$data['gross_margins']=$gross_margins;
		$gross = floatval($_REQUEST['gross']);
		$data['gross']=$gross;
		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."licai_order",$data,'UPDATE','id='.$id);
		if (false !== $re) {
 			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			 
			$this->error(L("UPDATE_FAILED"),0,L("UPDATE_FAILED"));
		}
	}
	public function order_list()
	{			
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$condition = " ";
		
		if(strim($_REQUEST["name"]) != "")
		{
			$condition = " and lc.name like '%".strim($_REQUEST["name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition = " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
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
			$condition .= " and lco.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "fee_format")
				{
					$order = "";
				}
			}
			else
			{
				$order = "lco.id";
			}			
		} else {
			$order = "lco.id";
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

		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lco.*,lc.type,lc.name,lc.licai_sn FROM ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition.$order_str." limit ".$limit);
			
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace("lco.","",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			if($v["status"] == 0)
			{
				$result['list'][$k]["status_format"] = "未支付";
			}
			elseif($v["status"] == 1)
			{
				$result['list'][$k]["status_format"] = "已支付";
			}
			elseif($v["status"] == 2)
			{
				$result['list'][$k]["status_format"] = "部分赎回";
			}
			elseif($v["status"] == 3)
			{
				$result["list"][$k]["status_format"] = "已完结";
			}
			
			$result['list'][$k]["site_buy_fee_rate_format"] = $v['site_buy_fee_rate']."%";
			
			if($v["begin_interest_type"] == 0)
			{
				$result['list'][$k]["begin_interest_type_format"] = "当日生效";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$result['list'][$k]["begin_interest_type_format"] = "次日生效";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下个工作日生效";
			}
			elseif($v["begin_interest_type"] == 3)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下二个工作日生效";
			}
			
			switch($v["type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				//case 2: $result["list"][$k]["type_format"] = "浮动定存";
					//break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);
			
			$result["list"][$k]["fee_format"] = format_price($v["site_buy_fee"]);
			
			if($v["type"] > 0){
				$result["list"][$k]["before_rate_format"] = $v["before_rate"]."%";
				$result["list"][$k]["interest_rate_format"] = $v["interest_rate"]."%";
			}else{
				 $licai_interest = get_licai_interest_yeb($v['licai_id'],$v["begin_interest_date"],$v["end_interest_date"]);
				 $result["list"][$k]["before_rate_format"] = "--";
				 $result["list"][$k]["interest_rate_format"] =number_format($licai_interest['avg_interest_rate'],4)."%";
			}
		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"订单列表");
		$this->display ();
	}
	public function view()
	{
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		$vo = $GLOBALS["db"]->getRow("select lco.*,lc.type,lc.name,lc.licai_sn from ".DB_PREFIX."licai_order lco
		 left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where lco.id =".$id);

		
		if($vo["status"] == 0)
		{
			$vo["status_format"] = "未支付";
		}
		elseif($vo["status"] == 1)
		{
			$vo["status_format"] = "已支付";
		}
		elseif($vo["status"] == 2)
		{
			$vo["status_format"] = "部分赎回";
		}
		elseif($vo["status"] == 3)
		{
			$vo["status_format"] = "已完结";
		}
		
		$vo["site_buy_fee_rate_format"] = $vo['site_buy_fee_rate']."%";
		
		if($vo["begin_interest_type"] == 0)
		{
			$vo["begin_interest_type_format"] = "当日生效";
		}
		elseif($vo["begin_interest_type"] == 1)
		{
			$vo["begin_interest_type_format"] = "次日生效";
		}
		elseif($vo["begin_interest_type"] == 2)
		{
			$vo["begin_interest_type_format"] = "下个工作日生效";
		}
		elseif($vo["begin_interest_type"] == 3)
		{
			$vo["begin_interest_type_format"] = "下二个工作日生效";
		}
		
		switch($vo["type"])
		{
			case 0: $vo["type_format"] = "余额宝";
				break;
			case 1: $vo["type_format"] = "固定定存";
				break;
			case 2: $vo["type_format"] = "浮动定存";
				break;
			//case 3: $vo["type_format"] = "票据";
			//	break;
			//case 4: $vo["type_format"] = "基金";
			//	break;
		}
		$vo["fee_format"] = format_price($vo["site_buy_fee_rate"]*$vo["money"]);		
		$vo["freeze_bond_format"] = format_price($vo["freeze_bond"]);
		$vo["pay_money_format"] = format_money_wan($vo["pay_money"]);
		$vo["before_breach_rate_format"] = $vo["before_breach_rate"]."%";
		$vo["site_buy_fee_rate_format"] = $vo["site_buy_fee_rate"]."%";
		$vo["breach_rate_format"] = $vo["breach_rate"]."%";
		
		if($vo["type"] > 0 ){
			$vo["interest_rate_format"] = $vo["interest_rate"]."%";
		}else{
			$licai_interest = get_licai_interest_yeb($vo['licai_id'],$vo["begin_interest_date"],$vo["end_interest_date"]);
			$vo["interest_rate_format"] = $licai_interest['interest_rate']."%";

		}
		$vo["money_format"] = format_money_wan($vo["money"]);
		$vo["before_rate"] = $vo["before_rate"]."%";
		
		if($vo["status_time"] == "" || $vo["status_time"] == "0000-00-00 00:00:00")
		{
			$vo["status_time"] = $vo["create_time"];
		}
		
		$this->assign('vo',$vo);
		$this->display ();
	}
	public function export_q_csv($page = 1)
	{
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		$id = intval($_REQUEST["id"]);
		
		$condition = " ";
		
		if(strim($_REQUEST["name"]) != "")
		{
			$condition = " and lc.name like '%".strim($_REQUEST["name"])."%'";
		}
		
		if(strim($_REQUEST["user_name"]) != "")
		{
			$condition = " and lco.user_name like '%".strim($_REQUEST["user_name"])."%'";
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
			$condition .= " and lco.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
				
		$result = array();

		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition." order by lco.id desc ");
		
		if($count > 0){
			
			$list = $GLOBALS['db']->getAll("SELECT lco.*,lc.type,lc.name FROM ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition." order by lco.id desc limit ".$limit);
			
		}
		 
		foreach($list  as $k => $v)
		{
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
			
			$list[$k]["site_buy_fee_rate_format"] = $v['site_buy_fee_rate']."%";
			
			
			if($v["begin_interest_type"] == 0)
			{
				$list[$k]["begin_interest_type_format"] = "当日生效";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$list[$k]["begin_interest_type_format"] = "次日生效";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$list[$k]["begin_interest_type_format"] = "下个工作日生效";
			}
			elseif($v["begin_interest_type"] == 3)
			{
				$list[$k]["begin_interest_type_format"] = "下二个工作日生效";
			}
			
			switch($v["type"])
			{
				case 0: $list[$k]["type_format"] = "余额宝";
				break;
				case 1: $list[$k]["type_format"
				] = "固定定存";
				break;
				//case 2: $list[$k]["type_format"] = "浮动定存";
					//break;
				//case 3: $list[$k]["type_format"] = "票据";
				//	break;
				//case 4: $list[$k]["type_format"] = "基金";
				//	break;
			}
			$list[$k]["fee_format"] = $v["site_buy_fee_rate"]*$v["money"]/100;
			$list[$k]["before_rate_format"] = $v["before_rate"]."%";
			$list[$k]["interest_rate_format"] = $v["interest_rate"]."%";
			$list[$k]["site_buy_fee_rate_format"] = $v["site_buy_fee_rate"]."%";
		}
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_q_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'name'=>'""', 'type_format'=>'""','user_name'=>'""','money'=>'""','fee_format'=>'""','before_rate_format'=>'""','interest_rate_format'=>'""','begin_interest_date'=>'""','status_format'=>'""','site_buy_fee_rate_format'=>'""','begin_interest_type_format'=>'""');
	    	if($page == 1)
	    	{	
		    	$content = iconv("utf-8","gbk","编号,产品名称,理财类型,购买用户,支付金额,购买手续费,预热期收益率,理财期收益率,支付时间,状态,网站手续费,利息类型");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['money'] = '"' . iconv('utf-8','gbk',$v['money']) . '"';
				$order_value['fee_format'] = '"' . iconv('utf-8','gbk',$v['fee_format']) . '"';
				$order_value['before_rate_format'] = '"' . iconv('utf-8','gbk',$v['before_rate_format']) . '"';
				$order_value['interest_rate_format'] = '"' . iconv('utf-8','gbk',$v['interest_rate_format']).'"';
				$order_value['begin_interest_date'] = '"' . iconv('utf-8','gbk',$v['begin_interest_date']). '"' ;
				$order_value['status_format'] = '"' . iconv('utf-8','gbk',$v['status_format']). '"' ;
				$order_value['site_buy_fee_rate_format'] = '"' . iconv('utf-8','gbk',$v['site_buy_fee_rate_format']). '"' ;
				$order_value['begin_interest_type_format'] = '"' . iconv('utf-8','gbk',$v['begin_interest_type_format']). '"' ;
				
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
}
?>