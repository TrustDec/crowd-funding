$(document).ready(function(){
	f_company_info.del_company_introduce_img();
	$(window).scroll(function() {
		fixed_page_status();
	});
	bind_ajax_company_form(".ajax_company_default_form");
	bind_ajax_company_form(".ajax_company_intro_form");
	bind_ajax_company_form(".ajax_company_link_form");
	bind_ajax_company_form(".ajax_companty_sub_product_form");
	bind_ajax_company_form(".ajax_companty_sub_product_edit_form");
	bind_ajax_company_form(".ajax_companty_founder_team_form");
	bind_ajax_company_form(".ajax_invest_exp_form");
	bind_ajax_company_form(".ajax_company_case_form");
	
	bind_ajax_company_form(".ajax_financing_exp_form");//融资经历
	bind_ajax_company_form(".ajax_company_investor_form");//过往投资方
	bind_ajax_company_form(".ajax_company_employee_form");//团队成员
	bind_ajax_company_form(".ajax_company_past_team_form");//过往成员
});

// 投资案例伸缩
function trigger_detail(obj){
	var $obj = $(obj);
	var c_val = $obj.attr("rel");
	if($("."+c_val+"_autoheight_wrap").hasClass("autoheight_wrap")){
		$("."+c_val+"_autoheight_wrap").removeClass("autoheight_wrap");
		$obj.html("展开");
	}
	else{
		$("."+c_val+"_autoheight_wrap").addClass("autoheight_wrap");
		$obj.html("收起");
	}
}

function select_phase(obj,id,c_val){
	var phase = document.getElementById(id).value;
	if(id=="finance_phase"){
		if(phase == 9){
			$("."+id+"_amount_text").text("并购金额");
			$("."+id+"_time_text").text("并购时间");
			$("."+id+"_valuation_text").hide();
			$("."+c_val+"_form").find("input[name='phase_type']").val(1);
		}
		else if(phase == 10){
			$("."+id+"_amount_text").text("总募金额");
			$("."+id+"_time_text").text("上市时间");
			$("."+id+"_valuation_text").hide();
			$("."+id+"_subject_text").hide();
			$("."+c_val+"_form").find("input[name='phase_type']").val(2);
		}
		else{
			$("."+id+"_amount_text").text("融资金额");
			$("."+id+"_time_text").text("融资时间");
			$("."+id+"_valuation_text").show();
			$("."+id+"_subject_text").show();
			$("."+c_val+"_form").find("input[name='phase_type']").val(0);
		}
	}
	if(id=="invest_phase"){
		if(phase == 9){
			$("."+id+"_amount_text").text("并购金额");
			$("."+id+"_time_text").text("并购时间");
			$("."+id+"_invest_amount_text").hide();
			$("."+id+"_valuation_text").hide();
			$("."+c_val+"_form").find("input[name='phase_type']").val(1);
		}
		else{
			$("."+id+"_amount_text").text("此轮总投资金额");
			$("."+id+"_time_text").text("投资时间");
			$("."+id+"_invest_amount_text").show();
			$("."+id+"_valuation_text").show();
			$("."+c_val+"_form").find("input[name='phase_type']").val(0);
		}
	}
}

// 自动检索名称
function check_u_name(obj,ajaxurl,check_type){
	if($(obj).val()){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajaxurl;
        var query = new Object();
        query.name = $(obj).val();
        window.setTimeout(function(){
	        $.ajax({ 
	            url: ajaxurl,
	            dataType: "json",
	            data:query,
	            type: "POST",
	            success: function(ajaxobj){
	                if(ajaxobj.status==1)
	                {
	                	var arr_name = ajaxobj.name;
	                	var menu_width = $("."+c_val+"_input").outerWidth()-2;
	                	var menu_top = 43;
	                	var html = '';
	                	var arr_name_length = arr_name.length;

	                	// 团队成员、过往成员、创始团队
	                	if(check_type == 'c_founder_team' || check_type == 'c_past_team' || check_type == 'c_employee_team'){
	                		if(arr_name_length){
			                	$("."+c_val+"_menu").show();
			                	$("."+c_val+"_menu").css({width:menu_width+"px",top:menu_top+"px"});
			                	$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
			                	for(var i=0; i<arr_name_length; i++){
			                		html+='<div class="ui_select_choices_row clearfix">'+
										  '		<span class="row_img mr5"><img src="'+arr_name[i].image+'"></span>'+
										  '		<span class="row_text u_name">'+arr_name[i].name+'</span>'+
										  '		<input type="hidden" name="u_email" value="'+arr_name[i].email+'">'+
										  '		<input type="hidden" name="id" value="'+arr_name[i].id+'">'+
										  '</div>';
			                	}
			                	$("."+c_val+"_menu").find(".ui_select_choices_group").html(html);
			                	$(".ui_select_choices_row").live('click',function(){
									bind_yes_item.bind_yes_c_founder_team(this,c_val);
									$("."+c_val+"_hide").show();
									$("."+c_val+"_menu").hide();
									show_tip();
								});
		                	}
		                	else{
		                		$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
		                		$("."+c_val+"_hide").hide();
		                	}
	                	}

	                	// 投资案例
	                	if(check_type == 'c_invest_exp'){
	                		if(arr_name_length){
			                	$("."+c_val+"_menu").show();
			                	$("."+c_val+"_menu").css({width:menu_width+"px",top:menu_top+"px"});
			                	$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
			                	for(var i=0; i<arr_name_length; i++){
			                		html+='<div class="ui_select_choices_row clearfix">'+
										  '		<span class="row_img mr5 company_image"><img src="'+arr_name[i].image+'"></span>'+
										  '		<span class="row_text company_name">'+arr_name[i].name+'</span>'+
										  '		<input type="hidden" name="company_brief" value="'+arr_name[i].brief+'">'+
										  '		<input type="hidden" name="invest_id" value="'+arr_name[i].invest_id+'">'+
										  '		<input type="hidden" name="ajax_item_id" value="'+arr_name[i].id+'">'+
										  '</div>';
			                	}
			                	// html+='<div class="ui_select_choices_new_row">添加“<span class="new_row_text">'+query.name+'</span>”</div>';
			                	$("."+c_val+"_menu").find(".ui_select_choices_group").html(html);
			                	$("."+c_val+"_name").blur(function(){
			                		$("."+c_val+"_form").find("."+c_val+"_name").val("");
			                		$(".ui_select_choices_row").live('click',function(){
										bind_yes_item.bind_yes_c_invest_exp(this,c_val);
										$("."+c_val+"_hide").show();
										$("."+c_val+"_menu").hide();
										show_tip();
									});
									show_tip();
			                	});
		                	}
		                	else{
		                		$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
		                		$("."+c_val+"_hide").hide();
		                	}
	                	}

	                	// 融资经历
	                	if(check_type == 'c_financing_exp'){
	                		if(arr_name_length){
			                	$("."+c_val+"_menu").show();
			                	$("."+c_val+"_menu").css({width:menu_width+"px",top:menu_top+"px"});
			                	$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
			                	for(var i=0; i<arr_name_length; i++){
			                		html+='<div class="ui_select_choices_row clearfix">'+
										  '		<span class="row_img mr5 company_image"><img src="'+arr_name[i].image+'"></span>'+
										  '		<span class="row_text company_name">'+arr_name[i].name+'</span>'+
										  '		<span class="row_text invest_type" style="float:right;">'+arr_name[i].invest_type_name+'</span>'+
										  '		<input type="hidden" name="company_brief" value="'+arr_name[i].brief+'">'+
										  '		<input type="hidden" name="invest_type" value="'+arr_name[i].invest_type+'">'+
										  '		<input type="hidden" name="invest_id" value="'+arr_name[i].invest_id+'">'+
										  '</div>';
			                	}
			                	// html+='<div class="ui_select_choices_new_row">添加“<span class="new_row_text">'+query.name+'</span>”</div>';
			                	$("."+c_val+"_menu").find(".ui_select_choices_group").html(html);
			                	$("."+c_val+"_name").blur(function(){
			                		$("."+c_val+"_form").find("."+c_val+"_name").val("");
									show_tip();
								});
								$(".ui_select_choices_row").on('click',function(event){
			                		event.stopPropagation();
									bind_yes_item.bind_yes_c_financing_exp(this,c_val);
									$("."+c_val+"_menu").hide();
									show_tip();
								});
		                	}
		                	else{
		                		$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
		                		// $("."+c_val+"_hide").hide();
		                	}
	                	}
	                	if(check_type == 'c_past_investors'){
	                		if(arr_name_length){
			                	$("."+c_val+"_menu").show();
			                	$("."+c_val+"_menu").css({width:menu_width+"px",top:menu_top+"px"});
			                	$("."+c_val+"_menu").find(".ui_select_choices_row").remove();
			                	for(var i=0; i<arr_name_length; i++){
			                		html+='<div class="ui_select_choices_row clearfix">'+
										  '		<span class="row_img mr5 company_image"><img src="'+arr_name[i].image+'"></span>'+
										  '		<span class="row_text company_name">'+arr_name[i].name+'</span>'+
										  '		<input type="hidden" name="company_brief" value="'+arr_name[i].brief+'">'+
										  '		<input type="hidden" name="invest_type" value="'+arr_name[i].invest_type+'">'+
										  '		<input type="hidden" name="invest_id" value="'+arr_name[i].invest_id+'">'+
										  '		<input type="hidden" name="id" value="'+arr_name[i].id+'">'+
										  '</div>';
			                	}
			                	// html+='<div class="ui_select_choices_new_row">添加“<span class="new_row_text">'+query.name+'</span>”</div>';
			                	$("."+c_val+"_menu").find(".ui_select_choices_group").html(html);
			                	$(".ui_select_choices_row").live('click',function(){
									bind_yes_item.bind_yes_c_past_investors(this,c_val);
									// $("."+c_val+"_hide").show();
									$("."+c_val+"_menu").hide();
									show_tip();
								});
		                	}
		                	else{
		                		$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
		                		// $("."+c_val+"_hide").hide();
		                	}
	                	}
	                }
	                else{
	                	$("."+c_val+"_menu").hide().end().find(".ui_select_choices_row").remove();
	                }
	            }
	        });
	        return false;
	    },300);
	}
}

