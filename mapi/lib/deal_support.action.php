<?php
require APP_ROOT_PATH . 'app/Lib/shop_lip.php';
class deal_support
{
	public function index()
	{
		$root = array ();
		$root ['response_code'] = 1;
		$id = intval ( $GLOBALS ['request'] ['id'] );
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码 // 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where id = " . $id . " and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = " . $user_id . "))" );
		
		$deal_info = cache_deal_extra ( $deal_info );
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$page = intval ( $GLOBALS ['request'] ['p'] );
		if ($page == 0)
			$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$support_list = $GLOBALS ['db']->getAll ( "select user_id,price from " . DB_PREFIX . "deal_support_log where deal_id = " . $id . " order by create_time desc limit " . $limit );
		$support_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal_support_log where deal_id = " . $id );
		
		$user_list = array ();
		$user_ids = array ();
		foreach ( $support_list as $k => $v )
		{
			if ($v ['user_id'])
			{
				$user_ids [] = $v ['user_id'];
			}
		}
		$user_ids = array_filter ( $user_ids );
		if ($user_ids)
		{
			$user_id_str = implode ( ',', array_filter ( $user_ids ) );
			$user_list_array = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user where id in (" . $user_id_str . ") " );
			foreach ( $user_list_array as $k => $v )
			{
				foreach ( $support_list as $k_support => $v_support )
				{
					if ($v ['id'] == $v_support ['user_id'])
					{
						$support_list [$k_support] ['user_info'] = $v;
						$support_list [$k_support] ['user_info'] ["image"] = get_user_avatar_root ( $v ["id"], "middle" );
						$support_list [$k_support] ['user_info'] ["url"] = url_root ( "home", array (
								"id" => $v ["user_id"] 
						) );
					}
				}
			}
		}
		$root ['support_list'] = $support_list;
		// -----------------------------------
		$virtual_person = $GLOBALS ['db']->getOne ( "select sum(virtual_person) from " . DB_PREFIX . "deal_item where deal_id=" . $id );
		// 获得该项目下的子项目的所有信息
		
		// $deal_item_list=$GLOBALS['db']->getAll("select * from
		// ".DB_PREFIX."deal_item where deal_id=".$id);
		$deal_item_list = $deal_info ['deal_item_list'];
		foreach ( $deal_item_list as $k => $v )
		{
			// 统计每个子项目真实+虚拟（人）
			$deal_item_list [$k] ['virtual_person'] = $v ['virtual_person'] + $v ['support_count'];
			// 统计所有真实+虚拟（钱）
			$deal_item_list [$k] ['virtual_price'] = $v ['price'] * $deal_item_list [$k] ['virtual_person'];
			// 支付该项花费的金额
			$deal_item_list [$k] ['total_price'] = $v ['price'] + $v ['delivery_fee'];
			$deal_item_list [$k] ['delivery_fee_format'] = number_price_format ( $v ['delivery_fee'] );
			$deal_item_list [$k] ['content'] = strip_tags($v ['description']);
			
			foreach ( $deal_item_list [$k] ['images'] as $kk => $vv )
			{
				
				// $deal_item_list [$k] ['images'] [$kk] ['image'] =
				// get_abs_img_root ( get_spec_image ( $vv ['image'], 640, 240,
				// 1 ) );
				$deal_item_list [$k] ['images'] [$kk] ['image'] = get_abs_img_root ( $vv ['image'] );
			}
		}
		if (! $deal_item_list)
		{
			$deal_item_list = Null;
		}
		
		$root ['deal_item_list'] = $deal_item_list;
		$root ['person'] = $virtual_person + $deal_info ['support_count'];
		// -----------------------------------
		if ($deal_info ['user_id'] > 0)
		{
			$deal_user_info = $GLOBALS ['db']->getRow ( "select id,user_name,province,city,intro,login_time from " . DB_PREFIX . "user where id = " . $deal_info ['user_id'] . " and is_effect = 1" );
			if ($deal_user_info == false)
			{
				$deal_user_info = NULL;
			}
			$root ['deal_user_info'] = $deal_user_info;
		}
		// $root['support_list'] = $support_list;
		
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $support_count / $page_size ),
				"page_size" => intval ( $page_size ),
				'total' => intval ( $support_count ) 
		);
		output ( $root );
	}
}

?>