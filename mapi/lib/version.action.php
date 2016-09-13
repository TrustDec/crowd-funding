<?php
class version
{
	public function index()
	{
	
		$site_url = str_replace("/mapi", "", get_domain().APP_ROOT)."/";//站点域名;
		
		//客服端手机类型dev_type=android
		$dev_type = $GLOBALS['request']['dev_type'];
		$version = $GLOBALS['request']['version'];//
		
		$root = array();
		if ($dev_type == 'android'){
			$root['serverVersion'] = $GLOBALS['m_config']['android_version'];//android版本号
			if ($version < $root['serverVersion']){	
				/*为了，计算文件大小，只能使用本地的
				if (stripos($GLOBALS['m_config']['android_filename'],'http://')){
					$root['filename'] = $GLOBALS['m_config']['android_filename'];
				}else{
					$root['filename'] = $site_url.$GLOBALS['m_config']['android_filename'];//android下载包名
				}
				*/
				//$root['filesize2'] = filesize($GLOBALS['m_config']['android_filename']);
				$root['filename'] = $site_url.$GLOBALS['m_config']['android_filename'];//android下载包名
				$root['android_upgrade'] = $GLOBALS['m_config']['android_upgrade'];//android版本升级内容
				if(file_exists(APP_ROOT_PATH.$GLOBALS['m_config']['android_filename']))
				{
					$root['hasfile'] = 1;
					$root['filesize'] = filesize(APP_ROOT_PATH.$GLOBALS['m_config']['android_filename']);
					$root['has_upgrade'] = 1;//1:可升级;0:不可升级
				}
				else 
				{
					$root['hasfile'] = 0;
					$root['filesize'] = 0;
					$root['has_upgrade'] = 0;//1:可升级;0:不可升级
				}
				
			}else{
				$root['hasfile'] = 0;
				$root['has_upgrade'] = 0;//1:可升级;0:不可升级
			}
			$root['response_code'] = 1;
		}else if ($dev_type == 'ios'){
			$root['serverVersion'] = $GLOBALS['m_config']['ios_version'];//ios版本号
			
			if ($version < $root['serverVersion']){
				$root['ios_down_url'] = $site_url.$GLOBALS['m_config']['ios_down_url'];//ios下载地址
				$root['ios_upgrade'] = $GLOBALS['m_config']['ios_upgrade'];//ios版本升级内容
				$root['has_upgrade'] = 1;//1:可升级;0:不可升级
			}else{
				$root['has_upgrade'] = 0;//1:可升级;0:不可升级
			}
			
			$root['response_code'] = 1;
		}else{
			$root['response_code'] = 0;
		}
				
		output($root);
	}
}
?>