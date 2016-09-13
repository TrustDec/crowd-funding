<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

//前后台加载的函数库


 
//获取真实路径
function get_real_path()
{
	return APP_ROOT_PATH;
}
//获取GMTime
function get_gmtime()
{
	return (time() - date('Z'));
}

function to_date($utc_time, $format = 'Y-m-d H:i:s') {
	if (empty ( $utc_time )) {
		return '';
	}
	$timezone = intval(app_conf('TIME_ZONE'));
	$time = $utc_time + $timezone * 3600; 
	return date ($format, $time );
}


function to_timespan($str, $format = 'Y-m-d H:i:s')
{
	$timezone = intval(app_conf('TIME_ZONE'));
	//$timezone = 8; 
	$time = intval(strtotime($str));
        
	if($time!=0)
	$time = $time - $timezone * 3600;
    return $time;
}


//获取客户端IP
function get_client_ip() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}

//过滤注入
function filter_injection(&$request)
{
	$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
	foreach($request as $k=>$v)
	{
				if(preg_match($pattern,$k,$match))
				{
						die("SQL Injection denied!");
				}
		
				if(is_array($v))
				{					
					filter_injection($request[$k]);
				}
				else
				{					
 					if(preg_match($pattern,$v,$match))
					{
						die("SQL Injection denied!");
					}					
				}
	}
	
}

function filter_ma_request(&$str){
	$search = array("../","\n","%","\r","\t","\r\n","'","<",">","\"");
 	return str_replace($search,"",$str);
}

//过滤请求
function filter_request(&$request)
{
		if(MAGIC_QUOTES_GPC)
		{
			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					filter_request($v);
				}
				else
				{
					$request[$k] = stripslashes(trim($v));
				}
			}
		}
		
}

function adddeepslashes(&$request)
{

			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					adddeepslashes($v);
				}
				else
				{
					$request[$k] = addslashes(trim($v));
				}
			}		
}


function quotes($content)
{
	//if $content is an array
	if (is_array($content))
	{
		foreach ($content as $key=>$value)
		{
			//$content[$key] = mysql_real_escape_string($value);
			$content[$key] = addslashes($value);
		}
	} else
	{
		//if $content is not an array
		//$content=mysql_real_escape_string($content);
		$content=addslashes($content);
	}
	return $content;
}


//request转码
function convert_req(&$req)
{
	foreach($req as $k=>$v)
	{
		if(is_array($v))
		{
			convert_req($req[$k]);
		}
		else
		{
			if(!is_u8($v))
			{
				$req[$k] = iconv("gbk","utf-8",$v);
			}
		}
	}
}

function is_u8($string)
{
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}


//清除缓存
function clear_cache()
{
		//系统后台缓存
		clear_dir_file(get_real_path()."public/runtime/admin/Cache/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");		
		clear_dir_file(get_real_path()."public/runtime/admin/Temp/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Logs/");	
		@unlink(get_real_path()."public/runtime/admin/~app.php");
		@unlink(get_real_path()."public/runtime/admin/~runtime.php");
		@unlink(get_real_path()."public/runtime/admin/lang.js");
		@unlink(get_real_path()."public/runtime/app/config_cache.php");	
		
		
		//数据缓存
		clear_dir_file(get_real_path()."public/runtime/app/data_caches/");				
		clear_dir_file(get_real_path()."public/runtime/app/db_caches/");
		$GLOBALS['cache']->clear();
		clear_dir_file(get_real_path()."public/runtime/data/");

		//模板页面缓存
		clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");		
		clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
		@unlink(get_real_path()."public/runtime/app/lang.js");	
		
		//脚本缓存
		clear_dir_file(get_real_path()."public/runtime/statics/");		
			
				
		
}
function clear_dir_file($path)
{
   if ( $dir = opendir( $path ) )
   {
            while ( $file = readdir( $dir ) )
            {
                $check = is_dir( $path. $file );
                if ( !$check )
                {
                    @unlink( $path . $file );                       
                }
                else 
                {
                 	if($file!='.'&&$file!='..')
                 	{
                 		clear_dir_file($path.$file."/");              			       		
                 	} 
                 }           
            }
            closedir( $dir );
            rmdir($path);
            return true;
   }
}


function check_install()
{
	if(!file_exists(get_real_path()."public/install.lock"))
	{
	    clear_cache();
		header('Location:'.APP_ROOT.'/install');
		exit;
	}
}



//utf8 字符串截取
function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr"))
    {
        $slice =  mb_substr($str, $start, $length, $charset);
        if($suffix&$slice!=$str) return $slice."…";
    	return $slice;
    }
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix&&$slice!=$str) return $slice."…";
    return $slice;
}


//字符编码转换
if(!function_exists("iconv"))
{	
	function iconv($in_charset,$out_charset,$str)
	{
		require 'libs/iconv.php';
		$chinese = new Chinese();
		return $chinese->Convert($in_charset,$out_charset,$str);
	}
}

//JSON兼容
if(!function_exists("json_encode"))
{	
	function json_encode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->encode($data);
	}
}
if(!function_exists("json_decode"))
{	
	function json_decode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->decode($data,1);
	}
}

//邮件格式验证的函数
function check_email($email)
{
	if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
	{
		return false;
	}
	else
	return true;
}
/*显示隐藏中间的手机号码*/
function hideMobile($mobile){
	 if($mobile!="")
	 	return preg_replace('#(\d{3})\d{5}(\d{3})#', '${1}*****${2}',$mobile);
	 else
	 	return "";
}
/*显示隐藏中间的邮箱号*/
function hideEmail($email){
	 if($email!="")
	 	{
	 		return substr($email,0,-8)."*****".substr($email,-3);	
	 	}
	 else
	 	{
	 		return "";
	 	}
	 	
}
//验证手机号码
function check_mobile($mobile)
{
	if(!empty($mobile) && !preg_match("/^([0-9]{11})?$/",$mobile))
	{
		return false;
	}
	else
	return true;
}
//验证邮编
function check_postcode($postcode)
{
	if(!empty($postcode) && !preg_match("/^([0-9]{6})(-[0-9]{5})?$/",$postcode))
	{
		return false;
	}
	else
	return true;
}
//验证验证码
function check_verify_coder($verify_coder){
	if(!empty($verify_coder) && !preg_match("/^([0-9]{6})?$/",$verify_coder))
	{
		return false;
	}
	else
	return true;
}
function get_verify_code($verify_coder){
 			$verify_coder_result = check_user("verify_coder",$verify_coder);
			//var_dump($verify_coder_result);exit;
			if($verify_coder_result['status']==0)
			{
				if($verify_coder_result['data']['error']==EMPTY_ERROR)
				{
					$error = "不能为空";
					$type = "form_tip";
				}
				if($verify_coder_result['data']['error']==EXIST_ERROR)
				{
					$error = "错误";
					$type="form_error";
				}
				return array("type"=>$type,"field"=>"verify_coder","info"=>"验证码".$error);
			}
			else
			{
				return array("type"=>"form_success","field"=>"verify_coder","info"=>"");
			}
 }
//跳转
function app_redirect($url,$time=0,$msg='')
{
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);    
    if(empty($msg))
        $msg    =   "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if(0===$time) {
        	if(substr($url,0,1)=="/")
        	{        		
        		header("Location:".get_domain().$url);
        	}
        	else
        	{
        		header("Location:".$url);
        	}
            
        }else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    }else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time!=0)
            $str   .=   $msg;
        exit($str);
    }
}



/**
 * 验证访问IP的有效性
 * @param ip地址 $ip_str
 * @param 访问页面 $module
 * @param 时间间隔 $time_span
 * @param 数据ID $id
 */
function check_ipop_limit($ip_str,$module,$time_span=0,$id=0)
{
		if(intval(app_conf('USER_SUBMIT_TIME'))>0){
			$time_span = intval(app_conf('USER_SUBMIT_TIME'));
		}
		$op = es_session::get($module."_".$id."_ip");
    	if(empty($op))
    	{
    		$check['ip']	=	 get_client_ip();
    		$check['time']	=	get_gmtime();
    		es_session::set($module."_".$id."_ip",$check);    		
    		return true;  //不存在session时验证通过
    	}
    	else 
    	{   
    		$check['ip']	=	 get_client_ip();
    		$check['time']	=	get_gmtime();    
    		$origin	=	es_session::get($module."_".$id."_ip");
    		
    		if($check['ip']==$origin['ip'])
    		{
    			if($check['time'] - $origin['time'] < $time_span)
    			{
    				return false;
    			}
    			else 
    			{
    				es_session::set($module."_".$id."_ip",$check);
    				return true;  //不存在session时验证通过    				
    			}
    		}
    		else 
    		{
    			es_session::set($module."_".$id."_ip",$check);
    			return true;  //不存在session时验证通过
    		}
    	}
    }

function gzip_out($content)
{
	header("Content-type: text/html; charset=utf-8");
    header("Cache-control: private");  //支持页面回跳
	$gzip = app_conf("GZIP_ON");
	if( intval($gzip)==1 )
	{
		if(!headers_sent()&&extension_loaded("zlib")&&preg_match("/gzip/i",$_SERVER["HTTP_ACCEPT_ENCODING"]))
		{
			$content = gzencode($content,9);	
			header("Content-Encoding: gzip");
			header("Content-Length: ".strlen($content));
			echo $content;
		}
		else
		echo $content;
	}else{
		echo $content;
	}
	
}


/**
	 * 保存图片
	 * @param array $upd_file  即上传的$_FILES数组
	 * @param array $key $_FILES 中的键名 为空则保存 $_FILES 中的所有图片
	 * @param string $dir 保存到的目录
	 * @param array $whs
	 	可生成多个缩略图
		数组 参数1 为宽度，
			 参数2为高度，
			 参数3为处理方式:0(缩放,默认)，1(剪裁)，
			 参数4为是否水印 默认为 0(不生成水印)
	 	array(
			'thumb1'=>array(300,300,0,0),
			'thumb2'=>array(100,100,0,0),
			'origin'=>array(0,0,0,0),  宽与高为0为直接上传
			...
		)，
	 * @param array $is_water 原图是否水印
	 * @return array
	 	array(
			'key'=>array(
				'name'=>图片名称，
				'url'=>原图web路径，
				'path'=>原图物理路径，
				有略图时
				'thumb'=>array(
					'thumb1'=>array('url'=>web路径,'path'=>物理路径),
					'thumb2'=>array('url'=>web路径,'path'=>物理路径),
					...
				)
			)
			....
		)
	 */
//$img = save_image_upload($_FILES,'avatar','temp',array('avatar'=>array(300,300,1,1)),1);
function save_image_upload($upd_file, $key='',$dir='temp', $whs=array(),$is_water=false,$need_return = false)
{
 		require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
		$image = new es_imagecls();
		$image->max_size = intval(app_conf("MAX_IMAGE_SIZE"));
		
		$list = array();

		if(empty($key))
		{
			foreach($upd_file as $fkey=>$file)
			{
				$list[$fkey] = false;
				$image->init($file,$dir);
				if($image->save())
				{
					$list[$fkey] = array();
					$list[$fkey]['url'] = $image->file['target'];
					$list[$fkey]['path'] = $image->file['local_target'];
					$list[$fkey]['name'] = $image->file['prefix'];
				}
				else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像'.$image->error_code);
						}
						else
						echo "非法图像";
					}
					exit;
				}
			}
		}
		else
		{
			$list[$key] = false;
			$image->init($upd_file[$key],$dir);
			if($image->save())
			{
				$list[$key] = array();
				$list[$key]['url'] = $image->file['target'];
				$list[$key]['path'] = $image->file['local_target'];
				$list[$key]['name'] = $image->file['prefix'];
			}
			else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像'.$image->error_code);
						}
						else
						echo "非法图像";
					}
					exit;
				}
		}

		$water_image = APP_ROOT_PATH.app_conf("WATER_MARK");
		$alpha = app_conf("WATER_ALPHA");
		$place = app_conf("WATER_POSITION");
		
		foreach($list as $lkey=>$item)
		{
				//循环生成规格图
				foreach($whs as $tkey=>$wh)
				{
					$list[$lkey]['thumb'][$tkey]['url'] = false;
					$list[$lkey]['thumb'][$tkey]['path'] = false;
					if($wh[0] > 0 || $wh[1] > 0)  //有宽高度
					{
						$thumb_type = isset($wh[2]) ? intval($wh[2]) : 0;  //剪裁还是缩放， 0缩放 1剪裁
						if($thumb = $image->thumb($item['path'],$wh[0],$wh[1],$thumb_type))
						{
							$list[$lkey]['thumb'][$tkey]['url'] = $thumb['url'];
							$list[$lkey]['thumb'][$tkey]['path'] = $thumb['path'];
							if(isset($wh[3]) && intval($wh[3]) > 0)//需要水印
							{
								$paths = pathinfo($list[$lkey]['thumb'][$tkey]['path']);
								$path = $paths['dirname'];
				        		$path = $path."/origin/";
				        		if (!is_dir($path)) { 
						             @mkdir($path);
						             @chmod($path, 0777);
					   			}   	    
				        		$filename = $paths['basename'];
								@file_put_contents($path.$filename,@file_get_contents($list[$lkey]['thumb'][$tkey]['path']));      
								$image->water($list[$lkey]['thumb'][$tkey]['path'],$water_image,$alpha, $place);
							}
						}
					}
				}
			if($is_water)
			{
				$paths = pathinfo($item['path']);
				$path = $paths['dirname'];
        		$path = $path."/origin/";
        		if (!is_dir($path)) { 
		             @mkdir($path);
		             @chmod($path, 0777);
	   			}   	    
        		$filename = $paths['basename'];
				@file_put_contents($path.$filename,@file_get_contents($item['path']));        		
				$image->water($item['path'],$water_image,$alpha, $place);
			}
		}			
		return $list;
}

function empty_tag($string)
{	
	$string = preg_replace(array("/\[img\]\d+\[\/img\]/","/\[[^\]]+\]/"),array("",""),$string);
	if(trim($string)=='')
	return $GLOBALS['lang']['ONLY_IMG'];
	else 
	return $string;
	//$string = str_replace(array("[img]","[/img]"),array("",""),$string);
}


/**
 * utf8字符转Unicode字符
 * @param string $char 要转换的单字符
 * @return void
 */
function utf8_to_unicode($char)
{
	switch(strlen($char))
	{
		case 1:
			return ord($char);
		case 2:
			$n = (ord($char[0]) & 0x3f) << 6;
			$n += ord($char[1]) & 0x3f;
			return $n;
		case 3:
			$n = (ord($char[0]) & 0x1f) << 12;
			$n += (ord($char[1]) & 0x3f) << 6;
			$n += ord($char[2]) & 0x3f;
			return $n;
		case 4:
			$n = (ord($char[0]) & 0x0f) << 18;
			$n += (ord($char[1]) & 0x3f) << 12;
			$n += (ord($char[2]) & 0x3f) << 6;
			$n += ord($char[3]) & 0x3f;
			return $n;
	}
}

/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @param string $depart 分隔,默认为空格为单字
 * @return string
 */
function str_to_unicode_word($str,$depart=' ')
{
	$arr = array();
	$str_len = mb_strlen($str,'utf-8');
	for($i = 0;$i < $str_len;$i++)
	{
		$s = mb_substr($str,$i,1,'utf-8');
		if($s != ' ' && $s != '　')
		{
			$arr[] = 'ux'.utf8_to_unicode($s);
		}
	}
	return implode($depart,$arr);
}


/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @return string
 */
function str_to_unicode_string($str)
{
	$string = str_to_unicode_word($str,'');
	return $string;
}

//分词
function div_str($str)
{
	require_once APP_ROOT_PATH."system/libs/words.php";
	$words = words::segment($str);
	$words[] = $str;	
	return $words;
}



/**
 * 
 * @param $tag  //要插入的关键词
 * @param $table  //表名
 * @param $id  //数据ID
 * @param $field		// tag_match/name_match/cate_match/locate_match
 */
function insert_match_item($tag,$table,$id,$field)
{
	if($tag=='')
	return;
	
	$unicode_tag = str_to_unicode_string($tag);
	$sql = "select count(*) from ".DB_PREFIX.$table." where match(".$field.") against ('".$unicode_tag."' IN BOOLEAN MODE) and id = ".$id;	
	$rs = $GLOBALS['db']->getOne($sql);
	if(intval($rs) == 0)
	{
		$match_row = $GLOBALS['db']->getRow("select * from ".DB_PREFIX.$table." where id = ".$id);
		if($match_row[$field]=="")
		{
				$match_row[$field] = $unicode_tag;
				$match_row[$field."_row"] = $tag;
		}
		else
		{
				$match_row[$field] = $match_row[$field].",".$unicode_tag;
				$match_row[$field."_row"] = $match_row[$field."_row"].",".$tag;
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX.$table, $match_row, $mode = 'UPDATE', "id=".$id, $querymode = 'SILENT');	
		
	}	
}
/**同步索引的示例
function syn_supplier_match($supplier_id)
{
	$supplier = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."supplier where id = ".$supplier_id);
	if($supplier)
	{
		$supplier['name_match'] = "";
		$supplier['name_match_row'] = "";
		$GLOBALS['db']->autoExecute(DB_PREFIX."supplier", $supplier, $mode = 'UPDATE', "id=".$supplier_id, $querymode = 'SILENT');	
		
		
		//同步名称
		$name_arr = div_str(trim($supplier['name'])); 
		foreach($name_arr as $name_item)
		{
			insert_match_item($name_item,"supplier",$supplier_id,"name_match");
		}
		
	}
}
*/

//封装url

function url($route="index",$param=array())
{
	$key = md5("URL_KEY_".$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	
	$route_array = explode("#",$route);
	
	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";
	
	if(app_conf("URL_MODEL")==0 || $module=='project' )
	{
	//原始模式
		$url = APP_ROOT."/index.php";
		if($module!=''||$action!=''||count($param)>0) //有后缀参数
		{
			$url.="?";
		}
	
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = APP_ROOT;
		if($module==''&&$action==''){
			$url .='/index';
		}else{
			if($module&&$module!='')
			$url .= "/".$module;
			if($action&&$action!='')
			$url .= "-".$action;
		}
		
		
		if(count($param)>0)
		{
			$url.="/";
			foreach($param as $k=>$v)
			{
				$url =$url.$k."-".urlencode($v)."-";
			}
		}
		
		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}
//		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-'){
//			$url.='index';
//		}
				
		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);		
		$url=trim($url);
		if($url==''){
			$url="/index.html";
		}else{
			if($module=='article_cate'){
				if($param['id']){
					if($GLOBALS['article_cates'][$param['id']]['seo_title']){
						if($param['p']){
						$url=APP_ROOT."/".$GLOBALS['article_cates'][$param['id']]['seo_title']."?p=".$param['p'];
						}else{
							$url=APP_ROOT."/".$GLOBALS['article_cates'][$param['id']]['seo_title'];
						}
					}else{
						$url.='.html';
					}
				}elseif($param['p']){
					$url=APP_ROOT."/article_cate?p=".$param['p'];
					
				}elseif($param['tag']){
					$url=APP_ROOT."/article_cate?tag=".$param['tag'];
				}
				else{
					$url=APP_ROOT."/article_cate";
					
				}
			}elseif($module=='article'){
				if($param['id']){
					if($GLOBALS['article_cates'][$GLOBALS['articles'][$param['id']]['cate_id']]['seo_title']){
						$url=APP_ROOT."/".$GLOBALS['article_cates'][$GLOBALS['articles'][$param['id']]['cate_id']]['seo_title']."/".$param['id'].".html";
					}else{
						$url.='.html';
					}
				}else{
					$url=APP_ROOT."/article_cate";
				}
			}else{
				$url.='.html';
			}
		}
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	
	
}

function url_wap($route="index",$param=array())
{
	if($GLOBALS['is_app']){
		$param['from_type'] = $GLOBALS['is_app'];
	}
	
	$key = md5("URL_WAP_KEY_".$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	
	$route_array = explode("#",$route);
	
	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";
	
	if(true)
	{
	//原始模式
		$url = APP_ROOT."/wap/index.php";
		if($module!=''||$action!=''||count($param)>0) //有后缀参数
		{
			$url.="?";
		}
	
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = APP_ROOT."/wap";

		if($module&&$module!='')
		$url .= "/".$module;
		if($action&&$action!='')
		$url .= "-".$action;
		
		if(count($param)>0)
		{
			$url.="/";
			foreach($param as $k=>$v)
			{
				$url =$url.$k."-".urlencode($v)."-";
			}
		}
		
		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}
				
		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);		
		
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	
	
}

//封装url

