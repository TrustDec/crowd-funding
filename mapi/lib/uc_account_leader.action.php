<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹支持的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_account_leader
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
		$now_time=get_gmtime();
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id." and is_delete = 0 and is_effect = 1  and user_id = ".intval($user['id']));
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
		
   		$investor_list_num=$GLOBALS['db']->getOne("select count(*) as num from  ".DB_PREFIX."investment_list where type=1 and deal_id=$deal_id order by id desc limit $limit ");
		
		$paid_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_pay_log where deal_id = ".$deal_id);
		if($investor_list_num >0)
		{	
			$user_level= load_auto_cache("user_level");
			$investor_list_array=array();
			$investor_list=$GLOBALS['db']->getAll("select invest.*,u.user_name,u.mobile,u.user_level,u.is_investor from  ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."user as u on u.id=invest.user_id where invest.type=1 and invest.deal_id=$deal_id order by invest.id desc limit $limit ");
			$now_time=get_gmtime();
			
			foreach($investor_list as $k=>$v)
			{
				$investor_list_array[$k]['id']=$v['id'];
				$investor_list_array[$k]['user_name']=$v['user_name'];
				$investor_list_array[$k]['user_level_icon']=get_mapi_user_level_icon($user_level,$v['user_level']);
				$investor_list_array[$k]['is_investor']=$v['is_investor'];
				
				$investor_list_array[$k]['mobile']=$v['mobile'];
				$investor_list_array[$k]['format_create_time']=to_date($v['create_time']);
	   			$investor_list_array[$k]['create_time']=$v['create_time'];
	   			$investor_list_array[$k]['cates']=implode('、',unserialize($v['cates']));
	   			$investor_list_array[$k]['introduce']=$v['introduce'];
	   			
	   			if($v['status']==0&&$now_time>$deal_info['end_time']){
						$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id= ".$v['id']);
   						$investor_list_return[$k]['status']=2;
   						$investor_list[$k]['status']=2;
   				}
	   			$investor_list_array[$k]['status']=$investor_list[$k]['status'];//1:审核通过
	   			if($investor_list[$k]['status'] ==1)
	   				$status_info="审核通过";
	   			elseif($investor_list[$k]['status'] ==2)
	   				$status_info="审核未通过";
	   			else
	   				$status_info="未审核";
	   			$investor_list_array[$k]['status_info']=$status_info;	
			}
		}
		else
		{
			$investor_list_array = array();
		}

		$root['investor_list'] = $investor_list_array;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $investor_list_num / $page_size )
		);
		$root['deal_id'] = $deal_id;
		output ( $root );
	}
	
	public function lead_examine(){
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
		
  		$id=intval($GLOBALS ['request']['id']);
  		$status=intval($GLOBALS ['request']['status']);
  		$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."investment_list where id=$id and type=1 ");
  		if(!$item){
  			$root ['response_code'] = 0;
  			$root ['info'] = "该申请不存在";
  			output($root);
  		}
  		if($status==1){
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=1 where id=$id ");
  			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where deal_id=".$item['deal_id']." and type=1 and id!=".$item['id']);
  		}else{
   			$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set status=2 where id=$id ");
  		}
  		$root ['response_code'] = 1;
  		$root ['info'] = "审核成功";
  		
  		output($root);
  	}
}
?>