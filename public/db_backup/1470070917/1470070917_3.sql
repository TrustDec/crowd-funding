-- fanwe SQL Dump Program
-- Apache
-- 
-- DATE : 2016-08-02 09:01:58
-- MYSQL SERVER VERSION : 5.5.48-log
-- PHP VERSION : apache2handler
-- Vol : 3


DROP TABLE IF EXISTS `%DB_PREFIX%log`;
CREATE TABLE `%DB_PREFIX%log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_info` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `log_admin` int(11) NOT NULL,
  `log_ip` varchar(255) NOT NULL,
  `log_status` tinyint(1) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3129 DEFAULT CHARSET=utf8 COMMENT='//记录';
INSERT INTO `%DB_PREFIX%log` VALUES ('3003','更新系统配置','1464894512','1','127.0.0.1','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3004','更新系统配置','1464894916','1','127.0.0.1','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3005','admin登录成功','1464895653','1','127.0.0.1','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3006','admin管理员密码错误','1464904955','0','127.0.0.1','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3007','admin管理员密码错误','1464904970','0','127.0.0.1','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3008','admin登录成功','1464904979','1','127.0.0.1','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3009','更新系统配置','1464905375','1','127.0.0.1','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3010','更新系统配置','1464906154','1','127.0.0.1','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3011','admin登录成功','1464913228','1','127.0.0.1','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3012','admin登录成功','1465239166','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3013','更新系统配置','1465239179','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3014','admin管理员密码错误','1465239654','0','36.40.164.74','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3015','admin登录成功','1465239663','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3016','admin登录成功','1465239828','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3017','更新系统配置','1465239871','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3018','更新系统配置','1465240373','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3019','更新系统配置','1465240400','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3020','admin登录成功','1465241876','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3021','方维众筹更新成功','1465241916','1','36.40.164.74','1','IndexImage','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3022','创业者曙光更新成功','1465242015','1','36.40.164.74','1','MAdv','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3023','掌上众筹添加成功','1465242342','1','36.40.164.74','1','MAdv','insert');
INSERT INTO `%DB_PREFIX%log` VALUES ('3024','25_status禁用成功','1465245341','1','36.40.164.74','1','MAdv','toogle_status');
INSERT INTO `%DB_PREFIX%log` VALUES ('3025','25_status启用成功','1465245345','1','36.40.164.74','1','MAdv','toogle_status');
INSERT INTO `%DB_PREFIX%log` VALUES ('3026','25_status禁用成功','1465245347','1','36.40.164.74','1','MAdv','toogle_status');
INSERT INTO `%DB_PREFIX%log` VALUES ('3027','admin登录成功','1465245746','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3028','豪华酒店更新成功','1465252111','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3029','豪华酒店更新成功','1465252721','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3030','豪华酒店更新成功','1465252737','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3031','管理员操作','1465252943','1','36.40.164.74','1','User','modify_account');
INSERT INTO `%DB_PREFIX%log` VALUES ('3032','豪华酒店更新成功','1465253158','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3033','肥仔开瓶器更新成功','1465253167','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3034','XXX酒店更新成功','1465253177','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3035','XXX房地产更新成功','1465253190','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3036','XXX酒店更新成功','1465253201','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3037','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！更新成功','1465253214','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3038','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！更新成功','1465253231','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3039','XX酒店-五星级为您打造更新成功','1465253244','1','36.40.164.74','1','DealSelflessOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3040','保护梁子湖，我们在行动。更新成功','1465253253','1','36.40.164.74','1','DealSelflessOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3041','扶贫帮困更新成功','1465253265','1','36.40.164.74','1','DealSelflessOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3042','筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！更新成功','1465253274','1','36.40.164.74','1','DealSelflessOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3043','项目ID74:¥0.00添加成功','1465253416','1','36.40.164.74','1','DealSelflessOnline','insert_deal_item');
INSERT INTO `%DB_PREFIX%log` VALUES ('3044','联系方式更新成功','1465253683','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3045','联系方式更新成功','1465253973','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3046','常见问题更新成功','1465254083','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3047','隐私策略更新成功','1465254541','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3048','隐私策略更新成功','1465254593','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3049','撰写指南更新成功','1465255213','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3050','撰写指南更新成功','1465255312','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3051','撰写指南更新成功','1465255517','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3052','撰写指南更新成功','1465255545','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3053','撰写指南更新成功','1465255558','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3054','隐私策略更新成功','1465255845','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3055','官方微博更新成功','1465256340','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3056','官方微博更新成功','1465256357','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3057','官方微博更新成功','1465256369','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3058','官方微博彻底删除成功','1465256401','1','36.40.164.74','1','Help','foreverdelete');
INSERT INTO `%DB_PREFIX%log` VALUES ('3059','关于我们更新成功','1465256597','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3060','关于我们更新成功','1465256604','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3061','关于我们更新成功','1465256654','0','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3062','admin登录成功','1465256689','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3063','关于我们更新成功','1465256956','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3064','关于我们更新成功','1465256969','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3065','关于我们更新成功','1465256984','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3066','服务介绍更新成功','1465257259','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3067','联系我们更新成功','1465257378','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3068','服务条款更新成功','1465258059','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3069','服务条款更新成功','1465258182','1','36.40.164.74','1','Help','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3070','发起项目更新成功','1465258258','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3071','会员注册更新成功','1465258275','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3072','版权申明更新成功','1465258295','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3073','项目规范更新成功','1465258307','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3074','【媒体报道】众筹平台助“印象”打造专业川菜连锁品牌更新成功','1465258314','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3075','使用条款更新成功','1465258323','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3076','【媒体报道】众筹平台助“印象”打造专业川菜连锁品牌更新成功','1465258341','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3077','项目规范更新成功','1465258349','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3078','版权申明更新成功','1465258358','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3079','会员注册更新成功','1465258369','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3080','发起项目更新成功','1465258381','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3081','使用条款更新成功','1465258395','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3082','【活动报名】10.21第一期天使合投SHOW热辣登场！删除成功','1465258469','1','36.40.164.74','1','Article','delete');
INSERT INTO `%DB_PREFIX%log` VALUES ('3083','常见问题更新成功','1465258489','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3084','联系方式更新成功','1465258499','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3085','关于我们更新成功','1465258532','1','36.40.164.74','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3086','豪华酒店更新成功','1465258744','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3087','XXX酒店更新成功','1465258790','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3088','西安临海酒店更新成功','1465258815','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3089','XXX房地产更新成功','1465258966','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3090','XXX酒店更新成功','1465259010','1','36.40.164.74','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3091','XX酒店-五星级为您打造更新成功','1465259072','1','36.40.164.74','1','DealSelflessOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3092','admin登录成功','1465260650','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3093','admin登录成功','1465260947','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3094','26_status禁用成功','1465261016','1','36.40.164.74','1','MAdv','toogle_status');
INSERT INTO `%DB_PREFIX%log` VALUES ('3095','世界云联更新成功','1465265048','1','36.40.164.74','1','User','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3096','更新系统配置','1465265061','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3097','更新系统配置','1465265186','1','36.40.164.74','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3098','admin更新成功','1465265241','1','36.40.164.74','1','Admin','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3099','test更新成功','1465265251','1','36.40.164.74','1','Admin','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3100','admin登录成功','1465265305','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3101','admin登录成功','1465265389','1','36.40.164.74','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3102','admin登录成功','1465318833','1','36.40.167.126','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3103','更新系统配置','1465319016','1','36.40.167.126','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3104','【媒体报道】众筹平台助“印象”打造专业川菜连锁品牌更新成功','1465319172','1','36.40.167.126','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3105','使用条款更新成功','1465326238','1','36.40.167.126','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3106','使用条款更新成功','1465326373','1','36.40.167.126','1','Article','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3107','admin管理员密码错误','1465335500','0','36.40.167.126','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3108','admin登录成功','1465335513','1','36.40.167.126','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3109','admin管理员密码错误','1465339366','0','36.40.167.126','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3110','admin登录成功','1465339375','1','36.40.167.126','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3111','admin登录成功','1465350741','1','36.40.167.126','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3112','钟日红爱禁用成功','1465350768','1','36.40.167.126','1','User','set_effect');
INSERT INTO `%DB_PREFIX%log` VALUES ('3113','更新系统配置','1465350801','1','36.40.167.126','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3114','更新系统配置','1465350812','1','36.40.167.126','1','Conf','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3115','admin管理员密码错误','1469464411','0','36.47.161.246','0','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3116','admin登录成功','1469464418','1','36.47.161.246','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3117','admin登录成功','1469582017','1','113.140.248.57','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3118','admin登录成功','1470017090','1','113.140.25.138','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3119','admin登录成功','1470069539','1','113.140.25.138','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3120','admin登录成功','1470069547','1','113.140.25.138','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3121','admin登录成功','1470069563','1','113.140.25.138','1','Public','do_login');
INSERT INTO `%DB_PREFIX%log` VALUES ('3122','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！更新成功','1470069624','1','113.140.25.138','1','DealOnline','update');
INSERT INTO `%DB_PREFIX%log` VALUES ('3123','支付宝银行直连支付卸载成功','1470070604','1','113.140.25.138','1','Payment','uninstall');
INSERT INTO `%DB_PREFIX%log` VALUES ('3124','管理员操作','1470070754','1','113.140.25.138','1','User','modify_account');
INSERT INTO `%DB_PREFIX%log` VALUES ('3125','管理员操作','1470070783','1','113.140.25.138','1','User','modify_account');
INSERT INTO `%DB_PREFIX%log` VALUES ('3126','管理员操作','1470070832','1','113.140.25.138','1','User','modify_account');
INSERT INTO `%DB_PREFIX%log` VALUES ('3127','管理员操作','1470070856','1','113.140.25.138','1','User','modify_account');
INSERT INTO `%DB_PREFIX%log` VALUES ('3128','管理员操作','1470070876','1','113.140.25.138','1','User','modify_account');
DROP TABLE IF EXISTS `%DB_PREFIX%m_adv`;
CREATE TABLE `%DB_PREFIX%m_adv` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `img` varchar(255) DEFAULT '',
  `page` varchar(20) DEFAULT '',
  `type` tinyint(1) DEFAULT '0' COMMENT '1.标签集,2.url地址,3.分类排行,4.最亮达人,5.搜索发现,6.一起拍,7.热门单品排行,8.直接显示某个分享',
  `data` text,
  `sort` smallint(5) DEFAULT '10',
  `status` tinyint(1) DEFAULT '1',
  `open_url_type` int(11) DEFAULT '0' COMMENT '0:使用内置浏览器打开url;1:使用外置浏览器打开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('25','酒店众筹','./public/attachment/201604/14/11/570f0f94e8a06.png','top','1','','2','0','0');
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('24','创业者曙光','./public/attachment/201602/29/01/56d328a87de29.jpg','top','1','','1','1','0');
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('26','掌上众筹','./public/attachment/201606/07/11/5756436179979.jpg','start','1','','1','0','0');
DROP TABLE IF EXISTS `%DB_PREFIX%m_config`;
CREATE TABLE `%DB_PREFIX%m_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `val` text,
  `type` tinyint(1) NOT NULL,
  `sort` int(11) DEFAULT '0',
  `value_scope` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%m_config` VALUES ('10','kf_phone','客服电话','400-1080-521','0','1','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('11','kf_email','客服邮箱','462414875@qq.com','0','2','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('29','ios_upgrade','ios版本升级内容','','3','9','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('16','page_size','分页大小','10','0','10','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('17','about_info','关于我们(填文章ID)','','0','3','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('18','program_title','程序标题名称','酒店众筹','0','0','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('22','android_version','android版本号(yyyymmddnn)','','0','4','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('23','android_filename','android下载包名(放程序根目录下)','','0','5','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('24','ios_version','ios版本号(yyyymmddnn)','','0','7','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('25','ios_down_url','ios下载地址(appstore连接地址)','','0','8','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('28','android_upgrade','android版本升级内容','修复bug','3','6','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('30','article_cate_id','文章分类ID','','0','11','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('31','android_forced_upgrade','android是否强制升级(0:否;1:是)','1','0','0','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('32','ios_forced_upgrade','ios是否强制升级(0:否;1:是)','1','0','0','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('35','logo','系统LOGO','','2','1','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('33','index_adv_num','首页广告数','5','0','33','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('34','index_pro_num','首页推荐商品数','0','0','34','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('36','wx_appid','微信APPID','','0','36','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('37','wx_secrit','微信SECRIT','','0','37','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('38','sina_app_key','新浪APP_KEY','','0','38','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('39','sina_app_secret','新浪APP_SECRET','','0','39','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('40','sina_bind_url','新浪回调地址','','0','40','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('41','qq_app_key','QQ登录APP_KEY','','0','41','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('42','qq_app_secret','QQ登录APP_SECRET','','0','42','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('43','wx_app_key','微信(分享)appkey','','0','43','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('44','wx_app_secret','微信(分享)appSecret','','0','44','');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('45','wx_controll','一站式登录方式','0','4','45','0,1');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('46','ios_check_version','ios审核版本号(审核中填写)','','0','9','');
DROP TABLE IF EXISTS `%DB_PREFIX%mail_list`;
CREATE TABLE `%DB_PREFIX%mail_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_address` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mail_address_idx` (`mail_address`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='//邮件列表';
DROP TABLE IF EXISTS `%DB_PREFIX%mail_server`;
CREATE TABLE `%DB_PREFIX%mail_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_server` varchar(255) NOT NULL,
  `smtp_name` varchar(255) NOT NULL,
  `smtp_pwd` varchar(255) NOT NULL,
  `is_ssl` tinyint(1) NOT NULL,
  `smtp_port` varchar(255) NOT NULL,
  `use_limit` int(11) NOT NULL,
  `is_reset` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `total_use` int(11) NOT NULL,
  `is_verify` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='// 邮件服务器';
INSERT INTO `%DB_PREFIX%mail_server` VALUES ('8','smtp.163.com','vitakung@163.com','vitakung','0','25','0','0','1','46','1');
DROP TABLE IF EXISTS `%DB_PREFIX%message`;
CREATE TABLE `%DB_PREFIX%message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '该留言所属人ID',
  `user_name` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='// 用户留言';
DROP TABLE IF EXISTS `%DB_PREFIX%message_cate`;
CREATE TABLE `%DB_PREFIX%message_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `is_project` tinyint(1) NOT NULL DEFAULT '0' COMMENT '项目发起的分类 0表示否 1表示 是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='// 用户留言分类';
INSERT INTO `%DB_PREFIX%message_cate` VALUES ('1','项目登记','0');
INSERT INTO `%DB_PREFIX%message_cate` VALUES ('2','建议留言','1');
DROP TABLE IF EXISTS `%DB_PREFIX%mobile_verify_code`;
CREATE TABLE `%DB_PREFIX%mobile_verify_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(10) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `create_time` int(11) NOT NULL,
  `client_ip` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT '邮件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='//手机验证';
INSERT INTO `%DB_PREFIX%mobile_verify_code` VALUES ('25','848283','18980616107','1469611445','118.114.230.113','');
DROP TABLE IF EXISTS `%DB_PREFIX%money_freeze`;
CREATE TABLE `%DB_PREFIX%money_freeze` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT '请求流水号',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '平台会员编号',
  `platformNo` varchar(20) NOT NULL COMMENT '商户编号',
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `expired` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '到期自劢解冻时间',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `description` varchar(255) DEFAULT NULL COMMENT '描述信息',
  `deal_id` int(10) NOT NULL COMMENT '项目id',
  `pay_type` tinyint(1) DEFAULT '0' COMMENT '0 表示第三方托管 1表示第三方支付',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1表示冻结诚意金，2表示解冻诚意金,3表示申请解冻',
  `create_time` int(11) DEFAULT NULL COMMENT '冻结时间',
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%money_type`;
CREATE TABLE `%DB_PREFIX%money_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'type类型 0 ~ ？',
  `class` varchar(100) NOT NULL DEFAULT '' COMMENT '所属分类 money  lock_money site_money  point  score',
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%money_type` VALUES ('1','普通的','0','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('2','加入诚意金','1','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('3','违约扣除诚意金','2','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('4','分红','3','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('5','订金','4','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('6','首付','5','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('7','众筹买房','6','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('8','买房卖出回报','7','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('9','理财赎回本金','8','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('10','理财赎回收益','9','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('11','理财赎回手续费','10','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('12','理财本金','11','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('13','理财购买手续费','12','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('14','理财冻结资金','13','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('15','理财服务费','14','money','0','1');
INSERT INTO `%DB_PREFIX%money_type` VALUES ('16','理财发放资金','15','money','0','1');
DROP TABLE IF EXISTS `%DB_PREFIX%mortgate`;
CREATE TABLE `%DB_PREFIX%mortgate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `notice_id` int(11) NOT NULL COMMENT '0 表示为余额支付 大于0表示在线支付',
  `money` int(11) NOT NULL COMMENT '诚意金 金额',
  `status` tinyint(1) NOT NULL COMMENT '状态 1表示诚意金支付 2表示扣除诚意金 3表示诚意金解冻到余额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
DROP TABLE IF EXISTS `%DB_PREFIX%msg_template`;
CREATE TABLE `%DB_PREFIX%msg_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '名字',
  `content` text NOT NULL COMMENT '内容',
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `is_html` tinyint(1) NOT NULL COMMENT '是否成功：1表示成功，0表示失败',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='// 邮箱验证';
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('1','TPL_MAIL_USER_PASSWORD','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}你好，请点击以下链接修改您的密码：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.password_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.password_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n \r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('3','TPL_MAIL_USER_VERIFY','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 帐号 发送验证码：</p>\r\n				<p style=\"margin:0px;padding:0px;\">验证码：{$user.send_code}</p>\r\n 				 \r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}帐号团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('18','TPL_SMS_DEAL_FAIL','{$fail_user_info.user_name}您好，很遗憾的通知您，您所支持的 \"{$fail_user_info.deal_name}\"项目筹资失败!【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('20','TPL_SMS_USER_VERIFY','恭喜你{$success_user_info.user_name}，注册验证成功!【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('21','TPL_SMS_USER_S','{$user_s_msg.user_name}您好，恭喜你，您发起的{$user_s_msg.deal_name}项目筹资成功!【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('22','TPL_SMS_USER_F','{$user_f_msg.user_name}您好，很遗憾的通知您，您发起的{$user_f_msg.deal_name}项目筹资失败!【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('23','TPL_SMS_VERIFY_CODE','你的手机号为{$verify.mobile},验证码为{$verify.code}【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('17','TPL_SMS_DEAL_SUCCESS','{$success_user_info.user_name}您好，恭喜你，您所支持的 \"{$success_user_info.deal_name}\" 项目筹资成功,近期将会发放回报!【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('4','TPL_MAIL_CHANGE_USER_VERIFY','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 进行邮件修改 <a href=\"mailto:{$user.email}\" target=\"_blank\">{$user.email}<wbr>.com</a> ，点击以下链接，即可进行下一步操作：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">1、为了保障您帐号的安全性，请在 48小时内完成激活，此链接将在您激活过一次后失效！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">2、注册的帐号可以畅行{$user.site_name}产品</p>\r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}帐号团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('5','TPL_MAIL_INVESTOR_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 进行{$user.is_investor_name}申请，{if $user.investor_status eq 1}很高兴您审核通过,点击以下链接，即可进行下一步操作{else}很遗憾您的申请未通过,理由是：{$user.investor_send_info};点击以下链接，即可重新申请{/if}：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n 				<div style=\"line-height:80px;height:80px;\"></div>\r\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('25','TPL_MAIL_INVESTOR_PAY_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}您好，您的投资申请已通过，请在{$user.pay_end_time}前进行支付{$user.money}元;点击以下链接</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.note_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.note_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('26','TPL_SMS_INVESTOR_PAY_STATUS','{$user.user_name}您好，您的投资申请已通过，请在{$user.pay_end_time}前进行支付{$user.money}元【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('6','TPL_SMS_INVESTOR_STATUS','{$user.user_name}您好，{if $user.investor_status eq 1}恭喜您{else}很遗憾,{$user.investor_send_info}{/if},您申请的{$user.is_investor_name}{$user.investor_status_name}【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('27','TPL_SMS_INVESTOR_PAID_STATUS','恭喜您，已经支付{$user.paid_money}元,支付单号为{$user.notice_sn}【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('28','TPL_MAIL_INVESTOR_PAID_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}您好，恭喜您，已经支付{$user.paid_money}元,支付单号为{$user.notice_sn}</p>\r\n				\r\n  				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('29','TPL_SMS_ZC_STATUS','{$user.control_content}【VK维客+猫力中国】','0','0');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('30','TPL_MAIL_ZC_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.control_content}</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>','1','1');
INSERT INTO `%DB_PREFIX%msg_template` VALUES ('31','TPL_SMS_TZT_VERIFY_CODE','你的手机号为{$verify.mobile},易宝投资通验证码为{$verify.code}【VK维客+猫力中国】','0','0');
DROP TABLE IF EXISTS `%DB_PREFIX%nav`;
CREATE TABLE `%DB_PREFIX%nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `blank` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `u_module` varchar(255) NOT NULL,
  `u_action` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL,
  `u_param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='//导航菜单列表';
INSERT INTO `%DB_PREFIX%nav` VALUES ('42','首页','index.php?ctl=indexs&act=indexs','0','1','1','','','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('47','公益模块','','0','3','1','deals','selfless','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('46','产品众筹','','0','2','1','deals','index','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('48','最新动态','','0','7','1','news','index','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('49','路演资讯','','0','20','0','article_cate','','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('50','股权交易','','0','3','0','deals','stock','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('51','积分商城','','0','5','0','score_mall','','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('53','新手帮助','','0','8','1','faq','','0','');
INSERT INTO `%DB_PREFIX%nav` VALUES ('54','天使投资人','','0','6','0','investor','invester_list','0','');
DROP TABLE IF EXISTS `%DB_PREFIX%payment`;
CREATE TABLE `%DB_PREFIX%payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `online_pay` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `total_amount` decimal(20,2) NOT NULL COMMENT '总金额',
  `config` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='// 付款';
INSERT INTO `%DB_PREFIX%payment` VALUES ('25','Wxjspay','1','1','微信支付(PC扫码支付)','','0.00','a:4:{s:5:\"appid\";s:0:\"\";s:9:\"appsecret\";s:0:\"\";s:5:\"mchid\";s:0:\"\";s:3:\"key\";s:0:\"\";}','','2');
INSERT INTO `%DB_PREFIX%payment` VALUES ('26','Alipay','1','1','支付宝即时到帐支付','','0.00','a:3:{s:14:\"alipay_partner\";s:0:\"\";s:14:\"alipay_account\";s:0:\"\";s:10:\"alipay_key\";s:0:\"\";}','','3');
DROP TABLE IF EXISTS `%DB_PREFIX%payment_notice`;
CREATE TABLE `%DB_PREFIX%payment_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_sn` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pay_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT 'order_id为0时为充值',
  `is_paid` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `bank_id` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `money` decimal(20,2) NOT NULL COMMENT '金额',
  `outer_notice_sn` varchar(255) NOT NULL,
  `deal_id` int(11) NOT NULL COMMENT '0为充值',
  `deal_item_id` int(11) NOT NULL COMMENT '0为充值',
  `deal_name` varchar(255) NOT NULL COMMENT '空为充值',
  `is_has_send_success` tinyint(1) NOT NULL COMMENT '（0表示发送不成功，1表示发送成功）',
  `is_mortgate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是诚意金 0表示否 1表示 是 2表示诚意金已经冻结（从余额中取出）',
  `paid_send` tinyint(1) NOT NULL COMMENT '支付成功后是否发送',
  PRIMARY KEY (`id`),
  UNIQUE KEY `notice_sn_unk` (`notice_sn`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `payment_id` (`payment_id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 DEFAULT CHARSET=utf8 COMMENT='// 付款单号列表';
DROP TABLE IF EXISTS `%DB_PREFIX%promote_msg`;
CREATE TABLE `%DB_PREFIX%promote_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '0:短信 1:邮件 2:站内推送',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `send_time` int(11) NOT NULL,
  `send_status` tinyint(1) NOT NULL,
  `send_type` tinyint(1) NOT NULL,
  `send_type_id` int(11) NOT NULL,
  `send_define_data` text NOT NULL,
  `is_html` tinyint(1) NOT NULL,
  `url_route` varchar(255) NOT NULL,
  `url_param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `send_type` (`send_type`),
  KEY `send_type_id` (`send_type_id`),
  KEY `send_status` (`send_status`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='// 推广信息';
INSERT INTO `%DB_PREFIX%promote_msg` VALUES ('13','0','','222222','1460409482','2','0','0','','0','','');
DROP TABLE IF EXISTS `%DB_PREFIX%promote_msg_list`;
CREATE TABLE `%DB_PREFIX%promote_msg_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dest` varchar(255) NOT NULL,
  `send_type` tinyint(1) NOT NULL,
  `content` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `send_time` int(11) NOT NULL,
  `is_send` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `result` text NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `is_html` tinyint(1) NOT NULL,
  `msg_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dest_idx` (`dest`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='// 推广信息队列表';
INSERT INTO `%DB_PREFIX%promote_msg_list` VALUES ('30','15510192688','0','222222','','1460409489','1','1460409489','0','网站未开启短信功能','0','0','13');
INSERT INTO `%DB_PREFIX%promote_msg_list` VALUES ('31','15596885995','0','222222','','1460409491','1','1460409491','0','网站未开启短信功能','0','0','13');
DROP TABLE IF EXISTS `%DB_PREFIX%recommend`;
CREATE TABLE `%DB_PREFIX%recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memo` text NOT NULL COMMENT '推荐理由',
  `deal_id` int(11) NOT NULL COMMENT '项目编号',
  `user_id` int(11) NOT NULL COMMENT '推送给哪个会员',
  `recommend_user_id` int(11) NOT NULL COMMENT '推送人ID',
  `create_time` int(11) NOT NULL COMMENT '推荐时间',
  `deal_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '项目类型 0表示普通项目 1表示股权项目',
  `deal_name` varchar(255) NOT NULL COMMENT '项目名称',
  `deal_image` varchar(255) NOT NULL COMMENT '推荐项目图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
INSERT INTO `%DB_PREFIX%recommend` VALUES ('1','我的项目很好哦。','77','27','24','1461280597','3','哈哈哈哈','./public/attachment/201604/22/11/5719a14ba0195.jpg');
INSERT INTO `%DB_PREFIX%recommend` VALUES ('2','减肥的考试jfk时代开始的','77','27','24','1461280642','3','哈哈哈哈','./public/attachment/201604/22/11/5719a14ba0195.jpg');
DROP TABLE IF EXISTS `%DB_PREFIX%referrals`;
CREATE TABLE `%DB_PREFIX%referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '邀请人ID（即需要返利的会员ID）',
  `user_name` varchar(50) NOT NULL COMMENT '邀请人名称（即需要返利的会员名称）',
  `rel_user_id` int(11) NOT NULL COMMENT '被邀请人ID',
  `rel_user_name` varchar(50) NOT NULL COMMENT '被邀请人名称',
  `money` double(20,4) NOT NULL COMMENT '返利的现金',
  `create_time` int(11) NOT NULL COMMENT '返利生成的时间',
  `pay_time` int(11) NOT NULL COMMENT '返利发放的时间',
  `score` int(11) NOT NULL COMMENT '返利的积分',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `type` tinyint(1) NOT NULL COMMENT '类型：1表示注册奖励，2购买奖励',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='邀请返利记录表';
DROP TABLE IF EXISTS `%DB_PREFIX%region_conf`;
CREATE TABLE `%DB_PREFIX%region_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '地区名称',
  `region_level` tinyint(4) NOT NULL COMMENT '1:国 2:省 3:市(县) 4:区(镇)',
  `py` varchar(50) NOT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为热门地区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3401 DEFAULT CHARSET=utf8 COMMENT='//地区配置';
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3','1','安徽','2','anhui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('4','1','福建','2','fujian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('5','1','甘肃','2','gansu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('6','1','广东','2','guangdong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('7','1','广西','2','guangxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('8','1','贵州','2','guizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('9','1','海南','2','hainan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('10','1','河北','2','hebei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('11','1','河南','2','henan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('12','1','黑龙江','2','heilongjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('13','1','湖北','2','hubei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('14','1','湖南','2','hunan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('15','1','吉林','2','jilin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('16','1','江苏','2','jiangsu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('17','1','江西','2','jiangxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('18','1','辽宁','2','liaoning','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('19','1','内蒙古','2','neimenggu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('20','1','宁夏','2','ningxia','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('21','1','青海','2','qinghai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('22','1','山东','2','shandong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('23','1','山西','2','shanxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('24','1','陕西','2','shanxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('26','1','四川','2','sichuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('28','1','西藏','2','xicang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('29','1','新疆','2','xinjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('30','1','云南','2','yunnan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('31','1','浙江','2','zhejiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('36','3','安庆','3','anqing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('37','3','蚌埠','3','bangbu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('38','3','巢湖','3','chaohu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('39','3','池州','3','chizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('40','3','滁州','3','chuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('41','3','阜阳','3','fuyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('42','3','淮北','3','huaibei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('43','3','淮南','3','huainan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('44','3','黄山','3','huangshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('45','3','六安','3','liuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('46','3','马鞍山','3','maanshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('47','3','宿州','3','suzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('48','3','铜陵','3','tongling','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('49','3','芜湖','3','wuhu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('50','3','宣城','3','xuancheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('51','3','亳州','3','zhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('52','2','北京','2','beijing','1');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('53','4','福州','3','fuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('54','4','龙岩','3','longyan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('55','4','南平','3','nanping','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('56','4','宁德','3','ningde','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('57','4','莆田','3','putian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('58','4','泉州','3','quanzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('59','4','三明','3','sanming','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('60','4','厦门','3','xiamen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('61','4','漳州','3','zhangzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('62','5','兰州','3','lanzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('63','5','白银','3','baiyin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('64','5','定西','3','dingxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('65','5','甘南','3','gannan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('66','5','嘉峪关','3','jiayuguan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('67','5','金昌','3','jinchang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('68','5','酒泉','3','jiuquan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('69','5','临夏','3','linxia','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('70','5','陇南','3','longnan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('71','5','平凉','3','pingliang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('72','5','庆阳','3','qingyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('73','5','天水','3','tianshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('74','5','武威','3','wuwei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('75','5','张掖','3','zhangye','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('76','6','广州','3','guangzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('77','6','深圳','3','shen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('78','6','潮州','3','chaozhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('79','6','东莞','3','dong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('80','6','佛山','3','foshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('81','6','河源','3','heyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('82','6','惠州','3','huizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('83','6','江门','3','jiangmen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('84','6','揭阳','3','jieyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('85','6','茂名','3','maoming','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('86','6','梅州','3','meizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('87','6','清远','3','qingyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('88','6','汕头','3','shantou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('89','6','汕尾','3','shanwei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('90','6','韶关','3','shaoguan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('91','6','阳江','3','yangjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('92','6','云浮','3','yunfu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('93','6','湛江','3','zhanjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('94','6','肇庆','3','zhaoqing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('95','6','中山','3','zhongshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('96','6','珠海','3','zhuhai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('97','7','南宁','3','nanning','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('98','7','桂林','3','guilin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('99','7','百色','3','baise','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('100','7','北海','3','beihai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('101','7','崇左','3','chongzuo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('102','7','防城港','3','fangchenggang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('103','7','贵港','3','guigang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('104','7','河池','3','hechi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('105','7','贺州','3','hezhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('106','7','来宾','3','laibin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('107','7','柳州','3','liuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('108','7','钦州','3','qinzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('109','7','梧州','3','wuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('110','7','玉林','3','yulin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('111','8','贵阳','3','guiyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('112','8','安顺','3','anshun','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('113','8','毕节','3','bijie','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('114','8','六盘水','3','liupanshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('115','8','黔东南','3','qiandongnan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('116','8','黔南','3','qiannan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('117','8','黔西南','3','qianxinan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('118','8','铜仁','3','tongren','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('119','8','遵义','3','zunyi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('120','9','海口','3','haikou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('121','9','三亚','3','sanya','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('122','9','白沙','3','baisha','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('123','9','保亭','3','baoting','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('124','9','昌江','3','changjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('125','9','澄迈县','3','chengmaixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('126','9','定安县','3','dinganxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('127','9','东方','3','dongfang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('128','9','乐东','3','ledong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('129','9','临高县','3','lingaoxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('130','9','陵水','3','lingshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('131','9','琼海','3','qionghai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('132','9','琼中','3','qiongzhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('133','9','屯昌县','3','tunchangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('134','9','万宁','3','wanning','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('135','9','文昌','3','wenchang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('136','9','五指山','3','wuzhishan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('137','9','儋州','3','zhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('138','10','石家庄','3','shijiazhuang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('139','10','保定','3','baoding','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('140','10','沧州','3','cangzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('141','10','承德','3','chengde','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('142','10','邯郸','3','handan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('143','10','衡水','3','hengshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('144','10','廊坊','3','langfang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('145','10','秦皇岛','3','qinhuangdao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('146','10','唐山','3','tangshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('147','10','邢台','3','xingtai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('148','10','张家口','3','zhangjiakou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('149','11','郑州','3','zhengzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('150','11','洛阳','3','luoyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('151','11','开封','3','kaifeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('152','11','安阳','3','anyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('153','11','鹤壁','3','hebi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('154','11','济源','3','jiyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('155','11','焦作','3','jiaozuo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('156','11','南阳','3','nanyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('157','11','平顶山','3','pingdingshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('158','11','三门峡','3','sanmenxia','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('159','11','商丘','3','shangqiu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('160','11','新乡','3','xinxiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('161','11','信阳','3','xinyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('162','11','许昌','3','xuchang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('163','11','周口','3','zhoukou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('164','11','驻马店','3','zhumadian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('165','11','漯河','3','he','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('166','11','濮阳','3','yang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('167','12','哈尔滨','3','haerbin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('168','12','大庆','3','daqing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('169','12','大兴安岭','3','daxinganling','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('170','12','鹤岗','3','hegang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('171','12','黑河','3','heihe','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('172','12','鸡西','3','jixi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('173','12','佳木斯','3','jiamusi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('174','12','牡丹江','3','mudanjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('175','12','七台河','3','qitaihe','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('176','12','齐齐哈尔','3','qiqihaer','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('177','12','双鸭山','3','shuangyashan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('178','12','绥化','3','suihua','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('179','12','伊春','3','yichun','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('180','13','武汉','3','wuhan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('181','13','仙桃','3','xiantao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('182','13','鄂州','3','ezhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('183','13','黄冈','3','huanggang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('184','13','黄石','3','huangshi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('185','13','荆门','3','jingmen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('186','13','荆州','3','jingzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('187','13','潜江','3','qianjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('188','13','神农架林区','3','shennongjialinqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('189','13','十堰','3','shiyan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('190','13','随州','3','suizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('191','13','天门','3','tianmen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('192','13','咸宁','3','xianning','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('193','13','襄樊','3','xiangfan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('194','13','孝感','3','xiaogan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('195','13','宜昌','3','yichang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('196','13','恩施','3','enshi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('197','14','长沙','3','changsha','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('198','14','张家界','3','zhangjiajie','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('199','14','常德','3','changde','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('200','14','郴州','3','chenzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('201','14','衡阳','3','hengyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('202','14','怀化','3','huaihua','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('203','14','娄底','3','loudi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('204','14','邵阳','3','shaoyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('205','14','湘潭','3','xiangtan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('206','14','湘西','3','xiangxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('207','14','益阳','3','yiyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('208','14','永州','3','yongzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('209','14','岳阳','3','yueyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('210','14','株洲','3','zhuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('211','15','长春','3','changchun','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('212','15','吉林','3','jilin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('213','15','白城','3','baicheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('214','15','白山','3','baishan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('215','15','辽源','3','liaoyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('216','15','四平','3','siping','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('217','15','松原','3','songyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('218','15','通化','3','tonghua','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('219','15','延边','3','yanbian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('220','16','南京','3','nanjing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('221','16','苏州','3','suzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('222','16','无锡','3','wuxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('223','16','常州','3','changzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('224','16','淮安','3','huaian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('225','16','连云港','3','lianyungang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('226','16','南通','3','nantong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('227','16','宿迁','3','suqian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('228','16','泰州','3','taizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('229','16','徐州','3','xuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('230','16','盐城','3','yancheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('231','16','扬州','3','yangzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('232','16','镇江','3','zhenjiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('233','17','南昌','3','nanchang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('234','17','抚州','3','fuzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('235','17','赣州','3','ganzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('236','17','吉安','3','jian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('237','17','景德镇','3','jingdezhen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('238','17','九江','3','jiujiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('239','17','萍乡','3','pingxiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('240','17','上饶','3','shangrao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('241','17','新余','3','xinyu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('242','17','宜春','3','yichun','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('243','17','鹰潭','3','yingtan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('244','18','沈阳','3','shenyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('245','18','大连','3','dalian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('246','18','鞍山','3','anshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('247','18','本溪','3','benxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('248','18','朝阳','3','chaoyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('249','18','丹东','3','dandong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('250','18','抚顺','3','fushun','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('251','18','阜新','3','fuxin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('252','18','葫芦岛','3','huludao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('253','18','锦州','3','jinzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('254','18','辽阳','3','liaoyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('255','18','盘锦','3','panjin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('256','18','铁岭','3','tieling','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('257','18','营口','3','yingkou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('258','19','呼和浩特','3','huhehaote','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('259','19','阿拉善盟','3','alashanmeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('260','19','巴彦淖尔盟','3','bayannaoermeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('261','19','包头','3','baotou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('262','19','赤峰','3','chifeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('263','19','鄂尔多斯','3','eerduosi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('264','19','呼伦贝尔','3','hulunbeier','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('265','19','通辽','3','tongliao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('266','19','乌海','3','wuhai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('267','19','乌兰察布市','3','wulanchabushi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('268','19','锡林郭勒盟','3','xilinguolemeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('269','19','兴安盟','3','xinganmeng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('270','20','银川','3','yinchuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('271','20','固原','3','guyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('272','20','石嘴山','3','shizuishan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('273','20','吴忠','3','wuzhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('274','20','中卫','3','zhongwei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('275','21','西宁','3','xining','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('276','21','果洛','3','guoluo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('277','21','海北','3','haibei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('278','21','海东','3','haidong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('279','21','海南','3','hainan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('280','21','海西','3','haixi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('281','21','黄南','3','huangnan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('282','21','玉树','3','yushu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('283','22','济南','3','jinan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('284','22','青岛','3','qingdao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('285','22','滨州','3','binzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('286','22','德州','3','dezhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('287','22','东营','3','dongying','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('288','22','菏泽','3','heze','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('289','22','济宁','3','jining','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('290','22','莱芜','3','laiwu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('291','22','聊城','3','liaocheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('292','22','临沂','3','linyi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('293','22','日照','3','rizhao','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('294','22','泰安','3','taian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('295','22','威海','3','weihai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('296','22','潍坊','3','weifang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('297','22','烟台','3','yantai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('298','22','枣庄','3','zaozhuang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('299','22','淄博','3','zibo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('300','23','太原','3','taiyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('301','23','长治','3','changzhi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('302','23','大同','3','datong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('303','23','晋城','3','jincheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('304','23','晋中','3','jinzhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('305','23','临汾','3','linfen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('306','23','吕梁','3','lvliang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('307','23','朔州','3','shuozhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('308','23','忻州','3','xinzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('309','23','阳泉','3','yangquan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('310','23','运城','3','yuncheng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('311','24','西安','3','xian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('312','24','安康','3','ankang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('313','24','宝鸡','3','baoji','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('314','24','汉中','3','hanzhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('315','24','商洛','3','shangluo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('316','24','铜川','3','tongchuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('317','24','渭南','3','weinan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('318','24','咸阳','3','xianyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('319','24','延安','3','yanan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('320','24','榆林','3','yulin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('321','25','上海','2','shanghai','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('322','26','成都','3','chengdu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('323','26','绵阳','3','mianyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('324','26','阿坝','3','aba','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('325','26','巴中','3','bazhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('326','26','达州','3','dazhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('327','26','德阳','3','deyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('328','26','甘孜','3','ganzi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('329','26','广安','3','guangan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('330','26','广元','3','guangyuan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('331','26','乐山','3','leshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('332','26','凉山','3','liangshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('333','26','眉山','3','meishan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('334','26','南充','3','nanchong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('335','26','内江','3','neijiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('336','26','攀枝花','3','panzhihua','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('337','26','遂宁','3','suining','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('338','26','雅安','3','yaan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('339','26','宜宾','3','yibin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('340','26','资阳','3','ziyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('341','26','自贡','3','zigong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('342','26','泸州','3','zhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('343','27','天津','2','tianjin','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('344','28','拉萨','3','lasa','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('345','28','阿里','3','ali','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('346','28','昌都','3','changdu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('347','28','林芝','3','linzhi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('348','28','那曲','3','naqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('349','28','日喀则','3','rikaze','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('350','28','山南','3','shannan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('351','29','乌鲁木齐','3','wulumuqi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('352','29','阿克苏','3','akesu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('353','29','阿拉尔','3','alaer','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('354','29','巴音郭楞','3','bayinguoleng','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('355','29','博尔塔拉','3','boertala','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('356','29','昌吉','3','changji','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('357','29','哈密','3','hami','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('358','29','和田','3','hetian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('359','29','喀什','3','kashi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('360','29','克拉玛依','3','kelamayi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('361','29','克孜勒苏','3','kezilesu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('362','29','石河子','3','shihezi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('363','29','图木舒克','3','tumushuke','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('364','29','吐鲁番','3','tulufan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('365','29','五家渠','3','wujiaqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('366','29','伊犁','3','yili','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('367','30','昆明','3','kunming','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('368','30','怒江','3','nujiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('369','30','普洱','3','puer','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('370','30','丽江','3','lijiang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('371','30','保山','3','baoshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('372','30','楚雄','3','chuxiong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('373','30','大理','3','dali','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('374','30','德宏','3','dehong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('375','30','迪庆','3','diqing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('376','30','红河','3','honghe','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('377','30','临沧','3','lincang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('378','30','曲靖','3','qujing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('379','30','文山','3','wenshan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('380','30','西双版纳','3','xishuangbanna','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('381','30','玉溪','3','yuxi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('382','30','昭通','3','zhaotong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('383','31','杭州','3','hangzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('384','31','湖州','3','huzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('385','31','嘉兴','3','jiaxing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('386','31','金华','3','jinhua','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('387','31','丽水','3','lishui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('388','31','宁波','3','ningbo','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('389','31','绍兴','3','shaoxing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('390','31','台州','3','taizhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('391','31','温州','3','wenzhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('392','31','舟山','3','zhoushan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('393','31','衢州','3','zhou','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('394','32','重庆','2','zhongqing','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('395','33','香港','2','xianggang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('396','34','澳门','2','aomen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('397','35','台湾','2','taiwan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('500','52','东城区','3','dongchengqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('501','52','西城区','3','xichengqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('502','52','海淀区','3','haidianqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('503','52','朝阳区','3','chaoyangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('504','52','崇文区','3','chongwenqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('505','52','宣武区','3','xuanwuqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('506','52','丰台区','3','fengtaiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('507','52','石景山区','3','shijingshanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('508','52','房山区','3','fangshanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('509','52','门头沟区','3','mentougouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('510','52','通州区','3','tongzhouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('511','52','顺义区','3','shunyiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('512','52','昌平区','3','changpingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('513','52','怀柔区','3','huairouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('514','52','平谷区','3','pingguqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('515','52','大兴区','3','daxingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('516','52','密云县','3','miyunxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('517','52','延庆县','3','yanqingxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2703','321','长宁区','3','changningqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2704','321','闸北区','3','zhabeiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2705','321','闵行区','3','xingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2706','321','徐汇区','3','xuhuiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2707','321','浦东新区','3','pudongxinqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2708','321','杨浦区','3','yangpuqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2709','321','普陀区','3','putuoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2710','321','静安区','3','jinganqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2711','321','卢湾区','3','luwanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2712','321','虹口区','3','hongkouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2713','321','黄浦区','3','huangpuqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2714','321','南汇区','3','nanhuiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2715','321','松江区','3','songjiangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2716','321','嘉定区','3','jiadingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2717','321','宝山区','3','baoshanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2718','321','青浦区','3','qingpuqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2719','321','金山区','3','jinshanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2720','321','奉贤区','3','fengxianqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2721','321','崇明县','3','chongmingxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2912','343','和平区','3','hepingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2913','343','河西区','3','hexiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2914','343','南开区','3','nankaiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2915','343','河北区','3','hebeiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2916','343','河东区','3','hedongqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2917','343','红桥区','3','hongqiaoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2918','343','东丽区','3','dongliqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2919','343','津南区','3','jinnanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2920','343','西青区','3','xiqingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2921','343','北辰区','3','beichenqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2922','343','塘沽区','3','tangguqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2923','343','汉沽区','3','hanguqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2924','343','大港区','3','dagangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2925','343','武清区','3','wuqingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2926','343','宝坻区','3','baoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2927','343','经济开发区','3','jingjikaifaqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2928','343','宁河县','3','ninghexian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2929','343','静海县','3','jinghaixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('2930','343','蓟县','3','jixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3325','394','合川区','3','hechuanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3326','394','江津区','3','jiangjinqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3327','394','南川区','3','nanchuanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3328','394','永川区','3','yongchuanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3329','394','南岸区','3','nananqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3330','394','渝北区','3','yubeiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3331','394','万盛区','3','wanshengqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3332','394','大渡口区','3','dadukouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3333','394','万州区','3','wanzhouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3334','394','北碚区','3','beiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3335','394','沙坪坝区','3','shapingbaqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3336','394','巴南区','3','bananqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3337','394','涪陵区','3','fulingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3338','394','江北区','3','jiangbeiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3339','394','九龙坡区','3','jiulongpoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3340','394','渝中区','3','yuzhongqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3341','394','黔江开发区','3','qianjiangkaifaqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3342','394','长寿区','3','changshouqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3343','394','双桥区','3','shuangqiaoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3344','394','綦江县','3','jiangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3345','394','潼南县','3','nanxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3346','394','铜梁县','3','tongliangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3347','394','大足县','3','dazuxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3348','394','荣昌县','3','rongchangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3349','394','璧山县','3','shanxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3350','394','垫江县','3','dianjiangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3351','394','武隆县','3','wulongxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3352','394','丰都县','3','fengduxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3353','394','城口县','3','chengkouxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3354','394','梁平县','3','liangpingxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3355','394','开县','3','kaixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3356','394','巫溪县','3','wuxixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3357','394','巫山县','3','wushanxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3358','394','奉节县','3','fengjiexian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3359','394','云阳县','3','yunyangxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3360','394','忠县','3','zhongxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3361','394','石柱','3','shizhu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3362','394','彭水','3','pengshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3363','394','酉阳','3','youyang','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3364','394','秀山','3','xiushan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3365','395','沙田区','3','shatianqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3366','395','东区','3','dongqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3367','395','观塘区','3','guantangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3368','395','黄大仙区','3','huangdaxianqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3369','395','九龙城区','3','jiulongchengqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3370','395','屯门区','3','tunmenqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3371','395','葵青区','3','kuiqingqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3372','395','元朗区','3','yuanlangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3373','395','深水埗区','3','shenshui','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3374','395','西贡区','3','xigongqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3375','395','大埔区','3','dapuqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3376','395','湾仔区','3','wanziqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3377','395','油尖旺区','3','youjianwangqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3378','395','北区','3','beiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3379','395','南区','3','nanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3380','395','荃湾区','3','wanqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3381','395','中西区','3','zhongxiqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3382','395','离岛区','3','lidaoqu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3383','396','澳门','3','aomen','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3384','397','台北','3','taibei','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3385','397','高雄','3','gaoxiong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3386','397','基隆','3','jilong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3387','397','台中','3','taizhong','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3388','397','台南','3','tainan','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3389','397','新竹','3','xinzhu','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3390','397','嘉义','3','jiayi','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3391','397','宜兰县','3','yilanxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3392','397','桃园县','3','taoyuanxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3393','397','苗栗县','3','miaolixian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3394','397','彰化县','3','zhanghuaxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3395','397','南投县','3','nantouxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3396','397','云林县','3','yunlinxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3397','397','屏东县','3','pingdongxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3398','397','台东县','3','taidongxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3399','397','花莲县','3','hualianxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('3400','397','澎湖县','3','penghuxian','0');
INSERT INTO `%DB_PREFIX%region_conf` VALUES ('1','0','全国','1','quanguo','0');
DROP TABLE IF EXISTS `%DB_PREFIX%role`;
CREATE TABLE `%DB_PREFIX%role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='//后台的权限节点';
INSERT INTO `%DB_PREFIX%role` VALUES ('4','测试管理员','1','0');
DROP TABLE IF EXISTS `%DB_PREFIX%role_access`;
CREATE TABLE `%DB_PREFIX%role_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='//访问权限';
INSERT INTO `%DB_PREFIX%role_access` VALUES ('100','4','7064','131','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('99','4','681','131','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('98','4','198','15','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('97','4','19','15','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('101','4','7065','131','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('102','4','7066','131','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('103','4','0','142','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('104','4','0','145','0');
INSERT INTO `%DB_PREFIX%role_access` VALUES ('105','4','0','144','0');
DROP TABLE IF EXISTS `%DB_PREFIX%role_group`;
CREATE TABLE `%DB_PREFIX%role_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nav_id` int(11) NOT NULL COMMENT '后台导航分组ID',
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='//组权限';
INSERT INTO `%DB_PREFIX%role_group` VALUES ('1','首页','1','0','1','1');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('5','系统设置','3','0','1','1');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('7','管理员','3','0','1','2');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('8','数据库操作','3','0','1','6');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('9','系统日志','3','0','1','7');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('19','菜单设置','11','0','1','17');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('28','邮件管理','10','0','1','26');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('29','短信管理','10','0','1','27');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('31','广告设置','11','0','1','29');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('33','队列管理','10','0','1','31');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('69','会员管理','5','0','1','31');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('70','会员整合','5','0','1','32');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('71','同步登录','5','0','1','33');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('72','项目管理','13','0','1','33');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('73','项目支持','13','0','1','34');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('74','项目点评','13','0','1','35');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('75','支付接口','14','0','1','1');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('76','付款记录','14','0','1','2');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('77','消息模板','10','0','1','1');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('78','提现记录','14','0','1','3');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('79','友情链接','11','0','1','36');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('80','文章管理','11','0','1','37');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('81','文章分类管理','11','0','1','38');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('82','地区管理','13','0','1','39');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('83','系统监测','3','0','1','83');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('62','手机端设置','3','0','1','1');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('84','问卷调查设置','11','0','1','84');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('85','会员邀请','5','0','1','31');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('86','回报项目统计','15','0','1','86');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('87','股权项目统计','15','0','1','87');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('88','平台统计','15','0','1','88');
INSERT INTO `%DB_PREFIX%role_group` VALUES ('89','留言列表','5','0','1','89');
DROP TABLE IF EXISTS `%DB_PREFIX%role_module`;
CREATE TABLE `%DB_PREFIX%role_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8 COMMENT='//模块权限';
INSERT INTO `%DB_PREFIX%role_module` VALUES ('123','DealOrder','项目支持','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('124','DealComment','项目点评','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('125','PaymentNotice','付款记录','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('126','UserRefund','用户提现','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('127','PromoteMsg','推广模块','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('128','PromoteMsgList','推广队列','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('130','LinkGroup','友情链接分组','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('129','Link','友情链接','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('131','UserLevel','会员等级','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('132','DealLevel','项目等级','0','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('133','Article','文章','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('134','ArticleCate','文章分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('135','RegionConf','地区','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('136','SqlCheck','系统监测','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('93','MAdv','手机端广告','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('137','UserInvestor','投资人申请管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('139','Vote','问卷调查','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('138','Bank','提现银行设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('141','Collocation','资金托管','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('140','UserCarry','提现手续费','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('142','Referrals','会员邀请','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('143','Statistics','统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('144','Message','留言列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('145','MessageCate','留言分类列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('151','UserFreeze','冻结资金列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('153','MoneyFreezes','诚意金管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('152','UserUnfreeze','申请解冻资金列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('155','YeepayWithdraw','第三方托管提现','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('154','YeepayRecharge','第三方托管充值','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('156','MoneyFreeze','第三方托管诚意金','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('160','DealHouseCate','房产众筹分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('161','StockTransfer','债权转让管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('162','Finance','融资公司管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('164','StationMessage','站内消息管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('165','Contract','合同范本设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('166','WeixinConf','微信第三方平台','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('167','WeixinInfo','微信配置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('168','WeixinReply','微信回复设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('169','WeixinTemplate','微信模板设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('170','WeixinUser','微信会员管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('171','Licai','理财管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('172','LicaiHistory','收益率管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('173','LicaiRecommend','个性推荐设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('174','LicaiOrder','购买记录','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('175','LicaiDealshow','首页订单展示设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('176','LicaiBank','银行列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('177','LicaiFundType','基金种类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('178','LicaiFundBrand','基金品牌','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('179','LicaiRedempte','赎回管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('180','LicaiNear','理财发放管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('181','LicaiSend','理财已发放管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('182','LicaiAdvance','垫付单管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('183','Goods','积分商城商品管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('184','GoodsCate','积分商城分类管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('185','GoodsOrder','积分商城兑换管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('186','LicaiHoliday','理财假日管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('122','Faq','常见问题','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('121','Help','站点帮助','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('120','IndexImage','轮播广告图','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('119','Payment','支付接口','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('118','Deal','项目管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('117','DealCate','项目分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('116','ApiLogin','同步登录','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('115','Integrate','会员整合','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('114','MsgTemplate','消息模板管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('113','User','会员管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('92','Cache','缓存处理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('56','DealMsgList','业务群发队列','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('53','Adv','广告模块','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('48','Sms','短信接口','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('47','MailServer','邮件服务器','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('36','Nav','导航菜单','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('58','Index','首页','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('19','File','文件管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('15','Log','系统日志','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('13','Database','数据库','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('12','Conf','系统配置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('6','Admin','管理员','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('5','Role','权限组别','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('245','UserConfirmRefund','提现确认记录','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('188','LicaiInterest','收益率设置','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('189','ReferralsTotal','统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('190','DealOnline','上线产品项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('191','DealSubmit','未审核产品项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('192','DealDelete','产品项目回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('193','DealSelflessCate','公益众筹分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('194','DealSelflessOnline','上线公益项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('195','DealSelflessSubmit','未审核公益项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('196','DealSelflessDelete','公益项目回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('197','DealInvestorCate','股权众筹分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('198','DealInvestorOnline','上线股权项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('199','DealInvestorSubmit','未审核股权项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('200','DealInvestorDelete','股权项目回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('201','DealFinanceCate','融资众筹分类','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('202','DealFinanceOnline','上线融资项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('203','DealFinanceSubmit','未审核融资项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('204','DealFinanceDelete','融资项目回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('205','DealHouseOnline','上线房产项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('206','DealHouseSubmit','未审核房产项目','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('207','DealHouseDelete','房产项目回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('208','DealSubmitUserBonus','未审核分红列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('209','DealSubmitFixedInterest','未审核固定回报列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('210','DealSubmitBuyHouseEarnings','未审核买房收益列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('211','StockTransferList','已审核转让列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('212','FinanceSubmit','未审核公司','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('213','ArticleCateTrash','文章分类回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('214','ArticleTrash','文章回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('215','PromoteMsgMail','邮件列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('216','PromoteMsgSms','短信列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('217','StationMessageMsgList','站内消息队列列表','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('218','StatisticsProject','项目统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('219','StatisticsUsernumTotal','人数统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('220','StatisticsMoneyTotal','金额统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('221','StatisticsHasbackTotal','回报统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('222','StatisticsOverdueTotal','逾期统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('223','StatisticsInvesteTotal','项目统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('224','StatisticsInvestorsTotal','投资人统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('225','StatisticsFinancingAmountTotal','融资金额统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('226','StatisticsBreachConventionTotal','违约统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('227','StatisticsMoneyInchangeTotal','充值统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('228','StatisticsMoneyCarryBankTotal','提现统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('229','StatisticsUserTotal','用户统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('230','StatisticsSiteCostsTotal','网站费用统计','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('231','RoleTrash','管理员分组回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('232','AdminTrash','管理员回收站','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('233','DatabaseSql','SQL操作','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('234','WeixinInfoNavSetting','自定义菜单','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('235','WeixinReplyOnfocus','关注时回复','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('236','WeixinReplyTxt','文本回复','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('237','WeixinReplyNews','图文回复','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('238','WeixinReplyLbs','LBS回复','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('239','WeixinTemplateSetIndustry','设置行业','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('240','WeixinTemplateMsglist','模板消息队列','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('241','WeixinUserGroups','分组管理','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('242','WeixinUserMessageSend','普通消息群发','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('243','WeixinUserAdvanced','高级群发','1','0');
INSERT INTO `%DB_PREFIX%role_module` VALUES ('244','LicaiRedempteBefore','预热期赎回管理','1','0');
DROP TABLE IF EXISTS `%DB_PREFIX%role_nav`;
CREATE TABLE `%DB_PREFIX%role_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='// 导航权限';
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('1','首页','0','1','1');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('3','系统设置','0','1','10');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('5','会员管理','0','1','3');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('10','短信邮件','0','1','7');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('13','项目管理','0','1','4');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('14','支付管理','0','1','5');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('11','前端设置','0','1','6');
INSERT INTO `%DB_PREFIX%role_nav` VALUES ('15','统计模块','0','1','8');
DROP TABLE IF EXISTS `%DB_PREFIX%role_node`;
CREATE TABLE `%DB_PREFIX%role_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `group_id` int(11) NOT NULL COMMENT '后台分组菜单分组ID',
  `module_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7467 DEFAULT CHARSET=utf8 COMMENT='// 权限节点';
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7276','breach_convention_info','查看','1','0','0','226','6881');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7275','export_csv_financing_amount_info','导出融资金额统计明细','1','0','0','225','6880');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7272','investors_info','查看','1','0','0','224','6879');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7273','export_csv_investors_info','导出投资人统计明细','1','0','0','224','6879');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7274','financing_amount_info','查看','1','0','0','225','6880');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7271','export_csv_investe_info','导出项目统计明细','1','0','0','223','6875');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7270','export_csv_overdue_info','导出逾期明细','1','0','0','222','6878');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7269','overdue_info','查看','1','0','0','222','6878');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7268','export_csv_hasback_info','导出回报明细','1','0','0','221','6877');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7267','hasback_info','查看','1','0','0','221','6877');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7264','export_csv_usernum_info','导出支持明细','1','0','0','219','6874');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7266','export_csv_money_info','导出金额明细','1','0','0','220','6876');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7265','money_info','查看','1','0','0','220','6876');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7263','usernum_info','查看','1','0','0','219','6874');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7262','export_csv_project_info','导出项目明细','1','0','0','218','6872');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7261','edit_sms','编辑','1','0','0','216','668');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7260','add_sms','新增','1','0','0','216','668');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7466','show_content','查看','1','0','0','217','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7257','do_vote_ask','编辑执行','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7255','update_deal_item','编辑执行','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7254','edit_deal_item','编辑子项目','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7253','del_deal_item','删除子项目','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7252','add_deal_item','添加子项目','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7457','toogle_status','设置首页类型','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7248','add_deal_item','添加子项目','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7249','del_deal_item','删除子项目','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7250','edit_deal_item','编辑子项目','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7449','order_index','购买记录列表','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7245','content','查看','1','0','0','144','6887');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7244','investor_go_allow','审核执行','1','0','0','137','6867');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7243','account','账户管理','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7242','restore','恢复','1','0','0','118','7240');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7241','foreverdelete','彻底删除','1','0','0','118','7240');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7240','delete_house_index','项目回收站','1','0','0','118','7240');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7239','foreverdelete','彻底删除','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7238','deal_item','子项目','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7237','edit','编辑上架','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7236','do_delivery','发货','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7235','cancel_order_do','取消兑换','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7233','delete','删除记录','1','0','0','245','7231');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7234','export_csv','导出','1','0','0','245','7231');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7232','refund_confirm','确认提现','1','0','0','245','7231');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7231','confirm_list','提现确认列表','1','0','78','245','7231');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7230','update_deal_item','更新子项目','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7228','edit_deal_item','编辑子项目','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7229','del_deal_item','删除子项目','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7226','deal_item','子项目列表','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7227','add_deal_item','新增子项目','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7225','del_pay_log','删除发放','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7224','save_pay_log','发放','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7223','pay_log','筹款发放','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7221','del_deal_log','删除日志','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7222','sharefee_list','子项目列表','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7220','deal_log','项目日志','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7219','set_sort','排序','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7217','delete','删除','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7215','add','新增房产众筹 ','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7216','edit','编辑','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7213','foreverdelete','彻底删除 ','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7214','restore','恢复 ','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7212','set_sort','排序 ','1','0','0','122','646');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7211','show_content','显示内容','1','0','0','128','674');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7202','edit_mail','编辑','1','0','0','215','667');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7203','add_mail','新增 ','1','0','0','215','667');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7201','set_effect','设置状态 ','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7198','foreverdelete','彻底删除 ','1','0','0','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7199','vote_ask','编辑问卷 ','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7200','set_sort','排序 ','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7196','get_pay_list','投资列表 ','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7197','add','新增 ','1','0','0','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7195','set_sort','排序','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7193','set_sort','排序','1','0','0','117','627');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7194','set_effect','修改状态','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7191','export_q_csv','导出','1','0','0','244','7031');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7192','edit','编辑','1','0','0','117','627');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7190','status','同意赎回','1','0','0','244','7031');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7188','save_industry','保存','1','0','0','239','6967');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7189','syn_industry_to_weixin','同步到微信公众平台','1','0','0','239','6967');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7187','save_onfocusn','保存关注时图文回复','1','0','0','235','6959');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7186','save_onfocus','保存关注时文本回复','1','0','0','235','6959');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7184','save_dtext','保存默认文本回复','1','0','0','168','6958');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7185','save_dnews','保存默认图文回复','1','0','0','168','6958');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7183','toogle_status','修改状态','1','0','0','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7182','set_sort','排序','1','0','0','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7181','edit','编辑','1','0','0','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7180','foreverdelete','彻底删除','1','0','0','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7177','site_costs_info','网站收益明细','1','0','0','230','6885');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7178','export_csv_site_costs_info','导出','1','0','0','230','6885');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7179','add','新增','1','0','0','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7176','export_csv_user_total','导出','1','0','0','229','6884');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7175','export_csv_money_carry_bank_total','导出','1','0','0','228','6883');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7174','export_csv_money_inchange_total','导出','1','0','0','227','6882');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7173','export_csv_breach_convention_total','导出','1','0','0','226','6881');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7172','export_csv_financing_amount_total','导出','1','0','0','225','6880');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7171','export_csv_investors_total','导出','1','0','0','224','6879');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7170','export_csv_investe_total','导出','1','0','0','223','6875');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7169','investe_info','查看项目','1','0','0','223','6875');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7168','export_csv_overdue_total','导出','1','0','0','222','6878');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7167','export_csv_hasback_total','导出','1','0','0','221','6877');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7165','export_csv_usernum_total','导出','1','0','0','219','6874');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7166','export_csv_money_total','导出','1','0','0','220','6876');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7164','export_csv_project','导出','1','0','0','218','6872');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7163','project_info','查看项目','1','0','0','218','6872');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7162','edit','编辑','1','0','0','212','6930');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7161','delete','删除','1','0','0','212','6930');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7206','stock_transfer_cancel','取消交易 ','1','0','0','211','6924');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7158','edit_user_bonus','审核','1','0','0','209','6919');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7157','del_user_bonus','删除','1','0','0','209','6919');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7156','foreverdelete','彻底删除','1','0','0','192','641');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7155','edit','产品编辑上架','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7154','deal_item','子项目','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7153','submit_house_index','审核房产众筹','1','0','0','118','7153');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7151','del_deal_item','删除子项目','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7152','update_deal_item','更新子项目','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7150','edit_deal_item','编辑子项目','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7149','add_deal_item','新增子项目','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7148','deal_item','子项目列表','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7147','del_pay_log','删除发放','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7146','save_pay_log','发放','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7145','pay_log','筹款发放','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7144','sharefee_list','子项目列表','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7141','deal_log','项目日志','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7142','del_deal_log','删除日志','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7140','set_sort','排序','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7139','add','新增','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7138','delete','删除','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7137','edit','编辑','1','0','0','118','7133');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7295','edit','编辑','1','0','0','192','641');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7134','house_index','房产众筹列表','1','0','0','118','7134');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7132','foreverdelete','彻底删除','1','0','0','136','689');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7131','delete','删除','1','0','0','138','6868');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7130','edit','编辑','1','0','0','138','6868');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7129','add','添加','1','0','0','138','6868');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7128','set_sort','排序','1','0','0','138','6868');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7127','set_sort','排序','1','0','0','121','642');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7126','set_sort','排序','1','0','0','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7125','set_effect','设置状态','1','0','0','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7124','foreverdelete','彻底删除','1','0','0','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7123','edit','编辑','1','0','0','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7122','add','添加','1','0','0','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7121','set_sort','排序','1','0','0','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7119','foreverdelete','彻底删除','1','0','0','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7120','set_effect','设置状态','1','0','0','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7117','add','添加','1','0','0','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7118','edit','编辑','1','0','0','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7116','set_sort','排序','1','0','0','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7115','set_effect','设置状态','1','0','0','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7112','delete','删除','1','0','0','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7113','foreverdelete','彻底删除','1','0','0','214','685');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7114','restore','恢复','1','0','0','214','685');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7111','edit','编辑','1','0','0','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7110','add','添加','1','0','0','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7109','set_sort','排序','1','0','0','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7108','set_effect','设置状态','1','0','0','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7107','restore','恢复','1','0','0','213','687');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7106','foreverdelete','彻底删除','1','0','0','213','687');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7105','delete','删除','1','0','0','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7103','add','添加','1','0','0','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7104','edit','编辑','1','0','0','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7209','refund_allow','是否允许 ','1','0','0','126','664');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7101','export_csv','导出','1','0','0','126','664');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7100','uninstall','卸载接口','1','0','0','141','6871');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7099','edit','更新托管接口','1','0','0','141','6871');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7098','install','安装托管接口','1','0','0','141','6871');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7096','edit','编辑','1','0','0','135','688');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7097','foreverdelete','彻底删除','1','0','0','135','688');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7095','add','新增','1','0','0','135','688');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7094','set_effect','是否显示','1','0','0','124','661');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7091','restore','回收站恢复','1','0','0','192','641');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7093','delete','删除','1','0','0','124','661');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7092','export_csv','项目导出','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7085','deal_item','子项目列表','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7090','foreverdelete','彻底删除','1','0','0','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7089','update_deal_item','更新子项目','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7088','del_deal_item','删除子项目','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7087','edit_deal_item','编辑子项目','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7086','add_deal_item','新增子项目','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7447','recommend_add','新增个性推荐','1','0','0','171','1');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7083','edit','编辑','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7077','delete','移到回收站','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7082','set_sort','设置排序','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7448','order_export_csv','导出功能','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7075','add','新增产品众筹','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7074','delete','删除','1','0','5','144','6887');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7072','set_effect','设置项目发起的分类','1','0','5','145','6886');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7071','delete','删除','1','0','5','145','6886');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7070','edit','编辑','1','0','5','145','6886');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7133','add','新增','1','0','5','145','6886');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7069','delete','删除','1','0','5','142','701');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7068','export_csv','导出','1','0','5','142','701');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7067','edit_dsffreezer','解冻资金','1','0','5','152','6893');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7066','delete','删除会员等级','1','0','0','131','681');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7065','edit','编辑会员等级','1','0','0','131','681');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7064','add','添加会员等级','1','0','0','131','681');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7063','userbank_delete','删除银行','1','0','0','113','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7061','userbank_add','新增银行','1','0','0','113','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7062','userbank_edit','编辑银行','1','0','0','113','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7060','userbank_index','会员银行列表','1','0','0','113','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7059','set_effect','会员状态修改','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7058','edit','编辑假日','1','0','0','186','7055');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7056','add','新增假日','1','0','0','186','7055');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7057','delete','删除假日','1','0','0','186','7055');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7055','index','假日列表','1','0','0','186','7055');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7053','view_order','查看订单','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7054','export_csv','导出','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7051','set_effect','修改分类状态','1','0','0','184','7047');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7052','index','兑换商品列表','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7050','edit','编辑商品分类','1','0','0','184','7047');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7049','delete','删除商品分类','1','0','0','184','7047');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7048','add','新增商品分类','1','0','0','184','7047');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7045','edit','编辑商品','1','0','0','183','7042');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7046','set_effect','修改状态','1','0','0','183','7042');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7047','index','商品分类列表','1','0','0','184','7047');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7044','delete','删除商品','1','0','0','183','7042');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7043','add','新增商品','1','0','0','183','7042');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7042','index','商品列表','1','0','0','183','7042');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7040','update','收回','1','0','0','182','7039');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7041','export_csv','导出','1','0','0','182','7039');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7039','index','垫付单列表','1','0','0','182','7039');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7038','export_csv','导出','1','0','0','181','7036');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7037','view','查看详情','1','0','0','181','7036');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7036','index','已发放理财列表','1','0','0','181','7036');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7035','export_csv','导出','1','0','0','180','7032');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7034','status','发放理财','1','0','0','180','7032');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7033','view','查看详情','1','0','0','180','7032');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7032','index','快到期理财列表','1','0','0','180','7032');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7031','before_index','预热期赎回列表','1','0','0','244','7031');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7030','export_csv','导出','1','0','0','179','7028');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7029','status','同意赎回','1','0','0','179','7028');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7028','index','赎回管理列表','1','0','0','179','7028');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7021','delete','删除基金品牌','1','0','0','178','7018');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7020','edit','编辑基金品牌','1','0','0','178','7018');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7019','add','新增基金品牌','1','0','0','178','7018');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7018','index','基金品牌列表','1','0','0','178','7018');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7017','delete','删除基金种类','1','0','0','177','7014');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7014','index','基金种类列表','1','0','0','177','7014');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7015','add','新增基金种类','1','0','0','177','7014');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7016','edit','编辑基金种类','1','0','0','177','7014');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7013','delete','删除银行','1','0','0','176','7010');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7012','edit','编辑银行','1','0','0','176','7010');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7011','add','新增银行','1','0','0','176','7010');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7027','delete','删除新增首页订单','1','0','0','175','7025');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7010','index','银行列表','1','0','0','176','7010');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7026','edit','编辑新增首页订单','1','0','0','175','7025');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7025','index','首页订单列表','1','0','0','175','7025');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7009','add','新增首页订单展示','1','0','0','175','7025');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7007','edit','编辑','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7008','view','查看详情','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7006','export_q_csv','导出功能','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7005','order_list','订单列表','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7004','index','购买记录列表','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7024','delete','删除个性推荐','1','0','0','173','7003');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7023','edit','编辑个性推荐','1','0','0','173','7003');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7022','add','新增个性推荐','1','0','0','173','7003');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7003','index','个性推荐列表','1','0','0','173','7003');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7002','edit','编辑收益率','1','0','0','172','6999');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7001','delete','删除收益率','1','0','0','172','6999');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7000','q_add','新增收益率','1','0','0','172','6999');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6999','index','收益率列表','1','0','0','172','6999');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6998','export_csv','导出功能','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6996','edit','编辑','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6997','set_effect','设置状态','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6995','foreverdelete','彻底删除','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6994','add','新增','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6993','index','理财列表','1','0','0','171','6993');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6992','nav_setting','自定义菜单列表','1','0','0','234','6992');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6990','advanced','高级群发','1','0','0','243','6990');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6991','advanced_add','新增高级群发信息','1','0','0','243','6990');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6989','to_send_message','推送消息','1','0','0','242','6984');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6988','newsAdvItem','获取高级群发节点','1','0','0','243','6990');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6987','formatAdvSendMsg','格式化模板','1','0','0','242','6984');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6986','message_send_del','删除普通群发信息','1','0','0','242','6984');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6985','message_send_add','新增普通群发信息','1','0','0','242','6984');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6984','message_send','普通消息群发','1','0','0','242','6984');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6983','setgroup','批量粉丝转移','1','0','0','170','6980');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6977','groups_synch','同步微信分组','1','0','0','241','6975');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6982','send_info','刷新所有粉丝','1','0','0','170','6980');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6981','send','获取最新粉丝','1','0','0','170','6980');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6980','index','会员管理','1','0','0','170','6980');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6979','groups_editor','编辑分组','1','0','0','241','6975');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6978','delgroups','删除分组','1','0','0','241','6975');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6976','groups_add','新增分组','1','0','0','241','6975');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6975','groups','分组管理列表','1','0','0','241','6975');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6974','reset_sending','重置队列发送','1','0','0','240','6970');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6972','show_content','模板消息查看','1','0','0','240','6970');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6973','send','模板消息发送','1','0','0','240','6970');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6971','foreverdel','模板消息彻底删除','1','0','0','240','6970');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6970','msglist','模板消息队列','1','0','0','240','6970');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6969','install_tmpl','模板安装','1','0','0','169','6968');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6968','index','模板列表','1','0','0','169','6968');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6967','set_industry','设置行业','1','0','0','239','6967');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6966','editlbs','添加LBS回复','1','0','0','238','6965');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6965','lbs','LBS回复列表','1','0','0','238','6965');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6964','editnews','添加图文回复','1','0','0','237','6963');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6963','news','图文回复列表','1','0','0','237','6963');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6962','delreply','删除文本回复','1','0','0','236','6960');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6961','edittext','添加文本回复','1','0','0','236','6960');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6960','txt','文本回复列表','1','0','0','236','6960');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6959','onfocus','添加关注时回复','1','0','0','235','6959');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6958','index','默认回复设置','1','0','0','168','6958');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6957','syn_to_weixin','同步到公众平台','1','0','0','234','6992');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6954','new_nav_row','添加主菜单','1','0','0','234','6992');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6955','account_remove','删除','1','0','0','234','6992');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6956','nav_save','保存','1','0','0','234','6992');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6953','index','账户管理','1','0','0','167','6953');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6952','update','编辑','1','0','0','166','6951');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6951','index','第三方平台设置','1','0','0','166','6951');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6950','trash','回收站','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6948','delete','删除','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6949','set_effect','状态设置','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6947','edit','编辑','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6945','index','合同范本列表','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6946','add','新增','1','0','0','165','6945');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6940','index','站内消息列表','1','0','0','164','6940');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6941','add','新增','1','0','0','164','6940');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6942','edit','编辑','1','0','0','164','6940');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6943','foreverdelete','彻底删除','1','0','0','164','6940');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6944','msg_list','站内消息队列列表','1','0','0','217','6944');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6939','vote_info','查看调查信息','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6938','vote_result','查看统计结果','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7208','install','安装 ','1','0','0','119','633');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7207','edit','编辑 ','1','0','0','119','633');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7205','edit_user_bonus','审核','1','0','0','210','6920');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6933','foreverdelete','彻底删除','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7204','del_user_bonus','删除','1','0','0','210','6920');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6928','set_sort','排序','1','0','0','162','6925');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6929','edit','编辑','1','0','0','162','6925');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6930','submit_index','未审核公司列表','1','0','0','212','6930');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6927','toogle_status','推荐','1','0','0','162','6925');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6926','delete','删除','1','0','0','162','6925');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6925','online_index','公司列表','1','0','0','162','6925');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6924','submit_stock_transfer','股权转让中列表','1','0','0','211','6924');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6923','shelves','未审核转让审核','1','0','0','161','6921');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6922','edit_investor','未审核转让编辑','1','0','0','161','6921');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6921','submit_index','未审核转让列表','1','0','0','161','6921');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6920','submit_buy_house_earnings','未审核买房收益列表','1','0','0','210','6920');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6919','submit_fixed_interest','未审核固定回报列表','1','0','0','209','6919');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6918','del_user_bonus','未审核分红删除','1','0','0','208','6916');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6917','edit_user_bonus','未审核分红审核','1','0','0','208','6916');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6916','submit_user_bonus','未审核分红列表','1','0','0','208','6916');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6915','foreverdelete','删除分类','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6914','edit','更新分类','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6913','add','添加分类','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6912','index','分类列表','1','0','0','160','6912');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6901','delete','删除记录','1','0','0','156','6900');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6900','index','诚意金记录','1','0','0','156','6900');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6899','delete','删除记录','1','0','0','155','6898');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6898','index','提现记录','1','0','0','155','6898');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6897','delete','删除记录','1','0','0','154','6896');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6896','index','充值记录','1','0','0','154','6896');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6895','delete','删除记录','1','0','0','153','6894');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6894','index','诚意金记录','1','0','0','153','6894');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6893','index','申请解冻资金列表','1','0','5','152','6893');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6892','edit_dsffreezer','解冻诚意金','1','0','0','151','6891');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6891','index','冻结资金列表','1','0','5','151','6891');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6911','show_content','申请审核','1','0','5','137','6867');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6887','index','留言列表','1','0','89','144','6887');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6886','index','留言分类列表','1','0','89','145','6886');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6885','site_costs_total','网站费用统计','1','0','88','230','6885');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6884','user_total','用户统计','1','0','88','229','6884');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6883','money_carry_bank_total','提现统计','1','0','88','228','6883');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6882','money_inchange_total','充值统计','1','0','88','227','6882');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6881','breach_convention_total','违约统计','1','0','87','226','6881');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6880','financing_amount_total','融资金额','1','0','87','225','6880');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6879','investors_total','投资人统计','1','0','87','224','6879');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6878','overdue_total','逾期统计','1','0','86','222','6878');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6877','hasback_total','回报统计','1','0','86','221','6877');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6876','money_total','金额统计','1','0','86','220','6876');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6875','investe_total','项目统计','1','0','87','223','6875');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6874','usernum_total','人数统计','1','0','86','219','6874');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6872','project','项目统计','1','0','86','218','6872');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('701','index','会员邀请返利列表','1','0','85','142','701');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('700','referrals_count','会员邀请统计','1','0','85','189','700');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('698','sharefee_list','查看分红列表','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6870','config','提现手续费','1','0','5','140','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('695','add','增加页面','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('697','delete','删除','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6869','edit','编辑页面','1','0','0','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7210','foreverdelete','彻底删除 ','1','0','0','216','668');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6871','index','资金托管','1','0','75','141','6871');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('693','index','问卷调查列表','1','0','84','139','6869');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6868','index','提现银行设置','1','0','5','138','6868');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('6867','index','投资申请列表','1','0','69','137','6867');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('485','savemobile','保存手机端配置','1','0','0','12','483');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('484','index','手机端广告列表','1','0','62','93','484');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('483','mobile','手机端配置','1','0','62','12','483');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('689','index','系统监测列表','1','0','83','136','689');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('688','index','地区列表','1','0','82','135','688');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('687','trash','分类回收站','1','0','81','213','687');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('686','index','分类列表','1','0','81','134','686');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('685','trash','文章回收站','1','0','80','214','685');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('684','index','文章列表','1','0','80','133','684');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('682','index','项目等级','0','0','72','132','682');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('16','index','系统配置','1','0','5','12','16');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('17','index','数据库备份列表','1','0','8','13','17');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('681','index','会员等级','1','0','69','131','681');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('680','index','分组列表','1','0','79','130','680');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('677','index','链接列表','1','0','79','129','677');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('676','foreverdelete','永久删除','1','0','0','128','674');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('675','send','手动发送','1','0','0','128','674');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('674','index','推广队列列表','1','0','33','128','674');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('673','foreverdelete','删除','1','0','0','215','667');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('672','index','查看对列','1','0','0','216','668');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('671','index','查看对列','1','0','0','215','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('269','uninstall','卸载','1','0','0','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('270','set_effect','设置生效','1','0','0','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('271','send_demo','发送测试短信','1','0','0','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('474','index','缓存处理','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('475','clear_parse_file','清空脚本样式缓存','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('477','clear_data','清空数据缓存','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('480','syn_data','同步数据','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('481','clear_image','清空图片缓存','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('482','clear_admin','清空后台缓存','1','0','0','92','474');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('605','index','消息模板','1','0','77','114','605');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('668','sms_index','短信列表','1','0','0','216','668');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('667','mail_index','邮件列表','1','0','28','215','667');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('665','delete','删除记录','1','0','0','126','664');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('664','index','提现审核列表','1','0','78','126','664');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('663','delete','删除记录','1','0','0','125','662');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('661','index','项目点评','1','0','74','124','661');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('662','index','付款记录','1','0','76','125','662');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('660','incharge','项目收款','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('659','delete','删除支持','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('656','index','项目支持','1','0','73','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('657','view','查看详情','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('658','refund','项目退款','1','0','0','123','656');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('654','del_deal_log','删除日志','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('655','batch_refund','批量退款','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('653','deal_log','项目日志','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('652','del_pay_log','删除发放','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('650','pay_log','筹款发放','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('651','add_pay_log','发放','1','0','0','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('649','foreverdelete','删除问题','1','0','0','122','646');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('646','index','常见问题','1','0','5','122','646');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('647','add','添加问题','1','0','0','122','646');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('648','edit','更新问题','1','0','0','122','646');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('645','foreverdelete','删除帮助','1','0','0','121','642');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('644','edit','修改帮助','1','0','0','121','642');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('643','add','添加帮助','1','0','0','121','642');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('641','delete_index','回收站','1','0','72','192','641');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('642','index','帮助列表','1','0','5','121','642');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('640','foreverdelete','删除广告','1','0','0','120','637');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('639','edit','修改广告','1','0','0','120','637');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('638','add','添加广告','1','0','0','120','637');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('637','index','轮播广告设置','1','0','5','120','637');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('636','uninstall','卸载接口','1','0','0','119','633');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('633','index','支付接口列表','1','0','75','119','633');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('632','submit_index','未审核项目','1','0','72','191','632');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('631','online_index','上线项目列表','1','0','72','190','631');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('630','foreverdelete','删除分类','1','0','0','117','627');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('628','add','添加分类','1','0','0','117','627');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('627','index','分类列表','1','0','72','117','627');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('626','uninstall','卸载接口','1','0','0','116','623');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('625','update','更新配置','1','0','0','116','623');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('624','insert','安装接口','1','0','0','116','623');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('623','index','同步登录接口','1','0','71','116','623');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('622','uninstall','卸载整合','1','0','0','115','620');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('621','save','执行整合','1','0','0','115','620');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('620','index','会员整合','1','0','70','115','620');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('618','weibo','微博列表','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('619','foreverdelete_weibo','删除微博','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('617','foreverdelete_consignee','删除配送地址','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('616','consignee','配送地址','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('615','foreverdelete_account_detail','删除帐户日志','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('614','account_detail','帐户日志','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('613','modify_account','会员资金变更','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('612','delete','删除会员','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('607','index','会员列表','1','0','69','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('608','add','添加会员','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('610','edit','编辑会员','1','0','0','113','607');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('606','update','更新模板','1','0','0','114','605');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('265','install','安装','1','0','0','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('267','edit','编辑','1','0','0','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('264','foreverdelete','永久删除','1','0','0','231','13');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('263','restore','恢复','1','0','0','231','13');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('262','delete','删除','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('261','set_effect','设置生效','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('260','update','编辑执行','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('259','edit','编辑页面','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('258','insert','添加执行','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('257','add','添加页面','1','0','0','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('232','set_sort','排序','1','0','0','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('231','set_effect','设置状态','1','0','0','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('229','edit','编辑','1','0','0','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('211','send_demo','发送测试邮件','1','0','0','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('210','foreverdelete','永久删除','1','0','0','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('209','set_effect','设置状态','1','0','0','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('207','edit','编辑','1','0','0','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('205','add','添加','1','0','0','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('198','foreverdelete','永久删除','1','0','0','15','19');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('182','deleteImg','删除图片','1','0','0','19','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('149','foreverdelete','永久删除','1','0','0','56','66');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('181','do_upload_img','图片控件上传','1','0','0','19','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('148','send','手动发送','1','0','0','56','66');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('147','show_content','显示内容','1','0','0','56','66');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('105','execute','执行SQL语句','1','0','0','233','18');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('102','restore','恢复备份','1','0','0','13','17');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('101','delete','删除备份','1','0','0','13','17');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('100','dump','备份数据','1','0','0','13','17');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('99','update','更新配置','1','0','0','12','16');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('81','set_effect','设置生效','1','0','0','53','63');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('80','foreverdelete','永久删除','1','0','0','53','63');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('78','edit','编辑','1','0','0','53','63');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('77','add','添加','1','0','0','53','63');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('76','set_default','设置默认管理员','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('75','foreverdelete','永久删除','1','0','0','232','15');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('74','restore','恢复','1','0','0','232','15');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('73','delete','删除','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('72','update','编辑执行','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('70','set_effect','设置生效','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('68','add','添加页面','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('69','edit','编辑页面','1','0','0','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('66','index','业务队列列表','1','0','33','56','66');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('63','index','广告列表','1','0','31','53','63');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('58','index','接口列表','1','0','29','48','58');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('57','index','邮件服务器列表','1','0','28','47','57');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('43','index','菜单列表','1','0','19','36','43');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('24','do_upload','编辑器图片上传','1','0','0','19','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('19','index','系统日志列表','1','0','9','15','19');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('18','sql','SQL操作','1','0','8','233','18');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('15','trash','管理员回收站','1','0','7','232','15');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('14','index','管理员列表','1','0','7','6','14');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('13','trash','管理员分组回收站','1','0','7','231','13');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('11','index','管理员分组列表','1','0','7','5','11');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('334','main','首页','1','0','1','58','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7277','export_csv_breach_convention_info','导出违约统计明细','1','0','0','226','6881');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7278','money_inchange_info','查看','1','0','0','227','6882');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7279','export_csv_money_inchange_info','导出充值明细','1','0','0','227','6882');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7280','money_carry_bank_info','查看','1','0','0','228','6883');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7281','export_csv_money_carry_bank_info','导出提现明细','1','0','0','228','6883');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7282','user_info','查看','1','0','0','229','6884');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7283','export_csv_user_info','导出用户注册明细','1','0','0','229','6884');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7284','set_sort','排序','1','0','0','120','637');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7285','index','固存收益率列表','1','0','0','188','7285');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7286','delete','删除收益率','1','0','0','188','7285');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7287','edit','编辑收益率','1','0','0','188','7285');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7288','add','新增收益率','1','0','0','188','7285');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7289','export_csv','购买记录导出','1','0','0','174','7004');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7290','set_status','拒绝赎回','1','0','0','244','7031');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7291','set_status','拒绝赎回','1','0','0','179','7028');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7292','set_status','发放执行','1','0','0','180','7032');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7293','cancel_order','确认取消','1','0','0','185','7052');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7308','set_sort','排序','1','0','0','193','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7297','set_sort','排序','1','0','0','197','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7298','set_sort','排序','1','0','0','201','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7299','edit','编辑','1','0','0','193','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7300','edit','编辑','1','0','0','197','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7301','edit','编辑','1','0','0','201','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7302','foreverdelete','删除分类','1','0','0','193','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7303','foreverdelete','删除分类','1','0','0','197','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7304','foreverdelete','删除分类','1','0','0','201','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7305','add','添加分类','1','0','0','193','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7306','add','添加分类','1','0','0','197','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7307','add','添加分类','1','0','0','201','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7296','index','分类列表','1','0','0','193','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7309','index','分类列表','1','0','0','197','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7310','index','分类列表','1','0','0','201','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7312','get_sharefee_list','分红列表','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7313','get_sharefee_list','分红列表','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7315','deal_item','子项目列表','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7316','deal_item','子项目列表','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7317','deal_item','子项目列表','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7318','deal_item','子项目列表','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7319','update_deal_item','更新子项目','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7320','update_deal_item','更新子项目','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7321','update_deal_item','更新子项目','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7322','update_deal_item','更新子项目','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7323','del_deal_item','删除子项目','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7324','del_deal_item','删除子项目','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7325','del_deal_item','删除子项目','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7326','del_deal_item','删除子项目','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7327','edit_deal_item','编辑子项目','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7328','edit_deal_item','编辑子项目','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7329','edit_deal_item','编辑子项目','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7330','edit_deal_item','编辑子项目','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7331','add_deal_item','新增子项目','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7332','add_deal_item','新增子项目','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7333','add_deal_item','新增子项目','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7334','add_deal_item','新增子项目','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7446','interest_add','新增固存收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7336','edit_investor','编辑股权众筹','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7337','edit_investor','编辑股权众筹','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7338','edit_investor','编辑股权众筹','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7339','edit','编辑','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7342','edit','编辑','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7343','delete','移到回收站','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7344','delete','移到回收站','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7345','delete','移到回收站','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7346','delete','移到回收站','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7347','set_sort','设置排序','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7348','set_sort','设置排序','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7349','set_sort','设置排序','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7350','set_sort','设置排序','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7352','add_investor','新增股权众筹','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7353','add_investor','新增股权众筹','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7354','add_investor','新增股权众筹','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7355','add','新增公益众筹','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7445','interest_edit','编辑固存收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7444','interest_delete','删除固存收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7358','add','新增产品众筹','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7359','sharefee_list','查看分红列表','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7360','sharefee_list','查看分红列表','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7361','sharefee_list','查看分红列表','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7362','sharefee_list','查看分红列表','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7363','del_deal_log','删除日志','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7364','del_deal_log','删除日志','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7365','del_deal_log','删除日志','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7366','del_deal_log','删除日志','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7367','batch_refund','批量退款','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7368','batch_refund','批量退款','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7369','batch_refund','批量退款','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7370','batch_refund','批量退款','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7371','deal_log','项目日志','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7372','deal_log','项目日志','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7373','deal_log','项目日志','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7374','deal_log','项目日志','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7375','del_pay_log','删除发放','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7376','del_pay_log','删除发放','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7377','del_pay_log','删除发放','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7378','del_pay_log','删除发放','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7379','pay_log','筹款发放','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7380','pay_log','筹款发放','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7381','pay_log','筹款发放','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7382','pay_log','筹款发放','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7383','add_pay_log','发放','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7384','add_pay_log','发放','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7385','add_pay_log','发放','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7386','add_pay_log','发放','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7387','online_index','上线项目列表','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7388','online_index','上线项目列表','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7389','online_index','上线项目列表','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7390','online_index','上线项目列表','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7423','add_deal_item','添加子项目','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7396','add_deal_item','添加子项目','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7397','add_deal_item','添加子项目','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7398','add_deal_item','添加子项目','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7399','del_deal_item','删除子项目','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7400','del_deal_item','删除子项目','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7401','del_deal_item','删除子项目','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7402','del_deal_item','删除子项目','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7403','edit_deal_item','编辑子项目','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7404','edit_deal_item','编辑子项目','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7405','edit_deal_item','编辑子项目','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7406','edit_deal_item','编辑子项目','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7443','interest_index','固存收益率列表','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7408','edit_investor','股权编辑上架','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7409','edit_investor','股权编辑上架','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7411','edit','公益编辑上架','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7414','edit','编辑上架','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7415','deal_item','子项目','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7416','deal_item','子项目','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7417','deal_item','子项目','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7418','deal_item','子项目','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7419','foreverdelete','彻底删除','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7420','foreverdelete','彻底删除','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7421','foreverdelete','彻底删除','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7422','foreverdelete','彻底删除','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7395','submit_index','未审核项目','1','0','0','195','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7424','submit_index','未审核项目','1','0','0','199','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7425','submit_index','未审核项目','1','0','0','203','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7426','submit_index','未审核项目','1','0','0','206','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7427','foreverdelete','彻底删除','1','0','0','196','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7428','foreverdelete','彻底删除','1','0','0','200','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7429','foreverdelete','彻底删除','1','0','0','204','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7430','foreverdelete','彻底删除','1','0','0','207','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7431','edit','编辑','1','0','0','196','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7432','edit_investor','编辑','1','0','0','200','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7433','edit_investor','编辑','1','0','0','204','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7434','edit','编辑','1','0','0','207','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7435','restore','回收站恢复','1','0','0','196','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7436','restore','回收站恢复','1','0','0','200','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7437','restore','回收站恢复','1','0','0','204','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7438','restore','回收站恢复','1','0','0','207','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7439','delete_index','回收站','1','0','0','196','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7440','delete_index','回收站','1','0','0','200','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7441','delete_index','回收站','1','0','0','204','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7442','delete_index','回收站','1','0','0','207','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7450','dealshow_add','新增首页订单展示','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7451','history_edit','编辑余额宝收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7452','history_delete','删除余额宝收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7453','history_add','新增余额宝收益率','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7454','history_index','余额宝收益率列表','1','0','0','171','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7455','toogle_status','设置首页类型','1','0','0','190','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7456','get_pay_list','支持列表','1','0','0','190','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7458','get_pay_list','支持列表','1','0','0','194','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7459','toogle_status','设置首页类型','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7460','get_pay_list','支持列表','1','0','0','198','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7461','toogle_status','设置首页类型','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7462','get_pay_list','支持列表','1','0','0','202','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7463','toogle_status','设置首页类型','1','0','0','205','0');
INSERT INTO `%DB_PREFIX%role_node` VALUES ('7464','get_pay_list','支持列表','1','0','0','205','0');
DROP TABLE IF EXISTS `%DB_PREFIX%sms`;
CREATE TABLE `%DB_PREFIX%sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `server_url` text NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `config` text NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='// 短信';
INSERT INTO `%DB_PREFIX%sms` VALUES ('9','短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)','','DXB','http://api.smsbao.com/','vitakung','vitakung','N;','1');
