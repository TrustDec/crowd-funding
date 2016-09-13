<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class dealModule{
	public function index()
	{
		$this->business();
	}
	//商业模式（手机端）
	public function business()
	{
		$id = intval($_REQUEST['id']);
		$user_id = intval($_REQUEST['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".$user_id."))");
		//$deal_info = cache_deal_extra($deal_info);
		$this->init_deal_page(@$deal_info);
	
		set_deal_status($deal_info);
		$GLOBALS['tmpl']->assign("id",$id);
		$user_name = $user_info['user_name'];
		$GLOBALS['tmpl']->assign("user_name",$user_name);
		$deal_info['business_create_time'] =to_date ($deal_info['business_create_time'], 'Y-m-d' );
		$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_info['cate_id']);
		$GLOBALS['tmpl']->assign("cate",$cate);
		
		$GLOBALS['tmpl']->assign('now',NOW_TIME);
		$GLOBALS['tmpl']->assign("deal_item",$deal_info);
		$GLOBALS['tmpl']->display("investor_business_mapi.html");
		
	}
	//创业团队（手机端）
	public function teams()
	{
		$id = intval($_REQUEST['id']);
		$user_id = intval($_REQUEST['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".$user_id."))");
		//$deal_info = cache_deal_extra($deal_info);
		$this->init_deal_page(@$deal_info);
		set_deal_status($deal_info);
		$GLOBALS['tmpl']->assign("id",$id);
		$user_name = $user_info['user_name'];
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

		$GLOBALS['tmpl']->display("investor_teams_mapi.html");
		
	}
	//历史情况（手机端）
	public function history()
	{
		$id = intval($_REQUEST['id']);
		$user_id = intval($_REQUEST['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".intval($user_id)."))");
		//$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page(@$deal_info);
		
		
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
		$GLOBALS['tmpl']->display("investor_history_mapi.html");
		
	}
	//未来计划（手机端）
	public function plans()
	{
		$id = intval($_REQUEST['id']);
		$user_id = intval($_REQUEST['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".$user_id."))");
		//$deal_info = cache_deal_extra($deal_info);
		
		$this->init_deal_page(@$deal_info);
		
		set_deal_status($deal_info);
		$GLOBALS['tmpl']->assign("id",$id);
		$user_name = $user_info['user_name'];
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
		$GLOBALS['tmpl']->display("investor_plans_mapi.html");
		
	}
	
	//未来计划（手机端）
	public function biref()
	{	
		$id = intval($_REQUEST['id']);
		$user_id = intval($_REQUEST['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
		$deal_info = $GLOBALS['db']->getRow("select d.*,dl.level as deal_level,dc.name as deal_type from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_level as dl on dl.id=d.user_level left join ".DB_PREFIX."deal_cate as dc on dc.id=d.cate_id where d.id = ".$id." and d.is_delete = 0 and (d.is_effect = 1 or (d.is_effect = 0 and d.user_id = ".$user_id."))");
		
		$deal_info ['image'] = str_replace("./public/",SITE_DOMAIN.REAL_APP_ROOT."/public/",$deal_info ['image']);
		$pattern = "/<img([^>]*)\/>/i";
		$replacement = "<img $1 width=100% />";
		$deal_info ['description'] = preg_replace ( $pattern, $replacement, get_abs_img_root ($deal_info ['description'] ));
		//print_r($deal_info);
		//标题
		$this->init_deal_page(@$deal_info);
			
		//等级	
		//$access=get_level_access($$user_info,$deal_info);
 		//$GLOBALS['tmpl']->assign("access",$access);
		
		if($deal_info['type']==1)
		{
			//资质证明
			$audit_data_list = unserialize($deal_info['audit_data']);
			$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);
			
			$GLOBALS['tmpl']->assign("deal_item",$deal_info);
			$GLOBALS['tmpl']->display("investor_biref.html");
		}
		elseif($deal_info['type']==0)
		{
			$GLOBALS['tmpl']->assign("deal_info",$deal_info);
			$GLOBALS['tmpl']->display("deal_biref.html");
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
		/*
		$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);
	
	
		$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
		$deal_info['limit_price_format'] = number_price_format($deal_info['limit_price']/10000);
	
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
		$deal_info['person']=0;
	
		//初始化与虚拟金额有所关联的几个比较特殊的数据 start
		foreach ($deal_info['deal_item_list'] as $k=>$v){
			//统计每个子项目真实+虚拟（人）
			$deal_info['deal_item_list'][$k]['virtual_person_list']=intval($v['virtual_person']+$v['support_count']);
		}
		if($deal_info['type']==1){
			$deal_info['person']=$deal_info['invote_num'];
			$deal_info['total_virtual_price']=number_price_format($deal_info['invote_money']/10000);
			$deal_info['percent']=round(($deal_info['invote_money']/$deal_info['limit_price'])*100);
		}else{
			$deal_info['person']=$deal_info['support_count']+$deal_info['virtual_num'];
			$deal_info['total_virtual_price']=number_price_format($deal_info['support_amount']+$deal_info['virtual_price']);
			$deal_info['percent']=round((($deal_info['support_amount']+$deal_info['virtual_price'])/$deal_info['limit_price'])*100);
		}
		//项目等级放到项目详细页面模块（对详细页面进行控制）
		$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
		$deal_info['virtual_person']=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$deal_info['id']);
		
		$deal_info['update_url']=url_wap("deal#update",array("id"=>$deal_info['id']));
		$deal_info['comment_url']=url_wap("deal#comment",array("id"=>$deal_info['id']));
		//初始化与虚拟金额有所关联的几个比较特殊的数据 end
		if(!empty($deal_info['vedio'])&&!preg_match("/http://player.youku.com/embed/i",$deal_info['source_video'])){
			$deal_info['source_vedio']= preg_replace("/id_(.*)\.html(.*)/i","http://player.youku.com/embed/\${1}",baseName($deal_info['vedio']));
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set source_vedio='".$deal_info['source_vedio']."'  where id=".$deal_info['id']);
		}
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
		*/
	}
	
}
?>