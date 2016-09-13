$(document).on("pageInit","#project-add", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});

	// 图片上传
	bind_del_image();
	get_file_fun("image_file");
	get_file_fun("update_log_icon_bj");
	get_file_more_fun("deal_images_file","image_more",5);

	bind_cate_select();
	bind_project_add_edit_form();
	
	set_earnings();
	$("select[name='is_earnings']").bind('change',function(){
		set_earnings();
	});
});
$(document).on("pageInit","#project-edit", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});

	// 图片上传
	bind_del_image();
	get_file_fun("image_file");
	get_file_fun("update_log_icon_bj");
	get_file_more_fun("deal_images_file","image_more",5);

	bind_cate_select();
	bind_project_add_edit_form();
	
	set_earnings();
	$("select[name='is_earnings']").bind('change',function(){
		set_earnings();
	});
});
// 删除已上传的图片
function bind_del_image() {
	$(".image_item").find(".remove_image").on("click",function() {
		del_image($(this));
		hide_imgupload();
	});
}

// 上传4张图片后，隐藏上传图片按钮
function hide_imgupload(num) {
	var pic_box_num = $("#image_box").find(".image_item").length;
	var $fileupload_box = $(".fileupload_box");
	pic_box_num == num ? $fileupload_box.hide() : $fileupload_box.show();
}

function del_image(o) {
	$(o).parent().remove();
}

function set_earnings(){
	var is_earnings=parseInt($("select[name='is_earnings']").val());
	if(is_earnings == 1)
	{
		$(".js_earnings_con").css("display","-webkit-box");
	}
	else{
		$(".js_earnings_con").css("display","none");
	}
}
function bind_cate_select() {
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
function bind_project_add_edit_form() {
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
		if($.trim($(this).find("input[name='limit_price']").val())=='')
		{
			$.alert("请输入筹款金额");
			return false;
		}
		if(isNaN($(this).find("input[name='limit_price']").val())||parseFloat($(this).find("input[name='limit_price']").val())<=0)
		{
			$.alert("请输入正确的筹款金额");
			return false;
		}
		if($.trim($(this).find("input[name='deal_days']").val())=='')
		{
			$.alert("请输入筹集天数");
			return false;
		}
		if(isNaN($(this).find("input[name='deal_days']").val())||parseInt($(this).find("input[name='deal_days']").val())<=0)
		{
			$.alert("请输入正确的筹集天数");
			return false;
		}
		if($.trim($(this).find("input[name='name']").val())=='')
		{
			$.alert("请填写项目标题");
			return false;
		}
		if($(this).find("input[name='name']").val().length>30)
		{
			$.alert("项目标题不超过30个字");
			return false;
		}
		if($(this).find("input[name='cate_id']").val()==''||$(this).find("input[name='cate_id']").val()==0)
		{
			$.alert("请选择项目分类");
			return false;
		}
		if($.trim($(this).find("select[name='province']").val())=='')
		{
			$.alert("请选择省份");
			return false;
		}
		if($.trim($(this).find("select[name='city']").val())=='')
		{
			$.alert("请选择城市");
			return false;
		}
		if($.trim($(this).find("input[name='image']").val())=='')
		{
			$.alert("上传封面图片");
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