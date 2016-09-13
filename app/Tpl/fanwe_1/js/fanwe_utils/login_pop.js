  
    (function(window, document, $, undefined){
		
    var _winWidth   = $(window).width(),
        _winHeight  = $(window).height(),
        _globalData = {};
	
    function wx(){}
    window.wx = wx;

    wx.VERSION = "1.4.7";
    //当前页面的module,action和参数
    wx.MODULE  = "";
    wx.ACTION  = "";
    wx.REQUEST = {};
    //用于弹出框的常量值
    wx.BACK    = 0;
    wx.RELOAD  = 1;
    //全局配置信息
    wx.config  = {};
    _browserCheck();

  /**
   * 渲染模板
   * @name    template
   * @param   {String}    模板ID
   * @param   {Object}    数据
   * @return  {String}    渲染好的HTML字符串
  */
  wx.template = function(id, data) {
    if(!window.template)
      return "";
    else
      return wx.trim(window.template(id, (data || {})));
  };

  /**
   * 管道节流，用于mouseover等调用频繁的优化处理
   * @name    throttle
   * @param   {Function}  真正用于执行的方法
   * @param   {Integer}   延时
   * @return  {Function}  节流方法
  */
	wx.throttle = function(fn, timeout) {
    var timer;
    return function(){
        var self = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function(){
            fn.apply(self, args);
        }, timeout);
    };
  };
  
  /**
   * 弹框关闭
   * @name    popClose
  */
  wx.popClose = function() {
    if(_globalData.currentPop)
      _globalData.currentPop.close();
  };
 
  /**
   * 弹框
   * @name    pop
   * @param   {String}    弹出内容
   * @param   {Function}  关闭后的回调方法
   * @param   {Object}    配置选项
   * @return  {String}    pop对象
  */
  wx.pop = function(content, callback, opts) {
    if(!content) return;
    if(!$.isFunction(callback) && $.type(callback) === "object")
      opts = callback;
    opts = opts || {};
    var temp;
    if(/^#/.test(content)){
      if(!$(content).length) return;
      temp = '<div class="pop form" '+(opts.width ? 'style="width:'+opts.width+'"': '')+'><div class="titleWrap"><div class="title" style="cursor: move;">登录<a class="shut-down-icon Js-pop-close"></a></div></div>'+$(content).html()+'</div>';
      if(opts.removeAfterShow)
       $(content).remove();
    } else{
      temp = '<div class="pop form" '+(opts.width ? 'style="width:'+opts.width+'"': '')+'><div class="titleWrap"><div class="title" style="cursor: move;">登录<a class="shut-down-icon Js-pop-close"></a></div></div>'+content+'</div>';
    }
    return _pop(temp,callback,opts);
  };

  //解决弹出模板问题
  function _configTplTranslate(string){
    return string.replace('<%',wx.config.tplOpenTag).replace('%>',wx.config.tplCloseTag);
  }

  //弹框的核心方法
	function _pop(content, callback, opts) {
    if(!$.isFunction(callback) && $.type(callback) === "object")
      opts = callback;
    opts = opts||{};

    if(callback === wx.RELOAD){
      callback = function(){
        location.reload();
      };
    } else if(callback === wx.BACK){
      callback = function(){
        history.back(-1);
      };
    } else if(callback && $.type(callback) === "string"){
      var jumpUrl = callback;
      callback = function(){
        location.href = jumpUrl;
      };
    }
    //立刻执行回调函数，不弹出浮框
    if(opts.notPop){
      callback();
      return;
    }
    $(".Js-pop").stop().remove();
    var htmlText = content;
    var temp = _getShadeLayer("Js-pop")+
                "<div id='Js-pop-body' class='Js-pop pop-container'>"+
                  htmlText+
                "</div>";
    $("body").append(temp).keyup(function(event){
      if(event.keyCode === 27)
        _close();
    });

    $("#Js-pop-body").children().show();
    _setEleToCenter("#Js-pop-body",opts);
    _moveAction(".title","#Js-pop-body");

    function _close(){
      if(opts.attachBg) $("body").css({"overflow":"auto","position":"static","height":"auto"});
      $("body").unbind("keyup");
      $(".Js-pop-close").unbind("click");
      _closeAni("#Js-pop-body",function(){
         $(".Js-pop").hide().remove();
      },opts);
      _globalData.currentPop = null;
    }

    if(opts.layerClick){
      $("#Js-shadeLayer").unbind("click").click(function(){
        _close();
      });
    }
    if(opts.attachBg){
      $("body").css({"overflow":"hidden","position":"relative","height":$(window).height()});
      $("#Js-shadeLayer").css({"width":$(window).width(),"height":$(window).height()});
    }
    _popAni("#Js-pop-body",function(){
      _pluginCheck("#Js-pop-body");
      if($.isFunction(opts.shown)){
        opts.shown();
      }
      if(wx.browser.msie && wx.browser.version === 6){
        if(typeof DD_belatedPNG !== "undefined") DD_belatedPNG.fix('.ie6fixpic');
      }
      $(".Js-pop-close").click(function(){
       _close();
       if($.isFunction(callback))
          callback();
       else if($.isFunction(opts.close))
          opts.close();
      });
      if(opts.autoClose){
        window.setTimeout(function(){
          _close();
        },opts.autoCloseTime || 3000);
      }
    },opts);

    _globalData.currentPop = {
      close : _close,
      open  : function(){
        _pop(htmlText,callback,opts);
      }
    };
    return _globalData.currentPop;
  }

  //弹出效果
  function _popAni(id, callback, opts) {
    if(!$.isFunction(callback) && $.type(callback) === "object")
      opts = callback;
    opts = opts||{};
    var o  = $(id);
    if(opts.notAni){
      o.show();
      if($.isFunction(callback))
        callback();
    } else {
      var top = parseInt(o.css("top").slice(0,-2));
      o.css("opacity",0);
      o.stop().animate({"opacity":1,"top":top+30},400,$.isFunction(callback)?callback:undefined);
    }
  }

  //弹出关闭
  function _closeAni(id, callback, opts) {
    if(!$.isFunction(callback) && $.type(callback) === "object")
      opts = callback;
    opts = opts||{};
    var o = $(id);
    if(opts.notAni){
      $("#Js-shadeLayer").css("opacity",0);
      o.css("opacity",0);
      if(callback)
        callback();
    } else {
      var top = parseInt(o.css("top").slice(0,-2));
      $("#Js-shadeLayer").animate({"opacity":0},200);
      o.stop().animate({"opacity":0,"top":top-30},300,callback);
    }
  }
 //改变窗口重新获取大小
  $(window).bind("resize",function(){
	 _getShadeLayer("Js-pop");
	 
});
  //将元素设置为居中
  function _setEleToCenter(eleId, opts) {
    opts = opts || {};
    var y      = opts.offsetY || -150,
        $ele   = $(eleId),
        width  = $ele.width(),
        height = $ele.height();

    if((wx.browser.msie && wx.browser.version <= 7) || opts.scrollFollow){
      y += $(document).scrollTop()+_winHeight/2-height/2;
      $ele.css("position","absolute");
    } else {
      y += _winHeight/2-height/2;
      $ele.css("position","fixed");
    }
    $ele.css({"top" : opts.y || (y<0 ? 10 : y),
              "left": opts.x || (_winWidth/2-width/2+(opts.offsetX||0)) });
  }

  //使元素可拖拽移动
  function _moveAction(moveBar, moveBody) {
    var isMove      = false,
        lastX       = -1,
        lastY       = -1,
        offsetX     = -1,
        offsetY     = -1,
        $winBody    = $("body"),
        $moveBar    = $(moveBar),
        $moveBody   = $(moveBody),
        isAbsoluate = $moveBody.css("position") === "absolute" ? true : false;

    if($moveBar.length === 0 || $moveBody.length === 0) return;
    $moveBar.css("cursor","move").unbind("mousedown").
      bind("mousedown",function(event){
        event.preventDefault();
        var body  = $moveBody,
            tempX = body.offset().left,
            tempY = body.offset().top - (isAbsoluate ? 0 : $(document).scrollTop());
        isMove  = true;
        lastX   = event.clientX;
        lastY   = event.clientY;
        offsetX = event.clientX - tempX;
        offsetY = event.clientY - tempY;
        $winBody.unbind("mousemove").bind("mousemove",function(event){
            if(!isMove) return false;
            event.preventDefault();
            event.stopPropagation();
            lastX = event.clientX - lastX;
            lastY = event.clientY - lastY;
            body.css({"left" : event.clientX-lastX-offsetX,"top" : event.clientY-lastY-offsetY});
            lastX = event.clientX;
            lastY = event.clientY;
        });
    }).unbind("mouseup").bind("mouseup",function(event){
        isMove = false;
        $winBody.unbind("mousemove");
    });
    $winBody.unbind("mouseup").bind("mouseup",function(){
        isMove = false;
    });
    $moveBar.blur(function(){
        isMove = false;
        $winBody.unbind("mousemove");
    });
  }

  //获得蒙版层
  function _getShadeLayer(layerClass) {
    var window_height = $('body').outerHeight() > _winHeight?$('body').outerHeight() : _winHeight;
	//console.log(_winWidth+","+ _winHeight);
    /*return '<div id="Js-shadeLayer" class="'+layerClass+' pop-bg" style="width:'+_winWidth+'px;height:'+window_height+'px;"></div>';*/
	return '<div id="Js-shadeLayer" class="'+layerClass+' pop-bg"></div>';
  }
 

  /**
   * 模板引擎
   * @name    tpl
   * @param   {String}  所要使用的模板，可以是id也可以是字符串
   * @param   {String}  需要结合的数据
   * @param   {String}  模板和数据结合后将append到这个元素里
  */
  wx.tpl = function(template,data,appendEle){
    wx.tpl.cache = wx.tpl.cache || {};
    if(!wx.tpl.cache[template]){
      var content    = template,
          match      = null,
          lastcursor = 0,
          codeStart  = 'var c = [];',
          codeEnd    = 'return c.join("");',
          param      = "",
          compileTpl = "",
          checkEXP   = /(^( )?(if|for|else|switch|case|continue|break|{|}))(.*)?/g,
          searchEXP  = new RegExp(wx.config.tplOpenTag+"(.*?)"+wx.config.tplCloseTag+"?","g"),
          replaceEXP = /[^\w$]+/g;

      if(template.charAt(0) === "#")
        content = $(template).html();
      else
        content = template;

      while(match = searchEXP.exec(content)){
        var b = RegExp.$1;
        var c = content.substring(lastcursor,match.index);
        c = _formatString(c);
        compileTpl += 'c.push("'+c+'");\n';
        if(checkEXP.test(b)){
          compileTpl += b;
        }
        else{
          compileTpl += 'c.push('+b+');\n';
        }
        _setVar(b);
        lastcursor = match.index+match[0].length;
      }
      compileTpl+= 'c.push("'+wx.trim(_formatString(content.substring(lastcursor)))+'");';
      wx.tpl.cache[template] = new Function('data','helper',param+codeStart+compileTpl+codeEnd);
    }

    var result = wx.tpl.cache[template].call(null,data,wx.tpl.helperList);
    if(appendEle){
     $(appendEle).append(result);
    }

    function _formatString(s){
      return s.replace(/^\s*|\s*$/gm, '').replace(/[\n\r\t\s]+/g, ' ').replace(/"/gm,'\\"');
    }

    function _setVar(code){
      code = code.replace(replaceEXP,',').split(',');
      for(var i=0,l=code.length;i<l;i++){
        code[i] = code[i].replace(checkEXP,'');
        if(!code[i].length || /^\d+$/.test(code[i])) continue;
        if(wx.tpl.helperList && code[i] in wx.tpl.helperList)
          param += code[i]+' = helper.'+code[i]+';';
        else
          param += 'var '+code[i]+' = data.'+code[i]+';';
      }
    }
    return result;
  };

  //浏览器类型
  function _browserCheck(){
    wx.browser = wx.browser || {version:0};
    var ua = navigator.userAgent.toLowerCase(),
      msie = ua.indexOf("compatible") !== -1 && ua.indexOf("msie") !== -1;

    if(msie){
      wx.browser.msie = true;
      /msie (\d+\.\d+);/.test(ua);
      wx.browser.version = parseInt(RegExp.$1);
    }
  }

  //插件检测
  function _pluginCheck(context){
    var $body = $(context || "body");
  }
})(window, document, jQuery);

window.zc = {};
var loginRegisterJump = "";
function loginDialog(loginTpl) {
    var loginTpl = loginTpl;
    $(".Js-showLogin").live("click", function () {
        show_login();
    });

    function show_login(callback) {
    	if(!zc.loginCallback){
    		zc.loginCallback = callback;
    	}
        loginRegisterJump = loginRegisterJump || $(this).attr('data-jump');
        zc.lastpopid = wx.pop(loginTpl, {shown: loginTplCallback});
    }
    zc.show_login = show_login;

    $(".Js-pop-close").live("click", function () {
        wx.popClose();
    });
    
    function loginTplCallback() {
      $('.zc').unbind('click').click(function (e) {
          beforeSubmit(e,'登录','loginForm');
      });
    }
}
