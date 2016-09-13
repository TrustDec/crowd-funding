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
class score_good_showModule
{
	public function index()
	{	
		$id=intval($_REQUEST['id']);
		$goods=get_goods_info($id);
		if(!$goods)
		{
			app_redirect(url_wap("index"));
		}
			
		if($goods['goods_type_id'] > 0)
		{
			$goods_attr=get_goods_attr($goods['goods_type_id'],$id);
			$goods['good_attr']=$goods_attr['good_attr'];
			$goods['goods_attr_stock_json']=$goods_attr['goods_attr_stock_json'];
		}else
		{
			$goods['good_attr']=array();
			$goods['goods_attr_stock_json']=array();
		}
		
		if($goods['max_bought'] >0)
			$goods['is_limit_user']=1;
		else
			$goods['is_limit_user']=0;
		if( $goods['user_max_bought'] >0 && $GLOBALS['user_info']['id']>0)
		{
			$count_buy_num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_order where order_status=1 and user_id=".intval($GLOBALS['user_info']['id'])." and goods_id=".$id."");
			$remain_user_buy=$goods['user_max_bought']-$count_buy_num;
			$goods['remain_user_buy']=$remain_user_buy>0?$remain_user_buy:0;
		}else
		{
			$goods['remain_user_buy']=0;
		}
		
		if($GLOBALS['user_info']['id']>0)
			$goods['is_login']=1;
		else
			$goods['is_login']=0;
			
		$GLOBALS['tmpl']->assign("goods",$goods);
		$GLOBALS['tmpl']->display("score/score_good_show.html");
		
	}
	
	public function check_order()
	{
		if(!$GLOBALS['user_info']['id']>0)
		{
			app_redirect(url_wap("user#login"));
		}
		//商品有效性，会员积分是否够，判断属性是否选择 ， 判断库存，会员购买量有没有操作过最大购买量
		$id=intval($_REQUEST['id']);
		$attr=$_REQUEST['attr'];
		$number=intval($_REQUEST['num']);
	
		$goods=get_goods_info($id);
		if(!$goods){
			showErr("请选择商品!",0);
		}
		
		if($number < 1)
		{
			showErr("请输入兑换数量!",0);
		}
		
		//判断及库存
		if($goods['goods_type_id']>0)
		{
			$goods_attr=get_goods_attr($goods['goods_type_id'],$id);
			$goods['good_attr']=$goods_attr['good_attr'];
			$goods['attr_stock_data']=$goods_attr['attr_stock_data'];
		}
		$stock_return=check_attr_stock($goods,$number,$attr);
		if($stock_return['status'] ==0)
		{
			showErr($stock_return['info'],0);
		}
		
		if($goods['goods_type_id']>0)
		{
			$goods["score"] +=$stock_return['attr_score'];//加上属性的钱
		}
		
		if($GLOBALS['user_info']['score'] < $goods["score"]*$number)
		{
			showErr("您的积分不够!",0);
		}
		
		//最大购买量
		if($goods['user_max_bought'] >0)
		{	
			$count_buy_num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_order where order_status=1 and user_id=".intval($GLOBALS['user_info']['id'])." and goods_id=".$id."");
			$all_number=$count_buy_num+$number;
			if($all_number > $goods['user_max_bought'])
			{
				showErr("最多可能兑换".$goods['user_max_bought']."件，您已经兑换了".$count_buy_num."件",0);
			}
		}
		
		//省份
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		
		//收获地址
		$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id= ".intval($GLOBALS['user_info']['id'])." order by is_default desc");
		if($consignee_list[0]['id'] > 0)
		{
			$have_consignee=1;
		}else{
			$have_consignee=0;
		}

		$GLOBALS['tmpl']->assign("have_consignee",$have_consignee);
		$GLOBALS['tmpl']->assign("consignee_list",$consignee_list);

		$GLOBALS['tmpl']->assign("goods",$goods);
		$GLOBALS['tmpl']->assign("view_attr",$stock_return['view_attr']);
		$GLOBALS['tmpl']->assign("number",$number);
		$GLOBALS['tmpl']->assign("total_score",$number*$goods['score']);
		$GLOBALS['tmpl']->display("score/score_check_order.html");
		
	}
	

