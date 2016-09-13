<?php
class deal_save_update{
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
	
			$id = intval($GLOBALS ['request']['id']);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and is_effect = 1 and user_id = ".$user_id);
			if(!$deal_info)
			{
				$root['info'] = "不能更新该项目的动态";
				output($root);
			}
			else
			{
				$data['log_info'] = strim($_REQUEST['log_info']);
				if($data['log_info']=="")
				{
					$root['info'] = "请输入更新的内容";
					output($root);
				}
				$data['image'] = strim($_REQUEST['image'])!=""?replace_public($_REQUEST['image']):"";
				$data['vedio'] = strim($_REQUEST['vedio']);
				if($data['vedio']!="")
				{
					$data['source_vedio'] = $data['vedio'];
				}
				$data['user_id'] = $user_id;
				$data['deal_id'] = $id;
				$data['create_time'] = NOW_TIME;
				$data['user_name'] = $user['user_name'];
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_log",$data);
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set log_count = log_count + 1 where id = ".$deal_info['id']);
				showSuccess("",$ajax,url("deal#update",array("id"=>$deal_info['id'])));
			}
		}
		else{
			$root['user_login_status'] = 0;
			output($root);
		}
	}
}

?>
