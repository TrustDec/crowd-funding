function bind_project(o)
{
	var obj1 = $(".common-sprite2").parent();
	var obj2 = $(".common-sprite2").parent().parent();
	var href = obj1.attr("href");
 	var checked = $(o).attr("checked");
	if(checked){
	 	obj2.removeClass("bg_gray").addClass("theme_bgcolor");
		obj1.attr("href",href);
 	}else{
	 	obj2.removeClass("theme_bgcolor").addClass("bg_gray");
		obj1.attr("href","javascript:void(0);");
 	}
 	$(o).on('click',function(){
 		var checked = $(this).attr("checked");
		if(checked){
		 	obj2.removeClass("bg_gray").addClass("theme_bgcolor");
			obj1.attr("href",href);
	 	}else{
		 	obj2.removeClass("theme_bgcolor").addClass("bg_gray");
			obj1.attr("href","javascript:void(0);");
	 	}
 	});
}
