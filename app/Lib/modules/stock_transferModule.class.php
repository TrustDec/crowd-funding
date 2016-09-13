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
class stock_transferModule extends BaseModule
{
	public function __construct(){
		parent::__construct();
		if(app_conf("IS_STOCK_TRANSFER")==0)
		{
			showErr("股权转让已经关闭");
		}
	}
	//股权转让主页
	public function index()
	{
		if(app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		
		 $param = array();//参数集合
           
         //数据来源参数
		
		$type=5;
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$GLOBALS['tmpl']->assign("p_id",$id);
		
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$GLOBALS['tmpl']->assign("p_loc",$loc);

		$param_new=$param;
		
		//分类
		if($type != 0)//买房众筹
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
		
			$nav_cate_all=$nav_cate_array['deal_cate_all'];
			$cate_result=$nav_cate_array['deal_cate_big'];
			foreach($cate_result as $k=>$v)
			{
				$temp_param = $param;
				$temp_param['id'] = $v['id'];
				$cate_result[$k]['url'] = deal_type_url($temp_param,$type);
			}
				
			if($id >0)
			{
				if($nav_cate_all[$id]['pid'] >0)
				{//当前小分类
					$pid=$nav_cate_all[$id]['pid'];
					$cate_ids['id']=$id;
				}
				else
				{//当前分类是大分类
					$pid=$id;
					if($nav_cate_all[$id]['sub_list'])
					{
						$cate_ids=array_map('array_shift',$nav_cate_all[$id]['sub_list']);
					}
					$cate_ids[]=$id;
				}
		
				$child_cate_result=$nav_cate_all[$pid]['sub_list'];
				foreach($child_cate_result as $k=>$v)
				{
					$temp_param = $param;
					$temp_param['id'] = $v['id'];
					$child_cate_result[$k]['url'] = deal_type_url($temp_param,$type);
				}
					
			}
				
		}else
		{
			$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
				
			if(!$cate_list)
			{
				$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where is_delete=0 order by sort asc");
				set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
			}
			$cate_result = array();
			foreach($cate_list as $k=>$v){
				if($v['pid'] == 0){
					$temp_param = $param;
					$cate_result[$k+1]['id'] = $v['id'];
					$cate_result[$k+1]['name'] = $v['name'];
					$temp_param['id'] = $v['id'];
					$cate_result[$k+1]['url'] = deal_type_url($temp_param,$type);
				}
			}
				
			$pid = $id;
			//获取父类id
				
			if($cate_list){
				$pid = $this->get_child($cate_list,$pid);
			}
			/*子分类 start*/
			$cate_ids = array();
			$is_child = false;
			$temp_cate_ids = array();
				
			if($cate_list){
				$child_cate_result= array();
				foreach($cate_list as $k=>$v)
				{
					if($v['pid'] == $pid){
						if($v['id'] > 0){
							$temp_param = $param;
							$child_cate_result[$v['id']]['id'] = $v['id'];
							$child_cate_result[$v['id']]['name'] = $v['name'];
							$temp_param['id'] = $v['id'];
							$child_cate_result[$v['id']]['url'] = deal_type_url($temp_param,$type);
							if($id==$v['id']){
								$is_child = true;
							}
								
						}
					}
					if($v['pid'] == $pid || $pid==0){
						$temp_cate_ids[] = $v['id'];
					}
				}
			}
				
			//假如选择了子类 那么使用子类ID  否则使用 父类和其子类
			if($is_child){
				$cate_ids[] = $id;
			}
			else{
				$cate_ids[] = $pid;
				$cate_ids = array_merge($cate_ids,$temp_cate_ids);
			}
			$cate_ids=array_filter($cate_ids);
			/*子分类 end*/
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
		
		$condition='';
		
		$page_size = 10;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		//
		//$transfer_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.status=1 and is_success=0 and begin_time<".NOW_TIME." and end_time >".NOW_TIME." limit ".$limit);
		if ($pid>0) {
			$transfer_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id  where s.status=1 and s.begin_time<".NOW_TIME." and s.cate_id in (".implode(",",$cate_ids).") order by s.id desc limit ".$limit);
			
		}else{
			$transfer_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.status=1 and s.begin_time<".NOW_TIME." order by s.id desc limit ".$limit);
		}
		
		
		
		/*foreach($transfer_list as $k=>$v){
			if ($v['purchaser_id']>0) {
				$deal_order=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where type=6 and deal_item_id= ".$v['id']);
				$transfer_list[$k]['deal_status']=$deal_order['status'];
			}
				
		}*/
		
		
		
		$transfer_success_list=$GLOBALS['db']->getAll("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id  where s.is_success=1 order by s.id desc limit 0,8");
		foreach($transfer_success_list as $k=>$v){
			$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where  id= ".$v['deal_id']);
			$transfer_success_list[$k]['image']=$deal['image'];
			
		}
		
		
		if ($pid>0) {
			
			$order_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."stock_transfer  where status=1 and begin_time<".NOW_TIME." and cate_id in (".implode(",",$cate_ids).") ");
			
		}else{
			$order_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."stock_transfer where status=1 and begin_time<".NOW_TIME);
		}
		


