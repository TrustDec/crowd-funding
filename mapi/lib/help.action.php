<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class help
{
	public function index()
	{	
		$help_item = $GLOBALS['db']->getRow("select title,content,url from ".DB_PREFIX."help where id=1");
		output ( $help_item );
	}

}
?>