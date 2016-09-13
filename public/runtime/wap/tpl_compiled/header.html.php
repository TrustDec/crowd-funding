	<?php echo $this->fetch('inc/sui_mobile_header.html'); ?>
    <?php
    	$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/js/sui_mobile/sm.min.css";
    	$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/js/sui_mobile/sm-extend.min.css";
    	$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/sm_jianrong.css";
    	
        $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/base.reset.css";
        $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/base.frame.css";
        $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/base.theme.css";
        $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/base.ui.css";
        $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/common_css/style.css";

    	$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/sui_mobile/zepto.min.js";
        $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fastclick.js";
        $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/jquery.bank.js";
        $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/lazyload.js";
        $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
        $this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
        $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/common_js/script.js";
        $this->_var['cpagejs'][]='';
        if(app_conf("APP_MSG_SENDER_OPEN")==1)
        {
            $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/msg_sender.js";
            $this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/msg_sender.js";
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
    <script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['pagejs'],
  'c' => $this->_var['cpagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
    <script type='text/javascript' src='<?php echo $this->_var['APP_ROOT']; ?>/public/region.js'></script>
    <script type="text/javascript">
        var APP_ROOT = '<?php echo $this->_var['APP_URL']; ?>';
        var APP_ROOT_ORA = '<?php echo $this->_var['PC_URL']; ?>';
        <?php if (app_conf ( "APP_MSG_SENDER_OPEN" ) == 1): ?>
        var send_span = <?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SEND_SPAN',
);
echo $k['name']($k['v']);
?>000;
        <?php endif; ?>
		var __HASH_KEY__ = "<?php echo $this->_var['hash_key']; ?>";
        var isapp = '<?php echo $this->_var['is_app']; ?>';
        var is_sdk = '<?php echo $this->_var['is_sdk']; ?>';
    </script>
    <script type="text/javascript">
        if ('addEventListener' in document) {
            document.addEventListener('DOMContentLoaded', function() {
                FastClick.attach(document.body);
            }, false);
        }
    </script>
</head>
<body>
    <!-- page 开始 -->
    <div class="page" id="<?php echo $this->_var['mobile_id']; ?>" <?php if ($this->_var['is_app']): ?> title='{"is_show_header":1,"is_show_left_button":"<?php echo $this->_var['left_app_msg']; ?>","title":"<?php if ($this->_var['page_title']): ?><?php echo $this->_var['page_title']; ?><?php else: ?><?php echo $this->_var['site_name']; ?><?php endif; ?>","is_show_right_button":"<?php echo $this->_var['right_app_msg']; ?>","is_back_first":"<?php echo $this->_var['is_back_first']; ?>","is_back":<?php echo $this->_var['is_back']; ?>}' <?php endif; ?>>
        <!-- header 开始 -->
    	<?php echo $this->fetch('inc/sui_mobile_title.html'); ?>
		<?php echo $this->fetch('inc/sui_mobile_user_status.html'); ?>
        <!-- header 结束 -->
        <!-- content 开始 -->
        <div class="content <?php if ($this->_var['is_loop']): ?>infinite-scroll<?php endif; ?> <?php if ($this->_var['is_pull_to_refresh']): ?>pull-to-refresh-content<?php endif; ?>" id="content_box">
            <?php echo $this->fetch('inc/header_search.html'); ?>