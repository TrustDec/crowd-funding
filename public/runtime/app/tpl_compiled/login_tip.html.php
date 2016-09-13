<ul class="head-sub-nav" id="head-sub-nav">
<?php if ($this->_var['user_info']): ?>
	<?php if ($this->_var['app']): ?>
	<li class="app-download">
		<a href="javascript:void(0);">
			<i class="icon iconfont">&#xe621;</i>客户端
		</a>
		<?php if ($this->_var['app'] [ 'is_app' ]): ?>
		<div class="mobile-wrap pop-up mobile-wrap-r">
			<img class="qr" src="<?php echo $this->_var['app']['app_url_logo']; ?>" alt="手机端">
			<div class="btns">
				<span>下载<?php echo $this->_var['site_name']; ?>客户端</span>
				<?php if ($this->_var['app'] [ 'ios_down_url' ]): ?>
				<a href="<?php echo $this->_var['app']['app_file_url']; ?>" target="_blank" title="客户端">
					<i class="icon iconfont">&#xe602;</i>App Store
				</a>
				<?php endif; ?>
				<?php if ($this->_var['app'] [ 'android_filename' ]): ?>
				<a href="<?php echo $this->_var['app']['app_file_url']; ?>" target="_blank" title="客户端">
					<i class="icon iconfont">&#xe600;</i>Android
				</a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</li>
	<?php endif; ?>
	<li class="msg-menu">
		<a href="javascript:void(0);" id="mymessage" class="mymessage cssType_title"><i class="icon iconfont">&#xe611;</i>&nbsp;消息</a>
		<div class="msg-wrap pop-up">
			<div class="menu">
				<a href="<?php
echo parse_url_tag("u:news#fav|"."".""); 
?>" class="first-of-type">关注动态</a>
				<a href="<?php
echo parse_url_tag("u:comment|"."".""); 
?>">查看评论</a>
				<a href="<?php
echo parse_url_tag("u:message|"."".""); 
?>">查看私信(<?php echo $this->_var['USER_MESSAGE_COUNT']; ?>)</a>
				<a href="<?php
echo parse_url_tag("u:notify|"."".""); 
?>">查看通知(<?php echo $this->_var['USER_NOTIFY_COUNT']; ?>)</a>
				<a href="<?php
echo parse_url_tag("u:invite|"."".""); 
?>">查看邀请(<?php echo $this->_var['USER_INVITE_COUNT']; ?>)</a>	
			</div>
		</div>
	</li>
	<li class="user-menu">
		<a href="javascript:void(0);" id="mycenter" class="head-avatar">
			<img id="avatar" src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['user_info']['id'],
  'type' => 'middle',
);
echo $k['name']($k['uid'],$k['type']);
?>" /> 
		</a>
		<div class="menu-wrap pop-up">
			<a class="brief" href="<?php
echo parse_url_tag("u:settings|"."".""); 
?>" >
				<img id="avatar" src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['user_info']['id'],
  'type' => 'middle',
);
echo $k['name']($k['uid'],$k['type']);
?>" />
				<span> <?php echo $this->_var['user_info']['user_name']; ?> </span>
			</a>
			<div class="menu">
				<a href="<?php
echo parse_url_tag("u:home|"."id=".$this->_var['user_info']['id']."".""); 
?>" class="first-of-type">我的主页</a>
				<?php if (app_conf ( "IS_FINANCE" ) == 1): ?>
				<a href="<?php
echo parse_url_tag("u:finance#company_create|"."".""); 
?>">创建公司</a>
				<a href="<?php
echo parse_url_tag("u:finance#company_manage|"."".""); 
?>">我管理的公司</a>
				<a href="<?php
echo parse_url_tag("u:finance#company_focus|"."".""); 
?>">我关注的公司</a>
				<?php endif; ?>
				<a href="<?php
echo parse_url_tag("u:home|"."".""); 
?>">账户管理</a>
				<a href="<?php 
$k = array (
  'name' => 'get_center_deal_url',
);
echo $k['name']();
?>">项目管理</a>
				<a href="<?php
echo parse_url_tag("u:settings|"."".""); 
?>">个人设置</a>
				<a href="<?php
echo parse_url_tag("u:user#loginout|"."".""); 
?>" title="退出" id="user_login_out">退出</a>
			</div>
		</div>
	</li>
<?php else: ?>
	<?php if ($this->_var['app']): ?>
	<li class="app-download">
		<a href="javascript:void(0);">
			<i class="icon iconfont">&#xe621;</i>客户端
		</a>
		<?php if ($this->_var['app'] [ 'is_app' ]): ?>
		<div class="mobile-wrap pop-up">
			<img class="qr" src="<?php echo $this->_var['app']['app_url_logo']; ?>" alt="手机端">
			<div class="btns">
				<span>下载<?php echo $this->_var['site_name']; ?>客户端</span>
				<?php if ($this->_var['app'] [ 'ios_down_url' ]): ?>
				<a href="<?php echo $this->_var['app']['app_file_url']; ?>" target="_blank" title="客户端">
					<i class="icon iconfont">&#xe602;</i>App Store
				</a>
				<?php endif; ?>
				<?php if ($this->_var['app'] [ 'android_filename' ]): ?>
				<a href="<?php echo $this->_var['app']['app_file_url']; ?>" target="_blank" title="客户端">
					<i class="icon iconfont">&#xe600;</i>Android
				</a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</li>
	<?php endif; ?>
	<li class="login-actions">
		<a title="登录" href="javascript:void(0)" id="show_pop_login" class="log Js-showLogin"><i class="icon iconfont">&#xe617;</i>&nbsp;登录&nbsp;/&nbsp;</a><?php if (app_conf ( "USER_INVESTMENT" ) == 1): ?><a href="<?php
echo parse_url_tag("u:user#register|"."".""); 
?>" title="创业者注册">创业者注册&nbsp;/&nbsp;</a><a href="<?php
echo parse_url_tag("u:user#register|"."".""); 
?>" title="投资者注册" style="padding:0 6px;">投资者注册</a><?php endif; ?><?php if (app_conf ( "USER_INVESTMENT" ) == 0): ?><a href="<?php
echo parse_url_tag("u:user#register|"."".""); 
?>" title="注册" class="reg f_red">注册</a><?php endif; ?>
	</li>
<?php endif; ?>
</ul> 
 <!-- <?php if ($this->_var['USER_NOTIFY_COUNT'] > 0 || $this->_var['USER_MESSAGE_COUNT'] > 0): ?>
	<?php if ($this->_var['HIDE_USER_NOTIFY'] == 0): ?>
		<div id="user_notify_tip" style="position:absolute; z-index:1; display:none; margin-top:45px;">		
			<div class="notify_tip_box1" id="close_user_notify">
				<div class="close_user_notify1"></div>
				<?php if ($this->_var['USER_NOTIFY_COUNT'] > 0): ?>
				<span><a href="<?php
echo parse_url_tag("u:notify|"."".""); 
?>">您有 <font><?php echo $this->_var['USER_NOTIFY_COUNT']; ?></font> 条新通知</a></span>
				<?php endif; ?>
				<?php if ($this->_var['USER_MESSAGE_COUNT'] > 0): ?>
				<span><a href="<?php
echo parse_url_tag("u:message|"."".""); 
?>">您有 <font><?php echo $this->_var['USER_MESSAGE_COUNT']; ?></font> 条新信息</a></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>  -->
<script type="text/javascript">
$(function(){
	var login_pop_ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=login";
	$.get(login_pop_ajaxurl, function(data) {
		var login_pop_html = data;
    	loginDialog(login_pop_html);
	});
});
</script>