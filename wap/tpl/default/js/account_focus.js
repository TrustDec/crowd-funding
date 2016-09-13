$(document).on("pageInit","#account-focus", function(e, pageId, $page) { 
    // 取消关注项目
    $(".J_cancel_focus").on("click",function(){
        var focus_id = $(this).attr('rel');
        var ajaxurl = APP_ROOT+'/index.php?ctl=account&act=del_focus&id='+focus_id;
        var query = new Object();
        query.ajax = 1;
        $.confirm("确定要取消关注此项目吗？",function(){
            $.ajax({ 
                url: ajaxurl,
                dataType: "json",
                data:query,
                type: "POST",
                success: function(ajaxobj){
                    if(ajaxobj.status==1)
                    {                       
                        $.closeModal();
                        if(ajaxobj.info!=""){
                            $.alert(ajaxobj.info,function(){
                                $("#focus_item_"+focus_id).remove();
                            });
                        }
                    }
                    else
                    {
                        $.closeModal();
                        if(ajaxobj.info!="")
                        {
                            $.toast(ajaxobj.info,1000); 
                        }                         
                    }
                },
                error:function(ajaxobj)
                {
                    // if(ajaxobj.responseText!='')
                    // alert(ajaxobj.responseText);
                }
            });
        
        });
        return false;
    });
});