$(document).ready(function(){
    init_add_btn();
    $("#syn_weixin").bind("click",function(){
        var ajaxurl = $(this).attr("rel");       
                                    
        $.ajax({ 
            url: ajaxurl,
            type: "POST",
            dataType: "json",
            success: function(ajaxobj){
              if(ajaxobj.status==1)
              {
                  $.showSuccess(ajaxobj.info,function(){
                      location.href = location.href;
                  });
              }
              else
              {
                  $.showErr(ajaxobj.info,function(){
                      if(ajaxobj.jump!=''&&ajaxobj.jump!=undefined)location.href = ajaxobj.jump;
                  });
              }
            },
            error:function(ajaxobj)
            {
                
            }
        }); 
    });
    $(".del_nav").live("click",function(){
        var id = $(this).parent().parent().find("input[name='id[]']").val();
        if($(".sub_"+id).length>0)
        {
            $.showErr("请选删除子菜单");
        }
        else
        $(this).parent().parent().remove();
    });
    $(".add_sub_nav").live("click",function(){
        var pid = $(this).attr("pid");
        var tr = $(this).parent().parent();
        if($(".sub_"+pid).length>=5)
        {
            $.showErr("子菜单数量超过不能超过五个");
        }
        else
        {           
                    var ajaxurl = $(this).attr("rel");    
                                                        
                    $.ajax({ 
                        url: ajaxurl,
                        type: "POST",
                        success: function(html){
                          $(tr).after(html);
                        },
                        error:function(ajaxobj)
                        {
                   
                        }
                    }); 
 
        }
    });
});

function init_add_btn()
{
    $("#add_weixin_main_nav").bind("click",function(){
        
            if($("#listTable").find("tr.main").length>=3)
            {
                $.showErr("主菜单数量超过不能超过三个");
            }
            else
            {
                  //改用ajax提交表单
                    var ajaxurl = $(this).attr("rel");       
                                    
                    $.ajax({ 
                        url: ajaxurl,
                        type: "POST",
                        success: function(html){
                           $("#listTable").append(html);
                        },
                        error:function(ajaxobj)
                        {
                   
                        }
                    }); 
                    //end
            }
           
        
    });
}
