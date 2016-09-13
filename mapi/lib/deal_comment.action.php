<?php
require APP_ROOT_PATH . 'app/Lib/shop_lip.php';
class deal_comment {
	public function index() {
		$root = array ();
		$root ['response_code'] = 1;
		$id = intval (  $GLOBALS ['request'] ['id'] );
	
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码 // 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where id = " . $id . " and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = " . $user_id . "))" );
		
		$deal_info = cache_deal_extra ( $deal_info );
		init_deal_page ( $deal_info );
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		;
		$page = intval (  $GLOBALS ['request'] ['p'] );
		if ($page == 0)
			$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$comment_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_comment where deal_id = " . $id . " and log_id = 0 and status=1 order by create_time desc limit " . $limit );
		// $comment_list = $GLOBALS['db']->getAll("select * from
		// ".DB_PREFIX."deal_comment where deal_id = ".$id." and log_id = 0
		// order by create_time desc ");
		$comment_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal_comment where deal_id = " . $id . " and status=1 and log_id = 0" );
		foreach ( $comment_list as $k => $v ) {
			$comment_list [$k] ['create_time'] = to_date ( $v ['create_time'], 'Y-m-d' );
			$comment_list [$k] ["image"] = get_user_avatar_root ( $v ["user_id"], "middle" );
			$comment_list [$k] ["url"] = url_root ( "home", array (
					"id" => $v ["user_id"] 
			) );
		}
		
		$root ['comment_list'] = $comment_list;
		$root ['comment_count'] = $comment_count;
		// require APP_ROOT_PATH.'app/Lib/page.php';
		// $page = new Page($comment_count,$page_size); //初始化分页对象
		// $p = $page->show();
		// $root['pages'] = $p;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $comment_count / $page_size ),
				"page_size" => intval ( $page_size ),
				'total' => intval ( $comment_count ) 
		);
		
		output ( $root );
	}
}

?>