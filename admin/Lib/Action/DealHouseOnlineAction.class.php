<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealHouseOnlineAction extends CommonAction{
	public function house_index()
	{
		$now=get_gmtime();
		$map['type']=2;
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
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
		$cate_id=intval($_REQUEST['cate_id']);
		if($cate_id>0)
		{
			$cate = M("DealHouseCate")->where('id='.$cate_id)->find();
			if($cate['pid'] ==0)
			{
				$cate_ids = $GLOBALS['db']->getOne("select Group_concat(id) from ".DB_PREFIX."deal_house_cate where pid=".$cate_id." and is_effect=1");
				if($cate_ids)
				{
					$cate_array=explode(',',$cate_ids);
					$cate_array[]=$cate_id;
				}else
				{
					$cate_array[]=$cate_id;
				}
				$map['cate_id'] = array('in',$cate_array);
			}
			else
			{
				$map['cate_id'] = intval($_REQUEST['cate_id']);	
			}
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
		$model = M ('Deal');
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$cate_list = M("DealHouseCate")->where('is_effect = 1')->findAll();
		$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$this->assign("type",2);
		$this->display ();
	}

	public function add()
	{
		$type = intval($_REQUEST['type']);
		$this->assign("type",$type);
		
		
		$cate_list = M("DealHouseCate")->findAll();
		$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);

		$this->assign("houses_status_list",get_houses_status_list());
		
		$this->assign("cate_list",$cate_list);
		
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$this->assign("region_lv2",$region_lv2);
		//项目等级
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->assign("new_sort", M("DealHouseOnline")->max("sort")+1);
		$this->display();
	}
	
	public function edit() {
		
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
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M('Deal')->create ();

		$data['type']=intval($data['type']);
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add?type=".$data['type']));
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
		if($data['type']==2)
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
		
		
		$list=M('Deal')->add($data);

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
			//插入图片
			$this->images_add($list);
			
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
/**
 * 更新所有上线项目
 */
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
 * 排序
 */
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("Deal")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Deal")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
/**
 * 删除上线项目
 */	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M('Deal')->where($condition)->findAll();
				$num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$id." and order_status=3 and is_refund=0");			
				if(intval($num)>0){
					$this->error ("该项目已经有人成功付款,无法删除",$ajax);
				}
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];
				}
				if($info) $info = implode(",",$info);
				$list = M('Deal')->where ( $condition )->setField("is_delete",1);		
						
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
/*
	public function add_faq()
	{
		$this->display();
	}
*/	
/**
 * 产品子项目
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
 * 写入子项目
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
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
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
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
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
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
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
 * 发款筹款
 */
	public function pay_log()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		
		//拥金
		$deal_info['commission'] = $deal_info['support_amount'] + $deal_info['delivery_fee_amount'] - ($deal_info['pay_amount']+$deal_info['share_fee_amount']) ;
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	

			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			$this->assign("remain_money",$remain_money);
			$this->assign("paid_money",$paid_money);
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
			
			//分红情况
			$share_fee_total =  D ("DealOrder")->where("deal_id=".$deal_id."")->sum("share_fee");
			$share_fee_issue =  D ("DealOrder")->where("deal_id=".$deal_id." and share_status=1 ")->sum("share_fee");
			$this->assign("share_fee_total",$share_fee_total);
			$this->assign("share_fee_issue",$share_fee_issue);
		}
		$this->display();
	}
/**
 * 发款
 */	
	public function add_pay_log()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		
		//拥金
		$deal_info['commission'] = $deal_info['support_amount'] + $deal_info['delivery_fee_amount'] - ($deal_info['pay_amount']+$deal_info['share_fee_amount']) ;
		
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
	
			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			$this->assign("paid_money",$paid_money);
			$this->assign("remain_money",$remain_money);
		}
		
		$this->display();
	}
