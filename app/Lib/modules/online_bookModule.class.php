<?php
// +----------------------------------------------------------------------
// | 问卷调查
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/page.php';
class online_bookModule extends BaseModule
{
	public function index()
	{
		$GLOBALS['tmpl']->display("online_book.html");
	}
}
?>