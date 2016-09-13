<?php echo $this->fetch('inc/header.html'); ?> 
<?php
$this->_var['dpagecss'][] = $this->_var['TMPL_REAL']."/css/deal_show.css";
$this->_var['dpagecss'][] = $this->_var['TMPL_REAL']."/css/news.css";
$this->_var['dpagecss'][] = $this->_var['TMPL_REAL']."/css/deal_log.css";
$this->_var['dpagejs'][] = $this->_var['TMPL_REAL']."/js/news.js";
$this->_var['dcpagejs'][] = $this->_var['TMPL_REAL']."/js/news.js";
$this->_var['dpagejs'][] = $this->_var['TMPL_REAL']."/js/discover.js";
$this->_var['dcpagejs'][] = $this->_var['TMPL_REAL']."/js/discover.js";
?>
<link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['dpagecss'],
);
echo $k['name']($k['v']);
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
.xqmain_main{background:none;}
.hot_recommended a , .more_category a {float:left; padding:5px; white-space:nowrap;}
.news_right_title {padding-left:5px;}
.change_item{margin-bottom:15px;}
.change_item a{padding:2px 5px;background:#ccc;color:#fff;}
.news_deal_info .div3{width:26.3%}
.news_deal_info .div3.tc{width:47.3%}
.deal_news_info_row .div3 span.num{font-size:12px;}
</style>
<div class="blank"></div>

<div class="xqmain news_update">
	<adv adv_id="news_top" />
	<div class="xqmain_main">		
		<?php echo $this->fetch('inc/news_update_header.html'); ?>	
		<div class="news_list_box">	
		<div class="news_left">	
		<div class="blank"></div>
			<div id="pin_box">		
			<?php echo $this->fetch('inc/news_item.html'); ?>
			</div>
			<div class="ajax_loading_log" id="pin_loading" rel="<?php echo $this->_var['ajaxurl']; ?>">加载更多动态</div>
			<div class="pages"><?php echo $this->_var['pages']; ?></div>
		</div><!--end left-->
		<div class="news_right">
			<div class="blank10"></div>
			<div class="news_right_title">
				<span class="f_l">你可能感兴趣的项目</span>
				<a href="javascript:void(0);" id="chang_rand" rel="<?php
echo parse_url_tag("u:ajax#randdeal|"."".""); 
?>" class="c_change linkgreen f_r">换一换</a>
				<div class="blank0"></div>
			</div>
			<div class="blank"></div>
			<div id="rand_deal">
				<?php echo $this->fetch('inc/rand_deals.html'); ?>
			</div>
			<div class="blank20"></div>
			<div class="change_item" id="change_item">
				<?php if (app_conf ( "INVEST_STATUS" ) == 0): ?>
				<a href="javascript:void(0);" class="cur">回报众筹</a>
				<a href="javascript:void(0);"><?php echo $this->_var['gq_name']; ?></a>
				<?php endif; ?>
				<?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
				<a href="javascript:void(0);" class="cur">回报众筹</a>
				<?php endif; ?>
				<?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
				<a href="javascript:void(0);" class="cur"><?php echo $this->_var['gq_name']; ?></a>
				<?php endif; ?>
			</div>
			<div class="change_item_box">
				<?php if (app_conf ( "INVEST_STATUS" ) == 0): ?>
				<div class="item_box">
					<div class="hot_recommended">
						<div class="news_right_title">热门推荐</div>
						<div class="blank5"></div>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=rec".""); 
?>" target="_blank" class="linkgreen block lh24">推荐项目</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=new".""); 
?>" target="_blank" class="linkgreen block lh24">最新上线</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=nend".""); 
?>" target="_blank" class="linkgreen block lh24">即将结束</a>
					</div>
					<div class="blank10"></div>
					<div class="more_category">
						<div class="news_right_title">更多类别</div>
						<div class="blank5"></div>
						<?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['cate_item']):
?>
						<?php if ($this->_var['cate_item']['pid'] == 0): ?>
							<a href="<?php
