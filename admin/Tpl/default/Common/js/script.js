var K = null;
var viewOpAct = null;
$(document).ready(function(){
	init_word_box();
	$("#info").ajaxStart(function(){
		 $(this).html(LANG['AJAX_RUNNING']);
		 $(this).show();
	});
	$("#info").ajaxStop(function(){
		
		$("#info").oneTime(2000, function() {				    
			$(this).fadeOut(2,function(){
				$("#info").html("");				
			});			    	
		});	
	});
	
	//今天
	$("#submit_date_0").bind("click",function(){ 
		$("#q_start_time").val(dec_date(0));		
		$("#q_end_time").val(dec_date(0));
		//$("form[name='search']").submit();
		//alert($('#search_form').length);
		$('#search_form').submit();
	});
	
	//昨天
	$("#submit_date_1").bind("click",function(){ 
		$("#q_start_time").val(dec_date(1));		
		$("#q_end_time").val(dec_date(1));
		
		//$("form[name='search']").submit();
		$('#search_form').submit();
	});
	
	//最近一周
	$("#submit_date_7").bind("click",function(){ 
		$("#q_start_time").val(dec_date(7));		
		$("#q_end_time").val(dec_date(0));
		$('#search_form').submit();
	});	
	
	//上上周
	$("#submit_date_8_14").bind("click",function(){ 
		$("#q_start_time").val(dec_date(14));		
		$("#q_end_time").val(dec_date(8));
		$('#search_form').submit();
	});	
	
	
	//最近一个月
	$("#submit_date_30").bind("click",function(){ 					
		$("#q_start_time").val(dec_date(30));		
		$("#q_end_time").val(dec_date(0));	
		$('#search_form').submit();
	});	
	
	$("form").bind("submit",function(){
		var doms = $(".require");
		var check_ok = true;
		$.each(doms,function(i, dom){
			if($.trim($(dom).val())==''||($(dom).val()=='0'&& $(dom).is("select")))
			{						
					var title = $(dom).parent().parent().find(".item_title").text();
					if(!title)
					{
						title = '';
					}
					if(title.substr(title.length-1,title.length)==':' && title.substr(0,1)=='*')
					{
						title = title.substr(1,title.length-2);
					}
					else if(title.substr(title.length-1,title.length)==':'){
						title = title.substr(0,title.length-1);
					}
					else if(title.substr(0,1)=='*'){
						title = title.substr(1,title.length);
					}
					if($(dom).val()=='')
					TIP = LANG['PLEASE_FILL'];
					if($(dom).val()=='0')
					TIP = LANG['PLEASE_SELECT'];						
					alert(TIP+title);
					$(dom).focus();
					check_ok = false;
					return false;						
			}
		});
		if(!check_ok){
			return false;
		}
		
		check_ok = true;
		$(".require_radio").each(function(){
			if ($(this).find("input[type='radio']").length != 0) {
				if ($(this).find("input[type='radio']:checked").length == 0) {
					var title = $(this).parent().find(".item_title").html();
					if (!title) {
						title = '';
					}
					if (title.substr(title.length - 1, title.length) == ':') {
						title = title.substr(0, title.length - 1);
					}
					
					alert(LANG['PLEASE_SELECT'] + title);
					check_ok = false;
					return false;
				}
			}
		});
		if(!check_ok){
			return false;
		}
		
		//有查询开始日期
		if ($("#q_start_time").length == 1){
			var date1 = $.trim($("#q_start_time").val());
			if (IsDate(date1) == false){
				alert('开始时间不是有效的时间格式(yyyy-mm-dd)');
				$("#q_start_time").focus();
				return false;
			}
		}
		
		//有查询结束日期
		if ($("#q_end_time").length == 1){
			var date1 = $.trim($("#q_end_time").val());
			if (IsDate(date1) == false){
				alert('结束时间不是有效的时间格式(yyyy-mm-dd)');
				$("#q_end_time").focus();
				return false;
			}
		}
		
		//结束时间不能大于开始时间
		if ($("#q_start_time").length == 1 && $("#q_end_time").length == 1){
			var date1 = $.trim($("#q_start_time").val());
			var date2 = $.trim($("#q_end_time").val());
			
			if (dateCompare(date1,date2) == 1){
				alert('开始时间不能大于结束时间');
				$("#q_start_time").focus();
				return false;			
			}
			
			
			//有查询日期间隔限制
			if ($("#q_date_diff").length == 1 && $.trim($("#q_date_diff").val())!='' && $("#q_date_diff").val() !='0'){
				if (GetDateDiff(date1,date2)+1 > $("#q_date_diff").val()){
					alert("查询时间间隔不能大于 " + $("#q_date_diff").val() + " 天");
					$("#q_end_time").focus();
					return false;	
				}
			}
		}
	});
	
	$(".dataTable .row").hover(function(){
		$(this).addClass("row_cur");
	},function(){
		$(this).removeClass("row_cur");
		
	});
	
	$(".dataTable .row .opration").click(function(){ 
		if($(this).hasClass("v")){
			$(this).removeClass("v");
			$(this).parent().find(".viewOpBox").hide();
		}
		else{
			$(this).addClass("v");
			viewOp($(this).parent());
			$(this).parent().find(".viewOpBox").show();
			var obj = $(this);
			$("body").one("click",function(){
				$(".dataTable a.opration").removeClass("v");
				obj.parent().find(".viewOpBox").hide();
			});
			return false;
		}
	});
	
	$(".dataTable a.A_opration").click(function(){
		if($(this).hasClass("v")){
			$(this).removeClass("v");
			$(".dataTable .row .opration").removeClass("v");
			$(".dataTable .row .viewOpBox").hide();
		}
		else{
			$(this).addClass("v");
			$(".dataTable .row .opration").addClass("v");
			$(".dataTable .row .opration").each(function(){
				viewOp($(this).parent());
			});
			
			$(".dataTable .row .viewOpBox").show();
			var obj = $(this);
			$("body").one("click",function(){
				$(".dataTable a.A_opration").removeClass("v");
				$(".dataTable .row .viewOpBox").hide();
			});
			return false;
		}
	});
	
	
	$(".dataTable .row td input[name='key']").click(function(){
		if($(this).attr("checked")=="checked"||$(this).attr("checked")==true || $(this).attr("checked")=="true"){
			$(this).parent().parent().addClass("row_chk");
		}
		else{
			$(this).parent().parent().removeClass("row_chk");
		}
	});
	
	 $('.J_autoUserName').live('focus',function (event) {
	 	var obj = $(this);
	    obj.autocomplete(ROOT+"?m=Public&a=autoloaduser", {
			width: 260,
			selectFirst: false,
			autoFill: false,    //自动填充
			dataType: "json",
			extraParams:{
				user_type:function(){return (obj.attr("user_type")==undefined ? 0 : obj.attr("user_type"))}
			},
			parse: function(data) {
				
				return $.map(data, function(row) {
					return {
						data: row,
						value: row.user_name,
						result: function(){
							if (row.id > 0)
								return row.user_name;
							else
								return "";
						}
					}
				});
			},
			formatItem: function(row, i, max) {
				return row.user_name + (row.real_name =="" ? "" : " [" + row.real_name + "]");
			}
		}).result(function(e,item) {
			$('.J_autoUserId').val(item.id);
			return item.id;
		});
	  });
	bindKdedior();
	bindKdupload();
	bindFileUpload();
});
function viewOp(obj){
	var viewOx =  obj.find(".viewOpBox");

	var html = "";
	viewOx.find("a").each(function(){
		if($.trim($(this).html())==""){
			$(this).remove();
		}
	});
	
	var stop = obj.offset().top ;
	var sheight= obj.innerHeight() - 2;
	var lineheight = obj.outerHeight() - 2;
	
	viewOx.css({top:stop,height:sheight,"line-height":lineheight+"px"});
	viewOx.html(viewOx.html().replace(/^\s+|\s+$/g, ''));
}
//排序
function sortBy(field,sortType,module_name,action_name)
{
	location.href = CURRENT_URL+"&_sort="+sortType+"&_order="+field+"&";
}
//添加跳转
function add()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=add";
}
//编辑跳转
function edit(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id;
}

