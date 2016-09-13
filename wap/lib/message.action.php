<?php
class messageModule{
	
	public function history()
	{
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
		
		$dest_user_id = intval($_REQUEST['id']);
		$dest_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$dest_user_id." and is_effect = 1");
		if(!$dest_user_info)
		{
			app_redirect(url_wap("message"));
		}
		
		$GLOBALS['db']->query("update ".DB_PREFIX."user_message set is_read = 1 where user_id = ".intval($GLOBALS['user_info']['id'])." and dest_user_id = ".$dest_user_id);
		$page_size = 20;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size).",".$page_size	;

		$sql = "select * from ".DB_PREFIX."user_message  where user_id = ".intval($GLOBALS['user_info']['id'])." and dest_user_id = ".$dest_user_id." order by create_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."user_message where user_id = ".intval($GLOBALS['user_info']['id'])." and dest_user_id = ".$dest_user_id;

		$message_list = $GLOBALS['db']->getAll($sql);
		$message_count = $GLOBALS['db']->getOne($sql_count);	
		$GLOBALS['tmpl']->assign("dest_user_info",$dest_user_info);
		$GLOBALS['tmpl']->assign("message_list",$message_list);
		require APP_ROOT_PATH.'wap/app/page.php';
		$page = new Page($message_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->display("message_history.html");
	}
	
	public function delmessage()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			app_redirect(url_wap("user#login"));
		}
	
		$id = intval($_REQUEST['id']);
		$user_id = intval($GLOBALS['user_info']['id']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."user_message where user_id = ".$user_id." and id = ".$id);
	
		showSuccess("",$ajax,get_gopreview());
	}
	
	public function send()
	{
		$ajax = intval($_REQUEST['ajax']);
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
	
		$receive_user_id = intval($_REQUEST['id']);
		$send_user_id = intval($GLOBALS['user_info']['id']);
		if($receive_user_id==$send_user_id)
		{
			showErr("不能向自己发私信",$ajax);
		}
		else
		{
			$receive_user_info = $GLOBALS['db']->getRow("select user_name from ".DB_PREFIX."user where is_effect = 1 and id = ".$receive_user_id);
			if(!$receive_user_info)
			{
				showErr("收信人不存在",$ajax);
			}
				
			//发私信：生成发件与收件
			//1.生成发件
			$data = array();
			$data['create_time'] = NOW_TIME;
			$data['message'] = strim($_REQUEST['message']);
			$data['user_id'] = $send_user_id;
			$data['dest_user_id'] = $receive_user_id;
			$data['send_user_id'] = $send_user_id;
			$data['receive_user_id'] = $receive_user_id;
			$data['user_name'] = $GLOBALS['user_info']['user_name'];
			$data['dest_user_name'] = $receive_user_info['user_name'];
			$data['send_user_name'] = $GLOBALS['user_info']['user_name'];
			$data['receive_user_name'] = $receive_user_info['user_name'];
			$data['message_type'] = "outbox";
			$data['is_read'] = 1;
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_message",$data);
				
			//2.生成收件
			$data = array();
			$data['create_time'] = NOW_TIME;
			$data['message'] = strim($_REQUEST['message']);
			$data['user_id'] = $receive_user_id;
			$data['dest_user_id'] = $send_user_id;
			$data['send_user_id'] = $send_user_id;
			$data['receive_user_id'] = $receive_user_id;
			$data['user_name'] = $receive_user_info['user_name'];
			$data['dest_user_name'] = $GLOBALS['user_info']['user_name'];
			$data['send_user_name'] = $GLOBALS['user_info']['user_name'];
			$data['receive_user_name'] = $receive_user_info['user_name'];
			$data['message_type'] = "inbox";
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_message",$data);
				
			showSuccess("发送成功",$ajax);
		}
	}
}
?>