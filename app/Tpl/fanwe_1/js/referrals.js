//定义复制文本
$.copyText = function(id)
{
	var txt = $(id).val();
	if(window.clipboardData)
	{
		window.clipboardData.clearData();
		var judge = window.clipboardData.setData("Text", txt);
		if(judge === true)
			$.showSuccess("已经拷贝到剪切板");
		else
			$.showErr("拷贝失败");
	}
	else if(navigator.userAgent.indexOf("Opera") != -1)
	{
		window.location = txt;
	} 
	else if (window.netscape) 
	{
		try
		{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}
		catch(e)
		{
			$.showErr("非IE内核，无法拷贝");
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
			return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans)
			return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;
		str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
		$.showSuccess("已经拷贝到剪切板");
	}
};