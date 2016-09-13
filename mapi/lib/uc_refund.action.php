<?php
class uc_refund{
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
			$root ['response_code'] = 1;
		}
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		//提现记录列表
		$refund_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_refund as ur where ur.user_id = ".intval($user['id'])." ");
		
		if($refund_count >0)
		{
			$refund_list = $GLOBALS['db']->getAll("select ur.*,ub.bank_name,ub.bankcard,ub.bankzone,ub.real_name from ".DB_PREFIX."user_refund as ur left join ".DB_PREFIX."user_bank as ub on ub.id = ur.user_bank_id where ur.user_id = ".intval($user['id'])." order by ur.id desc limit ".$limit);
			$refund_list_return=array();
			foreach($refund_list as $k=>$v)
			{
				$refund_list_return[$k]['id']=$v['id'];
				$refund_list_return[$k]['money']=format_price($v['money']);
				if($v['is_pay'] ==1)
				{
					$refund_list_return[$k]['status_info']="申请通过";
					$refund_list_return[$k]['reply_memo']=$v['reply'];
				}
				elseif($v['is_pay'] ==2)
				{
					$refund_list_return[$k]['status_info']="申请未通过";
					$refund_list_return[$k]['reply_memo']=$v['reply'];
				}
				elseif($v['is_pay'] ==3)
				{
					$refund_list_return[$k]['status_info']="提现成功";
					$refund_list_return[$k]['reply_memo']=$v['pay_log'];
				}else
				{
					$refund_list_return[$k]['status_info']="审核中，请耐心等待";
					$refund_list_return[$k]['reply_memo']='';
				}
				$refund_list_return[$k]['is_pay']=$v['is_pay'];
				$refund_list_return[$k]['pay_time']=to_date($v['pay_time']);
				$refund_list_return[$k]['create_time']=to_date($v['create_time']);
				$refund_list_return[$k]['bank_name']=$v['bank_name'];
				$refund_list_return[$k]['bankcard']=$v['bankcard'];
				$refund_list_return[$k]['bankzone']=$v['bankzone'];
				$refund_list_return[$k]['real_name']=$v['real_name'];
			}
		}
		
		$root['refund_list']=$refund_list_return;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $refund_count / $page_size ) 
		);
		
		output($root);
	}
	
	public function delete()
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
			$root ['response_code'] = 1;
		}
		
		$id = strim ( $GLOBALS ['request'] ['id'] ); // id
		//is_pay:0:待审核, 1:申请通过  2：申请未通过 3：提现成功  可删除 待审核，申请未通过
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund where id = ".$id." and user_id = ".intval($user['id'])." and is_pay in(0,2)");
		if($GLOBALS['db']->affected_rows()>0)
		{
			$root['status']=1;
			$root['info']="删除成功";
		}
		else
		{
			$root['status']=0;
			$root['info']="删除失败";
		}
		output($root);
	}
	
	public function save_refund()
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
			$root ['response_code'] = 1;
		}
		
		$user_bank_id=intval($GLOBALS ['request']['user_bank_id']);
		$money = floatval($GLOBALS ['request']['money']);
		$paypassword=strim($GLOBALS ['request']['paypassword']);
		
		if($money<=0)
		{
			$root['status']=0;
			$root['info']="提现金额出错";
			output($root);
		}
		if($user['paypassword']!=md5($paypassword)){
			$root['status']=0;
			$root['info']="支付密码错误";
			output($root);
		}
		
		$ready_refund_money =floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_refund where user_id = ".intval($user['id'])." and is_pay = 0"));
		if($ready_refund_money + $money > $user['money'])
		{
			$root['status']=0;
			$root['info']="提现超出限制";
			output($root);
		}
		
		$refund_data['user_bank_id'] = $user_bank_id;
		$refund_data['money'] = $money;
		$refund_data['user_id'] = $user['id'];
		$refund_data['create_time'] = get_gmtime();
		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."user_refund",$refund_data);
		
		if($re){
 			$root['status']=1;
			$root['info']="申请成功";;
 		}else{
 			$root['status']=0;
			$root['info']="申请失败";
 		}
 		
		output($root);
	}
}
?>