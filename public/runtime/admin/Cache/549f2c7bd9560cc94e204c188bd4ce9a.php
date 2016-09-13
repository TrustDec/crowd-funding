<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo app_conf("SITE_NAME");?><?php echo l("ADMIN_PLATFORM");?></title>
<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
<script type="text/javascript">
	var version = '<?php echo app_conf("DB_VERSION");?>';
</script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/main.css" />
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
</head>

<body>
	<div class="main">
	<div class="main_title"><?php echo conf("SITE_NAME");?><?php echo l("ADMIN_PLATFORM");?> <?php echo L("HOME");?>	</div>
	<div class="blank5"></div>
	<table class="form" cellpadding=0 cellspacing=0>
		<tr>
			<td colspan=2 class="topTd"></td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				<?php echo L("CURRENT_VERSION");?>
			</td>
			<td class="item_input">
				<?php echo L("APP_VERSION");?>:<?php echo conf("DB_VERSION");?> <span id="version_tip"></span>
			</td>
		</tr>
		
		<tr>
			<td class="item_title" style="width:200px;">
				<?php echo L("TIME_INFORMATION");?>
			</td>
			<td class="item_input">
				<?php echo L("CURRENT_TIME");?>：<?php echo to_date(get_gmtime()); ?>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				注册待验证
			</td>
			<td class="item_input">
				 <a href="<?php echo u("User/index",array("is_effect"=>0));?>"><?php echo ($info["user_num"]); ?>个</a>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				项目待审核
			</td>
			<td class="item_input">
				 <a href="<?php echo u("Deal/submit_index");?>"><?php echo ($info["project_none_num"]); ?>个</a>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				投资人待审核
			</td>
			<td class="item_input">
				  <a href="<?php echo u("UserInvestor/index");?>"><?php echo ($info["user_invest_num"]); ?>个</a>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				待审核提现申请
			</td>
			<td class="item_input">
				  <a href="<?php echo u("UserRefund/index",array("is_pay"=>0));?>"><?php echo ($info["user_refund_num"]); ?>个</a>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				订单成功数
			</td>
			<td class="item_input">
				 <a href="<?php echo u("DealOrder/index",array("order_status"=>3));?>"><?php echo ($info["deal_order"]["num"]); ?>个</a>
			</td>
		</tr>
		<tr>
			<td class="item_title" style="width:200px;">
				成交金额
			</td>
			<td class="item_input">
				 ￥<?php echo ($info["deal_order"]["money"]); ?>元
			</td>
		</tr>
 		<tr>
			<td colspan=2 class="bottomTd"></td>
		</tr>
	</table>	
	</div>
</body>
</html>