//跳转
function view(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=view&id="+id;
}
//添加跳转
function add_goods()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=shop_add";
}
//编辑跳转
function edit_goods(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=shop_edit&id="+id;
}

//添加跳转
function add_deal_youhui()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=youhui_add";
}
//编辑跳转
function edit_deal_youhui(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=youhui_edit&id="+id;
}

//全选
function CheckAll(tableID)
{
	$("#"+tableID).find(".key").attr("checked",$("#check").attr("checked"));
}

function toogle_status(id,domobj,field)
{
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=toogle_status&field="+field+"&id="+id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){

			if(obj.data=='1')
			{
				$(domobj).html(LANG['YES']);
			}
			else if(obj.data=='0')
			{
				$(domobj).html(LANG['NO']);
			}
			else if(obj.data=='')
			{
				
			}
			$("#info").html(obj.info);
		}
	});
}

//改变状态
function set_effect(id,domobj)
{
		$.ajax({ 
				url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_effect&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){

					if(obj.data=='1')
					{
						$(domobj).html(LANG['IS_EFFECT_1']);
					}
					else if(obj.data=='0')
					{
						$(domobj).html(LANG['IS_EFFECT_0']);
					}
					else if(obj.data=='')
					{
						
					}
					$("#info").html(obj.info);
					if(MODULE_NAME =='DealHouseCate')
					{
						location.reload();
					}
				}
		});
}



