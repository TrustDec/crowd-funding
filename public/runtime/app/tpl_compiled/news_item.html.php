<style>
	.deal_news_info_row {overflow:hidden;}
	.div3 {padding-top:0;}
	.div3 span.num {font-size:14px; line-height:14px;}
</style>
<?php $_from = $this->_var['log_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'log_item');if (count($_from)):
    foreach ($_from AS $this->_var['log_item']):
?>
<div class="deal_update news_item"  id="post_<?php echo $this->_var['log_item']['id']; ?>">
	<div class="news_deal_info">
		<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['log_item']['deal_info']['id']."".""); 
?>" target="_blank" title="<?php echo $this->_var['log_item']['deal_info']['name']; ?>"><img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['log_item']['deal_info']['image'],
  'w' => '180',
  'h' => '130',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" /></a>
		<div class="blank5"></div>
		<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['log_item']['deal_info']['id']."".""); 
?>" target="_blank" title="<?php echo $this->_var['log_item']['deal_info']['name']; ?>" class="linkgreen" style="line-height:18px">
			<?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['log_item']['deal_info']['name'],
  'b' => '0',
  'e' => '25',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?>
		</a>
		<div class="blank10"></div>
		
		<div class="deal_bar_bg">
			<?php if ($this->_var['log_item']['type'] == 0): ?>
				<div class="deal_bar_front" style="width:<?php echo $this->_var['log_item']['deal_info']['percent']; ?>%;"></div>
			<?php else: ?>
				<div class="deal_bar_front" style="width:<?php echo $this->_var['log_item']['percent']; ?>%;"></div>
			<?php endif; ?>
		</div>	
		<div class="blank5"></div>
		<div class="deal_news_info_row">
			<?php if ($this->_var['log_item']['type'] == 0): ?>
				<div class="div3" style="text-align:left">
					<span class="num"><?php echo $this->_var['log_item']['deal_info']['percent']; ?>%</span>
					<div class="blank10"></div>
					<span class="til">达到</span>
				</div>
				<div class="div3 tc">
					<span class="num">¥<?php 
$k = array (
  'name' => 'number_price_format',
  'v' => $this->_var['log_item']['deal_info']['support_amount'],
);
echo $k['name']($k['v']);
?></span>
					<div class="blank10"></div>
					<span class="til">支持</span>
				</div>
				<div class="div3" style="text-align:right">
					<span class="num">
					<?php if ($this->_var['log_item']['deal_info']['begin_time'] > $this->_var['now']): ?>
					未上线
					<?php elseif ($this->_var['log_item']['deal_info']['end_time'] < $this->_var['now'] && $this->_var['log_item']['deal_info']['end_time'] != 0): ?>
					已过期
					<?php else: ?>
					<?php if ($this->_var['log_item']['deal_info']['end_time'] == 0): ?>
					长期项目
					<?php else: ?>
					<font><?php echo $this->_var['log_item']['deal_info']['remain_days']; ?></font>天
					<?php endif; ?>
					<?php endif; ?>
					</span>
					<div class="blank10"></div>
					<span class="til">剩余</span>
				</div>
			<?php else: ?>
				<div class="div3" style="text-align:left">
					<span class="num"><?php echo $this->_var['log_item']['percent']; ?>%</span>
					<div class="blank10"></div>
					<span class="til">达到</span>
				</div>
				<div class="div3 tc">
					<span class="num">¥<?php 
$k = array (
  'name' => 'number_price_format',
  'v' => $this->_var['log_item']['invote_money'],
);
echo $k['name']($k['v']);
?></span>
					<div class="blank10"></div>
					<span class="til">已认投</span>
				</div>
				<div class="div3" style="text-align:right">
					<span class="num">
					<?php if ($this->_var['log_item']['deal_info']['begin_time'] > $this->_var['now']): ?>
					未上线
					<?php elseif ($this->_var['log_item']['deal_info']['end_time'] < $this->_var['now'] && $this->_var['log_item']['deal_info']['end_time'] != 0): ?>
					已过期
					<?php else: ?>
					<?php if ($this->_var['log_item']['deal_info']['end_time'] == 0): ?>
					长期项目
					<?php else: ?>
					<font><?php echo $this->_var['log_item']['deal_info']['remain_days']; ?></font>天
					<?php endif; ?>
					<?php endif; ?>
					</span>
					<div class="blank10"></div>
					<span class="til">剩余</span>
				</div>
			<?php endif; ?>
		</div>				
	</div><!--end news_deal_info-->
	<div class="news_deal_update">
		<a href="<?php
echo parse_url_tag("u:deal#updatedetail|"."id=".$this->_var['log_item']['id']."".""); 
?>" class="news_update_title"><?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['log_item']['log_info'],
  'b' => '0',
  'e' => '30',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?></a>
		<div class="blank5"></div>
		<div class="author">
			<a class="f_l linkgreen" href="<?php
