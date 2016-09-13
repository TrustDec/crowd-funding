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
class WeixinUserAction extends WeixinAction{
  	public function __construct(){
		parent::__construct();
 		$this->assign("max_size",get_max_file_size());
 		$this->assign("max_size_byte",get_max_file_size_byte());
  	}
    
	public function index()
	{
    	 //分页设置
        $page_size = 20; //分页量
        $page = $this->page; //当前页码
	    $page_args = array();
        
    	$showStatistics = 1;//是否显示图表
        if (isset($_GET['p']) || isset($_POST['keyword'])) {
            $showStatistics = 0;
        }
        $this->assign('showStatistics', $showStatistics);
        if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id); 
        $where = "account_id=".$this->account_id;
        if (strlen(trim($_POST['keyword']))) {
            $keyword = htmlspecialchars(trim($_POST['keyword']));
            $where  .= " and nickname like '%". $keyword."%' ";
            $list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_user where ".$where);  
        } else {
            if (isset($_GET['groupid'])) {
                $where  .= " and groupid=".intval($_GET['groupid']);
                $page_args['groupid'] = intval($_GET['groupid']);
                $this->assign('groupid', intval($_GET['groupid']));
            }
         	$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_user where ".$where);
	        //分页
	        $pager = buildPage('WeixinUser/index',$page_args,$count,$page,$page_size);
  	        
  	        $this->assign('pager',$pager);
  	        
        	$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_user where ".$where." order by id DESC limit ".$pager['limit']);  
        }
        $platform = $platform= new PlatformWechat($this->option);
	    $platform_authorizer_token=$platform->check_platform_authorizer_token();
	    if($platform_authorizer_token){
	    	$json=$platform->getGroup();
	    	 $wechat_groups = $json['groups'];
       		 $wechat_groups_ids = array();
	    	 if ($wechat_groups) {
	            foreach ($wechat_groups as $g) {
	            	$condition= ' account_id='.$this->account_id.' and groupid='.$g['id'];
	            	$thisGroupInDb = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_group where ".$condition);
 	                $arr = array('account_id' => $this->account_id, 'groupid' => $g['id'], 'name' => $g['name'], 'fanscount' => $g['count']);
	                if (!$thisGroupInDb) {
	                	$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$arr);
	                    
	                } else {
 	                	$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$arr,'UPDATE',"id=".$thisGroupInDb['id']);
	                }
	                array_push($wechat_groups_ids, $g['id']);
	            }
	        }
	    }else{
	    	//$this->error("通讯出错，请重试",$this->isajax);
	    }
        
        $groups=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where account_id=".$this->account_id." order by id ASC");
        
        $this->assign('groups', $groups);
        $groupsByWechatGroupID = array();
        if ($groups) {
            foreach ($groups as $g) {
                $groupsByWechatGroupID[$g['groupid']] = $g;
            }
        }
        if ($list) {
            $i = 0;
            foreach ($list as $item) {
                $list[$i]['smallheadimgurl'] = $item['headimgurl'];
                $list[$i]['groupName'] = $groupsByWechatGroupID[$item['groupid']]['name'];
                $list[$i]['subscribe_time'] = 	to_date($item['subscribe_time']);
                $i++;
            }
        }
        $this->assign('list', $list);
        $this->assign('box_title','会员管理');
        $this->display();
    }
	 //获取最新粉丝
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD']=='GET') {
        	
           $platform = $platform= new PlatformWechat($this->option);
	   	   $platform_authorizer_token=$platform->check_platform_authorizer_token();
            
            if (isset($_GET['next_openid'])) {
               $json_token = $platform->getUserList($_GET['next_openid']);
            }else{
            	 $json_token = $platform->getUserList();
            }
            if($json_token){
            	$arrayData = $json_token['data']['openid'];
	            $nextOpenID = $json_token['next_openid'];
	            $a = 0;
	            $b = 0;
	            foreach ($arrayData as $data) {
	            	$check = $GLOBALS['db']->getOne("select openid from ".DB_PREFIX."weixin_user where openid = '".$data."' and account_id = ".$this->account_id);
  	                if (!$check) {
 	                	$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_user",array('openid' => $data, 'account_id' => $this->account_id));
 	                    $a++;
	                } else {
	                    $b++;
	                }
	            }
	            if (strlen($nextOpenID)) {
  	                $this->showFrmSuccess((('本次更新' . $a) . '条,重复') . ($b = $b == 1 ? 0 : $b . '条，正在获取下一批粉丝数据'),1,$nextOpenID);
	           		
	            } else {
	                $this->success('更新完成,现在获取粉丝详细信息',0);
	            }
            }else{
            	$this->error("获取失败",$this->isajax);
            }
            
        } else {
            $this->showFrmErr('非法操作');
        }
    }
    //刷新所有粉丝详细信息
    public function send_info()
    {
        if ($_SERVER['REQUEST_METHOD']=='GET') {
            $refreshAll = isset($_GET['all']) ? 1 : 0;
            $platform = $platform= new PlatformWechat($this->option);
	   	    $platform_authorizer_token=$platform->check_platform_authorizer_token();
            if ($refreshAll) {
                $fansCount = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_user where   account_id = ".$this->account_id);
                $i = intval($_GET['i']);
                $step = 20;//每次更新20个
                 $fans = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_user where   account_id = ".$this->account_id." order by id desc limit $i,$step");
                if ($fans) {
                    foreach ($fans as $data_all) {
                         $classData= $platform->getUserInfo($data_all['openid']);
                         if($classData){
                         	$data['subscribe'] = $classData['subscribe'];
                         	$data['openid'] = $classData['openid'];
                         	$data['nickname'] = str_replace('\'', '', $classData['nickname']);
		                    $data['sex'] = $classData['sex'];
		                    $data['city'] = $classData['city'];
		                    $data['province'] = $classData['province'];
		                    $data['country'] = $classData['country'];
		                    $data['province'] = $classData['province'];
		                    $data['language'] = $classData['language'];
		                    $data['headimgurl'] = $classData['headimgurl'];
		                    $data['subscribe_time'] = $classData['subscribe_time'];
		                    $data['unionid'] = $classData['unionid'];
		                    $data['remark'] = $classData['remark'];
		                    $data['groupid'] = $classData['groupid'];
		                    $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_user",$data,'UPDATE'," openid='".$classData['openid']."'");
                         }
                          
                    }
                    $i = $step + $i;
                     $this->showFrmSuccess((('更新中请勿关闭...进度：' . $i) . '/') . $fansCount,1 ,$i);
                } else {
                	$this->success('更新完毕',1);
                    die;
                }
            }
        } else {
            $this->error('非法操作');
        }
    }
    //批量粉丝转移
    public function setgroup()
    {
        if ($_SERVER['REQUEST_METHOD']=='GET') {
        	$ids = strim($_REQUEST['ids']);
            $wechatgroupid = explode(",",$ids);
            $to_groupid = intval($_REQUEST['to_groupid']);
            $platform = $platform= new PlatformWechat($this->option);
	   	    $platform_authorizer_token=$platform->check_platform_authorizer_token();
            unset($wechatgroupid[0]);
            $openid_list = array();
            foreach($wechatgroupid as $wk => $wv){
            	$id = intval($wv);
            	$openid=$GLOBALS['db']->getOne("select openid from ".DB_PREFIX."weixin_user where id=".$id);
             	if($openid){
             		$openid_list[] = $openid ;
             	}
//             	$thisFans = $this->sdb->table("wechat_group_list")->where("id=".$id)->getRow();
//            	$url = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $access_token;
//                $post = self::system_curl($url,"POST",((('{"openid":"' . $thisFans['openid']) . '","to_groupid":') . $to_groupid) . '}');
//        		$json = json_decode($post,true);
//        		$this->sdb->table("wechat_group_list")->where("id=".$id)->update(array('g_id' => $to_groupid));
            }
           
            if($openid_list){
            	$json=$platform->batchUpdateGroupMembers($to_groupid,$openid_list);
             	if($json){
             		 
            		$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_user",array('groupid' => $to_groupid),'UPDATE',' id in ('.$ids.') ');
            	}
            }
             $this->success('设置完毕',1);
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