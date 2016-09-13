$(document).on("pageInit","#account-get_investor_status", function(e, pageId, $page) {
	$(".gentou_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,1,"是否允许跟投",2);
		 
	});
	$(".gentou_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,0,"是否要拒绝跟投",2);
		 
	});
	$(".lead_examine_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,1,"是否要允许投资",1);
	});
	$(".lead_examine_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,0,"是否要拒绝该领投人投资",1);
	});
	$(".J_examine").on('click',function(){
		var item_id = $(this).attr("rel");
		var ajaxobj = $(".examine_"+item_id).html();
		$.modal({
			title: '询价审核',
	      	text: ajaxobj,
	      	buttons: []
		});
		J_examine();
	});
	// 询价审核
	function J_examine(){
		$(".examine_yes").on('click',function(){
			var id=$(this).attr("rel");
			var stock_money=$(this).attr("title");
			deal_investor(id,1,"是否要通过该询价？通过后您的项目融资金额将会变成"+stock_money,0)
			 
		});
		$(".examine_no").on('click',function(){
			var id=$(this).attr("rel");
			var stock_money=$(this).attr("title");
			deal_investor(id,0,"是否要拒绝该询价？",0);
			 
		});
	}
	function deal_investor(id,status,msg,type){
		var ajaxurl = APP_ROOT+"/index.php?ctl=account&act=investor_examine&status="+status+"&id="+id+"&type="+type;
		$.closeModal();
		$.confirm(msg,function(){
			$.ajax({
				url:ajaxurl,
				dataType:"json",
				type:'POST',
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.closeModal();
						$.alert("已允许成功",function(){
							$.router.loadPage(window.location.href);
						});
					}else{
						$.closeModal();
						$.showErr(ajaxobj.info);
						
					}
				}
			});
		});
	}
});

$(document).on("pageInit","#account-get_leader_list", function(e, pageId, $page) {
	$(".lead_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_lead(id,1,"是否允许该用户成为领投人",2);
	});
	$(".lead_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_lead(id,0,"是否要拒绝该用户成为领投人",2);
	});
	
	function deal_lead(id,status,msg,type){
		var ajaxurl = APP_ROOT+"/index.php?ctl=account&act=lead_examine&status="+status+"&id="+id+"&type="+type;
		$.confirm(msg,function(){
			$.ajax({
				url:ajaxurl,
				dataType:"json",
				type:'POST',
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.closeModal();
						$.alert("已允许成功",function(){
							$.router.loadPage(window.location.href);
						});
					}else{
						$.closeModal();
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});