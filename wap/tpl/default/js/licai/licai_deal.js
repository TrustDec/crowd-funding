$(document).on("pageInit","#licai-deal", function(e, pageId, $page) {
    fun_money();
    leftTimeAct("#left_time");
	
	function fun_money(){
        // 预期一天收益
        var $deal_top_r_bd=$("#deal_top_r_bd"),
            $min_money=$deal_top_r_bd.find("input[name='min_money']"),
            $money=$deal_top_r_bd.find("input[name='money']"),
            $income_money=$deal_top_r_bd.find("input[name='income_money']"),
            endTime = parseInt($("#left_time").attr("data"))+3600*24,
            leftTime = endTime - system_time;
            
        if(!($money.val())){
            $income_money.attr("value",0);
        }
		$("#money").keyup(function(){
       
            var money_val= $.trim($("#money").val());	
            if(parseFloat($("#user_left_money").attr("data")) < parseFloat(money_val)){
                $("#user_left_money_tip").show();
            }
            else{
                $("#user_left_money_tip").hide();
            }
			
            if(licai_type > 0){
                if(parseInt(licai_interest_json[licai_interest_json.length - 1]['max_money']) <= money_val){
                    income_money_val = parseFloat(licai_interest_json[licai_interest_json.length - 1]['interest_rate']);
                    before_money_val = parseFloat(licai_interest_json[licai_interest_json.length - 1]['before_rate']);
                    site_buy_fee_rate= parseFloat(licai_interest_json[licai_interest_json.length - 1]['site_buy_fee_rate']);
                    redemption_fee_rate= parseFloat(licai_interest_json[licai_interest_json.length - 1]['redemption_fee_rate']);
                }
                else{
                    $.each(licai_interest_json,function(i,v){
                        if(parseInt(v['min_money']) <= money_val && parseInt(v['max_money']) > money_val){
                            income_money_val = parseFloat(v['interest_rate']);
                            before_money_val = parseFloat(v['before_rate']);
                            site_buy_fee_rate= parseFloat(v['site_buy_fee_rate']);
                            redemption_fee_rate= parseFloat(v['redemption_fee_rate']);
                        }
                    });
                }
            }
            else{
                income_money_val = licai_interest_json;
            }

            $("#verify_money").html(money_val);
            if(money_val){
				
                if(licai_type > 0){
                    var normal_rate=income_money_val/100;  // 正常利率
                    var preheat_rate=before_money_val/100;  // 预热利率
                    var procedures_rate=site_buy_fee_rate/100;  // 网站手续费率
                    var redemption_rate=redemption_fee_rate/100;  // 赎回手续费率
                    var new_money_val=money_val-money_val*procedures_rate;  // 扣除手续费后金额
                    
                    // 收益
                    var income_money=(new_money_val*normal_rate*buy_day)/365 + (new_money_val*preheat_rate*before_day)/365;
                    var redemption_money=((new_money_val)*redemption_rate*(buy_day+before_day))/365; // 赎回手续费
                    var new_income_money=(income_money-redemption_money).toFixed(2);
                    $income_money.attr("value",new_income_money);
					$(".J_u_money_sy").html(new_income_money);
                }
                else
                {
                    var redemption_fee_rate = income_money_val['redemption_fee_rate'];
                    var site_buy_fee_rate = income_money_val['site_buy_fee_rate'];
                    var platform_rate = income_money_val['platform_rate'];
                    var average_income_rate = income_money_val['average_income_rate']
                    var procedures_rate=site_buy_fee_rate/100;  // 网站手续费率
                    var redemption_rate=redemption_fee_rate/100;  // 赎回手续费率
                    var preheat_rate = average_income_rate/100; //收益
                    var new_money_val=money_val-money_val*procedures_rate;  // 扣除手续费后金额
                    //收益
                    var income_money= (new_money_val*preheat_rate*buy_day)/365;
                    var redemption_money=(new_money_val)*redemption_rate*buy_day/365;  // 赎回手续费
                    var new_income_money=(income_money-redemption_money).toFixed(2);
                    $income_money.attr("value",new_income_money);
					$(".J_u_money_sy").html(new_income_money);  
                }
            }
        });
        
		
        // 我要投资
        buy();
        function buy(){
			
           $("#pay_deal").click(function(){
		   	  var id= $.trim($("#id").val());
			  var money_val= $.trim($("#money").val());
			  var min_money= $.trim($("#min_money").val());  
			  var tc_money= $.trim($("#tc_money").val()); 
			  
                if(endTime!=0&&leftTime<=0){
                    $.alert("项目已结束！");
                    return false;
                }
                if($deal_top_r_bd.find("input[name='own_pro']").length){
                    $.alert("不能购买自己发布的理财产品！");
                    return false;
                }
                if(parseFloat(tc_money) < parseFloat(money_val)){
                    $.alert("您的账户余额不足！");
                    return false;
                }
                if(!(money_val)){
					 $.alert("请输入金额！");
                    return false;
                }
                if(parseFloat(money_val) < parseFloat(min_money)){
                    $.alert("最低金额不能低于"+ min_money +"元");
                    return false;
                }
                else if(!($.trim($("#pay_inmoney_password").val()))){
                    $.alert("请输入付款密码！");
                    return false;
                }
                else{
                    var ajaxurl = '{url_wap r="licai#bid"}';
			        var query = new Object();
			        
			        query.id = $.trim($("#id").val());
			        query.money = $.trim($("#money").val());
			        query.paypassword = $.trim($("#pay_inmoney_password").val());
			        query.post_type = "json";
			        $.ajax({
			            url:ajaxurl,
			            data:query,
			            type:"Post",
			            dataType:"json",
			            success:function(data){
                            if(data.status==1){
                                $.showSuccess(data.info,function(){	
									var href= APP_ROOT+'/index.php?ctl=licai&act=uc_buyed_lc';
                                    $.router.loadPage(href);
								});
                            }else{
                                $.showErr(data.info);
                            }
			            }
			        });
                }
            });
            
        }
    }
	
   // 项目剩余时间倒计时
    function leftTimeAct(left_time){
        var leftTimeActInv = null;
        clearTimeout(leftTimeActInv);
        $(left_time).each(function(){
			var endTime = parseInt($(this).attr("data"));
            var leftTime = endTime - system_time ;
            if(endTime){
                if(leftTime > 0){
                    var day  =  parseInt(leftTime / 24 /3600);
                    var hour = parseInt((leftTime % (24 *3600)) / 3600);
                    var min = parseInt((leftTime % 3600) / 60);
                    var sec = parseInt((leftTime % 3600) % 60);
                    $(this).find(".day").html((day<10?"0"+day:day));
                    $(this).find(".hour").html((hour<10?"0"+hour:hour));
                    $(this).find(".min").html((min<10?"0"+min:min));
                    $(this).find(".sec").html((sec<10?"0"+sec:sec));
                    system_time++;
                    //$(this).attr("data",leftTime);
                }
                else{
                    $(this).html("已结束");
                }
            }
            else{
                $(this).html("永久有效");
            }
        });
        leftTimeActInv = setTimeout(function(){
            leftTimeAct(left_time);
        },1000);
    }

   /* if ({$licai.type} == 0){	
		$(function(){

	        var myData = new Array(
	            {foreach from="$data.data_table" item=item name="dt"}
	                ['{$item.history_date}',{$item.rate}]{if !$smarty.foreach.dt.last},{/if}
	            {/foreach}
	        );
	        var myChart = new JSChart('data_table', 'line');
	        myChart.setAxisNameX("");
	        myChart.setAxisNameY("");
	        myChart.setIntervalStartY(0);
	        myChart.setAxisPaddingTop(10);
	        myChart.setDataArray(myData);
	        myChart.setTitle('');
	        myChart.setSize(360, 200);
	        myChart.setBarColor('#39a1ea');
	        myChart.draw();
	  		});
	}*/
});