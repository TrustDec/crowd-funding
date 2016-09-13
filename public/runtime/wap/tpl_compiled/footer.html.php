	</div>
	<!-- content 结束 -->
	<!-- footer-nav 开始 -->
  	<nav class="bar bar-tab footer_bar">
     	<a class="tab-item <?php if ($this->_var['class'] == 'index' && $this->_var['act'] == "index"): ?>active<?php endif; ?>" href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:index|"."".""); 
?>','#index-index');">
	      	<i class="icon iconfont">&#xe600;</i>
     	 	<span class="tab-label">首页</span>
	    </a>
	    <?php if (! app_conf ( "PROJECT_HIDE" )): ?>
		<?php if (app_conf ( "INVEST_STATUS" ) != 2): ?>
	    <a class="tab-item <?php if ($this->_var['act'] == "choose"): ?>active<?php endif; ?>" href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:project#choose|"."".""); 
?>','#project-choose');">
	      	<i class="icon iconfont">&#xe601;</i>
     	 	<span class="tab-label">发起</span>
	    </a>
	    <input type="hidden" name="check_login" value="<?php echo $this->_var['user_info']['id']; ?>">
	    <?php endif; ?>
		<?php endif; ?>
	 	<a class="tab-item <?php if ($this->_var['act'] == "invester_list"): ?>active<?php endif; ?>" href="#" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:investor#invester_list|"."".""); 
?>','#investor-invester_list');">
	      	<i class="icon iconfont">&#xe604;</i>
     	 	<span class="tab-label">投资人</span>
	    </a>
	    <?php if ($this->_var['user_info']): ?>
	    <a class="tab-item {if $class eq 'account' || <?php if ($this->_var['class'] == 'settings'): ?>}active<?php endif; ?>" onclick="RouterURL('<?php
echo parse_url_tag_wap("u:settings#index|"."".""); 
?>','#settings-index');">
	      	<i class="icon iconfont">&#xe602;</i>
     	 	<span class="tab-label">我</span>
	    </a>
	    <?php else: ?>
 	 	<a class="tab-item <?php if ($this->_var['act'] == 'login'): ?>active<?php endif; ?>" <?php if ($this->_var['is_weixin']): ?> onclick="RouterURL('<?php
echo parse_url_tag_wap("u:user#login|"."".""); 
?>','#user-login',1);"<?php else: ?> onclick="RouterURL('<?php
echo parse_url_tag_wap("u:user#login|"."".""); 
?>','#user-login',2);"<?php endif; ?>>
	      	<i class="icon iconfont">&#xe602;</i>
     	 	<span class="tab-label">登录</span>
	    </a>
	    <?php endif; ?>
  	</nav>
  	<!-- footer-nav 结束 -->
</div>
<!-- page 结束 -->
<?php echo $this->fetch('inc/left.html'); ?>
<?php if ($this->_var['referral']): ?>
<div class="popup popup-about-referral">
	<header class="bar bar-nav">
	    <a class="button button-link button-nav pull-right close-popup">关闭</a>
	    <h1 class="title">邀请规则</h1>
  	</header>
	<div class="content">
    <div class="content-block">
	    <p>总返利:  40积分</p>
		<p>返利规则：</p>
		<p>1、通过发送会员专属邀请链接，推荐好友注册成为[本平台] 会员。</p>
		<p>2、好友通过邀请链接访问网站并成功注册成为本站会员。</p>
		<p>3、每成功邀请一个会员注册，您获得20积分奖励。积分可以抵用金额。</p>
    </div>
	</div>
</div>
<!-- Services Popup -->
<div class="popup popup-services-referral">
	<header class="bar bar-nav">
	    <a class="button button-link button-nav pull-right close-popup">关闭</a>
	    <h1 class="title">我的邀请</h1>
  	</header>
	<div class="content">
		<?php if ($this->_var['referrals_list']): ?>
		<div class="content-block-title">邀请记录</div>
    	<div class="list-block media-list">
	        <ul>
				<?php $_from = $this->_var['referrals_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'referrals_item');if (count($_from)):
    foreach ($_from AS $this->_var['referrals_item']):
?>
		        <li>
		          <div class="item-content">
		            <div class="item-inner">
		              <div class="item-title-row">
		                <div class="item-title"><?php echo $this->_var['referrals_item']['rel_user_name']; ?></div>
		              </div>
		              <div class="item-subtitle">注册时间:<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['referrals_item']['register_time'],
);
echo $k['name']($k['v']);
?></div>
					  <div class="item-subtitle">返利订单:<?php if ($this->_var['referrals_item']['type'] == 1): ?><?php echo $this->_var['referrals_item']['order_id']; ?><?php else: ?>注册奖励<?php endif; ?>  <?php echo $this->_var['referrals_item']['score']; ?>  <?php if ($this->_var['referrals_item']['pay_time'] > 0): ?>已发放<?php else: ?>未发放<?php endif; ?></div>
					  <div class="item-subtitle">发放时间:<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['referrals_item']['pay_time'],
);
echo $k['name']($k['v']);
?></div>
		            </div>
		          </div>
		        </li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		    </ul>
	    </div>
		<?php else: ?>
        	<div class="content-block-title">暂无邀请记录</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
 
