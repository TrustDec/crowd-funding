$(function(){
	ui_radiobox();
	ui_checkbox();

	button_hover(".ui-button");
   	button_hover(".ui_button");
   	button_hover(".ui-small-button");
   	button_hover(".ui-center-button");

   	show_tip();
   	init_gotop();
});

// 初始化单选框,该UI必为label，且内部需要有radio
function ui_radiobox(){
	$(".ui_radiobox").each(function(){
		var img = $(this);
		var o = img.find("input[type='radio']");
		o.hide();
		var checked = $(o).attr("checked");
		img.addClass("common_rdo");
		img.attr("name",o.attr("name"));
		img.attr("checked",checked?true:false);
		img.css({"display":"inline-block"});

		if(checked){
			img.removeClass("common_rdo");
			img.removeClass("common_rdo_checked");
			img.addClass("common_rdo_checked");
		}
		else{
			img.removeClass("common_rdo");
			img.removeClass("common_rdo_checked");		
			img.addClass("common_rdo");
		}
	});
	$(".ui_radiobox").hover(function(){
		var img = $(this);
		img.css("cursor","pointer");
		var o = img.find("input[type='radio']");
		var checked = o.attr("checked");
		if(!checked){
			$(this).addClass("common_rdo_hover");
		}
	},function(){
		$(this).removeClass("common_rdo_hover");
	});
	$(".ui_radiobox").find("input[type='radio']").bind("click",function(){
		return false;
	});	
	$(".ui_radiobox").click(function(){
		var img = $(this);
		var o = img.find("input[type='radio']");
		var rel=img.attr("rel");
		checked = true;
		
		$(".ui_radiobox[rel='"+rel+"']").attr("checked",false);
		$(".ui_radiobox[rel='"+rel+"']").removeClass("common_rdo_hover");
		$(".ui_radiobox[rel='"+rel+"']").addClass("common_rdo");
		$(".ui_radiobox[rel='"+rel+"']").removeClass("common_rdo_checked");
		$(".ui_radiobox[rel='"+rel+"']").find("input[type='radio']").attr("checked",false);
		o.attr("checked",checked);
		img.attr("checked",checked);
		img.addClass("common_rdo_checked");
	});
}

// 初始化复选框,该UI必为label，且内部需要有checkbox
function ui_checkbox(){
	$(".ui_checkbox").each(function(){
		var img = $(this);
		var o = img.find("input[type='checkbox']");
		o.hide();
		var checked = $(o).attr("checked");
		img.addClass("common_cbo");
		img.attr("name",o.attr("name"));
		img.css({"display":"inline-block"});
		img.attr("checked",checked?true:false);
		if(checked){
			
			img.removeClass("common_cbo");
			img.removeClass("common_cbo_checked");
			img.addClass("common_cbo_checked");
		}
		else{
			
			img.removeClass("common_cbo");
			img.removeClass("common_cbo_checked");		
			img.addClass("common_cbo");
		}
	});
	$(".ui_checkbox").hover(function(){
		var img = $(this);
		img.css("cursor","pointer");
		var o = img.find("input[type='checkbox']");
		var checked = o.attr("checked");
		if(!checked){
			$(this).addClass("common_cbo_hover");
		}
	},function(){
		$(this).removeClass("common_cbo_hover");
	});
	$(".ui_checkbox").find("input[type='checkbox']").bind("click",function(){
		return false;
	});	
	$(".ui_checkbox").click(function(){
		var img = $(this);
		var o = img.find("input[type='checkbox']");
		var checked = $(o).attr("checked");
		var rel=img.attr("rel");
		$(".ui_checkbox[rel='"+rel+"']").removeClass("common_cbo_hover");
		checked = checked?false:true;
		o.attr("checked",checked);
		img.attr("checked",checked);
		if(checked){
			o.trigger("checkon");
			$(this).removeClass("common_cbo");
			$(this).addClass("common_cbo_checked");
			
		}
		else{
			o.trigger("checkoff");
			img.removeClass("common_cbo_checked");
			img.addClass("common_cbo");
		}
	});
}

// ui-button 鼠标悬浮替换颜色
function button_hover(hoverObj){
 	$(hoverObj).live('mouseover mouseout', function(){
		if($(this).hasClass("theme_bgcolor")){
			$(this).toggleClass("theme_bgcolor1");
		}
		if($(this).hasClass("bg_red")){
			$(this).toggleClass("bg_red1");
		}
		if($(this).hasClass("bg_gray")){
			$(this).toggleClass("bg_gray1");
		}
		if($(this).hasClass("bg_green")){
			$(this).toggleClass("bg_green1");
		}
	});
}

// 输入框提示文字显隐
function show_tip(){
	var $textbox = $(".textbox , .small_textbox");
	$(".holder_tip").live('click',function(){
		$(this).hide();
		$(this).parent().find(".textbox , .small_textbox").focus();
	});
	
	$textbox.live('focus',function(){
		$(this).parent().find(".holder_tip").hide();
	});
	$textbox.live('blur',function(){
		if($(this).val()==""){
			$(this).parent().find(".holder_tip").show();
		}
		else{
			$(this).parent().find(".holder_tip").hide();
		}
	});
	$textbox.each(function(){
		if($(this).val()==""){
			$(this).parent().find(".holder_tip").show();
		}
		else{
			$(this).parent().find(".holder_tip").hide();
		}
	});
}

// 返回顶部
function init_gotop(){
	$(window).scroll(function(){
		var s_top = $(document).scrollTop()+$(window).height()-70;
		if($.browser.msie && $.browser.version =="6.0"){
			$("#gotop").css("top",s_top);
			if($(document).scrollTop()>0){				
				$("#gotop").css("visibility","visible");	
			}
			else{
				$("#gotop").css("visibility","hidden");	
			}
		}	
		else{
			if($(document).scrollTop()>0){
				if($("#gotop").css("display")=="none")
				$("#gotop").fadeIn();	
			}
			else{
				if($("#gotop").css("display")!="none")
				$("#gotop").fadeOut();
			}
		}
		
		
	});		
	
	$("#gotop").bind("click",function(){		
		$("html,body").animate({scrollTop:0},"fast","swing",function(){});		
	});
	var top = $(document).scrollTop()+$(window).height()-70;
	if($.browser.msie && $.browser.version =="6.0"){
		$("#gotop").css("top",top);
		if($(document).scrollTop()>0){	
			$("#gotop").css("visibility","visible");
		}
		else{
			$("#gotop").css("visibility","hidden");
		}
	}
	else{
		if($(document).scrollTop()>0){	
			if($("#gotop").css("display")=="none")
			$("#gotop").show();	
		}
		else{
			if($("#gotop").css("display")!="none")
			$("#gotop").hide();
		}
	}
}