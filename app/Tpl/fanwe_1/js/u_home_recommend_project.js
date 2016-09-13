function ajax_get_recommend_project(o,u_id,ajaxurl){
	if($(o).attr("rel")==u_id){
		$.showErr("不能给自己推荐！");
		return false;
	}
	
	var query=new Object();
	query.id=u_id; // 推荐人id
	query.user_id=$(o).attr("rel"); // 被推荐人id
	$.ajax({
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success:function(ajaxobj){
			if(ajaxobj.status==0){
				show_pop_login();
				return false;
			}
			if(ajaxobj.status==1){
				$.showErr(ajaxobj.info);
				return false;
			}
			if(ajaxobj.status==2){
				$.weeboxs.open(ajaxobj.html, {boxid:'project_recommend_page',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'我的项目',width:480,type:'wee'});
				return false;
			}
		}
	});
}

// 投资案例伸缩
function trigger_detail(obj){
	var $obj = $(obj);
	var c_val = $obj.attr("rel");
	if($("."+c_val+"_autoheight_wrap").hasClass("autoheight_wrap")){
		$("."+c_val+"_autoheight_wrap").removeClass("autoheight_wrap");
		$obj.html("展开");
	}
	else{
		$("."+c_val+"_autoheight_wrap").addClass("autoheight_wrap");
		$obj.html("收起");
	}
}