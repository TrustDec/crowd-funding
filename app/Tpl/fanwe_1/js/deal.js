$(document).ready(function(){
	bind_buy_link();
	bind_zoom();
	bind_focus();
	attention_bind_focus();
	bind_faq();

	$("#J_btn_end").on('click',function(){
		if($(this).attr("rel") == 'preheat'){
			$.showErr("项目未开始！");
		}
		else{
			$.showErr("项目已结束！");
		}
	});
});

function bind_faq(){
	$(".faq_question").bind("click",function(){
		var id=$(this).attr("rel");
 		$(".faq_answer[rel="+id+"]").toggle("slow");
	});	
}

//定义复制文本
$.copyText = function(id)
{
	var txt = $(id).val();
	if(window.clipboardData)
	{
		window.clipboardData.clearData();
		var judge = window.clipboardData.setData("Text", txt);
		if(judge === true)
			$.showSuccess("已经拷贝到剪切板");
		else
			$.showErr("拷贝失败");
	}
	else if(navigator.userAgent.indexOf("Opera") != -1)
	{
		window.location = txt;
	} 
	else if (window.netscape) 
	{
		try
		{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}
		catch(e)
		{
			$.showErr("非IE内核，无法拷贝");
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
			return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans)
			return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;
		str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
		$.showSuccess("已经拷贝到剪切板");
	}
};

function bind_buy_link()
{
	$(".buy_deal_item").bind("click",function(){
		location.href = $(this).attr("url");
	});
}

function bind_zoom()
{
	$(".image_item").bind("click",function(){
		var img = $(this).find("img").attr("rel");
		$.fancybox.open(img);
	});
	
}

function bind_focus()
{
	$(".focus_deal").bind("click",function(){
		focus_deal($(this).attr("id"));
	});
}

function focus_deal(id)
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=deal&act=focus&id="+id;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$(".focus_deal").removeClass("blue");
				$(".focus_deal").removeClass("gray");
				$(".focus_deal").removeClass("blue_hover");
				$(".focus_deal").removeClass("gray_hover");
				$(".focus_deal").removeClass("blue_active");
				$(".focus_deal").removeClass("gray_active");
				$(".focus_deal").addClass("gray");
				$(".focus_deal").attr("rel","gray");
				$(".focus_deal").find("div span").html("取消关注");
				
			}
			else if(ajaxobj.status==2)
			{
				$(".focus_deal").removeClass("blue");
				$(".focus_deal").removeClass("gray");
				$(".focus_deal").removeClass("blue_hover");
				$(".focus_deal").removeClass("gray_hover");
				$(".focus_deal").removeClass("blue_active");
				$(".focus_deal").removeClass("gray_active");
				$(".focus_deal").addClass("blue");
				$(".focus_deal").attr("rel","blue");
				$(".focus_deal").find("div span").html("立即关注");							
			}
			else if(ajaxobj.status==3)
			{
				$.showErr(ajaxobj.info);							
			}
			else
			{
				show_pop_login();
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}
function attention_bind_focus()
{
	$(".attention_focus_deal").bind("click",function(){
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
				$(".attention_focus_deal").attr("title","取消关注").removeClass("gz").addClass("qxgz");
				//$(".attention_focus_deal").html('<i></i>取消关注');
			}
			else if(ajaxobj.status==2)
			{
				$(".attention_focus_deal").attr("title","关注").removeClass("qxgz").addClass("gz");
				//$(".attention_focus_deal").html('<i></i>关注');
			}
			else if(ajaxobj.status==3)
			{
				$.showErr(ajaxobj.info);							
			}
			else
			{
				show_pop_login();
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}

// 项目封面切换
function scrollTo(obj){
	var i = $(obj).index();
	$(obj).addClass("active").siblings().removeClass("active");
	$(".img_show").find("img").eq(i).fadeIn(300).siblings().hide();
}