<?php
/**
 * 获取特推理财 
 * a. 理财表   s. 特推表
 */

function get_special_licais($condition,$order="s.sort DESC,s.id DESC",$limit="0,10"){
	if($condition!=""){
		$extWhere =" and ".$condition;
	}
	$sql_str = "SELECT a.*,s.name as special_name,s.img as special_img,s.brief as special_brief FROM ".DB_PREFIX."licai a " .
			"LEFT JOIN ".DB_PREFIX."licai_recommend s ON s.licai_id = a.id " .
			"WHERE 1=1 $extWhere ORDER BY  ".$order." LIMIT ".$limit;
			
	$list = $GLOBALS['db']->getAll($sql_str);
	if($list){
		foreach($list as $k=>$v){
			$list[$k] = licai_item_format($v);
		}
	}
	return $list;
}

/**
 * 获取理财列表
 * $condition 条件语句  无 and 打头
 * $order 排序
 * $limit 条数
 */
function get_licai_list($condition,$order="sort DESC,id DESC",$limit="0,10"){
	
	if($condition!=""){
		$extWhere = " AND ".$condition;
	}
	
	$sql_count = "SELECT COUNT(*) FROM ".DB_PREFIX."licai WHERE 1=1 ".$extWhere;
	
	$rs_count = $GLOBALS['db']->getOne($sql_count);
	$list = array();
	if($rs_count > 0){
		$sql_str = "SELECT * FROM ".DB_PREFIX."licai WHERE 1=1 ".$extWhere." ORDER BY ".$order." LIMIT ".$limit;
		$list = $GLOBALS['db']->getAll($sql_str);
		if($list){
			foreach($list as $k=>$v){
				$list[$k] = licai_item_format($v);
			}
		}
	}
	
	return array("rs_count"=>$rs_count,"list"=>$list);
	
}

/**
 * 获取单个理财信息
 */
