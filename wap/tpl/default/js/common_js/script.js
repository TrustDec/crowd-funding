var ajax_callback = 0;

$(function(){
	bind_ajax_form();
	// navScroll(".index_nav");

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

	//初始化头部搜索
	$(document).click(function(e){
		e.stopPropagation();
		$("#selectbox1").hide();
		$("#screen").removeClass("screen1"); 
	});
});

//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func)
{
	$.alert(str, func);
};

$.showSuccess = function(str,func)
{
	$.alert(str, func);
};
/*$.confirm = function(str,func,funcls)
{
	$.weeboxs.open(str, {boxid:'fanwe_confirm_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'警告',width:300,type:'wee',onok:func,onclose:funcls});
};*/

/*验证*/
$.minLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength >= length;
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
$.checkMobilePhone = function(value){
	if($.trim(value)!='')
		return /^\d{6,}$/i.test($.trim(value));
	else
		return true;
};
$.checkEmail = function(val){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; 
	return reg.test(val);
};

function close_pop(){
	$(".dialog-close").click();
}

function bind_user_login()
{
	var $user_login_form = $("#user_login_form");
	var $submit_form = $user_login_form.find("input[name='submit_form']");
	var $user_pwd = $user_login_form.find("input[name='user_pwd']");
	var $email = $user_login_form.find("input[name='email']");
	
	$submit_form.on("click",function(){
		do_login_user();
	});
	$user_pwd.on("keydown",function(e){
		if(e.keyCode==13)
		{
			do_login_user();
		}
	});
	$email.on("keydown",function(e){
		if(e.keyCode==9||e.keyCode==13)
		{
			$user_pwd.val("").focus();
			return false;
		}
	});
	$user_login_form.on("submit",function(){
		return false;
	});
}
function bind_user_loginout()
{
	$("#user_login_out").on("click",function(){
		do_loginout($(this).attr("ajaxurl"));
		return false;
	});
}
function do_login_user(){
	var $user_login_form = $("#user_login_form");
	var $email = $user_login_form.find("input[name='email']");
	var $user_pwd = $user_login_form.find("input[name='user_pwd']");
	if($.trim($email.val())=="")
	{
		$.alert("请输入邮箱或者用户名");	
		$email.focus();
		return false;
	}
	if($.trim($user_pwd.val())=="")
	{
		$.alert("请输入密码");
		$user_pwd.focus();
		return false;
	}
	var ajaxurl = $user_login_form.attr("action");
	var query = $user_login_form.serialize();

	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				var user_info = ajaxobj.user_info;
				try{
					var json = '{"id":"'+user_info.id+'","user_name":"'+user_info.user_name+'"}';
					App.login_success(json);
				}
				catch(e){
					
				}
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				$.toast(ajaxobj.info,1000);
				setTimeout(
					function(){
						location.href = ajaxobj.jump;
					}
				, 1000);
			}
			else
			{
				if(ajaxobj.status==2){
					$.confirm("本站需绑定资金托管账户，是否马上去绑定",function(){
						location.href = ajaxobj.jump;
					},function(){
						$.router.loadPage(window.location.href);
					});
				}else{
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
					}
				}						
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
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
				try{
					App.logout();
				}
				catch(e){
					
				}
				
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				$.toast(ajaxobj.info,1000);
				setTimeout(
					function(){
						location.href = ajaxobj.jump;
					}
				, 1000);
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

function bind_ajax_form(){
	$(".ajax_form").find(".ui-button").bind("click",function(){
		$(".ajax_form").submit();
	});
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
						$.closeModal();
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.closeModal();
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
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

function show_login(){
	$.alert("请先登录",function(){
		$.router.loadPage(APP_ROOT+"/index.php?ctl=user&act=login");
	});
}

// 发私信
function send_message(user_id){
	$.showIndicator();
	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=usermessage&id="+user_id;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			$.hideIndicator();
			if(ajaxobj.status==1)
			{

		      	$.modal({
					title: '发私信',
			      	text: ajaxobj.html,
			      	buttons: []
				});
				bind_usermessage_form();
			}
			else if(ajaxobj.status==2)
			{
				href=APP_ROOT+"/index.php?ctl=user&act=login";
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

function bind_usermessage_form(){
	$("#user_message_form").find(".btn_send").on('click',function(){
		if($.trim($("#user_message_form").find("textarea[name='message']").val())==""){
			$.toast("私信内容不能为空！",1000);
			return false;
		}
		var ajaxurl = $("#user_message_form").attr("action");
		var query = $("#user_message_form").serialize();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				close_pop();
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.closeModal();
						$.toast(ajaxobj.info,1000);
						if(ajaxobj.jump!="")
						{
							setTimeout(
								function(){
									$.router.loadPage(ajaxobj.jump);
								}
							, 1000);
						}
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.closeModal();
						$.toast(ajaxobj.info,1000);
						if(ajaxobj.jump!="")
						{
							setTimeout(
								function(){
									$.router.loadPage(ajaxobj.jump);
								}
							, 1000);
						}
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
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

// 编辑地址页点击选中
function selectadd(obj){
    $(obj).find(".edit_select").attr("checked","checked");
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
					   		$.router.loadPage(ajaxobj.jump);
					   	}	
					});	
				}else{
					$.showSuccess(ajaxobj.info,function(){
					 	$.router.loadPage(window.location.href);
					});	
				}
			}
		});
	});
}

