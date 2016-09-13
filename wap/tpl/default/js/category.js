$(document).on("pageInit","#category-index", function(e, pageId, $page) {
	$(".category-table").each(function(){
		var li_num = $(this).find("li").length;
		if(li_num<4){
			var left_num = 4-li_num;
			for (i = 0; i < left_num; i++){
				$(this).append("<li></li>");
			}
		}
		else{
			var left_num = li_num % 4;
			for (i = 0; i < left_num; i++){
				$(this).append("<li></li>");
			}
		}
	});
	$(".sub-category-table").each(function(){
		if(!($(this).find("li").html())){
			$(this).hide();
			$(this).prev().hide();
		}
	});
 	$("#top_search_hd .search_cate").bind('click',function(){
        var $obj=$(this);
        var i=$obj.index();
        $obj.attr("checked",true).addClass("cur").siblings().attr("checked",false).removeClass("cur");
        $("#categoryList .category_li").eq(i).show().siblings().hide();
    });
});