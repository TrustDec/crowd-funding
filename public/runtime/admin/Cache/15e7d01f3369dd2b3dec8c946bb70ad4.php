<?php if (!defined('THINK_PATH')) exit();?>

<?php function get_payment_user_name($uid)
	{
		return M("User")->where("id=".$uid)->getField("user_name");
	}
	function get_pay_type($pay_type){
 		if($pay_type==1){
			return "网站余额支付";
		}else{
			return "第三方托管";
		}
	}
	function get_moneyfreeze_status($status){
 		if($status==1){
			return "冻结";
		}elseif($status==2){
			return "解冻";
		}else{
			return "申请解冻";
		}
	} ?>
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

<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<div class="main">
<div class="main_title">诚意金记录</div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="删除" onclick="del();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		类型:
		<select name="status">	
			<option value="1" <?php if($_REQUEST['status'] == 1): ?>selected="selected"<?php endif; ?> >冻结</option>
			<option value="2" <?php if($_REQUEST['status'] == 2): ?>selected="selected"<?php endif; ?> >解冻</option>
			<option value="3" <?php if($_REQUEST['status'] == 3): ?>selected="selected"<?php endif; ?> >申请解冻</option>
		</select>	
		<input type="hidden" value="MoneyFreeze" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="10" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px   "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('deal_id','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照项目名称   <?php echo ($sortType); ?> ">项目名称   <?php if(($order)  ==  "deal_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('platformUserNo','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照会员名   <?php echo ($sortType); ?> ">会员名   <?php if(($order)  ==  "platformUserNo"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('requestNo','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照商户诚意金订单号   <?php echo ($sortType); ?> ">商户诚意金订单号   <?php if(($order)  ==  "requestNo"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('status','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照类型   <?php echo ($sortType); ?> ">类型   <?php if(($order)  ==  "status"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照完成时间   <?php echo ($sortType); ?> ">完成时间   <?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('amount','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照操作金额   <?php echo ($sortType); ?> ">操作金额   <?php if(($order)  ==  "amount"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('pay_type','<?php echo ($sort); ?>','MoneyFreeze','index')" title="按照支付类型<?php echo ($sortType); ?> ">支付类型<?php if(($order)  ==  "pay_type"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$moneyfreeze): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($moneyfreeze["id"]); ?>"></td><td><?php echo ($moneyfreeze["id"]); ?></td><td><?php echo (get_deal_name($moneyfreeze["deal_id"])); ?></td><td><?php echo (get_payment_user_name($moneyfreeze["platformUserNo"])); ?></td><td><?php echo ($moneyfreeze["requestNo"]); ?></td><td><?php echo (get_moneyfreeze_status($moneyfreeze["status"])); ?></td><td><?php echo (to_date($moneyfreeze["create_time"])); ?></td><td><?php echo ($moneyfreeze["amount"]); ?></td><td><?php echo (get_pay_type($moneyfreeze["pay_type"])); ?></td><td class="op_action"><a href="javascript:del('<?php echo ($moneyfreeze["id"]); ?>')">删除</a>&nbsp;</td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="10" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 

<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>