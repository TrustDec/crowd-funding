$(document).on("pageInit","#ajax-three_seconds_jump", function(e, pageId, $page) {
	delayURL();    
    function delayURL() { 
        var delay = document.getElementById("time").innerHTML;
 		var t = setTimeout("delayURL()", 1000);
        if (delay > 0) {
            delay--;
            document.getElementById("time").innerHTML = delay;
        } else {
     		clearTimeout(t); 
            href =APP_ROOT+"/index.php?ctl=cart&act=index&id="+id;
			$.router.loadPage(href);
        }        
    } 
});