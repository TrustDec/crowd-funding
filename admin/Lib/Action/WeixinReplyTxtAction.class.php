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
class WeixinReplyTxtAction extends WeixinAction{
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
	//文本回复
	public function txt()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$keywords = strim($_REQUEST['keywords']);
		
		$condition =" account_id=".$this->account_id." and o_msg_type='text' and type=0   ";
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
		
		$this->assign("box_title","自定义文本回复信息");
 		$this->display();
	}
	/**
	 * 编辑文本回复
	 */
	public function edittext(){
		$id = intval($_REQUEST['id']);
		$reply = M('WeixinReply')->where(array('id'=>$id,'account_id'=>$this->account_id,'o_msg_type'=>'text'))->find();
		if($reply){
			$faces = $this->faces;
            $face_keys = array();
            $face_values = array();
             foreach($faces as $fkey => $fval){
                $face_keys[] = $fkey;
                $face_values[] = '<img src="'.get_domain().APP_ROOT.'/public/weixin/static/images/face/'.$fval.'" border="0" alt="'.$fkey.'">';
            }
			$reply['reply_content'] = nl2br(str_replace($face_keys,$face_values,htmlspecialchars_decode($reply['reply_content'])));
			$this->assign("reply",$reply);
		}
		$this->assign("box_title","自定义文本回复");
		$this->display();
	}

	/**
	 * 新增/修改文本回复
	 */
	public function save_text(){
		$id = intval($_POST['id']);
		$reply_content  = trim($_REQUEST['reply_content']);
		$keywords = trim($_POST['keywords']);
		if($reply_content==""){
			$this->error("回复内容不能为空",$this->isajax);
		}
		$match_type = (int)$_POST['match_type'];
		//验证关键词的重复性
		 
		$exists_keywords =$this->word_check($keywords,$id,$match_type);  
		
		if(count($exists_keywords)>0){
			$err_content = "关键词：%s 已经存在相关回复";
			$keywords_str = implode(",", $exists_keywords);
			$keywords_str = sprintf($err_content,$keywords_str);
			$this->error($keywords_str,$this->isajax);
		}
		preg_match_all('/(<a.*?>.*?<\/a>)/',$reply_content,$links);
		$search_array = array();
		$replace_array = array();
		foreach($links[1] as $link){
			$replace_key = md5($link);
			$search_array[] = $replace_key;
			$replace_array[] = $link;
			$reply_content = str_replace($link,$replace_key,$reply_content);
		}
        $reply_content = preg_replace('/&amp;/',"&",$reply_content);
        $reply_content = preg_replace('/<img src=".*?"( border="0")? alt="(.*?)"( \/)?>/',' $2',$reply_content);
        $reply_content = preg_replace('/<div>(.*?)<\/div>/',"\n$1 ",$reply_content);
        $reply_content = trim(strip_tags($reply_content));
        $reply_content = str_replace($search_array,$replace_array,$reply_content);
		$reply_content = strim($reply_content);
		if($id > 0){
			//更新
			$reply_data  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_reply where id=".$id." and o_msg_type = 'text' and account_id = ".$this->account_id);
 			if($reply_data){
				$reply_data['match_type'] = $match_type;
				$reply_data['reply_content'] = $reply_content;
				$reply_data['keywords'] = $keywords;
				$reply_data['keywords_match'] = '';
				$reply_data['keywords_match_row'] = '';
				$reply_data['account_id'] = $this->account_id;
				
 				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_reply",$reply_data,'UPDATE'," id=".$id."  and account_id = ".$this->account_id);
				if($match_type == 0){
 					$this->syncMatch($id);
				}
				$this->success("保存成功",$this->isajax);
			}else{
				$this->error("非法操作",$this->isajax);
			}
		}else{
			//新增
			$reply_data= array();
			$reply_data['i_msg_type'] = "text";
			$reply_data['o_msg_type'] = "text";
			$reply_data['reply_content'] = $reply_content;
			$reply_data['keywords'] = $keywords;
			$reply_data['match_type'] = $match_type;
			$reply_data['type'] = 0;
			$reply_data['account_id'] = $this->account_id;
			$res = M('WeixinReply')->add($reply_data);
			if($res>0){
				if($match_type == 0){
 					$this->syncMatch($GLOBALS['db']->insert_id());
				}
				$this->success("保存成功",$this->isajax);
			}else{
				if($res == -1){
					$this->error("文本回复限额已满",$this->isajax);
				}else{
					$this->error("系统出错，请重试",$this->isajax);
				}
			}
		}
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