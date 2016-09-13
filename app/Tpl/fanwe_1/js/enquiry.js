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
			$.showErr("投资份数不能超过"+total_num+"份");
		}
		$("#buy_num").val(num);
	
		account_money(num,invote_mini_money);
	});
	$("input[name='num']").bind('keyup blur',function(){
		var u_num = $(this).val();
		$(this).val(u_num.replace(/[^0-9]/g,''));
		u_num = $(this).val();
		if(u_num > total_num){
			$.showErr("投资份数不能超过"+total_num+"份");
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
	money = (parseFloat(num*invote_mini_money)).toFixed(2);
	$("input[name='money']").val(money);
	$("#money").html(money);
}