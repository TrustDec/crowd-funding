<?php $_from = $this->_var['deal_hot_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'deal_hot_pro_items');$this->_foreach['deal_item_hot_pro'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['deal_item_hot_pro']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['deal_hot_pro_items']):
        $this->_foreach['deal_item_hot_pro']['iteration']++;
?>	
<div class="nav_item nav_item2<?php if ($this->_foreach['deal_item_hot_pro']['iteration'] % 4 == 1): ?> first<?php endif; ?>">	
	<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['deal_hot_pro_items']['id']."".""); 
?>" target="_blank">
		<div class="project_image">
			<img src="<?php if ($this->_var['deal_hot_pro_items']['image'] == ''): ?><?php echo $this->_var['TMPL']; ?>/images/empty_thumb.gif<?php else: ?><?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['deal_hot_pro_items']['image'],
  'w' => '300',
  'h' => '210',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?><?php endif; ?>" alt="<?php echo $this->_var['deal_hot_pro_items']['name']; ?>" lazy="true" />
			<?php if ($this->_var['deal_hot_pro_items']['type'] == 0): ?>
				<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
				<span class="project_step project_begin">预热中</span>
				<?php elseif ($this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0): ?>
				<span <?php if ($this->_var['deal_hot_pro_items']['is_success'] == 1): ?>class="project_step project_success"<?php else: ?>class="project_step project_fail"<?php endif; ?>><?php if ($this->_var['deal_hot_pro_items']['is_success'] == 1): ?>已成功<?php else: ?>筹资失败<?php endif; ?></span> 	 
				<?php else: ?>
					<?php if ($this->_var['deal_hot_pro_items']['percent'] >= 100): ?>
						<span class="project_step project_success">已成功</span>
					<?php else: ?>
						<span class="project_step project_sprite">
							<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
							长期项目
							<?php else: ?>
								<?php if ($this->_var['deal_hot_pro_items']['type'] == 1): ?>
								融资中
								<?php else: ?>
								筹资中
								<?php endif; ?>
		 					<?php endif; ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<div class="blank0"></div>
		</div>
		<div class="project_text">
			<span class="project_title"><?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['deal_hot_pro_items']['name'],
  'b' => '0',
  'e' => '25',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?></span>
			<div class="project_intro">
				<span class="f_l">目标：<em class="f_red"><i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_hot_pro_items']['limit_price'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></em></span>
				<span class="f_r">
					<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
					<span>预热中</span>
					<?php elseif ($this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0): ?>
					<span <?php if ($this->_var['deal_item']['is_success'] == 1): ?>class="f_red"<?php else: ?>class="f_gray"<?php endif; ?>><?php if ($this->_var['deal_hot_pro_items']['is_success'] == 1): ?>已成功<?php else: ?>筹资失败<?php endif; ?></span> 	 
					<?php else: ?>
						<?php if ($this->_var['deal_hot_pro_items']['percent'] >= 100): ?>
							<span>已成功</span>
						<?php else: ?>
							<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
							<span class="btn_sprite">长期项目</span>
							<?php else: ?>
								<?php if ($this->_var['deal_hot_pro_items']['type'] == 1): ?>
								融资中
								<?php else: ?>
								<span class="btn_sprite">立即参与</span>
								<?php endif; ?>
		 					<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</span>
			</div>
			<div class="schedule_bar">
				<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
				<div class="ui-progress">
					<span class="bg_orange" style="width:<?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%;"></span>
				</div>
				<?php elseif ($this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0): ?>
					<?php if ($this->_var['deal_hot_pro_items']['is_success'] == 1): ?>				
					<div class="ui-progress">
						<span class="bg_green" style="width:100%;"></span>
					</div>
					<?php else: ?>
					<div class="ui-progress">
						<span class="bg_gray" style="width:<?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%;"></span>
					</div>
					<?php endif; ?>
				<?php else: ?>
					<?php if ($this->_var['deal_hot_pro_items']['percent'] >= 100): ?>
						<div class="ui-progress">
							<span class="bg_green" style="width:100%;"></span>
						</div>
					<?php else: ?>
						<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
						<div class="ui-progress">
							<span class="bg_orange" style="width:<?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%;"></span>
						</div>
						<?php else: ?>
						<div class="ui-progress">
							<span class="bg_orange" style="width:<?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%;"></span>
						</div>
						<?php endif; ?>	
					<?php endif; ?>
				<?php endif; ?>
				<div class="blank0"></div>
			</div>
		 	<div class="project_schedule">
				<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
				<div class="div3" style="text-align:left;width:100%;text-align:center;border:0">
					<div class="f_999">离项目开始还有</div>
					<div class="left_time" data="<?php echo $this->_var['deal_hot_pro_items']['left_begin_day']; ?>">
						<em class="s day">--</em>
						<em class="l">天</em>
						<em class="s hour">--</em>
						<em class="l">时</em>
						<em class="s min">--</em>
						<em class="l">分</em>
						<em class="s sec">--</em>
						<em class="l">秒</em>
					</div>
				</div>
				<div class="left_time_hide hide">
					<div class="div3" style="text-align:left;">
						<span class="num"><?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%</span>
						<div class="blank10"></div>
						<span class="til">达成率</span>
					</div>
					<div class="div3 div3_middle">
						<span class="num"><font><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_hot_pro_items']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></font>元</span>
						<div class="blank10"></div>
			 			<?php if ($this->_var['deal_hot_pro_items']['type'] == 1): ?>
						<span class="til">已认投</span>
						<?php else: ?>
						<span class="til">已筹金额</span>
						<?php endif; ?>
					</div>
					<div class="div3 div3_last" style="text-align:right;">
						<?php echo $this->_var['deal_hot_pro_items']['left_begin_days']; ?>
						<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
						<span class="num"><font><?php echo $this->_var['deal_hot_pro_items']['left_begin_days']; ?></font>天</span>
						<?php elseif ($this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0): ?>
						<span class="num"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_hot_pro_items']['end_time'],
  'f' => 'y/m/d',
);
echo $k['name']($k['v'],$k['f']);
?></span>
						<?php else: ?>
						<span class="num">
							<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
							长期项目
							<?php else: ?>
							<font><?php echo $this->_var['deal_hot_pro_items']['remain_days']; ?></font>天
							<?php endif; ?>
						</span>
						<?php endif; ?>	
						<div class="blank10"></div>
						<span class="til">
							<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
								已经预热
							<?php elseif (( $this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0 )): ?>
								结束时间
							<?php else: ?>
								<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
									长期项目
								<?php else: ?>
									剩余时间
								<?php endif; ?>
							<?php endif; ?>
						</span>
					</div>
					<div class="blank10"></div>
					<div class="follow">
						<em class="tl">支持：<?php echo $this->_var['deal_hot_pro_items']['support_count']; ?></em>
						<em class="tr">关注：<?php echo $this->_var['deal_hot_pro_items']['focus_count']; ?></em>
					</div>
					<div class="blank0"></div>
				</div>
				<?php else: ?>
				<div class="div3" style="text-align:left;">
					<span class="num"><?php echo $this->_var['deal_hot_pro_items']['percent']; ?>%</span>
					<div class="blank10"></div>
					<span class="til">达成率</span>
				</div>
				<div class="div3 div3_middle">
					<span class="num"><font><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_hot_pro_items']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></font>元</span>
					<div class="blank10"></div>
		 			<?php if ($this->_var['deal_hot_pro_items']['type'] == 1): ?>
					<span class="til">已认投</span>
					<?php else: ?>
					<span class="til">已筹金额</span>
					<?php endif; ?>
				</div>
				<div class="div3 div3_last" style="text-align:right;">
					<?php echo $this->_var['deal_hot_pro_items']['left_begin_days']; ?>
					<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
					<span class="num"><font><?php echo $this->_var['deal_hot_pro_items']['left_begin_days']; ?></font>天</span>
					<?php elseif ($this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0): ?>
					<span class="num"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_hot_pro_items']['end_time'],
  'f' => 'y/m/d',
);
echo $k['name']($k['v'],$k['f']);
?></span>
					<?php else: ?>
					<span class="num">
						<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
						长期项目
						<?php else: ?>
						<font><?php echo $this->_var['deal_hot_pro_items']['remain_days']; ?></font>天
						<?php endif; ?>
					</span>
					<?php endif; ?>	
					<div class="blank10"></div>
					<span class="til">
						<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
							已经预热
						<?php elseif (( $this->_var['deal_hot_pro_items']['end_time'] < $this->_var['now'] && $this->_var['deal_hot_pro_items']['end_time'] != 0 )): ?>
							结束时间
						<?php else: ?>
							<?php if ($this->_var['deal_hot_pro_items']['end_time'] == 0): ?>
								长期项目
							<?php else: ?>
								剩余时间
							<?php endif; ?>
						<?php endif; ?>
					</span>				
				</div>
				<?php endif; ?>
				<?php if ($this->_var['deal_hot_pro_items']['begin_time'] <= $this->_var['now']): ?>
				<div class="blank10"></div>
				<div class="follow">
					<em class="tl">支持：<?php echo $this->_var['deal_hot_pro_items']['support_count']; ?></em>
					<em class="tr">关注：<?php echo $this->_var['deal_hot_pro_items']['focus_count']; ?></em>
				</div>
				<div class="blank0"></div>
				<?php endif; ?>
			</div>
			<?php if ($this->_var['deal_hot_pro_items']['begin_time'] > $this->_var['now']): ?>
			<div class="follow left_time_follow">
				<div class="blank10"></div>
				<em class="rush tc" style="width:100%">抢先浏览</em>
			</div>
			<?php endif; ?>
		</div>
	</a>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<script type="text/javascript">
	if ($.browser.msie && $.browser.version <= 7){
		$(".nav_item").bind('click',function(){
			var $obj=$(this).find("a");
			var p_url=$obj.attr("href");
			window.open(p_url);
		});
	}
</script>