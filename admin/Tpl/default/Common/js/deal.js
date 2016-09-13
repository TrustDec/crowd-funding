function pay(id)
{
	$.weeboxs.open(ROOT+'?m='+MODULE_NAME+'&a=pay&id='+id, {contentType:'ajax',showButton:false,title:'发放筹款',width:600,height:180});
}

function offline(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert("请选择需要下架的项目");
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm("确定要下架选中的项目吗？"))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=delete&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}

function deal_item(id)
{
	location.href = ROOT + '?m='+MODULE_NAME+'&a=deal_item&id='+id;
}

function pay_log(id)
{
	location.href = ROOT + '?m='+MODULE_NAME+'&a=pay_log&id='+id;
}

function deal_log(id)
{
	location.href = ROOT + '?m='+MODULE_NAME+'&a=deal_log&id='+id;
}

function add_deal_item(deal_id)
{
	location.href = ROOT + '?m='+MODULE_NAME+'&a=add_deal_item&id='+deal_id;
}
function add_investor(){
	location.href = ROOT+'?m='+MODULE_NAME+'&a=add_investor&type=1&action_id='+ACTION_ID;
}

function edit_deal_item(item_id)
{
	location.href = ROOT + '?m='+MODULE_NAME+'&a=edit_deal_item&id='+item_id;
}

function del_deal_item(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert("请选择需要删除的项目");
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm("确定要删除选中的项目吗？"))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=del_deal_item&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}

function add_pay_log(deal_id)
{
	$.weeboxs.open(ROOT+'?m='+MODULE_NAME+'&a=add_pay_log&id='+deal_id, {contentType:'ajax',showButton:false,title:"发放筹款",width:600,height:220});
}

function del_pay_log(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert("请选择需要删除的项目");
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm("确定要删除选中的项目吗？"))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=del_pay_log&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}

function del_deal_log(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert("请选择需要删除的项目");
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm("确定要删除选中的项目吗？"))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=del_deal_log&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}
function get_sharefee_list(deal_id,share_status)
{
	if(share_status==-1)
		location.href = ROOT + '?m='+MODULE_NAME+'&a=sharefee_list&deal_id='+deal_id;
	else
		location.href = ROOT + '?m='+MODULE_NAME+'&a=sharefee_list&deal_id='+deal_id+'&share_status='+share_status;	
}

