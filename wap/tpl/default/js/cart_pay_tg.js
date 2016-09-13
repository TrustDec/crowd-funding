$(document).ready(function(){
	bind_pay_form();
});
function bind_pay_form()
{
	var pay_status=false;
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
  		var paypassword=$("input[name='paypassword']").val();
		if(paypassword==''){
			$.alert("请输入密码");
			return false;
		}
 		var ajaxurl =  APP_ROOT+"/index.php?ctl=ajax&act=check_paypassword";
		var query = $(this).serialize() ;
 		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				async:false,
				type: "POST",
				success: function(ajaxobj){
 					if(ajaxobj.status==1)
					{
 						pay_status= true;
					}
					else
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
						pay_status= false;		
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		if(pay_status){
  			return true;
		}else{
  			return false;
		}
 		
	});
}