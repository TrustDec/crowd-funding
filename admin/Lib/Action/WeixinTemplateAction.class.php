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
class WeixinTemplateAction extends WeixinAction{
	public $industry;
	public $tmpl_list;
 	public function __construct(){
		parent::__construct();
//  		$this->industry=array(
// 			1=>'IT科技-互联网/电子商务',
// 			2=>'IT科技-IT软件与服务',
// 			3=>'IT科技-IT硬件与设备',
//  			4=>'IT科技-电子技术',
// 			5=>'IT科技-通信与运营商',
// 			6=>'IT科技-网络游戏',
//  			7=>'金融业-银行',
// 			8=>'金融业-基金|理财|信托',
// 			9=>'金融业-保险',
// 			10=>'餐饮-餐饮',
// 			11=>'酒店旅游-酒店',
// 			12=>'酒店旅游-旅游',
// 			13=>'运输与仓储-快递',
// 			14=>'运输与仓储-物流',
// 			15=>'运输与仓储-仓储',
// 			16=>'教育-培训',
// 			17=>'教育-院校',
// 			18=>'政府与公共事业-学术科研',
// 			19=>'政府与公共事业-交警',
// 			20=>'政府与公共事业-博物馆',
// 			21=>'政府与公共事业-公共事业|非盈利机构',
// 			22=>'医药护理-医药医疗',
// 			23=>'医药护理-护理美容',
// 			24=>'医药护理-保健与卫生',
// 			25=>'交通工具-汽车相关',
// 			26=>'交通工具-摩托车相关',
// 			27=>'交通工具-火车相关',
// 			28=>'交通工具-飞机相关',
// 			29=>'房地产-建筑',
// 			30=>'房地产-物业',
// 			31=>'消费品-消费品',
// 			32=>'商业服务-法律',
// 			33=>'商业服务-会展',
// 			34=>'商业服务-中介服务',
// 			35=>'商业服务-认证',
// 			36=>'商业服务-审计',
// 			37=>'文体娱乐-传媒',
// 			38=>'文体娱乐-体育',
// 			39=>'文体娱乐-娱乐休闲',
// 			40=>'印刷-印刷',
// 			41=>'其它-其它',
// 		);
		$this->industry=array(
			2=>'IT科技-IT软件与服务',
			1=>'IT科技-互联网/电子商务',
		);
 		$this->assign('industry',$this->industry);
 		$this->tmpl_list= require APP_ROOT_PATH."system/weixin_tmpl.php";
 		$this->assign('tmpl_list',$this->tmpl_list);
  	}

