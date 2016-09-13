<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class projectModule
{
	public function index()
	{
		$GLOBALS['tmpl']->display("project_index.html");
	}
	public function add_selfless(){
		$this->add(3);
	}
	public function add_house(){
		if(app_conf("IS_HOUSE") ==1	)
		{
			$this->add(2);
		}
		else
		{
			showErr("房产众筹已关闭",0);
		}
		
	}
	public function add($input_type=0)
	{	
         require APP_ROOT_PATH.'system/utils/tree.php';
                
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		$is_wap = 1;
		check_tg($is_wap);
			
		$GLOBALS['tmpl']->assign("page_title","发起项目");
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
	
		if($input_type ==1)
		{
			$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_investor_cate order by sort asc");
		}elseif($input_type ==2)
		{
			$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_house_cate order by sort asc");
			$GLOBALS['tmpl']->assign("houses_status_list",get_houses_status_list());
		}elseif($input_type ==3)
		{
			$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_selfless_cate order by sort asc");
		}
		else
		{
			$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");		
		}
		$tree=new tree();
		$cate_list=$tree->toFormatTree($cate_list_str);
		$GLOBALS['tmpl']->assign("cate_list",$cate_list);
		
		$deal_image =  es_session::get("deal_image");
		$GLOBALS['tmpl']->assign("deal_image",$deal_image);
		
		ini_get("post_max_size");

		$GLOBALS['tmpl']->assign("type",$input_type);
		$GLOBALS['tmpl']->display("project_add.html");
	}
	
	public function edit()
	{			
		require APP_ROOT_PATH.'system/utils/tree.php';
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
		if($deal_item)
		{
			$GLOBALS['tmpl']->assign("page_title",$deal_item['name']);
			
			
			$region_pid = 0;
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			foreach($region_lv2 as $k=>$v)
			{
				if($v['name'] == $deal_item['province'])
				{
					$region_lv2[$k]['selected'] = 1;
					$region_pid = $region_lv2[$k]['id'];
					break;
				}
			}
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			
			
			if($region_pid>0)
			{
				$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
				foreach($region_lv3 as $k=>$v)
				{
					if($v['name'] == $deal_item['city'])
					{
						$region_lv3[$k]['selected'] = 1;
						break;
					}
				}
				$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
			}
			
			$deal_item['faq_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_faq where deal_id = ".$deal_item['id']." order by sort asc");
			
			if($deal_item['type'] ==2)
			{	
				$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_house_cate order by sort asc");
				$GLOBALS['tmpl']->assign("houses_status_list",get_houses_status_list());
			}
			else
			{
				$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
			}		
			require_once APP_ROOT_PATH."system/utils/tree.php";
			$tree=new tree();
			$cate_list=$tree->toFormatTree($cate_list_str);
			$GLOBALS['tmpl']->assign("cate_list",$cate_list);
			
			$deal_images=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_image where deal_id=".$id."");
			$GLOBALS['tmpl']->assign("deal_images",$deal_images);
			
			$GLOBALS['tmpl']->assign("deal_item",$deal_item);
			
			$GLOBALS['tmpl']->display("project_edit.html");
		}	
		else
		{
			app_redirect_preview();
		}		
		
	}
	
	public function edit_investor(){
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
	}
	
	public function save()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!check_ipop_limit(get_client_ip(),"project_save",5))
		showErr("提交太频繁",$ajax,"");	
			
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		
		$is_wap = 1;
		check_tg($is_wap);
		
		$id =  intval($_REQUEST['id']);
		$type=intval($_REQUEST['type']);
		$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$id and user_id=".$GLOBALS['user_info']['id']);
		if(!$item&&$id>0){
			showErr("项目不存在",$ajax,"");
		}
		if( $item['type'] ==2 || $type ==2){
			//房产众筹
			if(!app_conf("IS_HOUSE"))
				showErr("房产众筹已关闭",$ajax,"");
				
			if($GLOBALS['user_info']['investor_status'] !=1 ||  $GLOBALS['user_info']['is_investor'] !=2)
				showErr("企业型会员才能发布房产众筹",$ajax,"");
		}
		$is_edit = $item['is_edit'];
		$is_effect =  $item['is_effect'];
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		
		$data['name'] = strim($_REQUEST['name']);
		if($data['name']=="")
		{
			showErr("请填写项目名称",$ajax,"");
		}
		if(msubstr($data['name'],0,25)!=$data['name'])
		{			
			showErr("项目名称不超过25个字",$ajax,"");
		}
		$data['cate_id'] = intval($_REQUEST['cate_id']);
		if($data['cate_id']==0)
		{
			showErr("请选择项目分类",$ajax,"");
		}
		$data['province'] = strim($_REQUEST['province']);
		if($data['province']=='')
		{
			showErr("请选择省份",$ajax,"");
		}
		$data['city'] = strim($_REQUEST['city']);
		if($data['city']=='')
		{
			showErr("请选择城市",$ajax,"");
		}
		$data['brief'] = strim($_REQUEST['brief']);
		$data['image'] = replace_public(addslashes(trim($_REQUEST['image'])));
		if($data['image']=="")
		{			
			showErr("上传封面图片",$ajax,"");
		}
		
		require_once APP_ROOT_PATH."system/libs/words.php";	
		$data['tags'] = implode(" ",words::segment($data['name']));


		$data['description'] = replace_public(addslashes(trim(valid_tag($_REQUEST['description']))));	
		
//		
	
		$data['vedio'] = strim($_REQUEST['vedio']);
		
		if($data['vedio']!="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
		$data['limit_price'] = floatval($_REQUEST['limit_price']);
		if($data['limit_price']<=0)
		{
			showErr("请输入正确的目标金额",$ajax,"");
		}
		$data['deal_days'] = floatval($_REQUEST['deal_days']);
		if($data['deal_days']<=0)
		{
			showErr("请输入正确的上线天数",$ajax,"");
		}
		
		if($item['type'] ==2 || $type ==2){
			//房产众筹
			$data['houses_name']=strim($_REQUEST['houses_name']);
			$data['houses_status']=strim($_REQUEST['houses_status']);
			$data['houses_address']=strim($_REQUEST['houses_address']);
			$data['api_address']=strim($_REQUEST['api_address']);
			$data['xpoint']=strim($_REQUEST['xpoint']);
			$data['ypoint']=strim($_REQUEST['ypoint']);
			$data['houses_info']=replace_public(addslashes(trim($_REQUEST['houses_info'])));
			$data['houses_earnings_info'] = replace_public(addslashes(trim($_REQUEST['houses_earnings_info'])));
			$data['update_log_icon'] = replace_public(addslashes(trim($_REQUEST['update_log_icon'])));
			$data['is_earnings'] = intval($_REQUEST['is_earnings']);
			if($data['is_earnings'] == 1)
			{
				$data['earnings'] = floatval($_REQUEST['earnings']);
				$data['earnings_cycle'] = intval($_REQUEST['earnings_cycle']);
				$data['earnings_send_count'] = intval($_REQUEST['earnings_send_count']);
				$data['earnings_send_capital'] = intval($_REQUEST['earnings_send_capital']);
					if($data['earnings'] <=0)
				{
					showErr("请输入收益百分比",$ajax,"");
				}
				if($data['earnings_cycle'] <=0)
				{
					showErr("请输入收益周期",$ajax,"");
				}
				if($data['earnings_send_count'] <=0)
				{
					showErr("请输入收益期数",$ajax,"");
				}
			}
			else{
				$data['earnings'] = 0;
				$data['earnings_cycle'] = 0;
				$data['earnings_send_count'] = 0;
				$data['earnings_send_capital'] = 1;
			}			
			
			if($data['houses_name'] =='')
			{
				showErr("请输入楼盘名称",$ajax,"");
			}
			if($data['houses_status'] =='')
			{
				showErr("请选择楼盘阶段",$ajax,"");
			}
			if($data['houses_address'] =='')
			{
				showErr("请输入楼盘地址",$ajax,"");
			}
		}
		$images_array=$_REQUEST['image_more'];//项目图片
		$data['is_edit'] = 1;
		
		
		if($id>0)
		{
			$savenext = intval($_REQUEST['savenext']);
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			
			//追加faq
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_faq where deal_id = ".$id);
			$sort = 1;
			foreach($_REQUEST['question'] as $kk=>$question_item)
			{
				if(strim($_REQUEST['question'][$kk])!=""&&strim($_REQUEST['answer'][$kk])!=""&&strim($_REQUEST['question'][$kk])!="请输入问题"&&strim($_REQUEST['answer'][$kk])!="请输入答案")
				{
					$faq_item['deal_id'] = $id;
					$faq_item['question'] = strim($_REQUEST['question'][$kk]);
					$faq_item['answer'] = strim($_REQUEST['answer'][$kk]);
					$faq_item['sort'] = $sort;
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_faq",$faq_item);
					$sort++;
				}
			}
			
			//插入图片
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_image where deal_id=".intval($id)."");
			insert_deal_images($images_array,$id);
			
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$id);
			if($savenext==0)
			{
				showSuccess($id,$ajax,"");
			}
			else
			{
				showSuccess("",$ajax,url_wap("project#add_item",array("id"=>$id,"first_post"=>1)));
			}
		}
		else
		{
			$data['type']=$type;
			$data['user_id'] = intval($GLOBALS['user_info']['id']);
			$data['user_name'] = $GLOBALS['user_info']['user_name'];
			$data['create_time'] = get_gmtime();
			$savenext = intval($_REQUEST['savenext']);
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$data_id = intval($GLOBALS['db']->insert_id());
			if($data_id==0)
			{
				showErr("保存失败，请联系管理员",$ajax,"");
			}
			else
			{
				es_session::delete("deal_image");
				
				//追加faq
				$sort = 1;
				foreach($_REQUEST['question'] as $kk=>$question_item)
				{
					if(strim($_REQUEST['question'][$kk])!=""&&strim($_REQUEST['answer'][$kk])!=""&&strim($_REQUEST['question'][$kk])!="请输入问题"&&strim($_REQUEST['answer'][$kk])!="请输入答案")
					{
						$faq_item['deal_id'] = $data_id;
						$faq_item['question'] = strim($_REQUEST['question'][$kk]);
						$faq_item['answer'] = strim($_REQUEST['answer'][$kk]);
						$faq_item['sort'] = $sort;
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_faq",$faq_item);
						$sort++;
					}
				}
				//插入图片
				insert_deal_images($images_array,$data_id);//image_more 页面上传图片url数组名
				
				if($savenext==0)
				{
					showSuccess($data_id,$ajax,"");
				}
				else
				{
					showSuccess("",$ajax,url_wap("project#add_item",array("id"=>$data_id,"first_post"=>1)));
					
				}
			}
			
		}		
		
	}
	
	public function del()
	{
		$ajax = intval($_REQUEST['ajax']);	
		

		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."deal where id = ".$id." and is_edit = 1 and user_id = ".intval($GLOBALS['user_info']['id']." and is_effect = 0 and is_delete = 0"));
		if($GLOBALS['db']->affected_rows()>0)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item_image where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_faq where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_pay_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_support_log where deal_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_visit_log where deal_id = ".$id);
			// showSuccess("删除成功",$ajax,get_gopreview_wap());
			$data['status'] = 1;
			$data['info']='删除成功！';
			ajax_return($data);
		}
		else
		{
			// showErr("删除失败",$ajax);
			$data['status'] = 0;
			$data['info']='删除失败！';
			ajax_return($data);
		}
		
	
	}
	
	public function add_item()
	{			
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));			
		
		
		$id = intval($_REQUEST['id']);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_delete = 0 and id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
		if($deal_item)
		{		

			$deal_item_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id = ".$deal_item['id']." order by price asc");
			$GLOBALS['tmpl']->assign("deal_item_list",$deal_item_list);
		//	print_r("select * from ".DB_PREFIX."deal_item where deal_id = ".$deal_item['id']." order by price asc");exit;
			$GLOBALS['tmpl']->assign("deal_item",$deal_item);
			$GLOBALS['tmpl']->assign("page_title","回报设置 - ".$deal_item['name']);
			$GLOBALS['tmpl']->display("project_add_item.html");
		}
		else
		{
			app_redirect_preview();
		}
	}
	
	public function edit_item()
	{			
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));			
		
		
		$id = intval($_REQUEST['id']);
		$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_edit = 1 and is_delete = 0 and id = ".$item['deal_id']." and user_id = ".intval($GLOBALS['user_info']['id']));
		if($deal_item&&$item)
		{		
			$deal_item_images = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item_image where deal_id = ".$deal_item['id']." and deal_item_id = ".$item['id']);
			$GLOBALS['tmpl']->assign("deal_item_images",$deal_item_images);
			
			$GLOBALS['tmpl']->assign("deal_item",$deal_item);
			$GLOBALS['tmpl']->assign("item",$item);
			$GLOBALS['tmpl']->assign("page_title","回报设置 - ".$deal_item['name']);
			$GLOBALS['tmpl']->display("project_edit_item.html");
		}
		else
		{
			app_redirect_preview();
		}
	}
	
	public function del_item()
	{			
		$ajax = intval($_REQUEST['ajax']);	
		

		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}		
		
		
		$id = intval($_REQUEST['id']);
		$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id = ".$id);
		$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where is_edit = 1 and (is_effect = 0 or is_effect = 2) and is_delete = 0 and id = ".$item['deal_id']." and user_id = ".intval($GLOBALS['user_info']['id']));
		if($deal_item&&$item)
		{		
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item where id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item_image where deal_item_id = ".$id);
			showErr("",$ajax,get_gopreview());
			
		}
		else
		{
			showErr("删除失败",$ajax);
		}
	}
	
	public function save_deal_item()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}
		
		$id = intval($_REQUEST['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id and user_id=".$GLOBALS['user_info']['id']);
		if(!$item){
			showErr("项目不存在",$ajax,"");
		}
		if($id>0){
			$count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item where id=$id and deal_id=$deal_id ");
			if(!$count){
				showErr("该项目不存在这个子项目",$ajax,"");
			}
		}
		$data['description'] = strim($_REQUEST['description']);
		$data['type'] = intval($_REQUEST['type']);
		$data['deal_id'] = intval($_REQUEST['deal_id']);
		if($data['type'] ==1)
		{
			$data['price']=0;
			$data['is_delivery'] = 0;
			$data['delivery_fee'] = 0;
			$data['is_limit_user'] = 0;
			$data['repaid_day'] = 0;
			$data['is_share'] = 0;
			$data['share_fee'] = 0;
		}else
		{
			$data['price'] = floatval($_REQUEST['price']);
			if($data['price']<=0)
			showErr("请输入正确的价格",$ajax);
			
			
			$data['is_limit_user'] = intval($_REQUEST['is_limit_user']);
			$data['limit_user'] = intval($_REQUEST['limit_user']);
			$data['repaid_day'] = intval($_REQUEST['repaid_day']);
			$data['is_delivery'] = intval($_REQUEST['is_delivery']);
			
			if($data['type'] !=2)
			{
				$data['is_share'] = intval($_REQUEST['is_share']);
				$data['delivery_fee'] = floatval($_REQUEST['delivery_fee']);
			}
			else
			{
				$data['is_share'] = 0;
				$data['delivery_fee'] = 0;
			}
			
			if($data['is_share'] ==1)
				$data['share_fee'] = floatval($_REQUEST['share_fee']);
			else
				$data['share_fee'] = 0;
				
			if($data['type'] ==2)
			{
				$data['lottery_measure'] = intval($_REQUEST['lottery_measure']);
				$data['maxbuy'] = intval($_REQUEST['maxbuy']);
			}
		}
		
		if(count($_REQUEST['image'])>4)
		{
			showErr("图片不能超过四张",$ajax);
		}
		
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				if(count($_REQUEST['image'])>=0)
				{
					foreach($_REQUEST['image'] as $k=>$v)
					{
						$image_data['deal_id'] = $data['deal_id'];
						$image_data['deal_item_id'] = $result_id;
						$image_data['image'] = replace_public($v);
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item_image",$image_data);
					}					
				}
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$data['deal_id']);
				showSuccess("保存成功",$ajax,url_wap("project#add_item",array("id"=>$deal_id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item",$data,"UPDATE","id=".$id,"SILENT");
			if(count($_REQUEST['image'])>=0)
			{
					$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item_image where deal_item_id = ".$id);
					foreach($_REQUEST['image'] as $k=>$v)
					{
						$image_data['deal_id'] = $data['deal_id'];
						$image_data['deal_item_id'] = $id;
						$image_data['image'] = replace_public(strim($v));
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item_image",$image_data);
					}					
			}
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$data['deal_id']);
			showSuccess("保存成功",$ajax,url_wap("project#add_item",array("id"=>$deal_id)));
		}
		
	}
	
	public function submit_deal()
	{
		$id = intval($_REQUEST['id']);
		$ajax = 1;
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		$vo = $GLOBALS['db']->getRow("select is_edit from ".DB_PREFIX."deal where user_id = ".intval($GLOBALS['user_info']['id'])." and is_delete = 0 and is_effect = 0 and id= ".$id);
		$deal_item_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item where deal_id = ".$id);
		
//		if($vo['is_edit']==0)
//		{
//			showErr("项目审核中，请勿重复提交！",$ajax);
///		}
		if($deal_item_count==0)
		{
			showErr("请先添加至少一项回报设置! ",$ajax);
		}
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_edit = 0 where id = ".$id);
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_effect = 0 where id = ".$id);
		showSuccess("提交成功，等待管理员审核！",$ajax);
	}
	public function investor_index1()
	{
		
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
			
		$GLOBALS['tmpl']->assign("page_title","发起项目");
		$GLOBALS['tmpl']->display("investor_index1.html");
	}
	public function choose()
	{
		if(!$GLOBALS['user_info']){
			if($_REQUEST['first_post']){
				app_redirect(url_wap("user#login",array('first_post'=>1)));
			}else{
				app_redirect(url_wap("user#login"));
			}
 		}
 		
		$is_wap = 1;
		check_tg($is_wap);	
		$GLOBALS['tmpl']->assign("page_title","发起项目");
		$GLOBALS['tmpl']->display("project_choose.html");
	}
	
	public function investor_one(){
		require APP_ROOT_PATH.'system/utils/tree.php';
		//股权众筹 发起项目项目基本信息
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		
		if(!$GLOBALS['user_info'])
			app_redirect(url_wap("user#login"));
		
		$is_wap = 1;
		check_tg($is_wap);
		
		$id = intval($_REQUEST['id']);
		if($id>0){
			$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($deal_item)){
				showErr("该项目不存在");
			}
			if($deal_item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		$show_html=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."help where id=7");
		$GLOBALS['tmpl']->assign('show_html',$show_html);
		$cate_list_str = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
		$tree=new tree();
		$cate_list=$tree->toFormatTree($cate_list_str);
		$GLOBALS['tmpl']->assign("cate_list",$cate_list);
		if($deal_item)
		{
			$GLOBALS['tmpl']->assign("page_title",$deal_item['name']);
			$region_pid = 0;
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			foreach($region_lv2 as $k=>$v)
			{
				if($v['name'] == $deal_item['province'])
				{
					$region_lv2[$k]['selected'] = 1;
					$region_pid = $region_lv2[$k]['id'];
					break;
				}
			}
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			if($region_pid>0)
			{
				$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
				foreach($region_lv3 as $k=>$v)
				{
					if($v['name'] == $deal_item['city'])
					{
						$region_lv3[$k]['selected'] = 1;
						break;
					}
				}
				$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
			}
			
			
			$deal_item['business_create_time']=to_date($deal_item['business_create_time'],'Y-m-d');
			$deal_item['limit_price']=$deal_item['limit_price']/10000;
			$deal_item['invote_mini_money']=$deal_item['invote_mini_money']/10000;
			//资质证明
			$audit_data_list = unserialize($deal_item['audit_data']);	
 			$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);	
			$GLOBALS['tmpl']->assign("deal_item",$deal_item);
			$GLOBALS['tmpl']->display("investor_one.html");
		}	
		else
		{
			
			if(!$GLOBALS['user_info'])
			app_redirect(url_wap("user#login"));	
 			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			$deal_image =  es_session::get("deal_image");
			$GLOBALS['tmpl']->assign("deal_image",$deal_image);
			$GLOBALS['tmpl']->display("investor_one.html");
		}	
	}
	public function investor_one_save()
	{
		$ajax = intval($_REQUEST['ajax']);	
//		if(!check_ipop_limit(get_client_ip(),"project_agency_save",30))
//		showErr("提交太频繁",$ajax,"");	
			
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		$id =  intval($_REQUEST['id']);
		$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id);
		$is_edit=$deal['is_edit'];
		$is_effect=$deal['is_effect'];
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		
		$data['name'] = strim($_REQUEST['name']);
		if($data['name']=="")
		{
			showErr("请填写项目名称",$ajax,"");
		}
		if(msubstr($data['name'],0,25)!=$data['name'])
		{			
			showErr("项目名称不超过25个字",$ajax,"");
		}
		$data['investor_authority'] = intval($_REQUEST['investor_authority']);
	/*	if($data['investor_authority']=='')
		{
			showErr("请选择项目详细资料查看权限",$ajax,"");
		}
	*/	$data['cate_id'] = intval($_REQUEST['cate_id']);
		if($data['cate_id']==0)
		{
			showErr("请选择项目分类",$ajax,"");
		}
		$data['tags'] = strim($_REQUEST['tags']);
		if($data['tags']=="")
		{
			showErr("请填写项目标签",$ajax,"");
		}
		if(msubstr($data['tags'],0,25)!=$data['tags'])
		{			
			showErr("项目标签不超过25个字",$ajax,"");
		}
		$data['project_step'] = intval($_REQUEST['project_step']);
	/*	if($data['project_step']==0)
		{
			showErr("请选择项目所属阶段",$ajax,"");
		}
	*/	
		$data['business_employee_num'] = intval($_REQUEST['business_employee_num']);
		if($data['business_employee_num']==0)
		{
			showErr("请填写企业员工人数",$ajax,"");
		}
		$data['province'] = strim($_REQUEST['province']);
		if($data['province']=='')
		{
			showErr("请选择省份",$ajax,"");
		}
		$data['city'] = strim($_REQUEST['city']);
		if($data['city']=='')
		{
			showErr("请选择城市",$ajax,"");
		}
		$data['business_is_exist'] = intval($_REQUEST['business_is_exist']);
