<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo app_conf("SITE_NAME");?><?php echo l("ADMIN_PLATFORM");?></title>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/left.css" />
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.timer.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/left.js"></script>
</head>

<body>
	<?php if(is_array($menus)): $k = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_group): ++$k;$mod = ($k % 2 )?><dl class="menu <?php if($k > 1): ?>menu-hide<?php endif; ?>">
		<dt><?php echo ($menu_group["name"]); ?></dt>
		<?php if(is_array($menu_group["nodes"])): foreach($menu_group["nodes"] as $key=>$node): ?><dd><a href="<?php echo u($node["module"]."/".$node["action"]);?>"><?php echo ($node["name"]); ?></a></dd><?php endforeach; endif; ?>		
	</dl><?php endforeach; endif; else: echo "" ;endif; ?>
	<script type="text/javascript">
		jQuery(function(){
			$(".menu dt").click(function(){
				if($(this).parent().hasClass("menu-hide")){
					$(this).parent().removeClass("menu-hide");
				}
				else{
					$(this).parent().addClass("menu-hide");
				}
			});
		})
	</script>
</body>
</html>