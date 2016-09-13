/*******************************************************
 *
 * 使用此JS脚本之前请先仔细阅读IA300帮助文档!
 * 
 * @author		Fuly
 * @version		3.0
 * @date		2012/6/28
 * @explanation	IA300 v3.0版用户存储区和安全存储区扩大至2K，增加
 * 				相应的扩展函数操作这两个区域同时保留之前操作存储区的函数
 *
**********************************************************/

var _IA300Client;
var _TimerErrorMessage;
var _ExpireUrl;

function IA300_GetInstance()
{
    if(_IA300Client == null)
    {
        _IA300Client = document.getElementById("IA300Client");
    }
	
	_IA300Client.Model = 0;
   return _IA300Client; 
}

/*******************************************************
*
* 函数名称：IA300_CheckPassword()
* 功    能：打开 USB Key
* 输    入：password：页面传递进来的密码,是USB Key的用户密码
* 返 回 值：返回值为0即成功，1为失败，用户密码不正确
* 说	明：此方法未打开方法,也是第判断USB Key是否合法的USB Key
*
**********************************************************/
function IA300_CheckPassword(password)
{
     return IA300_GetInstance().IA300Open(password);
}
/*******************************************************
*
* 函数名称：IA300_LogOut()
* 功    能：登出IA300USB Key
* 返 回 值：返回0则表示登出成功，否则为失败
* 说	明：和IA300Open成对使用，有打开则必须有登出USB Key
*
**********************************************************/
function IA300_LogOut() {
    return IA300_GetInstance().IA300Close();
}
/*******************************************************
*
* 函数名称：IA300_GetLastError()
* 功    能：获取IA300函数执行失败的错误码
* 返 回 值：返回值为错误代号
* 说	明：获取USB Key最后一次执行失败的错误信息，可结合帮
*           助文档或设号工具查询错误信息
*
**********************************************************/
function IA300_GetLastError()
{
    return IA300_GetInstance().IA300GetLastError();
}
/*******************************************************
*
* 函数名称：IA300_GetHardwareId()
* 功    能：获取USB Key的硬件ID
* 返 回 值：返回值为该USB Key的硬件ID，返回NULL为失败
* 说	明：获取IA300USB Key唯一硬件ID，若未获取到，可使用
*           IA300_GetLastError()方法来获取错误吗,然后查明
*           错误原因
*
**********************************************************/
function IA300_GetHardwareId()
{
	return IA300_GetInstance().IA300GetUID();
}
/*******************************************************
*
* 函数名称：IA300_RequestEnabled()
* 功    能：检查USB Key远程申请注册功能是否打开
* 返 回 值：返回值为0即禁止，1为打开
* 说	明：远程申请时请查找Key后调用此方法
*
**********************************************************/
function IA300_RequestEnabled()
{
     return IA300_GetInstance().RequestEnabled;
}
/*******************************************************
*
* 函数名称：IA300_SHA1WithSeedEx()
* 功    能：
* 返 回 值：
* 说	明：检测到Key后调用此方法
*
**********************************************************/
function IA300_SHA1WithSeedEx(RanDoms)
{
     return IA300_GetInstance().IA300SHA1WithSeedEx(RanDoms);
}
/*******************************************************
*
* 函数名称：IA300_GetMachineCode()
* 功    能：可以获取本地以太网MAC（MD5后值），服务端计算SHA1时，在随机数后连接 IA300GetMachineCode（）此值即可。
* 返 回 值：返回值为MD5后的MAC地址，否则失败
* 说	明：检测到Key后调用此方法
*
**********************************************************/
function IA300_GetMachineCode()
{
     return IA300_GetInstance().IA300GetMachineCode();
}

/*******************************************************
*
* 函数名称：IA300_CalculateClientHash()
* 功    能：进行硬件SHA1运算
* 输    入：randomMessageFromServer：32个字符的随机数
* 返 回 值：返回值为0即成功，1为失败,可用IA300_GetLastErr
*           or()获取USB Key错误信息
* 说	明：IA300Key生成客户端摘要值(SHA1算法)需要传入32位
*           字符随机数与Key中种子码计算生成
*
**********************************************************/
function IA300_CalculateClientHash(randomMessageFromServer)
{
	return IA300_GetInstance().IA300SHA1WithSeed(randomMessageFromServer);
}

