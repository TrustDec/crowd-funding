$(document).ready(function(){
	// 关注的城市二级联动
	switch_city("gz_province","gz_city");
	bind_ajax_setting_form();
});

// 添加关注城市
function btn_addCity(obj,province,city){
	var btn_id = $(obj).attr("id");
	var gz_city_id = btn_id.substring(4);
	var gz_province = $("select#"+province).find("option:selected").val();
	var gz_city = $("select#"+city).find("option:selected").val();
	var $gz_region_box = $(".gz_region_box");
 	if($gz_region_box.children().length < 3){
		if(gz_province && gz_city){
			if($gz_region_box.children().length == 2){
				$(".gz_region_select").hide();
			}
			$gz_region_box.append("<label><span class='gz_region'>"+gz_province+"."+gz_city+"</span><i class='icon iconfont del_region' onclick='del_region(this);'>&#xe61f;</i><input type='hidden' name='gz_region["+gz_region_i+"]' value='"+gz_province+"."+gz_city+"' /></label>");
			gz_region_i++;
		}
		else{
			if(!gz_province){
				$.showErr("请选择省份");
			}
			else{
				$.showErr("请选择城市");
			}
		}
	}
	else{
		$(".gz_region_select").hide();
		$.showErr("最多只能添加3个关注城市");
		return false;
	}
}

// 删除添加的关注城市
function del_region(obj){
	$(obj).parent().remove();
	$(".gz_region_select").show();
}
var region_arr = new Array();
function do_region_arr(){
	for(var i=0; i<$("span[name='gz_region']").length; i++){
		region_arr[i] = $("span[name='gz_region']").eq(i).html();
	}
}

// 绑定ajax_form
function bind_ajax_setting_form()
{
	$(".ajax_setting_form").find(".ui-button").bind("click",function(){
 		$(".ajax_setting_form").submit();
	});
	$(".ajax_setting_form").bind("submit",function(){
		do_region_arr();
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		query.region_arr = region_arr;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}

// 检测字数
function checkstrlength(obj,left_words,words)
{
	var curStr=$(obj).val();
	var length_array=GetCharLength(curStr);
	var curLength=length_array['iLength'];
	var putLenght=words;

   	if(curLength>words){
		var substrAdd=length_array['substrAdd'];
		if(substrAdd >0)
			putLenght=putLenght+ Math.ceil(substrAdd/2)
        var num=$(obj).val().substr(0,putLenght);
		
		$(obj).val(num);
		$(left_words).text(0);
		$.showErr("最多输入"+words);
   	}
   	else{
   		var curLength=parseInt(curLength);
        $(left_words).text(words-curLength);
   	}
	
	// 获取字数长度
	function GetCharLength(str)
	{  
		var iLength = 0;
		var len = new Array(); 
		len["iLength"] =0;
		len["substrAdd"] =0;
	    for(var i = 0; i<str.length; i++){
			 
			if(str.charCodeAt(i) >255){  
		        len["iLength"] += 1;  
		    }  
		    else{  
				len["iLength"] += 0.5;
				if(len["iLength"] <= 300)
					 len["substrAdd"] +=1;
		    } 
		}  
	    return len;  
	} 
}