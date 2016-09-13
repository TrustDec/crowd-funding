function J_view_all(obj){
	var rel = $(obj).attr("rel");
	$("."+rel).addClass("autoheight_wrap");
	$(obj).remove();
}

// 公司介绍
function f_company_info(obj){
	this.scrollTo = function(obj){
		var i = $(obj).index();
		$(obj).addClass("active").siblings().removeClass("active");
		$(".banner-con").find(".item").eq(i).show().siblings().hide();
	}
}
var f_company_info = new f_company_info();

// show_tooltip
function show_tooltip(obj){
	var tooltip_html = '<div class="tooltip top">'+
            		   '	<div class="tooltip_arrow"></div>'+
                       '	<div class="tooltip_inner"></div>'+
                       '</div>';
    var tooltip_content = $(obj).attr("tooltip");
    if(tooltip_content){
    	$(obj).after(tooltip_html);
	    var $tooltip = $(".tooltip");
	    $tooltip.fadeIn(300);
	    $tooltip.css("position","absolute");
	    $(".tooltip_inner").text(tooltip_content);
		var px = ($tooltip.outerWidth()-$(obj).outerWidth())/2;
		$tooltip.css("left",$(obj).position().left-px);
		$tooltip.css("top",$(obj).position().top-$tooltip.outerHeight());
    }
}

function hide_tooltip(obj){
	if($(obj).next(".tooltip").length){
		$(obj).next(".tooltip").remove();
	}
}

function attention_focus_company(obj)
{
	cid=$(obj).attr("cid");
	var ajaxurl = APP_ROOT+"/index.php?ctl=finance&act=focus&cid="+cid;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$(obj).addClass("active").html("取消关注");
			}
			else if(ajaxobj.status==2)
			{
				$(obj).removeClass("active").html("关注");
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