/*		if($data['business_is_exist']==0)
		{
			showErr("请选择公司是否已经成立",$ajax,"");
		}
*/		
		$data['business_create_time'] =to_timespan(strim($_REQUEST['business_create_time']),'Y-m-d');
		if($data['business_is_exist']==1)
		{
			if($data['business_create_time']==0)
			{
				showErr("请选择企业成立时间",$ajax,"");
			}
		}	
		$data['has_another_project'] = intval($_REQUEST['has_another_project']);
/*		if($data['has_another_project']==0)
		{
			showErr("请选择是否有其他项目",$ajax,"");
		}
*/		$data['business_name'] = strim($_REQUEST['business_name']);
		if($data['business_name']=="")
		{
			showErr("请填写公司全称",$ajax,"");
		}
		$data['business_address'] = strim($_REQUEST['business_address']);
		if($data['business_address']=="")
		{
			showErr("请填写办公地址",$ajax,"");
		}
		$data['limit_price'] = floatval($_REQUEST['limit_price']);
		if($data['limit_price']<=0)
		{
			showErr("请输入正确的融资金额",$ajax,"");
		}
		
		$data['invote_mini_money'] = floatval($_REQUEST['invote_mini_money']);
		if($data['invote_mini_money']<=0)
		{
			showErr("请输入正确的单投资人最低出资",$ajax,"");
		}
		$data['transfer_share'] = floatval($_REQUEST['transfer_share']);
		if($data['transfer_share']==''|| $data['transfer_share']>100)
		{
			showErr("出让的股份为空或者出让的股份超过100%",$ajax,"");
		}
		$data['business_stock_type'] = intval($_REQUEST['business_stock_type']);
	/*	if($data['business_stock_type']==0)
		{
			showErr("请选择众筹股东成立的有限合伙企业入股方式",$ajax,"");
		}
	*/	$data['business_descripe'] = strim($_REQUEST['business_descripe']);
		if($data['business_descripe']=="")
		{
			showErr("请填写企业项目简介",$ajax,"");
		}
		$data['image'] = replace_public(addslashes(trim($_REQUEST['image'])));
