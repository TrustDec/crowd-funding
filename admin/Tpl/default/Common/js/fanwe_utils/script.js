$(document).ready(function(){
	init_ui_button();
	init_ui_textbox();
	init_ui_select();
	init_ui_radiobox();
	init_ui_checkbox();
	init_ui_starbar();
	init_ui_lazy();
	// init_gotop();	
	
	$(".option-qrcode").hover(
			function(){
				$(this).addClass("curr");
			},
			function(){
				$(this).removeClass("curr");
			}
	);
});

//以下是处理UI的公共函数
function init_ui_lazy()
{
//	$.refresh_image = function(){
//		$("img[lazy='true'][isload!='true']").ui_lazy({placeholder:LOADER_IMG});
//	};		
//	$.refresh_image();
//	$(window).bind("scroll", function(e){
//		$.refresh_image();
//	});	
	
}


//以下是处理UI的公共函数
function init_ui_checkbox()
{
	$("label.ui-checkbox[init!='init']").each(function(i,ImgCbo){
		$(ImgCbo).attr("init","init");  //为了防止重复初始化
		$(ImgCbo).ui_checkbox();		
	});
}

function init_ui_starbar()
{
	$("input.ui-starbar[init!='init']").each(function(i,ipt){
		$(ipt).attr("init","init");  //为了防止重复初始化
		$(ipt).ui_starbar();		
	});
}


function init_ui_radiobox()
{
	$("label.ui-radiobox[init!='init']").each(function(i,ImgCbo){
		$(ImgCbo).attr("init","init");  //为了防止重复初始化
		$(ImgCbo).ui_radiobox();		
	});
}

var droped_select = null; //已经下拉的对象
var uiselect_idx = 0;
function init_ui_select()
{
	$("select.ui-select[init!='init']").each(function(i,o){
		uiselect_idx++;
		var id = "uiselect_"+Math.round(Math.random()*10000000)+""+uiselect_idx;
		var op = {id:id};
		$(o).attr("init","init");  //为了防止重复初始化		
		$(o).ui_select(op);		
	});
	
	$(document.body).click(function(e) {		
		if($(e.target).attr("class")!='ui-select-selected'&&$(e.target).parent().attr("class")!='ui-select-selected')
    	{
			$(".ui-select-drop").fadeOut("fast");
			$(".ui-select").removeClass("dropdown");
			droped_select = null;
    	}
		else
		{			
			if(droped_select!=null&&droped_select.attr("id")!=$(e.target).parent().attr("id"))
			{
				$(droped_select).find(".ui-select-drop").fadeOut("fast");
				$(droped_select).removeClass("dropdown");
			}
			droped_select = $(e.target).parent();
		}
	});
	
}
function init_ui_button()
{
	
	$("button.ui-button[init!='init']").each(function(i,o){
		$(o).attr("init","init");  //为了防止重复初始化		
		$(o).ui_button();		
	});
	
}

function init_ui_textbox()
{
	
	$(".ui-textbox[init!='init'],.ui-textarea[init!='init']").each(function(i,o){
		$(o).attr("init","init");  //为了防止重复初始化		
		$(o).ui_textbox();		
	});

}
//ui初始化结束

/*function init_gotop()
{
	$("#go_top").css("top",$(document).scrollTop()+$(window).height()-80);
	$(window).scroll(function(){
		$("#go_top").css("top",$(document).scrollTop()+$(window).height()-80);
		if($(document).scrollTop()>0)
			$("#go_top").fadeIn();
		else
			$("#go_top").fadeOut();
	});	
	
	$("#go_top").bind("click",function(){
		$("html,body").animate({scrollTop:0},"fast","swing",function(){
		});
	});

}*/

