$(document).on("pageInit","#licai-uc_expire_status", function(e, pageId, $page) {
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=set_status';
        var id =  $.trim($("#id").val());
        var earn_money =  $.trim($("#earn_money").val());
		var fee =  $.trim($("#fee").val());
        var query = new Object();
        query.id = $.trim($("#id").val());
        query.earn_money = $.trim($("#earn_money").val());
        query.fee = $.trim($("#fee").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(data){
                if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_expire_lc';
                    $.router.loadPage(href);
                }
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
            }
        
        });
        $(this).parents(".float_block").hide();
    });
});