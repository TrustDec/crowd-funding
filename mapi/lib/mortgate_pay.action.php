<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 充值诚意金
 */
class mortgate_pay
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$deal_id = intval( $GLOBALS ['request'] ['deal_id'] );
		
		$user = user_check ( $email, $password );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and id = ".$deal_id);
		if(!$deal_info)
		{
			$data = responseErrorInfo ( "项目不存在！" );
			output ( $data );
		}
		
		$new_money = user_need_mortgate ();
		//$has_money = $GLOBALS ['db']->getOne ( "select mortgage_money from " . DB_PREFIX . "user where id=" . $user_id );
		$has_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$user_id." and deal_id=".$deal_id." and status=1 ");
		$money = $new_money - $has_money;
		if ($money <= 0)
		{
			$data = responseErrorInfo ( "您的诚意金已支付，无需再支付！" );
			output ( $data );
		}
		
		//判断是否用第三方托管
		$collotion=is_tg(true);
		$collotion=$GLOBALS['db']->getALL("select id,name,class_name from  ".DB_PREFIX."collocation where is_effect=1 limit 1");
		if($collotion['0']['id'] >0 && $deal_info['ips_bill_no'] !='')
		{
			$data ['is_view_tg']=1;//显示第三方托管
			$left_money = 0;//余额不够，充值金额
			$payment_list=$collotion;
		}
		else
		{
			$data ['is_view_tg']=0;//不显示第三方托管
			if($money>$user['money']){
	   			$left_money = $money - floatval($user['money']);
	   		}else{
	   			$left_money = 0;//余额不够，充值金额
	   		}
	   		
	   		//$payment_list=getPayMentList ();
	   		$payment_list=array();
		}
		
		$data ['money'] = $money;
		$data ['left_money'] = $left_money;
		$data ['user_money'] = $user['money'];
		$data ['deal_id'] = $deal_id;
		
		$data ['enquier_num'] = app_conf("ENQUIER_NUM");//询价次数
		$data ['payment_list'] =$payment_list;
		$data ['response_code'] = 1;
		$data ['info'] = "需要支付诚意金";

		output ( $data );
	}
}

?>