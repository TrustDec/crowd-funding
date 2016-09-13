$(document).on("pageInit","#licai-uc_record_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
		  toolbarTemplate: '<header class="bar bar-nav">\
		  <button class="button button-link pull-right close-picker">确定</button>\
		  <h1 class="title">选择日期</h1>\
		  </header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=uc_record_lc_status';
    	var id = $.trim($("#id").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var b_b_time = $.trim($("#buy_begin_time").val());
        var b_e_time = $.trim($("#buy_end_time").val());
        
        var query = new Object();
    	query.id = $.trim($("#id").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.b_b_time = $.trim($("#buy_begin_time").val());
        query.b_e_time = $.trim($("#buy_end_time").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
                if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_record_lc&begin_time='+b_time+'&end_time='+e_time+'&buy_begin_time='+b_b_time+'&buy_end_time='+b_e_time+'&id='+id;
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
        return false; 
        // $(this).parents(".float_block").hide();
    });
});