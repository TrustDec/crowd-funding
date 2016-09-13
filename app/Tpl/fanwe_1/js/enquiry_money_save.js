function enquiry_money_save(obj){
	var $add_enquiry = $(obj);
	$add_enquiry.find(".button_y").bind("click",function(){
		var money_val = $add_enquiry.find("input[name='money']").val();  // 投资金额
		var is_partner = $add_enquiry.find("input[name='is_partner']").attr("checked"); // 是否愿意担任
		if((isNaN(money_val)||parseFloat(money_val)<=0)||money_val=='')
		{
			$.showErr("请输入正确的投资金额");
			return false;
		}
		// if(!is_partner)
		// {
			// $.showErr("请选择愿意担任！");
			// return false;
		// }
		var ajaxurl = $add_enquiry.attr("action");
		var query = $add_enquiry.serialize();
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
					// 投资成功！
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