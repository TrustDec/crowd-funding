<?php

class StatisticsMoneyInchangeTotalAction extends CommonAction{
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
	
     //充值统计
    public function money_inchange_total() 
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
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间, sum(money) as 成功充值总额
		from ".DB_PREFIX."payment_notice  where deal_name ='' and  is_paid = 1 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		$model = D();	
		$voList = $this->_Sql_list($model, $sql_str,'时间', false);
	
		$this->display();		
    }
    //充值明细
    public function money_inchange_info() 
    {
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(a.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time +28800, '%Y-%m-%d') as 时间 ,
		a.notice_sn as 支付单号,
		u.user_name as 会员名称, 
		a.money as 应付金额, 
		(select name from ".DB_PREFIX."payment where id = a.payment_id )  as 支付方式, 
		if(a.is_paid>0,'是','否') as 支付状态 ,
		a.memo as 支付备注 
		from ".DB_PREFIX."payment_notice as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.deal_name ='' and a.is_paid = 1  $condtion  ";
		
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
     
	//充值统计导出
	public function export_csv_money_inchange_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj, sum(money) as cgczze
		from ".DB_PREFIX."payment_notice  where deal_name ='' and  is_paid = 1 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
		if($list)
		{
		
			$total_value = array(
									'sj'=>'""',
									'cgczze'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,成功充值总额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['cgczze'] = iconv('utf-8','gbk','"' . number_format($v['cgczze'],2) . '"');
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
	 //充值明细导出
	public function export_csv_money_inchange_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(a.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time+28800, '%Y-%m-%d') as sj ,
		a.notice_sn as zfdh,
		u.user_name as hymc, 
		a.money as yfje, 
		(select name from ".DB_PREFIX."payment where id = a.payment_id )  as zffs, 
		if(a.is_paid>0,'是','否') as zfzt ,
		a.memo as zfbz 
		from ".DB_PREFIX."payment_notice as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.deal_name ='' and a.is_paid = 1  $condtion  ";
		
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
									'zfdh'=>'""',
									'hymc'=>'""',	
									'yhje'=>'""',
									'zffs'=>'""',
									'zfzt'=>'""',
									'zfbz'=>'""',
																
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,支付单号,会员名称,应付金额,支付方式,支付状态,支付备注");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['zfdh'] = iconv('utf-8','gbk','"' . $v['zfdh'] . '"');				
				$total_value['hymc'] = iconv('utf-8','gbk','"' . $v['hymc'] . '"');
				$total_value['yhje'] = iconv('utf-8','gbk','"' . number_format($v['yhje'],2) . '"');
				$total_value['zffs'] = iconv('utf-8','gbk','"' . $v['zffs'] . '"');
				$total_value['zfzt'] = iconv('utf-8','gbk','"' . $v['zfzt'] . '"');
				$total_value['zfbz'] = iconv('utf-8','gbk','"' . $v['zfbz'] . '"');
				
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