$(document).on("pageInit","#score_goods_order-index", function(e, pageId, $page) {
	$(".Unfold_open").on('click',function(){
		var $obj = $(this);
		var $li = $obj.parent().parent();
		$obj.hide();
		$li.find(".order_detail_t_other").show();
	});
	$(".Unfold_close").on('click',function(){
		var $obj = $(this);
		var $order_detail_t_other = $obj.parent();
		$order_detail_t_other.hide();
		$order_detail_t_other.parent().find(".Unfold_open").show();
	});
	
	$(".del_order").click(function(){
		order_id=$(this).attr('rel');
		$.showConfirm("你确定要取消？",function(){
			ajaxurl=APP_ROOT+"/index.php?ctl=score_goods_order&act=del_order&id="+order_id+"&ajax=1";
			$.ajax({
				url:ajaxurl,
				type: "POST",
				dataType: "json",
				success:function(o){
					if(o.status == -1)
					{
						show_login();
					}
					else if(o.status == 1)
					{
						if(o.jump){
							$.showSuccess(o.info,function(){
								$.router.loadPage(o.jump);
							});
						}
						else{
							$.showSuccess(o.info);
						}
					}else{
						if(o.jump){
							$.showErr(o.info,function(){
								$.router.loadPage(o.jump);
							});
						}
						else{
							$.showErr(o.info);
						}
					}
				}
			});
		});
	});
});
