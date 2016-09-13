<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

require_once APP_ROOT_PATH.'system/libs/licai.php';
require_once APP_ROOT_PATH.'system/libs/user.php';
define("TIME_UTC",get_gmtime());   //当前UTC时间戳
class LicaiNearAction extends CommonAction{
	public function index()
	{	
		
		$condition = " and lco.status in (1,2) ";
		
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
		
		if(!$start_time)
		{
			$start_time = to_date(TIME_UTC-15*24*3600,"Y-m-d");
		}
		if(!$end_time)
		{
			$end_time = to_date(TIME_UTC,"Y-m-d");
		}
		
		/*if(strim($start_time)!="")
		{
			$condition .= " and lco.end_interest_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}*/
		if(strim($end_time) !="")
		{
			$condition .= " and lco.end_interest_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "interest_money_format")
				{
					$order = " lco.id ";
				}
			}
			else
			{
				$order = "lco.".strim($_REQUEST ['_order']);
			}			
		} else {
			$order = " lco.id ";
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

		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_order lco
		 left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lco.*,lc.type,lc.name FROM ".DB_PREFIX."licai_order lco left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace('lco.',"",$order);
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
				$result['list'][$k]["status_format"] = "已完结";
			}
			
			$result['list'][$k]["purchase_rate_format"] = $v['purchase_rate']."%";
			
			if($v["begin_interest_type"] == 0)
			{
				$result['list'][$k]["begin_interest_type_format"] = "购买次日起息";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下个工作日起息";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$result['list'][$k]["begin_interest_type_format"] = "下二个工作日起息";
			}
			
			switch($v["type"])
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
			
			$v["money"] = $v["money"] - $v["redempte_money"] - $v["site_buy_fee"]; 
			
