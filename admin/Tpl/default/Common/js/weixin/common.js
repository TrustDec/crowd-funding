//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func)
{
	$.weeboxs.open(str, {boxid:'msg_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'错误',width:250,type:'wee',onclose:func});
};

$.showSuccess = function(str,func)
{
	$.weeboxs.open(str, {boxid:'msg_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:250,type:'wee',onclose:func});
};

$.showCfm = function(str,funo,func)
{
	$.weeboxs.open(str, {boxid:'msg_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'确认',width:250,type:'wee',onok:funo,onclose:func});
};

/*验证*/
$.minLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength >= length;
};

$.maxLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength <= length;
};
$.getStringLength=function(str)
{
	str = $.trim(str);
	
	if(str=="")
		return 0; 
		
	var length=0; 
	for(var i=0;i <str.length;i++) 
	{ 
		if(str.charCodeAt(i)>255)
			length+=2; 
		else
			length++; 
	}
	
	return length;
};

$.checkMobilePhone = function(value){
	/*if($.trim(value)!='')
		return /^\d{6,}$/i.test($.trim(value));
	else
		return true;*/
	var reg1 = /^(13[0-9]|145|147|15[0-3]|15[5-9]|18[0-9])[0-9]{8}$/;
	var reg2 = /^(\([0-9]{3,4}\)|[0-9]{3,4}\-)[0-9]{7,8}$/;
	var reg3 = /^[0-9]{7,8}$/;
	var str = $.trim(value);
	if (!reg1.test(str) && !reg2.test(str) && !reg3.test(str)) {
		return false;
	}
	else{
		return true;
	}
};
$.checkEmail = function(val){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; 
	return reg.test(val);
};
