<?php

class StatisticsAction extends CommonAction{
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
    //产品众筹人数统计
    public function usernum_total() 
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
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间, count( user_id) as 支持人数, sum(total_price) as 支持总额 
		from ".DB_PREFIX."deal_order  where order_status =3 and type = 0 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$model = D();
	//	print_r($sql_str);exit;
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(array('支持人数','时间','支持人数'),array('支持总额','时间','支持总额')),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //产品众筹支持明细
	public function usernum_info(){
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(a.create_time, '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time +28800, '%Y-%m-%d %H:%i:%S') as 时间 ,
		u.user_name as 用户名,
		a.total_price as 支持金额
		from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.order_status =3 and a.type = 0  $condtion  ";
		
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
	//回报众筹-回报统计
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
	//回报众筹-逾期统计
    public function overdue_total() 
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
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 回报时间, sum(if(is_refund =0,1,0)) as 逾期未回报项目数
		from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 0 and is_success =1 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '回报时间', false);
		
		
		$this->display();		
    }
    //回报众筹-逾期明细
	public function overdue_info(){
		
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
		d.id as 项目编号, 
		d.name as 项目名称,	
		sum(if(do.repay_time>0,1,0)) as 已回报人数,
		sum(if(do.repay_time<=0,1,0)) as 待回报人数
		from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on d.id = do.deal_id  where do.is_refund =0 and do.order_status =3 and do.type = 0 and do.is_success =1  $condtion  ";
		
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (do.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time between $begin_time and $end_time )";
			}
			
		}
		$sql_str .= "group by d.id ";
		$model = D();
		
		//echo $sql_str;exit;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
	}
	//股权众筹项目统计
    public function investe_total() 
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
		sum(support_count) as 投资人数,
		sum(invote_money) as 认投金额, 
		sum(support_amount) as 成功融资金额,		
		count(*) as 项目总数,
		sum(if(is_success =1,1,0)) as 成功项目数,
		sum(if(is_success =0 and ".$now." > end_time,1,0)) as 失败项目数,
		sum(if(".$now." < end_time and ".$now." > begin_time,1,0)) as 进行中项目数,
		sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id )) as 已发放筹款,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
		sum((select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 1 and deal_id = a.id )) as 待发放筹款,
		sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO")."))  as 可获得佣金,
		sum((select sum(cy_money) from ".DB_PREFIX."user)) as 用户缴纳诚意金
		from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =1 and 1 = 1   ";
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
    //股权众筹单项目统计详细页面
    public function investe_info() 
    {
    	$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$now=get_gmtime();
		$sql_str = "select 
			id as 项目编号,
			name as 项目名称, 
			support_count as 投资人数,
			invote_money as 认投金额, 
			support_amount as 成功融资金额,
			case 
			when is_success = 1 then '项目成功'
			when (end_time < $now and is_success = 0) then '项目失败'
			when (end_time > $now and begin_time < $now and is_success = 0) then '项目进行中'
			when (begin_time > $now and is_success = 0) then '项目预热中'
			else ''
			end 项目状态,
			(select money from ".DB_PREFIX."deal_pay_log where deal_id = a.id ) as 已发放筹款,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 1 and deal_id = a.id ) as 待发放筹款,
			if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").") as 可获得佣金
			from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =1 and 1 = 1 ";
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
		$sql_str .= "group by a.id ";
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		//print_r($sql_str);exit;
		$this->display();
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
	//股权众筹-违约统计
    public function breach_convention_total() 
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
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间, count(user_id) as 违约人数, sum(money) as 违约金额
		from ".DB_PREFIX."investment_list  where investor_money_status =4  ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		
		$model = D();
		//echo $sql_str;exit;
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(array('违约人数','时间','违约人数'),array('违约金额','时间','违约金额')),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //违约统计明细
	public function breach_convention_info(){
		
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
		u.user_name as 会员名称, 
		count(*) as 违约次数,
		sum(a.money) as 违约金额
		from ".DB_PREFIX."investment_list as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.investor_money_status =4  $condtion  ";

		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time between $begin_time and $end_time )";
			}
			
		}
		$sql_str .= "group by u.id ";
		$model = D();
		
		//echo $sql_str;
		$voList = $this->_Sql_list($model, $sql_str, '时间', false);
		
		$this->display();		
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
    //用户统计
    public function user_total() 
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
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as 时间,count(*) as 注册人数, sum(if(is_investor=0,1,0)) as 普通会员, sum(if(is_investor=1,1,0)) as 投资人, sum(if(is_investor=2,1,0)) as 投资机构人
		from ".DB_PREFIX."user where is_effect = 1 ";
		
		//日期期间使用in形式,以确保能正常使用到索引
		
		if( isset($map['start_time']) && $map['start_time'] <> '' && isset($map['end_time']) && $map['end_time'] <> ''){
			$sql_str .= " and FROM_UNIXTIME(create_time , '%Y-%m-%d') in (".date_in($map['start_time'],$map['end_time']).")";
		}
		$sql_str .= "  group by FROM_UNIXTIME(create_time , '%Y-%m-%d') ";
		//print_r($sql_str);exit;
		$model = D();
		
		$voList = $this->_Sql_list($model, $sql_str, "&".$parameter, '时间', false);
		
		require('./admin/Tpl/default/Common/js/flash/php-ofc-library/open-flash-chart.php');
		$total_array=array(
				array(
					array('注册人数','时间','注册人数'),
					array('普通会员','时间','普通会员'),
					array('投资人','时间','投资人'),
					array('投资机构人','时间','投资机构人')
				),
		);
		krsort($voList);
		$chart_list=$this->get_jx_json_all($voList,$total_array);
		$this->assign("chart_list",$chart_list);
		
		//dump($chart_list);
		
		$this->display();		
    }
    //用户明细
	public function user_info(){
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(create_time+28800, '%Y-%m-%d %H:%i:%S') as 注册时间 ,
		user_name as 会员名称, 
		email as 会员邮箱, 
		mobile as 手机号,
		money as 会员余额,
		case 
			when is_investor = 0 then '普通会员'
			when is_investor = 1 then'投资人' 
			when is_investor = 2 then'投资机构人'
		else ''
		end 会员类型	
		from ".DB_PREFIX."user 
		where is_effect = 1  $condtion  ";
		
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
    //产品众筹回报项目统计导出
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
	
	//产品众筹人数统计导出
	public function export_csv_usernum_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj, count(DISTINCT user_id) as zcrs, sum(total_price) as zcze 
		from ".DB_PREFIX."deal_order  where order_status =3 and type = 0 ";
		
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
									'zcrs'=>'""',
									'zcze'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,支持人数,支持总额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	//print_r($list);exit;
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['zcrs'] = iconv('utf-8','gbk','"' . $v['zcrs'] . '"');
				$total_value['zcze'] = iconv('utf-8','gbk','"' . number_format($v['zcze'],2) . '"');
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
	 //产品众筹支持明细导出
	public function export_csv_usernum_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(a.create_time, '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(a.create_time+28800, '%Y-%m-%d %H:%i:%S') as sj ,
		u.user_name as yhm,
		a.total_price as zcje
		from ".DB_PREFIX."deal_order as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.order_status =3 and a.type = 0  $condtion  ";
		
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
									'yhm'=>'""',
									'zcje'=>'""',								
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,用户名,支持金额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['yhm'] = iconv('utf-8','gbk','"' . $v['yhm'] . '"');				
				$total_value['zcje'] = iconv('utf-8','gbk','"' . number_format($v['zcje'],2) . '"');
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
	//回报众筹-逾期统计导出
	public function export_csv_overdue_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as hbsj, sum(if(is_refund =0,1,0)) as yqwhbxms
		from ".DB_PREFIX."deal_order where order_status =3 and type = 0 and is_success =1";
		
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
									'hbsj'=>'""',
									'yqwhbxms'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","回报时间,逾期未回报项目数");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['hbsj'] = iconv('utf-8','gbk','"' . $v['hbsj'] . '"');
				$total_value['yqwhbxms'] = iconv('utf-8','gbk','"' . $v['yqwhbxms'] . '"');				
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
	//回报众筹-逾期明细导出
	public function export_csv_overdue_info($page = 1){
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
		d.id as xmbh, 
		d.name as xmmc,	
		sum(if(do.repay_time>0,1,0)) as yhbrs,
		sum(if(do.repay_time<=0,1,0)) as dhbrs
		from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on d.id = do.deal_id  where do.order_status =3 and do.type = 0 and do.is_success =1  $condtion  ";
		
		
		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (do.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (do.create_time between $begin_time and $end_time )";
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
	    	$content_total = iconv("utf-8","gbk","项目编号,预计回报时间,项目名称,已回报人数,待回报人数");
	    	  
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
	//股权众筹股权项目统计导出
	public function export_csv_investe_total($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$sql_str = "select 
		sum(support_count) as tzrs,
		sum(invote_money) as rgje, 
		sum(support_amount) as cglzje,		
		count(*) as xmzs,
		sum(if(is_success =1,1,0)) as cgxms,
		sum(if(is_success =0,1,0)) as sbxms,
		sum(if(".$now." < end_time and ".$now." > begin_time,1,0)) as jxzxms,
		sum((select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id = a.id )) as yffck,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
		sum((select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 1 and deal_id = a.id )) as dffck,
		sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO")."))  as khdyj,
		sum((select sum(cy_money) from ".DB_PREFIX."user)) as yhjncyj
		from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =1 and 1 = 1   ";
		
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
									'tzrs'=>'""',
									'rgje'=>'""',
									'cglzje'=>'""',
									'xmzs'=>'""',
									'cgxms'=>'""',
									'sbxms'=>'""',
									'jxzxms'=>'""',
									'yffck'=>'""',
									'dffck'=>'""',
									'khdyj'=>'""',
									'yhjncyj'=>'""'
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","投资人数,认投金额,成功融资金额,项目总数,成功项目数,失败项目数,进行中项目数,已发放筹款,待发放筹款,可获得佣金,用户缴纳诚意金");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['tzrs'] = iconv('utf-8','gbk','"' . $v['tzrs'] . '"');
				$total_value['rgje'] = iconv('utf-8','gbk','"' . number_format($v['rgje'],2) . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . number_format($v['cglzje'],2) . '"');
				$total_value['xmzs'] = iconv('utf-8','gbk','"' . $v['xmzs'] . '"');
				$total_value['cgxms'] = iconv('utf-8','gbk','"' . $v['cgxms'] . '"');
				$total_value['sbxms'] = iconv('utf-8','gbk','"' . $v['sbxms'] . '"');
				$total_value['jxzxms'] = iconv('utf-8','gbk','"' . $v['jxzxms'] . '"');
				$total_value['yffck'] = iconv('utf-8','gbk','"' . number_format($v['yffck'],2) . '"');
				$total_value['dffck'] = iconv('utf-8','gbk','"' . number_format($v['dffck'],2) . '"');
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
	 //股权众筹股权项目统计详细页面导出
	public function export_csv_investe_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		$sql_str = "select 
			id as xmbh,
			name as xmmc, 
			support_count as tzrs,
			invote_money as rgje, 
			support_amount as cglzje,
			case 
			when is_success = 1 then '项目成功'
			when (end_time < $now and is_success = 0) then '项目失败'
			when (end_time > $now and begin_time < $now and is_success = 0) then '项目进行中'
			when (begin_time > $now and is_success = 0) then '项目预热中'
			else ''
			end xmzt,
			(select money from ".DB_PREFIX."deal_pay_log where deal_id = a.id ) as yffck,
			(select sum(total_price) from ".DB_PREFIX."deal_order where is_refund =0 and order_status =3 and type = 1 and deal_id = a.id ) as dffck,
			if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").") as khdyj
			from ".DB_PREFIX."deal as a where is_effect = 1 and is_delete=0 and type =1 and 1 = 1 ";
	
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
		$sql_str .= "group by a.id ";
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
									'tzrs'=>'""',
									'rgje'=>'""',
									'cglzje'=>'""',
									'xmzt'=>'""',
									'yffck'=>'""',
									'dffck'=>'""',
									'khdyj'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","项目编号,项目名称,投资人数,认投金额,成功融资金额,项目状态,已发放筹款,待发放筹款,可获得佣金");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['xmbh'] = iconv('utf-8','gbk','"' . $v['xmbh'] . '"');
				$total_value['xmmc'] = iconv('utf-8','gbk','"' . $v['xmmc'] . '"');
				$total_value['tzrs'] = iconv('utf-8','gbk','"' . $v['tzrs'] . '"');
				$total_value['rgje'] = iconv('utf-8','gbk','"' . number_format($v['rgje'],2) . '"');
				$total_value['cglzje'] = iconv('utf-8','gbk','"' . $v['cglzje'] . '"');
				$total_value['xmzt'] = iconv('utf-8','gbk','"' . $v['xmzt'] . '"');
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
	//股权众筹-违约统计导出
	public function export_csv_breach_convention_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj, count(user_id) as wyrs, sum(money) as wyje
		from ".DB_PREFIX."investment_list  where investor_money_status =4  ";
		
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
									'wyrs'=>'""',
									'wyje'=>'""',
									
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,违约人数,违约金额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['wyrs'] = iconv('utf-8','gbk','"' . $v['wyrs'] . '"');
				$total_value['wyje'] = iconv('utf-8','gbk','"' . number_format($v['wyje'],2) . '"');
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
	 //违约统计明细导出
	public function export_csv_breach_convention_info($page = 1){
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
		FROM_UNIXTIME(a.create_time+28800 , '%Y-%m-%d') as sj ,
		u.user_name as hymc, 
		count(*) as wycs,
		sum(a.money) as wyje
		from ".DB_PREFIX."investment_list as a left join ".DB_PREFIX."user as u on u.id = a.user_id
		where a.investor_money_status =4  $condtion  ";

		if($begin_time > 0 || $end_time > 0){
			if($begin_time>0 && $end_time==0){
				$sql_str = "$sql_str and (a.create_time > $begin_time)";
			}elseif($begin_time==0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time < $end_time )";
			}elseif($begin_time >0 && $end_time>0){
				$sql_str = "$sql_str and (a.create_time between $begin_time and $end_time )";
			}
			
		}
		$sql_str .= "group by u.id ";
		$list = array();
		$list = $GLOBALS['db']->getAll($sql_str);
		if($list)
		{
			$total_value = array(
									'sj'=>'""',
									'hymc'=>'""',
									'wycs'=>'""',	
									'wyje'=>'""',							
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","时间,会员名称,违约次数,违约金额");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['hymc'] = iconv('utf-8','gbk','"' . $v['hymc'] . '"');				
				$total_value['wycs'] = iconv('utf-8','gbk','"' . number_format($v['wycs'],2) . '"');
				$total_value['wyje'] = iconv('utf-8','gbk','"' . number_format($v['wyje'],2) . '"');
				
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
	//用户统计导出
	public function export_csv_user_total($page = 1){
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map =  $this->com_search();
		foreach ( $map as $key => $val ) {
			//dump($key);
			if ((!is_array($val)) && ($val <> '')){
				$parameter .= "$key=" . urlencode ( $val ) . "&";
			}
		}
		
		$sql_str = "select FROM_UNIXTIME(create_time , '%Y-%m-%d') as sj,count(*) as zcrs, sum(if(is_investor=0,1,0)) as pthy, sum(if(is_investor=1,1,0)) as tzr, sum(if(is_investor=2,1,0)) as tzjgr
		from ".DB_PREFIX."user where is_effect = 1 ";
		
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
									'zcrs'=>'""',
									'pthy'=>'""',
									'tzr'=>'""',
									'tzjgr'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","注册人数,普通会员,投资人,投资机构人");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['sj'] . '"');
				$total_value['zcrs'] = iconv('utf-8','gbk','"' . $v['zcrs'] . '"');
				$total_value['pthy'] = iconv('utf-8','gbk','"' . $v['pthy'] . '"');
				$total_value['tzr'] = iconv('utf-8','gbk','"' . $v['tzr'] . '"');
				$total_value['tzjgr'] = iconv('utf-8','gbk','"' . $v['tzjgr'] . '"');
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
	 //用户明细导出
	public function export_csv_user_info($page = 1){
		$now=get_gmtime();
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$begin_time  = trim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
		$end_time  = trim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
		
		$time=trim($_REQUEST['time']);
		if(trim($_REQUEST['time'])){
			$condtion = " and  (FROM_UNIXTIME(create_time , '%Y-%m-%d') = '$time')";
		}
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$user_name= trim($_REQUEST['user_name']);
		}
		
		
		$sql_str = "select  
		FROM_UNIXTIME(create_time+28800, '%Y-%m-%d %H:%i:%S') as zcsj ,
		user_name as hymc, 
		email as hyyx, 
		mobile as sjh,
		money as hyye,
		case 
			when is_investor = 0 then '普通会员'
			when is_investor = 1 then'投资人' 
			when is_investor = 2 then'投资机构人'
		else ''
		end hylx	
		from ".DB_PREFIX."user 
		where is_effect = 1  $condtion  ";
		
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
									'zcsj'=>'""',
									'hymc'=>'""',
									'hyyx'=>'""',	
									'sjh'=>'""',
									'clsj'=>'""',
									'clsj'=>'""',
									);
			if($page == 1)
	    	$content_total = iconv("utf-8","gbk","注册时间,会员名称,会员邮箱,手机号,会员余额,会员类型 ");
	    	  
	    	if($page == 1) 	
	    	$content_total = $content_total . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$total_value = array();
				$total_value['sj'] = iconv('utf-8','gbk','"' . $v['zcsj'] . '"');
				$total_value['hymc'] = iconv('utf-8','gbk','"' . $v['hymc'] . '"');				
				$total_value['hyyx'] = iconv('utf-8','gbk','"' . $v['hyyx'] . '"');
				$total_value['sjh'] = iconv('utf-8','gbk','"' . $v['sjh'] . '"');
				$total_value['hyye'] = iconv('utf-8','gbk','"' . $v['hyye'] . '"');
				$total_value['hylx'] = iconv('utf-8','gbk','"' . $v['hylx'] . '"');
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