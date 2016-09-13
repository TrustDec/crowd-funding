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

<?php function get_collocation_effect($effect)
	{
		return l("IS_EFFECT_".$effect);
	}
	function get_collocation_install($install)
	{
		return l("IS_INSTALL_".$install);
	}
	function get_collocation_online($online)
	{
		return l("ONLINE_PAY_".$online);
	} ?>
<script type="text/javascript">
	function uninstall(id)
	{
		if(confirm("<?php echo L("CONFIRM_DELETE");?>"))
		{
			location.href = ROOT + "?m=Collocation&a=uninstall&id="+id;
		}
	}
</script>
<div class="main">
<div class="main_title">资金托管[注：状态同时只能一种有效,系统确定完后,不可再更改资金托管方式; 除非重新安装系统,请慎重]</div>
<div class="blank5"></div>

<table cellspacing="0" cellpadding="0" class="dataTable" id="dataTable">
	<tbody>
		<tr>
			<td class="topTd" colspan="7">&nbsp; </td>
			</tr>
			<tr class="row">
				<th><?php echo L("PAYMENT_NAME");?></th>
				<th><?php echo L("IS_EFFECT");?></th>
				<th><?php echo L("IS_INSTALL");?></th>
				<th><?php echo L("TAG_LANG_OPERATE");?></th>
				</tr>
				<?php if(is_array($collocation_list)): foreach($collocation_list as $key=>$collocation_item): ?><?php if($collocation_item["name"] != ''): ?><tr class="row">
					<td><?php echo ($collocation_item["name"]); ?>					
					</td>
					<td><?php echo (get_collocation_effect($collocation_item["is_effect"])); ?></td>
					<td><?php echo (get_collocation_install($collocation_item["installed"])); ?></td>
					</td>
					<td>
						<?php if($collocation_item['installed'] == 0): ?><a href="<?php echo u("Collocation/install",array("class_name"=>$collocation_item['class_name']));?>"><?php echo L("INSTALL");?></a>
						<?php else: ?>
						<a href="<?php echo u("Collocation/edit",array("id"=>$collocation_item['id']));?>"><?php echo L("EDIT");?></a>
						<a href="javascript:uninstall(<?php echo ($collocation_item["id"]); ?>);" ><?php echo L("UNINSTALL");?></a><?php endif; ?>
					</td>
				</tr><?php endif; ?><?php endforeach; endif; ?>
				<tr><td class="bottomTd" colspan="7"> &nbsp;</td></tr>
			</tbody>
		</table>


</div>
</body>
</html>