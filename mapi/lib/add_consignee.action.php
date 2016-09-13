<?php
// +----------------------------------------------------------------------
// | Fanwe 方维 用户添加配送地址
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class add_consignee
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
			$root['response_code'] = 1;
			
			if(!$user)
			{
			//	$data['html'] = $GLOBALS['tmpl']->display("inc/user_login_box.html","",true);			
				$data['status'] = 0;
			}
			else
			{
				$GLOBALS['tmpl']->caching = true;
				$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
				if (!$GLOBALS['tmpl']->is_cached('inc/add_consignee.html', $cache_id))
				{		
					$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
				//	$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
					$root['region_lv2'] = $region_lv2;
				}			
			//	$data['html'] = $GLOBALS['tmpl']->display("inc/add_consignee.html",$cache_id,true);			
				$data['status'] = 1;
				
			}
			ajax_return($data);
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>
