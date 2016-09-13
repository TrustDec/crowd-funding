<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';

class deal_show
{
	public function index(){
		$root = array();
		$id = intval($GLOBALS ['request']['id']);
		$root['response_code'] = 1;
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$virtual_person=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$id);
		//获得该项目下的子项目的所有信息
		$deal_item_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id=".$id." and type=0");
		$virtual_person_list=array();
		foreach ($deal_item_list as $k=>$v){
			// 统计所有真实+虚拟（钱）
			$total_virtual_price+=$v['price'] * $v['virtual_person']+$v['support_amount'];
			//统计每个子项目真实+虚拟（钱）
			//$virtual_money_list[$v['id']]['total_money']=$v['price'] * $v['virtual_person']+$v['support_amount'];
			//统计每个子项目真实+虚拟（人）
			$virtual_person_list[$k]['virtual_person']=$v['virtual_person']+$v['support_count'];
		}
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		
		if($deal_info['is_effect']==1)
		{
			log_deal_visit($deal_info['id']);
		}		
		
		$deal_info = cache_deal_extra($deal_info);
		//项目等级放到项目详细页面模块（对详细页面进行控制）
		$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
		$deal_faq_list = $deal_info['deal_faq_list'];
		
		$root['deal_faq_list'] = $deal_faq_list; 
		$root['virtual_person'] = $virtual_person; 
		$root['virtual_person_list'] = $virtual_person_list; 
		$root['total_virtual_price'] = $total_virtual_price; 
		$root['person'] = $virtual_person+$deal_info['support_count']; 
//		init_deal_page($deal_info);	
		
		$root['page_title'] = $deal_info['name'];     
		if($deal_info['seo_title']!="")
		$root['seo_title'] = $deal_info['seo_title']; 
		if($deal_info['seo_keyword']!="")
		$root['seo_keyword'] = $deal_info['seo_keyword']; 
		if($deal_info['seo_description']!="")
		$root['seo_description'] = $deal_info['seo_description']; 
		$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);		
	
		
		$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
		$deal_info['limit_price_format'] = number_price_format($deal_info['limit_price']);
		
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
		$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100);
		$root['deal_info'] = $deal_info; 
		$deal_item_list = $deal_info['deal_item_list'];
		$root['deal_item_list'] = $deal_item_list; 
		if($deal_info['user_id']>0)
		{
			$deal_user_info = $GLOBALS['db']->getRow("select id,user_name,province,city,intro,login_time from ".DB_PREFIX."user where id = ".$deal_info['user_id']." and is_effect = 1");
			if($deal_user_info)
			{
				$deal_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$deal_user_info['id']);
				$root['deal_user_info'] = $deal_user_info; 
			}
		}
		
		if($GLOBALS['user_info'])
		{
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
			$root['is_focus'] = $is_focus; 
		}		
		output($root);		
	}
}
?>
