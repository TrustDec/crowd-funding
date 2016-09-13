<?php

class StatisticsMoneyTotalAction extends CommonAction{
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

	//回报众筹-金额统计
    public function money_total() 
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
		FROM_UNIXTIME(do.create_time , '%Y-%m-%d') as 时间 ,
		count(*) as 支持人数, 
		sum(do.total_price) as 筹款总额,
		sum(if(do.is_success =1,do.total_price,0)) as 成功筹款,		
		sum(if(do.is_success =0,do.total_price,0)) as 失败筹款,	
		(select sum(money) from ".DB_PREFIX."deal_pay_log where  FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).") group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ) as 已发放筹款,		
			
		sum(if(d.pay_radio >0,do.total_price*d.pay_radio,do.total_price*".app_conf("PAY_RADIO").")) as 可获得佣金,			
		sum(if(do.is_refund =1 and do.order_status =3,do.total_price,0)) as 已经退筹款			
		from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on d.id = do.deal_id where d.type = 0";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(do.create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(do.create_time , '%Y-%m-%d') ";
		
		$model = D();
		
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(
					array('支持人数','时间','支持人数'),
					array('筹款总额','时间','筹款总额'),
					array('成功筹款','时间','成功筹款'),
					array('失败筹款','时间','失败筹款'),
					array('已发放筹款','时间','已发放筹款'),
					array('待发放筹款','时间','待发放筹款'),
				),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //回报众筹-金额明细
	public function money_info(){
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(do.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		d.id as 项目编号 ,
		d.name as 项目名称, 
		count(*) as 支持人数,
		sum(if(do.order_status =3,do.total_price,0)) as 已筹金额,		
		case 
			when d.is_success = 1 then '项目成功'
			when (d.end_time < $now and d.is_success = 0) then '项目失败'
			when (d.end_time > $now and d.begin_time < $now and d.is_success = 0) then '项目进行中'
			when (d.begin_time > $now and d.is_success = 0) then '项目预热中'
		else ''
		end 项目状态,
		if(do.order_status =3 and do.repay_time >0,'已发放' ,'未发放') as  回报状态,
		(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = do.deal_id ) as 已发放筹款,		
		
		sum(if(d.pay_radio >0 and d.id= do.deal_id,do.total_price*d.pay_radio,do.total_price*".app_conf("PAY_RADIO").")) as 可获得佣金,			
		sum(if(do.is_refund =1 and do.order_status =3 and d.id= do.deal_id,do.total_price,0)) as 已经退筹款			
		from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_order as do on d.id = do.deal_id where d.type = 0 $condtion  ";
		
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and d.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		$sql_str .= "  GROUP BY d.id";	
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (do.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time between $begin_time and $end_time )";
			}
			
		}
		$model = D();
		
