$(document).on("pageInit","#investor-index", function(e, pageId, $page) {
	$(".J_help_item_show").on('click',function(){
		help_item();
	});
	$("input[name='submit_form']").on('click',function(){
		if(mobile_is_bind){
			investor_save_mobile2();
		}
		else{
			investor_save_mobile();
		}
	});
	$(".tab-nav li").live('click',function(){
		$(".tab-nav li").removeClass("current");
		$(this).addClass("current").siblings().removeClass("current");
	});

	var code_timeer = null;
	screening_identity_type();
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
			//$.showErr("验证码不能为空！");
			return false;
		}else{
			check_register_verifyCoder();
		}		
	});
	//需要同意条款
	$("#J_agreement").bind("click",function(){
		if($("#J_agreement").attr("checked")){
			$("#ui-button").attr("disabled",false);
			$("#ui-button").addClass("theme_color");
		}else{
			$("#ui-button").attr("disabled",true);
			$("#ui-button").removeClass("theme_color");
		}
	});
	//筛选身份类型
	function screening_identity_type(){		
		$(".ui_check").click(function(){
			if($(this).find("input").attr("type")=="radio"){
				var rel=$(this).attr("rel");
				$(".ui_check[rel='"+rel+"']").removeClass("ui_checked");
				$(".ui_check[rel='"+rel+"'] input").attr("checked",false);
				$(this).addClass("ui_checked");
				$(this).find("input").attr("checked","checked");
			}
		});
	}

	//发送验证码短信
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
			$.showErr("长度不能超过11位！");	
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
	//短信提示时间	
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

	//投资者手机验证
	function investor_save_mobile(){
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
		var investor_two_url=$.trim($("input[name='investor_two_url']").val());
		var is_investor=$.trim($("#investor_id .current").find("input[name='is_investor']").val());
		var mobile = $.trim($("#settings-mobile").val());
		var verify_coder=$.trim($("#verify_coder").val());
		var ajaxurl = APP_ROOT+'/index.php?ctl=user&act=investor_save_mobile';
		var query=new Object();
		query.is_investor=is_investor;
		query.mobile=mobile;
		query.verify_coder=verify_coder;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=investor_two_url;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});

	}

	//投资者手机验证
	function investor_save_mobile2(){
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
		var investor_two_url=$.trim($("input[name='investor_two_url']").val());
		var is_investor=$.trim($("#investor_id .current").find("input[name='is_investor']").val());
		var mobile = $.trim($("#settings-mobile").val());
		var ajaxurl = APP_ROOT+'/index.php?ctl=user&act=investor_save_mobile';
		var query=new Object();
		query.is_investor=is_investor;
		query.mobile=mobile;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=investor_two_url;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});
	}
	// 条款内容
	function help_item()
	{
		var html_var=$("#show_html").html();
		if(html_var){
			$.alert(html_var);
		}	
	}
});

