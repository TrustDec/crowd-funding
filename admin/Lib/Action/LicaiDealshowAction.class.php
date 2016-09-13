<?php

class LicaiDealshowAction extends CommonAction{

    public function index()
	{	
		$condition = " ";
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			if(strim($_REQUEST['_order']) != "id")
			{
				$order = strim($_REQUEST ['_order']);
				if($order == "show_time")
				{
					$order = " d.id ";
				}
			}
			else
			{
				$order = "d.".strim($_REQUEST ['_order']);
			}			
		} else {
			$order = " d.id ";
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])){
			$sort = strim($_REQUEST ['_sort']) ? 'asc' : 'desc';
		} else {
			$sort = 'desc';
		}
		
		$sortImg = $sort; //排序图标
		$sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
		
		if($order == "")
		{
			$order_str = "";
		}
		else
		{
			$order_str = " order by ". str_replace("_format","",$order)." ".$sort;
		}
		
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 20;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_dealshow  where 1=1 ".$condition." order by sort asc");

		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT d.*,l.name as licai_name FROM ".DB_PREFIX."licai_dealshow d LEFT JOIN ".DB_PREFIX."licai l ON  l.id=d.licai_id  where 1=1 ".$condition.$order_str." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$order = str_replace('d.',"",$order);
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"首页显示订单");
		$this->display ();
	}
	
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		$vo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai_dealshow where  id = ".$id);
		$vo['licai_name'] = $GLOBALS['db']->getOne("SELECT `name` FROM ".DB_PREFIX."licai where  id = ".$vo['licai_id']);
		$this->assign ( 'vo', $vo );
		
		$this->display ();
	}
	public function update()
	{
		B('FilterString');
		$data = M(MODULE_NAME)->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
		$log_info = $data['id'];
		
		$list=M(MODULE_NAME)->save ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function delete()
	{
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				
				$info = $id;
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
	public function add()
	{
		$id =  intval($_REQUEST['id']);
		$vo['licai_name'] = $GLOBALS['db']->getOne("SELECT `name` FROM ".DB_PREFIX."licai where  id = ".$id);
		if($vo['licai_name']==""){
			$this->error("请选择理财产品");
		}
		$vo['licai_id'] = $id;
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	public function insert()
	{
		B('FilterString');
		$data = M(MODULE_NAME)->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/add",array("id"=>$data['licai_id'])));
		
		
		$data['create_date'] = to_date(NOW_TIME);
		//开始验证有效性
		
		$list=M(MODULE_NAME)->add ($data);
		
		if (false !== $list) {
			
			save_log($list.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($list.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$list.L("INSERT_FAILED"));
		}
	}
}
?>