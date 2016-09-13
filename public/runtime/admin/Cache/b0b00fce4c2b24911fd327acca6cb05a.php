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

<?php function get_refund($id,$deal)
	{
		if($deal['is_success']==0&&$deal['end_time']<get_gmtime()&&$deal['end_time']!=0)
		{
			if($deal['ips_bill_no'] != ""){
				return '<a href="__ROOT__/index.php?ctl=collocation&act=Transfer&pTransferType=2&deal_id='.$deal['id'].'&ref_data=" target="_blank">批量退款</a>';
				
			}else{
				return "<a href='javascript:void(0);' onclick=\"if(confirm('是否确认退款')){window.location.href='".u("DealInvestorOnline/batch_refund",array("id"=>$id))."';} \" >批量退款</a>";
			}
 			
		}
	}
	function get_edit($id,$deal){
 		if($deal['type']==1 || $deal['type']==4){
			return "<a href=\"javascript:edit_investor_index('".$id."')\">编辑</a>";
		}
		else{
			return "<a href=\"javascript:edit_index('".$id."')\">编辑</a>";
		}
	}
	function get_pay_list($id,$deal){
		if($deal['type']==1 || $deal['type']==4){
			return "<a href=\"javascript:get_pay_list('".$id."')\">投资列表</a>";
		}else{
			return "<a href=\"javascript:get_pay_list('".$id."')\">支持列表</a>";
		}
	}
	function get_item($id,$deal){
 		if($deal['type']==1 || $deal['type']==4){
			return "";
		}else{
			return "<a href=\"javascript:deal_item('".$id."')\">子项目</a>";
		}
	}
	function get_level($level){
		$user_level = $GLOBALS['db']->getOne("select `name` from ".DB_PREFIX."user_level where id = '".intval($level)."'");
		return $user_level;
	}
	function edit_new($name,$id){
		$deal=$GLOBALS['db']->getOne("select type from ".DB_PREFIX."deal where id=$id ");
 		if($deal['type']==1 || $deal['type']==4){
			return "<a href=\"javascript:edit_investor('".$id."')\">$name</a>";
		}
		else{
			return "<a href=\"javascript:edit('".$id."')\">$name</a>";
		}
	}
	function get_bonus($id,$deal){
 		if(($deal['type']==1 || $deal['type']==4)&&$deal['is_success']==1&&$deal['end_time']<get_gmtime()&&$deal['end_time']!=0&&$deal['stock_type']!=2){
			return "<a href=\"javascript:deal_bonus('".$id."')\">分红列表</a>";
		}else{
			return "";
		}
	}
	function get_fixed_interest($id,$deal){
 		if(($deal['type']==1 || $deal['type']==4)&&$deal['is_success']==1&&$deal['end_time']<get_gmtime()&&$deal['end_time']!=0&&$deal['stock_type']!=1){
			return "<a href=\"javascript:deal_fixed_interest('".$id."')\">固定回报列表</a>";
		}else{
			return "";
		}
	}
	function get_buy_house_earnings($id,$deal){
 		if($deal['type']==2&&$deal['is_success']==1&&$deal['end_time']<get_gmtime()&&$deal['end_time']!=0){
			return "<a href=\"javascript:deal_buy_house_earnings('".$id."')\">买房收益列表</a>";
		}else{
			return "";
		}
	}
	function get_lottery($id)
	{
		$count=M("dealItem")->where("deal_id =".intval($id)." and type =2")->count();
		if($count)
		{
			return "<a href=\"javascript:get_lottery_list('".$id."')\">抽奖号</a>";
		}
	}
	function get_pay_log($id,$deal)
	{
		if($deal['is_success']==1&&$deal['end_time']<get_gmtime()&&$deal['end_time']!=0)
		{
			return "<a href=\"javascript:pay_log('".$id."')\">发放筹款</a>";
 			
		}
	}
	
	function get_preview($id)
	{
		return "<a href='__ROOT__/index.php?ctl=deal&act=show&id=".$id."' target='_blank'>预览</a>";
	} ?>
<script>
	//编辑跳转
