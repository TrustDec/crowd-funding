<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户项目保存
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class project_save
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			$id =  intval($_REQUEST['id']);
			$is_edit = $GLOBALS['db']->getOne("select is_edit from ".DB_PREFIX."deal where id = ".$id);
			$is_effect = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."deal where id = ".$id);
			if($id>0&&$is_effect==1)
			{
				$root['info'] = "项目已提交，不能更改";
			}
			
			$data['name'] = strim($_REQUEST['name']);
			if($data['name']=="")
			{
				$root['info'] = "请填写项目名称";
			}
			if(msubstr($data['name'],0,25)!=$data['name'])
			{	
				$root['info'] = "项目名称不超过25个字";
			}
			$data['cate_id'] = intval($_REQUEST['cate_id']);
			if($data['cate_id']==0)
			{
				$root['info'] = "请选择项目分类";
			}
			$data['province'] = strim($_REQUEST['province']);
			if($data['province']=='')
			{
				$root['info'] = "请选择省份";
			}
			$data['city'] = strim($_REQUEST['city']);
			if($data['city']=='')
			{
				$root['info'] = "请选择城市";
			}
			$data['brief'] = strim($_REQUEST['brief']);
			$data['image'] = replace_public(addslashes(trim($_REQUEST['image'])));
			if($data['image']=="")
			{	
				$root['info'] = "上传封面图片";	
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
				$root['info'] = "请输入正确的目标金";
			}
			$data['deal_days'] = floatval($_REQUEST['deal_days']);
			if($data['deal_days']<=0)
			{
				$root['info'] = "请输入正确的上线天数";
			}
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
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$id);
				if($savenext==0)
				{
					showSuccess($id,$ajax,"");
				}
				else
				{
					showSuccess("",$ajax,url("project#add_item",array("id"=>$id)));
				}
			}
			else
			{
				$data['user_id'] = $user_id;
				$data['user_name'] = $user['user_name'];
				$data['create_time'] = NOW_TIME;
				$savenext = intval($_REQUEST['savenext']);
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,"INSERT","","SILENT");
				$data_id = intval($GLOBALS['db']->insert_id());
				if($data_id==0)
				{
					$root['info'] = "保存失败，请联系管理员";
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
					if($savenext==0)
					{
						showSuccess($data_id,$ajax,"");
					}
					else
					{
						showSuccess("",$ajax,url("project#add_item",array("id"=>$data_id)));
					}
				}
				
			}
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>