<?php if ($this->_var['signPackage']): ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
      debug: false,
      appId: '<?php echo $this->_var['signPackage']['appId']; ?>',
      timestamp: <?php echo $this->_var['signPackage']['timestamp']; ?>,
      nonceStr: '<?php echo $this->_var['signPackage']['nonceStr']; ?>',
      signature: '<?php echo $this->_var['signPackage']['signature']; ?>',
      jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
      ]
  });
   wx.ready(function () {
    // 在这里调用 API
			<?php if ($this->_var['wx']['title']): ?>
			var wx_title="<?php echo $this->_var['wx']['title']; ?>";
			<?php else: ?>
			var wx_title='<?php echo $this->_var['seo_title']; ?>';
 			<?php endif; ?>
			 <?php if ($this->_var['wx']['desc']): ?>
			var wx_desc= '<?php echo $this->_var['wx']['desc']; ?>'; // 分享描述
			<?php else: ?>
			var wx_desc=  '<?php echo $this->_var['seo_description']; ?>'; // 分享描述
			<?php endif; ?>
			var wx_link='<?php echo $this->_var['wx_url']; ?>';
			<?php if ($this->_var['wx']['img_url']): ?>
			var  wx_img= "<?php echo $this->_var['wx']['img_url']; ?>"; // 分享图标
			<?php else: ?>
			var  wx_img=  '<?php echo $this->_var['site_logo']; ?>'; // 分享图标
			<?php endif; ?>
		wx.onMenuShareTimeline({
		 	title: wx_title, // 分享标题
		    link: wx_link, // 分享链接
 			imgUrl: wx_img, // 分享图标
 		    success: function () { 
		        // 用户确认分享后执行的回调函数
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    }
		});
		wx.onMenuShareAppMessage({
			title: wx_title, // 分享标题
 			desc: wx_desc, // 分享描述
 		    link: wx_link,  // 分享链接
 			imgUrl: wx_img, // 分享图标
 		    type: 'link', // 分享类型,music、video或link，不填默认为link
		   // dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () { 
		        // 用户确认分享后执行的回调函数
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    }
		});
  });
</script>
<?php endif; ?>
<script>
  //初始化侧栏禁止滑动打开
  $.config = {
    swipePanelOnlyClose:true
  }
</script>
<?php
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/sui_mobile/sm.min.js";
   	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/sui_mobile/sm-extend.min.js";
   	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/common_js/common_sui.js";

	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/sui_pull_to_refresh.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/check_idcard.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/switch_city.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deals_mall_cate.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/ajax_get_recommend_project.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/count_invest_money.js";
	// $this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/discover.js";

	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deal.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deal_investor_show.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_add_leader_info.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_focus.js";
	// $this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/refund.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/order_list.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/cart_pay.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/go_pay.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_investor_list.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/user_login.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/user_register.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_money_carry.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_recommend.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_project.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_support.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/account_stock_transfer.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/add_consignee.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/category.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deal_comment.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deal_log.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deals_index.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/home.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/invester_list.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/apply_investor.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/invite.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/message.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/pay_wx_jspay.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/deal_publish.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/edit_deal_item.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/project_follow.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings_bank.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings_index.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings_modify.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings_security.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/user_applicate_leader.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/user_getpassword.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/add_update.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/user_bind_mobile.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/settings.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/finance.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_deal.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_buyed_lc.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_expire_lc.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_expire_status.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_published_lc.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_record_lc.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_redeem.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_redeem_lc.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/licai/licai_uc_redeem_lc_status.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/score/score_check_order.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/score/score_good_show.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/score/score_goods_order.js";
	$this->_var['foot_pagejs'][] = $this->_var['TMPL_REAL']."/js/score/score_mall_index.js";
?>
<script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['foot_pagejs'],
);
echo $k['name']($k['v']);
?>"></script>
<?php echo $this->fetch('inc/sui_mobile_js.html'); ?>
</body>
</html>