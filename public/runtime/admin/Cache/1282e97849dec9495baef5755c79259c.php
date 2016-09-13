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
<script type="text/javascript" src="__TMPL__Common/js/user.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.php?lang=zh-cn" ></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/js/calendar/calendar.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.js"></script>
<div class="main">
<div class="main_title"><?php echo ($main_title); ?></div>
<div class="blank5"></div>
<?php function get_level($level){
		$user_level = $GLOBALS['db']->getOne("select `name` from ".DB_PREFIX."user_level where id = '".intval($level)."'");
		return $user_level;
	} ?>
<div class="button_row">
	<input type="button" class="button" value="<?php echo L("ADD");?>" onclick="add();" />
	<input type="button" class="button" value="<?php echo L("DEL");?>" onclick="del();" />
</div>

<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		会员ID：<input type="text" class="textbox" name="id" value="<?php echo trim($_REQUEST['id']);?>" style="width:100px;" />
		会员名称：<input type="text" class="textbox" name="user_name" value="<?php echo trim($_REQUEST['user_name']);?>" style="width:100px;" />
		<?php echo L("USER_EMAIL");?>：<input type="text" class="textbox" name="email" value="<?php echo trim($_REQUEST['email']);?>" style="width:100px;" />
		手机号：<input type="text" class="textbox" name="mobile" value="<?php echo trim($_REQUEST['mobile']);?>" style="width:100px;" />
 		状态：
		<select name="is_effect">
			<option value="NULL" <?php if($_REQUEST['is_effect'] == 'NULL' ): ?>selected="selected"<?php endif; ?>>请选择</option>
			<option value="1"  <?php if($_REQUEST['is_effect'] == '1' ): ?>selected="selected"<?php endif; ?> >有效</option>
			<option value="0"  <?php if($_REQUEST['is_effect'] == '0' ): ?>selected="selected"<?php endif; ?>>无效</option>
		</select>
		<div class="blank10"></div>
		注册时间：<input type="text" class="textbox" name="create_time_1" id="create_time_1" value="<?php echo ($_REQUEST['create_time_1']); ?>" onfocus="this.blur(); return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />
			   <input type="button" class="button" id="btn_create_time_1" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />&nbsp;至&nbsp;<input type="text" class="textbox" name="create_time_2" id="create_time_2" value="<?php echo ($_REQUEST['create_time_2']); ?>" onfocus="this.blur(); return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />
		<input type="button" class="button" id="btn_create_time_2" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />	
		投资者类型：
		<select name="is_investor">
			<option value="NULL" <?php if($_REQUEST['is_investor'] == 'NULL' ): ?>selected="selected"<?php endif; ?>>请选择</option>
			<option value="0"  <?php if($_REQUEST['is_investor'] == '0' ): ?>selected="selected"<?php endif; ?> >普通用户</option>
			<option value="1"  <?php if($_REQUEST['is_investor'] == '1' ): ?>selected="selected"<?php endif; ?>>投资者</option>
			<option value="2"  <?php if($_REQUEST['is_investor'] == '2' ): ?>selected="selected"<?php endif; ?>>机构投资者</option>
		</select>
		
		<input type="hidden" value="User" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="18" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="40px    "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('user_name','<?php echo ($sort); ?>','User','index')" title="按照会员名称<?php echo ($sortType); ?> ">会员名称<?php if(($order)  ==  "user_name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('email','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("USER_EMAIL");?>    <?php echo ($sortType); ?> "><?php echo L("USER_EMAIL");?>    <?php if(($order)  ==  "email"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="70px    "><a href="javascript:sortBy('mobile','<?php echo ($sort); ?>','User','index')" title="按照手机号<?php echo ($sortType); ?> ">手机号<?php if(($order)  ==  "mobile"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('money','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("USER_MONEY");?>    <?php echo ($sortType); ?> "><?php echo L("USER_MONEY");?>    <?php if(($order)  ==  "money"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('score','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("USER_SCORE");?>    <?php echo ($sortType); ?> "><?php echo L("USER_SCORE");?>    <?php if(($order)  ==  "score"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('point','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("USER_POINT");?>    <?php echo ($sortType); ?> "><?php echo L("USER_POINT");?>    <?php if(($order)  ==  "point"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="80px    "><a href="javascript:sortBy('login_ip','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("LOGIN_IP");?><?php echo ($sortType); ?> "><?php echo L("LOGIN_IP");?><?php if(($order)  ==  "login_ip"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('source_url','<?php echo ($sort); ?>','User','index')" title="按照来源    <?php echo ($sortType); ?> ">来源    <?php if(($order)  ==  "source_url"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="120px    "><a href="javascript:sortBy('login_time','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("LOGIN_TIME");?><?php echo ($sortType); ?> "><?php echo L("LOGIN_TIME");?><?php if(($order)  ==  "login_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('build_count','<?php echo ($sort); ?>','User','index')" title="按照项目数    <?php echo ($sortType); ?> ">项目数    <?php if(($order)  ==  "build_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('support_count','<?php echo ($sort); ?>','User','index')" title="按照支持数    <?php echo ($sortType); ?> ">支持数    <?php if(($order)  ==  "support_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('focus_count','<?php echo ($sort); ?>','User','index')" title="按照关注数    <?php echo ($sortType); ?> ">关注数    <?php if(($order)  ==  "focus_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px    "><a href="javascript:sortBy('wx_openid','<?php echo ($sort); ?>','User','index')" title="按照wx_openid<?php echo ($sortType); ?> ">wx_openid<?php if(($order)  ==  "wx_openid"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px    "><a href="javascript:sortBy('user_level','<?php echo ($sort); ?>','User','index')" title="按照会员等级<?php echo ($sortType); ?> ">会员等级<?php if(($order)  ==  "user_level"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="30px"><a href="javascript:sortBy('is_effect','<?php echo ($sort); ?>','User','index')" title="按照<?php echo L("IS_EFFECT");?><?php echo ($sortType); ?> "><?php echo L("IS_EFFECT");?><?php if(($order)  ==  "is_effect"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($user["id"]); ?>"></td><td><?php echo ($user["id"]); ?></td><td><a href="javascript:edit    ('<?php echo (addslashes($user["id"])); ?>')"><?php echo ($user["user_name"]); ?></a></td><td><?php echo ($user["email"]); ?></td><td><?php echo ($user["mobile"]); ?></td><td><?php echo (format_price($user["money"])); ?></td><td><?php echo ($user["score"]); ?></td><td><?php echo ($user["point"]); ?></td><td><?php echo ($user["login_ip"]); ?></td><td><?php echo ($user["source_url"]); ?></td><td><?php echo (to_date($user["login_time"])); ?></td><td><?php echo ($user["build_count"]); ?></td><td><?php echo ($user["support_count"]); ?></td><td><?php echo ($user["focus_count"]); ?></td><td><?php echo ($user["wx_openid"]); ?></td><td><?php echo (get_level($user["user_level"],$user['user_level'])); ?></td><td><?php echo (get_is_effect($user["is_effect"],$user['id'])); ?></td><td class="op_action"><div class="viewOpBox_demo"><a href="javascript:edit('<?php echo ($user["id"]); ?>')"><?php echo L("EDIT");?></a>&nbsp;<a href="javascript: del('<?php echo ($user["id"]); ?>')"><?php echo L("DEL");?></a>&nbsp;<a href="javascript: account('<?php echo ($user["id"]); ?>')"><?php echo L("USER_ACCOUNT");?></a>&nbsp;<a href="javascript:account_detail('<?php echo ($user["id"]); ?>')"><?php echo L("USER_ACCOUNT_DETAIL");?></a>&nbsp;<a href="javascript:consignee('<?php echo ($user["id"]); ?>')"><?php echo L("USER_CONSIGNEE_INDEX");?></a>&nbsp;<a href="javascript:weibo('<?php echo ($user["id"]); ?>')"><?php echo L("USER_WEIBO_INDEX");?></a>&nbsp;<a href="javascript:user_bank('<?php echo ($user["id"]); ?>')"><?php echo L("USER_BANK_INDEX");?></a>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="18" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->


<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>