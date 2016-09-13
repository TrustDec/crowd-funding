$(document).on("pageInit", "#project-add_item", function(e, id, page) {
	get_file_fun('image_file');
	$(".J_add_reward").on('click',function(){
		add_reward(user_info_id);
	});
	$(".J_cancel_add").on('click',function(){
		cancel_add();
	});
	var $project_add_form = $("#project_add_form");
	$project_add_form.find(".ui-button").bind("click",function(){
		var type=$project_add_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_add_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_add_form");
	});
	bind_del_image();
	bind_del_item();
	bind_submit_deal_btn();
	load_type_info(1);
	
	var $project_edit_form = $("#project_edit_form");
	$project_edit_form.find(".ui-button").on("click",function(){
		var type=$project_edit_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_edit_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_edit_form");
	});

	$("input[name='type']").bind('click',function(){
		var type=$(this).val();
		load_type_info(0,type);
	});

	function add_reward(){
		if($(".item_row").length>=10){
			$.alert("回报项目不能超过10个");
			return false;
		}
		$("#add_item_form").show();
		$("#project_add_item").hide();
		load_type_info(1);
		get_file_fun('image_file');
	}
	function cancel_add(){
		$("#add_item_form").hide();
		$("#project_add_item").show();
	}
});
$(document).on("pageInit", "#project-edit_item", function(e, id, page) {
	get_file_fun('image_file');
	bind_del_image();
	bind_del_item();
	bind_submit_deal_btn();
	load_type_info(1);
	
	var $project_edit_form = $("#project_edit_form");
	$project_edit_form.find(".ui-button").on("click",function(){
		var type=$project_edit_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_edit_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_edit_form");
	});

	$("input[name='type']").bind('click',function(){
		var type=$(this).val();
		load_type_info(0,type);
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
function hide_imgupload() {
	var pic_box_num = $("#image_box").find(".image_item").length;
	var $fileupload_box = $(".fileupload_box");
	pic_box_num == 4 ? $fileupload_box.hide() : $fileupload_box.show();
}

function del_image(o) {
	$(o).parent().remove();
}

function bind_del_item() {
	$(".del_item").bind("click",function(){
		var ajaxurl = $(this).attr("href");
		var query = new Object();
		query.ajax = 1;
		$.confirm("确定删除该项吗？",function(){
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
		});
		
		return false;
	});
}

function bind_submit_deal_btn() {
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
					alert(111);
					$.showSuccess(ajaxobj.info,function(){
					 	href = jump;
					 	alert(222);
					 	$.router.loadPage(href);
					});
				}
				else
				{
					if(ajaxobj.jump!=""){
						href = ajaxobj.jump;
						$.router.loadPage(href);
					}
					else{
						$.alert(ajaxobj.info);
					}
					
				}
			}
		});
		return false;
	});
}
function ischeck(obj) {
	if($(obj).val()==0){
		$(obj).parent().parent().next().hide();
	}
	else{
		$(obj).parent().parent().next().show().css("display","-webkit-box");
	}
}
function load_type_info(load,type)
{
	if(load ==1)
	{
		var type=$("input[name='type']:checked").val();
	}
	
	if(type==1){
		$(".type_0").hide();
		$(".type_2").hide();
	}else if(type==2){
		$(".type_0").hide();
		$(".type_2").css("display","-webkit-box");

			ischeck("input[name='is_delivery']:checked");
			ischeck("input[name='is_limit_user']:checked");
		
	}else{
		$(".type_2").hide();
		$(".type_0").css("display","-webkit-box");


			ischeck("input[name='is_delivery']:checked");
			ischeck("input[name='is_limit_user']:checked");
			ischeck("input[name='is_share']:checked");

		
	}
}