function bind_yes_item(){
	this.bind_yes_c_founder_team = function(obj,c_val){
		var name = $(obj).find(".u_name").text();
		var email = $(obj).find("input[name='u_email']").val();
		var id = $(obj).find("input[name='id']").val();
		$("."+c_val+"_form").find("."+c_val+"_name").val(name);
		$("."+c_val+"_form").find("."+c_val+"_email").val(email).attr("disabled","disabled").addClass("disabled");
		$("."+c_val+"_form").find("input[name='user_id']").val(id);
	}
	this.bind_yes_c_invest_exp = function(obj,c_val){
		var name = $(obj).find(".company_name").text();
		var invest_id = $(obj).find("input[name='invest_id']").val();
		$("."+c_val+"_form").find("."+c_val+"_name").val(name);
		$("."+c_val+"_form").find("input[name='invest_id']").val(invest_id);
	}
	this.bind_yes_c_financing_exp = function(obj,c_val){
		var name = $(obj).find(".company_name").text();
		var image = $(obj).find(".company_image").text();
		var id = $(obj).find("input[name='invest_id']").val();
		var invest_type = $(obj).find("input[name='invest_type']").val();
		var html='';
		html = '<li>'+
			   '	<a href="javascript:void(0)">'+name+'</a>'+
			   '	<a href="javascript:void(0)" onclick="removeAddedEntity(this)">×</a>'+
			   '	<input type="hidden" name="invests_subject['+id+']" value="'+name+'" />'+
			   '	<input type="hidden" name="invests_id['+id+']" value="'+id+'" />'+
			   '	<input type="hidden" name="invests_type['+id+']" value="'+invest_type+'" />'+
			   '</li>';
		$("."+c_val+"_create_tags").append(html);
	}
	this.bind_yes_c_past_investors = function(obj,c_val){
		var name = $(obj).find(".company_name").text();
		var invest_id = $(obj).find("input[name='invest_id']").val();
		var invest_type = $(obj).find("input[name='invest_type']").val();
		$("."+c_val+"_form").find("."+c_val+"_name").val(name);
		$("."+c_val+"_form").find("input[name='invest_id']").val(invest_id);
		$("."+c_val+"_form").find("input[name='invest_type']").val(invest_type);
	}
	this.bind_no_c_invest_exp = function(obj,c_val){
		var name = $(obj).find(".new_row_text").text();
		$("."+c_val+"_form").find("."+c_val+"_name").val(name);
	}
}
var bind_yes_item = new bind_yes_item();

function removeAddedEntity(obj){
	$(obj).parent().remove();
}

// 草稿模式顶部固定(fixed)
function fixed_page_status(){
	header_h = $(".header").outerHeight();
	page_status_h = $(".company-page-status").outerHeight();
	var s=header_h-$(window).scrollTop();
	if(s <= 0){
		$(".company-page-status").addClass("company-page-status-fixed");
		$(".basic").css("paddingTop",page_status_h+"px");
	}
	else{
		$(".company-page-status").removeClass("company-page-status-fixed");
		$(".basic").css("paddingTop",0);
	}
}

// 公司介绍
function f_company_info(obj){
	this.obj = obj;
	// 删除已上传的图片
	this.del_company_introduce_img = function(){
		$(".image_item").find(".remove_image").live("click",function() {
			var pic_box_num = $("#image_box").find(".image_item").length;
			var $image_file = $("#image_file");
			$(this).parent().remove();
			pic_box_num == 5 ? $image_file.hide() : $image_file.show();
		});
	}
	// 超过图片最大张数进行处理
	this.hide_imgupload = function(){
		var pic_box_num = $("#image_box").find(".image_item").length;
		var $image_file = $("#image_file");
		pic_box_num == 5 ? $image_file.hide() : $image_file.show();
	}
	this.scrollTo = function(obj){
		var i = $(obj).index();
		$(obj).addClass("active").siblings().removeClass("active");
		$(".banner-con").find(".item").eq(i).show().siblings().hide();
	}
}
var f_company_info = new f_company_info();

// 删除已上传的图片
function bind_del_image() {
	$(".image_item").find(".remove_image").live("click",function() {
		del_image($(this));
		hide_imgupload();
	});
}