function init_sms_btn()
{
	$(".login-panel").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i,o){
		$(o).attr("init_sms","init_sms");
		var lesstime = $(o).attr("lesstime");
		var divbtn = $(o).next();
		divbtn.attr("form_prefix",$(o).attr("form_prefix"));
		divbtn.attr("lesstime",lesstime);
		if(parseInt(lesstime)>0)
		init_sms_code_btn($(divbtn),lesstime);	
	});
}
//关于短信验证码倒计时
function init_sms_code_btn(btn,lesstime)
{

	$(btn).stopTime();
	$(btn).removeClass($(btn).attr("rel"));
	$(btn).removeClass($(btn).attr("rel")+"_hover");
	$(btn).removeClass($(btn).attr("rel")+"_active");
	$(btn).attr("rel","disabled");
	$(btn).addClass("disabled");	
	$(btn).find("span").html("重新获取("+lesstime+")");
	$(btn).attr("lesstime",lesstime);
	$(btn).everyTime(1000,function(){
		var lt = parseInt($(btn).attr("lesstime"));
		lt--;
		$(btn).find("span").html("重新获取("+lt+")");
		$(btn).attr("lesstime",lt);
		if(lt==0)
		{
			$(btn).stopTime();
			$(btn).removeClass($(btn).attr("rel"));
			$(btn).removeClass($(btn).attr("rel")+"_hover");
			$(btn).removeClass($(btn).attr("rel")+"_active");
			$(btn).attr("rel","light");
			$(btn).addClass("light");
			$(btn).find("span").html("发送验证码");
		}
	});
}


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


/**
 * 检测密码的复杂度
 * @param pwd
 * 分数 1-2:弱 3-4:中 5-6:强 
 * 返回 0:弱 1:中 2:强 -1:无
 */
function checkPwdFormat(pwd)
{
	var regex0 = /[a-z]+/;  
	var regex1 = /[A-Z]+/;  
	var regex2 = /[0-9]+/;
	var regex3 = /\W+/;   //符号
	var regex4 = /\S{6,8}/;	    
	var regex5 = /\S{9,}/;   
	
	
	var result = 0;
	
	if(regex0.test(pwd))result++;
	if(regex1.test(pwd))result++;
	if(regex2.test(pwd))result++;
	if(regex3.test(pwd))result++;
	if(regex4.test(pwd))result++;
	if(regex5.test(pwd))result++;
	
	if(result>=1&&result<=2)
		result=0;
	else if(result>=3&&result<=4)
		result=1;
	else if(result>=5&&result<=6)
		result=2;
	else 
		result=-1;
	
	return result;
}

/**
 * 顶部错误提示
 */
$(function(){
	$(".msg_tip .close").bind("click",function(){
		$(".msg_tip").slideUp("slow");
		$(".msg_tip .msg_content").html("");
	});
	
	$.Close_top_tip = function(){
		$(".msg_tip").slideUp("slow");
		$(".msg_tip .msg_content").html("");
		};
	$.Show_top_tip = function(type,msg){
		position_scroll();
		if(type == 'error'){
			$(".msg_tip").addClass("sysmsg_error");
			$(".msg_tip .status").addClass("s_error");
		}else if(type == 'sucess'){
			$(".msg_tip").addClass("sysmsg_success");
			$(".msg_tip .status").addClass("s_success");
		}
		$(".msg_tip .msg_content").html(msg);
		$(".msg_tip").fadeIn();
		return false;
	};
	$.Show_error_tip = function(msg){
		$.Show_top_tip('error',msg);
	};
	$.Show_success_tip = function(msg){
		$.Show_top_tip('sucess',msg);
	};
	/**
	 * 显示错误列
	 */
	$.Show_field_error = function(obj) {
		$.Show_field_tip_status(obj, 'error');
	};
	$.Show_field_success = function(obj) {
		$.Show_field_tip_status(obj, 'success');
	};
	
	$.Show_field_tip_status=function(obj, type) {
		var show_item = $(obj).parent().find(".status_icon");
		if(type == "error"){
			$(show_item).children("i").removeClass("s_success");
			$(show_item).children("i").addClass("s_error");
		}else{
			$(show_item).children("i").removeClass("s_error");
			$(show_item).children("i").addClass("s_success");
		}
			
		$(show_item).show();
	}
});
//定位滚动
function position_scroll(){
	var window_scroll = $(window).scrollTop();
	if(window_scroll>100){
		var scroll_height = window_scroll-75;
		$(".msg_tip_box").css("top",scroll_height+"px");
	}else{
		$(".msg_tip_box").css("top",0);
	}
	$('.msg_tip_box').stopTime();
	$('.msg_tip_box').oneTime(3000,function(){
		$(".msg_tip_box").css("top",0);
	});
}


function load_ofc(id,dataurl,w,h)
{
	swfobject.embedSWF(
			OFC_SWF, id,
			w, h, "9.0.0", "expressInstall.swf",
			{"data-file":dataurl} );
}

function jump_to(url)
{
	location.href = url;	
}
