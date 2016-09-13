<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_paid
{
	public function index()
	{
		$root = array ();
		
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$deal_id = intval($GLOBALS ['request']['deal_id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($user['id']));
		if(!$deal_info)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "请选择正确项目";
			output($root);
		}
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$paid_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_pay_log where deal_id = ".$deal_id);
		if($paid_count >0)
		{
			$paid_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_pay_log where deal_id = ".$deal_id." order by create_time desc limit ".$limit);
			foreach($paid_list as $k=>$v)
			{
				$paid_list[$k]['format_create_time']=to_date($v['create_time']);
				$paid_list[$k]['format_money']=format_price($v['money']);
				$paid_list[$k]['pay_type']="管理员发放";
			}
		}
		else
		{
			$paid_list = array();
		}

		$root['paid_list'] = $paid_list;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $paid_count / $page_size )
		);
		$root['deal_id'] = $deal_id;
		output ( $root );
	}
}
?>