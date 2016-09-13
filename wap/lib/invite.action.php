<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class inviteModule
{
	public function index()
	{
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		$GLOBALS['tmpl']->assign("page_title","邀请列表");
		$condition='';
		$parameter=array();
		$status=$_REQUEST['status'];
		if($status!=''){
			$condition .= " and fi.status=".$status;
			$parameter[]="status=".$status;
			$GLOBALS['tmpl']->assign('status',$status);
		}
		$page_size = DEAL_COMMENT_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;
		$invite_list = $GLOBALS['db']->getAll("select fi.*,u.user_name as user_name,u.identify_business_name as identify_business_name from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.company_id = u.id  where fi.user_id=".$GLOBALS['user_info']['id']." ".$condition." limit ".$limit);
		foreach($invite_list as $k =>$v){
			if($v['type'] != 4 ){
				$user_name = $GLOBALS['db']->getOne("select u.user_name from ".DB_PREFIX."user as u LEFT JOIN  ".DB_PREFIX."finance_company as c on c.user_id = u.id where  c.id=".$v['company_id']);
				$invite_list[$k]['user_name'] =$user_name; 
			}
			
		}
		
		$invite_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.company_id = u.id where fi.user_id=".$GLOBALS['user_info']['id'].$condition);
		$invite_number=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_team as fi LEFT JOIN  ".DB_PREFIX."user as u on fi.company_id = u.id where fi.user_id=".$GLOBALS['user_info']['id']);
		
		$GLOBALS['tmpl']->assign("invite_number",$invite_number);
		$GLOBALS['tmpl']->assign("invite_list",$invite_list);
		$GLOBALS['tmpl']->assign("invite_count",$invite_count);
		require APP_ROOT_PATH.'app/Lib/page.php';
		$parameter_str="&".implode("&",$parameter);
		$page = new Page($invite_count,$page_size,$parameter_str);   //初始化分页对象 		
		$p  =  $page->show();

		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->display("invite.html");
	}
	
	public function set_invite_accept(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		$id= intval($_REQUEST['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."finance_company_team set status = 1,update_time=".NOW_TIME." where id = ".$id );

 		showSuccess("接受邀请成功",1,url_wap("invite#index"));		
	}
	public function set_invite_refuse(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}

		$id= intval($_REQUEST['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."finance_company_team set status = 2,update_time=".NOW_TIME." where id = ".$id );

 		showSuccess("拒绝邀请成功",1,url_wap("invite#index"));
	
	}
}
?>