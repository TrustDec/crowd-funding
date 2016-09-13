<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class StationMessageMsgListAction extends CommonAction{
	
	private $navs;
	
	public function __construct()
	{
		parent::__construct();
		$nav = array(
			'index' => array(
				'name'	=>	'首页',  //首页
			),			
			'deals' => array(
				'name'	=>	'项目列表',
				'acts'	=> array(
					'index'	=>	'产品众筹列表',
					'selfless'	=>	'公益众筹列表',
					'stock'	=>	'股权众筹列表',
 				),
			),
			'finance' => array(
				'name'	=>	'融资模块',
				'acts'	=> array(
					'index'	=>	'融资公司列表',
					'company_show'	=>	'融资公司详情',
					'company_finance'	=>	'融资详情',
 				),
			),
			'investor' => array(  
				'name'	=>	'天使投资人',
				'acts'	=> array(
					'invester_list'	=>	'列表',
				),
			),
			'deal' => array(  
				'name'	=>	'项目详情',
				'acts'	=> array(
					'show'	=>	'详情',
					'update'	=>	'动态',
					'support'	=>	'支持',
					'comment'	=>	'评论',
				),
			),
			'news' => array(  
				'name'	=>	'动态',
				'acts'	=> array(
					'index'	=>	'最新',
					'fav'	=>	'关注',
				),
			),
			'article_cate' => array(  
				'name'	=>	'文章列表',
			),
			'article' => array(  
				'name'	=>	'文章内容',
				'acts'	=> array(
					'index'	=>	'详情',
				),
			),
			'score_mall' => array(  
				'name'	=>	'积分商城',
			),
			'score_good_show' => array(  
				'name'	=>	'积分详情',
				'acts'	=> array(
					'index'	=>	'详情',
				),
			),
			'faq' => array(  
				'name'	=>	'新手帮助',
			),

		);
		
		if(LICAI_TYPE){
			$nav['licai'] = array(  
				'name'	=>	'理财模块',
				'acts'	=> array(
					'index'	=>	'理财首页',
					'deals'	=>	'理财列表页',
					'deal'	=>	'理财详情页',
				),
			);
		}
		
		if(HOUSE_TYPE ==1)
		{
			$nav['deals']['acts']['house']="房产众筹";
		}
		
		if(STOCK_TRANSFER_TYPE ==1)
		{
			$nav['stock_transfer']['name']="股权交易";
		}
		
		$this->navs = $nav;
	}
	

	
	public function msg_list()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$condition['type'] = 1;
		if(trim($_REQUEST['dest'])!='')
			$condition['user_id'] = array('like','%'.trim($_REQUEST['dest']).'%');
		if(trim($_REQUEST['content'])!='')
			$condition['log_info'] = array('like','%'.trim($_REQUEST['content']).'%');
		$this->assign("default_map",$condition);
	
		$map = $this->_search ();
 		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name='UserNotify';
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}

	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				//$MODULE_NAME='PromoteMsg';
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("PromoteMsg")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("PromoteMsg")->where ( $condition )->delete();	
			
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
	
	
	public function show_content()
	{
		$id = intval($_REQUEST['id']);
		header("Content-Type:text/html; charset=utf-8");
		echo M("UserNotify")->where("id=".$id)->getField("log_info");
	}

}
?>