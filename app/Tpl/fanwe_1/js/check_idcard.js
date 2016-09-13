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
