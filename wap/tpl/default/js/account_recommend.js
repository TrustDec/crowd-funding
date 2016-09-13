$(document).on("pageInit","#account-recommend", function(e, pageId, $page) {
	$(".view_deal").bind("click",function(){
		var $this = $(this);
		var $td = $this.parent().parent().find("td");
		var $hide_area = $this.parent().parent().next(".hide_area");
		if($hide_area.is(":hidden")){
			$this.html("关闭推荐理由");
			$td.css("borderBottom","0");
			$hide_area.show();
		}
		else{
			$this.html("查看推荐理由");
			$td.css("borderBottom","1px solid #f2f2f2");
			$hide_area.hide();
		}
	});

	$("#account_recommend tr").bind('mouseover mouseout', function(e){
		$(this).find(".deletebox").toggle();
	});
	ajax_delete_recommend();

	function ajax_delete_recommend(){
		$(".sc").bind("click",function(){
			if (confirm("确认要删除吗？")) {
				var id=$(this).attr("rel");
	          	var ajaxurl = APP_ROOT + "/index.php?ctl=ajax&act=ajax_delete_recommend";
				var query=new Object();
				query.id=id;
				$.ajax({
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
						if(ajaxobj.status==0){
							$.showErr(ajaxobj.info);
							return false;
						}
						if(ajaxobj.status==1){
							$.showSuccess(ajaxobj.info,function(){
								$.router.loadPage(window.location.href);
							});
							return false;
						}
						
					}
				});
	        }
		});
		return false;
	}
});