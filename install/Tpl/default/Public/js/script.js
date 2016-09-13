function startInstall()
{
	var DB_HOST = document.install.DB_HOST.value;
	var DB_NAME = document.install.DB_NAME.value;
	var DB_USER = document.install.DB_USER.value;
	var DB_PWD = document.install.DB_PWD.value;
	var DB_PORT = document.install.DB_PORT.value;
	var DB_PREFIX = document.install.DB_PREFIX.value;
	var DEMO_DATA = document.install.DEMO_DATA.value;
	
	//开始验证
	if(DB_HOST=="")
	{
		alert("请填写数据库主机名或IP地址");
		document.install.DB_HOST.focus();
		return;
	}
	if(DB_NAME=="")
	{
		alert("请填写数据库名");
		document.install.DB_NAME.focus();
		return;
	}
	if(DB_USER=="")
	{
		alert("请填写数据库用户名");
		document.install.DB_USER.focus();
		return;
	}
	$("#ajax_loading").ajaxStart(function(){
		$("#tip").html("正在安装......请稍候");
		$(this).fadeIn();
		$("#install").find("*").attr("disabled",true);
	 }); 
	 $("#ajax_loading").ajaxStop(function(){
			$("#ajax_loading").fadeOut();
			$("#install").find("input").attr("disabled",false);
	 }); 
	 
	 var query = new Object();
	 query.DB_HOST = DB_HOST;
	 query.DB_NAME = DB_NAME;
	 query.DB_USER = DB_USER;
	 query.DB_PWD = DB_PWD;
	 query.DB_PORT = DB_PORT;
	 query.DB_PREFIX = DB_PREFIX;
	 query.DEMO_DATA = DEMO_DATA;
	 
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=install",
		  cache: false,
		  data:query,
		  type: "POST",
		  dataType: "json",
		  success:function(data)
		  {
				//data = $.evalJSON(data); 

				if(data.status)
				{
					$("#tip").html("安装完成 ！正在登录管理后台");
					location.href = ROOT_PATH+"/m.php";
				}
				else
				{
					$("#ajax_loading").hide();
					alert(data.info);
				}
		  }
		}); 	
}