			$result["list"][$k]["money_format"] = format_money_wan($v["money"]);
			if($v["type"]>0){
				$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
				$day_before=intval((to_timespan($v['before_interest_enddate'])-to_timespan($v['before_interest_date']))/24/3600);
				if($day_before <0)
				{
					$day_before = 0;
				}
				
				$before_earn_money=$v["money"]*$day_before*$licai_interest['before_rate']*0.01/365;
				
				$day_begin=intval((to_timespan($v['end_interest_date'])-to_timespan($v['begin_interest_date']))/24/3600);
				
				if($day_begin < 0)
				{
					$day_begin = 0;
				}
				
				$begin_earn_money=$v["money"]*$day_begin*$licai_interest['interest_rate']*0.01/365;
				
				$result["list"][$k]['interest_rate_format']= $licai_interest['interest_rate']."%"; 
				$result["list"][$k]['interest_money_format']= round($before_earn_money+$begin_earn_money,4); 
			}else{
				$licai_interest = get_licai_interest_yeb($v['licai_id'],$v["begin_interest_date"],$v["end_interest_date"]);
				$result["list"][$k]['interest_rate_format']= $licai_interest['avg_interest_rate']."%"; 
				$result["list"][$k]['interest_money_format']=round($v["money"]*$licai_interest['interest_rate']*0.01/365,4);
			}
			
		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"快到期");
		$this->display ();
	}
	public function export_csv($page = 1)
	{	
		
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		$id = intval($_REQUEST["id"]);
		
		$condition = " and lco.status in (1,2) ";
		
		if(strim($_REQUEST["name"]) != "")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["name"])."%'";
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
		
		
		if(!$start_time)
		{
			$start_time = to_date(TIME_UTC-15*24*3600,"Y-m-d");
		}
		if(!$end_time)
		{
			$end_time = to_date(TIME_UTC,"Y-m-d");
		}
		
		if(strim($start_time)!="")
		{
			$condition .= " and lco.end_interest_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lco.end_interest_date <= '".  strim($end_time)."'";
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
			
			$list[$k]["purchase_rate_format"] = $v['purchase_rate']."%";
			
			if($v["begin_interest_type"] == 0)
			{
				$list[$k]["begin_interest_type_format"] = "购买次日起息";
			}
			elseif($v["begin_interest_type"] == 1)
			{
				$list[$k]["begin_interest_type_format"] = "下个工作日起息";
			}
			elseif($v["begin_interest_type"] == 2)
			{
				$list[$k]["begin_interest_type_format"] = "下二个工作日起息";
			}
			
			switch($v["type"])
			{
				case 0: $list[$k]["type_format"] = "余额宝";
					break;
				case 1: $list[$k]["type_format"] = "固定定存";
					break;
				case 2: $list[$k]["type_format"] = "浮动定存";
					break;
				//case 3: $list[$k]["type_format"] = "票据";
				//	break;
				//case 4: $list[$k]["type_format"] = "基金";
				//	break;
			}
			$v["money"] = $v["money"] - $v["redempte_money"] - $v["site_buy_fee"]; 
			
			$list[$k]["money_format"] = format_money_wan($v["money"]);
			
			$licai_interest = get_licai_interest($v["licai_id"],$v["money"]);
			
			$day_before=intval((to_timespan($v['before_interest_enddate'])-to_timespan($v['before_interest_date']))/24/3600);
			if($day_before <0)
			{
				$day_before = 0;
			}
			
			$before_earn_money=$v["money"]*$day_before*$licai_interest['before_rate']*0.01/365;
			
			$day_begin=intval((to_timespan($v['end_interest_date'])-to_timespan($v['begin_interest_date']))/24/3600);
			
			if($day_begin < 0)
			{
				$day_begin = 0;
			}
			
			$begin_earn_money=$v["money"]*$day_begin*$licai_interest['interest_rate']*0.01/365;
			
			$list[$k]['interest_rate_format']= $licai_interest['interest_rate']."%"; 
			$list[$k]['interest_money_format']= round($before_earn_money+$begin_earn_money,2); 
		}
		
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'name'=>'""', 'type_format'=>'""','user_name'=>'""','money_format'=>'""','interest_rate_format'=>'""','interest_money_format'=>'""','create_time'=>'""','end_interest_date'=>'""','status_format'=>'""');
	    	if($page == 1)
	    	{	
		    	$content = iconv("utf-8","gbk","编号,产品名称,理财类型,购买用户,投资本金,收益率,收益金额,支付时间,到期时间,状态");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['money_format'] = '"' . iconv('utf-8','gbk',$v['money_format']) . '"';
				$order_value['interest_rate_format'] = '"' . iconv('utf-8','gbk',$v['interest_rate_format']) . '"';
				$order_value['interest_money_format'] = '"' . iconv('utf-8','gbk',$v['interest_money_format']) . '"';
				$order_value['create_time'] = '"' . iconv('utf-8','gbk',$v['create_time']). '"' ;
				$order_value['end_interest_date'] = '"' . iconv('utf-8','gbk',$v['end_interest_date']). '"' ;
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
	public function view()
	{
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
		
		$vo["purchase_rate_format"] = $vo['purchase_rate']."%";
		
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
		$vo["money_format"] = format_money_wan($vo["money"]);
		$vo["redempte_money_format"] = format_price($vo["redempte_money"]);
		$vo["fee_format"] = format_price($vo["site_buy_fee"]);		
		$vo["freeze_bond_format"] = format_price($vo["freeze_bond"]);
		$vo["pay_money_format"] = format_money_wan($vo["pay_money"]);
		$vo["before_breach_rate_format"] = $vo["before_breach_rate"]."%";
		$vo["site_buy_fee_rate_format"] = $vo["site_buy_fee_rate"]."%";
		$vo["breach_rate_format"] = $vo["breach_rate"]."%";
		
		if($vo["type"]){
			$vo["interest_rate_format"] = $vo["interest_rate"]."%";
		}else{
			$licai_interest = get_licai_interest_yeb($vo['licai_id'],$vo["begin_interest_date"],$vo["end_interest_date"]);
			$vo["interest_rate_format"] = $licai_interest['avg_interest_rate']."%";
		}
		$vo["before_rate"] = $vo["before_rate"]."%";
		//部分赎回
		if($vo["status"] == 2)
		{
			//$vo["part"] = $GLOBALS["db"]->getOne(" select sum(money) from ".DB_PREFIX."licai_redempte where order_id = ".$vo['id']." and status = 1 ");
			$vo["money"] = $vo["money"] - $vo["redempte_money"]; 
			
		}
		$vo["money_format"] = format_price($vo["money"]);
		$this->assign('vo',$vo);
		$this->display ();
	}
	/*public function set_status()
	{
		//修改状态
		B('FilterString');
		$data = array();
		$data["id"] = intval($_REQUEST["id"]);
		
		$data["status"] = 3;
		
		$log_info = M("LicaiOrder")->where("id=".intval($data['id']))->getField("name")."发放理财,";
		
		// 更新数据
		$list=M("LicaiOrder")->save ($data);
		
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}*/
	public function status()
	{		
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST["id"]);
				
		$vo =  $GLOBALS["db"]->getRow("select lco.*,lc.type from ".DB_PREFIX."licai_order lco
		left join ".DB_PREFIX."licai lc on lc.id= lco.licai_id  
		where lco.id =".$id);
		
		
		$vo["money"] = $vo["money"] - $vo["redempte_money"] - $vo["site_buy_fee"];
		
		$money = $vo["money"];
		
		$vo['money_format'] =format_money_wan($vo['money']);
		if($vo["type"] > 0){
			$licai_interest=get_licai_interest($vo['licai_id'],$money);
		
			$day_before=intval((to_timespan($vo['before_interest_enddate'])-to_timespan($vo['before_interest_date']))/24/3600);
			
			if($day_before < 0)
			{
				$day_before = 0;
			}
			
			$before_earn_money=$money*$day_before*$licai_interest['before_rate']*0.01/365;
			
			$day_begin=intval((to_timespan($vo['end_interest_date'])-to_timespan($vo['begin_interest_date']))/24/3600);
			
			if($day_begin < 0)
			{
				$day_begin = 0;
			}
			
			$begin_earn_money=$money*$day_begin*$licai_interest['interest_rate']*0.01/365;
			
			
			$vo['earn_money']= round($before_earn_money+$begin_earn_money,4); 
			$vo['fee']= round($money*($day_before+$day_begin)*$licai_interest['redemption_fee_rate']*0.01/365,4);
			$vo['organiser_fee']= round($money*($day_before+$day_begin)*$licai_interest['platform_rate']*0.01/365,4);

		}else{
			$licai_interest = get_licai_interest_yeb($vo['licai_id'],$vo["begin_interest_date"],$vo["end_interest_date"]);
			
			$vo['earn_money']=round($money*$licai_interest['interest_rate']*0.01/365,4);
			$vo['fee']=round($money*$licai_interest['days']*$licai_interest['redemption_fee_rate']*0.01/365,4);
			$vo['organiser_fee']= round($money*$licai_interest['days']*$licai_interest['platform_rate']*0.01/365,4);
		}
		$vo['before_interest_enddate'] = to_timespan($vo['before_interest_enddate']);
		$vo['before_interest_date'] = to_timespan($vo['before_interest_date']);
		$vo['end_interest_date'] = to_timespan($vo['end_interest_date']);
		$vo['begin_interest_date'] = to_timespan($vo['begin_interest_date']);
		
		$this->assign("vo",$vo);
		$this->display();
	}
	
	public function set_status()
	{
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		require_once APP_ROOT_PATH.'system/libs/user.php';
		$result["jump"] = url("licai#uc_expire_lc");
		
		$id = intval($_REQUEST["id"]);
		$status = 1;
		$earn_money = strim($_REQUEST["earn_money"]);
		$fee = strim($_REQUEST["fee"]);
		$pay_type = strim($_REQUEST["pay_type"]); //0不允许垫付
		$web_type = 1; //0前台
		
		$licai_order = $GLOBALS["db"]->getRow("select lco.*,u.user_name from ".DB_PREFIX."licai_order lco 
		left join ".DB_PREFIX."licai lc on lco.licai_id = lc.id 
		left join ".DB_PREFIX."user u on u.id = lco.user_id 
		where lco.id=".$id);
		
		if(!$licai_order)
		{
			$result["status"] = 0;
			$result["info"] = "操作失败，请重试";
			$this->ajaxReturn("",$result["info"],$result["status"]);
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
			
			$money = $licai_redempte_data["money"] = $licai_order["money"] -$licai_order["redempte_money"]- $licai_order["site_buy_fee"];
			$licai_redempte_data["create_date"] = to_date(TIME_UTC);
			$licai_redempte_data["order_id"] = $licai_order["id"];
			$licai_redempte_data["user_id"] = $licai_order["user_id"];
			$licai_redempte_data["user_name"] = $licai_order["user_name"];
			$licai_redempte_data["status"] = 0;
			$licai_redempte_data["type"] = 2;	
			$licai_interest = get_licai_interest($licai_order["licai_id"],$money);
			
			/**/		
			$day_before=intval(($licai_order['before_interest_enddate']-$licai_order['before_interest_date'])/24/3600);
			
			if($day_before < 0)
			{
				$day_before = 0;
			}
			
			$before_earn_money = $money * $day_before * $licai_interest['before_rate']*0.01/365;
			
			$day_begin=intval(($licai_order['end_interest_date']-$licai_order['begin_interest_date'])/24/3600);
			
			if($day_begin < 0)
			{
				$day_begin = 0;
			}
			
			$begin_earn_money = $money * $day_begin * $licai_interest['interest_rate']*0.01/365;

			$licai_redempte_data['earn_money']= round($before_earn_money + $begin_earn_money,2); 
			$licai_redempte_data['fee']= round($money * ($day_before+$day_begin) * $licai_interest['redemption_fee_rate'] * 0.01/365,2);
			$licai_redempte_data['organiser_fee']= round($money * ($day_before+$day_begin) * $licai_interest['platform_rate'] * 0.01/365,2);
			
			/**/
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."licai_redempte",$licai_redempte_data,"INSERT");
			
			$redempte_id = $GLOBALS['db']->insert_id();
			
			$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$licai_redempte_data["organiser_fee"],$pay_type,$web_type);
		}
		else
		{
			
			$result = deal_redempte($redempte_id,$status,$earn_money,$fee,$redempte["organiser_fee"],$pay_type,$web_type);
		}
		
		//修改状态
		if($result["status"] != 1)
		{
			$this->ajaxReturn("",$result["info"],$result["status"]);
		}
		//status_time		
		$status_time = to_date(TIME_UTC,"Y-m-d H:i:s");
		
		$GLOBALS['db']->query("update ".DB_PREFIX."licai_order set status_time = '".$status_time."' where id =".$id);
		
		$this->ajaxReturn("",$result["info"],$result["status"]);
	}
}
?>