echo parse_url_tag("u:deals|"."id=".$this->_var['cate_item']['id']."".""); 
?>" target="_blank" class="linkgreen block lh24"><?php echo $this->_var['cate_item']['name']; ?></a>
						<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
					<div class="blank0"></div>
				</div>
				<div class="item_box" style="display:none">
					<div class="hot_recommended">
						<div class="news_right_title">热门推荐</div>
						<div class="blank5"></div>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=rec&type=1".""); 
?>" target="_blank" class="linkgreen block lh24">推荐项目</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=new&type=1".""); 
?>" target="_blank" class="linkgreen block lh24">最新上线</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=nend&type=1".""); 
?>" target="_blank "class="linkgreen block lh24">即将结束</a>
					</div>
					<div class="blank10"></div>
					<div class="more_category">
						<div class="news_right_title">更多类别</div>
						<div class="blank5"></div>
						<?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['cate_item']):
?>
						<?php if ($this->_var['cate_item']['pid'] == 0): ?>
							<a href="<?php
echo parse_url_tag("u:deals|"."id=".$this->_var['cate_item']['id']."&type=1".""); 
?>" target="_blank" class="linkgreen block lh24"><?php echo $this->_var['cate_item']['name']; ?></a>
						<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
					<div class="blank0"></div>
				</div>
				<?php endif; ?>
				<?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
				<div class="item_box">
					<div class="hot_recommended">
						<div class="news_right_title">热门推荐</div>
						<div class="blank5"></div>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=rec".""); 
?>" target="_blank" class="linkgreen block lh24">推荐项目</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=new".""); 
?>" target="_blank" class="linkgreen block lh24">最新上线</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=nend".""); 
?>" target="_blank" class="linkgreen block lh24">即将结束</a>
					</div>
					<div class="blank10"></div>
					<div class="more_category">
						<div class="news_right_title">更多类别</div>
						<div class="blank5"></div>
						<?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['cate_item']):
?>
						<?php if ($this->_var['cate_item']['pid'] == 0): ?>
							<a href="<?php
echo parse_url_tag("u:deals|"."id=".$this->_var['cate_item']['id']."".""); 
?>" target="_blank" class="linkgreen block lh24"><?php echo $this->_var['cate_item']['name']; ?></a>
						<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
					<div class="blank0"></div>
				</div>
				<?php endif; ?>
				<?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
				<div class="item_box">
					<div class="hot_recommended">
						<div class="news_right_title">热门推荐</div>
						<div class="blank5"></div>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=rec&type=1".""); 
?>" target="_blank" class="linkgreen block lh24">推荐项目</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=new&type=1".""); 
?>" target="_blank" class="linkgreen block lh24">最新上线</a>
						<a href="<?php
echo parse_url_tag("u:deals|"."r=nend&type=1".""); 
?>" target="_blank "class="linkgreen block lh24">即将结束</a>
					</div>
					<div class="blank10"></div>
					<div class="more_category">
						<div class="news_right_title">更多类别</div>
						<div class="blank5"></div>
						<?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['cate_item']):
?>
						<?php if ($this->_var['cate_item']['pid'] == 0): ?>
							<a href="<?php
echo parse_url_tag("u:deals|"."id=".$this->_var['cate_item']['id']."&type=1".""); 
?>" target="_blank" class="linkgreen block lh24"><?php echo $this->_var['cate_item']['name']; ?></a>
						<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
					<div class="blank0"></div>
				</div>
				<?php endif; ?>
			</div>
		</div><!--end right-->		
		<div class="blank"></div>	
		</div>	
	</div>
</div>
<div class="blank"></div>
<script type="text/javascript">
	$("#change_item").find("a").on('click',function(){
		$obj=$(this);
		var index=$obj.index();
		$obj.addClass("cur").siblings().removeClass("cur");
		$(".change_item_box").find(".item_box").eq(index).fadeIn().siblings().hide();
	});
</script>
<?php echo $this->fetch('inc/footer.html'); ?> 