<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

//app项目用到的函数库
/**
 * 获取导航菜单
 */
//获取所有子集的类
class ChildIds
{
	public function __construct($tb_name)
	{
		$this->tb_name = $tb_name;	
	}
	private $tb_name;
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$childItem_arr = $GLOBALS['db']->getAll("select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".intval($pid));
		if($childItem_arr)
		{
			foreach($childItem_arr as $childItem)
			{
				$this->childIds[] = $childItem[$pk_str];
				$this->_getChildIds($childItem[$pk_str],$pk_str,$pid_str);
			}
		}
	}
	public function getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$this->childIds = array();
		$this->_getChildIds($pid,$pk_str,$pid_str);
		return $this->childIds;
	}
}

/*
 * 获得查询次数以及查询时间存入数据库中，方便用户查看
 */
function sql_check($type=''){
	if(!app_conf("SQL_CHECK"))return "";
	$base_name=basename($_SERVER['SCRIPT_FILENAME']);
	$check=array();
	if($base_name=='m.php'){
		return "";
	}
	$check['file_name']=$base_name;
	if($_REQUEST['from']=='wap'||$type=='wap'){
		$check['file_name']='wap.php';
	}
	
	
	$check['url']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  	$module_name='ctl';
	$action_name='act';
	$module = strtolower(!empty($_REQUEST[$module_name])?$_REQUEST[$module_name]:"index");
	$check['module']=$module;
	$action = strtolower(!empty($_REQUEST[$action_name])?$_REQUEST[$action_name]:"index");
	$check['action']=$action;
	$check['module_action']=$module.'-'.$action;
	$check['module_action_para']=$module.'-'.$action;
 	//
	$request=array_filter($_REQUEST);
	foreach($request as $k=>$v){
		 
 		if($k==$module_name||$k==$action_name){
			unset($request[$k]);
			
		}
	}
  	asort($request);
	$pro=array_keys($request);
	if($pro){
		$check['para']=strtolower(implode("-",$pro));
		$check['module_action_para']=$module.'-'.$action.'-'.$check['para'];
	}
	//
	
 	$query_time = number_format($GLOBALS['db']->queryTime,6);
	$check['query_time']=$query_time;
	if($GLOBALS['begin_run_time']==''||$GLOBALS['begin_run_time']==0)
	{
		$run_time = 0;
	}
	else
	{
		if (PHP_VERSION >= '5.0.0')
		{
			$run_time = number_format(microtime(true) - $GLOBALS['begin_run_time'], 6);
		}
		else
		{
			list($now_usec, $now_sec)     = explode(' ', microtime());
			list($start_usec, $start_sec) = explode(' ', $GLOBALS['begin_run_time']);
			$run_time = number_format(($now_sec - $start_sec) + ($now_usec - $start_usec), 6);
		}
	}
	$check['run_time']=$run_time;
	/* 内存占用情况 */
	if (function_exists('memory_get_usage'))
	{
		$unit=array('B','KB','MB','GB');
		$size = memory_get_usage();
		$used = @round($size/pow(1024,($i=floor(log($size,1024)))),2);
		$memory_usage = $used;
	}
	else
	{
		$memory_usage = '';
	}
	$check['memory_usage']=$memory_usage;
	$enabled_gzip = (app_conf("GZIP_ON") && function_exists('ob_gzhandler'));
	$check['gzip_on']=$enabled_gzip;
	$check['sql_num']=count($GLOBALS['db']->queryLog);
	$sql_array=array();
	$old_num=$GLOBALS['db']->getOne("select sql_num from ".DB_PREFIX."sql_check where module_action_para='".$check['module_action_para']."' order by id desc ");
	
	foreach($GLOBALS['db']->queryLog as $K=>$sql)
	{
		$sql_array[]=$sql;
	}
	$check['sql_str']=serialize($sql_array);
	$check['create_time']=get_gmtime();
   	if($old_num!=$check['sql_num']||$old_num==0){
   		$GLOBALS['db']->autoExecute(DB_PREFIX."sql_check",$check,"INSERT","","SILENT");
 	}
 	
}

