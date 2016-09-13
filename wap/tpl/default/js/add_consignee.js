$(document).on("pageInit","#settings-add_consignee", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});
	if(consignee_id){
		bind_del_consignee(consignee_id,del_url);
	}
 	$("#add_consignee_form").find(".ui-button").bind("click",function(){
        if($("input[name='consignee']").val()==""){
            $.alert("请填写收货人姓名");
            return false;
        }
		if($("select[name='province']").find('option').not(function() {return !this.selected}).val()==""){
            $.alert("请选择省份");
            return false;
        }
        if($("select[name='city']").find('option').not(function() {return !this.selected}).val()==""){
            $.alert("请选择城市");
            return false;
        }
        if($("textarea[name='address']").val()==""){
            $.alert("请填写详细地址");
            return false;
        }
        if($("input[name='zip']").val()==""){
            $.alert("请填写邮编");
            return false;
        }
        if($("input[name='mobile']").val()==""){
            $.alert("请填写收货人手机号码");
            return false;
        }
        ajax_form("#add_consignee_form");
    });
});