function bindbankcard_confirm_save(obj){
	var $add_enquiry = $(obj);
	$add_enquiry.find(".button_y").bind("click",function(){
		close_pop();
		$(".ajax_loading_box").show();
		var requestid = $add_enquiry.find("input[name='requestid']").val(); 
		var validatecode = $add_enquiry.find("input[name='validatecode']").val(); 
		var ajaxurl = $add_enquiry.attr("action");
		var query = $add_enquiry.serialize();
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				$(".ajax_loading_box").hide();
				if(ajaxobj.status==1){
					// 绑卡成功！
					$.showSuccess(ajaxobj.info,function(){
						location.reload();
					});
				}
				if(ajaxobj.status==0){
					$.showErr(ajaxobj.info);
				}
			}
		});
		return false;
	});
	$add_enquiry.find(".button_n").bind("click",function(){
		$.weeboxs.close();
	});
}