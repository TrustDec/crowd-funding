<?php
// +----------------------------------------------------------------------
// | easethink 方维借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require_once APP_ROOT_PATH."system/utils/child.php";
class GoodsCateAction extends CommonAction{
	public function index()
	{	
		$condition['is_delete'] = 0;
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
		
		$model = D ("GoodsCate");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$list = $this->get("list");
		
		$result = array();
		$row = 0;
		foreach($list as $k=>$v)
		{
			$v['level'] = -1;
			$v['name'] = $v['name'];
			$result[$row] = $v;
			$row++;
			$sub_cate = M("GoodsCate")->where(array("id"=>array("in",D("GoodsCate")->getChildIds($v['id'])),'is_delete'=>0))->findAll();
			$sub_cate = D("GoodsCate")->toFormatTree($sub_cate,'name');
			
			foreach($sub_cate as $kk=>$vv)
			{
				$vv['name']	=	$vv['title_show'];
				$vv['pname']	= M("GoodsCate")->where("id=".$vv['pid'])->getField("name");
				$result[$row] = $vv;
				$row++;
			}
		}
		//dump($result);exit;
		$this->assign("list",$result);
		$this->display ();
		return;
	}
	
	
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		
		$condition['id'] = $id;
		$vo = M("GoodsCate")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		$list = M("GoodsCate")->where('pid=0 and is_delete= 0 and is_effect=1 AND id <> '.$id)->findAll();
		
		$this->assign ( 'list', $list );
		$this->display ();
		
	}
	
	public function update()
	{
		$data = M("GoodsCate")->create();
		$data['name'] = strim($data['name']);
		if($data['name'] =='')
		{
			$this->error ("分类名不能为空");
		}
		
		//判断分类级子分类下是否有商品
		$ids_util = new child("goods_cate");
		$cate_id = intval($data['id']);
		$cate_ids = $ids_util->getChildIds($cate_id);
		$cate_ids[] = $cate_id;
		if($data['is_effect'] ==0)
		{
			if(M("Goods")->where(array ('cate_id' => array ('in', implode ( ',', $cate_ids ) )))->count()>0)
			{
				$this->error ("分类或子类下有商品数据，更新无效失败");
			}
		}
		
		// 更新数据
		$list=M("GoodsCate")->save($data);
		
		
		if (false !== $list) {
			clear_auto_cache("score_cates");
			M("GoodsCate")->where("id in(".implode ( ',', $cate_ids ).")")->setField("is_effect",$data['is_effect']);
			//成功提示
			save_log($data['name'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($data['name'].L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0);
		}
	}

	public function delete(){
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			
			//删除的验证
			$rel_data = M("GoodsCate")->where($condition)->findAll();
			$ids_util = new child("goods_cate");
			$all_ids=array();
			foreach($rel_data as $data)
			{
				$cate_id = intval($data['id']);
				$cate_ids = $ids_util->getChildIds($cate_id);
				$cate_ids[] = $cate_id;
				$all_ids=array_merge($all_ids,$cate_ids);
				
			}
			$all_ids=implode(',',$all_ids);

			if(M("Goods")->where(array ('cate_id' => array ('in', explode ( ',', $all_ids ) )))->count()>0)
			{
				$this->error ("分类或子类下有商品数据，删除失败",$ajax);
			}
			
			$all_condition = array ('id' => array ('in', explode ( ',', $all_ids ) ) );
			$cate_list = M("GoodsCate")->where($all_condition)->findAll();
			foreach($cate_list as $data)
			{
				$info[] = $data['name'];
			}
			if($info) $info = implode(",",$info);
			
			$list = M("GoodsCate")->where ( $all_condition )->delete();
				
			if ($list!==false) {
				clear_auto_cache("score_cates");
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
	
	public function add() {
		
		$condition['is_delete'] = 0;
		$condition['pid'] = 0;
		
		$list = M("GoodsCate") ->where($condition) -> findAll();
		
		$sort = M("GoodsCate") -> max("sort");
		
		$this->assign ( 'list', $list );
		$this->assign ( 'newsort', $sort  + 1);
		
		
		$this->display ();
	}
	
	
	public function insert()
	{
		$data = M("GoodsCate")->create ();
	
		// 更新数据
		$list=M("GoodsCate")->add ($data);
	
		if (false !== $list) {
			
			save_log($data['name'].L("INSERT_SUCCESS"),1);
			$this->assign("jumpUrl",u(MODULE_NAME."/add"));
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			$dbErr = M()->getDbError();
			save_log($data['name'].L("INSERT_FAILED").$dbErr,0);
			$this->error(L("INSERT_FAILED").$dbErr);
		}
		
	}
	
	public function set_effect()
	{	
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M("GoodsCate")->where("id=".$id)->getField("name");
		$c_is_effect = M("GoodsCate")->where("id=".$id)->getField("is_effect");  //当前状态
		
		$ids_util = new child("goods_cate");
		$cate_id = intval($id);
		$cate_ids = $ids_util->getChildIds($cate_id);
		$cate_ids[] = $cate_id;
		
		if($c_is_effect ==1)
		{
			if(M("Goods")->where(array ('cate_id' => array ('in', implode ( ',', $cate_ids ) )))->count()>0)
			{
				$this->error ("分类或子类下有商品数据，设置无效失败",$ajax);
			}
		}
		
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M("GoodsCate")->where("id in(".implode ( ',', $cate_ids ).")")->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1);
	}
	
	
}
?>