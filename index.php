<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require './system/system_init.php';
require './app/Lib/App.class.php';

if($_REQUEST['is_pc']==1)
	es_cookie::set("is_pc","1",24*3600*30);
 
if (isMobile() && !isset($_REQUEST['is_pc']) && es_cookie::get("is_pc")!=1&&is_dir(APP_ROOT_PATH."wap")&$_REQUEST['ctl']!='collocation'&&$_REQUEST['ctl']!='collocation'){
	$ctl=$_REQUEST['ctl']?$_REQUEST['ctl']:'index';
	$act=$_REQUEST['act']?$_REQUEST['act']:'index';
	$id=$_REQUEST['id'];
	$cid=$_REQUEST['cid'];
	$ref=$_REQUEST['ref'];
	if($id){
		if($cid !=''){
			app_redirect(url_wap($ctl."#".$act,array('id'=>$id,'cid'=>$cid)));
		}else{
			app_redirect(url_wap($ctl."#".$act,array('id'=>$id)));
		}
	}elseif($ref !=''){
		if($cid !=''){
			app_redirect(url_wap($ctl."#".$act,array('ref'=>$ref,'cid'=>$cid)));
		}else{
			app_redirect(url_wap($ctl."#".$act,array('ref'=>$ref)));
		}
	}elseif($cid !=''){
			app_redirect(url_wap($ctl."#".$act,array('ref'=>$ref,'cid'=>$cid)));
		}
	else{
		app_redirect(url_wap($ctl."#".$act));
	}
	
}else{
 	set_source_url();
 	//实例化一个网站应用实例
	$AppWeb = new App();
}
//实例化一个网站应用实例
 
?>