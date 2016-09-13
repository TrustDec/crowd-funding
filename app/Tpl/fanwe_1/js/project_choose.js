$(document).ready(function(){
	bind_project_choose();
	bind_project_create();
});

// 选择众筹类型
function bind_project_choose(){
	$(".choose_type").find(".ui_button").on('click',function(){
		var $o = $(this);
		var type_url = $o.attr("type_url");
		$o.addClass("checked").siblings().removeClass("checked");
		$(".choose_btn").find(".ui_button").attr("href",type_url);
	});
}

function bind_project_create(){
	var is_checked = $("input[name='flag']").attr("checked");
	var $btn_box = $(".project_choose_bottom"),
	$btn_create = $btn_box.find("#btn_create"),
	$btn_flag = $btn_box.find("#flag_checkbox");
	
	$btn_create.on('click',function(){
		if(!is_checked){
			$.showErr("请先阅读并同意条款");
			return false;
		}
	});

	$btn_flag.on('click',function(){
		is_checked = $(this).attr("checked");
	});
}
