$(document).ready(function(){
	// 兑换按钮自适应对齐属性
/*	(function(){
		var widthArr =new Array();
		$(".detail_hd_text .control-label").each(function(i,o){
			widthArr[i]= $(o).width();
		});
		var max = widthArr[0];
		for(var i=1; i<widthArr.length; i++){ 
			if(max < widthArr[i]) max=widthArr[i];
		}
		$(".detail_hd_text .submit_row .control-label").css({width:max});
	})();
*/	
	init_buy_choose();
});

/**
 * 初始化购物的选择（包括规格，数量）
 * 1. 规格必需选满，选满后如有递加价格，显示新价格，如有新库存显示新库存，并修正购买数量
 * 2. 购买数量的选择
 * 3. 更新购买按钮
 */

function init_buy_choose()
{
	//购物按钮事件
	$.buy_action = function(){

		var is_choose_all = true; //是否已选中所有规格
		var attr_checked_ids = [];
		$("#main_package_choose .package_choose").each(function(i,o){		
			if($(o).attr("is_choose"))
			{
				attr_checked_ids.push($(o).find("a[active='true']").attr("rel"));
			}
			else
			{
				is_choose_all = false;  //有一项规格未选中即为未选满
			}			
		});
		var id = deal_id;
		var number = $("#deal_num").val();
		if(is_choose_all)
			 check_order(id,number,attr_checked_ids);
	};
	$.init_buy_num_ui = function(buy_num){
		if(buy_num==1)
		{
			$(".num_choose .less").addClass("num_choose_disabled");
		}
		else
		{
			$(".num_choose .less").removeClass("num_choose_disabled");
		}
		if(buy_num>=9999)
		{
			$(".num_choose .increase").addClass("num_choose_disabled");
		}
		else
		{
			$(".num_choose .increase").removeClass("num_choose_disabled");
		}
	};
	
	//填写最小的购物数量
	if(deal_user_min_bought==0)
	{
		$("#deal_num").val(1);
		$.init_buy_num_ui(1);
	}
	else
	{
		$("#deal_num").val(deal_user_min_bought);
		$.init_buy_num_ui(deal_user_min_bought);
	}
	
	
	
	//更新购物UI
	$.init_buy_ui = function(){
		var is_choose_all = true; //是否已选中所有规格
		var is_stock = true;      //库存是否满足
		var stock = deal_stock;   //无规格时的库存数
		var deal_show_price = deal_price;
		var deal_show_buy_count = deal_buy_count;	
		var deal_remain_stock = -1;  //剩余库存 -1:无限
		
		
		
		//更新规格选项卡UI
		var attr_checked_ids = new Array();
		$("#main_package_choose .package_choose").each(function(i,o){			
			$(o).find("a").removeClass("select_a");
			if($(o).attr("is_choose"))
			{
				$(o).find("a[active='true']").addClass("select_a");
				deal_show_price+=parseFloat($(o).find("a[active='true']").attr("price"));
				attr_checked_ids.push($(o).find("a[active='true']").attr("rel"));
			}
			else
			{
				is_choose_all = false;  //有一项规格未选中即为未选满
			}	
			
		});		
		
		//开始计算库存
		attr_checked_ids = attr_checked_ids.sort();
		attr_checked_ids_str = attr_checked_ids.join("_");
		if($("#main_package_choose .package_choose").length>0)
		{			
			var attr_spec_stock_cfg = deal_attr_stock_json[attr_checked_ids_str];
			if(attr_spec_stock_cfg)
			{   
				deal_show_buy_count = attr_spec_stock_cfg['buy_count'];
				stock = attr_spec_stock_cfg['stock_cfg'];
			}			
		}
		if(stock>0)
		{
			deal_remain_stock = stock - deal_show_buy_count;
			if(deal_remain_stock<0)deal_remain_stock=0;
		}
		//更新库存显示
		if(deal_remain_stock>=0)
		{	
			$("#stock_span").find("div").show();
			$("#stock_span").find(".inventory").html(deal_remain_stock);
		}
		else
		{
			$("#stock_span").find("div").hide();
		}
		
		//判断库存，并更新提示
		var buy_num = parseInt($("#deal_num").val());
		if(deal_remain_stock>=0)
		{
			if(deal_remain_stock<deal_user_min_bought)
			{
				//剩余库存小于最小购买量，表示库存不足
				is_stock = false;
				$("#stock_tips").html("每单最少购买"+deal_user_min_bought+"份,库存不足");
			}
			else if(buy_num>deal_remain_stock)
			{
				is_stock = false;
				$("#stock_tips").html("库存不足");
			}
			else if(buy_num<deal_user_min_bought)
			{
				is_stock = false;
				$("#stock_tips").html("每单最少购买"+deal_user_min_bought+"份");
			}
			else if(deal_user_max_bought>0&&buy_num>deal_user_max_bought)
			{
				is_stock = false;
				$("#stock_tips").html("每单最多购买"+deal_user_max_bought+"份");
			}
			else
			{
				$("#stock_tips").html("");
			}
		}
		else
		{
			if(buy_num<deal_user_min_bought)
			{
				is_stock = false;
				$("#stock_tips").html("每单最少购买"+deal_user_min_bought+"份");
			}
			else if(deal_user_max_bought>0&&buy_num>deal_user_max_bought)
			{
				is_stock = false;
				$("#stock_tips").html("每单最多购买"+deal_user_max_bought+"份");
			}
			else
			{
				$("#stock_tips").html("");
			}
		}
		

		
		//更新购物按钮
		var buy_btn = $("#buy_btn");
		var buy_btn_ui = buy_btn.next();
		if(is_choose_all&&is_stock)
		{
			//更新价格
			if(buy_type!=1)
			$("#deal_price").html(deal_show_price);
			
			buy_btn.removeClass("disabled");
			
			buy_btn.unbind("click");
			buy_btn.bind("click",function(){
				$.buy_action();
			});
		}
		else
		{
			//更新价格
			if(is_choose_all)
			{
				if(buy_type!=1)
				$("#deal_price").html(deal_show_price);
			}
			else
			{
				if(buy_type!=1)
				$("#deal_price").html(deal_price);
			}
			
			
			buy_btn.attr("rel","disabled");
			buy_btn.addClass("disabled");
			
			buy_btn.unbind("click");
		}
		
		
	};
	
	$.init_buy_ui();
	$("#main_package_choose .package_choose").each(function(i,o){
		is_choose_all = false;  //有规格选项时，选中为false
		$(o).find("a").bind("click",function(){
			var spec_btn = $(this);  //当前按中的A
			var is_active = spec_btn.attr("active");
			$(o).find("a").removeAttr("active");
			$(o).removeAttr("is_choose");
			if(!is_active)
			{
				spec_btn.attr("active",true);
				$(o).attr("is_choose",true);
			}
			
			$.init_buy_ui();
		});
	});
	
	//绑定购物数量
	$("#deal_num").bind("blur",function(){
		var buy_num = $(this).val();
		if(isNaN(buy_num)||parseInt(buy_num)<=0)buy_num=1;
		if(buy_num>9999)buy_num=9999;
		$.init_buy_num_ui(buy_num);
		$(this).val(buy_num);
		$.init_buy_ui();
	});
	$("#deal_num").bind("focus",function(){
		$(this).select();
	});
	$(".num_choose .less").bind("click",function(){
		var buy_num = $("#deal_num").val();
		buy_num = parseInt(buy_num) - 1;
		if(isNaN(buy_num)||parseInt(buy_num)<=0)buy_num=1;
		if(buy_num>9999)buy_num=9999;
		$.init_buy_num_ui(buy_num);
		$("#deal_num").val(buy_num);
		$.init_buy_ui();
	});
	$(".num_choose .increase").bind("click",function(){
		var buy_num = $("#deal_num").val();
		buy_num = parseInt(buy_num) + 1;
		if(isNaN(buy_num)||parseInt(buy_num)<=0)buy_num=1;
		if(buy_num>9999)buy_num=9999;
		$.init_buy_num_ui(buy_num);
		$("#deal_num").val(buy_num);
		$.init_buy_ui();
	});
}

/**
 * 
 * @param id 商品ID
 * @param attr 购买的属性规格 array()
 * @param number  购买数量
 */
function check_order(id, number, attr){
	attr = $.extend([], attr);
	var ajaxurl = APP_ROOT + '/index.php?ctl=score_good_show&act=check_order';
	var query = new Object();
	query['id'] = id;
	query['attr[]'] = attr;
	query['number'] = number;
	$.ajax({
		url: ajaxurl,
		data: query,
		dataType: "json",
		type: "post",
		success: function(obj){
			if (obj.status == 1) {
			
				$.weeboxs.open(obj.html, {
					boxid: 'check_order_box',
					contentType: 'text',
					showButton: false,
					title: "订单信息确定",
					width: 650,
					type: 'wee',
					onopen: function(){
						ui_radiobox();
						init_ui_select();
					},
					onclose: function(){
						
					}
				});
			}
			else 
				if (obj.status == -1) {
					show_pop_login();
				}
				else {
					$.showErr(obj.info);
				}
		},
		error: function(ajaxobj){
			//			if(ajaxobj.responseText!='')
			//			alert(ajaxobj.responseText);
		}
	});
}