	public function save_industry(){
		$industry_1 = intval($_POST['industry_1']);
		$industry_2 = intval($_POST['industry_2']);
		$test_user = strim($_POST['test_user']);
		$id=intval($_POST['id']);
		if(!$test_user){
			$this->error('请输入测试帐号',$this->isajax);
		}
		if(!$industry_1){
			$this->error('请选择行业1',$this->isajax);
		}
		if(!$industry_2){
			$this->error('请选择行业2',$this->isajax);
		}
		if($industry_2==$industry_1){
			$this->error('行业1不能和行业2一样',$this->isajax);
		}
		$data=array(
			'industry_1'=>$industry_1,
			'industry_2'=>$industry_2,
			'test_user'=>$test_user,
		);
		if($this->account['industry_1']!=$industry_1&&$this->account['industry_1']>0){
			$data['industry_1_status']=0;
		}
		if($this->account['industry_2']!=$industry_2&&$this->account['industry_2']>0){
			$data['industry_2_status']=0;
		}
 		$re=$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_account",$data,'update',"  id=".$this->account_id);
		 
		$this->success("保存成功",$this->isajax);
	}
	public function syn_industry_to_weixin(){
		//开始获取微信的token
		$industry_1 = intval($this->account['industry_1']);
		$industry_2 = intval($this->account['industry_2']);
		if(!$industry_1){
			$this->error('请选择行业1',$this->isajax);
		}
		if(!$industry_2){
			$this->error('请选择行业2',$this->isajax);
		}
		if($industry_2==$industry_1){
			$this->error('行业1不能和行业2一样',$this->isajax);
		}
		$weixin_app_id = $this->account['authorizer_appid'];
		$weixin_app_key = $this->account['authorizer_access_token'];
		if($weixin_app_id=="" || $weixin_app_key==""){
			//$this->showFrmErr("请先设置授权",1,"",JKU("nav/auth"));
			$this->error("请先设置授权",$this->isajax);
		}
		$platform= new PlatformWechat($this->option);
  	 	$platform_authorizer_token=$platform->check_platform_authorizer_token();
  		if($platform_authorizer_token){
   				$result=$platform->setTMIndustry($industry_1,$industry_2);
 				if($result){
 					if(!isset($result['errcode']) || intval($result['errcode'])==0){
 						//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id))->setField('status',1);
						$data=array('industry_1_status'=>1,'industry_2_status'=>1);
						$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_account",$data,'UPDATE',' id='.$this->account['id']);
						$this->success("同步成功",$this->isajax);
					}else{
						if($result['errcode']==43100){
						  $this->error("同步频率太高,一个月只可修改一次",$this->isajax);
						}else{
 						  $this->error("同步出错，错误代码".$result['errcode'].":".$result['errmsg'],$this->isajax);
						}
					}
				}else{
					$this->error("通讯出错，请重试",1);
				}
			}else{
			$this->error("通讯出错，请重试",1);
		}
	}
	public function index()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$keywords = strim($_REQUEST['keywords']);
		if($keywords){
			$this->assign("keywords",$keywords);
			$unicode_tag = words::strToUnicode($keywords);
 			$condition = " and MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) ";
 		}
 		$modules = $this->tmpl_list;
		$tmpl=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_tmpl where account_id=".$this->account_id.$condition);
		foreach($this->tmpl_list as $k=>$v){
			foreach($tmpl as $k1=>$v1){
				if($k==$v1['template_id_short']){
					$modules[$k]['id'] = $v1['id'] ;
					$modules[$k]['template_id'] = $v1['template_id'] ;
					$modules[$k]['installed'] = 1 ;
					break;
				}
 			}
			if($modules[$k]['installed']!=1){
				$modules[$k]['installed'] = 0;
			}
			$modules[$k]['template_id_short'] = $k;
			$modules[$k]['name'] = $v['name'];
		}
  		$this->assign('tmpl',$modules);
		$this->assign('box_title','模板列表');
  		$this->display();
	}
	public function edit_tmpl(){
		$id = $_REQUEST['id'];
		$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_tmpl where id=".$id);
		if($tmpl['msg']){
			$tmpl['msg'] = unserialize($tmpl['msg']);
 			$this->assign('remark',$tmpl['msg']['remark']['value']);
			unset($tmpl['msg']['first']);
			unset($tmpl['msg']['remark']);
 		}
 		 
  		if(!$tmpl){
			$this->error('没有该模版');
		}
//		if(!$tmpl['template_id']){
//			$platform= new PlatformWechat($this->option);
//			$platform->check_platform_authorizer_token();
//			$result=$platform->addTemplateMessage($tmpl['template_id_short']);
//			if($result){
//				if(isset($result['errcode'])&&$result['errcode']>0){
//					$this->error("获取template_id失败".$result['errcode'].":".$result['errmsg']);
//				}else{
//					$tmpl['template_id'] = $result;
//					$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_tmpl",array("template_id"=>$result),'UPDATE','id='.$id);
//				}
//			}else{
//				$this->error("通讯失败");
//			}
//		}
 		$this->assign('tmpl',$tmpl);
		
		$this->assign('box_title','编辑模板');
		$this->display();
	}
	public function install_tmpl(){
		$name = strim($_REQUEST['name']);
		$template_id_short = strim($_REQUEST['template_id_short']);
		
		$row = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_tmpl where template_id_short='".$template_id_short."' and account_id=".$this->account_id);
		if($row){
			$this->error($name."已经安装!");
		}else{
			$data = array('first'=>$name,'remark'=>array('value'=>$this->tmpl_list[$template_id_short]['remark'],'color'=>'#173177'));
			$msg = serialize($data);
			$re=$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_tmpl",array('name'=>$name,'template_id_short'=>$template_id_short,'account_id'=>$this->account_id,'msg'=>$msg));
			if($re){
				$id = $GLOBALS['db']->insert_id();
				$platform= new PlatformWechat($this->option);
				$platform->check_platform_authorizer_token();
				$result=$platform->addTemplateMessage($template_id_short);
 				if($result){
					if(isset($result['errcode'])&&$result['errcode']!=0&&intval($result['errmsg'])!=0&&!$result){
						$GLOBALS['db']->query("delete from ".DB_PREFIX."weixin_tmpl where id=".$id);
						$this->error("获取template_id失败".$result['errcode'].":".$result['errmsg']);
 					}else{
						$tmpl['template_id'] = $result;
						$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_tmpl",array("template_id"=>$result),'UPDATE','id='.$id);
					
					}
				}else{
					$GLOBALS['db']->query("delete from ".DB_PREFIX."weixin_tmpl where id=".$id);
					$this->error("通讯失败");
				}
				$this->success("安装成功!");
			}else{
				$this->error("安装失败!");
			}
		}
	}
	public function save_tmpl(){
		$name = $_REQUEST['name'] ;
		$remark = $_REQUEST['remark'] ;
		$key_node = $_REQUEST['key_node'];
		 
		$data=array(
			'first'=>array('value'=>$name,'color'=>'#173177'),
 			'remark'=>array('value'=>$remark,'color'=>'#173177'),
		);
		$num=1;
		foreach($key_node as $k=>$v){
			if($v){
				$data['keyword'.$num] =  array('value'=>$v,'color'=>'#173177');
				$num++;
			}
		}
		
		$tmpl=array();
		$tmpl['name']  = $name;
		$tmpl['template_id_short']  = strim($_REQUEST['template_id_short']);
		$tmpl['template_id']  = strim($_REQUEST['template_id']);
		$tmpl['account_id']  = strim($_REQUEST['account_id']);
		$tmpl['msg']  = serialize($data);
		$id= $_REQUEST['id'];
		if($id>0){
			//更新
			$re=$GLOBALS['db']->autoExecute(DB_PREFIX.'weixin_tmpl',$tmpl,'UPDATE',' id='.$id);	
			if($re){
				$this->success('更新成功',$this->isajax);
			}else{
				$this->error('更新失败',$this->isajax);
			}
		}
	}
	public function test_tmpl(){
		$id = $_REQUEST['id'];
		$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_tmpl where id=".$id);
		if(!$this->account['test_user']){
			$this->success('请先设置测试微信号',$this->isajax);
		}
 		if($tmpl['template_id']){
			$platform= new PlatformWechat($this->option);
			$platform->check_platform_authorizer_token();
			$data=unserialize($tmpl['msg']);
			if($tmpl['template_id_short']=='TM00738'){
				$data['infoid']=array('value'=>'infoid','color'=>'#173177');
				$data['tittle']=array('value'=>'tittle','color'=>'#173177'); 
				$data['reason']=array('value'=>'reason','color'=>'#173177'); 
			}elseif($tmpl['template_id_short']=='TM00979'){
				$data['first'] = array('value'=>'理财通余额提现申请已提交，资金预计XX月XX日24:00前到账，请注意查收。','color'=>'#173177');
				$data['money']=array('value'=>'money','color'=>'#173177');
				$data['timet']=array('value'=>'timet','color'=>'#173177');
			}
			else{
				$data['keyword1']=array('value'=>'keyword1','color'=>'#173177');
				$data['keyword2']=array('value'=>'keyword2','color'=>'#173177'); 
				$data['keyword3']=array('value'=>'keyword3','color'=>'#173177'); 
			}
			$info=array(
				'touser'=>$this->account['test_user'],
				'template_id'=>$tmpl['template_id'],
				'url'=>get_domain().url_wap("index"),
				'topcolor'=>'#FF0000',
				'data'=>$data
			);
			$result=$platform->sendTemplateMessage($info);
			if($result){
				if(isset($result['errcode'])&&$result['errcode']>0){
					$this->error("发送失败".$result['errcode'].":".$result['errmsg'],$this->isajax);
				}else{
					$this->success('发送成功',$this->isajax);
					
 				}
			}else{
				$this->error("通讯失败");
			}
		}
	}
	/**
	 * 删除
	 */
	public function deltmpl(){
		$ids_str = strim($_REQUEST['ids']);
		$id = intval($_REQUEST['id']);
		if($ids_str != ""){
			//批量删除
			$replys = M('WeixinTemplate')->where(array('id'=>array('in',explode(',',$ids_str))))->findAll();
			foreach($replys as $reply){
				M('WeixinTemplate')->where(array('id'=>$reply['id']))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}elseif($id > 0){
			//单条删除
			$reply = M('WeixinTemplate')->where(array('id'=>$id))->find();
			if($reply){
				M('WeixinTemplate')->where(array('id'=>$id))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}else{
			$this->error("请选择要删除的选项",$this->isajax);
		}
	}

	public function show_content()
	{
		$id = intval($_REQUEST['id']);
		header("Content-Type:text/html; charset=utf-8");
		echo htmlspecialchars(M("WeixinMsgList")->where("id=".$id)->getField("content"));
	}
	
	public function send()
	{
		$id = intval($_REQUEST['id']);
		$msg_item = M("WeixinMsgList")->getById($id);
		if($msg_item)
		{
			if($msg_item['send_type']==2)
			{
				$platform= new PlatformWechat($this->option);
				$platform->check_platform_authorizer_token();
				
				$data=unserialize($msg_item['content']);
				$result=$platform->sendTemplateMessage($data);
				if($result){
					if(isset($result['errcode'])&&$result['errcode']>0){
						header("Content-Type:text/html; charset=utf-8");
 						echo l("SEND_NOW").l("FAILED").$result['errcode'].":".$result['errmsg'];
					}else{
						header("Content-Type:text/html; charset=utf-8");
					    echo l("SEND_NOW").l("SUCCESS");
						
	 				}
				}else{
					header("Content-Type:text/html; charset=utf-8");
					echo l("SEND_NOW").l("FAILED").'通讯失败';
				}
				 
			}
			
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo l("SEND_NOW").l("FAILED");
		}
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("WeixinMsgList")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("WeixinMsgList")->where ( $condition )->delete();	
			
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