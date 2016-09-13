
$(document).ready(function(){
	hide();	
	route();
	allways();
	selectadd();
	/*uc_collect也删除钮*/
	$("#uc_collect_editor").click(function(){
		var this_value=$("#uc_collect_editor").html();
		if(this_value=="编辑")
		{
			$(".invest_tit .delete").show();
		   $(this).html("完成");
		}
		else
		{
			$(".invest_tit .delete").hide();
			$(this).html("编辑");
		}
	});
});

/*头部隐藏菜单的事件操作*/
function hide(){
    $(".hide").bind('click',function(){
            $(".hide_cont").slideToggle("fast"); 
            });
     }


/*页面刷新时圆圈很活泼的转动*/
function route(){
    $(".content_pic").addClass("route");

}


/*保证foot在窗口的底部*/
function allways(){
    var bodyheight=$(document.body).outerHeight(true);
    var windowheight=$(window).height();
    if (bodyheight<windowheight) {
        var mgheight=windowheight-bodyheight;
        $(".footer").css("margin-top",mgheight+'px');
    }
}


/*点击整行选中*/
function selectadd(){
    $(".bank_list ul li").click(function(){
        $(this).find(".mt").attr("checked","checked" );
    });
}

$(document).ready(function(){
	/*分页样式*/
	if($(".fy").children().eq(1).hasClass("current") &&  $(".fy .current").text()==1)
	{  	 
	 	   $(".fy").children().eq(0).addClass("disabled").attr("href","javascript:void;");	
	}
    if($(".fy").children().eq(-2).hasClass("current") &&  $(".fy").children().eq(-2).text()==$(".page_total").text())
	{		
			$(".btn2").eq(1).addClass("disabled").attr("href","javascript:void;");	
			
	}
	/*回到顶部*/
	$(window).scroll(function () {
var scrolltop = $(document).scrollTop();
var height=$(window).height();
var h=height;
if( scrolltop <h || scrolltop==0)
{ 
	$("#go_top").hide();
	$("#go_top").removeClass();
}
if( scrolltop >h)
{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity1");
}
if( scrolltop >2*h)

{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity2");
}	
if( scrolltop >h*3)
{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity3");
}		
if( scrolltop >h*4)
{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity4");
}
if( scrolltop >h*5)
{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity5");
}
if( scrolltop >h*6)
{ 
	$("#go_top").show();
	$("#go_top").removeClass().addClass("opacity6");
}

 })
 
});
