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

<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.php?lang=zh-cn" ></script>
<script type="text/javascript">
	
	function total_info(){
		location.href = ROOT + '?m='+MODULE_NAME+'&a=project_info';
	}
</script>
<script type="text/javascript">	
	function export_csv_project()
	{
		var query = $("#search_form").serialize();
		query = query.replace("&m="+MODULE_NAME+","");
		query = query.replace("&a=project","");
		var url= ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=export_csv_project"+"&"+query;
		location.href = url;
	}
	
</script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/js/calendar/calendar.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.js"></script>
<div class="main">
<div class="main_title">项目统计</div>
<div class="blank5"></div>
	
	
	<form name="search" id = "search_form"  action="__APP__" method="get">	
		
		按时间查询：
		<input type="text" class="textbox" name="begin_time" id="begin_time" value="<?php echo trim($_REQUEST['begin_time']);?>" onfocus="return showCalendar('begin_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_begin_time');" style="width:130px" />
		<input type="button" class="button" id="btn_begin_time" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('begin_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_begin_time');" />	
		-
		<input type="text" class="textbox" name="end_time" id="end_time" value="<?php echo trim($_REQUEST['end_time']);?>" onfocus="return showCalendar('end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');" style="width:130px" />
		<input type="button" class="button" id="btn_end_time" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');" />
		
		<input type="hidden" value="StatisticsProject" name="m" />
		<input type="hidden" value="project" name="a" />
		
		<input type="submit" class="button" value="搜索" />
		<input type="button" class="button" value="<?php echo L("EXPORT");?>" onclick="export_csv_project();" />
		<input type="button" class="button" value="查看项目" onclick="total_info()" />	
	</form>
	
	
<div class="blank5"></div>

<div class="blank5"></div>
	
	
	
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="11" class="topTd" ></td></tr><tr class="row" ><th><a href="javascript:sortBy('支持人数','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照支持人数   <?php echo ($sortType); ?> ">支持人数   <?php if(($order)  ==  "支持人数"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('筹款总额','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照筹款总额   <?php echo ($sortType); ?> ">筹款总额   <?php if(($order)  ==  "筹款总额"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('成功筹款','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照成功筹款   <?php echo ($sortType); ?> ">成功筹款   <?php if(($order)  ==  "成功筹款"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('失败筹款','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照失败筹款   <?php echo ($sortType); ?> ">失败筹款   <?php if(($order)  ==  "失败筹款"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('项目总数','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照项目总数   <?php echo ($sortType); ?> ">项目总数   <?php if(($order)  ==  "项目总数"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('成功项目数','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照成功项目数   <?php echo ($sortType); ?> ">成功项目数   <?php if(($order)  ==  "成功项目数"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('失败项目数','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照失败项目数   <?php echo ($sortType); ?> ">失败项目数   <?php if(($order)  ==  "失败项目数"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('进行中项目数','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照进行中项目数   <?php echo ($sortType); ?> ">进行中项目数   <?php if(($order)  ==  "进行中项目数"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('已发放筹款','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照已发放筹款   <?php echo ($sortType); ?> ">已发放筹款   <?php if(($order)  ==  "已发放筹款"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('待发放筹款','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照待发放筹款   <?php echo ($sortType); ?> ">待发放筹款   <?php if(($order)  ==  "待发放筹款"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('可获得佣金','<?php echo ($sort); ?>','StatisticsProject','project')" title="按照可获得佣金   <?php echo ($sortType); ?> ">可获得佣金   <?php if(($order)  ==  "可获得佣金"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$deal): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><?php echo ($deal["支持人数"]); ?></td><td><?php echo (format_price($deal["筹款总额"])); ?></td><td><?php echo (format_price($deal["成功筹款"])); ?></td><td><?php echo (format_price($deal["失败筹款"])); ?></td><td><?php echo ($deal["项目总数"]); ?></td><td><?php echo ($deal["成功项目数"]); ?></td><td><?php echo ($deal["失败项目数"]); ?></td><td><?php echo ($deal["进行中项目数"]); ?></td><td><?php echo (format_price($deal["已发放筹款"])); ?></td><td><?php echo (format_price($deal["待发放筹款"])); ?></td><td><?php echo (format_price($deal["可获得佣金"])); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="11" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 
					
	
<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>

</div>

</body>
</html>