<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 投资的项目
 * 投资的项目
 */
class mine_investor_status
{
	public function index()
	{
		// $type 0询价 1领投 2跟投
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$type = intval ( ($GLOBALS ['request'] ['type']) );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		$page = $page == 0 ? 1 : $page;
		
		$user = user_check ( $email, $password );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$investor_list = $GLOBALS ['db']->getAll ( "select invest.*,d.end_time as deal_end_time,d.pay_end_time as deal_pay_end_time,d.begin_time as deal_begin_time,d.name as deal_name ,d.image as deal_image,d.id as deal_id,d.transfer_share as transfer_share,d.limit_price as limit_price,d.ips_bill_no,d.is_success from  " . DB_PREFIX . "investment_list as invest " .
				"left join " . DB_PREFIX . "deal as d on d.id=invest.deal_id " .
				"where  invest.type=$type and invest.user_id=$user_id and d.is_delete=0 and d.is_effect order by invest.id desc limit $limit " );
		//$investor_list_num = $GLOBALS ['db']->getOne ( "select count(*) from  " . DB_PREFIX . "investment_list where  type=$type and user_id=$user_id  " );
		$investor_list_num = $GLOBALS ['db']->getOne ( "select count(invest.id) from  " . DB_PREFIX . "investment_list as invest " .
				"left join " . DB_PREFIX . "deal as d on d.id=invest.deal_id " .
				"where  invest.type=$type and invest.user_id=$user_id and d.is_delete=0 and d.is_effect" );
		$now_time = NOW_TIME;
		if ($type == 0 || $type == 2 || $type == 1)
		{
			foreach ( $investor_list as $k => $v )
			{
				if ($type == 1)
				{
					if ($now_time > $v ['deal_end_time'])
					{
						$investor_list [$k] ['status'] = 2;
						$GLOBALS ['db']->query ( "UPDATE  " . DB_PREFIX . "investment_list set status=2 where id= " . $v ['id'] );
					}
				}
				if ($v ['investor_money_status'] == 0 && $now_time > $v ['deal_end_time'])
				{
					$investor_list [$k] ['investor_money_status'] = 2;
					$GLOBALS ['db']->query ( "UPDATE  " . DB_PREFIX . "investment_list set investor_money_status=2 where id= " . $v ['id'] );
				} elseif ($v ['investor_money_status'] == 1 && $now_time > $v ['deal_pay_end_time'])
				{
					$investor_list [$k] ['investor_money_status'] = 4;
					deal_invest_break ( $v ['id'] );
				}
				
				$investor_list [$k] ['create_time'] = to_date ( $v ['create_time'] );
				$investor_list [$k] ['deal_begin_time'] = to_date ( $v ['deal_begin_time'] );
				$investor_list [$k] ['deal_end_time'] = to_date ( $v ['deal_end_time'] );
				$investor_list [$k] ['deal_pay_end_time'] = to_date ( $v ['deal_pay_end_time'] );
				$investor_list [$k] ['cates'] = formateCates ( unserialize ( $investor_list [$k] ['cates'] ) );
				$investor_list [$k] ['deal_image'] = get_abs_img_root ( $v ['deal_image'] );
				
				// 判断投资状态
				$is_view_button = 0;
				if ($v ['type'] == 0 || $v ['type'] == 2 || ($v ['type'] == 1 && $investor_list [$k] ['status'] == 1))
				{
					if ($investor_list [$k] ['investor_money_status'] == 0)
					{
						if ($now_time > $v ['deal_begin_time'] && $now_time < $v ['deal_end_time'])
						{
							if ($v ['type'] == 1)
								$investor_money_status_info = '投资审核中';
							else
								$investor_money_status_info = '审核中';
						} else
						{
							if ($v ['type'] == 1)
								$investor_money_status_info = '投资未通过';
							else
								$investor_money_status_info = '审核未通过';
						}
					} elseif ($investor_list [$k] ['investor_money_status'] == 1)
					{
						if ($now_time > $v ['deal_begin_time'] && $now_time < $v ['deal_pay_end_time'])
						{
							$investor_money_status_info = '审核通过,开始支付';
							$is_view_button = 1; // 显示继续支付按扭
						} else
							$investor_money_status_info = '审核通过,支付时间已过期';
					} elseif ($investor_list [$k] ['investor_money_status'] == 2)
						$investor_money_status_info = '审核未通过';
					elseif ($investor_list [$k] ['investor_money_status'] == 3)
					{
						$investor_money_status_info = '支付完成';
						$is_view_button = 2; // 显示查看订单按扭
					} elseif ($investor_list [$k] ['investor_money_status'] == 4)
						$investor_money_status_info = '您逾期未付款，已违约';
				} elseif ($v ['type'] == 1 && $v ['status'] == 0)
				{
					$investor_money_status_info = '领投人审核中';
					$is_view_button = 3; // 显示编辑领头申请资料， 这个只有领头显示
				} elseif ($v ['type'] == 1 && $investor_list [$k] ['status'] == 2)
				{
					$investor_money_status_info = '投资人审核未通过';
				}
				$investor_list [$k] ['investor_money_status_info'] = $investor_money_status_info;
				$investor_list [$k] ['is_view_button'] = $is_view_button; // 0:不显示按扭，1：显示支付的按钮，2：显示查看订单按扭
				                                                        // end判断投资状态
				
				if ($v ['order_id'] > 0 && ($investor_list [$k] ['investor_money_status'] == 1 || $investor_list [$k] ['investor_money_status'] == 3))
				{
					$order_array = array ();
					$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id=" . intval ( $v ['order_id'] ) . " and type=1" );
					$order_array ['id'] = $order_info ['id'];
					$order_array ['deal_name'] = $order_info ['deal_name'];
					// 出让股份
					$order_array ['transfer_share'] = $v ['transfer_share'];
					// 用户所占股份
					$order_array ['user_stock'] = round ( ($order_info ['total_price'] / $v ['limit_price']) * $v ['transfer_share'], 2 );
					// 项目金额
					$order_array ['stock_value'] = number_format ( $v ['limit_price'], 2 );
					// 应付金额
					$order_array ['total_price'] = $order_info ['total_price'];
					$order_array ['format_total_price'] = number_format ( $order_info ['total_price'], 2 );
					$order_array ['order_status'] = $order_info ['order_status'];
					if ($order_info ['order_status'] == 3)
					{
						$order_array ['order_status_info'] = "支付成功";
						if ($v ['is_success'] == 1)
						{
							if ($order_info ['repay_time'] == 0)
								$order_array ['repay_status_info'] = "项目成功，回报未发放";
							else
								$order_array ['repay_status_info'] = "回报已发放" . $order_info ['repay_memo'];
						} else
						{
							if ($order_info ['is_refund'] == 1)
								$order_array ['repay_status_info'] = "项目失败，金额已退回会员帐户";
							else
								$order_array ['repay_status_info'] = "项目未成功";
						}
					} elseif ($order_info ['order_status'] == 2)
						$order_array ['order_status_info'] = "因项目限额已满，资金已退到个人帐户";
					elseif ($order_info ['order_status'] == 1)
						$order_array ['order_status_info'] = "因项目过期，资金已退到个人帐户";
					elseif ($order_info ['order_status'] == 0)
						$order_array ['order_status_info'] = "支付未完成";
					else
						$order_array ['order_status_info'] = "订单失效";
					$order_array ['pay_time'] = to_date ( $order_info ['pay_time'] );
					
					$order_array ['credit_pay'] = $order_info ['credit_pay'];
					$order_array ['format_credit_pay'] = format_price ( $order_info ['credit_pay'] );
					$order_array ['score'] = $order_info ['score'];
					$order_array ['score_money'] = $order_info ['score_money'];
					$order_array ['format_score_money'] = format_price ( $order_info ['score_money'] );
					$order_array ['online_pay'] = $order_info ['online_pay'];
					$order_array ['format_online_pay'] = format_price ( $order_info ['online_pay'] );
					
					if (is_tg () && intval ( $v ['ips_bill_no'] ) > 0)
					{
						$order_array ['is_tg'] = 1; // 第三方托管支付
					} else
					{
						$order_array ['is_tg'] = 0;
					}
				}
				
				$investor_list [$k] ['order_info'] = $order_array;
			}
		}
		
		$data = responseSuccessInfo ( "", 0, "个人中心投资的项目" );
		$date ['investor_list'] = $investor_list;
		$date ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $investor_list_num / $page_size ),
				"page_size" => intval ( $page_size ) 
		);
		output ( $date );
	}
}

?>