function url_mapi($route="index",$param=array())
{
	$key = md5("URL_APP_KEY_".$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	
	$route_array = explode("#",$route);
	
	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";
	
	if(app_conf("URL_MODEL")==0)
	{
	//原始模式
		$url = get_domain().APP_ROOT."/index.php";
		if($module!=''||$action!=''||count($param)>0) //有后缀参数
		{
			$url.="?";
		}
	
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = get_domain().APP_ROOT;

		if($module&&$module!='')
		$url .= "/".$module;
		if($action&&$action!='')
		$url .= "-".$action;
		
		if(count($param)>0)
		{
			$url.="/";
			foreach($param as $k=>$v)
			{
				$url =$url.$k."-".urlencode($v)."-";
			}
		}
		
		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}
				
		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);		
		
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	
	
}

//手机端 访问根目录的url
function url_root($route="index",$param=array())
{
	$key = md5("URL_KEY_".$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	
	$route_array = explode("#",$route);
	
	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";
	
	if(app_conf("URL_MODEL")==0)
	{
	//原始模式
		$url = get_domain().REAL_APP_ROOT."/index.php";
		if($module!=''||$action!=''||count($param)>0) //有后缀参数
		{
			$url.="?";
		}
	
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = get_domain().REAL_APP_ROOT;

		if($module&&$module!='')
		$url .= "/".$module;
		if($action&&$action!='')
		$url .= "-".$action;
		
		if(count($param)>0)
		{
			$url.="/";
			foreach($param as $k=>$v)
			{
				$url =$url.$k."-".urlencode($v)."-";
			}
		}
		
		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}
				
		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);		
		
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	
	
}

function unicode_encode($name) {//to Unicode
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for($i = 0; $i < $len - 1; $i = $i + 2) {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0) {// 两个字节的字
            $cn_word = '\\'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            $str .= strtoupper($cn_word);
        } else {
            $str .= $c2;
        }
    }
    return $str;
}

function unicode_decode($name) {//Unicode to
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            } else {
                $name .= $str;
            }
        }
    }
    return $name;
}


//载入动态缓存数据
function load_dynamic_cache($name)
{
	if(isset($GLOBALS['dynamic_cache'][$name]))
	{
		return $GLOBALS['dynamic_cache'][$name];
	}
	else
	{
		return false;
	}
}

function set_dynamic_cache($name,$value)
{
	if(!isset($GLOBALS['dynamic_cache'][$name]))
	{
		if(count($GLOBALS['dynamic_cache'])>MAX_DYNAMIC_CACHE_SIZE)
		{
			array_shift($GLOBALS['dynamic_cache']);
		}
		$GLOBALS['dynamic_cache'][$name] = $value;		
	}
}

function load_auto_cache($key,$param=array(),$is_real=true)
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$result = $obj->load($param,$is_real);
	}
	else
	$result = false;
	return $result;
}

function rm_auto_cache($key,$param=array())
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->rm($param);
	}
}


function clear_auto_cache($key)
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->clear_all();
	}
}


/*ajax返回*/
function ajax_return($data,$is_debug=false)
{
		if(!$is_debug){
			header("Content-Type:text/html; charset=utf-8");
	        echo(json_encode($data));
	        exit;
		}else{
 			
			if($data['status']==0){
				var_export($data);
				echo "<br />";
				exit;
			}
			
		}
			
}



function is_animated_gif($filename){
 $fp=fopen($filename, 'rb');
 $filecontent=fread($fp, filesize($filename));
 fclose($fp);
 return strpos($filecontent,chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0')===FALSE?0:1;
}



function update_sys_config()
{
	$filename = APP_ROOT_PATH."public/sys_config.php";
	if(!file_exists($filename))
	{
		//定义DB
		require APP_ROOT_PATH.'system/db/db.php';
		$dbcfg = require APP_ROOT_PATH."public/db_config.php";
		define('DB_PREFIX', $dbcfg['DB_PREFIX']); 
		if(!file_exists(APP_ROOT_PATH.'public/runtime/app/db_caches/'))
			mkdir(APP_ROOT_PATH.'public/runtime/app/db_caches/',0777);
		$pconnect = false;
		$db = new mysql_db($dbcfg['DB_HOST'].":".$dbcfg['DB_PORT'], $dbcfg['DB_USER'],$dbcfg['DB_PWD'],$dbcfg['DB_NAME'],'utf8',$pconnect);
		//end 定义DB

		$sys_configs = $db->getAll("select * from ".DB_PREFIX."conf");
		$config_str = "<?php\n";
		$config_str .= "return array(\n";
		foreach($sys_configs as $k=>$v)
		{
			$config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
		}
		$config_str.=");\n ?>";	
		file_put_contents($filename,$config_str);
		$url = APP_ROOT."/";
		app_redirect($url);
	}
}


function gen_qrcode($str,$size = 5)
{

	require_once APP_ROOT_PATH."system/phpqrcode/qrlib.php";

	$root_dir = APP_ROOT_PATH."public/images/qrcode/";
 	if (!is_dir($root_dir)) {
            @mkdir($root_dir);               
            @chmod($root_dir, 0777);
     }
     
     $filename = md5($str."|".$size);
     $hash_dir = $root_dir. '/c' . substr(md5($filename), 0, 1)."/";
     if (!is_dir($hash_dir))
     {
        @mkdir($hash_dir);
        @chmod($hash_dir, 0777);
     }   
	
	$filesave = $hash_dir.$filename.'.png';

	if(!file_exists($filesave))
	{
		QRcode::png($str, $filesave, 'Q', $size, 2); 
	}	
	return APP_ROOT."/public/images/qrcode/c". substr(md5($filename), 0, 1)."/".$filename.".png";       
}

function format_price($v)
{
	if(!$v){$v = 0;}
	return "¥".number_format($v,2);
}

function syn_deal($deal_id)
{
	$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	
	if($deal_info)
	{
		$deal_info['comment_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_comment where deal_id = ".$deal_info['id']." and log_id = 0"));
		$deal_info['support_count'] = intval($GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where deal_id = ".$deal_info['id']." and order_status=3 and is_refund=0"));
		$deal_info['focus_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']));
		$deal_info['view_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_visit_log where deal_id = ".$deal_info['id']));
		$support_amount= $GLOBALS['db']->getOne("select sum(num*deal_price) as total_num from ".DB_PREFIX."deal_order where deal_id = ".$deal_info['id']." and order_status=3 and is_refund=0");
		$deal_info['support_amount']=floatval($support_amount);
		$deal_info['delivery_fee_amount'] = floatval($GLOBALS['db']->getOne("select sum(delivery_fee) from ".DB_PREFIX."deal_order where deal_id = ".$deal_info['id']." and order_status=3 and is_refund=0"));
		$deal_info['share_fee_amount'] = floatval($GLOBALS['db']->getOne("select sum(share_fee) from ".DB_PREFIX."deal_order where deal_id = ".$deal_info['id']." and order_status=3 and is_refund=0"));
		
		if($deal_info['pay_radio'] > 0){
			$deal_info['pay_amount'] = ($deal_info['support_amount']*(1-$deal_info['pay_radio']))+$deal_info['delivery_fee_amount']-$deal_info['share_fee_amount'];
		}
		else
		{
			$deal_info['pay_amount'] = ($deal_info['support_amount']*(1-app_conf("PAY_RADIO")))+$deal_info['delivery_fee_amount']-$deal_info['share_fee_amount'];
		
		}
		if($deal_info['type']==0||$deal_info['type']==3||$deal_info['type']==2){
			$deal_info["virtual_num"]=$GLOBALS['db']->getOne("select sum(virtual_person) from ".DB_PREFIX."deal_item where deal_id=".$deal_id);
			$deal_info["virtual_price"]=$GLOBALS['db']->getOne("select sum(virtual_person*price) from ".DB_PREFIX."deal_item where deal_id=".$deal_id);
			if(($deal_info['support_amount']+$deal_info["virtual_price"])>=$deal_info['limit_price'])
			{
				$deal_info['is_success'] = 1;
			}
			else
			{
				$deal_info['is_success'] = 0;
			}
			
		}elseif($deal_info['type']==1 ||$deal_info['type']==4){
			$deal_info["gen_num"]=$GLOBALS['db']->getOne("select count(distinct(user_id)) from ".DB_PREFIX."investment_list where  type=2 and  deal_id=".$deal_id);
			$deal_info["xun_num"]=$GLOBALS['db']->getOne("select count(distinct(user_id)) from ".DB_PREFIX."investment_list where  type=0 and  deal_id=".$deal_id);
			$deal_info["invote_num"]=$GLOBALS['db']->getOne("select count(distinct(user_id)) from ".DB_PREFIX."investment_list where ((type=1 and status=1) or type=2) and deal_id=".$deal_id);
			$deal_info["invote_money"]=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."investment_list where  deal_id=".$deal_id." and type <> 6");
			if(($deal_info['invote_money']>=$deal_info['limit_price'])&&$deal_info['invest_status']!=2)
			{
				$deal_info['is_success'] = 1;
			}
			else
			{
				$deal_info['is_success'] = 0;
			}
		}
		
		if($deal_info['is_success']==1){
				$paid_money=$GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."deal_pay_log where deal_id=".$deal_id);
				$deal_info['left_money']=$deal_info['pay_amount']-floatval($paid_money);
			}
  		
		$deal_info['tags_match'] = "";
		$deal_info['tags_match_row'] = "";
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal", $deal_info, $mode = 'UPDATE', "id=".$deal_info['id'], $querymode = 'SILENT');	
		
		$tags_arr = preg_split("/[, ]/",$deal_info["tags"]);

		foreach($tags_arr as $tgs){
			if(trim($tgs)!="")
			insert_match_item(trim($tgs),"deal",$deal_info['id'],"tags_match");
		}
		
		$name_arr = div_str($deal_info['name']);
		foreach($name_arr as $name_item){
			if(trim($name_item)!="")
			insert_match_item(trim($name_item),"deal",$deal_info['id'],"name_match");
		}

	}


}



//发密码验证邮件
function send_user_password_mail($user_id)
{

		$verify_code = rand(111111,999999);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '".$verify_code."' where id = ".$user_id);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);			
		if($user_info)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_USER_PASSWORD'");
			$tmpl_content=  $tmpl['content'];
			$user_info['logo']=app_conf("SITE_LOGO");
			$user_info['site_name']=app_conf("SITE_NAME");
			$time=get_gmtime();
			$user_info['send_time']=to_date($time,'Y年m月d日');
			$user_info['send_time_ms']=to_date($time,'Y年m月d日 H时i分');
			
			$user_info['password_url'] = get_domain().url("settings#password", array("code"=>$user_info['password_verify'],"id"=>$user_info['id']));			
			$GLOBALS['tmpl']->assign("user",$user_info);
			$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
			$msg_data['dest'] = $user_info['email'];
			$msg_data['send_type'] = 1;
			$msg_data['title'] = "重置密码";
			$msg_data['content'] = addslashes($msg);
			$msg_data['send_time'] = 0;
			$msg_data['is_send'] = 0;
			$msg_data['create_time'] = get_gmtime();
			$msg_data['user_id'] = $user_info['id'];
			$msg_data['is_html'] = $tmpl['is_html'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
		}

}

function strim($str)
{
	return quotes(htmlspecialchars(trim($str)));
}
function btrim($str)
{
	return quotes(trim($str));
}
function valid_tag($str)
{
	
	return preg_replace("/<(?!div|ol|ul|li|sup|sub|span|br|img|p|h1|h2|h3|h4|h5|h6|\/div|\/ol|\/ul|\/li|\/sup|\/sub|\/span|\/br|\/img|\/p|\/h1|\/h2|\/h3|\/h4|\/h5|\/h6|blockquote|\/blockquote|strike|\/strike|b|\/b|i|\/i|u|\/u)[^>]*>/i","",$str);
}

//$type = 1(添加) 2(删除)
function update_user_weibo($user_id,$weibo_url,$type=1)
{
	if($weibo_url!="")
	{
		if($type==1)
		{
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_weibo where weibo_url = '".$weibo_url."' and user_id = ".$user_id)==0)
			{
				$weibo_data['user_id'] = $user_id;
				$weibo_data['weibo_url'] = $weibo_url;				
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_weibo",$weibo_data);
			}
		}
		if($type==2)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".$user_id." and weibo_url = '".$weibo_url."'");
		}		
	}
}

//返回array: status:0:未支付 1:已支付(过期) 2:已支付(无库存) 3:成功  money:剩余需支付金额 4:已支付但未判定（锁住订单）5:订单内余额不足，购买失败
function pay_order($order_id)
{
	
	require_once APP_ROOT_PATH."system/libs/user.php";	
	$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
	$user=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$order_info['user_id']);

	//查出积分的数量score ,对应金额score_money
	if($order_info['score']>$user['score']){
		//积分不够，支付失败
		$result['status'] = 0;
		return $result;
	}
	
	if($order_info['is_tg']){
 		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 4 where id = ".$order_id." and  online_pay=total_price and order_status = 0");
	}else{
 		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 4 where id = ".$order_id." and  (online_pay+credit_pay+score_money)=total_price and order_status = 0");
	}
	
  	if($GLOBALS['db']->affected_rows()>0) //订单已成功支付
	{
 		if(!$order_info['is_tg']){
			//积分转成余额， 扣掉积分
			if($order_info['score']>0)
			{
				if ( $order_info['type']!=6) {
					$log_score=$order_info['deal_name']."购买，支付使用".$order_info['score']."积分,转存入余额".format_price($order_info['score_money']);;
				}else {
					$log_score=$order_info['deal_name']."股权转让，支付使用".$order_info['score']."积分,转存入余额".format_price($order_info['score_money']);;
				}
				
				modify_account(array("money"=>$order_info['score_money'],"score"=>"-".$order_info['score']),$order_info['user_id'],$log_score,array('money_type'=>20));
			}
			if ( $order_info['type']!=6) {
	  			$re=modify_account(array("money"=>"-".$order_info['total_price']),$order_info['user_id'],$order_info['deal_name']."购买成功",array('money_type'=>16,'deal_id'=>$order_info['deal_id'],'order_id'=>$order_info['id']));
			}else{
				$re=modify_account(array("money"=>"-".$order_info['total_price']),$order_info['user_id'],$order_info['deal_name']."股权转让支付成功",array('money_type'=>16,'deal_id'=>$order_info['deal_id'],'order_id'=>$order_info['id']));
			}
	   		if(!$re){
	 			$result['status'] = 5;
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 0 where id = ".$order_info['id']);
	 			//扣款失败，积分退回
				if ( $order_info['type']!=6) {
	 				$log_score=$order_info['deal_name']."购买，支付失败，退回".$order_info['score']."积分，扣除余额".format_price($order_info['score_money']);
				}else{
					$log_score=$order_info['deal_name']."股权转让，支付失败，退回".$order_info['score']."积分，扣除余额".format_price($order_info['score_money']);
				}
				
				modify_account(array("money"=>"-".$order_info['score_money'],"score"=>$order_info['score']),$order_info['user_id'],$log_score,array('money_type'=>21));
	 			
	 			return $result;
	  		} 
		}
		 
 		//$credit_pay=$order_info['total_price']-$order_info['online_pay'];
		//$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set credit_pay=".$credit_pay." where id=".$order_info['id']);
  		
 		$order_info['pay_time'] = get_gmtime();
		if($order_info['type']==1 || $order_info['type']==5){
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set support_count = support_count + 1,support_amount = support_amount + ".$order_info['deal_price'].",pay_amount = pay_amount + ".$order_info['total_price'].",delivery_fee_amount = delivery_fee_amount + ".$order_info['delivery_fee']." ,share_fee_amount = share_fee_amount + ".$order_info['share_fee']." where id = ".$order_info['deal_id']." and is_effect = 1 and is_delete = 0 and begin_time < ".get_gmtime()." and (pay_end_time > ".get_gmtime()." or pay_end_time = 0)");
		}elseif($order_info['type']==6){
			//转让项目是否有效，是否在有效时间内 ,pay_time 
			$GLOBALS['db']->query("update ".DB_PREFIX."stock_transfer set support_num = support_num+1  where begin_time < ".$order_info['pay_time']." and  end_time > ".$order_info['pay_time']."  and status=1 and id = ".$order_info['deal_item_id']);
		}else{
			$GLOBALS['db']->query("update ".DB_PREFIX."deal set support_count = support_count + ".$order_info['num'].",support_amount = support_amount + ".$order_info['deal_price']."*".$order_info['num'].",pay_amount = pay_amount + ".$order_info['total_price'].",delivery_fee_amount = delivery_fee_amount + ".$order_info['delivery_fee']." ,share_fee_amount = share_fee_amount + ".$order_info['share_fee']." where id = ".$order_info['deal_id']." and is_effect = 1 and is_delete = 0 and begin_time < ".get_gmtime()." and (end_time > ".get_gmtime()." or end_time = 0)");
		}
		if($GLOBALS['db']->affected_rows()>0)
		{
			//记录支持日志
			$support_log['deal_id'] = $order_info['deal_id'];
			$support_log['user_id'] = $order_info['user_id'];
			$support_log['create_time'] = get_gmtime();
			$support_log['price'] = $order_info['deal_price']*$order_info['num'];
			$support_log['num'] = $order_info['num'];
			$support_log['deal_item_id'] = $order_info['deal_item_id'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_support_log",$support_log);
			$support_log_id = intval($GLOBALS['db']->insert_id());
			
			if( $order_info['type']==6){
				// 股权转让是否超额  support_count+1
				$GLOBALS['db']->query("update ".DB_PREFIX."stock_transfer set support_count = support_count + ".$order_info['num']."  where support_count + 1 <= 1  and id = ".$order_info['deal_item_id']);
				
			}else{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_item set support_count = support_count + ".$order_info['num'].",support_amount = support_amount +".$order_info['deal_price']."*".$order_info['num']." where (support_count + 1 <= limit_user or limit_user = 0) and id = ".$order_info['deal_item_id']);
			}
			if($GLOBALS['db']->affected_rows()>0||($order_info['type']==1 || $order_info['type']==5 ))
			{
				$result['status'] = 3;
				$order_info['order_status'] = 3;	
				
				 
				
 				$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$order_info['deal_id']." and is_effect = 1 and is_delete = 0");
				//下单项目成功，准备加入准备队列
				if($deal_info['is_success'] == 0)
				{
					//未成功的项止准备生成队列
					$notify['user_id'] = $GLOBALS['user_info']['id'];
					$notify['deal_id'] = $deal_info['id'];
					$notify['create_time'] = get_gmtime();
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_deal_notify",$notify,"INSERT","","SILENT");

				}
				if($order_info['type']!=6){
				//发送信息
				//send_paid_msg(array('user_id'=> $order_info['user_id'],'money'=> $order_info['total_price'],'order_id'=>$order_info['id']));
				$GLOBALS['msg']->manage_msg('MSG_PAID',$order_info['user_id'],array('money'=> $order_info['total_price'],'order_id'=>$order_info['id']));
				//更新用户的支持数
				$GLOBALS['db']->query("update ".DB_PREFIX."user set support_count = support_count + ".$deal_info['num']." where id = ".$order_info['user_id']);
				//同步deal_log中的deal_info_cache
				
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_log set deal_info_cache = '' where deal_id = ".$deal_info['id']);
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set deal_extra_cache = '' where id = ".$deal_info['id']);
				}
				if($order_info['type']==1 || $order_info['type']==5){
					$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set investor_money_status=3 where order_id=".$order_info['id']);
				}
 				
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = ".intval($order_info['order_status']).",pay_time = ".$order_info['pay_time'].",is_refund = ".$order_info['is_refund']." where id = ".$order_info['id']);				
				if($order_info['type']==6){
					//修改状态
					//$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set investor_money_status=3 where order_id=".$order_info['id']);
				}
				//同步项目状态
				syn_deal_status($order_info['deal_id']);
				syn_deal($order_info['deal_id']);
				
				//生成抽奖号
				if($order_info['type'] ==3)
					insert_lottery_sn($order_info);
				
				//发放返利   
				if($user['pid'] >0)
					send_buy_referrals($user,$order_info['id']);//$user_info 会员信息 要传入id,pid,user_name,referral_count
				
				//发放积分与信用值
				$score_multiple=floatval(app_conf("BUY_PRESEND_SCORE_MULTIPLE"));
				$point_multiple=floatval(app_conf("BUY_PRESEND_POINT_MULTIPLE"));
				$score_point=array("score_multiple"=>$score_multiple,"point_multiple"=>$point_multiple);
				$score_point=serialize($score_point); 
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sp_multiple = '".$score_point."' where id = ".$order_info['id']);
				if($score_multiple >0)
				{
					$score=intval($order_info['total_price']*$score_multiple);
					if($order_info['type']!=6){
						$log_info=$order_info['deal_name']."购买成功,积分增加".$score;
					}else{
						$log_info=$order_info['deal_name']."转让支付成功,积分增加".$score;
					}
					modify_account(array("score"=>$score),$order_info['user_id'],$log_info);
				}
				if($point_multiple >0)
				{
					$point=intval($order_info['total_price']*$point_multiple);
					if($order_info['type']!=6){
						$log_info=$order_info['deal_name']."购买成功,信用值增加".$point;
					}else{
						$log_info=$order_info['deal_name']."转让支付成功,信用值增加".$point;
					}
					modify_account(array("point"=>$point),$order_info['user_id'],$log_info);
				}
					
			}
			else
			{
				$result['status'] = 2;
				$order_info['order_status'] = 2;
				$order_info['is_refund'] =1;
				if($order_info['type']==6){
					//股权转让
					$GLOBALS['db']->query("update ".DB_PREFIX."stock_transfer set support_count = 0  where  id = ".$order_info['deal_item_id']);
				}else{
					$GLOBALS['db']->query("update ".DB_PREFIX."deal set support_count = support_count - 1,support_amount = support_amount - ".$order_info['deal_price'].",pay_amount = pay_amount - ".$order_info['total_price'].",delivery_fee_amount = delivery_fee_amount - ".$order_info['delivery_fee']." ,share_fee_amount = share_fee_amount - ".$order_info['share_fee']." where id = ".$order_info['deal_id']);
				}
				$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_support_log where id = ".$support_log_id);
				modify_account(array("money"=>($order_info['online_pay']+$order_info['credit_pay'])),$order_info['user_id'],$order_info['deal_name']."限额已满，转存入会员帐户");
				//退回积分
				if($order_info['score'] >0)
 				{
 					$log_score=$order_info['deal_name']."限额已满，退回".$order_info['score']."积分";
					modify_account(array("score"=>$order_info['score']),$order_info['user_id'],$log_score);
 				}
				
			}
		}
		else
		{
			$result['status'] =1;
			$order_info['order_status'] =1;
			$order_info['is_refund'] =1;
			modify_account(array("money"=>($order_info['online_pay']+$order_info['credit_pay'])),$order_info['user_id'],$order_info['deal_name']."已过期，转存入会员帐户");
			//退回积分
			if($order_info['score'] >0)
			{
				$log_score=$order_info['deal_name']."限额已满，退回".$order_info['score']."积分";
				modify_account(array("score"=>$order_info['score']),$order_info['user_id'],$log_score);
			}
			
			
		}
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = ".intval($order_info['order_status']).",pay_time = ".$order_info['pay_time'].",is_refund = ".$order_info['is_refund']." where id = ".$order_info['id']);
		
	}
	else
	{
		$result['status'] = 0;
		$result['money'] = $order_info['total_price'] - $order_info['score_money']-$order_info['credit_pay']-$order_info['online_pay'];
	}
	return $result;
}

