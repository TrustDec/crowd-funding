<?php $_from = $this->_var['deal_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'deal_item');$this->_foreach['deal_items'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['deal_items']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['deal_item']):
        $this->_foreach['deal_items']['iteration']++;
?>
<?php if ($this->_var['deal_item']['type'] == 1): ?>
<div class="nav_items">
	<div class="invest_status">
		<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
		<i class="invest_status_icon soon">预热中</i>
		<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
			<?php if ($this->_var['deal_item']['is_success'] == 1): ?>
			<i class="invest_status_icon success">已成功</i>
			<?php else: ?>
			<i class="invest_status_icon fail">筹资失败</i>
			<?php endif; ?>
		<?php else: ?>
			<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
			<i class="invest_status_icon success">已成功</i>
			<?php else: ?>
				<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
					<i class="invest_status_icon long_term">长期项目</i>
				<?php else: ?>
					<?php if ($this->_var['deal_item']['type'] == 1): ?>
					<i class="invest_status_icon in_progress">融资中</i>
					<?php else: ?>
					<i class="invest_status_icon in_progress">筹资中</i>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>
	<div class="nav_item <?php if ($this->_foreach['deal_items']['iteration'] % 4 == 1): ?>first<?php endif; ?> <?php if ($this->_var['deal_item']['type'] == 1): ?>nav_item1<?php else: ?>nav_item2<?php endif; ?>">
		<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['deal_item']['id']."".""); 
