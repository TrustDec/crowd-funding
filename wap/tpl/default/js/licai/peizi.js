
/*
 * 转入借款金额，返回倍数列表
 *array(
	[0]=>array(id=0,lever=5,money=100)	
	[1]=>array(id=1,lever=10,money=200)
	[2]=>array(id=2,lever=15,money=350)
 )
 * 
 */
function getPeiziLeverList(borrow_money,peizi_conf) {	
	borrow_money = parseInt(borrow_money);
	var lever_list = peizi_conf.lever_list;
	
	for (var i=0;i<lever_list.length;i++){
		//alert('borrow_money:' + borrow_money + ';lever_list.length:' + lever_list.length + ';min_money:' + lever_list[i].min_money + ';max_money:' + lever_list[i].max_money);
		if (borrow_money >= lever_list[i].min_money && borrow_money <= lever_list[i].max_money){
			//alert(lever_list[i].min_money);
			for (var j=0;j<lever_list[i].lever_array.length;j++){
				//var item = lever_list[i].lever_array[j];
				//item.cc = 'ddd';
				lever_list[i].lever_array[j].money = Math.floor(borrow_money / lever_list[i].lever_array[j].lever);
				lever_list[i].lever_array[j].money_format = formatMoney(lever_list[i].lever_array[j].money);
				
				//alert(lever_list[i].lever_array[j].cc);
			}
			
			return lever_list[i].lever_array;
		}
	}
	
	return new Array();
}

function getPeizi2LeverList(cost_money,month,rate_id,peizi_conf) {
	cost_money = parseInt(cost_money);
	var lever_list = peizi_conf.lever_list;
	
	var money_list = new Array();
	for (var i=0;i<lever_list.length;i++){
		//alert('borrow_money:' + borrow_money + ';lever_list.length:' + lever_list.length + ';min_money:' + lever_list[i].min_money + ';max_money:' + lever_list[i].max_money);
		if (cost_money >= lever_list[i].min_money && cost_money <= lever_list[i].max_money){
			//alert(lever_list[i].min_money);
			
			
			for (var j=0;j<lever_list[i].lever_array.length;j++){
				
				var lever = lever_list[i].lever_array[j].lever;
				
				var pc = getPeiziCacl(cost_money * lever,lever,month,rate_id,peizi_conf);
				if (pc.merge_rate_show == 1){
					var rm = pc.total_rate_list[0];
				}else{
					var rm = pc.rate_list[0];
				}
				//	
				pc.forbidden = true;
				pc.rate_format = rm.rate_format;
				//alert(r1.rate_format);
				money_list.push(pc);
			}
			
			/*
			for (var i=peizi_conf.min_lever;i<=peizi_conf.max_lever;i++){
				var lever = i;
				var r1 = getPeiziCacl(cost_money * lever,lever,month,rate_id,peizi_conf);
				
				r1.forbidden = true;
				//alert(r1.rate_format);
				money_list.push(r1);
			}
			*/
			
			//计算最大利率;
			var max_rate = 0;
			for (var i=0;i<money_list.length;i++)
			{      
				if (money_list[i].rate > max_rate){
					max_rate = money_list[i].rate;
				}			
			}
			
			for (var i=0;i<money_list.length;i++)
			{      
				if (money_list[i].rate < max_rate){
					money_list[i].rate_title = '优惠';
				}else{
					money_list[i].rate_title = '';
				}			
			}		
			
			break;
		}		
	}
	
	return money_list;
}

/**
 * 包月 使用
 * 输入本金，返回 可以获得的 实盘(借款)金额
 * @param money 本金
 * @returns
 * 
 *  array(
	[0]=>array(id=1,lever=1,borrow_money=100,borrow_money_format=300,rate = 0.014, rate_format=1.4分/每月,forbidden=true)	
	[1]=>array(id=2,lever=2,borrow_money=200,borrow_money_format=300,rate = 0.014, rate_format=1.4分/每月,forbidden=true)
	[2]=>array(id=3,lever=3,borrow_money=300,borrow_money_format=300,rate = 0.014, rate_format=1.4分/每月,forbidden=true)
 )
 */
