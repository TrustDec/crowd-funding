<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'wap/app/page.php';
require APP_ROOT_PATH.'app/Lib/score_goods_func.php';
class score_goods_orderModule
{
	public function index()
	{	
		if(!$GLOBALS['user_info'])
		app_redirect(url("user#login"));
		
		$GLOBALS['tmpl']->assign("page_title","积分兑换列表");
		
		$page=intval($_REQUEST['p']);
		if($page <1)
			$page=1;

		$page_size=intval($GLOBALS['m_config']['page_size']);
		$page_size=2;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$parameter['order_sn']=strim($_REQUEST['order_sn']);
		$parameter['create_date']=strim($_REQUEST['create_date']);
 		
 		if($parameter['order_sn'] != '')
 		{
 			$where =" and go.order_sn =".$parameter['order_sn']."";
 		}
 		if($parameter['create_date'] != '')
 		{
 			$where =" and go.create_date ='".$parameter['create_date']."'";
 		}
 		
		$order_count=$GLOBALS['db']->getOne("select count(go.id) from ".DB_PREFIX."goods_order as go where go.user_id=".intval($GLOBALS['user_info']['id']).$where);
		if($order_count)
		{	
			$order_list=$GLOBALS['db']->getAll("select go.*,g.img from ".DB_PREFIX."goods_order as go left join ".DB_PREFIX."goods as g on g.id=go.goods_id where go.user_id = ".intval($GLOBALS['user_info']['id']).$where." order by go.id desc limit ".$limit);
			foreach($order_list as $k=>$v)
			{
				if($v['attr_view'] != '')
					$order_list[$k]['attr_view']=str_replace(',','<br />',$v['attr_view']);
				if($v['delivery_status'] ==0)
					$order_list[$k]['delivery_status_info'] ="未发货";
				elseif($v['delivery_status'] ==1)
					$order_list[$k]['delivery_status_info'] ="已发货";
				else
					$order_list[$k]['delivery_status_info'] ="无需发货";
					
				//订单状态 0未兑换 1兑换成功 2已兑换（无库存，积分已退回） 3:退积分（管理员取消兑换，退还积分）  4已取消   5无效的订单		
				if($v['order_status'] ==1)
					$order_list[$k]['order_status_info'] ="已兑换";
				elseif($v['order_status'] ==2)
					$order_list[$k]['order_status_info'] ="无库存，<br />积分已退回";
				elseif($v['order_status'] ==3)
					$order_list[$k]['order_status_info'] ="已退积分";
				elseif($v['order_status'] ==4)
					$order_list[$k]['order_status_info'] ="已取消";
				elseif($v['order_status'] ==5)
					$order_list[$k]['order_status_info'] ="已无效";
				
			}
			//print_r($order_list);
			
			$parameter_str="&".implode("&",$parameter);
	  		$page = new Page($order_count,$page_size,$parameter_str);   //初始化分页对象 		
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);
			$GLOBALS['tmpl']->assign('order_list',$order_list);
		}
		
		$GLOBALS['tmpl']->assign('parameter',$parameter);
		$GLOBALS['tmpl']->display("score/score_goods_order.html");
	}
	
	public function del_order(){
		$ajax=intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			$ajax_return['status']=-1;
			ajax_return($ajax_return);
		}
		
		$id=intval($_REQUEST['id']);
		$user_id=intval($GLOBALS['user_info']['id']);
		$order_info=$GLOBALS['db']->getROW("select * from ".DB_PREFIX."goods_order  where user_id=".$user_id." and id=".$id);
		if(!$order_info)
		{
			showErr("未找到订单",$ajax,url_wap("score_goods_order#index"));
		}elseif($order_info['order_status'] !=0){
			showErr("该订单不能取消",$ajax,url_wap("score_goods_order#index"));
		}
		
		
		if($GLOBALS['db']->query(" update ".DB_PREFIX."goods_order set order_status=4 where user_id=".$user_id." and id=".$id." and order_status=0"))
			showSuccess("取消成功",$ajax,url_wap("score_goods_order#index"));
		else
			showErr("取消失败",$ajax,url_wap("score_goods_order#index"));
		
	}
	
}
?>