
function upd_file1(obj,file_id)
{	

	$("input[name='"+file_id+"']").bind("change",function(){			
		$(obj).hide();
		$(obj).parent().find(".fileuploading").show();
		  $.ajaxFileUpload
		   (
			   {
				    url:APP_ROOT+'/upload_one.php?key='+file_id,
				    secureuri:false,
				    fileElementId:file_id,
				    dataType: 'json',
				    success: function (data, status)
				    {
				   		$(obj).show();
				   		$(obj).parent().find(".fileuploading").hide();
				   		if(data.status==1)
				   		{
				   			$("#image1").attr("src",data.thumb_url+"?r="+Math.random());				   			
				   			$("input[name='"+file_id+"_u"+"']").val(data.url);
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