function set_sort(id,sort,domobj)
{
	$(domobj).html("<input type='text' value='"+sort+"' id='set_sort' class='require'  />");
	$("#set_sort").select();
	$("#set_sort").focus();
	$("#set_sort").bind("blur",function(){
		var newsort = $(this).val();
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_sort&id="+id+"&sort="+newsort, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				if(obj.status)
				{
					$(domobj).html(newsort);
				}
				else
				{
					$(domobj).html(sort);
				}
				$("#info").html(obj.info);

			}
	});
});
}

//普通删除
function del(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_DELETE']))
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
//完全删除
function foreverdel(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=foreverdelete&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}
//恢复
function restore(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['RESTORE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_RESTORE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=restore&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href = location.href;
			}
	});
}

//节点全选
function check_node(obj)
{
	$(obj.parentNode.parentNode.parentNode).find(".node_item").attr("checked",$(obj).attr("checked"));
}
function check_is_all(obj)
{
	if($(obj.parentNode.parentNode.parentNode).find(".node_item:checked").length!=$(obj.parentNode.parentNode.parentNode).find(".node_item").length)
	{
		$(obj.parentNode.parentNode.parentNode).find(".check_all").attr("checked",false);
	}
	else
		$(obj.parentNode.parentNode.parentNode).find(".check_all").attr("checked",true);
}
function check_module(obj)
{
	if($(obj).attr("checked"))
	{
		$(obj).parent().parent().find(".check_all").attr("disabled",true);
		$(obj).parent().parent().find(".node_item").attr("disabled",true);
	}
	else
	{
		$(obj).parent().parent().find(".check_all").attr("disabled",false);
		$(obj).parent().parent().find(".node_item").attr("disabled",false);	
	}
}


function export_csv()
{
	var inputs = $(".search_row").find("input");
	var selects = $(".search_row").find("select");
	var param = '';
	for(i=0;i<inputs.length;i++)
	{
		if(inputs[i].name!='m'&&inputs[i].name!='a')
		param += "&"+inputs[i].name+"="+$(inputs[i]).val();
	}
	for(i=0;i<selects.length;i++)
	{
		param += "&"+selects[i].name+"="+$(selects[i]).val();
	}
	var url= ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=export_csv";
	location.href = url+param;
}

function init_word_box()
{
	$(".word-only").bind("keydown",function(e){
		if(e.keyCode<65||e.keyCode>90)
		{
			if(e.keyCode != 8)
			return false;
		}
	});
}

function reset_sending(field)
{
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=reset_sending&field="+field, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			$("#info").html(obj.info);			
		}
	});
}

function search_supplier()
{
	var key = $("input[name='supplier_key']").val();
	if($.trim(key)=='')
	{
		alert(INPUT_KEY_PLEASE);
	}
	else
	{
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"=SupplierLocation&"+VAR_ACTION+"=search_supplier", 
			data: "ajax=1&key="+key,
			type: "POST",
			success: function(obj){
				$("#supplier_list").html(obj);
			}
		});
	}
}
userCard=(function(){	
	return {
		load : function(e,id){
	
				
			}
	  	};
})();


function load_balance(id)
{
	deal_id = $("input[name='hd_deal_id']").val();
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['CHECK_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");		
	}	
	
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"=Balance&"+VAR_ACTION+"=check_balance&deal_id="+deal_id+"&id="+id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				$.weeboxs.open(ROOT+'?m=Balance&a=load_balance&id='+id+"&deal_id="+deal_id, {contentType:'ajax',showButton:false,title:LANG['DO_BALANCE'],width:600,height:200});
			}
			else
			{
				alert(obj.info);
			}
		}
	});
	
	
}
function bindKdedior(){
	K = KindEditor;
    var editor = K.create('textarea.ketext', {
        allowFileManager : true,
        emoticonsPath:EMOT_URL,
        afterBlur: function(){this.sync();}, //兼容jq的提交，失去焦点时同步表单值
        height:300,
        items : [
			'source','fsource', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
			'plainpaste', 'wordpaste', 'justifyleft', 'justifycenter', 'justifyright',
			'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			'superscript', 'selectall','/',
			'title', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
			'italic', 'underline', 'strikethrough', 'removeformat', 'image',
			'table', 'hr', 'emoticons', 'link', 'unlink'
		]
    });   
	
}
function bindFileUpload(){
	if(K==null){
		K = KindEditor;
	}
	var editor = K.editor({
       allowFileManager : true
     });
	 K('.kefile').click(function() {
	 				  var node = K(this);
      				 var dom =$(node).parent();
 					editor.loadPlugin('insertfile', function() {
						editor.plugin.fileDialog({
							clickFn : function(url, title) {
								dom.find(".kefile_url").val(url);
 								//K('#url').val(url);
								editor.hideDialog();
							}
						});
					});
				});
}

