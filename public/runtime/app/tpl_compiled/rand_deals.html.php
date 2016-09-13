<div class="blank10"></div>
<?php $_from = $this->_var['rand_deals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'deal_item');if (count($_from)):
    foreach ($_from AS $this->_var['deal_item']):
?>
<div class="rand_deal">
	<div class="rand_deal_image">
	<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['deal_item']['id']."".""); 
?>" title="<?php echo $this->_var['deal_item']['name']; ?>" target="_blank">
	<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['deal_item']['image'],
  'w' => '60',
  'h' => '60',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" />
	</a>
	</div>
	<div class="rand_deal_title">
	<a href="<?php
echo parse_url_tag("u:deal#show|"."id=".$this->_var['deal_item']['id']."".""); 
?>" title="<?php echo $this->_var['deal_item']['name']; ?>" target="_blank" class="linkgreen">
		<?php 
$k = array (
  'name' => 'msubstr',
  'v' => $this->_var['deal_item']['name'],
  'b' => '0',
  'e' => '20',
);
echo $k['name']($k['v'],$k['b'],$k['e']);
?>	
	</a>		
	</div>
	<div class="blank1"></div>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>