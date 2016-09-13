<?php if ($_REQUEST['hasleftpanel'] != 1): ?>
<div class="panel-overlay"></div>
<!-- Left Panel with Reveal effect -->
<div class="panel panel-left panel-reveal theme-dark" id='panel-left-box'>
    <div class="content-block">
		<p id="login_status_info"><?php if (! $this->_var['user_info']): ?>您好，您还没有登录哦<?php else: ?>您好，<span style="color:#fff;"><?php echo $this->_var['user_info']['user_name']; ?></span><?php endif; ?></p>
 		<input type="hidden" id="login_status" value="<?php if (! $this->_var['user_info']): ?>0<?php else: ?>1<?php endif; ?>">
    </div>
    <div class="list-block">
    	<ul>
            <li <?php if ($this->_var['class'] == "settings" || $this->_var['class'] == "user"): ?>class="current"<?php endif; ?> >
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62a;</i></div>
                    <div class="item-inner" id="login_status_url">
                        <?php if ($this->_var['user_info']): ?>
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:settings#index|"."".""); 
?>','#settings-index',2);" class="close-panel">用户中心</a>
                        <?php else: ?>
                        <a href="#" <?php if ($this->_var['is_weixin']): ?> onclick="RouterURL('<?php
echo parse_url_tag_wap("u:user#login|"."".""); 
?>','#user-login',1);"<?php else: ?>onclick="RouterURL('<?php
echo parse_url_tag_wap("u:user#login|"."".""); 
?>','#user-login');"<?php endif; ?>   class="close-panel ">登录/注册</a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <li <?php if ($this->_var['class'] == 'index' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe600;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:index|"."".""); 
?>','#index-index',2);" class="close-panel">网站首页</a>
                    </div>
                </div>
            </li>
            <?php if (app_conf ( "INVEST_STATUS" ) == 0): ?>
            <li <?php if ($this->_var['class'] == 'deals' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe615;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>','#deals-index',2);" class="close-panel">产品众筹</a>
                    </div>
                </div>
            </li>
           <!--  <li <?php if ($this->_var['class'] == 'deals' && $this->_var['act'] == 'stock'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62d;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#stock|"."".""); 
?>','#deals-stock',2);" class="close-panel"><?php echo $this->_var['gq_name']; ?></a>
                    </div>
                </div>
            </li> -->
            <?php if (app_conf ( "IS_STOCK_TRANSFER" ) == 1): ?>
           <!--  <li <?php if ($this->_var['class'] == 'stock_transfer' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62d;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:stock_transfer#index|"."".""); 
?>','#stock_transfer-index',2);" class="close-panel">股权转让</a>
                    </div>
                </div>
            </li> -->
            <?php endif; ?>
            <?php elseif (app_conf ( "INVEST_STATUS" ) == 1): ?>
    		<li <?php if ($this->_var['class'] == 'deals' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe615;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>','#deals-index',2);" class="close-panel">产品众筹</a>
                    </div>
                </div>
            </li>
            <?php else: ?>
            <li <?php if ($this->_var['class'] == 'deals' && $this->_var['act'] == 'stock'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62d;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#stock|"."".""); 
?>','#deals-stock',2);" class="close-panel"><?php echo $this->_var['gq_name']; ?></a>
                    </div>
                </div>
            </li>
            <?php if (app_conf ( "IS_STOCK_TRANSFER" ) == 1): ?>
            <li <?php if ($this->_var['class'] == 'stock_transfer' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62d;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:stock_transfer#index|"."".""); 
?>','#stock_transfer-index',2);" class="close-panel">股权转让</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (app_conf ( "IS_SELFLESS" )): ?>
         	<li <?php if ($this->_var['class'] == 'deals' && $this->_var['act'] == 'selfless'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe621;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:deals#selfless|"."".""); 
?>','#deals-selfless',2);" class="close-panel">公益众筹</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>
           <!-- <li <?php if ($this->_var['class'] == 'investor' && $this->_var['act'] == 'invester_list'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont" style="font-size:1rem;">&#xe62b;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:investor#invester_list|"."".""); 
?>','#investor-invester_list',2);" class="close-panel">天使投资人</a>
                    </div>
                </div>
            </li>-->
            <li <?php if ($this->_var['class'] == 'article_cate' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62e;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:article_cate|"."".""); 
?>','#article_cate-index',2);" class="close-panel">新闻资讯</a>
                    </div>
                </div>
            </li>
			<?php if (app_conf ( "IS_FINANCE" ) == 1): ?>
            <li <?php if ($this->_var['class'] == 'finance' && $this->_var['act'] == 'index'): ?>class="current"<?php endif; ?>>
                <div class="item-content">
                    <div class="item-media"><i class="icon iconfont">&#xe62f;</i></div>
                    <div class="item-inner">
                        <a href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:finance|"."".""); 
?>','#finance-index',2);" class="close-panel">创业公司</a>
                    </div>
                </div>
            </li>
			<?php endif; ?>
        </ul>
    </div>
</div>
<?php endif; ?>