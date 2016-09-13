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