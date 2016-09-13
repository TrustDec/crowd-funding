<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户提现列表
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_refund
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
			$root['response_code'] = 1;
			
			$page_size =  $GLOBALS['m_config']['page_size'];
			$page = intval($GLOBALS['request']['p']);
			if($page==0)$page = 1;		
			$limit = (($page-1)*$page_size).",".$page_size;
	
			$refund_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_refund where user_id = ".$user_id." order by create_time desc limit ".$limit);
			$refund_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_refund where user_id = ".$user_id);
		
			$root['refund_list'] = $refund_list;
			$root['page'] = array("page"=>$page,"page_total"=> ceil($refund_count/$page_size),"page_size"=>intval($page_size),'total'=>intval($refund_count));
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>