<?php if ($this->_var['is_app']): ?>
<script type="text/javascript">
	function deal_left_app_msg(){
		//$.alert(<?php echo $this->_var['is_back']; ?>);
		//$.alert(<?php echo $this->_var['back_url']; ?>);
		$.closeModal();
	  	<?php if ($this->_var['is_back'] == 1): ?>
			$.router.back();
		<?php elseif ($this->_var['is_back'] == 2): ?>
			$.router.loadPage("<?php echo $this->_var['back_url']; ?>");
		<?php else: ?>
		 	$.router.loadPage("<?php
echo parse_url_tag_wap("u:index|"."".""); 
?>");
		<?php endif; ?>
    }
	function deal_login_init(user_name,user_pwd){
		var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=do_login&ajax=1&auto_login=1";
		var query = new Object();
		query.email = user_name;
		query.user_pwd = user_pwd;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1||ajaxobj.status==2)
				{
					App.login_success('{"user_name":"'+user_name+'","user_pwd":"'+user_pwd+'"}');
 				}
				else
				{
					App.logout('{"user_name":"'+user_name+'","user_pwd":"'+user_pwd+'"}');	
				}
			}
		});
	}
	</script>
<?php else: ?>
<script type="text/javascript">
   	 login_status = $("#login_status").attr('title');
	<?php if ($this->_var['is_login'] || $this->_var['is_loginout']): ?>
		syn_user_info()
	<?php endif; ?>
 
	function syn_user_info()
	{	
 		<?php if ($this->_var['is_login']): ?>
 		  $("#login_status_info").html('您好，<span style="color:#fff;"><?php echo $this->_var['user_info']['user_name']; ?></span>');
		  $("#login_status_url").html('<a href="<?php
echo parse_url_tag_wap("u:settings#index|"."".""); 
?>" class="close-panel">用户中心</a>');
		<?php else: ?>
		  $("#login_status_info").html('您好，您还没有登录哦');
		  $("#login_status_url").html('<a href="<?php
echo parse_url_tag_wap("u:user#login|"."".""); 
?>"  class="close-panel <?php if ($this->_var['is_weixin']): ?>external<?php endif; ?>">登录/注册</a>');
		<?php endif; ?>
	}	
</script>
<?php endif; ?>
