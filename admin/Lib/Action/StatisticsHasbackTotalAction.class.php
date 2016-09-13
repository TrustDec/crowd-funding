<?php

class StatisticsHasbackTotalAction extends CommonAction{
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
	public function hasback_total() 
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
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间, sum(if(repay_time>0 ,1,0)) as 已回报人数,sum(if(repay_time>0 , 1,0)) as 已回报项目, sum(if(repay_time='', 1,0)) as 待回报项目 
		from ".DB_PREFIX."deal_order   where order_status =3 and type = 0 and is_success =1 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$model = D();
		
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		//print_r($sql_str);exit;
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(
					array('已回报人数','时间','已回报人数'),
					array('已回报项目','时间','已回报项目'),
					array('待回报项目','时间','待回报项目'),
				),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //回报众筹-回报明细
	public function hasback_info(){
		
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
		a.deal_id as 项目编号 ,
		d.name as 项目名称,
		sum(if(a.repay_time>0,1,0)) as 已回报人数,
		sum(if(a.repay_time<=0,1,0)) as 待回报人数
		from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."deal as d on d.id = a.deal_id
		where a.order_status =3 and a.type = 0 and a.is_success =1  $condtion  ";
		
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
		$sql_str .= "group by d.id ";
		
		$model = D();
		
		//echo $sql_str;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
	}
	//回报众筹-回报统计导出
	public function export_csv_hasback_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj, sum(if(repay_time>0 ,1,0)) as yhbrs,sum(if(repay_time>0 , 1,0)) as yhbxm, sum(if(repay_time='', 1,0)) as dhbxm 
		from ".DB_PREFIX."deal_order  where order_status =3 and type = 0 and is_success =1 ";
		
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
									'yhbrs'=>'""',
									'yhbxm'=>'""',
									'dhbxm'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,已回报人数,已回报项目,待回报项目");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['yhbrs'] = iconv('utf-8','gbk','"' . $v['yhbrs'] . '"');
				$total_value['yhbxm'] = iconv('utf-8','gbk','"' . $v['yhbxm'] . '"');
				$total_value['dhbxm'] = iconv('utf-8','gbk','"' . $v['dhbxm'] . '"');				
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
	 //回报众筹-回报明细导出
	public function export_csv_hasback_info($page = 1){
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
		a.deal_id as xmbh ,
		d.name as xmmc,
		sum(if(a.repay_time>0,1,0)) as yhbrs,
		sum(if(a.repay_time<=0,1,0)) as dhbrs
		from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."deal as d on d.id = a.deal_id
		where a.order_status =3 and a.type = 0 and a.is_success =1  $condtion  ";
		
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
		$sql_str .= "group by d.id ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		if($list)
		{
			$total_value = array(
									'xmbh'=>'""',
									'xmmc'=>'""',
									'yhbrs'=>'""',
									'dhbrs'=>'""',								
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","项目编号,预计回报时间,项目名称,已回报人数,待回报人");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['xmbh'] = iconv('utf-8','gbk','"' . $v['xmbh'] . '"');		
				$total_value['xmmc'] = iconv('utf-8','gbk','"' . $v['xmmc'] . '"');
				$total_value['yhbrs'] = iconv('utf-8','gbk','"' . $v['yhbrs'] . '"');
				$total_value['dhbrs'] = iconv('utf-8','gbk','"' . $v['dhbrs'] . '"');
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