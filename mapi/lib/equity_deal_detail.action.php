<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 股权项目详情页面接口
 */
require '../system/utils/weixin.php';
class equity_deal_detail
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['id'] );
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		
		if (! dealIdIsExist ( $id, 1 ))
		{
			$result = responseErrorInfo ( "deal_id参数错误" );
			output ( $result );
		}
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		// get_mortgate (); // 不知道为什么要冻结
		
		$deal_info = $this->getDealInfo ( $id, $user_id );
		$leader_info = getLeaderInfo ( $id );
		
		$deal_new_info = $this->getNewDeal_info ( $deal_info );
		// $stock_info = $this->getStockAndUnStock ( $deal_info );
		// $history_info = $this->getHistoryInfo ( $deal_info );
		// $plan_info = $this->getPlanInfo ( $deal_info );
		
		$result = responseSuccessInfo ( "", 0, "项目详情" );
		
		$result ["deal_info"] = $deal_new_info;
		$result ['leader_info'] = $leader_info;
		// $result ['stock_info'] = $stock_info;
		
		// $result ['history_info'] = $history_info;
		// $result ['plan_info'] = $plan_info;
		
		if ($user_id > 0)
		{
			$is_focus = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id . " and user_id = " . $user_id );
			$result ['is_focus'] = $is_focus;
		} else
		{
			$root ['is_focus'] = 0;
		}
		$biref_url = get_domain () . url_mapi_html ( "deal#biref", array (
				"id" => $id,
				"user_id" => $user_id 
		) );
		$result ['biref_url'] = $biref_url;
		// $result['content_html'] =file_get_contents($biref_url);
		$result ['business_url'] = get_domain () . url_mapi_html ( "deal#business", array (
				"id" => $id,
				"user_id" => $user_id 
		) );
		$result ['teams_url'] = get_domain () . url_mapi_html ( "deal#teams", array (
				"id" => $id,
				"user_id" => $user_id 
		) );
		$result ['history_url'] = get_domain () . url_mapi_html ( "deal#history", array (
				"id" => $id,
				"user_id" => $user_id 
		) );
		$result ['plans_url'] = get_domain () . url_mapi_html ( "deal#plans", array (
				"id" => $id,
				"user_id" => $user_id 
		) );
		
		$url = replace_mapi ( url_wap ( "deal#show", array (
				"id" => $id 
		) ) );
		$result ['share_url'] = $url;
		
		if ($GLOBALS ['m_config'] ['wx_appid'] != '' && $GLOBALS ['m_config'] ['wx_secrit'] != '')
		{
			$weixin_1 = new weixin ( $GLOBALS ['m_config'] ['wx_appid'], $GLOBALS ['m_config'] ['wx_secrit'], $url );
			$wx_url = $weixin_1->scope_get_code ();
			$result ['wx_share_url'] = $wx_url;
		}
		
		// 判断用户是否有权限
		// 0 表示未登陆 1表示正常 2表示等级不够 3表示没有认证手机 4表示没有身份认证 5表示身份认证审核中 6表示身份认证审核失败
		$access = mapi_get_level_access ( $user, $deal_info );
		$result ['access'] = $access ['access'];
		$result ['access_info'] = $access ['access_info'];
		output ( $result );
	}
	private function getDealInfo($deal_id, $user_id)
	{
		$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where id = " . $deal_id . " and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = " . $user_id . "))" );
		$deal_info ['deal_type'] = $GLOBALS ['db']->getOne ( "select name from " . DB_PREFIX . "deal_cate where id=" . $deal_info ['cate_id'] );
		return $deal_info;
	}
	private function getNewDeal_info($deal_info)
	{
		$deal_new_info = array ();
		
		$deal_new_info ['business_descripe'] = $deal_info ['business_descripe'];
		$deal_new_info ['transfer_share'] = $deal_info ['transfer_share'];
		$deal_new_info ['business_name'] = $deal_info ['business_name'];
		$deal_new_info ['business_address'] = $deal_info ['business_address'];
		$deal_new_info ['business_create_time'] = to_date ( $deal_info ['business_create_time'], 'Y-m-d' );
		$deal_new_info ['business_employee_num'] = $deal_info ['business_employee_num'];
		$deal_new_info ['has_another_project'] = $deal_info ['has_another_project'];
		$deal_new_info ['province'] = $deal_info ['province'];
		$deal_new_info ['city'] = $deal_info ['city'];
		$deal_new_info ['tags'] = $deal_info ['tags'];
		// 项目阶段 0表示未启动 1表示在开发中 2产品已上市或上线，3已经有收入，4已经盈利
		$deal_new_info ['project_step'] = $deal_info ['project_step'];
		
		$deal_new_info ['id'] = $deal_info ['id'];
		$deal_new_info ['name'] = $deal_info ['name'];
		$deal_new_info ['user_name'] = $deal_info ['user_name'];
		$deal_new_info ['user_id'] = $deal_info ['user_id'];
		$deal_new_info ['invote_money'] = floatval ( $deal_info ['invote_money'] );
		$deal_new_info ['invote_money_formate'] = ($deal_info ['invote_money'] / 10000) . '万';
		$deal_new_info ['gen_num'] = $deal_info ['gen_num'];
		$deal_new_info ['xun_num'] = $deal_info ['xun_num'];
		$deal_new_info ['limit_price'] = $deal_info ['limit_price'];
		$deal_new_info ['limit_price_format'] = ($deal_info ['limit_price'] / 10000) . '万';
		$deal_new_info ['comment_count'] = $deal_info ['comment_count'];
		$deal_new_info ['end_time'] = to_date ( $deal_info ['end_time'] );
		$deal_new_info ['business_pay_type'] = $deal_info ['business_pay_type'];
		$deal_new_info ['source_vedio'] = $deal_info ['source_vedio'];
		$deal_new_info ['vedio'] = $deal_info ['vedio'];
		$deal_new_info ['image'] = get_abs_img_root ( $deal_info ['image'] );
		$deal_new_info ['type'] = $deal_info ['type'];
		
		if ($deal_info ['end_time'] > NOW_TIME)
		{
			$deal_new_info ['remain_days'] = ceil ( ($deal_info ['end_time'] - NOW_TIME) / (24 * 3600) );
		} else
		{
			$deal_new_info ['remain_days'] = 0;
		}
		$deal_new_info ['person'] = $deal_info ['invote_num'];
		$deal_new_info ['percent'] = round ( ($deal_info ['invote_money'] / $deal_info ['limit_price']) * 100 );
		
		$cate = $GLOBALS ['db']->getOne ( "select name from " . DB_PREFIX . "deal_cate where id =" . $deal_info ['cate_id'] );
		$deal_new_info ['cate'] = $cate;
		
		// equity_status 0预热中 1已成功 2筹资失败 3 长期项目 4 融资中
		if ($deal_info ['begin_time'] > NOW_TIME)
		{
			$equity_status = 0; // 预热中
			$equity_status_expression = '预热中';
		} elseif ($deal_info ['end_time'] < NOW_TIME && $deal_info ['end_time'] > 0)
		{
			if ($deal_new_info ['percent'] >= 100)
			{
				$equity_status = 1; // 已成功
				$equity_status_expression = '已成功';
			} else
			{
				$equity_status = 2; // 筹资失败
				$equity_status_expression = '筹资失败';
			}
		} else
		{
			if ($deal_new_info ['percent'] >= 100)
			{
				$equity_status = 1; // 已成功
				$equity_status_expression = '已成功';
			} elseif ($deal_info ['end_time'] == 0)
			{
				$equity_status = 3; // 长期项目
				$equity_status_expression = '长期项目';
			} else
			{
				$equity_status_expression = '融资中';
				$equity_status = 4; // 融资中
			}
		}
		$deal_new_info ['equity_status_expression'] = $equity_status_expression;
		$deal_new_info ['equity_status'] = $equity_status;
		
		$deal_new_info ['ips_bill_no'] = $deal_info ['ips_bill_no'];
		$is_tg = is_tg ();
		if ($deal_info ['ips_bill_no'] && $is_tg)
		{
			$deal_new_info ['ips_bill_no_pay'] = 1; // 用托管支付
		} else
		{
			$deal_new_info ['ips_bill_no_pay'] = 0; // 网站支付
		}
		
		return $deal_new_info;
	}
	
	// 编辑及管理团队
	private function getStockAndUnStock($deal_info)
	{
		$stockInfo = array ();
		
		$stock_list = unserialize ( $deal_info ['stock'] );
		$stock_num = count ( $stock_list );
		if ($stock_num == 0)
		{
			$stock_num ++;
		}
		
		$unstock_list = unserialize ( $deal_info ['unstock'] );
		if (! $unstock_list || ! is_array ( $unstock_list ))
		{
			$unstock_num = 1;
			$is_unstock = 0;
		} else
		{
			$unstock_num = count ( $unstock_list );
			if ($unstock_num == 0)
			{
				$is_unstock = 0;
				$unstock_num ++;
			} else
			{
				$is_unstock = 1;
			}
		}
		
		$stockInfo ['stock_list'] = $stock_list;
		$stockInfo ['stock_num'] = $stock_num;
		$stockInfo ['is_unstock'] = $is_unstock;
		$stockInfo ['unstock_num '] = $unstock_num;
		$stockInfo ['unstock_list'] = $unstock_list;
		
		return $stockInfo;
	}
	// 项目历史执行资料
	private function getHistoryInfo($deal_info)
	{
		$historyInfo = array ();
		
		$history_list = unserialize ( $deal_info ['history'] );
		
		$history_num = count ( $history_list );
		
		$total_history_income = 0;
		$total_history_out = 0;
		$total_history = 0;
		foreach ( $history_list as $key => $v )
		{
			$total_history_income += intval ( $v ["info"] ["item_income"] );
			$total_history_out += intval ( $v ["info"] ["item_out"] );
			$total_history = $total_history_income - $total_history_out;
		}
		
		$historyInfo ['history_num'] = $history_num;
		$historyInfo ['history_list'] = $history_list;
		$historyInfo ['total_history_income'] = $total_history_income;
		$historyInfo ['total_history_out'] = $total_history_out;
		$historyInfo ['total_history'] = $total_history;
		
		return $historyInfo;
	}
	
	// 未来三年内计划
	private function getPlanInfo($deal_info)
	{
		$plan_info = array ();
		$plan_list = unserialize ( $deal_info ['plan'] );
		$plan_num = count ( $plan_list );
		
		$total_plan_income = 0;
		$total_plan_out = 0;
		$total_plan = 0;
		foreach ( $plan_list as $key => $v )
		{
			$total_plan_income += intval ( $v ["info"] ["item_income"] );
			$total_plan_out += intval ( $v ["info"] ["item_out"] );
			$total_plan = $total_plan_income - $total_plan_out;
		}
		
		$plan_info ['plan_num'] = $plan_num;
		$plan_info ['plan_list'] = $plan_list;
		$plan_info ['total_plan_income'] = $total_plan_income;
		$plan_info ['total_plan_out'] = $total_plan_out;
		$plan_info ['total_plan'] = $total_plan;
		return $plan_info;
	}
}

?>