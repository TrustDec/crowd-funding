$(function(){
	$("#idcard_number").bind("blur",function(){
		if($("#idcard_number").val()==''){
			$.showErr("请输入身份证号！");
			return false;
		}
		if(IdentityCodeValid($("#idcard_number").val())===true){
			//$.showSuccess("身份证号可以使用！");
		}else{
			$.showErr("请正确填写身份证号！");
			return false;
		}
	});
});
function check_data(){
	if($("#real_name").val()==''){
		$.showErr("请输入真实姓名！");
		return false;
	}
	if($("#idcard_number").val()==''){
		$.showErr("请输入身份证号！");
		return false;
	}
	if($("#idcard_zheng_u").val()==''){
		$.showErr("请上传身份证正面照片！");
		return false;
	}
	if($("#idcard_fang_u").val()==''){
		$.showErr("请上传身份证背面照片！");
		return false;
	}
	var result_url=$("#result_url").val();
	var ajax=$("#ajax").val();
	var identify_name=$("#real_name").val();
	var identify_number=$("#idcard_number").val();
	var ajaxurl=$("#ajaxurl").val();
	var idcard_zheng_u=$("#idcard_zheng_u").val();
	var idcard_fang_u=$("#idcard_fang_u").val();
	var query =new Object();
	query.ajax=ajax;
	query.identify_name=identify_name;
	query.identify_number=identify_number;
	query.idcard_zheng_u=idcard_zheng_u;
	query.idcard_fang_u=idcard_fang_u;
	$.ajax({
		url: ajaxurl,
		data:query,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==0){
				$.showErr(ajaxobj.info);
				return false;
			}else{
				window.location.href=result_url;
			}
		},
		error:function(ajaxobj)
		{
			$.showErr("系统繁忙，请您稍后重试！")
			return false;
		}
	});	
}
