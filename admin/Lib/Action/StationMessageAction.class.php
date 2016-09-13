<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class StationMessageAction extends CommonAction{
	
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
	
	public function index()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$condition['type'] = 2;
		$this->assign("default_map",$condition);
		/*$name='PromoteMsg';
		parent::index();*/
		$map = $this->_search ();
 		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name='PromoteMsg';
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}
	
	

	
	public function add()
	{
		$this->assign("navs",$this->navs);
		$this->display();
	}
	
	public function load_module()
	{
		$id = intval($_REQUEST['id']);
		$module = trim($_REQUEST['module']);
		$act = M(MODULE_NAME)->where("id=".$id)->getField("u_action");
		$this->ajaxReturn($this->navs[$module]['acts'],$act);
	}
	
	public function insert()
	{
		//开始验证
		if($_REQUEST['content']=='')
		{
			$this->error(l("MESSAGE_CONTENT_EMPTY_TIP"));
		}
	
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
	
		$msg_data['type'] = 2;
		$msg_data['content'] = $_REQUEST['content'];
		
		$msg_data['send_time'] = trim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_status'] = 0;
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
	
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}		
		
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		
		if($_REQUEST['u_module']!='')
		{
			$msg_data['url_route'] = $_REQUEST['u_module'];
			if(isset($_REQUEST['u_action'])){
				$msg_data['url_route'] = $msg_data['url_route'].'#'.$_REQUEST['u_action'];}
					
				$msg_data['url_param'] = $_REQUEST['u_param'];
		}else {
			$msg_data['url_route'] = $_REQUEST['url'];
		}
		
		$rs = M("PromoteMsg")->add($msg_data);
		if($rs)
		{
					
			save_log($msg_data['content'].L("INSERT_SUCCESS"),1);
												
			$this->success(L("INSERT_SUCCESS"));
		}
		else
		{
			$this->error(L("INSERT_FAILED"));
		}
	
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
	
	public function edit() {
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;
		$vo = M("PromoteMsg")->where($condition)->find();
		$vo['is_html']=intval($vo['is_html']);
		$this->assign ( 'vo', $vo );
		$this->assign("navs",$this->navs);
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
	
		$this->display ();
	}
	
	public function update()
	{
		//开始验证
		if($_REQUEST['content']=='')
		{
			$this->error(L("MESSAGE_CONTENT_EMPTY_TIP"));
		}
	
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
	
		$msg_data['type'] = 2;
		$msg_data['content'] = $_REQUEST['content'];
	
	
		$msg_data['send_time'] = trim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
	
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		
		if($_REQUEST['u_module']!='')
		{
			$msg_data['url_route'] = $_REQUEST['u_module'];
			if(isset($_REQUEST['u_action'])){
				$msg_data['url_route'] = $msg_data['url_route'].'#'.$_REQUEST['u_action'];}
					
				$msg_data['url_param'] = $_REQUEST['u_param'];
		}else {
			$msg_data['url_route'] = $_REQUEST['url'];
		}
		
		$msg_data['id'] = intval($_REQUEST['id']);
		if(intval($_REQUEST['resend'])==1)
		{
			$msg_data['send_status'] = 0;
			M("PromoteMsgList")->where("msg_id=".intval($msg_data['id']))->delete();
		}
		$rs = M("PromoteMsg")->save($msg_data);
		if($rs)
		{
			save_log($msg_data['content'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		}
		else
		{
			$this->error(L("UPDATE_FAILED"));
		}
	
	}
		
}
?>