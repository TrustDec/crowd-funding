<?php echo $this->fetch('inc/header.html'); ?> 
<?php
$this->_var['dpagecss'][] = $this->_var['TMPL_REAL']."/css/deal_list.css";
$this->_var['dcpagecss'][] = $this->_var['TMPL_REAL']."/css/deal_list.css";
$this->_var['dpagejs'][] = $this->_var['TMPL_REAL']."/js/index.js";
$this->_var['dcpagejs'][] = $this->_var['TMPL_REAL']."/js/index.js";
$this->_var['dpagejs'][] = $this->_var['TMPL_REAL']."/js/discover.js";
$this->_var['dcpagejs'][] = $this->_var['TMPL_REAL']."/js/discover.js";
?>
<link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['dpagecss'],
  'c' => $this->_var['dcpagecss'],
);
echo $k['name']($k['v'],$k['c']);
?>" />
<script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['dpagejs'],
  'c' => $this->_var['dcpagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
<style>
.field_select{margin-right:0}
.field_select dt{height:26px;line-height:26px}
.field_select dt i{top:12px;}
</style>
<div id="J_wrap">
 	<div class="blank20"></div>
	<div class="ui-deals wrap" style="overflow:hidden;">
		<adv adv_id="deals_top" />
		<div class="blank20"></div>
		<div class="ui_deals_filter">
			<div class="ui_deals_filter_item">
				<div class="filter_item">
					<label class="f_l">所属<?php if ($this->_var['p_type'] == 2): ?>类型<?php else: ?>行业<?php endif; ?>：</label>
					<div class="filter_text f_l">
						<ul>
							<li {if <?php if ($this->_var['p_id'] == 0): ?>class="current"<?php endif; ?>}>
								<a href="<?php
echo parse_url_tag("u:deals|"."loc=".$this->_var['p_loc']."&type=".$this->_var['p_type']."".""); 
?>" title="全部">全部</a>
							</li>
							<?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['cate_item']):
?>
							<li <?php if ($this->_var['pid'] == $this->_var['cate_item']['id']): ?>class="current"<?php endif; ?>>
								<?php if ($this->_var['cate_item']['pid'] == 0): ?>
								<a href="<?php echo $this->_var['cate_item']['url']; ?>" title="<?php echo $this->_var['cate_item']['name']; ?>"><?php echo $this->_var['cate_item']['name']; ?></a>
								<?php endif; ?>
							</li>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
					</div>
					<?php if ($this->_var['child_cate_list']): ?>
					<div class="blank0"></div>
					<div class="ui_deals_filter_l child_cate">
						<ul>
							<?php $_from = $this->_var['child_cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['child_cate_item']):
?>
							<li <?php if ($this->_var['p_id'] == $this->_var['child_cate_item']['id']): ?>class="current"<?php endif; ?>>
								<a href="<?php echo $this->_var['child_cate_item']['url']; ?>" title="<?php echo $this->_var['child_cate_item']['name']; ?>"><?php echo $this->_var['child_cate_item']['name']; ?></a>
							</li>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
						<div class="blank0"></div>
					</div>
					<div class="blank5"></div>
					<?php endif; ?>
				</div>
			</div>
			<div class="ui_deals_filter_item last_item">
				<div class="filter_item" id="deals_area">
					<label class="f_l">所属地区：</label>
					<div class="filter_text f_l">
						<ul>
							<li <?php if ($this->_var['p_loc'] == ''): ?>class="current"<?php endif; ?>>
								<a href="<?php
echo parse_url_tag("u:deals|"."r=".$this->_var['p_r']."&id=".$this->_var['p_id']."&type=".$this->_var['p_type']."".""); 
?>" value="">全部</a>
							</li>
							<?php $_from = $this->_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'city_item');if (count($_from)):
    foreach ($_from AS $this->_var['city_item']):
