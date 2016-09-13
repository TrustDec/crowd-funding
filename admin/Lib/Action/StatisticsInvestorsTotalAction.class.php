<?php

class StatisticsInvestorsTotalAction extends CommonAction{
	public function com_search(){
		$map = array();
		if (!isset($_REQUEST['end_time']) || $_REQUEST['end_time'] == '') {
			$_REQUEST['end_time'] = to_date(get_gmtime(), 'Y-m-d');
		}
		
		
		if (!isset($_REQUEST['start_time']) || $_REQUEST['start_time'] == '') {
			$_REQUEST['start_time'] = dec_date($_REQUEST['end_time'], 7);// $_SESSION['q_start_time_7'];
		}
		$map['start_time'] = trim($_REQUEST['start_time']);
		$map['end_time'] = trim($_REQUEST['end_time']);
		
	
		$this->assign("start_time",$map['start_time']);
		$this->assign("end_time",$map['end_time']);
	
	
		$d = explode('-',$map['start_time']);
		if (checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("开始时间不是有效的时间格式:{$map['start_time']}(yyyy-mm-dd)");
			exit;
		}
	
		$d = explode('-',$map['end_time']);
		if (checkdate($d[1], $d[2], $d[0]) == false){
			$this->error("结束时间不是有效的时间格式:{$map['end_time']}(yyyy-mm-dd)");
			exit;
		}
	
		if (to_timespan($map['start_time']) > to_timespan($map['end_time'])){
			$this->error('开始时间不能大于结束时间');
			exit;
		}
	
		$q_date_diff = 70;
		$this->assign("q_date_diff",$q_date_diff);
		//echo abs(to_timespan($map['end_time']) - to_timespan($map['start_time'])) / 86400 + 1;
		if ($q_date_diff > 0 && (abs(to_timespan($map['end_time']) - to_timespan($map['start_time'])) / 86400  + 1 > $q_date_diff)){
			$this->error("查询时间间隔不能大于  {$q_date_diff} 天");
			exit;
		}
		
		return $map;
	}	
	
