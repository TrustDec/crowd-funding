(function (window) {

    function WYPLUS_CTL(options){
    }

    var bodyEl = document.body || document.getElementsByTagName('body')[0];

    WYPLUS_CTL.prototype = {

        constructor: WYPLUS_CTL,

        init: function(options){
            this.options = options;
            this.iframe = getById(this.options.iframeId);
        },

        //打开网银+ 界面
        open: function(options){

            if ( !this.options ) {
                this.init(options);
            } 

            this.setStyle(this.options.iframeId);
            this.iframe.style.display = 'block';

            bodyEl.style.overflowY = 'hidden';           
        },

        //设置页内样式
        setStyle: function(iframeId, cssText){

            if ( this.setStyleDone ) return;

            var headEl = document.head || document.getElementsByTagName('head')[0],
                styleEl = document.createElement('style'),
                cssText = cssText || 'html,body{height:100%;}';

            styleEl.type = 'text/css';

            if (styleEl.styleSheet){
              styleEl.styleSheet.cssText = cssText;
            } else {
              styleEl.appendChild(document.createTextNode(cssText));
            }

            headEl.appendChild(styleEl);

            this.setStyleDone = true;
        },

        //关闭网银+ 界面
        close: function(){
            this.iframe.src = '';
            this.iframe.style.display = 'none';
            bodyEl.style.overflowY = '';           
        },

        //跳转页面
        redirect: function(url){
            window.location.href = url;
        },

        //重新付款
        repay: function(){
            this.options.formId && getById(this.options.formId).submit();   
        }
    };

    function getById(id) {
        return document.getElementById(id);
    }

    window.WYPLUS = new WYPLUS_CTL();

})(window);