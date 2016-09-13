<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class MessageAction extends CommonAction{
	public function index()
	{
		if(intval($_REQUEST['cate_id'])>0)
		{
			$condition['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		$cate_list = M("MessageCate")->findAll();
		$this->assign("cate_list",$cate_list);
		$this->assign("default_map",$condition);
		
		parent::index();
	}
	
	public function show_content()
	{
		$id = intval($_REQUEST['id']);
		header("Content-Type:text/html; charset=utf-8");
		echo htmlspecialchars(M("DealMsgList")->where("id=".$id)->getField("content"));
	}
	
	public function content()
	{
		$id = intval($_REQUEST['id']);
		header("Content-Type:text/html; charset=utf-8");
		echo htmlspecialchars(M("Message")->where("id=".$id)->getField("content"));
	}
	
	public function send()
	{
		$id = intval($_REQUEST['id']);
		$msg_item = M("DealMsgList")->getById($id);
		if($msg_item)
		{
			if($msg_item['send_type']==0)
			{
				//短信
				require_once APP_ROOT_PATH."system/utils/es_sms.php";
				$sms = new sms_sender();
		
				$result = $sms->sendSms($msg_item['dest'],$msg_item['content']);
				$msg_item['result'] = $result['msg'];
				$msg_item['is_success'] = intval($result['status']);
				$msg_item['send_time'] = get_gmtime();
				M("DealMsgList")->save($msg_item);
				if($result['status'])
				{					
					header("Content-Type:text/html; charset=utf-8");
					echo l("SEND_NOW").l("SUCCESS");
				}
				else
				{
					
					header("Content-Type:text/html; charset=utf-8");
					echo l("SEND_NOW").l("FAILED").$result['msg'];
				}
			}
			else
			{			
				//邮件
				require_once APP_ROOT_PATH."system/utils/es_mail.php";
				$mail = new mail_sender();
		
				$mail->AddAddress($msg_item['dest']);
				$mail->IsHTML($msg_item['is_html']); 				  // 设置邮件格式为 HTML
				$mail->Subject = $msg_item['title'];   // 标题
				$mail->Body = $msg_item['content'];  // 内容	
				$result = $mail->Send();
				
				$msg_item['result'] = $mail->ErrorInfo;
				$msg_item['is_success'] = intval($result);
				$msg_item['send_time'] = get_gmtime();
				M("DealMsgList")->save($msg_item);
				if($result)
				{					
					header("Content-Type:text/html; charset=utf-8");
					echo l("SEND_NOW").l("SUCCESS");
				}
				else
				{
					
					header("Content-Type:text/html; charset=utf-8");
					echo l("SEND_NOW").l("FAILED").$mail->ErrorInfo;
				}
				
			}
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo l("SEND_NOW").l("FAILED");
		}
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
			
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				$rel_data = M(MODULE_NAME)->where($condition)->count();				
				$list = M(MODULE_NAME)->where ( $condition )->delete();		

				
				
				if ($list!==false) {
					foreach($rel_data as $k=>$v)
					{
						if($v['log_id']==0)
						{
							$GLOBALS['db']->query("update ".DB_PREFIX."deal set comment_count = comment_count - 1 where id = ".$v['deal_id']);
						}
					}
					save_log($info."成功删除",1);
					$this->success ("成功删除",$ajax);
				} else {
					save_log($info."删除出错",0);					
					$this->error ("删除出错",$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
		
}
?>