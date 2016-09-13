<?php
//底部文章
class user_level_auto_cache extends auto_cache{
	public function load($param)
	{
		$param=array();
		$key = $this->build_key(__CLASS__,$param);
 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$user_level_array = $GLOBALS['cache']->get($key);
		if($user_level_array === false)
		{
			$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by point asc");
			$user_level_array=array();
			$template=app_conf("TEMPLATE");
			$template_level_icon_put_dir="./app/Tpl/".$template."/images/level_icon/";
			$template_level_icon_open_dir=APP_ROOT_PATH."/app/Tpl/".$template."/images/level_icon/";
			$no_leverl_icon=$template_level_icon_put_dir."level.png";
			foreach($user_level as $k=>$v){
				$user_level_array[$v['id']]=$v;
				$user_level_array[$v['id']]['level_num']=$k;
				if($v['icon'] =='')
				{
					$put_dir=$template_level_icon_put_dir."level".$k.".png";
					$open_dir_val=$template_level_icon_open_dir."level".$k.".png";
					if(fopen($open_dir_val,'r'))
						$user_level_array[$v['id']]['icon']=$put_dir;
					else
						$user_level_array[$v['id']]['icon']=$no_leverl_icon;
				}
 			}
 			$user_level_array[0]=array('icon'=>$no_leverl_icon);
 			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$user_level_array);
		}
		
		return $user_level_array;
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