?>
							<?php if ($this->_var['city_item']['province'] != ''): ?>
							<li <?php if ($this->_var['p_loc'] == $this->_var['city_item']['province']): ?>class="current"<?php endif; ?>>
								<a href="<?php echo $this->_var['city_item']['url']; ?>" target="_self" value=""><?php echo $this->_var['city_item']['province']; ?></a>
							</li>
							<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
					</div>
					
					<a href="javascript:void(0);" onclick="javascript:show_pop_region();" class="more_city hide"><i class="icon iconfont">&#xe619;</i>选择更多城市</a>
				</div>
			</div>
			<div class="blank0"></div>
		</div>
		<div id="pin_box">
			<div class="filter-box">
				<div class="rel-key-rec f_l" style="margin-right:10px">
					<a href="<?php echo $this->_var['url_list']['price_url']; ?>" class="<?php if ($this->_var['price'] != ''): ?>current<?php endif; ?> first">融资金额<i <?php if ($this->_var['price'] == 2): ?>class="asc"<?php endif; ?>></i></a>
					<a href="<?php echo $this->_var['url_list']['focus_url']; ?>" class="<?php if ($this->_var['focus'] != ''): ?>current<?php endif; ?>" >关注数<i <?php if ($this->_var['focus'] == 2): ?>class="asc"<?php endif; ?>></i></a>
					<a href="<?php echo $this->_var['url_list']['time_url']; ?>" class="<?php if ($this->_var['time'] != ''): ?>current<?php endif; ?>">剩余时间<i <?php if ($this->_var['time'] == 2): ?>class="asc"<?php endif; ?>></i></a>
					<a href="<?php echo $this->_var['url_list']['cp_url']; ?>" class="<?php if ($this->_var['cp'] != ''): ?>current<?php endif; ?>">完成比例<i <?php if ($this->_var['cp'] == 2): ?>class="asc"<?php endif; ?>></i></a>
				</div>
				<div class="range f_r">
					<select name="deal_sort" id="deal_sort" class="ui-select field_select small">
						<option <?php if (! $this->_var['p_r']): ?>selected="selected"<?php endif; ?>  value="<?php
echo parse_url_tag("u:deals|"."type=".$this->_var['p_type']."".""); 
?>">请选择</option>
						<option <?php if ($this->_var['p_r'] == 'rec'): ?>selected="selected"<?php endif; ?> value="<?php
echo parse_url_tag("u:deals|"."r=rec&type=".$this->_var['p_type']."".""); 
?>">推荐项目</option>
						<option <?php if ($this->_var['p_r'] == 'yure'): ?>selected="selected"<?php endif; ?> value="<?php
echo parse_url_tag("u:deals|"."r=yure&type=".$this->_var['p_type']."".""); 
?>">正在预热</option>
						<option <?php if ($this->_var['p_r'] == 'new'): ?>selected="selected"<?php endif; ?> value="<?php
echo parse_url_tag("u:deals|"."r=new&type=".$this->_var['p_type']."".""); 
?>">最新上线</option>
						<option <?php if ($this->_var['p_r'] == 'nend'): ?>selected="selected"<?php endif; ?> value="<?php
echo parse_url_tag("u:deals|"."r=nend&type=".$this->_var['p_type']."".""); 
?>">即将结束</option>
						<option <?php if ($this->_var['p_r'] == 'classic'): ?>selected="selected"<?php endif; ?> value="<?php
echo parse_url_tag("u:deals|"."r=classic&type=".$this->_var['p_type']."".""); 
?>">经典项目</option>
					</select>
				</div>	
				<script type="text/javascript">
					$("#deal_sort").bind('change',function(){
						location.href = $('#deal_sort option:selected').val();
					});
				</script>
				<div class="rel-key-rec f_r" style="margin-right:10px">
					<a href="<?php
