<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
 class dealModule extends BaseModule
{
	public function index()
	{		
		$this->show();
	}
	//show_type 0 表示默认（股权和 融资） 1表示公司页面
	public function show($show_type=0)
	{
		
		//get_mortgate();
		
 		//获取项目的ID
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		
 		$access=get_level_access($GLOBALS['user_info'],$deal_info);
 		 
		$GLOBALS['tmpl']->assign("access",$access);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		if($deal_info['type']==1)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_investor_cate where id=".$deal_info['cate_id']);
		elseif($deal_info['type']==2)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_house_cate where id=".$deal_info['cate_id']);
		elseif($deal_info['type']==3)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_selfless_cate where id=".$deal_info['cate_id']);
		else
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
 		
 		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		
		if($deal_info['is_effect']==1)
		{
			log_deal_visit($deal_info['id']);
		}		
		if($deal_info['type']==1||$deal_info['type']==4){
			//跟投人数
			$gen_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=2 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('gen_num',intval($gen_num));
			//询价人数
			$xun_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=0 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('xun_num',intval($xun_num));
		}
		
		//项目图片
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);

		$deal_info = cache_deal_extra($deal_info);
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign('comment_count',$comment_count);
		$this->init_deal_page(@$deal_info);

		if(app_conf("INVEST_STATUS")==2 && $deal_info['type']==0)
		{
			showErr("产品众筹已经关闭");
		}
		
		if($deal_info['type']==1){
			$GLOBALS['tmpl']->assign('deal_type','gq_type');
		}elseif($deal_info['type']==2){
			$GLOBALS['tmpl']->assign('deal_type','house_type');
			
			if(!app_conf("IS_HOUSE"))
				showErr("房产众筹已经关闭");
		}
		elseif($deal_info['type']==3){
			$GLOBALS['tmpl']->assign('deal_type','selfless_type');
		}
		elseif($deal_info['type']==4){
			$GLOBALS['tmpl']->assign('deal_type','finance_type');
		}else{
			$GLOBALS['tmpl']->assign('deal_type','product_type');
		}
 		
