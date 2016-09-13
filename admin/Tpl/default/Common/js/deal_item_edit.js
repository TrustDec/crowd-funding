$(document).ready(function(){
	load_type_info(1);
	load_delivery();
	load_limit_user();
	load_share_fee();
	$("select[name='is_delivery']").bind("change",function(){load_delivery();});
	$("select[name='is_limit_user']").bind("change",function(){load_limit_user();});
	$("select[name='is_share']").bind("change",function(){load_share_fee();});
	$("input[name='virtual_person']").bind("blur",function(){
		
		var virtual_person=parseInt($("input[name='virtual_person']").val());
		var limit_user=parseInt($("input[name='limit_user']").val());
		var is_limit_user=parseInt($("select[name='is_limit_user']").val());
		
		if(is_limit_user ==1 && virtual_person > limit_user )
		{
			alert("虚拟购买人数不能大于限购人数");
			$("input[name='virtual_person']").focus();
		}
		
		if($("form[rel='deal_item_edit']").length >0)
		{
			var already=virtual_person + parseInt(support_count);
			if(is_limit_user ==1 && parseInt(support_count) >0 && already > limit_user)
			{
				alert('"虚拟购买人数('+virtual_person+')+支持人数('+support_count+')"不能大于限购人数');
				$("input[name='virtual_person']").focus();
			}
		}
	
		
	});
	
	$("input[name='limit_user']").bind("blur",function(){
		var virtual_person=parseInt($("input[name='virtual_person']").val());
		var limit_user=parseInt($("input[name='limit_user']").val());
		var is_limit_user=parseInt($("select[name='is_limit_user']").val());
		
		if(is_limit_user==1 && virtual_person > limit_user)
		{
			alert("限购人数不能小于虚拟购买人数");
			$("input[name='limit_user']").focus();
		}
		
		if($("form[rel='deal_item_edit']").length >0)
		{
			var already=virtual_person + parseInt(support_count);
			if(is_limit_user ==1 && parseInt(support_count) >0 && already > limit_user)
			{
				alert('限购人数小于"虚拟购买人数('+virtual_person+')+支持人数('+support_count+')"');
				$("input[name='limit_user']").focus();
			}
		}			
	});
	
	$("input[name='type']").bind('click',function(){
		type=$(this).val();
		load_type_info(0,type)
	});
	
});


function load_delivery()
{
	var is_delivery = $("select[name='is_delivery']").val();
	var type=$("input[name='type']:checked").val();
	var html='';
	if(is_delivery==0)
	{
		$("input[name='delivery_fee']").val('');
		$("#delivery_fee").hide();
	}
	else
	{
		$("#delivery_fee").show();
		if(type ==2)
		{
			html='<td class="item_title">运费:</td><td class="item_input">免运费</td>'
		}else
		{
			html='<td class="item_title">运费:</td><td class="item_input"><input type="text" class="textbox" name="delivery_fee" value="'+delivery_fee+'" /></td>'
		}
		$("#delivery_fee").html(html);
	}
}

function load_limit_user()
{
	var is_limit_user = $("select[name='is_limit_user']").val();
	if(is_limit_user==0)
	{
		$("input[name='limit_user']").val('');
		$("#limit_user").hide();
	}
	else
	{
		$("#limit_user").show();
	}
}
function load_share_fee()
{	
	var is_share = $("select[name='is_share']").val();
	if(is_share==0)
	{
		$("input[name='share_fee']").val('');
		$("#share_fee").hide();
	}
	else
	{
		$("#share_fee").show();
	}
}

function load_type_info(load,type)
{
	if(load ==1)
	{
		type=$("input[name='type']:checked").val();
	}
	
	if(type==1){
		$(".type_class").hide();
		$(".type2_class").hide();
		$("input[name='price']").removeClass("require");
		$("input[name='lottery_measure']").removeClass("require");
	}else if(type==2){
		$(".type_class").hide();
		$(".type2_class").show();
		if(!$("input[name='price']").hasClass("require")){
			$("input[name='price']").addClass("require");
		}
		if(!$("input[name='lottery_measure']").hasClass("require")){
			$("input[name='lottery_measure']").addClass("require");
		}
		load_delivery();
		load_limit_user();
	}else{
		$(".type2_class").hide();
		$(".type_class").show();
		if(!$("input[name='price']").hasClass("require")){
			$("input[name='price']").addClass("require");
		}
		$("input[name='lottery_measure']").removeClass("require");
		load_delivery();
		load_limit_user();
		load_share_fee();
	}
}
