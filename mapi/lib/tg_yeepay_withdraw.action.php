<?php
/**
 * 第三方提现记录
 * */
class tg_yeepay_withdraw{
	public function index()
	{		
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			
		}
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$yeepay_recharge_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."yeepay_withdraw where platformUserNo = ".$user_id."");
		if( $yeepay_recharge_count >0 )
		{
			$yeepay_withdraw_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."yeepay_withdraw where platformUserNo = ".$user_id." order by create_time desc,id desc limit ".$limit);
			$return_list=array();
			foreach($yeepay_withdraw_list as $k=>$v)
			{
				$return_list[$k]['id'] = $v['id'];
				$return_list[$k]['title'] = '第三方托管';
				$return_list[$k]['requestNo'] = $v['requestNo'];
				$return_list[$k]['create_time'] = to_date($v['create_time']);
				$return_list[$k]['amount'] = $v['amount'];
				$return_list[$k]['format_amount'] = format_price($v['amount']);
				$return_list[$k]['fee'] = $v['fee'];
				$return_list[$k]['format_fee'] = format_price($v['fee']);
				if($v['code'] =='1')
				{
					$return_list[$k]['code'] = intval($v['code']);
					$return_list[$k]['code_info'] = '提现成功';
				}
				else
				{
					$return_list[$k]['code'] = 0;
					$return_list[$k]['code_info'] = '提现失败';
				}
			}
		}else{
			
			$return_list=array();
		}
 		
 		$root['refund_list']=$return_list;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $yeepay_recharge_count / $page_size ) 
		);
		
		$root ['response_code'] = 1;
		output($root);
	}
}
?>