// 获取会员所有项目列表
function ajax_get_recommend_project(obj){
	if($(obj).attr("rel") == user_info_id){
		$.showErr("不能给自己推荐！");
		return false;
	}
	var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=ajax_get_recommend_project';
	var query=new Object();
	//推荐人id
	query.id = user_info_id;
	//被推荐人id
	query.user_id=$(obj).attr("rel");
	$.ajax({
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success:function(ajaxobj){
			if(ajaxobj.status==0){
				show_login();
				return false;
			}
			if(ajaxobj.status==1){
				$.showErr(ajaxobj.info);
				return false;
			}
			if(ajaxobj.status==2){
	    		$.modal({
					title: '自荐我的项目',
			      	text: ajaxobj.html,
			      	buttons: []
				});
				page_style();
				ajax_recommend_save();
				return false;
			}
		}
	});
}
function page_style(){
	//筛选自荐项目
	$(".J_check").on('click',function(){
		var rel=$(this).attr("rel");
		$(".J_check").removeClass("ui_checked");
		$(".J_check").find("input[name='project_recommend']").removeAttr("checked");
		$(".J_check").find(".inf").removeClass("theme_fcolor");
		$(this).addClass("ui_checked");
		$(this).find("input[name='project_recommend']").attr("checked","checked");
		$(this).find(".inf").addClass("theme_fcolor");
	});

	if($(".project_list").find("li").length <= 4){
		$(".project_list").css("height","auto");
	}
	$(".button_n").click(function(){
		$.closeModal();
	});
}

function ajax_recommend_save(){
	$(".button_y").bind("click",function(){
		if($("input[name='project_recommend']:checked").length==0){
			$.toast("请选择推荐项目");
			return false;
		}
		if($("#memo").val()==''){
			$.toast("推荐理由不能为空！");
			return false;
		}
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=ajax_recommend_save";
		var deal_image=$("input[name='project_recommend']:checked").attr("rel3");
		var deal_name=$("input[name='project_recommend']:checked").attr("rel2");
		var deal_type=$("input[name='project_recommend']:checked").attr("rel");
		var deal_id=$("input[name='project_recommend']:checked").val();
		var memo=$("textarea[name='memo']").val();
		var recommend_user_id=$("#recommend_user_id").val();
		var user_id=$("#user_id").val();
		var query=new Object();
		query.deal_id=deal_id;
		query.memo=memo;
		query.recommend_user_id=recommend_user_id;
		query.user_id=user_id;
		query.deal_type=deal_type;
		query.deal_name=deal_name;
		query.deal_image=deal_image;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==0){
					$.toast(ajaxobj.info);
					return false;
				}
				if(ajaxobj.status==1){
					$.closeModal();
					$.toast(ajaxobj.info);
				}
			}
		});
	});
	return false;
}