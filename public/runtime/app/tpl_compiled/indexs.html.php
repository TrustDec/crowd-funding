<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php if ($this->_var['page_title'] != ''): ?><?php echo $this->_var['page_title']; ?> - <?php endif; ?><?php echo $this->_var['site_name']; ?> - <?php echo $this->_var['seo_title']; ?></title>
<style type="text/css">
li {
	margin: 0;
	padding: 0;
}
.header {
	position: fixed;
	z-index: 100;
	width: 100%;
}
.pop-container {
	position: relative;
	z-index: 100;
}
.header1 .header_nav {
	width: 100%;
	height: 40px;
	float: left;
	background: rgba(23,143,230,0.7)!important;
}
.Header2_login #mycenter {
	margin-top: 5px;
}
.Header2_login .iconfont {
	color: #AFAFAF;
}
.Header2_login a.mymessage:hover {
	color: #188eee;
}
#Js-shadeLayer{
	width: 100%;height: 100%;	
}
</style>
<link rel="stylesheet" type="text/css" href="app/Tpl/fanwe_1/css/cssType/Type.css">
</head>
<body  class="body">
<?php echo $this->fetch('inc/header.html'); ?> 
<!-- 导航栏二级 -->
<div class="Header2" id="Header2">
  <ul class="Header2_box">
    <li class="Header2_APP"> <a href="javascript:check_tg();">发布项目</a>
      <div class="Header2_border"></div>
      <a href="index.php?ctl=faq">新手帮助 <span></span> </a> </li>
    <div class="Header2_login" style="margin-top:-30px;padding-top:10px;"> <?php 
$k = array (
  'name' => 'login_tip',
);
echo $this->_hash . $k['name'] . '|' . base64_encode(serialize($k)) . $this->_hash;
?> </div>
  </ul>
</div>
<!-- 切换页面开始 -->
<div id="pic1" num="1" >
  <div class="nag">
    <div  class="ac active"><b></b></div>
    <div class="ac"><b></b></div>
    <div class="ac"><b></b></div>
    <div class="ac"><b></b></div>
  </div>
  <div class="divtop"> <img id="btntop" class="btntop" src="app/Tpl/fanwe_1/images/imagesType/top.png" /> </div>
  <div class="leave a1" >
    <div class="back_a1_box"></div>
    <img src="app/Tpl/fanwe_1/images/imagesType/title.png" class="div_title">
    <div class="back_a1_title">
      <p>国内最大的众筹平台</p>
      <p>众筹网是中国最具影响力的众筹平台,为项目发起者提供募资</p>
      <p>投资,孵化,运营一站式综合众筹服务</p>
    </div>
    <?php if ($this->_var['user_info']): ?> 
    <script type="text/javascript">
		$(".back_a1_title").css({"top":"50%"});
		</script> 
    <?php else: ?>
    <div class="back_a1_btn "> <a title="登录" href="javascript:void(0)" id="show_pop_login" class="log Js-showLogin back_a1_btn_login a1_btn1"> 登 录 </a> <a href="<?php
echo parse_url_tag("u:user#register|"."".""); 
?>" title="注册" class="back_a1_btn_login a1_btn2"> 注 册 </a> </div>
    <?php endif; ?> </div>
  <div class="leave a2">
    <div class="back_a1_box"></div>
    <img src="app/Tpl/fanwe_1/images/imagesType/two1.png" class="yu1 "> <img src="app/Tpl/fanwe_1/images/imagesType/two2.png"  class="yu2">
    <div class="imger">
      <h2>什么是众筹</h2>
      依据大众力量来实现梦想。通过对公众展示自己的创意，争取大家的关注和支持，进而获得所需要的资金援助。同时支持者也可以得到相应的奖励与回报。营造一个众筹创意平台，为他人实现梦想，撮合投融资项目，同时可以获得佣金。 </div>
  </div>
