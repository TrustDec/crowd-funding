// 询价信息入库
function enquiry_two_save(obj){
	var $enquiry_two = $(obj);
	$enquiry_two.find(".button_y").bind("click",function(){
		var stock_val = $enquiry_two.find("input[name='stock_value']").val();  // 估值金额
		var money_val = $enquiry_two.find("input[name='money']").val();  // 投资金额
		var investment_reason = $enquiry_two.find("textarea[name='investment_reason']").val();  // 投资理由
		var funding_to_help = $enquiry_two.find("textarea[name='funding_to_help']").val();  // 资金帮助
		var is_partner = $enquiry_two.find("input[name='is_partner']").attr("checked"); // 是否愿意担任
		if(!stock_val){
			$.showErr("项目估值不能为空！");
			return false;
		}
		if((isNaN(stock_val)||parseFloat(stock_val)<=0)||stock_val=='')
		{
			$.showErr("请输入正确的估值金额");
			return false;
		}
		if((isNaN(money_val)||parseFloat(money_val)<=0)||money_val=='')
		{
			$.showErr("请输入正确的投资金额");
			return false;
		}
		if(!investment_reason){
			$.showErr("投资理由不能为空！");
			return false;
		}
		if(!funding_to_help){
			$.showErr("资金帮助不能为空！");
			return false;
		}
		// if(!is_partner)
		// {
			// $.showErr("请选择愿意担任！");
			// return false;
		// }
		var ajaxurl = $enquiry_two.attr("action");
		var query = $enquiry_two.serialize();
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
						location.reload();
					});
				}
				 
			}
		});
		return false;
	});
	$enquiry_two.find(".button_n").bind("click",function(){
		$.weeboxs.close();
	});
}