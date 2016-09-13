/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/

KindEditor.plugin('wxemoticons', function(K) {
	var self = this, name = 'wxemoticons',
		path = SITE_PATH+'public/weixin/static/images/face/';
		allowPreview = self.allowPreviewEmoticons === undefined ? true : self.allowPreviewEmoticons,
		currentPageNum = 1;
    var wxfaces = ["/::)","/::~","/::B","/::|","/:8-)","/::<","/::$","/::X","/::Z","/::'(","/::-|","/::@","/::P","/::D","/::O","/::(","/::+","/:–b","/::Q","/::T","/:,@P","/:,@-D","/::d","/:,@o","/::g","/:|-)","/::!","/::L","/::>","/::,@","/:,@f","/::-S","/:?","/:,@x","/:,@@","/::8","/:,@!","/:!!!","/:xx","/:bye","/:wipe","/:dig","/:handclap","/:&-(","/:B-)","/:<@","/:@>","/::-O","/:>-|","/:P-(","/::'|","/:X-)","/::*","/:@x","/:8*","/:pd","/:<W>","/:beer","/:basketb","/:oo","/:coffee","/:eat","/:pig","/:rose","/:fade","/:showlove","/:heart","/:break","/:cake","/:li","/:bome","/:kn","/:footb","/:ladybug","/:shit","/:moon","/:sun","/:gift","/:hug","/:strong","/:weak","/:share","/:v","/:@)","/:jj","/:@@","/:bad","/:lvu","/:no","/:ok","/:love","/:<L>","/:jump","/:shake","/:<O>","/:circle","/:kotow","/:turn","/:skip","[挥手]","/:#-0","[街舞]","/:kiss","/:<&","/:&>"];
	self.clickToolbar(name, function() {
		var rows = 7, cols = 15, total = 105, startNum = 0,
			cells = rows * cols, pages = Math.ceil(total / cells),
			colsHalf = Math.floor(cols / 2),
			wrapperDiv = K('<div class="ke-plugin-emoticons"></div>'),
			elements = [],
			menu = self.createMenu({
				name : name,
				beforeRemove : function() {
					removeEvent();
				}
			});
		menu.div.append(wrapperDiv);
		var previewDiv, previewImg;
		if (allowPreview) {
			previewDiv = K('<div class="ke-preview"></div>').css('right', 0);
			previewImg = K('<img class="ke-preview-img" src="' + path + startNum + '.gif" />');
			wrapperDiv.append(previewDiv);
			previewDiv.append(previewImg);
		}
		function bindCellEvent(cell, j, num) {
			if (previewDiv) {
				cell.mouseover(function() {
					if (j > colsHalf) {
						previewDiv.css('left', 0);
						previewDiv.css('right', '');
					} else {
						previewDiv.css('left', '');
						previewDiv.css('right', 0);
					}
					previewImg.attr('src', path + num + '.gif');
					K(this).addClass('ke-on');
				});
			} else {
				cell.mouseover(function() {
					K(this).addClass('ke-on');
				});
			}
			cell.mouseout(function() {
				K(this).removeClass('ke-on');
			});
			cell.click(function(e) {
				self.insertHtml('<img src="' + SITE_URL + "public/emoticons/"  + num + '.gif" border="0" alt="'+ wxfaces[num] +'">').hideMenu().focus();
				e.stop();
			});
		}
		function createEmoticonsTable(pageNum, parentDiv) {
			var table = document.createElement('table');
			parentDiv.append(table);
			if (previewDiv) {
				K(table).mouseover(function() {
					previewDiv.show('block');
				});
				K(table).mouseout(function() {
					previewDiv.hide();
				});
				elements.push(K(table));
			}
			table.className = 'ke-table';
			table.cellPadding = 0;
			table.cellSpacing = 0;
			table.border = 0;
			var num = (pageNum - 1) * cells + startNum;
			for (var i = 0; i < rows; i++) {
				var row = table.insertRow(i);
				for (var j = 0; j < cols; j++) {
					var cell = K(row.insertCell(j));
					cell.addClass('ke-cell');
					bindCellEvent(cell, j, num);
					var span = K('<span class="ke-img"></span>')
						.css('background-position', '-' + (24 * num) + 'px 0px')
						.css('background-image', 'url(' + SITE_PATH+'public/weixin/static/images/default.gif)');
					cell.append(span);
					elements.push(cell);
					num++;
				}
			}
			return table;
		}
		var table = createEmoticonsTable(currentPageNum, wrapperDiv);
		function removeEvent() {
			K.each(elements, function() {
				this.unbind();
			});
		}
		var pageDiv;
		function bindPageEvent(el, pageNum) {
			el.click(function(e) {
				removeEvent();
				table.parentNode.removeChild(table);
				pageDiv.remove();
				table = createEmoticonsTable(pageNum, wrapperDiv);
				createPageTable(pageNum);
				currentPageNum = pageNum;
				e.stop();
			});
		}
		function createPageTable(currentPageNum) {
			pageDiv = K('<div class="ke-page"></div>');
			wrapperDiv.append(pageDiv);
			for (var pageNum = 1; pageNum <= pages; pageNum++) {
				if (currentPageNum !== pageNum) {
					var a = K('<a href="javascript:;">[' + pageNum + ']</a>');
					bindPageEvent(a, pageNum);
					pageDiv.append(a);
					elements.push(a);
				} else {
					pageDiv.append(K('@[' + pageNum + ']'));
				}
				pageDiv.append(K('@&nbsp;'));
			}
		}
		createPageTable(currentPageNum);
	});
});
