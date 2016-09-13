<?php
class entity_format_util
{
	/**
	 * 格式处理手机端才可解析
	 */
	public static function formateCates($cates = array ())
	{
		$cate = array ();
		$i = 0;
		foreach ( $cates as $k => $v )
		{
			$cate [$i] ['sort'] = $k;
			$cate [$i] ['name'] = $v;
			$i ++;
		}
		return $cate;
	}
}

?>