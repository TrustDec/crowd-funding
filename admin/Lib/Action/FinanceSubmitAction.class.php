<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class FinanceSubmitAction extends CommonAction{

	public function submit_index()
	{
		if(intval($_REQUEST['id'])!='')
		{
			$map['id'] = intval($_REQUEST['id']);
		}
		if(trim($_REQUEST['company_name'])!='')
		{
			$map['company_name'] = array('like','%'.trim($_REQUEST['company_name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$map['status'] = array("in",array(0,2));
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D (FinanceCompany);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
	
		$this->display ();
	}
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(FinanceCompany)->where($condition)->find();
	
		$vo['company_create_time'] = $vo['company_create_time']!=0?to_date($vo['company_create_time']):'';
		//图片介绍信息
		$vo['company_introduce_image']=unserialize($vo['company_introduce_image']);
 		$this->assign('company_introduce_image',$vo['company_introduce_image']);
 		//子产品介绍
 		$finance_company_sub_product = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_sub_product where company_id = ".$id." order by id asc");
		$this->assign('finance_company_sub_product',$finance_company_sub_product);
		
		//创始团队
		$finance_company_team = $GLOBALS['db']->getAll("select fc.*,fc.status as team_status,fc.id as invite_id from ".DB_PREFIX."finance_company_team as fc  where fc.company_id = ".$id." and fc.type = 0 order by fc.id asc");
		foreach($finance_company_team as $k=>$v)
		{
			$finance_company_team[$k]['job_start_time']=to_date($v['job_start_time'],'Y-m');	
			if($v['job_start_time']>0){
				$finance_company_team[$k]['job_end_time']=to_date($v['job_end_time'],'Y-m');	
			}
		}
		$this->assign('finance_company_team',$finance_company_team);
		//投资案例
		$finance_company_investment_case = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id = ".$id." and type = 0 order by id asc");
		foreach($finance_company_investment_case as $k=>$v)
		{
			$finance_company_investment_case[$k]['invest_time']=to_date($v['invest_time'],'Y-m-d');	
		}
		$this->assign('finance_company_investment_case',$finance_company_investment_case);
	
		//融资经历
		$finance_company_investment_invite = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."finance_company_investment_case where company_id = ".$id." and type = 1 order by id asc");

		foreach($finance_company_investment_invite as $k=>$v)
		{
			$finance_company_investment_invite[$k]['invest_time']=to_date($v['invest_time'],'Y-m-d');
			$finance_company_investment_invite[$k]['invest_subject']=unserialize($v['invest_subject']);
		}
		$this->assign('finance_company_investment_invite',$finance_company_investment_invite);
		
		//过往投资方
		$finance_company_investment_past = $GLOBALS['db']->getAll("select fc.*,u.user_name as user_name,fc.status as team_status,fc.id as invites_id from ".DB_PREFIX."finance_company_team as fc left join ".DB_PREFIX."user as u on fc.user_id = u.id  where fc.company_id = ".$id." and fc.type = 3 order by fc.id asc");
		$this->assign('finance_company_investment_past',$finance_company_investment_past);
		
		//团队成员
		$finance_company_group = $GLOBALS['db']->getAll("select fc.*,fc.status as team_status,fc.id as invite_id from ".DB_PREFIX."finance_company_team as fc  where fc.company_id = ".$id."  and fc.type = 1 order by fc.id asc");
		foreach($finance_company_group as $k=>$v)
		{
			$finance_company_group[$k]['job_start_time']=to_date($v['job_start_time'],'Y-m');	
			if($v['job_start_time']>0){
				$finance_company_group[$k]['job_end_time']=to_date($v['job_end_time'],'Y-m');	
			}
		}
		$this->assign('finance_company_group',$finance_company_group);
		
		//过往成员
		$finance_company_past = $GLOBALS['db']->getAll("select fc.*,fc.status as team_status,fc.id as invite_id from ".DB_PREFIX."finance_company_team as fc  where fc.company_id = ".$id."  and fc.type = 2  order by fc.id asc");
		foreach($finance_company_past as $k=>$v)
		{
			$finance_company_past[$k]['job_start_time']=to_date($v['job_start_time'],'Y-m');	
			if($v['job_start_time']>0){
				$finance_company_past[$k]['job_end_time']=to_date($v['job_end_time'],'Y-m');	
			}
		}
		$this->assign('finance_company_past',$finance_company_past);
		
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->assign ( 'vo', $vo );
		$this->assign("action",'update');
	
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
		
		$this->display ();
	}
	public function update()
	{
		
	 	B('FilterString');
		$data = M(FinanceCompany)->create();
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."finance_company where id=".$data['id']);		
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
		$log_info = M(FinanceCompany)->where("id=".intval($data['id']))->getField("company_name");

 		//开始验证有效性
		
		if(!check_empty($data['company_name']))
		{
			$this->error("请输入公司简称");
		}	
		if(intval($data['cate_id'])==0)
		{
			$this->error("请选择公司领域");
		}
		
		$data['company_create_time'] = trim($data['company_create_time'])==''?0:to_timespan($data['company_create_time']);			
 		if($data['status']==2&&$data['user_id']>0)
		{
			$data['is_edit'] = 1;
		}
		if($data['status']!=2&&$data['user_id']>0)
		{
			$data['is_edit'] = 0;
		}
		$team_status =array();
		$team_status=$_REQUEST['team_status'];
	//	print_r($team_status);exit;
		foreach($team_status as $k=>$v)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."finance_company_team set status = ".intval($v['status'])." ,update_time=".get_gmtime()." where id = ".intval($v['id']));		
		}
		$investment_case =array();
		$investment_case=$_REQUEST['investment_case'];
		foreach($investment_case as $k=>$v)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."finance_company_investment_case set status = ".intval($v['status'])." where id = ".intval($v['id']));		
		}
		$list=M(FinanceCompany)->save($data);
		if (false !== $list) {
			save_log("公司简称".$log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log("公司简称".$log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function delete()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("FinanceCompany")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['id'];
					
				}
				$info = "公司ID".$deal_id.":".$info;
				$list = M("FinanceCompany")->where ( $condition )->delete();				
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
		$log_info = M("FinanceCompany")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("FinanceCompany")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
}
?>