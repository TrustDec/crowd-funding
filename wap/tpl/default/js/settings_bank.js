$(document).on("pageInit","#settings-bank", function(e, pageId, $page) {
	$("#Jbank_bankcard").bankInput();
	$(".J_check_and_postData").on('click',function(){
		check_and_postData();
	});
	function check_and_postData(){
		if(confirm("一旦保存将不可以修改,您确定吗？")){
			if($("#ex_real_name").val()==""){
				$.showErr("请填写姓名");
				return false;
			}
			if($("#ex_account_bank").val()==""){
				$.showErr("请填写开户银行");
				return false;
			}
			if($("#Jbank_bankcard").val()==""){
				$.showErr("请填写银行帐号");
				return false;
			}
			if($("#ex_contact").val()==""){
				$.showErr("请填写联系电话");
				return false;
			}
			if($("#ex_qq").val()==""){
				$.showErr("请填写联系qq");
				return false;
			}
			
			var ex_real_name=$("#ex_real_name").val();
			var ex_account_bank=$("#ex_account_bank").val();
			var ex_account_info=$("#Jbank_bankcard").val();
			var ex_contact=$("#ex_contact").val();
			var ex_qq=$("#ex_qq").val();
			var post_url=APP_ROOT+'/index.php?ctl=settings&act=save_bank';
			
			var query=new Object();
			query.ex_real_name=ex_real_name;
			query.ex_account_bank=ex_account_bank;
			query.ex_account_info=ex_account_info;
			query.ex_contact=ex_contact;
			query.ex_qq=ex_qq;
			
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
					success:function(data){
						if(data.info!=null){
							$.showErr(data.info);
						}else{
							if(data.status==1){
								$.showSuccess("保存成功!",function(){
									$.router.loadPage(window.location.href);
								});
							}
							if(data.status==0){
								$.showErr("保存失败!");
							}
						}
				},error:function(){
					$.showErr("系统繁忙，稍后请重试!");
				}
			});
			return false;
		}else{
			return false;
		}
	
	}
});