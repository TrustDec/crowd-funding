//切换地区
$(document).ready(function(){	
	switch_city("province","city");
});
function switch_city(province,city){
	var city = city;
	$("select[name='"+province+"']").bind("change",function(){
		load_city(this,city);
	});
}
function load_city(obj,city)
{
	var id = $(obj).find("option:selected").attr("rel");
	
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
	$(obj).parent().find("select[name='"+city+"']").html(html);
	$(obj).parent().find("select[name='"+city+"']").ui_select({refresh:true});
}