/*******************************************************
*
* 函数名称：IA300_CheckExist()
* 功    能：检查USB Key是否存在
* 说	明：此方法结合IA300_StartCheckTimer方法可用来定时
*           检测USB Key是否存在,不存在即返回到指定页面(
*           _ExpireUrl)
*
**********************************************************/
function IA300_CheckExist()
{
	var rtn =IA300_GetInstance().IA300CheckExist();
    if(rtn < 1)
    {
        IA300_LogOut();
        if(_TimerErrorMessage != null)
        {
            alert(_TimerErrorMessage + "  Error Code: " +IA300Client.IA300GetLastError());
        }
        if(_ExpireUrl != null)
		{
			window.location = _ExpireUrl;
		}
	}
	return rtn;
}
/*******************************************************
*
* 函数名称：IA300_StartCheckTimer()
* 功    能：定时操作方法
* 输    入：interval：时间1000/秒；errMsg：输出的错误信息
*           logonUrl：跳转地址
* 说	明：此方法结合IA300_CheckExist方法可用来定时检测加
*           密Key是否存在,不存在即返回到指定页面(_ExpireUrl)
*
**********************************************************/
function IA300_StartCheckTimer(interval, errMsg, logonUrl)
{
    _TimerErrorMessage  = errMsg;
    _ExpireUrl = logonUrl;
    //定时检测
    window.setInterval(IA300_CheckExist, interval);
}

/*******************************************************
*
* 函数名称：IA300_ChangePassword()
* 功    能：修改密码
* 输    入：oldPassword：原始密码；newPassword：新密码
*           newPasswordConfirm：新密码确认
* 返 回 值：返回值1即原始密码为空，2即新密码确认失败,返回3
*           为修改失败，可用IA300_GetLastError()获取USB Key
*           错误信息
* 说	明：修改IA300Key密码，IA300打开后才能调用此方法，需
*           验证原密码，通过原有密码才能修改为新的密码
*
**********************************************************/
function IA300_ChangePassword(oldPassword, newPassword, newPasswordConfirm)
{
    if(oldPassword == "")
    {
        return 1;
    }
    if(newPassword != newPasswordConfirm)
    {
        return 2;
    }
    if( 0 !=  IA300_GetInstance().IA300ChangePassword(oldPassword,newPassword))
    {
        return 3;
    }
    return 0;
}
/*******************************************************
*
* 函数名称：IA300_ResetPasswordRequest()
* 功    能：修改密码
* 返 回 值：成功即返回生成的请求重置信息；失败可用
*           IA300_GetLastError()获取USB Key错误信息
* 说	明：生成IA300USB Key密码重置信息，每调用一次此方法，
*           生成的密码请求都是不同的，以最后一次调用生成的
*           信息发送到服务端，请求密码重置，由服务端进行处
*           理后生成一个经过密码重置的新密码
*
**********************************************************/
function IA300_ResetPasswordRequest()
{
	return IA300_GetInstance().IA300GenResetPwdRequest(); 
}
/*******************************************************
*
* 函数名称：IA300_ResetPassword()
* 功    能：修改密码
* 输    入：serverResponse：服务端密码重置新密码的信息
* 返 回 值：返回值为0即成功，1为失败,可用IA300_GetLastErr
*           or()获取USB Key错误信息
* 说	明：IA300USB Key密码重置，接受服务器端重置用户密码
*           信息，若返回值为0，密码被重置为服务器端定义的
*           新密码
*
**********************************************************/
function IA300_ResetPassword(serverResponse)
{
     return IA300_GetInstance().IA300ResetPassword(serverResponse);
}

/*******************************************************
*
* 函数名称：IA300WriteUserStorage()
* 功    能：用户数据区写入
* 输    入：UserStorage：IA300用户自定义数据
* 说	明：写入用户数据区的自定义数据（为明文，客户端写入）
*
**********************************************************/
function IA300_WriteUserStorage(UserStorage) {
    return IA300_GetInstance().IA300WriteUserStorage(UserStorage);
}

/*******************************************************
*
* 函数名称： IA300ReadUserStorage()
* 功    能：用户数据区读取
* 输    入：_length：IA300用户自定义数据 读取长度
* 说	明：读取用户数据区的自定义数据（为明文，客户端读取）
*
**********************************************************/
function IA300_ReadUserStorage(length) {
    return IA300_GetInstance().IA300ReadUserStorage(length);
}

