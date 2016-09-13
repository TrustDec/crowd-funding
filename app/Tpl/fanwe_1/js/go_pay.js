$(document).ready(function(){
	bind_pay_form();
});

function bind_pay_form()
{
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
		if($.trim($(this).find("input[name='money']").val())=="" || parseFloat($(this).find("input[name='money']").val())<=0)
		{
			$.showErr("请输入金额");
			return false;
		}
 		if($(this).find("input[name='payment']:checked").length==0&&payType==0)
		{
 			$.showErr("请选择支付方式");
			return false;
		}
		else
		{
 			if(payType==1){
				show_tg_tip();
			}else{
				show_pay_tip();
			}
			
			return true;
		}
	});
}

function show_pay_tip()
{
	var html =  '<div class="pay_tip_box">'+
				'	<div class="empty_tip" style="font-size:14px;">请您在新打开的网银或第三方支付页面上完成付款</div>'+
				'	<div class="blank"></div>'+
				'	<div class="choose" style="font-size:14px;">付款后请选择：</div>'+
				'	<div class="blank15"></div>'+
				'	<div class="button_row tc">'+
				'   	<span class="ui-button ui-center-button theme_bgcolor" id="check_payment" rel="green">支付成功</span>'+
				'   	<span class="ui-button ui-center-button bg_red" id="choose_payment" rel="blue">支付遇到问题</span>'+
				'	</div>'+
				'</div>';
	$.weeboxs.open(html, {boxid:'pay_tip',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'提示',width:450,type:'wee'});

	$("#choose_payment").bind("click",function(){
		close_pop();
	});
	$("#check_payment").bind("click",function(){
		location.href = $("#back_url").val();
	});
}

function show_tg_tip()
{
	var html =  '<div class="pay_tip_box">'+
				'	<div class="empty_tip" style="font-size:14px;">请您在新打开的网银或第三方支付页面上完成付款</div>'+
				'	<div class="blank"></div>'+
				'	<div class="choose" style="font-size:14px;">付款后请选择：</div>'+
				'	<div class="blank15"></div>'+
				'	<div class="button_row tc">'+
				'   	<span class="ui-button ui-center-button theme_bgcolor" id="check_payment" rel="green" style="width:100px">支付成功</span>'+
				'   	<span class="ui-button ui-center-button bg_red" id="choose_payment" rel="blue" style="width:130px">支付遇到问题</span>'+
				'	</div>'+
				'</div>';
	$.weeboxs.open(html, {boxid:'pay_tip',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'提示',width:450,type:'wee'});

	$("#choose_payment").bind("click",function(){
		close_pop();
	});
	$("#check_payment").bind("click",function(){
		location.reload();
	});
}