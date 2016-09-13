$(document).ready(function(){
	bind_user_mobile_register();	
});
function bind_user_mobile_register()
{
	
	$("#user_register_form_by_mobile").submit(function(){
		user_register();
		return false;
	});
	$("#user_register_form_by_mobile").find("input[name='mobile']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			user_register();
			return false;
		}
	});
	$("#user_register_form_by_mobile").find("input[name='user_pwd']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			user_register();
			return false;
		}
	});
	$("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			user_register();
			return false;
		}
	});
	$("#user_register_form_by_mobile").find("input[name='user_name']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			user_register();
			return false;
		}
	});
	
	$("#user_register_form_by_mobile").find("input[name='mobile']").bind("blur",function(){
		check_register_mobile();
	});
	$("#user_register_form_by_mobile").find("input[name='user_name']").bind("blur",function(){
		check_register_user_name();
		
	});
	$("#user_register_form_by_mobile").find("input[name='user_pwd']").bind("blur",function(){
		check_register_user_pwd();
		
	});
	$("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']").bind("blur",function(){
		check_register_confirm_user_pwd();
		
	});
	
}


function check_register_mobile()
{
	if($.trim($("#user_register_form_by_mobile").find("input[name='mobile']").val())=="")
	{
		form_error($("#user_register_form_by_mobile").find("input[name='mobile']"),"请输入手机号码");		
	}
	else
	{
		check_field($("#user_register_form_by_mobile").find("input[name='mobile']"),"mobile",$("#user_register_form_by_mobile").find("input[name='mobile']").val());
	}
}

function check_register_user_name()
{
	if($.trim($("#user_register_form_by_mobile").find("input[name='user_name']").val())=="")
	{
		form_error($("#user_register_form_by_mobile").find("input[name='user_name']"),"请输入会员帐号");
	}
	else
	{
		check_field($("#user_register_form_by_mobile").find("input[name='user_name']"),"user_name",$("#user_register_form_by_mobile").find("input[name='user_name']").val());
	}
}

function check_register_user_pwd()
{
	if($.trim($("#user_register_form_by_mobile").find("input[name='user_pwd']").val())=="")
	{
		form_error($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"请输入会员密码");
	}
	else if($.trim($("#user_register_form_by_mobile").find("input[name='user_pwd']").val()).length<4)
	{
		form_error($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"密码不得小于四位");
	}
	else
	{
		form_success($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"");
	}
}

function check_register_confirm_user_pwd()
{
	if($.trim($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']").val())!=$.trim($("#user_register_form_by_mobile").find("input[name='user_pwd']").val()))
	{
		form_error($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']"),"确认密码失败");
	}
	else
	{
		form_success($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']"),"");
	}
}

var is_submiting = false;
function user_register()
{
	
	if(!is_submiting)
	{
		is_submiting = true;
		
		var user_name = $.trim($("#user_register_form_by_mobile").find("input[name='user_name']").val());
		var user_pwd = $.trim($("#user_register_form_by_mobile").find("input[name='user_pwd']").val());
		var confirm_user_pwd = $.trim($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']").val());
		var mobile = $.trim($("#user_register_form_by_mobile").find("input[name='mobile']").val());
		var user_type = $.trim($("#user_register_form_by_mobile").find("select[name='select_box']").val());
		var user_level=$.trim(1);
		if(user_name==""){
			is_submiting = false;
			form_error($("#user_register_form_by_mobile").find("input[name='user_name']"),"请输入会员帐号");
			return false;
		}
		else{
			form_success($("#user_register_form_by_mobile").find("input[name='user_name']"),"");
		}
		
		
		if(mobile==""){
			is_submiting = false;
			form_error($("#user_register_form_by_mobile").find("input[name='mobile']"),"请输入手机");
			return false;
		}
		else{
			form_success($("#user_register_form_by_mobile").find("input[name='mobile']"),"");
		}
		
		if(user_pwd==""){
			is_submiting = false;
			form_error($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"请输入会员密码");	
			return false;
		}
		else if(user_pwd.length<4){
			is_submiting = false;
			form_error($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"密码不得小于四位");
			return false;
		}	
		else
			form_success($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"");
		
		
		if(user_pwd==confirm_user_pwd)
		{
			form_success($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']"),"");
		}
		else
		{
			is_submiting = false;
			form_error($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']"),"确认密码失败");
			return false;
		}
		var verify_coder = "";
		if($("#user_register_form_by_mobile #settings-mobile-code").length>0)
		{
			verify_coder = $("#user_register_form_by_mobile").find("input[name='verify_coder']").val();
			if(verify_coder==""){
				is_submiting = false;
				form_error($("#user_register_form_by_mobile").find("input[name='verify_coder']"),"请输入验证码");
				return false;
			}
			else{
				form_success($("#user_register_form_by_mobile").find("input[name='verify_coder']"),"");
			}
		}
		
		
		var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=user_register";
		var query = new Object();
			query.mobile = mobile;
			query.user_name = user_name;
			query.user_pwd = user_pwd;
			query.confirm_user_pwd = confirm_user_pwd;
			query.verify_coder = verify_coder;
			query.user_type=user_type;
			query.user_level=user_level;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					form_success($("#user_register_form_by_mobile").find("input[name='mobile']"),"");
					form_success($("#user_register_form_by_mobile").find("input[name='user_name']"),"");
					form_success($("#user_register_form_by_mobile").find("input[name='user_pwd']"),"");
					form_success($("#user_register_form_by_mobile").find("input[name='confirm_user_pwd']"),"");
					form_success($("#user_register_form_by_mobile").find("select[name='user_type']"),"");
					location.href = ajaxobj.jump;
				}
				else
				{
					is_submiting = false;
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							location.href = APP_ROOT+"/";
						});
					}
					for(var i=0;i<ajaxobj.data.length;i++)
					{
						 if(ajaxobj.data[i].type=="form_success")
						 {
							 form_success($("#user_register_form_by_mobile").find("input[name='"+ajaxobj.data[i].field+"']"),"");
						 }
						 if(ajaxobj.data[i].type=="form_error")
						 {
							 form_error($("#user_register_form_by_mobile").find("input[name='"+ajaxobj.data[i].field+"']"),ajaxobj.data[i].info);
						 }
						 if(ajaxobj.data[i].type=="form_tip")
						 {
							 form_tip($("#user_register_form_by_mobile").find("input[name='"+ajaxobj.data[i].field+"']"),ajaxobj.data[i].info);
						 }						
					}
				}
			},
			error:function(ajaxobj)
			{
				is_submiting = false;
				$.showErr("请求数据失败");
			}
			
		});
		
		
		return false;
	}
	return false;
}


function check_field(o,field,value)
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=register_check1";
	var query = new Object();
	query.field = field;
	query.value = value;
	form_loading(o);
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				form_success(o,"");			
			}
			else
			{
				form_error(o,ajaxobj.info);							
			}
		},
		error:function(ajaxobj)
		{
			clear_tip_box(o);
		}
	});
}