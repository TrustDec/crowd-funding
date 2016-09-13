<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
class WeixinInfoNavSettingAction extends WeixinAction{

	public function __construct(){
		parent::__construct();
		
  	}

	public function account_remove(){
		$config = $this->account;
		$re=$GLOBALS['db']->query("delete from ".DB_PREFIX."weixin_account where id=".$config['id']);
		
		$info= array('info'=>'删除成功','status'=>1);
		if($re){
			echo json_encode($info);
		}else{
			$info['status']=0;
			$info['info']='删除失败';
			echo json_encode($info);
		}
	}
	public function insert()
	{
		$data = M("WeixinAccount")->create ();
		$list=M("WeixinAccount")->add($data);
		$log_info = "微信配置";
 		if (false !== $list) {
			//成功提示
 			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	public function update()
	{
		$data = M("WeixinAccount")->create();
		$list=M("WeixinAccount")->save($data);
		$log_info = "微信配置";
 		if (false !== $list) {
			//成功提示
 			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	public function nav_setting(){
		$account = $this->account;
 		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
 		//if($account){
 			$main_navs=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=".$this->account_id." and pid = 0 ");
  			 
	  		foreach($main_navs as $k=>$v){
					$result_navs[] = $v;
					 
					$sub_navs = M("WeixinNav")->where(array('account_id'=>$this->account_id,'pid'=>$v['id']))->order('sort asc')->findAll();
					foreach($sub_navs as $kk=>$vv){
						$result_navs[] = $vv;
					}
 					
 			}
 			 
			$this->assign("result_navs",$result_navs);
 		//}
 		$this->assign("navs",$this->navs);
 		$this->display();
	}
	
	public function nav_save(){
		
		$ids = $_POST['id'];
		if(count($ids) == 0){
 			$GLOBALS['db']->query("delete from ".DB_PREFIX."weixin_nav where account_id=".$this->account_id);
			$this->success("保存成功",$this->isajax);			
		}
			
		//先验证
		$main_count = 0;
		$sub_count = array();
		foreach($_POST['row_type'] as $k=>$v){
			if($v=="main"){
				$main_count++;
				foreach($_POST['pid'] as $kk=>$pid){
					if(intval($pid)==intval($_POST['id'][$k])){
						$sub_count[$pid] = intval($sub_count[$pid])+1;
					}
				}
			}
		}
		
		if($main_count>3){
			$this->error("主菜单个数不能超过三个",$this->isajax);
			//$this->showFrmErr("主菜单个数不能超过三个",$this->isajax);
		}
		foreach ($sub_count as $sub_c)
		{
			if(intval($sub_c)>5){
				$this->error("子菜单个数不能超过五个",$this->isajax);
			}
		}
		$saved_ids = array();
		//var_dump($_REQUEST);exit;
		foreach($ids as $k=>$id){
			$id = intval($id);			
			if($id>0){
				//更新
				$nav_data['name'] = trim($_REQUEST['name'][$k]);
				$nav_data['sort'] = intval($_REQUEST['sort'][$k]);
				$nav_data['key_or_url'] = trim($_REQUEST['key_or_url'][$k]);
				$nav_data['pid'] = intval($_REQUEST['pid'][$k]);
				
				if($_REQUEST['u_module'][$k]!='')
				{
					$nav_data['key_or_url'] = '';
				}
				if($nav_data['key_or_url']!='')
				{
					$nav_data['u_module'] = '';
					$nav_data['u_action'] = '';
					$nav_data['u_id'] = '';
					$nav_data['u_param'] = '';
				}else{
					$nav_data['u_id'] = intval($_REQUEST['u_id'][$k]);
					$nav_data['u_module'] = trim($_REQUEST['u_module'][$k]);
					$nav_data['u_action'] = trim($_REQUEST['u_action'][$k]);
					$nav_data['u_param'] = trim($_REQUEST['u_param'][$k]);
				}
				
				
				
				$nav_data['status'] = 0;
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",$nav_data,'update',"id=$id and account_id=".$this->account_id);
				//$this->sdb->table('weixin_nav')->where(array('id'=>$id,'seller_id'=>$this->seller_id))->silent()->update($nav_data);
 				array_push($saved_ids, $id);
			}else{
				//新增
				$nav_data['name'] = strim($_REQUEST['name'][$k]);
				$nav_data['sort'] = intval($_REQUEST['sort'][$k]);
				$nav_data['key_or_url'] = strim($_REQUEST['key_or_url'][$k]);
				$nav_data['pid'] = intval($_REQUEST['pid'][$k]);
				$nav_data['event_type'] = "click";
				$nav_data['account_id'] = $this->account_id;
				
				if($_REQUEST['u_module'][$k]!='')
				{
					$nav_data['key_or_url'] = '';
				}
				if($nav_data['key_or_url']!='')
				{
					$nav_data['u_module'] = '';
					$nav_data['u_action'] = '';
					$nav_data['u_id'] = '';
					$nav_data['u_param'] = '';
				}else{
					$nav_data['u_id'] = intval($_REQUEST['u_id'][$k]);
					$nav_data['u_module'] = trim($_REQUEST['u_module'][$k]);
					$nav_data['u_action'] = trim($_REQUEST['u_action'][$k]);
					$nav_data['u_param'] = trim($_REQUEST['u_param'][$k]);
				}
//				$nav_data['u_id'] = intval($_REQUEST['u_id'][$k]);
//				$nav_data['u_module'] = intval($_REQUEST['u_module'][$k]);
//				$nav_data['u_action'] = intval($_REQUEST['u_action'][$k]);
//				$nav_data['u_param'] = intval($_REQUEST['u_param'][$k]);
				
				$nav_data['status'] = 0;
				//$nid = $this->sdb->table('weixin_nav')->silent()->insert($nav_data);
				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",$nav_data);
				$nid = $GLOBALS['db']->insert_id();	
				array_push($saved_ids,intval($nid));
			}
		}
	
		//$del_items = $this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id,'id'=>array('not in',$saved_ids)))->getAll();
		$condition['account_id'] = $this->account_id;
		$condition['id'] = array('not in',$saved_ids);
		$del_items = M("WeixinNav")->where($condition)->findAll();
		//$del_items = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id".$this->account_id." and id not in ");
		foreach($del_items as $it){
			//$this->sdb->table('weixin_nav')->where(array('pid'=>$it['id']))->delete();
			
			M("WeixinNav")->where(array('pid'=>$it['id']))->delete();
		}
		//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id,'id'=>array('not in',$saved_ids)))->delete();
		M("WeixinNav")->where(array('account_id'=>$this->account_id,'id'=>array('not in',$saved_ids)))->delete();
 		$this->success("保存成功",$this->isajax);
	}
	
	public function load_module()
	{
		$id = intval($_REQUEST['id']);
		$module = trim($_REQUEST['module']);
		$act = M("WeixinNav")->where("id=".$id)->getField("u_action");
		$this->ajaxReturn($this->navs[$module]['acts'],$act);
	}
	
	public function new_nav_row(){
		$row_type= strim($_REQUEST['row_type']) == "main" ? "main" : "sub";
		if($row_type=="sub"){
			$pid = intval($_REQUEST['id']);		
			$item['pid'] = $pid;
			 
			$this->assign("item",$item);
		}
		$this->assign("row_type",$row_type);
 		$this->assign("navs",$this->navs);
		echo $this->fetch("new_nav_row");
	}
	
	public function syn_to_weixin(){
		//开始获取微信的token
		$weixin_app_id = $this->account['authorizer_appid'];
		$weixin_app_key = $this->account['authorizer_access_token'];
		if($weixin_app_id=="" || $weixin_app_key==""){
			//$this->showFrmErr("请先设置授权",1,"",JKU("nav/auth"));
			$this->error("请先设置授权",$this->isajax);
		}
		$platform= new PlatformWechat($this->option);
  	 	$platform_authorizer_token=$platform->check_platform_authorizer_token();
 		if($platform_authorizer_token){
 				//开始读取菜单配置
				$navs =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=".$this->account_id." and pid=0 order by sort asc"); 
 				foreach($navs as $k=>$v){
					$sub_navs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=".$this->account_id." and pid=".$v['id']." order by sort asc");
					$navs[$k]['sub_button'] = $sub_navs;
				}
				
				$button_data = array();
				foreach($navs as $k=>$v){
					$button_data[$k]['name'] = $v['name'];
					if(count($v['sub_button'])==0){
						if($v['key_or_url']){
							if(strtolower(substr($v['key_or_url'], 0,7))=="http://"){
								$button_data[$k]['type'] = "view";
								$button_data[$k]['url'] = $v['key_or_url'];
									
							}else{
								$button_data[$k]['type'] = "click";
								$button_data[$k]['key'] = $v['key_or_url'];
							}
						}else{
							$button_data[$k]['type'] = "view";
 							if($v['u_module']=="")$v['u_module']="index";
							if($v['u_action']=="")$v['u_action']="index";
							$route = $v['u_module'];
							if($v['u_action']!='')$route.="#".$v['u_action'];								
							$str = "u:".$route."|".$v['u_param'];					
							$button_data[$k]['url']  =  get_domain().parse_url_tag_coomon($str);
 						}	
							
					}else{
						$sub_button_data = array();
						foreach($v['sub_button'] as $kk=>$vv){
							$sub_button_data[$kk]['name'] = $vv['name'];
							if($v['key_or_url']){
								if(strtolower(substr($vv['key_or_url'], 0,7))=="http://"){
									$sub_button_data[$kk]['type'] = "view";
									$sub_button_data[$kk]['url'] = $vv['key_or_url'];
								}else{
									$sub_button_data[$kk]['type'] = "click";
									$sub_button_data[$kk]['key'] = $vv['key_or_url'];
								}
							}else{
								$sub_button_data[$kk]['type'] = "view";
								if($vv['u_module']=="")$vv['u_module']="index";
								if($vv['u_action']=="")$vv['u_action']="index";
								$route = $vv['u_module'];
								if($vv['u_action']!='')$route.="#".$v['u_action'];								
								$str = "u:".$route."|".$vv['u_param'];					
								$sub_button_data[$kk]['url']  =  get_domain().parse_url_tag($str);
							}
								
						}
						$button_data[$k]['sub_button'] = $sub_button_data;
					}					
				}
				$json_data['button'] = $button_data;
 				$result=$platform->createMenu($json_data);
 				
				if($result){
 					if(!isset($result['errcode']) || intval($result['errcode'])==0){
 						$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",array('status'=>1),'UPDATE',"account_id=".$this->account_id);
						//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id))->setField('status',1);
						$this->success("同步成功",$this->isajax);
					}else{
 						$this->error("同步出错，错误代码".$result['errcode'].":".$result['errmsg'],$this->isajax);
					}
				}else{
					$this->error("通讯出错，请重试",1);
				}
			}else{
			$this->error("通讯出错，请重试",1);
		}
	}
	
 }
?>