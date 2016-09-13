<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'app/Lib/page.php';
class referralModule extends BaseModule
{
	public function index()
	{	
      	
        $user_id=intval($GLOBALS['user_info']['id']);
		if(!$user_id)
		{
			app_redirect(url("index"));	
		}
		
		//返利列表
		$page = intval($_REQUEST['p'])>0?intval($_REQUEST['p']):1;
		$referrals_count=$GLOBALS['db']->getRow("select count(*) as count,sum(score) as total_score from ".DB_PREFIX."referrals where user_id= ".$user_id." ");
		if($referrals_count)
		{
			$page_size = ACCOUNT_PAGE_SIZE;
			$limit = (($page-1)*$page_size).",".$page_size;
			$sql="select r.*,u.create_time as register_time from ".DB_PREFIX."referrals as r "
				 ." left join ".DB_PREFIX."user as u on u.id=r.user_id "
				 ." where user_id=".$user_id." order by id desc limit ".$limit;
			
			$referrals_list=$GLOBALS['db']->getAll($sql);
			$page=new Page($referrals_count['count'],$page_size);//初始化分页类
			$p=$page->show();
			$GLOBALS['tmpl']->assign("pages",$p);
		}
		
		//邀请连接
		$referrals_url = get_domain().APP_ROOT."/";
		if($GLOBALS['user_info'])
		$referrals_url .= "?ref=".base64_encode(intval($user_id));
		$GLOBALS['tmpl']->assign("referrals_url",$referrals_url);
		
		$GLOBALS['tmpl']->assign('referrals_list',$referrals_list);
		$GLOBALS['tmpl']->assign('referrals_count',$referrals_count);
		$GLOBALS['tmpl']->assign("page_title","我的邀请");
      	$GLOBALS['tmpl']->display("referrals_index.html");
	}
	
}
?>