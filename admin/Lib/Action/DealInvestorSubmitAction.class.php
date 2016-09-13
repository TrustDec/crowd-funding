<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealInvestorSubmitAction extends CommonAction{
/**
 * 未审核项目列表
 */
	public function submit_index()
	{
		$map['type']=1;
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
		$model = D ('Deal');
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealInvestorCate")->findAll();
		$this->assign("cate_list",$cate_list);

		$this->display ();
	}
/**
 * 编辑
 */
	public function edit_investor() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M('Deal')->where($condition)->find();
		$type = $vo['type'];
		$this->assign("type",$type);
		if($vo['type'] == 4){
			//最大融资轮数
			$invest_phase='';
			$project_invest_phase='';//通过审核项目 的最大融资轮数
			$company_investment_case='';//融资经历最大融资轮数
			$project_invest_phase=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."deal where user_id = ".$vo['user_id']." and company_id =".$vo['company_id']." and is_effect=1 and is_delete=0 and type = 4");
			$company_investment_case=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."finance_company_investment_case where  company_id =".$vo['company_id']." and status =1");	
			$invest_phase=$project_invest_phase>$company_investment_case?$project_invest_phase:$company_investment_case;
			$this->assign("invest_phase",$invest_phase);
		}
	
		if($vo['user_id']==0)$vo['user_id']  = '';
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$vo['pay_end_time'] = $vo['pay_end_time']!=0?to_date($vo['pay_end_time']):'';
		$vo['business_create_time'] = $vo['business_create_time']!=0?to_date($vo['business_create_time']):'';
		$vo['history']=unserialize($vo['history']);
 		$history_num=$vo['history']?count($vo['history']):0;
  		$this->assign('history_num',$history_num);
		$vo['plan']=unserialize($vo['plan']);
 		$plan_num=$vo['plan']?count($vo['plan']):0;
   		$this->assign('plan_step_num',$plan_num);
		$vo['attach']=unserialize($vo['attach']);
  		$attach_num=$vo['attach']?count($vo['attach']):0;
		$this->assign('attach_num',$attach_num);
		$vo['stock']=unserialize($vo['stock']);
 		$stock_num=$vo['stock']?count($vo['stock']):0;
 		$this->assign('stock_num',$stock_num);

		$vo['unstock']=unserialize($vo['unstock']);
		$unstock_num=$vo['unstock']?count($vo['unstock']):0;
 		$this->assign('unstock_num',$unstock_num);
 		
 		//企业资质材料信息
 		$vo['audit_data']=unserialize($vo['audit_data']);
 		$audit_data=$vo['audit_data'];
 		$this->assign('audit_data',$audit_data);
 		
		$this->assign ( 'vo', $vo );
		$this->assign("action",'update_investor');
		$plan_html=$this->fetch("add_new_history");
 		$this->assign('history_html',$plan_html);
		
 		$this->assign('plan_num',1);
		$plan_html=$this->fetch("add_new_plan");
		$this->assign('plan_html',$plan_html);
		
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$cate_list = M("DealInvestorCate")->findAll();
		$cate_list = D("DealInvestorCate")->toNameFormatTree($cate_list);
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
		
		//合同
		$contract_list = M("Contract")->findAll();
		$contract_list = D("Contract")->toFormatTree($contract_list);
		$this->assign("contract_list",$contract_list);
 		//$deal_imgs
		$deal_imges_list=D("dealImage")->where("deal_id=".$id."")->findAll();
		$this->assign("deal_imgs",array_map('array_pop',$deal_imges_list));
		
		$this->display ();
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
/**
 * 更新
 */
	public function update_investor() {
		$now_time=get_gmtime();
		B('FilterString');
 		$data = M('Deal')->create();
			$log_info = M('Deal')->where("id=".intval($data['id']))->getField("name");
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$data['id']);
			//开始验证有效性
			$this->assign("jumpUrl",u(MODULE_NAME."/edit_investor",array("id"=>$data['id'])));
			if($deal_info['type'] ==4){
				$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_effect = 1 and is_delete = 0 and ( (end_time>".$now_time.") or (pay_end_time>".$now_time." and is_success=1)) and id!=".$data['id']." and company_id =".$deal_info['company_id']);
				if($data['is_effect']==1){
					if($deal_count)
					{
						$this->error("审核无法通过，一个公司只能有一个进行中融资项目！");
					}
				}
				
			}
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
			$this->deal_update(intval($data['id']));
			
	    	$history_info=deal_investor_info($data['history'],'history');
	   		if($history_info['status']){
				$data['history']=serialize(array_filter($history_info['data']));
			}else{
				$this->error($history_info['info']);
			}
	  		$stock_info=deal_investor_info($data['stock'],'stock');
			if($stock_info['status']){
				$data['stock']=serialize(array_filter($stock_info['data']));
			}else{
	 			$this->error($stock_info['info']);
			}
	 		$unstock_info=deal_investor_info($data['unstock'],'unstock');
			if($unstock_info['status']){
				$data['unstock']=serialize(array_filter($unstock_info['data']));
			}else{
				$this->error($unstock_info['info']);
			}
	 		$plan_info=deal_investor_info($data['plan'],'plan');
			if($plan_info['status']){
				$data['plan']=serialize(array_filter($plan_info['data']));
			}else{
				$this->error($plan_info['info']);
			}
	   		$attach_info=deal_investor_info($data['attach'],'attach');
	 		if($attach_info['status']){
				$data['attach']=serialize(array_filter($attach_info['data']));
			}else{
				$this->error($attach_info['info']);
			}
			//企业资质材料信息
			$data['audit_data']=serialize($data['audit_data']);
			if($data['end_time']>$data['pay_end_time']){
				$this->error("支付结束时间要大于项目结束时间");
			}elseif($data['begin_time']>$data['end_time']){
				$this->error("项目结束时间要大于项目开始时间");
			}
			
			
			$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
			$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
			$data['pay_end_time'] = trim($data['pay_end_time'])==''?0:to_timespan($data['pay_end_time']);
			
			$data['business_create_time'] = trim($data['business_create_time'])==''?0:to_timespan($data['business_create_time']);
		
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
 		if($data['is_effect']==2)
 		{
 			$data['is_edit'] =1;
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
			
			//插入图片
			D("dealImage")->where("deal_id = ".intval($data['id'])."")->delete();
			$this->images_add($data['id']);
			
			M("Deal")->where("id=".$data['id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			M("DealComment")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			//syn_deal($data['id']);
			//syn_deal_status($data['id']);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
/*
 * 彻底删除
*/	
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
/**
 * 子项目
*/	
	public function deal_item()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ("DealItem");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	}
/**
 * 添加子项目
*/	
	public function add_deal_item()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$Count = M('DealItem')->where('deal_id = '.$deal_id)->count();
		$this->assign("deal_info",$deal_info);
		$this->display();
	}
/**
 * 子项目写入数据库
*/	
	public function insert_deal_item() {
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealItem")->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add_deal_item",array("id"=>$data['deal_id'])));
		if(!check_empty($data['price'])&&$data['type']==0)
		{
			$this->error("请输入价格");
		}
		if($data['type'] ==2 && $data['lottery_measure'] <=0)
		{
			$this->error("请输入抽奖计量数");
		}
		if( $data['is_limit_user']==1 && $data['virtual_person'] > $data['limit_user'])
				$this->error("虚拟购买人数不能大于限购人数");	
				
		$deal=M("Deal")->where("id=".$data['deal_id'])->find();
		if($deal['lottery_draw_time'] >0 && $data['type'] ==2)
		{
			$this->error("项目幸运号已揭晓，不能增加抽奖子项目");
		}
		
		// 更新数据
		$list=M("DealItem")->add($data);
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['price']);	
		
		if (false !== $list) {
			//成功提示
			
			M("DealItemImage")->where("deal_item_id=".$data['id'])->delete();
			$imgs=array($_REQUEST['img0'],$_REQUEST['img1'],$_REQUEST['img2'],$_REQUEST['img3']);
			
			//$imgs = $_REQUEST['image'];
			foreach($imgs as $k=>$v)
			{
				if($v!='')
				{
					$img_data['deal_id'] = $data['deal_id'];
					$img_data['deal_item_id'] = $list;
					$img_data['image'] = $v;
					M("DealItemImage")->add($img_data);
				}
			}
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			save_log($log_info.L("INSERT_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
/**
 * 编辑子项目
*/	
	public function edit_deal_item()
	{
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("DealItem")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		//输出图片集
		$img_list = M("DealItemImage")->where("deal_item_id=".$vo['id'])->findAll();
		$imgs = array();
		foreach($img_list as $k=>$v)
		{
			$imgs[$k] = $v['image']; 
		}
		$this->assign("img_list",$imgs);
		
		$deal_info = M("Deal")->where("id=".intval($vo['deal_id'])."")->find();
		$this->assign ( 'deal_info', $deal_info );
		
		$this->display();
	}
/**
 * 更新子项目
*/	
	public function update_deal_item() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealItem")->create ();
		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit_deal_item",array("id"=>$data['id'])));
		
		$deal_item=M("DealItem")->getById(intval($data['id']));
		
		if(!$deal_item)
			$this->error("更新失败");
		
		if($deal_item['type'] ==2 && $deal_item['lottery_draw_time'] >0)
		{
			$this->error("子分类已抽奖，不能再编辑");
		}
			
		if(!check_empty($data['price']))
		{
			$this->error("请输入价格");
		}
		
		if($data['type'] ==2)
		{
			if($data['lottery_measure'] <=0)
			{
				$this->error("请输入抽奖计量数");
			}
			
			$deal=M("Deal")->where("id=".$data['deal_id'])->find();
			if($deal['lottery_draw_time'] >0)
			{
				$this->error("项目幸运号已揭晓，不能增加抽奖子项目");
			}
			
		}
		
		$data['virtual_person']=intval($data['virtual_person']);
		$data['limit_user']=intval($data['limit_user']);
		if( $data['type'] == 0 && $data['is_limit_user']==1 && $data['virtual_person'] > $data['limit_user'])
				$this->error("虚拟购买人数不能大于限购人数".$data['limit_user']);
		
		if( $data['is_limit_user']==1 && $deal_item['support_count'] >0 && ($data['virtual_person']+$deal_item['support_count']) > $data['limit_user'])
		{
			if($data['virtual_person'] >0)
				$err_info='限购人数小于"虚拟购买人数('.$data['virtual_person'].')+支持人数('.$deal_item['support_count'].')"';
			else
				$err_info='限购人数小于"支持人数('.$deal_item['support_count'].')"';
			$this->error($err_info);
		}		
		
		
	
		// 更新数据
		$this->deal_update(intval($data['deal_id']));
		$list=M("DealItem")->save($data);
		
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['price']);	
		if (false !== $list) {
			if($data['virtual_person']>0){
				
			}
			//成功提示
			//开始处理图片
			M("DealItemImage")->where("deal_item_id=".$data['id'])->delete();
			$imgs=array($_REQUEST['img0'],$_REQUEST['img1'],$_REQUEST['img2'],$_REQUEST['img3']);
			//$imgs = $_REQUEST['image'];
			foreach($imgs as $k=>$v)
			{
				if($v!='')
				{
					$img_data['deal_item_id'] = $data['id'];
					$img_data['deal_id'] = $data['deal_id'];
					$img_data['image'] = $v;
					M("DealItemImage")->add($img_data);
				}
			}
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['deal_id'])->setField("deal_info_cache","");
			//end 处理图片
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"));
		}
	}
/**
 * 删除子项目
*/	
	public function del_deal_item()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealItem")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['price']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				$list = M("DealItem")->where ( $condition )->delete();				
				if ($list!==false) {					
					M("Deal")->where("id=".$deal_id)->setField("deal_extra_cache","");
					syn_deal($deal_id);
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
	/**
 * 更新数据使用
 * @param 项目ID $deal_id
 */
	function deal_update($deal_id){
		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id");
 		$now_time=get_gmtime();
 		if(($deal['begin_time']<$now_time||$deal['end_time']<$now_time)&&($deal['invote_money']>0||$deal['virtual_price']>0||$deal['support_amount']>0)){
 			// $this->error("项目已经开始无法编辑");
		} 
	}
/**
 * 添加图片
 */	
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