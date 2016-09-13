
$(document).on("pageInit","#user-register", function(e, pageId, $page) {
	var code_timeer = null;
	var code_lefttime = 0 ;
	$("#user_register_form").find("input[name='submit_form']").on("click",function(){
		do_register_user();
	});
	$("#J_send_sms_verify").on("click",function(){
		send_mobile_verify_sms();
	});
	$("#J_send_email_verify").on("click",function(){
		email=$("#user_register_form").find("input[name='email']").val();
		send_email_verify(1,email,"#J_send_email_verify");
	});

	function send_mobile_verify_sms(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.alert("手机号码格式错误");	
			return false;
		}
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.alert("长度不能超过11位");	
			return false;
		}
		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.alert("手机号码不能为空");	
			return false;
		}

	   	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=check_field";  
		var query = new Object();
		query.field_name = "mobile";
		query.field_data = $.trim($("#settings-mobile").val());
		 
		$.ajax({ 
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1)
				{	
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
									$.showErr(sdata.info);
								return false;
							}
						}
					});	
				}
				else
				{	
				 	
					$.showErr(data.info);
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
			$("#J_send_sms_verify").on("click",function(){
				send_mobile_verify_sms();
			});
		}
		
	}
	function do_register_user()
	{
		if($.trim($("#user_register_form").find("input[name='user_name']").val()) == ""){
			$.alert("请输入会员名称");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_name']").val()).length < 4){
			$.alert("会员名称不少于4个字符");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_pwd']").val())=="")
		{
			$.alert("请输入登录密码");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_pwd']").val()).length < 4){
			$.alert("登录密码不少于4个字符");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val())=="")
		{
			$.alert("请输入确认密码");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val()) != $.trim($("#user_register_form").find("input[name='user_pwd']").val()))
		{
	 		$.alert("密码不一致");
			return false;
		}
		if(is_mobile){
			if($.trim($("#user_register_form").find("input[name='mobile']").val())=="")
			{
				$.alert("请输入手机号码");
				return false;
			}
		}
		
		if(is_mobile_verify){
			if($.trim($("#user_register_form").find("input[name='verify_coder']").val())=="")
			{
				$.alert("请输入手机验证码");
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
	 				$.alert("注册成功！",function(){
	 					href = ajaxobj.jump;
						$.router.loadPage(href);
	 				});
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
});
$(document).on("pageInit","#user-register_two", function(e, pageId, $page) {
	get_file_fun("card");		
	get_file_fun("credit_report");	
	get_file_fun("housing_certificate");

	$("#ajax_form_identify .ui-button").on('click',function(){
		var $obj=$(this).parent().parent().parent();
		var identify_name = $obj.find("input[name='identify_name']").val();
		var identify_number = $obj.find("input[name='identify_number']").val();	
		if(identify_name == ""){
			$.alert("身份证姓名不能为空！");
			return false;
		}
		if(identify_number == ""){
			$.alert("身份证号码不能为空！");
			return false;
		}
		var ajaxurl = $("#ajax_form_identify").attr("action");
		var query = new Object();
		query.ajax = $obj.find("input[name='ajax']").val();
		query.is_investor = $obj.find("a[name='is_investor'].cur").attr("avalue");
		query.identify_name = $obj.find("input[name='identify_name']").val();
		query.identify_number = $obj.find("input[name='identify_number']").val();
		query.card = $obj.find("input[name='card']").val();
		query.credit_report = $obj.find("input[name='credit_report']").val();
		query.housing_certificate = $obj.find("input[name='housing_certificate']").val();
		query.identity_conditions = $obj.find("input[name='identity_conditions']:checked").val();
		
		query.identify_business_name = $obj.find("input[name='identify_business_name']").val();
		query.bankLicense = $obj.find("input[name='bankLicense']").val();
		query.orgNo = $obj.find("input[name='orgNo']").val();
		query.businessLicense = $obj.find("input[name='businessLicense']").val();
		query.taxNo = $obj.find("input[name='taxNo']").val();
		query.contact = $obj.find("input[name='contact']").val();
		query.memberClassType = $obj.find("select[name='memberClassType']").val();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
});
$(document).on("pageInit","#user-wx_register", function(e, pageId, $page) {
	var code_timeer = null;
	var code_lefttime = 0 ;
	bind_user_register_wx();
	$("#J_send_sms_verify").on("click",function(){
		send_mobile_verify_sms();
	});
	$("#J_send_email_verify").on("click",function(){
		email=$("#user_register_form").find("input[name='email']").val();
		send_email_verify(2,email,"#J_send_email_verify");
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
		$("#user_register_form").find("input[name='submit_form']").on("click",function(){
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
					href = ajaxobj.jump;
					$.router.loadPage(href);
					
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
});
function SelectRegisterType(obj,i){
	$(obj).addClass("cur").siblings().removeClass("cur");
		switch(i){
		case 0:
			$("#identify_name_str").text("个人身份证姓名:");
			$(".gr_div").show();
			$(".enterprise_class_type").addClass("enterprise_style");
				break;
			case 1:
				$("#identify_name_str").text("法人身份证姓名:");
			$(".gr_div").hide();
				$(".enterprise_class_type").removeClass("enterprise_style");
				break;
	}
}