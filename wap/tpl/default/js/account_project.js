$(document).on("pageInit","#account-project", function(e, pageId, $page) {
	$(".J_btn_del_item").on('click',function(){
		var ajax_del_id = $(this).attr("ajax_del_id");
		var ajaxurl = APP_ROOT+"/index.php?ctl=project&act=del&id="+ajax_del_id;
	  	ajax_del_item(ajaxurl,ajax_del_id);
	});
	
});
$(document).on("pageInit","#account-project_invest", function(e, pageId, $page) {
	// 拒绝理由
	$(".refuse_reason").on("click",function(){
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=refuse_reason";
		var obj=new Object();
		obj.deal_id=$(this).attr("rel");
		$.ajax({ 
			url: ajaxurl,
			data:obj,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1){
					$.alert(data.info, '未通过原因');
				}else{
					$.showErr(data.info);
				}
			}
		});
		return false;
	});
	$(".J_btn_del_invest_item").on('click',function(){
		var ajax_del_id = $(this).attr("ajax_del_id");
		var ajaxurl = APP_ROOT+"/index.php?ctl=project&act=del&id="+ajax_del_id;
	  	ajax_del_item(ajaxurl,ajax_del_id);
	});
});