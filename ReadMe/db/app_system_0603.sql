/*
 Navicat MySQL Data Transfer

 Source Server         : nginx
 Source Server Version : 50629
 Source Host           : localhost
 Source Database       : app_system

 Target Server Version : 50629
 File Encoding         : utf-8

 Date: 06/03/2016 09:55:13 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` char(50) NOT NULL DEFAULT '''''' COMMENT '用户名',
  `passwd` char(32) NOT NULL DEFAULT '''''' COMMENT '用户密码',
  `sex` set('男','女','保密') NOT NULL DEFAULT '保密' COMMENT '性别',
  `email` char(50) NOT NULL DEFAULT '''''' COMMENT '用户邮箱',
  `phone` char(12) NOT NULL DEFAULT '''''' COMMENT '用户手机',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `create_ip` char(16) NOT NULL DEFAULT '''''' COMMENT '注册ip',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `login_ip` char(16) NOT NULL DEFAULT '''''' COMMENT '登陆ip',
  `safe_question` tinyint(4) DEFAULT '0' COMMENT '安全问题',
  `safe_answer` char(45) DEFAULT NULL COMMENT '安全问题答案',
  `face` char(45) NOT NULL DEFAULT '''''' COMMENT '用户图像',
  `scores` mediumint(8) NOT NULL DEFAULT '10' COMMENT '用户积分',
  `group` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户等级',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '用户金币数',
  `is_lock` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `nick_name` varchar(45) NOT NULL DEFAULT '''''',
  `collect` varchar(255) NOT NULL DEFAULT '''''',
  `login_count` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid_UNIQUE` (`uid`),
  UNIQUE KEY `uusername_UNIQUE` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
--  Table structure for `auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_group_copy`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_copy`;
CREATE TABLE `auth_group_copy` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` smallint(5) NOT NULL COMMENT '父级ID',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_rule_copy`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule_copy`;
CREATE TABLE `auth_rule_copy` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` smallint(5) NOT NULL COMMENT '父级ID',
  `sort` smallint(5) NOT NULL COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `bt_app_version`
-- ----------------------------
DROP TABLE IF EXISTS `bt_app_version`;
CREATE TABLE `bt_app_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `version` varchar(30) DEFAULT NULL,
  `download_url` varchar(255) DEFAULT NULL,
  `download_url2` varchar(255) DEFAULT NULL,
  `descript` text COMMENT 'app描述',
  `update_descript` text COMMENT '更新描述、更新日志',
  `md5` varchar(32) DEFAULT NULL,
  `update_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '更新类型：强制/非强制',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `file_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '文件/包类型：deb、ipa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `bt_index_list`
-- ----------------------------
DROP TABLE IF EXISTS `bt_index_list`;
CREATE TABLE `bt_index_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ico_url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `title` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `describe` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `is_show` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='首页导航列表';

-- ----------------------------
--  Table structure for `data_summary`
-- ----------------------------
DROP TABLE IF EXISTS `data_summary`;
CREATE TABLE `data_summary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT '0000-00-00',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0',
  `camp_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_source` tinyint(2) DEFAULT NULL,
  `country` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `click` int(11) unsigned NOT NULL DEFAULT '0',
  `download` int(11) unsigned NOT NULL DEFAULT '0',
  `install` int(11) unsigned NOT NULL DEFAULT '0',
  `cake_active` int(11) unsigned NOT NULL DEFAULT '0',
  `h_click` int(11) unsigned NOT NULL DEFAULT '0',
  `h_active` int(11) unsigned NOT NULL DEFAULT '0',
  `analog_click` int(11) unsigned NOT NULL DEFAULT '0',
  `payout_amount` float(6,2) NOT NULL DEFAULT '0.00' COMMENT '支付价格',
  `name` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `category` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `countries` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `CP` float(3,2) NOT NULL DEFAULT '0.00' COMMENT 'cake激活比',
  `AP` float(3,2) NOT NULL DEFAULT '0.00' COMMENT 'appsotre 激活/点击比\n',
  `TA` int(11) NOT NULL DEFAULT '0' COMMENT '总激活量',
  `TP` float(3,2) NOT NULL DEFAULT '0.00' COMMENT '总激活比',
  `income` float(6,2) NOT NULL DEFAULT '0.00' COMMENT '收入',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1168 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='数据汇总表：点击、下载、安装、激活';

-- ----------------------------
--  Table structure for `data_summary_position`
-- ----------------------------
DROP TABLE IF EXISTS `data_summary_position`;
CREATE TABLE `data_summary_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT '0000-00-00',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0',
  `camp_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_source` tinyint(2) DEFAULT NULL,
  `country` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `click` int(11) unsigned NOT NULL DEFAULT '0',
  `download` int(11) unsigned NOT NULL DEFAULT '0',
  `install` int(11) unsigned NOT NULL DEFAULT '0',
  `position` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `category` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `countries` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32815 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='数据汇总表：cake位置统计汇总表';

SET FOREIGN_KEY_CHECKS = 1;
