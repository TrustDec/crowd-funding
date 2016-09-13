$(document).on("pageInit","#account-add_leader_info", function(e, pageId, $page) {
	get_file_fun(1);
	leader_info_save();

	function leader_info_save(){
		$(".button_leader_submit").on("click",function(){
			if($("#leader_help").val()==''){
				$.showErr("其它帮助不能为空！");
				return false;
			}
			if($("#leader_for_team").val()==''){
				$.showErr("团队评价不能为空！");
				return false;
			}
			if($("#leader_for_project").val()==''){
				$.showErr("项目评价不能为空！");
				return false;
			}				
			var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=leader_info_save';
			var id=$("#leader_info_id").val();
			var leader_help=$("#leader_help").val();
			var leader_for_team=$("#leader_for_team").val();
			var leader_for_project=$("#leader_for_project").val();
			var leader_moban=$("#attach_1_url").val();
			var query = new Object();
			query.id=id;
			query.leader_help=leader_help;
			query.leader_for_team=leader_for_team;
			query.leader_for_project=leader_for_project;
			query.leader_moban=leader_moban;
			$.ajax({
				url: ajaxurl,
				data:query,
				type: "POST",
				dataType: "json",
				success:function(data){
					if(data.status==0){
						$.showErr(data.info);
						return false;
					}
					if(data.status==2){
						$.showErr(data.info);
						return false;
					}
					if(data.status==1){
						$.showSuccess(data.info,function(){
							$.router.loadPage(window.location.href);
						});
					}
				}
			});
		});		
		return false;
	}
});