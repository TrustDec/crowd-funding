$(document).on("pageInit","#account-set_repay", function(e, pageId, $page) {
	bind_repay_form();
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
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
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
});