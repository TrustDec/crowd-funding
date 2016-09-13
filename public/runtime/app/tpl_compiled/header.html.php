<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="myApp">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="renderer" content="webkit" />
<meta name="keywords" content="<?php if ($this->_var['page_seo_keyword'] != ''): ?><?php echo $this->_var['page_seo_keyword']; ?><?php else: ?><?php echo $this->_var['seo_keyword']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_seo_description'] != ''): ?><?php echo $this->_var['page_seo_description']; ?><?php else: ?><?php echo $this->_var['seo_description']; ?><?php endif; ?>" />
<title><?php if ($this->_var['page_title'] != ''): ?><?php echo $this->_var['page_title']; ?> - <?php endif; ?><?php echo $this->_var['site_name']; ?> - <?php echo $this->_var['seo_title']; ?></title>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanwe_utils/base.theme.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanwe_utils/base.frame.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanwe_utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanwe_utils/login_pop.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/layout.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/style.css";
if(MODULE_NAME=="licai"){
	$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/licai.css";
}
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/head.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/foot.css";
if(MODULE_NAME=="home" || MODULE_NAME=="account" || MODULE_NAME=="settings" || MODULE_NAME=="comment" || MODULE_NAME=="message" || MODULE_NAME=="notify" || MODULE_NAME=="referral" || MODULE_NAME=="finance" || MODULE_NAME=="score_goods_order"){
	$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/account.css";
}
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/more1280.css";