//		echo $_REQUEST['image'];exit;
		if($data['image']=="")
		{			
			showErr("上传封面图片",$ajax,"");
		}
		
		require_once APP_ROOT_PATH."system/libs/words.php";	
		$data['vedio'] = strim($_REQUEST['vedio']);
		
		if($data['vedio']!="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
 		$audit_data=deal_investor_info($_REQUEST['audit_data'],'audit_data',unserialize($deal['audit_data']));
		$data['audit_data'] = serialize($audit_data['data']);
		$data['is_edit']=1;
		$data['type']=1;
		$data['limit_price']=$data['limit_price']*10000;
		$data['invote_mini_money']=$data['invote_mini_money']*10000;
		if($id>0)
		{
			$savenext = intval($_REQUEST['savenext']);
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$id);
			if($savenext==0)
			{
				showSuccess($id,$ajax,"");
			}
			else
			{
				$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
				if($investor_edit==1){		
					showSuccess("",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
				}
				else{
					showSuccess("",$ajax,url_wap("project#investor_two",array("id"=>$id)));
				}
				
			}
		}
		else
		{
			$data['user_id'] = intval($GLOBALS['user_info']['id']);
			$data['user_name'] = $GLOBALS['user_info']['user_name'];
			$data['create_time'] = NOW_TIME;
			$savenext = intval($_REQUEST['savenext']);
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$data_id = intval($GLOBALS['db']->insert_id());
			if($data_id==0)
			{
				showErr("保存失败，请联系管理员",$ajax,"");
			}
			else
			{
				es_session::delete("deal_image");
				if($savenext==0)
				{
					showSuccess($data_id,$ajax,"");
				}
				else
				{
					showSuccess("",$ajax,url_wap("project#investor_two",array("id"=>$data_id)));
				}
			}
		}		
	} 

	public function investor_two(){
		//股权众筹 发起项目市场定位与商业模式
		
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		
		
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
 		if($id>0){
			$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($deal_item)){
				showErr("该项目不存在");
			}
			if($deal_item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		if($deal_item)
		{
			$GLOBALS['tmpl']->assign("deal_item",$deal_item);
		}
		 
		$GLOBALS['tmpl']->display("investor_two.html");
	}
	
	public function investor_two_save()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		$id = intval($_REQUEST['id']);
		$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		$data['description_2'] = replace_public(addslashes(trim($_REQUEST['description_2'])));	
		$data['description_3'] = replace_public(addslashes(trim($_REQUEST['description_3'])));	
		$data['description_4'] = replace_public(addslashes(trim($_REQUEST['description_4'])));	
		$data['description_5'] = replace_public(addslashes(trim($_REQUEST['description_5'])));	
		$data['description_6'] = replace_public(addslashes(trim($_REQUEST['description_6'])));	
		$data['description_7'] = replace_public(addslashes(trim($_REQUEST['description_7'])));
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$result_id);
				showSuccess("保存成功",$ajax,url_wap("project#investor_three",array("id"=>$result_id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$id);
			$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
			if($investor_edit==1){		
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
			else{	
				showSuccess("保存成功",$ajax,url_wap("project#investor_three",array("id"=>$id)));
			}
		}
	}
	public function investor_three(){
		//股权众筹 发起项目 编辑及管理团队
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);	
 		if($id>0){
			$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($item)){
				showErr("该项目不存在");
			}
			if($item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		if($item)
		{
			$GLOBALS['tmpl']->assign("item",$item);
			$stock_list = unserialize($item['stock']);
	 		$stock_num=count($stock_list);
	 		if($stock_num==0){$stock_num++;}
			$GLOBALS['tmpl']->assign("stock_num",$stock_num);
			$GLOBALS['tmpl']->assign("stock_list",$stock_list);
			$unstock_list = unserialize($item['unstock']);
			if(!$unstock_list||!is_array($unstock_list)){
				$unstock_num=1;
				$is_unstock=0;
			}else{
				$unstock_num=count($unstock_list);
				if($unstock_num==0){
					$is_unstock=0;
					$unstock_num++;
				}else{
					$is_unstock=1;
				}
			}
			$GLOBALS['tmpl']->assign("is_unstock",$is_unstock);
			$GLOBALS['tmpl']->assign("unstock_num",$unstock_num);
			
			$GLOBALS['tmpl']->assign("unstock_list",$unstock_list);
			
		}
		else
		{
			$GLOBALS['tmpl']->assign("stock_num",1);
			$GLOBALS['tmpl']->assign("unstock_num",1);
				
		} 
		$GLOBALS['tmpl']->display("investor_three.html");
	}
	public function investor_three_save()
	{
		$ajax = intval($_REQUEST['ajax']);	
		if(!check_ipop_limit(get_client_ip(),"project_save",5))
		showErr("提交太频繁",$ajax,"");	
		
		$id = intval($_REQUEST['id']);
		$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		$stock=deal_investor_info($_REQUEST['stock'],'stock');
		if($stock['status']==1){
			$data['stock'] = serialize($stock['data']);
		}else{
			showErr($stock['info'],$ajax);
		}
		$is_unstock=intval($_REQUEST['hasTeam']);
		if($is_unstock){
			$unstock=deal_investor_info($_REQUEST['unstock'],'unstock');
			$data['unstock'] = serialize($unstock['data']);
		}else{
			$data['unstock']='';
		}
		
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				showSuccess("保存成功",$ajax,url_wap("project#investor_four",array("id"=>$id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
			if($investor_edit==1){		
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
			else{
 				showSuccess("保存成功",$ajax,url_wap("project#investor_four",array("id"=>$id)));
			}
		}
		//$GLOBALS['tmpl']->assign("id",$id);
	}
	public function investor_four(){
		//股权众筹 发起项目项目历史执行资料
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
 		if($id>0){
			$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($item)){
				showErr("该项目不存在");
			}
			if($item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		if($item)
		{
			$GLOBALS['tmpl']->assign("item",$item);
			$history_list = unserialize($item['history']);
	 	//	$stock = unserialize($item['stock']);
			$history_num=count($history_list);
			$GLOBALS['tmpl']->assign("history_num",$history_num);
			$GLOBALS['tmpl']->assign("history_list",$history_list);	
		}
		else
		{
			$GLOBALS['tmpl']->assign("history_num",1);
			$history_html=$GLOBALS['tmpl']->display("add_new_history.html",'',true);
 			$GLOBALS['tmpl']->assign('history_html',$history_html);
		}
		 
		$GLOBALS['tmpl']->display("investor_four.html");
	}	
	public function investor_four_save()
	{
 		$ajax = intval($_REQUEST['ajax']);	
		if(!check_ipop_limit(get_client_ip(),"project_save",5))
		showErr("提交太频繁",$ajax,"");	
		
		$id = intval($_REQUEST['id']);
		$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		
		$history=deal_investor_info($_REQUEST['history'],'history');
		
		$data['history'] = serialize($history['data']);
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				showSuccess("保存成功",$ajax,url_wap("project#investor_five",array("id"=>$id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
			if($investor_edit==1){		
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
			else{
 				showSuccess("保存成功",$ajax,url_wap("project#investor_five",array("id"=>$id)));
			}
		}
	}


	public function add_investor_item(){
		$return=array('status'=>1,'html'=>'');
		$num=intval($_REQUEST['num']);
		$html=strim($_REQUEST['html']);
		if($html=='add_new_plan'){
			$GLOBALS['tmpl']->assign('plan_num',$num);
		}elseif($html='add_new_history'){
			$GLOBALS['tmpl']->assign('history_num',$num);
		}
		$GLOBALS['tmpl']->assign('num',$num);
		$return['html']=$GLOBALS['tmpl']->display($html.".html",'',true);
		//$return['html'] = $GLOBALS['tmpl']->fetch("str:".$return['html']);
		echo json_encode($return);
		exit;
	}
	public function investor_five(){
		//股权众筹 发起项目未来三年内计划
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
 		if($id>0){
			$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($item)){
				showErr("该项目不存在");
			}
			if($item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		if($item)
		{
			$GLOBALS['tmpl']->assign("item",$item);
			$plan_list = unserialize($item['plan']);
			$plan_num=count($plan_list);
			$GLOBALS['tmpl']->assign("plan_num",$plan_num);
			$GLOBALS['tmpl']->assign("plan_list",$plan_list);
		}
		else
		{
			$GLOBALS['tmpl']->assign("plan_num",1);
			$plan_html=$GLOBALS['tmpl']->display("add_new_plan.html",'',true);
 			$GLOBALS['tmpl']->assign('plan_html',$plan_html);
		}
		 
		$GLOBALS['tmpl']->display("investor_five.html");
	}
	public function investor_five_save()
	{
 		$ajax = intval($_REQUEST['ajax']);	
		if(!check_ipop_limit(get_client_ip(),"project_save",5))
		showErr("提交太频繁",$ajax,"");	
		
		$id = intval($_REQUEST['id']);
		$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		$plan=deal_investor_info($_REQUEST['plan'],'plan');
		
		$data['plan'] = serialize($plan['data']);
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				showSuccess("保存成功",$ajax,url_wap("project#investor_six",array("id"=>$result_id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
			if($investor_edit==1){		
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
			else{
 				showSuccess("保存成功",$ajax,url_wap("project#investor_six",array("id"=>$id)));
			}
		}
	}
	
	
	public function investor_six()
	{
		//股权众筹 发起项目项目附件
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
		if($id>0){
			$item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($item)){
				showErr("该项目不存在");
			}
			if($item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		$attach_list = unserialize($item['attach']);
		if($attach_list)
		{		
			$attach_num=count($attach_list);
		//	print_r($attach_num);exit;
			$GLOBALS['tmpl']->assign("attach_num",$attach_num);
			$GLOBALS['tmpl']->assign("attach_list",$attach_list);
			$GLOBALS['tmpl']->assign("item",$item);
		}
		else
		{
			$GLOBALS['tmpl']->assign("attach_num",1);
		}
		$GLOBALS['tmpl']->display("investor_six.html");
	}
	public function investor_six_save()
	{
 		$ajax = intval($_REQUEST['ajax']);	
		if(!check_ipop_limit(get_client_ip(),"project_save",5))
		showErr("提交太频繁",$ajax,"");	
		
		$id = intval($_REQUEST['id']);
		$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		$attach=deal_investor_info($_REQUEST['attach'],'attach');
		
		$data['attach'] = serialize($attach['data']);

		$data['investor_edit'] =1;
		if($id==0)
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
			$result_id = intval($GLOBALS['db']->insert_id());
			if($result_id>0)
			{
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$result_id)));
			}
			else
			{
				showErr("保存失败",$ajax);
			}
		}
		else
		{
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"UPDATE","id=".$id,"SILENT");
			$investor_edit = $GLOBALS['db']->getOne("select investor_edit from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".intval($GLOBALS['user_info']['id']));
			if($investor_edit==1){		
				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
			else{
 				showSuccess("保存成功",$ajax,url_wap("project#investor_edit",array("id"=>$id)));
			}
		}
	}
	public function investor_edit()
	{
		//股权众筹 发起项目编辑
		if(!$GLOBALS['user_info'])
		app_redirect(url_wap("user#login"));
		
		if(app_conf("INVEST_STATUS")==1){
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		$ajax = intval($_REQUEST['ajax']);	
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
		$user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id =".intval($GLOBALS['user_info']['id']));
 		if($id>0){
			$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0  ");
			if(empty($deal_item)){
				showErr("该项目不存在");
			}
			if($deal_item['user_id']!=intval($GLOBALS['user_info']['id'])){
				showErr("您没有该项目的权限！");
			}
 		}
		$is_effect =  $deal_item['is_effect'];
		if($id>0&&$is_effect==1)
		{
			showErr("项目已提交，不能更改",$ajax,"");
		}
		if(!empty($deal_item['vedio'])&&!preg_match("/http://player.youku.com/embed/i",$deal_item['source_video'])){
			$deal_item['source_vedio']= preg_replace("/id_(.*)\.html(.*)/i","http://player.youku.com/embed/\${1}",baseName($deal_item['vedio']));
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set source_vedio='".$deal_item['source_vedio']."'  where id=".$deal_item['id']);
		}
		$deal_item['business_create_time'] =to_date ($deal_item['business_create_time'], 'Y-m-d' ); 
		$deal_item['limit_price'] =number_format($deal_item['limit_price'],2); 	
		$cate = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id =".$deal_item['cate_id']);
		$GLOBALS['tmpl']->assign("cate",$cate);
		$GLOBALS['tmpl']->assign("user_name",$user_name);
		//编辑及管理团队
		$stock_list = unserialize($deal_item['stock']);
		$GLOBALS['tmpl']->assign("stock_list",$stock_list);
		$unstock_list = unserialize($deal_item['unstock']);
		$GLOBALS['tmpl']->assign("unstock_list",$unstock_list);
		//项目历史执行资料
		$history_list = unserialize($deal_item['history']);
		$GLOBALS['tmpl']->assign("history_list",$history_list);	
		$total_history_income =0;
		$total_history_out=0;
		$total_history=0;
		foreach($history_list as $key => $v)
		{
			$total_history_income += floatval($v["info"]["item_income"]);
			$total_history_out+= floatval($v["info"]["item_out"]);
			$total_history=$total_history_income-$total_history_out;
		}
		$GLOBALS['tmpl']->assign("total_history_income",$total_history_income);
		$GLOBALS['tmpl']->assign("total_history_out",$total_history_out);
		$GLOBALS['tmpl']->assign("total_history",$total_history);
		//未来三年内计划
		$plan_list = unserialize($deal_item['plan']);
		$GLOBALS['tmpl']->assign("plan_list",$plan_list);
		$total_plan_income =0;
		$total_plan_out=0;
		$total_plan=0;
		foreach($plan_list as $key => $v)
		{
			$total_plan_income += floatval($v["info"]["item_income"]);
			$total_plan_out+= floatval($v["info"]["item_out"]);
			$total_plan=$total_plan_income-$total_plan_out;
		}
		$GLOBALS['tmpl']->assign("total_plan_income",$total_plan_income);
		$GLOBALS['tmpl']->assign("total_plan_out",$total_plan_out);
		$GLOBALS['tmpl']->assign("total_plan",$total_plan);
		//项目附件
		$attach_list = unserialize($deal_item['attach']);
		$GLOBALS['tmpl']->assign("attach_list",$attach_list);
		//资质证明
		$audit_data_list = unserialize($deal_item['audit_data']);
		$GLOBALS['tmpl']->assign("audit_data_list",$audit_data_list);

		$GLOBALS['tmpl']->assign("deal_item",$deal_item);
		$GLOBALS['tmpl']->display("investor_edit.html");
	}
	public function submit_investor()
	{
		$id = intval($_REQUEST['id']);
		$ajax = 1;
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url_wap("user#login"));
		}
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_edit = 0 where id = ".$id);
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_effect = 0 where id = ".$id);
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set type = 1 where id = ".$id);
		showSuccess("提交成功，等待管理员审核！",$ajax);
	}
	public function downfile()
	{
		$filename = strim($_REQUEST['file']);    //要下载的文件名
		if($filename==null){
			showErr("找不到文件");
			return false;
		}
		else{
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".basename($filename));  
			readfile($filename);
		}
	}
}
?>