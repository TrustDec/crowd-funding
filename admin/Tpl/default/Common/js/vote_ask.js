function add_ask_row()
{
	var i = $("#ask_form").find("tr").length - 3;

	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=add_ask_row&idx="+i, 
		data: "ajax=1",
		success: function(html){
			$(html).insertBefore($("#footer_row"));
		}
	});
}

function remove_ask_row(obj)
{
	$(obj.parentNode.parentNode).remove();
}