<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹关注的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_delorder
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
			$order_id = intval($GLOBALS['request']['id']);
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_status = 0 and user_id = ".$user_id." and id = ".$order_id);
			
			if(!$order_info)
			{
				$root['info']='无效的订单';
			}
			else
			{
				$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where id = ".$order_id." and user_id = ".$user_id." and order_status = 0");
				if($GLOBALS['db']->affected_rows()>0)
				{
					$root['response_code'] = 1;
					$root['info']='删除成功';
				}else
				{
					$root['response_code'] = 0;
					$root['info']='删除失败';
				}
				
			}
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>