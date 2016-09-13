$(document).ready(function(){
    $("#add_relate_reply").bind("click",function(){
        if($("input[name='relate_reply_id[]']").length<9)
        {
            $.weeboxs.open(LOAD_REPLY_LIST_URL, {boxid:'relate_reply_win',contentType:'ajax',showButton:true, showCancel:true, showOk:true,title:'选择要关联的图文回复',width:550,height:310,type:'',onopen:onOpenRelate,onok:onConfirmRelate,onclose:onCancelRelate});
        }
        else
        {
            $.showErr("关联的回复不能超过9条");
        }

    });

    $(".remove_relate").live("click",function(){
        $(this).parent().parent().remove();
        if($("input[name='relate_reply_id[]']").length==0)
        {
            $("#relate_table").remove();
            $("#relate_table_div").hide();
        }
    });

    $("#ajax_news_form").live("submit",function(){

        //改用ajax提交表单
        var ajaxurl = $(this).attr("action");
        var query = $(this).serialize();


        $.ajax({
            url: ajaxurl,
            data:query,
            type: "POST",
            success: function(html){
                $("#relate_reply_win").find(".dialog-content").html(html);

            },
            error:function(ajaxobj)
            {

            }
        });
        //end

        return false;
    });

    $("#ajax_news_page").find("a").live("click",function(){

        //改用ajax提交表单
        var ajaxurl = $(this).attr("href");
         var query =  $("#ajax_news_form").serialize();
        $.ajax({
            url: ajaxurl,
            data:query,
            type: "POST",
            success: function(html){
                $("#relate_reply_win").find(".dialog-content").html(html);

            },
            error:function(ajaxobj)
            {

            }
        });
        //end

        return false;
    });
});

function onConfirmRelate()
{
    var rowsCbo = $("input[rel='relate_reply_id']:checked");

    if(rowsCbo.length>0)
    {
        var relate_table = $("#relate_table");
        if(relate_table.length==0)
        {
            var relate_table = $("<table class='dataTable' id='relate_table'><tr><th>操作</th><th>回复内容</th></tr></table>");
            $("#relate_reply_box").append(relate_table);
            $("#relate_table_div").show();
        }

        $.each(rowsCbo,function(i,o){
            //alert($(o).val());
            if($("#relate_reply_id_"+$(o).val()).length==0)
            {
                    if($("input[name='relate_reply_id[]']").length>=9)
                    {
                        close_pop();
                        return;
                    }
                    var row = $("<tr><td><a href='javascript:void(0);' class='remove_relate'>删除</a></td><td><input type='hidden' id='relate_reply_id_"+$(o).val()+"' name='relate_reply_id[]' value='"+$(o).val()+"' />"+$(o).parent().parent().find(".reply_content").html()+"</td></tr>");
                    $(relate_table).append(row);

            }
        });
    }
    close_pop();
}
function onCancelRelate()
{

}
function onOpenRelate()
{
    $(document).on('click','#relate_reply_win .pagination a',function(){
        var url = $(this).data('url');
        if(url){
            $.get(url,function(result){
                $('#relate_reply_win .dialog-content').html(result);
            });
        }
    });
}