function bind_ajax_form_custom(str)
{
	$(str).find(".ui-button").bind("click",function(){
		$(str).submit();
	});
	$(str).bind("submit",function(){
		 
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
						$.closeModal();
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.closeModal();
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
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

// 发送手机验证码
function send_mobile_verify_sms_custom(type,mobile,verify_name){
	var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_change_mobile_verify_code";
	var squery = new Object();
	if(type!=2){
		if($.trim(mobile).length == 0)
		{			
 			$.alert("手机号码不能为空");
			return false;
		}
 		if(!$.checkMobilePhone(mobile))
		{
 			$.alert("手机号码格式错误");
			return false;
		}
			if(!$.maxLength(mobile,11,true))
		{
			$.alert("长度不能超过11位");
			return false;
		}
		squery.mobile = $.trim(mobile);
	}
	squery.step =type;
	$.ajax({ 
		url: sajaxurl,
		data:squery,
		type: "POST",
		dataType: "json",
		success: function(sdata){
			if(sdata.status==1)
			{
				code_lefttime = 60;
				code_lefttime_func_custom(type,mobile,verify_name,'mobile');
				// $.showSuccess(sdata.info);
				$.toast(sdata.info,1000);
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

// 发送邮箱验证码
function send_email_verify(type,email,verify_name){
	var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_email_verify_code";
	var squery = new Object();
	if(type!=2){
		if($.trim(email).length == 0){			
			$.showErr("邮箱不能为空");
			return false;
		}
		if(!$.checkEmail(email)){
			$.showErr("邮箱格式错误");
			return false;
		} 
 	}
	squery.email = email;
	squery.step =type;
	$.ajax({ 
		url: sajaxurl,
		data:squery,
		type: "POST",
		dataType: "json",
		success: function(sdata){
			if(sdata.status==1)
			{
				code_lefttime = 60;
				code_lefttime_func_custom(type,email,verify_name,'email');
				// $.showSuccess(sdata.info);
				$.toast(sdata.info,1000);
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

// 重新发送验证码
function code_lefttime_func_custom(type,mobile,verify_name,fun_name){
	var code_timeer=null;
	clearTimeout(code_timeer);
	$(verify_name).val(code_lefttime+"秒后重新发送");
	$(verify_name).css("color","#999");
	$(verify_name).addClass("bg_eee").removeClass("bg_red");
	code_lefttime--;
	if(code_lefttime >0){
		$(verify_name).attr("disabled","disabled");
		code_timeer = setTimeout(function(){code_lefttime_func_custom(type,mobile,verify_name);},1000);
	}
	else{
		code_lefttime = 60;
		$(verify_name).removeAttr("disabled");
		$(verify_name).val("发送验证码");
		$(verify_name).css("color","#fff");
		$(verify_name).addClass("bg_red").removeClass("bg_eee");
		$(verify_name).bind("click",function(){
			if(fun_name=='mobile'){
				send_mobile_verify_sms_custom(type,mobile,verify_name);
			}else{
				if(fun_name=='email'){
					send_email_verify(type,mobile,verify_name);
				}
			}
		});
	}
	
}

// 限制只能输入金额
function amount(th){
    var regStrs = [
        ['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
        ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
        ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
        ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
    ];
    for(i=0; i<regStrs.length; i++){
        var reg = new RegExp(regStrs[i][0]);
        th.value = th.value.replace(reg, regStrs[i][1]);
    }
}
//先使用round函数四舍五入成整数，然后再保留指定小数位  
function round2(number,fractionDigits){     
    with(Math){     
        return round(number*pow(10,fractionDigits))/pow(10,fractionDigits);     
    }     
}
function ajax_form(ajax_form){
	var ajaxurl = $(ajax_form).attr("action");
	var query = $(ajax_form).serialize() ;
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
					$.closeModal();
					$.showSuccess(ajaxobj.info,function(){
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
						}
					});	
				}
				else
				{
					if(ajaxobj.jump!="")
					{
						$.router.loadPage(ajaxobj.jump);
					}
				}
			}
			else
			{
				if(ajaxobj.info!="")
				{
					$.closeModal();
					$.showErr(ajaxobj.info,function(){
						if(ajaxobj.jump!="")
						{
							$.router.loadPage(ajaxobj.jump);
						}
					});	
				}
				else
				{
					if(ajaxobj.jump!="")
					{
						$.router.loadPage(ajaxobj.jump);
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
}
function checkIpsBalance(type,user_id,func){
 	var query = new Object();
	query.ctl="collocation";
	query.act="QueryForAccBalance";
	query.user_type = type;
	query.user_id = user_id;
	query.is_ajax = 1;
	$.ajax({
		url:APP_ROOT + "/index.php",
		data:query,
		type:"post",
		dataType:"json",
		success:function(result){
			if(func!=null)
				func.call(this,result);
		}
	});
}

/**
 * 格式化数字
 * @param {Object} num
 */
function formatNum(num) {
	num = String(num.toFixed(2));
	var re = /(\d+)(\d{3})/;
	while (re.test(num)) {
		num = num.replace(re, "$1,$2");
	}
	return num;
}

// 返回顶部
function init_gotop() {
	if($("body").height() <= document.documentElement.clientHeight*1.8){
		$("#jumphelper").remove();
	}
	$("#gotop").click(function(){
		$("html,body").animate({scrollTop:0},"fast","swing");		
	});
}

// 关注、取消关注 
function bind_attention_focus(){
	$(".attention_focus_deal").on("click",function(){
		attention_focus_deal($(this).attr("id"));
	});
}
function attention_focus_deal(id)
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=deal&act=focus&id="+id;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$(".attention_focus_deal").removeClass("gz");
				$(".attention_focus_deal").addClass("qxgz");
				$(".attention_focus_deal").html('<i class="icon iconfont is_focus">&#xe634;</i>');
				$.toast("关注成功",1000);
			}
			else if(ajaxobj.status==2)
			{
				$(".attention_focus_deal").removeClass("qxgz");
				$(".attention_focus_deal").addClass("gz");	
				$(".attention_focus_deal").html('<i class="icon iconfont">&#xe635;</i>');
				$.toast("已取消关注",1000);
			}
			else if(ajaxobj.status==3)
			{
				$.showErr(ajaxobj.info);							
			}
			else
			{
				
			 $.showErr("请先登录",function(){
			 	$.router.loadPage(APP_ROOT+"/index.php?ctl=user&act=login");
			 });
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}

// 删除未通过或者无效的项目
function ajax_del_item(ajaxurl,ajax_del_id){
	$.confirm('确定要删除吗？', 
 		function(){
			$.ajax({
				url:ajaxurl,
				dataType:"json",
				type:"post",
				success:function(data){
					if(data.status==1){
						$.alert(data.info,function(){
							$(".item_"+ajax_del_id).remove();
						});
					}else{
						$.showErr(data.info);
					}
				}
			});
		}
	);
}

function reloadpage(url,page,cls,func){
	$.showIndicator();
	$.ajax({
		url:url,
		type:"post",
		dataType:"html",
		success:function(result){
			$("body").append('<div id="tmpHTML">'+result+'</div>');
			var html = $("#tmpHTML").find(page).find(cls).html();
			$("#tmpHTML").remove();
			$(page).find(cls).html(html);
			$(page).find(".content").attr("now_page",1);
			$.hideIndicator();
			$.refreshScroller(page);
			if(func!=null){
				func.call(this);
			}
		}
	});
}

/** 
 * @param {Object} url  请求URL
 * @param {Object} 页面ID
 * @param {Object} w  0 正常LOAD  1打开新页面LOAD   2重载
 */
function RouterURL(url,page,w){
	if(isapp=="1" && url.indexOf("app")==-1){
		if(url.indexOf("?")==-1){
			url +="?app=1";
		}
		else{
			url +="&app=1";
		}
	}
	$.closePanel();
	if($("#panel-left-box").length > 0 && w!=1){
		if(url.indexOf("?")==-1){
			url +="?hasleftpanel=1";
		}
		else{
			url +="&hasleftpanel=1";
		}
	}
	if($(page).length > 0&&w!=1){
		if(w==2){
			if(!$(page).hasClass("page-current")){
				$(page).remove();
				loadUrl(url);
			}
		}
		else{
			if(!$(page).hasClass("page-current"))
				$.router.loadPage(page);
		}
	}
	else{
		loadUrl(url,page,w);
	}
}

function loadUrl(url,page,w){
	if (w == 1) {
		if(url.indexOf(APP_ROOT)===-1){
			try{
				var open_url_type = 0;
				if(page=="#adv_1"){
					open_url_type = 1;
				}
				var sjson = '{"url":"'+url+'","open_url_type":'+open_url_type+'}';
				App.open_type(sjson);
			}
			catch(e){
				if(page=="#adv_1"){
					window.open(url);
				}
				else{
					window.location.href = url;
				}
			}
		}
		else{
			if(page=="#adv_1"){
				window.open(url);
			}
			else{
				window.location.href = url;
			}
		}
	}
	else
		$.router.loadPage(url);
}