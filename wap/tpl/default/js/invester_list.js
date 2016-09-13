$(document).on("pageInit","#investor-invester_list", function(e, pageId, $page) {
	$("#choose_show").on('click',function(){
		if($("#choose_box").css("display")=="none"){
			$(this).html('筛选<i class="icon iconfont">&#xe607;</i>');
			$("#choose_box").show();
		}
		else{
			$(this).html('筛选<i class="icon iconfont">&#xe607;</i>');
			$("#choose_box").hide();

		}
	});
});