function syn_deal_status($deal_id)
{
	$deal_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id");
	if($deal_info['type']==1 || $deal_info['type']==4){
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_success = 1,success_time = ".get_gmtime()." where id = ".$deal_id." and is_effect=  1 and is_delete = 0 and invote_money >= limit_price and begin_time <".get_gmtime()." and (end_time > ".get_gmtime()." or end_time = 0)");
	}else{
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set is_success = 1,success_time = ".get_gmtime()." where id = ".$deal_id." and is_effect=  1 and is_delete = 0 and (support_amount+virtual_price) >= limit_price and begin_time <".get_gmtime()." and (end_time > ".get_gmtime()." or end_time = 0)");
	}
	if($GLOBALS['db']->affected_rows()>0)
	{		
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_success = 1 where deal_id = ".$deal_id);
		//无私奉献的用户，项目成功后，就默认发送成功回报
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time='".get_gmtime()."',repay_time='".get_gmtime()."',repay_memo='无私奉献' where order_status=3 and is_refund=0 and type=2 ");
		//项目成功，加入项目成功的待发队列
		$deal_notify['deal_id'] = $deal_id;
		$deal_notify['create_time'] = get_gmtime();
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_notify",$deal_notify,"INSERT","","SILENT");	
	}
}
//更新会员级别，用户升级上来的用户 没有会员等级
function syn_user_level(){
	$level_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_level order by level asc ");
 	$GLOBALS['db']->query("update ".DB_PREFIX."user set user_level=$level_id where user_level='' ");
}

//同步到微博
function syn_weibo($data)
{
	$api_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."api_login where is_weibo = 1");
	foreach($api_list as $k=>$v)
	{
		if($GLOBALS['user_info'][strtolower($v['class_name'])."_id"]==""||$GLOBALS['user_info'][strtolower($v['class_name'])."_token"]=="")
		{
			unset($api_list[$k]);
		}
		else
		{
			$class_name = $v['class_name']."_api";
			require_once APP_ROOT_PATH."system/api_login/".$class_name.".php";
			$o = new $class_name($v);
			$o->send_message($data);
		}
	}
}


//发送给用户通知
function send_notify($user_id,$content,$url_route,$url_param)
{
	
	$GLOBALS['msg']->manage_msg("notify",$user_id,array('content'=>$content,'url_route'=>$url_route,'url_param'=>$url_param));
	
	// $notify_user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($user_id));
 	// $notify = array();
	// if($notify_user)
	// {
		// $notify['user_id'] = $user_id;
		// $notify['log_info'] = $content;
		// $notify['log_time'] = get_gmtime();
		// $notify['url_route'] = $url_route;
		// $notify['url_param'] = $url_param;
// 		
		// $GLOBALS['db']->autoExecute(DB_PREFIX."user_notify",$notify,"INSERT","","SILENT");
	// }
// 	
}

   

//发短信验证码
function send_verify_sms($mobile,$code,$type="")
{
	$GLOBALS['msg']->manage_msg('TPL_SMS_VERIFY_CODE',$mobile,array('code'=>$code,'title'=>$title));
}
/**
 * 发送投资通短信验证码
 * @param $mobile 手机号
 * @param $code  验证码
 */
function send_tzt_verify_sms($mobile, $code){
	$GLOBALS['msg']->manage_msg('TPL_SMS_TZT_VERIFY_CODE',$mobile,array('code'=>$code,'user_id'=>$GLOBALS['user_info']['id']));
}
//发邮件验证码
function send_verify_email($email,$code,$title="")
{
	$GLOBALS['msg']->manage_msg('TPL_MAIL_USER_VERIFY',$email,array('code'=>$code,'title'=>$title));	
	
}

//项目成功发送短信、回报短信(所有成功项目的支持人、项目创立者）
function send_pay_success($log_info){
	if(app_conf("SMS_ON")==0){
		return false;
	}
	//项目成功发起者短信
	$deal_s_user=$GLOBALS['db']->getAll("select *,u.mobile from ".DB_PREFIX."deal d LEFT JOIN ".DB_PREFIX."user u ON u.id = d.user_id where d.is_success='1' and d.is_has_send_success='0' and d.is_delete = 0 ");
	$tmpl3=$GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_USER_S'");
	$tmpl_content3 = $tmpl3['content'];
	
	foreach ($deal_s_user as $k=>$v){
		if($v['id']){
		$user_s_msg['user_name']=$v['user_name'];
		$user_s_msg['deal_name']=$v['name'];
	
		$GLOBALS['tmpl']->assign("user_s_msg",$user_s_msg);
		$msg3=$GLOBALS['tmpl']->fetch("str:".$tmpl_content3);
		$msg_data3['dest']=$v['mobile'];
		$msg_data3['send_type']=0;
		$msg_data3['content']=addslashes($msg3);
		$msg_data3['send_time']=0;
		$msg_data['title']='项目成功发起者-'.$v['name'];
		$msg_data3['is_send']=0;
		$msg_data3['create_time'] = NOW_TIME;
		$msg_data3['user_id'] = $v['user_id'];
		$msg_data3['is_html'] = $tmpl3['is_html'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data3); //插入
	
		}
	}
 }
//获取系统运行上传的值
function get_max_file_size(){
	$system_size=intval(ini_get("post_max_size"))<intval(ini_get("upload_max_filesize"))?intval(ini_get("post_max_size"))*1024*1024:intval(ini_get("upload_max_filesize"))*1024*1024;
	$config_size=app_conf("MAX_IMAGE_SIZE");
	$max_size = $system_size>$config_size?$config_size:$system_size;
    //number_format($system_size/(1024*1024),1)
    if($max_size>=1024*1024){
    	return number_format($max_size/(1024*1024),1).'MB';
    }elseif($max_size>=1024){
    	return number_format($max_size/(1024),1).'KB';
    }else{
    	return $max_size.'B';
    }
}

//获取系统运行上传的值
function get_max_file_size_byte(){
	$system_size=intval(ini_get("post_max_size"))<intval(ini_get("upload_max_filesize"))?intval(ini_get("post_max_size"))*1024*1024:intval(ini_get("upload_max_filesize"))*1024*1024;
	$config_size=app_conf("MAX_IMAGE_SIZE");
	$max_size = $system_size>$config_size?$config_size:$system_size;
   	return $max_size;
}

function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
    //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array (
                                'nokia',
                                'sony',
                                'ericsson',
                                'mot',
                                'samsung',
                                'htc',
                                'sgh',
                                'lg',
                                'sharp',
                                'sie-',
                                'philips',
                                'panasonic',
                                'alcatel',
                                'lenovo',
                                'iphone',
                                'ipod',
                                'blackberry',
                                'meizu',
                                'android',
                                'netfront',
                                'symbian',
                                'ucweb',
                                'windowsce',
                                'palm',
                                'operamini',
                                'operamobi',
                                'openwave',
                                'nexusone',
                                'cldc',
                                'midp',
                                'wap',
                                'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
}
//发起通知用户审核通过或者失败
function send_investor_status($user_info){
	if($user_info['id']){
		$GLOBALS['msg']->manage_msg("MSG_INVEST_STATUS",$user_info['id'],array('user_info'=>$user_info));
	}
 }
//发送付款通知
function invest_pay_send($invest_id,$order_id=''){
	$GLOBALS['msg']->manage_msg("MSG_INVESTOR_GO_PAY",'',array('invest_id'=>$invest_id,'order_id'=>$order_id));
	$status=app_conf("INVEST_PAY_SEND_STATUS");
 	if(FALSE){
		$user_info=$GLOBALS['db']->getRow("select invest.id as invest_id,invest.user_id,invest.money,u.user_name,u.mobile,u.email,d.pay_end_time,d.name as deal_name from ".DB_PREFIX."investment_list as invest " .
				"left join ".DB_PREFIX."user as u on u.id=invest.user_id " .
				"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id" .
				" where invest.id=$invest_id and invest.send_type=0 ");
	 	$user_info['money']=number_price_format($user_info['money']);
 	 	$user_info['pay_end_time']=to_date($user_info['pay_end_time'],"Y-m-d");
		if($status==1&&$user_info){
			//邮件通知
			if(!empty($user_info['email'])){
				//$user_info['money']=number_price_format($user_info['money']);
 				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_INVESTOR_PAY_STATUS'");
				$tmpl_content=  $tmpl['content'];
				$user_info['logo']=app_conf("SITE_LOGO");
				$user_info['site_name']=app_conf("SITE_NAME");
				if($order_id){
					$user_info['note_url']=get_domain().url("account#view_order",array('id'=>$order_id));
				}else{
					$user_info['note_url']=get_domain().url("account#index");
				}
				$time=get_gmtime();
				$user_info['send_time']=to_date($time,'Y年m月d日');
				$user_info['send_time_ms']=to_date($time,'Y年m月d日 H时i分');
 				$GLOBALS['tmpl']->assign("user",$user_info);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				
				$msg_data['dest'] = $user_info['email'];
				$msg_data['send_type'] = 1;
				$msg_data['title'] = app_conf("SITE_NAME")."付款通知-".$user_info['deal_name'];
				$msg_data['content'] = addslashes($msg);;
				$msg_data['send_time'] = 0; 
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = get_gmtime();
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = $tmpl['is_html'];
				$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set send_type=1 where id=".$user_info['invest_id']);
				$re=$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
	 			return $re;
			}
		}elseif($status==2&&$user_info){
			//短信通知
			if(!empty($user_info['mobile'])){
 				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_INVESTOR_PAY_STATUS'");				
				$tmpl_content = $tmpl['content'];
  				$GLOBALS['tmpl']->assign("user",$user_info);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				
				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['send_type'] = 0;
				$msg_data['title'] =  "短信付款通知-".$user_info['deal_name'];
				$msg_data['content'] = addslashes($msg);
				$msg_data['send_time'] = 0;
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = get_gmtime();
 				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = $tmpl['is_html'];
				$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set send_type=1 where id=".$user_info['invest_id']);
				$re=$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入	
				return $re;
			}
		}
	}
 }
 
 
 //支付完成的notice_id,根据notice_id获取会员信息，发送给用户 短信或邮件
 function send_paid_info($notice_id){
 	$status=app_conf("INVEST_PAID_SEND_STATUS");
 	if($status>0){
		$user_info=$GLOBALS['db']->getRow("select pn.id as notice_id,pn.user_id,pn.money,u.user_name,u.mobile,u.email,pn.money as paid_money,pn.notice_sn from ".DB_PREFIX."payment_notice as pn " .
				"left join ".DB_PREFIX."user as u on u.id=pn.user_id " .
				" where pn.id=$notice_id and pn.is_paid = 1 and pn.paid_send=0");
	 	$user_info['paid_money']=number_price_format($user_info['paid_money']);
	 	$user_info['pay_end_time']=to_date($user_info['pay_end_time'],"Y-m-d");
		if($status==1&&$user_info){
			//邮件通知
			if(!empty($user_info['email'])){
				$user_info['money']=number_price_format($user_info['money']);
 				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_INVESTOR_PAID_STATUS'");
				$tmpl_content=  $tmpl['content'];
				$user_info['logo']=app_conf("SITE_LOGO");
				$user_info['site_name']=app_conf("SITE_NAME");
				$time=get_gmtime();
				$user_info['send_time']=to_date($time,'Y年m月d日');
				$user_info['send_time_ms']=to_date($time,'Y年m月d日 H时i分');
				$GLOBALS['tmpl']->assign("user",$user_info);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				
				$msg_data['dest'] = $user_info['email'];
				$msg_data['send_type'] = 1;
				$msg_data['title'] = app_conf("SITE_NAME")."已付款通知";
				$msg_data['content'] = addslashes($msg);;
				$msg_data['send_time'] = 0; 
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = get_gmtime();
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = $tmpl['is_html'];
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set paid_send=1 where id=".$notice_id);
				$re=$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
	 			return $re;
			}
		}elseif($status==2&&$user_info){
			//短信通知
			if(!empty($user_info['mobile'])){
 				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_INVESTOR_PAID_STATUS'");				
				$tmpl_content = $tmpl['content'];
  				$GLOBALS['tmpl']->assign("user",$user_info);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				
				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['send_type'] = 0;
				$msg_data['title'] =  "短信已付款通知";
				$msg_data['content'] = addslashes($msg);
				$msg_data['send_time'] = 0;
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = get_gmtime();
 				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = $tmpl['is_html'];
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set paid_send=1 where id=".$notice_id);
				$re=$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入	
				return $re;
			}
		}
	}
 }

