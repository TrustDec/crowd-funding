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

<?php function get_max_bought($max_bought){
		$max_bought=intval($max_bought);
		if($max_bought>0)
			return $max_bought;
		else
			return "不限";
	} ?>
<div class="main">
<div class="main_title"><?php echo ($main_title); ?></div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="<?php echo L("ADD");?>" onclick="add();" />
	<input type="button" class="button" value="<?php echo L("DEL");?>" onclick="del();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">
		商品名称：
		<input type="text" class="textbox" name="name"  value="<?php echo ($param["name"]); ?>" style="width:160px" />&nbsp;
		分类：
		<select name="cate_id">
			<option value="0" <?php if($param['cate_id'] == 0): ?>selected="selected"<?php endif; ?> >选择分类</option>
			<?php if(is_array($cate_tree)): foreach($cate_tree as $key=>$cate): ?><option value="<?php echo ($cate["id"]); ?>" <?php if($param['cate_id'] == $cate['id']): ?>selected="selected"<?php endif; ?> ><?php echo ($cate["title_show"]); ?></option><?php endforeach; endif; ?>
		</select>&nbsp;
		
		<input type="hidden" value="Goods" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
		</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="14" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px   "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','Goods','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('name','<?php echo ($sort); ?>','Goods','index')" title="按照名称   <?php echo ($sortType); ?> ">名称   <?php if(($order)  ==  "name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('cate_name','<?php echo ($sort); ?>','Goods','index')" title="按照分类   <?php echo ($sortType); ?> ">分类   <?php if(($order)  ==  "cate_name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('sort','<?php echo ($sort); ?>','Goods','index')" title="按照排序   <?php echo ($sortType); ?> ">排序   <?php if(($order)  ==  "sort"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('max_bought','<?php echo ($sort); ?>','Goods','index')" title="按照库存数   <?php echo ($sortType); ?> ">库存数   <?php if(($order)  ==  "max_bought"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('buy_number','<?php echo ($sort); ?>','Goods','index')" title="按照购买数   <?php echo ($sortType); ?> ">购买数   <?php if(($order)  ==  "buy_number"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('score','<?php echo ($sort); ?>','Goods','index')" title="按照购买所需积分   <?php echo ($sortType); ?> ">购买所需积分   <?php if(($order)  ==  "score"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_delivery_format','<?php echo ($sort); ?>','Goods','index')" title="按照是否配送   <?php echo ($sortType); ?> ">是否配送   <?php if(($order)  ==  "is_delivery_format"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_hot_format','<?php echo ($sort); ?>','Goods','index')" title="按照热卖   <?php echo ($sortType); ?> ">热卖   <?php if(($order)  ==  "is_hot_format"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_new_format','<?php echo ($sort); ?>','Goods','index')" title="按照新品   <?php echo ($sortType); ?> ">新品   <?php if(($order)  ==  "is_new_format"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_recommend_format','<?php echo ($sort); ?>','Goods','index')" title="按照是否推荐   <?php echo ($sortType); ?> ">是否推荐   <?php if(($order)  ==  "is_recommend_format"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_effect','<?php echo ($sort); ?>','Goods','index')" title="按照<?php echo L("IS_EFFECT");?><?php echo ($sortType); ?> "><?php echo L("IS_EFFECT");?><?php if(($order)  ==  "is_effect"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($data["id"]); ?>"></td><td><?php echo ($data["id"]); ?></td><td><?php echo ($data["name"]); ?></td><td><?php echo ($data["cate_name"]); ?></td><td><?php echo ($data["sort"]); ?></td><td><?php echo (get_max_bought($data["max_bought"])); ?></td><td><?php echo ($data["buy_number"]); ?></td><td><?php echo ($data["score"]); ?></td><td><?php echo ($data["is_delivery_format"]); ?></td><td><?php echo ($data["is_hot_format"]); ?></td><td><?php echo ($data["is_new_format"]); ?></td><td><?php echo ($data["is_recommend_format"]); ?></td><td><?php echo (get_is_effect($data["is_effect"],$data['id'])); ?></td><td class="op_action"><div class="viewOpBox_demo"><a href="javascript:edit('<?php echo ($data["id"]); ?>')"><?php echo L("EDIT");?></a>&nbsp;<a href="javascript: del('<?php echo ($data["id"]); ?>')"><?php echo L("DEL");?></a>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="14" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->


<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>