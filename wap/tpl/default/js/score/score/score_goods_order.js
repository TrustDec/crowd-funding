$(document).ready(function(){
	$(".Unfold").bind('click',function(){
		var $obj = $(this);
		var $li = $obj.parent().parent();
		if($li.hasClass("li_mini")){
			$obj.html('[&nbsp;收起&nbsp;]');
			$li.css({height:"auto"});
			$li.removeClass("li_mini");
		}
		else{
			$obj.html('[&nbsp;展开&nbsp;]');
			$li.css({height:"207px"});
			$li.addClass("li_mini");
		}
	});
	
	$(".del_order").bind('click',function(){
		order_id=$(this).attr('rel');
		$.confirm("你确定要取消？",function(){
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
								location.href=o.jump;
							});
						}
						else{
							$.showSuccess(o.info);
						}
					}else{
						if(o.jump){
							$.showErr(o.info,function(){
								location.href=o.jump;
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
