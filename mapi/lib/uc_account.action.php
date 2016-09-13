<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// 支持的项目
class uc_account
{
	public function index()
	{
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
			$now_time = get_gmtime ();
			$page_size = $GLOBALS ['m_config'] ['page_size'];
			$page = intval ( $GLOBALS ['request'] ['p'] );
			if ($page == 0)
				$page = 1;
			$limit = (($page - 1) * $page_size) . "," . $page_size;
			
			$order_list = $GLOBALS ['db']->getAll ( "select a.*,b.is_delivery,b.repaid_day as item_repaid_day from " . DB_PREFIX . "deal_order as a " . "left join " . DB_PREFIX . "deal_item as b on b.id=a.deal_item_id " . "left join " . DB_PREFIX . "deal as c on c.id=a.deal_id " . "where a.type in(0,2) and a.user_id = " . $user_id . " and c.is_delete=0 and c.is_effect=1 order by a.create_time desc limit " . $limit );
			
			$order_count = $GLOBALS ['db']->getOne ( "select count(a.id) from " . DB_PREFIX . "deal_order as a " . "left join " . DB_PREFIX . "deal_item as b on b.id=a.deal_item_id " . "left join " . DB_PREFIX . "deal as c on c.id=a.deal_id " . "where a.type in(0,2) and a.user_id = " . $user_id . " and c.is_delete=0 and c.is_effect=1 " );
			
			$root ['page'] = array (
					"page" => $page,
					"page_total" => ceil ( $order_count / $page_size ),
					"page_size" => intval ( $page_size ),
					'total' => intval ( $order_count ) 
			);
			foreach ( $order_list as $k => $v )
			{
				$deal_ids [] = $v ['deal_id'];
			}
			// print_r($order_list);exit;
			$deal_list_array = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where  is_effect = 1 and is_delete = 0  and type=0 and id in (" . implode ( ',', $deal_ids ) . ")" );
			$deal_list = array ();
			foreach ( $deal_list_array as $k => $v )
			{
				if ($v ['id'])
				{
					$deal_list [$v ['id']] = $v;
				}
			}
			
			// unset($deal_list_array);
			foreach ( $order_list as $k => $v )
			{
				// $order_list[$k]['deal_info'] = $GLOBALS['db']->getRow("select
				// * from ".DB_PREFIX."deal where id = ".$v['deal_id']." and
				// is_effect = 1 and is_delete = 0");
				$order_list [$k] ['repay_left_time'] = 0;
				
				if ($v ['repay_make_time'] == 0 && $v ['repay_time'] > 0)
				{
					// $item=$GLOBALS['db']->getRow("select * from
					// ".DB_PREFIX."deal_item where id=".$v['deal_item_id']);
					$item_day = intval ( $v ['item_repaid_day'] );
					if ($item_day > 0)
					{
						$left_date = $item_day;
					} else
					{
						$left_date = intval ( app_conf ( "REPAY_MAKE" ) );
					}
					
					$repay_make_date = $v ['repay_time'] + $left_date * 24 * 3600;
					$now_time = get_gmtime ();
					if ($repay_make_date <= $now_time)
					{
						$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set repay_make_time =  " . $now_time . " where id = " . $v ['id'] );
						$order_list [$k] ['repay_make_time'] = $now_time;
					} else
					{
						$order_list [$k] ['repay_left_time'] = $repay_make_date - $now_time;
					}
				}
				
				$order_list [$k] ['notice_sn'] = $GLOBALS ['db']->getOne ( "select notice_sn from " . DB_PREFIX . "payment_notice where order_id = " . $v ['id'] );
				// $image = $GLOBALS ['db']->getOne ( "select image from " .
				// DB_PREFIX . "deal where id = " . $v ['deal_id'] );
				// $order_list [$k] ['image'] = get_abs_img_root (
				// get_spec_image ( $image, 640, 240, 1 ) );
				$deal_info = $deal_list [$v ['deal_id']];
				$deal_info_view ['image'] = get_abs_img_root ( get_spec_image ( $deal_info ['image'], 640, 240, 1 ) );
				$deal_info_view ['end_time'] = to_date ( $deal_info ['end_time'], 'Y-m-d' );
				$deal_info_view ['begin_time'] = to_date ( $deal_info ['begin_time'], 'Y-m-d' );
				$deal_info_view ['create_time'] = to_date ( $deal_info ['create_time'], 'Y-m-d' );
				// $deal_info_view ['content'] = $deal_info ['description'];
				$deal_info_view ['deal_extra_cache'] = null;
				$deal_status = get_deal_status ( $deal_info, 0 );
				$deal_info_view ['deal_status'] = $deal_status ['deal_status'];
				$deal_info_view ['deal_status_expression'] = $deal_status ['deal_status_expression'];
				
				$order_list [$k] ['image'] = $deal_info_view ['image'];
				
				// $order_list[$k]['state']值0表示已用余额支付$order_list[$k]['credit_pay']剩余支付未完成；1表示预热中；2表示已成功；3表示回报已发放；4表示确认收到;5表示未确认收到；6表示等待发放回报；7表示未成功；8表示已退款；9表示等待退款;10表示项目进行中，还有未成功,11支付成功，但无库存,12已支付(过期),13无效的订单
				if ($order_list [$k] ['order_status'] == 0)
				{
					$order_list [$k] ['state'] = 0; // 未支付
					$order_list [$k] ['state_info'] = "余额预支付" + $order_list [$k] ['credit_pay'] + "支付未完成";
				} elseif ($order_list [$k] ['order_status'] == 3)
				{ // 支付成功
					if ($deal_info ['is_success'] == 1)
					{ // 项目成功
						$order_list [$k] ['state'] = 2; // 2表示已成功
						$order_list [$k] ['state_info'] = "已成功";
						if ($order_list [$k] ['repay_time'] > 0)
						{
							$order_list [$k] ['state'] = 3; // 3表示回报已发放
							$order_list [$k] ['state_info'] = "回报已发放";
							if ($order_list [$k] ['repay_make_time'] > 0)
							{
								$order_list [$k] ['state'] = 4; // 4表示确认收到;
								$order_list [$k] ['state_info'] = "确认收到";
							} else
							{
								$order_list [$k] ['state'] = 5; // 5表示未确认收到；
								$order_list [$k] ['state_info'] = "未确认收到";
							}
						} else
						{
							$order_list [$k] ['state'] = 6; // 6表示等待发放回报；
							$order_list [$k] ['state_info'] = "等待发放回报";
						}
					} else
					{ // 项目未成功
						if ($deal_info ['begin_time'] < $now_time && ($deal_info ['end_time'] > $now_time || $deal_info ['end_time'] = 0))
						{
							$order_list [$k] ['state'] = 10; // 表示项目进行中，还有未成功
							$order_list [$k] ['state_info'] = "项目进行中，还有未成功";
						} elseif ($deal_info ['begin_time'] < $now_time && $now_time >= $deal_info ['end_time'])
						{
							$order_list [$k] ['state'] = 7; // 7表示未成功
							if ($order_list [$k] ['is_refund'] == 1)
							{
								$order_list [$k] ['state'] = 8; // 8表示已退款
								$order_list [$k] ['state_info'] = "已退款";
							} else
							{
								$order_list [$k] ['state'] = 9; // 9表示等待退款
								$order_list [$k] ['state_info'] = "等待退款";
							}
						}
					}
				} elseif ($order_list [$k] ['order_status'] == 2)
				{ // 已支付(无库存)
					$order_list [$k] ['state'] = 11; // 已支付(无库存)
					$order_list [$k] ['state_info'] = "已支付(无库存)";
				} elseif ($order_list [$k] ['order_status'] == 1)
				{ // 已支付(过期)
					$order_list [$k] ['state'] = 12; // 已支付(过期)
					$order_list [$k] ['state_info'] = "已支付(过期)";
				} else
				{ // 无效的订单
					$order_list [$k] ['state'] = 13; // 无效的订单
					$order_list [$k] ['state_info'] = "无效的订单";
				}
				
				if (is_tg () && intval ( $deal_info ['ips_bill_no'] ) > 0)
				{
					$order_list [$k] ['is_tg'] = 1; // 第三方托管支付
				} else
				{
					$order_list [$k] ['is_tg'] = 0;
				}
				
				$order_list [$k] = $this->formatOrderListAddContent ( $order_list [$k], $deal_info );
				
				$order_list [$k] ['deal_info'] = $deal_info_view;
				$order_list [$k] ['create_time'] = to_date ( $v ['create_time'], 'Y-m-d' );
				$order_list [$k] ['pay_time'] = to_date ( $v ['pay_time'], 'Y-m-d' );
			}
			$root ['order_list'] = $order_list;
		} else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
	private function formatOrderListAddContent($order, $deal_info)
	{
		if ($order ['order_status'] == 0)
		{
			$order ['content'] = "余额预支付¥" . $order ['credit_pay'] . " 支付未完成";
		} else
		{
			if ($deal_info)
			{
				if ($deal_info ['is_success'] == 1)
				{
					if ($deal_info ['begin_time'] > NOW_TIME)
					{
						$order ['content'] = "预热中";
					}
					if ($deal_info ['end_time'] < NOW_TIME && $deal_info ['end_time'] > 0)
					{
						$order ['content'] = "已成功";
						if ($order ['repay_time'] > 0)
						{
							$order ['content'] = $order ['content'] . " 回报已发放";
							if ($order ['repay_make_time'] > 0)
							{
								$order ['content'] = $order ['content'] . " 确认收到";
							} else
							{
								$order ['content'] = $order ['content'] . " 未确认收到";
							}
						} else
						{
							$order ['content'] = $order ['content'] . " 等待回报发放";
						}
					}
					
					if ($deal_info ['begin_time'] < NOW_TIME && ($deal_info ['end_time'] > NOW_TIME || $deal_info ['end_time'] == 0))
					{
						$order ['content'] = "已成功";
						if ($order ['repay_time'] > 0)
						{
							$order ['content'] = $order ['content'] . " 回报已发放";
							if ($order ['repay_make_time'] > 0)
							{
								$order ['content'] = $order ['content'] . " 确认收到";
							} else
							{
								$order ['content'] = $order ['content'] . "未确认收到";
							}
						} else
						{
							$order ['content'] = $order ['content'] . " 等待发放回报";
						}
					}
				} else
				{
					if ($deal_info ['begin_time'] > NOW_TIME)
					{
						$order ['content'] = "预热中";
					}
					if ($deal_info ['end_time'] < NOW_TIME && $deal_info ['end_time'] > 0)
					{
						$order ['content'] = "未成功";
						if ($order ['is_refund'] == 1)
						{
							$order ['content'] = $order ['content'] . " 已退款";
						} else
						{
							$order ['content'] = $order ['content'] . " 等待退款";
						}
					}
					
					if ($deal_info ['begin_time'] < NOW_TIME && ($deal_info ['end_time'] > NOW_TIME || $deal_info ['end_time'] == 0))
					{
						$order ['content'] = "未结束";
					}
				}
			} else
			{
				if ($order ['is_success'] == 0)
				{
					$order ['content'] = "未成功";
					if ($order ['repay_time'] > 0)
					{
						$order ['content'] = $order ['content'] + " 回报已发放";
						if ($order ['repay_make_time'] > 0)
						{
							$order ['content'] = $order ['content'] + " 确认收到";
						} else
						{
							$order ['content'] = $order ['content'] + " 未确认收到";
						}
					} else
					{
						$order ['content'] = $order ['content'] + " 等待发放回报";
					}
				} else
				{
					$order ['content'] = "已成功";
					if ($order ['is_refund'] == 1)
					{
						$order ['content'] = $order ['content'] + " 已退款";
					} else
					{
						$order ['content'] = $order ['content'] + " 等待退款";
					}
				}
			}
		}
		return $order;
	}
}

?>