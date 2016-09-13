var ajax_callback = 0;
$(document).ready(function(){	
	$("#close_user_notify").bind("click",function(){
		$.ajax({ 
			url: APP_ROOT+"/index.php?ctl=ajax&act=close_notify",
			type: "POST",
			success: function(ajaxobj){
				$("#close_user_notify").remove();
			},
			error:function(ajaxobj)
			{
//				if(ajaxobj.responseText!='')
//				alert(ajaxobj.responseText);
			}
		});
	});
	
	if($("#mycenter").length>0)
	{
		$("#user_notify_tip").css("position","absolute");	
		//$("#user_notify_tip").css("top",$("#mycenter").position().top+$("#mycenter").height()+5);
		var px = ($("#user_notify_tip").outerWidth()-$("#mycenter").outerWidth())/2;
		$("#user_notify_tip").css("left",$("#mycenter").position().left-px);
		$("#user_notify_tip").show();
		
	
		var toppx = 0;
		try{
			toppx = parseInt($("#user_notify_tip").css("top").replace("px",""));
		}catch(ex)
		{
			
		}
		$(window).scroll(function(){
			//$("#user_notify_tip").css("top",$(document).scrollTop());
			try{
				toppx = parseInt($("#user_notify_tip").css("top").replace("px",""));
			}catch(ex)
			{
				
			}
			if(toppx<=27)
			{
				$("#user_notify_tip").css("top",27);
			}		
		});	
		//$("#user_notify_tip").css("top",$(document).scrollTop());	
		if(toppx<=27)
		{
			$("#user_notify_tip").css("top",27);
		}	
	}
	
	//加载主导航的焦点取消
	$("a").bind("focus",function(){
		$(this).blur();
	});
	bind_user_loginout();
	init_form_button_style();
	init_common_form_button_style();
	bind_ajax_form();
	uc_table_tdbg();
	
	try{
	bind_drop_panel($("#mymessage"),$("#mymessage_drop").html());
	$("#mymessage_drop").remove();
	bind_drop_panel($("#mycenter"),$("#mycenter_drop").html());
	$("#mycenter_drop").remove();
	bind_drop_panel($("#zc_phone"),$("#zc_phone_drop").html());
	$("#zc_phone_drop").remove();
	}catch(ex){
		
	}
	
	try{
	bind_drop_panel($("#api_login_tip"),$("#api_login_tip_drop").html());
	$("#api_login_tip_drop").remove();
	}catch(ex){
		
	}

	try{
	bind_drop_panel($("#zc_phone"),$("#zc_phone_drop").html());
	$("#zc_phone_drop").remove();
	}catch(ex){
		
	}
	
	bind_header_search();
	bindKindeditor();	
});



function init_form_button_style()
{

	$("input[name='submit_form']").bind("focus",function(){
		$(this).blur();
	});
}


//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_error_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:250,type:'wee',onclose:func});
};

$.showSuccess = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_success_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'成功',width:250,type:'wee',onclose:func});
};
$.showConfirm = function(str,func,funcls)
{
	$.weeboxs.open(str, {boxid:'fanwe_confirm_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'提示',width:250,type:'wee',onok:func,onclose:funcls});

};

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


function close_pop()
{
	$(".dialog-close").click();
}

function bind_user_login()
{
	$("#user_login_form").find("input[name='submit_form']").bind("click",function(){

		do_login_user();
	});
	$("#user_login_form").find("input[name='user_pwd']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			do_login_user();
		}
	});
	$("#user_login_form").find("input[name='email']").bind("keydown",function(e){
		if(e.keyCode==9||e.keyCode==13)
		{
			$("#user_login_form").find("input[name='user_pwd']").val("");
			$("#user_login_form").find("input[name='user_pwd']").focus();
			return false;
		}
	});
	/*$("#user_login_form").find("input[name='email']").bind("focus",function(){
		if($.trim($(this).val())=="邮箱或者用户名")
		{
			$(this).val("");
		}
	});
	$("#user_login_form").find("input[name='email']").bind("blur",function(){
		if($.trim($(this).val())=="")
		{
			$(this).val("邮箱或者用户名");
		}

	});*/
	$("#user_login_form").bind("submit",function(){
		return false;
	});
}

