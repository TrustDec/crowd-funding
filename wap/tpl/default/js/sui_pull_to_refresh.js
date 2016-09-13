$(document).on("pageInit","#index-index", function(e, pageId, $page) {
	var loading = false;
    var $content = $($page).find(".content").on('refresh', function(e) {
      	if (loading) return;
      	loading =true;
      	var query = new Object();
		query.page  =  1;
		query.is_ajax = 1;
		// var parms = get_search_parms();
		var ajaxurl = $("#index-index .pull_to_refresh_url").val();
	    $.ajax({
	    	url:ajaxurl,
	        data:query,
	        success:function(result){
	        	loading =false;
	       	 	$content.find(".pull-to-refresh-content").html(result);
       			$.pullToRefreshDone($content);
	       	}
	     });
    });
});
$(document).on("pageInit","#deals-index", function(e, pageId, $page) {
	var loading = false;
    var $content = $($page).find(".content").on('refresh', function(e) {
      	if (loading) return;
      	loading =true;
      	var query = new Object();
		query.page  =  1;
		query.is_ajax = 1;
		// var parms = get_search_parms();
		var ajaxurl = $("#deals-index .pull_to_refresh_url").val();
	    $.ajax({
	    	url:ajaxurl,
	        data:query,
	        success:function(result){
	        	loading =false;
	       	 	$content.find(".pull-to-refresh-content").html(result);
       			$.pullToRefreshDone($content);
	       	}
     	});
    });
});
$(document).on("pageInit","#investor-invester_list", function(e, pageId, $page) {
	var loading = false;
    var $content = $($page).find(".content").on('refresh', function(e) {
      	if (loading) return;
      	loading =true;
      	var query = new Object();
		query.page  =  1;
		query.is_ajax = 1;
		// var parms = get_search_parms();
		var ajaxurl = $("#investor-invester_list .pull_to_refresh_url").val();
	    $.ajax({
	    	url:ajaxurl,
	        data:query,
	        success:function(result){
	        	loading =false;
	       	 	$content.find('.pull-to-refresh-content').html(result);
       			$.pullToRefreshDone($content);
	       	}
	     });
    });
});
$(document).on("pageInit","#score_mall-index", function(e, pageId, $page) {
	var loading = false;
    var $content = $($page).find(".content").on('refresh', function(e) {
      	if (loading) return;
      	loading =true;
      	var query = new Object();
		query.page  =  1;
		query.is_ajax = 1;
		// var parms = get_search_parms();
		var ajaxurl = $("#score_mall-index .pull_to_refresh_url").val();
	    $.ajax({
	    	url:ajaxurl,
	        data:query,
	        success:function(result){
	        	loading =false;
	       	 	$content.find('.pull-to-refresh-content').html(result);
       			$.pullToRefreshDone($content);
	       	}
	     });
    });
});
$(document).on("pageInit","#finance-index", function(e, pageId, $page) {
	var loading = false;
    var $content = $($page).find(".content").on('refresh', function(e) {
      	if (loading) return;
      	loading =true;
      	var query = new Object();
		query.page  =  1;
		query.is_ajax = 1;
		// var parms = get_search_parms();
		var ajaxurl = $("#finance-index .pull_to_refresh_url").val();
	    $.ajax({
	    	url:ajaxurl,
	        data:query,
	        success:function(result){
	        	loading =false;
	       	 	$content.find('.pull-to-refresh-content').html(result);
       			$.pullToRefreshDone($content);
	       	}
	     });
    });
});
/*function get_search_parms()
{
	var parms = "";
	if($("#deals_search").length > 0){
		$("#deals_search .deals_search_list li").each(function(){
			parms +="&"+$(this).attr("data-type")+"="+$(this).attr("data-type-value");
		});
	}
	return parms;
}*/