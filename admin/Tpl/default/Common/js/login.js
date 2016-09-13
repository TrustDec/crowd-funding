$(document).ready(function(){
	//绑定提交按钮
	$("input[name='adm_name']").focus();
	$(".submit").bind("click",function(){ do_login();});
	$("input[name='adm_name']").bind("keypress",function(event){
		if(event.keyCode==13)
		{
			$("input[name='adm_password']").focus();
		}
	});
	$("input[name='adm_password']").bind("keypress",function(event){
		if(event.keyCode==13)
		{
			if(CHECK_DOG)
				$("input[name='adm_dog_key']").focus();
			else
				$("input[name='adm_verify']").focus();
		}
	});
	$("input[name='adm_dog_key']").bind("keypress",function(event){
		if(event.keyCode==13)
		{
			$("input[name='adm_verify']").focus();
		}
	});
	$("input[name='adm_verify']").bind("keypress",function(event){
		if(event.keyCode==13)
		{
			do_login();
		}
	})
	//绑定提交结束
	
	$("#verify").bind("click",function(){
		timenow = new Date().getTime();
		$(this).attr("src",$(this).attr("alt")+"&rand="+timenow);
	});
	
});

function do_login(){

	
	CHECK_DOG_HASH = $.trim($(".adm_dog_key").val());
	if (check_dog() == false) return;
	
	$(this).attr("disabled",true);
	
	//验证帐号
	if($.trim($(".adm_name").val())=='')
	{
		$(".adm_name").val("");
		$(".adm_name").focus();
		$("#login_msg").html(ADM_NAME_EMPTY);
		$("#login_msg").oneTime(2000, function() {
		    $(this).html("");
		    $(".submit").attr("disabled",false);
		    
		 });
		return;
	}	
	//验证密码
	if($.trim($(".adm_password").val())=='')
	{
		$(".adm_password").val("");
		$(".adm_password").focus();
		$("#login_msg").html(ADM_PASSWORD_EMPTY);
		$("#login_msg").oneTime(2000, function() {
		    $(this).html("");
		    $(".submit").attr("disabled",false);
		    
		 });
		return;
	}	
	
	//验证密码
	if($.trim($(".adm_verify").val())=='')
	{
		$(".adm_verify").val("");
		$(".adm_verify").focus();
		$("#login_msg").html(ADM_VERIFY_EMPTY);
		$("#login_msg").oneTime(2000, function() {
		    $(this).html("");
		    $(".submit").attr("disabled",false);
		    
		 });
		return;
	}	
	
	//表单参数
	var query = new Object();
	query.adm_name = $(".adm_name").val();
	query.adm_password = $(".adm_password").val();
	query.adm_dog_key = $(".adm_dog_key").val();
	query.adm_verify = $(".adm_verify").val();
	query.ajax = 1;
	url = $("form").attr("action");
	
	$(".adm_name").attr("disabled",true);
	$(".adm_password").attr("disabled",true);
	$(".adm_dog_key").attr("disabled",true);
	$(".adm_verify").attr("disabled",true);
	$.ajax({
		url: url, 
		data: query,
		type:"post",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				$("#login_msg").html(obj.info);
				$("#login_msg").oneTime(2000, function() {
				    $(this).html("");
				    location.href = L_jumpUrl;
				 });
				
			}
			else
			{
				$("#login_msg").html(obj.info);
				$("#login_msg").oneTime(1000, function() {
				    $(this).html("");
				    $(".submit").attr("disabled",false);
				    $(".adm_name").attr("disabled",false);
					$(".adm_password").attr("disabled",false);
					$(".adm_dog_key").attr("disabled",false);
					$(".adm_verify").attr("disabled",false);
					$("#verify").click();
				 });
			}
	}});
}