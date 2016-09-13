<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class AdminTrashAction extends CommonAction{

	public function trash()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$condition['is_delete'] = 1;
		$this->assign("default_map",$condition);
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
			$map = array_merge($map,$this->get("default_map"));

		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name="Admin";
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}

	//相关操作
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M("Admin")->where("id=".$id)->getField("adm_name");		
		$c_is_effect = M("Admin")->where("id=".$id)->getField("is_effect");  //当前状态
		if(conf("DEFAULT_ADMIN")==$info)
		{
			$this->ajaxReturn($c_is_effect,l("DEFAULT_ADMIN_CANNOT_EFFECT"),1)	;	
		}	
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M("Admin")->where("id=".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}



	public function restore() {
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("Admin")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['adm_name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("Admin")->where ( $condition )->setField ( 'is_delete', 0 );
				if ($list!==false) {
					save_log($info.l("RESTORE_SUCCESS"),1);
					$this->success (l("RESTORE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("RESTORE_FAILED"),0);
					$this->error (l("RESTORE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}		
	}
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				$rel_data = M("Admin")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['adm_name'];	
					if(conf("DEFAULT_ADMIN")==$data['adm_name'])
					{
						$this->error ($data['adm_name'].l("DEFAULT_ADMIN_CANNOT_DELETE"),$ajax);
					}	
				}
				if($info) $info = implode(",",$info);
				$list = M("Admin")->where ( $condition )->delete();
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