// JavaScript Document
$.extend({

    createUploadIframe: function(id, uri)
 {
   //create frame
            var frameId = 'jUploadFrame' + id;
            
            if(window.ActiveXObject) {
                var io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
                if(typeof uri== 'boolean'){
                    io.src = 'javascript:false';
                }
                else if(typeof uri== 'string'){
                    io.src = uri;
                }
            }
            else {
                var io = document.createElement('iframe');
                io.id = frameId;
                io.name = frameId;
            }
            io.style.position = 'absolute';
            io.style.top = '-1000px';
            io.style.left = '-1000px';

            document.body.appendChild(io);

            return io;   
    },
    createUploadForm: function(id, fileElementId)
 {
  //create form 
  var formId = 'jUploadForm' + id;
  var fileId = 'jUploadFile' + id;
  var form = $('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>'); 
  var oldElement = $('#' + fileElementId);
  var newElement = $(oldElement).clone();
  $(oldElement).attr('id', fileId);
  $(oldElement).before(newElement);
  $(oldElement).appendTo(form);
  //set attributes
  $(form).css('position', 'absolute');
  $(form).css('top', '-1200px');
  $(form).css('left', '-1200px');
  $(form).appendTo('body');  
  return form;
    },

    ajaxFileUpload: function(s) {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout  
        s = $.extend({}, $.ajaxSettings, s);
        var id = s.fileElementId;        
  var form = $.createUploadForm(id, s.fileElementId);
  var io = $.createUploadIframe(id, s.secureuri);
  var frameId = 'jUploadFrame' + id;
  var formId = 'jUploadForm' + id;  
        
        if( s.global && ! $.active++ )
  {
   // Watch for a new set of requests
   $.event.trigger( "ajaxStart" );
  }            
        var requestDone = false;
        // Create the request object
        var xml = {};   
        if( s.global )
        {
         $.event.trigger("ajaxSend", [xml, s]);
        }            
        
        var uploadCallback = function(isTimeout)
  {  
   // Wait for a response to come back 
   var io = document.getElementById(frameId);
            try 
   {    
    if(io.contentWindow)
    {
      xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                  xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
      
    }else if(io.contentDocument)
    {
      xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                 xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
    }      
            }catch(e)
   {
    $.handleError(s, xml, null, e);
   }
            if( xml || isTimeout == "timeout") 
   {    
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if( status != "error" )
     {
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = $.uploadHttpData( xml, s.dataType );                        
                        if( s.success )
                        {
       // ifa local callback was specified, fire it and pass it the data
                         s.success( data, status );
                        };                 
                        if( s.global )
                        {
       // Fire the global callback
                         $.event.trigger( "ajaxSuccess", [xml, s] );
                        };                            
                    } else
                    {
                     $.handleError(s, xml, status);
                    }
                        
                } catch(e) 
    {
                    status = "error";
                    $.handleError(s, xml, status, e);
                };                
                if( s.global )
                {
     // The request was completed
                 $.event.trigger( "ajaxComplete", [xml, s] );
                };
                    

                // Handle the global AJAX counter
                if(s.global && ! --$.active)
                {
                 $.event.trigger("ajaxStop");
                };
                if(s.complete)
                {
                  s.complete(xml, status);
                } ;                 

                $(io).unbind();

                setTimeout(function()
         { try 
          {
           $(io).remove();
           $(form).remove(); 
           
          } catch(e) 
          {
           $.handleError(s, xml, null, e);
          }         

         }, 100);

                xml = null;

            };
        }
        // Timeout checker
        if( s.timeout > 0 ) 
  {
            setTimeout(function(){
                
                if( !requestDone )
                {
     // Check to see ifthe request is still happening
                 uploadCallback( "timeout" );
                }
                
            }, s.timeout);
        }
        try 
  {
   var form = $('#' + formId);
   $(form).attr('action', s.url);
   $(form).attr('method', 'POST');
   $(form).attr('target', frameId);
            if(form.encoding)
   {
                form.encoding = 'multipart/form-data';    
            }
            else
   {    
                form.enctype = 'multipart/form-data';
            }   
            $(form).submit();

        } catch(e) 
  {   
            $.handleError(s, xml, null, e);
        }
        if(window.attachEvent){
            document.getElementById(frameId).attachEvent('onload', uploadCallback);
        }
        else{
            document.getElementById(frameId).addEventListener('load', uploadCallback, false);
        }   
        return {abort: function () {}}; 

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        // ifthe type is "script", eval it in global context
        if( type == "script" )
        {
         $.globalEval( data );
        }
            
        // Get the JavaScript object, ifJSON is used.
        if( type == "json" )
        {
         eval( "data = " + data );
        }
            
        // evaluate scripts within html
        if( type == "html" )
        {
         $("<div>").html(data).evalScripts();
        }
            
        return data;
    }
});
