<?php
class project_item{
	public function index()
	{	
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); //项目id
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".$user_id."");
		if(!$deal_info)
		{
			$root['status']=0;
			$root['info']="请选择正确项目";
			output($root);
		}
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$deal_item_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item where deal_id=".$deal_id."");
		
		if($deal_item_count >0)
		{
			$deal_item_list=$GLOBALS['db']->getAll("select id,deal_id,price,description,is_delivery,delivery_fee,is_limit_user,limit_user,is_share,share_fee,repaid_day from ".DB_PREFIX."deal_item where deal_id=".$deal_id." order by id desc limit ".$limit);
			foreach($deal_item_list as $k=>$v)
			{
				$deal_item_list[$k]['format_price']=format_price($v['price']);
				$deal_item_list[$k]['format_delivery_fee']=format_price($v['delivery_fee']);
				$deal_item_list[$k]['format_share_fee']=format_price($v['share_fee']);
				$deal_item_list[$k]['content']=$v['description'];//回报内容改content，因为苹果端这个是关键字
				$images_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item_image where deal_id=".$deal_id." and deal_item_id=".intval($v['id']));
				foreach($images_list as $kk=>$vv)
				{
						$images_list[$kk]['image']=get_abs_img_root($vv['image']);				
				}
				$deal_item_list[$k]['images_list']=$images_list;
			}
		}else
		{
			$deal_item_list=array();
		}
		