		$parameter_str="&".implode("&",$param);
		$page = new Page($order_count,$page_size,$parameter_str);   //初始化分页对象
		$p  =  $page->para_show("stock_transfer#index",$param);
		
		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->assign('now',NOW_TIME);
		$GLOBALS['tmpl']->assign("page_title","股权交易");
		$GLOBALS['tmpl']->assign('transfer_list',$transfer_list);
		$GLOBALS['tmpl']->assign('transfer_success_list',$transfer_success_list);
		$GLOBALS['tmpl']->display("stock_transfer_list.html");
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
	
	//股权转让支付页面
	/*public function go_transfer_pay(){
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);  //获得id
		
	}*/
	
	
	//股权转让详情页
	public function go_transfer(){
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		
		$id = intval($_REQUEST['id']);  //获得id
		
		$transfer_order=$GLOBALS['db']->getRow("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.id=".$id);
		
		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal  where id=".$transfer_order['deal_id']);
		
		$deal['percent'] = round($deal['support_amount']/$deal['limit_price']*100,2);
		$deal['remain_days'] = ceil(($deal['end_time'] - NOW_TIME)/(24*3600));
		
		
		// 支付方式
		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,1)  order by sort asc ");
		$payment_html = "";
		foreach($payment_list as $k=>$v)
		{
			$class_name = $v['class_name']."_payment";
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			$payment_html .= "<div>".$o->get_display_code()."<div class='blank'></div></div>";
		}
		$GLOBALS['tmpl']->assign("payment_html",$payment_html);

		// 相对应的项目
		/*$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}*/
		
		
		//添加到deal_order表
		//资格判定
		if($transfer_order['user_id']==intval($GLOBALS['user_info']['id'])){

			showErr("自己不可申请购买自己的转让",0,url("stock_transfer"));
		
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
		
			
		$GLOBALS['tmpl']->assign('order_info',$order_info);
		$GLOBALS['tmpl']->assign('transfer_order',$transfer_order);
		$GLOBALS['tmpl']->assign('deal_info',$deal);
		$GLOBALS['tmpl']->assign("page_title","股权交易详情");
		$GLOBALS['tmpl']->display("go_transfer.html");
	}
	
	//添加到deal_order表
	public function add_deal_order(){
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		
		
		
		$ajax = intval($_REQUEST['ajax']);
		$id=intval($_REQUEST['id']);

		$transfer_order=$GLOBALS['db']->getRow("select s.*,i.deal_id as deal_id from ".DB_PREFIX."stock_transfer as s join ".DB_PREFIX."investment_list as i on i.id = s.invest_id where s.id=".$id);		
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal  where id=".$transfer_order['deal_id']);
		
		//资格判定
		if($transfer_order['user_id']==intval($GLOBALS['user_info']['id'])){
			//app_redirect(url("stock_transfer"));
			//showErr("自己不可申请购买自己的转让",0,url("stock_transfer"));	
			$result['status'] = 0;
			$result['info'] = "自己不可申请购买自己的转让";
			ajax_return($result);
		}
		
		$order_info['deal_id'] = $deal_info['id'];
		$order_info['user_id'] =intval($GLOBALS['user_info']['id']);
		$order_info['user_name'] = $GLOBALS['user_info']['user_name'];
		$order_info['total_price'] = $transfer_order['price'];
		$order_info['online_pay'] = 0;
		$order_info['deal_name'] = $deal_info['name'];
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
			
		if($order_id>0)
		{
			//更新状态
			/*$data['purchaser_id'] =intval($GLOBALS['user_info']['id']);
			$data['purchaser_name'] = $GLOBALS['user_info']['user_name'];
			$data['deal_order_id'] = $order_id;
			$GLOBALS['db']->autoExecute(DB_PREFIX."stock_transfer",$data,"UPDATE","id=".$id);
						
			if($GLOBALS['db']->affected_rows()>0){
				$result['status'] = 1;
				$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_in";
				ajax_return($result);
			}else {
				$result['status'] = 0;
				$result['info'] = "提交失败";
				ajax_return($result);
					
			}*/
			
			$result['status'] = 1;
			//$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_in";
			$result['jump'] = APP_ROOT."/index.php?ctl=account&act=stock_transfer_view_order&id=".$id;
			ajax_return($result);
		}else{
			$result['status'] = 0;
			$result['info'] = "提交失败";
			ajax_return($result);
			
		}
	}
	
	
}
?>