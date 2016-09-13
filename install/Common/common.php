<?php 
if (!defined('THINK_PATH')) exit();
 function xCopy($source,   $destination,   $child){    
  //用法：    
  //   xCopy("feiy","feiy2",1):拷贝feiy下的文件到   feiy2,包括子目录    
  //   xCopy("feiy","feiy2",0):拷贝feiy下的文件到   feiy2,不包括子目录    
  //参数说明：    
  //   $source:源目录名    
  //   $destination:目的目录名    
  //   $child:复制时，是不是包含的子目录    
  if(!is_dir($source)){    
  echo("Error:the   $source   is   not   a   direction!");    
  return   0;    
  }    
  if(!is_dir($destination)){    
  	mkdir($destination,0777);    
  }    
   
   
  $handle=dir($source);    
  while($entry=$handle->read())   {    
  if(($entry!=".")&&($entry!="..")){    
  if(is_dir($source."/".$entry)){    
  if($child)    
  xCopy($source."/".$entry,$destination."/".$entry,$child);    
  }    
  else{    
   
  copy($source."/".$entry,$destination."/".$entry);    
  }    
   
  }    
  }    
   
  return   1;    
  }

?>