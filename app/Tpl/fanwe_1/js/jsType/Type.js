var temp=0;
  function $en(tit){return encodeURIComponent(tit)}
  var title="test！";
  var h=$(window).height();
  var w=$(window).width();
  $(".yutop").css("top",(h+150)+"px");
  $("#hjimg").css("top",(h+430)+"px");
  
  var yueAnimate={
    btntop :function(){

      $("#btntop").css({"margin-top": "0px"});
      $("#btntop").animate({"margin-top": "30px"
      },1000,'easeOutBounce');
      
    },
    imgTitle:function(){

      $(".yu1,.yu2").fadeIn(500,function(){
          $(".yu1").animate({"left":"250px"},"slow");
           $(".yu2").animate({"left":"350px"},"easeOutBounce",function(){

             $(".imger").slideDown();
           });
      });
  
    },
     back_a1_title:function(){
      $(".back_a1_title").fadeIn(1500);
     },
      back_a4_title:function(){
      $(".back_a4_title").fadeIn(3000);
     },
     backBottom:function(){
      // $(".Bottom").fadeOut(100,function(){
            $(".back_a4_header").fadeIn(2000);
            $(".back_a4_centent").fadeIn(2010);
            $(".back_a4_Bottom").fadeIn(2050);
            $(".Blogroll").fadeIn(2060);
            $(".back_Bottom").fadeIn(2065)  ;
        // });
     },
     none:function(){
         $(".back_a4_header,.back_a4_centent,.back_a4_Bottom,.Blogroll,.back_Bottom").css("display","none");
     }
  } 
  //鼠标滚动事件 
  var shubiao=true;
  var wheel = function(event) {  
    var delta = 0;  
    if (!event)
      event = window.event;  
    if (event.wheelDelta) {
      
     delta = event.wheelDelta / 120;
      var i=$(window).scrollTop();
    } else if (event.detail) {
      delta = -event.detail / 3;
    }
    if (delta) handle(delta);
    if (event.preventDefault) event.preventDefault();  
    event.returnValue = false;  
  }
  if (window.addEventListener) window.addEventListener('DOMMouseScroll', wheel, false);
  document.onmousewheel = wheel;
  var $f=true;
  var handle = function(delta) {
    if(!shubiao) return;
    shubiao=false;
    // var random_num = Math.floor((Math.random() * 100) + 50);
    // 火狐
	
    if (delta < 0) {      
       $(".header").fadeOut(200);
        $(".Header2").slideDown(300);
     
      PicWheelScroll(1);
      $f=false;
       //console.log("鼠标滑轮向下滚动：" + delta + "次！"); // 1
	   temp=delta;  
      return false;  
    } else {
      $f=true;

       $(".header").fadeIn(100);
        $(".Header2").slideUp(200);
      PicWheelScroll(0);
      //console.log("鼠标滑轮向上滚动：" + delta + "次！"); // -1  
	  temp=delta;
      return false;  
    }
  }
  $(".ac").each(function(i){
    $(this).click(function(){
      $(".ac").removeClass("active");
      $(".ac").eq(i).addClass("active");
      var num=i+1;
      if(num=="4") $("#btntop").hide();
      else $("#btntop").show();
      gotoAnchor($(".a"+num));
      getAnchroFun(num);
    })
  })

  var PicWheelScroll = function(n){   
    var num=$("#pic1").attr("num");   
    if((num===4&&n===1) || (num===1&&n===0)) return;
    if(n==1){
      if(num<4) num++;
    }else{
      if(num>1) num--;
    }
    $(".ac").removeClass("active");
    $(".ac").eq(num-1).addClass("active");
    if(num=="4") $("#btntop").hide();
    else $("#btntop").show();
    gotoAnchor($(".a"+num));
    getAnchroFun(num);
  }

  setInterval(yueAnimate.btntop,2000);
  var getAnchroFun=function(num){
    var h=$(window).height();
    var h=(h-500<30?30:h-580)+"px";
    $(".divtop").css("bottom","35px");
    var n=$("#pic1").attr("num");
    switch(parseInt(num)){
      case 1:
         yueAnimate.back_a1_title();
        break;
      case 2:
         yueAnimate.imgTitle();       
        break;
      case 3:       
        break;
      case 4:
        yueAnimate.backBottom();
        break;
    }

    if(num<4){
      yueAnimate.none();
    }else{
       yueAnimate.backBottom();
    }
     if(num>1){
      $(".back_a1_title").css("display","none");
    }
    if (num!=2) {
      $(".yu1,.yu2").css({"display":"none","left":"-1050px"});
      $(".imger").fadeOut("slow");
    }
    if(num==1){
      $(".header").fadeIn(200);
        $(".Header2").slideUp(300);
    }
    if(num===4){

       $(".header").css("display","none");
      $(".Header2").slideDown(300);
  }

    $("#pic1").attr("num",num);
  }
 
  var gotoAnchor = function(selector,isauto){
    var anchor = $(selector);
    if (anchor.length < 0) return;
    var $win=$(window);
    var $body = $(window.document.documentElement);
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("webkit") > -1) {
      $body = $(window.document.body)
    }
    var pos=anchor.offset();
    if (isauto) {
      var t = pos.top - $win.scrollTop(); //相对于屏幕显示区
      var t2 = $win.height() - t;

      if (t2 < anchor.outerHeight()) {
        $body.animate({"scrollTop": pos.top}, 3000);
      }
      return;
    }
    $body.animate({"scrollTop": pos.top},{queue :false,complete: function(){shubiao=true;}});
  }

  gotoAnchor($(".a1"));
   yueAnimate.back_a1_title();
  $(window).resize(function(){
    var h=$(window).height();
    $(".leave").css("height",h+"px");
    var n=$("#pic1").attr("num");
    var h1=(h-500<30?30:h-580)+"px";
    $(".divtop").css("bottom","35px");
   gotoAnchor($(".a"+n));
  });
  $(window).ready(function(){
     var h=$(window).height();
    $(".leave").css("height",h+"px");
    var n=$("#pic1").attr("num");
    var h1=(h-500<30?30:h-580)+"px";
    $(".divtop").css("bottom","35px");
    gotoAnchor($(".a"+n));
  });
  $(".divtop").click(function(){
    var n=$("#pic1").attr("num");
    if(n=="3") $("#btntop").hide();
    n=parseInt(n)+1;
    if(n==5) {return;}
    $(".ac").removeClass("active");
    $(".ac").eq(n-1).addClass("active");
    gotoAnchor($(".a"+n));
    getAnchroFun(n);
    $("#pic1").attr("num",n);
  });
  /*修复Chrome下引导页问题*/
      if(temp ==-1&&num<5){
       
        $(".header").fadeOut(200);
        $(".Header2").slideDown(300);
        // $(".Bottom").fadeOut(200);
      }else{
        $(".header").fadeIn(100);
        $(".Header2").slideUp(200);
        // $(".Bottom").fadeIn(200);
      }

