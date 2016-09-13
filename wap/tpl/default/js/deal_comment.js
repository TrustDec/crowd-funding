$(document).on("pageInit","#deal-comment", function(e, pageId, $page) {
	var dsc_pd_h = $(".dsc_pd").height();
	var h = document.body.offsetHeight;
	var m_h = h - (44 + 50 + dsc_pd_h);
	$(".discussion").css({minHeight:m_h+"px"});
	$(".J_dsc_send").on('click',function(){
		event_send();
	});
	$(".J_replycomment").on('click',function(){
		focus_event(this,user_name);
	});
	function event_send(){
		var content=$("#content").val();
		var ajax=$("#ajax").val();
		var post_url=$("#post_url").val();
		var id=$("#deal_id").val();
		var pid=$("#comment_pid").val();
		
		var query=new Object();
		query.content=content;
		query.ajax=ajax;
		query.id=id;
		query.pid=pid;
		$.ajax({
			url:post_url,
			dataType:"json",
			data:query,
			type:"post",
			success:function(data){
				if(data.status==1){
					var href = APP_ROOT+'/index.php?ctl=deal&act=comment&is_back=2';
					$.router.loadPage(href);
				}
                else{   
					if(data.status==2){
						$.toast(data.info);
					}else{
						$.showErr(data.info,function(){
	                        href = APP_ROOT+'/index.php?ctl=user&act=login';
							$.router.loadPage(href);
	                    });
					}
                    return false;
                }
			},error:function(){
				$.alert("系统繁忙，稍后请重试");
			}
		});
		return false;
	}
	function focus_event(obj,username){
		var pid=$(obj).attr("rel");
		$("#comment_pid").val(pid);
		$("#content").val("回复 "+username+":");
		$("#content").focus();
	}
});