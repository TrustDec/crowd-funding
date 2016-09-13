$(document).ready(function(){
	bind_cate_select();
	bind_project_form();
	bind_faq_func();
	$("select[name='is_earnings']").set_earnings();
	$("select[name='is_earnings']").bind('change',function(){
		$(this).set_earnings();
	});
});

function bind_faq_func()
{
	$("#add_faq").bind("click",function(){
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=add_deal_faq";
		$.ajax({ 
			url: ajaxurl,
			type: "POST",
			success: function(html){
				$("#faq_list").append(html);
				bind_faq_event();
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
	});
}

function del_faq(o)
{
	if($(".faq_item").length>1)
	$(o).parent().parent().parent().remove();
}

function bind_cate_select()
{
	$("#cate_id").bind("change",function(){
		$("#cate_id_last").val($(this).find("option:selected").attr("rel"));
		//alert($(this).attr("rel"));
	});
	/*
	$(".cate_list").find("span").bind("click",function(){
		$(".cate_list").find("span").removeClass("current");
		$(this).addClass("current");
		$("input[name='cate_id']").val($(this).attr("rel"));
	});*/
}

function bind_project_form()
{
	var KE = KindEditor;
	 
	
	$("input[name='name']").bind("keyup blur",function(){
		if($(this).val().length>30)
		{
			$(this).val($(this).val().substr(0,30));
			return false;
		}
		else
		$("#project_title").html($(this).val());
	});
	
	$("textarea[name='brief']").bind("keyup blur",function(){
		if($(this).val().length>75)
		{
			$(this).val($(this).val().substr(0,75));
			return false;
		}
		else
		$("#deal_brief").html($(this).val());
	});
	
	$("select[name='province']").bind("change",function(){
		var val = "";
		if($(this).val()=="")
			val = "省份";
		else
			val = $(this).val();
		$("#province").html(val);
	});
	
	$("select[name='city']").bind("change",function(){
		var val = "";
		if($(this).val()=="")
			val = "城市";
		else
			val = $(this).val();
		$("#city").html(val);
	});
	
	$("input[name='limit_price']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$(".limit_price").html($(this).val());
	});
	$("input[name='deal_days']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseInt($(this).val())<=0)
		{
			$(this).val("");
		}
		else if($(this).val().length>2)
		{
			$(this).val($(this).val().substr(0,2));
			$("#deal_days").html($(this).val().substr(0,2));
		}
		else
		$(".deal_days").html($(this).val());
	});

	$("#project_form").bind("submit",function(){
		
		if($(this).find("input[name='cate_id']").val()==''||$(this).find("input[name='cate_id']").val()==0)
		{
			$.showErr("请选择项目分类");
			return false;
		}
		if($.trim($(this).find("input[name='name']").val())=='')
		{
			$.showErr("请填写项目名称");
			return false;
		}
		if($(this).find("input[name='name']").val().length>30)
		{
			$.showErr("项目名称不超过30个字");
			return false;
		}
		if($.trim($(this).find("select[name='province']").val())=='')
		{
			$.showErr("请选择省份");
			return false;
		}
		if($.trim($(this).find("select[name='city']").val())=='')
		{
			$.showErr("请选择城市");
			return false;
		}
		if($.trim($(this).find("input[name='image']").val())=='')
		{
			$.showErr("上传封面图片");
			return false;
		}
		if($.trim($(this).find("input[name='limit_price']").val())=='')
		{
			$.showErr("请输入目标金额");
			return false;
		}
		if(isNaN($(this).find("input[name='limit_price']").val())||parseFloat($(this).find("input[name='limit_price']").val())<=0)
		{
			$.showErr("请输入正确的目标金额");
			return false;
		}
		if($.trim($(this).find("input[name='deal_days']").val())=='')
		{
			$.showErr("请输入上线天数");
			return false;
		}
		if(isNaN($(this).find("input[name='deal_days']").val())||parseInt($(this).find("input[name='deal_days']").val())<=0)
		{
			$.showErr("请输入正确的上线天数");
			return false;
		}
		
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		query+="&description="+ encodeURIComponent($("textarea[name='descript']").val());
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
							$("input[name='id']").val(ajaxobj.info);
							$.showSuccess("保存成功",function(){
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
		
	
	
	$("#savenow").bind("click",function(){
		$("input[name='savenext']").val("0");
		$("#project_form").submit();
	});
	$("#savenext").bind("click",function(){
		$("input[name='savenext']").val("1");
		$("#project_form").submit();
	});
}
$.fn.set_earnings=function(){
	var is_earnings=parseInt($(this).val());
	if(is_earnings == 1)
	{
		$(".js_earnings_con").show();
	}
	else{
		$(".js_earnings_con").hide();
	}
}
