<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';

class dealModule{
	public function show(){
		$this->index();
	}
	public function index()
	{	
		
		$id = intval($_REQUEST['id']);
		
		//if(!isMobile())//判断是否是手机端，不是跳到pc端
		//{
			//app_redirect(url("deal#show",array('id'=>$id)));
		//}
		
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
 		$access=get_level_access($GLOBALS['user_info'],$deal_info);
 		$GLOBALS['tmpl']->assign("access",$access);
 		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}		
		
		if($deal_info['is_effect']==1)
		{
			log_deal_visit($deal_info['id']);
		}		
	 
		
		$wx=array();
		$wx['img_url']=$deal_info['image'];
		$wx['title']=addslashes($deal_info['name']);
		$wx['desc']=addslashes($deal_info['brief']);
		$GLOBALS['tmpl']->assign('wx',$wx);
		
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		
		$limit="0,3";
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);			
		foreach($log_list as $k=>$v){
			if($v['user_id']){
				$user_ids[]=$v['user_id'];
			}
		}
		$user_ids=array_filter($user_ids);
		if($user_ids){
 			$user_id_str=implode(',',array_filter($user_ids));
			$user_list_array=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id in (".$user_id_str.") ");
			foreach($user_list_array as $k=>$v){
				foreach($log_list as $k_log=>$v_log){
 					if($v['id']==$v_log['user_id']){
						$v['avatar']=get_user_avatar_root($v["id"],"middle");
						
						$log_list[$k_log]['user_info']=$v;
					}
				}
			}
		}
		$log_num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id'] );		
		$GLOBALS['tmpl']->assign("log_list",$log_list);
		$GLOBALS['tmpl']->assign("log_num",intval($log_num));
		
		$comment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1 order by create_time desc limit ".$limit);
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1 ");
		$user_ids=array();
		foreach($comment_list as $k=>$v){
			if($v['user_id']){
				$user_ids[]=$v['user_id'];
			}
		}
		$user_ids=array_filter($user_ids);
		if($user_ids){
 			$user_id_str=implode(',',array_filter($user_ids));
			$user_list_array=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id in (".$user_id_str.") ");
			foreach($user_list_array as $k=>$v){
				foreach($comment_list as $k_comment=>$v_comment){
 					if($v['id']==$v_comment['user_id']){
						$v['avatar']=get_user_avatar_root($v["id"],"middle");
 						$comment_list[$k_comment]['user_info']=$v;
					}
				}
			}
		}

		$GLOBALS['tmpl']->assign("info_url",url_wap("deal#info",array("id"=>$id)));
		$GLOBALS['tmpl']->assign("comment_list",$comment_list);
		$GLOBALS['tmpl']->assign("comment_count",intval($comment_count));
		$GLOBALS['tmpl']->assign("deal_index_url",url_wap("deal#index",array("id"=>$id)));
		$GLOBALS['tmpl']->assign("usermessage_url",url_wap("ajax#usermessage",array("id"=>$deal_info['user_id'])));
		$GLOBALS['tmpl']->assign("home_url",url_wap("deal#home",array("id"=>$deal_info['user_id'])));
		if($deal_info['type']==1||$deal_info['type']==4){
		
			set_deal_status($deal_info);
			 
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
  			$GLOBALS['tmpl']->assign("user_name",$user_name);
  			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' ); 	
 			
 			$cates = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			
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
			//资质证明
			$audit_data_list = unserialize($deal_info['audit_data']);
			$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);
			
			//跟投、领投信息列表
			get_investor_info($id,1);
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("cates",$cates);
 			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("deal_investor_show.html");
		}else{
			//	//print_r($deal_info);exit;
			if($deal_info['type']==2)
				$GLOBALS['tmpl']->display("deal_house_details.html");
			else
				$GLOBALS['tmpl']->display("deal_details.html");
				
		}
		
		
	}
	//
	public function detail()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		if($deal_info['type']==1||$deal_info['type']==4){
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
				$total_history_income += intval($v["info"]["item_income"]);
				$total_history_out+= intval($v["info"]["item_out"]);
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
				$total_plan_income += intval($v["info"]["item_income"]);
				$total_plan_out+= intval($v["info"]["item_out"]);
				$total_plan=$total_plan_income-$total_plan_out;
			}
			$GLOBALS['tmpl']->assign("total_plan_income",$total_plan_income);
			$GLOBALS['tmpl']->assign("total_plan_out",$total_plan_out);
			$GLOBALS['tmpl']->assign("total_plan",$total_plan);
			//项目附件
			$attach_list = unserialize($deal_info['attach']);			
			$GLOBALS['tmpl']->assign("attach_list",$attach_list);
			//跟投、领投信息列表
			get_investor_info($id,1);
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("deal_investor_detail.html");
		}
	
	
	}
	//商业模式（手机端）
	public function business()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		if($deal_info['type']==1||$deal_info['type']==4){
			set_deal_status($deal_info);
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
			$GLOBALS['tmpl']->assign("user_name",$user_name);
			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' );
			$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			$GLOBALS['tmpl']->assign("cate",$cate);
		
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("inc/investor_business_mapi.html");
		}
	
	
	}
	//创业团队（手机端）
	public function teams()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		if($deal_info['type']==1||$deal_info['type']==4){
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
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("inc/investor_teams_mapi.html");
		}
	}
	//历史情况（手机端）
	public function history()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		if($deal_info['type']==1||$deal_info['type']==4){
			set_deal_status($deal_info);
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
			$GLOBALS['tmpl']->assign("user_name",$user_name);
			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' );
			$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			$GLOBALS['tmpl']->assign("cate",$cate);
			//项目历史执行资料
			$history_list = unserialize($deal_info['history']);			
			$GLOBALS['tmpl']->assign("history_list",$history_list);
			$total_history_income =0;
			$total_history_out=0;
			$total_history=0;
			foreach($history_list as $key => $v)
			{
				$total_history_income += intval($v["info"]["item_income"]);
				$total_history_out+= intval($v["info"]["item_out"]);
				$total_history=$total_history_income-$total_history_out;
			}
			$GLOBALS['tmpl']->assign("total_history_income",$total_history_income);
			$GLOBALS['tmpl']->assign("total_history_out",$total_history_out);
			$GLOBALS['tmpl']->assign("total_history",$total_history);
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("inc/investor_history_mapi.html");
		}
	}
	//未来计划（手机端）
	public function plans()
	{
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info = cache_deal_extra($deal_info);
		if($deal_info['type']==1||$deal_info['type']==4){
			$this->init_deal_page(@$deal_info);
		}else{
			init_deal_page_wap(@$deal_info);
		}
		if($deal_info['type']==1||$deal_info['type']==4){
			set_deal_status($deal_info);
			$GLOBALS['tmpl']->assign("id",$id);
			$user_name = $GLOBALS['user_info']['user_name'];
			$GLOBALS['tmpl']->assign("user_name",$user_name);
			$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' );
			$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
			$GLOBALS['tmpl']->assign("cate",$cate);
			//未来三年内计划
			$plan_list = unserialize($deal_info['plan']);			
			$GLOBALS['tmpl']->assign("plan_list",$plan_list);
			$total_plan_income =0;
			$total_plan_out=0;
			$total_plan=0;
			foreach($plan_list as $key => $v)
			{
				$total_plan_income += intval($v["info"]["item_income"]);
				$total_plan_out+= intval($v["info"]["item_out"]);
				$total_plan=$total_plan_income-$total_plan_out;
			}
			$GLOBALS['tmpl']->assign("total_plan_income",$total_plan_income);
			$GLOBALS['tmpl']->assign("total_plan_out",$total_plan_out);
			$GLOBALS['tmpl']->assign("total_plan",$total_plan);
			$GLOBALS['tmpl']->assign('now',NOW_TIME);
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("inc/investor_plans_mapi.html");
		}
	}
	//动态
	public function update()
	{
		$GLOBALS['tmpl']->assign('is_loop',1);
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}		
		$deal_info = cache_deal_extra($deal_info);
		
		init_deal_page_wap($deal_info);	

		$page_size = 15;
		//$step_size = 5;
		
		//$step = intval($_REQUEST['step']);
		//if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		//$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		$limit = ($page-1)*$page_size.",".$page_size;
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);				
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']);
		
		/*
		if(!$log_list||(($page-1)*$page_size+($step-1)*$step_size)+count($log_list)>=$log_count)
		{
			//最后一页
			$log_list[] = array("deal_id"=>$deal_info['id'],
								"create_time"=>$deal_info['begin_time']+1,
								"id"=>0);
		}
		*/
		
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
		
		require APP_ROOT_PATH.'wap/app/page.php';
		$page = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$param_ajax['p']=$page+1;
		$page_ajax_url=url_wap("ajax#dealupdate",$param_ajax);
		$GLOBALS['tmpl']->assign("page_ajax_url",$page_ajax_url);
		$GLOBALS['tmpl']->assign("page_count",ceil(intval($result['rs_count'])/$page_size));
			
		if($deal_info['type']==2)
		{
			$GLOBALS['tmpl']->display("deal_house_update.html");
		}
		else{
			$GLOBALS['tmpl']->display("deal_update.html");
		}
	}
	//支持者
	public function support(){
		$GLOBALS['tmpl']->display("deal_support.html");
	}
	//评论
	public function comment(){
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}
		$deal_info = cache_deal_extra($deal_info);
		
		init_deal_page($deal_info);
		
		$comment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1 order by create_time asc");
	
		$comment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0 and status=1");
		$save_url=url_wap("deal#savedealcomment");
		//$save_url=url_wap("savedealcomment#deal");
		$GLOBALS['tmpl']->assign("page_title","项目评论");
		$GLOBALS['tmpl']->assign('save_url',$save_url);
		$GLOBALS['tmpl']->assign('deal_id',$id);
		$GLOBALS['tmpl']->assign("comment_list",$comment_list);
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		$GLOBALS['tmpl']->display("deal_comment.html");
	}
	//详情
	public function info(){
		//获取项目的ID
		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
		
		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}
		
		if($deal_info['is_effect']==1)
		{
			log_deal_visit($deal_info['id']);
		}
		
		$deal_info = cache_deal_extra($deal_info);
	
		init_deal_page($deal_info);
		$GLOBALS['tmpl']->assign("deal_index_url",url_wap("deal#index",array("id"=>$id)));
		$GLOBALS['tmpl']->assign("usermessage_url",url_wap("ajax#usermessage",array("id"=>$deal_info['user_id'])));
		$GLOBALS['tmpl']->assign("home_url",url_wap("deal#home",array("id"=>$deal_info['user_id'])));
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		$GLOBALS['tmpl']->display("deal_info.html");
	}
	
	public function savedealcomment()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("请先登录",$ajax,url_wap("user#login"));
		}
	
		$comment['deal_id'] = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$comment['deal_id']." and is_delete = 0 and is_effect = 1 ");
		if(!$deal_info)
		{
			showErr("该项目暂时不能评论",$ajax);
		}
	
		if(!check_ipop_limit(get_client_ip(),"deal_savedealcomment",3))
			showErr("提交太快",2);
	
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
	public function add_update()
	{
		$GLOBALS['tmpl']->assign("page_title","项目动态更新");
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
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
			$deal_info['update_url']=url_wap("deal#update",array("id"=>$deal_info['id']));
			$GLOBALS['tmpl']->assign("deal_info",$deal_info);
			// $data['html'] = $GLOBALS['tmpl']->fetch("inc/add_update.html");
			// $data['status'] = 1;
			$GLOBALS['tmpl']->display("inc/add_update.html");
		}
		// ajax_return($data);
	}
	
	public function save_update()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("index"));
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
			
			showSuccess("",$ajax,url_wap("deal#update",array("id"=>$deal_info['id'],'is_back'=>2)));
		}
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
		$deal_info['limit_price_format'] = number_price_format($deal_info['limit_price']/10000);
	
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
			$deal_info['total_virtual_price']=number_price_format($deal_info['support_amount']+$deal_info['virtual_price']);
			$deal_info['percent']=round((($deal_info['support_amount']+$deal_info['virtual_price'])/$deal_info['limit_price'])*100,2);
		}
		//项目等级放到项目详细页面模块（对详细页面进行控制）
		$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
		$deal_info['virtual_person']=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$deal_info['id']);
		
		$deal_info['update_url']=url_wap("deal#update",array("id"=>$deal_info['id']));
		$deal_info['comment_url']=url_wap("deal#comment",array("id"=>$deal_info['id']));
		//初始化与虚拟金额有所关联的几个比较特殊的数据 end
		
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
	
		$deal_item_list = $deal_info['deal_item_list'];
		//开启限购后剩余几位
		foreach ($deal_item_list as $k=>$v){
			if($v['limit_user']>0){
				$deal_item_list[$k]['remain_person']=$v['limit_user']-$v['virtual_person']-$v['support_count'];
			}
		}
		$GLOBALS['tmpl']->assign("deal_item_list",$deal_item_list);
		if($GLOBALS['user_info'])
		{
			$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
			$GLOBALS['tmpl']->assign("is_focus",$is_focus);
		}
	
	}
	
	//全部投资人
	public function project_follow(){
		
		//获取项目的ID
		$id = intval($_REQUEST['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1");
		$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
	
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
		//跟投、领投信息列表
		get_investor_info($id,1);
		$GLOBALS['tmpl']->display("project_follow.html");
	}
}

?>