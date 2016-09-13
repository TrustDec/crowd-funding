<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class FileAction extends CommonAction{
	public function do_upload()
	{
		 
 		if(intval($_REQUEST['upload_type'])==0)
		$result = $this->uploadFile();
		else
		$result = $this->uploadImage();
		if($result['status'] == 1)
		{
			$list = $result['data'];
			if(intval($_REQUEST['upload_type'])==0)
			$file_url = ".".$list[0]['recpath'].$list[0]['savename'];
			else
			$file_url = ".".$list[0]['bigrecpath'].$list[0]['savename'];
			/*$html = '<html>';
			$html.= '<head>';
			$html.= '<title>Insert Image</title>';
			$html.= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
			$html.= '</head>';
			$html.= '<body>';
			$html.= '<script type="text/javascript">';
			$html.= 'parent.parent.KE.plugin["image"].insert("' . $_POST['id'] . '", "' . $file_url . '","' . $_POST['imgTitle'] . '","' . $_POST['imgWidth'] . '","' . $_POST['imgHeight'] . '","' . $_POST['imgBorder'] . '","' . $_POST['align'] . '");';
			$html.= '</script>';
			$html.= '</body>';
			$html.= '</html>';
			echo $html;*/
 			$public_url=$file_url;
 			if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
    		{
    			if($_REQUEST['dir']!='file'){
    				syn_to_remote_image_server($file_url);
    			}
     		}
    		$file_url=str_replace("./public/",SITE_DOMAIN.APP_ROOT."/public/",$file_url);
 			ajax_return(array('error' => 0, 'url' => $file_url,'public_url'=>$public_url));
		}
		else
		{
			//echo "<script>alert('".$result['info']."');</script>";
			ajax_return(array('error' => 1, 'message' => $result['info']));
		}
	}
	public function do_upload_img()
	{
		if(intval($_REQUEST['upload_type'])==0)
		$result = $this->uploadFile();
		else
		$result = $this->uploadImage();
		if($result['status'] == 1)
		{
			$list = $result['data'];
			if(intval($_REQUEST['upload_type'])==0)
			$file_url = ".".$list[0]['recpath'].$list[0]['savename'];
			else
			$file_url = ".".$list[0]['bigrecpath'].$list[0]['savename'];
			/*$html = '<html>';
			$html.= '<head>';
			$html.= '<title>Insert Image</title>';
			$html.= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
			$html.= '</head>';
			$html.= '<body>';
			$html.= '<script type="text/javascript">';
			//$html.='alert("'.$_POST['id'].'");';
			//$html.='alert(parent.parent.document.getElementById("'.$_POST['id'].'").value);';
			//$html.='parent.parent.document.getElementById("'.$_POST['id'].'").value="'.$file_url.'";';
			$html.= 'parent.parent.KE.plugin["upload_image"].insert("' . $_POST['id'] . '", "' . $file_url . '","' . $_POST['imgTitle'] . '","' . $_POST['imgWidth'] . '","' . $_POST['imgHeight'] . '","' . $_POST['imgBorder'] . '","' . $_POST['align'] . '");';
			$html.= '</script>';
			$html.= '</body>';
			$html.= '</html>';
			echo $html;*/
			ajax_return(array('error' => 0, 'url' => str_replace("./public/",SITE_DOMAIN.APP_ROOT."/public/",$file_url)));
		}
		else
		{
			//echo "<script>alert('".$result['info']."');</script>";
			ajax_return(array('error' => 1, 'message' => $result['info']));
		}
	}

	
	public function deleteImg()
	{
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$file = $_REQUEST['file'];
		$file = explode("..",$file);
		$file = $file[4];
		$file = substr($file,1);
		@unlink(get_real_path().$file);	
	    if(app_conf("PUBLIC_DOMAIN_ROOT")!='')
        {
	      	$syn_url = app_conf("PUBLIC_DOMAIN_ROOT")."/es_file.php?username=".app_conf("IMAGE_USERNAME")."&password=".app_conf("IMAGE_PASSWORD")."&path=".$file."&act=1";
	      	@file_get_contents($syn_url);
      	}	
		save_log(l("DELETE_SUCCESS"),1);
		$this->success(l("DELETE_SUCCESS"),$ajax);
	}
}
?>