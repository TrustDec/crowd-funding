<div class="page_title">
		<?php if (ACTION_NAME == 'index'): ?>最新动态<?php endif; ?>
		<?php if (ACTION_NAME == 'fav'): ?>我关注的项目动态<?php endif; ?>
</div>
<div class="blank"></div>		
<!-- <div class="switch_nav">
	<ul>
		<li <?php if (ACTION_NAME == 'index'): ?>class="select"<?php endif; ?>>
			<a href="<?php
echo parse_url_tag("u:news|"."".""); 
?>">最新动态</a>
		</li>
		<li <?php if (ACTION_NAME == 'fav'): ?>class="select"<?php endif; ?>>
			<a href="<?php
echo parse_url_tag("u:news#fav|"."".""); 
?>">我关注的项目动态</a>
		</li>
	</ul>		
</div>
 -->
<div class="hd3">
	<div class="hd31 f_l">
		<ul class="clearfix">
			<li <?php if (ACTION_NAME == 'index'): ?>class="select"<?php endif; ?>>
				<a href="<?php
echo parse_url_tag("u:news|"."".""); 
?>">最新动态</a>
			</li>
			<li <?php if (ACTION_NAME == 'fav'): ?>class="select"<?php endif; ?>>
				<a href="<?php
echo parse_url_tag("u:news#fav|"."".""); 
?>">我关注的项目动态</a>
			</li>
		</ul>
	</div>
</div>