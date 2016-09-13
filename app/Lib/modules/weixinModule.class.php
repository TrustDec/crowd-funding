<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
//require APP_ROOT_PATH.'system/wechat/wechat.class.php';
require APP_ROOT_PATH."system/wechat/CIpLocation.php";
require APP_ROOT_PATH."system/libs/words.php";
class weixinModule extends BaseModule
{
	public $option;
	public $platform;
	public $account;
	public function __construct()
	{
		parent::__construct();
		 
		 //添加微信接口
		$weixin_conf = load_auto_cache("weixin_conf");
		
		if(!$weixin_conf){
			$weixin_conf = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_conf");
		 	foreach($weixin_conf as $k=>$v){
				$weixin_conf[$v['name']]=$v['value'];
			}
		}
		$this->option = array(
 			'platform_token'=>$weixin_conf['platform_token'], //填写你设定的token
 			'platform_encodingAesKey'=>$weixin_conf['platform_encodingAesKey'], //填写加密用的EncodingAESKey
 			'platform_appid'=>$weixin_conf['platform_appid'], //填写高级调用功能的app id
 			'platform_appsecret'=>$weixin_conf['platform_appsecret'], //填写高级调用功能的密钥
 			
 			'platform_component_verify_ticket'=>$weixin_conf['platform_component_verify_ticket'], //第三方通知
 			'platform_component_access_token'=>$weixin_conf['platform_component_access_token'], //第三方平台令牌
 			'platform_pre_auth_code'=>$weixin_conf['platform_pre_auth_code'], //第三方平台预授权码
 			
 			'platform_component_access_token_expire'=>$weixin_conf['platform_component_access_token_expire'], 
 			'platform_pre_auth_code_expire'=>$weixin_conf['platform_pre_auth_code_expire'], 
 			
 			
 			
 			'logcallback'=>'log_result',
 			'debug'=>true,
 		);
		$authorizer_appid = $_REQUEST['amp;amp;appid']?$_REQUEST['amp;amp;appid']:$_REQUEST['appid'];
		if($authorizer_appid){
			$authorizer_appid=trim($authorizer_appid,'/');
			$account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where authorizer_appid='".$authorizer_appid."' ");
 			$this->account = $account ;
 			if($account){
 				$option_account=array(
	 				'authorizer_access_token'=>$this->account['authorizer_access_token'], 
		 			'authorizer_access_token_expire'=>$this->account['expires_in'], 
		 			'authorizer_appid'=>$this->account['authorizer_appid'], 
		 			'authorizer_refresh_token'=>$this->account['authorizer_refresh_token'], 
 				);
 				$this->option=array_merge($this->option,$option_account);
  			}
 		}
		
 		$this->platform = new PlatformWechat($this->option);
	}
	//微信验证
	public function valid(){
		echo 'valid';
	}
	//发起授权页的体验URL
	public function valid_url(){
		 if(!$GLOBALS['user_info']){
			app_redirect(url("user#login"));
		}
 		$platform_pre_auth_code=$this->platform->check_platform_get_pre_auth_code();
		$return_url=get_domain().url("weixin#platform_get_auth_code",array("type"=>0,"user_id"=>$GLOBALS['user_info']['id']));
		$return_url=urlencode($return_url);
		$sq_url='https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->option['platform_appid'].'&pre_auth_code='.$platform_pre_auth_code.'&redirect_uri='.$return_url;
		$GLOBALS['tmpl']->assign("sq_url",$sq_url);
		$GLOBALS['tmpl']->display("weixin_valid_url.html");
	}
	//授权事件接收URL
	public function accept(){
 		$platform= $this->platform;
		//$platform->log($_REQUEST);
		$result=$platform->platform_DecryptMsg();
		//$platform->log($info);
		if($result['status']==1){
			$msg=$result['info'];
  			//$platform->log($result);
 			if($msg['InfoType']=='component_verify_ticket'){
 				if($msg['ComponentVerifyTicket']){
 					 //保存component_verify_ticket
 					 $GLOBALS['db']->query("update ".DB_PREFIX."weixin_conf set value='".$msg['ComponentVerifyTicket']."' where name='platform_component_verify_ticket' ");
 					 rm_auto_cache("weixin_conf");
 					 //load_auto_cache("weixin_conf");
 				}else{
 					$info['msg']='ComponentVerifyTicket 为空';
 					//$platform->log($result);
 				}
 			}
			echo 'success';
		}else{
			 
			$platform->log($result);
		}
 	}
	//公众号消息与事件接收URL
	public function gz_accept(){
		$platform= $this->platform;
		$platform->log("公众号消息与事件接收URL");
		$platform->log($_REQUEST);
		$result=$platform->platform_DecryptMsg();
		$platform->log($result);
		//$platform->log($this->option);
		if($result['status']==1){
			$msg=$result['info'];
			if($msg['ToUserName']=='gh_3c884a361561'){
				//全网发布
				if($msg['MsgType']=='event'){
					$this->platform->text($msg['Event'].'from_callback')->reply();
				}elseif($msg['MsgType']=='text'){
					if($msg['Content']=='TESTCOMPONENT_MSG_TYPE_TEXT'){
						$this->platform->text('TESTCOMPONENT_MSG_TYPE_TEXT_callback')->reply();
					}else{
						$query_auth_code = str_replace('QUERY_AUTH_CODE:','',$msg['Content']);
						
						if($query_auth_code){
							$sendData = array();
							$sendData['msgtype'] =  'text';	
							$sendData['text']['content'] = $query_auth_code.'_from_api';
							$sendData['touser'] = $msg['FromUserName'];
							$platform->test_sendCustomMessage($sendData,$query_auth_code);
						}
					}
				}
			}else{
				
			
				if($msg['MsgType']=='event'){
	 				if($msg['Event']=='CLICK'){
						//点击事件 查询关键字
	 					$condition =" account_id=".$this->account['id']." and i_msg_type='text'   ";
						$keywords = $msg['EventKey'];
						if($keywords){
							$unicode_tag = words::strToUnicode($keywords);
							$condition .= " and MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) ";
						}
	 					$reply=$GLOBALS['db']->getRow("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
	  				    $this->responseReply($reply);
	 				}elseif($msg['Event']=='subscribe'){
						//关注
					   $condition =" account_id=".$this->account['id']."   and type=4 and default_close=0 ";
					   $reply=$GLOBALS['db']->getRow("select *  from ".DB_PREFIX."weixin_reply where ".$condition);
	 				   //$platform->log($reply);
					   $this->responseReply($reply);
					}elseif($msg['Event']=='unsubscribe'){
						//用户取消关注
						
					}
				}elseif($msg['MsgType']=='location'){
					$ypoint = strim($msg['Location_X']);
			        $xpoint = strim($msg['Location_Y']);
			        $pi = 3.14159265;  //圆周率
			        $r = 6378137;  //地球平均半径(米)
			
			        $sql = "select * ,(ACOS(SIN(($ypoint * $pi) / 180 ) *SIN((y_point * $pi) / 180 ) + COS(($ypoint * $pi) / 180 ) * COS((y_point * $pi) / 180 ) * COS(($xpoint * $pi) / 180 - (x_point * $pi) / 180 ) ) * $r) as distance
			        from ".DB_PREFIX."weixin_reply where scale_meter - ((ACOS(SIN(($ypoint * $pi) / 180 ) * SIN((y_point * $pi) / 180 ) + COS(($ypoint * $pi) / 180 ) * COS((y_point * $pi) / 180 ) * COS(($xpoint * $pi) / 180 - (x_point * $pi) / 180 ) ) * $r)) > 0 and account_id = ".$this->account['id']." and i_msg_type='location' order by distance asc";
			        $reply=$GLOBALS['db']->getRow($sql);
	   				$this->responseReply($reply);
	   				
				}elseif($msg['MsgType']=='text'){
					//点击事件 查询关键字
	 					$condition =" account_id=".$this->account['id']." and i_msg_type='text'   ";
						$keywords = $msg['Content'];
						if($keywords){
							$unicode_tag = words::strToUnicode($keywords);
							$condition .= " and MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) ";
						}
	 					$reply=$GLOBALS['db']->getRow("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
	  				    $this->responseReply($reply);
				}
			}
		} 
	}
	public function responseReply($reply){
		  if(!$reply){
 			$condition =" account_id=".$this->account['id']." and type=1 and default_close=0   ";
		  	$reply=$GLOBALS['db']->getRow("select *  from ".DB_PREFIX."weixin_reply where ".$condition);
		  }
		  if($reply['o_msg_type']=='text'){
		   	   $content = htmlspecialchars_decode(stripslashes($reply['reply_content']));
			   $content = str_replace(array('<br/>','<br />','&nbsp;'), array("\n","\n",' '), $content);
		       $this->platform->text($content)->reply();
 		   }elseif($reply['o_msg_type']=='news'){
		   	$new=array();
			if($reply['key_or_url'] == ''){
				//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
				if($reply['u_module']=="")$reply['u_module']="index";
				if($reply['u_action']=="")$reply['u_action']="index";
				$route = $reply['u_module'];
				if($reply['u_action']!='')$route.="#".$reply['u_action'];								
				$str = "u:".$route."|".$reply['u_param'];					
				$reply['key_or_url']  =  get_domain().parse_url_tag_coomon($str);
 			}
			$new[]=array('Title'=>$reply['reply_news_title'],'Description'=>$reply['reply_news_description'],'PicUrl'=> get_domain().$reply['reply_news_picurl'],'Url'=>$reply['key_or_url'],);
			$article_count = 1;

			$sql = "select r.* from ".DB_PREFIX."weixin_reply as r
                left join ".DB_PREFIX."weixin_reply_relate as rr on r.id = rr.relate_reply_id
                where rr.main_reply_id = ".$reply['id'];
			
			$relate_replys=$GLOBALS['db']->getAll($sql); 
            
			$article_count = $article_count + intval(count($relate_replys));
			
  			foreach($relate_replys as $k=>$item){
				 if($item){
				 	if($item['key_or_url'] == ''){
						//由关联数据端重新获取回复的内容（reply_news_title,reply_news_description,reply_news_picurl）
						if($item['u_module']=="")$item['u_module']="index";
						if($item['u_action']=="")$item['u_action']="index";
						$route = $item['u_module'];
						if($item['u_action']!='')$route.="#".$item['u_action'];								
						$str = "u:".$route."|".$item['u_param'];					
						$item['key_or_url']  =  get_domain().parse_url_tag_coomon($str);
		 			}
					$new[]=array('Title'=>$item['reply_news_title'],'Description'=>$item['reply_news_description'],'PicUrl'=> get_domain().$item['reply_news_picurl'],'Url'=>$item['key_or_url'],);
				 }
			}
  			$this->platform->news($new)->reply();
		 } 
	}
	//接受验证码并展示
	public function platform_get_auth_code(){
		$platform= new PlatformWechat($this->option);
		$auth_code= $_REQUEST['auth_code'];
		$type = intval($_REQUEST['type']);
		$user_id = intval($_REQUEST['user_id']);
		$re=$platform->platform_api_query_auth($auth_code,$type,$user_id);
		if($re){
			$info=$platform->platform_get_authrizer_info();
			if($info){
 				showSuccess("授权成功");
			}else{
				showSuccess("授权成功,获取信息失败");
			}
			
		}else{
 			showErr("授权失败，请重新授权");
		}
	}
}
?>