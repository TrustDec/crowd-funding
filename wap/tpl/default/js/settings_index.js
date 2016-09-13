$(document).on("pageInit","#settings-index", function(e, pageId, $page) {
 	(function(){
        if(is_tg){
            checkIpsBalance(0,user_info_id,function(result){
                var $u_money_other=$("#u_money_other");
                if(result.pErrCode=="1"){
                    $u_money_other.css("display","flex");
                    $u_money_other.find("#u_money_other_money").html(formatNum(result.pBalance-result.pLock));
                    $u_money_other.find("#u_money_other_freeze").html(formatNum(result.pLock));
                }
            });
        }
    })();
    bind_user_loginout();
});