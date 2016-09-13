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
class WeixinUserAdvancedAction extends WeixinAction{
  	public function __construct(){
		parent::__construct();
 		$this->assign("max_size",get_max_file_size());
 		$this->assign("max_size_byte",get_max_file_size_byte());
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
	
	public function advanced(){
		
		$condition = " account_id=".$this->account_id." and send_type = 1";
 		$page_size = 15;
 		$rs_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_send where ".$condition);
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if($rs_count > 0){
			$pager  = buildPage("WeixinUser/advanced",$_REQUEST,$rs_count,$this->page,$page_size);
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
		
 		$this->assign("box_title","高级群发");
		$this->display();
	}
	
	public function advanced_add(){
		
		
		$msgtype = array("news"=>"图文消息");
		$this->assign("msgtype",$msgtype); 
		
		$id = intval($_REQUEST['id']);
		
		if($id > 0){
 			$send = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_send where id=".$id." AND account_id=".$this->account_id);
 			//$send['wechat_group']= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where account_id=".$this->account_id);		
 			
 			$this->assign("box_title","编辑群发信息");
		}
		else{
			$send['send_type'] = 1;
			$send['msgtype'] = "news";
			$this->assign("box_title","添加群发信息");
			
		}
		$time= get_gmtime();
 
   		$send['wechat_group']= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where  account_id=".$this->account_id); 
  		
		$this->assign("send",$send); 
		$this->assign("user_type_id",$send['user_type_id']);
		
		//输出关联的回复
		$relate_replys = $GLOBALS['db']->getAll("select relate_id from ".DB_PREFIX."weixin_send_relate where send_id=".$send['id']);  
 		foreach($relate_replys as $k=>$v){
			
			$relate_replys[$k] = M('WeixinReply') -> where(array('id'=>$v['relate_id']))->find();
		}
		$this->assign("relate_replys",$relate_replys);
 		$this->display("message_send_add");
	 
	}
	
 }
?>