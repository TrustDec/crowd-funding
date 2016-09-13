<?php
//商城的导航dz_chh
class score_cates_auto_cache extends auto_cache{
	public function load($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$return = $GLOBALS['cache']->get($key);
		if($return === false)
		{
			$cates_list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."goods_cate WHERE is_effect=1 and is_delete = 0 and pid= 0 order by sort asc");
			$cates_blist=array();
			foreach($cates_list as $k=>$v)
			{
				$cates_blist[$v['id']]=$v;
				//子分类
				$sub_list=$GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."goods_cate WHERE is_effect=1 and is_delete = 0 and pid= ".intval($v['id'])." order by sort asc");
				$cates_blist[$v['id']]['sub_list']=$sub_list;
				
				//当前大分类id加子分类id
				$cur_cate_ids=array();
				$cur_cate_ids[]=$v['id'];
				foreach($sub_list as $kk=>$vv)
				{
					$cur_cate_ids[]=$vv["id"];
				}
				$cates_blist[$v['id']]['cur_cate_ids']=$cur_cate_ids;
			}
			//所有子分类
			$cates_sublist_all= $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."goods_cate WHERE is_effect=1 and is_delete =0 and pid > 0 order by sort asc");
			$cates_sublist=array();
			foreach($cates_sublist_all as $k=>$v)
			{
				$cates_sublist[$v['id']]=$v;
			}
			$return=array();
			$return['cates_blist']=$cates_blist;
			$return['cates_sublist']=$cates_sublist;
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$return);
		}
		return $return;
	}
	public function rm($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->clear();
	}
}
?>