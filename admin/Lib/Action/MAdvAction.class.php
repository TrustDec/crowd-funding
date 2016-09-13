<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class MAdvAction extends CommonAction{
	public function index() {


		$list = M("MAdv")->findAll();
		foreach($list as $k=>$v)
		{
			if ($v['type'] == '1'){
				$list[$k]['type_name'] = '文章ID';
			}	
			else if ($v['type'] == '2')
				$list[$k]['type_name'] = 'URL连接';
			
			if ($v['page'] == 'top')
				$list[$k]['page_name'] = '首页广告';
//			else if ($v['page'] == 'deal')
//				$list[$k]['page_name'] = '首页借款单';	
			else if ($v['page'] == 'start')
				$list[$k]['page_name'] = '启动页广告';			
				
		}
		
		//dump($list);
		$this->assign("list",$list);
		$this->display ();

	}
	
	public function add()
	{
		$this->assign("new_sort",intval(M(MODULE_NAME)->max("sort"))+1);
	
		$this->display();
	}
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		
		$_POST['data'] = "";
		switch($_POST['type'])
		{
			case 1:
				//$adv_data['data_id'] = (int)$_POST['data_id'];
				$_POST['data'] = trim($_POST['data_id']);//serialize($adv_data);
			break;

			case 2:
				//$adv_data['url'] = trim($_POST['url']);
				$_POST['data'] =  trim($_POST['url']);//serialize($adv_data);
			break;

		}
		
		$data = M(MODULE_NAME)->create ();
			
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAME_EMPTY_TIP"));
		}	

		$log_info = $data['name'];
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			$this->create_app_start();
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}

	public function create_app_start(){
		 
		if($_POST['page']=='start'){
 			$index_list = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_adv where status = 1 and page = 'start'  order by sort ");
			$str = json_encode($index_list);
			file_put_contents(APP_ROOT_PATH.'"/public/app_start.log',$str);
		}
	}
	
	public function edit()
	{
		$id = intval($_REQUEST['id']);
		$vo = M("MAdv")->getById($id);
		//$vo['data'] = $vo['data'];
		
		$this->assign ('vo', $vo);


		$this->display();
	}
	
	
	public function update() {
		B('FilterString');
		
		$_POST['data'] = "";
		switch($_POST['type'])
		{
			case 1:
				//$adv_data['data_id'] = (int)$_POST['data_id'];
				$_POST['data'] = trim($_POST['data_id']);//serialize($adv_data);
			break;

			case 2:
				//$adv_data['url'] = trim($_POST['url']);
				$_POST['data'] =  trim($_POST['url']);//serialize($adv_data);
			break;

		}
		
		$data = M(MODULE_NAME)->create ();	
		$log_info = $data['id'];
		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAME_EMPTY_TIP"));
		}
		
		$log_info = $data['name'];
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			$this->create_app_start();
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
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
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
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
}
?>