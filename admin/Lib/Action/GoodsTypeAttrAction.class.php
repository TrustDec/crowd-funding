<?php
// +----------------------------------------------------------------------
// | easethink 方维借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class GoodsTypeAttrAction extends CommonAction{
	public function index()
	{
		$goods_type_id = intval($_REQUEST['goods_type_id']);
		$goods_type_info = M("GoodsType")->getById($goods_type_id);
		if(!$goods_type_info)
		{
			$this->error(l("GOODS_TYPE_NOT_EXIST"));
		}
		$this->assign("goods_type_info",$goods_type_info);
		parent::index();
	}
	public function add()
	{
		$goods_type_id = intval($_REQUEST['goods_type_id']);
		$goods_type_info = M("GoodsType")->getById($goods_type_id);
		if(!$goods_type_info)
		{
			$this->error(l("GOODS_TYPE_NOT_EXIST"));
		}
		$this->assign("goods_type_info",$goods_type_info);
		$this->display();
	}
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();
		$data['name']=strim($data['name']);
		$data['preset_value']=strim($data['preset_value']);
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add",array("goods_type_id"=>$data['goods_type_id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("ATTR_NAME_EMPTY_TIP"));
		}	
		if(!check_empty($data['preset_value'])&&$data['input_type']==1)
		{
			$this->error(L("PRESET_VALUE_EMPTY_TIP"));
		}			

		// 更新数据
		$log_info = $data['name'];
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}	
	
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		$goods_type_id = intval($vo['goods_type_id']);
		$goods_type_info = M("GoodsType")->getById($goods_type_id);
		if(!$goods_type_info)
		{
			$this->error(l("GOODS_TYPE_NOT_EXIST"));
		}
		$this->assign("goods_type_info",$goods_type_info);
		
		$this->display ();
	}
	
public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		$data['name']=trim($data['name']);
		$data['preset_value']=trim($data['preset_value']);
	
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("ATTR_NAME_EMPTY_TIP"));
		}	
		if(!check_empty($data['preset_value'])&&$data['input_type']==1)
		{
			$this->error(L("PRESET_VALUE_EMPTY_TIP"));
		}
		
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
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
					$info[] = $data['name'];	
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
	
}
?>