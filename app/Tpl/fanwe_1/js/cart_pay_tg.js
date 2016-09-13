$(document).ready(function(){
	
	bind_pay_form();
});



function bind_pay_form()
{
	var pay_status=false;
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
  		var paypassword=$("input[name='paypassword']").val();
		if(paypassword==''){
			$.showErr("请输入密码");
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