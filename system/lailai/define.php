<?php 
define("IS_DEBUG",1);
define("SHOW_DEBUG",0);
define("SHOW_LOG",0);
define("LAI_LAI",0); 
define("MAX_DYNAMIC_CACHE_SIZE",1000);  //动态缓存最数量
define("SMS_TIMESPAN",60);  //短信验证码发送的时间间隔
define("SMS_EXPIRESPAN",300);  //短信验证码失效时间
define("NOW_TIME",get_gmtime());   //当前UTC时间戳
define("CLIENT_IP",get_client_ip());  //当前客户端IP
define("SITE_DOMAIN",get_domain());   //站点域名
define("PIN_PAGE_SIZE",80);
define("PIN_SECTOR",10);
define("MAX_SP_IMAGE",20); //商家的最大图片量
define("MAX_LOGIN_TIME",0);  //登录的过期时间,单位：秒
define("ORDER_DELIVERY_EXPIRE",7);  //延期收货天
define("TIME_ZONE",app_conf('DEFAULT_TIMEZONE'));  //时区
define("AES_DECRYPT_KEY",'fanwe');
define("SESSION_TIME",3600*1); //session超时时间
?>