function get_licai($id){
	if($id==0){
		return false;
	}
	$sql = "SELECT * FROM ".DB_PREFIX."licai WHERE id= ".$id;
	$result = $GLOBALS['db']->getRow($sql);
	if($result){
		$result = licai_item_format($result);
		if($result['type'] > 0 ){
			$result['licai_interest'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_interest WHERE licai_id=".$result['id']." ORDER BY max_money ASC  ");
		}
		else
		{
			$licai = $GLOBALS['db']->getRow("SELECT  id,platform_rate,site_buy_fee_rate,redemption_fee_rate FROM ".DB_PREFIX."licai WHERE id=".$result['id']);
			
			$licai_interest = get_licai_interest_yeb($licai["id"],$result["begin_interest_date"],$result["end_interest_date"]);			
			
			$result['licai_interest'] = array(
				"average_income_rate" => $licai_interest["avg_interest_rate"],
				"platform_rate" => $licai["platform_rate"],
				"site_buy_fee_rate" => $licai["site_buy_fee_rate"],
				"redemption_fee_rate" => $licai["redemption_fee_rate"],
				"days" => $licai_interest["days"],
				"interest_rate" => $licai_interest["interest_rate"],
			);
		}
	}
	return $result;
}

/**
 * 获取最近购买的
 * $type 0模拟数据 1真实数据
 */
function get_licai_dealshow($limit=10,$type=0){
	$d_table="licai_dealshow";
	if($type==1)
		$d_table="licai_order";
		
	$sql="SELECT d.*,l.id,l.name,l.img FROM ".DB_PREFIX.$d_table." d LEFT JOIN ".DB_PREFIX."licai l ON l.id = d.licai_id ORDER BY d.id DESC LIMIT ".$limit;
	
	$list = $GLOBALS['db']->getAll($sql);
	foreach($list as $k=>$v){
		$list[$k] = licai_item_format($v);
	}
	return $list;
}


/**
 * 格式化理财
 */
function licai_item_format($vo){
	
	//能购买的时间
	
	if($vo['type']>0){

	}else{
		$vo['end_buy_date'] = $vo['end_date'];
	}
	
  	if(to_timespan($vo['end_buy_date'])==0){
  		$vo['buy_limit_format'] = "无限期";
  		$vo['date_limit'] = "无限期";
		$vo['buy_limit'] = -1;
  	}
  	else{
  		$time_limit = to_timespan($vo['end_buy_date']) - to_timespan($vo['begin_buy_date']);
  		$vo['buy_limit_format'] = intval($time_limit/3600/24);
  		$vo['begin_date'] = get_peizi_next_date(NOW_TIME,$vo['begin_interest_type']);
  		if(to_timespan($vo['end_buy_date'])  > $vo['begin_date']){
  			$date_limit =to_timespan($vo['end_buy_date'])- to_timespan($vo['begin_date']);
			$vo['date_limit'] = intval($date_limit/3600/24);
		}
		if(to_timespan($vo['end_buy_date'])  > NOW_TIME)
			$vo['buy_limit'] = NOW_TIME - to_timespan($vo['begin_buy_date']);
		else
			$vo['buy_limit'] = $time_limit;
  	}
  	
  	if($vo['min_money']!=0){
  		$vo['min_money_format'] = format_price($vo['min_money']);
  		if($vo['min_money']>0){
  			$vo['min_money_format_num']=floatval($vo['min_money']/10000).'万元'; 
  		}
  	}
  	else{
  		$vo['min_money_format_num'] = "不限";
  	}
	
	if($vo['product_size']!=0){
		if($vo['product_size']>0&&$vo['product_size']<100000000){
			$vo['product_size_format_num']=floatval($vo['product_size']/10000).'万元'; 
		}elseif($vo['product_size']>=100000000){
			$vo['product_size_format_num']=floatval($vo['product_size']/100000000).'亿元'; 
		}
	}
	
	//安全等级
	switch($vo['risk_rank']){
		case 0:
			$vo['risk_rank_format'] = "低";
			break;
		case 1:
			$vo['risk_rank_format'] = "中";
			break;
		case 2:
			$vo['risk_rank_format'] = "高";
			break;
		default :
			$vo['risk_rank_format'] = "未知";
			break;
	}
	
	//理财产品类型
	switch($vo['type']){
		case 0:
			$vo['type_format'] = "余额宝";
			break;
		case 1:
			$vo['type_format'] = "固定定存";
			break;
		//case 2:
		//	$vo['type_format'] = "浮动定存";
		//	break;
		//case 3:
		//	$vo['type_format'] = "票据";
		//	break;
		//case 4:
		//	$vo['type_format'] = "基金";
		//	break;
		default :
			$vo['type_format'] = "未知";
			break;
	}
	
	//是否托管
	switch($vo['is_deposit']){
		case 0:
			$vo['deposit_format'] = "非托管";
			break;
		case 1:
			$vo['deposit_format'] = "托管";
			break;
		default :
			$vo['deposit_format'] = "未知";
			break;
	}
	
	//算息时间
	switch($vo['begin_interest_type']){
		case 0:
			$vo['begin_interest_type_format'] = "当日生效";
			break;
		case 1:
			$vo['begin_interest_type_format'] = "次日生效";
			break;
		case 2:
			$vo['begin_interest_type_format'] = "下个工作日生效";
			break;
		case 3:
			$vo['begin_interest_type_format'] = "下二个工作日";
			break;
		default :
			$vo['begin_interest_type_format'] = "未知";
			break;
	}
	
	$vo['url'] = url("licai#deal",array("id"=>$vo['id']));
	
	$vo["average_income_rate"]=number_format($vo["average_income_rate"],2);
	$vo['average_income_rate_format'] = number_format($vo["average_income_rate"],4) . "%";
	
	$vo["licai_status"] = get_licai_status($vo["id"]);
	
	//状态
	switch($vo['licai_status']){
		case 0:
			$vo['licai_status_format'] = "预热期";
			break;
		case 1:
			$vo['licai_status_format'] = "理财期";
			break;
		case 2:
			$vo['licai_status_format'] = "无限期";
			break;
	}
	
	switch($vo["review_type"])
	{
		case 0:
			$vo["review_type_format"] = "发起人审核" ;
			break;
		case 1:
			$vo["review_type_format"] = "网站和发起人审核" ;
			break;
		case 2:
			$vo["review_type_format"] = "自动审核" ;
			break;
	}
	
	if($vo['type'] >0 )
	{
		$vo['begin_interest_date'] = to_timespan($vo['begin_interest_date'])==0 ? to_date(NOW_TIME,"Y-m-d") : (to_timespan($vo['begin_interest_date'],"Y-m-d") > to_timespan(to_date(NOW_TIME,"Y-m-d"),"Y-m-d") ? $vo['begin_interest_date'] : to_date(NOW_TIME,"Y-m-d")  );
		
		
		//预热期
		$vo['before_interest_date'] = get_peizi_next_date(NOW_TIME,$vo['begin_interest_type']);
		
		if(to_timespan($vo['before_interest_date']) > 0 && to_timespan($vo['before_interest_date'],"Y-m-d") > to_timespan($vo['begin_interest_date'],"Y-m-d")){
			$vo['before_interest_date'] = $vo['begin_interest_date'];
		}
		//预热结束时间
		$vo['before_interest_enddate'] = $vo['begin_interest_date'];
		
		$vo['before_day'] = 0;
		$before_time_limit = to_timespan($vo['before_interest_enddate'],"Y-m-d") - to_timespan($vo['before_interest_date'],"Y-m-d");
		if($before_time_limit > 0){
			$vo['before_day'] = $before_time_limit / 24 / 3600;
		}
	
		//起息时间 
		$begin_interest_date = get_peizi_next_date(NOW_TIME,$vo['begin_interest_type']);

		if(to_timespan($begin_interest_date,"Y-m-d") > to_timespan($vo['begin_interest_date'],"Y-m-d")){
			$vo['begin_interest_date'] = $begin_interest_date;
		}		
		
		if($vo['time_limit'] == 0)
		{
			$vo['end_interest_date'] = $vo['end_date'];
		}
		//无项目结束时间 有周期
		elseif(to_timespan($vo['end_date']) == 0 && $vo['time_limit'] > 0){
			$vo['end_interest_date'] = to_date(next_month(NOW_TIME,$vo['time_limit']),"Y-m-d");
		}
		//有项目结束时间 有周期
		elseif(to_timespan($vo['end_date']) > 0 && $vo['time_limit'] > 0){
			//如果项目结束时间大于周期那么使用周期时间
			$str_time = to_timespan($vo['begin_interest_date']);
			if(to_timespan($vo['end_date'],"Y-m-d") > to_timespan(to_date(next_month($str_time,$vo['time_limit']),"Y-m-d"))){
				$vo['end_interest_date'] = to_date(next_month($str_time,$vo['time_limit']),"Y-m-d");
			}
			else{
				$vo['end_interest_date'] = $vo['end_date'];
			}
		}
	
		$vo['end_interest_date_format'] = to_date($vo['end_date']);
		
		$vo['buy_day'] = 0;
		
		$buy_time_limit = to_timespan($vo['end_interest_date'],"Y-m-d") - to_timespan($vo['begin_interest_date'],"Y-m-d");
		if($buy_time_limit >= 0){
			$vo['buy_day'] = $buy_time_limit / 24 / 3600 ;
		}
		if($vo["end_date"] == "0000-00-00" ||$vo["end_date"] == ""  )
		{
			$vo["end_date"] = "无期限";
		}
	}
	//余额宝
	else
	{
		$vo['before_day'] = 0;
		$vo['end_buy_date'] = $vo['end_date'];
		$vo['buy_day'] = 0;
		
		$vo['begin_interest_date'] = to_timespan($vo['begin_interest_date'])==0 ? to_date(NOW_TIME,"Y-m-d") : (to_timespan($vo['begin_interest_date'],"Y-m-d") > to_timespan(to_date(NOW_TIME,"Y-m-d"),"Y-m-d") ? $vo['begin_interest_date'] : to_date(NOW_TIME,"Y-m-d")  );
		
		$vo['begin_interest_date'] = get_peizi_next_date(to_timespan($vo['begin_interest_date']),$vo['begin_interest_type']);
		$vo['end_interest_date'] = $vo['end_date'];
		
		$buy_time_limit = to_timespan($vo['end_date'],"Y-m-d") - to_timespan($vo['begin_buy_date'],"Y-m-d");
		
		if($buy_time_limit >= 0){
			$vo['buy_day'] = $buy_time_limit / 24 / 3600;
		}
		
		if($vo["end_date"] == "0000-00-00" ||$vo["end_date"] == ""  )
		{
			$vo["end_date"] = "已结束";
		}
		
		//$vo['time_limit'] = intval($buy_time_limit/3600/24/30);
		
		$vo['platform_rate_format'] = number_format($vo['platform_rate'],4)."%";
		$vo['site_buy_fee_rate_format']= number_format($vo['site_buy_fee_rate'],4)."%"; 
		$vo['redemption_fee_rate_format']= number_format($vo['redemption_fee_rate'],4)."%"; 

	}

	$vo["service_fee_rate_format"] = number_format($vo["service_fee_rate"],4). "%";
	$vo["subscribing_amount_format"] = format_price($vo["subscribing_amount"]);
 	$vo["subscribing_amount_format_num"] = number_format($vo["subscribing_amount"]/10000);
	
	$vo['begin_interest_date_time'] = to_timespan($vo['begin_interest_date'],"Y-m-d");
	$vo['end_buy_date_time'] = to_timespan($vo['end_buy_date'],"Y-m-d");
	$vo['begin_buy_date_time'] = to_timespan($vo['begin_buy_date'],"Y-m-d");
	$vo['end_date_time'] = to_timespan($vo['end_date'],"Y-m-d");
	
	
	return $vo;
}

/**
 * 理财产品参数更新
 * id int 理财ID
 */
function syn_licai_status($id,$type = 1){
	if($id==0){
		return false;
	}
	
	$data=array();
	if($type > 0 )
	{
		$data['average_income_rate'] = $GLOBALS['db']->getOne("SELECT avg(interest_rate) FROM ".DB_PREFIX."licai_interest WHERE licai_id=".$id);
	}
	else
	{
		$data['average_income_rate'] = $GLOBALS['db']->getOne("SELECT avg(rate) FROM ".DB_PREFIX."licai_history WHERE licai_id=".$id);
	}
	$sum_rs = $GLOBALS['db']->getRow("SELECT count(*) as total_people,sum(money) as subscribing_amount FROM ".DB_PREFIX."licai_order where licai_id=".$id);
	$data['total_people'] = $sum_rs['total_people'];
	$data['subscribing_amount'] = $sum_rs['subscribing_amount'];
	
	$GLOBALS['db']->autoExecute(DB_PREFIX."licai",$data,"UPDATE","id=".$id);
}


function licai_bid($id,$money,$paypassword){
	
	$return = array("status"=>0,"info"=>"");
	
	if(intval($GLOBALS['user_info']['id']) == 0){
		$return['info'] = "请先登录";
		return $return;
	}
	
	if(intval($GLOBALS['user_info']['money']) < $money){
		$return['info'] = "账户余额不足";
		return $return;
	}
	
	if($GLOBALS['user_info']['paypassword'] != md5($paypassword)){
		$return['info'] = "支付密码错误";
		return $return;
	}
	
	$licai = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai where id=".$id);
	$licai['url'] = url("licai#deal",array("id"=>$licai['id']));
	
	if($licai['user_id'] == $GLOBALS['user_info']['id']){
		$return['info'] = "不能购买自己发布的理财产品";
		return $return;
	}
	if(!$licai || $licai['status'] == 0){
		$return['info'] = "理财产品不存在";
		return $return;
	}
	
	if(to_timespan($licai['begin_buy_date'],"Y-m-d") > 0 && to_timespan($licai['begin_buy_date'],"Y-m-d") > NOW_TIME){
		$return['info'] = "理财产品还未开始允许购买";
		return $return;
	}
	
	if(to_timespan($licai['end_buy_date'],"Y-m-d") > 0 && to_timespan($licai['end_buy_date'],"Y-m-d") < NOW_TIME){
		$return['info'] = "理财产品已结束购买";
		return $return;
	}
	if(to_timespan($licai['end_date'],"Y-m-d") > 0 && to_timespan($licai['end_date'],"Y-m-d") < NOW_TIME){
		$return['info'] = "理财产品已结束";
		return $return;
	}
		
	if(floatval($licai['min_money'])!=0){
		if($money < floatval($licai['min_money'])){
			$return['info'] = "最小购买金额为：".format_price($licai['min_money']);
			return $return;
		}
	}
	
	if(floatval($licai['max_money'])!=0){
		if($money > floatval($licai['max_money'])){
			$return['info'] = "最大购买金额为：".format_price($licai['max_money']);
			return $return;
		}
	}
	
	$licai_order_data = array();
	$licai_order_data['status'] = 1;
	$licai_order_data['licai_id'] = $id;
	$licai_order_data['user_id'] = intval($GLOBALS['user_info']['id']);
	$licai_order_data['user_name'] = $GLOBALS['user_info']['user_name'];
	$licai_order_data['money'] = $money;
	$licai_order_data['create_time'] = to_date(NOW_TIME);
	$licai_order_data['create_date'] = to_date(NOW_TIME);
	//算息时间
	$licai_order_data['begin_interest_type'] = $licai['begin_interest_type'];
	$licai['begin_interest_date'] = (to_timespan($licai['begin_interest_date'])==0 || to_timespan($licai['begin_interest_date']) < NOW_TIME) ? to_date(NOW_TIME,"Y-m-d") : $licai['begin_interest_date'] ;
	
	//预热期
	
	if($licai['type'] > 0){
		//预热开始时间 | 购买开始时间
		$licai_order_data['before_interest_date'] = get_peizi_next_date(NOW_TIME,$licai['begin_interest_type']);
		
		if(to_timespan($licai_order_data['before_interest_date'],"Y-m-d") > 0 && to_timespan($licai['before_interest_date'],"Y-m-d") > to_timespan($licai['begin_interest_date'],"Y-m-d")){
			$licai_order_data['before_interest_date'] = $licai['begin_interest_date'];
		}
		//预热结束时间
		$licai_order_data['before_interest_enddate'] = $licai['begin_interest_date'];
	}
	
		
	//起息时间 
	$licai_order_data['begin_interest_date'] = get_peizi_next_date(NOW_TIME,$licai['begin_interest_type']);
	
	if(to_timespan($licai_order_data['begin_interest_date'],"Y-m-d") < to_timespan($licai['begin_interest_date'],"Y-m-d")){
		$licai_order_data['begin_interest_date'] = $licai['begin_interest_date'];
	}
	//结束时间 
	if($licai['time_limit'] == 0){
		$licai_order_data['end_interest_date'] = $licai['end_date'];
	}
	//无项目结束时间 有周期
	elseif(to_timespan($licai['end_date'],"Y-m-d") == 0 && $licai['time_limit'] > 0){
		$licai_order_data['end_interest_date'] = to_date(next_month(NOW_TIME,$licai['time_limit']),"Y-m-d");
	}
	//有项目结束时间 有周期
	elseif(to_timespan($licai['end_date'],"Y-m-d") > 0 && $licai['time_limit'] > 0){
		//如果项目结束时间大于周期那么使用周期时间
		//$str_time = to_timespan($licai['begin_interest_date'],"Y-m-d");
		$str_time = to_timespan($licai_order_data['begin_interest_date'],"Y-m-d");
		if(to_timespan($licai['end_date']) > to_timespan(to_date(next_month($str_time,$licai['time_limit']),"Y-m-d"),"Y-m-d")){
			$licai_order_data['end_interest_date'] = to_date(next_month($str_time,$licai['time_limit']),"Y-m-d");
		}
		else{
			$licai_order_data['end_interest_date'] = $licai['end_date'];
		}
	}
	
	
	if($licai['type'] > 0){
		
		$interest_rs = get_licai_interest($id,$money);
		
		//预热利息
		$licai_order_data['before_rate'] = $interest_rs['before_rate'];
		//预热违约利息
		$licai_order_data['before_breach_rate'] = $interest_rs['before_breach_rate'];
		
		//正常利息
		$licai_order_data['interest_rate'] = $interest_rs['interest_rate'];
		//违约利息
		$licai_order_data['breach_rate'] = $interest_rs['breach_rate'];
		
		//申购手续费
		$licai_order_data['site_buy_fee_rate'] = $interest_rs['site_buy_fee_rate'];
		//赎回手续费
		$licai_order_data['redemption_fee_rate'] = $interest_rs['redemption_fee_rate'];
		
		//平台收益
		$licai_order_data['platform_rate'] = $interest_rs['platform_rate'];
		
		//用户违约平台收益
		$licai_order_data['platform_breach_rate'] = $interest_rs['platform_breach_rate'];
		
		//冻结保证金比例
		$licai_order_data['freeze_bond_rate'] = $interest_rs['freeze_bond_rate'];
	}
	else
	{
		$licai_order_data['before_rate'] = 0;
		$licai_order_data['before_breach_rate'] = 0;
		$licai_order_data['interest_rate'] = 0;
		$licai_order_data['breach_rate'] = 0;
		$licai_order_data['platform_breach_rate'] = 0;
		$licai_order_data['freeze_bond_rate'] = 0;		
		
		$licai_order_data['platform_rate'] = $licai['platform_rate'];
		$licai_order_data['site_buy_fee_rate'] = $licai['site_buy_fee_rate'];
		$licai_order_data['redemption_fee_rate'] = $licai['redemption_fee_rate'];
	}
	
	//预热期利息
	$licai_order_data['before_interest'] = $money * floatval($licai_order_data['before_rate'])  * 0.01 * ((to_timespan($licai_order_data['before_interest_enddate']) - to_timespan($licai_order_data['before_interest_date']))/24/3600) / 365; //余额宝时为0
	
	$licai_order_data['site_buy_fee'] = $money * floatval($licai_order_data['site_buy_fee_rate']) * 0.01;
	
	//$licai_order_data['freeze_bond_rate'] = $licai['freeze_bond_rate'];
	
	//冻结的保证金，扣除网站收取的手续费
	$licai_order_data['freeze_bond'] = round($licai_order_data['freeze_bond_rate'] * ($money-$licai_order_data['site_buy_fee']) * 0.01,2); //余额宝时为0
	$licai_order_data['pay_money'] = $money - $licai_order_data['site_buy_fee'] - $licai_order_data['freeze_bond'] ; 
	
	//成交服务费
	$licai_order_data['service_fee_rate'] = $licai['service_fee_rate'];
	$licai_order_data['service_fee'] = floatval($licai['service_fee_rate']) * 0.01 * ($money - $licai_order_data['site_buy_fee']);
	//print_r($licai_order_data);die();
	$GLOBALS['db']->autoExecute(DB_PREFIX."licai_order",$licai_order_data,"INSERT");
	
	if($GLOBALS['db']->affected_rows()){
		require_once(APP_ROOT_PATH.'system/libs/user.php');
		//理财购买本金
		modify_account(array("money"=>'-'.($money-$licai_order_data['site_buy_fee'])),$GLOBALS['user_info']['id'],"购买理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”,购买本金",array('type'=>42));
		//理财购买手续费
		modify_account(array("money"=>'-'.$licai_order_data['site_buy_fee']),$GLOBALS['user_info']['id'],"购买理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”,购买手续费",array('type'=>43));
		
		
		//理财发放资金
		modify_account(array("money"=>$licai_order_data['pay_money']),intval($licai['user_id']),$GLOBALS['user_info']['user_name']."对理财“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的投资,理财购买资金",array('type'=>46));
		
		if($licai_order_data['freeze_bond'] > 0)	
		{	
			//理财冻结资金
			modify_account(array("mortgage_money"=>$licai_order_data['freeze_bond']),intval($licai['user_id']),$GLOBALS['user_info']['user_name']."对理财“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的投资,理财冻结资金",array('type'=>44));
		}
		
		if($licai_order_data['service_fee']!=0)
		{
			//理财服务费
			modify_account(array("money"=>'-'.$licai_order_data['service_fee']),intval($licai['user_id']),$GLOBALS['user_info']['user_name']."对理财“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的投资,理财服务费",array('type'=>45));
		}
			
		if($licai['type'] >0 )
		{
			syn_licai_status($id);
		}
		else
		{
			syn_licai_status($id,0);
		}
		$return['status'] = 1; 
		$return['info'] = "购买成功";
		return $return;
	}
	
}
///获取余额宝总收益费率
///$id 理财编号
///$begin_date 起息时间 Y-m-d
///$end_date  结束时间 Y-m-d
function get_licai_interest_yeb($id,$begin_date,$end_date)
{
	if($id==0)
		return null;
		
	$licai = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where id =".$id." and type = 0 ");
	if(!$licai)
	{
		return null;
	}
	$interest = $GLOBALS["db"]->getRow("select sum(rate) as sum_rate,count(*) as sum_count from ".DB_PREFIX."licai_history where licai_id =".$id." and history_date >= '".$begin_date."' and history_date <'".$end_date."'");
	$days=(to_timespan($end_date)-to_timespan($begin_date))/3600/24;

	$avg = number_format($interest["sum_rate"]/$interest["sum_count"],4);

	return array(
		"days" => $days,
		"interest_rate"=>$interest["sum_rate"],
		"avg_interest_rate" => $avg,
		"site_buy_fee_rate"=>$licai["site_buy_fee_rate"], //购买手续费
		"redemption_fee_rate"=>$licai["redemption_fee_rate"], //赎回手续费
		"platform_rate"=>$licai["platform_rate"], //平台收益
	);
}
/**
 * 获取理财收益费率
 * $id 理财ID $money金额 投资金额 或赎回金额
 */
function get_licai_interest($id,$money){
	if($id==0)
		return null;
		
	$licai['licai_interest'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_interest WHERE licai_id=".$id." ORDER BY max_money ASC ");
	
	$before_rate = 0; //预热期利率
	$before_breach_rate = 0; //预热期违约利率
	$interest_rate = 0; //利息率
	$breach_rate = 0; //正常利息 违约收益率
	$site_buy_fee_rate=0; //网站购买手续费
	$redemption_fee_rate = 0; //赎回手续费
	$platform_rate = 0;  //平台收益率
	$platform_breach_rate = 0; // 用户违约网站收益
	$freeze_bond_rate = 0;
	$count_str = count($licai['licai_interest']) - 1;
	
	if($count_str >= 0 && $licai['licai_interest'][$count_str]['max_money'] < $money ){
		$before_rate = $licai['licai_interest'][$count_str]['before_rate'];
		$before_breach_rate = $licai['licai_interest'][$count_str]['before_breach_rate'];
		$interest_rate = $licai['licai_interest'][$count_str]['interest_rate'];
		$breach_rate = $licai['licai_interest'][$count_str]['breach_rate'];
		$site_buy_fee_rate = $licai['licai_interest'][$count_str]['site_buy_fee_rate'];
		$redemption_fee_rate = $licai['licai_interest'][$count_str]['redemption_fee_rate'];
		$platform_rate = $licai['licai_interest'][$count_str]['platform_rate'];
		$platform_breach_rate = $licai['licai_interest'][$count_str]['platform_breach_rate'];
		$freeze_bond_rate = $licai['licai_interest'][$count_str]['freeze_bond_rate'];
	}
	elseif($count_str >= 0){
		foreach($licai['licai_interest'] as $k=>$v){
			if($v['min_money'] <$money && $v['max_money']>=$money){
				$before_rate = $v['before_rate'];
				$before_breach_rate = $v['before_breach_rate'];
				$interest_rate = $v['interest_rate'];
				$breach_rate = $v['breach_rate'];
				$site_buy_fee_rate = $v['site_buy_fee_rate'];
				$redemption_fee_rate = $v['redemption_fee_rate'];
				$platform_rate = $v['platform_rate'];
				$platform_breach_rate = $v['platform_breach_rate'];
				$freeze_bond_rate = $v['freeze_bond_rate'];
			}
		}
	}
	
	return array(
		"interest"=>$licai['licai_interest'],
		"before_rate"=>$before_rate,
		"before_breach_rate"=>$before_breach_rate,
		"interest_rate"=>$interest_rate,
		"breach_rate"=>$breach_rate,
		"site_buy_fee_rate"=>$site_buy_fee_rate,
		"redemption_fee_rate"=>$redemption_fee_rate,
		"platform_rate"=>$platform_rate,
		"platform_breach_rate"=>$platform_breach_rate,
		"freeze_bond_rate"=>$freeze_bond_rate,
		);
	
}


/**
  * 下一交易日
  */
function get_peizi_next_date($time= NOW_TIME ,$left = 0){
	$date = to_date($time,"Y-m-d");
	
	switch($left){
		case 0://当天
			return $date;
			break;
		case 1://次日生效
			$cur_date = dec_date($date, -1);	
			return $cur_date;
		break;
		case 2://下一交易日
			for($i = 1; $i < 30; $i ++){
				$cur_date = dec_date($date, -$i);
				if (get_peizi_is_holiday($cur_date) == false){
					return $cur_date;
				} 		
			}
		break;
		case 3://下二个交易日
			for($i = 1; $i < 30; $i ++){
				$i ++;
				$cur_date = dec_date($date, -$i);
				
				if (get_peizi_is_holiday($cur_date) == false){
					
					return $cur_date;
				} 	
			}
		break;
		default :
			return null;
		break;
		
	}
}
 
 /**
  * 判断是否交易日
  * @param unknown_type $date
  */
function get_peizi_is_holiday($date){
 	//判断是否是：周末 	
 	$w = to_date(to_timespan($date,"Y-m-d"),'w');
 	
 	//echo $w;exit;
 	if ($w == 0 || $w == 6){
 		return true;
 	} 
 	
 	//判断是否为节假日
 	$sql = "select id from ".DB_PREFIX."licai_holiday where holiday = '".$date."'";
 	
 	if (intval($GLOBALS['db']->getOne($sql)) > 0){
 		return true;
 	}else{
 		return false;
 	} 
}
 


function next_month($time,$m=1){
	$str_t = to_timespan(to_date($time)." ".$m." month ");
	//$str_t = to_timespan(to_date($time)." ".$m." month -1 day");
	return $str_t;
}

/*
 * $redempte_id 申请ID
 * $status 0表示未审核 1表示已审核 2表示审核不通过  3表示取消赎回
 * $earn_money  审核收益
 * $fee  赎回手续费
 * $organiser_fee  平台收益
 * $pay_type 0 表示不允许垫付 1表示允许垫付
 * $web_type 0表示前台撤销   1表示后台审核赎回 2表示前台审核赎回  
 */
function deal_redempte($redempte_id,$status,$earn_money,$fee=0,$organiser_fee=0,$pay_type=0,$web_type=0){
	require_once(APP_ROOT_PATH.'system/libs/user.php');
	$info=array('status'=>1,'info'=>'');
	$licai=array();
	if($web_type==1||$web_type==2){
		$redempte=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."licai_redempte where id=$redempte_id");

 		if($redempte['status']==0){
			if($status==1){
				//审核通过
 				
				if(true){
					//最终的金额
 					$money=$redempte['money']+$organiser_fee+$earn_money;
					
					$order=$GLOBALS['db']->getRow("select dorder.money,dorder.site_buy_fee,dorder.redempte_money,dorder.user_id as consumer_id,d.user_id as organiser_id,d.name as licai_name,d.id as licai_id,d.service_fee_rate from ".DB_PREFIX."licai_order as dorder left join ".DB_PREFIX."licai as d on dorder.licai_id=d.id  where dorder.id=".$redempte['order_id']);

					if($redempte['money']>($order['money']-$order['redempte_money'])){
				 	 	$info['status']=0;
						$info['info']='您赎回的金额超过了您购买的金额(您的购买金额是'.$order['money'].',已赎回'.$order['redempte_money'].")";
						return $info;
					}
					/*if($order['service_fee_rate']>0){
						$organiser_fee=$redempte['money']*$order['service_fee_rate'];
					}else{
						$organiser_fee=0;
					}*/
 					//$money=$redempte['money']+$organiser_fee+$earn_money;
					$licai['url'] = url("licai#deal",array("id"=>$order['licai_id']));
					$licai['name']=$order['licai_name'];
					
					 //对发起人进行扣款操作,有冻结金额mortgage_money 先用冻结金额，没有再用余额money,如果余额也不够，就生成垫付单
					if($order['consumer_id']&&$order['organiser_id']){
						$organiser_user=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$order['organiser_id']);
						require_once APP_ROOT_PATH.'system/libs/user.php';
						
						//$organiser_user = get_user_info("*",$order['organiser_id']);
									
					 	if($organiser_user['money']>=$money){
					 		//modify_account(array('money'=>'-'.$money,'ben_money'=>'-'.$redempte['money'],'earn_money'=>'-'.$earn_money,'organiser_fee'=>'-'.$organiser_fee),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”",39);
							
							//赎回本金
							modify_account(array('money'=>'-'.$redempte['money']),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>42));//42理财购买本金 43理财购买手续费 44理财冻结资金 45理财服务费 46理财发放资金
							
							//收益
							modify_account(array('money'=>'-'.$earn_money),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>46));
							//平台手续费
							modify_account(array('money'=>'-'.$organiser_fee),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的平台收益",array('type'=>43));
							
							
					 		//if(!$re){
					 		/*	$info['status']=0;
								$info['info']='资金修改错误';
								return $info;
							*/
					 		//}
					 	}elseif($organiser_user['mortgage_money']>=$money){
							//赎回本金
							modify_account(array('mortgage_money'=>'-'.$redempte['money']),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>44));
							//赎回手续费
							modify_account(array('mortgage_money'=>'-'.$earn_money),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>44));
							//赎回平台手续费
							modify_account(array('mortgage_money'=>'-'.$organiser_fee),$order['organiser_id'],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的平台收益",array('type'=>44));
							
					 		//if(!$re){
					 		/*	$info['status']=0;
								$info['info']='资金修改错误';
								return $info;*/
					 		//} 
					 	}/*elseif(($organiser_user['money']+$organiser_user['mortgage_money'])>=$money){
					 		$ye_money=$money-$organiser_user['mortgage_money'];
							
							//赎回本金
					 		modify_account(array('money'=>'-'.$redempte['money']),$order["organiser_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>42));
							//赎回收益
							modify_account(array('money'=>'-'.$earn_money),$order["organiser_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>46));
							//平台手续费
							modify_account(array('money'=>'-'.$organiser_fee),$order["organiser_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的手续费",array('type'=>43));
							
					 	}*/else{
					 		if($pay_type==1){
						 		//生成垫付单
						 		$re=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."licai_advance where  redempte_id=".$redempte_id);
						 		if($re){
						 			$info['status']=0;
									$info['info']='发起人已经生成垫付单';
									return $info;
						 		}else{
						 			$advance['redempte_id']=$redempte_id;
						 			$advance['user_id']=$organiser_user['id'];
						 			$advance['user_name']=$organiser_user['user_name'];
						 			$advance['money']=$redempte['money'];
						 			$advance['earn_money']=$earn_money;
						 			$advance['fee']=$fee;
						 			$advance['organiser_fee']=$organiser_fee;
						 			
						 			$advance['real_money']=$organiser_user['money']+$organiser_user['mortgage_money'];
						 			
						 			$advance['advance_money']=$redempte['money']+$advance['earn_money']+$advance['organiser_fee']-$advance['real_money'];
						 			
						 			
						 			$advance['status']=1;
						 			$advance['type']=$redempte['type'];
						 			$advance['create_date']=to_date(NOW_TIME);
						 			$advance['update_date']=to_date(NOW_TIME);
						 			
						 			$re=$GLOBALS['db']->autoExecute(DB_PREFIX."licai_advance",$advance);
						 			if(!$re){
						 				$info['status']=0;
										$info['info']='发起人生成垫付单错误';
										return $info;
						 			}else{
						 				$results = array('redempte_money'=>0,'earn_money'=>0,'organiser_fee'=>0);
						 				if($organiser_user['mortgage_money']){
						 					//赎回本金
						 					if($results['redempte_money'] == 0&&$organiser_user['mortgage_money']>$redempte['money']){
						 						if(modify_account(array('mortgage_money'=>'-'.$redempte['money']),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>44))){
						 							$results['redempte_money'] = 1;
						 						}
						 					}
											
											//赎回收益
											if($results['earn_money'] == 0&&$organiser_user['mortgage_money']>$earn_money){
												if(modify_account(array('mortgage_money'=>'-'.$earn_money),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>44))){
						 							$results['earn_money'] = 1;
						 						}
											}
											//平台收益
											if($results['organiser_fee'] == 0&&$organiser_user['mortgage_money']>$organiser_fee){
												if(modify_account(array('mortgage_money'=>'-'.$organiser_fee),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的平台收益",array('type'=>44))){
						 							$results['organiser_fee'] = 1;
						 						}
											}
											//生成垫付单前扣除冻结金
											$yue1 = $GLOBALS['db']->getOne("select mortgage_money from  ".DB_PREFIX."user where  id=".$GLOBALS["user_info"]["id"]);
											modify_account(array('mortgage_money'=>'-'.$yue1),$advance["user_id"],"生成垫付单扣除冻结金",array('type'=>44));
											
						 				}
						 				
						 				
						 				if($organiser_user['money']){
						 					//赎回本金
						 					if($results['redempte_money'] == 0&&$organiser_user['money']>$redempte['money']){
						 						if(modify_account(array('money'=>'-'.$redempte['money']),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>42))){
						 							$results['redempte_money'] = 1;
						 						}
						 					}
											//赎回收益
											if($results['earn_money'] == 0&&$organiser_user['money']>$earn_money){
												if(modify_account(array('money'=>'-'.$earn_money),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>46))){
													$results['earn_money'] = 1;
												}
											}
											//平台收益
											if($results['organiser_fee'] == 0&&$organiser_user['money']>$organiser_fee){
							 					if(modify_account(array('money'=>'-'.$organiser_fee),$advance["user_id"],"发放用户要赎回的理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的平台收益",array('type'=>43))){
													$results['organiser_fee'] = 1;
							 					}
											}
											//生成垫付单前扣除余额
											$yue2 = $GLOBALS['db']->getOne("select money from  ".DB_PREFIX."user where  id=".$GLOBALS["user_info"]["id"]);
											modify_account(array('money'=>'-'.$yue2),$advance["user_id"],"生成垫付单扣除余额",array('type'=>47));
						 				}
						 				
						 			}
						 		}
					 		}else{
					 			$info['status']=2;
					 			if($web_type==1){
					 				$info['info']='用户账户资金不足，是否要帮用户垫付';
					 			}elseif($web_type==2){
					 				$licai_pay_type = $GLOBALS["db"]->getRow("select lc.pay_type from ".DB_PREFIX."licai as lc" .
					 						" left join ".DB_PREFIX."licai_order as lco on lc.id = lco.licai_id " .
					 						" left join ".DB_PREFIX."licai_redempte as lcr on lco.id = lcr.order_id " .
					 						" where lcr.id=".$redempte_id." and lc.user_id = ".$GLOBALS["user_info"]["id"]);
					 				if($licai_pay_type['pay_type'] ==1){
					 					$info['status']=2;
										$info['info']='账户资金不足，是否需要系统垫付';
										return $info;
					 				}else{
					 					$info['status']=0;
					 					$info['lack'] = 1;
					 					$info['info']='您的账户资金不足，请充值';
					 				}
					 			}
								
								return $info;
					 		}
					 	}
					 	//修改赎回状态
					 	 $re=$GLOBALS['db']->query("update  ".DB_PREFIX."licai_redempte set status=$status,earn_money=".$earn_money.",fee=".$fee.",update_date = '".to_date(NOW_TIME,"Y-m-d H:i:s")."' where id=$redempte_id  ");
					 	 //修改订单状态
					 	 if($redempte['money']<($order['money']-$order["site_buy_fee"]-$order['redempte_money'])){
					 	 	//部分赎回
					 	 	$set=" ,status=2 ";
					 	 }else{
					 	 	//全部赎回
					 	 	$set=" ,status=3 ";
							$over = 1;
					 	 }
					 	 $re=$GLOBALS['db']->query("update  ".DB_PREFIX."licai_order set redempte_money=redempte_money+".$redempte['money'].$set." ,status_time = '".to_date(NOW_TIME,"Y-m-d H:i:s")."' where id=".$redempte['order_id']);
					 	 //为投资人 进行金额的增加
					 	 $comuse_money=$redempte['money']+$earn_money-$fee;
						 //赎回本金
						 modify_account(array('money'=>$redempte['money']),$order['consumer_id'],"赎回理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的本金",array('type'=>42));
						 //赎回收益
						 modify_account(array('money'=>$earn_money),$order['consumer_id'],"赎回理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的收益",array('type'=>46));
						 //赎回手续费
						 modify_account(array('money'=>'-'.$fee),$order['consumer_id'],"赎回理财产品“<a href=\"".$licai['url']."\">".$licai['name']."</a>”的手续费",array('type'=>43));
						
						 if($over == 1)
						 {
							 $GLOBALS["db"]->query("update  ".DB_PREFIX."licai_redempte set status=2,update_date = '".to_date(NOW_TIME,"Y-m-d H:i:s")."' where order_id = ".$redempte['order_id']." and status = 0 and user_id =".$order["consumer_id"]);
						 }
						 
				 		/* if(!$re){
				 			$info['status']=0;
							$info['info']='用户收款错误';
							return $info;
				 		 }*/
					}else{
						$info['status']=0;
						$info['info']='操作失败,请重新提交';
						return $info;
					}
 					
					 
				}else{
					$info['status']=0;
					$info['info']='操作失败,请重新撤销';
					return $info;
				}
			}elseif($status==2){
				//审核不通过
				$re=$GLOBALS['db']->query("update  ".DB_PREFIX."licai_redempte set status=$status where id=$redempte_id  ");
				if($re){
					$info['info']='操作成功';
					return $info;
				}else{
					$info['status']=0;
					$info['info']='操作失败,请重新撤销';
					return $info;
				}
			}
		}
		
	}elseif($web_type==0){
		$user_id=$GLOBALS['user_info']['id'];
		if(!$user_id){
			$info['status']=0;
			$info['info']='请登录';
			return $info;
		}
 		if($status==3){
			
			$redempte=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."licai_redempte where id=$redempte_id");
			if($redempte['user_id']==$user_id){
				$re=$GLOBALS['db']->query("update  ".DB_PREFIX."licai_redempte set status=$status where id=$redempte_id and user_id=$user_id ");
				if($re){
					$info['info']='操作成功';
					return $info;
				}else{
					$info['status']=0;
					$info['info']='操作失败,请重新撤销';
					return $info;
				}
			}else{
				$info['status']=0;
				$info['info']='您没有权限撤销';
				return $info;
			}
		}
	}
	return $info;
}
/*
 * 生成赎回订单
 * $user_id 申请人ID
 * $order_id 订单ID
 * $money 申请赎回金额
 */

