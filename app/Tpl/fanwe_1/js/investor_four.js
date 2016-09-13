$(function(){
    show_tip();
    total_price(".history_table");
    bind_project_form();

    $(".history_table").live('blur',function(){
        total_price(".history_table");
    });

    // 添加下一个阶段
    $("#add_new_history").bind("click",function(){
        var num=parseInt($("input[name='history_step']").val())+1;
        $("input[name='history_step']").val(num);
        $.ajax({
            'url':APP_ROOT+"/index.php?ctl=project&act=add_investor_item&num="+num+"&html=add_new_history",
            'type':'POST',
            'dataType':'json',
            success:function(data){
                if(data.status==1){
                    $("#add_new_history_tr").before(data.html);
                    bindKindeditor();
                }
            }
        });
    });

    // 删除阶段
    $(".history_del").live('click',function(){
        var num=$(".history_del").index($(this));
        $(".xm-content:eq("+num+")").remove();
        total_price(".history_table");
    });
});

//选择日期控件
$("input.jcDate_1").jcDate({
    IcoClass : "jcDateIco",
    Event : "click",
    Speed : 100,
    Left :-125,
    Top : 28,
    format : "-",
    Timeout : 100,
    Oldyearall : 17,  // 配置过去多少年
    Newyearall : 0  // 配置未来多少年
});
function bind_project_form(){	
	$("#agencyAdd_stepfour_form").bind("submit",function(){
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status)
					{
						$.showSuccess(ajaxobj.info,function(){
							 location.href = ajaxobj.jump;
						});
					}
					else
					{
						if(ajaxobj.jump!="")
							location.href = ajaxobj.jump;
						else
						$.showErr(ajaxobj.info);
					}						
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
		});
		return false;
	});
    $("#ui-button").live("click",function(){
        var is_errortime = $(".time_box").create_beginTime({begin_time:"input[rel='begin_time']" , end_time:"input[rel='end_time']"});
        if(!is_errortime){
            $("#agencyAdd_stepfour_form").submit();
        }
    });
}