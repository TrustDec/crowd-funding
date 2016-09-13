<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class ConfAction extends CommonAction{
	public function index()
	{
		$conf_res = M("Conf")->where("is_effect = 1 and is_conf = 1")->order("group_id asc,sort asc")->findAll();
		foreach($conf_res as $k=>$v)
		{
			$v['value'] = htmlspecialchars($v['value']);
			if($v['name']=='TEMPLATE')
			{
				
				//输出现有模板文件夹
				$directory = APP_ROOT_PATH."app/Tpl/";
				$dir = @opendir($directory);
			    $tmpls     = array();
			
			    while (false !== ($file = @readdir($dir)))
			    {
			    	if($file!='.'&&$file!='..')
			        $tmpls[] = $file;
			    }
			    @closedir($dir);
				//end
				
				$v['input_type'] = 1;
				$v['value_scope'] = $tmpls;
			}
			elseif($v['name']=='SHOP_LANG')
			{
				//输出现有语言包文件夹
				$directory = APP_ROOT_PATH."app/Lang/";
				$dir = @opendir($directory);
			    $tmpls     = array();
			
			    while (false !== ($file = @readdir($dir)))
			    {
			    	if($file!='.'&&$file!='..')
			        $tmpls[] = $file;
			    }
			    @closedir($dir);
				//end
				
				$v['input_type'] = 1;
				$v['value_scope'] = $tmpls;
			}
			else
			$v['value_scope'] = explode(",",$v['value_scope']);
			$conf[$v['group_id']][] = $v;
		}
		$this->assign("conf",$conf);
		$this->display();
	}
	
	public function update()
	{
		$conf_res = M("Conf")->where("is_effect = 1 and is_conf = 1")->findAll();
		foreach($conf_res as $k=>$v)
		{
			conf($v['name'],$_REQUEST[$v['name']]);
			if($v['name']=='URL_MODEL'&&$v['value']!=$_REQUEST[$v['name']])
			{
				clear_dir_file(get_real_path()."public/runtime/app/data_caches/");	
				clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");	
				clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");	
				
				clear_dir_file(get_real_path()."public/runtime/app/data_caches/");	
				clear_dir_file(get_real_path()."public/runtime/data/page_static_cache/");
				clear_dir_file(get_real_path()."public/runtime/data/dynamic_avatar_cache/");	
			}
		}
			$sys_configs_array = array();
			//开始写入配置文件
			$sys_configs = M("Conf")->findAll();
			$config_str = "<?php\n";
			$config_str .= "return array(\n";
			foreach($sys_configs as $k=>$v)
			{
				$config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
				$sys_configs_array[$v['name']] = addslashes($v['value']);
			}
			$config_str.=");\n ?>";
			$filename = get_real_path()."public/sys_config.php";
			
		    if (!$handle = fopen($filename, 'w')) {
			     $this->error(l("OPEN_FILE_ERROR").$filename);
			}
			
			    
			if (fwrite($handle, $config_str) === FALSE) {
			     $this->error(l("WRITE_FILE_ERROR").$filename);
			}
			
	    fclose($handle);
			

			
		save_log(l("CONF_UPDATED"),1);		
		//clear_cache();
		write_timezone();
		//var_dump($sys_configs_array);exit;
		create_app_js($sys_configs_array);
		$this->success(L("UPDATE_SUCCESS"));
	}
	
	
	public function mobile()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$config = M("MConfig")->order("sort asc")->findAll();
		$wx_appid='';
		$wx_secrit='';
		$wx_url='';
		foreach($config as $k=>$v){
			if($v['code']=='wx_appid'){
				$wx_appid=$v['val'];
				continue;
			}
			if($v['code']=='wx_secrit'){
				$wx_secrit=$v['val'];
				continue;
			}
			if($v['type']==4){
				$config[$k]['value_scope']=explode(',',$v['value_scope']);
 			}else{
 				$config[$k]['value_scope']='';
 			}
		}
		if(!empty($wx_appid)&&!empty($wx_secrit)){
			require APP_ROOT_PATH."system/utils/weixin.php";
			$weixin=new weixin($wx_appid,$wx_secrit,get_domain().APP_ROOT."/wap");
			$wx_url=$weixin->scope_get_code();
 		}
 		$this->assign('wx_url',$wx_url);
  		$this->assign("config",$config);
		$this->display();
	}
	
	public function savemobile()
	{
		foreach($_POST as $k=>$v)
		{
			M("MConfig")->where("code='".$k."'")->setField("val",trim($v));
		}
		$this->success("保存成功");
	}	
	
}
?>