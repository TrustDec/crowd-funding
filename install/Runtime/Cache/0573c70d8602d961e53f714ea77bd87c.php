<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>方维众筹系统  -- 安装向导</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__TMPL__Public/css/style.css" />
</head>
<body>
<div class="install block">
<form name="install" action="<?php echo u('Index/index');?>" method="POST" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  	<td colspan="2" style="height:10px;">
	  		
	  	</td>
	  </tr>
	  <?php if(is_array($result)): foreach($result as $key=>$rs): ?><?php if($key != 'status'): ?><tr>
	  		
	  			<?php if($key == 'msg' or $key == 'php_env' or $key == 'gd_info' or $key == 'mb_info'): ?><td>
		  			<?php if($key == 'msg'): ?>检测结果：<?php endif; ?>
		  			<?php if($key == 'php_env'): ?>PHP 环境：<?php endif; ?>
		  			<?php if($key == 'gd_info'): ?>GD函数库：<?php endif; ?>
		  			<?php if($key == 'mb_info'): ?>MBSTRING函数库：<?php endif; ?>
		  		</td>
		  		<td>
	 		  		<?php echo ($rs); ?>
	  	  		</td>
		  		<?php else: ?>
		  		<td>
		  		<?php if($rs["file_type"] == 'dir' ): ?>目录
			  		<?php else: ?>
			  		文件<?php endif; ?><?php echo ($key); ?>:
			  	</td>
			  	<td>
	 		  		<?php echo ($rs["msg"]); ?>
	  	  		</td><?php endif; ?>
	  		
	  		
	  	</tr><?php endif; ?><?php endforeach; endif; ?>
	  
	  <tr>
	  	<td style="height:50px;"></td>
	  	<td>
	  		<?php if($result['status'] == 1): ?><input type="button" value="继续安装" onclick="location.href='<?php echo u('Index/database');?>'" /><?php endif; ?>

	  	</td>
	  </tr>
	  <tr>
	  	<td colspan="2" style="text-align: center;">
	  		方维众筹系统安装程序
	  	</td>
	  </tr>
	</table>
	</form>
</div>
</body>
</html>