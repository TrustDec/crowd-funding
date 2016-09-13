<?php
//商城的导航dz_chh
class admin_nav_auto_cache extends auto_cache{
	public function load($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$return = $GLOBALS['cache']->get($key);
		//$return = false;
		if($return === false)
		{
			//start
			$navs = require_once APP_ROOT_PATH."system/admnav_cfg.php";
			$navs = deal_admin_nav($navs);
			
			$modules = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."role_module where is_delete=0 and is_effect=1 ");
			$module_list = array();
			foreach($modules as $k=>$v){
				unset($v['name']);
				$module_list[$v['id']] = $v;
				$module_list[$v['module']] = $v;
			}

			$role_access_list  = $GLOBALS['db']->getAll("select * from  ".DB_PREFIX."role_access where role_id=".$param['id']);
			$access_list = array();
			foreach($role_access_list as $k=>$v){
				$v['module']=$module_list[$v['module_id']]['module'];
				$access_list[$module_list[$v['module_id']]['module']][]=$v;
			}
 			foreach($navs as $k=> $v) {
				foreach ($v['groups'] as $gk => $gv) {
					foreach ($gv['nodes'] as $nk => $nv) {
						if($gk =='index'){
							$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 1;
						}else{
							
							foreach($access_list[$nv['module']] as $k1=>$v1){
								if(($nv['module'] == $v1['module'])&&($v1['node_id']==0)){
									$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 1;
								}else{
									if ($v1['module'] == $nv['module']) {
										$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 1;
									}

								}
							}
						}
					}
				}
			}
			foreach($navs as $k=> $v){
				foreach ($v['groups'] as $gk => $gv) {
					foreach ($gv['nodes'] as $nk => $nv) {
						if(!isset($nv['module_auth'])){
							unset($navs[$k]['groups'][$gk]['nodes'][$nk]);
							if(count($navs[$k]['groups'][$gk]['nodes'])==0){
								unset($navs[$k]['groups'][$gk]);
								if(count($navs[$k]['groups'])==0){
									unset($navs[$k]);
								}
							}
						}
					}
				}
			}
   			//end
			$return=array();
			$return= $navs;

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