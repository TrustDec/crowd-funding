$(document).on("pageInit","#deal-update", function(e, pageId, $page) {
	// 无线滚动加载更多
	var loading = false;
	$($page).on('infinite', function() {
		bind_ajax_load();
	});
	function bind_ajax_load(){
        if (loading || now_page >= all_page){
 	 		$(".content-inner").css({paddingBottom:"0"});
 			return;
 	 	} 
 	 	$(".infinite-scroll-preloader").show();
      	loading = true;
      	var page_ajax_url = $("input[name='page_ajax_url']").val();
	  	var query = new Object();
	  	query.page  =  now_page + 1;
	  	query.ajax = 1;
      	$.ajax({
	      	url:page_ajax_url,
	      	dataType: "json",
	        data:query,
	        async:false,
	        success:function(data){
	        	setTimeout(function() {
	        		now_page ++;
	        		loading = false;
	        		 $("#pin_box").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
	}

	bind_comment_box_event();

	function bind_comment_box_event()
	{
		$(".timeline-box").find("textarea[name='content']").unbind("focus click blur");
		$(".swap_comment").unbind("click");
		$(".send_btn").unbind("click");
		$(".comment_form").unbind("submit");
		$(".delcomment").unbind("click");
		$(".replycomment").unbind("click");	
		$(".fodeup_comment").unbind("click");
		
		$(".fodeup_comment").bind("click",function(){
			$("#post_"+$(this).attr("rel")+"_comment").slideUp();		
		});
		
		$(".replycomment").bind("click",function(){
			if($(this).parent().parent().find(".reply_box").css("display")=="none")
				$(this).parent().parent().find(".reply_box").show();
			else
				$(this).parent().parent().find(".reply_box").hide();	
		});
		
		$(".delcomment").bind("click",function(){
			var ajaxurl = $(this).attr("href");
			var comment_box = $(this).parent().parent().parent();
			$.confirm("确认要删除该记录吗？",function(){
				
				var query = new Object();
				
				query.ajax = 1;
				$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						if(ajaxobj.status==1)
						{
							$(comment_box).remove();
							$("#comment_"+ajaxobj.logid+"_tip").html(ajaxobj.counthtml);
							close_pop();
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
			});
			return false;
		});
		
		$(".timeline-box").find("textarea[name='content']").bind("focus click",function(){
			if($.trim($(this).val())=="发表评论")
			{
				$(this).val("");
			}
			$(this).addClass("inputing");
			$(this).parent().parent().find(".comment-btn").show();
		});
		
		$(".timeline-box").find("textarea[name='content']").bind("blur",function(){
			if($.trim($(this).val())=="发表评论"||$.trim($(this).val())=="")
			{
				$(this).val("发表评论");
				$(this).removeClass("inputing");
				$(this).parent().parent().find(".comment-btn").hide();
			}
			
		});
		
		$(".swap_comment").bind("click",function(){
			var box_id_str = $(this).parent().parent().attr("id");
			var id_str = box_id_str.split("_");
			id_str = id_str[1];
			if($("#post_"+id_str+"_comment").css("display")=="none")
			{			
				$("#post_"+id_str+"_comment").slideDown(function(){
					if($(this).find(".deal_comment_list .comment_item").length==0)
					{
						$(this).find("textarea[name='content']").click();
					}
				});
			}
			else
			{
				
				$("#post_"+id_str+"_comment").slideUp(function(){
					$("#post_"+id_str+"_comment").find("textarea[name='content']").blur();
				});
			}		
		});
		
		$(".send_btn").bind("click",function(){	
			if($(this).find("div span").html()!="发送中")
			$(this).parent().parent().submit();		
		});
		
		$(".comment_form").bind("submit",function(){
			var btn = $(this).find(".send_btn");
			var form = $(this);
			if($.trim($(this).find("textarea[name='content']").val())==""||$.trim($(this).find("textarea[name='content']").val())=="发表评论")
			{
				$(this).find("textarea[name='content']").focus();
				return false;
			}
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize();
			var log_id = $(this).attr("rel");
			var comment_list_box = $("#deal_comment_list_"+log_id);
			var comment_pid = $(this).find("input[name='comment_pid']").val();			
			$(btn).find("div span").html("发送中");
			
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$(comment_list_box).parent().parent().find("textarea[name='content']").val("发表评论");
						$(comment_list_box).parent().parent().find("textarea[name='content']").blur();
						$(comment_list_box).parent().parent().parent().find(".swap_comment").html(ajaxobj.counthtml);
						comment_list_box.html(ajaxobj.html+comment_list_box.html());
						bind_comment_box_event();
						$("#reply_box_"+comment_pid).fadeOut();
						$(btn).find("div span").html("发送");
						
						// $(".comment_item").hover(function(){
						// 	$(this).find(".delcomment").show();
						// },function(){
						// 	$(this).find(".delcomment").hide();
						// });
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		}); //end comment_form_onsubmit
	}
});