$(function(){
	selectadd();
	bind_ajax_form();

	// 显示筛选框
    $("#screen").bind('click',function(e){
		e.stopPropagation();
		if($("#selectbox1").is(":hidden")){
			$("#selectbox1").show();
		}
		else{
			$("#selectbox1").hide();
		}
        $("#screen").toggleClass("screen1");
    });
	// 阻止冒泡
	$("#selectbj").bind('click',function(e){
		e.stopPropagation();
	});
	
    $(".mybtn").bind('click',function(){
        $(".mybtn").toggleClass("screen1");        
    });

    // 查看更多回报
    $(".moreProject").bind('click',function(){
        $(".displayReturn").slideToggle("fast");
        $(".closemore").slideToggle("fast");
        $(".openmore").slideToggle("fast");      
    });

    // 隐藏的相片放大
    $(".pimg").click(function showbig(){ 
        $(".outerdiv").slideToggle("fast");
        var v=$(this).attr("src");
        $("#bigimg").attr("src",v);
        var h=$(document).height();
        $(".innerdiv").css("height",h+"px");
    });
    // 放大相片关闭
    $(".outerdiv").click(function showbig(){ 
        $(".outerdiv").slideToggle("fast");
    });
	
	//初始化头部搜索
	$(document).click(function(e){
		e.stopPropagation();
		$("#selectbox1").hide();
		$("#screen").removeClass("screen1"); 
	});
});

// 编辑地址页点击选中
function selectadd(){
    $(".editAddress ul li").click(function(){
        $(this).find(".edit_select").attr("checked","checked");
    });
}

// 返回上一页
function return_prepage()  
{  
	if(window.document.referrer==""||window.document.referrer==window.location.href)  
	{  
		window.location.href="{dede:type}[field:typelink /]{/dede:type}";  
	}else  
	{  
		window.location.href=window.document.referrer;  
	}  
} 

function bind_del_consignee(consignee_id,del_url){
	$("#remove_but").bind("click",function(){
		id=consignee_id;
		var obj=new Object();
		obj.id=id;
		$.ajax({
			url:del_url,
			data:obj,
			type:"POST",
			dataType:"json",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
 					$.showSuccess(ajaxobj.info,function(){
						   if(ajaxobj.jump){
						   		window.location.href=ajaxobj.jump;
						   }
							
						});	
				}else{
 						$.showSuccess(ajaxobj.info,function(){
						 	window.location.reload();
						});	
				}
				}
		});
	});
}

function bind_ajax_form()
{
	$(".ajax_form").bind("submit",function(){
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize() ;
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
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
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
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
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
}

//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func) {
	$.weeboxs.open(str, {boxid:'fanwe_error_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:300,type:'wee',onclose:func});
};

$.showSuccess = function(str,func) {
	$.weeboxs.open(str, {boxid:'fanwe_success_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:300,type:'wee',onclose:func});
};
$.showConfirm = function(str,func) {
	$.weeboxs.open(str, {boxid:'fanwe_confirm_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'警告',width:300,type:'wee',onok:func});

};
function bind_user_login() {
	$("#user_login_form").find("input[name='submit_form']").bind("click",function(){
		do_login_user();
	});
	$("#user_login_form").bind("submit",function(){
		return false;
	});
}
function bind_user_register() {
	$("#user_register_form").find("input[name='submit_form']").bind("click",function(){
		do_register_user();
	});
	$("#user_register_form").bind("submit",function(){
		return false;
	});
}
function bind_user_loginout() {
	$("#user_login_out").bind("click",function(){
		do_loginout($(this).attr("href"));
		return false;
	});
}
function do_login_user() {
	if($.trim($("#user_login_form").find("input[name='email']").val())=="")
	{
		$.showErr("请输入邮箱或者手机号码",function(){			
			$("#user_login_form").find("input[name='email']").val("");
			$("#user_login_form").find("input[name='email']").focus();
		});
		return false;
	}
	if($.trim($("#user_login_form").find("input[name='user_pwd']").val())=="")
	{
		$.showErr("请输入密码",function(){
			$("#user_login_form").find("input[name='user_pwd']").val("");
			$("#user_login_form").find("input[name='user_pwd']").focus();
		});
		return false;
	}
	var ajaxurl = $("form[name='user_login_form']").attr("action");
	var query = $("form[name='user_login_form']").serialize() ;

	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				//alert(ajaxobj.data);
//				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
//				$("body").append(integrate);				
//				$("#integrate").remove();
//				close_pop();
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
$.checkMobilePhone = function(value){
	if($.trim(value)!='')
		return /^\d{6,}$/i.test($.trim(value));
	else
		return true;
};
$.maxLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength <= length;
};
$.getStringLength=function(str)
{
	str = $.trim(str);
	
	if(str=="")
		return 0; 
		
	var length=0; 
	for(var i=0;i <str.length;i++) 
	{ 
		if(str.charCodeAt(i)>255)
			length+=2; 
		else
			length++; 
	}
	
	return length;
};

function do_register_user()
{
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
	if($.trim($("#user_register_form").find("input[name='user_name']").val()) == ""){
		$.showErr("请输入用户名");
		return false;
	}
	if($.trim($("#user_register_form").find("input[name='user_name']").val()).length < 4){
		$.showErr("用户名不少于4个字符");
		return false;
	}
	if($.trim($("#user_register_form").find("input[name='user_pwd']").val())=="")
	{
		$.showErr("请输入密码");
		return false;
	}
	if($.trim($("#user_register_form").find("input[name='user_pwd']").val()).length < 4){
		$.showErr("密码不少于4个字符");
		return false;
	}
	if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val())=="")
	{
		$.showErr("请输入确认密码");
		return false;
	}
	if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val()) != $.trim($("#user_register_form").find("input[name='user_pwd']").val()))
	{
 		$.showErr("密码不一致");
		return false;
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
function close_pop()
{
	$(".dialog-close").click();
}
function do_loginout(ajaxurl)
{	
	var query = new Object();
	query.ajax = 1;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				//alert(ajaxobj.data);
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				location.href = ajaxobj.jump;
				
			}
			else
			{
				location.href = ajaxobj.jump;							
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}