function getPeizi2LeverList_old(cost_money,month,rate_id,peizi_conf) {
	cost_money = parseInt(cost_money);
	month = parseInt(month);
	
	var rate_list = peizi_conf.rate_list;
	
	var money_list = new Array();
	for (var i=peizi_conf.min_lever;i<=peizi_conf.max_lever;i++){
		var r1 = new Object();
		
		r1.id = i;
		r1.lever = i;
		r1.borrow_money = cost_money * r1.lever;
		r1.borrow_money_format = getPeiziMoneyFormat(r1.borrow_money);
		
		//alert(lever_coefficient_list.length);
		r1.rate_id = 0;
		r1.rate = 0;
		r1.rate_format = '';
		
		//alert('r1.lever:' + r1.lever);
		if (r1.borrow_money > 0 ){
			for (var k=0;k<rate_list.length;k++){
				//alert(k);
				var lm = rate_list[k];
				
				if (r1.lever >= lm.min_lever && r1.lever <= lm.max_lever && month >= lm.min_month && month <= lm.max_month && r1.borrow_money >= lm.min_money && r1.borrow_money <= lm.max_money){
					//alert('lm.id:' + lm.id);
					//账户管理费
					var rate_f;
					if (rate_id == 2){
						r1.rate = lm.rate2;
					}else if (rate_id == 3){
						r1.rate = lm.rate3;
					}else if (rate_id == 4){
						r1.rate = lm.rate4;
					}else{
						r1.rate = lm.rate1;
						rate_id = 1;
					}
					
					//var ra = parseFloat(r1.rate);
					//alert(ra + ';' + ra.toFixed(4));
					//r1.rate = ra.toFixed(4);
					var rate_f = getPeiziRateFormat(rate_id,r1.rate,lm.type);
					r1.rate_id = rate_f.id;
					r1.rate = rate_f.rate;
					r1.rate_format = rate_f.rate_format;	
					
					//break;
				}
			}
		}else{
			r1.rate = 0;
			r1.rate_id = 1;
			r1.rate = 0;
			r1.rate_format = '0 分/月';
		}
		
		if (r1.borrow_money > 0 && r1.borrow_money <= peizi_conf.max_money){
			r1.forbidden = true;
		}else{
			r1.forbidden = false;
		}
		money_list.push(r1);
	}
	
	//计算最大利率;
	var max_rate = 0;
	for (var i=0;i<money_list.length;i++)
	{      
		if (money_list[i].rate > max_rate){
			max_rate = money_list[i].rate;
		}			
	}
	
	for (var i=0;i<money_list.length;i++)
	{      
		if (money_list[i].rate < max_rate){
			money_list[i].rate_title = '优惠';
		}else{
			money_list[i].rate_title = '';
		}			
	}
	
	return money_list;
}

/**
 * 输入参数
 * borrow_money: 实盘金额(借款金额)
 * lever: 倍数
 * month: 月份（资金使用期限）
 * rate: 利率字段（1,2,3,4 应对 rate1,rate2,rate3,rate4)
 *
 * 输出参数
 * lever:倍率
 * borrow_money
 * borrow_money_format
 * forbidden
 * 
 * total_money:总操盘资金
 * warning_line:亏损警戒线
 * open_line:亏损平仓线
 * 
 * site_rate: 服务费利率
 * site_rate_format: 服务费利率格式化
 * site_money: 服务费
 * site_money_format: 服务费格式化后 
 *  
 * limit_info: 仓位限制消息
 * is_show_today: 开始交易时间，是否显示：今天; 1显示;0不显示;
 * payoff_rate: 盈利比如：0.7则，实际盈利的70%归操盘者；30%归平台
 * payoff_rate_format
 * rate_list: 借款利率选择列表，至少有一个
 *		rate_id
 *		rate
 *		rate_format
 *		rate_money
 *		rate_money_format
 *
 * 	total_rate_list: 借款利率 + 服务费利 合并 选择列表，至少有一个
 *		rate_id
 *		rate
 *		rate_format
 *		rate_money
 *		rate_money_format
 * 
 */
