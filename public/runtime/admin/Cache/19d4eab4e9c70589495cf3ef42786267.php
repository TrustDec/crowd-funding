<?php if (!defined('THINK_PATH')) exit();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<script type="text/javascript" src="__TMPL__Common/js/check_dog.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/IA300ClientJavascript.js"></script>
<script type="text/javascript">
	var ACTION_ID ='<?php echo $action_id ?>';
 	var VAR_MODULE = "<?php echo conf("VAR_MODULE");?>";
	var VAR_ACTION = "<?php echo conf("VAR_ACTION");?>";
	var MODULE_NAME	=	'<?php echo MODULE_NAME; ?>';
	var ACTION_NAME	=	'<?php echo ACTION_NAME; ?>';
	var ROOT = '__APP__';
	var ROOT_PATH = '<?php echo APP_ROOT; ?>';
	var CURRENT_URL = '<?php echo trim($_SERVER['REQUEST_URI']);?>';
	var INPUT_KEY_PLEASE = "<?php echo L("INPUT_KEY_PLEASE");?>";
	var TMPL = '__TMPL__';
	var APP_ROOT = '<?php echo APP_ROOT; ?>';
	var LOGINOUT_URL = '<?php echo u("Public/do_loginout");?>';
	var WEB_SESSION_ID = '<?php echo es_session::id(); ?>';
	var EMOT_URL = '<?php echo APP_ROOT; ?>/public/emoticons/';
	var MAX_FILE_SIZE = "<?php echo (app_conf("MAX_IMAGE_SIZE")/1000000)."MB"; ?>";
	var FILE_UPLOAD_URL ='<?php echo u("File/do_upload");?>' ;
	CHECK_DOG_HASH = '<?php $adm_session = es_session::get(md5(conf("AUTH_KEY"))); echo $adm_session["adm_dog_key"]; ?>';
	function check_dog_sender_fun()
	{
		window.clearInterval(check_dog_sender);
		check_dog2();
	}
	var check_dog_sender = window.setInterval("check_dog_sender_fun()",5000);
	
</script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.timer.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/script.js"></script>
<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
<script type='text/javascript'  src='__ROOT__/admin/public/kindeditor/kindeditor.js'></script>
<script type='text/javascript'  src='__ROOT__/admin/public/kindeditor/lang/zh_CN.js'></script>
</head>
<body onLoad="javascript:DogPageLoad();">
<div id="info"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$("select[name='name']").bind("change",function(){
			load_tpl($("select[name='name']").val());
		});
		load_tpl($("select[name='name']").val());
		
		$(".template_btn").bind('click',function(){
			var type=parseInt($(this).attr("rel"));
			$(".template_btn").removeClass("currentbtn");
			$(this).addClass("currentbtn");
			$.ajax({
				url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=ajax_tpl&type="+type,
				data:"ajax=1",
				dataType: "json",
				success:function(obj){
					if(obj.status==1)
					{
						var tpl_list=obj.data.list;
						var type=obj.data.type;
						var tpl_html='<option value="">'+LANG['SELECT_MSG_TPL']+'</option>';
						for(i=0;i<tpl_list.length;i++)
						{
							tpl_html+='<option value="'+tpl_list[i]['name']+'">'+LANG['LANG_'+tpl_list[i]['name']+'']+'</option>';
						}
						$("select[name='name']").html(tpl_html);
						
						if(type == 1)
						{
							$("#html_row").show();
							$("select[name='is_html']").val(1);	
						}
						else
						{
							hide_html_row();
						}
						
						$("textarea[name='content']").val('');
						$("input[name='id']").val(0);
						
						$("#content_tip").find("td").html('');
						$("#content_tip").hide();
						
					}
				}
			});
		});
		
		
		$("#sub_button").bind("click",function(){
			var query=new Object();
			query.m='MsgTemplate';
			query.a='update';
			query.id=$("#template_form").find("input[name='id']").val();
			query.name=$("#template_form").find("select[name='name']").val();
			query.content=$("#template_form").find("textarea[name='content']").val();
			query.is_html=$("#template_form").find("select[name='is_html']").val();
			query.ajax=1;
			if(query.name =='' || query.id <=0)
				alert(LANG("SELECT_MSG_TPL"));
				
			$.ajax({
				url:ROOT,
				data:query,
				dataType: "json",
				type:'POST',
				success:function(obj){		
					if(obj.status==0){
						alert(obj.info);
					}		
				}
			});
		});
		
	});
	
	
	function load_tpl(tpl_name)
	{
		if(tpl_name != '')
		{
			$.ajax({ 
					url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=load_tpl&name="+tpl_name, 
					data: "ajax=1",
					dataType: "json",
					success: function(obj){						
						if(obj.status==1)
						{
							var tpl = obj.data;
							if(tpl.type == 1)
							{
								$("#html_row").show();
								$("select[name='is_html']").val(tpl.is_html);	
							}
							else
							{
								hide_html_row();
							}
							$("textarea[name='content']").val(tpl.content);
							$("input[name='id']").val(tpl.id);
							if(tpl.tip)
							{
								$("#content_tip").find("td").html(tpl.tip);
								$("#content_tip").show();
							}
							
						}
						else
						{
							$("textarea[name='content']").val('');
							$("input[name='id']").val(0);
							hide_html_row();
						}
					}
			});
		}
		else
		{
			$("textarea[name='content']").val('');
			$("input[name='id']").val(0);
			$("#content_tip").hide();
			hide_html_row();
		}
	}
	function hide_html_row()
	{
		$("#html_row").hide();
		$("select[name='is_html']").val(0);		
		$("#content_tip").hide();
	}
