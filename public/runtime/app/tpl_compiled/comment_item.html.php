<div class="comment_item" <?php if ($this->_var['comment_item']['status'] == 0): ?>style="display:none;"<?php endif; ?>>
	<div class="comment_user_avatar"><?php 
$k = array (
  'name' => 'show_avatar',
  'p' => $this->_var['comment_item']['user_id'],
  't' => 'small',
);
echo $k['name']($k['p'],$k['t']);
?></div>
	<div class="comment_content">
		<a href="<?php
echo parse_url_tag("u:home|"."id=".$this->_var['comment_item']['user_id']."".""); 
?>" class="linkgreen"><?php echo $this->_var['comment_item']['user_name']; ?>:</a>&nbsp;<?php 
$k = array (
  'name' => 'nl2br',
  'v' => $this->_var['comment_item']['content'],
);
echo $k['name']($k['v']);
?> &nbsp;&nbsp;<span class="pass_time"><?php 
$k = array (
  'name' => 'pass_date',
  'v' => $this->_var['comment_item']['create_time'],
);
echo $k['name']($k['v']);
?></span>
		<div class="blank1"></div>
		<div class="comment_op">
			<?php if ($this->_var['comment_item']['user_id'] == $this->_var['user_info']['id']): ?>
			<a href="<?php
echo parse_url_tag("u:deal#delcomment|"."id=".$this->_var['comment_item']['id']."".""); 
?>" class="linkgreen delcomment">删除</a>
			<?php endif; ?>
			<a href="javascript:void(0);" class="linkgreen replycomment" rel="<?php echo $this->_var['comment_item']['id']; ?>">回复</a>			
		</div>
		<div class="blank10"></div>
		<div class="reply_box" id="reply_box_<?php echo $this->_var['comment_item']['id']; ?>">
			<div class="blank"></div>
			<form name="comment_<?php echo $this->_var['log_item']['id']; ?>_form" rel="<?php echo $this->_var['comment_item']['log_id']; ?>" class="comment_form" action="<?php
echo parse_url_tag("u:deal#save_comment|"."log_id=".$this->_var['comment_item']['log_id']."&deal_id=".$this->_var['comment_item']['deal_id']."&pid=".$this->_var['comment_item']['id']."".""); 
?>">		
			<div class="reply_content">
				<textarea name="content">回复 <?php echo $this->_var['comment_item']['user_name']; ?>:</textarea>
				<input type="hidden" name="ajax" value="1" />
				<input type="hidden" name="comment_pid" value="<?php echo $this->_var['comment_item']['id']; ?>" />
			</div>
			<div class="blank10"></div>
			<span class="syn_weibo">
				<label style="cursor:pointer">
					<input type="checkbox" name="syn_weibo" value="1" />
					<span>同时发布至我的微博 </span>
				</label>
			</span>	
			<div>			
			<div class="ui-button theme_bgcolor send_btn" rel="green">
					<div>
						<span>发送</span>
					</div>
			</div>	
			</div>
			<div class="blank10"></div>
			
			</form>
		</div><!--end reply_box-->
	</div>
	
	<div class="blank5"></div>
</div>