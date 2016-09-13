$(document).ready(function(){
	bind_attention_focus();
});

function bind_attention_focus(){
	$(".attention_focus_deal").bind("click",function(){
		attention_focus_deal($(this).attr("id"));
	});
}
function attention_focus_deal(id)
{
	var ajaxurl = APP_ROOT+"/index.php?ctl=deal&act=focus&id="+id;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$(".attention_focus_deal").removeClass("attention");
				$(".attention_focus_deal").addClass("remove_attention");
				$(".attention_focus_deal").html(ajaxobj);
			}
			else if(ajaxobj.status==2)
			{
				$(".attention_focus_deal").removeClass("remove_attention");
				$(".attention_focus_deal").addClass("attention");	
				$(".attention_focus_deal").html(ajaxobj);
			}
			else if(ajaxobj.status==3)
			{
				$.showErr(ajaxobj.info);							
			}
			else
			{
				show_pop_login();
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}