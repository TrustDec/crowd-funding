<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class WeixinAction extends CommonAction{
	 public $navs;
	 public $faces;
	 public $page = 1;
	 public $isajax = 0;
	 public $account;
	 public $account_id;
	 public $option;
	 public function __construct(){
	 	parent::__construct();
	 	$this->navs = array(
			'index' => array(
				'name'	=>	'首页',  //首页
			),			
			'deals' => array(
				'name'	=>	'项目列表',
				'acts'	=> array(
					'index'	=>	'列表',
				),
			),
			'investor' => array(  
				'name'	=>	'天使投资人',
				'acts'	=> array(
					'invester_list'	=>	'列表',
				),
			),
			'deal' => array(  
				'name'	=>	'项目详情',
				'acts'	=> array(
					'show'	=>	'详情',
					'update'	=>	'动态',
					'support'	=>	'支持',
					'comment'	=>	'评论',
				),
			),
			'news' => array(  
				'name'	=>	'动态',
				'acts'	=> array(
					'index'	=>	'最新',
					'fav'	=>	'关注',
				),
			),
			'article_cate' => array(  
				'name'	=>	'文章列表',
			),
			'article' => array(  
				'name'	=>	'文章内容',
				'acts'	=> array(
					'index'	=>	'详情',
				),
			),
			
			'faq' => array(  
				'name'	=>	'新手帮助',
			),
		);
		$this->assign('navs',$this->navs);
		$this->faces = array(
			"/::)"=>"0.gif","/::~"=>"1.gif","/::B"=>"2.gif","/::|"=>"3.gif","/:8-)"=>"4.gif",
			"/::<"=>"5.gif",'/::$'=>"6.gif",
			"/::X"=>"7.gif","/::Z"=>"8.gif","/::'("=>"9.gif",
			"/::-|"=>"10.gif","/::@"=>"11.gif","/::P"=>"12.gif","/::D"=>"13.gif","/::O"=>"14.gif",
			"/::("=>"15.gif","/::+"=>"16.gif","/:–b"=>"17.gif","/::Q"=>"18.gif","/::T"=>"19.gif","/:,@P"=>"20.gif","/:,@-D"=>"21.gif","/::d"=>"22.gif","/:,@o"=>"23.gif","/::g"=>"24.gif","/:|-)"=>"25.gif","/::!"=>"26.gif","/::L"=>"27.gif","/::>"=>"28.gif","/::,@"=>"29.gif","/:,@f"=>"30.gif","/::-S"=>"31.gif","/:?"=>"32.gif","/:,@x"=>"33.gif","/:,@@"=>"34.gif","/::8"=>"35.gif","/:,@!"=>"36.gif","/:!!!"=>"37.gif","/:xx"=>"38.gif","/:bye"=>"39.gif","/:wipe"=>"40.gif","/:dig"=>"41.gif","/:handclap"=>"42.gif","/:&-("=>"43.gif","/:B-)"=>"44.gif","/:<@"=>"45.gif","/:@>"=>"46.gif","/::-O"=>"47.gif","/:>-|"=>"48.gif","/:P-("=>"49.gif","/::'|"=>"50.gif","/:X-)"=>"51.gif","/::*"=>"52.gif","/:@x"=>"53.gif","/:8*"=>"54.gif","/:pd"=>"55.gif","/:<W>"=>"56.gif","/:beer"=>"57.gif",
			"/:basketb"=>"58.gif","/:oo"=>"59.gif","/:coffee"=>"60.gif","/:eat"=>"61.gif","/:pig"=>"62.gif","/:rose"=>"63.gif","/:fade"=>"64.gif","/:showlove"=>"65.gif","/:heart"=>"66.gif","/:break"=>"67.gif","/:cake"=>"68.gif","/:li"=>"69.gif","/:bome"=>"70.gif","/:kn"=>"71.gif","/:footb"=>"72.gif","/:ladybug"=>"73.gif","/:shit"=>"74.gif","/:moon"=>"75.gif","/:sun"=>"76.gif","/:gift"=>"77.gif","/:hug"=>"78.gif","/:strong"=>"79.gif","/:weak"=>"80.gif","/:share"=>"81.gif","/:v"=>"82.gif","/:@)"=>"83.gif","/:jj"=>"84.gif","/:@@"=>"85.gif","/:bad"=>"86.gif","/:lvu"=>"87.gif","/:no"=>"88.gif","/:ok"=>"89.gif","/:love"=>"90.gif","/:<L>"=>"91.gif","/:jump"=>"92.gif","/:shake"=>"93.gif","/:<O>"=>"94.gif","/:circle"=>"95.gif","/:kotow"=>"96.gif","/:turn"=>"97.gif","/:skip"=>"98.gif","[挥手]"=>"99.gif","/:#-0"=>"100.gif","[街舞]"=>"101.gif",
			"/:kiss"=>"102.gif","/:<&"=>"103.gif","/:&>"=>"104.gif"
		);
		$weixin_conf = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_conf");
	 	foreach($weixin_conf as $k=>$v){
			$weixin_conf[$v['name']]=$v['value'];
		}
		if(!$weixin_conf){
			$this->redirect(u("WeixinConf/index"));	
		}
		$account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where type=1 ");
		if(!$account){
			//$this->redirect(u("WeixinInfo/index"));	
		}
		$this->account=$account;
		$this->account_id=intval($account['id'])?intval($account['id']):0;
 		$this->assign("account",$account);
 		 
		
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
 			
 			'authorizer_access_token'=>$this->account['authorizer_access_token'], 
 			'authorizer_access_token_expire'=>$this->account['expires_in'], 
 			'authorizer_appid'=>$this->account['authorizer_appid'], 
 			'authorizer_refresh_token'=>$this->account['authorizer_refresh_token'], 
 			
 			'logcallback'=>'log_result',
 			'debug'=>true,
 		);
		if(isset($_REQUEST['page'])){
			$this->page = max(1,(int)$_REQUEST['page']);
		}
		if(isset($_REQUEST['isajax'])){
			$this->isajax = (int)$_REQUEST['isajax'] > 0 ? 1 : 0;
		}
		if(isset($_REQUEST['page'])){
			$this->page = max(1,(int)$_REQUEST['page']);
		}
		$this->assign('pager_num_now',$this->page);
	 }
	 
	 public function load_module()
	{
		$id = intval($_REQUEST['id']);
		$module = trim($_REQUEST['module']);
		$act = M("WeixinNav")->where("id=".$id)->getField("u_action");
		$this->ajaxReturn($this->navs[$module]['acts'],$act);
	}
	//显示成功-框架内用
	public function showFrmSuccess($msg,$ajax=0,$field="",$jump="")
	{
		
		if($ajax==1)
		{
			$result['status'] = 1;
			$result['info'] = $msg;
			$result['field'] = $field;
			$result['jump'] = $jump;
			header("Content-Type:text/html; charset=utf-8");
	        echo(json_encode($result));exit;
		}
		else
		{		
			$this->tooltip('操作提示',$msg,'ok',$jump);
		}
	}
	//显示错误-框架内用
	public function showFrmErr($msg,$ajax=0,$field="",$jump="")
	{
		if($ajax==1)
		{
			$result['status'] = 0;
			$result['msg'] = $msg;
			$result['field'] = $field;
			$result['jump'] = $jump;
			header("Content-Type:text/html; charset=utf-8");
	        echo(json_encode($result));exit;
		}
		else
		{		
			$this->tooltip('操作提示',$msg,'error',$jump);
		}
	}
 }
?>