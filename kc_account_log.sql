/*
Navicat MySQL Data Transfer

Source Server         : tpshop
Source Server Version : 50637
Source Host           : 120.79.176.96:3306
Source Database       : tpshop

Target Server Type    : MYSQL
Target Server Version : 50637
File Encoding         : 65001

Date: 2019-04-27 18:06:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for kc_account_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_account_log`;
CREATE TABLE `kc_account_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `al_deal_type` int(8) DEFAULT NULL COMMENT '交易类型 收入:1 支出:2',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_money` decimal(10,2) DEFAULT '0.00' COMMENT '用户金额',
  `frozen_money` decimal(10,2) DEFAULT '0.00' COMMENT '冻结金额',
  `pay_points` mediumint(9) DEFAULT '0' COMMENT '支付积分',
  `change_time` int(10) unsigned NOT NULL COMMENT '变动时间',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `type` tinyint(1) DEFAULT NULL COMMENT '商城消费 1 邀请好友 2 签到 3',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=690 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for kc_ad
-- ----------------------------
DROP TABLE IF EXISTS `kc_ad`;
CREATE TABLE `kc_ad` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '广告位置ID',
  `media_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '广告类型',
  `ad_name` varchar(60) NOT NULL DEFAULT '' COMMENT '广告名称',
  `ad_link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `ad_code` text NOT NULL COMMENT '图片地址',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '投放时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `link_man` varchar(60) NOT NULL DEFAULT '' COMMENT '添加人',
  `link_email` varchar(60) NOT NULL DEFAULT '' COMMENT '添加人邮箱',
  `link_phone` varchar(60) NOT NULL DEFAULT '' COMMENT '添加人联系电话',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `orderby` smallint(6) DEFAULT '50' COMMENT '排序',
  `target` tinyint(1) DEFAULT '0' COMMENT '是否开启浏览器新窗口',
  `bgcolor` varchar(20) DEFAULT NULL COMMENT '背景颜色',
  PRIMARY KEY (`ad_id`),
  KEY `enabled` (`enabled`) USING BTREE,
  KEY `position_id` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_ad
-- ----------------------------

-- ----------------------------
-- Table structure for kc_ad_position
-- ----------------------------
DROP TABLE IF EXISTS `kc_ad_position`;
CREATE TABLE `kc_ad_position` (
  `position_id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `position_name` varchar(60) NOT NULL DEFAULT '' COMMENT '广告位置名称',
  `ad_width` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '广告位宽度',
  `ad_height` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '广告位高度',
  `position_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '广告描述',
  `position_style` text COMMENT '模板',
  `is_open` tinyint(1) DEFAULT '0' COMMENT '0关闭1开启',
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=539 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_ad_position
-- ----------------------------
INSERT INTO `kc_ad_position` VALUES ('401', 'Goods页面自动增加广告位 401 ', '0', '0', 'Goods页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('9', 'Index页面自动增加广告位 9 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('400', 'Index页面自动增加广告位 400 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('301', 'Index页面自动增加广告位 301 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('302', 'Index页面自动增加广告位 302 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('300', 'Index页面自动增加广告位 300 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('303', 'Index页面自动增加广告位 303 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('304', 'Index页面自动增加广告位 304 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('305', 'Index页面自动增加广告位 305 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('306', 'Index页面自动增加广告位 306 ', '0', '0', 'Index页面', null, '1');
INSERT INTO `kc_ad_position` VALUES ('307', 'Index页面自动增加广告位 307 ', '0', '0', 'Index页面', null, '1');

-- ----------------------------
-- Table structure for kc_admin
-- ----------------------------
DROP TABLE IF EXISTS `kc_admin`;
CREATE TABLE `kc_admin` (
  `admin_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT 'email',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `ec_salt` varchar(10) DEFAULT NULL COMMENT '秘钥',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `last_login` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `nav_list` text COMMENT '权限',
  `lang_type` varchar(50) NOT NULL DEFAULT '' COMMENT 'lang_type',
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'agency_id',
  `suppliers_id` smallint(5) unsigned DEFAULT '0' COMMENT 'suppliers_id',
  `todolist` longtext COMMENT 'todolist',
  `role_id` smallint(5) DEFAULT '0' COMMENT '角色id',
  `province_id` int(8) unsigned DEFAULT '0' COMMENT '加盟商省级id',
  `city_id` int(8) unsigned DEFAULT '0' COMMENT '加盟商市级id',
  `district_id` int(8) unsigned DEFAULT '0' COMMENT '加盟商区级id',
  PRIMARY KEY (`admin_id`),
  KEY `user_name` (`user_name`) USING BTREE,
  KEY `agency_id` (`agency_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_admin
-- ----------------------------
INSERT INTO `kc_admin` VALUES ('1', 'admin', '', '519475228fe35ad067744465c42a19b2', null, '1545096114', '1556332815', '14.155.19.192', null, '', '0', '0', null, '1', '0', '0', '0');

-- ----------------------------
-- Table structure for kc_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_admin_log`;
CREATE TABLE `kc_admin_log` (
  `log_id` bigint(16) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `admin_id` int(10) DEFAULT NULL COMMENT '管理员id',
  `log_info` varchar(255) DEFAULT NULL COMMENT '日志描述',
  `log_ip` varchar(30) DEFAULT NULL COMMENT 'ip地址',
  `log_url` varchar(50) DEFAULT NULL COMMENT 'url',
  `log_time` int(10) DEFAULT NULL COMMENT '日志时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=621 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_admin_log
-- ----------------------------
INSERT INTO `kc_admin_log` VALUES ('616', '1', '后台登录', '0.0.0.0', '/index.php', '1556156851');
INSERT INTO `kc_admin_log` VALUES ('617', '1', '后台登录', '0.0.0.0', '/index.php', '1556239722');
INSERT INTO `kc_admin_log` VALUES ('618', '1', '后台登录', '14.155.19.192', '/index.php', '1556266850');
INSERT INTO `kc_admin_log` VALUES ('619', '1', '后台登录', '14.155.19.192', '/index.php', '1556269782');
INSERT INTO `kc_admin_log` VALUES ('620', '1', '后台登录', '14.155.19.192', '/index.php', '1556332815');

-- ----------------------------
-- Table structure for kc_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `kc_admin_role`;
CREATE TABLE `kc_admin_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) DEFAULT NULL COMMENT '角色名称',
  `act_list` text COMMENT '权限列表',
  `role_desc` varchar(255) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_admin_role
-- ----------------------------
INSERT INTO `kc_admin_role` VALUES ('1', '超级管理员', 'all', '管理全站');

-- ----------------------------
-- Table structure for kc_area_region
-- ----------------------------
DROP TABLE IF EXISTS `kc_area_region`;
CREATE TABLE `kc_area_region` (
  `shipping_area_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '物流配置id',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '地区id对应region表id',
  PRIMARY KEY (`shipping_area_id`,`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_area_region
-- ----------------------------

-- ----------------------------
-- Table structure for kc_article
-- ----------------------------
DROP TABLE IF EXISTS `kc_article`;
CREATE TABLE `kc_article` (
  `article_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `cat_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '类别ID',
  `cat_id2` smallint(5) DEFAULT '0' COMMENT '扩展类别ID',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` longtext NOT NULL COMMENT '文章内容',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '文章作者',
  `author_email` varchar(60) NOT NULL DEFAULT '' COMMENT '作者邮箱',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '文章类型',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'open_type',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `description` mediumtext COMMENT '文章摘要',
  `click` int(11) DEFAULT '0' COMMENT '浏览量',
  `publish_time` int(11) DEFAULT '0' COMMENT '文章发布时间',
  `thumb` varchar(255) DEFAULT '' COMMENT '文章缩略图',
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_article
-- ----------------------------
INSERT INTO `kc_article` VALUES ('10', '7', '0', '今天下雨了哦', '&lt;p&gt;阿西吧了&lt;/p&gt;', '', '', '', '2', '0', '1556343236', '', '0', '', '我是简单描述，拉啊啊啦', '1053', '1556380800', '');
INSERT INTO `kc_article` VALUES ('11', '7', '0', '昨天股票大跌', '&lt;p&gt;心情不了听说，亏了很多钱&lt;/p&gt;', '', '', '', '2', '1', '1556343286', '', '0', '', '垃圾了', '1008', '1556380800', '');
INSERT INTO `kc_article` VALUES ('12', '7', '0', '新品上线了', '&lt;p&gt;啦啦啦啦啦啦啦啦啦我我单位的分的分付付付付付付付&lt;/p&gt;', '', '', '', '2', '1', '1556343339', '', '0', '', '牛逼了', '1161', '1556380800', '');

-- ----------------------------
-- Table structure for kc_article_bak
-- ----------------------------
DROP TABLE IF EXISTS `kc_article_bak`;
CREATE TABLE `kc_article_bak` (
  `article_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '类别ID',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '文章作者',
  `author_email` varchar(60) NOT NULL DEFAULT '' COMMENT '作者邮箱',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示,1:显示;0:不显示',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `description` mediumtext COMMENT '文章摘要',
  `click` int(11) DEFAULT '0' COMMENT '浏览量',
  `publish_time` int(11) DEFAULT NULL COMMENT '文章预告发布时间',
  `thumb` varchar(255) DEFAULT '' COMMENT '文章缩略图',
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_article_bak
-- ----------------------------

-- ----------------------------
-- Table structure for kc_article_cat
-- ----------------------------
DROP TABLE IF EXISTS `kc_article_cat`;
CREATE TABLE `kc_article_cat` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `cat_name` varchar(20) DEFAULT NULL COMMENT '类别名称',
  `cat_type` smallint(6) DEFAULT '0' COMMENT '系统分组',
  `parent_id` smallint(6) DEFAULT NULL COMMENT '夫级ID',
  `show_in_nav` tinyint(1) DEFAULT '0' COMMENT '是否导航显示',
  `sort_order` smallint(6) DEFAULT '50' COMMENT '排序',
  `cat_desc` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `keywords` varchar(30) DEFAULT NULL COMMENT '搜索关键词',
  `cat_alias` varchar(20) DEFAULT NULL COMMENT '别名',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_article_cat
-- ----------------------------
INSERT INTO `kc_article_cat` VALUES ('7', '今日头条', '0', null, '0', '1', null, null, null);

-- ----------------------------
-- Table structure for kc_article_cat2
-- ----------------------------
DROP TABLE IF EXISTS `kc_article_cat2`;
CREATE TABLE `kc_article_cat2` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) DEFAULT NULL COMMENT '类别名称',
  `cat_type` smallint(6) DEFAULT '0' COMMENT '默认分组',
  `parent_id` smallint(6) DEFAULT '0' COMMENT '夫级ID',
  `show_in_nav` tinyint(1) DEFAULT '0' COMMENT '是否导航显示',
  `sort_order` smallint(6) DEFAULT '50' COMMENT '排序',
  `cat_desc` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `keywords` varchar(30) DEFAULT NULL COMMENT '搜索关键词',
  `cat_alias` varchar(20) DEFAULT NULL COMMENT '别名',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_article_cat2
-- ----------------------------

-- ----------------------------
-- Table structure for kc_article2
-- ----------------------------
DROP TABLE IF EXISTS `kc_article2`;
CREATE TABLE `kc_article2` (
  `article_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `cat_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '类别ID',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` longtext NOT NULL COMMENT '文章内容',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '文章作者',
  `author_email` varchar(60) NOT NULL DEFAULT '' COMMENT '作者邮箱',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '文章类型',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'open_type',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `description` mediumtext COMMENT '文章摘要',
  `click` int(11) DEFAULT '0' COMMENT '浏览量',
  `publish_time` int(11) DEFAULT '0' COMMENT '文章发布时间',
  `thumb` varchar(255) DEFAULT '' COMMENT '文章缩略图',
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_article2
-- ----------------------------

-- ----------------------------
-- Table structure for kc_bank
-- ----------------------------
DROP TABLE IF EXISTS `kc_bank`;
CREATE TABLE `kc_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `cardholder` varchar(255) DEFAULT NULL COMMENT '持有人',
  `bankname` varchar(255) NOT NULL COMMENT '银行名称',
  `banknum` varchar(255) DEFAULT NULL COMMENT '银行卡号',
  `bankplace` varchar(255) DEFAULT NULL COMMENT '银行支行',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否删除  否 0 是 1',
  `create_time` int(12) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(12) DEFAULT NULL COMMENT '更新时间',
  `is_default` varchar(255) DEFAULT '0' COMMENT '是否默认 0 否  1 是 ',
  `abbreviation` varchar(255) DEFAULT NULL COMMENT '银行卡标识',
  `place` varchar(255) DEFAULT NULL COMMENT '开户所在地地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_bank
-- ----------------------------

-- ----------------------------
-- Table structure for kc_banner
-- ----------------------------
DROP TABLE IF EXISTS `kc_banner`;
CREATE TABLE `kc_banner` (
  `b_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `b_name` varchar(255) NOT NULL DEFAULT '' COMMENT '链接名称',
  `goto_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `b_img` varchar(255) NOT NULL DEFAULT '' COMMENT '链接logo',
  `orderby` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `show_location` int(2) DEFAULT '1' COMMENT '显示位置 1 首页',
  PRIMARY KEY (`b_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_banner
-- ----------------------------
INSERT INTO `kc_banner` VALUES ('10', '第一张轮播图', 'pages/index/index', '/public/upload/link/2019/04-25/2152635968fbea90ce1ebccc892d3185.png', '1', '1', '1');
INSERT INTO `kc_banner` VALUES ('12', '第二张轮播图', 'pages/index/index', '/public/upload/link/2018/04-10/ba5c31a2f80beb49e0f2e2ada85b0f91.jpg', '0', '0', '1');

-- ----------------------------
-- Table structure for kc_brand
-- ----------------------------
DROP TABLE IF EXISTS `kc_brand`;
CREATE TABLE `kc_brand` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌表',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `logo` varchar(80) NOT NULL DEFAULT '' COMMENT '品牌logo',
  `desc` text NOT NULL COMMENT '品牌描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌地址',
  `sort` int(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `cat_name` varchar(128) DEFAULT '' COMMENT '品牌分类',
  `parent_cat_id` int(11) DEFAULT '0' COMMENT '分类id',
  `cat_id` int(10) DEFAULT '0' COMMENT '分类id',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_brand
-- ----------------------------

-- ----------------------------
-- Table structure for kc_cart
-- ----------------------------
DROP TABLE IF EXISTS `kc_cart`;
CREATE TABLE `kc_cart` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '购物车表',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `session_id` char(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'session',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品货号',
  `goods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `member_goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '会员折扣价',
  `goods_num` smallint(5) unsigned DEFAULT '0' COMMENT '购买数量',
  `item_id` int(11) DEFAULT '0' COMMENT '规格ID',
  `spec_key` varchar(64) DEFAULT '' COMMENT '商品规格key 对应sd_spec_goods_price 表',
  `spec_key_name` varchar(64) DEFAULT '' COMMENT '商品规格组合名称',
  `bar_code` varchar(64) DEFAULT '' COMMENT '商品条码',
  `selected` tinyint(1) DEFAULT '1' COMMENT '购物车选中状态',
  `add_time` int(11) DEFAULT '0' COMMENT '加入购物车的时间',
  `prom_type` tinyint(1) DEFAULT '0' COMMENT '0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠,7 搭配购',
  `prom_id` int(11) DEFAULT '0' COMMENT '活动id',
  `sku` varchar(128) DEFAULT '' COMMENT 'sku',
  `combination_group_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT ' 搭配购的组id/cart_id',
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `spec_key` (`spec_key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=299 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_cart
-- ----------------------------

-- ----------------------------
-- Table structure for kc_cells
-- ----------------------------
DROP TABLE IF EXISTS `kc_cells`;
CREATE TABLE `kc_cells` (
  `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT '细胞列表id',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `cell_num` int(16) DEFAULT NULL COMMENT '细胞编号',
  `create_time` int(11) DEFAULT NULL COMMENT '细胞创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `saving_time` varchar(255) DEFAULT NULL COMMENT '存储日期',
  `term_time` varchar(255) DEFAULT NULL COMMENT '存储期限',
  `saving_num` varchar(16) DEFAULT NULL COMMENT '存储编号',
  `insurance_num` varchar(255) DEFAULT NULL COMMENT '保单编号',
  `contract_num` int(11) DEFAULT NULL COMMENT '合同编号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `sex` tinyint(255) DEFAULT NULL COMMENT '性别 0 保密 1. 男 2. 女',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_cells
-- ----------------------------

-- ----------------------------
-- Table structure for kc_combination
-- ----------------------------
DROP TABLE IF EXISTS `kc_combination`;
CREATE TABLE `kc_combination` (
  `combination_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '上下架，0下，1上',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动有效起始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动有效截止时间',
  PRIMARY KEY (`combination_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组合促销表';

-- ----------------------------
-- Records of kc_combination
-- ----------------------------

-- ----------------------------
-- Table structure for kc_combination_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_combination_goods`;
CREATE TABLE `kc_combination_goods` (
  `combination_id` int(10) NOT NULL,
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `key_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格名称',
  `goods_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价/商城价',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠价格',
  `is_master` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1主0从'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组合促销商品映射关系表';

-- ----------------------------
-- Records of kc_combination_goods
-- ----------------------------

-- ----------------------------
-- Table structure for kc_comment
-- ----------------------------
DROP TABLE IF EXISTS `kc_comment`;
CREATE TABLE `kc_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT 'email邮箱',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `content` text NOT NULL COMMENT '评论内容',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户',
  `img` text COMMENT '晒单图片',
  `order_id` mediumint(8) DEFAULT '0' COMMENT '订单id',
  `deliver_rank` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '物流评价等级',
  `goods_rank` tinyint(1) DEFAULT '0' COMMENT '商品评价等级',
  `service_rank` tinyint(1) DEFAULT '0' COMMENT '商家服务态度评价等级',
  `zan_num` int(10) NOT NULL DEFAULT '0' COMMENT '被赞数',
  `zan_userid` varchar(255) NOT NULL DEFAULT '' COMMENT '点赞用户id',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名评价:0不是，1是',
  `rec_id` int(11) DEFAULT NULL COMMENT '订单商品表ID',
  `sort` int(4) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `id_value` (`goods_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_comment
-- ----------------------------

-- ----------------------------
-- Table structure for kc_config
-- ----------------------------
DROP TABLE IF EXISTS `kc_config`;
CREATE TABLE `kc_config` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(50) DEFAULT NULL COMMENT '配置的key键名',
  `value` varchar(512) DEFAULT NULL COMMENT '配置的val值',
  `inc_type` varchar(64) DEFAULT NULL COMMENT '配置分组',
  `desc` varchar(50) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=212 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_config
-- ----------------------------
INSERT INTO `kc_config` VALUES ('201', 'form_submit', 'ok', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('202', 'record_no', '', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('203', 'store_name', '课程小程序', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('204', 'store_desc', '', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('205', 'contact', '18319409222', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('206', 'phone', '0755-88888888', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('207', 'mobile', '18319409222', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('208', 'province', '0', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('209', 'city', '0', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('210', 'district', '0', 'shop_info', null);
INSERT INTO `kc_config` VALUES ('211', 'address', '', 'shop_info', null);

-- ----------------------------
-- Table structure for kc_coupon
-- ----------------------------
DROP TABLE IF EXISTS `kc_coupon`;
CREATE TABLE `kc_coupon` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '优惠券名字',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放类型 0下单赠送1 指定发放 2 免费领取 3线下发放',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券金额',
  `condition` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用条件',
  `createnum` int(11) DEFAULT '0' COMMENT '发放数量',
  `send_num` int(11) DEFAULT '0' COMMENT '已领取数量',
  `use_num` int(11) DEFAULT '0' COMMENT '已使用数量',
  `send_start_time` int(11) DEFAULT NULL COMMENT '发放开始时间',
  `send_end_time` int(11) DEFAULT NULL COMMENT '发放结束时间',
  `use_start_time` int(11) DEFAULT NULL COMMENT '使用开始时间',
  `use_end_time` int(11) DEFAULT NULL COMMENT '使用结束时间',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `status` int(2) DEFAULT NULL COMMENT '状态：1有效,2无效',
  `use_type` tinyint(1) DEFAULT '0' COMMENT '使用范围：0全店通用1指定商品可用2指定分类商品可用',
  PRIMARY KEY (`id`),
  KEY `use_end_time` (`use_end_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for kc_coupon_list
-- ----------------------------
DROP TABLE IF EXISTS `kc_coupon_list`;
CREATE TABLE `kc_coupon_list` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `cid` int(8) NOT NULL DEFAULT '0' COMMENT '优惠券 对应coupon表id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放类型 1 按订单发放 2 注册 3 邀请 4 按用户发放 5 所有人发放',
  `uid` int(8) NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(8) NOT NULL DEFAULT '0' COMMENT '订单id',
  `get_order_id` int(11) DEFAULT '0' COMMENT '优惠券来自订单ID',
  `use_time` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `code` varchar(10) DEFAULT '' COMMENT '优惠券兑换码',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发放时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '0未使用1已使用2已过期',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `code` (`code`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=177 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_coupon_list
-- ----------------------------

-- ----------------------------
-- Table structure for kc_delivery_doc
-- ----------------------------
DROP TABLE IF EXISTS `kc_delivery_doc`;
CREATE TABLE `kc_delivery_doc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '发货单ID',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_sn` varchar(64) NOT NULL DEFAULT '' COMMENT '订单编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `consignee` varchar(64) NOT NULL DEFAULT '' COMMENT '收货人',
  `zipcode` varchar(6) DEFAULT NULL COMMENT '邮编',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系手机',
  `country` int(11) unsigned NOT NULL COMMENT '国ID',
  `province` int(11) unsigned NOT NULL COMMENT '省ID',
  `city` int(11) unsigned NOT NULL COMMENT '市ID',
  `district` int(11) unsigned NOT NULL COMMENT '区ID',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `shipping_code` varchar(32) DEFAULT NULL COMMENT '物流code',
  `shipping_name` varchar(64) DEFAULT NULL COMMENT '快递名称',
  `shipping_price` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `invoice_no` varchar(255) DEFAULT '' COMMENT '物流单号',
  `tel` varchar(64) DEFAULT NULL COMMENT '座机电话',
  `note` text COMMENT '管理员添加的备注信息',
  `best_time` int(11) DEFAULT NULL COMMENT '友好收货时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `send_type` tinyint(1) DEFAULT '0' COMMENT '发货方式0自填快递1在线预约2电子面单3无需物流',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=utf8 COMMENT='发货单';

-- ----------------------------
-- Records of kc_delivery_doc
-- ----------------------------

-- ----------------------------
-- Table structure for kc_distribut_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_distribut_goods`;
CREATE TABLE `kc_distribut_goods` (
  `user_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `goods_price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `sales` int(11) DEFAULT NULL COMMENT '销量'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商销售表';

-- ----------------------------
-- Records of kc_distribut_goods
-- ----------------------------

-- ----------------------------
-- Table structure for kc_distribut_level
-- ----------------------------
DROP TABLE IF EXISTS `kc_distribut_level`;
CREATE TABLE `kc_distribut_level` (
  `level_id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `level_type` tinyint(1) DEFAULT '0' COMMENT '分销等级类别',
  `rate1` decimal(6,2) DEFAULT '0.00' COMMENT '一级佣金比例',
  `rate2` decimal(6,2) DEFAULT '0.00' COMMENT '二级佣金比例',
  `rate3` decimal(6,2) DEFAULT '0.00' COMMENT '三级佣金比例',
  `order_money` decimal(12,2) DEFAULT '0.00' COMMENT '升级条件',
  `level_name` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_distribut_level
-- ----------------------------

-- ----------------------------
-- Table structure for kc_examination
-- ----------------------------
DROP TABLE IF EXISTS `kc_examination`;
CREATE TABLE `kc_examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '体检记录明细id',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `pic` varchar(255) DEFAULT NULL COMMENT '体检照片',
  `num` int(16) DEFAULT NULL COMMENT '体检编号',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_examination
-- ----------------------------

-- ----------------------------
-- Table structure for kc_expense_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_expense_log`;
CREATE TABLE `kc_expense_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL COMMENT '操作管理员',
  `money` decimal(10,2) DEFAULT NULL COMMENT '支出金额',
  `integral` int(10) DEFAULT '0' COMMENT '赠送积分',
  `type` tinyint(1) DEFAULT '0' COMMENT '支出类型0用户提现,1订单退款,2其他,3注册,4邀请,5分享,6评论',
  `addtime` int(11) DEFAULT NULL COMMENT '日志记录时间',
  `log_type_id` int(11) DEFAULT '0' COMMENT '业务关联ID',
  `user_id` int(10) DEFAULT '0' COMMENT '涉及会员id',
  `user_name` int(10) DEFAULT '0' COMMENT '涉及用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=latin1 COMMENT='平台支出金额或赠送积分日志';

-- ----------------------------
-- Records of kc_expense_log
-- ----------------------------

-- ----------------------------
-- Table structure for kc_feedback
-- ----------------------------
DROP TABLE IF EXISTS `kc_feedback`;
CREATE TABLE `kc_feedback` (
  `msg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '默认自增ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复留言ID',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `msg_title` varchar(200) NOT NULL DEFAULT '' COMMENT '留言标题',
  `msg_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '留言类型',
  `msg_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '处理状态',
  `msg_content` text NOT NULL COMMENT '留言内容',
  `msg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '留言时间',
  `message_img` varchar(255) NOT NULL DEFAULT '',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_area` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for kc_flash_sale
-- ----------------------------
DROP TABLE IF EXISTS `kc_flash_sale`;
CREATE TABLE `kc_flash_sale` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '活动标题',
  `goods_id` int(10) NOT NULL COMMENT '参团商品ID',
  `item_id` bigint(20) DEFAULT '0' COMMENT '对应spec_goods_price商品规格id',
  `price` float(10,2) NOT NULL COMMENT '活动价格',
  `goods_num` int(10) DEFAULT '1' COMMENT '商品参加活动数',
  `buy_limit` int(11) NOT NULL DEFAULT '1' COMMENT '每人限购数',
  `buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '已购买人数',
  `order_num` int(10) DEFAULT '0' COMMENT '已下单数',
  `description` text COMMENT '活动描述',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `is_end` tinyint(1) DEFAULT '0' COMMENT '是否已结束',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_flash_sale
-- ----------------------------

-- ----------------------------
-- Table structure for kc_freight_config
-- ----------------------------
DROP TABLE IF EXISTS `kc_freight_config`;
CREATE TABLE `kc_freight_config` (
  `config_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置id',
  `first_unit` double(16,4) NOT NULL DEFAULT '0.0000' COMMENT '首(重：体积：件）',
  `first_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '首(重：体积：件）运费',
  `continue_unit` double(16,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '继续加（件：重量：体积）区间',
  `continue_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '继续加（件：重量：体积）的运费',
  `template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是默认运费配置.0不是，1是',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COMMENT='运费配置表';

-- ----------------------------
-- Records of kc_freight_config
-- ----------------------------
INSERT INTO `kc_freight_config` VALUES ('14', '1.0000', '10.00', '1.0000', '1.00', '7', '1');

-- ----------------------------
-- Table structure for kc_freight_region
-- ----------------------------
DROP TABLE IF EXISTS `kc_freight_region`;
CREATE TABLE `kc_freight_region` (
  `template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模板id',
  `config_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板配置ID',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'region表id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_freight_region
-- ----------------------------

-- ----------------------------
-- Table structure for kc_freight_template
-- ----------------------------
DROP TABLE IF EXISTS `kc_freight_template`;
CREATE TABLE `kc_freight_template` (
  `template_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '运费模板ID',
  `template_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '模板名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 件数；1 商品重量；2 商品体积',
  `is_enable_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用使用默认运费配置,0:不启用，1:启用',
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='运费模板表';

-- ----------------------------
-- Records of kc_freight_template
-- ----------------------------
INSERT INTO `kc_freight_template` VALUES ('7', '顺丰', '0', '1');

-- ----------------------------
-- Table structure for kc_friend_link
-- ----------------------------
DROP TABLE IF EXISTS `kc_friend_link`;
CREATE TABLE `kc_friend_link` (
  `link_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `link_name` varchar(255) NOT NULL DEFAULT '' COMMENT '链接名称',
  `link_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `link_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '链接logo',
  `orderby` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `target` tinyint(1) DEFAULT '1' COMMENT '是否新窗口打开',
  PRIMARY KEY (`link_id`),
  KEY `show_order` (`orderby`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_friend_link
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods`;
CREATE TABLE `kc_goods` (
  `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `extend_cat_id` int(11) DEFAULT '0' COMMENT '扩展分类id',
  `goods_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品编号',
  `goods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击数',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `store_count` smallint(5) unsigned NOT NULL DEFAULT '10' COMMENT '库存数量',
  `comment_count` smallint(5) DEFAULT '0' COMMENT '商品评论数',
  `weight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品重量克为单位',
  `volume` double(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '商品体积。单位立方米',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `cost_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品成本价',
  `price_ladder` text COMMENT '价格阶梯',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '商品关键词',
  `goods_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '商品简单描述',
  `goods_content` text COMMENT '商品详细描述',
  `mobile_content` text COMMENT '手机端商品详情',
  `original_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品上传原始图',
  `is_virtual` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为虚拟商品 1是，0否',
  `virtual_indate` int(11) DEFAULT '0' COMMENT '虚拟商品有效期',
  `virtual_limit` smallint(6) DEFAULT '0' COMMENT '虚拟商品购买上限',
  `virtual_refund` tinyint(1) DEFAULT '1' COMMENT '是否允许过期退款， 1是，0否',
  `virtual_sales_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟销售量',
  `virtual_collect_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟收藏量',
  `collect_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏量',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否包邮0否1是',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '50' COMMENT '商品排序',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否热卖',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品所属类型id，取值表goods_type的cat_id',
  `give_integral` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '购买商品赠送积分',
  `exchange_integral` int(10) NOT NULL DEFAULT '0' COMMENT '积分兑换：0不参与积分兑换，积分和现金的兑换比例见后台配置',
  `suppliers_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '供货商ID',
  `sales_sum` int(11) DEFAULT '0' COMMENT '商品销量',
  `prom_type` tinyint(1) DEFAULT '0' COMMENT '0默认1抢购2团购3优惠促销4预售5虚拟(5其实没用)6拼团7搭配购',
  `prom_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠活动id',
  `commission` decimal(10,2) DEFAULT '0.00' COMMENT '佣金用于分销分成',
  `spu` varchar(128) DEFAULT '' COMMENT 'SPU',
  `sku` varchar(128) DEFAULT '' COMMENT 'SKU',
  `template_id` int(11) unsigned DEFAULT '0' COMMENT '运费模板ID',
  `video` varchar(255) DEFAULT '' COMMENT '视频',
  `shuoming` varchar(255) DEFAULT NULL COMMENT '商品说明介绍',
  `music` varchar(255) DEFAULT '' COMMENT '音频',
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`) USING BTREE,
  KEY `cat_id` (`cat_id`) USING BTREE,
  KEY `last_update` (`last_update`) USING BTREE,
  KEY `brand_id` (`brand_id`) USING BTREE,
  KEY `goods_number` (`store_count`) USING BTREE,
  KEY `goods_weight` (`weight`) USING BTREE,
  KEY `sort_order` (`sort`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods
-- ----------------------------
INSERT INTO `kc_goods` VALUES ('243', '30', '0', 'TP0000243', '搞笑表演', '0', '0', '100', '0', '0', '0.0000', '0.05', '0.04', '0.03', '', '课程|视频', '商品简介111', '&lt;p&gt;这个是富文本的内容&lt;/p&gt;', null, '/public/upload/goods/2019/04-25/e467326d23833147d89ebf62bdff6a92.png', '0', '0', '0', '1', '0', '0', '0', '1', '1', '50', '0', '0', '0', '1556159530', '0', '0', '0', '0', '0', '0', '0', '0.00', '', '', '0', '/public/upload/goods/2019/04-25/1633a23a5dc3deb06ea9db0da6d12126.mp4', '商品说明哦', '');
INSERT INTO `kc_goods` VALUES ('244', '31', '0', 'TP0000244', '口才视频', '0', '0', '5', '0', '0', '0.0000', '0.10', '0.06', '0.01', '', '口才|测试', '口才视频二', '&lt;p&gt;富文本详情说明&lt;br/&gt;&lt;/p&gt;', null, '/public/upload/goods/2019/03-16/2963de3a012175fe1bef3c03732ad3cf.jpg', '0', '0', '0', '1', '0', '0', '0', '1', '1', '50', '0', '0', '0', '1556160268', '0', '0', '0', '0', '0', '0', '0', '0.00', '', '', '0', '/public/upload/goods/2019/04-25/847879653abbb60dc2d1ea50764584db.mp4', '这个是商品说明', '');
INSERT INTO `kc_goods` VALUES ('245', '30', '0', 'TP0000245', '搞笑表演2', '0', '0', '20', '0', '0', '0.0000', '0.10', '0.05', '0.03', '', '美国女子运动协会孕产期普拉提教练|Yoga Alliance E-RYT 200注册瑜伽师|普拉提Balanced Body 国际认证导师', '测试商品2', '&lt;p&gt;测试商品的详细内容，我是富文本&lt;/p&gt;', null, '/public/upload/goods/2019/04-25/40651278684f5e0bc1f5f43dbed5b330.jpg', '1', '0', '0', '1', '0', '0', '0', '1', '1', '50', '0', '0', '0', '1556355351', '0', '0', '0', '0', '0', '0', '0', '0.00', '', '', '0', '', '', '');
INSERT INTO `kc_goods` VALUES ('246', '31', '0', 'TP0000246', '什么都有的商品', '0', '0', '50', '0', '0', '0.0000', '0.09', '0.06', '0.03', '', '', '我有视频和音频哦', '&lt;p&gt;我测试一下音频有没有啊&lt;/p&gt;', null, '/public/upload/goods/2019/04-25/e467326d23833147d89ebf62bdff6a92.png', '1', '0', '0', '1', '0', '0', '0', '1', '0', '50', '0', '0', '0', '1556350574', '0', '0', '0', '0', '0', '0', '0', '0.00', '', '', '7', '/public/upload/goods/2019/04-26/7b8f631ff70b34f13afdfd7b5691ec05.mp4', '', '/public/upload/goods/2019/04-26/f65f91874bb3c172fd412b66ec6f6d15.mp3');

-- ----------------------------
-- Table structure for kc_goods_activity
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_activity`;
CREATE TABLE `kc_goods_activity` (
  `act_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `act_name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称',
  `act_desc` text NOT NULL COMMENT '活动描述',
  `act_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '活动类型:1预售2拼团',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '参加活动商品ID',
  `spec_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `start_time` int(10) unsigned NOT NULL COMMENT '活动开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '活动结束时间',
  `is_finished` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已结束:0,正常；1,成功结束；2，失败结束。',
  `ext_info` text NOT NULL COMMENT '活动扩展配置',
  `act_count` mediumint(8) NOT NULL DEFAULT '0' COMMENT '商品购买数',
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`,`act_type`,`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_activity
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_attr`;
CREATE TABLE `kc_goods_attr` (
  `goods_attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品属性id自增',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `attr_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '属性id',
  `attr_value` text NOT NULL COMMENT '属性值',
  `attr_price` varchar(255) NOT NULL DEFAULT '' COMMENT '属性价格',
  PRIMARY KEY (`goods_attr_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `attr_id` (`attr_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_attr
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_attribute
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_attribute`;
CREATE TABLE `kc_goods_attribute` (
  `attr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性id',
  `attr_name` varchar(60) NOT NULL DEFAULT '' COMMENT '属性名称',
  `type_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '属性分类id',
  `attr_index` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示0不显示1显示',
  `attr_values` text NOT NULL COMMENT '可选值列表',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '属性排序',
  PRIMARY KEY (`attr_id`),
  KEY `cat_id` (`type_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_category
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_category`;
CREATE TABLE `kc_goods_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类id',
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `mobile_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '手机端显示的商品分类名',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `parent_id_path` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '家族图谱',
  `level` tinyint(1) DEFAULT '0' COMMENT '等级 ',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50' COMMENT '顺序排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `image` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '分类图片',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否推荐为热门分类',
  `cat_group` tinyint(1) DEFAULT '0' COMMENT '分类分组默认0',
  `commission_rate` tinyint(1) DEFAULT '0' COMMENT '分佣比例',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_category
-- ----------------------------
INSERT INTO `kc_goods_category` VALUES ('30', '演讲', '演讲', '0', '0_30', '1', '1', '1', '', '0', '0', '0');
INSERT INTO `kc_goods_category` VALUES ('31', '口才', '口才', '0', '0_31', '1', '2', '1', '', '0', '0', '0');
INSERT INTO `kc_goods_category` VALUES ('32', '经济学', '经济学', '0', '0_32', '1', '3', '1', '', '0', '0', '0');

-- ----------------------------
-- Table structure for kc_goods_collect
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_collect`;
CREATE TABLE `kc_goods_collect` (
  `collect_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`collect_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_collect
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_consult
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_consult`;
CREATE TABLE `kc_goods_consult` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品咨询id',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品id',
  `username` varchar(32) CHARACTER SET utf8 DEFAULT '' COMMENT '网名',
  `add_time` int(11) DEFAULT '0' COMMENT '咨询时间',
  `consult_type` tinyint(1) DEFAULT '1' COMMENT '1 商品咨询 2 支付咨询 3 配送 4 售后',
  `content` varchar(1024) CHARACTER SET utf8 DEFAULT '' COMMENT '咨询内容',
  `parent_id` int(11) DEFAULT '0' COMMENT '父id 用于管理员回复',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `status` tinyint(1) DEFAULT '0' COMMENT '管理员回复状态，0未回复，1已回复',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_goods_consult
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_coupon
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_coupon`;
CREATE TABLE `kc_goods_coupon` (
  `coupon_id` int(8) NOT NULL COMMENT '优惠券id',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '指定的商品id：为零表示不指定商品',
  `goods_category_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '指定的商品分类：为零表示不指定分类',
  PRIMARY KEY (`coupon_id`,`goods_id`,`goods_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_goods_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_images
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_images`;
CREATE TABLE `kc_goods_images` (
  `img_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片id 自增',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `image_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_images
-- ----------------------------
INSERT INTO `kc_goods_images` VALUES ('996', '243', '/public/upload/goods/2019/04-25/e467326d23833147d89ebf62bdff6a92.png');
INSERT INTO `kc_goods_images` VALUES ('997', '244', '/public/upload/goods/2019/03-16/2963de3a012175fe1bef3c03732ad3cf.jpg');
INSERT INTO `kc_goods_images` VALUES ('998', '245', '/public/upload/goods/2019/04-25/40651278684f5e0bc1f5f43dbed5b330.jpg');
INSERT INTO `kc_goods_images` VALUES ('999', '246', '/public/upload/goods/2019/04-25/e467326d23833147d89ebf62bdff6a92.png');
INSERT INTO `kc_goods_images` VALUES ('1000', '246', '/public/upload/goods/2019/04-25/40651278684f5e0bc1f5f43dbed5b330.jpg');

-- ----------------------------
-- Table structure for kc_goods_type
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_type`;
CREATE TABLE `kc_goods_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id自增',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_goods_type
-- ----------------------------

-- ----------------------------
-- Table structure for kc_goods_visit
-- ----------------------------
DROP TABLE IF EXISTS `kc_goods_visit`;
CREATE TABLE `kc_goods_visit` (
  `visit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `visittime` int(11) NOT NULL COMMENT '浏览时间',
  `cat_id` int(11) NOT NULL COMMENT '商品分类ID',
  `extend_cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品扩展分类ID',
  PRIMARY KEY (`goods_id`,`user_id`,`visit_id`),
  KEY `visit_id` (`visit_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=897 DEFAULT CHARSET=utf8 COMMENT='商品浏览历史表';

-- ----------------------------
-- Records of kc_goods_visit
-- ----------------------------
INSERT INTO `kc_goods_visit` VALUES ('894', '246', '1', '1556356681', '31', '0');
INSERT INTO `kc_goods_visit` VALUES ('895', '243', '1', '1556355759', '30', '0');
INSERT INTO `kc_goods_visit` VALUES ('896', '245', '1', '1556357016', '30', '0');

-- ----------------------------
-- Table structure for kc_group_buy
-- ----------------------------
DROP TABLE IF EXISTS `kc_group_buy`;
CREATE TABLE `kc_group_buy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `item_id` bigint(20) DEFAULT '0' COMMENT '对应spec_goods_price商品规格id',
  `price` decimal(10,2) NOT NULL COMMENT '团购价格',
  `goods_num` int(10) DEFAULT '0' COMMENT '商品参团数',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品已购买数',
  `order_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已下单人数',
  `virtual_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟购买数',
  `rebate` decimal(10,1) NOT NULL COMMENT '折扣',
  `intro` text COMMENT '本团介绍',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品原价',
  `goods_name` varchar(200) NOT NULL DEFAULT '' COMMENT '商品名称',
  `recommended` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐 0.未推荐 1.已推荐',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `is_end` tinyint(1) DEFAULT '0' COMMENT '是否结束',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='团购商品表';

-- ----------------------------
-- Records of kc_group_buy
-- ----------------------------

-- ----------------------------
-- Table structure for kc_hijack
-- ----------------------------
DROP TABLE IF EXISTS `kc_hijack`;
CREATE TABLE `kc_hijack` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `hijack_url` varchar(255) DEFAULT NULL COMMENT '劫持URL',
  `page_url` varchar(255) DEFAULT NULL COMMENT '发生页面url',
  `add_time` int(15) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_hijack
-- ----------------------------

-- ----------------------------
-- Table structure for kc_industry_template
-- ----------------------------
DROP TABLE IF EXISTS `kc_industry_template`;
CREATE TABLE `kc_industry_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `industry_id` int(11) unsigned NOT NULL COMMENT '行业id',
  `style_id` int(11) unsigned NOT NULL COMMENT '风格id',
  `template_name` varchar(64) NOT NULL COMMENT '模板名称',
  `template_html` longtext NOT NULL COMMENT '保存编辑后的HTML',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `block_info` longtext NOT NULL COMMENT '接口数据',
  `thumb` varchar(255) DEFAULT NULL COMMENT '图片展示',
  `code_url` varchar(255) DEFAULT NULL COMMENT '二维码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_industry_template
-- ----------------------------

-- ----------------------------
-- Table structure for kc_integral_order
-- ----------------------------
DROP TABLE IF EXISTS `kc_integral_order`;
CREATE TABLE `kc_integral_order` (
  `order_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `order_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '手机',
  `shipping_code` varchar(32) NOT NULL DEFAULT '' COMMENT '物流code',
  `shipping_name` varchar(120) NOT NULL DEFAULT '' COMMENT '物流名称',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  `shipping_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `integral` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用积分',
  `integral_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用积分抵多少钱',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付款金额',
  `total_amount` decimal(10,2) DEFAULT '0.00' COMMENT '订单总价',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `shipping_time` int(11) DEFAULT '0' COMMENT '最后新发货时间',
  `confirm_time` int(10) DEFAULT '0' COMMENT '收货确认时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格调整',
  `user_note` varchar(255) NOT NULL DEFAULT '' COMMENT '用户备注',
  `admin_note` varchar(255) DEFAULT '' COMMENT '管理员备注',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户假删除标识,1:删除,0未删除',
  `goods_id` int(8) DEFAULT NULL COMMENT '商品id',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_integral_order
-- ----------------------------

-- ----------------------------
-- Table structure for kc_invoice
-- ----------------------------
DROP TABLE IF EXISTS `kc_invoice`;
CREATE TABLE `kc_invoice` (
  `invoice_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL COMMENT '订单id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `invoice_type` tinyint(1) DEFAULT '0' COMMENT '0普通发票1电子发票2增值税发票',
  `invoice_money` decimal(10,2) DEFAULT '0.00' COMMENT '发票金额',
  `invoice_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '发票抬头',
  `invoice_desc` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '发票内容',
  `invoice_rate` decimal(10,4) DEFAULT NULL COMMENT '发票税率',
  `taxpayer` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '纳税人识别号',
  `status` tinyint(1) DEFAULT '0' COMMENT '发票状态0待开1已开2作废',
  `atime` int(11) DEFAULT '0' COMMENT '开票时间',
  `ctime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=301 DEFAULT CHARSET=latin1 COMMENT='发票信息表';

-- ----------------------------
-- Records of kc_invoice
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_access
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_access`;
CREATE TABLE `kc_kf_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`) USING BTREE,
  KEY `nodeId` (`node_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_access
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_admin
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_admin`;
CREATE TABLE `kc_kf_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(155) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` smallint(6) unsigned NOT NULL COMMENT '组ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1:启用 0:禁止',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `last_login_time` int(11) unsigned NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  `last_location` varchar(100) DEFAULT NULL COMMENT '最后登录位置',
  `storeid` int(11) unsigned NOT NULL COMMENT '企业id（店铺id）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_admin
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_answer
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_answer`;
CREATE TABLE `kc_kf_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `que_id` int(11) unsigned NOT NULL COMMENT '问题id',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_answer
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_attr_question
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_attr_question`;
CREATE TABLE `kc_kf_attr_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '问题分类名称',
  `pid` int(11) unsigned NOT NULL COMMENT '父分类id',
  `storeid` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '所属店铺id',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_attr_question
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_chatlog
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_chatlog`;
CREATE TABLE `kc_kf_chatlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` varchar(55) NOT NULL COMMENT '网页用户随机编号(仅为记录参考记录)',
  `kefu_id` varchar(55) NOT NULL COMMENT '客服的id',
  `content` text NOT NULL COMMENT '发送的内容',
  `timeline` int(10) NOT NULL COMMENT '记录时间',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0：未删除 1：已删除',
  `need_send` tinyint(1) DEFAULT '0' COMMENT '0 不需要推送 1 需要推送',
  `from_name` varchar(155) NOT NULL DEFAULT '' COMMENT '消息来源用户名',
  `to_name` varchar(155) NOT NULL DEFAULT '' COMMENT '接收消息用户名',
  `storeid` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '店铺id',
  `store_name` varchar(50) NOT NULL COMMENT '客服所属店铺名称',
  PRIMARY KEY (`id`),
  KEY `fromid` (`from_id`(4)) USING BTREE,
  KEY `toid` (`kefu_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_chatlog
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_kefu
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_kefu`;
CREATE TABLE `kc_kf_kefu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(155) DEFAULT NULL,
  `pwd` varchar(155) DEFAULT NULL COMMENT '密码',
  `sign` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0下线 1在线',
  `storeid` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '店铺id，默认1',
  `Auditing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否审核  0：待审核  1：审核通过  2：审核不通过',
  `store_name` varchar(50) NOT NULL COMMENT '客服所属店铺名称',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0：未删除 1：已删除',
  `role` smallint(6) unsigned NOT NULL DEFAULT '5' COMMENT '组ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_kefu
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_node
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_node`;
CREATE TABLE `kc_kf_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `title` varchar(50) NOT NULL COMMENT '菜单名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否激活 1：是 2：否',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '节点等级',
  `data` varchar(255) DEFAULT NULL COMMENT '附加参数',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '菜单显示类型 0:不显示 1:导航菜单 2:左侧菜单',
  PRIMARY KEY (`id`),
  KEY `level` (`level`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_node
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_question
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_question`;
CREATE TABLE `kc_kf_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `link` varchar(255) DEFAULT NULL COMMENT '连接',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用 0 ：不启用  1：启用',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `storeid` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '店铺id',
  `is_host` tinyint(1) unsigned NOT NULL COMMENT '是否热门  0：否 1：是',
  `is_common` tinyint(1) unsigned NOT NULL COMMENT '是否常见 0：否 1：是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='问题分类表';

-- ----------------------------
-- Records of kc_kf_question
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_robot
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_robot`;
CREATE TABLE `kc_kf_robot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `robot_name` varchar(32) NOT NULL COMMENT '名称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `storeid` int(11) unsigned NOT NULL COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_robot
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_role
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_role`;
CREATE TABLE `kc_kf_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '后台组名',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '是否激活 1：是 0：否',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_role
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_role_user
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_role_user`;
CREATE TABLE `kc_kf_role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` smallint(6) unsigned NOT NULL,
  KEY `group_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_slogan
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_slogan`;
CREATE TABLE `kc_kf_slogan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '提示语id主键',
  `slogan` varchar(55) DEFAULT NULL COMMENT '标语（客服加载欢迎语）',
  `slogan_status` tinyint(1) unsigned DEFAULT '1' COMMENT '提示语状态  0：不开启  1：开启',
  `auditing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否审核提示语  0：待审核  1：审核通过  2：审核不通过',
  `timeline` int(11) unsigned DEFAULT NULL COMMENT '提示语设置时间',
  `storeid` int(11) unsigned NOT NULL COMMENT '提示语所属店铺id',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0：未删除 1：已删除',
  `kefuid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客服id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_slogan
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_store
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_store`;
CREATE TABLE `kc_kf_store` (
  `storeid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `store_name` varchar(55) NOT NULL COMMENT '店铺名称',
  `avatar` varchar(255) NOT NULL COMMENT '店铺头像',
  `auditing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否审核  0：待审核  1：审核通过  2：审核不通过',
  `timeline` int(11) unsigned DEFAULT NULL COMMENT '提示语设置时间',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0：未删除 1：已删除',
  `webid` varchar(255) NOT NULL COMMENT '网站域名',
  `phone` char(11) NOT NULL COMMENT '企业电话',
  `city` varchar(255) NOT NULL COMMENT '企业地址',
  `email` varchar(255) NOT NULL COMMENT '企业邮箱',
  PRIMARY KEY (`storeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_store
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_suggest
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_suggest`;
CREATE TABLE `kc_kf_suggest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户意见反馈主键id',
  `storeid` int(11) unsigned NOT NULL COMMENT '店铺id',
  `kehuid` varchar(255) NOT NULL COMMENT '客户id',
  `is_satisfied` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '满意度 0：很不满意  1：不满意 2：一般 3：满意 4：非常满意',
  `suggest` varchar(255) DEFAULT NULL COMMENT '建议',
  `timeline` int(11) unsigned DEFAULT NULL COMMENT '反馈时间',
  `is_delete` tinyint(1) unsigned NOT NULL COMMENT '是否删除  0：未删除   1：已删除',
  `kefu_id` int(11) unsigned NOT NULL COMMENT '客服id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户意见表';

-- ----------------------------
-- Records of kc_kf_suggest
-- ----------------------------

-- ----------------------------
-- Table structure for kc_kf_weixin_merchant
-- ----------------------------
DROP TABLE IF EXISTS `kc_kf_weixin_merchant`;
CREATE TABLE `kc_kf_weixin_merchant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '联关v1_store表主键',
  `storeid` int(11) DEFAULT NULL,
  `wx_type` tinyint(1) DEFAULT '0' COMMENT '众公号类型',
  `wx_url` varchar(100) DEFAULT NULL,
  `wx_token` varchar(50) DEFAULT NULL,
  `wx_EncodingAESKey` varchar(50) DEFAULT NULL COMMENT '消息加密密钥',
  `wx_raw_id` varchar(30) DEFAULT NULL COMMENT '微信原始ID',
  `wx_AppId` varchar(20) DEFAULT NULL,
  `wx_AppSecret` varchar(50) DEFAULT NULL,
  `wx_Random` tinyint(1) DEFAULT '0' COMMENT '是否随机回复',
  `wx_Subscribe` text COMMENT '关注后的回复',
  `wx_NoneReply` text COMMENT '无匹配时的回复',
  `media_id` varchar(255) DEFAULT NULL COMMENT '关注回复',
  `media_id2` varchar(255) DEFAULT NULL COMMENT '无匹配回复',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_kf_weixin_merchant
-- ----------------------------

-- ----------------------------
-- Table structure for kc_menu_cfg
-- ----------------------------
DROP TABLE IF EXISTS `kc_menu_cfg`;
CREATE TABLE `kc_menu_cfg` (
  `menu_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(100) NOT NULL DEFAULT '' COMMENT '自定义名称',
  `default_name` varchar(100) NOT NULL DEFAULT '' COMMENT '默认名称',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `is_tab` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否切块',
  `menu_url` varchar(255) NOT NULL DEFAULT '' COMMENT '手机端url',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_menu_cfg
-- ----------------------------

-- ----------------------------
-- Table structure for kc_message
-- ----------------------------
DROP TABLE IF EXISTS `kc_message`;
CREATE TABLE `kc_message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '管理者id',
  `message` text NOT NULL COMMENT '站内信内容',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '个体消息：0，全体消息1',
  `category` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT ' 系统消息：0，活动消息：1',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `data` text COMMENT '消息序列化内容',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_message
-- ----------------------------

-- ----------------------------
-- Table structure for kc_message_activity
-- ----------------------------
DROP TABLE IF EXISTS `kc_message_activity`;
CREATE TABLE `kc_message_activity` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_title` varchar(255) NOT NULL COMMENT '消息标题',
  `message_content` text COMMENT '消息内容',
  `img_uri` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `mmt_code` varchar(50) NOT NULL COMMENT '用户消息模板编号',
  `prom_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1抢购2团购3优惠促销4预售5虚拟6拼团7搭配购8自定义图文消息9订单促销',
  `prom_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动消息表';

-- ----------------------------
-- Records of kc_message_activity
-- ----------------------------

-- ----------------------------
-- Table structure for kc_message_logistics
-- ----------------------------
DROP TABLE IF EXISTS `kc_message_logistics`;
CREATE TABLE `kc_message_logistics` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_title` varchar(255) DEFAULT NULL COMMENT '消息标题',
  `message_content` text NOT NULL COMMENT '消息内容',
  `img_uri` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `order_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '单号',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '物流订单id',
  `mmt_code` varchar(50) DEFAULT NULL COMMENT '用户消息模板编号',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '1到货通知2发货提醒3签收提醒4评价提醒5退货提醒6退款提醒7虚拟商品',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物流消息表';

-- ----------------------------
-- Records of kc_message_logistics
-- ----------------------------

-- ----------------------------
-- Table structure for kc_message_notice
-- ----------------------------
DROP TABLE IF EXISTS `kc_message_notice`;
CREATE TABLE `kc_message_notice` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '个体消息：0，全体消息:1',
  `message_title` varchar(255) DEFAULT NULL COMMENT '消息标题',
  `message_content` text NOT NULL COMMENT '消息内容',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `mmt_code` varchar(50) DEFAULT NULL COMMENT '用户消息模板编号',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0系统公告1降价通知2优惠券到账提醒3优惠券使用提醒4优惠券即将过期提醒5预售订单尾款支付提醒6提现到账提醒',
  `prom_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='通知消息表';

-- ----------------------------
-- Records of kc_message_notice
-- ----------------------------

-- ----------------------------
-- Table structure for kc_message_private
-- ----------------------------
DROP TABLE IF EXISTS `kc_message_private`;
CREATE TABLE `kc_message_private` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_content` text NOT NULL COMMENT '消息内容',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `send_user_id` mediumint(8) unsigned NOT NULL COMMENT '发送者',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='私信消息表';

-- ----------------------------
-- Records of kc_message_private
-- ----------------------------

-- ----------------------------
-- Table structure for kc_mobile_block_info
-- ----------------------------
DROP TABLE IF EXISTS `kc_mobile_block_info`;
CREATE TABLE `kc_mobile_block_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL COMMENT '所属板块id',
  `block_type` int(8) unsigned NOT NULL COMMENT '板块类型',
  `title` varchar(120) DEFAULT NULL COMMENT '标题、描述、文字内容',
  `block_content` varchar(255) DEFAULT NULL COMMENT '其它信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_mobile_block_info
-- ----------------------------

-- ----------------------------
-- Table structure for kc_mobile_template
-- ----------------------------
DROP TABLE IF EXISTS `kc_mobile_template`;
CREATE TABLE `kc_mobile_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否设为首页 0否 1是',
  `template_name` varchar(64) NOT NULL COMMENT '模板名称',
  `template_html` longtext NOT NULL COMMENT '保存编辑后的HTML',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 0不显示  1显示',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `block_info` longtext NOT NULL COMMENT '接口数据',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '模板类型 0内页  1首页',
  `thumb` varchar(64) DEFAULT NULL COMMENT '模板缩略图',
  `style_id` int(11) DEFAULT '0' COMMENT '从模板库中添加风格id，',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_mobile_template
-- ----------------------------

-- ----------------------------
-- Table structure for kc_navigation
-- ----------------------------
DROP TABLE IF EXISTS `kc_navigation`;
CREATE TABLE `kc_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '前台导航表',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '导航名称',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `is_new` tinyint(1) DEFAULT '1' COMMENT '是否新窗口',
  `sort` smallint(6) DEFAULT '50' COMMENT '排序',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '链接地址',
  `position` enum('top','bottom') CHARACTER SET latin1 NOT NULL DEFAULT 'top' COMMENT '菜单位置，top顶部，bottom底部',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_navigation
-- ----------------------------

-- ----------------------------
-- Table structure for kc_news
-- ----------------------------
DROP TABLE IF EXISTS `kc_news`;
CREATE TABLE `kc_news` (
  `article_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '类别ID',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `tags` char(64) DEFAULT NULL COMMENT '新闻标签',
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '文章作者',
  `author_email` varchar(60) NOT NULL DEFAULT '' COMMENT '作者邮箱',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `description` mediumtext COMMENT '文章摘要',
  `click` int(11) DEFAULT '0' COMMENT '浏览量',
  `publish_time` int(11) DEFAULT NULL COMMENT '文章预告发布时间',
  `thumb` varchar(255) DEFAULT '' COMMENT '文章缩略图',
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_news
-- ----------------------------

-- ----------------------------
-- Table structure for kc_news_cat
-- ----------------------------
DROP TABLE IF EXISTS `kc_news_cat`;
CREATE TABLE `kc_news_cat` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) DEFAULT NULL COMMENT '类别名称',
  `cat_type` smallint(6) DEFAULT '0' COMMENT '默认分组',
  `parent_id` smallint(6) DEFAULT '0' COMMENT '夫级ID',
  `show_in_nav` tinyint(1) DEFAULT '0' COMMENT '是否导航显示',
  `sort_order` smallint(6) DEFAULT '50' COMMENT '排序',
  `cat_desc` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `keywords` varchar(30) DEFAULT NULL COMMENT '搜索关键词',
  `cat_alias` varchar(20) DEFAULT NULL COMMENT '别名',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_news_cat
-- ----------------------------

-- ----------------------------
-- Table structure for kc_oauth_users
-- ----------------------------
DROP TABLE IF EXISTS `kc_oauth_users`;
CREATE TABLE `kc_oauth_users` (
  `tu_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表自增ID',
  `user_id` mediumint(8) NOT NULL COMMENT '用户表ID',
  `openid` varchar(255) NOT NULL COMMENT '第三方开放平台openid',
  `oauth` varchar(50) NOT NULL COMMENT '第三方授权平台',
  `unionid` varchar(255) DEFAULT NULL COMMENT 'unionid',
  `oauth_child` varchar(50) DEFAULT NULL COMMENT 'mp标识来自公众号, open标识来自开放平台,用于标识来自哪个第三方授权平台, 因为同是微信平台有来自公众号和开放平台',
  `nick_name` varchar(64) DEFAULT NULL COMMENT '绑定时的昵称',
  PRIMARY KEY (`tu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_oauth_users
-- ----------------------------

-- ----------------------------
-- Table structure for kc_order
-- ----------------------------
DROP TABLE IF EXISTS `kc_order`;
CREATE TABLE `kc_order` (
  `order_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `order_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态 待确定0 已确定1 已发货2 已取消3 已完成4 已作废5',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态 0未发货 1 已发货 2部分发货',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 未支付0 已支付 1  已退款2 拒绝退款3',
  `consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人',
  `country` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '国家',
  `province` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '省份',
  `city` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '城市',
  `district` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '县区',
  `twon` int(11) DEFAULT '0' COMMENT '乡镇',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `zipcode` varchar(60) NOT NULL DEFAULT '' COMMENT '邮政编码',
  `mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '邮件',
  `shipping_code` varchar(32) NOT NULL DEFAULT '' COMMENT '物流code',
  `shipping_name` varchar(120) NOT NULL DEFAULT '' COMMENT '物流名称',
  `pay_code` varchar(32) NOT NULL DEFAULT '' COMMENT '支付code',
  `pay_name` varchar(120) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `invoice_title` varchar(256) DEFAULT '' COMMENT '发票抬头',
  `taxpayer` varchar(30) DEFAULT '' COMMENT '纳税人识别号',
  `invoice_desc` varchar(30) DEFAULT NULL COMMENT '发票内容',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  `shipping_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用余额',
  `coupon_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券抵扣',
  `integral` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用积分',
  `integral_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用积分抵多少钱',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付款金额',
  `total_amount` decimal(10,2) DEFAULT '0.00' COMMENT '订单总价',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `shipping_time` int(11) DEFAULT '0' COMMENT '最后新发货时间',
  `confirm_time` int(10) DEFAULT '0' COMMENT '收货确认时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT '第三方平台交易流水号',
  `prom_id` int(11) unsigned DEFAULT '0' COMMENT '活动ID',
  `prom_type` tinyint(4) unsigned DEFAULT '0' COMMENT '订单类型：0普通订单4预售订单5虚拟订单6拼团订单',
  `order_prom_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '活动id',
  `order_prom_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活动优惠金额',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格调整',
  `user_note` varchar(255) NOT NULL DEFAULT '' COMMENT '用户备注',
  `admin_note` varchar(255) DEFAULT '' COMMENT '管理员备注',
  `parent_sn` varchar(100) DEFAULT NULL COMMENT '父单单号',
  `is_distribut` tinyint(1) DEFAULT '0' COMMENT '是否已分成0未分成1已分成',
  `paid_money` decimal(10,2) DEFAULT '0.00' COMMENT '订金',
  `shop_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '自提点门店id',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户假删除标识,1:删除,0未删除',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1270 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_order
-- ----------------------------

-- ----------------------------
-- Table structure for kc_order_action
-- ----------------------------
DROP TABLE IF EXISTS `kc_order_action`;
CREATE TABLE `kc_order_action` (
  `action_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `action_user` int(11) DEFAULT '0' COMMENT '操作人 0 为用户操作，其他为管理员id',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配送状态',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `action_note` varchar(255) NOT NULL DEFAULT '' COMMENT '操作备注',
  `log_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `status_desc` varchar(255) DEFAULT NULL COMMENT '状态描述',
  PRIMARY KEY (`action_id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2217 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_order_action
-- ----------------------------

-- ----------------------------
-- Table structure for kc_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_order_goods`;
CREATE TABLE `kc_order_goods` (
  `rec_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id自增',
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品货号',
  `goods_num` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '购买数量',
  `final_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品实际购买价',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `cost_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品成本价',
  `member_goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '会员折扣价',
  `give_integral` mediumint(8) unsigned DEFAULT '0' COMMENT '购买商品赠送积分',
  `item_id` int(10) unsigned DEFAULT NULL COMMENT '商品规格id',
  `spec_key` varchar(128) DEFAULT '' COMMENT '商品规格key',
  `spec_key_name` varchar(128) DEFAULT '' COMMENT '规格对应的中文名字',
  `bar_code` varchar(64) NOT NULL DEFAULT '' COMMENT '条码',
  `is_comment` tinyint(1) DEFAULT '0' COMMENT '是否评价',
  `prom_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠,4预售',
  `prom_id` int(11) unsigned DEFAULT '0' COMMENT '活动id',
  `is_send` tinyint(1) DEFAULT '0' COMMENT '0未发货，1已发货，2已换货，3已退货',
  `delivery_id` int(11) DEFAULT '0' COMMENT '发货单ID',
  `sku` varchar(128) DEFAULT '' COMMENT 'sku',
  `shop_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品的原价',
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1067 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for kc_payment
-- ----------------------------
DROP TABLE IF EXISTS `kc_payment`;
CREATE TABLE `kc_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `pay_code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付code',
  `pay_name` varchar(120) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_fee` varchar(10) NOT NULL DEFAULT '' COMMENT '手续费',
  `pay_desc` text NOT NULL COMMENT '描述',
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'pay_coder',
  `pay_config` text NOT NULL COMMENT '配置',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否货到付款',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在线支付',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_payment
-- ----------------------------

-- ----------------------------
-- Table structure for kc_pick_up
-- ----------------------------
DROP TABLE IF EXISTS `kc_pick_up`;
CREATE TABLE `kc_pick_up` (
  `pickup_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自提点id',
  `pickup_name` varchar(255) NOT NULL DEFAULT '' COMMENT '自提点名称',
  `pickup_pic` varchar(255) DEFAULT NULL COMMENT '门店头像',
  `pickup_details` text COMMENT '门店简介',
  `pickup_address` varchar(255) NOT NULL DEFAULT '' COMMENT '自提点地址',
  `pickup_phone` varchar(30) NOT NULL DEFAULT '' COMMENT '自提点电话',
  `pickup_contact` varchar(20) NOT NULL DEFAULT '' COMMENT '自提点联系人',
  `province_id` int(11) NOT NULL COMMENT '省id',
  `city_id` int(11) NOT NULL COMMENT '市id',
  `district_id` int(11) NOT NULL COMMENT '区id',
  `longitude` decimal(10,7) DEFAULT '0.0000000' COMMENT '经度',
  `latitude` decimal(10,7) DEFAULT '0.0000000' COMMENT '纬度',
  `open` tinyint(2) DEFAULT '0' COMMENT '营业开始时间',
  `close` tinyint(2) DEFAULT '0' COMMENT '营业打烊时间',
  `suppliersid` int(11) NOT NULL COMMENT '供应商id',
  PRIMARY KEY (`pickup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='自提点表';

-- ----------------------------
-- Records of kc_pick_up
-- ----------------------------

-- ----------------------------
-- Table structure for kc_plugin
-- ----------------------------
DROP TABLE IF EXISTS `kc_plugin`;
CREATE TABLE `kc_plugin` (
  `code` varchar(13) DEFAULT NULL COMMENT '插件编码',
  `name` varchar(55) DEFAULT NULL COMMENT '中文名字',
  `version` varchar(255) DEFAULT NULL COMMENT '插件的版本',
  `author` varchar(30) DEFAULT NULL COMMENT '插件作者',
  `config` text COMMENT '配置信息',
  `config_value` text COMMENT '配置值信息',
  `desc` varchar(255) DEFAULT NULL COMMENT '插件描述',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否启用',
  `type` varchar(50) DEFAULT NULL COMMENT '插件类型 payment支付 login 登陆 shipping物流',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `bank_code` text COMMENT '网银配置信息',
  `scene` tinyint(1) DEFAULT '0' COMMENT '使用场景 0PC+手机 1手机 2PC 3APP 4小程序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for kc_poster
-- ----------------------------
DROP TABLE IF EXISTS `kc_poster`;
CREATE TABLE `kc_poster` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `poster_name` char(10) DEFAULT '' COMMENT '海报名称',
  `canvas_width` int(5) DEFAULT '0' COMMENT '画布宽度',
  `canvas_height` int(5) DEFAULT '0' COMMENT '画布高度',
  `poster_width` int(5) DEFAULT '0' COMMENT '海报宽度',
  `poster_height` int(5) DEFAULT '0' COMMENT '海报高度',
  `back_url` varchar(255) DEFAULT NULL COMMENT '海报路径',
  `canvas_x` int(5) DEFAULT '0' COMMENT '画布x轴',
  `canvas_y` int(5) DEFAULT '0' COMMENT '画布y轴',
  `enabled` tinyint(1) DEFAULT '0' COMMENT '是否启用 ： 0 = 未启用，1 = 已启用',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义海报';

-- ----------------------------
-- Records of kc_poster
-- ----------------------------

-- ----------------------------
-- Table structure for kc_pre_sell
-- ----------------------------
DROP TABLE IF EXISTS `kc_pre_sell`;
CREATE TABLE `kc_pre_sell` (
  `pre_sell_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '预售id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商品名称',
  `item_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '规格id',
  `item_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '规格名称',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '预售标题',
  `desc` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '预售描述',
  `deposit_goods_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订购商品数',
  `deposit_order_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订购订单数',
  `stock_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '预售库存',
  `sell_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `sell_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `pay_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '尾款支付开始时间',
  `pay_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '尾款支付结束时间',
  `deposit_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订金',
  `price_ladder` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '价格阶梯。预定人数达到多少个时，价格为多少钱',
  `delivery_time_desc` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开始发货时间描述',
  `is_finished` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已结束:0,正常；1，结束（待处理）；2,成功结束；3，失败结束。',
  PRIMARY KEY (`pre_sell_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_pre_sell
-- ----------------------------

-- ----------------------------
-- Table structure for kc_prom_coupon
-- ----------------------------
DROP TABLE IF EXISTS `kc_prom_coupon`;
CREATE TABLE `kc_prom_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '领券明细id',
  `poid` int(11) NOT NULL COMMENT '券id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `add_time` int(11) NOT NULL COMMENT '领券时间',
  `use_status` tinyint(1) DEFAULT '0' COMMENT '使用状态 0 未使用 1 使用 2 已过期',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '券码状态 有效 1 无效 2',
  `order_id` int(12) DEFAULT NULL COMMENT '订单号',
  `use_time` int(11) DEFAULT NULL,
  `use_num` int(11) DEFAULT NULL COMMENT '使用数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_prom_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for kc_prom_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_prom_goods`;
CREATE TABLE `kc_prom_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '促销活动名称',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '促销类型',
  `expression` varchar(100) NOT NULL DEFAULT '' COMMENT '优惠体现',
  `description` text COMMENT '活动描述',
  `start_time` int(11) NOT NULL COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL COMMENT '活动结束时间',
  `is_end` tinyint(1) DEFAULT '0' COMMENT '是否已结束',
  `group` varchar(255) DEFAULT NULL COMMENT '适用范围',
  `prom_img` varchar(150) DEFAULT NULL COMMENT '活动宣传图片',
  `buy_limit` int(10) DEFAULT NULL COMMENT '每人限购数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_prom_goods
-- ----------------------------

-- ----------------------------
-- Table structure for kc_prom_goods_item
-- ----------------------------
DROP TABLE IF EXISTS `kc_prom_goods_item`;
CREATE TABLE `kc_prom_goods_item` (
  `prom_id` int(10) unsigned NOT NULL COMMENT '活动id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `item_id` int(11) NOT NULL COMMENT '商品规格id',
  `goods_name` varchar(120) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `image` varchar(255) DEFAULT NULL COMMENT '商品图片'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_prom_goods_item
-- ----------------------------

-- ----------------------------
-- Table structure for kc_prom_order
-- ----------------------------
DROP TABLE IF EXISTS `kc_prom_order`;
CREATE TABLE `kc_prom_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '促销明细id',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '活动名称',
  `type` int(2) NOT NULL DEFAULT '1' COMMENT '活动类型 0 满额打折 1. 满额优惠金额  2 满额送积分 3 满额送优惠券',
  `condition_money` float(10,2) DEFAULT '0.00' COMMENT '满足金额数',
  `expression` varchar(100) DEFAULT NULL COMMENT '优惠金额',
  `description` text COMMENT '活动描述',
  `start_time` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `is_close` tinyint(1) NOT NULL DEFAULT '1' COMMENT '开启状态 ：0 否  1是',
  `group` varchar(255) DEFAULT NULL COMMENT '适用范围',
  `use_end_time` int(11) DEFAULT NULL COMMENT '券使用截至时间',
  `send_time` int(11) DEFAULT NULL COMMENT '送放时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用状态 0：无效 1：有效',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_prom_order
-- ----------------------------

-- ----------------------------
-- Table structure for kc_rebate_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_rebate_log`;
CREATE TABLE `kc_rebate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分成记录表',
  `user_id` int(11) DEFAULT '0' COMMENT '获佣用户',
  `buy_user_id` int(11) DEFAULT '0' COMMENT '购买人id',
  `nickname` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '购买人名称',
  `order_sn` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '订单编号',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单商品总额',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '获佣金额',
  `level` tinyint(1) DEFAULT '1' COMMENT '获佣用户级别',
  `create_time` int(11) DEFAULT '0' COMMENT '分成记录生成时间',
  `confirm` int(11) DEFAULT '0' COMMENT '确定收货时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '0未付款,1已付款, 2等待分成(已收货) 3已分成, 4已取消',
  `confirm_time` int(11) DEFAULT '0' COMMENT '确定分成或者取消时间',
  `remark` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '如果是取消, 有取消备注',
  `detail` varchar(1024) DEFAULT NULL COMMENT '记录该笔佣金中来自每个商品的分佣详情',
  `type` tinyint(1) DEFAULT '0' COMMENT '佣金类型 1商品分成 2推荐合作 3业绩分成',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1048 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_rebate_log
-- ----------------------------

-- ----------------------------
-- Table structure for kc_recharge
-- ----------------------------
DROP TABLE IF EXISTS `kc_recharge`;
CREATE TABLE `kc_recharge` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `nickname` varchar(50) DEFAULT NULL COMMENT '会员昵称',
  `order_sn` varchar(30) NOT NULL DEFAULT '' COMMENT '充值单号',
  `account` decimal(10,2) DEFAULT '0.00' COMMENT '充值金额',
  `ctime` int(11) DEFAULT NULL COMMENT '充值时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `pay_code` varchar(20) DEFAULT NULL,
  `pay_name` varchar(80) DEFAULT NULL COMMENT '支付方式',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '充值状态0:待支付 1:充值成功 2:交易关闭',
  `buy_vip` tinyint(1) DEFAULT '0' COMMENT '是否为VIP充值，1是',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for kc_region
-- ----------------------------
DROP TABLE IF EXISTS `kc_region`;
CREATE TABLE `kc_region` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(32) DEFAULT NULL COMMENT '地区名称',
  `level` tinyint(4) DEFAULT '0' COMMENT '地区等级 分省市县区',
  `parent_id` int(10) DEFAULT NULL COMMENT '父id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47503 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_region
-- ----------------------------

-- ----------------------------
-- Table structure for kc_region2
-- ----------------------------
DROP TABLE IF EXISTS `kc_region2`;
CREATE TABLE `kc_region2` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(20) NOT NULL COMMENT '地区名称',
  `parent_id` int(11) DEFAULT NULL COMMENT '父id',
  `level` tinyint(1) DEFAULT NULL COMMENT '地区等级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3524 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of kc_region2
-- ----------------------------

-- ----------------------------
-- Table structure for kc_remittance
-- ----------------------------
DROP TABLE IF EXISTS `kc_remittance`;
CREATE TABLE `kc_remittance` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分销用户转账记录表',
  `user_id` int(11) DEFAULT '0' COMMENT '汇款的用户id',
  `bank_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '收款银行名称',
  `account_bank` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '银行账号',
  `account_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '开户人名称',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '汇款金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '0汇款失败 1汇款成功',
  `handle_time` int(11) DEFAULT '0' COMMENT '处理时间',
  `create_time` int(11) DEFAULT '0' COMMENT '申请时间',
  `remark` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '汇款备注',
  `admin_id` int(11) DEFAULT '0' COMMENT '处理管理员id',
  `withdrawals_id` int(11) DEFAULT '0' COMMENT '提现申请表id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_remittance
-- ----------------------------

-- ----------------------------
-- Table structure for kc_reply
-- ----------------------------
DROP TABLE IF EXISTS `kc_reply`;
CREATE TABLE `kc_reply` (
  `reply_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '回复id',
  `comment_id` int(10) NOT NULL COMMENT '评论id：关联评论表',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父类id，0为最顶级',
  `content` text NOT NULL COMMENT '回复内容',
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '回复人的昵称',
  `to_name` varchar(255) NOT NULL DEFAULT '' COMMENT '被回复人的昵称',
  `deleted` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0' COMMENT '未删除0；删除：1',
  `reply_time` int(10) unsigned NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`reply_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_reply
-- ----------------------------

-- ----------------------------
-- Table structure for kc_return_deposit
-- ----------------------------
DROP TABLE IF EXISTS `kc_return_deposit`;
CREATE TABLE `kc_return_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退货申请表id自增',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `order_sn` varchar(1024) CHARACTER SET utf8 DEFAULT '' COMMENT '订单编号',
  `addtime` int(11) DEFAULT '0' COMMENT '申请时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '-2用户取消-1不同意0待审核1通过2已发货3已收货4换货完成5退款完成',
  `remark` varchar(1024) CHARACTER SET utf8 DEFAULT '' COMMENT '客服备注',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `refund_money` decimal(10,2) DEFAULT '0.00' COMMENT '退还金额',
  `refund_mark` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '退款备注',
  `refund_time` int(11) DEFAULT '0' COMMENT '退款时间',
  `checktime` int(11) DEFAULT NULL COMMENT '卖家审核时间',
  `canceltime` int(11) DEFAULT NULL COMMENT '用户取消时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_return_deposit
-- ----------------------------

-- ----------------------------
-- Table structure for kc_return_goods
-- ----------------------------
DROP TABLE IF EXISTS `kc_return_goods`;
CREATE TABLE `kc_return_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退货申请表id自增',
  `rec_id` int(11) DEFAULT '0' COMMENT 'order_goods表id',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `order_sn` varchar(1024) CHARACTER SET utf8 DEFAULT '' COMMENT '订单编号',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品id',
  `goods_num` int(10) DEFAULT '1' COMMENT '退货数量',
  `type` tinyint(1) DEFAULT '0' COMMENT '0仅退款 1退货退款 2换货',
  `reason` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '退换货原因',
  `describe` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '问题描述',
  `imgs` varchar(512) CHARACTER SET utf8 DEFAULT '' COMMENT '拍照图片路径',
  `addtime` int(11) DEFAULT '0' COMMENT '申请时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '-2用户取消-1不同意0待审核1通过2已发货3已收货4换货完成5退款完成',
  `remark` varchar(1024) CHARACTER SET utf8 DEFAULT '' COMMENT '客服备注',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `spec_key` varchar(64) CHARACTER SET utf8 DEFAULT '' COMMENT '商品规格key 对应sd_spec_goods_price 表',
  `seller_delivery` text CHARACTER SET utf8 COMMENT '换货服务，卖家重新发货信息',
  `refund_money` decimal(10,2) DEFAULT '0.00' COMMENT '退还金额',
  `refund_deposit` decimal(10,2) DEFAULT '0.00' COMMENT '退还余额',
  `refund_integral` int(11) DEFAULT '0' COMMENT '退还积分',
  `refund_type` tinyint(1) DEFAULT '0' COMMENT '退款类型',
  `refund_mark` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '退款备注',
  `refund_time` int(11) DEFAULT '0' COMMENT '退款时间',
  `is_receive` tinyint(4) DEFAULT '0' COMMENT '申请售后时是否收到货物',
  `delivery` text CHARACTER SET utf8 COMMENT '用户发货信息',
  `checktime` int(11) DEFAULT NULL COMMENT '卖家审核时间',
  `receivetime` int(11) DEFAULT NULL COMMENT '卖家收货时间',
  `canceltime` int(11) DEFAULT NULL COMMENT '用户取消时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_return_goods
-- ----------------------------

-- ----------------------------
-- Table structure for kc_search_word
-- ----------------------------
DROP TABLE IF EXISTS `kc_search_word`;
CREATE TABLE `kc_search_word` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '搜索表ID',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '搜索关键词，商品关键词',
  `pinyin_full` varchar(255) NOT NULL DEFAULT '' COMMENT '拼音全拼',
  `pinyin_simple` varchar(255) NOT NULL DEFAULT '' COMMENT '拼音简写',
  `search_num` int(8) NOT NULL DEFAULT '0' COMMENT '搜索次数',
  `goods_num` int(8) NOT NULL DEFAULT '0' COMMENT '商品数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索关键词表';

-- ----------------------------
-- Records of kc_search_word
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shipping
-- ----------------------------
DROP TABLE IF EXISTS `kc_shipping`;
CREATE TABLE `kc_shipping` (
  `shipping_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '物流公司id',
  `shipping_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `shipping_code` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司编码',
  `is_open` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `shipping_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '物流描述',
  `shipping_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司logo',
  `template_width` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运单模板宽度',
  `template_height` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运单模板高度',
  `template_offset_x` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运单模板左偏移量',
  `template_offset_y` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运单模板上偏移量',
  `template_img` varchar(255) NOT NULL DEFAULT '' COMMENT '运单模板图片',
  `template_html` text NOT NULL COMMENT '打印项偏移校正',
  PRIMARY KEY (`shipping_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_shipping
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shipping_area
-- ----------------------------
DROP TABLE IF EXISTS `kc_shipping_area`;
CREATE TABLE `kc_shipping_area` (
  `shipping_area_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `shipping_area_name` varchar(150) NOT NULL DEFAULT '' COMMENT '配送区域名称',
  `shipping_code` varchar(50) NOT NULL DEFAULT '0' COMMENT '物流id',
  `config` text NOT NULL COMMENT '配置首重续重等...序列化存储',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '是否默认',
  PRIMARY KEY (`shipping_area_id`),
  KEY `shipping_id` (`shipping_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_shipping_area
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shop
-- ----------------------------
DROP TABLE IF EXISTS `kc_shop`;
CREATE TABLE `kc_shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '门店索引id',
  `shop_name` varchar(50) NOT NULL DEFAULT '' COMMENT '门店名称',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '会员名称',
  `shopper_name` varchar(50) NOT NULL DEFAULT '' COMMENT '店主卖家用户名',
  `province_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '门店所在省份ID',
  `city_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '门店所在城市ID',
  `district_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '门店所在地区ID',
  `shop_address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地区',
  `longitude` decimal(10,7) NOT NULL DEFAULT '0.0000000' COMMENT '门店地址经度',
  `latitude` decimal(10,7) NOT NULL DEFAULT '0.0000000' COMMENT '门店地址纬度',
  `shop_zip` varchar(10) NOT NULL DEFAULT '' COMMENT '邮政编码',
  `suppliers_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '供应商id，0表示没有',
  `shop_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '门店状态，0关闭，1开启',
  `work_start_time` varchar(10) NOT NULL DEFAULT '' COMMENT '每天营业起始时间',
  `work_end_time` varchar(10) NOT NULL DEFAULT '' COMMENT '每天营业截止时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开店时间',
  `shop_phone_code` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话区号',
  `shop_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '商家电话',
  `monday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期一是否营业,0不是,1是',
  `tuesday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期二是否营业，0不是1是',
  `wednesday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期三是否营业，0不是1是',
  `thursday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期四是否营业，0不是1是',
  `friday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期五是否营业，0不是1是',
  `saturday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期六是否营业，0不是1是',
  `sunday` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星期日是否营业，0不是1是',
  `deleted` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0' COMMENT '未删除0，已删除1',
  `shop_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`shop_id`),
  KEY `store_name` (`shop_name`) USING BTREE,
  KEY `store_state` (`shop_status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店自提点表';

-- ----------------------------
-- Records of kc_shop
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shop_images
-- ----------------------------
DROP TABLE IF EXISTS `kc_shop_images`;
CREATE TABLE `kc_shop_images` (
  `shop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `image_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_shop_images
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shop_order
-- ----------------------------
DROP TABLE IF EXISTS `kc_shop_order`;
CREATE TABLE `kc_shop_order` (
  `shop_order_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '提货核销码。主键',
  `order_id` mediumint(8) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `shop_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `take_time` datetime NOT NULL COMMENT '提货时间',
  `is_write_off` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否核销。0未核销，1已核销',
  `write_off_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '核销时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录插入时间',
  PRIMARY KEY (`shop_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店订单表';

-- ----------------------------
-- Records of kc_shop_order
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shopper
-- ----------------------------
DROP TABLE IF EXISTS `kc_shopper`;
CREATE TABLE `kc_shopper` (
  `shopper_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店id',
  `shopper_name` varchar(50) NOT NULL DEFAULT '' COMMENT '门店账号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店Id',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`shopper_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店自提点管理员表';

-- ----------------------------
-- Records of kc_shopper
-- ----------------------------

-- ----------------------------
-- Table structure for kc_shopper_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_shopper_log`;
CREATE TABLE `kc_shopper_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `log_content` varchar(50) NOT NULL DEFAULT '' COMMENT '日志内容',
  `log_time` int(10) unsigned NOT NULL COMMENT '日志时间',
  `log_shopper_id` int(10) unsigned NOT NULL COMMENT '卖家编号',
  `log_shopper_name` varchar(50) NOT NULL DEFAULT '' COMMENT '门店帐号',
  `log_shop_id` int(10) unsigned NOT NULL COMMENT '门店id',
  `log_shopper_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '门店ip',
  `log_url` varchar(50) NOT NULL DEFAULT '' COMMENT '日志url',
  `log_state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '日志状态(0-失败 1-成功)',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店自提点管理员日志';

-- ----------------------------
-- Records of kc_shopper_log
-- ----------------------------

-- ----------------------------
-- Table structure for kc_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_sms_log`;
CREATE TABLE `kc_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机号',
  `session_id` varchar(128) DEFAULT '' COMMENT 'session_id',
  `add_time` int(11) DEFAULT '0' COMMENT '发送时间',
  `code` varchar(10) DEFAULT '' COMMENT '验证码',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '发送状态,1:成功,0:失败',
  `msg` varchar(255) DEFAULT NULL COMMENT '短信内容',
  `scene` int(1) DEFAULT '0' COMMENT '发送场景,1:用户注册,2:找回密码,3:客户下单,4:客户支付,5:商家发货,6:身份验证',
  `error_msg` text COMMENT '发送短信异常内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_sms_log
-- ----------------------------

-- ----------------------------
-- Table structure for kc_sms_template
-- ----------------------------
DROP TABLE IF EXISTS `kc_sms_template`;
CREATE TABLE `kc_sms_template` (
  `tpl_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `sms_sign` varchar(50) NOT NULL DEFAULT '' COMMENT '短信签名',
  `sms_tpl_code` varchar(100) NOT NULL DEFAULT '' COMMENT '短信模板ID',
  `tpl_content` varchar(512) NOT NULL DEFAULT '' COMMENT '发送短信内容',
  `send_scene` varchar(100) NOT NULL DEFAULT '' COMMENT '短信发送场景',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_sms_template
-- ----------------------------

-- ----------------------------
-- Table structure for kc_spec
-- ----------------------------
DROP TABLE IF EXISTS `kc_spec`;
CREATE TABLE `kc_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格表',
  `type_id` int(11) DEFAULT '0' COMMENT '规格类型',
  `name` varchar(55) DEFAULT NULL COMMENT '规格名称',
  `order` int(11) DEFAULT '50' COMMENT '排序',
  `is_upload_image` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可上传规格图.0不可，1可以',
  `search_index` tinyint(1) DEFAULT '1' COMMENT '是否需要检索：1是，0否',
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_spec
-- ----------------------------

-- ----------------------------
-- Table structure for kc_spec_goods_price
-- ----------------------------
DROP TABLE IF EXISTS `kc_spec_goods_price`;
CREATE TABLE `kc_spec_goods_price` (
  `item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格商品id',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品id',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '规格键名',
  `key_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '规格键名中文',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `cost_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `commission` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '佣金用于分销分成',
  `store_count` int(11) unsigned DEFAULT '10' COMMENT '库存数量',
  `bar_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '商品条形码',
  `sku` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT 'SKU',
  `spec_img` varchar(255) DEFAULT NULL COMMENT '规格商品主图',
  `prom_id` int(10) DEFAULT '0' COMMENT '活动id',
  `prom_type` tinyint(2) DEFAULT '0' COMMENT '参加活动类型',
  PRIMARY KEY (`item_id`),
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=400 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_spec_goods_price
-- ----------------------------

-- ----------------------------
-- Table structure for kc_spec_image
-- ----------------------------
DROP TABLE IF EXISTS `kc_spec_image`;
CREATE TABLE `kc_spec_image` (
  `goods_id` int(11) DEFAULT '0' COMMENT '商品规格图片表id',
  `spec_image_id` int(11) DEFAULT '0' COMMENT '规格项id',
  `src` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '商品规格图片路径'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_spec_image
-- ----------------------------

-- ----------------------------
-- Table structure for kc_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `kc_spec_item`;
CREATE TABLE `kc_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格项id',
  `spec_id` int(11) DEFAULT NULL COMMENT '规格id',
  `item` varchar(54) DEFAULT NULL COMMENT '规格项',
  `order_index` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_spec_item
-- ----------------------------

-- ----------------------------
-- Table structure for kc_stock_log
-- ----------------------------
DROP TABLE IF EXISTS `kc_stock_log`;
CREATE TABLE `kc_stock_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `goods_name` varchar(100) DEFAULT NULL COMMENT '商品名称',
  `goods_spec` varchar(50) DEFAULT NULL COMMENT '商品规格',
  `order_sn` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `muid` int(11) DEFAULT NULL COMMENT '操作用户ID',
  `stock` int(11) DEFAULT NULL COMMENT '更改库存',
  `ctime` int(11) DEFAULT NULL COMMENT '操作时间',
  `change_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '更改操作类型 （默认）0订单出库 1商品录入 2退货入库 3盘点更改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1412 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_stock_log
-- ----------------------------
INSERT INTO `kc_stock_log` VALUES ('1406', '243', '搞笑表演', null, '', '1', '100', '1556159530', '1');
INSERT INTO `kc_stock_log` VALUES ('1407', '244', '口才视频', null, '', '1', '5', '1556160268', '1');
INSERT INTO `kc_stock_log` VALUES ('1408', '245', '搞笑表演2', null, '', '1', '20', '1556174573', '1');
INSERT INTO `kc_stock_log` VALUES ('1409', '246', '什么都有的商品', null, '', '1', '50', '1556248965', '1');
INSERT INTO `kc_stock_log` VALUES ('1410', '246', '什么都有的商品', null, '', '1', '0', '1556350574', '3');
INSERT INTO `kc_stock_log` VALUES ('1411', '245', '搞笑表演2', null, '', '1', '0', '1556355351', '3');

-- ----------------------------
-- Table structure for kc_storage
-- ----------------------------
DROP TABLE IF EXISTS `kc_storage`;
CREATE TABLE `kc_storage` (
  `storage_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `storage_name` varchar(128) NOT NULL COMMENT '仓储名称',
  `is_open` tinyint(1) unsigned DEFAULT '1' COMMENT '仓储是否启用  0不启用  1启用',
  `province_id` int(11) unsigned NOT NULL COMMENT '省id',
  `city_id` int(11) unsigned NOT NULL COMMENT '市id',
  `district_id` int(11) unsigned NOT NULL COMMENT '区id',
  `address` varchar(255) NOT NULL COMMENT '仓储详细地址',
  `name` varchar(120) NOT NULL COMMENT '仓储负责人姓名',
  `mobile` char(15) NOT NULL COMMENT '仓储负责人联系电话',
  `capacity` int(11) unsigned NOT NULL COMMENT '仓储容量(前台取用单位立方米)',
  PRIMARY KEY (`storage_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_storage
-- ----------------------------

-- ----------------------------
-- Table structure for kc_suppliers
-- ----------------------------
DROP TABLE IF EXISTS `kc_suppliers`;
CREATE TABLE `kc_suppliers` (
  `suppliers_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '供应商ID',
  `suppliers_name` varchar(255) NOT NULL DEFAULT '' COMMENT '供应商名称',
  `suppliers_desc` mediumtext NOT NULL COMMENT '供应商描述',
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '供应商状态',
  `suppliers_contacts` varchar(255) NOT NULL DEFAULT '' COMMENT '供应商联系人',
  `suppliers_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '供应商电话',
  `province_id` int(10) unsigned DEFAULT NULL COMMENT '所在省份id',
  `city_id` int(10) unsigned DEFAULT NULL COMMENT '所在城市id',
  PRIMARY KEY (`suppliers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for kc_system_article
-- ----------------------------
DROP TABLE IF EXISTS `kc_system_article`;
CREATE TABLE `kc_system_article` (
  `doc_id` mediumint(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `doc_code` varchar(255) NOT NULL COMMENT '调用标识码',
  `doc_title` varchar(255) NOT NULL COMMENT '标题',
  `doc_content` text NOT NULL COMMENT '内容',
  `doc_time` int(10) unsigned NOT NULL COMMENT '添加时间/修改时间',
  PRIMARY KEY (`doc_id`),
  UNIQUE KEY `doc_code` (`doc_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统文章表';

-- ----------------------------
-- Records of kc_system_article
-- ----------------------------

-- ----------------------------
-- Table structure for kc_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `kc_system_menu`;
CREATE TABLE `kc_system_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名字',
  `group` varchar(20) DEFAULT NULL COMMENT '所属分组',
  `right` text COMMENT '权限码(控制器+动作)',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '删除状态 1删除,0正常',
  `type` tinyint(2) DEFAULT '0' COMMENT '所属模块类型 0admin 1home 2mobile 3api',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_system_menu
-- ----------------------------

-- ----------------------------
-- Table structure for kc_system_menu1
-- ----------------------------
DROP TABLE IF EXISTS `kc_system_menu1`;
CREATE TABLE `kc_system_menu1` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名字',
  `group` varchar(20) DEFAULT NULL COMMENT '所属分组',
  `right` text COMMENT '权限码(控制器+动作)',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '删除状态 1删除,0正常',
  `type` tinyint(2) DEFAULT '0' COMMENT '所属模块类型 0admin 1home 2mobile 3api',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_system_menu1
-- ----------------------------

-- ----------------------------
-- Table structure for kc_system_module
-- ----------------------------
DROP TABLE IF EXISTS `kc_system_module`;
CREATE TABLE `kc_system_module` (
  `mod_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `module` enum('top','menu','module') DEFAULT 'module',
  `level` tinyint(1) DEFAULT '3',
  `ctl` varchar(20) DEFAULT '',
  `act` varchar(30) DEFAULT '',
  `title` varchar(20) DEFAULT '',
  `visible` tinyint(1) DEFAULT '1',
  `parent_id` smallint(6) DEFAULT '0',
  `orderby` smallint(6) DEFAULT '50',
  `icon` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_system_module
-- ----------------------------

-- ----------------------------
-- Table structure for kc_team_activity
-- ----------------------------
DROP TABLE IF EXISTS `kc_team_activity`;
CREATE TABLE `kc_team_activity` (
  `team_id` int(10) NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL DEFAULT '' COMMENT '拼团活动标题',
  `team_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '拼团活动类型,0分享团1佣金团2抽奖团',
  `time_limit` int(11) NOT NULL DEFAULT '0' COMMENT '成团有效期。单位（秒)',
  `needer` int(10) NOT NULL DEFAULT '2' COMMENT '需要成团人数',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '团长佣金',
  `stock_limit` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖限量',
  `buy_limit` smallint(4) NOT NULL DEFAULT '0' COMMENT '单次团购买限制数0为不限制',
  `sales_sum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已拼多少件',
  `virtual_num` int(10) NOT NULL DEFAULT '0' COMMENT '虚拟销售基数',
  `share_title` varchar(100) NOT NULL COMMENT '分享标题',
  `share_desc` varchar(255) NOT NULL COMMENT '分享描述',
  `share_img` varchar(150) DEFAULT NULL COMMENT '分享图片',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭1正常',
  `is_lottery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已经抽奖.1是，0否',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除0否，1删除',
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='拼团活动表';

-- ----------------------------
-- Records of kc_team_activity
-- ----------------------------

-- ----------------------------
-- Table structure for kc_team_follow
-- ----------------------------
DROP TABLE IF EXISTS `kc_team_follow`;
CREATE TABLE `kc_team_follow` (
  `follow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `follow_user_id` int(11) DEFAULT '0' COMMENT '参团会员id',
  `follow_user_nickname` varchar(100) DEFAULT NULL COMMENT '参团会员昵称',
  `follow_user_head_pic` varchar(255) DEFAULT NULL COMMENT '会员头像',
  `follow_time` int(11) DEFAULT '0' COMMENT '参团时间',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `found_id` int(10) DEFAULT '0' COMMENT '开团ID',
  `found_user_id` int(11) DEFAULT '0' COMMENT '开团人user_id',
  `team_id` int(10) DEFAULT '0' COMMENT '拼团活动id',
  `status` tinyint(1) DEFAULT '0' COMMENT '参团状态0:待拼单(表示已下单但是未支付)1拼单成功(已支付)2成团成功3成团失败',
  `is_win` tinyint(1) DEFAULT '0' COMMENT '抽奖团是否中奖',
  PRIMARY KEY (`follow_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='参团表';

-- ----------------------------
-- Records of kc_team_follow
-- ----------------------------

-- ----------------------------
-- Table structure for kc_team_found
-- ----------------------------
DROP TABLE IF EXISTS `kc_team_found`;
CREATE TABLE `kc_team_found` (
  `found_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `found_time` int(11) DEFAULT '0' COMMENT '开团时间',
  `found_end_time` int(11) DEFAULT '0' COMMENT '成团截止时间',
  `user_id` int(11) DEFAULT '0' COMMENT '团长id',
  `team_id` int(10) DEFAULT '0' COMMENT '拼团活动id',
  `nickname` varchar(100) DEFAULT NULL COMMENT '团长用户名昵称',
  `head_pic` varchar(255) DEFAULT '' COMMENT '团长头像',
  `order_id` int(11) DEFAULT '0' COMMENT '团长订单id',
  `join` int(8) DEFAULT '1' COMMENT '已参团人数',
  `need` int(8) DEFAULT '1' COMMENT '需多少人成团',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '拼团价格',
  `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品原价',
  `status` tinyint(1) DEFAULT '0' COMMENT '拼团状态0:待开团(表示已下单但是未支付)1:已经开团(团长已支付)2:拼团成功,3拼团失败',
  `bonus_status` tinyint(1) DEFAULT '0' COMMENT '团长佣金领取状态：0无1领取',
  PRIMARY KEY (`found_id`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COMMENT='开团表';

-- ----------------------------
-- Records of kc_team_found
-- ----------------------------

-- ----------------------------
-- Table structure for kc_team_goods_item
-- ----------------------------
DROP TABLE IF EXISTS `kc_team_goods_item`;
CREATE TABLE `kc_team_goods_item` (
  `team_id` int(10) unsigned NOT NULL,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格ID',
  `team_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '拼团价',
  `sales_sum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已拼多少件',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除0否，1删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_team_goods_item
-- ----------------------------

-- ----------------------------
-- Table structure for kc_team_lottery
-- ----------------------------
DROP TABLE IF EXISTS `kc_team_lottery`;
CREATE TABLE `kc_team_lottery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '幸运儿手机',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `order_sn` varchar(50) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT '' COMMENT '幸运儿手机',
  `team_id` int(11) DEFAULT '0' COMMENT '拼团活动ID',
  `nickname` varchar(100) DEFAULT '' COMMENT '会员昵称',
  `head_pic` varchar(150) DEFAULT '' COMMENT '幸运儿头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_team_lottery
-- ----------------------------

-- ----------------------------
-- Table structure for kc_template_class
-- ----------------------------
DROP TABLE IF EXISTS `kc_template_class`;
CREATE TABLE `kc_template_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `type` tinyint(2) unsigned DEFAULT NULL COMMENT '类型  1行业  2风格',
  `name` varchar(64) DEFAULT NULL COMMENT '行业或风格名称',
  `sort_order` int(11) unsigned DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_template_class
-- ----------------------------

-- ----------------------------
-- Table structure for kc_topic
-- ----------------------------
DROP TABLE IF EXISTS `kc_topic`;
CREATE TABLE `kc_topic` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `topic_title` varchar(100) DEFAULT NULL COMMENT '专题标题',
  `topic_image` varchar(100) DEFAULT NULL COMMENT '专题封面',
  `topic_background_color` varchar(20) DEFAULT NULL COMMENT '专题背景颜色',
  `topic_background` varchar(100) DEFAULT NULL COMMENT '专题背景图',
  `topic_content` text COMMENT '专题详情',
  `topic_repeat` varchar(20) DEFAULT '' COMMENT '背景重复方式',
  `topic_state` tinyint(1) DEFAULT '1' COMMENT '专题状态1-草稿、2-已发布',
  `topic_margin_top` tinyint(3) DEFAULT '0' COMMENT '正文距顶部距离',
  `ctime` int(11) DEFAULT NULL COMMENT '专题创建时间',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_topic
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_address
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_address`;
CREATE TABLE `kc_user_address` (
  `address_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `country` varchar(11) NOT NULL DEFAULT '0' COMMENT '国家',
  `province` varchar(11) NOT NULL DEFAULT '0' COMMENT '省份',
  `city` varchar(11) NOT NULL DEFAULT '0' COMMENT '城市',
  `district` varchar(11) NOT NULL DEFAULT '0' COMMENT '地区',
  `twon` varchar(11) DEFAULT '0' COMMENT '乡镇',
  `address` varchar(120) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zipcode` varchar(60) NOT NULL DEFAULT '' COMMENT '邮政编码',
  `mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '手机',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '默认收货地址 0 否 1 是',
  `longitude` decimal(10,7) NOT NULL DEFAULT '0.0000000' COMMENT '地址经度',
  `latitude` decimal(10,7) NOT NULL DEFAULT '0.0000000' COMMENT '地址纬度',
  `famale` tinyint(1) DEFAULT '1' COMMENT '性别 保密0 男1 女 2',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新地址时间',
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_agent
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_agent`;
CREATE TABLE `kc_user_agent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `level_id` int(50) DEFAULT NULL COMMENT '等级id',
  `add_time` varchar(50) DEFAULT NULL COMMENT '申请时间',
  `is_bond` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否缴纳保证金',
  `is_fund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首次合作金额是否缴纳',
  `bond_time` int(10) NOT NULL DEFAULT '0' COMMENT '保证金缴纳时间',
  `fund_time` int(10) unsigned NOT NULL COMMENT '合作金额缴纳时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '审核状态 0待审核 1已通过',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `mobile` (`is_fund`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='用户店铺信息表';

-- ----------------------------
-- Records of kc_user_agent
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_benfits
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_benfits`;
CREATE TABLE `kc_user_benfits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分润明细表id',
  `level_id` int(11) DEFAULT NULL COMMENT '用户等级id',
  `cengji` tinyint(1) DEFAULT NULL COMMENT '层级：下面的一代和二代',
  `points` int(11) DEFAULT NULL COMMENT '分润点数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_benfits
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_collection
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_collection`;
CREATE TABLE `kc_user_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户下载收集表',
  `mobile` varchar(11) DEFAULT '' COMMENT '用户手机号',
  `contact` varchar(32) DEFAULT '' COMMENT '联系人',
  `wkc_down` varchar(32) DEFAULT '' COMMENT '下载原因',
  `add_time` int(11) DEFAULT '0' COMMENT '申请时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_collection
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_distribution
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_distribution`;
CREATE TABLE `kc_user_distribution` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '分销会员id',
  `user_name` varchar(50) DEFAULT NULL COMMENT '会员昵称',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `goods_name` varchar(150) DEFAULT NULL COMMENT '商品名称',
  `cat_id` smallint(6) DEFAULT '0' COMMENT '商品分类ID',
  `brand_id` mediumint(8) DEFAULT '0' COMMENT '商品品牌',
  `share_num` int(10) DEFAULT '0' COMMENT '分享次数',
  `sales_num` int(11) DEFAULT '0' COMMENT '分销销量',
  `addtime` int(11) DEFAULT NULL COMMENT '加入个人分销库时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8 COMMENT='用户选择分销商品表';

-- ----------------------------
-- Records of kc_user_distribution
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_extend
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_extend`;
CREATE TABLE `kc_user_extend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT '0',
  `invoice_title` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '发票抬头',
  `taxpayer` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '纳税人识别号',
  `invoice_desc` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '不开发票/明细',
  `realname` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '真实姓名',
  `idcard` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '身份证号',
  `cash_alipay` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '提现支付宝号',
  `cash_unionpay` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '提现银行卡号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_user_extend
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_integral
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_integral`;
CREATE TABLE `kc_user_integral` (
  `ui_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '积分明细id',
  `ui_uid` int(11) DEFAULT NULL COMMENT '用户id',
  `ui_integral` float(12,0) DEFAULT '0' COMMENT '变动积分',
  `ui_remark` varchar(255) DEFAULT NULL COMMENT '明细备注',
  `ui_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `ui_deal_type` tinyint(1) DEFAULT NULL COMMENT '积分类型 （签到：1邀请好友:2 商城消费：3）',
  `ui_business_type` varchar(12) DEFAULT NULL COMMENT '业务类型（消费 签到 邀请）',
  `ui_business_code` varchar(12) DEFAULT NULL COMMENT '业务code 首字母大写( 消费 XF 签到 QD 邀请 YQ)',
  `ui_sn` varchar(255) DEFAULT NULL COMMENT '变动交易号',
  `ui_serial_num` varchar(100) DEFAULT NULL COMMENT '账单流水号',
  PRIMARY KEY (`ui_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_integral
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_label
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_label`;
CREATE TABLE `kc_user_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签名称',
  `label_name` char(30) NOT NULL COMMENT '标签名称',
  `label_order` tinyint(2) NOT NULL COMMENT '标签排序',
  `label_code` varchar(80) NOT NULL COMMENT '标签图片',
  `label_describe` varchar(255) DEFAULT NULL COMMENT '标签描述',
  `is_recommend` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否推荐:0=否,1=是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_label
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_level
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_level`;
CREATE TABLE `kc_user_level` (
  `level_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `level_name` varchar(30) DEFAULT NULL COMMENT '头衔名称',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '等级必要金额',
  `discount` smallint(2) DEFAULT '0' COMMENT '折扣',
  `describe` varchar(200) DEFAULT NULL COMMENT '头街 描述',
  `fixed_amount` decimal(2,0) DEFAULT NULL,
  `deposit` decimal(10,2) DEFAULT NULL COMMENT '保证金',
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_user_level
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_mechanism
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_mechanism`;
CREATE TABLE `kc_user_mechanism` (
  `mechanism_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '机构id',
  `company_name` varchar(66) NOT NULL COMMENT '公司名称',
  `social_code` varchar(100) NOT NULL COMMENT '社会信用代码',
  `auditing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否审核  0：待审核  1：审核通过  2：审核不通过',
  `username` char(20) NOT NULL COMMENT '真实姓名',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0：未删除 1：已删除',
  `idcard` varchar(100) NOT NULL COMMENT '身份证号',
  `phone` char(11) NOT NULL COMMENT '负责人的手机号码',
  `yinyep_img` varchar(255) NOT NULL COMMENT '营业执照照片',
  `idcard_img` varchar(255) NOT NULL COMMENT '手持身份证照片',
  `addtime` int(11) NOT NULL COMMENT '申请时间',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  PRIMARY KEY (`mechanism_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='机构表';

-- ----------------------------
-- Records of kc_user_mechanism
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_message
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_message`;
CREATE TABLE `kc_user_message` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户消息明细id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `message_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息id',
  `category` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '通知消息：0, 活动消息：1, 物流:2, 私信:3',
  `is_see` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否查看：0未查看, 1已查看',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户假删除标识,1:删除,0未删除',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `message_id` (`message_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=221 DEFAULT CHARSET=utf8 COMMENT='用户的消息表';

-- ----------------------------
-- Records of kc_user_message
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_msg_tpl
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_msg_tpl`;
CREATE TABLE `kc_user_msg_tpl` (
  `mmt_code` varchar(50) NOT NULL COMMENT '用户消息模板编号',
  `mmt_name` varchar(50) NOT NULL COMMENT '模板名称',
  `mmt_message_switch` tinyint(3) unsigned NOT NULL COMMENT '站内信接收开关',
  `mmt_message_content` varchar(255) NOT NULL COMMENT '站内信消息内容',
  `mmt_short_switch` tinyint(3) unsigned NOT NULL COMMENT '短信接收开关',
  `mmt_short_content` varchar(255) DEFAULT NULL COMMENT '短信接收内容',
  `mmt_short_sign` varchar(50) DEFAULT NULL COMMENT '短信签名',
  `mmt_short_code` varchar(50) DEFAULT NULL COMMENT '短信模板ID',
  `mmt_mail_switch` tinyint(3) unsigned NOT NULL COMMENT '邮件接收开关',
  `mmt_mail_subject` varchar(255) DEFAULT NULL COMMENT '邮件标题',
  `mmt_mail_content` text COMMENT '邮件内容',
  PRIMARY KEY (`mmt_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息模板';

-- ----------------------------
-- Records of kc_user_msg_tpl
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_sign
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_sign`;
CREATE TABLE `kc_user_sign` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `sign_total` int(11) DEFAULT '0' COMMENT '累计签到天数',
  `sign_count` int(11) DEFAULT '0' COMMENT '连续签到天数',
  `sign_last` char(11) DEFAULT '0' COMMENT '最后签到时间，时间格式20170907',
  `sign_time` text CHARACTER SET utf8 COMMENT '历史签到时间，以逗号隔开',
  `cumtrapz` int(11) DEFAULT '0' COMMENT '用户累计签到总积分',
  `this_month` int(6) DEFAULT NULL COMMENT '本月累计积分',
  `total_sign_integral` int(11) DEFAULT NULL COMMENT '签到总积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kc_user_sign
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_star
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_star`;
CREATE TABLE `kc_user_star` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '星级列表id',
  `team_total` int(11) DEFAULT NULL COMMENT '团队总人数',
  `first_lower_level` int(8) DEFAULT NULL,
  `second_achievement` varchar(11) DEFAULT NULL COMMENT '第二区业绩(团队内直线，第二大总业绩)',
  `weighting` varchar(10) DEFAULT NULL COMMENT '加权(总业绩达到获取提成)',
  `star_level` int(11) DEFAULT NULL COMMENT '星级等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of kc_user_star
-- ----------------------------

-- ----------------------------
-- Table structure for kc_user_store
-- ----------------------------
DROP TABLE IF EXISTS `kc_user_store`;
CREATE TABLE `kc_user_store` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `store_name` varchar(50) DEFAULT NULL COMMENT '店铺名',
  `true_name` varchar(50) DEFAULT NULL COMMENT '真名',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `store_img` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺图片',
  `store_time` int(10) unsigned NOT NULL COMMENT '开店时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `mobile` (`mobile`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户店铺信息表';

-- ----------------------------
-- Records of kc_user_store
-- ----------------------------

-- ----------------------------
-- Table structure for kc_users
-- ----------------------------
DROP TABLE IF EXISTS `kc_users`;
CREATE TABLE `kc_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `user_no` int(10) DEFAULT NULL COMMENT '用户编码',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '邮件',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `paypwd` varchar(32) DEFAULT NULL COMMENT '支付密码',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 保密 1 男 2 女',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户金额(元宝)',
  `frozen_money` decimal(10,2) DEFAULT '0.00' COMMENT '冻结金额',
  `distribut_money` decimal(10,2) DEFAULT '0.00' COMMENT '累积分佣金额',
  `underling_number` int(5) DEFAULT NULL COMMENT '用户下线总数',
  `pay_points` int(12) DEFAULT NULL COMMENT '消费积分(购物积分，签到积分，邀请好友)',
  `address_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_validated` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '验证手机 0 否 1是',
  `oauth` varchar(10) DEFAULT '' COMMENT '第三方来源 wx weibo alipay',
  `openid` varchar(100) DEFAULT NULL COMMENT '第三方唯一标示',
  `unionid` varchar(100) DEFAULT NULL COMMENT '关注公众号获取推送id',
  `head_pic` varchar(255) DEFAULT NULL COMMENT '头像',
  `province` int(6) DEFAULT '0' COMMENT '省份',
  `city` int(6) DEFAULT '0' COMMENT '市区',
  `district` int(6) DEFAULT '0' COMMENT '县',
  `email_validated` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '验证电子邮箱 0否 1是',
  `nickname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '第三方返回昵称',
  `level` tinyint(1) DEFAULT '1' COMMENT '会员等级 ',
  `discount` decimal(10,2) DEFAULT '1.00' COMMENT '会员折扣，默认1不享受',
  `total_amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费累计额度',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '是否被锁定冻结',
  `is_distribut` tinyint(1) DEFAULT '0' COMMENT '是否为分销商 0 否 1 是',
  `first_leader` int(11) DEFAULT '0' COMMENT '第一个上级',
  `second_leader` int(11) DEFAULT '0' COMMENT '第二个上级',
  `third_leader` int(11) DEFAULT '0' COMMENT '第三个上级',
  `token` varchar(64) DEFAULT '' COMMENT '用于app 授权类似于session_id',
  `message_mask` tinyint(1) NOT NULL DEFAULT '63' COMMENT '消息掩码',
  `push_id` varchar(30) NOT NULL DEFAULT '' COMMENT '推送id',
  `distribut_level` tinyint(2) DEFAULT '0' COMMENT '分销商等级',
  `is_vip` tinyint(1) DEFAULT '0' COMMENT '是否为VIP ：0不是，1是',
  `xcx_qrcode` varchar(255) DEFAULT NULL COMMENT '小程序专属二维码',
  `poster` varchar(255) DEFAULT NULL COMMENT '专属推广海报',
  `realname` varchar(50) DEFAULT NULL COMMENT '用户真实姓名',
  `idcard` varchar(100) DEFAULT NULL COMMENT '身份证号',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `deposit` decimal(10,2) DEFAULT '0.00' COMMENT '保证金',
  `withdrawal_money` decimal(10,2) DEFAULT '0.00' COMMENT '累计提现金额',
  `is_name_auth` tinyint(1) DEFAULT '0' COMMENT '身份证 未认证：0 认证中：1 已认证：2 已驳回：3',
  `u_status` tinyint(1) DEFAULT '1' COMMENT '用户状态 正常：1 禁用 ：2',
  `u_yuan_parent_id` int(11) DEFAULT NULL COMMENT '初始推荐人ID',
  `subscribe` tinyint(1) DEFAULT NULL COMMENT '订阅 关注公众号 1是关注，0 否',
  `token_express` int(11) DEFAULT NULL COMMENT 'token有效期时间',
  `ewm` varchar(255) DEFAULT NULL COMMENT '用户推广二维码',
  `update_time` int(15) DEFAULT NULL,
  `team_money` int(11) unsigned DEFAULT NULL COMMENT '团队总金额',
  PRIMARY KEY (`user_id`),
  KEY `email` (`email`) USING BTREE,
  KEY `underling_number` (`underling_number`) USING BTREE,
  KEY `mobile` (`mobile_validated`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `unionid` (`unionid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_users
-- ----------------------------
INSERT INTO `kc_users` VALUES ('1', null, '', '', null, '0', '0', '0.00', '0.00', '0.00', null, null, '0', '1556332778', '0', '', '', '', '0', '', 'oY67c4sB7nbZ2MScu6xkomyRHe_Y', null, 'https://wx.qlogo.cn/mmopen/vi_32/R4DicBy8rqem3vQ96pibhficLXkP3DgCGmnKRSEM3PnoDP5cb785hMOjOV4ZHwTHFrI2bXU6dfOiaQJZDUrJiaQmeyw/132', '0', '0', '0', '0', 'IT小龙', '1', '1.00', '0.00', '0', '0', '0', '0', '0', '30b77bfadc0da17ebb451a92963ff367', '63', '', '0', '0', null, null, null, null, null, '0.00', '0.00', '0', '1', null, null, '1564972778', null, null, null);

-- ----------------------------
-- Table structure for kc_virtual_shop
-- ----------------------------
DROP TABLE IF EXISTS `kc_virtual_shop`;
CREATE TABLE `kc_virtual_shop` (
  `user_id` int(11) DEFAULT NULL,
  `shop_name` varchar(100) DEFAULT NULL COMMENT '店铺名称',
  `shop_level` tinyint(1) DEFAULT '0' COMMENT '店铺等级',
  `shop_intro` text COMMENT '店铺介绍',
  `shop_logo` varchar(255) DEFAULT NULL COMMENT '店铺logo',
  `shop_phone` varchar(20) DEFAULT NULL,
  `shop_qq` varchar(20) DEFAULT NULL,
  `shop_theme` tinyint(1) DEFAULT '0' COMMENT '店铺模板风格'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商虚拟店铺表';

-- ----------------------------
-- Records of kc_virtual_shop
-- ----------------------------

-- ----------------------------
-- Table structure for kc_vr_order_code
-- ----------------------------
DROP TABLE IF EXISTS `kc_vr_order_code`;
CREATE TABLE `kc_vr_order_code` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '兑换码表索引id',
  `order_id` int(11) NOT NULL COMMENT '虚拟订单id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买家ID',
  `vr_code` varchar(18) NOT NULL DEFAULT '' COMMENT '兑换码',
  `vr_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '使用状态 0:(默认)未使用1:已使用2:已过期',
  `vr_usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额(结算)',
  `vr_indate` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `refund_lock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退款锁定状态:0为正常,1为锁定,2为同意,默认为0',
  `vr_invalid_refund` tinyint(4) NOT NULL DEFAULT '1' COMMENT '允许过期退款1是0否',
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='兑换码表';

-- ----------------------------
-- Records of kc_vr_order_code
-- ----------------------------

-- ----------------------------
-- Table structure for kc_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `kc_withdrawals`;
CREATE TABLE `kc_withdrawals` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '提现申请表',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `create_time` int(11) DEFAULT '0' COMMENT '申请时间',
  `check_time` int(11) DEFAULT '0' COMMENT '审核时间',
  `pay_time` int(11) DEFAULT '0' COMMENT '支付时间',
  `refuse_time` int(11) DEFAULT '0' COMMENT '拒绝时间',
  `bank_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '银行名称 如支付宝 微信 中国银行 农业银行等',
  `bank_card` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '银行账号或支付宝账号',
  `realname` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '真实姓名',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '提现备注',
  `taxfee` float(12,2) DEFAULT '0.00' COMMENT '税收手续费',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态：-2删除作废-1审核失败0申请中1审核通过2付款成功3付款失败',
  `pay_code` varchar(100) DEFAULT NULL COMMENT '付款对账流水号',
  `error_code` varchar(255) DEFAULT NULL COMMENT '付款失败错误代码',
  `check_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `truemoney` decimal(12,2) DEFAULT NULL COMMENT '实际到账金额',
  `type` int(10) DEFAULT NULL COMMENT '提现类型 微信账号：1 支付宝账号：2 银行卡：3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_withdrawals
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_img
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_img`;
CREATE TABLE `kc_wx_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `keyword` char(255) NOT NULL COMMENT '关键词',
  `desc` text NOT NULL COMMENT '简介',
  `pic` char(255) NOT NULL COMMENT '封面图片',
  `url` char(255) NOT NULL COMMENT '图文外链地址',
  `createtime` varchar(13) NOT NULL COMMENT '创建时间',
  `uptatetime` varchar(13) NOT NULL COMMENT '更新时间',
  `token` char(30) NOT NULL COMMENT 'token',
  `title` varchar(60) NOT NULL COMMENT '标题',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信图文';

-- ----------------------------
-- Records of kc_wx_img
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_keyword
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_keyword`;
CREATE TABLE `kc_wx_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微信关键词表',
  `keyword` char(255) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '对应表ID，如wx_reply的id',
  `type` varchar(30) NOT NULL COMMENT '关键词操作类型 auto_reply',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_keyword
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_material
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_material`;
CREATE TABLE `kc_wx_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '微信公众号素材',
  `media_id` varchar(64) DEFAULT '' COMMENT '微信媒体id',
  `type` varchar(10) NOT NULL COMMENT '素材类型：text、image、news、video',
  `data` text COMMENT 'json数据',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `key` char(32) DEFAULT NULL COMMENT '便于查询的key，现用于image',
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_material
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_menu
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_menu`;
CREATE TABLE `kc_wx_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) DEFAULT '1' COMMENT '菜单级别',
  `name` varchar(50) NOT NULL DEFAULT '',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `type` varchar(20) DEFAULT '' COMMENT '0 view 1 click',
  `value` varchar(255) DEFAULT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `pid` int(11) DEFAULT '0' COMMENT '上级菜单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_menu
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_msg
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_msg`;
CREATE TABLE `kc_wx_msg` (
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '系统用户ID',
  `titile` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sendtime` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `issend` tinyint(1) DEFAULT '0' COMMENT '0未发送1成功2失败',
  `sendtype` tinyint(1) DEFAULT '1' COMMENT '0单人1所有',
  PRIMARY KEY (`msgid`),
  KEY `uid` (`admin_id`) USING BTREE,
  KEY `createymd` (`sendtime`) USING BTREE,
  KEY `fake_id` (`titile`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_msg
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_news
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_news`;
CREATE TABLE `kc_wx_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图文子素材id',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `title` varchar(64) DEFAULT '' COMMENT '标题',
  `material_id` int(10) unsigned DEFAULT NULL COMMENT '图片素材id，一个图片为素材可包括几个子图文',
  `author` varchar(8) DEFAULT '' COMMENT '作者',
  `content` text COMMENT 'html内容',
  `digest` varchar(120) DEFAULT '' COMMENT '摘要',
  `thumb_url` text COMMENT '封面链接',
  `thumb_media_id` varchar(64) DEFAULT '' COMMENT '封面媒体id',
  `content_source_url` text COMMENT '原文链接',
  `show_cover_pic` int(1) DEFAULT '0' COMMENT '是否显示封面',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信图文';

-- ----------------------------
-- Records of kc_wx_news
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_reply
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_reply`;
CREATE TABLE `kc_wx_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '微信关键词回复表',
  `rule` varchar(32) DEFAULT NULL COMMENT '规则名',
  `update_time` int(10) unsigned DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT '回复类型keyword,default,follow',
  `msg_type` varchar(10) DEFAULT NULL COMMENT '回复消息类型text,news',
  `data` text COMMENT 'text使用该自动存储文本',
  `material_id` int(10) unsigned DEFAULT NULL COMMENT 'news、image的素材id等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_reply
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_text
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_text`;
CREATE TABLE `kc_wx_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `uname` varchar(90) NOT NULL COMMENT '用户名',
  `keyword` char(255) NOT NULL COMMENT '关键词',
  `precisions` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'precisions',
  `text` text NOT NULL COMMENT 'text',
  `createtime` varchar(13) NOT NULL COMMENT '创建时间',
  `updatetime` varchar(13) NOT NULL COMMENT '更新时间',
  `click` int(11) NOT NULL COMMENT '点击',
  `token` char(30) NOT NULL COMMENT 'token',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文本回复表';

-- ----------------------------
-- Records of kc_wx_text
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_tpl_msg
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_tpl_msg`;
CREATE TABLE `kc_wx_tpl_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '微信模板消息',
  `title` varchar(32) CHARACTER SET gbk DEFAULT '' COMMENT '模板标题',
  `template_sn` varchar(64) CHARACTER SET gbk DEFAULT '' COMMENT '模板编号',
  `template_id` varchar(64) CHARACTER SET gbk DEFAULT '' COMMENT '模板id',
  `remark` varchar(255) CHARACTER SET gbk DEFAULT '' COMMENT '留言',
  `is_use` tinyint(1) DEFAULT '0' COMMENT '该模板是否启用',
  `add_time` int(10) unsigned DEFAULT NULL COMMENT '添加模板的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kc_wx_tpl_msg
-- ----------------------------

-- ----------------------------
-- Table structure for kc_wx_user
-- ----------------------------
DROP TABLE IF EXISTS `kc_wx_user`;
CREATE TABLE `kc_wx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `uid` int(11) NOT NULL COMMENT 'uid',
  `wxname` varchar(60) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `aeskey` varchar(256) NOT NULL DEFAULT '' COMMENT 'aeskey',
  `encode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'encode',
  `appid` varchar(50) NOT NULL DEFAULT '' COMMENT 'appid',
  `appsecret` varchar(50) NOT NULL DEFAULT '' COMMENT 'appsecret',
  `wxid` varchar(64) NOT NULL DEFAULT '' COMMENT '公众号原始ID',
  `weixin` char(64) NOT NULL COMMENT '微信号',
  `headerpic` char(255) NOT NULL COMMENT '头像地址',
  `token` char(255) NOT NULL COMMENT 'token',
  `w_token` varchar(150) NOT NULL DEFAULT '' COMMENT '微信对接token',
  `create_time` int(11) NOT NULL COMMENT 'create_time',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  `tplcontentid` varchar(2) NOT NULL DEFAULT '' COMMENT '内容模版ID',
  `share_ticket` varchar(150) NOT NULL DEFAULT '' COMMENT '分享ticket',
  `share_dated` char(15) NOT NULL COMMENT 'share_dated',
  `authorizer_access_token` varchar(200) NOT NULL DEFAULT '' COMMENT 'authorizer_access_token',
  `authorizer_refresh_token` varchar(200) NOT NULL DEFAULT '' COMMENT 'authorizer_refresh_token',
  `authorizer_expires` char(10) NOT NULL COMMENT 'authorizer_expires',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `web_access_token` varchar(200) DEFAULT '' COMMENT ' 网页授权token',
  `web_refresh_token` varchar(200) DEFAULT '' COMMENT 'web_refresh_token',
  `web_expires` int(11) NOT NULL COMMENT '过期时间',
  `qr` varchar(200) NOT NULL DEFAULT '' COMMENT 'qr',
  `menu_config` text COMMENT '菜单',
  `wait_access` tinyint(1) DEFAULT '0' COMMENT '微信接入状态,0待接入1已接入',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `uid_2` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='微信公共帐号';

-- ----------------------------
-- Records of kc_wx_user
-- ----------------------------