/**
 * 确认发款
 */	
	public function save_pay_log()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
	
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
		
			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			
			$money = floatval($_REQUEST['money']);
			$log_info = strim($_REQUEST['log_info']);
			
			if($deal_info['ips_bill_no']>0){
				if($remain_money>0){
					$url= APP_ROOT."/index.php?ctl=collocation&act=Transfer&pTransferType=1&deal_id=".$deal_id."&ref_data=".$loan_data['repay_start_time']; 
 	 				 app_redirect($url);
				}else{
					$this->error("筹款发放完成");
				}
				
			}
			if($money<=0||$money>$remain_money)
			{
				$this->error("金额出错");
			}
			else
			{
				if($deal_info['user_id']>0)
				{
					if($deal_info['ips_bill_no']>0){
						
					}else{
						require_once APP_ROOT_PATH."system/libs/user.php";
						if($log_info=="")$log_info = $deal_info['name']."项目筹款发放";
						modify_account(array("money"=>$money),$deal_info['user_id'],$log_info,array('money_type'=>22));
						$log['deal_id'] = $deal_info['id'];
						$log['money'] = $money;
						$log['create_time'] = get_gmtime();
						$log['log_info'] = $log_info;
						$model->add($log);
						save_log($log_info.$money,1);
						
						send_pay_success($log_info);
						
	 					$this->success("筹款发放成功");
					}
 				}
				else
				{
					$this->error("管理员创建项目，无需发放筹款");
				}
			}
			
		}
		else
		{
			$this->error("项目不存在");
		}
	}
/**
 * 删除发款
 */	
	public function del_pay_log()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealPayLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['money']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				$list = M("DealPayLog")->where ( $condition )->delete();				
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
/**
 * 项目日志
 */		
	public function deal_log()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	
			$model = D ("DealLog");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	}
