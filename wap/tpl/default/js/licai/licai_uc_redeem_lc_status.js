$(document).on("pageInit","#licai-uc_redeem_lc_status", function(e, pageId, $page) {
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=set_redeem_lc_status';
        var redempte_id =  $.trim($("#redempte_id").val());
        var earn_money =  $.trim($("#earn_money").val());
        var fee =  $.trim($("#fee").val());
        var query = new Object();
        query.redempte_id = $.trim($("#redempte_id").val());
        query.earn_money = $.trim($("#earn_money").val());
        query.fee = $.trim($("#fee").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
                if(ajaxobj.status==1)
                {
                    var href = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc';
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
    });
});