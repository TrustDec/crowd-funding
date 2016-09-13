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

<script type="text/javascript" src="__TMPL__Common/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.weebox.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/deal.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.php?lang=zh-cn" ></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/js/calendar/calendar.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.js"></script>

<?php function get_edit($s,$deal){
	if($deal['is_effect'] == 2){
		return "未通过";
	}
	elseif($deal['is_edit']==0)
		return "待审核";
	else
		return "未提交";	
}
function get_edit_1($id,$deal){
 		if($deal['type']==1 || $deal['type']==4){
			return "<a href=\"javascript:edit_investor_index('".$id."')\">编辑上架</a>";
		}
		else{
			return "<a href=\"javascript:edit_index('".$id."')\">编辑上架</a>";
		}
	}
function get_item($id,$deal){
 		if($deal['type']==1 || $deal['type']==4){
			return "";
		}
		else{
			return "<a href=\"javascript:deal_item('".$id."')\">子项目</a>";
		}
	}
function edit_new($name,$id){
		$deal=$GLOBALS['db']->getOne("select type from ".DB_PREFIX."deal where id=$id ");
 		if($deal['type']==1 || $deal['type']==4){
			return "<a href=\"javascript:edit_investor('".$id."')\">$name</a>";
		}
		else{
			return "<a href=\"javascript:edit('".$id."')\">$name</a>";
		}
	} ?>
<script>
	//编辑跳转
 
function edit_index(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id;
}
function edit_investor_index(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit_investor&id="+id;
}
</script>
<div class="main">
<div class="main_title">未审核项目</div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="彻底删除" onclick="foreverdel();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		项目名称：<input type="text" class="textbox" name="name" value="<?php echo trim($_REQUEST['name']);?>" style="width:100px;" />
		分类:<select name="cate_id">
				<option value="0" <?php if($_REQUEST['time_status'] == 0): ?>selected="selected"<?php endif; ?>>全部</option>
				<?php if(is_array($cate_list)): foreach($cate_list as $key=>$cate_item): ?><option value="<?php echo ($cate_item["id"]); ?>" <?php if($_REQUEST['cate_id'] == $cate_item['id']): ?>selected="selected"<?php endif; ?>><?php echo ($cate_item["name"]); ?></option><?php endforeach; endif; ?>
			</select>
		支付类型:<select name="ips_bill_no">
			<option value="NULL" <?php if($_REQUEST['ips_bill_no'] == 'NULL'): ?>selected="selected"<?php endif; ?> >请选择</option>
			<option value="0" <?php if($_REQUEST['ips_bill_no'] == '0'): ?>selected="selected"<?php endif; ?> >网站支付</option>
			<option value="1" <?php if($_REQUEST['ips_bill_no'] == '1'): ?>selected="selected"<?php endif; ?> >第三方托管</option>
			</select>
		发起人ID: <input type="text" class="textbox" name="user_id" value="<?php echo trim($_REQUEST['user_id']);?>" style="width:30px;" />
		<div class="blank10"></div>
		创建时间：<input type="text" class="textbox" name="create_time_1" id="create_time_1" value="<?php echo ($_REQUEST['create_time_1']); ?>" onfocus="this.blur(); return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />
			   <input type="button" class="button" id="btn_create_time_1" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />	
		至 <input type="text" class="textbox" name="create_time_2" id="create_time_2" value="<?php echo ($_REQUEST['create_time_2']); ?>" onfocus="this.blur(); return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />
		<input type="button" class="button" id="btn_create_time_2" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />	
		
		<input type="hidden" value="DealInvestorSubmit" name="m" />
		<input type="hidden" value="submit_index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="12" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px   "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('name','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照项目名称    <?php echo ($sortType); ?> ">项目名称    <?php if(($order)  ==  "name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="100px   "><a href="javascript:sortBy('type','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照类型<?php echo ($sortType); ?> ">类型<?php if(($order)  ==  "type"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="100px   "><a href="javascript:sortBy('ips_bill_no','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照支付方式<?php echo ($sortType); ?> ">支付方式<?php if(($order)  ==  "ips_bill_no"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px   "><a href="javascript:sortBy('user_id','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照发起人<?php echo ($sortType); ?> ">发起人<?php if(($order)  ==  "user_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="100px   "><a href="javascript:sortBy('limit_price','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照目标金额<?php echo ($sortType); ?> ">目标金额<?php if(($order)  ==  "limit_price"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('deal_days','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照参考上线时间   <?php echo ($sortType); ?> ">参考上线时间   <?php if(($order)  ==  "deal_days"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照创建时间   <?php echo ($sortType); ?> ">创建时间   <?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_edit','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照状态   <?php echo ($sortType); ?> ">状态   <?php if(($order)  ==  "is_edit"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_effect','<?php echo ($sort); ?>','DealInvestorSubmit','submit_index')" title="按照上架<?php echo ($sortType); ?> ">上架<?php if(($order)  ==  "is_effect"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$deal): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($deal["id"]); ?>"></td><td><?php echo ($deal["id"]); ?></td><td><?php echo (edit_new($deal["name"],$deal['id'])); ?></td><td><?php echo (get_type_name($deal["type"])); ?></td><td><?php echo (is_ips_bill_no_admin($deal["ips_bill_no"])); ?></td><td><?php echo (get_deal_user($deal["user_id"])); ?></td><td><?php echo (format_price($deal["limit_price"])); ?></td><td><?php echo ($deal["deal_days"]); ?></td><td><?php echo (to_date($deal["create_time"])); ?></td><td><?php echo (get_edit($deal["is_edit"],$deal)); ?></td><td><?php echo (get_status($deal["is_effect"])); ?></td><td class="op_action"><div class="viewOpBox_demo"> <?php echo (get_item($deal["id"],$deal)); ?>&nbsp; <?php echo (get_edit_1($deal["id"],$deal)); ?>&nbsp;<a href="javascript: foreverdel('<?php echo ($deal["id"]); ?>')">彻底删除</a>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="12" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 

<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>