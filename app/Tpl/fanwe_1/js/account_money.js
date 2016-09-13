//解决input中placeholder值在ie中无法支持的问题
    var doc=document,inputs=doc.getElementsByTagName('input'),supportPlaceholder='placeholder'in doc.createElement('input'),placeholder=function(input){
        var text=input.getAttribute('placeholder'),defaultValue=input.defaultValue;
        if(defaultValue==''){
            input.value=text
        }
        input.focus=function(){
            if(input.value===text){
                this.value=''
            }
        };
        input.blur=function(){
            if(input.value===''){
                this.value=text
            }
        }
    };
    if(!supportPlaceholder){
        for(var i=0,len=inputs.length;i<len;i++){
            var input=inputs[i],text=input.getAttribute('placeholder');
            if(input.type==='text'&&text){
                placeholder(input)
            }
        }
    }
	$(function(){
		$("#paypassword").val("");	
	});
/**********************转账操作********************************/
	var USERNAME="{$user_info.user_name}";
	var useT={		
		tableTrOne:"#FBFBFB",
		tableTrTwo:"#F5F5F5",
		collection_state:true,
		operation_information:true,
		balance:5000,
		Default:"<p style='text-align:center;'>没有记录</p>",
//用户名
		account_name:function(name){
			var reg=/^[\u4e00-\u9fa5a-zA-Z\d_]+$/;
			if(name.match(reg)){
				if(name==USERNAME){
				useT.errorAlert(".hideDoc","warning","不能给自己转账");
				return false;
				}else{
				var query=new Object();
				query.val=name;
					$.ajax({
						url:APP_ROOT+"/index.php?ctl=ajax&act=check_user1",
							data:query,
							dataType:"json",
							success: function(data){
								if(data.status===1){
									useT.errorAlert(".hideDoc","through","&radic;");										
									useT.peer=name;
								}else{
									useT.errorAlert(".hideDoc","warning","用户不存在;");										
								}							
							},
							error:function(){
								useT.errorAlert(".hideDoc","warning","请重新填写");									
							}					
					});
					return true;
				}
			} else{
					useT.errorAlert(".hideDoc","warning","包含非法字符或为空");
					
					return false;
			}
			
		},		
		/*collection_name:function(name){
			var reg=/^[\u2E80-\uFE4F]+$/ ;
			if(name.match(reg)!==null){
				useT.errorAlert(".hideDoc1","through","&radic;");
			}else{
				useT.errorAlert(".hideDoc1","warning","请输入对方真实姓名;");		
			}
		},*/
//手机
		mobile_phone:function(mobile){
			var reg=/^1[3|4|5|7|8]\d{9}$/;
			if(mobile.match(reg)){
				var query=new Object();
				query.val=$("#account_name").val();
				query.val1=mobile;				
					$.ajax({
						url:APP_ROOT+"/index.php?ctl=ajax&act=check_mobile",
							data:query,
							dataType:"json",
							success: function(data){
								if(data.status===1){
									useT.errorAlert(".hideDoc2","through","&radic;");	
									useT.phone=mobile;
								}else{
									useT.errorAlert(".hideDoc2","warning","号码不匹配;");	
								}						
							},
							error:function(){
								useT.errorAlert(".hideDoc2","warning","请重新填写");	
								
							}					
					});
					return true;
			}else{
				useT.errorAlert(".hideDoc2","warning","手机号码有误;");	
				return false;
			}
		},
//余额
		account_number:function(number){
			var reg= /^([1-9][\d]{0,10}|0)(\.[\d]{1,2})?$/;
			if(number.match(reg)){
				var num=parseFloat(number);
				var query=new Object();
					query.name=USERNAME;
					query.val=num;		
					$.ajax({
							url:APP_ROOT+"/index.php?ctl=ajax&act=user_money",
							data:query,
							dataType:"json",
							success: function(data){
								var money=parseFloat(data.status)-num;	
								if(money>0||money>num){
									useT.errorAlert(".hideDoc3","through","&radic;");
									useT.transferMoney=num;
									useT.moneyNUM=money;
								}else{
									useT.errorAlert(".hideDoc3","warning","您的余额不足");										
								}
							},
							error:function(){
								useT.errorAlert(".hideDoc3","warning","请重新填写");									
							}					
					});
				return true;
			}else{
				useT.errorAlert(".hideDoc3","warning","请输入金额");
				return false;
			}
		},
//备注
		account_remark:function(val){
				useT.errorAlert(".hideDoc4","through","&radic;");
				useT.remark=val;
				return true;
		},
//转账提交信息
		account_user_money:function(){
					var moneyNum=new Object();				
					moneyNum.num=useT.moneyNUM;
					moneyNum.user=USERNAME;
					var time=useT.submitTime();
					var TransferInformation={
								username:USERNAME,	
								money:useT.moneyNUM,
								moneyNum:useT.transferMoney,	
								time:time.timeOne,		
								peerName:useT.peer,		
								peerPhone:useT.phone,	
								//peerTime:,			
								remark:useT.remark,	
								//state:,				
								serial:	time.timeTwo
					};
					$.ajax({
						url:APP_ROOT+"/index.php?ctl=ajax&act=userMoney",
						data:moneyNum,
						dataType:"json",
						success:function(data){
							show2_pay_tip("操作成功,3秒后刷新此页面");							
							//日志生成入口入口							
							useT.commitLog(TransferInformation);		
							return;							
						},
						error:function(){							
							show2_pay_tip("操作失败");
							return;
						}
					});
		},
//警告提示
		errorAlert:function(element,fontColor,str){
			fontColor=="through"?$(element).removeClass("warning"):$(element).removeClass("through");
			return $(element).addClass(fontColor).html("&nbsp;&nbsp;&nbsp;&nbsp;"+str);
		},
//日志开始
		commitLog:function(message){
			$.ajax({
				url:APP_ROOT+"/index.php?ctl=ajax&act=commit_Log",
				data:message,
				dataType:"json",
				success:function(data){
					if(data.status){
						//show2_pay_tip("转账成功,可在操作日志查看转账信息");
						setTimeout(function(){
							window.location=APP_ROOT+"/index.php?ctl=account&act=money_withdrawal";							
						},2000);
						
					}else{
						show2_pay_tip("转账成功,交易日志生成失败,请联系客服");	
					}
				},
				error:function(){
					alert("请求失败");	
				}			
			});
		},
//流水号及事件
		submitTime:function(){
			var todayDate=new Date();
			var year=todayDate.getFullYear();
			var date=todayDate.getDate();
			var month=todayDate.getMonth()+1;
			var hour=todayDate.getHours();
			var mininutes=todayDate.getMinutes();
			var seconds=todayDate.getSeconds();
			var ran=Math.round((Math.random())*10000);
			var objTime={
				timeOne:year+"-"+month+"-"+date+" "+hour+":"+mininutes,//转账提交时间
				timeTwo:year+""+month+""+date+""+hour+""+mininutes+""+seconds+""+ran
			};
			return objTime;
		}
		
	};