$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/article.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/common_js/jquery.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/login_pop.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/lazyload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/zcUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/zcUI.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/common_js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/common_js/script.js";
if(app_conf("APP_MSG_SENDER_OPEN")==1)
{
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/msg_sender.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/msg_sender.js";
}
if(HAS_DEAL_NOTIFY>0)
{
$this->_var['notifypagejs'][] = $this->_var['TMPL_REAL']."/js/notify_sender.js";
$this->_var['cnotifypagejs'][] = $this->_var['TMPL_REAL']."/js/notify_sender.js";	
}
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/plupload/plupload.full.min.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/plupload/plupload.full.min.js";
?>
<link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['pagecss'],
);
echo $k['name']($k['v']);
?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['TMPL']; ?>/css/common_css/more1280.css" id="screenCss" />
<script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['pagejs'],
  'c' => $this->_var['cpagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
<script type="text/javascript">
var APP_ROOT = '<?php echo $this->_var['APP_ROOT']; ?>';
var LOADER_IMG = '<?php echo $this->_var['TMPL']; ?>/images/lazy_loading.gif';
var ERROR_IMG = '<?php echo $this->_var['TMPL']; ?>/images/image_err.gif';
<?php if (app_conf ( "APP_MSG_SENDER_OPEN" ) == 1): ?>
var send_span = <?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SEND_SPAN',
);
echo $k['name']($k['v']);
?>000;
<?php endif; ?>
var __HASH_KEY__ = "<?php 
$k = array (
  'name' => 'get_hash_key',
);
echo $this->_hash . $k['name'] . '|' . base64_encode(serialize($k)) . $this->_hash;
?>";
</script>
<?php if (HAS_DEAL_NOTIFY > 0): ?>
<script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['notifypagejs'],
  'c' => $this->_var['cnotifypagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
<?php endif; ?>
<script type='text/javascript'  src='<?php echo $this->_var['APP_ROOT']; ?>/public/region.js'></script>
<!--[if IE 6]>
	<script src="<?php echo $this->_var['TMPL']; ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
	DD_belatedPNG.fix('img , .banner .btn_tit ul li.on , .banner .btn_tit ul li , .mx_1 , .mx_2 , .mx_3 , .mx_4 , .xzdq1 , .zcz , .timeline-left-gray , .deal_log_title .title , .timeline-comment-top , .timeline-start span , .pageleft a , .dz , .kj , .mf , .boxaddress , .xzdq , .con6 .sub , .fx i , .attention_focus_deal i , .head_down_icon , .banner .prev , .banner .next , .mxr_1 , .mxr_2 , .mxr_3 , .shenhe .shenhe_info li , .mod_title i , .box4 .mod_cont3 .leader_t , .box4 .mod_cont3 .leader_w , .box4 .mod_cont3 .leader_r , .box4 .mod_cont3 .leader_x , .box4 .mod_cont3 .leader_p , .step_box , .pageleft a i , .invester_all .col_a , .article_l li.on , .article_box .article_r_list h3 , .deals_cate_equity .equity_box .equity_box_l .inf_2 i , .fa , .send_message , .sidebar ul li .sidetop , .sidebar ul li .sidetop:hover , .header .header_nav_box .main_publish .btn_publish , .login_tip span a.zc_phone , .icon_arrow_down , .homeleft .menutitle .icons , .uinfo_settting .set , .u_header .u_total_box , .u_header .u_img .u_img_bg , .jcDateIco'); 
	</script>
<![endif]-->
</head>
<body>
<div class="header" id="J_head">
	<div class="header_box1">
		<div class="header_top wrap">
			<div class="logo f_l">
				<a class="link" href="<?php echo $this->_var['APP_ROOT']; ?>/">
					<?php
						$this->_var['logo_image'] = app_conf("SITE_LOGO");
					?>
					<img src="<?php echo $this->_var['logo_image']; ?>" />
				</a>
			</div>
			<div class="header_search f_l">
				<form action="<?php
echo parse_url_tag("u:deals|"."".""); 
?>" method="post" id="header_search_form">
					 <?php if (app_conf ( "INVEST_STATUS" ) == 0): ?> 
						     			<a href="javascript:void(0);" livalue="0" class="search_cate cur" checked="checked">产品众筹</a>
						         		 <a href="javascript:void(0);" livalue="1" class="search_cate"><?php echo $this->_var['gq_name']; ?></a> 
					<?php endif; ?>
					<?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
					<a href="javascript:void(0);" livalue="0" class="search_cate cur" checked="checked">产品众筹</a>
					<?php endif; ?>
					<?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
					 <a href="javascript:void(0);" livalue="1" class="search_cate cur" checked="checked"><?php echo $this->_var['gq_name']; ?></a> 
					<?php endif; ?>
					<?php if (app_conf ( "IS_SELFLESS" ) == 1): ?>
						         		<a href="javascript:void(0);" livalue="3" class="search_cate">公益众筹</a>
					<?php endif; ?>
					<?php if (app_conf ( "IS_FINANCE" ) == 1): ?>
						         		<a href="javascript:void(0);" livalue="4" class="search_cate">融资众筹</a>
					<?php endif; ?>
					<?php if (app_conf ( "IS_HOUSE" ) == 1): ?>
						         		<a href="javascript:void(0);" livalue="2" class="search_cate">房产众筹</a>
					<?php endif; ?>
					<div class="blank0"></div>
					<input type="text" id="header_keyword" name="k" placeholder="搜索项目" class="seach_text">
					<input type="button" value="搜索" class="seach_submit" id="header_submit" />
					<?php if (app_conf ( "INVEST_STATUS" ) == 0): ?> 
						<input type="hidden" name="type" value="0"/>				
						<input type="hidden" name="redirect" value="1"/>		
					<?php endif; ?>
					<?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
						<input type="hidden" name="type" value="0"/>		
					<?php endif; ?>
					<?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
						<input type="hidden" name="type" value="1"/>	
					<?php endif; ?>
				</form>	
	        </div>
       		<div class="f_r" style="width:320px;height:102px;">		
			<?php 
$k = array (
  'name' => 'login_tip',
);
echo $this->_hash . $k['name'] . '|' . base64_encode(serialize($k)) . $this->_hash;
?> 
			<div class="clear"></div>
			<?php if (app_conf ( 'KF_PHONE' )): ?> 
			<div class="hotline tr f_666">
				客服热线<span class="f24 ml10"><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'KF_PHONE',
);
echo $k['name']($k['v']);
?></span>
			</div>
			<?php endif; ?>
		</div>
		<div class="blank0"></div>
	</div>
	</div>
	<div class="header_nav theme_bgcolor">
		<div class="header_nav_box">
			<ul class="main_nav f_l">
				<?php $_from = $this->_var['nav_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['nav_item']):
?>
				<li class="<?php if ($this->_var['deal_type']): ?><?php if ($this->_var['deal_type'] == 'gq_type' && $this->_var['nav_item']['u_module'] == 'deals' && $this->_var['nav_item']['u_action'] == 'index' && $this->_var['nav_item']['u_param'] == 'type=1'): ?>current<?php elseif ($this->_var['deal_type'] == 'gq_type' && $this->_var['nav_item']['u_module'] == 'deals' && $this->_var['nav_item']['u_action'] == 'stock'): ?>current <?php elseif ($this->_var['deal_type'] == 'selfless_type' && $this->_var['nav_item']['u_module'] == 'deals' && $this->_var['nav_item']['u_action'] == 'selfless'): ?>current <?php elseif ($this->_var['deal_type'] == 'product_type' && $this->_var['nav_item']['u_module'] == 'deals' && $this->_var['nav_item']['u_action'] == 'index'): ?>current<?php elseif ($this->_var['deal_type'] == 'gy_type' && $this->_var['nav_item']['u_module'] == 'deals' && $this->_var['nav_item']['u_action'] == 'selfless'): ?>current <?php elseif ($this->_var['deal_type'] == 'home' && $this->_var['nav_item']['u_module'] == 'investor' && $this->_var['nav_item']['u_action'] == 'invester_list'): ?>current <?php elseif ($this->_var['deal_type'] == 'investor_type' && $this->_var['nav_item']['u_module'] == 'investor' && $this->_var['nav_item']['u_action'] == 'invester_list'): ?>current <?php elseif ($this->_var['deal_type'] == 'article_type' && $this->_var['nav_item']['u_module'] == 'article_cate' && $this->_var['nav_item']['u_action'] == '' && $this->_var['nav_item']['u_param'] == ''): ?>current<?php elseif ($this->_var['deal_type'] == 'house_type' && $this->_var['nav_item']['u_action'] == 'house'): ?>current<?php elseif ($this->_var['deal_type'] == 'finance_type' && $this->_var['nav_item']['u_module'] == 'finance'): ?>current<?php endif; ?><?php else: ?><?php if ($this->_var['nav_item']['current'] == 1): ?>current<?php endif; ?><?php endif; ?>">
					<a href="<?php echo $this->_var['nav_item']['url']; ?>"  target="<?php if ($this->_var['nav_item']['blank'] == 1): ?>_blank<?php endif; ?>" title="<?php echo $this->_var['nav_item']['name']; ?>"><?php echo $this->_var['nav_item']['name']; ?></a>	
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
			<?php if (! app_conf ( "PROJECT_HIDE" )): ?>
			<div class="main_publish f_r">
				<a href="javascript:check_tg();" class="btn_publish"></a>
				<input type="hidden" name="check_login" value="<?php echo $this->_var['user_info']['id']; ?>">
			</div>
			<?php endif; ?>
		</div>
	</div>		
</div>
<script type="text/javascript">
	(function(){
		if(!($(".zc_phone_drop").children().length)){
			$(".zc_phone").remove();
		}

		var iWinWidth = $(window).width();  // 获取当前屏幕分辨率
	    if(iWinWidth <= 1280){               // 小于等于1280更改css样式路径
	        $("#screenCss").attr("href","<?php echo $this->_var['TMPL']; ?>/css/common_css/less1280.css");
	    }
	})();
	$(function(){
		$(".search_cate").bind('click',function(){
			$(this).attr("checked",true).addClass("cur").siblings().attr("checked",false).removeClass("cur");
			$("input[name='type']").val($(this).attr("livalue"));
			
			if($(this).attr("livalue")==4){
				$("#header_search_form").attr("action","<?php
echo parse_url_tag("u:finance|"."".""); 
?>");
			}
		});
		
		//解决input中placeholder值在ie中无法支持的问题
		var doc=document,inputs=doc.getElementsByTagName('input'),supportPlaceholder='placeholder'in doc.createElement('input'),placeholder=function(input){var text=input.getAttribute('placeholder'),defaultValue=input.defaultValue;
		if(defaultValue==''){
			input.value=text}
			input.onfocus=function(){
				if(input.value===text){
				this.value=''
				}
			};
			input.onblur=function(){
				if(input.value===''){
					this.value=text
				}
			}
		};
		if(!supportPlaceholder){
			for(var i=0,len=inputs.length;i<len;i++){
				var input=inputs[i],text=input.getAttribute('placeholder');
				if(input.type==='text'&&text){
					placeholder(input)
				}
			}
		}
	});

	function check_tg(){
		var is_tg=<?php echo $this->_var['is_tg']; ?>,
			is_user_tg=<?php echo $this->_var['is_user_tg']; ?>,
			is_user_investor=<?php echo $this->_var['is_user_investor']; ?>,
			check_login=$("input[name='check_login']").val();
		if(check_login){
			if(is_tg){
				if(!is_user_tg){
					$.showErr("您未绑定资金托管账户，无法发起项目，点击确定后跳转到绑定页面",function(){
						window.location.href=APP_ROOT +"/index.php?ctl=collocation&act=CreateNewAcct&user_type=0&user_id=<?php echo $this->_var['user_info']['id']; ?>";
					});
				}else{
					window.location.href="<?php
echo parse_url_tag("u:project#choose|"."".""); 
?>";
				}
			}else{
				
				if(is_user_investor ==1){
					window.location.href="<?php
echo parse_url_tag("u:project#choose|"."".""); 
?>";
				}else if(is_user_investor ==2){
					$.showErr("您的实名认证正在审核中，还不能发起项目，请联系网站管理员");
				}
				else{
					$.showErr("您未进行身份认证，无法发起项目，点击确定后跳转到身份认证页面",function(){
						window.location.href="<?php
echo parse_url_tag("u:settings#security|"."method=setting-id-box".""); 
?>";
					});
				}
				
			}
		}
		else{
			$.showErr("请先登录再进行发起项目",function(){
				show_pop_login();
			});
		}
	}
</script>
<script type="text/javascript">
	window.onload=function(){
		var url=window.location.href;
		if (url.indexOf("ctl=faq")>0) {
			$("[title=新手帮助]").css("background","#1271B6");
		}else if (url.indexOf("act=indexs")>0) {
			$("[title=首页]").css("background","#1271B6");
		};;
	}
</script>