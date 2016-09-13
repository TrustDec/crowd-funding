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
class accountModule extends BaseModule
{
	//支持的项目
	//产品众筹支持的项目
	public function index(){
		
		if(app_conf("INVEST_STATUS") == 2)
		{
			showErr("产品众筹已经关闭");
		}
		$GLOBALS['tmpl']->assign("page_title","支持的产品项目");
		$this->index_public(0,'index');
	}
	//房产众筹支持的项目
	public function house_index(){
		if(app_conf("IS_HOUSE") == 0)
		{
			showErr("房产众筹已经关闭");
		}
		$GLOBALS['tmpl']->assign("page_title","支持的房产项目");
		$this->index_public(2,'house_index');
	}
	//公益众筹支持的项目
	public function selfless_index(){
		if(app_conf("IS_SELFLESS") == 0)
		{
			showErr("房产众筹已经关闭");
		}
		$GLOBALS['tmpl']->assign("page_title","支持的公益项目");
		$this->index_public(3,'selfless_index');
	}
	
	public function index_public($index_deal_type=0,$index_act='index')
	{	
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));	
		
		$index_deal_type=intval($index_deal_type);
		if($index_deal_type)
		{
			$_REQUEST['deal_type'] = $index_deal_type;
		}
		$deal_type = intval($_REQUEST['deal_type']);
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		//订单类型 0表示普通众筹 1表示股权众筹 2表示无私奉 3抽奖商品 5表示融资众筹 6表示股权转让 7房产众筹
		
		if($deal_type == 2)
		{
			$condition = " de.type = 2 and do.type in (0,2,3,7) ";//房产众筹订单
			$condition_sum  = " de.type = 2 and do.type in (0,2,3,7) ";
		}elseif($deal_type == 3)
		{
			$condition = " de.type = 3 and do.type in (0,2,3) ";//公益众筹订单
			$condition_sum = " de.type = 3 and do.type in (0,2,3) ";
		}else{
			$condition = " de.type = 0 and do.type in (0,2,3) ";//产品众筹订单
			$condition_sum = " de.type = 0 and do.type in (0,2,3) ";
		}
		$parameter=array();
		$parameter['deal_type']= $deal_type;
		$is_paid=intval($_REQUEST['is_paid']);
		if($is_paid>0){
			if($is_paid==1){
				$condition .= " and do.order_status=3 ";
			}elseif($is_paid==2){
				$condition .= " and do.order_status=0 ";
			}
			$parameter['is_paid']=$is_paid;
		}
		$GLOBALS['tmpl']->assign('is_paid',$is_paid);
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter["more_search"]=$more_search;
		
		$deal_name=strim($_REQUEST['deal_name']);
		if(!empty($deal_name)){
			$condition .= " and de.name like '%$deal_name%' ";
			$parameter["deal_name"]=$deal_name;
			$GLOBALS['tmpl']->assign('deal_name',$deal_name);
		}
		
		$deal_status=intval($_REQUEST['deal_status']);
		if($deal_status>0){
			switch($deal_status){
				//进行中
				case 1:
				$condition .=" and de.begin_time<".NOW_TIME." and de.end_time>".NOW_TIME." ";
				break;
				//已成功
				case 2:
				$condition .= " and de.is_success=1 and de.end_time<".NOW_TIME." ";
				break;
				//已失败
				case 3:
				$condition .= " and de.is_success=0 and de.end_time<".NOW_TIME." ";
				break;
			}
			$parameter["deal_status"]=$deal_status;
			$GLOBALS['tmpl']->assign('deal_status',$deal_status);
		}
		
		$item_status=intval($_REQUEST['item_status']);
		if($item_status>0){
			switch($item_status){
				//进行中
				case 1:
				$condition .=" and do.repay_time=0 and do.order_status=3 and do.is_success=1";
				break;
				//已成功
				case 2:
				$condition .= " and do.repay_time>0 and repay_make_time=0 and do.is_success=1";
				break;
				//已失败
				case 3:
				$condition .= " and do.repay_time>0 and repay_make_time>0 and do.is_success=1";
				break;
			}
			$parameter["item_status"]=$item_status;
			$GLOBALS['tmpl']->assign('item_status',$item_status);
		}
		
		$pay_begin_time=strim($_REQUEST['pay_begin_time']);
 		if($pay_begin_time!=0){
 			$pay_begin_time=to_timespan($pay_begin_time,'Y-m-d');
 			$condition.=" and do.create_time>=$pay_begin_time ";
 			$GLOBALS['tmpl']->assign('pay_begin_time',to_date($pay_begin_time,'Y-m-d'));
 			$parameter["pay_begin_time"]=to_date($pay_begin_time,'Y-m-d');
 		}
		
		$pay_end_time=strim($_REQUEST['pay_end_time']);
 		if($pay_end_time!=0){
 			$pay_end_time=to_timespan($pay_end_time,'Y-m-d');
 			$condition.=" and do.create_time<=$pay_end_time ";
 			$GLOBALS['tmpl']->assign('pay_end_time',to_date($pay_end_time,'Y-m-d'));
 			$parameter["pay_end_time"]=to_date($pay_end_time,'Y-m-d');
 		}
		
		$order_list = $GLOBALS['db']->getAll("select do.*,de.lottery_draw_time as deal_lottery_draw_time from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as de on de.id=do.deal_id where $condition and do.user_id = ".intval($GLOBALS['user_info']['id'])." and de.is_delete =0 and de.is_effect =1 order by do.create_time desc limit ".$limit);
   		foreach($order_list as $k=>$v){
			if($v['repay_make_time']==0&&$v['repay_time']>0){
				$left_date=intval(app_conf("REPAY_MAKE"))?7:intval(app_conf("REPAY_MAKE"));
				$repay_make_date=$v['repay_time']+$left_date*24*3600;
				if($repay_make_date<=get_gmtime()){
 					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$v['id'] );
					$order_list[$k]['repay_make_time']=get_gmtime();
				}
			}
		}
		$order_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as de on de.id=do.deal_id where $condition and do.user_id = ".intval($GLOBALS['user_info']['id'])." and de.is_delete =0 and de.is_effect =1");
 		$order_sum = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as de on de.id=do.deal_id where  $condition_sum and do.user_id = ".intval($GLOBALS['user_info']['id'])." and de.is_delete =0 and de.is_effect =1");
 		$GLOBALS['tmpl']->assign('order_sum',$order_sum );
 		
 		//$investor_list_count=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."investment_list as invest where type<>6 and user_id=  ".intval($GLOBALS['user_info']['id']));
  		//$GLOBALS['tmpl']->assign('investor_list_count',$investor_list_count);
  		
 
  		$page = new Page($order_count,$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("account#".$index_act."",$parameter);
		$GLOBALS['tmpl']->assign('pages',$p);
		$deal_ids=array();
		foreach($order_list as $k=>$v){
			$deal_ids[] =  $v['deal_id'];
		}
		if($deal_ids!=null){
			$deal_list_array=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where  is_effect = 1 and is_delete = 0 and id in (".implode(',',$deal_ids).")");
			$deal_list=array();
			foreach($deal_list_array as $k=>$v){
				if($v['id']){
					$deal_list[$v['id']]=$v;
				}
			}
	 		//unset($deal_list_array);
			foreach($order_list as $k=>$v)
			{
	//			$order_list[$k]['deal_info'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$v['deal_id']." and is_effect = 1 and is_delete = 0");
	 			$order_list[$k]['deal_info'] =$deal_list[$v['deal_id']];
			}
			 
			$GLOBALS['tmpl']->assign('order_list',$order_list);
		}
	
		$GLOBALS['tmpl']->assign('cur_url',url("account#".$index_act.""));
		$GLOBALS['tmpl']->assign('is_paid_1_url',url("account#".$index_act."",array('is_paid'=>1)));
		$GLOBALS['tmpl']->assign('is_paid_2_url',url("account#".$index_act."",array('is_paid'=>2)));
		$GLOBALS['tmpl']->assign('cur_act',$index_act);
		$GLOBALS['tmpl']->display("account_index.html");
	}
	
	
	//领投列表
	public function lead(){
		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));		
		$GLOBALS['tmpl']->assign("page_title","跟投的项目");
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$GLOBALS['tmpl']->display("account_lead.html");
	}
	//跟投列表
	public function vote(){
		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));		
		$GLOBALS['tmpl']->assign("page_title","跟投的项目");
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$GLOBALS['tmpl']->display("account_vote.html");
	}
	
	//关注的产品项目
	public function focus()
	{
		if( app_conf("INVEST_STATUS") == 2)
		{
			showErr("产品众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的产品项目");
		$this->focus_public(0,'focus');
	}
	//关注的房产项目
	public function focus_house()
	{
		if( app_conf("IS_HOUSE") ==0)
		{
			showErr("房产众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的房产项目");
		$this->focus_public(2,'focus_house');
	}
	//关注公益项目
	public function focus_selfless()
	{
		if( app_conf("IS_SELFLESS") ==0)
		{
			showErr("公益众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的公益项目");
		$this->focus_public(3,'focus_selfless');
	}
	//关注股权项目
	public function focus_investor()
	{
		if(app_conf("INVEST_STATUS") == 1)
		{
			showErr("股权众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的股权项目");
		$this->focus_public(1,'focus_investor');
	}
	//关注融资项目
	public function focus_finance()
	{
		if( app_conf("IS_FINANCE") ==0)
		{
			showErr("融资众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的融资项目");
		$this->focus_public(4,'focus_finance');
	}
	public function focus_public($focus_deal_type=0,$focus_act)
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));		
		
		$focus_deal_type=intval($focus_deal_type);
		if($focus_deal_type)
		{
			$_REQUEST['deal_type'] = $focus_deal_type;
		}
		$deal_type = intval($_REQUEST['deal_type']);
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$param=array();
		$s = intval($_REQUEST['s']);
		$param['s']=$s;
		if($s==3)
		$sort_field = " d.support_amount desc ";
		if($s==1)
		$sort_field = " d.support_count desc ";
		if($s==2)
		$sort_field = " d.support_amount - d.limit_price desc ";
		if($s==0)
		$sort_field = " d.end_time asc ";
		
		$GLOBALS['tmpl']->assign("s",$s);
		
		$f = intval($_REQUEST['f']);
		$param['f']=$f;
		if($f==0)
		$cond = " 1=1 ";
		if($f==1)
		$cond = " d.begin_time < ".NOW_TIME." and (d.end_time = 0 or d.end_time > ".NOW_TIME.") ";
		if($f==2)
		$cond = " d.end_time <> 0 and d.end_time < ".NOW_TIME." and d.is_success = 1 "; //过期成功
		if($f==3)
		$cond = " d.end_time <> 0 and d.end_time < ".NOW_TIME." and d.is_success = 0 "; //过期失败
		$GLOBALS['tmpl']->assign("f",$f);
		
		if($deal_type ==0)
		{
			$condition = " d.type=0 ";
		}
		elseif($deal_type ==1)
		{
			$condition = " d.type=1 ";
		}
		elseif($deal_type ==2)
		{
			$condition = " d.type=2 ";
		}elseif($deal_type ==3)
		{
			$condition = " d.type=3 ";
		}
		elseif($deal_type ==4)
		{
			$condition = " d.type=4 ";
		}
		else{
			$condition = " d.type=0 ";
		}
		
		$app_sql = " ".DB_PREFIX."deal_focus_log as dfl left join ".DB_PREFIX."deal as d on d.id = dfl.deal_id where $condition and dfl.user_id = ".intval($GLOBALS['user_info']['id']).
				   " and d.is_effect = 1 and d.is_delete = 0 and ".$cond." ";
		
		$deal_list = $GLOBALS['db']->getAll("select d.*,dfl.id as fid from ".$app_sql." order by ".$sort_field." limit ".$limit);
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".$app_sql);
		
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['support_amount']/$v['limit_price']*100,2);
			if($v['type']== 0){
				
				$deal_list[$k]['support_amount']= $deal_list[$k]['support_amount']+ $deal_list[$k]['virtual_price'];
				$deal_list[$k]['percent'] = round($deal_list[$k]['support_amount']/$v['limit_price']*100,2);
				$deal_list[$k]['support_count']= $deal_list[$k]['support_count']+ $deal_list[$k]['virtual_num'];
			}
			if($v['type']== 1){
				$deal_list[$k]['percent']= round($v['invote_money']/$v['limit_price']*100,2);
			}
		}
		
		$param['deal_type']=$deal_type;
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("account#".$focus_act."",$param);
		$GLOBALS['tmpl']->assign('pages',$p);

		$GLOBALS['tmpl']->assign('deal_list',$deal_list);
		
		$GLOBALS['tmpl']->assign('cur_url',url("account#".$focus_act.""));
		$GLOBALS['tmpl']->assign('status_0_url',url("account#".$focus_act."",array('s'=>$s,'f'=>0)));
		$GLOBALS['tmpl']->assign('status_1_url',url("account#".$focus_act."",array('s'=>$s,'f'=>1)));
		$GLOBALS['tmpl']->assign('status_2_url',url("account#".$focus_act."",array('s'=>$s,'f'=>2)));
		$GLOBALS['tmpl']->assign('status_3_url',url("account#".$focus_act."",array('s'=>$s,'f'=>3)));
		$GLOBALS['tmpl']->display("account_focus.html");
	}
	
	public function del_focus()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);
		$deal_id = $GLOBALS['db']->getOne("select deal_id from ".DB_PREFIX."deal_focus_log where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set focus_count = focus_count - 1 where id = ".intval($deal_id));
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_deal_notify where user_id = ".intval($GLOBALS['user_info']['id'])." and deal_id = ".$deal_id);
							
		app_redirect_preview();
	}
	
	public function incharge()
	{		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));		
		$GLOBALS['tmpl']->assign("page_title","充值");
		$GLOBALS['tmpl']->display("account_incharge.html");
	}
	
	public function do_incharge()
	{		
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		$money = floatval($_REQUEST['money']);
		if($money<=0)
		{
			showErr("充值的金额不正确",$ajax,"");
		}
		
		showSuccess("",$ajax,url("account#pay",array("money"=>round($money*100))));
	}
	
	public function pay()
	{		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$GLOBALS['tmpl']->assign("page_title","支付");
		$money = floatval(intval($_REQUEST['money'])/100);
		if($money<=0)
		{
			app_redirect(url("account#incharge"));
		}
		
		$GLOBALS['tmpl']->assign("money",$money);
		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay=1 order by sort asc ");
		$payment_html = "";
		foreach($payment_list as $k=>$v)
		{
			$class_name = $v['class_name']."_payment";
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
		}
		$GLOBALS['tmpl']->assign("payment_html",$payment_html);
		$GLOBALS['tmpl']->display("account_pay.html");
	}
	//冻结余额
	public function ye_mortgage_pay(){
		$ajax = intval($_REQUEST['ajax']);
		$type =	intval($_REQUEST['type']);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
 			if($paypassword==''){
				showErr("请输入支付密码",0);	
			}
			if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
				showErr("支付密码错误",0);	
			}
			
		}
		
		$deal_id=intval($_REQUEST['deal_id']);
		$money = floatval($_REQUEST['money']);
		if($GLOBALS['user_info']['money']>=$money){
			$re = set_mortgate($GLOBALS['user_info']['id'],$deal_id,$money);
			if($re){
				syn_mortgate($GLOBALS['user_info']['id']);
				//$type等于4表示是融资众筹
				if($type==4){
					showErr("冻结成功",0,url("finance#company_finance",array("id"=>$deal_id)));	
				}
				else{
					showErr("冻结成功",0,url("deal#show",array("id"=>$deal_id)));	
				}
				
				
			}else{
				showErr("冻结失败",0);	
			}
		}else{
			showErr("您的余额不够",0);	
		}
		
	}
	
	public function go_pay()
	{
		$ajax = intval($_REQUEST['ajax']);
		//print_r($_REQUEST);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$is_mortgate=intval($_REQUEST['is_mortgate']);
		if($is_mortgate==1&&app_conf("PAYPASS_STATUS")){
			$paypassword=strim($_REQUEST['paypassword']);
			if($paypassword==''){
				showErr("请输入支付密码",0);	
			}
			if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
				showErr("支付密码错误",0);	
			}
		}
		
		$is_tg=intval($_REQUEST['is_tg']);
		$deal_id=intval($_REQUEST['deal_id']);
		
		$pTrdAmt=floatval($_REQUEST['money']);
		if($is_tg){
			if($GLOBALS['is_user_tg']){
				$jump_url=APP_ROOT."/index.php?ctl=collocation&act=SincerityGoldFreeze&user_type=0&user_id=".$GLOBALS['user_info']['id']."&pTrdAmt=".$pTrdAmt."&deal_id=".$deal_id."&from=".'web';
				//showErr("您已经绑定第三方接口，正在支付诚意金,点击确定后跳转",0,$jump_url);
				//app_redirect(url("collocation#SincerityGoldFreeze",array("user_type"=>0,"user_id"=>$GLOBALS['user_info']['id'],"pTrdAmt"=>$pTrdAmt,"&deal_id"=>$deal_id)));
				app_redirect($jump_url);
			}else{
				$jump_url=APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id'];
				showErr("您未绑定第三方接口无法支付,点击确定后跳转到绑定页面",0,$jump_url);
			}
		}
		$payment_id = intval($_REQUEST['payment']);
		if($payment_id==0)
		{
			app_redirect(url("account#pay"));
		}
		
		$money = floatval($_REQUEST['money']);
		if($money<=0)
		{
			app_redirect(url("account#pay"));
		}
		 
		
		$payment_notice['create_time'] = NOW_TIME;
		$payment_notice['user_id'] = intval($GLOBALS['user_info']['id']);
		$payment_notice['payment_id'] = $payment_id;
		$payment_notice['money'] = $money;
		$payment_notice['bank_id'] = strim($_REQUEST['bank_id']);
		//$payment_notice['is_mortgate']=intval($_REQUEST['is_mortgate']);
		if(!empty($_REQUEST['is_mortgate'])){
			$payment_notice['is_mortgate']=intval($_REQUEST['is_mortgate']);
		}
		
		do{
			$payment_notice['notice_sn'] = to_date(NOW_TIME,"Ymd").rand(100,999);
			$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
			$notice_id = $GLOBALS['db']->insert_id();
		}while($notice_id==0);
		

		app_redirect(url("account#jump",array("id"=>$notice_id)));
		
	}
	
	public function jump()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$notice_id = intval($_REQUEST['id']);
		$notice_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id." and is_paid = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
		if(!$notice_info)
		{
			app_redirect(url("index"));
		}
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$notice_info['payment_id']);
		$class_name = $payment_info['class_name']."_payment";
		
		require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
		$o = new $class_name;
		header("Content-Type:text/html; charset=utf-8");
		echo $o->get_payment_code($notice_id);
	}
	
	//我的项目列表
	//产品项目列表
	public function project()
	{
		if(app_conf("INVEST_STATUS")==2)
		{
			showErr("产品众筹已经关闭");
		}
		
		$GLOBALS['tmpl']->assign("page_title","我的产品项目列表");
		$this->project_public(0,'project');
	}
	//房产项目列表
	public function project_house()
	{
		if(app_conf("IS_HOUSE")==0)//
		{
			showErr("房产众筹已经关闭");
		}
		
		$GLOBALS['tmpl']->assign("page_title","我的产品项目列表");
		$this->project_public(2,'project_house');
	}
	//公益项目列表
	public function project_selfless()
	{
		if(app_conf("IS_SELFLESS")==0)
		{
			showErr("公益众筹已经关闭");
		}
		
		$GLOBALS['tmpl']->assign("page_title","我的公益项目列表");
		$this->project_public(3,'project_selfless');
	}
	
	public function project_public($project_deal_type=0,$project_act='project')
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));	

		$project_deal_type=intval($project_deal_type);
		if($project_deal_type)
		{
			$_REQUEST['deal_type'] = $project_deal_type;
		}
		$deal_type = intval($_REQUEST['deal_type']);

		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
	    $condition = " type = ".$deal_type." ";
		
		$parameter=array();
		$parameter["deal_type"]=$deal_type;
  		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter["more_search"]=$more_search;
		
		$deal_name=strim($_REQUEST['deal_name']);
		if(!empty($deal_name)){
			$condition .= " and  name like '%$deal_name%' ";
			$parameter["deal_name"]=$deal_name;
			$GLOBALS['tmpl']->assign('deal_name',$deal_name);
		}
		
		$deal_status=intval($_REQUEST['deal_status']);
		if($deal_status>0){
			switch($deal_status){
				//待审核
				case 1:
				$condition .=" and  is_effect=0 and is_edit =0";
				break;
				//进行中
				case 2:
				$condition .=" and is_effect=1 and begin_time<".NOW_TIME." and  end_time>".NOW_TIME." ";
				break;
				//已成功
				case 3:
				$condition .= " and is_success=1 and is_effect=1 and  end_time<".NOW_TIME." ";
				break;
				//已失败
				case 4:
				$condition .= " and  is_success=0 and is_effect=1 and  end_time<".NOW_TIME." ";
				break;
				//未通过
				case 5:
				$condition .=" and  is_effect=2 ";
				break;
				//预热中
				case 6:
				$condition .=" and  is_effect=1 and is_success=0 and begin_time >".NOW_TIME."";
				break;
			}
			$parameter["deal_status"]=$deal_status;
			$GLOBALS['tmpl']->assign('deal_status',$deal_status);
		}
		
		$give_status=intval($_REQUEST['give_status']);
		if($give_status>0){
			if($deal_status==3){
				switch($give_status){
					//已发放
					case 1:
					$condition .=" and  left_money=0 ";
					break;
					//未发放
					case 2:
					$condition .=" and  left_money>0 ";
					break;
				}
			}
			else
			{
				switch($give_status){
					//已发放
					case 1:
					$condition .=" and  left_money=0  and  is_success= 1";
					break;
					//未发放
					case 2:
					$condition .=" and  left_money>0 and  is_success= 1";
					break;
				}
			}
			$parameter["give_status"]=$give_status;
			$GLOBALS['tmpl']->assign('give_status',$give_status);
		}
		
		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and  create_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter["begin_time"]=to_date($begin_time,'Y-m-d');
 		}
		
		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and create_time<=$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
 			$parameter["end_time"]=to_date($end_time,'Y-m-d');
 		}
		
		$deal_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where $condition and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0 order by id desc,create_time desc limit ".$limit);
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where $condition and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0");
		
		$deal_cp_sum = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where type = ".$deal_type." and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0");
		$GLOBALS['tmpl']->assign('deal_cp_sum',$deal_cp_sum);
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['support_amount']/$v['limit_price']*100,2);
			if($v['type']== 0){
				$deal_list[$k]['support_count']= $deal_list[$k]['support_count']+ $deal_list[$k]['virtual_num'];
			}
			$is_lottery=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item where deal_id=".$v['id']." and type=2");
			$deal_list[$k]['is_lottery']=$is_lottery>0?1:0;
		}

		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("account#".$project_act."",$parameter);
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('deal_list',$deal_list);
		
		$GLOBALS['tmpl']->assign('cur_url',url("account#".$project_act.""));
		$GLOBALS['tmpl']->assign('deal_status_1_url',url("account#".$project_act."",array("deal_status"=>1)));
		$GLOBALS['tmpl']->assign('deal_status_2_url',url("account#".$project_act."",array("deal_status"=>2)));
		$GLOBALS['tmpl']->assign('deal_status_3_url',url("account#".$project_act."",array("deal_status"=>3)));
		$GLOBALS['tmpl']->assign('deal_status_4_url',url("account#".$project_act."",array("deal_status"=>4)));
		$GLOBALS['tmpl']->assign('deal_status_5_url',url("account#".$project_act."",array("deal_status"=>5)));
		$GLOBALS['tmpl']->assign('deal_status_6_url',url("account#".$project_act."",array("deal_status"=>6)));
		
		$GLOBALS['tmpl']->display("account_project.html");
	}
	
	//创建的股权项目
	public function project_invest(){
		if (app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$GLOBALS['tmpl']->assign("page_title","我的股权项目列表");
		$this->project_invest_public(1,"project_invest");
	}
	//创建的股权项目
	public function project_finance(){
		if(app_conf("IS_FINANCE")==0)
		{
			showErr("融资已经关闭");
		}
		$GLOBALS['tmpl']->assign("page_title","我的融资项目列表");
		$this->project_invest_public(4,"project_finance");
	}
	public function project_invest_public($project_invest_type,$project_invest_act){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));	

		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$deal_type=intval($project_invest_type);
		if($deal_type){
			$condition .=" type=".$deal_type;
			$GLOBALS['tmpl']->assign('deal_type',$deal_type);
			$parameter["deal_type"]=$deal_type;
		}else{
			$condition = " type=1 ";	
		}
		
	    
  		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter['more_search']=$more_search;
		
		$deal_name=strim($_REQUEST['deal_name']);
		if(!empty($deal_name)){
			$condition .= " and  name like '%$deal_name%' ";
			$parameter["deal_name"]=$deal_name;
			$GLOBALS['tmpl']->assign('deal_name',$deal_name);
		}
		$deal_viwe=intval($_REQUEST['deal_viwe']);
		$GLOBALS['tmpl']->assign('deal_viwe',$deal_viwe);
		$deal_status=intval($_REQUEST['deal_status']);
		if($deal_status>0){
			switch($deal_status){
				//待审核
				case 1:
				$condition .=" and  is_effect=0 ";
				break;
				//预热中
				case 2:
				$condition .=" and  begin_time>".NOW_TIME." and  end_time>".NOW_TIME."  and  is_effect=1 ";
				break;
				//进行中
				case 3:
				$condition .= " and  begin_time<".NOW_TIME." and  end_time>".NOW_TIME." ";
				break;
				//认投成功
				case 4:
				$condition .= " and  begin_time<".NOW_TIME." and  end_time>".NOW_TIME." and is_success=1 ";
				break;
				//融资成功
				case 5:
				$condition .=" and  end_time<".NOW_TIME." and is_success=1 and invest_status=1 ";
				break;
				//认投失败
				case 6:
				$condition .=" and  end_time<".NOW_TIME." and is_success=0 and invest_status=0 ";
				break;
				//融资失败
				case 7:
				$condition .=" and  end_time<".NOW_TIME." and is_success=0 and invest_status=2 ";
				break;
				//未通过
				case 8:
				$condition .=" and is_effect=2 ";
				break;
			}
			$parameter["deal_status"]=$deal_status;
			$GLOBALS['tmpl']->assign('deal_status',$deal_status);
		}

		$give_status=intval($_REQUEST['give_status']);
		if($give_status>0){
			switch($give_status){
				//已发放
				case 1:
				$condition .=" and  left_money=0 ";
				break;
				//未发放
				case 2:
				$condition .=" and  left_money>0 ";
				break;
			}
			$parameter["give_status"]=$give_status;
			$GLOBALS['tmpl']->assign('give_status',$give_status);
		}
		
		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and  create_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter["begin_time"]=to_date($begin_time,'Y-m-d');
 		}
		
		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and do.create_time<=$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
 			$parameter["begin_time"]=to_date($end_time,'Y-m-d');
 		}
 		$type=intval($_REQUEST['type']);
 		$company_id=intval($_REQUEST['company_id']);
 		if($type && $company_id){
 			$condition.=" and type = $type and company_id = $company_id";
 			$parameter["company_id"]=$company_id;
 		}
 		
		$deal_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where $condition and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0 order by id desc,create_time desc limit ".$limit);
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where $condition and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0");
		
		$deal_gq_sum = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where type =".intval($deal_type)." and user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0");
		$GLOBALS['tmpl']->assign('deal_gq_sum',$deal_gq_sum);
		

		foreach($deal_list as $k=>$v)
		{
			
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['invote_money']/$v['limit_price']*100,2);
			if($v['type']== 0){
				$deal_list[$k]['support_count']= $deal_list[$k]['support_count']+ $deal_list[$k]['virtual_num'];
			}
		}

		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("account#".$project_invest_act."",$parameter);
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('deal_list',$deal_list);
		
		$GLOBALS['tmpl']->assign('project_invest_act',$project_invest_act);
		$GLOBALS['tmpl']->assign('cur_url',url("account#".$project_invest_act.""));
		$GLOBALS['tmpl']->assign('deal_viwe_0_url',url("account#".$project_invest_act."",array("deal_viwe"=>0,"deal_status"=>0)));
		$GLOBALS['tmpl']->assign('deal_viwe_1_url',url("account#".$project_invest_act."",array("deal_viwe"=>1,"deal_status"=>1)));
		$GLOBALS['tmpl']->assign('deal_viwe_2_url',url("account#".$project_invest_act."",array("deal_viwe"=>2,"deal_status"=>2)));
		$GLOBALS['tmpl']->assign('deal_viwe_3_url',url("account#".$project_invest_act."",array("deal_viwe"=>3,"deal_status"=>3)));
		$GLOBALS['tmpl']->assign('deal_viwe_4_url',url("account#".$project_invest_act."",array("deal_viwe"=>4,"deal_status"=>4)));
		$GLOBALS['tmpl']->assign('deal_viwe_5_url',url("account#".$project_invest_act."",array("deal_viwe"=>5,"deal_status"=>5)));
		$GLOBALS['tmpl']->assign('deal_viwe_8_url',url("account#".$project_invest_act."",array("deal_viwe"=>8,"deal_status"=>8)));
		$GLOBALS['tmpl']->display("account_project_invest.html");
	}
	public function credit()
	{
       
		$GLOBALS['tmpl']->assign("page_title","收支明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and money <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and money>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and money>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and money<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and money<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		$type=intval($_REQUEST['type']);
 		if($type>0){
 			switch($type){
 				//我的收入
 				case 1:
 				$condition.=" and type=0 and money>0 ";
 				break;
 				//我的支出
 				case 2:
  				$condition.=" and type=0 and money<0 ";
 				break;
 				//我的提现
 				case 3:
 				$condition.=" and type=4  ";
 				break;
 				 
 			}
 			$GLOBALS['tmpl']->assign('type',$type);
 			
  			$parameter[]="type=".$type;
 		}
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])."   $condition order by log_time desc,id desc limit ".$limit);
 		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])."  $condition ");
 		 
 		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($log_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$GLOBALS['tmpl']->display("account_credit.html");
	}
	
		public function score()
	{
        
		$GLOBALS['tmpl']->assign("page_title","积分明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])." and score <>0  order by log_time desc,id desc limit ".$limit);
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])." and score <>0");
	
		$page = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$GLOBALS['tmpl']->assign('total_score',$GLOBALS['user_info']['score']);
		$GLOBALS['tmpl']->display("account_score.html");
	}
	
		public function point()
	{
        
		$GLOBALS['tmpl']->assign("page_title","信用明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])." and point <>0  order by log_time desc,id desc limit ".$limit);
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".intval($GLOBALS['user_info']['id'])." and point <>0");
	
		$page = new Page($log_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		$GLOBALS['tmpl']->assign('total_point',$GLOBALS['user_info']['point']);
		$GLOBALS['tmpl']->display("account_point.html");
	}
	
	public function del_order()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		$order_id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_status = 0 and user_id = ".intval($GLOBALS['user_info']['id'])." and id = ".$order_id);
		if(!$order_info)
		{
			showErr("无效的订单",$ajax,"");
		}
		else
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where id = ".$order_id." and user_id = ".intval($GLOBALS['user_info']['id'])." and order_status = 0");
			showSuccess("",$ajax,get_gopreview());
		}
	}
	
	//支持的项目详情
	public function view_order()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$GLOBALS['tmpl']->assign("page_title","支持的项目详情");
		$id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select deo.*,d.transfer_share as transfer_share,d.limit_price as limit_price ,deo.type as dtype,d.type as deal_type from ".DB_PREFIX."deal_order as deo LEFT JOIN ".DB_PREFIX."deal as d on deo.deal_id = d.id  where deo.id = ".$id." and deo.user_id = ".intval($GLOBALS['user_info']['id']));
		if($order_info['type'] == 0){
			$order_info['is_delivery']= $GLOBALS['db']->getOne("select is_delivery from ".DB_PREFIX."deal_item where id =".$order_info['deal_item_id']);
		}		
		if(!$order_info)
		{
			showErr("无效的项目支持",0,get_gopreview());
		}
		//========如果超过系统设置的时间，则自动设置收到回报 start
		if($order_info['repay_make_time']==0 && $order_info['repay_time']>0){
			$item=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id=".$order_info['deal_item_id']);
			$item_day=intval($item['repaid_day']);
			if($item_day>0){
				$left_date=$item_day;
			}else{
				$left_date=intval(app_conf("REPAY_MAKE"));
			}
				
			$repay_make_date=$order_info['repay_time']+$left_date*24*3600;
			
			if($repay_make_date>get_gmtime()&&$order_info['repay_time']>0){
				$order_info['repay_make_date']=date('Y-m-d H:i:s',$repay_make_date);
				$order_info['repay_left_time'] = $repay_make_date - get_gmtime();
			}else{
 				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$id);
				$order_info['repay_make_time']=get_gmtime();
			}
		}
		if($order_info['type']==1 || $order_info['type']==4){
			

			if($order_info['dtype']==6){
					
				$stock_transfer=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer  where id=".$order_info['deal_item_id']);
					
				//用户所占股份
				$order_info['user_stock']= number_format(($stock_transfer['stock_value']/$order_info['limit_price'])*$order_info['transfer_share'],2);
				//项目金额
				$order_info['stock_value'] =number_format($stock_transfer['stock_value'],2);
					
			
			}else{
			//用户所占股份
			$order_info['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);			
			//项目金额
			$order_info['stock_value'] =number_format($order_info['limit_price'],2);
			//应付金额
			//$order_info['total_price'] =number_format($order_info['total_price'],2);
				
			}
		}

		
		
		//抽奖订单
		if($order_info['type'] ==3)
		{
			$lottery_return=get_order_lottery($id);
			$order_info['lottery_list']=$lottery_return['lottery_list'];
			$order_info['lottery_luckyer_list']=$lottery_return['lottery_luckyer_list'];
		}
		
		//=============如果超过系统设置的时间，则自动设置收到回报 end
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$order_info['deal_id']." and is_delete = 0 and is_effect = 1");
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		
		if($order_info['order_status'] == 0)
		{
			$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay in (0,1) order by sort asc ");
			$payment_html = "";
			foreach($payment_list as $k=>$v)
			{
				$class_name = $v['class_name']."_payment";
				require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
				$o = new $class_name;
				$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
			}
			$GLOBALS['tmpl']->assign("payment_html",$payment_html);
			
			$max_pay = $order_info['total_price'];
			$GLOBALS['tmpl']->assign("max_pay",$max_pay);
			
			$order_sm=array('credit_pay'=>0,'score'=>0,'score_money'=>0);
			if($order_info['credit_pay']>0)
			{
				$order_sm['credit_pay']=$order_info['credit_pay'] <= $GLOBALS['user_info']['money']?$order_info['credit_pay']:$GLOBALS['user_info']['money'];
			}
			if($order_info['score'] >0)
			{
				
				if($order_info['score'] <= $GLOBALS['user_info']['score'])
				{
					$order_sm['score']=$order_info['score'];
					$order_sm['score_money']=$order_info['score_money'];
				}else
				{
					$order_sm['score']=$GLOBALS['user_info']['score'];
					$score_array=score_to_money($GLOBALS['user_info']['score']);
					$order_sm['score_money']=$score_array['score_money'];
					$order_sm['score']=$score_array['score'];
				}
			}
			$GLOBALS['tmpl']->assign("order_sm",json_encode($order_sm));
		}
		else{
			$order_sm=array('credit_pay'=>0,'score'=>0,'score_money'=>0);
			$GLOBALS['tmpl']->assign("order_sm",json_encode($order_sm));
		}
		
		$offline_pay=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Offlinepay'");
		$GLOBALS['tmpl']->assign("offline_pay",$offline_pay);
		
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		//print_r($order_info);exit;
		
		$GLOBALS['tmpl']->assign("coll",is_tg(true));
		
		$GLOBALS['tmpl']->assign("order_deal_type",$deal_info['type']);
		$GLOBALS['tmpl']->display("account_view_order.html");
	}
	
	public function go_order_pay()
	{
		
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		
		$id = intval($_REQUEST['order_id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id'])." and order_status = 0");
		$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
			if($paypassword==''){
				showErr("请输入支付密码",0);	
			}
			if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
				showErr("支付密码错误",0);	
			}
		}
		if(!$order_info)
		{
			showErr("项目支持已支付",0,get_gopreview());
		}
		
		//抽奖
		if($order_info['type']==3)
		{
			$deal_item=$GLOBALS['db']->getRow("select di.*,d.lottery_draw_time from ".DB_PREFIX."deal_item as di left join ".DB_PREFIX."deal as d on d.id =di.deal_id where di.id= ".intval($order_info['deal_item_id'])." and di.deal_id=".intval($order_info['deal_id'])." and di.type=2");
			if(!$deal_item)
				showErr("抽奖支持未找到",0);
				
			if($deal_item['lottery_draw_time'] >0)
			{
				showErr("项目幸运号已揭晓",0);
			}
				
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".intval($order_info['deal_item_id'])." and type=3 and order_status=3 and is_refund=0 and id<>".$id."");
				if($buy_num >=$deal_item['maxbuy'])
					showErr("你的支持数已达到限",0);
					
				$all_buy_num=$buy_num+$order_info['num'];
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				
				if($deal_item['limit_user'] >0)
				{
					if($deal_item['support_count'] >= $deal_item['limit_user'])
						showErr("库存不足",0);
					
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_maxbuy=$remain_maxbuy>$remain_count?$remain_count:$remain_maxbuy;
				}
				
				if($order_info['num'] >$remain_maxbuy)
					showErr("请重新下单，您最多支持数为".$remain_maxbuy.",你本单支持为".$order_info['num']."",0);
				
			}
		}
		
		

		$is_tg=intval($_REQUEST['is_tg']);
		if($is_tg){
			if(!$GLOBALS['is_user_tg']){
				$jump_url=APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id'];
				showErr("您未绑定第三方接口无法支付,点击确定后跳转到绑定页面",0,$jump_url);
			}elseif($order_info){
				$sign=md5(md5($paypassword).$order_info['id']);
				$url=APP_ROOT."/index.php?ctl=collocation&act=RegisterCreditor&order_id=".$order_info['id']."&sign=".$sign;
				//showSuccess("",0,$url);
				app_redirect($url);
			}
		}
		else
		{
			$credit = floatval($_REQUEST['credit']);
			$payment_id = intval($_REQUEST['payment']);
			$pay_score =intval($_REQUEST['pay_score']);
			$score_trade_number=intval(app_conf("SCORE_TRADE_NUMBER"))>0?intval(app_conf("SCORE_TRADE_NUMBER")):0;
			$pay_score_money=intval($pay_score/$score_trade_number*100)/100;
			
			/*余额支付金额先不扣，只写入订单
			if($credit>0)
			{				
				$max_pay = $order_info['total_price'] - $order_info['credit_pay'];
				$max_credit= $max_pay<$GLOBALS['user_info']['money']?$max_pay:$GLOBALS['user_info']['money'];
				if($max_credit<0){
					$max_credit=0;
				}
				$credit = $credit>$max_credit?$max_credit:$credit;		
			
				if($credit>0)
				{
	 				require_once APP_ROOT_PATH."system/libs/user.php";
					$re=modify_account(array("money"=>"-".$credit),intval($GLOBALS['user_info']['id']),"支持".$order_info['deal_name']."项目支付");		
 					if($re){
 						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set credit_pay = credit_pay + ".$credit." where id = ".$order_info['id']);//追加使用余额支付
	 				}
				}
			}
			*/
			
			if(!$is_tg)
			{
				
				$credit_score_money=$pay_score_money + $credit;
				if($credit> $GLOBALS['user_info']['money'])
					showErr("余额最多只能用".format_price($GLOBALS['user_info']['money']),0);
				if($pay_score > $GLOBALS['user_info']['score'])
					showErr("积分最多只能用".$GLOBALS['user_info']['score']);
				if( $credit_score_money > $order_info['total_price'])
					showErr("支付超出");
				if( intval(($order_info['total_price'] - $credit_score_money)*100) > 0 && $payment_id ==0)
					showErr("请选择支付方式");
				if( $payment_id >0)
				{
					$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$payment_id);
					if(!$payment_info)
						showErr("不支持该支付方式");
				}	
					
			}
			
			if($credit>0){
				$order_data['credit_pay'] = $credit;
			}else
			{
				$order_data['credit_pay'] = 0;
			}
			
			
			if($pay_score>0)
			{
				$order_data['score'] = $pay_score;
				$order_data['score_money'] = $pay_score_money;
			}
			else
			{
				$order_data['score'] = 0;
				$order_data['score_money'] = 0;
			}
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set credit_pay = ".$order_data['credit_pay'].",score=".$order_data['score'].",score_money=".$order_data['score_money']."  ,payment_id =".$payment_id." where id = ".intval($order_info['id'])." ");
			
			$result = pay_order($order_info['id']);
 			if($result['status']==0)
			{
				if($payment_info['online_pay'] ==0)
				{
					app_redirect(url("cart#onlinepay",array("id"=>$id)));
				}else
				{
					$money = $result['money'];
					$payment_notice['create_time'] = NOW_TIME;
					$payment_notice['user_id'] = intval($GLOBALS['user_info']['id']);
					$payment_notice['payment_id'] = $payment_id;
					$payment_notice['money'] = $money;
					$payment_notice['bank_id'] = strim($_REQUEST['bank_id']);
					$payment_notice['order_id'] = $order_info['id'];
					$payment_notice['memo'] = $order_info['support_memo'];
					$payment_notice['deal_id'] = $order_info['deal_id'];
					$payment_notice['deal_item_id'] = $order_info['deal_item_id'];
					$payment_notice['deal_name'] = $order_info['deal_name'];
					
					do{
						$payment_notice['notice_sn'] = to_date(NOW_TIME,"Ymd").rand(100,999);
						$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
						$notice_id = $GLOBALS['db']->insert_id();
					}while($notice_id==0);
					
					app_redirect(url("cart#jump",array("id"=>$notice_id)));
				}
			}
			else
			{
				
				if ($result['status']==3 && $order_info['type']==6){
					//股权转让 支付成功
					
					$stock_transfer_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where id = ".$order_info['deal_item_id']);
										
					//更新$investment_list 修改 为付款后依旧是锁定状态					
					/*$investment_list['type'] = 5;					
					$investment_list['user_id'] =$GLOBALS['user_info']['id'];*/
					$investment_list['order_id'] = $order_info['id'];
					$investment_list['investor_money_status'] = 3;
					
					$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list,"UPDATE","id=".$stock_transfer_info['invest_id_2']);
					
					
					$stock_transfer_info['purchaser_id'] = $GLOBALS['user_info']['id'];
					$stock_transfer_info['purchaser_name'] = $GLOBALS['user_info']['user_name'];
					$stock_transfer_info['deal_order_id'] =  $order_info['id'];									
					$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$stock_transfer_info,"UPDATE","id=".$order_info['deal_item_id']);
					
					//同步项目状态
					syn_deal($stock_transfer_info['deal_id']);
					app_redirect(url("account#stock_transfer_in"));
					//app_redirect(url("account#stock_transfer_view_order",array("id"=>$order_info['deal_item_id'])));
				}
				app_redirect(url("account#view_order",array("id"=>$order_info['id'])));  
			}
		}	
		
	}
	//我的项目-支持列表
	public function support()
	{	
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['tmpl']->assign("page_title","我的项目-支持列表");
		
		$deal_id = intval($_REQUEST['id']);
		$type = intval($_REQUEST['type']);
		$user_name = strim($_REQUEST['user_name']);
		$mobile = strim($_REQUEST['mobile']);
		$repay_status = intval($_REQUEST['repay_status']);
		
		$parameter=array();
		$parameter[]="type=".$type;
		$parameter[]="id=".$deal_id;
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		
		if(!$deal_info)
		{
			app_redirect_preview();
		}
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		$where='';
		if($user_name !='')
		{
			$parameter[]="user_name=".$user_name;
			$where .=" and d.user_name like '%".$user_name."%'";
		}
		if($mobile !='')
		{
			$parameter[]="mobile=".$mobile;
			$where .=" and d.mobile =".$mobile."";
		}
		if($repay_status >0)
		{
			$parameter[]="repay_status=".$repay_status;
			switch($repay_status){
  			case 1:
  			$where .= " and d.repay_time >0";
  			break;
  			case 2:
  			$where .= " and d.repay_time =0";
  			break;
  			case 3:
  			$where .= " and d.repay_time >0 and d.repay_make_time >0";
  			break;
  			}
		}
		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		if($more_search){
 			$parameter[]="more_search=".$more_search;
 		}
		
		$pay_begin_time=strim($_REQUEST['begin_time']);
 		if($pay_begin_time!=0){
 			$pay_begin_time=to_timespan($pay_begin_time,'Y-m-d');
 			$where.=" and d.create_time>=$pay_begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($pay_begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($pay_begin_time,'Y-m-d');
 		}
		
		$pay_end_time=strim($_REQUEST['end_time']);
 		if($pay_end_time!=0){
 			$pay_end_time=to_timespan($pay_end_time,'Y-m-d');
 			$where.=" and d.create_time<=$pay_end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($pay_end_time,'Y-m-d'));
 			$parameter[]="end_time=".to_date($pay_end_time,'Y-m-d');
 		}
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size;
		if($type ==1)
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 ".$where." order by d.create_time desc limit ".$limit);
		else
			$support_list = $GLOBALS['db']->getAll("select d.*,di.description as item_description , di.is_delivery as is_delivery from ".DB_PREFIX."deal_order as d left join ".DB_PREFIX."deal_item as di on di.id=d.deal_item_id where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.deal_item_id >0 ".$where." order by d.create_time desc limit ".$limit);
		
		$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$deal_id." and order_status = 3 and is_refund = 0 ".$where." ");
		foreach($support_list as $k=>$v){
			if($deal_info['type']==1 || $deal_info['type']==4){
				//项目金额
				$support_list[$k]['stock_value'] =number_format($deal_info['limit_price'],2);
				$support_list[$k]['money'] =$v['total_price'];
				//用户所占股份
 				$support_list[$k]['user_stock']= number_format(($support_list[$k]['money']/$deal_info['limit_price'])*$deal_info['transfer_share'],2);			
				
				if($v['invest_type'] ==0)
					$invest_type_val='询价';
				elseif($v['invest_type'] ==1)
					$invest_type_val='领投';
				elseif($v['invest_type'] ==2)
					$invest_type_val='跟投';
				elseif($v['invest_type'] ==2)
					$invest_type_val='追加';
					
				$support_list[$k]['invest_type_val']=$invest_type_val;
				
			}
		}
		$GLOBALS['tmpl']->assign("support_list",$support_list);
		$parameter_str="&".implode("&",$parameter);
		$page = new Page($support_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->new_para_show("account#support",array('id'=>$deal_id));
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		$GLOBALS['tmpl']->assign('deal_id',$deal_id);
		$GLOBALS['tmpl']->assign('project_deal_type',$deal_info['type']);
		$GLOBALS['tmpl']->assign('type',$type);
		$GLOBALS['tmpl']->assign('user_name',$user_name);
		$GLOBALS['tmpl']->assign('mobile',$mobile);
		$GLOBALS['tmpl']->assign('repay_status',$repay_status);
		$GLOBALS['tmpl']->display("account_support.html");
	}
	
	public function set_repay(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['tmpl']->assign("page_title","我的项目-支持列表");
		$order_id= intval($_REQUEST['id']);
	
		
		if($_REQUEST['type'] ==1 || $_REQUEST['type'] ==4)
			$order_info = $GLOBALS['db']->getRow("select d.*,i.stock_value as investment_stock_value,dl.transfer_share as transfer_share,dl.limit_price as limit_price,dl.type as deal_type from ".DB_PREFIX."deal_order as d left join (".DB_PREFIX."investment_list as i,".DB_PREFIX."deal as dl) on (i.id = d.invest_id and d.deal_id = dl.id)  where d.id = ".$order_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");
		else
			$order_info = $GLOBALS['db']->getRow("select d.*,di.description as item_description , di.is_delivery as is_delivery,deal.type as deal_type from ".DB_PREFIX."deal_order as d left join ".DB_PREFIX."deal_item as di on di.id=d.deal_item_id left join ".DB_PREFIX."deal as deal on deal.id =d.deal_id where d.id = ".$order_id." and d.order_status = 3 and d.is_refund = 0 and d.deal_item_id >0 order by d.create_time desc");
	
		if($order_info['type']==1 || $order_info['type']==5){
			//用户所占股份
			$order_info['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);			
			//项目金额
			$order_info['stock_value'] =number_format($order_info['limit_price']/10000,2);
			//应付金额
			$order_info['total_price'] =number_format($order_info['total_price']/10000,2);
		}
		
		//抽奖订单
		if($order_info['type'] ==3)
		{
			$lottery_return=get_order_lottery($order_id);
			$order_info['lottery_list']=$lottery_return['lottery_list'];
			$order_info['lottery_luckyer_list']=$lottery_return['lottery_luckyer_list'];
		}
		
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		$GLOBALS['tmpl']->assign("project_deal_type",$order_info['deal_type']);
		$GLOBALS['tmpl']->display("account_set_repay.html");
	}
	public function save_repay()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$order_id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id." and order_status = 3 and is_refund = 0");
		if(!$order_info)
		{
			showErr("无权为该订单设置回报",$ajax);
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$order_info['deal_id']." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		if(!$deal_info)
		{
			showErr("无权为该订单设置回报",$ajax);
		}
		
		$order_info['repay_time'] = NOW_TIME;
		$order_info['repay_memo'] = strim($_REQUEST['repay_memo']);
		
		if($order_info['repay_memo']=="")
		{
			showErr("请输入回报内容",$ajax);
		}
		$order_info['logistics_company'] = strim($_REQUEST['logistics_company']);
		$order_info['logistics_links'] = strim($_REQUEST['logistics_links']);
		$order_info['logistics_number'] = strim($_REQUEST['logistics_number']);
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info,"UPDATE","id=".$order_info['id']);
		if($GLOBALS['db']->affected_rows()>0)
		{
			if($order_info['share_fee']>0 && $order_info['share_status'] ==0 )
			{
				require_once APP_ROOT_PATH."system/libs/user.php";
				$add_param=array("type"=>3,"deal_id"=>$order_info['deal_id']);
				if(!intval($deal_info['ips_bill_no']))//项目类型是网站支付，分红金额才打给购买会员，是第三方托管支付不打给会员
					modify_account(array("money"=>$order_info['share_fee']),intval($order_info['user_id']),$order_info['deal_name']."项目成功，(订单:".$order_info['id'].")回报所得分红。",$add_param);						
				
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set share_status=1 where id=".intval($order_info['id'])." and share_status=0");
			}
			//send_notify($order_info['user_id'],"您支持的项目".$order_info['deal_name']."回报已发放","account#view_order","id=".$order_info['id']);
			$param = array(
				'content'=>"您支持的项目".$order_info['deal_name']."回报已发放",
				'url_route'=>"account#view_order",
				'url_param'=>"id=".$order_info['id']
			);
			$GLOBALS['msg']->manage_msg('notify',$order_info['user_id'],$param);
			if($order_info['type']==1 || $order_info['type']==5){
				showSuccess("回报设置成功",$ajax,url("account#support",array("id"=>$order_info['deal_id'],"type"=>1)));
			}
			else{
				showSuccess("回报设置成功",$ajax,url("account#support",array("id"=>$order_info['deal_id'])));
			}
		}else
		{
			showErr("回报设置失败",$ajax);
		}
		
		
	}
	//我的项目
	public function paid()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		
		$deal_id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		
		if(!$deal_info)
		{
			app_redirect_preview();
		}
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		$paid_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_pay_log where deal_id = ".$deal_id." order by create_time desc limit ".$limit);
		$paid_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_pay_log where deal_id = ".$deal_id);
		
		
		$GLOBALS['tmpl']->assign("paid_list",$paid_list);

		$page = new Page($paid_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign("project_deal_type",$deal_info['type']);
		$GLOBALS['tmpl']->display("account_paid.html");
	}
	//提现记录列表
	public function refund()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}

		$GLOBALS['tmpl']->assign("page_title","提现记录列表");
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		$refund_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_refund where user_id = ".intval($GLOBALS['user_info']['id'])." order by create_time desc limit ".$limit);
		$refund_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_refund where user_id = ".intval($GLOBALS['user_info']['id']));
	
		
		$GLOBALS['tmpl']->assign("refund_list",$refund_list);

		$page = new Page($refund_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$bank_sum= $GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_refund where (is_pay = 0 or is_pay = 1) and user_id = ".intval($GLOBALS['user_info']['id']));
		if($bank_sum){
 			$GLOBALS['tmpl']->assign("bank_sum",$bank_sum);
 		}
		$GLOBALS['tmpl']->display("account_money_carry_log.html");
	}
	
	//提现页面
	public function refund_list()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['tmpl']->assign("page_title","提现");
		 
		
		$GLOBALS['tmpl']->display("account_refund_list.html");
	}
	
	public function submitrefund()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		$user_bank_id=intval($_REQUEST['user_bank_id']);
		$money = floatval($_REQUEST['money']);
		$memo = strim($_REQUEST['memo']);
		
		if($money<=0)
		{
			showErr("提现金额出错",$ajax);
		}
		$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
			if($GLOBALS['user_info']['paypassword']!=md5($paypassword)){
				showErr("支付密码错误",$ajax);
			}
		}
		$ready_refund_money =floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_refund where user_id = ".intval($GLOBALS['user_info']['id'])." and is_pay = 0"));
		if($ready_refund_money + $money > $GLOBALS['user_info']['money'])
		{
			showErr("提现超出限制",$ajax);
		}
		$refund_data['user_bank_id'] = $user_bank_id;
		$refund_data['money'] = $money;
		$refund_data['user_id'] = $GLOBALS['user_info']['id'];
		$refund_data['create_time'] = NOW_TIME;
		$refund_data['memo'] = $memo;
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_refund",$refund_data);
		
		$GLOBALS['msg']->manage_msg('MSG_MONEY_CARRY_NOTIFIE','',array('money'=>$money,'user_name'=>$GLOBALS['user_info']['user_name']));
		
		showSuccess("提交成功",$ajax,url("account#refund"));
	}
	
	public function delrefund()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		if($GLOBALS['db']->affected_rows()>0)
		{
			showSuccess("删除成功",$ajax);
		}
		else
		{
			showErr("删除失败",$ajax);
		}
		
	}
	//充值记录
	public function record(){
 		
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		$GLOBALS['tmpl']->assign("page_title","充值记录");

		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		//SELECT * FROM fanwe_payment_notice n WHERE n.order_id='0' AND n.deal_id='0' AND n.deal_item_id='0' AND deal_name='';
		
		$condition=" and order_id=0 AND deal_id=0 AND deal_item_id=0 AND deal_name='' AND is_paid=1 ";
		$record_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment_notice where user_id = ".intval($GLOBALS['user_info']['id'])." $condition  order by create_time desc limit ".$limit);
		$record_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment_notice where user_id = ".intval($GLOBALS['user_info']['id'])." $condition");
		$total_money = $GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."payment_notice where user_id = ".intval($GLOBALS['user_info']['id'])." $condition");
		foreach($record_list as $k=>$v){
			if(!$v['is_paid']){
				$record_list[$k]['url']=url("account#record_pay",array("notice_id"=>$v['id']));
			}
		}
		$page = new Page($record_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign('total_money',floatval($total_money));
		/*
		foreach($record_list as $k=>$v)
		{
			$record_list[$k]['deal_info'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$v['deal_id']." and is_effect = 1 and is_delete = 0");
		}
		*/
		$GLOBALS['tmpl']->assign('record_list',$record_list);
		$GLOBALS['tmpl']->display("account_record.html");
	}
	public function record_pay(){
 		$id=intval($_REQUEST['notice_id']);
 		$payment_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id=".$id);
 		$payment_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where online_pay=1 and is_effect=1 ");
  		$payment_html = "";
		foreach($payment_list as $k=>$v)
		{
			$class_name = $v['class_name']."_payment";
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
		}
  		$GLOBALS['tmpl']->assign("page_title","充值");
  		$GLOBALS['tmpl']->assign("payment_html",$payment_html);
  		$GLOBALS['tmpl']->assign('payment_info',$payment_info);
  		$GLOBALS['tmpl']->assign('payment_list',$payment_list);
  		$GLOBALS['tmpl']->display("account_record_pay.html");
  	}
  	public function record_go_pay(){
  		$return=array('status'=>1,'info'=>'','jump'=>'');
 		$id=intval($_REQUEST['notice_id']);
 		$payment_id=intval($_REQUEST['payment']);
 		
 		$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set payment_id=$payment_id where id=$id ");
 		$return['jump']=url("account#jump",array('id'=>$id));
 		
 		ajax_return($return);
  	}
  	
  	public function mortgate_pay()
  	{
  		if(!$GLOBALS['user_info'])
  			app_redirect(url("user#login"));
  		$GLOBALS['tmpl']->assign("page_title","充值诚意金");
  	 	$deal_id=$_REQUEST['deal_id'];
  	 	$GLOBALS['tmpl']->assign("deal_id",$deal_id);
  	 	$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_id);
  	 	$GLOBALS['tmpl']->assign("deal_info",$deal_info);
  		$new_money=user_need_mortgate();
  		$has_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$GLOBALS['user_info']['id']." and deal_id=".$deal_id." and status=1 ");
   		//$has_money=$GLOBALS['db']->getOne("select mortgage_money from ".DB_PREFIX."user where id=".$GLOBALS['user_info']['id']);
   		$money = $new_money-$has_money;
   		if($money<=0)
  		{
  			//app_redirect(url("account#mortgate_incharge"));
  			if($deal_info['type']==4){
  				app_redirect(url("finance#company_finance",array("id"=>$deal_id)));
  			}else{
  				app_redirect(url("deal#show",array("id"=>$deal_id)));
  			}
  		}
   		$GLOBALS['tmpl']->assign("money",$money);
   		if($money>$GLOBALS['user_info']['money']){
   			$left_money = $money - floatval($GLOBALS['user_info']['money']);
   		}else{
   			$left_money = 0;
   		}
   		$GLOBALS['tmpl']->assign("left_money",$left_money);
  		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay=1 order by sort asc ");
  		$payment_html = "";
  		foreach($payment_list as $k=>$v)
  		{
  			$class_name = $v['class_name']."_payment";
  			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
  			$o = new $class_name;
  			$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
  		}
  		$GLOBALS['tmpl']->assign("payment_html",$payment_html);
  		$GLOBALS['tmpl']->assign("coll",is_tg(true));
  		$GLOBALS['tmpl']->display("mortgage_incharge.html");
  	}
  	public function get_leader_list(){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
  		$deal_id=intval($_REQUEST['id']);
  		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id and user_id=".$GLOBALS['user_info']['id']);
   		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
  		$investor_list=$GLOBALS['db']->getAll("select invest.*,u.user_name,u.mobile,u.user_level from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on u.id=invest.user_id where invest.type=1 and invest.deal_id=$deal_id order by invest.id desc limit $limit ");
   		$investor_list_num=$GLOBALS['db']->getOne("select count(*) as num from  ".DB_PREFIX."investment_list where type=1 and deal_id=$deal_id order by id desc limit $limit ");
  		$now_time=NOW_TIME;
  		foreach($investor_list as $k=>$v){
  			if($v['status']==0&&$now_time>$deal['end_time']){
  				$investor_list[$k]['status']=2;
   					$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id= ".$v['id']);
  			}
  			$investor_list[$k]['cates']=unserialize($v['cates']);
  			$investor_list[$k]['user_icon'] =$GLOBALS['user_level'][$v['user_level']]['icon'];//用户等级图标
  		}
  		$page = new Page($investor_list_num,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('deal',$deal);
		$GLOBALS['tmpl']->assign("project_deal_type",$deal['type']);
		$GLOBALS['tmpl']->assign('deal_id',$deal_id);
		$GLOBALS['tmpl']->assign('pages',$p);
   		$GLOBALS['tmpl']->assign('investor_list',$investor_list);
  		$GLOBALS['tmpl']->display("account_leader_list.html");
  	}
  	public function add_leader_info(){
  		if(!$GLOBALS['user_info']){
  			app_redirect(url("user#login"));
  		}
  		$id=intval($_REQUEST['id']);
  		$leader_info=$GLOBALS['db']->getRow("select invest.*,d.name as deal_name from ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."deal as d on d.id=invest.deal_id where invest.id=".$id);
  		if($GLOBALS['user_info']['id']!=$leader_info['user_id']){
  			showErr("该页面不存在",0,"");
  			return false;
  		}
  		$file=unserialize($leader_info['leader_moban']);
  		$GLOBALS['tmpl']->assign('file',$file);
  		$GLOBALS['tmpl']->assign('leader_info',$leader_info);
  		$GLOBALS['tmpl']->assign('title','上传领投信息');
  		$GLOBALS['tmpl']->display("account_add_leader_info.html");
  	}
  	
  	//股权参与列表
  	public function mine_investor_status(){
  		if(app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
  		$this->mine_investor_status_public(1,"mine_investor_status");
  	}
  	
  	//融资参与列表
  	public function mine_investor_finance(){
  		if(app_conf("IS_FINANCE")==0)
		{
			showErr("融资项目已经关闭");
		}
  		$this->mine_investor_status_public(4,"mine_investor_finance");
  	}
  	
  	public function mine_investor_status_public($investor_type,$investor_act){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$parameter=array();
		
		
  		$user_id=$GLOBALS['user_info']['id'];
  		$type=intval($_REQUEST['type']);
  		
  		$condition='';
  		$condition_num='';
  		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter["more_search"]=$more_search;
		
		$deal_type=intval($investor_type);
		if($deal_type){
			$condition .=" and d.type=".$deal_type;
			$parameter["deal_type"]=$deal_type;
			$GLOBALS['tmpl']->assign('deal_type',$deal_type);
		}else{
			$condition .=" and d.type=1";
		}
		
		$deal_name=strim($_REQUEST['deal_name']);
		if(!empty($deal_name)){
			$condition .= " and d.name like '%$deal_name%' ";
			$parameter["deal_name"]=$deal_name;
			$GLOBALS['tmpl']->assign('deal_name',$deal_name);
		}
		
		$item_status=intval($_REQUEST['item_status']);
		if($item_status>0){
			switch($item_status){
				//进行中
				case 1:
				$condition .=" and invest.investor_money_status=0  ";
				break;
				//未通过
				case 2:
				$condition .= " and invest.investor_money_status=2 ";
				break;
				//待付款
				case 3:
				$condition .= " and invest.investor_money_status=1 ";
				break;
				//已付款
				case 4:
				$condition .= " and invest.investor_money_status=3 ";
				break;
				//违约
				case 5:
				$condition .=" and invest.investor_money_status=4  ";
				break;
			}
			$parameter["item_status"]=$item_status;
			$GLOBALS['tmpl']->assign('item_status',$item_status);
		}
		
		$pay_begin_time=strim($_REQUEST['pay_begin_time']);
 		if($pay_begin_time!=0){
 			$pay_begin_time=to_timespan($pay_begin_time,'Y-m-d');
 			$condition.=" and invest.create_time>=$pay_begin_time ";
 			$GLOBALS['tmpl']->assign('pay_begin_time',to_date($pay_begin_time,'Y-m-d'));
 			$parameter["begin_time"]=to_date($pay_begin_time,'Y-m-d');
 		}
		
		$pay_end_time=strim($_REQUEST['pay_end_time']);
 		if($pay_end_time!=0){
 			$pay_end_time=to_timespan($pay_end_time,'Y-m-d');
 			$condition.=" and invest.create_time<=$pay_end_time ";
 			$GLOBALS['tmpl']->assign('pay_end_time',to_date($pay_end_time,'Y-m-d'));
 			$parameter["begin_time"]=to_date($pay_end_time,'Y-m-d');
 		}
  		 
  		
    	$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		if($type==0){
			$investor_list=$GLOBALS['db']->getAll("select invest.*,d.end_time,d.pay_end_time,d.begin_time,d.name as deal_name ,d.image as deal_image,d.id as deal_id,d.type as deal_type,d.invest_phase,d.is_success,d.stock_type" .
					" from  ".DB_PREFIX."investment_list as invest " .
					"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id where  (invest.type=0 or invest.type=5) and invest.user_id=$user_id and d.is_delete =0 and d.is_effect=1 ".$condition." order by invest.id desc limit $limit ");
			 
			$investor_list_num=$GLOBALS['db']->getOne("select count(invest.id)  from  ".DB_PREFIX."investment_list as invest " .
					"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id where  (invest.type=0 or invest.type=5) and invest.user_id=$user_id and d.is_delete =0 and d.is_effect=1 ".$condition." ");
		}else {
   		$investor_list=$GLOBALS['db']->getAll("select invest.*,d.end_time,d.pay_end_time,d.begin_time,d.name as deal_name ,d.image as deal_image,d.id as deal_id,d.type as deal_type,d.invest_phase,d.is_success,d.stock_type" .
   				" from  ".DB_PREFIX."investment_list as invest " .
   				"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id where  invest.type=$type and invest.user_id=$user_id and d.is_delete =0 and d.is_effect=1 ".$condition." order by invest.id desc limit $limit ");
    	
    	$investor_list_num=$GLOBALS['db']->getOne("select count(invest.id)  from  ".DB_PREFIX."investment_list as invest " .
   				"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id where  invest.type=$type and invest.user_id=$user_id and d.is_delete =0 and d.is_effect=1 ".$condition." ");
		}
    	//$investor_list_num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."investment_list as invest where  invest.type=$type and invest.user_id=$user_id  ".$condition);
		
  		//$order_sum = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as de on de.id=do.deal_id where  do.user_id = ".intval($GLOBALS['user_info']['id'])." and do.type in(0,2,3,6) and de.is_delete =0 and de.is_effect = 1");
 		//$GLOBALS['tmpl']->assign('order_sum',$order_sum );
  		$investor_list_count=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."deal as de on de.id = invest.deal_id where de.type=".$deal_type." and invest.type<>6 and invest.user_id=".intval($GLOBALS['user_info']['id'] ));
  		$GLOBALS['tmpl']->assign('investor_list_count',$investor_list_count);
  		$now_time=NOW_TIME;
 		if($type==0||$type==2||$type==1||$type==4){
   			foreach($investor_list as $k=>$v){
   				if($type==1){ 
   					if($v['status']==0&&$now_time>$v['end_time']){
						$investor_list[$k]['status']=2;
						$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id= ".$v['id']);
   					}
   				}
   				if($type==4){
   					//添加取消操作
   					$investor_list[$k]['stock_transfer_id']=$GLOBALS['db']->getOne("select id from  ".DB_PREFIX."stock_transfer where invest_id_2=".$v['id']);
   				}elseif($v['investor_money_status']==0&&$now_time>$v['end_time']){
					$investor_list[$k]['investor_money_status']=2;
					$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=2 where id= ".$v['id']);
   				}elseif($v['investor_money_status']==1&&$now_time>$v['pay_end_time']){
   					$investor_list[$k]['investor_money_status']=4;
					deal_invest_break($v['id']);   				
   				}
   			}
		} 
   		$order_ids=array();
		foreach($investor_list as $k=>$v){
			$order_ids[] =  $v['order_id'];
		}
		if($order_ids!=null){
			$order_list_array=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where  id in (".implode(',',$order_ids).")");
			$order_list=array();
			foreach($order_list_array as $k=>$v){
				if($v['id']){
					$order_list[$v['id']]=$v;
				}
			}
			foreach($investor_list as $k=>$v)
			{
	 			$investor_list[$k]['order_info'] =$order_list[$v['order_id']];
			}
		}
   		
   		
   		$title='';
   		switch($type){
  			case 1:
  			$title='领投列表';
  			break;
  			case 2:
  			$title='跟投列表';
  			break;
  			case 0:
  			$title='询价列表';
  			break;
  			case 4:
  			$title='转让中的股份列表';
  			break;
  			case 5:
  			$title='转入的股份列表';
  			break;
  		}
  		
  		$page = new Page($investor_list_num,$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("account#".$investor_act."",$parameter);
		$GLOBALS['tmpl']->assign('pages',$p);
   		$GLOBALS['tmpl']->assign('type',$type);
   		$GLOBALS['tmpl']->assign('title',$title);
   		$GLOBALS['tmpl']->assign('investor_list',$investor_list);
   		
   		$GLOBALS['tmpl']->assign('investor_act',$investor_act);
   		$GLOBALS['tmpl']->assign('cur_url',url("account#".$investor_act.""));
   		$GLOBALS['tmpl']->assign('status_0_url',url("account#".$investor_act."",array("type"=>0)));
   		$GLOBALS['tmpl']->assign('status_1_url',url("account#".$investor_act."",array("type"=>1)));
   		$GLOBALS['tmpl']->assign('status_2_url',url("account#".$investor_act."",array("type"=>2)));
   		$GLOBALS['tmpl']->assign('status_0_url',url("account#".$investor_act."",array("type"=>4)));
   		
  		$GLOBALS['tmpl']->display("account_mine_investor.html");
  	}
  	public function get_investor_status(){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$parameter=array();	
  		$deal_id=$_REQUEST['id'];
  		$type=intval($_REQUEST['type']);
  		$user_name_i=strim($_REQUEST['user_name_i']);
  		$money_status=intval($_REQUEST['money_status']);
  		$more_search=intval($_REQUEST['more_search']);
  		$begin_time=strim($_REQUEST['begin_time']);
  		$end_time=strim($_REQUEST['end_time']);
  		
  		$GLOBALS['tmpl']->assign('user_name_i',$user_name_i);
  		$GLOBALS['tmpl']->assign('money_status',$money_status);
  		$GLOBALS['tmpl']->assign('more_search',$more_search);
  		$GLOBALS['tmpl']->assign('end_time',$end_time);
  		$GLOBALS['tmpl']->assign('begin_time',$begin_time);
  		$GLOBALS['tmpl']->assign('deal_id',$deal_id);
  		

  		$parameter[]="type=".$type;
  		if($deal_id >0)
  			$parameter[]="id=".$deal_id;
  		if($money_status >0)
  			$parameter[]="money_status=".$money_status;
  		if($money_status >0)
  			$parameter[]="money_status=".$money_status;
  		if($end_time !='')
  			$parameter[]="end_time=".$end_time;
  		if($begin_time !='')
  			$parameter[]="begin_time=".$begin_time;
  		
  		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id and user_id=".$GLOBALS['user_info']['id']);
  		$GLOBALS['tmpl']->assign('deal',$deal);
  	 	
  		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		if($type==1){
			$condition=" and  invest.type=1 and invest.deal_id=$deal_id and invest.money>0 and invest.status=1 ";
		}else{
			$condition=" and  invest.type=$type and invest.deal_id=$deal_id ";
		}
		
		if($user_name_i != '')
		{
			$condition .=" and u.user_name like '%".$user_name_i."%' ";
		}
		if($money_status >0)
		{
			if($money_status == 5)
				$money_status=0;
			if($money_status ==1)
				$condition .=" and (invest.investor_money_status =1 or invest.investor_money_status >2)";			
			else
				$condition .=" and invest.investor_money_status =".$money_status."";
		}
		$begin_time_val=to_timespan($begin_time);
		$end_time_val=to_timespan($end_time);
		if($begin_time !='' && $end_time != '')
			$condition = " and invest.create_time >=".$begin_time_val." and invest.create_time <=".$end_time_val."";
		elseif($begin_time !='' && $end_time =='')
			$condition = " and invest.create_time >= ".$begin_time_val." ";
		elseif($begin_time =='' && $end_time !='')
			$condition = "and invest.create_time <=".$end_time_val." ";
		
  		$investor_list=$GLOBALS['db']->getAll("select invest.*,u.user_name from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on invest.user_id=u.id where 1=1 $condition order by id desc limit $limit ");
   		$investor_list_num=$GLOBALS['db']->getOne("select count( invest.id) from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on invest.user_id=u.id where 1=1 $condition  ");
   		$now_time=NOW_TIME;
   		
 		if($type==0||$type==2||$type==1){
   			foreach($investor_list as $k=>$v){
   				if($type==1){
   					if($v['status']==0&&$now_time>$deal['end_time']){
 						$investor_list[$k]['status']=2;
						$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id= ".$v['id']);
   					}
   				}
    				if($v['investor_money_status']==0&&$now_time>$deal['end_time']){
    				$investor_list[$k]['investor_money_status']=2;
   					$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=2 where id= ".$v['id']);
   				}elseif($v['investor_money_status']==1&&$now_time>$deal['pay_end_time']){
   					$investor_list[$k]['investor_money_status']=4;
					deal_invest_break($v['id']);
   				}
   			}
		} 
   		
   		$title='';
   		switch($type){
  			case 1:
  			$title='领投列表';
  			break;
  			case 2:
  			$title='跟投列表';
  			break;
  			case 0:
  			$title='询价列表';
  			break;
  		}
  		
  		$parameter_str="&".implode("&",$parameter);
  		$page = new Page($investor_list_num,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
   		$GLOBALS['tmpl']->assign('type',$type);
   		$GLOBALS['tmpl']->assign("project_deal_type",$deal['type']);
   		$GLOBALS['tmpl']->assign('deal_id',$deal_id);
   		$GLOBALS['tmpl']->assign('title',$title);
   		$GLOBALS['tmpl']->assign('investor_list',$investor_list);
  		$GLOBALS['tmpl']->display("account_investor_list.html");
  	}
  	public function lead_examine(){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
  		
  		$ajax=1;
  		$id=intval($_REQUEST['id']);
  		$type=intval($_REQUEST['type']);
  		$status=intval($_REQUEST['status']);
  		$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."investment_list where id=$id and type=1 ");
  		if(!$item){
  			showErr("该申请不存在",$ajax,"");
  		}
  		if($status==1){
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=1 where id=$id ");
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where deal_id=".$item['deal_id']." and type=1 and id!=".$item['id']);
  		}else{
   			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id=$id ");
  		}
  		showSuccess("审核成功",$ajax);
  	}
  	
  	//修改估值
  	public function investor_examine(){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
  		$ajax=1;
  		//$result=array('status'=>1,'info'=>'');
  		$id=intval($_REQUEST['id']);
  		$type=intval($_REQUEST['type']);
  		$status=intval($_REQUEST['status']);
  		$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."investment_list where id=$id ");
  		if(!$item){
  			showErr("该询价不存在",$ajax,"");
  		}
  		$deal_id=intval($item['deal_id']);
  		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id and user_id=".$GLOBALS['user_info']['id']);
  		if(!$deal){
  			showErr("项目不存在",$ajax,"");

  		}
  		if($status==1){
  			$now_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."investment_list where deal_id=$deal_id and (investor_money_status=1 or investor_money_status=3  )");
  			if($type==0){
  				if($item['stock_value']<($now_money+$item['money'])){
	  				showErr("您允许的金额已超过估值的额度".$deal['stock_value'],$ajax);
	  			}
  			}else{
  				if($deal['limit_price']<($now_money+$item['money'])){
	  				showErr("您允许的金额已超过你的融资额度".$deal['limit_price'],$ajax);
	  			}
  			}
  			
  			$this->create_investor_pay($id);
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=1 where id=$id ");
   			if($type==0){
   				$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."deal set limit_price=".$item['stock_value']." where id=$deal_id ");
   			}
   			
   		}else{
   			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=2 where id=$id ");
   		}
  		showSuccess("审核成功",$ajax);
  		
  		
  	}
  	
  	public function create_investor_pay($invest_id){
  		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
  		
  		$ajax=1;
  		if(!$invest_id){
  			showErr("该申请错误",$ajax);
  		}
  		$invest=$GLOBALS['db']->getRow("select  invest.*,u.user_name from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on u.id=invest.user_id where invest.id=".$invest_id."  ");
  		if(!$invest){
  			showErr("没有该申请",$ajax);
  		}
  		$user_id=$GLOBALS['user_info']['id'];
  		$deal_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=".$invest['deal_id']." and user_id=$user_id");
  		
  		if(!$deal_info){
  			showErr("没有该项目",$ajax);
  		}
  		$order_info['deal_id'] = $deal_info['id'];
 		$order_info['user_id'] = intval($invest['user_id']);
		$order_info['user_name'] = $invest['user_name'];
		$order_info['total_price'] = $invest['money'];
		$order_info['delivery_fee'] = 0;
		$order_info['deal_price'] = $invest['money'];
		$order_info['support_memo'] = "";
		//$order_info['payment_id'] = "";
		//$order_info['bank_id'] = strim($_REQUEST['bank_id']);
		
 		
		$order_info['credit_pay'] = 0;
		$order_info['online_pay'] = 0;
		$order_info['deal_name'] = $deal_info['name'];
		$order_info['order_status'] = 0;
		if($deal_info['type']==4){
			$order_info['type'] = 5;
		}else{
			$order_info['type'] = 1;
		}
		$order_info['invest_id'] = $invest_id;
		$order_info['create_time']	= NOW_TIME;
		$order_info['is_success'] = $deal_info['is_success'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info);
		
		$order_id = $GLOBALS['db']->insert_id();
		if(!$order_id){
			showErr("订单生成失败",$ajax);
		}else{
			//生成发送通知
			invest_pay_send($invest['id'],$order_id);
		}
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list SET order_id=$order_id where id=".$invest['id']);
  	}
  	/*我的推荐列表*/
  	public function recommend(){
  		if( app_conf("INVEST_STATUS") == 2)
		{
			showErr("产品众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","我的产品项目推列表");
		$this->recommend_public(0,'recommend');
  	}
  	
	//关注的房产项目
	public function recommend_house()
	{
		if( app_conf("IS_HOUSE") ==0)
		{
			showErr("房产众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的房产项目");
		$this->recommend_public(2,'recommend_house');
	}
	//关注公益项目
	public function recommend_selfless()
	{
		if( app_conf("IS_SELFLESS") ==0)
		{
			showErr("公益众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的公益项目");
		$this->recommend_public(3,'recommend_selfless');
	}
	//关注股权项目
	public function recommend_investor()
	{
		if( app_conf("INVEST_STATUS") ==1)
		{
			showErr("股权众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的股权项目");
		$this->recommend_public(1,'recommend_investor');
	}
	//关注融资项目
	public function recommend_finance()
	{
		if( app_conf("IS_FINANCE") ==0)
		{
			showErr("融资众筹已关");
		}
		$GLOBALS['tmpl']->assign("page_title","关注的融资项目");
		$this->recommend_public(4,'recommend_finance');
	}
  	public function recommend_public($recommend_deal_type=0,$recommend_act="recommend"){
  		if(!$GLOBALS['user_info'])
  		{
  			showErr("",0,url("user#login"));
  		}
  		$recommend_deal_type=intval($recommend_deal_type);
		if($recommend_deal_type >0)
		{
			$_REQUEST['deal_type']=$recommend_deal_type;
		}
		$deal_type=intval($_REQUEST['deal_type']);
		
		$where=" and r.deal_type =".$deal_type." ";
		
  		$page_size = ACCOUNT_PAGE_SIZE;
  		$page = intval($_REQUEST['p']);
  		if($page==0)$page = 1;
  		$limit = (($page-1)*$page_size).",".$page_size;
  		$user_id=intval($GLOBALS['user_info']['id']);
  		$recommend_info=$GLOBALS['db']->getAll("SELECT r.*,d.type,d.invest_phase FROM ".DB_PREFIX."recommend as r left join ".DB_PREFIX."deal as d on d.id=r.deal_id WHERE r.user_id=".$user_id.$where." ORDER BY r.create_time DESC limit $limit");
  		$recommend_gq_info=$GLOBALS['db']->getAll("SELECT r.*,d.type,d.invest_phase FROM ".DB_PREFIX."recommend as r left join ".DB_PREFIX."deal as d on d.id=r.deal_id WHERE r.user_id=".$user_id.$where." and r.deal_type=1 ORDER BY r.create_time DESC limit $limit");
  		$recommend_cp_info=$GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."recommend WHERE user_id=".$user_id." and deal_type=0 ORDER BY create_time DESC limit $limit");
  		$recommend_count=$GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."recommend as r WHERE r.user_id=".$user_id.$where);
  		$param=array();
  		$param['deal_type']=$deal_type;
  		$page = new Page($recommend_count,$page_size);   //初始化分页对象
  		$p  =  $page->para_show("account#".$recommend_act."",$param);
  		
  		$GLOBALS['tmpl']->assign('pages',$p);
  		$GLOBALS['tmpl']->assign('recommend_act',$recommend_act);
  		$GLOBALS['tmpl']->assign("recommend_info",$recommend_info);
  		$GLOBALS['tmpl']->display("account_recommend.html");
  	}
  	
  	public function money_index()
  	{
  		$GLOBALS['tmpl']->display("account_money_index.html");
  	}
	public function money_inchange()
	{		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$GLOBALS['tmpl']->assign("money",floatval($_REQUEST['money']));
		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay=1 order by sort asc ");
		$payment_html = "";
		foreach($payment_list as $k=>$v)
		{
			$class_name = $v['class_name']."_payment";
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
		}
		$GLOBALS['tmpl']->assign("payment_html",$payment_html);
		$GLOBALS['tmpl']->display("account_money_inchange.html");
	}
	
	public function money_inchange_log()
	{	
		
		$GLOBALS['tmpl']->display("account_money_inchange_log.html");
	}
	public function money_carry_bank(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$payment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,1) and class_name = 'YeepayInvestmentPass' ");
		if($payment_count){
			$banks=$GLOBALS['db']->getAll("select ub.*,b.icon as icon from ".DB_PREFIX."user_bank as ub left join ".DB_PREFIX."bank as b on ub.bank_id = b.id  where ub.user_id=".$GLOBALS['user_info']['id']);
			foreach($banks as $k=>$v){
				if($v['type']==1){
					$banks[$k]['ye']='（易宝快捷）';
				}
			}
		}else{
			$banks=$GLOBALS['db']->getAll("select ub.*,b.icon as icon from ".DB_PREFIX."user_bank as ub left join ".DB_PREFIX."bank as b on ub.bank_id = b.id  where ub.type = 0 and ub.user_id=".$GLOBALS['user_info']['id']);
		}
		$GLOBALS['tmpl']->assign('banks',$banks);
		$GLOBALS['tmpl']->assign('payment_count',$payment_count);
		if($GLOBALS['is_tg'] && $GLOBALS['user_info']['ips_acct_no']){
			//手续费
			$fee_config = load_auto_cache("user_carry_config");
			$json_fee = array();
			foreach($fee_config as $k=>$v){
				$json_fee[] = $v;
				if($v['fee_type']==1)
					$fee_config[$k]['fee_format'] = $v['fee']."%";
				else
					$fee_config[$k]['fee_format'] = format_price($v['fee']);
			}
			$GLOBALS['tmpl']->assign("fee_config",$fee_config);
			$GLOBALS['tmpl']->assign("json_fee",json_encode($json_fee));
		}
		$GLOBALS['tmpl']->display("account_money_carry_bank.html");
	}
	
	public function money_carry_log()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['tmpl']->display("account_money_carry_log.html");
	}
	public function money_carry_addbank()
	{
 		$bank_list=get_bank_list();
 		//$bank_list=$bank_list['recommend'];
 		$GLOBALS['tmpl']->assign('bank_list',$bank_list);
		$GLOBALS['tmpl']->display("inc/account_money_carry_addbank.html");
	}
	public function addbank(){
		$GLOBALS['tmpl']->display("account_money_carry_addbank.html");
	}
	public function delbank(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$ajax=1;
		$id=intval($_REQUEST['id']);
		$user_id=$GLOBALS['user_info']['id'];
		if($id>0){
			$re=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where user_id=$user_id and id=$id ");
			
			$payment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,1) and class_name = 'YeepayInvestmentPass' ");
			if($payment_count&&$re['type']==1){
				require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass_payment.php";
				$order = new YeepayInvestmentPass_payment;
				$user_bank = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where id = ".$id);
				$identityid=strim($user_bank['identityid']);
				$identitytype=intval($user_bank['identitytype']);
				$return = $order->yeepay_bankcardList($identityid, $identitytype);
				if($return['error_msg']){
					$this->error($return['error_msg']);
				}else{
					foreach($return['cardlist'] as $k=>$v)
					{
						$return['bindid']=$v['bindid'];
					}
					$bindid=$return['bindid'];
					$return = $order->yeepay_bankUnbind($bindid, $identityid, $identitytype);
					if($return['error_msg']){
						$this->error($return['error_msg']);
					}else{
						$results= $GLOBALS['db']->query("delete from  ".DB_PREFIX."user_bank where id=$id");
						 if($results){
						 	showSuccess("删除成功" ,1,url("account#money_carry_bank"));
						 }else{
						 	showErr("删除失败",1,url("account#money_carry_bank"));
						 }
					}
				}
			}else{
				if($re){
					$result= $GLOBALS['db']->query("delete from  ".DB_PREFIX."user_bank where id=$id");
					 if($result){
					 	showSuccess("删除成功" ,$ajax);
					 }else{
					 	showErr("删除失败",$ajax);
					 }
				}else{
					showErr("您没有权限删除该银行卡",$ajax);
				}
			}
			
		}else{
			showErr("不存在该银行卡",$ajax);
		}
	}
	public function savebank(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
 		$ajax=intval($_REQUEST['ajax']);
		if(empty($GLOBALS['user_info']['identify_name'])){
			showErr('请进行完成身份认证，才可以添加银行卡',$ajax);
		}
 		$bank_id = strim($_REQUEST['bank_id']);
 		$otherbank=intval($_REQUEST['otherbank']);
 		$data=array();
 		if(empty($bank_id)){
			showErr("请选择银行".$bank_id,$ajax);
		}else{
			if($bank_id=='other'){
				if($otherbank==0){
					showErr("请选择银行",$ajax);
				}else{
					$data['bank_id']=$otherbank;
				}
			}else{
				$data['bank_id']=$bank_id;
			}
		}
		$province=strim($_REQUEST['province']);
		if(empty($province)){
			showErr("请选择省份",$ajax);
		}
		$data['region_lv2']=$province;
		$city=strim($_REQUEST['city']);
		if(empty($city)){
			showErr("请选择城市",$ajax);
		}
		$data['region_lv3']=$city;
		$bankzone=strim($_REQUEST['bankzone']);
		if($bankzone==''){
			showErr('请填写开户行网点',$ajax);
		}
		$data['bankzone']=$bankzone;
		$bankcard=strim($_REQUEST['bankcard']);
		if($bankcard==''){
			showErr('请填写银行卡号',$ajax);
		}
		if(strlen($_REQUEST['bankcard'])<12)
		{
			showErr("最少输入10位账号信息！",$ajax);
		}
		$data['bankcard']=$bankcard;
		$reBankcard=strim($_REQUEST['reBankcard']);
		if($reBankcard!=$bankcard){
			showErr('银行卡号与确认卡号不一致',$ajax);
		}
		$data['reBankcard']=$reBankcard;
		$data['user_id']=$GLOBALS['user_info']['id'];
		
 		$data['real_name']=$GLOBALS['user_info']['identify_name'];
 		$data['bank_name']=$GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."bank where id=".$data['bank_id']);
 		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$data,"INSERT","","SILENT");
 		if($re){
 			showSuccess("添加成功",$ajax,url("account#money_carry_bank"));
 		}else{
 			showErr("插入失败",$ajax);
 		}
		
	}
	public function savebindbankcard(){
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
 		$ajax=intval($_REQUEST['ajax']);
		if(empty($GLOBALS['user_info']['identify_name'])){
			$result['status']=0;
			$result['info']="请进行完成身份认证，才可以添加银行卡";
			ajax_return($result);
		}
		$requestid=strim($_REQUEST['requestid']);
		$identitytype = 2;
		$username= $GLOBALS['user_info']['identify_name'];
		$idcardno = $GLOBALS['user_info']['identify_number'];
		$identityid=strim($_REQUEST['identityid']);
		$cardno=strim($_REQUEST['cardno']);
		$phone=strim($_REQUEST['phone']);
		$userip = strim($_REQUEST['userip']);
 		$bank_id = strim($_REQUEST['bank_id']);
 		$otherbank=intval($_REQUEST['otherbank']);
 		if($cardno==''){
			$result['status']=0;
			$result['info']="请填写银行卡号";
			ajax_return($result);
		}
		if(strlen($_REQUEST['cardno'])<12)
		{
			$result['status']=0;
			$result['info']="最少输入10位账号信息！";
			ajax_return($result);
		}
		if($phone==''){
			$result['status']=0;
			$result['info']="银行预留手机号不能为空!";
			ajax_return($result);
		}
 		$cart_info=array();
 		if(empty($bank_id)){
 			$result['status']=0;
			$result['info']="请选择银行";
			ajax_return($result);
		}else{
			if($bank_id=='other'){
				if($otherbank==0){
					$result['status']=0;
					$result['info']="请选择银行";
					ajax_return($result);
				}else{
					$cart_info['bank_id']=$otherbank;
					//非易宝绑卡
					$othertype=1;
				}
			}else{
				$cart_info['bank_id']=$bank_id;
			}
		}
		$province=strim($_REQUEST['province']);
		if(empty($province)){
			$result['status']=0;
			$result['info']="请选择省份";
			ajax_return($result);
		}
		$cart_info['region_lv2']=$province;
		$city=strim($_REQUEST['city']);
		if(empty($city)){
			$result['status']=0;
			$result['info']="请选择城市";
			ajax_return($result);
		}
		$cart_info['region_lv3']=$city;
		$bankzone=strim($_REQUEST['bankzone']);
		if($bankzone==''){
			$result['status']=0;
			$result['info']="请填写开户行网点";
			ajax_return($result);
		}
		$cart_info['bankzone']=$bankzone;
		
		$cart_info['bankcard']=$cardno;
		$cart_info['user_id']=$GLOBALS['user_info']['id'];
		
 		$cart_info['real_name']=$GLOBALS['user_info']['identify_name'];
 		$cart_info['identify_number']=$GLOBALS['user_info']['identify_number'];
 		if($othertype==1){
 			//非易宝
 			$cart_info['bank_name']=$GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."bank where id=".$cart_info['bank_id']);
 			$re=$GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$cart_info,"INSERT","","SILENT");
 			if($re){
 				$result['status']=4;
 				$result['info'] = '添加成功';
 				$result['jump']=url("account#money_carry_bank");
 				ajax_return($result);
 				return false;
 			}else{
 				$result['status']=0;
 				$result['info'] = '添加失败';
 				ajax_return($result);
 				return false;
 			}
 				
 		}else{
 			require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass/BindBankCard.php";
 			$bindbankcard = BindBankCard($identityid,$identitytype,$requestid,$cardno,$idcardno,$username,$phone,$userip);
 			
 			if($bindbankcard['codesender']){
 				if($bindbankcard['codesender']=='MERCHANT'){
 					send_tzt_verify_sms($phone,$bindbankcard['smscode']);
 				}
 				$cart_info['mobile']=$phone;
 				$cart_info['identityid']=$identityid;
 				$cart_info['identitytype']=$identitytype;
 				$GLOBALS['tmpl']->assign("cart_info",$cart_info);
 				$GLOBALS['tmpl']->assign("requestid",$requestid);
 			
 				$addbindbankcard=intval($_REQUEST['addbindbankcard']);
 				$GLOBALS['tmpl']->assign("addbindbankcard",$addbindbankcard);
 					
 				$result['status']=1;
 				$result['html'] = $GLOBALS['tmpl']->fetch("inc/bindbankcard_confirm.html");
 				ajax_return($result);
 				return false;
 			}else{
 					
 				$result['status']=2;
 				$result['info'] = $bindbankcard['error_msg'];
 				ajax_return($result);
 				return false;
 			}
 		}
 		
		
	}
	
	public function money_carry()
	{
		$user_bank_id=intval($_REQUEST['id']);
		$bank_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where id=".$user_bank_id." and user_id=".$GLOBALS['user_info']['id']);
 		if(!$bank_info){
 			showErr("银行信息不存在",0,url("account#money_carry_bank"));
 		}
 		$ready_refund_money =floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_refund where user_id = ".intval($GLOBALS['user_info']['id'])." and is_pay = 0"));
 		$bank_info['can_use_money']=$GLOBALS['user_info']['money']-$ready_refund_money;
 		$bank_info['ready_refund_money']=$ready_refund_money;
 		$bank_info['bankcard']=substr($bank_info['bankcard'],0,-6)."***".substr($bank_info['bankcard'],-3);
 		$GLOBALS['tmpl']->assign('bank_info',$bank_info);
 		$GLOBALS['tmpl']->display("account_money_carry.html");
	}
	
	public function export_support_0($page = 1)
	{
		set_time_limit(0);
		$limit = ($page - 1)*intval(ACCOUNT_PAGE_SIZE).",".intval(ACCOUNT_PAGE_SIZE);
		$retrun=array('status'=>0,'info'=>"","url"=>'');
		if(!$GLOBALS['user_info'])
		{
			$jump_url=url("user#login");
			showErr('请先登录',0,$jump_url);
		}
		
		$deal_id = intval($_REQUEST['id']);
		$type = intval($_REQUEST['type']);
		$user_name = strim($_REQUEST['user_name']);
		$mobile = strim($_REQUEST['mobile']);
		$repay_status = intval($_REQUEST['repay_status']);
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		
		if(!$deal_info)
		{
			$jump_url=url("account#support",array('id'=>$deal_id,'type'=>$type));
			showErr('项目未找到',0,$jump_url);
		}
		
		$where='';
		if($user_name !='')
		{
			$where .=" and d.user_name like '%".$user_name."%'";
		}
		if($mobile !='')
		{
			$where .=" and d.mobile =".$mobile."";
		}
		if($repay_status >0)
		{
			switch($repay_status){
  			case 1:
  			$where .= " and d.repay_time >0";
  			break;
  			case 2:
  			$where .= " and d.repay_time =0";
  			break;
  			case 3:
  			$where .= " and d.repay_time >0 and d.repay_make_time >0";
  			break;
  			}
		}
		
		$support_list = $GLOBALS['db']->getAll("select d.*,di.description as item_description from ".DB_PREFIX."deal_order as d left join ".DB_PREFIX."deal_item as di on di.id=d.deal_item_id where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.deal_item_id >0 ".$where." order by d.create_time desc limit ".$limit);
		
		if($support_list)
		{
			register_shutdown_function(array(&$this, 'export_support_0'), $page+1);
			$support_value = array('id'=>'""', 'user_name'=>'""', 'deal_name'=>'""','total_price'=>'""','delivery_fee'=>'""','share_fee'=>'""', 'item_description'=>'""', 'repay_memo'=>'""', 'address'=>'""','zip'=>'""','consignee'=>'""', 'mobile'=>'""', 'repay_status'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","编号,会员名,商品名称,支付金额,运费,分红金额,回报内容,回报备注,收货地址,邮编,收件人,电话,回报状态");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
	    	foreach($support_list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['deal_name'] = '"' . iconv('utf-8','gbk',$v['deal_name']) . '"';
				$order_value['total_price'] = '"' . iconv('utf-8','gbk',round($v['total_price'],2)."元") . '"';
				$order_value['delivery_fee'] = '"' . iconv('utf-8','gbk',round($v['delivery_fee'],2)."元") . '"';			
				$order_value['share_fee'] = '"' . iconv('utf-8','gbk',round($v['share_fee'],2)."元") . '"';
				$order_value['item_description'] = '"' . iconv('utf-8','gbk',$v['item_description']) . '"';
				$order_value['repay_memo'] = '"' . iconv('utf-8','gbk',$v['repay_memo']) . '"';
				$order_value['address'] = '"' . iconv('utf-8','gbk',$v['address']) . '"';
				$order_value['zip'] = '"' . iconv('utf-8','gbk',$v['zip']) . '"';
				$order_value['consignee'] = '"' . iconv('utf-8','gbk',$v['consignee']) . '"';
				$order_value['mobile'] = '"' . iconv('utf-8','gbk',$v['mobile']) . '"';
				
				if($v['is_success']==1){
					if($v['repay_time'] >0)
					{
						if($v['repay_make_time'] >0){
							$repay_status= "确认收到";
						}else{
							$repay_status= "未确认";
						}
					}else
					{
						$repay_status= "未发放";
					}
					
				}else{
					$repay_status= "未成功";
				}
				$order_value['repay_status'] = '"' . iconv('utf-8','gbk',$repay_status) . '"';
				
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			header("Content-Disposition: attachment; filename=support_list.csv");
	    	echo $content; 
		}
		else
		{
			if($page==1)
			{
				$jump_url=url("account#support",array('id'=>$deal_id,'type'=>$type));
				showErr('没有找到项目支持信息',0,$jump_url);
			}
		}

	}
	
		public function export_support_1($page = 1)
	{
		set_time_limit(0);
		$page_size=intval(ACCOUNT_PAGE_SIZE);
		$limit = ($page - 1)*$page_size.",".$page_size;
		$retrun=array('status'=>0,'info'=>"","url"=>'');
		if(!$GLOBALS['user_info'])
		{
			$jump_url=url("user#login");
			showErr('请先登录',0,$jump_url);
		}
		
		$deal_id = intval($_REQUEST['id']);
		$type = intval($_REQUEST['type']);
		$user_name = strim($_REQUEST['user_name']);
		$repay_status = intval($_REQUEST['repay_status']);
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($GLOBALS['user_info']['id']));
		
		if(!$deal_info)
		{
			$jump_url=url("account#support",array('id'=>$deal_id,'type'=>$type));
			showErr('项目未找到',0,$jump_url);
		}
		
		$where='';
		if($user_name !='')
		{
			$where .=" and d.user_name like '%".$user_name."%'";
		}
	
		if($repay_status >0)
		{
			switch($repay_status){
  			case 1:
  			$where .= " and d.repay_time >0";
  			break;
  			case 2:
  			$where .= " and d.repay_time <=0";
  			break;
  			case 3:
  			$where .= " and d.repay_time >0 and d.repay_make_time >0";
  			break;
  			}
		}
		
		$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 ".$where." order by d.create_time desc limit ".$limit);
		
		if($support_list)
		{
			register_shutdown_function(array(&$this, 'export_support_0'), $page+1);
			$support_value = array('id'=>'""', 'user_name'=>'""', 'deal_name'=>'""','total_price'=>'""','investment_stock_value'=>'""','pay_time'=>'""', 'repay_status'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","编号,投资人,商品名称,项目估值,投资金额,支付时间,回报状态");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
	    	foreach($support_list as $k=>$v)
			{
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['deal_name'] = '"' . iconv('utf-8','gbk',$v['deal_name']) . '"';
				
				if($v['invest_type'] ==0)
					$invest_type_val='询价：';
				elseif($v['invest_type'] ==1)
					$invest_type_val='领投：';
				elseif($v['invest_type'] ==2)
					$invest_type_val='跟投：';
				elseif($v['invest_type'] ==2)
					$invest_type_val='追加：';
					
				$order_value['investment_stock_value'] = '"' . iconv('utf-8','gbk',$invest_type_val.number_format($v['investment_stock_value'],2)."元") . '"';
				$order_value['total_price'] = '"' . iconv('utf-8','gbk',number_format($v['total_price'],2)."元") . '"';
				$order_value['pay_time'] = '"' . iconv('utf-8','gbk',to_date($v['pay_time'])) . '"';
				if($v['repay_time'] >0)
				{
					if($v['repay_make_time'] >0){
						$repay_status= "确认收到";
					}else{
						$repay_status= "未确认";
					}
				}else
				{
					$repay_status= "未发放";
				}
					
				$order_value['repay_status'] = '"' . iconv('utf-8','gbk',$repay_status) . '"';
				
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			header("Content-Disposition: attachment; filename=invest_order_list.csv");
	    	echo $content; 
		}
		else
		{
			if($page==1)
			{
				$jump_url=url("account#support",array('id'=>$deal_id,'type'=>$type));
				showErr('没有找到项目支持信息',0,$jump_url);
			}
		}

	}
	public function money_freeze()
	{
       
		$GLOBALS['tmpl']->assign("page_title","诚意金明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and amount <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and amount>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and amount>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and amount<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and amount<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		$type=intval($_REQUEST['type']);
 		if($type>0){
 			switch($type){
 				//冻结诚意金
 				case 1:
 				$condition.=" and status=1 ";
 				break;
 				//解冻诚意金
 				case 2:
  				$condition.=" and status=2 ";
 				break;
 				//申请解冻诚意金
 				case 3:
 				$condition.=" and status=3  ";
 				break;
 				 
 			}
 			$GLOBALS['tmpl']->assign('type',$type);
 			
  			$parameter[]="type=".$type;
 		}
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$money_freeze_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."money_freeze where platformUserNo = ".intval($GLOBALS['user_info']['id'])."   $condition order by create_time desc,id desc limit ".$limit);
 		foreach($money_freeze_list as $k=>$v)
		{
			$money_freeze_list[$k]['deal_name']= $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal where id=".$v['deal_id']);
		}
 		$money_freeze_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."money_freeze where platformUserNo = ".intval($GLOBALS['user_info']['id'])."  $condition ");
 		 
 		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($money_freeze_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('money_freeze_list',$money_freeze_list);
		
		$GLOBALS['tmpl']->display("account_money_freeze.html");
	}
	public function set_money_unfreeze(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		
		$money_unfreeze_id= intval($_REQUEST['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."money_freeze set status = 3,create_time=".NOW_TIME." where id = ".$money_unfreeze_id );

 		showSuccess("申请解冻成功",1,url("account#money_freeze"));		
	}
	public function set_money_freeze(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}

		$money_freeze_id= intval($_REQUEST['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."money_freeze set status = 1,create_time=".NOW_TIME." where id = ".$money_freeze_id );

 		showSuccess("取消申请成功",1,url("account#money_freeze"));
	
	}
	public function yeepay_recharge()
	{
       
		$GLOBALS['tmpl']->assign("page_title","第三方托管充值");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and amount <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and amount>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and amount>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and amount<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and amount<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$yeepay_recharge_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."yeepay_recharge where platformUserNo = ".intval($GLOBALS['user_info']['id'])." $condition order by create_time desc,id desc limit ".$limit);
 		
 		$yeepay_recharge_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."money_freeze where platformUserNo = ".intval($GLOBALS['user_info']['id'])."  $condition ");
 		 
 		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($yeepay_recharge_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('yeepay_recharge_list',$yeepay_recharge_list);
		
		$GLOBALS['tmpl']->display("account_yeepay_recharge.html");
	}
	public function yeepay_withdraw()
	{
       
		$GLOBALS['tmpl']->assign("page_title","第三方托管提现");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and amount <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and amount>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and amount>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and amount<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and amount<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$yeepay_withdraw_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."yeepay_withdraw where platformUserNo = ".intval($GLOBALS['user_info']['id'])." and code = 1 $condition order by create_time desc,id desc limit ".$limit);
  		$yeepay_withdraw_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."yeepay_withdraw where platformUserNo = ".intval($GLOBALS['user_info']['id'])." and code = 1  $condition ");
  		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($yeepay_withdraw_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('yeepay_withdraw_list',$yeepay_withdraw_list);
		
		$GLOBALS['tmpl']->display("account_yeepay_withdraw.html");
	}
	public function yeepay_money_freeze()
	{
       
		$GLOBALS['tmpl']->assign("page_title","诚意金明细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$condition =" and amount <>0";
		$parameter=array();
		$day=intval(strim($_REQUEST['day']));
 		//$day=intval(str_replace("ne","-",$day));
 		if($day!=0){
 			$now_date=to_timespan(to_date(NOW_TIME,'Y-m-d'),'Y-m-d');
		 	$last_date=$now_date+$day*24*3600;
 		 	if($day>0){
		 		$condition.=" and log_time>=$now_date and  log_time<$last_date  ";
		 	}else{
		 		$condition.=" and log_time>$last_date and  log_time<=$now_date  ";
		 	}
		 	$GLOBALS['tmpl']->assign('day',$day);
 		 	
		 	$parameter[]="day=".$day;
 		}
 		$begin_time=strim($_REQUEST['begin_time']);
 		if($begin_time!=0){
 			$begin_time=to_timespan($begin_time,'Y-m-d');
 			$condition.=" and log_time>=$begin_time ";
 			$GLOBALS['tmpl']->assign('begin_time',to_date($begin_time,'Y-m-d'));
 			$parameter[]="begin_time=".to_date($begin_time,'Y-m-d');
 		}
 		$end_time=strim($_REQUEST['end_time']);
 		if($end_time!=0){
 			$end_time=to_timespan($end_time,'Y-m-d');
 			$condition.=" and log_time<$end_time ";
 			$GLOBALS['tmpl']->assign('end_time',to_date($end_time,'Y-m-d'));
  			
 			$parameter[]="end_time=".to_date($end_time,'Y-m-d');
 		}
 		if($_REQUEST['begin_money']==='0'){
 			$condition.=" and amount>=0 ";
 			$GLOBALS['tmpl']->assign('begin_money',0);
   			$parameter[]="begin_money=0";
 		}else{
 			$begin_money=floatval($_REQUEST['begin_money']);
	 		if($begin_money!=0){
	 			$condition.=" and amount>=$begin_money ";
	 			$GLOBALS['tmpl']->assign('begin_money',$begin_money);
	 			
	  			$parameter[]="begin_money=".$begin_money;
	 		}
 		}
 	 
 		if($_REQUEST['end_money']==='0'){
  			$condition.=" and amount<=0 ";
 			$GLOBALS['tmpl']->assign('end_money',0);
   			$parameter[]="end_money=0";
 		}else{
 			$end_money=floatval($_REQUEST['end_money']);
	 		if($end_money!=0){
	 			$condition.=" and amount<=$end_money ";
	 			$GLOBALS['tmpl']->assign('end_money',$end_money);
	 			
	  			$parameter[]="end_money=".$end_money;
	 		}
 		}
 		
 		$type=intval($_REQUEST['type']);
 		if($type>0){
 			switch($type){
 				//冻结诚意金
 				case 1:
 				$condition.=" and status=1 ";
 				break;
 				//解冻诚意金
 				case 2:
  				$condition.=" and status=2 ";
 				break;
 				//申请解冻诚意金
 				case 3:
 				$condition.=" and status=3  ";
 				break;
 				 
 			}
 			$GLOBALS['tmpl']->assign('type',$type);
 			
  			$parameter[]="type=".$type;
 		}
 		
 		$more_search=intval($_REQUEST['more_search']);
 		$GLOBALS['tmpl']->assign('more_search',$more_search);
 		$parameter[]="more_search=".$more_search;
		
		$yeepay_money_freeze_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."money_freeze where platformUserNo = ".intval($GLOBALS['user_info']['id'])." and pay_type = 0 $condition order by create_time desc,id desc limit ".$limit);
 		foreach($yeepay_money_freeze_list as $k=>$v)
		{
			$yeepay_money_freeze_list[$k]['deal_name']= $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal where id=".$v['deal_id']);
		}
 		$yeepay_money_freeze_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."money_freeze where platformUserNo = ".intval($GLOBALS['user_info']['id'])." and pay_type = 0  $condition ");
 		 
 		$parameter_str="&".implode("&",$parameter);
 		
 		$page = new Page($yeepay_money_freeze_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('yeepay_money_freeze_list',$yeepay_money_freeze_list);
		
		$GLOBALS['tmpl']->display("account_yeepay_money_freeze.html");
	}
	//投后分红列表
	public function share_bonus(){
		$GLOBALS['tmpl']->assign("page_title","分红列表");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		$deal_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where  is_delete = 0 and is_effect = 1 and is_success = 1 and pay_end_time < ".NOW_TIME." and (stock_type = 1 or (stock_type = 3 and  share_fee_descripe != ''))  and type = 1 and end_time < ".NOW_TIME." and user_id = ".intval($GLOBALS['user_info']['id'])." limit ".$limit);
		foreach($deal_info as $k=>$v){
			$deal_info[$k]['user_bonus']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus where deal_id = ".$v['id']." and type = 0 order by id");
			$deal_info[$k]['support_list'] = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$v['id']." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");	
			foreach($deal_info[$k]['user_bonus'] as $kk=>$vv){
				$deal_info[$k]['user_bonus'][$kk]['begin_time'] =to_date($vv['begin_time'],'Y-m-d');
				$deal_info[$k]['user_bonus'][$kk]['end_time'] =to_date($vv['end_time'],'Y-m-d');
			}
			$deal_info[$k]['bonus_num']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 0 and status = 1 and deal_id = ".$v['id']);
			$deal_info[$k]['total_bonus_money']=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_bonus where type = 0 and status = 1 and deal_id = ".$v['id']);
		}
		$deal_count = $GLOBALS['db']->getOne("select  count(distinct(d.id)) from ".DB_PREFIX."deal d,".DB_PREFIX."deal_order as do where  d.is_delete = 0 and d.is_effect = 1 and d.is_success = 1 and (d.stock_type = 1 or (d.stock_type = 3 and  d.share_fee_descripe != '')) and d.type = 1 and d.end_time < ".NOW_TIME." and d.user_id = ".intval($GLOBALS['user_info']['id'])." and d.id=do.deal_id and do.order_status = 3 and do.is_refund = 0 and do.invest_id >0");
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('deal_info',$deal_info);
	
		$GLOBALS['tmpl']->display("account_share_bonus.html");
	}
	//投后分红申请
	public function share_bonus_apply()
	{
		$GLOBALS['tmpl']->assign("page_title","分红申请");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST['id']);
		if($id>0){
			$user_bonus = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bonus where id =".$id);
			$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
			$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
			$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
			$user_bonus_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id =".$id);
			$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$user_bonus['deal_id']);
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$user_bonus['deal_id']." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");
		}
		else{
			$deal_id = $_REQUEST['deal_id'];
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$deal_id);
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");	
		}
		foreach($support_list as $k=>$v){	
					//项目金额
					$support_list[$k]['stock_value'] =number_format($deal_info['limit_price'],2);
					$support_list[$k]['money'] =$v['total_price'];
					//用户所占股份
	 				$support_list[$k]['user_stock']= number_format(($support_list[$k]['money']/$deal_info['limit_price'])*100,2);	
	 				$support_list[$k]['notice_sn'] = to_date(NOW_TIME,"YmdHis").$v['id'];	
		}
		
		$GLOBALS['tmpl']->assign('support_list',$support_list);
		$GLOBALS['tmpl']->display("account_share_bonus_apply.html");	
	}
	public function save_share_bonus()
	{
		//print_r($_REQUEST);exit;
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		
		$data['year'] = intval($_REQUEST['year']);
		$data['number'] = trim($_REQUEST['number']);
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where year =".$data['year']." and type = 0 and number=".$data['number']." and deal_id =".$deal_id);
		
		if($id==0 && $count >0){
			showErr("该分红已存在！",$ajax);
		}
		$data['money'] = floatval($_REQUEST['money']);
		if($data['money']<=0){
			showErr("请输入本期分红金额",$ajax);
		}
		$data['average_monthly_returns'] = floatval($_REQUEST['average_monthly_returns']);
		$data['average_annualized_return'] = floatval($_REQUEST['average_annualized_return']);
		$data['begin_time'] = strtotime($_REQUEST['begin_time']);
		$data['end_time'] = strtotime($_REQUEST['end_time']);
		$data['descripe'] = trim($_REQUEST['descripe']);
		$data['deal_id'] = intval($_REQUEST['deal_id']);
		$data['user_id'] = intval($GLOBALS['user_info']['id']);
		$data['status'] = 0;
		$data['type'] = 0;
		$data1=array();
		$share_bonus=$_REQUEST['share_bonus_array'];
		if($id==0)
		{			
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());			
			if($result_id>0)
			{
				foreach($share_bonus as $k=>$v){	
					$data1['notice_sn'] =$v['0'];
					$data1['investor'] =$v['1'];
					$data1['investor_money'] =$v['2'];
					$data1['percentage_shares'] =$v['3'];
					$data1['amount'] =$v['4'];
					$data1['user_bonus_id'] =$result_id;
					$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
					$data1['user_id'] = intval($GLOBALS['user_info']['id']);
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"INSERT","","SILENT");	
					$user_bonus_id = intval($GLOBALS['db']->insert_id());
				}
				showSuccess("提交审核成功",$ajax,url("account#share_bonus"));
			}
			else
			{
				showErr("提交审核失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"UPDATE","id=".$id,"SILENT");
			$data1=array();
			$share_bonus=$_REQUEST['share_bonus_array'];			
			$result_id=$_REQUEST['id'];
			foreach($share_bonus as $k=>$v){
					
				$data1['notice_sn'] =$v['0'];
				$data1['investor'] =$v['1'];
				$data1['investor_money'] =$v['2'];
				$data1['percentage_shares'] =$v['3'];
				$data1['amount'] =$v['4'];
				$data1['id'] =$v['5'];
				$data1['user_bonus_id'] =$result_id;
				$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
				$data1['user_id'] = intval($GLOBALS['user_info']['id']);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"UPDATE","id=".$data1['id'],"SILENT");	
			}
			showSuccess("提交审核成功",$ajax,url("account#share_bonus"));
		}
	}
	//投后分红详细
	public function share_bonus_detail(){
		$GLOBALS['tmpl']->assign("page_title","分红详细");		
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = $_REQUEST['id'];
		$user_bonus=$GLOBALS['db']->getRow("select ub.*,d.name as deal_name from ".DB_PREFIX."user_bonus as ub left join ".DB_PREFIX."deal as d on ub.deal_id = d.id  where ub.id = ".$id);	
		$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
		$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
		$user_bonus_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id = ".$id." order by id");
		$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
		$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
		$GLOBALS['tmpl']->display("account_share_bonus_detail.html");
	}
	//投后利息发放列表
	public function fixed_interest(){
		$GLOBALS['tmpl']->assign("page_title","利息发放列表");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		$deal_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where  is_delete = 0 and is_effect = 1 and is_success = 1 and pay_end_time < ".NOW_TIME." and (stock_type = 2 or stock_type = 3)  and type = 1 and end_time < ".NOW_TIME." and user_id = ".intval($GLOBALS['user_info']['id'])." limit ".$limit);
		foreach($deal_info as $k=>$v){
			$deal_info[$k]['user_bonus']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus where deal_id = ".$v['id']." and type = 1  order by id");
			$deal_info[$k]['support_list'] = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$v['id']." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");	
			foreach($deal_info[$k]['user_bonus'] as $kk=>$vv){
				$deal_info[$k]['user_bonus'][$kk]['begin_time'] =to_date($vv['begin_time'],'Y-m-d');
				$deal_info[$k]['user_bonus'][$kk]['end_time'] =to_date($vv['end_time'],'Y-m-d');
			}
			$deal_info[$k]['bonus_num']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 1 and status = 1 and deal_id = ".$v['id']);
			$deal_info[$k]['total_bonus_money']=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_bonus where type = 1 and status = 1 and deal_id = ".$v['id']);
		}
		$deal_count = $GLOBALS['db']->getOne("select  count(distinct(d.id)) from ".DB_PREFIX."deal d,".DB_PREFIX."deal_order do where  d.is_delete = 0 and d.is_effect = 1 and d.is_success = 1 and (d.stock_type = 2 or d.stock_type = 3)  and d.type = 1 and d.end_time < ".NOW_TIME." and d.user_id = ".intval($GLOBALS['user_info']['id'])." and d.id=do.deal_id and do.order_status = 3 and do.is_refund = 0 and do.invest_id >0");
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('deal_info',$deal_info);
		
		$GLOBALS['tmpl']->display("account_fixed_interest.html");
	}
	//投后利息发放申请
	public function fixed_interest_apply(){
		$GLOBALS['tmpl']->assign("page_title","利息发放申请");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST['id']);
		if($id>0){
			$user_bonus = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bonus where id =".$id);
			$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
			$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
			$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
			$user_bonus_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id =".$id);
			$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$user_bonus['deal_id']);
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$user_bonus['deal_id']." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");
		}
		else{
			$deal_id = $_REQUEST['deal_id'];
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$deal_id);
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");	
		}
		foreach($support_list as $k=>$v){	
					//项目金额
					$support_list[$k]['stock_value'] =number_format($deal_info['limit_price'],2);
					$support_list[$k]['money'] =$v['total_price'];
					//用户所占股份
	 				$support_list[$k]['user_stock']= number_format(($support_list[$k]['money']/$deal_info['limit_price'])*100,2);	
	 				$support_list[$k]['notice_sn'] = to_date(NOW_TIME,"YmdHis").$v['id'];	
		}
		
		$GLOBALS['tmpl']->assign('support_list',$support_list);
		$GLOBALS['tmpl']->display("account_fixed_interest_apply.html");	
	}
	public function save_fixed_interest()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		
		$data['year'] = intval($_REQUEST['year']);
		$data['number'] = trim($_REQUEST['number']);
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where year =".$data['year']." and type = 1 and number=".$data['number']." and deal_id =".$deal_id);
		
		if($id==0 && $count >0){
			showErr("该分红已存在！",$ajax);
		}
		$data['money'] = floatval($_REQUEST['money']);
		if($data['money']<=0){
			showErr("请输入本期分红金额",$ajax);
		}
		$data['return_cycle'] = floatval($_REQUEST['return_cycle']);
		$data['average_annualized_return'] = floatval($_REQUEST['average_annualized_return']);
		
		$data['begin_time'] = strtotime($_REQUEST['begin_time']);
		$data['end_time'] = strtotime($_REQUEST['end_time']);
		$data['descripe'] = trim($_REQUEST['descripe']);
		$data['deal_id'] = intval($_REQUEST['deal_id']);
		$data['user_id'] = intval($GLOBALS['user_info']['id']);
		$data['status'] = 0;
		$data['type'] = 1;
		
		$data1=array();
		$share_bonus=$_REQUEST['share_bonus_array'];

		if($id==0)
		{
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());			
			if($result_id>0)
			{
				foreach($share_bonus as $k=>$v){	
					$data1['notice_sn'] =$v['0'];
					$data1['investor'] =$v['1'];
					$data1['investor_money'] =$v['2'];
					$data1['percentage_shares'] =$v['3'];
					$data1['amount'] =$v['4'];
					$data1['user_bonus_id'] =$result_id;
					$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
					$data1['user_id'] = intval($GLOBALS['user_info']['id']);
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"INSERT","","SILENT");	
					$user_bonus_id = intval($GLOBALS['db']->insert_id());
				}
				showSuccess("提交审核成功",$ajax,url("account#fixed_interest"));
			}
			else
			{
				showErr("提交审核失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"UPDATE","id=".$id,"SILENT");
			$data1=array();
			$share_bonus=$_REQUEST['share_bonus_array'];
			
			$result_id=$_REQUEST['id'];
			foreach($share_bonus as $k=>$v){
					
				$data1['notice_sn'] =$v['0'];
				$data1['investor'] =$v['1'];
				$data1['investor_money'] =$v['2'];
				$data1['percentage_shares'] =$v['3'];
				$data1['amount'] =$v['4'];
				$data1['id'] =$v['5'];
				$data1['user_bonus_id'] =$result_id;
				$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
				$data1['user_id'] = intval($GLOBALS['user_info']['id']);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"UPDATE","id=".$data1['id'],"SILENT");	
			}
			showSuccess("提交审核成功",$ajax,url("account#fixed_interest"));
		}
	}
	//投后利息发放详细
	public function fixed_interest_detail(){
		$GLOBALS['tmpl']->assign("page_title","利息发放详细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = $_REQUEST['id'];
		$user_bonus=$GLOBALS['db']->getRow("select ub.*,d.name as deal_name from ".DB_PREFIX."user_bonus as ub left join ".DB_PREFIX."deal as d on ub.deal_id = d.id  where ub.id = ".$id);
		$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
		$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
		$user_bonus_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id = ".$id." order by id");
		$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
		$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
		$GLOBALS['tmpl']->display("account_fixed_interest_detail.html");
	}
	//投后分红，利息，买房收益删除
	public function del_share_bonus()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_bonus where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_bonus_list where user_id = ".intval($GLOBALS['user_info']['id'])." and user_bonus_id = ".$id);
							
		app_redirect_preview();
	}
	public function lottery(){
		$ajax=$_REQUEST['ajax'];
		$return=array('status'=>0,'info'=>'',"url"=>'','html'=>'');
		$GLOBALS['tmpl']->assign("page_title","抽奖列表");
		if(!$GLOBALS['user_info'])
		{
			if($ajax){
				$return['status']=-1;
				ajax_return($return);
			}else{
				app_redirect(url("user#login"));
			}
			
		}
		$deal_id =$param['id']= intval($_REQUEST['id']);
		$lottery_sn =$param['lottery_sn']= strim($_REQUEST['lottery_sn']);
		//$deal_item_id =$param['deal_item_id']= intval($_REQUEST['deal_item_id']);
		$user_name=$param['user_name'] = strim($_REQUEST['user_name']);
		
		$GLOBALS['tmpl']->assign('param',$param);	
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".intval($GLOBALS['user_info']['id'])."");
		if(!$deal_info)	
		{
			if($ajax){
				$return['info']='';
				$return['url']=url("account#project");
				ajax_return($return);
			}else{
				app_redirect(url("account#project"));
			}
	
		}
		
		$where=" lot.deal_id=".$deal_id." and lot.is_winner < 3";
		if($user_name !='')
			$where .=" and lot.user_name like '%".$user_name."%' ";
		//if($deal_item_id >0)
			//$where .=" and lot.deal_item_id=".$deal_item_id."";
		
		if($lottery_sn !='')
			$where .=" and lot.lottery_sn='".$lottery_sn."'";
		
		
		$list_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order_lottery as lot where ".$where."");
		if($list_count)
		{
			$page = intval($_REQUEST['p']);
			if(!$page) $page=1;
			$page_size=ACCOUNT_PAGE_SIZE;
			$limit = (($page-1)*$page_size).",".$page_size;
			$sql="select lot.*,di.price,ord.repay_time,ord.repay_make_time from ".DB_PREFIX."deal_order_lottery as lot " .
				 "left join ".DB_PREFIX."deal_item as di on di.id=lot.deal_item_id " .
				 "left join ".DB_PREFIX."deal_order as ord on ord.id=lot.order_id " .
				 "where ".$where." order by lot.is_winner asc,lot.id desc limit ".$limit." ";
			$list=$GLOBALS['db']->getAll($sql);
			
			if($param)
			{
				$parameter_str='';
				foreach($param as $k=>$v)
				{
					$parameter_str .='&'.$k.'='.$v.'';
				}
			}
			
	 		$page = new Page($list_count,$page_size,$parameter_str);   //初始化分页对象 		
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);
			$GLOBALS['tmpl']->assign("project_deal_type",$deal_info['type']);
			$GLOBALS['tmpl']->assign('list',$list); 
		}
		
		$GLOBALS['tmpl']->assign('deal_info',$deal_info);
		if($ajax)
		{
			$return['html']=$GLOBALS['tmpl']->fetch("account_lottery_ajax.html");
			$return['status']=1;
			ajax_return($return);
		}
		else
		{	
			$GLOBALS['tmpl']->display("account_lottery.html");
		}
	}
	public function dy_print(){
		$GLOBALS['tmpl']->assign("page_title","股权认购合同打印");
		$id = intval($_REQUEST['order_id']);
		$user_id = intval($_REQUEST['user_id']);
		$order_info = $GLOBALS['db']->getRow("select deo.*,d.type as type,d.transfer_share as transfer_share,d.limit_price as limit_price ,deo.type as dtype from ".DB_PREFIX."deal_order as deo LEFT JOIN ".DB_PREFIX."deal as d on deo.deal_id = d.id  where deo.id = ".$id." and deo.user_id = ".$user_id);
		if(!$order_info)
		{
			showErr("无效的项目支持",0,get_gopreview());
		}
		if($order_info['type']==1 || $order_info['type']==4){
			

			if($order_info['dtype']==6){
					
				$stock_transfer=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer  where id=".$order_info['deal_item_id']);
					
				//用户所占股份
				$order_info['user_stock']= number_format(($stock_transfer['stock_value']/$order_info['limit_price'])*$order_info['transfer_share'],2);
				//项目金额
				$order_info['stock_value'] =number_format($stock_transfer['stock_value'],2);
					
			
			}else{
			//用户所占股份
			$order_info['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);			
			//项目金额
			$order_info['stock_value'] =number_format($order_info['limit_price'],2);
			}
		}
		
		$deal_id = $_REQUEST['id'];
		$contract_content =$GLOBALS['db']->getOne("select co.content from ".DB_PREFIX."deal as d left join ".DB_PREFIX."contract as co on d.stock_subscript_id = co.id  where d.id = ".$deal_id);
		
		$SITE_TITLE = app_conf("SITE_NAME");
		$GLOBALS['tmpl']->assign("SITE_TITLE",$SITE_TITLE);
		//用户所占股份
		$GLOBALS['tmpl']->assign("user_stock",$order_info['user_stock']);
		//投资金额
		$GLOBALS['tmpl']->assign("total_price",$order_info['total_price']);
		$contract_content = $GLOBALS['tmpl']->fetch("str:".$contract_content);
		$GLOBALS['tmpl']->assign('contract_content',$contract_content);
		$GLOBALS['tmpl']->display("account_dy_print.html");
	}
	
	
	public function dt_print(){
		$GLOBALS['tmpl']->assign("page_title","股权转让合同打印");
		$deal_id = $_REQUEST['id'];
		$stock_transfer_id = $_REQUEST['stock_transfer_id'];
		$contract_content =$GLOBALS['db']->getOne("select co.content from ".DB_PREFIX."deal as d left join ".DB_PREFIX."contract as co on d.stock_transfer_id = co.id  where d.id = ".$deal_id);
		$SITE_TITLE = app_conf("SITE_NAME");
		$GLOBALS['tmpl']->assign("SITE_TITLE",$SITE_TITLE);
		
		
		$stock_transfer_info =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer  where id = ".$stock_transfer_id);
		$deal_info =$GLOBALS['db']->getRow("select de.*,u.company from ".DB_PREFIX."deal as de join ".DB_PREFIX."user as u on de.user_id=u.id  where de.id = ".$deal_id);
		
		$seller_info =$GLOBALS['db']->getRow("select user_name,email,intro,ex_real_name,ex_contact,identify_number,company,mobile from ".DB_PREFIX."user  where id = ".$stock_transfer_info['user_id']);
		$purchaser_info =$GLOBALS['db']->getRow("select user_name,email,intro,ex_real_name,ex_contact,identify_number,company,mobile from ".DB_PREFIX."user  where id = ".$stock_transfer_info['purchaser_id']);
		
		$stock_transfer_info['user_stock']=number_format(($stock_transfer_info['stock_value']/$deal_info['limit_price'])*$deal_info['transfer_share'],2);
		
		
		$SITE_TITLE = app_conf("SITE_NAME");
		$GLOBALS['tmpl']->assign("SITE_TITLE",$SITE_TITLE);
		
		$GLOBALS['tmpl']->assign('deal',$deal_info);
		$GLOBALS['tmpl']->assign('transfer',$stock_transfer_info);
		$GLOBALS['tmpl']->assign('seller_info',$seller_info);
		$GLOBALS['tmpl']->assign('purchaser_info',$purchaser_info);
		$contract_content = $GLOBALS['tmpl']->fetch("str:".$contract_content);
		$GLOBALS['tmpl']->assign('contract_content',$contract_content);
		//$GLOBALS['tmpl']->display("account_ht_print.html");
		$GLOBALS['tmpl']->display("account_dy_print.html");
	}
	
	//股权转让
	public function stock_transfer_out()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		
		//过期处理
		$this->stock_transfer_off();
		 
		$user_id=$GLOBALS['user_info']['id'];
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		//获取有效列表，4为已删除
		$stock_transfer_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."stock_transfer where user_id=".$user_id." and status <4 order by id desc  limit ".$limit);
		foreach($stock_transfer_list as $k=>$v){
			if ($v['deal_order_id']>0) {
				$deal_order=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id= ".$v['deal_order_id']);
				$stock_transfer_list[$k]['deal_id']=$deal_order['deal_id'];
				$stock_transfer_list[$k]['deal_status']=$deal_order['order_status'];
				$stock_transfer_list[$k]['deal_is_success']=$deal_order['is_success'];
				$stock_transfer_list[$k]['deal_status']=$deal_order['order_status'];
				$stock_transfer_list[$k]['deal_share_status']=$deal_order['share_status'];//判断是否发放
				$stock_transfer_list[$k]['deal_repay_make_time']=$deal_order['repay_make_time'];//回报更新时间
				$stock_transfer_list[$k]['deal_pay_time']=$deal_order['pay_time'];
			}	
			
		
		}

		$deal_count = $GLOBALS['db']->getOne("select  count(id) from ".DB_PREFIX."stock_transfer where user_id=".$user_id." and status <4  ");
		$page = new Page($deal_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign("stock_transfer_list",$stock_transfer_list);
		$GLOBALS['tmpl']->assign("page_title","我的股权转让");
		$GLOBALS['tmpl']->display("account_stock_transfer_out.html");

	}
	//股权转让
	public function stock_transfer_edit()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		
		
		$id=intval($_REQUEST['id']);
		$user_id=$GLOBALS['user_info']['id'];
		$stock_transfer_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where id= ".$id." and user_id=".$user_id);		
		if(!$stock_transfer_info)
		{
			showErr("无效的项目",0,get_gopreview());
		}
		
		$invest_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id= ".$stock_transfer_info['invest_id']." and user_id=".$user_id);
		if(!$invest_info)
		{
			showErr("无效的项目",0,get_gopreview());
		}
		$order_info = $GLOBALS['db']->getRow("select deo.*,d.type as type,d.transfer_share as transfer_share,d.limit_price as limit_price,d.invote_mini_money as invote_mini_money from ".DB_PREFIX."deal_order as deo LEFT JOIN ".DB_PREFIX."deal as d on deo.deal_id = d.id  where deo.id = ".$invest_info['order_id']." and deo.user_id = ".intval($GLOBALS['user_info']['id']));
		if(!$order_info)
		{
			showErr("无效的项目",0,get_gopreview());
		}
		
		//用户所占股份
		$order_info['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);
		//项目金额
		$order_info['stock_value'] =number_format($order_info['limit_price'],2);
		//用户所占股份数量
		$order_info['user_num']=number_format($order_info['total_price']/$order_info['invote_mini_money']);
		
		$GLOBALS['tmpl']->assign("stock_transfer_info",$stock_transfer_info);
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		$GLOBALS['tmpl']->assign("page_title","股权转让设置");
		$GLOBALS['tmpl']->display("account_stock_transfer_edit.html");
	}
	
	//添加股权转让
	public function stock_transfer_add()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
				
		$id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select deo.*,d.type as type,d.transfer_share as transfer_share,d.limit_price as limit_price,d.invote_mini_money as invote_mini_money from ".DB_PREFIX."deal_order as deo LEFT JOIN ".DB_PREFIX."deal as d on deo.deal_id = d.id  where deo.id = ".$id." and deo.user_id = ".intval($GLOBALS['user_info']['id']));

		if(!$order_info)
		{
			showErr("无效的项目",0,get_gopreview());
		}
		//判断是否有交易中的做跳转
		/*$stock_transfer_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where invest_id = ".$order_info['invest_id']." and (status=0 or (status=1 and is_success=0 and end_time>".NOW_TIME."))");
		
		if($stock_transfer_info)
		{			
			showErr("已存在未完成的转让",0,url("account#stock_transfer_out"));
		}*/
		/*
		//用户所占股份
		$order_info['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);
		//项目金额
		$order_info['stock_value'] =number_format($order_info['limit_price'],2);
		//用户所占股份数量
		$order_info['user_num']=number_format($order_info['total_price']/$order_info['invote_mini_money']);*/
		
		$invest_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where order_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']) );
		//用户所占股份
		$order_info['user_stock']= number_format(($invest_info['money']/$order_info['limit_price'])*$order_info['transfer_share'],2);
		//项目金额
		$order_info['stock_value'] =number_format($order_info['limit_price'],2);
		//用户所占股份数量
		$order_info['user_num']=number_format($invest_info['money']/$order_info['invote_mini_money']);		
		//用户所占项目金额
		$order_info['total_price']=number_format($invest_info['money'],2);
		
		
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		$GLOBALS['tmpl']->assign("now",NOW_TIME);
		$GLOBALS['tmpl']->assign("page_title","股权转让设置");
		$GLOBALS['tmpl']->display("account_stock_transfer_add.html");
	}
	
	//添加股权转让
	public function stock_transfer_insert()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info']){
			$result['status'] = 0;
			$result['info'] = "请先登陆！";
			$result['jump'] = url("user#login");
			ajax_return($result);
		}
		$now_time = get_gmtime();
		$data['user_id']=intval($GLOBALS['user_info']['id']);
		$data['user_name']=$GLOBALS['user_info']['user_name'];
		$data['deal_name']=$_REQUEST['deal_name'];		
		$data['invest_id']=intval($_REQUEST['invest_id']);
		$idata = $GLOBALS['db']->getRow("select ilist.*,deal.invote_mini_money as invote_mini_money ,deal.cate_id as cate_id from ".DB_PREFIX."investment_list as ilist left join ".DB_PREFIX."deal as deal on deal.id=ilist.deal_id where ilist.id=".$data['invest_id']);
		
		$data['price']=floatval($_REQUEST['price']);
		$data['num']=intval($_REQUEST['num']);
		$data['day']=intval($_REQUEST['day']);
		$data['stock_value']=floatval($idata['invote_mini_money']*$data['num']);//原始价值
	 
		$data['purchaser_id']=0;
		$data['purchaser_name']="";		
		$data['create_time']= $now_time;
		$data['is_success']=0;

		
		/*$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where user_id=".$data['user_id']." and invest_id =".$data['invest_id']." and (status=0 or (status=1 and is_success=0 and end_time>".$data['create_time']."))");
		if ($order_info) {
			$result['status'] = 0;
			$result['info'] = "提交失败,已存在转让中的交易！";
			ajax_return($result);
		}*/
		
		//数据分裂
		$idata['money']=floatval($idata['money'])-floatval($data['stock_value']);
		$idata['num']=intval($idata['num'])-intval($data['num']);
		
		if(intval($idata['num'])<0){
			$result['status'] = 0;
			$result['info'] = "提交失败,出售的份数不足！";
			ajax_return($result);
		
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$idata,"UPDATE","id=".$data['invest_id']);
			
		//添加$investment_list2
		$investment_list['type'] = 4;
		$investment_list['money'] = $data['stock_value'];
		$investment_list['status'] = 1;
		$investment_list['user_id'] = $data['user_id'];
		$investment_list['deal_id'] = $idata['deal_id'];
		$investment_list['create_time'] = $now_time;
		$investment_list['investor_money_status'] = 1;
		$investment_list['order_id'] = 0;
		$investment_list['num'] = $data['num'];
		$investment_list['stock_transfer_value'] = $data['price'];
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list,"INSERT","","SILENT");
		$investment_id = $GLOBALS['db']->insert_id();
		$data['invest_id_2']=$investment_id;
		
		//是否需要审批
		//$idata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id=".$data['invest_id']);
		//$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$idata['deal_id']);
		$data['cate_id']=$idata['cate_id'];
		
		if(app_conf("STOCK_TRANSFER_IS_VERIFY")==0){
			$data['begin_time']=$now_time;
			$data['end_time']=$now_time+$data['day']*86400;
			$data['status']=1;
			$data['is_edit']=0;
		}else{
			$data['status']=0;
			$data['is_edit']=0;
		}
		
		$list=$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"INSERT","","SILENT");
		if (false !== $list) {
			$result['status'] = 1;
			$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_out";
			ajax_return($result);
		}else{
			$result['status'] = 0;
			$result['info'] = "提交失败";
			ajax_return($result);
		}
		
	}
	
	//股权转让更新
	public function stock_transfer_update()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		$ajax = intval($_REQUEST['ajax']);
		
		if(!$GLOBALS['user_info']){
			$result['status'] = 0;
			$result['info'] = "请先登陆！";
			$result['jump'] = url("user#login");
			ajax_return($result);
		}
		
		$id=intval($_REQUEST['id']);	
		$stock_transfer = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where id=".$id);
		
		$data['price']=$_REQUEST['price'];
		$data['num']=intval($_REQUEST['num']);
		$data['day']=intval($_REQUEST['day']);
		//$data['stock_value']=$_REQUEST['stock_value'];//原始价值
		$data['status']=0;				
		
		/*if($data['price']==$stock_transfer['price'] && $data['num']==$stock_transfer['num'] && $data['day']==$stock_transfer['day']){
			$result['status'] = 1;
			$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_out";
			ajax_return($result);	
		}*/
		
		//处理数据
		$investment_list_2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$stock_transfer['invest_id_2']);
		$investment_list_1 = $GLOBALS['db']->getRow("select ilist.*,deal.invote_mini_money as invote_mini_money from ".DB_PREFIX."investment_list as ilist left join ".DB_PREFIX."deal as deal on deal.id=ilist.deal_id where ilist.id= ".$stock_transfer['invest_id']);
		
		$data['stock_value']=floatval($investment_list_1['invote_mini_money']*$data['num']);//原始价值
		
		$investment_list_1['money'] = floatval($investment_list_1['money']) + floatval($investment_list_2['money']) - floatval($data['stock_value']);
		$investment_list_1['num'] = $investment_list_1['num'] + $investment_list_2['num']-intval($data['num']);
		
		if(intval($investment_list_1['num'])<0){
			$result['status'] = 0;
			$result['info'] = "提交失败,出售的份数不足！";
			ajax_return($result);
		
		}
		
		$investment_list_2['money'] = $data['stock_value'];
		$investment_list_2['num'] = $data['num'];
		$investment_list_2['stock_transfer_value'] = $data['price'];
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_1,"UPDATE","id=".$stock_transfer['invest_id']);
		$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_2,"UPDATE","id=".$stock_transfer['invest_id_2']);
		
		$data['is_edit']=0;
		$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"UPDATE","id=".$id);
		if($GLOBALS['db']->affected_rows()>0){
			$result['status'] = 1;
			$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_out";
			ajax_return($result);			
		}else {
			$result['status'] = 0;
			$result['info'] = "提交失败";
			ajax_return($result);
			
		}
		
	}
	
	//股权转让删除
	public function stock_transfer_delete()
	{
		
			
		$user_id=$GLOBALS['user_info']['id'];
		
		$id=intval($_REQUEST['id']);
		
		$stock_transfer_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where user_id=".$user_id." and id =".$id);

		if (intval($stock_transfer_info['deal_order_id'])>0) {
				
			showErr("乙方已付款，不可删除",0,url("account#stock_transfer_out"));
		}
		
		$data['status']=4;
		$data['is_edit']=0;
		$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"UPDATE","id=".$id);
		if($GLOBALS['db']->affected_rows()>0){
			if($stock_transfer_info['invest_id_2']>0){
				//处理数据
				$investment_list_2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$stock_transfer_info['invest_id_2']);
				
				$investment_list_1 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$stock_transfer_info['invest_id']);

				
				if(intval($investment_list_2['type'])==4){
					$investment_list_1['money'] = $investment_list_1['money'] + $investment_list_2['money'];
					$investment_list_1['num'] = $investment_list_1['num'] + $investment_list_2['num'];
					
					$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_1,"UPDATE","id=".$stock_transfer_info['invest_id']);
					
					$investment_list_2['type']=6;
					$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_2,"UPDATE","id=".$stock_transfer_info['invest_id_2']);
				}
				
			}	
			
				
			showSuccess("删除成功",0,url("account#stock_transfer_out"));
		}else {
			
			showErr("删除失败",0,url("account#stock_transfer_out"));
		}
	}
	
	//股权转让取消
	public function stock_transfer_cancel()
	{
		//$ajax = intval($_REQUEST['ajax']);
			
		$user_id=$GLOBALS['user_info']['id'];
		
		$id=intval($_REQUEST['id']);
		$stock_transfer_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where user_id=".$user_id." and id =".$id);
		
		if (intval($stock_transfer_info['deal_order_id'])>0) {
			//已经存在付款
			/*$result['status'] = 0;
			$result['info'] = "乙方已付款，不可取消";
			ajax_return($result);*/
			
			showErr("乙方已付款，不可取消",0,url("account#stock_transfer_out"));
		}
		
		$data['status']=3;	
		$data['is_edit']=0;
		$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"UPDATE","id=".$id);
		if($GLOBALS['db']->affected_rows()>0){
			
			if($stock_transfer_info['invest_id_2']>0){
				//处理数据
				$investment_list_2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$stock_transfer_info['invest_id_2']);
				
				$investment_list_1 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$stock_transfer_info['invest_id']);
				
			if(intval($investment_list_2['type'])==4){
				$investment_list_1['money'] = $investment_list_1['money'] + $investment_list_2['money'];
				$investment_list_1['num'] = $investment_list_1['num'] + $investment_list_2['num'];
					
				$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_1,"UPDATE","id=".$stock_transfer_info['invest_id']);
					
				$investment_list_2['type']=6;
				$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_2,"UPDATE","id=".$stock_transfer_info['invest_id_2']);
					
				
				//同步项目状态
				syn_deal($investment_list_1['deal_id']);
			}
				
				
			}
			
			//同步项目状态
			//syn_deal($investment_list_1['deal_id']);
			showSuccess("提交成功",0,url("account#stock_transfer_out"));
		}else {
			
			showErr("提交失败",0,url("account#stock_transfer_out"));
		}
	}
	
	
	//股权入
	public function stock_transfer_in()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
			
		$user_id=$GLOBALS['user_info']['id'];
		//重写SELECT a.* FROM fanwe_stock_transfer  as a JOIN fanwe_deal_order as b ON a.id=b.deal_item_id WHERE b.user_id=17 
		//$stock_transfer_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."stock_transfer where purchaser_id=".$user_id);
		
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$stock_transfer_list=$GLOBALS['db']->getAll("select a.*,b.order_status as deal_status ,b.is_success as deal_is_success 
				,b.share_status as deal_share_status , b.repay_make_time as deal_repay_make_time ,  b.pay_time as deal_pay_time ,b.deal_id as deal_id
				from ".DB_PREFIX."stock_transfer as a JOIN ".DB_PREFIX."deal_order as b  ON a.id=b.deal_item_id WHERE  b.user_id=".$user_id." and b.type=6 Order by a.id desc  limit ".$limit);
		
		$deal_count = $GLOBALS['db']->getOne("select  count(distinct(b.id)) from ".DB_PREFIX."stock_transfer as a JOIN ".DB_PREFIX."deal_order as b  ON a.id=b.deal_item_id WHERE  b.user_id=".$user_id." and b.type=6 ");
		$page = new Page($deal_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign("now",NOW_TIME);
		$GLOBALS['tmpl']->assign("stock_transfer_list",$stock_transfer_list);
		$GLOBALS['tmpl']->assign("page_title","我的股权转入");
		$GLOBALS['tmpl']->display("account_stock_transfer_in.html");
	}
	
	//股权转让支付
	public function stock_transfer_view_order(){
		
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);
		
		$stock_transfer=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer  where id=".$id);
		
		$order_info = $GLOBALS['db']->getRow("select deo.*,d.transfer_share as transfer_share,d.limit_price as limit_price from ".DB_PREFIX."deal_order as deo LEFT JOIN ".DB_PREFIX."deal as d on deo.deal_id = d.id  where deo.deal_item_id = ".$stock_transfer['id']." and deo.user_id = ".intval($GLOBALS['user_info']['id']) ." and deo.type =6");
	
		if(!$order_info)
		{
			showErr("无效的项目支持",0,get_gopreview());
		}


		//用户所占股份
		$order_info['user_stock']= number_format(($stock_transfer['stock_value']/$order_info['limit_price'])*$order_info['transfer_share'],2);			
		//项目金额
		$order_info['stock_value'] =number_format($order_info['limit_price'],2);
			
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$order_info['deal_id']." and is_delete = 0 and is_effect = 1");
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		
		if($order_info['order_status'] == 0)
		{
			$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay in (0,1) order by sort asc ");
			$payment_html = "";
			foreach($payment_list as $k=>$v)
			{
				$class_name = $v['class_name']."_payment";
				require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
				$o = new $class_name;
				$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
			}
			$GLOBALS['tmpl']->assign("payment_html",$payment_html);
			
			$max_pay = $order_info['total_price'];
			$GLOBALS['tmpl']->assign("max_pay",$max_pay);
			
			$order_sm=array('credit_pay'=>0,'score'=>0,'score_money'=>0);
			if($order_info['credit_pay']>0)
			{
				$order_sm['credit_pay']=$order_info['credit_pay'] <= $GLOBALS['user_info']['money']?$order_info['credit_pay']:$GLOBALS['user_info']['money'];
			}
			if($order_info['score'] >0)
			{
				
				if($order_info['score'] <= $GLOBALS['user_info']['score'])
				{
					$order_sm['score']=$order_info['score'];
					$order_sm['score_money']=$order_info['score_money'];
				}else
				{
					$order_sm['score']=$GLOBALS['user_info']['score'];
					$score_array=score_to_money($GLOBALS['user_info']['score']);
					$order_sm['score_money']=$score_array['score_money'];
					$order_sm['score']=$score_array['score'];
				}
			}
			$GLOBALS['tmpl']->assign("order_sm",json_encode($order_sm));
		}
		
		$offline_pay=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Offlinepay'");
		$GLOBALS['tmpl']->assign("offline_pay",$offline_pay);
		
		$GLOBALS['tmpl']->assign("order_deal_type",$order_info['deal_type']);
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		//print_r($order_info);exit;
		
		$GLOBALS['tmpl']->assign("coll",is_tg(true));
		$GLOBALS['tmpl']->assign("page_title","转让的项目详情");
		$GLOBALS['tmpl']->display("account_view_order.html");
	
	}
	//结算
	public function stock_transfer_out_end(){
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		$id = intval($_REQUEST['id']);
		$data['is_success']=1;
		//share_status
		$data['share_status']=1;
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$data,"UPDATE","id=".$id);
		
		app_redirect(url("account#stock_transfer_out"));
	}
	
	public function stock_transfer_in_end(){
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		$id = intval($_REQUEST['id']);
	
		$data['is_success']=1;
		$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"UPDATE","id=".$id);
		$re=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."stock_transfer where id=".$id );
		
		//乙方加股权
		$investment_list['type'] = 5;
		//$investment_list['order_id'] = $re['deal_order_id'];
		$investment_list['user_id'] =$re['purchaser_id'];		
		$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list,"UPDATE","id=".$re['invest_id_2']);
		
		//甲方加钱		
		require_once APP_ROOT_PATH."system/libs/user.php";		
		$res=modify_account(array("money"=>$re['price']),intval($re['user_id']),$re['deal_name']."股权转让资金到账");
		
		//佣金		
		$re['commission']=floatval($re['price']*floatval(app_conf("STOCK_TRANSFER_COMMISION"))/100.00);
		$res=modify_account(array("money"=>"-".$re['commission']),intval($re['user_id']),$re['deal_name']."股权转让缴纳佣金");
		
		app_redirect(url("account#stock_transfer_in"));
	}
	
	//过期处理
	public function stock_transfer_off(){
		$user_id=$GLOBALS['user_info']['id'];
		//NOW_TIME
		$stock_transfer_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."stock_transfer where user_id=".$user_id." and status=1 and end_time> 0 and end_time<".NOW_TIME." and purchaser_id=0");
		foreach($stock_transfer_list as $k=>$v){
			if($v['invest_id_2']>0){
				
			$investment_list_2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$v['invest_id_2']);
			
			$investment_list_1 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."investment_list where id = ".$v['invest_id']);
			
			if(intval($investment_list_2['type'])==4){
				$investment_list_1['money'] = $investment_list_1['money'] + $investment_list_2['money'];
				$investment_list_1['num'] = $investment_list_1['num'] + $investment_list_2['num'];
					
				$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_1,"UPDATE","id=".$v['invest_id']);
					
				$investment_list_2['type']=6;
				$GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$investment_list_2,"UPDATE","id=".$v['invest_id_2']);
					
				$stock_transfer_list['status']=3;
				$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$stock_transfer_list,"UPDATE","id=".$v['id']);
				//同步项目状态
				syn_deal($investment_list_1['deal_id']);
			}
			
				
			}
		}
	}
	//转账日志
	public function commit_log(){
		$user_name =$GLOBALS['user_info']['user_name'];
		$notice_id = intval($_REQUEST['id']);
	
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		

		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		$record_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."commit_log where one_uname='".$user_name."' or two_uname='".$user_name."' order by id desc limit ".$limit);
		
		$record_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."commit_log where one_uname='".$user_name."' or two_uname='".$user_name."' order by id desc");
		
		$total_money = $GLOBALS['db']->getOne("select sum(*) from ".DB_PREFIX."commit_log where one_uname='".$user_name."' or two_uname='".$user_name."' order by id desc");
		
		foreach($record_list as $k=>$v){
			if(!$v['is_paid']){
				$record_list[$k]['url']=url("account#commit_log",array("notice_id"=>$v['id']));
			}
		}
		$page = new Page($record_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign('total_money',floatval($total_money));

		$GLOBALS['tmpl']->assign("notice_info",$record_list);
		//print_r($record_list);exit;
		$GLOBALS['tmpl']->display("account_commit_log.html");
		
		
		
		
		
		
		
		
	}
	//转账操作
	
	public function money_withdrawal()
	{		
		$GLOBALS['tmpl']->display("account_money_withdrawal.html");
	}
	//买房收益发放列表
	public function buy_house_earnings()
	{
		$GLOBALS['tmpl']->assign("page_title","买房收益发放列表");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$page_size = ACCOUNT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		$deal_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where  is_delete = 0 and is_effect = 1 and is_success = 1 and type = 2 and is_earnings = 1 and end_time < ".NOW_TIME." and user_id = ".intval($GLOBALS['user_info']['id'])." limit ".$limit);
		foreach($deal_info as $k=>$v){
			$deal_info[$k]['user_bonus']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus where deal_id = ".$v['id']." and type = 2  order by id");
			$deal_info[$k]['support_list'] = $GLOBALS['db']->getAll("select d.*,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$v['id']." and d.order_status = 3 and d.is_refund = 0 order by d.create_time desc ");	
			foreach($deal_info[$k]['user_bonus'] as $kk=>$vv){
				$deal_info[$k]['user_bonus'][$kk]['begin_time'] =to_date($vv['begin_time'],'Y-m-d');
				$deal_info[$k]['user_bonus'][$kk]['end_time'] =to_date($vv['end_time'],'Y-m-d');
			}
			$deal_info[$k]['bonus_num']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where  type = 2 and status = 1 and deal_id = ".$v['id']);
			$deal_info[$k]['total_bonus_money']=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_bonus where type = 2 and status = 1 and deal_id = ".$v['id']);
			//待发放收益
			$deal_info[$k]['dai_money'] = ($v['support_amount']*($v['earnings']/100))-$deal_info[$k]['total_bonus_money'];
			if($v['earnings_send_capital']){
				//已发放本金
				$deal_info[$k]['ben_money'] = $deal_info[$k]['total_bonus_money']/($v['earnings']/100);
			}
		}
		$deal_count = $GLOBALS['db']->getOne("select count(distinct(d.id)) from ".DB_PREFIX."deal d,".DB_PREFIX."deal_order do where  d.is_delete = 0 and d.is_effect = 1 and d.is_success = 1  and d.type = 2 and d.end_time < ".NOW_TIME." and d.user_id = ".intval($GLOBALS['user_info']['id'])." and d.id=do.deal_id and do.order_status = 3 and do.is_refund = 0 ");
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('deal_info',$deal_info);
		//print_r($deal_info);exit;
		$GLOBALS['tmpl']->display("account_buy_house_earnings.html");
	}
	//买房收益发放申请
	public function buy_house_earnings_apply(){
		$GLOBALS['tmpl']->assign("page_title","买房收益发放申请");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = intval($_REQUEST['id']);
		if($id>0){
			$user_bonus = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bonus where id =".$id);
			$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
			$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
			$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
			$user_bonus_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id =".$id);
			$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$user_bonus['deal_id']);
			//每期收益金额
			$deal_info['money'] = ($deal_info['support_amount']*($deal_info['earnings']/100))/$deal_info['earnings_send_count'];
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$user_bonus['deal_id']." and d.order_status = 3 and d.is_refund = 0  order by d.create_time desc ");
		}
		else{
			$deal_id = $_REQUEST['deal_id'];
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id =".$deal_id);
			//每期收益金额
			$deal_info['money'] = ($deal_info['support_amount']*($deal_info['earnings']/100))/$deal_info['earnings_send_count'];
			$GLOBALS['tmpl']->assign('deal_info',$deal_info);
			$support_list = $GLOBALS['db']->getAll("select d.*,u.user_name as user_name from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."user as u on d.user_id = u.id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 order by d.create_time desc ");	
		}
		foreach($support_list as $k=>$v){	
					//项目金额
					$support_list[$k]['stock_value'] =number_format($deal_info['limit_price'],2);
					$support_list[$k]['money'] =$v['total_price'];
					if($deal_info['earnings_send_capital']){
						if($deal_info['earnings_send_count']< 13){
							$support_list[$k]['part_money'] =$v['total_price']/$deal_info['earnings_send_count'];
						}else{
							$support_list[$k]['part_money'] =$v['total_price']/12;
						}
					}
					//用户所占股份
	 				$support_list[$k]['user_stock']= number_format(($support_list[$k]['money']/$deal_info['support_amount'])*100,2);	
	 				$support_list[$k]['notice_sn'] = to_date(NOW_TIME,"YmdHis").$v['id'];	
		}
		
		$GLOBALS['tmpl']->assign('support_list',$support_list);
		$GLOBALS['tmpl']->display("account_buy_house_earnings_apply.html");	
	}
	public function save_buy_house_earnings()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		
		$data['year'] = intval($_REQUEST['year']);
		$data['number'] = trim($_REQUEST['number']);
		if(intval($_REQUEST['earnings_send_capital'])){
			$data['earnings_send_capital'] = intval($_REQUEST['earnings_send_capital']);
		}
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where year =".$data['year']." and type = 2 and number=".$data['number']." and deal_id =".$deal_id);
		
		if($id==0 && $count >0){
			showErr("该收益已存在！",$ajax);
		}
		$data['money'] = floatval($_REQUEST['money']);
		if($data['money']<=0){
			showErr("请输入本期收益金额",$ajax);
		}
		$data['return_cycle'] = floatval($_REQUEST['return_cycle']);
		$data['average_annualized_return'] = floatval($_REQUEST['average_annualized_return']);
		
		$data['begin_time'] = strtotime($_REQUEST['begin_time']);
		$data['end_time'] = strtotime($_REQUEST['end_time']);
		$data['descripe'] = trim($_REQUEST['descripe']);
		$data['deal_id'] = intval($_REQUEST['deal_id']);
		$data['user_id'] = intval($GLOBALS['user_info']['id']);
		$data['status'] = 0;
		$data['type'] = 2;
		
		$data1=array();
		$share_bonus=$_REQUEST['share_bonus_array'];
		if($id==0)
		{
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());			
			if($result_id>0)
			{
				foreach($share_bonus as $k=>$v){	
					$data1['notice_sn'] =$v['0'];
					$data1['investor'] =$v['1'];
					$data1['investor_money'] =$v['2'];
					$data1['percentage_shares'] =$v['3'];
					if($data['earnings_send_capital']){
						$data1['investor_part_money'] =$v['4'];
						$data1['part_amount'] =$v['5'];
						$data1['amount'] =$v['6'];
					}else{
						$data1['amount'] =$v['4'];
					}
					$data1['user_bonus_id'] =$result_id;
					$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
					$data1['user_id'] = intval($GLOBALS['user_info']['id']);
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"INSERT","","SILENT");	
					$user_bonus_id = intval($GLOBALS['db']->insert_id());
				}
				showSuccess("提交审核成功",$ajax,url("account#buy_house_earnings"));
			}
			else
			{
				showErr("提交审核失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus",$data,"UPDATE","id=".$id,"SILENT");
			$data1=array();
			$share_bonus=$_REQUEST['share_bonus_array'];
			
			$result_id=$_REQUEST['id'];
			foreach($share_bonus as $k=>$v){
					
				$data1['notice_sn'] =$v['0'];
				$data1['investor'] =$v['1'];
				$data1['investor_money'] =$v['2'];
				$data1['percentage_shares'] =$v['3'];
				$data1['amount'] =$v['4'];
				$data1['id'] =$v['5'];
				$data1['user_bonus_id'] =$result_id;
				$data1['deal_id'] = intval($_REQUEST['deal_id']) ;
				$data1['user_id'] = intval($GLOBALS['user_info']['id']);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_bonus_list",$data1,"UPDATE","id=".$data1['id'],"SILENT");	
			}
			showSuccess("提交审核成功",$ajax,url("account#buy_house_earnings"));
		}
	}
	//买房收益发放详细
	public function buy_house_earnings_detail(){
		$GLOBALS['tmpl']->assign("page_title"," 买房收益发放详细");
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		$id = $_REQUEST['id'];
		$user_bonus=$GLOBALS['db']->getRow("select ub.*,d.name as deal_name from ".DB_PREFIX."user_bonus as ub left join ".DB_PREFIX."deal as d on ub.deal_id = d.id  where ub.id = ".$id);
		$user_bonus['begin_time']=to_date($user_bonus['begin_time'],'Y-m-d');
		$user_bonus['end_time']=to_date($user_bonus['end_time'],'Y-m-d');
		$user_bonus_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bonus_list where user_bonus_id = ".$id." order by id");
		$GLOBALS['tmpl']->assign('user_bonus',$user_bonus);
		$GLOBALS['tmpl']->assign('user_bonus_list',$user_bonus_list);
		$GLOBALS['tmpl']->display("account_buy_house_earnings_detail.html");
	}
}
?>