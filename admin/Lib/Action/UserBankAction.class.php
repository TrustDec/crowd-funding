<?php
	class UserBankAction extends CommonAction{
		public function __construct()
		{
			parent::__construct();
			//会员名
			$user_id = intval($_REQUEST['user_id']);
			$username = M("user")->where("id=".$user_id)->getField("user_name");
			$this->assign("username", $username);
			$this->assign("user_id", $user_id);
		}
		
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