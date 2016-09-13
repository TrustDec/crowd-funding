<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
class index_catesModule{
	public function index()
	{
		$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
		if(!$cate_list)
		{	
			$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where pid =0 order by sort asc");
			set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
		}
		$invest_status=intval(app_conf("INVEST_STATUS"));
		if($invest_status ==2)
			$cate_url_type=1;
		else
			$cate_url_type=0;
			
		foreach($cate_list as $k=>$v)
		{
			$cate_list[$k]['url_type']=$cate_url_type;
		}
		//print_r($cate_list);
		$GLOBALS['tmpl']->assign('cate_list',$cate_list);
		$GLOBALS['tmpl']->display("index_cates.html");
	}
	
}
?>