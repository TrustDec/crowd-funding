<?php echo $this->fetch('inc/header.html'); ?>
<div class="blank20"></div>
<div class="wrap">
  <div class="news_box">
    <div class="location_box">
      <div class="location f_l"> <i class="fl ico loc_ico mr5"></i>
        <label>您现在的位置：</label>
        <?php if ($this->_var['nav_top']['top']): ?><a href="<?php echo $this->_var['nav_top']['top']['url']; ?>"><?php echo $this->_var['nav_top']['top']['name']; ?></a><?php endif; ?><?php if ($this->_var['nav_top']['list']): ?><em>>></em><a href="<?php echo $this->_var['nav_top']['list']['url']; ?>"><?php echo $this->_var['nav_top']['list']['name']; ?></a><?php endif; ?><?php if ($this->_var['nav_top']['cate_child']): ?><em>>></em><a href="<?php echo $this->_var['nav_top']['cate_child']['url']; ?>"><?php echo $this->_var['nav_top']['cate_child']['name']; ?></a><?php endif; ?> </div>
      <div class="blank10"></div>
    </div>
    <div class="news_con">
    <div class="news_fenlei" style="float:left;">
        <div class="lan">
          <div class="cn_txt"> <span>文章分类</span> </div>
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
          <?php $_from = $this->_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_list');if (count($_from)):
    foreach ($_from AS $this->_var['article_list']):
?>
          <li><a href="<?php echo $this->_var['article_list']['url']; ?>" title="<?php echo $this->_var['article_list']['cate_title']; ?>" ><?php echo $this->_var['article_list']['cate_title']; ?></a></li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
      </div>
      <div class="news_left">
        <div class="lan">
          <div class="cn_txt"> <span><?php if ($this->_var['cate_name']): ?><?php echo $this->_var['cate_name']; ?><?php else: ?>文章列表<?php endif; ?></span> </div>
        </div>
        <?php $_from = $this->_var['artilce_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_item');if (count($_from)):
    foreach ($_from AS $this->_var['article_item']):
?>
        <div class="list_news">
          <div class="pic"> <a href="<?php echo $this->_var['article_item']['url']; ?>" title="<?php echo $this->_var['article_item']['title']; ?>"> <?php if ($this->_var['article_item']['icon'] == ''): ?><img src='<?php echo $this->_var['TMPL']; ?>/images/empty_thumb.gif'  width="285" height="200"/><?php else: ?><img src="<?php echo $this->_var['article_item']['icon']; ?>" width="285" height="200"><?php endif; ?> </a> </div>
          <div class="miaosu">
            <h2> <a href="<?php echo $this->_var['article_item']['url']; ?>" title="<?php echo $this->_var['article_item']['title']; ?>"><?php echo $this->_var['article_item']['title']; ?></a> </h2>
            <div class="mscon"><?php 
$k = array (
  'name' => 'msubstr',
  'val' => $this->_var['article_item']['brief'],
  'a' => '0',
  'b' => '136',
);
echo $k['name']($k['val'],$k['a'],$k['b']);
?></div>
            <div class="bianqian">
              <div class="bianqian_left">
                <div class="icon_bq">标签</div>
                <div class="bq_list"> <?php $_from = $this->_var['article_item']['tags_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tag');if (count($_from)):
    foreach ($_from AS $this->_var['tag']):
?>
                  <?php if (trim ( $this->_var['tag'] ) != ''): ?> <a href="<?php
echo parse_url_tag("u:article_cate|"."tag=".$this->_var['tag']."".""); 
?>" title="<?php echo $this->_var['tag']; ?>" target="_blank"><?php echo $this->_var['tag']; ?></a> <?php endif; ?>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </div>
              </div>
              <div class="bianqian_right">
                <div class="zuozhe"> <span>发布的人：<?php if ($this->_var['article_item']['writer']): ?><?php echo $this->_var['article_item']['writer']; ?><?php else: ?><?php echo $this->_var['site_name']; ?><?php endif; ?></span> </div>
                <div class="gxshijian"> <span><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['article_item']['create_time'],
  'p' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['p']);
?></span> </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <div class="blank20"></div>
        <div class="pages" style="width:100%;"><?php echo $this->_var['pages']; ?></div>
        <div class="blank20"></div>
      </div>
      <!--<div class="news_fenlei">
                <div class="lan">
                    <div class="cn_txt">
                        <span>文章分类</span>
                    </div>
                </div>
                <div class="blank10"></div> 
                <ul>
                    <li>
                        <a <?php if ($this->_var['module'] == 'article_cate' && $this->_var['action'] == 'index' && $this->_var['id'] == 0): ?>class="on"<?php endif; ?> href="<?php
echo parse_url_tag("u:article_cate|"."id=".$this->_var['artilce_cate_item']['cate_id']."".""); 
?>">全部</a>
                    </li>
                    <?php $_from = $this->_var['artilce_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'artilce_cate_item');if (count($_from)):
    foreach ($_from AS $this->_var['artilce_cate_item']):
?>
                    <?php if ($this->_var['artilce_cate_item']['is_effect'] == 1 && $this->_var['artilce_cate_item']['is_delete'] == 0 && $this->_var['artilce_cate_item']['type_id'] == 0): ?>
                    <li>
                        <a <?php if ($this->_var['artilce_cate_item']['current'] == 1): ?>class="on"<?php endif; ?> href="<?php
echo parse_url_tag("u:article_cate|"."id=".$this->_var['artilce_cate_item']['cate_id']."".""); 
?>"><?php echo $this->_var['artilce_cate_item']['title']; ?></a>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>     
            </div>-->
    </div>
  </div>
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>