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