function get_investor($is_investor){
 		switch($is_investor){
 			case 0:
 			return '普通会员';
 			break;
 			case 1:
 			return '投资人';
 			break;
 			case 2:
 			return '投资机构';
 			break;
 		}
 	}
 function get_investor_status($investor_status){
 		switch($investor_status){
 			case 0:
 			return '未审核';
 			break;
 			case 1:
 			return '审核通过';
 			break;
 			case 2:
 			return '审核未通过';
 			break;
 		}
 	}
 //产品类型
 function  get_type_name($type){
 	switch($type){
 		case 0:
 		return '产品众筹';
 		break;
 		case 1:
 		return app_conf("GQ_NAME");
 		break;
 		case 2:
 		return '房产众筹';
 		break;
 		case 3:
 		return '公益众筹';
 		break;
 		case 4:
 		return '融资众筹';
 		break;
 	}
 }
 //订单类型
 function order_type_name($type){
 	switch($type){
 		case 0:
 		return '产品众筹';
 		break;
 		case 1:
 		return app_conf("GQ_NAME");
 		break;
 		case 2:
 		return '无私奉献';
 		break;
 		case 3:
 		return '抽奖';
 		break;
 		case 5:
 		return '融资众筹';
 		break;
 		case 6:
 		return '股权转让';
 		break;
 		case 7:
 		return '房产众筹';
 		break;
 	}
 }
 /*判断股份*/
 function deal_investor_info($info,$type='stock',$old_info=''){
   	$total=0.00;
 	$result=array("status"=>0,'info'=>'');
 	$result_info=array();
 	if($type=='attach'){
  		foreach($info as $k=>$v){
  			if(!empty($info[$k]['title'])&&!empty($info[$k]['file'])){
 				$result_info[]=$info[$k];
 			} 
 		}
  	}elseif($type=='stock'){
    		foreach($info as $k=>$v){
  			$info[$k]['share']=floatval($info[$k]['share']);
  			if($info[$k]['share']>0&&!empty($v['name'])){
	 			$total+=$v['share'];
	 			$result_info[]=$info[$k];
	 		}
 		}
  		if($total!=100){
	   		$result['info']='股份不等于100%或者股东姓名为空';
	 		return $result;
	 	}
  	}elseif($type=='unstock'){
  		foreach($info as $k=>$v){
	   		if(!empty($info[$k]['name'])){
	 				$result_info[]=$info[$k];
	 		} 
  		}
  	}elseif($type=='history'||$type=='plan'){
  	
  		foreach($info as $k=>$v){
	  		if(!empty($v['info']['name'])){
	  			$info[$k]['info']['income_num']=1;
		 			$info[$k]['info']['out_num']=1;
		 			if($v['info']['is_income']==1){
		 				$num=0;
		 				foreach($v['income'] as $k1=>$v1){
		 					if(empty($v1['type'])||empty($v1['money'])){
		 						unset($info[$k]['income'][$k1]);
		 					}else{
		 						$info[$k]['info']['income_num']++;
		 					}
		 				}
		 			}else{
		 				unset($info[$k]['income']);
		 			}
		 			if($v['info']['is_out']==1){
		 				foreach($v['out'] as $k2=>$v2){
		 					if(empty($v2['type'])||empty($v2['money'])){
		 						unset($info[$k]['out'][$k2]);
	 	 					}else{
		 						$info[$k]['info']['out_num']++;
		 					}
		 				}
		 			}else{
		 				unset($info[$k]['out']);
		 			}
		 			
		 			if( $v['info']['begin_time'] !='' && $v['info']['end_time'] !='' )
		 			{	
		 				$begin_time=to_timespan($v['info']['begin_time']);
		 				$end_time=to_timespan($v['info']['end_time']);
		 				
		 				if( $end_time < $begin_time )
		 				{ 
		 					$result['info']='开始时间要小于结束时间';
	 						return $result;
		 					
		 				}
		 			}
 		 			$result_info[]=$info[$k];
	  		}
  		}
  	}elseif($type=='audit_data'){
    		foreach($info as $k=>$v){
  			if(is_array($v)){
  				
  				$result_info[$k]['status']=intval($old_info[$k]['status']);
	  			$result_info[$k]['reason']=$old_info[$k]['reason'];
	  			if(!empty($v['image'])){
	  				foreach($v['image'] as $k1=>$v1){
	  					$result_info[$k]['image'][]=$v1;
	  				}
	  			}
  			}else{
  				$result_info[$k]=$v;
  			}
  			
  			 
  		}
  		
   	}
 	
  	
 	return array('status'=>1,'data'=>$result_info);
 }
 /*将会员余额转化为诚意金*/
 function set_mortgate($user_id,$deal_id,$money){
 	require_once APP_ROOT_PATH."system/libs/user.php";
    $has_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$user_id." and deal_id=".$deal_id." and status=1 ");
    $need_mortgate = need_mortgate();
	 
    if($need_mortgate==($has_money+$money)){
     	$data = array();
		$data['requestNo'] = 0;//请求流水号
		$data['platformUserNo'] = $user_id;//
		$data['platformNo'] = 0;// 商户编号
		$data['amount'] = $money ;
 		$data['deal_id']=$deal_id;
 		$data['is_callback'] = 1;
		$data['status'] = 1;
		$data['pay_type']=1;
		$data['create_time']=NOW_TIME;
 		$GLOBALS['db']->autoExecute(DB_PREFIX."money_freeze",$data,'INSERT');
 		if($GLOBALS['db']->insert_id()){
			modify_account(array('money'=>'-'.$money),$user_id,"项目ID".$deal_id." 冻结诚意金",$param=array());
			
		}
     	return true;
    }else{
     	return false;
    }
	   	
 }
 function syn_mortgate($user_id){
 	//余额支付诚意金插入用户总的诚意金
 	$sum_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$user_id."  and status=1 and requestNo=0");
    $where="id=".$user_id;
	$data['cy_money'] = $sum_money;
	$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data,'UPDATE',$where);
 }
 
 /*获取需要缴纳的诚意金金额*/
 function need_mortgate(){
 	$money=user_need_mortgate();
 	return $money;
 }
 /*将会员余额转化为诚意金*/
 function get_mortgate(){
 	require_once APP_ROOT_PATH."system/libs/user.php";
 	$list=$GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."payment_notice where is_mortgate=1 and is_paid=1 and order_id=0 ");
    foreach($list as $k=>$row){
    	if($row['money']>0){
	  		$user_id=$row['user_id'];
	 		$money=$row['money'];
	 		$row['money']="-".$row['money'];
	 		modify_account($row,$user_id,'冻结为诚意金');
	 		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user set mortgage_money=mortgage_money+".$money." where id=".$user_id." ");
	 		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."payment_notice set is_mortgate=2 where id=".$row['id']."  ");
	 		$mortgate_info['user_id']=$user_id;
	 		$mortgate_info['notice_id']=$row['id'];
	 		$mortgate_info['money']=$row['money'];
	 		$mortgate_info['status']=1;
	 		$GLOBALS['db']->autoExecute(DB_PREFIX."mortgate",$mortgate_info);
	  	}
    }
	   	
 }
 /*判断用户当前需要的诚意金数量*/
 function user_need_mortgate(){
 	//get_mortgate();
 	$now_time=NOW_TIME;
 	$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set investor_money_status=4 where investor_money_status=1 and deal_id in (select d.pay_end_time from ".DB_PREFIX."deal as d where d.type=1 and d.pay_end_time<".$now_time."  )  and  type < 4 ");
 	$num=$GLOBALS['db']->getRow("select count(*) from ".DB_PREFIX."investment_list where investor_money_status=4 and user_id=".$GLOBALS['user_info']['id']." group by deal_id ");
 	$num=intval($num)+1;
 	//$money=need_mortgate();
 	$money = app_conf("MORTGAGE_MONEY");
 	return $money*$num;
 }
 /*获取询价次数*/
 function get_ask(){
 	return app_conf("ENQUIER_NUM");
 }
 /*是否是自己的项目*/
 function is_mine_project($deal_id){
 	$id =  intval($deal_id);
	$item=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$id and user_id=".$GLOBALS['user_info']['id']);
	if(!$item){
		return false;
	}else{
		return true;
	}
 }
 /*当认购资金大于项目的筹资是，判断项目成功*/
 function investor_is_success($deal_id){
 	$deal=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."deal where id=$deal_id ");
 	syn_deal($deal_id);
 	if($deal){
 		$yu_money=$GLOBALS['db']->getOne("SELECT sum(money) FROM ".DB_PREFIX."investment_list where deal_id=$deal_id ");
 		if($yu_money>=$deal['limit_price']){
 			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal set is_success=1 where id=$deal_id ");
 		}
 	}
 }
 //用户违约时，扣掉违约金，并增加一次违约次数，同时记录数据库
 function deal_invest_break($invest_id){
 	$invest=$GLOBALS['db']->getRow("select invest.*,d.end_time,d.pay_end_time from ".DB_PREFIX."investment_list as invest left join ".DB_PREFIX."deal as d on d.id=invest.deal_id  where invest.id=$invest_id ");
 	if($invest['investor_money_status']==1&&$invest['pay_end_time']<NOW_TIME){
  		//用户违约
 		//$money=need_mortgate();
  		//$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user set mortgage_money=mortgage_money-".$money." where id=".$invest['user_id']." and mortgage_money>".$money);
 		//if($GLOBALS['db']->affected_rows()>0){
 		$GLOBALS['db']->query("UPDATE  ".DB_PREFIX."investment_list set investor_money_status=4 where id= ".$invest_id);
 			//$data=array('money'=>"-".$money);
 			//add_log($data,$invest['user_id'],"用户违约，ID为".$invest_id."的申请，扣除诚意金$money元");
 		//}
 	} 
 }
 //更新日志
 function add_log($data,$user_id,$log_msg=''){
 	$log_info['log_info'] = $log_msg;
 	$log_info['log_time'] = get_gmtime();
 	$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
 	$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
 	$adm_id = intval($adm_session['adm_id']);
 	if($adm_id!=0)
 	{
 		$log_info['log_admin_id'] = $adm_id;
 	}
 	$log_info['money'] = floatval($data['money']);
 	$log_info['user_id'] = $user_id;
 	$log_info['type'] = 2;
 	$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
 }
 //更新当前项目的姿态
 function set_deal_status($deal){
   	$now_time=NOW_TIME;
 	if($deal['end_time']<$now_time){
 		$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set investor_money_status=2 where deal_id=".$deal['id']." and investor_money_status=0 and type <4");
 	}
 	if($deal['pay_end_time']<$now_time){
 		$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set investor_money_status=4 where deal_id=".$deal['id']." and investor_money_status=1 and type <4");
 	}
 }
 //根据当前订单号，判断是否要更新 跟投数据
 /*
 * @param 判断融资模块条件 $type = 4 融资模块，空为正常的其他模块
 */
 //领投ajax判断
 function  investor_leader_ajax($user_id,$deal_id,$ajax='',$from='web',$info,$type=''){
 		$invest=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."investment_list where user_id=".$user_id." and type=1 and deal_id=".$deal_id);	
 		//0表示错误，1表示进行投资，2表示申请领头权限，3表示支付诚意金,4 追加投资 5追加资金审核不通过 6申请领头权限不通过 7已经“领投”,无法再跟投
		//8项目已经结束无法再进行投资 9判断“投资者认证成功”
		$result=array('status'=>1,'info'=>'','url'=>'','html'=>'');
		//项目结束出提示“不能再进行投资！”
		$deal_ifo=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."deal WHERE id=".$deal_id);
		//total_num剩余投资的份数
		$old_money=0;
		$enquiry_old=$GLOBALS['db']->getOne("SELECT money FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND type=1 AND deal_id=".$deal_id);
		if(floatval($enquiry_old)){
			$old_money=$enquiry_old;
		}
 		$total_num=($deal_ifo['limit_price']-$old_money)/$deal_ifo['invote_mini_money'];
 		$GLOBALS['tmpl']->assign("total_num",$total_num);
 		//invote_mini_money最低投资金额
 		$GLOBALS['tmpl']->assign("invote_mini_money",$deal_ifo['invote_mini_money']);
 		if($deal_ifo['user_id']==$user_id){
 			$result['status']=0;
			$result['info']="不能投资自己的项目！";
			return $result;
 		}
 		if($deal_ifo['end_time']<NOW_TIME||$deal_ifo['pay_end_time']<NOW_TIME){
			$result['status']=0;
			$result['info']="项目已经结束无法投资！";
			return $result;
		}
 		//判断“投资者认证成功” 
		if($info['is_investor']==0){
			$result['status']=0;
			$result['info']="您是普通用户,请进行实名认证";
			if($from=='web'){
				$result['url']=url("settings#security",array('method'=>'setting-id-box'));
			}elseif($from=='wap'){
				$result['url']=url_wap("investor#index");
			}elseif($from=='app'){
				$result['url']="investor#index";
			}
			
			return $result;
		}else{
			
			if($info['investor_status']==0){
				$result['status']=0;
				$result['info']="实名认证在审核中";
				return $result;
			}elseif($info['investor_status']==2){
				$result['status']=0;
				$result['info']="实名认证未通过，请重新提交";
 				if($from=='web'){
					//$result['url']=url("investor#index");
					$result['url']=url("settings#security",array('method'=>'setting-id-box'));
				}elseif($from=='wap'){
					$result['url']=url_wap("investor#index");
				}elseif($from=='app'){
					$result['url']="investor#index";
				}
				return $result;
			}
 			//判断是否有“跟投”
			$continue_investor_status=$GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
			if($continue_investor_status>0){
				//已经“领投”,无法再跟投
				$result['status']=0;
				$result['info']="已经跟投，无需再进行领投！";
				return $result;
			}
 			$num=$GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."investment_list where user_id!=".$user_id." and status=1 and type=1 and deal_id=".$deal_id);	
			if($num>0){
				//领投未审核
				$result['status']=0;
				$result['info']="领投用户已经存在,无法申请!";
				return $result;
			} 
			 
			if(!$invest){
				$result['status']=0;
				$result['info']="进行领投申请";
				if($from=='web'){
					if($type !=''){
						$result['url']=url("investor#applicate_leader",array("deal_id"=>$deal_id,"type"=>$type));
					}else{
						$result['url']=url("investor#applicate_leader",array("deal_id"=>$deal_id));
					}
				}elseif($from=='wap'){
					$result['url']=url_wap("investor#applicate_leader",array("deal_id"=>$deal_id));
				}elseif($from=='app'){
					$result['url']="investor#applicate_leader";
				}
				return $result;
			}
			 
			if($invest['status']==0){
				//领投未审核
  				$result['status']=0;
				$result['info']="您的申请已在审核中!";
				return $result;
			}
			 
 			if($invest['status']==1){
				//判断是否有诚意金
				//$margator_money=$info['mortgage_money'];
 				$margator_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$user_id." and deal_id=".$deal_id." and status=1 ");
  				if($margator_money>=user_need_mortgate() ){
					//是否已经支付过了(判断钱)
					$num=$GLOBALS['db']->getOne("SELECT count(*) from ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=$deal_id AND money!=0 and type=1");
			
					if($num>0){
						if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND money>0 AND (type IN (1)) AND investor_money_status=0 AND deal_id=".$deal_id)>0){
							$result['status']=1;
							$result['boxid']="append_one_money";
							$result['title']="追加投资";
							//调用追加资金页面
							$GLOBALS['tmpl']->assign("user_id",$user_id);
							$GLOBALS['tmpl']->assign("deal_id",$deal_id);
							$result['html'] = $GLOBALS['tmpl']->fetch("inc/append_money.html");
							return $result;
						}else{
							$result['status']=0;
							$result['info']="您的资金已经无法再追加了！";
							return $result;
						}
					}else{
						//调用领投资金页面
						$result['status']=1;
						$result['boxid']="append_money";
						$result['title']="领投投资";
						$GLOBALS['tmpl']->assign("user_id",$user_id);
						$GLOBALS['tmpl']->assign("deal_id",$deal_id);
						$result['html'] = $GLOBALS['tmpl']->fetch("inc/append_one_money.html");
						return $result;
					}
				}else{
					 
					//支付诚意金
					$result['status']=0;
					$result['info']="您的诚意金不足，请支付!";
					if($from=='web'){
						$result['url']=url("account#mortgate_pay",array('deal_id'=>$deal_id));
					}elseif($from=='wap'){
						$result['url']=url_wap("account#mortgate_pay",array('deal_id'=>$deal_id));
 					}elseif($from=='app'){
						$result['url']="account#mortgate_pay";
					}
					return $result;

				}
			}
			if($invest['status']==2){
				//申请领头权限不通过
				$result['status']=2;
				$result['info']="申请领头权限不通过,是否继续申请！";
				if($from=='web'){
					if($type !=''){
						$result['url']=url("investor#applicate_leader",array("deal_id"=>$deal_id,"type"=>$type));
					}else{
						$result['url']=url("investor#applicate_leader",array('deal_id'=>$deal_id));
					}
				}elseif($from=='wap'){
					$result['url']=url_wap("investor#applicate_leader",array('deal_id'=>$deal_id));
				}elseif($from=='app'){
					$result['url']="investor#applicate_leader";
				}
				return $result;
			}
		}
 }
 //领投(首次、追加)投资金额
 function investor_save_money($user_id,$deal_id,$money,$num,$is_partner,$ajax=''){
 	if($money<=0)
 	{
 		$result['status']=0;
 		$result['info']="请输入正确的目标金额！";
 		return $result;
 	}
 	
 	/*if($is_partner==2){
 		$result['status']=0;
 		$result['info']="请选择愿意担任！";
 		return $result;
 	}*/
 	//投资金额不能低于 单次投资的金额
 	$mini_info=mini_investor_money($money,$deal_id);
 	if($mini_info['status']==0){
 		return $mini_info;
 	}
 	$data=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND type=1 AND deal_id=".$deal_id);
 	$result=array('status'=>1,'info'=>'','url'=>'','html'=>'');
 	
 	if($data['id']>0){
 		//原来的钱
 		$investment_money=$data['money']+$money;
 		$investment_num=$data['num']+$num;
 		
 		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id");
 		if($investment_money>$deal['limit_price']){
 			$result['status']=0;
 			$result['info']="您的投资金额超过融资金额！";
 			return $result;
 		}
 		if($data['investor_money_status']>0){
 			$result['status']=0;
 			$result['info']="无法进行资金添加！";
 			return $result;
 		}
 	
 		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list SET money=".$investment_money.",num=".$investment_num.",is_partner=".$is_partner.",create_time=".NOW_TIME." WHERE user_id=".$user_id." AND type=1 AND deal_id=".$deal_id)>0){
 			investor_is_success($deal_id);
 			$result['status']=1;
 			$result['info']="投资成功！";
 			return $result;
 		}else{
 			$result['status']=0;
 			$result['info']="提交失败！";
 			return $result;
 		}
 	}else{
 		$result['status']=0;
 		$result['info']="提交失败！";
 		return $result;
 	}
 }
 //申请领投信息入库
 function investor_save_leader($deal_id,$user_id,$cates,$investor_id,$invest,$introduce,$from='web',$type=''){
 	if($investor_id>0){
 		if($invest['status']==2){
 			$invest['status']=0;
 		}
 		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list set introduce='".$introduce."',cates='".$cates."',create_time=".NOW_TIME.",status=".$invest['status']." where id=$investor_id ")){
 			$data['status'] = 1;
 			$data['info'] = "申请修改成功！";
 			if($from=='web'){
 				if($type==4){
 					$data['url'] =url("finance#company_finance",array("id"=>$deal_id));
 				}else{
 					$data['url'] =url("deal#show",array("id"=>$deal_id));
 				}
 			}elseif($from=='wap'){
 				$data['url'] =url_wap("deal#show",array("id"=>$deal_id));
 			}
 			return $data;
 		}
 	}else{
 		$re=$GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."investment_list (type,introduce,user_id,deal_id,cates,create_time) VALUES (1,'".$introduce."',$user_id,$deal_id,'".$cates."',".NOW_TIME.")");
 		if($re){
 			$data['status'] = 1;
 			$data['info'] = "已经提交申请！";
 			if($from=='web'){
 				if($type==4){
 					$data['url'] =url("finance#company_finance",array("id"=>$deal_id));
 				}else{
 					$data['url'] =url("deal#show",array("id"=>$deal_id));
 				}
 			}elseif($from=='wap'){
 				$data['url'] =url_wap("deal#show",array("id"=>$deal_id));
 			}
 			return $data;
 		}else{
 			echo "INSERT INTO ".DB_PREFIX."investment_list (type,introduce,user_id,deal_id,cates,create_time) VALUES(1,'".$introduce."',$user_id,$deal_id,'".$cates."',".NOW_TIME.")";
 		}
 	}
 }
 //跟投ajax判断
 function investor_continue($user_id,$deal_id,$from='web',$info){
 	//领投是否申请、领投申请状态
 	$whether_apply=$GLOBALS['db']->getRow("SELECT count(*) as count,status FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND type=1 AND deal_id=$deal_id");
 	//status:0用户未交纳诚意金 1交纳诚意金 3进入询价认筹 4申请领投未通过 5申请领投已通过 6申请领投审核中
 	//7:已经申请“领投”，但是未审核   8:已经申请“领投”，并通过  9:已经申请“领投”，但是审核不通过
 	//10:项目已经结束无法投资！ 11投资者认证未通过
 	$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
 	
 	//项目结束出提示“不能再进行投资！”
 	$deal_ifo=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."deal WHERE id=".$deal_id);
 	if($deal_ifo['user_id']==$user_id){
 		$result['status']=0;
 		$result['info']="不能投资自己的项目！";
 		return $result;
 	}
 	if($deal_ifo['end_time']<NOW_TIME||$deal_ifo['pay_end_time']<NOW_TIME){
 		$result['status']=0;
 		$result['info']="项目已经结束无法投资！";
  		return $result;
 	}
 	if($deal_ifo['begin_time']>NOW_TIME){
 		$result['status']=0;
 		$result['info']="项目未开始无法投资！";
  		return $result;
 	}
 	//判断“投资者认证成功”
 	if($info['is_investor']==0){
 		$result['status']=0;
 		$result['info']="您是普通用户,请进行实名认证";
 		if($from=='web'){
 			//$result['url']=url("investor#index");
 			$result['url']=url("settings#security",array('method'=>'setting-id-box'));
 		}elseif($from=='wap'){
 			$result['url']=url_wap("investor#index");
 		}elseif($from=='app'){
 			$result['url']="investor#index";
 		}
  		return $result;
 	}else{ 		
 		if($info['investor_status']==0){
 			$result['status']=0;
 			$result['info']="实名认证在审核中";
 			return $result;
 		}elseif($info['investor_status']==2){
 			$result['status']=0;
 			$result['info']="实名认证未通过，请重新提交";
 			if($from=='web'){
 				//$result['url']=url("investor#index");
 				$result['url']=url("settings#security",array('method'=>'setting-id-box'));
 			}elseif($from=='wap'){
 				$result['url']=url_wap("investor#index");
 			}elseif($from=='app'){
 				$result['url']="investor#index";
 			}
 			return $result;
 		}
 			
  	    $margator_money=$GLOBALS['db']->getOne("select sum(amount) from ".DB_PREFIX."money_freeze where platformUserNo=".$user_id." and deal_id=".$deal_id." and status=1 ");
 	    
 		
 		if($margator_money<user_need_mortgate()){
	 			//支付诚意金
	 			$result['status']=0;
	 			$result['info']="您的诚意金不足，请支付!";
	 			if($from=='web'){
	 				$result['url']=url("account#mortgate_pay",array('deal_id'=>$deal_id));
	 			}elseif($from=='wap'){
	 				$result['url']=url_wap("account#mortgate_pay",array('deal_id'=>$deal_id));
	 			}elseif($from=='app'){
	 				$result['url']="account#mortgate_pay";
	 			}
	 		 	return $result;
 			}
 		if(app_conf("IS_ENQUIRY") == 1){
	 		$result['status']=1;
	 		//剩余询价次数(type=0表示询价)
	 		$num=$GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."investment_list WHERE deal_id=".$deal_id." AND user_id=".$user_id." AND type=0");
	 		$inquiry_total_num=get_ask();
	 		$inquiry_num=$inquiry_total_num-$num;
	 		if($inquiry_num<0){
	 			$inquiry_num=0;
	 		}
	 		$result['boxid']="enquiry_index_form";
	 		$result['title']="询价认筹";
 		}
 		$GLOBALS['tmpl']->assign("user_id",$user_id);
 		$GLOBALS['tmpl']->assign("deal_id",$deal_id);
 		//total_num剩余投资的份数
 		$old_money=0;
		$enquiry_old=$GLOBALS['db']->getOne("SELECT money FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
		if(floatval($enquiry_old)){
			$old_money=$enquiry_old;
		}
 		$total_num=($deal_ifo['limit_price']-$old_money)/$deal_ifo['invote_mini_money'];
 		$GLOBALS['tmpl']->assign("total_num",$total_num);
 		//invote_mini_money最低投资金额
 		$GLOBALS['tmpl']->assign("invote_mini_money",$deal_ifo['invote_mini_money']);
 		$GLOBALS['tmpl']->assign("inquiry_total_num",$inquiry_total_num);
 		$GLOBALS['tmpl']->assign("inquiry_num",$inquiry_num);
 		if(app_conf("IS_ENQUIRY") == 1){
 			$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_index.html");
 		}else{
 			//判断是否跟投过是的话，跳到追加跟投金额的页面
	 		//判断是否可以追加资金
	 		$leader=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."investment_list where deal_id=$deal_id and user_id=$user_id and type=1 and (status=0 or status=1) ");
	 		if($leader){
	 			if($leader['status']==0){
	 				$result['status']=7;
	 				$result['info']="您已申请领投,是否取消!";
	 				return $result;
	 			}elseif($leader['status']==1){
	 				//“领投申请”通过
	 				$result['status']=8;
	 				$result['info']="您已为领投人,无需进行跟投！";
	 				return $result;
	 			}
	 		}
	 		/*需要判断是第一次还是追加*/
	 		//判断是不是第一次“跟投”
	 		$enquiry=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
	 		if($enquiry['money']==null){
	 			//第一次“跟投”
	 			$result['status']=2;
	 			$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_one_money.html");
	 			return $result;
	 		}else{
	 			if($enquiry['investor_money_status']==0){
	 				//后续“跟投”追加【要判断是否可以追加2条件】
	 				$result['status']=4;
	 				$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_two_money.html");
	 			 	return $result;
	 			}else{
	 				$result['status']=5;
	 				$result['info']="您的资金已经无法再次追加！"; 				
	 				return $result;
	 			}
	 		}
 			//$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_one_money.html");
 		}
 		
 		return $result;
 	}
 }
 
 function investor_enquiry_page($user_id,$deal_id,$enquiry){
 	$deal_ifo=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."deal WHERE id=".$deal_id);
 	//total_num剩余投资的份数
	$old_money=0;
	$enquiry_old=$GLOBALS['db']->getOne("SELECT money FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
	if(floatval($enquiry_old)){
		$old_money=$enquiry_old;
	}
	$total_num=($deal_ifo['limit_price']-$old_money)/$deal_ifo['invote_mini_money'];
	$GLOBALS['tmpl']->assign("total_num",$total_num);
	//invote_mini_money最低投资金额
	$GLOBALS['tmpl']->assign("invote_mini_money",$deal_ifo['invote_mini_money']);
 	//询价次数
 	$inquiry_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."investment_list where user_id=$user_id and deal_id=$deal_id and type=0 ");
 	//enquiry:1询价  0不参与询价无条件接受项目最终估值
 	$num=intval(get_ask());
 	$left_num=$num-$inquiry_num;
 	$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
 	if($enquiry==1){
 		/*需要判断次数是否还有*/
 		if($left_num>0){
 			// (次数大于0)
 			$result['status']=1;
 			$GLOBALS['tmpl']->assign("inquiry_num",$left_num);
 			$GLOBALS['tmpl']->assign("user_id",$user_id);
 			$GLOBALS['tmpl']->assign("deal_id",$deal_id);
 			$deal_ifo['invote_mini_moneys'] =number_format(($deal_ifo['invote_mini_money']/10000),2);
 			$GLOBALS['tmpl']->assign("deal_info",$deal_ifo);
 			$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_two.html");
 			return $result;
 		}else{
 			// (次数小于0)
 			$result['status']=3;
 			$result['info']="询价次数已经用完！";
 			return $result;
 		}
 			
 	}elseif($enquiry==0){
 		/*enquiry:0不参与询价无条件接受项目最终估值*/
 		//判断是否可以追加资金
 		$leader=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."investment_list where deal_id=$deal_id and user_id=$user_id and type=1 and (status=0 or status=1) ");
 		if($leader){
 			if($leader['status']==0){
 				$result['status']=7;
 				$result['info']="您已申请领投,是否取消!";
 				return $result;
 			}elseif($leader['status']==1){
 				//“领投申请”通过
 				$result['status']=8;
 				$result['info']="您已为领投人,无需进行跟投！";
 				return $result;
 			}
 		}
 		/*需要判断是第一次还是追加*/
 		//判断是不是第一次“跟投”
 		$enquiry=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
 		if($enquiry['money']==null){
 			//第一次“跟投”
 			$result['status']=2;
 			$GLOBALS['tmpl']->assign("user_id",$user_id);
 			$GLOBALS['tmpl']->assign("deal_id",$deal_id);
 			$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_one_money.html");
 			return $result;
 		}else{
 			if($enquiry['investor_money_status']==0){
 				//后续“跟投”追加【要判断是否可以追加2条件】
 				$result['status']=4;
 				$GLOBALS['tmpl']->assign("user_id",$user_id);
 				$GLOBALS['tmpl']->assign("deal_id",$deal_id);
 				$result['html'] = $GLOBALS['tmpl']->fetch("inc/enquiry_two_money.html");
 			 	return $result;
 			}else{
 				$result['status']=5;
 				$result['info']="您的资金已经无法再次追加！";
 				return $result;
 			}
 		}
 	}
 }
 //跟投出资保存
 function investor_enquiry_money_save($user_id,$deal_id,$is_partner,$money,$num){
 	// if($is_partner==2){
 		// $result['status']=0;
 		// $result['info']="请选择愿意担任！";
 		// return $result;
 	// }
 	if($money<=0){
 		$result['status']=0;
 		$result['info']="请输入正确的金额！";
 		return $result;
 	}
 	//投资金额不能低于 单次投资的金额
 	$mini_info=mini_investor_money($money,$deal_id);
 	if($mini_info['status']==0){
 		return $mini_info;
 	}
 	$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id");
 	//status:0投资成功 1投资失败 2追加投资成功 3追加投资失败  4不可以再追加资金
 	$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
 	//判断是不是第一次“跟投”
 	$enquiry=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND deal_id=".$deal_id." AND type=2");
 	if($enquiry['money']==null){
 		if($deal['limit_price']<$money){
 			$result['status']=0;
 			$result['info']="您的投资大于融资的金额！";
 			return $result;
 		}
 		//第一次“跟投”插入数据
 		if($GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."investment_list (user_id,deal_id,money,num,is_partner,type,create_time) VALUES($user_id,$deal_id,$money,$num,$is_partner,2,".NOW_TIME.")")>0){
 			investor_is_success($deal_id);
 			$result['status']=1;
 			$result['info']="投资成功！";
 			syn_deal($deal_id);
  			return $result;
 		}else{
 			$result['status']=0;
 			$result['info']="投资失败！";
 			return $result;
 		}
 	}else{
 		//项目原来的钱
 		$enquiry_old=$GLOBALS['db']->getRow("SELECT money,num FROM ".DB_PREFIX."investment_list WHERE user_id=".$user_id." AND type=2 AND deal_id=".$deal_id);
 		$enquiry_money=$money+$enquiry_old['money'];
 		$enquiry_num=$num+$enquiry_old['num'];
 		if($deal['limit_price']<$enquiry_money){
 			$result['status']=0;
 			$result['info']="您的投资大于融资的金额！";
  			return $result;
 		}
 		//追加“跟投”更新数据
 		if($GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list SET money=".$enquiry_money.",num=".$enquiry_num.",is_partner=".$is_partner.",create_time=".NOW_TIME." WHERE user_id=".$user_id." AND type=2 AND deal_id=".$deal_id)>0){
 			investor_is_success($deal_id);
 			$result['status']=1;
 			$result['info']="追加投资成功！";
 			syn_deal($deal_id);
  			return $result;
 		}else{
 			$result['status']=0;
 			$result['info']="追加投资失败！";
  			return $result;
 		}
 	}
 }
 //"跟投"询价表单信息保存
 function investor_enquiry_save($user_id,$deal_id,$stock_value,$money,$investment_reason,$funding_to_help,$is_partner,$num){
 	if($money<=0){
 		$result['status']=0;
 		$result['info']="请输入正确的金额！";
 		return $result;
 	}
 	// if($is_partner==2){
 		// $result['status']=0;
 		// $result['info']="请选择愿意担任！";
 		// return $result;
 	// }
 	if($stock_value<$money){
 		$result['status']=0;
 		$result['info']="您的出资不能大于估值！";
 		return $result;
 	}
 	$limit_price=$GLOBALS['db']->getOne("select limit_price from  ".DB_PREFIX."deal where id=".$deal_id);
 	if($stock_value>$limit_price){
 		$result['status']=0;
 		$result['info']="您的估值要小于融资金额！";
 		return $result;
 	}
 	$invote_mini_money=$GLOBALS['db']->getOne("select invote_mini_money from  ".DB_PREFIX."deal where id=".$deal_id);
 	$invote_mini_moneys=number_format(($invote_mini_money/10000),2);
 	if(ceil($stock_value%$invote_mini_money)>0){
 		$result['status']=0;
 		$result['info']="项目估值不是".$invote_mini_moneys."万元的整数倍";
 		return $result;
	}
 	//投资金额不能低于 单次投资的金额
 	$mini_info=mini_investor_money($money,$deal_id);
 	if($mini_info['status']==0){
 		return $mini_info;
 	}
 	$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
 	if($GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."investment_list (user_id,deal_id,stock_value,money,num,investment_reason,funding_to_help,is_partner,type,create_time) VALUES($user_id,$deal_id,$stock_value,$money,$num,'".$investment_reason."','".$funding_to_help."',$is_partner,0,".NOW_TIME.")")>0){
 		investor_is_success($deal_id);
 		$result['status']=1;
 		$result['info']="出资成功！";
 		syn_deal($deal_id);
 		return $result;
 	}else{
 		$result['status']=0;
 		$result['info']="出资失败！";
 		return $result;
 	}
 }
 //申请领投
 function investor_applicate_leader($deal_id,$user_id,$type=''){
 	if(!$deal_id){
 		showErr("您查找的页面不存在");
 	}
 	$cates=$GLOBALS['db']->getAllCached("SELECT name,id FROM ".DB_PREFIX."deal_cate ORDER BY sort ASC");
 	$row=array();
 	$row=$GLOBALS['db']->getRow("SELECT * from ".DB_PREFIX."investment_list where deal_id=$deal_id and user_id=$user_id and type=1 ");
 	
 	if($row){
 		if($row['status']==1){
 			showSuccess("您的申请已经通过");
 		}
 		$row['cates']=unserialize($row['cates']);
 		foreach($row['cates'] as $k=>$v){
 			foreach($cates as $k1=>$v1){
 				if($k==$v1['id']){
 					$cates[$k1]['selected']=1;
 				}
 			}
 		}
 	}
 	$GLOBALS['tmpl']->assign("item",$row);
 	$GLOBALS['tmpl']->assign("cates",$cates);
 	$GLOBALS['tmpl']->assign("deal_id",$deal_id);
 	$GLOBALS['tmpl']->assign("type",$type);
 	$GLOBALS['tmpl']->display("user_applicate_leader.html");
 }
 //判断是否够当前项目的资金
 function mini_investor_money($money,$deal_id){
 	$invote_mini_money=$GLOBALS['db']->getOne("select invote_mini_money from ".DB_PREFIX."deal where id=$deal_id ");
 	$return=array('status'=>0,'info'=>'');
 	if($money<$invote_mini_money){
 		$return['info']='投资的金额不能低于'.($invote_mini_money/10000).'万元';
 	}else{
 		$return['status']=1;
 	}
 	return $return;
 }
 
 function investor_save($id,$ajax='',$identify_name,$identify_number,$image1,$image2)
 {
 	if($identify_name==null){
 		$data['status'] = 0;
 		$data['info'] = "真实姓名不能为空！";
 		return $data;
 	}
 	if($identify_number==null){
 		$data['status'] = 0;
 		$data['info'] = "身份证号码不能为空！";
 		return $data;
 	}
 	if(!isCreditNo($identify_number)){
 		$data['status'] = 0;
 		$data['info'] = "请输入正确的身份证号码!";
 		return $data;
	}
 	if($image1['url']==null&&app_conf('IDENTIFY_POSITIVE')){
 		$data['status'] = 0;
 		$data['info'] = "请上传身份证正面照片！";
 		return $data;
 	}
 	if($image2['url']==null&&app_conf('IDENTIFY_NAGATIVE')){
 		$data['status'] = 0;
 		$data['info'] = "请上传身份证背面照片！";
 		return $data;
 	}
 	if($GLOBALS['db']->query("update ".DB_PREFIX."user set identify_name='".$identify_name."',identify_number='".$identify_number."',identify_positive_image='".$image1."',identify_nagative_image='".$image2."',investor_status=0 where id=".$id)>0){
 		$data['status'] = 1;
 		$data['info'] = "认证信息入录成功！";
 		$GLOBALS['db']->query("update ".DB_PREFIX."user set identify_business_name='',identify_business_licence='',identify_business_code='',identify_business_tax='' where id=".$id);
 		return $data;
 	}else{
 		$data['status'] = 0;
 		$data['info'] = "认证信息入录失败！";
 		return $data;
 	}
  }
 
 function investor_agency_save($id,$ajax='',$identify_business_name,$identify_business_licence,$identify_business_code,$identify_business_tax,$identify_name,$identify_number)
 {
 	if($identify_name==null){
 		$data['status'] = 0;
 		$data['info'] = "真实姓名不能为空！";
 		return $data;
 	}
 	if($identify_number==null){
 		$data['status'] = 0;
 		$data['info'] = "身份证号码不能为空！";
 		return $data;
 	}
 	if(!isCreditNo($identify_number)){
 		$data['status'] = 0;
 		$data['info'] = "请输入正确的身份证号码!";
 		return $data;
	}
 	if($identify_business_name==null){
 		$data['status'] = 0;
 		$data['info'] = "机构名称不能为空！";
 		return $data;
 	}
 	if($identify_business_licence==null&&app_conf('BUSINESS_LICENCE')){
 		$data['status'] = 0;
 		$data['info'] = "请上传营业执照！";
 		return $data;
 	}
 	if($identify_business_code==null&&app_conf('BUSINESS_CODE')){
 		$data['status'] = 0;
 		$data['info'] = "请上传组织机构代码证照片！";
 		return $data;
 	}
 	if($identify_business_tax==null&&app_conf('BUSINESS_TAX')){
 		$data['status'] = 0;
 		$data['info'] = "请上传税务登记证照片！";
 		return $data;
 	}
 	if($GLOBALS['db']->query("update ".DB_PREFIX."user set identify_name='".$identify_name."',identify_number='".$identify_number."', identify_business_name='".$identify_business_name."',identify_business_licence='".$identify_business_licence."',identify_business_code='".$identify_business_code."',identify_business_tax='".$identify_business_tax."',investor_status=0 where id=".$id)>0){
 		$data['status'] = 1;
 		$data['info'] = "认证信息入录成功！";
 		//$GLOBALS['db']->query("update ".DB_PREFIX."user set identify_name='',identify_number='',identify_positive_image='',identify_nagative_image='' where id=".$id);
 		return $data;
 	}else{
 		$data['status'] = 0;
 		$data['info'] = "认证信息入录失败！";
 		return $data;
 	}
  }
  function settings_invest_info($from='web',$user_info)
	{	
		
		
		if(!$user_info)
		{
			if($from='web')
			{
				app_redirect(url("user#login"));
			}
			elseif($from='wap')
			{
				app_redirect(url_wap("user#login"));
			}elseif ($from=='app')
			{
				
			}
		}
		 
		$data=$user_info;
		if($data)
		{
			//1 判断是否申请过，未申请的话，跳转到investor#index进行
			if($data['is_investor']>0)
			{
				if($from=='web'||$from=='wap'){
					$GLOBALS['tmpl']->assign("user_investment",$data);
					$GLOBALS['tmpl']->display("settings_invest_info.html");
				}elseif($from=='app'){
					
				}
			}
			//未申请
			elseif($data['is_investor']==0)
			{
				if($from=='web')
				{
					app_redirect(url("investor#index"));
				}
				elseif($from=='wap')
				{
					app_redirect(url_wap("investor#index"));
				}elseif($from=='app'){
					
				}
			}
		}
 		
	}
	function LOGIN_DES_KEY(){
		if(!es_session::is_set("DES_KEY")){
			require_once APP_ROOT_PATH."system/utils/es_string.php";
			es_session::set("DES_KEY",es_string::rand_string(50));
		}
		return es_session::get("DES_KEY");
	}
	function set_nav_top($module,$action='index',$id=0){
		
		if($module=='article_cate'&&$action=='index'&&$id==0){
			return array("top"=>array('name'=>'首页','url'=>url('index')),'list'=>array(name=>'文章列表','url'=>url('article_cate')));
		}elseif($module=='article_cate'&&$action=='index'&&$id>0){
			$cate_name=$GLOBALS['db']->getOne("select title from ".DB_PREFIX."article_cate where id=$id ");
			return array("top"=>array('name'=>'首页','url'=>url('index')),'list'=>array('name'=>'文章列表','url'=>url('article_cate')),'cate_child'=>array('name'=>$cate_name,'url'=>url('article_cate#index',array('id'=>$id))));
		}elseif($module=='article'&&$action=='index'&&$id>0){
			$article=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where id=$id ");
			if($article['cate_id']>0){
				$cate_name=$GLOBALS['db']->getOne("select title from ".DB_PREFIX."article_cate where id=".$article['cate_id']);
				return array("top"=>array('name'=>'首页','url'=>url('index')),'list'=>array('name'=>'文章列表','url'=>url('article_cate')),'cate_child'=>array('name'=>$cate_name,'url'=>url('article_cate#index',array('id'=>$article['cate_id']))),'article'=>array('name'=>$article['title']));
			}else{
 				return array("top"=>array('name'=>'首页','url'=>url('index')));
 			}
		}elseif($module=='faq'&&$id==0){
			return array("top"=>array('name'=>'首页','url'=>url('index')),'list'=>array(name=>'文章列表','url'=>url('article_cate')),'faq'=>array(name=>'常见问题','url'=>url('faq')));
		}
		elseif($module=='help'&&$id==0){
			return array("top"=>array('name'=>'首页','url'=>url('index')),'list'=>array(name=>'文章列表','url'=>url('article_cate')),'help'=>array(name=>'帮助列表','url'=>url('help#show')));
		}
	}
	
	function get_deal_cate_list($type){
		$info=array();
		//广告轮播
		if($type==0){
			//产品众筹
			$image_list_condition=" and type=1";
		}elseif($type==1){
			//股权众筹
			$image_list_condition=" and type=2";
		}
		
		
		$image_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."index_image where 1=1 ".$image_list_condition);
 		$info['image_list']=$image_list;
 		
 		$limit='0,3';
 		$condition=" type=".$type;
 		//热门项目
 		$hot_condition=$condition." and is_hot=1";
 		$hot_list=get_deal_list($limit,$hot_condition,'','deal_cate');
   		$info['hot_list']=$hot_list['list'];
 		//推荐项目
 		$recommend_condition=$condition."  and is_recommend=1 ";
 		$recommend_list=get_deal_list($limit,$recommend_condition,'','deal_cate');
   		$info['recommend_list']=$recommend_list['list'];
 		//经典项目
 		$classic_condition=$condition."  and is_classic=1 ";
 		$classic_list=get_deal_list($limit,$classic_condition,'','deal_cate');
   		$info['classic_list']=$classic_list['list'];
 		//最新预热-preheat
 		$preheat_limit='0,2';
 		$preheat_condition=$condition." and ".NOW_TIME." - d.begin_time < ".(24*3600)." and ".NOW_TIME." - d.begin_time <  0 ";
  		//$preheat_condition=$condition." ";
  		
  		$preheat_list=get_deal_list($preheat_limit,$preheat_condition,'','deal_cate_preheat');
   		$info['preheat_list']=$preheat_list['list'];
 		//项目动态
 		$deal_log_limit='0,18';
 		$deal_log_condition=" d.type=$type and d.is_effect = 1  ";
 		$deal_log_order_by=" l.create_time desc";
 		$log_list=deal_log_list($deal_log_limit,$deal_log_condition,$deal_log_order_by);
  		$info['log_list']=$log_list;
  		
  		return $info;
	}
	//获取提现银行
	function get_bank_list(){
		$bank=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."bank");
		$bank_list=array("recommend"=>array(),'other'=>array());
		$payment_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,1) and class_name = 'YeepayInvestmentPass' ");
		if($payment_count){
			foreach($bank as $k=>$v){
				if($v['is_support_tzt']==1){
					$bank_list['recommend'][]=$v;
				}else{
					$bank_list['other'][]=$v;
				}
			}
		}else {
			foreach($bank as $k=>$v){
				if($v['is_rec']==1){
					$bank_list['recommend'][]=$v;
				}else{
					$bank_list['other'][]=$v;
				}
			}
		}
				
		return $bank_list;
	}
	//检测手机是否可以绑定
	function check_registor_mobile($mobile,$ajax=1){
		if(strlen($mobile)< 0 || strlen($mobile)== 0){
			showErr("请输入手机号码",$ajax,"");
		}
		if(!check_mobile($mobile))
		{
			showErr("请填写正确的手机号码",$ajax,"");	
		}
		if(strlen($mobile)>11){
			showErr("手机号码长度不能超过11位",$ajax,"");
		}
		$condition=" mobile='$mobile'";
		 
		$num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where $condition");
		if($num>0){
			showErr("手机已存在,请重新输入",$ajax,"");
		}
	}
	//检测手机是否可以绑定
	function check_registor_email($email,$ajax=1){
		if(strlen($email)<=0 ){
			showErr("请输入邮箱",$ajax,"");
		}
		if(!check_email($email))
		{
			showErr("请填写正确的邮箱",$ajax,"");	
		}
		 
		$condition=" email='$email'";
		 
		$num=$GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where $condition");
		if($num>0){
			showErr("邮箱已存在,请重新输入",$ajax,"");
		}
	}
	/**
 * 验证身份证号
 * @param $vStr
 * @return bool
 */
