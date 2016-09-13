function total_price(class_name){
    var total_income=0.00;
    var total_out=0.00;
	 
     $(class_name).each(function(i){
        var item_income=0.00;
        var item_out=0.00;
         $(this).find(".income_table .amount").each(function(){
            if($(this).val()!=''){
                item_income=parseFloat(item_income+parseFloat($(this).val()));
                item_income = Math.round(item_income*100)/100;
            }
        });
        $(this).find(".out_table_1 .amount").each(function(){
            if($(this).val()!=''){
                item_out=parseFloat(item_out+parseFloat($(this).val()));
                item_out = Math.round(item_out*100)/100;
            }
        });
        $(this).find(".item_income").html(item_income);
        $(this).find(".item_income_input").val(item_income);
        $(this).find(".item_out").html(item_out);
        $(this).find(".item_out_input").val(item_out);
        total_income = Math.round((total_income+item_income)*100)/100;
        total_out = Math.round((total_out+item_out)*100)/100;
    });
    total_left = Math.round((total_income-total_out)*100)/100;
	if(class_name=='.history_table'){
		$("#totalsr").html(total_income);
	    $("#totalkz").html(total_out);
	    $("#totalyk").html(total_left);
	}
	if(class_name=='.plan_table'){
		$("#totalsr_plan").html(total_income);
	    $("#totalkz_plan").html(total_out);
	    $("#totalyk_plan").html(total_left);
	}
    
 }

 // 检测money,输入的非正规数字时归零处理 
function formatMoney(s,type) {
    var zf = 0;
    if(s<0) {
        zf = 1;
        s = 0;
    }
    if (/[^0-9\.]/.test(s)) return "0";
    if (s == null || s == "") return "0";
    s = s.toString().replace(/^(\d*)$/, "$1.");
    s = (s + "00").replace(/(\d*\.\d\d)\d*/, "$1");
    s = s.replace(".", ",");
    var re = /(\d)(\d{3},)/;
    while (re.test(s))
    s = s.replace(re, "$1,$2");
    s = s.replace(/,(\d\d)$/, ".$1");
    if (type == 0) {
        var a = s.split(".");
        if (a[1] == "00") {
            s = a[0];
        }
    }
    if(zf==1) {
        s = "-"+s;
    }
    return s;
}

// 限制只能输入金额
function amount(th){
    var regStrs = [
        ['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
        ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
        ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
        ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
    ];
    for(i=0; i<regStrs.length; i++){
        var reg = new RegExp(regStrs[i][0]);
        th.value = th.value.replace(reg, regStrs[i][1]);
    }
}