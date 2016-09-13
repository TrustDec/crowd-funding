<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商城系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class PromoteMsgMailAction extends CommonAction{
/**
 * 邮件列表
 */
	public function mail_index()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$condition['type'] = 1;
		$this->assign("default_map",$condition);
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
			$map = array_merge($map,$this->get("default_map"));

		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ('PromoteMsg');
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}

/**
 * 添加邮件
 */
	public function add_mail()
	{
		
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		parent::index();
	}
	
	public function insert_mail()
	{		
		
		//开始验证
		if($_REQUEST['title']=='')
			{
				$this->error(L("MAIL_TITLE_EMPTY_TIP"));
			}
			if($_REQUEST['content']=='')
			{
				$this->error(l("MAIL_CONTENT_EMPTY_TIP"));
			}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		$msg_data['type'] = 1;
		$msg_data['title'] = $_REQUEST['title'];
		$msg_data['content'] = $_REQUEST['content'];
		$msg_data['is_html'] = intval($_REQUEST['is_html']);

		
		$msg_data['send_time'] = trim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_status'] = 0;
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;

			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		
		$rs = M("PromoteMsg")->add($msg_data);
		if($rs)
		{
				save_log($msg_data['title'].L("INSERT_SUCCESS"),1);
				$this->success(L("INSERT_SUCCESS"));
		}
		else
		{			
				$this->error(L("INSERT_FAILED"));
		}
		
		
	}
/**
 * 编辑邮件
 */
	public function edit_mail() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M('PromoteMsg')->where($condition)->find();
		$vo['is_html']=intval($vo['is_html']);
		$this->assign ( 'vo', $vo );
 		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		$this->display ();
	}

public function update_mail()
	{		
		//开始验证
		if($_REQUEST['title']=='')
			{
				$this->error(L("MAIL_TITLE_EMPTY_TIP"));
			}
			if($_REQUEST['content']=='')
			{
				$this->error(L("MAIL_CONTENT_EMPTY_TIP"));
			}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		
		
		$msg_data['type'] = 1;
		$msg_data['title'] = $_REQUEST['title'];
		$msg_data['content'] = $_REQUEST['content'];
		$msg_data['is_html'] = intval($_REQUEST['is_html']);

		
		$msg_data['send_time'] = trim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		$msg_data['id'] = intval($_REQUEST['id']);
		if(intval($_REQUEST['resend'])==1)
		{
			$msg_data['send_status'] = 0;
			M("PromoteMsgList")->where("msg_id=".intval($msg_data['id']))->delete();
		}
		$rs = M("PromoteMsg")->save($msg_data); 
		if($rs)
		{
			save_log($msg_data['title'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		}
		else
		{			
			$this->error(L("UPDATE_FAILED"));
		}
		
	}
/**
 * 彻底删除
 */
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("PromoteMsg")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("PromoteMsg")->where ( $condition )->delete();	
			
				if ($list!==false) {
					M("PromoteMsgList")->where(array ('msg_id' => array ('in', explode ( ',', $id ) ) ))->delete();
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
/**
 * 查看对列
 */
	public function index()
	{
		if(trim($_REQUEST['dest'])!='')
		$condition['dest'] = array('like','%'.trim($_REQUEST['dest']).'%');
		if(trim($_REQUEST['content'])!='')
		$condition['content'] = array('like','%'.trim($_REQUEST['content']).'%');
		$this->assign("default_map",$condition);
		parent::index();
	}
	/**
	 * 查看内容
	 */
	public function show_content()
	{
		$id = intval($_REQUEST['id']);
		header("Content-Type:text/html; charset=utf-8");
		echo M("PromoteMsgList")->where("id=".$id)->getField("content");
	}
	
	public function send()
	{
		$id = intval($_REQUEST['id']);
		$msg_item = M("PromoteMsgList")->getById($id);
		if($msg_item)
		{
			if($msg_item['send_type']==0)
			{
				//短信
				
				require_once APP_ROOT_PATH."system/utils/es_sms.php";
				$sms = new sms_sender();
		
				$result = $sms->sendSms($msg_item['dest'],$msg_item['content'],get_gmtime(),1);
				$msg_item['result'] = $result['msg'];
				$msg_item['is_success'] = intval($result['status']);
				$msg_item['send_time'] = NOW_TIME;
				M("PromoteMsgList")->save($msg_item);
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
				

				$mail->Body = $mail_content;  // 内容	
				$result = $mail->Send();
				
				$msg_item['result'] = $mail->ErrorInfo;
				$msg_item['is_success'] = intval($result);
				$msg_item['send_time'] = NOW_TIME;
				M("PromoteMsgList")->save($msg_item);
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
	
	public function msglist_foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M('PromoteMsgList')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('PromoteMsgList')->where ( $condition )->delete();	
			
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
}
?>