<?php
/**
 * @author 作者
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 股筹项目详情页的全部投资人
 */
class project_follow
{
	public function index()
	{
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		$page = $page == 0 ? 1 : $page;
		
		// 验证参数
		$deal_id_is_exist = dealIdIsExist ( $deal_id, 1 );
		if ($deal_id_is_exist != 1)
		{
			$data = responseErrorInfo ( "deal_id参数错误" );
			output ( $data );
		}
		
		// get_mortgate ();
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where id = " . $deal_id . " and is_delete = 0 and is_effect = 1" );
		$deal_info ['deal_type'] = $GLOBALS ['db']->getOne ( "select name from " . DB_PREFIX . "deal_cate where id=" . $deal_info ['cate_id'] );
		
		$deal_info = $this->formateDeal_info ( $deal_info );
		
		// 跟投信息(所有)
		$enquiry_info_list = $GLOBALS ['db']->getAll ( "select i.*,u.user_name,u.is_investor,u.user_level from " . DB_PREFIX . "investment_list i LEFT JOIN " . DB_PREFIX . "user as u on u.id=i.user_id where i.deal_id=" . $deal_id . " and i.type=2    ORDER BY i.create_time DESC limit $limit" );
		$enquiry_info_list = $this->formateEnquiry_info_list ( $enquiry_info_list );
		
		// 跟投信息(统计)
		$enquiry_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "investment_list i where i.deal_id=" . $deal_id . " and i.type=2" );
		
		// 领投信息
		$leader_info = getLeaderInfo ( $deal_id );
		
		$result = responseSuccessInfo ( "", 0, "股筹项目详情页的全部投资人 " );
		
		$result ['leader_info'] = $leader_info;
		$result ['deal_info'] = $deal_info;
		$result ['enquiry_info_list'] = $enquiry_info_list;
		$result ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $enquiry_count / $page_size ),
				"page_size" => intval ( $page_size ) 
		);
		
		output ( $result );
	}
	private function formateEnquiry_info_list($enquiry_info_list)
	{
		$user_level = load_auto_cache ( "user_level" );
		foreach ( $enquiry_info_list as $k => $v )
		{
			$enquiry_info_list [$k] ['money'] = number_format ( $v ['money'], 2 );
			$enquiry_info_list [$k] ['image'] = get_user_avatar_root ( $enquiry_info_list [$k] ["user_id"], "middle" );
			$enquiry_info_list [$k] ['create_time'] = to_date ( $enquiry_info_list [$k] ['create_time'] );
			$enquiry_info_list [$k] ['user_level_icon'] = get_mapi_user_level_icon ( $user_level, $v ["user_level"] ); // 等级图片
		}
		return $enquiry_info_list;
	}
	private function formateDeal_info($deal_info)
	{
		$deal_new_info = array ();
		$deal_new_info ['name'] = $deal_info ['name'];
		$deal_new_info ['user_name'] = $deal_info ['user_name'];
		$deal_new_info ['province'] = $deal_info ['province'];
		$deal_new_info ['city'] = $deal_info ['city'];
		$deal_new_info ['deal_type'] = $deal_info ['deal_type'];
		return $deal_new_info;
	}
}

?>