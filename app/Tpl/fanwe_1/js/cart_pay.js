$(document).ready(function(){
	
	bind_pay_form();
});



function bind_pay_form()
{
	var pay_status=false;
	var max_pay = parseFloat($(".pay_form").find("input[name='max_pay']").val());
	//var max_credit = $(".pay_form").find("input[name='max_credit']").val();
	//var max_val = parseFloat(max_pay)<parseFloat(max_credit)?parseFloat(max_pay):parseFloat(max_credit);
	
	/*
	$(".pay_form").find("input[name='credit']").bind("keyup blur",function(){
		var money = $(this).val();
		if(isNaN(money)||parseFloat(money)<=0)
		{
			$(".pay_form").find("input[name='credit']").val("0");
		}
		else
		{
			if(parseFloat(money)>max_val)
			{
				$(".pay_form").find("input[name='credit']").val(max_val);
			}
			if(parseFloat(money)>=max_pay)
			{
				$(".pay_form").find("input[name='payment']:checked").attr("checked",false);
			}
		}
	});
	*/
	$(".pay_form").find(".ui-button").bind("click",function(){
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
		
		if(trade_score)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else	
			var pay_score_money=0;
		var paypassword=$("input[name='paypassword']").val();
		if(paypassword==''){
			$.showErr("请输入密码");
			return false;
		}
		
		pay_money_score=money+pay_score_money;
		pay_money_score=round2(pay_money_score,2);
		if(pay_money_score >0 )
		{
			if(pay_money_score<max_pay)
			{	
				if($(this).find("input[name='payment']:checked").length==0)
				{
					$.showErr("请选择支付方式");
					return false;
				}	
			}
		}
		else{
			if($(this).find("input[name='payment']:checked").length==0)
				{
					$.showErr("请选择支付方式");
					return false;
				}	
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
								location.href = ajaxobj.jump;
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
 			show_pay_tip();
  			return true;
		}else{
  			return false;
		}
 		
	});
}

function show_pay_tip()
{
	var html =  '<div class="pay_tip_box">'+
				'	<div class="empty_tip" style="font-size:14px;">请您在新打开的网银或第三方支付页面上完成付款</div>'+
				'	<div class="blank"></div>'+
				'	<div class="choose">付款后请选择：</div>'+
				'   <div class="blank15"></div>'+
				'	<div class="tc">'+
				'		<span class="ui-center-button theme_bgcolor" id="check_payment" rel="green">支付成功</span>'+
				'		<span class="ui-center-button bg_red" id="choose_payment" rel="blue">支付遇到问题</span>'+
				'	</div>'+
				'</div>'+
				'<div class="blank"></div>';
	$.weeboxs.open(html, {boxid:'pay_tip',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'提示',width:450,type:'wee'});

	$("#choose_payment").bind("click",function(){
		close_pop();
	});
	$("#check_payment").bind("click",function(){
		location.href = $("#back_url").val();
	});
}