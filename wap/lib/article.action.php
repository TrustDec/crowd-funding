<?php
class articleModule{
	public function index(){
		//输出文章
		$id = intval($_REQUEST['id']);
		$article = $GLOBALS['db']->getRow("select a.*,ac.type_id from ".DB_PREFIX."article as a left join ".DB_PREFIX."article_cate as ac on ac.id=a.cate_id where a.id=$id");
		
		$wx=array();
		$wx['img_url']=$article['icon'];
		$wx['title']=addslashes($article['title']);
		$wx['desc']=addslashes($article['brief']);
		$GLOBALS['tmpl']->assign('wx',$wx);
		$cate_id=$article['cate_id'];
		
		
		$article_cates = $GLOBALS['db']->getAllCached("select ac.*,fa.num from ".DB_PREFIX."article_cate as ac left join (SELECT count(*) as num,cate_id from ".DB_PREFIX."article where is_effect=1 and is_delete=0 GROUP BY cate_id) as fa on fa.cate_id=ac.id  where ac.is_effect=1 and  ac.is_delete=0  order by ac.sort asc");
		foreach($article_cates as $k=>$v){
			$article_cates[$k]['num']=intval($v['num']);
			$article_cates[$k]['url']=url_wap('article',array('id'=>$v['id']));
		}
		$artilce_cate_new=array();
		foreach($artilce_cate as $k=>$v)
		{
			if($v['type_id']==$article['type_id']&&$v['id']!=$cate_id&&$v['num']>0){
				$artilce_cate_new[$k]['cate_id']=$v['id'];
				$artilce_cate_new[$k]['titles']=$v['title'];
				$artilce_cate_new[$k]['url']=url_wap('article_cate',array('id'=>$v['id']));
			}
		
		}
		
		$GLOBALS['tmpl']->assign("other_cate",$artilce_cate_new);
		//文章详细页面最新更新(控制最新的10条)
		$temp_article_list=$GLOBALS['db']->getAllCached("SELECT a.*,c.type_id from ".DB_PREFIX."article a LEFT JOIN ".DB_PREFIX."article_cate c on a.cate_id=c.id where 1=1 and a.is_delete=0 and a.is_effect=1 and c.type_id=".$article['type_id']." and a.cate_id=$cate_id and a.id!=$id  order by update_time desc limit 0,5");
		
		$article_list=array();
		foreach ($temp_article_list as $k=>$v){
			//最新更新
			$article_list[$k]['cate_title']=$v['title'];
			if($v['rel_url']=="")
				$article_list[$k]['url']=url_wap('article',array('id'=>$v['id']));
			else
				$article_list[$k]['url']=$v['rel_url'];
		}
		unset($temp_article_list);
		
		$GLOBALS['tmpl']->assign("article",$article);
		$GLOBALS['tmpl']->assign("article_list",$article_list);
   		$GLOBALS['tmpl']->display("article_index.html");
	}
}
?>