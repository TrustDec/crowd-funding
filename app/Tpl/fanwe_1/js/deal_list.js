$(document).ready(function(){
	bind_del();
	$(".is_invest_seccess").bind("click",function(){
		var invest_deal=$(this).attr("rel");
		var box_html=$("#invest_seccess_box").html();

		box_html=box_html.replace(/chh_invest_seccess/i,'invest_seccess');
		box_html=box_html.replace(/chh_invest_failure/i,'invest_failure');
		$.weeboxs.open(box_html, {boxid:'do_invest_seccess',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'是否融资成功',width:360,type:'wee',onopen:function(){
			do_invest_seccess("#invest_seccess",invest_deal);
			do_invest_failure("#invest_failure",invest_deal);
		}});
	});
	
});
function bind_del()
{
	$(".del_deal").bind("click",function(){
		var ajaxurl = $(this).attr("href");
		$.showConfirm("确定删除该记录吗？",function(){
			var query = new Object();
			query.ajax = 1;
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
			
		});
		return false;
	});
}
function do_invest_seccess(id,deal_id)
{
	$(id).on("click",function(){
		$.ajax({
			url:APP_ROOT+"/index.php?ctl=ajax&act=do_invest_seccess&id="+deal_id,
			type:"POST",
			dataType: "json",
			success:function(o){
				if(o.status ==1)
				{
					$.showSuccess(o.info);
					setTimeout(function(){
						location.reload();
					},300);
				}
				else{
					$.showErr(o.info)
				}
			}
		});
	});
}
function do_invest_failure(id,deal_id)
{
	$(id).on("click",function(){
			$.showConfirm("您确定失败吗",function(){
				$.ajax({
					url:APP_ROOT+"/index.php?ctl=ajax&act=do_invest_failure&id="+deal_id,
					type:"POST",
					dataType: "json",
					success:function(o){
						if(o.status ==1)
						{
							$.showSuccess(o.info);
							setTimeout(function(){
								location.reload();
							},3000);
						}
						else{
							$.showErr(o.info)
						}
					}
				});
			});
	});
}