/*******************************************************
*
* 函数名称：IA300_WriteStorage()
* 功    能：USB Key安全存储区写入
* 输    入：storage：IA300用户自定义写入存储区数据 
* 返 回 值：返回0,写入存储区数据成功
* 说	明：写入存储区用户自定义的数据(为密文,由服务端确定
*           并加密写入的数据)
*
**********************************************************/
function IA300_WriteStorage(storage) {
    return IA300_GetInstance().IA300SecureWriteStorage(storage);
}

/*******************************************************
*
* 函数名称：IA300_ReadStorage()
* 功    能：USB Key安全存储区读取
* 输    入：storage：IA300用户自定义读取数据长度 
* 返 回 值：返回长度内的数据
* 说	明：读取存储区用户自定义数据(为密文,需要服务端解读)
*
**********************************************************/
function IA300_ReadStorage(storage) {
    return IA300_GetInstance().IA300SecureReadStorage(storage);
}

/*******************************************************
*
* 函数名称： IA300_WriteUserStorageEx()
* 功    能：用户数据区写入
* 输    入：UserStorage：IA300用户自定义数据
* 说	明：扩展的用户数据区写入函数, 支持读取新版本2K大小数据区
*
**********************************************************/
function IA300_WriteUserStorageEx(nStartAddr, pBuffer) {
    return IA300_GetInstance().IA300WriteUserStorageEx(nStartAddr, pBuffer);
}
/*******************************************************
*
* 函数名称： IA300_ReadUserStorageEx()
* 功    能：用户数据区读取
* 输    入：_length：IA300用户自定义数据 读取长度
* 说	明：扩展的用户数据区读取函数, 支持读取新版本2K大小数据区
*
**********************************************************/
function IA300_ReadUserStorageEx(nStartAddr, nDataLen) {
    return IA300_GetInstance().IA300ReadUserStorageEx(nStartAddr, nDataLen);
}
/*******************************************************
*
* 函数名称： IA300_SecureWriteStorageEx()
* 功    能：USB Key安全存储区写入
* 输    入：storage：IA300用户自定义写入存储区数据 
* 返 回 值：返回0,写入存储区数据成功
* 说	明：扩展的安全数据区写入函数, 支持读取新版本2K大小数据区
*
**********************************************************/
function IA300_SecureWriteStorageEx(nStartAddr, pBuffer) {
    return IA300_GetInstance().IA300SecureWriteStorageEx(nStartAddr, pBuffer);
}
/*******************************************************
*
* 函数名称：IA300_SecureReadStorageEx()
* 功    能：USB Key安全存储区读取
* 输    入：nStartAddr:数据读取地址; nDataLen：读取数据长度 
* 返 回 值：返回长度内的数据
* 说	明：扩展的用户数据区读取函数, 支持读取新版本2K大小数据区
*
**********************************************************/
function IA300_SecureReadStorageEx(nStartAddr, nDataLen) {
    return IA300_GetInstance().IA300SecureReadStorageEx(nStartAddr, nDataLen);
}

/*******************************************************
*
* 函数名称：IA300_DataEncrypt()
* 功    能：客户端3des加密
* 返 回 值：返回加密后的数据为成功，空为失败
*
**********************************************************/
function IA300_DataEncrypt(pBuffer)
{
    return IA300_GetInstance().IA300DataEncrypt(pBuffer);
}

/*******************************************************
*
* 函数名称：IA300_RemoteChangeRequest()
* 功    能：申请远程注册
* 输    入：strRandom：随机数 
* 返 回 值：返回USB Key相关参数，此参数作为服务端生成Response的重要参数
* 说	明：
*
**********************************************************/
function IA300_RemoteChangeRequest(strRandom)
{
    var rtn = IA300_GetInstance().IA300CheckExist();
    if(rtn == 0)
    {
        alert("请插入USB Key进行注册申请！");
        return;
    }
    return IA300_GetInstance().IA300RemoteChangeRequest(strRandom);
}
/*******************************************************
*
* 函数名称：IA300_RemoteChange()
* 功    能：远程注册
* 输    入：strResponse：服务端返回的注册参数；strRandom：服务端传递过来的随机数，是申请的时候参与请求的随机数
* 返 回 值：返回0，表示注册成功，返回1，表示注册失败。
* 说	明：
*
**********************************************************/
function IA300_RemoteChange(strResponse,strRandom)
{
    var rtn = IA300_GetInstance().IA300CheckExist();
    if(rtn == 0)
    {
        alert("请插入USB Key完成注册！");
        return;
    }
    return IA300_GetInstance().IA300RemoteChange(strResponse,strRandom);
}

