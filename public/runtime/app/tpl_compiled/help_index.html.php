<?php echo $this->fetch('inc/header.html'); ?> 
<style type="text/css">
	.l_main{width:auto;}
</style>
<div class="blank20"></div>
<div class="wrap">
	<div class="xqmain_main">
		<div class="page_title" style="border-bottom: 2px #12ADFF solid;">
			<?php echo $this->_var['help_item']['title']; ?>
		</div>
		<div class="l_main" style="padding-left:18px;padding-right:18px;">
			<?php echo $this->_var['help_item']['content']; ?>
		</div>
		<div class="blank"></div>
	</div>
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?> 