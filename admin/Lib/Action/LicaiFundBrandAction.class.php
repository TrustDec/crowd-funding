<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

class LicaiFundBrandAction extends CommonAction{
	public function index()
	{	
		$condition = " ";
		if(strim($_REQUEST["p_name"])!="")
		{
			$condition .= " and name like '%".strim($_REQUEST["p_name"])."%'";
		}
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = strim($_REQUEST ['_order']);
		}
		else
		{
			$order = "id";
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
		
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		
		$page_size = 20;
		
		$limit = (($page-1)*$page_size).",".$page_size;
		$result = array();
		$result['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."licai_fund_brand  where 1=1 ".$condition);

		if($result['count'] > 0){
			
			$result['list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."licai_fund_brand where 1=1 ".$condition." order by ". str_replace("_format","",$order)." ".$sort." limit ".$limit);
		}
		
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		
		$this->assign ( 'sort', $sort );
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		
		foreach($result['list']  as $k => $v)
		{
			$result['list'][$k]["status_format"] = $v['status'] == 1 ?"有效":"无效";
		}
		
		$this->assign("list",$result['list']);
		
		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$this->assign('page',$p);
		$this->assign('main_title',"基金品牌列表");
		$this->display ();
	}
	
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		$vo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."licai_fund_brand where  id = ".$id);
		$this->assign ( 'vo', $vo );
		
		$this->display ();
	}
	public function update()
	{
		B('FilterString');
		$data = M(MODULE_NAME)->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
		
		//开始验证有效性
		
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
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
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
		
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
	public function add()
	{
		$this->display ();
	}
	public function insert()
	{
		B('FilterString');
		$data = M(MODULE_NAME)->create();
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		
		$log_info = $data["name"];
		
		//开始验证有效性
		
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
		$list=M(MODULE_NAME)->add ($data);
		
		if (false !== $list) {
			
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"),0,$log_info.L("INSERT_FAILED"));
		}
	}
}
?>