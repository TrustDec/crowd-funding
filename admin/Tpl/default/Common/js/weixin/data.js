var bind_ajax_form_lock = false;
jQuery(function(){
	bind_ajax_form();
	$("#listTable .check_all").click(function(){
		if($(this).attr("checked")){
			$("#listTable tbody input[name='check']").attr("checked",true);
			$(".datatabletool").show();
		}
		else{
			$("#listTable tbody input[name='check']").attr("checked",false);
			$(".datatabletool").hide();
		}
	});

	$("#listTable tbody input[name='check']").click(function(){
		if($("#listTable tbody input[name='check']:checked").length > 0){
			$(".datatabletool").show();
		}
		else{
			$(".datatabletool").hide();
		}
	});

	$(".datatabletool .btn").click(function(){
		var obj = $(this);
		if (obj.attr("attr") == "BatchDel") {
			$.showCfm("确定要删除选定的数据吗", function(){
				if ($("#listTable tbody input[name='check']:checked").length == 0) {
					$.weeboxs.close();
					return false;
				}

				var query = new Object();
				query.ids = "0";
				$("#listTable tbody input[name='check']:checked").each(function(){
					query.ids += ","+$(this).val();
				});

				$.ajax({
					url:obj.attr("url"),
					data:query,
					type:"post",
					dataType:"json",
					success:function(result){
						if(result.status==1){
							location.href = location.href;
						}
						else{
						    $.weeboxs.close();
							$.showErr(result.msg);
						}
					},
					error:function(){
						location.href = location.href;
					}

				});
			}, function(){

			});
		}else if(obj.attr("attr") == "BatchLock"){
			$.showCfm("确定要冻结选定的会员吗", function(){
				if($("#listTable tbody input[name='check']:checked").length == 0)
					return false;

				var query = new Object();
				query.ids = "0";
				$("#listTable tbody input[name='check']:checked").each(function(){
					query.ids += ","+$(this).val();
				});
				query.status=0;

				$.ajax({
					url:obj.attr("url"),
					data:query,
					type:"post",
					dataType:"json",
					success:function(result){
						if(result.status==1){
							location.reload();
						}
						else{
						    $.weeboxs.close();
							$.showErr(result.msg);
						}
					},
					error:function(){
						location.reload();
					}

				});
			}, function(){

			});
		}else if(obj.attr("attr") == "BatchUNLock"){
			$.showCfm("确定要解冻选定的会员吗", function(){
				if($("#listTable tbody input[name='check']:checked").length == 0)
					return false;

				var query = new Object();
				query.ids = "0";
				$("#listTable tbody input[name='check']:checked").each(function(){
					query.ids += ","+$(this).val();
				});
				query.status=1;

				$.ajax({
					url:obj.attr("url"),
					data:query,
					type:"post",
					dataType:"json",
					success:function(result){
						if(result.status==1){
							location.reload();
						}
						else{
						    $.weeboxs.close();
							$.showErr(result.msg);
						}
					},
					error:function(){
						location.reload();
					}

				});
			}, function(){

			});
		}else if(obj.attr("attr") == "Batchyinc"){
			$.showCfm("确定要隐藏吗", function(){
				if($("#listTable tbody input[name='check']:checked").length == 0)
					return false;

				var query = new Object();
				query.ids = "0";
				$("#listTable tbody input[name='check']:checked").each(function(){
					query.ids += ","+$(this).val();
				});
				query.status=1;

				$.ajax({
					url:obj.attr("url"),
					data:query,
					type:"post",
					dataType:"json",
					success:function(result){
						if(result.status==1){
							location.reload();
						}
						else{
						    $.weeboxs.close();
							$.showErr(result.msg);
						}
					},
					error:function(){
						location.reload();
					}

				});
			}, function(){

			});
		}else if (obj.attr("attr") == "BatchDone") {
			$.showCfm("确定要处理？", function(){
				if($("#listTable tbody input[name='check']:checked").length == 0)
					return false;

				var query = new Object();
				query.ids = "0";
				$("#listTable tbody input[name='check']:checked").each(function(){
					query.ids += ","+$(this).val();
				});

				$.ajax({
					url:obj.attr("url"),
					data:query,
					type:"post",
					dataType:"json",
					success:function(result){
						if(result.status==1){
							location.href = location.href;
						}
						else{
						    $.weeboxs.close();
							$.showErr(result.msg);
						}
					},
					error:function(){
						location.href = location.href;
					}

				});
			}, function(){

			});
		}
	});

	$(".dropdown-toggle").click(function(){
		if($(this).parent().hasClass("open")){
			$(this).parent().removeClass("open");
		}
		else{
			$(".dropdown-toggle").parent().removeClass("open");
			$(this).parent().addClass("open");
			$("body").bind("click",function(){
				$(".dropdown-toggle").parent().removeClass("open");
			});
			return false;
		}
	});
});

