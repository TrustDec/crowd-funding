<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class cartModule extends BaseModule
{
	//购物车
	public function index()
	{		
             
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		//(普通众筹)支持之前需要用户绑定手机号
		if(!$GLOBALS['user_info']['mobile'])
		{
			showErr("您未绑定手机,无法进行众筹",0,url("settings#security",array('method'=>'setting-mobile-box')));
			//app_redirect(url("user#user_bind_mobile",array("cid"=>intval($_REQUEST['id']))));
			//app_redirect(url("settings#security",array('method'=>'setting-mobile-box')));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			app_redirect(url("index"));
		}
		elseif($deal_item['support_count']+$deal_item['virtual_person']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{
			
			$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
			if($deal_item['maxbuy'] >0)
			{
				if($buy_num >=$deal_item['maxbuy'])
					showErr("您的支持数已达到上限",0,url("deal#show",array("id"=>$deal_item['deal_id'])));
					
				$deal_item['remain_user_buy']=$deal_item['maxbuy']-$buy_num;
			}
		}
		
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品 已抽过奖，不能再支持
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			showErr("项目幸运星已揭晓",0,url("deal#update",array("id"=>$deal_item['deal_id'])));
		}
		
		$deal_info1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id = ".$deal_item['deal_id']);
		foreach ($deal_info1 as $k=>$v){
			// 统计所有真实+虚拟（钱）
			$deal_info1['total_virtual_price']+=intval($v['price'] * $v['virtual_person']+$v['support_amount']);
		}			
		$deal_info['support_amount']=$deal_info1['total_virtual_price'];
		$deal_item['price_format'] = number_price_format($deal_item['price']);
		$deal_item['delivery_fee_format'] = number_price_format($deal_item['delivery_fee']);
		$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100,2);
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
		
		$GLOBALS['tmpl']->assign("deal_item",$deal_item);
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		
		
		if($deal_info['seo_title']!="")
		$GLOBALS['tmpl']->assign("seo_title",$deal_info['seo_title']);
		if($deal_info['seo_keyword']!="")
		$GLOBALS['tmpl']->assign("seo_keyword",$deal_info['seo_keyword']);
		if($deal_info['seo_description']!="")
		$GLOBALS['tmpl']->assign("seo_description",$deal_info['seo_description']);
		$GLOBALS['tmpl']->assign("page_title",$deal_info['name']);
		
		if($deal_item['is_delivery'])
		{
			$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']));
			if($consignee_list)
			$GLOBALS['tmpl']->assign("consignee_list",$consignee_list);
			else
			{
				$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
				$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			}
		}
		
		$GLOBALS['tmpl']->display("cart_index.html");		
		
	}
	
	public function check()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$num = intval($_REQUEST['num']);
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			showErr("",$ajax,url("index"));
		}
		elseif($deal_item['support_count']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			showErr("",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{	
			if(!$num)
			{
				showErr("请输入数量",$ajax);
			}
			
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
				if($buy_num >= $deal_item['maxbuy'])
					showErr("总支持数已达到上限",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
				
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				if($deal_item['limit_user'] >0)
				{
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_count=$remain_count>0?$remain_count:0;
					if($remain_count ==0)
					{	
						showErr("",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
					}
					$remain_maxbuy=$remain_count<$remain_maxbuy?$remain_count:$remain_maxbuy;
				}
				
				if($num >$remain_maxbuy)
				{
					showErr("最多可以支持数为".$remain_maxbuy,$ajax);
				}
			}
			elseif($deal_item['limit_user'])
			{
				$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
				$remain_count=$remain_count>0?$remain_count:0;
				if($remain_count ==0)
				{	
					showErr("",$ajax,url("deal#show",array("id"=>$deal_item['deal_id'])));
				}
				
				if($num >$remain_count)
				{
					showErr("最多可以支持数为".$remain_count,$ajax);
				}
			}
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			showErr("",$ajax,url("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			showErr("",$ajax,url("deal#show",array("id"=>$deal_info['id'])));
		}
		
		//抽奖商品
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_info['id'])));
		}
		
		if($deal_item['is_delivery']==1)
		{
			$consignee_id = intval($_REQUEST['consignee_id']);
			if($consignee_id==0)
			{
				$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".intval($GLOBALS['user_info']['id']));
				if($consignee_list)
				{
					showErr("请选择配送方式",$ajax);
				}
				else
				{
					$consignee = strim($_REQUEST['consignee']);
					$province = strim($_REQUEST['province']);
					$city = strim($_REQUEST['city']);
					$address = strim($_REQUEST['address']);
					$zip = strim($_REQUEST['zip']);
					$mobile = strim($_REQUEST['mobile']);
					if($consignee=="")
					{
						showErr("请填写收货人姓名",$ajax,"");	
					}
					if($province=="")
					{
						showErr("请选择省份",$ajax,"");	
					}
					if($city=="")
					{
						showErr("请选择城市",$ajax,"");	
					}
					if($address=="")
					{
						showErr("请填写详细地址",$ajax,"");	
					}
					if(!check_postcode($zip))
					{
						showErr("请填写正确的邮编",$ajax,"");	
					}
					if($mobile=="")
					{
						showErr("请填写收货人手机号码",$ajax,"");	
					}
					if(!check_mobile($mobile))
					{
						showErr("请填写正确的手机号码",$ajax,"");	
					}
					
					$data = array();
					$data['consignee'] = $consignee;
					$data['province'] = $province;
					$data['city'] = $city;
					$data['address'] = $address;
					$data['zip'] = $zip;
					$data['mobile'] = $mobile;
					$data['user_id'] = intval($GLOBALS['user_info']['id']);
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$data);
					$consignee_id = $GLOBALS['db']->insert_id();
					
				}
			}			
		}
		
		if(intval($consignee_id)==0&&$deal_item['is_delivery']==1)
		{
			showErr("请选择配送方式",$ajax,"");	
		}
		else
		{
			$memo = strim($_REQUEST['memo']);
			if($memo!=""&&$memo!="在此填写关于回报内容的具体选择或者任何你想告诉项目发起人的话")
			es_session::set("cart_memo_".intval($id),$memo);
			
			//数量
			if($deal_item['type'] ==2)
				es_session::set("cart_num_".intval($id),$num);
			
			if($deal_item['type']==1){
				$pay_money=floatval($_REQUEST['pay_money']);
				if($pay_money<=0){
					showErr("您输入的金额错误",1);
				}else{
					showSuccess("",$ajax,url("cart#pay",array("id"=>$id,'pay_money'=>$pay_money)));
				}
				
			}else{
				if($deal_item['is_delivery']==0)
				showSuccess("",$ajax,url("cart#pay",array("id"=>$id)));
				else
				showSuccess("",$ajax,url("cart#pay",array("id"=>$id,"did"=>$consignee_id)));
			}
			
		}
		
		
	}
	
	public function pay()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			app_redirect(url("index"));
		}
		elseif($deal_item['support_count']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{	
			$num=es_session::get("cart_num_".$id);
			if(!$num)
			{
				app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
				if($buy_num >= $deal_item['maxbuy'])
					app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
				
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				if($deal_item['limit_user'] >0)
				{
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_count=$remain_count>0?$remain_count:0;
					if($remain_count ==0)
					{	
						app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
					}
					$remain_maxbuy=$remain_count<$remain_maxbuy?$remain_count:$remain_maxbuy;
				}
				
				if($num >$remain_maxbuy)
				{
					showErr("最多可以支持数为".$remain_maxbuy,0,url("deal#cart",array("id"=>$id)));
					
				}
			}
			elseif($deal_item['limit_user'])
			{
				$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
				$remain_count=$remain_count>0?$remain_count:0;
				if($remain_count ==0)
				{	
					app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
				}
				
				if($num >$remain_count)
				{
					showErr("最多可以支持数为".$remain_count,0,url("deal#cart",array("id"=>$id)));
				}
				
			}
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_info['id'])));
		}
		
		//无私奉献
		if($deal_item['type']==1){
			$pay_money=floatval($_REQUEST['pay_money']);
			if($pay_money<=0){
				showErr("您输入的金额错误",0,url("deal#show",array("id"=>$deal_item['deal_id'])));	
			}
			$deal_item['price']=$pay_money;
			$GLOBALS['tmpl']->assign('pay_money',$pay_money);
  		} 
		$deal_item['price_format'] = number_price_format($deal_item['price']);
		$deal_item['delivery_fee_format'] = number_price_format($deal_item['delivery_fee']);
		$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100,2);
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
		
		if($deal_item['type'] ==2)
		{
			$deal_item['buy_num']=$num;
			$deal_item['total_price'] = $deal_item['price']*$num;
		}
		else
		{
			$deal_item['buy_num']=1;
			$deal_item['total_price'] = $deal_item['price']+$deal_item['delivery_fee'];
		}
		$deal_item['total_price_format'] = number_price_format($deal_item['total_price']);
		
		$GLOBALS['tmpl']->assign("deal_item",$deal_item);
		$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		
		
		if($deal_info['seo_title']!="")
		$GLOBALS['tmpl']->assign("seo_title",$deal_info['seo_title']);
		if($deal_info['seo_keyword']!="")
		$GLOBALS['tmpl']->assign("seo_keyword",$deal_info['seo_keyword']);
		if($deal_info['seo_description']!="")
		$GLOBALS['tmpl']->assign("seo_description",$deal_info['seo_description']);
		$GLOBALS['tmpl']->assign("page_title",$deal_info['name']);
		
		$memo = es_session::get("cart_memo_".$id);
		$consignee_id = intval($_REQUEST['did']);
		$GLOBALS['tmpl']->assign("memo",$memo);
		$GLOBALS['tmpl']->assign("consignee_id",$consignee_id);
		
		
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
		
		$GLOBALS['tmpl']->assign("coll",is_tg(true));
		$GLOBALS['tmpl']->display("cart_pay.html");
	}

	public function go_pay()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		 
		$id = intval($_REQUEST['id']);
		$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
			if($paypassword==''){
				showErr("请输入支付密码",0);	
			}
			if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
				showErr("支付密码错误",0);	
			}
		}
		$consignee_id = intval($_REQUEST['consignee_id']);
		$credit = floatval($_REQUEST['credit']);
		$pay_score =intval($_REQUEST['pay_score']);
		if($pay_score >0)
		{
			$score_array=score_to_money($pay_score);
			$pay_score_money=$score_array['score_money'];
			$pay_score=$score_array['score'];
		}else
		{
			$pay_score_money=0;
		}
		
		$is_tg=intval($_REQUEST['is_tg']);
		if($is_tg){
			if(!$GLOBALS['is_user_tg']){
				$jump_url=APP_ROOT."/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id'];
				showErr("您未绑定第三方接口无法支付,点击确定后跳转到绑定页面",0,$jump_url);
			}
		}

		$memo = strim($_REQUEST['memo']);
		$payment_id = intval($_REQUEST['payment']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			app_redirect(url("index"));
		}
		elseif($deal_item['support_count']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{	
			$num=es_session::get("cart_num_".$id);
			if(!$num)
			{
				app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
				if($buy_num >= $deal_item['maxbuy'])
					app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
				
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				if($deal_item['limit_user'] >0)
				{
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_count=$remain_count>0?$remain_count:0;
					if($remain_count ==0)
					{	
						app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
					}
					$remain_maxbuy=$remain_count<$remain_maxbuy?$remain_count:$remain_maxbuy;
				}
				
				if($num >$remain_maxbuy)
				{
					showErr("最多可以支持数为".$remain_maxbuy,0,url("deal#cart",array("id"=>$id)));
					
				}
			}
			elseif($deal_item['limit_user'])
			{
				$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
				$remain_count=$remain_count>0?$remain_count:0;
				if($remain_count ==0)
				{	
					app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
				}
				
				if($num >$remain_count)
				{
					showErr("最多可以支持数为".$remain_count,0,url("deal#cart",array("id"=>$id)));
				}
				
			}
			
			es_session::delete("cart_num_".$id);
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			app_redirect(url("deal#show",array("id"=>$deal_info['id'])));
		}
		
		if(intval($consignee_id)==0&&$deal_item['is_delivery']==1)
		{
			showErr("请选择配送方式",0,get_gopreview());	
		}
		//无私奉献
		if($deal_item['type']==1){
			$pay_money=floatval($_REQUEST['pay_money']);
			if($pay_money<=0){
				showErr("您输入的金额错误",0,url("deal#show",array("id"=>$deal_item['deal_id'])));	
			}
			$deal_item['price']=$pay_money;
			$order_info['type'] = 2;
  		}elseif($deal_item['type']==2){
  			$order_info['type'] = 3;//抽奖商品
  		}else{
  			if($deal_info['type'] ==2)
  				$order_info['type']=7;//房产众筹
  			else
  				$order_info['type']=$deal_info['type'];
  		}
		$order_info['is_tg'] = $is_tg;
		$order_info['deal_id'] = $deal_info['id'];
		$order_info['deal_item_id'] = $deal_item['id'];
		$order_info['user_id'] = intval($GLOBALS['user_info']['id']);
		$order_info['user_name'] = $GLOBALS['user_info']['user_name'];
		$order_info['deal_price'] = $deal_item['price'];
		$order_info['support_memo'] = $memo;
		$order_info['payment_id'] = $payment_id;
		$order_info['bank_id'] = strim($_REQUEST['bank_id']);
		if($deal_item['type'] ==2)
		{
			$order_info['total_price'] = $deal_item['price']*$num;
			$order_info['num'] = $num;
			$order_info['delivery_fee'] = 0;
		}
		else
		{
			$order_info['total_price'] = $deal_item['price']+$deal_item['delivery_fee'];
			$order_info['delivery_fee'] = $deal_item['delivery_fee'];
		}
		if($deal_item['is_share'] ==1)//有分红
		{
			$order_info['share_fee']=$deal_item['share_fee'];
			$order_info['share_status']=0;
		}else{
			$order_info['share_fee']=0;
		}
		
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
		
		//$max_credit= $order_info['total_price']<($GLOBALS['user_info']['money'])?$order_info['total_price']:$GLOBALS['user_info']['money'];
		//if($max_credit<0){
		//	$max_credit=0;
		//}
		//$credit = $credit>$max_credit?$max_credit:$credit;
		
		
		if($credit>0){
			$order_info['credit_pay'] = $credit;
		}
		
		if($pay_score>0)
		{
			$order_info['score'] = $pay_score;
			$order_info['score_money'] = $pay_score_money;
		}
		
		$order_info['online_pay'] = 0;
		$order_info['deal_name'] = $deal_info['name'];
		$order_info['order_status'] = 0;
		$order_info['create_time']	= NOW_TIME;
		if($consignee_id>0)
		{
			$consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id = ".$consignee_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if(!$consignee_info&&$deal_item['is_delivery']==1)
			{
				showErr("请选择配送方式",0,get_gopreview());	
			}
			$order_info['consignee'] = $consignee_info['consignee'];
			$order_info['zip'] = $consignee_info['zip'];
			$order_info['address'] = $consignee_info['address'];
			$order_info['province'] = $consignee_info['province'];
			$order_info['city'] = $consignee_info['city'];
			$order_info['mobile'] = $consignee_info['mobile'];
		}
			
		$order_info['is_success'] = $deal_info['is_success'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info);
		
		$order_id = $GLOBALS['db']->insert_id();
		 
		if($order_id>0)
		{
//			if($credit>0)
//			{
//				
//				require_once APP_ROOT_PATH."system/libs/user.php";
//				$re=modify_account(array("money"=>"-".$credit),intval($GLOBALS['user_info']['id']),"支持".$deal_info['name']."项目支付");				
//				if($re){
//						$GLOBALS['db']->query("update  ".DB_PREFIX."deal_order set credit_pay=".$credit." where id=".$order_id);
//				}
//			}
			if($is_tg){
				$sign=md5(md5($paypassword).$order_id);
				$url=APP_ROOT."/index.php?ctl=collocation&act=RegisterCreditor&order_id=".$order_id."&sign=".$sign;
				//showSuccess("",0,$url);
				app_redirect($url);
			}
			
			$result = pay_order($order_id);
		 
			if($result['status']==0)
			{
					if($payment_info['online_pay'] ==0)
					{
						//app_redirect(url("cart#onlinepay",array("id"=>$order_id)));
						showSuccess("线下支付",0,url("account#view_order",array("id"=>$order_id)));
					}else
					{
						$money = $result['money'];
						$payment_notice['create_time'] = NOW_TIME;
						$payment_notice['user_id'] = intval($GLOBALS['user_info']['id']);
						$payment_notice['payment_id'] = $payment_id;
						$payment_notice['money'] = $money;
						$payment_notice['bank_id'] = strim($_REQUEST['bank_id']);
						$payment_notice['order_id'] = $order_id;
						$payment_notice['memo'] = $memo;
						$payment_notice['deal_id'] = $deal_info['id'];
						$payment_notice['deal_item_id'] = $deal_item['id'];
						$payment_notice['deal_name'] = $deal_info['name'];
						
						do{
							$payment_notice['notice_sn'] = to_date(NOW_TIME,"Ymdhi").rand(10000,99999);
							$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
							$notice_id = $GLOBALS['db']->insert_id();
						}while($notice_id==0);
	 					app_redirect(url("cart#jump",array("id"=>$notice_id)));
					}
					
			}
			elseif($result['status']==1||$result['status']==2)
			{
				app_redirect(url("account#credit")); 
			}elseif($result['status']==5){
				showErr("很遗憾,订单支付失败");
			}
			else
			{
				app_redirect(url_get_account($order_id));
			}
		}
		else
		{
			showErr("下单失败",0,get_gopreview());	
		}		
		
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
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$notice_info['deal_id']);
		
		if($GLOBALS['is_tg']&&$deal_info['ips_bill_no']){
			
			
		}else{
			$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$notice_info['payment_id']);
			$class_name = $payment_info['class_name']."_payment";
			
			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
			$o = new $class_name;
			
			header("Content-Type:text/html; charset=utf-8");
			echo $o->get_payment_code($notice_id);
			
		}
		
	}
	
	public function onlinepay(){
		if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
		$order_id = intval($_REQUEST['id']);
		$order_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id=".$order_id." and user_id=".intval($GLOBALS['user_info']['id'])."");	
		if(!$order_info)
			app_redirect(url("accout#index"));
		
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$order_info['payment_id']);
		
		$GLOBALS['tmpl']->assign("payment_info",$payment_info);
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		$GLOBALS['tmpl']->display("onlinepay.html");
	}
	public function confirmpayment(){
		require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass/confirmPayment.php";
		$payment = intval($_REQUEST['payment']);
		$orderid = strim($_REQUEST['orderid']);
		$validatecode = strim($_REQUEST['validatecode']);
		if($payment==1){
			$info = confirmPayment($validatecode,$orderid,$payment);
			if($info['error_msg']){
				echo $info['error_msg'];
			}else{
				$this->response($info);
			}
		}
		if($payment==2&&$validatecode==''){
			$GLOBALS['tmpl']->assign("payment",$payment);
			$GLOBALS['tmpl']->assign("orderid",$orderid);
			$GLOBALS['tmpl']->assign("validatecode",$validatecode);
			$GLOBALS['tmpl']->display("confirmpayment.html");
		}
		
	}
	public function save_confirmpayment(){
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		require_once APP_ROOT_PATH."system/payment/YeepayInvestmentPass/confirmPayment.php";
		$validatecode = strim($_REQUEST['validatecode']);
		$orderid = strim($_REQUEST['orderid']);
		if($validatecode==''){
			$result['status']=0;
			$result['info']="验证码不能为空!";
			ajax_return($result);
			return false;
		}
		else
		{
			$info = confirmPayment($validatecode,$orderid,2);
			log_result(var_export($info,true));
			//log_result(var_export($info,true));
			if($info['error_msg']){
				$result['status']=0;
				$result['info'] = $info['error_msg'];
				ajax_return($result);
				return false;
			}else{
				$this->response($info);
			}
		}
		
	}
	public function response($request)
	{
        $message_msg = trim($request['errormsg']);
		//log_result(var_export($request,true));
        $succeed = trim($request['status']);   // 获取交易结果,1成功,0失败,2：撤销
        $trxId = trim($request['yborderid']);  //易宝的交易流水号
        $orderid = trim($request['orderid']);  // 获取订单ID

		$payment_notice_sn = $orderid;
    	$money = $request['amount']/100;
    	$outer_notice_sn = $trxId;
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");   
		if(!$message_msg)
		{	
			if($trxId){
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
	 			showSuccess($rs['info'],0,$rs['jump'],0);
			}else{
			    showErr("支付失败",0,url("index"),1);
			}   
		}else{
		    showErr($message_msg,0,url("index"),1);
		}  
	}
}
?>