<?php
// +----------------------------------------------------------------------
// | Fanwe 方维 用户添加配送地址
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class uc_set_repay_make
{
	public function index(){
		$root = array();
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		$id = intval($GLOBALS['request']['id']);//订单id
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and repay_time>0 and user_id = ".$user_id);
			if(!$order_info)
			{
				$root['response_code'] = 0;
				$root['info'] = '无效的项目支持';
			}else{
				if($order_info['repay_make_time']==0){
					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$order_info['id']." and user_id = ".$user_id);
					$root['response_code'] = 0;
				$root['info'] = '无效的项目支持';
					$root['response_code'] = 1;
					$root['info'] = '确认收货成功';		
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
