<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealHouseDeleteAction extends CommonAction{
	
	public function delete_house_index(){
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		

		$map['is_delete'] = 1;
		$map['type'] = 2;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = M ('Deal');
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealHouseCate")->findAll();
		$this->assign("cate_list",$cate_list);
		
		$this->assign("action_name",'delete_house_index');
		
		$this->display ("delete_index");
	}
	
	public function edit() {
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M('Deal')->where($condition)->find();
		if($vo['user_id']==0)$vo['user_id']  = '';
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
 		$this->assign ( 'vo', $vo );
		$this->assign ( 'aa', 10 );
		if($vo['type'] ==2)
		{
			$cate_list = M("DealHouseCate")->findAll();
			$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);
			
			$this->assign("houses_status_list",get_houses_status_list());
		}
		else
		{
			$cate_list = M("DealCate")->findAll();
			$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		}
		
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
		
		//$deal_imgs
		$deal_imges_list=D("dealImage")->where("deal_id=".$id."")->findAll();
		$this->assign("deal_imgs",array_map('array_pop',$deal_imges_list));
		
		$this->display ();
	}

	public function update() {
		B('FilterString');
		$data = M('Deal')->create();
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$data['id']);
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		
 			$log_info = M('Deal')->where("id=".intval($data['id']))->getField("name");
			
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
			if(intval($deal_info['type'])==2)
			{
				$publisher_user=M("User")->where("id=".intval($data['user_id'])."")->find();
				if($publisher_user['is_investor'] !=2 || $publisher_user['investor_status'] !=1 )
					$this->error("房产众筹发启人要是企业型会员");
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
		$list=M('Deal')->save ($data);
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
			
			//插入图片
			D("dealImage")->where("deal_id = ".intval($data['id'])."")->delete();
			$this->images_add($data['id']);
			
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
	public function update_all(){
		$re=$GLOBALS['db']->getAll("select * from  ".DB_PREFIX."deal where  is_effect = 1 and is_delete=0 ");
		foreach($re as $k=>$v){
			syn_deal($v['id']);
			syn_deal_status($v['id']);
		}
		syn_user_level();
		ajax_return(array('status'=>1));
	}
	
	public function restore() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M('Deal')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('Deal')->where ( $condition )->setField("is_delete",0);				
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
				$rel_data = M('Deal')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('Deal')->where ( $condition )->delete();				
				if ($list!==false) {					
					M("DealFaq")->where($link_condition)->delete();
					M("DealComment")->where($link_condition)->delete();
					M("DealFocusLog")->where($link_condition)->delete();
					M("DealImage")->where($link_condition)->delete();
					M("DealItem")->where($link_condition)->delete();
					M("DealItemImage")->where($link_condition)->delete();
					M("DealOrder")->where($link_condition)->delete();
					M("DealOrderLottery")->where($link_condition)->delete();
					M("DealPayLog")->where($link_condition)->delete();
					M("DealSupportLog")->where($link_condition)->delete();
					M("DealVisitLog")->where($link_condition)->delete();
					M("DealLog")->where($link_condition)->delete();
					M("UserDealNotify")->where($link_condition)->delete();
					M("DealNotify")->where($link_condition)->delete();
					M("InvestmentList")->where($link_condition)->delete();
					
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
	
	function deal_update($deal_id){
		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id");
 		$now_time=get_gmtime();
 		if(($deal['begin_time']<$now_time||$deal['end_time']<$now_time)&&($deal['invote_money']>0||$deal['virtual_price']>0||$deal['support_amount']>0)){
 			// $this->error("项目已经开始无法编辑");
		} 
	}
	public function images_add($deal_id)
	{
		$imgs=array($_REQUEST['image1'],$_REQUEST['image2'],$_REQUEST['image3'],$_REQUEST['image4'],$_REQUEST['image5']);
		foreach($imgs as $k=>$v){
			if($v != '')
			{
				$img_data['deal_id']=$deal_id;
				$img_data['image']=$v;
				M('deal_image')->add($img_data);
			}
		}
	}
}
?>