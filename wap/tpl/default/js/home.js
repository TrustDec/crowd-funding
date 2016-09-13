$(document).on("pageInit","#home-index", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
	$(".J_focus_show").on('click',function(){
		J_focus_show(this);
	});
	function J_focus_show(obj){
		var rel = $(obj).attr("rel");
		$(obj).addClass("cur").siblings().removeClass("cur");
		$("."+rel).show().siblings().hide();
	}
});
$(document).on("pageInit","#home-organize_list", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
});
$(document).on("pageInit","#home-deal_list", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 
});
function J_view_all(obj){
	var rel = $(obj).attr("rel");
	$("."+rel).addClass("autoheight_wrap");
	$(obj).remove();
}