function isCreditNo($vStr)
{
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
 
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
 
    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
 
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
 
    if ($vLength == 18)
    {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
 
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18)
    {
        $vSum = 0;
 
        for ($i = 17 ; $i >= 0 ; $i--)
        {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
 
        if($vSum % 11 != 1) return false;
    }
 
    return true;
}

function get_carray_info($user_bank_id){
	$bank_info=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."user_bank where id=$user_bank_id");
	if(empty($bank_info)){
		
		return "开户名:".$GLOBALS['user_info']['ex_real_name']." ".$bank_info['ex_account_bank']." 卡号:".$bank_info['ex_account_info'];		
		
	}else{
		if($bank_info['type']==1){
			return "开户名:".$bank_info['real_name']." ".$bank_info['bank_name']." 卡号:".$bank_info['bankcard']." 开户地点:".$bank_info['region_lv2'].$bank_info['region_lv3'].$bank_info['bankzone'].'(易宝快捷)';
		}else{			
			return "开户名:".$bank_info['real_name']." ".$bank_info['bank_name']." 卡号:".$bank_info['bankcard']." 开户地点:".$bank_info['region_lv2'].$bank_info['region_lv3'].$bank_info['bankzone'];			
		}
	}
}
//获取来源网站
function set_source_url(){
  	 if(!es_session::get("source_url")&&!$GLOBALS['user_info']){
 	 	if($_SERVER['HTTP_REFERER']){
 	 		$source_url=$_SERVER['HTTP_REFERER'];
 	 		$url=parse_url($source_url);
 	 		if($url['host']!=$_SERVER['HTTP_HOST']){
 	 			es_session::set("source_url",$url['host']);
 	 		}
 	 		
 	 	}
	 	
	 }
}
/**
 * 获得当前使用的，资金管理名称
 * @return string
 */
