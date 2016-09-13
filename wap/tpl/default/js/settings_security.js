$(document).on("pageInit","#settings-security", function(e, pageId, $page) {
	$(".J_setting").on('click',function(){
		J_setting_security(this);
	});
	function J_setting_security(obj){
		var ajaxurl="";
		var setting_title="";
		if($(obj).attr("rel")=="setting_username"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_username";
			setting_title="设置昵称";
		}
		else if($(obj).attr("rel")=="setting_pwd"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_pwd";
			setting_title="登录密码";
			var ajax_fun = function(){
				$("#ajax_form_password").find(".ui-button").bind("click",function(){
					if($("input[name='user_old_pwd']").val()==""){
						$.toast("请输入旧密码",1000);
						return false;
					}
					if($("input[name='user_pwd']").val()==""){
						$.toast("请输入新密码",1000);
						return false;
					}
					if(($("input[name='user_pwd']").val()).length<4){
						$.toast("密码不能低于四位",1000);
						return false;
					}
					if($("input[name='confirm_user_pwd']").val()==""){
						$.toast("请输入确认密码",1000);
						return false;
					}
					ajax_form("#ajax_form_password");
				});
			}
		}
		else if($(obj).attr("rel")=="setting_email"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_email";
			setting_title="绑定邮箱";
			var ajax_fun = function(){
				$("#email_verify_code").bind("click",function(){
					step=$("#ajax_form_email").find("input[name='step']").val();
					if(step==1){
						email=$("#ajax_form_email").find("input[name='email']").val();
						send_email_verify(step,email,"#email_verify_code");
					}
					else{
						if(step==2){
							send_email_verify(step,'',"#email_verify_code");
						}
					}
				});
				$("#ajax_form_email").find(".ui-button").bind("click",function(){
					if(user_info_email){
						if($("input[name='verify_coder']").val()==""){
							$.toast("请输入邮件验证码",1000);
							return false;
						}
						if($("input[name='email']").val()==""){
							$.toast("请输入新邮箱",1000);
							return false;
						}
					}
					else{
						if($("input[name='email']").val()==""){
							$.toast("请输入新邮箱",1000);
							return false;
						}
						if($("input[name='verify_coder']").val()==""){
							$.toast("请输入邮件验证码",1000);
							return false;
						}
					}
					ajax_form("#ajax_form_email");
				});
			}
		}
		else if($(obj).attr("rel")=="setting_mobile"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_mobile";
			setting_title="绑定手机";
			var ajax_fun = function(){
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms_custom($.trim($("#settings-mobile-type").val()),$.trim($("#settings-mobile").val()),"#J_send_sms_verify");
				});

				$("#ajax_form_mobile .ui-button").bind('click',function(){
					var $obj=$(this).parent().parent().parent();
					var mobile=$obj.find("input[name='mobile']").val();
					var verify_coder=$obj.find("input[name='verify_coder']").val();
					if(user_info_mobile){
						if($.trim(verify_coder) == ""){
							$.toast("请输入手机验证码",1000);
							return false;
						}
						if($.trim(mobile) == ""){
							$.toast("请输入新手机号",1000);
							return false;
						}
					}
						
					else{
						if($.trim(mobile) == ""){
							$.toast("请输入手机号",1000);
							return false;
						}
						if($.trim(verify_coder) == ""){
							$.toast("请输入手机验证码",1000);
							return false;
						}
					}
					ajax_form("#ajax_form_mobile");
				});
			}
		}
		else{
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_paypwd";
			setting_title="付款密码";
			var ajax_fun = function(){
				$("#J_send_sms_verify_pay").bind("click",function(){
					send_mobile_verify_sms_custom(2,'',"#J_send_sms_verify_pay");
				});
				$("#ajax_form_paypassword .ui-button").bind('click',function(){
					var $obj=$(this).parent().parent().parent();
					var paypassword=$obj.find("input[name='paypassword']").val();
					var confirm_pypassword=$obj.find("input[name='confirm_pypassword']").val();
					var verify=$obj.find("input[name='verify']").val();
					if($.trim(paypassword)){
						if(paypassword.length <= 5){
							$.toast("付款密码长度不少于6位",1000);
							return false;
						}
					}
					else{
						$.toast("请输入付款密码",1000);
						return false;
					}
					if($.trim(confirm_pypassword)){
						if($.trim(confirm_pypassword) != $.trim(paypassword)){
							$.toast("两次输入密码不一致",1000);
							return false;
						}
					}
					else{
						$.toast("请输入确认密码",1000);
						return false;
					}
					if($.trim(verify) == ""){
						$.toast("请输入手机验证码",1000);
						return false;
					}
					ajax_form("#ajax_form_paypassword");
				});
			}
		}
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
		    		$.modal({
						title: setting_title,
				      	text: ajaxobj.html,
				      	buttons: []
					});
					ajax_fun();
				}
			    if(ajaxobj.status==2){
					$.showErr(ajaxobj.info);
				}
			}
		});
	}
});
$(document).on("pageInit","#settings-setting_id", function(e, pageId, $page) {
	get_file_fun("identify_positive");
	get_file_fun("identify_nagative");
	get_file_fun("identify_business_licence");
	get_file_fun("identify_business_code");
	get_file_fun("identify_business_tax");
	get_file_fun("card");		
	get_file_fun("credit_report");	
	get_file_fun("housing_certificate");	
	bind_ajax_form_custom(".ajax_form_identify");
	$("#J_send_sms_verify_iden").bind("click",function(){
		send_mobile_verify_sms_custom(2,'',"#J_send_sms_verify_iden");
	});
	$(".ajax_form_identify").find("input[name='is_investor']").bind('click',function(){
		$("#qy_div").toggle();
		get_file_fun("identify_business_licence");
		get_file_fun("identify_business_code");
		get_file_fun("identify_business_tax");
		if($(this).val()==2){
			$("#identify_name_str").html("法人身份证姓名：");
			$(".gr_div").hide();
		}else{
			$("#identify_name_str").html("个人身份证姓名：");
			$(".gr_div").show();
		}
	});
});