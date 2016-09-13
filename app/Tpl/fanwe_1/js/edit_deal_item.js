$(document).ready(function(){
	bind_item_form();
	bind_del_image();
	bind_add_item();
	bind_cancel_item();
	bind_del_item();
	bind_submit_deal_btn();
	load_type_info(1);
	$("input[name='type']").bind('click',function(){
		
		var type=$(this).val();
		
		load_type_info(0,type);
	});
});
function bind_item_form()
{
	var $item_form = $("#item_form");
	$item_form.find("input[name='price']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$("#support_price").html($(this).val());
		$("#support_price_btn").html($(this).val());
	});
	
	$item_form.find("textarea[name='description']").bind("keyup blur",function(){
		$("#repaid_content").html($(this).val());
	});
	
	// 是否配送
	$item_form.find("select[name='is_delivery']").bind("change",function(){
		if($(this).val()==0)
		{
			$item_form.find("#delivery_fee_l_box").hide();
			$("#delivery_box").hide();
		}
		else
		{
			var type=$("input[name='type']:checked").val();
			$item_form.find("#delivery_fee_l_box").show();
			$("#delivery_box").show();
			if(type ==2)
			{
				html='<label class="control-label">运费:</label><div class="control-text">免运费</div>';
			}else{
				html='<label class="control-label">运费:</label>';
				html +='<div class="control-text"><input type="text" value="'+delivery_fee+'" class="textbox w100" name="delivery_fee">元</div>';
			}
			$("#delivery_fee_l_box").html(html);
		}
	});
	if($item_form.find("select[name='is_delivery']").val()==0)
	{
		$item_form.find("#delivery_fee_l_box").hide();
		$("#delivery_box").hide();
	}
	else
	{
		var type=$("input[name='type']:checked").val();
		$item_form.find("#delivery_fee_l_box").show();
		$("#delivery_box").show();
		if(type ==2)
		{
			html='<label class="control-label">运费:</label><div class="control-text">免运费</div>';
			$("#delivery_fee_l_box").html(html);
		}else{
			html='<label class="control-label">运费:</label>';
			html +='<div class="control-text"><input type="text" value="'+delivery_fee+'" class="textbox w100" name="delivery_fee">元</div>';
		}
		$("#delivery_fee_l_box").html(html);
	}
	$item_form.find("input[name='delivery_fee']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$("#delivery_fee_box").html($(this).val());

	});

	// 是否限购
	$item_form.find("select[name='is_limit_user']").bind("change",function(){
		if($(this).val()==0)
		{
			$item_form.find("#limit_user_l_box").hide();
			$("#limit_user_box").hide();
		}
		else
		{
			$item_form.find("#limit_user_l_box").show();
			$("#limit_user_box").show();
		}
	});
	if($item_form.find("select[name='is_limit_user']").val()==0)
	{
		$item_form.find("#limit_user_l_box").hide();
		$("#limit_user_box").hide();
	}
	else
	{
		$item_form.find("#limit_user_l_box").show();
		$("#limit_user_box").show();
	}
	$item_form.find("input[name='limit_user']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<=0)
		{
			$(this).val("");
		}
		else
		{
			$("#limit_user").html($(this).val());
			$("#remain_user").html($(this).val());
		}
	});
	
	// 是否分红
	$item_form.find("select[name='is_share']").bind("change",function(){
		if($(this).val()==0)
		{
			$item_form.find("#share_fee_l_box").hide();
			$("#share_box").hide();
		}
		else
		{
			$item_form.find("#share_fee_l_box").show();
			$("#share_box").show();
		}
	});
	if($item_form.find("select[name='is_share']").val()==0)
	{
		$item_form.find("#share_fee_l_box").hide();
		$("#share_box").hide();
	}
	else
	{
		$item_form.find("#share_fee_l_box").show();
		$("#share_box").show();
	}
	$item_form.find("input[name='share_fee']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$("#share_fee_box").html($(this).val());

	});

	// 回报时间
	$item_form.find("input[name='repaid_day']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<=0)
		{
			$(this).val("");
		}
		else
		{
			$("#repaid_day").html($(this).val());

		}
	
	});
}
function bind_del_image()
{
	$(".image_item").find("span").bind("click",function(){
		del_image($(this));
	});
}

function del_image(o)
{
	
	$(o).parent().remove();
	if($(".image_item").length==0)
	{
		$("#image_box_outer").hide();
	}
}

function bind_add_item()
{
	
	$("#add_item_btn").bind("click",function(){
		if($(".item_row").length>=10)
		{
			$.showErr("回报项目不能超过10个");
			return;
		}
		$("#add_item_row").hide();
		$("#add_item_form").show();
	});
}

function bind_cancel_item()
{
	$("#cancel_add").bind("click",function(){
		$("#add_item_form").slideUp(function(){
			$("#add_item_row").show();
		});
	});
}

function bind_del_item()
{
	$(".del_item").bind("click",function(){
		var ajaxurl = $(this).attr("href");
		var query = new Object();
		query.ajax = 1;
		$.showConfirm("确定删除该项吗？",function(){
			close_pop();
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

function bind_submit_deal_btn()
{
	$("#submit_deal_btn").bind("click",function(){
		var ajaxurl = $(this).attr("url");
		var jump = $(this).attr("jump");
		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status)
					{
						$.showSuccess(ajaxobj.info,function(){
							 location.href = jump;
						});
					}
					else
					{
						if(ajaxobj.jump!="")
							location.href = ajaxobj.jump;
						else
						$.showErr(ajaxobj.info);
					}
				},
				error:function(ajaxobj)
				{

				}
			});
	});
}

function load_type_info(load,type)
{
	if(load ==1)
	{
		type=$("input[name='type']:checked").val();
	}
	
	if(type==1){
		$(".type_0").hide();
		$(".type_2").hide();
		$("#item_name").html("无私奉献");
		$(".return_inner").hide();
		$(".free_innner").show();
		$("textarea[name='description']").bind("keyup blur",function(){
			$("#repaid_content1").html($(this).val());
		});
	}else if(type==2){
		$(".type_0").hide();
		$(".type_2").show();
		$("#item_name").html("抽奖");
		$(".return_inner").show();
		$(".free_innner").hide();
		bind_item_form();
		
	}else{
		$(".type_2").hide();
		$(".type_0").show();
		$("#item_name").html("支持");
		$(".return_inner").show();
		$(".free_innner").hide();
		bind_item_form();
	}
}