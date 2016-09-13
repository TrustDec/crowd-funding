<?php
// +----------------------------------------------------------------------
// | Fanwe 方维用户项目编辑
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class project_edit
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
			$id = intval($_REQUEST['id']);
			$deal_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and user_id = ".$user_id);
			if($deal_item)
			{
				$root['page_title'] =$deal_item['name'];
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
				$root['region_lv2'] =$region_lv2;
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
					$root['region_lv3'] =$region_lv3;
				}
				
				$deal_item['faq_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_faq where deal_id = ".$deal_item['id']." order by sort asc");
				$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
				$root['cate_list'] =$cate_list;
				$root['deal_item'] =$deal_item;
			}	
			else
			{
				app_redirect_preview();
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
