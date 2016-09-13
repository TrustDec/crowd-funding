$(document).on("pageInit","#cart-index", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(left_money>=need_money){
			$("input[name='credit']").val(need_money);
			$("input[name='payment']").attr("disabled",true);
			count_total_money(need_money,0,0,need_money);
		}else{
			$("input[name='credit']").val(left_money);
			count_total_money(left_money,0,0,need_money);
		}

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-view_order", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(order_sm.credit_pay >0)
		{
			var pay_money_c = order_sm.credit_pay;
			$("input[name='credit']").val(order_sm.credit_pay);
			
		}
		else if(left_money >= need_money-order_sm.score_money){
			var money_pay=need_money-order_sm.score_money;
				money_pay=round2(money_pay,2);
			var pay_money_c = money_pay;
			
			$("input[name='credit']").val(money_pay);
			$("input[name='payment']").attr("disabled",true);
		}
		else{
			var pay_money_c = left_money;
			$("input[name='credit']").val(left_money);
		}
		
		if(order_sm.score>0)
		{
			$("input[name='score_check']").attr("checked","checked");
			$("input[name='pay_score']").val(order_sm.score);
			$("#score_trade_money").html("¥"+order_sm.score_money);
		}
		count_total_money(pay_money_c,order_sm.score,order_sm.score_money,need_money);

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-record_pay", function(e, pageId, $page) {
	// 选择银行列表
	choose_bank();
	bind_pay_form();
});
$(document).on("pageInit","#stock_transfer-go_transfer", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(order_sm.credit_pay >0)
		{
			var pay_money_c = order_sm.credit_pay;
			$("input[name='credit']").val(order_sm.credit_pay);
			
		}
		else if(left_money >= need_money-order_sm.score_money){
			var money_pay=need_money-order_sm.score_money;
				money_pay=round2(money_pay,2);
			var pay_money_c = money_pay;
			
			$("input[name='credit']").val(money_pay);
			$("input[name='payment']").attr("disabled",true);
		}
		else{
			var pay_money_c = left_money;
			$("input[name='credit']").val(left_money);
		}
		
		if(order_sm.score>0)
		{
			$("input[name='score_check']").attr("checked","checked");
			$("input[name='pay_score']").val(order_sm.score);
			$("#score_trade_money").html("¥"+order_sm.score_money);
		}
		count_total_money(pay_money_c,order_sm.score,order_sm.score_money,need_money);

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-mortgate_pay", function(e, pageId, $page) {
	bind_pay_tg_form();
});
// 选择银行列表
function choose_bank(){
	$(".pay_way_bank_list li").on('click',function(){
		$(".bank_list").addClass("hide");

		var $o = $(this);
		var $bank_list = $o.find(".bank_list");
		var disabled = $o.find("input[name='payment']").attr("disabled");

		if($bank_list.length && !disabled){
			$bank_list.removeClass("hide");
		}
	});
}

