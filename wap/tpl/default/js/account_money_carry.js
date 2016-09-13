$(document).on("pageInit","#account-money_carry", function(e, pageId, $page) {
	$("#Jcarry_amount").bind("blur",function(){
		use_money=parseFloat($(this).val());
		money=money-ready_refund_money;
		if(use_money<=0){
			$.showErr("提现金额要大于0元");
			return false;
		}
		left_money=money-use_money;
		if(left_money<0){
			$(this).attr("value","0");
			$.showErr("提现金额不能超过"+money+"元");
		}
		else{
			$("#Jcarry_acount_balance").html("￥"+foramtmoney(left_money,2)+"元");
		}
	});
});
$(document).on("pageInit","#account-money_carry_log", function(e, pageId, $page) {
	$(".delrefund").on("click",function(){
		var refund_item_id = $(this).attr("rel");
		var ajaxurl = APP_ROOT+'/index.php?ctl=account&act=delrefund&id='+refund_item_id;
		var query = new Object();
		query.ajax = 1;
		$.confirm("确定删除该记录吗？",function(){
			$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						if(ajaxobj.status==1)
						{						
							close_pop();
							location.reload();
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
		
		});
		return false;
	});
});
$(document).on("pageInit","#account-money_carry_bank", function(e, pageId, $page) {
	$(".J_carry_bank").on('click',function(){
		var obj = $(this);
		tabFun(obj);
	});
	if(is_tg && ips_acct_no){
		Jcarry_tj();
		var result_pLock=0;
		checkIpsBalance(0,user_info_id,function(result){
			if(result.pErrCode=="1"){
				result_pLock=result.pLock;
				$(".J_u_money_0").html(result.pBalance-result.pLock+"元");
				$("#Jcarry_totalAmount").val(result.pBalance);
			}
		});
		$("input[name='money']").bind("blur",function(){
			if($(this).val()){
				get_pay_url = APP_ROOT+"/index.php?ctl=ajax&act=get_carry_fee";
				var query = new Object();
				query.money=$("input[name='money']").val();
				$.ajax({
					url: get_pay_url,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
	 					if(ajaxobj.status==1){
 						 	$("#Jcarry_fee").html(ajaxobj.fee+" 元");
						 	end_money=(parseFloat(query.money)- parseFloat(ajaxobj.fee)).toFixed(2);
						 	$("#Jcarry_realAmount").html(end_money+" 元");
						 	tg_end_money=(parseFloat($("#Jcarry_totalAmount").val()-result_pLock)- parseFloat(query.money)).toFixed(2);
						 	$("#Jcarry_acount_balance").html(tg_end_money+" 元");
						 	$("input[name='Jcarry_acount_balance_amount']").val(tg_end_money);
						}
					}
				});
			}
			else{
				$("#Jcarry_fee").html("0.00 元");
				$("#Jcarry_realAmount").html("0.00 元");
				$("#Jcarry_acount_balance").html("0.00 元");
			}
			
		});
		function Jcarry_tj(){
			$("#Jcarry_submit").on("click",function(){
 				if(end_money<=0){
					$.alert("您输入的金额少于提现费用");
					return false;
				}
				if(tg_end_money<=0){
					$.alert("您输入的金额超过实际金额");
					return false;
				}
				var url = APP_ROOT+"/index.php?ctl=collocation&act=DoDwTrade&user_type=0&user_id="+user_info_id+"&pTrdAmt="+$("input[name='money']").val();
				$.router.loadPage(url);
			});
		}
		$("#IPS_CARRY_FORM").submit(function(){
			if($.trim($("#Jcarry_amount").val())=="" || !$.checkNumber($("#Jcarry_amount").val()) || parseFloat($("#Jcarry_amount").val())<=0){
				$.showErr(LANG.CARRY_MONEY_NOT_TRUE,function(){
					$("#Jcarry_amount").focus();
				});
				return false;
			}
			if(parseFloat($("#Jcarry_acount_balance_res").val())<0){
				$.showErr(LANG.CARRY_MONEY_NOT_ENOUGHT,function(){
					$("#Jcarry_acount_balance_res").focus();
				});
				return false;
			}
			var url = APP_ROOT + "/index.php?ctl=collocation&act=DoDwTrade&user_type=0&user_id="+user_info_id+"&pTrdAmt="+$.trim($("#Jcarry_amount").val());
			$.router.loadPage(url);
			return false;
		});
	}
	$(".J_deal_bank").click(function(){
		var obj = $(this);
		var query = new Object();
		query.id = $(this).attr("dataid");
		
		$.confirm("确定要删除吗",function(){
			$.ajax({
				url:APP_ROOT+"/index.php?ctl=account&act=delbank",
				data:query,
				type:"post",
				dataType:"json",
				success:function(result){
					if(result.status==1)
					{
						obj.parent().parent().remove();
						$.router.loadPage(window.location.href);
					}
					else{
						$.showErr(result.info);
					}
					$.closeModal();
				},
				error:function(){
					$.showErr("发生错误");
				}
			});
		});
	});
	
	$("#Jbank_bank_id").live("change",function(){
		if($(this).val()=="other"){
			$("#Jbank_otherbank").removeClass("hide");
		}
		else{
			$("#Jbank_otherbank").addClass("hide");
		}
	}).live('click', function () {
        if ($.data(this, 'events') == null || $.data(this, 'events').change == undefined){
            $(this).bind('change', function () {
               if($(this).val()=="other"){
					$("#Jbank_otherbank").removeClass("hide");
				}
				else{
					$("#Jbank_otherbank").addClass("hide");
				}
            });
        }
	});
	
	$("#addbank-box .reset_btn").live("click",function(){
		$.weeboxs.close("addbank-box");
	});
	function tabFun(obj){
		var $tab_bd_text=$(".tab_bd_text");
		$(obj).addClass("cur").siblings().removeClass("cur");
		if($(obj).attr("rel")=="carry_type1"){
			$("#carry_type1").show().siblings().hide();
		}
		else{
			$("#carry_type2").show().siblings().hide();
			// Jcarry_tj();
		}
	}
	$("#add_bank").click(function(){
		$.showPreloader('正在处理，请稍等');
		$.ajax({
			url:APP_ROOT+"/index.php?ctl=ajax&act=add_bank",
			dataType:"json",
			success:function(result){
				$.hidePreloader();
				if(result.status==1)
				{
					var href = APP_ROOT+"/index.php?ctl=account&act=money_carry_addbank";
					$.router.loadPage(href);
				}
				else{
					$.showErr(result.info,function(){
						if(result.jump!='')
							$.router.loadPage(result.jump);
					});
					
				}
			}
		});
	});
});
$(document).on("pageInit","#account-money_carry_addbank", function(e, pageId, $page) {
	$("#Jbank_bankcard,#Jbank_rebankcard").bankInput(); 
	$("#Jbank_bank_id").bind("change",function(){
		if($(this).val()=='other'){
			$(".otherbank_box").show().css("display","-webkit-box");
		}else{
			$(".otherbank_box").hide();
		}
	});
	$("#account_money_carry_addbank_from").find(".ui-button").bind("click",function(){
		if($("#Jbank_real_name").val()==""){
			$.alert("请输入开户名",function(){
				$("#Jbank_real_name").focus();
			});
			return false;
		}
		if($("select[name='bank_id']").find('option').not(function() {return !this.selected}).val()==""){
			$.alert("请选择银行");
			$("#Jbank_bank_id").focus();
			return false;
		}
		if($("select[name='bank_id']").find('option').not(function() {return !this.selected}).val()=="other" && $("select[name='otherbank']").find('option').not(function() {return !this.selected}).val()==""){
			$.alert("请选择银行");
			$("#Jbank_bank_id").focus();
			return false;
		}
		
		
		if($("select[name='province']").find('option').not(function() {return !this.selected}).val()=="" && $("select[name='city']").find('option').not(function() {return !this.selected}).val()=="0"){
			$.alert("请选择开户行所在地");
			$("#Jbank_region_lv3").focus();
			return false;
		}
		if($("#Jbank_bankzone").val()==""){
			$.alert("请输入开户行网点",function(){
				$("#Jbank_bankzone").focus();
			});
			return false;
		}
		if($.trim($("#Jbank_bankcard").val())==""){
			$.alert("请输入银行卡号");
			$("#Jbank_bankcard").focus();
			return false;
		}
		if($.trim($("#Jbank_rebankcard").val())==""){
			$.alert("请输入确认卡号");
			$("#Jbank_rebankcard").focus();
			return false;
		}
		if($.trim($("#Jbank_bankcard").val())!=$.trim($("#Jbank_rebankcard").val())){
			$.alert("确认卡号不一致");
			$("#Jbank_rebankcard").focus();
			return false;
		}
		ajax_form("#account_money_carry_addbank_from");
	});
});