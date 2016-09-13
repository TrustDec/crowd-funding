<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';

class LicaiAction extends CommonAction{
	public function index()
	{	
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$condition = " ";
		if(strim($_REQUEST["type"]) != "" && intval($_REQUEST["type"])!=-1)
		{
			$condition .= " and lc.type = ".intval($_REQUEST["type"]);
		}
		
		if(strim($_REQUEST["p_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		if(strim($_REQUEST["user_name"])!="")
		{
			$condition .= " and u.user_name like '%".strim($_REQUEST["user_name"])."%'";
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
			$condition .= " and lc.begin_buy_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lc.begin_buy_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "show_time" || $order == "product_size" )
				{
					$order = "";
				}
			}
			else
			{
				$order = "lc.".strim($_REQUEST ['_order']);
			}			
		} else {
			$order = " lc.id ";
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
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai lc 
		left join ".DB_PREFIX."user u on u.id = lc.user_id 
		where 1=1 ".$condition);

	
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT lc.*,u.user_name 
			FROM ".DB_PREFIX."licai lc 
			left join ".DB_PREFIX."user u on u.id = lc.user_id 
			where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace('lc.',"",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result["list"] as $k => $v)
		{
			//收益率
			$result["list"][$k]["average_income_rate_format"] = $v["average_income_rate"]."%";
			//产品期限
			if($v["end_date"] == "" || $v["end_date"] == "0000-00-00")
			{
				$v["end_date"] = "无期限";
			}
			$result["list"][$k]["show_time"] = $v["begin_buy_date"]."至".$v["end_date"];
			
			$result["list"][$k]["is_recommend_format"] = $v["is_recommend"] == 0 ?"否" :"是";
			//参与人数
			//$result["list"][$k]["member_count"] =intval($v["total_people"]);
			
			$result["list"][$k]["status_format"] = $v["status"] == 0 ?"无效":"有效";	
			
			
			switch($v["type"])
			{
				case 0: $result["list"][$k]["type_format"] = "余额宝";
					break;
				case 1: $result["list"][$k]["type_format"] = "固定定存";
					break;
				//case 2: $result["list"][$k]["type_format"] = "浮动定存";
				//	break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			
			//成交总额
			$result["list"][$k]["subscribing_amount_format"] = format_money_wan($v["subscribing_amount"]);
			
		}
		$this->assign("list",$result['list']);
		$this->assign("main_title","理财列表");
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		
		$this->display ();
	}
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		$vo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai where  id = ".$id);
		 
		$vo["min_money"] = $vo["min_money"] / 10000;
		
		$vo["max_money"] = $vo["max_money"] / 10000;
		
		$this->assign ( 'vo', $vo );
		
		$bank = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_bank where status = 1 ");
		$this->assign("bank",$bank);
		
		$fund_brand = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_brand where status = 1 ");
		$this->assign("fund_brand",$fund_brand);
		
		$fund_type = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_type where status = 1 ");
		$this->assign("fund_type",$fund_type);
		
		$this->display ();
	}
	public function update()
	{
		B('FilterString');
 		$data = M("Licai")->create();
		if($_REQUEST['investment_adviser']){
			$data['investment_adviser'] = $_REQUEST['investment_adviser'];
		}
		 
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
		$log_info = M("Licai")->where("id=".intval($data['id']))->getField("name");
		
		//开始验证有效性
		
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		if(!check_empty($data['time_limit']) && (!check_empty($data['end_date'])|| $data['end_date'] == '0000-00-00'))
		{
			$this->error("项目结束时间和理财期限至少填写一个");
		}
		if(!check_empty($data['purchasing_time']))
		{
			$this->error("请输入赎回到账时间描述");
		}
		/*if(!check_empty($data['licai_sn']))
		{
			$this->error("请输入项目编号");
		}*/
		$data["min_money"] = $data["min_money"] * 10000;
		
		$data["max_money"] = $data["max_money"] * 10000;

		$data['begin_buy_date'] = trim($data['begin_buy_date']);
		$data['end_buy_date'] = trim($data['end_buy_date']);
		$data['end_date'] = trim($data['end_date']);
		if($data['begin_buy_date'] > $data['end_date'])
		{
			$this->error("项目结束时间不能小于开始购买时间");
		}
		
		$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
		if(!$data['user_name'] )$data['user_id'] ="";
		
		//unset($data["type"]);
		 
		$list=M("Licai")->save ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("licai")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("licai")->where ( $condition )->delete();	
				//删除相关预览图
//				foreach($rel_data as $data)
//				{
//					@unlink(get_real_path().$data['preview']);
//				}
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	public function export_csv($page = 1)
	{
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$condition = " ";
		if(strim($_REQUEST["type"]) != "" && intval($_REQUEST["type"])!=-1)
		{
			$condition .= " and lc.type = ".intval($_REQUEST["type"]);
		}
		if(strim($_REQUEST["p_name"])!="")
		{
			$condition .= " and lc.name like '%".strim($_REQUEST["p_name"])."%'";
		}
		if(strim($_REQUEST["user_name"])!="")
		{
			$condition .= " and u.user_name like '%".strim($_REQUEST["user_name"])."%'";
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
			$condition .= " and lc.begin_buy_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lc.begin_buy_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}

		$list = $GLOBALS['db']->getAll("SELECT lc.*,u.user_name 
		FROM ".DB_PREFIX."licai lc 
		left join ".DB_PREFIX."user u on u.id = lc.user_id 
		where 1=1 ".$condition." limit ".$limit);
		
		foreach($list as $k => $v)
		{
			//收益率
			$list[$k]["average_income_rate_format"] = $v["average_income_rate"]."%";
			//产品期限
			if($v["end_date"] == "" || $v["end_date"] == "0000-00-00")
			{
				$v["end_date"] = "无期限";
			}
			$list[$k]["show_time"] = $v["begin_buy_date"]."至".$v["end_date"];
			
			$list[$k]["is_recommend_format"] = $v["is_recommend"] == 0 ?"否" :"是";
			//参与人数
			$list[$k]["member_count"] =intval($v["total_people"]);
			
			$list[$k]["status_format"] = $v["status"] == 0 ?"无效":"有效";	
			
			
			switch($v["type"])
			{
				//case 0: $result["list"][$k]["type_format"] = "余额宝";
				//	break;
				case 1: $list[$k]["type_format"] = "固定定存";
					break;
				case 2: $list[$k]["type_format"] = "浮动定存";
				//	break;
				//case 3: $result["list"][$k]["type_format"] = "票据";
				//	break;
				//case 4: $result["list"][$k]["type_format"] = "基金";
				//	break;
			}
			
			$list[$k]["subscribing_amount_format"] = format_money_wan($v["subscribing_amount"]);
		}
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'name'=>'""', 'licai_sn'=>'""','user_name'=>'""','product_size'=>'""','type_format'=>'""','average_income_rate_format'=>'""','show_time'=>'""','member_count'=>'""','subscribing_amount_format'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","编号,产品名称,理财代码,发起人,产品规模,类型,收益率,产品期限,参与人数,成交总额");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$order_value['licai_sn'] = '"' . iconv('utf-8','gbk',$v['licai_sn']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['product_size'] = '"' . iconv('utf-8','gbk',$v['product_size']) . '"';
				$order_value['average_income_rate_format'] = '"' . iconv('utf-8','gbk',$v['average_income_rate_format']).'"';
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']). '"' ;
				$order_value['show_time'] = '"' . iconv('utf-8','gbk',$v['show_time']). '"' ;
				$order_value['member_count'] = '"' . iconv('utf-8','gbk',$v['member_count']). '"' ;
				$order_value['subscribing_amount_format'] = '"' . iconv('utf-8','gbk',$v['subscribing_amount_format']). '"' ;
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
	public function add()
	{
		
		$bank = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_bank where status = 1 ");
		$this->assign("bank",$bank);
		
		$fund_brand = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_brand where status = 1 ");
		$this->assign("fund_brand",$fund_brand);
		
		$fund_type = $GLOBALS['db']->getAll("SELECT * from ".DB_PREFIX."licai_fund_type where status = 1 ");
		$this->assign("fund_type",$fund_type);
		
		$sort = D(MODULE_NAME)->where()->max("id") + 1;
		
		$this->assign("sort",$sort);
		
		$this->display ();
	}
	public function insert()
	{
		B('FilterString');
		$data = M("licai")->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		
		$log_info = $data["name"];
		
		$data["min_money"] = $data["min_money"] * 10000;
		
		$data["max_money"] = $data["max_money"] * 10000;
		
		//开始验证有效性
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}
		if(strim($data['name']) != "" && D("licai")->where("name='".$data['name']."'")->count() > 0){
			$this->error("理财名称已存在");
		}	
		if(!check_empty($data['time_limit']) && (!check_empty($data['end_date'])|| $data['end_date'] == '0000-00-00'))
		{
			$this->error("项目结束时间和理财期限至少填写一个");
		}
		/*if(!check_empty($data['licai_sn']))
		{
			$this->error("请输入项目编号");
		}*/
		
		if(strim($data['licai_sn']) != "" && D("licai")->where("deal_sn='".$data['licai_sn']."'")->count() > 0){
			$this->error("理财代码已存在");
		}
		if(!check_empty($data['purchasing_time']))
		{
			$this->error("请输入赎回到账时间描述");
		}
		if(strim($data["licai_sn"]) == "")
		{
			$data["licai_sn"] = "LC".to_date(TIME_UTC,"Y")."".str_pad(D(MODULE_NAME)->where()->max("id") + 1,7,0,STR_PAD_LEFT);
		}
		$data['begin_buy_date'] = trim($data['begin_buy_date']);
		$data['end_buy_date'] = trim($data['end_buy_date']);
		$data['end_date'] = trim($data['end_date']);
		
		$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
		if(!$data['user_name'] )
			$data['user_id'] ="";

		//$data["type"] = 0;
		
		$list=M("licai")->add ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$log_info.L("INSERT_FAILED"));
		}
	}
	
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$licai_info = M(MODULE_NAME)->getById($id);
	
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("status");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		$result=M(MODULE_NAME)->where("id=".$id)->setField("status",$n_is_effect);
		save_log($licai_info['name'].l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1);	
	}
	public function interest_index()
	{	
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		$condition = " and licai_id = ".$id;
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = strim($_REQUEST ['_order']);		
		} else {
			$order = " id ";
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
		
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_interest  where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_interest where 1=1 ".$condition." order by ".str_replace("_format","",$order)." ".$sort." limit ".$limit);
			
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			$result['list'][$k]["interest_rate_format"] = $v['interest_rate']."%";
			$result['list'][$k]["buy_fee_rate_format"] = $v['buy_fee_rate']."%";
			$result['list'][$k]["site_buy_fee_rate_format"] = $v['site_buy_fee_rate']."%";
			$result['list'][$k]["redemption_fee_rate_format"] = $v['redemption_fee_rate']."%";
			$result['list'][$k]["before_rate_format"] = $v['before_rate']."%";
			$result['list'][$k]["before_breach_rate_format"] = $v['before_breach_rate']."%";
			$result['list'][$k]["breach_rate_format"] = $v['breach_rate']."%";
			$result['list'][$k]["platform_rate_format"] = $v['platform_rate']."%";
			$result['list'][$k]["freeze_bond_rate_format"] = $v['freeze_bond_rate']."%";
			$result['list'][$k]["platform_breach_rate_format"] = $v['platform_breach_rate']."%";
			
			$result['list'][$k]["min_money_format"] = format_money_wan($v['min_money']);
			$result['list'][$k]["max_money_format"] = format_money_wan($v['max_money']);

		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"收益率列表");
		$this->display ();
	}
	public function interest_edit()
	{
		require_once APP_ROOT_PATH.'system/libs/licai.php';
		
		$id = intval($_REQUEST ['id']);
		$vo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai_interest where  id = ".$id);
		
		$vo["min_money_format"] = $vo['min_money']/10000;
		$vo["max_money_format"] = $vo['max_money']/10000;
		
		$this->assign ( 'vo', $vo );
		
		$this->display ();
	}
	public function interest_update()
	{
		B('FilterString');
		$data = M('LicaiInterest')->create();
		
		$data["min_money"] = $data['min_money']*10000;
		$data["max_money"] = $data['max_money']*10000;
		
		$this->assign("jumpUrl",u(MODULE_NAME."/interest_edit",array("id"=>$data['id'])));
		
		$log_info_array = M('LicaiInterest')->where("id=".intval($data['id']))->find();
		
		$log_info = $log_info["licai_id"]."--".$log_info["id"];
		//print_r($log_info);die;
		//开始验证有效性
		
		if(!check_empty($data['min_money']))
		{
			$this->error("请输入最小金额");
		}	
		if(!check_empty($data['max_money']))
		{
			$this->error("请输入最大金额");
		}
		if(!check_empty($data['interest_rate']))
		{
			$this->error("请输入利率");
		}
		if(floatval($data["min_money"]) > floatval($data["max_money"]))
		{
			$this->error("请输入正确的金额");
		}	
		
		$list=M('LicaiInterest')->save ($data);
		
		if (false !== $list) {
			
			require_once(APP_ROOT_PATH."system/libs/licai.php");
			
			syn_licai_status($log_info_array['licai_id']);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function interest_delete()
	{
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M('LicaiInterest')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('LicaiInterest')->where ( $condition )->delete();	
		
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	public function interest_add()
	{
		$id = intval($_REQUEST["id"]);
		
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		
		$licai = $GLOBALS["db"]->getRow("select name,id from ".DB_PREFIX."licai where id=".$id);

		$this->assign("licai",$licai);
		
		$this->display ();
	}
	public function interest_insert()
	{
		B('FilterString');
		$data = M('LicaiInterest')->create();

		$data["min_money"] = $data['min_money']*10000;
		$data["max_money"] = $data['max_money']*10000;
		
		$this->assign("jumpUrl",u(MODULE_NAME."/interest_add",array("id"=>$data["licai_id"])));
		
		$log_info = $data["licai_id"];
		
		//开始验证有效性
		
		if(!check_empty($data['min_money']))
		{
			$this->error("请输入最小金额");
		}	
		if(!check_empty($data['max_money']))
		{
			$this->error("请输入最大金额");
		}
		if(strim($data['interest_rate']) == "")
		{
			$this->error("请输入利率");
		}
		if(floatval($data["min_money"]) > floatval($data["max_money"]))
		{
			$this->error("请输入正确的金额");
		}
		
		
		$list=M('LicaiInterest')->add ($data);
		
		$log_info .= "--".$list["id"];
		
		if (false !== $list) {
			require_once(APP_ROOT_PATH."system/libs/licai.php");
			syn_licai_status($data['licai_id']);
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$log_info.L("INSERT_FAILED"));
		}
	}
	
	/*
	 * 添加个性推荐
	 */
	public function recommend_add()
	{
		$licai_id = intval($_REQUEST["id"]);
		if($licai_id)
		{
			$vo = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where id=".$licai_id);
			$this->assign("vo",$vo);
		}
		$this->display ();
	}
	public function recommend_insert()
	{
		B('FilterString');
		$data = M('LicaiRecommend')->create();
		$this->assign("jumpUrl",u("Licai"."/index"));
		
		$log_info = $data["name"];
		
		//开始验证有效性
		
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
		$list=M('LicaiRecommend')->add ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$log_info.L("INSERT_FAILED"));
		}
	}
	
	/*
	 * 购买记录
	 */
	 public function order_index()
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
	public function order_export_csv($page = 1)
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
	public function order_edit(){
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
	public function order_update(){
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
	public function order_order_list()
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
	public function order_view()
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
	public function order_export_q_csv($page = 1)
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
	/*
	 * 设置首页展示
	 */
	 public function dealshow_add()
	{
		$id =  intval($_REQUEST['id']);
		$vo['licai_name'] = $GLOBALS['db']->getOne("SELECT `name` FROM ".DB_PREFIX."licai where  id = ".$id);
		if($vo['licai_name']==""){
			$this->error("请选择理财产品");
		}
		$vo['licai_id'] = $id;
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	public function dealshow_insert()
	{
		B('FilterString');
		$data = M('LicaiDealshow')->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/dealshow_add",array("id"=>$data['licai_id'])));
		
		
		$data['create_date'] = to_date(NOW_TIME);
		//开始验证有效性
		
		$list=M('LicaiDealshow')->add ($data);
		
		if (false !== $list) {
			
			save_log($list.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($list.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$list.L("INSERT_FAILED"));
		}
	}
	/*
	 * 
	 */
	 public function history_index()
	{	
		$id = intval($_REQUEST["id"]);
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		$condition = " and licai_id = ".$id;
				
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 10;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();
		
		$licai = $GLOBALS["db"]->getRow("select name,id from ".DB_PREFIX."licai where id=".$id);

		$this->assign("licai",$licai);
		
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_history  where 1=1 ".$condition);
		
		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_history where 1=1 ".$condition." order by history_date desc limit ".$limit);
			
		}
		
		foreach($result['list']  as $k => $v)
		{
			$result['list'][$k]["rate_format"] = number_format($v['rate'],4)."%";
			$result['list'][$k]["net_value_format"] = format_price($v['net_value']);
		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"收益率列表");
		$this->display ();
	}
	
	public function history_edit()
	{
		$id = intval($_REQUEST ['id']);
		$vo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai_history where  id = ".$id);
		$this->assign ( 'vo', $vo );
		
		$this->display ();
	}
	public function history_update()
	{
		B('FilterString');
		$data = M('LicaiHistory')->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/history_edit",array("id"=>$data['id'])));
		
		$log_info_array = M('LicaiHistory')->where("id=".intval($data['id']))->find();
		
		$log_info = $log_info["licai_id"]."--".$log_info["id"];
		//print_r($log_info);die;
		//开始验证有效性
		
		if(!check_empty($data['history_date']))
		{
			$this->error("请输入日期");
		}	
		if(!check_empty($data['net_value']))
		{
			$this->error("请输入当日净利");
		}
		if(!check_empty($data['rate']))
		{
			$this->error("请输入利率");
		}
		
		$list=M('LicaiHistory')->save ($data);
		
		if (false !== $list) {
			
			require_once(APP_ROOT_PATH."system/libs/licai.php");
			
			syn_licai_status($log_info_array['licai_id'],0);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
			
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
		
	}
	public function history_delete()
	{
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M('LicaiHistory')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('LicaiHistory')->where ( $condition )->delete();	
		
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	public function history_add()
	{
		$id = intval($_REQUEST["id"]);
		
		if(!$id)
		{
			$this->error("操作失败，请返回重试");
		}
		
		$licai = $GLOBALS["db"]->getRow("select name,id from ".DB_PREFIX."licai where id=".$id);

		$this->assign("licai",$licai);
		
		$this->display ();
	}
	public function history_insert()
	{
		B('FilterString');
		$data = M('LicaiHistory')->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/history_add",array("id"=>$data["licai_id"])));
		
		$log_info = $data["licai_id"];
		
		//开始验证有效性
		
		if(!check_empty($data['history_date']))
		{
			$this->error("请输入日期");
		}	
		if(!check_empty($data['net_value']))
		{
			$this->error("请输入当日净利");
		}
		if(!check_empty($data['rate']))
		{
			$this->error("请输入利率");
		}
		
		
		$list=M('LicaiHistory')->add ($data);
		
		$log_info .= "--".$list["id"];
		
		if (false !== $list) {
			
			require_once(APP_ROOT_PATH."system/libs/licai.php");
			
			syn_licai_status($data['licai_id'],0);
			
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$log_info.L("INSERT_FAILED"));
		}
	}
}
?>