//表单事件绑定
	//转账
	$("#account_name").bind("blur",function(){
		useT.account_name($(this).val());
	});
	//真实姓名
	/*$("#collection_name").bind("blur",function(){
		useT.collection_name($(this).val());
	});*/
	$("#mobile_phone").bind("blur",function(){
		useT.mobile_phone($(this).val());
	});
	$("#account_number").bind("blur",function(){
		useT.account_number($(this).val());
	});
	$("#account_remark").bind("blur",function(){
		if($(this).val().length>0){
			useT.account_remark($(this).val());
		}		
	});
	//提交
	$(".submitButton").bind("click",function(){	
			var paypassword=$("input[name='paypassword']").val();						
			if(!paypassword.length>0){
				$.showErr("请输入支付密码");
				return false;
			}else{
				var temp=function (){
					for(var i=0;$(".doc").length;i++){
						if(!$(".doc").eq(i).hasClass("warning")){
								if(i=$(".doc").length-1){
									return true;
								}
						}else{
								return false;
						}
					}
				}
				var temp1=function(){for(var i=0;i<$(".value").length;i++){
						if($(".value").eq(i).val().length>0){
							if(i=$(".value").length-1){
									return true;
								}
						}else{
							return false;
						}
					}
			
				}
				var pwd=new Object();
				pwd.val=paypassword;
				pwd.uName=USERNAME;
				$.ajax({
					url:APP_ROOT+"/index.php?ctl=ajax&act=pay_password",
					data:pwd,
					dataType:"json",
					success: function(data){
						if(data.status==1&&temp()&&temp1()){							
							show1_pay_tip();
						return;		
						}else if(data.status==0){
							show2_pay_tip("支付密码错误");
							return;
						}else{
							show2_pay_tip("请正确填写");
						}						
					},
					error:function(){
						useT.errorAlert(".hideDoc2","warning","服务器异常,稍后再试");						
					}		
					
				});
	
			}			
	});
	
//选项卡	
/*$(".default_cur").click(function(){	
	var index=$(this).index();
	for(var i=0;i<$(".default_cur").length;i++){
		//$(".default_cur").eq(i).removeClass("cur");
		//$(".withdrawal_box"+i).hide();	
	//}
	$(this).addClass("cur");
	$(".withdrawal_box"+index).show();	
	
});*/
//操作日志判断
	if(useT.collection_state){
		$("#collectionState").show();
	}else{
		$("#collectionState").parent().html(useT.Default);
	}
	if(useT.operation_information){
		$("#operationInformation").show();
	}else{
		$("#operationInformation").parent().html(useT.Default);
	}
//提示框
function show1_pay_tip()
{
	var html =  '<div class="pay_tip_box">'+
				'	<div class="empty_tip" style="font-size:14px;">您确定转账吗?</div>'+
				'	<div class="blank"></div>'+
				/*'	<div class="choose">确认无误请点击提交：</div>'+*/
				'   <div class="blank15"></div>'+
				'	<div class="tc">'+
				'		<span class="ui-center-button theme_bgcolor" id="check_btn" rel="green">确定</span>'+
				'		<span class="ui-center-button bg_red" id="choose_btn" rel="blue">取消</span>'+
				'	</div>'+
				'</div>'+
				'<div class="blank"></div>';
	$.weeboxs.open(html, {boxid:'pay_tip',contentType:'text',showButton:false, showCancel:true, showOk:false,title:'温馨提示',width:350,type:'wee'});
	$("#check_btn").bind("click",function(){	
		useT.account_user_money();
		return;
	});
	$("#choose_btn").bind("click",function(){
		close_pop();
		return false;
	});
}
function show2_pay_tip(value){
	var html =  '<div class="pay_tip_box">'+
				'	<div class="empty_tip" style="font-size:14px;">'+value+'</div>'+
				'	<div class="blank"></div>'+
				'</div>'+
				'<div class="blank"></div>';
	$.weeboxs.open(html, {boxid:'pay_tip',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'温馨提示',width:350,type:'wee'});	
	return;	
}
//表格隔行色
$('table').each(function(){
	$("table").eq(1).find('tr:even').not("tr:eq(0)").css("background",useT.tableTrOne).height(35);
	$("table").eq(1).find('tr:odd').css("background","#eee").height(35);
	$("table").eq(2).find('tr:even').not("tr:eq(0)").css("background",useT.tableTrTwo).height(35);
	$("table").eq(2).find('tr:odd').css("background","#eee").height(35);
	
});