function bindKdupload(){
	if(K==null){
		K = KindEditor;
	}
	var ieditor = K.editor({
       allowFileManager : true,
       imageSizeLimit:MAX_FILE_SIZE               
    });
 	K('.keimg').unbind("click");
    K('.keimg').click(function() {
        var node = K(this);
        var dom =$(node).parent().parent().parent().parent();
        ieditor.loadPlugin('image', function() {
               ieditor.plugin.imageDialog({
               // imageUrl : K("#keimg_h_"+$(this).attr("rel")).val(),
                imageUrl:dom.find("#keimg_h_"+node.attr("rel")).val(),
                clickFn : function(url, title, width, height, border, align) {       
                    dom.find("#keimg_a_"+node.attr("rel")).attr("href",url),
                    dom.find("#keimg_m_"+node.attr("rel")).attr("src",url),
                    dom.find("#keimg_h_"+node.attr("rel")).val(url),
					dom.find(".keimg_d[rel='"+node.attr("rel")+"']").show(),
                    ieditor.hideDialog();
                }
            });
        });
    });
 	/**
	 * 删除单图
	 */
	K('.keimg_d').unbind("click");
    K('.keimg_d').click(function() {
        var node = K(this);
		K(this).hide();
        var dom =$(node).parent().parent().parent().parent();
        dom.find("#keimg_a_"+node.attr("rel")).attr("href","");
        dom.find("#keimg_m_"+node.attr("rel")).attr("src",ROOT_PATH + "/admin/Tpl/default/Common/images/no_pic.gif");
        dom.find("#keimg_h_"+node.attr("rel")).val("");
    });
}

//编辑跳转
function edit_investor(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit_investor&id="+id;
}

(function(){  
	// 数据表格编辑事件
	$(".opration").live('click',function(e){
		e.stopPropagation();
		var $obj=$(this),
			$tr=$obj.parent().parent(),
		 	td_length=$tr.find("td").length,
			has_viewOpBox_m=$tr.next(".viewOpBox_m").length,
			viewOpBox_demo=$obj.parent().find(".viewOpBox_demo").html();
		if(has_viewOpBox_m){
			$(".viewOpBox_m").remove();
			$(".opration").removeClass("cur");
		}
		else{
			$tr.after('<tr class="row viewOpBox_m"><td colspan="'+td_length+'">'+viewOpBox_demo+'</td></tr>');
			$obj.addClass("cur");
			$("body").one("click",function(){
				$(".opration").removeClass("cur");
				$(".viewOpBox_m").remove();
			});
		}
	});
})();  
function dec_date(num){
	var today = new Date();
	today.setDate(today.getDate() - num);
	var d = today.getFullYear();
	if ((today.getMonth()+1) < 10)
		d = d + "-0" + (today.getMonth()+1);
	else
		d = d + "-" + (today.getMonth()+1);

	if (today.getDate() < 10)
		d = d + "-0" + today.getDate();
	else
		d = d + "-" + today.getDate();
	
	return d;
}
function IsDate(str){
	var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if(r==null)return false;
	var d= new Date(r[1], r[3]-1, r[4]);
	return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]);
} 

function IsTime(str){
	var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/);
	if(r==null)return false; 
	var d = new Date(r[1], r[3]-1,r[4],r[5],r[6],r[7]);
	return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]&&d.getHours()==r[5]&&d.getMinutes()==r[6]&&d.getSeconds()==r[7]);	
}


function dateCompare(date1,date2){
	date1 = date1.replace(/\-/gi,"/");
	date2 = date2.replace(/\-/gi,"/");
	var time1 = new Date(date1).getTime();
	var time2 = new Date(date2).getTime();
	if(time1 > time2){
		return 1;
	}else if(time1 == time2){
		return 2;
	}else{
		return 3;
	}
}

function GetDateDiff(date1,date2) 
{ 
	date1 = date1.replace(/\-/gi,"/");
	date2 = date2.replace(/\-/gi,"/");
	var time1 = new Date(date1).getTime();
	var time2 = new Date(date2).getTime();
	var dates = Math.abs((time1 - time2))/(1000*60*60*24); 
	return dates; 
}
	

function change_tag(obj,id){
		var group = $(obj).attr("g");
		var tags = $("."+group);
		tags.each(function(){$(this).hide();});
		$("."+group+"_"+id).show();
};