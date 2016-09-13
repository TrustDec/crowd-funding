var code_timeer = null;
var code_lefttime = 0 ;
$(document).ready(function(){
	bind_user_register_wx();
	$("#J_send_sms_verify").bind("click",function(){
			
			send_mobile_verify_sms();
	});
	$("#J_send_email_verify").bind("click",function(){
				email=$("#user_register_form").find("input[name='email']").val();
				send_email_verify(2,email,"#J_send_email_verify");
 		});
 });
function send_mobile_verify_sms(){
 	
	if(!$.checkMobilePhone($("#settings-mobile").val()))
	{
		$.showErr("手机号码格式错误");	
		return false;
	}
	
	if(!$.maxLength($("#settings-mobile").val(),11,true))
	{
		$.showErr("长度不能超过11位");	
		return false;
	}
	
	
	if($.trim($("#settings-mobile").val()).length == 0)
	{				
		$.showErr("手机号码不能为空");	
		return false;
	}

   	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=check_field&is_verify=1";  
	var query = new Object();
		query.field_name = "mobile";
		query.field_data = $.trim($("#settings-mobile").val());
		 
		
		var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=0";
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
			code_timeer = setTimeout(code_lefttime_func,1000);
		}
		else{
			code_lefttime = 60;
			$("#J_send_sms_verify").val("发送验证码");
			
			$("#J_send_sms_verify").css({"color":"#fff"});
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
		}
		
	}	
	
	
function bind_user_register_wx() {
	$("#user_register_form").find("input[name='submit_form']").bind("click",function(){
		do_register_user_wx();
	});
	$("#user_register_form").bind("submit",function(){
		return false;
	});
}

function do_register_user_wx(){
	if(is_mobile){
		if($.trim($("#user_register_form").find("input[name='mobile']").val())=="")
		{
			$.showErr("请输入手机号码");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='verify_coder']").val())=="")
		{
			$.showErr("请输入验证码");
			return false;
		}
	}
 	if (!is_mobile) {
		if ($.trim($("#user_register_form").find("input[name='email']").val()) == "") {
			$.showErr("请输入邮箱地址");
			return false;
		}
	}
	 
 	var ajaxurl = $("form[name='user_register_form']").attr("action");
	var query = $("form[name='user_register_form']").serialize() ;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
 			if(ajaxobj.status==1)
			{
 				$.showSuccess("注册成功！自动跳转");
				location.href = ajaxobj.jump;
				
			}
			else
			{
 				$.showErr(ajaxobj.info);							
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}
