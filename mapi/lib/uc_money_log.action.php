<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_money_log
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
		
		//	require APP_ROOT_PATH.'app/Lib/uc_func.php';
			
			$root['user_login_status'] = 1;
			$root['response_code'] = 1;
			
			$page_size =  $GLOBALS['m_config']['page_size'];
			$page = intval($_REQUEST['p']);
			if($page==0)$page = 1;		
			$limit = (($page-1)*$page_size).",".$page_size	;
			
			$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".$user_id." and money <> 0 order by log_time desc limit ".$limit);
			foreach($log_list as $k=>$v)
			{
				$log_list[$k]['log_time']=to_date($v['log_time'],'Y-m-d');
				$log_list[$k]['money']=round($v['money'],2);
			}
			$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".$user_id);
			
			$root['page'] = array("page"=>$page,"page_total"=> ceil($log_count/$page_size),"page_size"=>intval($page_size),'total'=>intval($log_count));
			$root['log_list'] = $log_list;
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>
