<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'wap/app/page.php';
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class stock_transferModule  
{
	public function index()
	{
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$GLOBALS['tmpl']->assign("p_id",$id);
		
		$GLOBALS['tmpl']->assign("page_title","股权转让");
		$page_size = intval($GLOBALS['m_config']['page_size']);
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$type=5;
		
		//分类
		if($type != 0)
		{
			if($type == 1||$type == 5){
				$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_investor_cate'));
			}elseif($type == 2){
				$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_house_cate'));
			}elseif($type == 3){
				$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_selfless_cate'));
			}elseif($type == 4){
				$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
			}
			
			$house_cate_all=$nav_cate_array['deal_cate_all'];
			$cate_result=$nav_cate_array['deal_cate_big'];
			foreach($cate_result as $k=>$v)
			{
				$temp_param = $param;
				$temp_param['id'] = $v['id'];
				$cate_result[$k]['url'] = url_wap("stock_transfer",$temp_param);
			}
			
			if($id >0)
			{
				if($house_cate_all[$id]['pid'] >0)
				{//当前小分类
					$pid=$house_cate_all[$id]['pid'];
					$cate_ids['id']=$id;
				}
				else
				{//当前分类是大分类
					$pid=$id;
					if($house_cate_all[$id]['sub_list'])
					{
						$cate_ids=array_map('array_shift',$house_cate_all[$id]['sub_list']);
					}
					$cate_ids[]=$id;
				}

				$child_cate_result=$house_cate_all[$pid]['sub_list'];
				foreach($child_cate_result as $k=>$v)
				{
					$temp_param = $param;
					$temp_param['id'] = $v['id'];
					$child_cate_result[$k]['url'] = url_wap("stock_transfer",$temp_param);
				}
				
				$GLOBALS['tmpl']->assign("cate_name", $house_cate_all[$id]['name']);
			
			}
		}
		
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
		
		/*子分类 end*/
				
		if ($pid>0) {
			$transfer_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id  where s.status=1 and s.begin_time<".NOW_TIME." and s.cate_id in (".implode(",",$cate_ids).") order by s.id desc limit ".$limit);
			$order_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."stock_transfer  where status=1 and begin_time<".NOW_TIME." and cate_id in (".implode(",",$cate_ids).") ");
		}else{
			$transfer_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.status=1 and s.begin_time<".NOW_TIME." order by s.id desc limit ".$limit);
			$order_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."stock_transfer where status=1 and begin_time<".NOW_TIME);
		}
		
		
		$page = new Page($order_count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('transfer_list',$transfer_list);
		$GLOBALS['tmpl']->assign("page_count",ceil(intval($order_count)/$page_size));
		$GLOBALS['tmpl']->assign('now',NOW_TIME);
		$GLOBALS['tmpl']->display("stock_transfer_list.html");
	}	
	
	public function go_transfer(){
		
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
		if(!$GLOBALS['user_info'])
			app_redirect(url_wap("user#login"));
		
		$id = intval($_REQUEST['id']);  //获得id
		
		$transfer_order=$GLOBALS['db']->getRow("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.id=".$id);
		
		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal  where id=".$transfer_order['deal_id']);
		
		$deal['percent'] = round($deal['support_amount']/$deal['limit_price']*100,2);
		$deal['remain_days'] = ceil(($deal['end_time'] - NOW_TIME)/(24*3600));
		
			
		//添加到deal_order表
		//资格判定
		if($transfer_order['user_id']==intval($GLOBALS['user_info']['id'])){

			showErr("自己不可申请购买自己的转让",0,url_wap("stock_transfer"));
		
		}
		
		$order_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order  where user_id=".$GLOBALS['user_info']['id']." and deal_item_id=".$id." and type=6 and invest_id=".$transfer_order['invest_id_2']);
		if(!$order_info){
			
			$order_info['deal_id'] = $deal['id'];
			$order_info['user_id'] =intval($GLOBALS['user_info']['id']);
			$order_info['user_name'] = $GLOBALS['user_info']['user_name'];
			$order_info['total_price'] = $transfer_order['price'];
			$order_info['online_pay'] = 0;
			$order_info['deal_name'] = $deal['name'];
			$order_info['deal_item_id'] = $id;
			$order_info['order_status'] = 0;
			$order_info['create_time']	= NOW_TIME;
			$is_tg=intval($_REQUEST['is_tg']);
			$order_info['is_tg'] = $is_tg;
			$order_info['bank_id'] = strim($_REQUEST['bank_id']);
			$order_info['is_success'] =0;
			$order_info['invest_id'] =$transfer_order['invest_id_2'];
			$order_info['type'] =6; //表示股权转让			
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info);
			$order_id = $GLOBALS['db']->insert_id();			
			//添加到deal_order表
			$order_info['id']=$order_id;
		}else{	
					
			$order_info['total_price'] = $transfer_order['price'];			
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info,"UPDATE","id=".$order_info['id']);
			
		}
		
		// 支付方式
		if($order_info['order_status'] == 0)
		{
			$payment_list = get_payment_list("wap");
			foreach($payment_list as $k=>$v)
			{
				$class_name = $v['class_name']."_payment";
				if($v['class_name']=='Wnewpay'){
					require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
					$o = new $class_name;
					$payment_html .= $o->get_display_code()."<div class='blank'></div>";
				}elseif($v['class_name']=='Wbfpay'){
					require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
					$o = new $class_name;
					$pay_id= array();
					$pay_id= $o->get_pay_id();
						
					$payid = array();
					foreach($pay_id as $k =>$v){
						$payid[$k] = $payment_lang[$k];
					}
				}
		
			}
			$GLOBALS['tmpl']->assign("payment_payid",$payid);
			$GLOBALS['tmpl']->assign("payment_html",$payment_html);
			$GLOBALS['tmpl']->assign("payment_list",$payment_list);
				
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
			$GLOBALS['tmpl']->assign("page_title","订单支付");
		}else{}
		$GLOBALS['tmpl']->assign("coll",is_tg(true));

		
		$GLOBALS['tmpl']->assign('order_info',$order_info);
		$GLOBALS['tmpl']->assign('transfer_order',$transfer_order);
		$GLOBALS['tmpl']->assign('deal_info',$deal);
		$GLOBALS['tmpl']->assign("page_title","股权交易详情");
		$GLOBALS['tmpl']->display("go_transfer.html");
	
	}
	public function get_child($cate_list,$pid){
		foreach($cate_list as $k=>$v)
		{
			if($v['id'] ==  $pid){
				if($v['pid'] > 0){
					$pid =$this->get_child($cate_list,$v['pid']) ;
					if($pid==$v['pid']){
						return $pid;
					}
				}
				else{
					return $pid;
				}
			}
		}
	}
}
?>