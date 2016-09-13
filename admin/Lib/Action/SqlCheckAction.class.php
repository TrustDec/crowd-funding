<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class SqlCheckAction extends CommonAction{
	public function index()
	{
		 		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if(trim($_REQUEST['file_name'])!='')
		{
			$map['file_name'] = array('eq', trim($_REQUEST['file_name']) );
			$this->assign('file_name',trim($_REQUEST['file_name']) );
		}
		
		if(trim($_REQUEST['module'])!='')
		{
			$map['module'] = array('eq', trim($_REQUEST['module']) );
		}
		if(trim($_REQUEST['action'])!='')
		{
			$map['action'] = array('eq', trim($_REQUEST['action']) );
		}
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		 
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				
				$list = M(MODULE_NAME)->where ( $condition )->delete();
				if ($list!==false) {
					
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
		
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
}
?>