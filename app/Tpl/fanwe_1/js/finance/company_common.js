$(document).ready(function(){
	//选择日期控件
	$("input.jcDate").jcDate({
	    IcoClass : "jcDateIco",
	    Event : "click",
	    Speed : 100,
	    Left :-125,
	    Top : 28,
	    format : "-",
	    Timeout : 100,
	    Oldyearall : 17,  // 配置过去多少年
	    Newyearall : 0  // 配置未来多少年
	});
});

// 自动强制前缀(http://)
function auto_write_focus(obj){
	if($(obj).val() == "http://" || $(obj).val() == ""){
  		$(obj).val("http://");
  	}
}
function auto_write_blur(obj){
  	if($(obj).val() == "http://"){
		$(obj).val("");
		$(obj).next(".holder_tip").show();
	}
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

// show_tooltip
function show_tooltip(obj){
	var tooltip_html = '<div class="tooltip top">'+
            		   '	<div class="tooltip_arrow"></div>'+
                       '	<div class="tooltip_inner"></div>'+
                       '</div>';
    var tooltip_content = $(obj).attr("tooltip");
    $(obj).after(tooltip_html);
    var $tooltip = $(".tooltip");
    $tooltip.fadeIn(300);
    $tooltip.css("position","absolute");
    $(".tooltip_inner").text(tooltip_content);
	var px = ($tooltip.outerWidth()-$(obj).outerWidth())/2;
	$tooltip.css("left",$(obj).position().left-px);
	$tooltip.css("top",$(obj).position().top-$tooltip.outerHeight());
}
function hide_tooltip(obj){
	if($(obj).next(".tooltip").length){
		$(obj).next(".tooltip").remove();
	}
}
function isURL(str_url) {// 验证url
var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
+ "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" // ftp的user@
+ "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
+ "|" // 允许IP和DOMAIN（域名）
+ "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
+ "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
+ "[a-z]{2,6})" // first level domain- .com or .museum
+ "(:[0-9]{1,4})?" // 端口- :80
+ "((/?)|" // a slash isn't required if there is no file name
+ "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
var re = new RegExp(strRegex);
return re.test(str_url);
}