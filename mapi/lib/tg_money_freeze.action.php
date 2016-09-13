<?php
class tg_money_freeze{
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
		
		$money_freeze_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."money_freeze where platformUserNo = ".$user_id." and amount <>0 ");
		if( $money_freeze_count >0 )
		{
			$money_freeze_list = $GLOBALS['db']->getAll("select mf.*,d.name as deal_name from ".DB_PREFIX."money_freeze as mf left join ".DB_PREFIX."deal as d  on d.id = mf.deal_id  where mf.platformUserNo = ".$user_id."  and amount <>0  order by mf.create_time desc,mf.id desc limit ".$limit);
			$return_list=array();
			foreach($money_freeze_list as $k=>$v)
			{
				$return_list[$k]['id'] = $v['id'];
				$return_list[$k]['deal_name'] = $v['deal_name'];
				$return_list[$k]['deal_id'] = $v['deal_id'];
				$return_list[$k]['requestNo'] = $v['requestNo'];
				$return_list[$k]['create_time'] = to_date($v['create_time']);
				$return_list[$k]['amount'] = $v['amount'];
				$return_list[$k]['format_amount'] = format_price($v['amount']);
				$return_list[$k]['pay_type'] = $v['pay_type'];
				if($v['pay_type'] == 1)
				{
					$return_list[$k]['pay_type_info'] = '余额支付';
				}
				else
				{
					$return_list[$k]['pay_type_info'] = '第三方托管支付';
				}
				$return_list[$k]['status'] = $v['status'];
				if($v['status'] ==1)
					$return_list[$k]['status_info'] = '冻结';
				elseif($v['status'] ==2)
					$return_list[$k]['status_info'] = '解冻';
				elseif($v['status'] ==3)
					$return_list[$k]['status_info'] = '申请解冻';
				else
					$return_list[$k]['status_info'] = '';
			}
		}else{
			
			$return_list=array();
		}
 		
 		$root['refund_list']=$return_list;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $money_freeze_count / $page_size ) 
		);
		
		$root ['response_code'] = 1;
		$root ['info'] = '获取列表成功';
		output($root);
	}
	
	//申请解冻
	public function set_money_unfreeze(){
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
		
		$money_unfreeze_id= intval($GLOBALS ['request'] ['id']);//id
		
		$money_freeze=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."money_freeze where id = ".$money_unfreeze_id." and platformUserNo =".$user_id." ");
		
		if($money_freeze['status'] ==3)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "已在申请解冻中";
		}elseif($money_freeze['status'] ==2)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "已解冻";
		}elseif($money_freeze['status'] ==1)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."money_freeze set status = 3,create_time=".get_gmtime()." where id = ".$money_unfreeze_id." and platformUserNo =".$user_id );
			$root ['response_code'] =1;
			$root ['info'] = "申请解冻成功";
		}else{
			$root ['response_code'] = 0;
			$root ['info'] = "未找到符合条件的数据";
		}
		output($root);
	}
	public function set_money_freeze(){
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
	
		$money_freeze_id= intval($GLOBALS ['request'] ['id']);//id
		$money_freeze=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."money_freeze where id = ".$money_freeze_id." and platformUserNo =".$user_id." ");
		if($money_freeze['status'] ==3)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."money_freeze set status = 1,create_time=".get_gmtime()." where id = ".$money_freeze_id." and platformUserNo =".$user_id." " );
			$root ['response_code'] = 1;
			$root ['info'] = "取消申请成功";
		}elseif($money_freeze['status'] ==2)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "已解冻";
		}elseif($money_freeze['status'] ==1)
		{
			$root ['response_code'] =0;
			$root ['info'] = "解冻中，没有申请解冻";
		}else{
			$root ['response_code'] = 0;
			$root ['info'] = "未找到符合条件的数据";
		}
		output($root);
	}
}
?>