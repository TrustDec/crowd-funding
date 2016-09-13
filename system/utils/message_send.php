<?php
class message_send{
 	const MSG_PAID = 'MSG_PAID';
 	const MSG_ADMIN_MANAGE = 'MSG_ADMIN_MANAGE';//后台管理操作通知
 	const MSG_MEMBER_REMIDE = 'MSG_MEMBER_REMIDE';//会员提醒
 	const MSG_INCHARGE = 'MSG_INCHARGE';//充值通知
 	const MSG_REFUND = 'MSG_REFUND';//退款通知
 	const MSG_MONEY_CARRY_NOTIFIE = 'MSG_MONEY_CARRY_NOTIFIE';//提现
 	const MSG_MONEY_CARRY_RESULT = 'MSG_MONEY_CARRY_RESULT';//提现结果
 	const MSG_ZC_STATUS = 'MSG_ZC_STATUS';//众筹状态提示
 	const MSG_INVEST_STATUS = 'MSG_INVEST_STATUS';//投资资格审核状态
 	const MSG_INVESTOR_GO_PAY = 'MSG_INVESTOR_GO_PAY';//通知股权用户 允许付款
	
	const CROW_CREATE = 0;//表示创建和修改项目
	const CROW_EXAMINE = 1;//众筹提交审核 
	const CROW_EXAMINE_SUCCESS = 2;//审核通过
	const CROW_EXAMINE_FAIL = 3;//审核失败
	
	const CROW_PROJECT_ING = 10;//表示产品众筹进行中 
	const CROW_PROJECT_SUCCESS = 11;//表示产品众筹成功
	const CROW_PROJECT_FAIL = 12;//表示产品众筹失败
	const CROW_PROJECT_RETURN = 13;//表示产品众筹发放回报
	
	const CROW_INVEST_ING = 20;//表示股权进行中 
	const CROW_INVEST_SUCCESS = 21;//表示股权认投成功
	const CROW_INVEST_FAIL = 22;//表示股权认投失败
	const CROW_INVEST_PAY_ALLOW = 23;//表示股权允许用户付款
	const CROW_INVEST_PAY_FAIL = 24;//表示股权拒绝用户付款
	
	const CROW_INVEST_LT = 25;//表示领投通知
    const CROW_INVEST_LT_ALLOW = 26;//表示股权领投 允许
	const CROW_INVEST_LT_FAIL = 27;//表示股权领投 拒绝
	const CROW_INVEST_PAY_END = 28;//表示股权 付款时间截止，项目结束;
	
	const MAIL_PASSWORD = 'TPL_MAIL_USER_PASSWORD'; //会员取回密码邮件
 	const MAIL_VERIFY = 'TPL_MAIL_USER_VERIFY';  //会员验证邮件
	const MAIL_CHANGE_VERIFY = 'TPL_MAIL_CHANGE_USER_VERIFY';//邮箱修改模板
	const MAIL_INVESTOR_STATUS = 'TPL_MAIL_INVESTOR_STATUS';  //邮件通知用户通过投资人审核
	const MAIL_INVESTOR_GO_PAY = 'TPL_MAIL_INVESTOR_PAY_STATUS'; //邮件通知用户投资申请通过-允许付款
	const MAIL_INVESTOR_PAID = 'TPL_MAIL_INVESTOR_PAID_STATUS'; //邮件通知用户已经付款
	const MAIL_DEMO = 'MAIL_DEMO';//测试邮件
	
	const SMS_DEAL_FAIL = 'TPL_SMS_DEAL_FAIL'; //项目失败
	const SMS_DEAL_SUCCESS = 'TPL_SMS_DEAL_SUCCESS'; //项目成功
	const SMS_REGISTER_SUCCESS = 'TPL_SMS_USER_VERIFY'; //注册成功通知
	const SMS_DEAL_CREAT_SUCCESS = 'TPL_SMS_USER_S'; //通知项目发起人成功
	const SMS_DEAL_CREAT_FAIL = 'TPL_SMS_USER_F'; //通知项目发起人失败
	const SMS_VERIFY = 'TPL_SMS_VERIFY_CODE';  //短信验证码发送
	const SMS_TZT_VERIFY = 'TPL_SMS_TZT_VERIFY_CODE';  //投资通短信验证码发送
	const SMS_INVESTOR_STATUS = 'TPL_SMS_INVESTOR_STATUS'; //短信通知用户通过投资人审核
	const SMS_INVESTOR_GO_PAY = 'TPL_SMS_INVESTOR_PAY_STATUS';  //短信通知用户投资申请通过-允许付款
	const SMS_INVESTOR_PAID = 'TPL_SMS_INVESTOR_PAID_STATUS'; //短信通知用户已经付款
	const SMS_DEMO = 'SMS_DEMO';//测试短信
	
	const MSG_NOTIFY = 'notify';//
	public $data=array();
	public $msg_type = 'text';
	public $tmpl_content = '';
	public $msg_content = '';
 	public $result = true;
	public $debug = true;
	public $logcallback = 'log_result_notify';
	public $user_info ;
	public $is_wx;
 	public $wx_tmpl;
	public $account;
	
