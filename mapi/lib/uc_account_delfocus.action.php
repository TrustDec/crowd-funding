<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹关注的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_delfocus {
	public function index() {
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$id = intval ( $GLOBALS ['request'] ['id'] );
		
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0) {
			
			$root ['user_login_status'] = 1;
			
			if ($id > 0) {
				
				$root ['response_code'] = 1;
				
				$is_focus = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id . " and user_id = " . $user_id );
				
				if ($is_focus == 0) {
					
					$data ['deal_id'] = $id;
					$data ['user_id'] = $user_id;
					$now_time = get_gmtime ();
					$data ['create_time'] = $now_time;
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_focus_log", $data, "INSERT", "", "SILENT" );
					
					$focus_count = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id );
					
					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal set focus_count = " . $focus_count . " where id = " . intval ( $id ) );
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_deal_notify", $data, "INSERT", "", "SILENT" );
					$root ['is_focus']=1;
					$root ['info'] = '关注成功';
				} elseif ($is_focus == 1) {
					$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id . " and user_id = " . $user_id );
					
					$focus_count = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id );
					
					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal set focus_count = " . $focus_count . " where id = " . intval ( $id ) );
					$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "user_deal_notify where user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) . " and deal_id = " . $id );
					$root ['is_focus']=0;
					$root ['info'] = '取消成功';
				}
			}
		} else {
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
}
?>