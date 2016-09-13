function account(user_id)
{
	$.ajax({ 
			url: ROOT+'?m=User&a=account&id='+user_id, 
			data: "ajax=1",
			dataType: "json",
			success: function(msg){
				if(msg.status==0){
					alert(msg.info);
				}
			},
			error: function(){
				$.weeboxs.open(ROOT+'?m=User&a=account&id='+user_id, {contentType:'ajax',showButton:false,title:LANG['USER_ACCOUNT'],width:600,height:260});
			}
		});
	
}
function account_detail(user_id)
{
	location.href = ROOT + '?m=User&a=account_detail&id='+user_id;
}

function consignee(user_id)
{
	location.href = ROOT + '?m=User&a=consignee&id='+user_id;
}

function weibo(user_id)
{
	location.href = ROOT + '?m=User&a=weibo&id='+user_id;
}
function userBank(user_id)
{
	location.href = ROOT + '?m=UserBank&a=index&user_id='+user_id;
}
function user_bank(user_id)
{
	location.href = ROOT + '?m=User&a=userbank_index&user_id='+user_id;
}
