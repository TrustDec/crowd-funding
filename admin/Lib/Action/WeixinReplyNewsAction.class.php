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
class WeixinReplyNewsAction extends WeixinAction{
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
	/**
	 * 删除
	 */
	public function delreply(){
		$ids_str = strim($_REQUEST['ids']);
		$id = intval($_REQUEST['id']);
		if($ids_str != ""){
			//批量删除
			$replys = M('WeixinReply')->where(array('id'=>array('in',explode(',',$ids_str))))->findAll();
			foreach($replys as $reply){
				M('WeixinReply')->where(array('id'=>$reply['id']))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}elseif($id > 0){
			//单条删除
			$reply = M('WeixinReply')->where(array('id'=>$id))->find();
			if($reply){
				M('WeixinReply')->where(array('id'=>$id))->delete();
 			}
			$this->success("删除成功",$this->isajax);
		}else{
			$this->error("请选择要删除的选项",$this->isajax);
		}
	}
	function word_check($keywords,$reply_id = 0,$match_type = 0){
		
		if($match_type == 0){
			$keywords = preg_split("/[ ,]/i",$keywords);
			$exists_keywords = array();
			foreach($keywords as $tag){
				$tag = trim($tag);
				if($tag != ''){
					$unicode_tag =  words::strToUnicode(trim($tag),'+');
					
					$condition =" account_id=".$this->account_id."  and id <> ".$reply_id." ";
					if($unicode_tag){
  						$condition .= " matach(keywords_match) AGAINST (".$unicode_tag.") ";
						//$where['keywords_match'] = array('match',$unicode_tag);
					}
  					$count = M('WeixinReply')->where($condition)->count();
  					if($count > 0){
						$exists_keywords[] = trim($tag);
						break;
					}
				}
			}
		}else{
			$keywords = trim($keywords);
			if($keywords != ''){
				
 				$count = M("WeixinReply")->where(array(
					'id'=>array('neq',$reply_id),
					'account_id'=>$this->account_id,
					'match_type'=>1,
					'keywords'=>$keywords,
				))->count();
				 
				if($count > 0){
					$exists_keywords[] = $keywords;
				}
			}
		}
    	return $exists_keywords;
    }
	//图文回复
	public function news()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$keywords = strim($_REQUEST['keywords']);
 		$condition =" account_id=".$this->account_id." and o_msg_type='news' and type=0   ";
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
		$this->assign("box_title","自定义图文回复信息");
		
 		$this->display();
	}
	/**
	 * 添加/编辑图文回复
	 */
	public function editnews(){
		$id = intval($_REQUEST['id']);
 		$condition=array('id'=>$id,'account_id'=>$this->account_id,'type'=>0,'o_msg_type'=>'news');
		$reply=M('WeixinReply')->where($condition)->find();
		if($reply){
			if($reply['o_msg_type'] == "text" && intval($_REQUEST['t']) == 0){
 				$this->redirect(u("WeixinReply/onfocus",array('t'=>1)));	
			}
			if($reply['relate_data'] != ""){
				foreach($this->seller_navs as $kk => $vv){
					if(isset($vv['navs'][$reply['relate_data']])){
						$jump_type = $kk;
					}
				}
			}
			//输出关联的回复
			$relate_replys = M('WeixinReplyRelate')->where(array('main_reply_id'=>$reply['id']))->order('sort ASC')->field('relate_reply_id')->findAll();
			foreach($relate_replys as $k=>$v){
				
				$relate_replys[$k] = M('WeixinReply') -> where(array('id'=>$v['relate_reply_id']))->find();
			}
			$this->assign("relate_replys",$relate_replys);
 		}
		 
		$this->assign("reply",$reply);
 		$this->assign("box_title","自定义图文回复");
		$this->display();
	}

	/**
	 * 保存图文回复
	 */
	public function save_news(){
		$id = intval($_POST['id']);
		$keywords = trim($_POST['keywords']);
		$reply_news_description  = trim($_POST['reply_news_description']);
		if($reply_news_description==""){
			$this->error("回复内容不能为空",$this->isajax);
		}
		$match_type = (int)$_POST['match_type'];
		//验证关键词的重复性
		$exists_keywords = $this->word_check($keywords,$id,$match_type);
		if(count($exists_keywords)>0){
			$err_content = "关键词：%s 已经存在相关回复";
			$keywords_str = implode(",", $exists_keywords);
			$keywords_str = sprintf($err_content,$keywords_str);
			$this->error($keywords_str,$this->isajax);
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
				
				//$reply_data['reply_news_content'] = strim(($_REQUEST['reply_news_content']));;
				$reply_data['o_msg_type'] = "news";
				$reply_data['keywords'] = $keywords;
				$reply_data['match_type'] = $match_type;
				
				$reply_data['keywords_match'] = '';
				$reply_data['keywords_match_row'] = '';
				
				//$reply_data['relate_data'] = $relate_data;
				//$reply_data['relate_id'] = $relate_id;
				$reply_data['relate_type'] = $relate_type;
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
				if($match_type == 0){
 					$this->syncMatch($id);
				}
				$this->success("保存成功",$this->isajax);
			}else{
				$this->error("非法提交",$this->isajax);
			}
		}else{
			//新增
			$reply_data= array();
			$reply_data['i_msg_type'] = "text";
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
			//$reply_data['reply_news_content'] = strim(replacePublic($_REQUEST['reply_news_content']));;
			$reply_data['type'] = 0; //默认回复
			$reply_data['keywords'] = $keywords;
			$reply_data['match_type'] = $match_type;
			//$reply_data['relate_data'] = $relate_data;
			//$reply_data['relate_id'] = $relate_id;
			$reply_data['relate_type'] = $relate_type;
			$reply_data['account_id'] = $this->account_id;
			$res = M('WeixinReply')->add($reply_data);
			if($res>0){
				if($match_type == 0){
 					$this->syncMatch($res);
				}
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