<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__TMPL__Public/css/style.css" />
<script language="JavaScript">
function jump(url)
{
	if(url.substr(0,10)!="javascript")
	location.href = url;
	else
	history.go(-1);
}
<?php if($waitSecond != '-1'): ?>window.setInterval("jump('<?php echo ($jumpUrl); ?>')", <?php echo ($waitSecond); ?>000);<?php endif; ?>
</script>
</head>
<body>
<div class="block install">
	<div style=" font-size:12px; font-weight:bold;"><?php echo ($msgTitle); ?></div>
	<div style="color: #f30; font-size:14px; font-weight:bold;"><?php echo ($message); ?></div>
	<div><?php echo ($error); ?></div>
	<?php if($waitSecond != '-1'): ?><div>
	系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <a href="<?php echo ($jumpUrl); ?>">这里</a> 跳转
	</div><?php endif; ?>
</div>
</body>
</html>