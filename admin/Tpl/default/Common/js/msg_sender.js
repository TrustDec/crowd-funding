
function deal_sender_fun()
{
	window.clearInterval(deal_sender);
	$.ajax({
		url: "msg_send.php?act=deal_msg_list",
		success:function(data)
		{
			if(!isNaN(data)&&parseInt(data)>=1)
			{						
				$("#deal_msg").show();			
			}
			else
			{
				$("#deal_msg").hide();
			}
			deal_sender = window.setInterval("deal_sender_fun()",send_span);
		}
	});
}

var deal_sender = window.setInterval("deal_sender_fun()",send_span);	

function wx_sender_fun()
{
	window.clearInterval(wx_sender);
	$.ajax({
		url: "msg_send.php?act=wx_msg_list",
		success:function(data)
		{
			if(!isNaN(data)&&parseInt(data)>=1)
			{						
				$("#deal_msg").show();			
			}
			else
			{
				$("#deal_msg").hide();
			}
			wx_sender = window.setInterval("wx_sender_fun()",send_span);
		}
	});
}

var wx_sender = window.setInterval("wx_sender_fun()",send_span);

//设置队列的检测定时器

function promote_sender_fun()
{
	window.clearInterval(promote_sender);
	$.ajax({
		url: "msg_send.php?act=promote_msg_list",
		success:function(data)
		{
			if(!isNaN(data)&&parseInt(data)>=1)
			{						
				$("#promote_msg").show();			
			}
			else
			{
				$("#promote_msg").hide();
			}
			promote_sender = window.setInterval("promote_sender_fun()",send_span);
		}
	});
}

var promote_sender = window.setInterval("promote_sender_fun()",send_span);