<?php
class pay_order_coll
{
	public function index()
	{
		$root=array();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$order_id = intval ( $GLOBALS ['request'] ['order_id'] );//订单id
		$paypassword = strim ( $GLOBALS ['request'] ['paypassword'] );//支付密码 
		$ips_bill_no_pay = intval ( $GLOBALS ['request'] ['ips_bill_no_pay'] );//1表用托管支付,0表不是，用第三方托管支付时传1值
		
		$is_continue_pay = intval ( $GLOBALS ['request'] ['is_continue_pay'] );//1表继续支付
		 // 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if($user_id)
		{
			$root ['user_login_status'] = 1;
			$root ['show_pay_btn'] = 0; // 0:不显示，支付按钮; 1:显示支付按钮
			
			if($paypassword=='')
			{
				$root ['info'] = "请输入支付密码";
				$root ['response_code'] = 0;
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			if(md5($paypassword) != $user['paypassword'])
			{
				$root ['info'] = "支付密码不正确";
				$root ['response_code'] = 0;
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where user_id = {$user_id} and id = " . $order_id );
			
			if (empty ( $order ))
			{
				$root ['response_code'] = 0;
				$root ['info'] = '订单不存在.';
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			if ($order ['order_status'] == 3)
			{
				$root ['order_status'] = 1;
				$root ['order_id'] = $order_id;
				$root ['order_sn'] = $order ['order_sn'];
				$root ['deal_name'] = $order ['deal_name'];
				$root ['response_code'] = 1;
				$root ['info'] = '订单已支付成功.';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap'] = '';
				output ( $root );
			}
			elseif($order ['order_status'] == 2 || $order ['order_status'] == 2)
			{
				$root ['order_status'] = 1;
				$root ['order_id'] = $order_id;
				$root ['order_sn'] = $order ['order_sn'];
				$root ['deal_name'] = $order ['deal_name'];
				$root ['response_code'] = 1;
				$root ['info'] = '订单已支付过了';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap'] = '';
				output ( $root );
			}
			
			if($ips_bill_no_pay ==0)
			{
				$root ['response_code'] = 0;
				$root ['info'] = '请选择支付方式';
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where is_delete = 0 and is_effect = 1 and id = " . $order ['deal_id'] );
			$collotion=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."collocation where is_effect=1 ");
			if(!$collotion['id'])
			{
				$root ['info'] = "网站没有开启第三方支付";
				$root ['response_code'] = 0;
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}else
			{
				$is_tg=1;
			}
			
			if(!$deal_info['ips_bill_no'])
			{
				$root ['info'] = "项目没有开启第三方支付";
				$root ['response_code'] = 0;
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			$is_user_tg=is_user_tg_mapi($is_tg,$user['ips_acct_no'],$user['ips_mer_code']);
			if(!$is_user_tg)
			{
				$root ['info'] = "您没有绑定第三方托管";
				$root ['response_code'] = 0;
				$root ['show_pay_btn'] = 0;
				output ( $root );
			}
			
			if($is_continue_pay ==1)//继续支付
			{
				$order_data['score'] = 0;
				$order_data['score_money'] = 0;
				$order_data ['credit_pay']=0;
				$order_data ['online_pay'] = 0;
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set credit_pay = ".$order_data['credit_pay'].",score=".$order_data['score'].",score_money=".$order_data['score_money'].",online_pay=".$order_data ['online_pay']." where id = ".intval($order['id'])." ");
			}
			
			$result = pay_order ( $order_id );
			if ($result ['status'] == 0)
			{
				$sign=md5(md5($paypassword).$order_id);
	 			$url=HTML_APP_ROOT."index.php?ctl=collocation&act=RegisterCreditor&order_id=".$order_id."&sign=".$sign."&user_id=".$user_id."&from=app";
				$root ['pay_wap']=SITE_DOMAIN.$url;
				$root ['info'] = $collotion['name'];
				$root ['pay_money_format'] = format_price($result ['money']);
				$root ['pay_money'] = $order ['money'];
				$root ['ips_bill_no_pay'] = 1;
				if($result ['money'] >0)
					$root ['show_pay_btn'] = 1;
				
			}elseif($result['status']==3){
				$root ['order_status'] = 1;
				$root ['info'] = '订单已支付成功';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap'] ='';
			}
			elseif($result['status']==1||$result['status']==2){
				$root ['order_status'] = 2;
				$root ['info'] = '订单已支付过了';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap'] ='';
			}elseif($result['status']==5)
			{
				$root ['order_status'] = 0;
				$root ['info'] = '订单已支付失败';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap']= '';
			}else{
				$root ['order_status'] = 0;
				$root ['info'] = '支付出错了';
				$root ['show_pay_btn'] = 0;
				$root ['pay_wap'] ='';
			}
			
			$root ['order_id'] = $order_id;
			$root ['order_sn'] = $order ['order_sn'];
			$root ['deal_name'] = $order ['deal_name'];
			$root ['response_code'] = 1;
			output ( $root );
			
		} else
		{
			$root ['response_code'] = 0;
			$root ['user_login_status'] = 0;
			$root ['info'] = "未登录";
			output ( $root );
		}
	}
}
?>