</script>
<div class="main">
<div class="main_title"><?php echo ($main_title); ?></div>
<div class="blank5"></div>
<div class="button_row">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" class="button template_btn currentbtn" rel="0" value="短信模板" />&nbsp;
	<input type="button" class="button template_btn"  rel="1" value="邮件模板" />&nbsp;
</div>
<div class="blank5"></div>
<form name="edit" action="__APP__" id="template_form" method="post" enctype="multipart/form-data">
<table class="form" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=2 class="topTd"></td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("MSG_TPL_NAME");?>:</td>
		<td class="item_input">
			<select name="name">
				<option value=""><?php echo L("SELECT_MSG_TPL");?></option>
				<?php if(is_array($tpl_list)): foreach($tpl_list as $key=>$tpl_item): ?><option value="<?php echo ($tpl_item["name"]); ?>"><?php echo l("LANG_".$tpl_item['name']);?></option><?php endforeach; endif; ?>
			</select>
		</td>
	</tr>
	<tr id="html_row">
		<td class="item_title"><?php echo L("IS_HTML");?>:</td>
		<td class="item_input">
			<select name="is_html">
				<option value="0"><?php echo L("IS_HTML_0");?></option>
				<option value="1"><?php echo L("IS_HTML_1");?></option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="item_title"><?php echo L("CONTENT");?>:</td>
		<td class="item_input">
			<textarea class="textarea" name="content" style="width:600px; height:250px;" ></textarea>
		</td>
	</tr>
	<tr id="content_tip">
		<td colspan="2">
			
		</td>
	</tr>
	<tr>
		<td class="item_title"></td>
		<td class="item_input">
			<!--隐藏元素-->
			<input type="hidden" value="0" name="id" />
			<input type="hidden" name="<?php echo conf("VAR_MODULE");?>" value="MsgTemplate" />
			<input type="hidden" name="<?php echo conf("VAR_ACTION");?>" value="update" />
			<!--隐藏元素-->
			<input type="button" class="button"  id="sub_button" value="<?php echo L("EDIT");?>" />
			<input type="reset" class="button" value="<?php echo L("RESET");?>" />
		</td>
	</tr>
	<tr>
		<td colspan=2 class="bottomTd"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>