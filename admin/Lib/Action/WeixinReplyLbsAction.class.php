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
class WeixinReplyLbsAction extends WeixinAction{
	public $account;
	public $account_id;
 	public function __construct(){
		parent::__construct();
		$account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where type=1 ");
		$this->account=$account;
		$this->account_id=intval($account['id'])?intval($account['id']):0;
 		$this->assign("account",$account);
 		$this->assign("max_size",get_max_file_size());
 		$this->assign("max_size_byte",get_max_file_size_byte());
 	}
	
	//LBS回复
	public function lbs()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
 		$keywords = strim($_REQUEST['keywords']);
		
		$condition =" account_id=".$this->account_id." and o_msg_type='news' and i_msg_type = 'location' and type=0   ";
		if($keywords){
			$this->assign("keywords",$keywords);
			$unicode_tag = words::strToUnicode($keywords);
			$condition .= " and MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) ";
			//$where['keywords_match'] = array('match',$unicode_tag);
		}
		 
		$list = array();
		$count = M('WeixinReply')->where($condition)->count();
		
		$pager = buildPage(MODULE_NAME.'/'.ACTION_NAME,$_REQUEST,$count,$this->page);
		if($count > 0){
			$list =  M('WeixinReply')->where($condition)->order('id desc')->limit($pager['limit'])->findAll();
		}
		$this->assign("list",$list);
		$this->assign('pager',$pager);
		$this->assign("box_title","LBS回复信息");
 		$this->display();
	}
	
 	/**
	 * 添加/编辑lbs回复
	 */
	public function editlbs(){
		$id = intval($_REQUEST['id']);
 		$condition=array('id'=>$id,'account_id'=>$this->account_id,'type'=>0,'o_msg_type'=>'news','i_msg_type'=>'location');
		$reply=M('WeixinReply')->where($condition)->find();
 		//var_dump($reply['x_point']);var_dump($reply['y_point']);exit;
 		if($reply){
			if($reply['o_msg_type'] == "text" && intval($_REQUEST['t']) == 0){
 				$this->redirect(u("WeixinReply/onfocus"));	
			}
			 
			//输出关联的回复
			$relate_replys = M('WeixinReplyRelate')->where(array('main_reply_id'=>$reply['id']))->order('sort ASC')->field('relate_reply_id')->findAll();
			foreach($relate_replys as $k=>$v){
				$relate_replys[$k] = M('WeixinReply') -> where(array('id'=>$v['relate_reply_id']))->find();
			}
			$this->assign("relate_replys",$relate_replys);
			
			$this->assign("qq_x_point_val",$reply['x_point']);
			$this->assign("qq_y_point_val",$reply['y_point']);
			
		}
		if($reply['x_point']=="" || $reply['y_point']==""){
			//定位城市IP
			$iplocation = new CIpLocation();
			$address  =$iplocation->getAddress(CLIENT_IP);
			$this->assign("city_name",$address['area1']);
		}
		

 		$this->assign("reply",$reply);
 		$this->assign("box_title","自定义LBS图文回复");
		$this->display();
	}


	/**
	 * 保存lbs图文回复
	 */
	public function save_lbs(){
		$id = intval($_POST['id']);
		$x_point = trim($_POST['x_point']);
		$y_point = trim($_POST['y_point']);
		$address = trim($_POST['address']);
		$api_address = trim($_POST['api_address']);
		$scale_meter = intval($_POST['scale_meter']);

		if($x_point=="" || $y_point==""){
			$this->error("请选定位经纬度",$this->isajax,"");
		}
		if($address==""){
			$this->error("地址不能为空",$this->isajax,"address");
		}
		if($scale_meter<1000){
			$this->error("范围不能小于1000米",$this->isajax,"scale_meter");
		}

		$reply_news_description  = trim($_POST['reply_news_description']);
		if($reply_news_description==""){
			$this->error("回复内容不能为空",$this->isajax,"reply_news_description");
		}
		$reply_news_title = trim($_POST['reply_news_title']);
		if($reply_news_title==""){
			$this->error("回复标题不能为空",$this->isajax,"reply_news_title");
		}
		$reply_news_picurl = (trim($_POST['reply_news_picurl']));
		if($reply_news_picurl==""){
			$this->error("回复图片不能为空",$this->isajax,"reply_news_picurl");
		}
		//定义链接
		$relate_type  =1; //默认为0
		if($_REQUEST['u_module']==''&&$_REQUEST['reply_news_url']==''){
			$this->error("回复跳转链接不能为空",$this->isajax );
			
		}
		if($id>0){
			//更新
 			$reply_data = M('WeixinReply')->where(array('id'=>$id,'account_id'=>$this->account_id,'o_msg_type'=>'news'))->find();
			if($reply_data){
				$reply_data['reply_news_title'] = $reply_news_title;
				$reply_data['reply_news_description'] = $reply_news_description;
				$reply_data['reply_news_picurl'] = $reply_news_picurl;
				$reply_data['reply_news_url'] = trim($_REQUEST['reply_news_url']);
				if($_REQUEST['u_module']!='')
				{
					$reply_data['reply_news_url'] = '';
				}
				if($reply_data['reply_news_url']!='')
				{
					$reply_data['u_module'] = '';
					$reply_data['u_action'] = '';
					$reply_data['u_id'] = '';
					$reply_data['u_param'] = '';
				}else{
					$reply_data['u_id'] = intval($_REQUEST['u_id']);
					$reply_data['u_module'] = trim($_REQUEST['u_module']);
					$reply_data['u_action'] = trim($_REQUEST['u_action']);
					$reply_data['u_param'] = trim($_REQUEST['u_param']);
				}
				$reply_data['o_msg_type'] = "news";
				//$reply_data['relate_data'] = $relate_data;
				//$reply_data['relate_id'] = $relate_id;
				$reply_data['relate_type'] = $relate_type;

				$reply_data['x_point'] = $x_point;
				$reply_data['y_point'] = $y_point;
				$reply_data['address'] = $address;
				$reply_data['api_address'] = $api_address;
				$reply_data['scale_meter'] = $scale_meter;
				$reply_data['account_id'] = $this->account_id;
 				M('WeixinReply')->save($reply_data,array('id'=>$id,'account_id'=>$this->account_id));
				M('WeixinReplyRelate')->where(array('main_reply_id'=>$id))->delete();
				$total = 0;
				if($_POST['relate_reply_id']){
					foreach ($_POST['relate_reply_id'] as $k=>$vv){
						if(intval($vv) > 0 && $total < 9){
							$total++;
							$link_data = array();
							$link_data['main_reply_id'] = $id;
							$link_data['relate_reply_id'] = $vv;
							$link_data['sort'] = $k;
							M('WeixinReplyRelate')->add($link_data);	
						}
					}
				}
				//JKS('WeixinReply')->syncMatch($id);
				$this->success("保存成功",$this->isajax);
			}else{
				$this->error("非法操作",$this->isajax);
			}
		}else{
			//新增
			$reply_data= array();
			$reply_data['i_msg_type'] = "location";
			$reply_data['o_msg_type'] = "news";
			$reply_data['reply_news_title'] = $reply_news_title;
			$reply_data['reply_news_description'] = $reply_news_description;
			$reply_data['reply_news_picurl'] = $reply_news_picurl;
			$reply_data['reply_news_url'] = trim($_REQUEST['reply_news_url']);
				if($_REQUEST['u_module']!='')
				{
					$reply_data['reply_news_url'] = '';
				}
				if($reply_data['reply_news_url']!='')
				{
					$reply_data['u_module'] = '';
					$reply_data['u_action'] = '';
					$reply_data['u_id'] = '';
					$reply_data['u_param'] = '';
				}else{
					$reply_data['u_id'] = intval($_REQUEST['u_id']);
					$reply_data['u_module'] = trim($_REQUEST['u_module']);
					$reply_data['u_action'] = trim($_REQUEST['u_action']);
					$reply_data['u_param'] = trim($_REQUEST['u_param']);
				}
			$reply_data['type'] = 0; //默认回复
			//$reply_data['relate_data'] = $relate_data;
			//$reply_data['relate_id'] = $relate_id;
			$reply_data['relate_type'] = $relate_type;
			$reply_data['account_id'] = $this->account_id;
			$reply_data['x_point'] = $x_point;
			$reply_data['y_point'] = $y_point;
			$reply_data['address'] = $address;
			$reply_data['api_address'] = $api_address;
			$reply_data['scale_meter'] = $scale_meter;

			$res = M('WeixinReply')->add($reply_data);
			if($res>0){
				$total = 0;
				if($_POST['relate_reply_id']){
					foreach ($_POST['relate_reply_id'] as $k=>$vv){
						if(intval($vv) > 0 && $total < 9){
							$total++;
							$link_data = array();
							$link_data['main_reply_id'] = $res;
							$link_data['relate_reply_id'] = $vv;
							$link_data['sort'] = $k;
 							M('WeixinReplyRelate')->add($link_data);	
						}
					}
				}
				$this->success("保存成功",$this->isajax);
			}else{
				if($res == -1){
					$this->error("图文回复限额已满",$this->isajax);
				}else{
					$this->error("系统出错，请重试",$this->isajax);
				}
			}
		}
	}
	
	public function syncMatch($reply_id){
		$reply_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_reply where id=".$reply_id);  
		
		if($reply_data){
			$reply_data['keywords_match'] = "";
			$reply_data['keywords_match_row'] = "";
 			$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_reply",$reply_data,'UPDATE',' id ='.$reply_id);
			//检索标签
			$keywords = $reply_data['keywords'];
			$keywords = preg_split("/[ ,]/i",$keywords);
			
			foreach($keywords as $tag){
				if(trim($tag) != ''){
					$this->insertMatch(trim($tag),DB_PREFIX."weixin_reply",$reply_id,'keywords_match');
				}
			}
		}
	}
	public function insertMatch($tag,$table,$id,$field){
		if($tag === ''){
			return;
		}
		$unicode_tag = words::strToUnicode($tag,'+');
		
		if(empty($unicode_tag)){
			return;
		}
		$result = $GLOBALS['db']->getOne("select count(*) from ".$table." where id=".$id." and MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) ");  
		
		if($result == 0){
			$match_row = $GLOBALS['db']->getRow("select * from ".$table." where id=".$id);  
 			if($match_row[$field] == ""){
				$match_row[$field] = $unicode_tag;
				$match_row[$field."_row"] = $tag;
			}else{
				$match_row[$field] = $match_row[$field]." ".$unicode_tag;
				$match_row[$field."_row"] = $match_row[$field."_row"]." ".$tag;
			}
			 
			
			$GLOBALS['db']->autoExecute($table,$match_row,'UPDATE',' id ='.$id);
 		}
	}
	
 }
?>