<?php

class UserCarryAction extends CommonAction{

    //提现申请列表
	public function index(){
		
		$this->getlist(trim($_REQUEST['status']));
	}
	public function wait(){
		$this->getlist("0");
	}
	public function waitpay(){
		
		$this->getlist("3");
	}
	public function success(){
		$this->getlist("1");
	}
	public function failed(){
		$this->getlist("2");
	}
	public function reback(){
		$this->getlist("4");
	}
	
	
	private function getlist($status=''){
		if(trim($_REQUEST['user_name'])!='')
		{
			$map['user_id'] = D("User")->where("user_name='".trim($_REQUEST['user_name'])."'")->getField('id');
		}
		
		if($status!='')
		{
			$map['status'] = intval($status);
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$model = D ("UserCarry");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		
		$this->display ("index");
	}
	//提现申请列表
	public function edit(){
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$vo['region_lv1_name'] = M("DeliveryRegion")->where("id=".$vo['region_lv1'])->getField("name");
		$vo['region_lv2_name'] = M("DeliveryRegion")->where("id=".$vo['region_lv2'])->getField("name");
		$vo['region_lv3_name'] = M("DeliveryRegion")->where("id=".$vo['region_lv3'])->getField("name");
		$vo['region_lv4_name'] = M("DeliveryRegion")->where("id=".$vo['region_lv4'])->getField("name");
		$vo['bank_name'] =  M("bank")->where("id=".$vo['bank_id'])->getField("name");
		
		$this->assign("vo",$vo);
		$this->display ();
	}
	
	public function update(){
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		switch($data['status']){
			case 0:
				$action = 'wait';
				break;
			case 1:
				$action = 'success';
				break;
			case 2:
				$action = 'failed';
				break;
			case 3:
				$action = 'waitpay';
				break;
			case 4:
				$action = 'reback';
				break;
			default :
				$action = 'index';
				break;
		}
	
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		
		if ($list > 0) {
			$sdata['update_time'] = TIME_UTC;
			$sdata['id'] = $data['id'];
			M(MODULE_NAME)->save ($sdata);
			//成功提示
			$vo = M(MODULE_NAME)->where("id=".$data['id'])->find();
			$user_id = $vo['user_id'];
			$user_info = M("User")->where("id=".$user_id)->find();
			require_once APP_ROOT_PATH."/system/libs/user.php";
			if($data['status']==1){
				//提现
				modify_account(array("lock_money"=>-$vo['money']),$vo['user_id'],"提现成功",8);
				modify_account(array("lock_money"=>-$vo['fee']),$vo['user_id'],"提现成功",9);
				$content = "您于".to_date($vo['create_time'],"Y年m月d日 H:i:s")."提交的".format_price($vo['money'])."提现申请汇款成功，请查看您的资金记录。";
				
				
				$group_arr = array(0,$user_id);
				sort($group_arr);
				$group_arr[] =  6;
				
				$msg_data['content'] = $content;
				$msg_data['to_user_id'] = $user_id;
				$msg_data['create_time'] = TIME_UTC;
				$msg_data['type'] = 0;
				$msg_data['group_key'] = implode("_",$group_arr);
				$msg_data['is_notice'] = 6;
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."msg_box",$msg_data);
				$id = $GLOBALS['db']->insert_id();
				$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set group_key = '".$msg_data['group_key']."_".$id."' where id = ".$id);
				
				//短信通知
				if(app_conf("SMS_ON")==1)
				{
					$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_CARYY_SUCCESS_SMS'");				
					$tmpl_content = $tmpl['content'];
									
					$notice['user_name'] = $user_info["user_name"];
					$notice['carry_money'] = $vo['money'];
					$notice['site_name'] = app_conf("SHOP_TITLE");
					
					$GLOBALS['tmpl']->assign("notice",$notice);
					
					$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
					
					$msg_data['dest'] = $user_info['mobile'];
					$msg_data['send_type'] = 0;
					$msg_data['title'] = "提现成功短信提醒";
					$msg_data['content'] = addslashes($msg);;
					$msg_data['send_time'] = 0;
					$msg_data['is_send'] = 0;
					$msg_data['create_time'] = TIME_UTC;
					$msg_data['user_id'] = $user_info['id'];
					$msg_data['is_html'] = $tmpl['is_html'];
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入				
				}
			}
			elseif($data['status']==2){
				//驳回
				modify_account(array("money"=>$vo['money'],"lock_money"=>-$vo['money']),$vo['user_id'],"提现失败",8);
				modify_account(array("money"=>$vo['fee'],"lock_money"=>-$vo['fee']),$vo['user_id'],"提现失败",9);
				$content = "您于".to_date($vo['create_time'],"Y年m月d日 H:i:s")."提交的".format_price($vo['money'])."提现申请被我们驳回，驳回原因\"".$data['msg']."\"";
				
				$group_arr = array(0,$user_id);
				sort($group_arr);
				$group_arr[] =  7;
				
				$msg_data['content'] = $content;
				$msg_data['to_user_id'] = $user_id;
				$msg_data['create_time'] = TIME_UTC;
				$msg_data['type'] = 0;
				$msg_data['group_key'] = implode("_",$group_arr);
				$msg_data['is_notice'] = 7;
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."msg_box",$msg_data);
				$id = $GLOBALS['db']->insert_id();
				$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set group_key = '".$msg_data['group_key']."_".$id."' where id = ".$id);
			}
			save_log("编号为".$data['id']."的提现申请".L("UPDATE_SUCCESS"),1);
			//开始验证有效性
			$this->assign("jumpUrl",u(MODULE_NAME."/".$action));
			parent::success(L("UPDATE_SUCCESS"));
		}else {
			//错误提示
			$DBerr = M()->getDbError();
			save_log("编号为".$data['id']."的提现申请".L("UPDATE_FAILED").$DBerr,0);
			$this->error(L("UPDATE_FAILED").$DBerr,0);
		}
	}
	
	public function config(){
		$list = M()->query("SELECT * FROM ".DB_PREFIX."user_carry_config WHERE vip_id='0' ORDER BY id ASC");
		$this->assign("list",$list);
		$this->display();
	}
	
	public function saveconfig(){
		$config = $_POST['config'];
		$has_ids = null;
		foreach($config['id'] as $k=>$v){
			if(intval($v) > 0){
				$has_ids[] = $v;
			}
		}
		M()->query("DELETE FROM ".DB_PREFIX."user_carry_config WHERE id not in (".implode(",",$has_ids).")");
		
		foreach($config['id'] as $k=>$v){
			if(intval($v) > 0){
				$config_data =array();
				$config_data['id'] = $v;
				$config_data['name'] = trim($config['name'][$k]);
				$config_data['min_price'] = floatval($config['min_price'][$k]);
				$config_data['max_price'] = floatval($config['max_price'][$k]);
				$config_data['fee'] = floatval($config['fee'][$k]);
				$config_data['vip_id'] = 0;
				$config_data['fee_type'] = intval($config['fee_type'][$k]);
				M("UserCarryConfig")->save($config_data);
			}
		}
		
		$aconfig = $_POST['aconfig'];
		foreach($aconfig['name'] as $k=>$v){
			if(trim($v)!=""){
				$config_data =array();
				$config_data['name'] = trim($v);
				$config_data['min_price'] = floatval($aconfig['min_price'][$k]);
				$config_data['max_price'] = floatval($aconfig['max_price'][$k]);
				$config_data['fee'] = floatval($aconfig['fee'][$k]);
				$config_data['fee_type'] = intval($aconfig['fee_type'][$k]);
				M("UserCarryConfig")->add($config_data);
			}
		}
		rm_auto_cache("user_carry_config");
		parent::success(L("UPDATE_SUCCESS"));
	}
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$map['user_id'] = D("User")->where("user_name='".trim($_REQUEST['user_name'])."'")->getField('id');
		}
		
		if(trim($_REQUEST['status_type'])=="index"){
			if(trim($_REQUEST['status'])!='')
			{
				$map['status'] = intval($_REQUEST['status']);
			}
		}
		else{
			$status_type = trim($_REQUEST['status_type']);
			switch($status_type){
				case "wait":
					$map['status'] = 0;
					break;
				case "waitpay":
					$map['status'] = 3;
					break;
				case "success":
					$map['status'] = 1;
					break;
				case "failed":
					$map['status'] = 2;
					break;
				case "reback":
					$map['status'] = 4;
					break;
			}
		}
		
		$list = M("UserCarry")
				->where($map)
				->join(DB_PREFIX.'user ON '.DB_PREFIX.'user.id = '.DB_PREFIX.'user_carry.user_id')
				->join(DB_PREFIX.'delivery_region lv1 ON lv1.id = '.DB_PREFIX.'user_carry.region_lv1')
				->join(DB_PREFIX.'delivery_region lv2 ON lv2.id = '.DB_PREFIX.'user_carry.region_lv2')
				->join(DB_PREFIX.'delivery_region lv3 ON lv3.id = '.DB_PREFIX.'user_carry.region_lv3')
				->join(DB_PREFIX.'delivery_region lv4 ON lv4.id = '.DB_PREFIX.'user_carry.region_lv4')
				->join(DB_PREFIX.'bank ON '.DB_PREFIX.'bank.id = '.DB_PREFIX.'user_carry.bank_id')
				->field(
						DB_PREFIX.'user_carry.id,' .
						DB_PREFIX.'user.user_name,' .
						DB_PREFIX.'user_carry.real_name,' .
						DB_PREFIX.'user_carry.bankzone,' .
						DB_PREFIX.'user_carry.bankcard,' .
						DB_PREFIX.'user_carry.money,' .
						DB_PREFIX.'user_carry.fee,' .
						DB_PREFIX.'user_carry.create_time,' .
						DB_PREFIX.'user_carry.update_time,' .
						DB_PREFIX.'user_carry.status,' .
						DB_PREFIX.'user.mobile,' .
						DB_PREFIX.'user_carry.desc,' .
						'lv1.name as lv1_name,' .
						'lv2.name as lv2_name,' .
						'lv3.name as lv3_name,' .
						'lv4.name as lv4_name,' .
						DB_PREFIX.'bank.name as bank_name,' .
						'lv4.name as lv4_name'
						)
				->limit($limit)->findAll();


		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$carry_value = array('id'=>'""','bank_name'=>'""','region'=>'""','regions'=>'""','bankzone'=>'""','real_name'=>'""','bankcard'=>'""','money'=>'""','mobile'=>'""','desc'=>'""');
			if($page == 1){
				$content = iconv("utf-8","gbk","编号,银行,地区（省）,地区（市/区）,支行名称,开户名,卡号,金额,电话号码,操作备注");
				$content = $content . "\n";
			}
			foreach($list as $k=>$v)
			{	
				$carry_value = array();
				$carry_value['id'] = iconv('utf-8','gbk','"' . $v['id'] . '"');
				$carry_value['bank_name'] = iconv('utf-8','gbk','"' . $v['bank_name'] . '"');
				$carry_value['region'] = iconv('utf-8','gbk','" '.$v['lv1_name'] .' '.$v['lv2_name'] .' "');
				$carry_value['regions'] = iconv('utf-8','gbk','"  '.$v['lv3_name'] .' '.$v['lv4_name'] . '"');
				$carry_value['bankzone'] = iconv('utf-8','gbk','"' . $v['bankzone'] . '"');
				$carry_value['real_name'] = iconv('utf-8','gbk','"' . $v['real_name'] . '"');
				$carry_value['bankcard'] = iconv('utf-8','gbk','"' . $v['bankcard'] . '"');
				$carry_value['money'] = iconv('utf-8','gbk','"' .  number_format($v['money'],2) . '"');
				$carry_value['mobile'] = iconv('utf-8','gbk','"' . $v['mobile'] . '"');
				$carry_value['desc'] = iconv('utf-8','gbk','"' . $v['desc'] . '"');
			
				$content .= implode(",", $carry_value) . "\n";
			}	
					
			header("Content-Disposition: attachment; filename=carry_list.csv");
	    	echo $content;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}
		
	}
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				
				
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
		
				if ($list!==false) {					
					save_log(l("FOREVER_DELETE_SUCCESS"),1);
					parent::success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log(l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}	
}
?>