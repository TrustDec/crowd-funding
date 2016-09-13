<?php
// +----------------------------------------------------------------------
// | Fanwe 方维 用户添加配送地址
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class uc_account_support
{
	public function index(){
		$root = array();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
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
		$deal_id = intval($GLOBALS ['request']['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".$user_id);
		
		if(!$deal_info)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "请选择正确定的项目";
			output($root);
		}
		
		$page_size =  $GLOBALS['m_config']['page_size'];
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$deal_id." and order_status = 3 and is_refund = 0 ");
		if($support_count >0)
		{	
			$support_list_return=array();
			$support_list = $GLOBALS['db']->getAll("select d.*,di.description as item_description,di.is_delivery from ".DB_PREFIX."deal_order as d left join ".DB_PREFIX."deal_item as di on di.id=d.deal_item_id where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.deal_item_id >0 order by d.create_time desc limit ".$limit);
			foreach($support_list as $k=>$v)
			{
				$support_list_return[$k]['id']=$v['id'];
				$support_list_return[$k]['user_name']=$v['user_name'];
				$support_list_return[$k]['total_price']=$v['total_price'];
				$support_list_return[$k]['delivery_fee']=$v['delivery_fee'];
				$support_list_return[$k]['deal_price']=$v['deal_price'];
				$support_list_return[$k]['share_money']=$v['share_fee'];
				$support_list_return[$k]['format_total_price']=format_price($v['total_price']);
				$support_list_return[$k]['format_delivery_fee']=format_price($v['delivery_fee']);
				$support_list_return[$k]['format_deal_price']=format_price($v['deal_price']);
				$support_list_return[$k]['format_share_money']=format_price($v['share_fee']);
				$support_list_return[$k]['item_description']=$v['item_description'];
				$support_list_return[$k]['support_memo']=$v['support_memo'];
				$support_list_return[$k]['province']=$v['province'];
				$support_list_return[$k]['city']=$v['city'];
				$support_list_return[$k]['address']=$v['address'];
				$support_list_return[$k]['zip']=$v['zip'];
				$support_list_return[$k]['consignee']=$v['consignee'];
				$support_list_return[$k]['mobile']=$v['mobile'];
				$support_list_return[$k]['is_delivery']=$v['is_delivery'];
				$support_list_return[$k]['pay_time']=$v['pay_time'];
				$support_list_return[$k]['format_pay_time']=to_date($v['pay_time']);
				$support_list_return[$k]['to_repay']=0;//不显示发放回报按钮
				if($v['is_success']==1)
				{
					if($v['order_status'] ==3 && $v['repay_time'] >0)
						if($v['repay_make_time'] >0)
							$repay_info="会员确认收到";
						else
							$repay_info="已发放回报";
					else
						{
							$support_list_return[$k]['to_repay']=1;//显示发放回报按钮
							$repay_info="发放回报";
						}
				}else
				{
					$repay_info="没有发放";
				}
				$support_list_return[$k]['repay_info']=$repay_info;
				
				if($v['order_status'] ==1)
				{
					$order_status_info="因项目过期，资金已退到个人帐户";
				}elseif($v['order_status'] ==2){
					$order_status_info="因项目限额已满，资金已退到个人帐户";
				}
				elseif($v['order_status'] ==3){
					$order_status_info="支付成功";
				}
				else{
					$order_status_info="支付未完成";
					if($v['credit_pay'] > 0 || $v['score'] > 0)
					{
						$order_status_info .="(预支付：";
						if($v['credit_pay'] > 0)
							$order_status_info .="-余额支付".format_price($v['credit_pay']);
						if($v['score']>0)
							$order_status_info .="-积分支付".format_price($v['score_money']);
					}
				}
				$order_info_return['order_status_info']=$order_status_info;
				$order_info_return['order_status']=$v['order_status'];
				
			}
		}else{
			$support_list_return=array();
		}
		
		$root['support_list']=$support_list_return;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $support_count / $page_size )
		);
		output($root);		
	}
	
	public function invest(){
		
		$root = array();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
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
		$deal_id = intval($GLOBALS ['request']['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".$user_id);
		if(!$deal_info)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "请选择正确定的项目";
			output($root);
		}
		
		$page_size =  $GLOBALS['m_config']['page_size'];
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$deal_id." and order_status = 3 and is_refund = 0 ");
		if($support_count >0)
		{
			$support_list_return=array();
			$support_list = $GLOBALS['db']->getAll("select d.*,i.stock_value as investment_stock_value,i.type as invest_type from ".DB_PREFIX."deal_order as d "." left join ".DB_PREFIX."investment_list as i on i.id = d.invest_id  where d.deal_id = ".$deal_id." and d.order_status = 3 and d.is_refund = 0 and d.invest_id >0  order by d.create_time desc limit ".$limit);
			foreach($support_list as $k=>$v)
			{
				$support_list_return[$k]['id']=$v['id'];
				$support_list_return[$k]['user_name']=$v['user_name'];
				$support_list_return[$k]['investment_stock_value']=$v['investment_stock_value'];
				$support_list_return[$k]['delivery_fee']=$v['delivery_fee'];
				$support_list_return[$k]['deal_price']=$v['deal_price'];
				$support_list_return[$k]['format_investment_stock_value']=format_price($v['investment_stock_value']);
				$support_list_return[$k]['format_delivery_fee']=format_price($v['delivery_fee']);
				$support_list_return[$k]['format_deal_price']=format_price($v['deal_price']);
				$support_list_return[$k]['pay_time']=$v['pay_time'];
				$support_list_return[$k]['format_pay_time']=to_date($v['pay_time']);
				
				if($v['invest_type'] ==0)
					$invest_type_val='询价';
				elseif($v['invest_type'] ==1)
					$invest_type_val='领投';
				elseif($v['invest_type'] ==2)
					$invest_type_val='跟投';
				elseif($v['invest_type'] ==2)
					$invest_type_val='追加';
						
				$support_list_return[$k]['invest_type_val']=$invest_type_val;
				$support_list_return[$k]['invest_type']=$v['invest_type'];
					
				$support_list_return[$k]['to_repay']=0;//不显示发放回报按钮
				if($v['is_success']==1)
				{
					if($v['order_status'] ==3 && $v['repay_time'] >0)
						if($v['repay_make_time'] >0)
							$repay_info="会员确认收到";
						else
							$repay_info="已发放回报";
					else
						{
							$support_list_return[$k]['to_repay']=1;//显示发放回报按钮
							$repay_info="发放回报";
						}
				}else
				{
					$repay_info="没有发放";
				}
				$support_list_return[$k]['repay_info']=$repay_info;	
				
				if($v['order_status'] ==1)
				{
					$order_status_info="因项目过期，资金已退到个人帐户";
				}elseif($v['order_status'] ==2){
					$order_status_info="因项目限额已满，资金已退到个人帐户";
				}
				elseif($v['order_status'] ==3){
					$order_status_info="支付成功";
				}
				else{
					$order_status_info="支付未完成";
					if($v['credit_pay'] > 0 || $v['score'] > 0)
					{
						$order_status_info .="(预支付：";
						if($v['credit_pay'] > 0)
							$order_status_info .="-余额支付".format_price($v['credit_pay']);
						if($v['score']>0)
							$order_status_info .="-积分支付".format_price($v['score_money']);
					}
				}
				$support_list_return[$k]['order_status_info']=$order_status_info;
				$support_list_return[$k]['order_status']=$v['order_status'];
				
				$support_list_return[$k]['transfer_share']=$deal_info['transfer_share'];//出让股份
				//项目金额
				$support_list_return[$k]['stock_value'] =number_format($deal_info['limit_price']/10000,2);
				//用户所占股份
				$support_list_return[$k]['user_stock']= number_format(($v['total_price']/$deal_info['limit_price'])*$deal_info['transfer_share'],2);	
				$support_list_return[$k]['total_price']=$v['total_price'];
				$support_list_return[$k]['format_total_price']=format_price($v['total_price']);
			
			}
		}else{
			$support_list_return=array();
		}
		
		$root['support_list']=$support_list_return;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $support_count / $page_size )
		);
		
		output($root);		
	}
}
?>
