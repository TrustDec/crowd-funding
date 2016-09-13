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