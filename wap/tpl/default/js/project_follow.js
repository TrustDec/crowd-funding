$(document).on("pageInit","#deal-project_follow", function(e, pageId, $page) {
	leader_detailed_information();
	//领投人详细资料
	function leader_detailed_information(){
		$("#detailed_information").bind("click",function(){
			var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=leader_detailed_information&id='+leader_info_id;
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1){
						// $.weeboxs.open(ajaxobj.html, {boxid:'leader_detailed_info',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'详细信息',width:300,type:'wee'});
						$.alert(ajaxobj.html);
					}
				    if(ajaxobj.status==2){
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});