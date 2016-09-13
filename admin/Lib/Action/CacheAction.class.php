<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class CacheAction extends CommonAction{

	public function clear_admin()
	{
		set_time_limit(0);
		es_session::close();
		$this->clear_admin_file();
 		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	private function clear_admin_file(){
		clear_dir_file(get_real_path()."public/runtime/admin/Cache/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");		
		clear_dir_file(get_real_path()."public/runtime/admin/Temp/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Logs/");	
		@unlink(get_real_path()."public/runtime/admin/~app.php");
		@unlink(get_real_path()."public/runtime/admin/~runtime.php");
		@unlink(get_real_path()."public/runtime/admin/lang.js");
		@unlink(get_real_path()."public/runtime/app/config_cache.php");	
	}
	
	public function clear_parse_file()
	{
		set_time_limit(0);
		es_session::close();
		
		$this->clear_parse_file_fun();
		
		header("Content-Type:text/html; charset=utf-8");
		if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File"&&$GLOBALS['distribution_cfg']['CACHE_TYPE']!="Db")
		{
			exit("<div style='line-height:25px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS').",如未生效，请进入缓存管理平台重置缓存</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");		
		}
		else
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	private function clear_parse_file_fun(){
		clear_dir_file(get_real_path()."public/runtime/statics/");	
		
		clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");		
		clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
		
		clear_dir_file(get_real_path()."public/runtime/wap/tpl_caches/");		
		clear_dir_file(get_real_path()."public/runtime/wap/tpl_compiled/");
		
		clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_caches/");		
		clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_compiled/");
	}
	
	public function clear_data()
	{
		set_time_limit(0);
		es_session::close();
		$this->clear_data_file();
		
		header("Content-Type:text/html; charset=utf-8");
		if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File"&&$GLOBALS['distribution_cfg']['CACHE_TYPE']!="Db")
		{
			exit("<div style='line-height:25px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS').",如未生效，请进入缓存管理平台重置缓存</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
		}
		else
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	
	private function clear_data_file(){
		@unlink(get_real_path()."public/runtime/app/deal_cate_conf.js");	
		clear_dir_file(get_real_path()."public/runtime/app/deal_region_conf/");
		if(intval($_REQUEST['is_all'])==0)
		{
			//数据缓存
			clear_dir_file(get_real_path()."public/runtime/app/data_caches/");				
			clear_dir_file(get_real_path()."public/runtime/app/db_caches/");
			
			clear_dir_file(get_real_path()."public/runtime/mapi/data_caches/");				
			 
			$GLOBALS['cache']->clear();
			clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
			
			clear_dir_file(get_real_path()."public/runtime/wap/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/wap/tpl_compiled/");
			
			clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_compiled/");
			@unlink(get_real_path()."public/runtime/app/lang.js");				
			
			//删除相关未自动清空的数据缓存
			clear_auto_cache("page_image");
		}
		else
		{

			clear_dir_file(get_real_path()."public/runtime/data/");	
			clear_dir_file(get_real_path()."public/runtime/app/data_caches/");				
			clear_dir_file(get_real_path()."public/runtime/app/db_caches/");
			clear_dir_file(get_real_path()."public/runtime/mapi/data_caches/");		
			$GLOBALS['cache']->clear();
			clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
			
			clear_dir_file(get_real_path()."public/runtime/wap/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/wap/tpl_compiled/");
			
			clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_caches/");		
			clear_dir_file(get_real_path()."public/runtime/wap_app/tpl_compiled/");
			@unlink(get_real_path()."public/runtime/app/lang.js");	
			
			//后台
			clear_dir_file(get_real_path()."public/runtime/admin/Cache/");	
			clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");		
			clear_dir_file(get_real_path()."public/runtime/admin/Temp/");	
			clear_dir_file(get_real_path()."public/runtime/admin/Logs/");	
			@unlink(get_real_path()."public/runtime/admin/~app.php");
			@unlink(get_real_path()."public/runtime/admin/~runtime.php");
			@unlink(get_real_path()."public/runtime/admin/lang.js");
			@unlink(get_real_path()."public/runtime/app/config_cache.php");	
			
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_log set comment_data_cache = '',deal_info_cache=''");
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = ''");
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_comment set deal_info_cache=''");

		}
	}

	
	public function clear_image()
	{
		set_time_limit(0);
		es_session::close();
		
		$this->clear_image_file_fun();
		
		header("Content-Type:text/html; charset=utf-8");
		
		if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File"&&$GLOBALS['distribution_cfg']['CACHE_TYPE']!="Db")
		{
			exit("<div style='line-height:25px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS').",如未生效，请进入缓存管理平台重置缓存</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
		}
		else
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	private function clear_image_file_fun(){
		$path  = APP_ROOT_PATH."public/attachment/";
		$this->clear_image_file($path);
		$path  = APP_ROOT_PATH."public/images/";
		$this->clear_image_file($path);
		
		$qrcode_path = APP_ROOT_PATH."public/images/qrcode/";
		$this->clear_qrcode($qrcode_path);
	
		clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");		
		clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
	}
	
	public function clear_all(){
		set_time_limit(0);
		es_session::close();
		$this->clear_admin_file();

		$this->clear_parse_file_fun();
		
		$this->clear_data_file();
		
		$this->clear_image_file_fun();
		$this->updateRegionJS();
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	private function updateRegionJS()
	{
		$jsStr = "var regionConf = ".$this->getRegionJS();
		$path = get_real_path()."public/region.js";
		@file_put_contents($path,$jsStr);
	}
	
	private function getRegionJS()
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("region_level = 2")->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
	private function getRegionChildJS($pid)
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("pid=".$pid)->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
	
	private function clear_qrcode($path)
	{
	
	   if ( $dir = opendir( $path ) )
	   {
	            while ( $file = readdir( $dir ) )
	            {
	                $check = is_dir( $path. $file );
	                if ( !$check )
	                {
	                    @unlink ( $path . $file);                       
	                }
	                else 
	                {
	                 	if($file!='.'&&$file!='..')
	                 	{
	                 		$this->clear_qrcode($path.$file."/");              			       		
	                 	} 
	                 }           
	            }
	            closedir( $dir );
	            return true;
	   }
	}
	
	private function clear_image_file($path)
	{
	   if ( $dir = opendir( $path ) )
	   {
	            while ( $file = readdir( $dir ) )
	            {
	                $check = is_dir( $path. $file );
	                if ( !$check )
	                {
	                	if(preg_match("/_(\d+)x(\d+)/i",$file,$matches))
	                    @unlink ( $path . $file);                       
	                }
	                else 
	                {
	                 	if($file!='.'&&$file!='..')
	                 	{
	                 		$this->clear_image_file($path.$file."/");              			       		
	                 	} 
	                 }           
	            }
	            closedir( $dir );
	            return true;
	   }
	}
}
?>