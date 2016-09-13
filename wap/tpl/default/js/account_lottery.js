$(document).on("pageInit","#account-lottery", function(e, pageId, $page) {
	$("#get_lottery_sn").on('click',function(){
		var deal_id=$(this).attr("deal_id");
		//$.weeboxs.open("抽奖中,请勿刷新", {boxid:'lottery_title',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'抽奖中',width:200,type:'wee'});
		$.ajax({
			url:APP_ROOT+"/index.php?ctl=ajax&act=do_get_lottery_sn&id="+deal_id,
			type:"POST",
			dataType:'json',
			success:function(o){
				if(o.status ==1)
				{
					//$.weeboxs.open(o.html, {boxid:'lottery',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'抽奖中',width:720,height:300,type:'wee'});
					$(".lottery_start").hide();
					$("#lottery_box_ajax").hide();
					$("#lottery_luckyer_box").show();
					$("#lottery_luckyer_box").html(o.html);
					$("#search_but").addClass("change_css");
					$("#lottery_table").addClass("table_change");
					ajax_lottery();
				}
				else if(o.status ==-1)
					show_login();
				else
					$.showErr(o.info);
			}
		});
	});
	function ajax_lottery(){
		$("#btn_no").bind("click",function(){
			$("#lottery_luckyer_box").hide();
			$(".lottery_start").show();
			$("#lottery_box_ajax").show();
			$("#search_but").removeClass("change_css");
			$("#lottery_table").removeClass("table_change");
		});
		$("input[name='lottery_num']").bind("change",function(){
			var obj=$(this);
			var query=new Object();
			query.lottery_sn=obj.val();
			query.deal_id=obj.attr('data_id');
			query.number=obj.attr('rel');
			
			$.ajax({
				url:APP_ROOT+"/index.php?ctl=ajax&act=lottery_sn_check",
				type:"GET",
				data:query,
				dataType:'json',
				success:function(o){
					if(o.status ==1)
					{
						obj.parent().parent().find("span['rel=user_name']").hmtl(o.user_name);
					}
					else if(o.status ==2)
					{	
						$.showErr(o.info,function(){
							var ori=obj.attr("ori");
							obj.val(ori);
						});
						
					}
					else if(o.status ==-1)
						show_login();
					else
					{
						$.showErr(o.info,function(){
							//location.href=o.url;
							$.router.loadPage(o.url);
						});
					}
				}
			});
		});
		
		$("#btn_yes").bind('click',function(){
			var query=new Object();
			query.lottery_num=$("input[name='lottery_num']").val();
			query.deal_id=$("input[name='deal_id']").val();
			
			var lottery_num = new Array();
			$("input[name='lottery_num']").each(function(i,o){			
				lottery_num.push($(o).val());
			});	
			query['lottery_num'] = lottery_num;
			
			$.ajax({
				url:APP_ROOT+"/index.php?ctl=ajax&act=do_lottery_luckyer",
				type:'GET',
				data:query,
				dataType:'json',
				success:function(o){
					if(o.status ==1)
					{
						$.showSuccess("抽奖成功",function(){
						 	$.router.loadPage(window.location.href);
						});
					}
					else if(o.status ==-1)
						show_login();
					else
					{
						$.showErr(o.info,function(){
							if(o.url !='')
								//location.href=o.url;
								$.router.loadPage(o.url);
						});
					}
				}
			});	
		});
	}
	$("#search_but").bind("click",function(){
		var deal_id=$("input[name='id']").val();
		var query=new Object();
		query.ajax=1;
		query.id=deal_id;
		query.user_name=$("input[name='user_name']").val();
		query.lottery_sn=$("input[name='lottery_sn']").val();
		$.ajax({
			url:APP_ROOT+"/index.php?ctl=account&act=lottery",
			type:"POST",
			data:query,
			dataType:'json',
			success:function(o){
				if(o.status ==1)
				{

					$("#lottery_box_ajax").html(o.html);
				}
				else if(o.status ==-1)
					show_login();
				else
					alert(o.info);
			}
		});
	});
	
	$("#lottery_box_ajax .pages a").click(function(){
		var url = $(this).attr("href");
		var query=new Object();
		query.ajax=1;
		$.ajax({
			url:url,
			data:query,
			dataType:'json',
			cache:false,
			success:function(o){
				if(o.status ==1)
				{

					$("#lottery_box_ajax").html(o.html);
				}
				else if(o.status ==-1)
					show_login();
				else
					alert(o.info);
			}
		});
		return false;
	});
}