$(document).ready(function(){
	$("input[name='money']").val(invote_mini_money);
	$("#money").html(invote_mini_money);
	//minus 减
	$("#minus").bind('click',function(){
		var money = invote_mini_money;
		var num=parseInt($("#buy_num").val());
		if(num <=1)
			num=1;
		else
		{
			num -=1;
		}
		$("#buy_num").val(num);
		account_money(num,invote_mini_money);
	});
	
	//plus 加
	$("#plus").bind('click',function(){
		var money = invote_mini_money;
		var num=parseInt($("#buy_num").val());
		if(num < total_num){
			if(num <1)
				num=1;
			else
			{
				num=num+1;
			}
		}
		else{
			$.alert("投资份数不能超过"+total_num+"份");
		}
		$("#buy_num").val(num);
	
		account_money(num,invote_mini_money);
	});
	$("input[name='num']").bind('input propertychange',function(){
		var u_num = $(this).val();
		if(u_num > total_num){
			$.alert("投资份数不能超过"+total_num+"份");
			$(this).val(1);
			$("input[name='money']").val(invote_mini_money);
			$("#money").html(invote_mini_money);
			return false;
		}
		if(u_num==""){
			$(this).val(1);
			$("input[name='money']").val(invote_mini_money);
			$("#money").html(invote_mini_money);
			return false;
		}
		account_money(u_num,invote_mini_money);
	});
});
// 统计投资金额
function account_money(num,invote_mini_money){
	money = num*invote_mini_money;
	$("input[name='money']").val(money);
	$("#money").html(money);
}