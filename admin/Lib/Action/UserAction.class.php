<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class UserAction extends CommonAction{
	public function __construct()
	{	
		parent::__construct();
		require_once APP_ROOT_PATH."/system/libs/user.php";
		//会员银行
		$user_id = intval($_REQUEST['user_id']);
		$username = M("user")->where("id=".$user_id)->getField("user_name");
		$this->assign("username", $username);
		$this->assign("user_id", $user_id);
	}
	public function index()
	{
		$now=get_gmtime();
		if(trim($_REQUEST['user_name'])!='')
		{
			$map[DB_PREFIX.'user.user_name'] = array('like','%'.trim($_REQUEST['user_name']).'%');
		}
		if(trim($_REQUEST['email'])!='')
		{
			$map[DB_PREFIX.'user.email'] = array('like','%'.trim($_REQUEST['email']).'%');
		}
		if(trim($_REQUEST['mobile'])!='')
		{
			$map[DB_PREFIX.'user.mobile'] = array('like','%'.trim($_REQUEST['mobile']).'%');
		}
		 
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='' )
		{
			$map[DB_PREFIX.'user.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		if(intval($_REQUEST['id'])>0)
		{
			$map[DB_PREFIX.'user.id'] = intval($_REQUEST['id']);
		}
		if($_REQUEST['is_effect']=='NULL'){
			unset($_REQUEST['is_effect']);
		}
		if($_REQUEST['is_effect']!=NULL){
			$map['is_effect']=intval($_REQUEST['is_effect']);
		}
		if($_REQUEST['is_investor']=='NULL'){
			unset($_REQUEST['is_investor']);
		}
		if(trim($_REQUEST['is_investor'])!='')
		{
			$map[DB_PREFIX.'user.is_investor'] = intval($_REQUEST['is_investor']);
			if(intval($_REQUEST['is_investor']) !=0){
				$map[DB_PREFIX.'user.investor_status'] = 1;
			}
			
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}

	public function add()
	{
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$this->assign("region_lv2",$region_lv2);	
		//会员等级
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->display();
	}
	
	public function insert() {
		
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
	
		if(!check_empty($data['user_pwd']))
		{
			$this->error(L("USER_PWD_EMPTY_TIP"));
		}	
		if($data['user_pwd']!=$_REQUEST['user_confirm_pwd'])
		{
			$this->error(L("USER_PWD_CONFIRM_ERROR"));
		}
		$res = save_user($_REQUEST,'INSERT',$update_status=1);
 		if($res['status']==0)
		{
			$error_field = $res['data'];
			if($error_field['error'] == EMPTY_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EMPTY_TIP"));
				}
				elseif($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EMPTY_TIP"));
				}
				else
				{
					$this->error(sprintf(L("USER_EMPTY_ERROR"),$error_field['field_show_name']));
				}
			}
			if($error_field['error'] == FORMAT_ERROR)
			{
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_FORMAT_TIP"));
				}
				elseif($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_FORMAT_TIP"));
				}
			}
			
			if($error_field['error'] == EXIST_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EXIST_TIP"));
				}
				elseif($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EXIST_TIP"));
				}
				elseif($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_EXIST_TIP"));
				}
			}
		}
		$user_id = intval($res['user_id']);
		
		// 更新数据
		$log_info = $data['user_name'];
		save_log($log_info.L("INSERT_SUCCESS"),1);
		$this->success(L("INSERT_SUCCESS"));
		
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $vo['province'])
			{
				$region_lv2[$k]['selected'] = 1;
				$region_pid = $region_lv2[$k]['id'];
				break;
			}
		}
		$this->assign("region_lv2",$region_lv2);
		
		
		if($region_pid>0)
		{
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['name'] == $vo['city'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$this->assign("region_lv3",$region_lv3);
		}
		//会员等级信息
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->display ();
	}
		

	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['user_name'];	
				}
				if($info) $info = implode(",",$info);
				$ids = explode ( ',', $id );
				foreach($ids as $uid)
				{
					delete_user($uid);
				}
				save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
				$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
		
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("user_name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['user_pwd'])&&$data['user_pwd']!=$_REQUEST['user_confirm_pwd'])
		{
			$this->error(L("USER_PWD_CONFIRM_ERROR"));
		}
		//app和admin共用user.php的save_user方法，后台update是没有验证码的，所以save_user设置标示字段$update_status
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($_REQUEST['id']));
		$user_info = array_merge($user_info,$_REQUEST);
  		$res = save_user($user_info,'UPDATE',$update_status=1);
		if($res['status']==0)
		{
			$error_field = $res['data'];
			if($error_field['error'] == EMPTY_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EMPTY_TIP"));
				}
				elseif($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EMPTY_TIP"));
				}
				else
				{
					$this->error(sprintf(L("USER_EMPTY_ERROR"),$error_field['field_show_name']));
				}
			}
			if($error_field['error'] == FORMAT_ERROR)
			{
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_FORMAT_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_FORMAT_TIP"));
				}
			}
			
			if($error_field['error'] == EXIST_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_EXIST_TIP"));
				}
			}
		}
 		
		//开始更新is_effect状态
		M("User")->where("id=".intval($_REQUEST['id']))->setField("is_effect",intval($_REQUEST['is_effect']));
		$user_id = intval($_REQUEST['id']);		
		
		save_log($log_info.L("UPDATE_SUCCESS"),1);
		$this->success(L("UPDATE_SUCCESS"));
		
	}

	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$user_info = M(MODULE_NAME)->getById($id);
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		$result=M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);
		if($result && $c_is_effect==0 && $user_info['is_send_referrals']==1 && $user_info['pid'] >0)
		{
			send_referrals($user_info);//发入返利给推荐人
		}	
		save_log($user_info['user_name'].l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1);	
	}
	
	public function account()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$this->display();
	}
	public function modify_account()
	{
		$user_id = intval($_REQUEST['id']);
		$money = floatval($_REQUEST['money']);
		$score = intval($_REQUEST['score']);
		$point = intval($_REQUEST['point']);
		$msg = trim($_REQUEST['msg'])==''?l("ADMIN_MODIFY_ACCOUNT"):trim($_REQUEST['msg']);
		modify_account(array('money'=>$money,'score'=>$score,'point'=>$point),$user_id,$msg);
		save_log(l("ADMIN_MODIFY_ACCOUNT"),1);
		$this->success(L("UPDATE_SUCCESS")); 
	}
	
	public function account_detail()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$map['user_id'] = $user_id;
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = M ("UserLog");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	public function foreverdelete_account_detail()
	{
		
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("UserLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("UserLog")->where ( $condition )->delete();	
				
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	
public function consignee()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$map['user_id'] = $user_id;
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = M ("UserConsignee");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	public function foreverdelete_consignee()
	{
		
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("UserConsignee")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("UserConsignee")->where ( $condition )->delete();	
				
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function weibo()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$map['user_id'] = $user_id;
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = M ("UserWeibo");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	public function foreverdelete_weibo()
	{
		
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("UserWeibo")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("UserWeibo")->where ( $condition )->delete();	
				
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function check_user(){
		if(intval($_REQUEST['id'])>0)
		{
			$uinfo = M("User")->getById(intval($_REQUEST['id']));
			if($uinfo)
			{
				$result['status'] = true;
				ajax_return($result);
			}
			else
			{
				$result['status'] = false;
				ajax_return($result);
			}
		}
		$result['status'] = false;
		ajax_return($result);
	}
	/**
	 * 会员银行
	 */
		public function userbank_index(){
			$user_id = intval($_REQUEST['user_id']);
			$map['user_id']=$user_id;
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ($name);
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
			
			$this->assign ( 'vo', $vo );
			$this->display ();
		}
		
		public function userbank_add(){
			$user_id = intval($_REQUEST['user_id']);
			
			$userinfo = M("User")->getById($user_id);
			
			if(!$userinfo['identify_name'])
				$this->error("该会员的身份认证未完成，暂不能增加银行信息!");
				
			$this->assign("userinfo",$userinfo);
			
			//省份
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			$this->assign("region_lv2",$region_lv2);
			
			//银行
			$bank_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."bank order by is_rec desc,sort desc");  //银行列表
			$this->assign("bank_list",$bank_list);
			
			$this->assign("back_url",u(MODULE_NAME."/index",array("user_id"=>$user_id)));
			$this->display();
		}
		//银行编辑
		public function userbank_edit(){
			$user_id = intval($_REQUEST['user_id']);
			$id = intval($_REQUEST ['id']);
			
			$condition['id'] = $id;
			$vo = M(MODULE_NAME)->where($condition)->find();
			$this->assign ( 'vo', $vo );
			
			//省份
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			$this->assign("region_lv2",$region_lv2);
			
			//城市
			$region_lv2_id = M("RegionConf")->where("name='".$vo['region_lv2']."'")->getField("id");
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 3 and pid=".intval($region_lv2_id)." order by py asc");  //三级地址
			$this->assign("region_lv3",$region_lv3);
			
			//银行
			$bank_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."bank order by is_rec desc,sort desc");  //银行列表
			$this->assign("bank_list",$bank_list);
			
			$this->assign("back_url",u(MODULE_NAME."/index",array("user_id"=>$user_id)));
			$this->display ();
		}
		
		public function userbank_insert() {
			B('FilterString');
			$ajax = intval($_REQUEST['ajax']);
			$data = M(MODULE_NAME)->create ();
			
			$userinfo = M("User")->getById($data['user_id']);
			
			//开始验证有效性
			$this->assign("jumpUrl", u(MODULE_NAME."/add",array("user_id"=>$data['user_id'])) );
			
			if(!$userinfo['identify_name'])
				$this->error("该会员的身份认证未完成，暂不能增加银行信息!");
			
			if(!$data['bank_id'])
				$this->error("请选择银行");
			
			if(!$data['region_lv2'])
				$this->error("请选择省份");
				
			if(!$data['region_lv3'])
				$this->error("请选择城市");
			
			if($data['bankzone'] == '')
				$this->error("请输入开户行网点");
			
			if($data['bankcard'] == '')
				$this->error("请输入银行卡号");
			
			if($data['bankcard'] != $_REQUEST['reBankcard'])
				$this->error("银行卡号与确认卡号不一致");
				
			//插入数据
			$bank_name = M("bank")->where("id=".$data['bank_id'])->getField("name");
			$data['bank_name']=$bank_name;
			$data['real_name']=$userinfo['identify_name'];
			$log_info = $userinfo['user_name'].$bank_name."卡";
			$list=M(MODULE_NAME)->add($data);
			
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
		
		public function userbank_update(){
			B('FilterString');
			$data = M(MODULE_NAME)->create ();
			$this->assign("jumpUrl", u(MODULE_NAME."/edit",array("id"=>$data['id'],"user_id"=>$data['user_id'])) );

			$user_bank = M(MODULE_NAME)->where("id=".intval($data['id'])." and user_id= ".intval($data['user_id']) )->find();
			if(!$user_bank)
				$this->error("请选择编辑的银行信息!");
			
			$userinfo = M("User")->getById($data['user_id']);
			
			//开始验证有效性
			if(!$userinfo['identify_name'])
				$this->error("该会员的身份认证未完成，暂不能增加银行信息!");
				
			if(!$data['bank_id'])
				$this->error("请选择银行");
			
			if(!$data['region_lv2'])
				$this->error("请选择省份");
				
			if(!$data['region_lv3'])
				$this->error("请选择城市");
			
			if($data['bankzone'] == '')
				$this->error("请输入开户行网点");
			
			if($data['bankcard'] == '')
				$this->error("请输入银行卡号");
			
			$bank_name = M("bank")->where("id=".$data['bank_id'])->getField("name");
			$data['bank_name']=$bank_name;
			unset($data['user_id']);
			$log_info = $userinfo['user_name']."银行信息（id=".$data['id']."）";
			$list=M(MODULE_NAME)->save ($data);
			
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
		
		
		public function userbank_delete(){
			//彻底删除指定记录
			$ajax = intval($_REQUEST['ajax']);
			$id = strim($_REQUEST ['id']);
			$user_id = intval($_REQUEST ['user_id']);
			$userinfo = M("User")->getById($user_id);
			if (isset ( $id )) {
					$condition = array ('id' => array ('in', explode ( ',', $id ) ),"user_id" => $user_id);			
					$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
					foreach($rel_data as $data)
					{
						$info[] = $data['bank_name']."(尾号".substr($data['bankcard'],-4).")";		
					}
					if($info)
					{
						$info = "(会员".$userinfo['user_name'].")".implode(",",$info);
					}
					$list = M(MODULE_NAME)->where ( $condition )->delete();
					
					if ($list!==false) {
						save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
						$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
					} else {
						save_log($info.l("FOREVER_DELETE_FAILED"),0);
						$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
					}
				} else {
					$this->error (l("INVALID_OPERATION"),$ajax);
			}
		}
}
?>