$(document).ready(function(){
	$("#export_support").export_support();
});
function bind_repay_form(){
	$(".set_repay").bind("click",function(){
		if($("input[name='logistics_company']").length){
			if($("input[name='logistics_company']").val() == ''){
				$.showErr("物流公司名称不能为空！");
				return false;
			}
			if($("input[name='logistics_company']").val() == ''){
				$.showErr("物流公司名称不能为空！");
				return false;
			}
			if($("input[name='logistics_links']").val() == ''){
				$.showErr("物流链接地址不能为空！");
				return false;
			}
			if($("input[name='logistics_number']").val() == ''){
				$.showErr("物流编号不能为空！");
				return false;
			}
		}
		$("#repay_form_"+$(this).attr("id")).submit();
	});
	$(".repay_form").bind("submit",function(){
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}

$.fn.export_support = function(){
	$(this).bind("click",function(){
		var type=$(this).attr("rel");
		var data_id=$(this).attr("data_id");
		var user_name=$("input[name='user_name']").val();
		var mobile=$("input[name='mobile']").val();
		var repay_status=$("select[name='repay_status']").val();

		if(type ==1)
			var url = APP_ROOT+'/index.php?ctl=account&act=export_support_1';
		else
			var url = APP_ROOT+'/index.php?ctl=account&act=export_support_0';
		url +='&id='+data_id+'';
		url +='&type='+type+'';
		url +='&user_name='+user_name+'';
		url +='&mobile='+mobile+'';
		url +='&repay_status='+repay_status+'';
		
		location.href=url;
	});
} 