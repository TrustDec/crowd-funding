$(document).on("pageInit","#investor-applicate_leader", function(e, pageId, $page) {
    $(".btn_info_view").on("click",function(){
        if($(".cate_name:checked").length > 3) {
            $.toast("最多只能选择3项");
            return false;
        }
    });
    $("input[name='submit_form']").on("click",function(){
        check_num();
    });
    
    bind_submit();


	// 获取字数长度
	function GetCharLength(str)
	{  
	    var iLength = 0;  
	    for(var i = 0; i<str.length; i++){  
	        if(str.charCodeAt(i) >255){  
	            iLength += 1;  
	        }  
	        else{  
	            iLength += 0.5;  
	        }  
	    }  
	    return iLength;  
	}   

	function check_num(){
	    var falg = 0; 
	        $(".cate_name").each(function() { 
	            if($(this).attr("checked") == 'checked') { 
	                falg += 1; 
	            } 
	        });
	}
	function bind_submit(){
	    $("#applicat_lead_qualificat_form").bind("submit",function(){
	        if($(".cate_name:checked").length==0){
	            $.showErr("请选择领投项目行业");
	            return false;
	        }
	        if($(".cate_name:checked").length>3){
	            $.showErr("领投项目行业最多不超过3项");
	            return false;
	        }
	        // if($("textarea[name='describe']").val().length<100){
	        //  $.showErr("个人简介，不少于100字!");
	        //  return false;
	        // }

	        // 字数不少于100字
	        var curStr=$("textarea[name='describe']").val();
	        var curLength=parseInt(GetCharLength(curStr));
	        if(curLength<100){
	            $.showErr("个人简介，不少于100字!");
	            return false;
	        }
	        
	        var ajaxurl=$(this).attr("action");
	        var query=$(this).serialize();  
	        query+="&description="+encodeURIComponent($("textarea[name='describe']").val());
	        $.ajax({
	            url: ajaxurl,
	            dataType: "json",
	            data:query,
	            type: "POST",
	            success:function(ajaxobj){
	                if(ajaxobj.status==1){
	                    $.showSuccess(ajaxobj.info,function(){
	                        href=ajaxobj.url;
							$.router.loadPage(href);
	                    });
	                }else{
	                    $.showErr("系统繁忙，请您稍后重试！");
	                    return false;
	                }
	            }
	        });
	        return false;
	    });
	    $("#ui-button").bind("click",function(){    
	        $("#applicat_lead_qualificat_form").submit();
	    });
	}
});