	public $msg_tmpl_key;
	public $msg_tmpl_value;
	function __construct(){
		 if(file_exists(APP_ROOT_PATH."public/install.lock"))
		{
		 $weixin_conf = load_auto_cache("weixin_conf");
 		 $this->is_wx = $weixin_conf['platform_status'];
		 if($this->is_wx){
		 	
  	 		$account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where type=1 and user_id=0 ");
	 		$this->account = $account ;
	 		if($account){
   				$this->wx_tmpl = load_auto_cache("weixin_tmpl",array("account_id"=>$this->account['id']));
   			}
 		 }
		}
	}
	
	public function set_debug($status=false){
		$this->debug = $status;
	}
	//emali _1
	public function msg_tmpl($msg_type=''){
		if($msg_type){
			$this->msg_type = $msg_type;
		}
		$tmpl = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."msg_template where name = '".$this->msg_type."'");
		$tmpl_content=  $tmpl['content'];
		$this->tmpl_content = $tmpl_content;
	}
	
	 
	//email _2.1
	public function email_content_code(){
		$user_info = $this->user_info;
 		$user_info['logo']=app_conf("SITE_LOGO");
		$user_info['site_name']=app_conf("SITE_NAME");
		$time=get_gmtime();
		$user_info['send_time']=to_date($time,'Y年m月d日');
		$user_info['send_time_ms']=to_date($time,'Y年m月d日 H时i分');
 		
		$this->log("function email_content_code :tmple_email_name = ".$user_info['tmple_email_name']." :user_info");
		$this->log($user_info);
		
		if($user_info['tmple_email_name']){
			$GLOBALS['tmpl']->assign($user_info['tmple_email_name'],$user_info);
		}else{
			$GLOBALS['tmpl']->assign("user",$user_info);
		}
		
		$msg = $GLOBALS['tmpl']->fetch("str:".$this->tmpl_content);
		$this->msg_content = $msg;
	}
	 
	//sms_2.3
	public function sms_content_code(){
		$user_info = $this->user_info;
		if($user_info['tmpl_sms_name']){
			$GLOBALS['tmpl']->assign($user_info['tmpl_sms_name'],$user_info);
		}else{
			$GLOBALS['tmpl']->assign("user",$user_info);
		}
		
		$msg = $GLOBALS['tmpl']->fetch("str:".$this->tmpl_content);
		$this->msg_content = $msg;
	}
	//wx_2.4
	public function wx_content_code($tmpl){
		if($this->wx_tmpl[$tmpl]&&$this->user_info['wx_openid']){
			$msg = $this->wx_tmpl[$tmpl]['msg'];
			$data = unserialize($msg);
			$time = get_gmtime();
			$now_date = to_date($time);
			$wx_url = get_domain().url_wap("account#index");
			$this->log('function:wx_content_code 参数$tmpl:'.$tmpl);
 			if($tmpl=='OPENTM201490080'){
				//调用OPENTM201490080 ,订单支付成功
				$data['keyword1']=array('value'=>$this->user_info['notice_sn'],'color'=>'#173177');
				$data['keyword2']=array('value'=>$this->user_info['deal_name'],'color'=>'#173177'); 
				$data['keyword3']=array('value'=>format_price($this->user_info['paid_money']),'color'=>'#173177'); 
				$wx_url =  get_domain().url_wap("account#view_order",array('id'=>$this->user_info['notice_sn']));
			}elseif($tmpl=='OPENTM206915380'){
				//调用OPENTM206915380 ,后台管理操作通知
				$data['keyword1']=array('value'=>$this->user_info['control_type'],'color'=>'#173177');
				$data['keyword2']=array('value'=>$now_date,'color'=>'#173177'); 
				$data['keyword3']=array('value'=>$this->user_info['control_content'],'color'=>'#173177');
				$data['keyword4']=array('value'=>'处理完成','color'=>'#173177'); 
				$wx_url =  get_domain().url_wap("index");
			}elseif($tmpl=='OPENTM207029514'){
				$data['first'] = array('value'=>$this->user_info['control_type'],'color'=>'#173177');
				$data['keyword1']=array('value'=>$this->user_info['control_content'],'color'=>'#173177');
				$data['keyword2']=array('value'=>$now_date,'color'=>'#173177'); 
				if($this->user_info['control_url']){
					$wx_url = $this->user_info['control_url'];
				}
			}elseif($tmpl=='OPENTM203026900'){
				$data['keyword1']=array('value'=>$this->user_info['wx_code'],'color'=>'#173177');
				$data['keyword2']=array('value'=>$this->user_info['expire_time'],'color'=>'#173177'); 
			}elseif($tmpl=='OPENTM267386236'){
				$data['keyword1']=array('value'=>format_price($this->user_info['incharge_money']),'color'=>'#173177');
				$data['keyword2']=array('value'=>$now_date,'color'=>'#173177'); 
			}elseif($tmpl=='TM00004'){
				//退款通知
				$data['keyword1']=array('value'=>$this->user_info['control_content'],'color'=>'#173177');
				$data['keyword2']=array('value'=>format_price($this->user_info['refund_money']),'color'=>'#173177'); 
			}elseif($tmpl=='TM00979'){
				//提现申请通知
				$data['first'] = array('value'=>$this->user_info['control_content'],'color'=>'#173177');
				$data['money']=array('value'=>format_price($this->user_info['carray_money']),'color'=>'#173177');
				$data['timet']=array('value'=>$now_date,'color'=>'#173177'); 
			}elseif($tmpl=='OPENTM202425107'){
				//提现结果通知
				$data['keyword1']=array('value'=>format_price($this->user_info['carray_money']),'color'=>'#173177');
				$data['keyword2']=array('value'=>$this->user_info['carray_bank'],'color'=>'#173177'); 
				$data['keyword3']=array('value'=>$this->user_info['carray_create_time'],'color'=>'#173177');
				$data['keyword4']=array('value'=>$this->user_info['carray_end_time'],'color'=>'#173177'); 
				$data['keyword5']=array('value'=>$this->user_info['result'],'color'=>'#173177'); 
			}elseif($tmpl=='OPENTM202116678'){
				//众筹通知
				$data['keyword1']=array('value'=>$this->user_info['control_title'],'color'=>'#173177');
				$data['keyword2']=array('value'=>$this->user_info['control_content'],'color'=>'#173177'); 
			}
			$info = array(
				'touser'=>$this->user_info['wx_openid'],
				'template_id'=>$this->wx_tmpl[$tmpl]['template_id'],
				'url'=>$wx_url,
				'topcolor'=>'#FF0000',
				'data'=>$data
			);
			$this->log('function:wx_content_code 输出：'.var_export($info,true));
			$this->msg_content = serialize($info);	
		}
	}
	
	/*
	 * 构造邮件
	 * email _3
	 * @content 发送内容
	 * @$dest_email 目标邮件
	 * @$email_title 邮件标题
	 * @$user_id 发送会员
	 * @$is_html 是否html
	 * @$param 发送参数
	 */
	public function caret_data($type,$param){
			if($type == 'wx'){
 	 				$msg_data['send_time'] = 0;
					$msg_data['is_send'] = 0;
					$msg_data['create_time'] = get_gmtime();
					$msg_data['content'] = addslashes($this->msg_content);;
	 				//$msg_data['is_html'] = 1;
					if($param){
						foreach($param as $k=>$v){
							$msg_data[$k] = $v;
						}
					}
					$this->data = $msg_data;
					
				
			}else{
				if(app_conf("MAIL_ON")==0&&$type=='email')
				{
					return false;	
				}
				if(app_conf("SMS_ON")==0&&$type=='sms')
				{
					return false;	
				}
				if($this->msg_type != 'text'){
	 				
	 				$msg_data['send_time'] = 0;
					$msg_data['is_send'] = 0;
					$msg_data['create_time'] = get_gmtime();
					$msg_data['content'] = addslashes($this->msg_content);;
	 				//$msg_data['is_html'] = 1;
					if($param){
						foreach($param as $k=>$v){
							$msg_data[$k] = $v;
						}
					}
					$this->data = $msg_data;
				}else{
 					$msg_data['send_type'] = 1;
	 				$msg_data['content'] = addslashes($this->msg_content);;
					$msg_data['send_time'] = 0; 
					$msg_data['is_send'] = 0;
					$msg_data['create_time'] = get_gmtime();
					
					$msg_data['is_html'] = 1;

	 				
	 				$this->data = $msg_data;
				}
			}
				
			
	}
	 
	/*
	 * msg_4 组装
	 */
	public function msg_reply($type,$tmlp='',$data){
		if($type=='email'||$type=='sms'){
			if(app_conf("MAIL_ON")!=1&&$type=='email'){
				return false;
			}elseif(app_conf("SMS_ON")!=1&&$type=='sms'){
				return false;
			}
			$this->msg_tmpl($tmlp);
			if($type=='email'){
				$this->email_content_code();
			}else{
				$this->sms_content_code();
			}
 			$this->caret_data($type,$data);
			$this->insert_deal_msg_list();
		}elseif($type=='wx'){
			 
			$this->wx_content_code($tmlp);
			$this->caret_data($type,$data);
			
 			$this->insert_weixin_msg_list();
		}
	}
	/*操作类型$control_type
	 * 操作内容$control_content
	 * 后台管理通知
	 */
	public function msg_admin_manage($type,$control_type,$control_content){
		if($type=='wx'){
			$this->log("msg_admin_manage");
			$user_info= array();
			$user_info['control_type'] = $control_type;
	  	 	$user_info['control_content']= $control_content;
	  	 	$user_info['wx_openid'] = $this->account['test_user'];
	  	 	$this->user_info = $user_info;
	  	 	 
	  	 	if($user_info['wx_openid']){
	  	 		$msg_data['title'] =  "后台-".$user_info['control_type'];
				$msg_data['dest'] = $user_info['wx_openid'];
				$msg_data['user_id'] = 0;
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 2;
				$this->msg_reply('wx','OPENTM206915380',$msg_data);
	  	 	}
	  	 	
		}
	}
	/*
	 * 会员提醒
	 */
	public function msg_member_remind($type,$dest,$control_type,$control_content,$control_url=''){
  		$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$dest);
		
		 
		$user_info['control_type']=$control_type;
		$user_info['control_content']= $control_content;
		$user_info['control_url']= $control_url;
		$this->user_info = $user_info;
		$this->log("function msg_member_remind :user_info");
		$this->log($this->user_info);
 		if($user_info['wx_openid']){
  	 		$msg_data['title'] =  "会员提醒-".$user_info['control_type'];
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = 0;
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM207029514',$msg_data);
  	 	}
	}
	/*
	 * 构造notify
	 */
	public function msg_notify($user_id,$content,$url_route,$url_param){
		$notify_user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($user_id));
		if($notify_user)
		{
			$notify['user_id'] = $user_id;
			$notify['log_info'] = $content;
			$notify['log_time'] = get_gmtime();
			$notify['url_route'] = $url_route;
			$notify['url_param'] = $url_param;
			$this->data = $notify;
			$this->insert_user_notify();
			$url = get_domain().parse_url_tag_wap("u:".$url_route."|".$url_param);
			$this->msg_member_remind('wx', $user_id, '消息通知', $content,$url);
 		}
	}
	
	
	/*
	/*
 	 * 发送付款通知邮件
 	 */
 	public function msg_paid($type,$dest,$money,$order_id){
 		$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$dest);
  	 	$user_info['notice_sn'] = $order_id ;
  	 	$user_info['deal_name'] = $GLOBALS['db']->getOne("select deal_name from ".DB_PREFIX."deal_order where id=".$order_id);
  	 	$user_info['paid_money']=number_price_format($money);
  	 	$this->user_info = $user_info;
 		if($type == 'email'&&$user_info){
 			//邮件通知
			if(!empty($user_info['email'])){
 				$msg_data['title'] = app_conf("SITE_NAME")."已付款通知";
				$msg_data['dest'] = $user_info['email'];
 				$msg_data['user_id'] = $user_info['user_id'];
 				$msg_data['is_html'] = 1;
 				$msg_data['send_type'] = 1;
				$this->msg_reply('email','TPL_MAIL_INVESTOR_PAID_STATUS',$msg_data);
 			}
 		}elseif($type == 'sms'&&$user_info){
 			if(!empty($user_info['mobile'])){
 				$msg_data['title'] =  "短信已付款通知";
 				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				$this->msg_reply('sms','TPL_SMS_INVESTOR_PAID_STATUS',$msg_data);
  			}
 		}
 		
  		if($this->is_wx&&$user_info['wx_openid']){
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "订单支付成功";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM201490080',$msg_data);
 		}
 		
 	}
	
	public  function  get_user_info($dest,$type = 'email'){
		if($GLOBALS['user_info']['id']){
			$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id='".$GLOBALS['user_info']['id']."'");
		}else{
			if($type=='email'){
				$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where email='".$dest."'");
			}elseif($type=='sms'){
				$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where mobile='".$dest."'");
			}
		}
		return $user_info;
	}
 	/*
 	 * 发送验证码
 	 */
 	 public function msg_verify($type,$dest,$code,$title=''){
 	 	$user_info = array();
  	 	if($type == 'email'){
 	 		if(!empty($dest)){

				$user_info = $this->get_user_info($dest,$type);

 	 			$user_info['send_code'] = $code;
 				$user_info['tmpl_email_name'] = 'user';
				$this->user_info = $user_info;
				
				$this->log("function msg_verify :user_info");
				$this->log($this->user_info);
				if($title){
					$msg_data['title'] = $title;
				}else{
					$msg_data['title'] = "邮件验证码";
				}
 	 			
				$msg_data['dest'] =$dest;
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = 1;
				$msg_data['send_type'] = 1;
				$msg_data['code'] = $code;
				$this->msg_reply('email','TPL_MAIL_USER_VERIFY',$msg_data);
 	 		}
  	 	}elseif($type == 'sms'){
 			if(!empty($dest)){
				$user_info = $this->get_user_info($dest,$type);
 				$user_info['mobile'] = $dest;
				$user_info['code'] = $code;
				$user_info['tmpl_sms_name'] = 'verify';
				$this->user_info = $user_info;
				
				$this->log("function msg_verify :user_info");
				$this->log($this->user_info);
				
  				if($title){
					$msg_data['title'] = $title;
				}else{
					$msg_data['title'] = "短信验证码";
				}
 				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $GLOBALS['user_info']['id'];
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				$msg_data['code'] =$code;
				$this->msg_reply('sms','TPL_SMS_VERIFY_CODE',$msg_data);
  			}
  	 	}
		
		if($this->is_wx&&$user_info['wx_openid']){
			$user_info['wx_code'] = $code;
		 	$user_info['expire_time'] = to_date(get_gmtime()+60*5);
			$this->user_info = $user_info;
			
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "验证码通知";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM203026900',$msg_data);
 		}
		
 	 }
	/*
 	 * 发送验证码
 	 */
	public function tzt_msg_verify($type,$dest,$code,$user_id){
		if($type == 'sms'){
			if(!empty($dest)){
				$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$user_id);
				$user_info['mobile'] = $dest;
				$user_info['code'] = $code;
				$user_info['tmpl_sms_name'] = 'verify';
				$this->user_info = $user_info;

				//$this->log("function msg_verify :user_info");
				//$this->log($this->user_info);

				$msg_data['title'] = "投资通短信验证码";
				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $user_id;
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				$msg_data['code'] =$code;
				$this->msg_reply('sms','TPL_SMS_TZT_VERIFY_CODE',$msg_data);
			}
		}
	}
 	/*
	 * 充值通知
	 */
	public function msg_incharge($type,$dest,$money){
 	 	$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$dest);
		if($this->is_wx&&$user_info['wx_openid']){
 		 	$user_info['incharge_money'] = $money;
			$this->user_info = $user_info;
			
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "充值通知";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM267386236',$msg_data);
 		}
		
	}
	/*
	 * 退款通知
	 */
	public function msg_refund($type,$dest,$money,$control_content=''){
		$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$dest);
		if($this->is_wx&&$user_info['wx_openid']){
 		 	$user_info['refund_money'] = $money;
			$user_info['control_content'] = $control_content;
			$this->user_info = $user_info;
			
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "退款通知";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','TM00004',$msg_data);
 		}
	}
	/*
	 * 提现通知
	 */
	public function msg_carray_money_notice($type,$money,$user_name){
  		if($this->is_wx&&$this->account['test_user']&&$user_name){
  		 	$user_info['carray_money'] = $money;
			$user_info['control_content'] = '会员'.$user_name.'余额提现申请已提交，请您及时登录后台处理!';
			$user_info['wx_openid'] = $this->account['test_user'];
			$this->user_info = $user_info;
			$this->log("function msg_carray_money_notice :user_info");
			$this->log($this->user_info);
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "提现申请通知";
			$msg_data['dest'] =$this->account['test_user'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','TM00979',$msg_data);
 		}
		 
		 
	}
	/*
	 * 提现结果通知
	 */
	public function msg_carray_money_result($type,$dest,$refund_data){
		$user_info = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$dest);
		if($this->is_wx&&$user_info['wx_openid']&&$refund_data){
 		 	$user_info['carray_money'] = $refund_data['money'];
			$user_info['carray_bank'] = get_carray_info($refund_data['user_bank_id']);
			$user_info['carray_create_time'] = to_date($refund_data['create_time']);
			$user_info['carray_end_time'] = to_date(get_gmtime());
			if($refund_data['is_pay']==1){
				$user_info['result'] = '申请通过,请等待收款!';
			}else{
				$user_info['result'] = '申请拒绝,如有疑问请咨询客服!';
			}
 			$this->user_info = $user_info;
			
 			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "提现审核结果通知";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM202425107',$msg_data);
 		}
	}
	/*
	 * 众筹状态通知
	 * status 0 表示创建和修改项目 1表示 提交审核  2表示审核通过  3表示审核失败
	 * 10表示项目进行中  11表示项目成功  12表示项目失败  13表示发放回报  
	 * 20表示股权进行中 21表示认投成功  22表示认投失败  23表示运行用户付款  24表示拒绝用户付款 25表示付款时间截止，项目结束
	 */
	public function msg_zc_status($type,$dest,$deal_id,$deal_status,$tmpl_name=''){
		if(is_array($deal_id)){
			$deal = $deal_id;
		}else{
			$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		}
		if(!$deal){
			return false;
		}
		$deal_name = $deal['name'];
		$status = $deal_status;
		$user = $GLOBALS['db']->getRow("select *,id as user_id from ".DB_PREFIX."user where id=".$deal['user_id']);
		$deal_user_name = $user['user_name'];
		if($dest=='admin'){
			$user_info=array();
			$user_info['wx_openid'] = $this->account['test_user'];
			$user_info['email'] = app_conf("REPLY_ADDRESS");
			if($status==self::CROW_EXAMINE){
				$user_info['control_content'] = "项目发起人 ".$deal_user_name." 提交了项目".$deal_name."，请及时登录后台审核!";
				$user_info['title'] = "众筹通知-审核众筹项目";
			}
			
		}else{
			$user_info = $user;
			if($status==self::CROW_CREATE){
				$user_info['control_content'] = '您已经创建或修改了'.$deal_name.'，可以申请提交审核！注意：审核中不可修改项目！';
				$user_info['title'] = "众筹通知-创建和修改项目";
			}elseif($status==self::CROW_EXAMINE_SUCCESS){
				$user_info['control_content'] = '恭喜您的项目'.$deal_name.'审核通过！';
				$user_info['title'] = "众筹通知-项目审核通过";
			}elseif($status==self::CROW_EXAMINE_FAIL){
				$user_info['control_content'] = '您的项目'.$deal_name.'审核未通过！原因：'.$deal['refuse_reason'];
				$user_info['title'] = "众筹通知-项目审核未通过";
			}elseif($status==self::CROW_PROJECT_SUCCESS){
				$user_info['control_content'] = '您参与的项目'.$deal_name.'众筹成功！';
				$user_info['title'] = "众筹通知-项目众筹成功";
			}elseif($status==self::CROW_PROJECT_FAIL){
				$user_info['control_content'] = '您参与的项目'.$deal_name.'众筹失败！';
				$user_info['title'] = "众筹通知-项目众筹失败";
			}elseif($status==self::CROW_INVEST_PAY_ALLOW){
				$user_info['control_content'] = '您参与的股权项目'.$deal_name.'众筹失败！';
				$user_info['title'] = "众筹通知-您的投资通过";
			}
		}
		if(!$user_info['title']){
			$user_info['title'] = "众筹通知";
		}
		$user_info['control_title'] = $deal_name;
		$this->user_info = $user_info;
		if($type == 'email'&&$user_info){
 			//邮件通知
			if(!empty($user_info['email'])){
 				$msg_data['title'] = $user_info['title'];
				$msg_data['dest'] = $user_info['email'];
 				$msg_data['user_id'] = $user_info['user_id'];
 				$msg_data['is_html'] = 1;
 				$msg_data['send_type'] = 1;
				if($tmpl_name){
					$this->msg_reply('email',$tmpl_name,$msg_data);
				}else{
					$this->msg_reply('email','TPL_MAIL_ZC_STATUS',$msg_data);
				}
 			}
 		}elseif($type == 'sms'&&$user_info){
 			if(!empty($user_info['mobile'])){
 				$msg_data['title'] =  $user_info['title'];
 				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				if($tmpl_name){
					$this->msg_reply('sms',$tmpl_name,$msg_data);
				}else{
					$this->msg_reply('sms','TPL_SMS_ZC_STATUS',$msg_data);
				}
   			}
 		}
		
		if($this->is_wx&&$user_info['wx_openid'] ){
			
			//调用OPENTM201490080 ,订单支付成功
 			$msg_data['title'] =  "众筹状态通知";
			$msg_data['dest'] = $user_info['wx_openid'];
			$msg_data['user_id'] = $user_info['user_id'];
			$msg_data['is_html'] = 0;
			$msg_data['send_type'] = 2;
			$this->msg_reply('wx','OPENTM202116678',$msg_data);
		}
	}
	/*
	 * 投资人资格审核
	 */
    public function msg_invest_status($type,$dest,$user_info){
    	
    	if($type == 'email'&&$user_info){
 			//邮件通知
			if(!empty($user_info['email'])){
				$user_info['is_investor_name']=get_investor($user_info['is_investor']);
				$user_info['investor_status_name']=get_investor_status($user_info['investor_status']);
				$user_info['zc_type']="修改申请";
				if($user_info['investor_status']==1){
					$user_info['verify_url'] = get_domain().url("settings#invest_info");
					$info="您的投资人申请已经通过";	
					$info_url="account#index";
				}else{
					$user_info['verify_url'] = get_domain().url("settings#invest_info");	
					$info="您的投资人申请未通过";
					if($user_info['investor_send_info']){
						$info.=",理由是".$user_info['investor_send_info'];
	 				} 
					$info.=",点击链接重新申请";
					$info_url="settings#security";
				}
				$this->user_info = $user_info;
				
				$msg_data['title'] = app_conf("SITE_NAME")."帐号-".$user_info['is_investor_name'].$user_info['investor_status_name'];
				$msg_data['dest'] = $user_info['email'];
 				$msg_data['user_id'] = $user_info['user_id'];
 				$msg_data['is_html'] = 1;
 				$msg_data['send_type'] = 1;
				$this->msg_reply('email','TPL_MAIL_INVESTOR_STATUS',$msg_data);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set investor_status_send=1 where id=".$user_info['id']);
				$this->msg_notify($dest,$info,$info_url,'');
 			}
 		}elseif($type == 'sms'&&$user_info&&app_conf("INVEST_STATUS_SEND_STATUS")==2){
 			if(!empty($user_info['mobile'])){
 				$user_info['is_investor_name']=get_investor($user_info['is_investor']);
				$user_info['investor_status_name']=get_investor_status($user_info['investor_status']);
 				if($user_info['investor_status']==1){
					$user_info['verify_url'] = get_domain().url("account#index");
					$info="您的投资人申请已经通过";	
					$info_url="account#index";
				}else{
					$user_info['verify_url'] = get_domain().url("investor#index");	
					$info="您的投资人申请未通过";
					if($user_info['investor_send_info']){
						$info.=",理由是".$user_info['investor_send_info'];
	 				}
					$info.=",点击链接重新申请";
					$info_url="settings#security";
				}
				$this->user_info = $user_info;
				
 				$msg_data['title'] =  "短信通知-".$user_info['is_investor_name'].$user_info['investor_status_name'];
 				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				$this->msg_reply('sms','TPL_SMS_INVESTOR_STATUS',$msg_data);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set investor_status_send=1 where id=".$user_info['id']);
  				$this->msg_notify($dest,$info,$info_url,'');
			}
 		}
    }
	public function msg_investor_go_pay($type,$invest_id,$order_id=''){
		$user_info=$GLOBALS['db']->getRow("select invest.id as invest_id,invest.user_id,invest.money,u.user_name,u.mobile,u.email,d.pay_end_time,d.name as deal_name from ".DB_PREFIX."investment_list as invest " .
				"left join ".DB_PREFIX."user as u on u.id=invest.user_id " .
				"left join ".DB_PREFIX."deal as d on d.id=invest.deal_id" .
				" where invest.id=$invest_id and invest.send_type=0 ");
	 	$user_info['money']=number_price_format($user_info['money']);
 	 	$user_info['pay_end_time']=to_date($user_info['pay_end_time'],"Y-m-d");
		if($type == 'email'&&$user_info){
 			//邮件通知
			if(!empty($user_info['email'])){
				
				if($order_id){
					$user_info['note_url']=get_domain().url("account#view_order",array('id'=>$order_id));
					$info_url = "account#view_order";
					$info_param = "id=".$order_id;
				}else{
					$user_info['note_url']=get_domain().url("account#index");
					$info_url = "account#index";
					$info_param = "";
				}
 				$this->user_info = $user_info;
 				$msg_data['title'] =app_conf("SITE_NAME")."付款通知-".$user_info['deal_name'];
				$msg_data['dest'] = $user_info['email'];
 				$msg_data['user_id'] = $user_info['user_id'];
 				$msg_data['is_html'] = 1;
 				$msg_data['send_type'] = 1;
				$this->msg_reply('email','TPL_MAIL_INVESTOR_PAY_STATUS',$msg_data);
				$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set send_type=1 where id=".$user_info['invest_id']);
 				$this->msg_notify($dest,$info,$info_url,$info_param);
 			}
 		}elseif($type == 'sms'&&$user_info&&app_conf("INVEST_STATUS_SEND_STATUS")==2){
 			if(!empty($user_info['mobile'])){
 				if($order_id){
 					$info_url = "account#view_order";
					$info_param = "id=".$order_id;
				}else{
 					$info_url = "account#index";
					$info_param = "";
				}
				$this->user_info = $user_info;
				
 				$msg_data['title'] =  "短信付款通知-".$user_info['deal_name'];
 				$msg_data['dest'] = $user_info['mobile'];
				$msg_data['user_id'] = $user_info['user_id'];
				$msg_data['is_html'] = 0;
				$msg_data['send_type'] = 0;
				$this->msg_reply('sms','TPL_SMS_INVESTOR_PAY_STATUS',$msg_data);
				$GLOBALS['db']->query("update ".DB_PREFIX."investment_list set send_type=1 where id=".$user_info['invest_id']);
   				$this->msg_notify($dest,$info,$info_url,$info_param);
			}
 		}
	}
	/*
	 * 测试邮件
	 */
	public function mail_demo($mail){
		$test_mail = $mail;
		require_once APP_ROOT_PATH."system/utils/es_mail.php";
		$mail = new mail_sender();
		$demo_html=file_get_contents(APP_ROOT_PATH."public/mail_html/demo.html");
		$info=array();
		$info['content']="测试邮件";
		$info['logo']=app_conf("SITE_LOGO");
		$info['site_name']=app_conf("SITE_NAME");
		$time=get_gmtime();
		$info['send_time']=to_date($time,'Y年m月n日');
		$GLOBALS['tmpl']->assign("info",$info);
		$msg = $GLOBALS['tmpl']->fetch("str:".$demo_html);
		$mail->AddAddress($test_mail);
		$mail->IsHTML(true); 				  // 设置邮件格式为 HTML
		$mail->Subject = l("DEMO_MAIL");   // 标题
		$mail->Body = $msg;  // 内容
		$mail->FromName = app_conf("SITE_NAME");
		$status =  $mail->Send();
		$result =array('status'=>1,'info'=>'');
		if(!$status){
			$result['status'] = 0;
			$result['info'] = $mail->ErrorInfo;
		}
		return $result;
 	}
 	/*
 	 * 测试短信
 	 */
 	public function sms_demo($tel){
 		$test_mobile = $tel;
		require_once APP_ROOT_PATH."system/utils/es_sms.php";
		$sms = new sms_sender();
		
		$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_VERIFY_CODE'");
		$tmpl_content = $tmpl['content'];
		$verify['mobile'] = $test_mobile;
		$verify['code'] = $code;
		$GLOBALS['tmpl']->assign("verify",$verify);
		$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
		
		$result = $sms->sendSms($test_mobile,$msg);
		return $result;
 	}
 	
 	
	/*
	 * 插入数据库deal_msg_list
	 */
	 public function insert_deal_msg_list(){
	 	if($this->data){
	 		$this->log("function insert_deal_msg_list :data");

			$data = $this->data;
			if(app_conf('IS_SMS_DIRECT')==1){
				if($data['send_type']==0){
					require_once APP_ROOT_PATH."system/utils/es_sms.php";
					$sms = new sms_sender();
					$result = $sms->sendSms($data['dest'],$data['content']);
					$data['is_success'] = intval($result['status']);
					$data['result'] = $result['msg'];

				}elseif($data['send_type']==1){
					require_once APP_ROOT_PATH."system/utils/es_mail.php";
					$mail = new mail_sender();

					$mail->AddAddress($data['dest']);
					$mail->IsHTML($data['is_html']); 				  // 设置邮件格式为 HTML
					$mail->Subject = $data['title'];   // 标题
					$mail->Body = $data['content'];  // 内容
					$is_success = $mail->Send();
					$result = $mail->ErrorInfo;
					$data['is_success'] = intval($is_success);
					$data['result'] = $result;

				}
				$data['is_send'] = 1;
				$data['send_time'] = get_gmtime();
				$this->data = $data;
			}

			$this->log($this->data);
	 		return $GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$this->data); //插入
	 	}
	 }
	 /*
	  * 插入 weixin_msg_list
	  */
	  public function insert_weixin_msg_list(){
	 	if($this->data){
	 		return $GLOBALS['db']->autoExecute(DB_PREFIX."weixin_msg_list",$this->data); //插入
	 	}
	 } 
	 /*
	  * 插入数据库user_notify
	  */
	 public function insert_user_notify(){
	 	if($this->data){
	 		return $GLOBALS['db']->autoExecute(DB_PREFIX."user_notify",$this->data); //插入
	 	}
	 }
	 /*
	  * 日志
	  */
	 public function log($log){
    	 
    		if ($this->debug && function_exists($this->logcallback)) {
    			  
    			if (is_array($log)) $log = var_export($log,true);
    			return call_user_func($this->logcallback,$log);
    		}
    }
	 /*
	  * 处理信息
	  * @$msg_type 处理类型
	  * @$dest 收件人
	  * @$param 其他信息
	  */
	 public function manage_msg($msg_type,$dest='',$param=array()){
	  
	 	$this->msg_type = $msg_type;
		
	 	$this->log('manage_msg 参数1'.$this->msg_type.",".$dest);
 	 	$this->log('manage_msg 参数2'.var_export($param,true));
		
	 	switch($this->msg_type){
	 		//会员取回密码邮件
	 		case self::MAIL_PASSWORD:
	 		
	 		break;
	 		//会员验证邮件
	 		case self::MAIL_VERIFY:
	 		$this->msg_verify('email', $dest, $param['code'],$param['title']);
	 		break;
	 		//邮箱修改模板
	 		case self::MAIL_CHANGE_VERIFY:
	 		
	 		break;
	 		//邮件通知用户通过投资人审核
	 		case self::MAIL_INVESTOR_STATUS:
	 		$this->log("MAIL_INVESTOR_STATUS");
			$this->msg_zc_status('email', $dest, $param['deal_id'],$param['deal_status'],self::MAIL_INVESTOR_STATUS);
	 		break;
	 		//邮件通知用户投资申请通过-允许付款
	 		case self::MAIL_INVESTOR_GO_PAY:
	 		
	 		break;
	 		//邮件通知用户已经付款
	 		case self::MAIL_INVESTOR_PAID:
	 		$this->msg_paid('email',$dest,$param['money'],$param['order_id']);
 	 		break;
	 		//测试邮件
	 		case self::MAIL_DEMO:
	 		$re =  $this->mail_demo($dest);
	 		return $re;
	 		break;
	 		//项目失败
	 		case self::SMS_DEAL_FAIL:
	 		
	 		break;
	 		//项目成功
	 		case self::SMS_DEAL_SUCCESS:
	 		
	 		break;
	 		//注册成功通知
	 		case self::SMS_REGISTER_SUCCESS:
	 		
	 		break;
	 		//通知项目发起人成功
	 		case self::SMS_DEAL_CREAT_SUCCESS:
	 		
	 		break;
	 		//通知项目发起人失败
	 		case self::SMS_DEAL_CREAT_FAIL:
	 		
	 		break;
	 		//短信验证码发送
	 		case self::SMS_VERIFY:
	 		$this->msg_verify('sms',$dest,$param['code'],$param['title']);
	 		break;

			//短信验证码发送
			case self::SMS_TZT_VERIFY:
			$this->tzt_msg_verify('sms',$dest,$param['code'],$param['user_id']);
			break;
	 		//短信通知用户通过投资人审核
	 		case self::SMS_INVESTOR_STATUS:
	 		
	 		break;
	 		//短信通知用户投资申请通过-允许付款
	 		case self::SMS_INVESTOR_GO_PAY:
	 		
	 		break;
	 		//短信通知用户已经付款
	 		case self::SMS_INVESTOR_PAID:
	 		$this->msg_paid('sms',$dest,$param['money'],$param['order_id']);
	 		break;
	 		//测试短信
	 		case self::SMS_DEMO:
	 		$re = $this->sms_demo($dest);
	 		return $re;
	 		break;
	 		//发送通知给用户
	 		case self::MSG_NOTIFY:
	 		$this->msg_notify($dest,$param['content'],$param['url_route'],$param['url_param']);
	 		break;
	 		
	 		case self::MSG_PAID:
	 		$status=app_conf("INVEST_PAID_SEND_STATUS");
			$this->log("INVEST_PAID_SEND_STATUS == ".$status);
	 		if($status>0){
	 			if($status==1){
	 				return $this->msg_paid('email',$dest,$param['money'],$param['order_id']);
	 			}elseif($status==2){
	 				return $this->msg_paid('sms',$dest,$param['money'],$param['order_id']);
	 			}
	 		}else{
	 				return $this->msg_paid('wx',$dest,$param['money'],$param['order_id']);
	 		}
	 		break;
	 		
	 		case self::MSG_ADMIN_MANAGE:
	 		$this->msg_admin_manage('wx',$param['type'],$param['content']);
	 		break;
	 	    
			//会员提醒
			case self::MSG_MEMBER_REMIDE:
 			$this->msg_member_remind('wx', $dest, $param['type'],$param['content']);
 			break;
			//
			case self::MSG_INCHARGE:
			$this->msg_incharge('wx', $dest, $param['monney']);
			break;
			//退款
			case self::MSG_REFUND:
			$this->msg_refund('wx', $dest, $param['monney'],$param['content']);
			break;
			//MSG_MONEY_CARRY 提现提醒管理员
			case self::MSG_MONEY_CARRY_NOTIFIE:
 				$this->msg_carray_money_notice('wx' ,$param['money'],$param['user_name']);
			break;
			case self::MSG_MONEY_CARRY_RESULT:
				$this->log("MSG_MONEY_CARRY_RESULT");
				$this->msg_carray_money_result('wx' , $dest, $param);
			break;
			case self::MSG_ZC_STATUS:
				$this->log("MSG_ZC_STATUS");
				$this->msg_zc_status('email', $dest, $param['deal_id'],$param['deal_status']);
			break;
			case self::MSG_INVEST_STATUS:
				$this->log("MSG_ZC_STATUS");
				if(app_conf("INVEST_STATUS_SEND_STATUS")==1){
					$this->msg_invest_status('email', $dest,  $param['user_info']);
				}elseif(app_conf("INVEST_STATUS_SEND_STATUS")==2){
					$this->msg_invest_status('sms', $dest, $param['user_info']);
				}
			break;
			case self::MSG_INVESTOR_GO_PAY:
				$this->log("MSG_INVESTOR_GO_PAY");
				if(app_conf("INVEST_PAY_SEND_STATUS")==1){
					$this->msg_invest_status('email', $param['invest_id'],$param['order_id']);
				}elseif(app_conf("INVEST_PAY_SEND_STATUS")==2){
					$this->msg_invest_status('sms', $param['invest_id'],$param['order_id']);
				}

			break;
 	 	}
	 }
}