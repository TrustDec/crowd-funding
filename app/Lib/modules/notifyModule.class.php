<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class notifyModule extends BaseModule
{
	
	
	public function index()
	{
      
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}	

		$all = intval($_REQUEST['all']);		
		$page_size = 20;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		if($all==0)
		{
			$cond = " and is_read = 0 ";
		}
		else 
		{
			$cond = " and 1=1 ";
		}
		$GLOBALS['tmpl']->assign("all",$all);
		$sql = "select * from ".DB_PREFIX."user_notify  where user_id = ".intval($GLOBALS['user_info']['id'])." $cond  order by log_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."user_notify  where user_id = ".intval($GLOBALS['user_info']['id'])." $cond  ";		
		
		$notify_list = $GLOBALS['db']->getAll($sql);
		$notify_count = $GLOBALS['db']->getOne($sql_count);	
		
		foreach($notify_list as $k=>$v)
		{
			if($v['is_read']){
				$notify_list[$k]['url'] = parse_url_tag("u:".$v['url_route']."|".$v['url_param']);
			}else{
				$notify_list[$k]['url'] = url("notify#read",array("id"=>$v['id']));
			}
			//$notify_list[$k]['url'] = parse_url_tag("u:".$v['url_route']."|".$v['url_param']);
			
		}

		$GLOBALS['tmpl']->assign("notify_list",$notify_list);
		
		require APP_ROOT_PATH.'app/Lib/page.php';
		$page = new Page($notify_count,$page_size);   //初始化分页对象 		
		//$p  =  $page->show();
		$p  =  $page->new_para_show("notify#index",array('all'=>$all));
		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->assign("page_title","站内通知");
		$GLOBALS['tmpl']->display("notify.html");
	}
	
	public function read(){
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$id = intval($_REQUEST['id']);
		$data = array('status'=>1,'info'=>'','jump'=>'');
		$item=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_notify where id= ".$id);
 		
		$GLOBALS['db']->query("update ".DB_PREFIX."user_notify set is_read = 1 where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		$url =  parse_url_tag("u:".$item['url_route']."|".$item['url_param']);
 		if($GLOBALS['db']->affected_rows()){
 			if($url){
				//app_redirect($url);
				$data['jump'] = $url;
				 ajax_return($data);
			}
		}else{
			//app_redirect(url("notify"));
			
			//$data['jump'] = url("notify");
			$data['jump'] = $url;
			 ajax_return($data);
		}
	}
	
	public function ignore()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$id = intval($_REQUEST['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."user_notify set is_read = 1 where id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		//app_redirect_preview();
		app_redirect(url("notify"));
	}
	public function ignoreall()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url("user#login"));
		}
		$GLOBALS['db']->query("update ".DB_PREFIX."user_notify set is_read = 1 where user_id = ".intval($GLOBALS['user_info']['id']));
		app_redirect(url("notify"));
	}
	
	public function delnotify()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$user_id = intval($GLOBALS['user_info']['id']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_notify where user_id = ".$user_id." and id = ".$id);
		
		showSuccess("",$ajax,get_gopreview());
	}
}
?>