/**
 * 获得查询次数以及查询时间
 *
 * @access  public
 * @return  string
 */
function run_info()
{

	if(!SHOW_DEBUG)return "";

	$query_time = number_format($GLOBALS['db']->queryTime,6);

	if($GLOBALS['begin_run_time']==''||$GLOBALS['begin_run_time']==0)
	{
		$run_time = 0;
	}
	else
	{
		if (PHP_VERSION >= '5.0.0')
		{
			$run_time = number_format(microtime(true) - $GLOBALS['begin_run_time'], 6);
		}
		else
		{
			list($now_usec, $now_sec)     = explode(' ', microtime());
			list($start_usec, $start_sec) = explode(' ', $GLOBALS['begin_run_time']);
			$run_time = number_format(($now_sec - $start_sec) + ($now_usec - $start_usec), 6);
		}
	}

	/* 内存占用情况 */
	if (function_exists('memory_get_usage'))
	{
		$unit=array('B','KB','MB','GB');
		$size = memory_get_usage();
		$used = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		$memory_usage = "占用内存 ".$used;
	}
	else
	{
		$memory_usage = '';
	}

	/* 是否启用了 gzip */
	$enabled_gzip = (app_conf("GZIP_ON") && function_exists('ob_gzhandler'));
	$gzip_enabled = $enabled_gzip ? "gzip开启" : "gzip关闭";

	$str = '共执行 '.$GLOBALS['db']->queryCount.' 个查询，用时 '.$query_time.' 秒，'.$gzip_enabled.'，'.$memory_usage.'，程序执行时间 '.$run_time.' 秒';

	foreach($GLOBALS['db']->queryLog as $K=>$sql)
	{
		if($K==0)$str.="<br />SQL语句列表：";
		$str.="<br />行".($K+1).":".$sql;
	}

	return "<div style='width:940px; padding:10px; line-height:22px; border:1px solid #ccc; text-align:left; margin:30px auto; font-size:14px; color:#999; height:150px; overflow-y:auto;'>".$str."</div>";
}

//是否进行身份认证
function is_user_investor_mapi($is_investor,$investor_status){
	if($is_investor==0){
		return 0; //未进行身份认证
	}else{
		if($is_investor >0 && $investor_status==1)
			return 1; //通过审核
		else
			return 2; //审核中
	}
}
//判断用户是否有权限
//0 表示未登陆 1表示正常 2表示等级不够 3表示没有认证手机 4表示没有身份认证 5表示身份认证审核中 6表示身份认证审核失败
function get_level_access($user_info,$deal_info){
	if(!$user_info){
		//0 表示未登陆
		if($deal_info['user_level']>0){
			return 0;
		}else{
			return 1;
		}
		
	}
	if($user_info['id']!=$deal_info['user_id']){
	$user_level_array= load_auto_cache("user_level");
	
 	$user_level=intval($user_level_array[$user_info['user_level']]['point']);
	$deal_level=intval($user_level_array[$deal_info['user_level']]['point']);
	
	if($deal_level!=0&&($deal_level>$user_level)){
		// 2表示等级不够
		return 2;
	}
	if($deal_info['type']==0){
		if(!$user_info['mobile']){
			return 3;
		}
 	}elseif($deal_info['type']==1){
		if($user_info['is_investor']==0){
			return 4;
		}elseif($user_info['investor_status']==0){
			return 5;
		}elseif($user_info['investor_status']==2){
			return 6;
		}
	}
	}
	return 1;
}

//解析URL标签
// $str = u:acate#index|id=10&name=abc

