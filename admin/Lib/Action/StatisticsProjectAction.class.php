<?php

class StatisticsProjectAction extends CommonAction{
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
	
	//产品众筹项目统计
    public function project() 
    {
    	if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$sql_str = "select 
		sum(support_count) as 支持人数,
		sum(support_amount) as 筹款总额, 
		sum(if(is_success =1, support_amount,0)) as 成功筹款,
		sum(if(is_success =0, support_amount,0)) as 失败筹款,
		count(*) as 项目总数,
		sum(if(is_success =1,1,0)) as 成功项目数,
		sum(if(is_success =0 and end_time < $now,1,0)) as 失败项目数,
		sum(if(".$now." < end_time and ".$now." > begin_time,1,0)) as 进行中项目数,
		sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id )) as 已发放筹款,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
		sum((select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 0 and deal_id = a.id )) as 待发放筹款,
		sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO")."))   as 可获得佣金
		from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =0 and 1 = 1   ";
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.begin_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.end_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.begin_time > $begin_time and a.end_time < $end_time )";
			}
			
		}
 		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);		
		$this->display();		
    }
    //产品众筹单项目统计详细页面
    public function project_info() 
    {
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$sql_str = "select 
			id as 项目编号,
			name as 项目名称, 
			support_count as 支持人数,
			support_amount as 已筹金额,
			case 
			when is_success = 1 then '项目成功'
			when (end_time < $now and is_success = 0) then '项目失败'
			when (end_time > $now and begin_time < $now and is_success = 0) then '项目进行中'
			when (begin_time > $now and is_success = 0) then '项目预热中'
			else ''
			end 项目状态,
			if((select sum(repay_time) from ".DB_PREFIX."deal_order where order_status =3 and type = 0 and deal_id = a.id )>0,'已发放' ,'未发放') as  回报状态,
			(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id ) as 已发放筹款,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 0 and deal_id = a.id ) as 待发放筹款,
			if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").") as 可获得佣金,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =1 and order_status =3 and type = 0 and deal_id = a.id ) as 已经退筹款
			from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =0 and 1 = 1 ";
	
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and a.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and a.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.begin_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.end_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.begin_time > $begin_time and a.end_time < $end_time )";
			}
			
		}
		if(intval($_REQUEST['time_status'])>0)
		{
			if(intval($_REQUEST['time_status']) ==1){
				$sql_str .= " and a.is_success = 0 and  a.begin_time > ".$now."  ";
			}
			elseif(intval($_REQUEST['time_status']) ==2){
				$sql_str .= " and a.is_success = 0 and  a.end_time > ".$now." and a.begin_time < ".$now."  ";
			}
			elseif(intval($_REQUEST['time_status']) ==3){
				$sql_str .= " and a.is_success = 0 and  a.end_time < ".$now."  ";
			}	
			else{
				$sql_str .= " and a.is_success = 1";
			}	
		}
		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
	
		$this->display();
    }

	public function export_csv_project($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$sql_str = "select 
		sum(support_count) as zcrs,
		sum(support_amount) as ckze, 
		sum(if(is_success =1,support_amount,0)) as cgck,
		sum(if(is_success =0,support_amount,0)) as sbck,
		count(*) as xmzs,
		sum(if(is_success =1,1,0)) as cgxms,
		sum(if(is_success =0,1,0)) as sbxms,
		sum(if(".$now." < end_time and ".$now." > begin_time,1,0)) as jxzxms,
		sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id )) as yffck,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
		sum((select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 0 and deal_id = a.id )) as dffck,
		sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").")) as khdyj
		from fanwe_deal a where is_effect = 1 and is_delete=0 and type =0 and type = 0 and 1 = 1  ";
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.begin_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.end_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.begin_time > $begin_time and a.end_time < $end_time )";
			}
			
		}
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
//		echo $sql_str;
//		exit;
//		var_dump($list);exit;
		
		if($list)
		{
			//register_shutdown_function(array(&$this, 'export_csv_total'), $page+1);
			
			$total_value = array(
									'zcrs'=>'""',
									'ckze'=>'""',
									'cgck'=>'""',
									'sbck'=>'""',
									'xmzs'=>'""',
									'cgxms'=>'""',
									'sbxms'=>'""',
									'jxzxms'=>'""',
									'yffck'=>'""',
									'dffck'=>'""',
									'khdyj'=>'""'
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","支持人数,筹款总额,成功筹款,失败筹款,项目总数,成功项目数,失败项目数,进行中项目数,已发放筹款,待发放筹款,可获得佣金");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['zcrs'] = iconv('utf-8','gbk','"' . $v['zcrs'] . '"');
				$total_value['ckze'] = iconv('utf-8','gbk','"' . number_format($v['ckze'],2) . '"');
				$total_value['cgck'] = iconv('utf-8','gbk','"' . number_format($v['cgck'],2) . '"');
				$total_value['sbck'] = iconv('utf-8','gbk','"' . number_format($v['sbck'],2) . '"');
				$total_value['xmzs'] = iconv('utf-8','gbk','"' . $v['xmzs'] . '"');
				$total_value['cgxms'] = iconv('utf-8','gbk','"' . $v['cgxms'] . '"');
				$total_value['sbxms'] = iconv('utf-8','gbk','"' . $v['sbxms'] . '"');
				$total_value['jxzxms'] = iconv('utf-8','gbk','"' . $v['jxzxms'] . '"');
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				$total_value['dffck'] = iconv('utf-8','gbk','"' . number_format($v['dffck'],2) . '"');
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
	 //产品众筹单项目统计详细页面导出
	public function export_csv_project_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$sql_str = "select 
			id as xmbh,
			name as xmmc, 
			support_count as zcrs,
			if(is_success =1, support_amount,0) as ycje,
			case 
			when is_success = 1 then '项目成功'
			when (end_time < $now and is_success = 0) then '项目失败'
			when (end_time > $now and begin_time < $now and is_success = 0) then '项目进行中'
			when (begin_time > $now and is_success = 0) then '项目预热中'
			else ''
			end xmzt,
			if((select sum(repay_time) from ".DB_PREFIX."deal_order where order_status =3 and type = 0 and deal_id = a.id )>0,'已发放' ,'未发放') as  hbzt,
			(select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id ) as yffck,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 0 and deal_id = a.id ) as dffck,
			if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").") as khdyj,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =1 and order_status =3 and type = 0 and deal_id = a.id ) as yjtck
			from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =0 and 1 = 1 ";
	
		if(intval($_REQUEST['id'])!='')
		{
			$sql_str .= " and a.id =".intval($_REQUEST['id'])."  ";	
		}
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and a.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.begin_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.end_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.begin_time > $begin_time and a.end_time < $end_time )";
			}
			
		}
		if(intval($_REQUEST['time_status'])>0)
		{
			if(intval($_REQUEST['time_status']) ==1){
				$sql_str .= " and a.is_success = 0 and  a.begin_time > ".$now."  ";
			}
			elseif(intval($_REQUEST['time_status']) ==2){
				$sql_str .= " and a.is_success = 0 and  a.end_time > ".$now." and a.begin_time < ".$now."  ";
			}
			elseif(intval($_REQUEST['time_status']) ==3){
				$sql_str .= " and a.is_success = 0 and  a.end_time < ".$now."  ";
			}	
			else{
				$sql_str .= " and a.is_success = 1";
			}	
		}
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		
//		echo $sql_str;
//		exit;
//		var_dump($list);exit;
		
		if($list)
		{
			//register_shutdown_function(array(&$this, 'export_csv_total'), $page+1);
			
			$total_value = array(
									'xmbh'=>'""',
									'xmmc'=>'""',
									'zcrs'=>'""',
									'ycje'=>'""',
									'xmzt'=>'""',
									'hbzt'=>'""',
									'yffck'=>'""',
									'dffck'=>'""',
									'khdyj'=>'""',
									'yjtck'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","项目编号,项目名称,支持人数,已筹金额,项目状态,回报状态,已发放筹款,待发放筹款,可获得佣金,已经退筹款");
	    	  
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
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				$total_value['dffck'] = iconv('utf-8','gbk','"' . number_format($v['dffck'],2) . '"');
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