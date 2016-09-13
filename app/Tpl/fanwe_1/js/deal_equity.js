$(document).ready(function(){
	
 	bind_cate_select();
	bind_project_form();
//	var default_val = $("#cate_id option:eq(0)").attr("rel");
//	$("#cate_id_last").val(default_val);
	
});
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
//	if($("#agencyAdd_stepone_form").find(".cate_list span.current").length>0)
//	{
//		$("#agencyAdd_stepone_form").find("input[name='cate_id']").val($("#project_form").find(".cate_list span.current").attr("rel"));
//	}	
//	else
//	{
//		$("#agencyAdd_stepone_form").find("input[name='cate_id']").val('');
//	}
	
	$("input[name='name']").bind("keyup blur",function(){
		if($(this).val().length>25)
		{
			$(this).val($(this).val().substr(0,25));
			return false;
		}
		else
		$("#project_name").html($(this).val());
	});
	$("input[name='tags']").bind("keyup blur",function(){
		if($(this).val().length>25)
		{
			$(this).val($(this).val().substr(0,25));
			return false;
		}
		else
		$("#project_label").html($(this).val());
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
		$("#price").html($(this).val());
	});
	
	$("#agencyAdd_stepone_form").bind("submit",function(){
		$('#cityid-1').removeAttr('disabled');
		$('#cityid-2').removeAttr('disabled');
		$('#found_company').removeAttr('disabled');
		if($.trim($(this).find("input[name='name']").val())=='')
		{
			$.showErr("请填写项目名称");
			return false;
		}
		if($(this).find("input[name='name']").val().length>25)
		{
			$.showErr("项目名称不超过25个字");
			return false;
		}
	/*	if($("#item_form").find("input[name='investor_authority']").val()=='')
		{
			$.showErr("请选择项目详细资料查看权限");
			return false;
		}
	*/	
		if($(this).find("input[name='cate_id']").val()==''||$(this).find("input[name='cate_id']").val()==0)
		{
			$.showErr("请选择项目分类");
			return false;
		}
		
		/*if($.trim($(this).find("input[name='tags']").val())=='')
		{
			$.showErr("请填写项目标签");
			return false;
		}*/
		if($(this).find("input[name='tags']").val().length>25)
		{
			$.showErr("项目标签不超过25个字");
			return false;
		}
		
		if($("#item_form").find("select[name='project_step']").val()=='')
		{
			$.showErr("请选择项目所属阶段");
			return false;
		}
		
		
		if($.trim($(this).find("input[name='business_employee_num']").val())=='')
		{
			$.showErr("请填写企业员工人数");
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
		
		if($("#item_form").find("select[name='business_is_exist']").val()=='')
		{
			$.showErr("请选择公司是否已经成立");
			return false;
		}
		if($("#item_form").find("select[name='business_is_exist']").val()=='1')
		{
			if($.trim($(this).find("input[name='business_create_time']").val())=='')
			{
				$.showErr("请填写企业成立时间");
				return false;
			}
		}
		if($("#item_form").find("select[name='has_another_project']").val()=='')
		{
			$.showErr("请选择是否有其他项目");
			return false;
		}
		
		if($.trim($(this).find("input[name='business_name']").val())=='')
		{
			$.showErr("请填写公司全称");
			return false;
		}
		
		if($.trim($(this).find("input[name='business_address']").val())=='')
		{
			$.showErr("请填写办公地址");
			return false;
		}
		
		if($.trim($(this).find("input[name='limit_price']").val())=='')
		{
			$.showErr("请输入融资金额");
			return false;
		}
		if(isNaN($(this).find("input[name='limit_price']").val())||parseFloat($(this).find("input[name='limit_price']").val())<=0)
		{
			$.showErr("请输入正确的融资金额");
			return false;
		}
		if($.trim($(this).find("input[name='transfer_share']").val())=='')
		{
			$.showErr("请输入出让的股份");
			return false;
		}
		if(parseFloat($(this).find("input[name='invote_mini_money']").val()) > parseFloat($(this).find("input[name='limit_price']").val()))
		{
			$.showErr("单投资人最低出资金额不能高于融资金额");
			return false;
		}
		if($("#item_form").find("input[name='business_stock_type']").val()=='')
		{
			$.showErr("请选择众筹股东成立的有限合伙企业入股方式");
			return false;
		}
		
		if($.trim($(this).find("textarea[name='business_descripe']").val())=='')
		{
			$.showErr("请企业项目简介");
			return false;
		}
		
		if($.trim($(this).find("input[name='image']").val())=='')
		{
			$.showErr("上传封面图片");
			return false;
		}
		
		
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		//query+="&description="+ encodeURIComponent(KE.util.getData("descript"));
 		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
				//	alert('ajaxobj.status');
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
		$("#agencyAdd_stepone_form").submit();
	});
	$("#savenext").bind("click",function(){
		$("input[name='savenext']").val("1");
		$("#agencyAdd_stepone_form").submit();
	});
}
