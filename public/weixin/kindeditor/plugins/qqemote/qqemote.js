/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/
var emotion_name = new Array();
emotion_name["0"]= "微笑";
emotion_name["1"]= "撇嘴";
emotion_name["2"]= "色";
emotion_name["3"]= "发呆";
emotion_name["4"]= "得意";
emotion_name["5"]= "流泪";
emotion_name["6"]= "害羞";
emotion_name["7"]= "闭嘴";
emotion_name["8"]= "睡";
emotion_name["9"]= "大哭";
emotion_name["10"]= "尴尬";
emotion_name["11"]= "发怒";
emotion_name["12"]= "调皮";
emotion_name["13"]= "呲牙";
emotion_name["14"]= "惊讶";
emotion_name["15"]= "难过";
emotion_name["16"]= "酷";
emotion_name["17"]= "冷汗";
emotion_name["18"]= "抓狂";
emotion_name["19"]= "吐";
emotion_name["20"]= "偷笑";
emotion_name["21"]= "可爱";
emotion_name["22"]= "白眼";
emotion_name["23"]= "傲慢";
emotion_name["24"]= "饥饿";
emotion_name["25"]= "困";
emotion_name["26"]= "惊恐";
emotion_name["27"]= "流汗";
emotion_name["28"]= "憨笑";
emotion_name["29"]= "大兵";
emotion_name["30"]= "奋斗";
emotion_name["31"]= "咒骂";
emotion_name["32"]= "疑问";
emotion_name["33"]= "嘘";
emotion_name["34"]= "晕";
emotion_name["35"]= "折磨";
emotion_name["36"]= "衰";
emotion_name["37"]= "骷髅";
emotion_name["38"]= "敲打";
emotion_name["39"]= "再见";
emotion_name["40"]= "擦汗";
emotion_name["41"]= "抠鼻";
emotion_name["42"]= "鼓掌";
emotion_name["43"]= "糗大了";
emotion_name["44"]= "坏笑";
emotion_name["45"]= "左哼哼";
emotion_name["46"]= "右哼哼";
emotion_name["47"]= "哈欠";
emotion_name["48"]= "鄙视";
emotion_name["49"]= "委屈";
emotion_name["50"]= "快哭了";
emotion_name["51"]= "阴险";
emotion_name["52"]= "亲亲";
emotion_name["53"]= "吓";
emotion_name["54"]= "可怜";
emotion_name["55"]= "菜刀";
emotion_name["56"]= "西瓜";
emotion_name["57"]= "啤酒";
emotion_name["58"]= "篮球";
emotion_name["59"]= "乒乓";
emotion_name["60"]= "咖啡";
emotion_name["61"]= "饭";
emotion_name["62"]= "猪头";
emotion_name["63"]= "玫瑰";
emotion_name["64"]= "凋谢";
emotion_name["65"]= "示爱";
emotion_name["66"]= "爱心";
emotion_name["67"]= "心碎";
emotion_name["68"]= "蛋糕";
emotion_name["69"]= "闪电";
emotion_name["70"]= "炸弹";
emotion_name["71"]= "刀";
emotion_name["72"]= "足球";
emotion_name["73"]= "瓢虫";
emotion_name["74"]= "便便";
emotion_name["75"]= "月亮";
emotion_name["76"]= "太阳";
emotion_name["77"]= "礼物";
emotion_name["78"]= "拥抱";
emotion_name["79"]= "强";
emotion_name["80"]= "弱";
emotion_name["81"]= "握手";
emotion_name["82"]= "胜利";
emotion_name["83"]= "抱拳";
emotion_name["84"]= "勾引";
emotion_name["85"]= "拳头";
emotion_name["86"]= "差劲";
emotion_name["87"]= "爱你";
emotion_name["88"]= "NO";
emotion_name["89"]= "OK";
emotion_name["90"]= "爱情";
emotion_name["91"]= "飞吻";
emotion_name["92"]= "跳跳";
emotion_name["93"]= "发抖";
emotion_name["94"]= "怄火";
emotion_name["95"]= "转圈";
emotion_name["96"]= "磕头";
emotion_name["97"]= "回头";
emotion_name["98"]= "跳绳";
emotion_name["99"]= "挥手";
emotion_name["100"]= "激动";
emotion_name["101"]= "街舞";
emotion_name["102"]= "献吻";
emotion_name["103"]= "右太极";
emotion_name["104"]= "左太极";





                                                                


KindEditor.plugin('qqemote', function(K) {
	var self = this, name = 'qqemote',
		path = SITE_PATH+'public/emoticons/';
		allowPreview = self.allowPreviewEmoticons === undefined ? true : self.allowPreviewEmoticons,
		currentPageNum = 1;
	self.clickToolbar(name, function() {
		var rows = 7, cols = 15, total = 75, startNum = 0,
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
				self.insertHtml('<img src="'+ SITE_URL + "public/emoticons/"  + num + '.gif" border="0" alt="'+emotion_name[num]+'" />').hideMenu().focus();
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
						.css('background-image', 'url(' + path + 'static.gif)');
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

		}
		function createPageTable(currentPageNum) {
		    return;

		}
		createPageTable(currentPageNum);
	});
});
