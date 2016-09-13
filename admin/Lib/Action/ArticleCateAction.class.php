<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class ArticleCateAction extends CommonAction{
	public function index()
	{
		$condition['is_delete'] = 0;
		$condition['pid'] = 0;
		$this->assign("default_map",$condition);
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
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
		$list = $this->get("list");
		
		$result = array();
		$row = 0;
		foreach($list as $k=>$v)
		{
			$v['level'] = -1;
			$v['title'] = $v['title'];
			$result[$row] = $v;
			$row++;
			$sub_cate = M(MODULE_NAME)->where(array("id"=>array("in",D(MODULE_NAME)->getChildIds($v['id'])),'is_delete'=>0))->findAll();
			$sub_cate = D(MODULE_NAME)->toFormatTree($sub_cate);
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
	
	
	public function trash()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$condition['is_delete'] = 1;
		$this->assign("default_map",$condition);
		parent::index();
	}
	public function add()
	{
		
		$cate_tree = M(MODULE_NAME)->where('is_delete = 0')->findAll();
		$cate_tree = D(MODULE_NAME)->toFormatTree($cate_tree);
	//	var_dump($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		$this->assign("new_sort", M(MODULE_NAME)->where("is_delete=0")->max("sort")+1);		
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['is_delete'] = 0;
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		$ids = D(MODULE_NAME)->getChildIds($id);
		$ids[] = $id;
		
		$condition['is_delete'] = 0;
		$condition['id'] = array('not in',$ids);

		$cate_tree = M(MODULE_NAME)->where($condition)->findAll();
		$cate_tree = D(MODULE_NAME)->toFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		
		$this->display ();
	}

	public function insert() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLECATE_TITLE_EMPTY_TIP"));
		}	
		//开始同步type_id
		if($data['pid']>0)
		{
			$data['type_id'] = M("ArticleCate")->where("id=".$data['pid'])->getField("type_id");
		}
		// 更新数据
		$log_info = $data['title'];
		$data['seo_title'] = strim($data['seo_title']);
		if(!$this->check_bs($data['seo_title'])){
 			$this->error("SEO标识 已经存在或和前台模块名称一样",0,$log_info.L("UPDATE_FAILED"));
		}
		$data['title'] = strim($data['title']);
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			$this->create_httpd();
 			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			clear_auto_cache("cache_shop_acate_tree");
			clear_auto_cache("deal_shop_acate_belone_ids");
			clear_auto_cache("get_help_cache");
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M(MODULE_NAME)->where("id=".$id)->getField("title");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
		clear_auto_cache("cache_shop_acate_tree");
		clear_auto_cache("get_help_cache");
		save_log($log_info.l("SORT_SUCCESS"),1);
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
		clear_auto_cache("cache_shop_acate_tree");
		clear_auto_cache("deal_shop_acate_belone_ids");
		clear_auto_cache("get_help_cache");
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("title");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		$seo_title = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("seo_title");
		$data['seo_title'] = strim($data['seo_title']);
		if(!$this->check_bs($data['seo_title'],$seo_title)){
 			$this->error("SEO标识 已经存在或和前台模块名称一样",0,$log_info.L("UPDATE_FAILED"));
		}
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLECATE_TITLE_EMPTY_TIP"));
		}			
		if($data['pid']>0)
		{
			$data['type_id'] = M("ArticleCate")->where("id=".$data['pid'])->getField("type_id");
		}
		//开始同步type_id
		$ids = D("ArticleCate")->getChildIds($data['id']);		
		M("ArticleCate")->where(array("id"=>array("in",$ids)))->setField("type_id",$data['type_id']);
		
		$data['title'] = strim($data['title']);
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			$this->create_httpd();			
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			clear_auto_cache("cache_shop_acate_tree");
			clear_auto_cache("deal_shop_acate_belone_ids");
			clear_auto_cache("get_help_cache");
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}

	public function delete() {
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				if(M("ArticleCate")->where(array ('pid' => array ('in', explode ( ',', $id ) ),'is_delete'=>0 ))->count()>0)
				{
					$this->error (l("SUB_ARTICLECATE_EXIST"),$ajax);
				}
				if(M("Article")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ),'is_delete'=>0 ))->count()>0)
				{
					$this->error (l("SUB_ARTICLE_EXIST"),$ajax);
				}
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->setField ( 'is_delete', 1 );
				if ($list!==false) {
					save_log($info.l("DELETE_SUCCESS"),1);
					clear_auto_cache("cache_shop_acate_tree");
					clear_auto_cache("deal_shop_acate_belone_ids");
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
					clear_auto_cache("cache_shop_acate_tree");
					clear_auto_cache("deal_shop_acate_belone_ids");
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
				if(M("Article")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ) ))->count()>0)
				{
					$this->error (l("SUB_ARTICLE_EXIST"),$ajax);
				}

				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();

				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					clear_auto_cache("cache_shop_acate_tree");
					clear_auto_cache("deal_shop_acate_belone_ids");
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
	
	public function get_bs(){
		$article_cates_bs= load_auto_cache("article_cates_bs",array(),false);
     	$article_cates_bs=array_keys($article_cates_bs);
     	return $article_cates_bs;
	}
	 /*
      * 
      */
    public function create_httpd(){
     	$htppd=APP_ROOT_PATH.".htaccess";
     	$content=file_get_contents($htppd);
     	if(strpos($content,'title')==false){
     		$htppd_old=APP_ROOT_PATH."public/rewrite_rule/.htaccess";
     		$content=file_get_contents($htppd_old);
     	}
     	$article_cates_bs=$this->get_bs();
     	$article_cates_bs=implode('|',$article_cates_bs);
      	$str=trim($article_cates_bs,'|');
       	$content=str_replace("title",$str,$content);
        file_put_contents($htppd,$content);
     }
     /*
      * 
      */
     public function check_bs($bs,$seo_title){
     	if(!$bs){
     		return true;
     	}
		$bs = strim($bs);
     	$article_cates_bs=$this->get_bs();
     	if($seo_title){
     		foreach($article_cates_bs as $k=>$v){
     			if($v==$seo_title){
     				unset($article_cates_bs[$k]);
     			}
     		}
     	}
     	$article_cates_bs=array_merge($article_cates_bs,array('account','ajax','article_cate','article',
     	'avatar','cart','collocation','comment','deal_vote','deal','deals_cate',
     	'deals','faq','help','home','index','investor','message','news','notify',
     	'online_book','payment','project','referra','settings','user_message','user',
     	'vote'));
      	if(in_array($bs,$article_cates_bs)){
     		return false;
     	}else{
     		return true;
     	}
     }
}
?>