-- fanwe SQL Dump Program
-- nginx/1.10.0
-- 
-- DATE : 2016-06-08 17:53:53
-- MYSQL SERVER VERSION : 5.5.48-log
-- PHP VERSION : fpm-fcgi
-- Vol : 4


DROP TABLE IF EXISTS `%DB_PREFIX%user_bank`;
CREATE TABLE `%DB_PREFIX%user_bank` (
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%user_bank` VALUES ('7','24','1','中国工商银行','6222 0237 0001 5757 517','张三','','安徽','安庆','','安静减肥','0','','','0','0','0','');
DROP TABLE IF EXISTS `%DB_PREFIX%user_bonus`;
CREATE TABLE `%DB_PREFIX%user_bonus` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%user_bonus_list`;
CREATE TABLE `%DB_PREFIX%user_bonus_list` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%user_carry_config`;
CREATE TABLE `%DB_PREFIX%user_carry_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '简称',
  `min_price` decimal(20,0) NOT NULL COMMENT '最低额度',
  `max_price` decimal(20,0) NOT NULL COMMENT '最高额度',
  `fee` decimal(20,2) NOT NULL COMMENT '费率',
  `fee_type` tinyint(1) NOT NULL COMMENT '费率类型 0 是固定值 1是百分比',
  `vip_id` int(11) NOT NULL COMMENT 'VIP等级     0默认配置  否则就是对应VIP等级设置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%user_carry_config` VALUES ('2','5万以内','10001','50000','20.00','0','0');
INSERT INTO `%DB_PREFIX%user_carry_config` VALUES ('1','1万以内','0','10000','10.00','0','0');
DROP TABLE IF EXISTS `%DB_PREFIX%user_consignee`;
CREATE TABLE `%DB_PREFIX%user_consignee` (
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
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='//收件人';
DROP TABLE IF EXISTS `%DB_PREFIX%user_deal_notify`;
CREATE TABLE `%DB_PREFIX%user_deal_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `deal_id_user_id` (`user_id`,`deal_id`),
  KEY `user_id` (`user_id`),
  KEY `deal_id` (`deal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='//用于发送下单成功的用户与关注用户的项目成功准备队列';
INSERT INTO `%DB_PREFIX%user_deal_notify` VALUES ('20','18','55','1352229388');
INSERT INTO `%DB_PREFIX%user_deal_notify` VALUES ('39','42','68','1465245960');
INSERT INTO `%DB_PREFIX%user_deal_notify` VALUES ('40','43','68','1465246101');
INSERT INTO `%DB_PREFIX%user_deal_notify` VALUES ('43','49','77','1465253540');
DROP TABLE IF EXISTS `%DB_PREFIX%user_level`;
CREATE TABLE `%DB_PREFIX%user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '等级名',
  `level` int(11) DEFAULT NULL COMMENT '等级大小   大->小',
  `point` int(11) NOT NULL COMMENT '所需信用值',
  `icon` varchar(255) NOT NULL COMMENT '等级图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='//用户等级';
DROP TABLE IF EXISTS `%DB_PREFIX%user_log`;
CREATE TABLE `%DB_PREFIX%user_log` (
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
) ENGINE=MyISAM AUTO_INCREMENT=249 DEFAULT CHARSET=utf8 COMMENT='//帐户资金变动日志';
INSERT INTO `%DB_PREFIX%user_log` VALUES ('226','20160603','1464894020','1','8987564212.0000','42','0','0','5000000','650','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('227','XXX酒店购买成功','1465245921','1','-500.0000','42','0','68','0','0','16','101');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('228','XXX酒店购买成功,积分增加500','1465245921','1','0.0000','42','0','0','500','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('229','XXX酒店购买成功,信用值增加500','1465245921','1','0.0000','42','0','0','0','500','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('230','XXX酒店购买成功','1465245960','1','-500.0000','42','0','68','0','0','16','102');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('231','XXX酒店购买成功,积分增加500','1465245960','1','0.0000','42','0','0','500','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('232','XXX酒店购买成功,信用值增加500','1465245960','1','0.0000','42','0','0','0','500','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('233','XXX酒店购买成功','1465250887','1','-600.0000','42','0','68','0','0','16','103');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('234','XXX酒店购买成功,积分增加600','1465250887','1','0.0000','42','0','0','600','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('235','XXX酒店购买成功,信用值增加600','1465250887','1','0.0000','42','0','0','0','600','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('236','XXX房地产购买成功','1465252347','1','-1000000.0000','42','0','69','0','0','16','104');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('237','XXX房地产购买成功,积分增加1000000','1465252347','1','0.0000','42','0','0','1000000','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('238','XXX房地产购买成功,信用值增加1000000','1465252347','1','0.0000','42','0','0','0','1000000','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('239','XXX房地产购买成功','1465252401','1','-8400000.0000','42','0','69','0','0','16','105');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('240','XXX房地产购买成功,积分增加8400000','1465252401','1','0.0000','42','0','0','8400000','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('241','XXX房地产购买成功,信用值增加8400000','1465252401','1','0.0000','42','0','0','0','8400000','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('242','20160607','1465252943','1','6666666666.0000','49','0','0','562000','560450','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('243','保护梁子湖，我们在行动。购买成功','1465253454','1','-30000.0000','49','0','74','0','0','16','106');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('244','保护梁子湖，我们在行动。购买成功,积分增加30000','1465253454','1','0.0000','49','0','0','30000','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('245','保护梁子湖，我们在行动。购买成功,信用值增加30000','1465253454','1','0.0000','49','0','0','0','30000','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('246','XX酒店-五星级为您打造购买成功','1465253540','1','-30000.0000','49','0','77','0','0','16','107');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('247','XX酒店-五星级为您打造购买成功,积分增加30000','1465253540','1','0.0000','49','0','0','30000','0','','');
INSERT INTO `%DB_PREFIX%user_log` VALUES ('248','XX酒店-五星级为您打造购买成功,信用值增加30000','1465253540','1','0.0000','49','0','0','0','30000','','');
DROP TABLE IF EXISTS `%DB_PREFIX%user_message`;
CREATE TABLE `%DB_PREFIX%user_message` (
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
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='// 用户私信';
INSERT INTO `%DB_PREFIX%user_message` VALUES ('47','1352230383','感谢支持','18','19','18','19','fzmatthew','test','fzmatthew','test','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('48','1352230383','感谢支持','19','18','18','19','test','fzmatthew','fzmatthew','test','inbox','0');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('49','1352230403','感谢您的支持!!!','18','17','18','17','fzmatthew','fanwe','fzmatthew','fanwe','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('50','1352230403','感谢您的支持!!!','17','18','18','17','fanwe','fzmatthew','fzmatthew','fanwe','inbox','0');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('51','1352230499','谢谢!!!','17','18','17','18','fanwe','fzmatthew','fanwe','fzmatthew','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('52','1352230499','谢谢!!!','18','17','17','18','fzmatthew','fanwe','fanwe','fzmatthew','inbox','0');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('53','1461276865','小潘潘~_~','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('54','1461276865','小潘潘~_~','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('55','1461276876','小潘潘~_~','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('56','1461276876','小潘潘~_~','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('57','1461276876','小潘潘~_~','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('58','1461276876','小潘潘~_~','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('59','1461276876','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('60','1461276876','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('61','1461276876','小潘潘~_~','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('62','1461276876','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('63','1461276876','小潘潘~_~','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('64','1461276876','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('65','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('66','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('67','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('68','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('69','1461276876','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('70','1461276876','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('71','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('72','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('73','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('74','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('75','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('76','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('77','1461276877','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('78','1461276877','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('79','1461276878','小潘潘','27','24','27','24','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('80','1461276878','小潘潘','24','27','27','24','哈哈哈哈','爆炒大熊猫','爆炒大熊猫','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('81','1461276918','哈哈哈哈','24','27','24','27','哈哈哈哈','爆炒大熊猫','哈哈哈哈','爆炒大熊猫','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('82','1461276918','哈哈哈哈','27','24','24','27','爆炒大熊猫','哈哈哈哈','哈哈哈哈','爆炒大熊猫','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('83','1461277644','寇马克','25','24','25','24','小瑞','哈哈哈哈','小瑞','哈哈哈哈','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('84','1461277644','寇马克','24','25','25','24','哈哈哈哈','小瑞','小瑞','哈哈哈哈','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('85','1461279202','哈哈哈哈','24','25','24','25','哈哈哈哈','小瑞','哈哈哈哈','小瑞','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('86','1461279202','哈哈哈哈','25','24','24','25','小瑞','哈哈哈哈','哈哈哈哈','小瑞','inbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('87','1465251666','jyhgjghj g','42','48','42','48','贾国瑞','liuanchuan','贾国瑞','liuanchuan','outbox','1');
INSERT INTO `%DB_PREFIX%user_message` VALUES ('88','1465251666','jyhgjghj g','48','42','42','48','liuanchuan','贾国瑞','贾国瑞','liuanchuan','inbox','0');
DROP TABLE IF EXISTS `%DB_PREFIX%user_notify`;
CREATE TABLE `%DB_PREFIX%user_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_info` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `url_route` varchar(255) NOT NULL,
  `url_param` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0 默认 1表示站内推送',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='// 公告';
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('69','17','拥有自己的咖啡馆 在 2012-11-07 11:31:10 成功筹到 ¥5,000.00','1352230271','0','deal#show','id=56','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('70','19','拥有自己的咖啡馆 在 2012-11-07 11:31:10 成功筹到 ¥5,000.00','1352230271','0','deal#show','id=56','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('71','17','您支持的项目拥有自己的咖啡馆回报已发放','1352230424','0','account#view_order','id=66','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('72','18','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！ 在 2012-11-07 11:55:04 成功筹到 ¥3,000.00','1352231704','0','deal#show','id=58','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('73','23','XXX酒店 在 2016-04-21 15:45:16 成功筹到 ¥4,500,000.00','1461195917','0','deal#show','id=71','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('74','24','您的投资人申请已经通过','1461267672','0','account#index','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('75','27','您的投资人申请已经通过','1461267941','0','account#index','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('76','25','您的投资人申请已经通过','1461267948','0','account#index','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('77','25','您的投资人申请已经通过','1461267959','0','account#index','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('78','24','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！ 在 2016-04-22 11:47:59 成功筹到 ¥3,000.00','1461268080','0','deal#show','id=58','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('79','25','拯救地球 在 2016-04-22 12:01:13 成功筹到 ¥999,999.00','1461268875','0','deal#show','id=76','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('80','24','XXX酒店 在 2016-04-22 14:17:12 成功筹到 ¥55,555,555,550.00','1461277038','0','deal#show','id=71','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('81','25','哈哈哈哈 在 2016-04-22 14:28:20 成功筹到 ¥1,000,000,000.00','1461277700','0','deal#show','id=77','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('82','25','XXX酒店 在 2016-04-22 14:30:34 成功筹到 ¥120,000.00','1461277838','0','deal#show','id=68','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('83','24','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！ 在 2016-04-22 14:31:35 成功筹到 ¥30,000.00','1461277895','0','deal#show','id=58','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('84','27','流浪猫的家—爱天使公益咖啡馆的重建需要大家的力量！ 在 2016-04-22 14:31:35 成功筹到 ¥30,000.00','1461277895','0','deal#show','id=58','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('85','24','筹建康平羽毛球馆，为羽毛球爱好者建一个温暖的家！ 在 2016-04-22 14:40:23 成功筹到 ¥30,000.00','1461278423','0','deal#show','id=60','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('86','17','','1461278481','0','','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('87','17','','1461278625','0','','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('88','17','','1461279693','0','','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('89','17','','1461279733','0','','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('90','25','测试 在 2016-04-22 15:03:33 成功筹到 ¥10,000.00','1461279818','0','deal#show','id=78','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('91','24','测试 在 2016-04-22 15:03:33 成功筹到 ¥10,000.00','1461279818','0','deal#show','id=78','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('92','27','测试 在 2016-04-22 15:03:33 成功筹到 ¥10,000.00','1461279818','0','deal#show','id=78','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('93','24','您邀请会员hhh123注册成功,奖励20积分','1461279969','0','account#score','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('94','24','您邀请会员hhh123注册成功,信用值增加20','1461279969','0','account#point','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('95','27','您邀请会员嘿嘿嘿嘿注册成功,奖励20积分','1461280198','0','account#score','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('96','27','您邀请会员嘿嘿嘿嘿注册成功,信用值增加20','1461280198','0','account#point','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('97','25','恭喜，您支持的测试的抽奖号：5410000003已被抽为幸运号','1461280267','0','account#view_order','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('98','25','恭喜，您支持的测试的抽奖号：5410000011已被抽为幸运号','1461280267','0','account#view_order','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('99','25','您支持的项目测试回报已发放','1461280356','0','account#view_order','id=100','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('100','25','您支持的项目测试回报已发放','1461280380','1','account#view_order','id=96','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('101','17','','1461430207','0','','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('102','42','XXX房地产 在 2016-06-07 14:33:21 成功筹到 ¥8,400,000.00','1465252402','1','deal#show','id=69','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('103','49','保护梁子湖，我们在行动。 在 2016-06-07 14:50:54 成功筹到 ¥30,000.00','1465253455','0','deal#show','id=74','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('104','49','您的投资人申请未通过,点击链接重新申请','1465318854','0','settings#security','','0');
INSERT INTO `%DB_PREFIX%user_notify` VALUES ('105','49','您的投资人申请已经通过','1465319097','0','account#index','','0');
DROP TABLE IF EXISTS `%DB_PREFIX%user_refund`;
CREATE TABLE `%DB_PREFIX%user_refund` (
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='// 用户退款';
DROP TABLE IF EXISTS `%DB_PREFIX%user_weibo`;
CREATE TABLE `%DB_PREFIX%user_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `weibo_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COMMENT='//微博';
DROP TABLE IF EXISTS `%DB_PREFIX%vote`;
CREATE TABLE `%DB_PREFIX%vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '调查的项目名称',
  `begin_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `is_effect` tinyint(1) NOT NULL COMMENT '有效性',
  `sort` int(11) NOT NULL,
  `description` text NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%vote_ask`;
CREATE TABLE `%DB_PREFIX%vote_ask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '投票项名称',
  `type` tinyint(1) NOT NULL COMMENT '投票类型，单选多选/自定义可叠加 1:单选 2:多选 3:自定义',
  `sort` int(11) NOT NULL COMMENT ' 排序 大到小',
  `vote_id` int(11) NOT NULL COMMENT '调查ID',
  `val_scope` text NOT NULL COMMENT '预选范围 逗号分开',
  `is_fill` tinyint(1) NOT NULL COMMENT '1表示该项必填，0表示可以不填',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%vote_list`;
CREATE TABLE `%DB_PREFIX%vote_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '调查项ID',
  `value` text NOT NULL COMMENT '问题答案',
  `user_id` int(11) DEFAULT NULL COMMENT '参与调查的用户id',
  `mobile` varchar(11) DEFAULT NULL COMMENT '参与调查的用户手机号码',
  `email` varchar(64) DEFAULT NULL COMMENT '参与调查的用户邮箱',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%vote_result`;
CREATE TABLE `%DB_PREFIX%vote_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '投票的名称',
  `count` int(11) NOT NULL COMMENT '计数',
  `vote_id` int(11) NOT NULL COMMENT '调查项ID',
  `vote_ask_id` int(11) NOT NULL COMMENT '投票项（问题）ID',
  `type` int(1) NOT NULL COMMENT '0:固定选项，1:用户自定义输入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_account`;
CREATE TABLE `%DB_PREFIX%weixin_account` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_api_get_record`;
CREATE TABLE `%DB_PREFIX%weixin_api_get_record` (
  `openid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`openid`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `idx_0` (`account_id`,`create_time`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='请求的用户记录';
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_conf`;
CREATE TABLE `%DB_PREFIX%weixin_conf` (
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
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('1','第三方平台appid','platform_appid','appid','0','0','','0','1','1','1');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('2','第三方平台token','platform_token','token','0','0','','0','1','1','2');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('3','第三方平台symmetric_key','platform_encodingAesKey','symmetric_key','0','0','','0','1','1','3');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('4','是否开启第三方平台','platform_status','0','0','4','0,1','0','1','1','4');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('5','第三方平台AppSecret','platform_appsecret','0','0','0','','0','1','1','1');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('6','component_verify_ticket','platform_component_verify_ticket','0','0','0','','0','1','0','6');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('7','第三方平台access_token','platform_component_access_token','0','0','0','','0','1','0','7');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('8','第三方平台预授权码','platform_pre_auth_code','0','0','0','','0','1','0','8');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('9','第三方平台access_token有效期','platform_component_access_token_expire','0','0','0','','0','1','0','9');
INSERT INTO `%DB_PREFIX%weixin_conf` VALUES ('10','第三方平台预授权码有效期','platform_pre_auth_code_expire','0','0','0','','0','1','0','10');
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_group`;
CREATE TABLE `%DB_PREFIX%weixin_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupid` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL DEFAULT '',
  `account_id` varchar(30) NOT NULL DEFAULT '',
  `fanscount` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`,`account_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_msg_list`;
CREATE TABLE `%DB_PREFIX%weixin_msg_list` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_nav`;
CREATE TABLE `%DB_PREFIX%weixin_nav` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_reply`;
CREATE TABLE `%DB_PREFIX%weixin_reply` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_reply_relate`;
CREATE TABLE `%DB_PREFIX%weixin_reply_relate` (
  `main_reply_id` int(11) DEFAULT '0' COMMENT '主回复ID',
  `relate_reply_id` int(11) DEFAULT '0' COMMENT '关联的多图文用的子回复ID',
  `sort` tinyint(1) DEFAULT '0',
  KEY `main_reply_id` (`main_reply_id`),
  KEY `relate_reply_id` (`relate_reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='多图文回复的关联配置';
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_send`;
CREATE TABLE `%DB_PREFIX%weixin_send` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_send_relate`;
CREATE TABLE `%DB_PREFIX%weixin_send_relate` (
  `relate_id` int(11) NOT NULL,
  `send_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_tmpl`;
CREATE TABLE `%DB_PREFIX%weixin_tmpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '' COMMENT '菜单名称',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序 大->小',
  `account_id` int(11) DEFAULT '0' COMMENT '所属的商家ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信模板';
DROP TABLE IF EXISTS `%DB_PREFIX%weixin_user`;
CREATE TABLE `%DB_PREFIX%weixin_user` (
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_bind_bank_card`;
CREATE TABLE `%DB_PREFIX%yeepay_bind_bank_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_cp_transaction`;
CREATE TABLE `%DB_PREFIX%yeepay_cp_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformNo` varchar(20) NOT NULL,
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
  `transfer_id` int(11) NOT NULL DEFAULT '0' COMMENT '债权转让id %DB_PREFIX%deal_load_transfer.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_cp_transaction_detail`;
CREATE TABLE `%DB_PREFIX%yeepay_cp_transaction_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '%DB_PREFIX%yeepay_repayment.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_enterprise_register`;
CREATE TABLE `%DB_PREFIX%yeepay_enterprise_register` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_log`;
CREATE TABLE `%DB_PREFIX%yeepay_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `strxml` text,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71606 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_recharge`;
CREATE TABLE `%DB_PREFIX%yeepay_recharge` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_register`;
CREATE TABLE `%DB_PREFIX%yeepay_register` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
DROP TABLE IF EXISTS `%DB_PREFIX%yeepay_withdraw`;
CREATE TABLE `%DB_PREFIX%yeepay_withdraw` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `requestNo` int(10) NOT NULL DEFAULT '0' COMMENT 'yeepay_log.id',
  `platformUserNo` int(11) NOT NULL DEFAULT '0' COMMENT '%DB_PREFIX%user.id',
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
