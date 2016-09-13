<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('inc/header.html'); ?>
<?php
    $this->_var['dpagecss'][] = $this->_var['TMPL_REAL']."/css/dz_index.css";
?>
<link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['dpagecss'],
);
echo $k['name']($k['v']);
?>" />
<input type="hidden" class="pull_to_refresh_url" value="<?php
echo parse_url_tag_wap("u:index|"."".""); 
?>" />
<!-- 默认的下拉刷新层 -->
<div class="pull-to-refresh-layer">
    <div class="preloader"></div>
    <div class="pull-to-refresh-arrow"></div>
</div>
<div class="pull-to-refresh-content">
<?php endif; ?>
    <!-- 分类导航 开始 -->
    <nav class="index_nav">
        <ul class="webkit-box">
            <?php if ($this->_var['invest_status'] == 0): ?>
                <li class="curr webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>','#deals-index',2);">产品众筹</a></li>
                 <li class="webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>','#deals-index',2);"><?php echo $this->_var['gq_name']; ?></a></li> 
            <?php elseif ($this->_var['invest_status'] == 1): ?>
                <li class="curr webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>','#deals-index',2);">产品众筹</a></li>
            <?php else: ?>
                <li class="webkit-box-flex"><a href="<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>','#deals-index',2);"><?php echo $this->_var['gq_name']; ?></a></li>
            <?php endif; ?>
    		<?php if (app_conf ( "IS_HOUSE" )): ?>
    		 <li class="webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#house|"."".""); 
?>','#deals-house',2);">房产众筹</a></li>
    		<?php endif; ?>
            <?php if (app_conf ( "IS_SELFLESS" )): ?>
            <li class="webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#selfless|"."".""); 
?>','#deals-selfless',2);">公益众筹</a></li>
            <?php else: ?>
            <li class="webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:article_cate|"."".""); 
?>','#article_cate-index',2);">新闻资讯</a></li>
            <?php endif; ?>
            <!--<li class="nav_last webkit-box-flex"><a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:score_mall#index|"."".""); 