		$root['deal_item_list']=$deal_item_list;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_item_count / $page_size ) 
		);
		$root['deal_id']=$deal_id;
		
		output($root);
	}
	
	public function save_item()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                             
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		$id=intval($GLOBALS ['request']['id']);//回报id
		$images_save_ids=strim($GLOBALS ['request']['images_save_ids']);//编辑后未修改过和删除的图片id,以逗号分开的id串。
		$deal_id=intval($GLOBALS ['request']['deal_id']);//项目id
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".$user_id." and is_edit=1 and is_effect in(0,2)");
		
		if(!$deal_info)
		{
			$root['status']=0;
			$root['info']="出错了";
			output($root);
		}
		$root['deal_id']=$deal_id;
		if($id >0)
		{	
			$root['id']=$id;
			$deal_item_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_item where id=".$id." and deal_id=".$deal_id." ");
			if(!$deal_item_info)
			{
				$root['status']=0;
				$root['info']="回报不存在";
				output($root);
			}
			if($images_save_ids !='')
			{
				$deal_images_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_item_image where id in(".$images_save_ids.") and deal_item_id =".intval($id));
				$new_images=0;
				for($i=1;$i<=4;$i++)
				{
					if($_FILES['images_'.$i])
						$new_images++;
				}
				if($deal_images_count+$new_images >4)
				{
					$root['status']=0;
					$root['info']="图片最多传四张";
					output($root);
				}
			}
			
		}
		
		$data=array();
		$data['price']=floatval($GLOBALS ['request']['price']);//金额
		//$data['description']=btrim($GLOBALS ['request']['description']);//回报内容
		$data['description']=btrim($GLOBALS ['request']['content']);//回报内容
		$data['is_delivery']=intval($GLOBALS ['request']['is_delivery']);//是否配送
		$data['delivery_fee']=floatval($GLOBALS ['request']['delivery_fee']);//配送费用
		$data['is_limit_user']=intval($GLOBALS ['request']['is_limit_user']);//是否限购
		$data['limit_user']=intval($GLOBALS ['request']['limit_user']);//限购人数
		$data['is_share']=intval($GLOBALS ['request']['is_share']);//是否分红
		$data['share_fee']=floatval($GLOBALS ['request']['share_fee']);//分红金额
		$data['repaid_day']=intval($GLOBALS ['request']['repaid_day']);//回报天数
		
		if($data['price'] <=0)
		{
			$root['status']=0;
			$root['info']="请输入金额";
			output($root);
		}
		
		if($id >0){
			//更新
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item",$data,"UPDATE","id=".$id,"SILENT");
			$deal_item_id=$id;
			$root['status']=1;
			$root['info']="操作成功";
		}
		else{
			//插入
			$data['deal_id']=$deal_id;//项目id
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item",$data,"INSERT","","SILENT");
			$deal_item_id = intval($GLOBALS['db']->insert_id());
		//end插入
		}
		
		
		if($deal_item_id >0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$deal_id);
			//插入图片
			if($id >0)
			{
				if($images_save_ids !='')
				{
					$deal_images_count=$GLOBALS['db']->getOne("delete from ".DB_PREFIX."deal_item_image where id not in(".$images_save_ids.") and deal_item_id =".intval($id));
				}
				else
				{
					$deal_images_count=$GLOBALS['db']->getOne("delete from ".DB_PREFIX."deal_item_image where deal_item_id =".intval($id));
				}
			}
			
			
			if (isset ( $_FILES ['images_1'] ) || isset ( $_FILES ['images_2'] ) || isset ( $_FILES ['images_3'] ) ||isset ( $_FILES ['images_4'] ))
			{
				$dir = createImageDirectory ();
				$images_url=array();
			}
			
			if (isset ( $_FILES ['images_1'] ))
			{
				$images_result = save_image_upload ( $_FILES, "images_1", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $images_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $images_result ['message'] );
					$data['status']=0;
					output ( $data );
				} else
				{
					$images_url[] = $images_result ['images_1'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['images_2'] ))
			{
				$images_result = save_image_upload ( $_FILES, "images_2", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $images_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $images_result ['message'] );
					$data['status']=0;
					output ( $data );
				} else
				{
					$images_url[] = $images_result ['images_2'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['images_3'] ))
			{
				$images_result = save_image_upload ( $_FILES, "images_3", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $images_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $images_result ['message'] );
					$data['status']=0;
					output ( $data );
				} else
				{
					$images_url[] = $images_result ['images_3'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['images_4'] ))
			{
				$images_result = save_image_upload ( $_FILES, "images_4", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $images_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $images_result ['message'] );
					$data['status']=0;
					output ( $data );
				} else
				{
					$images_url[] = $images_result ['images_4'] ['url'];
				}
			}
			
			foreach($images_url as $k=>$v)
			{
				$img_data['deal_id'] = $deal_id;
				$img_data['deal_item_id'] = $deal_item_id;
				$img_data['image'] = $v;
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item_image",$img_data,"INSERT","","SILENT");
			}
			//end插入图片
			$root['status']=1;
			$root['info']="操作成功";
			
		}else
		{
			$root['status']=0;
			$root['info']="操作失败";
		}
		
		output($root);
	}
	
	public function edit()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); //项目id
		$id = intval ( $GLOBALS ['request'] ['id'] ); //回报id
		
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".$user_id." and is_edit=1 and is_effect in(0,2)");
		if(!$deal_info)
		{
			$root['status']=0;
			$root['info']="项目不存在";
			output($root);
		}
		
		$deal_item=$GLOBALS['db']->getRow("select id,deal_id,price,description,is_delivery,delivery_fee,is_limit_user,limit_user,is_share,share_fee from ".DB_PREFIX."deal_item where id=".$id."");
		if(!$deal_item)
		{
			$root['status']=0;
			$root['info']="回报不存在";
			output($root);
		}
		
		$deal_item['price']=format_price($deal_item['price']);
		$deal_item['delivery_fee']=format_price($deal_item['delivery_fee']);
		$deal_item['share_fee']=format_price($deal_item['share_fee']);
		$deal_item['content']=$deal_item['description'];
		
		//回报图片
		$deal_item_image=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item_imag where deal_id=".intval($deal_item['deal_id'])." and deal_item_id=".intval($deal_item['id'])."");
		foreach($deal_item_image as $k=>$v)
		{
			$deal_item_image['image']=get_abs_img_root($v['image']);				
		}
		$root['id']=$id;//回 报id
		$root['deal_id']=$deal_id;
		$root['deal_item']=$deal_item;
		$root['deal_item_image']=$deal_item_image;
		
		output($root);
	}
	
	public function delete()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); //项目id
		$id = intval ( $GLOBALS ['request'] ['id'] ); //回报id
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".$user_id." and is_edit=1 and is_effect in(0,2)");
		if(!$deal_info)
		{
			$root['status']=0;
			$root['info']="出错了";
			output($root);
		}
		$root['deal_id']=$deal_id;
		$deal_item=$GLOBALS['db']->getRow("select id,deal_id,price,description,is_delivery,delivery_fee,is_limit_user,limit_user,is_share,share_fee from ".DB_PREFIX."deal_item where id=".$id."");
		if(!$deal_item)
		{
			$root['status']=0;
			$root['info']="回报不存在";
			output($root);
		}
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_item  where id=".$id." and deal_id=".$deal_id);
		
		if($GLOBALS['db']->affected_rows()>0)
		{
			$root['status']=1;
			$root['info']="删除成功";
		}
		else
		{
			$root['status']=0;
			$root['info']="删除失败";
		}
		output($root);
	}
	
}
?>