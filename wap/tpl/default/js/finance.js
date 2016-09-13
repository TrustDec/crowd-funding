$(document).on("pageInit","#finance-index", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate();
});
$(document).on("pageInit","#deal-show", function(e, pageId, $page) {
	// 查看更多回报
    $(".view_more_return_item").find(".item-link").on('click',function(){
      	$(".return_item").addClass("return_more_item");
      	$(".view_more_return_item").remove();
      	$.refreshScroller();
    });

    $(".J_lottery_pop").on('click',function(){
    	lottery_pop(item_id,item_price_format);
    });

    //抽奖
	function lottery_pop(deal_item_id,price){
		$.ajax({
			url:APP_ROOT+'/index.php?ctl=ajax&act=go_lottery_num&item_id='+deal_item_id,
			type:"GET",
			data:'',
			dataType:'json',
			success:function(o){
				if(o.status ==-1){
					$.showErr("请先登录",function(){
						href=APP_ROOT+'/index.php?ctl=user&act=login&deal_id='+deal_info_id;
						$.router.loadPage(href);
					});
				}
				else if(o.status ==1){
					$.modal({
						title: '抽奖¥'+price,
				      	text: o.html,
				      	buttons: []
					});
					bind_lottery();
				}
				else{
					$.showErr(o.info);
				}
					
			}
		});
	}
	function bind_lottery(){
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
					},1000)
				}else
				{
					$("#buy_tip").hide();
				}
			}
		});
		
		$("input[name='lottery_go_cart']").bind('click',function(){
			var num=parseInt($("input[name='num']").val());
			if(num <=0)
			{
				showErr("请输入数量");
				return false;
			}
			
			$("#ajax_form_lottery").submit();
		});
	}
	
	$(".button_n").bind("click",function(){
		$.closeModal();
	});
});
$(document).on("pageInit","#finance-company_show", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
	$(".J_attention_focus_company").on('click',function(){
		attention_focus_company(this);
	});
	function attention_focus_company(obj){
		cid=$(obj).attr("cid");
		var ajaxurl = APP_ROOT+"/index.php?ctl=finance&act=focus&cid="+cid;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					$(obj).addClass("active").html("取消关注");
				}
				else if(ajaxobj.status==2)
				{
					$(obj).removeClass("active").html("关注");
				}
				else if(ajaxobj.status==3)
				{
					$.showErr(ajaxobj.info);							
				}
				else
				{
					show_login();
				}
			},
			error:function(ajaxobj)
			{
	//			if(ajaxobj.responseText!='')
	//			alert(ajaxobj.responseText);
			}
		});
	}
});