function edit_1(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit_investor&id="+id;
}
function get_pay_list(id){
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=get_pay_list&deal_id="+id;
}
function edit_index(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id;
}
function edit_investor_index(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit_investor&id="+id;
}
function deal_bonus(id){
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=user_bonus&deal_id="+id+"&type="+0;
}
function deal_fixed_interest(id){
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=user_bonus&deal_id="+id+"&type="+1;
}
function deal_buy_house_earnings(id){
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=user_bonus&deal_id="+id+"&type="+2;
}
function get_lottery_list(id){
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=get_lottery&id="+id;
}
function add_selfless(){
	location.href = ROOT+'?m='+MODULE_NAME+'&a=add&type=3';
}
function add_financing(){
	location.href = ROOT+'?m='+MODULE_NAME+'&a=add_investor&type=4';
}
</script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.weebox.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/deal.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.php?lang=zh-cn" ></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/js/calendar/calendar.css" />
<script type="text/javascript" src="__TMPL__Common/js/calendar/calendar.js"></script>
<div class="main">
<div class="main_title">上线项目</div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="新增股权众筹" onclick="add_investor();" <?php if( INVEST_TYPE == 1 ): ?>style="display:none;"<?php endif; ?> >
	<input type="button" class="button" value="移到回收站" onclick="del();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		项目名称：<input type="text" class="textbox" name="name" value="<?php echo trim($_REQUEST['name']);?>" style="width:100px;" />
		时间:<select name="time_status">
				<option value="0" <?php if($_REQUEST['time_status'] == 0): ?>selected="selected"<?php endif; ?>>全部</option>
				<option value="1" <?php if($_REQUEST['time_status'] == 1): ?>selected="selected"<?php endif; ?>>未开始</option>
				<option value="2" <?php if($_REQUEST['time_status'] == 2): ?>selected="selected"<?php endif; ?>>进行中</option>
				<option value="3" <?php if($_REQUEST['time_status'] == 3): ?>selected="selected"<?php endif; ?>>已结束</option>
			</select>
		分类:<select name="cate_id">
				<option value="0" <?php if($_REQUEST['time_status'] == 0): ?>selected="selected"<?php endif; ?>>全部</option>
				<?php if(is_array($cate_list)): foreach($cate_list as $key=>$cate_item): ?><option value="<?php echo ($cate_item["id"]); ?>" <?php if($_REQUEST['cate_id'] == $cate_item['id']): ?>selected="selected"<?php endif; ?>><?php echo ($cate_item["name"]); ?></option><?php endforeach; endif; ?>
			</select>
		类型:<select name="type">
			<option value="NULL" <?php if($_REQUEST['type'] == 'NULL'): ?>selected="selected"<?php endif; ?> >请选择</option>
			 <?php if( INVEST_TYPE != 2 ): ?><option value="0" <?php if($_REQUEST['type'] == '0'): ?>selected="selected"<?php endif; ?> >产品众筹</option><?php endif; ?>
			<?php if( INVEST_TYPE != 1 ): ?><option value="1" <?php if($_REQUEST['type'] == '1'): ?>selected="selected"<?php endif; ?> >股权众筹</option><?php endif; ?>
			<?php if( SELFLESS_TYPE == 1 ): ?><option value="3" <?php if($_REQUEST['type'] == '3'): ?>selected="selected"<?php endif; ?> >公益众筹</option><?php endif; ?>
			<?php if( INVEST_TYPE != 1 ): ?><option value="4" <?php if($_REQUEST['type'] == '4'): ?>selected="selected"<?php endif; ?> >融资众筹</option><?php endif; ?>
			</select>
		发起人ID: <input type="text" class="textbox" name="user_id" value="<?php echo trim($_REQUEST['user_id']);?>" style="width:30px;" />
		<div class="blank10"></div>
		创建时间：<input type="text" class="textbox" name="create_time_1" id="create_time_1" value="<?php echo ($_REQUEST['create_time_1']); ?>" onfocus="this.blur(); return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />
			   <input type="button" class="button" id="btn_create_time_1" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_1', '%Y-%m-%d', false, false, 'btn_create_time_1');" />	
		至 <input type="text" class="textbox" name="create_time_2" id="create_time_2" value="<?php echo ($_REQUEST['create_time_2']); ?>" onfocus="this.blur(); return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />
		<input type="button" class="button" id="btn_create_time_2" value="<?php echo L("SELECT_TIME");?>" onclick="return showCalendar('create_time_2', '%Y-%m-%d', false, false, 'btn_create_time_2');" />	
		
		<input type="hidden" value="DealInvestorOnline" name="m" />
		<input type="hidden" value="online_index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="21" class="topTd" ></td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="40px   "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="150px   "><a href="javascript:sortBy('name','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照项目名称<?php echo ($sortType); ?> ">项目名称<?php if(($order)  ==  "name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px   "><a href="javascript:sortBy('type','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照类型<?php echo ($sortType); ?> ">类型<?php if(($order)  ==  "type"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px   "><a href="javascript:sortBy('ips_bill_no','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照支付方式<?php echo ($sortType); ?> ">支付方式<?php if(($order)  ==  "ips_bill_no"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px   "><a href="javascript:sortBy('user_level','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照项目等级<?php echo ($sortType); ?> ">项目等级<?php if(($order)  ==  "user_level"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="50px   "><a href="javascript:sortBy('user_id','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照发起人<?php echo ($sortType); ?> ">发起人<?php if(($order)  ==  "user_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('limit_price','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照目标金额   <?php echo ($sortType); ?> ">目标金额   <?php if(($order)  ==  "limit_price"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('support_amount','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照已筹金额   <?php echo ($sortType); ?> ">已筹金额   <?php if(($order)  ==  "support_amount"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="30px   "><a href="javascript:sortBy('is_success','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照成功<?php echo ($sortType); ?> ">成功<?php if(($order)  ==  "is_success"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="30px   "><a href="javascript:sortBy('focus_count','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照关注<?php echo ($sortType); ?> ">关注<?php if(($order)  ==  "focus_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="30px   "><a href="javascript:sortBy('support_count','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照支持<?php echo ($sortType); ?> ">支持<?php if(($order)  ==  "support_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="120px   "><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照创建时间<?php echo ($sortType); ?> ">创建时间<?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="120px   "><a href="javascript:sortBy('end_time','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照结束时间<?php echo ($sortType); ?> ">结束时间<?php if(($order)  ==  "end_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px   "><a href="javascript:sortBy('sort','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照<?php echo L("SORT");?><?php echo ($sortType); ?> "><?php echo L("SORT");?><?php if(($order)  ==  "sort"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px   "><a href="javascript:sortBy('is_special','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照专题<?php echo ($sortType); ?> ">专题<?php if(($order)  ==  "is_special"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px   "><a href="javascript:sortBy('is_recommend','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照推荐<?php echo ($sortType); ?> ">推荐<?php if(($order)  ==  "is_recommend"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px   "><a href="javascript:sortBy('is_classic','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照经典<?php echo ($sortType); ?> ">经典<?php if(($order)  ==  "is_classic"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px   "><a href="javascript:sortBy('is_hot','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照热门<?php echo ($sortType); ?> ">热门<?php if(($order)  ==  "is_hot"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="25px"><a href="javascript:sortBy('is_top','<?php echo ($sort); ?>','DealInvestorOnline','online_index')" title="按照置顶<?php echo ($sortType); ?> ">置顶<?php if(($order)  ==  "is_top"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="60px">操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$deal): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($deal["id"]); ?>"></td><td><?php echo ($deal["id"]); ?></td><td><?php echo (edit_new($deal["name"],$deal['id'])); ?></td><td><?php echo (get_type_name($deal["type"])); ?></td><td><?php echo (is_ips_bill_no_admin($deal["ips_bill_no"])); ?></td><td><?php echo (get_level($deal["user_level"],$deal['user_level'])); ?></td><td><?php echo (get_deal_user($deal["user_id"])); ?></td><td><?php echo (format_price($deal["limit_price"])); ?></td><td><?php echo (format_price($deal["support_amount"])); ?></td><td><?php echo (get_status($deal["is_success"])); ?></td><td><?php echo ($deal["focus_count"]); ?></td><td><?php echo ($deal["support_count"]); ?></td><td><?php echo (to_date($deal["create_time"])); ?></td><td><?php echo (get_to_date($deal["end_time"])); ?></td><td><?php echo (get_sort($deal["sort"],$deal['id'])); ?></td><td><?php echo (get_toogle_status($deal["is_special"],$deal['id'],is_special)); ?></td><td><?php echo (get_toogle_status($deal["is_recommend"],$deal['id'],is_recommend)); ?></td><td><?php echo (get_toogle_status($deal["is_classic"],$deal['id'],is_classic)); ?></td><td><?php echo (get_toogle_status($deal["is_hot"],$deal['id'],is_hot)); ?></td><td><?php echo (get_toogle_status($deal["is_top"],$deal['id'],is_top)); ?></td><td class="op_action"><div class="viewOpBox_demo"> <?php echo (get_edit($deal["id"],$deal)); ?>&nbsp;<a href="javascript: del('<?php echo ($deal["id"]); ?>')">移到回收站</a>&nbsp; <?php echo (get_item($deal["id"],$deal)); ?>&nbsp; <?php echo (get_pay_log($deal["id"],$deal)); ?>&nbsp;<a href="javascript:deal_log('<?php echo ($deal["id"]); ?>')">项目日志</a>&nbsp; <?php echo (get_refund($deal["id"],$deal)); ?>&nbsp; <?php echo (get_pay_list($deal["id"],$deal)); ?>&nbsp; <?php echo (get_lottery($deal["id"])); ?>&nbsp; <?php echo (get_bonus($deal["id"],$deal)); ?>&nbsp; <?php echo (get_fixed_interest($deal["id"],$deal)); ?>&nbsp; <?php echo (get_buy_house_earnings($deal["id"],$deal)); ?>&nbsp; <?php echo (get_preview($deal["id"])); ?>&nbsp;</div><a href="javascript:void(0);" class="opration"><span>操作</span><i></i></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="21" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 


<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>