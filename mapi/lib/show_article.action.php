<?php
class show_article
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['id'] );
		$sql = "select id, title, content, create_time from " . DB_PREFIX . "article where is_effect = 1 and is_delete = 0 and id =" . $id;
		$article = $GLOBALS ['db']->getRow ( $sql );
		if ($article)
		{
			$root = array ();
			$root ['id'] = $article ['id'];
			$root ['title'] = $article ['title'];
			$root ['create_time'] = to_date ( $article ['create_time'] );
			
			//$root ['content'] = get_abs_img_root(format_html_content_image($article ['content'],150,100));
			$pattern = "/<img([^>]*)\/>/i";
			$replacement = "<img $1 width=100% />";
			$root ['content'] = preg_replace ( $pattern, $replacement, get_abs_img_root ($article ['content']));
			
			$root ['response_code'] = 1;
		} else
		{
			$root = array ();
			$root ['id'] = '';
			$root ['title'] = '';
			$root ['create_time'] = '';
			$root ['content'] = '';
			$root ['response_code'] = 0;
		}
		output ( $root );
	}
}

?>