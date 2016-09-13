<?php
/*
 * Created on 2015-8-27
 */
 
 function get_deal_list($limit="",$conditions="",$orderby=" sort asc ",$deal_type='deal'){
	
	
	if($limit!=""){
		$limit = " LIMIT ".$limit;
	}
	
	if($orderby!=""){
		$orderby = " ORDER BY ".$orderby;
	}
	
	if(app_conf("INVEST_STATUS")==0)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 ";
	}
	elseif(app_conf("INVEST_STATUS")==1)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 and d.type=0 ";
	}
	elseif(app_conf("INVEST_STATUS")==2)
	{
		$condition = " 1=1 AND d.is_delete = 0 AND d.is_effect = 1 and d.type=1 ";
	}
	
	if($conditions!=""){
		$condition.=" AND ".$conditions;
	}
	
	//权限浏览控制
 
 	$deal_count = $GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."deal as d  where ".$condition);
 
  	/*（所需项目）准备虚拟数据 start*/
	$deal_list = array();
	$level_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_level ");
	$level_list_array=array();
	foreach($level_list_array as $k=>$v){
		if($v['id']){
			$level_list_array[$v['id']]=$v['level'];
		}
	}
 	if($deal_count > 0){
		$now_time = NOW_TIME;
		$deal_list = $GLOBALS['db']->getAll("select d.* from ".DB_PREFIX."deal  as d   where ".$condition.$orderby.$limit);
 		//file_put_contents("condition.txt", print_r("select d.* from ".DB_PREFIX."deal  as d   where ".$condition.$orderby.$limit,1));
		$deal_ids = array();
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - $now_time)/(24*3600));
			if($v['begin_time'] > $now_time){
				$deal_list[$k]['left_days'] = ceil(($v['begin_time'] - $now_time) / 24 / 3600);
			}
			$deal_list[$k]['num_days'] = ceil(($v['end_time'] - $v['begin_time'])/(24*3600));
			$deal_ids[] =  $v['id'];
			//查询出对应项目id的user_level
			$deal_list[$k]['deal_level']=$level_list_array[intval($deal_list[$k]['user_level'])];
			if($v['begin_time'] > $now_time){
				$deal_list[$k]['left_begin_days'] = intval(($v['begin_time']  - $now_time) / 24 / 3600);
				$deal_list[$k]['left_begin_day'] = intval(($v['begin_time']  - $now_time));
			}
			if($v['begin_time'] > $now_time){
					$deal_list[$k]['status']= '0';                                 
			}
			elseif($v['end_time'] < $now_time && $v['end_time']>0){
				if($deal_list[$k]['percent'] >=100){
					$deal_list[$k]['status']= '1';  
				}
				else{
						$deal_list[$k]['status']= '2'; 
				}
			} 
			else{
					if ($v['end_time'] > 0) {
						$deal_list[$k]['status']= '3'; 
					}
					else
					$deal_list[$k]['status']= '4'; 
			}
			
			if($v['type']==1){
				$deal_list[$k]['virtual_person']=$deal_list[$k]['invote_num'];
				$deal_list[$k]['support_count'] =$deal_list[$k]['invote_num'];
				$deal_list[$k]['support_amount'] =$deal_list[$k]['invote_money'];
				$deal_list[$k]['percent'] = round(($deal_list[$k]['support_amount'])/$v['limit_price']*100,2);
				$deal_list[$k]['limit_price_w']=($deal_list[$k]['limit_price'])/10000;
				$deal_list[$k]['invote_mini_money_w']=number_format(($deal_list[$k]['invote_mini_money'])/10000,2);
			}else{
				$deal_list[$k]['virtual_person']=$deal_list[$k]['virtual_num'];
				$deal_list[$k]['support_count'] =$deal_list[$k]['virtual_num']+$deal_list[$k]['support_count'];
				$deal_list[$k]['support_amount'] =$deal_list[$k]['virtual_price']+$deal_list[$k]['support_amount'];
				$deal_list[$k]['percent'] = round(($deal_list[$k]['support_amount'])/$v['limit_price']*100,2);
 			}
 			if($deal_type=='deal_cate'||$deal_type=='deal_cate_preheat'){
 				$deal_list[$k]['user_info']=$GLOBALS['db']->getRowCached("select * from  ".DB_PREFIX."user where id=".$v['user_id']);
				$deal_list[$k]['deal_comment_num']=$GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$v['id']." and log_id = 0 and status=1 ");
				$deal_list[$k]['deal_comment_num']=intval($deal_list[$k]['deal_comment_num']);
				$deal_list[$k]['cate_name']=$GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."deal_cate where id=".$v['cate_id']);
  				if($deal_type=='deal_cate_preheat'){
  					//关注
  					$deal_list[$k]['focus_num']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$v['id']." and log_id = 0 and status=1 ");
   				}
  			}
		}
 	 
	}
	
	
	return array("rs_count"=>$deal_count,"list"=>$deal_list);
}
?>
