<?php

class StatisticsMoneyCarryBankTotalAction extends CommonAction{
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
	
     //提现统计
    public function money_carry_bank_total() 
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
	//	print_r(strtotime('2015-04-25'));exit;
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间, count(*) as 人次, sum(if(is_pay>0,money,0)) as 成功提现总额,  sum(money) as 申请提现总额
		from ".DB_PREFIX."user_refund where ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= "  FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		$model = D();	
		$voList = $this->_Sql_list($model, $sql_str,'时间', false);
	
		$this->display();		
    }
    //提现明细
    public function money_carry_bank_info() 
    {
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = "  (FROM_UNIXTIME(a.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time+28800, '%Y-%m-%d %H:%i:%S') as 时间 ,
		u.user_name as 会员名称, 
		a.money as 提现金额, 
		if(a.is_pay>0,'是','否') as 提现状态, 		
		FROM_UNIXTIME(a.pay_time, '%Y-%m-%d %H:%i:%S') as 处理时间 
		from ".DB_PREFIX."user_refund as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where $condtion  ";
		
		if($user_name){
			$sql_str="$sql_str and u.user_name like '%$user_name%'";
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time between $begin_time and $end_time )";
			}
			
		}
		
		$model = D();
		
		//echo $sql_str;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
    }
  
	//提现统计导出
	public function export_csv_money_carry_bank_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
	//	print_r(strtotime('2015-04-25'));exit;
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj, count(*) as rc, sum(if(is_pay>0,money,0)) as cgtxje,  sum(money) as sqtxze
		from ".DB_PREFIX."user_refund where ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= "  FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
		if($list)
		{
		
			$total_value = array(
									'sj'=>'""',
									'rc'=>'""',
									'cgtxje'=>'""',
									'sqtxze'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,人次,成功提现总额,申请提现总额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['rc'] = iconv('utf-8','gbk','"' . $v['rc'] . '"');
				$total_value['cgtxje'] = iconv('utf-8','gbk','"' . number_format($v['cgtxje'],2) . '"');
				$total_value['sqtxze'] = iconv('utf-8','gbk','"' . number_format($v['sqtxze'],2) . '"');
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
	 //提现明细导出
	public function export_csv_money_carry_bank_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = "  (FROM_UNIXTIME(a.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time+28800, '%Y-%m-%d %H:%i:%S') as sj ,
		u.user_name as hymc, 
		a.money as txje, 
		if(a.is_pay>0,'是','否') as txzt, 		
		FROM_UNIXTIME(a.pay_time, '%Y-%m-%d %H:%i:%S') as clsj 
		from ".DB_PREFIX."user_refund as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where $condtion  ";
		
		if($user_name){
			$sql_str="$sql_str and u.user_name like '%$user_name%'";
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time between $begin_time and $end_time )";
			}
			
		}
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		if($list)
		{
			$total_value = array(
									'sj'=>'""',
									'hymc'=>'""',
									'txje'=>'""',	
									'txzt'=>'""',
									'clsj'=>'""',
						
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,会员名称,提现金额,提现状态,处理时间 ");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['hymc'] = iconv('utf-8','gbk','"' . $v['hymc'] . '"');				
				$total_value['txje'] = iconv('utf-8','gbk','"' . number_format($v['txje'],2) . '"');
				$total_value['txzt'] = iconv('utf-8','gbk','"' . number_format($v['txzt'],2) . '"');
				$total_value['clsj'] = iconv('utf-8','gbk','"' . $v['clsj'] . '"');
				
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