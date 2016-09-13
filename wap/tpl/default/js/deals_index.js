$(document).on("pageInit","#deals-index", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 

	// 无限ajax加载
	var loading = false;
	$($page).on('infinite', function() {
 	 	if (loading || now_page >= all_page){
 	 		$(".content-inner").css({paddingBottom:"0"});
 			return;
 	 	} 
 	 	$(".infinite-scroll-preloader").show();
      	loading = true;
      	var page_ajax_url = $("input[name='page_ajax_url']").val();
	  	var query = new Object();
	  	query.page  =  now_page + 1;
	  	query.ajax = 1;
      	$.ajax({
	      	url:page_ajax_url,
	      	dataType: "json",
	        data:query,
	        async:false,
	        success:function(data){
	        	setTimeout(function() {
	        		now_page ++;
	        		loading = false;
	        		$(".infinite-scroll .items").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
    });
});
$(document).on("pageInit","#deals-house", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 

	// 无限ajax加载
	var loading = false;
	$($page).on('infinite', function() {
 	 	if (loading || now_page >= all_page){
 	 		$(".content-inner").css({paddingBottom:"0"});
 			return;
 	 	} 
 	 	$(".infinite-scroll-preloader").show();
      	loading = true;
      	var page_ajax_url = $("input[name='page_ajax_url']").val();
	  	var query = new Object();
	  	query.page  =  now_page + 1;
	  	query.ajax = 1;
      	$.ajax({
	      	url:page_ajax_url,
	      	dataType: "json",
	        data:query,
	        async:false,
	        success:function(data){
	        	setTimeout(function() {
	        		now_page ++;
	        		loading = false;
	        		$(".infinite-scroll .items").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
    });
});
$(document).on("pageInit","#deals-selfless", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 

	// 无限ajax加载
	var loading = false;
	$($page).on('infinite', function() {
 	 	if (loading || now_page >= all_page){
 	 		$(".content-inner").css({paddingBottom:"0"});
 			return;
 	 	} 
 	 	$(".infinite-scroll-preloader").show();
      	loading = true;
      	var page_ajax_url = $("input[name='page_ajax_url']").val();
	  	var query = new Object();
	  	query.page  =  now_page + 1;
	  	query.ajax = 1;
      	$.ajax({
	      	url:page_ajax_url,
	      	dataType: "json",
	        data:query,
	        async:false,
	        success:function(data){
	        	setTimeout(function() {
	        		now_page ++;
	        		loading = false;
	        		$(".infinite-scroll .items").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
    });
});
$(document).on("pageInit","#stock_transfer-index", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 

	// 无限ajax加载
	var loading = false;
	$($page).on('infinite', function() {
 	 	if (loading || now_page >= all_page){
 	 		$(".content-inner").css({paddingBottom:"0"});
 			return;
 	 	} 
 	 	$(".infinite-scroll-preloader").show();
      	loading = true;
      	var page_ajax_url = $("input[name='page_ajax_url']").val();
	  	var query = new Object();
	  	query.page  =  now_page + 1;
	  	query.ajax = 1;
      	$.ajax({
	      	url:page_ajax_url,
	      	dataType: "json",
	        data:query,
	        async:false,
	        success:function(data){
	        	setTimeout(function() {
	        		now_page ++;
	        		loading = false;
	        		$(".infinite-scroll .items").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
    });
});