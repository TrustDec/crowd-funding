<?php
//提现手续费
class user_carry_config_auto_cache extends auto_cache{
	public function load($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$config_list = $GLOBALS['cache']->get($key);
		if($config_list === false)
		{
			$config_list = array();
			if((int)$param['vip_id'] > 0 && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_carry_config where vip_id=".(int)$param['vip_id']."") > 0){
				$config_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_carry_config where vip_id=".(int)$param['vip_id']." order by max_price ASC,id ASC");
			}
			else
				$config_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_carry_config where vip_id=0 order by max_price ASC,id ASC");
			
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$config_list);
		}
		return $config_list;
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