	public function do_score_order()
	{	
		$ajax=intval($_REQUEST['ajax']);
		$user_id=intval($GLOBALS['user_info']['id']);
		if(!$user_id>0)
		{
			$ajax_return['status'] = -1;
			ajax_return($ajax_return);
		}
	
		$ajax=intval($_REQUEST['ajax']);
		$id=intval($_REQUEST['id']);
		$consignee_id=intval($_REQUEST['consignee_id']);
		$delivery_name=strim($_REQUEST['delivery_name']);
		$delivery_province=strim($_REQUEST['delivery_province']);
		$delivery_city=strim($_REQUEST['delivery_city']);
		$delivery_addr=strim($_REQUEST['delivery_addr']);
		$delivery_zip=strim($_REQUEST['delivery_zip']);
		$delivery_tel=strim($_REQUEST['delivery_tel']);
		$delivery_time=intval($_REQUEST['delivery_time']);
		$memo=strim($_REQUEST['memo']);
		
		$paypassword=intval($_REQUEST['paypassword']);
		
		$number=intval($_REQUEST['number']);
		$attr='';
	
		if(md5($paypassword) !=$GLOBALS['user_info']['paypassword'])
		{
			showErr("付款密码错误！",$ajax);
		}
	
		$goods=get_goods_info($id);
		if(!$goods)
		{
			showErr("无效的商品",$ajax,url_wap("score_mall#index"));
			
		}
		
		if( $number <=0)
		{
			showErr("请重新选择下单",$ajax,url_wap("score_good_show#index",array('id'=>$id)));
		}
		
		//最大购买量
		if($goods['user_max_bought'] >0)
		{	
			$count_buy_num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_order where order_status=1 and user_id=".intval($GLOBALS['user_info']['id'])." and goods_id=".$id."");
			$all_number=$count_buy_num+$number;
			if($all_number > $goods['user_max_bought'])
			{
				showErr("最多可能兑换".$goods['user_max_bought']."件，您已经兑换了".$count_buy_num."件",1);
			}
		}
		
		//判断收获地址
		if($goods['is_delivery'] ==1)
		{
			if($consignee_id >0)
			{
				$consignee = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where user_id= ".intval($GLOBALS['user_info']['id'])." and id=".$consignee_id);
				if(!$consignee)
					showErr("请重选择正确的配送地址",$ajax);
			}
			else
			{
				if($delivery_name =='')
					showErr("请输入收货人名称",$ajax);
				if($delivery_province =='')
					showErr("请选择省份",$ajax);
				if($delivery_city =='')
					showErr("请选择城市",$ajax);
				if($delivery_addr =='')
					showErr("请输入详细地址",$ajax);
				if($delivery_tel =='')
					showErr("请输入手机号码",$ajax);
				if(!check_mobile($delivery_tel))
					showErr("请输入正确手机号码",$ajax);
			}		
		}
		
		$ajax=intval($_REQUEST['ajax']);
		$id=intval($_REQUEST['id']);
		$consignee_id=intval($_REQUEST['consignee_id']);
		$delivery_name=strim($_REQUEST['delivery_name']);
		$delivery_province=strim($_REQUEST['delivery_province']);
		$delivery_city=strim($_REQUEST['delivery_city']);
		$delivery_addr=strim($_REQUEST['delivery_addr']);
		$delivery_zip=strim($_REQUEST['delivery_zip']);
		$delivery_tel=strim($_REQUEST['delivery_tel']);
		$delivery_time=intval($_REQUEST['delivery_time']);
		$memo=strim($_REQUEST['memo']);
		$paypassword=intval($_REQUEST['paypassword']);
		
		$data=array();
		$data['goods_id']=$id;
		$data['goods_name']=$goods['name'];
		$data['user_id']=$user_id;
		$data['user_name']=$GLOBALS['user_info']['user_name'];
		$data['memo']=$memo;
		$data['is_delivery']=$goods['is_delivery'];
		if($goods['is_delivery'] ==1)
		{	
			if($delivery_time ==1)
			{
				$delivery_memo="仅工作日送货";
			}elseif($delivery_time ==2)
			{
				$delivery_memo="仅周末送货";
			}elseif($delivery_time ==3)
			{
				$delivery_memo="任何时间均可送货";
			}else
			{
				$delivery_memo="仅工作日送货";
			}
			
			$data['memo'] .="[".$delivery_memo."]";
		}else
		{
			$data['delivery_status']=2;//无需发货
		}
		//echo $data['memo']; exit;
		if($goods['is_delivery'] && $consignee_id > 0)
		{
			$data['delivery_name']=$consignee['consignee'];
			$data['delivery_province']=$consignee['province'];
			$data['delivery_city']=$consignee['city'];
			$data['delivery_addr']=$consignee['address'];
			$data['delivery_zip']=$consignee['zip'];
			$data['delivery_tel']=$consignee['mobile'];
		}
		else
		{	
			$data['delivery_name']=$consignee_data['consignee']=$delivery_name;
			$data['delivery_province']=$consignee_data['province']=$delivery_province;
			$data['delivery_city']=$consignee_data['city']=$delivery_city;
			$data['delivery_addr']=$consignee_data['address']=$delivery_addr;
			$data['delivery_zip']=$consignee_data['zip']=$delivery_zip;
			$data['delivery_tel']=$consignee_data['mobile']=$delivery_tel;
		}
		
		//判断及库存
		if($goods['goods_type_id']>0)
		{
			$goods_attr=get_goods_attr($goods['goods_type_id'],$id);
			$goods['good_attr']=$goods_attr['good_attr'];
			$goods['attr_stock_data']=$goods_attr['attr_stock_data'];
		}
		$stock_return=check_attr_stock($goods,$number,$attr);
		if($stock_return['status'] ==0)
		{	
			showErr($stock_return['info'],$ajax,url_wap("score_good_show#index",array('id'=>$id)));
		}
		//写入属性
		if($goods['goods_type_id'] && $attr)
		{
			$attr_info=$GLOBALS['db']->getAll("select ga.id,ga.name,ga.score,gy.name as type_name from ".DB_PREFIX."goods_attr as ga left join ".DB_PREFIX."goods_type_attr as gy on gy.id=ga.goods_type_attr_id where ga.id in(".implode(',',$attr).") and ga.goods_id=".$id." order by ga.id asc");
			$attr_view='';
			$order_attr='';
			$attr_score=0;
			foreach($attr_info as $k=>$v)
			{
				$attr_view .= $v['type_name'].":". $v['name'].",";
				$order_attr .=$v['name'];
				$attr_score +=$v['score'];
			}
			$data['attr_view']=substr($attr_view,0,-1);
			$data['attr']=$order_attr;
			$goods['score'] +=$attr_score;
		}
		
		$data['score']=$goods['score'];
		$data['number']=$number;
		$data['total_score']=intval($number*$goods['score']);
		
		if( $GLOBALS['user_info']['score'] < $data['total_score'] )
		{
			showErr("您的积分不够",$ajax,url_wap("score_good_show#index",array('id'=>$id)));
		}
		
		$data['order_status']=0;
		$now_time =get_gmtime();
		$data['create_time']=$now_time;
		$data['create_date']=to_date($now_time,'Y-m-d');
		do
		{
			$data['order_sn'] = to_date($now_time,"Ymdhis").rand(10,99);
			$GLOBALS['db']->autoExecute(DB_PREFIX."goods_order",$data,'INSERT','','SILENT');
			$order_id = intval($GLOBALS['db']->insert_id());
		}while($order_id==0);
		
		if($order_id >0)
		{
			require_once APP_ROOT_PATH."system/libs/user.php";	
			//扣积分
			$log_score=$goods['name']."兑换成功";
			$score_status=modify_account(array("score"=>"-".$data['total_score']),$user_id,$log_score);
			if(!$score_status)
			{
				//积分扣除失败，订单无效
				$order_data['order_status']=5;
				$GLOBALS['db']->query("update ".DB_PREFIX."goods_order set order_status=".$order_data['order_status']." where id=".intval($order_id));
				showErr("兑换失败，订单无效",$ajax,url_wap("score_good_show#index",array('id'=>$id)));
			}else
			{
				$ex_new_time=get_gmtime();
				$order_data['order_status']=1;//兑换成功
				$order_data['pay_score']=$data['total_score'];
				$order_data['ex_time']=$ex_new_time;//对换时间
				$order_data['ex_date']=to_date($ex_new_time,'Y-m-d');
			}
			//判断库存，不够，退积分
			if($goods['goods_type_id']>0)
			{
				$goods_attr=get_goods_attr($goods['goods_type_id'],$id);
				$goods['good_attr']=$goods_attr['good_attr'];
				$goods['attr_stock_data']=$goods_attr['attr_stock_data'];
			}
			$stock_return=check_attr_stock($goods,$number,$attr);
			if($stock_return['status'] ==0)
			{
				$order_data['order_status']=2;//库存不够，退回积分
				$log_score=$goods['name']."兑换失败，库存不够，积分退回";
				$score_status=modify_account(array("score"=>$data['score']),$user_id,$log_score);
				$GLOBALS['db']->query("update ".DB_PREFIX."goods_order set order_status=".$order_data['order_status']." where id=".intval($order_id));
				showErr("兑换失败，库存不够，积分退回",$ajax,url_wap("score_good_show#index",array('id'=>$id)));
			}else
			{
				$order_data['order_status']=1;//兑换成功
				$GLOBALS['db']->query("update ".DB_PREFIX."goods set buy_number= buy_number +".$number." where id=".$id);
				if($data['attr'] !=='')
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."goods_attr_stock set buy_count= buy_count +".$number." where attr_str='".$data['attr']."' and goods_id=".$id);
				}
			}
			//成功更新订单状态
			$GLOBALS['db']->autoExecute(DB_PREFIX."goods_order",$order_data,'UPDATE'," id=".$order_id,'SILENT');
			
			if($data['is_delivery'] && $consignee_id == 0)
			{
				$consignee_data['user_id']=$user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$consignee_data,'INSERT','','SILENT');
			}
			
					
		}else
		{
			showErr("兑换失败",$ajax,url_wap("score_good_show#index",array('id'=>$id)));
		}
		
		$ajax_return['status']=1;
		$ajax_return['info']="兑换成功";
		showSuccess("兑换成功",$ajax,url_wap("score_goods_order#index"));
	}
}
?>