echo parse_url_tag("u:deals|"."type=".$this->_var['p_type']."".""); 
?>" <?php if ($this->_var['p_state'] == ''): ?> class="current"<?php endif; ?>>所有项目<?php if ($this->_var['p_state'] == ''): ?>(<?php echo $this->_var['deal_count']; ?>)<?php endif; ?></a>
					<?php $_from = $this->_var['state_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'state_item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['state_item']):
?>
					<a href="<?php echo $this->_var['state_item']['url']; ?>" title="<?php echo $this->_var['state_item']['name']; ?>" <?php if ($this->_var['p_state'] == $this->_var['key']): ?>class="current"<?php endif; ?>><?php echo $this->_var['state_item']['name']; ?><?php if ($this->_var['p_state'] == $this->_var['key']): ?>(<?php echo $this->_var['deal_count']; ?>)<?php endif; ?></a>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
				</div>
				<div class="blank0"></div>	
			</div>
			<div class="deal_item_list">
				<?php if ($this->_var['p_type'] == 1): ?>
					<?php echo $this->fetch('inc/deal_list_invest.html'); ?>
				<?php else: ?>
					<?php echo $this->fetch('inc/deal_list.html'); ?>
				<?php endif; ?>
			</div>
		</div>	
		<div class="blank"></div>
		<div id="pin_loading" rel="<?php
echo parse_url_tag("u:ajax#deals|"."r=".$this->_var['p_r']."&id=".$this->_var['p_id']."&loc=".$this->_var['p_loc']."&tag=".$this->_var['p_tag']."&k=".$this->_var['p_k']."&p=".$this->_var['current_page']."&state=".$this->_var['p_state']."&type=".$this->_var['p_type']."&price=".$this->_var['price']."&focus=".$this->_var['focus']."&time=".$this->_var['time']."&cp=".$this->_var['cp']."".""); 
?>">正在努力加载</div>
		<div class="pin_loading_icon"></div>	
		<div class="pages"><?php echo $this->_var['pages']; ?></div>
		<div class="blank20"></div>
		<adv adv_id="deals_bottom" />
	</div>
</div>
<script type="text/javascript">
	$(function(){
		var $deals_area=$("#deals_area");
		if($deals_area.find("li").length>=17){
			$(this).find(".more_city").show();
		}
		$("embed").attr({windowlessVideo: "1", wmode: "transparent"});
	});
</script>
<script type="text/javascript">
	if($(".left_time").length){
		leftTimeAct(".left_time");
	}
	if($(".left_times").length){
		leftTimeAct(".left_times");
	}
	// 未开始项目倒计时
    function leftTimeAct(left_time){
    	var leftTimeActInv = null;
		clearTimeout(leftTimeActInv);
		$(left_time).each(function(){
			var leftTime = parseInt($(this).attr("data"));
			if(leftTime > 0)
			{
				var day  =  parseInt(leftTime / 24 /3600);
				var hour = parseInt((leftTime % (24 *3600)) / 3600);
				var min = parseInt((leftTime % 3600) / 60);
				var sec = parseInt((leftTime % 3600) % 60);
				$(this).find(".day").html((day<10?"0"+day:day));
				$(this).find(".hour").html((hour<10?"0"+hour:hour));
				$(this).find(".min").html((min<10?"0"+min:min));
				$(this).find(".sec").html((sec<10?"0"+sec:sec));
				leftTime = leftTime-1;
				$(this).attr("data",leftTime);
			}
			else{
				$(this).parent(".div3").hide();
				$(this).parent(".div3").next().show();
				$(this).parent().parent().parent().find(".left_time_follow").hide();
				return false;
			}
		});
		leftTimeActInv = setTimeout(function(){
			leftTimeAct(left_time);
		},1000);
	}
</script>
<script type="text/javascript">
	if ($.browser.msie && $.browser.version <= 7){
		$(".nav_item").bind('click',function(){
			var $obj=$(this).find("a");
			var p_url=$obj.attr("href");
			window.open(p_url);
		});
	}
</script>
<?php echo $this->fetch('inc/footer.html'); ?> 