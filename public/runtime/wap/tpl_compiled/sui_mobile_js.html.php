<script type="text/javascript">
$(document).on("pageInit", function(e, pageId, $page) {

});

//$(document).on("pageInit","#index-index", function(e, pageId, $page) {
// 	$(function(){
//		$.refreshScroller();
//	    $('.lazyImg_index').LazyLoad({
//	        container: window,
//	        event:'scroll',
//	        effect: 'show',
//	        effectArgs: 'slow',
//	        loadImg:'<?php echo $this->_var['TMPL']; ?>/images/loading_img.gif',
//	        load: null,
//	        offset: 50
//	    });
//	});
//});
 
$(document).on("pageReinit", function(e, pageId, $page) {
});

$(document).on("pageLoadError", function(e, pageId, $page) {
 	$.toast("加载失败,返回首页");
    $.router.loadPage('<?php
echo parse_url_tag_wap("u:index|"."".""); 
?>');
});
$.init();
</script>