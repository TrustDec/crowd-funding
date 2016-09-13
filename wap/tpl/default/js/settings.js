$(document).on("pageInit","#settings-invite", function(e, pageId, $page) {
	$(".J_del_invite").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		del_invite(invite_item_id);
	});
	function del_invite(id){
		var post_url=APP_ROOT+"/index.php?ctl=settings&act=del_invite&id="+id;
		$.ajax({
			url:post_url,
			dataType:"json",
			type:"post",
			success:function(data){
				if(data.status==1){
					$.showSuccess(data.info,function(){
						$.router.loadPage(window.location.href);
					});
				}else{
					$.showErr(data.info);
				}
			}
		});
	}
});
$(document).on("pageInit","#settings-password", function(e, pageId, $page) {
	if(USER_VERIFY == 2){
		$(".J_save_mobile_password").on('click',function(){
			save_mobile_password();
		});

		var code_timeer = null;
		var code_lefttime = 0;

		$("#J_send_sms_verify").on("click",function(){
			send_mobile_verify_sms();
		});
		$("#setting_mobile_pwd_form").find("input[name='verify_coder']").bind("blur",function(){
			check_register_verifyCoder();
		});
		function form_error(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_tip'>"+str+"</div>");
		}
		function form_success(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_success'>"+str+"</div>");
		}
		function send_mobile_verify_sms(){
			$("#J_send_sms_verify").unbind("click");
		
			if(!$.checkMobilePhone($("#settings-mobile").val()))
			{
				form_error($("#settings-mobile"),"手机号码格式错误");	
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
			
			
			if(!$.maxLength($("#settings-mobile").val(),11,true))
			{
				$("#settings-mobile").focus();
				$("#settings-mobile").next().show().text("长度不能超过11位");			
				$("#settings-mobile").next().css({"color":"red"});
				$("#J_send_sms_verify").bind("click",function(){
					
					send_mobile_verify_sms();
				});
				return false;
			}
	 		if($.trim($("#settings-mobile").val()).length == 0)
			{				
				form_error($("#settings-mobile"),"手机号码不能为空");
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
		
			var sajaxurl ='{url_wap r="ajax#send_mobile_verify_code"}';
			var squery = new Object();
			squery.mobile = $.trim($("#settings-mobile").val());
			$.ajax({ 
				url: sajaxurl,
				data:squery,
				type: "POST",
				dataType: "json",
				success: function(sdata){
					if(sdata.status==1)
					{
						code_lefttime = 60;
						code_lefttime_func();
						$.showSuccess(sdata.info);
						return false;
					}
					else
					{
							
						$("#J_send_sms_verify").bind("click",function(){
							send_mobile_verify_sms();
						});
						$.showErr(sdata.info);
						return false;
					}
				}
			});
		}
		function code_lefttime_func(){
			clearTimeout(code_timeer);
			$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
			$("#J_send_sms_verify").css({"color":"#f1f1f1"});
			code_lefttime--;
			if(code_lefttime >0){
				$("#J_send_sms_verify").attr("disabled","true");
				code_timeer = setTimeout(code_lefttime_func,1000);
			}
			else{
				code_lefttime = 60;
				$("#J_send_sms_verify").val("发送验证码");
				$("#J_send_sms_verify").attr("disabled","false");
				$("#J_send_sms_verify").css({"color":"#fff"});
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
			}
		}
		//检查验证码
		function check_register_verifyCoder(){
	 		if($.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val())=="")
			{
				form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"请输入验证码");		
			}
			else
			{
				var mobile = $.trim($("#setting_mobile_pwd_form").find("input[name='mobile']").val());
				var code = $.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val());
				if(mobile!=""||code!=""){
					var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
					var query = new Object();
					query.mobile = mobile;
					query.code = code;
					$.ajax({
						url: ajaxurl,
						dataType: "json",
						data:query,
						type: "POST",
						success:function(ajaxobj){
							if(ajaxobj.status==1)
							{
								form_success($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码正确");
							}
							if(ajaxobj.status==0)
							{
								form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码不正确");
							}
						}
					});
				}
			}
		}
		
		function save_mobile_password(){
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var verify_coder=$("#verify_coder").val();
			var post_url=APP_ROOT+"/index.php?ctl=settings&act=save_mobile_password";
			var query = new Object();
				query.user_pwd = user_pwd;
				query.confirm_user_pwd = confirm_user_pwd;
				query.verify_coder=verify_coder;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
					success:function(data){
						if(data.info!=null){
							alert(data.info);
						}
						else{
							if(data.status==1){
								alert("保存成功!",function(){
									$.router.loadPage(window.location.href);
								});
							}
							if(data.status==0){
								alert("保存失败!");
							}
						}
				},error:function(){
					alert("系统繁忙，稍后请重试!");
				}
			});
		}
	}
	else{
		$(".J_save_password").on('click',function(){
			save_password();
		});
		function save_password(){
			var user_old_pwd=$("#user_old_pwd").val();
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var post_url='{url_wap r="settings#save_password"}';
			
			var query=new Object();
			query.user_old_pwd=user_old_pwd;
			query.user_pwd=user_pwd;
			query.confirm_user_pwd=confirm_user_pwd;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
				success:function(data){
					if(data.info!=null){
						$.showErr(data.info);
					}else{
						if(data.status==1){
							$.showSuccess("保存成功!",function(){
								$.router.loadPage(window.location.href);
							});
						}
						if(data.status==0){
							$.showSuccess("保存失败!");
						}
					}
				},
				error:function(){
					$.showErr("系统繁忙，稍后请重试!");
				}
			});
			return false;
		}
	}
});