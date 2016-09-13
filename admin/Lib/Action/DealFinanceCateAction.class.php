<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealFinanceCateAction extends CommonAction{
	public function index()
	{
		$condition['pid'] = 0;
		$this->assign("default_map",$condition);
		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = M ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$list = $this->get("list");
		$result = array();
		$row = 0;
		foreach($list as $k=>$v)
		{
			$v['level'] = -1;
			$v['title'] = $v['name'];
			$result[$row] = $v;
			$row++;
			$sub_cate = M(MODULE_NAME)->where(array("id"=>array("in",D(MODULE_NAME)->getChildIds($v['id']))))->findAll();
			$sub_cate = D(MODULE_NAME)->toNameFormatTree($sub_cate);
			foreach($sub_cate as $kk=>$vv)
			{
				$vv['title']	=	$vv['title_show'];
				$result[$row] = $vv;
				$row++;
			}
		}
		//dump($result);exit;
		$this->assign("list",$result);
		$this->display ();
		
		return;
	}
	public function add()
	{
		$cate_tree = M(MODULE_NAME)->where('is_effect = 1')->findAll();
		$cate_tree = D(MODULE_NAME)->toNameFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		$this->assign("new_sort", M(MODULE_NAME)->max("sort")+1);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$cate_tree = M(MODULE_NAME)->where('is_effect = 1')->findAll();
		$cate_tree = D(MODULE_NAME)->toNameFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				if(M("Deal")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ),'type'=>2 ))->count()>0)
				{
					$this->error ("分类下有项目，无法删除",$ajax);
				}
				if(M("MODULE_NAME")->where(array ('pid' => array ('in', explode ( ',', $id ) ) ))->count()>0){
					$this->error ("分类下有子分类，无法删除",$ajax);
				}
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
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error("请输入分类名称");
		}	
		
		// 更新数据
		$log_info = $data['name'];
		$data['is_effect']=1;
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			clear_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error("请输入分类名称");
		}	
		

		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			clear_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M(MODULE_NAME)->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
		clear_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
	
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M(MODULE_NAME)->where("id=".$id)->getField("name");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		
		$cate_ids=D(MODULE_NAME)->getChildIds($id);
		$cate_ids[]=$id;
		$cate_ids_str=implode(',',$cate_ids);
		if($c_is_effect ==1)
		{
			
			$deal_count=D("Deal")->where("cate_id in (".$cate_ids_str.") and type=2")->count();
			if($deal_count >0)
			{
				$this->error("该分类或子类，融资众筹项目在使用，不能删除",1);
			}
		}
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id in(".$cate_ids_str.")")->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		clear_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;
	}

}
?>