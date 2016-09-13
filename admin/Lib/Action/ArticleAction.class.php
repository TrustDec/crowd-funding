<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class ArticleAction extends CommonAction{
	public function index()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(trim($_REQUEST['title'])!='')
		{
			$condition['title'] = array('like','%'.trim($_REQUEST['title']).'%');			
		}
		if(intval($_REQUEST['cate_id'])>0)
		{
			$condition['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		$condition['is_delete'] = 0;
		$cate_list = M("ArticleCate")->where("is_delete = 0")->findAll();
		$this->assign("cate_list",$cate_list);
		$this->assign("default_map",$condition);
		parent::index();
	}

	public function add()
	{
		$cate_tree = M("ArticleCate")->where('is_delete = 0')->findAll();
		$cate_tree = D("ArticleCate")->toFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		$this->assign("new_sort", M("Article")->where("is_delete=0")->max("sort")+1);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['is_delete'] = 0;
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$cate_tree = M("ArticleCate")->where('is_delete = 0')->findAll();
		$cate_tree = D("ArticleCate")->toFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		$this->display ();
	}
	public function delete() {
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->setField ( 'is_delete', 1 );
				if ($list!==false) {
					save_log($info.l("DELETE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
					$this->success (l("DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("DELETE_FAILED"),0);
					$this->error (l("DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}		
	}
	
	public function restore() {
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];						
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->setField ( 'is_delete', 0 );
				if ($list!==false) {
					save_log($info.l("RESTORE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
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
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
				//删除相关预览图
//				foreach($rel_data as $data)
//				{
//					@unlink(get_real_path().$data['preview']);
//				}			
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					clear_auto_cache("get_help_cache");
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
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLE_TITLE_EMPTY_TIP"));
		}	
		if(!check_empty($data['content'])&&$data['rel_url']=='')
		{
			$this->error(L("ARTICLE_CONTENT_EMPTY_TIP"));
		}			
		if($data['cate_id']==0)
		{
			$this->error(L("ARTICLE_CATE_EMPTY_TIP"));
		}
		// 更新数据
		$log_info = $data['title'];
		$data['create_time'] = get_gmtime();
		$data['update_time'] = get_gmtime();
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			clear_auto_cache("get_help_cache");
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
		
//		if($_FILES['preview']['name']!='')
//		{
//			$result = $this->uploadImage();
//			if($result['status']==0)
//			{
//				$this->error($result['info'],$ajax);
//			}
//			//删除图片
//			@unlink(get_real_path().M("Article")->where("id=".$data['id'])->getField("preview"));
//			$data['preview'] = $result['data'][0]['bigrecpath'].$result['data'][0]['savename'];
//		}
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("title");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLE_TITLE_EMPTY_TIP"));
		}	
		if(!check_empty($data['content'])&&$data['rel_url']=='')
		{
			$this->error(L("ARTICLE_CONTENT_EMPTY_TIP"));
		}			
		if($data['cate_id']==0)
		{
			$this->error(L("ARTICLE_CATE_EMPTY_TIP"));
		}
		// 更新数据
		$data['update_time'] = get_gmtime();
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			clear_auto_cache("get_help_cache");
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
		$log_info = M("Article")->where("id=".$id)->getField("title");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Article")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		clear_auto_cache("get_help_cache");
		$this->success(l("SORT_SUCCESS"),1);
	}
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M(MODULE_NAME)->where("id=".$id)->getField("title");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		clear_auto_cache("get_help_cache");
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
}
?>