function getCollName(){
	
	return $GLOBALS['db']->getOne("select class_name from ".DB_PREFIX."collocation where is_effect = 1 limit 1");
	//if(intval(app_conf("OPEN_IPS"))==1){
	//	return 'Ips';
	//}else if(intval(app_conf("OPEN_IPS"))==2){
	//	return 'Yeepay';
	//}else if(intval(app_conf("OPEN_IPS"))==3){
	//	return 'Baofoo';
	//}else {
	//	return '';
	//}
}
function get_http()
{
	return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}
function get_domain()
{
	/* 协议 */
	$protocol = get_http();
	
	if(app_conf("SITE_DOMAIN")!="")
	{
		 return $protocol.app_conf("SITE_DOMAIN");
	}

	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		/* 端口 */
		if (isset($_SERVER['SERVER_PORT']))
		{
			$port = ':' . $_SERVER['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
			{
				$port = '';
			}
		}
		else
		{
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}
function get_host()
{


	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'];
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'];
		}
	}
	return $host;
}

//项目成功发送短信、回报短信(所有成功项目的支持人、项目创立者）
function send_deal_success(){
	if(app_conf("SMS_ON")==0){
		return false;
	}
	//项目成功发起者短信
	$deal_s_user=$GLOBALS['db']->getAll("select d.*,u.mobile from ".DB_PREFIX."deal d LEFT JOIN ".DB_PREFIX."user u ON u.id = d.user_id where d.is_success='1' and d.is_has_send_success='0' and d.is_delete = 0 ");
	$tmpl3=$GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_USER_S'");
	$tmpl_content3 = $tmpl3['content'];
	
	foreach ($deal_s_user as $k=>$v){
		if($v['id']){
		$user_s_msg['user_name']=$v['user_name'];
		$user_s_msg['deal_name']=$v['name'];
	
		$GLOBALS['tmpl']->assign("user_s_msg",$user_s_msg);
		$msg3=$GLOBALS['tmpl']->fetch("str:".$tmpl_content3);
		$msg_data3['dest']=$v['mobile'];
		$msg_data3['send_type']=0;
		$msg_data3['content']=addslashes($msg3);
		$msg_data3['send_time']=0;
		$msg_data3['title']='项目成功发起者-'.$v['name'].'-项目ID-'.$v['id'];;
		$msg_data3['is_send']=0;
		$msg_data3['create_time'] = NOW_TIME;
		$msg_data3['user_id'] = $v['user_id'];
		$msg_data3['is_html'] = $tmpl3['is_html'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data3); //插入
	
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal SET is_has_send_success='1' WHERE id = ".$v['id']);
		}
	}
	
	$success_deal_user=$GLOBALS['db']->getAll("SELECT dlo.* FROM ".DB_PREFIX."deal_order dlo LEFT JOIN ".DB_PREFIX."deal d ON d.id= dlo.deal_id WHERE d.is_success='1' and d.is_has_send_success='0' and d.is_delete = 0 AND dlo.order_status='3' AND dlo.is_success='1' AND dlo.is_has_send_success=0 ");
	if($success_deal_user){
		//项目成功支持者
		$tmpl=$GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_DEAL_SUCCESS'");
		$tmpl_content = $tmpl['content'];
		
		foreach ($success_deal_user as $k=>$v){
			if($v['id']){
			$success_user_info['user_name'] = $v['user_name'];
			$success_user_info['deal_name'] = $v['deal_name'];
			//封装发送到前台($success_user_info前台取)
			$GLOBALS['tmpl']->assign("success_user_info",$success_user_info);
			$msg=$GLOBALS['tmpl']->fetch("str:".$tmpl_content);
			$msg_data['dest']=$v['mobile'];
			$msg_data['send_type']=0;
			$msg_data['content']=addslashes($msg);
			$msg_data['send_time']=0;
			$msg_data['is_send']=0;
			$msg_data['title']='项目成功支持者-'.$v['deal_name'].'-订单号'.$v['id'];;
			$msg_data['create_time'] = NOW_TIME;
			$msg_data['user_id'] = $v['user_id'];
			$msg_data['is_html'] = $tmpl['is_html'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
			
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal_order SET is_has_send_success='1' WHERE id = ".$v['id']);
			}
		}
	}
	
}


//项目失败发送短信(支持人、项目发起人)
function send_deal_fail(){
	if(app_conf("SMS_ON")==0){
		return false;
	}
	//项目失败发起者短信
	$deal_f_user=$GLOBALS['db']->getAll("select d.*,u.mobile from ".DB_PREFIX."deal d LEFT JOIN ".DB_PREFIX."user u ON u.id = d.user_id where d.is_success='0' and d.is_has_send_success='0' and d.is_delete = 0 and d.support_amount < (d.limit_price-(select sum(virtual_person*price) FROM ".DB_PREFIX."deal_item where deal_id=d.id )) and d.end_time < ".NOW_TIME);
	
	$tmpl2=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_USER_F'");
	$tmpl_content2 = $tmpl2['content'];
	foreach ($deal_f_user as $k=>$v){
		$user_f_msg['user_name']=$v['user_name'];
		$user_f_msg['deal_name']=$v['name'];
		$GLOBALS['tmpl']->assign("user_f_msg",$user_f_msg);
		$msg2=$GLOBALS['tmpl']->fetch("str:".$tmpl_content2);
		$msg_data2['dest']=$v['mobile'];
		$msg_data2['send_type']=0;
		$msg_data2['content']=addslashes($msg2);
		$msg_data2['send_time']=0;
		$msg_data2['is_send']=0;
		$msg_data2['create_time'] = get_gmtime();
		$msg_data2['user_id'] = $v['user_id'];
		$msg_data2['is_html'] = $tmpl2['is_html'];
		$msg_data2['title']='项目失败发起者-'.$v['name'].'-项目ID-'.$v['id'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data2); //插入
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal SET is_has_send_success='1' WHERE id = ".$v['id']);
 	}
	
	//支持人
	$tmpl=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_DEAL_FAIL'");
	$tmpl_content = $tmpl['content'];
	$fail_deal_user = $GLOBALS['db']->getAll("SELECT dlo.* FROM ".DB_PREFIX."deal_order dlo LEFT JOIN ".DB_PREFIX."deal d ON d.id= dlo.deal_id WHERE d.is_success='0' and d.is_has_send_success='0' and d.is_delete = 0 and d.support_amount < (d.limit_price-(select sum(virtual_person*price) FROM ".DB_PREFIX."deal_item where deal_id=d.id )) and d.end_time < ".NOW_TIME." AND dlo.order_status='3' AND dlo.is_success='1' AND dlo.is_has_send_success=0 ");
	foreach ($fail_deal_user as $k=>$v){
		$fail_user_info['user_name']=$v['user_name'];
		$fail_user_info['deal_name']=$v['deal_name'];
		$GLOBALS['tmpl']->assign('fail_user_info',$fail_user_info);
		$msg=$GLOBALS['tmpl']->fetch("str:".$tmpl_content);
		$msg_data['dest']=$v['mobile'];
		$msg_data['send_type']=0;
		$msg_data['content']=addslashes($msg);
		$msg_data['send_time']=0;
		$msg_data['is_send']=0;
		$msg_data['title']='项目失败支持人-'.$v['deal_name'].'-订单号'.$v['id'];
		$msg_data['create_time'] = get_gmtime();
		$msg_data['user_id'] = $v['user_id'];
		$msg_data['is_html'] = $tmpl['is_html'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."deal_order SET is_has_send_success='1' WHERE id = ".$v['id']);
	}
	
}

//注册验证成功发送短信
function send_register_success($user_id=0,$user_info=array()){
	if(app_conf("SMS_ON")==0 && ($user_id == 0 || !$user_info)){
		return false;
	}
	if(!$user_info){
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
	}
	if($user_info['mobile']==""){
		return false;
	}
	$tmpl=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name='TPL_SMS_USER_VERIFY'");
	$tmpl_content = $tmpl['content'];
	if ($user_info){
		$success_user_info['user_name']=$user_info['user_name'];
		$GLOBALS['tmpl']->assign('success_user_info',$success_user_info);
		$msg=$GLOBALS['tmpl']->fetch("str:".$tmpl_content);
		$msg_data['dest']=$user_info['mobile'];
		$msg_data['send_type']=0;
		$msg_data['content']=addslashes($msg);
		$msg_data['send_time']=0;
		$msg_data['is_send']=0;
		$msg_data['title']='注册成功';
		$msg_data['create_time'] = NOW_TIME;
		$msg_data['user_id'] = $user_info['id'];
		$msg_data['is_html'] = $tmpl['is_html'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
	}
	
}


/**
 * 将单个图片同步到远程的图片服务器
 * @param string $url 本地的图片地址，"./public/......"
 */
function syn_to_remote_image_server($url)
{
	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		if($GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
		{
			$pathinfo = pathinfo($url);
			$file = $pathinfo['basename'];
			$dir = $pathinfo['dirname'];
			$dir = str_replace("./public/", "", $dir);
			$filefull = SITE_DOMAIN.APP_ROOT."/public/".$dir."/".$file;
			$syn_url = $GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php?username=".$GLOBALS['distribution_cfg']['OSS_ACCESS_ID']."&password=".$GLOBALS['distribution_cfg']['OSS_ACCESS_KEY']."&file=".
					$filefull."&path=".$dir."/&name=".$file."&act=0";
			@file_get_contents($syn_url);
		}
		elseif($GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
		{
			$pathinfo = pathinfo($url);
			$file = $pathinfo['basename'];
			$dir = $pathinfo['dirname'];
			$dir = str_replace("./public/", "public/", $dir);
			
			require_once APP_ROOT_PATH."system/alioss/sdk.class.php";
			$oss_sdk_service = new ALIOSS();			
			//设置是否打开curl调试模式
			$oss_sdk_service->set_debug_mode(FALSE);
			
			$bucket = $GLOBALS['distribution_cfg']['OSS_BUCKET_NAME'];
			$object = $dir."/".$file;
			$file_path = APP_ROOT_PATH.$dir."/".$file;
			
			$oss_sdk_service->upload_file_by_file($bucket,$object,$file_path);
		}
	}
	
}
function format_image_path($out)
{
	//对图片路径的修复
	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		$domain = $GLOBALS['distribution_cfg']['OSS_DOMAIN'];
	}
	else
	{
		$domain = SITE_DOMAIN.APP_ROOT;
	}
	$out = str_replace(APP_ROOT."./public/",$domain."/public/",$out);
	$out = str_replace("./public/",$domain."/public/",$out);
	return $out;
	
}

function replace_public($str){
    //对图片路径的修复
    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
    {
        $domain = $GLOBALS['distribution_cfg']['OSS_DOMAIN'];
    }
    else
    {
        $domain = SITE_DOMAIN.APP_ROOT;
    }

    return str_replace($domain."/public/","./public/",$str);
    
}
/**
 * 同步脚本样式缓存 $url:'public/runtime/statics/biz/'.$url.'.css';
 * @param unknown_type $url
 */
function syn_to_remote_file_server($url)
{
	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		if($GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
		{
			$pathinfo = pathinfo($url);
			$file = $pathinfo['basename'];
			$dir = $pathinfo['dirname'];
			$dir = str_replace("public/", "", $dir);
			$filefull = SITE_DOMAIN.APP_ROOT."/public/".$dir."/".$file;
			$syn_url = $GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php?username=".$GLOBALS['distribution_cfg']['OSS_ACCESS_ID']."&password=".$GLOBALS['distribution_cfg']['OSS_ACCESS_KEY']."&file=".
					$filefull."&path=".$dir."/&name=".$file."&act=0";
			@file_get_contents($syn_url);
		}
		elseif($GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
		{
			$pathinfo = pathinfo($url);
			$file = $pathinfo['basename'];
			$dir = $pathinfo['dirname'];
				
			require_once APP_ROOT_PATH."system/alioss/sdk.class.php";
			$oss_sdk_service = new ALIOSS();
			//设置是否打开curl调试模式
			$oss_sdk_service->set_debug_mode(FALSE);
				
			$bucket = $GLOBALS['distribution_cfg']['OSS_BUCKET_NAME'];
			$object = $dir."/".$file;
			$file_path = APP_ROOT_PATH.$dir."/".$file;
				
			$oss_sdk_service->upload_file_by_file($bucket,$object,$file_path);
		}
	}
	
}
function isWeixin(){ 
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $is_weixin = strpos($agent, 'micromessenger') ? true : false ;   
    if($is_weixin){
        return true;
    }else{
        return false;
    }
  }
function isios() {
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array (
				'iphone',
				'ipod',
				'mac',
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
}
  /**
 * 获得提现手续费
 * @param float $money  提现金额
 * @param user $user_info 用户信息
 * @return float 提现手续费
 */
function getCarryFee($money,$user_info){
	$fee = 0;
	$feel_type = 0;
	//获取手续费配置表
	$vip_id = 0;
	if($user_info['vip_id'] > 0 && $user_info['vip_state'] == 1){
		$vip_id = $user_info['vip_id'];
	}
		
	//手续费
	$fee_config = load_auto_cache("user_carry_config",array("vip_id"=>$vip_id));
	//如果手续费大于最大的配置那么取这个手续费
	if($money >=$fee_config[count($fee_config)-1]['max_price']){
		$fee = $fee_config[count($fee_config)-1]['fee'];
		$feel_type = $fee_config[count($fee_config)-1]['fee_type'];
	}
	else{
		foreach($fee_config as $k=>$v){
			if($money >= $v['min_price'] && $money <= $v['max_price']){
				$fee =  floatval($v['fee']);
				$feel_type = $v['fee_type'];
			}
		}
	}
		
	if($feel_type == 1){
		$fee = $money * $fee * 0.01;
	}
	
	return $fee;
}

function get_payment_list($from="pc"){
	if($from=='wap'){
		$condition=' ';
		if(!$GLOBALS['is_weixin']){
			$condition.=" and class_name!='Wwxjspay'";
		}
		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,2) $condition order by sort asc ");
		return $payment_list;
	}elseif($from=='pc'){
		$condition.=" and class_name!='Wwxjspay'";
		$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where is_effect = 1 and online_pay in(0,2) $condition order by sort asc ");
		return $payment_list;
	}
}
//是否托管项目
function is_ips_bill_no($deal_id){
	$deal=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id ");
	if($deal['ips_bill_no']>0){
		return true;
	}else{
		return false;
	}
}
//是否有安装第三方托管
function is_tg($status=false){
	$collotion=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."collocation where is_effect=1 ");
	if($collotion){
		if($status){
			return $collotion;
		}else{
			return true;
		}
		
	}else{
		return false;
	}
}

function is_user_tg(){
	if($GLOBALS['is_tg']&&($GLOBALS['user_info']['ips_acct_no']||$GLOBALS['user_info']['ips_mer_code'])){
		return 1;
	}else{
		return 0;
	}
}
function is_user_investor(){
	if($GLOBALS['user_info']['is_investor']==0){
		return 0; //未进行身份认证
	}else{
		if($GLOBALS['user_info']['is_investor'] >0 && $GLOBALS['user_info']['investor_status']==1)
			return 1; //通过审核
		else
			return 2; //审核中
	}
}
//显示成功
function showIpsInfo($msg,$jump='')
{		
	$GLOBALS['tmpl']->assign('msg',$msg);
	$GLOBALS['tmpl']->assign('jump',$jump);
	$GLOBALS['tmpl']->display("ips_show.html");
	exit;
}

/**
 * 获得用户余额
 * @param int $user_id
 * @param int $user_type
 * @return 
 * 	 * 			pMerCode 6 “平台”账号 否 由IPS颁发的商户号
				pErrCode 4 返回状态 否 0000成功； 9999失败；
				pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
				pIpsAcctNo 30 IPS账户号 否 查询时提交
				pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数
				pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数
				pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数
 */
function GetIpsUserMoney($user_id,$user_type = 0){
	$class_name = getCollName();
	require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
	$collocation_class = $class_name."_collocation";
	$collocation_object = new $collocation_class();
	$result = $collocation_object->QueryForAccBalance($user_id,$user_type);

	return $result;
}

//

/**
 * 投标数据格式化
 */
function return_deal_load_data($data,$user_info,$deal){
	$formatdata = array();
	if(isset($data['pMerBillNo']))
		$formatdata['pMerBillNo'] = $data['pMerBillNo'];
	if(isset($data['pContractNo']))
		$formatdata['pContractNo'] = $data["pContractNo"];
	if(isset($data['pP2PBillNo']))
		$formatdata['pP2PBillNo'] = $data['pP2PBillNo'];
		
	$formatdata['user_id'] = $user_info['id'];
	$formatdata['user_name'] = $user_info['user_name'];
	$formatdata['deal_id'] = $data['deal_id'];
	$formatdata['money'] = $data['money'];
	$formatdata['create_time'] = TIME_UTC;
	$formatdata['create_date'] = to_date(TIME_UTC);
	$formatdata['is_auto'] = intval($data['is_auto']);
	$formatdata['rebate_money'] = $formatdata['money'] * floatval(trim($deal['user_bid_rebate'])) * 0.01;
	
	$vip_state = $user_info['vip_state'];
	$income_value = "";
	if($vip_state==1){	
		$settinginfo= $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."vip_setting WHERE vip_id='".$user_info['vip_id']."' ");
		
		$deal['user_bid_score_fee'] = $settinginfo['multiple'];
		
		$jilu=rand(1,100);
		$income_type = 0;
		
		if($jilu<=$settinginfo['probability']){
			
			//随机奖励类型
			$rand_gift = array();
			//奖励红包
			if($settinginfo['red_envelope'] !=""){
				$rand_gift[1] = $settinginfo['red_envelope'];
			}
			//奖励利率
			if($settinginfo['rate'] > 0){
				$rand_gift[2] = $settinginfo['rate'];
			}
			//奖励积分
			if($settinginfo['integral'] > 0){
				$rand_gift[3] = $settinginfo['integral'];
			}
			
			//奖励礼品
			if($settinginfo['gift'] !=""){
				$rand_gift[4] = $settinginfo['gift'];
			}
			
			if(count($rand_gift)){
				$income_type =  array_rand($rand_gift);
				$retrun_gift = $rand_gift[$income_type];
				switch(intval($income_type)){
					case 1:
							$ids =  explode(",",$rand_gift[1]);
							$k= array_rand($ids);
							$income_value = $ids[$k];
						break;
					case 2:
							$income_value=$rand_gift[2];
						break;
					case 3:
							$income_value=$rand_gift[3];
						break;
					case 4:
							$ids =  explode(",",$rand_gift[4]);
							$k= array_rand($ids);
							$income_value = $ids[$k];
						break;
					default : 
						$income_value="";
						break;
				}
			}
			else{
				$income_value="";
			}
			
		}
		$formatdata['is_winning'] = $income_value== "" ? 0 : 1;
		$formatdata['income_type'] = $income_type;
		$formatdata['income_value'] = $income_value;
	}
	
	$formatdata['bid_score'] = $formatdata['money'] * floatval(trim($deal['user_bid_score_fee'])) * 0.01;
	
	
	return $formatdata;
}
/**
 *插入返利
 */
function add_referrals($referrals_data,$is_return=1){
	$add_data=array();
	$referrals_data['user_id']=intval($referrals_data['user_id']);
	$referrals_data['rel_user_id']=intval($referrals_data['rel_user_id']);
	
	if($referrals_data['user_id'] >0&&$referrals_data['user_name'] =='')
		$referrals_data['user_name']=$GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id= ".$referrals_data['user_id']);
	if($referrals_data['rel_user_id'] >0&&$referrals_data['rel_user_name'] =='')
		$referrals_data['rel_user_name']=$GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id= ".$referrals_data['rel_user_id']);
	$add_data['user_id']=$referrals_data['user_id'];
	$add_data['user_name']=$referrals_data['user_name'];
	$add_data['rel_user_id']=$referrals_data['rel_user_id'];
	$add_data['rel_user_name']=$referrals_data['rel_user_name'];
	$add_data['user_name']=$referrals_data['user_name'];
	$add_data['score'] = $referrals_data['score'];
	$add_data['order_id'] = intval($referrals_data['order_id']);
	$add_data['type'] = intval($referrals_data['type']);
	$add_data['create_time']=get_gmtime();

	$GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$add_data,'INSERT');
	if($is_return ==1)
		return   $GLOBALS['db']->insert_id();
}
/**
 * 发放注册返利
 * 要加载/system/libs/user.php文件
 * array $user_info 会员信息 要传入id,pid,user_name
 */
