$(function(){
	 $(".credit_date").bind("click",function(){
	 	var obj=$(this);
	 	account_create_date(obj.url,obj.day);
	 });
});

 