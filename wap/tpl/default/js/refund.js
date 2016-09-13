$(document).on("pageInit","#account-money_carry_log", function(e, pageId, $page) {
	$(".delrefund").on("click",function(){
		var refund_item_id = $(this).attr("rel");
		var ajaxurl = APP_ROOT+'/index.php?ctl=account&act=delrefund&id='+refund_item_id;
		var query = new Object();
		query.ajax = 1;
		$.confirm("确定删除该记录吗？",function(){
			$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						if(ajaxobj.status==1)
						{						
							close_pop();
							location.reload();
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
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
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
		
		});
		return false;
	});
});