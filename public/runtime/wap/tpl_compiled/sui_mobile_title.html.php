<header class="bar bar-nav header_nav">
	<?php if ($this->_var['class'] != 'index'): ?>
	<a class="button button-link button-nav pull-left <?php if ($this->_var['is_back']): ?>back<?php endif; ?>" href="<?php echo $this->_var['HTTP_REFERER']; ?>" data-transition="slide-out">
	  <span class="icon icon-left"></span>
	  	返回
	</a>
	<?php endif; ?>
	<?php if ($this->_var['class'] == 'index'): ?>
	<h1 class="title"><?php echo $this->_var['site_name']; ?></h1>
	<?php else: ?>
	<h1 class="title pull-left"><?php echo $this->_var['page_title']; ?></h1>
	<?php endif; ?>
	<a class="button  button-link button-nav  pull-right open-panel" data-panel='#panel-left-box'>
		<span class="icon icon-menu"></span>
	</a>
</header>