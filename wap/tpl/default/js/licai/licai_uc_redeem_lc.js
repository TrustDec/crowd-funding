$(document).on("pageInit","#licai-uc_redeem_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
	  toolbarTemplate: '<header class="bar bar-nav">\
	  <button class="button button-link pull-right close-picker">确定</button>\
	  <h1 class="title">选择日期</h1>\
	  </header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc_statu';
        var deal_name = $.trim($("#deal_name").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var user_name = $.trim($("#user_name").val());
        
        var query = new Object();
        query.deal_name = $.trim($("#deal_name").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.user_name = $.trim($("#user_name").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
               if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc&begin_time='+b_time+'&end_time='+e_time+'&user_name='+user_name+'&deal_name='+deal_name;
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