function getPeiziCacl(borrow_money,lever,month,rate_id,peizi_conf) {
	
	//平仓金额=投入本金*倍数 + 投入本金 * 倍数 * 平仓系数
	//警戒金额=投入本金*倍数 + 投入本金 * 倍数 * 警戒系数
	var lever_coefficient_list = peizi_conf.lever_coefficient_list;
	var rate_list = peizi_conf.rate_list;
	
	borrow_money = parseInt(borrow_money);
	lever = parseInt(lever);
	month = parseInt(month);
	
	if (lever == 0)  lever = 1;
	
	//获得本金
	var cost_money = Math.floor(borrow_money / lever);
	
	var parmar = new Object();
	

	
	parmar.merge_rate_show = peizi_conf.merge_rate_show;//前台合并利息展示，即将:利率与服务费合并; 0:分开展示;1:合并展示
	parmar.cost_money = cost_money;
	parmar.borrow_money = borrow_money;
	parmar.borrow_money_format = getPeiziMoneyFormat(parmar.borrow_money);
	parmar.lever = lever;	
	//总操盘资金
	parmar.total_money = borrow_money + cost_money;
	parmar.total_money_format = formatMoney(parmar.total_money);
	
	parmar.is_show_today = false;
	parmar.forbidden = true;
	
	parmar.site_rate = 0;
	parmar.site_rate_format = '';	
	parmar.site_rate_money = 0;

	
	/*
	parmar.rate = 0;
	parmar.rate_id = 1;
	parmar.rate = 0;	
	*/
	if (peizi_conf.type == 2){
		//parmar.rate_format = '0 分/月';
		parmar.site_rate_money_format = '0 分/月';
	}else{
		//parmar.rate_format = '免费';
		parmar.site_rate_money_format = '免费';
	}
	
	parmar.warning_line = 0;
	//亏损平仓线
	parmar.open_line = 0;						
	parmar.warning_line_format = formatMoney(parmar.warning_line);
	parmar.open_line_format = formatMoney(parmar.open_line);
	
	
	for (var i=0;i<lever_coefficient_list.length;i++){
		var lm = lever_coefficient_list[i];
		if (lm.lever == lever){
			//亏损警戒线
			parmar.warning_line = Math.floor(borrow_money + borrow_money * lm.warning_coefficient);
			//亏损平仓线
			parmar.open_line = Math.floor(borrow_money + borrow_money  * lm.open_coefficient);
									
			parmar.warning_line_format = formatMoney(parmar.warning_line);
			parmar.open_line_format = formatMoney(parmar.open_line);
			
			break;
		}
	}
	
	
	for (var k=0;k<rate_list.length;k++){
		//alert(k);
		var lm = rate_list[k];
		
		if (lever >= lm.min_lever && lever <= lm.max_lever && month >= lm.min_month && month <= lm.max_month && borrow_money >= lm.min_money && borrow_money <= lm.max_money){
			//alert('lm.id:' + lm.id);
			
			//服务费
			//alert(lm.site_rate);
			//var site_rate_f = getPeiziRateFormat(0,lm.site_rate,lm.type);
			var site_rate_f = getPeiziRateObjFormat(0,lm.site_rate,lm.type,borrow_money);
			
			parmar.site_rate = site_rate_f.rate;
			parmar.site_rate_format = site_rate_f.rate_format;	
			parmar.site_rate_money = site_rate_f.rate_money;
			parmar.site_rate_money_format = site_rate_f.rate_money_format;

			/*
			//利息费			
			var rate_f;
			if (rate_id == 2){
				parmar.rate = lm.rate2;
			}else if (rate_id == 3){
				parmar.rate = lm.rate3;
			}else if (rate_id == 4){
				parmar.rate = lm.rate4;
			}else{
				parmar.rate = lm.rate1;
				rate_id = 1;
			}			
							
			//parmar.rate = parmar.rate.toFixed(4);
			var rate_f = getPeiziRateObjFormat(rate_id,parmar.rate,lm.type,borrow_money);
			
			parmar.rate_id = rate_f.id;
			parmar.rate = rate_f.rate;
			parmar.rate_format = rate_f.rate_format;
			parmar.rate_money = rate_f.rate_money;
			parmar.rate_money_format = rate_f.rate_money_format;
			*/
			
			
			//仓位限制消息
			parmar.limit_info = lm.limit_info;	
			parmar.payoff_rate = lm.payoff_rate;
			
			if (parmar.payoff_rate == 0) parmar.payoff_rate = 1;
			
			if (parmar.payoff_rate == 1){
				parmar.payoff_rate_format = '全';	
			}else{
				parmar.payoff_rate_format = (parmar.payoff_rate * 100).toFixed(0) + '%';	
			}
			parmar.payoff_rate_format = (parmar.payoff_rate * 100).toFixed(0) + '%';
			
			parmar.limit_info = parmar.limit_info.replace("{payoff_format}",parmar.payoff_rate_format);
			
			
			parmar.is_show_today = lm.is_show_today;	
			
			parmar.rate_list = new Array();
			parmar.rate_list.push(getPeiziRateObjFormat(1,lm.rate1,lm.type,borrow_money));
			if (lm.rate2 > 0){		
				parmar.rate_list.push(getPeiziRateObjFormat(2,lm.rate2,lm.type,borrow_money));
			}
			if (lm.rate3 > 0){				
				parmar.rate_list.push(getPeiziRateObjFormat(3,lm.rate3,lm.type,borrow_money));
			}
			if (lm.rate4 > 0){				
				parmar.rate_list.push(getPeiziRateObjFormat(4,lm.rate4,lm.type,borrow_money));
			}		
		
			//parmar.total_rate1 = parseFloat(lm.rate1) + parseFloat(parmar.site_rate);
			//借款利息与服务费合并，对外展示收取
			parmar.total_rate_list = new Array();
			var total_rate1 = parseFloat(lm.rate1) + parseFloat(parmar.site_rate);
			parmar.total_rate_list.push(getPeiziRateObjFormat(1,total_rate1,lm.type,borrow_money));
			if (lm.rate2 > 0){		
				var total_rate2 = parseFloat(lm.rate2) + parseFloat(parmar.site_rate);
				parmar.total_rate_list.push(getPeiziRateObjFormat(2,total_rate2,lm.type,borrow_money));
			}
			if (lm.rate3 > 0){		
				var total_rate3 = parseFloat(lm.rate3) + parseFloat(parmar.site_rate);
				parmar.total_rate_list.push(getPeiziRateObjFormat(3,total_rate3,lm.type,borrow_money));
			}
			if (lm.rate4 > 0){	
				var total_rate4 = parseFloat(lm.rate4) + parseFloat(parmar.site_rate);
				parmar.total_rate_list.push(getPeiziRateObjFormat(4,total_rate4,lm.type,borrow_money));
			}
			
			//alert(parmar.rate_list.length);
			//parmar.rate_list = rate_list_tmp;
		//	has_rate = true;
			break;
		}
	}	
	

		
	
	return parmar;
}

