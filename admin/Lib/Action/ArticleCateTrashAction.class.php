<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class ArticleCateTrashAction extends CommonAction{

	
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
		$name="ArticleCate";
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}

	    
	public function restore() {
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("ArticleCate")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("ArticleCate")->where ( $condition )->setField ( 'is_delete', 0 );
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

				$rel_data = M("ArticleCate")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("ArticleCate")->where ( $condition )->delete();

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