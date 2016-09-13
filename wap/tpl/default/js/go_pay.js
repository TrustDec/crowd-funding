$(document).on("pageInit","#account-incharge", function(e, pageId, $page) { 	
	var payType = 0;
	var ips_submit_lock = true;
	$(".J_SelectPayType1").on('click',function(){
		SelectPayType(this,0);
	});
	$(".J_SelectPayType2").on('click',function(){
		SelectPayType(this,1);
	});
	function SelectPayType(obj,i){
		$(obj).addClass("cur").siblings().removeClass("cur");
  		switch(i){
			case 0:
				payType = 0;
				$("input[name='payment']").attr("checked",false);
				$("#J_online_pay").show();
 				$("#J_ips_pay").hide();
				$("#J_ips_pay_1").hide();
				$(".pay_form").attr("action",APP_ROOT+"/index.php?ctl=account&act=go_pay");
				$("input[name='is_tg']").val(0);
 				break;
 			case 1:
				payType=1;
 				$("input[name='payment']").attr("checked","");
				payType = 1;
				$("#J_online_pay").hide();
 				//$("#J_ips_pay").show();
				//$("#J_ips_pay_1").show();
				url = APP_ROOT+"/index.php?ctl=collocation&act=DoDpTrade&user_type=0&user_id="+user_id+"&pTrdAmt="+$("input[name='money']").val();
				$(".pay_form").attr("action",url);
				$("input[name='is_tg']").val(1);
 				break;
		}
	}
	$("input[name='money']").bind("blur",function(){
		if(payType==1){
			url = APP_ROOT+"/index.php?ctl=collocation&act=DoDpTrade&user_type=0&user_id="+user_id+"&pTrdAmt="+$("input[name='money']").val();
 			$(".pay_form").attr("action",url);
			get_pay_url='{url_wap r="ajax#get_carry_fee"}';
			var query = new Object();
			query.money=$("input[name='money']").val();
			$.ajax({
				url: get_pay_url,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
 					if(ajaxobj.status==1){
 						 $("#incharge_fee").html(ajaxobj.fee+" 人民币(元)");
						 end_money=parseFloat(query.money)- parseFloat(ajaxobj.fee);
						 $("#incharge_fee_end").html(end_money+" 人民币(元)");
					}
				}
			});
		}else{
			$(".pay_form").attr("action",APP_ROOT+"/index.php?ctl=account&act=go_pay");
		}
	});
	
	bind_incharge_form();
});
$(document).on("pageInit","#account-pay", function(e, pageId, $page) { 
	bind_incharge_form();
});

function bind_incharge_form()
{
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
		input_money = $(this).find("input[name='money']").val();
 		if($.trim(input_money) == "" || input_money<=0)
		{
			$.alert("请输入充值金额");
			return false;
		}		
		is_tg=$("input[name='is_tg']").val();
		if($(this).find("input[name='payment']:checked").length==0&&is_tg==0)
		{
			$.alert("请选择支付方式");
			return false;
		}		
		else
		{
 			return true;
		}
		
	});
}