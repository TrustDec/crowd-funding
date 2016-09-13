<?php

class StatisticsSiteCostsTotalAction extends CommonAction{
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
	
	//平台统计-网站费用统计
    public function site_costs_total() 
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
		(select count(*) from ".DB_PREFIX."user where is_effect = 1 ) as 关联用户数量,  
		sum(if(is_success =1 and type = 0, support_amount,0)) as 回报众筹成功筹款,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
		sum(if(is_success =1 and type = 1, support_amount,0)) as 股权众筹成功筹款,  
		sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO")."))   as 可获得佣金,
		sum((select sum(cy_money) from ".DB_PREFIX."user)) as 用户缴纳诚意金
		from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and 1 = 1   ";
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
    //网站收益明细
    public function site_costs_info() 
    {
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$sql_str = "select 
			u.user_name as 会员名称,
			a.total_price as 操作金额, 
			FROM_UNIXTIME(a.create_time +28800, '%Y-%m-%d %H:%i:%S') as 操作时间,
			case 
			when type = 0 then '回报众筹'
			when type = 1 then '股权众筹'
			else ''
			end 项目类型
			from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."user as u on u.id = a.user_id  where a.order_status =3 and 1 = 1 ";
	
		if(trim($_REQUEST['user_name'])!='')
		{
			$sql_str .= " and u.user_name like '%".trim($_REQUEST['user_name'])."%'  ";	
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.create_time > $begin_time and a.create_time < $end_time )";
			}
			
		}
		if(intval($_REQUEST['type'])<=1)
		{
			$sql_str .= " and a.type = ".intval($_REQUEST['type'])."  ";	
		}
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
	
		$this->display();
    }

	 //网站收益明细
	public function export_csv_site_costs_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$sql_str = "select 
			u.user_name as hymc,
			a.total_price as czzj, 
			FROM_UNIXTIME(a.create_time+28800 , '%Y-%m-%d %H:%i:%S') as czsj,
			case 
			when type = 0 then '回报众筹'
			when type = 1 then '股权众筹'
			else ''
			end xmlx
			from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."user as u on u.id = a.user_id  where a.order_status =3 and 1 = 1 ";
	
		if(trim($_REQUEST['user_name'])!='')
		{
			$sql_str .= " and u.user_name like '%".trim($_REQUEST['user_name'])."%'  ";	
		}
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str .= " and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str .= " and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str .= " and (a.create_time > $begin_time and a.create_time < $end_time )";
			}
			
		}
		if(intval($_REQUEST['type'])<=1)
		{
			$sql_str .= " and a.type = ".intval($_REQUEST['type'])."  ";	
		}
		
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);	
		if($list)
		{
			$total_value = array(
									'hymc'=>'""',
									'czzj'=>'""',
									'czsj'=>'""',
									'xmlx'=>'""',								
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","会员名称,操作金额,操作时间,项目类型");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['hymc'] = iconv('utf-8','gbk','"' . $v['hymc'] . '"');
				$total_value['czzj'] = iconv('utf-8','gbk','"' . $v['czzj'] . '"');
				$total_value['czsj'] = iconv('utf-8','gbk','"' . $v['czsj'] . '"');
				$total_value['xmlx'] = iconv('utf-8','gbk','"' . $v['xmlx'] . '"');
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