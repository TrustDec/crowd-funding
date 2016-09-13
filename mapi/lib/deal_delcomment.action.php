<?php
class deal_delcomment{
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
			$comment_id = intval( $GLOBALS ['request']['id']);
			$comment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_comment where id = ".$comment_id." and user_id = ".$user_id);
			if($comment_item)
			{
				$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where id = ".$comment_id." and user_id = ".$user_id);
				if($comment_item['log_id']==0)
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set comment_count = comment_count - 1 where id = ".$comment_item['deal_id']);
				if($ajax==1)
				{
					if($GLOBALS['db']->affected_rows()>0)
					{
						$data['status'] = 1;
						$data['logid'] = $comment_item['log_id'];
						$data['counthtml'] = "评论(".$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where log_id = ".$comment_item['log_id']).")";
						ajax_return($data);
					}
					else
					{
						$root['info'] = "删除失败";
						output($root);
					}
				}
				else
				{
					$root['info'] = "记录删除成功";
					output($root);
					//showSuccess("记录删除成功");
				}
			}
			else
			{
				$root['info'] = "您无权删除该记录";
				output($root);
			}
		}
		else{
			$root['user_login_status'] = 0;
			output($root);
		}
	}
}

?>