?>" target="_blank" style="display:block;overflow:hidden">
			<div class="project_image <?php if ($this->_var['deal_item']['type'] != 1): ?>project_image1<?php endif; ?>">
				<img src="<?php if ($this->_var['deal_item']['image'] == ''): ?><?php echo $this->_var['TMPL']; ?>/images/empty_thumb.gif<?php else: ?><?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['deal_item']['image'],
  'w' => '300',
  'h' => '210',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?><?php endif; ?>" alt="<?php echo $this->_var['deal_item']['name']; ?>" lazy="true" />
				<?php if ($this->_var['deal_item']['type'] == 1): ?>
				<div class="mask">
					<div class="mask_text">
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
						<div class="mask_invest_status">
							<span class="soon">项目预热中</span>
						</div>
						<div class="mb5">认投开始时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['begin_time'],
  'f' => 'Y年m月d日H时i分',
);
echo $k['name']($k['v'],$k['f']);
?></div>
						<div class="f_red">可约谈创业者</div>
					<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
						<?php if ($this->_var['deal_item']['is_success'] == 1): ?>
						<div class="mask_invest_status">
							<span class="soon">认投成功</span>
						</div>
						<div class="mb5">已认投金额：<i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></div>
						<div class="f_red">完成时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['success_time'],
  'f' => 'Y年m月d日H时i分',
);
echo $k['name']($k['v'],$k['f']);
?></div>
						<?php else: ?>
						<div class="mask_invest_status">
							<span class="soon">认投失败</span>
						</div>
						<div class="f_red">项目未结束，可继续认投</div>
						<?php endif; ?>
					<?php else: ?>
						<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
						<div class="mask_invest_status">
							<span class="soon">认投成功</span>
						</div>
						<div class="mb5">已认投金额：<i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></div>
						<div class="f_red">项目未结束，可继续认投</div>
						<?php else: ?>
							<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
							<div class="mask_invest_status">
								<span class="soon">长期项目</span>
							</div>
							<div class="mb5">认投金额：<i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></div>
							<div class="f_red">预计完成时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['end_time'],
  'f' => 'Y年m月d日H时i分',
);
echo $k['name']($k['v'],$k['f']);
?></div>
							<?php else: ?>
								<?php if ($this->_var['deal_item']['type'] == 1): ?>
								<div class="mask_invest_status">
									<span class="soon">项目进行中</span>
								</div>
								<div class="mb5">认投金额：<i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></div>
								<div class="f_red">预计完成时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['end_time'],
  'f' => 'Y年m月d日H时i分',
);
echo $k['name']($k['v'],$k['f']);
?></div>
								<?php else: ?>
								<span>筹资中</span>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
					</div>
					<div class="mask_bg"></div>
				</div>
				<?php endif; ?>
				<?php if ($this->_var['deal_item']['type'] == 0): ?>
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
					<span class="project_step project_begin">预热中</span>
					<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
					<span <?php if ($this->_var['deal_item']['is_success'] == 1): ?>class="project_step project_success"<?php else: ?>class="project_step project_fail"<?php endif; ?>><?php if ($this->_var['deal_item']['is_success'] == 1): ?>已成功<?php else: ?>筹资失败<?php endif; ?></span> 	 
					<?php else: ?>
						<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
							<span class="project_step project_success">已成功</span>
						<?php else: ?>
							<span class="project_step project_sprite">
								<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
								长期项目
								<?php else: ?>
									<?php if ($this->_var['deal_item']['type'] == 1): ?>
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
				<?php if ($this->_var['deal_item']['type'] == 1): ?>
				<span class="project_title"><?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['deal_item']['name'],
  'b' => '0',
  'e' => '25',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?></span>
				<div class="project_intro">
					<span class="caption-title f_l"><?php if ($this->_var['deal_item']['type'] == 1): ?>融资金额<?php else: ?>已预购<?php endif; ?>：<em class="f_red"><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['limit_price_w'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?>万</em></span>
					<?php if ($this->_var['deal_item']['stock_type'] == 1 && $this->_var['deal_item']['bonus_count'] > 0): ?>
					<i class="invest_type theme_bgcolor">股权众筹</i>
					<?php elseif ($this->_var['deal_item']['stock_type'] == 2 && $this->_var['deal_item']['bonus_count'] > 0): ?>
					<i class="invest_type bg_red">债权众筹</i>
					<?php elseif ($this->_var['deal_item']['stock_type'] == 3 && $this->_var['deal_item']['bonus_count'] > 0): ?>
					<i class="invest_type theme_bgcolor">股权+债权众筹</i>
					<?php else: ?>
					<?php endif; ?>
				</div>
				<div class="schedule_bar">
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
						<div class="ui-progress">
							<span style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;background:#eaeaea;"><?php echo $this->_var['deal_item']['percent']; ?>%</span>
						</div>
					<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
						<?php if ($this->_var['deal_item']['is_success'] == 1): ?>				
						<div class="ui-progress">
							<span class="bg_green" style="width:100%;"><?php echo $this->_var['deal_item']['percent']; ?>%</span>
						</div>
						<?php else: ?>
						<div class="ui-progress">
							<span class="bg_gray" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"><?php if (( $this->_var['deal_item']['percent'] > 0 && $this->_var['deal_item']['percent'] < 100 )): ?><?php echo $this->_var['deal_item']['percent']; ?>%<?php endif; ?></span>
						</div>
						<?php endif; ?>
			 		<?php else: ?>
			 			<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
							<div class="ui-progress">
								<span class="bg_green" style="width:100%;"><?php echo $this->_var['deal_item']['percent']; ?>%</span>
							</div>
						<?php else: ?>
							<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
							<div class="ui-progress">
								<span class="bg_green" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"></span>
							</div>
							<?php else: ?>
							<div class="ui-progress">
								<span class="bg_green" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"><?php if (( $this->_var['deal_item']['percent'] > 0 && $this->_var['deal_item']['percent'] < 100 )): ?><?php echo $this->_var['deal_item']['percent']; ?>%<?php endif; ?></span>
							</div>
							<?php endif; ?>	
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="project_schedule">           
					<div class="blank"></div>
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
					<div class="div3" style="width:100%;text-align:center;border:0">
						<div class="f_999">离项目开始还有</div>
						<div class="left_times" data="<?php echo $this->_var['deal_item']['left_begin_day']; ?>">
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
						<div class="div3 div3_invest" style="text-align:left">
							<span class="num"><i class="font-yen">¥</i><font><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></font></span>
							<div class="blank10"></div>
							<span class="til">已认投</span>
						</div>
						<div class="div3 div3_invest">
							<span class="num"><?php if ($this->_var['deal_item']['remain_days'] < 0): ?><?php if ($this->_var['deal_item']['percent'] > 100): ?>已成功<?php else: ?>已过期<?php endif; ?><?php else: ?><?php if ($this->_var['deal_item']['remain_days'] <= 0): ?>0<?php else: ?><?php echo $this->_var['deal_item']['remain_days']; ?><?php endif; ?>天<?php endif; ?></span>
							<div class="blank10"></div>
							<span class="til">剩余时间</span>
						</div>
						<div class="div3 div3_invest div3_last" style="text-align:right;">
							<span class="num"><font><?php echo $this->_var['deal_item']['invote_mini_money_w']; ?></font>万</span>
							<div class="blank10"></div>
							<span class="til">起投金额</span>
						</div>
						<div class="blank10"></div>
						<div class="follow">
							<em class="tl">支持：<?php echo $this->_var['deal_item']['support_count']; ?></em>
							<em class="tr">关注：<?php echo $this->_var['deal_item']['focus_count']; ?></em>
						</div>
						<div class="blank0"></div>
					</div>
					<?php else: ?>
					<div class="div3 div3_invest" style="text-align:left">
						<span class="num"><i class="font-yen">¥</i><font><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></font></span>
						<div class="blank10"></div>
						<span class="til">已认投</span>
					</div>
					<div class="div3 div3_invest">
						<span class="num"><?php if ($this->_var['deal_item']['remain_days'] < 0): ?><?php if ($this->_var['deal_item']['percent'] > 100): ?>已成功<?php else: ?>已过期<?php endif; ?><?php else: ?><?php if ($this->_var['deal_item']['remain_days'] <= 0): ?>0<?php else: ?><?php echo $this->_var['deal_item']['remain_days']; ?><?php endif; ?>天<?php endif; ?></span>
						<div class="blank10"></div>
						<span class="til">剩余时间</span>
					</div>
					<div class="div3 div3_invest div3_last" style="text-align:right;">
						<span class="num"><font><?php echo $this->_var['deal_item']['invote_mini_money_w']; ?></font>万</span>
						<div class="blank10"></div>
						<span class="til">起投金额</span>
					</div>
					<?php endif; ?>
					<?php if ($this->_var['deal_item']['begin_time'] <= $this->_var['now']): ?>
					<div class="blank10"></div>
					<div class="follow">
						<em class="tl">支持：<?php echo $this->_var['deal_item']['support_count']; ?></em>
						<em class="tr">关注：<?php echo $this->_var['deal_item']['focus_count']; ?></em>
					</div>
					<div class="blank0"></div>
					<?php endif; ?>
				</div>
				<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
				<div class="follow left_time_follow">
					<em class="rush tc" style="width:100%">抢先浏览</em>
				</div>
				<?php endif; ?>
				<?php else: ?>
				<span class="project_title"><?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['deal_item']['name'],
  'b' => '0',
  'e' => '25',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?></span>
				<div class="project_intro">
					<span class="f_l"><label class="f_666">目标：</label><em class="f_red"><i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['limit_price'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></em></span>
					<span class="f_r">
						<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
						<span>预热中</span>
						<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
						<span <?php if ($this->_var['deal_item']['is_success'] == 1): ?>class="f_red"<?php else: ?>class="f_gray"<?php endif; ?>><?php if ($this->_var['deal_item']['is_success'] == 1): ?>已成功<?php else: ?>筹资失败<?php endif; ?></span> 	 
						<?php else: ?>
							<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
								<span>已成功</span>
							<?php else: ?>
								<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
								<span class="btn_sprite">长期项目</span>
								<?php else: ?>
									<?php if ($this->_var['deal_item']['type'] == 1): ?>
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
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
					<div class="ui-progress">
						<span style="background:#eaeaea;width:<?php echo $this->_var['deal_item']['percent']; ?>%;"></span>
					</div>
					<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
						<?php if ($this->_var['deal_item']['percent'] >= 100): ?>				
						<div class="ui-progress">
							<span class="bg_green" style="width:100%;"></span>
						</div>
						<?php else: ?>
						<div class="ui-progress">
							<span class="bg_gray" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"></span>
						</div>
						<?php endif; ?>
					<?php else: ?>
						<?php if ($this->_var['deal_item']['percent'] >= 100): ?>
							<div class="ui-progress">
								<span class="bg_green" style="width:100%;"></span>
							</div>
						<?php else: ?>
							<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
							<div class="ui-progress">
								<span class="bg_orange" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"></span>
							</div>
							<?php else: ?>
							<div class="ui-progress">
								<span class="bg_orange" style="width:<?php echo $this->_var['deal_item']['percent']; ?>%;"></span>
							</div>
							<?php endif; ?>	
						<?php endif; ?>
					<?php endif; ?>
					<div class="blank0"></div>
				</div>
			 	<div class="project_schedule">
					<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
					<div class="div3" style="text-align:left;width:100%;text-align:center;border:0">
						<div class="f_999">离项目开始还有</div>
						<div class="left_time" data="<?php echo $this->_var['deal_item']['left_begin_day']; ?>">
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
							<span class="num"><?php echo $this->_var['deal_item']['percent']; ?>%</span>
							<div class="blank10"></div>
							<span class="til">达成率</span>
						</div>
						<div class="div3 div3_middle">
							<span class="num"><i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></span>
							<div class="blank10"></div>
				 			<?php if ($this->_var['deal_item']['type'] == 1): ?>
							<span class="til">已认投</span>
							<?php else: ?>
							<span class="til">已筹金额</span>
							<?php endif; ?>
						</div>
						<div class="div3 div3_last" style="text-align:right;">
							<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
							<span class="num"><font><?php echo $this->_var['deal_item']['left_begin_days']; ?></font>天</span>
							<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
							<span class="num"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['end_time'],
  'f' => 'y/m/d',
);
echo $k['name']($k['v'],$k['f']);
?></span>
							<?php else: ?>
							<span class="num">
								<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
								长期项目
								<?php else: ?>
								<font><?php echo $this->_var['deal_item']['remain_days']; ?></font>天
								<?php endif; ?>
							</span>
							<?php endif; ?>	
							<div class="blank10"></div>
							<span class="til">
								<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
									已经预热
								<?php elseif (( $this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0 )): ?>
									结束时间
								<?php else: ?>
									<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
										长期项目
									<?php else: ?>
										剩余时间
									<?php endif; ?>
								<?php endif; ?>
							</span>
						</div>
						<div class="blank10"></div>
						<div class="follow">
							<em class="tl">支持：<?php echo $this->_var['deal_item']['support_count']; ?></em>
							<em class="tr">关注：<?php echo $this->_var['deal_item']['focus_count']; ?></em>
						</div>
						<div class="blank0"></div>
					</div>
					<?php else: ?>
					<div class="div3" style="text-align:left;">
						<span class="num"><?php echo $this->_var['deal_item']['percent']; ?>%</span>
						<div class="blank10"></div>
						<span class="til">达成率</span>
					</div>
					<div class="div3 div3_middle">
						<span class="num"><i class="font-yen">¥</i><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['deal_item']['support_amount'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></span>
						<div class="blank10"></div>
			 			<?php if ($this->_var['deal_item']['type'] == 1): ?>
						<span class="til">已认投</span>
						<?php else: ?>
						<span class="til">已筹金额</span>
						<?php endif; ?>
					</div>
					<div class="div3 div3_last" style="text-align:right;">
						<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
						<span class="num"><font><?php echo $this->_var['deal_item']['left_begin_days']; ?></font>天</span>
						<?php elseif ($this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0): ?>
						<span class="num"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['deal_item']['end_time'],
  'f' => 'y/m/d',
);
echo $k['name']($k['v'],$k['f']);
?></span>
						<?php else: ?>
						<span class="num">
							<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
							长期项目
							<?php else: ?>
							<font><?php echo $this->_var['deal_item']['remain_days']; ?></font>天
							<?php endif; ?>
						</span>
						<?php endif; ?>	
						<div class="blank10"></div>
						<span class="til">
							<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
								已经预热
							<?php elseif (( $this->_var['deal_item']['end_time'] < $this->_var['now'] && $this->_var['deal_item']['end_time'] != 0 )): ?>
								结束时间
							<?php else: ?>
								<?php if ($this->_var['deal_item']['end_time'] == 0): ?>
									长期项目
								<?php else: ?>
									剩余时间
								<?php endif; ?>
							<?php endif; ?>
						</span>				
					</div>
					<?php endif; ?>
					<?php if ($this->_var['deal_item']['begin_time'] <= $this->_var['now']): ?>
					<div class="blank10"></div>
					<div class="follow">
						<em class="tl">支持：<?php echo $this->_var['deal_item']['support_count']; ?></em>
						<em class="tr">关注：<?php echo $this->_var['deal_item']['focus_count']; ?></em>
					</div>
					<div class="blank0"></div>
					<?php endif; ?>
				</div>
				<?php if ($this->_var['deal_item']['begin_time'] > $this->_var['now']): ?>
				<div class="left_time_follow">
					<div class="blank10"></div>
					<div class="follow">
						<em class="rush tc" style="width:100%">抢先浏览</em>
					</div>
				</div>
				<?php endif; ?>
				<?php endif; ?>
			</div>
		</a>
	</div>
<?php if ($this->_var['deal_item']['type'] == 1): ?>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>