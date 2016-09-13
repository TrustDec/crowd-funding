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
class WeixinUserGroupsAction extends WeixinAction{
  	public function __construct(){
		parent::__construct();
 		$this->assign("max_size",get_max_file_size());
 		$this->assign("max_size_byte",get_max_file_size_byte());
  	}
  	public function groups()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$groups=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where account_id=".$this->account_id);
 		$this->assign('groups',$groups);
 		$this->assign('box_title','分组管理');
  		$this->display();
	}
	/**
	 * 删除
	 */
	public function delgroups(){
		$ids_str = strim($_REQUEST['ids']);
		$id = intval($_REQUEST['id']);
		if($ids_str != ""){
			//批量删除
			$replys = M('WeixinGroup')->where(array('id'=>array('in',explode(',',$ids_str))))->findAll();
			foreach($replys as $reply){
				M('WeixinGroup')->where(array('id'=>$reply['id']))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}elseif($id > 0){
			//单条删除
			$reply = M('WeixinGroup')->where(array('id'=>$id))->find();
			if($reply){
				M('WeixinGroup')->where(array('id'=>$id))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}else{
			$this->error("请选择要删除的选项",$this->isajax);
		}
	}
	public function groups_add(){
		 $this->assign("box_title","添加分组");
		 $this->display();
	}
	public function groups_editor(){
		 /********基本设置**********/
        $account_id = $this->account_id;
        $id = intval($_REQUEST['id']);
        /********基本设置**********/
        
        $group =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_group where id=".$id." and account_id=".$account_id); 
       
        $this->assign("group",$group);
        $this->assign("box_title","修改");
		$this->display();
	}
	public function groups_save(){
        /********基本设置**********/
       $this->isajax = 1;
	   $platform = $platform= new PlatformWechat($this->option);
	   $platform_authorizer_token=$platform->check_platform_authorizer_token();
        
        if($_REQUEST['id']){
            $id = intval($_REQUEST['id']);
        }
        $name = strim($_REQUEST['name']);
        $intro = strim($_REQUEST['intro']);
        /********基本设置**********/
        if($id){//如果存在 为更新
             $local_group = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_group where id=".$id);
            
            //判断是否有更新分组名称
            if($local_group['name']==$name && $local_group['intro'] !=$intro ){
                 $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",array('intro'=>$intro),'UPDATE','id='.$id);
                $this->success("更新成功",$this->isajax);
            }elseif($local_group['name']!=$name){
               $json= $platform->updateGroup($local_group['groupid'],$name);
               if($json){
               		$update_data = array();
                    $update_data['name'] = $name;
                    $update_data['intro'] = $intro;
                    $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$update_data,'UPDATE','id='.$id);
               		$this->success("更新成功",$this->isajax);
               }else{
               		$this->error("同步出错，错误代码".$json['errcode'].":".$json['errmsg'],$this->isajax);
               }
                
            }
            $this->success("没有修改内容",$this->isajax);
        }else{//添加新的分组
            $insert_data = array();
            $insert_data['account_id'] = $this->account_id;
            $insert_data['name'] = $name;
            $insert_data['intro'] = $intro;
             //post data 
            $json = $platform->createGroup($name);
            if($json){
                $insert_data['groupid'] = $json['group']['id'];
                $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$insert_data);
                $this->success("新增成功",$this->isajax);
            }else{
                $this->error("新增失败",$this->isajax);
            }
        }
     }
	/**
     * 同步分组
     */
    function groups_synch(){
         /********基本设置**********/
        $this->isajax = 0;
        $platform = $platform= new PlatformWechat($this->option);
        $platform_authorizer_token=$platform->check_platform_authorizer_token();
        if($platform_authorizer_token){
        	$json=$platform->getGroup();
        	if($json){
        		$wechat_groups = $json['groups'];
        		if($wechat_groups){  //存在分组数据
        			$condition= ' account_id='.$this->account_id;
			        //平台端数据
			        $local_groups= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_group where ".$condition);
			        
 			        //格式化数据
			        
			        $update_data = array();
			        $notdel_data = array();
			        $inster_data = array();
			        
			        foreach ($wechat_groups as $wg_k=>$wg_v){
			            //判断是否存在了
			            $is_not_null = true;
			            //不能删除的数据
			            $notdel_data[] = $wg_v['id'];
			            $temp_data = array();
			            $temp_data['account_id'] = $this->account_id;
			            $temp_data['groupid'] = $wg_v['id'];
			            $temp_data['name'] = $wg_v['name'];
			            $temp_data['fanscount'] = $wg_v['count'];
			            foreach($local_groups as $lg_k=>$lg_v){
			                if($lg_v['groupid']==$wg_v['id']){
			                    //分组名称有改变 或者 粉丝数量改变的分组
			                    if($wg_v['name']!=$lg_v['name'] || $wg_v['count'] !=$lg_v['fanscount']){
			                        //更新数据
 			                        $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$temp_data,'UPDATE',$condition." AND groupid=".$temp_data['groupid']);
			                    }
			                    //已经存在了
			                    $is_not_null = FALSE;
			                }
			            }
			            if($is_not_null){
			                //保存不存在的数据
			                $inster_data[] = $temp_data;
			            }
			        }
			        
			        //删除微信端不存在的数据
 					$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."wechat_group WHERE ".$condition." AND groupid not in (".  implode(",", $notdel_data).") ");
			        
			        if($update_data){
			            foreach($update_data as $up_data){
			               
			            }
			        }
			        
			        //插入新的数据
			        if($inster_data){
			            foreach($inster_data as $ins_data){
 			                $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_group",$ins_data);
			            }
			        }
			    }
			    $this->success("同步成功",$this->isajax);
        	}else{
        		$this->error("同步出错，错误代码".$json['errcode'].":".$json['errmsg'],$this->isajax);
        	}
        	
        }else{
        	$this->error("通讯出错，请重试",$this->isajax);
        }
       
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

 }
?>