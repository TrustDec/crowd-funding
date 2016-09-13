<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 文章列表页面接口
 */
class article_list
{
	public function index()
	{
		$cate_id = intval ( $GLOBALS ['request'] ['cate_id'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		$page = $page == 0 ? 1 : $page;
		
		// 分页
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		// 条件判断
		$type_id = 0;
		$where = '1=1 and a.is_delete=0 and a.is_effect=1 ';
		if ($cate_id > 0)
		{
			$where .= '  and a.cate_id=' . $cate_id;
		}
		
		$article_cates = $GLOBALS ['db']->getAllCached ( "select ac.*,fa.num from " . DB_PREFIX . "article_cate as ac left join (SELECT count(*) as num,cate_id from " . DB_PREFIX . "article where is_effect=1 and is_delete=0 GROUP BY cate_id) as fa on fa.cate_id=ac.id  where ac.is_effect=1 and  ac.is_delete=0  order by ac.sort asc" );
		foreach ( $article_cates as $k => $v )
		{
			$article_cates [$k] ['num'] = intval ( $v ['num'] );
			$article_cates [$k] ['url'] = url_wap ( 'article', array (
					'id' => $v ['id'] 
			) );
		}
		
		// wap文章分类栏
		$article_cates_list = $GLOBALS ['db']->getAllCached ( "select ac.id,ac.title from " . DB_PREFIX . "article_cate ac where ac.is_effect=1 and ac.is_delete=0 and ac.type_id=0 order by ac.id asc" );
		$cates_list = array ();
		foreach ( $article_cates_list as $k => $v )
		{
			$cates_list [$k] ['title'] = $v ['title'];
			$cates_list [$k] ['id'] = $v ['id'];
		}
		
		$temp_artilce_list = $GLOBALS ['db']->getAllCached ( "SELECT a.*,c.type_id,c.title as cate_name from " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_cate c on a.cate_id=c.id where $where order by a.update_time desc limit $limit" );
		// echo "SELECT a.*,c.type_id,c.title as cate_name from " . DB_PREFIX .
		// "article a LEFT JOIN " . DB_PREFIX . "article_cate c on
		// a.cate_id=c.id where $where order by a.update_time desc limit
		// $limit";
		// exit();
		$comment_count = $GLOBALS ['db']->getOne ( "SELECT count(*) from " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_cate c on a.cate_id=c.id where $where order by a.update_time asc" );
		$artilce_item = array ();
		$pattern = "/<img([^>]*)\/>/i";
		$replacement = "<img width=100% $1 />";
		foreach ( $temp_artilce_list as $k => $v )
		{
			// 最新智能头条type_id==0普通文章, type_id==1帮助文章，is_hot==1热门，is_week==1本周必读
			if ($v ['id'] > 0)
			{
				$artilce_item [$k] ['cate_title'] = $v ['title'];
				$artilce_item [$k] ['title'] = $v ['title'];
				// $artilce_item [$k] ['content'] = $v ['content'];
				$artilce_item [$k] ['content'] = preg_replace ( $pattern, $replacement, get_abs_img_root ( $v ['content'] ) );
				
				$artilce_item [$k] ['update_time'] = to_date ( $v ['update_time'] );
				$artilce_item [$k] ['cate_name'] = $v ['cate_name'];
			}
		}
		
		$data = responseSuccessInfo ( "", 0, "文章列表页面" );
		$data ['cates_list'] = $cates_list;
		$data ['artilce_item'] = $artilce_item;
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $comment_count / $page_size ) 
		);
		output ( $data );
	}
}
?>