    //股权众筹-投资人统计
    public function investors_total() 
    {
    	if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
    	$now=get_gmtime();
    	$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(il.create_time , '%Y-%m-%d') as 时间, count( il.user_id) as 投资人总数, sum(if(u.is_investor=1, 1,0)) as 投资人, sum(if(u.is_investor=2,1,0)) as 投资机构 , sum(il.money) as 认投金额 , sum(if(il.investor_money_status=3,il.money,0)) as 成功融资金额
		from  ".DB_PREFIX."investment_list  as il left join ".DB_PREFIX."user as u on u.id = il.user_id where";
	
		//日期期间使用in形式,以确保能正常使用到索引
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " FROM_UNIXTIME(il.create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(il.create_time , '%Y-%m-%d') ";
		
		$model = D();
		
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		//print_r($sql_str);exit;
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(array('投资人总数','时间','投资人数'),array('成功融资金额','时间','成功融资金额')),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    // 股权众筹-投资人统计明细
	public function investors_info(){
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " (FROM_UNIXTIME(il.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select
		FROM_UNIXTIME(il.create_time +28800 , '%Y-%m-%d') as 时间,
	    d.id as 项目编号, 
	    d.name as 项目名称, 
	    count(il.user_id) as 投资人总数 , 
	    sum(if(u.is_investor=1 , 1,0)) as 投资人 , 
	    sum(if(u.is_investor=2 , 1,0)) as 投资机构,
	    sum(if(d.id=il.deal_id,il.money,0)) as 认投金额,
	    sum(if(il.investor_money_status=3 and d.id=il.deal_id,il.money,0)) as 成功融资金额
		from  ".DB_PREFIX."investment_list  as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and d.id = il.deal_id) where  $condtion  ";

		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (il.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (il.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (il.create_time between $begin_time and $end_time )";
			}
			
		}
		$sql_str .= "group by d.id ";
		$model = D();
		
		//echo $sql_str;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
	}
	//股权众筹-投资人统计导出
	public function export_csv_investors_total($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(il.create_time , '%Y-%m-%d') as sj, count(il.user_id) as tzje, sum(if(u.is_investor=1, 1,0)) as tzr, sum(if(u.is_investor=2,1,0)) as jgtz, sum(il.money) as rtje , sum(if(il.investor_money_status=3,il.money,0)) as cglzje
		from  ".DB_PREFIX."investment_list  as il left join ".DB_PREFIX."user as u on u.id = il.user_id where";
	
		//日期期间使用in形式,以确保能正常使用到索引
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " FROM_UNIXTIME(il.create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(il.create_time , '%Y-%m-%d') ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
		if($list)
		{
	
			$total_value = array(
									'sj'=>'""',
									'tzje'=>'""',
									'tzr'=>'""',
									'jgtz'=>'""',
									'rtje'=>'""',
									'cglzje'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,投资人数,投资人,投资机构,认投金额,成功融资金额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['tzje'] = iconv('utf-8','gbk','"' . number_format($v['tzje'],2) . '"');
				$total_value['tzr'] = iconv('utf-8','gbk','"' . $v['tzr'] . '"');
				$total_value['jgtz'] = iconv('utf-8','gbk','"' . $v['jgtz'] . '"');
				$total_value['rtje'] = iconv('utf-8','gbk','"' . number_format($v['rtje'],2) . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . number_format($v['cglzje'],2) . '"');

				$content_total .= implode(",", $total_value) . "\n";
			}	
			
			
			header("Content-Disposition: attachment; filename=total_list.csv");
	    	echo $content_total;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}
			
	}
	 //股权众筹-投资人统计明细导出
	public function export_csv_investors_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " (FROM_UNIXTIME(il.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select
		FROM_UNIXTIME(il.create_time +28800, '%Y-%m-%d') as sj,
	    d.id as xmbh, 
	    d.name as xmmc, 
	    count(il.user_id) as tzrzs , 
	    sum(if(u.is_investor=1 , 1,0)) as tzr , 
	    sum(if(u.is_investor=2 , 1,0)) as tzjg,
	    sum(if(d.id=il.deal_id,il.money,0)) as rgje,
	    sum(if(il.investor_money_status=3 and d.id=il.deal_id,il.money,0)) as cglzje
		from ".DB_PREFIX."investment_list  as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and d.id = il.deal_id) where  $condtion  ";

		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (il.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (il.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (il.create_time between $begin_time and $end_time )";
			}
			
		}
		$sql_str .= "group by d.id ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
//		echo $sql_str;
//		exit;
//		var_dump($list);exit;
		
		if($list)
		{
			//register_shutdown_function(array(&$this, 'export_csv_total'), $page+1);
			
			$total_value = array(
									'sj'=>'""',
									'xmbh'=>'""',
									'xmmc'=>'""',
									'tzrzs'=>'""',
									'tzr'=>'""',
									'tzjg'=>'""',
									'rgje'=>'""',
									'cglzje'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,项目编号,项目名称,投资人总数,投资人,投资机构,认购金额,成功融资金额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['xmbh'] = iconv('utf-8','gbk','"' . $v['xmbh'] . '"');
				$total_value['xmmc'] = iconv('utf-8','gbk','"' . $v['xmmc'] . '"');
				$total_value['tzrzs'] = iconv('utf-8','gbk','"' . $v['tzrzs'] . '"');
				$total_value['tzr'] = iconv('utf-8','gbk','"' . $v['tzr'] . '"');
				$total_value['tzjg'] = iconv('utf-8','gbk','"' . $v['tzjg'] . '"');
				$total_value['rgje'] = iconv('utf-8','gbk','"' . number_format($v['rgje'],2) . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . number_format($v['cglzje'],2) . '"');
				$content_total .= implode(",", $total_value) . "\n";
			}	
			
			
			header("Content-Disposition: attachment; filename=total_list.csv");
	    	echo $content_total;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}
			
	}
}
?>