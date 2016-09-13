$(document).on("pageInit","#deal-show", function(e, pageId, $page) {
	// 领投请求
	ajax_applicate_leader();

	// 跟投请求  IS_ENQUIRY:询价是否开启
	if(IS_ENQUIRY == 0){
		enquiry_money_first(".invest_btn_box");
	}
	if(IS_ENQUIRY == 1){
		ajax_continue_investor();
	}

	// 领投请求
	function ajax_applicate_leader(){
		$("#applicate_leader").bind("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href=APP_ROOT+"/index.php?ctl=user&act=login";
				$.alert("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl=APP_ROOT+"/index.php?ctl=investor&act=leader_ajax&deal_id="+deal_info_id;
			var leader_ajax=$("#leader_ajax").val();
			var query =new Object();
			query.leader_ajax=leader_ajax;
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				data:query,
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.url!=''){
								$.router.loadPage(ajaxobj.url);
							}
							
						});
					} 
					
					if(ajaxobj.status==2){
						//领投申请不通过
						$.closeModal();
						$.confirm(ajaxobj.info,function(){
							if(ajaxobj.url!=''){
								$.router.loadPage(ajaxobj.url);
							}
						});
					}
					if(ajaxobj.status==1){
				    	$.modal({
							title: '领投投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						add_investment_money();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
				    	$.modal({
							title: '追加投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						add_investment_money();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==6){
						//领投申请不通过
						$.confirm(ajaxobj.info,function(){
							$.router.loadPage(ajaxobj.url);
						});
					}
					if(ajaxobj.status==3){
						//支付诚意金
						var href=APP_ROOT+"/index.php?ctl=account&act=mortgate_pay";
						$.router.loadPage(href);
					}
					if(ajaxobj.status==7){
						//已经“领投”,无法再跟投
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==5){
						//投资不通过,资金无法再次追加了！
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==8){
						//项目已经结束无法投资！
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==9){
						//投资者认证未通过！
						$.showErr(ajaxobj.info,function(){
							var href=APP_ROOT+"/index.php?ctl=investor&act=index";
							$.router.loadPage(href);
						});
						return false;
					}
				}
			});
			return false;
		});
	}

	// ajax删除“领投”，但是未审核的数据
	function delete_leader_investor(){
		$.showIndicator();
		var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=delete_leader_investor&deal_id="+deal_info_id;
		var leader_ajax=$("#leader_ajax").val();
		var query =new Object();
		query.leader_ajax=leader_ajax;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			data:query,
			success:function(ajaxobj){
				$.hideIndicator();
				if(ajaxobj.status==1){
					//"领投申请"取消成功
					$.toast(ajaxobj.info,1000);
					setTimeout(
						function(){
							$.router.loadPage(window.location.href);
						}
					, 1000);
				}
				if(ajaxobj.status==0){
					//删除失败
					$.toast(ajaxobj.info,1000);
				}
			}
		});
	}

	// 领投人详细资料
	function leader_detailed_information(){
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=leader_detailed_information&id="+leader_info_id;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1){
					$.alert(ajaxobj.html);
				}
			    if(ajaxobj.status==2){
					$.toast(ajaxobj.info);
				}
			}
		});
	}

	// 跟投请求(询价未开启 IS_ENQUIRY=0)
	function enquiry_money_first(obj){
		var $btn_box = $(obj);
		$btn_box.find(".btn_enquiry_money").on("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href=APP_ROOT+"/index.php?ctl=user&act=login&deal_id="+deal_info_id;
				$.showErr("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=ajax_continue_investor&deal_id="+deal_info_id;
			var query = new Object();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 投资成功！
						$.toast(ajaxobj.info,1000);
						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					if(ajaxobj.status==0){
						$.alert(ajaxobj.info,function(){
							if(ajaxobj.url){
								href=ajaxobj.url;
								$.router.loadPage(href);
							}
						});
					}
					if(ajaxobj.status==2){
						//调取第一次跟投页面
			    		$.modal({
							title: '项目投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
						//调取后续追加跟投页面
			    		$.modal({
							title: '项目追加投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					
					if(ajaxobj.status==5){
						//无法再次跟投追加金额
						$.alert(ajaxobj.info);
					}
					if(ajaxobj.status==8){
						//您已为领投人,无需再进行跟投！
						$.alert(ajaxobj.info);
					}
					if(ajaxobj.status==7){
						$.closeModal();
						//已经申请“领投”，但是未审核
						$.confirm("您确定要取消,领投申请吗？",function(){
							delete_leader_investor();
						});
					}
				}
				
			});
			return false;
		});
		$btn_box.find(".button_n").bind("click",function(){
			$.closeModal();
		});
	}

	// 跟投请求(询价已开启 IS_ENQUIRY=1)
	function ajax_continue_investor(){
		$("#continue_investor").bind("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href = APP_ROOT+"/index.php?ctl=user&act=login";
				$.alert("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=ajax_continue_investor&deal_id="+deal_info_id;
			var leader_ajax=$("#continue_ajax").val();
			var query =new Object();
			query.leader_ajax=leader_ajax;
				$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				data:query,
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						//用户未交纳诚意金
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.url){
								href=ajaxobj.url;
								$.router.loadPage(href);
							}
							
						});
						return false;
					}
					if(ajaxobj.status==1){
						//进入询价页面
						$.modal({
							title: '项目跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_page();
						count_invest_money(invote_mini_money);
					}
					return false;
 				}
			});
			return false;
		});
	}

	// 进入询价页面
	function enquiry_page(){
		$("#enquiry_index .button_y").bind("click",function(){
			$.showIndicator();
			var ajaxurl=$("#enquiry_index").attr("action");
			var query=$("#enquiry_index").serialize();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 项目询价
						$.closeModal();
			    		$.modal({
							title: '项目询价',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==2){
						//调取第一次跟投页面
						$.closeModal();
			    		$.modal({
							title: '项目跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
						//调取后续追加跟投页面
						$.closeModal();
			    		$.modal({
							title: '项目追加跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==3){
						//(次数大于0,不能再次询价)
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==5){
						//无法再次跟投追加金额
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==8){
						//您已为领投人,无需再进行跟投！
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==7){
						$.closeModal();
						//已经申请“领投”，但是未审核
						$.confirm("您确定要取消,领投申请吗？",function(){
							delete_leader_investor();
						});
					}
				}
			});
		});
	}

	//询价信息入库
	function enquiry_save(){
		$("#enquiry_two .button_y").bind("click",function(){
			if($("#stock_value").val()==''){
				$.toast("项目估值不能为空");
				return false;
			}
			if((isNaN($(".stock_value").val())||parseFloat($(".stock_value").val())<=0)||$(".stock_value").val()=='')
			{
				$.toast("请输入正确的估值金额");
				return false;
			}
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.toast("请输入正确的投资金额");
				return false;
			}
			if($("#investment_reason").val()==''){
				$.toast("投资理由不能为空");
				return false;
			}
			if($("#funding_to_help").val()==''){
				$.toast("资金帮助不能为空");
				return false;
			}
			var ajaxurl = $("#enquiry_two").attr("action");
			var query = $("#enquiry_two").serialize();
			$.showIndicator();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						$.toast(ajaxobj.info,1000);
						return false;
					}
					if(ajaxobj.status==1){
	                    $.closeModal();
						$.toast(ajaxobj.info,1000);
						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					 
				}
			});
		});
	}

	// 确定追加跟投处理
	function enquiry_money_save(){
		$("#add_enquiry_money .button_y").bind("click",function(){
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.toast("请输入正确的投资金额");
				return false;
			}
			var ajaxurl = $("#add_enquiry_money").attr("action");
			var query = $("#add_enquiry_money").serialize();
			$.showIndicator();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
 						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==1){
						// 追加投资成功
 						$.closeModal();
 						$.toast(ajaxobj.info,1000);
 						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
				}
			});
		});
		return false;
	}

	// 确定领投资投资处理
	function add_investment_money(){
		$("#add_append_form .button_y").bind("click",function(){
			$.showIndicator();
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.showErr("请输入正确的投资金额");
				return false;
			}
			var ajaxurl = $("#add_append_form").attr("action");
			var query = $("#add_append_form").serialize();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 追加投资成功
 						$.closeModal();
 						$.toast(ajaxobj.info,1000);
 						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					if(ajaxobj.status==0){
						$.toast(ajaxobj.info,1000);
					}
	
				}
			});
		});
		return false;
	}
});