?>','#score_mall-index',2);">积分商城</a></li> -->
        </ul>
    </nav>
    <!-- 分类导航 结束 -->
    <!--首页广告位 开始 -->
    <?php if ($this->_var['adv_list']): ?>
    <section class="swiper-container" data-space-between='10'>
        <div class="swiper-wrapper">
            <?php $_from = $this->_var['adv_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'adv');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['adv']):
?>
                <div class="swiper-slide"><a href="#" onclick="RouterURL('<?php echo $this->_var['adv']['url']; ?>','#adv_<?php echo $this->_var['adv']['open_url_type']; ?>',1);"><img src="<?php echo $this->_var['adv']['img']; ?>" alt=""></a></div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php if ($this->_var['adv_list'] && count ( $this->_var['adv_list'] ) > 1): ?>
        <div class="swiper-pagination"></div>
        <?php endif; ?>
    </section>

    <?php endif; ?>
    <!--首页广告位 结束 -->
    <!-- 首页分类 开始 -->
    <section class="index_category sizing" id="category_slidebox">
        <div class="bd">
            <ul>
                <li class="itemss">
                    <?php $_from = $this->_var['cates_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'cate');$this->_foreach['cate_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate_list']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['cate']):
        $this->_foreach['cate_list']['iteration']++;
?>
                    <?php if ($this->_foreach['cate_list']['iteration'] <= 7): ?>
                    <?php if ($this->_var['cate']['pid'] == 0): ?>
                    <div class="items">
                      
                        <div class="item <?php if ($this->_var['k'] % 7 == 0): ?>bg1<?php endif; ?><?php if ($this->_var['k'] % 7 == 1): ?>bg2<?php endif; ?><?php if ($this->_var['k'] % 7 == 2): ?>bg3<?php endif; ?><?php if ($this->_var['k'] % 7 == 3): ?>bg4<?php endif; ?><?php if ($this->_var['k'] % 7 == 4): ?>bg5<?php endif; ?><?php if ($this->_var['k'] % 7 == 5): ?>bg6<?php endif; ?><?php if ($this->_var['k'] % 7 == 6): ?>bg7<?php endif; ?>">
                               <a href="<?php if (app_conf ( 'INVEST_STATUS' ) == 0 || app_conf ( 'INVEST_STATUS' ) == 1): ?><?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."".""); 
?><?php endif; ?><?php if (app_conf ( 'INVEST_STATUS' ) == 2): ?><?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."&type=1".""); 
?><?php endif; ?>" class="">
                                   <span><?php echo $this->_var['cate']['name']; ?></span>
                               </a>
                        </div> 
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <div class="items all_items">
                        <div class="item">
                            <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:category#index|"."".""); 
?>','#category-index');">
                                <i class="icon iconfont">&#xe630;</i><br />全部分类
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="hd">
            <ul></ul>
        </div>
    </section>
    <!-- 首页分类 结束 -->
    <!-- 最新创意产品列表 开始 -->
    <section class="items_list mt10">
        <h2 class="h2-title bdl sizing">最新创意</h2>
        <div class="tabul-div">
            <ul class="tab-ul">
                <li><a class="current">综合推荐</a></li>
                <li><a href="<?php if (app_conf ( 'INVEST_STATUS' ) == 0 || app_conf ( 'INVEST_STATUS' ) == 1): ?><?php
echo parse_url_tag_wap("u:deals|"."r=rec".""); 
?><?php endif; ?><?php if (app_conf ( 'INVEST_STATUS' ) == 2): ?><?php
echo parse_url_tag_wap("u:deals|"."r=rec&type=1".""); 
?><?php endif; ?>">推荐项目</a></li>
                <li><a href="<?php if (app_conf ( 'INVEST_STATUS' ) == 0 || app_conf ( 'INVEST_STATUS' ) == 1): ?><?php
echo parse_url_tag_wap("u:deals|"."r=yure".""); 
?><?php endif; ?><?php if (app_conf ( 'INVEST_STATUS' ) == 2): ?><?php
echo parse_url_tag_wap("u:deals|"."r=yure&type=1".""); 
?><?php endif; ?>">正在预热</a></li>
                <li><a href="<?php if (app_conf ( 'INVEST_STATUS' ) == 0 || app_conf ( 'INVEST_STATUS' ) == 1): ?><?php
echo parse_url_tag_wap("u:deals|"."r=new".""); 
?><?php endif; ?><?php if (app_conf ( 'INVEST_STATUS' ) == 2): ?><?php
echo parse_url_tag_wap("u:deals|"."r=new&type=1".""); 
?><?php endif; ?>">最新上线</a></li>
            </ul>
        </div>
        <?php if (app_conf ( 'INVEST_STATUS' ) == 0 || app_conf ( 'INVEST_STATUS' ) == 1): ?>
            <div class="items">
                <?php echo $this->fetch('inc/deal_list_index_new_pro.html'); ?>
            </div>
            <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>','#deals-index',2);">
                <div id="load-more" class="load-more l-btn" style="display:block">
                    <div class="addMoreProject">更多产品众筹</div>
                </div>
            </a>
        <?php else: ?>
            <div class="items">
                <?php echo $this->fetch('inc/deal_list_index_new_invest.html'); ?>
            </div>
            <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>','#deals-index',2);">
                <div id="load-more" class="load-more l-btn" style="display:block">
                    <div class="addMoreProject">更多<?php echo $this->_var['gq_name']; ?></div>
                </div>
            </a>
        <?php endif; ?>
    </section>
    <!-- 最新创意产品列表 结束 -->
    <!-- 热门投资产品列表 开始 -->
    <section class="items_list mt10">
        <?php if (app_conf ( 'INVEST_STATUS' ) != 1): ?>
        <h2 class="h2-title bdl sizing">热门投资</h2>
        <div class="tabul-div">
            <ul class="tab-ul">
                <li><a class="current">综合推荐</a></li>
                <li><a href="<?php
echo parse_url_tag_wap("u:deals|"."r=rec&type=1".""); 
?>">推荐项目</a></li>
                <li><a href="<?php
echo parse_url_tag_wap("u:deals|"."r=yure&type=1".""); 
?>">正在预热</a></li>
                <li><a href="<?php
echo parse_url_tag_wap("u:deals|"."r=new&type=1".""); 
?>">最新上线</a></li>
            </ul>
        </div>
        <div class="items">
            <?php echo $this->fetch('inc/deal_list_index_hot_invest.html'); ?>
        </div>
        <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>">
            <div id="load-more" class="load-more l-btn" style="display:block">
                <div class="addMoreProject">更多<?php echo $this->_var['gq_name']; ?></div>
            </div>
        </a>
        <?php endif; ?>
    </section> 
    <!-- 热门投资产品列表 结束 -->
    <div class="blank10"></div>
<?php if ($_REQUEST['is_ajax'] != 1): ?>
</div>
<?php echo $this->fetch('inc/footer.html'); ?>
<?php endif; ?>