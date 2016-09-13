$(document).on("pageInit","#score_good_show-check_order", function(e, pageId, $page) {
	$("select[name='province']").on("change",function(){
        load_city();
    });
    $("input[name='consignee_id']").on('click',function(){
		var consignee_id=parseInt($(this).val());
		if(consignee_id ==0)
		{
			$("#address_box").show();
		}
		else{
			$("#address_box").hide();
		}
	});
    $("#score_do_order").on("click",function(){
        var query = new Object();
        query.ajax=1;
        query.id=$("input[name='id']").val();
        query.number=$("input[name='number']").val();
        query.memo=$("textarea[name='memo']").val();
        if(is_delivery ==1)
        {
            if(have_consignee ==1)
                query.consignee_id=$("input[name='consignee_id']:checked").val();
            else
                query.consignee_id=0;   
            if(query.consignee_id == 0)
            {   
                query.delivery_name = $("input[name='delivery_name']").val();
                query.delivery_province = $("select[name='province']").val();
                query.delivery_city = $("select[name='city']").val();
                query.delivery_addr = $("textarea[name='delivery_addr']").val();
                query.delivery_zip = $("input[name='delivery_zip']").val();
                query.delivery_tel = $("input[name='delivery_tel']").val();
                
                if(query.delivery_name == ''){
                    $.showErr("请输入收货人名称");
                    return false;
                }
                if(query.delivery_province ==''){
                    $.showErr("请选择省份");
                    return false;
                }
                if(query.delivery_city ==''){
                    $.showErr("请选择城市");
                    return false;
                }
                if(query.delivery_addr == ''){
                    $.showErr("请输入详细地址");
                    return false;
                }
                if(query.delivery_tel == ''){
                    $.showErr("请输入手机号码");
                    return false;
                }
            }
            query.delivery_time=$("input[name='delivery_time']:checked").val(); 
        }
        
        query.paypassword=$("input[name='paypassword']").val();
        if(query.paypassword == ''){
            $.showErr("请输入付款密码");
            return false;
        }
        
        var ajax_url=APP_ROOT+"/index.php?ctl=score_good_show&act=do_score_order";
        $.ajax({
            url:ajax_url,
            data:query,
            dataType: "json",
            type: "post",
            success:function(o){
                if(o.status ==-1){
                    show_login();
               	}
                else if(o.status == 1){
                    if(o.jump){
                        $.showSuccess(o.info,function(){
                            $.router.loadPage(o.jump);
                        });
                    }
                    else{
                        $.showSuccess(o.info);
                    }
                }
                else{
                    if(o.jump){
                        $.showErr(o.info,function(){
                            $.router.loadPage(o.jump);
                        });
                    }
                    else{
                        $.showErr(o.info);
                    }
                }   
            }
        });
        
    });
});