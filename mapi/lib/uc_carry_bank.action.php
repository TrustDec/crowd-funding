<?php
class uc_carry_bank{
	public function index()
	{	
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		//银行列表
		$banks=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bank where user_id=".$user['id']);
		foreach($banks as $k=>$v)
		{
			$banks[$k]['icon']=SITE_DOMAIN.APP_ROOT."/../public/bank/".$v['bank_id'].".jpg";
		}
		$root['banks']=$banks;
		
		$is_tg=intval(is_tg());//是否有安装第三方托管
		$is_view_tg=0;//0：不显示第三方托管，1显示
		if($is_tg && $user['ips_acct_no']){
			
			$is_view_tg=1;//显示第三方托管
			//手续费
			$fee_config = load_auto_cache("user_carry_config");
			foreach($fee_config as $k=>$v){
				$json_fee[] = $v;
				if($v['fee_type']==1)
					$fee_config[$k]['fee_format'] = $v['fee']."%";
				else
					$fee_config[$k]['fee_format'] = format_price($v['fee']);
			}
			
			$result = GetIpsUserMoney($user_id,0);
			$root['ips_money'] = $result['pBalance']-$result['pLock'];//托管可用余额
			
			$app_url = HTML_APP_ROOT."index.php?ctl=collocation&act=DoDwTrade&user_type=0&pTrdAmt=parm_amt&user_id=".$user_id."&from=app";
			$root['dw_url'] = SITE_DOMAIN.$app_url;//提现路径
			$bind_bank_url = HTML_APP_ROOT."index.php?ctl=collocation&act=BindBankCard&user_id=".$user_id."&from=app";
			$root['bind_bank_url'] = SITE_DOMAIN.$bind_bank_url;//绑定银行卡
		}else
		{
			$fee_config=array();
		}
		
		$root['is_view_tg']=$is_view_tg;//0：不显示第三方托管，1显示
		$root['fee_config']=$fee_config;//手续费
		
		output($root);
	}
	
	public function add_bank()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$bank_list=$GLOBALS['db']->getAll("select id,name from ".DB_PREFIX."bank");
		$province_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by is_hot desc,id desc");
		foreach($province_list as $k=>$v)
		{
			$province_list[$k]['city']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$v['id']." order by is_hot desc,id desc");
		}
		$root['bank_list']=$bank_list;
		$root['province_list']=$province_list;
		$root['identify_name']=$user['identify_name'];
		
		output($root);
	}
	
	public function save_bank(){
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                            
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$data=array();
		$data['bank_id']=intval($GLOBALS ['request'] ['bank_id']);
		$data['region_lv2']=strim($GLOBALS ['request'] ['province']);
		$data['region_lv3']=strim($GLOBALS ['request'] ['city']);
		$data['bankzone']=strim($GLOBALS ['request'] ['bankzone']);
		$data['bankcard']=strim($GLOBALS ['request'] ['bankcard']);
		
		if($user['identify_name'] =='')
		{
			$root['status']=0;
			$root['info']="请进行完成身份认证，才可以添加银行卡";
			output($root);
		}
		
		if(!$data['bank_id'])
		{
			$root['status']=0;
			$root['info']="请选择银行";
			output($root);
		}
		
		if($data['region_lv2'] =='')
		{
			$root['status']=0;
			$root['info']="请选择省份";
			output($root);
		}
		
		if($data['region_lv3'] =='')
		{
			$root['status']=0;
			$root['info']="请选择城市";
			output($root);
		}
		
		if($data['bankzone'] =='')
		{
			$root['status']=0;
			$root['info']="请输入银行网点";
			output($root);
		}
		
		if($data['bankcard'] =='')
		{
			$root['status']=0;
			$root['info']="请输入银行卡号";
			output($root);
		}
		
		$data['user_id']=$user_id;
		$data['real_name']=$user['identify_name'];
 		$data['bank_name']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."bank where id=".$data['bank_id']);
		
		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$data,"INSERT","","SILENT");
 		if($re){
 			$root['status']=1;
			$root['info']="增加成功";
 		}else{
 			$root['status']=0;
			$root['info']="增加失败";
 		}
 		
		output($root);
	}
	
	public function delete_bank(){
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                               
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
			$root ['response_code'] = 1;
		}
		
		$bank_ids=strim ( $GLOBALS ['request'] ['bank_ids'] );
		if($bank_ids =="")
		{
			$root['status']=0;
			$root['info']="请选择银行";
			output($root);
		}
	
		$bank_ids_array=explode(",",$bank_ids);
		asort($bank_ids_array);
		$bank_ids=implode(",",$bank_ids_array);
		
		$bank_ids_db=$GLOBALS['db']->getOne("select Group_concat(id) from ".DB_PREFIX."user_bank where user_id=".$user['id']." and id in(".$bank_ids.") order by id asc");
		if($bank_ids_db != $bank_ids)
		{
			$root['status']=0;
			$root['info']="请选择正确的银行";
			output($root);
		}
		
		$GLOBALS['db']->query("delete  from ".DB_PREFIX."user_bank where user_id=".$user['id']." and id in(".$bank_ids.")");
		$root['status']=1;
		$root['info']="删除成功";
		
		output($root);
	}
}
?>