/**
 * 删除项目日志
 */	
	public function del_deal_log()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		
				$condition_log = array ('log_id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id."的日志:".$info;
				$list = M("DealLog")->where ( $condition )->delete();	
							
				if ($list!==false) {		
					$GLOBALS['db']->query("update ".DB_PREFIX."deal set log_count = log_count - ".intval($list)." where id = ".$deal_id);			
					M("DealComment")->where($condition_log)->delete();
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
 * 批量退款
 */
	public function batch_refund()
	{
		$page = intval($_REQUEST['page']);

		$page=($page<=0)?1:$page;

		$page_size = 100;
		$deal_id = intval($_REQUEST['id']);
		
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$deal_info = M("Deal")->where("id=".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 0 and end_time <>0 and end_time <".get_gmtime())->find();
		if(!$deal_info)
		{
			$this->error("该项目不能批量退款");
		}
		else
		{
			require_once APP_ROOT_PATH."system/libs/user.php";
			$refund_order_list = M("DealOrder")->where("deal_id=".$deal_id." and is_refund = 0 and order_status = 3")->limit($limit)->findAll();
			foreach($refund_order_list as $k=>$v)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$v['id']);
				if($GLOBALS['db']->affected_rows()>0)
				{	
					modify_account(array("money"=>($v['online_pay']+$v['credit_pay'])),$v['user_id'],$v['deal_name']."退款");
					//退回积分
					if($v['score'] >0)
	 				{
						$log_info=$v['deal_name']."退款，退回".$v['score']."积分";
						modify_account(array("score"=>$v['score']),$v['user_id'],$log_info);
	 				}
					
					//扣掉购买时送的积分和信用值
					$sp_multiple=unserialize($v['sp_multiple']);
					if($v['score_multiple']>0)
					{
						$score=intval($v['total_price']*$sp_multiple['score_multiple']);
						$log_info=$v['deal_name']."退款，扣掉".$score."积分";
						modify_account(array("score"=>"-".$score),$v['user_id'],$log_info);
					}	
					if($sp_multiple['point_multiple']>0)
					{
						$point=intval($v['total_price']*$sp_multiple['point_multiple']);
						$log_info=$v['deal_name']."退款，扣掉".$point."信用值";
						modify_account(array("point"=>"-".$point),$v['user_id'],$log_info);
					}
					
					//抽奖订单 把抽奖号标为已退款
					if($v['type'] ==3)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=3 where order_id=".intval($v['id'])."");
					}
					
				}
			}
			
			//同步商品记录
			syn_deal($deal_info['id']);
			$deal_item_list=M("DealItem")->where("deal_id=".intval($deal_info['id']))->findAll();
			foreach($deal_item_list as $k=>$v)
			{									
				$deal_item['support_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
				$deal_item['support_amount'] = floatval($GLOBALS['db']->getOne("select sum(deal_price) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item", $deal_item, $mode = 'UPDATE', "id=".intval($v['id']), $querymode = 'SILENT');
			}

			$remain = M("DealOrder")->where("deal_id=".$deal_id." and is_refund = 0 and order_status = 3")->count();
			if($remain==0)
			{
				$jump_url = u("Deal/online_index");
				$this->assign("jumpUrl",$jump_url);
				M("Deal")->where("id=".$deal_info['id'])->setField("deal_extra_cache","");
				M("DealLog")->where("deal_id=".$deal_info['id'])->setField("deal_info_cache","");
				$this->success("批量退款成功");
			}
			else
			{
				$jump_url = u("Deal/batch_refund",array("id"=>$deal_id,"page"=>$page+1));
				$this->assign("jumpUrl",$jump_url);
				$this->success("批量退款中，请勿刷新页面，剩余".$remain."条订单未退款");
			}
			
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
 * 分红列表
 */
	function sharefee_list(){
		$deal_id=intval($_REQUEST['deal_id']);
		$deal_info = M("Deal")->getById($deal_id);
		$map['deal_id'] = $deal_id;
		$user_id=intval($_REQUEST['user_id']);
		$user_name=strim($_REQUEST['user_name']);
		$deal_item_id=intval($_REQUEST['deal_item_id']);
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		//搜索条件
		if(isset($_REQUEST['share_status']))
		{
			$share_status = intval($_REQUEST['share_status']);
			if($share_status == -1)
				unset($_REQUEST['share_status']);
			else
				$map['share_status']=$share_status;
		}
		else
			$share_status=-1;
		if($user_id>0)
			$map['user_id'] = $user_id;
		else
			unset($user_id);
			
		if($deal_item_id >0)
			$map['deal_item_id'] = $deal_item_id;
		if($user_name !='')
			$map['user_name'] = array('like','%'.$user_name.'%');
		
		$map['share_fee'] = array('gt',0);
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		//子项目列表
		if($deal_info)
		{
			$model = D ("DealOrder");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
			
			$deal_item_list=D('DealItem')->where("deal_id=".intval($deal_info['id']))->findAll();
		}
		
		//分红情况
		$share_fee_total =  D ("DealOrder")->where("deal_id=".$deal_id."")->sum("share_fee");
		$share_fee_issue =  D ("DealOrder")->where("deal_id=".$deal_id." and share_status=1 ")->sum("share_fee");
		$this->assign("share_fee_total",$share_fee_total);
		$this->assign("share_fee_issue",$share_fee_issue);
		
		$this->assign("share_status",$share_status);
		$this->assign("user_id",$user_id);
		$this->assign("user_name",$user_name);
		$this->assign("deal_item_id",$deal_item_id);
		$this->assign("deal_info",$deal_info);
		$this->assign("deal_item_list",$deal_item_list);
		$this->assign("back_pay_log",u("Deal/pay_log",array("id"=>$deal_info['id'])));
		$this->assign("back_deal",u("Deal/online_index"));
		$this->display();
	}
		//项目分红列表
	public function user_bonus()
	{
		$deal_id = intval($_REQUEST['deal_id']);
		$type = intval($_REQUEST['type']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	
			$map['type'] =$type;
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ("UserBonus");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	
	}
	//项目分期详细
	public function edit_user_bonus()
	{
		$id = intval($_REQUEST['id']);
		$condition['id'] = $id;		
		$vo = M(UserBonus)->where($condition)->find();
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );
		
		$user_bonus_list = M("UserBonusList")->where("user_bonus_id=".$vo['id'])->order("id asc")->findAll();
		$this->assign("user_bonus_list",$user_bonus_list);
		
		$this->display();
	}
	public function update_user_bonus()
	{
		B('FilterString');
		$data = M("UserBonus")->create();
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
	
		$list=M("UserBonus")->save ($data);
		if (false !== $list) {
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			$this->error(L("UPDATE_FAILED"));
		}
	}
	public function del_user_bonus()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		
				$condition1 = array ('user_bonus_id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("UserBonus")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['price']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				
				$list = M("UserBonus")->where ( $condition )->delete();		
				$list1 = M("UserBonusList")->where ( $condition1 )->delete();						
				if ($list!==false && $list1!==false) {					
					
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
	public function submit_user_bonus(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select 
					ub.id as 分红编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 分红年度,
					ub.number as 分红期数,
					ub.money as 分红金额,
					ub.average_monthly_returns as 平均月回报率,
					ub.average_annualized_return as 平均年回报率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0 
					and d.is_effect = 1 and d.is_success = 1 
					and (d.stock_type = 1 or (d.stock_type = 3 and  d.share_fee_descripe != '')) and  ub.status = 0 and ub.type = 0 and d.type = 1 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str);
	//print_r($voList);exit;
		$this->display();
	}
	
	
/**
 * 下载
 */
	public function download(){
		$url=strim($_REQUEST['file']);
		if($url){
			header("Location: ".$url);
			exit;
		}else{
			return false;
		}
	}
/**
 * 抽奖号列表
 */
	public function get_lottery()
	{
		$deal_id=intval($_REQUEST['id']);
		$user_name=strim($_REQUEST['user_name']);
		$lottery_sn=strim($_REQUEST['lottery_sn']);
	
		$deal_info=M("deal")->where("id=".$deal_id)->find();
		if(!$deal_info)
			$this->error("未找到项目");

		if($user_name !='')
		{
			$map['user_name']=array("like","%".$user_name."%");
		}
		if($lottery_sn !='')
		{
			$map['lottery_sn']=$lottery_sn;
		}
		
		$map['deal_id'] = $deal_id;
		$map['is_winner'] = array("lt",2);			
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = D ("dealOrderLottery");
		if (! empty ( $model )) {
			$this->_list ( $model, $map , 'is_winner');
		}
		
		$this->assign("user_name",$user_name);
		$this->assign("lottery_sn",$lottery_sn);
		$this->assign("deal_info",$deal_info);
		$this->display ();
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
/**
 * 支持列表
 */
	public function get_pay_list()
	{
		$deal_id=$_REQUEST['deal_id'];
		if($deal_id>0){
			$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id ");
			$this->assign("deal_info",$deal_info);
			$order_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where repay_make_time=0 and repay_time>0 and deal_id=$deal_id  ");
		}else{
			$this->error('项目ID不存在');
		}
		foreach($order_list as $k=>$v){
				$left_date=intval(app_conf("REPAY_MAKE"))?7:intval(app_conf("REPAY_MAKE"));
				$repay_make_date=$v['repay_time']+$left_date*24*3600;
				if($repay_make_date<=get_gmtime()){
 					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$v['id'] );
				}
 		}
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		$map['deal_id'] = $deal_id;
		$map['type'] = 7;
		//追加默认参数		
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		if($map['order_status']=='NULL'){
			unset($map['order_status']);
		}
		if(trim($_REQUEST['deal_name'])!='')
		{
			$map['deal_name'] = array('like','%'.trim($_REQUEST['deal_name']).'%');
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ('DealOrder');

		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}

		$offline_pay=M('Payment')->where('class_name = "Offlinepay"')->find();
		if($offline_pay)
		{
			$list = $this->get("list");
			foreach($list as $k=>$v)
			{
				if($v['payment_id']==$offline_pay['id'])
				{
					$list[$k]['online_pay']=0;
					$list[$k]['offlinepay_money']=format_price($v['total_price']);
				}else
				{
					$list[$k]['offlinepay_money']=0;
				}
			}
			$this->assign("list",$list);
		}
		
		$this->display ();
		return;
	}
/**
 * 修改状态
 */
	public function toogle_status()
	{
		
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$field = $_REQUEST['field'];
		$info = $id."_".$field;
		
		$c_is_effect = M('Deal')->where("id=".$id)->getField($field);  //当前状态
		
		
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		
			M('Deal')->where("id=".$id)->setField($field,$n_is_effect);	
		
		
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
/**
 * 导出电子表
 */
	public function export_csv($page = 1)
	{
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$where = " 1=1 ";
		//定义条件
		if(trim($_REQUEST['deal_id'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_id = ".intval($_REQUEST['deal_id']);
		}
		if(trim($_REQUEST['deal_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_name = ".intval($_REQUEST['deal_name']);
		}
		if(trim($_REQUEST['user_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.user_name = ".intval($_REQUEST['user_name']);
		}
		if(trim($_REQUEST['type'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.type = ".intval($_REQUEST['type']);
		}
		$list = M("DealOrder")
				->where($where)
				->field(DB_PREFIX.'deal_order.*')
				->limit($limit)->findAll();
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'user_name'=>'""', 'deal_name'=>'""', 'total_price'=>'""','zip'=>'""','mobile'=>'""','province'=>'""','consignee'=>'""','order_status'=>'""','id'=>'""','create_time'=>'""','pay_time'=>'""','city'=>'""','address'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","参与者,项目名称,应付总额,邮编,手机,配送地址,收货人,支付状态,订单号,下单时间,支付时间");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['deal_name'] = '"' . iconv('utf-8','gbk',$v['deal_name']) . '"';
				$order_value['total_price'] = '"' . iconv('utf-8','gbk',$v['total_price']) . '"';
				$order_value['zip'] = '"' . iconv('utf-8','gbk',$v['zip']) . '"';
				$order_value['mobile'] = '"' . iconv('utf-8','gbk',$v['mobile']) . '"';
				$order_value['province'] = '"' . iconv('utf-8','gbk',$v['province']) . '"'. iconv('utf-8','gbk',$v['city']) . iconv('utf-8','gbk',$v['address']);
				$order_value['consignee'] = '"' . iconv('utf-8','gbk',$v['consignee']). '"' ;
				if($v['order_status']==0){
					$v['order_status']='未支付';
				}elseif ($v['order_status']==1){
					$v['order_status']='已支付(过期)';
				}elseif ($v['order_status']==2){
					$v['order_status']='已支付(无库存)';
				}elseif ($v['order_status']==3){
					$v['order_status']='支付成功';
				}
				$order_value['order_status'] = '"' . iconv('utf-8','gbk',$v['order_status']). '"' ;
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']). '"' ;
				$order_value['create_time'] = '"' . iconv('utf-8','gbk',to_date($v['create_time'])). '"' ;
				$order_value['pay_time'] = '"' . iconv('utf-8','gbk',to_date($v['pay_time'])). '"' ;
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=order_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
	public function view()
	{
		$order_info = M("DealOrder")->getById(intval($_REQUEST['id']));
		if(!$order_info)$this->error("没有该项目的支持");
		
		
		$payment_notice_list = M("PaymentNotice")->where("order_id=".$order_info['id']." and is_paid = 1")->findAll();
		$this->assign("payment_notice_list",$payment_notice_list);
		
		//抽奖订单
		if($order_info['type'] ==3)
		{
			$lottery_return=get_order_lottery($order_info['id']);
			$order_info['lottery_list']=$lottery_return['lottery_list'];
			$order_info['lottery_luckyer_list']=$lottery_return['lottery_luckyer_list'];
		}
		
		$offline_pay=M('Payment')->where('class_name = "Offlinepay"')->find();
		if($offline_pay && $order_info['payment_id'] == $offline_pay['id'])
		{
			
			$order_info['offlinepay_money']=format_price($order_info['total_price']);
			$order_info['online_pay']=0;
		}
		else
		{
			$order_info['offlinepay_money']=0;
		}
		$deal_list = M("Deal")->getById($order_info['deal_id']);
		$this->assign("order_info",$order_info);
		$this->assign("deal_list",$deal_list);
		$this->assign("offline_pay",$offline_pay);
		$this->assign("back_list",u("DealHouseOnline/get_pay_list",array("deal_id"=>$order_info['deal_id'])));		
		$this->display();
	}
	public function refund()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->getById($id);
		if($order_info)
		{
			$count_pay_log = M("DealPayLog")->where("deal_id=".intval($order_info['deal_id']))->count();
			if($count_pay_log >0)
				$this->error("筹款已发，不能退款");
			
			//已是抽奖订单，不能删除
			if($order_info['type'] ==3 && $order_info['is_winner']==1)
			{
				$this->error("本订单已被抽为幸运订单了，不能退款");
			}
				
			if($order_info['is_refund']==0 && $order_info['order_status'] ==3)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$id." and is_refund = 0 and order_status=3");
				if($GLOBALS['db']->affected_rows()>0)
				{
					require_once APP_ROOT_PATH."system/libs/user.php";									
					modify_account(array("money"=>($order_info['online_pay']+$order_info['credit_pay'])),$order_info['user_id'],$order_info['deal_name']."退款");
					//退回积分
					if($order_info['score'] >0)
	 				{
						$log_info=$order_info['deal_name']."退款，退回".$order_info['score']."积分";
						modify_account(array("score"=>$order_info['score']),$order_info['user_id'],$log_info);
	 				}
					
					//扣掉购买时送的积分和信用值
					$sp_multiple=unserialize($order_info['sp_multiple']);
					if($sp_multiple['score_multiple']>0)
					{
						$score=intval($order_info['total_price']*$sp_multiple['score_multiple']);
						$log_info=$order_info['deal_name']."退款，扣掉".$score."积分";
						modify_account(array("score"=>"-".$score),$order_info['user_id'],$log_info);
					}	
					if($sp_multiple['point_multiple']>0)
					{
						$point=intval($order_info['total_price']*$sp_multiple['point_multiple']);
						$log_info=$order_info['deal_name']."退款，扣掉".$point."信用值";
						modify_account(array("point"=>"-".$point),$order_info['user_id'],$log_info);
					}
					
					//抽奖订单 把抽奖号标为已退款
					if($order_info['type'] ==3)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=3 where order_id=".$id."");
					}
					
					syn_deal($order_info['deal_id']);
					
					$deal_item['support_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$order_info['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($order_info['deal_item_id'])));
					$deal_item['support_amount'] = floatval($GLOBALS['db']->getOne("select sum(deal_price) from ".DB_PREFIX."deal_order where deal_id = ".$order_info['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($order_info['deal_item_id'])));
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item", $deal_item, $mode = 'UPDATE', "id=".intval($order_info['deal_item_id']), $querymode = 'SILENT');	
				}
				$this->success("成功退款到会员余额");
			}elseif($order_info['is_refund']==0 && $order_info['order_status'] ==0)
			{
				$this->error("订单未付款");
			}
			else
			{
				$this->error("已经退款");
			}
		}
		else
		{
			$this->error("没有该项目的支持");
		}
	}
	
	public function incharge()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->getById($id);
		if($order_info)
		{
			if($order_info['order_status']==0)
			{
				if($order_info['payment_id'] >0)
				{
					$payment_info = M("Payment")->getById($order_info['payment_id']);
				}
				
				$result = pay_order($order_info['id']);				
				$money = $result['money'];
				$payment_notice['create_time'] = get_gmtime();
				$payment_notice['user_id'] = $order_info['user_id'];
				$payment_notice['money'] = $money;
				$payment_notice['bank_id'] = "";
				$payment_notice['order_id'] = $order_info['id'];
				$payment_notice['memo'] = "管理员收款";
				$payment_notice['deal_id'] = $order_info['deal_id'];
				$payment_notice['deal_item_id'] = $order_info['deal_item_id'];
				$payment_notice['deal_name'] = $order_info['deal_name'];
				
				if($payment_info['class_name'] =='Offlinepay')//线下支持
				{
					$payment_notice['payment_id'] = $order_info['payment_id'];
				}else
				{
					$payment_notice['payment_id'] = 0;
				}
				
				do{
					$payment_notice['notice_sn'] = to_date(get_gmtime(),"Ymd").rand(100,999);
					$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
					$notice_id = $GLOBALS['db']->insert_id();
				}while($notice_id==0);
				
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice['notice_sn'],"");	
				$this->success("收款完成");
			}
			else
			{
				$this->error("已经付过款");
			}
		}
		else
		{
			$this->error("没有该项目的支持");
		}
	}
	
	//导出电子表
	public function export_order_csv($page = 1)
	{
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$where = " 1=1 ";
		//定义条件
		if(trim($_REQUEST['deal_id'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_id = ".intval($_REQUEST['deal_id']);
		}
		if(trim($_REQUEST['deal_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_name = ".intval($_REQUEST['deal_name']);
		}
		if(trim($_REQUEST['user_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.user_name = ".intval($_REQUEST['user_name']);
		}
		if(trim($_REQUEST['type'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.type = ".intval($_REQUEST['type']);
		}
		$list = M("DealOrder")
				->where($where)
				->field(DB_PREFIX.'deal_order.*')
				->limit($limit)->findAll();
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'user_name'=>'""', 'deal_name'=>'""', 'total_price'=>'""','zip'=>'""','mobile'=>'""','province'=>'""','consignee'=>'""','order_status'=>'""','id'=>'""','create_time'=>'""','pay_time'=>'""','city'=>'""','address'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","参与者,项目名称,应付总额,邮编,手机,配送地址,收货人,支付状态,订单号,下单时间,支付时间");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['deal_name'] = '"' . iconv('utf-8','gbk',$v['deal_name']) . '"';
				$order_value['total_price'] = '"' . iconv('utf-8','gbk',$v['total_price']) . '"';
				$order_value['zip'] = '"' . iconv('utf-8','gbk',$v['zip']) . '"';
				$order_value['mobile'] = '"' . iconv('utf-8','gbk',$v['mobile']) . '"';
				$order_value['province'] = '"' . iconv('utf-8','gbk',$v['province']) . '"'. iconv('utf-8','gbk',$v['city']) . iconv('utf-8','gbk',$v['address']);
				$order_value['consignee'] = '"' . iconv('utf-8','gbk',$v['consignee']). '"' ;
				if($v['order_status']==0){
					$v['order_status']='未支付';
				}elseif ($v['order_status']==1){
					$v['order_status']='已支付(过期)';
				}elseif ($v['order_status']==2){
					$v['order_status']='已支付(无库存)';
				}elseif ($v['order_status']==3){
					$v['order_status']='支付成功';
				}
				$order_value['order_status'] = '"' . iconv('utf-8','gbk',$v['order_status']). '"' ;
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']). '"' ;
				$order_value['create_time'] = '"' . iconv('utf-8','gbk',to_date($v['create_time'])). '"' ;
				$order_value['pay_time'] = '"' . iconv('utf-8','gbk',to_date($v['pay_time'])). '"' ;
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=order_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
}
?>