//编译生成css文件
function parse_css($urls)
{
	
	$url = md5(implode(',',$urls));
	$css_url = 'public/runtime/statics/'.$url.'.css';
	$url_path = APP_ROOT_PATH.$css_url;
	if(!file_exists($url_path)||IS_DEBUG)
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
		mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);
		$tmpl_path = $GLOBALS['tmpl']->_var['TMPL'];	
	
		$css_content = '';
		foreach($urls as $url)
		{
			$css_content .= @file_get_contents($url);
		}
		$css_content = preg_replace("/[\r\n]/",'',$css_content);
		$css_content = str_replace("../images/",$tmpl_path."/images/",$css_content);
//		@file_put_contents($url_path, unicode_encode($css_content));
		@file_put_contents($url_path, $css_content);
	}
	return REAL_APP_ROOT."/".$css_url;
}

/**
 * 
 * @param $urls 载入的脚本
 * @param $encode_url 需加密的脚本
 */
function parse_script($urls,$encode_url=array())
{	
	$url = md5(implode(',',$urls));
	$js_url = 'public/runtime/statics/'.$url.'.js';
	$url_path = APP_ROOT_PATH.$js_url;
	if(!file_exists($url_path)||IS_DEBUG)
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
		mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);
	
		if(count($encode_url)>0)
		{
			require_once APP_ROOT_PATH."system/libs/javascriptpacker.php";
		}
		
		$js_content = '';
		foreach($urls as $url)
		{
			$append_content = @file_get_contents($url)."\r\n";
			if(in_array($url,$encode_url))
			{
				$packer = new JavaScriptPacker($append_content);
				$append_content = $packer->pack();
			}			
			$js_content .= $append_content;
		}		
//		require_once APP_ROOT_PATH."system/libs/javascriptpacker.php";
//	    $packer = new JavaScriptPacker($js_content);
//		$js_content = $packer->pack();
		@file_put_contents($url_path,$js_content);
	}
	return REAL_APP_ROOT."/".$js_url;
}

//获取相应规格的图片地址
//gen=0:保持比例缩放，不剪裁,如高为0，则保证宽度按比例缩放  gen=1：保证长宽，剪裁
function get_spec_image($img_path,$width=0,$height=0,$gen=0,$is_preview=true,$is_deleteable=true)
{
	if($width==0)
		$new_path = $img_path;
	else
	{
		$img_name = substr($img_path,0,-4);
		$img_ext = substr($img_path,-3);	
		if($is_deleteable){
			if($is_preview)
			$new_path = $img_name."_".$width."x".$height.".jpg";	
			else
			$new_path = $img_name."o_".$width."x".$height.".jpg";	
		}
		else
		{
			if($is_preview)
			$new_path = $img_name."".$width."".$height.".jpg";	
			else
			$new_path = $img_name."o".$width."".$height.".jpg";
		}
		
		/*
		if(!file_exists(APP_ROOT_PATH.$new_path))
		{ 	
			require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
			$imagec = new es_imagecls();
			$thumb = $imagec->thumb(APP_ROOT_PATH.$img_path,$width,$height,$gen,true,"",$is_preview,$is_deleteable);
			
			if(app_conf("PUBLIC_DOMAIN_ROOT")!='')
        	{
        		$paths = pathinfo($new_path);
        		$path = str_replace("./","",$paths['dirname']);
        		$filename = $paths['basename'];
        		$pathwithoupublic = str_replace("public/","",$path);
	        	$syn_url = app_conf("PUBLIC_DOMAIN_ROOT")."/es_file.php?username=".app_conf("IMAGE_USERNAME")."&password=".app_conf("IMAGE_PASSWORD")."&file=".get_domain().APP_ROOT."/".$path."/".$filename."&path=".$pathwithoupublic."/&name=".$filename."&act=0";
	        	@file_get_contents($syn_url);
        	}
			
		}
		*/
	}
	return $new_path;
}

