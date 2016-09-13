<?php

require_once APP_ROOT_PATH.'system/libs/licai.php';
class LicaiAdvanceAction extends CommonAction{

    public function index()
	{	
		$condition = " ";
		
		if(strim($_REQUEST["status"]) != "" && intval($_REQUEST["status"])!=-1)
		{
			$condition .= " and lca.status = ".intval($_REQUEST["status"]);
		}
		
		if(strim($_REQUEST["pay_username"])!="")
		{
			$condition .= " and lca.user_name like '%".strim($_REQUEST["pay_username"])."%'";
		}
		if(strim($_REQUEST["get_user_name"])!="")
		{
			$condition .= " and lcr.user_name like '%".strim($_REQUEST["get_user_name"])."%'";
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
			$condition .= " and lca.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lca.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id" && strim($_REQUEST['_order']) != "user_name")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "back_money_format" )
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
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_advance lca 
		left join ".DB_PREFIX."licai_redempte lcr on lcr.id = lca.redempte_id 
		where 1=1 ".$condition);
		
		if($count > 0){
			
			$list = $GLOBALS['db']->getAll("SELECT lca.*,lcr.user_name as get_user_name,lca.advance_money+lca.real_money as total_money 
			FROM ".DB_PREFIX."licai_advance lca 
			left join ".DB_PREFIX."licai_redempte lcr on lcr.id = lca.redempte_id 
			where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace("lcr.","",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($list as $k =>$v)
		{
			//$list[$k]["total_money"] = $v["total_money"] = $v["advance_money"]+$v["real_money"];
			$list[$k]["total_money_format"] = format_money_wan($v["total_money"]);
			
			if($v["status"] == 2)
			{
				$list[$k]["back_money"] = $v["advance_money"];
			}
			else
			{
				$list[$k]["back_money"] = 0;
			}
			
			$list[$k]["advance_money_format"] = format_money_wan($v["advance_money"]);
			$list[$k]["back_money_format"] = format_money_wan($list[$k]["back_money"]);
			
			if($v["status"] == 0)
			{
				$list[$k]["status_format"] = "生成失败";	
			}
			else if($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已垫付";	
			}
			else
			{
				$list[$k]["status_format"] = "已回收";	
			}
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期赎回垫付";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期赎回垫付";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "到期垫付";
			}
		}
		
		$this->assign("list",$list);
		
		$page = new Page($count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"垫付单管理");
		$this->display ();
	}
	
	public function update()
	{
		$data = array();
		$data["id"] = intval($_REQUEST["id"]);
		$data["status"] = 2;
		
		$this->assign("jumpUrl",u(MODULE_NAME."/index"));
		
		$log_info = $data['id'];
		
		$list=M(MODULE_NAME)->save ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->ajaxReturn("",l("UPDATE_SUCCESS"),1)	;	
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->ajaxReturn("",l("UPDATE_FAILED"),1)	;	
		}
	}
	public function export_csv($page = 1)
	{
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$condition = " ";
		
		if(strim($_REQUEST["status"]) != "" && intval($_REQUEST["status"])!=-1)
		{
			$condition .= " and lca.status = ".intval($_REQUEST["status"]);
		}
		
		if(strim($_REQUEST["pay_username"])!="")
		{
			$condition .= " and lca.user_name like '%".strim($_REQUEST["pay_username"])."%'";
		}
		if(strim($_REQUEST["get_user_name"])!="")
		{
			$condition .= " and lcr.user_name like '%".strim($_REQUEST["get_user_name"])."%'";
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
			$condition .= " and lca.create_date >= '".strim($start_time)."'";
			$this->assign("start_time",$start_time);
		}
		if(strim($end_time) !="")
		{
			$condition .= " and lca.create_date <= '".  strim($end_time)."'";
			$this->assign("end_time",$end_time);
		}


		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_advance lca 
		left join ".DB_PREFIX."licai_redempte lcr on lcr.id = lca.redempte_id 
		where 1=1 ".$condition);
		
		if($count > 0){
			
			$list = $GLOBALS['db']->getAll("SELECT lca.*,lcr.user_name as get_user_name 
			FROM ".DB_PREFIX."licai_advance lca 
			left join ".DB_PREFIX."licai_redempte lcr on lcr.id = lca.redempte_id 
			where 1=1 ".$condition." order by lca.id desc limit ".$limit);
		}		
		
		foreach($list as $k =>$v)
		{
			$list[$k]["total_money"] = $v["total_money"] = $v["advance_money"]+$v["real_money"];
			$list[$k]["total_money_format"] = format_price($v["total_money"]);
			
			if($v["status"] == 2)
			{
				$list[$k]["back_money"] = $v["advance_money"];
			}
			else
			{
				$list[$k]["back_money"] = 0;
			}
			$list[$k]["advance_money_format"] = format_price($v["advance_money"]);
			$list[$k]["back_money_format"] = format_price($list[$k]["back_money"]);
			
			if($v["status"] == 2)
			{
				$list[$k]["status_format"] = "已回收";	
			}
			elseif($v["status"] == 1)
			{
				$list[$k]["status_format"] = "已垫付";	
			}
			
			if($v["type"] == 0)
			{
				$list[$k]["type_format"] = "预热期赎回垫付";
			}
			elseif($v["type"] == 1)
			{
				$list[$k]["type_format"] = "理财期赎回垫付";
			}
			elseif($v["type"] == 2)
			{
				$list[$k]["type_format"] = "到期垫付";
			}
		}

		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'id'=>'""', 'user_name'=>'""', 'get_user_name'=>'""','total_money_format'=>'""','advance_money_format'=>'""','back_money_format'=>'""','create_date'=>'""','update_date'=>'""','status_format'=>'""','type_format'=>'""','redempte_id'=>"''");
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","编号,付款人,收款人,应付金额,垫付金额,收回金额,垫付时间,收回时间,状态,垫付类型,单号");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['get_user_name'] = '"' . iconv('utf-8','gbk',$v['get_user_name']) . '"';
				$order_value['total_money_format'] = '"' . iconv('utf-8','gbk',$v['total_money']) . '"';
				$order_value['advance_money_format'] = '"' . iconv('utf-8','gbk',$v['advance_money']) . '"';
				$order_value['back_money_format'] = '"' . iconv('utf-8','gbk',$v['back_money']).'"';
				$order_value['create_date'] = '"' . iconv('utf-8','gbk',$v['create_date']). '"' ;
				$order_value['update_date'] = '"' . iconv('utf-8','gbk',$v['update_date']). '"' ;
				$order_value['status_format'] = '"' . iconv('utf-8','gbk',$v['status_format']). '"' ;
				$order_value['type_format'] = '"' . iconv('utf-8','gbk',$v['type_format']). '"' ;
				$order_value['redempte_id'] = '"' . iconv('utf-8','gbk',$v['redempte_id']). '"' ;
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