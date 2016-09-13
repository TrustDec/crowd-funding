<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class deal_updatedetail{
	public function index()
	{
		$root = array();
		$root['response_code'] = 1;
		$id = intval($GLOBALS ['request']['id']);
		$update_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_log where id = ".$id);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".intval($update_info['deal_id'])." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		if(!$deal_info)
		{
			app_redirect(url("index"));
		}		
		$deal_info = cache_deal_extra($deal_info);
		init_deal_page($deal_info);	
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where id = ".$id);				
		
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$online_time = online_date($v['create_time'],$deal_info['begin_time']);
			$log_list[$k]['online_time'] = $online_time['info'];
			if($online_time['key']!=$last_time_key)
			{
				$last_time_key = $log_list[$k]['online_time_key'] = $online_time['key'];				
			}
			
			
			$log_list[$k]['comment_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where log_id = ".$v['id']." and deal_id = ".$deal_info['id']);
			
			$page_size = $GLOBALS['m_config']['page_size'];
			$page = intval($GLOBALS ['request']['p']);
			if($page==0)$page = 1;		
			$limit = (($page-1)*$page_size).",".$page_size;
			$log_list[$k]['comment_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_comment where log_id = ".$v['id']." and deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);

		//	require APP_ROOT_PATH.'app/Lib/page.php';
		//	$page = new Page($log_list[$k]['comment_count'],$page_size);   //初始化分页对象 		
		//	$p  =  $page->show();
		//	$GLOBALS['tmpl']->assign('pages',$p);	
			$root['page'] = array("page"=>$page,"page_total"=> ceil($log_list[$k]['comment_count']/$page_size),"page_size"=>intval($page_size),'total'=>intval($log_list[$k]['comment_count']));
		}
		
		$root['log_list']= $log_list;
		output($root);
	}
}
?>
