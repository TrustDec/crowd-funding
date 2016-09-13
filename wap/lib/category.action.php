<?php
/*项目分类*/
class categoryModule{
	public function index()
	{	
		$GLOBALS['tmpl']->assign("page_title","全部分类");
		$cates =$GLOBALS['db']->getAll("select *from ".DB_PREFIX."deal_cate where pid =0 order by sort asc");
		$GLOBALS['tmpl']->assign("cates",$cates);
		$cates_list =$GLOBALS['db']->getAll("select *from ".DB_PREFIX."deal_cate where pid !=0 order by sort asc");
		$GLOBALS['tmpl']->assign("cates_list",$cates_list);
		$GLOBALS['tmpl']->display("category.html");
	}	
}
?>
