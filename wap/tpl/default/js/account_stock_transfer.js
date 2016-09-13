$(document).on("pageInit","#account-stock_transfer_add", function(e, pageId, $page) {
	bind_stock_transfer(invest_id,deal_name,invote_mini_money,user_num,ajaxurl);
});
$(document).on("pageInit","#account-stock_transfer_edit", function(e, pageId, $page) {
	bind_stock_transfer(stock_transfer_info_id,'',invote_mini_money,user_num,ajaxurl);
});
function bind_stock_transfer(id,deal_name,invote_mini_money,user_num,ajaxurl){
	$(".btn_submit").bind("click",function(){
		if($("#price").val()==''){
			$.showErr("金额不能为空！");
			return false;
		}
		if($("#num").val()==''){
			$.showErr("转让股数不能为空！");
			return false;
		}
		if($("#day").val()==''){
			$.showErr("天数不能为空！");
			return false;
		}	
		if($("#num").val()>user_num){
			$.showErr("转让股数不能大于拥有股数！");
			return false;
		}	
		var ajaxurl = ajaxurl;
		var price=$("#price").val();
		var num=$("#num").val();
		var day=$("#day").val();	
		
		if(!/^[0-9]+(.[0-9]{2})?$/.test(price) &&!/^[0-9]+(.[0-9]{1})?$/.test(price) ){  
			$.showErr("价格至多保留两位小数!"); 
			return false;
	    } 
		if(!/^[0-9]*$/.test(num)){  
			$.showErr("天数必须是正整数!"); 
			return false;
	    } 
		if(!/^[0-9]*$/.test(day)){  
			$.showErr("股数必须是正整数!"); 
			return false;
	    } 
		var deal_name = deal_name;
		var query = new Object();
		query.price=price;
		query.num=num;
		query.day=day;
		query.id=id;
		query.stock_value=invote_mini_money*num;
		if(deal_name){	
			query.deal_name=deal_name;
		}
		$.ajax({
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success:function(data){
				if(data.status==0){
					$.showErr(data.info,function(){
						if(data.jump!="")
						{
							location.href = data.jump;
						}
					});
					return false;
				}
				if(data.status==2){
					$.showErr(data.info);
					return false;
				}
				if(data.status==1){
					$.showSuccess("提交成功",function(){
						if(data.jump!="")
						{
							location.href = data.jump;
						}
					});
				}
			}
		});
	});		
	return false;
}