$(document).ready(function(){
	// 自定义收货地址
	$(".ui_radiobox").bind('click',function(){
		$(this).hasClass("other_consignee_radio") ? $(".other_consignee").show() : $(".other_consignee").hide();
	});
	$(".attr_a").bind('click',function(){
		$(this).addClass("select_a").attr("active","true").siblings().removeClass("select_a").attr("active","false");
	});
	
	$("#score_do_order").bind("click",function(){
		var query = new Object();
		query.ajax=1;
	    query.id=$("input[name='id']").val();
		 query.memo=$("textarea[name='memo']").val();
		if(is_delivery ==1)
		{
			if(have_consignee ==1)
				query.consignee_id=$("input[name='consignee_id']:checked").val();
			else
				query.consignee_id=0;	
			if(query.consignee_id == 0)
			{ 	
				query.delivery_name = $("input[name='delivery_name']").val();
				query.delivery_province = $("select[name='province']").val();
				query.delivery_city = $("select[name='city']").val();
				query.delivery_addr = $("textarea[name='delivery_addr']").val();
				query.delivery_zip = $("input[name='delivery_zip']").val();
				query.delivery_tel = $("input[name='delivery_tel']").val();
				
				if(query.delivery_name == '')
					$.showErr("请输入收货人名称");
				if(query.delivery_province =='')
					$.showErr("请选择省份");
				if(query.delivery_city =='')
					$.showErr("请选择城市");
				if(query.delivery_addr == '')
					$.showErr("请输入详细地址");
				if(query.delivery_tel == '')
					$.showErr("请输入手机号码");
			}
			
			
			$("#delivery_time a").each(function(i,o){			
				if($(o).attr("active") == 'true')
				{	
					query.delivery_time=$(o).attr("rel");
					return false;
				}
			});	
		}
		
		query.paypassword=$("input[name='paypassword']").val();
		if(query.paypassword == '')
			$.showErr("请输入支付密码");
		
		var ajax_url=APP_ROOT+"/index.php?ctl=score_good_show&act=do_score_order";
		$.ajax({
			url:ajax_url,
			data:query,
			dataType: "json",
			type: "post",
			success:function(o){
				if(o.status ==-1)
					show_pop_login();
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