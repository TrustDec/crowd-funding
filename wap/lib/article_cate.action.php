<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'wap/app/page.php';
class article_cateModule{
	public function index()
	{	
			$id=intval($_REQUEST['id']);
		
		$article_cates = $GLOBALS['db']->getAllCached("select ac.*,fa.num from ".DB_PREFIX."article_cate as ac left join (SELECT count(*) as num,cate_id from ".DB_PREFIX."article where is_effect=1 and is_delete=0 GROUP BY cate_id) as fa on fa.cate_id=ac.id  where ac.is_effect=1 and  ac.is_delete=0  order by ac.sort asc");
		foreach($article_cates as $k=>$v){
			$article_cates[$k]['num']=intval($v['num']);
			$article_cates[$k]['url']=url_wap('article',array('id'=>$v['id']));
		
		}
	
		$type_id=0;
		$cate_name='';
		foreach($artilce_cate as $k=>$v)
		{
 			$artilce_cate[$k]['cate_id']=$v['id'];
 			$artilce_cate[$k]['titles']=$v['title'];
 			if($id>0&&$v['id']==$id){
 				$type_id=intval($v['type_id']);
 				$cate_name=$v['title'];
 			}
		}
		$GLOBALS['tmpl']->assign("cate_name",$cate_name);
		$GLOBALS['tmpl']->assign("artilce_cate",$artilce_cate);
		
		//分页
		$page_size = $GLOBALS['m_config']['page_size'];
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size	;
		
		//条件判断
		$where='1=1 and a.is_delete=0 and a.is_effect=1 ';
		if($id>0){
			$where.=' and c.type_id='.$type_id.'  and a.cate_id='.$id;
		}else{
			$where.=' and c.type_id=0 ';
		}

		//wap文章分类栏
		$article_cates_list=$GLOBALS['db']->getAllCached("select ac.id,ac.title from ".DB_PREFIX."article_cate ac where ac.is_effect=1 and ac.is_delete=0 and ac.type_id=0 order by ac.id asc");
		$cates_list=array();
		foreach ($article_cates_list as $k=>$v){
			$cates_list[$k]['title']=$v['title'];
			$cates_list[$k]['id']=$v['id'];
			$cates_list[$k]['url']=url_wap("article_cate",array('id'=>$v['id']));
			if($v['id']==intval($_REQUEST['id'])){
				$cates_list[$k]['status']=($k+1);
			}
		}
		$temp_artilce_list = $GLOBALS['db']->getAllCached("SELECT a.*,c.type_id,c.title as cate_name from ".DB_PREFIX."article a LEFT JOIN ".DB_PREFIX."article_cate c on a.cate_id=c.id where $where order by a.update_time desc limit $limit");
		$comment_count = $GLOBALS['db']->getOne("SELECT count(*) from ".DB_PREFIX."article a LEFT JOIN ".DB_PREFIX."article_cate c on a.cate_id=c.id where $where order by a.update_time asc");
		$artilce_item=array();
		foreach($temp_artilce_list as $k=>$v)
		{ 
			//最新智能头条type_id==0普通文章, type_id==1帮助文章，is_hot==1热门，is_week==1本周必读
			if($v['id']>0){
				$artilce_item[$k]['cate_title']=$v['title'];
				$artilce_item[$k]['seo_keyword']=$v['seo_keyword'];
				$artilce_item[$k]['title']=$v['title'];
				$artilce_item[$k]['content']=$v['content'];
				$artilce_item[$k]['update_time']=$v['update_time'];
				$artilce_item[$k]['cate_name']=$v['cate_name'];
				$artilce_item[$k]['cate_url']=url_wap('article_cate',array('id'=>$v['cate_id']));
				if($v['rel_url']=="")
 					$artilce_item[$k]['url']=url_wap('article',array('id'=>$v['id']));
				else
					$artilce_item[$k]['url']=$v['rel_url'];
			}
 		}
 	
 		$page = new Page($comment_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
 		unset($temp_artilce_list);
 		$GLOBALS['tmpl']->assign("cates_list",$cates_list);
		$GLOBALS['tmpl']->assign("artilce_list",$artilce_item);
		$GLOBALS['tmpl']->display("article_cate_index.html");
	}
}
?>