function send_referrals($user_info)
{	
	if(intval(app_conf("INVITE_REFERRALS")) >0)
	{
			$referral_ip_limit=app_conf("REFERRAL_IP_LIMI");//返利的IP限制
			if($referral_ip_limit ==1)//ip限制
			{
				$parent_info = $GLOBALS['db']->getRow("select login_ip,user_name from ".DB_PREFIX."user where id = ".$user_info['pid']);
				$referrals_data['user_name']=$parent_info['user_name'];
			}
			if(($referral_ip_limit ==1 && $parent_info['login_ip'] != CLIENT_IP) || $referral_ip_limit==0)
			{
				$referrals_data['user_id']=$user_info['pid'];
				$referrals_data['rel_user_id']=$user_info['id'];
				$referrals_data['rel_user_name']=$user_info['user_name'];
				$referrals_data['score']=intval(app_conf("INVITE_REFERRALS"));
				$referrals_data['type']=0;//0：注册奖励,1：购买奖励
				$insert_referrals_id=add_referrals($referrals_data,1);
				$re=modify_account(array('score'=>$referrals_data['score'],'point'=>$referrals_data['score']),$user_info['pid'],$log_msg='邀请会员'.$user_info['user_name']."注册成功，所得奖励",$param=array());
				
				if($re)
				{	
					$GLOBALS['db']->query("update ".DB_PREFIX."referrals set pay_time = ".NOW_TIME." where id =".intval($insert_referrals_id));//更新返利发放时间
					$GLOBALS['db']->query("update ".DB_PREFIX."user set is_send_referrals = 2 where id =".intval($user_info['id']));//更新返利已发放给推荐人
					
					send_notify($user_info['pid'],"您邀请会员".$user_info['user_name']."注册成功,奖励".$referrals_data['score']."积分","account#score");//通知会员获得邀请返利
					send_notify($user_info['pid'],"您邀请会员".$user_info['user_name']."注册成功,信用值增加".$referrals_data['score'],"account#point");//通知会员经验值增加
				}
			}
	}
	
}
/**
 *发放购买返利
 *要加载/system/libs/user.php文件
 * array $user_info 会员信息 要传入id,pid,user_name,referral_count
 * $order_id 订单id
 * */
function send_buy_referrals($user_info,$order_id)
{	
	$referrals_limit=intval(app_conf("REFERRAL_LIMIT"));
	
	if(intval(app_conf("BUY_INVITE_REFERRALS")) >0 && ( ($user_info['referral_count'] < $referrals_limit && $referrals_limit >0) || $referrals_limit<=0 ) )
	{
			$referral_ip_limit=app_conf("REFERRAL_IP_LIMI");//返利的IP限制
			if($referral_ip_limit ==1)//ip限制
			{
				$parent_info = $GLOBALS['db']->getRow("select login_ip,user_name from ".DB_PREFIX."user where id = ".$user_info['pid']);
				$referrals_data['user_name']=$parent_info['user_name'];
			}
			if(($referral_ip_limit ==1 && $parent_info['login_ip'] != CLIENT_IP) || $referral_ip_limit==0)
			{
				$referrals_data['user_id']=$user_info['pid'];
				$referrals_data['rel_user_id']=$user_info['id'];
				$referrals_data['rel_user_name']=$user_info['user_name'];
				$referrals_data['score']=intval(app_conf("BUY_INVITE_REFERRALS"));
				$referrals_data['order_id']=intval($order_id);
				$referrals_data['type']=1;//0：注册奖励,1：购买奖励
				
				$insert_referrals_id=add_referrals($referrals_data,1);//创建返利
				//更新会员购买返利次数
				$GLOBALS['db']->query("update ".DB_PREFIX."user set referral_count = referral_count +1 where id =".intval($user_info['id']));
				$re=modify_account(array('score'=>$referrals_data['score'],'point'=>$referrals_data['score']),$user_info['pid'],$log_msg='邀请会员'.$user_info['user_name']."订单支付成功，所得奖励",$param=array());
				if($re)
				{	
					$GLOBALS['db']->query("update ".DB_PREFIX."referrals set pay_time = ".NOW_TIME." where id =".intval($insert_referrals_id));//更新返利发放时间
					send_notify($user_info['pid'],"您邀请会员".$user_info['user_name']."订单支付成功,奖励".$referrals_data['score']."积分","account#score");//通知会员获得邀请返利
					send_notify($user_info['pid'],"您邀请会员".$user_info['user_name']."订单支付成功,信用值增加".$referrals_data['score'],"account#point");//通知会员经验值增加
				}
			}
	}
	
}
/**
 *更新会员等
 *$user_data 要包括会员id,会员等级,会员信用值
 * */
function user_leverl_syn($user_data)
{
	$user_current_level = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_level where id = ".intval($user_data['user_level']));
	$user_level = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_level where point <=".intval($user_data['point'])." order by point desc");
	if($user_current_level['point']<$user_level['point'])
	{
		$user_data['user_level'] = intval($user_level['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set user_level = ".$user_data['user_level']." where id = ".$user_data['id']);					
		$pm_content = "恭喜您，您已经成为".$user_level['name']."等级的会员！";	
		send_notify($user_data['id'], $pm_content, "account#point");
	}
	
	if($user_current_level['point']>$user_level['point'])
	{
		$user_data['user_level'] = intval($user_level['id']);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set user_level = ".$user_data['user_level']." where id = ".$user_data['id']);
		$pm_content = "很报歉，您的会员等级已经降为".$user_level['name']."！";	
		send_notify($user_data['id'], $pm_content, "account#point");
	}
}
function date_in($start_date,$end_date,$add_quotation = true){
	$sysc_start_time = to_timespan(to_date(to_timespan($start_date),'Y-m-d'));
	$sysc_end_time = to_timespan(to_date(to_timespan($end_date),'Y-m-d'));
	if ($sysc_end_time == 0)
		$sysc_end_time = $sysc_start_time;

	if ($sysc_start_time == 0)
		$sysc_start_time = $sysc_end_time;

	$str_in = '';
	for($s_date = $sysc_start_time; $s_date <= $sysc_end_time; $s_date += 86400){
		if ($add_quotation){
			if ($str_in == ""){
				$str_in = "'".to_date($s_date,'Y-m-d')."'";
			}else{
				$str_in .= ",'".to_date($s_date,'Y-m-d')."'";
			}
		}else{
			if ($str_in == ""){
				$str_in = to_date($s_date,'Y-m-d');
			}else{
				$str_in .= ",".to_date($s_date,'Y-m-d');
			}
		}
	}
	return $str_in;
}
//日期加减
function dec_date($date,$dec){
	//$sysc_start_time = to_timespan(to_date(to_timespan($date),'Y-m-d')) - $dec * 86400;

	return to_date(to_timespan($date)  - $dec * 86400,'Y-m-d');
}

//积分转成余额
function score_to_money($score)
{
	$score_array=array();
	$score_trade_number=intval(app_conf("SCORE_TRADE_NUMBER"))>0?intval(app_conf("SCORE_TRADE_NUMBER")):0;
	if($score_trade_number >0)
	{
		$score_array['score_money']=floatval(intval($score/$score_trade_number*100)/100);
		$score_array['score']=intval($score_trade_number*$score_array['score_money']);
	}
	else
	{
		$score_array['score_money']=0;
		$score_array['score']=0;
	}
	
	return $score_array;
}

/**
 * 	作用：将xml转为array
 */
function xmlToArray($xml)
{		
    //将XML转为array        
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
	return $array_data;
}
/**
	 * 	作用：array转xml
	 */
	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
 /*
  * $url 文件地址
  * $qrcode_name 生成的源文件
  * $qrcode_dir_logo 带logo的源文件
  */
  function get_qrcode_png($url,$qrcode_name,$qrcode_dir_logo){
  		require_once APP_ROOT_PATH.'system/utils/phpqrcode.php';
  		$value = $url; //二维码内容   
		$errorCorrectionLevel = 'L';//容错级别   
		$matrixPointSize = 6;//生成图片大小   
		//生成二维码图片   
		QRcode::png($value, $qrcode_name, $errorCorrectionLevel, $matrixPointSize, 2);   
		$logo = app_conf("SITE_LOGO");//准备好的logo图片   
		$QR = $qrcode_name;//已经生成的原始二维码图   
		if ($logo !== FALSE) {   
		    $QR = imagecreatefromstring(file_get_contents($QR));   
		    $logo = imagecreatefromstring(file_get_contents($logo));   
		    $QR_width = imagesx($QR);//二维码图片宽度   
		    $QR_height = imagesy($QR);//二维码图片高度   
		    $logo_width = imagesx($logo);//logo图片宽度   
		    $logo_height = imagesy($logo);//logo图片高度   
		    $logo_qr_width = $QR_width / 5;   
		    $scale = $logo_width/$logo_qr_width;   
		    $logo_qr_height = $logo_height/$scale;   
		    $from_width = ($QR_width - $logo_qr_width) / 2;   
		    //重新组合图片并调整大小   
		    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
		    $logo_qr_height, $logo_width, $logo_height);   
		}   
		//输出图片   
		imagepng($QR, $qrcode_dir_logo);   
   }
   /*
    * 
    */
    function check_tg($is_wap=0){
    	$is_tg=$GLOBALS['is_tg'];
		$is_user_tg=$GLOBALS['is_user_tg'];
		//$is_user_investor=$GLOBALS['is_user_investor'];
		$is_user_investor=$GLOBALS['db']->getOne("select is_investor from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		if($is_tg){
			if(!$is_user_tg){
				if($is_wap==1){
 					showErr('您未绑定资金托管账户，无法发起项目，点击确定后跳转到绑定页面',0,url_wap("collocation#CreateNewAcct",array('user_type'=>0,'user_id'=>$GLOBALS['user_info']['id'])));
				}else{
 					showErr('您未绑定资金托管账户，无法发起项目，点击确定后跳转到绑定页面',0,url("collocation#CreateNewAcct",array('user_type'=>0,'user_id'=>$GLOBALS['user_info']['id'])));
				}
			}else{
 				return true;
			}
		}else{
			if(!$is_user_investor){
				if($is_wap==1){
					showErr('您未进行身份认证，无法发起项目，点击确定后跳转到身份认证页面',0,url_wap("settings#security",array('method'=>'setting-id-box')));
				}else{
					showErr('您未进行身份认证，无法发起项目，点击确定后跳转到身份认证页面',0,url("settings#security",array('method'=>'setting-id-box')));
				}
 			}else{
 				return true;
			}
		}
    }
    /*
     * 
     */
     function deal_admin_nav($navs){
     	switch(INVEST_TYPE){
			case 1:
			unset($navs['statistics']['groups']['statistics_gq']);
			if(FINANCE_TYPE==0){
				//房产众筹是有投后管理
				unset($navs['dealcate']['groups']['deal']);
			}
			unset($navs['dealcate']['groups']['stock_transfer']);
				unset($navs['system']['groups']['sysconf']['nodes']['Contract_index']);
			break;
			case 2:
			unset($navs['statistics']['groups']['statistics']);
			break;
		}
		if(WEIXIN_TYPE==0){
			unset($navs['weixin']);
		}
		 if(LICAI_TYPE==0){
			 unset($navs['licai']);
		 }
		 if(FINANCE_TYPE==0){
			 unset($navs['dealcate']['groups']['finance']);
		 }
		 if(HOUSE_TYPE==0){
			 unset($navs['dealcate']['groups']['housecate']);
		 }

		return $navs;
     }
	function get_admin_nav($role_id,$adm_name){
		if($adm_name == app_conf('DEFAULT_ADMIN')){
			$navs = require_once APP_ROOT_PATH."system/admnav_cfg.php";
			$navs = deal_admin_nav($navs);
		}else{
			$navs = load_auto_cache("admin_nav",array('id'=>$role_id));
		}
		return $navs;
	}

    function  log_result($word) 
	{
		$file = "./public/notify_url.log";;
	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}
	function  log_result_notify($word) 
	{
	 
		$file = APP_ROOT_PATH."/public/msg_url.log";;
 	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}
		/**
 * 分页处理
 * @param string $type 所在页面
 * @param array  $args 参数
 * @param int $total_count 总数
 * @param int $page 当前页
 * @param int $page_size 分页大小
 * @param string $url 自定义路径
 * @param int $offset 偏移量
 * @return array
 */
function buildPage($type,$args,$total_count,$page = 1,$page_size = 0,$url='',$offset = 5){
	$pager['total_count'] = intval($total_count);
	$pager['page'] = $page;
	$pager['page_size'] = ($page_size == 0) ? 20 : $page_size;
	/* page 总数 */
	$pager['page_count'] = ($pager['total_count'] > 0) ? ceil($pager['total_count'] / $pager['page_size']) : 1;

	/* 边界处理 */
	if ($pager['page'] > $pager['page_count'])
		$pager['page'] = $pager['page_count'];

	$pager['limit'] = ($pager['page'] - 1) * $pager['page_size'] . "," . $pager['page_size'];
	$page_prev  = ($pager['page'] > 1) ? $pager['page'] - 1 : 1;
	$page_next  = ($pager['page'] < $pager['page_count']) ? $pager['page'] + 1 : $pager['page_count'];
	$pager['prev_page'] = $page_prev;
	$pager['next_page'] = $page_next;

	if (!empty($url)){
		$pager['page_first'] = $url . 1;
		$pager['page_prev']  = $url . $page_prev;
		$pager['page_next']  = $url . $page_next;
		$pager['page_last']  = $url . $pager['page_count'];
	}
	else{
		$args['page'] = '_page_';
		if(!empty($type)){
			if(strpos($type,'javascript:') === false){
				//$page_url = JKU($type,$args);
				$page_url = u($type,$args);
			}else{
				$page_url = $type;
				
			}
		}else{
			$page_url = 'javascript:;';
		}
 		$pager['page_first'] = str_replace('_page_',1,$page_url);
		$pager['page_prev']  = str_replace('_page_',$page_prev,$page_url);
		$pager['page_next']  = str_replace('_page_',$page_next,$page_url);
		$pager['page_last']  = str_replace('_page_',$pager['page_count'],$page_url);
	}
	$pager['page_nums'] = array();
	if($pager['page_count'] <= $offset * 2){
		for ($i=1; $i <= $pager['page_count']; $i++){
			$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
		}
	}else{
		if($pager['page'] - $offset < 2){
			$temp = $offset * 2;
			for ($i=1; $i<=$temp; $i++){
				$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
			}
			$pager['page_nums'][] = array('name'=>'...');
			$pager['page_nums'][] = array('name' => $pager['page_count'],'url' => empty($url) ? str_replace('_page_',$pager['page_count'],$page_url) : $url . $pager['page_count']);
		}else{
			$pager['page_nums'][] = array('name' => 1,'url' => empty($url) ? str_replace('_page_',1,$page_url) : $url . 1);
			$pager['page_nums'][] = array('name'=>'...');
			$start = $pager['page'] - $offset + 1;
			$end = $pager['page'] + $offset - 1;
			if($pager['page_count'] - $end > 1){
				for ($i=$start;$i<=$end;$i++){
					$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
				}

				$pager['page_nums'][] = array('name'=>'...');
				$pager['page_nums'][] = array('name' => $pager['page_count'],'url' => empty($url) ? str_replace('_page_',$pager['page_count'],$page_url) : $url . $pager['page_count']);
			}else{
				$start = $pager['page_count'] - $offset * 2 + 1;
				$end = $pager['page_count'];
				for ($i=$start;$i<=$end;$i++){
					$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
				}
			}
		}
	}
	return $pager;
}

function parse_url_tag_coomon($str)
{
 	$str = substr($str,2);
	$str_array = explode("|",$str);
	$route = $str_array[0];
	$param_tmp = explode("&",$str_array[1]);
	$param = array();
	foreach($param_tmp as $item)
	{
		if($item!='')
		$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
		$param[$item_arr[0]] = $item_arr[1];
	}
 	return url($route,$param);
}

//解析URL标签
// $str = u:acate#index|id=10&name=abc
function parse_url_tag($str)
{
	$key = md5("URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	$str = substr($str,2);
	$str_array = explode("|",$str);
	$route = $str_array[0];
	$param_tmp = explode("&",$str_array[1]);
	$param = array();
	foreach($param_tmp as $item)
	{
		if($item!='')
		$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
		$param[$item_arr[0]] = $item_arr[1];
	}
	$GLOBALS[$key]= url($route,$param);
	set_dynamic_cache($key,$GLOBALS[$key]);
	return $GLOBALS[$key];
}

function parse_url_tag_wap($str)
{
	$key = md5("URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	$str = substr($str,2);
	$str_array = explode("|",$str);
	$route = $str_array[0];
	$param_tmp = explode("&",$str_array[1]);
	$param = array();
	foreach($param_tmp as $item)
	{
		if($item!='')
		$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
		$param[$item_arr[0]] = $item_arr[1];
	}
	$GLOBALS[$key]= url_wap($route,$param);
	set_dynamic_cache($key,$GLOBALS[$key]);
	return $GLOBALS[$key];
}
function save_log_common($money,$user_id,$log_msg='',$param=array()){
		if(floatval($money)!=0)
			{
				$log_info['log_info'] = $log_msg;
				$log_info['log_time'] = get_gmtime();
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
 				$adm_id = intval($adm_session['adm_id']);
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				if(is_array($param)&&count($param)>0){
					foreach($param as $k=>$v){
 						$log_info[$k] = $v;
					}
				}
				$log_info['money'] = floatval($money);
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
				
			}
			return true;
 	}
 //0 表示未支付 2表示已支付定金 3表示支付首付
function deal_order_progress($deal_id,$user_id,$progress=0){
	//$GLOBALS['db']->query("update  ".DB_PREFIX."deal_order set progress=$progress where deal_id=$deal_id and user_id=$user_id ");
}

function HASH_KEY(){
	if(!es_session::is_set("HASH_KEY")){
		require_once APP_ROOT_PATH."system/utils/es_string.php";
		es_session::set("HASH_KEY",es_string::rand_string(50));
	}
	return es_session::get("HASH_KEY");
}

function check_hash_key(){
	if(strim($_REQUEST['fhash'])!="" && md5(HASH_KEY())==md5($_REQUEST['fhash'])){
		return true;
	}
	else
		return false;
}
function number_price_format($price)
{
	if($price*100%100==0)
	$price= number_format(round($price,2));
	else
	$price = number_format(round($price,2),2);
	return $price;
}


//获取用户头像的文件名
function get_user_avatar($id,$type,$head_img='')
{
	if($head_img){
		if(strpos('http', $head_img)){
			return $head_img;
		}else{
			return get_domain().REAL_APP_ROOT.$head_img;
		}
	}else{
		$uid = sprintf("%09d", $id);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$path = $dir1.'/'.$dir2.'/'.$dir3;
					
		$id = str_pad($id, 2, "0", STR_PAD_LEFT); 
		$id = substr($id,-2);
		$avatar_file = APP_ROOT."/public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
		$avatar_check_file = APP_ROOT_PATH."public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
		if(file_exists($avatar_check_file)){
			return $avatar_file;
		}
	 	else{
	 		$num=$id%10;
	 		return APP_ROOT."/public/avatar/default/noavatar_".$num.".JPG";
	 	}
	}
 }

//获取手机端用户头像的文件名
function get_user_avatar_root($id,$type,$head_img='')
{
	if($head_img){
		if(strpos('http', $head_img)){
			return $head_img;
		}else{
			return get_domain().REAL_APP_ROOT.$head_img;
		}
	}else{
		$uid = sprintf("%09d", $id);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$path = $dir1.'/'.$dir2.'/'.$dir3;
					
		$id = str_pad($id, 2, "0", STR_PAD_LEFT); 
		$id = substr($id,-2);
		$avatar_file = get_domain().REAL_APP_ROOT."/public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
		$avatar_check_file = APP_ROOT_PATH."/public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
	 	if(file_exists($avatar_check_file))	
		return $avatar_file;
		else
		return get_domain().REAL_APP_ROOT."/public/avatar/noavatar_".$type.".gif";
	}
	
 }


function show_avatar($u_id,$type="middle",$head_img='')
{
	$key = md5("AVATAR_".$u_id.$type);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	else
	{
		$avatar_key = md5("USER_AVATAR_".$u_id); 
		$avatar_data = $GLOBALS['dynamic_avatar_cache'][$avatar_key];// 当前用户所有头像的动态缓存			
		if(!isset($avatar_data)||!isset($avatar_data[$key]))
		{
			$avatar_file = get_user_avatar($u_id,$type,$head_img);	
			$avatar_str = "<a href='".url("home",array("id"=>$u_id))."' style='text-align:center; display:inline-block;'>".
				   "<img src='".$avatar_file."'  />".
				   "</a>"; 			
			$avatar_data[$key] = $avatar_str;
			if(count($GLOBALS['dynamic_avatar_cache'])<500) //保存500个用户头像缓存
			{
				$GLOBALS['dynamic_avatar_cache'][$avatar_key] = $avatar_data;
			}			
		}
		else
		{
			$avatar_str = $avatar_data[$key];
		}
		$GLOBALS[$key]= $avatar_str;
		return $GLOBALS[$key];
	}
}

function show_wap_avatar($u_id,$type="middle",$head_img='')
{
	$key = md5("AVATAR_".$u_id.$type);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	else
	{
		$avatar_key = md5("USER_AVATAR_".$u_id);
		$avatar_data = $GLOBALS['dynamic_avatar_cache'][$avatar_key];// 当前用户所有头像的动态缓存
		if(!isset($avatar_data)||!isset($avatar_data[$key]))
		{
			$avatar_file = get_user_avatar($u_id,$type,$head_img);
			$avatar_str = "<a href='".url_wap("settings",array("id"=>$u_id))."' style='text-align:center; display:inline-block;'>".
					"<img src='".$avatar_file."'  />".
					"</a>";
			$avatar_data[$key] = $avatar_str;
			if(count($GLOBALS['dynamic_avatar_cache'])<500) //保存500个用户头像缓存
			{
				$GLOBALS['dynamic_avatar_cache'][$avatar_key] = $avatar_data;
			}
		}
		else
		{
			$avatar_str = $avatar_data[$key];
		}
		$GLOBALS[$key]= $avatar_str;
		return $GLOBALS[$key];
	}
}

function show_empty_avatar($u_id,$type="middle",$head_img)
{
	$key = md5("AVATAR_".$u_id.$type);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	else
	{
		$avatar_key = md5("USER_AVATAR_".$u_id);
		$avatar_data = $GLOBALS['dynamic_avatar_cache'][$avatar_key];// 当前用户所有头像的动态缓存
		if(!isset($avatar_data)||!isset($avatar_data[$key]))
		{
			$avatar_file = get_user_avatar($u_id,$type,$head_img);
			$avatar_str ="<img src='".$avatar_file."'  />";
			$avatar_data[$key] = $avatar_str;
			if(count($GLOBALS['dynamic_avatar_cache'])<500) //保存500个用户头像缓存
			{
				$GLOBALS['dynamic_avatar_cache'][$avatar_key] = $avatar_data;
			}
		}
		else
		{
			$avatar_str = $avatar_data[$key];
		}
		$GLOBALS[$key]= $avatar_str;
		return $GLOBALS[$key];
	}
}

function update_avatar($u_id)
{
	$avatar_key = md5("USER_AVATAR_".$u_id); 
	unset($GLOBALS['dynamic_avatar_cache'][$avatar_key]);
	$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
	$GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
}

function show_wap_blank_avatar($u_id,$type="middle",$head_img='')
{
	$key = md5("AVATAR_".$u_id.$type);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	else
	{
		$avatar_key = md5("USER_AVATAR_".$u_id);
		$avatar_data = $GLOBALS['dynamic_avatar_cache'][$avatar_key];// 当前用户所有头像的动态缓存
		if(!isset($avatar_data)||!isset($avatar_data[$key]))
		{
			$avatar_file = get_user_avatar($u_id,$type,$head_img);
			$avatar_str = "<a style='text-align:center; display:inline-block;'>".
					"<img src='".$avatar_file."'  />".
					"</a>";
			$avatar_data[$key] = $avatar_str;
			if(count($GLOBALS['dynamic_avatar_cache'])<500) //保存500个用户头像缓存
			{
				$GLOBALS['dynamic_avatar_cache'][$avatar_key] = $avatar_data;
			}
		}
		else
		{
			$avatar_str = $avatar_data[$key];
		}
		$GLOBALS[$key]= $avatar_str;
		return $GLOBALS[$key];
	}
}

function get_muser_avatar($id,$type,$head_img='')
{
	if($head_img){
		 return $head_img;
	}else{
		$uid = sprintf("%09d", $id);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$path = $dir1.'/'.$dir2.'/'.$dir3;
					
		$id = str_pad($id, 2, "0", STR_PAD_LEFT); 
		$id = substr($id,-2);
		$avatar_file = "./public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
		$avatar_check_file = APP_ROOT_PATH."public/avatar/".$path."/".$id."virtual_avatar_".$type.".jpg";
	
		if(file_exists($avatar_check_file))	
		return $avatar_file;
		else
		return "./public/avatar/noavatar_".$type.".gif";
	}
}
 function getMConfig(){
	$file_name=md5("m_config");
	$GLOBALS['fcache']->set_dir(APP_ROOT_PATH.'public/runtime/mapi/data_caches');
	$m_config = $GLOBALS['fcache']->get($file_name);
	if($m_config===false)
	{
		$m_config = array();
		$sql = "select code,val from ".DB_PREFIX."m_config";
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $item){
			$m_config[$item['code']] = $item['val'];
		}
		$GLOBALS['fcache']->set_dir(APP_ROOT_PATH.'public/runtime/mapi/data_caches');
		$GLOBALS['fcache']->set($file_name,$m_config);
	}
	return $m_config;
}

/** 获取当前时间戳，精确到毫秒 */
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec-date('Z'));
}