// 金额处理
function bind_money(){
	trade_score=parseInt(trade_score)>0?parseInt(trade_score):0;
	if(trade_score >0)
	{ 
		var score_db_money=parseFloat(parseInt(score/trade_score*100)/100);//保留两位小数
		var score_db_pay=parseInt(score_db_money*trade_score);
	}
	else{
		var score_db_money=0;//保留两位小数
		var score_db_pay=0;
	}
	
	$("input[name='ye_check']").attr("checked","checked");
	$("input[name='ye_check']").bind("click",function(){
		var pay_score=isNaN($("input[name='pay_score']").val())?0:parseInt($("input[name='pay_score']").val());
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
			
		var need_money_m=need_money-pay_score_money;
			need_money_m=round2(need_money_m,2);
		var pay_money_val=0;
		if($(this).is(':checked')){
			$("input[name='credit']").removeAttr("disabled");
			if(pay_score_money>=need_money)
			{
				$("input[name='credit']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(left_money>=need_money_m){
				pay_money_val=need_money_m;
				$("input[name='credit']").val(need_money_m);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}else{
				pay_money_val=left_money;
				$("input[name='credit']").val(left_money);
			}
		}else{
			$("input[name='credit']").val(0);
			$("input[name='payment']").removeAttr("disabled");
			$("input[name='credit']").attr("disabled","disabled");
		}
		count_total_money(pay_money_val,pay_score,pay_score_money,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	$("input[name='credit']").bind("blur",function(){
		var money=isNaN($(this).val())?0:round2($(this).val(),2);
		var pay_score=isNaN($("input[name='pay_score']").val())?0:parseInt($("input[name='pay_score']").val());
		
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
			
		var need_money_m=need_money-pay_score_money;
			need_money_m=round2(need_money_m,2);

		var pay_money_val=0;
		if(money >0){
			if(pay_score_money>=need_money)
			{
				$("input[name='credit']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(money>=need_money_m){
				pay_money_val=need_money_m;
				$("input[name='credit']").val(need_money_m);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}else{
				pay_money_val=money;
				$("input[name='credit']").val(money);
				$("input[name='payment']").removeAttr("disabled");
			}
		}else{
			$("input[name='credit']").val(0);
		}
		count_total_money(pay_money_val,pay_score,pay_score_money,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	
	$("input[name='score_check']").bind('click',function(){
		
		var credit_money=isNaN($("input[name='credit']").val())?0:parseFloat($("input[name='credit']").val());
		var need_money_s=need_money-credit_money;
			need_money_s=round2(need_money_s,2);
		
		var pay_score_val=0;
		var pay_score_money_val=0;
		if($(this).is(':checked')){
			$("input[name='pay_score']").removeAttr("disabled");
			if(credit_money>=need_money)
			{
				$("input[name='pay_score']").val(0);
				$("#score_trade_money").html("¥0");
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(score_db_money>=need_money_s){
				pay_score_val=parseInt(need_money_s*trade_score);
				pay_score_money_val=need_money_s;
				$("input[name='pay_score']").val(pay_score_val);
				$("#score_trade_money").html("¥"+need_money_s);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				
			}else{
				pay_score_val=score_db_pay;
				pay_score_money_val=score_db_money;
				$("input[name='pay_score']").val(score_db_pay);
				$("#score_trade_money").html("¥"+score_db_money);
			}
			
		}else{
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("¥0");
			$("input[name='pay_score']").attr("disabled",true);
			$("input[name='payment']").removeAttr("disabled");
		}
		
		count_total_money(credit_money,pay_score_val,pay_score_money_val,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	$("input[name='pay_score']").bind("blur",function(){
		var pay_score=isNaN($(this).val())?0:parseInt($(this).val());
		var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
			pay_score=parseInt(pay_score_money*trade_score);
			
		var credit_money=parseFloat($("input[name='credit']").val());
		var need_money_s=need_money-credit_money;
			need_money_s=round2(need_money_s,2);
		
		var pay_score_val=0;
		var pay_score_money_val=0;
		if(pay_score >0)
		{
			if(credit_money>=need_money)
			{
				$("input[name='pay_score']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				$("#score_trade_money").html("¥0");
			}
			else if(pay_score_money>=need_money_s){
				pay_score_val=parseInt(need_money_s*trade_score);
				pay_score_money_val=need_money_s;
				$("input[name='pay_score']").val(pay_score_val);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				$("#score_trade_money").html("¥"+pay_score_money_val);
			}else{
				pay_score_val=pay_score;
				pay_score_money_val=pay_score_money;
				$("input[name='payment']").removeAttr("disabled");
				$("input[name='pay_score']").val(pay_score);
				$("#score_trade_money").html("¥"+pay_score_money);
				
			}
		}
		else
		{	
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("¥0");
		}
		
		count_total_money(credit_money,pay_score_val,pay_score_money_val,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	$("input[name='payment']").bind("click",function(){
		var paytype=$(this).attr("paytype");
		if(paytype == 'offline')
		{
			$("input[name='ye_check']").attr("checked",false).parent(".ui_check").removeClass("ui_checked");
			$("input[name='ye_check']").attr("disabled",true);
			$("input[name='credit']").val(0);
			$("input[name='credit']").attr("disabled",true);
			
			$("input[name='score_check']").attr("checked",false).parent(".ui_check").removeClass("ui_checked");;
			$("input[name='score_check']").attr("disabled",true);
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("0");
			$("input[name='pay_score']").attr("disabled",true);
			$("#instation_pay").hide();
		}
		else{
			$("input[name='ye_check']").removeAttr("disabled");
			$("input[name='score_check']").removeAttr("disabled");
			$("#instation_pay").show();
		}
		count_total_money(0,0,0,0);
	});
}

// 统计金额
function count_total_money(pay_money,pay_score,pay_score_money,total)
{
	pay_money=parseFloat(pay_money);
	if(isNaN(pay_score_money)){
		pay_score_money = 0;
	}
	pay_score_money=parseFloat(pay_score_money);
	total=parseFloat(total);
	var online_pay_money=total-(pay_money+pay_score_money);
		online_pay_money=round2(online_pay_money,2);
	
	if(pay_money >0)
	{
		var html="-¥"+pay_money;
		$("#real_money_box").css("display","-webkit-box");
		$("#real_money_val").html(html);
	}else{
		$("#real_money_val").html("");
		$("#real_money_box").hide();
	}
	
	if(pay_score_money>0)
	{
		$("#real_score_box").css("display","-webkit-box");
		$("#real_score_money").html("-¥"+pay_score_money+"&nbsp;("+pay_score+"积分)");
	}else
	{
		$("#real_money").html("");
		$("#real_score_box").hide();	
	}
	
	if(pay_money>0 || pay_score_money>0)
	{
		$("#real_online_box").css("display","-webkit-box");
		$("#real_online_money").html("¥"+online_pay_money);
	}else{
		$("#real_online_money").html("");
		$("#real_online_box").hide();
	}
}

function bind_pay_form()
{
	var pay_status=false;
	var max_pay = parseFloat($(".pay_form").find("input[name='max_pay']").val());
	$(".pay_form").find(".ui-button").on("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
		var max_pay = $(".pay_form").find("input[name='max_pay']").val();
		//var max_credit = $(".pay_form").find("input[name='max_credit']").val();
		//var max_val = parseFloat(max_pay)<parseFloat(max_credit)?parseFloat(max_pay):parseFloat(max_credit);

 
		var money = $(".pay_form").find("input[name='credit']").val();
			money = isNaN(money)?0:parseFloat(money);
		var pay_score=$(".pay_form").find("input[name='pay_score']").val();
			pay_score=isNaN(pay_score)?0:parseInt(pay_score);
		
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
				
		var pay_money_score=money+pay_score_money;
			pay_money_score=round2(pay_money_score,2);
		var paypassword=$("input[name='paypassword']").val();
		if(pay_money_score >0 )
		{
			if(pay_money_score<max_pay)
			{	
				if($(this).find("input[name='payment']:checked").length==0)
				{
					$.alert("请选择支付方式");
					return false;
				}	
			}
		}
		else{
			if($(this).find("input[name='payment']:checked").length==0)
				{
					$.alert("请选择支付方式");
					return false;
				}	
		}
		if(paypassword==''){
			$.alert("请输入付款密码");
			return false;
		}
		
		var ajaxurl =  APP_ROOT+"/index.php?ctl=ajax&act=check_paypassword";
		var query = $(this).serialize();
	
		$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				async:false,
				type: "POST",
				success: function(ajaxobj){
					
					if(ajaxobj.status==1)
					{
 						pay_status= true;
					}
					else
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
						pay_status= false;		
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		if(pay_status){
  			return true;
		}else{
  			return false;
		}
 		
	});
}

function bind_pay_tg_form()
{
	var pay_status=false;
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
  		var paypassword=$("input[name='paypassword']").val();
		if(paypassword==''){
			$.alert("请输入密码");
			return false;
		}
 		var ajaxurl =  APP_ROOT+"/index.php?ctl=ajax&act=check_paypassword";
		var query = $(this).serialize() ;
 		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				async:false,
				type: "POST",
				success: function(ajaxobj){
 					if(ajaxobj.status==1)
					{
 						pay_status= true;
					}
					else
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
						pay_status= false;		
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		if(pay_status){
  			return true;
		}else{
  			return false;
		}
 		
	});
}