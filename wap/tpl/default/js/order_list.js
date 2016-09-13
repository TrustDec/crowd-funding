$(document).on("pageInit","#account-index", function(e, pageId, $page) {
    $(".del_deal").on("click",function(){
        var ajaxurl = $(this).attr("href");
        $.confirm("确定删除该记录吗？",function(){
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
                        if(ajaxobj.info!="")
                        {
                            $.showSuccess(ajaxobj.info,function(){
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
});