</div>
<div class="leave a3">
  <div class="back_a1_box"></div>
  <div class="back_a4_title">
    <p>国内最大的众筹平台</p>
    <p>众筹网是中国最具影响力的众筹平台,为项目发起者提供募资</p>
    <p>投资,孵化,运营一站式综合众筹服务</p>
  </div>
  <div class="back_a4_btn"> <a href="javascript:check_tg();"  id="back_a4_btn_a" >发布项目</a> </div>
  <!-- 背景图变换 --> 
  <script type="text/javascript">
			$(function(){
				$('.back_a4_btn').hover(function(){
					$(this).css('background-position','0 -40px')
				},function(){
					$(this).css('background-position','0 0px')
				})
			})
		</script> 
  <!-- end --> 
</div>
<div class="leave a4">
  <div class="back_box">
    <div class="back_a4_header"> <img src="app/Tpl/fanwe_1/images/imagesType/zc.png"> </div>
    <p class="back_a4_centent">众筹，译自国外crowdfunding一词，即大众筹资或群众筹资。是指用团购+预购的形式，<br/>
      向网友募集项目资金的模式。众筹利用互联网和SNS传播的特性，让许多有梦想的人可以向公众展示自己的创意，发起项目争取别人的支持与帮助，进而获得所需要的援助，支持者则会获得实物、<br/>
      服务等不同形式的回报。</p>
    <div class="back_a4_Bottom">
      <div class="foot-map" style="width:430px;overflow: visible"> <?php $_from = $this->_var['help_cates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help_item');$this->_foreach['help_items'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['help_items']['total'] > 0):
    foreach ($_from AS $this->_var['help_item']):
        $this->_foreach['help_items']['iteration']++;
?>
        <?php if (($this->_foreach['help_items']['iteration'] - 1) < 6): ?>
        <dl <?php if (($this->_foreach['help_items']['iteration'] == $this->_foreach['help_items']['total'])): ?>class="last"<?php endif; ?> style="padding:0;width:100px;">
          <dt><?php echo $this->_var['help_item']['title']; ?></dt>
          <?php $_from = $this->_var['help_item']['article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_list');if (count($_from)):
    foreach ($_from AS $this->_var['article_list']):
?>
          <dd class="Fake_a"><a href="<?php echo $this->_var['article_list']['url']; ?>" ><?php echo $this->_var['article_list']['title']; ?></a></dd>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </dl>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </div>
      <div class="two_code"> <img src="<?php if (app_conf ( 'QR_CODE' )): ?><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'QR_CODE',
);
echo $k['name']($k['v']);
?><?php else: ?><?php echo $this->_var['app']['web_url']; ?><?php endif; ?>" class="two_code_img"> </div>
      <div class="back_tel_kefu"> <span class="two_code_zaixian">在线客服</span> <img src="app/Tpl/fanwe_1/images/imagesType/xinxi.png"  class="two_code_xinxi">
        <div class="clear"></div>
        <img src="app/Tpl/fanwe_1/images/imagesType/tel.png" class="two_code_tel"> <span class="two_tel_T"><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'KF_PHONE',
);
echo $k['name']($k['v']);
?></span> <span class="two_Time"><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'WORK_TIME',
);
echo $k['name']($k['v']);
?></span> </div>
    </div>
    <div class="back_Bottom">
      <div class="mb15" style="margin-bottom: 0px;"> <?php $_from = $this->_var['helps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help');$this->_foreach['helpss'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['helpss']['total'] > 0):
    foreach ($_from AS $this->_var['help']):
        $this->_foreach['helpss']['iteration']++;
?> <a href="<?php echo $this->_var['help']['url']; ?>" title="<?php echo $this->_var['help']['title']; ?>"><?php echo $this->_var['help']['title']; ?></a><?php if (! ($this->_foreach['helpss']['iteration'] == $this->_foreach['helpss']['total'])): ?>
        <div class="back_Bottom_border"></div>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </div>
      <p><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SITE_LICENSE',
);
echo $k['name']($k['v']);
?>&nbsp;<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'STATE_CDOE',
);
echo $k['name']($k['v']);
?></p>
      <p><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'NETWORK_FOR_RECORD',
);
echo $k['name']($k['v']);
?></p>
    </div>
  </div>
</div>
</div>
<!-- //切换页面结束 --> 
<script language="javascript" type="text/javascript" src="app/Tpl/fanwe_1/js/jsType/jquery.easing.js"></script> 
<script language="javascript" type="text/javascript" src="app/Tpl/fanwe_1/js/jsType/Type.js"></script>
</body>
</html>
