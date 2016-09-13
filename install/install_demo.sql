-- MySQL dump 10.13  Distrib 5.1.71, for Win32 (ia32)
--
-- Host: localhost    Database: zc03
-- ------------------------------------------------------
-- Server version	5.1.71-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fanwe_admin`
--

DROP TABLE IF EXISTS `fanwe_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adm_name` varchar(255) NOT NULL,
  `adm_password` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `role_id` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `login_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_adm_name` (`adm_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='//管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_admin`
--

LOCK TABLES `fanwe_admin` WRITE;
/*!40000 ALTER TABLE `fanwe_admin` DISABLE KEYS */;
INSERT INTO `fanwe_admin` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3',1,0,4,1456649913,'124.202.243.78'),(4,'test','098f6bcd4621d373cade4e832627b4f6',1,0,4,1453145662,'::1');
/*!40000 ALTER TABLE `fanwe_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_adv`
--

DROP TABLE IF EXISTS `fanwe_adv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmpl` varchar(255) NOT NULL,
  `adv_id` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `rel_table` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tmpl` (`tmpl`),
  KEY `adv_id` (`adv_id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_table` (`rel_table`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='//广告位';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_adv`
--

LOCK TABLES `fanwe_adv` WRITE;
/*!40000 ALTER TABLE `fanwe_adv` DISABLE KEYS */;
INSERT INTO `fanwe_adv` VALUES (44,'fanwe_1','deals_top','<div style=\"text-align:center;\">\r\n	<img src=\"./public/attachment/201602/29/01/56d32e7caf79e.jpg\" alt=\"\" width=\"1520\" height=\"277\" title=\"\" align=\"\" /><br />\r\n</div>',1,'产品众筹广告页面',0,''),(45,'fanwe_1','deals_bottom','<img src=\"./public/attachment/201602/29/01/56d32edc94c5f.jpg\" alt=\"\" width=\"1440\" height=\"263\" title=\"\" align=\"\" />',1,'产品众筹广告2',0,''),(46,'fanwe_1','deal_investor_show_bottom','<img src=\"./public/attachment/201602/29/01/56d32f283d090.png\" alt=\"\" width=\"1440\" height=\"263\" title=\"\" align=\"\" />',1,'股权众筹广告页面',0,''),(47,'fanwe_1','faq_index_top','<div style=\"text-align:center;\">\r\n	<img src=\"./public/attachment/201602/29/01/56d32ff476417.jpg\" alt=\"\" width=\"1440\" height=\"512\" title=\"\" align=\"\" /><br />\r\n</div>',1,'帮助列表广告',0,''),(48,'fanwe_1','news_top','<img src=\"./public/attachment/201602/29/01/56d330605b8d8.jpg\" alt=\"\" width=\"1440\" height=\"450\" title=\"\" align=\"\" />',1,'动态广告',0,''),(49,'fanwe_1','index_top','<div style=\"text-align:center;\">\r\n	<img src=\"./public/attachment/201602/29/01/56d330f64c4b4.jpg\" alt=\"\" />\r\n</div>',1,'首页广告',0,'');
/*!40000 ALTER TABLE `fanwe_adv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_api_login`
--

DROP TABLE IF EXISTS `fanwe_api_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_api_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `config` text NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `bicon` varchar(255) NOT NULL,
  `is_weibo` tinyint(1) NOT NULL,
  `dispname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='// 登录接口';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_api_login`
--

LOCK TABLES `fanwe_api_login` WRITE;
/*!40000 ALTER TABLE `fanwe_api_login` DISABLE KEYS */;
INSERT INTO `fanwe_api_login` VALUES (13,'新浪api登录接口','a:3:{s:7:\"app_key\";s:0:\"\";s:10:\"app_secret\";s:0:\"\";s:7:\"app_url\";s:0:\"\";}','Sina','./public/attachment/201210/13/17/50792e5bbc901.gif','./public/attachment/201210/13/16/5079277a72c9d.gif',1,'新浪微博');
/*!40000 ALTER TABLE `fanwe_api_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_article`
--

DROP TABLE IF EXISTS `fanwe_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT ' 文章内容',
  `cate_id` int(11) NOT NULL COMMENT '文章分类ID',
  `create_time` int(11) NOT NULL COMMENT '发表时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `add_admin_id` int(11) NOT NULL COMMENT '发布人(管理员ID)',
  `is_effect` tinyint(4) NOT NULL COMMENT '有效性标识',
  `rel_url` varchar(255) NOT NULL COMMENT '自动跳转的外链',
  `update_admin_id` int(11) NOT NULL COMMENT '更新人(管理员ID)',
  `is_delete` tinyint(4) NOT NULL COMMENT '删除标识',
  `click_count` int(11) NOT NULL COMMENT '点击数',
  `sort` int(11) NOT NULL COMMENT '排序 由大到小',
  `seo_title` text NOT NULL COMMENT '自定义seo页面标题',
  `seo_keyword` text NOT NULL COMMENT '自定义seo页面keyword',
  `seo_description` text NOT NULL COMMENT '自定义seo页面标述',
  `uname` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `brief` text NOT NULL,
  `is_week` tinyint(1) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `icon` varchar(255) NOT NULL COMMENT '展示图表',
  `writer` varchar(255) NOT NULL COMMENT '发布者',
  `tags` varchar(255) NOT NULL COMMENT '标签',
  PRIMARY KEY (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `click_count` (`click_count`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COMMENT='//文章列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_article`
--

LOCK TABLES `fanwe_article` WRITE;
/*!40000 ALTER TABLE `fanwe_article` DISABLE KEYS */;
INSERT INTO `fanwe_article` VALUES (68,'关于我们','关于方维众筹 <br />\r\n<br />\r\n<p>\r\n	众筹，译自国外crowdfunding一词，即大众筹资或群众筹资。是指用团购+预购的形式，向网友募集项目资金的模式。众筹利用互联网和SNS传播的特性，让许多有梦想的人可以向公众展示自己的创意，发起项目争取别人的支持与帮助，进而获得所需要的援助，支持者则会获得实物、服务等不同形式的回报。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n众筹是一个协助亲们发起创意、梦想的平台，不论你是学生、白领、艺术家、明星，如果你有一个想完成的计划（例如：电影、音乐、动漫、设计、公益等），你可以在此发起项目向大家展示你的计划，并邀请喜欢你的计划的人以资金支持你。如果你愿意帮助别人，支持别人的梦想，你可以在众筹浏览到各行各业的人发起的项目计划，也可以成为发起人的梦想合伙人，当你们一起见证项目成功后，你还会获得发起人感谢你支持的回报。<br />\r\n<br />',22,1413251192,1434136614,0,1,'',0,0,0,1,'众筹，译自国外crowdfunding一词，即大众筹资或群众筹资。','方维系统 众筹网站 方维众筹','众筹，译自国外crowdfunding一词，即大众筹资或群众筹资。','','','',1,1,'','方维众筹','关于'),(69,'联系方式','<img src=\"http://www.fanwe.com/app/Tpl/new/coupon/images/index_end_4.gif\" /> \r\n<div class=\"_link_1\">\r\n	<p class=\"shouqian\">\r\n		<span><img src=\"http://www.fanwe.com/app/Tpl/new/coupon/images/index_end_1.gif\" /></span>&nbsp;&nbsp;<span>福州市台江区八一七中路群升国际E区</span>\r\n	</p>\r\n	<p class=\"lianxi_1\">\r\n		<span><img src=\"http://www.fanwe.com/app/Tpl/new/coupon/images/index_end_2.gif\" /></span>&nbsp;&nbsp;<span>售前咨询（09:00-18:00）</span> 400-600-5505 0591-88600697\r\n	</p>\r\n	<p class=\"lianxi_1\">\r\n		QQ:800005515&nbsp;\r\n	</p>\r\n</div>',22,1413251436,1434136603,0,1,'',0,0,0,2,'','','','','','',1,1,'','方维众筹','联系'),(72,'常见问题','',24,1413338371,1434136594,0,1,'http://t2.fanwe.net:107/zc_svn/index.php?ctl=faq',0,0,0,5,'','','','','','',1,1,'','方维众筹','问题'),(77,'项目规范','<b>项目规范</b><br />\r\n<br />\r\n本众筹系统是中国最专业的众筹系统服务提供商，帮您在一天内快速搭建众筹平台。<br />\r\n<br />\r\n系统咨询热线： <br />\r\n<br />\r\n以下是众筹网站发布项目的基本要求，不合要求的项目，将会被拒绝或删除。如果你有疑问，可以通过邮件或电话联系我们。 <br />\r\n<br />\r\n<br />\r\n附注：某些规范会随着时间而更新或者调整，会导致一些旧项目并不能完全符合最新规范。<br />\r\n<br />\r\n项目发布团队资格：<br />\r\n&nbsp;&nbsp;&nbsp; （团队中必须有至少一名成员满足以下条件）<br />\r\n&nbsp;&nbsp;&nbsp; 18周岁以上;<br />\r\n&nbsp;&nbsp;&nbsp; 中华人民共和国公民;<br />\r\n&nbsp;&nbsp;&nbsp; 拥有能够在中国地区接收人民币汇款的银行卡或者支付宝、财付通账户;<br />\r\n&nbsp;&nbsp;&nbsp; 提供必要的身份认证和资质认证，根据项目内容，有可能包括但不限于：身份证，护照，学历证明等;<br />\r\n&nbsp;&nbsp;&nbsp; 其他跟项目发布、执行需求、渠道销售相关的必须条件。<br />\r\n项目发布：<br />\r\n&nbsp;&nbsp;&nbsp; 根据相关法律法规，项目发布申请提交后，须经过众筹网站工作人员审核后才能发布;<br />\r\n&nbsp;&nbsp;&nbsp; 根据项目的内容，众筹网站会要求项目发布团队提供相关材料，证明项目的可行性，以及项目发布团队的执行能力;<br />\r\n&nbsp;&nbsp;&nbsp; 众筹网站对提交上线审核的项目是否拥有上线资格具有最终决定权;<br />\r\n&nbsp;&nbsp;&nbsp; 项目在众筹网站上线预售期间，不能在中国大陆其他相似平台（包括但不限于众筹网站、电商网站、及其他形式网店等）同时发布。一经发现将立即下线处 理，其项目上线期间所获得的金额将被立即退回给预订用户在众筹网站上的账户中。<br />\r\n<br />\r\n项目内容规范（不符合以下内容规范的项目将被退回）：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 1. 只允许尚未正式对外发布的项目在众筹网站上线。项目在众筹网站上线之前，不能在中国大陆其他相似平台（包括但不限于众筹网站、电商网站、及其 他形式网店等）或媒体发布。<br />\r\n&nbsp;&nbsp;&nbsp; 2. 项目必须为智能项目。智能项目的定义为：设备必须可采集数据、联网联动，并提供自动化的服务。单纯有设计感的非智能项目暂时无法通过审核。<br />\r\n&nbsp;&nbsp;&nbsp; 3. 项目发布方必须在项目上线前提供无bug的实物试产样机，由众筹网站工作人员进行盲测确保没有问题才能正式上线。<br />\r\n&nbsp;&nbsp;&nbsp; 4. 项目内容介绍框架必须包含“项目介绍”、“团队介绍”、“研发进展”等重要板块。<br />\r\n&nbsp;&nbsp;&nbsp; 5. 项目软硬件设计必须完整、合理、具有可行性；有完整的计划和执行能力，且图片、视频不能借用或盗用非自行拍摄的内容。<br />\r\n&nbsp;&nbsp;&nbsp; 6. 项目发布团队必须有明确的生产计划及售后服务计划，尚不确定是否会量产的项目不符合首次发布的标准皆不能上线。<br />\r\n&nbsp;&nbsp;&nbsp; 7. 提交申请的项目必须能符合如下分类：医疗健康、家居生活、出行定位、影音娱乐、科技外设。<br />\r\n&nbsp;&nbsp;&nbsp; 8. 以下类别的项目或内容将不被允许在此发布或作为给预订用户的附加回报：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 烟、酒相关<br />\r\n&nbsp;&nbsp;&nbsp; 洗浴、美容或化妆项目相关<br />\r\n&nbsp;&nbsp;&nbsp; 毒品、类似毒品的物质、吸毒用具、烟等相关<br />\r\n&nbsp;&nbsp;&nbsp; 枪支、武器和刀具相关<br />\r\n&nbsp;&nbsp;&nbsp; 营养补充剂相关<br />\r\n&nbsp;&nbsp;&nbsp; 色情、保健、性用品内容相关<br />\r\n&nbsp;&nbsp;&nbsp; 房地产相关<br />\r\n&nbsp;&nbsp;&nbsp; 饮食类相关<br />\r\n&nbsp;&nbsp;&nbsp; 财政奖励(所有权、利润份额、还款贷款等)<br />\r\n&nbsp;&nbsp;&nbsp; 多级直销和传销类相关<br />\r\n&nbsp;&nbsp;&nbsp; 令人反感的内容(仇恨言论、不适当内容等)<br />\r\n&nbsp;&nbsp;&nbsp; 支持或反对政治党派的项目<br />\r\n&nbsp;&nbsp;&nbsp; 推广或美化暴力行为的项目<br />\r\n&nbsp;&nbsp;&nbsp; 对奖、彩票和抽奖活动相关<br />\r\n&nbsp;&nbsp;&nbsp; 股权、债券、分红、利息等形式的附加回报<br />\r\n&nbsp;&nbsp;&nbsp; 与首发项目无关的附加回报<br />\r\n&nbsp;&nbsp;&nbsp; 以其他无可行、不合理的承诺作为附加回报<br />\r\n举报及推荐标准：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 举报：不符合《项目内容规范》<br />\r\n&nbsp;&nbsp;&nbsp; 合格：符合《项目内容规范》<br />\r\n&nbsp;&nbsp;&nbsp; 推荐：合格并且满足下列标准中的任意1-3项（含3项），视为推荐<br />\r\n&nbsp;&nbsp;&nbsp; 强烈推荐：合格并且满足下列标准中的任意3项以上，视为强烈推荐<br />\r\n&nbsp;&nbsp;&nbsp; 1. 项目定位清晰，功能逻辑性强、完整且条理清晰、团队对研发和生产有完整的计划和管控能力，有相关的图片和视频（图片、视频不能借用或盗用非 本人/公司拍摄的）<br />\r\n&nbsp;&nbsp;&nbsp; 2. 项目符合时下趋势、有热点，具备可传播性<br />\r\n&nbsp;&nbsp;&nbsp; 3. 项目本身有创意、创新；非山寨、抄袭、跟风；市面上无同类相似项目<br />\r\n&nbsp;&nbsp;&nbsp; 4. 项目设计感好，有品质，符合大众审美喜好的要求<br />\r\n&nbsp;&nbsp;&nbsp; 5. 项目发布团队有一定的推广渠道、媒体资源、或在公众平台上有一定的影响力<br />\r\n&nbsp;&nbsp;&nbsp; 6. 项目发布团队的话题运营能力出众，与粉丝互动积极正面，能调动起网友的兴趣和参与感<br />\r\n<br />',24,1413588165,1434136547,0,1,'',0,0,0,9,'','','','','','',1,1,'','方维众筹','项目'),(74,'【活动报名】10.21第一期天使合投SHOW热辣登场！','<p>\r\n	本协会精心策划“天使合投SHOW”，期待您的光临！\r\n</p>\r\n<p>\r\n	<strong>活动时间：</strong><span style=\"color:#ff0000;\">2014年10月21日（下周二） 14:00-17:30</span>\r\n</p>\r\n<p>\r\n	<strong>活动地点：</strong>科技园科技大厦B座1层\r\n</p>\r\n<strong>协办及支持单位：</strong>\r\n<p>\r\n	<strong>参与投资机构：</strong>\r\n</p>\r\n<p>\r\n	<strong>活动人数：</strong>50-60人\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong><span style=\"background-color:#ffff00;\">分享嘉宾及主题</span></strong>\r\n</p>\r\n<p>\r\n	<img src=\"./public/attachment/201410/17/16/5440d8025b024.jpg\" alt=\"\" border=\"0\" />\r\n</p>\r\n<br />\r\n<p>\r\n	<strong><span style=\"background-color:#ffff00;\">活动流程：</span></strong>\r\n</p>\r\n<p>\r\n	14:00 活动开始\r\n</p>\r\n<br />\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong><span style=\"background-color:#ffff00;\">报名方法：</span></strong>\r\n</p>\r\n<p>\r\n	邮件： 请注明姓名、机构、职务\r\n</p>\r\n<p>\r\n	电话：方维服务 400-600-5505 请注明姓名、机构、职务\r\n</p>',25,1413506986,1434136582,0,1,'',0,0,0,6,'','','','','','',1,1,'','方维众筹','报名'),(75,'使用条款','使用条款<br />\r\n接受条款<br />\r\n<br />\r\n本站所提供的服务包含众筹网体验和使用、众筹网互联网消息传递服务以及众筹网提供的与众筹网有关的任何其他特色功能、内容或应用程序(合称\"众筹网服务\")。<br />\r\n<br />\r\n无论用户是以\"访客\"(表示用户只是浏览众筹网)还是\"成员\"(表示用户已在众筹网注册并登录)的身份使用众筹网服务，均表示该用户同意遵守本使用协议。<br />\r\n<br />\r\n如 果用户自愿成为众筹网成员并与其他成员交流(包括通过众筹网直接联系或通过众筹网各种服务而连接到的成员)，以及使用众筹网及其各种附加服务，请 务必认真阅读本协议并在注册过程中表明同意接受本协议。本协议的内容包含众筹网关于接受众筹网服务和在众筹网上发布内容的规定、用户使用众筹网服务所享有 的权利、承担的义务和对使用众筹网服务所受的限制、以及众筹网的隐私条款。<br />\r\n<br />\r\n如果用户选择使用某些众筹网服务，可能会收到要求其下载软件或内容的通知，和或要求用户同意附加条款和条件的通知。除非用户选择使用的众筹网服务相关的附加条款和条件另有规定，附加的条款和条件都应被包含于本协议中。<br />\r\n<br />\r\n有权随时修改本协议文本中的任何条款。一旦众筹网对本协议进行修改, 众筹网将会以公告形式发布通知。任何该等修改自发布之日起生效。如果用户在该等修改发布后继续使用众筹网服务，则表示该用户同意遵守对本协议所作出的该等 修改。因此，请用户务必定期查阅本协议，以确保了解所有关于本协议的最新修改。如果用户不同意众筹网对本协议进行的修改，请用户离开众筹网并立即停止使用 众筹网服务。同时，用户还应当删除个人档案并注销成员资格。<br />\r\n<br />\r\n遵守法律<br />\r\n<br />\r\n当使用众筹网服务时，用户同意遵守中华人民共和国 (下称\"中国\")的相关法律法规，包括但不限于《中华人民共和国宪法》、《中华人民共和国合同 法》、《中华人民共和国电信条例》、《互联网信息服务管理办法》、《互联网电子公告服务管理规定》、《中华人民共和国保守国家秘密法》、《全国人民代表大 会常务委员会关于维护互联网安全的决定》、《中华人民共和国计算机信息系统安全保护条例》、《计算机信息网络国际联网安全保护管理办法》、《中华人民共和 国著作权法》及其实施条例、《互联网著作权行政保护办法》等。用户只有在同意遵守所有相关法律法规和本协议时，才有权使用众筹网服务(无论用户是否有意访 问或使用此服务)。请用户仔细阅读本协议并将其妥善保存。<br />\r\n<br />\r\n用户帐号、密码及安全<br />\r\n<br />\r\n用户应提供及时、详尽、准确的个人资 料，并不断及时更新注册时提供的个人资料，保持其详尽、准确。所有用户输入的资料将引用为注册资料。众筹网不对因用户提交的注册信息不真实或未及时准确变 更信息而引起的问题、争议及其后果承担责任。 用户不应将其帐号、密码转让、出借或告知他人，供他人使用。如用户发现帐号遭他人非法使用，应立即通知众筹网。因黑客行为或用户的保管疏忽导致帐号、密码 遭他人非法使用的，众筹网不承担任何责任。<br />\r\n<br />\r\n隐私权政策<br />\r\n<br />\r\n用户提供的注册信息及众筹网保留的用户所有资料将受到中国相关法律法规和众筹网《隐私权政策》的规范。《隐私权政策》构成本协议不可分割的一部分。<br />\r\n<br />\r\n上传内容<br />\r\n<br />\r\n用 户通过任何众筹网提供的服务上传、张贴、发送(通过电子邮件或任何其它方式传送)的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任 何其他材料(以下简称\"内容\"，包括用户个人的或个人创作的照片、声音、视频等)，无论系公开还是私下传播，均由用户和内容提供者承担责任，众筹网不对该 等内容的正确性、完整性或品质作出任何保证。用户在使用众筹网服务时，可能会接触到令人不快、不适当或令人厌恶之内容，用户需在接受服务前自行做出判断。 在任何情况下，众筹网均不为任何内容负责(包括但不限于任何内容的错误、遗漏、不准确或不真实)，亦不对通过众筹网服务上传、张贴、发送(通过电子邮件或 任何其它方式传送)的内容衍生的任何损失或损害负责。众筹网在管理过程中发现或接到举报并进行初步调查后，有权依法停止任何前述内容的传播并采取进一步行 动，包括但不限于暂停某些用户使用众筹网的全部或部分服务，保存有关记录，并向有关机关报告。<br />\r\n<br />\r\n用户行为<br />\r\n<br />\r\n用户在使用众筹网服务时，必须遵守中华人民共和国相关法律法规的规定，用户保证不会利用众筹网服务进行任何违法或不正当的活动，包括但不限于下列行为：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 上传、展示、张贴或以其它方式传播含有下列内容之一的信息：<br />\r\n&nbsp;&nbsp;&nbsp; 反对宪法及其他法律的基本原则的;<br />\r\n&nbsp;&nbsp;&nbsp; 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的;<br />\r\n&nbsp;&nbsp;&nbsp; 损害国家荣誉和利益的;<br />\r\n&nbsp;&nbsp;&nbsp; 煽动民族仇恨、民族歧视、破坏民族团结的;<br />\r\n&nbsp;&nbsp;&nbsp; 破坏国家宗教政策，宣扬邪教和封建迷信的;<br />\r\n&nbsp;&nbsp;&nbsp; 散布谣言，扰乱社会秩序，破坏社会稳定的;<br />\r\n&nbsp;&nbsp;&nbsp; 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的;<br />\r\n&nbsp;&nbsp;&nbsp; 侮辱或者诽谤他人，侵害他人合法权利的;<br />\r\n&nbsp;&nbsp;&nbsp; 含有虚假、有害、胁迫、侵害他人隐私、骚扰、中伤、粗俗、猥亵、或其它道德上令人反感的内容;<br />\r\n&nbsp;&nbsp;&nbsp; 含有中国法律、法规、规章、条例以及任何具有法律效力的规范所限制或禁止的其它内容的;<br />\r\n&nbsp;&nbsp;&nbsp; 不得为任何非法目的而使用网络服务系统;<br />\r\n&nbsp;&nbsp;&nbsp; 用户同时保证不会利用众筹网服务从事以下活动：<br />\r\n&nbsp;&nbsp;&nbsp; 未经允许，进入计算机信息网络或者使用计算机信息网络资源的;<br />\r\n&nbsp;&nbsp;&nbsp; 未经允许，对计算机信息网络功能进行删除、修改或者增加的;<br />\r\n&nbsp;&nbsp;&nbsp; 未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的;故意制作、传播计算机病毒等破坏性程序的;<br />\r\n&nbsp;&nbsp;&nbsp; 其他危害计算机信息网络安全的行为。<br />\r\n<br />\r\n如用户在使用网络服务时违反任何上述规定，众筹网或经其授权者有权要求该用户改正或直接采取一切必要措施(包括但不限于更改、删除相关内容、暂停或终止相关用户使用众筹网服务)以减轻和消除该用户不当行为造成的影响。<br />\r\n<br />\r\n用户不得对众筹网服务的任何部分或全部以及通过众筹网取得的任何形式的信息，进行复制、拷贝、出售、转售或用于任何其它商业目的。<br />\r\n<br />\r\n用户须对自己在使用众筹网服务过程中的行为承担法律责任。用户承担法律责任的形式包括但不限于：停止侵害行为，向受到侵害者公开赔礼道歉，恢复受到 侵害者的名誉，对受到侵害者进行赔偿。如果众筹网网站因某用户的非法或不当行为受到行政处罚或承担了任何形式的侵权损害赔偿责任，该用户应向众筹网进行赔 偿(不低于众筹网向第三方赔偿的金额)并通过全国性的媒体向众筹网公开赔礼道歉。<br />\r\n<br />\r\n知识产权和其他合法权益(包括但不限于名誉权、商誉等)<br />\r\n<br />\r\n并不对用户发布到众筹网服务中的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任何其他材料(前文称为\"内容\")拥有任何所有 权。在用户将内容发布到众筹网服务中后，用户将继续对内容享有权利，并且有权选择恰当的方式使用该等内容。如果用户在众筹网服务中或通过众筹网服务展示或 发表任何内容，即表明该用户就此授予众筹网一个有限的许可以使众筹网能够合法使用、修改、复制、传播和出版此类内容。<br />\r\n<br />\r\n用户同意其已在服务所发布的内容，授予众筹网可以免费的、永久有效的、不可撤销的、非独家的、可转授权的、在全球范围内对所发布内容进行使 用、复制、修改、改写、改编、发行、翻译、创造衍生性著作的权利，及/或可以将前述部分或全部内容加以传播、表演、展示，及/或可以将前述部分或全部内容 放入任何现在已知和未来开发出的以任何形式、媒体或科技承载的著作当中。<br />\r\n<br />\r\n用户声明并保证：用户对其在众筹网服务中或通过众筹网服务发布 的内容拥有合法权利;用户在众筹网服务中或通过众筹网服务发布的内容不侵犯任何人的肖 像权、隐私权、著作权、商标权、专利权、及其它合同权利。如因用户在众筹网服务中或通过众筹网服务发布的内容而需向其他任何人支付许可费或其它费用，全部 由该用户承担。<br />\r\n<br />\r\n众筹网服务中包含众筹网提供的内容，包含用户和其他众筹网许可方的内容(下称\"众筹网的内容\")。众筹网的内容受《中华 人民共和国著作权法》、《中 华人民共和国商标法》、《中华人民共和国专利法》、《中华人民共和国反不正当竞争法》和其他相关法律法规的保护，众筹网拥有并保持对众筹网的内容和众筹网 服务的所有权利。<br />\r\n<br />\r\n国际使用之特别警告<br />\r\n<br />\r\n用户已了解国际互联网的无国界性，同意遵守所有关于网上行为、内容的法律法规。用户特别同意遵守有关从中国或用户所在国家或地区输出信息所可能涉及、适用的全部法律法规。<br />\r\n<br />\r\n项目筹款<br />\r\n<br />\r\n是一个让用户(即“项目发起人”)通过提供回报向支持者筹集资金的平台。您作为项目发起人，社会大众可以与您订立合同，在众筹网创建筹款项 目。您作为支持者，可以接受项目发起人和您之间的回报和契约，以赞助项目发起人的筹款项目。众筹网并不是支持者和项目发起人中的任何一方。所有交易仅存在 于用户和用户之间。<br />\r\n<br />\r\n通过众筹网支持项目，您须同意并遵守以下协议，包括如下条款：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 支持者同意接受在其承诺支持某项目时提供付款授权信息 。<br />\r\n&nbsp;&nbsp;&nbsp; 支持者同意众筹网及其合作伙伴授权或保留收费的权利。<br />\r\n&nbsp;&nbsp;&nbsp; 回报预期的完成日期并非约定实现的期限，它仅为项目发起人希望实现的日期。<br />\r\n&nbsp;&nbsp;&nbsp; 为建立良好的信用和名声，项目发起人会尽力依照预期完成日期实现项目。<br />\r\n&nbsp;&nbsp;&nbsp; 对于所有项目，众筹网将提供所有支持者的用户名称和联系方式给于项目发起人。项目成功时，众筹网将额外提供支持者的姓名、联系方式和邮寄地址等信息给于项目发起人。<br />\r\n&nbsp;&nbsp;&nbsp; 项目发起人可以在项目成功后直接向支持者要求额外信息。为了顺利获得回报，支持者须同意在合理期限内提供给项目发起人相关信息。<br />\r\n&nbsp;&nbsp;&nbsp; 如活动难以进行或无法满足回报需求时，项目发起人可应支持者的请求而退款 。<br />\r\n&nbsp;&nbsp;&nbsp; 项目发起人须满足项目成功后支持者的回报需求，或在无法实现的情况下退款。<br />\r\n&nbsp;&nbsp;&nbsp; 众筹网保留随时以任何理由取消项目的权利。<br />\r\n&nbsp;&nbsp;&nbsp; 众筹网有权随时以任何理由拒绝、取消、中断、删除或暂停该项目。众筹网不因该行为承担任何赔偿。众筹网的政策并非评论此类行为的理由。<br />\r\n&nbsp;&nbsp;&nbsp; 在项目成功和获得款项之间可能存在延迟。<br />\r\n<br />\r\n不承担任何相关回报或使用服务产生的损失或亏损。众筹网无义务介入任何用户之间的纠纷，或用户与其他第三方就服务使用方面产生的纠纷。包括但 不限于货物及服务的交付，其他条款、条件、保证或与网站活动相关联的有关陈述。众筹网不负责监督项目的实现与严格执行。您可授权众筹网、其工作人员、职 员、代理人及对损失索赔权的继任者所有已知或未知、公开或秘密的解决争议的方法和服务。<br />\r\n<br />\r\n费用和付款<br />\r\n<br />\r\n加入众筹网免费，但是我们对于某些服务是收取费用的。当您使用某项服务时，您将有机会看到您需要支付费用的项目，费用的变化在我们在网站上为您公开后生效。您负责支付使用该服务产生的所有费用和税款。<br />\r\n<br />\r\n向支持者筹集的资金通过第三方支付平台支付，众筹网对第三方支付平台的支付性能不承担责任。<br />\r\n<br />\r\n赔偿<br />\r\n<br />\r\n由 于用户通过众筹网服务上传、张贴、发送或传播的内容，或因用户与本服务连线，或因用户违反本使用协议，或因用户侵害他人任何权利而导致任何第三人 向众筹网提出赔偿请求，该用户同意赔偿众筹网及其股东、子公司、关联企业、代理人、品牌共有人或其它合作伙伴相应的赔偿金额(包括众筹网支付的律师费 等)，以使众筹网的利益免受损害。<br />\r\n<br />\r\n关于使用及储存的一般措施<br />\r\n<br />\r\n用户承认众筹网有权制定关于服务使用的一般措施及限制，包括 但不限于众筹网服务将保留用户的电子邮件信息、用户所张贴内容或其它上载内容的最长保留 期间、用户一个帐号可收发信息的最大数量、用户帐号当中可收发的单个信息的大小、众筹网服务器为用户分配的最大磁盘空间，以及一定期间内用户使用众筹网服 务的次数上限(及每次使用时间之上限)。通过众筹网服务存储或传送的任何信息、通讯资料和其它任何内容，如被删除或未予储存，用户同意众筹网毋须承担任何 责任。用户亦同意，超过一年未使用的帐号，众筹网有权关闭。众筹网有权依其自行判断和决定，随时变更相关一般措施及限制。<br />\r\n<br />\r\n服务的修改<br />\r\n<br />\r\n用 户了解并同意，无论通知与否，众筹网有权于任何时间暂时或永久修改或终止部分或全部众筹网服务，对此，众筹网对用户和任何第三人均无需承担任何责 任。用户同意，所有上传、张贴、发送到众筹网的内容，众筹网均无保存义务，用户应自行备份。众筹网不对任何内容丢失以及用户因此而遭受的相关损失承担责 任。<br />\r\n<br />\r\n终止服务<br />\r\n<br />\r\n用户同意众筹网可单方面判断并决定，如果用户违反本使用协议或用户长时间未能使用其帐号，众筹网可以终止该 用户的密码、帐号或某些服务的使用，并可 将该用户在众筹网服务中留存的任何内容加以移除或删除。众筹网亦可基于自身考虑，在通知或未通知之情形下，随时对该用户终止部分或全部服务。用户了解并同 意依本使用协议，无需进行事先通知，众筹网可在发现任何不适宜内容时，立即关闭或删除该用户的帐号及其帐号中所有相关信息及文件，并暂时或永久禁止该用户 继续使用前述文件或帐号。<br />\r\n<br />\r\n与广告商进行的交易<br />\r\n<br />\r\n用户通过众筹网服务与广告商进行任何形式的通讯或商业往来，或参与促销活 动(包括相关商品或服务的付款及交付)，以及达成的其它任何条款、条件、保 证或声明，完全是用户与广告商之间的行为。除有关法律法规明文规定要求众筹网承担责任外，用户因前述任何交易、沟通等而遭受的任何性质的损失或损害，均不予负责。<br />\r\n<br />\r\n链接<br />\r\n<br />\r\n用户了解并同意，对于众筹网服务或第三人提供的其它网站或资源的链接是否可以利用，众筹网不予负 责；存在或源于此类网站或资源的任何内容、广告、产 品或其它资料，众筹网亦不保证或负责。因使用或信赖任何此类网站或资源发布的或经由此类网站或资源获得的任何商品、服务、信息，如对用户造成任何损害，不负任何直接或间接责任。<br />\r\n<br />\r\n禁止商业行为<br />\r\n<br />\r\n用户同意不对众筹网服务的任何部分或全部以及用户通过众筹网的服务取得的任何物品、服务、信息等，进行复制、拷贝、出售、转售或用于任何其它商业目的。<br />\r\n<br />\r\n众筹网的专属权利<br />\r\n<br />\r\n用 户了解并同意，众筹网服务及其所使用的相关软件(以下简称\"服务软件\")含有受到相关知识产权及其它法律保护的专有保密资料。用户同时了解并同 意，经由众筹网服务或广告商向用户呈现的赞助广告或信息所包含之内容，亦可能受到著作权、商标、专利等相关法律的保护。未经众筹网或广告商书面授权，用户 不得修改、出售、传播部分或全部服务内容或软件，或加以制作衍生服务或软件。众筹网仅授予用户个人非专属的使用权，用户不得(也不得允许任何第三人)复 制、修改、创作衍生著作，或通过进行还原工程、反向组译及其它方式破译原代码。用户也不得以转让、许可、设定任何担保或其它方式移转服务和软件的任何权 利。用户同意只能通过由众筹网所提供的界面而非任何其它方式使用众筹网服务。<br />\r\n<br />\r\n担保与保证<br />\r\n<br />\r\n众筹网使用协议的任何规定均不 会免除因众筹网造成用户人身伤害或因故意造成用户财产损失而应承担的任何责任。 用户使用众筹网服务的风险由用户个人承担。众筹网对服务不提供任何明示或默示的担保或保证，包括但不限于商业适售性、特定目的的适用性及未侵害他人权利等 的担保或保证。<br />\r\n<br />\r\n众筹网亦不保证以下事项：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 服务将符合用户的要求；<br />\r\n&nbsp;&nbsp;&nbsp; 服务将不受干扰、及时提供、安全可靠或不会出错；<br />\r\n&nbsp;&nbsp;&nbsp; 使用服务取得的结果正确可靠；<br />\r\n&nbsp;&nbsp;&nbsp; 用户经由众筹网服务购买或取得的任何产品、服务、资讯或其它信息将符合用户的期望，且软件中任何错误都将得到更正。<br />\r\n&nbsp;&nbsp;&nbsp; 用户应自行决定使用众筹网服务下载或取得任何资料且自负风险，因任何资料的下载而导致的用户电脑系统损坏或数据流失等后果，由用户自行承担。<br />\r\n&nbsp;&nbsp;&nbsp; 用户经由众筹网服务获知的任何建议或信息(无论书面或口头形式)，除非使用协议有明确规定，将不构成众筹网对用户的任何保证。<br />\r\n<br />\r\n责任限制<br />\r\n<br />\r\n用户明确了解并同意，基于以下原因而造成的任何损失，众筹网均不承担任何直接、间接、附带、特别、衍生性或惩罚性赔偿责任(即使众筹网事先已被告知用户或第三方可能会产生相关损失)：<br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp; 众筹网服务的使用或无法使用；<br />\r\n&nbsp;&nbsp;&nbsp; 通过众筹网服务购买、兑换、交换取得的任何商品、数据、服务、信息，或缔结交易而发生的成本；<br />\r\n&nbsp;&nbsp;&nbsp; 用户的传输或数据遭到未获授权的存取或变造；<br />\r\n&nbsp;&nbsp;&nbsp; 任何第三方在众筹网服务中所作的声明或行为；<br />\r\n&nbsp;&nbsp;&nbsp; 与众筹网服务相关的其它事宜，但本使用协议有明确规定的除外。<br />\r\n<br />\r\n一般性条款<br />\r\n<br />\r\n本使用协议构成用户与众筹网之间的正式协议，并用于规范用户的使用行为。在用户使用众筹网服务、使用第三方提供的内容或软件时，在遵守本协议的基础上，亦应遵守与该等服务、内容、软件有关附加条款及条件。<br />\r\n<br />\r\n本使用协议及用户与众筹网之间的关系，均受到中华人民共和国法律管辖。<br />\r\n<br />\r\n用户与众筹网就服务本身、本使用协议或其它有关事项发生的争议，应通过友好协商解决。协商不成的，应向相关机构提起诉讼。<br />\r\n<br />\r\n众筹网未行使或执行本使用协议设定、赋予的任何权利，不构成对该等权利的放弃。<br />\r\n<br />\r\n本使用协议中的任何条款因与中华人民共和国法律相抵触而无效，并不影响其它条款的效力。<br />\r\n<br />\r\n本使用协议的标题仅供方便阅读而设，如与协议内容存在矛盾，以协议内容为准。<br />\r\n<br />\r\n举报<br />\r\n<br />\r\n如用户发现任何违反本服务条款的情事，请及时通知众筹网。<br />\r\n<br />',21,1413586458,1434136572,0,1,'',0,0,0,7,'','','','','','',1,1,'','方维众筹','条款'),(76,'【媒体报道】众筹平台助“印象”打造专业川菜连锁品牌','三顾茅庐，方识茅庐真印象!目前已经拥有直营店的平台，10月18号将与平台投资人见面。\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img alt=\"\" src=\"http://www.renrentou.com/s/upload/editor/2014/10images/141016102309036310fuv8e.jpg\" style=\"height:427px;width:651px;\" />\r\n</p>\r\n<p>\r\n	<br />\r\n　　融资计划\r\n</p>\r\n<p>\r\n	<br />\r\n　众筹平台的建立，将进一步提高本服务水平\r\n</p>\r\n<p>\r\n	<br />\r\n　　项目特色\r\n</p>\r\n<p>\r\n	<br />\r\n　项目特色信息\r\n</p>\r\n<p>\r\n	<br />\r\n　　项目背景\r\n</p>\r\n<p>\r\n	　跨济南、济区域，并保持良好增长势头。广大爱好美食的投资朋友们，这样好的赚钱投资机会你想拥有吗?赶紧关注众筹平台吧。\r\n</p>\r\n<p>\r\n	<br />\r\n　　市场优势\r\n</p>\r\n<p>\r\n	<br />\r\n　　拥有多年品牌积淀，就餐环境良好好，价格符合大众消费，是商场餐饮业市场中目前仅有的大众定位的川菜连锁品牌。另外，印象中央厨房的可复制性研发，品牌发展及品质保证，开业即火爆，市场潜力巨大。目前，印象已跨济南、济区域，并保持良好增长势头。广大爱好美食的投资朋友们，这样好的赚钱投资机会你想拥有吗?赶紧关注众筹平台吧。\r\n</p>',26,1413586791,1434136558,0,1,'',0,0,0,8,'','','','','','',1,1,'','方维众筹','媒体'),(78,'版权申明','该系统知识产权归我方所有，<span style=\"font-family:\'宋体\';\">未经书面许可，不得以任何形式公布“软件产品”的源码，并不得复制、传播、出售、出租、出借等。</span><!--[if gte mso 9]><![endif]-->',21,1413588553,1434136537,0,1,'',0,0,0,10,'','','','','','',1,1,'','方维众筹','版权'),(79,'会员注册','',28,1413588976,1434136523,0,1,'user-register',0,0,0,11,'','','','','','',1,1,'','方维众筹','会员'),(80,'发起项目','',28,1413589126,1434136507,0,1,'/zc_svn/project',0,0,0,12,'','','','','','',1,1,'./public/attachment/201412/08/10/548507b508df3.jpg','方维众筹','热门');
/*!40000 ALTER TABLE `fanwe_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_article_cate`
--

DROP TABLE IF EXISTS `fanwe_article_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '分类名称',
  `brief` varchar(255) NOT NULL COMMENT '分类简介(备用字段)',
  `pid` int(11) NOT NULL COMMENT '父ID，程序分类可分二级',
  `is_effect` tinyint(4) NOT NULL COMMENT '有效性标识',
  `is_delete` tinyint(4) NOT NULL COMMENT '删除标识',
  `type_id` tinyint(1) NOT NULL COMMENT '型 0:普通文章（可通前台分类列表查找到） 1.帮助文章（用于前台页面底部的站点帮助） 2.公告文章（用于前台页面公告模块的调用） 3.系统文章（自定义的一些文章，需要前台自定义一些入口链接到该文章） 所属该分类的所有文章类型与分类一致',
  `sort` int(11) NOT NULL,
  `seo_title` varchar(255) NOT NULL COMMENT 'SEO标题',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `type_id` (`type_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='//文章分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_article_cate`
--

LOCK TABLES `fanwe_article_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_article_cate` DISABLE KEYS */;
INSERT INTO `fanwe_article_cate` VALUES (21,'站点申明','',0,1,0,1,10,'zdsm'),(22,'关于我们','',0,1,0,1,0,'gywm'),(23,'众筹简介','众筹简介',0,1,1,0,3,''),(24,'新手帮助','',0,1,0,1,1,'xsbz'),(25,'活动报名','',0,1,0,0,5,'hdbm'),(26,'媒体报道','',0,1,0,0,6,'mtbd'),(27,'合作方式','',0,1,0,1,7,'hzfs'),(28,'我有项目','',0,1,0,1,8,'wyxm');
/*!40000 ALTER TABLE `fanwe_article_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_auto_cache`
--

DROP TABLE IF EXISTS `fanwe_auto_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_auto_cache` (
  `cache_key` varchar(100) NOT NULL,
  `cache_type` varchar(100) NOT NULL,
  `cache_data` text NOT NULL,
  `cache_time` int(11) NOT NULL,
  PRIMARY KEY (`cache_key`,`cache_type`),
  KEY `cache_type` (`cache_type`),
  KEY `cache_key` (`cache_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='//自动缓存';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_auto_cache`
--

LOCK TABLES `fanwe_auto_cache` WRITE;
/*!40000 ALTER TABLE `fanwe_auto_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_auto_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_bank`
--

DROP TABLE IF EXISTS `fanwe_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '银行名称',
  `is_rec` tinyint(1) NOT NULL COMMENT '是否推荐',
  `day` int(11) NOT NULL COMMENT '处理时间',
  `sort` int(11) NOT NULL COMMENT '银行排序',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `is_support_tzt` tinyint(1) NOT NULL COMMENT '0表示不支持易宝投资通，1表示支持支持易宝投资通',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_bank`
--

LOCK TABLES `fanwe_bank` WRITE;
/*!40000 ALTER TABLE `fanwe_bank` DISABLE KEYS */;
INSERT INTO `fanwe_bank` VALUES (1,'中国工商银行',1,3,0,'./public/bank/1.jpg',0),(2,'中国农业银行',1,3,0,'./public/bank/2.jpg',0),(3,'中国建设银行',1,3,0,'./public/bank/3.jpg',0),(4,'招商银行',1,3,0,'./public/bank/4.jpg',0),(5,'中国光大银行',1,3,0,'./public/bank/5.jpg',0),(6,'中国邮政储蓄银行',1,3,0,'./public/bank/6.jpg',0),(7,'兴业银行',1,3,0,'./public/bank/7.jpg',0),(8,'中国银行',0,3,0,'./public/bank/8.jpg',0),(9,'交通银行',0,3,3,'./public/bank/9.jpg',0),(10,'中信银行',0,3,0,'./public/bank/10.jpg',0),(11,'华夏银行',0,3,0,'./public/bank/11.jpg',0),(12,'上海浦东发展银行',0,3,1,'./public/bank/12.jpg',0),(13,'城市信用社',0,3,0,'./public/bank/13.jpg',0),(14,'恒丰银行',0,3,0,'./public/bank/14.jpg',0),(15,'广东发展银行',0,3,0,'./public/bank/15.jpg',0),(16,'深圳发展银行',0,3,2,'./public/bank/16.jpg',0),(17,'中国民生银行',0,3,0,'./public/bank/17.jpg',0),(18,'中国农业发展银行',0,3,0,'./public/bank/18.jpg',0),(19,'农村商业银行',0,3,0,'./public/bank/19.jpg',0),(20,'农村信用社',0,3,0,'./public/bank/20.jpg',0),(21,'城市商业银行',0,3,0,'./public/bank/21.jpg',0),(22,'农村合作银行',0,3,0,'./public/bank/22.jpg',0),(23,'浙商银行',0,3,0,'./public/bank/23.jpg',0),(24,'上海农商银行',0,3,0,'./public/bank/24.jpg',0),(25,'中国进出口银行',0,3,0,'./public/bank/25.jpg',0),(26,'渤海银行',0,3,0,'./public/bank/26.jpg',0),(27,'国家开发银行',0,3,0,'./public/bank/27.jpg',0),(28,'村镇银行',0,3,0,'./public/bank/28.jpg',0),(29,'徽商银行股份有限公司',0,3,0,'./public/bank/29.jpg',0),(30,'南洋商业银行',0,3,0,'./public/bank/30.jpg',0),(31,'韩亚银行',0,3,0,'./public/bank/31.jpg',0),(32,'花旗银行',0,3,0,'./public/bank/32.jpg',0),(33,'渣打银行',0,3,0,'./public/bank/33.jpg',0),(34,'华一银行',0,3,0,'./public/bank/34.jpg',0),(35,'东亚银行',1,3,0,'./public/bank/35.jpg',0),(36,'苏格兰皇家银行',1,1,26,'./public/bank/36.jpg',0);
/*!40000 ALTER TABLE `fanwe_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_collocation`
--

DROP TABLE IF EXISTS `fanwe_collocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_collocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `config` text NOT NULL,
  `fee_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_collocation`
--

LOCK TABLES `fanwe_collocation` WRITE;
/*!40000 ALTER TABLE `fanwe_collocation` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_collocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_conf`
--

DROP TABLE IF EXISTS `fanwe_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `input_type` tinyint(1) NOT NULL COMMENT '配置输入的类型 0:文本输入 1:下拉框输入 2:图片上传 3:编辑器',
  `value_scope` text NOT NULL COMMENT '取值范围',
  `is_effect` tinyint(1) NOT NULL,
  `is_conf` tinyint(1) NOT NULL COMMENT '是否可配置 0: 可配置  1:不可配置',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=312 DEFAULT CHARSET=utf8 COMMENT='// 配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_conf`
--

LOCK TABLES `fanwe_conf` WRITE;
/*!40000 ALTER TABLE `fanwe_conf` DISABLE KEYS */;
INSERT INTO `fanwe_conf` VALUES (1,'DEFAULT_ADMIN','admin',1,0,'',1,0,0),(2,'URL_MODEL','0',1,1,'0,1',1,1,3),(3,'AUTH_KEY','fanwe',1,0,'',1,1,4),(4,'TIME_ZONE','8',1,1,'0,8',1,1,1),(5,'ADMIN_LOG','1',1,1,'0,1',0,1,0),(6,'DB_VERSION','1.61',0,0,'',1,0,0),(7,'DB_VOL_MAXSIZE','8000000',1,0,'',1,1,11),(8,'WATER_MARK','',2,2,'',1,1,48),(10,'BIG_WIDTH','500',2,0,'',0,0,49),(11,'BIG_HEIGHT','500',2,0,'',0,0,50),(12,'SMALL_WIDTH','200',2,0,'',0,0,51),(13,'SMALL_HEIGHT','200',2,0,'',0,0,52),(14,'WATER_ALPHA','75',2,0,'',1,1,53),(15,'WATER_POSITION','4',2,1,'1,2,3,4,5',1,1,54),(16,'MAX_IMAGE_SIZE','3000000',2,0,'',1,1,55),(17,'ALLOW_IMAGE_EXT','jpg,gif,png',2,0,'',1,1,56),(18,'BG_COLOR','#ffffff',2,0,'',0,0,57),(19,'IS_WATER_MARK','1',2,1,'0,1',1,1,58),(20,'TEMPLATE','fanwe_1',1,0,'',1,1,17),(21,'SITE_LOGO','./public/attachment/201602/16/17/56c2ea5ce3b23.png',1,2,'',1,1,19),(173,'SEO_TITLE','猫力中国',1,0,'',1,1,20),(24,'SMS_ON','0',5,1,'0,1',1,1,78),(26,'PUBLIC_DOMAIN_ROOT','',2,0,'',0,1,59),(27,'APP_MSG_SENDER_OPEN','1',1,1,'0,1',1,1,9),(28,'ADMIN_MSG_SENDER_OPEN','1',1,1,'0,1',1,1,10),(29,'GZIP_ON','0',1,1,'0,1',1,1,2),(42,'SITE_NAME','VK维客众筹',1,0,'',1,1,1),(30,'CACHE_ON','1',1,1,'0,1',1,1,7),(31,'EXPIRED_TIME','0',1,0,'',1,1,5),(32,'TMPL_DOMAIN_ROOT','',2,0,'0',0,0,62),(33,'CACHE_TYPE','File',1,1,'File,Xcache,Memcached',1,1,7),(34,'MEMCACHE_HOST','127.0.0.1:11211',1,0,'',1,1,8),(35,'IMAGE_USERNAME','admin',2,0,'',0,1,60),(36,'IMAGE_PASSWORD','admin',2,4,'',0,1,61),(37,'DEAL_MSG_LOCK','0',0,0,'',0,0,1456656932),(38,'SEND_SPAN','2',1,0,'',1,1,85),(39,'TMPL_CACHE_ON','1',1,1,'0,1',1,1,6),(40,'DOMAIN_ROOT','',1,0,'',1,0,10),(41,'COOKIE_PATH','/',1,0,'',0,1,10),(43,'INTEGRATE_CFG','',0,0,'',1,0,0),(44,'INTEGRATE_CODE','',0,0,'',1,0,0),(172,'PAY_RADIO','0.1',3,0,'',1,1,10),(176,'SITE_LICENSE','VK维客众筹 - www.dede168.com 版权所有',1,0,'',1,1,22),(174,'SEO_KEYWORD','VK维客众筹',1,0,'',1,1,21),(175,'SEO_DESCRIPTION','VK维客众筹',1,0,'',1,1,22),(177,'PROMOTE_MSG_LOCK','0',0,0,'',0,0,0),(178,'PROMOTE_MSG_PAGE','0',0,0,'0',0,0,0),(179,'STATE_CDOE','',1,0,'',1,1,23),(180,'USER_VERIFY','0',4,1,'0,1,2,3,4,5,6',1,1,63),(181,'INVITE_REFERRALS','20',4,0,'',1,1,67),(182,'INVITE_REFERRALS_TYPE','1',4,1,'0,1',0,1,68),(183,'USER_MESSAGE_AUTO_EFFECT','1',4,1,'0,1',1,1,64),(184,'BUY_INVITE_REFERRALS','20',4,0,'',1,1,67),(185,'REFERRAL_IP_LIMI','0',4,1,'0,1',1,1,71),(288,'VIRSUAL_NUM','888',4,0,'',1,1,288),(190,'MAIL_SEND_PAYMENT','1',5,1,'0,1',1,1,75),(191,'REPLY_ADDRESS','462414875@qq.com',5,0,'',1,1,77),(192,'MAIL_SEND_DELIVERY','1',5,1,'0,1',1,1,76),(193,'MAIL_ON','1',5,1,'0,1',1,1,72),(262,'NETWORK_FOR_RECORD','京ICP备11046674号-3',1,0,'',1,1,201),(263,'QR_CODE','./public/attachment/201602/16/17/56c2eae468581.jpg',3,2,'',1,1,202),(264,'REPAY_MAKE','7',1,0,'',1,1,264),(265,'SQL_CHECK','0',1,1,'0,1',1,1,265),(266,'MORTGAGE_MONEY','0.01',6,0,'',1,1,1),(267,'ENQUIER_NUM','6',6,0,'',1,1,2),(268,'INVEST_PAY_SEND_STATUS','1',5,1,'0,1,2',1,1,3),(269,'INVEST_STATUS_SEND_STATUS','1',5,1,'0,1,2',1,1,4),(270,'INVEST_PAID_SEND_STATUS','1',5,1,'0,1,2',1,1,5),(271,'INVEST_STATUS','0',6,1,'0,1,2',1,1,0),(272,'AVERAGE_USER_STATUS','1',6,1,'0,1',1,1,6),(186,'REFERRAL_LIMIT','1',4,0,'',1,1,69),(273,'KF_PHONE','010-56267773',3,0,'',1,1,69),(274,'KF_QQ','462414875',3,0,'',1,1,69),(275,'SCORE_TRADE_NUMBER','100',4,0,'',1,1,72),(276,'BUY_PRESEND_SCORE_MULTIPLE','1',4,0,'',1,1,72),(277,'BUY_PRESEND_POINT_MULTIPLE','1',4,0,'',1,1,72),(278,'PROJECT_HIDE','0',3,1,'0,1',1,1,69),(279,'WORK_TIME','09:00-18:30',3,0,'',1,1,69),(280,'IDENTIFY_POSITIVE','1',4,1,'0,1',1,1,283),(281,'IDENTIFY_NAGATIVE','1',4,1,'0,1',1,1,284),(282,'BUSINESS_LICENCE','1',4,1,'0,1',1,1,285),(283,'BUSINESS_CODE','1',4,1,'0,1',1,1,286),(284,'BUSINESS_TAX','1',4,1,'0,1',1,1,287),(289,'MORTGAGE_MONEY_UNFREEZE','12',6,0,'',1,1,500),(290,'WX_MSG_LOCK','0',0,0,'',0,0,0),(291,'USER_VERIFY_STATUS','1',4,1,'0,1',1,1,291),(292,'GQ_NAME','股权众筹',6,0,'',1,1,500),(293,'PAYPASS_STATUS','1',4,1,'0,1',1,1,293),(294,'USER_SEND_VERIFY_TIME','300',4,0,'',1,1,294),(295,'URL_NAME','m.php',1,0,'',1,1,0),(296,'IS_SELFLESS','1',7,1,'0,1',1,1,0),(297,'IS_ENQUIRY','1',6,1,'0,1',1,1,0),(298,'IS_LIMITED_PARTNER','1',6,1,'0,1',1,1,0),(299,'IS_FINANCE','0',6,1,'0,1',1,1,0),(300,'VOTE_ID','',4,0,'',1,1,0),(301,'IS_HOUSE','0',7,1,'0,1',1,1,0),(302,'USER_SUBMIT_TIME','5',4,0,'0',1,1,302),(305,'BAIDU_MAP_APPKEY','',1,0,'',1,1,265),(306,'CREDIT_REPORT','0',4,1,'0,1',1,1,306),(307,'HOUSING_CERTIFICATE','0',4,1,'0,1',1,1,307),(308,'IS_STOCK_TRANSFER','1',6,1,'0,1',1,1,0),(309,'STOCK_TRANSFER_IS_VERIFY','1',6,1,'0,1',1,1,0),(310,'STOCK_TRANSFER_COMMISION','0.1',6,0,'',1,1,0),(311,'IS_SMS_DIRECT','0',5,1,'0,1',1,1,0);
/*!40000 ALTER TABLE `fanwe_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_contract`
--

DROP TABLE IF EXISTS `fanwe_contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL COMMENT '是否有效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='//合同模板';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_contract`
--

LOCK TABLES `fanwe_contract` WRITE;
/*!40000 ALTER TABLE `fanwe_contract` DISABLE KEYS */;
INSERT INTO `fanwe_contract` VALUES (1,'股权认购合同','<h2 align=\"center\">投 资 协 议</h2>\r\n<div style=\"width: 98%;text-align: right;\">\r\n协议编号：<span>{$transfer.load_id}</span>\r\n</div>\r\n<br/>\r\n<div> \r\n　　　本协议由以下各方于________年____月____日在______市签署：\r\n</p>\r\n</div>\r\n<br/>\r\n<div> \r\n<p style=\"text-align: left;font-weight: 600;\">甲方：______________________公司</p>\r\n<p >营业执照注册号：__________________</p>\r\n<p>法定代表：________________________</p>\r\n<p>注册地址： _______________________</p>\r\n<p>公司控股股东：</p>\r\n<p>姓名：_________</p>\r\n<p>身份证号：________________________</p>\r\n</div>\r\n <br/>\r\n<div> \r\n<p style=\"text-align: left;font-weight: 600;\">乙方： </p>\r\n<p>姓名：__________</p>\r\n<p>身份证号：_______________________</p>\r\n<p>或机构：_________________________</p>\r\n<p>营业执照注册号：_________________</p>\r\n<p>法定代表：_________________ </p>\r\n<p>注册地址：_________________________</p>\r\n</div>\r\n <br/>\r\n\r\n<div> \r\n<p style=\"text-align: left;font-weight: 600;\">丙方：____________________</p>\r\n<p>营业执照注册号：_____________________</p>\r\n<p>法定代表：__________________ </p>\r\n<p>注册地址：_______________________ </p>\r\n</div>\r\n<br/>\r\n<p style=\"text-align: left;font-weight: 600;\">释义</p>\r\n<p>除非本协议文意另有所指，下列词语具有以下含义： </p>\r\n<table border=\"1\" style=\"margin: 0px auto; border-collapse: collapse; border: 1px solid rgb(0, 0, 0); width: 70%; \">\r\n<tr>\r\n  <td width=\"20%\" style=\"padding-left:10px\">本次交易</td>\r\n <td style=\"padding-left:10px\">指乙方认购甲方（目标公司）增资的行为</td>\r\n</tr>\r\n<tr>\r\n  <td style=\"padding-left:10px\">元</td>\r\n  <td style=\"padding-left:10px\">指中华人民共和国法定货币人民币元</td>\r\n</tr>\r\n<tr>\r\n  <td style=\"padding-left:10px\">尽职调查</td>\r\n <td style=\"padding-left:10px\">\r\n    指基于本次交易之目的，由丙方委派保荐商或专业领投人士对目标公司在财务、法律等相关方面进行的调查                                                                  \r\n </td>\r\n</tr>\r\n<tr>\r\n  <td style=\"padding-left:10px\">投资完成</td>\r\n  <td style=\"padding-left:10px\">即增资完成，指乙方按照本协议的约定缴纳完毕认购的全部增资款或决定不继续投资</td>\r\n</tr>\r\n<tr>\r\n  <td style=\"padding-left:10px\">送达</td>\r\n  <td style=\"padding-left:10px\">\r\n	指本协议任一方按照本协议约定的任一种送达方式将公告、通知等文件发出的行为\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td style=\"padding-left:10px\">过渡期</td>\r\n  <td style=\"padding-left:10px\">\r\n  指本协议签署之日至乙方按照本协议约定的期限投资完成之日的期间\r\n  </td>\r\n</tr>\r\n</table>\r\n\r\n\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">1.鉴于：</p>\r\n<p>1.1 甲方________是一家依中华人民共和国法律在_____注册成立并合法存续的有限责任公司，注册号为____________。营业期限为自______年____月____日起至_____年    ____月_____日止。登记注册资本为人民币_____万元，实收资本为人民币_____万元，主要从事_____________________等业务。</p>\r\n<p>1.2 乙方____________________________系甲方即将登记股东，均系具有完全民事权利能力及民事行为能力人，能够独立承担民事责任。</p>\r\n<p>1.3 丙方成功运营{$SITE_TITLE}众筹网，能够为甲、乙双方提供完备的服务支持，并能够在授权范围内对双方的投资或经营行为进行监督。</p>\r\n<p>1.4甲方的项目基础包括：</p>\r\n<p>1.5 甲方转让其<span style=\"border-bottom: 1px solid #000;\">  {$user_stock}  </span>%的股份，融资<span style=\"border-bottom: 1px solid #000;\">  {$total_price}  </span>元，乙方按照本协议规定的条款和条件认购。</p>\r\n<p>1.6 乙方及丙方一致同意甲方新增注册资本及资本公积金共人民币_______万元，由投资方（乙方）按照本协议规定的条款和条件认购。丙方放弃认购本次增资。</p>\r\n<p>1.7乙方及丙方一致同意甲方将公司合法拥有的资产________评估价格______万元，抵押/质押给投资方（乙方）作为本次融资的担保措施。</p>\r\n<p>上述各方根据中华人民共和国有关法律法规的规定，经过友好协商，达成一致意见，特订立本协议如下条款，以供各方共同遵守。</p>\r\n<br />\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">2.股份认购的前提条件</p>\r\n<p>2.1  各方确认，乙方在本协议项下的投资义务以以下全部条件的满足为前提：</p>\r\n<p>（1）各方同意并正式签署本协议，包括所有附件内容。</p>\r\n<p>（2） 甲方按照本协议的相关条款修改章程并经所有股东正式签署，修改和签署业经乙方以书面形式认可；除上述目标公司章程修订之外，过渡期内，不得修订或重述目标公司章程。</p>\r\n<p>（3） 本次交易和担保措施需经过政府部门(如需)、甲方董事会的同意和批准，包括但不限于甲方董事会、股东会决议通过本协议项下的增资和担保事宜，及前述修改后的章程或章程修正案等。</p>\r\n<p>（4）甲方以书面形式向乙方和丙方充分、真实、完整披露甲方的资产、负债、权益、对外担保以及与本协议有关的全部信息。</p>\r\n<p>（5）甲方在变更时所产生的全部费用，工商变更费用，经甲、乙双方同意由甲方承担。</p>\r\n<p>（6）甲方在工商变更后，再转汇除定金之外的投资款，定金部分的结转由甲方与丙方另外按照约定办理。</p>\r\n<p>（7）甲方在股权变更之后，应在_____年____月之前完成在股权交易机构的挂牌，并按照股东占股比例，分别为乙方、丙方（另以协议约定）进行确权，以方便股东的转让、退出。</p>\r\n<p>（8）乙方具有自由转让、退出的权利，甲方应积极协助。</p>\r\n<p>2.2  若本协议2.1条的任何条件在_____年____月____日前因任何原因未能实现，则乙方有权以书面通知的形式单方解除本协议。</p><br/>\r\n<p style=\"text-align: left;font-weight: 600;\">3.  违约</p>\r\n<br />\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">3. 股份的认购</p>\r\n<p>3.1 甲方原有注册资本为人民币____万元，现各方同意，由乙方作为甲方的投资者，出资人民币_____万元，按照本协议的条款认购，认购所占比例为甲方股份的____%，甲方占股_____%，融资方式为一次性出资认购。</p>\r\n<p>3.2 股份认购前，甲、乙方股本结构如下图所示：</p>\r\n<table border=\"1\" style=\"margin: 0px auto; border-collapse: collapse; border: 1px solid rgb(0, 0, 0); width: 70%; \">\r\n  <tr>\r\n    <td style=\"padding-left:10px\">序号</td>\r\n    <td style=\"padding-left:10px\">股东</td>\r\n    <td style=\"padding-left:10px\">出资金额（元）</td>\r\n    <td style=\"padding-left:10px\">持股比例（%）</td>\r\n    <td style=\"padding-left:10px\">备注</td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">1</td>\r\n    <td style=\"padding-left:10px\">甲</td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">2</td>\r\n    <td style=\"padding-left:10px\">乙</td>\r\n    <td style=\"padding-left:10px\"></td>\r\n    <td style=\"padding-left:10px\"></td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">3</td>\r\n    <td style=\"padding-left:10px\">其他</td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n  </tr>\r\n</table>\r\n<p>3.3 乙方缴纳认投定金、签订本投资协议的30天之内，甲方完成修订公司章程、完成工商管理部门的股权变更登记；并在乙方汇出除定金之外的投资款之后的一周内，给乙方出具出资证明。</p>\r\n<p>3.4 乙方认购后，乙方须选择一次性出资方式。丙方确认甲方已经完成工商股权变更登记之后，通知乙方转出除定金之外的投资款；乙方应该在接到丙方通知的一周内一次性把除定金之外的认购款项汇至协议约定的甲方帐户。定金的转付和结算按照甲方与丙方事先的《融资承诺清单》等有关约定办理。</p>\r\n<p>3.5 在完成项目融资后，甲、乙方占股结构如下表所示：</p>\r\n<table border=\"1\" style=\"margin: 0px auto; border-collapse: collapse; border: 1px solid rgb(0, 0, 0); width: 70%; \">\r\n  <tr>\r\n    <td style=\"padding-left:10px\">序号</td>\r\n    <td style=\"padding-left:10px\">股东</td>\r\n    <td style=\"padding-left:10px\">出资金额（元）</td>\r\n    <td style=\"padding-left:10px\">持股比例（%）</td>\r\n    <td style=\"padding-left:10px\">备注</td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">1</td>\r\n    <td style=\"padding-left:10px\">甲</td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\">所占本项目投资比例</td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">2</td>\r\n    <td style=\"padding-left:10px\">乙</td>\r\n    <td style=\"padding-left:10px\">{$total_price}</td>\r\n    <td style=\"padding-left:10px\">{$user_stock}</td>\r\n    <td style=\"padding-left:10px\">所占本项目投资比例</td>\r\n  </tr>\r\n  <tr>\r\n    <td style=\"padding-left:10px\">3</td>\r\n    <td style=\"padding-left:10px\">其他</td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n    <td style=\"padding-left:10px\"> </td>\r\n  </tr>\r\n</table>\r\n<p>乙方缴纳完除定金之外的投资款项后，视为增资完成。</p>\r\n<p>丙方与甲方的赠股按照事先的《融资承诺清单》等有关约定履行。</p>\r\n<p>3.6 各方同意，在本协议股份认购的前提条件全部满足后，甲方应按照本协议的约定向乙方提供董事会决议、股东会决议、修改后的公司章程或章程修正案等文件正本并获得乙方的书面认可。</p>\r\n<p>3.7 各方同意，本协议约定的甲方账户是指以下账户</p>\r\n<p>户名：________________</p>\r\n<p>账号：______________________</p>\r\n<p>开户行名称：____________________________</p>\r\n<p>3.8 乙方成为甲方股东后，依照相应的法律法规、本协议和甲方公司章程修订案的规定享有公司的股东权利并承担相应股东义务，包括但不限于利润收入分配权。</p>\r\n<p>3.9 若乙方不能在上述约定时间内将其认缴的资金汇入甲方帐户，乙方应承担相应责任。</p>\r\n<p>3.10 双方同意，乙方对甲方的全部出资仅用于甲方正常经营需求或经股东会议以特殊决议批准的其它用途，不得用于偿还甲方或者股东债务等其它用途，也不得用于非经营性支出或者与甲方主营业务不相关的其它经营性支出；不得用于委托理财、委托贷款和期货交易等风险性投资业务。</p>\r\n<p>3.11 甲方承诺，自甲方与{$SITE_TITLE}签订的《融资承诺清单》生效之日起_____天内，甲方禁止通过其他融资途径，包括但不限于银行贷款、风险投资、信托等方式募集资金。</p>\r\n<br/>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">4.修订章程和变更事项</p>\r\n<p>4.1 甲方按照本协议的相关条款在章程修订案里专门增加此次融资说明和乙方出资金额,并经甲方董事会正式签署。章程修订案甲方以书面形式告之乙方并经乙方认可；除上述甲方公司章程修订之外，过渡期内，不得修订或重述公司章程。</p>\r\n<p>4.2 甲方收到乙方在本协议签字同意和授权委托书以及相应的股东身份证明后，     30个工作日内到当地工商行政管理部门办理完变更甲方股权事项。</p>\r\n<p>4.3 在乙方将扣除定金的出资款支付至本协议约定的甲方帐户之日起的30个工作日内，由甲方向乙方股东签发并交付股东出资凭证，出资凭证中的出资额度包括乙方之前在{$SITE_TITLE}认投的定金部分，甲方应当在股东名册中将乙方登记为股东。股份制改造之前，由甲方负责乙方此投资款的验资并出具相应的验资报告</p>\r\n<br/>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">5.乙方权利和义务</p>\r\n<p>5.1反稀释</p>\r\n<p>(1)结构性反稀释条款：若甲方的任一股东或者第三方对甲方进行增资时，乙方有权优先按相应比例以同等价格同时认购相应的增资，以使其在增资后持有的甲方股权比例不低于其根据本协议持有的甲方股权比例。</p>\r\n<p>(2)降价融资的反稀释条款：若甲方以比本次交易更优惠的价格和条件进行新的融资时，甲方需采取相关措施，包括但不限于给乙方配发免费认股权、附送额外股、更低价格转让等方式，确保增资后乙方所持股权的价值不低于新投资者进入前其股权的价值。</p>\r\n<p>5.2 优先购买权</p>\r\n<p>若甲方股东拟转让其股权，在同等条件下，乙方享有优先购买权。</p>\r\n<p>5.3 共同出售权</p>\r\n<p>若甲方股东拟向除甲方外的其他股东或任何第三方转让其持有的甲方的部分或全部股权时，乙方有权就其持有甲方的股权，按照同样的价格和其它条件，与该股东按照持有甲方的股权的相应比例向该第三方共同转让。</p>\r\n<p>5.4 清算优先权</p>\r\n<p>在甲方清算、解散、合并、被收购、出售控股股权、出售全部资产时，乙方有权优先于甲方股东获得原投资金额加上已产生但尚未支付的红利。剩余资产由股东按持股比例进行分配。</p>\r\n<p>5.5 领投人监督权和行为一致权</p>\r\n<p>领投人作为甲方的股东，对项目的运营发展具有监督权，领投人作为本次增资的股东代表，在股东会里享有特别权利，其投票意见，代表本次增资股东中未出席股东会的股东们的投票意见。</p>\r\n<p>5.6 乙方所享权利仅包含收益权，不含经营权。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">6.甲方治理和财务管理</p>\r\n<p>6.1 双方同意并保证，投资完成后，甲方董事会成员应不超过____人，本次增资的投资者有权提名 <span style=\"border-bottom: 1px solid #000;\">  1  </span> 人担任甲方董事，各方应同意在本项目股东大会上投票赞成上述投资者提名的人士出任甲方董事。甲方应在公司章程修订案中对甲方经营加以说明，甲方股东会应至少每半年召开一次会议。</p>\r\n<p>6.2 乙方享有作为股东所享有的对甲方经营管理的知情权和进行监督的权利，甲方应按时提供给乙方以下资料和信息：</p>\r\n<p>（1）每日历季度最后一日起<span style=\"border-bottom: 1px solid #000;\">  30个工作日  </span> 内，提供甲方的月度合并管理账，含利润表、资产负债表和现金流量表；</p>\r\n<p>（2）每日历年度结束后<span style=\"border-bottom: 1px solid #000;\">  45个工作日  </span> 内，提供甲方的年度合并管理账；</p>\r\n<p>（3）每日历年度结束后<span style=\"border-bottom: 1px solid #000;\">  120个工作日  </span> 内，提供甲方的年度合并审计账；</p>\r\n<p>（4）在每日历/财务年度结束前至少<span style=\"border-bottom: 1px solid #000;\">  30  </span>  天，提供甲方的年度业务计划、年度预算和预测的财务报表；</p>\r\n<p>（5）在乙方收到管理账后的<span style=\"border-bottom: 1px solid #000;\">  30  </span>  天内，提供机会供乙方与甲方就管理帐进行讨论及审核；</p>\r\n<p>（6）按照乙方要求的格式提供其它统计数据、其它财务和交易信息，以便乙方被适当告知甲方的信息以保护自身利益。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">7. 经营目标</p>\r\n<p>7.1 甲方对经营管理负主要责任，对其经营过程中人为损失负主要经济责任。</p>\r\n<p>7.2 甲方对经营目标应在每年总结中提出下一年的目标发展计划及方案，根据每年评估做出项目发展目标，并以各种形式告知乙方且须经50%以上股东同意方可执行。</p>\r\n<p>7.3 甲方承诺在_____年内，在股权交易机构_______挂牌，登陆资本市场，方便投资者推出和投后管理。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">8. 竞业禁止</p>\r\n<p>8.1 未经乙方及50%以上股东同意，甲方股东不得单独设立或以任何形式(包括但不限于以股东、合伙人、理事、监事、经理、职员、代理人、顾问等身份)参与设立和经营有竞争性的经营主体。</p>\r\n<p>8.2 甲方股东同意，如果甲方股东及主要管理人员和技术人员违反竞业禁止条款，致使乙方利益受到损害的，除该等人员须赔偿乙方损失外，甲方股东应就乙方遭受的损失承担连带赔偿责任。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">9.知识产权的占有与使用</p>\r\n<p>甲方承诺并保证，除本协议另有规定之外，本协议签订之时及本协议签订之后，甲方是现拥有的公司名称、品牌、商标和专利、商品名称及品牌、网站名称、域名、专有技术、各种经营许可证等相关知识产权、许可权的唯一、合法的所有权人。上述知识产权均需经过相关主管部门的批准或备案，且所有为保护该等知识产权而采取的合法措施均经过政府部门批准或备案，甲方保证按时缴纳相关费用，保证其权利的持续有效性。</p>\r\n<br>\r\n\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">10. 债务和或有债务</p>\r\n<p>10.1 债务，是指甲方资产负债表中已经列明的或经甲、乙双方作账外负债确认的负债。甲方及甲方股东承诺并保证，除已向乙方披露的债务之外，甲方不存在任何其他债务。如甲方还存在未披露债务，全部由甲方股东承担。若甲方未先行承担并清偿了上述未披露债务，因此给乙方造成的损失应由甲方股东在损失实际发生后<span style=\"border-bottom: 1px solid #000;\">  10  </span>个工作日内向乙方全额赔偿。 </p>\r\n<p>10.2 或有债务，是指由于甲方资产负债表日期之前的原因（事件、情况、行为、协议、合同等），在资产负债表日期之后使甲方遭受的负债，而该等负债未列明于上述资产负债表之中，也未经甲、乙双方作账外负债确认的；或该等负债虽在上述资产负债表中列明，但负债的数额大于上述资产负债表中列明的数额。</p>\r\n<p>10.3 若甲方遭受或有债务，则甲方股东应按如下约定向乙方履行赔偿责任：</p>\r\n<p>（1）乙方按照本协议相关约定增资完成前，甲方遭受或有债务的，乙方有权终止本协议或在继续融资前要求甲方股东先行支付赔偿款；</p>\r\n<p>（2）乙方按照本协议相关约定增资完成后，甲方遭受或有债务的，乙方应当书面通知甲方股东，若甲方股东以甲方名义行使抗辩权，乙方应促使甲方给予必要的协助。无论甲方股东是否行使抗辩权或抗辩的结果如何，只要甲方遭受或有债务，甲方股东均应按本协议的约定履行赔偿责任；</p>\r\n<p>（3）甲方股东因甲方遭受或有债务对乙方的赔偿责任的金额，按甲方遭受的或有债务金额乘本协议项下乙方融资后占股权的比例计得。甲方股东对乙方因甲方遭受或有债务的赔偿金额不超过乙方在本协议项下的投资额；</p>\r\n<p>（4）甲方股东应于甲方支付或有债务之日起<span style=\"border-bottom: 1px solid #000;\">  10个工作日  </span>内向乙方履行赔偿责任；</p>\r\n<p>（5）甲方股东对甲方遭受或有债务的保证赔偿期限为自上述甲方资产负债表日期起<span style=\"border-bottom: 1px solid #000;\">  26  </span> 个月；因甲方偷、逃、漏税款、对外提供担保及不受诉讼时效限制的其他或有债务的保证赔偿期限为自上述甲方资产负债表日期起<span style=\"border-bottom: 1px solid #000;\">  10  </span> 年。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">11. 共同保证和承诺</p>\r\n<p>11.1 其为依据中国法律正式成立并有效存续的自然人、法人或其他组织。</p>\r\n<p>11.2 其拥有签订和履行本协议所必须的民事权利能力和行为能力，能够独立承担民事责任。</p>\r\n<p>11.3 其保证其就本协议的签署所提供的一切文件资料均是真实、合法、有效、完整的。本协议的签订或履行不违反以其为一方或约束其自身或其有关资产的任何重大合同或协议。</p>\r\n<p>11.4 其在本协议上签字的代表，根据有效的委托书或有效的法定代表人证明书，已获得签订本协议的充分授权。</p>\r\n<p>11.5 其已就与本次交易有关的，并需为各方所了解和掌握的所有信息和资料，向相关方进行了充分、详尽、及时的披露，没有重大遗漏、误导和虚构。</p>\r\n<p>11.6 鉴于股东多、分布区域广，若召开股东会，甲方将提前半个月发出通知，乙方、丙方联系方式若出现变化应及时通知甲方。为不影响公司重大事项的进展，特共同约定：若因个人原因未能亲自或委托他人如期出席股东会，则视为同意股东会决议，并视为同意委托公司人员全权代为办理相关手续，包括再融资、挂牌的工商变更和公司章程的修订。并以本协议约定为准，无须再出具委托书。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">12. 风险揭示 </p>\r\n<p>乙方投资甲方可能面临如下风险，甲方和甲方股东不承诺任何回报：</p>\r\n<p>12.1 政策风险——指国家未来股权众筹融资行业法律、法规、政策发生重大变化或进行行业整改等举措，将改变现有的行业现状，项目原定目标难以实现甚至无法实现所产生的风险。</p>\r\n<p>12.2 市场风险——主要指由于市场变化或经济环境造成甲方营业收入减少，经营效益下降而导致还款能力不足的风险，甚至亏损。 </p>\r\n<p>12.3 信用风险和流动性风险——指社会诚信度，资金流动性等风险。</p>\r\n<p>12.4 其他风险——战争、自然灾害等不可抗力风险；金融市场危机等超出甲方自身直接控制能力之外的风险等。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">13. 违约及其责任 </p>\r\n<p>13.1 本协议生效后，各方应按照本协议及全部附件、附表的规定全面、适当、及时地履行其义务及约定，若本协议的任何一方违反本协议包括全部附件、附表的约定，均构成违约。</p>\r\n<p>13.2 各方同意，除本协议另有约定，本协议违约金为乙方认购的增资款项全额的 10％。</p>\r\n<p>13.3 支付违约金不影响守约方要求违约方赔偿损失、继续履行协议或解除协议的权利。</p>\r\n<p>13.4 一旦发生违约行为，违约方应当向守约方支付违约金，并赔偿因其违约而给守约方造成的损失以及守约方为追偿损失而支付的合理费用，包括但不限于律师费、财产保全费等。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">14. 协议的变更、解除和终止 </p>\r\n<p>14.1 本协议的任何修改、变更应经协议各方另行协商，并就修改、变更事项共同签署协议后方可生效。</p>\r\n<p>（1）经各方当事人协商一致解除。</p>\r\n<p>（2）任一方发生违约行为并在守约方向其发出要求更正的书面通知之日起30 天内不予更正的，或累计发生两次或两次以上违约行为的，守约方有权单方解除本协议。</p>\r\n<p>（3）因不可抗力，造成本协议无法履行的。</p>\r\n<p>14.3 提出解除协议的一方应当通知其他各方，通知在到达其他各方时生效。</p>\r\n<p>14.4 本协议解除后，不影响守约方要求违约方支付违约金和赔偿损失的权利。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">15. 附件</p>\r\n<p>15.1 乙方认可的现行有效的甲方公司章程及修订案。</p>\r\n<p>15.2 股东会关于公司增资的股东会决议。</p>\r\n<p>15.3 甲方及其本次项目发起人________关于本次股权众筹计划书和回报方案说明。</p>\r\n<p>15.4 甲方给乙方的股东出资凭证。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">16. 通知及送达</p>\r\n<p>16.1 协议各方同意，与本协议有关的任何通知均应采用书面方式,可采用{$SITE_TITLE}网站平台公告、当面递交、传真、特快专递或挂号信件、电子邮件等形式。公告形式的通知以{$SITE_TITLE}有关网页上发布之日为送达日；当面递交、传真的通知以当日为送达日；以特快专递、挂号信件发出的通知以签收日或通知发出后第三日为送达日；以电子邮件发出的通知进入对方电子数据接收系统之日视为送达日。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">17. 法律适用与争议解决</p>\r\n<p>17.1 本协议的效力、解释及履行均适用中国人民共和国法律。</p>\r\n<p>17.2 本协议各方当事人因本协议发生的任何争议，均应首先通过友好协商的方式解决，如协商不成，各方同意向丙方所在地人民法院仲裁委员会申请仲裁。</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">18. 附则</p>\r\n<p>18.1 除非本协议另有规定，各方应自行支付各自产生的，与本协议及本协议述及的文件的谈判、起草、签署和执行有关的成本和费用。</p>\r\n<p>18.2 本协议未尽事宜，各方可另行签署补充文件，该补充文件与本协议具有同等法律效力。</p>\r\n<p>18.3 本协议自各方签字、盖章后成立并生效。本协议用中文书写，一式三份，各方各持一份，其余由甲方备案，各份具有同等法律效力。</p>\r\n<p>（以下无正文）</p>\r\n<br>\r\n\r\n<p style=\"text-align: left;font-weight: 600;\">甲方(盖章)：_________________</p>\r\n<p>法定代表人/授权代表(签字)：__________________                            </p>\r\n<p>签约时间：_____年____月____日</p>\r\n<p style=\"text-align: left;font-weight: 600;\">乙方(签字加盖手摸或公章)：___________________ </p>\r\n<p>签约时间：_____年____月____日</p>\r\n<p style=\"text-align: left;font-weight: 600;\">丙方(盖章)：_____________________________公司</p>\r\n<p>法定代表人/授权代表(签字)：__________________</p>\r\n<p>签约时间：_____年____月____日</p>\r\n<br>\r\n',0,1),(2,'股权转让协议样本','<h2 align=\"center\">   <span style=\"border-bottom: 1px solid #000;\">   {$deal.company}   </span>公司股权转让协议</h2> \r\n<p></p>\r\n\r\n<p>甲乙双方根据《中华人民共和国公司法》等法律、法规和 <span style=\"border-bottom: 1px solid #000;\">  {$deal.company}  </span>公司（以下简称该公司）章程的规定，经友好协商，本着平等互利、诚实信用的原则，签订本股权转让协议，以资双方共同遵守。</p>\r\n<p></p>\r\n甲方（转让方）：<span style=\"border-bottom: 1px solid #000;\">  {$transfer.user_name}  </span>           乙方（受让方）：<span style=\"border-bottom: 1px solid #000;\">  {$transfer.purchaser_name}  </span>             \r\n<p>身份证号码： <span style=\"border-bottom: 1px solid #000;\">  {$seller_info.identify_number}  </span>                         身份证号码：<span style=\"border-bottom: 1px solid #000;\">  {$purchaser_info.identify_number}  </span>   </p>\r\n<p>联系电话：  <span style=\"border-bottom: 1px solid #000;\">  {$seller_info.mobile}  </span>                          联系电话：<span style=\"border-bottom: 1px solid #000;\">  {$purchaser_info.mobile}  </span>    </p>\r\n<p></p>\r\n<p></p>\r\n<p>第一条  股权的转让</p>\r\n<p>1、 甲方将其持有该公司<span style=\"border-bottom: 1px solid #000;\">  {$transfer.user_stock}  </span>%的股权转让给乙方。 　</p>\r\n<p>2、 乙方同意接受上述转让的股权。 　</p>\r\n<p>3、 甲乙双方确定的转让价格为人民币  <span style=\"border-bottom: 1px solid #000;\">  {$transfer.price}  </span>  元。 　</p>\r\n<p>4、 甲方保证向乙方转让的股权不存在第三人的请求权，没有设置任何质押，未涉及任何争议及诉讼。 </p>\r\n<p>5、 甲方向乙方转让的股权中尚未实际缴纳出资的部分，转让后，由乙方继续履行这部分股权的出资义务。</p>\r\n<p>（注：若本次转让的股权系已缴纳出资的部分，则删去第5款）</p>\r\n<p>6、 本次股权转让完成后，乙方即享受相应的股东权利并承担义务。甲方不再享受相应的股东权利和承担义务。</p>\r\n<p>7、 甲方应对该公司及乙方办理相关审批、变更登记等法律手续提供必要协作与配合。</p>\r\n<p>8、本次股权转让所产生的相关费用经甲乙双方协商由_________支付。　</p>\r\n<p></p>\r\n<p></p>\r\n<p>第二条  转让款的支付 　</p>\r\n_____________________________________________________________________\r\n_____________________________________________________________________\r\n_____________________________________________________________________\r\n_____________________________________________________________________\r\n_____________________________________________________________________\r\n_____________________________________________________________________\r\n<p>　 （注：转让款的支付时间、支付方式由转让双方自行约定并载明于此）</p>\r\n<p></p>\r\n<p></p>\r\n<p>第三条  违约责任</p>\r\n<p>1、 本协议正式签订后，任何一方不履行或不完全履行本协议约定条款的，即构成违约。违约方应当负责赔偿其违约行为给守约方造成的损失。 　</p>\r\n<p>2、 任何一方违约时，守约方有权要求违约方继续履行本协议。 　</p>\r\n<p></p>\r\n<p></p>\r\n<p>第四条  适用法律及争议解决 　</p>\r\n<p>1、 本协议适用中华人民共和国的法律。 　</p>\r\n<p>2、 凡因履行本协议所发生的或与本协议有关的一切争议双方应当通过友好协商解决；如协商不成，则通过诉讼解决。 　</p>\r\n<p></p>\r\n<p></p>\r\n<p>第五条  协议的生效及其他 　</p>\r\n<p>1、本协议经双方签字盖章后生效。</p>\r\n<p>2、本协议生效之日即为股权转让之日，该公司据此更改股东名册、换发出资证明书，并向登记机关申请相关变更登记。</p>\r\n<p>3、本合同一式四份，甲乙双方各持一份，该公司存档一份，申请变更登记一份。 </p>\r\n<p></p>\r\n甲方（签字或盖章）:                         乙方（签字或盖章）： 　　　　 　\r\n<p></p>\r\n<p></p>\r\n<p></p>\r\n<p></p>\r\n<p></p>\r\n签订日期：_____年____月____日        签订日期：_____年____月____日\r\n',0,1);
/*!40000 ALTER TABLE `fanwe_contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal`
--

DROP TABLE IF EXISTS `fanwe_deal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_match` text NOT NULL,
  `name_match_row` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `source_vedio` varchar(255) NOT NULL,
  `vedio` varchar(255) NOT NULL,
  `deal_days` int(11) NOT NULL COMMENT '上线天数，仅提供管理员审核参考',
  `begin_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `limit_price` decimal(20,2) NOT NULL COMMENT '项目金额',
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `comment_count` int(11) NOT NULL,
  `support_count` int(11) NOT NULL COMMENT '支持人数',
  `focus_count` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `log_count` int(11) NOT NULL,
  `support_amount` decimal(20,2) NOT NULL COMMENT '支持总金额，需大等于limit_price(不含运费)',
  `pay_amount` decimal(20,2) NOT NULL COMMENT '可发放金额，抽完佣金的可领金额（含运费，运费不抽佣金）\r\n即support_amount*佣金比率+delivery_fee_amount',
  `delivery_fee_amount` decimal(20,2) NOT NULL COMMENT '交付费用金额',
  `create_time` int(11) NOT NULL,
  `seo_title` text NOT NULL,
  `seo_keyword` text NOT NULL,
  `seo_description` text NOT NULL,
  `tags` text NOT NULL,
  `tags_match` text NOT NULL,
  `tags_match_row` text NOT NULL,
  `success_time` int(11) NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `is_recommend` tinyint(1) NOT NULL COMMENT '推荐项目',
  `is_classic` tinyint(1) NOT NULL COMMENT '经典项目',
  `is_delete` tinyint(1) NOT NULL,
  `deal_extra_cache` text NOT NULL,
  `is_edit` tinyint(1) NOT NULL,
  `description_1` text NOT NULL,
  `is_support_print` int(11) NOT NULL,
  `user_level` int(11) NOT NULL,
  `is_has_send_success` tinyint(1) NOT NULL,
  `pay_radio` decimal(20,2) NOT NULL,
  `adv_image` varchar(255) NOT NULL COMMENT '广告图片',
  `status` tinyint(1) NOT NULL,
  `deal_background_image` varchar(255) NOT NULL COMMENT '项目背景图片',
  `deal_backgroundColor_image` varchar(255) NOT NULL COMMENT '项目底色图片',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示普通众筹，1表示股权众筹,2表示众筹买房,3表示公益众筹, 4表示融资众筹',
  `description_2` text NOT NULL COMMENT '目标用户或客户群体定位',
  `description_3` text NOT NULL COMMENT '目标用户或客户群体目前困扰或需求定位',
  `description_4` text NOT NULL COMMENT '满足目标用户或客户需求的产品或服务模式说明',
  `description_5` text NOT NULL COMMENT '项目赢利模式说明',
  `description_6` text NOT NULL COMMENT '市场主要同行或竞争对手概述',
  `description_7` text NOT NULL COMMENT '项目主要核心竞争力说明',
  `stock` text NOT NULL COMMENT '股东信息',
  `unstock` text NOT NULL COMMENT '非股东成员',
  `history` text NOT NULL COMMENT '三年信息',
  `plan` text NOT NULL COMMENT '三年计划',
  `attach` text NOT NULL COMMENT '附件信息',
  `investor_authority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0表示只有投资人可以，1表所有人都可以看',
  `project_step` tinyint(1) NOT NULL COMMENT '项目阶段 0表示未启动 1表示在开发中 2产品已上市或上线，3已经有收入，4已经盈利',
  `business_create_time` int(11) NOT NULL COMMENT '企业成立时间',
  `business_employee_num` int(11) NOT NULL COMMENT '企业员工数量',
  `business_is_exist` tinyint(1) NOT NULL COMMENT '公司是否成立',
  `has_another_project` tinyint(1) NOT NULL COMMENT '是否有其他项目 0表示么有  1表示有',
  `business_name` varchar(255) NOT NULL COMMENT '公司名称',
  `business_address` varchar(255) NOT NULL COMMENT '办公地址',
  `business_stock_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '入股方式  0直接入股原公司 1 创建新公司入股',
  `business_pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '付款方式 0表示一次性付款',
  `business_descripe` text NOT NULL COMMENT '企业简介',
  `pay_end_time` int(11) NOT NULL COMMENT '支付结算时间',
  `investor_edit` int(1) NOT NULL COMMENT '0表示显示下一步按钮，1表示显示不错和返回按钮',
  `mine_stock` decimal(10,2) DEFAULT NULL COMMENT '投资人占有的股份',
  `transfer_share` decimal(10,2) NOT NULL,
  `virtual_num` int(11) NOT NULL COMMENT '虚拟人数',
  `virtual_price` decimal(20,2) NOT NULL COMMENT '虚拟金额',
  `gen_num` int(11) NOT NULL COMMENT '跟投人数',
  `xun_num` int(11) NOT NULL COMMENT '询价人数',
  `invote_money` decimal(20,2) NOT NULL COMMENT '预购金额',
  `invote_num` int(11) NOT NULL COMMENT '投资人数',
  `invote_mini_money` decimal(10,2) NOT NULL COMMENT '最低支付金额',
  `refuse_reason` text COMMENT '拒绝理由',
  `audit_data` text NOT NULL COMMENT '发起人资料',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热门',
  `ips_bill_no` varchar(255) NOT NULL COMMENT '托管项目',
  `share_fee_amount` decimal(20,2) NOT NULL COMMENT '分红总金额',
  `is_special` tinyint(1) NOT NULL COMMENT '专题',
  `invest_status` tinyint(1) NOT NULL COMMENT '融资状态：0未确认，1成功，2失败',
  `left_money` decimal(20,2) NOT NULL COMMENT '剩余筹款',
  `lottery_draw_time` int(11) NOT NULL COMMENT '开奖时间：0未开奖，大于0已开奖',
  `share_fee_descripe` text COMMENT '分红说明',
  `exit_mechanism` text COMMENT '退出机制',
  `annual_interest_rate` decimal(10,2) NOT NULL COMMENT '预期年利率',
  `return_cycle` varchar(255) NOT NULL COMMENT '固定回报周期',
  `return_descripe` text COMMENT '回报说明',
  `stock_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1表示股权众筹，2表示债权众筹，3表示股权+债权（这三种类型都属于股权众筹）',
  `invest_phase` tinyint(2) NOT NULL COMMENT '投资阶段 0表示天使轮,1表示Pre-A轮，2表示A轮，3表示A+轮，4表示B轮，5表示B+轮，6表示C轮，7表示D轮，8表示E轮及以后，9表示并购，10表示上市',
  `company_id` int(11) NOT NULL COMMENT '公司id',
  `stock_subscript_id` int(11) NOT NULL COMMENT '股权认购合同ID',
  `stock_transfer_id` int(11) NOT NULL COMMENT '股权转让合同ID',
  `houses_info` text COMMENT '楼般信息',
  `houses_name` varchar(255) NOT NULL COMMENT '楼盘名称',
  `houses_address` varchar(255) NOT NULL COMMENT '楼盘地址',
  `api_address` varchar(255) NOT NULL COMMENT '地图定位地址',
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL COMMENT '纬度',
  `houses_status` varchar(255) NOT NULL COMMENT '楼盘阶段',
  `earnings` decimal(20,2) NOT NULL COMMENT '收益百分比',
  `earnings_cycle` int(11) NOT NULL COMMENT '收益周期',
  `earnings_send_count` int(11) NOT NULL COMMENT '收益期数',
  `earnings_send_capital` tinyint(1) NOT NULL COMMENT '收益发放含本金：0不是，1是',
  `houses_earnings_info` text NOT NULL COMMENT '房产收益说明',
  `update_log_icon` varchar(255) NOT NULL COMMENT '动态展示小图标',
  `is_earnings` tinyint(1) NOT NULL DEFAULT '1' COMMENT '项目是否有收益：0无收益，1有收益',
  `is_top` tinyint(1) NOT NULL COMMENT '置顶项目',
  PRIMARY KEY (`id`),
  KEY `begin_time` (`begin_time`),
  KEY `end_time` (`end_time`),
  KEY `is_effect` (`is_effect`),
  KEY `limit_price` (`limit_price`),
  KEY `comment_count` (`comment_count`),
  KEY `support_count` (`support_count`),
  KEY `focus_count` (`focus_count`),
  KEY `view_count` (`view_count`),
  KEY `log_count` (`log_count`),
  KEY `support_amount` (`support_amount`),
  KEY `create_time` (`create_time`),
  KEY `is_success` (`is_success`),
  KEY `cate_id` (`cate_id`),
  KEY `sort` (`sort`),
  KEY `is_recommend` (`is_recommend`),
  KEY `is_classic` (`is_classic`),
  KEY `is_delete` (`is_delete`),
  FULLTEXT KEY `tags_match` (`tags_match`),
  FULLTEXT KEY `name_match` (`name_match`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COMMENT='//项目信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal`
--

LOCK TABLES `fanwe_deal` WRITE;
/*!40000 ALTER TABLE `fanwe_deal` DISABLE KEYS */;
INSERT INTO `fanwe_deal` VALUES (55,'原创DIY桌面游戏《功夫》《黄金密码》期待您的支持','ux21151ux22827,ux26700ux38754,ux26399ux24453,ux23494ux30721,ux40644ux37329,ux25903ux25345,ux21407ux21019,ux28216ux25103,ux68ux73ux89,ux21407ux21019ux68ux73ux89ux26700ux38754ux28216ux25103ux12298ux21151ux22827ux12299ux12298ux40644ux37329ux23494ux30721ux12299ux26399ux24453ux24744ux30340ux25903ux25345,ux21407ux21019ux68ux73ux89ux26700ux38754ux28216ux25103ux12298ux21151ux22827ux12299ux12298ux40644ux37329ux23494ux30721ux12299ux26399ux24453ux24744ux30340ux25903ux25345,ux21407ux21019ux68ux73ux89ux26700ux38754ux28216ux25103ux12298ux21151ux22827ux12299ux12298ux40644ux37329ux23494ux30721ux12299ux26399ux24453ux24744ux30340ux25903ux25345,ux21407ux21019ux68ux73ux89ux26700ux38754ux28216ux25103ux12298ux21151ux22827ux12299ux12298ux40644ux37329ux23494ux30721ux12299ux26399ux24453ux24744ux30340ux25903ux25345','功夫,桌面,期待,密码,黄金,支持,原创,游戏,DIY,原创DIY桌面游戏《功夫》《黄金密码》期待您的支持,原创DIY桌面游戏《功夫》《黄金密码》期待您的支持,原创DIY桌面游戏《功夫》《黄金密码》期待您的支持,原创DIY桌面游戏《功夫》《黄金密码》期待您的支持','./public/attachment/201211/07/10/021e2f6812298468cfab78cbd07b90ee85.jpg','','',15,1351710606,1623525000,1,'3000.00','这次给大家带来的是我们自己原创的两个桌面游戏《功夫》和《黄金密码》，由于我们并非专业的桌游制作公司，希望大家能够喜欢并支持我们！','这次给大家带来的是我们自己原创的两个桌面游戏《功夫》和《黄金密码》，由于我们并非专业的桌游制作公司，所以在游戏的美术、包装、宣传等方面都会存在一些不足。但本次带来的两个作品都是我们利用几乎所有的业余时间尽心尽力制作出来的，希望大家能够喜欢并支持我们！\r\n<p>\r\n	<br />\r\n</p>\r\n<h3>\r\n	我想要做什么\r\n</h3>\r\n<p>\r\n	&nbsp; 桌面游戏是一种健康的休闲方式，你不用整天面对电脑的辐射，同时也让你可以不再过度沉迷于虚拟的网络世界中。因为桌面游戏方式的特殊性，能使你更加注重加强与人面对面的交流，提高自己的语言和沟通能力，还可以在现实生活中用这种轻松愉快的休闲方式结交更多的朋友。\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;我们就是这样一群喜爱桌游，同时喜欢设计桌游的年轻人，我们并非专业的桌游制作团队，我们只是凭着对桌游的爱好开始了对桌游设计的探索。我们希望通过努力，将桌游的快乐带给更多喜欢轻松休闲、热爱生活的朋友。但是，我们的资金及能力有限，需要得到大家的帮助与支持，才能实现这样的梦想。也希望您在支持我们的同时收获一份快乐和惊喜！\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;我们这次将原创的桌面游戏《功夫》和《黄金密码》一起放到这里，希望得到大家的支持！&nbsp;&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n<img src=\"./public/attachment/201211/07/16/da4f6f7e11b249dcf71bf5e9c6a86d8a83o5700.jpg\" />\r\n</p>\r\n<p>\r\n	游戏人数：2-4人\r\n</p>\r\n<p>\r\n	适合年龄：8+\r\n</p>\r\n<p>\r\n	游戏时间：10-30分钟\r\n</p>\r\n<p>\r\n	游戏类型：手牌管理\r\n</p>\r\n<p>\r\n	游戏背景：你在游戏中扮演一名武者，灵活运用你掌握的功夫（手牌）和装备（装备牌）对抗其他的武者并最终打败他们。\r\n</p>\r\n<p>\r\n	游戏目标：扣除敌方所有人物的体力为胜。\r\n</p>\r\n游戏配件：69张动作牌（手牌）、6张道具牌、2张血量牌（需自行制作）\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"./public/attachment/201211/07/16/7a404c90f81ca1368ff0f5b24e26a5d781o5700.jpg\" />\r\n</p>\r\n<p>\r\n	游戏过程：游戏的每个回合分两个阶段，第一阶段为热身阶段，获得热身阶段胜利的玩家成为第二阶段（攻击阶段）的主导者，由他决定第二阶段如何进行。\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;《功夫》用卡牌较好的模拟再现了格斗中的一些乐趣，比如热身阶段的猜招、攻击阶段一招一式的过招，同时结合手牌管理的一些特点，打出组合招式及连招，配合你获得的道具，最终战胜对手。在游戏过程中，当你取得一定的优势时，也不能掉以轻心，形式可能会因为你的任何一个破绽而发生逆转，这与格斗、搏击的情况十分相似。所以如何保持良好的心态，灵活的运用手牌才是这个游戏制胜的关键所在。（具体规则见最下方及本项目动态）\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	游戏人数：3-4人\r\n</p>\r\n<p>\r\n	适合年龄：8+\r\n</p>\r\n<p>\r\n	游戏时间：20-40分钟\r\n</p>\r\n<p>\r\n	游戏类型：逻辑推理、谜题设计\r\n</p>\r\n<p>\r\n	游戏背景：二战时期，德军将一批黄金铸成金条，分别保存在3个金库里，并派重兵把守。为了得到这批黄金，美军重金收买了一个德军守卫为内奸，内奸成功获取了金库的密码破解方法，并将密码破解方法以暗号的形式告知了美军特工。但是，与此同时德军也发现了暗号，并且金库的守卫非常森严，解开金库密码的时间只有1分钟……玩家在这个游戏中分别扮演德军、德军内奸、美军特工。如何设计出德军看不懂，美军特工又能在1分钟内解出的暗号密码。就看你的表现啦！\r\n</p>\r\n<p>\r\n	游戏目标：根据身份的不同，任务也不同。德军需解开密码保住金库，特工需设置密码阻止德军解密，美军需解开密码同时选择金库获得黄金。\r\n</p>\r\n<p>\r\n	游戏配件：10张密码牌、12张空箱牌、24张黄金牌、沙漏1个、草稿纸和笔（自备）\r\n</p>\r\n<p>\r\n	游戏过程：每人分别扮演一次特工、德军、美军，完成后计算每人所获得的黄金数量，黄金最多的玩家获胜。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>',0,1,0,3,1,'15.00','18.50','5.00',1352229030,'','','','功夫 桌面 期待 密码 黄金 支持 原创 游戏 DIY','ux21151ux22827,ux26700ux38754,ux26399ux24453,ux23494ux30721,ux40644ux37329,ux25903ux25345,ux21407ux21019,ux28216ux25103,ux68ux73ux89','功夫,桌面,期待,密码,黄金,支持,原创,游戏,DIY',0,0,8,'福建','福州',17,0,'fanwe',1,1,0,'',0,'',0,0,0,'0.00','',0,'','',0,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',0,0,'0.00',0,NULL,NULL,'0.00','',NULL,1,0,0,0,0,NULL,'','','','','','','0.00',0,0,0,'','',1,0),(56,'拥有自己的咖啡馆','ux21654ux21857ux39302,ux25317ux26377,ux33258ux24049,ux25317ux26377ux33258ux24049ux30340ux21654ux21857ux39302','咖啡馆,拥有,自己,拥有自己的咖啡馆','./public/attachment/201211/07/11/40e44eb97b0ca5aed5148e59c2cc8dcb95.jpg','','',30,1351711495,1560367440,1,'5000.00','每个人心目中都有一个属于自己的咖啡馆,我们也是.但我们想要的咖啡馆，又不仅仅是咖啡馆','<h3>\r\n	关于我\r\n</h3>\r\n<p>\r\n	每个人心目中都有一个属于自己的咖啡馆<br />\r\n我们也是<br />\r\n但我们想要的咖啡馆，又不仅仅是咖啡馆<br />\r\n这里除了售卖咖啡和甜点，还有旅行的梦想<br />\r\n我们想要一个“窝”，一个无论在出发前还是归来后随时开放的地方<br />\r\n梦想着有一天<br />\r\n我们可以带着咖啡的香气出发<br />\r\n又满载着旅行的收获回到充满咖啡香气的小“窝”\r\n</p>\r\n<h3>\r\n	我想要做什么\r\n</h3>\r\n<p>\r\n	以图文并茂的方式简洁生动地说明你的项目，让大家一目了然，这会决定是否将你的项目描述继续看下去。建议不超过300字。\r\n</p>\r\n<p>\r\n	<img src=\"./public/attachment/201211/07/16/0482ef5836f6745af0f59ff40d40805765o5700.jpg\" /><br />\r\n</p>\r\n<h3>\r\n	为什么我需要你的支持\r\n</h3>\r\n<p>\r\n	这是加分项。说说你的项目不同寻常的特色、资金用途、以及大家支持你的理由。这会让更多人能够支持你，不超过200个汉字。\r\n</p>\r\n<h3>\r\n	我的承诺与回报\r\n</h3>\r\n让大家感到你对待项目的认真程度，鞭策你将项目执行最终成功。同时向大家展示一下你为支持者准备的回报，来吸引更多人支持你。<br />\r\n<br />\r\n<img src=\"./public/attachment/201211/07/16/2ae4c7149cfd31f12d91453713322f9076o5700.jpg\" /><br />\r\n<br />\r\n<br />',0,11,1,5,1,'5500.00','4950.00','0.00',1352229954,'','','','咖啡馆 拥有 自己','ux21654ux21857ux39302,ux25317ux26377,ux33258ux24049','咖啡馆,拥有,自己',1434136682,1,1,'北京','东城区',18,0,'fzmatthew',1,1,0,'',0,'',0,0,0,'0.00','',0,'','',0,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',1,0,'4950.00',0,NULL,NULL,'0.00','',NULL,1,0,0,0,0,NULL,'','','','','','','0.00',0,0,0,'','',1,0),(57,'短片电影《Blind Love》','ux30701ux29255,ux30005ux24433,ux66ux108ux105ux110ux100,ux76ux111ux118ux101,ux30701ux29255ux30005ux24433ux12298ux66ux108ux105ux110ux100ux76ux111ux118ux101ux12299,ux30701ux29255ux30005ux24433ux12298ux66ux108ux105ux110ux100ux76ux111ux118ux101ux12299','短片,电影,Blind,Love,短片电影《Blind Love》,短片电影《Blind Love》','./public/attachment/201211/07/11/0c067c4522bba51595c324028be7070d11.jpg','http://player.youku.com/embed/XMzgyNjMzNDA4','http://v.youku.com/v_show/id_XMzgyNjMzNDA4.html',30,1349034009,1655062800,1,'3000.00','我叫武秋辰， 美国圣地亚哥大学影视专业硕士毕业。这是我在毕业后的第一部独立电影作品，讲述了一个关于盲人画家的唯美爱情故事。','<p>\r\n	我叫武秋辰， 美国圣地亚哥大学影视专业硕士毕业。这是我在毕业后的第一部独立电影作品，讲述了一个关于盲人画家的唯美爱情故事。\r\n</p>\r\n<p>\r\n	这是一个需要爱与被爱的世界，然而在我们面对这纷繁复杂多变的世界时，我们如何过滤掉那迷乱双眼的尘沙找到真爱？我们在爱中得救，在爱中迷失。我们过度相信我们用双眼所见的，却忘记听从内心最真的感受！\r\n</p>\r\n<p>\r\n	我们一路奔跑、一路追逐，生活的洪流把我们涌向未来不确定的方向，我们有着一双能望穿苍穹的眼睛，却不断的迷失在路途中。如果有一天我们的双眼失去光明……\r\n</p>\r\n<p>\r\n	真爱是否还遥远？\r\n</p>\r\n<p>\r\n	导演武秋辰将用电影语言为我们讲述一位盲人画家的爱情故事，如同她所写道的：“我们视觉正常的人很容易被表象所迷惑，而我们的触觉，听觉和嗅觉却非常的精准，给我们带来更丰富的感知。”当我们不仅凭双眼去认识这个世界的时候，也许答案就在那里！\r\n</p>\r\n<p>\r\n	为了使影片更富深入性、更具专业性，导演请来了好莱坞的职业演员，就连剧中的盲人画像也由美国著名画家OlyaLusina特为此片创作。\r\n</p>\r\n<p>\r\n	该片不仅是一个远赴美国实现梦想的中国女孩的心血之作，同时也深刻展现了一个盲人心中的世界，从“他”的角度为因爱迷失的人们找到了一个诗意的出口。\r\n</p>\r\n<p>\r\n	在这里，真诚地感谢您的关注！关注武秋辰和她的《BlindLove》！\r\n</p>\r\n<h3>\r\n	自我介绍<br />\r\n</h3>\r\n<p>\r\n	我是一个在美国学电影做电影的中国女孩。在美国圣地亚哥大学电影系求学的过程中，我学会了编剧，导演，拍摄和剪辑，参与了几十部电影的创作。“盲爱”是我在硕士毕业后自编自导的第一部独立电影作品。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"./public/attachment/201211/07/16/148cb883cbb170735c331125a96c11e162o5700.jpg\" />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"./public/attachment/201211/07/16/875016977d65ee2cc679ab0cfd7a7f6620o5700.jpg\" /><br />\r\n<br />\r\n</p>',0,0,0,4,1,'0.00','0.00','0.00',1352230821,'','','','短片 电影 Blind Love','ux30701ux29255,ux30005ux24433,ux66ux108ux105ux110ux100,ux76ux111ux118ux101','短片,电影,Blind,Love',0,0,3,'福建','福州',17,0,'fanwe',1,1,0,'',0,'',0,0,0,'0.00','',0,'','',0,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',0,0,'0.00',0,NULL,NULL,'0.00','',NULL,1,0,0,0,0,NULL,'','','','','','','0.00',0,0,0,'','',1,0),(60,'筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！','ux24247ux24179,ux32701ux27611ux29699ux39302,ux31609ux24314,ux32701ux27611ux29699,ux29233ux22909ux32773,ux28201ux26262,ux19968ux20010,ux31609ux24314ux24247ux24179ux32701ux27611ux29699ux39302ux65292ux20026ux32701ux27611ux29699ux29233ux22909ux32773ux24314ux19968ux20010ux28201ux26262ux30340ux23478ux65281','康平,羽毛球馆,筹建,羽毛球,爱好者,温暖,一个,筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！','./public/attachment/201602/29/01/56d3344294c5f.jpg','','',30,1456653461,1461837465,1,'30000.00','康平县是一个经济落后的辽北小县城，靠近内蒙边界，体育、文化设施落后，没有专业羽毛球场馆，有时羽毛球爱好者不得不远程跋涉去外地市县打球，急需专业场馆！','<div class=\"xqLeftTitleBox\" style=\"margin:10px 0px;padding:0px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	<div class=\"xqLeftTitleInner\" style=\"margin:0px;padding:0px;text-align:center;\">\r\n		<h2 style=\"font-size:18px;vertical-align:top;color:#50ABF2;\">\r\n			羽毛球场馆——康平羽毛球爱好者的梦！\r\n		</h2>\r\n	</div>\r\n</div>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	这是一个关于梦想的故事，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	这是一个让生命更健康的话题，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	这里承载着欠发达地区对一项运动的渴望，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	这里争取为羽毛球爱好者建一个温暖的家，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	远离城市喧嚣的小县城有这么一群人，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们热爱生活，喜欢运动，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们舞动球拍，让快乐羽你同行，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们冬练三九，夏练三伏，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们东奔西走，四处找场地打球，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们不惧舟车劳顿，只为在球场上挥洒汗水，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们没有专业场地，但却屡创奇迹，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们是康平县羽毛球爱好者，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	他们的梦想是有一个专业的场馆，温暖的家，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	请您为康平县羽毛球馆筹建贡献一份力量，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	我们不会忘记您，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	我们不会忘记您在羽毛球馆筹建中的付出，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	我们真诚欢迎您，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	我们真诚欢迎您在羽毛球馆落成后的到来！\r\n</p>\r\n<img src=\"http://zcr6.ncfstatic.com/attachment/201602/14/08/56bfc694737f21a_t4_369x246_thumb_670x0.jpg\" alt=\"筹建康平县羽毛球馆，为康平县羽毛球爱好者建一个温暖的家！ (公益活动,公益创业,运动,健康,羽毛球)\" title=\"筹建康平县羽毛球馆，为康平县羽毛球爱好者建一个温暖的家！ (公益活动,公益创业,运动,健康,羽毛球)\" class=\"lazy1 go\" style=\"height:246px;width:369px;\" /> \r\n<div class=\"xqLeftTitleBox\" style=\"margin:10px 0px;padding:0px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	<div class=\"xqLeftTitleInner\" style=\"margin:0px;padding:0px;text-align:center;\">\r\n		<h2 style=\"font-size:18px;vertical-align:top;color:#50ABF2;\">\r\n			羽毛球馆筹建预算\r\n		</h2>\r\n	</div>\r\n</div>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	标准羽毛球场地的尺寸为：13.4M×6.1M，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	球场边线离建筑物外墙边线至少为1M，球场一侧设置观众座椅，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	单个羽毛球场地的占地建筑面积为：16.0×10.0≈160M2。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	球馆的结构为：混凝土框架，空心砖墙填充，普通抹灰、蓝色涂料粉刷，\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	断桥铝门窗、双层真空玻璃冷合，彩钢棚顶、地面为木地板油漆，铺设专业地胶。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	主体建筑600元／M2、地板100元/M2、采暖80元/M2、水电30元/M2、灯光20元/M2、设备（家具、网等等）10元／M2、其他10元/M2。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	塑胶地板：8000元/条,\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	管理支出：6000元。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	羽毛球馆单块场地造价：\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	850元/平方米×160平方米+8000+6000≈15万元；\r\n</p>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	备注：此预算未考虑占地费用。\r\n</p>\r\n<img src=\"http://zcr6.ncfstatic.com/attachment/201602/14/08/56bfcea10715b1a_t4_497x655_thumb_670x0.jpg\" alt=\"筹建康平县羽毛球馆，为康平县羽毛球爱好者建一个温暖的家！ (公益活动,公益创业,运动,健康,羽毛球)\" title=\"筹建康平县羽毛球馆，为康平县羽毛球爱好者建一个温暖的家！ (公益活动,公益创业,运动,健康,羽毛球)\" class=\"lazy1 go\" style=\"height:655px;width:497px;\" /> \r\n<div class=\"xqLeftTitleBox\" style=\"margin:10px 0px;padding:0px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	<div class=\"xqLeftTitleInner\" style=\"margin:0px;padding:0px;text-align:center;\">\r\n		<h2 style=\"font-size:18px;vertical-align:top;color:#50ABF2;\">\r\n			康平县羽毛球馆执行计划\r\n		</h2>\r\n	</div>\r\n</div>\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	场馆地理位置位于东关街道关家屯村，门前为东五线，是康平至方家的必经之路，距离国道203线300米，距离迎宾路2.4公里，交通便利，从县城内出发，车程不超过十分钟。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	场馆拟按标准场地建设，混凝土框架，顶高8米，空心砖墙填充，普通抹灰、蓝色涂料粉刷，断桥铝门窗、双层真空玻璃冷合，彩钢棚顶、地面为龙骨实木地板油漆，铺设专业地胶，球场侧面悬挂式防炫光直排灯管照明，锅炉供暖。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	场馆将于2016年3月未动工，主体部分将于2016年5月30日前完工，室内装修时间为40天，整体工程预计2016年7月18日前全部完成并投入使用。\r\n</p>\r\n<br />\r\n<p style=\"font-size:13px;font-family:\'Microsoft YaHei\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;color:#333333;background-color:#FFFFFF;\">\r\n	场馆建成后开放时间为每日9:00至21:00，节假日不休息，致力于打造康平地区专业羽毛球场馆！\r\n</p>\r\n<h3>\r\n</h3>',0,0,0,0,0,'0.00','0.00','0.00',1456653308,'','','','康平 羽毛球馆 筹建 羽毛球 爱好者 温暖 一个','ux24247ux24179,ux32701ux27611ux29699ux39302,ux31609ux24314,ux32701ux27611ux29699,ux29233ux22909ux32773,ux28201ux26262,ux19968ux20010','康平,羽毛球馆,筹建,羽毛球,爱好者,温暖,一个',0,0,18,'北京','朝阳区',20,0,'vitakung',0,0,0,'',0,'',1,0,0,'0.00','',0,'','',3,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',0,0,'0.00',0,'','','0.00','','',1,0,0,0,0,'','','','','','','','0.00',0,0,0,'','',1,0),(58,'流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！','ux21654ux21857ux39302,ux37325ux24314,ux20844ux30410,ux27969ux28010,ux21147ux37327,ux38656ux35201,ux22825ux20351,ux22823ux23478,ux27969ux28010ux29483ux30340ux23478ux8212ux29233ux22825ux20351ux20844ux30410ux21654ux21857ux39302ux30340ux37325ux24314ux38656ux35201ux22823ux23478ux30340ux21147ux37327ux65281,ux27969ux28010ux29483ux30340ux23478ux8212ux29233ux22825ux20351ux20844ux30410ux21654ux21857ux39302ux30340ux37325ux24314ux38656ux35201ux22823ux23478ux30340ux21147ux37327ux65281,ux27969ux28010ux29483ux30340ux23478ux8212ux29233ux22825ux20351ux20844ux30410ux21654ux21857ux39302ux30340ux37325ux24314ux38656ux35201ux22823ux23478ux30340ux21147ux37327ux65281,ux27969ux28010ux29483ux30340ux23478ux8212ux29233ux22825ux20351ux20844ux30410ux21654ux21857ux39302ux30340ux37325ux24314ux38656ux35201ux22823ux23478ux30340ux21147ux37327ux65281,ux27969ux28010ux29483ux30340ux23478ux8212ux29233ux22825ux20351ux20844ux30410ux21654ux21857ux39302ux30340ux37325ux24314ux38656ux35201ux22823ux23478ux30340ux21147ux37327ux65281','咖啡馆,重建,公益,流浪,力量,需要,天使,大家,流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！,流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！,流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！,流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！,流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！','./public/attachment/201211/07/11/438813e6d75cb84c6b0df8ffbad7aa8c31.jpg','http://www.tudou.com/v/153527563/v.swf','http://www.tudou.com/listplay/i67lCgQt5nQ/i9toRdup3ok.html',50,1352145022,1497297000,1,'3000.00','爱天使成立的猫天使驿站三年多收养救助了两百余只的流浪猫并为它们找到了一个个温暖的家。','<p>\r\n	爱天使成立的猫天使驿站三年多收养救助了两百余只的流浪猫并为它们找到了一个个温暖的家。爱天使是一种爱，更是一种生活！坚持个人信念的我一直努力活出这个世上不一般的价值人生。那就是不追求自己能拥有什么而在能为自己以外的生命带去什么。。。爱天使在今年因合同到期而到了转折点，重建是艰辛的却也坚信必将更加强大。\r\n</p>\r\n<h3>\r\n	【关于我】——将救助流浪猫视为自己的事业！\r\n</h3>\r\n<p>\r\n	首先做个自我介绍：\r\n</p>\r\n<p>\r\n	我叫李文婷，英文名ANGELLI。\r\n</p>\r\n<p>\r\n	是一名爱猫如命的“狂热分子”，\r\n</p>\r\n<p>\r\n	作为流浪猫的代理麻麻已收养救助过两百余只猫咪；\r\n</p>\r\n<p>\r\n	00年在大学校园宿舍开始拨号上网的网络生活，\r\n</p>\r\n<p>\r\n	担任系学生会副主席及宣传部长等，\r\n</p>\r\n<p>\r\n	参与系女篮队、校诗朗诵比赛、主持系选举活动，\r\n</p>\r\n<p>\r\n	组织带领系队作为一辩参加校辩论赛获得季军，\r\n</p>\r\n<p>\r\n	毕业后于厦门海尔及三五互联等公司工作近六年。\r\n</p>\r\n<p>\r\n	工作中一直表现突出主持公司千人晚会并荣获过部门最高荣誉奖。\r\n</p>\r\n<p>\r\n	08年辞去部门经理一职后成为SOHO一族，\r\n</p>\r\n<p>\r\n	经营LA爱天使韩国饰品成为淘宝卖家。\r\n</p>\r\n<p>\r\n	于短短半年间毫无虚假的升为二钻一年后升至三钻，\r\n</p>\r\n<p>\r\n	于09年6月20日在老爸大力的支持下经营爱天使咖啡馆，\r\n</p>\r\n<p>\r\n	于2010年10月创办猫天使驿站正式收养救助流浪猫，\r\n</p>\r\n<p>\r\n	先后接受了海峡导报厦门卫视等媒体及大学生的多次采访报道。\r\n</p>\r\n<p>\r\n	三年间收养救助了两百余只流浪猫并为它们找到了一个个温暖的家。\r\n</p>\r\n<p>\r\n	与仔仔、全全、QQ、EE四只咪咪一起相伴爱天使救命流浪猫的生活。\r\n</p>\r\n<p>\r\n	爱天使就是流浪猫们的家，是我将用余生为之奋斗的事业！\r\n</p>\r\n将“关爱弱小弱势生命，传递爱分享快乐”救助流浪猫视为毕生为之努力的事业。<br />\r\n<br />\r\n<img src=\"./public/attachment/201211/07/16/dda29128a6310c273da111f1f30296c172o5700.jpg\" /><br />\r\n<br />\r\n<br />\r\n<br />\r\n<img src=\"./public/attachment/201211/07/16/c7650c3dd93e5585dbfad780ba3bbced31o5700.jpg\" /><br />\r\n<br />\r\n<br />',1,2,1,5,1,'5000.00','4500.00','0.00',1352231478,'','','','咖啡馆 重建 公益 流浪 力量 需要 天使 大家','ux21654ux21857ux39302,ux37325ux24314,ux20844ux30410,ux27969ux28010,ux21147ux37327,ux38656ux35201,ux22825ux20351,ux22823ux23478','咖啡馆,重建,公益,流浪,力量,需要,天使,大家',1434136659,1,7,'福建','福州',17,0,'fanwe',1,1,0,'',0,'',0,0,0,'0.00','',0,'','',0,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',1,0,'4500.00',0,NULL,NULL,'0.00','',NULL,1,0,0,0,0,NULL,'','','','','','','0.00',0,0,0,'','',1,0),(59,'保护梁子湖，我们在行动。','ux26753ux23376ux28246,ux34892ux21160,ux20445ux25252,ux25105ux20204,ux20445ux25252ux26753ux23376ux28246ux65292ux25105ux20204ux22312ux34892ux21160ux12290','梁子湖,行动,保护,我们,保护梁子湖，我们在行动。','./public/attachment/201602/29/01/56d332260f424.jpg','','',30,1456653037,1459331439,1,'30000.00','梁子湖烟波浩渺，水质澄清，面积42万亩，是全国十大名湖之一，湖北省第二大淡水湖，武昌鱼的母亲湖。湖内动植物资源丰富多彩，有脊椎动物280多种，水生高等','<h3>\r\n</h3>\r\n<div>\r\n	千湖之省的美丽眼睛—梁子湖<br />\r\n梁子湖烟波浩渺，水质澄清，面积42万亩，是全国十大名湖之一，湖北省第二大淡水湖，武昌鱼的母亲湖。湖内动植物资源丰富多彩，有脊椎动物280多种，水生高等植物282种，是“水中大熊猫”桃花水母的栖息地，被誉为化石型湖泊和物种基因库，具有独特的生态价值，是湖北乃至全国的一个十分珍贵的湖泊湿地资源。<br />\r\n保护梁子湖，我们在行动。 (公益活动,绿色环保,绿色,青春,公益)<br />\r\n我们为什么这么做<br />\r\n如果世界上连一滴干净的水，一口新鲜的空气都没有，挣再多的钱，都是死路一条。——周星驰《美人鱼》<br />\r\n<br />\r\n一直以来，由于梁子湖流域的人口不断增加、产业发展和城镇化建设快速推进，加之一些保护措施跟不上等因素，水面面积不断缩小，其水质污染态势不容乐观。有专家担忧，一旦梁子湖流域生态环境进一步恶化，很可能沦为第二个“滇池”。<br />\r\n<br />\r\n幸运的是，我们觉悟的还算早。违法捕鱼的控制、围网被拆除、污染严重企业被关停取缔等等，一系列举措有效遏制了梁子湖生态环境恶化的步伐。2013年，梁子湖区政府划定严禁挖山、严禁填湖、严禁未批先建“三条红线”，在全域范围内全面退出一般性工业。2015年，鄂州市政府先后下发了《关于印发梁子湖（鄂州）生态文明示范区建设规划（2014-2020）》和《关于加快推进绿满鄂州行动的意见》，进一步明确了要加强梁子湖的生态文明建设。<br />\r\n保护梁子湖，我们在行动。 (公益活动,绿色环保,绿色,青春,公益)<br />\r\n我们做了什么<br />\r\n一直以来，为保护梁子湖的生态环境，鄂州市志愿者协会组织了志愿者开展植树造林、湿地保护宣传等活动，并协助相关部门宣教和制止非法捕捞行为。经过近几年的努力，我们在梁子湖流域植树达1万余株，有多个社会志愿组织加入到保护梁子湖的倡导行动中来，相关单位对梁子湖的生态文明建设也越来越重视，广大市民对保护梁子湖的意识越来越强。特别是2015年，我们通过众筹网，设计了“捐十元在梁子湖边种一棵爱心树”的公益项目”，成功筹集7万余元，分别在梁子湖区的沼山镇新桥村和梁子镇毛塘村开展植树造林活动，活动得到了社会各界的广泛支持，取得非常好的效果。<br />\r\n保护梁子湖，我们在行动。 (公益活动,绿色环保,绿色,青春,公益)保护梁子湖，我们在行动。 (公益活动,绿色环保,绿色,青春,公益)<br />\r\n我们将要做什么<br />\r\n您可以认捐树苗，共筑梁子湖生态梦；或者，您也可以联系我们，报名成为一名保护梁子湖生态环境的光荣志愿者。<br />\r\n<br />\r\n1、筹款达到10000元后，我们将在4月份集中组织志愿者到梁子湖畔种下1000棵爱心树。<br />\r\n<br />\r\n2、遵循适地适树的原则，以当地树种为主。严选地块、进行造林设计、管护规划，确保成活率。<br />\r\n<br />\r\n3、整个项目实施，我们将及时发布项目进展情况。<br />\r\n活动预算<br />\r\n1、所筹10000元全部用于购买树苗，按照每10元种一棵树的预算，共种下1000棵爱心树。<br />\r\n<br />\r\n2、所有捐款我们会完全公开明细，接受大家监督。<br />\r\n<br />\r\n3、组织植树活动需要的交通车辆、树苗运输、植树工具等费用，由项目发起方自行解决。<br />\r\n我们将给予什么回报<br />\r\n1、每捐款10元，将在梁子湖畔对应种下一颗爱心树。<br />\r\n<br />\r\n2、捐款50元（含）以上，您本人可参加我们的植树活动；捐款100元（含）以上，您和您的家人或朋友（不超过3人）棵参加我们的植树活动。<br />\r\n<br />\r\n3、捐款1000元（含）以上，您可组织不超过10人团队参加我们的植树活动，并为树木命名。<br />\r\n<br />\r\n4、捐款5000元（含）以上，您可组织不超过30人团队参加我们的植树活动，并为成片的树林命名。<br />\r\n<br />\r\n请将捐款成功页面截图、姓名、联系方式发送至ezhouvolunteer@163.com ，我们的工作人员会及时跟您取得联系。您也可以直接联系我们，将您的需求告诉我们。<br />\r\n保护梁子湖，我们在行动。 (公益活动,绿色环保,绿色,青春,公益)<br />\r\n<br />\r\n</div>',0,0,0,0,0,'0.00','0.00','0.00',1456652774,'','','','梁子湖 行动 保护 我们','ux26753ux23376ux28246,ux34892ux21160,ux20445ux25252,ux25105ux20204','梁子湖,行动,保护,我们',0,0,10,'北京','朝阳区',20,0,'vitakung',0,0,0,'',0,'',1,0,0,'0.00','',0,'','',0,'','','','','','','','','','','',0,0,0,0,0,0,'','',0,0,'',0,0,'0.00','0.00',0,'0.00',0,0,'0.00',0,'0.00','','',0,'','0.00',0,0,'0.00',0,'','','0.00','','',1,0,0,0,0,'','','','','','','','0.00',0,0,0,'','',1,0),(61,'邦美智洗洗衣店O2O智能管理系统','','','./public/attachment/201602/29/02/56d3411e2dc6c.png','','',0,1454323933,1461840736,1,'20000000.00','','',0,0,0,0,0,'0.00','0.00','0.00',1456656546,'','','','互联网+','','',0,0,18,'北京','海淀区',20,0,'vitakung',0,0,0,'',0,'',0,0,0,'0.00','',0,'','',1,'<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;line-height:24px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;line-height:24px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;line-height:24px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;line-height:24px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;line-height:24px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>','a:2:{i:0;a:7:{s:4:\"name\";s:1:\"A\";s:3:\"job\";s:9:\"总经理\";s:12:\"is_full_time\";s:6:\"全职\";s:5:\"share\";d:90;s:12:\"invest_money\";s:3:\"500\";s:8:\"relation\";s:0:\"\";s:8:\"describe\";s:729:\"<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>\";}i:1;a:7:{s:4:\"name\";s:1:\"B\";s:3:\"job\";s:6:\"监事\";s:12:\"is_full_time\";s:6:\"全职\";s:5:\"share\";d:10;s:12:\"invest_money\";s:3:\"100\";s:8:\"relation\";s:0:\"\";s:8:\"describe\";s:729:\"<span style=\"color:#32353D;font-family:\'Lantinghei SC\', \'Open Sans\', Arial, \'Hiragino Sans GB\', \'Microsoft YaHei\', 微软雅黑, STHeiti, \'WenQuanYi Micro Hei\', SimSun, sans-serif;font-size:13px;background-color:#F6F6F6;\">1、创始人王贵亮作风扎实为人刚毅，对业务了解，执行能力强；合伙人李成宇技术运营经验丰富，两人有较强的互补性。\r\n 2、SaaS整合现有洗衣资源模式较轻，业务扩张成本较低，容易整合广大社区服务资源。团队在转型前从事洗衣b2c业务，建立了较完善的标准化的符合行业需求的业务系统，能够提供满足用户需求的Saas服务。\r\n 3、团队有较好的成本控制机制，投资风险可控。</span>\";}}','a:0:{}','a:0:{}','a:0:{}','a:0:{}',0,0,1454227200,11,1,0,'北京猫力网科技有限公司','北京市海淀区中关村创业大街',0,0,'依托互联网及移动互联网产品级解决方案，面向洗衣服务商提供全流程信息化软件服务（SaaS），包括订单获取，订单汇集，物流管理，生产管理，客服管理，数据分析，营销方案支持等方面。帮助传统洗衣企业降低运营费用，提高管理效率，实现“互联网+”落地效应。',1467111140,1,NULL,'30.00',0,'0.00',0,0,'0.00',0,'5000000.00','','a:7:{s:8:\"legal_id\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:12:\"legal_credit\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:16:\"business_license\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:28:\"tax_registration_certificate\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:17:\"organization_code\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:13:\"company_photo\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}s:13:\"site_contract\";a:3:{s:6:\"status\";s:1:\"0\";s:6:\"reason\";s:0:\"\";s:12:\"display_type\";s:1:\"0\";}}',0,'','0.00',0,0,'0.00',0,'不断创新，保持领先，2年之内成为最优秀洗衣行业软件服务提供商，占据主导市场份额；寻找其它传统行业SaaS整合机会，横向发展，成为一家综合软件服务提供商。','回购是指公司的管理层购买本公司的股份，从而使得本公司股权结构、控制权结构发生变化，并使得企业的原经营者变成了企业的所有者的一种收购行为。','0.00','1','',1,0,0,0,0,NULL,'','','','','','','0.00',0,0,0,'','',1,0);
/*!40000 ALTER TABLE `fanwe_deal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_cate`
--

DROP TABLE IF EXISTS `fanwe_deal_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='// 项目分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_cate`
--

LOCK TABLES `fanwe_deal_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_cate` DISABLE KEYS */;
INSERT INTO `fanwe_deal_cate` VALUES (1,'设计',1,0,0),(2,'科技',2,0,0),(3,'影视',3,0,0),(4,'摄影',4,0,0),(5,'音乐',5,0,0),(6,'出版',6,0,0),(7,'活动',7,0,0),(8,'游戏',8,0,0),(9,'旅行',9,0,0),(10,'其他',10,0,0);
/*!40000 ALTER TABLE `fanwe_deal_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_comment`
--

DROP TABLE IF EXISTS `fanwe_deal_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '回复的评论ID',
  `deal_user_id` int(11) NOT NULL COMMENT '项目发起人的ID',
  `reply_user_id` int(11) NOT NULL COMMENT '回复的评论的评论人ID',
  `deal_user_name` varchar(255) NOT NULL,
  `reply_user_name` varchar(255) NOT NULL,
  `deal_info_cache` text NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0 表示隐藏 ，1 表示 显示',
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `user_id` (`user_id`),
  KEY `create_time` (`create_time`),
  KEY `log_id` (`log_id`),
  KEY `pid` (`pid`),
  KEY `deal_user_id` (`deal_user_id`),
  KEY `reply_user_id` (`reply_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COMMENT='// 项目评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_comment`
--

LOCK TABLES `fanwe_deal_comment` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_comment` DISABLE KEYS */;
INSERT INTO `fanwe_deal_comment` VALUES (170,55,'加油哦！',18,1352229601,26,'fzmatthew',0,17,0,'fanwe','','',0),(171,56,'感谢支持！！',18,1352230363,27,'fzmatthew',0,18,0,'fzmatthew','','',0),(172,57,'好好加油！',18,1352230882,28,'fzmatthew',0,17,0,'fanwe','','',0),(173,57,'回复 fzmatthew:一定会的。',17,1352230924,28,'fanwe',172,17,18,'fanwe','fzmatthew','',0),(174,58,'感谢',17,1352231585,29,'fanwe',0,17,0,'fanwe','','',0),(175,58,'感谢大家的支持',17,1352231787,0,'fanwe',0,17,0,'fanwe','','',0);
/*!40000 ALTER TABLE `fanwe_deal_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_faq`
--

DROP TABLE IF EXISTS `fanwe_deal_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='//项目常见问题解答';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_faq`
--

LOCK TABLES `fanwe_deal_faq` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_faq` DISABLE KEYS */;
INSERT INTO `fanwe_deal_faq` VALUES (103,56,'我们的咖啡馆在哪里？','目前暂定的店址，是在延安西路、重庆北路附近。',1),(104,56,'我们的咖啡馆大概有多大？','目前定的店址面积约在200平米以内，有上下两层，底楼较小，二层是整个一层。',2),(105,56,'咖啡馆筹备的进度是？','由于各种的原因，在寻找店址的过程中，先先后后放弃了很多地方，目前找的店址，在办证、面积、交通等方面都较理想。所以基本确定了地方，正在积极办理营业执照及设计各方面的工作，同时也在现有资金的基础上，募集更多的资金及支持。目前店面的装修免租期约在2个月内，所以离正式开业还需要一些时日。',3),(101,58,'流浪猫与爱天使咖啡是什么关系呢？','爱天使就是收养救助流浪猫的咖啡馆。因为救助需要资金与空间，这个资金的最好来源一定是要有一个有收益的项目来支撑而非单纯靠募捐方式，否则容易造成依赖与被动，当然其实也因自己个性好强。 在繁殖季爱天使最多一天能收到3-6只的流浪猫，三年间独自一人艰难维持并收养救助两百多只流浪猫，所需空间资金精力全部由我个人维持。',1),(102,58,'新店确定了吗？装修顺利吗？在哪里呢？','新店终于在几经各方协商后于前日确定于文化艺术中心广场正中间（五一文化广场中间文化宫左边，图书馆对面，大润发楼上正中间）的玻璃房。昨天开始了紧张的重新设计装修中。关于搬店的过程几经周折的磨难苦不堪言。因为新店面积比老店小了，而东西只能先搬过去，而里面要装修所以大柜子都没办法先放进去。里面也已堆满了东西，装修师傅找了五个都不愿意接，因为太多东西很影响装修。东西要一直搬来搬去，现在全部是灰，之后又是一大堆的卫生清洁整理工作，已有很多东西因此受到损坏了。。。新店是转过来了付了一大笔转让费未想因为要重装再装修又要投入两万多的改装费，这笔当时完全忘记预算在内了。。。 不过现在顺利的进入装修重新开业在即。谢谢大家的关注支持！我会让爱天使尽快走回正轨。',2);
/*!40000 ALTER TABLE `fanwe_deal_faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_finance_cate`
--

DROP TABLE IF EXISTS `fanwe_deal_finance_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_finance_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='// 融资分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_finance_cate`
--

LOCK TABLES `fanwe_deal_finance_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_finance_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_finance_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_focus_log`
--

DROP TABLE IF EXISTS `fanwe_deal_focus_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_focus_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `user_id` (`user_id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='// 项目焦点';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_focus_log`
--

LOCK TABLES `fanwe_deal_focus_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_focus_log` DISABLE KEYS */;
INSERT INTO `fanwe_deal_focus_log` VALUES (32,58,18,1352231518),(33,56,17,1352232247);
/*!40000 ALTER TABLE `fanwe_deal_focus_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_house_cate`
--

DROP TABLE IF EXISTS `fanwe_deal_house_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_house_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='// 众筹买房分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_house_cate`
--

LOCK TABLES `fanwe_deal_house_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_house_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_house_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_image`
--

DROP TABLE IF EXISTS `fanwe_deal_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COMMENT='//项目图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_image`
--

LOCK TABLES `fanwe_deal_image` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_image` DISABLE KEYS */;
INSERT INTO `fanwe_deal_image` VALUES (68,60,'./public/attachment/201602/29/01/56d3343ba037a.jpg');
/*!40000 ALTER TABLE `fanwe_deal_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_info_list`
--

DROP TABLE IF EXISTS `fanwe_deal_info_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_info_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL COMMENT '项目ID',
  `type` tinyint(1) NOT NULL COMMENT '类型 0 非股权团队 1 股权团队 2 项目历史 3 计划  4 项目附件',
  `name_list` text NOT NULL,
  `descrip_list` text NOT NULL,
  `pay_list` text NOT NULL COMMENT '支出列表',
  `income_list` text NOT NULL COMMENT '收入列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_info_list`
--

LOCK TABLES `fanwe_deal_info_list` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_info_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_info_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_investor_cate`
--

DROP TABLE IF EXISTS `fanwe_deal_investor_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_investor_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='// 股权众筹分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_investor_cate`
--

LOCK TABLES `fanwe_deal_investor_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_investor_cate` DISABLE KEYS */;
INSERT INTO `fanwe_deal_investor_cate` VALUES (18,'互联网+',1,0,1),(19,'实体店铺',2,0,1),(20,'影视项目',3,0,1);
/*!40000 ALTER TABLE `fanwe_deal_investor_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_item`
--

DROP TABLE IF EXISTS `fanwe_deal_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL COMMENT '金额',
  `support_count` int(11) NOT NULL,
  `support_amount` decimal(20,2) NOT NULL COMMENT '支持量',
  `description` text NOT NULL,
  `is_delivery` tinyint(1) NOT NULL,
  `delivery_fee` decimal(20,2) NOT NULL COMMENT '支付金额',
  `is_limit_user` tinyint(1) NOT NULL COMMENT '是否限',
  `limit_user` int(11) NOT NULL COMMENT '限额数量',
  `repaid_day` int(11) NOT NULL COMMENT '项目成功后的回报时间',
  `virtual_person` int(11) NOT NULL,
  `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否分红',
  `share_fee` decimal(20,2) NOT NULL COMMENT '分红金额',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示回报类型 1表示无私奉献 2抽奖型',
  `lottery_measure` int(11) NOT NULL COMMENT '抽奖量数',
  `maxbuy` int(11) NOT NULL COMMENT '会员购买量',
  `lottery_draw_time` int(11) NOT NULL COMMENT '开奖时间：大于0表已开奖，0未开奖',
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `price` (`price`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='// 项目回报';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_item`
--

LOCK TABLES `fanwe_deal_item` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_item` DISABLE KEYS */;
INSERT INTO `fanwe_deal_item` VALUES (17,55,'10.00',0,'0.00','我们将以平信的方式寄出2款桌游的首发纪念牌，随机赠送部分游戏牌（至少5张）并在游戏说明书中致谢',1,'0.00',0,0,60,0,0,'0.00',0,0,0,0),(18,55,'15.00',1,'15.00','我们将回报《黄金密码》1套，赠送首发纪念牌并在游戏说明书中致谢。（邮费另计）',1,'5.00',0,0,60,0,0,'0.00',0,0,0,0),(19,55,'30.00',0,'0.00','我们将回报《黄金密码》、《功夫》各1套，赠送首发纪念牌并在游戏说明书中致谢。（邮费另计）',1,'5.00',0,0,60,0,0,'0.00',0,0,0,0),(20,55,'50.00',0,'0.00','我们将回报《黄金密码》、《功夫》各2套，赠送首发纪念牌并在游戏说明书中致谢。（邮费另计）',1,'5.00',0,0,60,0,0,'0.00',0,0,0,0),(21,55,'500.00',0,'0.00','我们将回报《黄金密码》40套，赠送首发纪念牌并在游戏说明书中致谢，同时还将在首发纪念牌上印上您的姓名或公司名称致谢！（限额2名）。（国内包邮）',1,'0.00',0,0,60,0,0,'0.00',0,0,0,0),(22,56,'50.00',0,'0.00','你将收到小组成员，在旅行中为你寄出的一张祝福明信片\r\n你将成为我们开业PARTY的座上嘉宾\r\n所以，请留下你的联系方式（电话，地址及邮编）',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(23,56,'200.00',0,'0.00','你将获得咖啡馆开业后永久9折会员卡一张（会员卡可用于借阅书籍，并在生日当天获得免费饮料一杯）\r\n店内旅行纪念手信一份（价值在50元以内）\r\n成为开业PARTY的邀请来宾',1,'0.00',0,0,60,0,0,'0.00',0,0,0,0),(24,56,'500.00',11,'5500.00','你将获得咖啡馆开业后永久9折会员卡一张（会员卡可用于借阅书籍，并在生日当天获得免费饮料一杯）\r\n一份店内招牌下午茶套餐的招待券\r\n免费参加店内组织的活动（各类分享会、试吃体验等等）\r\n成为开业PARTY的邀请来宾',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(25,57,'60.00',0,'0.00','电影签名海报和明信片。全国包邮。',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(26,57,'150.00',0,'0.00','电影DVD的拷贝一张，以及片尾特别感谢。全国包邮。',1,'0.00',0,0,55,0,0,'0.00',0,0,0,0),(27,57,'600.00',0,'0.00','一个崭新的印有影片标志的8GB快闪储存器（flash drive), 电影DVD 拷贝，剧照，和特别回报（包括预告片DVD，拍摄花絮DVD）, 以及片尾特别感谢。（所有DVD均有中文字幕），全国包邮。',1,'0.00',1,20,50,0,0,'0.00',0,0,0,0),(28,57,'1200.00',0,'0.00','电影签名海报和明信片， 一个崭新的印有影片标志的8GB快闪储存器（flash drive), 电影DVD 拷贝，剧照，和特别回报（包括预告片DVD，拍摄花絮DVD）, 以及片尾特别感谢。（所有DVD均有中文字幕）全国包邮。',1,'0.00',1,5,10,0,0,'0.00',0,0,0,0),(29,57,'3000.00',0,'0.00','成为影片的联合制片人（associate producer), 8GB的快闪储存器（flash drive)， 电影DVD 拷贝，剧照，和特别回报（包括预告片DVD，拍摄花絮DVD）。（所有DVD均有中文字幕） 全国包邮。',1,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(30,58,'1000.00',0,'0.00','爱的礼物：精美工艺品及红酒。如果你希望得到一份爱的礼物与记念，请留言你的详细地址姓名电话，我将会于爱天使重建之后的三个月内为你寄一件精美的工艺品及价值399元的澳洲红宝龙红酒一瓶！你将成为爱天使的终生会员。。。',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(31,58,'2000.00',1,'2000.00','爱的礼物：精美工艺品红酒及晚餐。如果你希望得到一份爱的礼物与记念，请留言你的详细地址姓名电话，我将会于爱天使重建之后的三个月内为你寄一件精美的工艺品及澳洲红宝龙红酒一瓶及邀请你到爱天使享受晚餐！你将成为爱天使的终生会员。。。',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(32,58,'3000.00',1,'3000.00','爱的礼物：精美工艺品及红酒及晚餐。如果你希望得到一份爱的礼物与记念，请留言你的详细地址姓名电话，我将会于爱天使重建之后的三个月内为你寄一件精美的工艺品及价值688元的澳洲康纳瓦拉红酒一瓶及邀请你到爱天使享受晚餐！你将成为爱天使的终生会员。。。',1,'0.00',0,0,50,0,0,'0.00',0,0,0,0),(33,59,'0.00',0,'0.00','感谢您的支持，您将收到我们寄出的信件或贺卡，这份支持将助我们的梦想飞的更高更远。',0,'0.00',0,0,0,0,0,'0.00',1,0,0,0),(34,59,'10.00',0,'0.00','支持10元种下一棵爱心树\r\n感谢您的无私奉献，将为您在梁子湖畔种下一棵爱心树，这份捐赠将助我们的梦想飞的更高更远。您将收到一封来自梁子湖边的感谢信。',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(35,59,'50.00',0,'0.00','参与植树活动\r\n本人可以参加植树活动。您将收到一封来自梁子湖边的感谢信。',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(36,59,'100.00',0,'0.00','家庭亲子植树活动。\r\n您和您的家人（不超过3人）可以参加植树活动。您将收到一封来自梁子湖边的感谢信。',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(37,59,'1000.00',0,'0.00','组织团队参与植树活动，并为树木命名。\r\n您可组织不超过10人团队参加植树活动，并为树木命名。您将收到一封来自梁子湖边的感谢信。',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(38,60,'0.00',0,'0.00','感谢您的支持，您将收到我们寄出的信件或贺卡，这份支持将助我们的梦想飞的更高更远。',0,'0.00',0,0,0,0,0,'0.00',1,0,0,0),(39,60,'100.00',0,'0.00','青铜会员\r\n场馆建成后馈赠8次打球次数， 每次不超过3个小时，限一个月内用完！',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(40,60,'500.00',0,'0.00','白银会员\r\n场馆建成后馈赠20次打球次数， 每次不超过3个小时，限3个月内用完！',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0),(41,60,'1000.00',0,'0.00','黄金会员\r\n场馆建成后馈赠40次打球次数， 每次不超过3个小时，限6个月内用完！',0,'0.00',0,0,10,0,0,'0.00',0,0,0,0);
/*!40000 ALTER TABLE `fanwe_deal_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_item_image`
--

DROP TABLE IF EXISTS `fanwe_deal_item_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_item_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `deal_item_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `deal_item_id` (`deal_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='//项目回报图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_item_image`
--

LOCK TABLES `fanwe_deal_item_image` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_item_image` DISABLE KEYS */;
INSERT INTO `fanwe_deal_item_image` VALUES (40,55,18,'./public/attachment/201211/07/10/1df0db265b86352e3886b9c62e8ef01b18.jpg'),(41,55,18,'./public/attachment/201211/07/10/4a4a8bdca29b165b7bd5f510ce200c4385.jpg'),(42,55,18,'./public/attachment/201211/07/10/c8223b4192fc39e4a3dce8a986eccf3994.jpg'),(43,55,19,'./public/attachment/201211/07/10/a37a306a3bedaa664011115de251576034.jpg'),(44,55,20,'./public/attachment/201211/07/10/cc12200a637c9db1dcf7354e592f220985.jpg'),(45,55,21,'./public/attachment/201211/07/10/d65e7badd7098a0922db2b49c2fd8ef011.jpg'),(46,56,22,'./public/attachment/201211/07/11/5d379d45a98db1816b027e85d59ca47c58.jpg'),(47,56,23,'./public/attachment/201211/07/11/1ed8f029594ec5e809d95d8074fe3d2760.jpg'),(48,56,24,'./public/attachment/201211/07/11/b08505b20319f493cbc03debd52eceb474.jpg'),(49,56,24,'./public/attachment/201211/07/11/18b75305fe13c623363abb4ab995f6af34.jpg'),(50,57,25,'./public/attachment/201211/07/11/7ecd287a12bff4289d305c0fb949889e29.jpg'),(51,57,26,'./public/attachment/201211/07/11/d84152ab2d569c584c795018846cbb7233.jpg'),(52,57,27,'./public/attachment/201211/07/11/bdefb72e944b41b60a751d50d0d84fe983.jpg'),(53,57,28,'./public/attachment/201211/07/11/c0df234411b34427dedb121ab9bd577352.jpg'),(54,57,28,'./public/attachment/201211/07/11/9c82e2a30f02513d0a197f3d4573794e76.jpg'),(55,57,29,'./public/attachment/201211/07/11/326730647fde78562777b82f0a9e81a155.jpg'),(56,58,30,'./public/attachment/201211/07/11/06bab2f2823bdd050ef8949162bf717729.jpg'),(57,58,31,'./public/attachment/201211/07/11/c835e1fd43685e3106c4de641f70cf2b62.jpg'),(58,58,32,'./public/attachment/201211/07/11/44036ee2e369e9c91be966a329cac70084.jpg');
/*!40000 ALTER TABLE `fanwe_deal_item_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_level`
--

DROP TABLE IF EXISTS `fanwe_deal_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '等级名',
  `level` int(11) DEFAULT NULL COMMENT '等级大小   大->小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='//项目等级';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_level`
--

LOCK TABLES `fanwe_deal_level` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_log`
--

DROP TABLE IF EXISTS `fanwe_deal_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_info` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `vedio` varchar(255) NOT NULL,
  `source_vedio` varchar(255) NOT NULL,
  `comment_data_cache` text NOT NULL,
  `deal_info_cache` text NOT NULL,
  `update_log_icon` varchar(255) NOT NULL COMMENT '动态展示小图标(暂时房产众筹用到)',
  `houses_status` varchar(255) NOT NULL COMMENT '楼盘阶段',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='//项目的动态，主要由发起人更新动态进度';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_log`
--

LOCK TABLES `fanwe_deal_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_log` DISABLE KEYS */;
INSERT INTO `fanwe_deal_log` VALUES (26,'功夫图文说明书1',1352229555,17,'fanwe',55,'./public/attachment/201211/07/11/5d2a94ce2a3db73277fb04be463365a255.jpg','','','','','',''),(27,'每当我们踏上新的旅程，总是带着期待和兴奋\r\n\r\n而每次踏上归程，多多少少都会怀有一丝的失落\r\n\r\n在路上，我们拥有一拍即合、相谈甚欢的朋友\r\n\r\n在路上，我们总能遇到有趣的人，听到有意思的故事\r\n\r\n在路上，我们可以遗忘时间，丢开工作，在任何一方天地里享用美食和咖啡\r\n\r\n但是归来后，工作和生活又将我们丢回压力和快节奏之下\r\n\r\n我们想要一个在城市中，也能随时抽离的天地\r\n\r\n找朋友，找梦想，找快乐\r\n\r\n \r\n\r\n我们的小窝不会很大，但足以容纳所有的做梦者\r\n\r\n这里有齐全的旅行攻略书籍、各种旅行散文、绘本、游记……\r\n\r\n这里有香浓的咖啡和好吃的甜点\r\n\r\n这里有同样喜爱旅行，爱结交朋友的年轻人\r\n\r\n每一个将这里当做家的人，你们是我们的客人，更是这里的主人',1352230347,18,'fzmatthew',56,'./public/attachment/201211/07/11/714396a1e4416b0f7510d97e6966190459.jpg','','','','','',''),(28,'在电影里看到的最自然的场景在拍摄的时候都是要用灯光特别加工出来的，因为摄影机和人对光的感受能力不一样。人的眼睛可以说是世界上最好的摄影机。这一幕女主角站在窗边充满惆怅的向男主角的方向望过去，明显的受到了日本导演岩井俊二的影响。',1352230864,17,'fanwe',57,'./public/attachment/201211/07/11/eab603d5c65ec25f88a7fdd8ec9a5c1095.jpg','','','','','',''),(29,'谢谢这几天来帮忙的朋友们，昨天一群的同学们让窗户变得明亮，虽然还是挺乱但却充满了快乐与欢。。爱天使每天都要这样充满爱与快乐。。谢谢有你们！因为东西太多可能还要打理两天才能开业，希望最近有空的朋友还能过来帮忙。下午两点过来便可13400642022。地址文化艺术中心大润发楼上正中间店。谢谢！',1352231575,17,'fanwe',58,'./public/attachment/201211/07/11/85a7d1e781bfb8812271b6f6f1bee91d25.jpg','','','','','','');
/*!40000 ALTER TABLE `fanwe_deal_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_msg_list`
--

DROP TABLE IF EXISTS `fanwe_deal_msg_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_msg_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dest` varchar(255) NOT NULL,
  `send_type` tinyint(1) NOT NULL,
  `content` text NOT NULL,
  `send_time` int(11) NOT NULL,
  `is_send` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `result` text NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `is_html` tinyint(1) NOT NULL,
  `title` text NOT NULL,
  `is_youhui` tinyint(1) NOT NULL,
  `youhui_id` int(11) NOT NULL,
  `code` varchar(60) NOT NULL COMMENT '发送的验证码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COMMENT='//私信列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_msg_list`
--

LOCK TABLES `fanwe_deal_msg_list` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_msg_list` DISABLE KEYS */;
INSERT INTO `fanwe_deal_msg_list` VALUES (113,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">项目发起人 vitakung 提交了项目保护梁子湖，我们在行动。，请及时登录后台审核!</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456652985,1,1456652983,0,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-审核众筹项目',0,0,''),(114,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">恭喜您的项目保护梁子湖，我们在行动。审核通过！</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456653033,1,1456653032,20,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-项目审核通过',0,0,''),(115,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">项目发起人 vitakung 提交了项目筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！，请及时登录后台审核!</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456653431,1,1456653431,0,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-审核众筹项目',0,0,''),(116,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">恭喜您的项目筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！审核通过！</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456653456,1,1456653455,20,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-项目审核通过',0,0,''),(117,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">项目发起人 vitakung 提交了项目邦美智洗洗衣店O2O智能管理系统，请及时登录后台审核!</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456656700,1,1456656700,0,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-审核众筹项目',0,0,''),(118,'462414875@qq.com',1,'<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"http://zc03.vitakung.com/public/attachment/201602/16/17/56c2ea5ce3b23.png\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">恭喜您的项目邦美智洗洗衣店O2O智能管理系统审核通过！</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\"></a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">2016年02月29日</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过VK维客众筹帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1456656735,1,1456656735,20,'发件失败的发件人地址vitakung@163.com',0,1,'众筹通知-项目审核通过',0,0,'');
/*!40000 ALTER TABLE `fanwe_deal_msg_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_notify`
--

DROP TABLE IF EXISTS `fanwe_deal_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='//准备发送通知的项目ID';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_notify`
--

LOCK TABLES `fanwe_deal_notify` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_notify` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_order`
--

DROP TABLE IF EXISTS `fanwe_deal_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `deal_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `pay_time` int(11) NOT NULL,
  `total_price` decimal(20,2) NOT NULL COMMENT '总价',
  `delivery_fee` decimal(20,2) NOT NULL COMMENT '运费',
  `deal_price` decimal(20,2) NOT NULL COMMENT '项目费用',
  `support_memo` text NOT NULL,
  `payment_id` int(11) NOT NULL,
  `bank_id` varchar(255) NOT NULL,
  `credit_pay` decimal(20,2) NOT NULL COMMENT '信贷付款',
  `online_pay` decimal(20,2) NOT NULL COMMENT '在线付款',
  `deal_name` varchar(255) NOT NULL,
  `order_status` tinyint(1) NOT NULL COMMENT '0:未支付 1:已支付(过期) 2:已支付(无库存) 3:成功',
  `create_time` int(11) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `repay_time` int(11) NOT NULL COMMENT '回报更新时间',
  `repay_memo` text NOT NULL COMMENT '回报备注，由发起人更新',
  `is_refund` tinyint(1) NOT NULL COMMENT '已退款 0:未 1:已',
  `is_has_send_success` tinyint(1) NOT NULL,
  `repay_make_time` int(11) NOT NULL DEFAULT '0' COMMENT '回报确认时间',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '购买数量',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 0表示普通众筹 1表示股权众筹 2表示无私奉 3抽奖商品 5表示融资众筹 6表示股权转让 7房产众筹',
  `invest_id` int(11) NOT NULL DEFAULT '0' COMMENT 'invest 的ID',
  `share_fee` decimal(20,2) NOT NULL COMMENT '分红金额',
  `share_status` tinyint(1) NOT NULL COMMENT '分红是否发放：0未发放，1已发',
  `is_tg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示在线支付 1表示第三方托管',
  `score` int(11) NOT NULL COMMENT '付款积分',
  `score_money` decimal(20,2) NOT NULL COMMENT '积分对换的余额,对换的余额已加到余额支付里，这里记录是用在查看，退款时用',
  `sp_multiple` varchar(255) NOT NULL COMMENT '记录"购买送支付金额的几倍信用/积分"的倍数的反序列数组array("score_multiple"=>''倍数'',"point_multiple"=>''倍数''）,退款时用',
  `logistics_company` varchar(255) NOT NULL COMMENT '物流公司',
  `logistics_links` varchar(255) NOT NULL COMMENT '物流链接',
  `logistics_number` varchar(255) NOT NULL COMMENT '物流单号',
  `requestNo` varchar(255) NOT NULL COMMENT 'yeepay_log.id',
  `is_complete_transaction` tinyint(1) NOT NULL COMMENT '0 表示未放款 1表示放款  2表示退款',
  `fee` decimal(20,2) NOT NULL COMMENT '手续费',
  `targetAmount` decimal(20,2) NOT NULL COMMENT '获取的目标金额',
  `progress` tinyint(1) NOT NULL DEFAULT '0' COMMENT '项目进度 0 表示未支付 2表示已支付定金 3表示支付首付 4表示退款处理 5放款处理',
  `is_winner` tinyint(1) NOT NULL COMMENT '0：没开奖，1幸运单，2未抽到',
  `lottery_draw_time` int(11) unsigned zerofill NOT NULL COMMENT '开奖时间',
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `deal_item_id` (`deal_item_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COMMENT='// 订单信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_order`
--

LOCK TABLES `fanwe_deal_order` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_order` DISABLE KEYS */;
INSERT INTO `fanwe_deal_order` VALUES (65,55,18,18,'fzmatthew',1352229388,'20.00','5.00','15.00','请在上班时间配送。',0,'COMM','20.00','0.00','原创DIY桌面游戏《功夫》《黄金密码》期待您的支持',3,1352229388,'方维','350000','13333333333','福建','福州','福建福州台江区工业路博美诗邦',0,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(66,56,24,17,'fanwe',1352230101,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230101,'方维','22222','14444444444','福建','福州','方维方维方维方维方维',1,1352230424,'回报已经发货，发货单号123456, 有问题请联系我。',0,0,1424910145,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(67,56,24,19,'test',1352230180,'500.00','0.00','500.00','',24,'ICBCB2C','0.00','500.00','拥有自己的咖啡馆',3,1352230157,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(68,56,24,19,'test',1352230228,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230228,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(69,56,24,19,'test',1352230232,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230232,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(70,56,24,19,'test',1352230237,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230237,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(71,56,24,19,'test',1352230240,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230240,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(72,56,24,19,'test',1352230243,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230243,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(73,56,24,19,'test',1352230247,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230247,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(74,56,24,19,'test',1352230268,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230268,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(75,56,24,19,'test',1352230270,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230270,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(76,56,24,19,'test',1352230293,'500.00','0.00','500.00','',0,'','500.00','0.00','拥有自己的咖啡馆',3,1352230293,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(77,58,31,18,'fzmatthew',1352231539,'2000.00','0.00','2000.00','test',0,'','2000.00','0.00','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！',3,1352231539,'方维','350000','13333333333','福建','福州','福建福州台江区工业路博美诗邦',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(78,58,30,19,'test',0,'1000.00','0.00','1000.00','ttt',24,'CCB','500.00','0.00','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！',0,1352231631,'test','test','13344455555','湖北','襄樊','test',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(79,56,24,17,'fanwe',0,'500.00','0.00','500.00','部份支付',24,'ICBCB2C','300.00','0.00','拥有自己的咖啡馆',0,1352231671,'方维','22222','14444444444','福建','福州','方维方维方维方维方维',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000),(80,58,32,18,'fzmatthew',1352231704,'3000.00','0.00','3000.00','',0,'','3000.00','0.00','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！',3,1352231704,'方维','350000','13333333333','福建','福州','福建福州台江区工业路博美诗邦',1,0,'',0,0,0,1,0,0,'0.00',0,0,0,'0.00','','','','','',0,'0.00','0.00',0,0,00000000000);
/*!40000 ALTER TABLE `fanwe_deal_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_order_lottery`
--

DROP TABLE IF EXISTS `fanwe_deal_order_lottery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_order_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL COMMENT '项目id',
  `deal_item_id` int(11) NOT NULL COMMENT '支持id',
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `user_name` varchar(100) NOT NULL COMMENT '会员名',
  `lottery_sn` varchar(50) NOT NULL COMMENT '抽奖号',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `is_winner` tinyint(1) NOT NULL COMMENT '0：没开奖，1：幸运号,2：未抽到，3:订单退款，些号无效',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `time_msec` decimal(20,3) NOT NULL COMMENT '创建时间精确到毫秒',
  `lottery_draw_time` int(11) NOT NULL COMMENT '开奖时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lottery_sn` (`lottery_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_order_lottery`
--

LOCK TABLES `fanwe_deal_order_lottery` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_order_lottery` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_order_lottery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_pay_log`
--

DROP TABLE IF EXISTS `fanwe_deal_pay_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_pay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `money` decimal(20,2) NOT NULL,
  `create_time` int(11) NOT NULL,
  `log_info` text NOT NULL,
  `comissions` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `share_fee` decimal(20,2) NOT NULL,
  `delivery_fee` decimal(20,2) NOT NULL,
  `requestNo` varchar(255) NOT NULL COMMENT '是第三方支付的请求号',
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='//项目支持金额发放记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_pay_log`
--

LOCK TABLES `fanwe_deal_pay_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_pay_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_pay_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_selfless_cate`
--

DROP TABLE IF EXISTS `fanwe_deal_selfless_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_selfless_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='// 公益众筹分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_selfless_cate`
--

LOCK TABLES `fanwe_deal_selfless_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_selfless_cate` DISABLE KEYS */;
INSERT INTO `fanwe_deal_selfless_cate` VALUES (18,'扶贫',1,0,1),(19,'儿童',2,0,1);
/*!40000 ALTER TABLE `fanwe_deal_selfless_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_support_log`
--

DROP TABLE IF EXISTS `fanwe_deal_support_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_support_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL COMMENT '金额',
  `deal_item_id` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`),
  KEY `user_id` (`user_id`),
  KEY `create_time` (`create_time`),
  KEY `deal_item_id` (`deal_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='// 项目支持记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_support_log`
--

LOCK TABLES `fanwe_deal_support_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_support_log` DISABLE KEYS */;
INSERT INTO `fanwe_deal_support_log` VALUES (41,55,18,1352229388,'15.00',18,1),(42,56,17,1352230101,'500.00',24,1),(43,56,19,1352230180,'500.00',24,1),(44,56,19,1352230228,'500.00',24,1),(45,56,19,1352230232,'500.00',24,1),(46,56,19,1352230237,'500.00',24,1),(47,56,19,1352230240,'500.00',24,1),(48,56,19,1352230243,'500.00',24,1),(49,56,19,1352230247,'500.00',24,1),(50,56,19,1352230268,'500.00',24,1),(51,56,19,1352230270,'500.00',24,1),(52,56,19,1352230293,'500.00',24,1),(53,58,18,1352231539,'2000.00',31,1),(54,58,18,1352231704,'3000.00',32,1);
/*!40000 ALTER TABLE `fanwe_deal_support_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_visit_log`
--

DROP TABLE IF EXISTS `fanwe_deal_visit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_visit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `client_ip` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=utf8 COMMENT='// 访问记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_visit_log`
--

LOCK TABLES `fanwe_deal_visit_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_visit_log` DISABLE KEYS */;
INSERT INTO `fanwe_deal_visit_log` VALUES (117,55,'127.0.0.1',1352229137),(118,56,'127.0.0.1',1352230070),(119,57,'127.0.0.1',1352230830),(120,58,'127.0.0.1',1352231514),(121,56,'127.0.0.1',1352231651),(122,55,'127.0.0.1',1352232299),(123,58,'127.0.0.1',1352232420),(124,56,'127.0.0.1',1352232590),(125,57,'127.0.0.1',1352232717),(126,55,'127.0.0.1',1352246374),(127,57,'127.0.0.1',1352246699),(128,56,'127.0.0.1',1352246710),(129,58,'127.0.0.1',1352246719),(130,58,'127.0.0.1',1455586010),(131,56,'127.0.0.1',1455586205),(132,57,'127.0.0.1',1455586417),(133,58,'127.0.0.1',1455586732);
/*!40000 ALTER TABLE `fanwe_deal_visit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_vote`
--

DROP TABLE IF EXISTS `fanwe_deal_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `deal_id` int(11) NOT NULL COMMENT '项目id',
  `create_time` int(11) NOT NULL COMMENT '投票创建时间',
  `begin_time` int(11) NOT NULL COMMENT '投票开始时间',
  `end_time` int(11) NOT NULL COMMENT '投票结束时间',
  `money` decimal(20,2) NOT NULL COMMENT '卖出金额',
  `status` tinyint(1) NOT NULL COMMENT '0表示未同意 1表示同意 2表示投票失败',
  `yes_num` int(11) NOT NULL COMMENT '同意的总票数',
  `no_num` int(11) NOT NULL COMMENT '不同意的总票数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_vote`
--

LOCK TABLES `fanwe_deal_vote` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_deal_vote_log`
--

DROP TABLE IF EXISTS `fanwe_deal_vote_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_deal_vote_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `deal_vote_id` int(11) NOT NULL COMMENT '投票id',
  `vote_status` tinyint(1) NOT NULL COMMENT '0表示不同意 1表示同意',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_deal_vote_log`
--

LOCK TABLES `fanwe_deal_vote_log` WRITE;
/*!40000 ALTER TABLE `fanwe_deal_vote_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_deal_vote_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_faq`
--

DROP TABLE IF EXISTS `fanwe_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='// 常见问题设置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_faq`
--

LOCK TABLES `fanwe_faq` WRITE;
/*!40000 ALTER TABLE `fanwe_faq` DISABLE KEYS */;
INSERT INTO `fanwe_faq` VALUES (1,'基本问题','这是什么站?','我们是一个让你可以发起和支持创意项目的平台。如果你有一个创意的想法(新颖的产品?独立电影?)，我们欢迎你到我们的平台上发起项目，向公众推广，并得到资金的支持去完成你的想法。如果你喜欢创意，我们欢迎你来到我们平台，浏览各种有趣的项目，并力所能及支持他们。',1),(2,'基本问题','什么样的项目适合我们的平台?','我们欢迎一切有创意的想法，欢迎艺术家，电影工作者，音乐家，产品设计师，作家，画家，表演者，DJ等等来我们平台推广他们的创意。但是，我们的平台不适用于慈善项目或是创业投资项目。如果你不确定你的想法是否适合我们的平台，欢迎你直接与我们联系。',2),(3,'基本问题','这种模式有非法集资的风险吗?','不会，因为我们要求项目不能够以股权或是资金作为对支持者的回报。项目发起人更不能向支持者许诺任何资金上的收益。项目的回报必须是以实物（如产品，出版物），或者媒体内容（如提供视频或者音乐的流媒体播放或者下载）。我们平台项目接受支持，不能够以股权或者债券的形式。支持者对一个项目的支持属于购买行为，而不是投资行为。',3),(4,'基本问题','这个平台接受慈善项目类的提案么?','我们不接受慈善类项目。作为个人，我们支持社会公益慈善事业，但是我们平台不是支持此类项目的平台。我们所接受的是商业类，有销售购买行为的设计或者文创类的项目。项目发起人需要给支持以实物或者媒体内容类的回报。',4),(5,'项目发起人相关问题','是否会要求产品或作品的知识产权?','不会。我们只是提供一个宣传和支持的平台，知识产权由项目发起人所有。',5),(6,'项目发起人相关问题','什么人可以发起项目?','目前任何在两岸三地(中国大陆，台湾，港澳)的有创意的人都可以发起项目。你可以是一个从事创意行业的自由职业者，也可以是公司职员。只要你有个点子，我们都希望收到你的项目提案。',6),(7,'项目发起人相关问题','我怎么发起项目呢?','请到我们的网站并注册用户后，在我们网站上提交所需要的基本项目信息，包括项目的内容，目前进行的阶段等等。我们会有专人跟进，与你联系。',7),(8,'项目发起人相关问题','我想发起项目，但是我担心我的知识产权被人抄袭?','作为项目发起人，你可以选择公布更多的信息。知识产权敏感的信息，你可以选择不公开。同时，我们平台是一个面对公众的平台。你所提供的信息越丰富，越翔实，就越容易打动和说服别人的支持。',8),(9,'项目发起人相关问题','项目目标金额是否有上下限制?','我们对目标金额的下限是1000元人民币。原则上没有上限。但是资金的要求越高，成功的概率就越低。目前常见的目标金额从几千到几万不等。',9),(10,'项目发起人相关问题','没有达到目标金额，是否就不能得到支持?','是的。如果在项目截至日期到达时，没有达到预期，那么已经收到的资金会退还给支持者。这么做的原因是为了给支持者提供风险保护。只有当项目有足够多的人支持足够多的资金时，他们的支持才生效。',10),(11,'项目发起人相关问题','我的项目成功了，然后呢?','我们会分两次把资金打入你所提供的银行账户。两次汇款的时间和金额因项目而异，在项目上线之前，由我们平台与项目发起人确定。在资金的支持下，你就可以开始进行你的项目，给你的支持者以邮件或者其他形式的更新，并如期实现你承诺的回报。',11),(12,'项目发起人相关问题','如何设定项目截止日期?','一般来说，时间设置在一个月或以内比较合适。数据显示，绝大部分的支持发生在项目上线开始和结束前的一个星期中。',12),(13,'项目发起人相关问题','收到的金额能够超过预设的目标?','可以。在截至日期之前，项目可以一直接受资金支持。',13),(14,'项目发起人相关问题','大家支持的动机是什么?','大家对项目支持的动机是多样的。有些是因为项目发起者提供了有吸引力的回报，特别是产品设计类的项目。有些是因为认可这个项目，希望它能够实现。有些是因为认可项目的发起人，希望助他一臂之力。',14),(15,'项目发起人相关问题','什么样的回报比较合适?','回报因项目而异。可以是实物，比如如果是电影项目，可以提供成片后的DVD;如果是产品设计，可以提供产品;其他还有如明信片，T恤，出版物。也可以是非实物，比如鸣谢，与项目发起人共进晚餐，影片首映的门票等。我们欢迎项目发起人展开想象，设计出各式各样的回报。',15),(16,'项目发起人相关问题','如何能够吸引更多的人来支持我的项目?','对此，我们会另外详细介绍。简短来说，有以下要点\r\n- 拍摄一个有趣，吸引人的视频。讲述这个项目背后的故事。\r\n- 提供有吸引力，物有所值的回报。\r\n- 项目刚上线时，要发动你的亲朋好友来支持你。让你的项目有一个基本的人气。\r\n- 充分运用微博，人人等社交网站来推广。\r\n- 在项目上线期间，经常性的在你的项目页上提供项目的更新，与支持者，询问者的互动。\r\n- 项目宣传视频是必须的么?\r\n宣传视频是项目页上的重要内容。是公众了解你和你的项目的第一步。一个好的宣传视频，能够比文字和图片起到更好的宣传效果。基于这个原因，我们要求每个项目都提供一个视频。有必要的话，我们平台可以提供视频拍摄的支持。',16),(17,'项目发起人相关问题','项目宣传视频有什么要求?','我们要求宣传视频在两分钟之内。内容上，强烈建议包括以下内容\r\n发起人姓名\r\n项目简短描述(特别说明其吸引人的地方)，目前进展\r\n为什么需要支持\r\n谢谢大家',17),(18,'项目支持者相关问题','如果项目没有达到目标金额，我支持的资金会还给我么?','是的。如果项目在截止日期没有达到目标，你所支持的金额会返还给你。',18),(19,'项目支持者相关问题','如何支持一个项目?','每个项目页的右侧有可选择的支持额度和相应的回报介绍。想支持的话，选择你想支持的金额，鼠标点击绿色的按钮，即可。你可以选择支付宝或者财付通来完成付款。',19),(20,'项目支持者相关问题','如何保证项目发起人能够实现他们的承诺呢?','很多项目本身存在着风险，比如产品设计和纪录片的拍摄。有可能存在项目发起人无法完成其许诺的情况。项目支持者一方面要了解创意项目本身是有风险的，另一方面，我们要求项目发起人提供联系方式，并且鼓励有意支持者直接联系他们，在与项目发起人的沟通和互动中对项目的价值，风险，项目发起人的执行力等等有所判断。',20);
/*!40000 ALTER TABLE `fanwe_faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_file_verifies`
--

DROP TABLE IF EXISTS `fanwe_file_verifies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_file_verifies` (
  `nameid` char(32) NOT NULL DEFAULT '',
  `cthash` varchar(32) NOT NULL DEFAULT '',
  `method` enum('local','official') NOT NULL DEFAULT 'official',
  `filename` varchar(254) NOT NULL DEFAULT '',
  PRIMARY KEY (`nameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_file_verifies`
--

LOCK TABLES `fanwe_file_verifies` WRITE;
/*!40000 ALTER TABLE `fanwe_file_verifies` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_file_verifies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_finance_company`
--

DROP TABLE IF EXISTS `fanwe_finance_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_finance_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '融资公司',
  `company_name` varchar(255) NOT NULL COMMENT '公司简称',
  `company_all_name` varchar(255) NOT NULL COMMENT '公司全称',
  `company_p_status` tinyint(1) NOT NULL COMMENT '0 3个月内上线 1 6个月内上线  2 运营中  3 停止运营 ',
  `company_brief` varchar(255) NOT NULL COMMENT '一句话简介',
  `company_website` varchar(255) NOT NULL COMMENT '公司网址',
  `company_create_time` int(11) NOT NULL COMMENT '公司创建时间',
  `company_logo` varchar(255) NOT NULL COMMENT '公司LOGO',
  `company_level` tinyint(1) NOT NULL COMMENT '0 表示创始人 1表示联合创始人',
  `company_job` varchar(255) NOT NULL COMMENT '职位',
  `company_sina_weibo` varchar(255) NOT NULL COMMENT '新浪微博',
  `company_weixin` varchar(255) NOT NULL COMMENT '微信账号',
  `company_business_card` varchar(255) NOT NULL COMMENT '名片',
  `iphone_url` varchar(255) NOT NULL COMMENT 'iPhone下载链接',
  `pc_url` varchar(255) NOT NULL COMMENT 'PC端下载链接',
  `android_url` varchar(255) NOT NULL COMMENT 'Android下载链接',
  `ipd_url` varchar(255) NOT NULL COMMENT 'iPad下载链接',
  `take_office_time` int(11) NOT NULL COMMENT '上任时间',
  `company_introduce_image` text NOT NULL COMMENT '图片介绍',
  `company_introduce_word` text NOT NULL COMMENT '文字介绍',
  `user_id` int(11) NOT NULL COMMENT '所属会员',
  `cate_id` int(11) NOT NULL COMMENT '分类ID',
  `status` tinyint(1) NOT NULL COMMENT '0 表示无效，未提交审核 1表示审核通过 2表示审核未通过',
  `province` varchar(255) NOT NULL COMMENT '省份',
  `city` varchar(255) NOT NULL COMMENT '城市',
  `company_tag` varchar(255) NOT NULL COMMENT '标签',
  `team_advantage` text COMMENT '团队优势',
  `is_edit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '编辑状态 0 提交审核 1 编辑',
  `refuse_reason` varchar(255) NOT NULL COMMENT '未通过理由',
  `focus_company_count` int(11) NOT NULL DEFAULT '0' COMMENT '关注公司的数量',
  `user_level` int(11) NOT NULL COMMENT '公司等级',
  `sort` int(11) NOT NULL COMMENT '序列号',
  `is_recommend` tinyint(1) NOT NULL COMMENT '推荐公司',
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_name` (`company_name`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_finance_company`
--

LOCK TABLES `fanwe_finance_company` WRITE;
/*!40000 ALTER TABLE `fanwe_finance_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_finance_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_finance_company_focus`
--

DROP TABLE IF EXISTS `fanwe_finance_company_focus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_finance_company_focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID',
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='关注的公司';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_finance_company_focus`
--

LOCK TABLES `fanwe_finance_company_focus` WRITE;
/*!40000 ALTER TABLE `fanwe_finance_company_focus` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_finance_company_focus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_finance_company_investment_case`
--

DROP TABLE IF EXISTS `fanwe_finance_company_investment_case`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_finance_company_investment_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID',
  `create_time` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL COMMENT '公司简称',
  `invest_phase` tinyint(2) NOT NULL COMMENT '投资阶段 0表示天使轮,1表示Pre-A轮，2表示A轮，3表示A+轮，4表示B轮，5表示B+轮，6表示C轮，7表示D轮，8表示E轮及以后，9表示并购，10表示上市',
  `invest_amount_unit` tinyint(1) NOT NULL COMMENT '我方投资单位 0 人民币 1 美元',
  `invest_amount` decimal(20,2) NOT NULL COMMENT '我方投资金额 ，单位：元',
  `finance_amount_unit` tinyint(1) NOT NULL COMMENT '此轮总投资金额 单位 0 人民币 1 美元',
  `finance_amount` decimal(20,2) NOT NULL COMMENT '此轮总投资金额 单位：元',
  `valuation_unit` tinyint(1) NOT NULL COMMENT '此轮总投资金额 单位 0 人民币 1 美元',
  `valuation` decimal(20,2) NOT NULL COMMENT '此轮估值 金额 单位：元',
  `invest_time` int(11) NOT NULL COMMENT '投资时间',
  `status` tinyint(1) NOT NULL COMMENT '0 未审核 1审核通过 2 审核不通过',
  `type` tinyint(1) NOT NULL COMMENT '0 表示投资案例  1 融资经历 2 过往投资方',
  `invest_company_id` int(11) NOT NULL COMMENT '案例公司ID',
  `invest_type` tinyint(1) DEFAULT NULL COMMENT '过往投资方类型 1 代表个人 2 投资机构  3 公司',
  `level` tinyint(1) DEFAULT NULL COMMENT '0 代表创始合伙人 1 代表董事长 2 CEO 3 管理合伙人 4 资深合伙人 5 合伙人 6 风险合伙人 7 董事 8总经理 9 副总经理 10 董事总经理 11 高级副总裁 12 副总裁  13 投资总监 14 高级投资经理 15 投资经理 16 高级分析师 17 分析师',
  `invest_subject` text COMMENT '融资经历的 投资主体',
  `finance_pressurl` varchar(25) DEFAULT NULL COMMENT '融资经历的 相关报道',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关注的公司';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_finance_company_investment_case`
--

LOCK TABLES `fanwe_finance_company_investment_case` WRITE;
/*!40000 ALTER TABLE `fanwe_finance_company_investment_case` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_finance_company_investment_case` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_finance_company_sub_product`
--

DROP TABLE IF EXISTS `fanwe_finance_company_sub_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_finance_company_sub_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID',
  `create_time` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL COMMENT '产品名称',
  `product_website` varchar(255) NOT NULL COMMENT '子产品链接',
  `status` tinyint(1) NOT NULL COMMENT '0 未审核 1审核通过 2 审核不通过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司的子产品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_finance_company_sub_product`
--

LOCK TABLES `fanwe_finance_company_sub_product` WRITE;
/*!40000 ALTER TABLE `fanwe_finance_company_sub_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_finance_company_sub_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_finance_company_team`
--

DROP TABLE IF EXISTS `fanwe_finance_company_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_finance_company_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID/机构ID',
  `create_time` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0 表示创始团队 1 团队成员 2 过往成员 3 过往投资方 4 机构成员',
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '职位类型 0 代表创始人 1 代表联合创始人',
  `position` varchar(255) NOT NULL COMMENT '职位名称',
  `user_id` int(11) NOT NULL COMMENT '对应的会员ID',
  `status` tinyint(1) NOT NULL COMMENT '0 未审核 1审核通过 2 审核不通过',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `intro` varchar(255) NOT NULL COMMENT '成员介绍',
  `employee_level` tinyint(1) DEFAULT NULL COMMENT '职位类型 0 技术 1 设计 2 产品 3 运营 4 市场与销售 5 行政、人事及财务 6 投资和并购 7 其他',
  `is_manager` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是公司的管理者 0代表不是  1代表是',
  `job_start_time` int(11) NOT NULL COMMENT '任职开始时间',
  `job_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '0 表示至今 ',
  `invite_name` varchar(255) DEFAULT NULL COMMENT '公司名称/机构名称',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `invest_type` tinyint(1) NOT NULL COMMENT '过往投资方类型 1 代表个人 2 投资机构  ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司团队';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_finance_company_team`
--

LOCK TABLES `fanwe_finance_company_team` WRITE;
/*!40000 ALTER TABLE `fanwe_finance_company_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_finance_company_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods`
--

DROP TABLE IF EXISTS `fanwe_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `sub_name` varchar(255) NOT NULL COMMENT '商品简称',
  `cate_id` int(11) NOT NULL COMMENT '分类ID',
  `img` text NOT NULL COMMENT '商品主图',
  `brief` text NOT NULL COMMENT '商品简介',
  `description` text NOT NULL COMMENT '商品描述',
  `sort` int(11) NOT NULL COMMENT '排序',
  `max_bought` int(11) NOT NULL COMMENT '库存数',
  `user_max_bought` int(11) NOT NULL COMMENT '会员最大购买量按件',
  `score` int(11) NOT NULL COMMENT '购买所需积分',
  `is_delivery` tinyint(1) NOT NULL COMMENT '	是否需要配送；0：否; 1：是',
  `is_hot` tinyint(1) NOT NULL COMMENT '热卖',
  `is_new` tinyint(1) NOT NULL COMMENT '最新',
  `is_recommend` tinyint(1) NOT NULL COMMENT '是否推荐',
  `is_effect` tinyint(1) NOT NULL COMMENT '1：可用，0不可用',
  `seo_title` text NOT NULL COMMENT 'SEO自定义标题',
  `seo_keyword` text NOT NULL COMMENT 'SEO自定义关键词',
  `seo_description` text NOT NULL COMMENT 'SEO自定义描述',
  `goods_type_id` int(11) NOT NULL COMMENT '商品属性',
  `invented_number` int(11) NOT NULL COMMENT '虚拟购买数',
  `buy_number` int(11) NOT NULL COMMENT '购买人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods`
--

LOCK TABLES `fanwe_goods` WRITE;
/*!40000 ALTER TABLE `fanwe_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_attr`
--

DROP TABLE IF EXISTS `fanwe_goods_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '属性名称',
  `goods_type_attr_id` int(11) NOT NULL COMMENT '属性ID',
  `score` int(11) NOT NULL COMMENT '所需积分',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `is_checked` tinyint(1) NOT NULL COMMENT '是否有独立库存',
  PRIMARY KEY (`id`),
  KEY `goods_type_attr_id` (`goods_type_attr_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_attr`
--

LOCK TABLES `fanwe_goods_attr` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_attr_stock`
--

DROP TABLE IF EXISTS `fanwe_goods_attr_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_attr_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr_cfg` text NOT NULL,
  `stock_cfg` int(11) NOT NULL,
  `attr_str` text NOT NULL,
  `buy_count` int(11) NOT NULL,
  `attr_key` varchar(100) NOT NULL COMMENT '属性ID以下划线从小到大排序的key',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_attr_stock`
--

LOCK TABLES `fanwe_goods_attr_stock` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_attr_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_attr_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_cate`
--

DROP TABLE IF EXISTS `fanwe_goods_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品分类名称',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_cate`
--

LOCK TABLES `fanwe_goods_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_order`
--

DROP TABLE IF EXISTS `fanwe_goods_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL COMMENT '订单号',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `score` int(11) NOT NULL COMMENT ' 所需积分',
  `total_score` int(11) NOT NULL COMMENT ' 所需积分',
  `pay_score` int(11) NOT NULL COMMENT '支付的积分',
  `number` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `user_id` int(11) NOT NULL COMMENT ' 会员ID',
  `user_name` varchar(255) NOT NULL COMMENT '会员名',
  `delivery_sn` varchar(255) NOT NULL COMMENT '快递单号',
  `order_status` tinyint(1) NOT NULL COMMENT '订单状态 0未兑换 1已兑换 2已兑换（无库存，积分已退回） 3:退积分（管理员取消兑换，退还积分）  4已取消   5无效的订单',
  `delivery_status` tinyint(1) NOT NULL COMMENT '0:未发货，1：已发货，2：无需发货',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `create_date` date NOT NULL COMMENT '创建时间Ymd',
  `ex_time` int(11) NOT NULL COMMENT '兑换时间',
  `ex_date` date NOT NULL COMMENT '兑换时间Ymd',
  `is_delivery` tinyint(1) NOT NULL COMMENT '是否配送',
  `delivery_date` date NOT NULL COMMENT '发货时间Ymd',
  `delivery_time` int(11) NOT NULL COMMENT '发货时间',
  `delivery_tel` varchar(255) NOT NULL COMMENT '收货电话',
  `delivery_zip` varchar(255) NOT NULL,
  `delivery_addr` varchar(255) NOT NULL COMMENT '收货地址',
  `delivery_city` varchar(255) NOT NULL COMMENT '发货城市',
  `delivery_province` varchar(255) NOT NULL COMMENT '发货省份',
  `delivery_name` varchar(255) NOT NULL COMMENT '收货名称',
  `delivery_express` varchar(255) NOT NULL COMMENT '快递公司',
  `attr_view` varchar(255) NOT NULL COMMENT '属性信息',
  `attr` text NOT NULL COMMENT '所选属性',
  `memo` text NOT NULL COMMENT '用户留言',
  `admin_memo` text NOT NULL COMMENT '取消订单备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_order`
--

LOCK TABLES `fanwe_goods_order` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_type`
--

DROP TABLE IF EXISTS `fanwe_goods_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品类型名',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_type`
--

LOCK TABLES `fanwe_goods_type` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_goods_type_attr`
--

DROP TABLE IF EXISTS `fanwe_goods_type_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_goods_type_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品属性名',
  `input_type` tinyint(1) NOT NULL,
  `preset_value` text NOT NULL,
  `goods_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_goods_type_attr`
--

LOCK TABLES `fanwe_goods_type_attr` WRITE;
/*!40000 ALTER TABLE `fanwe_goods_type_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_goods_type_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_help`
--

DROP TABLE IF EXISTS `fanwe_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_fix` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='// 帮助介绍';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_help`
--

LOCK TABLES `fanwe_help` WRITE;
/*!40000 ALTER TABLE `fanwe_help` DISABLE KEYS */;
INSERT INTO `fanwe_help` VALUES (1,'服务条款','<div class=\"layout960\"><p><strong>一、接受条款</strong></p>\r\n<p>我们所提供的服务包含我们平台网站体验和使用、我们平台互联网消息传递服务以及我们平台提供的与我们平台网站有关的任何其他特色功能、内容或应用程序(合称\"我们平台服务\")。无论用户是以\"访客\"(表示用户只是浏览我们平台网站)还是\"成员\"(表示用户已在我们平台注册并登录)的身份使用我们平台服务，均表示该用户同意遵守本使用协议。</p>\r\n<p>如果用户自愿成为我们平台成员并与其他成员交流(包括通过我们平台网站直接联系或通过我们平台各种服务而连接到的成员)，以及使用我们平台网站及其各种附加服务，请务必认真阅读本协议并在注册过程中表明同意接受本协议。本协议的内容包含我们平台关于接受我们平台服务和在我们平台网站上发布内容的规定、用户使用我们平台服务所享有的权利、承担的义务和对使用我们平台服务所受的限制、以及我们平台的隐私条款。如果用户选择使用某些我们平台服务，可能会收到要求其下载软件或内容的通知，和/或要求用户同意附加条款和条件的通知。除非用户选择使用的我们平台服务相关的附加条款和条件另有规定，附加的条款和条件都应被包含于本协议中。</p>\r\n<p>我们平台有权随时修改本协议文本中的任何条款。一旦我们平台对本协议进行修改,我们平台将会以公告形式发布通知。任何该等修改自发布之日起生效。如果用户在该等修改发布后继续使用我们平台服务，则表示该用户同意遵守对本协议所作出的该等修改。因此，请用户务必定期查阅本协议，以确保了解所有关于本协议的最新修改。如果用户不同意我们平台对本协议进行的修改，请用户离开我们平台网站并立即停止使用我们平台服务。同时，用户还应当删除个人档案并注销成员资格。</p>\r\n<p><strong>二、遵守法律</strong></p>\r\n<p>当使用我们平台服务时，用户同意遵守中华人民共和国(下称\"中国\")的相关法律法规，包括但不限于《中华人民共和国宪法》、《中华人民共和国合同法》、《中华人民共和国电信条例》、《互联网信息服务管理办法》、《互联网电子公告服务管理规定》、《中华人民共和国保守国家秘密法》、《全国人民代表大会常务委员会关于维护互联网安全的决定》、《中华人民共和国计算机信息系统安全保护条例》、《计算机信息网络国际联网安全保护管理办法》、《中华人民共和国著作权法》及其实施条例、《互联网著作权行政保护办法》等。用户只有在同意遵守所有相关法律法规和本协议时，才有权使用我们平台服务(无论用户是否有意访问或使用此服务)。请用户仔细阅读本协议并将其妥善保存。</p>\r\n<p><strong>三、用户帐号、密码及安全</strong></p>\r\n<p>用户应提供及时、详尽、准确的个人资料，并不断及时更新注册时提供的个人资料，保持其详尽、准确。所有用户输入的资料将引用为注册资料。我们平台不对因用户提交的注册信息不真实或未及时准确变更信息而引起的问题、争议及其后果承担责任。</p>\r\n<p>用户不应将其帐号、密码转让、出借或告知他人，供他人使用。如用户发现帐号遭他人非法使用，应立即通知我们平台。因黑客行为或用户的保管疏忽导致帐号、密码遭他人非法使用的，我们平台不承担任何责任。</p>\r\n<p><strong>四、隐私权政策</strong></p>\r\n<p>用户提供的注册信息及我们平台保留的用户所有资料将受到中国相关法律法规和我们平台《隐私权政策》的规范。《隐私权政策》构成本协议不可分割的一部分。</p>\r\n<p><strong>五、上传内容</strong></p>\r\n<p>用户通过任何我们平台提供的服务上传、张贴、发送(通过电子邮件或任何其它方式传送)的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任何其他材料(以下简称\"内容\"，包括用户个人的或个人创作的照片、声音、视频等)，无论系公开还是私下传播，均由用户和内容提供者承担责任，我们平台不对该等内容的正确性、完整性或品质作出任何保证。用户在使用我们平台服务时，可能会接触到令人不快、不适当或令人厌恶之内容，用户需在接受服务前自行做出判断。在任何情况下，我们平台均不为任何内容负责(包括但不限于任何内容的错误、遗漏、不准确或不真实)，亦不对通过我们平台服务上传、张贴、发送(通过电子邮件或任何其它方式传送)的内容衍生的任何损失或损害负责。我们平台在管理过程中发现或接到举报并进行初步调查后，有权依法停止任何前述内容的传播并采取进一步行动，包括但不限于暂停某些用户使用我们平台的全部或部分服务，保存有关记录，并向有关机关报告。</p>\r\n<p><strong>六、用户行为</strong></p>\r\n<p>用户在使用我们平台服务时，必须遵守中华人民共和国相关法律法规的规定，用户保证不会利用我们平台服务进行任何违法或不正当的活动，包括但不限于下列行为∶</p>\r\n<p>上传、展示、张贴或以其它方式传播含有下列内容之一的信息：</p>\r\n<p>反对宪法及其他法律的基本原则的;</p>\r\n<p>危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的;</p>\r\n<p>损害国家荣誉和利益的;</p>\r\n<p>煽动民族仇恨、民族歧视、破坏民族团结的;</p>\r\n<p>破坏国家宗教政策，宣扬邪教和封建迷信的;</p>\r\n<p>散布谣言，扰乱社会秩序，破坏社会稳定的;</p>\r\n<p>散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的;</p>\r\n<p>侮辱或者诽谤他人，侵害他人合法权利的;</p>\r\n<p>含有虚假、有害、胁迫、侵害他人隐私、骚扰、中伤、粗俗、猥亵、或其它道德上令人反感的内容;</p>\r\n<p>含有中国法律、法规、规章、条例以及任何具有法律效力的规范所限制或禁止的其它内容的;</p>\r\n<p>不得为任何非法目的而使用网络服务系统;</p>\r\n<p>用户同时保证不会利用我们平台服务从事以下活动：</p>\r\n<p>未经允许，进入计算机信息网络或者使用计算机信息网络资源的;</p>\r\n<p>未经允许，对计算机信息网络功能进行删除、修改或者增加的;</p>\r\n<p>未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的;故意制作、传播计算机病毒等破坏性程序的;</p>\r\n<p>其他危害计算机信息网络安全的行为。</p>\r\n<p>如用户在使用网络服务时违反任何上述规定，我们平台或经其授权者有权要求该用户改正或直接采取一切必要措施(包括但不限于更改、删除相关内容、暂停或终止相关用户使用我们平台服务)以减轻和消除该用户不当行为造成的影响。</p>\r\n<p>用户不得对我们平台服务的任何部分或全部以及通过我们平台取得的任何形式的信息，进行复制、拷贝、出售、转售或用于任何其它商业目的。</p>\r\n<p>用户须对自己在使用我们平台服务过程中的行为承担法律责任。用户承担法律责任的形式包括但不限于：停止侵害行为，向受到侵害者公开赔礼道歉，恢复受到侵害这的名誉，对受到侵害者进行赔偿。如果我们平台网站因某用户的非法或不当行为受到行政处罚或承担了任何形式的侵权损害赔偿责任，该用户应向我们平台进行赔偿(不低于我们平台向第三方赔偿的金额)并通过全国性的媒体向我们平台公开赔礼道歉。</p>\r\n<p><strong>七、知识产权和其他合法权益(包括但不限于名誉权、商誉等)</strong></p>\r\n<p>我们平台并不对用户发布到我们平台服务中的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任何其他材料(前文称为\"内容\")拥有任何所有权。在用户将内容发布到我们平台服务中后，用户将继续对内容享有权利，并且有权选择恰当的方式使用该等内容。如果用户在我们平台服务中或通过我们平台服务展示或发表任何内容，即表明该用户就此授予我们平台一个有限的许可以使我们平台能够合法使用、修改、复制、传播和出版此类内容。</p>\r\n<p>用户同意其已就在我们平台服务所发布的内容，授予我们平台可以免费的、永久有效的、不可撤销的、非独家的、可转授权的、在全球范围内对所发布内容进行使用、复制、修改、改写、改编、发行、翻译、创造衍生性著作的权利，及/或可以将前述部分或全部内容加以传播、表演、展示，及/或可以将前述部分或全部内容放入任何现在已知和未来开发出的以任何形式、媒体或科技承载的著作当中。</p>\r\n<p>用户声明并保证：用户对其在我们平台服务中或通过我们平台服务发布的内容拥有合法权利;用户在我们平台服务中或通过我们平台服务发布的内容不侵犯任何人的肖像权、隐私权、著作权、商标权、专利权、及其它合同权利。如因用户在我们平台服务中或通过我们平台服务发布的内容而需向其他任何人支付许可费或其它费用，全部由该用户承担。</p>\r\n<p>我们平台服务中包含我们平台提供的内容，包含用户和其他我们平台许可方的内容(下称\"我们平台的内容\")。我们平台的内容受《中华人民共和国著作权法》、《中华人民共和国商标法》、《中华人民共和国专利法》、《中华人民共和国反不正当竞争法》和其他相关法律法规的保护，我们平台拥有并保持对我们平台的内容和我们平台服务的所有权利。</p>\r\n<p><strong>八、国际使用之特别警告</strong></p>\r\n<p>用户已了解国际互联网的无国界性，同意遵守所有关于网上行为、内容的法律法规。用户特别同意遵守有关从中国或用户所在国家或地区输出信息所可能涉及、适用的全部法律法规。</p>\r\n<p><strong>九、赔偿</strong></p>\r\n<p>由于用户通过我们平台服务上传、张贴、发送或传播的内容，或因用户与本服务连线，或因用户违反本使用协议，或因用户侵害他人任何权利而导致任何第三人向我们平台提出赔偿请求，该用户同意赔偿我们平台及其股东、子公司、关联企业、代理人、品牌共有人或其它合作伙伴相应的赔偿金额(包括我们平台支付的律师费等)，以使我们平台的利益免受损害。</p>\r\n<p><strong>十、关于使用及储存的一般措施</strong></p>\r\n<p>用户承认我们平台有权制定关于服务使用的一般措施及限制，包括但不限于我们平台服务将保留用户的电子邮件信息、用户所张贴内容或其它上载内容的最长保留期间、用户一个帐号可收发信息的最大数量、用户帐号当中可收发的单个信息的大小、我们平台服务器为用户分配的最大磁盘空间，以及一定期间内用户使用我们平台服务的次数上限(及每次使用时间之上限)。通过我们平台服务存储或传送的任何信息、通讯资料和其它任何内容，如被删除或未予储存，用户同意我们平台毋须承担任何责任。用户亦同意，超过一年未使用的帐号，我们平台有权关闭。我们平台有权依其自行判断和决定，随时变更相关一般措施及限制。</p>\r\n<p><strong>十一、服务的修改</strong></p>\r\n<p>用户了解并同意，无论通知与否，我们平台有权于任何时间暂时或永久修改或终止部分或全部我们平台服务，对此，我们平台对用户和任何第三人均无需承担任何责任。用户同意，所有上传、张贴、发送到我们平台的内容，我们平台均无保存义务，用户应自行备份。我们平台不对任何内容丢失以及用户因此而遭受的相关损失承担责任。</p>\r\n<p><strong>十二、终止服务</strong></p>\r\n<p>用户同意我们平台可单方面判断并决定，如果用户违反本使用协议或用户长时间未能使用其帐号，我们平台可以终止该用户的密码、帐号或某些服务的使用，并可将该用户在我们平台服务中留存的任何内容加以移除或删除。我们平台亦可基于自身考虑，在通知或未通知之情形下，随时对该用户终止部分或全部服务。用户了解并同意依本使用协议，无需进行事先通知，我们平台可在发现任何不适宜内容时，立即关闭或删除该用户的帐号及其帐号中所有相关信息及文件，并暂时或永久禁止该用户继续使用前述文件或帐号。</p>\r\n<p><strong>十三、与广告商进行的交易</strong></p>\r\n<p>用户通过我们平台服务与广告商进行任何形式的通讯或商业往来，或参与促销活动(包括相关商品或服务的付款及交付)，以及达成的其它任何条款、条件、保证或声明，完全是用户与广告商之间的行为。除有关法律法规明文规定要求我们平台承担责任外，用户因前述任何交易、沟通等而遭受的任何性质的损失或损害，我们平台均不予负责。</p>\r\n<p><strong>十四、链接</strong></p>\r\n<p>用户了解并同意，对于我们平台服务或第三人提供的其它网站或资源的链接是否可以利用，我们平台不予负责;存在或源于此类网站或资源的任何内容、广告、产品或其它资料，我们平台亦不保证或负责。因使用或信赖任何此类网站或资源发布的或经由此类网站或资源获得的任何商品、服务、信息，如对用户造成任何损害，我们平台不负任何直接或间接责任。</p>\r\n<p><strong>十五、禁止商业行为</strong></p>\r\n<p>用户同意不对我们平台服务的任何部分或全部以及用户通过我们平台的服务取得的任何物品、服务、信息等，进行复制、拷贝、出售、转售或用于任何其它商业目的。</p>\r\n<p><strong>十六、我们平台的专属权利</strong></p>\r\n<p>用户了解并同意，我们平台服务及其所使用的相关软件(以下简称\"服务软件\")含有受到相关知识产权及其它法律保护的专有保密资料。用户同时了解并同意，经由我们平台服务或广告商向用户呈现的赞助广告或信息所包含之内容，亦可能受到著作权、商标、专利等相关法律的保护。未经我们平台或广告商书面授权，用户不得修改、出售、传播部分或全部服务内容或软件，或加以制作衍生服务或软件。我们平台仅授予用户个人非专属的使用权，用户不得(也不得允许任何第三人)复制、修改、创作衍生著作，或通过进行还原工程、反向组译及其它方式破译原代码。用户也不得以转让、许可、设定任何担保或其它方式移转服务和软件的任何权利。用户同意只能通过由我们平台所提供的界面而非任何其它方式使用我们平台服务。</p>\r\n<p><strong>十七、担保与保证</strong></p>\r\n<p>我们平台使用协议的任何规定均不会免除因我们平台造成用户人身伤害或因故意造成用户财产损失而应承担的任何责任。</p>\r\n<p>用户使用我们平台服务的风险由用户个人承担。我们平台对服务不提供任何明示或默示的担保或保证，包括但不限于商业适售性、特定目的的适用性及未侵害他人权利等的担保或保证。</p>\r\n<p>我们平台亦不保证以下事项：</p>\r\n<p>服务将符合用户的要求;</p>\r\n<p>服务将不受干扰、及时提供、安全可靠或不会出错;</p>\r\n<p>使用服务取得的结果正确可靠;</p>\r\n<p>用户经由我们平台服务购买或取得的任何产品、服务、资讯或其它信息将符合用户的期望，且软件中任何错误都将得到更正。</p>\r\n<p>用户应自行决定使用我们平台服务下载或取得任何资料且自负风险，因任何资料的下载而导致的用户电脑系统损坏或数据流失等后果，由用户自行承担。</p>\r\n<p>用户经由我们平台服务获知的任何建议或信息(无论书面或口头形式)，除非使用协议有明确规定，将不构成我们平台对用户的任何保证。</p>\r\n<p><strong>十八、责任限制</strong></p>\r\n<p>用户明确了解并同意，基于以下原因而造成的任何损失，我们平台均不承担任何直接、间接、附带、特别、衍生性或惩罚性赔偿责任(即使我们平台事先已被告知用户或第三方可能会产生相关损失)：</p>\r\n<p>我们平台服务的使用或无法使用;</p>\r\n<p>通过我们平台服务购买、兑换、交换取得的任何商品、数据、信息、服务、信息，或缔结交易而发生的成本;</p>\r\n<p>用户的传输或数据遭到未获授权的存取或变造;</p>\r\n<p>任何第三方在我们平台服务中所作的声明或行为;</p>\r\n<p>与我们平台服务相关的其它事宜，但本使用协议有明确规定的除外。</p>\r\n<p><strong>十九、一般性条款</strong></p>\r\n<p>本使用协议构成用户与我们平台之间的正式协议，并用于规范用户的使用行为。在用户使用我们平台服务、使用第三方提供的内容或软件时，在遵守本协议的基础上，亦应遵守与该等服务、内容、软件有关附加条款及条件。</p>\r\n<p>本使用协以及用户与我们平台之间的关系，均受到中华人民共和国法律管辖。</p>\r\n<p>用户与我们平台就服务本身、本使用协议或其它有关事项发生的争议，应通过友好协商解决。协商不成的，应向北京市东城区人民法院提起诉讼。</p>\r\n<p>我们平台未行使或执行本使用协议设定、赋予的任何权利，不构成对该等权利的放弃。</p>\r\n<p>本使用协议中的任何条款因与中华人民共和国法律相抵触而无效，并不影响其它条款的效力。</p>\r\n<p>本使用协议的标题仅供方便阅读而设，如与协议内容存在矛盾，以协议内容为准。</p>\r\n<p><strong>二十、举报</strong></p>\r\n<p>如用户发现任何违反本服务条款的情事，请及时通知我们平台。</p>\r\n</div>','term','',1,1),(2,'服务介绍','','intro','',1,1),(3,'隐私策略','','privacy','',1,1),(4,'关于我们','','about','',1,1),(5,'官方微博','','','http://weibo.com/vitakung',0,1),(7,'撰写指南','','write_guide','',1,1);
/*!40000 ALTER TABLE `fanwe_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_index_image`
--

DROP TABLE IF EXISTS `fanwe_index_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_index_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示首页轮播 1表示产品页轮播 2表示股权轮播',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='//首页图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_index_image`
--

LOCK TABLES `fanwe_index_image` WRITE;
/*!40000 ALTER TABLE `fanwe_index_image` DISABLE KEYS */;
INSERT INTO `fanwe_index_image` VALUES (5,'./public/attachment/201211/07/10/5099c97ad9f82.gif','http://qodoculture.com',5,'方维众筹',2),(6,'./public/attachment/201211/07/10/5099c984946c3.jpg','http://mollygogo.com',4,'4',1),(7,'./public/attachment/201602/29/01/56d32a416acfc.jpg','http://vitakung.com',1,'1',0),(8,'./public/attachment/201602/29/01/56d32a6957bcf.png','http://molly.net.cn',2,'2',0),(9,'./public/attachment/201602/29/01/56d32aad31975.jpg','http://t5.mollygogo.com',3,'3',0);
/*!40000 ALTER TABLE `fanwe_index_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_investment_list`
--

DROP TABLE IF EXISTS `fanwe_investment_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_investment_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(11) NOT NULL DEFAULT '0' COMMENT '投资的类型 0 表示 询价，1表示领投，2表示跟投,3表示追加的金额 , 4转让中的股份 , 5转让获得的股份 ,6取消转让的股份',
  `money` decimal(20,2) NOT NULL COMMENT '投资的金额',
  `stock_value` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '估指',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0表示 未来审核，1表示同意，2表示不同意',
  `introduce` text NOT NULL COMMENT '领投人的个人简介',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `deal_id` int(11) NOT NULL COMMENT '股权众筹ID',
  `cates` text NOT NULL COMMENT '分类信息',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `investment_reason` text NOT NULL COMMENT '投资请求',
  `funding_to_help` text NOT NULL COMMENT '资金帮助',
  `investor_money_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '投资金额0 表示未审核 1表示审核通过 2表示审核拒绝 3表示已支付投资成功 4表示未按时间支付，违约',
  `order_id` int(11) NOT NULL COMMENT '订单号',
  `is_partner` tinyint(11) NOT NULL COMMENT '0表示无状态 1表示愿意承担企业合伙人 2表示不愿意承担企业合伙人',
  `leader_moban` text NOT NULL COMMENT '尽职调查和条款清单模板',
  `leader_help` text NOT NULL COMMENT '他为创业者还能提供的其它帮助',
  `leader_for_team` text NOT NULL COMMENT '对创业团队评价',
  `leader_for_project` text NOT NULL COMMENT '对创业项目评价',
  `send_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示未发送 1发送成功',
  `detailed_information` text NOT NULL COMMENT '详细资料',
  `num` int(11) NOT NULL COMMENT '份数',
  `stock_transfer_value` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '转让价值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_investment_list`
--

LOCK TABLES `fanwe_investment_list` WRITE;
/*!40000 ALTER TABLE `fanwe_investment_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_investment_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_invite`
--

DROP TABLE IF EXISTS `fanwe_invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '被邀请人的id',
  `invite_id` int(11) NOT NULL COMMENT '邀请的id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '判断是否接受邀请，默认0表示待确认，1表示接受邀请，2拒绝邀请',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `job` varchar(255) NOT NULL COMMENT '职位',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `type` tinyint(1) NOT NULL COMMENT '邀请的类型 0 表示邀请人是 投资机构 1表示邀请人是公司 3 创始团队来的邀请 4 团队成员来的邀请  5 过往成员来的邀请  6 过往投资方 ',
  `organization_name` varchar(255) NOT NULL COMMENT '机构/公司名称',
  `job_start_time` int(11) NOT NULL COMMENT '任职开始时间',
  `job_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '0 表示至今 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_invite`
--

LOCK TABLES `fanwe_invite` WRITE;
/*!40000 ALTER TABLE `fanwe_invite` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai`
--

DROP TABLE IF EXISTS `fanwe_licai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `licai_sn` varchar(50) NOT NULL COMMENT '编号',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '发起人【发起机构】',
  `img` varchar(255) NOT NULL COMMENT '项目图片',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `re_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0元;1新品上架;2当月畅销;3:本周畅销;4:限时抢购;',
  `begin_buy_date` date NOT NULL COMMENT '购买开始时间',
  `end_buy_date` date NOT NULL COMMENT '购买结束时间',
  `end_date` date NOT NULL COMMENT '项目结束时间',
  `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '起购金额',
  `max_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单笔最大购买限额',
  `begin_interest_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '【0:当日生效，1:次日生效，2:下个工作日生效,3下二个工作日】',
  `product_size` varchar(255) DEFAULT NULL COMMENT '产品规模',
  `risk_rank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '风险等级（2高、1中、0低）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1有效、0无效',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '理财类型（0余额宝、1固定定存、2浮动定存;3票据、4基金）',
  `description` text NOT NULL COMMENT '理财详情',
  `purchasing_time` varchar(255) DEFAULT NULL COMMENT '赎回到账时间描述',
  `rule_info` text COMMENT '规则',
  `is_trusteeship` tinyint(1) DEFAULT NULL COMMENT '是否托管 0是 1否',
  `average_income_rate` decimal(8,4) NOT NULL DEFAULT '0.0000' COMMENT 'type=0七日平均(年)收益率;type=1近三个月收益率【动态计算】',
  `per_million_revenue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '每万元收益【动态计算】',
  `subscribing_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计成交总额',
  `redeming_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计被赎回',
  `is_deposit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否托管;1:托管;0:非托管',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `brief` varchar(255) DEFAULT NULL COMMENT '简介',
  `net_value` decimal(10,2) DEFAULT '0.00' COMMENT '最新净值',
  `fund_key` varchar(50) DEFAULT NULL COMMENT '关连的基金编号',
  `fund_type_id` int(10) NOT NULL DEFAULT '0' COMMENT '基金种类',
  `fund_brand_id` int(10) NOT NULL DEFAULT '0' COMMENT '基金品牌',
  `bank_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行',
  `begin_interest_date` date DEFAULT NULL COMMENT '起息时间',
  `time_limit` int(10) DEFAULT NULL COMMENT '理财期限',
  `review_type` tinyint(1) DEFAULT NULL COMMENT '赎回到账方式: 0,发起人审核   1,网站和发起人审核 2，自动审核',
  `total_people` int(10) DEFAULT NULL COMMENT '参与人数',
  `service_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '成交服务费',
  `licai_status` tinyint(1) DEFAULT NULL COMMENT '理财状态 0：预热期 1：理财期 2：提前结束 3已到期',
  `send_type` tinyint(1) DEFAULT NULL COMMENT '发放款项类型  0：自动  1：手动',
  `is_send` tinyint(1) DEFAULT NULL COMMENT '是否发放 0：否 1：是',
  `investment_adviser` varchar(255) DEFAULT NULL,
  `profit_way` varchar(255) DEFAULT NULL COMMENT '获取收益方式',
  `scope` varchar(255) DEFAULT NULL COMMENT '利率范围',
  `platform_rate` decimal(10,4) DEFAULT NULL COMMENT '平台收益(余额宝)',
  `site_buy_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '购买手续费(余额宝)',
  `redemption_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '赎回手续费(余额宝)',
  `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放理财时资金不足是否允许垫付  0表示 不允许垫付  1 表示 允许垫付',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai`
--

LOCK TABLES `fanwe_licai` WRITE;
/*!40000 ALTER TABLE `fanwe_licai` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_advance`
--

DROP TABLE IF EXISTS `fanwe_licai_advance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_advance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `redempte_id` int(11) NOT NULL COMMENT '赎回ID',
  `user_id` int(11) NOT NULL COMMENT '申请人ID',
  `user_name` varchar(255) NOT NULL COMMENT '申请用户名',
  `money` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '赎回本金',
  `earn_money` decimal(10,2) NOT NULL COMMENT '收益金额',
  `fee` decimal(10,2) NOT NULL COMMENT '赎回手续费',
  `organiser_fee` decimal(10,2) NOT NULL,
  `advance_money` decimal(10,2) NOT NULL COMMENT '垫付金额',
  `real_money` decimal(10,2) NOT NULL COMMENT '发起人账户金额和冻结资金被扣的金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0表示未处理 1表示通过',
  `type` tinyint(1) NOT NULL COMMENT '0 预热期赎回 1.起息时间违约赎回 2.正常到期赎回',
  `create_date` date NOT NULL COMMENT '申请时间',
  `update_date` date NOT NULL COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_advance`
--

LOCK TABLES `fanwe_licai_advance` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_advance` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_advance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_bank`
--

DROP TABLE IF EXISTS `fanwe_licai_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_bank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='基金种类：\r\n全部 货币型 股票型 债券型 混合型 理财型 指数型 QDII 其他型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_bank`
--

LOCK TABLES `fanwe_licai_bank` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_dealshow`
--

DROP TABLE IF EXISTS `fanwe_licai_dealshow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_dealshow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licai_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_dealshow`
--

LOCK TABLES `fanwe_licai_dealshow` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_dealshow` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_dealshow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_fund_brand`
--

DROP TABLE IF EXISTS `fanwe_licai_fund_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_fund_brand` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='基金品牌：\r\n全部 嘉实 鹏华 易方达 国泰 南方 建信 招商 工银瑞信 海富通 华商 中邮创业 长盛 东方\r\n';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_fund_brand`
--

LOCK TABLES `fanwe_licai_fund_brand` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_fund_brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_fund_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_fund_type`
--

DROP TABLE IF EXISTS `fanwe_licai_fund_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_fund_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='基金种类：\r\n全部 货币型 股票型 债券型 混合型 理财型 指数型 QDII 其他型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_fund_type`
--

LOCK TABLES `fanwe_licai_fund_type` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_fund_type` DISABLE KEYS */;
INSERT INTO `fanwe_licai_fund_type` VALUES (1,'货币型',1,0),(2,'股票型',1,0),(3,'债券型',1,0),(4,'混合型',1,0),(5,'理财型',1,0),(6,'标准',1,0),(7,'QDII',1,0),(8,'其他型',1,0),(9,'中欧',1,0);
/*!40000 ALTER TABLE `fanwe_licai_fund_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_history`
--

DROP TABLE IF EXISTS `fanwe_licai_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `licai_id` varchar(50) NOT NULL COMMENT '编号',
  `history_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '购买金额起',
  `net_value` decimal(10,2) NOT NULL COMMENT '当日净利',
  `rate` decimal(7,4) NOT NULL COMMENT '利率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='基金净值列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_history`
--

LOCK TABLES `fanwe_licai_history` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_holiday`
--

DROP TABLE IF EXISTS `fanwe_licai_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_holiday` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL COMMENT '年',
  `holiday` date NOT NULL COMMENT '假日',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_holiday`
--

LOCK TABLES `fanwe_licai_holiday` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_holiday` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_interest`
--

DROP TABLE IF EXISTS `fanwe_licai_interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_interest` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `licai_id` varchar(50) NOT NULL COMMENT '编号',
  `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买金额起',
  `max_money` decimal(10,2) NOT NULL COMMENT '购买金额起',
  `interest_rate` decimal(7,4) NOT NULL COMMENT '利息率',
  `buy_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '原购买手续费',
  `site_buy_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '网站购买手续费',
  `redemption_fee_rate` decimal(10,4) DEFAULT NULL COMMENT '赎回手续费',
  `before_rate` decimal(10,4) DEFAULT NULL COMMENT '预热期利率',
  `before_breach_rate` decimal(10,4) DEFAULT NULL COMMENT '预热期违约利率',
  `breach_rate` decimal(10,4) DEFAULT NULL COMMENT '正常利息 违约收益率',
  `platform_rate` decimal(10,4) DEFAULT NULL COMMENT '平台收益率',
  `freeze_bond_rate` decimal(10,4) DEFAULT NULL,
  `platform_breach_rate` decimal(10,4) DEFAULT NULL COMMENT '用户违约网站收益',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COMMENT='利率列表【不同投资金额，可以获得不同的利率】';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_interest`
--

LOCK TABLES `fanwe_licai_interest` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_interest` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_interest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_order`
--

DROP TABLE IF EXISTS `fanwe_licai_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licai_id` int(11) NOT NULL COMMENT '理财产品ID',
  `user_id` int(11) NOT NULL COMMENT '购买用户的id',
  `user_name` varchar(50) NOT NULL,
  `money` decimal(10,2) NOT NULL COMMENT '购买金额',
  `status` tinyint(1) NOT NULL COMMENT '0：未支付 1：已支付 2、部分赎回 3、已完结',
  `freeze_bond_rate` decimal(10,4) NOT NULL COMMENT '冻结保证金费率',
  `freeze_bond` decimal(10,2) NOT NULL COMMENT '冻结保证金',
  `pay_money` decimal(10,2) NOT NULL COMMENT '发放金额',
  `status_time` datetime NOT NULL COMMENT '处理时间',
  `create_time` datetime NOT NULL COMMENT '购买时间',
  `create_date` date NOT NULL COMMENT '购买年月日',
  `site_buy_fee_rate` decimal(10,4) NOT NULL COMMENT '实际申购费率',
  `site_buy_fee` decimal(10,2) NOT NULL COMMENT '实际申购费',
  `redemption_fee_rate` decimal(10,4) NOT NULL COMMENT '赎回手续费',
  `before_interest_date` date NOT NULL COMMENT '预热开始时间',
  `before_interest_enddate` date NOT NULL COMMENT '预热结束时间',
  `before_rate` decimal(10,4) NOT NULL COMMENT '预热利率',
  `before_interest` decimal(10,2) NOT NULL COMMENT '预热利息',
  `is_before_pay` tinyint(1) NOT NULL COMMENT '是否已经支付预热期手续费',
  `before_breach_rate` decimal(10,4) NOT NULL COMMENT '预热期违约利率',
  `begin_interest_type` tinyint(1) NOT NULL COMMENT '【0:当日生效，1:次日生效，2:下个工作日生效,3下二个工作日】',
  `begin_interest_date` date NOT NULL COMMENT '起息时间YMD',
  `interest_rate` decimal(10,4) NOT NULL COMMENT '利息率',
  `breach_rate` decimal(10,4) NOT NULL COMMENT '正常利息 违约收益率',
  `end_interest_date` date NOT NULL COMMENT '结束时间YMD',
  `service_fee_rate` decimal(10,4) NOT NULL COMMENT '成交服务费率',
  `service_fee` decimal(10,2) NOT NULL COMMENT '成交服务费',
  `redempte_money` decimal(10,2) DEFAULT '0.00' COMMENT '赎回金额',
  `gross` decimal(10,2) DEFAULT '0.00' COMMENT '毛利',
  `gross_margins` decimal(10,4) DEFAULT '0.0000' COMMENT '毛利率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=147 DEFAULT CHARSET=utf8 COMMENT='理财订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_order`
--

LOCK TABLES `fanwe_licai_order` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_recommend`
--

DROP TABLE IF EXISTS `fanwe_licai_recommend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_recommend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `licai_id` varchar(50) NOT NULL COMMENT '编号',
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `img` varchar(255) NOT NULL COMMENT '项目图片',
  `brief` varchar(255) DEFAULT NULL COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='个性推荐';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_recommend`
--

LOCK TABLES `fanwe_licai_recommend` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_recommend` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_recommend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_licai_redempte`
--

DROP TABLE IF EXISTS `fanwe_licai_redempte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_licai_redempte` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `user_id` int(11) NOT NULL COMMENT '申请人ID',
  `user_name` varchar(255) NOT NULL COMMENT '申请用户名',
  `money` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '赎回本金',
  `earn_money` decimal(10,2) NOT NULL COMMENT '收益金额',
  `fee` decimal(10,2) NOT NULL COMMENT '赎回手续费',
  `organiser_fee` decimal(10,2) NOT NULL COMMENT '平台收益',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0表示未赎回 1表示已赎回 2表示拒绝 3表示取消赎回',
  `type` tinyint(1) NOT NULL COMMENT '0 预热期赎回 1.起息时间违约赎回 2.正常到期赎回3.预热期正常赎回 ',
  `create_date` date NOT NULL COMMENT '申请时间',
  `update_date` date NOT NULL COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_licai_redempte`
--

LOCK TABLES `fanwe_licai_redempte` WRITE;
/*!40000 ALTER TABLE `fanwe_licai_redempte` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_licai_redempte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_link`
--

DROP TABLE IF EXISTS `fanwe_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `count` int(11) NOT NULL,
  `show_index` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COMMENT='//链接';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_link`
--

LOCK TABLES `fanwe_link` WRITE;
/*!40000 ALTER TABLE `fanwe_link` DISABLE KEYS */;
INSERT INTO `fanwe_link` VALUES (128,'猫力中国',14,'http://molly.net.cn',1,1,'','',0,1),(129,'VK维客',14,'http://vitakung.com',1,2,'','',0,1),(130,'KK大公馆',14,'http://kungkuan.com',1,3,'','',0,1),(131,'宫网',14,'http://gong.news',1,4,'','',0,1),(132,'猫力网',14,'http://mollygogo.com',1,5,'','',0,1),(133,'qodo取道文化',14,'http://qodoculture.com',1,6,'','',0,1),(134,'VITAGONG宫伟',14,'http://vitagong.com',1,7,'','',0,1),(135,'MW猫力珠宝',14,'http://mollywang.com',1,8,'','',0,1),(136,'qodo取道中国',14,'http://qodo.com.cn',1,9,'','',0,1),(137,'36氪股权众筹',19,'https://z.36kr.com/projects',1,10,'./public/attachment/201602/29/02/56d3381803d09.png','',0,1),(138,'软银中国',21,'http://www.sbcvc.com/',1,11,'./public/attachment/201602/29/02/56d33860b71b0.png','',0,1),(139,'纪源资本',21,'http://www.ggvc.com/',1,12,'./public/attachment/201602/29/02/56d3388e94c5f.png','',0,1),(140,'红杉资本',21,'http://www.sequoiacap.cn/zh/',1,13,'./public/attachment/201602/29/02/56d338b12625a.png','',0,1),(141,'经纬中国',21,'http://www.matrixpartners.com.cn/',1,14,'./public/attachment/201602/29/02/56d338d8d9701.png','',0,1),(142,'IDG',21,'http://www.idgvc.com/',1,15,'./public/attachment/201602/29/02/56d338f76acfc.png','',0,1),(143,'GOBI',21,'http://www.gobivc.com/',1,16,'./public/attachment/201602/29/02/56d3391b03d09.png','',0,1),(144,'真格基金',21,'http://www.zhenfund.com/',1,17,'./public/attachment/201602/29/02/56d33958501bd.png','',0,1),(145,'京东金融',19,'http://z.jd.com/new.html',1,18,'./public/attachment/201602/29/02/56d339aa0f424.png','',0,1),(146,'天使客',19,'https://www.angelclub.com/',1,19,'./public/attachment/201602/29/02/56d339fc7a120.png','',0,1),(147,'天使街',19,'http://www.tianshijie.com.cn/',1,20,'./public/attachment/201602/29/02/56d33a4d40d99.png','',0,1),(148,'企e融',19,'http://www.71fi.com/',1,21,'./public/attachment/201602/29/02/56d33aadd59f8.png','',0,1),(149,'融e帮',19,'http://rongebang.com/',1,22,'./public/attachment/201602/29/02/56d33af866ff3.png','',0,1),(150,'第五创',19,'http://www.d5ct.com/',1,23,'./public/attachment/201602/29/02/56d33b2c44aa2.jpg','',0,1),(151,'众筹客',18,'http://www.zhongchouke.com/',1,24,'./public/attachment/201602/29/02/56d33bf91312d.png','',0,1),(152,'淘宝众筹',18,'https://izhongchou.taobao.com/index.htm',1,25,'./public/attachment/201602/29/02/56d33c5d31975.png','',0,1),(153,'众筹网',18,'http://www.zhongchou.com/',1,26,'./public/attachment/201602/29/02/56d33cb4b34a7.png','',0,1),(154,'汇梦公社',20,'http://www.hmzone.com/',1,27,'./public/attachment/201602/29/02/56d33d5800000.png','',0,1),(155,'咱们众筹',20,'http://www.zamazc.com/',1,28,'./public/attachment/201602/29/02/56d33da3b71b0.png','',0,1),(156,'大家投',20,'http://www.dajiatou.com/',1,29,'./public/attachment/201602/29/02/56d33e01f0537.png','',0,1),(157,'大家筹',20,'http://www.dajiachou.com/',1,30,'./public/attachment/201602/29/02/56d33e30bebc2.jpg','',0,1),(158,'人人合伙',20,'http://www.renrenhehuo.cn/index.ac',1,31,'./public/attachment/201602/29/02/56d33e736acfc.png','',0,1),(159,'360淘金',20,'https://t.360.cn/',1,32,'./public/attachment/201602/29/02/56d33ed05b8d8.png','',0,1),(160,'蚂蚁天使',20,'https://www.mayiangel.com/index.htm',1,33,'./public/attachment/201602/29/02/56d33f1440d99.png','',0,1);
/*!40000 ALTER TABLE `fanwe_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_link_group`
--

DROP TABLE IF EXISTS `fanwe_link_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_link_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '友情链接分组名称',
  `sort` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 文字描述 1图片描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='//链接组';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_link_group`
--

LOCK TABLES `fanwe_link_group` WRITE;
/*!40000 ALTER TABLE `fanwe_link_group` DISABLE KEYS */;
INSERT INTO `fanwe_link_group` VALUES (14,'友情链接',1,1,0),(18,'产品众筹',2,1,1),(19,'股权众筹',3,1,1),(20,'其他众筹',4,1,1),(21,'风投在线',5,1,1);
/*!40000 ALTER TABLE `fanwe_link_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_log`
--

DROP TABLE IF EXISTS `fanwe_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_info` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `log_admin` int(11) NOT NULL,
  `log_ip` varchar(255) NOT NULL,
  `log_status` tinyint(1) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2546 DEFAULT CHARSET=utf8 COMMENT='//记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_log`
--

LOCK TABLES `fanwe_log` WRITE;
/*!40000 ALTER TABLE `fanwe_log` DISABLE KEYS */;
INSERT INTO `fanwe_log` VALUES (2380,'发起项目更新成功',1417975607,1,'127.0.0.1',1,'Article','update'),(2381,'TPL_MAIL_INVESTOR_PAY_STATUS更新成功',1422475811,1,'127.0.0.1',1,'MsgTemplate','update'),(2382,'admin登录成功',1434136365,1,'127.0.0.1',1,'Public','do_login'),(2383,'我有项目更新成功',1434136446,1,'127.0.0.1',1,'ArticleCate','update'),(2384,'合作方式更新成功',1434136453,1,'127.0.0.1',1,'ArticleCate','update'),(2385,'媒体报道更新成功',1434136459,1,'127.0.0.1',1,'ArticleCate','update'),(2386,'活动报名更新成功',1434136466,1,'127.0.0.1',1,'ArticleCate','update'),(2387,'新手帮助更新成功',1434136474,1,'127.0.0.1',1,'ArticleCate','update'),(2388,'关于我们更新成功',1434136480,1,'127.0.0.1',1,'ArticleCate','update'),(2389,'站点申明更新成功',1434136488,1,'127.0.0.1',1,'ArticleCate','update'),(2390,'发起项目更新成功',1434136507,1,'127.0.0.1',1,'Article','update'),(2391,'会员注册更新成功',1434136523,1,'127.0.0.1',1,'Article','update'),(2392,'版权申明更新成功',1434136537,1,'127.0.0.1',1,'Article','update'),(2393,'项目规范更新成功',1434136547,1,'127.0.0.1',1,'Article','update'),(2394,'【媒体报道】众筹平台助“印象”打造专业川菜连锁品牌更新成功',1434136558,1,'127.0.0.1',1,'Article','update'),(2395,'使用条款更新成功',1434136572,1,'127.0.0.1',1,'Article','update'),(2396,'【活动报名】10.21第一期天使合投SHOW热辣登场！更新成功',1434136582,1,'127.0.0.1',1,'Article','update'),(2397,'常见问题更新成功',1434136594,1,'127.0.0.1',1,'Article','update'),(2398,'联系方式更新成功',1434136603,1,'127.0.0.1',1,'Article','update'),(2399,'关于我们更新成功',1434136614,1,'127.0.0.1',1,'Article','update'),(2400,'流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！更新成功',1434136659,1,'127.0.0.1',1,'Deal','update'),(2401,'短片电影《Blind Love》更新成功',1434136671,1,'127.0.0.1',1,'Deal','update'),(2402,'拥有自己的咖啡馆更新成功',1434136682,1,'127.0.0.1',1,'Deal','update'),(2403,'原创DIY桌面游戏《功夫》《黄金密码》期待您的支持更新成功',1434136697,1,'127.0.0.1',1,'Deal','update'),(2404,'58_is_special启用成功',1434136705,1,'127.0.0.1',1,'Deal','toogle_status'),(2405,'56_is_special启用成功',1434136708,1,'127.0.0.1',1,'Deal','toogle_status'),(2406,'admin登录成功',1438559152,1,'127.0.0.1',1,'Public','do_login'),(2407,'admin登录成功',1450915035,1,'127.0.0.1',1,'Public','do_login'),(2408,'admin登录成功',1453143765,1,'::1',1,'Public','do_login'),(2409,'测试管理员更新成功',1453143872,1,'::1',1,'Role','update'),(2410,'测试管理员更新成功',1453143896,1,'::1',1,'Role','update'),(2411,'更新系统配置',1453144048,1,'::1',1,'Conf','update'),(2412,'测试管理员更新成功',1453144423,1,'::1',1,'Role','update'),(2413,'测试管理员更新成功',1453144510,1,'::1',1,'Role','update'),(2414,'测试管理员更新成功',1453145592,1,'::1',1,'Role','update'),(2415,'test添加成功',1453145611,1,'::1',1,'Admin','insert'),(2416,'test登录成功',1453145662,4,'::1',1,'Public','do_login'),(2417,'测试管理员更新成功',1453145703,1,'::1',1,'Role','update'),(2418,'更新系统配置',1455585763,1,'127.0.0.1',1,'Conf','update'),(2419,'更新系统配置',1455585785,1,'127.0.0.1',1,'Conf','update'),(2420,'更新系统配置',1455585798,1,'127.0.0.1',1,'Conf','update'),(2421,'更新系统配置',1455585864,1,'127.0.0.1',1,'Conf','update'),(2422,'更新系统配置',1455585895,1,'127.0.0.1',1,'Conf','update'),(2423,'所有项目更新成功',1455586841,1,'127.0.0.1',1,'Nav','update'),(2424,'产品众筹更新成功',1455586862,1,'127.0.0.1',1,'Nav','update'),(2425,'公益模块更新成功',1455586877,1,'127.0.0.1',1,'Nav','update'),(2426,'股权众筹更新成功',1455586942,1,'127.0.0.1',1,'Nav','update'),(2427,'经典项目更新成功',1455586973,1,'127.0.0.1',1,'Nav','update'),(2428,'股权交易添加成功',1455587010,1,'127.0.0.1',1,'Nav','insert'),(2429,'积分商城添加成功',1455587152,1,'127.0.0.1',1,'Nav','insert'),(2430,'动态最新添加成功',1455587166,1,'127.0.0.1',1,'Nav','insert'),(2431,'动态最新彻底删除成功',1455587206,1,'127.0.0.1',1,'Nav','foreverdelete'),(2432,'新手帮助添加成功',1455587237,1,'127.0.0.1',1,'Nav','insert'),(2433,'天使投资人添加成功',1455587292,1,'127.0.0.1',1,'Nav','insert'),(2434,'股权交易排序修改成功',1455587352,1,'127.0.0.1',1,'Nav','set_sort'),(2435,'文章更新成功',1455587390,1,'127.0.0.1',1,'Nav','update'),(2436,'路演资讯排序修改成功',1455587417,1,'127.0.0.1',1,'Nav','set_sort'),(2437,'积分商城排序修改成功',1455587421,1,'127.0.0.1',1,'Nav','set_sort'),(2438,'天使投资人排序修改成功',1455587424,1,'127.0.0.1',1,'Nav','set_sort'),(2439,'新手帮助排序修改成功',1455587431,1,'127.0.0.1',1,'Nav','set_sort'),(2440,'最新动态排序修改成功',1455587431,1,'127.0.0.1',1,'Nav','set_sort'),(2441,'最新动态排序修改成功',1455587437,1,'127.0.0.1',1,'Nav','set_sort'),(2442,'admin登录成功',1456649913,1,'124.202.243.78',1,'Public','do_login'),(2443,'更新系统配置',1456649955,1,'124.202.243.78',1,'Conf','update'),(2444,'更新系统配置',1456650012,1,'124.202.243.78',1,'Conf','update'),(2445,'更新系统配置',1456650021,1,'124.202.243.78',1,'Conf','update'),(2446,'更新系统配置',1456650050,1,'124.202.243.78',1,'Conf','update'),(2447,'更新系统配置',1456650063,1,'124.202.243.78',1,'Conf','update'),(2448,'官方微博更新成功',1456650099,1,'124.202.243.78',1,'Help','update'),(2449,'彻底删除成功',1456650145,1,'124.202.243.78',1,'MAdv','foreverdelete'),(2450,'创业者的曙光添加成功',1456650281,1,'124.202.243.78',1,'MAdv','insert'),(2451,'VK维客添加成功',1456650302,1,'124.202.243.78',1,'MAdv','insert'),(2452,'vitakung添加成功',1456650404,1,'124.202.243.78',1,'User','insert'),(2453,'方维众筹更新成功',1456650637,1,'124.202.243.78',1,'IndexImage','update'),(2454,'方维众筹更新成功',1456650648,1,'124.202.243.78',1,'IndexImage','update'),(2455,'1添加成功',1456650707,1,'124.202.243.78',1,'IndexImage','insert'),(2456,'2添加成功',1456650752,1,'124.202.243.78',1,'IndexImage','insert'),(2457,'2更新成功',1456650762,1,'124.202.243.78',1,'IndexImage','update'),(2458,'3添加成功',1456650802,1,'124.202.243.78',1,'IndexImage','insert'),(2459,'3更新成功',1456650815,1,'124.202.243.78',1,'IndexImage','update'),(2460,'方维众筹更新成功',1456650934,1,'124.202.243.78',1,'IndexImage','update'),(2461,'方维众筹更新成功',1456651001,1,'124.202.243.78',1,'IndexImage','update'),(2462,'TPL_SMS_DEAL_FAIL更新成功',1456651056,1,'124.202.243.78',1,'MsgTemplate','update'),(2463,'TPL_SMS_DEAL_FAIL更新成功',1456651057,1,'124.202.243.78',1,'MsgTemplate','update'),(2464,'TPL_SMS_DEAL_FAIL更新成功',1456651059,1,'124.202.243.78',1,'MsgTemplate','update'),(2465,'TPL_SMS_DEAL_FAIL更新成功',1456651061,1,'124.202.243.78',1,'MsgTemplate','update'),(2466,'TPL_SMS_USER_VERIFY更新成功',1456651068,1,'124.202.243.78',1,'MsgTemplate','update'),(2467,'TPL_SMS_USER_S更新成功',1456651077,1,'124.202.243.78',1,'MsgTemplate','update'),(2468,'TPL_SMS_USER_F更新成功',1456651083,1,'124.202.243.78',1,'MsgTemplate','update'),(2469,'TPL_SMS_VERIFY_CODE更新成功',1456651090,1,'124.202.243.78',1,'MsgTemplate','update'),(2470,'TPL_SMS_DEAL_SUCCESS更新成功',1456651098,1,'124.202.243.78',1,'MsgTemplate','update'),(2471,'TPL_SMS_INVESTOR_PAY_STATUS更新成功',1456651105,1,'124.202.243.78',1,'MsgTemplate','update'),(2472,'TPL_SMS_INVESTOR_STATUS更新成功',1456651112,1,'124.202.243.78',1,'MsgTemplate','update'),(2473,'TPL_SMS_INVESTOR_PAID_STATUS更新成功',1456651118,1,'124.202.243.78',1,'MsgTemplate','update'),(2474,'TPL_SMS_ZC_STATUS更新成功',1456651128,1,'124.202.243.78',1,'MsgTemplate','update'),(2475,'TPL_SMS_TZT_VERIFY_CODE更新成功',1456651152,1,'124.202.243.78',1,'MsgTemplate','update'),(2476,'smtp.163.com添加成功',1456651209,1,'124.202.243.78',1,'MailServer','insert'),(2477,'短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)安装成功',1456651219,1,'124.202.243.78',1,'Sms','insert'),(2478,'短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)启用成功',1456651223,1,'124.202.243.78',1,'Sms','set_effect'),(2479,'更新系统配置',1456651396,1,'124.193.88.122',1,'Conf','update'),(2480,'短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)更新成功',1456651523,1,'124.193.88.122',1,'Sms','update'),(2481,'短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)更新成功',1456651532,1,'124.193.88.122',1,'Sms','update'),(2482,'产品众筹广告页面添加成功',1456651774,1,'124.193.88.122',1,'Adv','insert'),(2483,'产品众筹广告页面更新成功',1456651818,1,'60.253.251.254',1,'Adv','update'),(2484,'产品众筹广告2添加成功',1456651881,1,'60.253.251.254',1,'Adv','insert'),(2485,'股权众筹广告页面添加成功',1456651955,1,'60.253.251.254',1,'Adv','insert'),(2486,'帮助列表广告添加成功',1456652107,1,'124.193.88.122',1,'Adv','insert'),(2487,'帮助列表广告更新成功',1456652162,1,'124.202.243.78',1,'Adv','update'),(2488,'动态广告添加成功',1456652264,1,'124.193.88.122',1,'Adv','insert'),(2489,'首页广告添加成功',1456652418,1,'60.253.251.254',1,'Adv','insert'),(2490,'更新系统配置',1456652476,1,'124.202.243.78',1,'Conf','update'),(2491,'保护梁子湖，我们在行动。更新成功',1456653032,1,'124.202.243.78',1,'DealSubmit','update'),(2492,'扶贫添加成功',1456653196,1,'220.113.12.3',1,'DealSelflessCate','insert'),(2493,'儿童添加成功',1456653203,1,'220.113.12.3',1,'DealSelflessCate','insert'),(2494,'筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！更新成功',1456653455,1,'60.253.251.254',1,'DealSelflessSubmit','update'),(2495,'产品众筹添加成功',1456653517,1,'60.253.251.254',1,'LinkGroup','insert'),(2496,'股权众筹添加成功',1456653526,1,'124.202.243.78',1,'LinkGroup','insert'),(2497,'其他众筹添加成功',1456653624,1,'124.202.243.78',1,'LinkGroup','insert'),(2498,'风投在线添加成功',1456653637,1,'60.253.251.254',1,'LinkGroup','insert'),(2499,'猫力中国添加成功',1456653663,1,'60.253.251.254',1,'Link','insert'),(2500,'VK维客添加成功',1456653683,1,'60.253.251.254',1,'Link','insert'),(2501,'KK大公馆添加成功',1456653703,1,'60.253.251.254',1,'Link','insert'),(2502,'宫网添加成功',1456653720,1,'60.253.251.254',1,'Link','insert'),(2503,'猫力网添加成功',1456653752,1,'60.253.251.254',1,'Link','insert'),(2504,'qodo取道文化添加成功',1456653780,1,'60.253.251.254',1,'Link','insert'),(2505,'VITAGONG宫伟添加成功',1456653835,1,'124.193.88.122',1,'Link','insert'),(2506,'MW猫力珠宝添加成功',1456653875,1,'60.253.251.254',1,'Link','insert'),(2507,'qodo取道中国添加成功',1456653898,1,'124.193.88.122',1,'Link','insert'),(2508,'36氪股权众筹添加成功',1456654193,1,'124.193.88.122',1,'Link','insert'),(2509,'股权众筹更新成功',1456654216,1,'124.193.88.122',1,'LinkGroup','update'),(2510,'36氪股权众筹更新成功',1456654233,1,'124.193.88.122',1,'Link','update'),(2511,'风投在线更新成功',1456654272,1,'60.253.251.254',1,'LinkGroup','update'),(2512,'软银中国添加成功',1456654318,1,'124.193.88.122',1,'Link','insert'),(2513,'纪源资本添加成功',1456654352,1,'124.193.88.122',1,'Link','insert'),(2514,'红杉资本添加成功',1456654386,1,'124.193.88.122',1,'Link','insert'),(2515,'经纬中国添加成功',1456654426,1,'60.253.251.254',1,'Link','insert'),(2516,'IDG添加成功',1456654457,1,'60.253.251.254',1,'Link','insert'),(2517,'GOBI添加成功',1456654492,1,'60.253.251.254',1,'Link','insert'),(2518,'真格基金添加成功',1456654553,1,'60.253.251.254',1,'Link','insert'),(2519,'京东金融添加成功',1456654636,1,'60.253.251.254',1,'Link','insert'),(2520,'天使客添加成功',1456654718,1,'60.253.251.254',1,'Link','insert'),(2521,'天使街添加成功',1456654799,1,'60.253.251.254',1,'Link','insert'),(2522,'企e融添加成功',1456654895,1,'60.253.251.254',1,'Link','insert'),(2523,'融e帮添加成功',1456654969,1,'60.253.251.254',1,'Link','insert'),(2524,'第五创添加成功',1456655021,1,'124.193.88.122',1,'Link','insert'),(2525,'众筹客添加成功',1456655227,1,'124.193.88.122',1,'Link','insert'),(2526,'产品众筹更新成功',1456655242,1,'124.193.88.122',1,'LinkGroup','update'),(2527,'淘宝众筹添加成功',1456655326,1,'60.253.251.254',1,'Link','insert'),(2528,'众筹网添加成功',1456655365,1,'124.193.88.122',1,'Link','insert'),(2529,'众筹网更新成功',1456655414,1,'60.253.251.254',1,'Link','update'),(2530,'其他众筹更新成功',1456655535,1,'60.253.251.254',1,'LinkGroup','update'),(2531,'汇梦公社添加成功',1456655577,1,'60.253.251.254',1,'Link','insert'),(2532,'咱们众筹添加成功',1456655653,1,'60.253.251.254',1,'Link','insert'),(2533,'大家投添加成功',1456655747,1,'124.193.88.122',1,'Link','insert'),(2534,'大家筹添加成功',1456655794,1,'60.253.251.254',1,'Link','insert'),(2535,'人人合伙添加成功',1456655860,1,'124.193.88.122',1,'Link','insert'),(2536,'360淘金添加成功',1456655954,1,'60.253.251.254',1,'Link','insert'),(2537,'蚂蚁天使添加成功',1456656021,1,'124.193.88.122',1,'Link','insert'),(2538,'1456651606删除成功',1456656061,1,'124.193.88.122',1,'Database','delete'),(2539,'更新系统配置',1456656124,1,'60.253.251.254',1,'Conf','update'),(2540,'互联网+添加成功',1456656307,1,'60.253.251.254',1,'DealInvestorCate','insert'),(2541,'实体店铺添加成功',1456656315,1,'60.253.251.254',1,'DealInvestorCate','insert'),(2542,'影视项目添加成功',1456656332,1,'60.253.251.254',1,'DealInvestorCate','insert'),(2543,'邦美智洗洗衣店O2O智能管理系统更新成功',1456656735,1,'60.253.251.254',1,'DealInvestorSubmit','update_investor'),(2544,'admin密码修改成功',1456656848,1,'60.253.251.254',1,'Index','do_change_password'),(2545,'1456656055删除成功',1456656909,1,'60.253.251.254',1,'Database','delete');
/*!40000 ALTER TABLE `fanwe_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_m_adv`
--

DROP TABLE IF EXISTS `fanwe_m_adv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_m_adv` (
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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_m_adv`
--

LOCK TABLES `fanwe_m_adv` WRITE;
/*!40000 ALTER TABLE `fanwe_m_adv` DISABLE KEYS */;
INSERT INTO `fanwe_m_adv` VALUES (25,'VK维客','./public/attachment/201602/29/01/56d328bd3d090.jpg','top',1,'',2,1,0),(24,'创业者的曙光','./public/attachment/201602/29/01/56d328a87de29.jpg','top',1,'',1,1,0);
/*!40000 ALTER TABLE `fanwe_m_adv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_m_config`
--

DROP TABLE IF EXISTS `fanwe_m_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_m_config` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_m_config`
--

LOCK TABLES `fanwe_m_config` WRITE;
/*!40000 ALTER TABLE `fanwe_m_config` DISABLE KEYS */;
INSERT INTO `fanwe_m_config` VALUES (10,'kf_phone','客服电话','010-56267773',0,1,NULL),(11,'kf_email','客服邮箱','462414875@qq.com',0,2,NULL),(29,'ios_upgrade','ios版本升级内容','',3,9,NULL),(16,'page_size','分页大小','10',0,10,NULL),(17,'about_info','关于我们(填文章ID)','',0,3,NULL),(18,'program_title','程序标题名称','VK维客众筹',0,0,NULL),(22,'android_version','android版本号(yyyymmddnn)','',0,4,NULL),(23,'android_filename','android下载包名(放程序根目录下)','',0,5,NULL),(24,'ios_version','ios版本号(yyyymmddnn)','',0,7,NULL),(25,'ios_down_url','ios下载地址(appstore连接地址)','',0,8,NULL),(28,'android_upgrade','android版本升级内容','修复bug',3,6,NULL),(30,'article_cate_id','文章分类ID','',0,11,NULL),(31,'android_forced_upgrade','android是否强制升级(0:否;1:是)','1',0,0,NULL),(32,'ios_forced_upgrade','ios是否强制升级(0:否;1:是)','1',0,0,NULL),(35,'logo','系统LOGO','',2,1,NULL),(33,'index_adv_num','首页广告数','5',0,33,NULL),(34,'index_pro_num','首页推荐商品数','0',0,34,NULL),(36,'wx_appid','微信APPID','',0,36,NULL),(37,'wx_secrit','微信SECRIT','',0,37,NULL),(38,'sina_app_key','新浪APP_KEY','',0,38,NULL),(39,'sina_app_secret','新浪APP_SECRET','',0,39,NULL),(40,'sina_bind_url','新浪回调地址','',0,40,NULL),(41,'qq_app_key','QQ登录APP_KEY','',0,41,NULL),(42,'qq_app_secret','QQ登录APP_SECRET','',0,42,NULL),(43,'wx_app_key','微信(分享)appkey','',0,43,NULL),(44,'wx_app_secret','微信(分享)appSecret','',0,44,NULL),(45,'wx_controll','一站式登录方式','0',4,45,'0,1'),(46,'ios_check_version','ios审核版本号(审核中填写)','',0,9,'');
/*!40000 ALTER TABLE `fanwe_m_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_mail_list`
--

DROP TABLE IF EXISTS `fanwe_mail_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_mail_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_address` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mail_address_idx` (`mail_address`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='//邮件列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_mail_list`
--

LOCK TABLES `fanwe_mail_list` WRITE;
/*!40000 ALTER TABLE `fanwe_mail_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_mail_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_mail_server`
--

DROP TABLE IF EXISTS `fanwe_mail_server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_mail_server` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_mail_server`
--

LOCK TABLES `fanwe_mail_server` WRITE;
/*!40000 ALTER TABLE `fanwe_mail_server` DISABLE KEYS */;
INSERT INTO `fanwe_mail_server` VALUES (8,'smtp.163.com','vitakung@163.com','vitakung',0,'25',0,0,1,6,1);
/*!40000 ALTER TABLE `fanwe_mail_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_message`
--

DROP TABLE IF EXISTS `fanwe_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '该留言所属人ID',
  `user_name` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 COMMENT='// 用户留言';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_message`
--

LOCK TABLES `fanwe_message` WRITE;
/*!40000 ALTER TABLE `fanwe_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_message_cate`
--

DROP TABLE IF EXISTS `fanwe_message_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_message_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `is_project` tinyint(1) NOT NULL DEFAULT '0' COMMENT '项目发起的分类 0表示否 1表示 是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='// 用户留言分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_message_cate`
--

LOCK TABLES `fanwe_message_cate` WRITE;
/*!40000 ALTER TABLE `fanwe_message_cate` DISABLE KEYS */;
INSERT INTO `fanwe_message_cate` VALUES (1,'项目登记',1),(2,'建议留言',0);
/*!40000 ALTER TABLE `fanwe_message_cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_mobile_verify_code`
--

DROP TABLE IF EXISTS `fanwe_mobile_verify_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_mobile_verify_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(10) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `create_time` int(11) NOT NULL,
  `client_ip` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT '邮件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='//手机验证';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_mobile_verify_code`
--

LOCK TABLES `fanwe_mobile_verify_code` WRITE;
/*!40000 ALTER TABLE `fanwe_mobile_verify_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_mobile_verify_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_money_freeze`
--

DROP TABLE IF EXISTS `fanwe_money_freeze`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_money_freeze` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_money_freeze`
--

LOCK TABLES `fanwe_money_freeze` WRITE;
/*!40000 ALTER TABLE `fanwe_money_freeze` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_money_freeze` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_money_type`
--

DROP TABLE IF EXISTS `fanwe_money_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_money_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'type类型 0 ~ ？',
  `class` varchar(100) NOT NULL DEFAULT '' COMMENT '所属分类 money  lock_money site_money  point  score',
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_money_type`
--

LOCK TABLES `fanwe_money_type` WRITE;
/*!40000 ALTER TABLE `fanwe_money_type` DISABLE KEYS */;
INSERT INTO `fanwe_money_type` VALUES (1,'普通的',0,'money',0,1),(2,'加入诚意金',1,'money',0,1),(3,'违约扣除诚意金',2,'money',0,1),(4,'分红',3,'money',0,1),(5,'订金',4,'money',0,1),(6,'首付',5,'money',0,1),(7,'众筹买房',6,'money',0,1),(8,'买房卖出回报',7,'money',0,1),(9,'理财赎回本金',8,'money',0,1),(10,'理财赎回收益',9,'money',0,1),(11,'理财赎回手续费',10,'money',0,1),(12,'理财本金',11,'money',0,1),(13,'理财购买手续费',12,'money',0,1),(14,'理财冻结资金',13,'money',0,1),(15,'理财服务费',14,'money',0,1),(16,'理财发放资金',15,'money',0,1);
/*!40000 ALTER TABLE `fanwe_money_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_mortgate`
--

DROP TABLE IF EXISTS `fanwe_mortgate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_mortgate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `notice_id` int(11) NOT NULL COMMENT '0 表示为余额支付 大于0表示在线支付',
  `money` int(11) NOT NULL COMMENT '诚意金 金额',
  `status` tinyint(1) NOT NULL COMMENT '状态 1表示诚意金支付 2表示扣除诚意金 3表示诚意金解冻到余额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_mortgate`
--

LOCK TABLES `fanwe_mortgate` WRITE;
/*!40000 ALTER TABLE `fanwe_mortgate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_mortgate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_msg_template`
--

DROP TABLE IF EXISTS `fanwe_msg_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_msg_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '名字',
  `content` text NOT NULL COMMENT '内容',
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `is_html` tinyint(1) NOT NULL COMMENT '是否成功：1表示成功，0表示失败',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='// 邮箱验证';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_msg_template`
--

LOCK TABLES `fanwe_msg_template` WRITE;
/*!40000 ALTER TABLE `fanwe_msg_template` DISABLE KEYS */;
INSERT INTO `fanwe_msg_template` VALUES (1,'TPL_MAIL_USER_PASSWORD','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}你好，请点击以下链接修改您的密码：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.password_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.password_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n \r\n</tbody>\r\n</table>',1,1),(3,'TPL_MAIL_USER_VERIFY','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 帐号 发送验证码：</p>\r\n				<p style=\"margin:0px;padding:0px;\">验证码：{$user.send_code}</p>\r\n 				 \r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}帐号团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>',1,1),(18,'TPL_SMS_DEAL_FAIL','{$fail_user_info.user_name}您好，很遗憾的通知您，您所支持的 \"{$fail_user_info.deal_name}\"项目筹资失败!【VK维客+猫力中国】',0,0),(20,'TPL_SMS_USER_VERIFY','恭喜你{$success_user_info.user_name}，注册验证成功!【VK维客+猫力中国】',0,0),(21,'TPL_SMS_USER_S','{$user_s_msg.user_name}您好，恭喜你，您发起的{$user_s_msg.deal_name}项目筹资成功!【VK维客+猫力中国】',0,0),(22,'TPL_SMS_USER_F','{$user_f_msg.user_name}您好，很遗憾的通知您，您发起的{$user_f_msg.deal_name}项目筹资失败!【VK维客+猫力中国】',0,0),(23,'TPL_SMS_VERIFY_CODE','你的手机号为{$verify.mobile},验证码为{$verify.code}【VK维客+猫力中国】',0,0),(17,'TPL_SMS_DEAL_SUCCESS','{$success_user_info.user_name}您好，恭喜你，您所支持的 \"{$success_user_info.deal_name}\" 项目筹资成功,近期将会发放回报!【VK维客+猫力中国】',0,0),(4,'TPL_MAIL_CHANGE_USER_VERIFY','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 进行邮件修改 <a href=\"mailto:{$user.email}\" target=\"_blank\">{$user.email}<wbr>.com</a> ，点击以下链接，即可进行下一步操作：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">1、为了保障您帐号的安全性，请在 48小时内完成激活，此链接将在您激活过一次后失效！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">2、注册的帐号可以畅行{$user.site_name}产品</p>\r\n				<div style=\"line-height:80px;height:80px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.site_name}帐号团队</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>',1,1),(5,'TPL_MAIL_INVESTOR_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您于 {$user.send_time_ms} 进行{$user.is_investor_name}申请，{if $user.investor_status eq 1}很高兴您审核通过,点击以下链接，即可进行下一步操作{else}很遗憾您的申请未通过,理由是：{$user.investor_send_info};点击以下链接，即可重新申请{/if}：</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n 				<div style=\"line-height:80px;height:80px;\"></div>\r\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>',1,1),(25,'TPL_MAIL_INVESTOR_PAY_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}您好，您的投资申请已通过，请在{$user.pay_end_time}前进行支付{$user.money}元;点击以下链接</p>\r\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.note_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.note_url}</a></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>',1,1),(26,'TPL_SMS_INVESTOR_PAY_STATUS','{$user.user_name}您好，您的投资申请已通过，请在{$user.pay_end_time}前进行支付{$user.money}元【VK维客+猫力中国】',0,0),(6,'TPL_SMS_INVESTOR_STATUS','{$user.user_name}您好，{if $user.investor_status eq 1}恭喜您{else}很遗憾,{$user.investor_send_info}{/if},您申请的{$user.is_investor_name}{$user.investor_status_name}【VK维客+猫力中国】',0,0),(27,'TPL_SMS_INVESTOR_PAID_STATUS','恭喜您，已经支付{$user.paid_money}元,支付单号为{$user.notice_sn}【VK维客+猫力中国】',0,0),(28,'TPL_MAIL_INVESTOR_PAID_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\r\n<tbody>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\r\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\r\n		</tr>\r\n        </tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tbody>\r\n		<tr>\r\n			<td width=\"25px;\" style=\"width:25px;\"></td>\r\n			<td align=\"\">\r\n				<div style=\"line-height:40px;height:40px;\"></div>\r\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\r\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.user_name}您好，恭喜您，已经支付{$user.paid_money}元,支付单号为{$user.notice_sn}</p>\r\n				\r\n  				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\r\n			</td>\r\n		</tr>\r\n		</tbody>\r\n		</table>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\r\n			<tbody>\r\n			<tr>\r\n				<td width=\"25px;\" style=\"width:25px;\"></td>\r\n				<td align=\"\">\r\n					<div style=\"line-height:40px;height:40px;\"></div>\r\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，由此给您带来的不便请谅解。</p>\r\n				</td>\r\n			</tr>\r\n			</tbody>\r\n			</table>\r\n		</td>\r\n	</tr>\r\n</tbody>\r\n</table>',1,1),(29,'TPL_SMS_ZC_STATUS','{$user.control_content}【VK维客+猫力中国】',0,0),(30,'TPL_MAIL_ZC_STATUS','<table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\" width=\"100%\" style=\"background:#ffffff;\" class=\"baidu_pass\">\n<tbody>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;width:15px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;width:137px;\"><img src=\"{$user.logo}\" class=\"logo\" ellpadding=\"0\" cellspacing=\"0\"></td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #ffffff;width:10px;\">&nbsp;</td>\n			<td style=\"background:#ffffff;border-bottom:2px solid #dfdfdf;\">&nbsp;</td>\n		</tr>\n        </tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n        <tbody>\n		<tr>\n			<td width=\"25px;\" style=\"width:25px;\"></td>\n			<td align=\"\">\n				<div style=\"line-height:40px;height:40px;\"></div>\n				<p style=\"margin:0px;padding:0px;\"><strong style=\"font-size:14px;line-height:24px;color:#333333;font-family:arial,sans-serif;\">亲爱的用户：</strong></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">您好！</p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\">{$user.control_content}</p>\n				<p style=\"margin:0px;padding:0px;\"><a href=\"{$user.verify_url}\" style=\"line-height:24px;font-size:12px;font-family:arial,sans-serif;color:#0000cc\" target=\"_blank\">{$user.verify_url}</a></p>\n				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:arial,sans-serif;\">(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>\n 				<div style=\"line-height:80px;height:80px;\"></div>\n 				<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#333333;font-family:\'宋体\',arial,sans-serif;\"><span style=\"border-bottom:1px dashed #ccc;\" t=\"5\" times=\"\">{$user.send_time}</span></p>\n			</td>\n		</tr>\n		</tbody>\n		</table>\n		</td>\n	</tr>\n	<tr>\n		<td>\n			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-top:1px solid #dfdfdf\">\n			<tbody>\n			<tr>\n				<td width=\"25px;\" style=\"width:25px;\"></td>\n				<td align=\"\">\n					<div style=\"line-height:40px;height:40px;\"></div>\n					<p style=\"margin:0px;padding:0px;line-height:24px;font-size:12px;color:#979797;font-family:\'宋体\',arial,sans-serif;\">若您没有注册过{$user.site_name}帐号，请忽略此邮件，此帐号将不会被激活，由此给您带来的不便请谅解。</p>\n				</td>\n			</tr>\n			</tbody>\n			</table>\n		</td>\n	</tr>\n</tbody>\n</table>',1,1),(31,'TPL_SMS_TZT_VERIFY_CODE','你的手机号为{$verify.mobile},易宝投资通验证码为{$verify.code}【VK维客+猫力中国】',0,0);
/*!40000 ALTER TABLE `fanwe_msg_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_nav`
--

DROP TABLE IF EXISTS `fanwe_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_nav` (
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
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='//导航菜单列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_nav`
--

LOCK TABLES `fanwe_nav` WRITE;
/*!40000 ALTER TABLE `fanwe_nav` DISABLE KEYS */;
INSERT INTO `fanwe_nav` VALUES (42,'首页','',0,1,1,'index','',0,''),(47,'公益模块','',0,3,1,'deals','selfless',0,''),(46,'产品众筹','',0,2,1,'deals','index',0,''),(48,'最新动态','',0,8,1,'news','index',0,''),(49,'路演资讯','',0,20,1,'article_cate','',0,''),(50,'股权交易','',0,3,1,'deals','stock',0,''),(51,'积分商城','',0,5,1,'score_mall','',0,''),(53,'新手帮助','',0,7,1,'faq','',0,''),(54,'天使投资人','',0,6,1,'investor','invester_list',0,'');
/*!40000 ALTER TABLE `fanwe_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_payment`
--

DROP TABLE IF EXISTS `fanwe_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_payment` (
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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='// 付款';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_payment`
--

LOCK TABLES `fanwe_payment` WRITE;
/*!40000 ALTER TABLE `fanwe_payment` DISABLE KEYS */;
INSERT INTO `fanwe_payment` VALUES (24,'AlipayBank',1,1,'支付宝银行直连支付','','0.00','a:4:{s:14:\"alipay_partner\";s:0:\"\";s:14:\"alipay_account\";s:0:\"\";s:10:\"alipay_key\";s:0:\"\";s:14:\"alipay_gateway\";a:17:{s:7:\"ICBCB2C\";s:1:\"1\";s:3:\"CMB\";s:1:\"1\";s:3:\"CCB\";s:1:\"1\";s:3:\"ABC\";s:1:\"1\";s:4:\"SPDB\";s:1:\"1\";s:3:\"SDB\";s:1:\"1\";s:3:\"CIB\";s:1:\"1\";s:6:\"BJBANK\";s:1:\"1\";s:7:\"CEBBANK\";s:1:\"1\";s:4:\"CMBC\";s:1:\"1\";s:5:\"CITIC\";s:1:\"1\";s:3:\"GDB\";s:1:\"1\";s:7:\"SPABANK\";s:1:\"1\";s:6:\"BOCB2C\";s:1:\"1\";s:4:\"COMM\";s:1:\"1\";s:7:\"ICBCBTB\";s:1:\"1\";s:10:\"PSBC-DEBIT\";s:1:\"1\";}}','',1);
/*!40000 ALTER TABLE `fanwe_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_payment_notice`
--

DROP TABLE IF EXISTS `fanwe_payment_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_payment_notice` (
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
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='// 付款单号列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_payment_notice`
--

LOCK TABLES `fanwe_payment_notice` WRITE;
/*!40000 ALTER TABLE `fanwe_payment_notice` DISABLE KEYS */;
INSERT INTO `fanwe_payment_notice` VALUES (200,'20121107399',1352230157,0,67,0,19,24,'ICBCB2C','','500.00','',56,24,'拥有自己的咖啡馆',0,0,0),(201,'20121107985',1352230180,1352230180,67,1,19,0,'','管理员收款','500.00','',56,24,'拥有自己的咖啡馆',0,0,0),(202,'20121107931',1352231631,0,78,0,19,24,'CCB','ttt','500.00','',58,30,'流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！',0,0,0),(203,'20121107124',1352231671,0,79,0,17,24,'ICBCB2C','部份支付','200.00','',56,24,'拥有自己的咖啡馆',0,0,0);
/*!40000 ALTER TABLE `fanwe_payment_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_promote_msg`
--

DROP TABLE IF EXISTS `fanwe_promote_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_promote_msg` (
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='// 推广信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_promote_msg`
--

LOCK TABLES `fanwe_promote_msg` WRITE;
/*!40000 ALTER TABLE `fanwe_promote_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_promote_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_promote_msg_list`
--

DROP TABLE IF EXISTS `fanwe_promote_msg_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_promote_msg_list` (
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='// 推广信息队列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_promote_msg_list`
--

LOCK TABLES `fanwe_promote_msg_list` WRITE;
/*!40000 ALTER TABLE `fanwe_promote_msg_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_promote_msg_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_recommend`
--

DROP TABLE IF EXISTS `fanwe_recommend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_recommend` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_recommend`
--

LOCK TABLES `fanwe_recommend` WRITE;
/*!40000 ALTER TABLE `fanwe_recommend` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_recommend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_referrals`
--

DROP TABLE IF EXISTS `fanwe_referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_referrals` (
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='邀请返利记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_referrals`
--

LOCK TABLES `fanwe_referrals` WRITE;
/*!40000 ALTER TABLE `fanwe_referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_region_conf`
--

DROP TABLE IF EXISTS `fanwe_region_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_region_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '地区名称',
  `region_level` tinyint(4) NOT NULL COMMENT '1:国 2:省 3:市(县) 4:区(镇)',
  `py` varchar(50) NOT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为热门地区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3401 DEFAULT CHARSET=utf8 COMMENT='//地区配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_region_conf`
--

LOCK TABLES `fanwe_region_conf` WRITE;
/*!40000 ALTER TABLE `fanwe_region_conf` DISABLE KEYS */;
INSERT INTO `fanwe_region_conf` VALUES (3,1,'安徽',2,'anhui',0),(4,1,'福建',2,'fujian',0),(5,1,'甘肃',2,'gansu',0),(6,1,'广东',2,'guangdong',0),(7,1,'广西',2,'guangxi',0),(8,1,'贵州',2,'guizhou',0),(9,1,'海南',2,'hainan',0),(10,1,'河北',2,'hebei',0),(11,1,'河南',2,'henan',0),(12,1,'黑龙江',2,'heilongjiang',0),(13,1,'湖北',2,'hubei',0),(14,1,'湖南',2,'hunan',0),(15,1,'吉林',2,'jilin',0),(16,1,'江苏',2,'jiangsu',0),(17,1,'江西',2,'jiangxi',0),(18,1,'辽宁',2,'liaoning',0),(19,1,'内蒙古',2,'neimenggu',0),(20,1,'宁夏',2,'ningxia',0),(21,1,'青海',2,'qinghai',0),(22,1,'山东',2,'shandong',0),(23,1,'山西',2,'shanxi',0),(24,1,'陕西',2,'shanxi',0),(26,1,'四川',2,'sichuan',0),(28,1,'西藏',2,'xicang',0),(29,1,'新疆',2,'xinjiang',0),(30,1,'云南',2,'yunnan',0),(31,1,'浙江',2,'zhejiang',0),(36,3,'安庆',3,'anqing',0),(37,3,'蚌埠',3,'bangbu',0),(38,3,'巢湖',3,'chaohu',0),(39,3,'池州',3,'chizhou',0),(40,3,'滁州',3,'chuzhou',0),(41,3,'阜阳',3,'fuyang',0),(42,3,'淮北',3,'huaibei',0),(43,3,'淮南',3,'huainan',0),(44,3,'黄山',3,'huangshan',0),(45,3,'六安',3,'liuan',0),(46,3,'马鞍山',3,'maanshan',0),(47,3,'宿州',3,'suzhou',0),(48,3,'铜陵',3,'tongling',0),(49,3,'芜湖',3,'wuhu',0),(50,3,'宣城',3,'xuancheng',0),(51,3,'亳州',3,'zhou',0),(52,2,'北京',2,'beijing',1),(53,4,'福州',3,'fuzhou',0),(54,4,'龙岩',3,'longyan',0),(55,4,'南平',3,'nanping',0),(56,4,'宁德',3,'ningde',0),(57,4,'莆田',3,'putian',0),(58,4,'泉州',3,'quanzhou',0),(59,4,'三明',3,'sanming',0),(60,4,'厦门',3,'xiamen',0),(61,4,'漳州',3,'zhangzhou',0),(62,5,'兰州',3,'lanzhou',0),(63,5,'白银',3,'baiyin',0),(64,5,'定西',3,'dingxi',0),(65,5,'甘南',3,'gannan',0),(66,5,'嘉峪关',3,'jiayuguan',0),(67,5,'金昌',3,'jinchang',0),(68,5,'酒泉',3,'jiuquan',0),(69,5,'临夏',3,'linxia',0),(70,5,'陇南',3,'longnan',0),(71,5,'平凉',3,'pingliang',0),(72,5,'庆阳',3,'qingyang',0),(73,5,'天水',3,'tianshui',0),(74,5,'武威',3,'wuwei',0),(75,5,'张掖',3,'zhangye',0),(76,6,'广州',3,'guangzhou',0),(77,6,'深圳',3,'shen',0),(78,6,'潮州',3,'chaozhou',0),(79,6,'东莞',3,'dong',0),(80,6,'佛山',3,'foshan',0),(81,6,'河源',3,'heyuan',0),(82,6,'惠州',3,'huizhou',0),(83,6,'江门',3,'jiangmen',0),(84,6,'揭阳',3,'jieyang',0),(85,6,'茂名',3,'maoming',0),(86,6,'梅州',3,'meizhou',0),(87,6,'清远',3,'qingyuan',0),(88,6,'汕头',3,'shantou',0),(89,6,'汕尾',3,'shanwei',0),(90,6,'韶关',3,'shaoguan',0),(91,6,'阳江',3,'yangjiang',0),(92,6,'云浮',3,'yunfu',0),(93,6,'湛江',3,'zhanjiang',0),(94,6,'肇庆',3,'zhaoqing',0),(95,6,'中山',3,'zhongshan',0),(96,6,'珠海',3,'zhuhai',0),(97,7,'南宁',3,'nanning',0),(98,7,'桂林',3,'guilin',0),(99,7,'百色',3,'baise',0),(100,7,'北海',3,'beihai',0),(101,7,'崇左',3,'chongzuo',0),(102,7,'防城港',3,'fangchenggang',0),(103,7,'贵港',3,'guigang',0),(104,7,'河池',3,'hechi',0),(105,7,'贺州',3,'hezhou',0),(106,7,'来宾',3,'laibin',0),(107,7,'柳州',3,'liuzhou',0),(108,7,'钦州',3,'qinzhou',0),(109,7,'梧州',3,'wuzhou',0),(110,7,'玉林',3,'yulin',0),(111,8,'贵阳',3,'guiyang',0),(112,8,'安顺',3,'anshun',0),(113,8,'毕节',3,'bijie',0),(114,8,'六盘水',3,'liupanshui',0),(115,8,'黔东南',3,'qiandongnan',0),(116,8,'黔南',3,'qiannan',0),(117,8,'黔西南',3,'qianxinan',0),(118,8,'铜仁',3,'tongren',0),(119,8,'遵义',3,'zunyi',0),(120,9,'海口',3,'haikou',0),(121,9,'三亚',3,'sanya',0),(122,9,'白沙',3,'baisha',0),(123,9,'保亭',3,'baoting',0),(124,9,'昌江',3,'changjiang',0),(125,9,'澄迈县',3,'chengmaixian',0),(126,9,'定安县',3,'dinganxian',0),(127,9,'东方',3,'dongfang',0),(128,9,'乐东',3,'ledong',0),(129,9,'临高县',3,'lingaoxian',0),(130,9,'陵水',3,'lingshui',0),(131,9,'琼海',3,'qionghai',0),(132,9,'琼中',3,'qiongzhong',0),(133,9,'屯昌县',3,'tunchangxian',0),(134,9,'万宁',3,'wanning',0),(135,9,'文昌',3,'wenchang',0),(136,9,'五指山',3,'wuzhishan',0),(137,9,'儋州',3,'zhou',0),(138,10,'石家庄',3,'shijiazhuang',0),(139,10,'保定',3,'baoding',0),(140,10,'沧州',3,'cangzhou',0),(141,10,'承德',3,'chengde',0),(142,10,'邯郸',3,'handan',0),(143,10,'衡水',3,'hengshui',0),(144,10,'廊坊',3,'langfang',0),(145,10,'秦皇岛',3,'qinhuangdao',0),(146,10,'唐山',3,'tangshan',0),(147,10,'邢台',3,'xingtai',0),(148,10,'张家口',3,'zhangjiakou',0),(149,11,'郑州',3,'zhengzhou',0),(150,11,'洛阳',3,'luoyang',0),(151,11,'开封',3,'kaifeng',0),(152,11,'安阳',3,'anyang',0),(153,11,'鹤壁',3,'hebi',0),(154,11,'济源',3,'jiyuan',0),(155,11,'焦作',3,'jiaozuo',0),(156,11,'南阳',3,'nanyang',0),(157,11,'平顶山',3,'pingdingshan',0),(158,11,'三门峡',3,'sanmenxia',0),(159,11,'商丘',3,'shangqiu',0),(160,11,'新乡',3,'xinxiang',0),(161,11,'信阳',3,'xinyang',0),(162,11,'许昌',3,'xuchang',0),(163,11,'周口',3,'zhoukou',0),(164,11,'驻马店',3,'zhumadian',0),(165,11,'漯河',3,'he',0),(166,11,'濮阳',3,'yang',0),(167,12,'哈尔滨',3,'haerbin',0),(168,12,'大庆',3,'daqing',0),(169,12,'大兴安岭',3,'daxinganling',0),(170,12,'鹤岗',3,'hegang',0),(171,12,'黑河',3,'heihe',0),(172,12,'鸡西',3,'jixi',0),(173,12,'佳木斯',3,'jiamusi',0),(174,12,'牡丹江',3,'mudanjiang',0),(175,12,'七台河',3,'qitaihe',0),(176,12,'齐齐哈尔',3,'qiqihaer',0),(177,12,'双鸭山',3,'shuangyashan',0),(178,12,'绥化',3,'suihua',0),(179,12,'伊春',3,'yichun',0),(180,13,'武汉',3,'wuhan',0),(181,13,'仙桃',3,'xiantao',0),(182,13,'鄂州',3,'ezhou',0),(183,13,'黄冈',3,'huanggang',0),(184,13,'黄石',3,'huangshi',0),(185,13,'荆门',3,'jingmen',0),(186,13,'荆州',3,'jingzhou',0),(187,13,'潜江',3,'qianjiang',0),(188,13,'神农架林区',3,'shennongjialinqu',0),(189,13,'十堰',3,'shiyan',0),(190,13,'随州',3,'suizhou',0),(191,13,'天门',3,'tianmen',0),(192,13,'咸宁',3,'xianning',0),(193,13,'襄樊',3,'xiangfan',0),(194,13,'孝感',3,'xiaogan',0),(195,13,'宜昌',3,'yichang',0),(196,13,'恩施',3,'enshi',0),(197,14,'长沙',3,'changsha',0),(198,14,'张家界',3,'zhangjiajie',0),(199,14,'常德',3,'changde',0),(200,14,'郴州',3,'chenzhou',0),(201,14,'衡阳',3,'hengyang',0),(202,14,'怀化',3,'huaihua',0),(203,14,'娄底',3,'loudi',0),(204,14,'邵阳',3,'shaoyang',0),(205,14,'湘潭',3,'xiangtan',0),(206,14,'湘西',3,'xiangxi',0),(207,14,'益阳',3,'yiyang',0),(208,14,'永州',3,'yongzhou',0),(209,14,'岳阳',3,'yueyang',0),(210,14,'株洲',3,'zhuzhou',0),(211,15,'长春',3,'changchun',0),(212,15,'吉林',3,'jilin',0),(213,15,'白城',3,'baicheng',0),(214,15,'白山',3,'baishan',0),(215,15,'辽源',3,'liaoyuan',0),(216,15,'四平',3,'siping',0),(217,15,'松原',3,'songyuan',0),(218,15,'通化',3,'tonghua',0),(219,15,'延边',3,'yanbian',0),(220,16,'南京',3,'nanjing',0),(221,16,'苏州',3,'suzhou',0),(222,16,'无锡',3,'wuxi',0),(223,16,'常州',3,'changzhou',0),(224,16,'淮安',3,'huaian',0),(225,16,'连云港',3,'lianyungang',0),(226,16,'南通',3,'nantong',0),(227,16,'宿迁',3,'suqian',0),(228,16,'泰州',3,'taizhou',0),(229,16,'徐州',3,'xuzhou',0),(230,16,'盐城',3,'yancheng',0),(231,16,'扬州',3,'yangzhou',0),(232,16,'镇江',3,'zhenjiang',0),(233,17,'南昌',3,'nanchang',0),(234,17,'抚州',3,'fuzhou',0),(235,17,'赣州',3,'ganzhou',0),(236,17,'吉安',3,'jian',0),(237,17,'景德镇',3,'jingdezhen',0),(238,17,'九江',3,'jiujiang',0),(239,17,'萍乡',3,'pingxiang',0),(240,17,'上饶',3,'shangrao',0),(241,17,'新余',3,'xinyu',0),(242,17,'宜春',3,'yichun',0),(243,17,'鹰潭',3,'yingtan',0),(244,18,'沈阳',3,'shenyang',0),(245,18,'大连',3,'dalian',0),(246,18,'鞍山',3,'anshan',0),(247,18,'本溪',3,'benxi',0),(248,18,'朝阳',3,'chaoyang',0),(249,18,'丹东',3,'dandong',0),(250,18,'抚顺',3,'fushun',0),(251,18,'阜新',3,'fuxin',0),(252,18,'葫芦岛',3,'huludao',0),(253,18,'锦州',3,'jinzhou',0),(254,18,'辽阳',3,'liaoyang',0),(255,18,'盘锦',3,'panjin',0),(256,18,'铁岭',3,'tieling',0),(257,18,'营口',3,'yingkou',0),(258,19,'呼和浩特',3,'huhehaote',0),(259,19,'阿拉善盟',3,'alashanmeng',0),(260,19,'巴彦淖尔盟',3,'bayannaoermeng',0),(261,19,'包头',3,'baotou',0),(262,19,'赤峰',3,'chifeng',0),(263,19,'鄂尔多斯',3,'eerduosi',0),(264,19,'呼伦贝尔',3,'hulunbeier',0),(265,19,'通辽',3,'tongliao',0),(266,19,'乌海',3,'wuhai',0),(267,19,'乌兰察布市',3,'wulanchabushi',0),(268,19,'锡林郭勒盟',3,'xilinguolemeng',0),(269,19,'兴安盟',3,'xinganmeng',0),(270,20,'银川',3,'yinchuan',0),(271,20,'固原',3,'guyuan',0),(272,20,'石嘴山',3,'shizuishan',0),(273,20,'吴忠',3,'wuzhong',0),(274,20,'中卫',3,'zhongwei',0),(275,21,'西宁',3,'xining',0),(276,21,'果洛',3,'guoluo',0),(277,21,'海北',3,'haibei',0),(278,21,'海东',3,'haidong',0),(279,21,'海南',3,'hainan',0),(280,21,'海西',3,'haixi',0),(281,21,'黄南',3,'huangnan',0),(282,21,'玉树',3,'yushu',0),(283,22,'济南',3,'jinan',0),(284,22,'青岛',3,'qingdao',0),(285,22,'滨州',3,'binzhou',0),(286,22,'德州',3,'dezhou',0),(287,22,'东营',3,'dongying',0),(288,22,'菏泽',3,'heze',0),(289,22,'济宁',3,'jining',0),(290,22,'莱芜',3,'laiwu',0),(291,22,'聊城',3,'liaocheng',0),(292,22,'临沂',3,'linyi',0),(293,22,'日照',3,'rizhao',0),(294,22,'泰安',3,'taian',0),(295,22,'威海',3,'weihai',0),(296,22,'潍坊',3,'weifang',0),(297,22,'烟台',3,'yantai',0),(298,22,'枣庄',3,'zaozhuang',0),(299,22,'淄博',3,'zibo',0),(300,23,'太原',3,'taiyuan',0),(301,23,'长治',3,'changzhi',0),(302,23,'大同',3,'datong',0),(303,23,'晋城',3,'jincheng',0),(304,23,'晋中',3,'jinzhong',0),(305,23,'临汾',3,'linfen',0),(306,23,'吕梁',3,'lvliang',0),(307,23,'朔州',3,'shuozhou',0),(308,23,'忻州',3,'xinzhou',0),(309,23,'阳泉',3,'yangquan',0),(310,23,'运城',3,'yuncheng',0),(311,24,'西安',3,'xian',0),(312,24,'安康',3,'ankang',0),(313,24,'宝鸡',3,'baoji',0),(314,24,'汉中',3,'hanzhong',0),(315,24,'商洛',3,'shangluo',0),(316,24,'铜川',3,'tongchuan',0),(317,24,'渭南',3,'weinan',0),(318,24,'咸阳',3,'xianyang',0),(319,24,'延安',3,'yanan',0),(320,24,'榆林',3,'yulin',0),(321,25,'上海',2,'shanghai',0),(322,26,'成都',3,'chengdu',0),(323,26,'绵阳',3,'mianyang',0),(324,26,'阿坝',3,'aba',0),(325,26,'巴中',3,'bazhong',0),(326,26,'达州',3,'dazhou',0),(327,26,'德阳',3,'deyang',0),(328,26,'甘孜',3,'ganzi',0),(329,26,'广安',3,'guangan',0),(330,26,'广元',3,'guangyuan',0),(331,26,'乐山',3,'leshan',0),(332,26,'凉山',3,'liangshan',0),(333,26,'眉山',3,'meishan',0),(334,26,'南充',3,'nanchong',0),(335,26,'内江',3,'neijiang',0),(336,26,'攀枝花',3,'panzhihua',0),(337,26,'遂宁',3,'suining',0),(338,26,'雅安',3,'yaan',0),(339,26,'宜宾',3,'yibin',0),(340,26,'资阳',3,'ziyang',0),(341,26,'自贡',3,'zigong',0),(342,26,'泸州',3,'zhou',0),(343,27,'天津',2,'tianjin',0),(344,28,'拉萨',3,'lasa',0),(345,28,'阿里',3,'ali',0),(346,28,'昌都',3,'changdu',0),(347,28,'林芝',3,'linzhi',0),(348,28,'那曲',3,'naqu',0),(349,28,'日喀则',3,'rikaze',0),(350,28,'山南',3,'shannan',0),(351,29,'乌鲁木齐',3,'wulumuqi',0),(352,29,'阿克苏',3,'akesu',0),(353,29,'阿拉尔',3,'alaer',0),(354,29,'巴音郭楞',3,'bayinguoleng',0),(355,29,'博尔塔拉',3,'boertala',0),(356,29,'昌吉',3,'changji',0),(357,29,'哈密',3,'hami',0),(358,29,'和田',3,'hetian',0),(359,29,'喀什',3,'kashi',0),(360,29,'克拉玛依',3,'kelamayi',0),(361,29,'克孜勒苏',3,'kezilesu',0),(362,29,'石河子',3,'shihezi',0),(363,29,'图木舒克',3,'tumushuke',0),(364,29,'吐鲁番',3,'tulufan',0),(365,29,'五家渠',3,'wujiaqu',0),(366,29,'伊犁',3,'yili',0),(367,30,'昆明',3,'kunming',0),(368,30,'怒江',3,'nujiang',0),(369,30,'普洱',3,'puer',0),(370,30,'丽江',3,'lijiang',0),(371,30,'保山',3,'baoshan',0),(372,30,'楚雄',3,'chuxiong',0),(373,30,'大理',3,'dali',0),(374,30,'德宏',3,'dehong',0),(375,30,'迪庆',3,'diqing',0),(376,30,'红河',3,'honghe',0),(377,30,'临沧',3,'lincang',0),(378,30,'曲靖',3,'qujing',0),(379,30,'文山',3,'wenshan',0),(380,30,'西双版纳',3,'xishuangbanna',0),(381,30,'玉溪',3,'yuxi',0),(382,30,'昭通',3,'zhaotong',0),(383,31,'杭州',3,'hangzhou',0),(384,31,'湖州',3,'huzhou',0),(385,31,'嘉兴',3,'jiaxing',0),(386,31,'金华',3,'jinhua',0),(387,31,'丽水',3,'lishui',0),(388,31,'宁波',3,'ningbo',0),(389,31,'绍兴',3,'shaoxing',0),(390,31,'台州',3,'taizhou',0),(391,31,'温州',3,'wenzhou',0),(392,31,'舟山',3,'zhoushan',0),(393,31,'衢州',3,'zhou',0),(394,32,'重庆',2,'zhongqing',0),(395,33,'香港',2,'xianggang',0),(396,34,'澳门',2,'aomen',0),(397,35,'台湾',2,'taiwan',0),(500,52,'东城区',3,'dongchengqu',0),(501,52,'西城区',3,'xichengqu',0),(502,52,'海淀区',3,'haidianqu',0),(503,52,'朝阳区',3,'chaoyangqu',0),(504,52,'崇文区',3,'chongwenqu',0),(505,52,'宣武区',3,'xuanwuqu',0),(506,52,'丰台区',3,'fengtaiqu',0),(507,52,'石景山区',3,'shijingshanqu',0),(508,52,'房山区',3,'fangshanqu',0),(509,52,'门头沟区',3,'mentougouqu',0),(510,52,'通州区',3,'tongzhouqu',0),(511,52,'顺义区',3,'shunyiqu',0),(512,52,'昌平区',3,'changpingqu',0),(513,52,'怀柔区',3,'huairouqu',0),(514,52,'平谷区',3,'pingguqu',0),(515,52,'大兴区',3,'daxingqu',0),(516,52,'密云县',3,'miyunxian',0),(517,52,'延庆县',3,'yanqingxian',0),(2703,321,'长宁区',3,'changningqu',0),(2704,321,'闸北区',3,'zhabeiqu',0),(2705,321,'闵行区',3,'xingqu',0),(2706,321,'徐汇区',3,'xuhuiqu',0),(2707,321,'浦东新区',3,'pudongxinqu',0),(2708,321,'杨浦区',3,'yangpuqu',0),(2709,321,'普陀区',3,'putuoqu',0),(2710,321,'静安区',3,'jinganqu',0),(2711,321,'卢湾区',3,'luwanqu',0),(2712,321,'虹口区',3,'hongkouqu',0),(2713,321,'黄浦区',3,'huangpuqu',0),(2714,321,'南汇区',3,'nanhuiqu',0),(2715,321,'松江区',3,'songjiangqu',0),(2716,321,'嘉定区',3,'jiadingqu',0),(2717,321,'宝山区',3,'baoshanqu',0),(2718,321,'青浦区',3,'qingpuqu',0),(2719,321,'金山区',3,'jinshanqu',0),(2720,321,'奉贤区',3,'fengxianqu',0),(2721,321,'崇明县',3,'chongmingxian',0),(2912,343,'和平区',3,'hepingqu',0),(2913,343,'河西区',3,'hexiqu',0),(2914,343,'南开区',3,'nankaiqu',0),(2915,343,'河北区',3,'hebeiqu',0),(2916,343,'河东区',3,'hedongqu',0),(2917,343,'红桥区',3,'hongqiaoqu',0),(2918,343,'东丽区',3,'dongliqu',0),(2919,343,'津南区',3,'jinnanqu',0),(2920,343,'西青区',3,'xiqingqu',0),(2921,343,'北辰区',3,'beichenqu',0),(2922,343,'塘沽区',3,'tangguqu',0),(2923,343,'汉沽区',3,'hanguqu',0),(2924,343,'大港区',3,'dagangqu',0),(2925,343,'武清区',3,'wuqingqu',0),(2926,343,'宝坻区',3,'baoqu',0),(2927,343,'经济开发区',3,'jingjikaifaqu',0),(2928,343,'宁河县',3,'ninghexian',0),(2929,343,'静海县',3,'jinghaixian',0),(2930,343,'蓟县',3,'jixian',0),(3325,394,'合川区',3,'hechuanqu',0),(3326,394,'江津区',3,'jiangjinqu',0),(3327,394,'南川区',3,'nanchuanqu',0),(3328,394,'永川区',3,'yongchuanqu',0),(3329,394,'南岸区',3,'nananqu',0),(3330,394,'渝北区',3,'yubeiqu',0),(3331,394,'万盛区',3,'wanshengqu',0),(3332,394,'大渡口区',3,'dadukouqu',0),(3333,394,'万州区',3,'wanzhouqu',0),(3334,394,'北碚区',3,'beiqu',0),(3335,394,'沙坪坝区',3,'shapingbaqu',0),(3336,394,'巴南区',3,'bananqu',0),(3337,394,'涪陵区',3,'fulingqu',0),(3338,394,'江北区',3,'jiangbeiqu',0),(3339,394,'九龙坡区',3,'jiulongpoqu',0),(3340,394,'渝中区',3,'yuzhongqu',0),(3341,394,'黔江开发区',3,'qianjiangkaifaqu',0),(3342,394,'长寿区',3,'changshouqu',0),(3343,394,'双桥区',3,'shuangqiaoqu',0),(3344,394,'綦江县',3,'jiangxian',0),(3345,394,'潼南县',3,'nanxian',0),(3346,394,'铜梁县',3,'tongliangxian',0),(3347,394,'大足县',3,'dazuxian',0),(3348,394,'荣昌县',3,'rongchangxian',0),(3349,394,'璧山县',3,'shanxian',0),(3350,394,'垫江县',3,'dianjiangxian',0),(3351,394,'武隆县',3,'wulongxian',0),(3352,394,'丰都县',3,'fengduxian',0),(3353,394,'城口县',3,'chengkouxian',0),(3354,394,'梁平县',3,'liangpingxian',0),(3355,394,'开县',3,'kaixian',0),(3356,394,'巫溪县',3,'wuxixian',0),(3357,394,'巫山县',3,'wushanxian',0),(3358,394,'奉节县',3,'fengjiexian',0),(3359,394,'云阳县',3,'yunyangxian',0),(3360,394,'忠县',3,'zhongxian',0),(3361,394,'石柱',3,'shizhu',0),(3362,394,'彭水',3,'pengshui',0),(3363,394,'酉阳',3,'youyang',0),(3364,394,'秀山',3,'xiushan',0),(3365,395,'沙田区',3,'shatianqu',0),(3366,395,'东区',3,'dongqu',0),(3367,395,'观塘区',3,'guantangqu',0),(3368,395,'黄大仙区',3,'huangdaxianqu',0),(3369,395,'九龙城区',3,'jiulongchengqu',0),(3370,395,'屯门区',3,'tunmenqu',0),(3371,395,'葵青区',3,'kuiqingqu',0),(3372,395,'元朗区',3,'yuanlangqu',0),(3373,395,'深水埗区',3,'shenshui',0),(3374,395,'西贡区',3,'xigongqu',0),(3375,395,'大埔区',3,'dapuqu',0),(3376,395,'湾仔区',3,'wanziqu',0),(3377,395,'油尖旺区',3,'youjianwangqu',0),(3378,395,'北区',3,'beiqu',0),(3379,395,'南区',3,'nanqu',0),(3380,395,'荃湾区',3,'wanqu',0),(3381,395,'中西区',3,'zhongxiqu',0),(3382,395,'离岛区',3,'lidaoqu',0),(3383,396,'澳门',3,'aomen',0),(3384,397,'台北',3,'taibei',0),(3385,397,'高雄',3,'gaoxiong',0),(3386,397,'基隆',3,'jilong',0),(3387,397,'台中',3,'taizhong',0),(3388,397,'台南',3,'tainan',0),(3389,397,'新竹',3,'xinzhu',0),(3390,397,'嘉义',3,'jiayi',0),(3391,397,'宜兰县',3,'yilanxian',0),(3392,397,'桃园县',3,'taoyuanxian',0),(3393,397,'苗栗县',3,'miaolixian',0),(3394,397,'彰化县',3,'zhanghuaxian',0),(3395,397,'南投县',3,'nantouxian',0),(3396,397,'云林县',3,'yunlinxian',0),(3397,397,'屏东县',3,'pingdongxian',0),(3398,397,'台东县',3,'taidongxian',0),(3399,397,'花莲县',3,'hualianxian',0),(3400,397,'澎湖县',3,'penghuxian',0),(1,0,'全国',1,'quanguo',0);
/*!40000 ALTER TABLE `fanwe_region_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role`
--

DROP TABLE IF EXISTS `fanwe_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='//后台的权限节点';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role`
--

LOCK TABLES `fanwe_role` WRITE;
/*!40000 ALTER TABLE `fanwe_role` DISABLE KEYS */;
INSERT INTO `fanwe_role` VALUES (4,'测试管理员',1,0);
/*!40000 ALTER TABLE `fanwe_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role_access`
--

DROP TABLE IF EXISTS `fanwe_role_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='//访问权限';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role_access`
--

LOCK TABLES `fanwe_role_access` WRITE;
/*!40000 ALTER TABLE `fanwe_role_access` DISABLE KEYS */;
INSERT INTO `fanwe_role_access` VALUES (100,4,7064,131,0),(99,4,681,131,0),(98,4,198,15,0),(97,4,19,15,0),(101,4,7065,131,0),(102,4,7066,131,0),(103,4,0,142,0),(104,4,0,145,0),(105,4,0,144,0);
/*!40000 ALTER TABLE `fanwe_role_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role_group`
--

DROP TABLE IF EXISTS `fanwe_role_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nav_id` int(11) NOT NULL COMMENT '后台导航分组ID',
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='//组权限';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role_group`
--

LOCK TABLES `fanwe_role_group` WRITE;
/*!40000 ALTER TABLE `fanwe_role_group` DISABLE KEYS */;
INSERT INTO `fanwe_role_group` VALUES (1,'首页',1,0,1,1),(5,'系统设置',3,0,1,1),(7,'管理员',3,0,1,2),(8,'数据库操作',3,0,1,6),(9,'系统日志',3,0,1,7),(19,'菜单设置',11,0,1,17),(28,'邮件管理',10,0,1,26),(29,'短信管理',10,0,1,27),(31,'广告设置',11,0,1,29),(33,'队列管理',10,0,1,31),(69,'会员管理',5,0,1,31),(70,'会员整合',5,0,1,32),(71,'同步登录',5,0,1,33),(72,'项目管理',13,0,1,33),(73,'项目支持',13,0,1,34),(74,'项目点评',13,0,1,35),(75,'支付接口',14,0,1,1),(76,'付款记录',14,0,1,2),(77,'消息模板',10,0,1,1),(78,'提现记录',14,0,1,3),(79,'友情链接',11,0,1,36),(80,'文章管理',11,0,1,37),(81,'文章分类管理',11,0,1,38),(82,'地区管理',13,0,1,39),(83,'系统监测',3,0,1,83),(62,'手机端设置',3,0,1,1),(84,'问卷调查设置',11,0,1,84),(85,'会员邀请',5,0,1,31),(86,'回报项目统计',15,0,1,86),(87,'股权项目统计',15,0,1,87),(88,'平台统计',15,0,1,88),(89,'留言列表',5,0,1,89);
/*!40000 ALTER TABLE `fanwe_role_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role_module`
--

DROP TABLE IF EXISTS `fanwe_role_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8 COMMENT='//模块权限';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role_module`
--

LOCK TABLES `fanwe_role_module` WRITE;
/*!40000 ALTER TABLE `fanwe_role_module` DISABLE KEYS */;
INSERT INTO `fanwe_role_module` VALUES (123,'DealOrder','项目支持',1,0),(124,'DealComment','项目点评',1,0),(125,'PaymentNotice','付款记录',1,0),(126,'UserRefund','用户提现',1,0),(127,'PromoteMsg','推广模块',1,0),(128,'PromoteMsgList','推广队列',1,0),(130,'LinkGroup','友情链接分组',1,0),(129,'Link','友情链接',1,0),(131,'UserLevel','会员等级',1,0),(132,'DealLevel','项目等级',0,0),(133,'Article','文章',1,0),(134,'ArticleCate','文章分类',1,0),(135,'RegionConf','地区',1,0),(136,'SqlCheck','系统监测',1,0),(93,'MAdv','手机端广告',1,0),(137,'UserInvestor','投资人申请管理',1,0),(139,'Vote','问卷调查',1,0),(138,'Bank','提现银行设置',1,0),(141,'Collocation','资金托管',1,0),(140,'UserCarry','提现手续费',1,0),(142,'Referrals','会员邀请',1,0),(143,'Statistics','统计',1,0),(144,'Message','留言列表',1,0),(145,'MessageCate','留言分类列表',1,0),(151,'UserFreeze','冻结资金列表',1,0),(153,'MoneyFreezes','诚意金管理',1,0),(152,'UserUnfreeze','申请解冻资金列表',1,0),(155,'YeepayWithdraw','第三方托管提现',1,0),(154,'YeepayRecharge','第三方托管充值',1,0),(156,'MoneyFreeze','第三方托管诚意金',1,0),(160,'DealHouseCate','房产众筹分类',1,0),(161,'StockTransfer','债权转让管理',1,0),(162,'Finance','融资公司管理',1,0),(164,'StationMessage','站内消息管理',1,0),(165,'Contract','合同范本设置',1,0),(166,'WeixinConf','微信第三方平台',1,0),(167,'WeixinInfo','微信配置',1,0),(168,'WeixinReply','微信回复设置',1,0),(169,'WeixinTemplate','微信模板设置',1,0),(170,'WeixinUser','微信会员管理',1,0),(171,'Licai','理财管理',1,0),(172,'LicaiHistory','收益率管理',1,0),(173,'LicaiRecommend','个性推荐设置',1,0),(174,'LicaiOrder','购买记录',1,0),(175,'LicaiDealshow','首页订单展示设置',1,0),(176,'LicaiBank','银行列表',1,0),(177,'LicaiFundType','基金种类',1,0),(178,'LicaiFundBrand','基金品牌',1,0),(179,'LicaiRedempte','赎回管理',1,0),(180,'LicaiNear','理财发放管理',1,0),(181,'LicaiSend','理财已发放管理',1,0),(182,'LicaiAdvance','垫付单管理',1,0),(183,'Goods','积分商城商品管理',1,0),(184,'GoodsCate','积分商城分类管理',1,0),(185,'GoodsOrder','积分商城兑换管理',1,0),(186,'LicaiHoliday','理财假日管理',1,0),(122,'Faq','常见问题',1,0),(121,'Help','站点帮助',1,0),(120,'IndexImage','轮播广告图',1,0),(119,'Payment','支付接口',1,0),(118,'Deal','项目管理',1,0),(117,'DealCate','项目分类',1,0),(116,'ApiLogin','同步登录',1,0),(115,'Integrate','会员整合',1,0),(114,'MsgTemplate','消息模板管理',1,0),(113,'User','会员管理',1,0),(92,'Cache','缓存处理',1,0),(56,'DealMsgList','业务群发队列',1,0),(53,'Adv','广告模块',1,0),(48,'Sms','短信接口',1,0),(47,'MailServer','邮件服务器',1,0),(36,'Nav','导航菜单',1,0),(58,'Index','首页',1,0),(19,'File','文件管理',1,0),(15,'Log','系统日志',1,0),(13,'Database','数据库',1,0),(12,'Conf','系统配置',1,0),(6,'Admin','管理员',1,0),(5,'Role','权限组别',1,0),(245,'UserConfirmRefund','提现确认记录',1,0),(188,'LicaiInterest','收益率设置',1,0),(189,'ReferralsTotal','统计',1,0),(190,'DealOnline','上线产品项目',1,0),(191,'DealSubmit','未审核产品项目',1,0),(192,'DealDelete','产品项目回收站',1,0),(193,'DealSelflessCate','公益众筹分类',1,0),(194,'DealSelflessOnline','上线公益项目',1,0),(195,'DealSelflessSubmit','未审核公益项目',1,0),(196,'DealSelflessDelete','公益项目回收站',1,0),(197,'DealInvestorCate','股权众筹分类',1,0),(198,'DealInvestorOnline','上线股权项目',1,0),(199,'DealInvestorSubmit','未审核股权项目',1,0),(200,'DealInvestorDelete','股权项目回收站',1,0),(201,'DealFinanceCate','融资众筹分类',1,0),(202,'DealFinanceOnline','上线融资项目',1,0),(203,'DealFinanceSubmit','未审核融资项目',1,0),(204,'DealFinanceDelete','融资项目回收站',1,0),(205,'DealHouseOnline','上线房产项目',1,0),(206,'DealHouseSubmit','未审核房产项目',1,0),(207,'DealHouseDelete','房产项目回收站',1,0),(208,'DealSubmitUserBonus','未审核分红列表',1,0),(209,'DealSubmitFixedInterest','未审核固定回报列表',1,0),(210,'DealSubmitBuyHouseEarnings','未审核买房收益列表',1,0),(211,'StockTransferList','已审核转让列表',1,0),(212,'FinanceSubmit','未审核公司',1,0),(213,'ArticleCateTrash','文章分类回收站',1,0),(214,'ArticleTrash','文章回收站',1,0),(215,'PromoteMsgMail','邮件列表',1,0),(216,'PromoteMsgSms','短信列表',1,0),(217,'StationMessageMsgList','站内消息队列列表',1,0),(218,'StatisticsProject','项目统计',1,0),(219,'StatisticsUsernumTotal','人数统计',1,0),(220,'StatisticsMoneyTotal','金额统计',1,0),(221,'StatisticsHasbackTotal','回报统计',1,0),(222,'StatisticsOverdueTotal','逾期统计',1,0),(223,'StatisticsInvesteTotal','项目统计',1,0),(224,'StatisticsInvestorsTotal','投资人统计',1,0),(225,'StatisticsFinancingAmountTotal','融资金额统计',1,0),(226,'StatisticsBreachConventionTotal','违约统计',1,0),(227,'StatisticsMoneyInchangeTotal','充值统计',1,0),(228,'StatisticsMoneyCarryBankTotal','提现统计',1,0),(229,'StatisticsUserTotal','用户统计',1,0),(230,'StatisticsSiteCostsTotal','网站费用统计',1,0),(231,'RoleTrash','管理员分组回收站',1,0),(232,'AdminTrash','管理员回收站',1,0),(233,'DatabaseSql','SQL操作',1,0),(234,'WeixinInfoNavSetting','自定义菜单',1,0),(235,'WeixinReplyOnfocus','关注时回复',1,0),(236,'WeixinReplyTxt','文本回复',1,0),(237,'WeixinReplyNews','图文回复',1,0),(238,'WeixinReplyLbs','LBS回复',1,0),(239,'WeixinTemplateSetIndustry','设置行业',1,0),(240,'WeixinTemplateMsglist','模板消息队列',1,0),(241,'WeixinUserGroups','分组管理',1,0),(242,'WeixinUserMessageSend','普通消息群发',1,0),(243,'WeixinUserAdvanced','高级群发',1,0),(244,'LicaiRedempteBefore','预热期赎回管理',1,0);
/*!40000 ALTER TABLE `fanwe_role_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role_nav`
--

DROP TABLE IF EXISTS `fanwe_role_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='// 导航权限';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role_nav`
--

LOCK TABLES `fanwe_role_nav` WRITE;
/*!40000 ALTER TABLE `fanwe_role_nav` DISABLE KEYS */;
INSERT INTO `fanwe_role_nav` VALUES (1,'首页',0,1,1),(3,'系统设置',0,1,10),(5,'会员管理',0,1,3),(10,'短信邮件',0,1,7),(13,'项目管理',0,1,4),(14,'支付管理',0,1,5),(11,'前端设置',0,1,6),(15,'统计模块',0,1,8);
/*!40000 ALTER TABLE `fanwe_role_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_role_node`
--

DROP TABLE IF EXISTS `fanwe_role_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_role_node` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_role_node`
--

LOCK TABLES `fanwe_role_node` WRITE;
/*!40000 ALTER TABLE `fanwe_role_node` DISABLE KEYS */;
INSERT INTO `fanwe_role_node` VALUES (7276,'breach_convention_info','查看',1,0,0,226,6881),(7275,'export_csv_financing_amount_info','导出融资金额统计明细',1,0,0,225,6880),(7272,'investors_info','查看',1,0,0,224,6879),(7273,'export_csv_investors_info','导出投资人统计明细',1,0,0,224,6879),(7274,'financing_amount_info','查看',1,0,0,225,6880),(7271,'export_csv_investe_info','导出项目统计明细',1,0,0,223,6875),(7270,'export_csv_overdue_info','导出逾期明细',1,0,0,222,6878),(7269,'overdue_info','查看',1,0,0,222,6878),(7268,'export_csv_hasback_info','导出回报明细',1,0,0,221,6877),(7267,'hasback_info','查看',1,0,0,221,6877),(7264,'export_csv_usernum_info','导出支持明细',1,0,0,219,6874),(7266,'export_csv_money_info','导出金额明细',1,0,0,220,6876),(7265,'money_info','查看',1,0,0,220,6876),(7263,'usernum_info','查看',1,0,0,219,6874),(7262,'export_csv_project_info','导出项目明细',1,0,0,218,6872),(7261,'edit_sms','编辑',1,0,0,216,668),(7260,'add_sms','新增',1,0,0,216,668),(7466,'show_content','查看',1,0,0,217,0),(7257,'do_vote_ask','编辑执行',1,0,0,139,6869),(7255,'update_deal_item','编辑执行',1,0,0,118,7153),(7254,'edit_deal_item','编辑子项目',1,0,0,118,7153),(7253,'del_deal_item','删除子项目',1,0,0,118,7153),(7252,'add_deal_item','添加子项目',1,0,0,118,7153),(7457,'toogle_status','设置首页类型',1,0,0,194,0),(7248,'add_deal_item','添加子项目',1,0,0,191,632),(7249,'del_deal_item','删除子项目',1,0,0,191,632),(7250,'edit_deal_item','编辑子项目',1,0,0,191,632),(7449,'order_index','购买记录列表',1,0,0,171,0),(7245,'content','查看',1,0,0,144,6887),(7244,'investor_go_allow','审核执行',1,0,0,137,6867),(7243,'account','账户管理',1,0,0,113,607),(7242,'restore','恢复',1,0,0,118,7240),(7241,'foreverdelete','彻底删除',1,0,0,118,7240),(7240,'delete_house_index','项目回收站',1,0,0,118,7240),(7239,'foreverdelete','彻底删除',1,0,0,118,7153),(7238,'deal_item','子项目',1,0,0,118,7153),(7237,'edit','编辑上架',1,0,0,118,7153),(7236,'do_delivery','发货',1,0,0,185,7052),(7235,'cancel_order_do','取消兑换',1,0,0,185,7052),(7233,'delete','删除记录',1,0,0,245,7231),(7234,'export_csv','导出',1,0,0,245,7231),(7232,'refund_confirm','确认提现',1,0,0,245,7231),(7231,'confirm_list','提现确认列表',1,0,78,245,7231),(7230,'update_deal_item','更新子项目',1,0,0,118,7134),(7228,'edit_deal_item','编辑子项目',1,0,0,118,7134),(7229,'del_deal_item','删除子项目',1,0,0,118,7134),(7226,'deal_item','子项目列表',1,0,0,118,7134),(7227,'add_deal_item','新增子项目',1,0,0,118,7134),(7225,'del_pay_log','删除发放',1,0,0,118,7134),(7224,'save_pay_log','发放',1,0,0,118,7134),(7223,'pay_log','筹款发放',1,0,0,118,7134),(7221,'del_deal_log','删除日志',1,0,0,118,7134),(7222,'sharefee_list','子项目列表',1,0,0,118,7134),(7220,'deal_log','项目日志',1,0,0,118,7134),(7219,'set_sort','排序',1,0,0,118,7134),(7217,'delete','删除',1,0,0,118,7134),(7215,'add','新增房产众筹 ',1,0,0,118,7134),(7216,'edit','编辑',1,0,0,118,7134),(7213,'foreverdelete','彻底删除 ',1,0,0,165,6945),(7214,'restore','恢复 ',1,0,0,165,6945),(7212,'set_sort','排序 ',1,0,0,122,646),(7211,'show_content','显示内容',1,0,0,128,674),(7202,'edit_mail','编辑',1,0,0,215,667),(7203,'add_mail','新增 ',1,0,0,215,667),(7201,'set_effect','设置状态 ',1,0,0,139,6869),(7198,'foreverdelete','彻底删除 ',1,0,0,36,43),(7199,'vote_ask','编辑问卷 ',1,0,0,139,6869),(7200,'set_sort','排序 ',1,0,0,139,6869),(7196,'get_pay_list','投资列表 ',1,0,0,123,656),(7197,'add','新增 ',1,0,0,36,43),(7195,'set_sort','排序',1,0,0,160,6912),(7193,'set_sort','排序',1,0,0,117,627),(7194,'set_effect','修改状态',1,0,0,160,6912),(7191,'export_q_csv','导出',1,0,0,244,7031),(7192,'edit','编辑',1,0,0,117,627),(7190,'status','同意赎回',1,0,0,244,7031),(7188,'save_industry','保存',1,0,0,239,6967),(7189,'syn_industry_to_weixin','同步到微信公众平台',1,0,0,239,6967),(7187,'save_onfocusn','保存关注时图文回复',1,0,0,235,6959),(7186,'save_onfocus','保存关注时文本回复',1,0,0,235,6959),(7184,'save_dtext','保存默认文本回复',1,0,0,168,6958),(7185,'save_dnews','保存默认图文回复',1,0,0,168,6958),(7183,'toogle_status','修改状态',1,0,0,93,484),(7182,'set_sort','排序',1,0,0,93,484),(7181,'edit','编辑',1,0,0,93,484),(7180,'foreverdelete','彻底删除',1,0,0,93,484),(7177,'site_costs_info','网站收益明细',1,0,0,230,6885),(7178,'export_csv_site_costs_info','导出',1,0,0,230,6885),(7179,'add','新增',1,0,0,93,484),(7176,'export_csv_user_total','导出',1,0,0,229,6884),(7175,'export_csv_money_carry_bank_total','导出',1,0,0,228,6883),(7174,'export_csv_money_inchange_total','导出',1,0,0,227,6882),(7173,'export_csv_breach_convention_total','导出',1,0,0,226,6881),(7172,'export_csv_financing_amount_total','导出',1,0,0,225,6880),(7171,'export_csv_investors_total','导出',1,0,0,224,6879),(7170,'export_csv_investe_total','导出',1,0,0,223,6875),(7169,'investe_info','查看项目',1,0,0,223,6875),(7168,'export_csv_overdue_total','导出',1,0,0,222,6878),(7167,'export_csv_hasback_total','导出',1,0,0,221,6877),(7165,'export_csv_usernum_total','导出',1,0,0,219,6874),(7166,'export_csv_money_total','导出',1,0,0,220,6876),(7164,'export_csv_project','导出',1,0,0,218,6872),(7163,'project_info','查看项目',1,0,0,218,6872),(7162,'edit','编辑',1,0,0,212,6930),(7161,'delete','删除',1,0,0,212,6930),(7206,'stock_transfer_cancel','取消交易 ',1,0,0,211,6924),(7158,'edit_user_bonus','审核',1,0,0,209,6919),(7157,'del_user_bonus','删除',1,0,0,209,6919),(7156,'foreverdelete','彻底删除',1,0,0,192,641),(7155,'edit','产品编辑上架',1,0,0,191,632),(7154,'deal_item','子项目',1,0,0,191,632),(7153,'submit_house_index','审核房产众筹',1,0,0,118,7153),(7151,'del_deal_item','删除子项目',1,0,0,118,7133),(7152,'update_deal_item','更新子项目',1,0,0,118,7133),(7150,'edit_deal_item','编辑子项目',1,0,0,118,7133),(7149,'add_deal_item','新增子项目',1,0,0,118,7133),(7148,'deal_item','子项目列表',1,0,0,118,7133),(7147,'del_pay_log','删除发放',1,0,0,118,7133),(7146,'save_pay_log','发放',1,0,0,118,7133),(7145,'pay_log','筹款发放',1,0,0,118,7133),(7144,'sharefee_list','子项目列表',1,0,0,118,7133),(7141,'deal_log','项目日志',1,0,0,118,7133),(7142,'del_deal_log','删除日志',1,0,0,118,7133),(7140,'set_sort','排序',1,0,0,118,7133),(7139,'add','新增',1,0,0,118,7133),(7138,'delete','删除',1,0,0,118,7133),(7137,'edit','编辑',1,0,0,118,7133),(7295,'edit','编辑',1,0,0,192,641),(7134,'house_index','房产众筹列表',1,0,0,118,7134),(7132,'foreverdelete','彻底删除',1,0,0,136,689),(7131,'delete','删除',1,0,0,138,6868),(7130,'edit','编辑',1,0,0,138,6868),(7129,'add','添加',1,0,0,138,6868),(7128,'set_sort','排序',1,0,0,138,6868),(7127,'set_sort','排序',1,0,0,121,642),(7126,'set_sort','排序',1,0,0,129,677),(7125,'set_effect','设置状态',1,0,0,129,677),(7124,'foreverdelete','彻底删除',1,0,0,129,677),(7123,'edit','编辑',1,0,0,129,677),(7122,'add','添加',1,0,0,129,677),(7121,'set_sort','排序',1,0,0,130,680),(7119,'foreverdelete','彻底删除',1,0,0,130,680),(7120,'set_effect','设置状态',1,0,0,130,680),(7117,'add','添加',1,0,0,130,680),(7118,'edit','编辑',1,0,0,130,680),(7116,'set_sort','排序',1,0,0,133,684),(7115,'set_effect','设置状态',1,0,0,133,684),(7112,'delete','删除',1,0,0,133,684),(7113,'foreverdelete','彻底删除',1,0,0,214,685),(7114,'restore','恢复',1,0,0,214,685),(7111,'edit','编辑',1,0,0,133,684),(7110,'add','添加',1,0,0,133,684),(7109,'set_sort','排序',1,0,0,134,686),(7108,'set_effect','设置状态',1,0,0,134,686),(7107,'restore','恢复',1,0,0,213,687),(7106,'foreverdelete','彻底删除',1,0,0,213,687),(7105,'delete','删除',1,0,0,134,686),(7103,'add','添加',1,0,0,134,686),(7104,'edit','编辑',1,0,0,134,686),(7209,'refund_allow','是否允许 ',1,0,0,126,664),(7101,'export_csv','导出',1,0,0,126,664),(7100,'uninstall','卸载接口',1,0,0,141,6871),(7099,'edit','更新托管接口',1,0,0,141,6871),(7098,'install','安装托管接口',1,0,0,141,6871),(7096,'edit','编辑',1,0,0,135,688),(7097,'foreverdelete','彻底删除',1,0,0,135,688),(7095,'add','新增',1,0,0,135,688),(7094,'set_effect','是否显示',1,0,0,124,661),(7091,'restore','回收站恢复',1,0,0,192,641),(7093,'delete','删除',1,0,0,124,661),(7092,'export_csv','项目导出',1,0,0,123,656),(7085,'deal_item','子项目列表',1,0,0,190,631),(7090,'foreverdelete','彻底删除',1,0,0,191,632),(7089,'update_deal_item','更新子项目',1,0,0,190,631),(7088,'del_deal_item','删除子项目',1,0,0,190,631),(7087,'edit_deal_item','编辑子项目',1,0,0,190,631),(7086,'add_deal_item','新增子项目',1,0,0,190,631),(7447,'recommend_add','新增个性推荐',1,0,0,171,1),(7083,'edit','编辑',1,0,0,190,631),(7077,'delete','移到回收站',1,0,0,190,631),(7082,'set_sort','设置排序',1,0,0,190,631),(7448,'order_export_csv','导出功能',1,0,0,171,0),(7075,'add','新增产品众筹',1,0,0,190,631),(7074,'delete','删除',1,0,5,144,6887),(7072,'set_effect','设置项目发起的分类',1,0,5,145,6886),(7071,'delete','删除',1,0,5,145,6886),(7070,'edit','编辑',1,0,5,145,6886),(7133,'add','新增',1,0,5,145,6886),(7069,'delete','删除',1,0,5,142,701),(7068,'export_csv','导出',1,0,5,142,701),(7067,'edit_dsffreezer','解冻资金',1,0,5,152,6893),(7066,'delete','删除会员等级',1,0,0,131,681),(7065,'edit','编辑会员等级',1,0,0,131,681),(7064,'add','添加会员等级',1,0,0,131,681),(7063,'userbank_delete','删除银行',1,0,0,113,0),(7061,'userbank_add','新增银行',1,0,0,113,0),(7062,'userbank_edit','编辑银行',1,0,0,113,0),(7060,'userbank_index','会员银行列表',1,0,0,113,0),(7059,'set_effect','会员状态修改',1,0,0,113,607),(7058,'edit','编辑假日',1,0,0,186,7055),(7056,'add','新增假日',1,0,0,186,7055),(7057,'delete','删除假日',1,0,0,186,7055),(7055,'index','假日列表',1,0,0,186,7055),(7053,'view_order','查看订单',1,0,0,185,7052),(7054,'export_csv','导出',1,0,0,185,7052),(7051,'set_effect','修改分类状态',1,0,0,184,7047),(7052,'index','兑换商品列表',1,0,0,185,7052),(7050,'edit','编辑商品分类',1,0,0,184,7047),(7049,'delete','删除商品分类',1,0,0,184,7047),(7048,'add','新增商品分类',1,0,0,184,7047),(7045,'edit','编辑商品',1,0,0,183,7042),(7046,'set_effect','修改状态',1,0,0,183,7042),(7047,'index','商品分类列表',1,0,0,184,7047),(7044,'delete','删除商品',1,0,0,183,7042),(7043,'add','新增商品',1,0,0,183,7042),(7042,'index','商品列表',1,0,0,183,7042),(7040,'update','收回',1,0,0,182,7039),(7041,'export_csv','导出',1,0,0,182,7039),(7039,'index','垫付单列表',1,0,0,182,7039),(7038,'export_csv','导出',1,0,0,181,7036),(7037,'view','查看详情',1,0,0,181,7036),(7036,'index','已发放理财列表',1,0,0,181,7036),(7035,'export_csv','导出',1,0,0,180,7032),(7034,'status','发放理财',1,0,0,180,7032),(7033,'view','查看详情',1,0,0,180,7032),(7032,'index','快到期理财列表',1,0,0,180,7032),(7031,'before_index','预热期赎回列表',1,0,0,244,7031),(7030,'export_csv','导出',1,0,0,179,7028),(7029,'status','同意赎回',1,0,0,179,7028),(7028,'index','赎回管理列表',1,0,0,179,7028),(7021,'delete','删除基金品牌',1,0,0,178,7018),(7020,'edit','编辑基金品牌',1,0,0,178,7018),(7019,'add','新增基金品牌',1,0,0,178,7018),(7018,'index','基金品牌列表',1,0,0,178,7018),(7017,'delete','删除基金种类',1,0,0,177,7014),(7014,'index','基金种类列表',1,0,0,177,7014),(7015,'add','新增基金种类',1,0,0,177,7014),(7016,'edit','编辑基金种类',1,0,0,177,7014),(7013,'delete','删除银行',1,0,0,176,7010),(7012,'edit','编辑银行',1,0,0,176,7010),(7011,'add','新增银行',1,0,0,176,7010),(7027,'delete','删除新增首页订单',1,0,0,175,7025),(7010,'index','银行列表',1,0,0,176,7010),(7026,'edit','编辑新增首页订单',1,0,0,175,7025),(7025,'index','首页订单列表',1,0,0,175,7025),(7009,'add','新增首页订单展示',1,0,0,175,7025),(7007,'edit','编辑',1,0,0,174,7004),(7008,'view','查看详情',1,0,0,174,7004),(7006,'export_q_csv','导出功能',1,0,0,174,7004),(7005,'order_list','订单列表',1,0,0,174,7004),(7004,'index','购买记录列表',1,0,0,174,7004),(7024,'delete','删除个性推荐',1,0,0,173,7003),(7023,'edit','编辑个性推荐',1,0,0,173,7003),(7022,'add','新增个性推荐',1,0,0,173,7003),(7003,'index','个性推荐列表',1,0,0,173,7003),(7002,'edit','编辑收益率',1,0,0,172,6999),(7001,'delete','删除收益率',1,0,0,172,6999),(7000,'q_add','新增收益率',1,0,0,172,6999),(6999,'index','收益率列表',1,0,0,172,6999),(6998,'export_csv','导出功能',1,0,0,171,6993),(6996,'edit','编辑',1,0,0,171,6993),(6997,'set_effect','设置状态',1,0,0,171,6993),(6995,'foreverdelete','彻底删除',1,0,0,171,6993),(6994,'add','新增',1,0,0,171,6993),(6993,'index','理财列表',1,0,0,171,6993),(6992,'nav_setting','自定义菜单列表',1,0,0,234,6992),(6990,'advanced','高级群发',1,0,0,243,6990),(6991,'advanced_add','新增高级群发信息',1,0,0,243,6990),(6989,'to_send_message','推送消息',1,0,0,242,6984),(6988,'newsAdvItem','获取高级群发节点',1,0,0,243,6990),(6987,'formatAdvSendMsg','格式化模板',1,0,0,242,6984),(6986,'message_send_del','删除普通群发信息',1,0,0,242,6984),(6985,'message_send_add','新增普通群发信息',1,0,0,242,6984),(6984,'message_send','普通消息群发',1,0,0,242,6984),(6983,'setgroup','批量粉丝转移',1,0,0,170,6980),(6977,'groups_synch','同步微信分组',1,0,0,241,6975),(6982,'send_info','刷新所有粉丝',1,0,0,170,6980),(6981,'send','获取最新粉丝',1,0,0,170,6980),(6980,'index','会员管理',1,0,0,170,6980),(6979,'groups_editor','编辑分组',1,0,0,241,6975),(6978,'delgroups','删除分组',1,0,0,241,6975),(6976,'groups_add','新增分组',1,0,0,241,6975),(6975,'groups','分组管理列表',1,0,0,241,6975),(6974,'reset_sending','重置队列发送',1,0,0,240,6970),(6972,'show_content','模板消息查看',1,0,0,240,6970),(6973,'send','模板消息发送',1,0,0,240,6970),(6971,'foreverdel','模板消息彻底删除',1,0,0,240,6970),(6970,'msglist','模板消息队列',1,0,0,240,6970),(6969,'install_tmpl','模板安装',1,0,0,169,6968),(6968,'index','模板列表',1,0,0,169,6968),(6967,'set_industry','设置行业',1,0,0,239,6967),(6966,'editlbs','添加LBS回复',1,0,0,238,6965),(6965,'lbs','LBS回复列表',1,0,0,238,6965),(6964,'editnews','添加图文回复',1,0,0,237,6963),(6963,'news','图文回复列表',1,0,0,237,6963),(6962,'delreply','删除文本回复',1,0,0,236,6960),(6961,'edittext','添加文本回复',1,0,0,236,6960),(6960,'txt','文本回复列表',1,0,0,236,6960),(6959,'onfocus','添加关注时回复',1,0,0,235,6959),(6958,'index','默认回复设置',1,0,0,168,6958),(6957,'syn_to_weixin','同步到公众平台',1,0,0,234,6992),(6954,'new_nav_row','添加主菜单',1,0,0,234,6992),(6955,'account_remove','删除',1,0,0,234,6992),(6956,'nav_save','保存',1,0,0,234,6992),(6953,'index','账户管理',1,0,0,167,6953),(6952,'update','编辑',1,0,0,166,6951),(6951,'index','第三方平台设置',1,0,0,166,6951),(6950,'trash','回收站',1,0,0,165,6945),(6948,'delete','删除',1,0,0,165,6945),(6949,'set_effect','状态设置',1,0,0,165,6945),(6947,'edit','编辑',1,0,0,165,6945),(6945,'index','合同范本列表',1,0,0,165,6945),(6946,'add','新增',1,0,0,165,6945),(6940,'index','站内消息列表',1,0,0,164,6940),(6941,'add','新增',1,0,0,164,6940),(6942,'edit','编辑',1,0,0,164,6940),(6943,'foreverdelete','彻底删除',1,0,0,164,6940),(6944,'msg_list','站内消息队列列表',1,0,0,217,6944),(6939,'vote_info','查看调查信息',1,0,0,139,6869),(6938,'vote_result','查看统计结果',1,0,0,139,6869),(7208,'install','安装 ',1,0,0,119,633),(7207,'edit','编辑 ',1,0,0,119,633),(7205,'edit_user_bonus','审核',1,0,0,210,6920),(6933,'foreverdelete','彻底删除',1,0,0,139,6869),(7204,'del_user_bonus','删除',1,0,0,210,6920),(6928,'set_sort','排序',1,0,0,162,6925),(6929,'edit','编辑',1,0,0,162,6925),(6930,'submit_index','未审核公司列表',1,0,0,212,6930),(6927,'toogle_status','推荐',1,0,0,162,6925),(6926,'delete','删除',1,0,0,162,6925),(6925,'online_index','公司列表',1,0,0,162,6925),(6924,'submit_stock_transfer','股权转让中列表',1,0,0,211,6924),(6923,'shelves','未审核转让审核',1,0,0,161,6921),(6922,'edit_investor','未审核转让编辑',1,0,0,161,6921),(6921,'submit_index','未审核转让列表',1,0,0,161,6921),(6920,'submit_buy_house_earnings','未审核买房收益列表',1,0,0,210,6920),(6919,'submit_fixed_interest','未审核固定回报列表',1,0,0,209,6919),(6918,'del_user_bonus','未审核分红删除',1,0,0,208,6916),(6917,'edit_user_bonus','未审核分红审核',1,0,0,208,6916),(6916,'submit_user_bonus','未审核分红列表',1,0,0,208,6916),(6915,'foreverdelete','删除分类',1,0,0,160,6912),(6914,'edit','更新分类',1,0,0,160,6912),(6913,'add','添加分类',1,0,0,160,6912),(6912,'index','分类列表',1,0,0,160,6912),(6901,'delete','删除记录',1,0,0,156,6900),(6900,'index','诚意金记录',1,0,0,156,6900),(6899,'delete','删除记录',1,0,0,155,6898),(6898,'index','提现记录',1,0,0,155,6898),(6897,'delete','删除记录',1,0,0,154,6896),(6896,'index','充值记录',1,0,0,154,6896),(6895,'delete','删除记录',1,0,0,153,6894),(6894,'index','诚意金记录',1,0,0,153,6894),(6893,'index','申请解冻资金列表',1,0,5,152,6893),(6892,'edit_dsffreezer','解冻诚意金',1,0,0,151,6891),(6891,'index','冻结资金列表',1,0,5,151,6891),(6911,'show_content','申请审核',1,0,5,137,6867),(6887,'index','留言列表',1,0,89,144,6887),(6886,'index','留言分类列表',1,0,89,145,6886),(6885,'site_costs_total','网站费用统计',1,0,88,230,6885),(6884,'user_total','用户统计',1,0,88,229,6884),(6883,'money_carry_bank_total','提现统计',1,0,88,228,6883),(6882,'money_inchange_total','充值统计',1,0,88,227,6882),(6881,'breach_convention_total','违约统计',1,0,87,226,6881),(6880,'financing_amount_total','融资金额',1,0,87,225,6880),(6879,'investors_total','投资人统计',1,0,87,224,6879),(6878,'overdue_total','逾期统计',1,0,86,222,6878),(6877,'hasback_total','回报统计',1,0,86,221,6877),(6876,'money_total','金额统计',1,0,86,220,6876),(6875,'investe_total','项目统计',1,0,87,223,6875),(6874,'usernum_total','人数统计',1,0,86,219,6874),(6872,'project','项目统计',1,0,86,218,6872),(701,'index','会员邀请返利列表',1,0,85,142,701),(700,'referrals_count','会员邀请统计',1,0,85,189,700),(698,'sharefee_list','查看分红列表',1,0,0,190,631),(6870,'config','提现手续费',1,0,5,140,0),(695,'add','增加页面',1,0,0,139,6869),(697,'delete','删除',1,0,0,139,6869),(6869,'edit','编辑页面',1,0,0,139,6869),(7210,'foreverdelete','彻底删除 ',1,0,0,216,668),(6871,'index','资金托管',1,0,75,141,6871),(693,'index','问卷调查列表',1,0,84,139,6869),(6868,'index','提现银行设置',1,0,5,138,6868),(6867,'index','投资申请列表',1,0,69,137,6867),(485,'savemobile','保存手机端配置',1,0,0,12,483),(484,'index','手机端广告列表',1,0,62,93,484),(483,'mobile','手机端配置',1,0,62,12,483),(689,'index','系统监测列表',1,0,83,136,689),(688,'index','地区列表',1,0,82,135,688),(687,'trash','分类回收站',1,0,81,213,687),(686,'index','分类列表',1,0,81,134,686),(685,'trash','文章回收站',1,0,80,214,685),(684,'index','文章列表',1,0,80,133,684),(682,'index','项目等级',0,0,72,132,682),(16,'index','系统配置',1,0,5,12,16),(17,'index','数据库备份列表',1,0,8,13,17),(681,'index','会员等级',1,0,69,131,681),(680,'index','分组列表',1,0,79,130,680),(677,'index','链接列表',1,0,79,129,677),(676,'foreverdelete','永久删除',1,0,0,128,674),(675,'send','手动发送',1,0,0,128,674),(674,'index','推广队列列表',1,0,33,128,674),(673,'foreverdelete','删除',1,0,0,215,667),(672,'index','查看对列',1,0,0,216,668),(671,'index','查看对列',1,0,0,215,0),(269,'uninstall','卸载',1,0,0,48,58),(270,'set_effect','设置生效',1,0,0,48,58),(271,'send_demo','发送测试短信',1,0,0,48,58),(474,'index','缓存处理',1,0,0,92,474),(475,'clear_parse_file','清空脚本样式缓存',1,0,0,92,474),(477,'clear_data','清空数据缓存',1,0,0,92,474),(480,'syn_data','同步数据',1,0,0,92,474),(481,'clear_image','清空图片缓存',1,0,0,92,474),(482,'clear_admin','清空后台缓存',1,0,0,92,474),(605,'index','消息模板',1,0,77,114,605),(668,'sms_index','短信列表',1,0,0,216,668),(667,'mail_index','邮件列表',1,0,28,215,667),(665,'delete','删除记录',1,0,0,126,664),(664,'index','提现审核列表',1,0,78,126,664),(663,'delete','删除记录',1,0,0,125,662),(661,'index','项目点评',1,0,74,124,661),(662,'index','付款记录',1,0,76,125,662),(660,'incharge','项目收款',1,0,0,123,656),(659,'delete','删除支持',1,0,0,123,656),(656,'index','项目支持',1,0,73,123,656),(657,'view','查看详情',1,0,0,123,656),(658,'refund','项目退款',1,0,0,123,656),(654,'del_deal_log','删除日志',1,0,0,190,631),(655,'batch_refund','批量退款',1,0,0,190,631),(653,'deal_log','项目日志',1,0,0,190,631),(652,'del_pay_log','删除发放',1,0,0,190,631),(650,'pay_log','筹款发放',1,0,0,190,631),(651,'add_pay_log','发放',1,0,0,190,631),(649,'foreverdelete','删除问题',1,0,0,122,646),(646,'index','常见问题',1,0,5,122,646),(647,'add','添加问题',1,0,0,122,646),(648,'edit','更新问题',1,0,0,122,646),(645,'foreverdelete','删除帮助',1,0,0,121,642),(644,'edit','修改帮助',1,0,0,121,642),(643,'add','添加帮助',1,0,0,121,642),(641,'delete_index','回收站',1,0,72,192,641),(642,'index','帮助列表',1,0,5,121,642),(640,'foreverdelete','删除广告',1,0,0,120,637),(639,'edit','修改广告',1,0,0,120,637),(638,'add','添加广告',1,0,0,120,637),(637,'index','轮播广告设置',1,0,5,120,637),(636,'uninstall','卸载接口',1,0,0,119,633),(633,'index','支付接口列表',1,0,75,119,633),(632,'submit_index','未审核项目',1,0,72,191,632),(631,'online_index','上线项目列表',1,0,72,190,631),(630,'foreverdelete','删除分类',1,0,0,117,627),(628,'add','添加分类',1,0,0,117,627),(627,'index','分类列表',1,0,72,117,627),(626,'uninstall','卸载接口',1,0,0,116,623),(625,'update','更新配置',1,0,0,116,623),(624,'insert','安装接口',1,0,0,116,623),(623,'index','同步登录接口',1,0,71,116,623),(622,'uninstall','卸载整合',1,0,0,115,620),(621,'save','执行整合',1,0,0,115,620),(620,'index','会员整合',1,0,70,115,620),(618,'weibo','微博列表',1,0,0,113,607),(619,'foreverdelete_weibo','删除微博',1,0,0,113,607),(617,'foreverdelete_consignee','删除配送地址',1,0,0,113,607),(616,'consignee','配送地址',1,0,0,113,607),(615,'foreverdelete_account_detail','删除帐户日志',1,0,0,113,607),(614,'account_detail','帐户日志',1,0,0,113,607),(613,'modify_account','会员资金变更',1,0,0,113,607),(612,'delete','删除会员',1,0,0,113,607),(607,'index','会员列表',1,0,69,113,607),(608,'add','添加会员',1,0,0,113,607),(610,'edit','编辑会员',1,0,0,113,607),(606,'update','更新模板',1,0,0,114,605),(265,'install','安装',1,0,0,48,58),(267,'edit','编辑',1,0,0,48,58),(264,'foreverdelete','永久删除',1,0,0,231,13),(263,'restore','恢复',1,0,0,231,13),(262,'delete','删除',1,0,0,5,11),(261,'set_effect','设置生效',1,0,0,5,11),(260,'update','编辑执行',1,0,0,5,11),(259,'edit','编辑页面',1,0,0,5,11),(258,'insert','添加执行',1,0,0,5,11),(257,'add','添加页面',1,0,0,5,11),(232,'set_sort','排序',1,0,0,36,43),(231,'set_effect','设置状态',1,0,0,36,43),(229,'edit','编辑',1,0,0,36,43),(211,'send_demo','发送测试邮件',1,0,0,47,57),(210,'foreverdelete','永久删除',1,0,0,47,57),(209,'set_effect','设置状态',1,0,0,47,57),(207,'edit','编辑',1,0,0,47,57),(205,'add','添加',1,0,0,47,57),(198,'foreverdelete','永久删除',1,0,0,15,19),(182,'deleteImg','删除图片',1,0,0,19,0),(149,'foreverdelete','永久删除',1,0,0,56,66),(181,'do_upload_img','图片控件上传',1,0,0,19,0),(148,'send','手动发送',1,0,0,56,66),(147,'show_content','显示内容',1,0,0,56,66),(105,'execute','执行SQL语句',1,0,0,233,18),(102,'restore','恢复备份',1,0,0,13,17),(101,'delete','删除备份',1,0,0,13,17),(100,'dump','备份数据',1,0,0,13,17),(99,'update','更新配置',1,0,0,12,16),(81,'set_effect','设置生效',1,0,0,53,63),(80,'foreverdelete','永久删除',1,0,0,53,63),(78,'edit','编辑',1,0,0,53,63),(77,'add','添加',1,0,0,53,63),(76,'set_default','设置默认管理员',1,0,0,6,14),(75,'foreverdelete','永久删除',1,0,0,232,15),(74,'restore','恢复',1,0,0,232,15),(73,'delete','删除',1,0,0,6,14),(72,'update','编辑执行',1,0,0,6,14),(70,'set_effect','设置生效',1,0,0,6,14),(68,'add','添加页面',1,0,0,6,14),(69,'edit','编辑页面',1,0,0,6,14),(66,'index','业务队列列表',1,0,33,56,66),(63,'index','广告列表',1,0,31,53,63),(58,'index','接口列表',1,0,29,48,58),(57,'index','邮件服务器列表',1,0,28,47,57),(43,'index','菜单列表',1,0,19,36,43),(24,'do_upload','编辑器图片上传',1,0,0,19,0),(19,'index','系统日志列表',1,0,9,15,19),(18,'sql','SQL操作',1,0,8,233,18),(15,'trash','管理员回收站',1,0,7,232,15),(14,'index','管理员列表',1,0,7,6,14),(13,'trash','管理员分组回收站',1,0,7,231,13),(11,'index','管理员分组列表',1,0,7,5,11),(334,'main','首页',1,0,1,58,0),(7277,'export_csv_breach_convention_info','导出违约统计明细',1,0,0,226,6881),(7278,'money_inchange_info','查看',1,0,0,227,6882),(7279,'export_csv_money_inchange_info','导出充值明细',1,0,0,227,6882),(7280,'money_carry_bank_info','查看',1,0,0,228,6883),(7281,'export_csv_money_carry_bank_info','导出提现明细',1,0,0,228,6883),(7282,'user_info','查看',1,0,0,229,6884),(7283,'export_csv_user_info','导出用户注册明细',1,0,0,229,6884),(7284,'set_sort','排序',1,0,0,120,637),(7285,'index','固存收益率列表',1,0,0,188,7285),(7286,'delete','删除收益率',1,0,0,188,7285),(7287,'edit','编辑收益率',1,0,0,188,7285),(7288,'add','新增收益率',1,0,0,188,7285),(7289,'export_csv','购买记录导出',1,0,0,174,7004),(7290,'set_status','拒绝赎回',1,0,0,244,7031),(7291,'set_status','拒绝赎回',1,0,0,179,7028),(7292,'set_status','发放执行',1,0,0,180,7032),(7293,'cancel_order','确认取消',1,0,0,185,7052),(7308,'set_sort','排序',1,0,0,193,0),(7297,'set_sort','排序',1,0,0,197,0),(7298,'set_sort','排序',1,0,0,201,0),(7299,'edit','编辑',1,0,0,193,0),(7300,'edit','编辑',1,0,0,197,0),(7301,'edit','编辑',1,0,0,201,0),(7302,'foreverdelete','删除分类',1,0,0,193,0),(7303,'foreverdelete','删除分类',1,0,0,197,0),(7304,'foreverdelete','删除分类',1,0,0,201,0),(7305,'add','添加分类',1,0,0,193,0),(7306,'add','添加分类',1,0,0,197,0),(7307,'add','添加分类',1,0,0,201,0),(7296,'index','分类列表',1,0,0,193,0),(7309,'index','分类列表',1,0,0,197,0),(7310,'index','分类列表',1,0,0,201,0),(7312,'get_sharefee_list','分红列表',1,0,0,198,0),(7313,'get_sharefee_list','分红列表',1,0,0,202,0),(7315,'deal_item','子项目列表',1,0,0,194,0),(7316,'deal_item','子项目列表',1,0,0,198,0),(7317,'deal_item','子项目列表',1,0,0,202,0),(7318,'deal_item','子项目列表',1,0,0,205,0),(7319,'update_deal_item','更新子项目',1,0,0,194,0),(7320,'update_deal_item','更新子项目',1,0,0,198,0),(7321,'update_deal_item','更新子项目',1,0,0,202,0),(7322,'update_deal_item','更新子项目',1,0,0,205,0),(7323,'del_deal_item','删除子项目',1,0,0,194,0),(7324,'del_deal_item','删除子项目',1,0,0,198,0),(7325,'del_deal_item','删除子项目',1,0,0,202,0),(7326,'del_deal_item','删除子项目',1,0,0,205,0),(7327,'edit_deal_item','编辑子项目',1,0,0,194,0),(7328,'edit_deal_item','编辑子项目',1,0,0,198,0),(7329,'edit_deal_item','编辑子项目',1,0,0,202,0),(7330,'edit_deal_item','编辑子项目',1,0,0,205,0),(7331,'add_deal_item','新增子项目',1,0,0,194,0),(7332,'add_deal_item','新增子项目',1,0,0,198,0),(7333,'add_deal_item','新增子项目',1,0,0,202,0),(7334,'add_deal_item','新增子项目',1,0,0,205,0),(7446,'interest_add','新增固存收益率',1,0,0,171,0),(7336,'edit_investor','编辑股权众筹',1,0,0,198,0),(7337,'edit_investor','编辑股权众筹',1,0,0,202,0),(7338,'edit_investor','编辑股权众筹',1,0,0,205,0),(7339,'edit','编辑',1,0,0,194,0),(7342,'edit','编辑',1,0,0,205,0),(7343,'delete','移到回收站',1,0,0,194,0),(7344,'delete','移到回收站',1,0,0,198,0),(7345,'delete','移到回收站',1,0,0,202,0),(7346,'delete','移到回收站',1,0,0,205,0),(7347,'set_sort','设置排序',1,0,0,194,0),(7348,'set_sort','设置排序',1,0,0,198,0),(7349,'set_sort','设置排序',1,0,0,202,0),(7350,'set_sort','设置排序',1,0,0,205,0),(7352,'add_investor','新增股权众筹',1,0,0,198,0),(7353,'add_investor','新增股权众筹',1,0,0,202,0),(7354,'add_investor','新增股权众筹',1,0,0,205,0),(7355,'add','新增公益众筹',1,0,0,194,0),(7445,'interest_edit','编辑固存收益率',1,0,0,171,0),(7444,'interest_delete','删除固存收益率',1,0,0,171,0),(7358,'add','新增产品众筹',1,0,0,205,0),(7359,'sharefee_list','查看分红列表',1,0,0,194,0),(7360,'sharefee_list','查看分红列表',1,0,0,198,0),(7361,'sharefee_list','查看分红列表',1,0,0,202,0),(7362,'sharefee_list','查看分红列表',1,0,0,205,0),(7363,'del_deal_log','删除日志',1,0,0,194,0),(7364,'del_deal_log','删除日志',1,0,0,198,0),(7365,'del_deal_log','删除日志',1,0,0,202,0),(7366,'del_deal_log','删除日志',1,0,0,205,0),(7367,'batch_refund','批量退款',1,0,0,194,0),(7368,'batch_refund','批量退款',1,0,0,198,0),(7369,'batch_refund','批量退款',1,0,0,202,0),(7370,'batch_refund','批量退款',1,0,0,205,0),(7371,'deal_log','项目日志',1,0,0,194,0),(7372,'deal_log','项目日志',1,0,0,198,0),(7373,'deal_log','项目日志',1,0,0,202,0),(7374,'deal_log','项目日志',1,0,0,205,0),(7375,'del_pay_log','删除发放',1,0,0,194,0),(7376,'del_pay_log','删除发放',1,0,0,198,0),(7377,'del_pay_log','删除发放',1,0,0,202,0),(7378,'del_pay_log','删除发放',1,0,0,205,0),(7379,'pay_log','筹款发放',1,0,0,194,0),(7380,'pay_log','筹款发放',1,0,0,198,0),(7381,'pay_log','筹款发放',1,0,0,202,0),(7382,'pay_log','筹款发放',1,0,0,205,0),(7383,'add_pay_log','发放',1,0,0,194,0),(7384,'add_pay_log','发放',1,0,0,198,0),(7385,'add_pay_log','发放',1,0,0,202,0),(7386,'add_pay_log','发放',1,0,0,205,0),(7387,'online_index','上线项目列表',1,0,0,194,0),(7388,'online_index','上线项目列表',1,0,0,198,0),(7389,'online_index','上线项目列表',1,0,0,202,0),(7390,'online_index','上线项目列表',1,0,0,205,0),(7423,'add_deal_item','添加子项目',1,0,0,195,0),(7396,'add_deal_item','添加子项目',1,0,0,199,0),(7397,'add_deal_item','添加子项目',1,0,0,203,0),(7398,'add_deal_item','添加子项目',1,0,0,206,0),(7399,'del_deal_item','删除子项目',1,0,0,195,0),(7400,'del_deal_item','删除子项目',1,0,0,199,0),(7401,'del_deal_item','删除子项目',1,0,0,203,0),(7402,'del_deal_item','删除子项目',1,0,0,206,0),(7403,'edit_deal_item','编辑子项目',1,0,0,195,0),(7404,'edit_deal_item','编辑子项目',1,0,0,199,0),(7405,'edit_deal_item','编辑子项目',1,0,0,203,0),(7406,'edit_deal_item','编辑子项目',1,0,0,206,0),(7443,'interest_index','固存收益率列表',1,0,0,171,0),(7408,'edit_investor','股权编辑上架',1,0,0,199,0),(7409,'edit_investor','股权编辑上架',1,0,0,203,0),(7411,'edit','公益编辑上架',1,0,0,195,0),(7414,'edit','编辑上架',1,0,0,206,0),(7415,'deal_item','子项目',1,0,0,195,0),(7416,'deal_item','子项目',1,0,0,199,0),(7417,'deal_item','子项目',1,0,0,203,0),(7418,'deal_item','子项目',1,0,0,206,0),(7419,'foreverdelete','彻底删除',1,0,0,195,0),(7420,'foreverdelete','彻底删除',1,0,0,199,0),(7421,'foreverdelete','彻底删除',1,0,0,203,0),(7422,'foreverdelete','彻底删除',1,0,0,206,0),(7395,'submit_index','未审核项目',1,0,0,195,0),(7424,'submit_index','未审核项目',1,0,0,199,0),(7425,'submit_index','未审核项目',1,0,0,203,0),(7426,'submit_index','未审核项目',1,0,0,206,0),(7427,'foreverdelete','彻底删除',1,0,0,196,0),(7428,'foreverdelete','彻底删除',1,0,0,200,0),(7429,'foreverdelete','彻底删除',1,0,0,204,0),(7430,'foreverdelete','彻底删除',1,0,0,207,0),(7431,'edit','编辑',1,0,0,196,0),(7432,'edit_investor','编辑',1,0,0,200,0),(7433,'edit_investor','编辑',1,0,0,204,0),(7434,'edit','编辑',1,0,0,207,0),(7435,'restore','回收站恢复',1,0,0,196,0),(7436,'restore','回收站恢复',1,0,0,200,0),(7437,'restore','回收站恢复',1,0,0,204,0),(7438,'restore','回收站恢复',1,0,0,207,0),(7439,'delete_index','回收站',1,0,0,196,0),(7440,'delete_index','回收站',1,0,0,200,0),(7441,'delete_index','回收站',1,0,0,204,0),(7442,'delete_index','回收站',1,0,0,207,0),(7450,'dealshow_add','新增首页订单展示',1,0,0,171,0),(7451,'history_edit','编辑余额宝收益率',1,0,0,171,0),(7452,'history_delete','删除余额宝收益率',1,0,0,171,0),(7453,'history_add','新增余额宝收益率',1,0,0,171,0),(7454,'history_index','余额宝收益率列表',1,0,0,171,0),(7455,'toogle_status','设置首页类型',1,0,0,190,0),(7456,'get_pay_list','支持列表',1,0,0,190,0),(7458,'get_pay_list','支持列表',1,0,0,194,0),(7459,'toogle_status','设置首页类型',1,0,0,198,0),(7460,'get_pay_list','支持列表',1,0,0,198,0),(7461,'toogle_status','设置首页类型',1,0,0,202,0),(7462,'get_pay_list','支持列表',1,0,0,202,0),(7463,'toogle_status','设置首页类型',1,0,0,205,0),(7464,'get_pay_list','支持列表',1,0,0,205,0);
/*!40000 ALTER TABLE `fanwe_role_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_sms`
--

DROP TABLE IF EXISTS `fanwe_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_sms` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_sms`
--

LOCK TABLES `fanwe_sms` WRITE;
/*!40000 ALTER TABLE `fanwe_sms` DISABLE KEYS */;
INSERT INTO `fanwe_sms` VALUES (9,'短信宝平台(<a href=http://www.smsbao.com/reg?r=10027 target=_blank>马上注册</a>)','','DXB','http://api.smsbao.com/','vitakung','vitakung','N;',1);
/*!40000 ALTER TABLE `fanwe_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_sql_check`
--

DROP TABLE IF EXISTS `fanwe_sql_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_sql_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `module_action` varchar(255) NOT NULL,
  `para` varchar(255) NOT NULL COMMENT '参数',
  `module_action_para` varchar(255) NOT NULL,
  `sql_num` int(11) NOT NULL,
  `sql_str` text NOT NULL,
  `query_time` float(11,6) NOT NULL DEFAULT '0.000000' COMMENT 'SQL运行时间',
  `run_time` float(11,6) NOT NULL DEFAULT '0.000000' COMMENT '运行时间',
  `memory_usage` float(11,4) NOT NULL DEFAULT '0.0000' COMMENT '内存占用情况',
  `gzip_on` tinyint(1) NOT NULL COMMENT '是否开启gzip_on',
  `url` varchar(255) NOT NULL COMMENT '请求地址',
  `file_name` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8 COMMENT='//系统监测';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_sql_check`
--

LOCK TABLES `fanwe_sql_check` WRITE;
/*!40000 ALTER TABLE `fanwe_sql_check` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_sql_check` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_stock_transfer`
--

DROP TABLE IF EXISTS `fanwe_stock_transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_stock_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '转让人会员ID',
  `user_name` varchar(255) NOT NULL,
  `purchaser_id` int(11) NOT NULL DEFAULT '0' COMMENT '购买人ID',
  `purchaser_name` varchar(255) NOT NULL,
  `invest_id` int(11) NOT NULL COMMENT '转出交易ID',
  `price` decimal(20,2) NOT NULL COMMENT '转让的金额',
  `stock_value` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '估值',
  `num` int(11) NOT NULL COMMENT '份数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '上线天数，仅提供管理员审核参考',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 表示未审核  1表示审核通过 2表示审核不通过',
  `is_edit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 表示不允许编辑 1表示允许编辑',
  `is_success` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示不成功 1表示成功',
  `deal_order_id` int(11) NOT NULL DEFAULT '0',
  `invest_id_2` int(11) NOT NULL COMMENT '转入交易ID',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '限制',
  `support_num` int(11) NOT NULL DEFAULT '0' COMMENT '限制2',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属行业',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='//股权转让';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_stock_transfer`
--

LOCK TABLES `fanwe_stock_transfer` WRITE;
/*!40000 ALTER TABLE `fanwe_stock_transfer` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_stock_transfer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user`
--

DROP TABLE IF EXISTS `fanwe_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `money` decimal(20,2) NOT NULL COMMENT '金额',
  `login_time` int(11) NOT NULL,
  `login_ip` varchar(50) NOT NULL,
  `province` varchar(10) NOT NULL,
  `city` varchar(10) NOT NULL,
  `password_verify` varchar(255) NOT NULL COMMENT '找回密码的验证号',
  `sex` tinyint(1) NOT NULL COMMENT '性别',
  `build_count` int(11) NOT NULL COMMENT '发起的项目数',
  `support_count` int(11) NOT NULL COMMENT '支持的项目数',
  `focus_count` int(11) NOT NULL COMMENT '关注的项目数',
  `integrate_id` int(11) NOT NULL,
  `intro` text NOT NULL COMMENT '个人简介',
  `ex_real_name` varchar(255) NOT NULL COMMENT '发布者真实姓名',
  `ex_account_bank` text NOT NULL,
  `ex_account_info` text NOT NULL COMMENT '银行帐号等信息',
  `ex_contact` text NOT NULL COMMENT '联系方式',
  `ex_qq` text NOT NULL,
  `code` varchar(255) NOT NULL,
  `sina_id` varchar(255) NOT NULL,
  `sina_token` varchar(255) NOT NULL,
  `sina_secret` varchar(255) NOT NULL,
  `sina_url` varchar(255) NOT NULL,
  `tencent_id` varchar(255) NOT NULL,
  `tencent_token` varchar(255) NOT NULL,
  `tencent_secret` varchar(255) NOT NULL,
  `tencent_url` varchar(255) NOT NULL,
  `verify` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `user_type` int(11) NOT NULL,
  `is_has_send_success` tinyint(1) NOT NULL,
  `is_bank` tinyint(1) NOT NULL COMMENT '（0表示银行账户信息未提交，1表示银行账户信息提交）',
  `verify_time` int(11) DEFAULT NULL COMMENT '验证发送时间',
  `verify_setting` varchar(255) DEFAULT NULL COMMENT '设置时候的验证码',
  `verify_setting_time` int(11) NOT NULL COMMENT '设置时间',
  `identify_name` varchar(255) NOT NULL COMMENT '身份证名称',
  `identify_number` varchar(255) NOT NULL COMMENT '身份证号码',
  `identify_positive_image` varchar(255) NOT NULL COMMENT '身份证正面',
  `identify_nagative_image` varchar(255) NOT NULL COMMENT '身份证反面',
  `identify_business_licence` varchar(255) NOT NULL COMMENT '营业执照',
  `identify_business_code` varchar(255) NOT NULL COMMENT '组织机构代码证',
  `identify_business_tax` varchar(255) NOT NULL COMMENT '税务登记证',
  `identify_business_name` varchar(255) NOT NULL COMMENT '机构名称',
  `is_investor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '判断是否为投资者，默认0表示非投资者，1表示投资者,2 表示机构投资者',
  `mortgage_money` decimal(20,2) NOT NULL COMMENT '理财冻结资金',
  `investor_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '投资认证是否通过 0 表示未通过，1表示通过审核',
  `investor_status_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核结果通知用户，0表示未发送，1表示已发送',
  `break_num` tinyint(1) NOT NULL DEFAULT '0' COMMENT '毁约次数',
  `wx_openid` varchar(255) NOT NULL COMMENT '微信openid',
  `investor_send_info` varchar(255) NOT NULL COMMENT '审核信息',
  `paypassword` varchar(255) NOT NULL COMMENT '提现和支付密码',
  `source_url` varchar(255) NOT NULL COMMENT '来源url',
  `ips_mer_code` varchar(10) NOT NULL COMMENT '由IPS颁发的商户号 is_investor = 2',
  `ips_acct_no` varchar(255) DEFAULT NULL COMMENT '托管平台账户号',
  `pid` int(11) NOT NULL COMMENT '推荐人id',
  `score` int(11) NOT NULL COMMENT '积分',
  `is_send_referrals` tinyint(1) NOT NULL COMMENT '是否发放推荐返利给推存人，0：没有推荐人,不用发放返利，1：未发，2.已发',
  `referral_count` int(11) NOT NULL COMMENT '返利数量',
  `point` int(11) NOT NULL COMMENT '信用值',
  `cate_name` varchar(255) NOT NULL COMMENT '投资领域',
  `company` varchar(255) NOT NULL COMMENT '公司',
  `job` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '职位',
  `head_image` varchar(255) NOT NULL COMMENT '用户头像',
  `wjdpay_token` varchar(255) NOT NULL COMMENT '京东WAP的token',
  `cy_money` varchar(255) NOT NULL COMMENT '诚意金',
  `concept` text NOT NULL COMMENT '投资理念',
  `investment_num` int(11) NOT NULL COMMENT '一年计划投资项目',
  `investment_begin` decimal(20,2) NOT NULL COMMENT '投资范围开始（万）',
  `investment_end` decimal(20,2) NOT NULL COMMENT '投资范围结束（万）',
  `gz_region` varchar(255) NOT NULL COMMENT '关注城市',
  `bankLicense` varchar(255) NOT NULL COMMENT '开户许可证中的核准号_易宝托管',
  `orgNo` varchar(255) NOT NULL COMMENT '组织机构代码_易宝托管',
  `businessLicense` varchar(255) NOT NULL COMMENT '营业执照编号_易宝托管',
  `contact` varchar(255) NOT NULL COMMENT '企业联系人_易宝托管',
  `memberClassType` varchar(255) DEFAULT NULL COMMENT '公司类型_易宝托管 ENTERPRISE 企业用户 ， GUARANTEE_CORP 担保公司 ',
  `taxNo` varchar(255) DEFAULT NULL COMMENT '税务登记号_易宝托管',
  `company_create_time` int(11) NOT NULL COMMENT '机构成立时间',
  `company_english_name` varchar(255) NOT NULL COMMENT '机构英文名称',
  `company_url` varchar(255) NOT NULL COMMENT '机构网址',
  `focus_company_count` int(11) NOT NULL DEFAULT '0' COMMENT '关注公司的数量',
  `card` varchar(255) NOT NULL COMMENT '名片',
  `identity_conditions` tinyint(1) NOT NULL DEFAULT '3' COMMENT '身份条件 0表示我的金融资产超过100万元，1表示我的年收入超过30万元，2表示我是专业的风险投资人，3表示我不符合上述任一条件',
  `credit_report` varchar(255) NOT NULL COMMENT '信用报告',
  `housing_certificate` varchar(255) NOT NULL COMMENT '房产认证',
  `is_binding` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0表示不绑定银行卡，1表示绑定银行卡',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `is_effect` (`is_effect`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='//用户信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user`
--

LOCK TABLES `fanwe_user` WRITE;
/*!40000 ALTER TABLE `fanwe_user` DISABLE KEYS */;
INSERT INTO `fanwe_user` VALUES (17,'fanwe','6714ccb93be0fda4e51f206b91b46358',1352227130,1352227130,1,'97139915@qq.com','1200.00',1434136382,'127.0.0.1','福建','福州','',1,3,1,1,0,'方维众筹 - http://zc.fanwe.cn','','','','','','','','','','','','','','','',0,'',0,0,0,NULL,NULL,0,'','','','','','','','',0,'0.00',0,0,0,'','','','','',NULL,0,0,0,0,0,'','','','','','','',0,'0.00','0.00','','','','','',NULL,NULL,0,'','',0,'',3,'','',1),(18,'fzmatthew','6714ccb93be0fda4e51f206b91b46358',1352229180,1352229180,1,'fanwe@fanwe.com','980.00',1352246617,'127.0.0.1','北京','东城区','',1,1,3,1,0,'爱旅行的猫，生活在路上','','','','','','','','','','','','','','','',0,'',0,0,0,NULL,NULL,0,'','','','','','','','',0,'0.00',0,0,0,'','','','','',NULL,0,0,0,0,0,'','','','','','','',0,'0.00','0.00','','','','','',NULL,NULL,0,'','',0,'',3,'','',1),(19,'test','098f6bcd4621d373cade4e832627b4f6',1352230142,1352230142,1,'test@test.com','0.00',1352232937,'127.0.0.1','广东','江门','',0,0,10,0,0,'','','','','','','','','','','','','','','','',0,'',0,0,0,NULL,NULL,0,'','','','','','','','',0,'0.00',0,0,0,'','','','','',NULL,0,0,0,0,0,'','','','','','','',0,'0.00','0.00','','','','','',NULL,NULL,0,'','',0,'',3,'','',1),(20,'vitakung','29c22315747814fd2e4c5364942040eb',1456650404,0,1,'462414875@qq.com','0.00',1456650417,'124.202.243.78','北京','朝阳区','',1,3,0,0,0,'','','','','','','','','','','','','','','','',0,'15510192688',0,0,0,NULL,NULL,0,'','','','','','','','',1,'0.00',1,0,0,'','','','','',NULL,0,0,0,0,0,'','','','','','','',0,'0.00','0.00','','','','','',NULL,NULL,0,'','',0,'',0,'','',0);
/*!40000 ALTER TABLE `fanwe_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_bank`
--

DROP TABLE IF EXISTS `fanwe_user_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '体现人（标识ID）',
  `bank_id` int(11) NOT NULL COMMENT '银行(标识ID)',
  `bank_name` varchar(255) NOT NULL,
  `bankcard` varchar(30) NOT NULL COMMENT '卡号',
  `real_name` varchar(20) NOT NULL COMMENT '姓名',
  `region_lv1` varchar(50) NOT NULL,
  `region_lv2` varchar(50) NOT NULL,
  `region_lv3` varchar(50) NOT NULL,
  `region_lv4` varchar(50) NOT NULL,
  `bankzone` varchar(255) NOT NULL COMMENT '开户网点',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0表示添加银行卡，1表示添加易宝投资通银行卡',
  `mobile` varchar(20) NOT NULL COMMENT '银行预留手机号',
  `identityid` varchar(255) NOT NULL COMMENT '用户标识（易宝投资通）',
  `identitytype` tinyint(1) NOT NULL COMMENT '用户标识类型:0：IMEI、1：MAC地址、2：用户 ID、3：用户 Email、4：用户手机号、5：用户身份证号、6：用户纸质订单协议号',
  `card_top` int(11) NOT NULL COMMENT '卡号前6位',
  `card_last` int(11) NOT NULL COMMENT '卡号后4位',
  `bankcode` varchar(255) NOT NULL COMMENT '银行字母',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `bank_id` (`bank_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_bank`
--

LOCK TABLES `fanwe_user_bank` WRITE;
/*!40000 ALTER TABLE `fanwe_user_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_bonus`
--

DROP TABLE IF EXISTS `fanwe_user_bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL COMMENT '年度',
  `number` varchar(255) NOT NULL COMMENT '期数',
  `money` decimal(20,2) NOT NULL COMMENT '金额',
  `begin_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `deal_id` int(11) NOT NULL COMMENT '项目id',
  `average_monthly_returns` decimal(10,2) NOT NULL COMMENT '平均月回报率',
  `average_annualized_return` decimal(10,2) NOT NULL COMMENT '平均年回报率',
  `status` int(1) NOT NULL COMMENT '0表示等待审核，1表示审核通过，2表示审核不通过 ',
  `type` int(1) NOT NULL COMMENT '0表示分红，1表示固定利息,2表示买房收益',
  `descripe` varchar(255) NOT NULL COMMENT '描述',
  `return_cycle` varchar(255) NOT NULL COMMENT '固定回报周期',
  `is_show` int(1) NOT NULL COMMENT '0表示显示，1表示不显示',
  `earnings_send_capital` int(1) NOT NULL DEFAULT '0' COMMENT '收益发放是否含本金 0表示否，1表示是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_bonus`
--

LOCK TABLES `fanwe_user_bonus` WRITE;
/*!40000 ALTER TABLE `fanwe_user_bonus` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_bonus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_bonus_list`
--

DROP TABLE IF EXISTS `fanwe_user_bonus_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_bonus_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `notice_sn` varchar(255) NOT NULL COMMENT '编号',
  `investor` varchar(255) NOT NULL COMMENT '投资人',
  `investor_money` decimal(20,2) NOT NULL COMMENT '投资金额',
  `percentage_shares` decimal(10,2) NOT NULL COMMENT '占股比例',
  `amount` decimal(20,2) NOT NULL COMMENT '分红金额',
  `deal_id` int(11) NOT NULL COMMENT '项目id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `user_bonus_id` int(11) NOT NULL COMMENT '分红期数id',
  `part_amount` decimal(20,2) NOT NULL COMMENT '含有本金的收益',
  `investor_part_money` decimal(20,2) NOT NULL COMMENT '本金',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_bonus_list`
--

LOCK TABLES `fanwe_user_bonus_list` WRITE;
/*!40000 ALTER TABLE `fanwe_user_bonus_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_bonus_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_carry_config`
--

DROP TABLE IF EXISTS `fanwe_user_carry_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_carry_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '简称',
  `min_price` decimal(20,0) NOT NULL COMMENT '最低额度',
  `max_price` decimal(20,0) NOT NULL COMMENT '最高额度',
  `fee` decimal(20,2) NOT NULL COMMENT '费率',
  `fee_type` tinyint(1) NOT NULL COMMENT '费率类型 0 是固定值 1是百分比',
  `vip_id` int(11) NOT NULL COMMENT 'VIP等级     0默认配置  否则就是对应VIP等级设置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_carry_config`
--

LOCK TABLES `fanwe_user_carry_config` WRITE;
/*!40000 ALTER TABLE `fanwe_user_carry_config` DISABLE KEYS */;
INSERT INTO `fanwe_user_carry_config` VALUES (2,'5万以内','10001','50000','20.00',0,0),(1,'1万以内','0','10000','10.00',0,0);
/*!40000 ALTER TABLE `fanwe_user_carry_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_consignee`
--

DROP TABLE IF EXISTS `fanwe_user_consignee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_consignee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认地址',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='//收件人';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_consignee`
--

LOCK TABLES `fanwe_user_consignee` WRITE;
/*!40000 ALTER TABLE `fanwe_user_consignee` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_consignee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_deal_notify`
--

DROP TABLE IF EXISTS `fanwe_user_deal_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_deal_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `deal_id_user_id` (`user_id`,`deal_id`),
  KEY `user_id` (`user_id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='//用于发送下单成功的用户与关注用户的项目成功准备队列';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_deal_notify`
--

LOCK TABLES `fanwe_user_deal_notify` WRITE;
/*!40000 ALTER TABLE `fanwe_user_deal_notify` DISABLE KEYS */;
INSERT INTO `fanwe_user_deal_notify` VALUES (20,18,55,1352229388);
/*!40000 ALTER TABLE `fanwe_user_deal_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_level`
--

DROP TABLE IF EXISTS `fanwe_user_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '等级名',
  `level` int(11) DEFAULT NULL COMMENT '等级大小   大->小',
  `point` int(11) NOT NULL COMMENT '所需信用值',
  `icon` varchar(255) NOT NULL COMMENT '等级图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='//用户等级';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_level`
--

LOCK TABLES `fanwe_user_level` WRITE;
/*!40000 ALTER TABLE `fanwe_user_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_log`
--

DROP TABLE IF EXISTS `fanwe_user_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_info` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `log_admin_id` int(11) NOT NULL,
  `money` double(20,4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0表示充值 1表示 加入诚意金 2表示违约扣除诚意金 3表示分红  4提现 5购买回报 6购买股权 7积分购买 8积分消费 9退款 42理财购买本金 43理财购买手续费 44理财冻结资金 45理财服务费 46理财发放资金',
  `deal_id` int(11) NOT NULL COMMENT '商品ID号',
  `score` int(11) NOT NULL COMMENT '积分',
  `point` int(11) NOT NULL COMMENT '信用值',
  `money_type` tinyint(1) DEFAULT NULL COMMENT '资金类型',
  `order_id` int(11) DEFAULT NULL COMMENT '订单编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COMMENT='//帐户资金变动日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_log`
--

LOCK TABLES `fanwe_user_log` WRITE;
/*!40000 ALTER TABLE `fanwe_user_log` DISABLE KEYS */;
INSERT INTO `fanwe_user_log` VALUES (114,'管理员测试充值',1352229203,1,1000.0000,18,0,0,0,0,NULL,NULL),(115,'支持原创DIY桌面游戏《功夫》《黄金密码》期待您的支持项目支付',1352229388,1,-20.0000,18,0,0,0,0,NULL,NULL),(116,'管理员测试充值',1352229989,1,2000.0000,17,0,0,0,0,NULL,NULL),(117,'支持拥有自己的咖啡馆项目支付',1352230101,1,-500.0000,17,0,0,0,0,NULL,NULL),(118,'test',1352230213,1,5000.0000,19,0,0,0,0,NULL,NULL),(119,'支持拥有自己的咖啡馆项目支付',1352230228,1,-500.0000,19,0,0,0,0,NULL,NULL),(120,'支持拥有自己的咖啡馆项目支付',1352230232,1,-500.0000,19,0,0,0,0,NULL,NULL),(121,'支持拥有自己的咖啡馆项目支付',1352230237,1,-500.0000,19,0,0,0,0,NULL,NULL),(122,'支持拥有自己的咖啡馆项目支付',1352230240,1,-500.0000,19,0,0,0,0,NULL,NULL),(123,'支持拥有自己的咖啡馆项目支付',1352230243,1,-500.0000,19,0,0,0,0,NULL,NULL),(124,'支持拥有自己的咖啡馆项目支付',1352230247,1,-500.0000,19,0,0,0,0,NULL,NULL),(125,'支持拥有自己的咖啡馆项目支付',1352230268,1,-500.0000,19,0,0,0,0,NULL,NULL),(126,'支持拥有自己的咖啡馆项目支付',1352230270,1,-500.0000,19,0,0,0,0,NULL,NULL),(127,'支持拥有自己的咖啡馆项目支付',1352230293,1,-500.0000,19,0,0,0,0,NULL,NULL),(128,'继续测试',1352231510,1,5000.0000,18,0,0,0,0,NULL,NULL),(129,'支持流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！项目支付',1352231539,1,-2000.0000,18,0,0,0,0,NULL,NULL),(130,'支持流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！项目支付',1352231631,1,-500.0000,19,0,0,0,0,NULL,NULL),(131,'支持拥有自己的咖啡馆项目支付',1352231671,1,-300.0000,17,0,0,0,0,NULL,NULL),(132,'支持流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！项目支付',1352231704,1,-3000.0000,18,0,0,0,0,NULL,NULL);
/*!40000 ALTER TABLE `fanwe_user_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_message`
--

DROP TABLE IF EXISTS `fanwe_user_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '该私信所属人ID',
  `dest_user_id` int(11) NOT NULL COMMENT '对方的用户ID（如果user_id是发件人，该ID为收件，反之为发件人ID）',
  `send_user_id` int(11) NOT NULL COMMENT '发件人ID',
  `receive_user_id` int(11) NOT NULL COMMENT '收件人ID',
  `user_name` varchar(255) NOT NULL,
  `dest_user_name` varchar(255) NOT NULL,
  `send_user_name` varchar(255) NOT NULL,
  `receive_user_name` varchar(255) NOT NULL,
  `message_type` enum('inbox','outbox') NOT NULL COMMENT '类型：inbox(收件) outbox(发件)',
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='// 用户私信';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_message`
--

LOCK TABLES `fanwe_user_message` WRITE;
/*!40000 ALTER TABLE `fanwe_user_message` DISABLE KEYS */;
INSERT INTO `fanwe_user_message` VALUES (47,1352230383,'感谢支持',18,19,18,19,'fzmatthew','test','fzmatthew','test','outbox',1),(48,1352230383,'感谢支持',19,18,18,19,'test','fzmatthew','fzmatthew','test','inbox',0),(49,1352230403,'感谢您的支持!!!',18,17,18,17,'fzmatthew','fanwe','fzmatthew','fanwe','outbox',1),(50,1352230403,'感谢您的支持!!!',17,18,18,17,'fanwe','fzmatthew','fzmatthew','fanwe','inbox',0),(51,1352230499,'谢谢!!!',17,18,17,18,'fanwe','fzmatthew','fanwe','fzmatthew','outbox',1),(52,1352230499,'谢谢!!!',18,17,17,18,'fzmatthew','fanwe','fanwe','fzmatthew','inbox',0);
/*!40000 ALTER TABLE `fanwe_user_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_notify`
--

DROP TABLE IF EXISTS `fanwe_user_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_info` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `url_route` varchar(255) NOT NULL,
  `url_param` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0 默认 1表示站内推送',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COMMENT='// 公告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_notify`
--

LOCK TABLES `fanwe_user_notify` WRITE;
/*!40000 ALTER TABLE `fanwe_user_notify` DISABLE KEYS */;
INSERT INTO `fanwe_user_notify` VALUES (69,17,'拥有自己的咖啡馆 在 2012-11-07 11:31:10 成功筹到 ¥5,000.00',1352230271,0,'deal#show','id=56',0),(70,19,'拥有自己的咖啡馆 在 2012-11-07 11:31:10 成功筹到 ¥5,000.00',1352230271,0,'deal#show','id=56',0),(71,17,'您支持的项目拥有自己的咖啡馆回报已发放',1352230424,0,'account#view_order','id=66',0),(72,18,'流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！ 在 2012-11-07 11:55:04 成功筹到 ¥3,000.00',1352231704,0,'deal#show','id=58',0);
/*!40000 ALTER TABLE `fanwe_user_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_refund`
--

DROP TABLE IF EXISTS `fanwe_user_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money` double(20,4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '提现申请时间',
  `reply` text NOT NULL COMMENT '提现审核回复',
  `is_pay` tinyint(1) NOT NULL COMMENT '0 表示未审核;1 表示 允许操作成功；2 表示 未允许操作成功;3 表示 提现确认成功',
  `pay_time` int(11) NOT NULL,
  `memo` text NOT NULL COMMENT '提现的备注',
  `pay_log` text NOT NULL COMMENT '支付说明',
  `user_bank_id` int(11) NOT NULL COMMENT '银行ID',
  `ybdrawflowid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='// 用户退款';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_refund`
--

LOCK TABLES `fanwe_user_refund` WRITE;
/*!40000 ALTER TABLE `fanwe_user_refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_user_refund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_user_weibo`
--

DROP TABLE IF EXISTS `fanwe_user_weibo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_user_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `weibo_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='//微博';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_user_weibo`
--

LOCK TABLES `fanwe_user_weibo` WRITE;
/*!40000 ALTER TABLE `fanwe_user_weibo` DISABLE KEYS */;
INSERT INTO `fanwe_user_weibo` VALUES (55,17,'http://weibo.com/fzmatthew');
/*!40000 ALTER TABLE `fanwe_user_weibo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_vote`
--

DROP TABLE IF EXISTS `fanwe_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '调查的项目名称',
  `begin_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `is_effect` tinyint(1) NOT NULL COMMENT '有效性',
  `sort` int(11) NOT NULL,
  `description` text NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_vote`
--

LOCK TABLES `fanwe_vote` WRITE;
/*!40000 ALTER TABLE `fanwe_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_vote_ask`
--

DROP TABLE IF EXISTS `fanwe_vote_ask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_vote_ask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '投票项名称',
  `type` tinyint(1) NOT NULL COMMENT '投票类型，单选多选/自定义可叠加 1:单选 2:多选 3:自定义',
  `sort` int(11) NOT NULL COMMENT ' 排序 大到小',
  `vote_id` int(11) NOT NULL COMMENT '调查ID',
  `val_scope` text NOT NULL COMMENT '预选范围 逗号分开',
  `is_fill` tinyint(1) NOT NULL COMMENT '1表示该项必填，0表示可以不填',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_vote_ask`
--

LOCK TABLES `fanwe_vote_ask` WRITE;
/*!40000 ALTER TABLE `fanwe_vote_ask` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_vote_ask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_vote_list`
--

DROP TABLE IF EXISTS `fanwe_vote_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_vote_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '调查项ID',
  `value` text NOT NULL COMMENT '问题答案',
  `user_id` int(11) DEFAULT NULL COMMENT '参与调查的用户id',
  `mobile` varchar(11) DEFAULT NULL COMMENT '参与调查的用户手机号码',
  `email` varchar(64) DEFAULT NULL COMMENT '参与调查的用户邮箱',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_vote_list`
--

LOCK TABLES `fanwe_vote_list` WRITE;
/*!40000 ALTER TABLE `fanwe_vote_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_vote_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_vote_result`
--

DROP TABLE IF EXISTS `fanwe_vote_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_vote_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '投票的名称',
  `count` int(11) NOT NULL COMMENT '计数',
  `vote_id` int(11) NOT NULL COMMENT '调查项ID',
  `vote_ask_id` int(11) NOT NULL COMMENT '投票项（问题）ID',
  `type` int(1) NOT NULL COMMENT '0:固定选项，1:用户自定义输入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_vote_result`
--

LOCK TABLES `fanwe_vote_result` WRITE;
/*!40000 ALTER TABLE `fanwe_vote_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_vote_result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_account`
--

DROP TABLE IF EXISTS `fanwe_weixin_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(255) NOT NULL COMMENT 'AppID(应用ID)-第三方平台指 授权方appid',
  `appsecret` varchar(255) NOT NULL COMMENT 'AppSecret(应用密钥)-第三方平台无用',
  `app_url` varchar(255) NOT NULL COMMENT 'URL(服务器地址)-第三方平台无用',
  `app_token` varchar(255) NOT NULL COMMENT 'Token(令牌)-第三方平台无用',
  `app_encodingAESKey` varchar(255) NOT NULL COMMENT 'EncodingAESKey(消息加解密密钥)-第三方平台无用',
  `authorizer_appid` varchar(255) NOT NULL COMMENT '授权方appid',
  `authorizer_access_token` varchar(255) NOT NULL COMMENT '授权方令牌-第三方平台无用',
  `expires_in` int(11) NOT NULL COMMENT '授权方令牌 有效时间-第三方平台无用',
  `authorizer_refresh_token` varchar(255) NOT NULL COMMENT '刷新令牌-第三方平台',
  `func_info` text NOT NULL COMMENT '公众号授权给开发者的权限集列表',
  `verify_type_info` tinyint(1) NOT NULL COMMENT '授权方认证类型，-1代表未认证，0代表微信认证，1代表新浪微博认证，2代表腾讯微博认证，3代表已资质认证通过但还未通过名称认证，4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证',
  `service_type_info` tinyint(1) NOT NULL COMMENT '授权方公众号类型，0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号',
  `nick_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL COMMENT '授权方公众号的原始ID',
  `authorizer_info` varchar(255) NOT NULL COMMENT '授权方昵称',
  `head_img` varchar(255) NOT NULL COMMENT '授权方头像',
  `alias` varchar(255) NOT NULL COMMENT '授权方公众号所设置的微信号，可能为空',
  `qrcode_url` varchar(255) NOT NULL COMMENT '二维码图片的URL，开发者最好自行也进行保存',
  `location_report` tinyint(1) NOT NULL COMMENT '地理位置上报选项 0 无上报 1 进入会话时上报 2 每5s上报',
  `voice_recognize` tinyint(1) NOT NULL COMMENT '语音识别开关选项 0 关闭语音识别 1 开启语音识别',
  `customer_service` tinyint(1) NOT NULL COMMENT '客服开关选项 0 关闭多客服 1 开启多客服',
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方是否取消授权 0表示取消授权 1表示授权',
  `user_id` int(11) NOT NULL COMMENT '会员ID ，诺type为1，user_id 为空',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示前台会员 1 表示后台管理员',
  `industry_1` int(11) NOT NULL,
  `industry_1_status` tinyint(1) NOT NULL,
  `industry_2` int(11) NOT NULL,
  `industry_2_status` tinyint(1) NOT NULL,
  `test_user` varchar(255) DEFAULT NULL COMMENT '测试微信号',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `au_app_id` (`authorizer_appid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='//微信公众号列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_account`
--

LOCK TABLES `fanwe_weixin_account` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_api_get_record`
--

DROP TABLE IF EXISTS `fanwe_weixin_api_get_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_api_get_record` (
  `openid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`openid`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `idx_0` (`account_id`,`create_time`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='请求的用户记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_api_get_record`
--

LOCK TABLES `fanwe_weixin_api_get_record` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_api_get_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_api_get_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_conf`
--

DROP TABLE IF EXISTS `fanwe_weixin_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '配置输入的类型 0:文本输入 1:下拉框输入 2:图片上传 3:编辑器',
  `value_scope` text NOT NULL COMMENT '取值范围',
  `is_require` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `is_conf` tinyint(1) NOT NULL COMMENT '是否可配置 0: 可配置  1:不可配置',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='//微信配置选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_conf`
--

LOCK TABLES `fanwe_weixin_conf` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_conf` DISABLE KEYS */;
INSERT INTO `fanwe_weixin_conf` VALUES (1,'第三方平台appid','platform_appid','appid',0,0,'',0,1,1,1),(2,'第三方平台token','platform_token','token',0,0,'',0,1,1,2),(3,'第三方平台symmetric_key','platform_encodingAesKey','symmetric_key',0,0,'',0,1,1,3),(4,'是否开启第三方平台','platform_status','0',0,4,'0,1',0,1,1,4),(5,'第三方平台AppSecret','platform_appsecret','0',0,0,'',0,1,1,1),(6,'component_verify_ticket','platform_component_verify_ticket','0',0,0,'',0,1,0,6),(7,'第三方平台access_token','platform_component_access_token','0',0,0,'',0,1,0,7),(8,'第三方平台预授权码','platform_pre_auth_code','0',0,0,'',0,1,0,8),(9,'第三方平台access_token有效期','platform_component_access_token_expire','0',0,0,'',0,1,0,9),(10,'第三方平台预授权码有效期','platform_pre_auth_code_expire','0',0,0,'',0,1,0,10);
/*!40000 ALTER TABLE `fanwe_weixin_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_group`
--

DROP TABLE IF EXISTS `fanwe_weixin_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupid` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL DEFAULT '',
  `account_id` varchar(30) NOT NULL DEFAULT '',
  `fanscount` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`,`account_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_group`
--

LOCK TABLES `fanwe_weixin_group` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_msg_list`
--

DROP TABLE IF EXISTS `fanwe_weixin_msg_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_msg_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dest` varchar(255) NOT NULL,
  `send_type` tinyint(1) NOT NULL,
  `content` text NOT NULL,
  `send_time` int(11) NOT NULL,
  `is_send` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `result` text NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `is_html` tinyint(1) NOT NULL,
  `title` text NOT NULL,
  `is_youhui` tinyint(1) NOT NULL,
  `youhui_id` int(11) NOT NULL,
  `code` varchar(60) NOT NULL COMMENT '发送的验证码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='//微信消息列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_msg_list`
--

LOCK TABLES `fanwe_weixin_msg_list` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_msg_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_msg_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_nav`
--

DROP TABLE IF EXISTS `fanwe_weixin_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '' COMMENT '菜单名称',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序 大->小',
  `key_or_url` varchar(255) DEFAULT '' COMMENT '用于推送到微信平台的key或url(所有以http://开头的表示url，其余一率为key)',
  `event_type` enum('click') DEFAULT 'click' COMMENT '按钮的事件，目前微信只支持click',
  `account_id` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否已推送到微信(0:未推送或失败 1:成功)，该列同一个商家全部相同，菜单为一次性推送,对菜单本地修改时，批量更新该值为0',
  `u_id` int(11) DEFAULT NULL,
  `u_module` varchar(255) DEFAULT NULL,
  `u_action` varchar(255) DEFAULT NULL,
  `u_param` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `event_type` (`event_type`),
  KEY `account_id` (`account_id`,`key_or_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='为微信自定义的菜单设置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_nav`
--

LOCK TABLES `fanwe_weixin_nav` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_nav` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_reply`
--

DROP TABLE IF EXISTS `fanwe_weixin_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `i_msg_type` enum('event','link','location','image','text') DEFAULT 'text' COMMENT '接收到的微信的推送到本系统api中的MsgType',
  `o_msg_type` enum('news','music','text') DEFAULT 'text' COMMENT '用于响应并回复给微信推送的消息类型 news:图文 music:音乐 text:纯文本',
  `keywords` varchar(300) DEFAULT NULL COMMENT '用于响应文本(i_msg_type:text或者i_event:click时对key的响应)类型的回复时进行匹配的关键词',
  `keywords_match` text COMMENT 'keywords的全文索引列',
  `keywords_match_row` text COMMENT 'keywords全文索引的未作unicode编码的原文，用于开发者查看',
  `address` text COMMENT '用于显示的地理地址',
  `api_address` text COMMENT '用于地理定位的API地址',
  `x_point` varchar(100) DEFAULT '' COMMENT '用于lbs消息,i_msg_type:location 匹配的经度',
  `y_point` varchar(100) DEFAULT '' COMMENT '用于lbs消息,i_msg_type:location 匹配的纬度',
  `scale_meter` int(11) DEFAULT '0' COMMENT '用于lbs消息,i_msg_type:location 匹配的距离范围(米)',
  `i_event` enum('subscribe','unsubscribe','click','empty') DEFAULT 'empty' COMMENT '用于响应i_msg_type为event时的对应事件',
  `reply_content` text COMMENT '回复的文本消息',
  `reply_music` varchar(255) DEFAULT '' COMMENT '回复的音乐链接',
  `reply_news_title` text COMMENT '图文回复的标题',
  `reply_news_description` text COMMENT '图文回复的描述',
  `reply_news_picurl` varchar(255) DEFAULT '' COMMENT '图文回复的图片链接',
  `reply_news_url` varchar(255) DEFAULT '' COMMENT '图文回复的跳转链接',
  `reply_news_content` text,
  `type` tinyint(1) DEFAULT '0' COMMENT '回复归类 \r\n0:普通的回复 \r\n1:默认回复(只能一条文本或图文) \r\n2:官网回复(只能有一条图文)\r\n3.业务数据(图文)\r\n4.关注时回复(只能有一条文本或图文) \r\n5.取消关注时回复(只能有一条文本或图文) ',
  `relate_data` varchar(255) DEFAULT '' COMMENT '关联的业务数据源(如youhui,vote)等',
  `relate_id` int(11) DEFAULT '0' COMMENT '所关联的relate_data的id，用于判断数据关联的删除(指定url)',
  `account_id` int(11) DEFAULT '0' COMMENT '所属的商家ID',
  `default_close` tinyint(1) DEFAULT '1' COMMENT '默认回复是否关闭 0：关闭 1：开启',
  `relate_type` tinyint(1) DEFAULT NULL COMMENT '与关联数据的关系 0:回复数据由关联数据源获取 1:只url跳转数据来源于关联数据',
  `match_type` tinyint(1) NOT NULL DEFAULT '0',
  `u_id` int(11) DEFAULT NULL,
  `u_module` varchar(255) DEFAULT NULL,
  `u_action` varchar(255) DEFAULT NULL,
  `u_param` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_msg_type` (`i_msg_type`),
  KEY `o_msg_type` (`o_msg_type`),
  KEY `i_event` (`i_event`),
  KEY `type` (`type`),
  KEY `relate_data` (`relate_data`),
  KEY `relate_id` (`relate_id`),
  KEY `account_id` (`account_id`),
  KEY `match_type` (`account_id`,`match_type`,`keywords`),
  FULLTEXT KEY `keywords_match` (`keywords_match`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商家回复设置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_reply`
--

LOCK TABLES `fanwe_weixin_reply` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_reply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_reply_relate`
--

DROP TABLE IF EXISTS `fanwe_weixin_reply_relate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_reply_relate` (
  `main_reply_id` int(11) DEFAULT '0' COMMENT '主回复ID',
  `relate_reply_id` int(11) DEFAULT '0' COMMENT '关联的多图文用的子回复ID',
  `sort` tinyint(1) DEFAULT '0',
  KEY `main_reply_id` (`main_reply_id`),
  KEY `relate_reply_id` (`relate_reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='多图文回复的关联配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_reply_relate`
--

LOCK TABLES `fanwe_weixin_reply_relate` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_reply_relate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_reply_relate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_send`
--

DROP TABLE IF EXISTS `fanwe_weixin_send`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `author` varchar(100) NOT NULL COMMENT '作者件',
  `media_file` varchar(255) NOT NULL COMMENT '多媒体文件',
  `content` text NOT NULL COMMENT '图文消息页面的内容，支持HTML标签',
  `send_type` tinyint(4) NOT NULL COMMENT '0普通群发，1高级群发',
  `user_type` tinyint(4) NOT NULL COMMENT '发送对 0所有 1会员组 2会员等级',
  `user_type_id` int(11) NOT NULL COMMENT '组ID或者等级ID',
  `msgtype` enum('news','music','video','voice','image','text') NOT NULL COMMENT '消息类型',
  `relate_type` tinyint(4) NOT NULL COMMENT '与关联数据的关系 0:回复数据由关联数据源获取 1:只url跳转数据来源于关联数据',
  `relate_data` varchar(255) NOT NULL,
  `relate_id` int(255) NOT NULL,
  `url` varchar(255) NOT NULL COMMENT '连接地址',
  `digest` text NOT NULL COMMENT '简介',
  `account_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `send_time` int(11) NOT NULL COMMENT '推送时间',
  `media_id` varchar(255) NOT NULL COMMENT '微信服务器的关联多媒体ID',
  `u_id` int(11) NOT NULL,
  `u_module` varchar(255) NOT NULL,
  `u_action` varchar(255) NOT NULL,
  `u_param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_send`
--

LOCK TABLES `fanwe_weixin_send` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_send` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_send` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_send_relate`
--

DROP TABLE IF EXISTS `fanwe_weixin_send_relate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_send_relate` (
  `relate_id` int(11) NOT NULL,
  `send_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_send_relate`
--

LOCK TABLES `fanwe_weixin_send_relate` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_send_relate` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_send_relate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_tmpl`
--

DROP TABLE IF EXISTS `fanwe_weixin_tmpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_tmpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '' COMMENT '菜单名称',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序 大->小',
  `account_id` int(11) DEFAULT '0' COMMENT '所属的商家ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信模板';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_tmpl`
--

LOCK TABLES `fanwe_weixin_tmpl` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_tmpl` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_tmpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_weixin_user`
--

DROP TABLE IF EXISTS `fanwe_weixin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_weixin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `account_id` int(11) NOT NULL,
  `subscribe` tinyint(1) NOT NULL COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。',
  `openid` varchar(255) NOT NULL COMMENT '用户的标识，对当前公众号唯一',
  `nickname` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `city` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` varchar(255) DEFAULT NULL COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `unionid` varchar(255) DEFAULT NULL COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。',
  `remark` varchar(255) DEFAULT NULL COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `groupid` int(11) DEFAULT NULL COMMENT '用户所在的分组ID',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `only` (`account_id`,`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=733 DEFAULT CHARSET=utf8 COMMENT='//微信公众号会员列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_weixin_user`
--

LOCK TABLES `fanwe_weixin_user` WRITE;
/*!40000 ALTER TABLE `fanwe_weixin_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_weixin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_bind_bank_card`
--

DROP TABLE IF EXISTS `fanwe_yeepay_bind_bank_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_bind_bank_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT 'fanwe_user.id',
  `platformNo` varchar(20) NOT NULL,
  `bankCardNo` varchar(50) NOT NULL DEFAULT '' COMMENT '绑定的卡号',
  `bank` varchar(20) NOT NULL DEFAULT '' COMMENT '卡的开户行',
  `cardStatus` varchar(20) NOT NULL COMMENT '卡的状态VERIFYING 认证中 VERIFIED 已认证',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `bizType` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_bind_bank_card`
--

LOCK TABLES `fanwe_yeepay_bind_bank_card` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_bind_bank_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_bind_bank_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_cp_transaction`
--

DROP TABLE IF EXISTS `fanwe_yeepay_cp_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_cp_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformNo` varchar(20) NOT NULL,
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT 'fanwe_user.id',
  `userType` varchar(20) NOT NULL DEFAULT 'MEMBER' COMMENT '出款人用户类型，目前只支持传入 MEMBER\r\nMEMBER 个人会员 MERCHANT 商户 ',
  `bizType` varchar(50) NOT NULL COMMENT 'TENDER 投标 REPAYMENT 还款 CREDIT_ASSIGNMENT 债权转让 TRANSFER 转账 COMMISSION 分润，仅在资金转账明细中使用',
  `expired` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '超过此时间即不允许提交订单',
  `tenderOrderNo` int(11) DEFAULT '0' COMMENT '项目编号',
  `tenderName` varchar(255) DEFAULT NULL COMMENT '项目名称 ',
  `tenderAmount` decimal(20,2) DEFAULT '0.00' COMMENT '项目金额',
  `tenderDescription` varchar(255) DEFAULT NULL COMMENT '项目描述信息',
  `borrowerPlatformUserNo` int(11) DEFAULT NULL COMMENT '项目的借款人平台用户编号',
  `originalRequestNo` int(11) DEFAULT NULL COMMENT '需要转让的投资记录流水号',
  `paymentAmount` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `details` text COMMENT '资金明细记录',
  `extend` text COMMENT '业务扩展属性，根据业务类型的不同，需要传入不同的参数。',
  `transfer_id` int(11) NOT NULL DEFAULT '0' COMMENT '债权转让id fanwe_deal_load_transfer.id',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `is_complete_transaction` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_callback=1时，才生效;判断是否已经完成转帐',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  `deal_repay_id` int(11) DEFAULT NULL COMMENT '还款计划ID',
  `fee` decimal(20,2) DEFAULT '0.00' COMMENT '手续费',
  `repay_start_time` varchar(50) DEFAULT NULL COMMENT '记录还款时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '易宝处理时间',
  `share_fee` decimal(20,2) DEFAULT '0.00' COMMENT '分红',
  `delivery_fee` decimal(20,2) DEFAULT NULL COMMENT '快递费用',
  `targetAmount` decimal(20,2) DEFAULT NULL COMMENT '用户实际收到金额',
  `tenderId` int(11) NOT NULL,
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=226 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_cp_transaction`
--

LOCK TABLES `fanwe_yeepay_cp_transaction` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_cp_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_cp_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_cp_transaction_detail`
--

DROP TABLE IF EXISTS `fanwe_yeepay_cp_transaction_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_cp_transaction_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT 'fanwe_yeepay_repayment.id',
  `deal_load_repay_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户回款计划表',
  `targetUserType` int(11) NOT NULL DEFAULT '0' COMMENT '用户类型',
  `targetPlatformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户编号',
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '转入金额',
  `bizType` varchar(20) NOT NULL DEFAULT '' COMMENT '资金明细业务类型。根据业务的不同，需要传入不同的值，见【业务类型',
  `repay_manage_impose_money` decimal(20,2) DEFAULT NULL COMMENT '平台收取借款者的管理费逾期罚息',
  `impose_money` decimal(20,2) DEFAULT NULL COMMENT '投资者收取借款者的逾期罚息',
  `repay_status` int(11) DEFAULT NULL COMMENT '还款状态',
  `true_repay_time` int(11) DEFAULT NULL COMMENT '还款时间',
  `fee` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_cp_transaction_detail`
--

LOCK TABLES `fanwe_yeepay_cp_transaction_detail` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_cp_transaction_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_cp_transaction_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_enterprise_register`
--

DROP TABLE IF EXISTS `fanwe_yeepay_enterprise_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_enterprise_register` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) DEFAULT '0' COMMENT 'fanwe_user.id',
  `platformNo` varchar(20) DEFAULT NULL,
  `enterpriseName` varchar(50) DEFAULT NULL COMMENT '企业名称',
  `bankLicense` varchar(50) DEFAULT NULL COMMENT '开户银行许可证',
  `orgNo` varchar(50) DEFAULT NULL COMMENT '组织机构代码',
  `businessLicense` varchar(50) DEFAULT NULL COMMENT '营业执照编号',
  `taxNo` varchar(20) DEFAULT NULL COMMENT '税务登记号',
  `legal` varchar(50) DEFAULT NULL COMMENT '法人姓名',
  `legalIdNo` varchar(20) DEFAULT NULL COMMENT '法人身份证号',
  `contact` varchar(20) DEFAULT NULL COMMENT '企业联系人',
  `contactPhone` varchar(20) DEFAULT NULL COMMENT '联系人手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '联系人邮箱',
  `memberClassType` varchar(255) DEFAULT NULL COMMENT '会员类型ENTERPRISE：企业借款人;GUARANTEE_CORP：担保公司',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `bizType` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_enterprise_register`
--

LOCK TABLES `fanwe_yeepay_enterprise_register` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_enterprise_register` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_enterprise_register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_log`
--

DROP TABLE IF EXISTS `fanwe_yeepay_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `strxml` text,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71606 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_log`
--

LOCK TABLES `fanwe_yeepay_log` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_recharge`
--

DROP TABLE IF EXISTS `fanwe_yeepay_recharge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_recharge` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT 'fanwe_user.id',
  `platformNo` varchar(20) NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `feeMode` varchar(50) NOT NULL DEFAULT 'PLATFORM' COMMENT '费率模式PLATFORM',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `bizType` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `fee` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_recharge`
--

LOCK TABLES `fanwe_yeepay_recharge` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_recharge` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_recharge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_register`
--

DROP TABLE IF EXISTS `fanwe_yeepay_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_register` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) DEFAULT '0' COMMENT 'fanwe_user.id',
  `platformNo` varchar(20) DEFAULT NULL,
  `nickName` varchar(50) DEFAULT NULL,
  `realName` varchar(50) DEFAULT NULL,
  `idCardNo` varchar(50) DEFAULT NULL,
  `idCardType` varchar(50) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `bizType` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `bankLicense` varchar(255) NOT NULL COMMENT '开户许可证中的核准号_易宝托管',
  `orgNo` varchar(255) NOT NULL COMMENT '组织机构代码_易宝托管',
  `businessLicense` varchar(255) NOT NULL COMMENT '营业执照编号_易宝托管',
  `contact` varchar(255) NOT NULL COMMENT '企业联系人_易宝托管',
  `memberClassType` varchar(255) DEFAULT NULL COMMENT '公司类型_易宝托管 ENTERPRISE 企业用户 ， GUARANTEE_CORP 担保公司 ',
  `taxNo` varchar(255) DEFAULT NULL COMMENT '税务登记号_易宝托管',
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_register`
--

LOCK TABLES `fanwe_yeepay_register` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_register` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fanwe_yeepay_withdraw`
--

DROP TABLE IF EXISTS `fanwe_yeepay_withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fanwe_yeepay_withdraw` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT 'fanwe_user.id',
  `platformNo` varchar(20) NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `feeMode` varchar(50) NOT NULL DEFAULT '' COMMENT 'PLATFORM 收取商户手续费 USER 收取用户手续费',
  `is_callback` tinyint(1) NOT NULL DEFAULT '0',
  `bizType` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '返回码;1 成功 0 失败 2 xml参数格式错误 3 签名验证失败 101 引用了不存在的对象（例如错误的订单号） 102 业务状态不正确 103 由于业务限制导致业务不能执行 104 实名认证失败',
  `message` varchar(255) DEFAULT NULL COMMENT '描述异常信息',
  `description` varchar(255) DEFAULT NULL,
  `cardNo` varchar(50) DEFAULT NULL COMMENT '绑定的卡号',
  `bank` varchar(20) DEFAULT NULL COMMENT '卡的开户行',
  `create_time` int(11) DEFAULT NULL,
  `fee` decimal(20,2) DEFAULT NULL COMMENT '手续费',
  PRIMARY KEY (`id`,`requestNo`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fanwe_yeepay_withdraw`
--

LOCK TABLES `fanwe_yeepay_withdraw` WRITE;
/*!40000 ALTER TABLE `fanwe_yeepay_withdraw` DISABLE KEYS */;
/*!40000 ALTER TABLE `fanwe_yeepay_withdraw` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-29  2:55:33