function bind_user_loginout()
{
	$("#user_login_out").bind("click",function(){
		do_loginout($(this).attr("href"));
		return false;
	});
}

function do_login_user()
{
	
	if($.trim($("#user_login_form").find("input[name='email']").val())=="")
	{
		$.showErr("请输入账户信息",function(){
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
				
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				close_pop();
				location.href = ajaxobj.jump;
				
			}
			else
			{
				if(ajaxobj.status==2){
					$.showConfirm("本站需绑定资金托管账户，是否马上去绑定",function(){
						location.href = ajaxobj.jump;
					},function(){
						location.reload();
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

function bind_drop_panel(o,html)
{
	var timer;
	var drop_o = $(html);
	$(drop_o).hide();
	$(drop_o).css("position","absolute");	
	$(drop_o).css("z-index",99999);	
	$(drop_o).css("top",$(o).position().top+$(o).height()+5);
	$("body").append(drop_o);
	
	$(o).hover(function(){
		var x = ($(drop_o).outerWidth()-$(o).outerWidth())/2;
		$(drop_o).css("left",$(o).position().left-x);
		$(this).addClass("hover");
		$(".drop_box").slideUp(300);
		clearTimeout(timer);
		$(drop_o).stop().slideDown(300);
	},function(){		
		 timer = setTimeout(function(){
			 $(drop_o).slideUp(300);
			 $(o).removeClass("hover");
	      },500);		
	});
	$(drop_o).hover(function(){		
	// 	$(".drop_box").slideUp(300);
		clearTimeout(timer);
		$(drop_o).slideDown(300);
	},function(){		
		timer = setTimeout(function(){
	 		$(drop_o).slideUp(300);
	 		$(o).removeClass("hover");
      	},500);		
	});
}

function del_weibo(o)
{
	$(o).parent().remove();
}

function add_weibo()
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=add_weibo";
	$.ajax({ 
		url: ajaxurl,
		type: "POST",
		success: function(html){
			$("#weibo_list").append(html);
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}


function init_common_form_button_style()
{

}

function bind_ajax_form()
{
	
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

function bind_header_search()
{
	$("#header_submit").bind("click",function(){
		var kw = $("#header_keyword").val();
		if($.trim(kw)==""||$.trim(kw)=="搜索项目")
		{
			$("#header_keyword").val("");
			$("#header_keyword").focus();
		}
		else
		{
			$("#header_search_form").submit();
		}
	});
	$("#header_search_form").bind("submit",function(){
		var kw = $("#header_keyword").val();
		if($.trim(kw)==""||$.trim(kw)=="搜索项目")
		{
			$("#header_keyword").val("");
			$("#header_keyword").focus();
			return false;
		}
		else
		{
			return true;
		}
	});
	$("#header_keyword").bind("blur",function(){
		var kw = $("#header_keyword").val();
		if($.trim(kw)=="")
		{
			$("#header_keyword").val("搜索项目");
		}
	});
	$("#header_keyword").bind("focus",function(){
		var kw = $("#header_keyword").val();
		if($.trim(kw)=="搜索项目")
		{
			$("#header_keyword").val("");
		}
	});
}

function show_pop_login()
{
	$.weeboxs.open(APP_ROOT+"/index.php?ctl=ajax&act=login", {boxid:'pop_user_login',contentType:'ajax',showButton:false, showCancel:false, showOk:false,title:'会员登录',width:1060,type:'wee',onopen:function(){ui_checkbox();}});

}

function send_message(user_id)
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=usermessage&id="+user_id;

	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$.weeboxs.open(ajaxobj.html, {boxid:'send_message',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'发送私信',width:500,type:'wee'});				
				$("#user_message_form").find("textarea[name='message']").focus();
				bind_usermessage_form();
			}
			else if(ajaxobj.status==2)
			{
				show_pop_login();
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

//格式化金额
function foramtmoney(price, len)   
{  
   len = len > 0 && len <= 20 ? len : 2;   
   price = parseFloat((price + "").replace(/[^\d\.-]/g, "")).toFixed(len) + "";   
   var l = price.split(".")[0].split("").reverse(),   
   r = price.split(".")[1];   
   t = "";   
   for(i = 0; i < l.length; i ++ )   
   {   
      t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");   
   }   
   var re = t.split("").reverse().join("") + "." + r;
   return re.replace("-,","-");
} 

function bind_usermessage_form()
{
	

		$("#user_message_form").find(".ui-button").bind("click",function(){
			$("#user_message_form").submit();
		});
		$("#user_message_form").bind("submit",function(){
			if($.trim($("#user_message_form").find("textarea[name='message']").val())=="")
			{
				$("#user_message_form").find("textarea[name='message']").focus();
				return false;
			}
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize() ;
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
//页面自适应满屏显示
var resetTimeact=null;
function resetWindowBox(){
	clearTimeout(resetTimeact);
	var main_height=$(window).height() - $("#J_footer").outerHeight() - $("#J_head").outerHeight();
	var box_height=$("#J_wrap").outerHeight();
	if($("#J_wrap").outerHeight() + $("#J_footer").outerHeight() + $("#J_head").outerHeight() < $(window).height())
	{	
		$("#J_wrap").css("height",main_height+"px");
	}
	resetTimeact = setTimeout(resetWindowBox,100);
}

$(function(){
	//筛选
	$(".ui_check").click(function(){
		if($(this).find("input").attr("type")=="radio"){
			var rel=$(this).attr("rel");
			$(".ui_check[rel='"+rel+"']").removeClass("ui_checked");
			$(".ui_check[rel='"+rel+"'] input").attr("checked",false);
			$(this).addClass("ui_checked");
			$(this).find("input").attr("checked","checked");
		}
		else{
			if($(this).hasClass("ui_checked")){
				$(this).removeClass("ui_checked");
				$(this).find("input").attr("checked",false);
			}
			else{
				$(this).addClass("ui_checked");
				$(this).find("input").attr("checked","checked");
			}
		}
	});
	

	// 阶段数字转化
    $(".daxie").each(function(){
        if($(this).html() == 1){
            $(this).html("一");
        }
        if($(this).html() == 2){
            $(this).html("二");
        }
        if($(this).html() == 3){
            $(this).html("三");
        }
        if($(this).html() == 4){
            $(this).html("四");
        }
        if($(this).html() == 5){
            $(this).html("五");
        }
        if($(this).html() == 6){
            $(this).html("六");
        }
        if($(this).html() == 7){
            $(this).html("七");
        }
        if($(this).html() == 8){
            $(this).html("八");
        }
        if($(this).html() == 9){
            $(this).html("九");
        }
        if($(this).html() == 10){
            $(this).html("十");
        }
    });
});
 
function bindKindeditor(){
	if ($("textarea.ketext").length >  0) {
		var K = KindEditor;
	}
	if ($("textarea.ketext").length > 0) {
		var editor = K.create('textarea.ketext', {
			allowFileManager: false,
			emoticonsPath: APP_ROOT + "/public/emoticons/",
			minWidth:400,
			afterBlur: function(){
				this.sync();
			}, //兼容jq的提交，失去焦点时同步表单值
			height: 300,
 			items : [
				'source','fsource', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
				 'justifyleft', 'justifycenter', 'justifyright',
				  'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'selectall','/',
				'title', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'removeformat', 'image',
				'flash', 'media', 'table', 'hr', 'emoticons', 'link', 'unlink'
			]
		});
	}  
	
	bindKeUpload();
	
}


function bindKeUpload(){
	 if($(".keimg").length > 0) {
	 	if(K == undefined)
			var K = KindEditor;
	}
	if ($(".keimg").length > 0) {
		var ieditor = K.editor({
	       allowFileManager : false,
	       imageSizeLimit:MAX_FILE_SIZE               
	    });
		K('.keimg').unbind("click");
		K('.keimg').click(function(){
			var node = K(this);
			var dom = $(node).parent().parent().parent().parent();
			ieditor.loadPlugin('image', function(){
				ieditor.plugin.imageDialog({
					// imageUrl : K("#keimg_h_"+$(this).attr("rel")).val(),
					imageUrl: dom.find("#keimg_h_" + node.attr("rel")).val(),
					clickFn: function(url, title, width, height, border, align){
						dom.find("#keimg_a_" + node.attr("rel")).attr("href", url), dom.find("#keimg_m_" + node.attr("rel")).attr("src", url), dom.find("#keimg_h_" + node.attr("rel")).val(url), dom.find(".keimg_d[rel='" + node.attr("rel") + "']").show(), ieditor.hideDialog();
					}
				});
			});
		});
		
		/**
		 * 删除单图
		 */
		K('.keimg_d').unbind("click");
	    K('.keimg_d').click(function() {
	        var node = K(this);
			K(this).hide();
	        var dom =$(node).parent().parent().parent().parent();
	        dom.find("#keimg_a_"+node.attr("rel")).attr("href","");
	        dom.find("#keimg_m_"+node.attr("rel")).attr("src",ROOT_PATH + "/admin/Tpl/default/Common/images/no_pic.gif");
	        dom.find("#keimg_h_"+node.attr("rel")).val("");
	    });
	}
}

function send_mobile_verify_sms_custom(type,mobile,verify_name){
			var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_change_mobile_verify_code";
 			var squery = new Object();
 			if(type!=2){
				if($.trim(mobile).length == 0)
				{			
		 			$.showErr("手机号码不能为空");
					return false;
				}
		 		if(!$.checkMobilePhone(mobile))
				{
		 			$.showErr("手机号码格式错误");
					return false;
				}
 				if(!$.maxLength(mobile,11,true))
				{
					$.showErr("长度不能超过11位");
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
	function send_email_verify(type,email,verify_name){
			var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_email_verify_code";
 			var squery = new Object();
 			if(type!=2){
				if($.trim(email).length == 0)
				{			
		 			$.showErr("邮箱不能为空");
					return false;
				}
		 		if(!$.checkEmail(email))
				{
		 			$.showErr("邮箱格式错误");
					return false;
				}
 				 
				squery.email = $.trim(email);
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
						code_lefttime_func_custom(type,email,verify_name,'email');
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
	function code_lefttime_func_custom(type,mobile,verify_name,fun_name){
  		clearTimeout(code_timeer);
		$(verify_name).val(code_lefttime+"秒后重新发送");
		$(verify_name).css({"color":"#f1f1f1"});
		code_lefttime--;
		if(code_lefttime >0){
 			code_timeer = setTimeout(function(){code_lefttime_func_custom(type,mobile,verify_name);},1000);
		}
		else{
			code_lefttime = 60;
			$(verify_name).val("发送验证码");
 			$(verify_name).css({"color":"#fff"});
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

function tips(input,msg,top,left)
{
	var tip='<div class="cashdraw_tips" style="top: '+top+'px; left:'+left+'px; display: block;"><div class="cashdraw_tip_header"></div><div class="cashdraw_tip_body_container"><div class="cashdraw_tip_body"><div class="cashdraw_tip_content">'+msg+'</div></div></div></div>';
	$("#imgtips").after(tip);
	input.onmouseout=function(){
		setTimeout(function(){
			$(".cashdraw_tips").remove()
		},500);	
	}
}

/**
 * ofc图表显示函数
 * @param {图表容器id} id
 * @param {图表数据获取连接,需要urlencode} dataurl
 * @param {图表宽度} w
 * @param {图表高度} h
 * @param {指定图表SWF的URL} ofc_swf
 */
function load_ofc(id,dataurl,w,h,ofc_swf)
{
	swfobject.embedSWF(
		ofc_swf, id,
		w, h, "9.0.0", "expressInstall.swf",
		{"data-file":dataurl} ,{"wmode":"transparent"});
}

// uc_table 间隔背景色
function uc_table_tdbg(){
	$(".uc_table").find("tr:even").addClass("even");
}
// 会员中心搜索更多条件处理函数
function account_more_search(more_search_btn,more_search_box){
	$(more_search_btn).click(function(){
		$more_search = $(more_search_box);
		$iconfont_down = $("#account_search").find("#iconfont_down");
		$iconfont_up = $("#account_search").find("#iconfont_up");
		if($more_search.is(":hidden")){
			$("input[name='more_search']").val(1);
			$more_search.show();
			$iconfont_down.hide();
			$iconfont_up.show();
		}
		else{
			$("input[name='more_search']").val(0);
			$more_search.hide();
			$iconfont_up.hide();
			$iconfont_down.show();
		}
	});
}

function show_pop_region(){
	$.weeboxs.open(APP_ROOT+"/index.php?ctl=ajax&act=region", {boxid:'pop_region',contentType:'ajax',showButton:false, showCancel:false, showOk:false,title:'选择地区',width:566,type:'wee'});
}

// 股权发起项目统计盈亏
function total_price(table_class){
    var total_income=0.00;
    var total_out=0.00;
    $(table_class).each(function(i){
        var item_income=0.00;
        var item_out=0.00;
        $(this).find(".income_table .amount").each(function(){
            if($(this).val()!=''){
                item_income=parseFloat(item_income+parseFloat($(this).val()));
                item_income = Math.round(item_income*100)/100;
            }
        });
        $(this).find(".out_table .amount").each(function(){
            if($(this).val()!=''){
                item_out=parseFloat(item_out+parseFloat($(this).val()));
                item_out = Math.round(item_out*100)/100;
            }
        });
        $(this).find(".item_income").html(item_income);
        $(this).find(".item_income_input").val(item_income);
        $(this).find(".item_out").html(item_out);
        $(this).find(".item_out_input").val(item_out);
        total_income = Math.round((total_income+item_income)*100)/100;
        total_out = Math.round((total_out+item_out)*100)/100;
    });
    total_left = Math.round((total_income-total_out)*100)/100;
    $("#totalsr").html(total_income);
    $("#totalkz").html(total_out);
    $("#totalyk").html(total_left);
}

// 股权发起项目是否有阶段收入
function setSR(state,obj,table_class) {
    var $textarea_obj = $(obj).parent().parent().parent().find("table");
    if(state==1) {
        $textarea_obj.show();
    } else {
	  $textarea_obj.find(".tr_income_row").remove("tr") ;
       $textarea_obj.hide();
    }
	total_price(table_class);
}

// 股权发起项目是否有阶段开支
function setKZ(state,obj,table_class) {
    var $textarea_obj = $(obj).parent().parent().parent().find("table");
    if(state==1) {
        $textarea_obj.show();
    } else {
		$textarea_obj.find(".tr_out_row").remove("tr") ;
        $textarea_obj.hide();
    }
	total_price(table_class);
}

/*
阶段开始结束时间控制
	time_box:外围盒子
	begin_time:开始时间元素
	end_time:结束时间元素
*/
function checkErrortime(time_box,begin_time,end_time){
    var is_errortime=false;
    function isErrortime(){
        $(time_box).each(function(i){
            var $obj=$(this) ,
    			begin_time_val=$obj.find(begin_time).val() ,
            	end_time_val=$obj.find(end_time).val() ,
            	begin_time_arr = begin_time_val.split("-") ,
            	new_begin_time = new Date(begin_time_arr[0], begin_time_arr[1], begin_time_arr[2]) ,
            	new_begin_times = new_begin_time.getTime() ,
            	end_time_arr = end_time_val.split("-") ,
            	new_end_time = new Date(end_time_arr[0], end_time_arr[1], end_time_arr[2]) ,
            	new_end_times = new_end_time.getTime();
            if (new_begin_times >= new_end_times) {
                $.showErr("开始时间不能大于结束时间");
                is_errortime=true;
                $(begin_time).eq(i).focus();
                return false;
            }
            else{
                is_errortime=false;
                return true;
            }
        });
    }
    isErrortime();
    return is_errortime;
}