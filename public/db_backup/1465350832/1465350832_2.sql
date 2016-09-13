-- fanwe SQL Dump Program
-- nginx/1.10.0
-- 
-- DATE : 2016-06-08 17:53:52
-- MYSQL SERVER VERSION : 5.5.48-log
-- PHP VERSION : fpm-fcgi
-- Vol : 2


DROP TABLE IF EXISTS `%DB_PREFIX%deal_support_log`;
CREATE TABLE `%DB_PREFIX%deal_support_log` (
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
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='// 项目支持记录';
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('75','68','42','1465245921','500.00','52','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('76','68','42','1465245960','500.00','52','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('77','68','42','1465250887','600.00','52','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('78','69','42','1465252347','1000000.00','50','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('79','69','42','1465252401','8400000.00','50','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('80','74','49','1465253454','30000.00','55','1');
INSERT INTO `%DB_PREFIX%deal_support_log` VALUES ('81','77','49','1465253540','30000.00','47','1');
DROP TABLE IF EXISTS `%DB_PREFIX%deal_visit_log`;
CREATE TABLE `%DB_PREFIX%deal_visit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `client_ip` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 COMMENT='// 访问记录';
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('117','55','127.0.0.1','1352229137');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('118','56','127.0.0.1','1352230070');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('119','57','127.0.0.1','1352230830');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('120','58','127.0.0.1','1352231514');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('121','56','127.0.0.1','1352231651');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('122','55','127.0.0.1','1352232299');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('123','58','127.0.0.1','1352232420');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('124','56','127.0.0.1','1352232590');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('125','57','127.0.0.1','1352232717');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('126','55','127.0.0.1','1352246374');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('127','57','127.0.0.1','1352246699');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('128','56','127.0.0.1','1352246710');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('129','58','127.0.0.1','1352246719');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('130','58','127.0.0.1','1455586010');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('131','56','127.0.0.1','1455586205');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('132','57','127.0.0.1','1455586417');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('133','58','127.0.0.1','1455586732');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('134','59','::1','1459982091');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('136','62','::1','1460408368');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('137','63','::1','1460408446');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('138','60','::1','1460408753');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('139','68','::1','1460566852');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('140','68','192.168.1.70','1461191726');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('141','60','192.168.1.70','1461192385');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('142','71','192.168.1.79','1461195082');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('143','71','192.168.1.79','1461195761');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('144','58','192.168.1.70','1461201507');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('145','69','192.168.1.29','1461201849');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('146','71','192.168.1.70','1461257196');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('147','58','192.168.1.70','1461257389');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('148','68','192.168.1.56','1461268002');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('149','58','192.168.1.56','1461268030');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('150','76','192.168.1.79','1461268843');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('151','76','192.168.1.70','1461268859');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('152','68','192.168.1.79','1461268961');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('153','77','192.168.1.56','1461268999');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('154','77','192.168.1.70','1461271035');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('155','76','192.168.1.56','1461276309');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('156','76','192.168.1.70','1461276448');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('157','71','192.168.1.56','1461276616');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('158','77','192.168.1.70','1461277016');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('159','69','192.168.1.56','1461277123');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('160','69','192.168.1.79','1461277146');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('161','68','192.168.1.56','1461277156');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('162','76','192.168.1.56','1461277187');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('163','76','192.168.1.70','1461277429');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('164','77','192.168.1.79','1461277602');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('165','58','192.168.1.56','1461277635');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('166','68','192.168.1.79','1461277755');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('167','58','192.168.1.70','1461277859');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('168','60','192.168.1.56','1461278383');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('169','78','192.168.1.79','1461279081');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('170','78','192.168.1.56','1461279329');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('171','78','192.168.1.70','1461279367');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('172','78','192.168.1.79','1461279690');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('173','78','192.168.1.79','1461282397');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('174','69','192.168.1.35','1461429936');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('175','76','192.168.1.70','1461430044');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('176','76','192.168.1.70','1461431346');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('177','78','192.168.1.95','1461524684');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('178','76','192.168.1.15','1461622996');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('179','76','192.168.1.162','1461631435');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('180','77','127.0.0.1','1464745970');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('181','69','127.0.0.1','1464815841');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('182','58','119.147.158.34','1465244141');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('183','69','123.174.63.31','1465245097');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('184','58','123.174.63.31','1465245142');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('185','68','36.40.164.74','1465245451');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('186','78','36.40.164.74','1465245533');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('187','69','36.40.164.74','1465245545');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('188','69','61.151.218.118','1465245548');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('189','69','101.226.227.99','1465245548');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('190','76','36.40.164.74','1465245894');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('191','68','119.147.158.34','1465246001');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('192','68','219.149.31.179','1465246450');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('193','68','119.147.158.34','1465247112');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('194','78','14.215.35.76','1465248266');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('195','77','125.90.246.32','1465248362');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('196','68','113.81.25.246','1465248607');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('197','58','36.40.164.74','1465250449');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('198','76','36.40.164.74','1465250865');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('199','78','36.40.164.74','1465250868');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('200','68','36.40.164.74','1465250873');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('201','76','36.40.164.74','1465252217');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('202','69','36.40.164.74','1465252326');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('203','78','219.149.31.186','1465252756');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('204','68','1.69.33.108','1465252954');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('205','74','36.40.164.74','1465253302');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('206','77','36.40.164.74','1465253312');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('207','78','219.149.31.179','1465255906');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('208','76','14.215.35.76','1465255972');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('209','77','219.149.31.179','1465256073');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('210','68','1.68.6.180','1465257596');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('211','69','1.68.6.180','1465257655');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('212','78','223.104.30.163','1465257664');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('213','69','223.104.30.163','1465257749');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('214','69','223.104.30.163','1465258426');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('215','58','116.21.137.236','1465259357');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('216','69','116.21.137.236','1465259377');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('217','58','59.40.1.235','1465259585');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('218','78','223.104.30.163','1465260218');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('219','68','223.104.30.163','1465260286');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('220','69','223.104.30.163','1465260499');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('221','68','183.50.110.115','1465260593');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('222','78','183.50.110.115','1465260907');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('223','69','14.148.204.66','1465261119');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('224','71','43.225.236.192','1465263564');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('225','78','111.206.51.104','1465263693');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('226','69','36.40.164.74','1465264969');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('227','69','117.136.38.240','1465265803');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('228','76','219.137.208.121','1465265886');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('229','78','219.137.208.121','1465265937');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('230','68','60.221.229.157','1465267005');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('231','71','122.228.11.221','1465267435');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('232','76','122.228.11.221','1465267520');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('233','68','183.0.49.167','1465267727');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('234','69','59.38.67.58','1465269334');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('235','69','202.109.166.163','1465269350');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('236','78','219.134.209.66','1465271859');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('237','68','116.17.240.223','1465273855');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('238','71','202.109.166.162','1465274449');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('239','68','110.76.186.138','1465275141');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('240','69','10.160.179.148','1465275926');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('241','74','112.95.136.65','1465276109');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('242','69','14.215.63.198','1465277054');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('243','68','14.215.63.198','1465277106');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('244','76','14.215.63.198','1465277121');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('245','58','14.215.63.198','1465277249');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('246','78','113.251.36.230','1465277568');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('247','78','218.76.129.136','1465278237');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('248','68','61.164.250.92','1465278833');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('249','78','113.251.36.230','1465278851');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('250','69','113.75.130.101','1465282777');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('251','69','223.104.30.177','1465284152');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('252','74','223.104.30.177','1465284327');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('253','69','103.44.207.169','1465285071');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('254','68','103.44.207.169','1465285086');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('255','78','103.44.207.169','1465285100');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('256','69','59.63.190.122','1465285109');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('257','76','59.63.190.122','1465285142');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('258','76','111.206.51.104','1465287771');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('259','78','122.228.11.136','1465289594');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('260','78','14.215.37.61','1465290391');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('261','69','119.147.146.189','1465293463');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('262','69','223.104.30.121','1465298168');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('263','60','61.135.189.103','1465303311');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('264','77','61.135.189.103','1465303341');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('265','74','61.135.189.103','1465303393');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('266','73','61.135.189.103','1465303403');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('267','68','117.136.6.71','1465304401');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('268','78','114.197.161.155','1465304994');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('269','78','218.76.129.136','1465306616');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('270','74','110.188.0.6','1465317745');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('271','78','183.20.165.215','1465319301');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('272','78','14.215.34.194','1465321014');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('273','78','119.127.253.70','1465323614');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('274','68','119.129.46.44','1465324220');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('275','68','14.215.59.178','1465326066');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('276','69','14.20.138.185','1465326360');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('277','68','14.20.138.185','1465326434');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('278','69','122.228.62.139','1465326447');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('279','76','14.20.138.185','1465326514');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('280','78','14.20.138.185','1465326547');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('281','58','14.20.138.185','1465326597');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('282','77','14.20.138.185','1465326781');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('283','69','125.90.118.223','1465328580');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('284','78','125.90.118.223','1465328634');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('285','78','183.5.122.214','1465329533');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('286','69','183.5.122.214','1465329574');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('287','78','14.215.35.239','1465329976');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('288','78','183.58.56.144','1465330577');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('289','69','183.58.56.144','1465330595');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('290','68','14.215.35.14','1465332841');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('291','77','14.215.35.14','1465332957');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('292','69','14.215.35.14','1465333053');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('293','77','36.40.167.126','1465336408');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('294','71','220.179.191.136','1465338952');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('295','69','116.21.161.184','1465339253');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('296','69','183.26.210.58','1465341648');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('297','78','223.104.175.74','1465342107');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('298','77','36.40.167.126','1465342924');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('299','77','101.226.227.98','1465342926');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('300','77','180.153.214.189','1465342926');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('301','71','14.215.63.178','1465345178');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('302','69','14.215.63.178','1465345198');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('303','74','14.215.63.178','1465345381');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('304','78','14.215.63.178','1465345500');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('305','68','61.135.189.103','1465346642');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('306','58','61.135.189.103','1465346733');
INSERT INTO `%DB_PREFIX%deal_visit_log` VALUES ('307','76','61.135.189.103','1465350261');
DROP TABLE IF EXISTS `%DB_PREFIX%deal_vote`;
CREATE TABLE `%DB_PREFIX%deal_vote` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%deal_vote_log`;
CREATE TABLE `%DB_PREFIX%deal_vote_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `deal_vote_id` int(11) NOT NULL COMMENT '投票id',
  `vote_status` tinyint(1) NOT NULL COMMENT '0表示不同意 1表示同意',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%faq`;
CREATE TABLE `%DB_PREFIX%faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='// 常见问题设置';
INSERT INTO `%DB_PREFIX%faq` VALUES ('1','基本问题','这是什么站?','我们是一个让你可以发起和支持创意项目的平台。如果你有一个创意的想法(新颖的产品?独立电影?)，我们欢迎你到我们的平台上发起项目，向公众推广，并得到资金的支持去完成你的想法。如果你喜欢创意，我们欢迎你来到我们平台，浏览各种有趣的项目，并力所能及支持他们。','1');
INSERT INTO `%DB_PREFIX%faq` VALUES ('2','基本问题','什么样的项目适合我们的平台?','我们欢迎一切有创意的想法，欢迎艺术家，电影工作者，音乐家，产品设计师，作家，画家，表演者，DJ等等来我们平台推广他们的创意。但是，我们的平台不适用于慈善项目或是创业投资项目。如果你不确定你的想法是否适合我们的平台，欢迎你直接与我们联系。','2');
INSERT INTO `%DB_PREFIX%faq` VALUES ('3','基本问题','这种模式有非法集资的风险吗?','不会，因为我们要求项目不能够以股权或是资金作为对支持者的回报。项目发起人更不能向支持者许诺任何资金上的收益。项目的回报必须是以实物（如产品，出版物），或者媒体内容（如提供视频或者音乐的流媒体播放或者下载）。我们平台项目接受支持，不能够以股权或者债券的形式。支持者对一个项目的支持属于购买行为，而不是投资行为。','3');
INSERT INTO `%DB_PREFIX%faq` VALUES ('4','基本问题','这个平台接受慈善项目类的提案么?','我们不接受慈善类项目。作为个人，我们支持社会公益慈善事业，但是我们平台不是支持此类项目的平台。我们所接受的是商业类，有销售购买行为的设计或者文创类的项目。项目发起人需要给支持以实物或者媒体内容类的回报。','4');
INSERT INTO `%DB_PREFIX%faq` VALUES ('5','项目发起人相关问题','是否会要求产品或作品的知识产权?','不会。我们只是提供一个宣传和支持的平台，知识产权由项目发起人所有。','5');
INSERT INTO `%DB_PREFIX%faq` VALUES ('6','项目发起人相关问题','什么人可以发起项目?','目前任何在两岸三地(中国大陆，台湾，港澳)的有创意的人都可以发起项目。你可以是一个从事创意行业的自由职业者，也可以是公司职员。只要你有个点子，我们都希望收到你的项目提案。','6');
INSERT INTO `%DB_PREFIX%faq` VALUES ('7','项目发起人相关问题','我怎么发起项目呢?','请到我们的网站并注册用户后，在我们网站上提交所需要的基本项目信息，包括项目的内容，目前进行的阶段等等。我们会有专人跟进，与你联系。','7');
INSERT INTO `%DB_PREFIX%faq` VALUES ('8','项目发起人相关问题','我想发起项目，但是我担心我的知识产权被人抄袭?','作为项目发起人，你可以选择公布更多的信息。知识产权敏感的信息，你可以选择不公开。同时，我们平台是一个面对公众的平台。你所提供的信息越丰富，越翔实，就越容易打动和说服别人的支持。','8');
INSERT INTO `%DB_PREFIX%faq` VALUES ('9','项目发起人相关问题','项目目标金额是否有上下限制?','我们对目标金额的下限是1000元人民币。原则上没有上限。但是资金的要求越高，成功的概率就越低。目前常见的目标金额从几千到几万不等。','9');
INSERT INTO `%DB_PREFIX%faq` VALUES ('10','项目发起人相关问题','没有达到目标金额，是否就不能得到支持?','是的。如果在项目截至日期到达时，没有达到预期，那么已经收到的资金会退还给支持者。这么做的原因是为了给支持者提供风险保护。只有当项目有足够多的人支持足够多的资金时，他们的支持才生效。','10');
INSERT INTO `%DB_PREFIX%faq` VALUES ('11','项目发起人相关问题','我的项目成功了，然后呢?','我们会分两次把资金打入你所提供的银行账户。两次汇款的时间和金额因项目而异，在项目上线之前，由我们平台与项目发起人确定。在资金的支持下，你就可以开始进行你的项目，给你的支持者以邮件或者其他形式的更新，并如期实现你承诺的回报。','11');
INSERT INTO `%DB_PREFIX%faq` VALUES ('12','项目发起人相关问题','如何设定项目截止日期?','一般来说，时间设置在一个月或以内比较合适。数据显示，绝大部分的支持发生在项目上线开始和结束前的一个星期中。','12');
INSERT INTO `%DB_PREFIX%faq` VALUES ('13','项目发起人相关问题','收到的金额能够超过预设的目标?','可以。在截至日期之前，项目可以一直接受资金支持。','13');
INSERT INTO `%DB_PREFIX%faq` VALUES ('14','项目发起人相关问题','大家支持的动机是什么?','大家对项目支持的动机是多样的。有些是因为项目发起者提供了有吸引力的回报，特别是产品设计类的项目。有些是因为认可这个项目，希望它能够实现。有些是因为认可项目的发起人，希望助他一臂之力。','14');
INSERT INTO `%DB_PREFIX%faq` VALUES ('15','项目发起人相关问题','什么样的回报比较合适?','回报因项目而异。可以是实物，比如如果是电影项目，可以提供成片后的DVD;如果是产品设计，可以提供产品;其他还有如明信片，T恤，出版物。也可以是非实物，比如鸣谢，与项目发起人共进晚餐，影片首映的门票等。我们欢迎项目发起人展开想象，设计出各式各样的回报。','15');
INSERT INTO `%DB_PREFIX%faq` VALUES ('16','项目发起人相关问题','如何能够吸引更多的人来支持我的项目?','对此，我们会另外详细介绍。简短来说，有以下要点\r\n- 拍摄一个有趣，吸引人的视频。讲述这个项目背后的故事。\r\n- 提供有吸引力，物有所值的回报。\r\n- 项目刚上线时，要发动你的亲朋好友来支持你。让你的项目有一个基本的人气。\r\n- 充分运用微博，人人等社交网站来推广。\r\n- 在项目上线期间，经常性的在你的项目页上提供项目的更新，与支持者，询问者的互动。\r\n- 项目宣传视频是必须的么?\r\n宣传视频是项目页上的重要内容。是公众了解你和你的项目的第一步。一个好的宣传视频，能够比文字和图片起到更好的宣传效果。基于这个原因，我们要求每个项目都提供一个视频。有必要的话，我们平台可以提供视频拍摄的支持。','16');
INSERT INTO `%DB_PREFIX%faq` VALUES ('17','项目发起人相关问题','项目宣传视频有什么要求?','我们要求宣传视频在两分钟之内。内容上，强烈建议包括以下内容\r\n发起人姓名\r\n项目简短描述(特别说明其吸引人的地方)，目前进展\r\n为什么需要支持\r\n谢谢大家','17');
INSERT INTO `%DB_PREFIX%faq` VALUES ('18','项目支持者相关问题','如果项目没有达到目标金额，我支持的资金会还给我么?','是的。如果项目在截止日期没有达到目标，你所支持的金额会返还给你。','18');
INSERT INTO `%DB_PREFIX%faq` VALUES ('19','项目支持者相关问题','如何支持一个项目?','每个项目页的右侧有可选择的支持额度和相应的回报介绍。想支持的话，选择你想支持的金额，鼠标点击绿色的按钮，即可。你可以选择支付宝或者财付通来完成付款。','19');
INSERT INTO `%DB_PREFIX%faq` VALUES ('20','项目支持者相关问题','如何保证项目发起人能够实现他们的承诺呢?','很多项目本身存在着风险，比如产品设计和纪录片的拍摄。有可能存在项目发起人无法完成其许诺的情况。项目支持者一方面要了解创意项目本身是有风险的，另一方面，我们要求项目发起人提供联系方式，并且鼓励有意支持者直接联系他们，在与项目发起人的沟通和互动中对项目的价值，风险，项目发起人的执行力等等有所判断。','20');
DROP TABLE IF EXISTS `%DB_PREFIX%file_verifies`;
CREATE TABLE `%DB_PREFIX%file_verifies` (
  `nameid` char(32) NOT NULL DEFAULT '',
  `cthash` varchar(32) NOT NULL DEFAULT '',
  `method` enum('local','official') NOT NULL DEFAULT 'official',
  `filename` varchar(254) NOT NULL DEFAULT '',
  PRIMARY KEY (`nameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%finance_company`;
CREATE TABLE `%DB_PREFIX%finance_company` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%finance_company_focus`;
CREATE TABLE `%DB_PREFIX%finance_company_focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID',
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='关注的公司';
DROP TABLE IF EXISTS `%DB_PREFIX%finance_company_investment_case`;
CREATE TABLE `%DB_PREFIX%finance_company_investment_case` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%finance_company_sub_product`;
CREATE TABLE `%DB_PREFIX%finance_company_sub_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '融资公司ID',
  `create_time` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL COMMENT '产品名称',
  `product_website` varchar(255) NOT NULL COMMENT '子产品链接',
  `status` tinyint(1) NOT NULL COMMENT '0 未审核 1审核通过 2 审核不通过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司的子产品';
DROP TABLE IF EXISTS `%DB_PREFIX%finance_company_team`;
CREATE TABLE `%DB_PREFIX%finance_company_team` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%goods`;
CREATE TABLE `%DB_PREFIX%goods` (
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%goods` VALUES ('3','超级面包','好吃啊','10','./public/attachment/201410/14/11/origin/26bf3af3edd169dd7cd1a4c14d03242690.jpg','','','1','1000000000','10','1','1','1','1','1','1','','','','0','0','0');
INSERT INTO `%DB_PREFIX%goods` VALUES ('4','火腿面包','好吃啊','10','./public/attachment/201211/07/11/326730647fde78562777b82f0a9e81a155.jpg','','','2','22222','11','2','1','1','1','1','1','','','','0','0','1');
INSERT INTO `%DB_PREFIX%goods` VALUES ('5','德芙','好吃啊','11','','','','1','111111111','11','1','1','1','1','1','1','','','','0','0','0');
INSERT INTO `%DB_PREFIX%goods` VALUES ('6','金地','好吃啊','11','./public/attachment/201508/18/23/55d355b980d4b.png','','','3','2147483647','111','1','0','0','0','0','1','','','','0','0','0');
DROP TABLE IF EXISTS `%DB_PREFIX%goods_attr`;
CREATE TABLE `%DB_PREFIX%goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '属性名称',
  `goods_type_attr_id` int(11) NOT NULL COMMENT '属性ID',
  `score` int(11) NOT NULL COMMENT '所需积分',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `is_checked` tinyint(1) NOT NULL COMMENT '是否有独立库存',
  PRIMARY KEY (`id`),
  KEY `goods_type_attr_id` (`goods_type_attr_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%goods_attr_stock`;
CREATE TABLE `%DB_PREFIX%goods_attr_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr_cfg` text NOT NULL,
  `stock_cfg` int(11) NOT NULL,
  `attr_str` text NOT NULL,
  `buy_count` int(11) NOT NULL,
  `attr_key` varchar(100) NOT NULL COMMENT '属性ID以下划线从小到大排序的key',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%goods_cate`;
CREATE TABLE `%DB_PREFIX%goods_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品分类名称',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%goods_cate` VALUES ('10','面包','1','0','0','1');
INSERT INTO `%DB_PREFIX%goods_cate` VALUES ('11','巧克力','1','0','0','2');
INSERT INTO `%DB_PREFIX%goods_cate` VALUES ('12','大幅度','1','0','0','3');
DROP TABLE IF EXISTS `%DB_PREFIX%goods_order`;
CREATE TABLE `%DB_PREFIX%goods_order` (
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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%goods_order` VALUES ('36','2016042203294821','4','火腿面包','2','2','2','1','27','爆炒大熊猫','','1','0','1461281388','2016-04-22','1461281388','2016-04-22','1','0000-00-00','0','13992864996','4654654','速度房顶上发生大地色都是','安庆','安徽','的吗','','','','[仅工作日送货]','');
DROP TABLE IF EXISTS `%DB_PREFIX%goods_type`;
CREATE TABLE `%DB_PREFIX%goods_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品类型名',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%goods_type_attr`;
CREATE TABLE `%DB_PREFIX%goods_type_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品属性名',
  `input_type` tinyint(1) NOT NULL,
  `preset_value` text NOT NULL,
  `goods_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%help`;
CREATE TABLE `%DB_PREFIX%help` (
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
INSERT INTO `%DB_PREFIX%help` VALUES ('1','服务条款','<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong><span style=\"color:#337FE5;\"><strong>『一、接受条款</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;我们所提供的服务包含我们平台网站体验和使用、我们平台互联网消息传递服务以及我们平台提供的与我们平台网站有关的任何其他特色功能、内容或应用程序(合称\"我们平台服务\")。无论用户是以\"访客\"(表示用户只是浏览我们平台网站)还是\"成员\"(表示用户已在我们平台注册并登录)的身份使用我们平台服务，均表示该用户同意遵守本使用协议。\r\n</p>\r\n<p>\r\n	如果用户自愿成为我们平台成员并与其他成员交流(包括通过我们平台网站直接联系或通过我们平台各种服务而连接到的成员)，以及使用我们平台网站及其各种附加服务，请务必认真阅读本协议并在注册过程中表明同意接受本协议。本协议的内容包含我们平台关于接受我们平台服务和在我们平台网站上发布内容的规定、用户使用我们平台服务所享有的权利、承担的义务和对使用我们平台服务所受的限制、以及我们平台的隐私条款。如果用户选择使用某些我们平台服务，可能会收到要求其下载软件或内容的通知，和/或要求用户同意附加条款和条件的通知。除非用户选择使用的我们平台服务相关的附加条款和条件另有规定，附加的条款和条件都应被包含于本协议中。\r\n</p>\r\n<p>\r\n	我们平台有权随时修改本协议文本中的任何条款。一旦我们平台对本协议进行修改,我们平台将会以公告形式发布通知。任何该等修改自发布之日起生效。如果用户在该等修改发布后继续使用我们平台服务，则表示该用户同意遵守对本协议所作出的该等修改。因此，请用户务必定期查阅本协议，以确保了解所有关于本协议的最新修改。如果用户不同意我们平台对本协议进行的修改，请用户离开我们平台网站并立即停止使用我们平台服务。同时，用户还应当删除个人档案并注销成员资格。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『二、遵守法律</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;当使用我们平台服务时，用户同意遵守中华人民共和国(下称\"中国\")的相关法律法规，包括但不限于《中华人民共和国宪法》、《中华人民共和国合同法》、《中华人民共和国电信条例》、《互联网信息服务管理办法》、《互联网电子公告服务管理规定》、《中华人民共和国保守国家秘密法》、《全国人民代表大会常务委员会关于维护互联网安全的决定》、《中华人民共和国计算机信息系统安全保护条例》、《计算机信息网络国际联网安全保护管理办法》、《中华人民共和国著作权法》及其实施条例、《互联网著作权行政保护办法》等。用户只有在同意遵守所有相关法律法规和本协议时，才有权使用我们平台服务(无论用户是否有意访问或使用此服务)。请用户仔细阅读本协议并将其妥善保存。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『三、用户账号、密码及安全</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户应提供及时、详尽、准确的个人资料，并不断及时更新注册时提供的个人资料，保持其详尽、准确。所有用户输入的资料将引用为注册资料。我们平台不对因用户提交的注册信息不真实或未及时准确变更信息而引起的问题、争议及其后果承担责任。\r\n</p>\r\n<p>\r\n	用户不应将其帐号、密码转让、出借或告知他人，供他人使用。如用户发现帐号遭他人非法使用，应立即通知我们平台。因黑客行为或用户的保管疏忽导致帐号、密码遭他人非法使用的，我们平台不承担任何责任。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『四、隐私权政策</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户提供的注册信息及我们平台保留的用户所有资料将受到中国相关法律法规和我们平台《隐私权政策》的规范。《隐私权政策》构成本协议不可分割的一部分。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『五、上传内容</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户通过任何我们平台提供的服务上传、张贴、发送(通过电子邮件或任何其它方式传送)的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任何其他材料(以下简称\"内容\"，包括用户个人的或个人创作的照片、声音、视频等)，无论系公开还是私下传播，均由用户和内容提供者承担责任，我们平台不对该等内容的正确性、完整性或品质作出任何保证。用户在使用我们平台服务时，可能会接触到令人不快、不适当或令人厌恶之内容，用户需在接受服务前自行做出判断。在任何情况下，我们平台均不为任何内容负责(包括但不限于任何内容的错误、遗漏、不准确或不真实)，亦不对通过我们平台服务上传、张贴、发送(通过电子邮件或任何其它方式传送)的内容衍生的任何损失或损害负责。我们平台在管理过程中发现或接到举报并进行初步调查后，有权依法停止任何前述内容的传播并采取进一步行动，包括但不限于暂停某些用户使用我们平台的全部或部分服务，保存有关记录，并向有关机关报告。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『六、用户行为</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;用户在使用我们平台服务时，必须遵守中华人民共和国相关法律法规的规定，用户保证不会利用我们平台服务进行任何违法或不正当的活动，包括但不限于下列行为∶<br />\r\n上传、展示、张贴或以其它方式传播含有下列内容之一的信息：<br />\r\n反对宪法及其他法律的基本原则的;<br />\r\n危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的;<br />\r\n损害国家荣誉和利益的;<br />\r\n煽动民族仇恨、民族歧视、破坏民族团结的;<br />\r\n破坏国家宗教政策，宣扬邪教和封建迷信的;<br />\r\n散布谣言，扰乱社会秩序，破坏社会稳定的;<br />\r\n散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的;<br />\r\n侮辱或者诽谤他人，侵害他人合法权利的;<br />\r\n含有虚假、有害、胁迫、侵害他人隐私、骚扰、中伤、粗俗、猥亵、或其它道德上令人反感的内容;<br />\r\n含有中国法律、法规、规章、条例以及任何具有法律效力的规范所限制或禁止的其它内容的;<br />\r\n不得为任何非法目的而使用网络服务系统;<br />\r\n用户同时保证不会利用我们平台服务从事以下活动：<br />\r\n未经允许，进入计算机信息网络或者使用计算机信息网络资源的;<br />\r\n未经允许，对计算机信息网络功能进行删除、修改或者增加的;<br />\r\n未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的;故意制作、传播计算机病毒等破坏性程序的;<br />\r\n其他危害计算机信息网络安全的行为。<br />\r\n如用户在使用网络服务时违反任何上述规定，我们平台或经其授权者有权要求该用户改正或直接采取一切必要措施(包括但不限于更改、删除相关内容、暂停或终止相关用户使用我们平台服务)以减轻和消除该用户不当行为造成的影响。<br />\r\n用户不得对我们平台服务的任何部分或全部以及通过我们平台取得的任何形式的信息，进行复制、拷贝、出售、转售或用于任何其它商业目的。<br />\r\n用户须对自己在使用我们平台服务过程中的行为承担法律责任。用户承担法律责任的形式包括但不限于：停止侵害行为，向受到侵害者公开赔礼道歉，恢复受到侵害这的名誉，对受到侵害者进行赔偿。如果我们平台网站因某用户的非法或不当行为受到行政处罚或承担了任何形式的侵权损害赔偿责任，该用户应向我们平台进行赔偿(不低于我们平台向第三方赔偿的金额)并通过全国性的媒体向我们平台公开赔礼道歉。<br />\r\n『七、知识产权和其他合法权益（包括但不限于名誉权、商誉等）』<br />\r\n我们平台并不对用户发布到我们平台服务中的文本、文件、图像、照片、视频、声音、音乐、其他创作作品或任何其他材料(前文称为\"内容\")拥有任何所有权。在用户将内容发布到我们平台服务中后，用户将继续对内容享有权利，并且有权选择恰当的方式使用该等内容。如果用户在我们平台服务中或通过我们平台服务展示或发表任何内容，即表明该用户就此授予我们平台一个有限的许可以使我们平台能够合法使用、修改、复制、传播和出版此类内容。<br />\r\n用户同意其已就在我们平台服务所发布的内容，授予我们平台可以免费的、永久有效的、不可撤销的、非独家的、可转授权的、在全球范围内对所发布内容进行使用、复制、修改、改写、改编、发行、翻译、创造衍生性著作的权利，及/或可以将前述部分或全部内容加以传播、表演、展示，及/或可以将前述部分或全部内容放入任何现在已知和未来开发出的以任何形式、媒体或科技承载的著作当中。<br />\r\n用户声明并保证：用户对其在我们平台服务中或通过我们平台服务发布的内容拥有合法权利;用户在我们平台服务中或通过我们平台服务发布的内容不侵犯任何人的肖像权、隐私权、著作权、商标权、专利权、及其它合同权利。如因用户在我们平台服务中或通过我们平台服务发布的内容而需向其他任何人支付许可费或其它费用，全部由该用户承担。<br />\r\n我们平台服务中包含我们平台提供的内容，包含用户和其他我们平台许可方的内容(下称\"我们平台的内容\")。我们平台的内容受《中华人民共和国著作权法》、《中华人民共和国商标法》、《中华人民共和国专利法》、《中华人民共和国反不正当竞争法》和其他相关法律法规的保护，我们平台拥有并保持对我们平台的内容和我们平台服务的所有权利。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『八、国际使用之特别警告</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户已了解国际互联网的无国界性，同意遵守所有关于网上行为、内容的法律法规。用户特别同意遵守有关从中国或用户所在国家或地区输出信息所可能涉及、适用的全部法律法规。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『九、赔偿</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;由于用户通过我们平台服务上传、张贴、发送或传播的内容，或因用户与本服务连线，或因用户违反本使用协议，或因用户侵害他人任何权利而导致任何第三人向我们平台提出赔偿请求，该用户同意赔偿我们平台及其股东、子公司、关联企业、代理人、品牌共有人或其它合作伙伴相应的赔偿金额(包括我们平台支付的律师费等)，以使我们平台的利益免受损害。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十、关于使用及储存的一般措施</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户承认我们平台有权制定关于服务使用的一般措施及限制，包括但不限于我们平台服务将保留用户的电子邮件信息、用户所张贴内容或其它上载内容的最长保留期间、用户一个帐号可收发信息的最大数量、用户帐号当中可收发的单个信息的大小、我们平台服务器为用户分配的最大磁盘空间，以及一定期间内用户使用我们平台服务的次数上限(及每次使用时间之上限)。通过我们平台服务存储或传送的任何信息、通讯资料和其它任何内容，如被删除或未予储存，用户同意我们平台毋须承担任何责任。用户亦同意，超过一年未使用的帐号，我们平台有权关闭。我们平台有权依其自行判断和决定，随时变更相关一般措施及限制。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十一、服务的修改</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户了解并同意，无论通知与否，我们平台有权于任何时间暂时或永久修改或终止部分或全部我们平台服务，对此，我们平台对用户和任何第三人均无需承担任何责任。用户同意，所有上传、张贴、发送到我们平台的内容，我们平台均无保存义务，用户应自行备份。我们平台不对任何内容丢失以及用户因此而遭受的相关损失承担责任。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『一二、接终止服务</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户同意我们平台可单方面判断并决定，如果用户违反本使用协议或用户长时间未能使用其帐号，我们平台可以终止该用户的密码、帐号或某些服务的使用，并可将该用户在我们平台服务中留存的任何内容加以移除或删除。我们平台亦可基于自身考虑，在通知或未通知之情形下，随时对该用户终止部分或全部服务。用户了解并同意依本使用协议，无需进行事先通知，我们平台可在发现任何不适宜内容时，立即关闭或删除该用户的帐号及其帐号中所有相关信息及文件，并暂时或永久禁止该用户继续使用前述文件或帐号。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十三、于广告商进行的交易</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户通过我们平台服务与广告商进行任何形式的通讯或商业往来，或参与促销活动(包括相关商品或服务的付款及交付)，以及达成的其它任何条款、条件、保证或声明，完全是用户与广告商之间的行为。除有关法律法规明文规定要求我们平台承担责任外，用户因前述任何交易、沟通等而遭受的任何性质的损失或损害，我们平台均不予负责。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十四、链接</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户了解并同意，对于我们平台服务或第三人提供的其它网站或资源的链接是否可以利用，我们平台不予负责;存在或源于此类网站或资源的任何内容、广告、产品或其它资料，我们平台亦不保证或负责。因使用或信赖任何此类网站或资源发布的或经由此类网站或资源获得的任何商品、服务、信息，如对用户造成任何损害，我们平台不负任何直接或间接责任。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十五、禁止商业行为</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户同意不对我们平台服务的任何部分或全部以及用户通过我们平台的服务取得的任何物品、服务、信息等，进行复制、拷贝、出售、转售或用于任何其它商业目的。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十六、我们平台的专属权力</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户了解并同意，我们平台服务及其所使用的相关软件(以下简称\"服务软件\")含有受到相关知识产权及其它法律保护的专有保密资料。用户同时了解并同意，经由我们平台服务或广告商向用户呈现的赞助广告或信息所包含之内容，亦可能受到著作权、商标、专利等相关法律的保护。未经我们平台或广告商书面授权，用户不得修改、出售、传播部分或全部服务内容或软件，或加以制作衍生服务或软件。我们平台仅授予用户个人非专属的使用权，用户不得(也不得允许任何第三人)复制、修改、创作衍生著作，或通过进行还原工程、反向组译及其它方式破译原代码。用户也不得以转让、许可、设定任何担保或其它方式移转服务和软件的任何权利。用户同意只能通过由我们平台所提供的界面而非任何其它方式使用我们平台服务。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十七、担保与保证</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;我们平台使用协议的任何规定均不会免除因我们平台造成用户人身伤害或因故意造成用户财产损失而应承担的任何责任。\r\n</p>\r\n<p>\r\n	用户使用我们平台服务的风险由用户个人承担。我们平台对服务不提供任何明示或默示的担保或保证，包括但不限于商业适售性、特定目的的适用性及未侵害他人权利等的担保或保证。\r\n</p>\r\n<p>\r\n	我们平台亦不保证以下事项：\r\n</p>\r\n<p>\r\n	服务将符合用户的要求;\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;服务将不受干扰、及时提供、安全可靠或不会出错;\r\n</p>\r\n<p>\r\n	使用服务取得的结果正确可靠;\r\n</p>\r\n<p>\r\n	用户经由我们平台服务购买或取得的任何产品、服务、资讯或其它信息将符合用户的期望，且软件中任何错误都将得到更正。\r\n</p>\r\n<p>\r\n	用户应自行决定使用我们平台服务下载或取得任何资料且自负风险，因任何资料的下载而导致的用户电脑系统损坏或数据流失等后果，由用户自行承担。\r\n</p>\r\n<p>\r\n	用户经由我们平台服务获知的任何建议或信息(无论书面或口头形式)，除非使用协议有明确规定，将不构成我们平台对用户的任何保证。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十八、责任限制</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;用户明确了解并同意，基于以下原因而造成的任何损失，我们平台均不承担任何直接、间接、附带、特别、衍生性或惩罚性赔偿责任(即使我们平台事先已被告知用户或第三方可能会产生相关损失)：\r\n</p>\r\n<p>\r\n	我们平台服务的使用或无法使用;\r\n</p>\r\n<p>\r\n	通过我们平台服务购买、兑换、交换取得的任何商品、数据、信息、服务、信息，或缔结交易而发生的成本;\r\n</p>\r\n<p>\r\n	用户的传输或数据遭到未获授权的存取或变造;\r\n</p>\r\n<p>\r\n	任何第三方在我们平台服务中所作的声明或行为;\r\n</p>\r\n<p>\r\n	与我们平台服务相关的其它事宜，但本使用协议有明确规定的除外。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『十九、一般性条款</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;本使用协议构成用户与我们平台之间的正式协议，并用于规范用户的使用行为。在用户使用我们平台服务、使用第三方提供的内容或软件时，在遵守本协议的基础上，亦应遵守与该等服务、内容、软件有关附加条款及条件。\r\n</p>\r\n<p>\r\n	本使用协以及用户与我们平台之间的关系，均受到中华人民共和国法律管辖。\r\n</p>\r\n<p>\r\n	用户与我们平台就服务本身、本使用协议或其它有关事项发生的争议，应通过友好协商解决。协商不成的，应向北京市东城区人民法院提起诉讼。\r\n</p>\r\n<p>\r\n	我们平台未行使或执行本使用协议设定、赋予的任何权利，不构成对该等权利的放弃。\r\n</p>\r\n<p>\r\n	本使用协议中的任何条款因与中华人民共和国法律相抵触而无效，并不影响其它条款的效力。\r\n</p>\r\n<p>\r\n	本使用协议的标题仅供方便阅读而设，如与协议内容存在矛盾，以协议内容为准。\r\n</p>\r\n<p>\r\n	<strong><strong><span style=\"color:#337FE5;\"><strong>『二十、举报</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></strong><br />\r\n</strong> \r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;如用户发现任何违反本服务条款的情事，请及时通知我们平台。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>','term','','1','1');
INSERT INTO `%DB_PREFIX%help` VALUES ('2','联系我们','<p>\r\n	<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『公司</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></span>\r\n</p>\r\n<p>\r\n	<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;西安聚云网络科技有限公司</span>\r\n</p>\r\n<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『客户服务</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></span><br />\r\n<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;如果您在使用酒店众筹（zcjiudian.com）的过程中有任何疑问请您与客服人员联系。</span><br />\r\n<p>\r\n	<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『客服电话</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></span>\r\n</p>\r\n<p>\r\n	<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;029-62867633/801/802/803/805</span>\r\n</p>\r\n<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『公司地址</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span></span><br />\r\n<span style=\"color:#333333;font-family:tahoma, arial, \" font-size:15px;line-height:30px;background-color:#ffffff;\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;陕西省西安市高新区丈八一路汇鑫IBC &nbsp; &nbsp;D座12楼6789室</span>','intro','','1','1');
INSERT INTO `%DB_PREFIX%help` VALUES ('3','隐私策略','<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"> <span style=\"color:#337FE5;\"><strong>『</strong></span><span style=\"color:#337FE5;\"><strong>隐私权规则适用范围</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span> \r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp;包括酒店众筹如何收集、处理、保护：\r\n</p>\r\n<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">&nbsp; &nbsp; &nbsp;1、用户在登录本网站和服务器时留下的个人身份信息;</span><br />\r\n<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">&nbsp; &nbsp; &nbsp;2、用户通过本网站和服务器与其他用户或非用户之间传送的各种资讯;以及</span><br />\r\n<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">&nbsp; &nbsp; &nbsp;3、酒店众筹与商业伙伴共享的其他用户或非用户的各种信息。</span><br />\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『</strong></span><span style=\"color:#337FE5;\"><strong>信息和资讯收集和使用</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span> \r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; 你提供的信息和资讯\r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; 在你登记注册<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>帐户，或使用该帐户，或参加其他<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>及与之相关需要注册的服务或推广活动时，我们会要求你提供个人信息(包括但不限于你的电子邮件地址、帐户密码以及昵称等)。这些信息会以加密方式保存在安全的服务器上。我们会将从你的帐户下采集的个人信息和资讯与其他从<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>服务中或从第三方获得的信息和资讯进行整合，以便向你提供更好的用户体验和改善我们的服务质量。在某些服务中，我们会给予提示，由你亲自决定是否参与上述信息和资讯的整合。\r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『</strong></span><span style=\"color:#337FE5;\"><strong>Cookie</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span> \r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp;当你访问<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>时，我们会向你的电脑发送一个或多个cookie - 包含有一串字符的小文件 - 它能够对你的浏览器进行辨识。我们通过饼干技术来记录用户的使用偏好和习惯并跟踪用户倾向(诸如用户常用的搜索方式等)，以具有针对性的改善我们的服务质量。大多数浏览器都能在默认设置的状态下接受cookie，但是你也可以重新设置浏览器来拒绝所有cookie，或者让浏览器在是否接受cookie时进行提示。需要注意的是，如果你将浏览器设置为拒绝接受cookie，则一些<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>的特色功能或服务可能会无法正常运行。我们允许那些在<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>网页上发布广告的公司在用户电脑上设定或取用Cookie。\r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『</strong></span><span style=\"color:#337FE5;\"><strong>日志资讯</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span><span style=\"color:#337FE5;\"><strong></strong></span> \r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 当你使用<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>的服务时，我们的主机会自动记录你的浏览器在访问网站时所发送的信息和资讯。主机日志资讯包括但不限于你的网路请求、IP地址、浏览器类型、浏览器使用的语言、请求的日期和时间，以及一个或多个可以对你的浏览器进行辨识的cookie。\r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><strong><span style=\"color:#337FE5;\">『</span></strong><span style=\"color:#337FE5;\"><strong>用户交流</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span> \r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 当你与<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>通过电子邮件或其他方式进行交流时，我们可能会记录这些交流内容用以处理你的问题以及改善我们的服务。\r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; <span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>仅对本隐私权规则和/或具体服务的隐私声明中允许的目的而对用户的个人信息和资讯进行处理。除上述已列明部分外，这些目的还包括：向用户提供产品或服务，包括列明定制的内容和广告;审计、调研和分析，以维持、保护和改善酒店的服务;确保网站的技术运作;开发新服务;以及其他<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>运营所需要的目的。\r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><span style=\"color:#337FE5;\"><strong>『</strong></span><span style=\"color:#337FE5;\"><strong>资讯公开与共享</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span> \r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp;<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>不会将你的个人信息和资讯故意透露、出租或出售给任何第三方。但以下情况除外：\r\n</p>\r\n<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">&nbsp; &nbsp; &nbsp; &nbsp;1、用户本人同意与第三方共享信息和资讯;</span><br />\r\n<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">&nbsp; &nbsp; &nbsp; &nbsp;2、只有透露用户的个人信息和资讯，才能提供用户所要求的某种产品和服务;</span><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">应代表</span><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\"><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span></span><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">提供产品或服务的主体的要求提供(除非我们另行通知，否则该等主体无权将相关用户个人信息和资讯用于提供产品和服务之外的其他用途)：</span><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">根据法律法规或行政命令的要求提供;</span><br />\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"> <span style=\"line-height:1.5;\">&nbsp; &nbsp; &nbsp; &nbsp;3、因外部审计需要而提供;用户违反了</span><span style=\"line-height:1.5;\"><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span></span><span style=\"line-height:1.5;\">服务条款或任何其他产品及服务的使用规定;经</span><span style=\"line-height:1.5;\"><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span></span><span style=\"line-height:1.5;\">评估，用户的帐户存在风险，需要加以保护。</span> \r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"><span style=\"line-height:1.5;\"><strong><span style=\"color:#337FE5;\">『</span></strong><span style=\"color:#337FE5;\"><strong>安全保障</strong></span><span style=\"color:#337FE5;\"><strong>』</strong></span><span style=\"color:#337FE5;\"><strong></strong></span></span> \r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"> <span style=\"line-height:1.5;\">&nbsp; &nbsp; &nbsp; &nbsp;你的</span><span style=\"line-height:1.5;\"><span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span></span><span style=\"line-height:1.5;\">帐户具有密码保护功能，以确保你的隐私及信息和资讯安全。</span> \r\n	</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp; &nbsp; &nbsp; &nbsp;<span style=\"color:#333333;font-family:\" font-size:14px;line-height:1.5;background-color:#ffffff;\"=\"\">酒店众筹</span>不使用明文保存密码，我们以符合工业标准的加密方式对密码进行妥善保护。\r\n</p>\r\n<p style=\"color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\"> <span style=\"color:#009900;\">&nbsp;</span><span style=\"font-size:16px;color:#009900;\"><strong>酒店众筹</strong></span><span style=\"color:#337FE5;\"><span style=\"font-size:16px;color:#009900;\"><strong>会不时对隐私权规则进</strong></span><span style=\"font-size:16px;color:#009900;\"><strong>行修改。如有修改，我们会在修改后及时公告相关修改内容及新规定，以便你知悉和使用。</strong></span></span><span style=\"font-size:16px;color:#009900;\"><strong>如果你有任何问题和建议，请随时通知我们</strong></span><span style=\"font-size:16px;color:#009900;\"><strong>。</strong></span> \r\n	</p>','privacy','','1','1');
INSERT INTO `%DB_PREFIX%help` VALUES ('4','关于我们','<b><span style=\"color:#337FE5;font-size:16px;\"><strong>『关于酒店众筹</strong></span><span style=\"color:#337FE5;font-size:16px;\"><strong>』</strong></span></b><br />\r\n<p>\r\n	<span style=\"font-size:14px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;众筹，译自国外crowdfunding一词，即大众筹资或群众筹资。是指用团购+预购的形式，向网友募集项目资金的模式。众筹利用互联网和SNS传播的\r\n特性，让许多有梦想的人可以向公众展示自己的创意，发起项目争取别人的支持与帮助，进而获得所需要的援助，支持者则会获得实物、服务等不同形式的回报。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<span style=\"font-size:14px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;众筹是一个协助亲们发起创意、梦想的平台，不论你是学生、白领、艺术家、明星，如果你有一个想完成的计划（例如：电影、音乐、动漫、设计、公益等），你可\r\n以在此发起项目向大家展示你的计划，并邀请喜欢你的计划的人以资金支持你。如果你愿意帮助别人，支持别人的梦想，你可以在众筹浏览到各行各业的人发起的项\r\n目计划，也可以成为发起人的梦想合伙人，当你们一起见证项目成功后，你还会获得发起人感谢你支持的回报。</span>','about','','1','1');
INSERT INTO `%DB_PREFIX%help` VALUES ('7','撰写指南','<p>\r\n	<span><span style=\"font-size:14px;line-height:21px;\"><b><b><span style=\"color:#337FE5;font-size:14px;\"><strong>项目规范</strong></span></b></b></span></span> \r\n</p>\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本众筹系统是中国最专业的众筹系统服务提供商，帮您在一天内快速搭建众筹平台。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;以下是酒店众筹网站发布项目的基本要求，不合要求的项目，将会被拒绝或删除。如果你有疑问，可以通过邮件或电话联系我们。&nbsp;<br />\r\n<span style=\"color:#999999;\">&nbsp;&nbsp;&nbsp;&nbsp;附注：某些规范会随着时间而更新或者调整，会导致一些旧项目并不能完全符合最新规范。</span> \r\n<p>\r\n	<b><span style=\"color:#337FE5;font-size:14px;\"><strong>『项目发布团队资格</strong></span><span style=\"color:#337FE5;font-size:14px;\"><strong>』</strong></span></b> \r\n</p>\r\n&nbsp; &nbsp; （团队中必须有至少一名成员满足以下条件）<br />\r\n&nbsp; &nbsp; 18周岁以上;<br />\r\n&nbsp; &nbsp; 中华人民共和国公民;<br />\r\n&nbsp; &nbsp; 拥有能够在中国地区接收人民币汇款的银行卡或者支付宝、财付通账户;<br />\r\n&nbsp; &nbsp; 提供必要的身份认证和资质认证，根据项目内容，有可能包括但不限于：身份证，护照，学历证明等;<br />\r\n&nbsp; &nbsp; 其他跟项目发布、执行需求、渠道销售相关的必须条件。\r\n<p>\r\n	<b><span style=\"color:#337FE5;font-size:14px;\"><strong>『项目发布</strong></span><span style=\"color:#337FE5;font-size:14px;\"><strong>』</strong></span></b> \r\n</p>\r\n&nbsp; &nbsp; 根据相关法律法规，项目发布申请提交后，须经过众筹网站工作人员审核后才能发布;<br />\r\n&nbsp; &nbsp; 根据项目的内容，众筹网站会要求项目发布团队提供相关材料，证明项目的可行性，以及项目发布团队的执行能力;<br />\r\n&nbsp; &nbsp; 众筹网站对提交上线审核的项目是否拥有上线资格具有最终决定权;<br />\r\n&nbsp; &nbsp; 项目在众筹网站上线预售期间，不能在中国大陆其他相似平台（包括但不限于众筹网站、电商网站、及其他形式网店等）同时发布。一经发现将立即下线处 理，其项目上线期间所获得的金额将被立即退回给预订用户在众筹网站上的账户中。<br />\r\n<br />\r\n<b><span style=\"color:#337FE5;font-size:14px;\"><strong>『项目内容规范</strong></span><span style=\"color:#337FE5;font-size:14px;\"><strong>』</strong></span></b><span style=\"color:#999999;\">（不符合以下内容规范的项目将被退回）</span>：<br />\r\n&nbsp; &nbsp; 1. 只允许尚未正式对外发布的项目在众筹网站上线。项目在众筹网站上线之前，不能在中国大陆其他相似平台（包括但不限于众筹网站、电商网站、及其 他形式网店等）或媒体发布。<br />\r\n&nbsp; &nbsp; 2. 项目必须为智能项目。智能项目的定义为：设备必须可采集数据、联网联动，并提供自动化的服务。单纯有设计感的非智能项目暂时无法通过审核。<br />\r\n&nbsp; &nbsp; 3. 项目发布方必须在项目上线前提供无bug的实物试产样机，由众筹网站工作人员进行盲测确保没有问题才能正式上线。<br />\r\n&nbsp; &nbsp; 4. 项目内容介绍框架必须包含“项目介绍”、“团队介绍”、“研发进展”等重要板块。<br />\r\n&nbsp; &nbsp; 5. 项目软硬件设计必须完整、合理、具有可行性；有完整的计划和执行能力，且图片、视频不能借用或盗用非自行拍摄的内容。<br />\r\n&nbsp; &nbsp; 6. 项目发布团队必须有明确的生产计划及售后服务计划，尚不确定是否会量产的项目不符合首次发布的标准皆不能上线。<br />\r\n&nbsp; &nbsp; 7. 提交申请的项目必须能符合如下分类：医疗健康、家居生活、出行定位、影音娱乐、科技外设。<br />\r\n&nbsp; &nbsp; 8. 以下类别的项目或内容将不被允许在此发布或作为给预订用户的附加回报：<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;烟、酒相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;洗浴、美容或化妆项目相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;毒品、类似毒品的物质、吸毒用具、烟等相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;枪支、武器和刀具相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;营养补充剂相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;色情、保健、性用品内容相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;房地产相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;饮食类相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;财政奖励(所有权、利润份额、还款贷款等)<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;多级直销和传销类相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;令人反感的内容(仇恨言论、不适当内容等)<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持或反对政治党派的项目<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;推广或美化暴力行为的项目<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对奖、彩票和抽奖活动相关<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;股权、债券、分红、利息等形式的附加回报<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;与首发项目无关的附加回报<br />\r\n&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;以其他无可行、不合理的承诺作为附加回报\r\n<p>\r\n	<b><span style=\"color:#337FE5;font-size:14px;\"><strong>『举报及推荐标准</strong></span><span style=\"color:#337FE5;font-size:14px;\"><strong>』:</strong></span></b> \r\n</p>\r\n&nbsp; &nbsp; 举报：不符合《项目内容规范》<br />\r\n&nbsp; &nbsp; 合格：符合《项目内容规范》<br />\r\n&nbsp; &nbsp; 推荐：合格并且满足下列标准中的任意1-3项（含3项），视为推荐<br />\r\n&nbsp; &nbsp; 强烈推荐：合格并且满足下列标准中的任意3项以上，视为强烈推荐<br />\r\n&nbsp; &nbsp; 1. 项目定位清晰，功能逻辑性强、完整且条理清晰、团队对研发和生产有完整的计划和管控能力，有相关的图片和视频（图片、视频不能借用或盗用非 本人/公司拍摄的）<br />\r\n&nbsp; &nbsp; 2. 项目符合时下趋势、有热点，具备可传播性<br />\r\n&nbsp; &nbsp; 3. 项目本身有创意、创新；非山寨、抄袭、跟风；市面上无同类相似项目<br />\r\n&nbsp; &nbsp; 4. 项目设计感好，有品质，符合大众审美喜好的要求<br />\r\n&nbsp; &nbsp; 5. 项目发布团队有一定的推广渠道、媒体资源、或在公众平台上有一定的影响力<br />\r\n&nbsp; &nbsp; 6. 项目发布团队的话题运营能力出众，与粉丝互动积极正面，能调动起网友的兴趣和参与感<br />','write_guide','','1','1');
DROP TABLE IF EXISTS `%DB_PREFIX%index_image`;
CREATE TABLE `%DB_PREFIX%index_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 表示首页轮播 1表示产品页轮播 2表示股权轮播',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='//首页图片';
INSERT INTO `%DB_PREFIX%index_image` VALUES ('5','./public/attachment/201211/07/10/5099c97ad9f82.gif','http://qodoculture.com','5','酒店众筹','2');
INSERT INTO `%DB_PREFIX%index_image` VALUES ('6','./public/attachment/201211/07/10/5099c984946c3.jpg','http://mollygogo.com','4','4','1');
INSERT INTO `%DB_PREFIX%index_image` VALUES ('9','./public/attachment/201602/29/01/56d32aad31975.jpg','http://t5.mollygogo.com','3','3','0');
INSERT INTO `%DB_PREFIX%index_image` VALUES ('11','./public/attachment/201606/01/18/574eb464ecf24.jpg','','2','2','0');
INSERT INTO `%DB_PREFIX%index_image` VALUES ('12','./public/attachment/201606/02/10/574f9e511f78f.jpg','','7','6','0');
DROP TABLE IF EXISTS `%DB_PREFIX%investment_list`;
CREATE TABLE `%DB_PREFIX%investment_list` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%invite`;
CREATE TABLE `%DB_PREFIX%invite` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%licai`;
CREATE TABLE `%DB_PREFIX%licai` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%licai_advance`;
CREATE TABLE `%DB_PREFIX%licai_advance` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%licai_bank`;
CREATE TABLE `%DB_PREFIX%licai_bank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='基金种类：\r\n全部 货币型 股票型 债券型 混合型 理财型 指数型 QDII 其他型';
DROP TABLE IF EXISTS `%DB_PREFIX%licai_dealshow`;
CREATE TABLE `%DB_PREFIX%licai_dealshow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licai_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%licai_fund_brand`;
CREATE TABLE `%DB_PREFIX%licai_fund_brand` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='基金品牌：\r\n全部 嘉实 鹏华 易方达 国泰 南方 建信 招商 工银瑞信 海富通 华商 中邮创业 长盛 东方\r\n';
DROP TABLE IF EXISTS `%DB_PREFIX%licai_fund_type`;
CREATE TABLE `%DB_PREFIX%licai_fund_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='基金种类：\r\n全部 货币型 股票型 债券型 混合型 理财型 指数型 QDII 其他型';
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('1','货币型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('2','股票型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('3','债券型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('4','混合型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('5','理财型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('6','标准','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('7','QDII','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('8','其他型','1','0');
INSERT INTO `%DB_PREFIX%licai_fund_type` VALUES ('9','中欧','1','0');
DROP TABLE IF EXISTS `%DB_PREFIX%licai_history`;
CREATE TABLE `%DB_PREFIX%licai_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `licai_id` varchar(50) NOT NULL COMMENT '编号',
  `history_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '购买金额起',
  `net_value` decimal(10,2) NOT NULL COMMENT '当日净利',
  `rate` decimal(7,4) NOT NULL COMMENT '利率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='基金净值列表';
DROP TABLE IF EXISTS `%DB_PREFIX%licai_holiday`;
CREATE TABLE `%DB_PREFIX%licai_holiday` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL COMMENT '年',
  `holiday` date NOT NULL COMMENT '假日',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%licai_interest`;
CREATE TABLE `%DB_PREFIX%licai_interest` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%licai_order`;
CREATE TABLE `%DB_PREFIX%licai_order` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%licai_recommend`;
CREATE TABLE `%DB_PREFIX%licai_recommend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `licai_id` varchar(50) NOT NULL COMMENT '编号',
  `name` varchar(255) NOT NULL COMMENT '产品名称',
  `img` varchar(255) NOT NULL COMMENT '项目图片',
  `brief` varchar(255) DEFAULT NULL COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1:有效;0无效',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='个性推荐';
DROP TABLE IF EXISTS `%DB_PREFIX%licai_redempte`;
CREATE TABLE `%DB_PREFIX%licai_redempte` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%link`;
CREATE TABLE `%DB_PREFIX%link` (
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
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8 COMMENT='//链接';
INSERT INTO `%DB_PREFIX%link` VALUES ('128','猫力中国','14','http://molly.net.cn','0','1','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('129','VK维客','14','http://vitakung.com','0','2','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('130','KK大公馆','14','http://kungkuan.com','0','3','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('131','宫网','14','http://gong.news','0','4','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('132','猫力网','14','http://mollygogo.com','0','5','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('133','qodo取道文化','14','http://qodoculture.com','0','6','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('134','VITAGONG宫伟','14','http://vitagong.com','0','7','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('135','MW猫力珠宝','14','http://mollywang.com','0','8','','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('136','世界云联','14','http://www.shijieyinlian.com/','1','2','','世界云联','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('137','36氪股权众筹','19','https://z.36kr.com/projects','0','10','./public/attachment/201602/29/02/56d3381803d09.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('138','软银中国','21','http://www.sbcvc.com/','0','11','./public/attachment/201602/29/02/56d33860b71b0.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('139','纪源资本','21','http://www.ggvc.com/','0','12','./public/attachment/201602/29/02/56d3388e94c5f.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('140','红杉资本','21','http://www.sequoiacap.cn/zh/','0','13','./public/attachment/201602/29/02/56d338b12625a.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('141','经纬中国','21','http://www.matrixpartners.com.cn/','0','14','./public/attachment/201602/29/02/56d338d8d9701.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('142','IDG','21','http://www.idgvc.com/','0','15','./public/attachment/201602/29/02/56d338f76acfc.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('143','GOBI','21','http://www.gobivc.com/','0','16','./public/attachment/201602/29/02/56d3391b03d09.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('144','真格基金','21','http://www.zhenfund.com/','0','17','./public/attachment/201602/29/02/56d33958501bd.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('145','京东金融','19','http://z.jd.com/new.html','0','18','./public/attachment/201602/29/02/56d339aa0f424.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('146','天使客','19','https://www.angelclub.com/','0','19','./public/attachment/201602/29/02/56d339fc7a120.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('147','天使街','19','http://www.tianshijie.com.cn/','0','20','./public/attachment/201602/29/02/56d33a4d40d99.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('148','企e融','19','http://www.71fi.com/','0','21','./public/attachment/201602/29/02/56d33aadd59f8.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('149','融e帮','19','http://rongebang.com/','0','22','./public/attachment/201602/29/02/56d33af866ff3.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('150','第五创','19','http://www.d5ct.com/','0','23','./public/attachment/201602/29/02/56d33b2c44aa2.jpg','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('151','众筹客','18','http://www.zhongchouke.com/','0','24','./public/attachment/201602/29/02/56d33bf91312d.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('152','淘宝众筹','18','https://izhongchou.taobao.com/index.htm','0','25','./public/attachment/201602/29/02/56d33c5d31975.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('153','众筹网','18','http://www.zhongchou.com/','0','26','./public/attachment/201602/29/02/56d33cb4b34a7.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('154','汇梦公社','20','http://www.hmzone.com/','0','27','./public/attachment/201602/29/02/56d33d5800000.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('155','咱们众筹','20','http://www.zamazc.com/','0','28','./public/attachment/201602/29/02/56d33da3b71b0.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('156','大家投','20','http://www.dajiatou.com/','0','29','./public/attachment/201602/29/02/56d33e01f0537.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('157','大家筹','20','http://www.dajiachou.com/','0','30','./public/attachment/201602/29/02/56d33e30bebc2.jpg','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('158','人人合伙','20','http://www.renrenhehuo.cn/index.ac','0','31','./public/attachment/201602/29/02/56d33e736acfc.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('159','360淘金','20','https://t.360.cn/','0','32','./public/attachment/201602/29/02/56d33ed05b8d8.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('160','蚂蚁天使','20','https://www.mayiangel.com/index.htm','0','33','./public/attachment/201602/29/02/56d33f1440d99.png','','0','1');
INSERT INTO `%DB_PREFIX%link` VALUES ('161','亿万家','14','http://www.yiwanjia.com','1','10','','亿万家商城','0','1');
DROP TABLE IF EXISTS `%DB_PREFIX%link_group`;
CREATE TABLE `%DB_PREFIX%link_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '友情链接分组名称',
  `sort` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 文字描述 1图片描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='//链接组';
INSERT INTO `%DB_PREFIX%link_group` VALUES ('14','友情链接','1','1','0');
INSERT INTO `%DB_PREFIX%link_group` VALUES ('18','产品众筹','2','0','1');
INSERT INTO `%DB_PREFIX%link_group` VALUES ('19','股权众筹','3','0','1');
INSERT INTO `%DB_PREFIX%link_group` VALUES ('20','其他众筹','4','0','1');
INSERT INTO `%DB_PREFIX%link_group` VALUES ('21','风投在线','5','0','1');
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
) ENGINE=MyISAM AUTO_INCREMENT=3115 DEFAULT CHARSET=utf8 COMMENT='//记录';
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
