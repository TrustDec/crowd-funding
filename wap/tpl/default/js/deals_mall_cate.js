// 筛选分类 
function J_mall_cate(){
	var hideList_height = $(document).height();
	$(".hide_list").css("height",hideList_height+"px");
	
	$(".mall-cate>li").each(function(index){
		var $this = $(this);
		$this.on({
			click:function(e){
				e.stopPropagation();
				// 初始化
				$(".abbr").removeClass("webkit-box");
				$(".hide_list").hide()
				$("#second_list>ul").hide();
				if(!($this.hasClass("cur"))){
					$this.addClass("cur").siblings().removeClass("cur");
					$(".hide_list").show().find(".abbr").eq(index).addClass("webkit-box").find("#second_list>ul").eq(index).show();
					$("#first_list li").each(function(index){
						var $this = $(this);
						$this.click(function(e){
							e.stopPropagation();
							$(".second_list>ul").hide();
							$this.addClass("select").siblings().removeClass("select");
							$(".second_list>ul").eq(index).show();
						})
					})
				}
				else{
					$this.removeClass("cur");
					$(".abbr").eq(index).removeClass("webkit-box");
				}
			} ,
			change:function(){
				
			}
		});
	});
	$(".abbr").on("click",function(e){
		e.stopPropagation();
	});
	$(document).click(function(){
		$(".mall-cate>li").removeClass("cur");
		$(".abbr").removeClass("webkit-box");
		$(".hide_list").hide();
		$("#second_list>ul").hide();
	});
}