/*
Navicat MySQL Data Transfer

Source Server         : localhost_mysql
Source Server Version : 50612
Source Host           : 127.0.0.1:3306
Source Database       : fruit

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2016-04-23 21:05:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zz_account
-- ----------------------------
DROP TABLE IF EXISTS `zz_account`;
CREATE TABLE `zz_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `usemoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `nousemoney` decimal(50,2) NOT NULL,
  `paymoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '已使用金额',
  `recharemoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `total` decimal(50,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='会员账户表';

-- ----------------------------
-- Records of zz_account
-- ----------------------------

-- ----------------------------
-- Table structure for zz_account_log
-- ----------------------------
DROP TABLE IF EXISTS `zz_account_log`;
CREATE TABLE `zz_account_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(5) DEFAULT NULL,
  `tuid` int(5) DEFAULT '0',
  `total` decimal(50,2) DEFAULT '0.00',
  `usemoney` decimal(50,2) DEFAULT '0.00',
  `nousemoney` decimal(50,2) DEFAULT NULL,
  `money` decimal(50,2) DEFAULT '0.00',
  `type` int(5) DEFAULT NULL,
  `addtime` int(15) DEFAULT NULL,
  `addip` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '交易状态 1成功 0失败',
  `dcflag` tinyint(1) DEFAULT '1' COMMENT '1收入 2支出',
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='资金记录表';

-- ----------------------------
-- Records of zz_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for zz_activity
-- ----------------------------
DROP TABLE IF EXISTS `zz_activity`;
CREATE TABLE `zz_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL COMMENT '栏目ID',
  `storeid` int(11) DEFAULT NULL COMMENT '关联商户id',
  `pid` int(11) DEFAULT NULL COMMENT '关联产品id',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '缩略图',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '简介',
  `content` text COLLATE utf8_bin COMMENT '活动内容',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核 1审核 0未审核',
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '发布人',
  `inputtime` int(11) DEFAULT NULL COMMENT '发布时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='活动优惠表';

-- ----------------------------
-- Records of zz_activity
-- ----------------------------

-- ----------------------------
-- Table structure for zz_ad
-- ----------------------------
DROP TABLE IF EXISTS `zz_ad`;
CREATE TABLE `zz_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL COMMENT '类型ID',
  `title` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `url` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '链接地址',
  `storeid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `image` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'LOGO',
  `content` text COLLATE utf8_bin,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述',
  `username` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '用户名',
  `inputtime` int(15) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(15) DEFAULT NULL COMMENT '更新时间',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核 1审核 2未审核',
  `type` tinyint(4) DEFAULT NULL COMMENT '1链接  2商品  3图文',
  `isadmin` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='广告表';

-- ----------------------------
-- Records of zz_ad
-- ----------------------------

-- ----------------------------
-- Table structure for zz_address
-- ----------------------------
DROP TABLE IF EXISTS `zz_address`;
CREATE TABLE `zz_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `area` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '地区',
  `address` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '详细地址',
  `code` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '邮编',
  `name` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '收货人',
  `remark` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '备注',
  `tel` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '电话',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '地址类型  1公司 2家 3其他',
  `lng` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '经度',
  `lat` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '纬度',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是默认地址 （0否 1是）',
  `inputtime` int(11) NOT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='地址管理表';

-- ----------------------------
-- Records of zz_address
-- ----------------------------

-- ----------------------------
-- Table structure for zz_adminpanel
-- ----------------------------
DROP TABLE IF EXISTS `zz_adminpanel`;
CREATE TABLE `zz_adminpanel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menuid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `datetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='常用菜单表';

-- ----------------------------
-- Records of zz_adminpanel
-- ----------------------------
INSERT INTO `zz_adminpanel` VALUES ('23', '180', '1', '通知列表', '/index.php/Admin/Message/index/menuid/180.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('22', '197', '1', '用户加入企业申请管理', '/index.php/Admin/Apply/joincompany/menuid/197.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('21', '175', '1', '连锁店申请管理', '/index.php/Admin/Apply/shop/menuid/175.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('20', '174', '1', '企业申请管理', '/index.php/Admin/Apply/company/menuid/174.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('19', '267', '1', '添加企业', '/index.php/Admin/Company/add/menuid/267.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('18', '266', '1', '企业列表', '/index.php/Admin/Company/index/menuid/266.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('17', '270', '1', '企业用户列表', '/index.php/Admin/Member/company/menuid/270.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('16', '94', '1', '添加会员', '/index.php/Admin/Member/add/menuid/94.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('15', '105', '1', '会员列表', '/index.php/Admin/Member/index/menuid/105.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('24', '188', '1', '积分列表', '/index.php/Admin/Integral/index/menuid/188.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('25', '21', '1', '后台操作日志', '/index.php/Admin/Logs/index/menuid/21.html', '1461412448');
INSERT INTO `zz_adminpanel` VALUES ('26', '22', '1', '后台登录日志', '/index.php/Admin/Logs/login/menuid/22.html', '1461412448');

-- ----------------------------
-- Table structure for zz_adtype
-- ----------------------------
DROP TABLE IF EXISTS `zz_adtype`;
CREATE TABLE `zz_adtype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '类型ID',
  `parentid` int(10) DEFAULT '0' COMMENT '父ID',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='广告类型表';

-- ----------------------------
-- Records of zz_adtype
-- ----------------------------

-- ----------------------------
-- Table structure for zz_area
-- ----------------------------
DROP TABLE IF EXISTS `zz_area`;
CREATE TABLE `zz_area` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `arrparentid` varchar(200) DEFAULT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 0禁用',
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`,`listorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3368 DEFAULT CHARSET=utf8 COMMENT='地区表';

-- ----------------------------
-- Records of zz_area
-- ----------------------------
INSERT INTO `zz_area` VALUES ('2', '北京市', '0', ',2,', '0', '0');
INSERT INTO `zz_area` VALUES ('3', '上海市', '0', ',3,', '1', '1');
INSERT INTO `zz_area` VALUES ('4', '天津市', '0', ',4,', '0', '0');
INSERT INTO `zz_area` VALUES ('5', '重庆市', '0', ',5,', '0', '0');
INSERT INTO `zz_area` VALUES ('6', '河北省', '0', ',6,', '0', '0');
INSERT INTO `zz_area` VALUES ('7', '山西省', '0', ',7,', '0', '0');
INSERT INTO `zz_area` VALUES ('8', '内蒙古', '0', ',8,', '0', '0');
INSERT INTO `zz_area` VALUES ('9', '辽宁省', '0', ',9,', '0', '0');
INSERT INTO `zz_area` VALUES ('10', '吉林省', '0', ',10,', '0', '0');
INSERT INTO `zz_area` VALUES ('11', '黑龙江省', '0', ',11,', '0', '0');
INSERT INTO `zz_area` VALUES ('12', '江苏省', '0', ',12,', '0', '0');
INSERT INTO `zz_area` VALUES ('13', '浙江省', '0', ',13,', '0', '0');
INSERT INTO `zz_area` VALUES ('14', '安徽省', '0', ',14,', '0', '0');
INSERT INTO `zz_area` VALUES ('15', '福建省', '0', ',15,', '0', '1');
INSERT INTO `zz_area` VALUES ('16', '江西省', '0', ',16,', '0', '0');
INSERT INTO `zz_area` VALUES ('17', '山东省', '0', ',17,', '0', '0');
INSERT INTO `zz_area` VALUES ('18', '河南省', '0', ',18,', '0', '0');
INSERT INTO `zz_area` VALUES ('19', '湖北省', '0', ',19,', '0', '0');
INSERT INTO `zz_area` VALUES ('20', '湖南省', '0', ',20,', '0', '0');
INSERT INTO `zz_area` VALUES ('21', '广东省', '0', ',21,', '0', '0');
INSERT INTO `zz_area` VALUES ('22', '广西', '0', ',22,', '0', '0');
INSERT INTO `zz_area` VALUES ('23', '海南省', '0', ',23,', '0', '0');
INSERT INTO `zz_area` VALUES ('24', '四川省', '0', ',24,', '0', '0');
INSERT INTO `zz_area` VALUES ('25', '贵州省', '0', ',25,', '0', '0');
INSERT INTO `zz_area` VALUES ('26', '云南省', '0', ',26,', '0', '0');
INSERT INTO `zz_area` VALUES ('27', '西藏', '0', ',27,', '0', '0');
INSERT INTO `zz_area` VALUES ('28', '陕西省', '0', ',28,', '0', '0');
INSERT INTO `zz_area` VALUES ('29', '甘肃省', '0', ',29,', '0', '0');
INSERT INTO `zz_area` VALUES ('30', '青海省', '0', ',30,', '0', '0');
INSERT INTO `zz_area` VALUES ('31', '宁夏', '0', ',31,', '0', '0');
INSERT INTO `zz_area` VALUES ('32', '新疆', '0', ',32,', '0', '0');
INSERT INTO `zz_area` VALUES ('33', '台湾省', '0', ',33,', '0', '0');
INSERT INTO `zz_area` VALUES ('34', '香港', '0', ',34,', '0', '0');
INSERT INTO `zz_area` VALUES ('35', '澳门', '0', ',35,', '0', '0');
INSERT INTO `zz_area` VALUES ('36', '东城区', '2', '2,36,', '0', '1');
INSERT INTO `zz_area` VALUES ('37', '西城区', '2', '2,37,', '0', '1');
INSERT INTO `zz_area` VALUES ('38', '崇文区', '2', '2,38,', '0', '1');
INSERT INTO `zz_area` VALUES ('39', '宣武区', '2', '2,39,', '0', '1');
INSERT INTO `zz_area` VALUES ('40', '朝阳区', '2', '2,40,', '0', '1');
INSERT INTO `zz_area` VALUES ('41', '石景山区', '2', '2,41,', '0', '1');
INSERT INTO `zz_area` VALUES ('42', '海淀区', '2', '2,42,', '0', '1');
INSERT INTO `zz_area` VALUES ('43', '门头沟区', '2', '2,43,', '0', '1');
INSERT INTO `zz_area` VALUES ('44', '房山区', '2', '2,44,', '0', '1');
INSERT INTO `zz_area` VALUES ('45', '通州区', '2', '2,45,', '0', '1');
INSERT INTO `zz_area` VALUES ('46', '顺义区', '2', '2,46,', '0', '1');
INSERT INTO `zz_area` VALUES ('47', '昌平区', '2', '2,47,', '0', '1');
INSERT INTO `zz_area` VALUES ('48', '大兴区', '2', '2,48,', '0', '1');
INSERT INTO `zz_area` VALUES ('49', '怀柔区', '2', '2,49,', '0', '1');
INSERT INTO `zz_area` VALUES ('50', '平谷区', '2', '2,50,', '0', '1');
INSERT INTO `zz_area` VALUES ('51', '密云县', '2', '2,51,', '0', '1');
INSERT INTO `zz_area` VALUES ('52', '延庆县', '2', '2,52,', '0', '1');
INSERT INTO `zz_area` VALUES ('53', '黄浦区', '3', '3,53,', '0', '1');
INSERT INTO `zz_area` VALUES ('54', '卢湾区', '3', '3,54,', '0', '1');
INSERT INTO `zz_area` VALUES ('55', '徐汇区', '3', '3,55,', '0', '1');
INSERT INTO `zz_area` VALUES ('56', '长宁区', '3', '3,56,', '0', '1');
INSERT INTO `zz_area` VALUES ('57', '静安区', '3', '3,57,', '0', '1');
INSERT INTO `zz_area` VALUES ('58', '普陀区', '3', '3,58,', '0', '1');
INSERT INTO `zz_area` VALUES ('59', '闸北区', '3', '3,59,', '0', '1');
INSERT INTO `zz_area` VALUES ('60', '虹口区', '3', '3,60,', '0', '1');
INSERT INTO `zz_area` VALUES ('61', '杨浦区', '3', '3,61,', '0', '1');
INSERT INTO `zz_area` VALUES ('62', '闵行区', '3', '3,62,', '0', '1');
INSERT INTO `zz_area` VALUES ('63', '宝山区', '3', '3,63,', '0', '1');
INSERT INTO `zz_area` VALUES ('64', '嘉定区', '3', '3,64,', '0', '1');
INSERT INTO `zz_area` VALUES ('65', '浦东新区', '3', '3,65,', '0', '1');
INSERT INTO `zz_area` VALUES ('66', '金山区', '3', '3,66,', '0', '1');
INSERT INTO `zz_area` VALUES ('67', '松江区', '3', '3,67,', '0', '1');
INSERT INTO `zz_area` VALUES ('68', '青浦区', '3', '3,68,', '0', '1');
INSERT INTO `zz_area` VALUES ('69', '南汇区', '3', '3,69,', '0', '1');
INSERT INTO `zz_area` VALUES ('70', '奉贤区', '3', '3,70,', '0', '1');
INSERT INTO `zz_area` VALUES ('71', '崇明县', '3', '3,71,', '0', '1');
INSERT INTO `zz_area` VALUES ('72', '和平区', '4', '4,72,', '0', '1');
INSERT INTO `zz_area` VALUES ('73', '河东区', '4', '4,73,', '0', '1');
INSERT INTO `zz_area` VALUES ('74', '河西区', '4', '4,74,', '0', '1');
INSERT INTO `zz_area` VALUES ('75', '南开区', '4', '4,75,', '0', '1');
INSERT INTO `zz_area` VALUES ('76', '河北区', '4', '4,76,', '0', '1');
INSERT INTO `zz_area` VALUES ('77', '红桥区', '4', '4,77,', '0', '1');
INSERT INTO `zz_area` VALUES ('78', '塘沽区', '4', '4,78,', '0', '1');
INSERT INTO `zz_area` VALUES ('79', '汉沽区', '4', '4,79,', '0', '1');
INSERT INTO `zz_area` VALUES ('80', '大港区', '4', '4,80,', '0', '1');
INSERT INTO `zz_area` VALUES ('81', '东丽区', '4', '4,81,', '0', '1');
INSERT INTO `zz_area` VALUES ('82', '西青区', '4', '4,82,', '0', '1');
INSERT INTO `zz_area` VALUES ('83', '津南区', '4', '4,83,', '0', '1');
INSERT INTO `zz_area` VALUES ('84', '北辰区', '4', '4,84,', '0', '1');
INSERT INTO `zz_area` VALUES ('85', '武清区', '4', '4,85,', '0', '1');
INSERT INTO `zz_area` VALUES ('86', '宝坻区', '4', '4,86,', '0', '1');
INSERT INTO `zz_area` VALUES ('87', '宁河县', '4', '4,87,', '0', '1');
INSERT INTO `zz_area` VALUES ('88', '静海县', '4', '4,88,', '0', '1');
INSERT INTO `zz_area` VALUES ('89', '蓟县', '4', '4,89,', '0', '1');
INSERT INTO `zz_area` VALUES ('90', '万州区', '5', '5,90,', '0', '1');
INSERT INTO `zz_area` VALUES ('91', '涪陵区', '5', '5,91,', '0', '1');
INSERT INTO `zz_area` VALUES ('92', '渝中区', '5', '5,92,', '0', '1');
INSERT INTO `zz_area` VALUES ('93', '大渡口区', '5', '5,93,', '0', '1');
INSERT INTO `zz_area` VALUES ('94', '江北区', '5', '5,94,', '0', '1');
INSERT INTO `zz_area` VALUES ('95', '沙坪坝区', '5', '5,95,', '0', '1');
INSERT INTO `zz_area` VALUES ('96', '九龙坡区', '5', '5,96,', '0', '1');
INSERT INTO `zz_area` VALUES ('97', '南岸区', '5', '5,97,', '0', '1');
INSERT INTO `zz_area` VALUES ('98', '北碚区', '5', '5,98,', '0', '1');
INSERT INTO `zz_area` VALUES ('99', '万盛区', '5', '5,99,', '0', '1');
INSERT INTO `zz_area` VALUES ('100', '双桥区', '5', '5,100,', '0', '1');
INSERT INTO `zz_area` VALUES ('101', '渝北区', '5', '5,101,', '0', '1');
INSERT INTO `zz_area` VALUES ('102', '巴南区', '5', '5,102,', '0', '1');
INSERT INTO `zz_area` VALUES ('103', '黔江区', '5', '5,103,', '0', '1');
INSERT INTO `zz_area` VALUES ('104', '长寿区', '5', '5,104,', '0', '1');
INSERT INTO `zz_area` VALUES ('105', '綦江县', '5', '5,105,', '0', '1');
INSERT INTO `zz_area` VALUES ('106', '潼南县', '5', '5,106,', '0', '1');
INSERT INTO `zz_area` VALUES ('107', '铜梁县', '5', '5,107,', '0', '1');
INSERT INTO `zz_area` VALUES ('108', '大足县', '5', '5,108,', '0', '1');
INSERT INTO `zz_area` VALUES ('109', '荣昌县', '5', '5,109,', '0', '1');
INSERT INTO `zz_area` VALUES ('110', '璧山县', '5', '5,110,', '0', '1');
INSERT INTO `zz_area` VALUES ('111', '梁平县', '5', '5,111,', '0', '1');
INSERT INTO `zz_area` VALUES ('112', '城口县', '5', '5,112,', '0', '1');
INSERT INTO `zz_area` VALUES ('113', '丰都县', '5', '5,113,', '0', '1');
INSERT INTO `zz_area` VALUES ('114', '垫江县', '5', '5,114,', '0', '1');
INSERT INTO `zz_area` VALUES ('115', '武隆县', '5', '5,115,', '0', '1');
INSERT INTO `zz_area` VALUES ('116', '忠县', '5', '5,116,', '0', '1');
INSERT INTO `zz_area` VALUES ('117', '开县', '5', '5,117,', '0', '1');
INSERT INTO `zz_area` VALUES ('118', '云阳县', '5', '5,118,', '0', '1');
INSERT INTO `zz_area` VALUES ('119', '奉节县', '5', '5,119,', '0', '1');
INSERT INTO `zz_area` VALUES ('120', '巫山县', '5', '5,120,', '0', '1');
INSERT INTO `zz_area` VALUES ('121', '巫溪县', '5', '5,121,', '0', '1');
INSERT INTO `zz_area` VALUES ('122', '石柱县', '5', '5,122,', '0', '1');
INSERT INTO `zz_area` VALUES ('123', '秀山县', '5', '5,123,', '0', '1');
INSERT INTO `zz_area` VALUES ('124', '酉阳县', '5', '5,124,', '0', '1');
INSERT INTO `zz_area` VALUES ('125', '彭水县', '5', '5,125,', '0', '1');
INSERT INTO `zz_area` VALUES ('126', '江津区', '5', '5,126,', '0', '1');
INSERT INTO `zz_area` VALUES ('127', '合川区', '5', '5,127,', '0', '1');
INSERT INTO `zz_area` VALUES ('128', '永川区', '5', '5,128,', '0', '1');
INSERT INTO `zz_area` VALUES ('129', '南川区', '5', '5,129,', '0', '1');
INSERT INTO `zz_area` VALUES ('130', '石家庄市', '130', ',130,', '0', '0');
INSERT INTO `zz_area` VALUES ('131', '唐山市', '6', '6,131,', '0', '1');
INSERT INTO `zz_area` VALUES ('132', '秦皇岛市', '6', '6,132,', '0', '1');
INSERT INTO `zz_area` VALUES ('133', '邯郸市', '6', '6,133,', '0', '1');
INSERT INTO `zz_area` VALUES ('134', '邢台市', '6', '6,134,', '0', '1');
INSERT INTO `zz_area` VALUES ('135', '保定市', '6', '6,135,', '0', '1');
INSERT INTO `zz_area` VALUES ('136', '张家口市', '6', '6,136,', '0', '1');
INSERT INTO `zz_area` VALUES ('137', '承德市', '6', '6,137,', '0', '1');
INSERT INTO `zz_area` VALUES ('138', '沧州市', '6', '6,138,', '0', '1');
INSERT INTO `zz_area` VALUES ('139', '廊坊市', '6', '6,139,', '0', '1');
INSERT INTO `zz_area` VALUES ('140', '衡水市', '6', '6,140,', '0', '1');
INSERT INTO `zz_area` VALUES ('141', '太原市', '7', '7,141,', '0', '1');
INSERT INTO `zz_area` VALUES ('142', '大同市', '7', '7,142,', '0', '1');
INSERT INTO `zz_area` VALUES ('143', '阳泉市', '7', '7,143,', '0', '1');
INSERT INTO `zz_area` VALUES ('144', '长治市', '7', '7,144,', '0', '1');
INSERT INTO `zz_area` VALUES ('145', '晋城市', '7', '7,145,', '0', '1');
INSERT INTO `zz_area` VALUES ('146', '朔州市', '7', '7,146,', '0', '1');
INSERT INTO `zz_area` VALUES ('147', '晋中市', '7', '7,147,', '0', '1');
INSERT INTO `zz_area` VALUES ('148', '运城市', '7', '7,148,', '0', '1');
INSERT INTO `zz_area` VALUES ('149', '忻州市', '7', '7,149,', '0', '1');
INSERT INTO `zz_area` VALUES ('150', '临汾市', '7', '7,150,', '0', '1');
INSERT INTO `zz_area` VALUES ('151', '吕梁市', '7', '7,151,', '0', '1');
INSERT INTO `zz_area` VALUES ('152', '呼和浩特市', '8', '8,152,', '0', '1');
INSERT INTO `zz_area` VALUES ('153', '包头市', '8', '8,153,', '0', '1');
INSERT INTO `zz_area` VALUES ('154', '乌海市', '8', '8,154,', '0', '1');
INSERT INTO `zz_area` VALUES ('155', '赤峰市', '8', '8,155,', '0', '1');
INSERT INTO `zz_area` VALUES ('156', '通辽市', '8', '8,156,', '0', '1');
INSERT INTO `zz_area` VALUES ('157', '鄂尔多斯市', '8', '8,157,', '0', '1');
INSERT INTO `zz_area` VALUES ('158', '呼伦贝尔市', '8', '8,158,', '0', '1');
INSERT INTO `zz_area` VALUES ('159', '巴彦淖尔市', '8', '8,159,', '0', '1');
INSERT INTO `zz_area` VALUES ('160', '乌兰察布市', '8', '8,160,', '0', '1');
INSERT INTO `zz_area` VALUES ('161', '兴安盟', '8', '8,161,', '0', '1');
INSERT INTO `zz_area` VALUES ('162', '锡林郭勒盟', '8', '8,162,', '0', '1');
INSERT INTO `zz_area` VALUES ('163', '阿拉善盟', '8', '8,163,', '0', '1');
INSERT INTO `zz_area` VALUES ('164', '沈阳市', '9', '9,164,', '0', '1');
INSERT INTO `zz_area` VALUES ('165', '大连市', '9', '9,165,', '0', '1');
INSERT INTO `zz_area` VALUES ('166', '鞍山市', '9', '9,166,', '0', '1');
INSERT INTO `zz_area` VALUES ('167', '抚顺市', '9', '9,167,', '0', '1');
INSERT INTO `zz_area` VALUES ('168', '本溪市', '9', '9,168,', '0', '1');
INSERT INTO `zz_area` VALUES ('169', '丹东市', '9', '9,169,', '0', '1');
INSERT INTO `zz_area` VALUES ('170', '锦州市', '9', '9,170,', '0', '1');
INSERT INTO `zz_area` VALUES ('171', '营口市', '9', '9,171,', '0', '1');
INSERT INTO `zz_area` VALUES ('172', '阜新市', '9', '9,172,', '0', '1');
INSERT INTO `zz_area` VALUES ('173', '辽阳市', '9', '9,173,', '0', '1');
INSERT INTO `zz_area` VALUES ('174', '盘锦市', '9', '9,174,', '0', '1');
INSERT INTO `zz_area` VALUES ('175', '铁岭市', '9', '9,175,', '0', '1');
INSERT INTO `zz_area` VALUES ('176', '朝阳市', '9', '9,176,', '0', '1');
INSERT INTO `zz_area` VALUES ('177', '葫芦岛市', '9', '9,177,', '0', '1');
INSERT INTO `zz_area` VALUES ('178', '长春市', '10', '10,178,', '0', '1');
INSERT INTO `zz_area` VALUES ('179', '吉林市', '10', '10,179,', '0', '1');
INSERT INTO `zz_area` VALUES ('180', '四平市', '10', '10,180,', '0', '1');
INSERT INTO `zz_area` VALUES ('181', '辽源市', '10', '10,181,', '0', '1');
INSERT INTO `zz_area` VALUES ('182', '通化市', '10', '10,182,', '0', '1');
INSERT INTO `zz_area` VALUES ('183', '白山市', '10', '10,183,', '0', '1');
INSERT INTO `zz_area` VALUES ('184', '松原市', '10', '10,184,', '0', '1');
INSERT INTO `zz_area` VALUES ('185', '白城市', '10', '10,185,', '0', '1');
INSERT INTO `zz_area` VALUES ('186', '延边', '10', '10,186,', '0', '1');
INSERT INTO `zz_area` VALUES ('187', '哈尔滨市', '11', '11,187,', '0', '1');
INSERT INTO `zz_area` VALUES ('188', '齐齐哈尔市', '11', '11,188,', '0', '1');
INSERT INTO `zz_area` VALUES ('189', '鸡西市', '11', '11,189,', '0', '1');
INSERT INTO `zz_area` VALUES ('190', '鹤岗市', '11', '11,190,', '0', '1');
INSERT INTO `zz_area` VALUES ('191', '双鸭山市', '11', '11,191,', '0', '1');
INSERT INTO `zz_area` VALUES ('192', '大庆市', '11', '11,192,', '0', '1');
INSERT INTO `zz_area` VALUES ('193', '伊春市', '11', '11,193,', '0', '1');
INSERT INTO `zz_area` VALUES ('194', '佳木斯市', '11', '11,194,', '0', '1');
INSERT INTO `zz_area` VALUES ('195', '七台河市', '11', '11,195,', '0', '1');
INSERT INTO `zz_area` VALUES ('196', '牡丹江市', '11', '11,196,', '0', '1');
INSERT INTO `zz_area` VALUES ('197', '黑河市', '11', '11,197,', '0', '1');
INSERT INTO `zz_area` VALUES ('198', '绥化市', '11', '11,198,', '0', '1');
INSERT INTO `zz_area` VALUES ('199', '大兴安岭地区', '11', '11,199,', '0', '1');
INSERT INTO `zz_area` VALUES ('200', '南京市', '12', '12,200,', '0', '1');
INSERT INTO `zz_area` VALUES ('201', '无锡市', '12', '12,201,', '0', '1');
INSERT INTO `zz_area` VALUES ('202', '徐州市', '12', '12,202,', '0', '1');
INSERT INTO `zz_area` VALUES ('203', '常州市', '12', '12,203,', '0', '1');
INSERT INTO `zz_area` VALUES ('204', '苏州市', '12', '12,204,', '0', '1');
INSERT INTO `zz_area` VALUES ('205', '南通市', '12', '12,205,', '0', '1');
INSERT INTO `zz_area` VALUES ('206', '连云港市', '12', '12,206,', '0', '1');
INSERT INTO `zz_area` VALUES ('207', '淮安市', '12', '12,207,', '0', '1');
INSERT INTO `zz_area` VALUES ('208', '盐城市', '12', '12,208,', '0', '1');
INSERT INTO `zz_area` VALUES ('209', '扬州市', '12', '12,209,', '0', '1');
INSERT INTO `zz_area` VALUES ('210', '镇江市', '12', '12,210,', '0', '1');
INSERT INTO `zz_area` VALUES ('211', '泰州市', '12', '12,211,', '0', '1');
INSERT INTO `zz_area` VALUES ('212', '宿迁市', '12', '12,212,', '0', '1');
INSERT INTO `zz_area` VALUES ('213', '杭州市', '13', '13,213,', '0', '1');
INSERT INTO `zz_area` VALUES ('214', '宁波市', '13', '13,214,', '0', '1');
INSERT INTO `zz_area` VALUES ('215', '温州市', '13', '13,215,', '0', '1');
INSERT INTO `zz_area` VALUES ('216', '嘉兴市', '13', '13,216,', '0', '1');
INSERT INTO `zz_area` VALUES ('217', '湖州市', '13', '13,217,', '0', '1');
INSERT INTO `zz_area` VALUES ('218', '绍兴市', '13', '13,218,', '0', '1');
INSERT INTO `zz_area` VALUES ('219', '金华市', '13', '13,219,', '0', '1');
INSERT INTO `zz_area` VALUES ('220', '衢州市', '13', '13,220,', '0', '1');
INSERT INTO `zz_area` VALUES ('221', '舟山市', '13', '13,221,', '0', '1');
INSERT INTO `zz_area` VALUES ('222', '台州市', '13', '13,222,', '0', '1');
INSERT INTO `zz_area` VALUES ('223', '丽水市', '13', '13,223,', '0', '1');
INSERT INTO `zz_area` VALUES ('224', '合肥市', '14', '14,224,', '0', '1');
INSERT INTO `zz_area` VALUES ('225', '芜湖市', '14', '14,225,', '0', '1');
INSERT INTO `zz_area` VALUES ('226', '蚌埠市', '14', '14,226,', '0', '1');
INSERT INTO `zz_area` VALUES ('227', '淮南市', '14', '14,227,', '0', '1');
INSERT INTO `zz_area` VALUES ('228', '马鞍山市', '14', '14,228,', '0', '1');
INSERT INTO `zz_area` VALUES ('229', '淮北市', '14', '14,229,', '0', '1');
INSERT INTO `zz_area` VALUES ('230', '铜陵市', '14', '14,230,', '0', '1');
INSERT INTO `zz_area` VALUES ('231', '安庆市', '14', '14,231,', '0', '1');
INSERT INTO `zz_area` VALUES ('232', '黄山市', '14', '14,232,', '0', '1');
INSERT INTO `zz_area` VALUES ('233', '滁州市', '14', '14,233,', '0', '1');
INSERT INTO `zz_area` VALUES ('234', '阜阳市', '14', '14,234,', '0', '1');
INSERT INTO `zz_area` VALUES ('235', '宿州市', '14', '14,235,', '0', '1');
INSERT INTO `zz_area` VALUES ('236', '巢湖市', '14', '14,236,', '0', '1');
INSERT INTO `zz_area` VALUES ('237', '六安市', '14', '14,237,', '0', '1');
INSERT INTO `zz_area` VALUES ('238', '亳州市', '14', '14,238,', '0', '1');
INSERT INTO `zz_area` VALUES ('239', '池州市', '14', '14,239,', '0', '1');
INSERT INTO `zz_area` VALUES ('240', '宣城市', '14', '14,240,', '0', '1');
INSERT INTO `zz_area` VALUES ('241', '福州市', '15', '15,241,', '2', '1');
INSERT INTO `zz_area` VALUES ('242', '厦门市', '15', '15,242,', '1', '1');
INSERT INTO `zz_area` VALUES ('243', '莆田市', '243', ',15,243,', '0', '0');
INSERT INTO `zz_area` VALUES ('244', '三明市', '15', '15,244,', '4', '1');
INSERT INTO `zz_area` VALUES ('245', '泉州市', '15', '15,245,', '0', '1');
INSERT INTO `zz_area` VALUES ('246', '漳州市', '15', '15,246,', '3', '1');
INSERT INTO `zz_area` VALUES ('247', '南平市', '15', '15,247,', '5', '1');
INSERT INTO `zz_area` VALUES ('248', '龙岩市', '248', ',15,248,', '0', '0');
INSERT INTO `zz_area` VALUES ('249', '宁德市', '249', ',15,249,', '0', '0');
INSERT INTO `zz_area` VALUES ('250', '南昌市', '16', '16,250,', '0', '1');
INSERT INTO `zz_area` VALUES ('251', '景德镇市', '16', '16,251,', '0', '1');
INSERT INTO `zz_area` VALUES ('252', '萍乡市', '16', '16,252,', '0', '1');
INSERT INTO `zz_area` VALUES ('253', '九江市', '16', '16,253,', '0', '1');
INSERT INTO `zz_area` VALUES ('254', '新余市', '16', '16,254,', '0', '1');
INSERT INTO `zz_area` VALUES ('255', '鹰潭市', '16', '16,255,', '0', '1');
INSERT INTO `zz_area` VALUES ('256', '赣州市', '16', '16,256,', '0', '1');
INSERT INTO `zz_area` VALUES ('257', '吉安市', '16', '16,257,', '0', '1');
INSERT INTO `zz_area` VALUES ('258', '宜春市', '16', '16,258,', '0', '1');
INSERT INTO `zz_area` VALUES ('259', '抚州市', '16', '16,259,', '0', '1');
INSERT INTO `zz_area` VALUES ('260', '上饶市', '16', '16,260,', '0', '1');
INSERT INTO `zz_area` VALUES ('261', '济南市', '17', '17,261,', '0', '1');
INSERT INTO `zz_area` VALUES ('262', '青岛市', '17', '17,262,', '0', '1');
INSERT INTO `zz_area` VALUES ('263', '淄博市', '17', '17,263,', '0', '1');
INSERT INTO `zz_area` VALUES ('264', '枣庄市', '17', '17,264,', '0', '1');
INSERT INTO `zz_area` VALUES ('265', '东营市', '17', '17,265,', '0', '1');
INSERT INTO `zz_area` VALUES ('266', '烟台市', '17', '17,266,', '0', '1');
INSERT INTO `zz_area` VALUES ('267', '潍坊市', '17', '17,267,', '0', '1');
INSERT INTO `zz_area` VALUES ('268', '济宁市', '17', '17,268,', '0', '1');
INSERT INTO `zz_area` VALUES ('269', '泰安市', '17', '17,269,', '0', '1');
INSERT INTO `zz_area` VALUES ('270', '威海市', '17', '17,270,', '0', '1');
INSERT INTO `zz_area` VALUES ('271', '日照市', '17', '17,271,', '0', '1');
INSERT INTO `zz_area` VALUES ('272', '莱芜市', '17', '17,272,', '0', '1');
INSERT INTO `zz_area` VALUES ('273', '临沂市', '17', '17,273,', '0', '1');
INSERT INTO `zz_area` VALUES ('274', '德州市', '17', '17,274,', '0', '1');
INSERT INTO `zz_area` VALUES ('275', '聊城市', '17', '17,275,', '0', '1');
INSERT INTO `zz_area` VALUES ('276', '滨州市', '17', '17,276,', '0', '1');
INSERT INTO `zz_area` VALUES ('277', '荷泽市', '17', '17,277,', '0', '1');
INSERT INTO `zz_area` VALUES ('278', '郑州市', '18', '18,278,', '0', '1');
INSERT INTO `zz_area` VALUES ('279', '开封市', '18', '18,279,', '0', '1');
INSERT INTO `zz_area` VALUES ('280', '洛阳市', '18', '18,280,', '0', '1');
INSERT INTO `zz_area` VALUES ('281', '平顶山市', '18', '18,281,', '0', '1');
INSERT INTO `zz_area` VALUES ('282', '安阳市', '18', '18,282,', '0', '1');
INSERT INTO `zz_area` VALUES ('283', '鹤壁市', '18', '18,283,', '0', '1');
INSERT INTO `zz_area` VALUES ('284', '新乡市', '18', '18,284,', '0', '1');
INSERT INTO `zz_area` VALUES ('285', '焦作市', '18', '18,285,', '0', '1');
INSERT INTO `zz_area` VALUES ('286', '濮阳市', '18', '18,286,', '0', '1');
INSERT INTO `zz_area` VALUES ('287', '许昌市', '18', '18,287,', '0', '1');
INSERT INTO `zz_area` VALUES ('288', '漯河市', '18', '18,288,', '0', '1');
INSERT INTO `zz_area` VALUES ('289', '三门峡市', '18', '18,289,', '0', '1');
INSERT INTO `zz_area` VALUES ('290', '南阳市', '18', '18,290,', '0', '1');
INSERT INTO `zz_area` VALUES ('291', '商丘市', '18', '18,291,', '0', '1');
INSERT INTO `zz_area` VALUES ('292', '信阳市', '18', '18,292,', '0', '1');
INSERT INTO `zz_area` VALUES ('293', '周口市', '18', '18,293,', '0', '1');
INSERT INTO `zz_area` VALUES ('294', '驻马店市', '18', '18,294,', '0', '1');
INSERT INTO `zz_area` VALUES ('295', '武汉市', '19', '19,295,', '0', '1');
INSERT INTO `zz_area` VALUES ('296', '黄石市', '19', '19,296,', '0', '1');
INSERT INTO `zz_area` VALUES ('297', '十堰市', '19', '19,297,', '0', '1');
INSERT INTO `zz_area` VALUES ('298', '宜昌市', '19', '19,298,', '0', '1');
INSERT INTO `zz_area` VALUES ('299', '襄樊市', '19', '19,299,', '0', '1');
INSERT INTO `zz_area` VALUES ('300', '鄂州市', '19', '19,300,', '0', '1');
INSERT INTO `zz_area` VALUES ('301', '荆门市', '19', '19,301,', '0', '1');
INSERT INTO `zz_area` VALUES ('302', '孝感市', '19', '19,302,', '0', '1');
INSERT INTO `zz_area` VALUES ('303', '荆州市', '19', '19,303,', '0', '1');
INSERT INTO `zz_area` VALUES ('304', '黄冈市', '19', '19,304,', '0', '1');
INSERT INTO `zz_area` VALUES ('305', '咸宁市', '19', '19,305,', '0', '1');
INSERT INTO `zz_area` VALUES ('306', '随州市', '19', '19,306,', '0', '1');
INSERT INTO `zz_area` VALUES ('307', '恩施土家族苗族自治州', '19', '19,307,', '0', '1');
INSERT INTO `zz_area` VALUES ('308', '仙桃市', '19', '19,308,', '0', '1');
INSERT INTO `zz_area` VALUES ('309', '潜江市', '19', '19,309,', '0', '1');
INSERT INTO `zz_area` VALUES ('310', '天门市', '19', '19,310,', '0', '1');
INSERT INTO `zz_area` VALUES ('311', '神农架林区', '19', '19,311,', '0', '1');
INSERT INTO `zz_area` VALUES ('312', '长沙市', '20', '20,312,', '0', '1');
INSERT INTO `zz_area` VALUES ('313', '株洲市', '20', '20,313,', '0', '1');
INSERT INTO `zz_area` VALUES ('314', '湘潭市', '20', '20,314,', '0', '1');
INSERT INTO `zz_area` VALUES ('315', '衡阳市', '20', '20,315,', '0', '1');
INSERT INTO `zz_area` VALUES ('316', '邵阳市', '20', '20,316,', '0', '1');
INSERT INTO `zz_area` VALUES ('317', '岳阳市', '20', '20,317,', '0', '1');
INSERT INTO `zz_area` VALUES ('318', '常德市', '20', '20,318,', '0', '1');
INSERT INTO `zz_area` VALUES ('319', '张家界市', '20', '20,319,', '0', '1');
INSERT INTO `zz_area` VALUES ('320', '益阳市', '20', '20,320,', '0', '1');
INSERT INTO `zz_area` VALUES ('321', '郴州市', '20', '20,321,', '0', '1');
INSERT INTO `zz_area` VALUES ('322', '永州市', '20', '20,322,', '0', '1');
INSERT INTO `zz_area` VALUES ('323', '怀化市', '20', '20,323,', '0', '1');
INSERT INTO `zz_area` VALUES ('324', '娄底市', '20', '20,324,', '0', '1');
INSERT INTO `zz_area` VALUES ('325', '湘西土家族苗族自治州', '20', '20,325,', '0', '1');
INSERT INTO `zz_area` VALUES ('326', '广州市', '21', '21,326,', '0', '1');
INSERT INTO `zz_area` VALUES ('327', '韶关市', '21', '21,327,', '0', '1');
INSERT INTO `zz_area` VALUES ('328', '深圳市', '21', '21,328,', '0', '1');
INSERT INTO `zz_area` VALUES ('329', '珠海市', '21', '21,329,', '0', '1');
INSERT INTO `zz_area` VALUES ('330', '汕头市', '21', '21,330,', '0', '1');
INSERT INTO `zz_area` VALUES ('331', '佛山市', '21', '21,331,', '0', '1');
INSERT INTO `zz_area` VALUES ('332', '江门市', '21', '21,332,', '0', '1');
INSERT INTO `zz_area` VALUES ('333', '湛江市', '21', '21,333,', '0', '1');
INSERT INTO `zz_area` VALUES ('334', '茂名市', '21', '21,334,', '0', '1');
INSERT INTO `zz_area` VALUES ('335', '肇庆市', '21', '21,335,', '0', '1');
INSERT INTO `zz_area` VALUES ('336', '惠州市', '21', '21,336,', '0', '1');
INSERT INTO `zz_area` VALUES ('337', '梅州市', '21', '21,337,', '0', '1');
INSERT INTO `zz_area` VALUES ('338', '汕尾市', '21', '21,338,', '0', '1');
INSERT INTO `zz_area` VALUES ('339', '河源市', '21', '21,339,', '0', '1');
INSERT INTO `zz_area` VALUES ('340', '阳江市', '21', '21,340,', '0', '1');
INSERT INTO `zz_area` VALUES ('341', '清远市', '21', '21,341,', '0', '1');
INSERT INTO `zz_area` VALUES ('342', '东莞市', '21', '21,342,', '0', '1');
INSERT INTO `zz_area` VALUES ('343', '中山市', '21', '21,343,', '0', '1');
INSERT INTO `zz_area` VALUES ('344', '潮州市', '21', '21,344,', '0', '1');
INSERT INTO `zz_area` VALUES ('345', '揭阳市', '21', '21,345,', '0', '1');
INSERT INTO `zz_area` VALUES ('346', '云浮市', '21', '21,346,', '0', '1');
INSERT INTO `zz_area` VALUES ('347', '南宁市', '22', '22,347,', '0', '1');
INSERT INTO `zz_area` VALUES ('348', '柳州市', '22', '22,348,', '0', '1');
INSERT INTO `zz_area` VALUES ('349', '桂林市', '22', '22,349,', '0', '1');
INSERT INTO `zz_area` VALUES ('350', '梧州市', '22', '22,350,', '0', '1');
INSERT INTO `zz_area` VALUES ('351', '北海市', '22', '22,351,', '0', '1');
INSERT INTO `zz_area` VALUES ('352', '防城港市', '22', '22,352,', '0', '1');
INSERT INTO `zz_area` VALUES ('353', '钦州市', '22', '22,353,', '0', '1');
INSERT INTO `zz_area` VALUES ('354', '贵港市', '22', '22,354,', '0', '1');
INSERT INTO `zz_area` VALUES ('355', '玉林市', '22', '22,355,', '0', '1');
INSERT INTO `zz_area` VALUES ('356', '百色市', '22', '22,356,', '0', '1');
INSERT INTO `zz_area` VALUES ('357', '贺州市', '22', '22,357,', '0', '1');
INSERT INTO `zz_area` VALUES ('358', '河池市', '22', '22,358,', '0', '1');
INSERT INTO `zz_area` VALUES ('359', '来宾市', '22', '22,359,', '0', '1');
INSERT INTO `zz_area` VALUES ('360', '崇左市', '22', '22,360,', '0', '1');
INSERT INTO `zz_area` VALUES ('361', '海口市', '23', '23,361,', '0', '1');
INSERT INTO `zz_area` VALUES ('362', '三亚市', '23', '23,362,', '0', '1');
INSERT INTO `zz_area` VALUES ('363', '五指山市', '23', '23,363,', '0', '1');
INSERT INTO `zz_area` VALUES ('364', '琼海市', '23', '23,364,', '0', '1');
INSERT INTO `zz_area` VALUES ('365', '儋州市', '23', '23,365,', '0', '1');
INSERT INTO `zz_area` VALUES ('366', '文昌市', '23', '23,366,', '0', '1');
INSERT INTO `zz_area` VALUES ('367', '万宁市', '23', '23,367,', '0', '1');
INSERT INTO `zz_area` VALUES ('368', '东方市', '23', '23,368,', '0', '1');
INSERT INTO `zz_area` VALUES ('369', '定安县', '23', '23,369,', '0', '1');
INSERT INTO `zz_area` VALUES ('370', '屯昌县', '23', '23,370,', '0', '1');
INSERT INTO `zz_area` VALUES ('371', '澄迈县', '23', '23,371,', '0', '1');
INSERT INTO `zz_area` VALUES ('372', '临高县', '23', '23,372,', '0', '1');
INSERT INTO `zz_area` VALUES ('373', '白沙黎族自治县', '23', '23,373,', '0', '1');
INSERT INTO `zz_area` VALUES ('374', '昌江黎族自治县', '23', '23,374,', '0', '1');
INSERT INTO `zz_area` VALUES ('375', '乐东黎族自治县', '23', '23,375,', '0', '1');
INSERT INTO `zz_area` VALUES ('376', '陵水黎族自治县', '23', '23,376,', '0', '1');
INSERT INTO `zz_area` VALUES ('377', '保亭黎族苗族自治县', '23', '23,377,', '0', '1');
INSERT INTO `zz_area` VALUES ('378', '琼中黎族苗族自治县', '23', '23,378,', '0', '1');
INSERT INTO `zz_area` VALUES ('379', '西沙群岛', '23', '23,379,', '0', '1');
INSERT INTO `zz_area` VALUES ('380', '南沙群岛', '23', '23,380,', '0', '1');
INSERT INTO `zz_area` VALUES ('381', '中沙群岛的岛礁及其海域', '23', '23,381,', '0', '1');
INSERT INTO `zz_area` VALUES ('382', '成都市', '24', '24,382,', '0', '1');
INSERT INTO `zz_area` VALUES ('383', '自贡市', '24', '24,383,', '0', '1');
INSERT INTO `zz_area` VALUES ('384', '攀枝花市', '24', '24,384,', '0', '1');
INSERT INTO `zz_area` VALUES ('385', '泸州市', '24', '24,385,', '0', '1');
INSERT INTO `zz_area` VALUES ('386', '德阳市', '24', '24,386,', '0', '1');
INSERT INTO `zz_area` VALUES ('387', '绵阳市', '24', '24,387,', '0', '1');
INSERT INTO `zz_area` VALUES ('388', '广元市', '24', '24,388,', '0', '1');
INSERT INTO `zz_area` VALUES ('389', '遂宁市', '24', '24,389,', '0', '1');
INSERT INTO `zz_area` VALUES ('390', '内江市', '24', '24,390,', '0', '1');
INSERT INTO `zz_area` VALUES ('391', '乐山市', '24', '24,391,', '0', '1');
INSERT INTO `zz_area` VALUES ('392', '南充市', '24', '24,392,', '0', '1');
INSERT INTO `zz_area` VALUES ('393', '眉山市', '24', '24,393,', '0', '1');
INSERT INTO `zz_area` VALUES ('394', '宜宾市', '24', '24,394,', '0', '1');
INSERT INTO `zz_area` VALUES ('395', '广安市', '24', '24,395,', '0', '1');
INSERT INTO `zz_area` VALUES ('396', '达州市', '24', '24,396,', '0', '1');
INSERT INTO `zz_area` VALUES ('397', '雅安市', '24', '24,397,', '0', '1');
INSERT INTO `zz_area` VALUES ('398', '巴中市', '24', '24,398,', '0', '1');
INSERT INTO `zz_area` VALUES ('399', '资阳市', '24', '24,399,', '0', '1');
INSERT INTO `zz_area` VALUES ('400', '阿坝州', '24', '24,400,', '0', '1');
INSERT INTO `zz_area` VALUES ('401', '甘孜州', '24', '24,401,', '0', '1');
INSERT INTO `zz_area` VALUES ('402', '凉山州', '24', '24,402,', '0', '1');
INSERT INTO `zz_area` VALUES ('403', '贵阳市', '25', '25,403,', '0', '1');
INSERT INTO `zz_area` VALUES ('404', '六盘水市', '25', '25,404,', '0', '1');
INSERT INTO `zz_area` VALUES ('405', '遵义市', '25', '25,405,', '0', '1');
INSERT INTO `zz_area` VALUES ('406', '安顺市', '25', '25,406,', '0', '1');
INSERT INTO `zz_area` VALUES ('407', '铜仁地区', '25', '25,407,', '0', '1');
INSERT INTO `zz_area` VALUES ('408', '黔西南州', '25', '25,408,', '0', '1');
INSERT INTO `zz_area` VALUES ('409', '毕节地区', '25', '25,409,', '0', '1');
INSERT INTO `zz_area` VALUES ('410', '黔东南州', '25', '25,410,', '0', '1');
INSERT INTO `zz_area` VALUES ('411', '黔南州', '25', '25,411,', '0', '1');
INSERT INTO `zz_area` VALUES ('412', '昆明市', '26', '26,412,', '0', '1');
INSERT INTO `zz_area` VALUES ('413', '曲靖市', '26', '26,413,', '0', '1');
INSERT INTO `zz_area` VALUES ('414', '玉溪市', '26', '26,414,', '0', '1');
INSERT INTO `zz_area` VALUES ('415', '保山市', '26', '26,415,', '0', '1');
INSERT INTO `zz_area` VALUES ('416', '昭通市', '26', '26,416,', '0', '1');
INSERT INTO `zz_area` VALUES ('417', '丽江市', '26', '26,417,', '0', '1');
INSERT INTO `zz_area` VALUES ('418', '思茅市', '26', '26,418,', '0', '1');
INSERT INTO `zz_area` VALUES ('419', '临沧市', '26', '26,419,', '0', '1');
INSERT INTO `zz_area` VALUES ('420', '楚雄州', '26', '26,420,', '0', '1');
INSERT INTO `zz_area` VALUES ('421', '红河州', '26', '26,421,', '0', '1');
INSERT INTO `zz_area` VALUES ('422', '文山州', '26', '26,422,', '0', '1');
INSERT INTO `zz_area` VALUES ('423', '西双版纳', '26', '26,423,', '0', '1');
INSERT INTO `zz_area` VALUES ('424', '大理', '26', '26,424,', '0', '1');
INSERT INTO `zz_area` VALUES ('425', '德宏', '26', '26,425,', '0', '1');
INSERT INTO `zz_area` VALUES ('426', '怒江', '26', '26,426,', '0', '1');
INSERT INTO `zz_area` VALUES ('427', '迪庆', '26', '26,427,', '0', '1');
INSERT INTO `zz_area` VALUES ('428', '拉萨市', '27', '27,428,', '0', '1');
INSERT INTO `zz_area` VALUES ('429', '昌都', '27', '27,429,', '0', '1');
INSERT INTO `zz_area` VALUES ('430', '山南', '27', '27,430,', '0', '1');
INSERT INTO `zz_area` VALUES ('431', '日喀则', '27', '27,431,', '0', '1');
INSERT INTO `zz_area` VALUES ('432', '那曲', '27', '27,432,', '0', '1');
INSERT INTO `zz_area` VALUES ('433', '阿里', '27', '27,433,', '0', '1');
INSERT INTO `zz_area` VALUES ('434', '林芝', '27', '27,434,', '0', '1');
INSERT INTO `zz_area` VALUES ('435', '西安市', '28', '28,435,', '0', '1');
INSERT INTO `zz_area` VALUES ('436', '铜川市', '28', '28,436,', '0', '1');
INSERT INTO `zz_area` VALUES ('437', '宝鸡市', '28', '28,437,', '0', '1');
INSERT INTO `zz_area` VALUES ('438', '咸阳市', '28', '28,438,', '0', '1');
INSERT INTO `zz_area` VALUES ('439', '渭南市', '28', '28,439,', '0', '1');
INSERT INTO `zz_area` VALUES ('440', '延安市', '28', '28,440,', '0', '1');
INSERT INTO `zz_area` VALUES ('441', '汉中市', '28', '28,441,', '0', '1');
INSERT INTO `zz_area` VALUES ('442', '榆林市', '28', '28,442,', '0', '1');
INSERT INTO `zz_area` VALUES ('443', '安康市', '28', '28,443,', '0', '1');
INSERT INTO `zz_area` VALUES ('444', '商洛市', '28', '28,444,', '0', '1');
INSERT INTO `zz_area` VALUES ('445', '兰州市', '29', '29,445,', '0', '1');
INSERT INTO `zz_area` VALUES ('446', '嘉峪关市', '29', '29,446,', '0', '1');
INSERT INTO `zz_area` VALUES ('447', '金昌市', '29', '29,447,', '0', '1');
INSERT INTO `zz_area` VALUES ('448', '白银市', '29', '29,448,', '0', '1');
INSERT INTO `zz_area` VALUES ('449', '天水市', '29', '29,449,', '0', '1');
INSERT INTO `zz_area` VALUES ('450', '武威市', '29', '29,450,', '0', '1');
INSERT INTO `zz_area` VALUES ('451', '张掖市', '29', '29,451,', '0', '1');
INSERT INTO `zz_area` VALUES ('452', '平凉市', '29', '29,452,', '0', '1');
INSERT INTO `zz_area` VALUES ('453', '酒泉市', '29', '29,453,', '0', '1');
INSERT INTO `zz_area` VALUES ('454', '庆阳市', '29', '29,454,', '0', '1');
INSERT INTO `zz_area` VALUES ('455', '定西市', '29', '29,455,', '0', '1');
INSERT INTO `zz_area` VALUES ('456', '陇南市', '29', '29,456,', '0', '1');
INSERT INTO `zz_area` VALUES ('457', '临夏州', '29', '29,457,', '0', '1');
INSERT INTO `zz_area` VALUES ('458', '甘州', '29', '29,458,', '0', '1');
INSERT INTO `zz_area` VALUES ('459', '西宁市', '30', '30,459,', '0', '1');
INSERT INTO `zz_area` VALUES ('460', '海东地区', '30', '30,460,', '0', '1');
INSERT INTO `zz_area` VALUES ('461', '海州', '30', '30,461,', '0', '1');
INSERT INTO `zz_area` VALUES ('462', '黄南州', '30', '30,462,', '0', '1');
INSERT INTO `zz_area` VALUES ('463', '海南州', '30', '30,463,', '0', '1');
INSERT INTO `zz_area` VALUES ('464', '果洛州', '30', '30,464,', '0', '1');
INSERT INTO `zz_area` VALUES ('465', '玉树州', '30', '30,465,', '0', '1');
INSERT INTO `zz_area` VALUES ('466', '海西州', '30', '30,466,', '0', '1');
INSERT INTO `zz_area` VALUES ('467', '银川市', '31', '31,467,', '0', '1');
INSERT INTO `zz_area` VALUES ('468', '石嘴山市', '31', '31,468,', '0', '1');
INSERT INTO `zz_area` VALUES ('469', '吴忠市', '31', '31,469,', '0', '1');
INSERT INTO `zz_area` VALUES ('470', '固原市', '31', '31,470,', '0', '1');
INSERT INTO `zz_area` VALUES ('471', '中卫市', '31', '31,471,', '0', '1');
INSERT INTO `zz_area` VALUES ('472', '乌鲁木齐市', '32', '32,472,', '0', '1');
INSERT INTO `zz_area` VALUES ('473', '克拉玛依市', '32', '32,473,', '0', '1');
INSERT INTO `zz_area` VALUES ('474', '吐鲁番地区', '32', '32,474,', '0', '1');
INSERT INTO `zz_area` VALUES ('475', '哈密地区', '32', '32,475,', '0', '1');
INSERT INTO `zz_area` VALUES ('476', '昌吉州', '32', '32,476,', '0', '1');
INSERT INTO `zz_area` VALUES ('477', '博尔州', '32', '32,477,', '0', '1');
INSERT INTO `zz_area` VALUES ('478', '巴音郭楞州', '32', '32,478,', '0', '1');
INSERT INTO `zz_area` VALUES ('479', '阿克苏地区', '32', '32,479,', '0', '1');
INSERT INTO `zz_area` VALUES ('480', '克孜勒苏柯尔克孜自治州', '32', '32,480,', '0', '1');
INSERT INTO `zz_area` VALUES ('481', '喀什地区', '32', '32,481,', '0', '1');
INSERT INTO `zz_area` VALUES ('482', '和田地区', '32', '32,482,', '0', '1');
INSERT INTO `zz_area` VALUES ('483', '伊犁州', '32', '32,483,', '0', '1');
INSERT INTO `zz_area` VALUES ('484', '塔城地区', '32', '32,484,', '0', '1');
INSERT INTO `zz_area` VALUES ('485', '阿勒泰地区', '32', '32,485,', '0', '1');
INSERT INTO `zz_area` VALUES ('486', '石河子市', '32', '32,486,', '0', '1');
INSERT INTO `zz_area` VALUES ('487', '阿拉尔市', '32', '32,487,', '0', '1');
INSERT INTO `zz_area` VALUES ('488', '图木舒克市', '32', '32,488,', '0', '1');
INSERT INTO `zz_area` VALUES ('489', '五家渠市', '32', '32,489,', '0', '1');
INSERT INTO `zz_area` VALUES ('490', '台北市', '33', '33,490,', '0', '1');
INSERT INTO `zz_area` VALUES ('491', '高雄市', '33', '33,491,', '0', '1');
INSERT INTO `zz_area` VALUES ('492', '基隆市', '33', '33,492,', '0', '1');
INSERT INTO `zz_area` VALUES ('493', '新竹市', '33', '33,493,', '0', '1');
INSERT INTO `zz_area` VALUES ('494', '台中市', '33', '33,494,', '0', '1');
INSERT INTO `zz_area` VALUES ('495', '嘉义市', '33', '33,495,', '0', '1');
INSERT INTO `zz_area` VALUES ('496', '台南市', '33', '33,496,', '0', '1');
INSERT INTO `zz_area` VALUES ('497', '台北县', '33', '33,497,', '0', '1');
INSERT INTO `zz_area` VALUES ('498', '桃园县', '33', '33,498,', '0', '1');
INSERT INTO `zz_area` VALUES ('499', '新竹县', '33', '33,499,', '0', '1');
INSERT INTO `zz_area` VALUES ('500', '苗栗县', '33', '33,500,', '0', '1');
INSERT INTO `zz_area` VALUES ('501', '台中县', '33', '33,501,', '0', '1');
INSERT INTO `zz_area` VALUES ('502', '彰化县', '33', '33,502,', '0', '1');
INSERT INTO `zz_area` VALUES ('503', '南投县', '33', '33,503,', '0', '1');
INSERT INTO `zz_area` VALUES ('504', '云林县', '33', '33,504,', '0', '1');
INSERT INTO `zz_area` VALUES ('505', '嘉义县', '33', '33,505,', '0', '1');
INSERT INTO `zz_area` VALUES ('506', '台南县', '33', '33,506,', '0', '1');
INSERT INTO `zz_area` VALUES ('507', '高雄县', '33', '33,507,', '0', '1');
INSERT INTO `zz_area` VALUES ('508', '屏东县', '33', '33,508,', '0', '1');
INSERT INTO `zz_area` VALUES ('509', '宜兰县', '33', '33,509,', '0', '1');
INSERT INTO `zz_area` VALUES ('510', '花莲县', '33', '33,510,', '0', '1');
INSERT INTO `zz_area` VALUES ('511', '台东县', '33', '33,511,', '0', '1');
INSERT INTO `zz_area` VALUES ('512', '澎湖县', '33', '33,512,', '0', '1');
INSERT INTO `zz_area` VALUES ('513', '金门县', '33', '33,513,', '0', '1');
INSERT INTO `zz_area` VALUES ('514', '连江县', '33', '33,514,', '0', '1');
INSERT INTO `zz_area` VALUES ('515', '中西区', '34', '34,515,', '0', '1');
INSERT INTO `zz_area` VALUES ('516', '东区', '34', '34,516,', '0', '1');
INSERT INTO `zz_area` VALUES ('517', '南区', '34', '34,517,', '0', '1');
INSERT INTO `zz_area` VALUES ('518', '湾仔区', '34', '34,518,', '0', '1');
INSERT INTO `zz_area` VALUES ('519', '九龙城区', '34', '34,519,', '0', '1');
INSERT INTO `zz_area` VALUES ('520', '观塘区', '34', '34,520,', '0', '1');
INSERT INTO `zz_area` VALUES ('521', '深水埗区', '34', '34,521,', '0', '1');
INSERT INTO `zz_area` VALUES ('522', '黄大仙区', '34', '34,522,', '0', '1');
INSERT INTO `zz_area` VALUES ('523', '油尖旺区', '34', '34,523,', '0', '1');
INSERT INTO `zz_area` VALUES ('524', '离岛区', '34', '34,524,', '0', '1');
INSERT INTO `zz_area` VALUES ('525', '葵青区', '34', '34,525,', '0', '1');
INSERT INTO `zz_area` VALUES ('526', '北区', '34', '34,526,', '0', '1');
INSERT INTO `zz_area` VALUES ('527', '西贡区', '34', '34,527,', '0', '1');
INSERT INTO `zz_area` VALUES ('528', '沙田区', '34', '34,528,', '0', '1');
INSERT INTO `zz_area` VALUES ('529', '大埔区', '34', '34,529,', '0', '1');
INSERT INTO `zz_area` VALUES ('530', '荃湾区', '34', '34,530,', '0', '1');
INSERT INTO `zz_area` VALUES ('531', '屯门区', '34', '34,531,', '0', '1');
INSERT INTO `zz_area` VALUES ('532', '元朗区', '34', '34,532,', '0', '1');
INSERT INTO `zz_area` VALUES ('533', '花地玛堂区', '35', '35,533,', '0', '1');
INSERT INTO `zz_area` VALUES ('534', '市圣安多尼堂区', '35', '35,534,', '0', '1');
INSERT INTO `zz_area` VALUES ('535', '大堂区', '35', '35,535,', '0', '1');
INSERT INTO `zz_area` VALUES ('536', '望德堂区', '35', '35,536,', '0', '1');
INSERT INTO `zz_area` VALUES ('537', '风顺堂区', '35', '35,537,', '0', '1');
INSERT INTO `zz_area` VALUES ('538', '嘉模堂区', '35', '35,538,', '0', '1');
INSERT INTO `zz_area` VALUES ('539', '圣方济各堂区', '35', '35,539,', '0', '1');
INSERT INTO `zz_area` VALUES ('540', '长安区', '130', '6,130,540', '0', '1');
INSERT INTO `zz_area` VALUES ('541', '桥东区', '130', '6,130,541', '0', '1');
INSERT INTO `zz_area` VALUES ('542', '桥西区', '130', '6,130,542', '0', '1');
INSERT INTO `zz_area` VALUES ('543', '新华区', '130', '6,130,543', '0', '1');
INSERT INTO `zz_area` VALUES ('544', '井陉矿区', '130', '6,130,544', '0', '1');
INSERT INTO `zz_area` VALUES ('545', '裕华区', '130', '6,130,545', '0', '1');
INSERT INTO `zz_area` VALUES ('546', '井陉县', '130', '6,130,546', '0', '1');
INSERT INTO `zz_area` VALUES ('547', '正定县', '130', '6,130,547', '0', '1');
INSERT INTO `zz_area` VALUES ('548', '栾城县', '130', '6,130,548', '0', '1');
INSERT INTO `zz_area` VALUES ('549', '行唐县', '130', '6,130,549', '0', '1');
INSERT INTO `zz_area` VALUES ('550', '灵寿县', '130', '6,130,550', '0', '1');
INSERT INTO `zz_area` VALUES ('551', '高邑县', '130', '6,130,551', '0', '1');
INSERT INTO `zz_area` VALUES ('552', '深泽县', '130', '6,130,552', '0', '1');
INSERT INTO `zz_area` VALUES ('553', '赞皇县', '130', '6,130,553', '0', '1');
INSERT INTO `zz_area` VALUES ('554', '无极县', '130', '6,130,554', '0', '1');
INSERT INTO `zz_area` VALUES ('555', '平山县', '130', '6,130,555', '0', '1');
INSERT INTO `zz_area` VALUES ('556', '元氏县', '130', '6,130,556', '0', '1');
INSERT INTO `zz_area` VALUES ('557', '赵县', '130', '6,130,557', '0', '1');
INSERT INTO `zz_area` VALUES ('558', '辛集市', '130', '6,130,558', '0', '1');
INSERT INTO `zz_area` VALUES ('559', '藁城市', '130', '6,130,559', '0', '1');
INSERT INTO `zz_area` VALUES ('560', '晋州市', '130', '6,130,560', '0', '1');
INSERT INTO `zz_area` VALUES ('561', '新乐市', '130', '6,130,561', '0', '1');
INSERT INTO `zz_area` VALUES ('562', '鹿泉市', '130', '6,130,562', '0', '1');
INSERT INTO `zz_area` VALUES ('563', '路南区', '131', '6,131,563', '0', '1');
INSERT INTO `zz_area` VALUES ('564', '路北区', '131', '6,131,564', '0', '1');
INSERT INTO `zz_area` VALUES ('565', '古冶区', '131', '6,131,565', '0', '1');
INSERT INTO `zz_area` VALUES ('566', '开平区', '131', '6,131,566', '0', '1');
INSERT INTO `zz_area` VALUES ('567', '丰南区', '131', '6,131,567', '0', '1');
INSERT INTO `zz_area` VALUES ('568', '丰润区', '131', '6,131,568', '0', '1');
INSERT INTO `zz_area` VALUES ('569', '滦县', '131', '6,131,569', '0', '1');
INSERT INTO `zz_area` VALUES ('570', '滦南县', '131', '6,131,570', '0', '1');
INSERT INTO `zz_area` VALUES ('571', '乐亭县', '131', '6,131,571', '0', '1');
INSERT INTO `zz_area` VALUES ('572', '迁西县', '131', '6,131,572', '0', '1');
INSERT INTO `zz_area` VALUES ('573', '玉田县', '131', '6,131,573', '0', '1');
INSERT INTO `zz_area` VALUES ('574', '唐海县', '131', '6,131,574', '0', '1');
INSERT INTO `zz_area` VALUES ('575', '遵化市', '131', '6,131,575', '0', '1');
INSERT INTO `zz_area` VALUES ('576', '迁安市', '131', '6,131,576', '0', '1');
INSERT INTO `zz_area` VALUES ('577', '海港区', '132', '6,132,577', '0', '1');
INSERT INTO `zz_area` VALUES ('578', '山海关区', '132', '6,132,578', '0', '1');
INSERT INTO `zz_area` VALUES ('579', '北戴河区', '132', '6,132,579', '0', '1');
INSERT INTO `zz_area` VALUES ('580', '青龙县', '132', '6,132,580', '0', '1');
INSERT INTO `zz_area` VALUES ('581', '昌黎县', '132', '6,132,581', '0', '1');
INSERT INTO `zz_area` VALUES ('582', '抚宁县', '132', '6,132,582', '0', '1');
INSERT INTO `zz_area` VALUES ('583', '卢龙县', '132', '6,132,583', '0', '1');
INSERT INTO `zz_area` VALUES ('584', '邯山区', '133', '6,133,584', '0', '1');
INSERT INTO `zz_area` VALUES ('585', '丛台区', '133', '6,133,585', '0', '1');
INSERT INTO `zz_area` VALUES ('586', '复兴区', '133', '6,133,586', '0', '1');
INSERT INTO `zz_area` VALUES ('587', '峰峰矿区', '133', '6,133,587', '0', '1');
INSERT INTO `zz_area` VALUES ('588', '邯郸县', '133', '6,133,588', '0', '1');
INSERT INTO `zz_area` VALUES ('589', '临漳县', '133', '6,133,589', '0', '1');
INSERT INTO `zz_area` VALUES ('590', '成安县', '133', '6,133,590', '0', '1');
INSERT INTO `zz_area` VALUES ('591', '大名县', '133', '6,133,591', '0', '1');
INSERT INTO `zz_area` VALUES ('592', '涉县', '133', '6,133,592', '0', '1');
INSERT INTO `zz_area` VALUES ('593', '磁县', '133', '6,133,593', '0', '1');
INSERT INTO `zz_area` VALUES ('594', '肥乡县', '133', '6,133,594', '0', '1');
INSERT INTO `zz_area` VALUES ('595', '永年县', '133', '6,133,595', '0', '1');
INSERT INTO `zz_area` VALUES ('596', '邱县', '133', '6,133,596', '0', '1');
INSERT INTO `zz_area` VALUES ('597', '鸡泽县', '133', '6,133,597', '0', '1');
INSERT INTO `zz_area` VALUES ('598', '广平县', '133', '6,133,598', '0', '1');
INSERT INTO `zz_area` VALUES ('599', '馆陶县', '133', '6,133,599', '0', '1');
INSERT INTO `zz_area` VALUES ('600', '魏县', '133', '6,133,600', '0', '1');
INSERT INTO `zz_area` VALUES ('601', '曲周县', '133', '6,133,601', '0', '1');
INSERT INTO `zz_area` VALUES ('602', '武安市', '133', '6,133,602', '0', '1');
INSERT INTO `zz_area` VALUES ('603', '桥东区', '134', '6,134,603', '0', '1');
INSERT INTO `zz_area` VALUES ('604', '桥西区', '134', '6,134,604', '0', '1');
INSERT INTO `zz_area` VALUES ('605', '邢台县', '134', '6,134,605', '0', '1');
INSERT INTO `zz_area` VALUES ('606', '临城县', '134', '6,134,606', '0', '1');
INSERT INTO `zz_area` VALUES ('607', '内丘县', '134', '6,134,607', '0', '1');
INSERT INTO `zz_area` VALUES ('608', '柏乡县', '134', '6,134,608', '0', '1');
INSERT INTO `zz_area` VALUES ('609', '隆尧县', '134', '6,134,609', '0', '1');
INSERT INTO `zz_area` VALUES ('610', '任县', '134', '6,134,610', '0', '1');
INSERT INTO `zz_area` VALUES ('611', '南和县', '134', '6,134,611', '0', '1');
INSERT INTO `zz_area` VALUES ('612', '宁晋县', '134', '6,134,612', '0', '1');
INSERT INTO `zz_area` VALUES ('613', '巨鹿县', '134', '6,134,613', '0', '1');
INSERT INTO `zz_area` VALUES ('614', '新河县', '134', '6,134,614', '0', '1');
INSERT INTO `zz_area` VALUES ('615', '广宗县', '134', '6,134,615', '0', '1');
INSERT INTO `zz_area` VALUES ('616', '平乡县', '134', '6,134,616', '0', '1');
INSERT INTO `zz_area` VALUES ('617', '威县', '134', '6,134,617', '0', '1');
INSERT INTO `zz_area` VALUES ('618', '清河县', '134', '6,134,618', '0', '1');
INSERT INTO `zz_area` VALUES ('619', '临西县', '134', '6,134,619', '0', '1');
INSERT INTO `zz_area` VALUES ('620', '南宫市', '134', '6,134,620', '0', '1');
INSERT INTO `zz_area` VALUES ('621', '沙河市', '134', '6,134,621', '0', '1');
INSERT INTO `zz_area` VALUES ('622', '新市区', '135', '6,135,622', '0', '1');
INSERT INTO `zz_area` VALUES ('623', '北市区', '135', '6,135,623', '0', '1');
INSERT INTO `zz_area` VALUES ('624', '南市区', '135', '6,135,624', '0', '1');
INSERT INTO `zz_area` VALUES ('625', '满城县', '135', '6,135,625', '0', '1');
INSERT INTO `zz_area` VALUES ('626', '清苑县', '135', '6,135,626', '0', '1');
INSERT INTO `zz_area` VALUES ('627', '涞水县', '135', '6,135,627', '0', '1');
INSERT INTO `zz_area` VALUES ('628', '阜平县', '135', '6,135,628', '0', '1');
INSERT INTO `zz_area` VALUES ('629', '徐水县', '135', '6,135,629', '0', '1');
INSERT INTO `zz_area` VALUES ('630', '定兴县', '135', '6,135,630', '0', '1');
INSERT INTO `zz_area` VALUES ('631', '唐县', '135', '6,135,631', '0', '1');
INSERT INTO `zz_area` VALUES ('632', '高阳县', '135', '6,135,632', '0', '1');
INSERT INTO `zz_area` VALUES ('633', '容城县', '135', '6,135,633', '0', '1');
INSERT INTO `zz_area` VALUES ('634', '涞源县', '135', '6,135,634', '0', '1');
INSERT INTO `zz_area` VALUES ('635', '望都县', '135', '6,135,635', '0', '1');
INSERT INTO `zz_area` VALUES ('636', '安新县', '135', '6,135,636', '0', '1');
INSERT INTO `zz_area` VALUES ('637', '易县', '135', '6,135,637', '0', '1');
INSERT INTO `zz_area` VALUES ('638', '曲阳县', '135', '6,135,638', '0', '1');
INSERT INTO `zz_area` VALUES ('639', '蠡县', '135', '6,135,639', '0', '1');
INSERT INTO `zz_area` VALUES ('640', '顺平县', '135', '6,135,640', '0', '1');
INSERT INTO `zz_area` VALUES ('641', '博野县', '135', '6,135,641', '0', '1');
INSERT INTO `zz_area` VALUES ('642', '雄县', '135', '6,135,642', '0', '1');
INSERT INTO `zz_area` VALUES ('643', '涿州市', '135', '6,135,643', '0', '1');
INSERT INTO `zz_area` VALUES ('644', '定州市', '135', '6,135,644', '0', '1');
INSERT INTO `zz_area` VALUES ('645', '安国市', '135', '6,135,645', '0', '1');
INSERT INTO `zz_area` VALUES ('646', '高碑店市', '135', '6,135,646', '0', '1');
INSERT INTO `zz_area` VALUES ('647', '桥东区', '136', '6,136,647', '0', '1');
INSERT INTO `zz_area` VALUES ('648', '桥西区', '136', '6,136,648', '0', '1');
INSERT INTO `zz_area` VALUES ('649', '宣化区', '136', '6,136,649', '0', '1');
INSERT INTO `zz_area` VALUES ('650', '下花园区', '136', '6,136,650', '0', '1');
INSERT INTO `zz_area` VALUES ('651', '宣化县', '136', '6,136,651', '0', '1');
INSERT INTO `zz_area` VALUES ('652', '张北县', '136', '6,136,652', '0', '1');
INSERT INTO `zz_area` VALUES ('653', '康保县', '136', '6,136,653', '0', '1');
INSERT INTO `zz_area` VALUES ('654', '沽源县', '136', '6,136,654', '0', '1');
INSERT INTO `zz_area` VALUES ('655', '尚义县', '136', '6,136,655', '0', '1');
INSERT INTO `zz_area` VALUES ('656', '蔚县', '136', '6,136,656', '0', '1');
INSERT INTO `zz_area` VALUES ('657', '阳原县', '136', '6,136,657', '0', '1');
INSERT INTO `zz_area` VALUES ('658', '怀安县', '136', '6,136,658', '0', '1');
INSERT INTO `zz_area` VALUES ('659', '万全县', '136', '6,136,659', '0', '1');
INSERT INTO `zz_area` VALUES ('660', '怀来县', '136', '6,136,660', '0', '1');
INSERT INTO `zz_area` VALUES ('661', '涿鹿县', '136', '6,136,661', '0', '1');
INSERT INTO `zz_area` VALUES ('662', '赤城县', '136', '6,136,662', '0', '1');
INSERT INTO `zz_area` VALUES ('663', '崇礼县', '136', '6,136,663', '0', '1');
INSERT INTO `zz_area` VALUES ('664', '双桥区', '137', '6,137,664', '0', '1');
INSERT INTO `zz_area` VALUES ('665', '双滦区', '137', '6,137,665', '0', '1');
INSERT INTO `zz_area` VALUES ('666', '鹰手营子矿区', '137', '6,137,666', '0', '1');
INSERT INTO `zz_area` VALUES ('667', '承德县', '137', '6,137,667', '0', '1');
INSERT INTO `zz_area` VALUES ('668', '兴隆县', '137', '6,137,668', '0', '1');
INSERT INTO `zz_area` VALUES ('669', '平泉县', '137', '6,137,669', '0', '1');
INSERT INTO `zz_area` VALUES ('670', '滦平县', '137', '6,137,670', '0', '1');
INSERT INTO `zz_area` VALUES ('671', '隆化县', '137', '6,137,671', '0', '1');
INSERT INTO `zz_area` VALUES ('672', '丰宁县', '137', '6,137,672', '0', '1');
INSERT INTO `zz_area` VALUES ('673', '宽城县', '137', '6,137,673', '0', '1');
INSERT INTO `zz_area` VALUES ('674', '围场县', '137', '6,137,674', '0', '1');
INSERT INTO `zz_area` VALUES ('675', '新华区', '138', '6,138,675', '0', '1');
INSERT INTO `zz_area` VALUES ('676', '运河区', '138', '6,138,676', '0', '1');
INSERT INTO `zz_area` VALUES ('677', '沧县', '138', '6,138,677', '0', '1');
INSERT INTO `zz_area` VALUES ('678', '青县', '138', '6,138,678', '0', '1');
INSERT INTO `zz_area` VALUES ('679', '东光县', '138', '6,138,679', '0', '1');
INSERT INTO `zz_area` VALUES ('680', '海兴县', '138', '6,138,680', '0', '1');
INSERT INTO `zz_area` VALUES ('681', '盐山县', '138', '6,138,681', '0', '1');
INSERT INTO `zz_area` VALUES ('682', '肃宁县', '138', '6,138,682', '0', '1');
INSERT INTO `zz_area` VALUES ('683', '南皮县', '138', '6,138,683', '0', '1');
INSERT INTO `zz_area` VALUES ('684', '吴桥县', '138', '6,138,684', '0', '1');
INSERT INTO `zz_area` VALUES ('685', '献县', '138', '6,138,685', '0', '1');
INSERT INTO `zz_area` VALUES ('686', '孟村县', '138', '6,138,686', '0', '1');
INSERT INTO `zz_area` VALUES ('687', '泊头市', '138', '6,138,687', '0', '1');
INSERT INTO `zz_area` VALUES ('688', '任丘市', '138', '6,138,688', '0', '1');
INSERT INTO `zz_area` VALUES ('689', '黄骅市', '138', '6,138,689', '0', '1');
INSERT INTO `zz_area` VALUES ('690', '河间市', '138', '6,138,690', '0', '1');
INSERT INTO `zz_area` VALUES ('691', '安次区', '139', '6,139,691', '0', '1');
INSERT INTO `zz_area` VALUES ('692', '广阳区', '139', '6,139,692', '0', '1');
INSERT INTO `zz_area` VALUES ('693', '固安县', '139', '6,139,693', '0', '1');
INSERT INTO `zz_area` VALUES ('694', '永清县', '139', '6,139,694', '0', '1');
INSERT INTO `zz_area` VALUES ('695', '香河县', '139', '6,139,695', '0', '1');
INSERT INTO `zz_area` VALUES ('696', '大城县', '139', '6,139,696', '0', '1');
INSERT INTO `zz_area` VALUES ('697', '文安县', '139', '6,139,697', '0', '1');
INSERT INTO `zz_area` VALUES ('698', '大厂县', '139', '6,139,698', '0', '1');
INSERT INTO `zz_area` VALUES ('699', '霸州市', '139', '6,139,699', '0', '1');
INSERT INTO `zz_area` VALUES ('700', '三河市', '139', '6,139,700', '0', '1');
INSERT INTO `zz_area` VALUES ('701', '桃城区', '140', '6,140,701', '0', '1');
INSERT INTO `zz_area` VALUES ('702', '枣强县', '140', '6,140,702', '0', '1');
INSERT INTO `zz_area` VALUES ('703', '武邑县', '140', '6,140,703', '0', '1');
INSERT INTO `zz_area` VALUES ('704', '武强县', '140', '6,140,704', '0', '1');
INSERT INTO `zz_area` VALUES ('705', '饶阳县', '140', '6,140,705', '0', '1');
INSERT INTO `zz_area` VALUES ('706', '安平县', '140', '6,140,706', '0', '1');
INSERT INTO `zz_area` VALUES ('707', '故城县', '140', '6,140,707', '0', '1');
INSERT INTO `zz_area` VALUES ('708', '景县', '140', '6,140,708', '0', '1');
INSERT INTO `zz_area` VALUES ('709', '阜城县', '140', '6,140,709', '0', '1');
INSERT INTO `zz_area` VALUES ('710', '冀州市', '140', '6,140,710', '0', '1');
INSERT INTO `zz_area` VALUES ('711', '深州市', '140', '6,140,711', '0', '1');
INSERT INTO `zz_area` VALUES ('712', '小店区', '141', '7,141,712', '0', '1');
INSERT INTO `zz_area` VALUES ('713', '迎泽区', '141', '7,141,713', '0', '1');
INSERT INTO `zz_area` VALUES ('714', '杏花岭区', '141', '7,141,714', '0', '1');
INSERT INTO `zz_area` VALUES ('715', '尖草坪区', '141', '7,141,715', '0', '1');
INSERT INTO `zz_area` VALUES ('716', '万柏林区', '141', '7,141,716', '0', '1');
INSERT INTO `zz_area` VALUES ('717', '晋源区', '141', '7,141,717', '0', '1');
INSERT INTO `zz_area` VALUES ('718', '清徐县', '141', '7,141,718', '0', '1');
INSERT INTO `zz_area` VALUES ('719', '阳曲县', '141', '7,141,719', '0', '1');
INSERT INTO `zz_area` VALUES ('720', '娄烦县', '141', '7,141,720', '0', '1');
INSERT INTO `zz_area` VALUES ('721', '古交市', '141', '7,141,721', '0', '1');
INSERT INTO `zz_area` VALUES ('722', '城区', '142', '7,142,722', '0', '1');
INSERT INTO `zz_area` VALUES ('723', '矿区', '142', '7,142,723', '0', '1');
INSERT INTO `zz_area` VALUES ('724', '南郊区', '142', '7,142,724', '0', '1');
INSERT INTO `zz_area` VALUES ('725', '新荣区', '142', '7,142,725', '0', '1');
INSERT INTO `zz_area` VALUES ('726', '阳高县', '142', '7,142,726', '0', '1');
INSERT INTO `zz_area` VALUES ('727', '天镇县', '142', '7,142,727', '0', '1');
INSERT INTO `zz_area` VALUES ('728', '广灵县', '142', '7,142,728', '0', '1');
INSERT INTO `zz_area` VALUES ('729', '灵丘县', '142', '7,142,729', '0', '1');
INSERT INTO `zz_area` VALUES ('730', '浑源县', '142', '7,142,730', '0', '1');
INSERT INTO `zz_area` VALUES ('731', '左云县', '142', '7,142,731', '0', '1');
INSERT INTO `zz_area` VALUES ('732', '大同县', '142', '7,142,732', '0', '1');
INSERT INTO `zz_area` VALUES ('733', '城区', '143', '7,143,733', '0', '1');
INSERT INTO `zz_area` VALUES ('734', '矿区', '143', '7,143,734', '0', '1');
INSERT INTO `zz_area` VALUES ('735', '郊区', '143', '7,143,735', '0', '1');
INSERT INTO `zz_area` VALUES ('736', '平定县', '143', '7,143,736', '0', '1');
INSERT INTO `zz_area` VALUES ('737', '盂县', '143', '7,143,737', '0', '1');
INSERT INTO `zz_area` VALUES ('738', '城区', '144', '7,144,738', '0', '1');
INSERT INTO `zz_area` VALUES ('739', '郊区', '144', '7,144,739', '0', '1');
INSERT INTO `zz_area` VALUES ('740', '长治县', '144', '7,144,740', '0', '1');
INSERT INTO `zz_area` VALUES ('741', '襄垣县', '144', '7,144,741', '0', '1');
INSERT INTO `zz_area` VALUES ('742', '屯留县', '144', '7,144,742', '0', '1');
INSERT INTO `zz_area` VALUES ('743', '平顺县', '144', '7,144,743', '0', '1');
INSERT INTO `zz_area` VALUES ('744', '黎城县', '144', '7,144,744', '0', '1');
INSERT INTO `zz_area` VALUES ('745', '壶关县', '144', '7,144,745', '0', '1');
INSERT INTO `zz_area` VALUES ('746', '长子县', '144', '7,144,746', '0', '1');
INSERT INTO `zz_area` VALUES ('747', '武乡县', '144', '7,144,747', '0', '1');
INSERT INTO `zz_area` VALUES ('748', '沁县', '144', '7,144,748', '0', '1');
INSERT INTO `zz_area` VALUES ('749', '沁源县', '144', '7,144,749', '0', '1');
INSERT INTO `zz_area` VALUES ('750', '潞城市', '144', '7,144,750', '0', '1');
INSERT INTO `zz_area` VALUES ('751', '城区', '145', '7,145,751', '0', '1');
INSERT INTO `zz_area` VALUES ('752', '沁水县', '145', '7,145,752', '0', '1');
INSERT INTO `zz_area` VALUES ('753', '阳城县', '145', '7,145,753', '0', '1');
INSERT INTO `zz_area` VALUES ('754', '陵川县', '145', '7,145,754', '0', '1');
INSERT INTO `zz_area` VALUES ('755', '泽州县', '145', '7,145,755', '0', '1');
INSERT INTO `zz_area` VALUES ('756', '高平市', '145', '7,145,756', '0', '1');
INSERT INTO `zz_area` VALUES ('757', '朔城区', '146', '7,146,757', '0', '1');
INSERT INTO `zz_area` VALUES ('758', '平鲁区', '146', '7,146,758', '0', '1');
INSERT INTO `zz_area` VALUES ('759', '山阴县', '146', '7,146,759', '0', '1');
INSERT INTO `zz_area` VALUES ('760', '应县', '146', '7,146,760', '0', '1');
INSERT INTO `zz_area` VALUES ('761', '右玉县', '146', '7,146,761', '0', '1');
INSERT INTO `zz_area` VALUES ('762', '怀仁县', '146', '7,146,762', '0', '1');
INSERT INTO `zz_area` VALUES ('763', '榆次区', '147', '7,147,763', '0', '1');
INSERT INTO `zz_area` VALUES ('764', '榆社县', '147', '7,147,764', '0', '1');
INSERT INTO `zz_area` VALUES ('765', '左权县', '147', '7,147,765', '0', '1');
INSERT INTO `zz_area` VALUES ('766', '和顺县', '147', '7,147,766', '0', '1');
INSERT INTO `zz_area` VALUES ('767', '昔阳县', '147', '7,147,767', '0', '1');
INSERT INTO `zz_area` VALUES ('768', '寿阳县', '147', '7,147,768', '0', '1');
INSERT INTO `zz_area` VALUES ('769', '太谷县', '147', '7,147,769', '0', '1');
INSERT INTO `zz_area` VALUES ('770', '祁县', '147', '7,147,770', '0', '1');
INSERT INTO `zz_area` VALUES ('771', '平遥县', '147', '7,147,771', '0', '1');
INSERT INTO `zz_area` VALUES ('772', '灵石县', '147', '7,147,772', '0', '1');
INSERT INTO `zz_area` VALUES ('773', '介休市', '147', '7,147,773', '0', '1');
INSERT INTO `zz_area` VALUES ('774', '盐湖区', '148', '7,148,774', '0', '1');
INSERT INTO `zz_area` VALUES ('775', '临猗县', '148', '7,148,775', '0', '1');
INSERT INTO `zz_area` VALUES ('776', '万荣县', '148', '7,148,776', '0', '1');
INSERT INTO `zz_area` VALUES ('777', '闻喜县', '148', '7,148,777', '0', '1');
INSERT INTO `zz_area` VALUES ('778', '稷山县', '148', '7,148,778', '0', '1');
INSERT INTO `zz_area` VALUES ('779', '新绛县', '148', '7,148,779', '0', '1');
INSERT INTO `zz_area` VALUES ('780', '绛县', '148', '7,148,780', '0', '1');
INSERT INTO `zz_area` VALUES ('781', '垣曲县', '148', '7,148,781', '0', '1');
INSERT INTO `zz_area` VALUES ('782', '夏县', '148', '7,148,782', '0', '1');
INSERT INTO `zz_area` VALUES ('783', '平陆县', '148', '7,148,783', '0', '1');
INSERT INTO `zz_area` VALUES ('784', '芮城县', '148', '7,148,784', '0', '1');
INSERT INTO `zz_area` VALUES ('785', '永济市', '148', '7,148,785', '0', '1');
INSERT INTO `zz_area` VALUES ('786', '河津市', '148', '7,148,786', '0', '1');
INSERT INTO `zz_area` VALUES ('787', '忻府区', '149', '7,149,787', '0', '1');
INSERT INTO `zz_area` VALUES ('788', '定襄县', '149', '7,149,788', '0', '1');
INSERT INTO `zz_area` VALUES ('789', '五台县', '149', '7,149,789', '0', '1');
INSERT INTO `zz_area` VALUES ('790', '代县', '149', '7,149,790', '0', '1');
INSERT INTO `zz_area` VALUES ('791', '繁峙县', '149', '7,149,791', '0', '1');
INSERT INTO `zz_area` VALUES ('792', '宁武县', '149', '7,149,792', '0', '1');
INSERT INTO `zz_area` VALUES ('793', '静乐县', '149', '7,149,793', '0', '1');
INSERT INTO `zz_area` VALUES ('794', '神池县', '149', '7,149,794', '0', '1');
INSERT INTO `zz_area` VALUES ('795', '五寨县', '149', '7,149,795', '0', '1');
INSERT INTO `zz_area` VALUES ('796', '岢岚县', '149', '7,149,796', '0', '1');
INSERT INTO `zz_area` VALUES ('797', '河曲县', '149', '7,149,797', '0', '1');
INSERT INTO `zz_area` VALUES ('798', '保德县', '149', '7,149,798', '0', '1');
INSERT INTO `zz_area` VALUES ('799', '偏关县', '149', '7,149,799', '0', '1');
INSERT INTO `zz_area` VALUES ('800', '原平市', '149', '7,149,800', '0', '1');
INSERT INTO `zz_area` VALUES ('801', '尧都区', '150', '7,150,801', '0', '1');
INSERT INTO `zz_area` VALUES ('802', '曲沃县', '150', '7,150,802', '0', '1');
INSERT INTO `zz_area` VALUES ('803', '翼城县', '150', '7,150,803', '0', '1');
INSERT INTO `zz_area` VALUES ('804', '襄汾县', '150', '7,150,804', '0', '1');
INSERT INTO `zz_area` VALUES ('805', '洪洞县', '150', '7,150,805', '0', '1');
INSERT INTO `zz_area` VALUES ('806', '古县', '150', '7,150,806', '0', '1');
INSERT INTO `zz_area` VALUES ('807', '安泽县', '150', '7,150,807', '0', '1');
INSERT INTO `zz_area` VALUES ('808', '浮山县', '150', '7,150,808', '0', '1');
INSERT INTO `zz_area` VALUES ('809', '吉县', '150', '7,150,809', '0', '1');
INSERT INTO `zz_area` VALUES ('810', '乡宁县', '150', '7,150,810', '0', '1');
INSERT INTO `zz_area` VALUES ('811', '大宁县', '150', '7,150,811', '0', '1');
INSERT INTO `zz_area` VALUES ('812', '隰县', '150', '7,150,812', '0', '1');
INSERT INTO `zz_area` VALUES ('813', '永和县', '150', '7,150,813', '0', '1');
INSERT INTO `zz_area` VALUES ('814', '蒲县', '150', '7,150,814', '0', '1');
INSERT INTO `zz_area` VALUES ('815', '汾西县', '150', '7,150,815', '0', '1');
INSERT INTO `zz_area` VALUES ('816', '侯马市', '150', '7,150,816', '0', '1');
INSERT INTO `zz_area` VALUES ('817', '霍州市', '150', '7,150,817', '0', '1');
INSERT INTO `zz_area` VALUES ('818', '离石区', '151', '7,151,818', '0', '1');
INSERT INTO `zz_area` VALUES ('819', '文水县', '151', '7,151,819', '0', '1');
INSERT INTO `zz_area` VALUES ('820', '交城县', '151', '7,151,820', '0', '1');
INSERT INTO `zz_area` VALUES ('821', '兴县', '151', '7,151,821', '0', '1');
INSERT INTO `zz_area` VALUES ('822', '临县', '151', '7,151,822', '0', '1');
INSERT INTO `zz_area` VALUES ('823', '柳林县', '151', '7,151,823', '0', '1');
INSERT INTO `zz_area` VALUES ('824', '石楼县', '151', '7,151,824', '0', '1');
INSERT INTO `zz_area` VALUES ('825', '岚县', '151', '7,151,825', '0', '1');
INSERT INTO `zz_area` VALUES ('826', '方山县', '151', '7,151,826', '0', '1');
INSERT INTO `zz_area` VALUES ('827', '中阳县', '151', '7,151,827', '0', '1');
INSERT INTO `zz_area` VALUES ('828', '交口县', '151', '7,151,828', '0', '1');
INSERT INTO `zz_area` VALUES ('829', '孝义市', '151', '7,151,829', '0', '1');
INSERT INTO `zz_area` VALUES ('830', '汾阳市', '151', '7,151,830', '0', '1');
INSERT INTO `zz_area` VALUES ('831', '新城区', '152', '8,152,831', '0', '1');
INSERT INTO `zz_area` VALUES ('832', '回民区', '152', '8,152,832', '0', '1');
INSERT INTO `zz_area` VALUES ('833', '玉泉区', '152', '8,152,833', '0', '1');
INSERT INTO `zz_area` VALUES ('834', '赛罕区', '152', '8,152,834', '0', '1');
INSERT INTO `zz_area` VALUES ('835', '土默特左旗', '152', '8,152,835', '0', '1');
INSERT INTO `zz_area` VALUES ('836', '托克托县', '152', '8,152,836', '0', '1');
INSERT INTO `zz_area` VALUES ('837', '和林格尔县', '152', '8,152,837', '0', '1');
INSERT INTO `zz_area` VALUES ('838', '清水河县', '152', '8,152,838', '0', '1');
INSERT INTO `zz_area` VALUES ('839', '武川县', '152', '8,152,839', '0', '1');
INSERT INTO `zz_area` VALUES ('840', '东河区', '153', '8,153,840', '0', '1');
INSERT INTO `zz_area` VALUES ('841', '昆都仑区', '153', '8,153,841', '0', '1');
INSERT INTO `zz_area` VALUES ('842', '青山区', '153', '8,153,842', '0', '1');
INSERT INTO `zz_area` VALUES ('843', '石拐区', '153', '8,153,843', '0', '1');
INSERT INTO `zz_area` VALUES ('844', '白云矿区', '153', '8,153,844', '0', '1');
INSERT INTO `zz_area` VALUES ('845', '九原区', '153', '8,153,845', '0', '1');
INSERT INTO `zz_area` VALUES ('846', '土默特右旗', '153', '8,153,846', '0', '1');
INSERT INTO `zz_area` VALUES ('847', '固阳县', '153', '8,153,847', '0', '1');
INSERT INTO `zz_area` VALUES ('848', '达尔罕茂明安联合旗', '153', '8,153,848', '0', '1');
INSERT INTO `zz_area` VALUES ('849', '海勃湾区', '154', '8,154,849', '0', '1');
INSERT INTO `zz_area` VALUES ('850', '海南区', '154', '8,154,850', '0', '1');
INSERT INTO `zz_area` VALUES ('851', '乌达区', '154', '8,154,851', '0', '1');
INSERT INTO `zz_area` VALUES ('852', '红山区', '155', '8,155,852', '0', '1');
INSERT INTO `zz_area` VALUES ('853', '元宝山区', '155', '8,155,853', '0', '1');
INSERT INTO `zz_area` VALUES ('854', '松山区', '155', '8,155,854', '0', '1');
INSERT INTO `zz_area` VALUES ('855', '阿鲁科尔沁旗', '155', '8,155,855', '0', '1');
INSERT INTO `zz_area` VALUES ('856', '巴林左旗', '155', '8,155,856', '0', '1');
INSERT INTO `zz_area` VALUES ('857', '巴林右旗', '155', '8,155,857', '0', '1');
INSERT INTO `zz_area` VALUES ('858', '林西县', '155', '8,155,858', '0', '1');
INSERT INTO `zz_area` VALUES ('859', '克什克腾旗', '155', '8,155,859', '0', '1');
INSERT INTO `zz_area` VALUES ('860', '翁牛特旗', '155', '8,155,860', '0', '1');
INSERT INTO `zz_area` VALUES ('861', '喀喇沁旗', '155', '8,155,861', '0', '1');
INSERT INTO `zz_area` VALUES ('862', '宁城县', '155', '8,155,862', '0', '1');
INSERT INTO `zz_area` VALUES ('863', '敖汉旗', '155', '8,155,863', '0', '1');
INSERT INTO `zz_area` VALUES ('864', '科尔沁区', '156', '8,156,864', '0', '1');
INSERT INTO `zz_area` VALUES ('865', '科尔沁左翼中旗', '156', '8,156,865', '0', '1');
INSERT INTO `zz_area` VALUES ('866', '科尔沁左翼后旗', '156', '8,156,866', '0', '1');
INSERT INTO `zz_area` VALUES ('867', '开鲁县', '156', '8,156,867', '0', '1');
INSERT INTO `zz_area` VALUES ('868', '库伦旗', '156', '8,156,868', '0', '1');
INSERT INTO `zz_area` VALUES ('869', '奈曼旗', '156', '8,156,869', '0', '1');
INSERT INTO `zz_area` VALUES ('870', '扎鲁特旗', '156', '8,156,870', '0', '1');
INSERT INTO `zz_area` VALUES ('871', '霍林郭勒市', '156', '8,156,871', '0', '1');
INSERT INTO `zz_area` VALUES ('872', '东胜区', '157', '8,157,872', '0', '1');
INSERT INTO `zz_area` VALUES ('873', '达拉特旗', '157', '8,157,873', '0', '1');
INSERT INTO `zz_area` VALUES ('874', '准格尔旗', '157', '8,157,874', '0', '1');
INSERT INTO `zz_area` VALUES ('875', '鄂托克前旗', '157', '8,157,875', '0', '1');
INSERT INTO `zz_area` VALUES ('876', '鄂托克旗', '157', '8,157,876', '0', '1');
INSERT INTO `zz_area` VALUES ('877', '杭锦旗', '157', '8,157,877', '0', '1');
INSERT INTO `zz_area` VALUES ('878', '乌审旗', '157', '8,157,878', '0', '1');
INSERT INTO `zz_area` VALUES ('879', '伊金霍洛旗', '157', '8,157,879', '0', '1');
INSERT INTO `zz_area` VALUES ('880', '海拉尔区', '158', '8,158,880', '0', '1');
INSERT INTO `zz_area` VALUES ('881', '阿荣旗', '158', '8,158,881', '0', '1');
INSERT INTO `zz_area` VALUES ('882', '莫力达瓦达斡尔族自治旗', '158', '8,158,882', '0', '1');
INSERT INTO `zz_area` VALUES ('883', '鄂伦春自治旗', '158', '8,158,883', '0', '1');
INSERT INTO `zz_area` VALUES ('884', '鄂温克族自治旗', '158', '8,158,884', '0', '1');
INSERT INTO `zz_area` VALUES ('885', '陈巴尔虎旗', '158', '8,158,885', '0', '1');
INSERT INTO `zz_area` VALUES ('886', '新巴尔虎左旗', '158', '8,158,886', '0', '1');
INSERT INTO `zz_area` VALUES ('887', '新巴尔虎右旗', '158', '8,158,887', '0', '1');
INSERT INTO `zz_area` VALUES ('888', '满洲里市', '158', '8,158,888', '0', '1');
INSERT INTO `zz_area` VALUES ('889', '牙克石市', '158', '8,158,889', '0', '1');
INSERT INTO `zz_area` VALUES ('890', '扎兰屯市', '158', '8,158,890', '0', '1');
INSERT INTO `zz_area` VALUES ('891', '额尔古纳市', '158', '8,158,891', '0', '1');
INSERT INTO `zz_area` VALUES ('892', '根河市', '158', '8,158,892', '0', '1');
INSERT INTO `zz_area` VALUES ('893', '临河区', '159', '8,159,893', '0', '1');
INSERT INTO `zz_area` VALUES ('894', '五原县', '159', '8,159,894', '0', '1');
INSERT INTO `zz_area` VALUES ('895', '磴口县', '159', '8,159,895', '0', '1');
INSERT INTO `zz_area` VALUES ('896', '乌拉特前旗', '159', '8,159,896', '0', '1');
INSERT INTO `zz_area` VALUES ('897', '乌拉特中旗', '159', '8,159,897', '0', '1');
INSERT INTO `zz_area` VALUES ('898', '乌拉特后旗', '159', '8,159,898', '0', '1');
INSERT INTO `zz_area` VALUES ('899', '杭锦后旗', '159', '8,159,899', '0', '1');
INSERT INTO `zz_area` VALUES ('900', '集宁区', '160', '8,160,900', '0', '1');
INSERT INTO `zz_area` VALUES ('901', '卓资县', '160', '8,160,901', '0', '1');
INSERT INTO `zz_area` VALUES ('902', '化德县', '160', '8,160,902', '0', '1');
INSERT INTO `zz_area` VALUES ('903', '商都县', '160', '8,160,903', '0', '1');
INSERT INTO `zz_area` VALUES ('904', '兴和县', '160', '8,160,904', '0', '1');
INSERT INTO `zz_area` VALUES ('905', '凉城县', '160', '8,160,905', '0', '1');
INSERT INTO `zz_area` VALUES ('906', '察哈尔右翼前旗', '160', '8,160,906', '0', '1');
INSERT INTO `zz_area` VALUES ('907', '察哈尔右翼中旗', '160', '8,160,907', '0', '1');
INSERT INTO `zz_area` VALUES ('908', '察哈尔右翼后旗', '160', '8,160,908', '0', '1');
INSERT INTO `zz_area` VALUES ('909', '四子王旗', '160', '8,160,909', '0', '1');
INSERT INTO `zz_area` VALUES ('910', '丰镇市', '160', '8,160,910', '0', '1');
INSERT INTO `zz_area` VALUES ('911', '乌兰浩特市', '161', '8,161,911', '0', '1');
INSERT INTO `zz_area` VALUES ('912', '阿尔山市', '161', '8,161,912', '0', '1');
INSERT INTO `zz_area` VALUES ('913', '科尔沁右翼前旗', '161', '8,161,913', '0', '1');
INSERT INTO `zz_area` VALUES ('914', '科尔沁右翼中旗', '161', '8,161,914', '0', '1');
INSERT INTO `zz_area` VALUES ('915', '扎赉特旗', '161', '8,161,915', '0', '1');
INSERT INTO `zz_area` VALUES ('916', '突泉县', '161', '8,161,916', '0', '1');
INSERT INTO `zz_area` VALUES ('917', '二连浩特市', '162', '8,162,917', '0', '1');
INSERT INTO `zz_area` VALUES ('918', '锡林浩特市', '162', '8,162,918', '0', '1');
INSERT INTO `zz_area` VALUES ('919', '阿巴嘎旗', '162', '8,162,919', '0', '1');
INSERT INTO `zz_area` VALUES ('920', '苏尼特左旗', '162', '8,162,920', '0', '1');
INSERT INTO `zz_area` VALUES ('921', '苏尼特右旗', '162', '8,162,921', '0', '1');
INSERT INTO `zz_area` VALUES ('922', '东乌珠穆沁旗', '162', '8,162,922', '0', '1');
INSERT INTO `zz_area` VALUES ('923', '西乌珠穆沁旗', '162', '8,162,923', '0', '1');
INSERT INTO `zz_area` VALUES ('924', '太仆寺旗', '162', '8,162,924', '0', '1');
INSERT INTO `zz_area` VALUES ('925', '镶黄旗', '162', '8,162,925', '0', '1');
INSERT INTO `zz_area` VALUES ('926', '正镶白旗', '162', '8,162,926', '0', '1');
INSERT INTO `zz_area` VALUES ('927', '正蓝旗', '162', '8,162,927', '0', '1');
INSERT INTO `zz_area` VALUES ('928', '多伦县', '162', '8,162,928', '0', '1');
INSERT INTO `zz_area` VALUES ('929', '阿拉善左旗', '163', '8,163,929', '0', '1');
INSERT INTO `zz_area` VALUES ('930', '阿拉善右旗', '163', '8,163,930', '0', '1');
INSERT INTO `zz_area` VALUES ('931', '额济纳旗', '163', '8,163,931', '0', '1');
INSERT INTO `zz_area` VALUES ('932', '和平区', '164', '9,164,932', '0', '1');
INSERT INTO `zz_area` VALUES ('933', '沈河区', '164', '9,164,933', '0', '1');
INSERT INTO `zz_area` VALUES ('934', '大东区', '164', '9,164,934', '0', '1');
INSERT INTO `zz_area` VALUES ('935', '皇姑区', '164', '9,164,935', '0', '1');
INSERT INTO `zz_area` VALUES ('936', '铁西区', '164', '9,164,936', '0', '1');
INSERT INTO `zz_area` VALUES ('937', '苏家屯区', '164', '9,164,937', '0', '1');
INSERT INTO `zz_area` VALUES ('938', '东陵区', '164', '9,164,938', '0', '1');
INSERT INTO `zz_area` VALUES ('939', '新城子区', '164', '9,164,939', '0', '1');
INSERT INTO `zz_area` VALUES ('940', '于洪区', '164', '9,164,940', '0', '1');
INSERT INTO `zz_area` VALUES ('941', '辽中县', '164', '9,164,941', '0', '1');
INSERT INTO `zz_area` VALUES ('942', '康平县', '164', '9,164,942', '0', '1');
INSERT INTO `zz_area` VALUES ('943', '法库县', '164', '9,164,943', '0', '1');
INSERT INTO `zz_area` VALUES ('944', '新民市', '164', '9,164,944', '0', '1');
INSERT INTO `zz_area` VALUES ('945', '中山区', '165', '9,165,945', '0', '1');
INSERT INTO `zz_area` VALUES ('946', '西岗区', '165', '9,165,946', '0', '1');
INSERT INTO `zz_area` VALUES ('947', '沙河口区', '165', '9,165,947', '0', '1');
INSERT INTO `zz_area` VALUES ('948', '甘井子区', '165', '9,165,948', '0', '1');
INSERT INTO `zz_area` VALUES ('949', '旅顺口区', '165', '9,165,949', '0', '1');
INSERT INTO `zz_area` VALUES ('950', '金州区', '165', '9,165,950', '0', '1');
INSERT INTO `zz_area` VALUES ('951', '长海县', '165', '9,165,951', '0', '1');
INSERT INTO `zz_area` VALUES ('952', '瓦房店市', '165', '9,165,952', '0', '1');
INSERT INTO `zz_area` VALUES ('953', '普兰店市', '165', '9,165,953', '0', '1');
INSERT INTO `zz_area` VALUES ('954', '庄河市', '165', '9,165,954', '0', '1');
INSERT INTO `zz_area` VALUES ('955', '铁东区', '166', '9,166,955', '0', '1');
INSERT INTO `zz_area` VALUES ('956', '铁西区', '166', '9,166,956', '0', '1');
INSERT INTO `zz_area` VALUES ('957', '立山区', '166', '9,166,957', '0', '1');
INSERT INTO `zz_area` VALUES ('958', '千山区', '166', '9,166,958', '0', '1');
INSERT INTO `zz_area` VALUES ('959', '台安县', '166', '9,166,959', '0', '1');
INSERT INTO `zz_area` VALUES ('960', '岫岩满族自治县', '166', '9,166,960', '0', '1');
INSERT INTO `zz_area` VALUES ('961', '海城市', '166', '9,166,961', '0', '1');
INSERT INTO `zz_area` VALUES ('962', '新抚区', '167', '9,167,962', '0', '1');
INSERT INTO `zz_area` VALUES ('963', '东洲区', '167', '9,167,963', '0', '1');
INSERT INTO `zz_area` VALUES ('964', '望花区', '167', '9,167,964', '0', '1');
INSERT INTO `zz_area` VALUES ('965', '顺城区', '167', '9,167,965', '0', '1');
INSERT INTO `zz_area` VALUES ('966', '抚顺县', '167', '9,167,966', '0', '1');
INSERT INTO `zz_area` VALUES ('967', '新宾满族自治县', '167', '9,167,967', '0', '1');
INSERT INTO `zz_area` VALUES ('968', '清原满族自治县', '167', '9,167,968', '0', '1');
INSERT INTO `zz_area` VALUES ('969', '平山区', '168', '9,168,969', '0', '1');
INSERT INTO `zz_area` VALUES ('970', '溪湖区', '168', '9,168,970', '0', '1');
INSERT INTO `zz_area` VALUES ('971', '明山区', '168', '9,168,971', '0', '1');
INSERT INTO `zz_area` VALUES ('972', '南芬区', '168', '9,168,972', '0', '1');
INSERT INTO `zz_area` VALUES ('973', '本溪满族自治县', '168', '9,168,973', '0', '1');
INSERT INTO `zz_area` VALUES ('974', '桓仁满族自治县', '168', '9,168,974', '0', '1');
INSERT INTO `zz_area` VALUES ('975', '元宝区', '169', '9,169,975', '0', '1');
INSERT INTO `zz_area` VALUES ('976', '振兴区', '169', '9,169,976', '0', '1');
INSERT INTO `zz_area` VALUES ('977', '振安区', '169', '9,169,977', '0', '1');
INSERT INTO `zz_area` VALUES ('978', '宽甸满族自治县', '169', '9,169,978', '0', '1');
INSERT INTO `zz_area` VALUES ('979', '东港市', '169', '9,169,979', '0', '1');
INSERT INTO `zz_area` VALUES ('980', '凤城市', '169', '9,169,980', '0', '1');
INSERT INTO `zz_area` VALUES ('981', '古塔区', '170', '9,170,981', '0', '1');
INSERT INTO `zz_area` VALUES ('982', '凌河区', '170', '9,170,982', '0', '1');
INSERT INTO `zz_area` VALUES ('983', '太和区', '170', '9,170,983', '0', '1');
INSERT INTO `zz_area` VALUES ('984', '黑山县', '170', '9,170,984', '0', '1');
INSERT INTO `zz_area` VALUES ('985', '义县', '170', '9,170,985', '0', '1');
INSERT INTO `zz_area` VALUES ('986', '凌海市', '170', '9,170,986', '0', '1');
INSERT INTO `zz_area` VALUES ('987', '北镇市', '170', '9,170,987', '0', '1');
INSERT INTO `zz_area` VALUES ('988', '站前区', '171', '9,171,988', '0', '1');
INSERT INTO `zz_area` VALUES ('989', '西市区', '171', '9,171,989', '0', '1');
INSERT INTO `zz_area` VALUES ('990', '鲅鱼圈区', '171', '9,171,990', '0', '1');
INSERT INTO `zz_area` VALUES ('991', '老边区', '171', '9,171,991', '0', '1');
INSERT INTO `zz_area` VALUES ('992', '盖州市', '171', '9,171,992', '0', '1');
INSERT INTO `zz_area` VALUES ('993', '大石桥市', '171', '9,171,993', '0', '1');
INSERT INTO `zz_area` VALUES ('994', '海州区', '172', '9,172,994', '0', '1');
INSERT INTO `zz_area` VALUES ('995', '新邱区', '172', '9,172,995', '0', '1');
INSERT INTO `zz_area` VALUES ('996', '太平区', '172', '9,172,996', '0', '1');
INSERT INTO `zz_area` VALUES ('997', '清河门区', '172', '9,172,997', '0', '1');
INSERT INTO `zz_area` VALUES ('998', '细河区', '172', '9,172,998', '0', '1');
INSERT INTO `zz_area` VALUES ('999', '阜新蒙古族自治县', '172', '9,172,999', '0', '1');
INSERT INTO `zz_area` VALUES ('1000', '彰武县', '172', '9,172,1000', '0', '1');
INSERT INTO `zz_area` VALUES ('1001', '白塔区', '173', '9,173,1001', '0', '1');
INSERT INTO `zz_area` VALUES ('1002', '文圣区', '173', '9,173,1002', '0', '1');
INSERT INTO `zz_area` VALUES ('1003', '宏伟区', '173', '9,173,1003', '0', '1');
INSERT INTO `zz_area` VALUES ('1004', '弓长岭区', '173', '9,173,1004', '0', '1');
INSERT INTO `zz_area` VALUES ('1005', '太子河区', '173', '9,173,1005', '0', '1');
INSERT INTO `zz_area` VALUES ('1006', '辽阳县', '173', '9,173,1006', '0', '1');
INSERT INTO `zz_area` VALUES ('1007', '灯塔市', '173', '9,173,1007', '0', '1');
INSERT INTO `zz_area` VALUES ('1008', '双台子区', '174', '9,174,1008', '0', '1');
INSERT INTO `zz_area` VALUES ('1009', '兴隆台区', '174', '9,174,1009', '0', '1');
INSERT INTO `zz_area` VALUES ('1010', '大洼县', '174', '9,174,1010', '0', '1');
INSERT INTO `zz_area` VALUES ('1011', '盘山县', '174', '9,174,1011', '0', '1');
INSERT INTO `zz_area` VALUES ('1012', '银州区', '175', '9,175,1012', '0', '1');
INSERT INTO `zz_area` VALUES ('1013', '清河区', '175', '9,175,1013', '0', '1');
INSERT INTO `zz_area` VALUES ('1014', '铁岭县', '175', '9,175,1014', '0', '1');
INSERT INTO `zz_area` VALUES ('1015', '西丰县', '175', '9,175,1015', '0', '1');
INSERT INTO `zz_area` VALUES ('1016', '昌图县', '175', '9,175,1016', '0', '1');
INSERT INTO `zz_area` VALUES ('1017', '调兵山市', '175', '9,175,1017', '0', '1');
INSERT INTO `zz_area` VALUES ('1018', '开原市', '175', '9,175,1018', '0', '1');
INSERT INTO `zz_area` VALUES ('1019', '双塔区', '176', '9,176,1019', '0', '1');
INSERT INTO `zz_area` VALUES ('1020', '龙城区', '176', '9,176,1020', '0', '1');
INSERT INTO `zz_area` VALUES ('1021', '朝阳县', '176', '9,176,1021', '0', '1');
INSERT INTO `zz_area` VALUES ('1022', '建平县', '176', '9,176,1022', '0', '1');
INSERT INTO `zz_area` VALUES ('1023', '喀喇沁左翼蒙古族自治县', '176', '9,176,1023', '0', '1');
INSERT INTO `zz_area` VALUES ('1024', '北票市', '176', '9,176,1024', '0', '1');
INSERT INTO `zz_area` VALUES ('1025', '凌源市', '176', '9,176,1025', '0', '1');
INSERT INTO `zz_area` VALUES ('1026', '连山区', '177', '9,177,1026', '0', '1');
INSERT INTO `zz_area` VALUES ('1027', '龙港区', '177', '9,177,1027', '0', '1');
INSERT INTO `zz_area` VALUES ('1028', '南票区', '177', '9,177,1028', '0', '1');
INSERT INTO `zz_area` VALUES ('1029', '绥中县', '177', '9,177,1029', '0', '1');
INSERT INTO `zz_area` VALUES ('1030', '建昌县', '177', '9,177,1030', '0', '1');
INSERT INTO `zz_area` VALUES ('1031', '兴城市', '177', '9,177,1031', '0', '1');
INSERT INTO `zz_area` VALUES ('1032', '南关区', '178', '10,178,1032', '0', '1');
INSERT INTO `zz_area` VALUES ('1033', '宽城区', '178', '10,178,1033', '0', '1');
INSERT INTO `zz_area` VALUES ('1034', '朝阳区', '178', '10,178,1034', '0', '1');
INSERT INTO `zz_area` VALUES ('1035', '二道区', '178', '10,178,1035', '0', '1');
INSERT INTO `zz_area` VALUES ('1036', '绿园区', '178', '10,178,1036', '0', '1');
INSERT INTO `zz_area` VALUES ('1037', '双阳区', '178', '10,178,1037', '0', '1');
INSERT INTO `zz_area` VALUES ('1038', '农安县', '178', '10,178,1038', '0', '1');
INSERT INTO `zz_area` VALUES ('1039', '九台市', '178', '10,178,1039', '0', '1');
INSERT INTO `zz_area` VALUES ('1040', '榆树市', '178', '10,178,1040', '0', '1');
INSERT INTO `zz_area` VALUES ('1041', '德惠市', '178', '10,178,1041', '0', '1');
INSERT INTO `zz_area` VALUES ('1042', '昌邑区', '179', '10,179,1042', '0', '1');
INSERT INTO `zz_area` VALUES ('1043', '龙潭区', '179', '10,179,1043', '0', '1');
INSERT INTO `zz_area` VALUES ('1044', '船营区', '179', '10,179,1044', '0', '1');
INSERT INTO `zz_area` VALUES ('1045', '丰满区', '179', '10,179,1045', '0', '1');
INSERT INTO `zz_area` VALUES ('1046', '永吉县', '179', '10,179,1046', '0', '1');
INSERT INTO `zz_area` VALUES ('1047', '蛟河市', '179', '10,179,1047', '0', '1');
INSERT INTO `zz_area` VALUES ('1048', '桦甸市', '179', '10,179,1048', '0', '1');
INSERT INTO `zz_area` VALUES ('1049', '舒兰市', '179', '10,179,1049', '0', '1');
INSERT INTO `zz_area` VALUES ('1050', '磐石市', '179', '10,179,1050', '0', '1');
INSERT INTO `zz_area` VALUES ('1051', '铁西区', '180', '10,180,1051', '0', '1');
INSERT INTO `zz_area` VALUES ('1052', '铁东区', '180', '10,180,1052', '0', '1');
INSERT INTO `zz_area` VALUES ('1053', '梨树县', '180', '10,180,1053', '0', '1');
INSERT INTO `zz_area` VALUES ('1054', '伊通满族自治县', '180', '10,180,1054', '0', '1');
INSERT INTO `zz_area` VALUES ('1055', '公主岭市', '180', '10,180,1055', '0', '1');
INSERT INTO `zz_area` VALUES ('1056', '双辽市', '180', '10,180,1056', '0', '1');
INSERT INTO `zz_area` VALUES ('1057', '龙山区', '181', '10,181,1057', '0', '1');
INSERT INTO `zz_area` VALUES ('1058', '西安区', '181', '10,181,1058', '0', '1');
INSERT INTO `zz_area` VALUES ('1059', '东丰县', '181', '10,181,1059', '0', '1');
INSERT INTO `zz_area` VALUES ('1060', '东辽县', '181', '10,181,1060', '0', '1');
INSERT INTO `zz_area` VALUES ('1061', '东昌区', '182', '10,182,1061', '0', '1');
INSERT INTO `zz_area` VALUES ('1062', '二道江区', '182', '10,182,1062', '0', '1');
INSERT INTO `zz_area` VALUES ('1063', '通化县', '182', '10,182,1063', '0', '1');
INSERT INTO `zz_area` VALUES ('1064', '辉南县', '182', '10,182,1064', '0', '1');
INSERT INTO `zz_area` VALUES ('1065', '柳河县', '182', '10,182,1065', '0', '1');
INSERT INTO `zz_area` VALUES ('1066', '梅河口市', '182', '10,182,1066', '0', '1');
INSERT INTO `zz_area` VALUES ('1067', '集安市', '182', '10,182,1067', '0', '1');
INSERT INTO `zz_area` VALUES ('1068', '八道江区', '183', '10,183,1068', '0', '1');
INSERT INTO `zz_area` VALUES ('1069', '抚松县', '183', '10,183,1069', '0', '1');
INSERT INTO `zz_area` VALUES ('1070', '靖宇县', '183', '10,183,1070', '0', '1');
INSERT INTO `zz_area` VALUES ('1071', '长白朝鲜族自治县', '183', '10,183,1071', '0', '1');
INSERT INTO `zz_area` VALUES ('1072', '江源县', '183', '10,183,1072', '0', '1');
INSERT INTO `zz_area` VALUES ('1073', '临江市', '183', '10,183,1073', '0', '1');
INSERT INTO `zz_area` VALUES ('1074', '宁江区', '184', '10,184,1074', '0', '1');
INSERT INTO `zz_area` VALUES ('1075', '前郭尔罗斯蒙古族自治县', '184', '10,184,1075', '0', '1');
INSERT INTO `zz_area` VALUES ('1076', '长岭县', '184', '10,184,1076', '0', '1');
INSERT INTO `zz_area` VALUES ('1077', '乾安县', '184', '10,184,1077', '0', '1');
INSERT INTO `zz_area` VALUES ('1078', '扶余县', '184', '10,184,1078', '0', '1');
INSERT INTO `zz_area` VALUES ('1079', '洮北区', '185', '10,185,1079', '0', '1');
INSERT INTO `zz_area` VALUES ('1080', '镇赉县', '185', '10,185,1080', '0', '1');
INSERT INTO `zz_area` VALUES ('1081', '通榆县', '185', '10,185,1081', '0', '1');
INSERT INTO `zz_area` VALUES ('1082', '洮南市', '185', '10,185,1082', '0', '1');
INSERT INTO `zz_area` VALUES ('1083', '大安市', '185', '10,185,1083', '0', '1');
INSERT INTO `zz_area` VALUES ('1084', '延吉市', '186', '10,186,1084', '0', '1');
INSERT INTO `zz_area` VALUES ('1085', '图们市', '186', '10,186,1085', '0', '1');
INSERT INTO `zz_area` VALUES ('1086', '敦化市', '186', '10,186,1086', '0', '1');
INSERT INTO `zz_area` VALUES ('1087', '珲春市', '186', '10,186,1087', '0', '1');
INSERT INTO `zz_area` VALUES ('1088', '龙井市', '186', '10,186,1088', '0', '1');
INSERT INTO `zz_area` VALUES ('1089', '和龙市', '186', '10,186,1089', '0', '1');
INSERT INTO `zz_area` VALUES ('1090', '汪清县', '186', '10,186,1090', '0', '1');
INSERT INTO `zz_area` VALUES ('1091', '安图县', '186', '10,186,1091', '0', '1');
INSERT INTO `zz_area` VALUES ('1092', '道里区', '187', '11,187,1092', '0', '1');
INSERT INTO `zz_area` VALUES ('1093', '南岗区', '187', '11,187,1093', '0', '1');
INSERT INTO `zz_area` VALUES ('1094', '道外区', '187', '11,187,1094', '0', '1');
INSERT INTO `zz_area` VALUES ('1095', '香坊区', '187', '11,187,1095', '0', '1');
INSERT INTO `zz_area` VALUES ('1096', '动力区', '187', '11,187,1096', '0', '1');
INSERT INTO `zz_area` VALUES ('1097', '平房区', '187', '11,187,1097', '0', '1');
INSERT INTO `zz_area` VALUES ('1098', '松北区', '187', '11,187,1098', '0', '1');
INSERT INTO `zz_area` VALUES ('1099', '呼兰区', '187', '11,187,1099', '0', '1');
INSERT INTO `zz_area` VALUES ('1100', '依兰县', '187', '11,187,1100', '0', '1');
INSERT INTO `zz_area` VALUES ('1101', '方正县', '187', '11,187,1101', '0', '1');
INSERT INTO `zz_area` VALUES ('1102', '宾县', '187', '11,187,1102', '0', '1');
INSERT INTO `zz_area` VALUES ('1103', '巴彦县', '187', '11,187,1103', '0', '1');
INSERT INTO `zz_area` VALUES ('1104', '木兰县', '187', '11,187,1104', '0', '1');
INSERT INTO `zz_area` VALUES ('1105', '通河县', '187', '11,187,1105', '0', '1');
INSERT INTO `zz_area` VALUES ('1106', '延寿县', '187', '11,187,1106', '0', '1');
INSERT INTO `zz_area` VALUES ('1107', '阿城市', '187', '11,187,1107', '0', '1');
INSERT INTO `zz_area` VALUES ('1108', '双城市', '187', '11,187,1108', '0', '1');
INSERT INTO `zz_area` VALUES ('1109', '尚志市', '187', '11,187,1109', '0', '1');
INSERT INTO `zz_area` VALUES ('1110', '五常市', '187', '11,187,1110', '0', '1');
INSERT INTO `zz_area` VALUES ('1111', '龙沙区', '188', '11,188,1111', '0', '1');
INSERT INTO `zz_area` VALUES ('1112', '建华区', '188', '11,188,1112', '0', '1');
INSERT INTO `zz_area` VALUES ('1113', '铁锋区', '188', '11,188,1113', '0', '1');
INSERT INTO `zz_area` VALUES ('1114', '昂昂溪区', '188', '11,188,1114', '0', '1');
INSERT INTO `zz_area` VALUES ('1115', '富拉尔基区', '188', '11,188,1115', '0', '1');
INSERT INTO `zz_area` VALUES ('1116', '碾子山区', '188', '11,188,1116', '0', '1');
INSERT INTO `zz_area` VALUES ('1117', '梅里斯达斡尔族区', '188', '11,188,1117', '0', '1');
INSERT INTO `zz_area` VALUES ('1118', '龙江县', '188', '11,188,1118', '0', '1');
INSERT INTO `zz_area` VALUES ('1119', '依安县', '188', '11,188,1119', '0', '1');
INSERT INTO `zz_area` VALUES ('1120', '泰来县', '188', '11,188,1120', '0', '1');
INSERT INTO `zz_area` VALUES ('1121', '甘南县', '188', '11,188,1121', '0', '1');
INSERT INTO `zz_area` VALUES ('1122', '富裕县', '188', '11,188,1122', '0', '1');
INSERT INTO `zz_area` VALUES ('1123', '克山县', '188', '11,188,1123', '0', '1');
INSERT INTO `zz_area` VALUES ('1124', '克东县', '188', '11,188,1124', '0', '1');
INSERT INTO `zz_area` VALUES ('1125', '拜泉县', '188', '11,188,1125', '0', '1');
INSERT INTO `zz_area` VALUES ('1126', '讷河市', '188', '11,188,1126', '0', '1');
INSERT INTO `zz_area` VALUES ('1127', '鸡冠区', '189', '11,189,1127', '0', '1');
INSERT INTO `zz_area` VALUES ('1128', '恒山区', '189', '11,189,1128', '0', '1');
INSERT INTO `zz_area` VALUES ('1129', '滴道区', '189', '11,189,1129', '0', '1');
INSERT INTO `zz_area` VALUES ('1130', '梨树区', '189', '11,189,1130', '0', '1');
INSERT INTO `zz_area` VALUES ('1131', '城子河区', '189', '11,189,1131', '0', '1');
INSERT INTO `zz_area` VALUES ('1132', '麻山区', '189', '11,189,1132', '0', '1');
INSERT INTO `zz_area` VALUES ('1133', '鸡东县', '189', '11,189,1133', '0', '1');
INSERT INTO `zz_area` VALUES ('1134', '虎林市', '189', '11,189,1134', '0', '1');
INSERT INTO `zz_area` VALUES ('1135', '密山市', '189', '11,189,1135', '0', '1');
INSERT INTO `zz_area` VALUES ('1136', '向阳区', '190', '11,190,1136', '0', '1');
INSERT INTO `zz_area` VALUES ('1137', '工农区', '190', '11,190,1137', '0', '1');
INSERT INTO `zz_area` VALUES ('1138', '南山区', '190', '11,190,1138', '0', '1');
INSERT INTO `zz_area` VALUES ('1139', '兴安区', '190', '11,190,1139', '0', '1');
INSERT INTO `zz_area` VALUES ('1140', '东山区', '190', '11,190,1140', '0', '1');
INSERT INTO `zz_area` VALUES ('1141', '兴山区', '190', '11,190,1141', '0', '1');
INSERT INTO `zz_area` VALUES ('1142', '萝北县', '190', '11,190,1142', '0', '1');
INSERT INTO `zz_area` VALUES ('1143', '绥滨县', '190', '11,190,1143', '0', '1');
INSERT INTO `zz_area` VALUES ('1144', '尖山区', '191', '11,191,1144', '0', '1');
INSERT INTO `zz_area` VALUES ('1145', '岭东区', '191', '11,191,1145', '0', '1');
INSERT INTO `zz_area` VALUES ('1146', '四方台区', '191', '11,191,1146', '0', '1');
INSERT INTO `zz_area` VALUES ('1147', '宝山区', '191', '11,191,1147', '0', '1');
INSERT INTO `zz_area` VALUES ('1148', '集贤县', '191', '11,191,1148', '0', '1');
INSERT INTO `zz_area` VALUES ('1149', '友谊县', '191', '11,191,1149', '0', '1');
INSERT INTO `zz_area` VALUES ('1150', '宝清县', '191', '11,191,1150', '0', '1');
INSERT INTO `zz_area` VALUES ('1151', '饶河县', '191', '11,191,1151', '0', '1');
INSERT INTO `zz_area` VALUES ('1152', '萨尔图区', '192', '11,192,1152', '0', '1');
INSERT INTO `zz_area` VALUES ('1153', '龙凤区', '192', '11,192,1153', '0', '1');
INSERT INTO `zz_area` VALUES ('1154', '让胡路区', '192', '11,192,1154', '0', '1');
INSERT INTO `zz_area` VALUES ('1155', '红岗区', '192', '11,192,1155', '0', '1');
INSERT INTO `zz_area` VALUES ('1156', '大同区', '192', '11,192,1156', '0', '1');
INSERT INTO `zz_area` VALUES ('1157', '肇州县', '192', '11,192,1157', '0', '1');
INSERT INTO `zz_area` VALUES ('1158', '肇源县', '192', '11,192,1158', '0', '1');
INSERT INTO `zz_area` VALUES ('1159', '林甸县', '192', '11,192,1159', '0', '1');
INSERT INTO `zz_area` VALUES ('1160', '杜尔伯特蒙古族自治县', '192', '11,192,1160', '0', '1');
INSERT INTO `zz_area` VALUES ('1161', '伊春区', '193', '11,193,1161', '0', '1');
INSERT INTO `zz_area` VALUES ('1162', '南岔区', '193', '11,193,1162', '0', '1');
INSERT INTO `zz_area` VALUES ('1163', '友好区', '193', '11,193,1163', '0', '1');
INSERT INTO `zz_area` VALUES ('1164', '西林区', '193', '11,193,1164', '0', '1');
INSERT INTO `zz_area` VALUES ('1165', '翠峦区', '193', '11,193,1165', '0', '1');
INSERT INTO `zz_area` VALUES ('1166', '新青区', '193', '11,193,1166', '0', '1');
INSERT INTO `zz_area` VALUES ('1167', '美溪区', '193', '11,193,1167', '0', '1');
INSERT INTO `zz_area` VALUES ('1168', '金山屯区', '193', '11,193,1168', '0', '1');
INSERT INTO `zz_area` VALUES ('1169', '五营区', '193', '11,193,1169', '0', '1');
INSERT INTO `zz_area` VALUES ('1170', '乌马河区', '193', '11,193,1170', '0', '1');
INSERT INTO `zz_area` VALUES ('1171', '汤旺河区', '193', '11,193,1171', '0', '1');
INSERT INTO `zz_area` VALUES ('1172', '带岭区', '193', '11,193,1172', '0', '1');
INSERT INTO `zz_area` VALUES ('1173', '乌伊岭区', '193', '11,193,1173', '0', '1');
INSERT INTO `zz_area` VALUES ('1174', '红星区', '193', '11,193,1174', '0', '1');
INSERT INTO `zz_area` VALUES ('1175', '上甘岭区', '193', '11,193,1175', '0', '1');
INSERT INTO `zz_area` VALUES ('1176', '嘉荫县', '193', '11,193,1176', '0', '1');
INSERT INTO `zz_area` VALUES ('1177', '铁力市', '193', '11,193,1177', '0', '1');
INSERT INTO `zz_area` VALUES ('1178', '永红区', '194', '11,194,1178', '0', '1');
INSERT INTO `zz_area` VALUES ('1179', '向阳区', '194', '11,194,1179', '0', '1');
INSERT INTO `zz_area` VALUES ('1180', '前进区', '194', '11,194,1180', '0', '1');
INSERT INTO `zz_area` VALUES ('1181', '东风区', '194', '11,194,1181', '0', '1');
INSERT INTO `zz_area` VALUES ('1182', '郊区', '194', '11,194,1182', '0', '1');
INSERT INTO `zz_area` VALUES ('1183', '桦南县', '194', '11,194,1183', '0', '1');
INSERT INTO `zz_area` VALUES ('1184', '桦川县', '194', '11,194,1184', '0', '1');
INSERT INTO `zz_area` VALUES ('1185', '汤原县', '194', '11,194,1185', '0', '1');
INSERT INTO `zz_area` VALUES ('1186', '抚远县', '194', '11,194,1186', '0', '1');
INSERT INTO `zz_area` VALUES ('1187', '同江市', '194', '11,194,1187', '0', '1');
INSERT INTO `zz_area` VALUES ('1188', '富锦市', '194', '11,194,1188', '0', '1');
INSERT INTO `zz_area` VALUES ('1189', '新兴区', '195', '11,195,1189', '0', '1');
INSERT INTO `zz_area` VALUES ('1190', '桃山区', '195', '11,195,1190', '0', '1');
INSERT INTO `zz_area` VALUES ('1191', '茄子河区', '195', '11,195,1191', '0', '1');
INSERT INTO `zz_area` VALUES ('1192', '勃利县', '195', '11,195,1192', '0', '1');
INSERT INTO `zz_area` VALUES ('1193', '东安区', '196', '11,196,1193', '0', '1');
INSERT INTO `zz_area` VALUES ('1194', '阳明区', '196', '11,196,1194', '0', '1');
INSERT INTO `zz_area` VALUES ('1195', '爱民区', '196', '11,196,1195', '0', '1');
INSERT INTO `zz_area` VALUES ('1196', '西安区', '196', '11,196,1196', '0', '1');
INSERT INTO `zz_area` VALUES ('1197', '东宁县', '196', '11,196,1197', '0', '1');
INSERT INTO `zz_area` VALUES ('1198', '林口县', '196', '11,196,1198', '0', '1');
INSERT INTO `zz_area` VALUES ('1199', '绥芬河市', '196', '11,196,1199', '0', '1');
INSERT INTO `zz_area` VALUES ('1200', '海林市', '196', '11,196,1200', '0', '1');
INSERT INTO `zz_area` VALUES ('1201', '宁安市', '196', '11,196,1201', '0', '1');
INSERT INTO `zz_area` VALUES ('1202', '穆棱市', '196', '11,196,1202', '0', '1');
INSERT INTO `zz_area` VALUES ('1203', '爱辉区', '197', '11,197,1203', '0', '1');
INSERT INTO `zz_area` VALUES ('1204', '嫩江县', '197', '11,197,1204', '0', '1');
INSERT INTO `zz_area` VALUES ('1205', '逊克县', '197', '11,197,1205', '0', '1');
INSERT INTO `zz_area` VALUES ('1206', '孙吴县', '197', '11,197,1206', '0', '1');
INSERT INTO `zz_area` VALUES ('1207', '北安市', '197', '11,197,1207', '0', '1');
INSERT INTO `zz_area` VALUES ('1208', '五大连池市', '197', '11,197,1208', '0', '1');
INSERT INTO `zz_area` VALUES ('1209', '北林区', '198', '11,198,1209', '0', '1');
INSERT INTO `zz_area` VALUES ('1210', '望奎县', '198', '11,198,1210', '0', '1');
INSERT INTO `zz_area` VALUES ('1211', '兰西县', '198', '11,198,1211', '0', '1');
INSERT INTO `zz_area` VALUES ('1212', '青冈县', '198', '11,198,1212', '0', '1');
INSERT INTO `zz_area` VALUES ('1213', '庆安县', '198', '11,198,1213', '0', '1');
INSERT INTO `zz_area` VALUES ('1214', '明水县', '198', '11,198,1214', '0', '1');
INSERT INTO `zz_area` VALUES ('1215', '绥棱县', '198', '11,198,1215', '0', '1');
INSERT INTO `zz_area` VALUES ('1216', '安达市', '198', '11,198,1216', '0', '1');
INSERT INTO `zz_area` VALUES ('1217', '肇东市', '198', '11,198,1217', '0', '1');
INSERT INTO `zz_area` VALUES ('1218', '海伦市', '198', '11,198,1218', '0', '1');
INSERT INTO `zz_area` VALUES ('1219', '呼玛县', '199', '11,199,1219', '0', '1');
INSERT INTO `zz_area` VALUES ('1220', '塔河县', '199', '11,199,1220', '0', '1');
INSERT INTO `zz_area` VALUES ('1221', '漠河县', '199', '11,199,1221', '0', '1');
INSERT INTO `zz_area` VALUES ('1222', '玄武区', '200', '12,200,1222', '0', '1');
INSERT INTO `zz_area` VALUES ('1223', '白下区', '200', '12,200,1223', '0', '1');
INSERT INTO `zz_area` VALUES ('1224', '秦淮区', '200', '12,200,1224', '0', '1');
INSERT INTO `zz_area` VALUES ('1225', '建邺区', '200', '12,200,1225', '0', '1');
INSERT INTO `zz_area` VALUES ('1226', '鼓楼区', '200', '12,200,1226', '0', '1');
INSERT INTO `zz_area` VALUES ('1227', '下关区', '200', '12,200,1227', '0', '1');
INSERT INTO `zz_area` VALUES ('1228', '浦口区', '200', '12,200,1228', '0', '1');
INSERT INTO `zz_area` VALUES ('1229', '栖霞区', '200', '12,200,1229', '0', '1');
INSERT INTO `zz_area` VALUES ('1230', '雨花台区', '200', '12,200,1230', '0', '1');
INSERT INTO `zz_area` VALUES ('1231', '江宁区', '200', '12,200,1231', '0', '1');
INSERT INTO `zz_area` VALUES ('1232', '六合区', '200', '12,200,1232', '0', '1');
INSERT INTO `zz_area` VALUES ('1233', '溧水县', '200', '12,200,1233', '0', '1');
INSERT INTO `zz_area` VALUES ('1234', '高淳县', '200', '12,200,1234', '0', '1');
INSERT INTO `zz_area` VALUES ('1235', '崇安区', '201', '12,201,1235', '0', '1');
INSERT INTO `zz_area` VALUES ('1236', '南长区', '201', '12,201,1236', '0', '1');
INSERT INTO `zz_area` VALUES ('1237', '北塘区', '201', '12,201,1237', '0', '1');
INSERT INTO `zz_area` VALUES ('1238', '锡山区', '201', '12,201,1238', '0', '1');
INSERT INTO `zz_area` VALUES ('1239', '惠山区', '201', '12,201,1239', '0', '1');
INSERT INTO `zz_area` VALUES ('1240', '滨湖区', '201', '12,201,1240', '0', '1');
INSERT INTO `zz_area` VALUES ('1241', '江阴市', '201', '12,201,1241', '0', '1');
INSERT INTO `zz_area` VALUES ('1242', '宜兴市', '201', '12,201,1242', '0', '1');
INSERT INTO `zz_area` VALUES ('1243', '鼓楼区', '202', '12,202,1243', '0', '1');
INSERT INTO `zz_area` VALUES ('1244', '云龙区', '202', '12,202,1244', '0', '1');
INSERT INTO `zz_area` VALUES ('1245', '九里区', '202', '12,202,1245', '0', '1');
INSERT INTO `zz_area` VALUES ('1246', '贾汪区', '202', '12,202,1246', '0', '1');
INSERT INTO `zz_area` VALUES ('1247', '泉山区', '202', '12,202,1247', '0', '1');
INSERT INTO `zz_area` VALUES ('1248', '丰县', '202', '12,202,1248', '0', '1');
INSERT INTO `zz_area` VALUES ('1249', '沛县', '202', '12,202,1249', '0', '1');
INSERT INTO `zz_area` VALUES ('1250', '铜山县', '202', '12,202,1250', '0', '1');
INSERT INTO `zz_area` VALUES ('1251', '睢宁县', '202', '12,202,1251', '0', '1');
INSERT INTO `zz_area` VALUES ('1252', '新沂市', '202', '12,202,1252', '0', '1');
INSERT INTO `zz_area` VALUES ('1253', '邳州市', '202', '12,202,1253', '0', '1');
INSERT INTO `zz_area` VALUES ('1254', '天宁区', '203', '12,203,1254', '0', '1');
INSERT INTO `zz_area` VALUES ('1255', '钟楼区', '203', '12,203,1255', '0', '1');
INSERT INTO `zz_area` VALUES ('1256', '戚墅堰区', '203', '12,203,1256', '0', '1');
INSERT INTO `zz_area` VALUES ('1257', '新北区', '203', '12,203,1257', '0', '1');
INSERT INTO `zz_area` VALUES ('1258', '武进区', '203', '12,203,1258', '0', '1');
INSERT INTO `zz_area` VALUES ('1259', '溧阳市', '203', '12,203,1259', '0', '1');
INSERT INTO `zz_area` VALUES ('1260', '金坛市', '203', '12,203,1260', '0', '1');
INSERT INTO `zz_area` VALUES ('1261', '沧浪区', '204', '12,204,1261', '0', '1');
INSERT INTO `zz_area` VALUES ('1262', '平江区', '204', '12,204,1262', '0', '1');
INSERT INTO `zz_area` VALUES ('1263', '金阊区', '204', '12,204,1263', '0', '1');
INSERT INTO `zz_area` VALUES ('1264', '虎丘区', '204', '12,204,1264', '0', '1');
INSERT INTO `zz_area` VALUES ('1265', '吴中区', '204', '12,204,1265', '0', '1');
INSERT INTO `zz_area` VALUES ('1266', '相城区', '204', '12,204,1266', '0', '1');
INSERT INTO `zz_area` VALUES ('1267', '常熟市', '204', '12,204,1267', '0', '1');
INSERT INTO `zz_area` VALUES ('1268', '张家港市', '204', '12,204,1268', '0', '1');
INSERT INTO `zz_area` VALUES ('1269', '昆山市', '204', '12,204,1269', '0', '1');
INSERT INTO `zz_area` VALUES ('1270', '吴江市', '204', '12,204,1270', '0', '1');
INSERT INTO `zz_area` VALUES ('1271', '太仓市', '204', '12,204,1271', '0', '1');
INSERT INTO `zz_area` VALUES ('1272', '崇川区', '205', '12,205,1272', '0', '1');
INSERT INTO `zz_area` VALUES ('1273', '港闸区', '205', '12,205,1273', '0', '1');
INSERT INTO `zz_area` VALUES ('1274', '海安县', '205', '12,205,1274', '0', '1');
INSERT INTO `zz_area` VALUES ('1275', '如东县', '205', '12,205,1275', '0', '1');
INSERT INTO `zz_area` VALUES ('1276', '启东市', '205', '12,205,1276', '0', '1');
INSERT INTO `zz_area` VALUES ('1277', '如皋市', '205', '12,205,1277', '0', '1');
INSERT INTO `zz_area` VALUES ('1278', '通州市', '205', '12,205,1278', '0', '1');
INSERT INTO `zz_area` VALUES ('1279', '海门市', '205', '12,205,1279', '0', '1');
INSERT INTO `zz_area` VALUES ('1280', '连云区', '206', '12,206,1280', '0', '1');
INSERT INTO `zz_area` VALUES ('1281', '新浦区', '206', '12,206,1281', '0', '1');
INSERT INTO `zz_area` VALUES ('1282', '海州区', '206', '12,206,1282', '0', '1');
INSERT INTO `zz_area` VALUES ('1283', '赣榆县', '206', '12,206,1283', '0', '1');
INSERT INTO `zz_area` VALUES ('1284', '东海县', '206', '12,206,1284', '0', '1');
INSERT INTO `zz_area` VALUES ('1285', '灌云县', '206', '12,206,1285', '0', '1');
INSERT INTO `zz_area` VALUES ('1286', '灌南县', '206', '12,206,1286', '0', '1');
INSERT INTO `zz_area` VALUES ('1287', '清河区', '207', '12,207,1287', '0', '1');
INSERT INTO `zz_area` VALUES ('1288', '楚州区', '207', '12,207,1288', '0', '1');
INSERT INTO `zz_area` VALUES ('1289', '淮阴区', '207', '12,207,1289', '0', '1');
INSERT INTO `zz_area` VALUES ('1290', '清浦区', '207', '12,207,1290', '0', '1');
INSERT INTO `zz_area` VALUES ('1291', '涟水县', '207', '12,207,1291', '0', '1');
INSERT INTO `zz_area` VALUES ('1292', '洪泽县', '207', '12,207,1292', '0', '1');
INSERT INTO `zz_area` VALUES ('1293', '盱眙县', '207', '12,207,1293', '0', '1');
INSERT INTO `zz_area` VALUES ('1294', '金湖县', '207', '12,207,1294', '0', '1');
INSERT INTO `zz_area` VALUES ('1295', '亭湖区', '208', '12,208,1295', '0', '1');
INSERT INTO `zz_area` VALUES ('1296', '盐都区', '208', '12,208,1296', '0', '1');
INSERT INTO `zz_area` VALUES ('1297', '响水县', '208', '12,208,1297', '0', '1');
INSERT INTO `zz_area` VALUES ('1298', '滨海县', '208', '12,208,1298', '0', '1');
INSERT INTO `zz_area` VALUES ('1299', '阜宁县', '208', '12,208,1299', '0', '1');
INSERT INTO `zz_area` VALUES ('1300', '射阳县', '208', '12,208,1300', '0', '1');
INSERT INTO `zz_area` VALUES ('1301', '建湖县', '208', '12,208,1301', '0', '1');
INSERT INTO `zz_area` VALUES ('1302', '东台市', '208', '12,208,1302', '0', '1');
INSERT INTO `zz_area` VALUES ('1303', '大丰市', '208', '12,208,1303', '0', '1');
INSERT INTO `zz_area` VALUES ('1304', '广陵区', '209', '12,209,1304', '0', '1');
INSERT INTO `zz_area` VALUES ('1305', '邗江区', '209', '12,209,1305', '0', '1');
INSERT INTO `zz_area` VALUES ('1306', '维扬区', '209', '12,209,1306', '0', '1');
INSERT INTO `zz_area` VALUES ('1307', '宝应县', '209', '12,209,1307', '0', '1');
INSERT INTO `zz_area` VALUES ('1308', '仪征市', '209', '12,209,1308', '0', '1');
INSERT INTO `zz_area` VALUES ('1309', '高邮市', '209', '12,209,1309', '0', '1');
INSERT INTO `zz_area` VALUES ('1310', '江都市', '209', '12,209,1310', '0', '1');
INSERT INTO `zz_area` VALUES ('1311', '京口区', '210', '12,210,1311', '0', '1');
INSERT INTO `zz_area` VALUES ('1312', '润州区', '210', '12,210,1312', '0', '1');
INSERT INTO `zz_area` VALUES ('1313', '丹徒区', '210', '12,210,1313', '0', '1');
INSERT INTO `zz_area` VALUES ('1314', '丹阳市', '210', '12,210,1314', '0', '1');
INSERT INTO `zz_area` VALUES ('1315', '扬中市', '210', '12,210,1315', '0', '1');
INSERT INTO `zz_area` VALUES ('1316', '句容市', '210', '12,210,1316', '0', '1');
INSERT INTO `zz_area` VALUES ('1317', '海陵区', '211', '12,211,1317', '0', '1');
INSERT INTO `zz_area` VALUES ('1318', '高港区', '211', '12,211,1318', '0', '1');
INSERT INTO `zz_area` VALUES ('1319', '兴化市', '211', '12,211,1319', '0', '1');
INSERT INTO `zz_area` VALUES ('1320', '靖江市', '211', '12,211,1320', '0', '1');
INSERT INTO `zz_area` VALUES ('1321', '泰兴市', '211', '12,211,1321', '0', '1');
INSERT INTO `zz_area` VALUES ('1322', '姜堰市', '211', '12,211,1322', '0', '1');
INSERT INTO `zz_area` VALUES ('1323', '宿城区', '212', '12,212,1323', '0', '1');
INSERT INTO `zz_area` VALUES ('1324', '宿豫区', '212', '12,212,1324', '0', '1');
INSERT INTO `zz_area` VALUES ('1325', '沭阳县', '212', '12,212,1325', '0', '1');
INSERT INTO `zz_area` VALUES ('1326', '泗阳县', '212', '12,212,1326', '0', '1');
INSERT INTO `zz_area` VALUES ('1327', '泗洪县', '212', '12,212,1327', '0', '1');
INSERT INTO `zz_area` VALUES ('1328', '上城区', '213', '13,213,1328', '0', '1');
INSERT INTO `zz_area` VALUES ('1329', '下城区', '213', '13,213,1329', '0', '1');
INSERT INTO `zz_area` VALUES ('1330', '江干区', '213', '13,213,1330', '0', '1');
INSERT INTO `zz_area` VALUES ('1331', '拱墅区', '213', '13,213,1331', '0', '1');
INSERT INTO `zz_area` VALUES ('1332', '西湖区', '213', '13,213,1332', '0', '1');
INSERT INTO `zz_area` VALUES ('1333', '滨江区', '213', '13,213,1333', '0', '1');
INSERT INTO `zz_area` VALUES ('1334', '萧山区', '213', '13,213,1334', '0', '1');
INSERT INTO `zz_area` VALUES ('1335', '余杭区', '213', '13,213,1335', '0', '1');
INSERT INTO `zz_area` VALUES ('1336', '桐庐县', '213', '13,213,1336', '0', '1');
INSERT INTO `zz_area` VALUES ('1337', '淳安县', '213', '13,213,1337', '0', '1');
INSERT INTO `zz_area` VALUES ('1338', '建德市', '213', '13,213,1338', '0', '1');
INSERT INTO `zz_area` VALUES ('1339', '富阳市', '213', '13,213,1339', '0', '1');
INSERT INTO `zz_area` VALUES ('1340', '临安市', '213', '13,213,1340', '0', '1');
INSERT INTO `zz_area` VALUES ('1341', '海曙区', '214', '13,214,1341', '0', '1');
INSERT INTO `zz_area` VALUES ('1342', '江东区', '214', '13,214,1342', '0', '1');
INSERT INTO `zz_area` VALUES ('1343', '江北区', '214', '13,214,1343', '0', '1');
INSERT INTO `zz_area` VALUES ('1344', '北仑区', '214', '13,214,1344', '0', '1');
INSERT INTO `zz_area` VALUES ('1345', '镇海区', '214', '13,214,1345', '0', '1');
INSERT INTO `zz_area` VALUES ('1346', '鄞州区', '214', '13,214,1346', '0', '1');
INSERT INTO `zz_area` VALUES ('1347', '象山县', '214', '13,214,1347', '0', '1');
INSERT INTO `zz_area` VALUES ('1348', '宁海县', '214', '13,214,1348', '0', '1');
INSERT INTO `zz_area` VALUES ('1349', '余姚市', '214', '13,214,1349', '0', '1');
INSERT INTO `zz_area` VALUES ('1350', '慈溪市', '214', '13,214,1350', '0', '1');
INSERT INTO `zz_area` VALUES ('1351', '奉化市', '214', '13,214,1351', '0', '1');
INSERT INTO `zz_area` VALUES ('1352', '鹿城区', '215', '13,215,1352', '0', '1');
INSERT INTO `zz_area` VALUES ('1353', '龙湾区', '215', '13,215,1353', '0', '1');
INSERT INTO `zz_area` VALUES ('1354', '瓯海区', '215', '13,215,1354', '0', '1');
INSERT INTO `zz_area` VALUES ('1355', '洞头县', '215', '13,215,1355', '0', '1');
INSERT INTO `zz_area` VALUES ('1356', '永嘉县', '215', '13,215,1356', '0', '1');
INSERT INTO `zz_area` VALUES ('1357', '平阳县', '215', '13,215,1357', '0', '1');
INSERT INTO `zz_area` VALUES ('1358', '苍南县', '215', '13,215,1358', '0', '1');
INSERT INTO `zz_area` VALUES ('1359', '文成县', '215', '13,215,1359', '0', '1');
INSERT INTO `zz_area` VALUES ('1360', '泰顺县', '215', '13,215,1360', '0', '1');
INSERT INTO `zz_area` VALUES ('1361', '瑞安市', '215', '13,215,1361', '0', '1');
INSERT INTO `zz_area` VALUES ('1362', '乐清市', '215', '13,215,1362', '0', '1');
INSERT INTO `zz_area` VALUES ('1363', '秀城区', '216', '13,216,1363', '0', '1');
INSERT INTO `zz_area` VALUES ('1364', '秀洲区', '216', '13,216,1364', '0', '1');
INSERT INTO `zz_area` VALUES ('1365', '嘉善县', '216', '13,216,1365', '0', '1');
INSERT INTO `zz_area` VALUES ('1366', '海盐县', '216', '13,216,1366', '0', '1');
INSERT INTO `zz_area` VALUES ('1367', '海宁市', '216', '13,216,1367', '0', '1');
INSERT INTO `zz_area` VALUES ('1368', '平湖市', '216', '13,216,1368', '0', '1');
INSERT INTO `zz_area` VALUES ('1369', '桐乡市', '216', '13,216,1369', '0', '1');
INSERT INTO `zz_area` VALUES ('1370', '吴兴区', '217', '13,217,1370', '0', '1');
INSERT INTO `zz_area` VALUES ('1371', '南浔区', '217', '13,217,1371', '0', '1');
INSERT INTO `zz_area` VALUES ('1372', '德清县', '217', '13,217,1372', '0', '1');
INSERT INTO `zz_area` VALUES ('1373', '长兴县', '217', '13,217,1373', '0', '1');
INSERT INTO `zz_area` VALUES ('1374', '安吉县', '217', '13,217,1374', '0', '1');
INSERT INTO `zz_area` VALUES ('1375', '越城区', '218', '13,218,1375', '0', '1');
INSERT INTO `zz_area` VALUES ('1376', '绍兴县', '218', '13,218,1376', '0', '1');
INSERT INTO `zz_area` VALUES ('1377', '新昌县', '218', '13,218,1377', '0', '1');
INSERT INTO `zz_area` VALUES ('1378', '诸暨市', '218', '13,218,1378', '0', '1');
INSERT INTO `zz_area` VALUES ('1379', '上虞市', '218', '13,218,1379', '0', '1');
INSERT INTO `zz_area` VALUES ('1380', '嵊州市', '218', '13,218,1380', '0', '1');
INSERT INTO `zz_area` VALUES ('1381', '婺城区', '219', '13,219,1381', '0', '1');
INSERT INTO `zz_area` VALUES ('1382', '金东区', '219', '13,219,1382', '0', '1');
INSERT INTO `zz_area` VALUES ('1383', '武义县', '219', '13,219,1383', '0', '1');
INSERT INTO `zz_area` VALUES ('1384', '浦江县', '219', '13,219,1384', '0', '1');
INSERT INTO `zz_area` VALUES ('1385', '磐安县', '219', '13,219,1385', '0', '1');
INSERT INTO `zz_area` VALUES ('1386', '兰溪市', '219', '13,219,1386', '0', '1');
INSERT INTO `zz_area` VALUES ('1387', '义乌市', '219', '13,219,1387', '0', '1');
INSERT INTO `zz_area` VALUES ('1388', '东阳市', '219', '13,219,1388', '0', '1');
INSERT INTO `zz_area` VALUES ('1389', '永康市', '219', '13,219,1389', '0', '1');
INSERT INTO `zz_area` VALUES ('1390', '柯城区', '220', '13,220,1390', '0', '1');
INSERT INTO `zz_area` VALUES ('1391', '衢江区', '220', '13,220,1391', '0', '1');
INSERT INTO `zz_area` VALUES ('1392', '常山县', '220', '13,220,1392', '0', '1');
INSERT INTO `zz_area` VALUES ('1393', '开化县', '220', '13,220,1393', '0', '1');
INSERT INTO `zz_area` VALUES ('1394', '龙游县', '220', '13,220,1394', '0', '1');
INSERT INTO `zz_area` VALUES ('1395', '江山市', '220', '13,220,1395', '0', '1');
INSERT INTO `zz_area` VALUES ('1396', '定海区', '221', '13,221,1396', '0', '1');
INSERT INTO `zz_area` VALUES ('1397', '普陀区', '221', '13,221,1397', '0', '1');
INSERT INTO `zz_area` VALUES ('1398', '岱山县', '221', '13,221,1398', '0', '1');
INSERT INTO `zz_area` VALUES ('1399', '嵊泗县', '221', '13,221,1399', '0', '1');
INSERT INTO `zz_area` VALUES ('1400', '椒江区', '222', '13,222,1400', '0', '1');
INSERT INTO `zz_area` VALUES ('1401', '黄岩区', '222', '13,222,1401', '0', '1');
INSERT INTO `zz_area` VALUES ('1402', '路桥区', '222', '13,222,1402', '0', '1');
INSERT INTO `zz_area` VALUES ('1403', '玉环县', '222', '13,222,1403', '0', '1');
INSERT INTO `zz_area` VALUES ('1404', '三门县', '222', '13,222,1404', '0', '1');
INSERT INTO `zz_area` VALUES ('1405', '天台县', '222', '13,222,1405', '0', '1');
INSERT INTO `zz_area` VALUES ('1406', '仙居县', '222', '13,222,1406', '0', '1');
INSERT INTO `zz_area` VALUES ('1407', '温岭市', '222', '13,222,1407', '0', '1');
INSERT INTO `zz_area` VALUES ('1408', '临海市', '222', '13,222,1408', '0', '1');
INSERT INTO `zz_area` VALUES ('1409', '莲都区', '223', '13,223,1409', '0', '1');
INSERT INTO `zz_area` VALUES ('1410', '青田县', '223', '13,223,1410', '0', '1');
INSERT INTO `zz_area` VALUES ('1411', '缙云县', '223', '13,223,1411', '0', '1');
INSERT INTO `zz_area` VALUES ('1412', '遂昌县', '223', '13,223,1412', '0', '1');
INSERT INTO `zz_area` VALUES ('1413', '松阳县', '223', '13,223,1413', '0', '1');
INSERT INTO `zz_area` VALUES ('1414', '云和县', '223', '13,223,1414', '0', '1');
INSERT INTO `zz_area` VALUES ('1415', '庆元县', '223', '13,223,1415', '0', '1');
INSERT INTO `zz_area` VALUES ('1416', '景宁畲族自治县', '223', '13,223,1416', '0', '1');
INSERT INTO `zz_area` VALUES ('1417', '龙泉市', '223', '13,223,1417', '0', '1');
INSERT INTO `zz_area` VALUES ('1418', '瑶海区', '224', '14,224,1418', '0', '1');
INSERT INTO `zz_area` VALUES ('1419', '庐阳区', '224', '14,224,1419', '0', '1');
INSERT INTO `zz_area` VALUES ('1420', '蜀山区', '224', '14,224,1420', '0', '1');
INSERT INTO `zz_area` VALUES ('1421', '包河区', '224', '14,224,1421', '0', '1');
INSERT INTO `zz_area` VALUES ('1422', '长丰县', '224', '14,224,1422', '0', '1');
INSERT INTO `zz_area` VALUES ('1423', '肥东县', '224', '14,224,1423', '0', '1');
INSERT INTO `zz_area` VALUES ('1424', '肥西县', '224', '14,224,1424', '0', '1');
INSERT INTO `zz_area` VALUES ('1425', '镜湖区', '225', '14,225,1425', '0', '1');
INSERT INTO `zz_area` VALUES ('1426', '弋江区', '225', '14,225,1426', '0', '1');
INSERT INTO `zz_area` VALUES ('1427', '鸠江区', '225', '14,225,1427', '0', '1');
INSERT INTO `zz_area` VALUES ('1428', '三山区', '225', '14,225,1428', '0', '1');
INSERT INTO `zz_area` VALUES ('1429', '芜湖县', '225', '14,225,1429', '0', '1');
INSERT INTO `zz_area` VALUES ('1430', '繁昌县', '225', '14,225,1430', '0', '1');
INSERT INTO `zz_area` VALUES ('1431', '南陵县', '225', '14,225,1431', '0', '1');
INSERT INTO `zz_area` VALUES ('1432', '龙子湖区', '226', '14,226,1432', '0', '1');
INSERT INTO `zz_area` VALUES ('1433', '蚌山区', '226', '14,226,1433', '0', '1');
INSERT INTO `zz_area` VALUES ('1434', '禹会区', '226', '14,226,1434', '0', '1');
INSERT INTO `zz_area` VALUES ('1435', '淮上区', '226', '14,226,1435', '0', '1');
INSERT INTO `zz_area` VALUES ('1436', '怀远县', '226', '14,226,1436', '0', '1');
INSERT INTO `zz_area` VALUES ('1437', '五河县', '226', '14,226,1437', '0', '1');
INSERT INTO `zz_area` VALUES ('1438', '固镇县', '226', '14,226,1438', '0', '1');
INSERT INTO `zz_area` VALUES ('1439', '大通区', '227', '14,227,1439', '0', '1');
INSERT INTO `zz_area` VALUES ('1440', '田家庵区', '227', '14,227,1440', '0', '1');
INSERT INTO `zz_area` VALUES ('1441', '谢家集区', '227', '14,227,1441', '0', '1');
INSERT INTO `zz_area` VALUES ('1442', '八公山区', '227', '14,227,1442', '0', '1');
INSERT INTO `zz_area` VALUES ('1443', '潘集区', '227', '14,227,1443', '0', '1');
INSERT INTO `zz_area` VALUES ('1444', '凤台县', '227', '14,227,1444', '0', '1');
INSERT INTO `zz_area` VALUES ('1445', '金家庄区', '228', '14,228,1445', '0', '1');
INSERT INTO `zz_area` VALUES ('1446', '花山区', '228', '14,228,1446', '0', '1');
INSERT INTO `zz_area` VALUES ('1447', '雨山区', '228', '14,228,1447', '0', '1');
INSERT INTO `zz_area` VALUES ('1448', '当涂县', '228', '14,228,1448', '0', '1');
INSERT INTO `zz_area` VALUES ('1449', '杜集区', '229', '14,229,1449', '0', '1');
INSERT INTO `zz_area` VALUES ('1450', '相山区', '229', '14,229,1450', '0', '1');
INSERT INTO `zz_area` VALUES ('1451', '烈山区', '229', '14,229,1451', '0', '1');
INSERT INTO `zz_area` VALUES ('1452', '濉溪县', '229', '14,229,1452', '0', '1');
INSERT INTO `zz_area` VALUES ('1453', '铜官山区', '230', '14,230,1453', '0', '1');
INSERT INTO `zz_area` VALUES ('1454', '狮子山区', '230', '14,230,1454', '0', '1');
INSERT INTO `zz_area` VALUES ('1455', '郊区', '230', '14,230,1455', '0', '1');
INSERT INTO `zz_area` VALUES ('1456', '铜陵县', '230', '14,230,1456', '0', '1');
INSERT INTO `zz_area` VALUES ('1457', '迎江区', '231', '14,231,1457', '0', '1');
INSERT INTO `zz_area` VALUES ('1458', '大观区', '231', '14,231,1458', '0', '1');
INSERT INTO `zz_area` VALUES ('1459', '宜秀区', '231', '14,231,1459', '0', '1');
INSERT INTO `zz_area` VALUES ('1460', '怀宁县', '231', '14,231,1460', '0', '1');
INSERT INTO `zz_area` VALUES ('1461', '枞阳县', '231', '14,231,1461', '0', '1');
INSERT INTO `zz_area` VALUES ('1462', '潜山县', '231', '14,231,1462', '0', '1');
INSERT INTO `zz_area` VALUES ('1463', '太湖县', '231', '14,231,1463', '0', '1');
INSERT INTO `zz_area` VALUES ('1464', '宿松县', '231', '14,231,1464', '0', '1');
INSERT INTO `zz_area` VALUES ('1465', '望江县', '231', '14,231,1465', '0', '1');
INSERT INTO `zz_area` VALUES ('1466', '岳西县', '231', '14,231,1466', '0', '1');
INSERT INTO `zz_area` VALUES ('1467', '桐城市', '231', '14,231,1467', '0', '1');
INSERT INTO `zz_area` VALUES ('1468', '屯溪区', '232', '14,232,1468', '0', '1');
INSERT INTO `zz_area` VALUES ('1469', '黄山区', '232', '14,232,1469', '0', '1');
INSERT INTO `zz_area` VALUES ('1470', '徽州区', '232', '14,232,1470', '0', '1');
INSERT INTO `zz_area` VALUES ('1471', '歙县', '232', '14,232,1471', '0', '1');
INSERT INTO `zz_area` VALUES ('1472', '休宁县', '232', '14,232,1472', '0', '1');
INSERT INTO `zz_area` VALUES ('1473', '黟县', '232', '14,232,1473', '0', '1');
INSERT INTO `zz_area` VALUES ('1474', '祁门县', '232', '14,232,1474', '0', '1');
INSERT INTO `zz_area` VALUES ('1475', '琅琊区', '233', '14,233,1475', '0', '1');
INSERT INTO `zz_area` VALUES ('1476', '南谯区', '233', '14,233,1476', '0', '1');
INSERT INTO `zz_area` VALUES ('1477', '来安县', '233', '14,233,1477', '0', '1');
INSERT INTO `zz_area` VALUES ('1478', '全椒县', '233', '14,233,1478', '0', '1');
INSERT INTO `zz_area` VALUES ('1479', '定远县', '233', '14,233,1479', '0', '1');
INSERT INTO `zz_area` VALUES ('1480', '凤阳县', '233', '14,233,1480', '0', '1');
INSERT INTO `zz_area` VALUES ('1481', '天长市', '233', '14,233,1481', '0', '1');
INSERT INTO `zz_area` VALUES ('1482', '明光市', '233', '14,233,1482', '0', '1');
INSERT INTO `zz_area` VALUES ('1483', '颍州区', '234', '14,234,1483', '0', '1');
INSERT INTO `zz_area` VALUES ('1484', '颍东区', '234', '14,234,1484', '0', '1');
INSERT INTO `zz_area` VALUES ('1485', '颍泉区', '234', '14,234,1485', '0', '1');
INSERT INTO `zz_area` VALUES ('1486', '临泉县', '234', '14,234,1486', '0', '1');
INSERT INTO `zz_area` VALUES ('1487', '太和县', '234', '14,234,1487', '0', '1');
INSERT INTO `zz_area` VALUES ('1488', '阜南县', '234', '14,234,1488', '0', '1');
INSERT INTO `zz_area` VALUES ('1489', '颍上县', '234', '14,234,1489', '0', '1');
INSERT INTO `zz_area` VALUES ('1490', '界首市', '234', '14,234,1490', '0', '1');
INSERT INTO `zz_area` VALUES ('1491', '埇桥区', '235', '14,235,1491', '0', '1');
INSERT INTO `zz_area` VALUES ('1492', '砀山县', '235', '14,235,1492', '0', '1');
INSERT INTO `zz_area` VALUES ('1493', '萧县', '235', '14,235,1493', '0', '1');
INSERT INTO `zz_area` VALUES ('1494', '灵璧县', '235', '14,235,1494', '0', '1');
INSERT INTO `zz_area` VALUES ('1495', '泗县', '235', '14,235,1495', '0', '1');
INSERT INTO `zz_area` VALUES ('1496', '居巢区', '236', '14,236,1496', '0', '1');
INSERT INTO `zz_area` VALUES ('1497', '庐江县', '236', '14,236,1497', '0', '1');
INSERT INTO `zz_area` VALUES ('1498', '无为县', '236', '14,236,1498', '0', '1');
INSERT INTO `zz_area` VALUES ('1499', '含山县', '236', '14,236,1499', '0', '1');
INSERT INTO `zz_area` VALUES ('1500', '和县', '236', '14,236,1500', '0', '1');
INSERT INTO `zz_area` VALUES ('1501', '金安区', '237', '14,237,1501', '0', '1');
INSERT INTO `zz_area` VALUES ('1502', '裕安区', '237', '14,237,1502', '0', '1');
INSERT INTO `zz_area` VALUES ('1503', '寿县', '237', '14,237,1503', '0', '1');
INSERT INTO `zz_area` VALUES ('1504', '霍邱县', '237', '14,237,1504', '0', '1');
INSERT INTO `zz_area` VALUES ('1505', '舒城县', '237', '14,237,1505', '0', '1');
INSERT INTO `zz_area` VALUES ('1506', '金寨县', '237', '14,237,1506', '0', '1');
INSERT INTO `zz_area` VALUES ('1507', '霍山县', '237', '14,237,1507', '0', '1');
INSERT INTO `zz_area` VALUES ('1508', '谯城区', '238', '14,238,1508', '0', '1');
INSERT INTO `zz_area` VALUES ('1509', '涡阳县', '238', '14,238,1509', '0', '1');
INSERT INTO `zz_area` VALUES ('1510', '蒙城县', '238', '14,238,1510', '0', '1');
INSERT INTO `zz_area` VALUES ('1511', '利辛县', '238', '14,238,1511', '0', '1');
INSERT INTO `zz_area` VALUES ('1512', '贵池区', '239', '14,239,1512', '0', '1');
INSERT INTO `zz_area` VALUES ('1513', '东至县', '239', '14,239,1513', '0', '1');
INSERT INTO `zz_area` VALUES ('1514', '石台县', '239', '14,239,1514', '0', '1');
INSERT INTO `zz_area` VALUES ('1515', '青阳县', '239', '14,239,1515', '0', '1');
INSERT INTO `zz_area` VALUES ('1516', '宣州区', '240', '14,240,1516', '0', '1');
INSERT INTO `zz_area` VALUES ('1517', '郎溪县', '240', '14,240,1517', '0', '1');
INSERT INTO `zz_area` VALUES ('1518', '广德县', '240', '14,240,1518', '0', '1');
INSERT INTO `zz_area` VALUES ('1519', '泾县', '240', '14,240,1519', '0', '1');
INSERT INTO `zz_area` VALUES ('1520', '绩溪县', '240', '14,240,1520', '0', '1');
INSERT INTO `zz_area` VALUES ('1521', '旌德县', '240', '14,240,1521', '0', '1');
INSERT INTO `zz_area` VALUES ('1522', '宁国市', '240', '14,240,1522', '0', '1');
INSERT INTO `zz_area` VALUES ('1523', '鼓楼区', '241', '15,241,1523', '0', '1');
INSERT INTO `zz_area` VALUES ('1524', '台江区', '241', '15,241,1524', '0', '1');
INSERT INTO `zz_area` VALUES ('1525', '仓山区', '241', '15,241,1525', '0', '1');
INSERT INTO `zz_area` VALUES ('1526', '马尾区', '241', '15,241,1526', '0', '1');
INSERT INTO `zz_area` VALUES ('1527', '晋安区', '241', '15,241,1527', '0', '1');
INSERT INTO `zz_area` VALUES ('1528', '闽侯县', '241', '15,241,1528', '0', '1');
INSERT INTO `zz_area` VALUES ('1529', '连江县', '241', '15,241,1529', '0', '1');
INSERT INTO `zz_area` VALUES ('1530', '罗源县', '241', '15,241,1530', '0', '1');
INSERT INTO `zz_area` VALUES ('1531', '闽清县', '241', '15,241,1531', '0', '1');
INSERT INTO `zz_area` VALUES ('1532', '永泰县', '241', '15,241,1532', '0', '1');
INSERT INTO `zz_area` VALUES ('1533', '平潭县', '241', '15,241,1533', '0', '1');
INSERT INTO `zz_area` VALUES ('1534', '福清市', '241', '15,241,1534', '0', '1');
INSERT INTO `zz_area` VALUES ('1535', '长乐市', '241', '15,241,1535', '0', '1');
INSERT INTO `zz_area` VALUES ('1536', '思明区', '242', '15,242,1536', '0', '1');
INSERT INTO `zz_area` VALUES ('1537', '海沧区', '242', '15,242,1537', '0', '1');
INSERT INTO `zz_area` VALUES ('1538', '湖里区', '242', '15,242,1538', '0', '1');
INSERT INTO `zz_area` VALUES ('1539', '集美区', '242', '15,242,1539', '0', '1');
INSERT INTO `zz_area` VALUES ('1540', '同安区', '242', '15,242,1540', '0', '1');
INSERT INTO `zz_area` VALUES ('1541', '翔安区', '242', '15,242,1541', '0', '1');
INSERT INTO `zz_area` VALUES ('1542', '城厢区', '243', '15,243,1542', '0', '1');
INSERT INTO `zz_area` VALUES ('1543', '涵江区', '243', '15,243,1543', '0', '1');
INSERT INTO `zz_area` VALUES ('1544', '荔城区', '243', '15,243,1544', '0', '1');
INSERT INTO `zz_area` VALUES ('1545', '秀屿区', '243', '15,243,1545', '0', '1');
INSERT INTO `zz_area` VALUES ('1546', '仙游县', '243', '15,243,1546', '0', '1');
INSERT INTO `zz_area` VALUES ('1547', '梅列区', '244', '15,244,1547', '0', '1');
INSERT INTO `zz_area` VALUES ('1548', '三元区', '244', '15,244,1548', '0', '1');
INSERT INTO `zz_area` VALUES ('1549', '明溪县', '244', '15,244,1549', '0', '1');
INSERT INTO `zz_area` VALUES ('1550', '清流县', '244', '15,244,1550', '0', '1');
INSERT INTO `zz_area` VALUES ('1551', '宁化县', '244', '15,244,1551', '0', '1');
INSERT INTO `zz_area` VALUES ('1552', '大田县', '244', '15,244,1552', '0', '1');
INSERT INTO `zz_area` VALUES ('1553', '尤溪县', '244', '15,244,1553', '0', '1');
INSERT INTO `zz_area` VALUES ('1554', '沙县', '244', '15,244,1554', '0', '1');
INSERT INTO `zz_area` VALUES ('1555', '将乐县', '244', '15,244,1555', '0', '1');
INSERT INTO `zz_area` VALUES ('1556', '泰宁县', '244', '15,244,1556', '0', '1');
INSERT INTO `zz_area` VALUES ('1557', '建宁县', '244', '15,244,1557', '0', '1');
INSERT INTO `zz_area` VALUES ('1558', '永安市', '244', '15,244,1558', '0', '1');
INSERT INTO `zz_area` VALUES ('1559', '鲤城区', '245', '15,245,1559', '0', '1');
INSERT INTO `zz_area` VALUES ('1560', '丰泽区', '245', '15,245,1560', '0', '1');
INSERT INTO `zz_area` VALUES ('1561', '洛江区', '245', '15,245,1561', '0', '1');
INSERT INTO `zz_area` VALUES ('1562', '泉港区', '245', '15,245,1562', '0', '1');
INSERT INTO `zz_area` VALUES ('1563', '惠安县', '245', '15,245,1563', '0', '1');
INSERT INTO `zz_area` VALUES ('1564', '安溪县', '245', '15,245,1564', '0', '1');
INSERT INTO `zz_area` VALUES ('1565', '永春县', '245', '15,245,1565', '0', '1');
INSERT INTO `zz_area` VALUES ('1566', '德化县', '245', '15,245,1566', '0', '1');
INSERT INTO `zz_area` VALUES ('1567', '金门县', '245', '15,245,1567', '0', '1');
INSERT INTO `zz_area` VALUES ('1568', '石狮市', '245', '15,245,1568', '0', '1');
INSERT INTO `zz_area` VALUES ('1569', '晋江市', '245', '15,245,1569', '0', '1');
INSERT INTO `zz_area` VALUES ('1570', '南安市', '245', '15,245,1570', '0', '1');
INSERT INTO `zz_area` VALUES ('1571', '芗城区', '246', '15,246,1571', '0', '1');
INSERT INTO `zz_area` VALUES ('1572', '龙文区', '246', '15,246,1572', '0', '1');
INSERT INTO `zz_area` VALUES ('1573', '云霄县', '246', '15,246,1573', '0', '1');
INSERT INTO `zz_area` VALUES ('1574', '漳浦县', '246', '15,246,1574', '0', '1');
INSERT INTO `zz_area` VALUES ('1575', '诏安县', '246', '15,246,1575', '0', '1');
INSERT INTO `zz_area` VALUES ('1576', '长泰县', '246', '15,246,1576', '0', '1');
INSERT INTO `zz_area` VALUES ('1577', '东山县', '246', '15,246,1577', '0', '1');
INSERT INTO `zz_area` VALUES ('1578', '南靖县', '246', '15,246,1578', '0', '1');
INSERT INTO `zz_area` VALUES ('1579', '平和县', '246', '15,246,1579', '0', '1');
INSERT INTO `zz_area` VALUES ('1580', '华安县', '246', '15,246,1580', '0', '1');
INSERT INTO `zz_area` VALUES ('1581', '龙海市', '246', '15,246,1581', '0', '1');
INSERT INTO `zz_area` VALUES ('1582', '延平区', '247', '15,247,1582', '0', '1');
INSERT INTO `zz_area` VALUES ('1583', '顺昌县', '247', '15,247,1583', '0', '1');
INSERT INTO `zz_area` VALUES ('1584', '浦城县', '247', '15,247,1584', '0', '1');
INSERT INTO `zz_area` VALUES ('1585', '光泽县', '247', '15,247,1585', '0', '1');
INSERT INTO `zz_area` VALUES ('1586', '松溪县', '247', '15,247,1586', '0', '1');
INSERT INTO `zz_area` VALUES ('1587', '政和县', '247', '15,247,1587', '0', '1');
INSERT INTO `zz_area` VALUES ('1588', '邵武市', '247', '15,247,1588', '0', '1');
INSERT INTO `zz_area` VALUES ('1589', '武夷山市', '247', '15,247,1589', '0', '1');
INSERT INTO `zz_area` VALUES ('1590', '建瓯市', '247', '15,247,1590', '0', '1');
INSERT INTO `zz_area` VALUES ('1591', '建阳市', '247', '15,247,1591', '0', '1');
INSERT INTO `zz_area` VALUES ('1592', '新罗区', '248', '15,248,1592', '0', '1');
INSERT INTO `zz_area` VALUES ('1593', '长汀县', '248', '15,248,1593', '0', '1');
INSERT INTO `zz_area` VALUES ('1594', '永定县', '248', '15,248,1594', '0', '1');
INSERT INTO `zz_area` VALUES ('1595', '上杭县', '248', '15,248,1595', '0', '1');
INSERT INTO `zz_area` VALUES ('1596', '武平县', '248', '15,248,1596', '0', '1');
INSERT INTO `zz_area` VALUES ('1597', '连城县', '248', '15,248,1597', '0', '1');
INSERT INTO `zz_area` VALUES ('1598', '漳平市', '248', '15,248,1598', '0', '1');
INSERT INTO `zz_area` VALUES ('1599', '蕉城区', '249', '15,249,1599', '0', '1');
INSERT INTO `zz_area` VALUES ('1600', '霞浦县', '249', '15,249,1600', '0', '1');
INSERT INTO `zz_area` VALUES ('1601', '古田县', '249', '15,249,1601', '0', '1');
INSERT INTO `zz_area` VALUES ('1602', '屏南县', '249', '15,249,1602', '0', '1');
INSERT INTO `zz_area` VALUES ('1603', '寿宁县', '249', '15,249,1603', '0', '1');
INSERT INTO `zz_area` VALUES ('1604', '周宁县', '249', '15,249,1604', '0', '1');
INSERT INTO `zz_area` VALUES ('1605', '柘荣县', '249', '15,249,1605', '0', '1');
INSERT INTO `zz_area` VALUES ('1606', '福安市', '249', '15,249,1606', '0', '1');
INSERT INTO `zz_area` VALUES ('1607', '福鼎市', '249', '15,249,1607', '0', '1');
INSERT INTO `zz_area` VALUES ('1608', '东湖区', '250', '16,250,1608', '0', '1');
INSERT INTO `zz_area` VALUES ('1609', '西湖区', '250', '16,250,1609', '0', '1');
INSERT INTO `zz_area` VALUES ('1610', '青云谱区', '250', '16,250,1610', '0', '1');
INSERT INTO `zz_area` VALUES ('1611', '湾里区', '250', '16,250,1611', '0', '1');
INSERT INTO `zz_area` VALUES ('1612', '青山湖区', '250', '16,250,1612', '0', '1');
INSERT INTO `zz_area` VALUES ('1613', '南昌县', '250', '16,250,1613', '0', '1');
INSERT INTO `zz_area` VALUES ('1614', '新建县', '250', '16,250,1614', '0', '1');
INSERT INTO `zz_area` VALUES ('1615', '安义县', '250', '16,250,1615', '0', '1');
INSERT INTO `zz_area` VALUES ('1616', '进贤县', '250', '16,250,1616', '0', '1');
INSERT INTO `zz_area` VALUES ('1617', '昌江区', '251', '16,251,1617', '0', '1');
INSERT INTO `zz_area` VALUES ('1618', '珠山区', '251', '16,251,1618', '0', '1');
INSERT INTO `zz_area` VALUES ('1619', '浮梁县', '251', '16,251,1619', '0', '1');
INSERT INTO `zz_area` VALUES ('1620', '乐平市', '251', '16,251,1620', '0', '1');
INSERT INTO `zz_area` VALUES ('1621', '安源区', '252', '16,252,1621', '0', '1');
INSERT INTO `zz_area` VALUES ('1622', '湘东区', '252', '16,252,1622', '0', '1');
INSERT INTO `zz_area` VALUES ('1623', '莲花县', '252', '16,252,1623', '0', '1');
INSERT INTO `zz_area` VALUES ('1624', '上栗县', '252', '16,252,1624', '0', '1');
INSERT INTO `zz_area` VALUES ('1625', '芦溪县', '252', '16,252,1625', '0', '1');
INSERT INTO `zz_area` VALUES ('1626', '庐山区', '253', '16,253,1626', '0', '1');
INSERT INTO `zz_area` VALUES ('1627', '浔阳区', '253', '16,253,1627', '0', '1');
INSERT INTO `zz_area` VALUES ('1628', '九江县', '253', '16,253,1628', '0', '1');
INSERT INTO `zz_area` VALUES ('1629', '武宁县', '253', '16,253,1629', '0', '1');
INSERT INTO `zz_area` VALUES ('1630', '修水县', '253', '16,253,1630', '0', '1');
INSERT INTO `zz_area` VALUES ('1631', '永修县', '253', '16,253,1631', '0', '1');
INSERT INTO `zz_area` VALUES ('1632', '德安县', '253', '16,253,1632', '0', '1');
INSERT INTO `zz_area` VALUES ('1633', '星子县', '253', '16,253,1633', '0', '1');
INSERT INTO `zz_area` VALUES ('1634', '都昌县', '253', '16,253,1634', '0', '1');
INSERT INTO `zz_area` VALUES ('1635', '湖口县', '253', '16,253,1635', '0', '1');
INSERT INTO `zz_area` VALUES ('1636', '彭泽县', '253', '16,253,1636', '0', '1');
INSERT INTO `zz_area` VALUES ('1637', '瑞昌市', '253', '16,253,1637', '0', '1');
INSERT INTO `zz_area` VALUES ('1638', '渝水区', '254', '16,254,1638', '0', '1');
INSERT INTO `zz_area` VALUES ('1639', '分宜县', '254', '16,254,1639', '0', '1');
INSERT INTO `zz_area` VALUES ('1640', '月湖区', '255', '16,255,1640', '0', '1');
INSERT INTO `zz_area` VALUES ('1641', '余江县', '255', '16,255,1641', '0', '1');
INSERT INTO `zz_area` VALUES ('1642', '贵溪市', '255', '16,255,1642', '0', '1');
INSERT INTO `zz_area` VALUES ('1643', '章贡区', '256', '16,256,1643', '0', '1');
INSERT INTO `zz_area` VALUES ('1644', '赣县', '256', '16,256,1644', '0', '1');
INSERT INTO `zz_area` VALUES ('1645', '信丰县', '256', '16,256,1645', '0', '1');
INSERT INTO `zz_area` VALUES ('1646', '大余县', '256', '16,256,1646', '0', '1');
INSERT INTO `zz_area` VALUES ('1647', '上犹县', '256', '16,256,1647', '0', '1');
INSERT INTO `zz_area` VALUES ('1648', '崇义县', '256', '16,256,1648', '0', '1');
INSERT INTO `zz_area` VALUES ('1649', '安远县', '256', '16,256,1649', '0', '1');
INSERT INTO `zz_area` VALUES ('1650', '龙南县', '256', '16,256,1650', '0', '1');
INSERT INTO `zz_area` VALUES ('1651', '定南县', '256', '16,256,1651', '0', '1');
INSERT INTO `zz_area` VALUES ('1652', '全南县', '256', '16,256,1652', '0', '1');
INSERT INTO `zz_area` VALUES ('1653', '宁都县', '256', '16,256,1653', '0', '1');
INSERT INTO `zz_area` VALUES ('1654', '于都县', '256', '16,256,1654', '0', '1');
INSERT INTO `zz_area` VALUES ('1655', '兴国县', '256', '16,256,1655', '0', '1');
INSERT INTO `zz_area` VALUES ('1656', '会昌县', '256', '16,256,1656', '0', '1');
INSERT INTO `zz_area` VALUES ('1657', '寻乌县', '256', '16,256,1657', '0', '1');
INSERT INTO `zz_area` VALUES ('1658', '石城县', '256', '16,256,1658', '0', '1');
INSERT INTO `zz_area` VALUES ('1659', '瑞金市', '256', '16,256,1659', '0', '1');
INSERT INTO `zz_area` VALUES ('1660', '南康市', '256', '16,256,1660', '0', '1');
INSERT INTO `zz_area` VALUES ('1661', '吉州区', '257', '16,257,1661', '0', '1');
INSERT INTO `zz_area` VALUES ('1662', '青原区', '257', '16,257,1662', '0', '1');
INSERT INTO `zz_area` VALUES ('1663', '吉安县', '257', '16,257,1663', '0', '1');
INSERT INTO `zz_area` VALUES ('1664', '吉水县', '257', '16,257,1664', '0', '1');
INSERT INTO `zz_area` VALUES ('1665', '峡江县', '257', '16,257,1665', '0', '1');
INSERT INTO `zz_area` VALUES ('1666', '新干县', '257', '16,257,1666', '0', '1');
INSERT INTO `zz_area` VALUES ('1667', '永丰县', '257', '16,257,1667', '0', '1');
INSERT INTO `zz_area` VALUES ('1668', '泰和县', '257', '16,257,1668', '0', '1');
INSERT INTO `zz_area` VALUES ('1669', '遂川县', '257', '16,257,1669', '0', '1');
INSERT INTO `zz_area` VALUES ('1670', '万安县', '257', '16,257,1670', '0', '1');
INSERT INTO `zz_area` VALUES ('1671', '安福县', '257', '16,257,1671', '0', '1');
INSERT INTO `zz_area` VALUES ('1672', '永新县', '257', '16,257,1672', '0', '1');
INSERT INTO `zz_area` VALUES ('1673', '井冈山市', '257', '16,257,1673', '0', '1');
INSERT INTO `zz_area` VALUES ('1674', '袁州区', '258', '16,258,1674', '0', '1');
INSERT INTO `zz_area` VALUES ('1675', '奉新县', '258', '16,258,1675', '0', '1');
INSERT INTO `zz_area` VALUES ('1676', '万载县', '258', '16,258,1676', '0', '1');
INSERT INTO `zz_area` VALUES ('1677', '上高县', '258', '16,258,1677', '0', '1');
INSERT INTO `zz_area` VALUES ('1678', '宜丰县', '258', '16,258,1678', '0', '1');
INSERT INTO `zz_area` VALUES ('1679', '靖安县', '258', '16,258,1679', '0', '1');
INSERT INTO `zz_area` VALUES ('1680', '铜鼓县', '258', '16,258,1680', '0', '1');
INSERT INTO `zz_area` VALUES ('1681', '丰城市', '258', '16,258,1681', '0', '1');
INSERT INTO `zz_area` VALUES ('1682', '樟树市', '258', '16,258,1682', '0', '1');
INSERT INTO `zz_area` VALUES ('1683', '高安市', '258', '16,258,1683', '0', '1');
INSERT INTO `zz_area` VALUES ('1684', '临川区', '259', '16,259,1684', '0', '1');
INSERT INTO `zz_area` VALUES ('1685', '南城县', '259', '16,259,1685', '0', '1');
INSERT INTO `zz_area` VALUES ('1686', '黎川县', '259', '16,259,1686', '0', '1');
INSERT INTO `zz_area` VALUES ('1687', '南丰县', '259', '16,259,1687', '0', '1');
INSERT INTO `zz_area` VALUES ('1688', '崇仁县', '259', '16,259,1688', '0', '1');
INSERT INTO `zz_area` VALUES ('1689', '乐安县', '259', '16,259,1689', '0', '1');
INSERT INTO `zz_area` VALUES ('1690', '宜黄县', '259', '16,259,1690', '0', '1');
INSERT INTO `zz_area` VALUES ('1691', '金溪县', '259', '16,259,1691', '0', '1');
INSERT INTO `zz_area` VALUES ('1692', '资溪县', '259', '16,259,1692', '0', '1');
INSERT INTO `zz_area` VALUES ('1693', '东乡县', '259', '16,259,1693', '0', '1');
INSERT INTO `zz_area` VALUES ('1694', '广昌县', '259', '16,259,1694', '0', '1');
INSERT INTO `zz_area` VALUES ('1695', '信州区', '260', '16,260,1695', '0', '1');
INSERT INTO `zz_area` VALUES ('1696', '上饶县', '260', '16,260,1696', '0', '1');
INSERT INTO `zz_area` VALUES ('1697', '广丰县', '260', '16,260,1697', '0', '1');
INSERT INTO `zz_area` VALUES ('1698', '玉山县', '260', '16,260,1698', '0', '1');
INSERT INTO `zz_area` VALUES ('1699', '铅山县', '260', '16,260,1699', '0', '1');
INSERT INTO `zz_area` VALUES ('1700', '横峰县', '260', '16,260,1700', '0', '1');
INSERT INTO `zz_area` VALUES ('1701', '弋阳县', '260', '16,260,1701', '0', '1');
INSERT INTO `zz_area` VALUES ('1702', '余干县', '260', '16,260,1702', '0', '1');
INSERT INTO `zz_area` VALUES ('1703', '鄱阳县', '260', '16,260,1703', '0', '1');
INSERT INTO `zz_area` VALUES ('1704', '万年县', '260', '16,260,1704', '0', '1');
INSERT INTO `zz_area` VALUES ('1705', '婺源县', '260', '16,260,1705', '0', '1');
INSERT INTO `zz_area` VALUES ('1706', '德兴市', '260', '16,260,1706', '0', '1');
INSERT INTO `zz_area` VALUES ('1707', '历下区', '261', '17,261,1707', '0', '1');
INSERT INTO `zz_area` VALUES ('1708', '市中区', '261', '17,261,1708', '0', '1');
INSERT INTO `zz_area` VALUES ('1709', '槐荫区', '261', '17,261,1709', '0', '1');
INSERT INTO `zz_area` VALUES ('1710', '天桥区', '261', '17,261,1710', '0', '1');
INSERT INTO `zz_area` VALUES ('1711', '历城区', '261', '17,261,1711', '0', '1');
INSERT INTO `zz_area` VALUES ('1712', '长清区', '261', '17,261,1712', '0', '1');
INSERT INTO `zz_area` VALUES ('1713', '平阴县', '261', '17,261,1713', '0', '1');
INSERT INTO `zz_area` VALUES ('1714', '济阳县', '261', '17,261,1714', '0', '1');
INSERT INTO `zz_area` VALUES ('1715', '商河县', '261', '17,261,1715', '0', '1');
INSERT INTO `zz_area` VALUES ('1716', '章丘市', '261', '17,261,1716', '0', '1');
INSERT INTO `zz_area` VALUES ('1717', '市南区', '262', '17,262,1717', '0', '1');
INSERT INTO `zz_area` VALUES ('1718', '市北区', '262', '17,262,1718', '0', '1');
INSERT INTO `zz_area` VALUES ('1719', '四方区', '262', '17,262,1719', '0', '1');
INSERT INTO `zz_area` VALUES ('1720', '黄岛区', '262', '17,262,1720', '0', '1');
INSERT INTO `zz_area` VALUES ('1721', '崂山区', '262', '17,262,1721', '0', '1');
INSERT INTO `zz_area` VALUES ('1722', '李沧区', '262', '17,262,1722', '0', '1');
INSERT INTO `zz_area` VALUES ('1723', '城阳区', '262', '17,262,1723', '0', '1');
INSERT INTO `zz_area` VALUES ('1724', '胶州市', '262', '17,262,1724', '0', '1');
INSERT INTO `zz_area` VALUES ('1725', '即墨市', '262', '17,262,1725', '0', '1');
INSERT INTO `zz_area` VALUES ('1726', '平度市', '262', '17,262,1726', '0', '1');
INSERT INTO `zz_area` VALUES ('1727', '胶南市', '262', '17,262,1727', '0', '1');
INSERT INTO `zz_area` VALUES ('1728', '莱西市', '262', '17,262,1728', '0', '1');
INSERT INTO `zz_area` VALUES ('1729', '淄川区', '263', '17,263,1729', '0', '1');
INSERT INTO `zz_area` VALUES ('1730', '张店区', '263', '17,263,1730', '0', '1');
INSERT INTO `zz_area` VALUES ('1731', '博山区', '263', '17,263,1731', '0', '1');
INSERT INTO `zz_area` VALUES ('1732', '临淄区', '263', '17,263,1732', '0', '1');
INSERT INTO `zz_area` VALUES ('1733', '周村区', '263', '17,263,1733', '0', '1');
INSERT INTO `zz_area` VALUES ('1734', '桓台县', '263', '17,263,1734', '0', '1');
INSERT INTO `zz_area` VALUES ('1735', '高青县', '263', '17,263,1735', '0', '1');
INSERT INTO `zz_area` VALUES ('1736', '沂源县', '263', '17,263,1736', '0', '1');
INSERT INTO `zz_area` VALUES ('1737', '市中区', '264', '17,264,1737', '0', '1');
INSERT INTO `zz_area` VALUES ('1738', '薛城区', '264', '17,264,1738', '0', '1');
INSERT INTO `zz_area` VALUES ('1739', '峄城区', '264', '17,264,1739', '0', '1');
INSERT INTO `zz_area` VALUES ('1740', '台儿庄区', '264', '17,264,1740', '0', '1');
INSERT INTO `zz_area` VALUES ('1741', '山亭区', '264', '17,264,1741', '0', '1');
INSERT INTO `zz_area` VALUES ('1742', '滕州市', '264', '17,264,1742', '0', '1');
INSERT INTO `zz_area` VALUES ('1743', '东营区', '265', '17,265,1743', '0', '1');
INSERT INTO `zz_area` VALUES ('1744', '河口区', '265', '17,265,1744', '0', '1');
INSERT INTO `zz_area` VALUES ('1745', '垦利县', '265', '17,265,1745', '0', '1');
INSERT INTO `zz_area` VALUES ('1746', '利津县', '265', '17,265,1746', '0', '1');
INSERT INTO `zz_area` VALUES ('1747', '广饶县', '265', '17,265,1747', '0', '1');
INSERT INTO `zz_area` VALUES ('1748', '芝罘区', '266', '17,266,1748', '0', '1');
INSERT INTO `zz_area` VALUES ('1749', '福山区', '266', '17,266,1749', '0', '1');
INSERT INTO `zz_area` VALUES ('1750', '牟平区', '266', '17,266,1750', '0', '1');
INSERT INTO `zz_area` VALUES ('1751', '莱山区', '266', '17,266,1751', '0', '1');
INSERT INTO `zz_area` VALUES ('1752', '长岛县', '266', '17,266,1752', '0', '1');
INSERT INTO `zz_area` VALUES ('1753', '龙口市', '266', '17,266,1753', '0', '1');
INSERT INTO `zz_area` VALUES ('1754', '莱阳市', '266', '17,266,1754', '0', '1');
INSERT INTO `zz_area` VALUES ('1755', '莱州市', '266', '17,266,1755', '0', '1');
INSERT INTO `zz_area` VALUES ('1756', '蓬莱市', '266', '17,266,1756', '0', '1');
INSERT INTO `zz_area` VALUES ('1757', '招远市', '266', '17,266,1757', '0', '1');
INSERT INTO `zz_area` VALUES ('1758', '栖霞市', '266', '17,266,1758', '0', '1');
INSERT INTO `zz_area` VALUES ('1759', '海阳市', '266', '17,266,1759', '0', '1');
INSERT INTO `zz_area` VALUES ('1760', '潍城区', '267', '17,267,1760', '0', '1');
INSERT INTO `zz_area` VALUES ('1761', '寒亭区', '267', '17,267,1761', '0', '1');
INSERT INTO `zz_area` VALUES ('1762', '坊子区', '267', '17,267,1762', '0', '1');
INSERT INTO `zz_area` VALUES ('1763', '奎文区', '267', '17,267,1763', '0', '1');
INSERT INTO `zz_area` VALUES ('1764', '临朐县', '267', '17,267,1764', '0', '1');
INSERT INTO `zz_area` VALUES ('1765', '昌乐县', '267', '17,267,1765', '0', '1');
INSERT INTO `zz_area` VALUES ('1766', '青州市', '267', '17,267,1766', '0', '1');
INSERT INTO `zz_area` VALUES ('1767', '诸城市', '267', '17,267,1767', '0', '1');
INSERT INTO `zz_area` VALUES ('1768', '寿光市', '267', '17,267,1768', '0', '1');
INSERT INTO `zz_area` VALUES ('1769', '安丘市', '267', '17,267,1769', '0', '1');
INSERT INTO `zz_area` VALUES ('1770', '高密市', '267', '17,267,1770', '0', '1');
INSERT INTO `zz_area` VALUES ('1771', '昌邑市', '267', '17,267,1771', '0', '1');
INSERT INTO `zz_area` VALUES ('1772', '市中区', '268', '17,268,1772', '0', '1');
INSERT INTO `zz_area` VALUES ('1773', '任城区', '268', '17,268,1773', '0', '1');
INSERT INTO `zz_area` VALUES ('1774', '微山县', '268', '17,268,1774', '0', '1');
INSERT INTO `zz_area` VALUES ('1775', '鱼台县', '268', '17,268,1775', '0', '1');
INSERT INTO `zz_area` VALUES ('1776', '金乡县', '268', '17,268,1776', '0', '1');
INSERT INTO `zz_area` VALUES ('1777', '嘉祥县', '268', '17,268,1777', '0', '1');
INSERT INTO `zz_area` VALUES ('1778', '汶上县', '268', '17,268,1778', '0', '1');
INSERT INTO `zz_area` VALUES ('1779', '泗水县', '268', '17,268,1779', '0', '1');
INSERT INTO `zz_area` VALUES ('1780', '梁山县', '268', '17,268,1780', '0', '1');
INSERT INTO `zz_area` VALUES ('1781', '曲阜市', '268', '17,268,1781', '0', '1');
INSERT INTO `zz_area` VALUES ('1782', '兖州市', '268', '17,268,1782', '0', '1');
INSERT INTO `zz_area` VALUES ('1783', '邹城市', '268', '17,268,1783', '0', '1');
INSERT INTO `zz_area` VALUES ('1784', '泰山区', '269', '17,269,1784', '0', '1');
INSERT INTO `zz_area` VALUES ('1785', '岱岳区', '269', '17,269,1785', '0', '1');
INSERT INTO `zz_area` VALUES ('1786', '宁阳县', '269', '17,269,1786', '0', '1');
INSERT INTO `zz_area` VALUES ('1787', '东平县', '269', '17,269,1787', '0', '1');
INSERT INTO `zz_area` VALUES ('1788', '新泰市', '269', '17,269,1788', '0', '1');
INSERT INTO `zz_area` VALUES ('1789', '肥城市', '269', '17,269,1789', '0', '1');
INSERT INTO `zz_area` VALUES ('1790', '环翠区', '270', '17,270,1790', '0', '1');
INSERT INTO `zz_area` VALUES ('1791', '文登市', '270', '17,270,1791', '0', '1');
INSERT INTO `zz_area` VALUES ('1792', '荣成市', '270', '17,270,1792', '0', '1');
INSERT INTO `zz_area` VALUES ('1793', '乳山市', '270', '17,270,1793', '0', '1');
INSERT INTO `zz_area` VALUES ('1794', '东港区', '271', '17,271,1794', '0', '1');
INSERT INTO `zz_area` VALUES ('1795', '岚山区', '271', '17,271,1795', '0', '1');
INSERT INTO `zz_area` VALUES ('1796', '五莲县', '271', '17,271,1796', '0', '1');
INSERT INTO `zz_area` VALUES ('1797', '莒县', '271', '17,271,1797', '0', '1');
INSERT INTO `zz_area` VALUES ('1798', '莱城区', '272', '17,272,1798', '0', '1');
INSERT INTO `zz_area` VALUES ('1799', '钢城区', '272', '17,272,1799', '0', '1');
INSERT INTO `zz_area` VALUES ('1800', '兰山区', '273', '17,273,1800', '0', '1');
INSERT INTO `zz_area` VALUES ('1801', '罗庄区', '273', '17,273,1801', '0', '1');
INSERT INTO `zz_area` VALUES ('1802', '河东区', '273', '17,273,1802', '0', '1');
INSERT INTO `zz_area` VALUES ('1803', '沂南县', '273', '17,273,1803', '0', '1');
INSERT INTO `zz_area` VALUES ('1804', '郯城县', '273', '17,273,1804', '0', '1');
INSERT INTO `zz_area` VALUES ('1805', '沂水县', '273', '17,273,1805', '0', '1');
INSERT INTO `zz_area` VALUES ('1806', '苍山县', '273', '17,273,1806', '0', '1');
INSERT INTO `zz_area` VALUES ('1807', '费县', '273', '17,273,1807', '0', '1');
INSERT INTO `zz_area` VALUES ('1808', '平邑县', '273', '17,273,1808', '0', '1');
INSERT INTO `zz_area` VALUES ('1809', '莒南县', '273', '17,273,1809', '0', '1');
INSERT INTO `zz_area` VALUES ('1810', '蒙阴县', '273', '17,273,1810', '0', '1');
INSERT INTO `zz_area` VALUES ('1811', '临沭县', '273', '17,273,1811', '0', '1');
INSERT INTO `zz_area` VALUES ('1812', '德城区', '274', '17,274,1812', '0', '1');
INSERT INTO `zz_area` VALUES ('1813', '陵县', '274', '17,274,1813', '0', '1');
INSERT INTO `zz_area` VALUES ('1814', '宁津县', '274', '17,274,1814', '0', '1');
INSERT INTO `zz_area` VALUES ('1815', '庆云县', '274', '17,274,1815', '0', '1');
INSERT INTO `zz_area` VALUES ('1816', '临邑县', '274', '17,274,1816', '0', '1');
INSERT INTO `zz_area` VALUES ('1817', '齐河县', '274', '17,274,1817', '0', '1');
INSERT INTO `zz_area` VALUES ('1818', '平原县', '274', '17,274,1818', '0', '1');
INSERT INTO `zz_area` VALUES ('1819', '夏津县', '274', '17,274,1819', '0', '1');
INSERT INTO `zz_area` VALUES ('1820', '武城县', '274', '17,274,1820', '0', '1');
INSERT INTO `zz_area` VALUES ('1821', '乐陵市', '274', '17,274,1821', '0', '1');
INSERT INTO `zz_area` VALUES ('1822', '禹城市', '274', '17,274,1822', '0', '1');
INSERT INTO `zz_area` VALUES ('1823', '东昌府区', '275', '17,275,1823', '0', '1');
INSERT INTO `zz_area` VALUES ('1824', '阳谷县', '275', '17,275,1824', '0', '1');
INSERT INTO `zz_area` VALUES ('1825', '莘县', '275', '17,275,1825', '0', '1');
INSERT INTO `zz_area` VALUES ('1826', '茌平县', '275', '17,275,1826', '0', '1');
INSERT INTO `zz_area` VALUES ('1827', '东阿县', '275', '17,275,1827', '0', '1');
INSERT INTO `zz_area` VALUES ('1828', '冠县', '275', '17,275,1828', '0', '1');
INSERT INTO `zz_area` VALUES ('1829', '高唐县', '275', '17,275,1829', '0', '1');
INSERT INTO `zz_area` VALUES ('1830', '临清市', '275', '17,275,1830', '0', '1');
INSERT INTO `zz_area` VALUES ('1831', '滨城区', '276', '17,276,1831', '0', '1');
INSERT INTO `zz_area` VALUES ('1832', '惠民县', '276', '17,276,1832', '0', '1');
INSERT INTO `zz_area` VALUES ('1833', '阳信县', '276', '17,276,1833', '0', '1');
INSERT INTO `zz_area` VALUES ('1834', '无棣县', '276', '17,276,1834', '0', '1');
INSERT INTO `zz_area` VALUES ('1835', '沾化县', '276', '17,276,1835', '0', '1');
INSERT INTO `zz_area` VALUES ('1836', '博兴县', '276', '17,276,1836', '0', '1');
INSERT INTO `zz_area` VALUES ('1837', '邹平县', '276', '17,276,1837', '0', '1');
INSERT INTO `zz_area` VALUES ('1838', '牡丹区', '277', '17,277,1838', '0', '1');
INSERT INTO `zz_area` VALUES ('1839', '曹县', '277', '17,277,1839', '0', '1');
INSERT INTO `zz_area` VALUES ('1840', '单县', '277', '17,277,1840', '0', '1');
INSERT INTO `zz_area` VALUES ('1841', '成武县', '277', '17,277,1841', '0', '1');
INSERT INTO `zz_area` VALUES ('1842', '巨野县', '277', '17,277,1842', '0', '1');
INSERT INTO `zz_area` VALUES ('1843', '郓城县', '277', '17,277,1843', '0', '1');
INSERT INTO `zz_area` VALUES ('1844', '鄄城县', '277', '17,277,1844', '0', '1');
INSERT INTO `zz_area` VALUES ('1845', '定陶县', '277', '17,277,1845', '0', '1');
INSERT INTO `zz_area` VALUES ('1846', '东明县', '277', '17,277,1846', '0', '1');
INSERT INTO `zz_area` VALUES ('1847', '中原区', '278', '18,278,1847', '0', '1');
INSERT INTO `zz_area` VALUES ('1848', '二七区', '278', '18,278,1848', '0', '1');
INSERT INTO `zz_area` VALUES ('1849', '管城回族区', '278', '18,278,1849', '0', '1');
INSERT INTO `zz_area` VALUES ('1850', '金水区', '278', '18,278,1850', '0', '1');
INSERT INTO `zz_area` VALUES ('1851', '上街区', '278', '18,278,1851', '0', '1');
INSERT INTO `zz_area` VALUES ('1852', '惠济区', '278', '18,278,1852', '0', '1');
INSERT INTO `zz_area` VALUES ('1853', '中牟县', '278', '18,278,1853', '0', '1');
INSERT INTO `zz_area` VALUES ('1854', '巩义市', '278', '18,278,1854', '0', '1');
INSERT INTO `zz_area` VALUES ('1855', '荥阳市', '278', '18,278,1855', '0', '1');
INSERT INTO `zz_area` VALUES ('1856', '新密市', '278', '18,278,1856', '0', '1');
INSERT INTO `zz_area` VALUES ('1857', '新郑市', '278', '18,278,1857', '0', '1');
INSERT INTO `zz_area` VALUES ('1858', '登封市', '278', '18,278,1858', '0', '1');
INSERT INTO `zz_area` VALUES ('1859', '龙亭区', '279', '18,279,1859', '0', '1');
INSERT INTO `zz_area` VALUES ('1860', '顺河回族区', '279', '18,279,1860', '0', '1');
INSERT INTO `zz_area` VALUES ('1861', '鼓楼区', '279', '18,279,1861', '0', '1');
INSERT INTO `zz_area` VALUES ('1862', '禹王台区', '279', '18,279,1862', '0', '1');
INSERT INTO `zz_area` VALUES ('1863', '金明区', '279', '18,279,1863', '0', '1');
INSERT INTO `zz_area` VALUES ('1864', '杞县', '279', '18,279,1864', '0', '1');
INSERT INTO `zz_area` VALUES ('1865', '通许县', '279', '18,279,1865', '0', '1');
INSERT INTO `zz_area` VALUES ('1866', '尉氏县', '279', '18,279,1866', '0', '1');
INSERT INTO `zz_area` VALUES ('1867', '开封县', '279', '18,279,1867', '0', '1');
INSERT INTO `zz_area` VALUES ('1868', '兰考县', '279', '18,279,1868', '0', '1');
INSERT INTO `zz_area` VALUES ('1869', '老城区', '280', '18,280,1869', '0', '1');
INSERT INTO `zz_area` VALUES ('1870', '西工区', '280', '18,280,1870', '0', '1');
INSERT INTO `zz_area` VALUES ('1871', '廛河回族区', '280', '18,280,1871', '0', '1');
INSERT INTO `zz_area` VALUES ('1872', '涧西区', '280', '18,280,1872', '0', '1');
INSERT INTO `zz_area` VALUES ('1873', '吉利区', '280', '18,280,1873', '0', '1');
INSERT INTO `zz_area` VALUES ('1874', '洛龙区', '280', '18,280,1874', '0', '1');
INSERT INTO `zz_area` VALUES ('1875', '孟津县', '280', '18,280,1875', '0', '1');
INSERT INTO `zz_area` VALUES ('1876', '新安县', '280', '18,280,1876', '0', '1');
INSERT INTO `zz_area` VALUES ('1877', '栾川县', '280', '18,280,1877', '0', '1');
INSERT INTO `zz_area` VALUES ('1878', '嵩县', '280', '18,280,1878', '0', '1');
INSERT INTO `zz_area` VALUES ('1879', '汝阳县', '280', '18,280,1879', '0', '1');
INSERT INTO `zz_area` VALUES ('1880', '宜阳县', '280', '18,280,1880', '0', '1');
INSERT INTO `zz_area` VALUES ('1881', '洛宁县', '280', '18,280,1881', '0', '1');
INSERT INTO `zz_area` VALUES ('1882', '伊川县', '280', '18,280,1882', '0', '1');
INSERT INTO `zz_area` VALUES ('1883', '偃师市', '280', '18,280,1883', '0', '1');
INSERT INTO `zz_area` VALUES ('1884', '新华区', '281', '18,281,1884', '0', '1');
INSERT INTO `zz_area` VALUES ('1885', '卫东区', '281', '18,281,1885', '0', '1');
INSERT INTO `zz_area` VALUES ('1886', '石龙区', '281', '18,281,1886', '0', '1');
INSERT INTO `zz_area` VALUES ('1887', '湛河区', '281', '18,281,1887', '0', '1');
INSERT INTO `zz_area` VALUES ('1888', '宝丰县', '281', '18,281,1888', '0', '1');
INSERT INTO `zz_area` VALUES ('1889', '叶县', '281', '18,281,1889', '0', '1');
INSERT INTO `zz_area` VALUES ('1890', '鲁山县', '281', '18,281,1890', '0', '1');
INSERT INTO `zz_area` VALUES ('1891', '郏县', '281', '18,281,1891', '0', '1');
INSERT INTO `zz_area` VALUES ('1892', '舞钢市', '281', '18,281,1892', '0', '1');
INSERT INTO `zz_area` VALUES ('1893', '汝州市', '281', '18,281,1893', '0', '1');
INSERT INTO `zz_area` VALUES ('1894', '文峰区', '282', '18,282,1894', '0', '1');
INSERT INTO `zz_area` VALUES ('1895', '北关区', '282', '18,282,1895', '0', '1');
INSERT INTO `zz_area` VALUES ('1896', '殷都区', '282', '18,282,1896', '0', '1');
INSERT INTO `zz_area` VALUES ('1897', '龙安区', '282', '18,282,1897', '0', '1');
INSERT INTO `zz_area` VALUES ('1898', '安阳县', '282', '18,282,1898', '0', '1');
INSERT INTO `zz_area` VALUES ('1899', '汤阴县', '282', '18,282,1899', '0', '1');
INSERT INTO `zz_area` VALUES ('1900', '滑县', '282', '18,282,1900', '0', '1');
INSERT INTO `zz_area` VALUES ('1901', '内黄县', '282', '18,282,1901', '0', '1');
INSERT INTO `zz_area` VALUES ('1902', '林州市', '282', '18,282,1902', '0', '1');
INSERT INTO `zz_area` VALUES ('1903', '鹤山区', '283', '18,283,1903', '0', '1');
INSERT INTO `zz_area` VALUES ('1904', '山城区', '283', '18,283,1904', '0', '1');
INSERT INTO `zz_area` VALUES ('1905', '淇滨区', '283', '18,283,1905', '0', '1');
INSERT INTO `zz_area` VALUES ('1906', '浚县', '283', '18,283,1906', '0', '1');
INSERT INTO `zz_area` VALUES ('1907', '淇县', '283', '18,283,1907', '0', '1');
INSERT INTO `zz_area` VALUES ('1908', '红旗区', '284', '18,284,1908', '0', '1');
INSERT INTO `zz_area` VALUES ('1909', '卫滨区', '284', '18,284,1909', '0', '1');
INSERT INTO `zz_area` VALUES ('1910', '凤泉区', '284', '18,284,1910', '0', '1');
INSERT INTO `zz_area` VALUES ('1911', '牧野区', '284', '18,284,1911', '0', '1');
INSERT INTO `zz_area` VALUES ('1912', '新乡县', '284', '18,284,1912', '0', '1');
INSERT INTO `zz_area` VALUES ('1913', '获嘉县', '284', '18,284,1913', '0', '1');
INSERT INTO `zz_area` VALUES ('1914', '原阳县', '284', '18,284,1914', '0', '1');
INSERT INTO `zz_area` VALUES ('1915', '延津县', '284', '18,284,1915', '0', '1');
INSERT INTO `zz_area` VALUES ('1916', '封丘县', '284', '18,284,1916', '0', '1');
INSERT INTO `zz_area` VALUES ('1917', '长垣县', '284', '18,284,1917', '0', '1');
INSERT INTO `zz_area` VALUES ('1918', '卫辉市', '284', '18,284,1918', '0', '1');
INSERT INTO `zz_area` VALUES ('1919', '辉县市', '284', '18,284,1919', '0', '1');
INSERT INTO `zz_area` VALUES ('1920', '解放区', '285', '18,285,1920', '0', '1');
INSERT INTO `zz_area` VALUES ('1921', '中站区', '285', '18,285,1921', '0', '1');
INSERT INTO `zz_area` VALUES ('1922', '马村区', '285', '18,285,1922', '0', '1');
INSERT INTO `zz_area` VALUES ('1923', '山阳区', '285', '18,285,1923', '0', '1');
INSERT INTO `zz_area` VALUES ('1924', '修武县', '285', '18,285,1924', '0', '1');
INSERT INTO `zz_area` VALUES ('1925', '博爱县', '285', '18,285,1925', '0', '1');
INSERT INTO `zz_area` VALUES ('1926', '武陟县', '285', '18,285,1926', '0', '1');
INSERT INTO `zz_area` VALUES ('1927', '温县', '285', '18,285,1927', '0', '1');
INSERT INTO `zz_area` VALUES ('1928', '济源市', '285', '18,285,1928', '0', '1');
INSERT INTO `zz_area` VALUES ('1929', '沁阳市', '285', '18,285,1929', '0', '1');
INSERT INTO `zz_area` VALUES ('1930', '孟州市', '285', '18,285,1930', '0', '1');
INSERT INTO `zz_area` VALUES ('1931', '华龙区', '286', '18,286,1931', '0', '1');
INSERT INTO `zz_area` VALUES ('1932', '清丰县', '286', '18,286,1932', '0', '1');
INSERT INTO `zz_area` VALUES ('1933', '南乐县', '286', '18,286,1933', '0', '1');
INSERT INTO `zz_area` VALUES ('1934', '范县', '286', '18,286,1934', '0', '1');
INSERT INTO `zz_area` VALUES ('1935', '台前县', '286', '18,286,1935', '0', '1');
INSERT INTO `zz_area` VALUES ('1936', '濮阳县', '286', '18,286,1936', '0', '1');
INSERT INTO `zz_area` VALUES ('1937', '魏都区', '287', '18,287,1937', '0', '1');
INSERT INTO `zz_area` VALUES ('1938', '许昌县', '287', '18,287,1938', '0', '1');
INSERT INTO `zz_area` VALUES ('1939', '鄢陵县', '287', '18,287,1939', '0', '1');
INSERT INTO `zz_area` VALUES ('1940', '襄城县', '287', '18,287,1940', '0', '1');
INSERT INTO `zz_area` VALUES ('1941', '禹州市', '287', '18,287,1941', '0', '1');
INSERT INTO `zz_area` VALUES ('1942', '长葛市', '287', '18,287,1942', '0', '1');
INSERT INTO `zz_area` VALUES ('1943', '源汇区', '288', '18,288,1943', '0', '1');
INSERT INTO `zz_area` VALUES ('1944', '郾城区', '288', '18,288,1944', '0', '1');
INSERT INTO `zz_area` VALUES ('1945', '召陵区', '288', '18,288,1945', '0', '1');
INSERT INTO `zz_area` VALUES ('1946', '舞阳县', '288', '18,288,1946', '0', '1');
INSERT INTO `zz_area` VALUES ('1947', '临颍县', '288', '18,288,1947', '0', '1');
INSERT INTO `zz_area` VALUES ('1948', '湖滨区', '289', '18,289,1948', '0', '1');
INSERT INTO `zz_area` VALUES ('1949', '渑池县', '289', '18,289,1949', '0', '1');
INSERT INTO `zz_area` VALUES ('1950', '陕县', '289', '18,289,1950', '0', '1');
INSERT INTO `zz_area` VALUES ('1951', '卢氏县', '289', '18,289,1951', '0', '1');
INSERT INTO `zz_area` VALUES ('1952', '义马市', '289', '18,289,1952', '0', '1');
INSERT INTO `zz_area` VALUES ('1953', '灵宝市', '289', '18,289,1953', '0', '1');
INSERT INTO `zz_area` VALUES ('1954', '宛城区', '290', '18,290,1954', '0', '1');
INSERT INTO `zz_area` VALUES ('1955', '卧龙区', '290', '18,290,1955', '0', '1');
INSERT INTO `zz_area` VALUES ('1956', '南召县', '290', '18,290,1956', '0', '1');
INSERT INTO `zz_area` VALUES ('1957', '方城县', '290', '18,290,1957', '0', '1');
INSERT INTO `zz_area` VALUES ('1958', '西峡县', '290', '18,290,1958', '0', '1');
INSERT INTO `zz_area` VALUES ('1959', '镇平县', '290', '18,290,1959', '0', '1');
INSERT INTO `zz_area` VALUES ('1960', '内乡县', '290', '18,290,1960', '0', '1');
INSERT INTO `zz_area` VALUES ('1961', '淅川县', '290', '18,290,1961', '0', '1');
INSERT INTO `zz_area` VALUES ('1962', '社旗县', '290', '18,290,1962', '0', '1');
INSERT INTO `zz_area` VALUES ('1963', '唐河县', '290', '18,290,1963', '0', '1');
INSERT INTO `zz_area` VALUES ('1964', '新野县', '290', '18,290,1964', '0', '1');
INSERT INTO `zz_area` VALUES ('1965', '桐柏县', '290', '18,290,1965', '0', '1');
INSERT INTO `zz_area` VALUES ('1966', '邓州市', '290', '18,290,1966', '0', '1');
INSERT INTO `zz_area` VALUES ('1967', '梁园区', '291', '18,291,1967', '0', '1');
INSERT INTO `zz_area` VALUES ('1968', '睢阳区', '291', '18,291,1968', '0', '1');
INSERT INTO `zz_area` VALUES ('1969', '民权县', '291', '18,291,1969', '0', '1');
INSERT INTO `zz_area` VALUES ('1970', '睢县', '291', '18,291,1970', '0', '1');
INSERT INTO `zz_area` VALUES ('1971', '宁陵县', '291', '18,291,1971', '0', '1');
INSERT INTO `zz_area` VALUES ('1972', '柘城县', '291', '18,291,1972', '0', '1');
INSERT INTO `zz_area` VALUES ('1973', '虞城县', '291', '18,291,1973', '0', '1');
INSERT INTO `zz_area` VALUES ('1974', '夏邑县', '291', '18,291,1974', '0', '1');
INSERT INTO `zz_area` VALUES ('1975', '永城市', '291', '18,291,1975', '0', '1');
INSERT INTO `zz_area` VALUES ('1976', '浉河区', '292', '18,292,1976', '0', '1');
INSERT INTO `zz_area` VALUES ('1977', '平桥区', '292', '18,292,1977', '0', '1');
INSERT INTO `zz_area` VALUES ('1978', '罗山县', '292', '18,292,1978', '0', '1');
INSERT INTO `zz_area` VALUES ('1979', '光山县', '292', '18,292,1979', '0', '1');
INSERT INTO `zz_area` VALUES ('1980', '新县', '292', '18,292,1980', '0', '1');
INSERT INTO `zz_area` VALUES ('1981', '商城县', '292', '18,292,1981', '0', '1');
INSERT INTO `zz_area` VALUES ('1982', '固始县', '292', '18,292,1982', '0', '1');
INSERT INTO `zz_area` VALUES ('1983', '潢川县', '292', '18,292,1983', '0', '1');
INSERT INTO `zz_area` VALUES ('1984', '淮滨县', '292', '18,292,1984', '0', '1');
INSERT INTO `zz_area` VALUES ('1985', '息县', '292', '18,292,1985', '0', '1');
INSERT INTO `zz_area` VALUES ('1986', '川汇区', '293', '18,293,1986', '0', '1');
INSERT INTO `zz_area` VALUES ('1987', '扶沟县', '293', '18,293,1987', '0', '1');
INSERT INTO `zz_area` VALUES ('1988', '西华县', '293', '18,293,1988', '0', '1');
INSERT INTO `zz_area` VALUES ('1989', '商水县', '293', '18,293,1989', '0', '1');
INSERT INTO `zz_area` VALUES ('1990', '沈丘县', '293', '18,293,1990', '0', '1');
INSERT INTO `zz_area` VALUES ('1991', '郸城县', '293', '18,293,1991', '0', '1');
INSERT INTO `zz_area` VALUES ('1992', '淮阳县', '293', '18,293,1992', '0', '1');
INSERT INTO `zz_area` VALUES ('1993', '太康县', '293', '18,293,1993', '0', '1');
INSERT INTO `zz_area` VALUES ('1994', '鹿邑县', '293', '18,293,1994', '0', '1');
INSERT INTO `zz_area` VALUES ('1995', '项城市', '293', '18,293,1995', '0', '1');
INSERT INTO `zz_area` VALUES ('1996', '驿城区', '294', '18,294,1996', '0', '1');
INSERT INTO `zz_area` VALUES ('1997', '西平县', '294', '18,294,1997', '0', '1');
INSERT INTO `zz_area` VALUES ('1998', '上蔡县', '294', '18,294,1998', '0', '1');
INSERT INTO `zz_area` VALUES ('1999', '平舆县', '294', '18,294,1999', '0', '1');
INSERT INTO `zz_area` VALUES ('2000', '正阳县', '294', '18,294,2000', '0', '1');
INSERT INTO `zz_area` VALUES ('2001', '确山县', '294', '18,294,2001', '0', '1');
INSERT INTO `zz_area` VALUES ('2002', '泌阳县', '294', '18,294,2002', '0', '1');
INSERT INTO `zz_area` VALUES ('2003', '汝南县', '294', '18,294,2003', '0', '1');
INSERT INTO `zz_area` VALUES ('2004', '遂平县', '294', '18,294,2004', '0', '1');
INSERT INTO `zz_area` VALUES ('2005', '新蔡县', '294', '18,294,2005', '0', '1');
INSERT INTO `zz_area` VALUES ('2006', '江岸区', '295', '19,295,2006', '0', '1');
INSERT INTO `zz_area` VALUES ('2007', '江汉区', '295', '19,295,2007', '0', '1');
INSERT INTO `zz_area` VALUES ('2008', '硚口区', '295', '19,295,2008', '0', '1');
INSERT INTO `zz_area` VALUES ('2009', '汉阳区', '295', '19,295,2009', '0', '1');
INSERT INTO `zz_area` VALUES ('2010', '武昌区', '295', '19,295,2010', '0', '1');
INSERT INTO `zz_area` VALUES ('2011', '青山区', '295', '19,295,2011', '0', '1');
INSERT INTO `zz_area` VALUES ('2012', '洪山区', '295', '19,295,2012', '0', '1');
INSERT INTO `zz_area` VALUES ('2013', '东西湖区', '295', '19,295,2013', '0', '1');
INSERT INTO `zz_area` VALUES ('2014', '汉南区', '295', '19,295,2014', '0', '1');
INSERT INTO `zz_area` VALUES ('2015', '蔡甸区', '295', '19,295,2015', '0', '1');
INSERT INTO `zz_area` VALUES ('2016', '江夏区', '295', '19,295,2016', '0', '1');
INSERT INTO `zz_area` VALUES ('2017', '黄陂区', '295', '19,295,2017', '0', '1');
INSERT INTO `zz_area` VALUES ('2018', '新洲区', '295', '19,295,2018', '0', '1');
INSERT INTO `zz_area` VALUES ('2019', '黄石港区', '296', '19,296,2019', '0', '1');
INSERT INTO `zz_area` VALUES ('2020', '西塞山区', '296', '19,296,2020', '0', '1');
INSERT INTO `zz_area` VALUES ('2021', '下陆区', '296', '19,296,2021', '0', '1');
INSERT INTO `zz_area` VALUES ('2022', '铁山区', '296', '19,296,2022', '0', '1');
INSERT INTO `zz_area` VALUES ('2023', '阳新县', '296', '19,296,2023', '0', '1');
INSERT INTO `zz_area` VALUES ('2024', '大冶市', '296', '19,296,2024', '0', '1');
INSERT INTO `zz_area` VALUES ('2025', '茅箭区', '297', '19,297,2025', '0', '1');
INSERT INTO `zz_area` VALUES ('2026', '张湾区', '297', '19,297,2026', '0', '1');
INSERT INTO `zz_area` VALUES ('2027', '郧县', '297', '19,297,2027', '0', '1');
INSERT INTO `zz_area` VALUES ('2028', '郧西县', '297', '19,297,2028', '0', '1');
INSERT INTO `zz_area` VALUES ('2029', '竹山县', '297', '19,297,2029', '0', '1');
INSERT INTO `zz_area` VALUES ('2030', '竹溪县', '297', '19,297,2030', '0', '1');
INSERT INTO `zz_area` VALUES ('2031', '房县', '297', '19,297,2031', '0', '1');
INSERT INTO `zz_area` VALUES ('2032', '丹江口市', '297', '19,297,2032', '0', '1');
INSERT INTO `zz_area` VALUES ('2033', '西陵区', '298', '19,298,2033', '0', '1');
INSERT INTO `zz_area` VALUES ('2034', '伍家岗区', '298', '19,298,2034', '0', '1');
INSERT INTO `zz_area` VALUES ('2035', '点军区', '298', '19,298,2035', '0', '1');
INSERT INTO `zz_area` VALUES ('2036', '猇亭区', '298', '19,298,2036', '0', '1');
INSERT INTO `zz_area` VALUES ('2037', '夷陵区', '298', '19,298,2037', '0', '1');
INSERT INTO `zz_area` VALUES ('2038', '远安县', '298', '19,298,2038', '0', '1');
INSERT INTO `zz_area` VALUES ('2039', '兴山县', '298', '19,298,2039', '0', '1');
INSERT INTO `zz_area` VALUES ('2040', '秭归县', '298', '19,298,2040', '0', '1');
INSERT INTO `zz_area` VALUES ('2041', '长阳土家族自治县', '298', '19,298,2041', '0', '1');
INSERT INTO `zz_area` VALUES ('2042', '五峰土家族自治县', '298', '19,298,2042', '0', '1');
INSERT INTO `zz_area` VALUES ('2043', '宜都市', '298', '19,298,2043', '0', '1');
INSERT INTO `zz_area` VALUES ('2044', '当阳市', '298', '19,298,2044', '0', '1');
INSERT INTO `zz_area` VALUES ('2045', '枝江市', '298', '19,298,2045', '0', '1');
INSERT INTO `zz_area` VALUES ('2046', '襄城区', '299', '19,299,2046', '0', '1');
INSERT INTO `zz_area` VALUES ('2047', '樊城区', '299', '19,299,2047', '0', '1');
INSERT INTO `zz_area` VALUES ('2048', '襄阳区', '299', '19,299,2048', '0', '1');
INSERT INTO `zz_area` VALUES ('2049', '南漳县', '299', '19,299,2049', '0', '1');
INSERT INTO `zz_area` VALUES ('2050', '谷城县', '299', '19,299,2050', '0', '1');
INSERT INTO `zz_area` VALUES ('2051', '保康县', '299', '19,299,2051', '0', '1');
INSERT INTO `zz_area` VALUES ('2052', '老河口市', '299', '19,299,2052', '0', '1');
INSERT INTO `zz_area` VALUES ('2053', '枣阳市', '299', '19,299,2053', '0', '1');
INSERT INTO `zz_area` VALUES ('2054', '宜城市', '299', '19,299,2054', '0', '1');
INSERT INTO `zz_area` VALUES ('2055', '梁子湖区', '300', '19,300,2055', '0', '1');
INSERT INTO `zz_area` VALUES ('2056', '华容区', '300', '19,300,2056', '0', '1');
INSERT INTO `zz_area` VALUES ('2057', '鄂城区', '300', '19,300,2057', '0', '1');
INSERT INTO `zz_area` VALUES ('2058', '东宝区', '301', '19,301,2058', '0', '1');
INSERT INTO `zz_area` VALUES ('2059', '掇刀区', '301', '19,301,2059', '0', '1');
INSERT INTO `zz_area` VALUES ('2060', '京山县', '301', '19,301,2060', '0', '1');
INSERT INTO `zz_area` VALUES ('2061', '沙洋县', '301', '19,301,2061', '0', '1');
INSERT INTO `zz_area` VALUES ('2062', '钟祥市', '301', '19,301,2062', '0', '1');
INSERT INTO `zz_area` VALUES ('2063', '孝南区', '302', '19,302,2063', '0', '1');
INSERT INTO `zz_area` VALUES ('2064', '孝昌县', '302', '19,302,2064', '0', '1');
INSERT INTO `zz_area` VALUES ('2065', '大悟县', '302', '19,302,2065', '0', '1');
INSERT INTO `zz_area` VALUES ('2066', '云梦县', '302', '19,302,2066', '0', '1');
INSERT INTO `zz_area` VALUES ('2067', '应城市', '302', '19,302,2067', '0', '1');
INSERT INTO `zz_area` VALUES ('2068', '安陆市', '302', '19,302,2068', '0', '1');
INSERT INTO `zz_area` VALUES ('2069', '汉川市', '302', '19,302,2069', '0', '1');
INSERT INTO `zz_area` VALUES ('2070', '沙市区', '303', '19,303,2070', '0', '1');
INSERT INTO `zz_area` VALUES ('2071', '荆州区', '303', '19,303,2071', '0', '1');
INSERT INTO `zz_area` VALUES ('2072', '公安县', '303', '19,303,2072', '0', '1');
INSERT INTO `zz_area` VALUES ('2073', '监利县', '303', '19,303,2073', '0', '1');
INSERT INTO `zz_area` VALUES ('2074', '江陵县', '303', '19,303,2074', '0', '1');
INSERT INTO `zz_area` VALUES ('2075', '石首市', '303', '19,303,2075', '0', '1');
INSERT INTO `zz_area` VALUES ('2076', '洪湖市', '303', '19,303,2076', '0', '1');
INSERT INTO `zz_area` VALUES ('2077', '松滋市', '303', '19,303,2077', '0', '1');
INSERT INTO `zz_area` VALUES ('2078', '黄州区', '304', '19,304,2078', '0', '1');
INSERT INTO `zz_area` VALUES ('2079', '团风县', '304', '19,304,2079', '0', '1');
INSERT INTO `zz_area` VALUES ('2080', '红安县', '304', '19,304,2080', '0', '1');
INSERT INTO `zz_area` VALUES ('2081', '罗田县', '304', '19,304,2081', '0', '1');
INSERT INTO `zz_area` VALUES ('2082', '英山县', '304', '19,304,2082', '0', '1');
INSERT INTO `zz_area` VALUES ('2083', '浠水县', '304', '19,304,2083', '0', '1');
INSERT INTO `zz_area` VALUES ('2084', '蕲春县', '304', '19,304,2084', '0', '1');
INSERT INTO `zz_area` VALUES ('2085', '黄梅县', '304', '19,304,2085', '0', '1');
INSERT INTO `zz_area` VALUES ('2086', '麻城市', '304', '19,304,2086', '0', '1');
INSERT INTO `zz_area` VALUES ('2087', '武穴市', '304', '19,304,2087', '0', '1');
INSERT INTO `zz_area` VALUES ('2088', '咸安区', '305', '19,305,2088', '0', '1');
INSERT INTO `zz_area` VALUES ('2089', '嘉鱼县', '305', '19,305,2089', '0', '1');
INSERT INTO `zz_area` VALUES ('2090', '通城县', '305', '19,305,2090', '0', '1');
INSERT INTO `zz_area` VALUES ('2091', '崇阳县', '305', '19,305,2091', '0', '1');
INSERT INTO `zz_area` VALUES ('2092', '通山县', '305', '19,305,2092', '0', '1');
INSERT INTO `zz_area` VALUES ('2093', '赤壁市', '305', '19,305,2093', '0', '1');
INSERT INTO `zz_area` VALUES ('2094', '曾都区', '306', '19,306,2094', '0', '1');
INSERT INTO `zz_area` VALUES ('2095', '广水市', '306', '19,306,2095', '0', '1');
INSERT INTO `zz_area` VALUES ('2096', '恩施市', '307', '19,307,2096', '0', '1');
INSERT INTO `zz_area` VALUES ('2097', '利川市', '307', '19,307,2097', '0', '1');
INSERT INTO `zz_area` VALUES ('2098', '建始县', '307', '19,307,2098', '0', '1');
INSERT INTO `zz_area` VALUES ('2099', '巴东县', '307', '19,307,2099', '0', '1');
INSERT INTO `zz_area` VALUES ('2100', '宣恩县', '307', '19,307,2100', '0', '1');
INSERT INTO `zz_area` VALUES ('2101', '咸丰县', '307', '19,307,2101', '0', '1');
INSERT INTO `zz_area` VALUES ('2102', '来凤县', '307', '19,307,2102', '0', '1');
INSERT INTO `zz_area` VALUES ('2103', '鹤峰县', '307', '19,307,2103', '0', '1');
INSERT INTO `zz_area` VALUES ('2104', '芙蓉区', '312', '20,312,2104', '0', '1');
INSERT INTO `zz_area` VALUES ('2105', '天心区', '312', '20,312,2105', '0', '1');
INSERT INTO `zz_area` VALUES ('2106', '岳麓区', '312', '20,312,2106', '0', '1');
INSERT INTO `zz_area` VALUES ('2107', '开福区', '312', '20,312,2107', '0', '1');
INSERT INTO `zz_area` VALUES ('2108', '雨花区', '312', '20,312,2108', '0', '1');
INSERT INTO `zz_area` VALUES ('2109', '长沙县', '312', '20,312,2109', '0', '1');
INSERT INTO `zz_area` VALUES ('2110', '望城县', '312', '20,312,2110', '0', '1');
INSERT INTO `zz_area` VALUES ('2111', '宁乡县', '312', '20,312,2111', '0', '1');
INSERT INTO `zz_area` VALUES ('2112', '浏阳市', '312', '20,312,2112', '0', '1');
INSERT INTO `zz_area` VALUES ('2113', '荷塘区', '313', '20,313,2113', '0', '1');
INSERT INTO `zz_area` VALUES ('2114', '芦淞区', '313', '20,313,2114', '0', '1');
INSERT INTO `zz_area` VALUES ('2115', '石峰区', '313', '20,313,2115', '0', '1');
INSERT INTO `zz_area` VALUES ('2116', '天元区', '313', '20,313,2116', '0', '1');
INSERT INTO `zz_area` VALUES ('2117', '株洲县', '313', '20,313,2117', '0', '1');
INSERT INTO `zz_area` VALUES ('2118', '攸县', '313', '20,313,2118', '0', '1');
INSERT INTO `zz_area` VALUES ('2119', '茶陵县', '313', '20,313,2119', '0', '1');
INSERT INTO `zz_area` VALUES ('2120', '炎陵县', '313', '20,313,2120', '0', '1');
INSERT INTO `zz_area` VALUES ('2121', '醴陵市', '313', '20,313,2121', '0', '1');
INSERT INTO `zz_area` VALUES ('2122', '雨湖区', '314', '20,314,2122', '0', '1');
INSERT INTO `zz_area` VALUES ('2123', '岳塘区', '314', '20,314,2123', '0', '1');
INSERT INTO `zz_area` VALUES ('2124', '湘潭县', '314', '20,314,2124', '0', '1');
INSERT INTO `zz_area` VALUES ('2125', '湘乡市', '314', '20,314,2125', '0', '1');
INSERT INTO `zz_area` VALUES ('2126', '韶山市', '314', '20,314,2126', '0', '1');
INSERT INTO `zz_area` VALUES ('2127', '珠晖区', '315', '20,315,2127', '0', '1');
INSERT INTO `zz_area` VALUES ('2128', '雁峰区', '315', '20,315,2128', '0', '1');
INSERT INTO `zz_area` VALUES ('2129', '石鼓区', '315', '20,315,2129', '0', '1');
INSERT INTO `zz_area` VALUES ('2130', '蒸湘区', '315', '20,315,2130', '0', '1');
INSERT INTO `zz_area` VALUES ('2131', '南岳区', '315', '20,315,2131', '0', '1');
INSERT INTO `zz_area` VALUES ('2132', '衡阳县', '315', '20,315,2132', '0', '1');
INSERT INTO `zz_area` VALUES ('2133', '衡南县', '315', '20,315,2133', '0', '1');
INSERT INTO `zz_area` VALUES ('2134', '衡山县', '315', '20,315,2134', '0', '1');
INSERT INTO `zz_area` VALUES ('2135', '衡东县', '315', '20,315,2135', '0', '1');
INSERT INTO `zz_area` VALUES ('2136', '祁东县', '315', '20,315,2136', '0', '1');
INSERT INTO `zz_area` VALUES ('2137', '耒阳市', '315', '20,315,2137', '0', '1');
INSERT INTO `zz_area` VALUES ('2138', '常宁市', '315', '20,315,2138', '0', '1');
INSERT INTO `zz_area` VALUES ('2139', '双清区', '316', '20,316,2139', '0', '1');
INSERT INTO `zz_area` VALUES ('2140', '大祥区', '316', '20,316,2140', '0', '1');
INSERT INTO `zz_area` VALUES ('2141', '北塔区', '316', '20,316,2141', '0', '1');
INSERT INTO `zz_area` VALUES ('2142', '邵东县', '316', '20,316,2142', '0', '1');
INSERT INTO `zz_area` VALUES ('2143', '新邵县', '316', '20,316,2143', '0', '1');
INSERT INTO `zz_area` VALUES ('2144', '邵阳县', '316', '20,316,2144', '0', '1');
INSERT INTO `zz_area` VALUES ('2145', '隆回县', '316', '20,316,2145', '0', '1');
INSERT INTO `zz_area` VALUES ('2146', '洞口县', '316', '20,316,2146', '0', '1');
INSERT INTO `zz_area` VALUES ('2147', '绥宁县', '316', '20,316,2147', '0', '1');
INSERT INTO `zz_area` VALUES ('2148', '新宁县', '316', '20,316,2148', '0', '1');
INSERT INTO `zz_area` VALUES ('2149', '城步苗族自治县', '316', '20,316,2149', '0', '1');
INSERT INTO `zz_area` VALUES ('2150', '武冈市', '316', '20,316,2150', '0', '1');
INSERT INTO `zz_area` VALUES ('2151', '岳阳楼区', '317', '20,317,2151', '0', '1');
INSERT INTO `zz_area` VALUES ('2152', '云溪区', '317', '20,317,2152', '0', '1');
INSERT INTO `zz_area` VALUES ('2153', '君山区', '317', '20,317,2153', '0', '1');
INSERT INTO `zz_area` VALUES ('2154', '岳阳县', '317', '20,317,2154', '0', '1');
INSERT INTO `zz_area` VALUES ('2155', '华容县', '317', '20,317,2155', '0', '1');
INSERT INTO `zz_area` VALUES ('2156', '湘阴县', '317', '20,317,2156', '0', '1');
INSERT INTO `zz_area` VALUES ('2157', '平江县', '317', '20,317,2157', '0', '1');
INSERT INTO `zz_area` VALUES ('2158', '汨罗市', '317', '20,317,2158', '0', '1');
INSERT INTO `zz_area` VALUES ('2159', '临湘市', '317', '20,317,2159', '0', '1');
INSERT INTO `zz_area` VALUES ('2160', '武陵区', '318', '20,318,2160', '0', '1');
INSERT INTO `zz_area` VALUES ('2161', '鼎城区', '318', '20,318,2161', '0', '1');
INSERT INTO `zz_area` VALUES ('2162', '安乡县', '318', '20,318,2162', '0', '1');
INSERT INTO `zz_area` VALUES ('2163', '汉寿县', '318', '20,318,2163', '0', '1');
INSERT INTO `zz_area` VALUES ('2164', '澧县', '318', '20,318,2164', '0', '1');
INSERT INTO `zz_area` VALUES ('2165', '临澧县', '318', '20,318,2165', '0', '1');
INSERT INTO `zz_area` VALUES ('2166', '桃源县', '318', '20,318,2166', '0', '1');
INSERT INTO `zz_area` VALUES ('2167', '石门县', '318', '20,318,2167', '0', '1');
INSERT INTO `zz_area` VALUES ('2168', '津市市', '318', '20,318,2168', '0', '1');
INSERT INTO `zz_area` VALUES ('2169', '永定区', '319', '20,319,2169', '0', '1');
INSERT INTO `zz_area` VALUES ('2170', '武陵源区', '319', '20,319,2170', '0', '1');
INSERT INTO `zz_area` VALUES ('2171', '慈利县', '319', '20,319,2171', '0', '1');
INSERT INTO `zz_area` VALUES ('2172', '桑植县', '319', '20,319,2172', '0', '1');
INSERT INTO `zz_area` VALUES ('2173', '资阳区', '320', '20,320,2173', '0', '1');
INSERT INTO `zz_area` VALUES ('2174', '赫山区', '320', '20,320,2174', '0', '1');
INSERT INTO `zz_area` VALUES ('2175', '南县', '320', '20,320,2175', '0', '1');
INSERT INTO `zz_area` VALUES ('2176', '桃江县', '320', '20,320,2176', '0', '1');
INSERT INTO `zz_area` VALUES ('2177', '安化县', '320', '20,320,2177', '0', '1');
INSERT INTO `zz_area` VALUES ('2178', '沅江市', '320', '20,320,2178', '0', '1');
INSERT INTO `zz_area` VALUES ('2179', '北湖区', '321', '20,321,2179', '0', '1');
INSERT INTO `zz_area` VALUES ('2180', '苏仙区', '321', '20,321,2180', '0', '1');
INSERT INTO `zz_area` VALUES ('2181', '桂阳县', '321', '20,321,2181', '0', '1');
INSERT INTO `zz_area` VALUES ('2182', '宜章县', '321', '20,321,2182', '0', '1');
INSERT INTO `zz_area` VALUES ('2183', '永兴县', '321', '20,321,2183', '0', '1');
INSERT INTO `zz_area` VALUES ('2184', '嘉禾县', '321', '20,321,2184', '0', '1');
INSERT INTO `zz_area` VALUES ('2185', '临武县', '321', '20,321,2185', '0', '1');
INSERT INTO `zz_area` VALUES ('2186', '汝城县', '321', '20,321,2186', '0', '1');
INSERT INTO `zz_area` VALUES ('2187', '桂东县', '321', '20,321,2187', '0', '1');
INSERT INTO `zz_area` VALUES ('2188', '安仁县', '321', '20,321,2188', '0', '1');
INSERT INTO `zz_area` VALUES ('2189', '资兴市', '321', '20,321,2189', '0', '1');
INSERT INTO `zz_area` VALUES ('2190', '零陵区', '322', '20,322,2190', '0', '1');
INSERT INTO `zz_area` VALUES ('2191', '冷水滩区', '322', '20,322,2191', '0', '1');
INSERT INTO `zz_area` VALUES ('2192', '祁阳县', '322', '20,322,2192', '0', '1');
INSERT INTO `zz_area` VALUES ('2193', '东安县', '322', '20,322,2193', '0', '1');
INSERT INTO `zz_area` VALUES ('2194', '双牌县', '322', '20,322,2194', '0', '1');
INSERT INTO `zz_area` VALUES ('2195', '道县', '322', '20,322,2195', '0', '1');
INSERT INTO `zz_area` VALUES ('2196', '江永县', '322', '20,322,2196', '0', '1');
INSERT INTO `zz_area` VALUES ('2197', '宁远县', '322', '20,322,2197', '0', '1');
INSERT INTO `zz_area` VALUES ('2198', '蓝山县', '322', '20,322,2198', '0', '1');
INSERT INTO `zz_area` VALUES ('2199', '新田县', '322', '20,322,2199', '0', '1');
INSERT INTO `zz_area` VALUES ('2200', '江华瑶族自治县', '322', '20,322,2200', '0', '1');
INSERT INTO `zz_area` VALUES ('2201', '鹤城区', '323', '20,323,2201', '0', '1');
INSERT INTO `zz_area` VALUES ('2202', '中方县', '323', '20,323,2202', '0', '1');
INSERT INTO `zz_area` VALUES ('2203', '沅陵县', '323', '20,323,2203', '0', '1');
INSERT INTO `zz_area` VALUES ('2204', '辰溪县', '323', '20,323,2204', '0', '1');
INSERT INTO `zz_area` VALUES ('2205', '溆浦县', '323', '20,323,2205', '0', '1');
INSERT INTO `zz_area` VALUES ('2206', '会同县', '323', '20,323,2206', '0', '1');
INSERT INTO `zz_area` VALUES ('2207', '麻阳苗族自治县', '323', '20,323,2207', '0', '1');
INSERT INTO `zz_area` VALUES ('2208', '新晃侗族自治县', '323', '20,323,2208', '0', '1');
INSERT INTO `zz_area` VALUES ('2209', '芷江侗族自治县', '323', '20,323,2209', '0', '1');
INSERT INTO `zz_area` VALUES ('2210', '靖州苗族侗族自治县', '323', '20,323,2210', '0', '1');
INSERT INTO `zz_area` VALUES ('2211', '通道侗族自治县', '323', '20,323,2211', '0', '1');
INSERT INTO `zz_area` VALUES ('2212', '洪江市', '323', '20,323,2212', '0', '1');
INSERT INTO `zz_area` VALUES ('2213', '娄星区', '324', '20,324,2213', '0', '1');
INSERT INTO `zz_area` VALUES ('2214', '双峰县', '324', '20,324,2214', '0', '1');
INSERT INTO `zz_area` VALUES ('2215', '新化县', '324', '20,324,2215', '0', '1');
INSERT INTO `zz_area` VALUES ('2216', '冷水江市', '324', '20,324,2216', '0', '1');
INSERT INTO `zz_area` VALUES ('2217', '涟源市', '324', '20,324,2217', '0', '1');
INSERT INTO `zz_area` VALUES ('2218', '吉首市', '325', '20,325,2218', '0', '1');
INSERT INTO `zz_area` VALUES ('2219', '泸溪县', '325', '20,325,2219', '0', '1');
INSERT INTO `zz_area` VALUES ('2220', '凤凰县', '325', '20,325,2220', '0', '1');
INSERT INTO `zz_area` VALUES ('2221', '花垣县', '325', '20,325,2221', '0', '1');
INSERT INTO `zz_area` VALUES ('2222', '保靖县', '325', '20,325,2222', '0', '1');
INSERT INTO `zz_area` VALUES ('2223', '古丈县', '325', '20,325,2223', '0', '1');
INSERT INTO `zz_area` VALUES ('2224', '永顺县', '325', '20,325,2224', '0', '1');
INSERT INTO `zz_area` VALUES ('2225', '龙山县', '325', '20,325,2225', '0', '1');
INSERT INTO `zz_area` VALUES ('2226', '荔湾区', '326', '21,326,2226', '0', '1');
INSERT INTO `zz_area` VALUES ('2227', '越秀区', '326', '21,326,2227', '0', '1');
INSERT INTO `zz_area` VALUES ('2228', '海珠区', '326', '21,326,2228', '0', '1');
INSERT INTO `zz_area` VALUES ('2229', '天河区', '326', '21,326,2229', '0', '1');
INSERT INTO `zz_area` VALUES ('2230', '白云区', '326', '21,326,2230', '0', '1');
INSERT INTO `zz_area` VALUES ('2231', '黄埔区', '326', '21,326,2231', '0', '1');
INSERT INTO `zz_area` VALUES ('2232', '番禺区', '326', '21,326,2232', '0', '1');
INSERT INTO `zz_area` VALUES ('2233', '花都区', '326', '21,326,2233', '0', '1');
INSERT INTO `zz_area` VALUES ('2234', '南沙区', '326', '21,326,2234', '0', '1');
INSERT INTO `zz_area` VALUES ('2235', '萝岗区', '326', '21,326,2235', '0', '1');
INSERT INTO `zz_area` VALUES ('2236', '增城市', '326', '21,326,2236', '0', '1');
INSERT INTO `zz_area` VALUES ('2237', '从化市', '326', '21,326,2237', '0', '1');
INSERT INTO `zz_area` VALUES ('2238', '武江区', '327', '21,327,2238', '0', '1');
INSERT INTO `zz_area` VALUES ('2239', '浈江区', '327', '21,327,2239', '0', '1');
INSERT INTO `zz_area` VALUES ('2240', '曲江区', '327', '21,327,2240', '0', '1');
INSERT INTO `zz_area` VALUES ('2241', '始兴县', '327', '21,327,2241', '0', '1');
INSERT INTO `zz_area` VALUES ('2242', '仁化县', '327', '21,327,2242', '0', '1');
INSERT INTO `zz_area` VALUES ('2243', '翁源县', '327', '21,327,2243', '0', '1');
INSERT INTO `zz_area` VALUES ('2244', '乳源瑶族自治县', '327', '21,327,2244', '0', '1');
INSERT INTO `zz_area` VALUES ('2245', '新丰县', '327', '21,327,2245', '0', '1');
INSERT INTO `zz_area` VALUES ('2246', '乐昌市', '327', '21,327,2246', '0', '1');
INSERT INTO `zz_area` VALUES ('2247', '南雄市', '327', '21,327,2247', '0', '1');
INSERT INTO `zz_area` VALUES ('2248', '罗湖区', '328', '21,328,2248', '0', '1');
INSERT INTO `zz_area` VALUES ('2249', '福田区', '328', '21,328,2249', '0', '1');
INSERT INTO `zz_area` VALUES ('2250', '南山区', '328', '21,328,2250', '0', '1');
INSERT INTO `zz_area` VALUES ('2251', '宝安区', '328', '21,328,2251', '0', '1');
INSERT INTO `zz_area` VALUES ('2252', '龙岗区', '328', '21,328,2252', '0', '1');
INSERT INTO `zz_area` VALUES ('2253', '盐田区', '328', '21,328,2253', '0', '1');
INSERT INTO `zz_area` VALUES ('2254', '香洲区', '329', '21,329,2254', '0', '1');
INSERT INTO `zz_area` VALUES ('2255', '斗门区', '329', '21,329,2255', '0', '1');
INSERT INTO `zz_area` VALUES ('2256', '金湾区', '329', '21,329,2256', '0', '1');
INSERT INTO `zz_area` VALUES ('2257', '龙湖区', '330', '21,330,2257', '0', '1');
INSERT INTO `zz_area` VALUES ('2258', '金平区', '330', '21,330,2258', '0', '1');
INSERT INTO `zz_area` VALUES ('2259', '濠江区', '330', '21,330,2259', '0', '1');
INSERT INTO `zz_area` VALUES ('2260', '潮阳区', '330', '21,330,2260', '0', '1');
INSERT INTO `zz_area` VALUES ('2261', '潮南区', '330', '21,330,2261', '0', '1');
INSERT INTO `zz_area` VALUES ('2262', '澄海区', '330', '21,330,2262', '0', '1');
INSERT INTO `zz_area` VALUES ('2263', '南澳县', '330', '21,330,2263', '0', '1');
INSERT INTO `zz_area` VALUES ('2264', '禅城区', '331', '21,331,2264', '0', '1');
INSERT INTO `zz_area` VALUES ('2265', '南海区', '331', '21,331,2265', '0', '1');
INSERT INTO `zz_area` VALUES ('2266', '顺德区', '331', '21,331,2266', '0', '1');
INSERT INTO `zz_area` VALUES ('2267', '三水区', '331', '21,331,2267', '0', '1');
INSERT INTO `zz_area` VALUES ('2268', '高明区', '331', '21,331,2268', '0', '1');
INSERT INTO `zz_area` VALUES ('2269', '蓬江区', '332', '21,332,2269', '0', '1');
INSERT INTO `zz_area` VALUES ('2270', '江海区', '332', '21,332,2270', '0', '1');
INSERT INTO `zz_area` VALUES ('2271', '新会区', '332', '21,332,2271', '0', '1');
INSERT INTO `zz_area` VALUES ('2272', '台山市', '332', '21,332,2272', '0', '1');
INSERT INTO `zz_area` VALUES ('2273', '开平市', '332', '21,332,2273', '0', '1');
INSERT INTO `zz_area` VALUES ('2274', '鹤山市', '332', '21,332,2274', '0', '1');
INSERT INTO `zz_area` VALUES ('2275', '恩平市', '332', '21,332,2275', '0', '1');
INSERT INTO `zz_area` VALUES ('2276', '赤坎区', '333', '21,333,2276', '0', '1');
INSERT INTO `zz_area` VALUES ('2277', '霞山区', '333', '21,333,2277', '0', '1');
INSERT INTO `zz_area` VALUES ('2278', '坡头区', '333', '21,333,2278', '0', '1');
INSERT INTO `zz_area` VALUES ('2279', '麻章区', '333', '21,333,2279', '0', '1');
INSERT INTO `zz_area` VALUES ('2280', '遂溪县', '333', '21,333,2280', '0', '1');
INSERT INTO `zz_area` VALUES ('2281', '徐闻县', '333', '21,333,2281', '0', '1');
INSERT INTO `zz_area` VALUES ('2282', '廉江市', '333', '21,333,2282', '0', '1');
INSERT INTO `zz_area` VALUES ('2283', '雷州市', '333', '21,333,2283', '0', '1');
INSERT INTO `zz_area` VALUES ('2284', '吴川市', '333', '21,333,2284', '0', '1');
INSERT INTO `zz_area` VALUES ('2285', '茂南区', '334', '21,334,2285', '0', '1');
INSERT INTO `zz_area` VALUES ('2286', '茂港区', '334', '21,334,2286', '0', '1');
INSERT INTO `zz_area` VALUES ('2287', '电白县', '334', '21,334,2287', '0', '1');
INSERT INTO `zz_area` VALUES ('2288', '高州市', '334', '21,334,2288', '0', '1');
INSERT INTO `zz_area` VALUES ('2289', '化州市', '334', '21,334,2289', '0', '1');
INSERT INTO `zz_area` VALUES ('2290', '信宜市', '334', '21,334,2290', '0', '1');
INSERT INTO `zz_area` VALUES ('2291', '端州区', '335', '21,335,2291', '0', '1');
INSERT INTO `zz_area` VALUES ('2292', '鼎湖区', '335', '21,335,2292', '0', '1');
INSERT INTO `zz_area` VALUES ('2293', '广宁县', '335', '21,335,2293', '0', '1');
INSERT INTO `zz_area` VALUES ('2294', '怀集县', '335', '21,335,2294', '0', '1');
INSERT INTO `zz_area` VALUES ('2295', '封开县', '335', '21,335,2295', '0', '1');
INSERT INTO `zz_area` VALUES ('2296', '德庆县', '335', '21,335,2296', '0', '1');
INSERT INTO `zz_area` VALUES ('2297', '高要市', '335', '21,335,2297', '0', '1');
INSERT INTO `zz_area` VALUES ('2298', '四会市', '335', '21,335,2298', '0', '1');
INSERT INTO `zz_area` VALUES ('2299', '惠城区', '336', '21,336,2299', '0', '1');
INSERT INTO `zz_area` VALUES ('2300', '惠阳区', '336', '21,336,2300', '0', '1');
INSERT INTO `zz_area` VALUES ('2301', '博罗县', '336', '21,336,2301', '0', '1');
INSERT INTO `zz_area` VALUES ('2302', '惠东县', '336', '21,336,2302', '0', '1');
INSERT INTO `zz_area` VALUES ('2303', '龙门县', '336', '21,336,2303', '0', '1');
INSERT INTO `zz_area` VALUES ('2304', '梅江区', '337', '21,337,2304', '0', '1');
INSERT INTO `zz_area` VALUES ('2305', '梅县', '337', '21,337,2305', '0', '1');
INSERT INTO `zz_area` VALUES ('2306', '大埔县', '337', '21,337,2306', '0', '1');
INSERT INTO `zz_area` VALUES ('2307', '丰顺县', '337', '21,337,2307', '0', '1');
INSERT INTO `zz_area` VALUES ('2308', '五华县', '337', '21,337,2308', '0', '1');
INSERT INTO `zz_area` VALUES ('2309', '平远县', '337', '21,337,2309', '0', '1');
INSERT INTO `zz_area` VALUES ('2310', '蕉岭县', '337', '21,337,2310', '0', '1');
INSERT INTO `zz_area` VALUES ('2311', '兴宁市', '337', '21,337,2311', '0', '1');
INSERT INTO `zz_area` VALUES ('2312', '城区', '338', '21,338,2312', '0', '1');
INSERT INTO `zz_area` VALUES ('2313', '海丰县', '338', '21,338,2313', '0', '1');
INSERT INTO `zz_area` VALUES ('2314', '陆河县', '338', '21,338,2314', '0', '1');
INSERT INTO `zz_area` VALUES ('2315', '陆丰市', '338', '21,338,2315', '0', '1');
INSERT INTO `zz_area` VALUES ('2316', '源城区', '339', '21,339,2316', '0', '1');
INSERT INTO `zz_area` VALUES ('2317', '紫金县', '339', '21,339,2317', '0', '1');
INSERT INTO `zz_area` VALUES ('2318', '龙川县', '339', '21,339,2318', '0', '1');
INSERT INTO `zz_area` VALUES ('2319', '连平县', '339', '21,339,2319', '0', '1');
INSERT INTO `zz_area` VALUES ('2320', '和平县', '339', '21,339,2320', '0', '1');
INSERT INTO `zz_area` VALUES ('2321', '东源县', '339', '21,339,2321', '0', '1');
INSERT INTO `zz_area` VALUES ('2322', '江城区', '340', '21,340,2322', '0', '1');
INSERT INTO `zz_area` VALUES ('2323', '阳西县', '340', '21,340,2323', '0', '1');
INSERT INTO `zz_area` VALUES ('2324', '阳东县', '340', '21,340,2324', '0', '1');
INSERT INTO `zz_area` VALUES ('2325', '阳春市', '340', '21,340,2325', '0', '1');
INSERT INTO `zz_area` VALUES ('2326', '清城区', '341', '21,341,2326', '0', '1');
INSERT INTO `zz_area` VALUES ('2327', '佛冈县', '341', '21,341,2327', '0', '1');
INSERT INTO `zz_area` VALUES ('2328', '阳山县', '341', '21,341,2328', '0', '1');
INSERT INTO `zz_area` VALUES ('2329', '连山壮族瑶族自治县', '341', '21,341,2329', '0', '1');
INSERT INTO `zz_area` VALUES ('2330', '连南瑶族自治县', '341', '21,341,2330', '0', '1');
INSERT INTO `zz_area` VALUES ('2331', '清新县', '341', '21,341,2331', '0', '1');
INSERT INTO `zz_area` VALUES ('2332', '英德市', '341', '21,341,2332', '0', '1');
INSERT INTO `zz_area` VALUES ('2333', '连州市', '341', '21,341,2333', '0', '1');
INSERT INTO `zz_area` VALUES ('2334', '湘桥区', '344', '21,344,2334', '0', '1');
INSERT INTO `zz_area` VALUES ('2335', '潮安县', '344', '21,344,2335', '0', '1');
INSERT INTO `zz_area` VALUES ('2336', '饶平县', '344', '21,344,2336', '0', '1');
INSERT INTO `zz_area` VALUES ('2337', '榕城区', '345', '21,345,2337', '0', '1');
INSERT INTO `zz_area` VALUES ('2338', '揭东县', '345', '21,345,2338', '0', '1');
INSERT INTO `zz_area` VALUES ('2339', '揭西县', '345', '21,345,2339', '0', '1');
INSERT INTO `zz_area` VALUES ('2340', '惠来县', '345', '21,345,2340', '0', '1');
INSERT INTO `zz_area` VALUES ('2341', '普宁市', '345', '21,345,2341', '0', '1');
INSERT INTO `zz_area` VALUES ('2342', '云城区', '346', '21,346,2342', '0', '1');
INSERT INTO `zz_area` VALUES ('2343', '新兴县', '346', '21,346,2343', '0', '1');
INSERT INTO `zz_area` VALUES ('2344', '郁南县', '346', '21,346,2344', '0', '1');
INSERT INTO `zz_area` VALUES ('2345', '云安县', '346', '21,346,2345', '0', '1');
INSERT INTO `zz_area` VALUES ('2346', '罗定市', '346', '21,346,2346', '0', '1');
INSERT INTO `zz_area` VALUES ('2347', '兴宁区', '347', '22,347,2347', '0', '1');
INSERT INTO `zz_area` VALUES ('2348', '青秀区', '347', '22,347,2348', '0', '1');
INSERT INTO `zz_area` VALUES ('2349', '江南区', '347', '22,347,2349', '0', '1');
INSERT INTO `zz_area` VALUES ('2350', '西乡塘区', '347', '22,347,2350', '0', '1');
INSERT INTO `zz_area` VALUES ('2351', '良庆区', '347', '22,347,2351', '0', '1');
INSERT INTO `zz_area` VALUES ('2352', '邕宁区', '347', '22,347,2352', '0', '1');
INSERT INTO `zz_area` VALUES ('2353', '武鸣县', '347', '22,347,2353', '0', '1');
INSERT INTO `zz_area` VALUES ('2354', '隆安县', '347', '22,347,2354', '0', '1');
INSERT INTO `zz_area` VALUES ('2355', '马山县', '347', '22,347,2355', '0', '1');
INSERT INTO `zz_area` VALUES ('2356', '上林县', '347', '22,347,2356', '0', '1');
INSERT INTO `zz_area` VALUES ('2357', '宾阳县', '347', '22,347,2357', '0', '1');
INSERT INTO `zz_area` VALUES ('2358', '横县', '347', '22,347,2358', '0', '1');
INSERT INTO `zz_area` VALUES ('2359', '城中区', '348', '22,348,2359', '0', '1');
INSERT INTO `zz_area` VALUES ('2360', '鱼峰区', '348', '22,348,2360', '0', '1');
INSERT INTO `zz_area` VALUES ('2361', '柳南区', '348', '22,348,2361', '0', '1');
INSERT INTO `zz_area` VALUES ('2362', '柳北区', '348', '22,348,2362', '0', '1');
INSERT INTO `zz_area` VALUES ('2363', '柳江县', '348', '22,348,2363', '0', '1');
INSERT INTO `zz_area` VALUES ('2364', '柳城县', '348', '22,348,2364', '0', '1');
INSERT INTO `zz_area` VALUES ('2365', '鹿寨县', '348', '22,348,2365', '0', '1');
INSERT INTO `zz_area` VALUES ('2366', '融安县', '348', '22,348,2366', '0', '1');
INSERT INTO `zz_area` VALUES ('2367', '融水苗族自治县', '348', '22,348,2367', '0', '1');
INSERT INTO `zz_area` VALUES ('2368', '三江侗族自治县', '348', '22,348,2368', '0', '1');
INSERT INTO `zz_area` VALUES ('2369', '秀峰区', '349', '22,349,2369', '0', '1');
INSERT INTO `zz_area` VALUES ('2370', '叠彩区', '349', '22,349,2370', '0', '1');
INSERT INTO `zz_area` VALUES ('2371', '象山区', '349', '22,349,2371', '0', '1');
INSERT INTO `zz_area` VALUES ('2372', '七星区', '349', '22,349,2372', '0', '1');
INSERT INTO `zz_area` VALUES ('2373', '雁山区', '349', '22,349,2373', '0', '1');
INSERT INTO `zz_area` VALUES ('2374', '阳朔县', '349', '22,349,2374', '0', '1');
INSERT INTO `zz_area` VALUES ('2375', '临桂县', '349', '22,349,2375', '0', '1');
INSERT INTO `zz_area` VALUES ('2376', '灵川县', '349', '22,349,2376', '0', '1');
INSERT INTO `zz_area` VALUES ('2377', '全州县', '349', '22,349,2377', '0', '1');
INSERT INTO `zz_area` VALUES ('2378', '兴安县', '349', '22,349,2378', '0', '1');
INSERT INTO `zz_area` VALUES ('2379', '永福县', '349', '22,349,2379', '0', '1');
INSERT INTO `zz_area` VALUES ('2380', '灌阳县', '349', '22,349,2380', '0', '1');
INSERT INTO `zz_area` VALUES ('2381', '龙胜各族自治县', '349', '22,349,2381', '0', '1');
INSERT INTO `zz_area` VALUES ('2382', '资源县', '349', '22,349,2382', '0', '1');
INSERT INTO `zz_area` VALUES ('2383', '平乐县', '349', '22,349,2383', '0', '1');
INSERT INTO `zz_area` VALUES ('2384', '荔蒲县', '349', '22,349,2384', '0', '1');
INSERT INTO `zz_area` VALUES ('2385', '恭城瑶族自治县', '349', '22,349,2385', '0', '1');
INSERT INTO `zz_area` VALUES ('2386', '万秀区', '350', '22,350,2386', '0', '1');
INSERT INTO `zz_area` VALUES ('2387', '蝶山区', '350', '22,350,2387', '0', '1');
INSERT INTO `zz_area` VALUES ('2388', '长洲区', '350', '22,350,2388', '0', '1');
INSERT INTO `zz_area` VALUES ('2389', '苍梧县', '350', '22,350,2389', '0', '1');
INSERT INTO `zz_area` VALUES ('2390', '藤县', '350', '22,350,2390', '0', '1');
INSERT INTO `zz_area` VALUES ('2391', '蒙山县', '350', '22,350,2391', '0', '1');
INSERT INTO `zz_area` VALUES ('2392', '岑溪市', '350', '22,350,2392', '0', '1');
INSERT INTO `zz_area` VALUES ('2393', '海城区', '351', '22,351,2393', '0', '1');
INSERT INTO `zz_area` VALUES ('2394', '银海区', '351', '22,351,2394', '0', '1');
INSERT INTO `zz_area` VALUES ('2395', '铁山港区', '351', '22,351,2395', '0', '1');
INSERT INTO `zz_area` VALUES ('2396', '合浦县', '351', '22,351,2396', '0', '1');
INSERT INTO `zz_area` VALUES ('2397', '港口区', '352', '22,352,2397', '0', '1');
INSERT INTO `zz_area` VALUES ('2398', '防城区', '352', '22,352,2398', '0', '1');
INSERT INTO `zz_area` VALUES ('2399', '上思县', '352', '22,352,2399', '0', '1');
INSERT INTO `zz_area` VALUES ('2400', '东兴市', '352', '22,352,2400', '0', '1');
INSERT INTO `zz_area` VALUES ('2401', '钦南区', '353', '22,353,2401', '0', '1');
INSERT INTO `zz_area` VALUES ('2402', '钦北区', '353', '22,353,2402', '0', '1');
INSERT INTO `zz_area` VALUES ('2403', '灵山县', '353', '22,353,2403', '0', '1');
INSERT INTO `zz_area` VALUES ('2404', '浦北县', '353', '22,353,2404', '0', '1');
INSERT INTO `zz_area` VALUES ('2405', '港北区', '354', '22,354,2405', '0', '1');
INSERT INTO `zz_area` VALUES ('2406', '港南区', '354', '22,354,2406', '0', '1');
INSERT INTO `zz_area` VALUES ('2407', '覃塘区', '354', '22,354,2407', '0', '1');
INSERT INTO `zz_area` VALUES ('2408', '平南县', '354', '22,354,2408', '0', '1');
INSERT INTO `zz_area` VALUES ('2409', '桂平市', '354', '22,354,2409', '0', '1');
INSERT INTO `zz_area` VALUES ('2410', '玉州区', '355', '22,355,2410', '0', '1');
INSERT INTO `zz_area` VALUES ('2411', '容县', '355', '22,355,2411', '0', '1');
INSERT INTO `zz_area` VALUES ('2412', '陆川县', '355', '22,355,2412', '0', '1');
INSERT INTO `zz_area` VALUES ('2413', '博白县', '355', '22,355,2413', '0', '1');
INSERT INTO `zz_area` VALUES ('2414', '兴业县', '355', '22,355,2414', '0', '1');
INSERT INTO `zz_area` VALUES ('2415', '北流市', '355', '22,355,2415', '0', '1');
INSERT INTO `zz_area` VALUES ('2416', '右江区', '356', '22,356,2416', '0', '1');
INSERT INTO `zz_area` VALUES ('2417', '田阳县', '356', '22,356,2417', '0', '1');
INSERT INTO `zz_area` VALUES ('2418', '田东县', '356', '22,356,2418', '0', '1');
INSERT INTO `zz_area` VALUES ('2419', '平果县', '356', '22,356,2419', '0', '1');
INSERT INTO `zz_area` VALUES ('2420', '德保县', '356', '22,356,2420', '0', '1');
INSERT INTO `zz_area` VALUES ('2421', '靖西县', '356', '22,356,2421', '0', '1');
INSERT INTO `zz_area` VALUES ('2422', '那坡县', '356', '22,356,2422', '0', '1');
INSERT INTO `zz_area` VALUES ('2423', '凌云县', '356', '22,356,2423', '0', '1');
INSERT INTO `zz_area` VALUES ('2424', '乐业县', '356', '22,356,2424', '0', '1');
INSERT INTO `zz_area` VALUES ('2425', '田林县', '356', '22,356,2425', '0', '1');
INSERT INTO `zz_area` VALUES ('2426', '西林县', '356', '22,356,2426', '0', '1');
INSERT INTO `zz_area` VALUES ('2427', '隆林各族自治县', '356', '22,356,2427', '0', '1');
INSERT INTO `zz_area` VALUES ('2428', '八步区', '357', '22,357,2428', '0', '1');
INSERT INTO `zz_area` VALUES ('2429', '昭平县', '357', '22,357,2429', '0', '1');
INSERT INTO `zz_area` VALUES ('2430', '钟山县', '357', '22,357,2430', '0', '1');
INSERT INTO `zz_area` VALUES ('2431', '富川瑶族自治县', '357', '22,357,2431', '0', '1');
INSERT INTO `zz_area` VALUES ('2432', '金城江区', '358', '22,358,2432', '0', '1');
INSERT INTO `zz_area` VALUES ('2433', '南丹县', '358', '22,358,2433', '0', '1');
INSERT INTO `zz_area` VALUES ('2434', '天峨县', '358', '22,358,2434', '0', '1');
INSERT INTO `zz_area` VALUES ('2435', '凤山县', '358', '22,358,2435', '0', '1');
INSERT INTO `zz_area` VALUES ('2436', '东兰县', '358', '22,358,2436', '0', '1');
INSERT INTO `zz_area` VALUES ('2437', '罗城仫佬族自治县', '358', '22,358,2437', '0', '1');
INSERT INTO `zz_area` VALUES ('2438', '环江毛南族自治县', '358', '22,358,2438', '0', '1');
INSERT INTO `zz_area` VALUES ('2439', '巴马瑶族自治县', '358', '22,358,2439', '0', '1');
INSERT INTO `zz_area` VALUES ('2440', '都安瑶族自治县', '358', '22,358,2440', '0', '1');
INSERT INTO `zz_area` VALUES ('2441', '大化瑶族自治县', '358', '22,358,2441', '0', '1');
INSERT INTO `zz_area` VALUES ('2442', '宜州市', '358', '22,358,2442', '0', '1');
INSERT INTO `zz_area` VALUES ('2443', '兴宾区', '359', '22,359,2443', '0', '1');
INSERT INTO `zz_area` VALUES ('2444', '忻城县', '359', '22,359,2444', '0', '1');
INSERT INTO `zz_area` VALUES ('2445', '象州县', '359', '22,359,2445', '0', '1');
INSERT INTO `zz_area` VALUES ('2446', '武宣县', '359', '22,359,2446', '0', '1');
INSERT INTO `zz_area` VALUES ('2447', '金秀瑶族自治县', '359', '22,359,2447', '0', '1');
INSERT INTO `zz_area` VALUES ('2448', '合山市', '359', '22,359,2448', '0', '1');
INSERT INTO `zz_area` VALUES ('2449', '江洲区', '360', '22,360,2449', '0', '1');
INSERT INTO `zz_area` VALUES ('2450', '扶绥县', '360', '22,360,2450', '0', '1');
INSERT INTO `zz_area` VALUES ('2451', '宁明县', '360', '22,360,2451', '0', '1');
INSERT INTO `zz_area` VALUES ('2452', '龙州县', '360', '22,360,2452', '0', '1');
INSERT INTO `zz_area` VALUES ('2453', '大新县', '360', '22,360,2453', '0', '1');
INSERT INTO `zz_area` VALUES ('2454', '天等县', '360', '22,360,2454', '0', '1');
INSERT INTO `zz_area` VALUES ('2455', '凭祥市', '360', '22,360,2455', '0', '1');
INSERT INTO `zz_area` VALUES ('2456', '秀英区', '361', '23,361,2456', '0', '1');
INSERT INTO `zz_area` VALUES ('2457', '龙华区', '361', '23,361,2457', '0', '1');
INSERT INTO `zz_area` VALUES ('2458', '琼山区', '361', '23,361,2458', '0', '1');
INSERT INTO `zz_area` VALUES ('2459', '美兰区', '361', '23,361,2459', '0', '1');
INSERT INTO `zz_area` VALUES ('2460', '锦江区', '382', '24,382,2460', '0', '1');
INSERT INTO `zz_area` VALUES ('2461', '青羊区', '382', '24,382,2461', '0', '1');
INSERT INTO `zz_area` VALUES ('2462', '金牛区', '382', '24,382,2462', '0', '1');
INSERT INTO `zz_area` VALUES ('2463', '武侯区', '382', '24,382,2463', '0', '1');
INSERT INTO `zz_area` VALUES ('2464', '成华区', '382', '24,382,2464', '0', '1');
INSERT INTO `zz_area` VALUES ('2465', '龙泉驿区', '382', '24,382,2465', '0', '1');
INSERT INTO `zz_area` VALUES ('2466', '青白江区', '382', '24,382,2466', '0', '1');
INSERT INTO `zz_area` VALUES ('2467', '新都区', '382', '24,382,2467', '0', '1');
INSERT INTO `zz_area` VALUES ('2468', '温江区', '382', '24,382,2468', '0', '1');
INSERT INTO `zz_area` VALUES ('2469', '金堂县', '382', '24,382,2469', '0', '1');
INSERT INTO `zz_area` VALUES ('2470', '双流县', '382', '24,382,2470', '0', '1');
INSERT INTO `zz_area` VALUES ('2471', '郫县', '382', '24,382,2471', '0', '1');
INSERT INTO `zz_area` VALUES ('2472', '大邑县', '382', '24,382,2472', '0', '1');
INSERT INTO `zz_area` VALUES ('2473', '蒲江县', '382', '24,382,2473', '0', '1');
INSERT INTO `zz_area` VALUES ('2474', '新津县', '382', '24,382,2474', '0', '1');
INSERT INTO `zz_area` VALUES ('2475', '都江堰市', '382', '24,382,2475', '0', '1');
INSERT INTO `zz_area` VALUES ('2476', '彭州市', '382', '24,382,2476', '0', '1');
INSERT INTO `zz_area` VALUES ('2477', '邛崃市', '382', '24,382,2477', '0', '1');
INSERT INTO `zz_area` VALUES ('2478', '崇州市', '382', '24,382,2478', '0', '1');
INSERT INTO `zz_area` VALUES ('2479', '自流井区', '383', '24,383,2479', '0', '1');
INSERT INTO `zz_area` VALUES ('2480', '贡井区', '383', '24,383,2480', '0', '1');
INSERT INTO `zz_area` VALUES ('2481', '大安区', '383', '24,383,2481', '0', '1');
INSERT INTO `zz_area` VALUES ('2482', '沿滩区', '383', '24,383,2482', '0', '1');
INSERT INTO `zz_area` VALUES ('2483', '荣县', '383', '24,383,2483', '0', '1');
INSERT INTO `zz_area` VALUES ('2484', '富顺县', '383', '24,383,2484', '0', '1');
INSERT INTO `zz_area` VALUES ('2485', '东区', '384', '24,384,2485', '0', '1');
INSERT INTO `zz_area` VALUES ('2486', '西区', '384', '24,384,2486', '0', '1');
INSERT INTO `zz_area` VALUES ('2487', '仁和区', '384', '24,384,2487', '0', '1');
INSERT INTO `zz_area` VALUES ('2488', '米易县', '384', '24,384,2488', '0', '1');
INSERT INTO `zz_area` VALUES ('2489', '盐边县', '384', '24,384,2489', '0', '1');
INSERT INTO `zz_area` VALUES ('2490', '江阳区', '385', '24,385,2490', '0', '1');
INSERT INTO `zz_area` VALUES ('2491', '纳溪区', '385', '24,385,2491', '0', '1');
INSERT INTO `zz_area` VALUES ('2492', '龙马潭区', '385', '24,385,2492', '0', '1');
INSERT INTO `zz_area` VALUES ('2493', '泸县', '385', '24,385,2493', '0', '1');
INSERT INTO `zz_area` VALUES ('2494', '合江县', '385', '24,385,2494', '0', '1');
INSERT INTO `zz_area` VALUES ('2495', '叙永县', '385', '24,385,2495', '0', '1');
INSERT INTO `zz_area` VALUES ('2496', '古蔺县', '385', '24,385,2496', '0', '1');
INSERT INTO `zz_area` VALUES ('2497', '旌阳区', '386', '24,386,2497', '0', '1');
INSERT INTO `zz_area` VALUES ('2498', '中江县', '386', '24,386,2498', '0', '1');
INSERT INTO `zz_area` VALUES ('2499', '罗江县', '386', '24,386,2499', '0', '1');
INSERT INTO `zz_area` VALUES ('2500', '广汉市', '386', '24,386,2500', '0', '1');
INSERT INTO `zz_area` VALUES ('2501', '什邡市', '386', '24,386,2501', '0', '1');
INSERT INTO `zz_area` VALUES ('2502', '绵竹市', '386', '24,386,2502', '0', '1');
INSERT INTO `zz_area` VALUES ('2503', '涪城区', '387', '24,387,2503', '0', '1');
INSERT INTO `zz_area` VALUES ('2504', '游仙区', '387', '24,387,2504', '0', '1');
INSERT INTO `zz_area` VALUES ('2505', '三台县', '387', '24,387,2505', '0', '1');
INSERT INTO `zz_area` VALUES ('2506', '盐亭县', '387', '24,387,2506', '0', '1');
INSERT INTO `zz_area` VALUES ('2507', '安县', '387', '24,387,2507', '0', '1');
INSERT INTO `zz_area` VALUES ('2508', '梓潼县', '387', '24,387,2508', '0', '1');
INSERT INTO `zz_area` VALUES ('2509', '北川羌族自治县', '387', '24,387,2509', '0', '1');
INSERT INTO `zz_area` VALUES ('2510', '平武县', '387', '24,387,2510', '0', '1');
INSERT INTO `zz_area` VALUES ('2511', '江油市', '387', '24,387,2511', '0', '1');
INSERT INTO `zz_area` VALUES ('2512', '市中区', '388', '24,388,2512', '0', '1');
INSERT INTO `zz_area` VALUES ('2513', '元坝区', '388', '24,388,2513', '0', '1');
INSERT INTO `zz_area` VALUES ('2514', '朝天区', '388', '24,388,2514', '0', '1');
INSERT INTO `zz_area` VALUES ('2515', '旺苍县', '388', '24,388,2515', '0', '1');
INSERT INTO `zz_area` VALUES ('2516', '青川县', '388', '24,388,2516', '0', '1');
INSERT INTO `zz_area` VALUES ('2517', '剑阁县', '388', '24,388,2517', '0', '1');
INSERT INTO `zz_area` VALUES ('2518', '苍溪县', '388', '24,388,2518', '0', '1');
INSERT INTO `zz_area` VALUES ('2519', '船山区', '389', '24,389,2519', '0', '1');
INSERT INTO `zz_area` VALUES ('2520', '安居区', '389', '24,389,2520', '0', '1');
INSERT INTO `zz_area` VALUES ('2521', '蓬溪县', '389', '24,389,2521', '0', '1');
INSERT INTO `zz_area` VALUES ('2522', '射洪县', '389', '24,389,2522', '0', '1');
INSERT INTO `zz_area` VALUES ('2523', '大英县', '389', '24,389,2523', '0', '1');
INSERT INTO `zz_area` VALUES ('2524', '市中区', '390', '24,390,2524', '0', '1');
INSERT INTO `zz_area` VALUES ('2525', '东兴区', '390', '24,390,2525', '0', '1');
INSERT INTO `zz_area` VALUES ('2526', '威远县', '390', '24,390,2526', '0', '1');
INSERT INTO `zz_area` VALUES ('2527', '资中县', '390', '24,390,2527', '0', '1');
INSERT INTO `zz_area` VALUES ('2528', '隆昌县', '390', '24,390,2528', '0', '1');
INSERT INTO `zz_area` VALUES ('2529', '市中区', '391', '24,391,2529', '0', '1');
INSERT INTO `zz_area` VALUES ('2530', '沙湾区', '391', '24,391,2530', '0', '1');
INSERT INTO `zz_area` VALUES ('2531', '五通桥区', '391', '24,391,2531', '0', '1');
INSERT INTO `zz_area` VALUES ('2532', '金口河区', '391', '24,391,2532', '0', '1');
INSERT INTO `zz_area` VALUES ('2533', '犍为县', '391', '24,391,2533', '0', '1');
INSERT INTO `zz_area` VALUES ('2534', '井研县', '391', '24,391,2534', '0', '1');
INSERT INTO `zz_area` VALUES ('2535', '夹江县', '391', '24,391,2535', '0', '1');
INSERT INTO `zz_area` VALUES ('2536', '沐川县', '391', '24,391,2536', '0', '1');
INSERT INTO `zz_area` VALUES ('2537', '峨边彝族自治县', '391', '24,391,2537', '0', '1');
INSERT INTO `zz_area` VALUES ('2538', '马边彝族自治县', '391', '24,391,2538', '0', '1');
INSERT INTO `zz_area` VALUES ('2539', '峨眉山市', '391', '24,391,2539', '0', '1');
INSERT INTO `zz_area` VALUES ('2540', '顺庆区', '392', '24,392,2540', '0', '1');
INSERT INTO `zz_area` VALUES ('2541', '高坪区', '392', '24,392,2541', '0', '1');
INSERT INTO `zz_area` VALUES ('2542', '嘉陵区', '392', '24,392,2542', '0', '1');
INSERT INTO `zz_area` VALUES ('2543', '南部县', '392', '24,392,2543', '0', '1');
INSERT INTO `zz_area` VALUES ('2544', '营山县', '392', '24,392,2544', '0', '1');
INSERT INTO `zz_area` VALUES ('2545', '蓬安县', '392', '24,392,2545', '0', '1');
INSERT INTO `zz_area` VALUES ('2546', '仪陇县', '392', '24,392,2546', '0', '1');
INSERT INTO `zz_area` VALUES ('2547', '西充县', '392', '24,392,2547', '0', '1');
INSERT INTO `zz_area` VALUES ('2548', '阆中市', '392', '24,392,2548', '0', '1');
INSERT INTO `zz_area` VALUES ('2549', '东坡区', '393', '24,393,2549', '0', '1');
INSERT INTO `zz_area` VALUES ('2550', '仁寿县', '393', '24,393,2550', '0', '1');
INSERT INTO `zz_area` VALUES ('2551', '彭山县', '393', '24,393,2551', '0', '1');
INSERT INTO `zz_area` VALUES ('2552', '洪雅县', '393', '24,393,2552', '0', '1');
INSERT INTO `zz_area` VALUES ('2553', '丹棱县', '393', '24,393,2553', '0', '1');
INSERT INTO `zz_area` VALUES ('2554', '青神县', '393', '24,393,2554', '0', '1');
INSERT INTO `zz_area` VALUES ('2555', '翠屏区', '394', '24,394,2555', '0', '1');
INSERT INTO `zz_area` VALUES ('2556', '宜宾县', '394', '24,394,2556', '0', '1');
INSERT INTO `zz_area` VALUES ('2557', '南溪县', '394', '24,394,2557', '0', '1');
INSERT INTO `zz_area` VALUES ('2558', '江安县', '394', '24,394,2558', '0', '1');
INSERT INTO `zz_area` VALUES ('2559', '长宁县', '394', '24,394,2559', '0', '1');
INSERT INTO `zz_area` VALUES ('2560', '高县', '394', '24,394,2560', '0', '1');
INSERT INTO `zz_area` VALUES ('2561', '珙县', '394', '24,394,2561', '0', '1');
INSERT INTO `zz_area` VALUES ('2562', '筠连县', '394', '24,394,2562', '0', '1');
INSERT INTO `zz_area` VALUES ('2563', '兴文县', '394', '24,394,2563', '0', '1');
INSERT INTO `zz_area` VALUES ('2564', '屏山县', '394', '24,394,2564', '0', '1');
INSERT INTO `zz_area` VALUES ('2565', '广安区', '395', '24,395,2565', '0', '1');
INSERT INTO `zz_area` VALUES ('2566', '岳池县', '395', '24,395,2566', '0', '1');
INSERT INTO `zz_area` VALUES ('2567', '武胜县', '395', '24,395,2567', '0', '1');
INSERT INTO `zz_area` VALUES ('2568', '邻水县', '395', '24,395,2568', '0', '1');
INSERT INTO `zz_area` VALUES ('2569', '华蓥市', '395', '24,395,2569', '0', '1');
INSERT INTO `zz_area` VALUES ('2570', '通川区', '396', '24,396,2570', '0', '1');
INSERT INTO `zz_area` VALUES ('2571', '达县', '396', '24,396,2571', '0', '1');
INSERT INTO `zz_area` VALUES ('2572', '宣汉县', '396', '24,396,2572', '0', '1');
INSERT INTO `zz_area` VALUES ('2573', '开江县', '396', '24,396,2573', '0', '1');
INSERT INTO `zz_area` VALUES ('2574', '大竹县', '396', '24,396,2574', '0', '1');
INSERT INTO `zz_area` VALUES ('2575', '渠县', '396', '24,396,2575', '0', '1');
INSERT INTO `zz_area` VALUES ('2576', '万源市', '396', '24,396,2576', '0', '1');
INSERT INTO `zz_area` VALUES ('2577', '雨城区', '397', '24,397,2577', '0', '1');
INSERT INTO `zz_area` VALUES ('2578', '名山县', '397', '24,397,2578', '0', '1');
INSERT INTO `zz_area` VALUES ('2579', '荥经县', '397', '24,397,2579', '0', '1');
INSERT INTO `zz_area` VALUES ('2580', '汉源县', '397', '24,397,2580', '0', '1');
INSERT INTO `zz_area` VALUES ('2581', '石棉县', '397', '24,397,2581', '0', '1');
INSERT INTO `zz_area` VALUES ('2582', '天全县', '397', '24,397,2582', '0', '1');
INSERT INTO `zz_area` VALUES ('2583', '芦山县', '397', '24,397,2583', '0', '1');
INSERT INTO `zz_area` VALUES ('2584', '宝兴县', '397', '24,397,2584', '0', '1');
INSERT INTO `zz_area` VALUES ('2585', '巴州区', '398', '24,398,2585', '0', '1');
INSERT INTO `zz_area` VALUES ('2586', '通江县', '398', '24,398,2586', '0', '1');
INSERT INTO `zz_area` VALUES ('2587', '南江县', '398', '24,398,2587', '0', '1');
INSERT INTO `zz_area` VALUES ('2588', '平昌县', '398', '24,398,2588', '0', '1');
INSERT INTO `zz_area` VALUES ('2589', '雁江区', '399', '24,399,2589', '0', '1');
INSERT INTO `zz_area` VALUES ('2590', '安岳县', '399', '24,399,2590', '0', '1');
INSERT INTO `zz_area` VALUES ('2591', '乐至县', '399', '24,399,2591', '0', '1');
INSERT INTO `zz_area` VALUES ('2592', '简阳市', '399', '24,399,2592', '0', '1');
INSERT INTO `zz_area` VALUES ('2593', '汶川县', '400', '24,400,2593', '0', '1');
INSERT INTO `zz_area` VALUES ('2594', '理县', '400', '24,400,2594', '0', '1');
INSERT INTO `zz_area` VALUES ('2595', '茂县', '400', '24,400,2595', '0', '1');
INSERT INTO `zz_area` VALUES ('2596', '松潘县', '400', '24,400,2596', '0', '1');
INSERT INTO `zz_area` VALUES ('2597', '九寨沟县', '400', '24,400,2597', '0', '1');
INSERT INTO `zz_area` VALUES ('2598', '金川县', '400', '24,400,2598', '0', '1');
INSERT INTO `zz_area` VALUES ('2599', '小金县', '400', '24,400,2599', '0', '1');
INSERT INTO `zz_area` VALUES ('2600', '黑水县', '400', '24,400,2600', '0', '1');
INSERT INTO `zz_area` VALUES ('2601', '马尔康县', '400', '24,400,2601', '0', '1');
INSERT INTO `zz_area` VALUES ('2602', '壤塘县', '400', '24,400,2602', '0', '1');
INSERT INTO `zz_area` VALUES ('2603', '阿坝县', '400', '24,400,2603', '0', '1');
INSERT INTO `zz_area` VALUES ('2604', '若尔盖县', '400', '24,400,2604', '0', '1');
INSERT INTO `zz_area` VALUES ('2605', '红原县', '400', '24,400,2605', '0', '1');
INSERT INTO `zz_area` VALUES ('2606', '康定县', '401', '24,401,2606', '0', '1');
INSERT INTO `zz_area` VALUES ('2607', '泸定县', '401', '24,401,2607', '0', '1');
INSERT INTO `zz_area` VALUES ('2608', '丹巴县', '401', '24,401,2608', '0', '1');
INSERT INTO `zz_area` VALUES ('2609', '九龙县', '401', '24,401,2609', '0', '1');
INSERT INTO `zz_area` VALUES ('2610', '雅江县', '401', '24,401,2610', '0', '1');
INSERT INTO `zz_area` VALUES ('2611', '道孚县', '401', '24,401,2611', '0', '1');
INSERT INTO `zz_area` VALUES ('2612', '炉霍县', '401', '24,401,2612', '0', '1');
INSERT INTO `zz_area` VALUES ('2613', '甘孜县', '401', '24,401,2613', '0', '1');
INSERT INTO `zz_area` VALUES ('2614', '新龙县', '401', '24,401,2614', '0', '1');
INSERT INTO `zz_area` VALUES ('2615', '德格县', '401', '24,401,2615', '0', '1');
INSERT INTO `zz_area` VALUES ('2616', '白玉县', '401', '24,401,2616', '0', '1');
INSERT INTO `zz_area` VALUES ('2617', '石渠县', '401', '24,401,2617', '0', '1');
INSERT INTO `zz_area` VALUES ('2618', '色达县', '401', '24,401,2618', '0', '1');
INSERT INTO `zz_area` VALUES ('2619', '理塘县', '401', '24,401,2619', '0', '1');
INSERT INTO `zz_area` VALUES ('2620', '巴塘县', '401', '24,401,2620', '0', '1');
INSERT INTO `zz_area` VALUES ('2621', '乡城县', '401', '24,401,2621', '0', '1');
INSERT INTO `zz_area` VALUES ('2622', '稻城县', '401', '24,401,2622', '0', '1');
INSERT INTO `zz_area` VALUES ('2623', '得荣县', '401', '24,401,2623', '0', '1');
INSERT INTO `zz_area` VALUES ('2624', '西昌市', '402', '24,402,2624', '0', '1');
INSERT INTO `zz_area` VALUES ('2625', '木里藏族自治县', '402', '24,402,2625', '0', '1');
INSERT INTO `zz_area` VALUES ('2626', '盐源县', '402', '24,402,2626', '0', '1');
INSERT INTO `zz_area` VALUES ('2627', '德昌县', '402', '24,402,2627', '0', '1');
INSERT INTO `zz_area` VALUES ('2628', '会理县', '402', '24,402,2628', '0', '1');
INSERT INTO `zz_area` VALUES ('2629', '会东县', '402', '24,402,2629', '0', '1');
INSERT INTO `zz_area` VALUES ('2630', '宁南县', '402', '24,402,2630', '0', '1');
INSERT INTO `zz_area` VALUES ('2631', '普格县', '402', '24,402,2631', '0', '1');
INSERT INTO `zz_area` VALUES ('2632', '布拖县', '402', '24,402,2632', '0', '1');
INSERT INTO `zz_area` VALUES ('2633', '金阳县', '402', '24,402,2633', '0', '1');
INSERT INTO `zz_area` VALUES ('2634', '昭觉县', '402', '24,402,2634', '0', '1');
INSERT INTO `zz_area` VALUES ('2635', '喜德县', '402', '24,402,2635', '0', '1');
INSERT INTO `zz_area` VALUES ('2636', '冕宁县', '402', '24,402,2636', '0', '1');
INSERT INTO `zz_area` VALUES ('2637', '越西县', '402', '24,402,2637', '0', '1');
INSERT INTO `zz_area` VALUES ('2638', '甘洛县', '402', '24,402,2638', '0', '1');
INSERT INTO `zz_area` VALUES ('2639', '美姑县', '402', '24,402,2639', '0', '1');
INSERT INTO `zz_area` VALUES ('2640', '雷波县', '402', '24,402,2640', '0', '1');
INSERT INTO `zz_area` VALUES ('2641', '南明区', '403', '25,403,2641', '0', '1');
INSERT INTO `zz_area` VALUES ('2642', '云岩区', '403', '25,403,2642', '0', '1');
INSERT INTO `zz_area` VALUES ('2643', '花溪区', '403', '25,403,2643', '0', '1');
INSERT INTO `zz_area` VALUES ('2644', '乌当区', '403', '25,403,2644', '0', '1');
INSERT INTO `zz_area` VALUES ('2645', '白云区', '403', '25,403,2645', '0', '1');
INSERT INTO `zz_area` VALUES ('2646', '小河区', '403', '25,403,2646', '0', '1');
INSERT INTO `zz_area` VALUES ('2647', '开阳县', '403', '25,403,2647', '0', '1');
INSERT INTO `zz_area` VALUES ('2648', '息烽县', '403', '25,403,2648', '0', '1');
INSERT INTO `zz_area` VALUES ('2649', '修文县', '403', '25,403,2649', '0', '1');
INSERT INTO `zz_area` VALUES ('2650', '清镇市', '403', '25,403,2650', '0', '1');
INSERT INTO `zz_area` VALUES ('2651', '钟山区', '404', '25,404,2651', '0', '1');
INSERT INTO `zz_area` VALUES ('2652', '六枝特区', '404', '25,404,2652', '0', '1');
INSERT INTO `zz_area` VALUES ('2653', '水城县', '404', '25,404,2653', '0', '1');
INSERT INTO `zz_area` VALUES ('2654', '盘县', '404', '25,404,2654', '0', '1');
INSERT INTO `zz_area` VALUES ('2655', '红花岗区', '405', '25,405,2655', '0', '1');
INSERT INTO `zz_area` VALUES ('2656', '汇川区', '405', '25,405,2656', '0', '1');
INSERT INTO `zz_area` VALUES ('2657', '遵义县', '405', '25,405,2657', '0', '1');
INSERT INTO `zz_area` VALUES ('2658', '桐梓县', '405', '25,405,2658', '0', '1');
INSERT INTO `zz_area` VALUES ('2659', '绥阳县', '405', '25,405,2659', '0', '1');
INSERT INTO `zz_area` VALUES ('2660', '正安县', '405', '25,405,2660', '0', '1');
INSERT INTO `zz_area` VALUES ('2661', '道真仡佬族苗族自治县', '405', '25,405,2661', '0', '1');
INSERT INTO `zz_area` VALUES ('2662', '务川仡佬族苗族自治县', '405', '25,405,2662', '0', '1');
INSERT INTO `zz_area` VALUES ('2663', '凤冈县', '405', '25,405,2663', '0', '1');
INSERT INTO `zz_area` VALUES ('2664', '湄潭县', '405', '25,405,2664', '0', '1');
INSERT INTO `zz_area` VALUES ('2665', '余庆县', '405', '25,405,2665', '0', '1');
INSERT INTO `zz_area` VALUES ('2666', '习水县', '405', '25,405,2666', '0', '1');
INSERT INTO `zz_area` VALUES ('2667', '赤水市', '405', '25,405,2667', '0', '1');
INSERT INTO `zz_area` VALUES ('2668', '仁怀市', '405', '25,405,2668', '0', '1');
INSERT INTO `zz_area` VALUES ('2669', '西秀区', '406', '25,406,2669', '0', '1');
INSERT INTO `zz_area` VALUES ('2670', '平坝县', '406', '25,406,2670', '0', '1');
INSERT INTO `zz_area` VALUES ('2671', '普定县', '406', '25,406,2671', '0', '1');
INSERT INTO `zz_area` VALUES ('2672', '镇宁布依族苗族自治县', '406', '25,406,2672', '0', '1');
INSERT INTO `zz_area` VALUES ('2673', '关岭布依族苗族自治县', '406', '25,406,2673', '0', '1');
INSERT INTO `zz_area` VALUES ('2674', '紫云苗族布依族自治县', '406', '25,406,2674', '0', '1');
INSERT INTO `zz_area` VALUES ('2675', '铜仁市', '407', '25,407,2675', '0', '1');
INSERT INTO `zz_area` VALUES ('2676', '江口县', '407', '25,407,2676', '0', '1');
INSERT INTO `zz_area` VALUES ('2677', '玉屏侗族自治县', '407', '25,407,2677', '0', '1');
INSERT INTO `zz_area` VALUES ('2678', '石阡县', '407', '25,407,2678', '0', '1');
INSERT INTO `zz_area` VALUES ('2679', '思南县', '407', '25,407,2679', '0', '1');
INSERT INTO `zz_area` VALUES ('2680', '印江土家族苗族自治县', '407', '25,407,2680', '0', '1');
INSERT INTO `zz_area` VALUES ('2681', '德江县', '407', '25,407,2681', '0', '1');
INSERT INTO `zz_area` VALUES ('2682', '沿河土家族自治县', '407', '25,407,2682', '0', '1');
INSERT INTO `zz_area` VALUES ('2683', '松桃苗族自治县', '407', '25,407,2683', '0', '1');
INSERT INTO `zz_area` VALUES ('2684', '万山特区', '407', '25,407,2684', '0', '1');
INSERT INTO `zz_area` VALUES ('2685', '兴义市', '408', '25,408,2685', '0', '1');
INSERT INTO `zz_area` VALUES ('2686', '兴仁县', '408', '25,408,2686', '0', '1');
INSERT INTO `zz_area` VALUES ('2687', '普安县', '408', '25,408,2687', '0', '1');
INSERT INTO `zz_area` VALUES ('2688', '晴隆县', '408', '25,408,2688', '0', '1');
INSERT INTO `zz_area` VALUES ('2689', '贞丰县', '408', '25,408,2689', '0', '1');
INSERT INTO `zz_area` VALUES ('2690', '望谟县', '408', '25,408,2690', '0', '1');
INSERT INTO `zz_area` VALUES ('2691', '册亨县', '408', '25,408,2691', '0', '1');
INSERT INTO `zz_area` VALUES ('2692', '安龙县', '408', '25,408,2692', '0', '1');
INSERT INTO `zz_area` VALUES ('2693', '毕节市', '409', '25,409,2693', '0', '1');
INSERT INTO `zz_area` VALUES ('2694', '大方县', '409', '25,409,2694', '0', '1');
INSERT INTO `zz_area` VALUES ('2695', '黔西县', '409', '25,409,2695', '0', '1');
INSERT INTO `zz_area` VALUES ('2696', '金沙县', '409', '25,409,2696', '0', '1');
INSERT INTO `zz_area` VALUES ('2697', '织金县', '409', '25,409,2697', '0', '1');
INSERT INTO `zz_area` VALUES ('2698', '纳雍县', '409', '25,409,2698', '0', '1');
INSERT INTO `zz_area` VALUES ('2699', '威宁彝族回族苗族自治县', '409', '25,409,2699', '0', '1');
INSERT INTO `zz_area` VALUES ('2700', '赫章县', '409', '25,409,2700', '0', '1');
INSERT INTO `zz_area` VALUES ('2701', '凯里市', '410', '25,410,2701', '0', '1');
INSERT INTO `zz_area` VALUES ('2702', '黄平县', '410', '25,410,2702', '0', '1');
INSERT INTO `zz_area` VALUES ('2703', '施秉县', '410', '25,410,2703', '0', '1');
INSERT INTO `zz_area` VALUES ('2704', '三穗县', '410', '25,410,2704', '0', '1');
INSERT INTO `zz_area` VALUES ('2705', '镇远县', '410', '25,410,2705', '0', '1');
INSERT INTO `zz_area` VALUES ('2706', '岑巩县', '410', '25,410,2706', '0', '1');
INSERT INTO `zz_area` VALUES ('2707', '天柱县', '410', '25,410,2707', '0', '1');
INSERT INTO `zz_area` VALUES ('2708', '锦屏县', '410', '25,410,2708', '0', '1');
INSERT INTO `zz_area` VALUES ('2709', '剑河县', '410', '25,410,2709', '0', '1');
INSERT INTO `zz_area` VALUES ('2710', '台江县', '410', '25,410,2710', '0', '1');
INSERT INTO `zz_area` VALUES ('2711', '黎平县', '410', '25,410,2711', '0', '1');
INSERT INTO `zz_area` VALUES ('2712', '榕江县', '410', '25,410,2712', '0', '1');
INSERT INTO `zz_area` VALUES ('2713', '从江县', '410', '25,410,2713', '0', '1');
INSERT INTO `zz_area` VALUES ('2714', '雷山县', '410', '25,410,2714', '0', '1');
INSERT INTO `zz_area` VALUES ('2715', '麻江县', '410', '25,410,2715', '0', '1');
INSERT INTO `zz_area` VALUES ('2716', '丹寨县', '410', '25,410,2716', '0', '1');
INSERT INTO `zz_area` VALUES ('2717', '都匀市', '411', '25,411,2717', '0', '1');
INSERT INTO `zz_area` VALUES ('2718', '福泉市', '411', '25,411,2718', '0', '1');
INSERT INTO `zz_area` VALUES ('2719', '荔波县', '411', '25,411,2719', '0', '1');
INSERT INTO `zz_area` VALUES ('2720', '贵定县', '411', '25,411,2720', '0', '1');
INSERT INTO `zz_area` VALUES ('2721', '瓮安县', '411', '25,411,2721', '0', '1');
INSERT INTO `zz_area` VALUES ('2722', '独山县', '411', '25,411,2722', '0', '1');
INSERT INTO `zz_area` VALUES ('2723', '平塘县', '411', '25,411,2723', '0', '1');
INSERT INTO `zz_area` VALUES ('2724', '罗甸县', '411', '25,411,2724', '0', '1');
INSERT INTO `zz_area` VALUES ('2725', '长顺县', '411', '25,411,2725', '0', '1');
INSERT INTO `zz_area` VALUES ('2726', '龙里县', '411', '25,411,2726', '0', '1');
INSERT INTO `zz_area` VALUES ('2727', '惠水县', '411', '25,411,2727', '0', '1');
INSERT INTO `zz_area` VALUES ('2728', '三都水族自治县', '411', '25,411,2728', '0', '1');
INSERT INTO `zz_area` VALUES ('2729', '五华区', '412', '26,412,2729', '0', '1');
INSERT INTO `zz_area` VALUES ('2730', '盘龙区', '412', '26,412,2730', '0', '1');
INSERT INTO `zz_area` VALUES ('2731', '官渡区', '412', '26,412,2731', '0', '1');
INSERT INTO `zz_area` VALUES ('2732', '西山区', '412', '26,412,2732', '0', '1');
INSERT INTO `zz_area` VALUES ('2733', '东川区', '412', '26,412,2733', '0', '1');
INSERT INTO `zz_area` VALUES ('2734', '呈贡县', '412', '26,412,2734', '0', '1');
INSERT INTO `zz_area` VALUES ('2735', '晋宁县', '412', '26,412,2735', '0', '1');
INSERT INTO `zz_area` VALUES ('2736', '富民县', '412', '26,412,2736', '0', '1');
INSERT INTO `zz_area` VALUES ('2737', '宜良县', '412', '26,412,2737', '0', '1');
INSERT INTO `zz_area` VALUES ('2738', '石林彝族自治县', '412', '26,412,2738', '0', '1');
INSERT INTO `zz_area` VALUES ('2739', '嵩明县', '412', '26,412,2739', '0', '1');
INSERT INTO `zz_area` VALUES ('2740', '禄劝彝族苗族自治县', '412', '26,412,2740', '0', '1');
INSERT INTO `zz_area` VALUES ('2741', '寻甸回族彝族自治县', '412', '26,412,2741', '0', '1');
INSERT INTO `zz_area` VALUES ('2742', '安宁市', '412', '26,412,2742', '0', '1');
INSERT INTO `zz_area` VALUES ('2743', '麒麟区', '413', '26,413,2743', '0', '1');
INSERT INTO `zz_area` VALUES ('2744', '马龙县', '413', '26,413,2744', '0', '1');
INSERT INTO `zz_area` VALUES ('2745', '陆良县', '413', '26,413,2745', '0', '1');
INSERT INTO `zz_area` VALUES ('2746', '师宗县', '413', '26,413,2746', '0', '1');
INSERT INTO `zz_area` VALUES ('2747', '罗平县', '413', '26,413,2747', '0', '1');
INSERT INTO `zz_area` VALUES ('2748', '富源县', '413', '26,413,2748', '0', '1');
INSERT INTO `zz_area` VALUES ('2749', '会泽县', '413', '26,413,2749', '0', '1');
INSERT INTO `zz_area` VALUES ('2750', '沾益县', '413', '26,413,2750', '0', '1');
INSERT INTO `zz_area` VALUES ('2751', '宣威市', '413', '26,413,2751', '0', '1');
INSERT INTO `zz_area` VALUES ('2752', '红塔区', '414', '26,414,2752', '0', '1');
INSERT INTO `zz_area` VALUES ('2753', '江川县', '414', '26,414,2753', '0', '1');
INSERT INTO `zz_area` VALUES ('2754', '澄江县', '414', '26,414,2754', '0', '1');
INSERT INTO `zz_area` VALUES ('2755', '通海县', '414', '26,414,2755', '0', '1');
INSERT INTO `zz_area` VALUES ('2756', '华宁县', '414', '26,414,2756', '0', '1');
INSERT INTO `zz_area` VALUES ('2757', '易门县', '414', '26,414,2757', '0', '1');
INSERT INTO `zz_area` VALUES ('2758', '峨山彝族自治县', '414', '26,414,2758', '0', '1');
INSERT INTO `zz_area` VALUES ('2759', '新平彝族傣族自治县', '414', '26,414,2759', '0', '1');
INSERT INTO `zz_area` VALUES ('2760', '元江哈尼族彝族傣族自治县', '414', '26,414,2760', '0', '1');
INSERT INTO `zz_area` VALUES ('2761', '隆阳区', '415', '26,415,2761', '0', '1');
INSERT INTO `zz_area` VALUES ('2762', '施甸县', '415', '26,415,2762', '0', '1');
INSERT INTO `zz_area` VALUES ('2763', '腾冲县', '415', '26,415,2763', '0', '1');
INSERT INTO `zz_area` VALUES ('2764', '龙陵县', '415', '26,415,2764', '0', '1');
INSERT INTO `zz_area` VALUES ('2765', '昌宁县', '415', '26,415,2765', '0', '1');
INSERT INTO `zz_area` VALUES ('2766', '昭阳区', '416', '26,416,2766', '0', '1');
INSERT INTO `zz_area` VALUES ('2767', '鲁甸县', '416', '26,416,2767', '0', '1');
INSERT INTO `zz_area` VALUES ('2768', '巧家县', '416', '26,416,2768', '0', '1');
INSERT INTO `zz_area` VALUES ('2769', '盐津县', '416', '26,416,2769', '0', '1');
INSERT INTO `zz_area` VALUES ('2770', '大关县', '416', '26,416,2770', '0', '1');
INSERT INTO `zz_area` VALUES ('2771', '永善县', '416', '26,416,2771', '0', '1');
INSERT INTO `zz_area` VALUES ('2772', '绥江县', '416', '26,416,2772', '0', '1');
INSERT INTO `zz_area` VALUES ('2773', '镇雄县', '416', '26,416,2773', '0', '1');
INSERT INTO `zz_area` VALUES ('2774', '彝良县', '416', '26,416,2774', '0', '1');
INSERT INTO `zz_area` VALUES ('2775', '威信县', '416', '26,416,2775', '0', '1');
INSERT INTO `zz_area` VALUES ('2776', '水富县', '416', '26,416,2776', '0', '1');
INSERT INTO `zz_area` VALUES ('2777', '古城区', '417', '26,417,2777', '0', '1');
INSERT INTO `zz_area` VALUES ('2778', '玉龙纳西族自治县', '417', '26,417,2778', '0', '1');
INSERT INTO `zz_area` VALUES ('2779', '永胜县', '417', '26,417,2779', '0', '1');
INSERT INTO `zz_area` VALUES ('2780', '华坪县', '417', '26,417,2780', '0', '1');
INSERT INTO `zz_area` VALUES ('2781', '宁蒗彝族自治县', '417', '26,417,2781', '0', '1');
INSERT INTO `zz_area` VALUES ('2782', '翠云区', '418', '26,418,2782', '0', '1');
INSERT INTO `zz_area` VALUES ('2783', '普洱哈尼族彝族自治县', '418', '26,418,2783', '0', '1');
INSERT INTO `zz_area` VALUES ('2784', '墨江哈尼族自治县', '418', '26,418,2784', '0', '1');
INSERT INTO `zz_area` VALUES ('2785', '景东彝族自治县', '418', '26,418,2785', '0', '1');
INSERT INTO `zz_area` VALUES ('2786', '景谷傣族彝族自治县', '418', '26,418,2786', '0', '1');
INSERT INTO `zz_area` VALUES ('2787', '镇沅彝族哈尼族拉祜族自治县', '418', '26,418,2787', '0', '1');
INSERT INTO `zz_area` VALUES ('2788', '江城哈尼族彝族自治县', '418', '26,418,2788', '0', '1');
INSERT INTO `zz_area` VALUES ('2789', '孟连傣族拉祜族佤族自治县', '418', '26,418,2789', '0', '1');
INSERT INTO `zz_area` VALUES ('2790', '澜沧拉祜族自治县', '418', '26,418,2790', '0', '1');
INSERT INTO `zz_area` VALUES ('2791', '西盟佤族自治县', '418', '26,418,2791', '0', '1');
INSERT INTO `zz_area` VALUES ('2792', '临翔区', '419', '26,419,2792', '0', '1');
INSERT INTO `zz_area` VALUES ('2793', '凤庆县', '419', '26,419,2793', '0', '1');
INSERT INTO `zz_area` VALUES ('2794', '云县', '419', '26,419,2794', '0', '1');
INSERT INTO `zz_area` VALUES ('2795', '永德县', '419', '26,419,2795', '0', '1');
INSERT INTO `zz_area` VALUES ('2796', '镇康县', '419', '26,419,2796', '0', '1');
INSERT INTO `zz_area` VALUES ('2797', '双江拉祜族佤族布朗族傣族自治县', '419', '26,419,2797', '0', '1');
INSERT INTO `zz_area` VALUES ('2798', '耿马傣族佤族自治县', '419', '26,419,2798', '0', '1');
INSERT INTO `zz_area` VALUES ('2799', '沧源佤族自治县', '420', '26,420,2799', '0', '1');
INSERT INTO `zz_area` VALUES ('2800', '楚雄市', '420', '26,420,2800', '0', '1');
INSERT INTO `zz_area` VALUES ('2801', '双柏县', '420', '26,420,2801', '0', '1');
INSERT INTO `zz_area` VALUES ('2802', '牟定县', '420', '26,420,2802', '0', '1');
INSERT INTO `zz_area` VALUES ('2803', '南华县', '420', '26,420,2803', '0', '1');
INSERT INTO `zz_area` VALUES ('2804', '姚安县', '420', '26,420,2804', '0', '1');
INSERT INTO `zz_area` VALUES ('2805', '大姚县', '420', '26,420,2805', '0', '1');
INSERT INTO `zz_area` VALUES ('2806', '永仁县', '420', '26,420,2806', '0', '1');
INSERT INTO `zz_area` VALUES ('2807', '元谋县', '420', '26,420,2807', '0', '1');
INSERT INTO `zz_area` VALUES ('2808', '武定县', '420', '26,420,2808', '0', '1');
INSERT INTO `zz_area` VALUES ('2809', '禄丰县', '420', '26,420,2809', '0', '1');
INSERT INTO `zz_area` VALUES ('2810', '个旧市', '421', '26,421,2810', '0', '1');
INSERT INTO `zz_area` VALUES ('2811', '开远市', '421', '26,421,2811', '0', '1');
INSERT INTO `zz_area` VALUES ('2812', '蒙自县', '421', '26,421,2812', '0', '1');
INSERT INTO `zz_area` VALUES ('2813', '屏边苗族自治县', '421', '26,421,2813', '0', '1');
INSERT INTO `zz_area` VALUES ('2814', '建水县', '421', '26,421,2814', '0', '1');
INSERT INTO `zz_area` VALUES ('2815', '石屏县', '421', '26,421,2815', '0', '1');
INSERT INTO `zz_area` VALUES ('2816', '弥勒县', '421', '26,421,2816', '0', '1');
INSERT INTO `zz_area` VALUES ('2817', '泸西县', '421', '26,421,2817', '0', '1');
INSERT INTO `zz_area` VALUES ('2818', '元阳县', '421', '26,421,2818', '0', '1');
INSERT INTO `zz_area` VALUES ('2819', '红河县', '421', '26,421,2819', '0', '1');
INSERT INTO `zz_area` VALUES ('2820', '金平苗族瑶族傣族自治县', '421', '26,421,2820', '0', '1');
INSERT INTO `zz_area` VALUES ('2821', '绿春县', '421', '26,421,2821', '0', '1');
INSERT INTO `zz_area` VALUES ('2822', '河口瑶族自治县', '421', '26,421,2822', '0', '1');
INSERT INTO `zz_area` VALUES ('2823', '文山县', '422', '26,422,2823', '0', '1');
INSERT INTO `zz_area` VALUES ('2824', '砚山县', '422', '26,422,2824', '0', '1');
INSERT INTO `zz_area` VALUES ('2825', '西畴县', '422', '26,422,2825', '0', '1');
INSERT INTO `zz_area` VALUES ('2826', '麻栗坡县', '422', '26,422,2826', '0', '1');
INSERT INTO `zz_area` VALUES ('2827', '马关县', '422', '26,422,2827', '0', '1');
INSERT INTO `zz_area` VALUES ('2828', '丘北县', '422', '26,422,2828', '0', '1');
INSERT INTO `zz_area` VALUES ('2829', '广南县', '422', '26,422,2829', '0', '1');
INSERT INTO `zz_area` VALUES ('2830', '富宁县', '422', '26,422,2830', '0', '1');
INSERT INTO `zz_area` VALUES ('2831', '景洪市', '423', '26,423,2831', '0', '1');
INSERT INTO `zz_area` VALUES ('2832', '勐海县', '423', '26,423,2832', '0', '1');
INSERT INTO `zz_area` VALUES ('2833', '勐腊县', '423', '26,423,2833', '0', '1');
INSERT INTO `zz_area` VALUES ('2834', '大理市', '424', '26,424,2834', '0', '1');
INSERT INTO `zz_area` VALUES ('2835', '漾濞彝族自治县', '424', '26,424,2835', '0', '1');
INSERT INTO `zz_area` VALUES ('2836', '祥云县', '424', '26,424,2836', '0', '1');
INSERT INTO `zz_area` VALUES ('2837', '宾川县', '424', '26,424,2837', '0', '1');
INSERT INTO `zz_area` VALUES ('2838', '弥渡县', '424', '26,424,2838', '0', '1');
INSERT INTO `zz_area` VALUES ('2839', '南涧彝族自治县', '424', '26,424,2839', '0', '1');
INSERT INTO `zz_area` VALUES ('2840', '巍山彝族回族自治县', '424', '26,424,2840', '0', '1');
INSERT INTO `zz_area` VALUES ('2841', '永平县', '424', '26,424,2841', '0', '1');
INSERT INTO `zz_area` VALUES ('2842', '云龙县', '424', '26,424,2842', '0', '1');
INSERT INTO `zz_area` VALUES ('2843', '洱源县', '424', '26,424,2843', '0', '1');
INSERT INTO `zz_area` VALUES ('2844', '剑川县', '424', '26,424,2844', '0', '1');
INSERT INTO `zz_area` VALUES ('2845', '鹤庆县', '424', '26,424,2845', '0', '1');
INSERT INTO `zz_area` VALUES ('2846', '瑞丽市', '425', '26,425,2846', '0', '1');
INSERT INTO `zz_area` VALUES ('2847', '潞西市', '425', '26,425,2847', '0', '1');
INSERT INTO `zz_area` VALUES ('2848', '梁河县', '425', '26,425,2848', '0', '1');
INSERT INTO `zz_area` VALUES ('2849', '盈江县', '425', '26,425,2849', '0', '1');
INSERT INTO `zz_area` VALUES ('2850', '陇川县', '425', '26,425,2850', '0', '1');
INSERT INTO `zz_area` VALUES ('2851', '泸水县', '426', '26,426,2851', '0', '1');
INSERT INTO `zz_area` VALUES ('2852', '福贡县', '426', '26,426,2852', '0', '1');
INSERT INTO `zz_area` VALUES ('2853', '贡山独龙族怒族自治县', '426', '26,426,2853', '0', '1');
INSERT INTO `zz_area` VALUES ('2854', '兰坪白族普米族自治县', '426', '26,426,2854', '0', '1');
INSERT INTO `zz_area` VALUES ('2855', '香格里拉县', '427', '26,427,2855', '0', '1');
INSERT INTO `zz_area` VALUES ('2856', '德钦县', '427', '26,427,2856', '0', '1');
INSERT INTO `zz_area` VALUES ('2857', '维西傈僳族自治县', '427', '26,427,2857', '0', '1');
INSERT INTO `zz_area` VALUES ('2858', '城关区', '428', '27,428,2858', '0', '1');
INSERT INTO `zz_area` VALUES ('2859', '林周县', '428', '27,428,2859', '0', '1');
INSERT INTO `zz_area` VALUES ('2860', '当雄县', '428', '27,428,2860', '0', '1');
INSERT INTO `zz_area` VALUES ('2861', '尼木县', '428', '27,428,2861', '0', '1');
INSERT INTO `zz_area` VALUES ('2862', '曲水县', '428', '27,428,2862', '0', '1');
INSERT INTO `zz_area` VALUES ('2863', '堆龙德庆县', '428', '27,428,2863', '0', '1');
INSERT INTO `zz_area` VALUES ('2864', '达孜县', '428', '27,428,2864', '0', '1');
INSERT INTO `zz_area` VALUES ('2865', '墨竹工卡县', '428', '27,428,2865', '0', '1');
INSERT INTO `zz_area` VALUES ('2866', '昌都县', '429', '27,429,2866', '0', '1');
INSERT INTO `zz_area` VALUES ('2867', '江达县', '429', '27,429,2867', '0', '1');
INSERT INTO `zz_area` VALUES ('2868', '贡觉县', '429', '27,429,2868', '0', '1');
INSERT INTO `zz_area` VALUES ('2869', '类乌齐县', '429', '27,429,2869', '0', '1');
INSERT INTO `zz_area` VALUES ('2870', '丁青县', '429', '27,429,2870', '0', '1');
INSERT INTO `zz_area` VALUES ('2871', '察雅县', '429', '27,429,2871', '0', '1');
INSERT INTO `zz_area` VALUES ('2872', '八宿县', '429', '27,429,2872', '0', '1');
INSERT INTO `zz_area` VALUES ('2873', '左贡县', '429', '27,429,2873', '0', '1');
INSERT INTO `zz_area` VALUES ('2874', '芒康县', '429', '27,429,2874', '0', '1');
INSERT INTO `zz_area` VALUES ('2875', '洛隆县', '429', '27,429,2875', '0', '1');
INSERT INTO `zz_area` VALUES ('2876', '边坝县', '429', '27,429,2876', '0', '1');
INSERT INTO `zz_area` VALUES ('2877', '乃东县', '430', '27,430,2877', '0', '1');
INSERT INTO `zz_area` VALUES ('2878', '扎囊县', '430', '27,430,2878', '0', '1');
INSERT INTO `zz_area` VALUES ('2879', '贡嘎县', '430', '27,430,2879', '0', '1');
INSERT INTO `zz_area` VALUES ('2880', '桑日县', '430', '27,430,2880', '0', '1');
INSERT INTO `zz_area` VALUES ('2881', '琼结县', '430', '27,430,2881', '0', '1');
INSERT INTO `zz_area` VALUES ('2882', '曲松县', '430', '27,430,2882', '0', '1');
INSERT INTO `zz_area` VALUES ('2883', '措美县', '430', '27,430,2883', '0', '1');
INSERT INTO `zz_area` VALUES ('2884', '洛扎县', '430', '27,430,2884', '0', '1');
INSERT INTO `zz_area` VALUES ('2885', '加查县', '430', '27,430,2885', '0', '1');
INSERT INTO `zz_area` VALUES ('2886', '隆子县', '430', '27,430,2886', '0', '1');
INSERT INTO `zz_area` VALUES ('2887', '错那县', '430', '27,430,2887', '0', '1');
INSERT INTO `zz_area` VALUES ('2888', '浪卡子县', '430', '27,430,2888', '0', '1');
INSERT INTO `zz_area` VALUES ('2889', '日喀则市', '431', '27,431,2889', '0', '1');
INSERT INTO `zz_area` VALUES ('2890', '南木林县', '431', '27,431,2890', '0', '1');
INSERT INTO `zz_area` VALUES ('2891', '江孜县', '431', '27,431,2891', '0', '1');
INSERT INTO `zz_area` VALUES ('2892', '定日县', '431', '27,431,2892', '0', '1');
INSERT INTO `zz_area` VALUES ('2893', '萨迦县', '431', '27,431,2893', '0', '1');
INSERT INTO `zz_area` VALUES ('2894', '拉孜县', '431', '27,431,2894', '0', '1');
INSERT INTO `zz_area` VALUES ('2895', '昂仁县', '431', '27,431,2895', '0', '1');
INSERT INTO `zz_area` VALUES ('2896', '谢通门县', '431', '27,431,2896', '0', '1');
INSERT INTO `zz_area` VALUES ('2897', '白朗县', '431', '27,431,2897', '0', '1');
INSERT INTO `zz_area` VALUES ('2898', '仁布县', '431', '27,431,2898', '0', '1');
INSERT INTO `zz_area` VALUES ('2899', '康马县', '431', '27,431,2899', '0', '1');
INSERT INTO `zz_area` VALUES ('2900', '定结县', '431', '27,431,2900', '0', '1');
INSERT INTO `zz_area` VALUES ('2901', '仲巴县', '431', '27,431,2901', '0', '1');
INSERT INTO `zz_area` VALUES ('2902', '亚东县', '431', '27,431,2902', '0', '1');
INSERT INTO `zz_area` VALUES ('2903', '吉隆县', '431', '27,431,2903', '0', '1');
INSERT INTO `zz_area` VALUES ('2904', '聂拉木县', '431', '27,431,2904', '0', '1');
INSERT INTO `zz_area` VALUES ('2905', '萨嘎县', '431', '27,431,2905', '0', '1');
INSERT INTO `zz_area` VALUES ('2906', '岗巴县', '431', '27,431,2906', '0', '1');
INSERT INTO `zz_area` VALUES ('2907', '那曲县', '432', '27,432,2907', '0', '1');
INSERT INTO `zz_area` VALUES ('2908', '嘉黎县', '432', '27,432,2908', '0', '1');
INSERT INTO `zz_area` VALUES ('2909', '比如县', '432', '27,432,2909', '0', '1');
INSERT INTO `zz_area` VALUES ('2910', '聂荣县', '432', '27,432,2910', '0', '1');
INSERT INTO `zz_area` VALUES ('2911', '安多县', '432', '27,432,2911', '0', '1');
INSERT INTO `zz_area` VALUES ('2912', '申扎县', '432', '27,432,2912', '0', '1');
INSERT INTO `zz_area` VALUES ('2913', '索县', '432', '27,432,2913', '0', '1');
INSERT INTO `zz_area` VALUES ('2914', '班戈县', '432', '27,432,2914', '0', '1');
INSERT INTO `zz_area` VALUES ('2915', '巴青县', '432', '27,432,2915', '0', '1');
INSERT INTO `zz_area` VALUES ('2916', '尼玛县', '432', '27,432,2916', '0', '1');
INSERT INTO `zz_area` VALUES ('2917', '普兰县', '433', '27,433,2917', '0', '1');
INSERT INTO `zz_area` VALUES ('2918', '札达县', '433', '27,433,2918', '0', '1');
INSERT INTO `zz_area` VALUES ('2919', '噶尔县', '433', '27,433,2919', '0', '1');
INSERT INTO `zz_area` VALUES ('2920', '日土县', '433', '27,433,2920', '0', '1');
INSERT INTO `zz_area` VALUES ('2921', '革吉县', '433', '27,433,2921', '0', '1');
INSERT INTO `zz_area` VALUES ('2922', '改则县', '433', '27,433,2922', '0', '1');
INSERT INTO `zz_area` VALUES ('2923', '措勤县', '433', '27,433,2923', '0', '1');
INSERT INTO `zz_area` VALUES ('2924', '林芝县', '434', '27,434,2924', '0', '1');
INSERT INTO `zz_area` VALUES ('2925', '工布江达县', '434', '27,434,2925', '0', '1');
INSERT INTO `zz_area` VALUES ('2926', '米林县', '434', '27,434,2926', '0', '1');
INSERT INTO `zz_area` VALUES ('2927', '墨脱县', '434', '27,434,2927', '0', '1');
INSERT INTO `zz_area` VALUES ('2928', '波密县', '434', '27,434,2928', '0', '1');
INSERT INTO `zz_area` VALUES ('2929', '察隅县', '434', '27,434,2929', '0', '1');
INSERT INTO `zz_area` VALUES ('2930', '朗县', '434', '27,434,2930', '0', '1');
INSERT INTO `zz_area` VALUES ('2931', '新城区', '435', '28,435,2931', '0', '1');
INSERT INTO `zz_area` VALUES ('2932', '碑林区', '435', '28,435,2932', '0', '1');
INSERT INTO `zz_area` VALUES ('2933', '莲湖区', '435', '28,435,2933', '0', '1');
INSERT INTO `zz_area` VALUES ('2934', '灞桥区', '435', '28,435,2934', '0', '1');
INSERT INTO `zz_area` VALUES ('2935', '未央区', '435', '28,435,2935', '0', '1');
INSERT INTO `zz_area` VALUES ('2936', '雁塔区', '435', '28,435,2936', '0', '1');
INSERT INTO `zz_area` VALUES ('2937', '阎良区', '435', '28,435,2937', '0', '1');
INSERT INTO `zz_area` VALUES ('2938', '临潼区', '435', '28,435,2938', '0', '1');
INSERT INTO `zz_area` VALUES ('2939', '长安区', '435', '28,435,2939', '0', '1');
INSERT INTO `zz_area` VALUES ('2940', '蓝田县', '435', '28,435,2940', '0', '1');
INSERT INTO `zz_area` VALUES ('2941', '周至县', '435', '28,435,2941', '0', '1');
INSERT INTO `zz_area` VALUES ('2942', '户县', '435', '28,435,2942', '0', '1');
INSERT INTO `zz_area` VALUES ('2943', '高陵县', '435', '28,435,2943', '0', '1');
INSERT INTO `zz_area` VALUES ('2944', '王益区', '436', '28,436,2944', '0', '1');
INSERT INTO `zz_area` VALUES ('2945', '印台区', '436', '28,436,2945', '0', '1');
INSERT INTO `zz_area` VALUES ('2946', '耀州区', '436', '28,436,2946', '0', '1');
INSERT INTO `zz_area` VALUES ('2947', '宜君县', '436', '28,436,2947', '0', '1');
INSERT INTO `zz_area` VALUES ('2948', '渭滨区', '437', '28,437,2948', '0', '1');
INSERT INTO `zz_area` VALUES ('2949', '金台区', '437', '28,437,2949', '0', '1');
INSERT INTO `zz_area` VALUES ('2950', '陈仓区', '437', '28,437,2950', '0', '1');
INSERT INTO `zz_area` VALUES ('2951', '凤翔县', '437', '28,437,2951', '0', '1');
INSERT INTO `zz_area` VALUES ('2952', '岐山县', '437', '28,437,2952', '0', '1');
INSERT INTO `zz_area` VALUES ('2953', '扶风县', '437', '28,437,2953', '0', '1');
INSERT INTO `zz_area` VALUES ('2954', '眉县', '437', '28,437,2954', '0', '1');
INSERT INTO `zz_area` VALUES ('2955', '陇县', '437', '28,437,2955', '0', '1');
INSERT INTO `zz_area` VALUES ('2956', '千阳县', '437', '28,437,2956', '0', '1');
INSERT INTO `zz_area` VALUES ('2957', '麟游县', '437', '28,437,2957', '0', '1');
INSERT INTO `zz_area` VALUES ('2958', '凤县', '437', '28,437,2958', '0', '1');
INSERT INTO `zz_area` VALUES ('2959', '太白县', '437', '28,437,2959', '0', '1');
INSERT INTO `zz_area` VALUES ('2960', '秦都区', '438', '28,438,2960', '0', '1');
INSERT INTO `zz_area` VALUES ('2961', '杨凌区', '438', '28,438,2961', '0', '1');
INSERT INTO `zz_area` VALUES ('2962', '渭城区', '438', '28,438,2962', '0', '1');
INSERT INTO `zz_area` VALUES ('2963', '三原县', '438', '28,438,2963', '0', '1');
INSERT INTO `zz_area` VALUES ('2964', '泾阳县', '438', '28,438,2964', '0', '1');
INSERT INTO `zz_area` VALUES ('2965', '乾县', '438', '28,438,2965', '0', '1');
INSERT INTO `zz_area` VALUES ('2966', '礼泉县', '438', '28,438,2966', '0', '1');
INSERT INTO `zz_area` VALUES ('2967', '永寿县', '438', '28,438,2967', '0', '1');
INSERT INTO `zz_area` VALUES ('2968', '彬县', '438', '28,438,2968', '0', '1');
INSERT INTO `zz_area` VALUES ('2969', '长武县', '438', '28,438,2969', '0', '1');
INSERT INTO `zz_area` VALUES ('2970', '旬邑县', '438', '28,438,2970', '0', '1');
INSERT INTO `zz_area` VALUES ('2971', '淳化县', '438', '28,438,2971', '0', '1');
INSERT INTO `zz_area` VALUES ('2972', '武功县', '438', '28,438,2972', '0', '1');
INSERT INTO `zz_area` VALUES ('2973', '兴平市', '438', '28,438,2973', '0', '1');
INSERT INTO `zz_area` VALUES ('2974', '临渭区', '439', '28,439,2974', '0', '1');
INSERT INTO `zz_area` VALUES ('2975', '华县', '439', '28,439,2975', '0', '1');
INSERT INTO `zz_area` VALUES ('2976', '潼关县', '439', '28,439,2976', '0', '1');
INSERT INTO `zz_area` VALUES ('2977', '大荔县', '439', '28,439,2977', '0', '1');
INSERT INTO `zz_area` VALUES ('2978', '合阳县', '439', '28,439,2978', '0', '1');
INSERT INTO `zz_area` VALUES ('2979', '澄城县', '439', '28,439,2979', '0', '1');
INSERT INTO `zz_area` VALUES ('2980', '蒲城县', '439', '28,439,2980', '0', '1');
INSERT INTO `zz_area` VALUES ('2981', '白水县', '439', '28,439,2981', '0', '1');
INSERT INTO `zz_area` VALUES ('2982', '富平县', '439', '28,439,2982', '0', '1');
INSERT INTO `zz_area` VALUES ('2983', '韩城市', '439', '28,439,2983', '0', '1');
INSERT INTO `zz_area` VALUES ('2984', '华阴市', '439', '28,439,2984', '0', '1');
INSERT INTO `zz_area` VALUES ('2985', '宝塔区', '440', '28,440,2985', '0', '1');
INSERT INTO `zz_area` VALUES ('2986', '延长县', '440', '28,440,2986', '0', '1');
INSERT INTO `zz_area` VALUES ('2987', '延川县', '440', '28,440,2987', '0', '1');
INSERT INTO `zz_area` VALUES ('2988', '子长县', '440', '28,440,2988', '0', '1');
INSERT INTO `zz_area` VALUES ('2989', '安塞县', '440', '28,440,2989', '0', '1');
INSERT INTO `zz_area` VALUES ('2990', '志丹县', '440', '28,440,2990', '0', '1');
INSERT INTO `zz_area` VALUES ('2991', '吴起县', '440', '28,440,2991', '0', '1');
INSERT INTO `zz_area` VALUES ('2992', '甘泉县', '440', '28,440,2992', '0', '1');
INSERT INTO `zz_area` VALUES ('2993', '富县', '440', '28,440,2993', '0', '1');
INSERT INTO `zz_area` VALUES ('2994', '洛川县', '440', '28,440,2994', '0', '1');
INSERT INTO `zz_area` VALUES ('2995', '宜川县', '440', '28,440,2995', '0', '1');
INSERT INTO `zz_area` VALUES ('2996', '黄龙县', '440', '28,440,2996', '0', '1');
INSERT INTO `zz_area` VALUES ('2997', '黄陵县', '440', '28,440,2997', '0', '1');
INSERT INTO `zz_area` VALUES ('2998', '汉台区', '441', '28,441,2998', '0', '1');
INSERT INTO `zz_area` VALUES ('2999', '南郑县', '441', '28,441,2999', '0', '1');
INSERT INTO `zz_area` VALUES ('3000', '城固县', '441', '28,441,3000', '0', '1');
INSERT INTO `zz_area` VALUES ('3001', '洋县', '441', '28,441,3001', '0', '1');
INSERT INTO `zz_area` VALUES ('3002', '西乡县', '441', '28,441,3002', '0', '1');
INSERT INTO `zz_area` VALUES ('3003', '勉县', '441', '28,441,3003', '0', '1');
INSERT INTO `zz_area` VALUES ('3004', '宁强县', '441', '28,441,3004', '0', '1');
INSERT INTO `zz_area` VALUES ('3005', '略阳县', '441', '28,441,3005', '0', '1');
INSERT INTO `zz_area` VALUES ('3006', '镇巴县', '441', '28,441,3006', '0', '1');
INSERT INTO `zz_area` VALUES ('3007', '留坝县', '441', '28,441,3007', '0', '1');
INSERT INTO `zz_area` VALUES ('3008', '佛坪县', '441', '28,441,3008', '0', '1');
INSERT INTO `zz_area` VALUES ('3009', '榆阳区', '442', '28,442,3009', '0', '1');
INSERT INTO `zz_area` VALUES ('3010', '神木县', '442', '28,442,3010', '0', '1');
INSERT INTO `zz_area` VALUES ('3011', '府谷县', '442', '28,442,3011', '0', '1');
INSERT INTO `zz_area` VALUES ('3012', '横山县', '442', '28,442,3012', '0', '1');
INSERT INTO `zz_area` VALUES ('3013', '靖边县', '442', '28,442,3013', '0', '1');
INSERT INTO `zz_area` VALUES ('3014', '定边县', '442', '28,442,3014', '0', '1');
INSERT INTO `zz_area` VALUES ('3015', '绥德县', '442', '28,442,3015', '0', '1');
INSERT INTO `zz_area` VALUES ('3016', '米脂县', '442', '28,442,3016', '0', '1');
INSERT INTO `zz_area` VALUES ('3017', '佳县', '442', '28,442,3017', '0', '1');
INSERT INTO `zz_area` VALUES ('3018', '吴堡县', '442', '28,442,3018', '0', '1');
INSERT INTO `zz_area` VALUES ('3019', '清涧县', '442', '28,442,3019', '0', '1');
INSERT INTO `zz_area` VALUES ('3020', '子洲县', '442', '28,442,3020', '0', '1');
INSERT INTO `zz_area` VALUES ('3021', '汉滨区', '443', '28,443,3021', '0', '1');
INSERT INTO `zz_area` VALUES ('3022', '汉阴县', '443', '28,443,3022', '0', '1');
INSERT INTO `zz_area` VALUES ('3023', '石泉县', '443', '28,443,3023', '0', '1');
INSERT INTO `zz_area` VALUES ('3024', '宁陕县', '443', '28,443,3024', '0', '1');
INSERT INTO `zz_area` VALUES ('3025', '紫阳县', '443', '28,443,3025', '0', '1');
INSERT INTO `zz_area` VALUES ('3026', '岚皋县', '443', '28,443,3026', '0', '1');
INSERT INTO `zz_area` VALUES ('3027', '平利县', '443', '28,443,3027', '0', '1');
INSERT INTO `zz_area` VALUES ('3028', '镇坪县', '443', '28,443,3028', '0', '1');
INSERT INTO `zz_area` VALUES ('3029', '旬阳县', '443', '28,443,3029', '0', '1');
INSERT INTO `zz_area` VALUES ('3030', '白河县', '443', '28,443,3030', '0', '1');
INSERT INTO `zz_area` VALUES ('3031', '商州区', '444', '28,444,3031', '0', '1');
INSERT INTO `zz_area` VALUES ('3032', '洛南县', '444', '28,444,3032', '0', '1');
INSERT INTO `zz_area` VALUES ('3033', '丹凤县', '444', '28,444,3033', '0', '1');
INSERT INTO `zz_area` VALUES ('3034', '商南县', '444', '28,444,3034', '0', '1');
INSERT INTO `zz_area` VALUES ('3035', '山阳县', '444', '28,444,3035', '0', '1');
INSERT INTO `zz_area` VALUES ('3036', '镇安县', '444', '28,444,3036', '0', '1');
INSERT INTO `zz_area` VALUES ('3037', '柞水县', '444', '28,444,3037', '0', '1');
INSERT INTO `zz_area` VALUES ('3038', '城关区', '445', '29,445,3038', '0', '1');
INSERT INTO `zz_area` VALUES ('3039', '七里河区', '445', '29,445,3039', '0', '1');
INSERT INTO `zz_area` VALUES ('3040', '西固区', '445', '29,445,3040', '0', '1');
INSERT INTO `zz_area` VALUES ('3041', '安宁区', '445', '29,445,3041', '0', '1');
INSERT INTO `zz_area` VALUES ('3042', '红古区', '445', '29,445,3042', '0', '1');
INSERT INTO `zz_area` VALUES ('3043', '永登县', '445', '29,445,3043', '0', '1');
INSERT INTO `zz_area` VALUES ('3044', '皋兰县', '445', '29,445,3044', '0', '1');
INSERT INTO `zz_area` VALUES ('3045', '榆中县', '445', '29,445,3045', '0', '1');
INSERT INTO `zz_area` VALUES ('3046', '金川区', '447', '29,447,3046', '0', '1');
INSERT INTO `zz_area` VALUES ('3047', '永昌县', '447', '29,447,3047', '0', '1');
INSERT INTO `zz_area` VALUES ('3048', '白银区', '448', '29,448,3048', '0', '1');
INSERT INTO `zz_area` VALUES ('3049', '平川区', '448', '29,448,3049', '0', '1');
INSERT INTO `zz_area` VALUES ('3050', '靖远县', '448', '29,448,3050', '0', '1');
INSERT INTO `zz_area` VALUES ('3051', '会宁县', '448', '29,448,3051', '0', '1');
INSERT INTO `zz_area` VALUES ('3052', '景泰县', '448', '29,448,3052', '0', '1');
INSERT INTO `zz_area` VALUES ('3053', '秦城区', '449', '29,449,3053', '0', '1');
INSERT INTO `zz_area` VALUES ('3054', '北道区', '449', '29,449,3054', '0', '1');
INSERT INTO `zz_area` VALUES ('3055', '清水县', '449', '29,449,3055', '0', '1');
INSERT INTO `zz_area` VALUES ('3056', '秦安县', '449', '29,449,3056', '0', '1');
INSERT INTO `zz_area` VALUES ('3057', '甘谷县', '449', '29,449,3057', '0', '1');
INSERT INTO `zz_area` VALUES ('3058', '武山县', '449', '29,449,3058', '0', '1');
INSERT INTO `zz_area` VALUES ('3059', '张家川回族自治县', '449', '29,449,3059', '0', '1');
INSERT INTO `zz_area` VALUES ('3060', '凉州区', '450', '29,450,3060', '0', '1');
INSERT INTO `zz_area` VALUES ('3061', '民勤县', '450', '29,450,3061', '0', '1');
INSERT INTO `zz_area` VALUES ('3062', '古浪县', '450', '29,450,3062', '0', '1');
INSERT INTO `zz_area` VALUES ('3063', '天祝藏族自治县', '450', '29,450,3063', '0', '1');
INSERT INTO `zz_area` VALUES ('3064', '甘州区', '451', '29,451,3064', '0', '1');
INSERT INTO `zz_area` VALUES ('3065', '肃南裕固族自治县', '451', '29,451,3065', '0', '1');
INSERT INTO `zz_area` VALUES ('3066', '民乐县', '451', '29,451,3066', '0', '1');
INSERT INTO `zz_area` VALUES ('3067', '临泽县', '451', '29,451,3067', '0', '1');
INSERT INTO `zz_area` VALUES ('3068', '高台县', '451', '29,451,3068', '0', '1');
INSERT INTO `zz_area` VALUES ('3069', '山丹县', '451', '29,451,3069', '0', '1');
INSERT INTO `zz_area` VALUES ('3070', '崆峒区', '452', '29,452,3070', '0', '1');
INSERT INTO `zz_area` VALUES ('3071', '泾川县', '452', '29,452,3071', '0', '1');
INSERT INTO `zz_area` VALUES ('3072', '灵台县', '452', '29,452,3072', '0', '1');
INSERT INTO `zz_area` VALUES ('3073', '崇信县', '452', '29,452,3073', '0', '1');
INSERT INTO `zz_area` VALUES ('3074', '华亭县', '452', '29,452,3074', '0', '1');
INSERT INTO `zz_area` VALUES ('3075', '庄浪县', '452', '29,452,3075', '0', '1');
INSERT INTO `zz_area` VALUES ('3076', '静宁县', '452', '29,452,3076', '0', '1');
INSERT INTO `zz_area` VALUES ('3077', '肃州区', '453', '29,453,3077', '0', '1');
INSERT INTO `zz_area` VALUES ('3078', '金塔县', '453', '29,453,3078', '0', '1');
INSERT INTO `zz_area` VALUES ('3079', '瓜州县', '453', '29,453,3079', '0', '1');
INSERT INTO `zz_area` VALUES ('3080', '肃北蒙古族自治县', '453', '29,453,3080', '0', '1');
INSERT INTO `zz_area` VALUES ('3081', '阿克塞哈萨克族自治县', '453', '29,453,3081', '0', '1');
INSERT INTO `zz_area` VALUES ('3082', '玉门市', '453', '29,453,3082', '0', '1');
INSERT INTO `zz_area` VALUES ('3083', '敦煌市', '453', '29,453,3083', '0', '1');
INSERT INTO `zz_area` VALUES ('3084', '西峰区', '454', '29,454,3084', '0', '1');
INSERT INTO `zz_area` VALUES ('3085', '庆城县', '454', '29,454,3085', '0', '1');
INSERT INTO `zz_area` VALUES ('3086', '环县', '454', '29,454,3086', '0', '1');
INSERT INTO `zz_area` VALUES ('3087', '华池县', '454', '29,454,3087', '0', '1');
INSERT INTO `zz_area` VALUES ('3088', '合水县', '454', '29,454,3088', '0', '1');
INSERT INTO `zz_area` VALUES ('3089', '正宁县', '454', '29,454,3089', '0', '1');
INSERT INTO `zz_area` VALUES ('3090', '宁县', '454', '29,454,3090', '0', '1');
INSERT INTO `zz_area` VALUES ('3091', '镇原县', '454', '29,454,3091', '0', '1');
INSERT INTO `zz_area` VALUES ('3092', '安定区', '455', '29,455,3092', '0', '1');
INSERT INTO `zz_area` VALUES ('3093', '通渭县', '455', '29,455,3093', '0', '1');
INSERT INTO `zz_area` VALUES ('3094', '陇西县', '455', '29,455,3094', '0', '1');
INSERT INTO `zz_area` VALUES ('3095', '渭源县', '455', '29,455,3095', '0', '1');
INSERT INTO `zz_area` VALUES ('3096', '临洮县', '455', '29,455,3096', '0', '1');
INSERT INTO `zz_area` VALUES ('3097', '漳县', '455', '29,455,3097', '0', '1');
INSERT INTO `zz_area` VALUES ('3098', '岷县', '455', '29,455,3098', '0', '1');
INSERT INTO `zz_area` VALUES ('3099', '武都区', '456', '29,456,3099', '0', '1');
INSERT INTO `zz_area` VALUES ('3100', '成县', '456', '29,456,3100', '0', '1');
INSERT INTO `zz_area` VALUES ('3101', '文县', '456', '29,456,3101', '0', '1');
INSERT INTO `zz_area` VALUES ('3102', '宕昌县', '456', '29,456,3102', '0', '1');
INSERT INTO `zz_area` VALUES ('3103', '康县', '456', '29,456,3103', '0', '1');
INSERT INTO `zz_area` VALUES ('3104', '西和县', '456', '29,456,3104', '0', '1');
INSERT INTO `zz_area` VALUES ('3105', '礼县', '456', '29,456,3105', '0', '1');
INSERT INTO `zz_area` VALUES ('3106', '徽县', '456', '29,456,3106', '0', '1');
INSERT INTO `zz_area` VALUES ('3107', '两当县', '456', '29,456,3107', '0', '1');
INSERT INTO `zz_area` VALUES ('3108', '临夏市', '457', '29,457,3108', '0', '1');
INSERT INTO `zz_area` VALUES ('3109', '临夏县', '457', '29,457,3109', '0', '1');
INSERT INTO `zz_area` VALUES ('3110', '康乐县', '457', '29,457,3110', '0', '1');
INSERT INTO `zz_area` VALUES ('3111', '永靖县', '457', '29,457,3111', '0', '1');
INSERT INTO `zz_area` VALUES ('3112', '广河县', '457', '29,457,3112', '0', '1');
INSERT INTO `zz_area` VALUES ('3113', '和政县', '457', '29,457,3113', '0', '1');
INSERT INTO `zz_area` VALUES ('3114', '东乡族自治县', '457', '29,457,3114', '0', '1');
INSERT INTO `zz_area` VALUES ('3115', '积石山保安族东乡族撒拉族自治县', '457', '29,457,3115', '0', '1');
INSERT INTO `zz_area` VALUES ('3116', '合作市', '458', '29,458,3116', '0', '1');
INSERT INTO `zz_area` VALUES ('3117', '临潭县', '458', '29,458,3117', '0', '1');
INSERT INTO `zz_area` VALUES ('3118', '卓尼县', '458', '29,458,3118', '0', '1');
INSERT INTO `zz_area` VALUES ('3119', '舟曲县', '458', '29,458,3119', '0', '1');
INSERT INTO `zz_area` VALUES ('3120', '迭部县', '458', '29,458,3120', '0', '1');
INSERT INTO `zz_area` VALUES ('3121', '玛曲县', '458', '29,458,3121', '0', '1');
INSERT INTO `zz_area` VALUES ('3122', '碌曲县', '458', '29,458,3122', '0', '1');
INSERT INTO `zz_area` VALUES ('3123', '夏河县', '458', '29,458,3123', '0', '1');
INSERT INTO `zz_area` VALUES ('3124', '城东区', '459', '30,459,3124', '0', '1');
INSERT INTO `zz_area` VALUES ('3125', '城中区', '459', '30,459,3125', '0', '1');
INSERT INTO `zz_area` VALUES ('3126', '城西区', '459', '30,459,3126', '0', '1');
INSERT INTO `zz_area` VALUES ('3127', '城北区', '459', '30,459,3127', '0', '1');
INSERT INTO `zz_area` VALUES ('3128', '大通回族土族自治县', '459', '30,459,3128', '0', '1');
INSERT INTO `zz_area` VALUES ('3129', '湟中县', '459', '30,459,3129', '0', '1');
INSERT INTO `zz_area` VALUES ('3130', '湟源县', '459', '30,459,3130', '0', '1');
INSERT INTO `zz_area` VALUES ('3131', '平安县', '460', '30,460,3131', '0', '1');
INSERT INTO `zz_area` VALUES ('3132', '民和回族土族自治县', '460', '30,460,3132', '0', '1');
INSERT INTO `zz_area` VALUES ('3133', '乐都县', '460', '30,460,3133', '0', '1');
INSERT INTO `zz_area` VALUES ('3134', '互助土族自治县', '460', '30,460,3134', '0', '1');
INSERT INTO `zz_area` VALUES ('3135', '化隆回族自治县', '460', '30,460,3135', '0', '1');
INSERT INTO `zz_area` VALUES ('3136', '循化撒拉族自治县', '460', '30,460,3136', '0', '1');
INSERT INTO `zz_area` VALUES ('3137', '门源回族自治县', '461', '30,461,3137', '0', '1');
INSERT INTO `zz_area` VALUES ('3138', '祁连县', '461', '30,461,3138', '0', '1');
INSERT INTO `zz_area` VALUES ('3139', '海晏县', '461', '30,461,3139', '0', '1');
INSERT INTO `zz_area` VALUES ('3140', '刚察县', '461', '30,461,3140', '0', '1');
INSERT INTO `zz_area` VALUES ('3141', '同仁县', '462', '30,462,3141', '0', '1');
INSERT INTO `zz_area` VALUES ('3142', '尖扎县', '462', '30,462,3142', '0', '1');
INSERT INTO `zz_area` VALUES ('3143', '泽库县', '462', '30,462,3143', '0', '1');
INSERT INTO `zz_area` VALUES ('3144', '河南蒙古族自治县', '462', '30,462,3144', '0', '1');
INSERT INTO `zz_area` VALUES ('3145', '共和县', '463', '30,463,3145', '0', '1');
INSERT INTO `zz_area` VALUES ('3146', '同德县', '463', '30,463,3146', '0', '1');
INSERT INTO `zz_area` VALUES ('3147', '贵德县', '463', '30,463,3147', '0', '1');
INSERT INTO `zz_area` VALUES ('3148', '兴海县', '463', '30,463,3148', '0', '1');
INSERT INTO `zz_area` VALUES ('3149', '贵南县', '463', '30,463,3149', '0', '1');
INSERT INTO `zz_area` VALUES ('3150', '玛沁县', '464', '30,464,3150', '0', '1');
INSERT INTO `zz_area` VALUES ('3151', '班玛县', '464', '30,464,3151', '0', '1');
INSERT INTO `zz_area` VALUES ('3152', '甘德县', '464', '30,464,3152', '0', '1');
INSERT INTO `zz_area` VALUES ('3153', '达日县', '464', '30,464,3153', '0', '1');
INSERT INTO `zz_area` VALUES ('3154', '久治县', '464', '30,464,3154', '0', '1');
INSERT INTO `zz_area` VALUES ('3155', '玛多县', '464', '30,464,3155', '0', '1');
INSERT INTO `zz_area` VALUES ('3156', '玉树县', '465', '30,465,3156', '0', '1');
INSERT INTO `zz_area` VALUES ('3157', '杂多县', '465', '30,465,3157', '0', '1');
INSERT INTO `zz_area` VALUES ('3158', '称多县', '465', '30,465,3158', '0', '1');
INSERT INTO `zz_area` VALUES ('3159', '治多县', '465', '30,465,3159', '0', '1');
INSERT INTO `zz_area` VALUES ('3160', '囊谦县', '465', '30,465,3160', '0', '1');
INSERT INTO `zz_area` VALUES ('3161', '曲麻莱县', '465', '30,465,3161', '0', '1');
INSERT INTO `zz_area` VALUES ('3162', '格尔木市', '466', '30,466,3162', '0', '1');
INSERT INTO `zz_area` VALUES ('3163', '德令哈市', '466', '30,466,3163', '0', '1');
INSERT INTO `zz_area` VALUES ('3164', '乌兰县', '466', '30,466,3164', '0', '1');
INSERT INTO `zz_area` VALUES ('3165', '都兰县', '466', '30,466,3165', '0', '1');
INSERT INTO `zz_area` VALUES ('3166', '天峻县', '466', '30,466,3166', '0', '1');
INSERT INTO `zz_area` VALUES ('3167', '兴庆区', '467', '31,467,3167', '0', '1');
INSERT INTO `zz_area` VALUES ('3168', '西夏区', '467', '31,467,3168', '0', '1');
INSERT INTO `zz_area` VALUES ('3169', '金凤区', '467', '31,467,3169', '0', '1');
INSERT INTO `zz_area` VALUES ('3170', '永宁县', '467', '31,467,3170', '0', '1');
INSERT INTO `zz_area` VALUES ('3171', '贺兰县', '467', '31,467,3171', '0', '1');
INSERT INTO `zz_area` VALUES ('3172', '灵武市', '467', '31,467,3172', '0', '1');
INSERT INTO `zz_area` VALUES ('3173', '大武口区', '468', '31,468,3173', '0', '1');
INSERT INTO `zz_area` VALUES ('3174', '惠农区', '468', '31,468,3174', '0', '1');
INSERT INTO `zz_area` VALUES ('3175', '平罗县', '468', '31,468,3175', '0', '1');
INSERT INTO `zz_area` VALUES ('3176', '利通区', '469', '31,469,3176', '0', '1');
INSERT INTO `zz_area` VALUES ('3177', '盐池县', '469', '31,469,3177', '0', '1');
INSERT INTO `zz_area` VALUES ('3178', '同心县', '469', '31,469,3178', '0', '1');
INSERT INTO `zz_area` VALUES ('3179', '青铜峡市', '469', '31,469,3179', '0', '1');
INSERT INTO `zz_area` VALUES ('3180', '原州区', '470', '31,470,3180', '0', '1');
INSERT INTO `zz_area` VALUES ('3181', '西吉县', '470', '31,470,3181', '0', '1');
INSERT INTO `zz_area` VALUES ('3182', '隆德县', '470', '31,470,3182', '0', '1');
INSERT INTO `zz_area` VALUES ('3183', '泾源县', '470', '31,470,3183', '0', '1');
INSERT INTO `zz_area` VALUES ('3184', '彭阳县', '470', '31,470,3184', '0', '1');
INSERT INTO `zz_area` VALUES ('3185', '沙坡头区', '471', '31,471,3185', '0', '1');
INSERT INTO `zz_area` VALUES ('3186', '中宁县', '471', '31,471,3186', '0', '1');
INSERT INTO `zz_area` VALUES ('3187', '海原县', '471', '31,471,3187', '0', '1');
INSERT INTO `zz_area` VALUES ('3188', '天山区', '472', '32,472,3188', '0', '1');
INSERT INTO `zz_area` VALUES ('3189', '沙依巴克区', '472', '32,472,3189', '0', '1');
INSERT INTO `zz_area` VALUES ('3190', '新市区', '472', '32,472,3190', '0', '1');
INSERT INTO `zz_area` VALUES ('3191', '水磨沟区', '472', '32,472,3191', '0', '1');
INSERT INTO `zz_area` VALUES ('3192', '头屯河区', '472', '32,472,3192', '0', '1');
INSERT INTO `zz_area` VALUES ('3193', '达坂城区', '472', '32,472,3193', '0', '1');
INSERT INTO `zz_area` VALUES ('3194', '东山区', '472', '32,472,3194', '0', '1');
INSERT INTO `zz_area` VALUES ('3195', '乌鲁木齐县', '472', '32,472,3195', '0', '1');
INSERT INTO `zz_area` VALUES ('3196', '独山子区', '473', '32,473,3196', '0', '1');
INSERT INTO `zz_area` VALUES ('3197', '克拉玛依区', '473', '32,473,3197', '0', '1');
INSERT INTO `zz_area` VALUES ('3198', '白碱滩区', '473', '32,473,3198', '0', '1');
INSERT INTO `zz_area` VALUES ('3199', '乌尔禾区', '473', '32,473,3199', '0', '1');
INSERT INTO `zz_area` VALUES ('3200', '吐鲁番市', '474', '32,474,3200', '0', '1');
INSERT INTO `zz_area` VALUES ('3201', '鄯善县', '474', '32,474,3201', '0', '1');
INSERT INTO `zz_area` VALUES ('3202', '托克逊县', '474', '32,474,3202', '0', '1');
INSERT INTO `zz_area` VALUES ('3203', '哈密市', '475', '32,475,3203', '0', '1');
INSERT INTO `zz_area` VALUES ('3204', '巴里坤哈萨克自治县', '475', '32,475,3204', '0', '1');
INSERT INTO `zz_area` VALUES ('3205', '伊吾县', '475', '32,475,3205', '0', '1');
INSERT INTO `zz_area` VALUES ('3206', '昌吉市', '476', '32,476,3206', '0', '1');
INSERT INTO `zz_area` VALUES ('3207', '阜康市', '476', '32,476,3207', '0', '1');
INSERT INTO `zz_area` VALUES ('3208', '米泉市', '476', '32,476,3208', '0', '1');
INSERT INTO `zz_area` VALUES ('3209', '呼图壁县', '476', '32,476,3209', '0', '1');
INSERT INTO `zz_area` VALUES ('3210', '玛纳斯县', '476', '32,476,3210', '0', '1');
INSERT INTO `zz_area` VALUES ('3211', '奇台县', '476', '32,476,3211', '0', '1');
INSERT INTO `zz_area` VALUES ('3212', '吉木萨尔县', '476', '32,476,3212', '0', '1');
INSERT INTO `zz_area` VALUES ('3213', '木垒哈萨克自治县', '476', '32,476,3213', '0', '1');
INSERT INTO `zz_area` VALUES ('3214', '博乐市', '477', '32,477,3214', '0', '1');
INSERT INTO `zz_area` VALUES ('3215', '精河县', '477', '32,477,3215', '0', '1');
INSERT INTO `zz_area` VALUES ('3216', '温泉县', '477', '32,477,3216', '0', '1');
INSERT INTO `zz_area` VALUES ('3217', '库尔勒市', '478', '32,478,3217', '0', '1');
INSERT INTO `zz_area` VALUES ('3218', '轮台县', '478', '32,478,3218', '0', '1');
INSERT INTO `zz_area` VALUES ('3219', '尉犁县', '478', '32,478,3219', '0', '1');
INSERT INTO `zz_area` VALUES ('3220', '若羌县', '478', '32,478,3220', '0', '1');
INSERT INTO `zz_area` VALUES ('3221', '且末县', '478', '32,478,3221', '0', '1');
INSERT INTO `zz_area` VALUES ('3222', '焉耆回族自治县', '478', '32,478,3222', '0', '1');
INSERT INTO `zz_area` VALUES ('3223', '和静县', '478', '32,478,3223', '0', '1');
INSERT INTO `zz_area` VALUES ('3224', '和硕县', '478', '32,478,3224', '0', '1');
INSERT INTO `zz_area` VALUES ('3225', '博湖县', '478', '32,478,3225', '0', '1');
INSERT INTO `zz_area` VALUES ('3226', '阿克苏市', '479', '32,479,3226', '0', '1');
INSERT INTO `zz_area` VALUES ('3227', '温宿县', '479', '32,479,3227', '0', '1');
INSERT INTO `zz_area` VALUES ('3228', '库车县', '479', '32,479,3228', '0', '1');
INSERT INTO `zz_area` VALUES ('3229', '沙雅县', '479', '32,479,3229', '0', '1');
INSERT INTO `zz_area` VALUES ('3230', '新和县', '479', '32,479,3230', '0', '1');
INSERT INTO `zz_area` VALUES ('3231', '拜城县', '479', '32,479,3231', '0', '1');
INSERT INTO `zz_area` VALUES ('3232', '乌什县', '479', '32,479,3232', '0', '1');
INSERT INTO `zz_area` VALUES ('3233', '阿瓦提县', '479', '32,479,3233', '0', '1');
INSERT INTO `zz_area` VALUES ('3234', '柯坪县', '479', '32,479,3234', '0', '1');
INSERT INTO `zz_area` VALUES ('3235', '阿图什市', '480', '32,480,3235', '0', '1');
INSERT INTO `zz_area` VALUES ('3236', '阿克陶县', '480', '32,480,3236', '0', '1');
INSERT INTO `zz_area` VALUES ('3237', '阿合奇县', '480', '32,480,3237', '0', '1');
INSERT INTO `zz_area` VALUES ('3238', '乌恰县', '480', '32,480,3238', '0', '1');
INSERT INTO `zz_area` VALUES ('3239', '喀什市', '481', '32,481,3239', '0', '1');
INSERT INTO `zz_area` VALUES ('3240', '疏附县', '481', '32,481,3240', '0', '1');
INSERT INTO `zz_area` VALUES ('3241', '疏勒县', '481', '32,481,3241', '0', '1');
INSERT INTO `zz_area` VALUES ('3242', '英吉沙县', '481', '32,481,3242', '0', '1');
INSERT INTO `zz_area` VALUES ('3243', '泽普县', '481', '32,481,3243', '0', '1');
INSERT INTO `zz_area` VALUES ('3244', '莎车县', '481', '32,481,3244', '0', '1');
INSERT INTO `zz_area` VALUES ('3245', '叶城县', '481', '32,481,3245', '0', '1');
INSERT INTO `zz_area` VALUES ('3246', '麦盖提县', '481', '32,481,3246', '0', '1');
INSERT INTO `zz_area` VALUES ('3247', '岳普湖县', '481', '32,481,3247', '0', '1');
INSERT INTO `zz_area` VALUES ('3248', '伽师县', '481', '32,481,3248', '0', '1');
INSERT INTO `zz_area` VALUES ('3249', '巴楚县', '481', '32,481,3249', '0', '1');
INSERT INTO `zz_area` VALUES ('3250', '塔什库尔干塔吉克自治县', '481', '32,481,3250', '0', '1');
INSERT INTO `zz_area` VALUES ('3251', '和田市', '482', '32,482,3251', '0', '1');
INSERT INTO `zz_area` VALUES ('3252', '和田县', '482', '32,482,3252', '0', '1');
INSERT INTO `zz_area` VALUES ('3253', '墨玉县', '482', '32,482,3253', '0', '1');
INSERT INTO `zz_area` VALUES ('3254', '皮山县', '482', '32,482,3254', '0', '1');
INSERT INTO `zz_area` VALUES ('3255', '洛浦县', '482', '32,482,3255', '0', '1');
INSERT INTO `zz_area` VALUES ('3256', '策勒县', '482', '32,482,3256', '0', '1');
INSERT INTO `zz_area` VALUES ('3257', '于田县', '482', '32,482,3257', '0', '1');
INSERT INTO `zz_area` VALUES ('3258', '民丰县', '482', '32,482,3258', '0', '1');
INSERT INTO `zz_area` VALUES ('3259', '伊宁市', '483', '32,483,3259', '0', '1');
INSERT INTO `zz_area` VALUES ('3260', '奎屯市', '483', '32,483,3260', '0', '1');
INSERT INTO `zz_area` VALUES ('3261', '伊宁县', '483', '32,483,3261', '0', '1');
INSERT INTO `zz_area` VALUES ('3262', '察布查尔锡伯自治县', '483', '32,483,3262', '0', '1');
INSERT INTO `zz_area` VALUES ('3263', '霍城县', '483', '32,483,3263', '0', '1');
INSERT INTO `zz_area` VALUES ('3264', '巩留县', '483', '32,483,3264', '0', '1');
INSERT INTO `zz_area` VALUES ('3265', '新源县', '483', '32,483,3265', '0', '1');
INSERT INTO `zz_area` VALUES ('3266', '昭苏县', '483', '32,483,3266', '0', '1');
INSERT INTO `zz_area` VALUES ('3267', '特克斯县', '483', '32,483,3267', '0', '1');
INSERT INTO `zz_area` VALUES ('3268', '尼勒克县', '483', '32,483,3268', '0', '1');
INSERT INTO `zz_area` VALUES ('3269', '塔城市', '484', '32,484,3269', '0', '1');
INSERT INTO `zz_area` VALUES ('3270', '乌苏市', '484', '32,484,3270', '0', '1');
INSERT INTO `zz_area` VALUES ('3271', '额敏县', '484', '32,484,3271', '0', '1');
INSERT INTO `zz_area` VALUES ('3272', '沙湾县', '484', '32,484,3272', '0', '1');
INSERT INTO `zz_area` VALUES ('3273', '托里县', '484', '32,484,3273', '0', '1');
INSERT INTO `zz_area` VALUES ('3274', '裕民县', '484', '32,484,3274', '0', '1');
INSERT INTO `zz_area` VALUES ('3275', '和布克赛尔蒙古自治县', '484', '32,484,3275', '0', '1');
INSERT INTO `zz_area` VALUES ('3276', '阿勒泰市', '485', '32,485,3276', '0', '1');
INSERT INTO `zz_area` VALUES ('3277', '布尔津县', '485', '32,485,3277', '0', '1');
INSERT INTO `zz_area` VALUES ('3278', '富蕴县', '485', '32,485,3278', '0', '1');
INSERT INTO `zz_area` VALUES ('3279', '福海县', '485', '32,485,3279', '0', '1');
INSERT INTO `zz_area` VALUES ('3280', '哈巴河县', '485', '32,485,3280', '0', '1');
INSERT INTO `zz_area` VALUES ('3281', '青河县', '485', '32,485,3281', '0', '1');
INSERT INTO `zz_area` VALUES ('3282', '吉木乃县', '485', '32,485,3282', '0', '1');
INSERT INTO `zz_area` VALUES ('3358', '钓鱼岛', '0', null, '2', '1');
INSERT INTO `zz_area` VALUES ('3359', '钓鱼岛', '3358', '3358,3359,', '0', '1');
INSERT INTO `zz_area` VALUES ('3365', '清濛开发区', '245', ',15,245,', '0', '1');
INSERT INTO `zz_area` VALUES ('3366', '台商区', '245', ',15,245,', '0', '1');

-- ----------------------------
-- Table structure for zz_article
-- ----------------------------
DROP TABLE IF EXISTS `zz_article`;
CREATE TABLE `zz_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `thumb1` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '二维码',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '缩略图',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '简介',
  `content` text COLLATE utf8_bin COMMENT '正文',
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '跳转链接',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核 1审核 0未审核',
  `islink` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否外链 1是',
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '发布人',
  `inputtime` int(11) DEFAULT NULL COMMENT '发布时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '类型 1推荐 2头条 0默认',
  `imglist` text COLLATE utf8_bin,
  `author` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sources` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `hit` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='内容表';

-- ----------------------------
-- Records of zz_article
-- ----------------------------

-- ----------------------------
-- Table structure for zz_attachment
-- ----------------------------
DROP TABLE IF EXISTS `zz_attachment`;
CREATE TABLE `zz_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `savename` varchar(100) NOT NULL,
  `ext` varchar(20) NOT NULL,
  `size` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `uid` int(11) NOT NULL,
  `catid` tinyint(3) unsigned NOT NULL,
  `info` varchar(255) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL,
  `ip` varchar(16) NOT NULL,
  `name` varchar(100) NOT NULL,
  `isimage` tinyint(4) DEFAULT '0' COMMENT '是否为图片 1为图片',
  `isthumb` tinyint(4) DEFAULT '0' COMMENT '是否为缩略图 1为缩略图',
  `isadmin` tinyint(4) DEFAULT '0' COMMENT '是否后台用户上传',
  `status` tinyint(4) DEFAULT '0' COMMENT '附件使用状态',
  `authcode` char(32) DEFAULT NULL COMMENT '附件路径MD5值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of zz_attachment
-- ----------------------------
INSERT INTO `zz_attachment` VALUES ('1', '570fa300af154.png', 'png', '14866', '/Uploads/scrawl/570fa300af154.png', '1', '3', 'scrawl.png', '1460642560', '127.0.0.1', 'scrawl.png', '1', '0', '1', '0', null);

-- ----------------------------
-- Table structure for zz_attention
-- ----------------------------
DROP TABLE IF EXISTS `zz_attention`;
CREATE TABLE `zz_attention` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '产品id',
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '备注',
  `inputtime` int(11) NOT NULL COMMENT '关注时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='关注表';

-- ----------------------------
-- Records of zz_attention
-- ----------------------------

-- ----------------------------
-- Table structure for zz_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `zz_auth_group`;
CREATE TABLE `zz_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='后台用户组表';

-- ----------------------------
-- Records of zz_auth_group
-- ----------------------------
INSERT INTO `zz_auth_group` VALUES ('1', '超级管理员', '1', '', '拥有网站最高管理员权限！');
INSERT INTO `zz_auth_group` VALUES ('2', '平台管理人员', '1', '26,27,28,69,93,105,94,95,96,408,270,265,266,267,268,269,172,174,177,249,175,178,248,179,180,181,182,183,187,188,189,190,191,206,207,208,209,210,235,236,238,237,239,240,241,242,251,253,252,274,275,276,277,278,279,153,154,156,157,158,159,160,161,162,155,163,164,165,166,167,168,213,323,324,328,329,330,331,395,396,148,280,149,150,151,281,271,272,273,290,291,292,293,294,295,296,297,214,215,216,217,218,219,220,221,366,336,337,338,339,340,341,342,224,225,229,226,227,228,343,344,345,29,30,257,258,259,260,261,262,263,264,36,31,38,49,50,52,53,55,51,54,56,57,58,59,64,62,63,134,135,136,137,138,139,141,142,143,144,145,140,1,2,9,25,212,298,245,246,255,256,71,20,21,23,22,24,114,115,117,118,119,128,116,120,121,122,123,124,125,126,127,199,200,201,202,203,204,205,243', '平台管理人员');
INSERT INTO `zz_auth_group` VALUES ('3', '门店管理员', '1', '26,27,28,69,274,384,385,386,387,389,153,155,323,324,299,300,301,302,303,304,326,327,332,333,334,335,397,404,405,406,407,148,325,282,283,284,285,286,287,288,289,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,214,215,216,371,398,372,374,375,376,377,382,380,381,383,217,218,219,220,221,366,373,391,392,393,394,379,29,58,59,64,62,63', '门店管理员');
INSERT INTO `zz_auth_group` VALUES ('4', '包装员', '1', '26,27,28,214,348,349,350,352,353,354', '包装员');
INSERT INTO `zz_auth_group` VALUES ('5', '门店财务人员', '1', '26,27,28,224,351,355,356,357,399,400,401,402,403', '门店财务人员');
INSERT INTO `zz_auth_group` VALUES ('6', '企业管理员', '1', '26,27,28,69,172,197,198,247,358,359,360,361,363,362,364,365', '企业管理员');

-- ----------------------------
-- Table structure for zz_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `zz_auth_group_access`;
CREATE TABLE `zz_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台用户、用户组关系表';

-- ----------------------------
-- Records of zz_auth_group_access
-- ----------------------------
INSERT INTO `zz_auth_group_access` VALUES ('1', '0');
INSERT INTO `zz_auth_group_access` VALUES ('5', '2');
INSERT INTO `zz_auth_group_access` VALUES ('12', '3');
INSERT INTO `zz_auth_group_access` VALUES ('13', '4');
INSERT INTO `zz_auth_group_access` VALUES ('14', '3');
INSERT INTO `zz_auth_group_access` VALUES ('15', '4');
INSERT INTO `zz_auth_group_access` VALUES ('16', '4');
INSERT INTO `zz_auth_group_access` VALUES ('17', '4');
INSERT INTO `zz_auth_group_access` VALUES ('18', '4');
INSERT INTO `zz_auth_group_access` VALUES ('19', '5');
INSERT INTO `zz_auth_group_access` VALUES ('20', '3');
INSERT INTO `zz_auth_group_access` VALUES ('21', '4');
INSERT INTO `zz_auth_group_access` VALUES ('22', '4');
INSERT INTO `zz_auth_group_access` VALUES ('23', '5');
INSERT INTO `zz_auth_group_access` VALUES ('24', '6');
INSERT INTO `zz_auth_group_access` VALUES ('25', '3');
INSERT INTO `zz_auth_group_access` VALUES ('27', '6');
INSERT INTO `zz_auth_group_access` VALUES ('28', '6');
INSERT INTO `zz_auth_group_access` VALUES ('30', '4');

-- ----------------------------
-- Table structure for zz_cart
-- ----------------------------
DROP TABLE IF EXISTS `zz_cart`;
CREATE TABLE `zz_cart` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '商品种数数量',
  `money` double(50,2) NOT NULL DEFAULT '0.00' COMMENT '购物车总金额',
  `inputtime` int(11) NOT NULL,
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='购物车表';

-- ----------------------------
-- Records of zz_cart
-- ----------------------------

-- ----------------------------
-- Table structure for zz_cartinfo
-- ----------------------------
DROP TABLE IF EXISTS `zz_cartinfo`;
CREATE TABLE `zz_cartinfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cartid` int(11) NOT NULL COMMENT '购物车表id',
  `storeid` int(11) NOT NULL COMMENT '门店id',
  `pid` int(11) NOT NULL COMMENT '产品id',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '产品数量',
  `price` decimal(50,2) NOT NULL,
  `money` decimal(50,2) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '商品类型 0企业专区  1一般商品  2团购  3预购  4称重',
  `inputtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='购物车详细表';

-- ----------------------------
-- Records of zz_cartinfo
-- ----------------------------

-- ----------------------------
-- Table structure for zz_category
-- ----------------------------
DROP TABLE IF EXISTS `zz_category`;
CREATE TABLE `zz_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类别 1内部栏目 2外部链接',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID 1单页模块 2列表模块',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `arrparentid` varchar(255) DEFAULT NULL COMMENT '所有父ID',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1存在',
  `arrchildid` mediumtext COMMENT '所有子栏目ID',
  `catname` varchar(30) NOT NULL COMMENT '栏目名称',
  `encatname` varchar(30) DEFAULT NULL COMMENT '栏目英文名称',
  `image` varchar(100) DEFAULT NULL COMMENT '栏目图片',
  `description` mediumtext COMMENT '栏目描述',
  `url` varchar(100) DEFAULT NULL COMMENT '链接地址',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `ultimate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否终极 1是 0否',
  `meta_title` varchar(255) DEFAULT NULL COMMENT '栏目标题',
  `meta_keywords` varchar(255) DEFAULT NULL COMMENT '栏目关键字',
  `meta_description` varchar(255) DEFAULT NULL COMMENT '栏目描述',
  PRIMARY KEY (`id`),
  KEY `module` (`parentid`,`listorder`,`id`) USING BTREE,
  KEY `siteid` (`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of zz_category
-- ----------------------------

-- ----------------------------
-- Table structure for zz_collect
-- ----------------------------
DROP TABLE IF EXISTS `zz_collect`;
CREATE TABLE `zz_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '收藏者',
  `value` int(11) DEFAULT NULL,
  `varname` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '类型 shop：商户  product：产品',
  `inputtime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='收藏表';

-- ----------------------------
-- Records of zz_collect
-- ----------------------------

-- ----------------------------
-- Table structure for zz_company
-- ----------------------------
DROP TABLE IF EXISTS `zz_company`;
CREATE TABLE `zz_company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `applyid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '企业名称',
  `companynumber` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '企业编号',
  `head` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '企业LOGO',
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '企业负责人',
  `tel` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '企业负责人联系方式',
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '绑定邮箱',
  `status` tinyint(4) DEFAULT '1' COMMENT '1审核  0待审核',
  `updatetime` int(11) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='企业表';

-- ----------------------------
-- Records of zz_company
-- ----------------------------

-- ----------------------------
-- Table structure for zz_companyorder_info
-- ----------------------------
DROP TABLE IF EXISTS `zz_companyorder_info`;
CREATE TABLE `zz_companyorder_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL,
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL,
  `ordernum` int(11) NOT NULL DEFAULT '0' COMMENT '订单汇总数',
  `ordermoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '订单汇总额',
  `yes_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '已结算金额',
  `no_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '未结算金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未结算  1部分结算 2完成结算',
  `last_paytime` int(11) NOT NULL COMMENT '最后打款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='企业订单结算信息表';

-- ----------------------------
-- Records of zz_companyorder_info
-- ----------------------------

-- ----------------------------
-- Table structure for zz_company_member
-- ----------------------------
DROP TABLE IF EXISTS `zz_company_member`;
CREATE TABLE `zz_company_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyid` int(11) DEFAULT NULL COMMENT '企业id',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '申请人姓名',
  `phone` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '申请人手机号',
  `inputtime` int(11) DEFAULT NULL,
  `status` int(5) DEFAULT '1' COMMENT '审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='企业会员关系表';

-- ----------------------------
-- Records of zz_company_member
-- ----------------------------

-- ----------------------------
-- Table structure for zz_config
-- ----------------------------
DROP TABLE IF EXISTS `zz_config`;
CREATE TABLE `zz_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '名称',
  `info` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `groupid` int(11) DEFAULT '1' COMMENT '类别 1网站配置 2邮箱配置',
  `value` text COLLATE utf8_bin COMMENT '值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='网站配置表';

-- ----------------------------
-- Records of zz_config
-- ----------------------------
INSERT INTO `zz_config` VALUES ('1', 'sitename', '站点名称', '1', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('2', 'siteurl', '网站域名', '1', 0x687474703A2F2F6C6F63616C686F73743A383038302F);
INSERT INTO `zz_config` VALUES ('3', 'sitetitle', '网站标题', '1', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('4', 'sitekeywords', '网站关键字', '1', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('5', 'sitedescription', '网站描述', '1', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('6', 'sitetel', '网站电话', '1', '');
INSERT INTO `zz_config` VALUES ('7', 'siteaddress', '公司地址', '1', '');
INSERT INTO `zz_config` VALUES ('8', 'sitecopyright', '版权信息', '1', '');
INSERT INTO `zz_config` VALUES ('9', 'siteicp', '备案号', '1', '');
INSERT INTO `zz_config` VALUES ('10', 'mail_type', '邮件发送模式', '2', 0x31);
INSERT INTO `zz_config` VALUES ('11', 'mail_server', '邮件服务器', '2', 0x736D74702E6D7868696368696E612E636F6D);
INSERT INTO `zz_config` VALUES ('12', 'mail_port', '邮件发送端口', '2', 0x343635);
INSERT INTO `zz_config` VALUES ('13', 'mail_from', '发件人地址', '2', 0x7365727669636540657375676F2E636E);
INSERT INTO `zz_config` VALUES ('14', 'mail_auth', 'AUTH LOGIN验证', '2', 0x31);
INSERT INTO `zz_config` VALUES ('15', 'mail_user', '验证用户名', '2', 0x7365727669636540657375676F2E636E);
INSERT INTO `zz_config` VALUES ('16', 'mail_password', '验证密码', '2', 0x536778734032303135);
INSERT INTO `zz_config` VALUES ('17', 'mail_fname', '发件人名称', '2', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('40', 'siteleftcontact', '侧边联系方式', '1', '');
INSERT INTO `zz_config` VALUES ('41', 'email_reg', '用户注册成功', '3', 0x3C703E266E6273703B3C2F703E0D0A0D0A3C646976207374796C653D22746578742D616C69676E3A7269676874223E266E6273703B3C2F6469763E);
INSERT INTO `zz_config` VALUES ('42', 'email_password', '密码找回提示', '3', 0x3C703EE5B08AE695ACE79A84277B23757365726E616D65237D27E682A8E5A5BDEFBC813C6272202F3E0D0AE38080E38080E682A8E794B3E8AFB7E4BA86E5AF86E7A081E689BEE59B9EEFBC8CE8AFB7E782B9E587BBE9AA8CE8AF81E993BEE68EA5E280997B236C696E6B237DE28098E8BF9BE8A18CE5AF86E7A081E689BEE59B9EE79A84E4B88BE4B880E6ADA5E6938DE4BD9C3C2F703E0D0A0D0A3C646976207374796C653D22746578742D616C69676E3A7269676874223EE8A681E59083E595A520E695ACE590AF3C2F6469763E);
INSERT INTO `zz_config` VALUES ('43', 'phone_code', '短信验证码提示', '3', 0xE38090E894ACE69E9CE58588E7949FE38091E682A8E5A5BDEFBC8CE682A8E79A84E6898BE69CBAE9AA8CE8AF81E7A081E698AF7B23636F6465237D);
INSERT INTO `zz_config` VALUES ('44', 'mes_reg', '注册成功', '3', 0xE682A8E5A5BDEFBC8CE681ADE5969CE682A8E6B3A8E5868CE68890E58A9F);
INSERT INTO `zz_config` VALUES ('45', 'email_reg_subject', '用户注册成功', '3', 0xE794A8E688B7E6B3A8E5868CE68890E58A9F);
INSERT INTO `zz_config` VALUES ('46', 'email_password_subject', '密码找回提示', '3', 0xE5AF86E7A081E689BEE59B9E);
INSERT INTO `zz_config` VALUES ('47', 'uploadASize', '允许上传附件大小', '4', 0x323032343030303030303030);
INSERT INTO `zz_config` VALUES ('48', 'uploadAType', '允许上传附件类型', '4', 0x6A70677C6A7065677C6769667C706E677C646F637C7A6970);
INSERT INTO `zz_config` VALUES ('49', 'uploadHSize', '前台允许上传附件大小', '4', 0x32303030303030303030);
INSERT INTO `zz_config` VALUES ('50', 'uploadHType', '前台允许上传附件类型', '4', 0x6A70677C6A7065677C6769667C7261727C7A69707C706E677C786C73);
INSERT INTO `zz_config` VALUES ('51', 'waterShow', '是否开启图片水印', '4', 0x30);
INSERT INTO `zz_config` VALUES ('52', 'waterW', '水印-宽', '4', 0x313030);
INSERT INTO `zz_config` VALUES ('53', 'waterH', '水印-高', '4', 0x313030);
INSERT INTO `zz_config` VALUES ('54', 'waterImg', '水印图片', '4', 0x2F55706C6F6164732F696D616765732F70632F61727469636C652F32303135313132352F3136343834335F333934372E706E67);
INSERT INTO `zz_config` VALUES ('55', 'waterTran', '水印透明度', '4', 0x3830);
INSERT INTO `zz_config` VALUES ('56', 'waterPos', '水印位置', '4', 0x39);
INSERT INTO `zz_config` VALUES ('57', 'smsUser', '购买亿美短信后获得的短信接口帐号', '5', 0x3653444B2D454D592D363638382D4B4B564D54);
INSERT INTO `zz_config` VALUES ('58', 'smsPass', '购买亿美短信后获得的短信接口密码', '5', 0x363237333836);
INSERT INTO `zz_config` VALUES ('59', 'reg_service', '注册协议', '6', 0x3C70207374796C653D22746578742D616C69676E3A63656E746572223E3C7374726F6E673EE6B3A8E5868CE58D8FE8AEAE3C2F7374726F6E673E3C2F703E0D0A0D0A3C703EE38080E38080E6ACA2E8BF8EE99885E8AFBBE7BD91E7AB99EFBC88E4BBA5E4B88BE7AE80E7A7B0266C6471756F3BE69CACE7BD91E7AB9926726471756F3BEFBC89E69C8DE58AA1E69DA1E6ACBEEFBC8CE585B6E99890E8BFB0E4B98BE58685E5AEB9E5928CE69DA1E4BBB6E98082E794A8E4BA8EE682A8E4BDBFE794A8E69CACE7BD91E7AB99E68F90E4BE9BE79A84E59084E9A1B9E69C8DE58AA1E380823C6272202F3E0D0AE38080E380803C7374726F6E673EE4B880E38081E69C8DE58AA1E69DA1E6ACBEE79A84E7A1AEE8AEA43C2F7374726F6E673E3C6272202F3E0D0AE38080E38080E682A8E782B9E587BBE69C8DE58AA1E69DA1E6ACBEE9A1B5E99DA2E4B88BE79A84266C6471756F3BE68891E5908CE6848F26726471756F3BE68C89E992AEEFBC8CE58DB3E8A786E4B8BAE682A8E5B7B2E99885E8AFBBE38081E4BA86E8A7A3E5B9B6E5AE8CE585A8E5908CE6848FE69C8DE58AA1E69DA1E6ACBEE4B8ADE79A84E59084E9A1B9E58685E5AEB9EFBC8CE58C85E68BACE69CACE7BD91E7AB99E5AFB9E69C8DE58AA1E69DA1E6ACBEE68980E4BD9CE79A84E4BBBBE4BD95E4BFAEE694B9E38082E999A4E58FA6E8A18CE6988EE7A1AEE5A3B0E6988EE5A496EFBC8CE69CACE7BD91E7AB99E4BBBBE4BD95E69C8DE58AA1E88C83E59BB4E68896E58A9FE883BDE79A84E58F98E58C96E59D87E58F97E69C8DE58AA1E69DA1E6ACBEE7BAA6E69D9FE380823C6272202F3E0D0AE380803C7374726F6E673EE38080E4BA8CE3808120E69C8DE58AA1E69DA1E6ACBEE79A84E4BFAEE694B93C2F7374726F6E673E3C6272202F3E0D0AE38080E38080E69CACE7BD91E7AB99E59CA8E5BF85E8A681E697B6E58FAFE4BFAEE694B9E69C8DE58AA1E69DA1E6ACBEEFBC8CE5B9B6E59CA8E7BD91E7AB99E8BF9BE8A18CE585ACE5918AEFBC8CE4B880E7BB8FE585ACE5918AEFBC8CE7AB8BE58DB3E7949FE69588E38082E5A682E682A8E7BBA7E7BBADE4BDBFE794A8E69C8DE58AA1EFBC8CE58899E8A786E4B8BAE682A8E5B7B2E68EA5E58F97E4BFAEE8AEA2E79A84E69C8DE58AA1E69DA1E6ACBEE380823C6272202F3E0D0A3C7374726F6E673EE380803C7374726F6E673EE380803C2F7374726F6E673EE4B889E3808120E794A8E688B7E6B3A8E5868C3C2F7374726F6E673E3C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673EE88083E89991E588B0E69CACE7BD91E7AB99E794A8E688B7E69C8DE58AA1E79A84E9878DE8A681E680A7EFBC8CE682A8E5908CE6848FE59CA8E6B3A8E5868CE697B6E68F90E4BE9BE79C9FE5AE9EE38081E5AE8CE695B4E58F8AE58786E7A1AEE79A84E4B8AAE4BABAE8B584E69699EFBC8CE5B9B6E58F8AE697B6E69BB4E696B0E38082E5A682E682A8E68F90E4BE9BE79A84E8B584E69699E4B88DE58786E7A1AEEFBC8CE68896E69CACE7BD91E7AB99E69C89E59088E79086E79A84E79086E794B1E8AEA4E4B8BAE8AFA5E8B584E69699E4B88DE79C9FE5AE9EE38081E4B88DE5AE8CE695B4E38081E4B88DE58786E7A1AEEFBC8CE69CACE7BD91E7AB99E69C89E69D83E69A82E5819CE68896E7BB88E6ADA2E682A8E79A84E6B3A8E5868CE8BAABE4BBBDE58F8AE8B584E69699EFBC8CE5B9B6E68B92E7BB9DE682A8E4BDBFE794A8E69CACE7BD91E7AB99E79A84E69C8DE58AA1E380823C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673E3C7374726F6E673EE59B9BE3808120E794A8E688B7E8B584E69699E58F8AE4BF9DE5AF863C2F7374726F6E673E3C6272202F3E0D0AE38080E38080E6B3A8E5868CE697B6EFBC8CE8AFB7E682A8E98089E68BA9E5A1ABE58699E794A8E688B7E5908DE5928CE5AF86E7A081EFBC8CE5B9B6E68C89E9A1B5E99DA2E68F90E7A4BAE68F90E4BAA4E79BB8E585B3E4BFA1E681AFE38082E682A8E8B49FE69C89E5AFB9E794A8E688B7E5908DE5928CE5AF86E7A081E4BF9DE5AF86E79A84E4B989E58AA1EFBC8CE5B9B6E5AFB9E8AFA5E794A8E688B7E5908DE5928CE5AF86E7A081E4B88BE58F91E7949FE79A84E68980E69C89E6B4BBE58AA8E689BFE68B85E8B4A3E4BBBBE38082E682A8E5908CE6848FE982AEE4BBB6E69C8DE58AA1E5928CE6898BE69CBAE79FADE4BFA1E79A84E69C8DE58AA1E79A84E4BDBFE794A8E794B1E682A8E887AAE5B7B1E689BFE68B85E9A38EE999A9E38082E69CACE7BD91E7AB99E4B88DE4BC9AE59091E682A8E68980E4BDBFE794A8E69C8DE58AA1E68980E6B689E58F8AE79BB8E585B3E696B9E4B98BE5A496E79A84E585B6E4BB96E696B9E585ACE5BC80E68896E9808FE99CB2E682A8E79A84E4B8AAE4BABAE8B584E69699EFBC8CE999A4E99D9EE6B395E5BE8BE8A784E5AE9AE380823C6272202F3E0D0AE38080E380803C7374726F6E673E20E4BA94E3808120E8B4A3E4BBBBE79A84E5858DE999A4E5928CE99990E588B63C2F7374726F6E673E3C6272202F3E0D0AE38080E38080EFBC8831EFBC89E98187E4BBA5E4B88BE68385E586B5EFBC8CE69CACE7BD91E7AB99E4B88DE689BFE68B85E4BBBBE4BD95E8B4A3E4BBBBEFBC8CE58C85E68BACE4BD86E4B88DE4BB85E99990E4BA8EEFBC9A3C6272202F3E0D0AE291A0E59BA0E4B88DE58FAFE68A97E58A9BE38081E7B3BBE7BB9FE69585E99A9CE38081E9809AE8AEAFE69585E99A9CE38081E7BD91E7BB9CE68BA5E5A0B5E38081E4BE9BE794B5E7B3BBE7BB9FE69585E99A9CE38081E681B6E6848FE694BBE587BBE7AD89E980A0E68890E69CACE7BD91E7AB99E69CAAE883BDE58F8AE697B6E38081E58786E7A1AEE38081E5AE8CE695B4E59CB0E68F90E4BE9BE69C8DE58AA1E380823C6272202F3E0D0AE291A1E697A0E8AEBAE59CA8E4BBBBE4BD95E58E9FE59BA0E4B88BEFBC8CE682A8E9809AE8BF87E4BDBFE794A8E69CACE7BD91E7AB99E4B88AE79A84E4BFA1E681AFE68896E794B1E69CACE7BD91E7AB99E993BEE68EA5E79A84E585B6E4BB96E7BD91E7AB99E4B88AE79A84E4BFA1E681AFEFBC8CE68896E585B6E4BB96E4B88EE69CACE7BD91E7AB99E993BEE68EA5E79A84E7BD91E7AB99E4B88AE79A84E4BFA1E681AFE68980E5AFBCE887B4E79A84E4BBBBE4BD95E68D9FE5A4B1E68896E68D9FE5AEB3E380823C6272202F3E0D0AEFBC8832EFBC89E69CACE7BD91E7AB99E8B49FE8B4A3E5AFB9E69CACE7BD91E7AB99E4B88AE79A84E4BFA1E681AFE8BF9BE8A18CE5AEA1E6A0B8E4B88EE69BB4E696B0EFBC8CE4BD86E5B9B6E4B88DE5B0B1E4BFA1E681AFE79A84E697B6E69588E680A7E38081E58786E7A1AEE680A7E4BBA5E58F8AE69C8DE58AA1E58A9FE883BDE79A84E5AE8CE695B4E680A7E5928CE58FAFE99DA0E680A7E689BFE68B85E4BBBBE4BD95E4B989E58AA1E5928CE8B594E581BFE8B4A3E4BBBBE380823C6272202F3E0D0AE38080E38080EFBC8833EFBC89E5A682E794A8E688B7E588A9E794A8E7B3BBE7BB9FE5B7AEE99499E38081E69585E99A9CE68896E585B6E4BB96E58E9FE59BA0E5AFBCE887B4E79A84E6BC8FE6B49EEFBC8CE68D9FE5AEB3E69CACE7BD91E7AB99E58F8AE4BBBBE4BD95E7ACACE4B889E696B9E79A84E69D83E79B8AEFBC8CE69CACE7BD91E7AB99E5B086E7BB88E6ADA2E8AFA5E794A8E688B7E8B584E6A0BCEFBC8CE5B9B6E4BF9DE79599E6B395E5BE8BE8BFBDE7A9B6E79A84E69D83E588A9E380823C6272202F3E0D0AE38080E38080EFBC8834EFBC89E5A682E69E9CE69CACE7BD91E7AB99E58F91E78EB0E69C89E5BDB1E5938DE794A8E688B7E4BFA1E681AFE5AE89E585A8E79A84E8A18CE4B8BAEFBC8CE69CACE7BD91E7AB99E69C89E69D83E5AFB9E794A8E688B7E4BFA1E681AFE5AE9EE696BDE4BF9DE68AA4E38082E5BF85E8A681E697B6E794A8E688B7E99C80E7BB8FE9878DE696B0E9AA8CE8AF81E8BAABE4BBBDE5908EE696B9E58FAFE7BBA7E7BBADE4BDBFE794A8E380823C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673E3C7374726F6E673EE585ADE38081E68B92E7BB9DE68F90E4BE9BE68B85E4BF9D3C2F7374726F6E673E3C6272202F3E0D0AE38080E38080E69CACE7BD91E7AB99E5AFB9E4BBA5E4B88BE68385E5BDA2E4B88DE5819AE4BBBBE4BD95E68B85E4BF9DEFBC8CE58C85E68BACE4BD86E4B88DE4BB85E99990E4BA8EEFBC9A3C6272202F3E0D0AE38080E38080EFBC8831EFBC89E69C8DE58AA1E4B880E5AE9AE883BDE6BBA1E8B6B3E682A8E79A84E8A681E6B182E380823C6272202F3E0D0AE38080E38080EFBC8832EFBC89E69C8DE58AA1E4B88DE4BC9AE58F97E4B8ADE696ADE380823C6272202F3E0D0AE38080E38080EFBC8833EFBC89E69C8DE58AA1E79A84E5AE89E585A8E680A7E38081E58F8AE697B6E680A7E38081E5AE8CE695B4E680A7E5928CE58786E7A1AEE680A7E380823C6272202F3E0D0AE38080E38080EFBC8834EFBC89E69C8DE58AA1E68980E6B689E58F8AE79BB8E585B3E696B9E79A84E69C8DE58AA1E380823C6272202F3E0D0AE38080E38080EFBC8835EFBC89E682A8E4BB8EE69CACE7BD91E7AB99E694B6E588B0E58FA3E5A4B4E68896E4B9A6E99DA2E79A84E6848FE8A781E68896E4BFA1E681AFE380823C6272202F3E0D0AE38080E38080EFBC8836EFBC89E682A8E68980E68F90E4BE9BE79A84E8BAABE4BBBDE4BFA1E681AFE4B880E5AE9AE8A2ABE6B3A8E5868CE380823C6272202F3E0D0AE38080E38080EFBC8837EFBC89E4BB96E4BABAE4BDBFE794A8E4B88EE682A8E79BB8E5908CE79A84E8BAABE4BBBDE4BFA1E681AFE6B3A8E5868CE380823C6272202F3E0D0A3C7374726F6E673EE380803C7374726F6E673EE380803C2F7374726F6E673EE4B883E38081E7BD91E7AB99E993BEE68EA53C2F7374726F6E673E3C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673EE69CACE7BD91E7AB99E58C85E590ABE69C89E993BEE68EA5E79A84E7ACACE4B889E696B9E7BD91E7AB99E38082E8BF99E4BA9BE993BEE68EA5E7BAAFE7B2B9E4B8BAE794A8E688B7E68F90E4BE9BE696B9E4BEBFE38082E69CACE7BD91E7AB99E697A0E6B395E5B0B1E68980E993BEE68EA5E79A84E7ACACE4B889E696B9E7BD91E7AB99E79A84E58685E5AEB9E68896E58FAFE794A8E680A7E4BA88E4BBA5E68EA7E588B6E68896E5AFB9E585B6E8B49FE8B4A3E38082E5A682E69E9CE682A8E586B3E5AE9AE8AEBFE997AEE4BBBBE4BD95E4B88EE69CACE7BD91E7AB99E79BB8E993BEE68EA5E79A84E7ACACE4B889E696B9E7BD91E7AB99EFBC8CE58899E5BA94E5AE8CE585A8E887AAE8A18CE689BFE68B85E79BB8E5BA94E9A38EE999A9E5928CE8B4A3E4BBBBE380823C6272202F3E0D0A3C7374726F6E673EE380803C7374726F6E673EE380803C2F7374726F6E673EE585ABE38081E4BF9DE99A9C3C2F7374726F6E673E3C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673EE682A8E5908CE6848FE4BF9DE99A9CE5928CE7BBB4E68AA4E69CACE7BD91E7AB99E79A84E588A9E79B8AEFBC8CE5B9B6E689BFE68B85E682A8E68896E585B6E4BB96E4BABAE4BDBFE794A8E682A8E79A84E794A8E688B7E8B584E69699E980A0E68890E69CACE7BD91E7AB99E68896E4BBBBE4BD95E7ACACE4B889E696B9E79A84E68D9FE5A4B1E68896E68D9FE5AEB3E79A84E8B594E581BFE8B4A3E4BBBBE380823C6272202F3E0D0A3C7374726F6E673EE380803C7374726F6E673EE380803C2F7374726F6E673EE4B99DE38081E79FA5E8AF86E4BAA7E69D833C2F7374726F6E673E3C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673EE69CACE7BD91E7AB99E68980E69C89E58685E5AEB9E5928CE8B584E6BA90E79A84E78988E69D83E5BD92E69CACE7BD91E7AB99E68980E69C89EFBC88E999A4E99D9EE69CACE7BD91E7AB99E5B7B2E7BB8FE6A087E6988EE78988E69D83E68980E69C89E4BABAEFBC89EFBC8CE9A1B5E99DA2E68980E69C89E4BFA1E681AFE58F97E3808AE4B8ADE58D8EE4BABAE6B091E585B1E5928CE59BBDE89197E4BD9CE69D83E6B395E3808BE58F8AE79BB8E585B3E6B395E5BE8BE6B395E8A784E5928CE4B8ADE59BBDE58AA0E585A5E79A84E68980E69C89E79FA5E8AF86E4BAA7E69D83E696B9E99DA2E79A84E59BBDE99985E69DA1E7BAA6E79A84E4BF9DE68AA4E38082E69CAAE7BB8FE69CACE7BD91E7AB99E4BA8BE58588E79A84E4B9A6E99DA2E8AEB8E58FAFEFBC8CE4BBBBE4BD95E58D95E4BD8DE5928CE4B8AAE4BABAE4B88DE5BE97E5B0B1E69CACE7BD91E7AB99E4B88AE79A84E79BB8E585B3E8B584E6BA90E4BBA5E4BBBBE4BD95E696B9E5BC8FE38081E4BBBBE4BD95E69687E5AD97E5819AE585A8E983A8E68896E5B180E983A8E5A48DE588B6E38081E4BFAEE694B9E38081E58F91E98081E38081E582A8E5AD98E38081E58F91E5B883E38081E4BAA4E6B581E68896E58886E58F91EFBC8CE68896E588A9E794A8E69CACE7BD91E7AB99E4B88AE79A84E79BB8E585B3E8B584E6BA90E5889BE5BBBAE585B6E4BB96E59586E4B89AE794A8E98094E79A84E8B584E6BA90E38082E590A6E58899E69CACE7BD91E7AB99E5B086E8BFBDE7A9B6E585B6E6B395E5BE8BE8B4A3E4BBBBE380823C6272202F3E0D0A3C7374726F6E673EE380803C7374726F6E673EE380803C2F7374726F6E673EE58D81E38081E6B395E5BE8BE98082E794A8E5928CE7AEA1E8BE963C2F7374726F6E673E3C6272202F3E0D0AE380803C7374726F6E673EE380803C2F7374726F6E673EE69CACE69C8DE58AA1E69DA1E6ACBEE4B98BE69588E58A9BE38081E8A7A3E9878AE38081E58F98E69BB4E38081E689A7E8A18CE4B88EE4BA89E8AEAEE8A7A3E586B3E59D87E98082E794A8E4B8ADE58D8EE4BABAE6B091E585B1E5928CE59BBDE6B395E5BE8BE38082E59BA0E682A8E4BDBFE794A8E69CACE7BD91E7AB99E8808CE5AFBCE887B4E79A84E4BA89E8AEAEEFBC8CE682A8E5908CE6848FE68EA5E58F97E69CACE7BD91E7AB99E6B3A8E5868CE59CB0E4BABAE6B091E6B395E999A2E79A84E7AEA1E8BE96E3808220E69CACE7BD91E7AB99E4BAABE69C89E5AFB9E69C8DE58AA1E69DA1E6ACBEE79A84E69C80E7BB88E8A7A3E9878AE4B88EE4BFAEE694B9E69D83E380823C2F703E);
INSERT INTO `zz_config` VALUES ('61', 'help', '帮助中心', '6', 0x3C703E64736664736B6667686B6B6B6A3C2F703E);
INSERT INTO `zz_config` VALUES ('62', 'software_share_title', '蔬果先生软件分享标题', '7', 0xE68891E59CA8E894ACE69E9CE58588E7949FE6898BE69CBA415050E7ABAFE7AD89E4BDA0E593A6);
INSERT INTO `zz_config` VALUES ('63', 'software_share_content', '蔬果先生软件分享文案', '7', 0xE5BD93E5ADA3E783ADE99480E6B0B4E69E9CE38081E8BF9BE58FA3E6B0B4E69E9CE38081E9B29CE58887E6B0B4E69E9CE894ACE88F9CE38081E4BBA4E4BABAE6BF80E58AA8E79A84E7949FE9B29CE4BAA7E59381E4B99FE58DB3E5B086E4B88AE7BABFE592AFEFBC81);
INSERT INTO `zz_config` VALUES ('64', 'software_share_image', '蔬果先生软件分享图片', '7', 0x2F55706C6F6164732F696D616765732F70632F32303136303230312F3230343830375F323630362E706E67);
INSERT INTO `zz_config` VALUES ('65', 'software_share_link', '蔬果先生软件分享链接', '7', 0x687474703A2F2F66727569742E63636A6A6A2E6E65742F696E6465782E7068702F5765622F);
INSERT INTO `zz_config` VALUES ('108', 'jpush_run_appkey', '极光推送配送端appkey', '5', 0x383364653162343938633161346366663738633631653366);
INSERT INTO `zz_config` VALUES ('109', 'jpush_run_masterSecret', '极光推送配送端secret', '5', 0x313165643035663433373031373365306332646438366232);
INSERT INTO `zz_config` VALUES ('70', 'waterText', '水印文字', '4', 0xE894ACE69E9CE58588E7949F);
INSERT INTO `zz_config` VALUES ('71', 'waterColor', '水印文字颜色', '4', 0x23666666663030);
INSERT INTO `zz_config` VALUES ('72', 'waterFontsize', '水印文字大小', '4', 0x3138);
INSERT INTO `zz_config` VALUES ('73', 'thumbShow', '是否开启缩略图', '4', 0x30);
INSERT INTO `zz_config` VALUES ('74', 'thumbW', '缩略图-宽', '4', 0x323030);
INSERT INTO `zz_config` VALUES ('75', 'thumbH', '缩略图-高', '4', 0x323030);
INSERT INTO `zz_config` VALUES ('76', 'thumbType', '缩略图类型', '4', 0x31);
INSERT INTO `zz_config` VALUES ('77', 'rongyunUser', '融云账号', '5', 0x70776538366761356533687436);
INSERT INTO `zz_config` VALUES ('78', 'rongyunPass', '融云密码', '5', 0x4630467134754D625744);
INSERT INTO `zz_config` VALUES ('79', 'pingAppid', 'Ping++APPID', '5', 0x6170705F797262767648766E5034573171316548);
INSERT INTO `zz_config` VALUES ('80', 'pingKey', 'Ping++KEY', '5', 0x736B5F6C6976655F38534F434B4F546139346D4C5447387162356666396D7A35);
INSERT INTO `zz_config` VALUES ('81', 'baidumap_key', '百度地图密钥', '5', 0x3936643234366432613235613464636566653062323231373464303266336437);
INSERT INTO `zz_config` VALUES ('82', 'gaodemap_key', '高德地图密钥', '5', '');
INSERT INTO `zz_config` VALUES ('83', 'about_us', '关于蔬果先生', '6', 0x3C703E3C7370616E207374796C653D22666F6E742D73697A653A31347078223E3C7370616E207374796C653D22666F6E742D66616D696C793AE5BEAEE8BDAFE99B85E9BB91223EE894ACE69E9CE58588E7949FE698AFE4B880E5AEB6E7949FE9B29CE9858DE98081E588B0E5AEB6E79A84E4BC81E4B89AEFBC8CE4B8BBE890A5EFBC9AE69E9CE59381E38081E894ACE88F9CE38081E9A284E58C85E8A385E9A39FE59381E38081E695A3E8A385E9A39FE59381E38081E5869CE589AFE4BAA7E59381E38082E585ACE58FB832303135E5B9B431E69C8835E697A5E68890E7AB8BE588B0E78EB0E59CA8E59FB9E585BBE4BA86E695B0E4B887E4B8AAE5BFA0E5AE9EE7B289E4B89DEFBC8CE68BA5E69C89E887AAE5B7B1E79A84E9858DE98081E59BA2E9989FE38081E586B7E993BEE4BB93E582A8E38081E5AEA2E688B7E7ABAFE4B88EE9858DE98081E7ABAFE79A84415050EFBC8CE79BAEE5898DE5B7B2E5BBBAE7AB8BE887AAE5B7B1E79A84E997BDE6B996E88AA6E69F91E59FBAE59CB0EFBC9B32303135E5B9B4E8AF95E99480E594AEE99D9EE5B8B8E68890E58A9FEFBC8CE79BAEE5898DE58FA4E794B0E6B0B4E89C9CE6A183E59FBAE59CB0E38081E7A68FE5AE89E891A1E89084E59FBAE59CB0E6ADA3E59CA8E7ADB9E5A487E4B8ADEFBC8C3C2F7370616E3E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793AE5BEAEE8BDAFE99B85E9BB913B20666F6E742D73697A653A31347078223EE585ACE58FB8E7A789E689BF266C6471756F3BE58FAAE4B8BAE69BB4E4BC98E8B4A8E79A84E7949FE9B29CE894ACE69E9C26726471756F3BE79A84E69C8DE58AA1E79086E5BFB5EFBC8CE4BBA5E9878DE59381E8B4A8E38081E8AEB2E8AF9AE4BFA1E79A84E7BB8FE890A5E79086E5BFB5E5BE97E588B0E794A8E688B7E79A84E5B9BFE6B39BE8AEA4E58FAFEFBC8CE5B9B6E5BE97E588B0E8BF85E9809FE58F91E5B195EFBC9BE894ACE69E9CE58588E7949FE5BF97E59CA8E68890E4B8BAE7A68FE5BBBAE79C81E9A696E98089E79A84E7949FE9B29C303230E4BC81E4B89AEFBC8CE585ACE58FB8E587ADE5809FE4B880E6B581E8BF90E4BD9CE4BD93E7B3BBE5928CE4B88DE696ADE5AE8CE59684E79A84E7949FE9B29CE59FBAE59CB0EFBC8CE7ABADE8AF9AE4B8BAE5B9BFE5A4A7E794A8E688B7E68F90E4BE9BE5AE89E585A8E38081E4BEBFE68DB7E38081E4B893E4B89AE79A84E7949FE9B29CE69C8DE58AA1E380823C2F7370616E3E3C2F703E);
INSERT INTO `zz_config` VALUES ('90', 'phone_order_done_success', '成功下单', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E59CA87B2364617465237DE68890E58A9FE68F90E4BAA4E4BA86E4B880E7AC942CE8AEA2E58D95E98791E9A29DE585B1E8AEA17B236D6F6E6579237DE58583E38082);
INSERT INTO `zz_config` VALUES ('91', 'phone_order_pay_success', '成功支付订单', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E59CA87B2364617465237DE68890E58A9FE694AFE4BB98E4BA86E4B880E7AC942CE8AEA2E58D95E98791E9A29DE585B1E8AEA17B236D6F6E6579237DE58583E38082);
INSERT INTO `zz_config` VALUES ('92', 'phone_reg_apply_success', '注册审核成功', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E794B3E8AFB7E68890E4B8BA7B236D656D626572726F6C65237DEFBC8CE7AEA1E79086E59198E59CA87B2364617465237DE5AEA1E6A0B8E68890E58A9FE4BA86E38082);
INSERT INTO `zz_config` VALUES ('93', 'phone_reg_apply_error', '注册审核失败', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E794B3E8AFB7E68890E4B8BA7B236D656D626572726F6C65237DEFBC8CE7AEA1E79086E59198E59CA87B2364617465237DE5AEA1E6A0B8E5A4B1E8B4A5E4BA86E38082);
INSERT INTO `zz_config` VALUES ('94', 'phone_product_add_success', '产品发布审核成功', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E58F91E5B883E79A84E4BAA7E593817B2370726F64756374237DEFBC8CE59CA87B2364617465237DE5AEA1E6A0B8E68890E58A9FE4BA86E38082);
INSERT INTO `zz_config` VALUES ('95', 'phone_product_add_error', '产品发布审核失败', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E58F91E5B883E79A84E4BAA7E593817B2370726F64756374237DEFBC8CE59CA87B2364617465237DE5AEA1E6A0B8E5A4B1E8B4A5E4BA86E38082);
INSERT INTO `zz_config` VALUES ('96', 'phone_store_add_success', '店铺发布审核成功', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E58F91E5B883E79A84E5BA97E993BA7B2373746F7265237DEFBC8CE59CA87B2364617465237DE5AEA1E6A0B8E68890E58A9FE4BA86E38082);
INSERT INTO `zz_config` VALUES ('97', 'phone_store_add_error', '店铺发布审核失败', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E58F91E5B883E79A84E5BA97E993BA7B2373746F7265237DEFBC8CE59CA87B2364617465237DE5AEA1E6A0B8E5A4B1E8B4A5E4BA86E38082);
INSERT INTO `zz_config` VALUES ('98', 'phone_upgrade_success', '会员升级', '3', 0xE5B08AE695ACE79A847B23757365726E616D65237DEFBC8CE682A8E5A5BDEFBC81E682A8E5B7B2E7BB8FE58D87E7BAA7E4BA86E593A6EFBC81);
INSERT INTO `zz_config` VALUES ('99', 'mes_attestation', '会员认证', '3', 0xE682A8E5A5BDEFBC8CE681ADE5969CE682A8E8AEA4E8AF81E68890E58A9F);
INSERT INTO `zz_config` VALUES ('100', 'jpush_member_appkey', '极光推送用户端appkey', '5', 0x396533353637653964626362653534353265353964333262);
INSERT INTO `zz_config` VALUES ('101', 'jpush_member_masterSecret', '极光推送用户端secret', '5', 0x313031376361643861383165626235376262363162633339);
INSERT INTO `zz_config` VALUES ('102', 'ccpest_accountSid', '云通讯主帐号', '5', 0x3861343862353531353061633730666430313530616439343136336530373334);
INSERT INTO `zz_config` VALUES ('103', 'ccpest_accountToken', '云通讯主帐号Token', '5', 0x3638316463376238326531653438366361323330303135303135613532373336);
INSERT INTO `zz_config` VALUES ('104', 'ccpest_appId', '云通讯应用Id', '5', 0x3861343862353531353061633730666430313530616439633565316530373563);
INSERT INTO `zz_config` VALUES ('105', 'ccpest_serverPort', '云通讯请求端口', '5', 0x38383833);
INSERT INTO `zz_config` VALUES ('106', 'ccpest_serverIP', '云通讯请求地址', '5', 0x6170702E636C6F6F70656E2E636F6D);
INSERT INTO `zz_config` VALUES ('107', 'ccpest_tempId', '云通讯模板Id', '5', 0x3438363336);
INSERT INTO `zz_config` VALUES ('110', 'email_joincompany_subject', '用户申请加入企业主题', '3', 0xE794A8E688B7E794B3E8AFB7E58AA0E585A5E4BC81E4B89A);
INSERT INTO `zz_config` VALUES ('111', 'email_joincompany', '用户申请加入企业', '3', 0x3C703EE5B08AE695ACE79A847B23636F6D70616E79237DE7AEA1E79086E59198E682A8E5A5BDEFBC813C6272202F3E0D0AE38080E38080E894ACE69E9CE58588E7949FE794A8E688B77B23757365726E616D65237DE794B3E8AFB7E58AA0E585A5E682A8E79A84E4BC81E4B89AEFBC8CE682A8E79A84E4BC81E4B89AE7BC96E58FB7E4B8BA7B23636F6D70616E796E756D626572237DEFBC8CE8AFB7E682A8E5B0BDE5BFABE799BBE5BD95E4BC81E4B89AE5908EE58FB0E5AEA1E6A0B8EFBC8CE8B0A2E8B0A2EFBC813C2F703E0D0A0D0A3C703E266E6273703B20266E6273703BE799BBE5BD95E993BEE68EA5EFBC9A687474703A2F2F66727569742E63636A6A6A2E6E65742F696E6465782E7068702F41646D696E2F5075626C69632F6C6F67696E2E68746D6C3C2F703E0D0A0D0A3C646976207374796C653D22746578742D616C69676E3A7269676874223EE894ACE69E9CE58588E7949F266E6273703BE695ACE590AF3C2F6469763E);
INSERT INTO `zz_config` VALUES ('112', 'integralset', '充值送积分设置', '1', 0x32);

-- ----------------------------
-- Table structure for zz_cooperation
-- ----------------------------
DROP TABLE IF EXISTS `zz_cooperation`;
CREATE TABLE `zz_cooperation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '申请人',
  `company` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '公司名称',
  `companynumber` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '企业编号',
  `username` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '联系人姓名',
  `email` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `tel` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '联系方式',
  `content` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '合作意向',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='企业合作表';

-- ----------------------------
-- Records of zz_cooperation
-- ----------------------------

-- ----------------------------
-- Table structure for zz_coupons
-- ----------------------------
DROP TABLE IF EXISTS `zz_coupons`;
CREATE TABLE `zz_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) NOT NULL COMMENT '商铺ID',
  `pid` int(11) NOT NULL,
  `catid` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '代金券名称',
  `thumb` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '代金券缩略图',
  `price` decimal(50,2) NOT NULL COMMENT '价值',
  `range` decimal(50,2) NOT NULL COMMENT '适用范围',
  `validity_starttime` int(11) NOT NULL COMMENT '有效期开始时间',
  `validity_endtime` int(11) NOT NULL COMMENT '有效期结束时间',
  `num` int(11) NOT NULL COMMENT '数量',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0默认  1适用于某个店铺  2适用于某件商品 3适用于某类产品',
  `status` tinyint(1) NOT NULL COMMENT '审核状态 1成功  0失败',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '使用规则',
  `inputtime` int(11) NOT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `listorder` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='优惠券类型表';

-- ----------------------------
-- Records of zz_coupons
-- ----------------------------

-- ----------------------------
-- Table structure for zz_coupons_order
-- ----------------------------
DROP TABLE IF EXISTS `zz_coupons_order`;
CREATE TABLE `zz_coupons_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL COMMENT '优惠券类型id',
  `uid` int(11) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否使用 1是 0否',
  `inputtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='优惠券订单表';

-- ----------------------------
-- Records of zz_coupons_order
-- ----------------------------

-- ----------------------------
-- Table structure for zz_debug
-- ----------------------------
DROP TABLE IF EXISTS `zz_debug`;
CREATE TABLE `zz_debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '请求控制器',
  `action` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '请求方法',
  `get` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '请求路径',
  `post` varchar(1024) CHARACTER SET utf8 DEFAULT NULL COMMENT '请求参数',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='调试表';

-- ----------------------------
-- Records of zz_debug
-- ----------------------------
INSERT INTO `zz_debug` VALUES ('1', 'AutoDb', 'backupbackground', null, null, '1460645958');

-- ----------------------------
-- Table structure for zz_emaillog
-- ----------------------------
DROP TABLE IF EXISTS `zz_emaillog`;
CREATE TABLE `zz_emaillog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `s_id` int(11) DEFAULT '0' COMMENT '发送用户id，0代表管理员',
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '接收用户id',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1已读',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='邮件记录表';

-- ----------------------------
-- Records of zz_emaillog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_evaluation
-- ----------------------------
DROP TABLE IF EXISTS `zz_evaluation`;
CREATE TABLE `zz_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '类型 shop：商户  product 商品 order 订单',
  `uid` int(11) DEFAULT NULL COMMENT '点赞用户UID',
  `value` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '关联id',
  `speed` int(11) DEFAULT '0' COMMENT '配送速度',
  `attitude` int(11) DEFAULT '0' COMMENT '服务态度',
  `total` int(11) DEFAULT '0' COMMENT '订单评价',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '评价内容',
  `reply` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '评价回复',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '评价图片',
  `replytime` int(11) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '回复状态 0未回复  1已回复',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='评价记录表';

-- ----------------------------
-- Records of zz_evaluation
-- ----------------------------

-- ----------------------------
-- Table structure for zz_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zz_feedback`;
CREATE TABLE `zz_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容',
  `inputtime` int(11) DEFAULT NULL,
  `status` int(5) DEFAULT '1' COMMENT '状态 ',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='意见和建议表';

-- ----------------------------
-- Records of zz_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for zz_feedback_dolog
-- ----------------------------
DROP TABLE IF EXISTS `zz_feedback_dolog`;
CREATE TABLE `zz_feedback_dolog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `varname` varchar(255) DEFAULT NULL COMMENT '订单反馈  order  /用户反馈 member',
  `value` varchar(255) DEFAULT NULL COMMENT '订单号/用户id',
  `loginfo` varchar(255) DEFAULT NULL COMMENT '内容',
  `status` tinyint(4) DEFAULT NULL COMMENT '1 成功  2失败',
  `username` varchar(255) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单售后处理记录';

-- ----------------------------
-- Records of zz_feedback_dolog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_hit
-- ----------------------------
DROP TABLE IF EXISTS `zz_hit`;
CREATE TABLE `zz_hit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '类型 thumb：图片 product 商品',
  `uid` int(11) DEFAULT NULL COMMENT '点赞用户UID',
  `value` int(11) DEFAULT NULL COMMENT '照片ID',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='点赞记录表';

-- ----------------------------
-- Records of zz_hit
-- ----------------------------

-- ----------------------------
-- Table structure for zz_integral
-- ----------------------------
DROP TABLE IF EXISTS `zz_integral`;
CREATE TABLE `zz_integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `useintegral` int(11) DEFAULT '0' COMMENT '可用积分',
  `giveintegral` int(11) DEFAULT '0',
  `payed` int(11) DEFAULT '0' COMMENT '已花费积分',
  `totalintegral` int(11) DEFAULT '0' COMMENT '累计积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='积分表';

-- ----------------------------
-- Records of zz_integral
-- ----------------------------

-- ----------------------------
-- Table structure for zz_integrallog
-- ----------------------------
DROP TABLE IF EXISTS `zz_integrallog`;
CREATE TABLE `zz_integrallog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `paytype` tinyint(1) NOT NULL COMMENT '0为增加  1为减少',
  `content` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '内容',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '影响值',
  `useintegral` int(11) NOT NULL DEFAULT '0' COMMENT '可用值',
  `totalintegral` int(11) NOT NULL DEFAULT '0' COMMENT '累计值',
  `varname` varchar(255) COLLATE utf8_bin NOT NULL,
  `inputtime` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='积分使用记录表';

-- ----------------------------
-- Records of zz_integrallog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_invite
-- ----------------------------
DROP TABLE IF EXISTS `zz_invite`;
CREATE TABLE `zz_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `tuid` int(11) NOT NULL COMMENT '被邀请用户id',
  `tuijiancode` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '邀请码',
  `status` int(5) DEFAULT '1' COMMENT '邀请状态 1邀请中 2邀请成功 3邀请失败',
  `inputtime` int(11) DEFAULT NULL COMMENT '邀请时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='邀请推荐表';

-- ----------------------------
-- Records of zz_invite
-- ----------------------------

-- ----------------------------
-- Table structure for zz_link
-- ----------------------------
DROP TABLE IF EXISTS `zz_link`;
CREATE TABLE `zz_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL COMMENT '类型ID',
  `title` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `url` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '链接地址',
  `image` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'LOGO',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述',
  `username` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '用户名',
  `inputtime` int(15) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(15) DEFAULT NULL COMMENT '更新时间',
  `type` int(5) DEFAULT '1' COMMENT '类型 1图片链接 2文字链接',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核 1审核 2未审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='链接表';

-- ----------------------------
-- Records of zz_link
-- ----------------------------

-- ----------------------------
-- Table structure for zz_linkage
-- ----------------------------
DROP TABLE IF EXISTS `zz_linkage`;
CREATE TABLE `zz_linkage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) DEFAULT NULL COMMENT '类别ID',
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '名称',
  `value` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '值',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1正常 0禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='联动菜单';

-- ----------------------------
-- Records of zz_linkage
-- ----------------------------
INSERT INTO `zz_linkage` VALUES ('1', '1', '苹果', '1', '0', '1');
INSERT INTO `zz_linkage` VALUES ('2', '1', '香蕉', '2', '0', '1');

-- ----------------------------
-- Table structure for zz_linkagetype
-- ----------------------------
DROP TABLE IF EXISTS `zz_linkagetype`;
CREATE TABLE `zz_linkagetype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '类别名称',
  `listorder` int(255) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1正常 0禁用',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='菜单类型表';

-- ----------------------------
-- Records of zz_linkagetype
-- ----------------------------
INSERT INTO `zz_linkagetype` VALUES ('1', '个人偏好', '0', '1', '个人偏好');

-- ----------------------------
-- Table structure for zz_linktype
-- ----------------------------
DROP TABLE IF EXISTS `zz_linktype`;
CREATE TABLE `zz_linktype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '类型ID',
  `parentid` int(10) DEFAULT '0' COMMENT '父ID',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='链接类型表';

-- ----------------------------
-- Records of zz_linktype
-- ----------------------------

-- ----------------------------
-- Table structure for zz_log
-- ----------------------------
DROP TABLE IF EXISTS `zz_log`;
CREATE TABLE `zz_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '管理员ID',
  `time` datetime DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `info` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `application` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态,1为成功 2为失败',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='后台操作日志';

-- ----------------------------
-- Records of zz_log
-- ----------------------------
INSERT INTO `zz_log` VALUES ('1', '1', '2016-04-12 22:25:10', '127.0.0.1', '模块/控制器/方法：Admin/Expand/add<br>提示语：新增菜单成功！', 'Admin/Expand/add', '1');
INSERT INTO `zz_log` VALUES ('2', '1', '2016-04-12 22:25:40', '127.0.0.1', '模块/控制器/方法：Admin/Expand/add<br>提示语：新增菜单成功！', 'Admin/Expand/add', '1');
INSERT INTO `zz_log` VALUES ('3', '1', '2016-04-14 22:05:43', '127.0.0.1', '模块/控制器/方法：Admin/Menu/public_changyong<br>提示语：添加成功！', 'Admin/Menu/public_changyong', '1');
INSERT INTO `zz_log` VALUES ('4', '1', '2016-04-14 23:19:16', '127.0.0.1', '模块/控制器/方法：Admin/Db/optimize<br>提示语：数据表\'zz_account\'优化完成！', 'Admin/Db/optimize', '1');
INSERT INTO `zz_log` VALUES ('5', '1', '2016-04-14 23:19:16', '127.0.0.1', '模块/控制器/方法：Admin/Db/optimize<br>提示语：数据表\'statics\'优化完成！', 'Admin/Db/optimize', '1');
INSERT INTO `zz_log` VALUES ('6', '1', '2016-04-23 19:53:12', '0.0.0.0', '模块/控制器/方法：Admin/Menu/public_changyong<br>提示语：添加成功！', 'Admin/Menu/public_changyong', '1');
INSERT INTO `zz_log` VALUES ('7', '1', '2016-04-23 19:54:08', '0.0.0.0', '模块/控制器/方法：Admin/Menu/public_changyong<br>提示语：添加成功！', 'Admin/Menu/public_changyong', '1');

-- ----------------------------
-- Table structure for zz_loginlog
-- ----------------------------
DROP TABLE IF EXISTS `zz_loginlog`;
CREATE TABLE `zz_loginlog` (
  `loginid` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `username` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录帐号',
  `logintime` datetime NOT NULL COMMENT '登录时间',
  `loginip` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,1为登录成功，0为登录失败',
  `password` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '尝试错误密码',
  `info` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '其他说明',
  PRIMARY KEY (`loginid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk COMMENT='后台登陆日志表';

-- ----------------------------
-- Records of zz_loginlog
-- ----------------------------
INSERT INTO `zz_loginlog` VALUES ('1', 'admin', '2016-04-12 22:21:39', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('2', 'admin', '2016-04-14 21:08:45', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('3', 'admin', '2016-04-14 21:20:56', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('4', 'admin', '2016-04-15 22:58:57', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('5', 'admin', '2016-04-23 12:45:19', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('6', 'admin', '2016-04-23 13:26:19', '0.0.0.0', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('7', 'admin', '2016-04-23 19:30:18', '127.0.0.1', '1', '***in88***', '');
INSERT INTO `zz_loginlog` VALUES ('8', 'admin', '2016-04-23 19:36:13', '0.0.0.0', '1', '***in88***', '');

-- ----------------------------
-- Table structure for zz_member
-- ----------------------------
DROP TABLE IF EXISTS `zz_member`;
CREATE TABLE `zz_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '登录密码',
  `nickname` char(50) COLLATE utf8_bin NOT NULL COMMENT '昵称',
  `realname` char(20) COLLATE utf8_bin NOT NULL COMMENT '真实姓名',
  `idcard` char(18) COLLATE utf8_bin NOT NULL COMMENT '身份证',
  `phone` char(11) COLLATE utf8_bin NOT NULL COMMENT '手机',
  `email` char(100) COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `head` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '头像',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1男 2女 0未知',
  `birthday` char(50) COLLATE utf8_bin NOT NULL,
  `email_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱认证 1认证 0未认证',
  `phone_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机认证 1认证 0未认证',
  `realname_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '实名认证 1认证 0未认证 ',
  `group_id` int(11) NOT NULL DEFAULT '1' COMMENT '角色1 普通用户 2为配送员',
  `groupid_id` int(11) NOT NULL COMMENT '上级邀请者',
  `isleader` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是组长 1是  0否',
  `leader` int(11) NOT NULL COMMENT '组长',
  `tuijiancode` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '邀请码',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `area` varchar(50) COLLATE utf8_bin NOT NULL,
  `address` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '详细地址',
  `reg_ip` varchar(50) COLLATE utf8_bin NOT NULL,
  `reg_time` int(15) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `lastlogin_time` int(15) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `lastlogin_ip` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '最后登录IP',
  `login_num` int(15) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `verify` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '验证码',
  `workstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '工作状态 0下班 1上班 2故障',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '禁用 0禁用 1未禁用',
  `deviceToken` varchar(255) COLLATE utf8_bin NOT NULL,
  `preference` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '个人偏好',
  `companyid` int(11) NOT NULL COMMENT '企业账号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='会员表';

-- ----------------------------
-- Records of zz_member
-- ----------------------------

-- ----------------------------
-- Table structure for zz_member_info
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_info`;
CREATE TABLE `zz_member_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL COMMENT '用户ID',
  `education` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '学历',
  `school` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '毕业学校',
  `marital` int(2) DEFAULT '2' COMMENT '婚姻状况 1已婚 0未婚 2其他',
  `address` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '地址',
  `companyindustry` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '公司行业',
  `companyscale` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '公司规模',
  `position` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '职位',
  `income` char(50) COLLATE utf8_bin DEFAULT NULL COMMENT '收入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='会员信息表';

-- ----------------------------
-- Records of zz_member_info
-- ----------------------------

-- ----------------------------
-- Table structure for zz_menu
-- ----------------------------
DROP TABLE IF EXISTS `zz_menu`;
CREATE TABLE `zz_menu` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) DEFAULT '0' COMMENT '父ID',
  `arrparentid` varchar(50) DEFAULT '' COMMENT '所有父ID',
  `child` tinyint(1) DEFAULT '0' COMMENT '是否存在子栏目，1存在',
  `arrchildid` varchar(50) DEFAULT '' COMMENT '所有子栏目ID',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则标识/菜单',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1为菜单，2为权限验证，3为菜单+权限',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `listorder` mediumint(10) DEFAULT '0' COMMENT '排序',
  `ismenu` tinyint(1) DEFAULT '1' COMMENT '是否显示，1显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=411 DEFAULT CHARSET=utf8 COMMENT='用户权限规则表+后台菜单表';

-- ----------------------------
-- Records of zz_menu
-- ----------------------------
INSERT INTO `zz_menu` VALUES ('1', '0', '', '0', '', 'Admin/Config/index', '系统设置', '1', '3', '', '设置', '0', '1');
INSERT INTO `zz_menu` VALUES ('2', '1', '', '0', '', 'Admin/Config/index', '系统设置', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('3', '2', '', '0', '', 'Admin/Menu/index', '菜单设置', '1', '3', '', '菜单设置', '0', '1');
INSERT INTO `zz_menu` VALUES ('4', '3', '', '0', '', 'Admin/Menu/add', '添加菜单', '1', '3', '', '添加菜单', '0', '1');
INSERT INTO `zz_menu` VALUES ('5', '3', '', '0', '', 'Admin/Menu/edit', '修改菜单', '1', '3', '', '修改菜单', '0', '0');
INSERT INTO `zz_menu` VALUES ('6', '3', '', '0', '', 'Admin/Menu/delete', '删除菜单', '1', '3', '', '删除菜单', '0', '0');
INSERT INTO `zz_menu` VALUES ('7', '3', '', '0', '', 'Admin/Menu/listorders', '菜单排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('8', '1', '', '0', '', 'Admin/Manager/index', '管理员管理', '1', '3', '', '管理员管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('9', '2', '', '0', '', 'Admin/Config/index', '站点设置', '1', '3', '', '站点设置', '1', '1');
INSERT INTO `zz_menu` VALUES ('10', '13', '', '0', '', 'Admin/Manager/add', '添加管理', '1', '3', '', '添加管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('11', '13', '', '0', '', 'Admin/Manager/edit', '编辑管理信息', '1', '3', '', '编辑管理信息', '0', '0');
INSERT INTO `zz_menu` VALUES ('12', '13', '', '0', '', 'Admin/Manager/delete', '删除管理员', '1', '3', '', '删除管理员', '0', '0');
INSERT INTO `zz_menu` VALUES ('13', '8', '', '0', '', 'Admin/Manager/index', '管理员管理', '1', '3', '', '管理员管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('14', '8', '', '0', '', 'Admin/Role/index', '角色管理', '1', '3', '', '角色管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('15', '14', '', '0', '', 'Admin/Role/add', '添加角色', '1', '3', '', '添加角色', '0', '1');
INSERT INTO `zz_menu` VALUES ('16', '14', '', '0', '', 'Admin/Role/edit', '编辑角色', '1', '3', '', '编辑角色', '0', '0');
INSERT INTO `zz_menu` VALUES ('17', '14', '', '0', '', 'Admin/Role/delete', '角色删除', '1', '3', '', '角色删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('18', '14', '', '0', '', 'Admin/Role/auth', '权限设置', '1', '3', '', '权限设置', '0', '0');
INSERT INTO `zz_menu` VALUES ('20', '1', '', '0', '', 'Admin/Logs/index', '日志管理', '1', '3', '', '日志管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('21', '20', '', '0', '', 'Admin/Logs/index', '后台操作日志', '1', '3', '', '后台操作日志', '0', '1');
INSERT INTO `zz_menu` VALUES ('22', '20', '', '0', '', 'Admin/Logs/login', '后台登录日志', '1', '3', '', '后台登录日志', '0', '1');
INSERT INTO `zz_menu` VALUES ('23', '21', '', '0', '', 'Admin/Logs/del', '删除', '1', '2', '', '删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('24', '22', '', '0', '', 'Admin/Logs/logindel', '删除', '1', '2', '', '删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('25', '9', '', '0', '', 'Admin/Config/email', '邮箱配置', '1', '3', '', '邮箱配置', '0', '1');
INSERT INTO `zz_menu` VALUES ('26', '0', '', '0', '', 'Admin/Manager/myinfo', '我的面板', '1', '3', '', '', '7', '1');
INSERT INTO `zz_menu` VALUES ('27', '26', '', '0', '', 'Admin/Manager/myinfo', '个人信息', '1', '3', '', '个人信息', '0', '1');
INSERT INTO `zz_menu` VALUES ('28', '26', '', '0', '', 'Admin/Manager/chanpass', '修改密码', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('29', '0', '', '0', '', 'Admin/article/index', '内容管理', '1', '3', '', '内容', '1', '1');
INSERT INTO `zz_menu` VALUES ('30', '29', '', '0', '', 'Admin/article/index', '管理内容', '1', '3', '', '管理内容', '2', '1');
INSERT INTO `zz_menu` VALUES ('31', '29', '', '0', '', 'Admin/category/index', '管理栏目', '1', '3', '', '管理栏目', '0', '0');
INSERT INTO `zz_menu` VALUES ('32', '30', '', '0', '', 'Admin/article/index', '内容列表', '0', '3', '', '内容列表', '0', '0');
INSERT INTO `zz_menu` VALUES ('33', '32', '', '0', '', 'Admin/article/add', '添加内容', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('34', '32', '', '0', '', 'Admin/article/del', '批量删除内容', '1', '2', '', '删除内容', '0', '0');
INSERT INTO `zz_menu` VALUES ('35', '32', '', '0', '', 'Admin/article/listorder', '内容排序', '1', '2', '', '内容排序', '0', '0');
INSERT INTO `zz_menu` VALUES ('36', '29', '', '0', '', 'Admin/page/index', '单页管理', '1', '3', '', '单页管理', '1', '0');
INSERT INTO `zz_menu` VALUES ('37', '36', '', '0', '', 'Admin/page/edit', '修改单页内容', '1', '2', '', '修改单页内容', '0', '0');
INSERT INTO `zz_menu` VALUES ('38', '31', '', '0', '', 'Admin/category/index', '栏目列表', '1', '3', '', '栏目列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('39', '38', '', '0', '', 'Admin/category/add', '添加栏目', '1', '3', '', '添加栏目', '0', '1');
INSERT INTO `zz_menu` VALUES ('40', '38', '', '0', '', 'Admin/category/del', '删除栏目', '1', '2', '', '删除栏目', '0', '0');
INSERT INTO `zz_menu` VALUES ('41', '38', '', '0', '', 'Admin/category/listorder', '栏目排序', '1', '2', '', '栏目排序', '0', '0');
INSERT INTO `zz_menu` VALUES ('42', '38', '', '0', '', 'Admin/category/change', '栏目属性转换', '1', '2', '', '栏目属性转换', '0', '0');
INSERT INTO `zz_menu` VALUES ('43', '38', '', '0', '', 'Admin/category/addurl', ' 添加外部链接栏目', '1', '3', '', ' 添加外部链接栏目', '0', '1');
INSERT INTO `zz_menu` VALUES ('44', '32', '', '0', '', 'Admin/article/pushs', '内容推荐', '1', '2', '', '内容推荐', '0', '0');
INSERT INTO `zz_menu` VALUES ('45', '32', '', '0', '', 'Admin/article/review', '内容审核', '1', '2', '', '内容审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('46', '32', '', '0', '', 'Admin/article/unpushs', '内容取消推荐', '1', '2', '', '内容取消推荐', '0', '0');
INSERT INTO `zz_menu` VALUES ('47', '32', '', '0', '', 'Admin/article/unreview', '内容取消审核', '1', '2', '', '内容取消审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('48', '32', '', '0', '', 'Admin/article/delete', '删除内容', '1', '2', '', '删除内容', '0', '0');
INSERT INTO `zz_menu` VALUES ('49', '29', '', '0', '', 'Admin/link/index', '友情链接管理', '1', '3', '', '友情链接管理', '0', '0');
INSERT INTO `zz_menu` VALUES ('50', '49', '', '0', '', 'Admin/link/index', '链接列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('51', '49', '', '0', '', 'Admin/link/type', '链接类型', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('52', '50', '', '0', '', 'Admin/link/add', '添加链接', '1', '3', '', '添加链接', '0', '1');
INSERT INTO `zz_menu` VALUES ('53', '50', '', '0', '', 'Admin/link/del', '链接删除', '1', '2', '', '链接删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('54', '51', '', '0', '', 'Admin/link/typeadd', '添加类型', '1', '3', '', '添加类型', '0', '1');
INSERT INTO `zz_menu` VALUES ('55', '50', '', '0', '', 'Admin/link/edit', '链接修改', '1', '2', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('56', '51', '', '0', '', 'Admin/link/typeedit', '修改类型', '1', '2', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('57', '51', '', '0', '', 'Admin/link/typedel', '删除类型', '1', '2', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('58', '29', '', '0', '', 'Admin/ad/index', '广告管理', '1', '3', '', '广告管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('59', '58', '', '0', '', 'Admin/ad/index', '广告管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('60', '58', '', '0', '', 'Admin/ad/type', '广告位管理', '0', '3', '', '广告位管理', '0', '0');
INSERT INTO `zz_menu` VALUES ('64', '59', '', '0', '', 'Admin/ad/delete', '广告删除', '1', '2', '', '广告删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('62', '59', '', '0', '', 'Admin/ad/add', '添加广告', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('63', '59', '', '0', '', 'Admin/ad/edit', '广告修改', '1', '2', '', '广告修改', '0', '0');
INSERT INTO `zz_menu` VALUES ('65', '60', '', '0', '', 'Admin/ad/typeadd', '添加广告位', '1', '3', '', '添加广告位', '0', '1');
INSERT INTO `zz_menu` VALUES ('66', '60', '', '0', '', 'Admin/ad/typeedit', '修改广告位', '1', '2', '', '修改广告位', '0', '0');
INSERT INTO `zz_menu` VALUES ('67', '60', '', '0', '', 'Admin/ad/typedel', '删除广告位', '1', '2', '', '删除广告位', '0', '0');
INSERT INTO `zz_menu` VALUES ('69', '0', '', '0', '', 'Admin/Member/index', '会员管理', '1', '3', '', '会员管理', '6', '1');
INSERT INTO `zz_menu` VALUES ('71', '2', '', '0', '', 'Admin/Index/public_cache', '缓存更新', '1', '2', '', '缓存更新', '0', '0');
INSERT INTO `zz_menu` VALUES ('134', '29', '', '0', '', 'Admin/Models/index', '模型管理', '1', '3', '', '模型管理', '0', '0');
INSERT INTO `zz_menu` VALUES ('135', '134', '', '0', '', 'Admin/Models/index', '管理模型', '1', '3', '', '管理模型', '0', '1');
INSERT INTO `zz_menu` VALUES ('136', '135', '', '0', '', 'Admin/Models/add', '添加模型', '1', '3', '', '添加模型', '0', '1');
INSERT INTO `zz_menu` VALUES ('137', '135', '', '0', '', 'Admin/Models/edit', '修改模型', '1', '3', '', '修改模型', '0', '0');
INSERT INTO `zz_menu` VALUES ('138', '135', '', '0', '', 'Admin/Models/del', '删除模型', '1', '3', '', '删除模型', '0', '0');
INSERT INTO `zz_menu` VALUES ('139', '135', '', '0', '', 'Admin/Field/index', '字段管理', '1', '3', '', '字段管理', '0', '0');
INSERT INTO `zz_menu` VALUES ('140', '135', '', '0', '', 'Admin/Models/disabled', '禁用模型', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('141', '139', '', '0', '', 'Admin/Field/add', '添加字段', '1', '3', '', '添加字段', '0', '1');
INSERT INTO `zz_menu` VALUES ('142', '139', '', '0', '', 'Admin/Field/edit', '修改字段', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('143', '139', '', '0', '', 'Admin/Field/del', '删除字段', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('144', '139', '', '0', '', 'Admin/Field/disabled', '禁用字段', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('145', '139', '', '0', '', 'Admin/Field/priview', '预览模型', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('93', '69', '', '0', '', 'Admin/Member/index', '会员管理', '1', '3', '', '会员管理', '2', '1');
INSERT INTO `zz_menu` VALUES ('94', '105', '', '0', '', 'Admin/Member/add', '添加会员', '1', '3', '', '添加会员', '0', '1');
INSERT INTO `zz_menu` VALUES ('95', '105', '', '0', '', 'Admin/Member/del', '删除会员', '1', '2', '', '删除会员', '0', '0');
INSERT INTO `zz_menu` VALUES ('96', '105', '', '0', '', 'Admin/Member/edit', '修改查看会员资料', '1', '2', '', '修改查看会员资料', '0', '0');
INSERT INTO `zz_menu` VALUES ('105', '93', '', '0', '', 'Admin/Member/index', '会员列表', '1', '3', '', '会员列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('114', '1', '', '0', '', 'Admin/Expand/index', '扩展设置', '1', '3', '', '扩展设置', '0', '1');
INSERT INTO `zz_menu` VALUES ('115', '114', '', '0', '', 'Admin/Expand/index', '联动菜单', '1', '3', '', '联动菜单', '0', '1');
INSERT INTO `zz_menu` VALUES ('116', '114', '', '0', '', 'Admin/Expand/type', '联动类型', '1', '3', '', '联动类型', '0', '1');
INSERT INTO `zz_menu` VALUES ('117', '115', '', '0', '', 'Admin/Expand/add', '增加菜单', '1', '3', '', '增加菜单', '0', '1');
INSERT INTO `zz_menu` VALUES ('118', '115', '', '0', '', 'Admin/Expand/edit', '修改菜单', '1', '2', '', '修改菜单', '0', '0');
INSERT INTO `zz_menu` VALUES ('119', '115', '', '0', '', 'Admin/Expand/del', '删除菜单', '1', '2', '', '删除菜单', '0', '0');
INSERT INTO `zz_menu` VALUES ('120', '116', '', '0', '', 'Admin/Expand/typeadd', '添加类型', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('121', '116', '', '0', '', 'Admin/Expand/typeedit', '修改类型', '1', '2', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('122', '116', '', '0', '', 'Admin/Expand/typedel', '删除类型', '1', '2', '', '删除类型', '0', '0');
INSERT INTO `zz_menu` VALUES ('123', '114', '', '0', '', 'Admin/Expand/area', '地区管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('124', '123', '', '0', '', 'Admin/Expand/areaadd', '地区添加', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('125', '123', '', '0', '', 'Admin/Expand/areaedit', '地区修改', '1', '2', '', '地区修改', '0', '0');
INSERT INTO `zz_menu` VALUES ('126', '123', '', '0', '', 'Admin/Expand/areadel', '地区删除', '1', '2', '', '地区删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('127', '123', '', '0', '', 'Admin/Expand/arealistorder', '地区排序', '1', '2', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('128', '115', '', '0', '', 'Admin/Expand/listorder', '菜单排序', '1', '2', '', '菜单排序', '0', '0');
INSERT INTO `zz_menu` VALUES ('148', '153', '', '0', '', 'Admin/Productcate/index', '商品分类管理', '1', '3', '', '商品分类管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('149', '280', '', '0', '', 'Admin/Productcate/add', '添加分类', '1', '3', '', '添加分类', '0', '1');
INSERT INTO `zz_menu` VALUES ('150', '280', '', '0', '', 'Admin/Productcate/delete', '删除分类', '1', '3', '', '删除分类', '0', '0');
INSERT INTO `zz_menu` VALUES ('151', '280', '', '0', '', 'Admin/Productcate/listorder', '分类排序', '1', '3', '', '分类排序', '0', '0');
INSERT INTO `zz_menu` VALUES ('153', '0', '', '0', '', 'Admin/Store/index', '连锁店管理', '1', '3', '', '连锁店管理', '5', '1');
INSERT INTO `zz_menu` VALUES ('154', '153', '', '0', '', 'Admin/Store/index', '连锁店管理', '1', '3', '', '店铺管理', '2', '1');
INSERT INTO `zz_menu` VALUES ('155', '153', '', '0', '', 'Admin/Product/index', '商品管理', '1', '3', '', '商品管理', '1', '1');
INSERT INTO `zz_menu` VALUES ('156', '154', '', '0', '', 'Admin/Store/index', '连锁店列表', '1', '3', '', '店铺列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('157', '156', '', '0', '', 'Admin/Store/add', '添加连锁店', '1', '3', '', '添加店铺', '0', '1');
INSERT INTO `zz_menu` VALUES ('158', '156', '', '0', '', 'Admin/Store/edit', '修改店铺', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('159', '156', '', '0', '', 'Admin/Store/delete', '删除店铺', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('160', '156', '', '0', '', 'Admin/Store/listorder', '店铺排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('161', '156', '', '0', '', 'Admin/Store/del', '店铺批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('162', '156', '', '0', '', 'Admin/Store/review', '店铺审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('163', '155', '', '0', '', 'Admin/Product/index', '企业商品列表', '1', '3', '', '商品列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('164', '163', '', '0', '', 'Admin/Product/add', '商品添加', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('165', '163', '', '0', '', 'Admin/Product/edit', '商品修改', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('166', '163', '', '0', '', 'Admin/Product/delete', '商品删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('167', '163', '', '0', '', 'Admin/Product/listorder', '商品排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('168', '163', '', '0', '', 'Admin/Product/del', '商品批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('266', '265', '', '0', '', 'Admin/Company/index', '企业列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('172', '69', '', '0', '', 'Admin/Apply/index', '申请管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('174', '172', '', '0', '', 'Admin/Apply/company', '企业申请管理', '1', '3', '', '供应商申请管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('175', '172', '', '0', '', 'Admin/Apply/shop', '连锁店申请管理', '1', '3', '', '连锁店申请管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('177', '174', '', '0', '', 'Admin/Apply/companyreview', '企业申请审核', '1', '3', '', '企业申请', '0', '0');
INSERT INTO `zz_menu` VALUES ('178', '175', '', '0', '', 'Admin/Apply/shopreview', '连锁店申请审核', '1', '3', '', '连锁店申请审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('179', '69', '', '0', '', 'Admin/Message/index', '消息管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('180', '179', '', '0', '', 'Admin/Message/index', '通知列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('181', '180', '', '0', '', 'Admin/Message/add', '新增通知', '1', '3', '', '新增通知', '0', '0');
INSERT INTO `zz_menu` VALUES ('182', '180', '', '0', '', 'Admin/Message/delete', '删除通知', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('183', '180', '', '0', '', 'Admin/Message/bpaydel', '批量删除通知', '1', '3', '', '批量删除通知', '0', '0');
INSERT INTO `zz_menu` VALUES ('184', '179', '', '0', '', 'Admin/Message/sms', '短信列表', '0', '3', '', '短信列表', '0', '0');
INSERT INTO `zz_menu` VALUES ('185', '184', '', '0', '', 'Admin/Message/deletesms', '删除短信记录', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('186', '184', '', '0', '', 'Admin/Message/smsdel', '批量删除短信记录', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('187', '69', '', '0', '', 'Admin/Integral/index', '积分管理', '1', '3', '', '积分管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('188', '187', '', '0', '', 'Admin/Integral/index', '积分列表', '1', '3', '', '积分列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('189', '188', '', '0', '', 'Admin/Integral/edit', '赠送积分', '1', '3', '', '赠送霸王币', '0', '0');
INSERT INTO `zz_menu` VALUES ('190', '188', '', '0', '', 'Admin/Integral/log', '积分使用记录', '1', '3', '', '霸王币使用记录', '0', '0');
INSERT INTO `zz_menu` VALUES ('191', '188', '', '0', '', 'Admin/Integral/dellog', '删除使用记录', '1', '3', '', '删除使用记录', '0', '0');
INSERT INTO `zz_menu` VALUES ('280', '148', '', '0', '', 'Admin/Productcate/index', '商品分类列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('281', '280', '', '0', '', 'Admin/Productcate/edit', '修改商品分类', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('282', '153', '', '0', '', 'Admin/Storehouse/index', '仓库管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('283', '282', '', '0', '', 'Admin/Storehouse/index', '仓库列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('284', '283', '', '0', '', 'Admin/Storehouse/add', '添加仓库', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('197', '172', '', '0', '', 'Admin/Apply/joincompany', '用户加入企业申请管理', '1', '3', '', '用户加入企业申请管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('198', '197', '', '0', '', 'Admin/Apply/joincompanyreview', '用户加入企业申请审核', '1', '3', '', '用户加入企业申请审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('199', '114', '', '0', '', 'Admin/Db/index', '数据库管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('200', '199', '', '0', '', 'Admin/Db/export', '数据库备份', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('201', '199', '', '0', '', 'Admin/Db/import', '数据库还原', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('202', '199', '', '0', '', 'Admin/Db/baklist', '数据库备份列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('203', '199', '', '0', '', 'Admin/Db/showtable', '查看表结构', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('204', '199', '', '0', '', 'Admin/Db/downzip', '下载备份', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('205', '199', '', '0', '', 'Admin/Db/delzip', '删除备份', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('206', '69', '', '0', '', 'Admin/Feedback/index', '反馈管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('207', '206', '', '0', '', 'Admin/Feedback/index', '反馈列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('208', '207', '', '0', '', 'Admin/Feedback/delete', '删除反馈', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('209', '207', '', '0', '', 'Admin/Feedback/del', '反馈批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('210', '207', '', '0', '', 'Admin/Feedback/feedbackcheck', '反馈处理', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('211', '32', '', '0', '', 'Admin/article/edit', '修改内容', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('212', '9', '', '0', '', 'Admin/Config/template', '通知信息模版', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('213', '163', '', '0', '', 'Admin/Product/review', '商品审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('214', '0', '', '0', '', 'Admin/Order/index', '订单管理', '1', '3', '', '', '4', '1');
INSERT INTO `zz_menu` VALUES ('215', '214', '', '0', '', 'Admin/Order/index', '订单管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('216', '215', '', '0', '', 'Admin/Order/neworder', '最新订单', '1', '3', '', '最新订单', '0', '1');
INSERT INTO `zz_menu` VALUES ('217', '216', '', '0', '', 'Admin/Order/edit', '修改订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('218', '216', '', '0', '', 'Admin/Order/del', '批量删除订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('219', '216', '', '0', '', 'Admin/Order/delete', '删除订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('220', '216', '', '0', '', 'Admin/Order/excel', '订单导出', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('221', '216', '', '0', '', 'Admin/Order/show', '订单查看', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('299', '155', '', '0', '', 'Admin/Product/product', '商品列表', '1', '3', '', '商品列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('224', '0', '', '0', '', 'Admin/Balance/index', '财务管理', '1', '3', '', '财务管理', '3', '1');
INSERT INTO `zz_menu` VALUES ('225', '224', '', '0', '', 'Admin/Balance/index', '销售汇总报表', '1', '3', '', '销售汇总报表', '0', '1');
INSERT INTO `zz_menu` VALUES ('226', '229', '', '0', '', 'Admin/Balance/company', '企业订单列表', '1', '3', '', '企业订单管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('227', '226', '', '0', '', 'Admin/Balance/companyorderdeal', '企业订单结算', '1', '3', '', '企业订单结算', '0', '0');
INSERT INTO `zz_menu` VALUES ('228', '226', '', '0', '', 'Admin/Balance/companyorderinfo', '企业订单明细', '1', '3', '', '企业订单明细', '0', '0');
INSERT INTO `zz_menu` VALUES ('229', '224', '', '0', '', 'Admin/Balance/company', '企业订单管理', '1', '3', '', '企业订单管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('343', '224', '', '0', '', 'Admin/Bill/index', '发票申请管理', '1', '3', '', '发票管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('344', '343', '', '0', '', 'Admin/Bill/index', '发票申请列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('298', '9', '', '0', '', 'Admin/Config/version', '版本管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('345', '344', '', '0', '', 'Admin/Bill/review', '发票申请确认开票', '1', '3', '', '确认开票', '0', '0');
INSERT INTO `zz_menu` VALUES ('235', '69', '', '0', '', 'Admin/Userupload/index', '其他', '1', '3', '', '会员上传资料管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('236', '235', '', '0', '', 'Admin/Userupload/index', '会员上传资料管理', '1', '3', '', '会员上传资料管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('237', '235', '', '0', '', 'Admin/Userupload/type', '会员资料类型管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('238', '236', '', '0', '', 'Admin/Userupload/review', '会员上传资料审核', '1', '3', '', '会员上传资料审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('239', '237', '', '0', '', 'Admin/Userupload/typeadd', '添加资料类型', '1', '3', '', '添加类型', '0', '1');
INSERT INTO `zz_menu` VALUES ('240', '237', '', '0', '', 'Admin/Userupload/typeedit', '修改资料类型', '1', '3', '', '修改类型', '0', '0');
INSERT INTO `zz_menu` VALUES ('241', '237', '', '0', '', 'Admin/Userupload/typedelete', '删除资料类型', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('242', '237', '', '0', '', 'Admin/Userupload/typelistorder', '资料类型排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('243', '114', '', '0', '', 'Admin/Attachment/index', '附件管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('245', '9', '', '0', '', 'Admin/Config/attach', '附件配置', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('246', '9', '', '0', '', 'Admin/Config/third', '第三方接口配置', '1', '3', '', '短信接口配置', '0', '1');
INSERT INTO `zz_menu` VALUES ('247', '197', '', '0', '', 'Admin/Apply/joincompanydelete', '删除申请', '1', '3', '', '删除申请', '0', '0');
INSERT INTO `zz_menu` VALUES ('248', '175', '', '0', '', 'Admin/Apply/shopdelete', '连锁店申请删除', '1', '3', '', '连锁店申请删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('249', '174', '', '0', '', 'Admin/Apply/companydelete', '企业申请删除', '1', '3', '', '供应商申请删除', '0', '0');
INSERT INTO `zz_menu` VALUES ('251', '69', '', '0', '', 'Admin/CacheLevel/index', '会员级别管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('252', '253', '', '0', '', 'Admin/CacheLevel/save', '会员级别修改', '1', '3', '', '会员级别修改', '0', '0');
INSERT INTO `zz_menu` VALUES ('253', '251', '', '0', '', 'Admin/CacheLevel/index', '会员级别列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('265', '69', '', '0', '', 'Admin/Company/index', '企业管理', '1', '3', '', '企业管理', '1', '1');
INSERT INTO `zz_menu` VALUES ('255', '9', '', '0', '', 'Admin/Config/service', '系统文案配置', '1', '3', '', '服务协议配置', '0', '1');
INSERT INTO `zz_menu` VALUES ('256', '9', '', '0', '', 'Admin/Config/share', '分享文案配置', '1', '3', '', '分享文案配置', '0', '1');
INSERT INTO `zz_menu` VALUES ('257', '30', '', '0', '', 'Admin/Party/index', '优惠活动', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('258', '257', '', '0', '', 'Admin/Party/add', '添加活动', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('259', '257', '', '0', '', 'Admin/Party/edit', '修改活动', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('260', '257', '', '0', '', 'Admin/Party/delete', '删除活动', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('261', '257', '', '0', '', 'Admin/Party/del', '批量删除活动', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('262', '257', '', '0', '', 'Admin/Party/listorder', '活动排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('263', '257', '', '0', '', 'Admin/Party/review', '活动审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('264', '257', '', '0', '', 'Admin/Party/unreview', '活动取消审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('267', '266', '', '0', '', 'Admin/Company/add', '添加企业', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('268', '266', '', '0', '', 'Admin/Company/edit', '修改企业', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('269', '266', '', '0', '', 'Admin/Company/del', '删除企业', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('270', '93', '', '0', '', 'Admin/Member/company', '企业用户列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('271', '153', '', '0', '', 'Admin/CacheProductUnit/index', '商品单位管理', '1', '3', '', '商品单位管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('272', '271', '', '0', '', 'Admin/CacheProductUnit/index', '商品单位列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('273', '272', '', '0', '', 'Admin/CacheProductUnit/edit', '修改商品单位', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('274', '69', '', '0', '', 'Admin/Push/index', '推送管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('275', '274', '', '0', '', 'Admin/Push/index', '推送列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('276', '275', '', '0', '', 'Admin/Push/add', '添加推送消息', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('277', '275', '', '0', '', 'Admin/Push/delete', '推送信息删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('278', '275', '', '0', '', 'Admin/Push/again', '再次推送信息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('279', '275', '', '0', '', 'Admin/Push/del', '批量删除推送消息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('285', '283', '', '0', '', 'Admin/Storehouse/edit', '修改仓库', '1', '3', '', '修改仓库', '0', '0');
INSERT INTO `zz_menu` VALUES ('286', '283', '', '0', '', 'Admin/Storehouse/delete', '删除仓库', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('287', '283', '', '0', '', 'Admin/Storehouse/listorder', '仓库排序', '1', '3', '', '仓库排序', '0', '0');
INSERT INTO `zz_menu` VALUES ('288', '283', '', '0', '', 'Admin/Storehouse/del', '仓库批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('289', '283', '', '0', '', 'Admin/Storehouse/review', '仓库审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('290', '153', '', '0', '', 'Admin/Coupons/index', '优惠券管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('291', '290', '', '0', '', 'Admin/Coupons/index', '优惠券类型列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('292', '291', '', '0', '', 'Admin/Coupons/add', '发布优惠券', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('293', '291', '', '0', '', 'Admin/Coupons/edit', '修改优惠券', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('294', '291', '', '0', '', 'Admin/Coupons/delete', '删除优惠券', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('295', '291', '', '0', '', 'Admin/Coupons/listorder', '优惠券排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('296', '291', '', '0', '', 'Admin/Coupons/del', '批量删除优惠券', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('297', '291', '', '0', '', 'Admin/Coupons/review', '优惠券审核', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('300', '299', '', '0', '', 'Admin/Product/padd', '商品添加', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('301', '299', '', '0', '', 'Admin/Product/pedit', '商品修改', '1', '3', '', '商品修改', '0', '0');
INSERT INTO `zz_menu` VALUES ('302', '299', '', '0', '', 'Admin/Product/pdelete', '商品删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('303', '299', '', '0', '', 'Admin/Product/plistorder', '商品排序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('304', '299', '', '0', '', 'Admin/Product/pdel', '商品批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('305', '153', '', '0', '', 'Admin/Store/owner', '本店信息管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('306', '153', '', '0', '', 'Admin/Store/packing', '包装员管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('307', '306', '', '0', '', 'Admin/Store/packing', '包装员列表', '1', '3', '', '添加包装员', '0', '1');
INSERT INTO `zz_menu` VALUES ('308', '307', '', '0', '', 'Admin/Store/packingadd', '添加包装员', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('309', '307', '', '0', '', 'Admin/Store/packingedit', '修改包装员', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('310', '307', '', '0', '', 'Admin/Store/packingdelete', '包装员删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('311', '153', '', '0', '', 'Admin/Store/financial', '财务人员管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('312', '311', '', '0', '', 'Admin/Store/financial', '财务人员列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('313', '312', '', '0', '', 'Admin/Store/financialadd', '添加财务人员', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('314', '312', '', '0', '', 'Admin/Store/financialedit', '修改财务人员信息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('315', '312', '', '0', '', 'Admin/Store/financialdelete', '删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('316', '312', '', '0', '', 'Admin/Store/financialdel', '批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('317', '153', '', '0', '', 'Admin/Store/courier', '配送员管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('318', '317', '', '0', '', 'Admin/Store/courier', '配送员列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('319', '318', '', '0', '', 'Admin/Store/courieradd', '添加配送员', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('320', '318', '', '0', '', 'Admin/Store/courieredit', '修改配送员信息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('321', '318', '', '0', '', 'Admin/Store/courierdelete', '删除配送员', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('322', '318', '', '0', '', 'Admin/Store/courierdel', '批量删除配送员', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('323', '163', '', '0', '', 'Admin/Product/storehouse', '仓库中商品列表', '1', '3', '', '仓库中商品列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('324', '163', '', '0', '', 'Admin/Product/selling', '在售中商品列表', '1', '3', '', '在售中商品列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('325', '148', '', '0', '', 'Admin/Productcate/apply', '商品分类应用', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('326', '299', '', '0', '', 'Admin/Product/pstorehouse', '仓库中的商品列', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('327', '299', '', '0', '', 'Admin/Product/pselling', '在售中商品列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('328', '163', '', '0', '', 'Admin/Product/pushs', '商品置顶', '1', '3', '', '商品置顶', '0', '0');
INSERT INTO `zz_menu` VALUES ('329', '163', '', '0', '', 'Admin/Product/unpushs', '商品取消置顶', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('330', '163', '', '0', '', 'Admin/Product/off', '商品下架', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('331', '163', '', '0', '', 'Admin/Product/unoff', '商品取消下架', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('332', '299', '', '0', '', 'Admin/Product/ppushs', '商品置顶', '1', '3', '', '商品置顶', '0', '0');
INSERT INTO `zz_menu` VALUES ('333', '299', '', '0', '', 'Admin/Product/punpushs', '商品取消置顶', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('334', '299', '', '0', '', 'Admin/Product/poff', '商品下架', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('335', '299', '', '0', '', 'Admin/Product/punoff', '商品取消下架', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('336', '214', '', '0', '', 'Admin/Companyorder/index', '企业订单管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('337', '336', '', '0', '', 'Admin/Companyorder/index', '订单列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('338', '337', '', '0', '', 'Admin/Companyorder/deal', '派发订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('339', '337', '', '0', '', 'Admin/Companyorder/delete', '删除订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('340', '337', '', '0', '', 'Admin/Companyorder/del', '批量删除订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('341', '337', '', '0', '', 'Admin/Companyorder/excel', '订单导出', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('342', '337', '', '0', '', 'Admin/Companyorder/show', '订单查看', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('349', '348', '', '0', '', 'Admin/Package/package', '包装订单', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('348', '214', '', '0', '', 'Admin/Package/index', '订单包装管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('350', '348', '', '0', '', 'Admin/Package/weigh', '填写称重信息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('351', '224', '', '0', '', 'Admin/Balance/store', '门店销售汇总报表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('352', '348', '', '0', '', 'Admin/Package/packagedone', '包装完成', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('353', '348', '', '0', '', 'Admin/Package/packages', '批量包装', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('354', '348', '', '0', '', 'Admin/Package/packagedones', '批量包装完成', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('355', '224', '', '0', '', 'Admin/Balance/runercommission', '配送员提成结算管理', '1', '3', '', '配送员提成结算管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('356', '355', '', '0', '', 'Admin/Balance/runercommission', '配送员提成结算列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('357', '356', '', '0', '', 'Admin/Balance/billrunercommissiondeal', '配送员提成结算', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('358', '0', '', '0', '', 'Admin/Company/index', '企业管理', '1', '3', '', '', '2', '1');
INSERT INTO `zz_menu` VALUES ('359', '358', '', '0', '', 'Admin/Company/index', '企业管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('360', '359', '', '0', '', 'Admin/Company/info', '企业基本信息', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('361', '359', '', '0', '', 'Admin/Company/member', '企业会员管理', '1', '3', '', '企业会员管理', '0', '1');
INSERT INTO `zz_menu` VALUES ('362', '359', '', '0', '', 'Admin/Company/order', '企业订单管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('363', '361', '', '0', '', 'Admin/Company/changestatus', '更改状态', '1', '2', '', '更改状态', '0', '0');
INSERT INTO `zz_menu` VALUES ('364', '362', '', '0', '', 'Admin/Company/ordershow', '查看订单详情', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('365', '362', '', '0', '', 'Admin/Company/orderexcel', '订单导出', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('366', '216', '', '0', '', 'Admin/Order/deal', '订单分配', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('367', '114', '', '0', '', 'Admin/Task/index', '执守程序管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('368', '367', '', '0', '', 'Admin/Task/add', '添加执守程序', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('369', '367', '', '0', '', 'Admin/Task/edit', '修改执守程序', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('376', '216', '', '0', '', 'Admin/Order/packageing', '包装中', '1', '3', '', null, '7', '1');
INSERT INTO `zz_menu` VALUES ('371', '216', '', '0', '', 'Admin/Order/index', '全部订单', '1', '3', '', '全部订单', '12', '1');
INSERT INTO `zz_menu` VALUES ('372', '216', '', '0', '', 'Admin/Order/waitpay', '等待支付', '1', '3', '', '', '10', '1');
INSERT INTO `zz_menu` VALUES ('373', '216', '', '0', '', 'Admin/Order/waitreview', '待审核', '1', '3', '', '待审核', '0', '0');
INSERT INTO `zz_menu` VALUES ('374', '216', '', '0', '', 'Admin/Order/waitdistribute', '待派发', '1', '3', '', '', '9', '1');
INSERT INTO `zz_menu` VALUES ('375', '216', '', '0', '', 'Admin/Order/distributedone', '已派发', '1', '3', '', '', '8', '1');
INSERT INTO `zz_menu` VALUES ('382', '216', '', '0', '', 'Admin/Order/delivery', '配送中', '1', '3', '', '', '5', '1');
INSERT INTO `zz_menu` VALUES ('380', '216', '', '0', '', 'Admin/Order/done', '交易成功', '1', '3', '', '', '4', '1');
INSERT INTO `zz_menu` VALUES ('381', '216', '', '0', '', 'Admin/Order/close', '交易关闭', '1', '3', '', '', '3', '1');
INSERT INTO `zz_menu` VALUES ('377', '216', '', '0', '', 'Admin/Order/packagedone', '包装完成', '1', '3', '', null, '6', '1');
INSERT INTO `zz_menu` VALUES ('379', '215', '', '0', '', 'Admin/Order/order_import', '订单导入', '1', '3', '', '订单导入', '0', '1');
INSERT INTO `zz_menu` VALUES ('383', '216', '', '0', '', 'Admin/Order/errororder', '异常订单', '1', '3', '', '异常订单', '2', '1');
INSERT INTO `zz_menu` VALUES ('384', '274', '', '0', '', 'Admin/Push/store', '门店推送列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('385', '384', '', '0', '', 'Admin/Push/sadd', '添加推送消息', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('386', '384', '', '0', '', 'Admin/Push/pushsagain', '再次推送消息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('387', '384', '', '0', '', 'Admin/Push/delete', '推送消息删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('389', '384', '', '0', '', 'Admin/Push/del', '批量删除推送消息', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('391', '216', '', '0', '', 'Admin/Orderfeedback/index', '售后订单列表', '1', '3', '', '订单反馈列表', '0', '1');
INSERT INTO `zz_menu` VALUES ('392', '391', '', '0', '', 'Admin/Orderfeedback/delete', '反馈删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('393', '391', '', '0', '', 'Admin/Orderfeedback/del', '反馈批量删除', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('394', '391', '', '0', '', 'Admin/Orderfeedback/feedbackcheck	', '反馈处理', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('395', '163', '', '0', '', 'Admin/Product/hot', '热销商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('396', '163', '', '0', '', 'Admin/Product/search', '商品搜索情况', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('397', '299', '', '0', '', 'Admin/Product/phot', '热销商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('398', '216', '', '0', '', 'Admin/Order/speed', '极速达订单', '1', '3', '', '', '11', '1');
INSERT INTO `zz_menu` VALUES ('399', '224', '', '0', '', 'Admin/Balance/runer', '配送员应缴款结算管理', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('400', '399', '', '0', '', 'Admin/Balance/runer', '配送员应缴款结算列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('401', '400', '', '0', '', 'Admin/Balance/runerdeal', '配送员应缴款结算', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('402', '224', '', '0', '', 'Admin/Balance/runerinvite', '配送员推广结算管理', '1', '3', '', '配送员推广结算', '0', '1');
INSERT INTO `zz_menu` VALUES ('403', '402', '', '0', '', 'Admin/Balance/runerinvite', '配送员推广结算列表', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('404', '299', '', '0', '', 'Admin/Product/simple', '普通商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('405', '299', '', '0', '', 'Admin/Product/book', '预购商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('406', '299', '', '0', '', 'Admin/Product/group', '团购商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('407', '299', '', '0', '', 'Admin/Product/weigh', '称重商品', '1', '3', '', '', '0', '1');
INSERT INTO `zz_menu` VALUES ('408', '105', '', '0', '', 'Admin/Member/details', '查看详情', '1', '3', '', '', '0', '0');
INSERT INTO `zz_menu` VALUES ('409', '216', '', '0', '', 'Admin/Order/cancelorder', '已取消订单', '1', '3', '', '交易取消', '1', '1');
INSERT INTO `zz_menu` VALUES ('410', '216', '', '0', '', 'Admin/Evaluate/index', '评论列表', '1', '3', '', '', '0', '1');

-- ----------------------------
-- Table structure for zz_message
-- ----------------------------
DROP TABLE IF EXISTS `zz_message`;
CREATE TABLE `zz_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发送者  0代表管理员',
  `tuid` int(11) NOT NULL COMMENT '接收者  0代表管理员',
  `value` varchar(255) COLLATE utf8_bin NOT NULL,
  `varname` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'system 系统消息  order 订单消息  hot 促销信息',
  `inputtime` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 1已读 0未读',
  `isdel` tinyint(4) DEFAULT '0' COMMENT '是否删除 1是 0否',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='系统信息表';

-- ----------------------------
-- Records of zz_message
-- ----------------------------

-- ----------------------------
-- Table structure for zz_noticestatus
-- ----------------------------
DROP TABLE IF EXISTS `zz_noticestatus`;
CREATE TABLE `zz_noticestatus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `orderid` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单提示记录';

-- ----------------------------
-- Records of zz_noticestatus
-- ----------------------------

-- ----------------------------
-- Table structure for zz_oauth
-- ----------------------------
DROP TABLE IF EXISTS `zz_oauth`;
CREATE TABLE `zz_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_bind` tinyint(30) NOT NULL DEFAULT '0',
  `site` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `openid` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `nickname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `head` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `logintimes` int(10) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `bind_uid` int(10) NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `site` (`site`,`openid`) USING BTREE,
  KEY `uname` (`is_bind`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='第三方授权表';

-- ----------------------------
-- Records of zz_oauth
-- ----------------------------

-- ----------------------------
-- Table structure for zz_order
-- ----------------------------
DROP TABLE IF EXISTS `zz_order`;
CREATE TABLE `zz_order` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `ordercode` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '订单二维码',
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `ruid` int(11) NOT NULL DEFAULT '0' COMMENT '配送员id',
  `puid` int(11) NOT NULL DEFAULT '0' COMMENT '包装员id',
  `storeid` int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
  `orderid` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '订单号',
  `nums` int(5) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '现金支付',
  `yes_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '已付金额',
  `wait_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '待付金额',
  `total` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `paystyle` int(1) NOT NULL COMMENT '支付途径 1在线支付 2 货到付款 3钱包支付 4优惠券抵扣',
  `paytype` int(5) NOT NULL COMMENT '在线支付方式 1：支付宝，2：微信',
  `discount` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '使用优惠券',
  `couponsid` int(11) DEFAULT NULL COMMENT '优惠券订单id',
  `wallet` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '使用钱包',
  `integral` int(5) NOT NULL DEFAULT '0' COMMENT '花费积分',
  `delivery` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `deliverytype` int(5) NOT NULL COMMENT '运送方式',
  `addresstype` int(5) NOT NULL COMMENT '地址类型',
  `area` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '地区',
  `lat` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '纬度',
  `lng` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '经度',
  `address` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '详细地址',
  `name` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '收货人',
  `tel` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '联系方式',
  `code` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '邮编',
  `buyerremark` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '买家备注',
  `sellerremark` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '卖家备注',
  `cardremark` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '贺卡留言',
  `billtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票类型 0未申请发票 1普通发票 2增值发票',
  `billtitle` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '发票抬头',
  `billaddressid` int(11) DEFAULT NULL COMMENT '发票地址id',
  `start_sendtime` int(15) NOT NULL COMMENT '开始收货时间',
  `end_sendtime` int(15) NOT NULL COMMENT '结束送达时间',
  `isspeed` int(5) NOT NULL COMMENT '是否是极速达订单',
  `inputtime` int(15) NOT NULL COMMENT '下单时间',
  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 1普通订单 2预购订单 3企业订单 ',
  `ordersource` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单来源 1手机web 2App 3饿了么 4口碑外卖 5售后订单',
  `trade_no` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `paynotifydata` text COLLATE utf8_bin,
  `wxcode` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `error_content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `error_thumb` text COLLATE utf8_bin COMMENT '接收图片',
  `receivesite_thumb` text COLLATE utf8_bin,
  `noconfirm_num` int(11) DEFAULT '0',
  `iscontainsweigh` tinyint(4) DEFAULT '0' COMMENT '是否包含称重商品 1是 0否',
  `isserviceorder` tinyint(4) DEFAULT '0' COMMENT '是否是售后订单 1是 0否',
  `relationorderid` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '关联订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单表';

-- ----------------------------
-- Records of zz_order
-- ----------------------------

-- ----------------------------
-- Table structure for zz_order_distance
-- ----------------------------
DROP TABLE IF EXISTS `zz_order_distance`;
CREATE TABLE `zz_order_distance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` varchar(100) COLLATE utf8_bin NOT NULL,
  `ruid` int(11) NOT NULL,
  `lat` varchar(255) COLLATE utf8_bin NOT NULL,
  `lng` varchar(255) COLLATE utf8_bin NOT NULL,
  `inputtime` int(11) NOT NULL,
  `ip` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单配送路径表';

-- ----------------------------
-- Records of zz_order_distance
-- ----------------------------

-- ----------------------------
-- Table structure for zz_order_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zz_order_feedback`;
CREATE TABLE `zz_order_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `orderid` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '订单号',
  `content` varchar(255) COLLATE utf8_bin NOT NULL,
  `thumb` varchar(255) COLLATE utf8_bin NOT NULL,
  `inputtime` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin NOT NULL,
  `verify_time` int(11) NOT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单反馈表';

-- ----------------------------
-- Records of zz_order_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for zz_order_productinfo
-- ----------------------------
DROP TABLE IF EXISTS `zz_order_productinfo`;
CREATE TABLE `zz_order_productinfo` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '订单号',
  `pid` int(11) DEFAULT NULL COMMENT '商品id',
  `nums` int(11) DEFAULT NULL COMMENT '商品数量',
  `price` decimal(50,2) DEFAULT NULL COMMENT '单价',
  `product_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型 1一般商品 2团购商品 3预购商品 4称重商品',
  `weigh` decimal(50,2) DEFAULT NULL,
  `isweigh` int(5) NOT NULL COMMENT '是否称重 1已称重 0未称重',
  `weightime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单产品信息表';

-- ----------------------------
-- Records of zz_order_productinfo
-- ----------------------------

-- ----------------------------
-- Table structure for zz_order_time
-- ----------------------------
DROP TABLE IF EXISTS `zz_order_time`;
CREATE TABLE `zz_order_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` varchar(100) NOT NULL,
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '订单状态 0默认 1用户确认订单成功 2商家订单审核成功 3取消订单 4异常订单 5订单完成 6订单关闭',
  `inputtime` int(11) NOT NULL COMMENT '订单生成时间',
  `pay_status` int(5) NOT NULL DEFAULT '0' COMMENT '支付状态 0未支付 1已支付',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `delivery_status` int(5) NOT NULL DEFAULT '0' COMMENT '发货状态 0未发货 1配送员配送中 2配送员确认送达 3收货人确认送达 4已完成',
  `delivery_time` int(11) NOT NULL COMMENT '发货时间',
  `cancel_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '取消订单状态 1已取消 0未取消',
  `cancel_time` int(11) NOT NULL COMMENT '取消订单时间',
  `donetime` int(11) NOT NULL COMMENT '订单完成时间',
  `package_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0等待包装 1包装中   2包装完成',
  `package_time` int(11) NOT NULL,
  `package_donetime` int(11) NOT NULL COMMENT '包装完成时间',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款状态 1申请中 0未申请 2退款完成',
  `refund_applytime` int(11) NOT NULL COMMENT '退款申请时间',
  `refund_donetime` int(11) NOT NULL COMMENT '退款完成时间',
  `buyer_sendstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收货人确认送达 1确认送达 0未确认送达',
  `buyer_sendtime` int(11) NOT NULL COMMENT '收货人确认送达时间',
  `runer_sendstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '跑腿确认送达 1确认送达 0未确认送达',
  `runer_sendtime` int(11) NOT NULL COMMENT '跑腿确认送达时间',
  `seller_sendstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商户确认送达 1确认送达 0未确认送达',
  `seller_sendtime` int(11) NOT NULL COMMENT '商户确认送达时间',
  `evaluate_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已经评价 0未评价',
  `evaluate_time` int(11) NOT NULL COMMENT '订单评价时间',
  `bill_apply_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票申请状态 1申请中 0未申请 2申请成功 3申请失败',
  `bill_apply_time` int(11) NOT NULL COMMENT '发票申请时间',
  `bill_review_time` int(11) NOT NULL COMMENT '发票申请审核时间',
  `bill_review_remark` varchar(255) DEFAULT NULL,
  `error_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '异常订单  0否  1申请中   2已处理',
  `error_applytime` int(11) NOT NULL COMMENT '异常订单提交时间',
  `error_donetime` int(11) NOT NULL COMMENT '异常处理时间',
  `close_status` tinyint(1) NOT NULL DEFAULT '0',
  `close_time` int(11) NOT NULL,
  `distribute_time` int(11) DEFAULT '0' COMMENT '派发时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='订单时间表';

-- ----------------------------
-- Records of zz_order_time
-- ----------------------------

-- ----------------------------
-- Table structure for zz_page
-- ----------------------------
DROP TABLE IF EXISTS `zz_page`;
CREATE TABLE `zz_page` (
  `id` int(11) NOT NULL,
  `catid` int(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `content` text COLLATE utf8_bin,
  `username` varchar(20) COLLATE utf8_bin DEFAULT '0',
  `updatetime` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='单页内容表';

-- ----------------------------
-- Records of zz_page
-- ----------------------------

-- ----------------------------
-- Table structure for zz_product
-- ----------------------------
DROP TABLE IF EXISTS `zz_product`;
CREATE TABLE `zz_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) NOT NULL COMMENT '店铺ID',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '商品名称',
  `productnumber` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '商品编号',
  `brand` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '商品品牌',
  `description` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '关键描述',
  `catid` int(11) NOT NULL COMMENT '行业分类',
  `subcatid` int(11) NOT NULL COMMENT '商品分类',
  `thumb` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '商品缩略图',
  `extrathumb` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '横向商品缩略图',
  `imglist` text COLLATE utf8_bin COMMENT '商品图片列表',
  `backimglist` text COLLATE utf8_bin COMMENT '商品背景列表',
  `price` decimal(50,2) NOT NULL COMMENT '商品单价',
  `nowprice` decimal(50,2) NOT NULL COMMENT '商品现价',
  `oldprice` decimal(50,2) NOT NULL COMMENT '商品原价',
  `unit` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `stock` int(11) NOT NULL COMMENT '库存',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '商品详情',
  `standard` smallint(8) NOT NULL COMMENT '商品规格',
  `ishot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1促销产品 0默认',
  `isindex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否置顶 1是 0否',
  `expiretime` int(11) NOT NULL COMMENT '到期时间',
  `selltime` int(11) NOT NULL COMMENT '出售时间',
  `advanceprice` decimal(50,2) NOT NULL COMMENT '预付金额',
  `hit` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isoff` tinyint(4) NOT NULL DEFAULT '0' COMMENT '下架 1是，0否 ',
  `shelvestime` int(11) DEFAULT NULL COMMENT '上架时间',
  `unshelvestime` int(11) DEFAULT NULL,
  `inputtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品类型 0企业专区  1一般商品  2团购  3预购  4称重',
  `isdel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除 1是 0否',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  `storehouse` int(11) DEFAULT NULL COMMENT '仓库',
  `step` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='商品表';

-- ----------------------------
-- Records of zz_product
-- ----------------------------

-- ----------------------------
-- Table structure for zz_productcate
-- ----------------------------
DROP TABLE IF EXISTS `zz_productcate`;
CREATE TABLE `zz_productcate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '类型ID',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `parentid` int(10) DEFAULT '0' COMMENT '父ID',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述',
  `listorder` int(5) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='商品类型表';

-- ----------------------------
-- Records of zz_productcate
-- ----------------------------

-- ----------------------------
-- Table structure for zz_push
-- ----------------------------
DROP TABLE IF EXISTS `zz_push`;
CREATE TABLE `zz_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '通知标题',
  `description` blob NOT NULL COMMENT '摘要',
  `type` tinyint(4) NOT NULL COMMENT 'weighorderpaynotice   orderdeliverynotice  orderdeliverydonenotice',
  `content` blob NOT NULL COMMENT '图文信息内容',
  `pid` int(11) NOT NULL COMMENT '商品id',
  `scale` tinyint(4) DEFAULT NULL COMMENT '范围',
  `preference` text COLLATE utf8_bin,
  `inputtime` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '推送状态 1待推送 2推送成功 3推送失败',
  `isadmin` tinyint(4) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='推送消息表';

-- ----------------------------
-- Records of zz_push
-- ----------------------------

-- ----------------------------
-- Table structure for zz_pushlog
-- ----------------------------
DROP TABLE IF EXISTS `zz_pushlog`;
CREATE TABLE `zz_pushlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `receiver` text,
  `title` varchar(255) DEFAULT NULL,
  `extras` varchar(255) DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推送记录表';

-- ----------------------------
-- Records of zz_pushlog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_recharge
-- ----------------------------
DROP TABLE IF EXISTS `zz_recharge`;
CREATE TABLE `zz_recharge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `orderid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` int(5) DEFAULT '1' COMMENT '充值方式',
  `money` decimal(50,2) DEFAULT '0.00' COMMENT '金额',
  `addtime` int(15) DEFAULT NULL,
  `addip` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '交易状态 1成功 0失败',
  `paystatus` tinyint(4) DEFAULT '0',
  `trade_no` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `paytime` int(11) DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='充值记录表';

-- ----------------------------
-- Records of zz_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for zz_runercommission_info
-- ----------------------------
DROP TABLE IF EXISTS `zz_runercommission_info`;
CREATE TABLE `zz_runercommission_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ruid` int(11) NOT NULL,
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL,
  `ordernum` int(11) NOT NULL DEFAULT '0' COMMENT '订单汇总数',
  `ordermoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '订单汇总额',
  `yes_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '已结算金额',
  `no_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '未结算金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未结算  1部分结算 2完成结算',
  `last_paytime` int(11) NOT NULL COMMENT '最后打款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配送员订单提成结算信息表';

-- ----------------------------
-- Records of zz_runercommission_info
-- ----------------------------

-- ----------------------------
-- Table structure for zz_runermoney_info
-- ----------------------------
DROP TABLE IF EXISTS `zz_runermoney_info`;
CREATE TABLE `zz_runermoney_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ruid` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `ordernum` int(11) NOT NULL DEFAULT '0' COMMENT '订单汇总数',
  `ordermoney` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '订单汇总额',
  `yes_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '已结算金额',
  `no_money` decimal(50,2) NOT NULL DEFAULT '0.00' COMMENT '未结算金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未结算  1部分结算 2完成结算',
  `last_paytime` int(11) NOT NULL COMMENT '最后打款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配送员订单应缴款结算信息表';

-- ----------------------------
-- Records of zz_runermoney_info
-- ----------------------------

-- ----------------------------
-- Table structure for zz_runerposition
-- ----------------------------
DROP TABLE IF EXISTS `zz_runerposition`;
CREATE TABLE `zz_runerposition` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `lastposition` varchar(100) COLLATE utf8_bin NOT NULL,
  `distance` double NOT NULL COMMENT '最近一次距离',
  `totaldistance` double NOT NULL COMMENT '总的距离',
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='配送员配送记录表';

-- ----------------------------
-- Records of zz_runerposition
-- ----------------------------

-- ----------------------------
-- Table structure for zz_search_keyword
-- ----------------------------
DROP TABLE IF EXISTS `zz_search_keyword`;
CREATE TABLE `zz_search_keyword` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) DEFAULT NULL,
  `hit` int(11) DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `lastupdatetime` int(11) DEFAULT NULL,
  `listorder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索关键词表';

-- ----------------------------
-- Records of zz_search_keyword
-- ----------------------------

-- ----------------------------
-- Table structure for zz_sendpush_queue
-- ----------------------------
DROP TABLE IF EXISTS `zz_sendpush_queue`;
CREATE TABLE `zz_sendpush_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `varname` varchar(255) DEFAULT NULL COMMENT 'system 系统消息   imagetext 图文消息   product 商品消息',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `receiver` text NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` blob,
  `extras` text NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 是默认  1用户端  2配送端',
  `inputtime` int(11) unsigned NOT NULL,
  `send_time_start` int(11) unsigned NOT NULL,
  `send_time_end` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推送队列表';

-- ----------------------------
-- Records of zz_sendpush_queue
-- ----------------------------

-- ----------------------------
-- Table structure for zz_signlog
-- ----------------------------
DROP TABLE IF EXISTS `zz_signlog`;
CREATE TABLE `zz_signlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容',
  `continuesign` int(11) DEFAULT '0' COMMENT '连续签到次数',
  `totalsign` int(11) DEFAULT '0' COMMENT '累计签到次数',
  `integral` int(11) DEFAULT '0' COMMENT '获得积分',
  `status` tinyint(4) DEFAULT '0',
  `inputtime` int(11) DEFAULT NULL,
  `lastsigntime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='签到记录表';

-- ----------------------------
-- Records of zz_signlog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_sms
-- ----------------------------
DROP TABLE IF EXISTS `zz_sms`;
CREATE TABLE `zz_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `phone` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `r_id` int(11) NOT NULL COMMENT '接收用户id',
  `s_id` int(11) NOT NULL COMMENT '发送用户id',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='短信记录表';

-- ----------------------------
-- Records of zz_sms
-- ----------------------------

-- ----------------------------
-- Table structure for zz_store
-- ----------------------------
DROP TABLE IF EXISTS `zz_store`;
CREATE TABLE `zz_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applyid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '开店人',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '店铺名称',
  `thumb` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '店铺logo',
  `area` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '所属商区',
  `address` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '店铺地址',
  `lng` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '经度',
  `lat` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '纬度',
  `servicearea` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '服务区域',
  `username` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '门店负责人',
  `contact` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '负责人联系方式',
  `workstarttime` char(10) COLLATE utf8_bin NOT NULL COMMENT '营业开始时间',
  `workendtime` char(10) COLLATE utf8_bin NOT NULL COMMENT '营业结束时间',
  `imglist` text COLLATE utf8_bin COMMENT '店铺图片列表',
  `content` text COLLATE utf8_bin,
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '店铺审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `listorder` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='店铺表';

-- ----------------------------
-- Records of zz_store
-- ----------------------------

-- ----------------------------
-- Table structure for zz_storehouse
-- ----------------------------
DROP TABLE IF EXISTS `zz_storehouse`;
CREATE TABLE `zz_storehouse` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(11) NOT NULL COMMENT '所属门店id',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '仓库名称',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '仓库访问链接',
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `curl` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '企业产品链接',
  `inputtime` int(11) NOT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_bin NOT NULL,
  `listorder` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1审核通过  2待审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='仓库表';

-- ----------------------------
-- Records of zz_storehouse
-- ----------------------------

-- ----------------------------
-- Table structure for zz_store_apply
-- ----------------------------
DROP TABLE IF EXISTS `zz_store_apply`;
CREATE TABLE `zz_store_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '申请人',
  `company` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '公司名称',
  `username` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '联系人姓名',
  `email` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `tel` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '联系方式',
  `content` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '合作意向',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '审核状态1审核中 2审核通过3审核不通过',
  `verify_user` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `inputtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='企业合作表';

-- ----------------------------
-- Records of zz_store_apply
-- ----------------------------

-- ----------------------------
-- Table structure for zz_store_cate
-- ----------------------------
DROP TABLE IF EXISTS `zz_store_cate`;
CREATE TABLE `zz_store_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) NOT NULL COMMENT '店铺id',
  `catid` text NOT NULL COMMENT '分类id',
  `inputtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺商品分类表';

-- ----------------------------
-- Records of zz_store_cate
-- ----------------------------

-- ----------------------------
-- Table structure for zz_store_member
-- ----------------------------
DROP TABLE IF EXISTS `zz_store_member`;
CREATE TABLE `zz_store_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(11) NOT NULL COMMENT '门店id',
  `ruid` int(11) NOT NULL COMMENT '配送员id',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='门店配送员关系表';

-- ----------------------------
-- Records of zz_store_member
-- ----------------------------

-- ----------------------------
-- Table structure for zz_task
-- ----------------------------
DROP TABLE IF EXISTS `zz_task`;
CREATE TABLE `zz_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `month` varchar(50) NOT NULL,
  `week` varchar(30) NOT NULL,
  `day` varchar(80) NOT NULL,
  `hour` varchar(50) NOT NULL,
  `min` varchar(150) NOT NULL,
  `task_url` varchar(300) NOT NULL,
  `is_on` tinyint(4) NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL,
  `is_sys` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='计划任务表';

-- ----------------------------
-- Records of zz_task
-- ----------------------------
INSERT INTO `zz_task` VALUES ('1', '待推送信息', '*', '*', '*', '*', '*', 'http://mygrowth.cn/index.php/Home/AutoPush/push.html', '1', '1373708383', '1');

-- ----------------------------
-- Table structure for zz_task_log
-- ----------------------------
DROP TABLE IF EXISTS `zz_task_log`;
CREATE TABLE `zz_task_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_time` datetime NOT NULL,
  `run_time` decimal(10,6) NOT NULL,
  `task_info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='计划任务记录表';

-- ----------------------------
-- Records of zz_task_log
-- ----------------------------
INSERT INTO `zz_task_log` VALUES ('1', '2016-04-14 22:59:20', '2.343258', '系统自动备份开始\r\n数据表zz_account结构备份成功\r\n数据表zz_account数据备份成功\r\n数据表zz_account_log结构备份成功\r\n数据表zz_account_log数据备份成功\r\n数据表zz_activity结构备份成功\r\n数据表zz_activity数据备份成功\r\n数据表zz_ad结构备份成功\r\n数据表zz_ad数据备份成功\r\n数据表zz_address结构备份成功\r\n数据表zz_address数据备份成功\r\n数据表zz_adminpanel结构备份成功\r\n数据表zz_adminpanel数据备份成功\r\n数据表zz_adtype结构备份成功\r\n数据表zz_adtype数据备份成功\r\n数据表zz_area结构备份成功\r\n数据表zz_area数据备份成功\r\n数据表zz_article结构备份成功\r\n数据表zz_article数据备份成功\r\n数据表zz_attachment结构备份成功\r\n数据表zz_attachment数据备份成功\r\n数据表zz_attention结构备份成功\r\n数据表zz_attention数据备份成功\r\n数据表zz_auth_group结构备份成功\r\n数据表zz_auth_group数据备份成功\r\n数据表zz_auth_group_access结构备份成功\r\n数据表zz_auth_group_access数据备份成功\r\n数据表zz_cart结构备份成功\r\n数据表zz_cart数据备份成功\r\n数据表zz_cartinfo结构备份成功\r\n数据表zz_cartinfo数据备份成功\r\n数据表zz_category结构备份成功\r\n数据表zz_category数据备份成功\r\n数据表zz_collect结构备份成功\r\n数据表zz_collect数据备份成功\r\n数据表zz_company结构备份成功\r\n数据表zz_company数据备份成功\r\n数据表zz_company_member结构备份成功\r\n数据表zz_company_member数据备份成功\r\n数据表zz_companyorder_info结构备份成功\r\n数据表zz_companyorder_info数据备份成功\r\n数据表zz_config结构备份成功\r\n数据表zz_config数据备份成功\r\n数据表zz_cooperation结构备份成功\r\n数据表zz_cooperation数据备份成功\r\n数据表zz_coupons结构备份成功\r\n数据表zz_coupons数据备份成功\r\n数据表zz_coupons_order结构备份成功\r\n数据表zz_coupons_order数据备份成功\r\n数据表zz_debug结构备份成功\r\n数据表zz_debug数据备份成功\r\n数据表zz_emaillog结构备份成功\r\n数据表zz_emaillog数据备份成功\r\n数据表zz_evaluation结构备份成功\r\n数据表zz_evaluation数据备份成功\r\n数据表zz_feedback结构备份成功\r\n数据表zz_feedback数据备份成功\r\n数据表zz_feedback_dolog结构备份成功\r\n数据表zz_feedback_dolog数据备份成功\r\n数据表zz_hit结构备份成功\r\n数据表zz_hit数据备份成功\r\n数据表zz_integral结构备份成功\r\n数据表zz_integral数据备份成功\r\n数据表zz_integrallog结构备份成功\r\n数据表zz_integrallog数据备份成功\r\n数据表zz_invite结构备份成功\r\n数据表zz_invite数据备份成功\r\n数据表zz_link结构备份成功\r\n数据表zz_link数据备份成功\r\n数据表zz_linkage结构备份成功\r\n数据表zz_linkage数据备份成功\r\n数据表zz_linkagetype结构备份成功\r\n数据表zz_linkagetype数据备份成功\r\n数据表zz_linktype结构备份成功\r\n数据表zz_linktype数据备份成功\r\n数据表zz_log结构备份成功\r\n数据表zz_log数据备份成功\r\n数据表zz_loginlog结构备份成功\r\n数据表zz_loginlog数据备份成功\r\n数据表zz_member结构备份成功\r\n数据表zz_member数据备份成功\r\n数据表zz_member_info结构备份成功\r\n数据表zz_member_info数据备份成功\r\n数据表zz_menu结构备份成功\r\n数据表zz_menu数据备份成功\r\n数据表zz_message结构备份成功\r\n数据表zz_message数据备份成功\r\n数据表zz_noticestatus结构备份成功\r\n数据表zz_noticestatus数据备份成功\r\n数据表zz_oauth结构备份成功\r\n数据表zz_oauth数据备份成功\r\n数据表zz_order结构备份成功\r\n数据表zz_order数据备份成功\r\n数据表zz_order_distance结构备份成功\r\n数据表zz_order_distance数据备份成功\r\n数据表zz_order_feedback结构备份成功\r\n数据表zz_order_feedback数据备份成功\r\n数据表zz_order_productinfo结构备份成功\r\n数据表zz_order_productinfo数据备份成功\r\n数据表zz_order_time结构备份成功\r\n数据表zz_order_time数据备份成功\r\n数据表zz_page结构备份成功\r\n数据表zz_page数据备份成功\r\n数据表zz_product结构备份成功\r\n数据表zz_product数据备份成功\r\n数据表zz_productcate结构备份成功\r\n数据表zz_productcate数据备份成功\r\n数据表zz_push结构备份成功\r\n数据表zz_push数据备份成功\r\n数据表zz_pushlog结构备份成功\r\n数据表zz_pushlog数据备份成功\r\n数据表zz_recharge结构备份成功\r\n数据表zz_recharge数据备份成功\r\n数据表zz_runercommission_info结构备份成功\r\n数据表zz_runercommission_info数据备份成功\r\n数据表zz_runermoney_info结构备份成功\r\n数据表zz_runermoney_info数据备份成功\r\n数据表zz_runerposition结构备份成功\r\n数据表zz_runerposition数据备份成功\r\n数据表zz_search_keyword结构备份成功\r\n数据表zz_search_keyword数据备份成功\r\n数据表zz_sendpush_queue结构备份成功\r\n数据表zz_sendpush_queue数据备份成功\r\n数据表zz_signlog结构备份成功\r\n数据表zz_signlog数据备份成功\r\n数据表zz_sms结构备份成功\r\n数据表zz_sms数据备份成功\r\n数据表zz_store结构备份成功\r\n数据表zz_store数据备份成功\r\n数据表zz_store_apply结构备份成功\r\n数据表zz_store_apply数据备份成功\r\n数据表zz_store_cate结构备份成功\r\n数据表zz_store_cate数据备份成功\r\n数据表zz_store_member结构备份成功\r\n数据表zz_store_member数据备份成功\r\n数据表zz_storehouse结构备份成功\r\n数据表zz_storehouse数据备份成功\r\n数据表zz_task结构备份成功\r\n数据表zz_task数据备份成功\r\n数据表zz_task_log结构备份成功\r\n数据表zz_task_log数据备份成功\r\n数据表zz_thirdparty_data结构备份成功\r\n数据表zz_thirdparty_data数据备份成功\r\n数据表zz_thirdparty_send结构备份成功\r\n数据表zz_thirdparty_send数据备份成功\r\n数据表zz_user结构备份成功\r\n数据表zz_user数据备份成功\r\n数据表zz_userlog结构备份成功\r\n数据表zz_userlog数据备份成功\r\n数据表zz_verify结构备份成功\r\n数据表zz_verify数据备份成功\r\n数据表zz_version结构备份成功\r\n数据表zz_version数据备份成功\r\n系统自动备份结束\r\n');

-- ----------------------------
-- Table structure for zz_thirdparty_data
-- ----------------------------
DROP TABLE IF EXISTS `zz_thirdparty_data`;
CREATE TABLE `zz_thirdparty_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `post` text CHARACTER SET utf8 COLLATE utf8_bin,
  `get` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ispc` tinyint(1) DEFAULT '0',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方平台数据接收表';

-- ----------------------------
-- Records of zz_thirdparty_data
-- ----------------------------

-- ----------------------------
-- Table structure for zz_thirdparty_send
-- ----------------------------
DROP TABLE IF EXISTS `zz_thirdparty_send`;
CREATE TABLE `zz_thirdparty_send` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `data` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ispc` tinyint(1) DEFAULT '0',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方平台数据发送表';

-- ----------------------------
-- Records of zz_thirdparty_send
-- ----------------------------

-- ----------------------------
-- Table structure for zz_user
-- ----------------------------
DROP TABLE IF EXISTS `zz_user`;
CREATE TABLE `zz_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(16) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` char(100) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `email` char(100) COLLATE utf8_bin DEFAULT NULL COMMENT '邮箱',
  `nickname` char(100) COLLATE utf8_bin DEFAULT NULL,
  `sex` tinyint(4) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 0 锁定',
  `login_num` int(11) DEFAULT '0' COMMENT '登录次数',
  `lastlogin_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `lastlogin_ip` char(100) COLLATE utf8_bin DEFAULT NULL COMMENT '最后登录ip',
  `content` char(100) COLLATE utf8_bin DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `verify` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '证验码',
  `role` int(11) DEFAULT NULL COMMENT '1平台管理员 2包装员 3门店管理员 4财务人员 5企业人员',
  `reg_time` int(11) DEFAULT NULL,
  `head` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `realname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `storeid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='后台用户表';

-- ----------------------------
-- Records of zz_user
-- ----------------------------
INSERT INTO `zz_user` VALUES ('1', 'admin', '27585d322833a77ce441625c4940efd7', 'abc@163.com', 'bajie', null, '1', '292', '1461411373', '0.0.0.0', '超级管理员', '1', 'NvxO14', '1', '1449536801', null, null, null, null);
INSERT INTO `zz_user` VALUES ('2', 'shuguo', 'b282353a02cca92d81ffc62ba0bffb25', 'test@o2o2o.me', 'test', null, '1', '217', '1460466533', '127.0.0.1', '', '2', 'vZiHVP', '1', '1444292478', null, null, null, null);

-- ----------------------------
-- Table structure for zz_userlog
-- ----------------------------
DROP TABLE IF EXISTS `zz_userlog`;
CREATE TABLE `zz_userlog` (
  `loginid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `username` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录帐号',
  `logintime` datetime NOT NULL COMMENT '登录时间',
  `loginip` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,1为登录成功，0为登录失败',
  `password` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '尝试错误密码',
  `info` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '其他说明',
  PRIMARY KEY (`loginid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='前台用户登录记录表';

-- ----------------------------
-- Records of zz_userlog
-- ----------------------------

-- ----------------------------
-- Table structure for zz_verify
-- ----------------------------
DROP TABLE IF EXISTS `zz_verify`;
CREATE TABLE `zz_verify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '手机号',
  `verify` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '验证码',
  `inputtime` int(15) DEFAULT NULL COMMENT '添加时间',
  `expiretime` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用 1使用 0未使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='验证码表';

-- ----------------------------
-- Records of zz_verify
-- ----------------------------

-- ----------------------------
-- Table structure for zz_version
-- ----------------------------
DROP TABLE IF EXISTS `zz_version`;
CREATE TABLE `zz_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1为安卓版，2为ios',
  `version` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `info` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '版本简介',
  `inputtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='版本更新表';

-- ----------------------------
-- Records of zz_version
-- ----------------------------
