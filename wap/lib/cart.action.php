<?php
require APP_ROOT_PATH.'wap/app/shop_lip.php';
class cartModule{
	public function index()
	{	
 		if(!$GLOBALS['user_info'])
		{
			$target = "URL-dealID-".intval($_REQUEST['deal_id']);
			app_redirect(url_wap("user#login",array("target"=>$target)));
		}
		//(普通众筹)支持之前需要用户绑定手机号
		if(!$GLOBALS['user_info']['mobile'])
		{
			app_redirect(url_wap("user#user_bind_mobile",array("cid"=>intval($_REQUEST['id']))));
		}
		$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
		
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			app_redirect(url_wap("index"));
		}
		elseif(($deal_item['support_count']+$deal_item['virtual_person'])>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{	
			$num=intval($_REQUEST['num']);
			if(!$num)
			{
				app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
				if($buy_num >= $deal_item['maxbuy'])
					app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
				
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				if($deal_item['limit_user'] >0)
				{
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_count=$remain_count>0?$remain_count:0;
					if($remain_count ==0)
					{	
						app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
					}
					$remain_maxbuy=$remain_count<$remain_maxbuy?$remain_count:$remain_maxbuy;
				}
				
				if($num >$remain_maxbuy)
				{
					showErr("最多可以支持数为".$remain_maxbuy,0,url_wap("deal#cart",array("id"=>$id)));
					
				}
			}
			elseif($deal_item['limit_user'])
			{
				$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
				$remain_count=$remain_count>0?$remain_count:0;
				if($remain_count ==0)
				{	
					app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
				}
				
				if($num >$remain_count)
				{
					showErr("最多可以支持数为".$remain_count,0,url_wap("deal#cart",array("id"=>$id)));
				}
				
			}
			
			$GLOBALS['tmpl']->assign("num",$num);
		}
		//抽奖end
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		$deal_info = cache_deal_extra($deal_info);
		init_deal_page_wap($deal_info);
		
		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		////抽奖商品
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_info['id'])));
		}
		
		$deal_item['consigee_url']=url_wap("settings#add_consignee",array("deal_item_id"=>$id));
		
		//无私奉献
		if($deal_item['type']==1){
			$pay_money=floatval($_REQUEST['pay_money']);
			if($pay_money<=0){
				showErr("您输入的金额错误",0,url_wap("deal#show",array("id"=>$deal_item['deal_id'])));	
			}
			$deal_item['price']=$pay_money;
			$GLOBALS['tmpl']->assign('pay_money',$pay_money);
  		} 
		
		$deal_item['price_format'] = number_price_format($deal_item['price']);
		$deal_item['delivery_fee_format'] = number_price_format($deal_item['delivery_fee']);
		if($deal_item['type'] ==2)
		{
			
			$deal_item['total_price'] = $deal_item['price']*$num+$deal_item['delivery_fee'];
			$deal_item['num']=$num;
		}else{
			$deal_item['total_price'] = $deal_item['price']+$deal_item['delivery_fee'];
			$deal_item['num']=1;
		}
		$deal_item['total_price_format'] = number_price_format($deal_item['total_price']);
		$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100,2);
		$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
		
		$GLOBALS['tmpl']->assign("deal_item",$deal_item);
		 
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
		$GLOBALS['tmpl']->assign("coll",is_tg(true));
		$GLOBALS['tmpl']->assign("page_title","提交订单");
		$GLOBALS['tmpl']->display("cart_index.html");
	}
	
	public function go_pay()
	{

		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		
		 
		$id = intval($_REQUEST['id']);
		$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
		if($paypassword==''){
			showErr("请输入付款密码",0);	
		}
		if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
			showErr("付款密码错误",0);	
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
			$pay_score=0;
			$pay_score_money=0;
		}
		
		
		$is_tg=intval($_REQUEST['is_tg']);
		if($is_tg){
			if(!$GLOBALS['is_user_tg']){
				$jump_url=get_domain().APP_ROOT."/wap/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=".$GLOBALS['user_info']['id'];
				//$jump_url = get_domain().url_wap("collocation#CreateNewAcct",array('user_type'=>0,'user_id'=>$GLOBALS['user_info']['id']));
				showErr("您未绑定第三方接口无法支付,点击确定后跳转到绑定页面",0,$jump_url);
			}
		}
		
		$memo = strim($_REQUEST['memo']);
		$payment_id = intval($_REQUEST['payment']);
		//@by slf
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		if(!$deal_item)
		{
			app_redirect(url_wap("index"));
		}
		elseif($deal_item['support_count']>=$deal_item['limit_user']&&$deal_item['limit_user']!=0)
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if($deal_item['type'] ==2)
		{	
			$num=intval($_REQUEST['num']);
			if(!$num)
			{
				app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
			}
			
			if($deal_item['maxbuy'] >0)
			{
				$buy_num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id =".intval($GLOBALS['user_info']['id'])." and deal_item_id=".$id." and type=3 and order_status=3 and is_refund=0");
				if($buy_num >= $deal_item['maxbuy'])
					app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
				
				$remain_maxbuy=$deal_item['maxbuy']-$buy_num;
				if($deal_item['limit_user'] >0)
				{
					$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
					$remain_count=$remain_count>0?$remain_count:0;
					if($remain_count ==0)
					{	
						app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
					}
					$remain_maxbuy=$remain_count<$remain_maxbuy?$remain_count:$remain_maxbuy;
				}
				
				if($num >$remain_maxbuy)
				{
					showErr("最多可以支持数为".$remain_maxbuy,0,url_wap("deal#cart",array("id"=>$id)));
					
				}
			}
			elseif($deal_item['limit_user'])
			{
				$remain_count=$deal_item['limit_user']-$deal_item['support_count'];
				$remain_count=$remain_count>0?$remain_count:0;
				if($remain_count ==0)
				{	
					app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
				}
				
				if($num >$remain_count)
				{
					showErr("最多可以支持数为".$remain_count,0,url_wap("deal#cart",array("id"=>$id)));
				}
				
			}
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_item['deal_id']);
		if(!$deal_info)
		{
			app_redirect(url_wap("index"));
		}
		elseif($deal_info['begin_time']>NOW_TIME||($deal_info['end_time']<NOW_TIME&&$deal_info['end_time']!=0))
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_item['deal_id'])));
		}
		
		//抽奖商品
		if( $deal_item['type'] == 2 && $deal_info['lottery_draw_time'] >0)
		{
			app_redirect(url_wap("deal#show",array("id"=>$deal_info['id'])));
		}
		
		if(intval($consignee_id)==0&&$deal_item['is_delivery']==1)
		{
			showErr("请选择配送方式",0,get_gopreview_wap());	
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
		
		$order_info['deal_id'] = $deal_info['id'];
		$order_info['deal_item_id'] = $deal_item['id'];
		$order_info['user_id'] = intval($GLOBALS['user_info']['id']);
		$order_info['user_name'] = $GLOBALS['user_info']['user_name'];
		$order_info['deal_price'] = $deal_item['price'];
		$order_info['support_memo'] = $memo;
		$order_info['payment_id'] = $payment_id;
		$order_info['bank_id'] = strim($_REQUEST['bank_id']);
		
		if($deal_item['type'] ==2)//抽奖商品
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
		
		if($deal_item['is_share'] ==1)//有分红  chh 15/06/25
		{
			$order_info['share_fee']=$deal_item['share_fee'];
			$order_info['share_status']=0;
		}else{
			$order_info['share_fee']=0;
		}
		/*
		$max_credit= $order_info['total_price']<$GLOBALS['user_info']['money']?$order_info['total_price']:$GLOBALS['user_info']['money'];
		$credit = $credit>$max_credit?$max_credit:$credit;
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
			if($payment_id >0)
			{
				$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$payment_id);
				if(!$payment_info)
					showErr("不支持该支付方式");
			}
		}
			
		if ($credit > 0 && $GLOBALS['user_info']['money'] >= $credit)
			$order_info ['credit_pay'] = $credit;
			
		if ($pay_score > 0 &&$GLOBALS['user_info']['score'] >= $pay_score)
		{
			$order_info['score'] = $pay_score;
			$order_info['score_money'] = $pay_score_money;
		}
		
//		$order_info['credit_pay'] = $credit;
		$order_info['online_pay'] = 0;
		$order_info['deal_name'] = $deal_info['name'];
		$order_info['order_status'] = 0;
		$order_info['create_time']	= NOW_TIME;
		if($consignee_id>0)
		{
			$consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id = ".$consignee_id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if(!$consignee_info&&$deal_item['is_delivery']==1)
			{
				showErr("请选择配送方式",0,get_gopreview_wap());	
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
//			if($order_info['credit_pay']>0)
//			{
//				require_once APP_ROOT_PATH."system/libs/user.php";
//				modify_account(array("money"=>"-".$order_info['credit_pay']),intval($GLOBALS['user_info']['id']),"支持".$order_info['deal_name']."项目支付");				
//			}
			$result = pay_order($order_id);
			if($result['status']==0)
			{
				if($is_tg){
					$sign=md5(md5($paypassword).$order_id);
 					$url=get_domain().APP_ROOT."/wap/index.php?ctl=collocation&act=RegisterCreditor&order_id=".$order_id."&sign=".$sign;
 					//showSuccess("",0,$url);
 					app_redirect($url);
				}elseif($payment_info['online_pay'] ==0)
				{
					showSuccess("线下支付",0,url_wap("account#view_order",array("id"=>$order_id)));
				}
				else{
					$money = $result['money'];
					$payment_notice['create_time'] = NOW_TIME;
					$payment_notice['user_id'] = intval($GLOBALS['user_info']['id']);
					$payment_notice['payment_id'] = $order_info['payment_id'];
					//@by slf
					$payment_notice['money'] = $money;
	 				$payment_notice['order_id'] = $order_id;
					$payment_notice['memo'] = $order_info['memo'];
					$payment_notice['deal_id'] = $order_info['deal_id'];
					$payment_notice['deal_item_id'] = $order_info['deal_item_id'];
					$payment_notice['deal_name'] = $order_info['deal_name'];
					$payment_notice['bank_id'] = $order_info['bank_id'];
					do{
						$payment_notice['notice_sn'] = to_date(NOW_TIME,"Ymdhi").rand(10000,99999);
						$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
						$notice_id = $GLOBALS['db']->insert_id();
					}while($notice_id==0);
					
					app_redirect(url_wap("cart#jump",array("id"=>$notice_id)));
				}
			}elseif($result['status']==1)
			{
				$data['pay_status'] = 0;
				$data['pay_info'] = '订单过期.';
				$data['show_pay_btn'] = 0;
 				$GLOBALS['tmpl']->assign('data',$data);
 				$GLOBALS['tmpl']->display('pay_order_index.html');
 				
 				
			}elseif($result['status']==2)
			{
				$data['pay_status'] = 0;
				$data['pay_info'] = '订单无库存.';
				$data['show_pay_btn'] = 0;
 				$GLOBALS['tmpl']->assign('data',$data);
 				$GLOBALS['tmpl']->display('pay_order_index.html');
 			}
			else
			{
				$data['pay_status'] = 1;
				$data['pay_info'] = '订单支付成功.';
				$data['show_pay_btn'] = 0;
 				$GLOBALS['tmpl']->assign('data',$data);
 				$GLOBALS['tmpl']->display('pay_order_index.html');
 			}
 			
			//app_redirect(url_wap("cart#pay_order",array("order_id"=>$order_id)));
		}
		else
		{
			showErr("下单失败",0,get_gopreview_wap());	
		}		
		
	}
 	
	public function jump()
	{
 		$notice_id = intval($_REQUEST['id']);
		$notice_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id."   and user_id = ".intval($GLOBALS['user_info']['id']));  		
  		if($notice_info['is_paid']==1)
		{
			$data['pay_status'] = 1;
			$data['pay_info'] = '已支付.';
			$data['show_pay_btn'] = 0;
			$GLOBALS['tmpl']->assign('data',$data);
			$GLOBALS['tmpl']->display('pay_order_index.html');
		}else{
			$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$notice_info['payment_id']);
	 		$class_name = $payment_info['class_name']."_payment";
  			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
 			$o = new $class_name;
 			if($payment_info['class_name']=='Wnewpay'){
 				echo $o->get_payment_code($notice_id);
 			}elseif($payment_info['class_name']=='Wjdpay'){
  				echo $o->get_payment_code($notice_id);
 			}elseif($payment_info['class_name']=='Wbfpay'){
  				echo $o->get_payment_code($notice_id);
 			}else{
 				$pay= $o->get_payment_code($notice_id);
 	  			app_redirect($pay['notify_url']);
 			}
 	  		
 		}
		
		 
	}
	
	public function wx_jspay(){
		$notice_id = intval($_REQUEST['id']);
		$notice_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id."   and user_id = ".intval($GLOBALS['user_info']['id']));
 		 
 		if($notice_info['is_paid']==1)
		{
			$data['pay_status'] = 1;
			$data['pay_info'] = '已支付.';
			$data['show_pay_btn'] = 0;
			$data['deal_id'] = $notice_info['deal_id'];
			$GLOBALS['tmpl']->assign('data',$data);
			$GLOBALS['tmpl']->display('pay_order_index.html');
		}else{
			$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$notice_info['payment_id']);
	 		$class_name = $payment_info['class_name']."_payment";
 			require_once APP_ROOT_PATH."system/payment/".$class_name.".php";
 			$o = new $class_name;
	  		$pay= $o->get_payment_code($notice_id);
  	  		$GLOBALS['tmpl']->assign('jsApiParameters',$pay['parameters']);
	  		$notice_info['pay_status'] = 0;
			$notice_info['pay_info'] = '未支付.';
			$notice_info['show_pay_btn'] = 1;
			$notice_info['deal_id'] = $notice_info['deal_id'];
 	  		$GLOBALS['tmpl']->assign('data',$notice_info);
 	  		$payment_info['config'] = unserialize($payment_info['config']);
 	  		$GLOBALS['tmpl']->assign('type',$payment_info['config']['type']);
	  		$GLOBALS['tmpl']->display('pay_wx_jspay.html');
  		}
	}
	
	public function pay_result(){
		$notice_id = intval($_REQUEST['id']);
		if(!$notice_id){
				$data['pay_status'] = 1;
				$data['pay_info'] = '支付失败.';
				$data['show_pay_btn'] = 0;
		}else{
			$notice_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id."   and user_id = ".intval($GLOBALS['user_info']['id']));
			if($notice_info['is_paid']==1){
				$data['pay_status'] = 1;
				$data['pay_info'] = '支付成功.';
				$data['show_pay_btn'] = 0;
 			}else{
				$data['pay_status'] = 1;
				$data['pay_info'] = '支付失败.';
				$data['show_pay_btn'] = 0;
 			}
		}
		$GLOBALS['tmpl']->assign('data',$data);
		$GLOBALS['tmpl']->display('pay_order_index.html');
		 
	}
}


?>