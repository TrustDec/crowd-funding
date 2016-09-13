<?php
require APP_ROOT_PATH . 'app/Lib/shop_lip.php';
class deal_update
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
		init_deal_page ( $deal_info );
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$page = intval ( $GLOBALS ['request'] ['p'] );
		if ($page == 0)
			$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$log_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_log where deal_id = " . $deal_info ['id'] . " order by create_time desc limit " . $limit );
		$log_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal_log where deal_id = " . $deal_info ['id'] );
		
		$root ['url'] = get_domain () . str_replace ( "/mapi", "", url_wap ( "deal#update", array (
				"id" => $id 
		) ) );
		$last_time_key = "";
		foreach ( $log_list as $k => $v )
		{
			$log_list [$k] ['image'] = get_user_avatar_root ( $v ["user_id"], "middle" ); // 用户头像
			                                                                              
			// $log_list[$k]['image'] =
			                                                                              // get_abs_img_root(get_spec_image($v['image'],640,240,1));
			$log_list [$k] ['pass_time'] = pass_date ( $v ['create_time'] );
			$online_time = online_date ( $v ['create_time'], $deal_info ['begin_time'] );
			$log_list [$k] ['online_time'] = $online_time ['info'];
			$log_list [$k] ['comment_data_cache'] = null;
			$log_list [$k] ['deal_info_cache'] = null;
			if ($online_time ['key'] != $last_time_key)
			{
				$last_time_key = $log_list [$k] ['online_time_key'] = $online_time ['key'];
			}
			
			$log_list [$k] = cache_log_comment ( $log_list [$k] );
		}
		$root ['log_list'] = $log_list;
		// require APP_ROOT_PATH.'app/Lib/page.php';
		// $page = new Page($log_count,$page_size); //初始化分页对象
		// $p = $page->show();
		// $root['pages'] = $p;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $log_count / $page_size ),
				"page_size" => intval ( $page_size ),
				'total' => intval ( $log_count ) 
		);
		output ( $root );
	}
}

?>