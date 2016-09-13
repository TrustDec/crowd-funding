<?php echo $this->fetch('inc/header.html'); ?>
<div class="blank20"></div>
<div class="wrap">
  <div class="news_box">
    <div class="location_box">
      <div class="location f_l"> <i class="fl ico loc_ico mr5"></i>
        <label>您现在的位置：</label>
        <?php if ($this->_var['nav_top']['top']): ?><a href="<?php echo $this->_var['nav_top']['top']['url']; ?>"><?php echo $this->_var['nav_top']['top']['name']; ?></a><?php endif; ?><?php if ($this->_var['nav_top']['list']): ?><em> > </em><a href="<?php echo $this->_var['nav_top']['list']['url']; ?>"><?php echo $this->_var['nav_top']['list']['name']; ?></a><?php endif; ?> <em> > </em><?php if ($this->_var['nav_top']['cate_child']): ?><a href="<?php echo $this->_var['nav_top']['cate_child']['url']; ?>"><?php echo $this->_var['nav_top']['cate_child']['name']; ?></a><?php endif; ?> </div>
      <div class="blank10"></div>
    </div>
    <div class="news_con">
      <div class="news_fenlei" style="float:left;">
        <div class="lan">
          <div class="cn_txt"> <span>文章分类111</span> </div>
        </div>
        <div class="blank10"></div>
        <ul>
          <li> <a <?php if ($this->_var['module'] == 'article_cate' && $this->_var['action'] == 'index' && $this->_var['id'] == 0): ?>class="on"<?php endif; ?> href="<?php
echo parse_url_tag("u:article_cate|"."id=".$this->_var['artilce_cate_item']['cate_id']."".""); 
?>">全部</a> </li>
          <?php $_from = $this->_var['artilce_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'artilce_cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['artilce_cate_item']):
?>
          <?php if ($this->_var['artilce_cate_item']['is_effect'] == 1 && $this->_var['artilce_cate_item']['is_delete'] == 0 && $this->_var['artilce_cate_item']['type_id'] == 0): ?>
          <li> <a <?php if ($this->_var['artilce_cate_item']['current'] == 1): ?>class="on"<?php endif; ?> href="<?php
echo parse_url_tag("u:article_cate|"."id=".$this->_var['artilce_cate_item']['cate_id']."".""); 
?>"><?php echo $this->_var['artilce_cate_item']['title']; ?></a> </li>
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          <li <?php if ($this->_var['module'] == 'faq' && $this->_var['action'] == 'index' && $this->_var['id'] == 0): ?>class="on"<?php endif; ?>>
          <a href="<?php
echo parse_url_tag("u:faq|"."".""); 
?>">常见问题</a>
          </li>
          <li <?php if ($this->_var['module'] == 'help' && $this->_var['action'] == 'index' && $this->_var['id'] == 0): ?>class="on"<?php endif; ?>>
          <a href="<?php
echo parse_url_tag("u:help#show|"."".""); 
?>">帮助列表</a>
          </li>
          <?php $_from = $this->_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_list_0_34860400_1470320299');if (count($_from)):
    foreach ($_from AS $this->_var['article_list_0_34860400_1470320299']):
?>
          <li><a href="<?php echo $this->_var['article_list_0_34860400_1470320299']['url']; ?>" title="<?php echo $this->_var['article_list_0_34860400_1470320299']['cate_title']; ?>" ><?php echo $this->_var['article_list_0_34860400_1470320299']['cate_title']; ?></a></li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
      </div>
      <div class="newspage_left">
        <h1><?php echo $this->_var['article']['title']; ?></h1>
        <div class="mess_page">发布日期：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['article']['create_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?>&nbsp;&nbsp;来源：<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SITE_NAME',
);
echo $k['name']($k['v']);
?></div>
        <div class="page_vd"><?php echo $this->_var['article']['content']; ?></div>
        <div class="page_biqoaian"> <?php if ($this->_var['article']['tags'] != ''): ?>
          <div class="bqleft">标 签</div>
          <div class="bqright"> <?php $_from = $this->_var['article']['tags_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tag');if (count($_from)):
    foreach ($_from AS $this->_var['tag']):
?>
            <?php if (trim ( $this->_var['tag'] ) != ''): ?> <span title="<?php echo $this->_var['tag']; ?>"><?php echo $this->_var['tag']; ?></span> 
            <!-- <a href="<?php
echo parse_url_tag("u:article_cate|"."tag=".$this->_var['tag']."".""); 
?>" title="<?php echo $this->_var['tag']; ?>" target="_blank"><?php echo $this->_var['tag']; ?></a> --> 
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </div>
          <?php endif; ?> </div>
        <div class="page_up">
          <p><strong>上一篇：</strong><?php if ($this->_var['article_shang']['title']): ?><a href="<?php echo $this->_var['article_shang']['url']; ?>"><?php echo $this->_var['article_shang']['title']; ?><?php else: ?><a href="#">没有上一篇了<?php endif; ?></a></p>
          <p><strong>下一篇：</strong><?php if ($this->_var['article_xia']['title']): ?><a href="<?php echo $this->_var['article_xia']['url']; ?>"><?php echo $this->_var['article_xia']['title']; ?><?php else: ?><a href="#">没有下一篇了<?php endif; ?></a></p>
        </div>
      </div>
      <!--<div class="newspage_right">
				<?php if ($this->_var['article_list']): ?>
			    <div class="lan">
			      	<div class="cn_txt">
			      		<span>相关资讯</span>
			      	</div>
			    </div>
    			<div class="about_news">
		      		<ul>
				        <?php $_from = $this->_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_list_0_34936900_1470320299');if (count($_from)):
    foreach ($_from AS $this->_var['article_list_0_34936900_1470320299']):
?>
				        <li><a href="<?php echo $this->_var['article_list_0_34936900_1470320299']['url']; ?>" title="<?php echo $this->_var['article_list_0_34936900_1470320299']['cate_title']; ?>" ><?php echo $this->_var['article_list_0_34936900_1470320299']['cate_title']; ?></a></li>
				        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			      	</ul>
    			</div>
    			<?php endif; ?>
			 	<div class="lan">
      				<div class="cn_txt">
      					<span>文章分类121212</span>
      				</div>
				</div>
			    <div class="page_fenlei">
			      	<ul>
				        <?php $_from = $this->_var['other_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate');if (count($_from)):
    foreach ($_from AS $this->_var['cate']):
?>
				        <li><a href="<?php echo $this->_var['cate']['url']; ?>" ><?php echo $this->_var['cate']['titles']; ?></a></li>
				        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			      	</ul>
			    </div>
  			</div>-->
      <div class="blank0"></div>
    </div>
    <div class="blank20"></div>
  </div>
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?> 