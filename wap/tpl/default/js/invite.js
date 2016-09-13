$(document).on("pageInit","#invite-index", function(e, pageId, $page) {
	$(".J_invite_accept").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		bind_invite_accept(invite_item_id);
	});
	$(".J_invite_refuse").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		bind_invite_refuse(invite_item_id);
	});

	// 接受邀请
	function bind_invite_accept(id){
		var ajaxurl = APP_ROOT+'/index.php?ctl=invite&act=set_invite_accept';
		var obj=new Object();
		obj.ajax=1;
		obj.id=id;
		$.confirm("确定接受邀请？",function(){
			$.ajax({
				url:ajaxurl,
				type:"POST",
				data:obj,
				dataType:"json",
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});
					}else{
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
	// 拒绝邀请
	function bind_invite_refuse(id){
		var ajaxurl = APP_ROOT+'/index.php?ctl=invite&act=set_invite_refuse';
		var obj=new Object();
		obj.ajax=1;
		obj.id=id;
		$.confirm("确定拒绝邀请？",function(){
			$.ajax({
				url:ajaxurl,
				type:"POST",
				data:obj,
				dataType:"json",
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});
					}else{
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});