$(document).ready(function(){
	$("textarea[name='memo']").bind("focus click",function(){
		if($.trim($(this).val())=="在此填写关于回报内容的具体选择或者任何你想告诉项目发起人的话")
		{
			$(this).val("");			
		}		
	});
	
	$("textarea[name='memo']").bind("blur",function(){
		if($.trim($(this).val())=="")
		{
			$(this).val("在此填写关于回报内容的具体选择或者任何你想告诉项目发起人的话");			
		}		
	});
	
	//minus 减
	$("#minus").bind('click',function(){
		var num=parseInt($("#buy_num").val());
		var hidden_tip=0;
		if(num <=1)
			num=1;
		else
		{
			num -=1
		}
		if(maxbuy >0 && remain_user_buy ==0)
		{
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}else{
			$("#buy_num").val(num);
		
			var buy_tip_view=$("#buy_tip").is(":visible");
			if(buy_tip_view)
			{
				if(is_limit_user ==1 && limit_user >0)
				{
					var useful_count =limit_user - support_count;
					if(useful_count <0) useful_count=0;
					
					if((maxbuy >0 &&  useful_count <=remain_user_buy && num<= useful_count) || (maxbuy >0 &&  useful_count >remain_user_buy && num<= remain_user_buy) )
					{
						$("#buy_tip").addClass("hidden");
					}
					else
					{
						if(num <=useful_count)
							$("#buy_tip").addClass("hidden");
					}
				}else if(maxbuy >0 && num <=maxbuy)
				{
					$("#buy_tip").addClass("hidden");
				}
			}
		}
		
		
	});
	
	//plus 加
	$("#plus").bind('click',function(){
		var num=parseInt($("#buy_num").val());
		var buy_tip='';
		var num_view =num+1;
		if (maxbuy > 0 && remain_user_buy == 0) {
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}
		else {
			if( maxbuy >0 && num >=remain_user_buy)
			{
				num_view=remain_user_buy;
				buy_tip='最多抽'+remain_user_buy+'次';
			}
	
			if(is_limit_user ==1 && limit_user >0)
			{
				var useful_count =limit_user - support_count;
				if(useful_count <0)
					useful_count=0;
					
				if(num_view >useful_count)
				{
					num_view=useful_count;
					buy_tip='库存不足，最多抽'+useful_count+'次';
				}
			}
			
			$("#buy_num").val(num_view);
			if(buy_tip !='')
			{
				$("#buy_tip").show();
				$("#buy_tip").html(buy_tip);
				setTimeout(function(){
					$("#buy_tip").fadeOut("slow");
				},1000)
			}else
			{
				$("#buy_tip").hide();
			}
		}
		
	});
	
	//buy_num change
	$("#buy_num").bind('change',function(){
		var num=parseInt($("#buy_num").val());
		var buy_tip='';
		var num_view =num;
		//alert(num);
		if (maxbuy > 0 && remain_user_buy == 0) {
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}
		else {
			//limit_user remain_user_buy maxbuy
			if( maxbuy >0 && num > remain_user_buy)
			{	
				num_view=remain_user_buy;
				buy_tip='最多抽'+remain_user_buy+'次';
			}
			 
			if(is_limit_user ==1 && limit_user >0)
			{
				var useful_count =limit_user - support_count;
				if(useful_count <0)
					useful_count=0;
					
				if(num_view >useful_count)
				{
					num_view=useful_count;
					buy_tip='库存不足，最多抽'+useful_count+'次';
				}
			}
			
			$("#buy_num").val(num_view);
			if(buy_tip !='')
			{
				$("#buy_tip").show();
				$("#buy_tip").html(buy_tip);
				setTimeout(function(){
					$("#buy_tip").fadeOut("slow");
				},1000);
			}else
			{
				$("#buy_tip").hide();
			}
		}
	});
	
	bind_cart_form();
});




function bind_cart_form()
{
	$("#cart_form").find("#stand_by").bind("click",function(){
		$("#cart_form").submit();
	});
	$("#cart_form").bind("submit",function(){
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}