// 编辑删除操作
function f_item(obj){
	this.obj = obj;
	this.add_job_end_time = function(obj){
		$(obj).find("input[type='checkbox']")
		if($(obj).find("input[type='checkbox']").is(':checked')){
			$(obj).attr('name','job_end_time');
			$(obj).prev("input[name='job_end_time']").remove();
		}
		else{
			$(obj).removeAttr('name');
			var html='<input readonly="" type="text" class="small_textbox w100 jcDate mr10" rel="input-text" value="" name="job_end_time" placeholder="请选择时间">';
			$(obj).before(html);
			$(document).ready(function(){
				//选择日期控件
				$("input.jcDate").jcDate({
				    IcoClass : "jcDateIco",
				    Event : "click",
				    Speed : 100,
				    Left :-125,
				    Top : 28,
				    format : "-",
				    Timeout : 100,
				    Oldyearall : 17,  // 配置过去多少年
				    Newyearall : 0  // 配置未来多少年
				});
			});

		}
	}
	this.edit_item = function(obj){
		var c_val = $(obj).attr("rel");
		$(obj).hide();
		$("."+c_val+"_normal").hide();
		$("."+c_val+"_btn_add").hide();
		$("."+c_val+"_edit").show();
	}
	this.edit_ajax_item = function(obj){
		var c_val = $(obj).attr("rel");
		var ajaxurl = company_sub_project_ajaxurl;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_item_id=$("."+c_val+"_item").find("input[name='ajax_item_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.ajax({ 
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1)
				{
					var edit_form_html = '<form class="ml20 ajax_companty_sub_product_edit_form c_sub_product_'+data.compay_product.id+'_ajax_edit edit_mode edit_form" action="'+data.company_sub_project_ajaxurl+'" method="post" rel="c_sub_product_'+data.compay_product.id+'">'+
										 '	<div class="control-group small-control-group">'+
										 '		<label class="control-label"><span class="f_red">*</span>子产品名称</label>'+
										 '		<div class="control-text">'+
										 '			<div class="holder_tip_box">'+
										 '				<input type="text" name="subPro_name" value="'+data.compay_product.product_name+'" class="textbox small_textbox">'+
										 '				<div class="holder_tip small_holder_tip"><span>请输入产品名称</span></div>'+
										 '			</div>'+
										 '      </div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '	<div class="control-group small-control-group">'+
										 '		<label class="control-label"><span class="f_red">*</span>子产品链接</label>'+
										 '		<div class="control-text">'+
										 '			<div class="holder_tip_box">'+
										 '				<input type="text" name="subPro_website" value="'+data.compay_product.product_website+'" class="textbox small_textbox" onfocus="auto_write_focus(this);" onblur="auto_write_blur(this);">'+
										 '				<div class="holder_tip small_holder_tip"><span>如:http://www.fanwe.com</span></div>'+
										 '			</div>'+
										 '		</div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '	<div class="submit_row clearfix">'+
										 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_sub_product_'+data.compay_product.id+'">跳过</a>'+
										 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_sub_product_'+data.compay_product.id+'">保存</a>'+
										 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_product.id+'" />'+
							             '		<input type="hidden" name="method" value="company_sub_project">'+
										 '		<input type="hidden" name="ajax" value="1">'+
										 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
										 '		<input type="hidden" name="ajax_act" value="save">'+
										 '	</div>'+
										 '</form>';
					$("."+c_val+"_item").after(edit_form_html);
					$("."+c_val+"_item").hide();
					show_tip();
					return false;
				}
				else
				{
					$.showErr(data.info);
					return false;
				}
			}
		});	
	}
	// 融资经历编辑
	this.c_financing_exp_edit = function(obj,ajaxurl){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajaxurl;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_item_id=$("."+c_val+"_item").find("input[name='ajax_item_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.ajax({ 
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1)
				{
					var ajax_edit_length = $("."+c_val+"_ajax_edit").length;
					if(!ajax_edit_length){
						var invest_subject_info_html='';
						if(data.compay_team.invest_subject_info){
							var invest_subject_info = data.compay_team.invest_subject_info;
							var invest_subject_info_length = data.compay_team.invest_subject_info.length;
							
							for(var i=0; i<invest_subject_info_length; i++){
		                		invest_subject_info_html+='<li>'+
									  '		<a href="javascript:void(0)">'+data.compay_team.invest_subject_info[i].name+'</a>'+
									  '		<a href="javascript:void(0)" onclick="removeAddedEntity(this)">×</a>'+
									  '		<input type="hidden" name="invests_subject['+data.compay_team.invest_subject_info[i].id+']" value="'+data.compay_team.invest_subject_info[i].name+'" />'+
									  '		<input type="hidden" name="invests_id['+data.compay_team.invest_subject_info[i].id+']" value="'+data.compay_team.invest_subject_info[i].id+'" />'+
									  '		<input type="hidden" name="invests_type['+data.compay_team.invest_subject_info[i].id+']" value="'+data.compay_team.invest_subject_info[i].invest_type+'" />'+
									  '		</li>'+
									  '</li>';
		                	}
						}
						
						var new_invest_phase = "";
						var invest_phase = data.compay_team.invest_phase;
						if(invest_phase == 0){new_invest_phase="天使轮";}
						if(invest_phase == 1){new_invest_phase="Pre-A轮";}
						if(invest_phase == 2){new_invest_phase="A轮";}
						if(invest_phase == 3){new_invest_phase="A+轮";}
						if(invest_phase == 4){new_invest_phase="B轮";}
						if(invest_phase == 5){new_invest_phase="B+轮";}
						if(invest_phase == 6){new_invest_phase="C轮";}
						if(invest_phase == 7){new_invest_phase="D轮";}
						if(invest_phase == 8){new_invest_phase="E轮及以后";}
						if(invest_phase == 9){new_invest_phase="并购";}
						if(invest_phase == 10){new_invest_phase="上市";}
						if(invest_phase == 9){
							var edit_form_html = '<form class="ajax_financing_exp_form c_financing_exp_'+data.compay_team.id+'_ajax_edit edit_mode ml20 edit_form" action="'+ajaxurl+'" method="post" rel="c_financing_exp_'+data.compay_team.id+'">'+
												 '	<div class="control-group small-control-group pr" style="overflow:inherit;">'+
												 '		<label class="control-label"><span class="f_red">*</span>融资阶段</label>'+
												 '		<div class="control-text">'+
												 '			<span>'+new_invest_phase+'</span>'+
												 '			<input type="hidden" name="finance_phase" value="'+data.compay_team.invest_phase+'" />'+
												 '      </div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
												 '	<div class="control-group small-control-group">'+
												 '		<label class="control-label">并购金额</label>'+
												 '		<div class="control-text">'+
												 '			<select name="finance_amount_unit" id="finance_amount_unit" class="ui-select field_select small">'+
												 '				<option value="0" selected="selected" label="人民币">人民币</option>'+
												 '				<option value="1" label="美元">美元</option>'+
												 '			</select>'+
												 '			<div class="holder_tip_box">'+
												 '				<input type="text" name="finance_amount" value="'+data.compay_team.finance_amount+'" class="textbox small_textbox w200" />'+
												 '				<div class="holder_tip small_holder_tip"><span>1,000,000</span></div>'+
												 '			</div> 万'+
												 '		</div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label"><span class="f_red f12">*</span><span class="finance_phase_time_text">并购时间</span></label>'+
												 '  	<div class="control-text">'+
												 '  		<input readonly="" type="text" class="small_textbox w100 jcDate" rel="input-text" value="'+data.compay_team.invest_time+'" name="finance_time" id="inputLaunchTime" placeholder="请选择时间">'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group pr finance_phase_subject_text" style="overflow:inherit;">'+
												 '  	<label class="control-label">收购方</label>'+
												 '  	<div class="control-text">'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" class="textbox small_textbox init_data c_financing_exp_name c_financing_exp_input J_check_c_financing_exp_name" rel="c_financing_exp" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>输入投资主体，如：经纬中国</span></div>'+
												 '  		</div>'+
												 '  		<div class="clear"></div>'+
												 '  		<ul class="create-tags c_financing_exp_create_tags">'+invest_subject_info_html+'</ul>'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label">相关报道</label>'+
												 '  	<div class="control-text">'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" name="finance_pressurl" value="'+data.compay_team.finance_pressurl+'" class="textbox small_textbox w200" onfocus="auto_write_focus(this);" onblur="auto_write_blur(this);" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>填写事件被报道的链接地址</span></div>'+
												 '  		</div>'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '	<div class="submit_row clearfix">'+
												 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_financing_exp_'+data.compay_team.id+'">跳过</a>'+
												 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_financing_exp_'+data.compay_team.id+'">保存</a>'+
									             '		<input type="hidden" name="method" value="company_experience">'+
												 '		<input type="hidden" name="ajax" value="1">'+
												 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
												 '		<input type="hidden" name="ajax_act" value="save">'+
	 										 	 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_team.id+'">'+
												 '		<input type="hidden" name="phase_type" value="1">'+
												 '	</div>'+
												 '</form>';
						}
						else if(invest_phase == 10){
							var edit_form_html = '<form class="ajax_financing_exp_form c_financing_exp_'+data.compay_team.id+'_ajax_edit edit_mode ml20 edit_form" action="'+ajaxurl+'" method="post" rel="c_financing_exp_'+data.compay_team.id+'">'+
												 '	<div class="control-group small-control-group pr" style="overflow:inherit;">'+
												 '		<label class="control-label"><span class="f_red">*</span>融资阶段</label>'+
												 '		<div class="control-text">'+
												 '			<span>'+new_invest_phase+'</span>'+
												 '			<input type="hidden" name="finance_phase" value="'+data.compay_team.invest_phase+'" />'+
												 '      </div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
												 '	<div class="control-group small-control-group">'+
												 '		<label class="control-label">总募金额</label>'+
												 '		<div class="control-text">'+
												 '			<select name="finance_amount_unit" id="finance_amount_unit" class="ui-select field_select small">'+
												 '				<option value="0" selected="selected" label="人民币">人民币</option>'+
												 '				<option value="1" label="美元">美元</option>'+
												 '			</select>'+
												 '			<div class="holder_tip_box">'+
												 '				<input type="text" name="finance_amount" value="'+data.compay_team.finance_amount+'" class="textbox small_textbox w200" />'+
												 '				<div class="holder_tip small_holder_tip"><span>1,000,000</span></div>'+
												 '			</div> 万'+
												 '		</div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label"><span class="f_red f12">*</span><span class="finance_phase_time_text">上市时间</span></label>'+
												 '  	<div class="control-text">'+
												 '  		<input readonly="" type="text" class="small_textbox w100 jcDate" rel="input-text" value="'+data.compay_team.invest_time+'" name="finance_time" id="inputLaunchTime" placeholder="请选择时间">'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label">相关报道</label>'+
												 '  	<div class="control-text">'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" name="finance_pressurl" value="'+data.compay_team.finance_pressurl+'" class="textbox small_textbox w200" onfocus="auto_write_focus(this);" onblur="auto_write_blur(this);" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>填写事件被报道的链接地址</span></div>'+
												 '  		</div>'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '	<div class="submit_row clearfix">'+
												 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_financing_exp_'+data.compay_team.id+'">跳过</a>'+
												 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_financing_exp_'+data.compay_team.id+'">保存</a>'+
									             '		<input type="hidden" name="method" value="company_experience">'+
												 '		<input type="hidden" name="ajax" value="1">'+
												 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
												 '		<input type="hidden" name="ajax_act" value="save">'+
	 										 	 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_team.id+'">'+
												 '		<input type="hidden" name="phase_type" value="2">'+
												 '	</div>'+
												 '</form>';
						}
						else{
							var edit_form_html = '<form class="ajax_financing_exp_form c_financing_exp_'+data.compay_team.id+'_ajax_edit edit_mode ml20 edit_form" action="'+ajaxurl+'" method="post" rel="c_financing_exp_'+data.compay_team.id+'">'+
												 '	<div class="control-group small-control-group pr" style="overflow:inherit;">'+
												 '		<label class="control-label"><span class="f_red">*</span>融资阶段</label>'+
												 '		<div class="control-text">'+
												 '			<span>'+new_invest_phase+'</span>'+
												 '			<input type="hidden" name="finance_phase" value="'+data.compay_team.invest_phase+'" />'+
												 '      </div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
												 '	<div class="control-group small-control-group">'+
												 '		<label class="control-label">融资金额</label>'+
												 '		<div class="control-text">'+
												 '			<select name="finance_amount_unit" id="finance_amount_unit" class="ui-select field_select small">'+
												 '				<option value="0" label="人民币">人民币</option>'+
												 '				<option value="1" label="美元">美元</option>'+
												 '			</select>'+
												 '			<div class="holder_tip_box">'+
												 '				<input type="text" name="finance_amount" value="'+data.compay_team.finance_amount+'" class="textbox small_textbox w200" />'+
												 '				<div class="holder_tip small_holder_tip"><span>1,000,000</span></div>'+
												 '			</div> 万'+
												 '		</div>'+
												 '		<div class="clear"></div>'+
												 '	</div>'+
	 											 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label">此轮估值</label>'+
												 '  	<div class="control-text">'+
												 '  		<select name="finance_valuation_unit" id="finance_valuation_unit" class="ui-select field_select small">'+
												 '  			<option value="0" label="人民币">人民币</option>'+
												 '  			<option value="1" label="美元">美元</option>'+
												 '  		</select>'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" name="finance_valuation" value="'+data.compay_team.valuation+'" class="textbox small_textbox w200" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>5,000,000</span></div>'+
												 '  		</div> 万'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label"><span class="f_red f12">*</span><span class="finance_phase_time_text">融资时间</span></label>'+
												 '  	<div class="control-text">'+
												 '  		<input readonly="" type="text" class="small_textbox w100 jcDate" rel="input-text" value="'+data.compay_team.invest_time+'" name="finance_time" id="inputLaunchTime" placeholder="请选择时间">'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group pr finance_phase_subject_text" style="overflow:inherit;">'+
												 '  	<label class="control-label">投资主体</label>'+
												 '  	<div class="control-text" style="overflow:inherit;height:auto;line-heigth:1.5;">'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" class="textbox small_textbox init_data c_financing_exp_name c_financing_exp_input J_check_c_financing_exp_name" rel="c_financing_exp" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>输入投资主体，如：经纬中国</span></div>'+
												 '  		</div>'+
												 '  		<div class="clear"></div>'+
												 '  		<ul class="create-tags c_financing_exp_create_tags">'+invest_subject_info_html+'</ul>'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  	<ul class="ui_select_choices c_financing_exp_menu hide" role="menu">'+
												 '  		<li class="ui_select_choices_group"></li>'+
												 '  	</ul>'+
												 '  </div>'+
												 '  <div class="control-group small-control-group">'+
												 '  	<label class="control-label">相关报道</label>'+
												 '  	<div class="control-text">'+
												 '  		<div class="holder_tip_box">'+
												 '  			<input type="text" name="finance_pressurl" value="'+data.compay_team.finance_pressurl+'" class="textbox small_textbox w200" onfocus="auto_write_focus(this);" onblur="auto_write_blur(this);" />'+
												 '  			<div class="holder_tip small_holder_tip"><span>填写事件被报道的链接地址</span></div>'+
												 '  		</div>'+
												 '  	</div>'+
												 '  	<div class="clear"></div>'+
												 '  </div>'+
												 '	<div class="submit_row clearfix">'+
												 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_financing_exp_'+data.compay_team.id+'">跳过</a>'+
												 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_financing_exp_'+data.compay_team.id+'">保存</a>'+
									             '		<input type="hidden" name="method" value="company_experience">'+
												 '		<input type="hidden" name="ajax" value="1">'+
												 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
												 '		<input type="hidden" name="ajax_act" value="save">'+
	 										 	 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_team.id+'">'+
												 '		<input type="hidden" name="phase_type" value="0">'+
												 '	</div>'+
												 '</form>';
						}
						$(".c_financing_exp_normal").prepend(edit_form_html);
						$("."+c_val+"_ajax_edit select[name='finance_amount_unit']").find("option").eq(data.compay_team.finance_amount_unit).attr("selected","selected");
						$("."+c_val+"_ajax_edit select[name='finance_valuation_unit']").find("option").eq(data.compay_team.valuation_unit).attr("selected","selected");
						show_tip();
						init_ui_select();
						//选择日期控件
						$("input.jcDate").jcDate({
						    IcoClass : "jcDateIco",
						    Event : "click",
						    Speed : 100,
						    Left :-125,
						    Top : 28,
						    format : "-",
						    Timeout : 100,
						    Oldyearall : 17,  // 配置过去多少年
						    Newyearall : 0  // 配置未来多少年
						});
						$(".J_check_c_financing_exp_name").on('input propertychange',function(){
							check_u_name(this,c_financing_exp_checkname_ajaxurl,'c_financing_exp');
						});
						return false;
					}
				}
				else
				{
					$.showErr(data.info);
					return false;
				}
			}
		});	
	}
	// 投资案例编辑
	this.c_invest_exp_edit = function(obj,ajaxurl){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajaxurl;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_item_id=$("."+c_val+"_detail").find("input[name='ajax_item_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.ajax({ 
			url: ajaxurl,
			data:query,
			type: "POST",
			dataType: "json",
			success: function(data){
				if(data.status==1)
				{
					var invest_phase = data.compay_team.invest_phase;
					if(invest_phase == 0){invest_phase="天使轮";}
					if(invest_phase == 1){invest_phase="Pre-A轮";}
					if(invest_phase == 2){invest_phase="A轮";}
					if(invest_phase == 3){invest_phase="A+轮";}
					if(invest_phase == 4){invest_phase="B轮";}
					if(invest_phase == 5){invest_phase="B+轮";}
					if(invest_phase == 6){invest_phase="C轮";}
					if(invest_phase == 7){invest_phase="D轮";}
					if(invest_phase == 8){invest_phase="E轮及以后";}
					if(invest_phase == 9){invest_phase="并购";}
					if(data.compay_team.invest_phase == 9){
						var edit_form_html = '<form class="ajax_invest_exp_form c_invest_exp_'+data.compay_team.id+'_ajax_edit edit_mode ml20 edit_form" action="'+ajaxurl+'" method="post" rel="c_invest_exp_'+data.compay_team.id+'">'+
										 '	<div class="control-group small-control-group pr" style="overflow:inherit;">'+
										 '		<label class="control-label"><span class="f_red f12">*</span>公司简称</label>'+
										 '		<div class="control-text">'+
										 '			<input type="text" name="company_abbreviat" value="'+data.compay_team.company_name+'" class="textbox small_textbox w200 disabled" readonly="readonly" />'+
										 '      </div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '	<div class="control-group small-control-group">'+
										 '		<label class="control-label">投资阶段</label>'+
										 '		<div class="control-text">'+
										 '			<input type="text" value="'+invest_phase+'" class="textbox small_textbox w200 disabled" readonly="readonly" />'+
										 '			<input type="hidden" name="invest_phase" value="'+data.compay_team.invest_phase+'" />'+
										 '		</div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label">并购金额</label>'+
										 '  	<div class="control-text">'+
										 '  		<select name="finance_amount_unit" id="finance_amount_unit" class="ui-select field_select small">'+
										 '  			<option value="0" selected="selected" label="人民币">人民币</option>'+
										 '  			<option value="1" label="美元">美元</option>'+
										 '  		</select>'+
										 '  		<div class="holder_tip_box">'+
										 '  			<input type="text" name="finance_amount" value="'+data.compay_team.finance_amount+'" class="textbox small_textbox w200" />'+
										 '  			<div class="holder_tip small_holder_tip"><span>5,000,000</span></div>'+
										 '  		</div> 万'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
 										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label"><span class="f_red f12">*</span>并购时间</label>'+
										 '  	<div class="control-text">'+
										 '  		<input readonly="" type="text" class="small_textbox w100 jcDate" rel="input-text" value="'+data.compay_team.invest_time+'" name="invest_time" id="inputLaunchTime" placeholder="请选择时间">'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
										 '	<div class="submit_row clearfix">'+
										 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_invest_exp_'+data.compay_team.id+'">跳过</a>'+
										 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_invest_exp_'+data.compay_team.id+'">保存</a>'+
							             '		<input type="hidden" name="method" value="company_invest">'+
										 '		<input type="hidden" name="ajax" value="1">'+
										 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
										 '		<input type="hidden" name="ajax_act" value="save">'+
										 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_team.id+'">'+
										 '	</div>'+
										 '</form>';
					}
					else{
						var edit_form_html = '<form class="ajax_invest_exp_form c_invest_exp_'+data.compay_team.id+'_ajax_edit edit_mode ml20 edit_form" action="'+ajaxurl+'" method="post" rel="c_invest_exp_'+data.compay_team.id+'">'+
										 '	<div class="control-group small-control-group pr" style="overflow:inherit;">'+
										 '		<label class="control-label"><span class="f_red f12">*</span>公司简称</label>'+
										 '		<div class="control-text">'+
										 '			<input type="text" name="company_abbreviat" value="'+data.compay_team.company_name+'" class="textbox small_textbox w200 disabled" readonly="readonly" />'+
										 '      </div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '	<div class="control-group small-control-group">'+
										 '		<label class="control-label">投资阶段</label>'+
										 '		<div class="control-text">'+
										 '			<input type="text" value="'+invest_phase+'" class="textbox small_textbox w200 disabled" readonly="readonly" />'+
 										 '			<input type="hidden" name="invest_phase" value="'+data.compay_team.invest_phase+'" />'+
										 '		</div>'+
										 '		<div class="clear"></div>'+
										 '	</div>'+
										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label">我方投资金额</label>'+
										 '  	<div class="control-text">'+
										 '  		<select name="invest_amount_unit" id="invest_amount_unit" class="ui-select field_select small">'+
										 '  			<option value="0" label="人民币">人民币</option>'+
										 '  			<option value="1" label="美元">美元</option>'+
										 '  		</select>'+
										 '  		<div class="holder_tip_box">'+
										 '  			<input type="text" name="invest_amount" value="'+data.compay_team.invest_amount+'" class="textbox small_textbox w200" />'+
										 '  			<div class="holder_tip small_holder_tip"><span>1,000,000</span></div>'+
										 '  		</div> 万'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label">此轮总投资金额</label>'+
										 '  	<div class="control-text">'+
										 '  		<select name="finance_amount_unit" id="finance_amount_unit" class="ui-select field_select small">'+
										 '  			<option value="0" selected="selected" label="人民币">人民币</option>'+
										 '  			<option value="1" label="美元">美元</option>'+
										 '  		</select>'+
										 '  		<div class="holder_tip_box">'+
										 '  			<input type="text" name="finance_amount" value="'+data.compay_team.finance_amount+'" class="textbox small_textbox w200" />'+
										 '  			<div class="holder_tip small_holder_tip"><span>5,000,000</span></div>'+
										 '  		</div> 万'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label">此轮估值</label>'+
										 '  	<div class="control-text">'+
										 '  		<select name="valuation_unit" id="valuation_unit" class="ui-select field_select small">'+
										 '  			<option value="0" selected="selected" label="人民币">人民币</option>'+
										 '  			<option value="1" label="美元">美元</option>'+
										 '  		</select>'+
										 '  		<div class="holder_tip_box">'+
										 '  			<input type="text" name="valuation" value="'+data.compay_team.valuation+'" class="textbox small_textbox w200" />'+
										 '  			<div class="holder_tip small_holder_tip"><span>5,000,000</span></div>'+
										 '  		</div> 万'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
 										 '  <div class="control-group small-control-group">'+
										 '  	<label class="control-label"><span class="f_red f12">*</span>投资时间</label>'+
										 '  	<div class="control-text">'+
										 '  		<input readonly="" type="text" class="small_textbox w100 jcDate" rel="input-text" value="'+data.compay_team.invest_time+'" name="invest_time" id="inputLaunchTime" placeholder="请选择时间">'+
										 '  	</div>'+
										 '  	<div class="clear"></div>'+
										 '  </div>'+
										 '	<div class="submit_row clearfix">'+
										 '		<a href="javascript:void(0);" class="ui-button bg_gray ajax-cancle-btn" rel="c_invest_exp_'+data.compay_team.id+'">跳过</a>'+
										 '		<a href="javascript:void(0);" class="ui-button theme_bgcolor mr10 ajax-save-btn" rel="c_invest_exp_'+data.compay_team.id+'">保存</a>'+
							             '		<input type="hidden" name="method" value="company_invest">'+
										 '		<input type="hidden" name="ajax" value="1">'+
										 '		<input type="hidden" name="company_id" value="'+company_id+'">'+
										 '		<input type="hidden" name="ajax_act" value="save">'+
										 '		<input type="hidden" name="ajax_item_id" value="'+data.compay_team.id+'">'+
										 '	</div>'+
										 '</form>';
					}
					$(".c_invest_exp_normal").prepend(edit_form_html);
					$("."+c_val+"_ajax_edit select[name='invest_amount_unit']").find("option").eq(data.compay_team.invest_amount_unit).attr("selected","selected");
					$("."+c_val+"_ajax_edit select[name='finance_amount_unit']").find("option").eq(data.compay_team.finance_amount_unit).attr("selected","selected");
					$("."+c_val+"_ajax_edit select[name='valuation_unit']").find("option").eq(data.compay_team.valuation_unit).attr("selected","selected");
					show_tip();
					init_ui_select();
					//选择日期控件
					$("input.jcDate").jcDate({
					    IcoClass : "jcDateIco",
					    Event : "click",
					    Speed : 100,
					    Left :-125,
					    Top : 28,
					    format : "-",
					    Timeout : 100,
					    Oldyearall : 17,  // 配置过去多少年
					    Newyearall : 0  // 配置未来多少年
					});
					return false;
				}
				else
				{
					$.showErr(data.info);
					return false;
				}
			}
		});	
	}
	this.add_item = function(obj){
		var c_val = $(obj).attr("rel");
		$("."+c_val+"_edit").show();
		$("."+c_val+"_btn_add").hide();
		$("."+c_val+"_edit").find(".init_data").val("");
		$("."+c_val+"_hide").hide();
		$("."+c_val+"_form").find("option:first").attr("selected","selected");
		show_tip();
		init_ui_select();
		if(c_val="c_financing_exp"){
			$("."+c_val+"_form").find("select[name='finance_phase']").ui_select({refresh:true});
	 		$("."+c_val+"_form").find("select[name='finance_amount_unit']").ui_select({refresh:true});
	 		$("."+c_val+"_form").find("select[name='finance_valuation_unit']").ui_select({refresh:true});
	 		select_phase(this,"finance_phase","c_financing_exp");
	 		$("."+c_val+"_create_tags").find("li").remove();
		}
		if(c_val="c_invest_exp"){
	 		select_phase(this,"invest_phase","c_invest_exp");
		}
	}
	this.del_item = function(obj){
		var c_val = $(obj).attr("rel");
		$(obj).parent().parent().parent("."+c_val+"_normal").remove();
	}
	this.del_ajax_item = function(obj,ajax_url){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajax_url;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_item_id=$("."+c_val+"_item").find("input[name='ajax_item_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.showConfirm("确定要删除吗？",function(){
			$.ajax({ 
				url: ajaxurl,
				data:query,
				type: "POST",
				dataType: "json",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$("."+c_val+"_item").remove();
						$.weeboxs.close();
					}
					else
					{
						$.showErr(ajaxobj.info);
					}
				}
			});
			return false;
		});
	}
	this.del_ajax_main_item = function(obj,ajax_url){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajax_url;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_invest_id=$("."+c_val+"_item").find("input[name='ajax_invest_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.showConfirm("确定要删除吗？",function(){
			$.ajax({ 
				url: ajaxurl,
				data:query,
				type: "POST",
				dataType: "json",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$("."+c_val+"_item").remove();
						$.weeboxs.close();
					}
					else
					{
						$.showErr(ajaxobj.info);
					}
				}
			});
			return false;
		});
	}
	this.del_ajax_sub_item = function(obj,ajax_url){
		var c_val = $(obj).attr("rel");
		var ajaxurl = ajax_url;
		var query = new Object();
		query.company_id=company_id;
		query.ajax_item_id=$("."+c_val+"_detail").find("input[name='ajax_item_id']").val();
		query.ajax_act=$(obj).attr("ajax_act");
		$.showConfirm("确定要删除吗？",function(){
			$.ajax({ 
				url: ajaxurl,
				data:query,
				type: "POST",
				dataType: "json",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$("."+c_val+"_detail").remove();
						$.weeboxs.close();
					}
					else
					{
						$.showErr(ajaxobj.info);
					}
				}
			});
			return false;
		});
	}
}
var f_item = new f_item();

