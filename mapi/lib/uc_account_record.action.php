<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户充值记录
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_record
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
			$page = intval($_REQUEST['p']);
			if($page==0)$page = 1;		
			$limit = (($page-1)*$page_size).",".$page_size	;
			$record_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment_notice where user_id = ".$user_id." and order_id=0 AND deal_id=0 AND deal_item_id=0 AND deal_name='' order by create_time desc limit ".$limit);
			
			if( is_tg() && $user['ips_acct_no'] )
				$is_tg=1;
			else
				$is_tg=0;
				
			foreach($record_list as $k=>$v)
			{
				$record_list[$k]['create_time']=to_date($v['create_time'],'Y-m-d');
				$record_list[$k]['is_tg']=$is_tg;
			}
			$record_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment_notice where user_id = ".$user_id." and order_id=0 AND deal_id=0 AND deal_item_id=0 AND deal_name=''");
			$root['record_list'] = $record_list;
			$root['record_list'] = $record_list;
			$root['page'] = array("page"=>$page,"page_total"=> ceil($record_count/$page_size),"page_size"=>intval($page_size),'total'=>intval($record_count));
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>