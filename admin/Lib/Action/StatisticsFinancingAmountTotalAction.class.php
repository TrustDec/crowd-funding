<?php

class StatisticsFinancingAmountTotalAction extends CommonAction{
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
	
	//股权众筹-融资金额统计
    public function financing_amount_total() 
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
		
		$sql_str = "select 
		FROM_UNIXTIME(il.create_time , '%Y-%m-%d') as 时间,
	    count(il.user_id) as 投资人数, 
	    sum(il.money) as 认投金额 , 
	    sum(if(il.investor_money_status=3,il.money,0)) as 成功融资金额,
	    sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = d.id )) as 已发放筹款,
	    sum(if(d.pay_radio >0,if(il.investor_money_status=3,il.money,0)*pay_radio,if(il.investor_money_status=3,il.money,0)*".app_conf("PAY_RADIO").")) as 可获得佣金,
	    sum(u.cy_money) as 用户缴纳诚意金		
		from ".DB_PREFIX."investment_list as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and il.deal_id = d.id) where  ";
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " FROM_UNIXTIME(il.create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(il.create_time , '%Y-%m-%d') ";
		$model = D();
		
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(array('认投金额','时间','认投金额'),array('成功融资金额','时间','成功融资金额')),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //股权众筹-融资金额明细
	public function financing_amount_info(){
		$now=get_gmtime();
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = "   (FROM_UNIXTIME(il.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select 
		FROM_UNIXTIME(il.create_time +28800, '%Y-%m-%d') as 时间 ,	
		d.id as 项目编号 ,
		d.name as 项目名称, 
		count(il.user_id) as 投资人数, 
	    sum(il.money) as 认投金额 , 
	    sum(if(il.investor_money_status=3,il.money,0)) as 成功融资金额,	
		case 
			when d.is_success = 1 then '认投成功'
			when (d.end_time < $now and d.is_success = 0) then '认投失败'
			when (d.end_time > $now and d.begin_time < $now and d.is_success = 0) then '项目融资中'
			when (d.begin_time > $now and d.is_success = 0) then '项目预热中'
		else ''
		end 项目状态,
		if((select sum(repay_time) from ".DB_PREFIX."deal_order where order_status =3 and type = 0 and deal_id = d.id )>0,'已发放' ,'未发放') as  回报状态,
		(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = d.id ) as 已发放筹款,		
		if(d.pay_radio >0 and d.id= il.deal_id,sum(if(il.investor_money_status=3,il.money,0))*d.pay_radio,sum(if(il.investor_money_status=3,il.money,0))*".app_conf("PAY_RADIO").") as 可获得佣金			
		from ".DB_PREFIX."investment_list as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and il.deal_id = d.id) where  $condtion  ";
		
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and d.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
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
	//股权众筹-融资金额统计导出
	public function export_csv_financing_amount_total($page = 1){
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
		
		$sql_str = "select 
		FROM_UNIXTIME(il.create_time , '%Y-%m-%d') as sj,
	    count(il.user_id) as tzrs, 
	    sum(il.money) as rtje , 
	    sum(if(il.investor_money_status=3,il.money,0)) as cglzje,
	    sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = d.id )) as yffck,
	   sum(if(d.pay_radio >0,d.support_amount*pay_radio,d.support_amount*".app_conf("PAY_RADIO").")) as khdyj,
	    sum(u.cy_money) as yhjncyj		
		from ".DB_PREFIX."investment_list as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and il.deal_id = d.id) where  ";
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
									'tzrs'=>'""',
									'rtje'=>'""',
									'cglzje'=>'""',
									'yffck'=>'""',
									
									'khdyj'=>'""',
									'yhjncyj'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,投资人数,认投金额,成功融资金额,已发放筹款,待发放筹款,可获得佣金,用户缴纳诚意金");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['tzrs'] = iconv('utf-8','gbk','"' . $v['tzrs'] . '"');
				$total_value['rtje'] = iconv('utf-8','gbk','"' . $v['rtje'] . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . number_format($v['cglzje'],2) . '"');
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				$total_value['khdyj'] = iconv('utf-8','gbk','"' . number_format($v['khdyj'],2) . '"');
				$total_value['yhjncyj'] = iconv('utf-8','gbk','"' . number_format($v['yhjncyj'],2) . '"');
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
	public function export_csv_financing_amount_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = "   (FROM_UNIXTIME(il.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select 
		FROM_UNIXTIME(il.create_time+28800 , '%Y-%m-%d') as sj ,	
		d.id as xmbh ,
		d.name as xmmc, 
		count(il.user_id) as tzrs, 
	    sum(il.money) as rtje, 
	    sum(if(il.investor_money_status=3,il.money,0)) as cglzje,	
		case 
			when d.is_success = 1 then '认投成功'
			when (d.end_time < $now and d.is_success = 0) then '认投失败'
			when (d.end_time > $now and d.begin_time < $now and d.is_success = 0) then '项目融资中'
			when (d.begin_time > $now and d.is_success = 0) then '项目预热中'
		else ''
		end xmzt,
		if((select sum(repay_time) from ".DB_PREFIX."deal_order where order_status =3 and type = 0 and deal_id = d.id )>0,'已发放' ,'未发放') as  hbzt,
		(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = d.id ) as yffck,		
		if(d.pay_radio >0 and d.id= il.deal_id,d.support_amount*d.pay_radio,d.support_amount*".app_conf("PAY_RADIO").") as khdyj			
		from ".DB_PREFIX."investment_list as il left join (".DB_PREFIX."user as u,".DB_PREFIX."deal as d) on (u.id = il.user_id and il.deal_id = d.id) where  $condtion  ";
		
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and d.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
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
		
//		print_r($sql_str);exit; 
//		exit;
//		var_dump($list);exit;
		
		if($list)
		{
			//register_shutdown_function(array(&$this, 'export_csv_total'), $page+1);
			
			$total_value = array(
									'sj'=>'""',
									'xmbh'=>'""',
									'xmmc'=>'""',
									'tzrs'=>'""',
									'rtje'=>'""',
									'cglzje'=>'""',
									'xmzt'=>'""',
									'hbzt'=>'""',
									'yffck'=>'""',
									
									'khdyj'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,项目编号,项目名称,投资人数,认投金额,成功融资金额,项目状态,回报状态,已发放筹款,待发放筹款,可获得佣金");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['xmbh'] = iconv('utf-8','gbk','"' . $v['xmbh'] . '"');
				$total_value['xmmc'] = iconv('utf-8','gbk','"' . $v['xmmc'] . '"');
				$total_value['tzrs'] = iconv('utf-8','gbk','"' . $v['tzrs'] . '"');
				$total_value['rtje'] = iconv('utf-8','gbk','"' . number_format($v['rtje'],2) . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . $v['cglzje'] . '"');
				$total_value['xmzt'] = iconv('utf-8','gbk','"' . $v['xmzt'] . '"');
				$total_value['hbzt'] = iconv('utf-8','gbk','"' . $v['hbzt'] . '"');
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				$total_value['khdyj'] = iconv('utf-8','gbk','"' . number_format($v['khdyj'],2) . '"');
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