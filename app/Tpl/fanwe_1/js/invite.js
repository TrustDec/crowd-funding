$(document).ready(function(){
	bind_add_invite();
});

function bind_add_invite()
{	
	$("#add_invite").bind("click",function(){		
		var ajaxurl = $(this).attr("url");
		$.ajax({ 
			url: ajaxurl,
			type: "POST",
			dataType: "json",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					$(".html_add_invite").html(ajaxobj.html);
				}
				else
				{
					$.weeboxs.open(ajaxobj.html, {boxid:'user_login',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'用户登录',width:940,type:'wee'});
				}
			},
			error:function(ajaxobj)
			{
//				if(ajaxobj.responseText!='')
//				alert(ajaxobj.responseText);
			}
		});
	});
}

function check_u_name(obj,ajaxurl,check_type){
	if($(obj).val()){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajaxurl;
        var query = new Object();
        query.name = $(obj).val();
        window.setTimeout(function(){
	        $.ajax({ 
	            url: ajaxurl,
	            dataType: "json",
	            data:query,
	            type: "POST",
	            success: function(ajaxobj){
	                if(ajaxobj.status==1)
	                {
	                	var arr_name = ajaxobj.name;
	                	var menu_width = $("."+c_val+"_input").outerWidth()-2;
	                	var menu_top = $("."+c_val+"_input").outerHeight()+8;
	                	var html = '';
	                	var arr_name_length = arr_name.length;
	                	if(check_type == 'c_founder_team'){
	                		if(arr_name_length){
			                	$("."+c_val+"_menu").show();
			                	$("."+c_val+"_menu").css({width:menu_width+"px",top:menu_top+"px"});
			                	$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
			                	for(var i=0; i<arr_name_length; i++){
			                		html+='<div class="ui_select_choices_row clearfix">'+
										  '		<span class="row_img mr5"><img src="'+arr_name[i].image+'"></span>'+
										  '		<span class="row_text u_name">'+arr_name[i].user_name+'</span>'+
										  '		<input type="hidden" name="u_email" value="'+arr_name[i].email+'">'+
										  '		<input type="hidden" name="id" value="'+arr_name[i].id+'">'+
										  '		<input type="hidden" name="u_job" value="'+arr_name[i].job+'">'+
										  '</div>';
			                	}
			                	$("."+c_val+"_menu").find(".ui_select_choices_group").html(html);
			                	$(".ui_select_choices_row").live('click',function(){
									bind_yes_item.bind_yes_c_founder_team(this,c_val);
									$("."+c_val+"_hide").show();
									$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
									$("."+c_val+"_menu").hide();
									show_tip();
								});
		                	}
		                	else{
		                		$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
		                		$("."+c_val+"_hide").hide();
		                	}
	                	}
	                }
	                else{
	                
	                }
	            }
	        });
	        return false;
	    },300);
	}
}
function bind_yes_item(){
	this.bind_yes_c_founder_team = function(obj,c_val){
		var name = $(obj).find(".u_name").text();
		var email = $(obj).find("input[name='u_email']").val();
		var job = $(obj).find("input[name='u_job']").val();
		var id = $(obj).find("input[name='id']").val();
		
		console.log(name+','+email+','+job);
		$("."+c_val+"_form").find("."+c_val+"_name").val(name);
		$("."+c_val+"_form").find("."+c_val+"_job").val(job).attr("readonly","readonly").addClass("disabled");
		$("."+c_val+"_form").find("."+c_val+"_email").val(email).attr("readonly","readonly").addClass("disabled");
		$("."+c_val+"_form").find("input[name='user_id']").val(id);
	}
}
var bind_yes_item = new bind_yes_item();


function add_job_end_time(obj){
	if ($(obj).find("input[type='checkbox']").is(':checked')) {
		$(obj).find("input[type='checkbox']").attr('name', 'job_end_time');
		$(obj).prev("input[name='job_end_time']").remove();
	}
	else {
		$(obj).find("input[type='checkbox']").removeAttr('name');
		var html = '<input readonly="" type="text" class="small_textbox w100 jcDate mr10" rel="input-text" value="" name="job_end_time" placeholder="请选择时间">';
		$(obj).before(html);
		$(document).ready(function(){
			//选择日期控件
			$("input.jcDate").jcDate({
				IcoClass: "jcDateIco",
				Event: "click",
				Speed: 100,
				Left: -125,
				Top: 28,
				format: "-",
				Timeout: 100,
				Oldyearall: 17, // 配置过去多少年
				Newyearall: 0 // 配置未来多少年
			});
		});
		
	}
}
