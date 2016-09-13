<?php
// +----------------------------------------------------------------------
// | Fanwe 方维 用户添加配送地址
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class del_consignee
{
	public function index(){
		$root = array();
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			
			$id = intval($GLOBALS['request']['id']);//id,有ID值则更新，无ID值，则插入

			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where id = ".$id." and user_id = ".$user_id);
			//$root = array();
			$root['response_code'] = 1;
			$root['info'] = "数据删除成功!";
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>