	//	echo $sql_str;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
	}
   
	//回报众筹-金额统计
	public function export_csv_money_total($page = 1){
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
                FROM_UNIXTIME(do.create_time , '%Y-%m-%d') as sj ,
		count(*) as zcrs, 
		sum(do.total_price) as ckze,
		sum(if(do.is_success =1,do.total_price,0)) as cgck,		
		sum(if(do.is_success =0,do.total_price,0)) as sbck,	
		(select sum(money) from ".DB_PREFIX."deal_pay_log where  FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).") group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ) as yffck,		
			
		sum(if(d.pay_radio >0,do.total_price*d.pay_radio,do.total_price*".app_conf("PAY_RADIO").")) as khdyj,			
		sum(if(do.is_refund =1 and do.order_status =3,do.total_price,0)) as yjtke			
		from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on d.id = do.deal_id where d.type = 0";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(do.create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(do.create_time , '%Y-%m-%d') ";
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
 
		if($list)
		{

			$total_value = array(
									'sj'=>'""',
									'zcrs'=>'""',
									'ckze'=>'""',
									'cgck'=>'""',
									'sbck'=>'""',
									'yffck'=>'""',
									
									'khdyj'=>'""',
									'yjtke'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,支持人数,筹款总额,成功筹款,失败筹款,已发放筹款,可获得佣金,已经退筹款");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['zcrs'] = iconv('utf-8','gbk','"' . $v['zcrs'] . '"');
				$total_value['ckze'] = iconv('utf-8','gbk','"' . number_format($v['ckze'],2) . '"');
				$total_value['cgck'] = iconv('utf-8','gbk','"' . number_format($v['cgck'],2) . '"');
				$total_value['sbck'] = iconv('utf-8','gbk','"' . number_format($v['sbck'],2) . '"');
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				//$total_value['dffck'] = iconv('utf-8','gbk','"' . number_format($v['dffck'],2) . '"');
				$total_value['khdyj'] = iconv('utf-8','gbk','"' . number_format($v['khdyj'],2) . '"');
				$total_value['yjtke'] = iconv('utf-8','gbk','"' . number_format($v['yjtke'],2) . '"');
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
	 //回报众筹-金额明细
	public function export_csv_money_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(do.create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		d.id as xmbh ,
		d.name as xmmc, 
		d.support_count as zcrs,
		d.support_amount as ycje,		
		case 
			when d.is_success = 1 then '项目成功'
			when (d.end_time < $now and d.is_success = 0) then '项目失败'
			when (d.end_time > $now and d.begin_time < $now and d.is_success = 0) then '项目进行中'
			when (d.begin_time > $now and d.is_success = 0) then '项目预热中'
		else ''
		end xmzt,
		if(do.order_status =3 and do.repay_time >0,'已发放' ,'未发放') as  hbzt,
		(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = do.deal_id ) as yffck,		
		
		sum(if(d.pay_radio >0 and d.id= do.deal_id,d.support_amount*d.pay_radio,d.support_amount*".app_conf("PAY_RADIO").")) as yhdyj,			
		sum(if(do.is_refund =1 and do.order_status =3 and d.id= do.deal_id,do.total_price,0)) as yjtck			
		from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on d.id = do.deal_id where d.type = 0 $condtion  ";
		
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and d.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		$sql_str .= "  GROUP BY d.id";	
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (do.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time between $begin_time and $end_time )";
			}
			
		}
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		if($list)
		{
			$total_value = array(
									'xmbh'=>'""',
									'xmmc'=>'""',
									'zcrs'=>'""',	
									'ycje'=>'""',	
									'xmzt'=>'""',	
									'hbzt'=>'""',	
									'yffhb'=>'""',	
									
									'khdyj'=>'""',	
									'yjtck'=>'""',								
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","项目编号,项目名称,支持人数,已筹金额,项目状态,回报状态,已发放筹,可获得佣金,已经退筹款");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['xmbh'] = iconv('utf-8','gbk','"' . $v['xmbh'] . '"');
				$total_value['xmmc'] = iconv('utf-8','gbk','"' . $v['xmmc'] . '"');				
				$total_value['zcrs'] = iconv('utf-8','gbk','"' . $v['zcrs'] . '"');
				$total_value['ycje'] = iconv('utf-8','gbk','"' . number_format($v['ycje'],2) . '"');
				$total_value['xmzt'] = iconv('utf-8','gbk','"' . $v['xmzt'] . '"');
				$total_value['hbzt'] = iconv('utf-8','gbk','"' . $v['hbzt'] . '"');
				$total_value['yffhb'] = iconv('utf-8','gbk','"' . number_format($v['yffhb'],2) . '"');
				$total_value['dffhb'] = iconv('utf-8','gbk','"' . number_format($v['dffhb'],2) . '"');
				$total_value['khdyj'] = iconv('utf-8','gbk','"' . number_format($v['khdyj'],2) . '"');
				$total_value['yjtck'] = iconv('utf-8','gbk','"' . number_format($v['yjtck'],2) . '"');
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