/** 格式化时间戳，精确到毫秒，x代表毫秒 */
function microtime_format($utc_time,  $format = 'H:i:s.x')
{
   if (empty ( $utc_time )) {
		return '';
	}
	
   $timezone = intval(app_conf('TIME_ZONE'));
   $time = $utc_time + $timezone * 3600;
   
   list($usec, $sec) = explode(".", $time);
   $date = date($format,$usec);
   return str_replace('x', $sec, $date);
}
//插入抽奖号
function insert_lottery_sn($order_info){
	$lottery['deal_id']=$order_info['deal_id'];
	$lottery['deal_item_id']=$order_info['deal_item_id'];
	$lottery['user_id']=$order_info['user_id'];
	$lottery['user_name']=$order_info['user_name'];
	$lottery['order_id']=$order_info['id'];
	$lottery['is_winner']=0;
	$max_lottery_sn=$GLOBALS['db']->getOne("select lottery_sn from ".DB_PREFIX."deal_order_lottery where deal_id = ".$order_info['deal_id']." and deal_item_id= ".$order_info['deal_item_id']." order by id desc");
	if($max_lottery_sn)
	{
		$sn_array=split_lottery_sn($max_lottery_sn);
		$lottery_max=$sn_array['sn_number'];
	}
	else
	{
		$lottery_max=10000000;
	}
	
	for($i=0;$i<$order_info['num'];$i++)
	{
		do{
			++$lottery_max;
			$lottery_sn=$order_info['deal_item_id'].$lottery_max;
			$lottery['lottery_sn']=$lottery_sn;
			$lottery['create_time']=get_gmtime();
			$lottery['time_msec']=microtime_float();
			
			$lottery_insert_id=$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_lottery",$lottery);
		}while(!$lottery_insert_id);
	}
	
}
/**
 * 幸运号确定处理  这没有对，幸运号数组$lottery_num,$deal_id,$user_id判断正确性，在函数外面判断
 * $lottery_num 幸运号数组
 * $lottery_list 幸运号数据库数据
 * $deal_id 项目id
 * $user_id 会员id(项目会员id)
 */
function handle_luckyer_lotter_sn($lottery_num,$lottery_list,$deal_id,$user_id)
{
	$retrun=array('status'=>0);
	$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=1,lottery_draw_time=".NOW_TIME." where lottery_sn in('".implode("','",$lottery_num)."') and deal_id=".$deal_id." and is_winner=0");
	$re=$GLOBALS['db']->affected_rows();
	if($re)
	{
		//更新未抽中的抽奖号状态为未抽中
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=2,lottery_draw_time=".NOW_TIME." where lottery_sn not in('".implode("','",$lottery_num)."') and deal_id=".$deal_id." and is_winner=0");
		
		$deal_update_log_info="幸运号如下：\n";
		foreach($lottery_list as $k=>$v){
			//更新订单是幸运订单
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_winner=1,lottery_draw_time=".NOW_TIME." where id=".intval($v['order_id'])." and is_winner=0");
			//站内通知
			$content="恭喜，您支持的".$v['deal_name']."的抽奖号：".$v['lottery_sn']."已被抽为幸运号";
			send_notify($v['user_id'],$content,"account#view_order",array('id'=>$v['order_id']));

			//短信通知
			$msg_data['dest']=$v['mobile'];
			$msg_data['send_type']=0;
			$msg_data['content']=addslashes($content);
			$msg_data['send_time']=0;
			$msg_data['title']='幸运号-'.$v['deal_name'];
			$msg_data['is_send']=0;
			$msg_data['create_time'] = NOW_TIME;
			$msg_data['user_id'] = $v['user_id'];
			$msg_data['is_html'] = 0;
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
			
			//动态内容
			$num=$GLOBALS['db']->getOne("select sum(num) from ".DB_PREFIX."deal_order where user_id=".$v['user_id']." and deal_id=".intval($v['deal_id'])." and deal_item_id=".intval($v['deal_item_id'])." and type=3 and order_status=3 and is_refund=0");
			$deal_update_log_info .=$v['user_name'].':'.$v['lottery_sn']." 支持数：".$num."\n";
		}
		//更新未抽中的订单为未抽中
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_winner=2,lottery_draw_time=".NOW_TIME." where is_winner=0 and type=3 and deal_id=".$deal_id."");
			
		//更新项目开奖时间
		$GLOBALS['db']->query("update ".DB_PREFIX."deal set lottery_draw_time=".NOW_TIME." where id=".intval($v['deal_id'])." and user_id= ".$user_id."");
		//更新子项目开奖时间
		$GLOBALS['db']->query("update ".DB_PREFIX."deal_item set lottery_draw_time=".NOW_TIME." where deal_id=".intval($v['deal_id'])." and type=2");
		
		//抽奖结果更新到项目动态
		$deal_update_data=array();
		$deal_update_data['log_info']=$deal_update_log_info;
		$deal_update_data['user_id']=$user_id;
		$deal_update_data['user_name']=$GLOBALS['user_info']['user_name'];
		$deal_update_data['deal_id']=$deal_id;
		$deal_update_data['create_time']=NOW_TIME;
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_log",$deal_update_data);
		
		//自动把没有被抽中的订单设置为已发放回报，已确认收到
		$order_repay_memo="感谢您的参与，希望下次您是幸运星";
	    $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_time=".NOW_TIME.",repay_memo='".$order_repay_memo."',repay_make_time=".NOW_TIME." where deal_id=".$deal_id." and is_winner=2 and type=3");
		
		//删除幸运号列表缓存
		rm_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
		
		$retrun['status']=1;
	}else{
		//删除幸运号列表缓存
		rm_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
		$retrun['status']=0;
	}
	
	return $retrun;
}
//获得指定订单的抽奖号抽奖
function get_order_lottery($order_id){
	$all_lottery_list=$GLOBALS['db']->getAll("select id,lottery_sn,is_winner from ".DB_PREFIX."deal_order_lottery where order_id=".intval($order_id)." ");
	$lottery_luckyer_list = array();
	$lottery_list=array();
	foreach($all_lottery_list as $k=>$v)
	{
		if($v['is_winner'] ==1)
		{
			$lottery_luckyer_list[]=$v;
		}else{
			$lottery_list[]=$v;
		}
	}
	$lottery_return['all_lottery_list']=$all_lottery_list;
	$lottery_return['lottery_list']=$lottery_list; //不是幸运号
	$lottery_return['lottery_luckyer_list']=$lottery_luckyer_list;//幸运号
	return $lottery_return;
}

//分离抽奖号 3310000001
function split_lottery_sn($lottery_sn)
{
	$sn_array=array();
	$sn_length=strlen($lottery_sn);
	$sn_array['sn_number']=substr($lottery_sn,-8);
	$sn_array['item_id']=substr($lottery_sn,0,$sn_length-8);
	
	return $sn_array;
}
function trim_utf8mb4($str){
	return  preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',$str);
}
/**
 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
 * @param string $user_name 姓名
 * @return string 格式化后的姓名
 */
function substr_cut($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}
/*
 * is_wap 0 表示wep 1表示wap 2表示APP
 */
function create_target_url($target,$is_wap = 0){
	$return_url = "";
	if(strpos($target,'URL-dealID-')!==FALSE){
		$deal_id = trim($target,'URL-dealID-');
		if($is_wap==0){
			$return_url = url("deal#show",array('id'=>$deal_id));
		}elseif($is_wap==1){
			$return_url = url_wap("deal#show",array('id'=>$deal_id));
		}
	}
	return $return_url;
}

/**
 * 积分商品 同步库存索引的key
 */
function syn_attr_stock_key($id)
{
    $attr_stock_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr_stock where goods_id = ".$id);
    foreach($attr_stock_list as $row)
    {
        $attr_ids = array();
        $attr_cfg = unserialize($row['attr_cfg']);
        foreach($attr_cfg as $goods_type_attr_id=>$good_attr_name)
        {
            $attr_ids[] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."goods_attr where goods_id = ".$id." and goods_type_attr_id = ".$goods_type_attr_id." and name='".$good_attr_name."'");
        }
        sort($attr_ids);
        $attr_ids = implode($attr_ids, "_");
        $GLOBALS['db']->query("update ".DB_PREFIX."goods_attr_stock set attr_key = '".$attr_ids."' where id =".$row['id']);
    }
}
function  get_deal_item_type($type){
 	switch($type){
 		case 0:
 		return '产品回报';
 		break;
 		case 1:
 		return '无私奉献';
 		break;
 		case 2:
 		return '抽奖';
 		break;
 	}
 }
 //验证网址
function check_url($url)
{
	$patern ='/^http[s]?:\/\/'.
	'(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184
	'|'. // 允许IP和DOMAIN（域名）
	'([0-9a-z_!~*\'()-]+\.)*'. // 域名- www.
	'([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域名
	'[a-z]{2,6})'.  // first level domain- .com or .museum
	'(:[0-9]{1,4})?'.  // 端口- :80
	'((\/\?)|'.  // a slash isn't required if there is no file name
	'(\/[0-9a-zA-Z_!~\'\(\)\[\]\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/';
	if(!empty($url) && !preg_match($patern,$url))
	{
		return false;
	}
	else
	return true;
}
	/* 
	 * 转成元
	 * $money 金额
	 */ 
	 function transform_yuan($money){
		$money =intval($money);
		if($money){
			return $money * 10000;
		}
	}
	/* 
	 * 元转成逆转万元
	 * $money 金额
	 */
	 function transform_wan($money){
		$money =intval($money);
		if($money){
			return $money/10000;
		}
	}
	/*
	 * 
	 */
	function deal_type_url($param=array(),$type = 0){
		if($type==1){
			return  url("deals#stock",$param);
		}elseif($type==2){
			return url("deals#house",$param);
		}
		elseif($type==3){
			return url("deals#selfless",$param);
		}elseif($type==4){
			return url("investor#invester_list",$param);
		}elseif($type==5){
			return url("stock_transfer",$param);
		}else{
			return url("deals",$param);
		}
	}
	/*
	 * @return 删除过期的验证码
	 */
	function delete_mobile_verify_code(){
		$time=app_conf("USER_SEND_VERIFY_TIME")?app_conf("USER_SEND_VERIFY_TIME"):300;
		$n_time=get_gmtime()-$time;
		//删除超过时间的验证码
		$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."mobile_verify_code WHERE create_time <=".$n_time);
	}
	
	//获取 房产众筹楼盘阶段
	function get_houses_status_list()
	{
		$houses_status=array(
			'0'=>"获取土地",
			'1'=>"取得土地使用权证书",
			'2'=>"施工图设计",
			'3'=>"工程施工图文件审批",
			'4'=>"取得建设工程规划许可证",
			'5'=>"取得施工许可证",
			'6'=>"开工建设",
			'7'=>"售楼处开发",
			'8'=>"开盘",
			'9'=>"施工监理",
			'10'=>"竣工验收",
			'11'=>"装修中",
			'12'=>"客户收房",
			'13'=>"项目完成"
		);
		return $houses_status;
	}
	
	/**
	 * 插入项目图片
	 * @param array $images_array 图片url数组名
	 * @param integer $deal_id 项目id
	 */
	function insert_deal_images($images_array,$deal_id)
	{
		foreach($images_array as $k=>$v)
		{
			$images_data=array();
			$images_data['deal_id']=$deal_id;
			$images_data['image']=replace_public(addslashes(trim($v)));
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_image",$images_data,"INSERT","","SILENT");
		}
	}
	
	function get_deal_images_list($deal_id,$deal_image)
	{
		//项目图片
		if($deal_image !='')
		{
			$deal_img[0]=array('image'=>$deal_image);
			$deal_imgs=$GLOBALS['db']->getAll("select image from ".DB_PREFIX."deal_image where deal_id = ".intval($deal_id)."");
			$deal_imgs_all=array_merge($deal_img,$deal_imgs);
		}
		else
		{
			$deal_imgs_all=$GLOBALS['db']->getAll("select image from ".DB_PREFIX."deal_image where deal_id = ".intval($deal_id)."");
		}
		
		return $deal_imgs_all;
	}


/**
 * 查看是否有开通投资通
 * @return bool
 */
function is_investment_pass(){
	$invest = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."payment where class_name='YeepayInvestmentPass'");
	if(intval($invest)>0){
		return true;
	}else{
		return false;
	}
}

/**
 * @param $user_bank_id 会员绑定的银行user_bank 的ID
 * @param $user_id  会员的ID
 * @return bool  true 表示 是投资通，false 表示不是投资通
 */
function check_refund_type($user_bank_id, $user_id){
	$user_bank_id = intval($user_bank_id);
	if($user_bank_id>0){
		$type = $GLOBALS['db']->getOne("select ub.type from ".DB_PREFIX."user_bank AS ub  where ub.user_id=".$user_id." and ub.id=".$user_bank_id);
		if(intval($type)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function deal_refund_auto(){

}
function create_app_js($app_conf){
 	$node_app=APP_ROOT_PATH."public/node_app.js";
	if(is_file($node_app)){
		$content=file_get_contents($node_app);
		$url = get_domain().APP_ROOT;
		$content=str_replace("{domain}",$url,$content);
		if($app_conf['IS_SMS_DIRECT']==0){
			$deal_msg_list= 'true';
		}else{
			$deal_msg_list= 'false';
		}
		$content=str_replace("{deal_msg_list}",$deal_msg_list,$content);
		$time = $app_conf['SEND_SPAN']?$app_conf['SEND_SPAN']*1000:500;
		$content=str_replace("{time}",$time,$content);

		$app=APP_ROOT_PATH."public/app.js";
		file_put_contents($app,$content);
	}
}

/**
 * 获取返回参与列表的url
 * @param $order_id 订单id
 * @param $terminal 手机端:wap/pc端 
 */
function url_get_account($order_id,$terminal,$is_array)
{
	$deal_type_array=array(
	'0'=>'index',
	'1'=>'mine_investor_status',
	'2'=>'house_index',
	'3'=>'selfless_index',
	'4'=>'mine_investor_finance'
	);

	$deal_type=intval($GLOBALS['db']->getOne("select d.type from ".DB_PREFIX."deal_order as ord left join ".DB_PREFIX."deal as d on d.id = ord.deal_id where ord.id= ".intval($order_id).""));
	
	if($terminal=='wap')
		$url_var=url_wap("account#".$deal_type_array[$deal_type]."");
	else
		$url_var=url("account#".$deal_type_array[$deal_type]."");
	
	if($is_array ==1)
	{
		return array('url'=>$url_var,'act_val'=>$deal_type_array[$deal_type],'deal_type'=>$deal_type);
	}
	else
	{
		return $url_var;
	}
}

/**
 * 获取快捷登录安装个数
 * $no_api 不算在已安装个数内的快捷登录接口字符串,用逗号分开
 * */
function get_api_login_count($no_api)
{
	if($no_api !='')
	{
		$no_api_array=explode(',',$no_api);
		$where= " class_name not in('".implode("',",$no_api_array)."')";
	}
	$api_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."api_login where ".$where.""));
	return $api_count;
}
?>