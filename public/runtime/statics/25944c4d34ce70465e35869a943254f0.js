/*!
 * =====================================================
 * SUI Mobile - http://m.sui.taobao.org/
 *
 * =====================================================
 */
;$.smVersion = "0.5.9";/* global Zepto:true */
+function ($) {
    "use strict";

    //全局配置
    var defaults = {
        autoInit: false, //自动初始化页面
        showPageLoadingIndicator: true, //push.js加载页面的时候显示一个加载提示
        router: true, //默认使用router
        swipePanel: "left", //滑动打开侧栏
        swipePanelOnlyClose: true,  //只允许滑动关闭，不允许滑动打开侧栏
        pushAnimationDuration: 400  //不要动这个，这是解决安卓 animationEnd 事件无法触发的bug
    };

    $.smConfig = $.extend(defaults, $.config);

}(Zepto);

/* global Zepto:true */
+ function($) {
    "use strict";

    //比较一个字符串版本号
    //a > b === 1
    //a = b === 0
    //a < b === -1
    $.compareVersion = function(a, b) {
        var as = a.split('.');
        var bs = b.split('.');
        if (a === b) return 0;

        for (var i = 0; i < as.length; i++) {
            var x = parseInt(as[i]);
            if (!bs[i]) return 1;
            var y = parseInt(bs[i]);
            if (x < y) return -1;
            if (x > y) return 1;
        }
        return -1;
    };

    $.getCurrentPage = function() {
        return $(".page-current")[0] || $(".page")[0] || document.body;
    };

}(Zepto);

/* global Zepto:true */
/* global WebKitCSSMatrix:true */

(function($) {
    "use strict";
    ['width', 'height'].forEach(function(dimension) {
        var  Dimension = dimension.replace(/./, function(m) {
            return m[0].toUpperCase();
        });
        $.fn['outer' + Dimension] = function(margin) {
            var elem = this;
            if (elem) {
                var size = elem[dimension]();
                var sides = {
                    'width': ['left', 'right'],
                    'height': ['top', 'bottom']
                };
                sides[dimension].forEach(function(side) {
                    if (margin) size += parseInt(elem.css('margin-' + side), 10);
                });
                return size;
            } else {
                return null;
            }
        };
    });


    //support
    $.support = (function() {
        var support = {
            touch: !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch)
        };
        return support;
    })();

    $.touchEvents = {
        start: $.support.touch ? 'touchstart' : 'mousedown',
        move: $.support.touch ? 'touchmove' : 'mousemove',
        end: $.support.touch ? 'touchend' : 'mouseup'
    };

    $.getTranslate = function (el, axis) {
        var matrix, curTransform, curStyle, transformMatrix;

        // automatic axis detection
        if (typeof axis === 'undefined') {
            axis = 'x';
        }

        curStyle = window.getComputedStyle(el, null);
        if (window.WebKitCSSMatrix) {
            // Some old versions of Webkit choke when 'none' is passed; pass
            // empty string instead in this case
            transformMatrix = new WebKitCSSMatrix(curStyle.webkitTransform === 'none' ? '' : curStyle.webkitTransform);
        }
        else {
            transformMatrix = curStyle.MozTransform || curStyle.OTransform || curStyle.MsTransform || curStyle.msTransform  || curStyle.transform || curStyle.getPropertyValue('transform').replace('translate(', 'matrix(1, 0, 0, 1,');
            matrix = transformMatrix.toString().split(',');
        }

        if (axis === 'x') {
            //Latest Chrome and webkits Fix
            if (window.WebKitCSSMatrix)
                curTransform = transformMatrix.m41;
            //Crazy IE10 Matrix
            else if (matrix.length === 16)
                curTransform = parseFloat(matrix[12]);
            //Normal Browsers
            else
                curTransform = parseFloat(matrix[4]);
        }
        if (axis === 'y') {
            //Latest Chrome and webkits Fix
            if (window.WebKitCSSMatrix)
                curTransform = transformMatrix.m42;
            //Crazy IE10 Matrix
            else if (matrix.length === 16)
                curTransform = parseFloat(matrix[13]);
            //Normal Browsers
            else
                curTransform = parseFloat(matrix[5]);
        }

        return curTransform || 0;
    };
    $.requestAnimationFrame = function (callback) {
        if (window.requestAnimationFrame) return window.requestAnimationFrame(callback);
        else if (window.webkitRequestAnimationFrame) return window.webkitRequestAnimationFrame(callback);
        else if (window.mozRequestAnimationFrame) return window.mozRequestAnimationFrame(callback);
        else {
            return window.setTimeout(callback, 1000 / 60);
        }
    };

    $.cancelAnimationFrame = function (id) {
        if (window.cancelAnimationFrame) return window.cancelAnimationFrame(id);
        else if (window.webkitCancelAnimationFrame) return window.webkitCancelAnimationFrame(id);
        else if (window.mozCancelAnimationFrame) return window.mozCancelAnimationFrame(id);
        else {
            return window.clearTimeout(id);
        }
    };


    $.fn.transitionEnd = function(callback) {
        var events = ['webkitTransitionEnd', 'transitionend'],
            i, dom = this;

        function fireCallBack(e) {
            /*jshint validthis:true */
            if (e.target !== this) return;
            callback.call(this, e);
            for (i = 0; i < events.length; i++) {
                dom.off(events[i], fireCallBack);
            }
        }
        if (callback) {
            for (i = 0; i < events.length; i++) {
                dom.on(events[i], fireCallBack);
            }
        }
        return this;
    };
    $.fn.dataset = function() {
        var el = this[0];
        if (el) {
            var dataset = {};
            if (el.dataset) {

                for (var dataKey in el.dataset) { // jshint ignore:line
                    dataset[dataKey] = el.dataset[dataKey];
                }
            } else {
                for (var i = 0; i < el.attributes.length; i++) {
                    var attr = el.attributes[i];
                    if (/^data-/.test(attr.name)) {
                        dataset[$.toCamelCase(attr.name.split('data-')[1])] = attr.value;
                    }
                }
            }
            for (var key in dataset) {
                if (dataset[key] === 'false') dataset[key] = false;
                else if (dataset[key] === 'true') dataset[key] = true;
                else if (parseFloat(dataset[key]) === dataset[key] * 1) dataset[key] = dataset[key] * 1;
            }
            return dataset;
        } else return undefined;
    };
    $.fn.data = function(key, value) {
        if (typeof key === 'undefined') {
            return $(this).dataset();
        }
        if (typeof value === 'undefined') {
            // Get value
            if (this[0] && this[0].getAttribute) {
                var dataKey = this[0].getAttribute('data-' + key);

                if (dataKey) {
                    return dataKey;
                } else if (this[0].smElementDataStorage && (key in this[0].smElementDataStorage)) {


                    return this[0].smElementDataStorage[key];

                } else {
                    return undefined;
                }
            } else return undefined;

        } else {
            // Set value
            for (var i = 0; i < this.length; i++) {
                var el = this[i];
                if (!el.smElementDataStorage) el.smElementDataStorage = {};
                el.smElementDataStorage[key] = value;
            }
            return this;
        }
    };
    $.fn.animationEnd = function(callback) {
        var events = ['webkitAnimationEnd', 'animationend'],
            i, dom = this;

        function fireCallBack(e) {
            callback(e);
            for (i = 0; i < events.length; i++) {
                dom.off(events[i], fireCallBack);
            }
        }
        if (callback) {
            for (i = 0; i < events.length; i++) {
                dom.on(events[i], fireCallBack);
            }
        }
        return this;
    };
    $.fn.transition = function(duration) {
        if (typeof duration !== 'string') {
            duration = duration + 'ms';
        }
        for (var i = 0; i < this.length; i++) {
            var elStyle = this[i].style;
            elStyle.webkitTransitionDuration = elStyle.MsTransitionDuration = elStyle.msTransitionDuration = elStyle.MozTransitionDuration = elStyle.OTransitionDuration = elStyle.transitionDuration = duration;
        }
        return this;
    };
    $.fn.transform = function(transform) {
        for (var i = 0; i < this.length; i++) {
            var elStyle = this[i].style;
            elStyle.webkitTransform = elStyle.MsTransform = elStyle.msTransform = elStyle.MozTransform = elStyle.OTransform = elStyle.transform = transform;
        }
        return this;
    };
    $.fn.prevAll = function (selector) {
        var prevEls = [];
        var el = this[0];
        if (!el) return $([]);
        while (el.previousElementSibling) {
            var prev = el.previousElementSibling;
            if (selector) {
                if($(prev).is(selector)) prevEls.push(prev);
            }
            else prevEls.push(prev);
            el = prev;
        }
        return $(prevEls);
    };
    $.fn.nextAll = function (selector) {
        var nextEls = [];
        var el = this[0];
        if (!el) return $([]);
        while (el.nextElementSibling) {
            var next = el.nextElementSibling;
            if (selector) {
                if($(next).is(selector)) nextEls.push(next);
            }
            else nextEls.push(next);
            el = next;
        }
        return $(nextEls);
    };

    //重置zepto的show方法，防止有些人引用的版本中 show 方法操作 opacity 属性影响动画执行
    $.fn.show = function(){
        var elementDisplay = {};
        function defaultDisplay(nodeName) {
            var element, display;
            if (!elementDisplay[nodeName]) {
                element = document.createElement(nodeName);
                document.body.appendChild(element);
                display = getComputedStyle(element, '').getPropertyValue("display");
                element.parentNode.removeChild(element);
                display === "none" && (display = "block");
                elementDisplay[nodeName] = display;
            }
            return elementDisplay[nodeName];
        }

        return this.each(function(){
            this.style.display === "none" && (this.style.display = '');
            if (getComputedStyle(this, '').getPropertyValue("display") === "none");
            this.style.display = defaultDisplay(this.nodeName);
        });
    };
})(Zepto);

/*===========================
Device/OS Detection
===========================*/
/* global Zepto:true */
;(function ($) {
    "use strict";
    var device = {};
    var ua = navigator.userAgent;

    var android = ua.match(/(Android);?[\s\/]+([\d.]+)?/);
    var ipad = ua.match(/(iPad).*OS\s([\d_]+)/);
    var ipod = ua.match(/(iPod)(.*OS\s([\d_]+))?/);
    var iphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/);

    device.ios = device.android = device.iphone = device.ipad = device.androidChrome = false;

    // Android
    if (android) {
        device.os = 'android';
        device.osVersion = android[2];
        device.android = true;
        device.androidChrome = ua.toLowerCase().indexOf('chrome') >= 0;
    }
    if (ipad || iphone || ipod) {
        device.os = 'ios';
        device.ios = true;
    }
    // iOS
    if (iphone && !ipod) {
        device.osVersion = iphone[2].replace(/_/g, '.');
        device.iphone = true;
    }
    if (ipad) {
        device.osVersion = ipad[2].replace(/_/g, '.');
        device.ipad = true;
    }
    if (ipod) {
        device.osVersion = ipod[3] ? ipod[3].replace(/_/g, '.') : null;
        device.iphone = true;
    }
    // iOS 8+ changed UA
    if (device.ios && device.osVersion && ua.indexOf('Version/') >= 0) {
        if (device.osVersion.split('.')[0] === '10') {
            device.osVersion = ua.toLowerCase().split('version/')[1].split(' ')[0];
        }
    }

    // Webview
    device.webView = (iphone || ipad || ipod) && ua.match(/.*AppleWebKit(?!.*Safari)/i);

    // Minimal UI
    if (device.os && device.os === 'ios') {
        var osVersionArr = device.osVersion.split('.');
        device.minimalUi = !device.webView &&
            (ipod || iphone) &&
            (osVersionArr[0] * 1 === 7 ? osVersionArr[1] * 1 >= 1 : osVersionArr[0] * 1 > 7) &&
            $('meta[name="viewport"]').length > 0 && $('meta[name="viewport"]').attr('content').indexOf('minimal-ui') >= 0;
    }

    // Check for status bar and fullscreen app mode
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    device.statusBar = false;
    if (device.webView && (windowWidth * windowHeight === screen.width * screen.height)) {
        device.statusBar = true;
    }
    else {
        device.statusBar = false;
    }

    // Classes
    var classNames = [];

    // Pixel Ratio
    device.pixelRatio = window.devicePixelRatio || 1;
    classNames.push('pixel-ratio-' + Math.floor(device.pixelRatio));
    if (device.pixelRatio >= 2) {
        classNames.push('retina');
    }

    // OS classes
    if (device.os) {
        classNames.push(device.os, device.os + '-' + device.osVersion.split('.')[0], device.os + '-' + device.osVersion.replace(/\./g, '-'));
        if (device.os === 'ios') {
            var major = parseInt(device.osVersion.split('.')[0], 10);
            for (var i = major - 1; i >= 6; i--) {
                classNames.push('ios-gt-' + i);
            }
        }

    }
    // Status bar classes
    if (device.statusBar) {
        classNames.push('with-statusbar-overlay');
    }
    else {
        $('html').removeClass('with-statusbar-overlay');
    }

    // Add html classes
    if (classNames.length > 0) $('html').addClass(classNames.join(' '));

    // keng..
    device.isWeixin = /MicroMessenger/i.test(ua);

    $.device = device;
})(Zepto);

;(function () {
    'use strict';

    /**
     * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
     *
     * @codingstandard ftlabs-jsv2
     * @copyright The Financial Times Limited [All Rights Reserved]
     * @license MIT License (see LICENSE.txt)
     */

    /*jslint browser:true, node:true, elision:true*/
    /*global Event, Node*/


    /**
     * Instantiate fast-clicking listeners on the specified layer.
     *
     * @constructor
     * @param {Element} layer The layer to listen on
     * @param {Object} [options={}] The options to override the defaults
     */
    function FastClick(layer, options) {
        var oldOnClick;

        options = options || {};

        /**
         * Whether a click is currently being tracked.
         *
         * @type boolean
         */
        this.trackingClick = false;


        /**
         * Timestamp for when click tracking started.
         *
         * @type number
         */
        this.trackingClickStart = 0;


        /**
         * The element being tracked for a click.
         *
         * @type EventTarget
         */
        this.targetElement = null;


        /**
         * X-coordinate of touch start event.
         *
         * @type number
         */
        this.touchStartX = 0;


        /**
         * Y-coordinate of touch start event.
         *
         * @type number
         */
        this.touchStartY = 0;


        /**
         * ID of the last touch, retrieved from Touch.identifier.
         *
         * @type number
         */
        this.lastTouchIdentifier = 0;


        /**
         * Touchmove boundary, beyond which a click will be cancelled.
         *
         * @type number
         */
        this.touchBoundary = options.touchBoundary || 10;


        /**
         * The FastClick layer.
         *
         * @type Element
         */
        this.layer = layer;

        /**
         * The minimum time between tap(touchstart and touchend) events
         *
         * @type number
         */
        this.tapDelay = options.tapDelay || 200;

        /**
         * The maximum time for a tap
         *
         * @type number
         */
        this.tapTimeout = options.tapTimeout || 700;

        if (FastClick.notNeeded(layer)) {
            return;
        }

        // Some old versions of Android don't have Function.prototype.bind
        function bind(method, context) {
            return function() { return method.apply(context, arguments); };
        }


        var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
        var context = this;
        for (var i = 0, l = methods.length; i < l; i++) {
            context[methods[i]] = bind(context[methods[i]], context);
        }

        // Set up event handlers as required
        if (deviceIsAndroid) {
            layer.addEventListener('mouseover', this.onMouse, true);
            layer.addEventListener('mousedown', this.onMouse, true);
            layer.addEventListener('mouseup', this.onMouse, true);
        }

        layer.addEventListener('click', this.onClick, true);
        layer.addEventListener('touchstart', this.onTouchStart, false);
        layer.addEventListener('touchmove', this.onTouchMove, false);
        layer.addEventListener('touchend', this.onTouchEnd, false);
        layer.addEventListener('touchcancel', this.onTouchCancel, false);

        // Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
        // which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
        // layer when they are cancelled.
        if (!Event.prototype.stopImmediatePropagation) {
            layer.removeEventListener = function(type, callback, capture) {
                var rmv = Node.prototype.removeEventListener;
                if (type === 'click') {
                    rmv.call(layer, type, callback.hijacked || callback, capture);
                } else {
                    rmv.call(layer, type, callback, capture);
                }
            };

            layer.addEventListener = function(type, callback, capture) {
                var adv = Node.prototype.addEventListener;
                if (type === 'click') {
                    adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
                        if (!event.propagationStopped) {
                            callback(event);
                        }
                    }), capture);
                } else {
                    adv.call(layer, type, callback, capture);
                }
            };
        }

        // If a handler is already declared in the element's onclick attribute, it will be fired before
        // FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
        // adding it as listener.
        if (typeof layer.onclick === 'function') {

            // Android browser on at least 3.2 requires a new reference to the function in layer.onclick
            // - the old one won't work if passed to addEventListener directly.
            oldOnClick = layer.onclick;
            layer.addEventListener('click', function(event) {
                oldOnClick(event);
            }, false);
            layer.onclick = null;
        }
    }

    /**
     * Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
     *
     * @type boolean
     */
    var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

    /**
     * Android requires exceptions.
     *
     * @type boolean
     */
    var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


    /**
     * iOS requires exceptions.
     *
     * @type boolean
     */
    var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


    /**
     * iOS 4 requires an exception for select elements.
     *
     * @type boolean
     */
    var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


    /**
     * iOS 6.0-7.* requires the target element to be manually derived
     *
     * @type boolean
     */
    var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

    /**
     * BlackBerry requires exceptions.
     *
     * @type boolean
     */
    var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

    /**
     * 判断是否组合型label
     * @type {Boolean}
     */
    var isCompositeLabel = false;

    /**
     * Determine whether a given element requires a native click.
     *
     * @param {EventTarget|Element} target Target DOM element
     * @returns {boolean} Returns true if the element needs a native click
     */
    FastClick.prototype.needsClick = function(target) {

        // 修复bug: 如果父元素中有 label
        // 如果label上有needsclick这个类，则用原生的点击，否则，用模拟点击
        var parent = target;
        while(parent && (parent.tagName.toUpperCase() !== "BODY")) {
            if (parent.tagName.toUpperCase() === "LABEL") {
                isCompositeLabel = true;
                if ((/\bneedsclick\b/).test(parent.className)) return true;
            }
            parent = parent.parentNode;
        }

        switch (target.nodeName.toLowerCase()) {

            // Don't send a synthetic click to disabled inputs (issue #62)
            case 'button':
            case 'select':
            case 'textarea':
                if (target.disabled) {
                    return true;
                }

                break;
            case 'input':

                // File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
                if ((deviceIsIOS && target.type === 'file') || target.disabled) {
                    return true;
                }

                break;
            case 'label':
            case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
            case 'video':
                return true;
        }

        return (/\bneedsclick\b/).test(target.className);
    };


    /**
     * Determine whether a given element requires a call to focus to simulate click into element.
     *
     * @param {EventTarget|Element} target Target DOM element
     * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
     */
    FastClick.prototype.needsFocus = function(target) {
        switch (target.nodeName.toLowerCase()) {
            case 'textarea':
                return true;
            case 'select':
                return !deviceIsAndroid;
            case 'input':
                switch (target.type) {
                    case 'button':
                    case 'checkbox':
                    case 'file':
                    case 'image':
                    case 'radio':
                    case 'submit':
                        return false;
                }

                // No point in attempting to focus disabled inputs
                return !target.disabled && !target.readOnly;
            default:
                return (/\bneedsfocus\b/).test(target.className);
        }
    };


    /**
     * Send a click event to the specified element.
     *
     * @param {EventTarget|Element} targetElement
     * @param {Event} event
     */
    FastClick.prototype.sendClick = function(targetElement, event) {
        var clickEvent, touch;

        // On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
        if (document.activeElement && document.activeElement !== targetElement) {
            document.activeElement.blur();
        }

        touch = event.changedTouches[0];

        // Synthesise a click event, with an extra attribute so it can be tracked
        clickEvent = document.createEvent('MouseEvents');
        clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
        clickEvent.forwardedTouchEvent = true;
        targetElement.dispatchEvent(clickEvent);
    };

    FastClick.prototype.determineEventType = function(targetElement) {

        //Issue #159: Android Chrome Select Box does not open with a synthetic click event
        if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
            return 'mousedown';
        }

        return 'click';
    };


    /**
     * @param {EventTarget|Element} targetElement
     */
    FastClick.prototype.focus = function(targetElement) {
        var length;

        // Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
        var unsupportedType = ['date', 'time', 'month', 'number', 'email'];
        if (deviceIsIOS && targetElement.setSelectionRange && unsupportedType.indexOf(targetElement.type) === -1) {
            length = targetElement.value.length;
            targetElement.setSelectionRange(length, length);
        } else {
            targetElement.focus();
        }
    };


    /**
     * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
     *
     * @param {EventTarget|Element} targetElement
     */
    FastClick.prototype.updateScrollParent = function(targetElement) {
        var scrollParent, parentElement;

        scrollParent = targetElement.fastClickScrollParent;

        // Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
        // target element was moved to another parent.
        if (!scrollParent || !scrollParent.contains(targetElement)) {
            parentElement = targetElement;
            do {
                if (parentElement.scrollHeight > parentElement.offsetHeight) {
                    scrollParent = parentElement;
                    targetElement.fastClickScrollParent = parentElement;
                    break;
                }

                parentElement = parentElement.parentElement;
            } while (parentElement);
        }

        // Always update the scroll top tracker if possible.
        if (scrollParent) {
            scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
        }
    };


    /**
     * @param {EventTarget} targetElement
     * @returns {Element|EventTarget}
     */
    FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {

        // On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
        if (eventTarget.nodeType === Node.TEXT_NODE) {
            return eventTarget.parentNode;
        }

        return eventTarget;
    };


    /**
     * On touch start, record the position and scroll offset.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchStart = function(event) {
        var targetElement, touch, selection;

        // Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
        if (event.targetTouches.length > 1) {
            return true;
        }

        targetElement = this.getTargetElementFromEventTarget(event.target);
        touch = event.targetTouches[0];

        if (deviceIsIOS) {

            // Only trusted events will deselect text on iOS (issue #49)
            selection = window.getSelection();
            if (selection.rangeCount && !selection.isCollapsed) {
                return true;
            }

            if (!deviceIsIOS4) {

                // Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
                // when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
                // with the same identifier as the touch event that previously triggered the click that triggered the alert.
                // Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
                // immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
                // Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
                // which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
                // random integers, it's safe to to continue if the identifier is 0 here.
                if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
                    event.preventDefault();
                    return false;
                }

                this.lastTouchIdentifier = touch.identifier;

                // If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
                // 1) the user does a fling scroll on the scrollable layer
                // 2) the user stops the fling scroll with another tap
                // then the event.target of the last 'touchend' event will be the element that was under the user's finger
                // when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
                // is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
                this.updateScrollParent(targetElement);
            }
        }

        this.trackingClick = true;
        this.trackingClickStart = event.timeStamp;
        this.targetElement = targetElement;

        this.touchStartX = touch.pageX;
        this.touchStartY = touch.pageY;

        // Prevent phantom clicks on fast double-tap (issue #36)
        if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
            event.preventDefault();
        }

        return true;
    };


    /**
     * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.touchHasMoved = function(event) {
        var touch = event.changedTouches[0], boundary = this.touchBoundary;

        if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
            return true;
        }

        return false;
    };


    /**
     * Update the last position.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchMove = function(event) {
        if (!this.trackingClick) {
            return true;
        }

        // If the touch has moved, cancel the click tracking
        if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
            this.trackingClick = false;
            this.targetElement = null;
        }

        return true;
    };


    /**
     * Attempt to find the labelled control for the given label element.
     *
     * @param {EventTarget|HTMLLabelElement} labelElement
     * @returns {Element|null}
     */
    FastClick.prototype.findControl = function(labelElement) {

        // Fast path for newer browsers supporting the HTML5 control attribute
        if (labelElement.control !== undefined) {
            return labelElement.control;
        }

        // All browsers under test that support touch events also support the HTML5 htmlFor attribute
        if (labelElement.htmlFor) {
            return document.getElementById(labelElement.htmlFor);
        }

        // If no for attribute exists, attempt to retrieve the first labellable descendant element
        // the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
        return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
    };


    /**
     * On touch end, determine whether to send a click event at once.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchEnd = function(event) {
        var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

        if (!this.trackingClick) {
            return true;
        }

        // Prevent phantom clicks on fast double-tap (issue #36)
        if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
            this.cancelNextClick = true;
            return true;
        }

        if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
            return true;
        }
        //修复安卓微信下，input type="date" 的bug，经测试date,time,month已没问题
        var unsupportedType = ['date', 'time', 'month'];
        if(unsupportedType.indexOf(event.target.type) !== -1){
            　　　　return false;
            　　}
        // Reset to prevent wrong click cancel on input (issue #156).
        this.cancelNextClick = false;

        this.lastClickTime = event.timeStamp;

        trackingClickStart = this.trackingClickStart;
        this.trackingClick = false;
        this.trackingClickStart = 0;

        // On some iOS devices, the targetElement supplied with the event is invalid if the layer
        // is performing a transition or scroll, and has to be re-detected manually. Note that
        // for this to function correctly, it must be called *after* the event target is checked!
        // See issue #57; also filed as rdar://13048589 .
        if (deviceIsIOSWithBadTarget) {
            touch = event.changedTouches[0];

            // In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
            targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
            targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
        }

        targetTagName = targetElement.tagName.toLowerCase();
        if (targetTagName === 'label') {
            forElement = this.findControl(targetElement);
            if (forElement) {
                this.focus(targetElement);
                if (deviceIsAndroid) {
                    return false;
                }

                targetElement = forElement;
            }
        } else if (this.needsFocus(targetElement)) {

            // Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
            // Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
            if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
                this.targetElement = null;
                return false;
            }

            this.focus(targetElement);
            this.sendClick(targetElement, event);

            // Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
            // Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
            if (!deviceIsIOS || targetTagName !== 'select') {
                this.targetElement = null;
                event.preventDefault();
            }

            return false;
        }

        if (deviceIsIOS && !deviceIsIOS4) {

            // Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
            // and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
            scrollParent = targetElement.fastClickScrollParent;
            if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
                return true;
            }
        }

        // Prevent the actual click from going though - unless the target node is marked as requiring
        // real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
        if (!this.needsClick(targetElement)) {
            event.preventDefault();
            this.sendClick(targetElement, event);
        }

        return false;
    };


    /**
     * On touch cancel, stop tracking the click.
     *
     * @returns {void}
     */
    FastClick.prototype.onTouchCancel = function() {
        this.trackingClick = false;
        this.targetElement = null;
    };


    /**
     * Determine mouse events which should be permitted.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onMouse = function(event) {

        // If a target element was never set (because a touch event was never fired) allow the event
        if (!this.targetElement) {
            return true;
        }

        if (event.forwardedTouchEvent) {
            return true;
        }

        // Programmatically generated events targeting a specific element should be permitted
        if (!event.cancelable) {
            return true;
        }

        // Derive and check the target element to see whether the mouse event needs to be permitted;
        // unless explicitly enabled, prevent non-touch click events from triggering actions,
        // to prevent ghost/doubleclicks.
        if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

            // Prevent any user-added listeners declared on FastClick element from being fired.
            if (event.stopImmediatePropagation) {
                event.stopImmediatePropagation();
            } else {

                // Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
                event.propagationStopped = true;
            }

            // Cancel the event
            event.stopPropagation();
            // 允许组合型label冒泡
            if (!isCompositeLabel) {
                event.preventDefault();
            }
            // 允许组合型label冒泡
            return false;
        }

        // If the mouse event is permitted, return true for the action to go through.
        return true;
    };


    /**
     * On actual clicks, determine whether this is a touch-generated click, a click action occurring
     * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
     * an actual click which should be permitted.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onClick = function(event) {
        var permitted;

        // It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
        if (this.trackingClick) {
            this.targetElement = null;
            this.trackingClick = false;
            return true;
        }

        // Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
        if (event.target.type === 'submit' && event.detail === 0) {
            return true;
        }

        permitted = this.onMouse(event);

        // Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
        if (!permitted) {
            this.targetElement = null;
        }

        // If clicks are permitted, return true for the action to go through.
        return permitted;
    };


    /**
     * Remove all FastClick's event listeners.
     *
     * @returns {void}
     */
    FastClick.prototype.destroy = function() {
        var layer = this.layer;

        if (deviceIsAndroid) {
            layer.removeEventListener('mouseover', this.onMouse, true);
            layer.removeEventListener('mousedown', this.onMouse, true);
            layer.removeEventListener('mouseup', this.onMouse, true);
        }

        layer.removeEventListener('click', this.onClick, true);
        layer.removeEventListener('touchstart', this.onTouchStart, false);
        layer.removeEventListener('touchmove', this.onTouchMove, false);
        layer.removeEventListener('touchend', this.onTouchEnd, false);
        layer.removeEventListener('touchcancel', this.onTouchCancel, false);
    };


    /**
     * Check whether FastClick is needed.
     *
     * @param {Element} layer The layer to listen on
     */
    FastClick.notNeeded = function(layer) {
        var metaViewport;
        var chromeVersion;
        var blackberryVersion;
        var firefoxVersion;

        // Devices that don't support touch don't need FastClick
        if (typeof window.ontouchstart === 'undefined') {
            return true;
        }

        // Chrome version - zero for other browsers
        chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

        if (chromeVersion) {

            if (deviceIsAndroid) {
                metaViewport = document.querySelector('meta[name=viewport]');

                if (metaViewport) {
                    // Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
                    if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
                        return true;
                    }
                    // Chrome 32 and above with width=device-width or less don't need FastClick
                    if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
                        return true;
                    }
                }

                // Chrome desktop doesn't need FastClick (issue #15)
            } else {
                return true;
            }
        }

        if (deviceIsBlackBerry10) {
            blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

            // BlackBerry 10.3+ does not require Fastclick library.
            // https://github.com/ftlabs/fastclick/issues/251
            if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
                metaViewport = document.querySelector('meta[name=viewport]');

                if (metaViewport) {
                    // user-scalable=no eliminates click delay.
                    if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
                        return true;
                    }
                    // width=device-width (or less than device-width) eliminates click delay.
                    if (document.documentElement.scrollWidth <= window.outerWidth) {
                        return true;
                    }
                }
            }
        }

        // IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
        if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
            return true;
        }

        // Firefox version - zero for other browsers
        firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

        if (firefoxVersion >= 27) {
            // Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

            metaViewport = document.querySelector('meta[name=viewport]');
            if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
                return true;
            }
        }

        // IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
        // http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
        if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
            return true;
        }

        return false;
    };


    /**
     * Factory method for creating a FastClick object
     *
     * @param {Element} layer The layer to listen on
     * @param {Object} [options={}] The options to override the defaults
     */
    FastClick.attach = function(layer, options) {
        return new FastClick(layer, options);
    };

    //直接绑定
    FastClick.attach(document.body);
}());

/*======================================================
************   Modals   ************
======================================================*/
/*jshint unused: false*/
/* global Zepto:true */
+function ($) {
    "use strict";
    var _modalTemplateTempDiv = document.createElement('div');

    $.modalStack = [];

    $.modalStackClearQueue = function () {
        if ($.modalStack.length) {
            ($.modalStack.shift())();
        }
    };
    $.modal = function (params) {
        params = params || {};
        var modalHTML = '';
        var buttonsHTML = '';
        if (params.buttons && params.buttons.length > 0) {
            for (var i = 0; i < params.buttons.length; i++) {
                buttonsHTML += '<span class="modal-button' + (params.buttons[i].bold ? ' modal-button-bold' : '') + '">' + params.buttons[i].text + '</span>';
            }
        }
        var extraClass = params.extraClass || '';
        var titleHTML = params.title ? '<div class="modal-title">' + params.title + '</div>' : '';
        var textHTML = params.text ? '<div class="modal-text">' + params.text + '</div>' : '';
        var afterTextHTML = params.afterText ? params.afterText : '';
        var noButtons = !params.buttons || params.buttons.length === 0 ? 'modal-no-buttons' : '';
        var verticalButtons = params.verticalButtons ? 'modal-buttons-vertical' : '';
        modalHTML = '<div class="modal ' + extraClass + ' ' + noButtons + '"><div class="modal-inner">' + (titleHTML + textHTML + afterTextHTML) + '</div><div class="modal-buttons ' + verticalButtons + '">' + buttonsHTML + '</div></div>';

        _modalTemplateTempDiv.innerHTML = modalHTML;

        var modal = $(_modalTemplateTempDiv).children();

        $(defaults.modalContainer).append(modal[0]);

        // Add events on buttons
        modal.find('.modal-button').each(function (index, el) {
            $(el).on('click', function (e) {
                if (params.buttons[index].close !== false) $.closeModal(modal);
                if (params.buttons[index].onClick) params.buttons[index].onClick(modal, e);
                if (params.onClick) params.onClick(modal, index);
            });
        });
        $.openModal(modal);
        return modal[0];
    };
    $.alert = function (text, title, callbackOk) {
        if (typeof title === 'function') {
            callbackOk = arguments[1];
            title = undefined;
        }
        return $.modal({
            text: text || '',
            title: typeof title === 'undefined' ? defaults.modalTitle : title,
            buttons: [ {text: defaults.modalButtonOk, bold: true, onClick: callbackOk} ]
        });
    };
    $.confirm = function (text, title, callbackOk, callbackCancel) {
        if (typeof title === 'function') {
            callbackCancel = arguments[2];
            callbackOk = arguments[1];
            title = undefined;
        }
        return $.modal({
            text: text || '',
            title: typeof title === 'undefined' ? defaults.modalTitle : title,
            buttons: [
                {text: defaults.modalButtonCancel, onClick: callbackCancel},
                {text: defaults.modalButtonOk, bold: true, onClick: callbackOk}
            ]
        });
    };
    $.prompt = function (text, title, callbackOk, callbackCancel) {
        if (typeof title === 'function') {
            callbackCancel = arguments[2];
            callbackOk = arguments[1];
            title = undefined;
        }
        return $.modal({
            text: text || '',
            title: typeof title === 'undefined' ? defaults.modalTitle : title,
            afterText: '<input type="text" class="modal-text-input">',
            buttons: [
                {
                    text: defaults.modalButtonCancel
                },
                {
                    text: defaults.modalButtonOk,
                    bold: true
                }
            ],
            onClick: function (modal, index) {
                if (index === 0 && callbackCancel) callbackCancel($(modal).find('.modal-text-input').val());
                if (index === 1 && callbackOk) callbackOk($(modal).find('.modal-text-input').val());
            }
        });
    };
    $.modalLogin = function (text, title, callbackOk, callbackCancel) {
        if (typeof title === 'function') {
            callbackCancel = arguments[2];
            callbackOk = arguments[1];
            title = undefined;
        }
        return $.modal({
            text: text || '',
            title: typeof title === 'undefined' ? defaults.modalTitle : title,
            afterText: '<input type="text" name="modal-username" placeholder="' + defaults.modalUsernamePlaceholder + '" class="modal-text-input modal-text-input-double"><input type="password" name="modal-password" placeholder="' + defaults.modalPasswordPlaceholder + '" class="modal-text-input modal-text-input-double">',
            buttons: [
                {
                    text: defaults.modalButtonCancel
                },
                {
                    text: defaults.modalButtonOk,
                    bold: true
                }
            ],
            onClick: function (modal, index) {
                var username = $(modal).find('.modal-text-input[name="modal-username"]').val();
                var password = $(modal).find('.modal-text-input[name="modal-password"]').val();
                if (index === 0 && callbackCancel) callbackCancel(username, password);
                if (index === 1 && callbackOk) callbackOk(username, password);
            }
        });
    };
    $.modalPassword = function (text, title, callbackOk, callbackCancel) {
        if (typeof title === 'function') {
            callbackCancel = arguments[2];
            callbackOk = arguments[1];
            title = undefined;
        }
        return $.modal({
            text: text || '',
            title: typeof title === 'undefined' ? defaults.modalTitle : title,
            afterText: '<input type="password" name="modal-password" placeholder="' + defaults.modalPasswordPlaceholder + '" class="modal-text-input">',
            buttons: [
                {
                    text: defaults.modalButtonCancel
                },
                {
                    text: defaults.modalButtonOk,
                    bold: true
                }
            ],
            onClick: function (modal, index) {
                var password = $(modal).find('.modal-text-input[name="modal-password"]').val();
                if (index === 0 && callbackCancel) callbackCancel(password);
                if (index === 1 && callbackOk) callbackOk(password);
            }
        });
    };
    $.showPreloader = function (title) {
        $.hidePreloader();
        $.showPreloader.preloaderModal = $.modal({
            title: title || defaults.modalPreloaderTitle,
            text: '<div class="preloader"></div>'
        });

        return $.showPreloader.preloaderModal;
    };
    $.hidePreloader = function () {
        $.showPreloader.preloaderModal && $.closeModal($.showPreloader.preloaderModal);
    };
    $.showIndicator = function () {
        if ($('.preloader-indicator-modal')[0]) return;
        $(defaults.modalContainer).append('<div class="preloader-indicator-overlay"></div><div class="preloader-indicator-modal"><span class="preloader preloader-white"></span></div>');
    };
    $.hideIndicator = function () {
        $('.preloader-indicator-overlay, .preloader-indicator-modal').remove();
    };
    // Action Sheet
    $.actions = function (params) {
        var modal, groupSelector, buttonSelector;
        params = params || [];

        if (params.length > 0 && !$.isArray(params[0])) {
            params = [params];
        }
        var modalHTML;
        var buttonsHTML = '';
        for (var i = 0; i < params.length; i++) {
            for (var j = 0; j < params[i].length; j++) {
                if (j === 0) buttonsHTML += '<div class="actions-modal-group">';
                var button = params[i][j];
                var buttonClass = button.label ? 'actions-modal-label' : 'actions-modal-button';
                if (button.bold) buttonClass += ' actions-modal-button-bold';
                if (button.color) buttonClass += ' color-' + button.color;
                if (button.bg) buttonClass += ' bg-' + button.bg;
                if (button.disabled) buttonClass += ' disabled';
                buttonsHTML += '<span class="' + buttonClass + '">' + button.text + '</span>';
                if (j === params[i].length - 1) buttonsHTML += '</div>';
            }
        }
        modalHTML = '<div class="actions-modal">' + buttonsHTML + '</div>';
        _modalTemplateTempDiv.innerHTML = modalHTML;
        modal = $(_modalTemplateTempDiv).children();
        $(defaults.modalContainer).append(modal[0]);
        groupSelector = '.actions-modal-group';
        buttonSelector = '.actions-modal-button';

        var groups = modal.find(groupSelector);
        groups.each(function (index, el) {
            var groupIndex = index;
            $(el).children().each(function (index, el) {
                var buttonIndex = index;
                var buttonParams = params[groupIndex][buttonIndex];
                var clickTarget;
                if ($(el).is(buttonSelector)) clickTarget = $(el);
                // if (toPopover && $(el).find(buttonSelector).length > 0) clickTarget = $(el).find(buttonSelector);

                if (clickTarget) {
                    clickTarget.on('click', function (e) {
                        if (buttonParams.close !== false) $.closeModal(modal);
                        if (buttonParams.onClick) buttonParams.onClick(modal, e);
                    });
                }
            });
        });
        $.openModal(modal);
        return modal[0];
    };
    $.popup = function (modal, removeOnClose) {
        if (typeof removeOnClose === 'undefined') removeOnClose = true;
        if (typeof modal === 'string' && modal.indexOf('<') >= 0) {
            var _modal = document.createElement('div');
            _modal.innerHTML = modal.trim();
            if (_modal.childNodes.length > 0) {
                modal = _modal.childNodes[0];
                if (removeOnClose) modal.classList.add('remove-on-close');
                $(defaults.modalContainer).append(modal);
            }
            else return false; //nothing found
        }
        modal = $(modal);
        if (modal.length === 0) return false;
        modal.show();
        modal.find(".content").scroller("refresh");
        if (modal.find('.' + defaults.viewClass).length > 0) {
            $.sizeNavbars(modal.find('.' + defaults.viewClass)[0]);
        }
        $.openModal(modal);

        return modal[0];
    };
    $.pickerModal = function (pickerModal, removeOnClose) {
        if (typeof removeOnClose === 'undefined') removeOnClose = true;
        if (typeof pickerModal === 'string' && pickerModal.indexOf('<') >= 0) {
            pickerModal = $(pickerModal);
            if (pickerModal.length > 0) {
                if (removeOnClose) pickerModal.addClass('remove-on-close');
                $(defaults.modalContainer).append(pickerModal[0]);
            }
            else return false; //nothing found
        }
        pickerModal = $(pickerModal);
        if (pickerModal.length === 0) return false;
        pickerModal.show();
        $.openModal(pickerModal);
        return pickerModal[0];
    };
    $.loginScreen = function (modal) {
        if (!modal) modal = '.login-screen';
        modal = $(modal);
        if (modal.length === 0) return false;
        modal.show();
        if (modal.find('.' + defaults.viewClass).length > 0) {
            $.sizeNavbars(modal.find('.' + defaults.viewClass)[0]);
        }
        $.openModal(modal);
        return modal[0];
    };
    //显示一个消息，会在2秒钟后自动消失
    $.toast = function(msg, duration, extraclass) {
        var $toast = $('<div class="modal toast ' + (extraclass || '') + '">' + msg + '</div>').appendTo(document.body);
        $.openModal($toast, function(){
            setTimeout(function() {
                $.closeModal($toast);
            }, duration || 2000);
        });
    };
    $.openModal = function (modal, cb) {
        modal = $(modal);
        var isModal = modal.hasClass('modal'),
            isNotToast = !modal.hasClass('toast');
        if ($('.modal.modal-in:not(.modal-out)').length && defaults.modalStack && isModal && isNotToast) {
            $.modalStack.push(function () {
                $.openModal(modal, cb);
            });
            return;
        }
        var isPopup = modal.hasClass('popup');
        var isLoginScreen = modal.hasClass('login-screen');
        var isPickerModal = modal.hasClass('picker-modal');
        var isToast = modal.hasClass('toast');
        if (isModal) {
            modal.show();
            modal.css({
                marginTop: - Math.round(modal.outerHeight() / 2) + 'px'
            });
        }
        if (isToast) {
            modal.css({
                marginLeft: - Math.round(modal.outerWidth() / 2 / 1.185) + 'px' //1.185 是初始化时候的放大效果
            });
        }

        var overlay;
        if (!isLoginScreen && !isPickerModal && !isToast) {
            if ($('.modal-overlay').length === 0 && !isPopup) {
                $(defaults.modalContainer).append('<div class="modal-overlay"></div>');
            }
            if ($('.popup-overlay').length === 0 && isPopup) {
                $(defaults.modalContainer).append('<div class="popup-overlay"></div>');
            }
            overlay = isPopup ? $('.popup-overlay') : $('.modal-overlay');
        }

        //Make sure that styles are applied, trigger relayout;
        var clientLeft = modal[0].clientLeft;

        // Trugger open event
        modal.trigger('open');

        // Picker modal body class
        if (isPickerModal) {
            $(defaults.modalContainer).addClass('with-picker-modal');
        }

        // Classes for transition in
        if (!isLoginScreen && !isPickerModal && !isToast) overlay.addClass('modal-overlay-visible');
        modal.removeClass('modal-out').addClass('modal-in').transitionEnd(function (e) {
            if (modal.hasClass('modal-out')) modal.trigger('closed');
            else modal.trigger('opened');
        });
        // excute callback
        if (typeof cb === 'function') {
          cb.call(this);
        }
        return true;
    };
    $.closeModal = function (modal) {
        modal = $(modal || '.modal-in');
        if (typeof modal !== 'undefined' && modal.length === 0) {
            return;
        }
        var isModal = modal.hasClass('modal'),
            isPopup = modal.hasClass('popup'),
            isToast = modal.hasClass('toast'),
            isLoginScreen = modal.hasClass('login-screen'),
            isPickerModal = modal.hasClass('picker-modal'),
            removeOnClose = modal.hasClass('remove-on-close'),
            overlay = isPopup ? $('.popup-overlay') : $('.modal-overlay');
        if (isPopup){
            if (modal.length === $('.popup.modal-in').length) {
                overlay.removeClass('modal-overlay-visible');
            }
        }
        else if (!(isPickerModal || isToast)) {
            overlay.removeClass('modal-overlay-visible');
        }

        modal.trigger('close');

        // Picker modal body class
        if (isPickerModal) {
            $(defaults.modalContainer).removeClass('with-picker-modal');
            $(defaults.modalContainer).addClass('picker-modal-closing');
        }

        modal.removeClass('modal-in').addClass('modal-out').transitionEnd(function (e) {
            if (modal.hasClass('modal-out')) modal.trigger('closed');
            else modal.trigger('opened');

            if (isPickerModal) {
                $(defaults.modalContainer).removeClass('picker-modal-closing');
            }
            if (isPopup || isLoginScreen || isPickerModal) {
                modal.removeClass('modal-out').hide();
                if (removeOnClose && modal.length > 0) {
                    modal.remove();
                }
            }
            else {
                modal.remove();
            }
        });
        if (isModal &&  defaults.modalStack ) {
            $.modalStackClearQueue();
        }

        return true;
    };
    function handleClicks(e) {
        /*jshint validthis:true */
        var clicked = $(this);
        var url = clicked.attr('href');


        //Collect Clicked data- attributes
        var clickedData = clicked.dataset();

        // Popup
        var popup;
        if (clicked.hasClass('open-popup')) {
            if (clickedData.popup) {
                popup = clickedData.popup;
            }
            else popup = '.popup';
            $.popup(popup);
        }
        if (clicked.hasClass('close-popup')) {
            if (clickedData.popup) {
                popup = clickedData.popup;
            }
            else popup = '.popup.modal-in';
            $.closeModal(popup);
        }

        // Close Modal
        if (clicked.hasClass('modal-overlay')) {
            if ($('.modal.modal-in').length > 0 && defaults.modalCloseByOutside)
                $.closeModal('.modal.modal-in');
            if ($('.actions-modal.modal-in').length > 0 && defaults.actionsCloseByOutside)
                $.closeModal('.actions-modal.modal-in');

        }
        if (clicked.hasClass('popup-overlay')) {
            if ($('.popup.modal-in').length > 0 && defaults.popupCloseByOutside)
                $.closeModal('.popup.modal-in');
        }




    }
    $(document).on('click', ' .modal-overlay, .popup-overlay, .close-popup, .open-popup, .close-picker', handleClicks);
    var defaults =  $.modal.prototype.defaults  = {
        modalStack: true,
        modalButtonOk: '确定',
        modalButtonCancel: '取消',
        modalPreloaderTitle: '加载中',
        modalContainer : document.body
    };
}(Zepto);

/*======================================================
************   Calendar   ************
======================================================*/
/* global Zepto:true */
/*jshint unused: false*/
+function ($) {
    "use strict";
    var rtl = false;
    var Calendar = function (params) {
        var p = this;
        var defaults = {
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月' , '九月' , '十月', '十一月', '十二月'],
            monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月' , '九月' , '十月', '十一月', '十二月'],
            dayNames: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
            dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
            firstDay: 1, // First day of the week, Monday
            weekendDays: [0, 6], // Sunday and Saturday
            multiple: false,
            dateFormat: 'yyyy-mm-dd',
            direction: 'horizontal', // or 'vertical'
            minDate: null,
            maxDate: null,
            touchMove: true,
            animate: true,
            closeOnSelect: true,
            monthPicker: true,
            monthPickerTemplate:
                '<div class="picker-calendar-month-picker">' +
                '<a href="#" class="link icon-only picker-calendar-prev-month"><i class="icon icon-prev"></i></a>' +
                '<div class="current-month-value"></div>' +
                '<a href="#" class="link icon-only picker-calendar-next-month"><i class="icon icon-next"></i></a>' +
                '</div>',
            yearPicker: true,
            yearPickerTemplate:
                '<div class="picker-calendar-year-picker">' +
                '<a href="#" class="link icon-only picker-calendar-prev-year"><i class="icon icon-prev"></i></a>' +
                '<span class="current-year-value"></span>' +
                '<a href="#" class="link icon-only picker-calendar-next-year"><i class="icon icon-next"></i></a>' +
                '</div>',
            weekHeader: true,
            // Common settings
            scrollToInput: true,
            inputReadOnly: true,
            toolbar: true,
            toolbarCloseText: 'Done',
            toolbarTemplate:
                '<div class="toolbar">' +
                '<div class="toolbar-inner">' +
                '{{monthPicker}}' +
                '{{yearPicker}}' +
                // '<a href="#" class="link close-picker">{{closeText}}</a>' +
                '</div>' +
                '</div>',
            /* Callbacks
               onMonthAdd
               onChange
               onOpen
               onClose
               onDayClick
               onMonthYearChangeStart
               onMonthYearChangeEnd
               */
        };
        params = params || {};
        for (var def in defaults) {
            if (typeof params[def] === 'undefined') {
                params[def] = defaults[def];
            }
        }
        p.params = params;
        p.initialized = false;

        // Inline flag
        p.inline = p.params.container ? true : false;

        // Is horizontal
        p.isH = p.params.direction === 'horizontal';

        // RTL inverter
        var inverter = p.isH ? (rtl ? -1 : 1) : 1;

        // Animating flag
        p.animating = false;

        // Format date
        function formatDate(date) {
            date = new Date(date);
            var year = date.getFullYear();
            var month = date.getMonth();
            var month1 = month + 1;
            var day = date.getDate();
            var weekDay = date.getDay();
            return p.params.dateFormat
                .replace(/yyyy/g, year)
                .replace(/yy/g, (year + '').substring(2))
                .replace(/mm/g, month1 < 10 ? '0' + month1 : month1)
                .replace(/m/g, month1)
                .replace(/MM/g, p.params.monthNames[month])
                .replace(/M/g, p.params.monthNamesShort[month])
                .replace(/dd/g, day < 10 ? '0' + day : day)
                .replace(/d/g, day)
                .replace(/DD/g, p.params.dayNames[weekDay])
                .replace(/D/g, p.params.dayNamesShort[weekDay]);
        }


        // Value
        p.addValue = function (value) {
            if (p.params.multiple) {
                if (!p.value) p.value = [];
                var inValuesIndex;
                for (var i = 0; i < p.value.length; i++) {
                    if (new Date(value).getTime() === new Date(p.value[i]).getTime()) {
                        inValuesIndex = i;
                    }
                }
                if (typeof inValuesIndex === 'undefined') {
                    p.value.push(value);
                }
                else {
                    p.value.splice(inValuesIndex, 1);
                }
                p.updateValue();
            }
            else {
                p.value = [value];
                p.updateValue();
            }
        };
        p.setValue = function (arrValues) {
            p.value = arrValues;
            p.updateValue();
        };
        p.updateValue = function () {
            p.wrapper.find('.picker-calendar-day-selected').removeClass('picker-calendar-day-selected');
            var i, inputValue;
            for (i = 0; i < p.value.length; i++) {
                var valueDate = new Date(p.value[i]);
                p.wrapper.find('.picker-calendar-day[data-date="' + valueDate.getFullYear() + '-' + valueDate.getMonth() + '-' + valueDate.getDate() + '"]').addClass('picker-calendar-day-selected');
            }
            if (p.params.onChange) {
                p.params.onChange(p, p.value, p.value.map(formatDate));
            }
            if (p.input && p.input.length > 0) {
                if (p.params.formatValue) inputValue = p.params.formatValue(p, p.value);
                else {
                    inputValue = [];
                    for (i = 0; i < p.value.length; i++) {
                        inputValue.push(formatDate(p.value[i]));
                    }
                    inputValue = inputValue.join(', ');
                }
                $(p.input).val(inputValue);
                $(p.input).trigger('change');
            }
        };

        // Columns Handlers
        p.initCalendarEvents = function () {
            var col;
            var allowItemClick = true;
            var isTouched, isMoved, touchStartX, touchStartY, touchCurrentX, touchCurrentY, touchStartTime, touchEndTime, startTranslate, currentTranslate, wrapperWidth, wrapperHeight, percentage, touchesDiff, isScrolling;
            function handleTouchStart (e) {
                if (isMoved || isTouched) return;
                // e.preventDefault();
                isTouched = true;
                touchStartX = touchCurrentY = e.type === 'touchstart' ? e.targetTouches[0].pageX : e.pageX;
                touchStartY = touchCurrentY = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
                touchStartTime = (new Date()).getTime();
                percentage = 0;
                allowItemClick = true;
                isScrolling = undefined;
                startTranslate = currentTranslate = p.monthsTranslate;
            }
            function handleTouchMove (e) {
                if (!isTouched) return;

                touchCurrentX = e.type === 'touchmove' ? e.targetTouches[0].pageX : e.pageX;
                touchCurrentY = e.type === 'touchmove' ? e.targetTouches[0].pageY : e.pageY;
                if (typeof isScrolling === 'undefined') {
                    isScrolling = !!(isScrolling || Math.abs(touchCurrentY - touchStartY) > Math.abs(touchCurrentX - touchStartX));
                }
                if (p.isH && isScrolling) {
                    isTouched = false;
                    return;
                }
                e.preventDefault();
                if (p.animating) {
                    isTouched = false;
                    return;
                }
                allowItemClick = false;
                if (!isMoved) {
                    // First move
                    isMoved = true;
                    wrapperWidth = p.wrapper[0].offsetWidth;
                    wrapperHeight = p.wrapper[0].offsetHeight;
                    p.wrapper.transition(0);
                }
                e.preventDefault();

                touchesDiff = p.isH ? touchCurrentX - touchStartX : touchCurrentY - touchStartY;
                percentage = touchesDiff/(p.isH ? wrapperWidth : wrapperHeight);
                currentTranslate = (p.monthsTranslate * inverter + percentage) * 100;

                // Transform wrapper
                p.wrapper.transform('translate3d(' + (p.isH ? currentTranslate : 0) + '%, ' + (p.isH ? 0 : currentTranslate) + '%, 0)');

            }
            function handleTouchEnd (e) {
                if (!isTouched || !isMoved) {
                    isTouched = isMoved = false;
                    return;
                }
                isTouched = isMoved = false;

                touchEndTime = new Date().getTime();
                if (touchEndTime - touchStartTime < 300) {
                    if (Math.abs(touchesDiff) < 10) {
                        p.resetMonth();
                    }
                    else if (touchesDiff >= 10) {
                        if (rtl) p.nextMonth();
                        else p.prevMonth();
                    }
                    else {
                        if (rtl) p.prevMonth();
                        else p.nextMonth();
                    }
                }
                else {
                    if (percentage <= -0.5) {
                        if (rtl) p.prevMonth();
                        else p.nextMonth();
                    }
                    else if (percentage >= 0.5) {
                        if (rtl) p.nextMonth();
                        else p.prevMonth();
                    }
                    else {
                        p.resetMonth();
                    }
                }

                // Allow click
                setTimeout(function () {
                    allowItemClick = true;
                }, 100);
            }

            function handleDayClick(e) {
                if (!allowItemClick) return;
                var day = $(e.target).parents('.picker-calendar-day');
                if (day.length === 0 && $(e.target).hasClass('picker-calendar-day')) {
                    day = $(e.target);
                }
                if (day.length === 0) return;
                if (day.hasClass('picker-calendar-day-selected') && !p.params.multiple) return;
                if (day.hasClass('picker-calendar-day-disabled')) return;
                if (day.hasClass('picker-calendar-day-next')) p.nextMonth();
                if (day.hasClass('picker-calendar-day-prev')) p.prevMonth();
                var dateYear = day.attr('data-year');
                var dateMonth = day.attr('data-month');
                var dateDay = day.attr('data-day');
                if (p.params.onDayClick) {
                    p.params.onDayClick(p, day[0], dateYear, dateMonth, dateDay);
                }
                p.addValue(new Date(dateYear, dateMonth, dateDay).getTime());
                if (p.params.closeOnSelect) p.close();
            }

            p.container.find('.picker-calendar-prev-month').on('click', p.prevMonth);
            p.container.find('.picker-calendar-next-month').on('click', p.nextMonth);
            p.container.find('.picker-calendar-prev-year').on('click', p.prevYear);
            p.container.find('.picker-calendar-next-year').on('click', p.nextYear);
            p.wrapper.on('click', handleDayClick);
            if (p.params.touchMove) {
                p.wrapper.on($.touchEvents.start, handleTouchStart);
                p.wrapper.on($.touchEvents.move, handleTouchMove);
                p.wrapper.on($.touchEvents.end, handleTouchEnd);
            }

            p.container[0].f7DestroyCalendarEvents = function () {
                p.container.find('.picker-calendar-prev-month').off('click', p.prevMonth);
                p.container.find('.picker-calendar-next-month').off('click', p.nextMonth);
                p.container.find('.picker-calendar-prev-year').off('click', p.prevYear);
                p.container.find('.picker-calendar-next-year').off('click', p.nextYear);
                p.wrapper.off('click', handleDayClick);
                if (p.params.touchMove) {
                    p.wrapper.off($.touchEvents.start, handleTouchStart);
                    p.wrapper.off($.touchEvents.move, handleTouchMove);
                    p.wrapper.off($.touchEvents.end, handleTouchEnd);
                }
            };


        };
        p.destroyCalendarEvents = function (colContainer) {
            if ('f7DestroyCalendarEvents' in p.container[0]) p.container[0].f7DestroyCalendarEvents();
        };

        // Calendar Methods
        p.daysInMonth = function (date) {
            var d = new Date(date);
            return new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
        };
        p.monthHTML = function (date, offset) {
            date = new Date(date);
            var year = date.getFullYear(),
                month = date.getMonth(),
                day = date.getDate();
            if (offset === 'next') {
                if (month === 11) date = new Date(year + 1, 0);
                else date = new Date(year, month + 1, 1);
            }
            if (offset === 'prev') {
                if (month === 0) date = new Date(year - 1, 11);
                else date = new Date(year, month - 1, 1);
            }
            if (offset === 'next' || offset === 'prev') {
                month = date.getMonth();
                year = date.getFullYear();
            }
            var daysInPrevMonth = p.daysInMonth(new Date(date.getFullYear(), date.getMonth()).getTime() - 10 * 24 * 60 * 60 * 1000),
                daysInMonth = p.daysInMonth(date),
                firstDayOfMonthIndex = new Date(date.getFullYear(), date.getMonth()).getDay();
            if (firstDayOfMonthIndex === 0) firstDayOfMonthIndex = 7;

            var dayDate, currentValues = [], i, j,
                rows = 6, cols = 7,
                monthHTML = '',
                dayIndex = 0 + (p.params.firstDay - 1),
                today = new Date().setHours(0,0,0,0),
                minDate = p.params.minDate ? new Date(p.params.minDate).getTime() : null,
                maxDate = p.params.maxDate ? new Date(p.params.maxDate).getTime() : null;

            if (p.value && p.value.length) {
                for (i = 0; i < p.value.length; i++) {
                    currentValues.push(new Date(p.value[i]).setHours(0,0,0,0));
                }
            }

            for (i = 1; i <= rows; i++) {
                var rowHTML = '';
                var row = i;
                for (j = 1; j <= cols; j++) {
                    var col = j;
                    dayIndex ++;
                    var dayNumber = dayIndex - firstDayOfMonthIndex;
                    var addClass = '';
                    if (dayNumber < 0) {
                        dayNumber = daysInPrevMonth + dayNumber + 1;
                        addClass += ' picker-calendar-day-prev';
                        dayDate = new Date(month - 1 < 0 ? year - 1 : year, month - 1 < 0 ? 11 : month - 1, dayNumber).getTime();
                    }
                    else {
                        dayNumber = dayNumber + 1;
                        if (dayNumber > daysInMonth) {
                            dayNumber = dayNumber - daysInMonth;
                            addClass += ' picker-calendar-day-next';
                            dayDate = new Date(month + 1 > 11 ? year + 1 : year, month + 1 > 11 ? 0 : month + 1, dayNumber).getTime();
                        }
                        else {
                            dayDate = new Date(year, month, dayNumber).getTime();
                        }
                    }
                    // Today
                    if (dayDate === today) addClass += ' picker-calendar-day-today';
                    // Selected
                    if (currentValues.indexOf(dayDate) >= 0) addClass += ' picker-calendar-day-selected';
                    // Weekend
                    if (p.params.weekendDays.indexOf(col - 1) >= 0) {
                        addClass += ' picker-calendar-day-weekend';
                    }
                    // Disabled
                    if ((minDate && dayDate < minDate) || (maxDate && dayDate > maxDate)) {
                        addClass += ' picker-calendar-day-disabled';
                    }

                    dayDate = new Date(dayDate);
                    var dayYear = dayDate.getFullYear();
                    var dayMonth = dayDate.getMonth();
                    rowHTML += '<div data-year="' + dayYear + '" data-month="' + dayMonth + '" data-day="' + dayNumber + '" class="picker-calendar-day' + (addClass) + '" data-date="' + (dayYear + '-' + dayMonth + '-' + dayNumber) + '"><span>'+dayNumber+'</span></div>';
                }
                monthHTML += '<div class="picker-calendar-row">' + rowHTML + '</div>';
            }
            monthHTML = '<div class="picker-calendar-month" data-year="' + year + '" data-month="' + month + '">' + monthHTML + '</div>';
            return monthHTML;
        };
        p.animating = false;
        p.updateCurrentMonthYear = function (dir) {
            if (typeof dir === 'undefined') {
                p.currentMonth = parseInt(p.months.eq(1).attr('data-month'), 10);
                p.currentYear = parseInt(p.months.eq(1).attr('data-year'), 10);
            }
            else {
                p.currentMonth = parseInt(p.months.eq(dir === 'next' ? (p.months.length - 1) : 0).attr('data-month'), 10);
                p.currentYear = parseInt(p.months.eq(dir === 'next' ? (p.months.length - 1) : 0).attr('data-year'), 10);
            }
            p.container.find('.current-month-value').text(p.params.monthNames[p.currentMonth]);
            p.container.find('.current-year-value').text(p.currentYear);

        };
        p.onMonthChangeStart = function (dir) {
            p.updateCurrentMonthYear(dir);
            p.months.removeClass('picker-calendar-month-current picker-calendar-month-prev picker-calendar-month-next');
            var currentIndex = dir === 'next' ? p.months.length - 1 : 0;

            p.months.eq(currentIndex).addClass('picker-calendar-month-current');
            p.months.eq(dir === 'next' ? currentIndex - 1 : currentIndex + 1).addClass(dir === 'next' ? 'picker-calendar-month-prev' : 'picker-calendar-month-next');

            if (p.params.onMonthYearChangeStart) {
                p.params.onMonthYearChangeStart(p, p.currentYear, p.currentMonth);
            }
        };
        p.onMonthChangeEnd = function (dir, rebuildBoth) {
            p.animating = false;
            var nextMonthHTML, prevMonthHTML, newMonthHTML;
            p.wrapper.find('.picker-calendar-month:not(.picker-calendar-month-prev):not(.picker-calendar-month-current):not(.picker-calendar-month-next)').remove();

            if (typeof dir === 'undefined') {
                dir = 'next';
                rebuildBoth = true;
            }
            if (!rebuildBoth) {
                newMonthHTML = p.monthHTML(new Date(p.currentYear, p.currentMonth), dir);
            }
            else {
                p.wrapper.find('.picker-calendar-month-next, .picker-calendar-month-prev').remove();
                prevMonthHTML = p.monthHTML(new Date(p.currentYear, p.currentMonth), 'prev');
                nextMonthHTML = p.monthHTML(new Date(p.currentYear, p.currentMonth), 'next');
            }
            if (dir === 'next' || rebuildBoth) {
                p.wrapper.append(newMonthHTML || nextMonthHTML);
            }
            if (dir === 'prev' || rebuildBoth) {
                p.wrapper.prepend(newMonthHTML || prevMonthHTML);
            }
            p.months = p.wrapper.find('.picker-calendar-month');
            p.setMonthsTranslate(p.monthsTranslate);
            if (p.params.onMonthAdd) {
                p.params.onMonthAdd(p, dir === 'next' ? p.months.eq(p.months.length - 1)[0] : p.months.eq(0)[0]);
            }
            if (p.params.onMonthYearChangeEnd) {
                p.params.onMonthYearChangeEnd(p, p.currentYear, p.currentMonth);
            }
        };
        p.setMonthsTranslate = function (translate) {
            translate = translate || p.monthsTranslate || 0;
            if (typeof p.monthsTranslate === 'undefined') p.monthsTranslate = translate;
            p.months.removeClass('picker-calendar-month-current picker-calendar-month-prev picker-calendar-month-next');
            var prevMonthTranslate = -(translate + 1) * 100 * inverter;
            var currentMonthTranslate = -translate * 100 * inverter;
            var nextMonthTranslate = -(translate - 1) * 100 * inverter;
            p.months.eq(0).transform('translate3d(' + (p.isH ? prevMonthTranslate : 0) + '%, ' + (p.isH ? 0 : prevMonthTranslate) + '%, 0)').addClass('picker-calendar-month-prev');
            p.months.eq(1).transform('translate3d(' + (p.isH ? currentMonthTranslate : 0) + '%, ' + (p.isH ? 0 : currentMonthTranslate) + '%, 0)').addClass('picker-calendar-month-current');
            p.months.eq(2).transform('translate3d(' + (p.isH ? nextMonthTranslate : 0) + '%, ' + (p.isH ? 0 : nextMonthTranslate) + '%, 0)').addClass('picker-calendar-month-next');
        };
        p.nextMonth = function (transition) {
            if (typeof transition === 'undefined' || typeof transition === 'object') {
                transition = '';
                if (!p.params.animate) transition = 0;
            }
            var nextMonth = parseInt(p.months.eq(p.months.length - 1).attr('data-month'), 10);
            var nextYear = parseInt(p.months.eq(p.months.length - 1).attr('data-year'), 10);
            var nextDate = new Date(nextYear, nextMonth);
            var nextDateTime = nextDate.getTime();
            var transitionEndCallback = p.animating ? false : true;
            if (p.params.maxDate) {
                if (nextDateTime > new Date(p.params.maxDate).getTime()) {
                    return p.resetMonth();
                }
            }
            p.monthsTranslate --;
            if (nextMonth === p.currentMonth) {
                var nextMonthTranslate = -(p.monthsTranslate) * 100 * inverter;
                var nextMonthHTML = $(p.monthHTML(nextDateTime, 'next')).transform('translate3d(' + (p.isH ? nextMonthTranslate : 0) + '%, ' + (p.isH ? 0 : nextMonthTranslate) + '%, 0)').addClass('picker-calendar-month-next');
                p.wrapper.append(nextMonthHTML[0]);
                p.months = p.wrapper.find('.picker-calendar-month');
                if (p.params.onMonthAdd) {
                    p.params.onMonthAdd(p, p.months.eq(p.months.length - 1)[0]);
                }
            }
            p.animating = true;
            p.onMonthChangeStart('next');
            var translate = (p.monthsTranslate * 100) * inverter;

            p.wrapper.transition(transition).transform('translate3d(' + (p.isH ? translate : 0) + '%, ' + (p.isH ? 0 : translate) + '%, 0)');
            if (transitionEndCallback) {
                p.wrapper.transitionEnd(function () {
                    p.onMonthChangeEnd('next');
                });
            }
            if (!p.params.animate) {
                p.onMonthChangeEnd('next');
            }
        };
        p.prevMonth = function (transition) {
            if (typeof transition === 'undefined' || typeof transition === 'object') {
                transition = '';
                if (!p.params.animate) transition = 0;
            }
            var prevMonth = parseInt(p.months.eq(0).attr('data-month'), 10);
            var prevYear = parseInt(p.months.eq(0).attr('data-year'), 10);
            var prevDate = new Date(prevYear, prevMonth + 1, -1);
            var prevDateTime = prevDate.getTime();
            var transitionEndCallback = p.animating ? false : true;
            if (p.params.minDate) {
                if (prevDateTime < new Date(p.params.minDate).getTime()) {
                    return p.resetMonth();
                }
            }
            p.monthsTranslate ++;
            if (prevMonth === p.currentMonth) {
                var prevMonthTranslate = -(p.monthsTranslate) * 100 * inverter;
                var prevMonthHTML = $(p.monthHTML(prevDateTime, 'prev')).transform('translate3d(' + (p.isH ? prevMonthTranslate : 0) + '%, ' + (p.isH ? 0 : prevMonthTranslate) + '%, 0)').addClass('picker-calendar-month-prev');
                p.wrapper.prepend(prevMonthHTML[0]);
                p.months = p.wrapper.find('.picker-calendar-month');
                if (p.params.onMonthAdd) {
                    p.params.onMonthAdd(p, p.months.eq(0)[0]);
                }
            }
            p.animating = true;
            p.onMonthChangeStart('prev');
            var translate = (p.monthsTranslate * 100) * inverter;
            p.wrapper.transition(transition).transform('translate3d(' + (p.isH ? translate : 0) + '%, ' + (p.isH ? 0 : translate) + '%, 0)');
            if (transitionEndCallback) {
                p.wrapper.transitionEnd(function () {
                    p.onMonthChangeEnd('prev');
                });
            }
            if (!p.params.animate) {
                p.onMonthChangeEnd('prev');
            }
        };
        p.resetMonth = function (transition) {
            if (typeof transition === 'undefined') transition = '';
            var translate = (p.monthsTranslate * 100) * inverter;
            p.wrapper.transition(transition).transform('translate3d(' + (p.isH ? translate : 0) + '%, ' + (p.isH ? 0 : translate) + '%, 0)');
        };
        p.setYearMonth = function (year, month, transition) {
            if (typeof year === 'undefined') year = p.currentYear;
            if (typeof month === 'undefined') month = p.currentMonth;
            if (typeof transition === 'undefined' || typeof transition === 'object') {
                transition = '';
                if (!p.params.animate) transition = 0;
            }
            var targetDate;
            if (year < p.currentYear) {
                targetDate = new Date(year, month + 1, -1).getTime();
            }
            else {
                targetDate = new Date(year, month).getTime();
            }
            if (p.params.maxDate && targetDate > new Date(p.params.maxDate).getTime()) {
                return false;
            }
            if (p.params.minDate && targetDate < new Date(p.params.minDate).getTime()) {
                return false;
            }
            var currentDate = new Date(p.currentYear, p.currentMonth).getTime();
            var dir = targetDate > currentDate ? 'next' : 'prev';
            var newMonthHTML = p.monthHTML(new Date(year, month));
            p.monthsTranslate = p.monthsTranslate || 0;
            var prevTranslate = p.monthsTranslate;
            var monthTranslate, wrapperTranslate;
            var transitionEndCallback = p.animating ? false : true;
            if (targetDate > currentDate) {
                // To next
                p.monthsTranslate --;
                if (!p.animating) p.months.eq(p.months.length - 1).remove();
                p.wrapper.append(newMonthHTML);
                p.months = p.wrapper.find('.picker-calendar-month');
                monthTranslate = -(prevTranslate - 1) * 100 * inverter;
                p.months.eq(p.months.length - 1).transform('translate3d(' + (p.isH ? monthTranslate : 0) + '%, ' + (p.isH ? 0 : monthTranslate) + '%, 0)').addClass('picker-calendar-month-next');
            }
            else {
                // To prev
                p.monthsTranslate ++;
                if (!p.animating) p.months.eq(0).remove();
                p.wrapper.prepend(newMonthHTML);
                p.months = p.wrapper.find('.picker-calendar-month');
                monthTranslate = -(prevTranslate + 1) * 100 * inverter;
                p.months.eq(0).transform('translate3d(' + (p.isH ? monthTranslate : 0) + '%, ' + (p.isH ? 0 : monthTranslate) + '%, 0)').addClass('picker-calendar-month-prev');
            }
            if (p.params.onMonthAdd) {
                p.params.onMonthAdd(p, dir === 'next' ? p.months.eq(p.months.length - 1)[0] : p.months.eq(0)[0]);
            }
            p.animating = true;
            p.onMonthChangeStart(dir);
            wrapperTranslate = (p.monthsTranslate * 100) * inverter;
            p.wrapper.transition(transition).transform('translate3d(' + (p.isH ? wrapperTranslate : 0) + '%, ' + (p.isH ? 0 : wrapperTranslate) + '%, 0)');
            if (transitionEndCallback) {
                p.wrapper.transitionEnd(function () {
                    p.onMonthChangeEnd(dir, true);
                });
            }
            if (!p.params.animate) {
                p.onMonthChangeEnd(dir);
            }
        };
        p.nextYear = function () {
            p.setYearMonth(p.currentYear + 1);
        };
        p.prevYear = function () {
            p.setYearMonth(p.currentYear - 1);
        };


        // HTML Layout
        p.layout = function () {
            var pickerHTML = '';
            var pickerClass = '';
            var i;

            var layoutDate = p.value && p.value.length ? p.value[0] : new Date().setHours(0,0,0,0);
            var prevMonthHTML = p.monthHTML(layoutDate, 'prev');
            var currentMonthHTML = p.monthHTML(layoutDate);
            var nextMonthHTML = p.monthHTML(layoutDate, 'next');
            var monthsHTML = '<div class="picker-calendar-months"><div class="picker-calendar-months-wrapper">' + (prevMonthHTML + currentMonthHTML + nextMonthHTML) + '</div></div>';
            // Week days header
            var weekHeaderHTML = '';
            if (p.params.weekHeader) {
                for (i = 0; i < 7; i++) {
                    var weekDayIndex = (i + p.params.firstDay > 6) ? (i - 7 + p.params.firstDay) : (i + p.params.firstDay);
                    var dayName = p.params.dayNamesShort[weekDayIndex];
                    weekHeaderHTML += '<div class="picker-calendar-week-day ' + ((p.params.weekendDays.indexOf(weekDayIndex) >= 0) ? 'picker-calendar-week-day-weekend' : '') + '"> ' + dayName + '</div>';

                }
                weekHeaderHTML = '<div class="picker-calendar-week-days">' + weekHeaderHTML + '</div>';
            }
            pickerClass = 'picker-modal picker-calendar ' + (p.params.cssClass || '');
            var toolbarHTML = p.params.toolbar ? p.params.toolbarTemplate.replace(/{{closeText}}/g, p.params.toolbarCloseText) : '';
            if (p.params.toolbar) {
                toolbarHTML = p.params.toolbarTemplate
                    .replace(/{{closeText}}/g, p.params.toolbarCloseText)
                    .replace(/{{monthPicker}}/g, (p.params.monthPicker ? p.params.monthPickerTemplate : ''))
                    .replace(/{{yearPicker}}/g, (p.params.yearPicker ? p.params.yearPickerTemplate : ''));
            }

            pickerHTML =
                '<div class="' + (pickerClass) + '">' +
                toolbarHTML +
                '<div class="picker-modal-inner">' +
                weekHeaderHTML +
                monthsHTML +
                '</div>' +
                '</div>';


            p.pickerHTML = pickerHTML;
        };

        // Input Events
        function openOnInput(e) {
            e.preventDefault();
            // 安卓微信webviewreadonly的input依然弹出软键盘问题修复
            if ($.device.isWeixin && $.device.android && p.params.inputReadOnly) {
                /*jshint validthis:true */
                this.focus();
                this.blur();
            }
            if (p.opened) return;
            p.open();
            if (p.params.scrollToInput) {
                var pageContent = p.input.parents('.content');
                if (pageContent.length === 0) return;

                var paddingTop = parseInt(pageContent.css('padding-top'), 10),
                    paddingBottom = parseInt(pageContent.css('padding-bottom'), 10),
                    pageHeight = pageContent[0].offsetHeight - paddingTop - p.container.height(),
                    pageScrollHeight = pageContent[0].scrollHeight - paddingTop - p.container.height(),
                    newPaddingBottom;

                var inputTop = p.input.offset().top - paddingTop + p.input[0].offsetHeight;
                if (inputTop > pageHeight) {
                    var scrollTop = pageContent.scrollTop() + inputTop - pageHeight;
                    if (scrollTop + pageHeight > pageScrollHeight) {
                        newPaddingBottom = scrollTop + pageHeight - pageScrollHeight + paddingBottom;
                        if (pageHeight === pageScrollHeight) {
                            newPaddingBottom = p.container.height();
                        }
                        pageContent.css({'padding-bottom': (newPaddingBottom) + 'px'});
                    }
                    pageContent.scrollTop(scrollTop, 300);
                }
            }
        }
        function closeOnHTMLClick(e) {
            if (p.input && p.input.length > 0) {
                if (e.target !== p.input[0] && $(e.target).parents('.picker-modal').length === 0) p.close();
            }
            else {
                if ($(e.target).parents('.picker-modal').length === 0) p.close();
            }
        }

        if (p.params.input) {
            p.input = $(p.params.input);
            if (p.input.length > 0) {
                if (p.params.inputReadOnly) p.input.prop('readOnly', true);
                if (!p.inline) {
                    p.input.on('click', openOnInput);
                }
            }

        }

        if (!p.inline) $('html').on('click', closeOnHTMLClick);

        // Open
        function onPickerClose() {
            p.opened = false;
            if (p.input && p.input.length > 0) p.input.parents('.content').css({'padding-bottom': ''});
            if (p.params.onClose) p.params.onClose(p);

            // Destroy events
            p.destroyCalendarEvents();
        }

        p.opened = false;
        p.open = function () {
            var updateValue = false;
            if (!p.opened) {
                // Set date value
                if (!p.value) {
                    if (p.params.value) {
                        p.value = p.params.value;
                        updateValue = true;
                    }
                }

                // Layout
                p.layout();

                // Append
                if (p.inline) {
                    p.container = $(p.pickerHTML);
                    p.container.addClass('picker-modal-inline');
                    $(p.params.container).append(p.container);
                }
                else {
                    p.container = $($.pickerModal(p.pickerHTML));
                    $(p.container)
                        .on('close', function () {
                            onPickerClose();
                        });
                }

                // Store calendar instance
                p.container[0].f7Calendar = p;
                p.wrapper = p.container.find('.picker-calendar-months-wrapper');

                // Months
                p.months = p.wrapper.find('.picker-calendar-month');

                // Update current month and year
                p.updateCurrentMonthYear();

                // Set initial translate
                p.monthsTranslate = 0;
                p.setMonthsTranslate();

                // Init events
                p.initCalendarEvents();

                // Update input value
                if (updateValue) p.updateValue();

            }

            // Set flag
            p.opened = true;
            p.initialized = true;
            if (p.params.onMonthAdd) {
                p.months.each(function () {
                    p.params.onMonthAdd(p, this);
                });
            }
            if (p.params.onOpen) p.params.onOpen(p);
        };

        // Close
        p.close = function () {
            if (!p.opened || p.inline) return;
            $.closeModal(p.container);
            return;
        };

        // Destroy
        p.destroy = function () {
            p.close();
            if (p.params.input && p.input.length > 0) {
                p.input.off('click', openOnInput);
            }
            $('html').off('click', closeOnHTMLClick);
        };

        if (p.inline) {
            p.open();
        }

        return p;
    };
    $.fn.calendar = function (params) {
        return this.each(function() {
            var $this = $(this);
            if(!$this[0]) return;
            var p = {};
            if($this[0].tagName.toUpperCase() === "INPUT") {
                p.input = $this;
            } else {
                p.container = $this;
            }
            new Calendar($.extend(p, params));
        });
    };

    $.initCalendar = function(content) {
        var $content = content ? $(content) : $(document.body);
        $content.find("[data-toggle='date']").each(function() {
            $(this).calendar();
        });
    };
}(Zepto);

/*======================================================
************   Picker   ************
======================================================*/
/* global Zepto:true */
/* jshint unused:false */
/* jshint multistr:true */
+ function($) {
    "use strict";
    var Picker = function (params) {
        var p = this;
        var defaults = {
            updateValuesOnMomentum: false,
            updateValuesOnTouchmove: true,
            rotateEffect: false,
            momentumRatio: 7,
            freeMode: false,
            // Common settings
            scrollToInput: true,
            inputReadOnly: true,
            toolbar: true,
            toolbarCloseText: '确定',
            toolbarTemplate: '<header class="bar bar-nav">\
                <button class="button button-link pull-right close-picker">确定</button>\
                <h1 class="title">请选择</h1>\
                </header>',
        };
        params = params || {};
        for (var def in defaults) {
            if (typeof params[def] === 'undefined') {
                params[def] = defaults[def];
            }
        }
        p.params = params;
        p.cols = [];
        p.initialized = false;

        // Inline flag
        p.inline = p.params.container ? true : false;

        // 3D Transforms origin bug, only on safari
        var originBug = $.device.ios || (navigator.userAgent.toLowerCase().indexOf('safari') >= 0 && navigator.userAgent.toLowerCase().indexOf('chrome') < 0) && !$.device.android;

        // Value
        p.setValue = function (arrValues, transition) {
            var valueIndex = 0;
            for (var i = 0; i < p.cols.length; i++) {
                if (p.cols[i] && !p.cols[i].divider) {
                    p.cols[i].setValue(arrValues[valueIndex], transition);
                    valueIndex++;
                }
            }
        };
        p.updateValue = function () {
            var newValue = [];
            var newDisplayValue = [];
            for (var i = 0; i < p.cols.length; i++) {
                if (!p.cols[i].divider) {
                    newValue.push(p.cols[i].value);
                    newDisplayValue.push(p.cols[i].displayValue);
                }
            }
            if (newValue.indexOf(undefined) >= 0) {
                return;
            }
            p.value = newValue;
            p.displayValue = newDisplayValue;
            if (p.params.onChange) {
                p.params.onChange(p, p.value, p.displayValue);
            }
            if (p.input && p.input.length > 0) {
                $(p.input).val(p.params.formatValue ? p.params.formatValue(p, p.value, p.displayValue) : p.value.join(' '));
                $(p.input).trigger('change');
            }
        };

        // Columns Handlers
        p.initPickerCol = function (colElement, updateItems) {
            var colContainer = $(colElement);
            var colIndex = colContainer.index();
            var col = p.cols[colIndex];
            if (col.divider) return;
            col.container = colContainer;
            col.wrapper = col.container.find('.picker-items-col-wrapper');
            col.items = col.wrapper.find('.picker-item');

            var i, j;
            var wrapperHeight, itemHeight, itemsHeight, minTranslate, maxTranslate;
            col.replaceValues = function (values, displayValues) {
                col.destroyEvents();
                col.values = values;
                col.displayValues = displayValues;
                var newItemsHTML = p.columnHTML(col, true);
                col.wrapper.html(newItemsHTML);
                col.items = col.wrapper.find('.picker-item');
                col.calcSize();
                col.setValue(col.values[0], 0, true);
                col.initEvents();
            };
            col.calcSize = function () {
                if (p.params.rotateEffect) {
                    col.container.removeClass('picker-items-col-absolute');
                    if (!col.width) col.container.css({width:''});
                }
                var colWidth, colHeight;
                colWidth = 0;
                colHeight = col.container[0].offsetHeight;
                wrapperHeight = col.wrapper[0].offsetHeight;
                itemHeight = col.items[0].offsetHeight;
                itemsHeight = itemHeight * col.items.length;
                minTranslate = colHeight / 2 - itemsHeight + itemHeight / 2;
                maxTranslate = colHeight / 2 - itemHeight / 2;
                if (col.width) {
                    colWidth = col.width;
                    if (parseInt(colWidth, 10) === colWidth) colWidth = colWidth + 'px';
                    col.container.css({width: colWidth});
                }
                if (p.params.rotateEffect) {
                    if (!col.width) {
                        col.items.each(function () {
                            var item = $(this);
                            item.css({width:'auto'});
                            colWidth = Math.max(colWidth, item[0].offsetWidth);
                            item.css({width:''});
                        });
                        col.container.css({width: (colWidth + 2) + 'px'});
                    }
                    col.container.addClass('picker-items-col-absolute');
                }
            };
            col.calcSize();

            col.wrapper.transform('translate3d(0,' + maxTranslate + 'px,0)').transition(0);


            var activeIndex = 0;
            var animationFrameId;

            // Set Value Function
            col.setValue = function (newValue, transition, valueCallbacks) {
                if (typeof transition === 'undefined') transition = '';
                var newActiveIndex = col.wrapper.find('.picker-item[data-picker-value="' + newValue + '"]').index();
                if(typeof newActiveIndex === 'undefined' || newActiveIndex === -1) {
                    return;
                }
                var newTranslate = -newActiveIndex * itemHeight + maxTranslate;
                // Update wrapper
                col.wrapper.transition(transition);
                col.wrapper.transform('translate3d(0,' + (newTranslate) + 'px,0)');

                // Watch items
                if (p.params.updateValuesOnMomentum && col.activeIndex && col.activeIndex !== newActiveIndex ) {
                    $.cancelAnimationFrame(animationFrameId);
                    col.wrapper.transitionEnd(function(){
                        $.cancelAnimationFrame(animationFrameId);
                    });
                    updateDuringScroll();
                }

                // Update items
                col.updateItems(newActiveIndex, newTranslate, transition, valueCallbacks);
            };

            col.updateItems = function (activeIndex, translate, transition, valueCallbacks) {
                if (typeof translate === 'undefined') {
                    translate = $.getTranslate(col.wrapper[0], 'y');
                }
                if(typeof activeIndex === 'undefined') activeIndex = -Math.round((translate - maxTranslate)/itemHeight);
                if (activeIndex < 0) activeIndex = 0;
                if (activeIndex >= col.items.length) activeIndex = col.items.length - 1;
                var previousActiveIndex = col.activeIndex;
                col.activeIndex = activeIndex;
                /*
                   col.wrapper.find('.picker-selected, .picker-after-selected, .picker-before-selected').removeClass('picker-selected picker-after-selected picker-before-selected');

                   col.items.transition(transition);
                   var selectedItem = col.items.eq(activeIndex).addClass('picker-selected').transform('');
                   var prevItems = selectedItem.prevAll().addClass('picker-before-selected');
                   var nextItems = selectedItem.nextAll().addClass('picker-after-selected');
                   */
                //去掉 .picker-after-selected, .picker-before-selected 以提高性能
                col.wrapper.find('.picker-selected').removeClass('picker-selected');
                if (p.params.rotateEffect) {
                    col.items.transition(transition);
                }
                var selectedItem = col.items.eq(activeIndex).addClass('picker-selected').transform('');

                if (valueCallbacks || typeof valueCallbacks === 'undefined') {
                    // Update values
                    col.value = selectedItem.attr('data-picker-value');
                    col.displayValue = col.displayValues ? col.displayValues[activeIndex] : col.value;
                    // On change callback
                    if (previousActiveIndex !== activeIndex) {
                        if (col.onChange) {
                            col.onChange(p, col.value, col.displayValue);
                        }
                        p.updateValue();
                    }
                }

                // Set 3D rotate effect
                if (!p.params.rotateEffect) {
                    return;
                }
                var percentage = (translate - (Math.floor((translate - maxTranslate)/itemHeight) * itemHeight + maxTranslate)) / itemHeight;

                col.items.each(function () {
                    var item = $(this);
                    var itemOffsetTop = item.index() * itemHeight;
                    var translateOffset = maxTranslate - translate;
                    var itemOffset = itemOffsetTop - translateOffset;
                    var percentage = itemOffset / itemHeight;

                    var itemsFit = Math.ceil(col.height / itemHeight / 2) + 1;

                    var angle = (-18*percentage);
                    if (angle > 180) angle = 180;
                    if (angle < -180) angle = -180;
                    // Far class
                    if (Math.abs(percentage) > itemsFit) item.addClass('picker-item-far');
                    else item.removeClass('picker-item-far');
                    // Set transform
                    item.transform('translate3d(0, ' + (-translate + maxTranslate) + 'px, ' + (originBug ? -110 : 0) + 'px) rotateX(' + angle + 'deg)');
                });
            };

            function updateDuringScroll() {
                animationFrameId = $.requestAnimationFrame(function () {
                    col.updateItems(undefined, undefined, 0);
                    updateDuringScroll();
                });
            }

            // Update items on init
            if (updateItems) col.updateItems(0, maxTranslate, 0);

            var allowItemClick = true;
            var isTouched, isMoved, touchStartY, touchCurrentY, touchStartTime, touchEndTime, startTranslate, returnTo, currentTranslate, prevTranslate, velocityTranslate, velocityTime;
            function handleTouchStart (e) {
                if (isMoved || isTouched) return;
                e.preventDefault();
                isTouched = true;
                touchStartY = touchCurrentY = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
                touchStartTime = (new Date()).getTime();

                allowItemClick = true;
                startTranslate = currentTranslate = $.getTranslate(col.wrapper[0], 'y');
            }
            function handleTouchMove (e) {
                if (!isTouched) return;
                e.preventDefault();
                allowItemClick = false;
                touchCurrentY = e.type === 'touchmove' ? e.targetTouches[0].pageY : e.pageY;
                if (!isMoved) {
                    // First move
                    $.cancelAnimationFrame(animationFrameId);
                    isMoved = true;
                    startTranslate = currentTranslate = $.getTranslate(col.wrapper[0], 'y');
                    col.wrapper.transition(0);
                }
                e.preventDefault();

                var diff = touchCurrentY - touchStartY;
                currentTranslate = startTranslate + diff;
                returnTo = undefined;

                // Normalize translate
                if (currentTranslate < minTranslate) {
                    currentTranslate = minTranslate - Math.pow(minTranslate - currentTranslate, 0.8);
                    returnTo = 'min';
                }
                if (currentTranslate > maxTranslate) {
                    currentTranslate = maxTranslate + Math.pow(currentTranslate - maxTranslate, 0.8);
                    returnTo = 'max';
                }
                // Transform wrapper
                col.wrapper.transform('translate3d(0,' + currentTranslate + 'px,0)');

                // Update items
                col.updateItems(undefined, currentTranslate, 0, p.params.updateValuesOnTouchmove);

                // Calc velocity
                velocityTranslate = currentTranslate - prevTranslate || currentTranslate;
                velocityTime = (new Date()).getTime();
                prevTranslate = currentTranslate;
            }
            function handleTouchEnd (e) {
                if (!isTouched || !isMoved) {
                    isTouched = isMoved = false;
                    return;
                }
                isTouched = isMoved = false;
                col.wrapper.transition('');
                if (returnTo) {
                    if (returnTo === 'min') {
                        col.wrapper.transform('translate3d(0,' + minTranslate + 'px,0)');
                    }
                    else col.wrapper.transform('translate3d(0,' + maxTranslate + 'px,0)');
                }
                touchEndTime = new Date().getTime();
                var velocity, newTranslate;
                if (touchEndTime - touchStartTime > 300) {
                    newTranslate = currentTranslate;
                }
                else {
                    velocity = Math.abs(velocityTranslate / (touchEndTime - velocityTime));
                    newTranslate = currentTranslate + velocityTranslate * p.params.momentumRatio;
                }

                newTranslate = Math.max(Math.min(newTranslate, maxTranslate), minTranslate);

                // Active Index
                var activeIndex = -Math.floor((newTranslate - maxTranslate)/itemHeight);

                // Normalize translate
                if (!p.params.freeMode) newTranslate = -activeIndex * itemHeight + maxTranslate;

                // Transform wrapper
                col.wrapper.transform('translate3d(0,' + (parseInt(newTranslate,10)) + 'px,0)');

                // Update items
                col.updateItems(activeIndex, newTranslate, '', true);

                // Watch items
                if (p.params.updateValuesOnMomentum) {
                    updateDuringScroll();
                    col.wrapper.transitionEnd(function(){
                        $.cancelAnimationFrame(animationFrameId);
                    });
                }

                // Allow click
                setTimeout(function () {
                    allowItemClick = true;
                }, 100);
            }

            function handleClick(e) {
                if (!allowItemClick) return;
                $.cancelAnimationFrame(animationFrameId);
                /*jshint validthis:true */
                var value = $(this).attr('data-picker-value');
                col.setValue(value);
            }

            col.initEvents = function (detach) {
                var method = detach ? 'off' : 'on';
                col.container[method]($.touchEvents.start, handleTouchStart);
                col.container[method]($.touchEvents.move, handleTouchMove);
                col.container[method]($.touchEvents.end, handleTouchEnd);
                col.items[method]('click', handleClick);
            };
            col.destroyEvents = function () {
                col.initEvents(true);
            };

            col.container[0].f7DestroyPickerCol = function () {
                col.destroyEvents();
            };

            col.initEvents();

        };
        p.destroyPickerCol = function (colContainer) {
            colContainer = $(colContainer);
            if ('f7DestroyPickerCol' in colContainer[0]) colContainer[0].f7DestroyPickerCol();
        };
        // Resize cols
        function resizeCols() {
            if (!p.opened) return;
            for (var i = 0; i < p.cols.length; i++) {
                if (!p.cols[i].divider) {
                    p.cols[i].calcSize();
                    p.cols[i].setValue(p.cols[i].value, 0, false);
                }
            }
        }
        $(window).on('resize', resizeCols);

        // HTML Layout
        p.columnHTML = function (col, onlyItems) {
            var columnItemsHTML = '';
            var columnHTML = '';
            if (col.divider) {
                columnHTML += '<div class="picker-items-col picker-items-col-divider ' + (col.textAlign ? 'picker-items-col-' + col.textAlign : '') + ' ' + (col.cssClass || '') + '">' + col.content + '</div>';
            }
            else {
                for (var j = 0; j < col.values.length; j++) {
                    columnItemsHTML += '<div class="picker-item" data-picker-value="' + col.values[j] + '">' + (col.displayValues ? col.displayValues[j] : col.values[j]) + '</div>';
                }

                columnHTML += '<div class="picker-items-col ' + (col.textAlign ? 'picker-items-col-' + col.textAlign : '') + ' ' + (col.cssClass || '') + '"><div class="picker-items-col-wrapper">' + columnItemsHTML + '</div></div>';
            }
            return onlyItems ? columnItemsHTML : columnHTML;
        };
        p.layout = function () {
            var pickerHTML = '';
            var pickerClass = '';
            var i;
            p.cols = [];
            var colsHTML = '';
            for (i = 0; i < p.params.cols.length; i++) {
                var col = p.params.cols[i];
                colsHTML += p.columnHTML(p.params.cols[i]);
                p.cols.push(col);
            }
            pickerClass = 'picker-modal picker-columns ' + (p.params.cssClass || '') + (p.params.rotateEffect ? ' picker-3d' : '');
            pickerHTML =
                '<div class="' + (pickerClass) + '">' +
                (p.params.toolbar ? p.params.toolbarTemplate.replace(/{{closeText}}/g, p.params.toolbarCloseText) : '') +
                '<div class="picker-modal-inner picker-items">' +
                colsHTML +
                '<div class="picker-center-highlight"></div>' +
                '</div>' +
                '</div>';

            p.pickerHTML = pickerHTML;
        };

        // Input Events
        function openOnInput(e) {
            e.preventDefault();
            // 安卓微信webviewreadonly的input依然弹出软键盘问题修复
            if ($.device.isWeixin && $.device.android && p.params.inputReadOnly) {
                /*jshint validthis:true */
                this.focus();
                this.blur();
            }
            if (p.opened) return;
            p.open();
            if (p.params.scrollToInput) {
                var pageContent = p.input.parents('.content');
                if (pageContent.length === 0) return;

                var paddingTop = parseInt(pageContent.css('padding-top'), 10),
                    paddingBottom = parseInt(pageContent.css('padding-bottom'), 10),
                    pageHeight = pageContent[0].offsetHeight - paddingTop - p.container.height(),
                    pageScrollHeight = pageContent[0].scrollHeight - paddingTop - p.container.height(),
                    newPaddingBottom;
                var inputTop = p.input.offset().top - paddingTop + p.input[0].offsetHeight;
                if (inputTop > pageHeight) {
                    var scrollTop = pageContent.scrollTop() + inputTop - pageHeight;
                    if (scrollTop + pageHeight > pageScrollHeight) {
                        newPaddingBottom = scrollTop + pageHeight - pageScrollHeight + paddingBottom;
                        if (pageHeight === pageScrollHeight) {
                            newPaddingBottom = p.container.height();
                        }
                        pageContent.css({'padding-bottom': (newPaddingBottom) + 'px'});
                    }
                    pageContent.scrollTop(scrollTop, 300);
                }
            }
        }
        function closeOnHTMLClick(e) {
            if (p.input && p.input.length > 0) {
                if (e.target !== p.input[0] && $(e.target).parents('.picker-modal').length === 0) p.close();
            }
            else {
                if ($(e.target).parents('.picker-modal').length === 0) p.close();
            }
        }

        if (p.params.input) {
            p.input = $(p.params.input);
            if (p.input.length > 0) {
                if (p.params.inputReadOnly) p.input.prop('readOnly', true);
                if (!p.inline) {
                    p.input.on('click', openOnInput);
                }
            }
        }

        if (!p.inline) $('html').on('click', closeOnHTMLClick);

        // Open
        function onPickerClose() {
            p.opened = false;
            if (p.input && p.input.length > 0) p.input.parents('.content').css({'padding-bottom': ''});
            if (p.params.onClose) p.params.onClose(p);

            // Destroy events
            p.container.find('.picker-items-col').each(function () {
                p.destroyPickerCol(this);
            });
        }

        p.opened = false;
        p.open = function () {
            if (!p.opened) {

                // Layout
                p.layout();

                // Append
                if (p.inline) {
                    p.container = $(p.pickerHTML);
                    p.container.addClass('picker-modal-inline');
                    $(p.params.container).append(p.container);
                }
                else {
                    p.container = $($.pickerModal(p.pickerHTML));
                    $(p.container)
                        .on('close', function () {
                            onPickerClose();
                        });
                }

                // Store picker instance
                p.container[0].f7Picker = p;

                // Init Events
                p.container.find('.picker-items-col').each(function () {
                    var updateItems = true;
                    if ((!p.initialized && p.params.value) || (p.initialized && p.value)) updateItems = false;
                    p.initPickerCol(this, updateItems);
                });

                // Set value
                if (!p.initialized) {
                    if (p.params.value) {
                        p.setValue(p.params.value, 0);
                    }
                }
                else {
                    if (p.value) p.setValue(p.value, 0);
                }
            }

            // Set flag
            p.opened = true;
            p.initialized = true;

            if (p.params.onOpen) p.params.onOpen(p);
        };

        // Close
        p.close = function () {
            if (!p.opened || p.inline) return;
            $.closeModal(p.container);
            return;
        };

        // Destroy
        p.destroy = function () {
            p.close();
            if (p.params.input && p.input.length > 0) {
                p.input.off('click', openOnInput);
            }
            $('html').off('click', closeOnHTMLClick);
            $(window).off('resize', resizeCols);
        };

        if (p.inline) {
            p.open();
        }

        return p;
    };

    $(document).on("click", ".close-picker", function() {
        var pickerToClose = $('.picker-modal.modal-in');
        $.closeModal(pickerToClose);
    });

    $.fn.picker = function(params) {
        var args = arguments;
        return this.each(function() {
            if(!this) return;
            var $this = $(this);

            var picker = $this.data("picker");
            if(!picker) {
                var p = $.extend({
                    input: this,
                    value: $this.val() ? $this.val().split(' ') : ''
                }, params);
                picker = new Picker(p);
                $this.data("picker", picker);
            }
            if(typeof params === typeof "a") {
                picker[params].apply(picker, Array.prototype.slice.call(args, 1));
            }
        });
    };
}(Zepto);

/* global Zepto:true */
/* jshint unused:false*/

+ function($) {
    "use strict";

    var today = new Date();

    var getDays = function(max) {
        var days = [];
        for(var i=1; i<= (max||31);i++) {
            days.push(i < 10 ? "0"+i : i);
        }
        return days;
    };

    var getDaysByMonthAndYear = function(month, year) {
        var int_d = new Date(year, parseInt(month)+1-1, 1);
        var d = new Date(int_d - 1);
        return getDays(d.getDate());
    };

    var formatNumber = function (n) {
        return n < 10 ? "0" + n : n;
    };

    var initMonthes = ('01 02 03 04 05 06 07 08 09 10 11 12').split(' ');

    var initYears = (function () {
        var arr = [];
        for (var i = 1950; i <= 2030; i++) { arr.push(i); }
        return arr;
    })();


    var defaults = {

        rotateEffect: false,  //为了性能

        value: [today.getFullYear(), formatNumber(today.getMonth()+1), formatNumber(today.getDate()), today.getHours(), formatNumber(today.getMinutes())],

        onChange: function (picker, values, displayValues) {
            var days = getDaysByMonthAndYear(picker.cols[1].value, picker.cols[0].value);
            var currentValue = picker.cols[2].value;
            if(currentValue > days.length) currentValue = days.length;
            picker.cols[2].setValue(currentValue);
        },

        formatValue: function (p, values, displayValues) {
            return displayValues[0] + '-' + values[1] + '-' + values[2] + ' ' + values[3] + ':' + values[4];
        },

        cols: [
            // Years
        {
            values: initYears
        },
        // Months
        {
            values: initMonthes
        },
        // Days
        {
            values: getDays()
        },

        // Space divider
        {
            divider: true,
            content: '  '
        },
        // Hours
        {
            values: (function () {
                var arr = [];
                for (var i = 0; i <= 23; i++) { arr.push(i); }
                return arr;
            })(),
        },
        // Divider
        {
            divider: true,
            content: ':'
        },
        // Minutes
        {
            values: (function () {
                var arr = [];
                for (var i = 0; i <= 59; i++) { arr.push(i < 10 ? '0' + i : i); }
                return arr;
            })(),
        }
        ]
    };

    $.fn.datetimePicker = function(params) {
        return this.each(function() {
            if(!this) return;
            var p = $.extend(defaults, params);
            $(this).picker(p);
            if (params.value) $(this).val(p.formatValue(p, p.value, p.value));
        });
    };

}(Zepto);

+ function(window) {

    "use strict";

    var rAF = window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function(callback) {
            window.setTimeout(callback, 1000 / 60);
        };
    /*var cRAF = window.cancelRequestAnimationFrame ||
        window.webkitCancelRequestAnimationFrame ||
        window.mozCancelRequestAnimationFrame ||
        window.oCancelRequestAnimationFrame ||
        window.msCancelRequestAnimationFrame;*/

    var utils = (function() {
        var me = {};

        var _elementStyle = document.createElement('div').style;
        var _vendor = (function() {
            var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
                transform,
                i = 0,
                l = vendors.length;

            for (; i < l; i++) {
                transform = vendors[i] + 'ransform';
                if (transform in _elementStyle) return vendors[i].substr(0, vendors[i].length - 1);
            }

            return false;
        })();

        function _prefixStyle(style) {
            if (_vendor === false) return false;
            if (_vendor === '') return style;
            return _vendor + style.charAt(0).toUpperCase() + style.substr(1);
        }

        me.getTime = Date.now || function getTime() {
            return new Date().getTime();
        };

        me.extend = function(target, obj) {
            for (var i in obj) {  // jshint ignore:line
                    target[i] = obj[i]; 
            }
        };

        me.addEvent = function(el, type, fn, capture) {
            el.addEventListener(type, fn, !!capture);
        };

        me.removeEvent = function(el, type, fn, capture) {
            el.removeEventListener(type, fn, !!capture);
        };

        me.prefixPointerEvent = function(pointerEvent) {
            return window.MSPointerEvent ?
                'MSPointer' + pointerEvent.charAt(9).toUpperCase() + pointerEvent.substr(10) :
                pointerEvent;
        };

        me.momentum = function(current, start, time, lowerMargin, wrapperSize, deceleration, self) {
            var distance = current - start,
                speed = Math.abs(distance) / time,
                destination,
                duration;

            // var absDistance = Math.abs(distance);
            speed = speed / 2; //slowdown
            speed = speed > 1.5 ? 1.5 : speed; //set max speed to 1
            deceleration = deceleration === undefined ? 0.0006 : deceleration;

            destination = current + (speed * speed) / (2 * deceleration) * (distance < 0 ? -1 : 1);
            duration = speed / deceleration;

            if (destination < lowerMargin) {
                destination = wrapperSize ? lowerMargin - (wrapperSize / 2.5 * (speed / 8)) : lowerMargin;
                distance = Math.abs(destination - current);
                duration = distance / speed;
            } else if (destination > 0) {
                destination = wrapperSize ? wrapperSize / 2.5 * (speed / 8) : 0;
                distance = Math.abs(current) + destination;
                duration = distance / speed;
            }

            //simple trigger, every 50ms
            var t = +new Date();
            var l = t;

            function eventTrigger() {
                if (+new Date() - l > 50) {
                    self._execEvent('scroll');
                    l = +new Date();
                }
                if (+new Date() - t < duration) {
                    rAF(eventTrigger);
                }
            }
            rAF(eventTrigger);

            return {
                destination: Math.round(destination),
                duration: duration
            };
        };

        var _transform = _prefixStyle('transform');

        me.extend(me, {
            hasTransform: _transform !== false,
            hasPerspective: _prefixStyle('perspective') in _elementStyle,
            hasTouch: 'ontouchstart' in window,
            hasPointer: window.PointerEvent || window.MSPointerEvent, // IE10 is prefixed
            hasTransition: _prefixStyle('transition') in _elementStyle
        });

        // This should find all Android browsers lower than build 535.19 (both stock browser and webview)
        me.isBadAndroid = /Android /.test(window.navigator.appVersion) && !(/Chrome\/\d/.test(window.navigator.appVersion)) && false; //this will cause many android device scroll flash; so set it to false!

        me.extend(me.style = {}, {
            transform: _transform,
            transitionTimingFunction: _prefixStyle('transitionTimingFunction'),
            transitionDuration: _prefixStyle('transitionDuration'),
            transitionDelay: _prefixStyle('transitionDelay'),
            transformOrigin: _prefixStyle('transformOrigin')
        });

        me.hasClass = function(e, c) {
            var re = new RegExp('(^|\\s)' + c + '(\\s|$)');
            return re.test(e.className);
        };

        me.addClass = function(e, c) {
            if (me.hasClass(e, c)) {
                return;
            }

            var newclass = e.className.split(' ');
            newclass.push(c);
            e.className = newclass.join(' ');
        };

        me.removeClass = function(e, c) {
            if (!me.hasClass(e, c)) {
                return;
            }

            var re = new RegExp('(^|\\s)' + c + '(\\s|$)', 'g');
            e.className = e.className.replace(re, ' ');
        };

        me.offset = function(el) {
            var left = -el.offsetLeft,
                top = -el.offsetTop;

            // jshint -W084
            while (el = el.offsetParent) {
                left -= el.offsetLeft;
                top -= el.offsetTop;
            }
            // jshint +W084

            return {
                left: left,
                top: top
            };
        };

        me.preventDefaultException = function(el, exceptions) {
            for (var i in exceptions) {
                if (exceptions[i].test(el[i])) {
                    return true;
                }
            }

            return false;
        };

        me.extend(me.eventType = {}, {
            touchstart: 1,
            touchmove: 1,
            touchend: 1,

            mousedown: 2,
            mousemove: 2,
            mouseup: 2,

            pointerdown: 3,
            pointermove: 3,
            pointerup: 3,

            MSPointerDown: 3,
            MSPointerMove: 3,
            MSPointerUp: 3
        });

        me.extend(me.ease = {}, {
            quadratic: {
                style: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                fn: function(k) {
                    return k * (2 - k);
                }
            },
            circular: {
                style: 'cubic-bezier(0.1, 0.57, 0.1, 1)', // Not properly 'circular' but this looks better, it should be (0.075, 0.82, 0.165, 1)
                fn: function(k) {
                    return Math.sqrt(1 - (--k * k));
                }
            },
            back: {
                style: 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                fn: function(k) {
                    var b = 4;
                    return (k = k - 1) * k * ((b + 1) * k + b) + 1;
                }
            },
            bounce: {
                style: '',
                fn: function(k) {
                    if ((k /= 1) < (1 / 2.75)) {
                        return 7.5625 * k * k;
                    } else if (k < (2 / 2.75)) {
                        return 7.5625 * (k -= (1.5 / 2.75)) * k + 0.75;
                    } else if (k < (2.5 / 2.75)) {
                        return 7.5625 * (k -= (2.25 / 2.75)) * k + 0.9375;
                    } else {
                        return 7.5625 * (k -= (2.625 / 2.75)) * k + 0.984375;
                    }
                }
            },
            elastic: {
                style: '',
                fn: function(k) {
                    var f = 0.22,
                        e = 0.4;

                    if (k === 0) {
                        return 0;
                    }
                    if (k === 1) {
                        return 1;
                    }

                    return (e * Math.pow(2, -10 * k) * Math.sin((k - f / 4) * (2 * Math.PI) / f) + 1);
                }
            }
        });

        me.tap = function(e, eventName) {
            var ev = document.createEvent('Event');
            ev.initEvent(eventName, true, true);
            ev.pageX = e.pageX;
            ev.pageY = e.pageY;
            e.target.dispatchEvent(ev);
        };

        me.click = function(e) {
            var target = e.target,
                ev;

            if (!(/(SELECT|INPUT|TEXTAREA)/i).test(target.tagName)) {
                ev = document.createEvent('MouseEvents');
                ev.initMouseEvent('click', true, true, e.view, 1,
                    target.screenX, target.screenY, target.clientX, target.clientY,
                    e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
                    0, null);

                ev._constructed = true;
                target.dispatchEvent(ev);
            }
        };

        return me;
    })();

    function IScroll(el, options) {
        this.wrapper = typeof el === 'string' ? document.querySelector(el) : el;
        this.scroller = $(this.wrapper).find('.content-inner')[0]; // jshint ignore:line


        this.scrollerStyle = this.scroller&&this.scroller.style; // cache style for better performance

        this.options = {

            resizeScrollbars: true,

            mouseWheelSpeed: 20,

            snapThreshold: 0.334,

            // INSERT POINT: OPTIONS 

            startX: 0,
            startY: 0,
            scrollY: true,
            directionLockThreshold: 5,
            momentum: true,

            bounce: true,
            bounceTime: 600,
            bounceEasing: '',

            preventDefault: true,
            preventDefaultException: {
                tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT)$/
            },

            HWCompositing: true,
            useTransition: true,
            useTransform: true,

            //other options
            eventPassthrough: undefined, //if you  want to use native scroll, you can set to: true or horizontal
        };

        for (var i in options) {
                this.options[i] = options[i];
        }

        // Normalize options
        this.translateZ = this.options.HWCompositing && utils.hasPerspective ? ' translateZ(0)' : '';

        this.options.useTransition = utils.hasTransition && this.options.useTransition;
        this.options.useTransform = utils.hasTransform && this.options.useTransform;

        this.options.eventPassthrough = this.options.eventPassthrough === true ? 'vertical' : this.options.eventPassthrough;
        this.options.preventDefault = !this.options.eventPassthrough && this.options.preventDefault;

        // If you want eventPassthrough I have to lock one of the axes
        this.options.scrollY = this.options.eventPassthrough === 'vertical' ? false : this.options.scrollY;
        this.options.scrollX = this.options.eventPassthrough === 'horizontal' ? false : this.options.scrollX;

        // With eventPassthrough we also need lockDirection mechanism
        this.options.freeScroll = this.options.freeScroll && !this.options.eventPassthrough;
        this.options.directionLockThreshold = this.options.eventPassthrough ? 0 : this.options.directionLockThreshold;

        this.options.bounceEasing = typeof this.options.bounceEasing === 'string' ? utils.ease[this.options.bounceEasing] || utils.ease.circular : this.options.bounceEasing;

        this.options.resizePolling = this.options.resizePolling === undefined ? 60 : this.options.resizePolling;

        if (this.options.tap === true) {
            this.options.tap = 'tap';
        }

        if (this.options.shrinkScrollbars === 'scale') {
            this.options.useTransition = false;
        }

        this.options.invertWheelDirection = this.options.invertWheelDirection ? -1 : 1;

        if (this.options.probeType === 3) {
            this.options.useTransition = false;
        }

        // INSERT POINT: NORMALIZATION

        // Some defaults    
        this.x = 0;
        this.y = 0;
        this.directionX = 0;
        this.directionY = 0;
        this._events = {};

        // INSERT POINT: DEFAULTS

        this._init();
        this.refresh();

        this.scrollTo(this.options.startX, this.options.startY);
        this.enable();
    }

    IScroll.prototype = {
        version: '5.1.3',

        _init: function() {
            this._initEvents();

            if (this.options.scrollbars || this.options.indicators) {
                this._initIndicators();
            }

            if (this.options.mouseWheel) {
                this._initWheel();
            }

            if (this.options.snap) {
                this._initSnap();
            }

            if (this.options.keyBindings) {
                this._initKeys();
            }

            // INSERT POINT: _init

        },

        destroy: function() {
            this._initEvents(true);

            this._execEvent('destroy');
        },

        _transitionEnd: function(e) {
            if (e.target !== this.scroller || !this.isInTransition) {
                return;
            }

            this._transitionTime();
            if (!this.resetPosition(this.options.bounceTime)) {
                this.isInTransition = false;
                this._execEvent('scrollEnd');
            }
        },

        _start: function(e) {
            // React to left mouse button only
            if (utils.eventType[e.type] !== 1) {
                if (e.button !== 0) {
                    return;
                }
            }

            if (!this.enabled || (this.initiated && utils.eventType[e.type] !== this.initiated)) {
                return;
            }

            if (this.options.preventDefault && !utils.isBadAndroid && !utils.preventDefaultException(e.target, this.options.preventDefaultException)) {
                e.preventDefault();
            }

            var point = e.touches ? e.touches[0] : e,
                pos;

            this.initiated = utils.eventType[e.type];
            this.moved = false;
            this.distX = 0;
            this.distY = 0;
            this.directionX = 0;
            this.directionY = 0;
            this.directionLocked = 0;

            this._transitionTime();

            this.startTime = utils.getTime();

            if (this.options.useTransition && this.isInTransition) {
                this.isInTransition = false;
                pos = this.getComputedPosition();
                this._translate(Math.round(pos.x), Math.round(pos.y));
                this._execEvent('scrollEnd');
            } else if (!this.options.useTransition && this.isAnimating) {
                this.isAnimating = false;
                this._execEvent('scrollEnd');
            }

            this.startX = this.x;
            this.startY = this.y;
            this.absStartX = this.x;
            this.absStartY = this.y;
            this.pointX = point.pageX;
            this.pointY = point.pageY;

            this._execEvent('beforeScrollStart');
        },

        _move: function(e) {
            if (!this.enabled || utils.eventType[e.type] !== this.initiated) {
                return;
            }

            if (this.options.preventDefault) { // increases performance on Android? TODO: check!
                e.preventDefault();
            }

            var point = e.touches ? e.touches[0] : e,
                deltaX = point.pageX - this.pointX,
                deltaY = point.pageY - this.pointY,
                timestamp = utils.getTime(),
                newX, newY,
                absDistX, absDistY;

            this.pointX = point.pageX;
            this.pointY = point.pageY;

            this.distX += deltaX;
            this.distY += deltaY;
            absDistX = Math.abs(this.distX);
            absDistY = Math.abs(this.distY);

            // We need to move at least 10 pixels for the scrolling to initiate
            if (timestamp - this.endTime > 300 && (absDistX < 10 && absDistY < 10)) {
                return;
            }

            // If you are scrolling in one direction lock the other
            if (!this.directionLocked && !this.options.freeScroll) {
                if (absDistX > absDistY + this.options.directionLockThreshold) {
                    this.directionLocked = 'h'; // lock horizontally
                } else if (absDistY >= absDistX + this.options.directionLockThreshold) {
                    this.directionLocked = 'v'; // lock vertically
                } else {
                    this.directionLocked = 'n'; // no lock
                }
            }

            if (this.directionLocked === 'h') {
                if (this.options.eventPassthrough === 'vertical') {
                    e.preventDefault();
                } else if (this.options.eventPassthrough === 'horizontal') {
                    this.initiated = false;
                    return;
                }

                deltaY = 0;
            } else if (this.directionLocked === 'v') {
                if (this.options.eventPassthrough === 'horizontal') {
                    e.preventDefault();
                } else if (this.options.eventPassthrough === 'vertical') {
                    this.initiated = false;
                    return;
                }

                deltaX = 0;
            }

            deltaX = this.hasHorizontalScroll ? deltaX : 0;
            deltaY = this.hasVerticalScroll ? deltaY : 0;

            newX = this.x + deltaX;
            newY = this.y + deltaY;

            // Slow down if outside of the boundaries
            if (newX > 0 || newX < this.maxScrollX) {
                newX = this.options.bounce ? this.x + deltaX / 3 : newX > 0 ? 0 : this.maxScrollX;
            }
            if (newY > 0 || newY < this.maxScrollY) {
                newY = this.options.bounce ? this.y + deltaY / 3 : newY > 0 ? 0 : this.maxScrollY;
            }

            this.directionX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
            this.directionY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

            if (!this.moved) {
                this._execEvent('scrollStart');
            }

            this.moved = true;

            this._translate(newX, newY);

            /* REPLACE START: _move */
            if (timestamp - this.startTime > 300) {
                this.startTime = timestamp;
                this.startX = this.x;
                this.startY = this.y;

                if (this.options.probeType === 1) {
                    this._execEvent('scroll');
                }
            }

            if (this.options.probeType > 1) {
                this._execEvent('scroll');
            }
            /* REPLACE END: _move */

        },

        _end: function(e) {
            if (!this.enabled || utils.eventType[e.type] !== this.initiated) {
                return;
            }

            if (this.options.preventDefault && !utils.preventDefaultException(e.target, this.options.preventDefaultException)) {
                e.preventDefault();
            }

            var /*point = e.changedTouches ? e.changedTouches[0] : e,*/
                momentumX,
                momentumY,
                duration = utils.getTime() - this.startTime,
                newX = Math.round(this.x),
                newY = Math.round(this.y),
                distanceX = Math.abs(newX - this.startX),
                distanceY = Math.abs(newY - this.startY),
                time = 0,
                easing = '';

            this.isInTransition = 0;
            this.initiated = 0;
            this.endTime = utils.getTime();

            // reset if we are outside of the boundaries
            if (this.resetPosition(this.options.bounceTime)) {
                return;
            }

            this.scrollTo(newX, newY); // ensures that the last position is rounded

            // we scrolled less than 10 pixels
            if (!this.moved) {
                if (this.options.tap) {
                    utils.tap(e, this.options.tap);
                }

                if (this.options.click) {
                    utils.click(e);
                }

                this._execEvent('scrollCancel');
                return;
            }

            if (this._events.flick && duration < 200 && distanceX < 100 && distanceY < 100) {
                this._execEvent('flick');
                return;
            }

            // start momentum animation if needed
            if (this.options.momentum && duration < 300) {
                momentumX = this.hasHorizontalScroll ? utils.momentum(this.x, this.startX, duration, this.maxScrollX, this.options.bounce ? this.wrapperWidth : 0, this.options.deceleration, this) : {
                    destination: newX,
                    duration: 0
                };
                momentumY = this.hasVerticalScroll ? utils.momentum(this.y, this.startY, duration, this.maxScrollY, this.options.bounce ? this.wrapperHeight : 0, this.options.deceleration, this) : {
                    destination: newY,
                    duration: 0
                };
                newX = momentumX.destination;
                newY = momentumY.destination;
                time = Math.max(momentumX.duration, momentumY.duration);
                this.isInTransition = 1;
            }


            if (this.options.snap) {
                var snap = this._nearestSnap(newX, newY);
                this.currentPage = snap;
                time = this.options.snapSpeed || Math.max(
                    Math.max(
                        Math.min(Math.abs(newX - snap.x), 1000),
                        Math.min(Math.abs(newY - snap.y), 1000)
                    ), 300);
                newX = snap.x;
                newY = snap.y;

                this.directionX = 0;
                this.directionY = 0;
                easing = this.options.bounceEasing;
            }

            // INSERT POINT: _end

            if (newX !== this.x || newY !== this.y) {
                // change easing function when scroller goes out of the boundaries
                if (newX > 0 || newX < this.maxScrollX || newY > 0 || newY < this.maxScrollY) {
                    easing = utils.ease.quadratic;
                }

                this.scrollTo(newX, newY, time, easing);
                return;
            }

            this._execEvent('scrollEnd');
        },

        _resize: function() {
            var that = this;

            clearTimeout(this.resizeTimeout);

            this.resizeTimeout = setTimeout(function() {
                that.refresh();
            }, this.options.resizePolling);
        },

        resetPosition: function(time) {
            var x = this.x,
                y = this.y;

            time = time || 0;

            if (!this.hasHorizontalScroll || this.x > 0) {
                x = 0;
            } else if (this.x < this.maxScrollX) {
                x = this.maxScrollX;
            }

            if (!this.hasVerticalScroll || this.y > 0) {
                y = 0;
            } else if (this.y < this.maxScrollY) {
                y = this.maxScrollY;
            }

            if (x === this.x && y === this.y) {
                return false;
            }

            if (this.options.ptr && this.y > 44 && this.startY * -1 < $(window).height() && !this.ptrLock) {// jshint ignore:line
                // not trigger ptr when user want to scroll to top
                y = this.options.ptrOffset || 44;
                this._execEvent('ptr');
                // 防止返回的过程中再次触发了 ptr ，导致被定位到 44px（因为可能done事件触发很快，在返回到44px以前就触发done
                this.ptrLock = true;
                var self = this;
                setTimeout(function() {
                    self.ptrLock = false;
                }, 500);
            }

            this.scrollTo(x, y, time, this.options.bounceEasing);

            return true;
        },

        disable: function() {
            this.enabled = false;
        },

        enable: function() {
            this.enabled = true;
        },

        refresh: function() {
            // var rf = this.wrapper.offsetHeight; // Force reflow

            this.wrapperWidth = this.wrapper.clientWidth;
            this.wrapperHeight = this.wrapper.clientHeight;

            /* REPLACE START: refresh */

            this.scrollerWidth = this.scroller.offsetWidth;
            this.scrollerHeight = this.scroller.offsetHeight;

            this.maxScrollX = this.wrapperWidth - this.scrollerWidth;
            this.maxScrollY = this.wrapperHeight - this.scrollerHeight;

            /* REPLACE END: refresh */

            this.hasHorizontalScroll = this.options.scrollX && this.maxScrollX < 0;
            this.hasVerticalScroll = this.options.scrollY && this.maxScrollY < 0;

            if (!this.hasHorizontalScroll) {
                this.maxScrollX = 0;
                this.scrollerWidth = this.wrapperWidth;
            }

            if (!this.hasVerticalScroll) {
                this.maxScrollY = 0;
                this.scrollerHeight = this.wrapperHeight;
            }

            this.endTime = 0;
            this.directionX = 0;
            this.directionY = 0;

            this.wrapperOffset = utils.offset(this.wrapper);

            this._execEvent('refresh');

            this.resetPosition();

            // INSERT POINT: _refresh

        },

        on: function(type, fn) {
            if (!this._events[type]) {
                this._events[type] = [];
            }

            this._events[type].push(fn);
        },

        off: function(type, fn) {
            if (!this._events[type]) {
                return;
            }

            var index = this._events[type].indexOf(fn);

            if (index > -1) {
                this._events[type].splice(index, 1);
            }
        },

        _execEvent: function(type) {
            if (!this._events[type]) {
                return;
            }

            var i = 0,
                l = this._events[type].length;

            if (!l) {
                return;
            }

            for (; i < l; i++) {
                this._events[type][i].apply(this, [].slice.call(arguments, 1));
            }
        },

        scrollBy: function(x, y, time, easing) {
            x = this.x + x;
            y = this.y + y;
            time = time || 0;

            this.scrollTo(x, y, time, easing);
        },

        scrollTo: function(x, y, time, easing) {
            easing = easing || utils.ease.circular;

            this.isInTransition = this.options.useTransition && time > 0;

            if (!time || (this.options.useTransition && easing.style)) {
                this._transitionTimingFunction(easing.style);
                this._transitionTime(time);
                this._translate(x, y);
            } else {
                this._animate(x, y, time, easing.fn);
            }
        },

        scrollToElement: function(el, time, offsetX, offsetY, easing) {
            el = el.nodeType ? el : this.scroller.querySelector(el);

            if (!el) {
                return;
            }

            var pos = utils.offset(el);

            pos.left -= this.wrapperOffset.left;
            pos.top -= this.wrapperOffset.top;

            // if offsetX/Y are true we center the element to the screen
            if (offsetX === true) {
                offsetX = Math.round(el.offsetWidth / 2 - this.wrapper.offsetWidth / 2);
            }
            if (offsetY === true) {
                offsetY = Math.round(el.offsetHeight / 2 - this.wrapper.offsetHeight / 2);
            }

            pos.left -= offsetX || 0;
            pos.top -= offsetY || 0;

            pos.left = pos.left > 0 ? 0 : pos.left < this.maxScrollX ? this.maxScrollX : pos.left;
            pos.top = pos.top > 0 ? 0 : pos.top < this.maxScrollY ? this.maxScrollY : pos.top;

            time = time === undefined || time === null || time === 'auto' ? Math.max(Math.abs(this.x - pos.left), Math.abs(this.y - pos.top)) : time;

            this.scrollTo(pos.left, pos.top, time, easing);
        },

        _transitionTime: function(time) {
            time = time || 0;

            this.scrollerStyle[utils.style.transitionDuration] = time + 'ms';

            if (!time && utils.isBadAndroid) {
                this.scrollerStyle[utils.style.transitionDuration] = '0.001s';
            }


            if (this.indicators) {
                for (var i = this.indicators.length; i--;) {
                    this.indicators[i].transitionTime(time);
                }
            }


            // INSERT POINT: _transitionTime

        },

        _transitionTimingFunction: function(easing) {
            this.scrollerStyle[utils.style.transitionTimingFunction] = easing;


            if (this.indicators) {
                for (var i = this.indicators.length; i--;) {
                    this.indicators[i].transitionTimingFunction(easing);
                }
            }


            // INSERT POINT: _transitionTimingFunction

        },

        _translate: function(x, y) {
            if (this.options.useTransform) {

                /* REPLACE START: _translate */

                this.scrollerStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.translateZ;

                /* REPLACE END: _translate */

            } else {
                x = Math.round(x);
                y = Math.round(y);
                this.scrollerStyle.left = x + 'px';
                this.scrollerStyle.top = y + 'px';
            }

            this.x = x;
            this.y = y;


            if (this.indicators) {
                for (var i = this.indicators.length; i--;) {
                    this.indicators[i].updatePosition();
                }
            }


            // INSERT POINT: _translate

        },

        _initEvents: function(remove) {
            var eventType = remove ? utils.removeEvent : utils.addEvent,
                target = this.options.bindToWrapper ? this.wrapper : window;

            eventType(window, 'orientationchange', this);
            eventType(window, 'resize', this);

            if (this.options.click) {
                eventType(this.wrapper, 'click', this, true);
            }

            if (!this.options.disableMouse) {
                eventType(this.wrapper, 'mousedown', this);
                eventType(target, 'mousemove', this);
                eventType(target, 'mousecancel', this);
                eventType(target, 'mouseup', this);
            }

            if (utils.hasPointer && !this.options.disablePointer) {
                eventType(this.wrapper, utils.prefixPointerEvent('pointerdown'), this);
                eventType(target, utils.prefixPointerEvent('pointermove'), this);
                eventType(target, utils.prefixPointerEvent('pointercancel'), this);
                eventType(target, utils.prefixPointerEvent('pointerup'), this);
            }

            if (utils.hasTouch && !this.options.disableTouch) {
                eventType(this.wrapper, 'touchstart', this);
                eventType(target, 'touchmove', this);
                eventType(target, 'touchcancel', this);
                eventType(target, 'touchend', this);
            }

            eventType(this.scroller, 'transitionend', this);
            eventType(this.scroller, 'webkitTransitionEnd', this);
            eventType(this.scroller, 'oTransitionEnd', this);
            eventType(this.scroller, 'MSTransitionEnd', this);
        },

        getComputedPosition: function() {
            var matrix = window.getComputedStyle(this.scroller, null),
                x, y;

            if (this.options.useTransform) {
                matrix = matrix[utils.style.transform].split(')')[0].split(', ');
                x = +(matrix[12] || matrix[4]);
                y = +(matrix[13] || matrix[5]);
            } else {
                x = +matrix.left.replace(/[^-\d.]/g, '');
                y = +matrix.top.replace(/[^-\d.]/g, '');
            }

            return {
                x: x,
                y: y
            };
        },

        _initIndicators: function() {
            var interactive = this.options.interactiveScrollbars,
                customStyle = typeof this.options.scrollbars !== 'string',
                indicators = [],
                indicator;

            var that = this;

            this.indicators = [];

            if (this.options.scrollbars) {
                // Vertical scrollbar
                if (this.options.scrollY) {
                    indicator = {
                        el: createDefaultScrollbar('v', interactive, this.options.scrollbars),
                        interactive: interactive,
                        defaultScrollbars: true,
                        customStyle: customStyle,
                        resize: this.options.resizeScrollbars,
                        shrink: this.options.shrinkScrollbars,
                        fade: this.options.fadeScrollbars,
                        listenX: false
                    };

                    this.wrapper.appendChild(indicator.el);
                    indicators.push(indicator);
                }

                // Horizontal scrollbar
                if (this.options.scrollX) {
                    indicator = {
                        el: createDefaultScrollbar('h', interactive, this.options.scrollbars),
                        interactive: interactive,
                        defaultScrollbars: true,
                        customStyle: customStyle,
                        resize: this.options.resizeScrollbars,
                        shrink: this.options.shrinkScrollbars,
                        fade: this.options.fadeScrollbars,
                        listenY: false
                    };

                    this.wrapper.appendChild(indicator.el);
                    indicators.push(indicator);
                }
            }

            if (this.options.indicators) {
                // TODO: check concat compatibility
                indicators = indicators.concat(this.options.indicators);
            }

            for (var i = indicators.length; i--;) {
                this.indicators.push(new Indicator(this, indicators[i]));
            }

            // TODO: check if we can use array.map (wide compatibility and performance issues)
            function _indicatorsMap(fn) {
                for (var i = that.indicators.length; i--;) {
                    fn.call(that.indicators[i]);
                }
            }

            if (this.options.fadeScrollbars) {
                this.on('scrollEnd', function() {
                    _indicatorsMap(function() {
                        this.fade();
                    });
                });

                this.on('scrollCancel', function() {
                    _indicatorsMap(function() {
                        this.fade();
                    });
                });

                this.on('scrollStart', function() {
                    _indicatorsMap(function() {
                        this.fade(1);
                    });
                });

                this.on('beforeScrollStart', function() {
                    _indicatorsMap(function() {
                        this.fade(1, true);
                    });
                });
            }


            this.on('refresh', function() {
                _indicatorsMap(function() {
                    this.refresh();
                });
            });

            this.on('destroy', function() {
                _indicatorsMap(function() {
                    this.destroy();
                });

                delete this.indicators;
            });
        },

        _initWheel: function() {
            utils.addEvent(this.wrapper, 'wheel', this);
            utils.addEvent(this.wrapper, 'mousewheel', this);
            utils.addEvent(this.wrapper, 'DOMMouseScroll', this);

            this.on('destroy', function() {
                utils.removeEvent(this.wrapper, 'wheel', this);
                utils.removeEvent(this.wrapper, 'mousewheel', this);
                utils.removeEvent(this.wrapper, 'DOMMouseScroll', this);
            });
        },

        _wheel: function(e) {
            if (!this.enabled) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            var wheelDeltaX, wheelDeltaY,
                newX, newY,
                that = this;

            if (this.wheelTimeout === undefined) {
                that._execEvent('scrollStart');
            }

            // Execute the scrollEnd event after 400ms the wheel stopped scrolling
            clearTimeout(this.wheelTimeout);
            this.wheelTimeout = setTimeout(function() {
                that._execEvent('scrollEnd');
                that.wheelTimeout = undefined;
            }, 400);

            if ('deltaX' in e) {
                if (e.deltaMode === 1) {
                    wheelDeltaX = -e.deltaX * this.options.mouseWheelSpeed;
                    wheelDeltaY = -e.deltaY * this.options.mouseWheelSpeed;
                } else {
                    wheelDeltaX = -e.deltaX;
                    wheelDeltaY = -e.deltaY;
                }
            } else if ('wheelDeltaX' in e) {
                wheelDeltaX = e.wheelDeltaX / 120 * this.options.mouseWheelSpeed;
                wheelDeltaY = e.wheelDeltaY / 120 * this.options.mouseWheelSpeed;
            } else if ('wheelDelta' in e) {
                wheelDeltaX = wheelDeltaY = e.wheelDelta / 120 * this.options.mouseWheelSpeed;
            } else if ('detail' in e) {
                wheelDeltaX = wheelDeltaY = -e.detail / 3 * this.options.mouseWheelSpeed;
            } else {
                return;
            }

            wheelDeltaX *= this.options.invertWheelDirection;
            wheelDeltaY *= this.options.invertWheelDirection;

            if (!this.hasVerticalScroll) {
                wheelDeltaX = wheelDeltaY;
                wheelDeltaY = 0;
            }

            if (this.options.snap) {
                newX = this.currentPage.pageX;
                newY = this.currentPage.pageY;

                if (wheelDeltaX > 0) {
                    newX--;
                } else if (wheelDeltaX < 0) {
                    newX++;
                }

                if (wheelDeltaY > 0) {
                    newY--;
                } else if (wheelDeltaY < 0) {
                    newY++;
                }

                this.goToPage(newX, newY);

                return;
            }

            newX = this.x + Math.round(this.hasHorizontalScroll ? wheelDeltaX : 0);
            newY = this.y + Math.round(this.hasVerticalScroll ? wheelDeltaY : 0);

            if (newX > 0) {
                newX = 0;
            } else if (newX < this.maxScrollX) {
                newX = this.maxScrollX;
            }

            if (newY > 0) {
                newY = 0;
            } else if (newY < this.maxScrollY) {
                newY = this.maxScrollY;
            }

            this.scrollTo(newX, newY, 0);

            this._execEvent('scroll');

            // INSERT POINT: _wheel
        },

        _initSnap: function() {
            this.currentPage = {};

            if (typeof this.options.snap === 'string') {
                this.options.snap = this.scroller.querySelectorAll(this.options.snap);
            }

            this.on('refresh', function() {
                var i = 0,
                    l,
                    m = 0,
                    n,
                    cx, cy,
                    x = 0,
                    y,
                    stepX = this.options.snapStepX || this.wrapperWidth,
                    stepY = this.options.snapStepY || this.wrapperHeight,
                    el;

                this.pages = [];

                if (!this.wrapperWidth || !this.wrapperHeight || !this.scrollerWidth || !this.scrollerHeight) {
                    return;
                }

                if (this.options.snap === true) {
                    cx = Math.round(stepX / 2);
                    cy = Math.round(stepY / 2);

                    while (x > -this.scrollerWidth) {
                        this.pages[i] = [];
                        l = 0;
                        y = 0;

                        while (y > -this.scrollerHeight) {
                            this.pages[i][l] = {
                                x: Math.max(x, this.maxScrollX),
                                y: Math.max(y, this.maxScrollY),
                                width: stepX,
                                height: stepY,
                                cx: x - cx,
                                cy: y - cy
                            };

                            y -= stepY;
                            l++;
                        }

                        x -= stepX;
                        i++;
                    }
                } else {
                    el = this.options.snap;
                    l = el.length;
                    n = -1;

                    for (; i < l; i++) {
                        if (i === 0 || el[i].offsetLeft <= el[i - 1].offsetLeft) {
                            m = 0;
                            n++;
                        }

                        if (!this.pages[m]) {
                            this.pages[m] = [];
                        }

                        x = Math.max(-el[i].offsetLeft, this.maxScrollX);
                        y = Math.max(-el[i].offsetTop, this.maxScrollY);
                        cx = x - Math.round(el[i].offsetWidth / 2);
                        cy = y - Math.round(el[i].offsetHeight / 2);

                        this.pages[m][n] = {
                            x: x,
                            y: y,
                            width: el[i].offsetWidth,
                            height: el[i].offsetHeight,
                            cx: cx,
                            cy: cy
                        };

                        if (x > this.maxScrollX) {
                            m++;
                        }
                    }
                }

                this.goToPage(this.currentPage.pageX || 0, this.currentPage.pageY || 0, 0);

                // Update snap threshold if needed
                if (this.options.snapThreshold % 1 === 0) {
                    this.snapThresholdX = this.options.snapThreshold;
                    this.snapThresholdY = this.options.snapThreshold;
                } else {
                    this.snapThresholdX = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].width * this.options.snapThreshold);
                    this.snapThresholdY = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].height * this.options.snapThreshold);
                }
            });

            this.on('flick', function() {
                var time = this.options.snapSpeed || Math.max(
                    Math.max(
                        Math.min(Math.abs(this.x - this.startX), 1000),
                        Math.min(Math.abs(this.y - this.startY), 1000)
                    ), 300);

                this.goToPage(
                    this.currentPage.pageX + this.directionX,
                    this.currentPage.pageY + this.directionY,
                    time
                );
            });
        },

        _nearestSnap: function(x, y) {
            if (!this.pages.length) {
                return {
                    x: 0,
                    y: 0,
                    pageX: 0,
                    pageY: 0
                };
            }

            var i = 0,
                l = this.pages.length,
                m = 0;

            // Check if we exceeded the snap threshold
            if (Math.abs(x - this.absStartX) < this.snapThresholdX &&
                Math.abs(y - this.absStartY) < this.snapThresholdY) {
                return this.currentPage;
            }

            if (x > 0) {
                x = 0;
            } else if (x < this.maxScrollX) {
                x = this.maxScrollX;
            }

            if (y > 0) {
                y = 0;
            } else if (y < this.maxScrollY) {
                y = this.maxScrollY;
            }

            for (; i < l; i++) {
                if (x >= this.pages[i][0].cx) {
                    x = this.pages[i][0].x;
                    break;
                }
            }

            l = this.pages[i].length;

            for (; m < l; m++) {
                if (y >= this.pages[0][m].cy) {
                    y = this.pages[0][m].y;
                    break;
                }
            }

            if (i === this.currentPage.pageX) {
                i += this.directionX;

                if (i < 0) {
                    i = 0;
                } else if (i >= this.pages.length) {
                    i = this.pages.length - 1;
                }

                x = this.pages[i][0].x;
            }

            if (m === this.currentPage.pageY) {
                m += this.directionY;

                if (m < 0) {
                    m = 0;
                } else if (m >= this.pages[0].length) {
                    m = this.pages[0].length - 1;
                }

                y = this.pages[0][m].y;
            }

            return {
                x: x,
                y: y,
                pageX: i,
                pageY: m
            };
        },

        goToPage: function(x, y, time, easing) {
            easing = easing || this.options.bounceEasing;

            if (x >= this.pages.length) {
                x = this.pages.length - 1;
            } else if (x < 0) {
                x = 0;
            }

            if (y >= this.pages[x].length) {
                y = this.pages[x].length - 1;
            } else if (y < 0) {
                y = 0;
            }

            var posX = this.pages[x][y].x,
                posY = this.pages[x][y].y;

            time = time === undefined ? this.options.snapSpeed || Math.max(
                Math.max(
                    Math.min(Math.abs(posX - this.x), 1000),
                    Math.min(Math.abs(posY - this.y), 1000)
                ), 300) : time;

            this.currentPage = {
                x: posX,
                y: posY,
                pageX: x,
                pageY: y
            };

            this.scrollTo(posX, posY, time, easing);
        },

        next: function(time, easing) {
            var x = this.currentPage.pageX,
                y = this.currentPage.pageY;

            x++;

            if (x >= this.pages.length && this.hasVerticalScroll) {
                x = 0;
                y++;
            }

            this.goToPage(x, y, time, easing);
        },

        prev: function(time, easing) {
            var x = this.currentPage.pageX,
                y = this.currentPage.pageY;

            x--;

            if (x < 0 && this.hasVerticalScroll) {
                x = 0;
                y--;
            }

            this.goToPage(x, y, time, easing);
        },

        _initKeys: function() {
            // default key bindings
            var keys = {
                pageUp: 33,
                pageDown: 34,
                end: 35,
                home: 36,
                left: 37,
                up: 38,
                right: 39,
                down: 40
            };
            var i;

            // if you give me characters I give you keycode
            if (typeof this.options.keyBindings === 'object') {
                for (i in this.options.keyBindings) {
                    if (typeof this.options.keyBindings[i] === 'string') {
                        this.options.keyBindings[i] = this.options.keyBindings[i].toUpperCase().charCodeAt(0);
                    }
                }
            } else {
                this.options.keyBindings = {};
            }

            for (i in keys) { // jshint ignore:line
                    this.options.keyBindings[i] = this.options.keyBindings[i] || keys[i];
            }

            utils.addEvent(window, 'keydown', this);

            this.on('destroy', function() {
                utils.removeEvent(window, 'keydown', this);
            });
        },

        _key: function(e) {
            if (!this.enabled) {
                return;
            }

            var snap = this.options.snap, // we are using this alot, better to cache it
                newX = snap ? this.currentPage.pageX : this.x,
                newY = snap ? this.currentPage.pageY : this.y,
                now = utils.getTime(),
                prevTime = this.keyTime || 0,
                acceleration = 0.250,
                pos;

            if (this.options.useTransition && this.isInTransition) {
                pos = this.getComputedPosition();

                this._translate(Math.round(pos.x), Math.round(pos.y));
                this.isInTransition = false;
            }

            this.keyAcceleration = now - prevTime < 200 ? Math.min(this.keyAcceleration + acceleration, 50) : 0;

            switch (e.keyCode) {
                case this.options.keyBindings.pageUp:
                    if (this.hasHorizontalScroll && !this.hasVerticalScroll) {
                        newX += snap ? 1 : this.wrapperWidth;
                    } else {
                        newY += snap ? 1 : this.wrapperHeight;
                    }
                    break;
                case this.options.keyBindings.pageDown:
                    if (this.hasHorizontalScroll && !this.hasVerticalScroll) {
                        newX -= snap ? 1 : this.wrapperWidth;
                    } else {
                        newY -= snap ? 1 : this.wrapperHeight;
                    }
                    break;
                case this.options.keyBindings.end:
                    newX = snap ? this.pages.length - 1 : this.maxScrollX;
                    newY = snap ? this.pages[0].length - 1 : this.maxScrollY;
                    break;
                case this.options.keyBindings.home:
                    newX = 0;
                    newY = 0;
                    break;
                case this.options.keyBindings.left:
                    newX += snap ? -1 : 5 + this.keyAcceleration >> 0; // jshint ignore:line
                    break;
                case this.options.keyBindings.up:
                    newY += snap ? 1 : 5 + this.keyAcceleration >> 0; // jshint ignore:line
                    break;
                case this.options.keyBindings.right:
                    newX -= snap ? -1 : 5 + this.keyAcceleration >> 0; // jshint ignore:line
                    break;
                case this.options.keyBindings.down:
                    newY -= snap ? 1 : 5 + this.keyAcceleration >> 0; // jshint ignore:line
                    break;
                default:
                    return;
            }

            if (snap) {
                this.goToPage(newX, newY);
                return;
            }

            if (newX > 0) {
                newX = 0;
                this.keyAcceleration = 0;
            } else if (newX < this.maxScrollX) {
                newX = this.maxScrollX;
                this.keyAcceleration = 0;
            }

            if (newY > 0) {
                newY = 0;
                this.keyAcceleration = 0;
            } else if (newY < this.maxScrollY) {
                newY = this.maxScrollY;
                this.keyAcceleration = 0;
            }

            this.scrollTo(newX, newY, 0);

            this.keyTime = now;
        },

        _animate: function(destX, destY, duration, easingFn) {
            var that = this,
                startX = this.x,
                startY = this.y,
                startTime = utils.getTime(),
                destTime = startTime + duration;

            function step() {
                var now = utils.getTime(),
                    newX, newY,
                    easing;

                if (now >= destTime) {
                    that.isAnimating = false;
                    that._translate(destX, destY);

                    if (!that.resetPosition(that.options.bounceTime)) {
                        that._execEvent('scrollEnd');
                    }

                    return;
                }

                now = (now - startTime) / duration;
                easing = easingFn(now);
                newX = (destX - startX) * easing + startX;
                newY = (destY - startY) * easing + startY;
                that._translate(newX, newY);

                if (that.isAnimating) {
                    rAF(step);
                }

                if (that.options.probeType === 3) {
                    that._execEvent('scroll');
                }
            }

            this.isAnimating = true;
            step();
        },

        handleEvent: function(e) {
            switch (e.type) {
                case 'touchstart':
                case 'pointerdown':
                case 'MSPointerDown':
                case 'mousedown':
                    this._start(e);
                    break;
                case 'touchmove':
                case 'pointermove':
                case 'MSPointerMove':
                case 'mousemove':
                    this._move(e);
                    break;
                case 'touchend':
                case 'pointerup':
                case 'MSPointerUp':
                case 'mouseup':
                case 'touchcancel':
                case 'pointercancel':
                case 'MSPointerCancel':
                case 'mousecancel':
                    this._end(e);
                    break;
                case 'orientationchange':
                case 'resize':
                    this._resize();
                    break;
                case 'transitionend':
                case 'webkitTransitionEnd':
                case 'oTransitionEnd':
                case 'MSTransitionEnd':
                    this._transitionEnd(e);
                    break;
                case 'wheel':
                case 'DOMMouseScroll':
                case 'mousewheel':
                    this._wheel(e);
                    break;
                case 'keydown':
                    this._key(e);
                    break;
                case 'click':
                    if (!e._constructed) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    break;
            }
        }
    };

    function createDefaultScrollbar(direction, interactive, type) {
        var scrollbar = document.createElement('div'),
            indicator = document.createElement('div');

        if (type === true) {
            scrollbar.style.cssText = 'position:absolute;z-index:9999';
            indicator.style.cssText = '-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);border-radius:3px';
        }

        indicator.className = 'iScrollIndicator';

        if (direction === 'h') {
            if (type === true) {
                scrollbar.style.cssText += ';height:5px;left:2px;right:2px;bottom:0';
                indicator.style.height = '100%';
            }
            scrollbar.className = 'iScrollHorizontalScrollbar';
        } else {
            if (type === true) {
                scrollbar.style.cssText += ';width:5px;bottom:2px;top:2px;right:1px';
                indicator.style.width = '100%';
            }
            scrollbar.className = 'iScrollVerticalScrollbar';
        }

        scrollbar.style.cssText += ';overflow:hidden';

        if (!interactive) {
            scrollbar.style.pointerEvents = 'none';
        }

        scrollbar.appendChild(indicator);

        return scrollbar;
    }

    function Indicator(scroller, options) {
        this.wrapper = typeof options.el === 'string' ? document.querySelector(options.el) : options.el;
        this.wrapperStyle = this.wrapper.style;
        this.indicator = this.wrapper.children[0];
        this.indicatorStyle = this.indicator.style;
        this.scroller = scroller;

        this.options = {
            listenX: true,
            listenY: true,
            interactive: false,
            resize: true,
            defaultScrollbars: false,
            shrink: false,
            fade: false,
            speedRatioX: 0,
            speedRatioY: 0
        };

        for (var i in options) { // jshint ignore:line
                this.options[i] = options[i];

        }

        this.sizeRatioX = 1;
        this.sizeRatioY = 1;
        this.maxPosX = 0;
        this.maxPosY = 0;

        if (this.options.interactive) {
            if (!this.options.disableTouch) {
                utils.addEvent(this.indicator, 'touchstart', this);
                utils.addEvent(window, 'touchend', this);
            }
            if (!this.options.disablePointer) {
                utils.addEvent(this.indicator, utils.prefixPointerEvent('pointerdown'), this);
                utils.addEvent(window, utils.prefixPointerEvent('pointerup'), this);
            }
            if (!this.options.disableMouse) {
                utils.addEvent(this.indicator, 'mousedown', this);
                utils.addEvent(window, 'mouseup', this);
            }
        }

        if (this.options.fade) {
            this.wrapperStyle[utils.style.transform] = this.scroller.translateZ;
            this.wrapperStyle[utils.style.transitionDuration] = utils.isBadAndroid ? '0.001s' : '0ms';
            this.wrapperStyle.opacity = '0';
        }
    }

    Indicator.prototype = {
        handleEvent: function(e) {
            switch (e.type) {
                case 'touchstart':
                case 'pointerdown':
                case 'MSPointerDown':
                case 'mousedown':
                    this._start(e);
                    break;
                case 'touchmove':
                case 'pointermove':
                case 'MSPointerMove':
                case 'mousemove':
                    this._move(e);
                    break;
                case 'touchend':
                case 'pointerup':
                case 'MSPointerUp':
                case 'mouseup':
                case 'touchcancel':
                case 'pointercancel':
                case 'MSPointerCancel':
                case 'mousecancel':
                    this._end(e);
                    break;
            }
        },

        destroy: function() {
            if (this.options.interactive) {
                utils.removeEvent(this.indicator, 'touchstart', this);
                utils.removeEvent(this.indicator, utils.prefixPointerEvent('pointerdown'), this);
                utils.removeEvent(this.indicator, 'mousedown', this);

                utils.removeEvent(window, 'touchmove', this);
                utils.removeEvent(window, utils.prefixPointerEvent('pointermove'), this);
                utils.removeEvent(window, 'mousemove', this);

                utils.removeEvent(window, 'touchend', this);
                utils.removeEvent(window, utils.prefixPointerEvent('pointerup'), this);
                utils.removeEvent(window, 'mouseup', this);
            }

            if (this.options.defaultScrollbars) {
                this.wrapper.parentNode.removeChild(this.wrapper);
            }
        },

        _start: function(e) {
            var point = e.touches ? e.touches[0] : e;

            e.preventDefault();
            e.stopPropagation();

            this.transitionTime();

            this.initiated = true;
            this.moved = false;
            this.lastPointX = point.pageX;
            this.lastPointY = point.pageY;

            this.startTime = utils.getTime();

            if (!this.options.disableTouch) {
                utils.addEvent(window, 'touchmove', this);
            }
            if (!this.options.disablePointer) {
                utils.addEvent(window, utils.prefixPointerEvent('pointermove'), this);
            }
            if (!this.options.disableMouse) {
                utils.addEvent(window, 'mousemove', this);
            }

            this.scroller._execEvent('beforeScrollStart');
        },

        _move: function(e) {
            var point = e.touches ? e.touches[0] : e,
                deltaX, deltaY,
                newX, newY,
                timestamp = utils.getTime();

            if (!this.moved) {
                this.scroller._execEvent('scrollStart');
            }

            this.moved = true;

            deltaX = point.pageX - this.lastPointX;
            this.lastPointX = point.pageX;

            deltaY = point.pageY - this.lastPointY;
            this.lastPointY = point.pageY;

            newX = this.x + deltaX;
            newY = this.y + deltaY;

            this._pos(newX, newY);


            if (this.scroller.options.probeType === 1 && timestamp - this.startTime > 300) {
                this.startTime = timestamp;
                this.scroller._execEvent('scroll');
            } else if (this.scroller.options.probeType > 1) {
                this.scroller._execEvent('scroll');
            }


            // INSERT POINT: indicator._move

            e.preventDefault();
            e.stopPropagation();
        },

        _end: function(e) {
            if (!this.initiated) {
                return;
            }

            this.initiated = false;

            e.preventDefault();
            e.stopPropagation();

            utils.removeEvent(window, 'touchmove', this);
            utils.removeEvent(window, utils.prefixPointerEvent('pointermove'), this);
            utils.removeEvent(window, 'mousemove', this);

            if (this.scroller.options.snap) {
                var snap = this.scroller._nearestSnap(this.scroller.x, this.scroller.y);

                var time = this.options.snapSpeed || Math.max(
                    Math.max(
                        Math.min(Math.abs(this.scroller.x - snap.x), 1000),
                        Math.min(Math.abs(this.scroller.y - snap.y), 1000)
                    ), 300);

                if (this.scroller.x !== snap.x || this.scroller.y !== snap.y) {
                    this.scroller.directionX = 0;
                    this.scroller.directionY = 0;
                    this.scroller.currentPage = snap;
                    this.scroller.scrollTo(snap.x, snap.y, time, this.scroller.options.bounceEasing);
                }
            }

            if (this.moved) {
                this.scroller._execEvent('scrollEnd');
            }
        },

        transitionTime: function(time) {
            time = time || 0;
            this.indicatorStyle[utils.style.transitionDuration] = time + 'ms';

            if (!time && utils.isBadAndroid) {
                this.indicatorStyle[utils.style.transitionDuration] = '0.001s';
            }
        },

        transitionTimingFunction: function(easing) {
            this.indicatorStyle[utils.style.transitionTimingFunction] = easing;
        },

        refresh: function() {
            this.transitionTime();

            if (this.options.listenX && !this.options.listenY) {
                this.indicatorStyle.display = this.scroller.hasHorizontalScroll ? 'block' : 'none';
            } else if (this.options.listenY && !this.options.listenX) {
                this.indicatorStyle.display = this.scroller.hasVerticalScroll ? 'block' : 'none';
            } else {
                this.indicatorStyle.display = this.scroller.hasHorizontalScroll || this.scroller.hasVerticalScroll ? 'block' : 'none';
            }

            if (this.scroller.hasHorizontalScroll && this.scroller.hasVerticalScroll) {
                utils.addClass(this.wrapper, 'iScrollBothScrollbars');
                utils.removeClass(this.wrapper, 'iScrollLoneScrollbar');

                if (this.options.defaultScrollbars && this.options.customStyle) {
                    if (this.options.listenX) {
                        this.wrapper.style.right = '8px';
                    } else {
                        this.wrapper.style.bottom = '8px';
                    }
                }
            } else {
                utils.removeClass(this.wrapper, 'iScrollBothScrollbars');
                utils.addClass(this.wrapper, 'iScrollLoneScrollbar');

                if (this.options.defaultScrollbars && this.options.customStyle) {
                    if (this.options.listenX) {
                        this.wrapper.style.right = '2px';
                    } else {
                        this.wrapper.style.bottom = '2px';
                    }
                }
            }

            // var r = this.wrapper.offsetHeight; // force refresh

            if (this.options.listenX) {
                this.wrapperWidth = this.wrapper.clientWidth;
                if (this.options.resize) {
                    this.indicatorWidth = Math.max(Math.round(this.wrapperWidth * this.wrapperWidth / (this.scroller.scrollerWidth || this.wrapperWidth || 1)), 8);
                    this.indicatorStyle.width = this.indicatorWidth + 'px';
                } else {
                    this.indicatorWidth = this.indicator.clientWidth;
                }

                this.maxPosX = this.wrapperWidth - this.indicatorWidth;

                if (this.options.shrink === 'clip') {
                    this.minBoundaryX = -this.indicatorWidth + 8;
                    this.maxBoundaryX = this.wrapperWidth - 8;
                } else {
                    this.minBoundaryX = 0;
                    this.maxBoundaryX = this.maxPosX;
                }

                this.sizeRatioX = this.options.speedRatioX || (this.scroller.maxScrollX && (this.maxPosX / this.scroller.maxScrollX));
            }

            if (this.options.listenY) {
                this.wrapperHeight = this.wrapper.clientHeight;
                if (this.options.resize) {
                    this.indicatorHeight = Math.max(Math.round(this.wrapperHeight * this.wrapperHeight / (this.scroller.scrollerHeight || this.wrapperHeight || 1)), 8);
                    this.indicatorStyle.height = this.indicatorHeight + 'px';
                } else {
                    this.indicatorHeight = this.indicator.clientHeight;
                }

                this.maxPosY = this.wrapperHeight - this.indicatorHeight;

                if (this.options.shrink === 'clip') {
                    this.minBoundaryY = -this.indicatorHeight + 8;
                    this.maxBoundaryY = this.wrapperHeight - 8;
                } else {
                    this.minBoundaryY = 0;
                    this.maxBoundaryY = this.maxPosY;
                }

                this.maxPosY = this.wrapperHeight - this.indicatorHeight;
                this.sizeRatioY = this.options.speedRatioY || (this.scroller.maxScrollY && (this.maxPosY / this.scroller.maxScrollY));
            }

            this.updatePosition();
        },

        updatePosition: function() {
            var x = this.options.listenX && Math.round(this.sizeRatioX * this.scroller.x) || 0,
                y = this.options.listenY && Math.round(this.sizeRatioY * this.scroller.y) || 0;

            if (!this.options.ignoreBoundaries) {
                if (x < this.minBoundaryX) {
                    if (this.options.shrink === 'scale') {
                        this.width = Math.max(this.indicatorWidth + x, 8);
                        this.indicatorStyle.width = this.width + 'px';
                    }
                    x = this.minBoundaryX;
                } else if (x > this.maxBoundaryX) {
                    if (this.options.shrink === 'scale') {
                        this.width = Math.max(this.indicatorWidth - (x - this.maxPosX), 8);
                        this.indicatorStyle.width = this.width + 'px';
                        x = this.maxPosX + this.indicatorWidth - this.width;
                    } else {
                        x = this.maxBoundaryX;
                    }
                } else if (this.options.shrink === 'scale' && this.width !== this.indicatorWidth) {
                    this.width = this.indicatorWidth;
                    this.indicatorStyle.width = this.width + 'px';
                }

                if (y < this.minBoundaryY) {
                    if (this.options.shrink === 'scale') {
                        this.height = Math.max(this.indicatorHeight + y * 3, 8);
                        this.indicatorStyle.height = this.height + 'px';
                    }
                    y = this.minBoundaryY;
                } else if (y > this.maxBoundaryY) {
                    if (this.options.shrink === 'scale') {
                        this.height = Math.max(this.indicatorHeight - (y - this.maxPosY) * 3, 8);
                        this.indicatorStyle.height = this.height + 'px';
                        y = this.maxPosY + this.indicatorHeight - this.height;
                    } else {
                        y = this.maxBoundaryY;
                    }
                } else if (this.options.shrink === 'scale' && this.height !== this.indicatorHeight) {
                    this.height = this.indicatorHeight;
                    this.indicatorStyle.height = this.height + 'px';
                }
            }

            this.x = x;
            this.y = y;

            if (this.scroller.options.useTransform) {
                this.indicatorStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.scroller.translateZ;
            } else {
                this.indicatorStyle.left = x + 'px';
                this.indicatorStyle.top = y + 'px';
            }
        },

        _pos: function(x, y) {
            if (x < 0) {
                x = 0;
            } else if (x > this.maxPosX) {
                x = this.maxPosX;
            }

            if (y < 0) {
                y = 0;
            } else if (y > this.maxPosY) {
                y = this.maxPosY;
            }

            x = this.options.listenX ? Math.round(x / this.sizeRatioX) : this.scroller.x;
            y = this.options.listenY ? Math.round(y / this.sizeRatioY) : this.scroller.y;

            this.scroller.scrollTo(x, y);
        },

        fade: function(val, hold) {
            if (hold && !this.visible) {
                return;
            }

            clearTimeout(this.fadeTimeout);
            this.fadeTimeout = null;

            var time = val ? 250 : 500,
                delay = val ? 0 : 300;

            val = val ? '1' : '0';

            this.wrapperStyle[utils.style.transitionDuration] = time + 'ms';

            this.fadeTimeout = setTimeout((function(val) {
                this.wrapperStyle.opacity = val;
                this.visible = +val;
            }).bind(this, val), delay);
        }
    };

    IScroll.utils = utils;

    window.IScroll = IScroll;
}(window);

/* ===============================================================================
************   scroller   ************
=============================================================================== */
/* global Zepto:true */
+ function($) {
    "use strict";
    //重置zepto自带的滚动条
    var _zeptoMethodCache = {
        "scrollTop": $.fn.scrollTop,
        "scrollLeft": $.fn.scrollLeft
    };
    //重置scrollLeft和scrollRight
    (function() {
        $.extend($.fn, {
            scrollTop: function(top, dur) {
                if (!this.length) return;
                var scroller = this.data('scroller');
                if (scroller && scroller.scroller) { //js滚动
                    return scroller.scrollTop(top, dur);
                } else {
                    return _zeptoMethodCache.scrollTop.apply(this, arguments);
                }
            }
        });
        $.extend($.fn, {
            scrollLeft: function(left, dur) {
                if (!this.length) return;
                var scroller = this.data('scroller');
                if (scroller && scroller.scroller) { //js滚动
                    return scroller.scrollLeft(left, dur);
                } else {
                    return _zeptoMethodCache.scrollLeft.apply(this, arguments);
                }
            }
        });
    })();



    //自定义的滚动条
    var Scroller = function(pageContent, _options) {
        var $pageContent = this.$pageContent = $(pageContent);

        this.options = $.extend({}, this._defaults, _options);

        var type = this.options.type;
        //auto的type,系统版本的小于4.4.0的安卓设备和系统版本小于6.0.0的ios设备，启用js版的iscoll
        var useJSScroller = (type === 'js') || (type === 'auto' && ($.device.android && $.compareVersion('4.4.0', $.device.osVersion) > -1) || (type === 'auto' && ($.device.ios && $.compareVersion('6.0.0', $.device.osVersion) > -1)));

        if (useJSScroller) {

            var $pageContentInner = $pageContent.find('.content-inner');
            //如果滚动内容没有被包裹，自动添加wrap
            if (!$pageContentInner[0]) {
                // $pageContent.html('<div class="content-inner">' + $pageContent.html() + '</div>');
                var children = $pageContent.children();
                if (children.length < 1) {
                    $pageContent.children().wrapAll('<div class="content-inner"></div>');
                } else {
                    $pageContent.html('<div class="content-inner">' + $pageContent.html() + '</div>');
                }
            }

            if ($pageContent.hasClass('pull-to-refresh-content')) {
                //因为iscroll 当页面高度不足 100% 时无法滑动，所以无法触发下拉动作，这里改动一下高度
                //区分是否有.bar容器，如有，则content的top:0，无则content的top:-2.2rem,这里取2.2rem的最大值，近60
                var minHeight = $(window).height() + ($pageContent.prev().hasClass(".bar") ? 1 : 61);
                $pageContent.find('.content-inner').css('min-height', minHeight + 'px'); 
            }

            var ptr = $(pageContent).hasClass('pull-to-refresh-content');
            //js滚动模式，用transform移动内容区位置，会导致fixed失效，表现类似absolute。因此禁用transform模式
            var useTransform = $pageContent.find('.fixed-tab').length === 0;
            var options = {
                probeType: 1,
                mouseWheel: true,
                //解决安卓js模式下，刷新滚动条后绑定的事件不响应，对chrome内核浏览器设置click:true
                click: $.device.androidChrome,
                useTransform: useTransform,
                //js模式下允许滚动条横向滚动，但是需要注意，滚动容易宽度必须大于屏幕宽度滚动才生效
                scrollX: true
            };
            if (ptr) {
                options.ptr = true;
                options.ptrOffset = 44;
            }
            //如果用js滚动条，用transform计算内容区位置，position：fixed将实效。若有.fixed-tab，强制使用native滚动条；备选方案，略粗暴
            // if($(pageContent).find('.fixed-tab').length>0){
            //     $pageContent.addClass('native-scroll');
            //     return;
            // }
            this.scroller = new IScroll(pageContent, options); // jshint ignore:line
            //和native滚动统一起来
            this._bindEventToDomWhenJs();
            $.initPullToRefresh = $._pullToRefreshJSScroll.initPullToRefresh;
            $.pullToRefreshDone = $._pullToRefreshJSScroll.pullToRefreshDone;
            $.pullToRefreshTrigger = $._pullToRefreshJSScroll.pullToRefreshTrigger;
            $.destroyToRefresh = $._pullToRefreshJSScroll.destroyToRefresh;
            $pageContent.addClass('javascript-scroll');
            if (!useTransform) {
                $pageContent.find('.content-inner').css({
                    width: '100%',
                    position: 'absolute'
                });
            }

            //如果页面本身已经进行了原生滚动，那么把这个滚动换成JS的滚动
            var nativeScrollTop = this.$pageContent[0].scrollTop;
            if(nativeScrollTop) {
                this.$pageContent[0].scrollTop = 0;
                this.scrollTop(nativeScrollTop);
            }
        } else {
            $pageContent.addClass('native-scroll');
        }
    };
    Scroller.prototype = {
        _defaults: {
            type: 'native',
        },
        _bindEventToDomWhenJs: function() {
            //"scrollStart", //the scroll started.
            //"scroll", //the content is scrolling. Available only in scroll-probe.js edition. See onScroll event.
            //"scrollEnd", //content stopped scrolling.
            if (this.scroller) {
                var self = this;
                this.scroller.on('scrollStart', function() {
                    self.$pageContent.trigger('scrollstart');
                });
                this.scroller.on('scroll', function() {
                    self.$pageContent.trigger('scroll');
                });
                this.scroller.on('scrollEnd', function() {
                    self.$pageContent.trigger('scrollend');
                });
            } else {
                //TODO: 实现native的scrollStart和scrollEnd
            }
        },
        scrollTop: function(top, dur) {
            if (this.scroller) {
                if (top !== undefined) {
                    this.scroller.scrollTo(0, -1 * top, dur);
                } else {
                    return this.scroller.getComputedPosition().y * -1;
                }
            } else {
                return this.$pageContent.scrollTop(top, dur);
            }
            return this;
        },
        scrollLeft: function(left, dur) {
            if (this.scroller) {
                if (left !== undefined) {
                    this.scroller.scrollTo(-1 * left, 0);
                } else {
                    return this.scroller.getComputedPosition().x * -1;
                }
            } else {
                return this.$pageContent.scrollTop(left, dur);
            }
            return this;
        },
        on: function(event, callback) {
            if (this.scroller) {
                this.scroller.on(event, function() {
                    callback.call(this.wrapper);
                });
            } else {
                this.$pageContent.on(event, callback);
            }
            return this;
        },
        off: function(event, callback) {
            if (this.scroller) {
                this.scroller.off(event, callback);
            } else {
                this.$pageContent.off(event, callback);
            }
            return this;
        },
        refresh: function() {
            if (this.scroller) this.scroller.refresh();
            return this;
        },
        scrollHeight: function() {
            if (this.scroller) {
                return this.scroller.scrollerHeight;
            } else {
                return this.$pageContent[0].scrollHeight;
            }
        }

    };

    //Scroller PLUGIN DEFINITION
    // =======================

    function Plugin(option) {
        var args = Array.apply(null, arguments);
        args.shift();
        var internal_return;

        this.each(function() {

            var $this = $(this);

            var options = $.extend({}, $this.dataset(), typeof option === 'object' && option);

            var data = $this.data('scroller');
            //如果 scroller 没有被初始化，对scroller 进行初始化r
            if (!data) {
                //获取data-api的
                $this.data('scroller', (data = new Scroller(this, options)));

            }
            if (typeof option === 'string' && typeof data[option] === 'function') {
                internal_return = data[option].apply(data, args);
                if (internal_return !== undefined)
            return false;
            }

        });

        if (internal_return !== undefined)
            return internal_return;
        else
            return this;

    }

    var old = $.fn.scroller;

    $.fn.scroller = Plugin;
    $.fn.scroller.Constructor = Scroller;


    // Scroll NO CONFLICT
    // =================

    $.fn.scroller.noConflict = function() {
        $.fn.scroller = old;
        return this;
    };
    //添加data-api
    $(function() {
        $('[data-toggle="scroller"]').scroller();
    });

    //统一的接口,带有 .javascript-scroll 的content 进行刷新
    $.refreshScroller = function(content) {
        if (content) {
            $(content).scroller('refresh');
        } else {
            $('.javascript-scroll').each(function() {
                $(this).scroller('refresh');
            });
        }

    };
    //全局初始化方法，会对页面上的 [data-toggle="scroller"]，.content. 进行滚动条初始化
    $.initScroller = function(option) {
        this.options = $.extend({}, typeof option === 'object' && option);
        $('[data-toggle="scroller"],.content').scroller(option);
    };
    //获取scroller对象
    $.getScroller = function(content) {
        //以前默认只能有一个无限滚动，因此infinitescroll都是加在content上，现在允许里面有多个，因此要判断父元素是否有content
        content = content.hasClass('content') ? content : content.parents('.content');
        if (content) {
            return $(content).data('scroller');
        } else {
            return $('.content.javascript-scroll').data('scroller');
        }
    };
    //检测滚动类型,
    //‘js’: javascript 滚动条
    //‘native’: 原生滚动条
    $.detectScrollerType = function(content) {
        if (content) {
            if ($(content).data('scroller') && $(content).data('scroller').scroller) {
                return 'js';
            } else {
                return 'native';
            }
        }
    };

}(Zepto);

/* ===============================================================================
************   Tabs   ************
=============================================================================== */
/* global Zepto:true */
+function ($) {
    "use strict";

    var showTab = function (tab, tabLink, force) {
        var newTab = $(tab);
        if (arguments.length === 2) {
            if (typeof tabLink === 'boolean') {
                force = tabLink;
            }
        }
        if (newTab.length === 0) return false;
        if (newTab.hasClass('active')) {
            if (force) newTab.trigger('show');
            return false;
        }
        var tabs = newTab.parent('.tabs');
        if (tabs.length === 0) return false;

        // Animated tabs
        /*var isAnimatedTabs = tabs.parent().hasClass('tabs-animated-wrap');
          if (isAnimatedTabs) {
          tabs.transform('translate3d(' + -newTab.index() * 100 + '%,0,0)');
          }*/

        // Remove active class from old tabs
        var oldTab = tabs.children('.tab.active').removeClass('active');
        // Add active class to new tab
        newTab.addClass('active');
        // Trigger 'show' event on new tab
        newTab.trigger('show');

        // Update navbars in new tab
        /*if (!isAnimatedTabs && newTab.find('.navbar').length > 0) {
        // Find tab's view
        var viewContainer;
        if (newTab.hasClass(app.params.viewClass)) viewContainer = newTab[0];
        else viewContainer = newTab.parents('.' + app.params.viewClass)[0];
        app.sizeNavbars(viewContainer);
        }*/

        // Find related link for new tab
        if (tabLink) tabLink = $(tabLink);
        else {
            // Search by id
            if (typeof tab === 'string') tabLink = $('.tab-link[href="' + tab + '"]');
            else tabLink = $('.tab-link[href="#' + newTab.attr('id') + '"]');
            // Search by data-tab
            if (!tabLink || tabLink && tabLink.length === 0) {
                $('[data-tab]').each(function () {
                    if (newTab.is($(this).attr('data-tab'))) tabLink = $(this);
                });
            }
        }
        if (tabLink.length === 0) return;

        // Find related link for old tab
        var oldTabLink;
        if (oldTab && oldTab.length > 0) {
            // Search by id
            var oldTabId = oldTab.attr('id');
            if (oldTabId) oldTabLink = $('.tab-link[href="#' + oldTabId + '"]');
            // Search by data-tab
            if (!oldTabLink || oldTabLink && oldTabLink.length === 0) {
                $('[data-tab]').each(function () {
                    if (oldTab.is($(this).attr('data-tab'))) oldTabLink = $(this);
                });
            }
        }

        // Update links' classes
        if (tabLink && tabLink.length > 0) tabLink.addClass('active');
        if (oldTabLink && oldTabLink.length > 0) oldTabLink.removeClass('active');
        tabLink.trigger('active');

        //app.refreshScroller();

        return true;
    };

    var old = $.showTab;
    $.showTab = showTab;

    $.showTab.noConflict = function () {
        $.showTab = old;
        return this;
    };
    //a标签上的click事件，在iscroll下响应有问题
    $(document).on("click", ".tab-link", function(e) {
        e.preventDefault();
        var clicked = $(this);
        showTab(clicked.data("tab") || clicked.attr('href'), clicked);
    });


}(Zepto);

/* ===============================================================================
************   Tabs   ************
=============================================================================== */
/* global Zepto:true */
+function ($) {
    "use strict";
    $.initFixedTab = function(){
        var $fixedTab = $('.fixed-tab');
        if ($fixedTab.length === 0) return;
        $('.fixed-tab').fixedTab();//默认{offset: 0}
    };
    var FixedTab = function(pageContent, _options) {
        var $pageContent = this.$pageContent = $(pageContent);
        var shadow = $pageContent.clone();
        var fixedTop = $pageContent[0].getBoundingClientRect().top;

        shadow.css('visibility', 'hidden');
        this.options = $.extend({}, this._defaults, {
            fixedTop: fixedTop,
            shadow: shadow,
            offset: 0
        }, _options);

        this._bindEvents();
    };

    FixedTab.prototype = {
        _defaults: {
            offset: 0,
        },
        _bindEvents: function() {
            this.$pageContent.parents('.content').on('scroll', this._scrollHandler.bind(this));
            this.$pageContent.on('active', '.tab-link', this._tabLinkHandler.bind(this));
        },
        _tabLinkHandler: function(ev) {
            var isFixed = $(ev.target).parents('.buttons-fixed').length > 0;
            var fixedTop = this.options.fixedTop;
            var offset = this.options.offset;
            $.refreshScroller();
            if (!isFixed) return;
            this.$pageContent.parents('.content').scrollTop(fixedTop - offset);
        },
        // 滚动核心代码
        _scrollHandler: function(ev) {
            var $scroller = $(ev.target);
            var $pageContent = this.$pageContent;
            var shadow = this.options.shadow;
            var offset = this.options.offset;
            var fixedTop = this.options.fixedTop;
            var scrollTop = $scroller.scrollTop();
            var isFixed = scrollTop >= fixedTop - offset;
            if (isFixed) {
                shadow.insertAfter($pageContent);
                $pageContent.addClass('buttons-fixed').css('top', offset);
            } else {
                shadow.remove();
                $pageContent.removeClass('buttons-fixed').css('top', 0);
            }
        }
    };

    //FixedTab PLUGIN DEFINITION
    // =======================

    function Plugin(option) {
        var args = Array.apply(null, arguments);
        args.shift();
        this.each(function() {
            var $this = $(this);
            var options = $.extend({}, $this.dataset(), typeof option === 'object' && option);
            var data = $this.data('fixedtab');
            if (!data) {
                //获取data-api的
                $this.data('fixedtab', (data = new FixedTab(this, options)));
            } 
        });
        
    }
    $.fn.fixedTab = Plugin;
    $.fn.fixedTab.Constructor = FixedTab;
    $(document).on('pageInit',function(){
        $.initFixedTab();
    });
   
   

}(Zepto);

+ function($) {
    "use strict";
    //这里实在js滚动时使用的下拉刷新代码。

    var refreshTime = 0;
    var initPullToRefreshJS = function(pageContainer) {
        var eventsTarget = $(pageContainer);
        if (!eventsTarget.hasClass('pull-to-refresh-content')) {
            eventsTarget = eventsTarget.find('.pull-to-refresh-content');
        }
        if (!eventsTarget || eventsTarget.length === 0) return;

        var page = eventsTarget.hasClass('content') ? eventsTarget : eventsTarget.parents('.content');
        var scroller = $.getScroller(page[0]);
        if(!scroller) return;


        var container = eventsTarget;

        function handleScroll() {
            if (container.hasClass('refreshing')) return;
            if (scroller.scrollTop() * -1 >= 44) {
                container.removeClass('pull-down').addClass('pull-up');
            } else {
                container.removeClass('pull-up').addClass('pull-down');
            }
        }

        function handleRefresh() {
            if (container.hasClass('refreshing')) return;
            container.removeClass('pull-down pull-up');
            container.addClass('refreshing transitioning');
            container.trigger('refresh');
            refreshTime = +new Date();
        }
        scroller.on('scroll', handleScroll);
        scroller.scroller.on('ptr', handleRefresh);

        // Detach Events on page remove
        function destroyPullToRefresh() {
            scroller.off('scroll', handleScroll);
            scroller.scroller.off('ptr', handleRefresh);
        }
        eventsTarget[0].destroyPullToRefresh = destroyPullToRefresh;

    };

    var pullToRefreshDoneJS = function(container) {
        container = $(container);
        if (container.length === 0) container = $('.pull-to-refresh-content.refreshing');
        if (container.length === 0) return;
        var interval = (+new Date()) - refreshTime;
        var timeOut = interval > 1000 ? 0 : 1000 - interval; //long than bounce time
        var scroller = $.getScroller(container);
        setTimeout(function() {
            scroller.refresh();
            container.removeClass('refreshing');
            container.transitionEnd(function() {
                container.removeClass("transitioning");
            });
        }, timeOut);
    };
    var pullToRefreshTriggerJS = function(container) {
        container = $(container);
        if (container.length === 0) container = $('.pull-to-refresh-content');
        if (container.hasClass('refreshing')) return;
        container.addClass('refreshing');
        var scroller = $.getScroller(container);
        scroller.scrollTop(44 + 1, 200);
        container.trigger('refresh');
    };

    var destroyPullToRefreshJS = function(pageContainer) {
        pageContainer = $(pageContainer);
        var pullToRefreshContent = pageContainer.hasClass('pull-to-refresh-content') ? pageContainer : pageContainer.find('.pull-to-refresh-content');
        if (pullToRefreshContent.length === 0) return;
        if (pullToRefreshContent[0].destroyPullToRefresh) pullToRefreshContent[0].destroyPullToRefresh();
    };

    $._pullToRefreshJSScroll = {
        "initPullToRefresh": initPullToRefreshJS,
        "pullToRefreshDone": pullToRefreshDoneJS,
        "pullToRefreshTrigger": pullToRefreshTriggerJS,
        "destroyPullToRefresh": destroyPullToRefreshJS,
    };
}(Zepto); // jshint ignore:line

+ function($) {
    'use strict';
    $.initPullToRefresh = function(pageContainer) {
        var eventsTarget = $(pageContainer);
        if (!eventsTarget.hasClass('pull-to-refresh-content')) {
            eventsTarget = eventsTarget.find('.pull-to-refresh-content');
        }
        if (!eventsTarget || eventsTarget.length === 0) return;

        var isTouched, isMoved, touchesStart = {},
            isScrolling, touchesDiff, touchStartTime, container, refresh = false,
            useTranslate = false,
            startTranslate = 0,
            translate, scrollTop, wasScrolled, triggerDistance, dynamicTriggerDistance;

        container = eventsTarget;

        // Define trigger distance
        if (container.attr('data-ptr-distance')) {
            dynamicTriggerDistance = true;
        } else {
            triggerDistance = 44;
        }

        function handleTouchStart(e) {
            if (isTouched) {
                if ($.device.android) {
                    if ('targetTouches' in e && e.targetTouches.length > 1) return;
                } else return;
            }
            isMoved = false;
            isTouched = true;
            isScrolling = undefined;
            wasScrolled = undefined;
            touchesStart.x = e.type === 'touchstart' ? e.targetTouches[0].pageX : e.pageX;
            touchesStart.y = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
            touchStartTime = (new Date()).getTime();
            /*jshint validthis:true */
            container = $(this);
        }

        function handleTouchMove(e) {
            if (!isTouched) return;
            var pageX = e.type === 'touchmove' ? e.targetTouches[0].pageX : e.pageX;
            var pageY = e.type === 'touchmove' ? e.targetTouches[0].pageY : e.pageY;
            if (typeof isScrolling === 'undefined') {
                isScrolling = !!(isScrolling || Math.abs(pageY - touchesStart.y) > Math.abs(pageX - touchesStart.x));
            }
            if (!isScrolling) {
                isTouched = false;
                return;
            }

            scrollTop = container[0].scrollTop;
            if (typeof wasScrolled === 'undefined' && scrollTop !== 0) wasScrolled = true;

            if (!isMoved) {
                /*jshint validthis:true */
                container.removeClass('transitioning');
                if (scrollTop > container[0].offsetHeight) {
                    isTouched = false;
                    return;
                }
                if (dynamicTriggerDistance) {
                    triggerDistance = container.attr('data-ptr-distance');
                    if (triggerDistance.indexOf('%') >= 0) triggerDistance = container[0].offsetHeight * parseInt(triggerDistance, 10) / 100;
                }
                startTranslate = container.hasClass('refreshing') ? triggerDistance : 0;
                if (container[0].scrollHeight === container[0].offsetHeight || !$.device.ios) {
                    useTranslate = true;
                } else {
                    useTranslate = false;
                }
                useTranslate = true;
            }
            isMoved = true;
            touchesDiff = pageY - touchesStart.y;

            if (touchesDiff > 0 && scrollTop <= 0 || scrollTop < 0) {
                // iOS 8 fix
                if ($.device.ios && parseInt($.device.osVersion.split('.')[0], 10) > 7 && scrollTop === 0 && !wasScrolled) useTranslate = true;

                if (useTranslate) {
                    e.preventDefault();
                    translate = (Math.pow(touchesDiff, 0.85) + startTranslate);
                    container.transform('translate3d(0,' + translate + 'px,0)');
                } else {}
                if ((useTranslate && Math.pow(touchesDiff, 0.85) > triggerDistance) || (!useTranslate && touchesDiff >= triggerDistance * 2)) {
                    refresh = true;
                    container.addClass('pull-up').removeClass('pull-down');
                } else {
                    refresh = false;
                    container.removeClass('pull-up').addClass('pull-down');
                }
            } else {

                container.removeClass('pull-up pull-down');
                refresh = false;
                return;
            }
        }

        function handleTouchEnd() {
            if (!isTouched || !isMoved) {
                isTouched = false;
                isMoved = false;
                return;
            }
            if (translate) {
                container.addClass('transitioning');
                translate = 0;
            }
            container.transform('');
            if (refresh) {
                //防止二次触发
                if(container.hasClass('refreshing')) return;
                container.addClass('refreshing');
                container.trigger('refresh');
            } else {
                container.removeClass('pull-down');
            }
            isTouched = false;
            isMoved = false;
        }

        // Attach Events
        eventsTarget.on($.touchEvents.start, handleTouchStart);
        eventsTarget.on($.touchEvents.move, handleTouchMove);
        eventsTarget.on($.touchEvents.end, handleTouchEnd);


        function destroyPullToRefresh() {
            eventsTarget.off($.touchEvents.start, handleTouchStart);
            eventsTarget.off($.touchEvents.move, handleTouchMove);
            eventsTarget.off($.touchEvents.end, handleTouchEnd);
        }
        eventsTarget[0].destroyPullToRefresh = destroyPullToRefresh;

    };
    $.pullToRefreshDone = function(container) {
        $(window).scrollTop(0);//解决微信下拉刷新顶部消失的问题
        container = $(container);
        if (container.length === 0) container = $('.pull-to-refresh-content.refreshing');
        container.removeClass('refreshing').addClass('transitioning');
        container.transitionEnd(function() {
            container.removeClass('transitioning pull-up pull-down');
        });
    };
    $.pullToRefreshTrigger = function(container) {
        container = $(container);
        if (container.length === 0) container = $('.pull-to-refresh-content');
        if (container.hasClass('refreshing')) return;
        container.addClass('transitioning refreshing');
        container.trigger('refresh');
    };

    $.destroyPullToRefresh = function(pageContainer) {
        pageContainer = $(pageContainer);
        var pullToRefreshContent = pageContainer.hasClass('pull-to-refresh-content') ? pageContainer : pageContainer.find('.pull-to-refresh-content');
        if (pullToRefreshContent.length === 0) return;
        if (pullToRefreshContent[0].destroyPullToRefresh) pullToRefreshContent[0].destroyPullToRefresh();
    };

    //这里是否需要写到 scroller 中去？
/*    $.initPullToRefresh = function(pageContainer) {
        var $pageContainer = $(pageContainer);
        $pageContainer.each(function(index, item) {
            if ($.detectScrollerType(item) === 'js') {
                $._pullToRefreshJSScroll.initPullToRefresh(item);
            } else {
                initPullToRefresh(item);
            }
        });
    };


    $.pullToRefreshDone = function(pageContainer) {
        var $pageContainer = $(pageContainer);
        $pageContainer.each(function(index, item) {
            if ($.detectScrollerType(item) === 'js') {
                $._pullToRefreshJSScroll.pullToRefreshDone(item);
            } else {
                pullToRefreshDone(item);
            }
        });
    };


    $.pullToRefreshTrigger = function(pageContainer) {
       var $pageContainer = $(pageContainer);
        $pageContainer.each(function(index, item) {
            if ($.detectScrollerType(item) === 'js') {
                $._pullToRefreshJSScroll.pullToRefreshTrigger(item);
            } else {
                pullToRefreshTrigger(item);
            }
        });
    };

    $.destroyPullToRefresh = function(pageContainer) {
        var $pageContainer = $(pageContainer);
        $pageContainer.each(function(index, item) {
            if ($.detectScrollerType(item) === 'js') {
                $._pullToRefreshJSScroll.destroyPullToRefresh(item);
            } else {
                destroyPullToRefresh(item);
            }
        });
    };
*/

}(Zepto); //jshint ignore:line

+ function($) {
    'use strict';
    /* global Zepto:true */

    function handleInfiniteScroll() {
        /*jshint validthis:true */
        var inf = $(this);
        var scroller = $.getScroller(inf);
        var scrollTop = scroller.scrollTop();
        var scrollHeight = scroller.scrollHeight();
        var height = inf[0].offsetHeight;
        var distance = inf[0].getAttribute('data-distance');
        var virtualListContainer = inf.find('.virtual-list');
        var virtualList;
        var onTop = inf.hasClass('infinite-scroll-top');
        if (!distance) distance = 50;
        if (typeof distance === 'string' && distance.indexOf('%') >= 0) {
            distance = parseInt(distance, 10) / 100 * height;
        }
        if (distance > height) distance = height;
        if (onTop) {
            if (scrollTop < distance) {
                inf.trigger('infinite');
            }
        } else {
            if (scrollTop + height >= scrollHeight - distance) {
                if (virtualListContainer.length > 0) {
                    virtualList = virtualListContainer[0].f7VirtualList;
                    if (virtualList && !virtualList.reachEnd) return;
                }
                inf.trigger('infinite');
            }
        }

    }
    $.attachInfiniteScroll = function(infiniteContent) {
        $.getScroller(infiniteContent).on('scroll', handleInfiniteScroll);
    };
    $.detachInfiniteScroll = function(infiniteContent) {
        $.getScroller(infiniteContent).off('scroll', handleInfiniteScroll);
    };

    $.initInfiniteScroll = function(pageContainer) {
        pageContainer = $(pageContainer);
        var infiniteContent = pageContainer.hasClass('infinite-scroll')?pageContainer:pageContainer.find('.infinite-scroll');
        if (infiniteContent.length === 0) return;
        $.attachInfiniteScroll(infiniteContent);
        //如果是顶部无限刷新，要将滚动条初始化于最下端
        pageContainer.forEach(function(v){
            if($(v).hasClass('infinite-scroll-top')){
                var height = v.scrollHeight - v.clientHeight;
                $(v).scrollTop(height);     
            }
        });
        function detachEvents() {
            $.detachInfiniteScroll(infiniteContent);
            pageContainer.off('pageBeforeRemove', detachEvents);
        }
        pageContainer.on('pageBeforeRemove', detachEvents);
    };
}(Zepto);

/* global Zepto:true */
+function ($) {
    "use strict";
    $(function() {
        $(document).on("focus", ".searchbar input", function(e) {
            var $input = $(e.target);
            $input.parents(".searchbar").addClass("searchbar-active");
        });
        $(document).on("click", ".searchbar-cancel", function(e) {
            var $btn = $(e.target);
            $btn.parents(".searchbar").removeClass("searchbar-active");
        });
        $(document).on("blur", ".searchbar input", function(e) {
            var $input = $(e.target);
            $input.parents(".searchbar").removeClass("searchbar-active");
        });
    });
}(Zepto);

/*======================================================
************   Panels   ************
======================================================*/
/* global Zepto:true */
/*jshint unused: false*/
+function ($) {
    "use strict";
    $.allowPanelOpen = true;
    $.openPanel = function (panel) {
        if (!$.allowPanelOpen) return false;
        if(panel === 'left' || panel === 'right') panel = ".panel-" + panel;  //可以传入一个方向
        panel = panel ? $(panel) : $(".panel").eq(0);
        var direction = panel.hasClass("panel-right") ? "right" : "left";
        if (panel.length === 0 || panel.hasClass('active')) return false;
        $.closePanel(); // Close if some panel is opened
        $.allowPanelOpen = false;
        var effect = panel.hasClass('panel-reveal') ? 'reveal' : 'cover';
        panel.css({display: 'block'}).addClass('active');
        panel.trigger('open');

        // Trigger reLayout
        var clientLeft = panel[0].clientLeft;

        // Transition End;
        var transitionEndTarget = effect === 'reveal' ? $($.getCurrentPage()) : panel;
        var openedTriggered = false;

        function panelTransitionEnd() {
            transitionEndTarget.transitionEnd(function (e) {
                if (e.target === transitionEndTarget[0]) {
                    if (panel.hasClass('active')) {
                        panel.trigger('opened');
                    }
                    else {
                        panel.trigger('closed');
                    }
            $.allowPanelOpen = true;
                }
                else panelTransitionEnd();
            });
        }
        panelTransitionEnd();

        $(document.body).addClass('with-panel-' + direction + '-' + effect);
        return true;
    };
    $.closePanel = function () {
        var activePanel = $('.panel.active');
        if (activePanel.length === 0) return false;
        var effect = activePanel.hasClass('panel-reveal') ? 'reveal' : 'cover';
        var panelPosition = activePanel.hasClass('panel-left') ? 'left' : 'right';
        activePanel.removeClass('active');
        var transitionEndTarget = effect === 'reveal' ? $('.page') : activePanel;
        activePanel.trigger('close');
        $.allowPanelOpen = false;

        transitionEndTarget.transitionEnd(function () {
            if (activePanel.hasClass('active')) return;
            activePanel.css({display: ''});
            activePanel.trigger('closed');
            $('body').removeClass('panel-closing');
            $.allowPanelOpen = true;
        });

        $('body').addClass('panel-closing').removeClass('with-panel-' + panelPosition + '-' + effect);
    };

    $(document).on("click", ".open-panel", function(e) {
        var panel = $(e.target).data('panel');
        $.openPanel(panel);
    });
    $(document).on("click", ".close-panel, .panel-overlay", function(e) {
        $.closePanel();
    });
    /*======================================================
     ************   Swipe panels   ************
     ======================================================*/
    $.initSwipePanels = function () {
        var panel, side;
        var swipePanel = $.smConfig.swipePanel;
        var swipePanelOnlyClose = $.smConfig.swipePanelOnlyClose;
        var swipePanelCloseOpposite = true;
        var swipePanelActiveArea = false;
        var swipePanelThreshold = 2;
        var swipePanelNoFollow = false;

        if(!(swipePanel || swipePanelOnlyClose)) return;

        var panelOverlay = $('.panel-overlay');
        var isTouched, isMoved, isScrolling, touchesStart = {}, touchStartTime, touchesDiff, translate, opened, panelWidth, effect, direction;
        var views = $('.page');

        function handleTouchStart(e) {
            if (!$.allowPanelOpen || (!swipePanel && !swipePanelOnlyClose) || isTouched) return;
            if ($('.modal-in, .photo-browser-in').length > 0) return;
            if (!(swipePanelCloseOpposite || swipePanelOnlyClose)) {
                if ($('.panel.active').length > 0 && !panel.hasClass('active')) return;
            }
            touchesStart.x = e.type === 'touchstart' ? e.targetTouches[0].pageX : e.pageX;
            touchesStart.y = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
            if (swipePanelCloseOpposite || swipePanelOnlyClose) {
                if ($('.panel.active').length > 0) {
                    side = $('.panel.active').hasClass('panel-left') ? 'left' : 'right';
                }
                else {
                    if (swipePanelOnlyClose) return;
                    side = swipePanel;
                }
                if (!side) return;
            }
            panel = $('.panel.panel-' + side);
            if(!panel[0]) return;
            opened = panel.hasClass('active');
            if (swipePanelActiveArea && !opened) {
                if (side === 'left') {
                    if (touchesStart.x > swipePanelActiveArea) return;
                }
                if (side === 'right') {
                    if (touchesStart.x < window.innerWidth - swipePanelActiveArea) return;
                }
            }
            isMoved = false;
            isTouched = true;
            isScrolling = undefined;

            touchStartTime = (new Date()).getTime();
            direction = undefined;
        }
        function handleTouchMove(e) {
            if (!isTouched) return;
            if(!panel[0]) return;
            if (e.f7PreventPanelSwipe) return;
            var pageX = e.type === 'touchmove' ? e.targetTouches[0].pageX : e.pageX;
            var pageY = e.type === 'touchmove' ? e.targetTouches[0].pageY : e.pageY;
            if (typeof isScrolling === 'undefined') {
                isScrolling = !!(isScrolling || Math.abs(pageY - touchesStart.y) > Math.abs(pageX - touchesStart.x));
            }
            if (isScrolling) {
                isTouched = false;
                return;
            }
            if (!direction) {
                if (pageX > touchesStart.x) {
                    direction = 'to-right';
                }
                else {
                    direction = 'to-left';
                }

                if (
                        side === 'left' &&
                        (
                         direction === 'to-left' && !panel.hasClass('active')
                        ) ||
                        side === 'right' &&
                        (
                         direction === 'to-right' && !panel.hasClass('active')
                        )
                   )
                {
                    isTouched = false;
                    return;
                }
            }

            if (swipePanelNoFollow) {
                var timeDiff = (new Date()).getTime() - touchStartTime;
                if (timeDiff < 300) {
                    if (direction === 'to-left') {
                        if (side === 'right') $.openPanel(side);
                        if (side === 'left' && panel.hasClass('active')) $.closePanel();
                    }
                    if (direction === 'to-right') {
                        if (side === 'left') $.openPanel(side);
                        if (side === 'right' && panel.hasClass('active')) $.closePanel();
                    }
                }
                isTouched = false;
                console.log(3);
                isMoved = false;
                return;
            }

            if (!isMoved) {
                effect = panel.hasClass('panel-cover') ? 'cover' : 'reveal';
                if (!opened) {
                    panel.show();
                    panelOverlay.show();
                }
                panelWidth = panel[0].offsetWidth;
                panel.transition(0);
                /*
                   if (panel.find('.' + app.params.viewClass).length > 0) {
                   if (app.sizeNavbars) app.sizeNavbars(panel.find('.' + app.params.viewClass)[0]);
                   }
                   */
            }

            isMoved = true;

            e.preventDefault();
            var threshold = opened ? 0 : -swipePanelThreshold;
            if (side === 'right') threshold = -threshold;

            touchesDiff = pageX - touchesStart.x + threshold;

            if (side === 'right') {
                translate = touchesDiff  - (opened ? panelWidth : 0);
                if (translate > 0) translate = 0;
                if (translate < -panelWidth) {
                    translate = -panelWidth;
                }
            }
            else {
                translate = touchesDiff  + (opened ? panelWidth : 0);
                if (translate < 0) translate = 0;
                if (translate > panelWidth) {
                    translate = panelWidth;
                }
            }
            if (effect === 'reveal') {
                views.transform('translate3d(' + translate + 'px,0,0)').transition(0);
                panelOverlay.transform('translate3d(' + translate + 'px,0,0)');
                //app.pluginHook('swipePanelSetTransform', views[0], panel[0], Math.abs(translate / panelWidth));
            }
            else {
                panel.transform('translate3d(' + translate + 'px,0,0)').transition(0);
                //app.pluginHook('swipePanelSetTransform', views[0], panel[0], Math.abs(translate / panelWidth));
            }
        }
        function handleTouchEnd(e) {
            if (!isTouched || !isMoved) {
                isTouched = false;
                isMoved = false;
                return;
            }
            isTouched = false;
            isMoved = false;
            var timeDiff = (new Date()).getTime() - touchStartTime;
            var action;
            var edge = (translate === 0 || Math.abs(translate) === panelWidth);

            if (!opened) {
                if (translate === 0) {
                    action = 'reset';
                }
                else if (
                        timeDiff < 300 && Math.abs(translate) > 0 ||
                        timeDiff >= 300 && (Math.abs(translate) >= panelWidth / 2)
                        ) {
                            action = 'swap';
                        }
                else {
                    action = 'reset';
                }
            }
            else {
                if (translate === -panelWidth) {
                    action = 'reset';
                }
                else if (
                        timeDiff < 300 && Math.abs(translate) >= 0 ||
                        timeDiff >= 300 && (Math.abs(translate) <= panelWidth / 2)
                        ) {
                            if (side === 'left' && translate === panelWidth) action = 'reset';
                            else action = 'swap';
                        }
                else {
                    action = 'reset';
                }
            }
            if (action === 'swap') {
                $.allowPanelOpen = true;
                if (opened) {
                    $.closePanel();
                    if (edge) {
                        panel.css({display: ''});
                        $('body').removeClass('panel-closing');
                    }
                }
                else {
                    $.openPanel(side);
                }
                if (edge) $.allowPanelOpen = true;
            }
            if (action === 'reset') {
                if (opened) {
                    $.allowPanelOpen = true;
                    $.openPanel(side);
                }
                else {
                    $.closePanel();
                    if (edge) {
                        $.allowPanelOpen = true;
                        panel.css({display: ''});
                    }
                    else {
                        var target = effect === 'reveal' ? views : panel;
                        $('body').addClass('panel-closing');
                        target.transitionEnd(function () {
                            $.allowPanelOpen = true;
                            panel.css({display: ''});
                            $('body').removeClass('panel-closing');
                        });
                    }
                }
            }
            if (effect === 'reveal') {
                views.transition('');
                views.transform('');
            }
            panel.transition('').transform('');
            panelOverlay.css({display: ''}).transform('');
        }
        $(document).on($.touchEvents.start, handleTouchStart);
        $(document).on($.touchEvents.move, handleTouchMove);
        $(document).on($.touchEvents.end, handleTouchEnd);
    };

    $.initSwipePanels();
}(Zepto);

// jshint ignore: start
/*
 * 路由器
 */
+function($) {
    "use strict";

    if (!window.CustomEvent) {
        window.CustomEvent = function(type, config) {
            config = config || { bubbles: false, cancelable: false, detail: undefined};
            var e = document.createEvent('CustomEvent');
            e.initCustomEvent(type, config.bubbles, config.cancelable, config.detail);
            return e;
        };

        window.CustomEvent.prototype = window.Event.prototype;
    }

    var Router = function() {
        this.state = sessionStorage;
        this.state.setItem("stateid", parseInt(this.state.getItem("stateid") || 1) + 1);
        this.state.setItem("currentStateID", this.state.getItem("stateid"));
        this.stack = sessionStorage;
        this.stack.setItem("back", "[]");  //返回栈, {url, pageid, stateid}
        this.stack.setItem("forward", "[]");  //前进栈, {url, pageid, stateid}
        this.init();
        this.xhr = null;
        // 解决各个webview针对页面重新加载（包括后退造成的）时History State的处理差异，加此标志位
        this.newLoaded = true;
    };

    Router.prototype.defaults = {};

    Router.prototype.init = function() {
        var currentPage = this.getCurrentPage(),
            page1st = $(".page").eq(0);
        if (!currentPage[0]) currentPage = page1st.addClass("page-current");
        var hash = location.hash;
        if(currentPage[0] && !currentPage[0].id) currentPage[0].id = (hash ? hash.slice(1) : this.genRandomID());

        if (!currentPage[0]) throw new Error("can't find .page element");
        var newCurrentPage = $(hash);

        // 确保是 page 时才切换显示，不然可能只是普通的 hash（#129）
        if (newCurrentPage[0] && newCurrentPage.hasClass('page')
            && (!currentPage[0] || hash.slice(1) !== currentPage[0].id)) {
            currentPage.removeClass("page-current");
            newCurrentPage.addClass("page-current");
            currentPage = newCurrentPage;
        }

        var id = this.genStateID(),
            curUrl = location.href,
            // 需要设置入口页的Url，方便用户在类似xx/yy#step2 的页面刷新加载后 点击后退可以回到入口页
            entryUrl = curUrl.split('#')[0];

        // 在页面加载时，可能会包含一个非空的状态对象history.state。这种情况是会发生的，例如，如果页面中使用pushState()或replaceState()方法设置了一个状态对象，然后用户重启了浏览器。https://developer.mozilla.org/en-US/docs/Web/API/History_API#Reading_the_current_state
        history.replaceState({url: curUrl, id: id}, '', curUrl);
        this.setCurrentStateID(id);
        this.pushBack({
            url: entryUrl,
            pageid: '#' + page1st[0].id,
            id: id
        });
        window.addEventListener('popstate', $.proxy(this.onpopstate, this));
    };

    //加载一个页面,传入的参数是页面id或者url
    Router.prototype.loadPage = function(url) {

        // android chrome 在移动端加载页面时不会触发一次‘popstate’事件
        this.newLoaded && (this.newLoaded = false);
        this.getPage(url, function(page) {

            var pageid = this.getCurrentPage()[0].id;
            this.pushBack({
                url: url,
                pageid: "#" + pageid,
                id: this.getCurrentStateID()
            });

            //删除全部forward
            var forward = JSON.parse(this.state.getItem("forward") || "[]");
            for (var i = 0; i < forward.length; i++) {
                $(forward[i].pageid).each(function() {
                    var $page = $(this);
                    if ($page.data("page-remote")) $page.remove();
                });
            }
            this.state.setItem("forward", "[]");  //clearforward

            page.insertAfter($(".page")[0]);
            this.animatePages(this.getCurrentPage(), page);

            var id = this.genStateID();
            this.setCurrentStateID(id);
			url = url.replace("&hasleftpanel=1","");
			url = url.replace("?hasleftpanel=1","");
            this.pushState(url, id);

            this.forwardStack = [];  //clear forward stack

        });
    };

    /**
     * 页面转场效果
     *
     * 首先给要移入展示的页面添加上当前页面标识（page-current），要移出展示的移除当前页面标识；
     * 然后给移入移除的页面添加上对应的动画 class，动画结束后清除动画 class 并发送对应事件。
     *
     * 注意，不能在动画后才给移入展示的页面添加当前页面标识，否则，在快速切换的时候将会因为没有 .page-current
     * 的页面而报错（具体来说是找这类页面的 id 时报错，目前并没有确保 id 查找的健壮性）
     *
     * @param leftPage 从效果上看位于左侧的页面，jQuery/Zepto 对象
     * @param rightPage 从效果上位于右侧的页面，jQuery/Zepto 对象
     * @param {Boolean} leftToRight 是否是从左往右切换（代表是后退），默认是相当于 false
     */
    Router.prototype.animatePages = function(leftPage, rightPage, leftToRight) {
    	$(".pull-to-refresh-layer .pull-to-refresh-arrow").css({"opacity":0});
        var curPageClass = 'page-current';
        var animPageClasses = [
            'page-from-center-to-left',
            'page-from-center-to-right',
            'page-from-right-to-center',
            'page-from-left-to-center'].join(' ');

        if (!leftToRight) {
            // 新页面从右侧切入
            rightPage.trigger("pageAnimationStart", [rightPage[0].id, rightPage]);
            leftPage.removeClass(animPageClasses).removeClass(curPageClass).addClass('page-from-center-to-left');
            rightPage.removeClass(animPageClasses).addClass(curPageClass).addClass('page-from-right-to-center');
            leftPage.animationEnd(function() {
                leftPage.removeClass(animPageClasses);
                $(".pull-to-refresh-layer .pull-to-refresh-arrow").css({"opacity":1});
            });
            rightPage.animationEnd(function() {
                rightPage.removeClass(animPageClasses);
                rightPage.trigger("pageAnimationEnd", [rightPage[0].id, rightPage]);
                rightPage.trigger("pageInitInternal", [rightPage[0].id, rightPage]);
                 $(".pull-to-refresh-layer .pull-to-refresh-arrow").css({"opacity":1});
            });
        } else {
            leftPage.trigger("pageAnimationStart", [rightPage[0].id, rightPage]);
            leftPage.removeClass(animPageClasses).addClass(curPageClass).addClass('page-from-left-to-center');
            rightPage.removeClass(animPageClasses).removeClass(curPageClass).addClass('page-from-center-to-right');
            leftPage.animationEnd(function() {
                leftPage.removeClass(animPageClasses);
                leftPage.trigger("pageAnimationEnd", [leftPage[0].id, leftPage]);
                leftPage.trigger("pageReinit", [leftPage[0].id, leftPage]);
                 $(".pull-to-refresh-layer .pull-to-refresh-arrow").css({"opacity":1});
            });
            rightPage.animationEnd(function() {
                rightPage.removeClass(animPageClasses);
                $(".pull-to-refresh-layer .pull-to-refresh-arrow").css({"opacity":1});
            });
        }

    };

    Router.prototype.getCurrentPage = function() {
        return $(".page-current");
    };

    // 其实没调用到，如果无法前进，则加载对应的url
    Router.prototype.forward = function(url) {
        var stack = JSON.parse(this.stack.getItem("forward"));
        if (stack.length) {
            history.forward();
        } else {
            location.href = url;
        }
    };

    // 点击 .back 按钮，如果无法后退，则加载对应的url
    Router.prototype.back = function(url) {
        var stack = JSON.parse(this.stack.getItem("back"));
        if (stack.length) {
            history.back();
        } else if (url) {
            location.href = url;
        } else {
            console.warn('[router.back]: can not back')
        }
    };

    // popstate 后退
    Router.prototype._back = function(url) {
        var h = this.popBack();
        var currentPage = this.getCurrentPage();
        var newPage = $(h.pageid);
        if (!newPage[0]) return;
        this.pushForward({url: location.href, pageid: "#" + currentPage[0].id, id: this.getCurrentStateID()});
        this.setCurrentStateID(h.id);
        this.animatePages(newPage, currentPage, true);
    };

    // popstate 前进
    Router.prototype._forward = function() {
        var h = this.popForward();
        var currentPage = this.getCurrentPage();
        var newPage = $(h.pageid);
        if (!newPage[0]) return;
        this.pushBack({url: location.href, pageid: "#" + currentPage[0].id, id: this.getCurrentStateID()});
        this.setCurrentStateID(h.id);
        this.animatePages(currentPage, newPage);
    };

    Router.prototype.pushState = function(url, id) {
        history.pushState({url: url, id: id}, '', url);
    };

    Router.prototype.onpopstate = function(d) {
        var state = d.state;
        // 刷新再后退导致无法取到state
        if (!state || this.newLoaded) {
            this.newLoaded = false;
            return;
        }

        if (state.id === this.getCurrentStateID()) {
            return false;
        }
        var forward = state.id > this.getCurrentStateID();
        if (forward) this._forward();
        else this._back(state.url);
    };

    //根据url获取页面的DOM，如果是一个内联页面，则直接返回，否则用ajax加载
    Router.prototype.getPage = function(url, callback) {
        if (url[0] === "#") return callback.apply(this, [$(url)]);

        this.dispatch("pageLoadStart");

        if (this.xhr && this.xhr.readyState < 4) {
            this.xhr.onreadystatechange = function() {
            };
            this.xhr.abort();
            this.dispatch("pageLoadCancel");
        }

        var self = this;

        this.xhr = $.ajax({
            url: url,
            timeout:10000,
            success: $.proxy(function(data, s, xhr) {
                var $page = this.parseXHR(xhr);
                if (!$page[0].id) $page[0].id = this.genRandomID();
                $page.data("page-remote", 1);
                callback.apply(this, [$page]);
            }, this),
            error: function() {
                self.dispatch("pageLoadError");
            },
            complete: function() {
                self.dispatch("pageLoadComplete");
            }
        });
    };

    Router.prototype.parseXHR = function(xhr) {
        var html = '';
        var response = xhr.responseText;
        var matches = response.match(/<body[^>]*>([\s\S.]*)<\/body>/i);
        if(matches) {
            html = matches[1];
        } else {
            html = response;
        }
        html = "<div>" + html + "</div>";
        var tmp = $(html);

        tmp.find(".popup, .panel, .panel-overlay").appendTo(document.body);

        var $page = tmp.find(".page");
        if (!$page[0]) $page = tmp.addClass("page");
        return $page;
    };

    Router.prototype.genStateID = function() {
        var id = parseInt(this.state.getItem("stateid")) + 1;
        this.state.setItem("stateid", id);
        return id;
    };

    Router.prototype.getCurrentStateID = function() {
        return parseInt(this.state.getItem("currentStateID"));
    };

    Router.prototype.setCurrentStateID = function(id) {
        this.state.setItem("currentStateID", id);
    };

    Router.prototype.genRandomID = function() {
        return "page-" + (+new Date());
    };

    Router.prototype.popBack = function() {
        var stack = JSON.parse(this.stack.getItem("back"));
        if (!stack.length) return null;
        var h = stack.splice(stack.length - 1, 1)[0];
        this.stack.setItem("back", JSON.stringify(stack));
        return h;
    };

    Router.prototype.pushBack = function(h) {
        var stack = JSON.parse(this.stack.getItem("back"));
        stack.push(h);
        this.stack.setItem("back", JSON.stringify(stack));
    };

    Router.prototype.popForward = function() {
        var stack = JSON.parse(this.stack.getItem("forward"));
        if (!stack.length) return null;
        var h = stack.splice(stack.length - 1, 1)[0];
        this.stack.setItem("forward", JSON.stringify(stack));
        return h;
    };

    Router.prototype.pushForward = function(h) {
        var stack = JSON.parse(this.stack.getItem("forward"));
        stack.push(h);
        this.stack.setItem("forward", JSON.stringify(stack));
    };

    Router.prototype.dispatch = function(event) {
        var e = new CustomEvent(event, {
            bubbles: true,
            cancelable: true
        });

        window.dispatchEvent(e);
    };

    $(function() {
        // 用户可选关闭router功能
        if (!$.smConfig.router) return;
        var router = $.router = new Router();
        $(document).on("click", "a", function(e) {
            var $target = $(e.currentTarget);
            if ($target.hasClass("external") ||
                $target[0].hasAttribute("external") ||
                $target.hasClass("tab-link") ||
                $target.hasClass("open-popup") ||
                $target.hasClass("open-panel")
            ) return;
            e.preventDefault();
            var url = $target.attr("href");
            if ($target.hasClass("back")) {
                router.back(url);
                return;
            }

            if (!url || url === "#") return;
            router.loadPage(url);
        })
    });
}(Zepto);
// jshint ignore: end

/* global Zepto:true */
/*jshint unused: false*/
+function ($) {
    "use strict";

    var getPage = function() {
        var $page = $(".page-current");
        if(!$page[0]) $page = $(".page").addClass("page-current");
        return $page;
    };

    //初始化页面中的JS组件
    $.initPage = function(page) {
        var $page = getPage();
        if(!$page[0]) $page = $(document.body);
        var $content = $page.hasClass("content") ? $page : $page.find(".content");
        $content.scroller();  //注意滚动条一定要最先初始化

        $.initPullToRefresh($content);
        $.initInfiniteScroll($content);
        $.initCalendar($content);

        //extend
        if($.initSwiper) $.initSwiper($content);
    };


    if($.smConfig.showPageLoadingIndicator) {
        //这里的 以 push 开头的是私有事件，不要用
        $(window).on("pageLoadStart", function() {
            $.showIndicator();
        });
        $(document).on("pageAnimationStart", function() {
            $.hideIndicator();
        });
        $(window).on("pageLoadCancel", function() {
            $.hideIndicator();
        });
        $(window).on("pageLoadError", function() {
            $.hideIndicator();
            $.toast("加载失败");
        });
    }



    $.init = function() {
        var $page = getPage();
        var id = $page[0].id;
        $.initPage();
        $page.trigger("pageInit", [id, $page]);
    };

    $(function() {
        if($.smConfig.autoInit) {
            $.init();
        }

        $(document).on("pageInitInternal", function(e, id, page) {
            $.init();
        });
    });


}(Zepto);

/**
 * ScrollFix v0.1
 * http://www.joelambert.co.uk
 *
 * Copyright 2011, Joe Lambert.
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
/* ===============================================================================
************   ScrollFix   ************
=============================================================================== */

/* global Zepto:true */
+ function($) {
    "use strict";
    //安卓微信中使用scrollfix会有问题，因此只在ios中使用，安卓机器按照原来的逻辑
   
    if($.device.ios){
        var ScrollFix = function(elem) {

            // Variables to track inputs
            var startY;
            var startTopScroll;

            elem = elem || document.querySelector(elem);

            // If there is no element, then do nothing
            if(!elem)
                return;

            // Handle the start of interactions
            elem.addEventListener('touchstart', function(event){
                startY = event.touches[0].pageY;
                startTopScroll = elem.scrollTop;

                if(startTopScroll <= 0)
                elem.scrollTop = 1;

            if(startTopScroll + elem.offsetHeight >= elem.scrollHeight)
                elem.scrollTop = elem.scrollHeight - elem.offsetHeight - 1;
            }, false);
        };

        var initScrollFix = function(){
            var prefix = $('.page-current').length > 0 ? '.page-current ' : '';
            var scrollable = $(prefix + ".content");
            new ScrollFix(scrollable[0]);
        };
    
        $(document).on($.touchEvents.move, ".page-current .bar",function(){
            event.preventDefault();
        }); 
        //监听ajax页面跳转
        $(document).on("pageLoadComplete", function(){
             initScrollFix(); 
        }); 
        //监听内联页面跳转
        $(document).on("pageAnimationEnd", function(){
             initScrollFix(); 
        });
        initScrollFix();
    }
   
}(Zepto);

/*!
 * =====================================================
 * SUI Mobile - http://m.sui.taobao.org/
 *
 * =====================================================
 */
+function(a){"use strict";var b=function(c,d){function e(){return"horizontal"===o.params.direction}function f(){o.autoplayTimeoutId=setTimeout(function(){o.params.loop?(o.fixLoop(),o._slideNext()):o.isEnd?d.autoplayStopOnLast?o.stopAutoplay():o._slideTo(0):o._slideNext()},o.params.autoplay)}function g(b,c){var d=a(b.target);if(!d.is(c))if("string"==typeof c)d=d.parents(c);else if(c.nodeType){var e;return d.parents().each(function(a,b){b===c&&(e=c)}),e?c:void 0}if(0!==d.length)return d[0]}function h(a,b){b=b||{};var c=window.MutationObserver||window.WebkitMutationObserver,d=new c(function(a){a.forEach(function(a){o.onResize(),o.emit("onObserverUpdate",o,a)})});d.observe(a,{attributes:"undefined"==typeof b.attributes?!0:b.attributes,childList:"undefined"==typeof b.childList?!0:b.childList,characterData:"undefined"==typeof b.characterData?!0:b.characterData}),o.observers.push(d)}function i(b,c){b=a(b);var d,f,g;d=b.attr("data-swiper-parallax")||"0",f=b.attr("data-swiper-parallax-x"),g=b.attr("data-swiper-parallax-y"),f||g?(f=f||"0",g=g||"0"):e()?(f=d,g="0"):(g=d,f="0"),f=f.indexOf("%")>=0?parseInt(f,10)*c+"%":f*c+"px",g=g.indexOf("%")>=0?parseInt(g,10)*c+"%":g*c+"px",b.transform("translate3d("+f+", "+g+",0px)")}function j(a){return 0!==a.indexOf("on")&&(a=a[0]!==a[0].toUpperCase()?"on"+a[0].toUpperCase()+a.substring(1):"on"+a),a}var k=this.defaults,l=d&&d.virtualTranslate;d=d||{};for(var m in k)if("undefined"==typeof d[m])d[m]=k[m];else if("object"==typeof d[m])for(var n in k[m])"undefined"==typeof d[m][n]&&(d[m][n]=k[m][n]);var o=this;if(o.params=d,o.classNames=[],o.$=a,o.container=a(c),0!==o.container.length){if(o.container.length>1)return void o.container.each(function(){new a.Swiper(this,d)});o.container[0].swiper=o,o.container.data("swiper",o),o.classNames.push("swiper-container-"+o.params.direction),o.params.freeMode&&o.classNames.push("swiper-container-free-mode"),o.support.flexbox||(o.classNames.push("swiper-container-no-flexbox"),o.params.slidesPerColumn=1),(o.params.parallax||o.params.watchSlidesVisibility)&&(o.params.watchSlidesProgress=!0),["cube","coverflow"].indexOf(o.params.effect)>=0&&(o.support.transforms3d?(o.params.watchSlidesProgress=!0,o.classNames.push("swiper-container-3d")):o.params.effect="slide"),"slide"!==o.params.effect&&o.classNames.push("swiper-container-"+o.params.effect),"cube"===o.params.effect&&(o.params.resistanceRatio=0,o.params.slidesPerView=1,o.params.slidesPerColumn=1,o.params.slidesPerGroup=1,o.params.centeredSlides=!1,o.params.spaceBetween=0,o.params.virtualTranslate=!0,o.params.setWrapperSize=!1),"fade"===o.params.effect&&(o.params.slidesPerView=1,o.params.slidesPerColumn=1,o.params.slidesPerGroup=1,o.params.watchSlidesProgress=!0,o.params.spaceBetween=0,"undefined"==typeof l&&(o.params.virtualTranslate=!0)),o.params.grabCursor&&o.support.touch&&(o.params.grabCursor=!1),o.wrapper=o.container.children("."+o.params.wrapperClass),o.params.pagination&&(o.paginationContainer=a(o.params.pagination),o.params.paginationClickable&&o.paginationContainer.addClass("swiper-pagination-clickable")),o.rtl=e()&&("rtl"===o.container[0].dir.toLowerCase()||"rtl"===o.container.css("direction")),o.rtl&&o.classNames.push("swiper-container-rtl"),o.rtl&&(o.wrongRTL="-webkit-box"===o.wrapper.css("display")),o.params.slidesPerColumn>1&&o.classNames.push("swiper-container-multirow"),o.device.android&&o.classNames.push("swiper-container-android"),o.container.addClass(o.classNames.join(" ")),o.translate=0,o.progress=0,o.velocity=0,o.lockSwipeToNext=function(){o.params.allowSwipeToNext=!1},o.lockSwipeToPrev=function(){o.params.allowSwipeToPrev=!1},o.lockSwipes=function(){o.params.allowSwipeToNext=o.params.allowSwipeToPrev=!1},o.unlockSwipeToNext=function(){o.params.allowSwipeToNext=!0},o.unlockSwipeToPrev=function(){o.params.allowSwipeToPrev=!0},o.unlockSwipes=function(){o.params.allowSwipeToNext=o.params.allowSwipeToPrev=!0},o.params.grabCursor&&(o.container[0].style.cursor="move",o.container[0].style.cursor="-webkit-grab",o.container[0].style.cursor="-moz-grab",o.container[0].style.cursor="grab"),o.imagesToLoad=[],o.imagesLoaded=0,o.loadImage=function(a,b,c,d){function e(){d&&d()}var f;a.complete&&c?e():b?(f=new Image,f.onload=e,f.onerror=e,f.src=b):e()},o.preloadImages=function(){function a(){"undefined"!=typeof o&&null!==o&&(void 0!==o.imagesLoaded&&o.imagesLoaded++,o.imagesLoaded===o.imagesToLoad.length&&(o.params.updateOnImagesReady&&o.update(),o.emit("onImagesReady",o)))}o.imagesToLoad=o.container.find("img");for(var b=0;b<o.imagesToLoad.length;b++)o.loadImage(o.imagesToLoad[b],o.imagesToLoad[b].currentSrc||o.imagesToLoad[b].getAttribute("src"),!0,a)},o.autoplayTimeoutId=void 0,o.autoplaying=!1,o.autoplayPaused=!1,o.startAutoplay=function(){return"undefined"!=typeof o.autoplayTimeoutId?!1:o.params.autoplay?o.autoplaying?!1:(o.autoplaying=!0,o.emit("onAutoplayStart",o),void f()):!1},o.stopAutoplay=function(){o.autoplayTimeoutId&&(o.autoplayTimeoutId&&clearTimeout(o.autoplayTimeoutId),o.autoplaying=!1,o.autoplayTimeoutId=void 0,o.emit("onAutoplayStop",o))},o.pauseAutoplay=function(a){o.autoplayPaused||(o.autoplayTimeoutId&&clearTimeout(o.autoplayTimeoutId),o.autoplayPaused=!0,0===a?(o.autoplayPaused=!1,f()):o.wrapper.transitionEnd(function(){o.autoplayPaused=!1,o.autoplaying?f():o.stopAutoplay()}))},o.minTranslate=function(){return-o.snapGrid[0]},o.maxTranslate=function(){return-o.snapGrid[o.snapGrid.length-1]},o.updateContainerSize=function(){o.width=o.container[0].clientWidth,o.height=o.container[0].clientHeight,o.size=e()?o.width:o.height},o.updateSlidesSize=function(){o.slides=o.wrapper.children("."+o.params.slideClass),o.snapGrid=[],o.slidesGrid=[],o.slidesSizesGrid=[];var a,b=o.params.spaceBetween,c=0,d=0,f=0;"string"==typeof b&&b.indexOf("%")>=0&&(b=parseFloat(b.replace("%",""))/100*o.size),o.virtualSize=-b,o.rtl?o.slides.css({marginLeft:"",marginTop:""}):o.slides.css({marginRight:"",marginBottom:""});var g;o.params.slidesPerColumn>1&&(g=Math.floor(o.slides.length/o.params.slidesPerColumn)===o.slides.length/o.params.slidesPerColumn?o.slides.length:Math.ceil(o.slides.length/o.params.slidesPerColumn)*o.params.slidesPerColumn);var h;for(a=0;a<o.slides.length;a++){h=0;var i=o.slides.eq(a);if(o.params.slidesPerColumn>1){var j,k,l,m,n=o.params.slidesPerColumn;"column"===o.params.slidesPerColumnFill?(k=Math.floor(a/n),l=a-k*n,j=k+l*g/n,i.css({"-webkit-box-ordinal-group":j,"-moz-box-ordinal-group":j,"-ms-flex-order":j,"-webkit-order":j,order:j})):(m=g/n,l=Math.floor(a/m),k=a-l*m),i.css({"margin-top":0!==l&&o.params.spaceBetween&&o.params.spaceBetween+"px"}).attr("data-swiper-column",k).attr("data-swiper-row",l)}"none"!==i.css("display")&&("auto"===o.params.slidesPerView?h=e()?i.outerWidth(!0):i.outerHeight(!0):(h=(o.size-(o.params.slidesPerView-1)*b)/o.params.slidesPerView,e()?o.slides[a].style.width=h+"px":o.slides[a].style.height=h+"px"),o.slides[a].swiperSlideSize=h,o.slidesSizesGrid.push(h),o.params.centeredSlides?(c=c+h/2+d/2+b,0===a&&(c=c-o.size/2-b),Math.abs(c)<.001&&(c=0),f%o.params.slidesPerGroup===0&&o.snapGrid.push(c),o.slidesGrid.push(c)):(f%o.params.slidesPerGroup===0&&o.snapGrid.push(c),o.slidesGrid.push(c),c=c+h+b),o.virtualSize+=h+b,d=h,f++)}o.virtualSize=Math.max(o.virtualSize,o.size);var p;if(o.rtl&&o.wrongRTL&&("slide"===o.params.effect||"coverflow"===o.params.effect)&&o.wrapper.css({width:o.virtualSize+o.params.spaceBetween+"px"}),(!o.support.flexbox||o.params.setWrapperSize)&&(e()?o.wrapper.css({width:o.virtualSize+o.params.spaceBetween+"px"}):o.wrapper.css({height:o.virtualSize+o.params.spaceBetween+"px"})),o.params.slidesPerColumn>1&&(o.virtualSize=(h+o.params.spaceBetween)*g,o.virtualSize=Math.ceil(o.virtualSize/o.params.slidesPerColumn)-o.params.spaceBetween,o.wrapper.css({width:o.virtualSize+o.params.spaceBetween+"px"}),o.params.centeredSlides)){for(p=[],a=0;a<o.snapGrid.length;a++)o.snapGrid[a]<o.virtualSize+o.snapGrid[0]&&p.push(o.snapGrid[a]);o.snapGrid=p}if(!o.params.centeredSlides){for(p=[],a=0;a<o.snapGrid.length;a++)o.snapGrid[a]<=o.virtualSize-o.size&&p.push(o.snapGrid[a]);o.snapGrid=p,Math.floor(o.virtualSize-o.size)>Math.floor(o.snapGrid[o.snapGrid.length-1])&&o.snapGrid.push(o.virtualSize-o.size)}0===o.snapGrid.length&&(o.snapGrid=[0]),0!==o.params.spaceBetween&&(e()?o.rtl?o.slides.css({marginLeft:b+"px"}):o.slides.css({marginRight:b+"px"}):o.slides.css({marginBottom:b+"px"})),o.params.watchSlidesProgress&&o.updateSlidesOffset()},o.updateSlidesOffset=function(){for(var a=0;a<o.slides.length;a++)o.slides[a].swiperSlideOffset=e()?o.slides[a].offsetLeft:o.slides[a].offsetTop},o.updateSlidesProgress=function(a){if("undefined"==typeof a&&(a=o.translate||0),0!==o.slides.length){"undefined"==typeof o.slides[0].swiperSlideOffset&&o.updateSlidesOffset();var b=o.params.centeredSlides?-a+o.size/2:-a;o.rtl&&(b=o.params.centeredSlides?a-o.size/2:a),o.slides.removeClass(o.params.slideVisibleClass);for(var c=0;c<o.slides.length;c++){var d=o.slides[c],e=o.params.centeredSlides===!0?d.swiperSlideSize/2:0,f=(b-d.swiperSlideOffset-e)/(d.swiperSlideSize+o.params.spaceBetween);if(o.params.watchSlidesVisibility){var g=-(b-d.swiperSlideOffset-e),h=g+o.slidesSizesGrid[c],i=g>=0&&g<o.size||h>0&&h<=o.size||0>=g&&h>=o.size;i&&o.slides.eq(c).addClass(o.params.slideVisibleClass)}d.progress=o.rtl?-f:f}}},o.updateProgress=function(a){"undefined"==typeof a&&(a=o.translate||0);var b=o.maxTranslate()-o.minTranslate();0===b?(o.progress=0,o.isBeginning=o.isEnd=!0):(o.progress=(a-o.minTranslate())/b,o.isBeginning=o.progress<=0,o.isEnd=o.progress>=1),o.isBeginning&&o.emit("onReachBeginning",o),o.isEnd&&o.emit("onReachEnd",o),o.params.watchSlidesProgress&&o.updateSlidesProgress(a),o.emit("onProgress",o,o.progress)},o.updateActiveIndex=function(){var a,b,c,d=o.rtl?o.translate:-o.translate;for(b=0;b<o.slidesGrid.length;b++)"undefined"!=typeof o.slidesGrid[b+1]?d>=o.slidesGrid[b]&&d<o.slidesGrid[b+1]-(o.slidesGrid[b+1]-o.slidesGrid[b])/2?a=b:d>=o.slidesGrid[b]&&d<o.slidesGrid[b+1]&&(a=b+1):d>=o.slidesGrid[b]&&(a=b);(0>a||"undefined"==typeof a)&&(a=0),c=Math.floor(a/o.params.slidesPerGroup),c>=o.snapGrid.length&&(c=o.snapGrid.length-1),a!==o.activeIndex&&(o.snapIndex=c,o.previousIndex=o.activeIndex,o.activeIndex=a,o.updateClasses())},o.updateClasses=function(){o.slides.removeClass(o.params.slideActiveClass+" "+o.params.slideNextClass+" "+o.params.slidePrevClass);var b=o.slides.eq(o.activeIndex);if(b.addClass(o.params.slideActiveClass),b.next("."+o.params.slideClass).addClass(o.params.slideNextClass),b.prev("."+o.params.slideClass).addClass(o.params.slidePrevClass),o.bullets&&o.bullets.length>0){o.bullets.removeClass(o.params.bulletActiveClass);var c;o.params.loop?(c=Math.ceil(o.activeIndex-o.loopedSlides)/o.params.slidesPerGroup,c>o.slides.length-1-2*o.loopedSlides&&(c-=o.slides.length-2*o.loopedSlides),c>o.bullets.length-1&&(c-=o.bullets.length)):c="undefined"!=typeof o.snapIndex?o.snapIndex:o.activeIndex||0,o.paginationContainer.length>1?o.bullets.each(function(){a(this).index()===c&&a(this).addClass(o.params.bulletActiveClass)}):o.bullets.eq(c).addClass(o.params.bulletActiveClass)}o.params.loop||(o.params.prevButton&&(o.isBeginning?(a(o.params.prevButton).addClass(o.params.buttonDisabledClass),o.params.a11y&&o.a11y&&o.a11y.disable(a(o.params.prevButton))):(a(o.params.prevButton).removeClass(o.params.buttonDisabledClass),o.params.a11y&&o.a11y&&o.a11y.enable(a(o.params.prevButton)))),o.params.nextButton&&(o.isEnd?(a(o.params.nextButton).addClass(o.params.buttonDisabledClass),o.params.a11y&&o.a11y&&o.a11y.disable(a(o.params.nextButton))):(a(o.params.nextButton).removeClass(o.params.buttonDisabledClass),o.params.a11y&&o.a11y&&o.a11y.enable(a(o.params.nextButton)))))},o.updatePagination=function(){if(o.params.pagination&&o.paginationContainer&&o.paginationContainer.length>0){for(var a="",b=o.params.loop?Math.ceil((o.slides.length-2*o.loopedSlides)/o.params.slidesPerGroup):o.snapGrid.length,c=0;b>c;c++)a+=o.params.paginationBulletRender?o.params.paginationBulletRender(c,o.params.bulletClass):'<span class="'+o.params.bulletClass+'"></span>';o.paginationContainer.html(a),o.bullets=o.paginationContainer.find("."+o.params.bulletClass)}},o.update=function(a){function b(){d=Math.min(Math.max(o.translate,o.maxTranslate()),o.minTranslate()),o.setWrapperTranslate(d),o.updateActiveIndex(),o.updateClasses()}if(o.updateContainerSize(),o.updateSlidesSize(),o.updateProgress(),o.updatePagination(),o.updateClasses(),o.params.scrollbar&&o.scrollbar&&o.scrollbar.set(),a){var c,d;o.params.freeMode?b():(c="auto"===o.params.slidesPerView&&o.isEnd&&!o.params.centeredSlides?o.slideTo(o.slides.length-1,0,!1,!0):o.slideTo(o.activeIndex,0,!1,!0),c||b())}},o.onResize=function(){if(o.updateContainerSize(),o.updateSlidesSize(),o.updateProgress(),("auto"===o.params.slidesPerView||o.params.freeMode)&&o.updatePagination(),o.params.scrollbar&&o.scrollbar&&o.scrollbar.set(),o.params.freeMode){var a=Math.min(Math.max(o.translate,o.maxTranslate()),o.minTranslate());o.setWrapperTranslate(a),o.updateActiveIndex(),o.updateClasses()}else o.updateClasses(),"auto"===o.params.slidesPerView&&o.isEnd&&!o.params.centeredSlides?o.slideTo(o.slides.length-1,0,!1,!0):o.slideTo(o.activeIndex,0,!1,!0)};var p=["mousedown","mousemove","mouseup"];window.navigator.pointerEnabled?p=["pointerdown","pointermove","pointerup"]:window.navigator.msPointerEnabled&&(p=["MSPointerDown","MSPointerMove","MSPointerUp"]),o.touchEvents={start:o.support.touch||!o.params.simulateTouch?"touchstart":p[0],move:o.support.touch||!o.params.simulateTouch?"touchmove":p[1],end:o.support.touch||!o.params.simulateTouch?"touchend":p[2]},(window.navigator.pointerEnabled||window.navigator.msPointerEnabled)&&("container"===o.params.touchEventsTarget?o.container:o.wrapper).addClass("swiper-wp8-"+o.params.direction),o.initEvents=function(b){var c=b?"off":"on",e=b?"removeEventListener":"addEventListener",f="container"===o.params.touchEventsTarget?o.container[0]:o.wrapper[0],g=o.support.touch?f:document,h=o.params.nested?!0:!1;o.browser.ie?(f[e](o.touchEvents.start,o.onTouchStart,!1),g[e](o.touchEvents.move,o.onTouchMove,h),g[e](o.touchEvents.end,o.onTouchEnd,!1)):(o.support.touch&&(f[e](o.touchEvents.start,o.onTouchStart,!1),f[e](o.touchEvents.move,o.onTouchMove,h),f[e](o.touchEvents.end,o.onTouchEnd,!1)),!d.simulateTouch||o.device.ios||o.device.android||(f[e]("mousedown",o.onTouchStart,!1),g[e]("mousemove",o.onTouchMove,h),g[e]("mouseup",o.onTouchEnd,!1))),window[e]("resize",o.onResize),o.params.nextButton&&(a(o.params.nextButton)[c]("click",o.onClickNext),o.params.a11y&&o.a11y&&a(o.params.nextButton)[c]("keydown",o.a11y.onEnterKey)),o.params.prevButton&&(a(o.params.prevButton)[c]("click",o.onClickPrev),o.params.a11y&&o.a11y&&a(o.params.prevButton)[c]("keydown",o.a11y.onEnterKey)),o.params.pagination&&o.params.paginationClickable&&a(o.paginationContainer)[c]("click","."+o.params.bulletClass,o.onClickIndex),(o.params.preventClicks||o.params.preventClicksPropagation)&&f[e]("click",o.preventClicks,!0)},o.attachEvents=function(){o.initEvents()},o.detachEvents=function(){o.initEvents(!0)},o.allowClick=!0,o.preventClicks=function(a){o.allowClick||(o.params.preventClicks&&a.preventDefault(),o.params.preventClicksPropagation&&(a.stopPropagation(),a.stopImmediatePropagation()))},o.onClickNext=function(a){a.preventDefault(),o.slideNext()},o.onClickPrev=function(a){a.preventDefault(),o.slidePrev()},o.onClickIndex=function(b){b.preventDefault();var c=a(this).index()*o.params.slidesPerGroup;o.params.loop&&(c+=o.loopedSlides),o.slideTo(c)},o.updateClickedSlide=function(b){var c=g(b,"."+o.params.slideClass);if(!c)return o.clickedSlide=void 0,void(o.clickedIndex=void 0);if(o.clickedSlide=c,o.clickedIndex=a(c).index(),o.params.slideToClickedSlide&&void 0!==o.clickedIndex&&o.clickedIndex!==o.activeIndex){var d,e=o.clickedIndex;if(o.params.loop)if(d=a(o.clickedSlide).attr("data-swiper-slide-index"),e>o.slides.length-o.params.slidesPerView)o.fixLoop(),e=o.wrapper.children("."+o.params.slideClass+'[data-swiper-slide-index="'+d+'"]').eq(0).index(),setTimeout(function(){o.slideTo(e)},0);else if(e<o.params.slidesPerView-1){o.fixLoop();var f=o.wrapper.children("."+o.params.slideClass+'[data-swiper-slide-index="'+d+'"]');e=f.eq(f.length-1).index(),setTimeout(function(){o.slideTo(e)},0)}else o.slideTo(e);else o.slideTo(e)}};var q,r,s,t,u,v,w,x,y,z="input, select, textarea, button",A=Date.now(),B=[];o.animating=!1,o.touches={startX:0,startY:0,currentX:0,currentY:0,diff:0};var C,D;o.onTouchStart=function(b){if(b.originalEvent&&(b=b.originalEvent),C="touchstart"===b.type,C||!("which"in b)||3!==b.which){if(o.params.noSwiping&&g(b,"."+o.params.noSwipingClass))return void(o.allowClick=!0);if(!o.params.swipeHandler||g(b,o.params.swipeHandler)){if(q=!0,r=!1,t=void 0,D=void 0,o.touches.startX=o.touches.currentX="touchstart"===b.type?b.targetTouches[0].pageX:b.pageX,o.touches.startY=o.touches.currentY="touchstart"===b.type?b.targetTouches[0].pageY:b.pageY,s=Date.now(),o.allowClick=!0,o.updateContainerSize(),o.swipeDirection=void 0,o.params.threshold>0&&(w=!1),"touchstart"!==b.type){var c=!0;a(b.target).is(z)&&(c=!1),document.activeElement&&a(document.activeElement).is(z)&&document.activeElement.blur(),c&&b.preventDefault()}o.emit("onTouchStart",o,b)}}},o.onTouchMove=function(b){if(b.originalEvent&&(b=b.originalEvent),!(C&&"mousemove"===b.type||b.preventedByNestedSwiper)){if(o.params.onlyExternal)return r=!0,void(o.allowClick=!1);if(C&&document.activeElement&&b.target===document.activeElement&&a(b.target).is(z))return r=!0,void(o.allowClick=!1);if(o.emit("onTouchMove",o,b),!(b.targetTouches&&b.targetTouches.length>1)){if(o.touches.currentX="touchmove"===b.type?b.targetTouches[0].pageX:b.pageX,o.touches.currentY="touchmove"===b.type?b.targetTouches[0].pageY:b.pageY,"undefined"==typeof t){var c=180*Math.atan2(Math.abs(o.touches.currentY-o.touches.startY),Math.abs(o.touches.currentX-o.touches.startX))/Math.PI;t=e()?c>o.params.touchAngle:90-c>o.params.touchAngle}if(t&&o.emit("onTouchMoveOpposite",o,b),"undefined"==typeof D&&o.browser.ieTouch&&(o.touches.currentX!==o.touches.startX||o.touches.currentY!==o.touches.startY)&&(D=!0),q){if(t)return void(q=!1);if(D||!o.browser.ieTouch){o.allowClick=!1,o.emit("onSliderMove",o,b),b.preventDefault(),o.params.touchMoveStopPropagation&&!o.params.nested&&b.stopPropagation(),r||(d.loop&&o.fixLoop(),v=o.getWrapperTranslate(),o.setWrapperTransition(0),o.animating&&o.wrapper.trigger("webkitTransitionEnd transitionend oTransitionEnd MSTransitionEnd msTransitionEnd"),o.params.autoplay&&o.autoplaying&&(o.params.autoplayDisableOnInteraction?o.stopAutoplay():o.pauseAutoplay()),y=!1,o.params.grabCursor&&(o.container[0].style.cursor="move",o.container[0].style.cursor="-webkit-grabbing",o.container[0].style.cursor="-moz-grabbin",o.container[0].style.cursor="grabbing")),r=!0;var f=o.touches.diff=e()?o.touches.currentX-o.touches.startX:o.touches.currentY-o.touches.startY;f*=o.params.touchRatio,o.rtl&&(f=-f),o.swipeDirection=f>0?"prev":"next",u=f+v;var g=!0;if(f>0&&u>o.minTranslate()?(g=!1,o.params.resistance&&(u=o.minTranslate()-1+Math.pow(-o.minTranslate()+v+f,o.params.resistanceRatio))):0>f&&u<o.maxTranslate()&&(g=!1,o.params.resistance&&(u=o.maxTranslate()+1-Math.pow(o.maxTranslate()-v-f,o.params.resistanceRatio))),g&&(b.preventedByNestedSwiper=!0),!o.params.allowSwipeToNext&&"next"===o.swipeDirection&&v>u&&(u=v),!o.params.allowSwipeToPrev&&"prev"===o.swipeDirection&&u>v&&(u=v),o.params.followFinger){if(o.params.threshold>0){if(!(Math.abs(f)>o.params.threshold||w))return void(u=v);if(!w)return w=!0,o.touches.startX=o.touches.currentX,o.touches.startY=o.touches.currentY,u=v,void(o.touches.diff=e()?o.touches.currentX-o.touches.startX:o.touches.currentY-o.touches.startY)}(o.params.freeMode||o.params.watchSlidesProgress)&&o.updateActiveIndex(),o.params.freeMode&&(0===B.length&&B.push({position:o.touches[e()?"startX":"startY"],time:s}),B.push({position:o.touches[e()?"currentX":"currentY"],time:(new Date).getTime()})),o.updateProgress(u),o.setWrapperTranslate(u)}}}}}},o.onTouchEnd=function(b){if(b.originalEvent&&(b=b.originalEvent),o.emit("onTouchEnd",o,b),q){o.params.grabCursor&&r&&q&&(o.container[0].style.cursor="move",o.container[0].style.cursor="-webkit-grab",o.container[0].style.cursor="-moz-grab",o.container[0].style.cursor="grab");var c=Date.now(),d=c-s;if(o.allowClick&&(o.updateClickedSlide(b),o.emit("onTap",o,b),300>d&&c-A>300&&(x&&clearTimeout(x),x=setTimeout(function(){o&&(o.params.paginationHide&&o.paginationContainer.length>0&&!a(b.target).hasClass(o.params.bulletClass)&&o.paginationContainer.toggleClass(o.params.paginationHiddenClass),o.emit("onClick",o,b))},300)),300>d&&300>c-A&&(x&&clearTimeout(x),o.emit("onDoubleTap",o,b))),A=Date.now(),setTimeout(function(){o&&o.allowClick&&(o.allowClick=!0)},0),!q||!r||!o.swipeDirection||0===o.touches.diff||u===v)return void(q=r=!1);q=r=!1;var e;if(e=o.params.followFinger?o.rtl?o.translate:-o.translate:-u,o.params.freeMode){if(e<-o.minTranslate())return void o.slideTo(o.activeIndex);if(e>-o.maxTranslate())return void o.slideTo(o.slides.length-1);if(o.params.freeModeMomentum){if(B.length>1){var f=B.pop(),g=B.pop(),h=f.position-g.position,i=f.time-g.time;o.velocity=h/i,o.velocity=o.velocity/2,Math.abs(o.velocity)<.02&&(o.velocity=0),(i>150||(new Date).getTime()-f.time>300)&&(o.velocity=0)}else o.velocity=0;B.length=0;var j=1e3*o.params.freeModeMomentumRatio,k=o.velocity*j,l=o.translate+k;o.rtl&&(l=-l);var m,n=!1,p=20*Math.abs(o.velocity)*o.params.freeModeMomentumBounceRatio;l<o.maxTranslate()&&(o.params.freeModeMomentumBounce?(l+o.maxTranslate()<-p&&(l=o.maxTranslate()-p),m=o.maxTranslate(),n=!0,y=!0):l=o.maxTranslate()),l>o.minTranslate()&&(o.params.freeModeMomentumBounce?(l-o.minTranslate()>p&&(l=o.minTranslate()+p),m=o.minTranslate(),n=!0,y=!0):l=o.minTranslate()),0!==o.velocity&&(j=o.rtl?Math.abs((-l-o.translate)/o.velocity):Math.abs((l-o.translate)/o.velocity)),o.params.freeModeMomentumBounce&&n?(o.updateProgress(m),o.setWrapperTransition(j),o.setWrapperTranslate(l),o.onTransitionStart(),o.animating=!0,o.wrapper.transitionEnd(function(){y&&(o.emit("onMomentumBounce",o),o.setWrapperTransition(o.params.speed),o.setWrapperTranslate(m),o.wrapper.transitionEnd(function(){o.onTransitionEnd()}))})):o.velocity?(o.updateProgress(l),o.setWrapperTransition(j),o.setWrapperTranslate(l),o.onTransitionStart(),o.animating||(o.animating=!0,o.wrapper.transitionEnd(function(){o.onTransitionEnd()}))):o.updateProgress(l),o.updateActiveIndex()}return void((!o.params.freeModeMomentum||d>=o.params.longSwipesMs)&&(o.updateProgress(),o.updateActiveIndex()))}var t,w=0,z=o.slidesSizesGrid[0];for(t=0;t<o.slidesGrid.length;t+=o.params.slidesPerGroup)"undefined"!=typeof o.slidesGrid[t+o.params.slidesPerGroup]?e>=o.slidesGrid[t]&&e<o.slidesGrid[t+o.params.slidesPerGroup]&&(w=t,z=o.slidesGrid[t+o.params.slidesPerGroup]-o.slidesGrid[t]):e>=o.slidesGrid[t]&&(w=t,z=o.slidesGrid[o.slidesGrid.length-1]-o.slidesGrid[o.slidesGrid.length-2]);var C=(e-o.slidesGrid[w])/z;if(d>o.params.longSwipesMs){if(!o.params.longSwipes)return void o.slideTo(o.activeIndex);"next"===o.swipeDirection&&(C>=o.params.longSwipesRatio?o.slideTo(w+o.params.slidesPerGroup):o.slideTo(w)),"prev"===o.swipeDirection&&(C>1-o.params.longSwipesRatio?o.slideTo(w+o.params.slidesPerGroup):o.slideTo(w))}else{if(!o.params.shortSwipes)return void o.slideTo(o.activeIndex);"next"===o.swipeDirection&&o.slideTo(w+o.params.slidesPerGroup),"prev"===o.swipeDirection&&o.slideTo(w)}}},o._slideTo=function(a,b){return o.slideTo(a,b,!0,!0)},o.slideTo=function(a,b,c,d){"undefined"==typeof c&&(c=!0),"undefined"==typeof a&&(a=0),0>a&&(a=0),o.snapIndex=Math.floor(a/o.params.slidesPerGroup),o.snapIndex>=o.snapGrid.length&&(o.snapIndex=o.snapGrid.length-1);var e=-o.snapGrid[o.snapIndex];o.params.autoplay&&o.autoplaying&&(d||!o.params.autoplayDisableOnInteraction?o.pauseAutoplay(b):o.stopAutoplay()),o.updateProgress(e);for(var f=0;f<o.slidesGrid.length;f++)-e>=o.slidesGrid[f]&&(a=f);return"undefined"==typeof b&&(b=o.params.speed),o.previousIndex=o.activeIndex||0,o.activeIndex=a,e===o.translate?(o.updateClasses(),!1):(o.onTransitionStart(c),0===b?(o.setWrapperTransition(0),o.setWrapperTranslate(e),o.onTransitionEnd(c)):(o.setWrapperTransition(b),o.setWrapperTranslate(e),o.animating||(o.animating=!0,o.wrapper.transitionEnd(function(){o.onTransitionEnd(c)}))),o.updateClasses(),!0)},o.onTransitionStart=function(a){"undefined"==typeof a&&(a=!0),o.lazy&&o.lazy.onTransitionStart(),a&&(o.emit("onTransitionStart",o),o.activeIndex!==o.previousIndex&&o.emit("onSlideChangeStart",o))},o.onTransitionEnd=function(a){o.animating=!1,o.setWrapperTransition(0),"undefined"==typeof a&&(a=!0),o.lazy&&o.lazy.onTransitionEnd(),a&&(o.emit("onTransitionEnd",o),o.activeIndex!==o.previousIndex&&o.emit("onSlideChangeEnd",o)),o.params.hashnav&&o.hashnav&&o.hashnav.setHash()},o.slideNext=function(a,b,c){return o.params.loop?o.animating?!1:(o.fixLoop(),o.slideTo(o.activeIndex+o.params.slidesPerGroup,b,a,c)):o.slideTo(o.activeIndex+o.params.slidesPerGroup,b,a,c)},o._slideNext=function(a){return o.slideNext(!0,a,!0)},o.slidePrev=function(a,b,c){return o.params.loop?o.animating?!1:(o.fixLoop(),o.slideTo(o.activeIndex-1,b,a,c)):o.slideTo(o.activeIndex-1,b,a,c)},o._slidePrev=function(a){return o.slidePrev(!0,a,!0)},o.slideReset=function(a,b){return o.slideTo(o.activeIndex,b,a)},o.setWrapperTransition=function(a,b){o.wrapper.transition(a),"slide"!==o.params.effect&&o.effects[o.params.effect]&&o.effects[o.params.effect].setTransition(a),o.params.parallax&&o.parallax&&o.parallax.setTransition(a),o.params.scrollbar&&o.scrollbar&&o.scrollbar.setTransition(a),o.params.control&&o.controller&&o.controller.setTransition(a,b),o.emit("onSetTransition",o,a)},o.setWrapperTranslate=function(a,b,c){var d=0,f=0,g=0;e()?d=o.rtl?-a:a:f=a,o.params.virtualTranslate||(o.support.transforms3d?o.wrapper.transform("translate3d("+d+"px, "+f+"px, "+g+"px)"):o.wrapper.transform("translate("+d+"px, "+f+"px)")),o.translate=e()?d:f,b&&o.updateActiveIndex(),"slide"!==o.params.effect&&o.effects[o.params.effect]&&o.effects[o.params.effect].setTranslate(o.translate),o.params.parallax&&o.parallax&&o.parallax.setTranslate(o.translate),o.params.scrollbar&&o.scrollbar&&o.scrollbar.setTranslate(o.translate),o.params.control&&o.controller&&o.controller.setTranslate(o.translate,c),o.emit("onSetTranslate",o,o.translate)},o.getTranslate=function(a,b){var c,d,e,f;return"undefined"==typeof b&&(b="x"),o.params.virtualTranslate?o.rtl?-o.translate:o.translate:(e=window.getComputedStyle(a,null),window.WebKitCSSMatrix?f=new WebKitCSSMatrix("none"===e.webkitTransform?"":e.webkitTransform):(f=e.MozTransform||e.OTransform||e.MsTransform||e.msTransform||e.transform||e.getPropertyValue("transform").replace("translate(","matrix(1, 0, 0, 1,"),c=f.toString().split(",")),"x"===b&&(d=window.WebKitCSSMatrix?f.m41:16===c.length?parseFloat(c[12]):parseFloat(c[4])),"y"===b&&(d=window.WebKitCSSMatrix?f.m42:16===c.length?parseFloat(c[13]):parseFloat(c[5])),o.rtl&&d&&(d=-d),d||0)},o.getWrapperTranslate=function(a){return"undefined"==typeof a&&(a=e()?"x":"y"),o.getTranslate(o.wrapper[0],a)},o.observers=[],o.initObservers=function(){if(o.params.observeParents)for(var a=o.container.parents(),b=0;b<a.length;b++)h(a[b]);h(o.container[0],{childList:!1}),h(o.wrapper[0],{attributes:!1})},o.disconnectObservers=function(){for(var a=0;a<o.observers.length;a++)o.observers[a].disconnect();o.observers=[]},o.createLoop=function(){o.wrapper.children("."+o.params.slideClass+"."+o.params.slideDuplicateClass).remove();var b=o.wrapper.children("."+o.params.slideClass);o.loopedSlides=parseInt(o.params.loopedSlides||o.params.slidesPerView,10),o.loopedSlides=o.loopedSlides+o.params.loopAdditionalSlides,o.loopedSlides>b.length&&(o.loopedSlides=b.length);var c,d=[],e=[];for(b.each(function(c,f){var g=a(this);c<o.loopedSlides&&e.push(f),c<b.length&&c>=b.length-o.loopedSlides&&d.push(f),g.attr("data-swiper-slide-index",c)}),c=0;c<e.length;c++)o.wrapper.append(a(e[c].cloneNode(!0)).addClass(o.params.slideDuplicateClass));for(c=d.length-1;c>=0;c--)o.wrapper.prepend(a(d[c].cloneNode(!0)).addClass(o.params.slideDuplicateClass))},o.destroyLoop=function(){o.wrapper.children("."+o.params.slideClass+"."+o.params.slideDuplicateClass).remove(),o.slides.removeAttr("data-swiper-slide-index")},o.fixLoop=function(){var a;o.activeIndex<o.loopedSlides?(a=o.slides.length-3*o.loopedSlides+o.activeIndex,a+=o.loopedSlides,o.slideTo(a,0,!1,!0)):("auto"===o.params.slidesPerView&&o.activeIndex>=2*o.loopedSlides||o.activeIndex>o.slides.length-2*o.params.slidesPerView)&&(a=-o.slides.length+o.activeIndex+o.loopedSlides,a+=o.loopedSlides,o.slideTo(a,0,!1,!0))},o.appendSlide=function(a){if(o.params.loop&&o.destroyLoop(),"object"==typeof a&&a.length)for(var b=0;b<a.length;b++)a[b]&&o.wrapper.append(a[b]);else o.wrapper.append(a);o.params.loop&&o.createLoop(),o.params.observer&&o.support.observer||o.update(!0)},o.prependSlide=function(a){o.params.loop&&o.destroyLoop();var b=o.activeIndex+1;if("object"==typeof a&&a.length){for(var c=0;c<a.length;c++)a[c]&&o.wrapper.prepend(a[c]);b=o.activeIndex+a.length}else o.wrapper.prepend(a);o.params.loop&&o.createLoop(),o.params.observer&&o.support.observer||o.update(!0),o.slideTo(b,0,!1)},o.removeSlide=function(a){o.params.loop&&o.destroyLoop();var b,c=o.activeIndex;if("object"==typeof a&&a.length){for(var d=0;d<a.length;d++)b=a[d],o.slides[b]&&o.slides.eq(b).remove(),c>b&&c--;c=Math.max(c,0)}else b=a,o.slides[b]&&o.slides.eq(b).remove(),c>b&&c--,c=Math.max(c,0);o.params.observer&&o.support.observer||o.update(!0),o.slideTo(c,0,!1)},o.removeAllSlides=function(){for(var a=[],b=0;b<o.slides.length;b++)a.push(b);o.removeSlide(a)},o.effects={fade:{fadeIndex:null,setTranslate:function(){for(var a=0;a<o.slides.length;a++){var b=o.slides.eq(a),c=b[0].swiperSlideOffset,d=-c;o.params.virtualTranslate||(d-=o.translate);var f=0;e()||(f=d,d=0);var g=o.params.fade.crossFade?Math.max(1-Math.abs(b[0].progress),0):1+Math.min(Math.max(b[0].progress,-1),0);g>0&&1>g&&(o.effects.fade.fadeIndex=a),b.css({opacity:g}).transform("translate3d("+d+"px, "+f+"px, 0px)")}},setTransition:function(a){if(o.slides.transition(a),o.params.virtualTranslate&&0!==a){var b=null!==o.effects.fade.fadeIndex?o.effects.fade.fadeIndex:o.activeIndex;o.slides.eq(b).transitionEnd(function(){for(var a=["webkitTransitionEnd","transitionend","oTransitionEnd","MSTransitionEnd","msTransitionEnd"],b=0;b<a.length;b++)o.wrapper.trigger(a[b])})}}},cube:{setTranslate:function(){var b,c=0;o.params.cube.shadow&&(e()?(b=o.wrapper.find(".swiper-cube-shadow"),0===b.length&&(b=a('<div class="swiper-cube-shadow"></div>'),o.wrapper.append(b)),b.css({height:o.width+"px"})):(b=o.container.find(".swiper-cube-shadow"),0===b.length&&(b=a('<div class="swiper-cube-shadow"></div>'),o.container.append(b))));for(var d=0;d<o.slides.length;d++){var f=o.slides.eq(d),g=90*d,h=Math.floor(g/360);o.rtl&&(g=-g,h=Math.floor(-g/360));var i=Math.max(Math.min(f[0].progress,1),-1),j=0,k=0,l=0;d%4===0?(j=4*-h*o.size,l=0):(d-1)%4===0?(j=0,l=4*-h*o.size):(d-2)%4===0?(j=o.size+4*h*o.size,l=o.size):(d-3)%4===0&&(j=-o.size,l=3*o.size+4*o.size*h),o.rtl&&(j=-j),e()||(k=j,j=0);var m="rotateX("+(e()?0:-g)+"deg) rotateY("+(e()?g:0)+"deg) translate3d("+j+"px, "+k+"px, "+l+"px)";if(1>=i&&i>-1&&(c=90*d+90*i,o.rtl&&(c=90*-d-90*i)),f.transform(m),o.params.cube.slideShadows){var n=e()?f.find(".swiper-slide-shadow-left"):f.find(".swiper-slide-shadow-top"),p=e()?f.find(".swiper-slide-shadow-right"):f.find(".swiper-slide-shadow-bottom");0===n.length&&(n=a('<div class="swiper-slide-shadow-'+(e()?"left":"top")+'"></div>'),f.append(n)),0===p.length&&(p=a('<div class="swiper-slide-shadow-'+(e()?"right":"bottom")+'"></div>'),f.append(p)),n.length&&(n[0].style.opacity=-f[0].progress),p.length&&(p[0].style.opacity=f[0].progress)}}if(o.wrapper.css({"-webkit-transform-origin":"50% 50% -"+o.size/2+"px","-moz-transform-origin":"50% 50% -"+o.size/2+"px","-ms-transform-origin":"50% 50% -"+o.size/2+"px",
"transform-origin":"50% 50% -"+o.size/2+"px"}),o.params.cube.shadow)if(e())b.transform("translate3d(0px, "+(o.width/2+o.params.cube.shadowOffset)+"px, "+-o.width/2+"px) rotateX(90deg) rotateZ(0deg) scale("+o.params.cube.shadowScale+")");else{var q=Math.abs(c)-90*Math.floor(Math.abs(c)/90),r=1.5-(Math.sin(2*q*Math.PI/360)/2+Math.cos(2*q*Math.PI/360)/2),s=o.params.cube.shadowScale,t=o.params.cube.shadowScale/r,u=o.params.cube.shadowOffset;b.transform("scale3d("+s+", 1, "+t+") translate3d(0px, "+(o.height/2+u)+"px, "+-o.height/2/t+"px) rotateX(-90deg)")}var v=o.isSafari||o.isUiWebView?-o.size/2:0;o.wrapper.transform("translate3d(0px,0,"+v+"px) rotateX("+(e()?0:c)+"deg) rotateY("+(e()?-c:0)+"deg)")},setTransition:function(a){o.slides.transition(a).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(a),o.params.cube.shadow&&!e()&&o.container.find(".swiper-cube-shadow").transition(a)}},coverflow:{setTranslate:function(){for(var b=o.translate,c=e()?-b+o.width/2:-b+o.height/2,d=e()?o.params.coverflow.rotate:-o.params.coverflow.rotate,f=o.params.coverflow.depth,g=0,h=o.slides.length;h>g;g++){var i=o.slides.eq(g),j=o.slidesSizesGrid[g],k=i[0].swiperSlideOffset,l=(c-k-j/2)/j*o.params.coverflow.modifier,m=e()?d*l:0,n=e()?0:d*l,p=-f*Math.abs(l),q=e()?0:o.params.coverflow.stretch*l,r=e()?o.params.coverflow.stretch*l:0;Math.abs(r)<.001&&(r=0),Math.abs(q)<.001&&(q=0),Math.abs(p)<.001&&(p=0),Math.abs(m)<.001&&(m=0),Math.abs(n)<.001&&(n=0);var s="translate3d("+r+"px,"+q+"px,"+p+"px)  rotateX("+n+"deg) rotateY("+m+"deg)";if(i.transform(s),i[0].style.zIndex=-Math.abs(Math.round(l))+1,o.params.coverflow.slideShadows){var t=e()?i.find(".swiper-slide-shadow-left"):i.find(".swiper-slide-shadow-top"),u=e()?i.find(".swiper-slide-shadow-right"):i.find(".swiper-slide-shadow-bottom");0===t.length&&(t=a('<div class="swiper-slide-shadow-'+(e()?"left":"top")+'"></div>'),i.append(t)),0===u.length&&(u=a('<div class="swiper-slide-shadow-'+(e()?"right":"bottom")+'"></div>'),i.append(u)),t.length&&(t[0].style.opacity=l>0?l:0),u.length&&(u[0].style.opacity=-l>0?-l:0)}}if(o.browser.ie){var v=o.wrapper[0].style;v.perspectiveOrigin=c+"px 50%"}},setTransition:function(a){o.slides.transition(a).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(a)}}},o.lazy={initialImageLoaded:!1,loadImageInSlide:function(b){if("undefined"!=typeof b&&0!==o.slides.length){var c=o.slides.eq(b),d=c.find("img.swiper-lazy:not(.swiper-lazy-loaded):not(.swiper-lazy-loading)");0!==d.length&&d.each(function(){var b=a(this);b.addClass("swiper-lazy-loading");var d=b.attr("data-src");o.loadImage(b[0],d,!1,function(){b.attr("src",d),b.removeAttr("data-src"),b.addClass("swiper-lazy-loaded").removeClass("swiper-lazy-loading"),c.find(".swiper-lazy-preloader, .preloader").remove(),o.emit("onLazyImageReady",o,c[0],b[0])}),o.emit("onLazyImageLoad",o,c[0],b[0])})}},load:function(){if(o.params.watchSlidesVisibility)o.wrapper.children("."+o.params.slideVisibleClass).each(function(){o.lazy.loadImageInSlide(a(this).index())});else if(o.params.slidesPerView>1)for(var b=o.activeIndex;b<o.activeIndex+o.params.slidesPerView;b++)o.slides[b]&&o.lazy.loadImageInSlide(b);else o.lazy.loadImageInSlide(o.activeIndex);if(o.params.lazyLoadingInPrevNext){var c=o.wrapper.children("."+o.params.slideNextClass);c.length>0&&o.lazy.loadImageInSlide(c.index());var d=o.wrapper.children("."+o.params.slidePrevClass);d.length>0&&o.lazy.loadImageInSlide(d.index())}},onTransitionStart:function(){o.params.lazyLoading&&(o.params.lazyLoadingOnTransitionStart||!o.params.lazyLoadingOnTransitionStart&&!o.lazy.initialImageLoaded)&&(o.lazy.initialImageLoaded=!0,o.lazy.load())},onTransitionEnd:function(){o.params.lazyLoading&&!o.params.lazyLoadingOnTransitionStart&&o.lazy.load()}},o.scrollbar={set:function(){if(o.params.scrollbar){var b=o.scrollbar;b.track=a(o.params.scrollbar),b.drag=b.track.find(".swiper-scrollbar-drag"),0===b.drag.length&&(b.drag=a('<div class="swiper-scrollbar-drag"></div>'),b.track.append(b.drag)),b.drag[0].style.width="",b.drag[0].style.height="",b.trackSize=e()?b.track[0].offsetWidth:b.track[0].offsetHeight,b.divider=o.size/o.virtualSize,b.moveDivider=b.divider*(b.trackSize/o.size),b.dragSize=b.trackSize*b.divider,e()?b.drag[0].style.width=b.dragSize+"px":b.drag[0].style.height=b.dragSize+"px",b.divider>=1?b.track[0].style.display="none":b.track[0].style.display="",o.params.scrollbarHide&&(b.track[0].style.opacity=0)}},setTranslate:function(){if(o.params.scrollbar){var a,b=o.scrollbar,c=b.dragSize;a=(b.trackSize-b.dragSize)*o.progress,o.rtl&&e()?(a=-a,a>0?(c=b.dragSize-a,a=0):-a+b.dragSize>b.trackSize&&(c=b.trackSize+a)):0>a?(c=b.dragSize+a,a=0):a+b.dragSize>b.trackSize&&(c=b.trackSize-a),e()?(o.support.transforms3d?b.drag.transform("translate3d("+a+"px, 0, 0)"):b.drag.transform("translateX("+a+"px)"),b.drag[0].style.width=c+"px"):(o.support.transforms3d?b.drag.transform("translate3d(0px, "+a+"px, 0)"):b.drag.transform("translateY("+a+"px)"),b.drag[0].style.height=c+"px"),o.params.scrollbarHide&&(clearTimeout(b.timeout),b.track[0].style.opacity=1,b.timeout=setTimeout(function(){b.track[0].style.opacity=0,b.track.transition(400)},1e3))}},setTransition:function(a){o.params.scrollbar&&o.scrollbar.drag.transition(a)}},o.controller={setTranslate:function(a,c){var d,e,f=o.params.control;if(o.isArray(f))for(var g=0;g<f.length;g++)f[g]!==c&&f[g]instanceof b&&(a=f[g].rtl&&"horizontal"===f[g].params.direction?-o.translate:o.translate,d=(f[g].maxTranslate()-f[g].minTranslate())/(o.maxTranslate()-o.minTranslate()),e=(a-o.minTranslate())*d+f[g].minTranslate(),o.params.controlInverse&&(e=f[g].maxTranslate()-e),f[g].updateProgress(e),f[g].setWrapperTranslate(e,!1,o),f[g].updateActiveIndex());else f instanceof b&&c!==f&&(a=f.rtl&&"horizontal"===f.params.direction?-o.translate:o.translate,d=(f.maxTranslate()-f.minTranslate())/(o.maxTranslate()-o.minTranslate()),e=(a-o.minTranslate())*d+f.minTranslate(),o.params.controlInverse&&(e=f.maxTranslate()-e),f.updateProgress(e),f.setWrapperTranslate(e,!1,o),f.updateActiveIndex())},setTransition:function(a,c){var d=o.params.control;if(o.isArray(d))for(var e=0;e<d.length;e++)d[e]!==c&&d[e]instanceof b&&d[e].setWrapperTransition(a,o);else d instanceof b&&c!==d&&d.setWrapperTransition(a,o)}},o.parallax={setTranslate:function(){o.container.children("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function(){i(this,o.progress)}),o.slides.each(function(){var b=a(this);b.find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function(){var a=Math.min(Math.max(b[0].progress,-1),1);i(this,a)})})},setTransition:function(b){"undefined"==typeof b&&(b=o.params.speed),o.container.find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function(){var c=a(this),d=parseInt(c.attr("data-swiper-parallax-duration"),10)||b;0===b&&(d=0),c.transition(d)})}},o._plugins=[];for(var E in o.plugins)if(o.plugins.hasOwnProperty(E)){var F=o.plugins[E](o,o.params[E]);F&&o._plugins.push(F)}return o.callPlugins=function(a){for(var b=0;b<o._plugins.length;b++)a in o._plugins[b]&&o._plugins[b][a](arguments[1],arguments[2],arguments[3],arguments[4],arguments[5])},o.emitterEventListeners={},o.emit=function(a){o.params[a]&&o.params[a](arguments[1],arguments[2],arguments[3],arguments[4],arguments[5]);var b;if(o){if(o.emitterEventListeners[a])for(b=0;b<o.emitterEventListeners[a].length;b++)o.emitterEventListeners[a][b](arguments[1],arguments[2],arguments[3],arguments[4],arguments[5]);o.callPlugins&&o.callPlugins(a,arguments[1],arguments[2],arguments[3],arguments[4],arguments[5])}},o.on=function(a,b){return a=j(a),o.emitterEventListeners[a]||(o.emitterEventListeners[a]=[]),o.emitterEventListeners[a].push(b),o},o.off=function(a,b){var c;if(a=j(a),"undefined"==typeof b)return o.emitterEventListeners[a]=[],o;if(o.emitterEventListeners[a]&&0!==o.emitterEventListeners[a].length){for(c=0;c<o.emitterEventListeners[a].length;c++)o.emitterEventListeners[a][c]===b&&o.emitterEventListeners[a].splice(c,1);return o}},o.once=function(a,b){a=j(a);var c=function(){b(arguments[0],arguments[1],arguments[2],arguments[3],arguments[4]),o.off(a,c)};return o.on(a,c),o},o.a11y={makeFocusable:function(a){return a[0].tabIndex="0",a},addRole:function(a,b){return a.attr("role",b),a},addLabel:function(a,b){return a.attr("aria-label",b),a},disable:function(a){return a.attr("aria-disabled",!0),a},enable:function(a){return a.attr("aria-disabled",!1),a},onEnterKey:function(b){13===b.keyCode&&(a(b.target).is(o.params.nextButton)?(o.onClickNext(b),o.isEnd?o.a11y.notify(o.params.lastSlideMsg):o.a11y.notify(o.params.nextSlideMsg)):a(b.target).is(o.params.prevButton)&&(o.onClickPrev(b),o.isBeginning?o.a11y.notify(o.params.firstSlideMsg):o.a11y.notify(o.params.prevSlideMsg)))},liveRegion:a('<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>'),notify:function(a){var b=o.a11y.liveRegion;0!==b.length&&(b.html(""),b.html(a))},init:function(){if(o.params.nextButton){var b=a(o.params.nextButton);o.a11y.makeFocusable(b),o.a11y.addRole(b,"button"),o.a11y.addLabel(b,o.params.nextSlideMsg)}if(o.params.prevButton){var c=a(o.params.prevButton);o.a11y.makeFocusable(c),o.a11y.addRole(c,"button"),o.a11y.addLabel(c,o.params.prevSlideMsg)}a(o.container).append(o.a11y.liveRegion)},destroy:function(){o.a11y.liveRegion&&o.a11y.liveRegion.length>0&&o.a11y.liveRegion.remove()}},o.init=function(){o.params.loop&&o.createLoop(),o.updateContainerSize(),o.updateSlidesSize(),o.updatePagination(),o.params.scrollbar&&o.scrollbar&&o.scrollbar.set(),"slide"!==o.params.effect&&o.effects[o.params.effect]&&(o.params.loop||o.updateProgress(),o.effects[o.params.effect].setTranslate()),o.params.loop?o.slideTo(o.params.initialSlide+o.loopedSlides,0,o.params.runCallbacksOnInit):(o.slideTo(o.params.initialSlide,0,o.params.runCallbacksOnInit),0===o.params.initialSlide&&(o.parallax&&o.params.parallax&&o.parallax.setTranslate(),o.lazy&&o.params.lazyLoading&&o.lazy.load())),o.attachEvents(),o.params.observer&&o.support.observer&&o.initObservers(),o.params.preloadImages&&!o.params.lazyLoading&&o.preloadImages(),o.params.autoplay&&o.startAutoplay(),o.params.keyboardControl&&o.enableKeyboardControl&&o.enableKeyboardControl(),o.params.mousewheelControl&&o.enableMousewheelControl&&o.enableMousewheelControl(),o.params.hashnav&&o.hashnav&&o.hashnav.init(),o.params.a11y&&o.a11y&&o.a11y.init(),o.emit("onInit",o)},o.cleanupStyles=function(){o.container.removeClass(o.classNames.join(" ")).removeAttr("style"),o.wrapper.removeAttr("style"),o.slides&&o.slides.length&&o.slides.removeClass([o.params.slideVisibleClass,o.params.slideActiveClass,o.params.slideNextClass,o.params.slidePrevClass].join(" ")).removeAttr("style").removeAttr("data-swiper-column").removeAttr("data-swiper-row"),o.paginationContainer&&o.paginationContainer.length&&o.paginationContainer.removeClass(o.params.paginationHiddenClass),o.bullets&&o.bullets.length&&o.bullets.removeClass(o.params.bulletActiveClass),o.params.prevButton&&a(o.params.prevButton).removeClass(o.params.buttonDisabledClass),o.params.nextButton&&a(o.params.nextButton).removeClass(o.params.buttonDisabledClass),o.params.scrollbar&&o.scrollbar&&(o.scrollbar.track&&o.scrollbar.track.length&&o.scrollbar.track.removeAttr("style"),o.scrollbar.drag&&o.scrollbar.drag.length&&o.scrollbar.drag.removeAttr("style"))},o.destroy=function(a,b){o.detachEvents(),o.stopAutoplay(),o.params.loop&&o.destroyLoop(),b&&o.cleanupStyles(),o.disconnectObservers(),o.params.keyboardControl&&o.disableKeyboardControl&&o.disableKeyboardControl(),o.params.mousewheelControl&&o.disableMousewheelControl&&o.disableMousewheelControl(),o.params.a11y&&o.a11y&&o.a11y.destroy(),o.emit("onDestroy"),a!==!1&&(o=null)},o.init(),o}};b.prototype={defaults:{direction:"horizontal",touchEventsTarget:"container",initialSlide:0,speed:300,autoplay:!1,autoplayDisableOnInteraction:!0,freeMode:!1,freeModeMomentum:!0,freeModeMomentumRatio:1,freeModeMomentumBounce:!0,freeModeMomentumBounceRatio:1,setWrapperSize:!1,virtualTranslate:!1,effect:"slide",coverflow:{rotate:50,stretch:0,depth:100,modifier:1,slideShadows:!0},cube:{slideShadows:!0,shadow:!0,shadowOffset:20,shadowScale:.94},fade:{crossFade:!1},parallax:!1,scrollbar:null,scrollbarHide:!0,keyboardControl:!1,mousewheelControl:!1,mousewheelForceToAxis:!1,hashnav:!1,spaceBetween:0,slidesPerView:1,slidesPerColumn:1,slidesPerColumnFill:"column",slidesPerGroup:1,centeredSlides:!1,touchRatio:1,touchAngle:45,simulateTouch:!0,shortSwipes:!0,longSwipes:!0,longSwipesRatio:.5,longSwipesMs:300,followFinger:!0,onlyExternal:!1,threshold:0,touchMoveStopPropagation:!0,pagination:null,paginationClickable:!1,paginationHide:!1,paginationBulletRender:null,resistance:!0,resistanceRatio:.85,nextButton:null,prevButton:null,watchSlidesProgress:!1,watchSlidesVisibility:!1,grabCursor:!1,preventClicks:!0,preventClicksPropagation:!0,slideToClickedSlide:!1,lazyLoading:!1,lazyLoadingInPrevNext:!1,lazyLoadingOnTransitionStart:!1,preloadImages:!0,updateOnImagesReady:!0,loop:!1,loopAdditionalSlides:0,loopedSlides:null,control:void 0,controlInverse:!1,allowSwipeToPrev:!0,allowSwipeToNext:!0,swipeHandler:null,noSwiping:!0,noSwipingClass:"swiper-no-swiping",slideClass:"swiper-slide",slideActiveClass:"swiper-slide-active",slideVisibleClass:"swiper-slide-visible",slideDuplicateClass:"swiper-slide-duplicate",slideNextClass:"swiper-slide-next",slidePrevClass:"swiper-slide-prev",wrapperClass:"swiper-wrapper",bulletClass:"swiper-pagination-bullet",bulletActiveClass:"swiper-pagination-bullet-active",buttonDisabledClass:"swiper-button-disabled",paginationHiddenClass:"swiper-pagination-hidden",observer:!1,observeParents:!1,a11y:!1,prevSlideMessage:"Previous slide",nextSlideMessage:"Next slide",firstSlideMessage:"This is the first slide",lastSlideMessage:"This is the last slide",runCallbacksOnInit:!0},isSafari:function(){var a=navigator.userAgent.toLowerCase();return a.indexOf("safari")>=0&&a.indexOf("chrome")<0&&a.indexOf("android")<0}(),isUiWebView:/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(navigator.userAgent),isArray:function(a){return"[object Array]"===Object.prototype.toString.apply(a)},browser:{ie:window.navigator.pointerEnabled||window.navigator.msPointerEnabled,ieTouch:window.navigator.msPointerEnabled&&window.navigator.msMaxTouchPoints>1||window.navigator.pointerEnabled&&window.navigator.maxTouchPoints>1},device:function(){var a=navigator.userAgent,b=a.match(/(Android);?[\s\/]+([\d.]+)?/),c=a.match(/(iPad).*OS\s([\d_]+)/),d=!c&&a.match(/(iPhone\sOS)\s([\d_]+)/);return{ios:c||d||c,android:b}}(),support:{touch:window.Modernizr&&Modernizr.touch===!0||function(){return!!("ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch)}(),transforms3d:window.Modernizr&&Modernizr.csstransforms3d===!0||function(){var a=document.createElement("div").style;return"webkitPerspective"in a||"MozPerspective"in a||"OPerspective"in a||"MsPerspective"in a||"perspective"in a}(),flexbox:function(){for(var a=document.createElement("div").style,b="alignItems webkitAlignItems webkitBoxAlign msFlexAlign mozBoxAlign webkitFlexDirection msFlexDirection mozBoxDirection mozBoxOrient webkitBoxDirection webkitBoxOrient".split(" "),c=0;c<b.length;c++)if(b[c]in a)return!0}(),observer:function(){return"MutationObserver"in window||"WebkitMutationObserver"in window}()},plugins:{}},a.Swiper=b}(Zepto),+function(a){"use strict";a.Swiper.prototype.defaults.pagination=".page-current .swiper-pagination",a.swiper=function(b,c){return new a.Swiper(b,c)},a.fn.swiper=function(b){return new a.Swiper(this,b)},a.initSwiper=function(b){function c(a){function b(){a.destroy(),d.off("pageBeforeRemove",b)}d.on("pageBeforeRemove",b)}var d=a(b||document.body),e=d.find(".swiper-container");if(0!==e.length)for(var f=0;f<e.length;f++){var g,h=e.eq(f);if(h.data("swiper"))h.data("swiper").update(!0);else{g=h.dataset();var i=a.swiper(h[0],g);c(i)}}},a.reinitSwiper=function(b){var c=a(b||".page-current"),d=c.find(".swiper-container");if(0!==d.length)for(var e=0;e<d.length;e++){var f=d[0].swiper;f&&f.update(!0)}}}(Zepto),+function(a){"use strict";var b=function(b){var c,d=this,e=this.defaults;b=b||{};for(var f in e)"undefined"==typeof b[f]&&(b[f]=e[f]);d.params=b;var g=d.params.navbarTemplate||'<header class="bar bar-nav"><a class="icon icon-left pull-left photo-browser-close-link'+("popup"===d.params.type?" close-popup":"")+'"></a><h1 class="title"><div class="center sliding"><span class="photo-browser-current"></span> <span class="photo-browser-of">'+d.params.ofText+'</span> <span class="photo-browser-total"></span></div></h1></header>',h=d.params.toolbarTemplate||'<nav class="bar bar-tab"><a class="tab-item photo-browser-prev" href="#"><i class="icon icon-prev"></i></a><a class="tab-item photo-browser-next" href="#"><i class="icon icon-next"></i></a></nav>',i=d.params.template||'<div class="photo-browser photo-browser-'+d.params.theme+'">{{navbar}}{{toolbar}}<div data-page="photo-browser-slides" class="content">{{captions}}<div class="photo-browser-swiper-container swiper-container"><div class="photo-browser-swiper-wrapper swiper-wrapper">{{photos}}</div></div></div></div>',j=d.params.lazyLoading?d.params.photoLazyTemplate||'<div class="photo-browser-slide photo-browser-slide-lazy swiper-slide"><div class="preloader'+("dark"===d.params.theme?" preloader-white":"")+'"></div><span class="photo-browser-zoom-container"><img data-src="{{url}}" class="swiper-lazy"></span></div>':d.params.photoTemplate||'<div class="photo-browser-slide swiper-slide"><span class="photo-browser-zoom-container"><img src="{{url}}"></span></div>',k=d.params.captionsTheme||d.params.theme,l=d.params.captionsTemplate||'<div class="photo-browser-captions photo-browser-captions-'+k+'">{{captions}}</div>',m=d.params.captionTemplate||'<div class="photo-browser-caption" data-caption-index="{{captionIndex}}">{{caption}}</div>',n=d.params.objectTemplate||'<div class="photo-browser-slide photo-browser-object-slide swiper-slide">{{html}}</div>',o="",p="";for(c=0;c<d.params.photos.length;c++){var q=d.params.photos[c],r="";"string"==typeof q||q instanceof String?r=q.indexOf("<")>=0||q.indexOf(">")>=0?n.replace(/{{html}}/g,q):j.replace(/{{url}}/g,q):"object"==typeof q&&(q.hasOwnProperty("html")&&q.html.length>0?r=n.replace(/{{html}}/g,q.html):q.hasOwnProperty("url")&&q.url.length>0&&(r=j.replace(/{{url}}/g,q.url)),q.hasOwnProperty("caption")&&q.caption.length>0?p+=m.replace(/{{caption}}/g,q.caption).replace(/{{captionIndex}}/g,c):r=r.replace(/{{caption}}/g,"")),o+=r}var s=i.replace("{{navbar}}",d.params.navbar?g:"").replace("{{noNavbar}}",d.params.navbar?"":"no-navbar").replace("{{photos}}",o).replace("{{captions}}",l.replace(/{{captions}}/g,p)).replace("{{toolbar}}",d.params.toolbar?h:"");d.activeIndex=d.params.initialSlide,d.openIndex=d.activeIndex,d.opened=!1,d.open=function(b){return"undefined"==typeof b&&(b=d.activeIndex),b=parseInt(b,10),d.opened&&d.swiper?void d.swiper.slideTo(b):(d.opened=!0,d.openIndex=b,"standalone"===d.params.type&&a(d.params.container).append(s),"popup"===d.params.type&&(d.popup=a.popup('<div class="popup photo-browser-popup">'+s+"</div>"),a(d.popup).on("closed",d.onPopupClose)),"page"===d.params.type?(a(document).on("pageBeforeInit",d.onPageBeforeInit),a(document).on("pageBeforeRemove",d.onPageBeforeRemove),d.params.view||(d.params.view=a.mainView),void d.params.view.loadContent(s)):(d.layout(d.openIndex),void(d.params.onOpen&&d.params.onOpen(d))))},d.close=function(){d.opened=!1,d.swiperContainer&&0!==d.swiperContainer.length&&(d.params.onClose&&d.params.onClose(d),d.attachEvents(!0),"standalone"===d.params.type&&d.container.removeClass("photo-browser-in").addClass("photo-browser-out").animationEnd(function(){d.container.remove()}),d.swiper.destroy(),d.swiper=d.swiperContainer=d.swiperWrapper=d.slides=t=u=v=void 0)},d.onPopupClose=function(){d.close(),a(d.popup).off("pageBeforeInit",d.onPopupClose)},d.onPageBeforeInit=function(b){"photo-browser-slides"===b.detail.page.name&&d.layout(d.openIndex),a(document).off("pageBeforeInit",d.onPageBeforeInit)},d.onPageBeforeRemove=function(b){"photo-browser-slides"===b.detail.page.name&&d.close(),a(document).off("pageBeforeRemove",d.onPageBeforeRemove)},d.onSliderTransitionStart=function(b){d.activeIndex=b.activeIndex;var c=b.activeIndex+1,e=b.slides.length;if(d.params.loop&&(e-=2,c-=b.loopedSlides,1>c&&(c=e+c),c>e&&(c-=e)),d.container.find(".photo-browser-current").text(c),d.container.find(".photo-browser-total").text(e),a(".photo-browser-prev, .photo-browser-next").removeClass("photo-browser-link-inactive"),b.isBeginning&&!d.params.loop&&a(".photo-browser-prev").addClass("photo-browser-link-inactive"),b.isEnd&&!d.params.loop&&a(".photo-browser-next").addClass("photo-browser-link-inactive"),d.captions.length>0){d.captionsContainer.find(".photo-browser-caption-active").removeClass("photo-browser-caption-active");var f=d.params.loop?b.slides.eq(b.activeIndex).attr("data-swiper-slide-index"):d.activeIndex;d.captionsContainer.find('[data-caption-index="'+f+'"]').addClass("photo-browser-caption-active")}var g=b.slides.eq(b.previousIndex).find("video");g.length>0&&"pause"in g[0]&&g[0].pause(),d.params.onSlideChangeStart&&d.params.onSlideChangeStart(b)},d.onSliderTransitionEnd=function(a){d.params.zoom&&t&&a.previousIndex!==a.activeIndex&&(u.transform("translate3d(0,0,0) scale(1)"),v.transform("translate3d(0,0,0)"),t=u=v=void 0,w=x=1),d.params.onSlideChangeEnd&&d.params.onSlideChangeEnd(a)},d.layout=function(b){"page"===d.params.type?d.container=a(".photo-browser-swiper-container").parents(".view"):d.container=a(".photo-browser"),"standalone"===d.params.type&&d.container.addClass("photo-browser-in"),d.swiperContainer=d.container.find(".photo-browser-swiper-container"),d.swiperWrapper=d.container.find(".photo-browser-swiper-wrapper"),d.slides=d.container.find(".photo-browser-slide"),d.captionsContainer=d.container.find(".photo-browser-captions"),d.captions=d.container.find(".photo-browser-caption");var c={nextButton:d.params.nextButton||".photo-browser-next",prevButton:d.params.prevButton||".photo-browser-prev",indexButton:d.params.indexButton,initialSlide:b,spaceBetween:d.params.spaceBetween,speed:d.params.speed,loop:d.params.loop,lazyLoading:d.params.lazyLoading,lazyLoadingInPrevNext:d.params.lazyLoadingInPrevNext,lazyLoadingOnTransitionStart:d.params.lazyLoadingOnTransitionStart,preloadImages:d.params.lazyLoading?!1:!0,onTap:function(a,b){d.params.onTap&&d.params.onTap(a,b)},onClick:function(a,b){d.params.exposition&&d.toggleExposition(),d.params.onClick&&d.params.onClick(a,b)},onDoubleTap:function(b,c){d.toggleZoom(a(c.target).parents(".photo-browser-slide")),d.params.onDoubleTap&&d.params.onDoubleTap(b,c)},onTransitionStart:function(a){d.onSliderTransitionStart(a)},onTransitionEnd:function(a){d.onSliderTransitionEnd(a)},onLazyImageLoad:function(a,b,c){d.params.onLazyImageLoad&&d.params.onLazyImageLoad(d,b,c)},onLazyImageReady:function(b,c,e){a(c).removeClass("photo-browser-slide-lazy"),d.params.onLazyImageReady&&d.params.onLazyImageReady(d,c,e)}};d.params.swipeToClose&&"page"!==d.params.type&&(c.onTouchStart=d.swipeCloseTouchStart,c.onTouchMoveOpposite=d.swipeCloseTouchMove,c.onTouchEnd=d.swipeCloseTouchEnd),d.swiper=a.swiper(d.swiperContainer,c),0===b&&d.onSliderTransitionStart(d.swiper),d.attachEvents()},d.attachEvents=function(a){var b=a?"off":"on";if(d.params.zoom){var c=d.params.loop?d.swiper.slides:d.slides;c[b]("gesturestart",d.onSlideGestureStart),c[b]("gesturechange",d.onSlideGestureChange),c[b]("gestureend",d.onSlideGestureEnd),c[b]("touchstart",d.onSlideTouchStart),c[b]("touchmove",d.onSlideTouchMove),c[b]("touchend",d.onSlideTouchEnd)}d.container.find(".photo-browser-close-link")[b]("click",d.close)},d.exposed=!1,d.toggleExposition=function(){d.container&&d.container.toggleClass("photo-browser-exposed"),d.params.expositionHideCaptions&&d.captionsContainer.toggleClass("photo-browser-captions-exposed"),d.exposed=!d.exposed},d.enableExposition=function(){d.container&&d.container.addClass("photo-browser-exposed"),d.params.expositionHideCaptions&&d.captionsContainer.addClass("photo-browser-captions-exposed"),d.exposed=!0},d.disableExposition=function(){d.container&&d.container.removeClass("photo-browser-exposed"),d.params.expositionHideCaptions&&d.captionsContainer.removeClass("photo-browser-captions-exposed"),d.exposed=!1};var t,u,v,w=1,x=1,y=!1;d.onSlideGestureStart=function(){return t||(t=a(this),u=t.find("img, svg, canvas"),v=u.parent(".photo-browser-zoom-container"),0!==v.length)?(u.transition(0),void(y=!0)):void(u=void 0)},d.onSlideGestureChange=function(a){u&&0!==u.length&&(w=a.scale*x,w>d.params.maxZoom&&(w=d.params.maxZoom-1+Math.pow(w-d.params.maxZoom+1,.5)),w<d.params.minZoom&&(w=d.params.minZoom+1-Math.pow(d.params.minZoom-w+1,.5)),u.transform("translate3d(0,0,0) scale("+w+")"))},d.onSlideGestureEnd=function(){u&&0!==u.length&&(w=Math.max(Math.min(w,d.params.maxZoom),d.params.minZoom),u.transition(d.params.speed).transform("translate3d(0,0,0) scale("+w+")"),x=w,y=!1,1===w&&(t=void 0))},d.toggleZoom=function(){t||(t=d.swiper.slides.eq(d.swiper.activeIndex),u=t.find("img, svg, canvas"),v=u.parent(".photo-browser-zoom-container")),u&&0!==u.length&&(v.transition(300).transform("translate3d(0,0,0)"),w&&1!==w?(w=x=1,u.transition(300).transform("translate3d(0,0,0) scale(1)"),t=void 0):(w=x=d.params.maxZoom,u.transition(300).transform("translate3d(0,0,0) scale("+w+")")))};var z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q={},R={};d.onSlideTouchStart=function(b){u&&0!==u.length&&(z||("android"===a.device.os&&b.preventDefault(),z=!0,Q.x="touchstart"===b.type?b.targetTouches[0].pageX:b.pageX,Q.y="touchstart"===b.type?b.targetTouches[0].pageY:b.pageY))},d.onSlideTouchMove=function(b){if(u&&0!==u.length&&(d.swiper.allowClick=!1,z&&t)){A||(H=u[0].offsetWidth,I=u[0].offsetHeight,J=a.getTranslate(v[0],"x")||0,K=a.getTranslate(v[0],"y")||0,v.transition(0));var c=H*w,e=I*w;if(!(c<d.swiper.width&&e<d.swiper.height)){if(D=Math.min(d.swiper.width/2-c/2,0),F=-D,E=Math.min(d.swiper.height/2-e/2,0),G=-E,R.x="touchmove"===b.type?b.targetTouches[0].pageX:b.pageX,R.y="touchmove"===b.type?b.targetTouches[0].pageY:b.pageY,!A&&!y&&(Math.floor(D)===Math.floor(J)&&R.x<Q.x||Math.floor(F)===Math.floor(J)&&R.x>Q.x))return void(z=!1);b.preventDefault(),b.stopPropagation(),A=!0,B=R.x-Q.x+J,C=R.y-Q.y+K,D>B&&(B=D+1-Math.pow(D-B+1,.8)),B>F&&(B=F-1+Math.pow(B-F+1,.8)),E>C&&(C=E+1-Math.pow(E-C+1,.8)),C>G&&(C=G-1+Math.pow(C-G+1,.8)),L||(L=R.x),O||(O=R.y),M||(M=Date.now()),N=(R.x-L)/(Date.now()-M)/2,P=(R.y-O)/(Date.now()-M)/2,Math.abs(R.x-L)<2&&(N=0),Math.abs(R.y-O)<2&&(P=0),L=R.x,O=R.y,M=Date.now(),v.transform("translate3d("+B+"px, "+C+"px,0)")}}},d.onSlideTouchEnd=function(){if(u&&0!==u.length){if(!z||!A)return z=!1,void(A=!1);z=!1,A=!1;var a=300,b=300,c=N*a,e=B+c,f=P*b,g=C+f;0!==N&&(a=Math.abs((e-B)/N)),0!==P&&(b=Math.abs((g-C)/P));var h=Math.max(a,b);B=e,C=g;var i=H*w,j=I*w;D=Math.min(d.swiper.width/2-i/2,0),F=-D,E=Math.min(d.swiper.height/2-j/2,0),G=-E,B=Math.max(Math.min(B,F),D),C=Math.max(Math.min(C,G),E),v.transition(h).transform("translate3d("+B+"px, "+C+"px,0)")}};var S,T,U,V,W,X=!1,Y=!0,Z=!1;return d.swipeCloseTouchStart=function(){Y&&(X=!0)},d.swipeCloseTouchMove=function(a,b){if(X){Z||(Z=!0,T="touchmove"===b.type?b.targetTouches[0].pageY:b.pageY,V=d.swiper.slides.eq(d.swiper.activeIndex),W=(new Date).getTime()),b.preventDefault(),U="touchmove"===b.type?b.targetTouches[0].pageY:b.pageY,S=T-U;var c=1-Math.abs(S)/300;V.transform("translate3d(0,"+-S+"px,0)"),d.swiper.container.css("opacity",c).transition(0)}},d.swipeCloseTouchEnd=function(){if(X=!1,!Z)return void(Z=!1);Z=!1,Y=!1;var b=Math.abs(S),c=(new Date).getTime()-W;return 300>c&&b>20||c>=300&&b>100?void setTimeout(function(){"standalone"===d.params.type&&d.close(),"popup"===d.params.type&&a.closeModal(d.popup),d.params.onSwipeToClose&&d.params.onSwipeToClose(d),Y=!0},0):(0!==b?V.addClass("transitioning").transitionEnd(function(){Y=!0,V.removeClass("transitioning")}):Y=!0,d.swiper.container.css("opacity","").transition(""),void V.transform(""))},d};b.prototype={defaults:{photos:[],container:"body",initialSlide:0,spaceBetween:20,speed:300,zoom:!0,maxZoom:3,minZoom:1,exposition:!0,expositionHideCaptions:!1,type:"standalone",navbar:!0,toolbar:!0,theme:"light",swipeToClose:!0,backLinkText:"Close",ofText:"of",loop:!1,lazyLoading:!1,lazyLoadingInPrevNext:!1,lazyLoadingOnTransitionStart:!1}},a.photoBrowser=function(c){return a.extend(c,a.photoBrowser.prototype.defaults),new b(c)},a.photoBrowser.prototype={defaults:{}}}(Zepto);
/*
 * 重新定义   时间日期选择器  
 * 函数 名称   datetimePickers 
 * 时间格式   yyyy-mm-dd
 */

+ function($) {
  "use strict";

  var today = new Date();

  var getDays = function(max) {
    var days = [];
    for(var i=1; i<= (max||31);i++) {
      days.push(i < 10 ? "0"+i : i);
    }
    return days;
  };

  var getDaysByMonthAndYear = function(month, year) {
    var int_d = new Date(year, parseInt(month)+1-1, 1);
    var d = new Date(int_d - 1);
    return getDays(d.getDate());
  };

  var formatNumber = function (n) {
    return n < 10 ? "0" + n : n;
  };

  var initMonthes = ('01 02 03 04 05 06 07 08 09 10 11 12').split(' ');

  var initYears = (function () {
    var arr = [];
    for (var i = 1950; i <= 2030; i++) { arr.push(i); }
    return arr;
  })();


  var defaults = {

    rotateEffect: false,  //为了性能

	value: [today.getFullYear(), formatNumber(today.getMonth()+1), today.getDate()],

    onChange: function (picker, values, displayValues) {
      var days = getDaysByMonthAndYear(picker.cols[1].value, picker.cols[0].value);
      var currentValue = picker.cols[2].value;
      if(currentValue > days.length) currentValue = days.length;
      picker.cols[2].setValue(currentValue);
    },

    formatValue: function (p, values, displayValues) {
	  return displayValues[0] + '-' + values[1] + '-' + values[2];
    },

    cols: [
      // Years
      {
        values: initYears
      },
      // Months
      {
        values: initMonthes
      },
      // Days
      {
        values: getDays()
      },

      // Space divider
      {
        divider: true,
        content: '  '
      },
    ]
  };
   
  $.fn.datetimePickers = function(params) {
    return this.each(function() {
      if(!this) return;
      var p = $.extend(defaults, params);
      $(this).picker(p);
    });
  };
}(Zepto);

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
 //个人验证身份证号码
 function IdentityCodeValid(code) { 
	var code=code;
	var reg1=/(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})/;
	var reg2=/(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X|x)/;
	if((code!='')&&reg1.test(code)||(code!='')&&reg2.test(code)){
		return true
	}else{
		return false;
	}
}
//切换地区
$(document).ready(function(){	
	$("select[name='province']").bind("change",function(){
		load_city();
	});
});
	
function load_city()
{
	var id = $("select[name='province']").find('option').not(function() {return !this.selected}).attr("rel");
	var evalStr="regionConf.r"+id+".c";
	if(id==0)
	{
		var html = "<option value=''>请选择城市</option>";
	}
	else
	{
		var regionConfs=eval(evalStr);
		evalStr+=".";
		var html = "<option value=''>请选择城市</option>";
		for(var key in regionConfs)
		{
			html+="<option value='"+eval(evalStr+key+".n")+"' rel='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
		}
	}
	$("select[name='city']").html(html);
}
// 筛选分类 
function J_mall_cate(){
	var hideList_height = $(document).height();
	$(".hide_list").css("height",hideList_height+"px");
	
	$(".mall-cate>li").each(function(index){
		var $this = $(this);
		$this.on({
			click:function(e){
				e.stopPropagation();
				// 初始化
				$(".abbr").removeClass("webkit-box");
				$(".hide_list").hide()
				$("#second_list>ul").hide();
				if(!($this.hasClass("cur"))){
					$this.addClass("cur").siblings().removeClass("cur");
					$(".hide_list").show().find(".abbr").eq(index).addClass("webkit-box").find("#second_list>ul").eq(index).show();
					$("#first_list li").each(function(index){
						var $this = $(this);
						$this.click(function(e){
							e.stopPropagation();
							$(".second_list>ul").hide();
							$this.addClass("select").siblings().removeClass("select");
							$(".second_list>ul").eq(index).show();
						})
					})
				}
				else{
					$this.removeClass("cur");
					$(".abbr").eq(index).removeClass("webkit-box");
				}
			} ,
			change:function(){
				
			}
		});
	});
	$(".abbr").on("click",function(e){
		e.stopPropagation();
	});
	$(document).click(function(){
		$(".mall-cate>li").removeClass("cur");
		$(".abbr").removeClass("webkit-box");
		$(".hide_list").hide();
		$("#second_list>ul").hide();
	});
}
// 获取会员所有项目列表
function ajax_get_recommend_project(obj){
	if($(obj).attr("rel") == user_info_id){
		$.showErr("不能给自己推荐！");
		return false;
	}
	var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=ajax_get_recommend_project';
	var query=new Object();
	//推荐人id
	query.id = user_info_id;
	//被推荐人id
	query.user_id=$(obj).attr("rel");
	$.ajax({
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success:function(ajaxobj){
			if(ajaxobj.status==0){
				show_login();
				return false;
			}
			if(ajaxobj.status==1){
				$.showErr(ajaxobj.info);
				return false;
			}
			if(ajaxobj.status==2){
	    		$.modal({
					title: '自荐我的项目',
			      	text: ajaxobj.html,
			      	buttons: []
				});
				page_style();
				ajax_recommend_save();
				return false;
			}
		}
	});
}
function page_style(){
	//筛选自荐项目
	$(".J_check").on('click',function(){
		var rel=$(this).attr("rel");
		$(".J_check").removeClass("ui_checked");
		$(".J_check").find("input[name='project_recommend']").removeAttr("checked");
		$(".J_check").find(".inf").removeClass("theme_fcolor");
		$(this).addClass("ui_checked");
		$(this).find("input[name='project_recommend']").attr("checked","checked");
		$(this).find(".inf").addClass("theme_fcolor");
	});

	if($(".project_list").find("li").length <= 4){
		$(".project_list").css("height","auto");
	}
	$(".button_n").click(function(){
		$.closeModal();
	});
}

function ajax_recommend_save(){
	$(".button_y").bind("click",function(){
		if($("input[name='project_recommend']:checked").length==0){
			$.toast("请选择推荐项目");
			return false;
		}
		if($("#memo").val()==''){
			$.toast("推荐理由不能为空！");
			return false;
		}
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=ajax_recommend_save";
		var deal_image=$("input[name='project_recommend']:checked").attr("rel3");
		var deal_name=$("input[name='project_recommend']:checked").attr("rel2");
		var deal_type=$("input[name='project_recommend']:checked").attr("rel");
		var deal_id=$("input[name='project_recommend']:checked").val();
		var memo=$("textarea[name='memo']").val();
		var recommend_user_id=$("#recommend_user_id").val();
		var user_id=$("#user_id").val();
		var query=new Object();
		query.deal_id=deal_id;
		query.memo=memo;
		query.recommend_user_id=recommend_user_id;
		query.user_id=user_id;
		query.deal_type=deal_type;
		query.deal_name=deal_name;
		query.deal_image=deal_image;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==0){
					$.toast(ajaxobj.info);
					return false;
				}
				if(ajaxobj.status==1){
					$.closeModal();
					$.toast(ajaxobj.info);
				}
			}
		});
	});
	return false;
}
function count_invest_money(invote_mini_money){
	$("input[name='money']").val(invote_mini_money);
	$("#money").html(invote_mini_money);
	//minus 减
	$("#minus").bind('click',function(){
		var money = invote_mini_money;
		var num=parseInt($("#buy_num").val());
		if(num <=1)
			num=1;
		else
		{
			num -=1;
		}
		$("#buy_num").val(num);
		account_money(num,invote_mini_money);
	});
	
	//plus 加
	$("#plus").bind('click',function(){
		var money = invote_mini_money;
		var num=parseInt($("#buy_num").val());
		if(num < total_num){
			if(num <1)
				num=1;
			else
			{
				num=num+1;
			}
		}
		else{
			$.toast("投资份数不能超过"+total_num+"份",1000);
		}
		$("#buy_num").val(num);
	
		account_money(num,invote_mini_money);
	});
	$("input[name='num']").bind({
		keyup:function(){
			var u_num = $(this).val();
			$(this).val(u_num.replace(/[^0-9]/g,''));
			u_num = $(this).val();
			if(u_num > total_num){
				$.toast("投资份数不能超过"+total_num+"份",1000);
				$(this).val(1);
				$("input[name='money']").val(invote_mini_money);
				$("#money").html(invote_mini_money);
				return false;
			}
			account_money(u_num,invote_mini_money);
		},
		blur:function(){
			var u_num = $(this).val();
			if(u_num == ''){
				$(this).val(1);
				u_num = 1;
			}
			account_money(u_num,invote_mini_money);
		}
	});
}
// 统计投资金额
function account_money(num,invote_mini_money){
	money = (parseFloat(num*invote_mini_money)).toFixed(2);;
	$("input[name='money']").val(money);
	$("#money").html(money);
}
$(document).on("pageInit","#deal-show", function(e, pageId, $page) {
	// 关注、取消关注 
	bind_attention_focus();
	
	$(".lottery_do_num").on('click',function(){
		 var item_id=$(this).attr("item_id");
		 var item_price=$(this).attr("item_price");
		 lottery_pop(item_id,item_price);
	});
	
	$(".dedication_do").on('click',function(){
		var item_id=$(this).attr('data_id');
		dedicate_pop(item_id);
	});
	$(".J_view_detail").on('click',function(){
		view_detail(this,"#deal_info_box");
	});
	$(".J_close_detail").on('click',function(){
		close_detail(".J_view_detail","#deal_info_box");
	});

 	// 查看更多回报
    $(".view_more_return_item").find(".item-link").on('click',function(){
      	$(".return_item").addClass("return_more_item");
      	$(".view_more_return_item").remove();
      	$.refreshScroller();
    });

    //抽奖
	function lottery_pop(deal_item_id,price){
		$.ajax({
			url:APP_ROOT+'/index.php?ctl=ajax&act=go_lottery_num&item_id='+deal_item_id,
			type:"GET",
			data:'',
			dataType:'json',
			success:function(o){
				if(o.status ==-1){
					$.showErr("请先登录",function(){
						var href=APP_ROOT+'/index.php?ctl=user&act=login&deal_id='+deal_info;
						$.router.loadPage(href);
					});
				}
				else if(o.status ==1)
				{
					$.modal({
						'title':'',
						'text':o.html,
						'buttons':[]
					});
				}
				else{
					$.showErr(o.info);
				}	
			}
		});
	}
	
	// 无私奉献
	var dedicate_demo=$("#dedicate_demo").html();
	function dedicate_pop(item_id){
		var dedicate_demo_1=dedicate_demo;
		dedicate_demo_1=dedicate_demo_1.replace('item_id',item_id);
		dedicate_demo_1=dedicate_demo_1.replace('ajax_form_dedicate',"ajax_form_dedicate_1");
		$.modal({
			'title':'无私奉献',
			'text':dedicate_demo_1,
			'button':[]
		});
		bind_ajax_form_dedicate(".ajax_form_dedicate_1");
	}

	function bind_ajax_form_dedicate(str)
	{
		$(str).find(".ui-button").bind("click",function(){
			var $obj=$(this);
			var $dedicate_form=$obj.parent().parent(str);
			var $dedicate_dedicate_money=$dedicate_form.find("input[name='pay_money']");
			if((isNaN($dedicate_dedicate_money.val()) || parseFloat($dedicate_dedicate_money.val())<=0) || $dedicate_dedicate_money.val()==''){
				$.toast("请输入正确的金额",1000);
				return false;
			}
			$(str).submit();
		});
	}

	/** 显隐详情
	 * @param {Object} obj  当前对象
	 * @param {Object} detail 要展示的内容
	 * @param {Object} btn_view_detail 点击查看详情的触发对象
	 */
	function view_detail(obj,detail){
		$(obj).hide();
		$(detail).show();
	}
	function close_detail(btn_view_detail,detail){
		$(detail).hide();
		$(btn_view_detail).show();
	}
	$(".J_open_share").on('click',function(){
		if(is_sdk>0){
			App.sdk_share('{"share_content":"'+deal_info_brief+'","share_imageUrl":"'+deal_info_image+'","share_url":"'+deal_info_url+'"}');
		}
		else{
			$(".open_share_box").toggle();	
		}
	});
	window._bd_share_config={
		"common":{
			"bdSnsKey":{},
			"bdText":deal_info_name,
			"bdDesc":deal_info_brief,
			"bdPic":deal_info_image,
			"bdMini":"1",
			"bdMiniList":false,
			"bdStyle":"1",
			"bdSize":"32"
		},
		"share":{},
		"selectShare":{
			"bdContainerClass":null,
			"bdSelectMiniList":["weixin","sqq","tsina","mail"]
		}
	};
	with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
});
$(document).on("pageInit","#deal-show", function(e, pageId, $page) {
	// 领投请求
	ajax_applicate_leader();

	// 跟投请求  IS_ENQUIRY:询价是否开启
	if(IS_ENQUIRY == 0){
		enquiry_money_first(".invest_btn_box");
	}
	if(IS_ENQUIRY == 1){
		ajax_continue_investor();
	}

	// 领投请求
	function ajax_applicate_leader(){
		$("#applicate_leader").bind("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href=APP_ROOT+"/index.php?ctl=user&act=login";
				$.alert("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl=APP_ROOT+"/index.php?ctl=investor&act=leader_ajax&deal_id="+deal_info_id;
			var leader_ajax=$("#leader_ajax").val();
			var query =new Object();
			query.leader_ajax=leader_ajax;
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				data:query,
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.url!=''){
								$.router.loadPage(ajaxobj.url);
							}
							
						});
					} 
					
					if(ajaxobj.status==2){
						//领投申请不通过
						$.closeModal();
						$.confirm(ajaxobj.info,function(){
							if(ajaxobj.url!=''){
								$.router.loadPage(ajaxobj.url);
							}
						});
					}
					if(ajaxobj.status==1){
				    	$.modal({
							title: '领投投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						add_investment_money();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
				    	$.modal({
							title: '追加投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						add_investment_money();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==6){
						//领投申请不通过
						$.confirm(ajaxobj.info,function(){
							$.router.loadPage(ajaxobj.url);
						});
					}
					if(ajaxobj.status==3){
						//支付诚意金
						var href=APP_ROOT+"/index.php?ctl=account&act=mortgate_pay";
						$.router.loadPage(href);
					}
					if(ajaxobj.status==7){
						//已经“领投”,无法再跟投
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==5){
						//投资不通过,资金无法再次追加了！
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==8){
						//项目已经结束无法投资！
						$.showErr(ajaxobj.info);
						return false;
					}
					if(ajaxobj.status==9){
						//投资者认证未通过！
						$.showErr(ajaxobj.info,function(){
							var href=APP_ROOT+"/index.php?ctl=investor&act=index";
							$.router.loadPage(href);
						});
						return false;
					}
				}
			});
			return false;
		});
	}

	// ajax删除“领投”，但是未审核的数据
	function delete_leader_investor(){
		$.showIndicator();
		var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=delete_leader_investor&deal_id="+deal_info_id;
		var leader_ajax=$("#leader_ajax").val();
		var query =new Object();
		query.leader_ajax=leader_ajax;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			data:query,
			success:function(ajaxobj){
				$.hideIndicator();
				if(ajaxobj.status==1){
					//"领投申请"取消成功
					$.toast(ajaxobj.info,1000);
					setTimeout(
						function(){
							$.router.loadPage(window.location.href);
						}
					, 1000);
				}
				if(ajaxobj.status==0){
					//删除失败
					$.toast(ajaxobj.info,1000);
				}
			}
		});
	}

	// 领投人详细资料
	function leader_detailed_information(){
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=leader_detailed_information&id="+leader_info_id;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1){
					$.alert(ajaxobj.html);
				}
			    if(ajaxobj.status==2){
					$.toast(ajaxobj.info);
				}
			}
		});
	}

	// 跟投请求(询价未开启 IS_ENQUIRY=0)
	function enquiry_money_first(obj){
		var $btn_box = $(obj);
		$btn_box.find(".btn_enquiry_money").on("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href=APP_ROOT+"/index.php?ctl=user&act=login&deal_id="+deal_info_id;
				$.showErr("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=ajax_continue_investor&deal_id="+deal_info_id;
			var query = new Object();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 投资成功！
						$.toast(ajaxobj.info,1000);
						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					if(ajaxobj.status==0){
						$.alert(ajaxobj.info,function(){
							if(ajaxobj.url){
								href=ajaxobj.url;
								$.router.loadPage(href);
							}
						});
					}
					if(ajaxobj.status==2){
						//调取第一次跟投页面
			    		$.modal({
							title: '项目投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
						//调取后续追加跟投页面
			    		$.modal({
							title: '项目追加投资',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					
					if(ajaxobj.status==5){
						//无法再次跟投追加金额
						$.alert(ajaxobj.info);
					}
					if(ajaxobj.status==8){
						//您已为领投人,无需再进行跟投！
						$.alert(ajaxobj.info);
					}
					if(ajaxobj.status==7){
						$.closeModal();
						//已经申请“领投”，但是未审核
						$.confirm("您确定要取消,领投申请吗？",function(){
							delete_leader_investor();
						});
					}
				}
				
			});
			return false;
		});
		$btn_box.find(".button_n").bind("click",function(){
			$.closeModal();
		});
	}

	// 跟投请求(询价已开启 IS_ENQUIRY=1)
	function ajax_continue_investor(){
		$("#continue_investor").bind("click",function(){
			$.showIndicator();
			if(login_id==''){
				var href = APP_ROOT+"/index.php?ctl=user&act=login";
				$.alert("请先登录",function(){
					$.router.loadPage(href);
				});
				return false;
			}
			var ajaxurl = APP_ROOT+"/index.php?ctl=investor&act=ajax_continue_investor&deal_id="+deal_info_id;
			var leader_ajax=$("#continue_ajax").val();
			var query =new Object();
			query.leader_ajax=leader_ajax;
				$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				data:query,
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						//用户未交纳诚意金
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.url){
								href=ajaxobj.url;
								$.router.loadPage(href);
							}
							
						});
						return false;
					}
					if(ajaxobj.status==1){
						//进入询价页面
						$.modal({
							title: '项目跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_page();
						count_invest_money(invote_mini_money);
					}
					return false;
 				}
			});
			return false;
		});
	}

	// 进入询价页面
	function enquiry_page(){
		$("#enquiry_index .button_y").bind("click",function(){
			$.showIndicator();
			var ajaxurl=$("#enquiry_index").attr("action");
			var query=$("#enquiry_index").serialize();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 项目询价
						$.closeModal();
			    		$.modal({
							title: '项目询价',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==2){
						//调取第一次跟投页面
						$.closeModal();
			    		$.modal({
							title: '项目跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==4){
						//调取后续追加跟投页面
						$.closeModal();
			    		$.modal({
							title: '项目追加跟投',
					      	text: ajaxobj.html,
					      	buttons: []
						});
						enquiry_money_save();
						count_invest_money(invote_mini_money);
					}
					if(ajaxobj.status==3){
						//(次数大于0,不能再次询价)
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==5){
						//无法再次跟投追加金额
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==8){
						//您已为领投人,无需再进行跟投！
						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==7){
						$.closeModal();
						//已经申请“领投”，但是未审核
						$.confirm("您确定要取消,领投申请吗？",function(){
							delete_leader_investor();
						});
					}
				}
			});
		});
	}

	//询价信息入库
	function enquiry_save(){
		$("#enquiry_two .button_y").bind("click",function(){
			if($("#stock_value").val()==''){
				$.toast("项目估值不能为空");
				return false;
			}
			if((isNaN($(".stock_value").val())||parseFloat($(".stock_value").val())<=0)||$(".stock_value").val()=='')
			{
				$.toast("请输入正确的估值金额");
				return false;
			}
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.toast("请输入正确的投资金额");
				return false;
			}
			if($("#investment_reason").val()==''){
				$.toast("投资理由不能为空");
				return false;
			}
			if($("#funding_to_help").val()==''){
				$.toast("资金帮助不能为空");
				return false;
			}
			var ajaxurl = $("#enquiry_two").attr("action");
			var query = $("#enquiry_two").serialize();
			$.showIndicator();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
						$.toast(ajaxobj.info,1000);
						return false;
					}
					if(ajaxobj.status==1){
	                    $.closeModal();
						$.toast(ajaxobj.info,1000);
						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					 
				}
			});
		});
	}

	// 确定追加跟投处理
	function enquiry_money_save(){
		$("#add_enquiry_money .button_y").bind("click",function(){
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.toast("请输入正确的投资金额");
				return false;
			}
			var ajaxurl = $("#add_enquiry_money").attr("action");
			var query = $("#add_enquiry_money").serialize();
			$.showIndicator();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==0){
 						$.toast(ajaxobj.info,1000);
					}
					if(ajaxobj.status==1){
						// 追加投资成功
 						$.closeModal();
 						$.toast(ajaxobj.info,1000);
 						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
				}
			});
		});
		return false;
	}

	// 确定领投资投资处理
	function add_investment_money(){
		$("#add_append_form .button_y").bind("click",function(){
			$.showIndicator();
			if((isNaN($("input[name='money']").val())||parseFloat($("input[name='money']").val())<=0)||$("input[name='money']").val()=='')
			{
				$.showErr("请输入正确的投资金额");
				return false;
			}
			var ajaxurl = $("#add_append_form").attr("action");
			var query = $("#add_append_form").serialize();
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
					$.hideIndicator();
					if(ajaxobj.status==1){
						// 追加投资成功
 						$.closeModal();
 						$.toast(ajaxobj.info,1000);
 						setTimeout(
							function(){
								$.router.loadPage(window.location.href);
							}
						, 1000);
					}
					if(ajaxobj.status==0){
						$.toast(ajaxobj.info,1000);
					}
	
				}
			});
		});
		return false;
	}
});
$(document).on("pageInit","#account-add_leader_info", function(e, pageId, $page) {
	get_file_fun(1);
	leader_info_save();

	function leader_info_save(){
		$(".button_leader_submit").on("click",function(){
			if($("#leader_help").val()==''){
				$.showErr("其它帮助不能为空！");
				return false;
			}
			if($("#leader_for_team").val()==''){
				$.showErr("团队评价不能为空！");
				return false;
			}
			if($("#leader_for_project").val()==''){
				$.showErr("项目评价不能为空！");
				return false;
			}				
			var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=leader_info_save';
			var id=$("#leader_info_id").val();
			var leader_help=$("#leader_help").val();
			var leader_for_team=$("#leader_for_team").val();
			var leader_for_project=$("#leader_for_project").val();
			var leader_moban=$("#attach_1_url").val();
			var query = new Object();
			query.id=id;
			query.leader_help=leader_help;
			query.leader_for_team=leader_for_team;
			query.leader_for_project=leader_for_project;
			query.leader_moban=leader_moban;
			$.ajax({
				url: ajaxurl,
				data:query,
				type: "POST",
				dataType: "json",
				success:function(data){
					if(data.status==0){
						$.showErr(data.info);
						return false;
					}
					if(data.status==2){
						$.showErr(data.info);
						return false;
					}
					if(data.status==1){
						$.showSuccess(data.info,function(){
							$.router.loadPage(window.location.href);
						});
					}
				}
			});
		});		
		return false;
	}
});
$(document).on("pageInit","#account-focus", function(e, pageId, $page) { 
    // 取消关注项目
    $(".J_cancel_focus").on("click",function(){
        var focus_id = $(this).attr('rel');
        var ajaxurl = APP_ROOT+'/index.php?ctl=account&act=del_focus&id='+focus_id;
        var query = new Object();
        query.ajax = 1;
        $.confirm("确定要取消关注此项目吗？",function(){
            $.ajax({ 
                url: ajaxurl,
                dataType: "json",
                data:query,
                type: "POST",
                success: function(ajaxobj){
                    if(ajaxobj.status==1)
                    {                       
                        $.closeModal();
                        if(ajaxobj.info!=""){
                            $.alert(ajaxobj.info,function(){
                                $("#focus_item_"+focus_id).remove();
                            });
                        }
                    }
                    else
                    {
                        $.closeModal();
                        if(ajaxobj.info!="")
                        {
                            $.toast(ajaxobj.info,1000); 
                        }                         
                    }
                },
                error:function(ajaxobj)
                {
                    // if(ajaxobj.responseText!='')
                    // alert(ajaxobj.responseText);
                }
            });
        
        });
        return false;
    });
});
$(document).on("pageInit","#account-index", function(e, pageId, $page) {
    $(".del_deal").on("click",function(){
        var ajaxurl = $(this).attr("href");
        $.confirm("确定删除该记录吗？",function(){
            var query = new Object();
            query.ajax = 1;
            $.ajax({ 
                url: ajaxurl,
                dataType: "json",
                data:query,
                type: "POST",
                success: function(ajaxobj){
                    if(ajaxobj.status==1)
                    {
                        if(ajaxobj.info!="")
                        {
                            $.showSuccess(ajaxobj.info,function(){
                                if(ajaxobj.jump!="")
                                {
                                    href = ajaxobj.jump;
									$.router.loadPage(href);
                                }
                            }); 
                        }
                        else
                        {
                            if(ajaxobj.jump!="")
                            {
                                href = ajaxobj.jump;
								$.router.loadPage(href);
                            }
                        }
                    }
                    else
                    {
                        if(ajaxobj.info!="")
                        {
                            $.showErr(ajaxobj.info,function(){
                                if(ajaxobj.jump!="")
                                {
                                    href = ajaxobj.jump;
									$.router.loadPage(href);
                                }
                            }); 
                        }
                        else
                        {
                            if(ajaxobj.jump!="")
                            {
                                href = ajaxobj.jump;
								$.router.loadPage(href);
                            }
                        }                           
                    }
                },
                error:function(ajaxobj)
                {
                    if(ajaxobj.responseText!='')
                    alert(ajaxobj.responseText);
                }
            });
            
        });
        return false;
    });
});
$(document).on("pageInit","#cart-index", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(left_money>=need_money){
			$("input[name='credit']").val(need_money);
			$("input[name='payment']").attr("disabled",true);
			count_total_money(need_money,0,0,need_money);
		}else{
			$("input[name='credit']").val(left_money);
			count_total_money(left_money,0,0,need_money);
		}

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-view_order", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(order_sm.credit_pay >0)
		{
			var pay_money_c = order_sm.credit_pay;
			$("input[name='credit']").val(order_sm.credit_pay);
			
		}
		else if(left_money >= need_money-order_sm.score_money){
			var money_pay=need_money-order_sm.score_money;
				money_pay=round2(money_pay,2);
			var pay_money_c = money_pay;
			
			$("input[name='credit']").val(money_pay);
			$("input[name='payment']").attr("disabled",true);
		}
		else{
			var pay_money_c = left_money;
			$("input[name='credit']").val(left_money);
		}
		
		if(order_sm.score>0)
		{
			$("input[name='score_check']").attr("checked","checked");
			$("input[name='pay_score']").val(order_sm.score);
			$("#score_trade_money").html("¥"+order_sm.score_money);
		}
		count_total_money(pay_money_c,order_sm.score,order_sm.score_money,need_money);

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-record_pay", function(e, pageId, $page) {
	// 选择银行列表
	choose_bank();
	bind_pay_form();
});
$(document).on("pageInit","#stock_transfer-go_transfer", function(e, pageId, $page) {
	if(ips_bill_no == '' || !is_tg){
		// 选择银行列表
		choose_bank();

		if(order_sm.credit_pay >0)
		{
			var pay_money_c = order_sm.credit_pay;
			$("input[name='credit']").val(order_sm.credit_pay);
			
		}
		else if(left_money >= need_money-order_sm.score_money){
			var money_pay=need_money-order_sm.score_money;
				money_pay=round2(money_pay,2);
			var pay_money_c = money_pay;
			
			$("input[name='credit']").val(money_pay);
			$("input[name='payment']").attr("disabled",true);
		}
		else{
			var pay_money_c = left_money;
			$("input[name='credit']").val(left_money);
		}
		
		if(order_sm.score>0)
		{
			$("input[name='score_check']").attr("checked","checked");
			$("input[name='pay_score']").val(order_sm.score);
			$("#score_trade_money").html("¥"+order_sm.score_money);
		}
		count_total_money(pay_money_c,order_sm.score,order_sm.score_money,need_money);

		bind_money();
		bind_pay_form();
	}
	else{
		bind_pay_tg_form();
	}
});
$(document).on("pageInit","#account-mortgate_pay", function(e, pageId, $page) {
	bind_pay_tg_form();
});
// 选择银行列表
function choose_bank(){
	$(".pay_way_bank_list li").on('click',function(){
		$(".bank_list").addClass("hide");

		var $o = $(this);
		var $bank_list = $o.find(".bank_list");
		var disabled = $o.find("input[name='payment']").attr("disabled");

		if($bank_list.length && !disabled){
			$bank_list.removeClass("hide");
		}
	});
}

// 金额处理
function bind_money(){
	trade_score=parseInt(trade_score)>0?parseInt(trade_score):0;
	if(trade_score >0)
	{ 
		var score_db_money=parseFloat(parseInt(score/trade_score*100)/100);//保留两位小数
		var score_db_pay=parseInt(score_db_money*trade_score);
	}
	else{
		var score_db_money=0;//保留两位小数
		var score_db_pay=0;
	}
	
	$("input[name='ye_check']").attr("checked","checked");
	$("input[name='ye_check']").bind("click",function(){
		var pay_score=isNaN($("input[name='pay_score']").val())?0:parseInt($("input[name='pay_score']").val());
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
			
		var need_money_m=need_money-pay_score_money;
			need_money_m=round2(need_money_m,2);
		var pay_money_val=0;
		if($(this).is(':checked')){
			$("input[name='credit']").removeAttr("disabled");
			if(pay_score_money>=need_money)
			{
				$("input[name='credit']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(left_money>=need_money_m){
				pay_money_val=need_money_m;
				$("input[name='credit']").val(need_money_m);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}else{
				pay_money_val=left_money;
				$("input[name='credit']").val(left_money);
			}
		}else{
			$("input[name='credit']").val(0);
			$("input[name='payment']").removeAttr("disabled");
			$("input[name='credit']").attr("disabled","disabled");
		}
		count_total_money(pay_money_val,pay_score,pay_score_money,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	$("input[name='credit']").bind("blur",function(){
		var money=isNaN($(this).val())?0:round2($(this).val(),2);
		var pay_score=isNaN($("input[name='pay_score']").val())?0:parseInt($("input[name='pay_score']").val());
		
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
			
		var need_money_m=need_money-pay_score_money;
			need_money_m=round2(need_money_m,2);

		var pay_money_val=0;
		if(money >0){
			if(pay_score_money>=need_money)
			{
				$("input[name='credit']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(money>=need_money_m){
				pay_money_val=need_money_m;
				$("input[name='credit']").val(need_money_m);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}else{
				pay_money_val=money;
				$("input[name='credit']").val(money);
				$("input[name='payment']").removeAttr("disabled");
			}
		}else{
			$("input[name='credit']").val(0);
		}
		count_total_money(pay_money_val,pay_score,pay_score_money,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	
	$("input[name='score_check']").bind('click',function(){
		
		var credit_money=isNaN($("input[name='credit']").val())?0:parseFloat($("input[name='credit']").val());
		var need_money_s=need_money-credit_money;
			need_money_s=round2(need_money_s,2);
		
		var pay_score_val=0;
		var pay_score_money_val=0;
		if($(this).is(':checked')){
			$("input[name='pay_score']").removeAttr("disabled");
			if(credit_money>=need_money)
			{
				$("input[name='pay_score']").val(0);
				$("#score_trade_money").html("¥0");
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
			}
			else if(score_db_money>=need_money_s){
				pay_score_val=parseInt(need_money_s*trade_score);
				pay_score_money_val=need_money_s;
				$("input[name='pay_score']").val(pay_score_val);
				$("#score_trade_money").html("¥"+need_money_s);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				
			}else{
				pay_score_val=score_db_pay;
				pay_score_money_val=score_db_money;
				$("input[name='pay_score']").val(score_db_pay);
				$("#score_trade_money").html("¥"+score_db_money);
			}
			
		}else{
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("¥0");
			$("input[name='pay_score']").attr("disabled",true);
			$("input[name='payment']").removeAttr("disabled");
		}
		
		count_total_money(credit_money,pay_score_val,pay_score_money_val,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	$("input[name='pay_score']").bind("blur",function(){
		var pay_score=isNaN($(this).val())?0:parseInt($(this).val());
		var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
			pay_score=parseInt(pay_score_money*trade_score);
			
		var credit_money=parseFloat($("input[name='credit']").val());
		var need_money_s=need_money-credit_money;
			need_money_s=round2(need_money_s,2);
		
		var pay_score_val=0;
		var pay_score_money_val=0;
		if(pay_score >0)
		{
			if(credit_money>=need_money)
			{
				$("input[name='pay_score']").val(0);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				$("#score_trade_money").html("¥0");
			}
			else if(pay_score_money>=need_money_s){
				pay_score_val=parseInt(need_money_s*trade_score);
				pay_score_money_val=need_money_s;
				$("input[name='pay_score']").val(pay_score_val);
				$("input[name='payment']").attr("disabled",true).removeAttr("checked");
				$("#score_trade_money").html("¥"+pay_score_money_val);
			}else{
				pay_score_val=pay_score;
				pay_score_money_val=pay_score_money;
				$("input[name='payment']").removeAttr("disabled");
				$("input[name='pay_score']").val(pay_score);
				$("#score_trade_money").html("¥"+pay_score_money);
				
			}
		}
		else
		{	
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("¥0");
		}
		
		count_total_money(credit_money,pay_score_val,pay_score_money_val,need_money);
		$("#real_total_box li").css("borderBottom","1px solid #e5e5e5");
		// $("#real_total_box").find("li:visible").last().css("borderBottom","0px");
	});
	
	$("input[name='payment']").bind("click",function(){
		var paytype=$(this).attr("paytype");
		if(paytype == 'offline')
		{
			$("input[name='ye_check']").attr("checked",false).parent(".ui_check").removeClass("ui_checked");
			$("input[name='ye_check']").attr("disabled",true);
			$("input[name='credit']").val(0);
			$("input[name='credit']").attr("disabled",true);
			
			$("input[name='score_check']").attr("checked",false).parent(".ui_check").removeClass("ui_checked");;
			$("input[name='score_check']").attr("disabled",true);
			$("input[name='pay_score']").val(0);
			$("#score_trade_money").html("0");
			$("input[name='pay_score']").attr("disabled",true);
			$("#instation_pay").hide();
		}
		else{
			$("input[name='ye_check']").removeAttr("disabled");
			$("input[name='score_check']").removeAttr("disabled");
			$("#instation_pay").show();
		}
		count_total_money(0,0,0,0);
	});
}

// 统计金额
function count_total_money(pay_money,pay_score,pay_score_money,total)
{
	pay_money=parseFloat(pay_money);
	if(isNaN(pay_score_money)){
		pay_score_money = 0;
	}
	pay_score_money=parseFloat(pay_score_money);
	total=parseFloat(total);
	var online_pay_money=total-(pay_money+pay_score_money);
		online_pay_money=round2(online_pay_money,2);
	
	if(pay_money >0)
	{
		var html="-¥"+pay_money;
		$("#real_money_box").css("display","-webkit-box");
		$("#real_money_val").html(html);
	}else{
		$("#real_money_val").html("");
		$("#real_money_box").hide();
	}
	
	if(pay_score_money>0)
	{
		$("#real_score_box").css("display","-webkit-box");
		$("#real_score_money").html("-¥"+pay_score_money+"&nbsp;("+pay_score+"积分)");
	}else
	{
		$("#real_money").html("");
		$("#real_score_box").hide();	
	}
	
	if(pay_money>0 || pay_score_money>0)
	{
		$("#real_online_box").css("display","-webkit-box");
		$("#real_online_money").html("¥"+online_pay_money);
	}else{
		$("#real_online_money").html("");
		$("#real_online_box").hide();
	}
}

function bind_pay_form()
{
	var pay_status=false;
	var max_pay = parseFloat($(".pay_form").find("input[name='max_pay']").val());
	$(".pay_form").find(".ui-button").on("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
		var max_pay = $(".pay_form").find("input[name='max_pay']").val();
		//var max_credit = $(".pay_form").find("input[name='max_credit']").val();
		//var max_val = parseFloat(max_pay)<parseFloat(max_credit)?parseFloat(max_pay):parseFloat(max_credit);

 
		var money = $(".pay_form").find("input[name='credit']").val();
			money = isNaN(money)?0:parseFloat(money);
		var pay_score=$(".pay_form").find("input[name='pay_score']").val();
			pay_score=isNaN(pay_score)?0:parseInt(pay_score);
		
		if(trade_score >0)
			var pay_score_money=parseFloat(parseInt(pay_score/trade_score*100)/100);//保留两位小数
		else
			var pay_score_money=0;
				
		var pay_money_score=money+pay_score_money;
			pay_money_score=round2(pay_money_score,2);
		var paypassword=$("input[name='paypassword']").val();
		if(pay_money_score >0 )
		{
			if(pay_money_score<max_pay)
			{	
				if($(this).find("input[name='payment']:checked").length==0)
				{
					$.alert("请选择支付方式");
					return false;
				}	
			}
		}
		else{
			if($(this).find("input[name='payment']:checked").length==0)
				{
					$.alert("请选择支付方式");
					return false;
				}	
		}
		if(paypassword==''){
			$.alert("请输入付款密码");
			return false;
		}
		
		var ajaxurl =  APP_ROOT+"/index.php?ctl=ajax&act=check_paypassword";
		var query = $(this).serialize();
	
		$.ajax({
				url: ajaxurl,
				dataType: "json",
				data:query,
				async:false,
				type: "POST",
				success: function(ajaxobj){
					
					if(ajaxobj.status==1)
					{
 						pay_status= true;
					}
					else
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
						pay_status= false;		
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		if(pay_status){
  			return true;
		}else{
  			return false;
		}
 		
	});
}

function bind_pay_tg_form()
{
	var pay_status=false;
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
  		var paypassword=$("input[name='paypassword']").val();
		if(paypassword==''){
			$.alert("请输入密码");
			return false;
		}
 		var ajaxurl =  APP_ROOT+"/index.php?ctl=ajax&act=check_paypassword";
		var query = $(this).serialize() ;
 		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				async:false,
				type: "POST",
				success: function(ajaxobj){
 					if(ajaxobj.status==1)
					{
 						pay_status= true;
					}
					else
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
						pay_status= false;		
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		if(pay_status){
  			return true;
		}else{
  			return false;
		}
 		
	});
}
$(document).on("pageInit","#account-incharge", function(e, pageId, $page) { 	
	var payType = 0;
	var ips_submit_lock = true;
	$(".J_SelectPayType1").on('click',function(){
		SelectPayType(this,0);
	});
	$(".J_SelectPayType2").on('click',function(){
		SelectPayType(this,1);
	});
	function SelectPayType(obj,i){
		$(obj).addClass("cur").siblings().removeClass("cur");
  		switch(i){
			case 0:
				payType = 0;
				$("input[name='payment']").attr("checked",false);
				$("#J_online_pay").show();
 				$("#J_ips_pay").hide();
				$("#J_ips_pay_1").hide();
				$(".pay_form").attr("action",APP_ROOT+"/index.php?ctl=account&act=go_pay");
				$("input[name='is_tg']").val(0);
 				break;
 			case 1:
				payType=1;
 				$("input[name='payment']").attr("checked","");
				payType = 1;
				$("#J_online_pay").hide();
 				//$("#J_ips_pay").show();
				//$("#J_ips_pay_1").show();
				url = APP_ROOT+"/index.php?ctl=collocation&act=DoDpTrade&user_type=0&user_id="+user_id+"&pTrdAmt="+$("input[name='money']").val();
				$(".pay_form").attr("action",url);
				$("input[name='is_tg']").val(1);
 				break;
		}
	}
	$("input[name='money']").bind("blur",function(){
		if(payType==1){
			url = APP_ROOT+"/index.php?ctl=collocation&act=DoDpTrade&user_type=0&user_id="+user_id+"&pTrdAmt="+$("input[name='money']").val();
 			$(".pay_form").attr("action",url);
			get_pay_url='{url_wap r="ajax#get_carry_fee"}';
			var query = new Object();
			query.money=$("input[name='money']").val();
			$.ajax({
				url: get_pay_url,
				dataType: "json",
				data:query,
				type: "POST",
				success:function(ajaxobj){
 					if(ajaxobj.status==1){
 						 $("#incharge_fee").html(ajaxobj.fee+" 人民币(元)");
						 end_money=parseFloat(query.money)- parseFloat(ajaxobj.fee);
						 $("#incharge_fee_end").html(end_money+" 人民币(元)");
					}
				}
			});
		}else{
			$(".pay_form").attr("action",APP_ROOT+"/index.php?ctl=account&act=go_pay");
		}
	});
	
	bind_incharge_form();
});
$(document).on("pageInit","#account-pay", function(e, pageId, $page) { 
	bind_incharge_form();
});

function bind_incharge_form()
{
	$(".pay_form").find(".ui-button").bind("click",function(){
		$(".pay_form").submit();
	});
	$(".pay_form").bind("submit",function(){		
		input_money = $(this).find("input[name='money']").val();
 		if($.trim(input_money) == "" || input_money<=0)
		{
			$.alert("请输入充值金额");
			return false;
		}		
		is_tg=$("input[name='is_tg']").val();
		if($(this).find("input[name='payment']:checked").length==0&&is_tg==0)
		{
			$.alert("请选择支付方式");
			return false;
		}		
		else
		{
 			return true;
		}
		
	});
}
$(document).on("pageInit","#account-get_investor_status", function(e, pageId, $page) {
	$(".gentou_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,1,"是否允许跟投",2);
		 
	});
	$(".gentou_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,0,"是否要拒绝跟投",2);
		 
	});
	$(".lead_examine_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,1,"是否要允许投资",1);
	});
	$(".lead_examine_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_investor(id,0,"是否要拒绝该领投人投资",1);
	});
	$(".J_examine").on('click',function(){
		var item_id = $(this).attr("rel");
		var ajaxobj = $(".examine_"+item_id).html();
		$.modal({
			title: '询价审核',
	      	text: ajaxobj,
	      	buttons: []
		});
		J_examine();
	});
	// 询价审核
	function J_examine(){
		$(".examine_yes").on('click',function(){
			var id=$(this).attr("rel");
			var stock_money=$(this).attr("title");
			deal_investor(id,1,"是否要通过该询价？通过后您的项目融资金额将会变成"+stock_money,0)
			 
		});
		$(".examine_no").on('click',function(){
			var id=$(this).attr("rel");
			var stock_money=$(this).attr("title");
			deal_investor(id,0,"是否要拒绝该询价？",0);
			 
		});
	}
	function deal_investor(id,status,msg,type){
		var ajaxurl = APP_ROOT+"/index.php?ctl=account&act=investor_examine&status="+status+"&id="+id+"&type="+type;
		$.closeModal();
		$.confirm(msg,function(){
			$.ajax({
				url:ajaxurl,
				dataType:"json",
				type:'POST',
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.closeModal();
						$.alert("已允许成功",function(){
							$.router.loadPage(window.location.href);
						});
					}else{
						$.closeModal();
						$.showErr(ajaxobj.info);
						
					}
				}
			});
		});
	}
});

$(document).on("pageInit","#account-get_leader_list", function(e, pageId, $page) {
	$(".lead_yes").on('click',function(){
		var id=$(this).attr("rel");
		deal_lead(id,1,"是否允许该用户成为领投人",2);
	});
	$(".lead_no").on('click',function(){
		var id=$(this).attr("rel");
		deal_lead(id,0,"是否要拒绝该用户成为领投人",2);
	});
	
	function deal_lead(id,status,msg,type){
		var ajaxurl = APP_ROOT+"/index.php?ctl=account&act=lead_examine&status="+status+"&id="+id+"&type="+type;
		$.confirm(msg,function(){
			$.ajax({
				url:ajaxurl,
				dataType:"json",
				type:'POST',
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.closeModal();
						$.alert("已允许成功",function(){
							$.router.loadPage(window.location.href);
						});
					}else{
						$.closeModal();
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});
$(document).on("pageInit","#user-login", function(e, pageId, $page) {
	bind_user_login();
});
$(document).on("pageInit","#ajax-login", function(e, pageId, $page) {
	bind_user_login();
});

$(document).on("pageInit","#user-register", function(e, pageId, $page) {
	var code_timeer = null;
	var code_lefttime = 0 ;
	$("#user_register_form").find("input[name='submit_form']").on("click",function(){
		do_register_user();
	});
	$("#J_send_sms_verify").on("click",function(){
		send_mobile_verify_sms();
	});
	$("#J_send_email_verify").on("click",function(){
		email=$("#user_register_form").find("input[name='email']").val();
		send_email_verify(1,email,"#J_send_email_verify");
	});

	function send_mobile_verify_sms(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.alert("手机号码格式错误");	
			return false;
		}
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.alert("长度不能超过11位");	
			return false;
		}
		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.alert("手机号码不能为空");	
			return false;
		}

	   	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=check_field";  
		var query = new Object();
		query.field_name = "mobile";
		query.field_data = $.trim($("#settings-mobile").val());
		 
		$.ajax({ 
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1)
				{	
					var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=1";
					var squery = new Object();
					squery.mobile = $.trim($("#settings-mobile").val());
					$.ajax({ 
						url: sajaxurl,
						data:squery,
						type: "POST",
						dataType: "json",
						success: function(sdata){
								
							if(sdata.status==1)
							{
								code_lefttime = 60;
								code_lefttime_func();
								$.showSuccess(sdata.info);
								return false;
							}
							else
							{
									$.showErr(sdata.info);
								return false;
							}
						}
					});	
				}
				else
				{	
				 	
					$.showErr(data.info);
					return false;
				}
			}
		});	
	}
	function code_lefttime_func(){
		clearTimeout(code_timeer);
		$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
		$("#J_send_sms_verify").css({"color":"#f1f1f1"});
		code_lefttime--;
		if(code_lefttime >0){
			code_timeer = setTimeout(code_lefttime_func,1000);
		}
		else{
			code_lefttime = 60;
			$("#J_send_sms_verify").val("发送验证码");
			
			$("#J_send_sms_verify").css({"color":"#fff"});
			$("#J_send_sms_verify").on("click",function(){
				send_mobile_verify_sms();
			});
		}
		
	}
	function do_register_user()
	{
		if($.trim($("#user_register_form").find("input[name='user_name']").val()) == ""){
			$.alert("请输入会员名称");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_name']").val()).length < 4){
			$.alert("会员名称不少于4个字符");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_pwd']").val())=="")
		{
			$.alert("请输入登录密码");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='user_pwd']").val()).length < 4){
			$.alert("登录密码不少于4个字符");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val())=="")
		{
			$.alert("请输入确认密码");
			return false;
		}
		if($.trim($("#user_register_form").find("input[name='confirm_user_pwd']").val()) != $.trim($("#user_register_form").find("input[name='user_pwd']").val()))
		{
	 		$.alert("密码不一致");
			return false;
		}
		if(is_mobile){
			if($.trim($("#user_register_form").find("input[name='mobile']").val())=="")
			{
				$.alert("请输入手机号码");
				return false;
			}
		}
		
		if(is_mobile_verify){
			if($.trim($("#user_register_form").find("input[name='verify_coder']").val())=="")
			{
				$.alert("请输入手机验证码");
				return false;
			}
		}

		var ajaxurl = $("form[name='user_register_form']").attr("action");
		var query = $("form[name='user_register_form']").serialize() ;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
	 			if(ajaxobj.status==1)
				{
	 				$.alert("注册成功！",function(){
	 					href = ajaxobj.jump;
						$.router.loadPage(href);
	 				});
				}
				else
				{
	 				$.showErr(ajaxobj.info);							
				}
			},
			error:function(ajaxobj)
			{
	//			if(ajaxobj.responseText!='')
	//			alert(ajaxobj.responseText);
			}
		});
	}
});
$(document).on("pageInit","#user-register_two", function(e, pageId, $page) {
	get_file_fun("card");		
	get_file_fun("credit_report");	
	get_file_fun("housing_certificate");

	$("#ajax_form_identify .ui-button").on('click',function(){
		var $obj=$(this).parent().parent().parent();
		var identify_name = $obj.find("input[name='identify_name']").val();
		var identify_number = $obj.find("input[name='identify_number']").val();	
		if(identify_name == ""){
			$.alert("身份证姓名不能为空！");
			return false;
		}
		if(identify_number == ""){
			$.alert("身份证号码不能为空！");
			return false;
		}
		var ajaxurl = $("#ajax_form_identify").attr("action");
		var query = new Object();
		query.ajax = $obj.find("input[name='ajax']").val();
		query.is_investor = $obj.find("a[name='is_investor'].cur").attr("avalue");
		query.identify_name = $obj.find("input[name='identify_name']").val();
		query.identify_number = $obj.find("input[name='identify_number']").val();
		query.card = $obj.find("input[name='card']").val();
		query.credit_report = $obj.find("input[name='credit_report']").val();
		query.housing_certificate = $obj.find("input[name='housing_certificate']").val();
		query.identity_conditions = $obj.find("input[name='identity_conditions']:checked").val();
		
		query.identify_business_name = $obj.find("input[name='identify_business_name']").val();
		query.bankLicense = $obj.find("input[name='bankLicense']").val();
		query.orgNo = $obj.find("input[name='orgNo']").val();
		query.businessLicense = $obj.find("input[name='businessLicense']").val();
		query.taxNo = $obj.find("input[name='taxNo']").val();
		query.contact = $obj.find("input[name='contact']").val();
		query.memberClassType = $obj.find("select[name='memberClassType']").val();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
});
$(document).on("pageInit","#user-wx_register", function(e, pageId, $page) {
	var code_timeer = null;
	var code_lefttime = 0 ;
	bind_user_register_wx();
	$("#J_send_sms_verify").on("click",function(){
		send_mobile_verify_sms();
	});
	$("#J_send_email_verify").on("click",function(){
		email=$("#user_register_form").find("input[name='email']").val();
		send_email_verify(2,email,"#J_send_email_verify");
	});
	function send_mobile_verify_sms(){
	 	
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误");	
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位");	
			return false;
		}
		
		
		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空");	
			return false;
		}

	   	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=check_field&is_verify=1";  
		var query = new Object();
			query.field_name = "mobile";
			query.field_data = $.trim($("#settings-mobile").val());
			 
			
			var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=0";
			var squery = new Object();
			squery.mobile = $.trim($("#settings-mobile").val());
			$.ajax({ 
				url: sajaxurl,
				data:squery,
				type: "POST",
				dataType: "json",
				success: function(sdata){
						
					if(sdata.status==1)
					{
						code_lefttime = 60;
						code_lefttime_func();
						$.showSuccess(sdata.info);
						return false;
					}
					else
					{
						$.showErr(sdata.info);
						return false;
					}
				}
			});		
		}
		function code_lefttime_func(){
			clearTimeout(code_timeer);
			$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
			$("#J_send_sms_verify").css({"color":"#f1f1f1"});
			code_lefttime--;
			if(code_lefttime >0){
				code_timeer = setTimeout(code_lefttime_func,1000);
			}
			else{
				code_lefttime = 60;
				$("#J_send_sms_verify").val("发送验证码");
				
				$("#J_send_sms_verify").css({"color":"#fff"});
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
			}
			
		}	
		
		
	function bind_user_register_wx() {
		$("#user_register_form").find("input[name='submit_form']").on("click",function(){
			do_register_user_wx();
		});
		$("#user_register_form").bind("submit",function(){
			return false;
		});
	}

	function do_register_user_wx(){
		if(is_mobile){
			if($.trim($("#user_register_form").find("input[name='mobile']").val())=="")
			{
				$.showErr("请输入手机号码");
				return false;
			}
			if($.trim($("#user_register_form").find("input[name='verify_coder']").val())=="")
			{
				$.showErr("请输入验证码");
				return false;
			}
		}
	 	if (!is_mobile) {
			if ($.trim($("#user_register_form").find("input[name='email']").val()) == "") {
				$.showErr("请输入邮箱地址");
				return false;
			}
		}
		 
	 	var ajaxurl = $("form[name='user_register_form']").attr("action");
		var query = $("form[name='user_register_form']").serialize() ;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
	 			if(ajaxobj.status==1)
				{
	 				$.showSuccess("注册成功！自动跳转");
					href = ajaxobj.jump;
					$.router.loadPage(href);
					
				}
				else
				{
	 				$.showErr(ajaxobj.info);							
				}
			},
			error:function(ajaxobj)
			{
	//			if(ajaxobj.responseText!='')
	//			alert(ajaxobj.responseText);
			}
		});
	}
});
function SelectRegisterType(obj,i){
	$(obj).addClass("cur").siblings().removeClass("cur");
		switch(i){
		case 0:
			$("#identify_name_str").text("个人身份证姓名:");
			$(".gr_div").show();
			$(".enterprise_class_type").addClass("enterprise_style");
				break;
			case 1:
				$("#identify_name_str").text("法人身份证姓名:");
			$(".gr_div").hide();
				$(".enterprise_class_type").removeClass("enterprise_style");
				break;
	}
}
$(document).on("pageInit","#account-money_carry", function(e, pageId, $page) {
	$("#Jcarry_amount").bind("blur",function(){
		use_money=parseFloat($(this).val());
		money=money-ready_refund_money;
		if(use_money<=0){
			$.showErr("提现金额要大于0元");
			return false;
		}
		left_money=money-use_money;
		if(left_money<0){
			$(this).attr("value","0");
			$.showErr("提现金额不能超过"+money+"元");
		}
		else{
			$("#Jcarry_acount_balance").html("￥"+foramtmoney(left_money,2)+"元");
		}
	});
});
$(document).on("pageInit","#account-money_carry_log", function(e, pageId, $page) {
	$(".delrefund").on("click",function(){
		var refund_item_id = $(this).attr("rel");
		var ajaxurl = APP_ROOT+'/index.php?ctl=account&act=delrefund&id='+refund_item_id;
		var query = new Object();
		query.ajax = 1;
		$.confirm("确定删除该记录吗？",function(){
			$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						if(ajaxobj.status==1)
						{						
							close_pop();
							location.reload();
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
		
		});
		return false;
	});
});
$(document).on("pageInit","#account-money_carry_bank", function(e, pageId, $page) {
	$(".J_carry_bank").on('click',function(){
		var obj = $(this);
		tabFun(obj);
	});
	if(is_tg && ips_acct_no){
		Jcarry_tj();
		var result_pLock=0;
		checkIpsBalance(0,user_info_id,function(result){
			if(result.pErrCode=="1"){
				result_pLock=result.pLock;
				$(".J_u_money_0").html(result.pBalance-result.pLock+"元");
				$("#Jcarry_totalAmount").val(result.pBalance);
			}
		});
		$("input[name='money']").bind("blur",function(){
			if($(this).val()){
				get_pay_url = APP_ROOT+"/index.php?ctl=ajax&act=get_carry_fee";
				var query = new Object();
				query.money=$("input[name='money']").val();
				$.ajax({
					url: get_pay_url,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
	 					if(ajaxobj.status==1){
 						 	$("#Jcarry_fee").html(ajaxobj.fee+" 元");
						 	end_money=(parseFloat(query.money)- parseFloat(ajaxobj.fee)).toFixed(2);
						 	$("#Jcarry_realAmount").html(end_money+" 元");
						 	tg_end_money=(parseFloat($("#Jcarry_totalAmount").val()-result_pLock)- parseFloat(query.money)).toFixed(2);
						 	$("#Jcarry_acount_balance").html(tg_end_money+" 元");
						 	$("input[name='Jcarry_acount_balance_amount']").val(tg_end_money);
						}
					}
				});
			}
			else{
				$("#Jcarry_fee").html("0.00 元");
				$("#Jcarry_realAmount").html("0.00 元");
				$("#Jcarry_acount_balance").html("0.00 元");
			}
			
		});
		function Jcarry_tj(){
			$("#Jcarry_submit").on("click",function(){
 				if(end_money<=0){
					$.alert("您输入的金额少于提现费用");
					return false;
				}
				if(tg_end_money<=0){
					$.alert("您输入的金额超过实际金额");
					return false;
				}
				var url = APP_ROOT+"/index.php?ctl=collocation&act=DoDwTrade&user_type=0&user_id="+user_info_id+"&pTrdAmt="+$("input[name='money']").val();
				$.router.loadPage(url);
			});
		}
		$("#IPS_CARRY_FORM").submit(function(){
			if($.trim($("#Jcarry_amount").val())=="" || !$.checkNumber($("#Jcarry_amount").val()) || parseFloat($("#Jcarry_amount").val())<=0){
				$.showErr(LANG.CARRY_MONEY_NOT_TRUE,function(){
					$("#Jcarry_amount").focus();
				});
				return false;
			}
			if(parseFloat($("#Jcarry_acount_balance_res").val())<0){
				$.showErr(LANG.CARRY_MONEY_NOT_ENOUGHT,function(){
					$("#Jcarry_acount_balance_res").focus();
				});
				return false;
			}
			var url = APP_ROOT + "/index.php?ctl=collocation&act=DoDwTrade&user_type=0&user_id="+user_info_id+"&pTrdAmt="+$.trim($("#Jcarry_amount").val());
			$.router.loadPage(url);
			return false;
		});
	}
	$(".J_deal_bank").click(function(){
		var obj = $(this);
		var query = new Object();
		query.id = $(this).attr("dataid");
		
		$.confirm("确定要删除吗",function(){
			$.ajax({
				url:APP_ROOT+"/index.php?ctl=account&act=delbank",
				data:query,
				type:"post",
				dataType:"json",
				success:function(result){
					if(result.status==1)
					{
						obj.parent().parent().remove();
						$.router.loadPage(window.location.href);
					}
					else{
						$.showErr(result.info);
					}
					$.closeModal();
				},
				error:function(){
					$.showErr("发生错误");
				}
			});
		});
	});
	
	$("#Jbank_bank_id").live("change",function(){
		if($(this).val()=="other"){
			$("#Jbank_otherbank").removeClass("hide");
		}
		else{
			$("#Jbank_otherbank").addClass("hide");
		}
	}).live('click', function () {
        if ($.data(this, 'events') == null || $.data(this, 'events').change == undefined){
            $(this).bind('change', function () {
               if($(this).val()=="other"){
					$("#Jbank_otherbank").removeClass("hide");
				}
				else{
					$("#Jbank_otherbank").addClass("hide");
				}
            });
        }
	});
	
	$("#addbank-box .reset_btn").live("click",function(){
		$.weeboxs.close("addbank-box");
	});
	function tabFun(obj){
		var $tab_bd_text=$(".tab_bd_text");
		$(obj).addClass("cur").siblings().removeClass("cur");
		if($(obj).attr("rel")=="carry_type1"){
			$("#carry_type1").show().siblings().hide();
		}
		else{
			$("#carry_type2").show().siblings().hide();
			// Jcarry_tj();
		}
	}
	$("#add_bank").click(function(){
		$.showPreloader('正在处理，请稍等');
		$.ajax({
			url:APP_ROOT+"/index.php?ctl=ajax&act=add_bank",
			dataType:"json",
			success:function(result){
				$.hidePreloader();
				if(result.status==1)
				{
					var href = APP_ROOT+"/index.php?ctl=account&act=money_carry_addbank";
					$.router.loadPage(href);
				}
				else{
					$.showErr(result.info,function(){
						if(result.jump!='')
							$.router.loadPage(result.jump);
					});
					
				}
			}
		});
	});
});
$(document).on("pageInit","#account-money_carry_addbank", function(e, pageId, $page) {
	$("#Jbank_bankcard,#Jbank_rebankcard").bankInput(); 
	$("#Jbank_bank_id").bind("change",function(){
		if($(this).val()=='other'){
			$(".otherbank_box").show().css("display","-webkit-box");
		}else{
			$(".otherbank_box").hide();
		}
	});
	$("#account_money_carry_addbank_from").find(".ui-button").bind("click",function(){
		if($("#Jbank_real_name").val()==""){
			$.alert("请输入开户名",function(){
				$("#Jbank_real_name").focus();
			});
			return false;
		}
		if($("select[name='bank_id']").find('option').not(function() {return !this.selected}).val()==""){
			$.alert("请选择银行");
			$("#Jbank_bank_id").focus();
			return false;
		}
		if($("select[name='bank_id']").find('option').not(function() {return !this.selected}).val()=="other" && $("select[name='otherbank']").find('option').not(function() {return !this.selected}).val()==""){
			$.alert("请选择银行");
			$("#Jbank_bank_id").focus();
			return false;
		}
		
		
		if($("select[name='province']").find('option').not(function() {return !this.selected}).val()=="" && $("select[name='city']").find('option').not(function() {return !this.selected}).val()=="0"){
			$.alert("请选择开户行所在地");
			$("#Jbank_region_lv3").focus();
			return false;
		}
		if($("#Jbank_bankzone").val()==""){
			$.alert("请输入开户行网点",function(){
				$("#Jbank_bankzone").focus();
			});
			return false;
		}
		if($.trim($("#Jbank_bankcard").val())==""){
			$.alert("请输入银行卡号");
			$("#Jbank_bankcard").focus();
			return false;
		}
		if($.trim($("#Jbank_rebankcard").val())==""){
			$.alert("请输入确认卡号");
			$("#Jbank_rebankcard").focus();
			return false;
		}
		if($.trim($("#Jbank_bankcard").val())!=$.trim($("#Jbank_rebankcard").val())){
			$.alert("确认卡号不一致");
			$("#Jbank_rebankcard").focus();
			return false;
		}
		ajax_form("#account_money_carry_addbank_from");
	});
});
$(document).on("pageInit","#account-recommend", function(e, pageId, $page) {
	$(".view_deal").bind("click",function(){
		var $this = $(this);
		var $td = $this.parent().parent().find("td");
		var $hide_area = $this.parent().parent().next(".hide_area");
		if($hide_area.is(":hidden")){
			$this.html("关闭推荐理由");
			$td.css("borderBottom","0");
			$hide_area.show();
		}
		else{
			$this.html("查看推荐理由");
			$td.css("borderBottom","1px solid #f2f2f2");
			$hide_area.hide();
		}
	});

	$("#account_recommend tr").bind('mouseover mouseout', function(e){
		$(this).find(".deletebox").toggle();
	});
	ajax_delete_recommend();

	function ajax_delete_recommend(){
		$(".sc").bind("click",function(){
			if (confirm("确认要删除吗？")) {
				var id=$(this).attr("rel");
	          	var ajaxurl = APP_ROOT + "/index.php?ctl=ajax&act=ajax_delete_recommend";
				var query=new Object();
				query.id=id;
				$.ajax({
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
						if(ajaxobj.status==0){
							$.showErr(ajaxobj.info);
							return false;
						}
						if(ajaxobj.status==1){
							$.showSuccess(ajaxobj.info,function(){
								$.router.loadPage(window.location.href);
							});
							return false;
						}
						
					}
				});
	        }
		});
		return false;
	}
});
$(document).on("pageInit","#account-project", function(e, pageId, $page) {
	$(".J_btn_del_item").on('click',function(){
		var ajax_del_id = $(this).attr("ajax_del_id");
		var ajaxurl = APP_ROOT+"/index.php?ctl=project&act=del&id="+ajax_del_id;
	  	ajax_del_item(ajaxurl,ajax_del_id);
	});
	
});
$(document).on("pageInit","#account-project_invest", function(e, pageId, $page) {
	// 拒绝理由
	$(".refuse_reason").on("click",function(){
		var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=refuse_reason";
		var obj=new Object();
		obj.deal_id=$(this).attr("rel");
		$.ajax({ 
			url: ajaxurl,
			data:obj,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1){
					$.alert(data.info, '未通过原因');
				}else{
					$.showErr(data.info);
				}
			}
		});
		return false;
	});
	$(".J_btn_del_invest_item").on('click',function(){
		var ajax_del_id = $(this).attr("ajax_del_id");
		var ajaxurl = APP_ROOT+"/index.php?ctl=project&act=del&id="+ajax_del_id;
	  	ajax_del_item(ajaxurl,ajax_del_id);
	});
});
$(document).on("pageInit","#account-set_repay", function(e, pageId, $page) {
	bind_repay_form();
	function bind_repay_form(){
		$(".set_repay").bind("click",function(){
			if($("input[name='logistics_company']").length){
				if($("input[name='logistics_company']").val() == ''){
					$.showErr("物流公司名称不能为空！");
					return false;
				}
				if($("input[name='logistics_company']").val() == ''){
					$.showErr("物流公司名称不能为空！");
					return false;
				}
				if($("input[name='logistics_links']").val() == ''){
					$.showErr("物流链接地址不能为空！");
					return false;
				}
				if($("input[name='logistics_number']").val() == ''){
					$.showErr("物流编号不能为空！");
					return false;
				}
			}
			$("#repay_form_"+$(this).attr("id")).submit();
		});
		$(".repay_form").bind("submit",function(){
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize();
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						if(ajaxobj.info!="")
						{
							$.showSuccess(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									location.href = ajaxobj.jump;
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		});
	}
});
$(document).on("pageInit","#account-stock_transfer_add", function(e, pageId, $page) {
	bind_stock_transfer(invest_id,deal_name,invote_mini_money,user_num,ajaxurl);
});
$(document).on("pageInit","#account-stock_transfer_edit", function(e, pageId, $page) {
	bind_stock_transfer(stock_transfer_info_id,'',invote_mini_money,user_num,ajaxurl);
});
function bind_stock_transfer(id,deal_name,invote_mini_money,user_num,ajaxurl){
	$(".btn_submit").bind("click",function(){
		if($("#price").val()==''){
			$.showErr("金额不能为空！");
			return false;
		}
		if($("#num").val()==''){
			$.showErr("转让股数不能为空！");
			return false;
		}
		if($("#day").val()==''){
			$.showErr("天数不能为空！");
			return false;
		}	
		if($("#num").val()>user_num){
			$.showErr("转让股数不能大于拥有股数！");
			return false;
		}	
		var ajaxurl = ajaxurl;
		var price=$("#price").val();
		var num=$("#num").val();
		var day=$("#day").val();	
		
		if(!/^[0-9]+(.[0-9]{2})?$/.test(price) &&!/^[0-9]+(.[0-9]{1})?$/.test(price) ){  
			$.showErr("价格至多保留两位小数!"); 
			return false;
	    } 
		if(!/^[0-9]*$/.test(num)){  
			$.showErr("天数必须是正整数!"); 
			return false;
	    } 
		if(!/^[0-9]*$/.test(day)){  
			$.showErr("股数必须是正整数!"); 
			return false;
	    } 
		var deal_name = deal_name;
		var query = new Object();
		query.price=price;
		query.num=num;
		query.day=day;
		query.id=id;
		query.stock_value=invote_mini_money*num;
		if(deal_name){	
			query.deal_name=deal_name;
		}
		$.ajax({
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success:function(data){
				if(data.status==0){
					$.showErr(data.info,function(){
						if(data.jump!="")
						{
							location.href = data.jump;
						}
					});
					return false;
				}
				if(data.status==2){
					$.showErr(data.info);
					return false;
				}
				if(data.status==1){
					$.showSuccess("提交成功",function(){
						if(data.jump!="")
						{
							location.href = data.jump;
						}
					});
				}
			}
		});
	});		
	return false;
}
$(document).on("pageInit","#settings-add_consignee", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});
	if(consignee_id){
		bind_del_consignee(consignee_id,del_url);
	}
 	$("#add_consignee_form").find(".ui-button").bind("click",function(){
        if($("input[name='consignee']").val()==""){
            $.alert("请填写收货人姓名");
            return false;
        }
		if($("select[name='province']").find('option').not(function() {return !this.selected}).val()==""){
            $.alert("请选择省份");
            return false;
        }
        if($("select[name='city']").find('option').not(function() {return !this.selected}).val()==""){
            $.alert("请选择城市");
            return false;
        }
        if($("textarea[name='address']").val()==""){
            $.alert("请填写详细地址");
            return false;
        }
        if($("input[name='zip']").val()==""){
            $.alert("请填写邮编");
            return false;
        }
        if($("input[name='mobile']").val()==""){
            $.alert("请填写收货人手机号码");
            return false;
        }
        ajax_form("#add_consignee_form");
    });
});
$(document).on("pageInit","#category-index", function(e, pageId, $page) {
	$(".category-table").each(function(){
		var li_num = $(this).find("li").length;
		if(li_num<4){
			var left_num = 4-li_num;
			for (i = 0; i < left_num; i++){
				$(this).append("<li></li>");
			}
		}
		else{
			var left_num = li_num % 4;
			for (i = 0; i < left_num; i++){
				$(this).append("<li></li>");
			}
		}
	});
	$(".sub-category-table").each(function(){
		if(!($(this).find("li").html())){
			$(this).hide();
			$(this).prev().hide();
		}
	});
 	$("#top_search_hd .search_cate").bind('click',function(){
        var $obj=$(this);
        var i=$obj.index();
        $obj.attr("checked",true).addClass("cur").siblings().attr("checked",false).removeClass("cur");
        $("#categoryList .category_li").eq(i).show().siblings().hide();
    });
});
$(document).on("pageInit","#deal-comment", function(e, pageId, $page) {
	var dsc_pd_h = $(".dsc_pd").height();
	var h = document.body.offsetHeight;
	var m_h = h - (44 + 50 + dsc_pd_h);
	$(".discussion").css({minHeight:m_h+"px"});
	$(".J_dsc_send").on('click',function(){
		event_send();
	});
	$(".J_replycomment").on('click',function(){
		focus_event(this,user_name);
	});
	function event_send(){
		var content=$("#content").val();
		var ajax=$("#ajax").val();
		var post_url=$("#post_url").val();
		var id=$("#deal_id").val();
		var pid=$("#comment_pid").val();
		
		var query=new Object();
		query.content=content;
		query.ajax=ajax;
		query.id=id;
		query.pid=pid;
		$.ajax({
			url:post_url,
			dataType:"json",
			data:query,
			type:"post",
			success:function(data){
				if(data.status==1){
					var href = APP_ROOT+'/index.php?ctl=deal&act=comment&is_back=2';
					$.router.loadPage(href);
				}
                else{   
					if(data.status==2){
						$.toast(data.info);
					}else{
						$.showErr(data.info,function(){
	                        href = APP_ROOT+'/index.php?ctl=user&act=login';
							$.router.loadPage(href);
	                    });
					}
                    return false;
                }
			},error:function(){
				$.alert("系统繁忙，稍后请重试");
			}
		});
		return false;
	}
	function focus_event(obj,username){
		var pid=$(obj).attr("rel");
		$("#comment_pid").val(pid);
		$("#content").val("回复 "+username+":");
		$("#content").focus();
	}
});
$(document).on("pageInit","#deal-update", function(e, pageId, $page) {
	// 无线滚动加载更多
	var loading = false;
	$($page).on('infinite', function() {
		bind_ajax_load();
	});
	function bind_ajax_load(){
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
	        		 $("#pin_box").append(data.html);
		        	setTimeout(function() {
		        		$(".infinite-scroll .items").find(".lazy").addClass("go");
		        	}, 1);
	        		$(".infinite-scroll-preloader").hide();
	        		$("input[name='page_ajax_url']").val(data.page_ajax_url);
	        		$.refreshScroller();
         		}, 1000);
	        }
      	});
	}

	bind_comment_box_event();

	function bind_comment_box_event()
	{
		$(".timeline-box").find("textarea[name='content']").unbind("focus click blur");
		$(".swap_comment").unbind("click");
		$(".send_btn").unbind("click");
		$(".comment_form").unbind("submit");
		$(".delcomment").unbind("click");
		$(".replycomment").unbind("click");	
		$(".fodeup_comment").unbind("click");
		
		$(".fodeup_comment").bind("click",function(){
			$("#post_"+$(this).attr("rel")+"_comment").slideUp();		
		});
		
		$(".replycomment").bind("click",function(){
			if($(this).parent().parent().find(".reply_box").css("display")=="none")
				$(this).parent().parent().find(".reply_box").show();
			else
				$(this).parent().parent().find(".reply_box").hide();	
		});
		
		$(".delcomment").bind("click",function(){
			var ajaxurl = $(this).attr("href");
			var comment_box = $(this).parent().parent().parent();
			$.confirm("确认要删除该记录吗？",function(){
				
				var query = new Object();
				
				query.ajax = 1;
				$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						if(ajaxobj.status==1)
						{
							$(comment_box).remove();
							$("#comment_"+ajaxobj.logid+"_tip").html(ajaxobj.counthtml);
							close_pop();
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
			});
			return false;
		});
		
		$(".timeline-box").find("textarea[name='content']").bind("focus click",function(){
			if($.trim($(this).val())=="发表评论")
			{
				$(this).val("");
			}
			$(this).addClass("inputing");
			$(this).parent().parent().find(".comment-btn").show();
		});
		
		$(".timeline-box").find("textarea[name='content']").bind("blur",function(){
			if($.trim($(this).val())=="发表评论"||$.trim($(this).val())=="")
			{
				$(this).val("发表评论");
				$(this).removeClass("inputing");
				$(this).parent().parent().find(".comment-btn").hide();
			}
			
		});
		
		$(".swap_comment").bind("click",function(){
			var box_id_str = $(this).parent().parent().attr("id");
			var id_str = box_id_str.split("_");
			id_str = id_str[1];
			if($("#post_"+id_str+"_comment").css("display")=="none")
			{			
				$("#post_"+id_str+"_comment").slideDown(function(){
					if($(this).find(".deal_comment_list .comment_item").length==0)
					{
						$(this).find("textarea[name='content']").click();
					}
				});
			}
			else
			{
				
				$("#post_"+id_str+"_comment").slideUp(function(){
					$("#post_"+id_str+"_comment").find("textarea[name='content']").blur();
				});
			}		
		});
		
		$(".send_btn").bind("click",function(){	
			if($(this).find("div span").html()!="发送中")
			$(this).parent().parent().submit();		
		});
		
		$(".comment_form").bind("submit",function(){
			var btn = $(this).find(".send_btn");
			var form = $(this);
			if($.trim($(this).find("textarea[name='content']").val())==""||$.trim($(this).find("textarea[name='content']").val())=="发表评论")
			{
				$(this).find("textarea[name='content']").focus();
				return false;
			}
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize();
			var log_id = $(this).attr("rel");
			var comment_list_box = $("#deal_comment_list_"+log_id);
			var comment_pid = $(this).find("input[name='comment_pid']").val();			
			$(btn).find("div span").html("发送中");
			
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$(comment_list_box).parent().parent().find("textarea[name='content']").val("发表评论");
						$(comment_list_box).parent().parent().find("textarea[name='content']").blur();
						$(comment_list_box).parent().parent().parent().find(".swap_comment").html(ajaxobj.counthtml);
						comment_list_box.html(ajaxobj.html+comment_list_box.html());
						bind_comment_box_event();
						$("#reply_box_"+comment_pid).fadeOut();
						$(btn).find("div span").html("发送");
						
						// $(".comment_item").hover(function(){
						// 	$(this).find(".delcomment").show();
						// },function(){
						// 	$(this).find(".delcomment").hide();
						// });
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		}); //end comment_form_onsubmit
	}
});
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
$(document).on("pageInit","#home-index", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
	$(".J_focus_show").on('click',function(){
		J_focus_show(this);
	});
	function J_focus_show(obj){
		var rel = $(obj).attr("rel");
		$(obj).addClass("cur").siblings().removeClass("cur");
		$("."+rel).show().siblings().hide();
	}
});
$(document).on("pageInit","#home-organize_list", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
});
$(document).on("pageInit","#home-deal_list", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 
});
function J_view_all(obj){
	var rel = $(obj).attr("rel");
	$("."+rel).addClass("autoheight_wrap");
	$(obj).remove();
}
$(document).on("pageInit","#investor-invester_list", function(e, pageId, $page) {
	$("#choose_show").on('click',function(){
		if($("#choose_box").css("display")=="none"){
			$(this).html('筛选<i class="icon iconfont">&#xe607;</i>');
			$("#choose_box").show();
		}
		else{
			$(this).html('筛选<i class="icon iconfont">&#xe607;</i>');
			$("#choose_box").hide();

		}
	});
});
$(document).on("pageInit","#investor-index", function(e, pageId, $page) {
	$(".J_help_item_show").on('click',function(){
		help_item();
	});
	$("input[name='submit_form']").on('click',function(){
		if(mobile_is_bind){
			investor_save_mobile2();
		}
		else{
			investor_save_mobile();
		}
	});
	$(".tab-nav li").live('click',function(){
		$(".tab-nav li").removeClass("current");
		$(this).addClass("current").siblings().removeClass("current");
	});

	var code_timeer = null;
	screening_identity_type();
	$("#J_send_sms_verify").bind("click",function(){
		if($("#settings-mobile").val()==''){
			$.showErr("手机号码不能为空！");
			return false;
		}else{
			send_mobile_verify_sms();
		}
	});
	$("#verify_coder").bind("blur",function(){	
		if($(this).val()==''){
			//$.showErr("验证码不能为空！");
			return false;
		}else{
			check_register_verifyCoder();
		}		
	});
	//需要同意条款
	$("#J_agreement").bind("click",function(){
		if($("#J_agreement").attr("checked")){
			$("#ui-button").attr("disabled",false);
			$("#ui-button").addClass("theme_color");
		}else{
			$("#ui-button").attr("disabled",true);
			$("#ui-button").removeClass("theme_color");
		}
	});
	//筛选身份类型
	function screening_identity_type(){		
		$(".ui_check").click(function(){
			if($(this).find("input").attr("type")=="radio"){
				var rel=$(this).attr("rel");
				$(".ui_check[rel='"+rel+"']").removeClass("ui_checked");
				$(".ui_check[rel='"+rel+"'] input").attr("checked",false);
				$(this).addClass("ui_checked");
				$(this).find("input").attr("checked","checked");
			}
		});
	}

	//发送验证码短信
	function send_mobile_verify_sms(){
		$("#J_send_sms_verify").unbind("click");

		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位！");	
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}
			if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}

		var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=1";
		var squery = new Object();
		squery.mobile = $.trim($("#settings-mobile").val());
		$.ajax({ 
			url: sajaxurl,
			data:squery,
			type: "POST",
			dataType: "json",
			success: function(sdata){
				if(sdata.status==1)
				{
					code_lefttime = 60;
					code_lefttime_func();
					$.showSuccess(sdata.info);
					return false;
				}
				else
				{
						
					$("#J_send_sms_verify").bind("click",function(){
						send_mobile_verify_sms();
					});
					$.showErr(sdata.info);
					return false;
				}
			}
		});	
	}
	//短信提示时间	
	function code_lefttime_func(){
		clearTimeout(code_timeer);
		$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
		$("#J_send_sms_verify").css({"color":"#f1f1f1"});
		code_lefttime--;
		if(code_lefttime >0){
			$("#J_send_sms_verify").attr("disabled","true");
			code_timeer = setTimeout(code_lefttime_func,1000);
		}
		else{
			code_lefttime = 60;
			$("#J_send_sms_verify").val("发送验证码");
			$("#J_send_sms_verify").attr("disabled","false");
			$("#J_send_sms_verify").css({"color":"#fff"});
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
		}	
	}
	//检查验证码
	function check_register_verifyCoder(){
		if($.trim($("#verify_coder").val())=="")
		{
			$.showErr("请输入验证码!");		
		}
		else
		{
			var mobile = $.trim($("#settings-mobile").val());
			var code = $.trim($("#verify_coder").val());
			if(mobile!=""||code!=""){
				var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
				var query = new Object();
				query.mobile = mobile;
				query.code = code;
				$.ajax({
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
						if(ajaxobj.status==1)
						{
							//$.showSuccess("验证码正确!");
						}
						if(ajaxobj.status==0)
						{
							$.showErr("验证码不正确!");
						}
					}
				});
			}
		}
	}

	//投资者手机验证
	function investor_save_mobile(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位!");	
			return false;
		}
			if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			return false;
		}
		if($.trim($("#verify_coder").val()).length == 0){
			$.showErr("验证码不能为空！");
			return false;
		}
		var investor_two_url=$.trim($("input[name='investor_two_url']").val());
		var is_investor=$.trim($("#investor_id .current").find("input[name='is_investor']").val());
		var mobile = $.trim($("#settings-mobile").val());
		var verify_coder=$.trim($("#verify_coder").val());
		var ajaxurl = APP_ROOT+'/index.php?ctl=user&act=investor_save_mobile';
		var query=new Object();
		query.is_investor=is_investor;
		query.mobile=mobile;
		query.verify_coder=verify_coder;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=investor_two_url;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});

	}

	//投资者手机验证
	function investor_save_mobile2(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位!");	
			return false;
		}
			if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			return false;
		}
		var investor_two_url=$.trim($("input[name='investor_two_url']").val());
		var is_investor=$.trim($("#investor_id .current").find("input[name='is_investor']").val());
		var mobile = $.trim($("#settings-mobile").val());
		var ajaxurl = APP_ROOT+'/index.php?ctl=user&act=investor_save_mobile';
		var query=new Object();
		query.is_investor=is_investor;
		query.mobile=mobile;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=investor_two_url;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});
	}
	// 条款内容
	function help_item()
	{
		var html_var=$("#show_html").html();
		if(html_var){
			$.alert(html_var);
		}	
	}
});

$(document).on("pageInit","#investor-investor_two", function(e, pageId, $page) {
	if(user_info_is_investor == 1){  // investor_personal.html  个人投资者认证
		// 上传图片
		get_file_fun("idcard_zheng");
		get_file_fun("idcard_fang");

		$("#idcard_number").bind("blur",function(){
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if(IdentityCodeValid($("#idcard_number").val())===true){
				//$.showSuccess("身份证号可以使用！");
			}else{
				$.showErr("请正确填写身份证号！");
				return false;
			}
		});
		$(".submit_investor_personal").on('click',function(){
			check_personal_data();
		});
		function check_personal_data(){
			if($("#real_name").val()==''){
				$.showErr("请输入真实姓名！");
				return false;
			}
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if($("#idcard_zheng_u").val()==''){
				$.showErr("请上传身份证正面照片！");
				return false;
			}
			if($("#idcard_fang_u").val()==''){
				$.showErr("请上传身份证背面照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var identify_name=$("#real_name").val();
			var identify_number=$("#idcard_number").val();
			var ajaxurl=$("#ajaxurl").val();
			var idcard_zheng_u=$("#idcard_zheng_u").val();
			var idcard_fang_u=$("#idcard_fang_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=identify_name;
			query.identify_number=identify_number;
			query.idcard_zheng_u=idcard_zheng_u;
			query.idcard_fang_u=idcard_fang_u;
			$.ajax({
				url: ajaxurl,
				data:query,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(ajaxobj)
				{
					$.showErr("系统繁忙，请您稍后重试！")
					return false;
				}
			});
		}
	}
	if(user_info_is_investor == 2){  // investor_agency.html  机构投资者认证
		// 上传图片
		get_file_fun("identify_business_licence");
		get_file_fun("identify_business_code");
		get_file_fun("identify_business_tax");

		$("#identify_business_name").bind("blur",function(){
			if(!$("#identify_business_name").val()){
				$.showErr("机构名称不能为空!");
			}
		});
		$(".submit_investor_agency").on('click',function(){
			check_agency_data();
		});
		function check_agency_data(){
			if($("#identify_business_name").val()==''){
				$.showErr("请输入机构名称！");
				return false;
			}
			if($("#identify_business_licence_u").val()==''){
				$.showErr("请上传营业执照！");
				return false;
			}
			if($("#identify_business_code_u").val()==''){
				$.showErr("请上传组织机构代码证照片！");
				return false;
			}
			if($("#identify_business_tax_u").val()==''){
				$.showErr("请上传税务登记证照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var ajaxurl=$("#ajaxurl").val();
			var identify_business_name=$("#identify_business_name").val();
			var identify_business_licence_u=$("#identify_business_licence_u").val();
			var identify_business_code_u=$("#identify_business_code_u").val();
			var identify_business_tax_u=$("#identify_business_tax_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=$("#identify_name").val();
			query.identify_number=$("#identify_number").val();
			
			query.identify_business_name=identify_business_name;	
			query.identify_business_licence_u=identify_business_licence_u;
			query.identify_business_code_u=identify_business_code_u;
			query.identify_business_tax_u=identify_business_tax_u;
			$.ajax({
				url:ajaxurl,
				data:query,
				dataType:"json",
				type:"post",
				success:function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(){
					$.showErr("系统繁忙，请您稍后重试！");
					return false;
				}
			});	
		}

		// 上传图片
		get_file_fun("idcard_zheng");
		get_file_fun("idcard_fang");

		$("#idcard_number").bind("blur",function(){
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if(IdentityCodeValid($("#idcard_number").val())===true){
				//$.showSuccess("身份证号可以使用！");
			}else{
				$.showErr("请正确填写身份证号！");
				return false;
			}
		});
		$(".submit_investor_personal").on('click',function(){
			check_personal_data();
		});
		function check_personal_data(){
			if($("#real_name").val()==''){
				$.showErr("请输入真实姓名！");
				return false;
			}
			if($("#idcard_number").val()==''){
				$.showErr("请输入身份证号！");
				return false;
			}
			if($("#idcard_zheng_u").val()==''){
				$.showErr("请上传身份证正面照片！");
				return false;
			}
			if($("#idcard_fang_u").val()==''){
				$.showErr("请上传身份证背面照片！");
				return false;
			}
			var result_url=$("#result_url").val();
			var ajax=$("#ajax").val();
			var identify_name=$("#real_name").val();
			var identify_number=$("#idcard_number").val();
			var ajaxurl=$("#ajaxurl").val();
			var idcard_zheng_u=$("#idcard_zheng_u").val();
			var idcard_fang_u=$("#idcard_fang_u").val();
			var query =new Object();
			query.ajax=ajax;
			query.identify_name=identify_name;
			query.identify_number=identify_number;
			query.idcard_zheng_u=idcard_zheng_u;
			query.idcard_fang_u=idcard_fang_u;
			$.ajax({
				url: ajaxurl,
				data:query,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
						return false;
					}else{
						href=result_url;
						$.router.loadPage(href);
					}
				},
				error:function(ajaxobj)
				{
					$.showErr("系统繁忙，请您稍后重试！")
					return false;
				}
			});
		}
	}
});

$(document).on("pageInit","#user-investor_result", function(e, pageId, $page) {
	delayURL();    
    function delayURL() { 
        var delay = $("#time").html();
 		var t = setTimeout("delayURL()", 1000);
        if (delay > 0) {
            delay--;
            $("#time").html(delay);
        } else {
     		clearTimeout(t); 
            href ='{url_wap r="index#index"}';
			$.router.loadPage(href);
        }        
    } 
});
$(document).on("pageInit","#invite-index", function(e, pageId, $page) {
	$(".J_invite_accept").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		bind_invite_accept(invite_item_id);
	});
	$(".J_invite_refuse").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		bind_invite_refuse(invite_item_id);
	});

	// 接受邀请
	function bind_invite_accept(id){
		var ajaxurl = APP_ROOT+'/index.php?ctl=invite&act=set_invite_accept';
		var obj=new Object();
		obj.ajax=1;
		obj.id=id;
		$.confirm("确定接受邀请？",function(){
			$.ajax({
				url:ajaxurl,
				type:"POST",
				data:obj,
				dataType:"json",
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});
					}else{
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
	// 拒绝邀请
	function bind_invite_refuse(id){
		var ajaxurl = APP_ROOT+'/index.php?ctl=invite&act=set_invite_refuse';
		var obj=new Object();
		obj.ajax=1;
		obj.id=id;
		$.confirm("确定拒绝邀请？",function(){
			$.ajax({
				url:ajaxurl,
				type:"POST",
				data:obj,
				dataType:"json",
				success:function(ajaxobj){
					if(ajaxobj.status==1){
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								$.router.loadPage(ajaxobj.jump);
							}
						});
					}else{
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});
$(document).on("pageInit","#message-history", function(e, pageId, $page) {
	bind_del_contact();
	bind_del_message();
	function bind_del_contact()
	{
		$(".delcontact").bind("click",function(){
			var ajaxurl = $(this).attr("href");
			var query = new Object();
			query.ajax = 1;
			$.confirm("确定要删除与该联系人的私信记录吗？",function(){
				$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						close_pop();
						if(ajaxobj.status==1)
						{
							if(ajaxobj.info!="")
							{
								$.showSuccess(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
			});
			
			
			return false;
		});
	}



	function bind_del_message()
	{
		$(".delmessage").bind("click",function(){
			var ajaxurl = $(this).attr("href");
			var query = new Object();
			query.ajax = 1;
			$.confirm("确定要删除该记录吗？",function(){
				$.ajax({ 
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success: function(ajaxobj){
						close_pop();
						if(ajaxobj.status==1)
						{
							if(ajaxobj.info!="")
							{
								$.showSuccess(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}
						}
						else
						{
							if(ajaxobj.info!="")
							{
								$.showErr(ajaxobj.info,function(){
									if(ajaxobj.jump!="")
									{
										href = ajaxobj.jump;
										$.router.loadPage(href);
									}
								});	
							}
							else
							{
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							}							
						}
					},
					error:function(ajaxobj)
					{
						if(ajaxobj.responseText!='')
						alert(ajaxobj.responseText);
					}
				});
			});
			
			
			return false;
		});
	}
});
$(document).on("pageInit","#cart-wx_jspay", function(e, pageId, $page) {
	$(".J_pay").on('click',function(){
		if(type == "V4"){
			callpay_1();
		}
		else{
			callpay();
		}
	});
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			jsApiParameters,
			function(res){
				if(res.err_msg=='get_brand_wcpay_request:fail'){
					//alert(res.err_code+res.err_desc+res.err_msg);
					$.alert('支付失败');
				}
				if(res.err_msg=='get_brand_wcpay_request:cancel '){
					$.alert('支付取消');
				}
				if(res.err_msg=='get_brand_wcpay_request:ok'){
					$.showSuccess('恭喜您支付成功',function(){
						// href="{url_wap r="deal#index" p="id=$data.deal_id"}";
						var href = APP_ROOT+'/index.php?ctl=deal&act=index&id='+deal_id;
						$.router.loadPage(href);
					});
				}
				else{
					//$.showSuccess(res.err_msg);
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
    function callpay_1() {
        wx.chooseWXPay(jsApiParameters);
    }
});
$(document).on("pageInit","#project-add", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});

	// 图片上传
	bind_del_image();
	get_file_fun("image_file");
	get_file_fun("update_log_icon_bj");
	get_file_more_fun("deal_images_file","image_more",5);

	bind_cate_select();
	bind_project_add_edit_form();
	
	set_earnings();
	$("select[name='is_earnings']").bind('change',function(){
		set_earnings();
	});
});
$(document).on("pageInit","#project-edit", function(e, pageId, $page) {
	$("select[name='province']").bind("change",function(){
		load_city();
	});

	// 图片上传
	bind_del_image();
	get_file_fun("image_file");
	get_file_fun("update_log_icon_bj");
	get_file_more_fun("deal_images_file","image_more",5);

	bind_cate_select();
	bind_project_add_edit_form();
	
	set_earnings();
	$("select[name='is_earnings']").bind('change',function(){
		set_earnings();
	});
});
// 删除已上传的图片
function bind_del_image() {
	$(".image_item").find(".remove_image").on("click",function() {
		del_image($(this));
		hide_imgupload();
	});
}

// 上传4张图片后，隐藏上传图片按钮
function hide_imgupload(num) {
	var pic_box_num = $("#image_box").find(".image_item").length;
	var $fileupload_box = $(".fileupload_box");
	pic_box_num == num ? $fileupload_box.hide() : $fileupload_box.show();
}

function del_image(o) {
	$(o).parent().remove();
}

function set_earnings(){
	var is_earnings=parseInt($("select[name='is_earnings']").val());
	if(is_earnings == 1)
	{
		$(".js_earnings_con").css("display","-webkit-box");
	}
	else{
		$(".js_earnings_con").css("display","none");
	}
}
function bind_cate_select() {
	$("#cate_id").bind("change",function(){
		$("#cate_id_last").val($(this).find("option:selected").attr("rel"));
		//alert($(this).attr("rel"));
	});
	/*
	$(".cate_list").find("span").bind("click",function(){
		$(".cate_list").find("span").removeClass("current");
		$(this).addClass("current");
		$("input[name='cate_id']").val($(this).attr("rel"));
	});*/
}
function bind_project_add_edit_form() {
	$("input[name='name']").bind("keyup blur",function(){
		if($(this).val().length>30)
		{
			$(this).val($(this).val().substr(0,30));
			return false;
		}
		else
		$("#project_title").html($(this).val());
	});
	
	$("textarea[name='brief']").bind("keyup blur",function(){
		if($(this).val().length>75)
		{
			$(this).val($(this).val().substr(0,75));
			return false;
		}
		else
		$("#deal_brief").html($(this).val());
	});
	
	$("select[name='province']").bind("change",function(){
		var val = "";
		if($(this).val()=="")
			val = "省份";
		else
			val = $(this).val();
		$("#province").html(val);
	});
	
	$("select[name='city']").bind("change",function(){
		var val = "";
		if($(this).val()=="")
			val = "城市";
		else
			val = $(this).val();
		$("#city").html(val);
	});
	
	$("input[name='limit_price']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$(".limit_price").html($(this).val());
	});
	$("input[name='deal_days']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseInt($(this).val())<=0)
		{
			$(this).val("");
		}
		else if($(this).val().length>2)
		{
			$(this).val($(this).val().substr(0,2));
			$("#deal_days").html($(this).val().substr(0,2));
		}
		else
		$(".deal_days").html($(this).val());
	});

	$("#project_form").bind("submit",function(){
		if($.trim($(this).find("input[name='limit_price']").val())=='')
		{
			$.alert("请输入筹款金额");
			return false;
		}
		if(isNaN($(this).find("input[name='limit_price']").val())||parseFloat($(this).find("input[name='limit_price']").val())<=0)
		{
			$.alert("请输入正确的筹款金额");
			return false;
		}
		if($.trim($(this).find("input[name='deal_days']").val())=='')
		{
			$.alert("请输入筹集天数");
			return false;
		}
		if(isNaN($(this).find("input[name='deal_days']").val())||parseInt($(this).find("input[name='deal_days']").val())<=0)
		{
			$.alert("请输入正确的筹集天数");
			return false;
		}
		if($.trim($(this).find("input[name='name']").val())=='')
		{
			$.alert("请填写项目标题");
			return false;
		}
		if($(this).find("input[name='name']").val().length>30)
		{
			$.alert("项目标题不超过30个字");
			return false;
		}
		if($(this).find("input[name='cate_id']").val()==''||$(this).find("input[name='cate_id']").val()==0)
		{
			$.alert("请选择项目分类");
			return false;
		}
		if($.trim($(this).find("select[name='province']").val())=='')
		{
			$.alert("请选择省份");
			return false;
		}
		if($.trim($(this).find("select[name='city']").val())=='')
		{
			$.alert("请选择城市");
			return false;
		}
		if($.trim($(this).find("input[name='image']").val())=='')
		{
			$.alert("上传封面图片");
			return false;
		}
		
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		query+="&description="+ encodeURIComponent($("textarea[name='descript']").val());
 		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$("input[name='id']").val(ajaxobj.info);
						$.showSuccess("保存成功",function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							href = ajaxobj.jump;
							$.router.loadPage(href);
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
		
	$("#savenow").bind("click",function(){
		$("input[name='savenext']").val("0");
		$("#project_form").submit();
	});
	$("#savenext").bind("click",function(){
		$("input[name='savenext']").val("1");
		$("#project_form").submit();
	});
}
$(document).on("pageInit", "#project-add_item", function(e, id, page) {
	get_file_fun('image_file');
	$(".J_add_reward").on('click',function(){
		add_reward(user_info_id);
	});
	$(".J_cancel_add").on('click',function(){
		cancel_add();
	});
	var $project_add_form = $("#project_add_form");
	$project_add_form.find(".ui-button").bind("click",function(){
		var type=$project_add_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_add_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_add_form");
	});
	bind_del_image();
	bind_del_item();
	bind_submit_deal_btn();
	load_type_info(1);
	
	var $project_edit_form = $("#project_edit_form");
	$project_edit_form.find(".ui-button").on("click",function(){
		var type=$project_edit_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_edit_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_edit_form");
	});

	$("input[name='type']").bind('click',function(){
		var type=$(this).val();
		load_type_info(0,type);
	});

	function add_reward(){
		if($(".item_row").length>=10){
			$.alert("回报项目不能超过10个");
			return false;
		}
		$("#add_item_form").show();
		$("#project_add_item").hide();
		load_type_info(1);
		get_file_fun('image_file');
	}
	function cancel_add(){
		$("#add_item_form").hide();
		$("#project_add_item").show();
	}
});
$(document).on("pageInit", "#project-edit_item", function(e, id, page) {
	get_file_fun('image_file');
	bind_del_image();
	bind_del_item();
	bind_submit_deal_btn();
	load_type_info(1);
	
	var $project_edit_form = $("#project_edit_form");
	$project_edit_form.find(".ui-button").on("click",function(){
		var type=$project_edit_form.find("input[name='type']:checked").val();
		if(type !=1 && $project_edit_form.find("input[name='price']").val()<=0){
			$.alert("请输入正确的价格");
			return false;
		}
		ajax_form("#project_edit_form");
	});

	$("input[name='type']").bind('click',function(){
		var type=$(this).val();
		load_type_info(0,type);
	});
});

// 删除已上传的图片
function bind_del_image() {
	$(".image_item").find(".remove_image").on("click",function() {
		del_image($(this));
		hide_imgupload();
	});
}

// 上传4张图片后，隐藏上传图片按钮
function hide_imgupload() {
	var pic_box_num = $("#image_box").find(".image_item").length;
	var $fileupload_box = $(".fileupload_box");
	pic_box_num == 4 ? $fileupload_box.hide() : $fileupload_box.show();
}

function del_image(o) {
	$(o).parent().remove();
}

function bind_del_item() {
	$(".del_item").bind("click",function(){
		var ajaxurl = $(this).attr("href");
		var query = new Object();
		query.ajax = 1;
		$.confirm("确定删除该项吗？",function(){
			close_pop();
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						if(ajaxobj.info!="")
						{
							$.showSuccess(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		});
		
		return false;
	});
}

function bind_submit_deal_btn() {
	$("#submit_deal_btn").bind("click",function(){
		var ajaxurl = $(this).attr("url");
		var jump = $(this).attr("jump");
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status)
				{
					alert(111);
					$.showSuccess(ajaxobj.info,function(){
					 	href = jump;
					 	alert(222);
					 	$.router.loadPage(href);
					});
				}
				else
				{
					if(ajaxobj.jump!=""){
						href = ajaxobj.jump;
						$.router.loadPage(href);
					}
					else{
						$.alert(ajaxobj.info);
					}
					
				}
			}
		});
		return false;
	});
}
function ischeck(obj) {
	if($(obj).val()==0){
		$(obj).parent().parent().next().hide();
	}
	else{
		$(obj).parent().parent().next().show().css("display","-webkit-box");
	}
}
function load_type_info(load,type)
{
	if(load ==1)
	{
		var type=$("input[name='type']:checked").val();
	}
	
	if(type==1){
		$(".type_0").hide();
		$(".type_2").hide();
	}else if(type==2){
		$(".type_0").hide();
		$(".type_2").css("display","-webkit-box");

			ischeck("input[name='is_delivery']:checked");
			ischeck("input[name='is_limit_user']:checked");
		
	}else{
		$(".type_2").hide();
		$(".type_0").css("display","-webkit-box");


			ischeck("input[name='is_delivery']:checked");
			ischeck("input[name='is_limit_user']:checked");
			ischeck("input[name='is_share']:checked");

		
	}
}
$(document).on("pageInit","#deal-project_follow", function(e, pageId, $page) {
	leader_detailed_information();
	//领投人详细资料
	function leader_detailed_information(){
		$("#detailed_information").bind("click",function(){
			var ajaxurl = APP_ROOT+'/index.php?ctl=ajax&act=leader_detailed_information&id='+leader_info_id;
			$.ajax({
				url: ajaxurl,
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1){
						// $.weeboxs.open(ajaxobj.html, {boxid:'leader_detailed_info',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'详细信息',width:300,type:'wee'});
						$.alert(ajaxobj.html);
					}
				    if(ajaxobj.status==2){
						$.showErr(ajaxobj.info);
					}
				}
			});
		});
	}
});
$(document).on("pageInit","#settings-bank", function(e, pageId, $page) {
	$("#Jbank_bankcard").bankInput();
	$(".J_check_and_postData").on('click',function(){
		check_and_postData();
	});
	function check_and_postData(){
		if(confirm("一旦保存将不可以修改,您确定吗？")){
			if($("#ex_real_name").val()==""){
				$.showErr("请填写姓名");
				return false;
			}
			if($("#ex_account_bank").val()==""){
				$.showErr("请填写开户银行");
				return false;
			}
			if($("#Jbank_bankcard").val()==""){
				$.showErr("请填写银行帐号");
				return false;
			}
			if($("#ex_contact").val()==""){
				$.showErr("请填写联系电话");
				return false;
			}
			if($("#ex_qq").val()==""){
				$.showErr("请填写联系qq");
				return false;
			}
			
			var ex_real_name=$("#ex_real_name").val();
			var ex_account_bank=$("#ex_account_bank").val();
			var ex_account_info=$("#Jbank_bankcard").val();
			var ex_contact=$("#ex_contact").val();
			var ex_qq=$("#ex_qq").val();
			var post_url=APP_ROOT+'/index.php?ctl=settings&act=save_bank';
			
			var query=new Object();
			query.ex_real_name=ex_real_name;
			query.ex_account_bank=ex_account_bank;
			query.ex_account_info=ex_account_info;
			query.ex_contact=ex_contact;
			query.ex_qq=ex_qq;
			
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
					success:function(data){
						if(data.info!=null){
							$.showErr(data.info);
						}else{
							if(data.status==1){
								$.showSuccess("保存成功!",function(){
									$.router.loadPage(window.location.href);
								});
							}
							if(data.status==0){
								$.showErr("保存失败!");
							}
						}
				},error:function(){
					$.showErr("系统繁忙，稍后请重试!");
				}
			});
			return false;
		}else{
			return false;
		}
	
	}
});
$(document).on("pageInit","#settings-index", function(e, pageId, $page) {
 	(function(){
        if(is_tg){
            checkIpsBalance(0,user_info_id,function(result){
                var $u_money_other=$("#u_money_other");
                if(result.pErrCode=="1"){
                    $u_money_other.css("display","flex");
                    $u_money_other.find("#u_money_other_money").html(formatNum(result.pBalance-result.pLock));
                    $u_money_other.find("#u_money_other_freeze").html(formatNum(result.pLock));
                }
            });
        }
    })();
    bind_user_loginout();
});
$(document).on("pageInit","#settings-invite", function(e, pageId, $page) {
	$(".J_del_invite").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		del_invite(invite_item_id);
	});
	function del_invite(id){
		var post_url=APP_ROOT+"/index.php?ctl=settings&act=del_invite&id="+id;
		$.ajax({
			url:post_url,
			dataType:"json",
			type:"post",
			success:function(data){
				if(data.status==1){
					$.showSuccess(data.info,function(){
						$.router.loadPage(window.location.href);
					});
				}else{
					$.showErr(data.info);
				}
			}
		});
	}
});
$(document).on("pageInit","#settings-password", function(e, pageId, $page) {
	if(USER_VERIFY == 2){
		$(".J_save_mobile_password").on('click',function(){
			save_mobile_password();
		});

		var code_timeer = null;
		var code_lefttime = 0;

		$("#J_send_sms_verify").on("click",function(){
			send_mobile_verify_sms();
		});
		$("#setting_mobile_pwd_form").find("input[name='verify_coder']").bind("blur",function(){
			check_register_verifyCoder();
		});
		function form_error(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_tip'>"+str+"</div>");
		}
		function form_success(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_success'>"+str+"</div>");
		}
		function send_mobile_verify_sms(){
			$("#J_send_sms_verify").unbind("click");
		
			if(!$.checkMobilePhone($("#settings-mobile").val()))
			{
				form_error($("#settings-mobile"),"手机号码格式错误");	
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
			
			
			if(!$.maxLength($("#settings-mobile").val(),11,true))
			{
				$("#settings-mobile").focus();
				$("#settings-mobile").next().show().text("长度不能超过11位");			
				$("#settings-mobile").next().css({"color":"red"});
				$("#J_send_sms_verify").bind("click",function(){
					
					send_mobile_verify_sms();
				});
				return false;
			}
	 		if($.trim($("#settings-mobile").val()).length == 0)
			{				
				form_error($("#settings-mobile"),"手机号码不能为空");
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
		
			var sajaxurl ='{url_wap r="ajax#send_mobile_verify_code"}';
			var squery = new Object();
			squery.mobile = $.trim($("#settings-mobile").val());
			$.ajax({ 
				url: sajaxurl,
				data:squery,
				type: "POST",
				dataType: "json",
				success: function(sdata){
					if(sdata.status==1)
					{
						code_lefttime = 60;
						code_lefttime_func();
						$.showSuccess(sdata.info);
						return false;
					}
					else
					{
							
						$("#J_send_sms_verify").bind("click",function(){
							send_mobile_verify_sms();
						});
						$.showErr(sdata.info);
						return false;
					}
				}
			});
		}
		function code_lefttime_func(){
			clearTimeout(code_timeer);
			$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
			$("#J_send_sms_verify").css({"color":"#f1f1f1"});
			code_lefttime--;
			if(code_lefttime >0){
				$("#J_send_sms_verify").attr("disabled","true");
				code_timeer = setTimeout(code_lefttime_func,1000);
			}
			else{
				code_lefttime = 60;
				$("#J_send_sms_verify").val("发送验证码");
				$("#J_send_sms_verify").attr("disabled","false");
				$("#J_send_sms_verify").css({"color":"#fff"});
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
			}
		}
		//检查验证码
		function check_register_verifyCoder(){
	 		if($.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val())=="")
			{
				form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"请输入验证码");		
			}
			else
			{
				var mobile = $.trim($("#setting_mobile_pwd_form").find("input[name='mobile']").val());
				var code = $.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val());
				if(mobile!=""||code!=""){
					var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
					var query = new Object();
					query.mobile = mobile;
					query.code = code;
					$.ajax({
						url: ajaxurl,
						dataType: "json",
						data:query,
						type: "POST",
						success:function(ajaxobj){
							if(ajaxobj.status==1)
							{
								form_success($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码正确");
							}
							if(ajaxobj.status==0)
							{
								form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码不正确");
							}
						}
					});
				}
			}
		}
		
		function save_mobile_password(){
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var verify_coder=$("#verify_coder").val();
			var post_url=APP_ROOT+"/index.php?ctl=settings&act=save_mobile_password";
			var query = new Object();
				query.user_pwd = user_pwd;
				query.confirm_user_pwd = confirm_user_pwd;
				query.verify_coder=verify_coder;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
					success:function(data){
						if(data.info!=null){
							alert(data.info);
						}
						else{
							if(data.status==1){
								alert("保存成功!",function(){
									$.router.loadPage(window.location.href);
								});
							}
							if(data.status==0){
								alert("保存失败!");
							}
						}
				},error:function(){
					alert("系统繁忙，稍后请重试!");
				}
			});
		}
	}
	else{
		$(".J_save_password").on('click',function(){
			save_password();
		});
		function save_password(){
			var user_old_pwd=$("#user_old_pwd").val();
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var post_url='{url_wap r="settings#save_password"}';
			
			var query=new Object();
			query.user_old_pwd=user_old_pwd;
			query.user_pwd=user_pwd;
			query.confirm_user_pwd=confirm_user_pwd;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
				success:function(data){
					if(data.info!=null){
						$.showErr(data.info);
					}else{
						if(data.status==1){
							$.showSuccess("保存成功!",function(){
								$.router.loadPage(window.location.href);
							});
						}
						if(data.status==0){
							$.showSuccess("保存失败!");
						}
					}
				},
				error:function(){
					$.showErr("系统繁忙，稍后请重试!");
				}
			});
			return false;
		}
	}
});
$(document).on("pageInit","#settings-modify", function(e, pageId, $page) {
	get_file_fun("avatar_file");
	bind_ajax_setting_form();

	switch_city("province","city");
	switch_city("gz_province","gz_city");

	$(".J_SelectPersonalType").on('click',function(){
		SelectSettingType(this,0);
	});
	$(".J_SelectAgencyType").on('click',function(){
		SelectSettingType(this,1);
	});
	$(".J_addCity").on('click',function(){
		btn_addCity(this,'gz_province','gz_city');
	});
	$("#company_create_time").datetimePickers({
	  	toolbarTemplate: '<header class="bar bar-nav">\
						  	<button class="button button-link pull-right close-picker">确定</button>\
  							<h1 class="title">选择日期</h1>\
  						  </header>'
	});
	$("input[name='company_url']").focus(function(){
	  	auto_write_focus(this);
	});
	$("input").blur(function(){
  		auto_write_blur(this);
	});

	// 最多选择3个
	(function(){
		var cate_name_list=$("#cate_name_list");
		var cate_name=cate_name_list.find("input[rel='cate_name']");
		var notChecked = cate_name_list.find("input[rel='cate_name']").not("input:checked");
		var isChecked = cate_name_list.find("input[rel='cate_name']:checked");
		cate_name.bind('click',function(){
			check();
		});
	  	if(isChecked.length>=3){
	  		for(var i=0; i<notChecked.length; i++){
				notChecked[i].disabled=true;
			}
	  	}
		function disableCheckBox(){ 
			for(var i=0; i<cate_name.length; i++){
				if(!cate_name[i].checked) 
				cate_name[i].disabled=true;
			}
		}
		function ableCheckBox(){
		    for(var i=0; i<cate_name.length; i++)
		    cate_name[i].disabled = false;
		}

		function check(){
		    var sun=0;
		    for(var i=0; i<cate_name.length; i++){
		        if(cate_name[i].type=="checkbox" && cate_name[i].checked)
		        	sun++;
		        if(sun<3) {
		            ableCheckBox();
		            //break; 
		        } else if (sun==3) {
		            disableCheckBox();
		           	event.srcElement.checked = true;
		            break;
		        } else if (sun>3) {
		            event.srcElement.checked = false;
		            break;
		        }
		    }
		}
	})();


	// 自动强制前缀(http://)
	function auto_write_focus(obj){
		if($(obj).val() == "http://" || $(obj).val() == ""){
	  		$(obj).val("http://");
	  	}
	}
	function auto_write_blur(obj){
	  	if($(obj).val() == "http://"){
			$(obj).val("");
			$(obj).next(".holder_tip").show();
		}
	}
	function SelectSettingType(obj,obj_i){
		$(obj).addClass("cur").siblings().removeClass("cur");
  		switch(obj_i){
			case 0:
				$("#J_online_pay").show();
 				$("#J_ips_pay").hide();
 				break;
 			case 1:
				$("#J_online_pay").hide();
				$("#J_ips_pay").show();
 				break;
		}
	}
	// 添加关注城市
	function btn_addCity(obj,province,city){
		var btn_id = $(obj).attr("id");
		var gz_city_id = btn_id.substring(4);
		var gz_province = $("select#"+province).find('option').not(function() {return !this.selected}).val();
		var gz_city = $("select#"+city).find('option').not(function() {return !this.selected}).val();
		var $gz_region_box = $(".gz_region_box");
	 	if($gz_region_box.children().length < 3){
			if(gz_province && gz_city){
				if($gz_region_box.children().length == 2){
					$(".gz_region_select").hide();
				}
				gz_region_i++;
				$gz_region_box.append("<label class='mr10'><span class='gz_region'>"+gz_province+"."+gz_city+"</span>&nbsp;<i class='icon iconfont del_region' onclick='del_region(this);'>&#xe61f;</i><input type='hidden' name='gz_region["+gz_region_i+"]' value='"+gz_province+"."+gz_city+"' /></label>");
			}
			else{
				if(!gz_province){
					$.showErr("请选择省份");
				}
				else{
					$.showErr("请选择城市");
				}
			}
		}
		else{
			$(".gz_region_select").hide();
			$.showErr("最多只能添加3个关注城市");
			return false;
		}
	}
	
	// 删除添加的关注城市
	function del_region(obj){
		$(obj).parent().remove();
		$(".gz_region_select").show();
	}
	var region_arr = new Array();
	function do_region_arr(){
		for(var i=0; i<$("span[name='gz_region']").length; i++){
			region_arr[i] = $("span[name='gz_region']").eq(i).html();
		}
	}
	// 绑定ajax_form
	function bind_ajax_setting_form()
	{
		$(".ajax_setting_form").find(".ui-button").bind("click",function(){
	 		$(".ajax_setting_form").submit();
		});
		$(".ajax_setting_form").bind("submit",function(){
			do_region_arr();
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize();
			query.region_arr = region_arr;
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						if(ajaxobj.info!="")
						{
							$.showSuccess(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		});
	}

	//切换地区
	function switch_city(province,city){
		var city = city;
		$("select[name='"+province+"']").bind("change",function(){
			load_city(this,city);
		});
	}
	function load_city(obj,city)
	{
		var id = $(obj).find('option').not(function() {return !this.selected}).attr("rel");
		var evalStr="regionConf.r"+id+".c";
		if(id==0)
		{
			var html = "<option value=''>请选择城市</option>";
		}
		else
		{
			var regionConfs=eval(evalStr);
			evalStr+=".";
			var html = "<option value=''>请选择城市</option>";
			for(var key in regionConfs)
			{
				html+="<option value='"+eval(evalStr+key+".n")+"' rel='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
			}
		}
		$(obj).parent().parent().find("select[name='"+city+"']").html(html);
	}
});
$(document).on("pageInit","#settings-security", function(e, pageId, $page) {
	$(".J_setting").on('click',function(){
		J_setting_security(this);
	});
	function J_setting_security(obj){
		var ajaxurl="";
		var setting_title="";
		if($(obj).attr("rel")=="setting_username"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_username";
			setting_title="设置昵称";
		}
		else if($(obj).attr("rel")=="setting_pwd"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_pwd";
			setting_title="登录密码";
			var ajax_fun = function(){
				$("#ajax_form_password").find(".ui-button").bind("click",function(){
					if($("input[name='user_old_pwd']").val()==""){
						$.toast("请输入旧密码",1000);
						return false;
					}
					if($("input[name='user_pwd']").val()==""){
						$.toast("请输入新密码",1000);
						return false;
					}
					if(($("input[name='user_pwd']").val()).length<4){
						$.toast("密码不能低于四位",1000);
						return false;
					}
					if($("input[name='confirm_user_pwd']").val()==""){
						$.toast("请输入确认密码",1000);
						return false;
					}
					ajax_form("#ajax_form_password");
				});
			}
		}
		else if($(obj).attr("rel")=="setting_email"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_email";
			setting_title="绑定邮箱";
			var ajax_fun = function(){
				$("#email_verify_code").bind("click",function(){
					step=$("#ajax_form_email").find("input[name='step']").val();
					if(step==1){
						email=$("#ajax_form_email").find("input[name='email']").val();
						send_email_verify(step,email,"#email_verify_code");
					}
					else{
						if(step==2){
							send_email_verify(step,'',"#email_verify_code");
						}
					}
				});
				$("#ajax_form_email").find(".ui-button").bind("click",function(){
					if(user_info_email){
						if($("input[name='verify_coder']").val()==""){
							$.toast("请输入邮件验证码",1000);
							return false;
						}
						if($("input[name='email']").val()==""){
							$.toast("请输入新邮箱",1000);
							return false;
						}
					}
					else{
						if($("input[name='email']").val()==""){
							$.toast("请输入新邮箱",1000);
							return false;
						}
						if($("input[name='verify_coder']").val()==""){
							$.toast("请输入邮件验证码",1000);
							return false;
						}
					}
					ajax_form("#ajax_form_email");
				});
			}
		}
		else if($(obj).attr("rel")=="setting_mobile"){
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_mobile";
			setting_title="绑定手机";
			var ajax_fun = function(){
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms_custom($.trim($("#settings-mobile-type").val()),$.trim($("#settings-mobile").val()),"#J_send_sms_verify");
				});

				$("#ajax_form_mobile .ui-button").bind('click',function(){
					var $obj=$(this).parent().parent().parent();
					var mobile=$obj.find("input[name='mobile']").val();
					var verify_coder=$obj.find("input[name='verify_coder']").val();
					if(user_info_mobile){
						if($.trim(verify_coder) == ""){
							$.toast("请输入手机验证码",1000);
							return false;
						}
						if($.trim(mobile) == ""){
							$.toast("请输入新手机号",1000);
							return false;
						}
					}
						
					else{
						if($.trim(mobile) == ""){
							$.toast("请输入手机号",1000);
							return false;
						}
						if($.trim(verify_coder) == ""){
							$.toast("请输入手机验证码",1000);
							return false;
						}
					}
					ajax_form("#ajax_form_mobile");
				});
			}
		}
		else{
			ajaxurl=APP_ROOT+"/index.php?ctl=ajax&act=setting_paypwd";
			setting_title="付款密码";
			var ajax_fun = function(){
				$("#J_send_sms_verify_pay").bind("click",function(){
					send_mobile_verify_sms_custom(2,'',"#J_send_sms_verify_pay");
				});
				$("#ajax_form_paypassword .ui-button").bind('click',function(){
					var $obj=$(this).parent().parent().parent();
					var paypassword=$obj.find("input[name='paypassword']").val();
					var confirm_pypassword=$obj.find("input[name='confirm_pypassword']").val();
					var verify=$obj.find("input[name='verify']").val();
					if($.trim(paypassword)){
						if(paypassword.length <= 5){
							$.toast("付款密码长度不少于6位",1000);
							return false;
						}
					}
					else{
						$.toast("请输入付款密码",1000);
						return false;
					}
					if($.trim(confirm_pypassword)){
						if($.trim(confirm_pypassword) != $.trim(paypassword)){
							$.toast("两次输入密码不一致",1000);
							return false;
						}
					}
					else{
						$.toast("请输入确认密码",1000);
						return false;
					}
					if($.trim(verify) == ""){
						$.toast("请输入手机验证码",1000);
						return false;
					}
					ajax_form("#ajax_form_paypassword");
				});
			}
		}
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
		    		$.modal({
						title: setting_title,
				      	text: ajaxobj.html,
				      	buttons: []
					});
					ajax_fun();
				}
			    if(ajaxobj.status==2){
					$.showErr(ajaxobj.info);
				}
			}
		});
	}
});
$(document).on("pageInit","#settings-setting_id", function(e, pageId, $page) {
	get_file_fun("identify_positive");
	get_file_fun("identify_nagative");
	get_file_fun("identify_business_licence");
	get_file_fun("identify_business_code");
	get_file_fun("identify_business_tax");
	get_file_fun("card");		
	get_file_fun("credit_report");	
	get_file_fun("housing_certificate");	
	bind_ajax_form_custom(".ajax_form_identify");
	$("#J_send_sms_verify_iden").bind("click",function(){
		send_mobile_verify_sms_custom(2,'',"#J_send_sms_verify_iden");
	});
	$(".ajax_form_identify").find("input[name='is_investor']").bind('click',function(){
		$("#qy_div").toggle();
		get_file_fun("identify_business_licence");
		get_file_fun("identify_business_code");
		get_file_fun("identify_business_tax");
		if($(this).val()==2){
			$("#identify_name_str").html("法人身份证姓名：");
			$(".gr_div").hide();
		}else{
			$("#identify_name_str").html("个人身份证姓名：");
			$(".gr_div").show();
		}
	});
});
$(document).on("pageInit","#investor-applicate_leader", function(e, pageId, $page) {
    $(".btn_info_view").on("click",function(){
        if($(".cate_name:checked").length > 3) {
            $.toast("最多只能选择3项");
            return false;
        }
    });
    $("input[name='submit_form']").on("click",function(){
        check_num();
    });
    
    bind_submit();


	// 获取字数长度
	function GetCharLength(str)
	{  
	    var iLength = 0;  
	    for(var i = 0; i<str.length; i++){  
	        if(str.charCodeAt(i) >255){  
	            iLength += 1;  
	        }  
	        else{  
	            iLength += 0.5;  
	        }  
	    }  
	    return iLength;  
	}   

	function check_num(){
	    var falg = 0; 
	        $(".cate_name").each(function() { 
	            if($(this).attr("checked") == 'checked') { 
	                falg += 1; 
	            } 
	        });
	}
	function bind_submit(){
	    $("#applicat_lead_qualificat_form").bind("submit",function(){
	        if($(".cate_name:checked").length==0){
	            $.showErr("请选择领投项目行业");
	            return false;
	        }
	        if($(".cate_name:checked").length>3){
	            $.showErr("领投项目行业最多不超过3项");
	            return false;
	        }
	        // if($("textarea[name='describe']").val().length<100){
	        //  $.showErr("个人简介，不少于100字!");
	        //  return false;
	        // }

	        // 字数不少于100字
	        var curStr=$("textarea[name='describe']").val();
	        var curLength=parseInt(GetCharLength(curStr));
	        if(curLength<100){
	            $.showErr("个人简介，不少于100字!");
	            return false;
	        }
	        
	        var ajaxurl=$(this).attr("action");
	        var query=$(this).serialize();  
	        query+="&description="+encodeURIComponent($("textarea[name='describe']").val());
	        $.ajax({
	            url: ajaxurl,
	            dataType: "json",
	            data:query,
	            type: "POST",
	            success:function(ajaxobj){
	                if(ajaxobj.status==1){
	                    $.showSuccess(ajaxobj.info,function(){
	                        href=ajaxobj.url;
							$.router.loadPage(href);
	                    });
	                }else{
	                    $.showErr("系统繁忙，请您稍后重试！");
	                    return false;
	                }
	            }
	        });
	        return false;
	    });
	    $("#ui-button").bind("click",function(){    
	        $("#applicat_lead_qualificat_form").submit();
	    });
	}
});
$(document).on("pageInit","#user-getpassword", function(e, pageId, $page) {
	var code_timeer = null;
	var code_lefttime = 0 ;
	$(function(){
		check_submit_css(false);
		//切换
		$(".nav_item ").bind('click',function(){
			var num=$(this).attr("data");
			$(".nav_item ").each(function(i){
				box_name="box_"+i;
				$(this).removeClass("c");
				$(".box_"+i).removeClass("show");
				if(num==i){
					$(this).addClass("c");
					$(".box_"+i).addClass("show");
				}
			});
		});
		//绑定短信发送
		$("#J_send_sms_verify").bind("click",function(){
			send_mobile_verify_sms();
		});
		//点击提交表单
		$("#user_getpwd_by_mobile").find("input[name='submit_form_up_pwd']").bind("click",function(){
			do_mobile_getpassword();
		});	
	});
	//表单提交
	function do_mobile_getpassword(){
		 
		if(!check_pwd_mobile_phone()){
			$.alert(mobile_err_info);
			return false;
		}
		if(!check_register_user_pwd()){
			$.alert(user_pwd_err_info);
			return false;
		}
		if(!check_register_confirm_user_pwd()){
			$.alert(confirm_user_pwd_err_info);
			return false;
		}
		var code_val=$.trim($("#settings_mobile_code").val());
		var mobile=$.trim($("#settings-mobile").val());
		var user_pwd=$.trim($("#user_pwd").val());
		var confirm_user_pwd=$.trim($("#confirm_user_pwd").val());
		
		var sajaxurl = APP_ROOT+"/index.php?ctl=user&act=phone_update_password";
		var squery = new Object();
		squery.code = code_val;
		squery.mobile = mobile;
		squery.user_pwd = user_pwd;
		squery.confirm_user_pwd = confirm_user_pwd;
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: sajaxurl,
		   data: squery,
		   success: function(msg){
		   		if(msg.status){
					 $.showSuccess(msg.info,function(){
					 	
					 	href = APP_ROOT+"/index.php?ctl=user&act=login";
						$.router.loadPage(href);
					 });
					 
				}else{
					 $.showErr(msg.info);
					
				}
		   }
		});
		
	}
	function check_pwd_mobile_code(){
		var code_val=$.trim($("#settings_mobile_code").val());
		var mobile=$.trim($("#settings-mobile").val());
		if(!check_pwd_mobile_phone()){
			$("#settings-mobile").focus();
				return false;
		}
		if(code_val==""){
			$.showErr("验证码不能为空");
			return false;
		}else{
			var return_val="";
			
			var sajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
			var squery = new Object();
			squery.code = code_val;
			squery.mobile = mobile;
			$.ajax({
			   type: "POST",
			   dataType: "json",
			   url: sajaxurl,
			   data: squery,
			   success: function(msg){
			   		if(msg.status){
						$.showSuccess(msg.info);
						//form_success($("#user_getpwd_by_mobile").find("input[name='verify_coder']"),msg.info);
						check_submit_css(true);
					}else{
						$.showErr(msg.info);
						//form_error($("#user_getpwd_by_mobile").find("input[name='verify_coder']"),msg.info);
						check_submit_css(false);
						
					}
			   }
			});
			return return_val;
			
		}
	}
	function check_submit_css(status){
		if(status==true){
			$(".btn_user_register").css("background-color","#00b0f5");
			$(".btn_user_register").css("cursor","pointer");
			$(".btn_user_register").removeAttr("disabled");
		}else{
			$(".btn_user_register").css("background-color","#ccc");
			$(".btn_user_register").css("cursor","default");
			$(".btn_user_register").attr("disabled","disabled");
		}
	}
	function send_mobile_verify_sms(){
		 
		$("#J_send_sms_verify").unbind("click");
		
			var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobie_pwd_sncode_new";
			var squery = new Object();
			squery.mobile = $.trim($("#settings-mobile").val());
			$.ajax({ 
				url: sajaxurl,
				data:squery,
				type: "POST",
				dataType: "json",
				success: function(sdata){
						
					if(sdata.status==1)
					{
						code_lefttime = 60;
						code_lefttime_func();
						$.showSuccess(sdata.info);
						return false;
					}
					else
					{
							
						$("#J_send_sms_verify").bind("click",function(){
							send_mobile_verify_sms();
						});
						$.showErr(sdata.info);
						return false;
					}
				}
			});	
	}
	function user_getpwd_by_mobile(){
		$("#user_getpwd_by_mobile").find("input[name='submit_form']").bind("click",function(){
			do_mobile_getpassword();
		});
	}

	function form_error(obj,str)
	{
		
		$(obj).parent().find(".tip_box").html("<div class='form_error'>"+str+"</div>");
	}
	function form_success(obj,str)
	{
		$(obj).parent().find(".tip_box").html("<div class='form_success'>"+str+"</div>");
	}
	//检测 密码
	var user_pwd_err_info;
	function check_register_user_pwd()
	{
		if($.trim($("#user_getpwd_by_mobile").find("input[name='user_pwd']").val())=="")
		{
			user_pwd_err_info = "请输入会员密码！";
			return false;
		}
		else if($.trim($("#user_getpwd_by_mobile").find("input[name='user_pwd']").val()).length<4)
		{
			user_pwd_err_info = "密码不得小于四位！";
			return false;
		}
		else
		{
			form_success($("#user_getpwd_by_mobile").find("input[name='user_pwd']"),"");
			return true;
		}
		
	}
	//检测确认密码
	var confirm_user_pwd_err_info;
	function check_register_confirm_user_pwd()
	{
		if($.trim($("#user_getpwd_by_mobile").find("input[name='confirm_user_pwd']").val())!=$.trim($("#user_getpwd_by_mobile").find("input[name='user_pwd']").val()))
		{
			confirm_user_pwd_err_info = "确认密码失败！";
			return false;
		}
		else
		{
			form_success($("#user_getpwd_by_mobile").find("input[name='confirm_user_pwd']"),"");
			return true;
		}
		 
	}
	//检测手机号码
	var mobile_err_info;
	function check_pwd_mobile_phone(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			mobile_err_info="手机号码格式错误！";
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			mobile_err_info="长度不能超过11位！";
			return false;
		}
		
		
		if($.trim($("#settings-mobile").val()).length == 0)
		{	
			mobile_err_info="手机号码不能为空！";	
			return false;
		}
		form_success($("#user_getpwd_by_mobile").find("input[name='mobile']"),"");
		return true;
	}
	function code_lefttime_func(){
		clearTimeout(code_timeer);
		$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
		$("#J_send_sms_verify").css("color","#999");
		$("#J_send_sms_verify").addClass("bg_eee").removeClass("bg_red");
		code_lefttime--;
		if(code_lefttime >0){
			$("#J_send_sms_verify").attr("disabled","disabled");
			code_timeer = setTimeout(code_lefttime_func,1000);
		}
		else{
			code_lefttime = 60;
			$("#J_send_sms_verify").removeAttr("disabled");
			$("#J_send_sms_verify").val("发送验证码");
			$("#J_send_sms_verify").css("color","#fff");
			$("#J_send_sms_verify").addClass("bg_red").removeClass("bg_eee");
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
		}
	}
});
$(document).on("pageInit","#deal-add_update", function(e, pageId, $page) {
	bind_update_form();
	get_file_fun("image_file");
	get_file_fun("update_log_icon_bj");
	function bind_update_form()
	{
		$("#add_update_form").find(".ui-button").bind("click",function(){
			$("#add_update_form").submit();
		});
		$("#add_update_form").bind("submit",function(){
			var ajaxurl = $(this).attr("action");
			var query = $(this).serialize() ;
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$.closeModal();
						if(ajaxobj.info!="")
						{
							$.showSuccess(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									href = ajaxobj.jump;
									$.router.loadPage(href);
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								href = ajaxobj.jump;
								$.router.loadPage(href);
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		});
	}
});
$(document).on("pageInit","#user-user_bind_mobile", function(e, pageId, $page) {
	var code_timeer = null;
	$("#J_send_sms_verify").bind("click",function(){
		if($("#settings-mobile").val()==''){
			$.showErr("手机号码不能为空！");
			return false;
		}else{
			send_mobile_verify_sms();
		}
	});
	$("#verify_coder").bind("blur",function(){	
		if($(this).val()==''){
			$.showErr("验证码不能为空！");
			return false;
		}else{
			check_register_verifyCoder();
		}		
	});
	
	function send_mobile_verify_sms(){
		$("#J_send_sms_verify").unbind("click");
	
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}
		
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$("#settings-mobile").focus();
			$("#settings-mobile").next().show().text("长度不能超过11位");			
			$("#settings-mobile").next().css({"color":"red"});
			$("#J_send_sms_verify").bind("click",function(){
				
				send_mobile_verify_sms();
			});
			return false;
		}
 		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
			return false;
		}
	
		var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_mobile_verify_code&is_only=1";
		var squery = new Object();
		squery.mobile = $.trim($("#settings-mobile").val());
		$.ajax({ 
			url: sajaxurl,
			data:squery,
			type: "POST",
			dataType: "json",
			success: function(sdata){
				if(sdata.status==1)
				{
					code_lefttime = 60;
					code_lefttime_func();
					$.showSuccess(sdata.info);
					return false;
				}
				else
				{
						
					$("#J_send_sms_verify").bind("click",function(){
						send_mobile_verify_sms();
					});
					$.showErr(sdata.info);
					return false;
				}
			}
		});	
		
		
	}

	function code_lefttime_func(){
		
		clearTimeout(code_timeer);
		$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
		$("#J_send_sms_verify").css({"color":"#f1f1f1"});
		code_lefttime--;
		if(code_lefttime >0){
			$("#J_send_sms_verify").attr("disabled","true");
			code_timeer = setTimeout(code_lefttime_func,1000);
		}
		else{
			code_lefttime = 60;
			$("#J_send_sms_verify").val("发送验证码");
			$("#J_send_sms_verify").attr("disabled","false");
			$("#J_send_sms_verify").css({"color":"#fff"});
			$("#J_send_sms_verify").bind("click",function(){
				send_mobile_verify_sms();
			});
		}	
	}
	//检查验证码
	function check_register_verifyCoder(){
 		if($.trim($("#verify_coder").val())=="")
		{
			$.showErr("请输入验证码!");		
		}
		else
		{
			var mobile = $.trim($("#settings-mobile").val());
			var code = $.trim($("#verify_coder").val());
			if(mobile!=""||code!=""){
				var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
				var query = new Object();
				query.mobile = mobile;
				query.code = code;
				$.ajax({
					url: ajaxurl,
					dataType: "json",
					data:query,
					type: "POST",
					success:function(ajaxobj){
						if(ajaxobj.status==1)
						{
							//$.showSuccess("验证码正确!");
						}
						if(ajaxobj.status==0)
						{
							$.showErr("验证码不正确!");
						}
					}
				});
			}
		}
	}
	function save_mobile(){
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$.showErr("手机号码格式错误!");	
			return false;
		}
		
		if(!$.maxLength($("#settings-mobile").val(),11,true))
		{
			$.showErr("长度不能超过11位!");	
			return false;
		}
 		if($.trim($("#settings-mobile").val()).length == 0)
		{				
			$.showErr("手机号码不能为空!");
			return false;
		}
		if($.trim($("#verify_coder").val()).length == 0){
			$.showErr("验证码不能为空！");
			return false;
		}
		var mobile = $.trim($("#settings-mobile").val());
		var cid= $.trim($("#cid").val());
		var verify_coder=$.trim($("#verify_coder").val());
		var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=save_mobile";
		var query=new Object();
		query.mobile=mobile;
		query.cid=cid;
		query.verify_coder=verify_coder;
		$.ajax({
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success:function(ajaxobj){
				if(ajaxobj.status==1)
				{
					href=APP_ROOT+"/index.php?ctl=ajax&act=three_seconds_jump&id="+cid;
					$.router.loadPage(href);
				}
				if(ajaxobj.status==0)
				{
					$.showErr(ajaxobj.info);
				}
			}
		});
		return false;
	}
});
$(document).on("pageInit","#settings-invite", function(e, pageId, $page) {
	$(".J_del_invite").on('click',function(){
		var invite_item_id = $(this).attr("rel");
		del_invite(invite_item_id);
	});
	function del_invite(id){
		var post_url=APP_ROOT+"/index.php?ctl=settings&act=del_invite&id="+id;
		$.ajax({
			url:post_url,
			dataType:"json",
			type:"post",
			success:function(data){
				if(data.status==1){
					$.showSuccess(data.info,function(){
						$.router.loadPage(window.location.href);
					});
				}else{
					$.showErr(data.info);
				}
			}
		});
	}
});
$(document).on("pageInit","#settings-password", function(e, pageId, $page) {
	if(USER_VERIFY == 2){
		$(".J_save_mobile_password").on('click',function(){
			save_mobile_password();
		});

		var code_timeer = null;
		var code_lefttime = 0;

		$("#J_send_sms_verify").on("click",function(){
			send_mobile_verify_sms();
		});
		$("#setting_mobile_pwd_form").find("input[name='verify_coder']").bind("blur",function(){
			check_register_verifyCoder();
		});
		function form_error(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_tip'>"+str+"</div>");
		}
		function form_success(obj,str)
		{
			$(obj).parent().find(".tip_box").html("<div class='form_success'>"+str+"</div>");
		}
		function send_mobile_verify_sms(){
			$("#J_send_sms_verify").unbind("click");
		
			if(!$.checkMobilePhone($("#settings-mobile").val()))
			{
				form_error($("#settings-mobile"),"手机号码格式错误");	
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
			
			
			if(!$.maxLength($("#settings-mobile").val(),11,true))
			{
				$("#settings-mobile").focus();
				$("#settings-mobile").next().show().text("长度不能超过11位");			
				$("#settings-mobile").next().css({"color":"red"});
				$("#J_send_sms_verify").bind("click",function(){
					
					send_mobile_verify_sms();
				});
				return false;
			}
	 		if($.trim($("#settings-mobile").val()).length == 0)
			{				
				form_error($("#settings-mobile"),"手机号码不能为空");
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
				return false;
			}
		
			var sajaxurl ='{url_wap r="ajax#send_mobile_verify_code"}';
			var squery = new Object();
			squery.mobile = $.trim($("#settings-mobile").val());
			$.ajax({ 
				url: sajaxurl,
				data:squery,
				type: "POST",
				dataType: "json",
				success: function(sdata){
					if(sdata.status==1)
					{
						code_lefttime = 60;
						code_lefttime_func();
						$.showSuccess(sdata.info);
						return false;
					}
					else
					{
							
						$("#J_send_sms_verify").bind("click",function(){
							send_mobile_verify_sms();
						});
						$.showErr(sdata.info);
						return false;
					}
				}
			});
		}
		function code_lefttime_func(){
			clearTimeout(code_timeer);
			$("#J_send_sms_verify").val(code_lefttime+"秒后重新发送");
			$("#J_send_sms_verify").css({"color":"#f1f1f1"});
			code_lefttime--;
			if(code_lefttime >0){
				$("#J_send_sms_verify").attr("disabled","true");
				code_timeer = setTimeout(code_lefttime_func,1000);
			}
			else{
				code_lefttime = 60;
				$("#J_send_sms_verify").val("发送验证码");
				$("#J_send_sms_verify").attr("disabled","false");
				$("#J_send_sms_verify").css({"color":"#fff"});
				$("#J_send_sms_verify").bind("click",function(){
					send_mobile_verify_sms();
				});
			}
		}
		//检查验证码
		function check_register_verifyCoder(){
	 		if($.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val())=="")
			{
				form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"请输入验证码");		
			}
			else
			{
				var mobile = $.trim($("#setting_mobile_pwd_form").find("input[name='mobile']").val());
				var code = $.trim($("#setting_mobile_pwd_form").find("input[name='verify_coder']").val());
				if(mobile!=""||code!=""){
					var ajaxurl = APP_ROOT+"/index.php?ctl=user&act=check_verify_code";
					var query = new Object();
					query.mobile = mobile;
					query.code = code;
					$.ajax({
						url: ajaxurl,
						dataType: "json",
						data:query,
						type: "POST",
						success:function(ajaxobj){
							if(ajaxobj.status==1)
							{
								form_success($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码正确");
							}
							if(ajaxobj.status==0)
							{
								form_error($("#setting_mobile_pwd_form").find("input[name='verify_coder']"),"验证码不正确");
							}
						}
					});
				}
			}
		}
		
		function save_mobile_password(){
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var verify_coder=$("#verify_coder").val();
			var post_url=APP_ROOT+"/index.php?ctl=settings&act=save_mobile_password";
			var query = new Object();
				query.user_pwd = user_pwd;
				query.confirm_user_pwd = confirm_user_pwd;
				query.verify_coder=verify_coder;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
					success:function(data){
						if(data.info!=null){
							alert(data.info);
						}
						else{
							if(data.status==1){
								alert("保存成功!",function(){
									$.router.loadPage(window.location.href);
								});
							}
							if(data.status==0){
								alert("保存失败!");
							}
						}
				},error:function(){
					alert("系统繁忙，稍后请重试!");
				}
			});
		}
	}
	else{
		$(".J_save_password").on('click',function(){
			save_password();
		});
		function save_password(){
			var user_old_pwd=$("#user_old_pwd").val();
			var user_pwd=$("#user_pwd").val();
			var confirm_user_pwd=$("#confirm_user_pwd").val();
			var post_url='{url_wap r="settings#save_password"}';
			
			var query=new Object();
			query.user_old_pwd=user_old_pwd;
			query.user_pwd=user_pwd;
			query.confirm_user_pwd=confirm_user_pwd;
			$.ajax({
				url:post_url,
				dataType:"json",
				data:query,
				type:"post",
				success:function(data){
					if(data.info!=null){
						$.showErr(data.info);
					}else{
						if(data.status==1){
							$.showSuccess("保存成功!",function(){
								$.router.loadPage(window.location.href);
							});
						}
						if(data.status==0){
							$.showSuccess("保存失败!");
						}
					}
				},
				error:function(){
					$.showErr("系统繁忙，稍后请重试!");
				}
			});
			return false;
		}
	}
});
$(document).on("pageInit","#finance-index", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate();
});
$(document).on("pageInit","#deal-show", function(e, pageId, $page) {
	// 查看更多回报
    $(".view_more_return_item").find(".item-link").on('click',function(){
      	$(".return_item").addClass("return_more_item");
      	$(".view_more_return_item").remove();
      	$.refreshScroller();
    });

    $(".J_lottery_pop").on('click',function(){
    	lottery_pop(item_id,item_price_format);
    });

    //抽奖
	function lottery_pop(deal_item_id,price){
		$.ajax({
			url:APP_ROOT+'/index.php?ctl=ajax&act=go_lottery_num&item_id='+deal_item_id,
			type:"GET",
			data:'',
			dataType:'json',
			success:function(o){
				if(o.status ==-1){
					$.showErr("请先登录",function(){
						href=APP_ROOT+'/index.php?ctl=user&act=login&deal_id='+deal_info_id;
						$.router.loadPage(href);
					});
				}
				else if(o.status ==1){
					$.modal({
						title: '抽奖¥'+price,
				      	text: o.html,
				      	buttons: []
					});
					bind_lottery();
				}
				else{
					$.showErr(o.info);
				}
					
			}
		});
	}
	function bind_lottery(){
		//minus 减
		$("#minus").bind('click',function(){
			var num=parseInt($("#buy_num").val());
			var hidden_tip=0;
			if(num <=1)
				num=1;
			else
			{
				num -=1
			}
			if(maxbuy >0 && remain_user_buy ==0)
			{
				$("#buy_num").val(0);
				$("#buy_tip").removeClass("hidden");
				$("#buy_tip").html("你的支持数已达到上限");
			}else{
				$("#buy_num").val(num);
			
				var buy_tip_view=$("#buy_tip").is(":visible");
				if(buy_tip_view)
				{
					if(is_limit_user ==1 && limit_user >0)
					{
						var useful_count =limit_user - support_count;
						if(useful_count <0) useful_count=0;
						
						if((maxbuy >0 &&  useful_count <=remain_user_buy && num<= useful_count) || (maxbuy >0 &&  useful_count >remain_user_buy && num<= remain_user_buy) )
						{
							$("#buy_tip").addClass("hidden");
						}
						else
						{
							if(num <=useful_count)
								$("#buy_tip").addClass("hidden");
						}
					}else if(maxbuy >0 && num <=maxbuy)
					{
						$("#buy_tip").addClass("hidden");
					}
				}
			}
			
			
		});
		
		//plus 加
		$("#plus").bind('click',function(){
			var num=parseInt($("#buy_num").val());
			var buy_tip='';
			var num_view =num+1;
			if (maxbuy > 0 && remain_user_buy == 0) {
				$("#buy_num").val(0);
				$("#buy_tip").removeClass("hidden");
				$("#buy_tip").html("你的支持数已达到上限");
			}
			else {
				if( maxbuy >0 && num >=remain_user_buy)
				{
					num_view=remain_user_buy;
					buy_tip='最多抽'+remain_user_buy+'次';
				}
		
				if(is_limit_user ==1 && limit_user >0)
				{
					var useful_count =limit_user - support_count;
					if(useful_count <0)
						useful_count=0;
						
					if(num_view >useful_count)
					{
						num_view=useful_count;
						buy_tip='库存不足，最多抽'+useful_count+'次';
					}
				}
				
				$("#buy_num").val(num_view);
				if(buy_tip !='')
				{
					$("#buy_tip").show();
					$("#buy_tip").html(buy_tip);
					setTimeout(function(){
						$("#buy_tip").fadeOut("slow");
					},1000)
				}else
				{
					$("#buy_tip").hide();
				}
			}
			
		});
		
		//buy_num change
		$("#buy_num").bind('change',function(){
			var num=parseInt($("#buy_num").val());
			var buy_tip='';
			var num_view =num;
			//alert(num);
			if (maxbuy > 0 && remain_user_buy == 0) {
				$("#buy_num").val(0);
				$("#buy_tip").removeClass("hidden");
				$("#buy_tip").html("你的支持数已达到上限");
			}
			else {
				//limit_user remain_user_buy maxbuy
				if( maxbuy >0 && num > remain_user_buy)
				{	
					num_view=remain_user_buy;
					buy_tip='最多抽'+remain_user_buy+'次';
				}
				 
				if(is_limit_user ==1 && limit_user >0)
				{
					var useful_count =limit_user - support_count;
					if(useful_count <0)
						useful_count=0;
						
					if(num_view >useful_count)
					{
						num_view=useful_count;
						buy_tip='库存不足，最多抽'+useful_count+'次';
					}
				}
				
				$("#buy_num").val(num_view);
				if(buy_tip !='')
				{
					$("#buy_tip").show();
					$("#buy_tip").html(buy_tip);
					setTimeout(function(){
						$("#buy_tip").fadeOut("slow");
					},1000)
				}else
				{
					$("#buy_tip").hide();
				}
			}
		});
		
		$("input[name='lottery_go_cart']").bind('click',function(){
			var num=parseInt($("input[name='num']").val());
			if(num <=0)
			{
				showErr("请输入数量");
				return false;
			}
			
			$("#ajax_form_lottery").submit();
		});
	}
	
	$(".button_n").bind("click",function(){
		$.closeModal();
	});
});
$(document).on("pageInit","#finance-company_show", function(e, pageId, $page) {
	$(".J_view_all").on('click',function(){
		J_view_all(this);
	});
	$(".J_attention_focus_company").on('click',function(){
		attention_focus_company(this);
	});
	function attention_focus_company(obj){
		cid=$(obj).attr("cid");
		var ajaxurl = APP_ROOT+"/index.php?ctl=finance&act=focus&cid="+cid;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					$(obj).addClass("active").html("取消关注");
				}
				else if(ajaxobj.status==2)
				{
					$(obj).removeClass("active").html("关注");
				}
				else if(ajaxobj.status==3)
				{
					$.showErr(ajaxobj.info);							
				}
				else
				{
					show_login();
				}
			},
			error:function(ajaxobj)
			{
	//			if(ajaxobj.responseText!='')
	//			alert(ajaxobj.responseText);
			}
		});
	}
});
$(document).on("pageInit","#licai-deal", function(e, pageId, $page) {
    fun_money();
    leftTimeAct("#left_time");
	
	function fun_money(){
        // 预期一天收益
        var $deal_top_r_bd=$("#deal_top_r_bd"),
            $min_money=$deal_top_r_bd.find("input[name='min_money']"),
            $money=$deal_top_r_bd.find("input[name='money']"),
            $income_money=$deal_top_r_bd.find("input[name='income_money']"),
            endTime = parseInt($("#left_time").attr("data"))+3600*24,
            leftTime = endTime - system_time;
            
        if(!($money.val())){
            $income_money.attr("value",0);
        }
		$("#money").keyup(function(){
       
            var money_val= $.trim($("#money").val());	
            if(parseFloat($("#user_left_money").attr("data")) < parseFloat(money_val)){
                $("#user_left_money_tip").show();
            }
            else{
                $("#user_left_money_tip").hide();
            }
			
            if(licai_type > 0){
                if(parseInt(licai_interest_json[licai_interest_json.length - 1]['max_money']) <= money_val){
                    income_money_val = parseFloat(licai_interest_json[licai_interest_json.length - 1]['interest_rate']);
                    before_money_val = parseFloat(licai_interest_json[licai_interest_json.length - 1]['before_rate']);
                    site_buy_fee_rate= parseFloat(licai_interest_json[licai_interest_json.length - 1]['site_buy_fee_rate']);
                    redemption_fee_rate= parseFloat(licai_interest_json[licai_interest_json.length - 1]['redemption_fee_rate']);
                }
                else{
                    $.each(licai_interest_json,function(i,v){
                        if(parseInt(v['min_money']) <= money_val && parseInt(v['max_money']) > money_val){
                            income_money_val = parseFloat(v['interest_rate']);
                            before_money_val = parseFloat(v['before_rate']);
                            site_buy_fee_rate= parseFloat(v['site_buy_fee_rate']);
                            redemption_fee_rate= parseFloat(v['redemption_fee_rate']);
                        }
                    });
                }
            }
            else{
                income_money_val = licai_interest_json;
            }

            $("#verify_money").html(money_val);
            if(money_val){
				
                if(licai_type > 0){
                    var normal_rate=income_money_val/100;  // 正常利率
                    var preheat_rate=before_money_val/100;  // 预热利率
                    var procedures_rate=site_buy_fee_rate/100;  // 网站手续费率
                    var redemption_rate=redemption_fee_rate/100;  // 赎回手续费率
                    var new_money_val=money_val-money_val*procedures_rate;  // 扣除手续费后金额
                    
                    // 收益
                    var income_money=(new_money_val*normal_rate*buy_day)/365 + (new_money_val*preheat_rate*before_day)/365;
                    var redemption_money=((new_money_val)*redemption_rate*(buy_day+before_day))/365; // 赎回手续费
                    var new_income_money=(income_money-redemption_money).toFixed(2);
                    $income_money.attr("value",new_income_money);
					$(".J_u_money_sy").html(new_income_money);
                }
                else
                {
                    var redemption_fee_rate = income_money_val['redemption_fee_rate'];
                    var site_buy_fee_rate = income_money_val['site_buy_fee_rate'];
                    var platform_rate = income_money_val['platform_rate'];
                    var average_income_rate = income_money_val['average_income_rate']
                    var procedures_rate=site_buy_fee_rate/100;  // 网站手续费率
                    var redemption_rate=redemption_fee_rate/100;  // 赎回手续费率
                    var preheat_rate = average_income_rate/100; //收益
                    var new_money_val=money_val-money_val*procedures_rate;  // 扣除手续费后金额
                    //收益
                    var income_money= (new_money_val*preheat_rate*buy_day)/365;
                    var redemption_money=(new_money_val)*redemption_rate*buy_day/365;  // 赎回手续费
                    var new_income_money=(income_money-redemption_money).toFixed(2);
                    $income_money.attr("value",new_income_money);
					$(".J_u_money_sy").html(new_income_money);  
                }
            }
        });
        
		
        // 我要投资
        buy();
        function buy(){
			
           $("#pay_deal").click(function(){
		   	  var id= $.trim($("#id").val());
			  var money_val= $.trim($("#money").val());
			  var min_money= $.trim($("#min_money").val());  
			  var tc_money= $.trim($("#tc_money").val()); 
			  
                if(endTime!=0&&leftTime<=0){
                    $.alert("项目已结束！");
                    return false;
                }
                if($deal_top_r_bd.find("input[name='own_pro']").length){
                    $.alert("不能购买自己发布的理财产品！");
                    return false;
                }
                if(parseFloat(tc_money) < parseFloat(money_val)){
                    $.alert("您的账户余额不足！");
                    return false;
                }
                if(!(money_val)){
					 $.alert("请输入金额！");
                    return false;
                }
                if(parseFloat(money_val) < parseFloat(min_money)){
                    $.alert("最低金额不能低于"+ min_money +"元");
                    return false;
                }
                else if(!($.trim($("#pay_inmoney_password").val()))){
                    $.alert("请输入付款密码！");
                    return false;
                }
                else{
                    var ajaxurl = '{url_wap r="licai#bid"}';
			        var query = new Object();
			        
			        query.id = $.trim($("#id").val());
			        query.money = $.trim($("#money").val());
			        query.paypassword = $.trim($("#pay_inmoney_password").val());
			        query.post_type = "json";
			        $.ajax({
			            url:ajaxurl,
			            data:query,
			            type:"Post",
			            dataType:"json",
			            success:function(data){
                            if(data.status==1){
                                $.showSuccess(data.info,function(){	
									var href= APP_ROOT+'/index.php?ctl=licai&act=uc_buyed_lc';
                                    $.router.loadPage(href);
								});
                            }else{
                                $.showErr(data.info);
                            }
			            }
			        });
                }
            });
            
        }
    }
	
   // 项目剩余时间倒计时
    function leftTimeAct(left_time){
        var leftTimeActInv = null;
        clearTimeout(leftTimeActInv);
        $(left_time).each(function(){
			var endTime = parseInt($(this).attr("data"));
            var leftTime = endTime - system_time ;
            if(endTime){
                if(leftTime > 0){
                    var day  =  parseInt(leftTime / 24 /3600);
                    var hour = parseInt((leftTime % (24 *3600)) / 3600);
                    var min = parseInt((leftTime % 3600) / 60);
                    var sec = parseInt((leftTime % 3600) % 60);
                    $(this).find(".day").html((day<10?"0"+day:day));
                    $(this).find(".hour").html((hour<10?"0"+hour:hour));
                    $(this).find(".min").html((min<10?"0"+min:min));
                    $(this).find(".sec").html((sec<10?"0"+sec:sec));
                    system_time++;
                    //$(this).attr("data",leftTime);
                }
                else{
                    $(this).html("已结束");
                }
            }
            else{
                $(this).html("永久有效");
            }
        });
        leftTimeActInv = setTimeout(function(){
            leftTimeAct(left_time);
        },1000);
    }

   /* if ({$licai.type} == 0){	
		$(function(){

	        var myData = new Array(
	            {foreach from="$data.data_table" item=item name="dt"}
	                ['{$item.history_date}',{$item.rate}]{if !$smarty.foreach.dt.last},{/if}
	            {/foreach}
	        );
	        var myChart = new JSChart('data_table', 'line');
	        myChart.setAxisNameX("");
	        myChart.setAxisNameY("");
	        myChart.setIntervalStartY(0);
	        myChart.setAxisPaddingTop(10);
	        myChart.setDataArray(myData);
	        myChart.setTitle('');
	        myChart.setSize(360, 200);
	        myChart.setBarColor('#39a1ea');
	        myChart.draw();
	  		});
	}*/
});
$(document).on("pageInit","#licai-uc_buyed_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
		toolbarTemplate: '<header class="bar bar-nav">\
		  					<button class="button button-link pull-right close-picker">确定</button>\
		  					<h1 class="title">选择日期</h1>\
	  					</header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = '{url_wap  r="licai#uc_buyed_lc_status"}';
        var deal_name = $.trim($("#deal_name").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var b_b_time = $.trim($("#buy_begin_time").val());
        var b_e_time = $.trim($("#buy_end_time").val());
        
        var query = new Object();
        query.deal_name = $.trim($("#deal_name").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.b_b_time = $.trim($("#buy_begin_time").val());
        query.b_e_time = $.trim($("#buy_end_time").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
                if(ajaxobj.status==1)
                {
                    var href = APP_ROOT+'/index.php?ctl=licai&act=uc_buyed_lc&begin_time='+b_time+'&end_time='+e_time+'&buy_begin_time='+b_b_time+'&buy_end_time='+b_e_time+'&deal_name='+deal_name;
                	$.router.load(href);
                }
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
            }
        
        });
        return false;
    });
});
$(document).on("pageInit","#licai-uc_expire_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
		  toolbarTemplate: '<header class="bar bar-nav">\
		  <button class="button button-link pull-right close-picker">确定</button>\
		  <h1 class="title">选择日期</h1>\
		  </header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = '{url_wap  r="licai#uc_expire_lc_status"}';
        var deal_name = $.trim($("#deal_name").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var user_name = $.trim($("#user_name").val());
        
        var query = new Object();
        query.deal_name = $.trim($("#deal_name").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.user_name = $.trim($("#user_name").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
				
				if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_expire_lc&begin_time='+b_time+'&end_time='+e_time+'&user_name='+user_name+'&deal_name='+deal_name;
                	$.router.load(href);
                } 
                else{
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
                
            }
        
        });
          
        $(this).parents(".float_block").hide();
    });
});
$(document).on("pageInit","#licai-uc_expire_status", function(e, pageId, $page) {
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=set_status';
        var id =  $.trim($("#id").val());
        var earn_money =  $.trim($("#earn_money").val());
		var fee =  $.trim($("#fee").val());
        var query = new Object();
        query.id = $.trim($("#id").val());
        query.earn_money = $.trim($("#earn_money").val());
        query.fee = $.trim($("#fee").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(data){
                if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_expire_lc';
                    $.router.loadPage(href);
                }
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
            }
        
        });
        $(this).parents(".float_block").hide();
    });
});
$(document).on("pageInit","#licai-uc_published_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
		  toolbarTemplate: '<header class="bar bar-nav">\
		  <button class="button button-link pull-right close-picker">确定</button>\
		  <h1 class="title">选择日期</h1>\
		  </header>'
	});
 	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=uc_published_lc_status';
        var deal_name = $.trim($("#deal_name").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var b_b_time = $.trim($("#buy_begin_time").val());
        var b_e_time = $.trim($("#buy_end_time").val());

        var query = new Object();
        query.deal_name = $.trim($("#deal_name").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.b_b_time = $.trim($("#buy_begin_time").val());
        query.b_e_time = $.trim($("#buy_end_time").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
    		success:function(ajaxobj){

    			if(ajaxobj.status==1)
    			{
    				var href = APP_ROOT+'/index.php?ctl=licai&act=uc_published_lc&begin_time='+b_time+'&end_time='+e_time+'&buy_begin_time='+b_b_time+'&buy_end_time='+b_e_time+'&deal_name='+deal_name;
    				$.router.loadPage(href);
    			}
    			else
    			{
    				if(ajaxobj.info!="")
    				{
    					$.alert(ajaxobj.info);	
    				}						
    			}
    		}
    		/*error:function(ajaxobj)
    		{
    			if(ajaxobj.responseText!='')
    			alert(ajaxobj.responseText);
    		}*/
    	
        });
        return false;
        // $(this).parents(".float_block").hide();
    });
});
$(document).on("pageInit","#licai-uc_record_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
		  toolbarTemplate: '<header class="bar bar-nav">\
		  <button class="button button-link pull-right close-picker">确定</button>\
		  <h1 class="title">选择日期</h1>\
		  </header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=uc_record_lc_status';
    	var id = $.trim($("#id").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var b_b_time = $.trim($("#buy_begin_time").val());
        var b_e_time = $.trim($("#buy_end_time").val());
        
        var query = new Object();
    	query.id = $.trim($("#id").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.b_b_time = $.trim($("#buy_begin_time").val());
        query.b_e_time = $.trim($("#buy_end_time").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
                if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_record_lc&begin_time='+b_time+'&end_time='+e_time+'&buy_begin_time='+b_b_time+'&buy_end_time='+b_e_time+'&id='+id;
                	$.router.loadPage(href);
                }
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
            }
        
        });
        return false; 
        // $(this).parents(".float_block").hide();
    });
});
$(document).on("pageInit","#licai-uc_redeem", function(e, pageId, $page) {
	$("#redeem_money").bind("keyup",function(event){
        code = event.keyCode;
        if(parseFloat($("#redeem_money").val())>parseFloat($("#have_money").attr("title")))
        {
            $("#redeem_money").val($("#have_money").attr("title"));
            $.alert("赎回的金额不能大于持有本金");
        }
        money = $("#back_rate").html() * $("#redeem_money").val();
        if(isNaN(money))
        {
            money = 0;
        }
        fun_money();
    });
    
	//计算
    var before_rate = 0;
    var before_breach_rate = 0;
    var breach_rate = 0;
    var interest_rate = 0;

    function fun_money(){
        $money = $("#redeem_money");
        var money_val=$money.val();
        
        if(licai_type > 0){
            if(licai_interest_json[licai_interest_json.length - 1]['max_money'] < money_val){
                before_rate = licai_interest_json[licai_interest_json.length - 1]['before_rate'];
                before_breach_rate = licai_interest_json[licai_interest_json.length - 1]['before_breach_rate'];
                breach_rate = licai_interest_json[licai_interest_json.length - 1]['breach_rate'];
                interest_rate = licai_interest_json[licai_interest_json.length - 1]['interest_rate'];
            }
            else{
                $.each(licai_interest_json,function(i,v){
                    
                    if( parseFloat(v['min_money']) < parseFloat(money_val) && parseFloat(v['max_money']) > parseFloat(money_val)){
                        before_rate = v['before_rate'];
                        before_breach_rate = v['before_breach_rate'];
                        breach_rate = v['breach_rate'];
                        interest_rate = v['interest_rate'];
                    }
                });
            }
        }
        else{
            income_money_val = licai_interest_json;
        }
        if(money_val){
	        if(licai_type > 0){
	            if(licai_status == 0){
                 	//预热期违约收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_breach_rate / 365 / 100 * (before_days);
                  	//理财期收益
                  	arrival_earn = 0;
                  	$("#q_rate").html(before_breach_rate+"%");
	            }
	            else if(licai_status == 1){
                   	//预热期完成收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_rate / 365 / 100 * (before_days);
                  	//理财期违约收益
                  	arrival_earn = parseFloat($("#redeem_money").val()) * breach_rate / 365 / 100 * (days);
                  	$("#q_rate").html(breach_rate+"%");
	            }
	            else if(licai_status == 2){
                   	//预热期完成收益
                  	before_arrival_earn = parseFloat($("#redeem_money").val()) * before_rate / 365 / 100 * (before_days);
                  	//理财期完成收益
                  	arrival_earn = parseFloat($("#redeem_money").val()) * interest_rate / 365 / 100 * (days);
                  	$("#q_rate").html(interest_rate+"%");
	            }
	       	}
	       	else{
	            before_arrival_earn = 0;
	            arrival_earn = income_money_val*money_val/365/100;
	        }
          	//预计收益
          	arrival_amount = parseFloat($("#redeem_money").val())+ before_arrival_earn + arrival_earn;
          	$("#redeem_interest_money").html(arrival_earn.toFixed(2) +"元");
          	$("#expect_amount").html(arrival_amount.toFixed(2));   //预计到账金额
          	$("#expect_before_earn").html(before_arrival_earn.toFixed(2));     //预计收益
          	$("#expect_earn").html(arrival_earn.toFixed(2));   //预计理财收益
        }
    }
    
    $("#redeem_btn").click(function(){
    
		var ajaxurl = '{url_wap r="licai#uc_redeem_add"}';
        if(!$.trim($("input[name='redeem_money']").val()))
        {			 
            $.alert("请输入要赎回的金额");
            return false;
        }
        if(!$.trim($("input[name='paypassword']").val()))
        {
            $.alert("请输入付款密码");
            return false;
        }
        var id = $("#id").val();
		var redeem_money =  $.trim($("#redeem_money").val());
        var paypassword =  $.trim($("#paypassword").val());
        var query = new Object();
        query.id = $.trim($("#id").val());
		query.redeem_money = $.trim($("#redeem_money").val());
        query.paypassword = $.trim($("#paypassword").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(data){
				if(data.status == 1){
					var href = APP_ROOT+'/index.php?ctl=licai&act=uc_buyed_lc&id='+id;
					$.router.loadPage(href);
				}else{
	               if(data.info!="")
                    {
                        $.alert(data.info);   
                    }     
				};
            }
        
        });
     });
});
$(document).on("pageInit","#licai-uc_redeem_lc", function(e, pageId, $page) {
	$("#buy_begin_time,#buy_end_time,#begin_time,#end_time").datetimePickers({
	  toolbarTemplate: '<header class="bar bar-nav">\
	  <button class="button button-link pull-right close-picker">确定</button>\
	  <h1 class="title">选择日期</h1>\
	  </header>'
	});
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc_statu';
        var deal_name = $.trim($("#deal_name").val());
        var b_time = $.trim($("#begin_time").val());
        var e_time = $.trim($("#end_time").val());
        var user_name = $.trim($("#user_name").val());
        
        var query = new Object();
        query.deal_name = $.trim($("#deal_name").val());
        query.b_time = $.trim($("#begin_time").val());
        query.e_time = $.trim($("#end_time").val());
        query.user_name = $.trim($("#user_name").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
               if(ajaxobj.status==1)
                {
                	var href = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc&begin_time='+b_time+'&end_time='+e_time+'&user_name='+user_name+'&deal_name='+deal_name;
                	$.router.loadPage(href);
                } 
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
			    
            }
        
        });
          
        $(this).parents(".float_block").hide();
    });
});
$(document).on("pageInit","#licai-uc_redeem_lc_status", function(e, pageId, $page) {
	$("#submitt").on('click',function(){
        var ajaxurl = APP_ROOT+'/index.php?ctl=licai&act=set_redeem_lc_status';
        var redempte_id =  $.trim($("#redempte_id").val());
        var earn_money =  $.trim($("#earn_money").val());
        var fee =  $.trim($("#fee").val());
        var query = new Object();
        query.redempte_id = $.trim($("#redempte_id").val());
        query.earn_money = $.trim($("#earn_money").val());
        query.fee = $.trim($("#fee").val());
        
        query.post_type = "json";
        $.ajax({
            url:ajaxurl,
            data:query,
            type:"Post",
            dataType:"json",
            success:function(ajaxobj){
                if(ajaxobj.status==1)
                {
                    var href = APP_ROOT+'/index.php?ctl=licai&act=uc_redeem_lc';
                    $.router.loadPage(href);
                }
                else
                {
                    if(ajaxobj.info!="")
                    {
                        $.alert(ajaxobj.info);   
                    }                       
                }
            }
        
        });
    });
});
$(document).on("pageInit","#score_good_show-check_order", function(e, pageId, $page) {
	$("select[name='province']").on("change",function(){
        load_city();
    });
    $("input[name='consignee_id']").on('click',function(){
		var consignee_id=parseInt($(this).val());
		if(consignee_id ==0)
		{
			$("#address_box").show();
		}
		else{
			$("#address_box").hide();
		}
	});
    $("#score_do_order").on("click",function(){
        var query = new Object();
        query.ajax=1;
        query.id=$("input[name='id']").val();
        query.number=$("input[name='number']").val();
        query.memo=$("textarea[name='memo']").val();
        if(is_delivery ==1)
        {
            if(have_consignee ==1)
                query.consignee_id=$("input[name='consignee_id']:checked").val();
            else
                query.consignee_id=0;   
            if(query.consignee_id == 0)
            {   
                query.delivery_name = $("input[name='delivery_name']").val();
                query.delivery_province = $("select[name='province']").val();
                query.delivery_city = $("select[name='city']").val();
                query.delivery_addr = $("textarea[name='delivery_addr']").val();
                query.delivery_zip = $("input[name='delivery_zip']").val();
                query.delivery_tel = $("input[name='delivery_tel']").val();
                
                if(query.delivery_name == ''){
                    $.showErr("请输入收货人名称");
                    return false;
                }
                if(query.delivery_province ==''){
                    $.showErr("请选择省份");
                    return false;
                }
                if(query.delivery_city ==''){
                    $.showErr("请选择城市");
                    return false;
                }
                if(query.delivery_addr == ''){
                    $.showErr("请输入详细地址");
                    return false;
                }
                if(query.delivery_tel == ''){
                    $.showErr("请输入手机号码");
                    return false;
                }
            }
            query.delivery_time=$("input[name='delivery_time']:checked").val(); 
        }
        
        query.paypassword=$("input[name='paypassword']").val();
        if(query.paypassword == ''){
            $.showErr("请输入付款密码");
            return false;
        }
        
        var ajax_url=APP_ROOT+"/index.php?ctl=score_good_show&act=do_score_order";
        $.ajax({
            url:ajax_url,
            data:query,
            dataType: "json",
            type: "post",
            success:function(o){
                if(o.status ==-1){
                    show_login();
               	}
                else if(o.status == 1){
                    if(o.jump){
                        $.showSuccess(o.info,function(){
                            $.router.loadPage(o.jump);
                        });
                    }
                    else{
                        $.showSuccess(o.info);
                    }
                }
                else{
                    if(o.jump){
                        $.showErr(o.info,function(){
                            $.router.loadPage(o.jump);
                        });
                    }
                    else{
                        $.showErr(o.info);
                    }
                }   
            }
        });
        
    });
});
$(document).on("pageInit","#score_good_show-index", function(e, pageId, $page) {
	$("#go_check_order").on('click',function(){
		var num=parseInt($("input[name='num']").val());
		if(!parseInt(is_login))
		{
			$.showErr("请先登录",function(){
				$.router.loadPage(login_url);
			});
			return false;
		}
		
		if(!num)
		{
			$.showErr("请填写数量");
			return false;
		}
		$("form[name='score_form']").submit();
	});
	
	//minus 减
	$("#minus").on('click',function(){
		var num=parseInt($("#buy_num").val());
		var hidden_tip=0;
		if(num <=1)
			num=1;
		else
		{
			num -=1
		}
		if(maxbuy >0 && remain_user_buy ==0)
		{
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}else{
			$("#buy_num").val(num);
		
			var buy_tip_view=$("#buy_tip").is(":visible");
			if(buy_tip_view)
			{
				if(is_limit_user ==1 && limit_user >0)
				{
					var useful_count =limit_user - support_count;
					if(useful_count <0) useful_count=0;
					
					if((maxbuy >0 &&  useful_count <=remain_user_buy && num<= useful_count) || (maxbuy >0 &&  useful_count >remain_user_buy && num<= remain_user_buy) )
					{
						$("#buy_tip").addClass("hidden");
					}
					else
					{
						if(num <=useful_count)
							$("#buy_tip").addClass("hidden");
					}
				}else if(maxbuy >0 && num <=maxbuy)
				{
					$("#buy_tip").addClass("hidden");
				}
			}
		}
		
		
	});
	
	//plus 加
	$("#plus").on('click',function(){
		if(!parseInt(is_login))
		{
			$.showErr("请先登录",function(){
				location.href=login_url;
			});
		}
		var num=parseInt($("#buy_num").val());
		var buy_tip='';
		var num_view =num+1;
		if (maxbuy > 0 && remain_user_buy == 0) {
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}
		else {
			if( maxbuy >0 && num >=remain_user_buy)
			{
				num_view=remain_user_buy;
				buy_tip='最多抽'+remain_user_buy+'次';
			}
	
			if(is_limit_user ==1 && limit_user >0)
			{
				var useful_count =limit_user - support_count;
				if(useful_count <0)
					useful_count=0;
					
				if(num_view >useful_count)
				{
					num_view=useful_count;
					buy_tip='库存不足，最多抽'+useful_count+'次';
				}
			}
			
			$("#buy_num").val(num_view);
			if(buy_tip !='')
			{
				$("#buy_tip").show();
				$("#buy_tip").html(buy_tip);
				setTimeout(function(){
					$("#buy_tip").fadeOut("slow");
				},1000)
			}else
			{
				$("#buy_tip").hide();
			}
		}
		
	});
	
	//buy_num change
	$("#buy_num").on('change',function(){
		var num=parseInt($("#buy_num").val());
		var buy_tip='';
		var num_view =num;
		
		//alert(num);
		if (maxbuy > 0 && remain_user_buy == 0) {
			$("#buy_num").val(0);
			$("#buy_tip").removeClass("hidden");
			$("#buy_tip").html("你的支持数已达到上限");
		}
		else {
			//limit_user remain_user_buy maxbuy
			if( maxbuy >0 && num > remain_user_buy)
			{	
				num_view=remain_user_buy;
				buy_tip='最多抽'+remain_user_buy+'次';
			}
			 
			if(is_limit_user ==1 && limit_user >0)
			{
				var useful_count =limit_user - support_count;
				if(useful_count <0)
					useful_count=0;
					
				if(num_view >useful_count)
				{
					num_view=useful_count;
					buy_tip='库存不足，最多抽'+useful_count+'次';
				}
			}
			
			$("#buy_num").val(num_view);
			if(buy_tip !='')
			{
				$("#buy_tip").show();
				$("#buy_tip").html(buy_tip);
				setTimeout(function(){
					$("#buy_tip").fadeOut("slow");
				},1000)
			}else
			{
				$("#buy_tip").hide();
			}
		}
	});
	
});
$(document).on("pageInit","#score_goods_order-index", function(e, pageId, $page) {
	$(".Unfold_open").on('click',function(){
		var $obj = $(this);
		var $li = $obj.parent().parent();
		$obj.hide();
		$li.find(".order_detail_t_other").show();
	});
	$(".Unfold_close").on('click',function(){
		var $obj = $(this);
		var $order_detail_t_other = $obj.parent();
		$order_detail_t_other.hide();
		$order_detail_t_other.parent().find(".Unfold_open").show();
	});
	
	$(".del_order").click(function(){
		order_id=$(this).attr('rel');
		$.showConfirm("你确定要取消？",function(){
			ajaxurl=APP_ROOT+"/index.php?ctl=score_goods_order&act=del_order&id="+order_id+"&ajax=1";
			$.ajax({
				url:ajaxurl,
				type: "POST",
				dataType: "json",
				success:function(o){
					if(o.status == -1)
					{
						show_login();
					}
					else if(o.status == 1)
					{
						if(o.jump){
							$.showSuccess(o.info,function(){
								$.router.loadPage(o.jump);
							});
						}
						else{
							$.showSuccess(o.info);
						}
					}else{
						if(o.jump){
							$.showErr(o.info,function(){
								$.router.loadPage(o.jump);
							});
						}
						else{
							$.showErr(o.info);
						}
					}
				}
			});
		});
	});
});

$(document).on("pageInit","#score_mall-index", function(e, pageId, $page) {
	//筛选分类 
	J_mall_cate(); 
});