function get_spec_gif_anmation($url,$width,$height)
{
	require_once APP_ROOT_PATH."system/utils/gif_encoder.php";
	require_once APP_ROOT_PATH."system/utils/gif_reader.php";
	require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
	$gif = new GIFReader();
	$gif->load($url);
	$imagec = new es_imagecls();
	foreach($gif->IMGS['frames'] as $k=>$img)
	{
		$im = imagecreatefromstring($gif->getgif($k));		
		$im = $imagec->make_thumb($im,$img['FrameWidth'],$img['FrameHeight'],"gif",$width,$height,$gen=1);
		ob_start();
		imagegif($im);
		$content = ob_get_contents();
        ob_end_clean();
		$frames [ ] = $content;
   		$framed [ ] = $img['frameDelay'];
	}
		
	$gif_maker = new GIFEncoder (
	       $frames,
	       $framed,
	       0,
	       2,
	       0, 0, 0,
	       "bin"   //bin为二进制   url为地址
	  );
	return $gif_maker->GetAnimation ( );
} 
function load_page_png($img)
{
	return load_auto_cache("page_image",array("img"=>$img));
}

function get_gopreview()
{
		$gopreview = es_session::get("gopreview");
		if($gopreview==get_current_url())
		{
			$gopreview = url("index");
		}
		if(!isset($gopreview)||$gopreview=="")
		{
			$gopreview = es_session::get('before_login')?es_session::get('before_login'):url("index");				
		}	
		return $gopreview;
}


function get_current_url()
{
	$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");   
    $parse = parse_url($url);
    if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            $url   =  $parse['path'].'?'.http_build_query($params);
    }
    if(app_conf("URL_MODEL")==1)
    {	
    	$url = $GLOBALS['current_url'];
    	if(intval($_REQUEST['p'])>0)
    	{
    		$req = $_REQUEST;
    		unset($req['ctl']);
    		unset($req['act']);
    		unset($req['p']);
    		if(count($req)>0)
    		{
    			$url.="-p-".intval($_REQUEST['p']);
    		}
    		else
    		{
    			$url.="/p-".intval($_REQUEST['p']);
    		}
    	}
    }
    return $url;
}

//过滤非法的html标签
function vaild_html($content)
{
	$content = preg_replace("/<(?!div|ol|ul|li|sup|sub|span|br|img|p|h1|h2|h3|h4|h5|h6)[^>]*>/i","",$content);
	return $content;
}

//获取已过时间
function pass_date($time)
{
		$time_span = NOW_TIME - $time;
		if($time_span>3600*24*365)
		{
			$time_span_lang = to_date($time,"Y-m-d");
		}
		elseif($time_span>3600*24*30)
		{
			$time_span_lang = to_date($time,"Y-m-d");
		}
		elseif($time_span>3600*24)
		{
			//一天
			$time_span_lang = round($time_span/(3600*24))."天前";
	
		}
		elseif($time_span>3600)
		{
			//一小时
			$time_span_lang = round($time_span/(3600))."小时前";
		}
	    elseif($time_span>60)
		{
			//一分
			$time_span_lang = round($time_span/(60))."分钟前";
			
		}
		else
		{
			//一秒
			$time_span_lang = "刚刚";
		}
		return $time_span_lang;
}

/**
* 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
*
* @access  public
* @param   string      $sql        SQL查询串
* @return  string      返回已过滤掉注释的SQL查询串。
*/
function remove_comment($sql)
{
	/* 删除SQL行注释，行注释不匹配换行符 */
	$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);

	/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
	//$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
	$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);

	return $sql;
}

function emptyTag($string)
{
		if(empty($string))
			return "";

		$string = strip_tags(trim($string));
		$string = preg_replace("|&.+?;|",'',$string);

		return $string;
}

function get_abs_img_root($content)
{	

	return str_replace("./public/",get_domain().REAL_APP_ROOT."/public/",$content);
	//return str_replace('/mapi/','/',$str);
}
//
function get_abs_img_root_wap($content)
{	
	return str_replace("./public/",get_domain().REAL_APP_ROOT."/public/",$content);
	//return str_replace('/mapi/','/',$str);
}

function get_abs_url_root($content)
{
	$content = str_replace("./",get_domain().REAL_APP_ROOT."/../",$content);	
	return $content;
}
?>