// 编辑
function edit_item(obj){
	var c_val = $(obj).attr("rel");
	$(obj).hide();
	$("."+c_val+"_normal").hide();
	$("."+c_val+"_edit").show();
	checkstrlength("textarea[name='intro']",'#c_company_intro_left_words',1000);
}

// 加载图标
var loading_html = '<div class="btn-loading-wrap">'+
				   '	<div class="spinner">'+
				   '		<div class="rect1"></div>'+
				   '		<div class="rect2"></div>'+
				   '		<div class="rect3"></div>'+
				   '		<div class="rect4"></div>'+
				   '		<div class="rect5"></div>'+
				   '	</div>'+
				   '</div>';

// 绑定ajax_company_form
function bind_ajax_company_form(form_type)
{
	$(form_type).find(".save-btn").live("click",function(){
 		$(form_type).submit();
	});
	$(form_type).find(".cancle-btn").bind("click",function(){
		var $o = $(this);
		var c_val = $o.attr("rel");
		$("."+c_val+"_edit").hide();
		$("."+c_val+"_icon_edit").show();

		switch (c_val){
			case "c_company_intro":
			  	if($("."+c_val+"_normal_list").children().length || $("."+c_val+"_introduce").text()){
					$("."+c_val+"_normal").show();
					$("."+c_val+"_btn_add").hide();
				}
				else{
					$("."+c_val+"_normal").hide();
					$("."+c_val+"_btn_add").show();
				}
		  		break;
			case "c_company_basic":
		  		$("."+c_val+"_normal").show();
		  		break;
		  	case "c_company_case":
		  		if($("."+c_val+"_normal_list").find(".ajax_team_advantage").text()){
					$("."+c_val+"_normal").show();
					$("."+c_val+"_btn_add").hide();
				}
				else{
					$("."+c_val+"_normal").hide();
					$("."+c_val+"_btn_add").show();
				}
				break;
			default:
		  		if($("."+c_val+"_normal_list").children().length){
					$("."+c_val+"_normal").show();
					$("."+c_val+"_btn_add").hide();
				}
				else{
					$("."+c_val+"_normal").hide();
					$("."+c_val+"_btn_add").show();
				}
		}
		
	});
	$(form_type).find(".ajax-save-btn").live("click",function(){
			var $o = $(this);
			var c_val = $o.attr("rel");
			var ajaxurl = company_sub_project_ajaxurl;
			var query = $o.parent().parent().serialize();
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						$o.before(loading_html);
						$o.hide();
						// 子产品
						if(form_type == ".ajax_companty_sub_product_form"){
							window.setTimeout(function(){
								$o.parent().find(".btn-loading-wrap").remove();
								$o.show();
								var arr_item = ajaxobj.productall;
								var arr_item_length = arr_item.length;
								$("."+c_val+"_ajax_edit").remove();
								$("."+c_val+"_item").find(".ajax_product_name").html(arr_item[arr_item_length-1].product_name);
								$("."+c_val+"_item").find(".ajax_product_website").attr("href",arr_item[arr_item_length-1].product_website).html(arr_item[arr_item_length-1].product_website);
								$("."+c_val+"_item").show();
								$(".c_sub_product_"+arr_item[arr_item_length-1].id+"_ajax_edit").hide();
							},1000);
						}
						// 投资案例
						if(form_type == ".ajax_invest_exp_form"){
							window.setTimeout(function(){
								$o.parent().find(".btn-loading-wrap").remove();
								$("."+c_val+"_ajax_edit").remove();
								$o.show();
								var finance_amount_unit;
								ajaxobj.finance_amount_unit == 0 ? finance_amount_unit="¥" : finance_amount_unit="$";
								$("."+c_val+"_detail").find(".ajax_time").html(ajaxobj.invest_time);
								$("."+c_val+"_detail").find(".ajax_finance_amount_unit").html(finance_amount_unit);
								$("."+c_val+"_detail").find(".ajax_finance_amount").html(ajaxobj.finance_amount);
							},1000);
						}
						// 融资经历
						if(form_type == ".ajax_financing_exp_form"){
							var invest_subject_info_html = '';
							if(ajaxobj.invest_subject_info){
								var invest_subject_info = ajaxobj.invest_subject_info;
								var invest_subject_info_length = ajaxobj.invest_subject_info.length;
								for(var i=0; i<invest_subject_info_length; i++){
			                		invest_subject_info_html+='<li>'+
															  '		<a class="logo" href="'+invest_subject_info[i].home_url+'" target="_blank">'+
															  '			<img alt="" width="50" src="'+invest_subject_info[i].image+'">'+
															  '		</a>'+
															  '		<a class="name" href="'+invest_subject_info[i].home_url+'" target="_blank">'+
															  '			<span class="ng-binding">'+invest_subject_info[i].user_name+'</span>'+
															  '		</a>'+
															  '	</li>';
			                	}
			                	invest_subject_info_html='<ul class="financing-list-member list-unstyled ul_maxheight">'+invest_subject_info_html+'</ul>';
							}
							else{
								invest_subject_info_html='<div class="financing-unknow">未披露</div>';
							}
							var finance_amount_unit;
							ajaxobj.finance_amount_unit == 0 ? finance_amount_unit="¥" : finance_amount_unit="$";

							var valuation_unit;
							ajaxobj.valuation_unit == 0 ? valuation_unit="¥" : valuation_unit="$";

							if(ajaxobj.finance_pressurl){
								var html_finance_pressurl = '<a href="'+ajaxobj.finance_pressurl+'" target="_blank" class="pressUrl">相关报道</a>';
							}
							else{
								var html_finance_pressurl='';
							}

							window.setTimeout(function(){
								$o.parent().find(".btn-loading-wrap").remove();
								$("."+c_val+"_ajax_edit").remove();
								$("."+c_val+"_item").find(".financing-list-member").remove();
								$("."+c_val+"_item").find(".financing-unknow").remove();
								$("."+c_val+"_item").find(".financing-price").after(invest_subject_info_html);
								$o.show();
								$("."+c_val+"_item").find(".ajax_invest_time").html(ajaxobj.invest_time);
								$("."+c_val+"_item").find(".pressUrl_box").html(html_finance_pressurl);
								// $("."+c_val+"_item").find(".ajax_finance_amount_unit").html(ajaxobj.finance_amount_unit);
								$("."+c_val+"_item").find(".ajax_finance_amount").html(ajaxobj.finance_amount);
								$("."+c_val+"_item").find(".ajax_finance_amount_unit").html(finance_amount_unit);
								$("."+c_val+"_item").find(".ajax_valuation").html(ajaxobj.valuation);
								$("."+c_val+"_item").find(".ajax_valuation_unit").html(valuation_unit);
								// $("."+c_val+"_item .financing-list-member").append(invest_subject_info_html);
							},1000);
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
					}
				}
			});
			return false;
	});
	$(form_type).find(".ajax-cancle-btn").live("click",function(){
		var $o = $(this);
		var c_val = $o.attr("rel");
		$("."+c_val+"_ajax_edit").remove();
		$("."+c_val+"_item").show();
	});
	$(form_type).bind("submit",function(){
		var $o = $(this);
		var c_val = $o.attr("rel");
		var ajaxurl = $o.attr("action");
		var query = $o.serialize();
		query.company_id=company_id;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					$o.find(".save-btn").before(loading_html);
					$o.find(".save-btn").hide();
					window.setTimeout(function(){
						// 公司简介
						if(ajaxobj.method=='company_default'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							$(".ajax_company_brief").html(ajaxobj.company_brief);
							$(".ajax_province").html(ajaxobj.province);
							$(".ajax_city").html(ajaxobj.city);
							$(".ajax_company_website").html(ajaxobj.company_website);
							$(".ajax_company_tag").html(ajaxobj.company_tag);
							$(".ajax_company_sina_weibo").attr("href",ajaxobj.company_sina_weibo);
							$(".ajax_company_weixin").attr("tooltip",ajaxobj.company_weixin);
							$("."+c_val+"_normal").show();
							
						}
						// 公司介绍
						if(ajaxobj.method=='company_intro'){
							var $banner_con = $(".banner-con");
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							$(".ajax_company_introduce_image").html(ajaxobj.company_introduce_image);
							$(".banner-con").find(".item").remove();
							$(".banner-p").find("a").remove();
							var arr_img = ajaxobj.company_introduce_image;
							if(arr_img){
								var banner_con_html='';
								var banner_p_html='';
								for(var i=0; i<arr_img.length; i++){
									if(i!=0){
										banner_con_html+='<div class="item ajax_company_introduce_image hide" style="background-image: url('+ajaxobj.company_introduce_image[i]+');"></div>';
										banner_p_html+='<a href="javascript:void(0);" onclick="f_company_info.scrollTo(this);" style="background-image: url('+ajaxobj.company_introduce_image[i]+');" class=""></a>';
									}
									else{
										banner_con_html+='<div class="item ajax_company_introduce_image" style="background-image: url('+ajaxobj.company_introduce_image[i]+');"></div>';
										banner_p_html+='<a href="javascript:void(0);" onclick="f_company_info.scrollTo(this);" style="background-image: url('+ajaxobj.company_introduce_image[i]+');" class="active"></a>';
									}
								}
								$(".banner-con").append(banner_con_html);
								if(arr_img.length > 1){
									$(".banner-p").append(banner_p_html);
								}
							}
							$(".ajax_company_introduce_word").html(ajaxobj.company_introduce_word);
							$("."+c_val+"_normal").show();
						}
						// 相关链接
						if(ajaxobj.method=='company_link'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$(".block-editor-link").find(".icon-edit").show();
							$("."+c_val+"_edit").hide();
							$("."+c_val+"_normal ul").find("li").remove();
							var item_html = '';
							if(ajaxobj.company_website){
								item_html+='<li>'+
										   '	<span class="link-title">Web端链接</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_company_website" title="'+ajaxobj.company_website+'" target="_blank" href="'+ajaxobj.company_website+'">'+ajaxobj.company_website+'</a>'+
										   '	</div>'+
										   '</li>';
							}
							if(ajaxobj.iphone_url){
								item_html+='<li>'+
										   '	<span class="link-title">iPhone下载链接</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_iphone_url" title="'+ajaxobj.iphone_url+'" target="_blank" href="'+ajaxobj.iphone_url+'">'+ajaxobj.iphone_url+'</a>'+
										   '	</div>'+
										   '</li>';
							}
							if(ajaxobj.pc_url){
								item_html+='<li>'+
										   '	<span class="link-title">PC端下载链接</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_pc_url" title="'+ajaxobj.pc_url+'" target="_blank" href="'+ajaxobj.pc_url+'">'+ajaxobj.pc_url+'</a>'+
										   '	</div>'+
										   '</li>';
							}
							if(ajaxobj.android_url){
								item_html+='<li>'+
										   '	<span class="link-title">Android下载链接</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_android_url" title="'+ajaxobj.android_url+'" target="_blank" href="'+ajaxobj.android_url+'">'+ajaxobj.android_url+'</a>'+
										   '	</div>'+
										   '</li>';
							}
							if(ajaxobj.ipd_url){
								item_html+='<li>'+
										   '	<span class="link-title">iPad下载链接</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_ipd_url" title="'+ajaxobj.ipd_url+'" target="_blank" href="'+ajaxobj.ipd_url+'">'+ajaxobj.ipd_url+'</a>'+
										   '	</div>'+
										   '</li>';
							}
							$("."+c_val+"_normal").find("ul").append(item_html);
							$("."+c_val+"_normal").show();
						}
						// 子产品介绍
						if(ajaxobj.method=='company_sub_project'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							$("."+c_val+"_normal").find(".product-item").remove();
							var item_html = '';
							var arr_item = ajaxobj.productall;
							var arr_item_length = arr_item.length;
							for(var i=0; i<arr_item_length; i++){
								item_html+='<div class="product-item c_sub_product_'+arr_item[i].id+'_item">'+
										   '	<span class="link-title ajax_product_name">'+arr_item[i].product_name+'</span>'+
										   '	<div class="link-con">'+
										   '		<a class="link ajax_product_website" target="_blank" href="'+arr_item[i].product_website+'">'+arr_item[i].product_website+'</a>'+
										   ' 	</div>'+
										   '	<span class="actions">'+
										   '		<a href="javascript:void(0)" class="icon-edit c_sub_product_ajax_edit" rel="c_sub_product_'+arr_item[i].id+'" ajax_act="edit">'+
										   '			<i class="icon iconfont">&#xe60c;</i>'+
										   '		</a>'+
										   '		<a href="javascript:void(0)" class="icon-del c_sub_product_ajax_del" rel="c_sub_product_'+arr_item[i].id+'" ajax_act="del">'+
										   '			<i class="icon iconfont">&#xe604;</i>'+
										   '		</a>'+
										   '		<input type="hidden" name="ajax_item_id" value="'+arr_item[i].id+'" />'+
										   '		<input type="hidden" name="method" value="company_sub_project" />'+
										   '		<input type="hidden" name="ajax" value="1" />'+
										   '		<input type="hidden" name="company_id" value="'+company_id+'" />'+
										   '		<input type="hidden" name="ajax_url" value="'+company_sub_project_ajaxurl+'" />'+
										   '	</span>'+
										   '</div>';
							}
							$("."+c_val+"_normal").append(item_html);
							$("."+c_val+"_normal").show();
						}
						if(ajaxobj.method=='company_team'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();

							// 创始团队
							if(c_val=="c_founder_team"){
								ajaxobj.level == 0 ? ajaxobj.level = '创始人' : ajaxobj.level = '联合创始人';
								var item_html = '<li class="'+c_val+'_'+ajaxobj.id+'_item">'+
												'	<a class="media-avatar" href="'+ajaxobj.home_url+'" target="_blank">'+
												'		<img width="70" height="70" src="'+ajaxobj.image+'">'+
												'	</a>'+
												'	<div class="media-body">'+
												'		<div class="media-heading">'+
												'			<span class="member-name" href="'+ajaxobj.home_url+'" target="_blank">'+ajaxobj.name+'</span>'+
												'			<i class="gray-icon">待确认</i>'+
												'		</div>'+
												'		<span class="member-position">'+ajaxobj.level+'&nbsp;'+ajaxobj.position+'</span>'+
												'		<p class="ng-binding">'+ajaxobj.intro+'</p>'+
												'	</div>'+
												'	<a href="javascript:void(0)" class="icon-del btn-delete '+c_val+'_ajax_del" ajax_act="del" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
												'	<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'">'+
												'</li>';
							}
							// 过往成员 团队成员
							if(c_val=="c_past_team" || c_val=="c_employee_team"){
								if(ajaxobj.employee_level == 0){ajaxobj.employee_level = '技术';}
								if(ajaxobj.employee_level == 1){ajaxobj.employee_level = '设计';}
								if(ajaxobj.employee_level == 2){ajaxobj.employee_level = '产品';}
								if(ajaxobj.employee_level == 3){ajaxobj.employee_level = '运营';}
								if(ajaxobj.employee_level == 4){ajaxobj.employee_level = '市场与销售';}
								if(ajaxobj.employee_level == 5){ajaxobj.employee_level = '行政、人事及财务';}
								if(ajaxobj.employee_level == 6){ajaxobj.employee_level = '投资和并购';}
								if(ajaxobj.employee_level == 7){ajaxobj.employee_level = '其他';}
								var item_html = '<li class="'+c_val+'_'+ajaxobj.id+'_item">'+
												'	<a class="media-avatar" href="'+ajaxobj.home_url+'" target="_blank">'+
												'		<img width="40" height="40" src="'+ajaxobj.image+'">'+
												'	</a>'+
												'	<div class="media-body">'+
												'		<div class="media-heading">'+
												'			<span class="member-name" href="'+ajaxobj.home_url+'" target="_blank">'+ajaxobj.name+'</span>'+
												'			<i class="gray-icon">待确认</i>'+
												'		</div>'+
												'		<span class="member-position">'+ajaxobj.employee_level+'&nbsp;'+ajaxobj.position+'</span>'+
												'		<p class="ng-binding">'+ajaxobj.intro+'</p>'+
												'	</div>'+
												'	<a href="javascript:void(0)" class="icon-del btn-delete '+c_val+'_ajax_del" ajax_act="del" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
												'	<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'">'+
												'</li>';
							}
							
							$("."+c_val+"_normal").find("."+c_val+"_normal_list").append(item_html);
							$("."+c_val+"_edit").find(".init_data").val("");
							$("."+c_val+"_normal").show();
							show_tip();
						}
						// 团队优势
						if(ajaxobj.method=='company_case'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							$("."+c_val+"_normal").find(".ajax_team_advantage").text(ajaxobj.team_advantage);
							$("."+c_val+"_normal").show();
						}
						// 投资案例
						if(ajaxobj.method=='company_invest'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							var new_finance_amount_unit;
							var finance_amount_unit = ajaxobj.finance_amount_unit;
							finance_amount_unit == 1 ? new_finance_amount_unit="$" : new_finance_amount_unit="¥";
							var new_invest_phase;
							var invest_phase = ajaxobj.invest_phase;
							if(invest_phase == 0){new_invest_phase="天使轮";}
							if(invest_phase == 1){new_invest_phase="Pre-A轮";}
							if(invest_phase == 2){new_invest_phase="A轮";}
							if(invest_phase == 3){new_invest_phase="A+轮";}
							if(invest_phase == 4){new_invest_phase="B轮";}
							if(invest_phase == 5){new_invest_phase="B+轮";}
							if(invest_phase == 6){new_invest_phase="C轮";}
							if(invest_phase == 7){new_invest_phase="D轮";}
							if(invest_phase == 8){new_invest_phase="E轮及以后";}
							if(invest_phase == 9){new_invest_phase="并购";}
							var indexes;
							var has_company_status=0;
							var new_company_id = $o.find("input[name='invest_id']").val();
							var $item = $("."+c_val+"_item");
							var $has_company = $item.find("input[name='ajax_invest_id']");
							var has_company_num = $has_company.length;
							$has_company.each(function(i){
								if($(this).val() == new_company_id){
									indexes = i;
									has_company_status=1; 
								};
							});
							if(has_company_status){
								var item_html ='<div class="detail c_invest_exp_'+ajaxobj.id+'_detail">'+
											   '	<span class="time ajax_time">'+ajaxobj.invest_time+'</span>'+
											   '	<div class="desc">'+
											   '		<span class="ng-binding">'+new_invest_phase+'</span>'+
											   '		<span class="ng-scope">| 总投资额'+
											   '			<span class="ajax_finance_amount_unit">'+new_finance_amount_unit+'</span>'+
											   '			<span class="ajax_finance_amount">'+ajaxobj.finance_amount+'</span>万<i class="gray-icon">待确认</i>'+
											   '		</span>'+
											   '	</div>'+
											   '	<div class="edit">'+
											   '		<a href="javascript:void(0)" class="icon-edit c_invest_exp_ajax_edit" ajax_act="edit" rel="c_invest_exp_'+ajaxobj.id+'"><i class="icon iconfont">&#xe60c;</i></a>&nbsp;'+
											   '		<a href="javascript:void(0)" class="icon-del c_invest_exp_ajax_del_sub" ajax_act="del" rel="c_invest_exp_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
											   '		<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'" />'+
											   '		<input type="hidden" name="method" value="company_invest" />'+
											   '		<input type="hidden" name="ajax" value="1" />'+
											   '		<input type="hidden" name="company_id" value="'+company_id+'" />'+
											   '		<input type="hidden" name="ajax_url" value="'+c_invest_exp_ajaxurl+'" />'+
											   '	</div>'+
											   '</div>';
								$item.eq(indexes).find(".details").append(item_html);
							}
							else{
								var item_html = '<div class="invest-col c_invest_exp_'+ajaxobj.invest_company_id+'_item">'+
											'	<div class="info">'+
											'		<div class="operations">'+
											'			<a href="javascript:void(0)" class="icon-del c_invest_exp_ajax_del_main" ajax_act="del" rel="c_invest_exp_'+ajaxobj.invest_company_id+'"><i class="icon iconfont">&#xe604;</i></a>'+
											'			<input type="hidden" name="ajax_invest_id" value="'+ajaxobj.invest_company_id+'">'+
											'			<input type="hidden" name="method" value="company_invest">'+
											'			<input type="hidden" name="ajax" value="1">'+
											'			<input type="hidden" name="company_id" value="'+company_id+'">'+
											'			<input type="hidden" name="ajax_url" value="'+c_invest_exp_ajaxurl+'">'+
											'		</div>'+
											'		<div class="company clearfix">'+
											'			<a class="logo" href="'+ajaxobj.company_url+'" target="_blank">'+
											'				<img src="'+ajaxobj.image+'" width="50 height="50">'+
											'			</a>'+
											'			<div class="desc">'+
											'				<a class="title" href="'+ajaxobj.company_url+'" target="_blank">'+ajaxobj.company_name+'</a>'+
											'				<div class="brief">'+
											'					<span>'+ajaxobj.company_brief+'</span>'+
											'				</div>'+
											'			</div>'+
											'		</div>'+
											'		<p class="ng-binding"></p>'+
											'	</div>'+
											'	<div class="c_invest_exp_'+ajaxobj.id+'_autoheight_wrap">'+
											'		<div class="details">'+
											'			<div class="detail c_invest_exp_'+ajaxobj.id+'_detail">'+
											'				<span class="time">'+ajaxobj.invest_time+'</span>'+
											'				<div class="desc">'+
											'					<span>'+new_invest_phase+'</span>'+
											'					<span class="ng-scope">'+
											'						| 总投资额 <span class="ng-binding">'+new_finance_amount_unit+' '+ajaxobj.finance_amount+'万</span><i class="gray-icon">待确认</i>'+
											'					</span>'+
											'				</div>'+
											'				<div class="edit">'+
											'					<a href="javascript:void(0)" class="icon-edit c_invest_exp_ajax_edit" ajax_act="edit" rel="c_invest_exp_'+ajaxobj.id+'"><i class="icon iconfont">&#xe60c;</i></a>&nbsp;'+
											'					<a href="javascript:void(0)" class="icon-del c_invest_exp_ajax_del_sub" ajax_act="del" rel="c_invest_exp_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
											'					<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'" />'+
											'					<input type="hidden" name="method" value="company_invest" />'+
											'					<input type="hidden" name="ajax" value="1" />'+
											'					<input type="hidden" name="company_id" value="'+company_id+'" />'+
											'					<input type="hidden" name="ajax_url" value="'+c_invest_exp_ajaxurl+'" />'+
											'				</div>'+
											'			</div>'+
											'			<span></span>'+
											'		</div>'+
											'	</div>'+
											'</div>';
								$("."+c_val+"_normal").find(".invest-exp-wrap").append(item_html);
								has_company_num=has_company_num+1;
							}
							$("."+c_val+"_normal").show();
						}
						// 融资经历
						if(ajaxobj.method=='company_experience'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							var finance_amount_unit = ajaxobj.finance_amount_unit;
							var valuation_unit = ajaxobj.valuation_unit;
							finance_amount_unit == 1 ? finance_amount_unit = "$" : finance_amount_unit = "¥";
							valuation_unit == 1 ? valuation_unit = "$" : valuation_unit = "¥";
							var valuation_unit = ajaxobj.valuation_unit;
							valuation_unit == 1 ? valuation_unit = "$" : valuation_unit = "¥";
							var new_financing_phase;
							var finance_amount_name="融资金额";
							var valuation_html = '<div class="f-r">'+
												 '	融资估值：'+
												 '	<span class="price2 ng-binding">'+valuation_unit+
												 '	<span class="price2 ng-binding">'+
												 '		<span class="ajax_valuation">'+ajaxobj.valuation+'</span>万'+
												 '	</span>'+
												 '</div>';
							var financing_phase = ajaxobj.invest_phase;
							if(financing_phase == 0){new_financing_phase="天使轮";}
							if(financing_phase == 1){new_financing_phase="Pre-A轮";}
							if(financing_phase == 2){new_financing_phase="A轮";}
							if(financing_phase == 3){new_financing_phase="A+轮";}
							if(financing_phase == 4){new_financing_phase="B轮";}
							if(financing_phase == 5){new_financing_phase="B+轮";}
							if(financing_phase == 6){new_financing_phase="C轮";}
							if(financing_phase == 7){new_financing_phase="D轮";}
							if(financing_phase == 8){new_financing_phase="E轮及以后";}
							if(financing_phase == 9){new_financing_phase="并购";finance_amount_name="并购金额";valuation_html="";}
							if(financing_phase == 10){new_financing_phase="上市";finance_amount_name="上市金额";valuation_html="";}

							var invest_subject_info = ajaxobj.invest_subject_info; // 投资主体方
							var invest_subject_info_html = '';
							if(invest_subject_info){
								var invest_subject_info_length = invest_subject_info.length;
								for(var i=0; i<invest_subject_info_length; i++){
									invest_subject_info_html+='<li>'+
															  '	<a class="logo" href="'+invest_subject_info[i].home_url+'" target="_blank">'+
															  '		<img alt="" width="50" src="'+invest_subject_info[i].image+'">'+
															  '	</a>'+
															  '	<a class="name" href="'+invest_subject_info[i].home_url+'" target="_blank">'+
															  '		<span class="ng-binding">'+invest_subject_info[i].user_name+'</span>'+
															  '	</a>'+
															  '</li>';
								}
								var item_html = '<div class="financing-col c_financing_exp_'+ajaxobj.id+'_item">'+
											'		<div class="financing-exp">'+
											'			<div class="financing-list-heading clearfix">'+
											'				<div class="financing-con">'+
											'					<span class="round">'+new_financing_phase+'</span>'+
											'					<span class="date">'+ajaxobj.invest_time+'</span>'+
											'					<a href="'+ajaxobj.finance_pressurl+'" target="_blank" class="pressUrl">相关报道</a>'+
											'				</div>'+
											'				<div class="tool">'+
											'					<a href="javascript:void(0)" class="icon-del '+c_val+'_ajax_edit" ajax_act="edit" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe60c;</i></a>'+
											'					<a href="javascript:void(0)" class="icon-del '+c_val+'_ajax_del" ajax_act="del" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
											'					<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'">'+
											'					<input type="hidden" name="method" value="company_experience">'+
											'					<input type="hidden" name="ajax" value="1">'+
											'					<input type="hidden" name="company_id" value="'+company_id+'">'+
											'					<input type="hidden" name="ajax_url" value="'+c_financing_exp_ajaxurl+'">'+
											'				</div>'+
											'			</div>'+
											'			<div class="financing-price">'+
											'				<div class="f-l">'+
											'					<span>'+finance_amount_name+'：</span>'+
											'					<span class="price1">'+finance_amount_unit+'&nbsp;'+ajaxobj.finance_amount+'万</span>'+
											'				</div>'+
															valuation_html+
											'			</div>'+
											'			<ul class="financing-list-member list-unstyled ul_maxheight">'+invest_subject_info_html+
											'			</ul>'+
											'		</div>'+
											'	</div>';
							}
							else{
								if(ajaxobj.finance_pressurl){
									var html_finance_pressurl = '<a href="'+ajaxobj.finance_pressurl+'" target="_blank" class="pressUrl">相关报道</a>';
								}
								else{
									var html_finance_pressurl='';
								}
								var item_html = '<div class="financing-col c_financing_exp_'+ajaxobj.id+'_item">'+
											'		<div class="financing-exp">'+
											'			<div class="financing-list-heading clearfix">'+
											'				<div class="financing-con">'+
											'					<span class="round">'+new_financing_phase+'</span>'+
											'					<span class="date">'+ajaxobj.invest_time+'</span>'+html_finance_pressurl+
											'				</div>'+
											'				<div class="tool">'+
											'					<a href="javascript:void(0)" class="icon-del '+c_val+'_ajax_edit" ajax_act="edit" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe60c;</i></a>'+
											'					<a href="javascript:void(0)" class="icon-del '+c_val+'_ajax_del" ajax_act="del" rel="'+c_val+'_'+ajaxobj.id+'"><i class="icon iconfont">&#xe604;</i></a>'+
											'					<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'">'+
											'					<input type="hidden" name="method" value="company_experience">'+
											'					<input type="hidden" name="ajax" value="1">'+
											'					<input type="hidden" name="company_id" value="'+company_id+'">'+
											'					<input type="hidden" name="ajax_url" value="'+c_financing_exp_ajaxurl+'">'+
											'				</div>'+
											'			</div>'+
											'			<div class="financing-price">'+
											'				<div class="f-l">'+
											'					<span>'+finance_amount_name+'：</span>'+
											'					<span class="price1">'+finance_amount_unit+'&nbsp;'+ajaxobj.finance_amount+'万</span>'+
											'				</div>'+
															valuation_html+
											'			</div>'+
											'			<div class="financing-unknow">未披露</div>'+
											'		</div>'+
											'	</div>';
							}
							$("."+c_val+"_normal").find(".financing-exp-wrap").append(item_html);
							$("."+c_val+"_normal").show();
						}
						// 过往投资方
						if(ajaxobj.method=='company_investor'){
							$o.find(".btn-loading-wrap").remove();
							$o.find(".save-btn").show();
							$("."+c_val+"_icon_edit").show();
							$("."+c_val+"_edit").hide();
							var item_html = '<li class="'+c_val+'_'+ajaxobj.id+'_item"">'+
											'	<a class="media-avatar" href="'+ajaxobj.home_url+'" target="_blank">'+
											'		<img alt="'+ajaxobj.image+'" width="40" height="40" src="'+ajaxobj.image+'">'+
											'	</a>'+
											'	<div class="media-body">'+
											'		<div class="media-heading" href="'+ajaxobj.home_url+'" target="_blank">'+
											'			<span class="member-name oh">'+ajaxobj.name+'</span>'+
											'			<i class="gray-icon">待确认</i>'+
											'		</div>'+
											'		<p title="asdads" style="overflow: hidden; text-overflow: ellipsis; -webkit-box-orient: vertical; display: -webkit-box; -webkit-line-clamp: 3;">'+ajaxobj.brief+'</p>'+
											'	</div>'+
											'	<a href="javascript:void(0)" class="icon-del btn-delete c_past_investors_ajax_del" ajax_act="del" rel="c_past_investors_'+ajaxobj.id+'">'+
											'		<i class="icon iconfont">&#xe604;</i>'+
											'	</a>'+
											'	<input type="hidden" name="ajax_item_id" value="'+ajaxobj.id+'" />'+
											'	<input type="hidden" name="method" value="company_investor" />'+
											'	<input type="hidden" name="ajax" value="1" />'+
											'	<input type="hidden" name="ajax_url" value="'+c_past_investors_ajaxurl+'" />'+
											'</li>';
							$("."+c_val+"_normal").find(".past-investors-list").append(item_html);
							$("."+c_val+"_normal").show();
						}
					},1500);
					
				
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
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
			error:function(ajaxobj){}
		});
		return false;
	});
}

// 提交审核
function submit_finance(obj)
{
	var checked_status = true;
	var $company_basic = $(".edit-basic-info");
	var $company_intro = $(".c_company_intro_edit");
	var edit_form_len = $(".main-content-wrap").find(".edit_form").length;
	for(var i=0; i<edit_form_len; i++){
		if($(".main-content-wrap").find(".edit_form").eq(i).is(':visible')){
			checked_status = false;
			$.showErr("您还有未保存的表单，请先保存后再提交审核");
			return false;
		}
	}
	if(checked_status){
		var ajaxurl = $(obj).attr("ajaxurl");
		var jump = $(obj).attr("jump");
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status)
				{
					$.showSuccess(ajaxobj.info,function(){
						 location.href = jump;
					});
				}
				else
				{
					if(ajaxobj.jump!="")
						location.href = ajaxobj.jump;
					else
					$.showErr(ajaxobj.info);
				}
			},
			error:function(ajaxobj)
			{

			}
		});
	}
}