function bind_ajax_form()
{
	 
     $(".ajax_form").find(".ipt_require").live("click",function(){
         $(this).removeClass("input-need");
     });
     $(".ajax_form").find(".ipt_require").live("blur",function(){
         if($.trim($(this).val())=="")
         $(this).addClass("input-need");
     });

    $(".ajax_form").live("submit",function(){
        if(bind_ajax_form_lock)
		{
			return false;
		}
		
         var empty = 0;
		var tel_num=0;
		var req_length  = 0;
        $.each(  $(this).find(".ipt_require"), function(i, obj){
          if($.trim($(obj).val())=="")
          {
              $(obj).addClass("input-need");
              empty++;
          }
        });
		$.each(  $(this).find(".tel_require"), function(i, obj){
          if(!$.checkMobilePhone($(obj).val())||($.getStringLength($(obj).val())!=11))
          {
              $(obj).addClass("input-need");
              tel_num++;
          }
        });
		$.each(  $(this).find(".length_require"), function(i, obj){
          if($.getStringLength($(obj).val()) >= parseInt($(obj).attr("maxlen")))
          {
              $(obj).addClass("input-need");
              req_length++;
          }
        });
		if(tel_num > 0){
			$.showErr("请输入正确的手机号");
			return false;
		}
		if(empty > 0){
			$.showErr("请检查必填项是否为空");
			return false;
		}

		if(req_length > 0){
			$.showErr("请检查输入框是否超出限制的长度");
			return false;
		}

        if(empty==0 && req_length==0)
        {
			bind_ajax_form_lock = true;
            var ajaxurl = $(this).attr("action");
            var query = $(this).serialize();
            var form = $(this);

            $.ajax({
                url: ajaxurl,
                dataType: "json",
                data:query,
                type: "POST",
                success: function(ajaxobj){
                    if(ajaxobj.status==1)
                    {
						 
                        $.showSuccess(ajaxobj.info,function(){
							
							if(ajaxobj.jump!=""&&ajaxobj.jump!=undefined){
								 location.href = ajaxobj.jump;
							}
 							else{
								
								location.reload(true);
							}
                            	
                        });
                    }
                    else
                    {
                        if(ajaxobj.info!="")
                        {
                            $.showErr(ajaxobj.info,function(){
                                if(ajaxobj.field!="")
                                	$(form).find("input[name='"+ajaxobj.field+"']").addClass("input-need");
                                  if(ajaxobj.jump!=""&&ajaxobj.jump!=undefined)
								 {
								 	location.href = ajaxobj.jump;
								 }else{
								 	location.reload(true);
								 }
                              });
                        }
                        else
                        {
                             if(ajaxobj.jump!=""&&ajaxobj.jump!=undefined)
							 {
							 	location.href = ajaxobj.jump;
							 }else{
							 	location.reload(true);
							 }
                        }
                    }
                    bind_ajax_form_lock = false;
                },
                error:function(ajaxobj)
                {
                    bind_ajax_form_lock = false;
                }
            });
        }
        return false;
    });
}

function do_confirm(info,url){
	$.showCfm(info, function(){
		$.ajax({
			url:url,
			data:"ajax=1",
			dataType:"json",
			success:function(result){
				if(result.status==1){
					$.weeboxs.close();
					$.showSuccess(result.info,function(){
						if(result.jump!=""&&result.jump!=undefined)
							 location.href = result.jump;
						else
                        	location.reload();
					});
				}
				else{
					$.weeboxs.close();
					$.showErr(result.info);
				}
			}
		});
	}, function(){

	});
}

function close_pop()
{
	$(".dialog-close").click();
}