echo parse_url_tag("u:home|"."id=".$this->_var['log_item']['user_id']."".""); 
?>" style="font-size:14px;" ><?php echo $this->_var['log_item']['user_name']; ?></a>
			<span onclick="send_message(<?php echo $this->_var['log_item']['user_id']; ?>);" class="send_message f_l" style="height:20px;"></span>	
		</div>
		<div class="passdate">
			<?php echo $this->_var['log_item']['pass_time']; ?>
		</div>
		<div class="blank5"></div>
		<div class="dash"></div>
		<div class="log_info">
			<?php 
$k = array (
  'name' => 'nl2br',
  'v' => $this->_var['log_item']['log_info'],
);
echo $k['name']($k['v']);
?>
			<div class="blank"></div>
			<?php if ($this->_var['log_item']['source_vedio'] != ''): ?>
			<div class="blank"></div>
			<embed wmode="opaque"wmode="opaque"src="<?php echo $this->_var['log_item']['source_vedio']; ?>" allowFullScreen="true" quality="high" width="520" height="400" align="middle" allowScriptAccess="always"></embed>				
			<?php endif; ?>
			
			<?php if ($this->_var['log_item']['image'] != ''): ?>
			<div class="blank"></div>
			<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['log_item']['image'],
  'w' => '520',
  'h' => '400',
);
echo $k['name']($k['v'],$k['w'],$k['h']);
?>" />
			<?php endif; ?>
		</div>
		<!--comment-->
		<div class="blank"></div>
		<div class="comment_tip_row">
			<?php if ($this->_var['log_item']['comment_count'] > 0): ?>
			<a href="javascript:void(0);" class="swap_comment" id="comment_<?php echo $this->_var['log_item']['id']; ?>_tip">评论(<?php echo $this->_var['log_item']['comment_count']; ?>)</a>
			<?php else: ?>
			<a href="javascript:void(0);" class="swap_comment" id="comment_<?php echo $this->_var['log_item']['id']; ?>_tip">发表评论</a>
			<?php endif; ?>
		</div>
		
		<div id="post_<?php echo $this->_var['log_item']['id']; ?>_comment" <?php if ($this->_var['log_item']['comment_count'] == 0): ?>style="display:none;"<?php endif; ?>>
		
			<div class="timeline-comment">
				<div class="timeline-comment-top"></div>
				<?php if ($this->_var['user_info']): ?>
				<form name="comment_<?php echo $this->_var['log_item']['id']; ?>_form"  rel="<?php echo $this->_var['log_item']['id']; ?>" class="comment_form" action="<?php
echo parse_url_tag("u:deal#save_comment|"."log_id=".$this->_var['log_item']['id']."&deal_id=".$this->_var['log_item']['deal_info']['id']."".""); 
?>">		
				<div class="comment-content">
					<textarea name="content">发表评论</textarea>
					<input type="hidden" name="ajax" value="1" />
				</div>
				<div class="comment-btn">
					<span class="syn_weibo">
					<label style="cursor:pointer">
						<input type="checkbox" name="syn_weibo" value="1" />
						<span>同时发布至我的微博 </span>
					</label>
					</span>				
					<div class="ui-button theme_bgcolor send_btn" rel="green">
							<div>
								<span>发送</span>
							</div>
					</div>	
					<div class="blank10"></div>
				</div>
				</form>
				<?php else: ?>
				<div class="comment-content" style="font-size:12px;">请登录后评论，立即 <a href="<?php
echo parse_url_tag("u:user#login|"."".""); 
?>" class="theme_fcolor">登录</a> 或 <a href="<?php
echo parse_url_tag("u:user#register|"."".""); 
?>"  class="theme_fcolor">注册</a></div>
				<?php endif; ?>
	
				<div class="deal_comment_list" id="deal_comment_list_<?php echo $this->_var['log_item']['id']; ?>">
					<?php if ($this->_var['log_item']['comment_list']): ?>
					<?php $_from = $this->_var['log_item']['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment_item');if (count($_from)):
    foreach ($_from AS $this->_var['comment_item']):
?>
					<?php echo $this->fetch('inc/comment_item.html'); ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php endif; ?>
				</div>
				<?php if ($this->_var['log_item']['more_comment']): ?>
				<div class="timeline-comment-more ui-button-ajax-more">
				<p><a href="<?php
echo parse_url_tag("u:deal#updatedetail|"."id=".$this->_var['log_item']['id']."".""); 
?>" style="color:#12adff">更多评论</a></p>
				<span><a class="fodeup_comment" href="javascript:void(0);" rel="<?php echo $this->_var['log_item']['id']; ?>" style="color:#12adff">收起</a></span>
				</div>
				<?php endif; ?>
				
			</div>
			
			
		</div>
		
		<!--end comment-->
	</div>
	<div class="blank"></div>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>