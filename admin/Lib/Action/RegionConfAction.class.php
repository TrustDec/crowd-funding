<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class RegionConfAction extends CommonAction{
	public function index()
	{
		if(intval($_REQUEST['pid'])> 0){
			$map['pid'] = array("eq",intval($_REQUEST['pid']));
		}
		else
			$map['region_level'] = array("eq",2);
		
		//================================
		$pid=0;
		$cid=0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			foreach($region_lv2 as $k=>$v)
			{
				if($v['name'] == $_REQUEST['province'])
				{
					$region_lv2[$k]['selected'] = 1;
					$region_pid = $region_lv2[$k]['id'];
					$pid=$region_lv2[$k]['id'];
					break;
				}
			}
			
			$this->assign("region_lv2",$region_lv2);
			
			
			if($region_pid)
			{
				$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
				foreach($region_lv3 as $k=>$v)
				{
					if($v['name'] == $_REQUEST['city'])
					{
						$region_lv3[$k]['selected'] = 1;
						$cid=$region_lv3[$k]['id'];
						break;
					}
				}
				$this->assign("region_lv3",$region_lv3);
			} 
		//================================
		if($cid>0){
			$map['pid'] = array("eq",intval($pid));
			$map['id'] = array("eq",$cid);
			$map['region_level'] = array("eq",3);
		}else if($pid>0){
			$map['id'] = array("eq",$pid);
		}
		$this->assign("pid",intval($_REQUEST['pid']) > 0 ? intval($_REQUEST['pid']) : 0);
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
	
		$this->display ();
		return;
	}
	
	public function add()
	{
		$pid = intval($_REQUEST['pid']);
		if(!$pid){
			$pid=1;
		}
		$region_lv2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."region_conf where id=".$pid);  //二级地址
		$this->assign("region_lv2",$region_lv2);
		$this->assign("pid",$pid);
		$region_level = intval($region_lv2['region_level']) + 1;
		$this->assign("region_level",$region_level);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("RegionConf")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$this->assign(M("RegionConf")->findAll());
		$this->display ();
	}

	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("RegionConf")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("RegionConf")->where ( $condition )->delete();	
		
				if ($list!==false) {
					$this->updateRegionJS();
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
		$data = M("RegionConf")->create ();
		
		//开始验证有效性
		$this->assign("jumpUrl",u("RegionConf"."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("CITY_NAME_EMPTY_TIP"));
		}	
		if(!check_empty($data['py']))
		{
			$this->error("请输入拼音");
		}			
		// 更新数据
		$log_info = $data['name'];
		$list=M("RegionConf")->add($data);	
		if (false !== $list) {
			//成功提示
			$this->updateRegionJS();
			save_log($log_info.L("INSERT_SUCCESS"),1);
			clear_auto_cache("city_list_result");
			clear_auto_cache("deal_city_belone_ids");
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			$DBerr = M()->getDbError();
			save_log($log_info.L("INSERT_FAILED").$DBerr,0);
			$this->error(L("INSERT_FAILED").$DBerr);
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M("RegionConf")->create();

		$log_info = M("RegionConf")->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u("RegionConf"."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("请输入地区"));
		}	
		if(!check_empty($data['py']))
		{
			$this->error(L("请输入拼音"));
		}	
		
		// 更新数据
		$list=M("RegionConf")->save($data);
		if (false !== $list) {
			//成功提示
			$this->updateRegionJS();
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	private function updateRegionJS()
	{
		$jsStr = "var regionConf = ".$this->getRegionJS();
		$path = get_real_path()."public/region.js";
		@file_put_contents($path,$jsStr);
	}
	
	private function getRegionJS()
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("region_level = 2")->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
	private function getRegionChildJS($pid)
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("pid=".$pid)->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
	
}
?>