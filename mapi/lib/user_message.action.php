<?php
// +----------------------------------------------------------------------
// | Fanwe 文章列表页
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class user_message
{
	public function index(){
		
		$root = array();
		$root['response_code'] = 0;
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		$data['user_name']=strim($GLOBALS['request']['user_name']);
		$data['tel']=strim($GLOBALS['request']['tel']);
		$data['content']=strim($GLOBALS['request']['content']);
		
		
		if(empty($data['user_name'])){
			$root['info'] = "请填写您的姓名";
			output($root);
		}

		if(empty($data['tel'])){
			$root['info'] = "请填写您的联系方式";
			output($root);
		}
		
		if(empty($data['content'])){
			$root['info'] = "请填写您的留言内容";
			output($root);
		}
		//留言分类（项目登记id）
		$message_cate_project_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."message_cate where is_project=1 "); 
		$data['cate_id']=$message_cate_project_id;
		
		$data['create_time'] = NOW_TIME;
		$data['user_id']=$user_id;
		$GLOBALS['db']->autoExecute(DB_PREFIX."message",$data,"INSERT","","SILENT");
		$message_id = $GLOBALS['db']->insert_id();
		if($message_id>0){
			$root['response_code'] = 1;
			$root['info'] = "申请成功!";
		}else{
			$root['response_code'] = 0;
			$root['info'] = "发送失败，请重新申请!";
		}
		
		output($root);		
	}
}
?>
