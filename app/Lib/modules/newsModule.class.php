<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'app/Lib/page.php';
class newsModule extends BaseModule
{
	public function index()
	{	
                
		$GLOBALS['tmpl']->assign("page_title","最新动态");
		$cate_result = load_dynamic_cache("INDEX_CATE_LIST");
 		if($cate_result===false)
		{
			$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
			$cate_result= array();
			foreach($cate_list as $k=>$v)
			{
				$cate_result[$v['id']] = $v;
			}
			set_dynamic_cache("INDEX_CATE_LIST",$cate_result);
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " type=1 ";
		}
		
		$rand_deals = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where $condition and is_delete = 0 and is_effect = 1 and begin_time < ".NOW_TIME." and (end_time >".NOW_TIME." or end_time = 0) order by rand() limit 3");
		$GLOBALS['tmpl']->assign("rand_deals",$rand_deals);
		/*
		 * //获得所有项目ID数组
		$rand_deal_ids=array();
		foreach ($rand_deals as $k=>$v){
			$rand_deal_ids[]=$rand_deals[$k]['id'];
		}
		//获得每个项目下的所有子项目信息
		$deal_item_List=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id in(".implode(",",$rand_deal_ids).")");
		var_dump($deal_item_List);
		var_dump($rand_deal_ids);
		var_dump($rand_deals);
		 * */
		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		$GLOBALS['tmpl']->assign("current_page",$page);	
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " d.type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " d.type=1 ";
		}
		$log_list = $GLOBALS['db']->getAll("select l.*,d.invote_money as invote_money,d.limit_price as limit_price,d.type as type from ".DB_PREFIX."deal_log as l left join ".DB_PREFIX."deal as d on d.id=l.deal_id where $condition order by l.create_time desc limit ".$limit);
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log as l left join ".DB_PREFIX."deal as d on d.id=l.deal_id where $condition");
		/*准备虚拟数据 start*/
		if($log_count>0){
			$deal_item_ids=array();
			foreach($log_list as $k=>$v)
			{
				$log_list[$k]['pass_time'] = pass_date($v['create_time']);
				$log_list[$k] = cache_log_comment($log_list[$k]);
				$log_list[$k] = cache_log_deal($log_list[$k]);
				//$log_list[$k]['deal_info']['lin']=8;放进去
				//得到页面项目的ID
				$deal_item_ids[]=$log_list[$k]['deal_info']['id'];
			}
			
			//获得每个项目下的所有子项目信息
			$temp_deal_item_List=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id in(".implode(",",$deal_item_ids).") order by deal_id desc");
			$virtual_price_list  = array();
			//重新组装一个以项目ID为KEY的虚拟价格
			foreach ($temp_deal_item_List as $k=>$v){
				$virtual_price_list[$v['deal_id']]['total_virtual_price']+=$v['price'] * $v['virtual_person'];
			}
			unset($temp_deal_item_List);
			//放到项目动态表里面进行统计
			foreach($log_list as $k=>$v)
			{
				if($v['type']==1){
					$log_list[$k]['support_amount'] =$log_list[$k]['invote_money'];
					$log_list[$k]['percent'] = round(($log_list[$k]['invote_money'])/$v['limit_price']*100,2);
					
				}else{
					$log_list[$k]['deal_info']['percent']=round(($v['deal_info']['support_amount']+$virtual_price_list[$v['deal_id']]['total_virtual_price'])/$v['deal_info']['limit_price']*100,2);
					$log_list[$k]['deal_info']['support_amount'] +=$virtual_price_list[$v['deal_id']]['total_virtual_price'];
				
				}
				
			}
			
		}
		//print_r($log_list);exit;
		/*准备虚拟数据 end*/
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$pager = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $pager->show();
		$GLOBALS['tmpl']->assign('pages',$p);		
		
		$GLOBALS['tmpl']->assign("ajaxurl",url("ajax#news",array("p"=>$page)));		
		$GLOBALS['tmpl']->display("news.html");
	}
	

	public function fav()
	{	
                    
		$GLOBALS['tmpl']->assign("page_title","最新动态");
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['tmpl']->assign("page_title","我关注的项目动态");
		$cate_result = load_dynamic_cache("INDEX_CATE_LIST");
		if($cate_result===false)
		{
			$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
			$cate_result= array();
			foreach($cate_list as $k=>$v)
			{
				$cate_result[$v['id']] = $v;
			}
			set_dynamic_cache("INDEX_CATE_LIST",$cate_result);
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " type=1 ";
		}
		$rand_deals = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where $condition and is_delete = 0 and is_effect = 1 and begin_time < ".NOW_TIME." and (end_time >".NOW_TIME." or end_time = 0) order by rand() limit 3");
		$GLOBALS['tmpl']->assign("rand_deals",$rand_deals);
		
		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " de.type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " de.type=1 ";
		}
		
		$sql = "select dl.* from ".DB_PREFIX."deal_log as dl left join ".DB_PREFIX."deal_focus_log as dfl on dl.deal_id = dfl.deal_id left join ".DB_PREFIX."deal as de on de.id=dl.deal_id where $condition and dfl.user_id = ".intval($GLOBALS['user_info']['id'])." order by dl.create_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."deal_log as dl left join ".DB_PREFIX."deal_focus_log as dfl on dl.deal_id = dfl.deal_id left join ".DB_PREFIX."deal as de on de.id=dl.deal_id where $condition and dfl.user_id = ".intval($GLOBALS['user_info']['id']);
		
		$log_list = $GLOBALS['db']->getAll($sql);
		$log_count = $GLOBALS['db']->getOne($sql_count);
		
		/*(最新动态)准备虚拟数据 start*/
		if($log_count>0){
			$deal_item_ids=array();
			foreach($log_list as $k=>$v)
			{
				$log_list[$k]['pass_time'] = pass_date($v['create_time']);
				$log_list[$k] = cache_log_comment($log_list[$k]);
				$log_list[$k] = cache_log_deal($log_list[$k]);
				//$log_list[$k]['deal_info']['lin']=8;放进去
				//得到页面项目的ID
				$deal_item_ids[]=$log_list[$k]['deal_info']['id'];
			}
			//获得每个项目下的所有子项目信息
			$temp_deal_item_List=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id in(".implode(",",$deal_item_ids).") order by deal_id desc");
			$virtual_price_list  = array();
			//重新组装一个以项目ID为KEY的虚拟价格
			foreach ($temp_deal_item_List as $k=>$v){
				$virtual_price_list[$v['deal_id']]['total_virtual_price']+=$v['price'] * $v['virtual_person'];
			}
			unset($temp_deal_item_List);
			//放到项目动态表里面进行统计
			foreach($log_list as $k=>$v)
			{
				$log_list[$k]['deal_info']['percent']=round(($v['deal_info']['support_amount']+$virtual_price_list[$v['deal_id']]['total_virtual_price'])/$v['deal_info']['limit_price']*100,2);
				$log_list[$k]['deal_info']['support_amount'] +=$virtual_price_list[$v['deal_id']]['total_virtual_price'];
				
			}
			
		}
		
		/*(最新动态)准备虚拟数据 end*/
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$pager = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $pager->show();
		$GLOBALS['tmpl']->assign('pages',$p);		
		
		$GLOBALS['tmpl']->assign("ajaxurl",url("ajax#newsfav",array("p"=>$page)));		
		$GLOBALS['tmpl']->display("news.html");
	}
	
}
?>