function getPeiziRateObjFormat(rate_id,rate,type,borrow_money) {
	var rm_r = new Object();
	
	var rate_r = getPeiziRateFormat(rate_id,rate,type);
	rm_r.rate_id = rate_r.id;
	rm_r.rate = rate_r.rate;
	rm_r.rate_format = rate_r.rate_format;

	rm_r.rate_money = (borrow_money * rate).toFixed(2);
	if (rm_r.rate_money == 0)
		rm_r.rate_money_format = '免费';
	else
		rm_r.rate_money_format = getPeiziMoneyFormat(rm_r.rate_money);
	
	return rm_r;
}

function getPeiziMoneyFormat(money) {
	if (money >= 10000){
		money = (money / 10000)+'万';
	}

	return money;
}

function formatMoney(num, n) {
    num = String(num.toFixed(n ? n: 2));
    var re = /(-?\d+)(\d{3})/;
    while (re.test(num)) {
        num = num.replace(re, "$1,$2")
    };
    return n ? num: num.replace(/^([0-9,]+\.[1-9])0$/, "$1").replace(/^([0-9,]+)\.00$/, "$1");
}

//利率格式化
function getPeiziRateFormat(rate_id,rate,type) {
	//var ra = parseFloat(r1.rate);
	//alert(ra + ';' + ra.toFixed(4));
	//r1.rate = ra.toFixed(4);
	
	var r1 = new Object();
	r1.id = rate_id;
	r1.rate = rate;
	if (r1.rate == 0){
		r1.rate_format = '免';
	}else{
		if (type == 2){
			r1.rate_format = parseFloat((r1.rate * 100).toFixed(2)) + '分/每月';
		}else{
			r1.rate_format = parseFloat((r1.rate * 100).toFixed(2))  + '分/每日';
		}
	}	
	
	return r1;
}