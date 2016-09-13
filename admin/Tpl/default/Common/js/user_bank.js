//切换地区
$(document).ready(function(){	
		$("select[name='region_lv2']").bind("change",function(){
			load_city();
		});
});
//增加
function add_userbank(user_id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=add&user_id="+user_id;
}
//编辑
//编辑跳转
function edit_userbank(id)
{
	var user_id=$("input[name='user_id']").val();
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id+"&user_id="+user_id;
}
//删除
function del_userbank(id,user_id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(!user_id)
	{
		user_id=$("input[name='user_id']").val();
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=delete&id="+id+"&user_id="+user_id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}
//增加
function add_user_bank(user_id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=userbank_add&user_id="+user_id;
}
//编辑
//编辑跳转
function edit_user_bank(id)
{
	var user_id=$("input[name='user_id']").val();
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=userbank_edit&id="+id+"&user_id="+user_id;
}
//删除
function del_user_bank(id,user_id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(!user_id)
	{
		user_id=$("input[name='user_id']").val();
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=userbank_delete&id="+id+"&user_id="+user_id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}
function load_city()
{
		var id = $("select[name='region_lv2']").find("option:selected").attr("rel");
		
		var evalStr="regionConf.r"+id+".c";

		if(id==0)
		{
			var html = "<option value=''>请选择城市</option>";
		}
		else
		{
			var regionConfs=eval(evalStr);
			evalStr+=".";
			var html = "<option value=''>请选择城市</option>";
			for(var key in regionConfs)
			{
				html+="<option value='"+eval(evalStr+key+".n")+"' rel='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
			}
		}
		$("select[name='region_lv3']").html(html);		
}
