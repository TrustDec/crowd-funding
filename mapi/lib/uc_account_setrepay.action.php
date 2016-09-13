<?php
/**
 * @author 作者
 * @version 创建时间：2015-5-20  类说明 个人中心我的项目列表
 */
class uc_account_setrepay
{
	public function index()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码                                             
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
		}
		
		$order_id = intval($GLOBALS ['request']['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id." and order_status = 3 and is_refund = 0");
		if(!$order_info)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "无权为该订单设置回报";
			output($root);
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$order_info['deal_id']." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($user['id']));
		if(!$deal_info)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "无权为该订单设置回报";
			output($root);
		}
		
		$order_info_data['repay_memo'] = strim($GLOBALS ['request']['repay_memo']);
		if($order_info_data['repay_memo']=="")
		{
			$root ['response_code'] = 0;
			$root ['info'] = "请输入回报内容";
			output($root);
		}
		
		$order_info_data['logistics_company'] = strim($GLOBALS ['request']['logistics_company']);
		$order_info_data['logistics_links'] = strim($GLOBALS ['request']['logistics_links']);
		$order_info_data['logistics_number'] = strim($GLOBALS ['request']['logistics_number']);
		$order_info_data['repay_time'] = get_gmtime();
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info_data,"UPDATE","id=".$order_info['id']);
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
			send_notify($order_info['user_id'],"您支持的项目".$order_info['deal_name']."回报已发放","account#view_order","id=".$order_info['id']);
			$root ['response_code'] = 1;
			$root ['info'] = "回报发放成功";
		}else
		{
			$root ['response_code'] = 0;
			$root ['info'] = "回报设置失败";
		}
		output($root);
	}
	
	
	//会员中心 
	//产品
	public function index2()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$order_id= intval($GLOBALS['request']['id']);
		
		$order_info = $GLOBALS['db']->getRow("select d.*,di.description as item_description from ".DB_PREFIX."deal_order as d left join ".DB_PREFIX."deal_item as di on di.id=d.deal_item_id where d.id = ".$order_id." and d.order_status = 3 and d.is_refund = 0 and d.deal_item_id >0 order by d.create_time desc");
		$order_info_return = array();
		$order_info_return['id'] = $order_info['id'];
		$order_info_return['user_name'] = $order_info['user_name'];
		$order_info_return['deal_price'] = $order_info['deal_price'];
		$order_info_return['delivery_fee'] = $order_info['delivery_fee'];
		$order_info_return['total_price'] = $order_info['total_price'];
		$order_info_return['share_money']=$order_info['share_money'];
		$order_info_return['format_deal_price'] = format_price($order_info['deal_price']);
		$order_info_return['format_delivery_fee'] = format_price($order_info['delivery_fee']);
		$order_info_return['format_total_price'] = format_price($order_info['total_price']);
		$order_info_return['format_share_money']=format_price($order_info['share_money']);
		$order_info_return['pay_time'] = $order_info['pay_time'];
		$order_info_return['format_pay_time'] = to_date($order_info['pay_time']);
		$order_info_return['item_description']=$order_info['item_description'];
		$order_info_return['province']=$order_info['province'];
		$order_info_return['city']=$order_info['city'];
		$order_info_return['address']=$order_info['address'];
		$order_info_return['zip']=$order_info['zip'];
		$order_info_return['consignee']=$order_info['consignee'];
		$order_info_return['mobile']=$order_info['mobile'];
		
		if($order_info['order_status'] ==1)
		{
			$order_status_info="因项目过期，资金已退到个人帐户";
		}elseif($order_info['order_status'] ==2){
			$order_status_info="因项目限额已满，资金已退到个人帐户";
		}
		elseif($order_info['order_status'] ==3){
			$order_status_info="支付成功";
		}
		else{
			$order_status_info="支付未完成";
			if($order_info['credit_pay'] > 0 || $order_info['score'] > 0)
			{
				$order_status_info .="(预支付：";
				if($order_info['credit_pay'] > 0)
					$order_status_info .="-余额支付".format_price($order_info['credit_pay']);
				if($order_info['score']>0)
					$order_status_info .="-积分支付".format_price($order_info['score_money']);
			}
		}
		$order_info_return['order_status_info']=$order_status_info;
		$order_info_return['order_status']=$order_info['order_status'];
		
		
		
		output ( $root );
	}
	
	//股权
	public function invest()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$order_id= intval($GLOBALS['request']['id']);
		$order_info = $GLOBALS['db']->getRow("select d.*,i.stock_value as investment_stock_value,dl.transfer_share as transfer_share,dl.limit_price as limit_price from ".DB_PREFIX."deal_order as d left join (".DB_PREFIX."investment_list as i,".DB_PREFIX."deal as dl) on (i.id = d.invest_id and d.deal_id = dl.id)  where d.id = ".$order_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0 order by d.create_time desc ");
		$order_info_return = array();
		
		$order_info_return['id'] = $order_info['id'];
		$order_info_return['user_name'] = $order_info['user_name'];
		$order_info_return['pay_time'] = $order_info['pay_time'];
		$order_info_return['format_pay_time'] = to_date($order_info['pay_time']);
		
		//用户所占股份
		$order_info_return['user_stock']= number_format(($order_info['total_price']/$order_info['limit_price'])*$order_info['transfer_share'],2);			
		//项目金额
		$order_info_return['stock_value'] =number_format($order_info['limit_price']/10000,2);
		//应付金额
		$order_info_return['total_price'] = $order_info['total_price'];
		$order_info_return['format_total_price'] =number_format($order_info['total_price']/10000,2);
		
		if($order_info['order_status'] ==1)
		{
			$order_status_info="因项目过期，资金已退到个人帐户";
		}elseif($order_info['order_status'] ==2){
			$order_status_info="因项目限额已满，资金已退到个人帐户";
		}
		elseif($order_info['order_status'] ==3){
			$order_status_info="支付成功";
		}
		else{
			$order_status_info="支付未完成";
			if($order_info['credit_pay'] > 0 || $order_info['score'] > 0)
			{
				$order_status_info .="(预支付：";
				if($order_info['credit_pay'] > 0)
					$order_status_info .="-余额支付".format_price($order_info['credit_pay']);
				if($order_info['score']>0)
					$order_status_info .="-积分支付".format_price($order_info['score_money']);
			}
		}
		$order_info_return['order_status_info']=$order_status_info;
		$order_info_return['order_status']=$order_info['order_status'];
		
		output ( $root );
	}
	
	
}
?>