$(document).on("pageInit","#investor-investor_two", function(e, pageId, $page) {
	if(user_info_is_investor == 1){  // investor_personal.html  个人投资者认证
		// 上传图片
		get_file_fun("idcard_zheng");
		get_file_fun("idcard_fang");

		$("#idcard_number").bind("blur",function(){
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if(IdentityCodeValid($("#idcard_number").val())===true){
				//$.showSuccess("身份证号可以使用！");
			}else{
				$.showErr("请正确填写身份证号！");
				return false;
			}
		});
		$(".submit_investor_personal").on('click',function(){
			check_personal_data();
		});
		function check_personal_data(){
			if($("#real_name").val()==''){
				$.showErr("请输入真实姓名！");
				return false;
			}
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if($("#idcard_zheng_u").val()==''){
				$.showErr("请上传身份证正面照片！");
				return false;
			}
			if($("#idcard_fang_u").val()==''){
				$.showErr("请上传身份证背面照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var identify_name=$("#real_name").val();
			var identify_number=$("#idcard_number").val();
			var ajaxurl=$("#ajaxurl").val();
			var idcard_zheng_u=$("#idcard_zheng_u").val();
			var idcard_fang_u=$("#idcard_fang_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=identify_name;
			query.identify_number=identify_number;
			query.idcard_zheng_u=idcard_zheng_u;
			query.idcard_fang_u=idcard_fang_u;
			$.ajax({
				url: ajaxurl,
				data:query,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(ajaxobj)
				{
					$.showErr("系统繁忙，请您稍后重试！")
					return false;
				}
			});
		}
	}
	if(user_info_is_investor == 2){  // investor_agency.html  机构投资者认证
		// 上传图片
		get_file_fun("identify_business_licence");
		get_file_fun("identify_business_code");
		get_file_fun("identify_business_tax");

		$("#identify_business_name").bind("blur",function(){
			if(!$("#identify_business_name").val()){
				$.showErr("机构名称不能为空!");
			}
		});
		$(".submit_investor_agency").on('click',function(){
			check_agency_data();
		});
		function check_agency_data(){
			if($("#identify_business_name").val()==''){
				$.showErr("请输入机构名称！");
				return false;
			}
			if($("#identify_business_licence_u").val()==''){
				$.showErr("请上传营业执照！");
				return false;
			}
			if($("#identify_business_code_u").val()==''){
				$.showErr("请上传组织机构代码证照片！");
				return false;
			}
			if($("#identify_business_tax_u").val()==''){
				$.showErr("请上传税务登记证照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var ajaxurl=$("#ajaxurl").val();
			var identify_business_name=$("#identify_business_name").val();
			var identify_business_licence_u=$("#identify_business_licence_u").val();
			var identify_business_code_u=$("#identify_business_code_u").val();
			var identify_business_tax_u=$("#identify_business_tax_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=$("#identify_name").val();
			query.identify_number=$("#identify_number").val();
			
			query.identify_business_name=identify_business_name;	
			query.identify_business_licence_u=identify_business_licence_u;
			query.identify_business_code_u=identify_business_code_u;
			query.identify_business_tax_u=identify_business_tax_u;
			$.ajax({
				url:ajaxurl,
				data:query,
				dataType:"json",
				type:"post",
				success:function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(){
					$.showErr("系统繁忙，请您稍后重试！");
					return false;
				}
			});	
		}

		// 上传图片
		get_file_fun("idcard_zheng");
		get_file_fun("idcard_fang");

		$("#idcard_number").bind("blur",function(){
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if(IdentityCodeValid($("#idcard_number").val())===true){
				//$.showSuccess("身份证号可以使用！");
			}else{
				$.showErr("请正确填写身份证号！");
				return false;
			}
		});
		$(".submit_investor_personal").on('click',function(){
			check_personal_data();
		});
		function check_personal_data(){
			if($("#real_name").val()==''){
				$.showErr("请输入真实姓名！");
				return false;
			}
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if($("#idcard_zheng_u").val()==''){
				$.showErr("请上传身份证正面照片！");
				return false;
			}
			if($("#idcard_fang_u").val()==''){
				$.showErr("请上传身份证背面照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var identify_name=$("#real_name").val();
			var identify_number=$("#idcard_number").val();
			var ajaxurl=$("#ajaxurl").val();
			var idcard_zheng_u=$("#idcard_zheng_u").val();
			var idcard_fang_u=$("#idcard_fang_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=identify_name;
			query.identify_number=identify_number;
			query.idcard_zheng_u=idcard_zheng_u;
			query.idcard_fang_u=idcard_fang_u;
			$.ajax({
				url: ajaxurl,
				data:query,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(ajaxobj)
				{
					$.showErr("系统繁忙，请您稍后重试！")
					return false;
				}
			});
		}
	}
});

$(document).on("pageInit","#user-investor_result", function(e, pageId, $page) {
	delayURL();    
    function delayURL() { 
        var delay = $("#time").html();
 		var t = setTimeout("delayURL()", 1000);
        if (delay > 0) {
            delay--;
            $("#time").html(delay);
        } else {
     		clearTimeout(t); 
            href ='{url_wap r="index#index"}';
			$.router.loadPage(href);
        }        
    } 
});