		if($deal_info['type']==1||$deal_info['type']==4){
			if(app_conf("INVEST_STATUS")==1)
			{
				showErr(app_conf("GQ_NAME")."已经关闭");
			}
			set_deal_status($deal_info);
	
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
  			$GLOBALS['tmpl']->assign("user_name",$user_name);
  			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' ); 	
 			$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			$GLOBALS['tmpl']->assign("cate",$cate);
			//编辑及管理团队
			$stock_list = unserialize($deal_info['stock']);
			$GLOBALS['tmpl']->assign("stock_list",$stock_list);
			$unstock_list = unserialize($deal_info['unstock']);
			$GLOBALS['tmpl']->assign("unstock_list",$unstock_list);
			//项目历史执行资料
			$history_list = unserialize($deal_info['history']);
			$GLOBALS['tmpl']->assign("history_list",$history_list);	
			$total_history_income =0;
			$total_history_out=0;
			$total_history=0;
			foreach($history_list as $key => $v)
			{
				$total_history_income += floatval($v["info"]["item_income"]);
				$total_history_out+= floatval($v["info"]["item_out"]);
				$total_history=$total_history_income-$total_history_out;
			}
			$GLOBALS['tmpl']->assign("total_history_income",$total_history_income);
			$GLOBALS['tmpl']->assign("total_history_out",$total_history_out);
			$GLOBALS['tmpl']->assign("total_history",$total_history);
			//未来三年内计划
			$plan_list = unserialize($deal_info['plan']);
			$GLOBALS['tmpl']->assign("plan_list",$plan_list);
			$total_plan_income =0;
			$total_plan_out=0;
			$total_plan=0;
			foreach($plan_list as $key => $v)
			{
				$total_plan_income += floatval($v["info"]["item_income"]);
				$total_plan_out+= floatval($v["info"]["item_out"]);
				$total_plan=$total_plan_income-$total_plan_out;
			}
			$GLOBALS['tmpl']->assign("total_plan_income",$total_plan_income);
			$GLOBALS['tmpl']->assign("total_plan_out",$total_plan_out);
			$GLOBALS['tmpl']->assign("total_plan",$total_plan);
			//项目附件
			$attach_list = unserialize($deal_info['attach']);
			$GLOBALS['tmpl']->assign("attach_list",$attach_list);
			
			$this->investor_info($deal_info,$id);

			//热门的项目
			$deal_hot_result = get_deal_list($limit,'is_hot=1','support_count desc');
			$GLOBALS['tmpl']->assign("deal_hot_list",$deal_hot_result['list']);
			
			//固定回报总共期数
			$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
			$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
			//分红总共期数
			$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
			$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
			if($show_type==0){
				if($deal_info['type']==1){
					$GLOBALS['tmpl']->display("deal_investor_show.html");
				}else{
					$GLOBALS['tmpl']->display("finance/company_deal_finance.html");
				}
			}elseif($show_type==1){
				$GLOBALS['tmpl']->display("finance/company_deal_show.html");
			}elseif($show_type==2){
				
			}
		}else{
			
			if($deal_info['type'] ==2)
				$GLOBALS['tmpl']->display("deal_house_show.html");
			else
				$GLOBALS['tmpl']->display("deal_show.html");//普通众筹
		}
	}
	
	public function init_deal_page($deal_info)
	{
 				$GLOBALS['tmpl']->assign("page_title",$deal_info['name']);
		
		if($deal_info['seo_title']!="")
			$GLOBALS['tmpl']->assign("seo_title",$deal_info['seo_title']);
		if($deal_info['seo_keyword']!="")
			$GLOBALS['tmpl']->assign("seo_keyword",$deal_info['seo_keyword']);
		if($deal_info['seo_description']!="")
			$GLOBALS['tmpl']->assign("seo_description",$deal_info['seo_description']);
		
		$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);
	
	
		$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
		if($deal_info['type']==1||$deal_info['type']==4){
			$deal_info['limit_price_format'] = number_price_format(($deal_info['limit_price']/10000));
		}else{
			$deal_info['limit_price_format'] = $deal_info['limit_price'];
		}
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
 		$deal_info['person']=0;
		$type_2=0;
		$type_array=array();
		//初始化与虚拟金额有所关联的几个比较特殊的数据 start
		foreach ($deal_info['deal_item_list'] as $k=>$v){
 			//统计每个子项目真实+虚拟（人）
			$deal_info['deal_item_list'][$k]['virtual_person_list']=intval($v['virtual_person']+$v['support_count']);
 			if($v['type']==1){
  				$type_array[]=$v;
 				unset($deal_info['deal_item_list'][$k]);
 			}
 		}
  		if($type_array){
 			$deal_info['deal_item_list']=array_merge($deal_info['deal_item_list'],$type_array);
 		}
 		if($deal_info['type']==1||$deal_info['type']==4){
	 		$deal_info['person']=$deal_info['invote_num'];
			$deal_info['total_virtual_price']=number_price_format($deal_info['invote_money']/10000);
 			$deal_info['percent']=round(($deal_info['invote_money']/$deal_info['limit_price'])*100,2);
 		}else{
	 		$deal_info['person']=$deal_info['support_count']+$deal_info['virtual_num'];
			$deal_info['total_virtual_price']=$deal_info['support_amount']+$deal_info['virtual_price'];
 			$deal_info['percent']=round((($deal_info['support_amount']+$deal_info['virtual_price'])/$deal_info['limit_price'])*100,2);
 		}
 		//项目等级放到项目详细页面模块（对详细页面进行控制）
		$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
		$deal_info['virtual_person']=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$deal_info['id']);
		//初始化与虚拟金额有所关联的几个比较特殊的数据 end
		
  		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
	
		$deal_item_list = $deal_info['deal_item_list'];
		//开启限购后剩余几位
		foreach ($deal_item_list as $k=>$v){
			if($v['limit_user']>0){
				$deal_item_list[$k]['virtual_add_support_person']=$v['virtual_person']+$v['support_count'];
				$deal_item_list[$k]['remain_person']=$v['limit_user']-$deal_item_list[$k]['virtual_add_support_person'];
				if($deal_item_list[$k]['remain_person'] < 0)
					$deal_item_list[$k]['remain_person']=0;
			}
		}
		$GLOBALS['tmpl']->assign("deal_item_list",$deal_item_list);
 		if($GLOBALS['user_info'])
		{
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focus",$is_focus);
		}
	
	}
	public function comment()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		if($deal_info['type']==2)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_house_cate where id=".$deal_info['cate_id']);
		else
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);

		//项目图片
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);
		// $deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		$this->investor_info($deal_info,$id);
		$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page($deal_info);	
		
		
		$page_size = DEAL_COMMENT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		$comment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1 order by create_time desc limit ".$limit);
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		
		$GLOBALS['tmpl']->assign("comment_list",$comment_list);
		$GLOBALS['tmpl']->assign("comment_count",$comment_count);
		require APP_ROOT_PATH.'app/Lib/page.php';
		$page = new Page($comment_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		//固定回报总共期数
		$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
		//分红总共期数
		$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
		
		$GLOBALS['tmpl']->display("deal_comment.html");
	}
	
	public function support()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
		if($deal_info['type']==2)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_house_cate where id=".$deal_info['cate_id']);
		else
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}
		
		//项目图片
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);
				
		$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page($deal_info);	
		
		$page_size = DEAL_SUPPORT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		$support_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_support_log where deal_id = ".$id." order by create_time desc limit ".$limit);
		$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_support_log where deal_id = ".$id);
		//支持者优化 start
		$user_ids=array();
		
		foreach($support_list as $k=>$v)
		{
			$user_ids[]=$v['user_id'];
		}
		$support_user_info=$GLOBALS['db']->getAll("select id,user_name from ".DB_PREFIX."user where id in (".implode(",",$user_ids).")");
		foreach ($support_user_info as $k=>$v){
			foreach ($support_list as $kk=>$vv){
				if($support_list[$kk]['user_id']==$support_user_info[$k]['id']){
					$support_list[$kk]['user_info']=$support_user_info[$k];
				}
			}
		}
		//支持者优化 end
		$GLOBALS['tmpl']->assign("support_list",$support_list);
		
		require APP_ROOT_PATH.'app/Lib/page.php';
		$page = new Page($support_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$p = $page->new_para_show("deal#support", array('id'=>$id));
		$GLOBALS['tmpl']->assign('pages',$p);	
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign('comment_count',$comment_count);
		$GLOBALS['tmpl']->display("deal_support.html");
	}
	
	public function focus()
	{
		if(!$GLOBALS['user_info'])
		{
			$data['status'] = 0;
		}
		else
		{
			$id = intval($_REQUEST['id']);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1");
			if(!$deal_info)
			{
				$data['status'] = 3;	
				$data['info'] = "项目不存在";
				ajax_return($data);
			}
			
			$focus_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_focus_log where deal_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if($focus_data)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set focus_count = focus_count - 1 where id = ".$id." and is_effect = 1");
				if($GLOBALS['db']->affected_rows()>0)
				{
					$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where id = ".$focus_data['id']);
					$GLOBALS['db']->query("update ".DB_PREFIX."user set focus_count = focus_count - 1 where id = ".intval($GLOBALS['user_info']['id']));
					
					//删除准备队列
					$GLOBALS['db']->query("delete from ".DB_PREFIX."user_deal_notify where user_id = ".intval($GLOBALS['user_info']['id'])." and deal_id = ".$id);
					$data['status'] = 2;	
				}	
				else
				{
					$data['status'] = 3;	
					$data['info'] = "项目未上线";
				}		
			}
			else
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set focus_count = focus_count + 1 where id = ".$id." and is_effect = 1");
				if($GLOBALS['db']->affected_rows()>0)
				{
					$focus_data['user_id'] = intval($GLOBALS['user_info']['id']);
					$focus_data['deal_id'] = $id;
					$focus_data['create_time'] = NOW_TIME;
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_focus_log",$focus_data);
					$GLOBALS['db']->query("update ".DB_PREFIX."user set focus_count = focus_count + 1 where id = ".intval($GLOBALS['user_info']['id']));
					
					//关注项目成功，准备加入准备队列
					if($deal_info['is_success'] == 0 && $deal_info['begin_time'] < NOW_TIME && ($deal_info['end_time']==0 || $deal_info['end_time']>NOW_TIME))
					{
						//未成功的项止准备生成队列
						$notify['user_id'] = $GLOBALS['user_info']['id'];
						$notify['deal_id'] = $deal_info['id'];
						$notify['create_time'] = NOW_TIME;
						$GLOBALS['db']->autoExecute(DB_PREFIX."user_deal_notify",$notify,"INSERT","","SILENT");
					}
					
					$data['status'] = 1;
				}
				else
				{
					$data['status'] = 3;
					$data['info'] = "项目未上线";
				}
			}
		}
		
		
		ajax_return($data);
	}
	
	
	public function update()
	{
		
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
		if($deal_info['type']==2)
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_house_cate where id=".$deal_info['cate_id']);
		else
			$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);//项目图片
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}	
		if($deal_info['type']==1||$deal_info['type']==4){
			//跟投人数
			$gen_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=2 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('gen_num',intval($gen_num));
			//询价人数
			$xun_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where  type=0 and  deal_id=".$id);
			$GLOBALS['tmpl']->assign('xun_num',intval($xun_num));
			//预购总额
			$yu_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."investment_list where deal_id=$id ");
			$GLOBALS['tmpl']->assign('yu_money',intval($yu_money));
			$GLOBALS['tmpl']->assign("percent_invest",round($yu_money/$deal_info['limit_price']*100,2));
		}	
		$this->investor_info($deal_info,$id);
		$deal_info = cache_deal_extra($deal_info);
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign('comment_count',$comment_count);
		$this->init_deal_page($deal_info);	

		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);				
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']);
		
		
		if(!$log_list||(($page-1)*$page_size+($step-1)*$step_size)+count($log_list)>=$log_count)
		{
			//最后一页
			$log_list[] = array("deal_id"=>$deal_info['id'],
								"create_time"=>$deal_info['begin_time']+1,
								"id"=>0);
		}
		
		$last_time_key = "";
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$online_time = online_date($v['create_time'],$deal_info['begin_time']);
			$log_list[$k]['online_time'] = $online_time['info'];
			if($online_time['key']!=$last_time_key)
			{
				$last_time_key = $log_list[$k]['online_time_key'] = $online_time['key'];				
			}
			
			$log_list[$k] = cache_log_comment($log_list[$k]);
		}

		$GLOBALS['tmpl']->assign("log_list",$log_list);		
		
		require APP_ROOT_PATH.'app/Lib/page.php';
		$page = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		//固定回报总共期数
		$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
		//分红总共期数
		$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
		
		if($deal_info['type']==4){
			$GLOBALS['tmpl']->assign("deal_info_type",4);
			$GLOBALS['tmpl']->display("finance/company_deal_update.html");
		}elseif($deal_info['type']==2)
		{
			$GLOBALS['tmpl']->display("deal_house_update.html");
		}
		else{
			$GLOBALS['tmpl']->display("deal_update.html");
		}
	}
	
	
	
	public function updatedetail()
	{
		
		$id = intval($_REQUEST['id']);
		$update_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_log where id = ".$id);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".intval($update_info['deal_id'])." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);//项目图片
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		$deal_info = cache_deal_extra($deal_info);
		$this->init_deal_page($deal_info);	
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where id = ".$id);				
		
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$online_time = online_date($v['create_time'],$deal_info['begin_time']);
			$log_list[$k]['online_time'] = $online_time['info'];
			if($online_time['key']!=$last_time_key)
			{
				$last_time_key = $log_list[$k]['online_time_key'] = $online_time['key'];				
			}
			
			
			$log_list[$k]['comment_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where log_id = ".$v['id']." and status=1 and deal_id = ".$deal_info['id']);
			
			$page_size = DEAL_COMMENT_PAGE_SIZE;
			$page = intval($_REQUEST['p']);
			if($page==0)$page = 1;		
			$limit = (($page-1)*$page_size).",".$page_size;
			$log_list[$k]['comment_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_comment where log_id = ".$v['id']." and status=1 and deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);

			require APP_ROOT_PATH.'app/Lib/page.php';
			$page = new Page($log_list[$k]['comment_count'],$page_size);   //初始化分页对象 		
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);	
		}
		
		
		$GLOBALS['tmpl']->assign("log_list",$log_list);			
		$GLOBALS['tmpl']->display("deal_updatedetail.html");
	}
	
	
	public function add_update()
	{
		if(!$GLOBALS['user_info'])
		{
			$data['html'] = $GLOBALS['tmpl']->display("inc/user_login_box.html","",true);			
			$data['status'] = 2;
		}
		else
		{
			$id = intval($_REQUEST['id']);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
			if(!$deal_info)
			{
				showErr("不能更新该项目的动态",1);
			}
			else
			{
				if( $deal_info['type'] == 2)
				{
					$houses_status_list=get_houses_status_list();
					$GLOBALS['tmpl']->assign("houses_status_list",$houses_status_list);
				}
				$GLOBALS['tmpl']->assign("deal_info",$deal_info);
				$data['html'] = $GLOBALS['tmpl']->fetch("inc/add_update.html");			
				$data['status'] = 1;
			}
		}
		ajax_return($data);
	}
	
	public function save_update()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}

	
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		if(!$deal_info)
		{
			showErr("不能更新该项目的动态",$ajax);
		}
		else
		{
			$data['log_info'] = strim($_REQUEST['log_info']);
			if($data['log_info']=="")
			{
				showErr("请输入更新的内容",$ajax,"");
			}
			$data['image'] = strim($_REQUEST['image'])!=""?replace_public($_REQUEST['image']):"";
			$data['update_log_icon'] = strim($_REQUEST['update_log_icon'])!=""?replace_public($_REQUEST['update_log_icon']):"";
			$data['vedio'] = strim($_REQUEST['vedio']);
			if($data['vedio']!="")
			{
				$data['source_vedio'] = $data['vedio'];
			}
			$data['houses_status'] = strim($_REQUEST['houses_status']);//房产众筹的楼盘阶段
			$data['user_id'] = intval($GLOBALS['user_info']['id']);
			$data['deal_id'] = $id;
			$data['create_time'] = NOW_TIME;
			$data['user_name'] = $GLOBALS['user_info']['user_name'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_log",$data);
			
			if($deal_info['type']==2){
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set log_count = log_count + 1,houses_status = '".$data['houses_status']."' where id = ".$deal_info['id']);
			}else
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set log_count = log_count + 1 where id = ".$deal_info['id']);
			}
			if($deal_info['type']==4){
				showSuccess("",$ajax,url("finance#company_update",array("id"=>$deal_info['id'])));
			}else{
				showSuccess("",$ajax,url("deal#update",array("id"=>$deal_info['id'])));
			}
		}
	}
	
	public function delcomment()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		$comment_id = intval($_REQUEST['id']);
		$comment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_comment where id = ".$comment_id." and user_id = ".intval($GLOBALS['user_info']['id']));
		if($comment_item)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where id = ".$comment_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if($comment_item['log_id']==0)
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set comment_count = comment_count - 1 where id = ".$comment_item['deal_id']);
			if($ajax==1)
			{
				if($GLOBALS['db']->affected_rows()>0)
				{
					$data['status'] = 1;
					$data['logid'] = $comment_item['log_id'];
					$data['counthtml'] = "评论(".$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where log_id = ".$comment_item['log_id']).")";
					ajax_return($data);
				}
				else
				{
					showErr("删除失败",$ajax);
				}
			}
			else
			{
				showSuccess("记录删除成功");
			}
		}
		else
		{
			showErr("您无权删除该记录",$ajax);
		}
	}
	
	public function save_comment()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$comment['deal_id'] = intval($_REQUEST['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$comment['deal_id']." and is_delete = 0 and is_effect = 1 ");
		if(!$deal_info)
		{
			showErr("该项目暂时不能评论",$ajax);
		}
		
		if(!check_ipop_limit(get_client_ip(),"deal_save_comment",3))
		showErr("提交太快",$ajax);	
		
		$comment['content'] = strim($_REQUEST['content']);
		$comment['user_id'] = intval($GLOBALS['user_info']['id']);
		$comment['create_time'] =  NOW_TIME;
		$comment['log_id'] = intval($_REQUEST['log_id']);
		$comment['user_name'] = $GLOBALS['user_info']['user_name'];
		$comment['pid'] = intval($_REQUEST['pid']);
		$comment['deal_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal where id = ".$comment['deal_id']));
		$comment['reply_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal_comment where id = ".$comment['pid']));		
		$comment['deal_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['deal_user_id']));
		$comment['reply_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['reply_user_id']));
		if(app_conf("USER_MESSAGE_AUTO_EFFECT")){
			$comment['status']=1;
		}
 		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_comment",$comment);
		$comment['id'] = $GLOBALS['db']->insert_id();		
		
		if(intval($_REQUEST['syn_weibo'])==1)
		{
			$weibo_info = array();
			$weibo_info['content'] = $comment['content']." ".get_domain().url("deal#show",array("id"=>$comment['deal_id']));
			$img = $GLOBALS['db']->getOne("select image from ".DB_PREFIX."deal where id = ".intval($comment['deal_id']));
			if($img)$weibo_info['img'] = APP_ROOT_PATH."/".$img;
			syn_weibo($weibo_info);
		}
		
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_log set comment_data_cache = '' where id = ".intval($_REQUEST['log_id']));
		
		if($ajax==1)
		{
			$data['status'] = 1;
			$GLOBALS['tmpl']->assign("comment_item",$comment);
			$data['html'] = $GLOBALS['tmpl']->fetch("inc/comment_item.html");
			$data['counthtml'] = "评论(".$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where log_id = ".$comment['log_id']).")";
			ajax_return($data);
		}
		else
		{
			showSuccess("发表成功");
		}
	}
	
	public function savedealcomment()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$comment['deal_id'] = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$comment['deal_id']." and is_delete = 0 and is_effect = 1 ");
		if(!$deal_info)
		{
			showErr("该项目暂时不能评论",$ajax);
		}
		
		if(!check_ipop_limit(get_client_ip(),"deal_savedealcomment",3))
		showErr("提交太快",$ajax);	
		
		$comment['content'] = strim($_REQUEST['content']);
		$comment['user_id'] = intval($GLOBALS['user_info']['id']);
		$comment['create_time'] =  NOW_TIME;
		$comment['user_name'] = $GLOBALS['user_info']['user_name'];
		$comment['pid'] = intval($_REQUEST['pid']);
		$comment['deal_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal where id = ".$comment['deal_id']));
		$comment['reply_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal_comment where id = ".$comment['pid']));		
		$comment['deal_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['deal_user_id']));
		$comment['reply_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['reply_user_id']));
		if(app_conf("USER_MESSAGE_AUTO_EFFECT")){
			$comment['status']=1;
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_comment",$comment);
		$comment['id'] = $GLOBALS['db']->insert_id();		
		
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set comment_count = comment_count+1 where id = ".$comment['deal_id']);
		
		if(intval($_REQUEST['syn_weibo'])==1)
		{
			$weibo_info = array();
			$weibo_info['content'] = $comment['content']." ".get_domain().url("deal#show",array("id"=>$comment['deal_id']));
			$img = $GLOBALS['db']->getOne("select image from ".DB_PREFIX."deal where id = ".intval($comment['deal_id']));
			if($img)$weibo_info['img'] = APP_ROOT_PATH."/".$img;
			syn_weibo($weibo_info);
		}
		
		if($ajax==1)
		{
			$data['status'] = 1;
			ajax_return($data);
		}
		else
		{
			showSuccess("发表成功");
		}
	}
	//全部投资人
	public function project_follow(){
		if(app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		//get_mortgate();
		//获取项目的ID
		$id = intval($_REQUEST['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1");
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
 		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
 		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);//项目图片
		
		$deal_info = cache_deal_extra($deal_info);
//		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
//		$GLOBALS['tmpl']->assign('comment_count',$comment_count);
		$this->init_deal_page(@$deal_info);	
		set_deal_status($deal_info);
 		//股权众筹
		$GLOBALS['tmpl']->assign("id",$id);
		$user_name = $GLOBALS['user_info']['user_name'];
		$GLOBALS['tmpl']->assign("user_name",$user_name);
 		$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
		$GLOBALS['tmpl']->assign("cate",$cate);
		
		//资质证明
		$audit_data_list = unserialize($deal_info['audit_data']);
			
		$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);

		//跟投、领投信息列表
		get_investor_info($id,1);
		
		$GLOBALS['tmpl']->assign("deal_item",$deal_info);

  		$GLOBALS['tmpl']->display("project_follow.html");
	}

	// 众筹买房详情
	public function financ_show()
	{
		$GLOBALS['tmpl']->display("deal_financ_house_show.html");
	}
	
	// 众筹建房详情第一步
	public function schedule_index()
	{
		$GLOBALS['tmpl']->display("deal_schedule_index.html");
	}
	// 众筹建房详情第二步
	public function schedule_two()
	{
		$GLOBALS['tmpl']->display("deal_schedule_two.html");
	}
	// 众筹建房详情第三步
	public function schedule_three()
	{
		$GLOBALS['tmpl']->display("deal_schedule_three.html");
	}
	// 众筹建房详情第四步
	public function schedule_four()
	{
		$GLOBALS['tmpl']->display("deal_schedule_four.html");
	}
	// 众筹建房详情第五步
	public function schedule_five()
	{
		$GLOBALS['tmpl']->display("deal_schedule_five.html");
	}
	// 众筹建房详情第六步
	public function schedule_six()
	{
		$GLOBALS['tmpl']->display("deal_schedule_six.html");
	}
	//股权详细页固定利息回报
	public function fixation_return()
	{
		
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		$id = intval($_REQUEST['id']);
		
		$GLOBALS['tmpl']->assign('id',$id);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);//项目图片
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		$this->investor_info($deal_info,$id);
		$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page($deal_info);	
		
		//固定回报年度
		$fixation_return_year=$GLOBALS['db']->getAll("select distinct year from ".DB_PREFIX."user_bonus where type = 1 and is_show = 0 and status = 1 and deal_id = ".$id." order by year asc");
		$GLOBALS['tmpl']->assign('fixation_return_year',$fixation_return_year);	
	
		//固定回报期数
		$fixation_return_number=$GLOBALS['db']->getAll("select distinct number from ".DB_PREFIX."user_bonus where type = 1 and is_show = 0 and status = 1 and deal_id = ".$id." order by number asc");
		$GLOBALS['tmpl']->assign('fixation_return_number',$fixation_return_number);	
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;
		
		if(intval($_REQUEST['year'])!=''){
			$year = intval($_REQUEST['year']);
		}
		else{
			$year=$fixation_return_year[0]['year'];
		}
		if(trim($_REQUEST['number'])!=''){
			$number = trim($_REQUEST['number']);
		}
		else{
			$number=$fixation_return_number[0]['number'];
		}
		$where .=" and year =".$year."";
		$where .=" and number =".$number."";
		$GLOBALS['tmpl']->assign('year',$year);
		$GLOBALS['tmpl']->assign('number',$number);
		$parameter=array();
		$parameter[]="id=".$id;
		$parameter[]="year=".$year;
		$parameter[]="number=".$number;
		$fixation_return = $GLOBALS['db']->getRow("select id,money,return_cycle,descripe from ".DB_PREFIX."user_bonus where deal_id = ".$id." $where and type = 1 and status=1 and is_show = 0");
		$GLOBALS['tmpl']->assign('fixation_return',$fixation_return);	
		$fixation_return_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where deal_id = ".$id." and user_bonus_id = ".$fixation_return['id']." order by id desc limit ".$limit);
		$fixation_return_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus_list where deal_id = ".$id." and  user_bonus_id = ".$fixation_return['id']);
		$GLOBALS['tmpl']->assign("fixation_return_list",$fixation_return_list);
		$GLOBALS['tmpl']->assign("fixation_return_count",$fixation_return_count);
		require APP_ROOT_PATH.'app/Lib/page.php';
		$parameter_str="&".implode("&",$parameter);
  		$page = new Page($fixation_return_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
			
		//固定回报总共期数
		$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
		//固定回报总共期数金额
		$total_bonus_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_bonus where type = 1 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('total_bonus_money',$total_bonus_money);	
		
		//	print_r($fixation_return_number);exit;
		//分红总共期数
		$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
		
		//评论条数
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign("comment_count",$comment_count);
		$GLOBALS['tmpl']->display("deal_fixation_return.html");
	}
	//股权详细页分红纪录
	public function share_bonus()
	{
		$access=get_level_access($GLOBALS['user_info'],$deal_info);
		$GLOBALS['tmpl']->assign("access",$access);
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign('id',$id);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		$deal_info['login_time']=$GLOBALS['db']->getOne("select login_time from ".DB_PREFIX."user where id=".$deal_info['user_id']);
 		$deal_info['user_icon']=$GLOBALS['user_level'][$deal_info['user_level']]['icon'];
 		$deal_info['is_investor']=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id=".$deal_info['user_id']);
		$deal_info['invote_mini_moneys'] =number_format(($deal_info['invote_mini_money']/10000),2);
		$deal_info['deal_imgs']=get_deal_images_list($deal_info['id'],$deal_info['image']);//项目图片
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		$this->investor_info($deal_info,$id);
		$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page($deal_info);	
		
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;
		
		//分红年度期数列表
		$share_bonus_year=$GLOBALS['db']->getAll("select distinct year from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and is_show = 0 and deal_id = ".$id." order by year asc");
		$GLOBALS['tmpl']->assign('share_bonus_year',$share_bonus_year);	
		//分红期数列表
		$share_bonus_number=$GLOBALS['db']->getAll("select distinct number from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and is_show = 0 and deal_id = ".$id." order by number asc");
		$GLOBALS['tmpl']->assign('share_bonus_number',$share_bonus_number);	
		
		if(intval($_REQUEST['year'])!=''){
			$year = intval($_REQUEST['year']);
		}
		else{
			$year=$share_bonus_year[0]['year'];
		}
		if(trim($_REQUEST['number'])!=''){
			$number = trim($_REQUEST['number']);
		}
		else{
			$number=$share_bonus_number[0]['number'];
		}
		$where .=" and year =".$year."";
		$where .=" and number =".$number."";
		$GLOBALS['tmpl']->assign('year',$year);
		$GLOBALS['tmpl']->assign('number',$number);
		$parameter=array();
		$parameter[]="id=".$id;
		$parameter[]="year=".$year;
		$parameter[]="number=".$number;
		$share_bonus = $GLOBALS['db']->getRow("select id,money,return_cycle,descripe from ".DB_PREFIX."user_bonus where deal_id = ".$id." $where and type = 0 and status=1 and is_show = 0");
		$GLOBALS['tmpl']->assign('share_bonus',$share_bonus);	
		$share_bonus_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where deal_id = ".$id." and user_bonus_id = ".$share_bonus['id']." order by id desc limit ".$limit);
		$share_bonus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus_list where deal_id = ".$id." and  user_bonus_id = ".$share_bonus['id']);
		$GLOBALS['tmpl']->assign("share_bonus_list",$share_bonus_list);
		$GLOBALS['tmpl']->assign("share_bonus_count",$share_bonus_count);
		require APP_ROOT_PATH.'app/Lib/page.php';
		$parameter_str="&".implode("&",$parameter);
  		$page = new Page($share_bonus_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		//分红总共期数金额
		$total_bonus_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_bonus where type = 0 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('total_bonus_money',$total_bonus_money);	
		//分红总共期数
		$share_bonus_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('share_bonus_num',$share_bonus_num);	
	
		//固定回报总共期数
		$fixation_return_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$id);
		$GLOBALS['tmpl']->assign('fixation_return_num',$fixation_return_num);
		//评论条数
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$GLOBALS['tmpl']->assign("comment_count",$comment_count);
		
		$GLOBALS['tmpl']->display("deal_share_bonus.html");
	}
	//股权右边的一些信息
	public function investor_info($deal_info,$id)
	{
		if($deal_info['type']==1||$deal_info['type']==4){
			if(app_conf("INVEST_STATUS")==1)
			{
				showErr(app_conf("GQ_NAME")."已经关闭");
			}
			set_deal_status($deal_info);
			//资质证明
			$audit_data_list = unserialize($deal_info['audit_data']);
			
			$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);

			//跟投、领投信息列表
			get_investor_info($id);
			//var_dump($deal_info);
 			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
		}
	}
}
?>