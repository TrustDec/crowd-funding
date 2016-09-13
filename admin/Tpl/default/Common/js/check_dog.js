
var CHECK_DOG = false;
var CHECK_DOG_HASH = '';

//页面加载时调用此函数方法
function DogPageLoad()
{
	if (CHECK_DOG != true) return;
	
	var browser = DetectBrowser();
    if(browser == "Unknown")
    {
        alert("不支持该浏览器， 如果您在使用傲游或类似浏览器，请切换到IE模式");
        return ;
    }
    //createElementIA300() 对本页面加入IA300插件
    createElementIA300();
    //DetectActiveX() 判断IA300Clinet是否安装
    var create = DetectIA300Plugin();
	
   	if(create == false)
    {
        alert("插件未安装，请先下载安装浏览器插件!");
        return false;
    }
}

function check_dog(){

	if (CHECK_DOG != true) return true;
	
	var retVal = IA300_CheckExist();
	if(1 > retVal)
	{
		//IA300_GetLastError 为封装到JS文件的获取错误信息的方法,返回错误信息,根据错误信息到帮助文档查询具体错误
		alert("ErrorCode:"+IA300_GetLastError() +"   没有找到Key");
		return false;
	}else if(1 < retVal){
		alert("找到"+retVal+"把Key,我们只对第一把识别到的Key进行申请.请不要插多把Key!");
		return false ;
	}
	
	//打开USB Key,sIAPWD为USB Key的用户密码  
	var retVal = IA300_CheckPassword(CHECK_DOG_HASH);
	if(retVal != 0)
	{
		if(IA300_GetLastError() == 84){
			//IA300_GetLastError 为封装到JS文件的获取错误信息的方法,返回错误信息,根据错误信息到帮助文档查询具体错误
			alert("ErrorCode:"+IA300_GetLastError() +"   未找到USB Key!");
			return false;
		}else if(IA300_GetLastError() == 104){
			alert("ErrorCode:"+IA300_GetLastError() +"   USB Key密码错误!");
			return false;
		}
		alert("ErrorCode:"+IA300_GetLastError() +"   USB Key登录失败!");
		return false;
	}
	
	return true;
	
}

function check_dog2(){

	//alert(CHECK_DOG);
	
	if (CHECK_DOG == false) return true;
	
	var retVal = IA300_CheckExist();
	
	if(1 > retVal)
	{
		//IA300_GetLastError 为封装到JS文件的获取错误信息的方法,返回错误信息,根据错误信息到帮助文档查询具体错误
		alert("ErrorCode:"+IA300_GetLastError() +"   没有找到Key");
		location.href = LOGINOUT_URL;
		return false;
	}else if(1 < retVal){
		alert("找到"+retVal+"把Key,我们只对第一把识别到的Key进行申请.请不要插多把Key!");
		location.href = LOGINOUT_URL;
		return false ;
	}
	
	//打开USB Key,sIAPWD为USB Key的用户密码  
	var retVal = IA300_CheckPassword(CHECK_DOG_HASH);
	if(retVal != 0)
	{
		if(IA300_GetLastError() == 84){
			//IA300_GetLastError 为封装到JS文件的获取错误信息的方法,返回错误信息,根据错误信息到帮助文档查询具体错误
			alert("ErrorCode:"+IA300_GetLastError() +"   未找到USB Key!");
			location.href = LOGINOUT_URL;
			return false;
		}else if(IA300_GetLastError() == 104){
			alert("ErrorCode:"+IA300_GetLastError() +"   密码错误!");
			location.href = LOGINOUT_URL;
			return false;
		}
		alert("ErrorCode:"+IA300_GetLastError() +"   登录失败!");
		location.href = LOGINOUT_URL;
		return false;
	}
	
}