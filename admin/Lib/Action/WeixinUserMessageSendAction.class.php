<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH."system/wechat/CIpLocation.php";
require APP_ROOT_PATH."system/libs/words.php";
class WeixinUserMessageSendAction extends WeixinAction{
  	public function __construct(){
		parent::__construct();
 		$this->assign("max_size",get_max_file_size());
 		$this->assign("max_size_byte",get_max_file_size_byte());
  	}
  	 /*
     * 群发列表
     */
    public function message_send(){
    	 
		$condition = " account_id=".$this->account_id." and send_type = 0";
		$page_size = 15;
		$rs_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_send where ".$condition);
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if($rs_count > 0){
			$pager  = buildPage("WeixinUser/message_send",$_REQUEST,$rs_count,$this->page,$page_size);
			$this->assign("pager",$pager);
			$list =  $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_send where ".$condition."  order by id DESC limit ".$pager['limit']);
 			foreach($list as $k=>$v){
				$list[$k]['create_time_format'] = to_date($v['create_time']); 
				if($v['send_time']!=""){
					$list[$k]['send_time_format'] = to_date($v['send_time']); 
				}
			}
			$this->assign("list",$list);
			
		}
		
		$this->assign("box_title","普通群发");
		$this->display();
    }
    
    public function message_send_del(){
    	$ids_str = strim($_REQUEST['ids']);
		$id = intval($_REQUEST['id']);
		if($ids_str != ""){
			//批量删除
			$replys = M('WeixinSend')->where(array('id'=>array('in',explode(',',$ids_str)),'account_id'=>$this->account_id))->findAll();
			foreach($replys as $reply){
				M('WeixinSend')->where(array('id'=>$reply['id'],'account_id'=>$this->account_id))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}elseif($id > 0){
			//单条删除
			$reply = M('WeixinSend')->where(array('id'=>$id,'account_id'=>$this->account_id))->find();
			if($reply){
				M('WeixinSend')->where(array('id'=>$id,'account_id'=>$this->account_id))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}else{
			$this->error("请选择要删除的选项",$this->isajax);
		}
    }
    
    /**
	 * 普通推送
	 */
	public function message_send_add(){
		$msgtype = array(
			"text"=>"文本消息",
			"image"=>"图片消息",
			"voice"=>"语音消息",
			"video"=>"视频消息",
			"music"=>"音乐消息",
			"news"=>"图文消息"
		);
		$this->assign("msgtype",$msgtype); 
		
		$id = intval($_REQUEST['id']);
		
		if($id > 0){
 			$send = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_send where id=".$id." AND account_id=".$this->account_id);
 			//$send['wechat_group']= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where account_id=".$this->account_id);		
 			
 			$this->assign("box_title","编辑群发信息");
		}
		else{
			$send['send_type'] = 0;
			$this->assign("box_title","添加群发信息");
			
		}
		$time= get_gmtime();
 		$send['user_list'] = $GLOBALS['db']->getAll("SELECT wgl.* from ".DB_PREFIX."weixin_api_get_record ar LEFT JOIN ".DB_PREFIX."weixin_user wgl ON wgl.openid = ar.openid where ar.account_id=".$this->account_id ." and ar.create_time < ".$time." AND ar.create_time > ".($time-48*3600+1)."");
  		
		$this->assign("send",$send); 
		$this->assign("user_type_id",$send['user_type_id']);
		
		//输出关联的回复
		$relate_replys = $GLOBALS['db']->getAll("select relate_id from ".DB_PREFIX."weixin_send_relate where send_id=".$send['id']);  
 		foreach($relate_replys as $k=>$v){
			
			$relate_replys[$k] = M('WeixinReply') -> where(array('id'=>$v['relate_id']))->find();
		}
		$this->assign("relate_replys",$relate_replys);
 		$this->display();
	}
	/**
	 * 保存群发
	 */
	public function message_send_save(){
		$id = intval($_REQUEST['id']);
		$data['msgtype'] = $_POST['msgtype'];
		if(!in_array($data['msgtype'],array("text","image","voice","video","music","news"))){
			$this->showFrmErr("不支持的类型",$this->isajax,"msgtype");
		}
		$data['title'] = $_POST['title'];
		$data['user_type'] = intval($_POST['user_type']);
		$data['user_type_id'] = intval($_POST['user_type_id']);
		$data['author'] = $GLOBALS['seller_info']['public_name'];
		$data['media_file'] = ($_POST['media_file']);
		$data['content'] = ($_POST['content']);
		$data['digest'] = ($_POST['digest']);
		$data['send_type'] = intval($_POST['send_type']);
		
		if($data['title'] ==""){
			$this->showFrmErr("标题不能为空",$this->isajax,"title");
		}
		
		switch ($data['msgtype']){
			case "news":
				if($data['content']==""){
					$this->showFrmErr("内容不能为空",$this->isajax,"content");
				}
				break;
		}
		switch ($data['msgtype']){
			case "image":
			case "voice":
			case "video":
			case "music":
			case "news":
				if($data['media_file']==""){
					$this->showFrmErr("媒体文件不能为空",$this->isajax,"media_file");
				}
				$file_name = pathinfo($data['media_file']);
				$file_ext = strtolower($file_name['extension']);
				switch($data['msgtype']){
					case "image":
					case "news":
						if(!in_array($file_ext,array("jpg","jpeg"))){
							$this->showFrmErr("媒体文件有误,必须为:jpg",$this->isajax,"media_file");
						}
						$fsize =  filesize(APP_ROOT_PATH.$data['media_file'])/1024;
						if($fsize > 124){
							$this->showFrmErr("媒体文件太大，只允许124KB",$this->isajax,"media_file");
						}
						break;
					case "voice":
						if(!in_array($file_ext,array("amr","mp3"))){
							$this->showFrmErr("媒体文件有误,必须为:amr/mp3",$this->isajax,"media_file");
						}
						$fsize =  filesize(APP_ROOT_PATH.$data['media_file'])/1024;
						if($fsize > 256){
							$this->showFrmErr("媒体文件太大，只允许256KB",$this->isajax,"media_file");
						}
						break;
					case "music":
						if(!in_array($file_ext,array("mp3"))){
							$this->showFrmErr("媒体文件有误,必须为:mp3",$this->isajax,"media_file");
						}
						
						break;
					case "video":
						if(!in_array($file_ext,array("mp4"))){
							$this->showFrmErr("媒体文件有误,必须为:mp4",$this->isajax,"media_file");
						}
						$fsize =  filesize(APP_ROOT_PATH.$data['media_file'])/1024;
						if($fsize > 1024){
							$this->showFrmErr("媒体文件太大，只允许1M",$this->isajax,"media_file");
						}
						break;
				}
				break;
		}
		
		if($data['msgtype']=="news"){
			//定义链接
			$relate_type  =1; //默认为0
			$data['url'] = trim($_REQUEST['url']);
			$data['account_id'] = $this->account_id;
			if($_REQUEST['u_module']!='')
			{
				$data['url'] = '';
			}
			if($data['url']!='')
			{
				$data['u_module'] = '';
				$data['u_action'] = '';
				$data['u_id'] = '';
				$data['u_param'] = '';
			}else{
				$data['u_id'] = intval($_REQUEST['u_id']);
				$data['u_module'] = trim($_REQUEST['u_module']);
				$data['u_action'] = trim($_REQUEST['u_action']);
				$data['u_param'] = trim($_REQUEST['u_param']);
			}
		}
  		$data['account_id'] = $this->account_id;
		 
		
		if($id >0){
			//录入到数据库
 			$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",$data,'UPDATE','id='.$id);
			$total = 0;
			//删除旧的关联 
			$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."weixin_send_relate where send_id=".$id);
 			if($_POST['relate_reply_id']){
				foreach ($_POST['relate_reply_id'] as $k=>$vv){
					if(intval($vv) > 0 && $total < 9){
						$total++;
						$link_data = array();
						$link_data['send_id'] = $id;
						$link_data['relate_id'] = $vv;
						$link_data['sort'] = $k;
						$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send_relate",$link_data);
 					}
				}
			}
		}
		else{
			$data['create_time'] = get_gmtime();
			$rs = $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",$data);
			if($rs>0){
				$total = 0;
				if($_POST['relate_reply_id']){
					foreach ($_POST['relate_reply_id'] as $k=>$vv){
						if(intval($vv) > 0 && $total < 9){
							$total++;
							$link_data = array();
							$link_data['send_id'] = $rs;
							$link_data['relate_id'] = $vv;
							$link_data['sort'] = $k;
							$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send_relate",$link_data);
						}
					}
				}
			}
			else{
				$this->showFrmErr("保存失败,请检查",$this->isajax);
			}
		}
		
		if($id > 0){
			$this->showFrmSuccess("保存成功",$this->isajax);
		}
		else{
			if($data['send_type'] == 1)
				$this->success("保存成功",$this->isajax);
			else
				$this->success("保存成功",$this->isajax);
		}
		
		
	}
	/**
	 * 推送消息
	 */
	public function to_send_message(){
 		$id = intval($_REQUEST['id']);
		if($id==0){
			$this->error("数据错误",$this->isajax);
		}
		
		//获取要发送的内容
		$send = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_send where id=".$id." AND account_id=".$this->account_id) ;
		if(!$send){
			$this->error("数据错误",$this->isajax);
		}
		$platform = $platform= new PlatformWechat($this->option);
	    $platform_authorizer_token=$platform->check_platform_authorizer_token();
		//判断是否是高级群发
		//var_dump($send);exit;
		if(intval($send['send_type']) == 0){
			$sendData = array();
			$sendData['msgtype'] =  $send['msgtype'];
			//普通群发
			$data['media_file'] = '@'.APP_ROOT_PATH.$send['media_file'];
			//$data['media_file'] = '@public/attachment/201507/11/10/55a079c5ce6ff.jpg';
			
 			switch($send['msgtype']){
				case "text"://文本直接提交
					$sendData['text']['content'] = $send['content'];
					break;
				case "image"://图片消息
//					$media_info = self::uploadmedia($send,$access_token);
 					$media_info=$platform->uploadMedia($data,'image');
 					$sendData['image']['media_id'] = $media_info['media_id'];
					//上传多媒体消息
					break;
				case "voice":
					$media_info=$platform->uploadMedia($data,'voice');
					$sendData['voice']['media_id'] = $media_info['media_id'];
					break;
				case "video":
//					$media_info = self::uploadmedia($send,$access_token);
					$media_info=$platform->uploadMedia($data,'video');
					$sendData['video']['media_id'] = $media_info['media_id'];
					
					$sendData['video']['title'] = $send['title'];
					$sendData['video']['description'] = $send['content'];
					break;
				case "music":
					$sendData['music']['title'] = $send['title'];
					$sendData['music']['musicurl'] = get_domain().$send['media_file'];
					$sendData['music']['hqmusicurl'] = get_domain().$send['media_file'];
					$sendData['music']['description'] = $send['content'];
 					$data['media_file'] = '@'.APP_ROOT_PATH."./public/weixin/static/images/wap/demo/box.jpg";;
  					$media_info=$platform->uploadMedia($data,'image');
  					
					$sendData['music']['thumb_media_id'] = $media_info['media_id'];
					break;
				case "news":
					$item['title']=  $send['title']."";
					$item['description']=  $send['content']."";
 					$item["picurl"] = get_domain().$send['media_file'];
 					if($send['url'] == ''){
						//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
						if($send['u_module']=="")$send['u_module']="index";
						if($send['u_action']=="")$send['u_action']="index";
						$route = $send['u_module'];
						if($send['u_action']!='')$route.="#".$send['u_action'];								
						$str = "u:".$route."|".$send['u_param'];					
						$send['url']  =  get_domain().parse_url_tag_coomon($str);
					}
					$item['url'] = $send['url'];
 					$sendData['news']['articles'][] = $item;
					
					//获取关联图文数据
 					$relate_data = $GLOBALS['db']->getAll("select s.* from ".DB_PREFIX."weixin_reply s LEFT JOIN ".DB_PREFIX."weixin_send_relate sr on sr.relate_id=s.id WHERE sr.send_id=".$send['id']);
					foreach($relate_data as $kk=>$vv){
						$item = array();
						$item['title'] = $vv['reply_news_title']."";
						$item['description'] = $vv['reply_news_description']."";
						$item['url'] = $vv['reply_news_url'];
						if($item['url'] == ''){
							//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
							if($item['u_module']=="")$item['u_module']="index";
							if($item['u_action']=="")$item['u_action']="index";
							$route = $item['u_module'];
							if($item['u_action']!='')$route.="#".$item['u_action'];								
							$str = "u:".$route."|".$item['u_param'];					
							$item['url']  =  get_domain().parse_url_tag_coomon($str);
						}
						 	
 						$item["picurl"] = get_domain().$vv['reply_news_picurl'];
						
						$sendData['news']['articles'][] = $item;
					}
					 
 					break;
				}
			 
  			//判断是否是全部发送
			if(intval($send['user_type_id'])>0){
				//推送OPENID
 				$touser_info = $GLOBALS['db']->getRow("SELECT openid,nickname from ".DB_PREFIX."weixin_user where id=".intval($send['user_type_id'])." and account_id=".$this->account_id);
				if(!$touser_info){
					$this->success("推送失败,推送的粉丝不存在,请确认是否已经同步粉丝",$this->isajax);
				}
				$sendData['touser'] = $touser_info['openid'];
				
				$res = $platform->sendCustomMessage($sendData);
				if($res){
 					$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",array("send_time"=>NOW_TIME,"status"=>1),'UPDATE','account_id='.$this->account_id.' and id='.$send['id']);
					$this->success("推送完成",$this->isajax);
				}
				else{
					$this->error($touser_info['nickname']." 推送失败",$this->isajax);
				}
			}
			else{
 				$send_user_list = $GLOBALS['db']->getAll("SELECT wgl.openid,wgl.nickname from ".DB_PREFIX."weixin_api_get_record ar LEFT JOIN ".DB_PREFIX."weixin_user wgl ON wgl.openid = ar.openid where ar.account_id=".$this->account_id." AND ar.create_time < ".get_gmtime()." AND ar.create_time >".(get_gmtime()-48*3600+1)."");
				$err_msg = "";
				
				foreach($send_user_list as $k=>$v){
					if($v['openid']!=""){
						$sendData['touser'] = $v['openid'];
						$res = $platform->sendCustomMessage($sendData);
						if($res){
							 
						}
						else{
							$err_msg .=$v['nickname']." 推送失败<br>";
						}
					}
				}
  			    $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",array("send_time"=>NOW_TIME,"status"=>1),'UPDATE','account_id='.$this->account_id.' and id='.$send['id']);
				if($err_msg=="")
					$this->success("推送完成",$this->isajax);
				else
					$this->success("推送完成<span style='color:red'>".$err_msg."</span>",$this->isajax,'',u("WeixinUser/message_send"));
			}
		}
		else{//高级群发
			if($send['msgtype']!="news"){
				$this->error("数据错误",$this->isajax);
			}

			$json_data['articles'] = self::formatAdvSendMsg($send);
 			$result = $platform->uploadArticles($json_data);
			if($result){
				$rs_data['media_id'] = $result['media_id'];
 				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",$rs_data,'UPDATE',' account_id='.$this->account_id.' and id='.$send['id']);
				//开始推送
  				$wechat_group= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where account_id=".$this->account_id);
				//如果是群发给所有用户的话
				$send_news_data['filter']['group_id'] = $send['user_type_id']; 
				$send_news_data['mpnews']['media_id'] = $result['media_id'];
				$send_news_data['msgtype'] = "mpnews";
				
 				$sresult = $platform->sendGroupMassMessage($send_news_data);
				if($sresult){
 					$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_send",array("send_time"=>NOW_TIME,"status"=>1),'UPDATE',' account_id='.$this->account_id.' and id='.$send['id']);
 					$this->success("推送完成",$this->isajax);
				}
				else{
					$this->error("推送失败",$this->isajax);
				}
 			}
			else{
				$this->error("推送失败",$this->isajax);
			}
		}
		
	}
	
	/**
	 * 格式化模板
	 */
	private function formatAdvSendMsg($send){
		$sendData = array();
		switch($send['msgtype']){
			case "news":
 				$sendData[] = self::newsAdvItem($send);
				//获取关联图文数据
				$relate_data = $GLOBALS['db']->getAll("select s.* from ".DB_PREFIX."weixin_reply s LEFT JOIN ".DB_PREFIX."weixin_send_relate sr on sr.relate_id=s.id WHERE sr.send_id=".$send['id']);
				foreach($relate_data as $kk=>$vv){
					$item = array();
					$item['title'] = $vv['reply_news_title']."";
 					$item['digest'] = $vv['reply_news_description'];
					$item['content'] = $vv['reply_news_content'];
					$item['url'] = $vv['reply_news_url'];
					if($item['url'] == ''){
						//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
						if($item['u_module']=="")$item['u_module']="index";
						if($item['u_action']=="")$item['u_action']="index";
						$route = $item['u_module'];
						if($item['u_action']!='')$route.="#".$item['u_action'];								
						$str = "u:".$route."|".$item['u_param'];					
						$item['url']  =  get_domain().parse_url_tag_coomon($str);
					}
					 	
					$item["media_file"] = $vv['reply_news_picurl'];
					$sendData[] = self::newsAdvItem($item);
 				}
 				break;
		}
		
		return $sendData;
	}
	
	/**
	 * 获取高级群发节点
	 */
	private function newsAdvItem($send){
		$data['media_file'] = '@'.APP_ROOT_PATH.$send['media_file'];
		$platform = $platform= new PlatformWechat($this->option);
	   	$platform_authorizer_token=$platform->check_platform_authorizer_token();
		$mediainfo=$platform->uploadMedia($data,'image');
 		$item['thumb_media_id'] = $mediainfo['media_id'];
		$item['author'] = $send['author'];
		$item['title'] = $send['title'];
		
		if($send['url']){
			$data['content_source_url'] = $send['url'];
		}
		else{
			//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
			if($send['u_module']=="")$send['u_module']="index";
			if($send['u_action']=="")$send['u_action']="index";
			$route = $send['u_module'];
			if($send['u_action']!='')$route.="#".$send['u_action'];								
			$str = "u:".$route."|".$send['u_param'];					
			$data['content_source_url']  =  get_domain().parse_url_tag_coomon($str);
			//$data['content_source_url'] =  SITE_DOMIAN."/".url("wei:".$send['relate_data'],array("id"=>$send['relate_id']));
		}
		$item['content'] = $send['content'];
		$item['digest'] = $send['digest'];
		
		return $item;
	}
		
 }
?>