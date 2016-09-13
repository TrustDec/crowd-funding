function enquiry_money_first(obj,login_id,deal_info_id){
	var $btn_box = $(obj);
	$btn_box.find(".btn_enquiry_money").bind("click",function(){
		if(login_id==''){
			show_pop_login();
			return false;
		}
		var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=ajax_continue_investor&deal_id="+deal_info_id;
		var query = $btn_box.serialize();
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
					// 投资成功！
					$.showSuccess(ajaxobj.info,function(){
						location.reload();
					});
				}
				if(ajaxobj.status==0){
					$.showErr(ajaxobj.info,function(){
						if(ajaxobj.url){
							location.href=ajaxobj.url;
						}
					});
					
				}
				if(ajaxobj.status==2){
					//调取第一次跟投页面
					$("body").find(".dialog-mask").first().remove();
					$("body").find(".weebox").first().remove();
					$.weeboxs.open(ajaxobj.html, {boxid:'enquiry_one',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'项目投资',width:480,type:'wee'});
				}
				if(ajaxobj.status==4){
					//调取后续追加跟投页面
					$("body").find(".dialog-mask").first().remove();
					$("body").find(".weebox").first().remove();
					$.weeboxs.open(ajaxobj.html, {boxid:'enquiry_two',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'项目追加投资',width:480,type:'wee'});
				}
				
				if(ajaxobj.status==5){
					//无法再次跟投追加金额
					$.showErr(ajaxobj.info,function(){
						location.reload();
					});
				}
				if(ajaxobj.status==8){
					//您已为领投人,无需再进行跟投！
					$.showSuccess(ajaxobj.info,function(){
						
						//location.reload();
					});
				}
				if(ajaxobj.status==7){
					//已经申请“领投”，但是未审核
					$.showConfirm("您确定要取消,领投申请吗？",function(){
						delete_leader_investor();
					});
				}
			}
			
		});
		return false;
	});
	$btn_box.find(".button_n").bind("click",function(){
		$.weeboxs.close();
	});
}