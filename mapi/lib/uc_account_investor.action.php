<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_investor
{
	public function index()
	{
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
		$type=intval($GLOBALS ['request']['type']);
		
		$deal= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 1 and user_id = ".intval($user['id']));
		if(!$deal)
		{
			$root ['response_code'] = 0;
			$root ['info'] = "请选择正确项目";
			output($root);
		}
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		if($type==1){
			$condition=" and  invest.type=1 and invest.deal_id=$deal_id and invest.money>0 and invest.status=1 ";
		}else{
			$condition=" and  invest.type=$type and invest.deal_id=$deal_id ";
		}
		
		$investor_list_num=$GLOBALS['db']->getOne("select count( invest.id) from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on invest.user_id=u.id where 1=1 $condition  ");
		if($investor_list_num)
		{
			$investor_list=$GLOBALS['db']->getAll("select invest.*,u.user_name from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on invest.user_id=u.id where 1=1 $condition order by id desc limit $limit ");
	   		$investor_list_return=array();
	   		$now_time=get_gmtime();
	   		foreach($investor_list as $k=>$v){
	   			$investor_list_return[$k]=$v;
	   			//$investor_list_return[$k]['id']=$v['id'];
	   			//$investor_list_return[$k]['deal_id']=$v['deal_id'];
	   			//$investor_list_return[$k]['user_name']=$v['user_name'];
	   			//$investor_list_return[$k]['stock_value']=$v['stock_value'];
	   			$investor_list_return[$k]['money']=format_price($v['money']);
	   			$investor_list_return[$k]['format_money']=format_price($v['money']);
	   			$investor_list_return[$k]['format_create_time']=to_date($v['create_time']);
	   			$investor_list_return[$k]['create_time']=$v['create_time'];
	   			if($type==1){
   					if($v['status']==0&&$now_time>$deal['end_time']){
						$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id= ".$v['id']);
   						$investor_list_return[$k]['status']=2;
   						$investor_list[$k]['status']=2;
   					}
   				}
   				
    			if($v['investor_money_status']==0&&$now_time>$deal['end_time']){
    				
   					$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=2 where id= ".$v['id']);
   					$investor_list_return[$k]['investor_money_status']=2;
   					$investor_list[$k]['investor_money_status']=2;
   				}elseif($v['investor_money_status']==1&&$now_time>$deal['pay_end_time']){
   					$investor_list_return[$k]['investor_money_status']=4;
   					$investor_list[$k]['investor_money_status']=4;
					deal_invest_break($v['id']);
   				}
   				$is_view_button=0;//0：不显示审核按扭，1：显示审核投资按扭;2:显示审核领投人按扭
   				$investor_money_status_info='';
   				if($v['type'] ==0 || $v['type'] ==2 || ($v['type'] ==1 && $investor_list[$k]['status'] ==1) )
   				{
   					if($investor_list[$k]['investor_money_status'] ==0)
   					{
   						if($now_time > $deal['begin_time'] &&  $now_time < $deal['end_time'])
   						{
   							if($deal['is_success'] ==1)
   							{
   								$is_view_button=1;//显示审核投资按扭
   								$investor_money_status_info='显示审核投资按扭';
   							}
   							else
   								$investor_money_status_info='项目未成功';
   						}	
   						else
   							$investor_money_status_info='审核未通过';
   					}
   					elseif($investor_list[$k]['investor_money_status'] ==1)
   					{
   						if($now_time > $deal['begin_time'] &&  $now_time < $deal['end_time'])
   							$investor_money_status_info='审核通过,未开始支付';
   						else
   							$investor_money_status_info='审核通过,等待用户支付';
   					}
   					elseif($investor_list[$k]['investor_money_status'] ==2)
   						$investor_money_status_info='审核不通过';
   					elseif($investor_list[$k]['investor_money_status'] ==3)
   						$investor_money_status_info='支付完成';
   					elseif($investor_list[$k]['investor_money_status'] ==4)
   						$investor_money_status_info='该用户未付款，已违约';
   				}
   				elseif( $v['type'] ==1 && $v['status'] ==0){
   					$is_view_button=2;//显示审核领投人按扭
   					$investor_money_status_info='显示审核领投人按扭';
   				}
   				elseif($v['type'] ==1 && $investor_list[$k]['status'] ==2){
   					$investor_money_status_info='投资人审核不通过';
   				}
   				
   				$investor_list_return[$k]['is_view_button']=$is_view_button;//0：不显示审核按扭，1：显示审核投资按扭;2:显示审核领投人按扭
   				$investor_list_return[$k]['investor_money_status_info']=$investor_money_status_info;
	   		
	   		
	   		
	   		}//end foreach
		}
		else
		{
			$investor_list_return=array();
		}
		
		$deal_array=array();
		$deal_array['id']=$deal['id'];
		$deal_array['image']=get_abs_img_root($deal['image']);
		$deal_array['name']=$deal['name'];
		$root['deal']=$deal_array;
		$root['investor_list'] = $investor_list_return;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $investor_list_num / $page_size )
		);
		

		output ( $root );
	}
	
	
	  	//修改估值
  	public function investor_examine(){
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
		
  		$id=intval($GLOBALS ['request']['id']);//申请id
  		$type=intval($GLOBALS ['request']['type']);//类型
  		$status=intval($GLOBALS ['request']['status']);//审核状态  1表通示审核 ，0：没有通过审核
  		
  		$item=$GLOBALS['db']->getRow("select il.*,us.user_name from  ".DB_PREFIX."investment_list as il left join ".DB_PREFIX."user as us on us.id=il.user_id where il.id=$id ");
  		if(!$item){
  			if($type ==0)
  				$type_info='询价';
  			elseif($type ==1)
  				$type_info='领头';
  			elseif($type ==2)
  				$type_info='跟头';
  			$root ['response_code'] = 0;
			$root ['info'] = $type_info."不存在";
			output($root);
  		}
  		$deal_id=intval($item['deal_id']);//项目id
  		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id and user_id=".$user_id);
  		if(!$deal){
  			$root ['response_code'] = 0;
			$root ['info'] = "项目不存在";
			output($root);
  		}
  		if($status==1){
  			$now_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."investment_list where deal_id=$deal_id and (investor_money_status=1 or investor_money_status=3  )");
  			if($type==0){
  				if($item['stock_value']<($now_money+$item['money'])){
	  				$root ['response_code'] = 0;
					$root ['info'] = "您允许的金额已超过估值的额度".$deal['stock_value'];
					output($root);
	  			}
  			}else{
  				if($deal['limit_price']<($now_money+$item['money'])){
	  				$root ['response_code'] = 0;
					$root ['info'] = "您允许的金额已超过你的融资额度".$deal['limit_price'];
					output($root);
	  			}
  			}
  			
  			$this->create_investor_pay($item,$deal,$user_id);
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=1 where id=$id ");
   			if($type==0){
   				$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."deal set limit_price=".$item['stock_value']." where id=$deal_id ");
   			}
   			
   		}else{
   			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=2 where id=$id ");
   		}

  		$root ['response_code'] = 1;
  		$root ['info'] ='审核成功';
  		output($root);
  	}
  	
  	
  	public function create_investor_pay($invest,$deal_info,$user_id){
  		
  		$order_info['deal_id'] = $deal_info['id'];
 		$order_info['user_id'] = intval($invest['user_id']);
		$order_info['user_name'] = $invest['user_name'];
		$order_info['total_price'] = $invest['money'];
		$order_info['delivery_fee'] = 0;
		$order_info['deal_price'] = $invest['money'];
		$order_info['support_memo'] = "";
		//$order_info['payment_id'] = "";
		//$order_info['bank_id'] = strim($_REQUEST['bank_id']);
		
 		
		$order_info['credit_pay'] = 0;
		$order_info['online_pay'] = 0;
		$order_info['deal_name'] = $deal_info['name'];
		$order_info['order_status'] = 0;
		$order_info['type'] = 1;
		$order_info['invest_id'] = $invest['id'];;
		$order_info['create_time']	= get_gmtime();
		$order_info['is_success'] = $deal_info['is_success'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info);
		
		$order_id = $GLOBALS['db']->insert_id();
		if(!$order_id){
			$root ['response_code'] = 0;
			$root ['info'] = "订单生成失败";
			output($root);	
		}else{
			//生成发送通知
			invest_pay_send($invest['id'],$order_id);
		}
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list SET order_id=$order_id where id=".$invest['id']);
  	}
}
?>