function create_redempte($user_id,$order_id,$money){
	$info=array('status'=>1,'info'=>'');
	if($money<=0){
		$info['status']=0;
		$info['info']='请输入有效金额';
		return $info;
	}
	if(!$GLOBALS['user_info']){
		$info['status']=0;
		$info['info']='请先登录';
		return $info;
	}
	if($GLOBALS['user_info']['id']!=$user_id){
		$info['status']=0;
		$info['info']='您没有权限';
		return $info;
	}
	$order=$GLOBALS['db']->getRow("select dorder.before_interest_date,dorder.before_interest_enddate,dorder.begin_interest_date as begin_order,dorder.end_interest_date, dorder.user_id as consumer_id,d.user_id as organiser_id,d.name as licai_name,d.id as licai_id,d.service_fee_rate,d.begin_buy_date,d.begin_interest_date,d.end_buy_date,d.end_date,d.time_limit,d.type from ".DB_PREFIX."licai_order as dorder left join ".DB_PREFIX."licai as d on dorder.licai_id=d.id  where dorder.id=".$order_id." and dorder.user_id=".$user_id);

	if($order){
		if($order["type"]>0)
		{
			$licai_interest=get_licai_interest($order['licai_id'],$money);
		}
		else
		{
			if( to_timespan($order["end_interest_date"]) > NOW_TIME)
			{
				$licai_interest= get_licai_interest_yeb($order['licai_id'],$order['begin_order'],to_date(NOW_TIME,'Y-m-d'));
			}
			else
			{
				$licai_interest= get_licai_interest_yeb($order['licai_id'],$order['begin_order'],$order['end_interest_date']);
			}
			
		}
		
		$redempte['order_id']=$order_id;
		$redempte['user_id']=$user_id;
		$redempte['user_name']=$GLOBALS['user_info']['user_name'];
		$redempte['money']=$money;
//		$redempte['earn_money']=$earn_money;
//		$redempte['fee']=$fee;
//		$redempte['organiser_fee']=$organiser_fee;
		$now=get_gmtime();
		
		$limit_time=$order['time_limit']*30*24*3600;
		$order['before_interest_date']=to_timespan($order['before_interest_date']);
		$order['before_interest_enddate']=to_timespan($order['before_interest_enddate']);
		
		 
		$order['begin_interest_date']=$order['begin_order'];
		$order['begin_interest_date']=to_timespan($order['begin_interest_date']);
		$order['end_interest_date']=to_timespan($order['end_interest_date']);
		 
		if($order["type"]>0)
		{
			if($order['before_interest_date']<$now&&$order['before_interest_enddate']>$now){
				//小于起息时间，就是预热期就赎回
				$redempte['type']=0;
				$day=intval(($now-$order['before_interest_date'])/24/3600);
				if($day<=0){
					$day=0;
				}
				$redempte['earn_money']=$money*$day*$licai_interest['before_breach_rate']*0.01/365;
				$redempte['fee']=$money*$day*$licai_interest['redemption_fee_rate']*0.01/365;
				$redempte['organiser_fee']=$money*$day*$licai_interest['platform_breach_rate']*0.01/365;
				
			}elseif($order['before_interest_enddate']<=$now&&$order['begin_interest_date']>$now){
				//完成预期期间，未进入正式起息时间
				$redempte['type']=1;
				$day=intval(($order['before_interest_enddate']-$order['before_interest_date'])/24/3600);
				if($day<=0){
					$day=0;
				}
				$redempte['earn_money']=$money*$day*$licai_interest['before_rate']*0.01/365;
				$redempte['fee']=$money*$day*$licai_interest['redemption_fee_rate']*0.01/365;
				$redempte['organiser_fee']=$money*$day*$licai_interest['platform_breach_rate']*0.01/365;
				 
			}elseif($order['begin_interest_date']<=$now&&$order['end_interest_date']>$now){
				//进入正式起息时间,违约
				
				$redempte['type']=1;
	 
				
				$day_before=intval(($order['before_interest_enddate']-$order['before_interest_date'])/24/3600);
				if($day_before<=0){
					$day_before=0;
				}
				
				$before_earn_money=$money*$day_before*$licai_interest['before_rate']*0.01/365;
				
				$day_begin=intval(($now-$order['begin_interest_date'])/24/3600);
			
				if($day_begin<=0){
					$day_begin=0;
					if($order['begin_interest_date']!=$order['before_interest_enddate']){
						$redempte['type']=1;
					}else{
						$redempte['type']=3;
					}
				}
				$begin_earn_money=$money*$day_begin*$licai_interest['breach_rate']*0.01/365;
				
				$redempte['earn_money']=$before_earn_money+$begin_earn_money;
				$redempte['fee']=$money*($day_before+$day_begin)*$licai_interest['redemption_fee_rate']*0.01/365;
				$redempte['organiser_fee']=$money*($day_before+$day_begin)*$licai_interest['platform_breach_rate']*0.01/365;
				
			}elseif($order['end_interest_date']<=$now){
				 
				$redempte['type']=2;
	 
				$day_before=intval(($order['before_interest_enddate']-$order['before_interest_date'])/24/3600);
				$before_earn_money=$money*$day_before*$licai_interest['before_rate']*0.01/365;
				
				$day_begin=intval(($order['end_interest_date']-$order['begin_interest_date'])/24/3600);
				$begin_earn_money=$money*$day_begin*$licai_interest['interest_rate']*0.01/365;
				
				$redempte['earn_money']=$before_earn_money+$begin_earn_money;
				$redempte['fee']=$money*($day_before+$day_begin)*$licai_interest['redemption_fee_rate']*0.01/365;
				$redempte['organiser_fee']=$money*($day_before+$day_begin)*$licai_interest['platform_rate']*0.01/365;
				 
			}
		}
		else
		{	
			$order_data['platform_rate'] = $licai_interest['platform_rate'];
			$order_data['service_fee_rate'] = $licai_interest['service_fee_rate'];
			$order_data['redemption_fee_rate'] = $licai_interest['redemption_fee_rate'];
			
			if($order['end_interest_date'] < NOW_TIME)
			{
				$redempte['type']=2;
				
				$days = intval(($order['end_interest_date']-$order['begin_interest_date'])/24/3600);
			}
			else
			{
				$redempte['type']=1;
				
				$days = intval((NOW_TIME-$order['begin_interest_date'])/24/3600);
			}
			
			if($days<0)
			{
				$days = 0;
			}
			$redempte['earn_money']=$money*$licai_interest["interest_rate"]/365/100;
			$redempte['fee']=$money*$days*$order_data['redemption_fee_rate']*0.01/365;
			$redempte['organiser_fee']=$money*$days*$order_data['platform_rate']*0.01/365;

		}
 		$redempte['status']=0;
		
		$redempte['create_date']=to_date(NOW_TIME);
		$redempte['update_date']=to_date(NOW_TIME);
		
		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."licai_redempte",$redempte);
		if($re){
			$info['info']='申请成功';
			return $info;
		}else{
			$info['status']=0;
			$info['info']='操作失败,请重新提交';
			return $info;
		}
	}else{
		$info['status']=0;
		$info['info']='您的订单不存在';
		return $info;
	}
	
	
}

