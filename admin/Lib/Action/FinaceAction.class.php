<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class FinanceAction extends CommonAction{
	public function online_index()
	{	
 		$now=get_gmtime();
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['time_status'])==1)
		{
			$map['_string'] = '(begin_time > '.get_gmtime().')';			
		}
		
		if(intval($_REQUEST['time_status'])==2)
		{
			$map['_string'] = "(begin_time < '".get_gmtime()."') and ((end_time > '".get_gmtime()."') or (end_time = 0))";
		}
		
		if(intval($_REQUEST['time_status'])==3)
		{
			$map['_string'] = '(end_time < '.get_gmtime().') and (end_time <> 0)';	
		}
		if($_REQUEST['type']=='NULL'){
			unset($_REQUEST['type']);
		}
		if($_REQUEST['type']!=NULL){
			$map['type']=intval($_REQUEST['type']);
		}
		
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		$map['is_effect'] = 1;		
		$map['is_delete'] = 0;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$finance_list = M("Finance")->findAll();
		$this->assign("finance_list",$finance_list);
		$this->display ();
	}
	
	public function submit_index()
	{
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		if($_REQUEST['type']=='NULL'){
			unset($_REQUEST['type']);
		}
		
		if($_REQUEST['type']!=NULL){
 			$map['type']=intval($_REQUEST['type']);
		}
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		$map['is_effect'] = array("in",array(0,2));
		$map['is_delete'] = 0;		

		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$finance_list = M("FinanceCompany")->findAll();
		$this->assign("finance_list",$finance_list);
		$this->display ();
	}
	
	public function delete_index()
	{
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		

		$map['is_delete'] = 1;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$finance_list = M("FinanceCompany")->findAll();
		$this->assign("finance_list",$finance_list);
		$this->display ();
	}
	
	public function add()
	{
		
		$cate_list = M("DealCate")->findAll();
		$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$this->assign("region_lv2",$region_lv2);
		//项目等级
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->assign("new_sort", M("Deal")->max("sort")+1);
		$this->display();
	}
	 
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		if($vo['user_id']==0)$vo['user_id']  = '';
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
 		$this->assign ( 'vo', $vo );
		$this->assign ( 'aa', 10 );
		$cate_list = M("DealCate")->findAll();
		$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $vo['province'])
			{
				$region_lv2[$k]['selected'] = 1;
				$region_pid = $region_lv2[$k]['id'];
				break;
			}
		}
		$this->assign("region_lv2",$region_lv2);
		
		
		if($region_pid>0)
		{
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['name'] == $vo['city'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$this->assign("region_lv3",$region_lv3);
		}
		
		$qa_list = M("DealFaq")->where("deal_id=".$vo['id'])->order("sort asc")->findAll();
		$this->assign("faq_list",$qa_list);
		
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->display ();
	}
	
	 
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
		if(intval($data['cate_id'])==0)
		{
			$this->error("请选择分类");
		}	
		if(floatval($data['limit_price'])<=0){
			$this->error("目标金额要大于0");
		}
			
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
		if($data['begin_time']>$data['end_time']){
			$this->error("开始时间不能大于结束 时间");
		}
 		$data['create_time'] = get_gmtime();
		$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
		if(!$data['user_name'] )$data['user_name'] ="";
		if($data['vedio']!="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
		// 更新数据
		$log_info = $data['name'];
		
		if(!$data['ips_bill_no'])
		{
			$data['ips_bill_no']='';
		}
		
		$list=M(MODULE_NAME)->add($data);

		if (false !== $list) {
			//成功提示
			
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $list;
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			if($_REQUEST['ips_bill_no']>0){
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_bill_no=$list where id=".$list);
			}
			
			syn_deal($list);
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create();
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$data['id']);
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
 			$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
			
			$this->deal_update(intval($data['id']));
	 		//开始验证有效性
			
			if(!check_empty($data['name']))
			{
				$this->error("请输入名称");
			}	
			if(intval($data['cate_id'])==0)
			{
				$this->error("请选择分类");
			}
			if(floatval($data['limit_price'])<=0){
				$this->error("目标金额要大于0");
			}
			
			$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
			$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
			if($data['begin_time']>$data['end_time']){
				$this->error("开始时间不能大于结束 时间");
			}
			
			$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
			if(!$data['user_name'] )$data['user_name'] ="";
			if($data['vedio']!="")
			{
				$data['source_vedio'] = $data['vedio'];
			}
			else
			{
				$data['source_vedio'] = "";
			}
		if($_REQUEST['ips_bill_no']>0){
			$data['ips_bill_no'] = intval($data['id']);
 		}else{
 			$data['ips_bill_no'] = '';
 		}
 		if($data['is_effect']==2&&$data['user_id']>0)
		{
			$data['is_edit'] = 1;
		}
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			if($deal_info['is_effect']!=$data['is_effect']){
				if($data['is_effect']==1){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_SUCCESS));
				}elseif($data['is_effect']==2){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_FAIL));
				}
			}
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			//成功提示			
			M("DealFaq")->where("deal_id=".$data['id'])->delete();
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $data['id'];
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			M("Deal")->where("id=".$data['id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			M("DealComment")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			syn_deal($data['id']);
			syn_deal_status($data['id']);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
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
		$log_info = M("FinanceCompany")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("FinanceCompany")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->setField("is_delete",1);		
						
				if ($list!==false) {
					foreach($rel_data as $data)
					{						
						$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
						M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);						
					}
					save_log($info."成功移到回收站",1);
					$this->success ("成功移到回收站",$ajax);
				} else {
					save_log($info."移到回收站出错",0);					
					$this->error ("移到回收站出错",$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function restore() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->setField("is_delete",0);				
				if ($list!==false) {
					save_log($info."恢复成功",1);
					$this->success ("恢复成功",$ajax);
				} else {
					save_log($info."恢复出错",0);
					$this->error ("恢复出错",$ajax);
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
				$link_condition = array ('deal_id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
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
	
	
}
?>