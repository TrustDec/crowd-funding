<?php
//商城的导航dz_chh
class admin_role_auto_cache extends auto_cache{
	public function load($param)
	{
		
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$return = $GLOBALS['cache']->get($key);
		if($return === false)
		{
			//start
			$navs = require_once APP_ROOT_PATH."system/admnav_cfg.php";
			$navs = deal_admin_nav($navs);
			
 			$role_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."role_node  where is_delete=0 and is_effect=1 order by id");
			$role_access_list  = $GLOBALS['db']->getAll("select * from  ".DB_PREFIX."role_access where role_id=".$param['id']."  order by id");
			//$nodes = M("RoleModule")->where("is_delete=0 and is_effect=1 and module <> 'Index' and module = '".$nv['module']."' ")->order("module asc")->findAll();
			$modules = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."role_module where is_delete=0 and is_effect=1 order by id");
			$node_list =array();
			$access_list = array();
			$module_list = array();
			foreach($modules as $k=>$v){
				unset($v['name']);
				$module_list[$v['id']] = $v;
				$module_list[$v['module']] = $v;
			}
 			foreach($role_list as $k=>$v){
				$node_list[$module_list[$v['module_id']]['module']][]=$v;
			}
			foreach($role_access_list as $k=>$v){
				$access_list[$module_list[$v['module_id']]['module']][]=$v;
			}
			foreach($navs as $k=> $v){
				foreach($v['groups'] as $gk=> $gv){
 					foreach($gv['nodes'] as $nk=> $nv){
  						$navs[$k]['groups'][$gk]['nodes'][$nk]['node_list']= $node_list[$nv['module']];
 						foreach($module_list[$nv['module']] as $mk=>$mv){
							$navs[$k]['groups'][$gk]['nodes'][$nk][$mk] = $mv;
						}
 					}
				}
 			}
 			foreach($navs as $k=> $v) {
				foreach ($v['groups'] as $gk => $gv) {
					if($gk =='index'){
						unset($navs[$k]['groups'][$gk]);
						unset($gv);
					}

					foreach ($gv['nodes'] as $nk => $nv) {
						//  M("RoleAccess")->where("role_id=".$param['id']." and action_id=".$nv['action_id']." and node_id =0")->count()>0
						foreach($access_list[$nv['module']] as $k1=>$v1){
							if(($nv['id'] == $v1['module_id'])&&($v1['node_id']==0)){
 								$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 1;
							}else{
								$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 0;
							}
						}
						
						foreach ($nv['node_list'] as $nlk => $nlv) {
							//if(M("RoleAccess")->where("role_id=".$param['id']." and action_id=".$nv['action_id']." and node_id =".$nlv['id'])->count()>0)
							foreach ($access_list[$nv['module']] as $k2 => $v2) {
								if ($v2['node_id'] == $nlv['id']) {
									$navs[$k]['groups'][$gk]['nodes'][$nk]['node_list'][$nlk]['node_auth'] = 1;
								} else {
									if($navs[$k]['groups'][$gk]['nodes'][$nk]['node_list'][$nlk]['node_auth']!=1){
										$navs[$k]['groups'][$gk]['nodes'][$nk]['node_list'][$nlk]['node_auth'] = 0;
									}

								}
							}
								//if(M("RoleAccess")->where("role_id=".$param['id']." and action_id=".$nv['action_id']." and node_id <>0")->count()==
								//   M("RoleNode")->where("is_delete=0 and is_effect=1 and action_id=".$nv['action_id'])->count()&&
								//   M("RoleNode")->where("is_delete=0 and is_effect=1 and action_id=".$nv['action_id'])->count() != 0)

							$access_count = 0;
							foreach($access_list[$nv['module']] as $k_1=>$v_1){
								if($v_1['node_id']!=0){
									$access_count++;
								}
							}
							$node_count =intval(count($node_list[$nv['module']]));
							if (($access_count==$node_count)&&$node_count>0) {
								$navs[$k]['groups'][$gk]['nodes'][$nk]['node_list'][$nlk]['check_all'] = 1;
								//$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 1;
							} else {
								$navs[$k]['groups'][$gk]['nodes'][$nk]['node_list'][$nlk]['check_all'] = 0;
								//$navs[$k]['groups'][$gk]['nodes'][$nk]['module_auth'] = 0;
							}
 						}

					}
				}
			}
   			//end
			$return=array();
			$return = $navs;
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