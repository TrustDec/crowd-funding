<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class user_messageModule extends BaseModule
{
	 
	public function save_info(){
		if(!check_hash_key()){
			showErr("非法请求!",1);
		}
 		$data=array();
		$ajax=intval($_REQUEST['ajax']);
		$data['user_name']=strim($_REQUEST['user_name']);
		if(empty($data['user_name'])){
			showErr("user_name",$ajax);
		}
		$data['tel']=strim($_REQUEST['tel']);
		if(empty($data['tel'])){
			showErr("tel",$ajax);
		}
		$data['content']=strim($_REQUEST['content']);
		if(empty($data['content'])){
			showErr("content",$ajax);
		}
		$data['cate_id']=intval($_REQUEST['cate_id']);
		
		$data['create_time'] = NOW_TIME;
		$data['user_id']=intval($_REQUEST['user_id']);
		$GLOBALS['db']->autoExecute(DB_PREFIX."message",$data,"INSERT","","SILENT");
		$message_id = $GLOBALS['db']->insert_id();
		if($message_id>0){
			showSuccess("申请成功!",$ajax,url("index"));
		}else{
			showErr("发送失败，请重新申请!",$ajax);
		}
	}
	
 }
?>