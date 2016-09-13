<?php

// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class go_pay
{
	public function index()
	{
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$consignee_id = intval ( $GLOBALS ['request'] ['consignee_id'] );
		$id = intval ( $GLOBALS ['request'] ['id'] );
		$memo = strim ( $GLOBALS ['request'] ['memo'] );
		$payment_id = intval ( $GLOBALS ['request'] ['payment'] );
		
		$ips_bill_no_pay = intval ( $GLOBALS ['request'] ['ips_bill_no_pay'] );//1表用托管支付,0表不是，用第三方托管支付时传1值
		
		$paypassword = strim ( $GLOBALS ['request'] ['paypassword'] );//支付密码 chh 15/06/25
		$credit = floatval ( $GLOBALS ['request'] ['credit'] );
		$pay_score = intval ( $GLOBALS ['request'] ['pay_score'] );//积分 chh 15/06/25
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
		// $bank_id = intval ( $GLOBALS ['request'] ['bank_id'] );
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$root ['user_login_status'] = 1;
			$order_info ['payment_id'] = 0;
		
			if(md5($paypassword) != $user['paypassword'])
			{
				$root ['info'] = "支付密码不正确";
				$root ['response_code'] = 0;
				output ( $root );
			}
			
			
			$deal_item = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_item where id = " . $id );
			
			$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where is_delete = 0 and is_effect = 1 and id = " . $deal_item ['deal_id'] );
			
			if($ips_bill_no_pay ==1)
			{
				$is_tg=is_tg();
				if(!$is_tg)
				{
					$root ['info'] = "网站没有开启第三方支付";
					$root ['response_code'] = 0;
					output ( $root );
				}
				
				if(!$deal_info['ips_bill_no'])
				{
					$root ['info'] = "项目没有开启第三方支付";
					$root ['response_code'] = 0;
					output ( $root );
				}
				
				$is_user_tg=is_user_tg_mapi($is_tg,$user['ips_acct_no'],$user['ips_mer_code']);
				if(!$is_user_tg)
				{
					$root ['info'] = "您没有绑定第三方托管";
					$root ['response_code'] = 0;
					output ( $root );
				}
			}
			
			
			if (intval ( $consignee_id ) == 0 && $deal_item ['is_delivery'] == 1)
			{
				$root ['info'] = "请选择配送方式";
				$root ['response_code'] = 0;
				output ( $root );
			}
			
			//无私奉献
			if($deal_item['type']==1){
				$pay_money=floatval($GLOBALS ['request']['pay_money']);//无私奉献金额
				if($pay_money<=0){
					$root ['info'] = "无私奉献金额";
					$root ['response_code'] = 0;
					output ( $root );
				}
				$deal_item['price']=$pay_money;
				$order_info['type'] = 2;//2表示无私奉献
	  		} 
			
			$order_info ['deal_id'] = $deal_info ['id'];
			$order_info ['deal_item_id'] = $deal_item ['id'];
			$order_info ['user_id'] = intval ( $user ['id'] );
			$order_info ['user_name'] = $user ['user_name'];
			$order_info ['total_price'] = $deal_item ['price'] + $deal_item ['delivery_fee'];
			$order_info ['delivery_fee'] = $deal_item ['delivery_fee'];
			$order_info ['deal_price'] = $deal_item ['price'];
			$order_info ['support_memo'] = $memo;
			$order_info ['payment_id'] = $payment_id;
			// $order_info['bank_id'] = $bank_id;
			if($deal_item['is_share'] ==1)//有分红  chh 15/06/25
			{
				$order_info['share_fee']=$deal_item['share_fee'];
				$order_info['share_status']=0;
			}else{
				$order_info['share_fee']=0;
			}
			
			if($ips_bill_no_pay ==1)
			{
				$order_info ['is_tg'] = 1;
			}
			else{
				/*
				$max_credit = $order_info ['total_price'] < $user ['money'] ? $order_info ['total_price'] : $user ['money'];
				if ($max_credit < 0)
				{
					$max_credit = 0;
				}
				$credit = $credit > $max_credit ? $max_credit : $credit;
				*/
				$credit_score_money=$pay_score_money + $credit; //chh 15/06/25
				if($credit>0 && $credit> $user['money'])
				{
					$root ['info'] = "余额最多只能用".format_price($user['money']);
					$root ['response_code'] = 0;
					output ( $root );
				}
				if($pay_score >0 && $pay_score > $GLOBALS['user_info']['score'])
				{
					$root ['info'] = "积分最多只能用".$user['score'];
					$root ['response_code'] = 0;
					output ( $root );
				}
				if( $credit_score_money > $order_info['total_price'])
				{
					$root ['info'] = "支付超出";
					$root ['response_code'] = 0;
					output ( $root );
				}
				if( intval(($order_info['total_price'] - $credit_score_money)*100) > 0 && $payment_id <=0)
				{
					$root ['response_code'] = 0;
					$root ['info'] = "发送异常";//请选择支付方式
					output ( $root );
				}
				
				if ($credit > 0 && $user ['money'] >= $credit)
					$order_info ['credit_pay'] = $credit;
					
				if ($pay_score > 0 && $user ['score'] >= $pay_score)
				{
					$order_info['score'] = $pay_score;
					$order_info['score_money'] = $pay_score_money;
				}
			}
			
			$order_info ['online_pay'] = 0;
			$order_info ['deal_name'] = $deal_info ['name'];
			$order_info ['order_status'] = 0;
			$order_info ['create_time'] = NOW_TIME;
			
			if ($consignee_id > 0)
			{
				$consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where id = " . $consignee_id . " and user_id = " . intval ( $user ['id'] ) );
				if (! $consignee_info && $deal_item ['is_delivery'] == 1)
				{
					$root ['info'] = "请选择配送方式";
					$root ['response_code'] = 0;
					output ( $root );
				}
				$order_info ['consignee'] = $consignee_info ['consignee'];
				$order_info ['zip'] = $consignee_info ['zip'];
				$order_info ['address'] = $consignee_info ['address'];
				$order_info ['province'] = $consignee_info ['province'];
				$order_info ['city'] = $consignee_info ['city'];
				$order_info ['mobile'] = $consignee_info ['mobile'];
			}
			$order_info ['is_success'] = $deal_info ['is_success'];
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order", $order_info );
			$root ['order_info'] = $order_info;
			$order_id = $GLOBALS ['db']->insert_id ();
			if ($order_id > 0)
			{
				$root ['response_code'] = 1;
				$root ['info'] = "下单成功";
				$root ['user_login_status'] = 1;
				$root ['order_id'] = $order_id;
				$root ['ips_bill_no_pay'] = $ips_bill_no_pay;//1表用托管支付,0表不是
				$root ['response_code'] = 1;
			} else
			{
				$root ['info'] = "下单失败";
			}
		} else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
	
	// 1 全部余额 credit>=total_price （满足user['money']>=credit） 无支付方式 payment <=0
	// 2 部分余额 0<credit<total_price（满足user['money']>=credit） 带支付方式 payment >0
	// 3 无余额支付 credit<=0 带支付方式 payment>0 下单
}

?>