function interest_item_format($vo)
{
	$vo["min_money_format"] = format_price($vo["min_money"]);
	$vo["max_money_format"] = format_price($vo["max_money"]);
	$vo["interest_rate_format"] = number_format($vo["interest_rate"],4)."%";
	$vo["buy_fee_rate_format"] = number_format($vo["buy_fee_rate"],4)."%";
	$vo["site_buy_fee_rate_format"] = number_format($vo["site_buy_fee_rate"],4)."%";
	$vo["redemption_fee_rate_format"] = number_format($vo["redemption_fee_rate"],4)."%";
	$vo["before_rate_format"] = number_format($vo["before_rate"],4)."%";
	$vo["before_breach_rate_format"] = number_format($vo["before_breach_rate"],4)."%";
	$vo["breach_rate_format"] = number_format($vo["breach_rate"],4)."%";
	$vo["platform_rate_format"] = number_format($vo["platform_rate"],4)."%";
	$vo["freeze_bond_rate_format"] = number_format($vo["freeze_bond_rate"],4)."%";
	$vo["platform_breach_rate_format"] = number_format($vo["platform_breach_rate"],4)."%";
	
	return $vo;
}

function get_licai_status($id)
{
	$vo = $GLOBALS["db"]->getRow("select * from ".DB_PREFIX."licai where id =".$id);
	
	if(!$vo)
	{
		return "";
	}
	$time = get_peizi_next_date(to_timespan($vo["begin_interest_date"]),$vo['begin_buy_date']);
	if(NOW_TIME < to_timespan($time))
	{
		$status = 0;
	}
	elseif(($vo["end_date"] =="" || $vo["end_date"] = "0000-00-00" || NOW_TIME <= to_timespan($vo["end_date"]) && NOW_TIME >= to_timespan($time)))
	{
		$status = 1;
	}
	elseif(NOW_TIME > to_timespan($vo["end_date"]))
	{
		$status = 3;
	}
	return $status;
}

function format_money_wan($money)
{
	return ($money/10000)."万元";
}
	
	
?>
