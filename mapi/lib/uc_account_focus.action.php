<?php
/**
 * @author 作者
 * @version 创建时间：2015-5-19 类说明 个人中心关注项目列表
 */
class uc_account_focus
{
	
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		//===============================
		$f = intval ( $GLOBALS ['request'] ['f'] );
		if ($f == 0)
			$cond = " 1=1 ";
		if ($f == 1)
			$cond = " d.begin_time < " . NOW_TIME . " and (d.end_time = 0 or d.end_time > " . NOW_TIME . ") ";
		if ($f == 2)
			$cond = " d.end_time <> 0 and d.end_time < " . NOW_TIME . " and d.is_success = 1 "; // 过期成功
		if ($f == 3)
			$cond = " d.end_time <> 0 and d.end_time < " . NOW_TIME . " and d.is_success = 0 "; // 过期失败
		
		$s = intval ( $GLOBALS ['request'] ['s'] );
		if ($s == 3)
			$sort_field = " d.support_amount desc ";
		if ($s == 1)
			$sort_field = " d.support_count desc ";
		if ($s == 2)
			$sort_field = " d.support_amount - d.limit_price desc ";
		if ($s == 0)
			$sort_field = " d.end_time asc ";
		//==================================
		
		if (app_conf ( "INVEST_STATUS" ) == 0)
		{
			$condition = " 1=1 ";
		} elseif (app_conf ( "INVEST_STATUS" ) == 1)
		{
			$condition = " d.type=0 ";
		} elseif (app_conf ( "INVEST_STATUS" ) == 2)
		{
			$condition = " d.type=1 ";
		}
		
		$app_sql = " " . DB_PREFIX . "deal_focus_log as dfl left join " . DB_PREFIX . "deal as d on d.id = dfl.deal_id where $condition and dfl.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) . " and d.is_effect = 1 and d.is_delete = 0 and " . $cond . " ";
		
		$deal_list = $GLOBALS ['db']->getAll ( "select d.*,dfl.id as fid from " . $app_sql . " order by " . $sort_field . " limit " . $limit );
		$deal_count = $GLOBALS ['db']->getOne ( "select count(*) from " . $app_sql );
		
		foreach ( $deal_list as $k => $v )
		{
			if ($v ['type'] == 1)
			{
				$deal_list [$k] = formateType2DealInfo ( $deal_list [$k] );
			} else
			{
				$deal_list [$k] = formateType1DealInfo ( $deal_list [$k] );
			}
		}
		
		$data = responseSuccessInfo ( null, 1, "个人中心关注项目列表" );
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_count / $page_size ) 
		);
		
		$data ['deal_list'] = $deal_list;
		output ( $data );
	}
}
?>