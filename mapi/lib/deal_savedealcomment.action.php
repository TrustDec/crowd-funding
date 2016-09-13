<?php
class deal_savedealcomment{
	public function index()
	{		
		$root = array();
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);		
		if($user_id>0)
		{
			$comment['deal_id'] = intval($GLOBALS ['request']['id']);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$comment['deal_id']." and is_delete = 0 and is_effect = 1 ");
			if(!$deal_info)
			{
				$root['info'] = "该项目暂时不能评论";
				output($root);
			}
			
			if(!check_ipop_limit(get_client_ip(),"deal_savedealcomment",3))
			$root['info'] = "提交太快";
			output($root);
			
			$comment['content'] = strim($_REQUEST['content']);
			$comment['user_id'] = intval($user_id);
			$comment['create_time'] =  NOW_TIME;
			$comment['user_name'] = $user['user_name'];
			$comment['pid'] = intval($_REQUEST['pid']);
			$comment['deal_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal where id = ".$comment['deal_id']));
			$comment['reply_user_id'] = intval($GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."deal_comment where id = ".$comment['pid']));		
			$comment['deal_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['deal_user_id']));
			$comment['reply_user_name'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".intval($comment['reply_user_id']));
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_comment",$comment);
			$comment['id'] = $GLOBALS['db']->insert_id();		
			
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set comment_count = comment_count+1 where id = ".$comment['deal_id']);
			
			if(intval($_REQUEST['syn_weibo'])==1)
			{
				$weibo_info = array();
				$weibo_info['content'] = $comment['content']." ".get_domain().url("deal#show",array("id"=>$comment['deal_id']));
				$img = $GLOBALS['db']->getOne("select image from ".DB_PREFIX."deal where id = ".intval($comment['deal_id']));
				if($img)$weibo_info['img'] = APP_ROOT_PATH."/".$img;
				syn_weibo($weibo_info);
			}
			
			if($ajax==1)
			{
				$data['status'] = 1;
				ajax_return($data);
			}
			else
			{
				showSuccess("发表成功");
			}
		}
		else{
			$root['user_login_status'] = 0;
			output($root);
		}
		
	}
}

?>
