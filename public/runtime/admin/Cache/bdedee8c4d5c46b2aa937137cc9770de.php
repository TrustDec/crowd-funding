<?php if (!defined('THINK_PATH')) exit();?>

<?php function get_refund_user_name($uid)
	{
		return M("commit_log")->where("shop_id=".$uid)->getField("user_name");
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
<div class="main_title">转账记录</div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="删除" onclick="del();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		交易ID: <input type="text" class="textbox" name="shop_id" value="<?php echo trim($_REQUEST['shop_id']);?>" style="width:100px;" />	
		<input type="hidden" value="PaymentNotice" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="10" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px      "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','Transfernotice','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('shop_id','<?php echo ($sort); ?>','Transfernotice','index')" title="按照交易ID         <?php echo ($sortType); ?> ">交易ID         <?php if(($order)  ==  "shop_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('remark','<?php echo ($sort); ?>','Transfernotice','index')" title="按照交易单号   <?php echo ($sortType); ?> ">交易单号   <?php if(($order)  ==  "remark"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_money_hand','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账金额   <?php echo ($sortType); ?> ">转账金额   <?php if(($order)  ==  "one_money_hand"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_uname','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账人         <?php echo ($sortType); ?> ">转账人         <?php if(($order)  ==  "one_uname"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('two_uname','<?php echo ($sort); ?>','Transfernotice','index')" title="按照收款人   <?php echo ($sortType); ?> ">收款人   <?php if(($order)  ==  "two_uname"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_time','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账时间   <?php echo ($sortType); ?> ">转账时间   <?php if(($order)  ==  "one_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('remark','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账人备注<?php echo ($sortType); ?> ">转账人备注<?php if(($order)  ==  "remark"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list1): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($list1["id"]); ?>"></td><td><?php echo ($list1["id"]); ?></td><td><?php echo (shopId($list1["shop_id"])); ?></td><td><?php echo (get_refund_user_name($list1["remark"])); ?></td><td><?php echo (getMoney($list1["one_money_hand"])); ?></td><td><?php echo (nameOne($list1["one_uname"])); ?></td><td><?php echo (nameTwo($list1["two_uname"])); ?></td><td><?php echo (timeOne($list1["one_time"])); ?></td><td><?php echo (get_title($list1["remark"])); ?></td><td class="op_action"><div class="viewOpBox_demo"><a href="javascript:del('<?php echo ($list1["id"]); ?>')">删除</a>&nbsp; <?php echo (get_confirm($list1["id"],$deal)); ?>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="10" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 
    
        <!--<?php if(is_array($list)): foreach($list as $k=>$vo): ?><!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="10" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px      "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','Transfernotice','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('shop_id','<?php echo ($sort); ?>','Transfernotice','index')" title="按照交易ID         <?php echo ($sortType); ?> ">交易ID         <?php if(($order)  ==  "shop_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('remark','<?php echo ($sort); ?>','Transfernotice','index')" title="按照交易单号   <?php echo ($sortType); ?> ">交易单号   <?php if(($order)  ==  "remark"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_money_hand','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账金额   <?php echo ($sortType); ?> ">转账金额   <?php if(($order)  ==  "one_money_hand"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_uname','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账人         <?php echo ($sortType); ?> ">转账人         <?php if(($order)  ==  "one_uname"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('two_uname','<?php echo ($sort); ?>','Transfernotice','index')" title="按照收款人   <?php echo ($sortType); ?> ">收款人   <?php if(($order)  ==  "two_uname"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('one_time','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账时间   <?php echo ($sortType); ?> ">转账时间   <?php if(($order)  ==  "one_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('remark','<?php echo ($sort); ?>','Transfernotice','index')" title="按照转账人备注<?php echo ($sortType); ?> ">转账人备注<?php if(($order)  ==  "remark"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$deal): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($deal["id"]); ?>"></td><td><?php echo ($deal["id"]); ?></td><td><?php echo (shopId($deal["shop_id"])); ?></td><td><?php echo (get_refund_user_name($deal["remark"])); ?></td><td><?php echo (getMoney($deal["one_money_hand"])); ?></td><td><?php echo (nameOne($deal["one_uname"])); ?></td><td><?php echo (nameTwo($deal["two_uname"])); ?></td><td><?php echo (timeOne($deal["one_time"])); ?></td><td><?php echo (get_title($deal["remark"])); ?></td><td class="op_action"><div class="viewOpBox_demo"><a href="javascript:del('<?php echo ($deal["id"]); ?>')">删除</a>&nbsp; <?php echo (get_confirm($deal["id"],$deal)); ?>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="10" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 --><?php endforeach; endif; ?>-->
<!--,pay_time|to_date:确认转账时间-->
<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>