$(document).on("pageInit","#licai-uc_redeem", function(e, pageId, $page) {
	$("#redeem_money").bind("keyup",function(event){
        code = event.keyCode;
        if(parseFloat($("#redeem_money").val())>parseFloat($("#have_money").attr("title")))
        {
            $("#redeem_money").val($("#have_money").attr("title"));
            $.alert("赎回的金额不能大于持有本金");
        }
        money = $("#back_rate").html() * $("#redeem_money").val();
        if(isNaN(money))
        {
            money = 0;
        }
        fun_money();
    });
    
	//计算
    var before_rate = 0;
    var before_breach_rate = 0;
    var breach_rate = 0;
    var interest_rate = 0;

    function fun_money(){
        $money = $("#redeem_money");
        var money_val=$money.val();
        
        if(licai_type > 0){
            if(licai_interest_json[licai_interest_json.length - 1]['max_money'] < money_val){
                before_rate = licai_interest_json[licai_interest_json.length - 1]['before_rate'];
                before_breach_rate = licai_interest_json[licai_interest_json.length - 1]['before_breach_rate'];
                breach_rate = licai_interest_json[licai_interest_json.length - 1]['breach_rate'];
                interest_rate = licai_interest_json[licai_interest_json.length - 1]['interest_rate'];
            }
            else{
                $.each(licai_interest_json,function(i,v){
                    
                    if( parseFloat(v['min_money']) < parseFloat(money_val) && parseFloat(v['max_money']) > parseFloat(money_val)){
                        before_rate = v['before_rate'];
                        before_breach_rate = v['before_breach_rate'];
                        breach_rate = v['breach_rate'];
                        interest_rate = v['interest_rate'];
                    }
                });
            }
        }
        else{
            income_money_val = licai_interest_json;
        }
        if(money_val){
	        if(licai_type > 0){
	            if(licai_status == 0){
                 	//预热期违约收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_breach_rate / 365 / 100 * (before_days);
                  	//理财期收益
                  	arrival_earn = 0;
                  	$("#q_rate").html(before_breach_rate+"%");
	            }
	            else if(licai_status == 1){
                   	//预热期完成收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_rate / 365 / 100 * (before_days);
                  	//理财期违约收益
                  	arrival_earn = parseFloat($("#redeem_money").val()) * breach_rate / 365 / 100 * (days);
                  	$("#q_rate").html(breach_rate+"%");
	            }
	            else if(licai_status == 2){
                   	//预热期完成收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_rate / 365 / 100 * (before_days);
                  	//理财期完成收益
                  	arrival_earn = parseFloat($("#redeem_money").val()) * interest_rate / 365 / 100 * (days);
                  	$("#q_rate").html(interest_rate+"%");
	            }
	       	}
	       	else{
	            before_arrival_earn = 0;
	            arrival_earn = income_money_val*money_val/365/100;
	        }
          	//预计收益
          	arrival_amount = parseFloat($("#redeem_money").val())+ before_arrival_earn + arrival_earn;
          	$("#redeem_interest_money").html(arrival_earn.toFixed(2) +"元");
          	$("#expect_amount").html(arrival_amount.toFixed(2));   //预计到账金额
          	$("#expect_before_earn").html(before_arrival_earn.toFixed(2));     //预计收益
          	$("#expect_earn").html(arrival_earn.toFixed(2));   //预计理财收益
        }
    }
    
    $("#redeem_btn").click(function(){
    
		var ajaxurl = '{url_wap r="licai#uc_redeem_add"}';
        if(!$.trim($("input[name='redeem_money']").val()))
        {			 
            $.alert("请输入要赎回的金额");
            return false;
        }
        if(!$.trim($("input[name='paypassword']").val()))
        {
            $.alert("请输入付款密码");
            return false;
        }
        var id = $("#id").val();
		var redeem_money =  $.trim($("#redeem_money").val());
        var paypassword =  $.trim($("#paypassword").val());
        var query = new Object();
        query.id = $.trim($("#id").val());
		query.redeem_money = $.trim($("#redeem_money").val());
        query.paypassword = $.trim($("#paypassword").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(data){
				if(data.status == 1){
					var href = APP_ROOT+'/index.php?ctl=licai&act=uc_buyed_lc&id='+id;
					$.router.loadPage(href);
				}else{
	               if(data.info!="")
                    {
                        $.alert(data.info);   
                    }     
				};
            }
        
        });
     });
});