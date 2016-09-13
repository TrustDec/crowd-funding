
function upd_file2(obj,file_id)
{	

	$("input[name='"+file_id+"']").bind("change",function(){			
		$(obj).hide();
		$(obj).parent().find(".fileuploading").show();
		  $.ajaxFileUpload
		   (
			   {
				    url:APP_ROOT+'/upload_two.php',
				    secureuri:false,
				    fileElementId:file_id,
				    dataType: 'json',
				    success: function (data, status)
				    {
				   		$(obj).show();
						$(obj).parent().find(".fileuploading").hide();
				   		if(data.status==1)
				   		{
				   			$("#image2").attr("src",data.thumb_url+"?r="+Math.random());				   			
				   			$("input[name='idcard_fang_u']").val(data.url);
				   		}
				   		else
				   		{
				   			$.showErr(data.msg);
				   		}
				   		
				    },
				    error: function (data, status, e)
				    {
						$.showErr(data.responseText);;
				    	$(obj).show();
				    	$(obj).parent().find(".fileuploading").hide();
				    }
			   }
		   );
		  $("input[name='"+file_id+"']").unbind("change");
	});	
}