$(document).on("pageInit","#score_good_show-index", function(e, pageId, $page) {
	$("#go_check_order").on('click',function(){
		var num=parseInt($("input[name='num']").val());
		if(!parseInt(is_login))
		{
			$.showErr("请先登录",function(){
				$.router.loadPage(login_url);
			});
			return false;
		}
		
		if(!num)
		{
			$.showErr("请填写数量");
			return false;
		}
		$("form[name='score_form']").submit();
	});
	
	//minus 减
	$("#minus").on('click',function(){
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
	$("#plus").on('click',function(){
		if(!parseInt(is_login))
		{
			$.showErr("请先登录",function(){
				location.href=login_url;
			});
		}
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
	$("#buy_num").on('change',function(){
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
				},1000)
			}else
			{
				$("#buy_tip").hide();
			}
		}
	});
	
});