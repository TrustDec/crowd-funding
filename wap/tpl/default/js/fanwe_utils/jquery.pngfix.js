(function($) {

	$.fn.pngFix = function(settings) {
		settings = $.extend({
			blankgif: 'blank.gif'
	}, settings);

	var ie55 = (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) == 4 && navigator.appVersion.indexOf("MSIE 5.5") != -1);
	var ie6 = (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) == 4 && navigator.appVersion.indexOf("MSIE 6.0") != -1);
	
	if ($.browser.msie && (ie55 || ie6)) {
		$(this).find("img[src$=.png]").each(function() {

			$(this).attr('width',$(this).width());
			$(this).attr('height',$(this).height());

			var prevStyle = '';
			var strNewHTML = '';
			var imgId = ($(this).attr('id')) ? 'id="' + $(this).attr('id') + '" ' : '';
			var imgClass = ($(this).attr('class')) ? 'class="' + $(this).attr('class') + '" ' : '';
			var imgTitle = ($(this).attr('title')) ? 'title="' + $(this).attr('title') + '" ' : '';
			var imgAlt = ($(this).attr('alt')) ? 'alt="' + $(this).attr('alt') + '" ' : '';
			var imgAlign = ($(this).attr('align')) ? 'float:' + $(this).attr('align') + ';' : '';
			var imgHand = ($(this).parent().attr('href')) ? 'cursor:hand;' : '';
			if (this.style.border) {
				prevStyle += 'border:'+this.style.border+';';
				this.style.border = '';
			}
			if (this.style.padding) {
				prevStyle += 'padding:'+this.style.padding+';';
				this.style.padding = '';
			}
			if (this.style.margin) {
				prevStyle += 'margin:'+this.style.margin+';';
				this.style.margin = '';
			}
			var imgStyle = (this.style.cssText);

			strNewHTML += '<span '+imgId+imgClass+imgTitle+imgAlt;
			strNewHTML += 'style="position:relative;white-space:pre-line;display:inline-block;background:transparent;'+imgAlign+imgHand;
			strNewHTML += 'width:' + $(this).width() + 'px;' + 'height:' + $(this).height() + 'px;';
			strNewHTML += 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader' + '(src=\'' + $(this).attr('src') + '\', sizingMethod=\'scale\');';
			strNewHTML += imgStyle+'"></span>';
			if (prevStyle != ''){
				strNewHTML = '<span style="position:relative;display:inline-block;'+prevStyle+imgHand+'width:' + $(this).width() + 'px;' + 'height:' + $(this).height() + 'px;'+'">' + strNewHTML + '</span>';
			}

			$(this).hide();
			$(this).after(strNewHTML);

		});

		$(this).find("*").each(function(){
			var bgIMG = $(this).css('background-image');
			if(bgIMG.indexOf(".png")!=-1){
				var iebg = bgIMG.split('url("')[1].split('")')[0];
				
				$(this).css('background-image', 'none');
				$(this).get(0).runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + iebg + "',sizingMethod='scale')";
			}
		});
		
		$(this).find("input[src$=.png]").each(function() {
			var bgIMG = $(this).attr('src');
			$(this).get(0).runtimeStyle.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader' + '(src=\'' + bgIMG + '\', sizingMethod=\'scale\');';
   		$(this).attr('src', settings.blankgif)
		});
	
	}
	return $;
};
})($);