/*******************************************************
*
* 函数名称：IA300GetName()
* 功    能：获取USB Key中设置的USB Key别名
* 返 回 值：返回Key中设置的USB Key别名
*
**********************************************************/
function IA300_GetName()
{
    return  IA300_GetInstance().Name;
}
/*******************************************************
*
* 函数名称：IA300GetDescription()
* 功    能：获取USB Key中设置的公司名称信息
* 返 回 值：返回Key中设置的公司名称
*
**********************************************************/
function IA300_GetDescription()
{
    return IA300_GetInstance().Description;
}
/*******************************************************
*
* 函数名称：IA300GetUrl()
* 功    能：获取USB Key中设置的网址
* 返 回 值：返回Key中设置的网址信息
* 说    明：此网址为插Key自动弹出网页的地址
*
**********************************************************/
function IA300_GetUrl()
{
    return IA300_GetInstance().Url;
}
/*******************************************************
*
* 函数名称：IA300GetOther()
* 功    能：获取USB Key中设置的其他信息，例如联系电话等
* 返 回 值：返回Key中设置的其他信息
*
**********************************************************/
function IA300_GetOther()
{
    return IA300_GetInstance().Other;
}
/*******************************************************
*
* 函数名称：createElementIA300()
* 功    能：自动判断操作系统是X64或X32并自动添加相应的插件
* 说	明：自动判断操作系统是X64或X32并自动添加相应的插件_CLSID为IA300ClinetID
*
**********************************************************/
//已完成判断系统,自动添加插件;
//未完成相应系统不同插件的添加,现皆为x32插件
function createElementIA300()
{
    var ia300client;
    var browser = DetectBrowser();
    if(browser == "IE") {
		
        if (IsIE9Above() == true) {
            ia300client = document.createElement('object');
            if (ia300client != null) {
                    ia300client.setAttribute("id", "IA300Client");
                    ia300client.setAttribute("CLASSID", "clsid:6AAEEBD3-838D-4A35-9571-26EFC79882ED");
                    ia300client.setAttribute("width", "0");
                    ia300client.setAttribute("height", "0");
            }
        }
        else  //IE6,7
        {
            ia300client = document.createElement("<object id=\"IA300Client\" CLASSID=\"clsid:6AAEEBD3-838D-4A35-9571-26EFC79882ED\" style=\"left:0px; top:0px; width:0; height:0; \" ></object>");
        }
        document.body.appendChild(ia300client);
    }
    else {

        ia300client = document.createElement('embed');
        if (ia300client != null) {
            ia300client.setAttribute("id", "IA300Client");
            ia300client.setAttribute("width", "0");
            ia300client.setAttribute("height", "0");
            ia300client.setAttribute("type", "application/IA300Plugin");

            document.body.appendChild(ia300client);
        }
    }
  
}
/*******************************************************
*
* 函数名称：DetectIA300Plugin()()
* 功    能：自动判断是否注册客户端插件
* 说	明：IA300ACTIVEX.IA300ActiveXCtrl.1为IA300Clinet插件注册后的NAME
*
**********************************************************/
 
function DetectIA300Plugin() {

    var browser = DetectBrowser();
    if(browser == "IE")
    {
        try
        {   
           var comActiveX = new ActiveXObject("IA300ACTIVEX.IA300ActiveXCtrl.1");   
        }
        catch(e)
        {
           return false;   
        }
        return true;
    }
    else
    {
        var ia300Plugin = navigator.plugins["IA300 Plugin"];
        if (ia300Plugin != null) 
        {
            return true;
        }
        return false;
    }
      
}

/*******************************************************
*
* 函数名称：DetectBrowser()()
* 功    能：自动判断当前使用浏览器
* 说	明：自动判断浏览器引擎，返回结果为当前浏览器名称
*
**********************************************************/
function DetectBrowser()
 {
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    var s;  
    (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
    (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
    (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
    (s = ua.match(/rv:([\d.]+)/)) ? Sys.ie = s[1] :
    (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1]:
    (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;

	var browser="Unknown";
    if (Sys.ie){browser="IE";}
    if (Sys.firefox) {browser="Firefox";}
    if (Sys.chrome) {browser="Chrome";}
    if (Sys.opera) {browser="Opera";}
    if (Sys.safari) { browser = "Safari"; }
    
    return browser;
}

function IsIE9Above() {
    if (navigator.userAgent.indexOf("Gecko") >= 0) {
        return true;
    }
    else {
        var ua = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/)[1];
        if (parseInt(ua, 10) >= 9) 
            return true;
        else 
            return false;
    }
}