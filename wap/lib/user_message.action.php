<?php
require APP_ROOT_PATH.'wap/app/page.php';
class user_messageModule{
	 
	public function index()
	{	
		$cate_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."message_cate where is_project=1 "); 
 		$GLOBALS['tmpl']->assign('cate_id',$cate_id);
 		$GLOBALS['tmpl']->display("user_message.html");
	}
	public function save_info(){
		$data=array();
		$ajax=intval($_REQUEST['ajax']);
		$data['user_name']=strim($_REQUEST['user_name']);
		if(empty($data['user_name'])){
			showErr("请填写您的姓名",$ajax);
		}
		$data['tel']=strim($_REQUEST['tel']);
		if(empty($data['tel'])){
			showErr("请填写您的联系方式",$ajax);
		}
		$data['content']=strim($_REQUEST['content']);
		if(empty($data['content'])){
			showErr("请填写您的留言内容",$ajax);
		}
		$data['cate_id']=intval($_REQUEST['cate_id']);
		
		$data['create_time'] = NOW_TIME;
		$data['user_id']=intval($_REQUEST['user_id']);
		$GLOBALS['db']->autoExecute(DB_PREFIX."message",$data,"INSERT","","SILENT");
		$message_id = $GLOBALS['db']->insert_id();
		if($message_id>0){
			showSuccess("申请成功!",$ajax,url_wap("index"));
		}else{
			showErr("发送失败，请重新申请!",$ajax);
		}
	}
	
 }
?>