<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class MsgTemplateAction extends CommonAction{
	public function index()
	{
		$tpl_list = M("MsgTemplate")->where("type = 0 ")->findAll();
		$this->assign("tpl_list",$tpl_list);
		$this->display();
	}	
	public function load_tpl()
	{
		$name = trim($_REQUEST['name']);
		$tpl = M("MsgTemplate")->where("name='".$name."'")->find();
		if($tpl)
		{
			$tpl['tip'] = l("MSG_TIP_".strtoupper($name));
			$this->ajaxReturn($tpl,'',1);
		}
		else
		{
			$this->ajaxReturn('','',0);
		}		
	}
	
	public function update()
	{
		//$data = M(MODULE_NAME)->create ();
		$data = array();
		$data['id'] = intval($_REQUEST['id']);
		$data['name'] = strim($_REQUEST['name']);
		//$data['content'] = $_REQUEST['content'];
		$data['content'] = stripslashes($_REQUEST['content']);
		$data['is_html'] = intval($_REQUEST['is_html']);
		
		$return=array('info'=>'','status'=>'0');
		if($data['name']=='' || $data['id']==0)
		{
			$info=l("SELECT_MSG_TPL");
			header("Content-Type:text/html; charset=utf-8");
			echo $info;
		}
		$log_info = $data['name'];
		
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$info='"'.L("LANG_".$data['name']).'"模板'.L("UPDATE_SUCCESS");
			$return['status']=1;
			header("Content-Type:text/html; charset=utf-8");
			echo $info;
			
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			
			$info=L("LANG_".$data['name'])."模板".L("UPDATE_FAILED");
			$return['status']=0;
			header("Content-Type:text/html; charset=utf-8");
			echo $info;

		}
	}
	public function ajax_tpl()
	{
		$type=intval($_REQUEST['type']);
		$tpl_list = M("MsgTemplate")->where("type=".$type)->findAll();
		
		$data['list'] = $tpl_list;
		$data['type'] = $type;
		
		$this->ajaxReturn($data,'',1);
	}
}
?>