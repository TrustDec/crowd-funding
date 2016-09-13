$(document).on("pageInit","#user-user_bind_mobile", function(e, pageId, $page) {
	var code_timeer = null;
	$("#J_send_sms_verify").bind("click",function(){
		if($("#settings-mobile").val()==''){
			$.showErr("手机号码不能为空！");
			return false;
		}else{
			send_mobile_verify_sms();
		}
	});
	$("#verify_coder").bind("blur",function(){	
		if($(this).val()==''){
			$.showErr("验证码不能为空！");
			return false;
		}else{
			check_register_verifyCoder();
		}		
	});
	
	function send_mobile_verify_sms(){
		$("#J_send_sms_verify").unbind("click");
	
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
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
			$.showErr("手机号码不能为空!");
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}
	
		var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=1";
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
 		if($.trim($("#verify_coder").val())=="")
		{
			$.showErr("请输入验证码!");		
		}
		else
		{
			var mobile = $.trim($("#settings-mobile").val());
			var code = $.trim($("#verify_coder").val());
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
							//$.showSuccess("验证码正确!");
						}
						if(ajaxobj.status==0)
						{
							$.showErr("验证码不正确!");
						}
					}
				});
			}
		}
	}
	function save_mobile(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位!");	
			return false;
		}
 		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			return false;
		}
		if($.trim($("#verify_coder").val()).length == 0){
			$.showErr("验证码不能为空！");
			return false;
		}
		var mobile = $.trim($("#settings-mobile").val());
		var cid= $.trim($("#cid").val());
		var verify_coder=$.trim($("#verify_coder").val());
		var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=save_mobile";
		var query=new Object();
		query.mobile=mobile;
		query.cid=cid;
		query.verify_coder=verify_coder;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=APP_ROOT+"/index.php?ctl=ajax&act=three_seconds_jump&id="+cid;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});
		return false;
	}
});