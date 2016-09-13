$(document).ready(function(){
	$("input[name='company_name']").bind('keyup focus',function(){
		if($(this).val()){
			$(".ac-container").removeClass("hide");
			$(".newCo-text").html($(this).val());
			var ajaxurl = $(this).attr("ajaxurl");  //后端action
	        var query = new Object();
	        query.company_name = $(this).val();
	        $.ajax({ 
	            url: ajaxurl,
	            dataType: "json",
	            data:query,
	            type: "POST",
	            success: function(ajaxobj){
	                if(ajaxobj.status)
	                {
	                	// 存储已有的公司名称到数组
	                	var arr_yes_item_company_name = $(".ac-menu-yes-item").find("input[name='yes_item_company_name']").map(function(){return $(this).val();}).get();
	                	
	                	if(arr_yes_item_company_name.length){
	                		if(!has_contains(arr_yes_item_company_name,query.company_name)){
	                			add_ac_menu(ajaxobj);
	                		}
	                	}
	                	else{
	                		add_ac_menu(ajaxobj);
	                	}
	                	
	                	bind_yes_item(ajaxobj);
	                }
	                else{}                       
	            },
	            error:function(ajaxobj){}
	        });
		}
    });
	
	bind_no_item();
	// $("input[name='company_name']").bind('blur',function(){
	// 	$(".ac-container").addClass("hide");
	// });
});

// 创建新公司
function bind_no_item(){
	$(".ac-menu-no-item").on('click',function(){
		$("input[name='company_name']").val($(this).find(".newCo-text").text());
		$(".ac-container").addClass("hide");
		show_tip();
	});
}

// 有存在的公司
function bind_yes_item(ajaxobj){
	$(".ac-menu-yes-item").live('click',function(){
		var $company_logo_box = $(".company_logo_box");
		$("input[name='company_name']").val(ajaxobj.company_name);
		document.getElementById("company_p_status").options[ajaxobj.company_p_status].selected = true;
		$company_logo_box.find("input[name='company_logo']").val(ajaxobj.company_logo).end().find("#company_logo_image").attr("src",ajaxobj.company_logo).end().find("input[type='file']").attr("disabled","disabled").end().find(".img_upload").attr("disabled","disabled");
		$("textarea[name='company_brief']").val(ajaxobj.company_brief).attr("disabled","disabled");
		$(".ac-container").addClass("hide").find(".ac-menu-yes-item").remove();
		show_tip();
	});
}

// 添加已创建的公司到DOM
function add_ac_menu(ajaxobj){
	$(".ac-menu").prepend('<li class="ac-menu-item ac-menu-yes-item"><a href="javascript:void(0);" class="ng-binding"><div class="coList"><img src='+ajaxobj.company_logo+'>'+ajaxobj.company_name+'<input type="hidden" name="yes_item_company_name" value="'+ajaxobj.company_name+'"></div></a></li>');
}

// 数组里是否存在某个值
function has_contains(arr, val) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == val) {
            return true;
        }
        else{
        	return false;
        }
    }
}